<?php

namespace App\Models\Record_room;

use CodeIgniter\Model;

class FileTrap_model extends Model
{

    public function case_types()
    {
        $builder = $this->db->table('master.casetype');
        $builder->where('is_deleted', false)
            ->where('casecode !=', 9999)
            ->orderBy('casecode', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function checkUserCaseTypeRole($usercode = null, $userType = null)
    {
        $builder = $this->db->table('master.rr_da_case_distribution r')
            ->select("STRING_AGG(DISTINCT casehead, ', ') as casehead")
            ->join('fil_trap_users u', 'r.user_code = u.usercode')
            ->where('u.usertype', (int)$userType)
            ->where('r.user_code', $usercode)
            ->where('u.display', 'Y')
            ->where('r.display', 'Y')
            ->where('r.casetype', 0)
            ->groupBy('u.usertype, r.user_code');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }



    public function getCaseType($diaryNo)
    {
        $builder = $this->db->table('main');
        $builder->select('case_grp')
            ->where('diary_no', $diaryNo);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getFileTrapUsersRole($usercode = null)
    {
        if ($usercode !== null) {
            $usercode = (string)$usercode;
        }

        $builder = $this->db->table('fil_trap_users a');

        $builder->select('a.usertype, b.type_name, b.disp_flag, 
                          STRING_AGG(DISTINCT m.ref_hall_no, \', \') AS ref_hall_no, 
                          STRING_AGG(DISTINCT h.Description, \', \') AS Description')
            ->join('master.usertype b', 'a.usertype = b.id')
            ->join('master.rr_user_hall_mapping m', 'a.usercode = m.usercode')
            ->join('master.ref_rr_hall h', 'CAST(m.ref_hall_no AS integer) = h.hall_no')  // Change here
            ->whereIn('b.display', ['E', 'Y'])
            ->where('m.display', 'Y')
            ->where('a.usercode', $usercode)
            ->where('a.display', 'Y')
            ->groupBy('a.usertype, b.type_name, b.disp_flag');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function getEmpID($userCode = null)
    {
        $builder = $this->db->table('master.users');
        $builder->select('empid, name')
            ->where('usercode', $userCode);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_case_details($case_type = 0, $case_number = 0, $case_year = 0, $diary_number = 0, $diary_year = 0)
    {
        $builder = $this->db->table('main m');
        $builder->select('m.diary_no as case_diary, c_status, CONCAT(pet_name, " Vs ", res_name) as case_title, DATE_FORMAT(ord_dt, "%d-%m-%Y") as disp_date')
            ->join('dispose d', 'd.diary_no = m.diary_no', 'left');

        if ($case_type != 0 && $case_number != 0 && $case_year !== 0) {
            $builder->where("CAST(SUBSTRING(active_fil_no, 1, 2) AS UNSIGNED)", $case_type)
                ->where('active_reg_year', $case_year)
                ->groupStart()
                ->where("CAST(SUBSTRING(active_fil_no, 4, 6) AS UNSIGNED)", $case_number)
                ->orWhere("$case_number BETWEEN CAST(SUBSTRING(active_fil_no, 4, 6) AS UNSIGNED) AND CAST(SUBSTRING(active_fil_no, 11, 6) AS UNSIGNED)")
                ->groupEnd();
        } elseif ($diary_number != 0 && $diary_year != 0) {
            $builder->where('m.diary_no', $diary_number . $diary_year);
        }

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function getReceivedCasesList($fromDate = null, $toDate = null, $userType = null, $usercode = null, $roleCaseNature = null)
    {
        $condition1 = "";

        if (in_array($userType, [110, 111, 112])) {
            if ($roleCaseNature == 'R' || $roleCaseNature == 'C') {
                $condition1 = " AND case_grp = :roleCaseNature:";
            }
        }

        if ($userType == 110) { // RecordRoom DA
            $sql = "SELECT DISTINCT x.* FROM (
                    SELECT 
                        m.diary_no,
                        COALESCE((SELECT STRING_AGG(jname, ', ') FROM master.judge WHERE POSITION(d.jud_id::text IN jcode::text) > 0), '') AS coram,
                        CONCAT(m.reg_no_display, ' @ ', CONCAT(SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4), '/', SUBSTR(m.diary_no, -4))) AS case_no,
                        m.active_fil_dt, 
                        m.active_reg_year, 
                        m.active_fil_no,
                        CASE 
                            WHEN (m.fil_no IS NULL OR m.fil_no = '') AND (m.active_fil_no IS NULL OR m.active_fil_no = '') THEN m.casetype_id 
                            ELSE m.active_casetype_id 
                        END AS casetype_id,
                        CASE 
                            WHEN (m.fil_no IS NULL OR m.fil_no = '') AND (m.active_fil_no IS NULL OR m.active_fil_no = '' OR active_reg_year = 0) THEN YEAR(m.diary_no_rec_date) 
                            ELSE m.active_reg_year 
                        END AS case_year,
                        CONCAT(m.pet_name, ' Vs. ', m.res_name) AS Cause_title,
                        DATE_FORMAT(d.ord_dt, '%d-%m-%Y') AS order_date,
                        'SupAdmin' AS dispathBy,
                        d.ord_dt AS dispatchDate,
                        'Disposal -> RR-DA' AS remarks,
                        '' AS consignment_remark       
                    FROM 
                        dispose d 
                    INNER JOIN 
                        main m ON d.diary_no = m.diary_no
                    WHERE 
                        d.diary_no NOT IN (
                            SELECT r.diary_no 
                            FROM record_keeping r 
                            INNER JOIN main m1 ON r.diary_no = m1.diary_no 
                            WHERE consignment_status = 'Y' AND display = 'Y' AND m1.c_status = 'D'
                        )   
                        AND d.diary_no NOT IN (
                            SELECT f.diary_no 
                            FROM fil_trap f 
                            WHERE (d_by_empid = (SELECT empid FROM master.users WHERE usercode = :usercode:) OR remarks = 'RR-DA -> SEG-DA')
                        )    
                        AND ord_dt BETWEEN :fromDate: AND :toDate:
                        AND m.c_status = 'D'
                        
                    UNION
                    
                    SELECT 
                        m.diary_no,
                        COALESCE((SELECT STRING_AGG(jname, ', ') FROM master.judge WHERE POSITION(d.jud_id::text IN jcode::text) > 0), '') AS coram,
                        CONCAT(m.reg_no_display, ' @ ', CONCAT(SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTR(m.diary_no::text, -4))) AS case_no,          
                        m.active_fil_dt, 
                        m.active_reg_year, 
                        m.active_fil_no,
                        CASE 
                            WHEN (m.fil_no IS NULL OR m.fil_no = '') AND (m.active_fil_no IS NULL OR m.active_fil_no = '') THEN m.casetype_id 
                            ELSE m.active_casetype_id 
                        END AS casetype_id,
                        CASE 
                            WHEN (m.fil_no IS NULL OR m.fil_no = '') AND (m.active_fil_no IS NULL OR m.active_fil_no = '' OR active_reg_year = 0) THEN YEAR(m.diary_no_rec_date) 
                            ELSE m.active_reg_year 
                        END AS case_year,
                        CONCAT(m.pet_name, ' Vs. ', m.res_name) AS Cause_title,                              
                        DATE_FORMAT(d.ord_dt, '%d-%m-%Y') AS order_date,
                        'SupAdmin' AS dispathBy,
                        d.ord_dt AS dispatchDate,
                        'Disposal -> RR-DA' AS remarks,
                        f.consignment_remark     
                    FROM 
                        fil_trap f 
                    INNER JOIN 
                        main m ON f.diary_no = m.diary_no
                    INNER JOIN 
                        dispose d ON f.diary_no = d.diary_no
                    WHERE
                        d.diary_no NOT IN (
                            SELECT f.diary_no 
                            FROM fil_trap f 
                            WHERE (d_by_empid = (SELECT empid FROM master.users WHERE usercode = :usercode:) OR remarks = 'RR-DA -> SEG-DA')
                        )    
                        AND remarks = 'Disposal -> RRDA'
                        AND ord_dt BETWEEN :fromDate: AND :toDate:
                        AND m.c_status = 'D'
                ) x
                JOIN rr_hall_case_distribution hd ON (
                    (CASE WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) THEN SUBSTR(x.diary_no, -4) ELSE x.case_year END) BETWEEN hd.caseyear_from AND hd.caseyear_to
                    AND hd.display = 'Y'
                    AND (
                        (CASE WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) THEN CAST(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) AS INTEGER) ELSE CAST(SUBSTRING(x.active_fil_no, 4, 6) AS INTEGER) END) BETWEEN hd.case_from AND hd.case_to
                        OR 
                        (CASE WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL) THEN CAST(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) AS INTEGER) ELSE CAST(SUBSTRING(x.active_fil_no, 11, 6) AS INTEGER) END) BETWEEN hd.case_from AND hd.case_to
                    )
                    AND x.casetype_id = hd.casetype
                    AND hd.hall_no IN (
                        SELECT ref_hall_no FROM rr_user_hall_mapping WHERE usercode = :usercode: AND display = 'Y'
                        AND (to_date = '' OR DATE(to_date) = '0000-00-00' OR to_date IS NULL)
                    )
                )";
        } else {
            // Query for all dispatched cases with hall case allocation mapping with role
            $sql = "
            SELECT DISTINCT x.*
            FROM (
                SELECT
                    a.uid,
                    a.diary_no,
                    COALESCE((
                        SELECT STRING_AGG(jname, ', ')
                        FROM master.judge
                        WHERE POSITION(d.jud_id::text IN jcode::text) > 0
                    ), '') AS coram,
                    CONCAT(b.reg_no_display, ' @ ',
                        CONCAT(SUBSTRING(b.diary_no::text, 1, LENGTH(b.diary_no::text) - 4),
                        '/', SUBSTRING(b.diary_no::text, -4))) AS case_no,
                    CONCAT(b.pet_name, ' Vs. ', b.res_name) AS Cause_title,
                    TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS order_date,
                    a.d_by_empid,
                    a.d_to_empid,
                    a.disp_dt AS dispatchDate,
                    a.remarks,
                    e.name AS dispathBy,
                    b.pet_name,
                    b.res_name,
                    a.rece_dt,
                    b.active_fil_dt,
                    b.active_reg_year,
                    b.active_fil_no,
                    CASE
                        WHEN (b.fil_no IS NULL OR b.fil_no = '') AND
                             (b.active_fil_no IS NULL OR b.active_fil_no = '')
                        THEN b.casetype_id
                        ELSE b.active_casetype_id
                    END AS casetype_id,
                    CASE
                        WHEN (b.fil_no IS NULL OR b.fil_no = '') AND
                             (b.active_fil_no IS NULL OR b.active_fil_no = '' OR
                             b.active_reg_year = 0)
                        THEN EXTRACT(YEAR FROM b.diary_no_rec_date)
                        ELSE b.active_reg_year
                    END AS case_year,
                    a.consignment_remark
                FROM
                    fil_trap a
                LEFT JOIN main b ON a.diary_no = b.diary_no
                LEFT JOIN master.users e ON e.empid = a.d_by_empid
                LEFT JOIN dispose d ON d.diary_no = a.diary_no
                WHERE
                    a.d_to_empid = (SELECT empid FROM master.users WHERE usercode = $usercode)
                    AND a.d_to_empid IN (SELECT empid FROM master.users WHERE usertype = '$userType')
                    AND comp_dt IS NULL
                    AND a.disp_dt BETWEEN '".$fromDate."' AND '".$toDate."'
                    AND b.c_status = 'D'
                ORDER BY a.d_by_empid, a.disp_dt
            ) x
            JOIN master.rr_hall_case_distribution hd ON (
                (CASE 
                    WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL)
                    THEN SUBSTRING(x.diary_no::text, -4)
                    ELSE x.case_year::text
                END) BETWEEN hd.caseyear_from::text AND hd.caseyear_to::text
                AND hd.display = 'Y'
                AND (
                    (CASE 
                        WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL)
                        THEN CAST(SUBSTRING(x.diary_no::text, 1, LENGTH(x.diary_no::text) - 4) AS INTEGER)
                        ELSE CAST(SUBSTRING(x.active_fil_no, 4, 6) AS INTEGER) 
                    END) BETWEEN hd.case_from AND hd.case_to
                    OR
                    (CASE 
                        WHEN (x.active_fil_no = '' OR x.active_fil_no IS NULL)
                        THEN CAST(SUBSTRING(x.diary_no::text, 1, LENGTH(x.diary_no::text) - 4) AS INTEGER)
                        ELSE CAST(SUBSTRING(x.active_fil_no, 11, 6) AS INTEGER) 
                    END) BETWEEN hd.case_from AND hd.case_to
                )
                AND x.casetype_id = hd.casetype
                AND hd.hall_no::char IN (
                    SELECT ref_hall_no
                    FROM master.rr_user_hall_mapping
                    WHERE usercode = $usercode
                    AND display = 'Y'
                    AND (to_date::text = '' OR to_date IS NULL OR to_date::text = '0000-00-00')
                )
            )";
        }

