<?php
namespace App\Models\Listing;
use CodeIgniter\Model;

class PhysicalHearingModel extends Model
{
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_short_description($dno)
    {
        $builder = $this->db->table('main a');
        $builder->select('short_description')
            ->join('master.casetype b', 'a.casetype_id = b.casecode', 'left')
            ->where('a.diary_no', $dno);
        $query = $builder->get();
        $result = $query->getRowArray();
        $short_description = !empty($result) ? $result['short_description'] : null;
        //pr($short_description);
        return $short_description;
    }

    public function get_next_dt($dno)
    {
        $builder = $this->db->table('heardt');
        $builder->select('next_dt')
            ->where('diary_no', $dno);

        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $result['next_dt'] : null;
    }

    public function get_case_details($dno)
    {
        $builder = $this->db->table('main a');
        $builder->select('
            diary_no_rec_date,
            fil_dt,
            lastorder,
            pet_name,
            res_name,
            c_status,
            b.next_dt,
            b.mainhead,
            b.subhead,
            b.clno,
            b.brd_slno,
            roster_id,
            judges,
            board_type,
            main_supp_flag,
            b.tentative_cl_dt,
            sitting_judges,
            c.remark,
            case_grp AS side
        ')
            ->join('heardt b', 'CAST(a.diary_no AS text) = CAST(b.diary_no AS text)', 'left')
            ->join('brdrem c', 'CAST(a.diary_no AS text) = CAST(c.diary_no AS text)', 'left')
            //->join('brdrem c', "c.diary_no = CAST(a.diary_no AS text)", 'left')
            ->where('a.diary_no', $dno);
        
        $query = $builder->get();
        $result = $query->getRowArray(); // Get results as an array
        return $result;
    }

    public function get_fil_details($dno)
    {
        $builder = $this->db->table('main a');
        $builder->select([
            'a.fil_no',
            'a.fil_dt',
            'a.fil_no_fh',
            'a.fil_dt_fh',
            'short_description',
            "CASE 
                WHEN a.reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) 
                ELSE a.reg_year_mh 
             END AS m_year",
            "CASE 
                WHEN a.reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) 
                ELSE a.reg_year_fh 
             END AS f_year"
        ]);
        