        $query = $this->db->query($sql);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    function getReceivedCasesFromScanningList($fromDate = null, $toDate = null, $userType = null, $usercode = null, $roleCaseNature = null)
    {
        $condition = '';
        if ($roleCaseNature != null) {
            $condition = " and b.case_grp in (" . $roleCaseNature . ")";
        }



        if ($userType == 110) //RecordRoom DA
        {
            $sql = "select distinct x.* from (
            SELECT a.uid,a.diary_no,
            ifnull((select group_concat(jname separator ' , ') from judge where find_in_set(jcode,d.jud_id)),'') as coram,
            concat(b.reg_no_display,' @ ',concat(SUBSTR(a.diary_no, 1, LENGTH(a.diary_no) - 4),'/',SUBSTR(a.diary_no, - 4))) as case_no,          
          concat(b.pet_name,' Vs. ',b.res_name) as Cause_title,
          DATE_FORMAT(d.ord_dt, '%d-%m-%Y') as order_date,
            a.d_by_empid,a.d_to_empid,DATE_FORMAT(a.disp_dt, '%d-%m-%Y') as dispatchDate,
            a.remarks,e.name as dispathBy,b.pet_name,b.res_name,a.rece_dt,
            b.active_fil_dt,b.active_reg_year,b.active_fil_no,
                        case when ((b.fil_no is null or b.fil_no ='') and (b.active_fil_no is null or b.active_fil_no ='')) 
                            then b.casetype_id else b.active_casetype_id end as casetype_id ,
                        case when ((b.fil_no is null or b.fil_no ='') and (b.active_fil_no is null or b.active_fil_no ='' or active_reg_year=0)) 
                            then year(b.diary_no_rec_date) else b.active_reg_year end as case_year,
            a.consignment_remark
            FROM fil_trap a 
            LEFT JOIN main b ON a.diary_no = b.diary_no
            LEFT JOIN users e ON e.empid = a.d_by_empid
            left join dispose d on d.diary_no=a.diary_no
            WHERE d_to_empid = (SELECT empid FROM users where usercode=$usercode) 
            and d_to_empid in (SELECT empid FROM users WHERE usertype=$userType)
            and remarks='SCA -> RR-DA'
            AND comp_dt='0000-00-00 00:00:00'
            $condition
            and date(a.disp_dt) between '" . $fromDate . "' and '" . $toDate . "'
            and b.c_status='D'
            ORDER BY d_by_empid, a.disp_dt
            )x
                     join rr_hall_case_distribution hd 
                    on
                    (
                    if((x.active_fil_no='' or x.active_fil_no is null),SUBSTR(x.diary_no, - 4),x.case_year) between hd.caseyear_from and hd.caseyear_to
                        AND hd.display='Y'
                        and (
                                if((x.active_fil_no='' or x.active_fil_no is null),(cast(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) as unsigned)),cast(substring(x.active_fil_no,4,6) as unsigned)) between hd.case_from and hd.case_to
                                    or
                                if((x.active_fil_no='' or x.active_fil_no is null),(cast(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) as unsigned)),cast(substring(x.active_fil_no,11,6) as unsigned)) between hd.case_from and hd.case_to
                            )
                        
                        and x.casetype_id=hd.casetype
                        and hd.hall_no in (SELECT ref_hall_no FROM rr_user_hall_mapping where usercode=$usercode and display='Y'
                        AND (to_date='' OR date(to_date)='0000-00-00' OR to_date is null)
                        )
                    )
            ";
        }

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function getPendingToDispatchCasesList($fromDate = null, $toDate = null, $userType = null, $usercode = null)
    {
        $sql = "";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function get_all_users($userType, $caseGroup = null, $hallNo = null)
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('a.usercode, b.name, empid')
            ->join('users b', 'a.usercode = b.usercode')
            ->join('master.rr_da_case_distribution cd', 'a.usercode = cd.user_code')
            ->join('master.rr_user_hall_mapping m', 'a.usercode = m.usercode')
            ->where('a.usertype', $userType)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('m.display', 'Y')
            ->where('attend', 'P');

        if ($hallNo !== null) {
            $builder->where('m.ref_hall_no', $hallNo);
        }

        if ($caseGroup !== null) {
            $builder->where('caseHead', $caseGroup);
        }

        $builder->orderBy('empid');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }


    public function get_designated_users($userType, $utypeName, $caseGroup = null, $hallNo = null)
    {
        $builder = $this->db->table('fil_trap_users a');
        $builder->select('a.usercode as to_usercode, b.name as to_name, empid as to_userno, ddate, c.no as curno')
            ->join('users b', 'a.usercode = b.usercode')
            ->join('fil_trap_seq c', 'c.no < empid', 'left')
            ->join('master.rr_da_case_distribution cd', 'b.usercode = cd.user_code', 'left')
            ->join('master.rr_user_hall_mapping m', 'a.usercode = m.usercode')
            ->where('a.usertype', $userType)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y')
            ->where('m.display', 'Y')
            ->where('attend', 'P')
            ->where('m.ref_hall_no', $hallNo)
            ->where('utype', $utypeName)
            ->where('ddate', date('Y-m-d')); // Use the current date

        if ($caseGroup !== null) {
            $builder->where('caseHead', $caseGroup);
        }

        $builder->orderBy('to_userno');

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }


    public function record_last_assigned_user($user, $utypeName)
    {
        $builder = $this->db->table('fil_trap_seq');
        $exists = $builder->where('ddate', date('Y-m-d'))
            ->where('utype', $utypeName)
            ->get();

        if ($exists->getNumRows() == 0) {
            // Insert new record if it doesn't exist
            $data = [
                'ddate' => date('Y-m-d'),
                'utype' => $utypeName,
                'no' => $user
            ];
            $builder->insert($data);
        } else {
            // Update existing record if it does exist
            $builder->where('ddate', date('Y-m-d'))
                ->where('utype', $utypeName)
                ->update(['no' => $user]);
        }
    }


    public function check_case_file_trap($case_diary)
    {
        $builder = $this->db->table('fil_trap');
        $builder->select('COUNT(diary_no) as count_no')
            ->where('diary_no', $case_diary);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRowArray(); // Return a single row as an associative array
        } else {
            return false;
        }
    }


    public function updateCaseFileTrap($case_count_file_trap = null, $case_diary = null, $dispatchFromEmpID = null, $dispatchToEmpID = null, $remarks = null, $hallNo = null, $consignmentRemarks = null)
    {
        $builder = $this->db->table('fil_trap_his');

        if ($case_count_file_trap <= 0) {
            $data = [
                'diary_no' => $case_diary,
                'd_by_empid' => 1,
                'd_to_empid' => $dispatchFromEmpID,
                'disp_dt' => date('Y-m-d H:i:s'),
                'remarks' => 'DISPOSAL -> RR-DA',
                'r_by_empid' => $dispatchFromEmpID,
                'rece_dt' => date('Y-m-d H:i:s'),
                'comp_dt' => '0000-00-00 00:00:00',
                'disp_dt_seq' => '0000-00-00 00:00:00',
                'thisdt' => date('Y-m-d H:i:s'),
                'other' => 0,
                'scr_lower' => 0,
                'consignment_remark' => $consignmentRemarks
            ];

            $builder->insert($data);

            if ($this->db->affectedRows() >= 1) {
                $builder = $this->db->table('fil_trap');
                $data = [
                    'diary_no' => $case_diary,
                    'd_by_empid' => $dispatchFromEmpID,
                    'd_to_empid' => $dispatchToEmpID,
                    'disp_dt' => date('Y-m-d H:i:s'),
                    'remarks' => $remarks,
                    'r_by_empid' => $dispatchToEmpID,
                    'rece_dt' => date('Y-m-d H:i:s'),
                    'comp_dt' => '0000-00-00 00:00:00',
                    'disp_dt_seq' => '0000-00-00 00:00:00',
                    'other' => $hallNo,
                    'scr_lower' => 0,
                    'consignment_remark' => $consignmentRemarks
                ];

                $builder->insert($data);
            } else {
                return "Error while updating file trap";
            }
        } else {
            $builder = $this->db->table('fil_trap_his');

            $sql = $builder->select('diary_no, d_by_empid, d_to_empid, disp_dt, remarks, r_by_empid, rece_dt, comp_dt, disp_dt_seq, NOW() as thisdt, other, scr_lower, consignment_remark')
                ->where('diary_no', $case_diary)
                ->get();

            if ($sql->getNumRows() > 0) {
                $result = $sql->getRowArray();

                $data = [
                    'diary_no' => $result['diary_no'],
                    'd_by_empid' => $result['d_by_empid'],
                    'd_to_empid' => $result['d_to_empid'],
                    'disp_dt' => $result['disp_dt'],
                    'remarks' => $result['remarks'],
                    'r_by_empid' => $result['r_by_empid'],
                    'rece_dt' => $result['rece_dt'],
                    'comp_dt' => '0000-00-00 00:00:00',
                    'disp_dt_seq' => '0000-00-00 00:00:00',
                    'other' => $hallNo,
                    'scr_lower' => 0,
                    'consignment_remark' => $consignmentRemarks
                ];

                $builder->insert($data);

                $builder = $this->db->table('fil_trap');
                $data = [
                    'd_by_empid' => $dispatchFromEmpID,
                    'd_to_empid' => $dispatchToEmpID,
                    'disp_dt' => date('Y-m-d H:i:s'),
                    'remarks' => $remarks,
                    'r_by_empid' => $dispatchToEmpID,
                    'rece_dt' => date('Y-m-d H:i:s'),
                    'comp_dt' => '0000-00-00 00:00:00',
                    'disp_dt_seq' => '0000-00-00 00:00:00',
                    'other' => $hallNo,
                    'scr_lower' => 0,
                    'consignment_remark' => $consignmentRemarks
                ];

                $builder->where('diary_no', $case_diary)
                    ->update($data);
            } else {
                return "Error while updating file trap history from file trap";
            }
        }

        return true;
    }


    public function getReceivedDispatchedReport($fromDate = null, $toDate = null, $userType = null, $empId = null, $reportType = null)
    {
        $condition = "";
        $orderBy = "";
        $condition2 = "";
        if ($empId == 1) {
            $condition="1=1 and (remarks='Disposal -> RRDA' or remarks='RR-DA -> SEG-DA' or remarks='SEG-DA -> SCA' or remarks='SCA -> RR-DA' or remarks='REC-DA -> Rack')";
            $condition2="1=1";
            $orderBy="d_by_empid,d_to_empid,dispatchDate";

        }
        else
        {
            if ($reportType == 1) // 1 for Receive Report
            {
                $condition = "d_to_empid = $empId ";
                $orderBy = "d_by_empid,dispatchDate,d_by_empid";
                $condition2 = "d_to_empid in (SELECT empid FROM master.users WHERE usertype=$userType)";
            } elseif ($reportType == 2) // 2 for Dispatch Report
            {
                $condition = "d_by_empid = $empId ";
                $orderBy = "d_by_empid,dispatchDate,d_to_empid";
                $condition2 = "d_by_empid in (SELECT empid FROM master.users WHERE usertype=$userType)";
            }
        }
        $sql = "
        SELECT * FROM
        (
            SELECT a.uid, a.diary_no,
            COALESCE((SELECT string_agg(jname, ', ') FROM master.judge WHERE jcode::text = ANY(string_to_array(d.jud_id, ','))), '') AS coram,
            concat(b.reg_no_display, ' @ ', concat(SUBSTR(a.diary_no::text, 1, LENGTH(a.diary_no::text) - 4), '/', SUBSTR(a.diary_no::text, - 4))) AS case_no,
            concat(b.pet_name, ' Vs. ', b.res_name) AS Cause_title,
            TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS order_date,
            a.d_by_empid, a.d_to_empid, TO_CHAR(a.disp_dt, 'DD-MM-YYYY') AS dispatchDate,
            a.remarks, e.name AS dispathBy,
            e1.name AS dispathTo,
            b.pet_name, b.res_name, a.rece_dt,
            u1.type_name AS roleBy, u2.type_name AS roleTo,
            a.consignment_remark
            FROM fil_trap a
            LEFT JOIN main b ON a.diary_no = b.diary_no
            LEFT JOIN master.users e ON e.empid = a.d_by_empid
            LEFT JOIN master.usertype u1 ON e.usertype = u1.id
            LEFT JOIN master.users e1 ON e1.empid = a.d_to_empid
            LEFT JOIN master.usertype u2 ON e1.usertype = u2.id
            LEFT JOIN dispose d ON d.diary_no = a.diary_no
            WHERE $condition 
            AND $condition2 
            AND comp_dt IS NULL
            AND date(a.disp_dt) BETWEEN '".$fromDate."' AND '".$toDate."'
    
            UNION
    
            SELECT a.uid, a.diary_no,
            COALESCE((SELECT string_agg(jname, ', ') FROM master.judge WHERE jcode::text = ANY(string_to_array(d.jud_id, ','))), '') AS coram,
            concat(b.reg_no_display, ' @ ', concat(SUBSTR(a.diary_no::text, 1, LENGTH(a.diary_no::text) - 4), '/', SUBSTR(a.diary_no::text, - 4))) AS case_no,
            concat(b.pet_name, ' Vs. ', b.res_name) AS Cause_title,
            TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS order_date,
            a.d_by_empid, a.d_to_empid, TO_CHAR(a.disp_dt, 'DD-MM-YYYY') AS dispatchDate,
            a.remarks, e.name AS dispathBy, e1.name AS dispathTo,
            b.pet_name, b.res_name, a.rece_dt,
            u1.type_name AS roleBy, u2.type_name AS roleTo,
            a.consignment_remark
            FROM fil_trap_his a
            LEFT JOIN main b ON a.diary_no = b.diary_no
            LEFT JOIN master.users e ON e.empid = a.d_by_empid
            LEFT JOIN master.usertype u1 ON e.usertype = u1.id
            LEFT JOIN master.users e1 ON e1.empid = a.d_to_empid
            LEFT JOIN master.usertype u2 ON e1.usertype = u2.id
            LEFT JOIN dispose d ON d.diary_no = a.diary_no
            WHERE $condition 
            AND $condition2 
            AND comp_dt IS NULL
            AND date(a.disp_dt) BETWEEN '".$fromDate."' AND '".$toDate."'
        ) x            
        ORDER BY $orderBy";
    

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function getCaseTimeLineReport($case_diary)
    {
        // Create the subquery for fil_trap
        $subquery1 = $this->db->table('fil_trap')
            ->select('uid, diary_no, d_by_empid, d_to_empid, disp_dt, remarks, r_by_empid, rece_dt, comp_dt, disp_dt_seq, NOW() AS thisdt, other, scr_lower, consignment_remark')
            ->where('diary_no', $case_diary)
            ->getCompiledSelect();

        // Create the subquery for fil_trap_his
        $subquery2 = $this->db->table('fil_trap_his')
            ->select('uid, diary_no, d_by_empid, d_to_empid, disp_dt, remarks, r_by_empid, rece_dt, comp_dt, disp_dt_seq, NOW() AS thisdt, other, scr_lower, consignment_remark')
            ->where('diary_no', $case_diary)
            ->getCompiledSelect();

        // Combine the subqueries using UNION
        $combinedQuery = "($subquery1 UNION $subquery2) a";

        // Main query
        $query = $this->db->table($combinedQuery)
            ->select('a.uid, a.diary_no,
            IFNULL((SELECT GROUP_CONCAT(jname SEPARATOR " , ") FROM judge WHERE FIND_IN_SET(jcode, d.jud_id)), "") AS coram,
            CONCAT(b.reg_no_display, " @ ", CONCAT(SUBSTR(a.diary_no, 1, LENGTH(a.diary_no) - 4), "/", SUBSTR(a.diary_no, -4))) AS case_no,
            CONCAT(b.pet_name, " Vs. ", b.res_name) AS Cause_title,
            DATE_FORMAT(d.ord_dt, "%d-%m-%Y") AS order_date,
            a.d_by_empid, a.d_to_empid, DATE_FORMAT(a.disp_dt, "%d-%m-%Y") AS dispatchDate,
            a.remarks, e.name AS dispathBy, e1.name AS dispathTo,
            b.pet_name, b.res_name, a.rece_dt,
            u1.type_name AS roleBy, u2.type_name AS roleTo,
            rr.Description AS hall_location, rr.hall_no AS hall_no,
            a.consignment_remark')
            ->join('main b', 'a.diary_no = b.diary_no', 'left')
            ->join('users e', 'e.empid = a.d_by_empid', 'left')
            ->join('usertype u1', 'e.usertype = u1.id', 'left')
            ->join('users e1', 'e1.empid = a.d_to_empid', 'left')
            ->join('usertype u2', 'e1.usertype = u2.id', 'left')
            ->join('dispose d', 'd.diary_no = a.diary_no', 'left')
            ->join('ref_rr_hall rr', 'a.other = rr.hall_no AND (a.other IN (SELECT hall_no FROM ref_rr_hall))', 'left')
            ->orderBy('thisdt', 'desc');

        // Execute the query
        $queryResult = $query->get();

        if ($queryResult->getNumRows() >= 1) {
            return $queryResult->getResultArray();
        } else {
            return false;
        }
    }


    public function getCaseDestinationHallNo($diaryNo = null)
    {
        // Build the first part of the query using a subquery
        $subquery = $this->db->table('dispose d')
            ->select('
            d.diary_no,   
            b.active_fil_dt,
            b.active_reg_year,
            b.active_fil_no,
            CASE 
                WHEN (b.fil_no IS NULL OR b.fil_no = "" AND (b.active_fil_no IS NULL OR b.active_fil_no = "")) 
                THEN b.casetype_id 
                ELSE b.active_casetype_id 
            END AS casetype_id,
            CASE 
                WHEN (b.fil_no IS NULL OR b.fil_no = "" AND (b.active_fil_no IS NULL OR b.active_fil_no = "" OR b.active_reg_year = 0)) 
                THEN YEAR(b.diary_no_rec_date) 
                ELSE b.active_reg_year 
            END AS case_year')
            ->join('main b', 'd.diary_no = b.diary_no')
            ->where('b.diary_no', $diaryNo)
            ->where('b.c_status', 'D')
            ->getCompiledSelect();

        // Main query that uses the subquery
        $query = $this->db->table("($subquery) x", false)
            ->join('rr_hall_case_distribution hd', function ($join) {
                $join->on("IF((x.active_fil_no = '' OR x.active_fil_no IS NULL), SUBSTR(x.diary_no, -4), x.case_year) BETWEEN hd.caseyear_from AND hd.caseyear_to")
                    ->where('hd.display', 'Y')
                    ->where(function ($query) {
                        $query->where("IF((x.active_fil_no = '' OR x.active_fil_no IS NULL), (CAST(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) AS UNSIGNED)), (CAST(SUBSTRING(x.active_fil_no, 4, 6) AS UNSIGNED)) BETWEEN hd.case_from AND hd.case_to")
                            ->orWhere("IF((x.active_fil_no = '' OR x.active_fil_no IS NULL), (CAST(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) AS UNSIGNED)), (CAST(SUBSTRING(x.active_fil_no, 11, 6) AS UNSIGNED)) BETWEEN hd.case_from AND hd.case_to")
                            ->where('x.casetype_id', 'hd.casetype');
                    });
            });

        // Execute the query
        $queryResult = $query->get();

        if ($queryResult->getNumRows() >= 1) {
            return $queryResult->getResultArray();
        } else {
            return false;
        }
    }


    function check_consignment_entry($userCode = null, $caseDiary = null)
    {
        // function check the case should be already consigned before and all employee with role 110 should have role to received & dispatch that type of case

        $condition = "";
        if ($userCode != 1) {
            //$condition=" AND d_by_empid = (SELECT empid FROM users where (usercode=$userCode)) ";
            $condition = " AND d_by_empid in (SELECT empid FROM users where usercode in (select usercode from master.rr_da_case_distribution  r
            inner join fil_trap_users u 
            on r.user_code=u.usercode
            where u.usertype=110 and  u.display='Y' AND r.display='Y'
                    and r.casetype=0
                    and (valid_from is not null or valid_from !=''))) ";
        } else {
            $condition = " and 1=1 ";
        }
        $sql = "select x.* from 
          (SELECT 
                m.diary_no,            
                    m.active_fil_dt,
                    m.active_reg_year,
                    m.active_fil_no,
                    CASE
                        WHEN
                        ((m.fil_no IS NULL OR m.fil_no = '')
                                AND (m.active_fil_no IS NULL
            OR m.active_fil_no = ''))
                        THEN
                            m.casetype_id
                        ELSE m.active_casetype_id
                    END AS casetype_id,
                    CASE
                        WHEN
                        ((m.fil_no IS NULL OR m.fil_no = '')
                                AND (m.active_fil_no IS NULL
            OR m.active_fil_no = ''
            OR active_reg_year = 0))
                        THEN
                            YEAR(m.diary_no_rec_date)
                        ELSE m.active_reg_year
                    END AS case_year,
                    DATE_FORMAT(f.disp_dt, '%d-%m-%Y') AS consignment_date,
                    f.d_by_empid, f.d_to_empid, disp_dt, f.remarks, 
              f.r_by_empid, f.rece_dt,f.consignment_remark
            FROM
                fil_trap f
            INNER JOIN main m ON f.diary_no = m.diary_no
            WHERE
                f.remarks = 'RR-DA -> SEG-DA'
                $condition
                    AND f.diary_no = $caseDiary
            AND m.c_status = 'D'
            )x 
            join rr_hall_case_distribution hd 
                on
                (
                if((x.active_fil_no='' or x.active_fil_no is null),SUBSTR(x.diary_no, - 4),x.case_year) between hd.caseyear_from and hd.caseyear_to
                    AND hd.display='Y'
                    and (
                            if((x.active_fil_no='' or x.active_fil_no is null),(cast(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) as unsigned)),cast(substring(x.active_fil_no,4,6) as unsigned)) between hd.case_from and hd.case_to
                                or
                            if((x.active_fil_no='' or x.active_fil_no is null),(cast(SUBSTR(x.diary_no, 1, LENGTH(x.diary_no) - 4) as unsigned)),cast(substring(x.active_fil_no,11,6) as unsigned)) between hd.case_from and hd.case_to
                        )
                    
                    and x.casetype_id=hd.casetype
                    and hd.hall_no in 
                    (SELECT ref_hall_no FROM rr_user_hall_mapping where usercode in (SELECT 
usercode
FROM
master.rr_da_case_distribution r
    INNER JOIN
fil_trap_users u ON r.user_code = u.usercode
WHERE
u.usertype = 110 AND u.display = 'Y'
    AND r.display = 'Y'
    AND r.casetype = 0
    AND (valid_from IS NOT NULL
    OR valid_from != '')
    ) and display='Y'
                    AND (to_date='' OR date(to_date)='0000-00-00' OR to_date is null)
                    )                        
                )";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function check_already_reconsign_today($caseDiary = null)
    {
        $query = $this->db->table('fil_trap f')
            ->where('f.remarks', 'RR-DA -> SEG-DA')
            ->where('f.diary_no', $caseDiary)
            ->where('DATE(disp_dt)', 'CURDATE()')
            ->get();

        // Check if any rows are returned
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    public function updateConsignmentDate($caseDiary = null, $updateData = null, $newConsignmentRemarks = null)
    {
        $builder = $this->db->table('fil_trap_his');

        $data = $this->db->table('fil_trap')
            ->select('diary_no, d_by_empid, d_to_empid, disp_dt, remarks, r_by_empid, rece_dt, comp_dt, disp_dt_seq, NOW() as thisdt, other, scr_lower, consignment_remark')
            ->where('diary_no', $caseDiary)
            ->get()
            ->getResultArray();

        if (!empty($data)) {
            $builder->insertBatch($data);

            // Update the main table
            $this->db->table('fil_trap')->where('diary_no', $caseDiary)->update($updateData);

            // Check if the update was successful
            if ($this->db->affectedRows() > 0) {
                return 1; // Success
            } else {
                return 0; // Update failed
            }
        } else {
            throw new \Exception("Error: No data found for diary_no = $caseDiary while updating Consignment Date.");
        }
    }

    public function getConsignmentRemarks($whereConditionArray = null)
    {
        $builder = $this->db->table('fil_trap');

        if (!is_null($whereConditionArray)) {
            $builder->where($whereConditionArray);
        }

        $query = $builder->get();

        return $query->getResultArray();
    }

    function getAllRestoredDisposedCases($fromDate = null, $toDate = null)
    {
        /* $sql="select  f1.diary_no,
            ifnull((select group_concat(jname separator ' , ') from judge where find_in_set(jcode,d.jud_id)),'') as coram,
            concat(b.reg_no_display,' @ ',concat(SUBSTR(f1.diary_no, 1, LENGTH(f1.diary_no) - 4),'/',SUBSTR(f1.diary_no, - 4))) as case_no,          
          concat(b.pet_name,' Vs. ',b.res_name) as Cause_title,
          DATE_FORMAT(d.ord_dt, '%d-%m-%Y') as order_date,
            f1.d_by_empid,f1.d_to_empid,DATE_FORMAT(f1.disp_dt, '%d-%m-%Y') as dispatchDate,
            f1.remarks,e.name as dispathBy,
            e1.name as dispathTo,
            b.pet_name,b.res_name,f1.rece_dt,
            u1.type_name as roleBy, u2.type_name as roleTo,
            f1.consignment_remark          
	from
	(
		select b.diary_no, max(b.next_dt) last_listing_dt from 
			(
				select m.diary_no, h.next_dt from heardt h, main m,fil_trap f
				where m.diary_no = h.diary_no 
				and m.diary_no=f.diary_no
				and h.board_type='J' and date(h.next_dt)> date(f.disp_dt)
				and (remarks='Disposal -> RRDA' or remarks='RR-DA -> SEG-DA' or remarks='SEG-DA -> SCA' or remarks='SCA -> RR-DA' or remarks='REC-DA -> Rack')
				union
				select m.diary_no, h.next_dt from last_heardt h, main m,fil_trap f 
				where m.diary_no = h.diary_no
				and m.diary_no=f.diary_no
				and h.board_type='J'and date(h.next_dt) >date(f.disp_dt)
				and (remarks='Disposal -> RRDA' or remarks='RR-DA -> SEG-DA' or remarks='SEG-DA -> SCA' or remarks='SCA -> RR-DA' or remarks='REC-DA -> Rack') 
			) b 
		group by b.diary_no
        )c
    LEFT JOIN main b ON c.diary_no = b.diary_no
    inner join restored r on c.diary_no=r.diary_no 
    inner join fil_trap f1 on c.diary_no=f1.diary_no
    inner join dispose d on c.diary_no=d.diary_no
    LEFT JOIN users e ON e.empid = f1.d_by_empid
    left join usertype u1 on e.usertype=u1.id
    LEFT JOIN users e1 ON e1.empid = f1.d_to_empid
    left join usertype u2 on e1.usertype=u2.id
    where date(d.ord_dt) between '".$fromDate."' and '".$toDate."'";
     */

        // the above query changed for receiving the list of already consigned restored/recalled cases

        $sql = "SELECT f1.diary_no,
        COALESCE((SELECT STRING_AGG(jname, ', ') FROM master.judge WHERE jcode::text = ANY(string_to_array(d.jud_id, ','))), '') AS coram,
        CONCAT(b.reg_no_display, ' @ ', CONCAT(SUBSTR(f1.diary_no::text, 1, LENGTH(f1.diary_no::text) - 4), '/', SUBSTR(f1.diary_no::text, - 4))) AS case_no,
        CONCAT(b.pet_name, ' Vs. ', b.res_name) AS Cause_title,
        TO_CHAR(d.ord_dt, 'DD-MM-YYYY') AS order_date,
        f1.d_by_empid, f1.d_to_empid, TO_CHAR(f1.disp_dt, 'DD-MM-YYYY') AS dispatchDate,
        f1.remarks, e.name AS dispathBy,
        e1.name AS dispathTo,
        b.pet_name, b.res_name, f1.rece_dt,
        u1.type_name AS roleBy, u2.type_name AS roleTo,
        f1.consignment_remark
    FROM
    (
        SELECT b.diary_no, MAX(b.next_dt) AS last_listing_dt FROM 
        (
            SELECT m.diary_no, h.next_dt FROM heardt h, main m, fil_trap f
            WHERE m.diary_no = h.diary_no 
            AND m.diary_no = f.diary_no
            AND h.board_type = 'J' AND DATE(h.next_dt) > DATE(f.disp_dt)
            AND (remarks = 'Disposal -> RRDA' OR remarks = 'RR-DA -> SEG-DA' OR remarks = 'SEG-DA -> SCA' OR remarks = 'SCA -> RR-DA' OR remarks = 'REC-DA -> Rack')
            UNION
            SELECT m.diary_no, h.next_dt FROM last_heardt h, main m, fil_trap f 
            WHERE m.diary_no = h.diary_no
            AND m.diary_no = f.diary_no
            AND h.board_type = 'J' AND DATE(h.next_dt) > DATE(f.disp_dt)
            AND (remarks = 'Disposal -> RRDA' OR remarks = 'RR-DA -> SEG-DA' OR remarks = 'SEG-DA -> SCA' OR remarks = 'SCA -> RR-DA' OR remarks = 'REC-DA -> Rack') 
            UNION
            SELECT m.diary_no, h.next_dt
            FROM last_heardt h, main m, record_keeping f
            WHERE m.diary_no = h.diary_no
            AND m.diary_no = f.diary_no
            AND h.board_type = 'J'
            AND DATE(h.next_dt) > DATE(f.consignment_date)
        ) b 
        GROUP BY b.diary_no
    ) c
    LEFT JOIN main b ON c.diary_no = b.diary_no
    LEFT JOIN restored r ON c.diary_no = r.diary_no 
    LEFT JOIN record_keeping rk ON c.diary_no = rk.diary_no
    INNER JOIN fil_trap f1 ON c.diary_no = f1.diary_no
    INNER JOIN dispose d ON c.diary_no = d.diary_no
    LEFT JOIN master.users e ON e.empid = f1.d_by_empid
    LEFT JOIN master.usertype u1 ON e.usertype = u1.id
    LEFT JOIN master.users e1 ON e1.empid = f1.d_to_empid
    LEFT JOIN master.usertype u2 ON e1.usertype = u2.id
    WHERE DATE(d.ord_dt) BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";


        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}