        $builder->join(
            'master.casetype b',
            "(
                SUBSTRING(a.fil_no FROM 1 FOR 2) ~ '^[0-9]+$' AND 
                CAST(
                    CASE 
                        WHEN SUBSTRING(a.fil_no FROM 1 FOR 2) ~ '^[0-9]+$' 
                        THEN SUBSTRING(a.fil_no FROM 1 FOR 2) 
                        ELSE NULL 
                    END AS INTEGER
                ) = b.casecode
            )",
            'left',
            false
        );
        $builder->where('a.diary_no', $dno);
        // pr($builder->getCompiledSelect());
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function short_description_by_casecode($fil_no_fh)
    {
        $casecode = substr($fil_no_fh, 0, 2);
        $builder = $this->db->table('master.casetype');
        $builder->select('short_description')
                ->where('casecode', $casecode);

        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ? $result['short_description'] : null;
    }

    public function get_multiple_category($dno)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select('a.submaster_id, b.sub_name1, b.sub_name2, b.sub_name3, b.sub_name4')
                ->join('master.submaster b', 'b.id = a.submaster_id', 'left')
                ->where('a.display', 'Y')
                ->where('a.diary_no', $dno);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_conct($dno)
    {
        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no')
                ->where('conn_key', $dno)
                ->orWhere('diary_no', $dno);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function physical_hearing_consent_required($fil_no, $usercode)
    {
        $return = false;
        $builder = $this->db->table('physical_hearing_consent_required');
        $builder->select('*')
                ->where('diary_no', $fil_no);
                
        // $exists = $builder->countAllResults() > 0;
        if ($builder->countAllResults() == 0) {

            $subQuery = $this->db->table('conct')
                ->select('diary_no')
                ->where('conn_key', $fil_no)
                ->orWhere('diary_no', $fil_no)
                ->where('list', 'Y')
                ->orWhere('list IS NULL');

            // Prepare the main query to insert data
            $insertData = $this->db->table('main m')
                ->select('m.diary_no, m.conn_key, "' . $usercode . '" as updated_by, NOW() as updated_on, h.mainhead')
                ->join('heardt h', 'h.diary_no = m.diary_no')
                ->where('m.diary_no IN (' . $subQuery->getCompiledSelect() . ')')
                ->orWhere('m.diary_no', $fil_no)
                ->get()
                ->getResultArray();

            // Execute the insert
            $builder = $this->db->table('physical_hearing_consent_required');

            if ($builder->insertBatch($insertData)) {
                echo $affectedRows = $this->db->affectedRows();
                if ($affectedRows > 0) {
                    $return = true;
                } 
            }
        }
        return $return;
    }

    public function navigate_diary($dno)
    {
        $builder = $this->builder('main m');
        $builder->select('m.diary_no, c1.short_description, m.active_reg_year, m.active_fil_no, 
                          m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, 
                          m.active_fil_dt, m.lastorder, m.c_status')
                ->join('master.casetype c1', 'm.active_casetype_id = c1.casecode', 'left')
                ->where('m.diary_no', $dno);

                $query = $builder->get();
        $results = $query->getResultArray();
        $filno_array = [];
        $cstatus = '';
        
        foreach ($results as $ro) {
            $filno_array = explode("-", $ro['active_fil_no']);
            $fil_no_print = '';
            if (empty($filno_array[0])) {
                $fil_no_print = "Unreg.";
            } else {
                //pr($filno_array);
                if(isset($filno_array[1])) {
                    $fil_no_print = $ro['short_description'] . "/" . ltrim($filno_array[1], '0');
                }
                if (!empty($filno_array[2]) && $filno_array[1] != $filno_array[2]) {
                    $fil_no_print .= "-" . ltrim($filno_array[2], '0');
                }
                $fil_no_print .= "/" . $ro['active_reg_year'];
            }

            $cstatus = ($ro['c_status'] == "P") ? "Pending" : "Disposed";
            $session_diary_recv_dt = !empty($ro['diary_no_rec_date']) ? date('d-m-Y H:i:s', strtotime($ro['diary_no_rec_date'])) : null;
            $session_active_fil_dt = !empty($ro['active_fil_dt']) ? date('d-m-Y H:i:s', strtotime($ro['active_fil_dt'])) : null;
            session()->set([
                'session_c_status' => $cstatus,
                'session_pet_name' => $ro['pet_name'],
                'session_res_name' => $ro['res_name'],
                'session_lastorder' => $ro['lastorder'],
                'session_diary_recv_dt' => $session_diary_recv_dt,
                'session_active_fil_dt' => $session_active_fil_dt,
                'session_diary_no' => substr($dno, 0, -4),
                'session_diary_yr' => substr($dno, -4),
                'session_active_reg_no' => $fil_no_print,
            ]);
        }
    }



    public function get_vacation_advance_list()
    {
        $results = [];
        $sql = "SELECT DISTINCT 
                    val.diary_no, 
                    val.conn_key,
                    CASE
                        WHEN (val.diary_no = val.conn_key OR val.conn_key = 0 OR val.conn_key IS NULL)
                        THEN 0
                        ELSE 1
                    END AS main_or_connected,
                    val.is_fixed,
                    CONCAT(m.reg_no_display, ' @ ', 
                        CONCAT(SUBSTR(CAST(val.diary_no AS TEXT), 1, LENGTH(CAST(val.diary_no AS TEXT)) - 4), 
                                ' / ', 
                                SUBSTR(CAST(val.diary_no AS TEXT), -4))) AS case_no,
                    TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date,
                    CONCAT(COALESCE(m.pet_name, ''), ' Vs. ', COALESCE(m.res_name, '')) AS cause_title,
                    val.is_deleted AS declined_by_admin,
                    (CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END) AS fixed_order,
                    CASE 
                        WHEN val.conn_key = 0 OR val.conn_key IS NULL OR val.conn_key = val.diary_no 
                        THEN val.diary_no 
                        ELSE val.conn_key 
                    END AS conn_key_order
                FROM 
                    vacation_advance_list val
                INNER JOIN 
                    main m ON val.diary_no = m.diary_no
                WHERE 
                    vacation_list_year = EXTRACT(YEAR FROM NOW())
                ORDER BY 
                    fixed_order,
                    conn_key_order,
                    main_or_connected ASC";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $results = $query->getResultArray();
            foreach($results as $index => $record) {

                $builder = $this->db->table('vacation_advance_list_advocate v');
                $builder->select("STRING_AGG(DISTINCT CONCAT(COALESCE(b.name, ''), '<font color=\"red\" weight=\"bold\">', 
                            CASE WHEN v.is_deleted = 't' THEN '(Declined)' ELSE '' END, '</font>'), '<br/>') AS advocate", false);
                $builder->join('master.bar b', 'b.aor_code = v.aor_code');
                $builder->where('v.diary_no', $record['diary_no']);
                $builder->where('v.vacation_list_year', date('Y'));
                $builder->where('b.if_aor', 'Y');
                $builder->where('b.isdead', 'N');
                $builder->groupBy('v.diary_no');
                
                $query = $builder->get();
                $advocate = $query->getRowArray();
                $results[$index]['advocate'] = isset($advocate['advocate']) ? $advocate['advocate'] :'';
                
            }
        }
       
        return $results;
    }

    public function get_vacation_advance_lists($caseCategory)
    {
        $builder = $this->db->table('physical_hearing_consent_required as val')
            ->select([
                'val.diary_no',
                'val.conn_key',
                "CASE 
                    WHEN val.diary_no = val.conn_key 
                        OR val.conn_key = 0 
                        OR val.conn_key IS NULL 
                    THEN 0 
                    ELSE 1 
                END AS main_or_connected",
                'val.is_fixed',
                "m.reg_no_display || ' @ ' || 
                (SUBSTRING(val.diary_no::TEXT FROM 1 FOR LENGTH(val.diary_no::TEXT) - 4) || ' / ' || 
                SUBSTRING(val.diary_no::TEXT FROM LENGTH(val.diary_no::TEXT) - 3 FOR 4)) AS case_no",
                "TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date",
                "COALESCE(m.pet_name, '') || ' Vs. ' || COALESCE(m.res_name, '') AS cause_title",
                'val.consent',
                'val.is_deleted AS declined_by_admin',
                "CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END AS fixed_order",
                "COALESCE(NULLIF(val.conn_key, 0), val.diary_no) AS sort_key"
            ], false) // false to prevent escaping of raw SQL
            ->join('main as m', 'val.diary_no = m.diary_no', 'inner')
            ->where('mainhead', $caseCategory)
            ->orderBy('fixed_order')
            ->orderBy('sort_key')
            ->orderBy('main_or_connected', 'ASC')
            ->distinct()
            ->get();

        $result = $builder->getResultArray();

        return $result;
    }

    

    public function decline_vacation_list_cases($all_cases, $updated_by, $updated_from_system)
    {
        $update_flag = 0;
        foreach($all_cases as $case) {
            $case_data = explode('_', $case);
            $subQuery = $this->db->table('physical_hearing_consent_required')
            ->select('diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent')
            ->where('diary_no', $case_data[1])
            ->where('is_deleted', 'f');
            
            $record = $subQuery->get()->getRowArray();

            if ($record) {
                $this->db->table('physical_hearing_consent_required_log')->insert($record);
                $data = [
                    'consent'           => $case_data[0],
                    'updated_on'        => date('Y-m-d H:i:s'), 
                    'updated_by'        => $updated_by,
                    'updated_from_ip'   => $updated_from_system,
                ];

                // Update the record
                $is_udpate = $this->db->table('physical_hearing_consent_required')
                    ->where('diary_no', $case_data[1])
                    ->where('is_deleted', 'f')
                    ->update($data);
                if($is_udpate){
                    $update_flag = 1;
                }
            }

            echo $update_flag;
         }
    }


    public function check_case_in_physical_hearing($diary_no)
    {
        $return = false;
        $builder = $this->db->table('physical_hearing_consent_required')->where('diary_no', $diary_no);
        
        $exists = $builder->countAllResults() > 0;
        if (!empty($exists)) {
            $return = true;
        }      
        return $return;
    }

    public function get_advocate_details($diary_no)
    {
        $builder = $this->db->table('advocate a');
        $builder->select('a.diary_no, CONCAT(b.title, \' \', b.name) as adv_name, b.aor_code, adv_type, pet_res, pet_res_no, advocate_id, adv, aor_state, phac.consent')
            ->join('master.bar b', 'a.advocate_id = b.bar_id', 'left')
            ->join('physical_hearing_advocate_consent phac', 'a.diary_no = phac.diary_no AND a.advocate_id = phac.bar_id', 'left')
            ->where('a.diary_no', $diary_no)
            ->where('display', 'Y')
            ->orderBy('CASE WHEN pet_res = \'P\' THEN 1 WHEN pet_res = \'R\' THEN 2 ELSE 99 END')
            ->orderBy('adv_type', 'DESC')
            ->orderBy('pet_res_no', 'ASC');
            //pr($builder->getCompiledSelect());
        // Execute the query
        $query = $builder->get();
        $results = $query->getResultArray();
        return $results;
    }

    public function update_hearing_consent_required($advocate_ids, $diary_no)
    {
        //$advocate_ids = [];
        $total_consent_count = count($advocate_ids);
        //pr($advocate_ids);
        $declineEntryCount = $total_p_consent_count = $update_flag = 0;
        $updatedFromSystem = $_SERVER['REMOTE_ADDR'];
        $updatedByUser = 1;
        
        foreach($advocate_ids as $advocate) {
            $advocate_data = explode('_',$advocate);
            if($advocate_data[0]=='P'){
                $total_p_consent_count++;
            }
                

            if($advocate_data[0] == 'V' && ($declineEntryCount <= 0)) {
                $update_flag = 0;
                $builder = $this->db->table('physical_hearing_consent_required_log');
                $sql = "INSERT INTO physical_hearing_consent_required_log (diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent)
                        SELECT diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent 
                        FROM physical_hearing_consent_required 
                        WHERE diary_no = ? AND is_deleted = 'f' AND consent = 'P'";

                $query = $this->db->query($sql, [$diary_no]);
                if ($query) {
                    $affectedRows = $this->db->affectedRows();
                    if ($affectedRows > 0) {
                        $update_flag = 1;
                        $data = [
                            'consent' => 'V',
                            'updated_on' => date('Y-m-d H:i:s'),
                            'updated_by' => $updatedByUser,
                            'updated_from_ip' => $updatedFromSystem
                        ];
                        
                        $builder = $this->db->table('physical_hearing_consent_required');
                        $builder->set($data)->where('diary_no', $diary_no)->where('is_deleted', 'f');
                        $update = $builder->update();
                        if ($update) {
                            $affectedRows = $this->db->affectedRows();
                            if ($affectedRows > 0) {
                                $update_flag = 1;
                            }
                        }
                    }
                } else {
                    $error = $this->db->error();
                    pr('Query failed: ' . $error['message']);
                }
                $declineEntryCount++;
            }   
            
            $builder = $this->db->table('physical_hearing_advocate_consent');
            $query = $builder->select('*')
                ->where('diary_no', $diary_no)
                ->where('is_deleted', 'f')
                ->where('bar_id', $advocate_data[1]);
               
            if($builder->countAllResults() > 0) {
                
                $selectQuery = $this->db->table('physical_hearing_advocate_consent')
                    ->select('diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent, bar_id')
                    ->where('diary_no', $diary_no)
                    ->where('is_deleted', 'f')
                    ->where('bar_id', $advocate_data[1])
                    ->getCompiledSelect();

                
                $sql = "INSERT INTO physical_hearing_advocate_consent_log (diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent, bar_id)
                    " . $selectQuery;

                $query = $this->db->query($sql);
                if ($query) {
                    $affectedRows = $this->db->affectedRows();
                    if ($affectedRows > 0) {
                        $update_flag = 1;

                        $data = [
                            'consent' => $advocate_data[0],
                            'updated_on' => date('Y-m-d H:i:s'),
                            'updated_by' => $updatedByUser,
                            'updated_from_ip' => $updatedFromSystem
                        ];
                        
                        $builder = $this->db->table('physical_hearing_advocate_consent');
                        $builder->set($data)
                            ->where('bar_id', $advocate_data[1])
                            ->where('is_deleted', 'f')
                            ->where('diary_no', $diary_no);
                        $update = $builder->update();
                        if ($update) {
                            $affectedRows = $this->db->affectedRows();
                            if ($affectedRows > 0) {
                                $update_flag = 1;
                            }
                        }    
                    }
                }
            } else {

                $builder = $this->db->table('physical_hearing_consent_required');
                $query = $builder->select('diary_no, conn_key, is_fixed, next_dt, is_deleted, vacation_list_year, mainhead')
                    ->where('diary_no', $diary_no)
                    ->where('is_deleted', 'f')
                    ->get();
                $results = $query->getResultArray();
                $insertData = [];
                foreach ($results as $row) {
                    $insertData[] = [
                        'diary_no' => $row['diary_no'],
                        'conn_key' => $row['conn_key'],
                        'is_fixed' => $row['is_fixed'],
                        'next_dt' => $row['next_dt'],
                        'is_deleted' => $row['is_deleted'],
                        'updated_by' => $updatedByUser,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'updated_from_ip' => $updatedFromSystem,
                        'vacation_list_year' => $row['vacation_list_year'],
                        'mainhead' => $row['mainhead'],
                        'consent' => $advocate_data[0],
                        'bar_id' => $advocate_data[1],
                    ];
                }
                
                if (!empty($insertData)) {
                    $this->db->table('physical_hearing_advocate_consent')->insertBatch($insertData);
                    $update_flag=1;
                }
            } 
        }    //End Of For Loop

        if($total_consent_count == $total_p_consent_count && $total_consent_count > 1) {
            
            $builder = $this->db->table('physical_hearing_consent_required');
            $query = $builder->select('diary_no, conn_key, is_fixed, next_dt, is_deleted, updated_by, updated_on, updated_from_ip, vacation_list_year, mainhead, consent')
                ->where('diary_no', $diary_no)
                ->where('is_deleted', 'f')
                ->where('consent', 'P')
                ->get();

            $results = $query->getResultArray();
            //pr($results);
            $insertData = [];
            foreach ($results as $row) {
                $insertData[] = [
                    'diary_no' => $row['diary_no'],
                    'conn_key' => $row['conn_key'],
                    'is_fixed' => $row['is_fixed'],
                    'next_dt' => $row['next_dt'],
                    'is_deleted' => $row['is_deleted'],
                    'updated_by' => $row['updated_by'], 
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_from_ip' => $row['updated_from_ip'],
                    'vacation_list_year' => $row['vacation_list_year'],
                    'mainhead' => $row['mainhead'],
                    'consent' => $row['consent'],
                ];
            }
            
            if (!empty($insertData)) {
                //$this->db->table('physical_hearing_consent_required_log')->insertBatch($insertData);
                $builder = $this->db->table('physical_hearing_consent_required_log');
                $inserted = $builder->insertBatch($insertData);
                if ($inserted) {
                    $affectedRows = $this->db->affectedRows(); 
                    if ($affectedRows > 0) {
                        $update_flag = 1;
                        $data = [
                            'consent' => 'P',
                            'updated_on' => date('Y-m-d H:i:s'), // Set current timestamp
                            'updated_by' => $updatedByUser,
                            'updated_from_ip' => $updatedFromSystem,
                        ];
                        
                        $builder = $this->db->table('physical_hearing_advocate_consent');
                        $builder->set($data)
                                ->where('diary_no', $diary_no)
                                ->where('is_deleted', 'f');
                        $update = $builder->update();
                        if ($update) {
                            $affectedRows = $this->db->affectedRows();
                            if ($affectedRows > 0) {
                                $update_flag = 1;
                            } else {
                                $update_flag=0;
                            }
                        }
                    }
                    
                } else {
                    $update_flag=0;
                }
            }
        }  
        echo $update_flag;  
    }

    public function get_consent_report($case_category, $consent_type)
    {
        $return = [];
        // echo $builder = "SELECT DISTINCT val.diary_no, val.conn_key,
        //             CASE
        //                 WHEN (val.diary_no = val.conn_key OR val.conn_key = 0 OR val.conn_key IS NULL) THEN 0
        //                 ELSE 1
        //             END AS main_or_connected,
        //             val.is_fixed,
        //             CONCAT(m.reg_no_display, ' @ ', 
        //                 CONCAT(SUBSTR(CAST(val.diary_no AS TEXT), 1, LENGTH(CAST(val.diary_no AS TEXT)) - 4), ' / ', 
        //                 SUBSTR(CAST(val.diary_no AS TEXT), -4))) AS case_no,
        //             TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date,
        //             CONCAT(COALESCE(m.pet_name, ''), ' <strong>Vs. </strong>', COALESCE(m.res_name, '')) AS cause_title,
        //             CASE 
        //                 WHEN consent = 'V' THEN 'Virtual'
        //                 WHEN consent = 'P' THEN 'Physical'
        //                 ELSE 'Not Updated' 
        //             END AS final_consent,
        //             val.is_deleted AS declined_by_admin,
        //             (CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END) AS fixed_order,
        //             COALESCE(NULLIF(val.conn_key, 0), val.diary_no) AS conn_key_order
        //         FROM physical_hearing_consent_required val 
        //         INNER JOIN main m ON val.diary_no = m.diary_no
        //         WHERE m.mainhead = '$case_category' AND val.consent = '$consent_type' AND val.is_deleted = 'f'
        //         ORDER BY fixed_order, conn_key_order, main_or_connected ASC";
        // die;
        $builder = "SELECT DISTINCT val.diary_no, val.conn_key, CASE WHEN (val.diary_no = val.conn_key OR val.conn_key = 0 OR val.conn_key IS NULL) 
            THEN 0 ELSE 1 END AS main_or_connected, val.is_fixed, CONCAT(m.reg_no_display, ' @ ', CONCAT(SUBSTR(CAST(val.diary_no AS TEXT), 1, 
            LENGTH(CAST(val.diary_no AS TEXT)) - 4), ' / ', SUBSTR(CAST(val.diary_no AS TEXT), -4))) AS case_no, TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date, 
            CONCAT(COALESCE(m.pet_name, ''), ' Vs. ', COALESCE(m.res_name, '')) AS cause_title, 
            CASE 
                    WHEN val.consent = 'V' THEN 'Virtual' 
                    WHEN val.consent = 'P' THEN 'Physical' 
                    ELSE 'Not Updated' 
                END AS final_consent, 
                val.is_deleted AS declined_by_admin,  -- Added to SELECT list
                CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END AS fixed_order,  -- Added to SELECT list
                CASE 
                    WHEN NULLIF(val.conn_key::TEXT, '') IS NOT NULL THEN val.conn_key 
                    ELSE val.diary_no 
                END AS order_key  -- Added to SELECT list
            FROM physical_hearing_consent_required val 
            INNER JOIN main m ON val.diary_no = m.diary_no 
            WHERE mainhead = '$case_category' 
            AND val.consent = '$consent_type' 
            AND val.is_deleted = 'f'
            ORDER BY 
                fixed_order, 
                order_key, 
                main_or_connected ASC;";

        $queryBuilder = $this->db->query($builder);        
        if ($queryBuilder->getNumRows() >= 1) {
            $results = $queryBuilder->getResultArray();
            $return = $results;
        }
        return $return;

    }

    public function restore_vacation_advance_list($diary_no, $emp_id, $user_id, $updated_from_system)
    {
        
        $return = [];
        $sql = "INSERT INTO vacation_advance_list_log 
        SELECT * 
        FROM vacation_advance_list 
        WHERE diary_no = ? 
          AND is_deleted = 't' 
          AND vacation_list_year = EXTRACT(YEAR FROM NOW())";

                $query = $this->db->query($sql, [$diary_no]);
                if ($query) {
                    $affectedRows = $this->db->affectedRows();
                    if ($affectedRows > 0) {
                        $data = [
                            'is_deleted' => 'f',
                            'updated_on' => date('Y-m-d H:i:s'),
                            'updated_by' => $user_id,
                            'updated_from_ip' => $updated_from_system,
                        ];
                        $this->db->table('vacation_advance_list')
                            ->where('diary_no', $diary_no)
                            ->where('is_deleted', 't')
                            ->where('vacation_list_year', date('Y'))
                            ->update($data);
                            if ($this->db->affectedRows() > 0) {
                                $result = $this->db->table('vacation_advance_list')
                                    ->where('diary_no', $diary_no)
                                    ->where('vacation_list_year', date('Y'))
                                    ->get()
                                    ->getRowArray();
                                $return = $result;   
                            } else {
                                echo "0";
                            }    
                    }
                }
                return  $return;     
    }
}