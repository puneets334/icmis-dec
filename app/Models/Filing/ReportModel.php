<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class ReportModel extends Model
{


    public function __construct()
    {
        parent::__construct();
    }

    function getReport($status, $data)
    {

        $builder = $this->db->table("main m");
        //$builder->selectDistinct();
        $builder->select("m.ack_id, m.diary_no_rec_date, case when c_status='$status' then 'Pending' else 'Disposed' end as status");
        $builder->select("CASE 
                    WHEN m.ack_id <> 0 THEN 'e-filed'
                    WHEN efiled_type = 'new_case' THEN 'e-filed'
                    ELSE ''
                END as isefiled", false);
        $builder->select("CASE 
                    WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
                    WHEN efiled_type = 'new_case' THEN efiling_no
                    ELSE ''
                END as ref_id", false);
        $builder->select("m.diary_no as dno");
        $builder->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder->select("TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') as diary_date", false);
        $builder->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder->select("m.active_fil_dt, m.pet_name, m.res_name");
        $builder->select("b.name as pet_adv_id, m.pet_adv_id as padvid");
        $builder->select("m.c_status, u.name as diary_user_id, m.reg_no_display");
        $builder->select("sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id");
        $builder->select("m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email");
        $builder->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['reg_or_def']) {
            $builder->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
        }
        if ($data['from_date']) {
            $builder->where('m.diary_no_rec_date >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('m.diary_no_rec_date <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        if ($data['cause_title']) {
            $builder->orLike($data['parties']);
        }
        if ($data['case_type_casecode']) {
            $builder->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['isma']) {
            $builder->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if ($data['is_inperson']) {
            $builder->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        $builder->groupStart();
        if ($data['is_efiled_pfield'] = 'pfield') {
            $builder->where('ack_id', 0);
            //$builder->Where('ack_id IS NULL');
        }
        if ($data['is_efiled_pfield'] = 'efield') {
            $builder->orWhere('ack_id', 0);
        }
        $builder->groupEnd();
        $builder->orderBy('m.diary_no_rec_date');
        $builder->orderBy('dno');
        $builder->limit(500);
        $builder = $builder->get();

        return $results = $builder->getResult();

        //echo $this->db->getLastquery();

    }

    function getCaveat($data)
    {

        $db = db_connect();
        $builder = $db->table('caveat m');
        $builder->select([
            "SUBSTRING(caveat_no::text, 1, LENGTH(caveat_no::text) - 4) AS caveat_no1",
            "SUBSTRING(caveat_no::text, -4) AS caveat_year",
            "TO_CHAR(diary_no_rec_date, 'YYYY-MM-DD') AS caveat_date",
            "CASE WHEN fil_no IS NULL THEN 'Not Registered' ELSE fil_no END AS fil_no",
            "pet_name",
            "res_name",
            "b.name AS pet_adv_id",
            "b1.name AS res_adv_id",
            "c_status",
            "u.name AS diary_user_id",
            "sis.name AS ref_agency_state_id",
            "rac.agency_name AS ref_agency_code_id",
            "court_fee",
            "total_court_fee",
            "case_status_id",
            "caveat_no AS c_no",
            "DATE_PART('day', NOW() - diary_no_rec_date) AS no_of_days"
        ]);
        $builder->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder->join('master.bar b1', 'm.res_adv_id = b1.bar_id', 'left');
        $builder->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        if ($data['from_date']) {
            $builder->where('diary_no_rec_date >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('diary_no_rec_date <=', $data['to_date']);
        }
        if ($data['caveat_no']) {
            $builder->where('caveat_no', $data['caveat_no']);
        }
        $builder->where('m.casetype_id', 2);
        if ($data['cause_title']) {
            $builder->Like($data['parties']);
        }
        $builder->orderBy('caveat_no');
        $builder->orderBy('caveat_year');



        return $results = $builder->get()->getResult();
        //echo $this->db->getLastquery();



    }

    function getDak($data)
    {

        $db = db_connect();
        $builder = $db->table('docdetails a');
        $builder->select('a.doccode, a.doccode1, docnum, docyear, a.remark, other1, filedby, iastat, ent_dt, advocate_id, verified, docdesc, c.name as advname, u.name as entryuser, active_fil_no, active_reg_year, short_description');
        $builder->select("left((cast(a.diary_no as text)),-4) as diary_no, right((cast(a.diary_no as text)),4) as diary_year");
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1 AND b.display = \'Y\'', 'left');
        $builder->join('master.bar c', 'advocate_id = bar_id', 'left');
        $builder->join('master.users u', 'a.usercode = u.usercode', 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');
        $builder->join('master.casetype ct', 'casecode = active_casetype_id', 'left');
        // if($data['from_date']){
        // $builder->where('m.diary_no_rec_date >=', $data['from_date']);
        // }
        // if($data['to_date']){
        // $builder->where('m.diary_no_rec_date <=', $data['to_date']);
        // }
        if ($data['document_no']) {
            $builder->where('docnum', $data['document_no']);
        }
        if ($data['doc_year']) {
            $builder->where('docyear', $data['doc_year']);
        }
        $builder->where('a.display', 'Y');
        $builder->limit(500);

        return $data = $builder->get()->getResult();

        //echo $this->db->getLastbuilder();

    }

    function getfileTrap($data)
    {

        $where = '';
        if ($data['diary_no']) {
            $where .= "WHERE diary_no =" . $data['diary_no'] . "";
        }

        $db = db_connect();
        $builder = $db->table('((SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other FROM fil_trap
                                    ' . $where . ') UNION (SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other FROM fil_trap_his ' . $where . ') ) a');
        $builder->select('a.*,u1.name d_by_name,u2.name r_by_name,u3.name o_name,u4.name d_to_name');
        $builder->join('master.users u1', 'd_by_empid = u1.empid', 'left');
        $builder->join('master.users u2', 'r_by_empid = u2.empid', 'left');
        $builder->join('master.users u3', 'other = u3.empid', 'left');
        $builder->join('master.users u4', 'd_to_empid = u4.empid', 'left');
        $builder->orderBy('disp_dt', 'DESC');
        $builder->orderBy('rece_dt', 'DESC');
        $builder->limit(500);

        return $results = $builder->get()->getResult();

        //echo $db->getLastquery();exit;


    }

    function getRefiling($data)
    {

        $db = db_connect();

        $builder = $this->db->table("main m");
        $builder->select("CASE 
             WHEN m.ack_id <> 0 THEN 'e-filed'
             WHEN efiled_type = 'new_case' THEN 'e-filed'
             ELSE ''
         END as isefiled", false);
        $builder->select("CASE 
             WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
             WHEN efiled_type = 'new_case' THEN efiling_no
             ELSE ''
         END as ref_id", false);
        $builder->select("m.diary_no as dno");
        $builder->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder->select("TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') as diary_date", false);
        $builder->select("CASE 
             WHEN m.active_fil_no IS NULL THEN ''
             ELSE 
            CASE 
                 WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                 ELSE m.reg_no_display
             END
         END as fil_no", false);
        $builder->select("m.active_fil_dt, m.pet_name, m.res_name");
        $builder->select("b.name as pet_adv_id, m.pet_adv_id as padvid");
        $builder->select("m.c_status, u.name as diary_user_id, m.reg_no_display");
        $builder->select("sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id");
        $builder->select("m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email");
        $builder->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['from_date']) {
            $builder->where('m.diary_no_rec_date >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('m.diary_no_rec_date <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        $builder->orderBy('m.diary_no_rec_date');
        $builder->orderBy('dno');
        $builder->limit(500);
        $builder = $builder->get();

        return $results = $builder->getResult();

        //echo $db->getLastquery();exit;
    }

    function getCasesearch($data)
    {

        $builder = $this->db->table("main m");
        //$builder->selectDistinct();
        //$builder->select("m.ack_id, m.diary_no_rec_date, case when c_status='$status' then 'Pending' else 'Disposed' end as status");
        $builder->select("CASE 
             WHEN m.ack_id <> 0 THEN 'e-filed'
             WHEN efiled_type = 'new_case' THEN 'e-filed'
             ELSE ''
         END as isefiled", false);
        $builder->select("CASE 
             WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
             WHEN efiled_type = 'new_case' THEN efiling_no
             ELSE ''
         END as ref_id", false);
        $builder->select("m.diary_no as dno");
        $builder->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder->select("TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') as diary_date", false);
        $builder->select("CASE 
             WHEN m.active_fil_no IS NULL THEN ''
             ELSE 
            CASE 
                 WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                 ELSE m.reg_no_display
             END
         END as fil_no", false);
        $builder->select("m.active_fil_dt, m.pet_name, m.res_name");
        $builder->select("b.name as pet_adv_id, m.pet_adv_id as padvid");
        $builder->select("m.c_status, u.name as diary_user_id, m.reg_no_display");
        $builder->select("sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id");
        $builder->select("m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email");
        $builder->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['reg_or_def']) {
            $builder->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
        }
        if ($data['from_date']) {
            $builder->where('m.diary_no_rec_date >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('m.diary_no_rec_date <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        if ($data['cause_title']) {
            $builder->orLike($data['parties']);
        }
        if ($data['case_type_casecode']) {
            $builder->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['isma']) {
            $builder->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if ($data['is_inperson']) {
            $builder->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        $builder->groupStart();
        if ($data['is_efiled_pfield'] = 'pfield') {
            $builder->where('ack_id', 0);
            $builder->orWhere('ack_id IS NULL');
        }
        if ($data['is_efiled_pfield'] = 'efield') {
            $builder->where('ack_id <>', 0);
            $builder->orWhere('ack_id', NULL);
        }
        $builder->groupEnd();
        $builder->orderBy('m.diary_no_rec_date');
        $builder->orderBy('dno');
        $builder->limit(500);
        $builder = $builder->get();

        return $results = $builder->getResult();

        //echo $this->db->getLastquery();

    }

    public function getCaseDetails($condition, $condition1)
    {
        // echo "<pre>"; print_r($condition . $condition1); exit();

        $conditionString = '';
        foreach ($condition as $key => $value) {
            if (is_array($value) && count($value) === 2 && $key === 'date(main.diary_no_rec_date) BETWEEN') {
                // Handling the BETWEEN condition separately
                $conditionString .= "($key '{$value[0]}' AND '{$value[1]}') AND ";
            } else {
                // Handling other conditions
                if (is_array($value)) {
                    // Debug output to identify where the array-to-string conversion occurs
                    var_dump($key, $value);
                }
                $operator = strpos($key, '=') !== false ? '' : '=';
                $conditionString .= "($key $operator '{$value}') AND ";
            }
        }
        $conditionString = rtrim($conditionString, 'AND ');
        $conditionString1 = '';
        foreach ($condition1 as $key => $value) {
            if (is_array($value) && count($value) === 2 && $key === 'date(main_a.diary_no_rec_date) BETWEEN') {
                $conditionString1 .= "($key '{$value[0]}' AND '{$value[1]}') AND ";
            } else {
                if (is_array($value)) {
                    var_dump($key, $value);
                }
                $operator = strpos($key, '=') !== false ? '' : '=';
                $conditionString1 .= "($key $operator '{$value}') AND ";
            }
        }
        $conditionString1 = rtrim($conditionString1, 'AND ');

         $query = "select distinct diary_no,
         isefiled,
         ref_id,
         short_description,
         CauseTitle,
         Diarized_On,
         Diarized_By,
         MAX(lowercourt_entered_on) AS lowercourt_entered_on,
         MAX(lowercourt_entered_by) AS lowercourt_entered_by,
         MAX(Defect_raised_on) AS Defect_raised_on,
         MAX(Defects_notified_by) AS Defects_notified_by,
         MAX(Defects_Removed_on) AS Defects_Removed_on,
         MAX(defects_removed_by) AS defects_removed_by,
         Registration_No,
         MAX(Registered_By) AS Registered_By,
         MAX(verified_on) AS verified_on,
         MAX(Verified_by) AS Verified_by,
         Proposed_to_be_listed_on,
         registered_on

        from(select
                main.diary_no,
                case
                    when ack_id <> 0 then 'e-filed'
                    else ''
                end as isefiled,
                case
                    when ack_id <> 0 then CONCAT(ack_id, '/', ack_rec_dt)
                    else ''
                end as ref_id,
                CONCAT(pet_name, ' VS ', res_name) as CauseTitle,
                TO_CHAR(diary_no_rec_date, 'DD/MM/YYYY HH24:MI:SS') as Diarized_On,
                CONCAT(u.name, ' [', u.empid, ']') as Diarized_By,
                TO_CHAR(lowerct.ent_dt, 'DD/MM/YYYY HH24:MI:SS') as lowercourt_entered_on,
                CONCAT(lwu.name, ' [', lwu.empid, ']') as lowercourt_entered_by,
                TO_CHAR(MIN(coalesce(obj_save_a.save_dt, obj_save.save_dt)), 'DD/MM/YYYY HH24:MI:SS') as Defect_raised_on,
                CONCAT(os.name, ' [', os.empid, ']') as Defects_notified_by,
                TO_CHAR(MIN(coalesce(obj_save_a.rm_dt, obj_save.rm_dt)), 'DD/MM/YYYY HH24:MI:SS') as Defects_Removed_on,
                CONCAT(rs.name, ' [', rs.empid, ']') as defects_removed_by,
                reg_no_display as Registration_No,
                TO_CHAR(fil_dt, 'DD/MM/YYYY HH24:MI:SS') as Registered_on,
                mhu.name as Registered_By,
                TO_CHAR(dv.verification_date, 'DD/MM/YYYY HH24:MI:SS') as verified_on,
                CONCAT(ud.name, ' [', ud.empid, ']') as Verified_by,
                TO_CHAR(MIN(coalesce(heardt.next_dt, ha.next_dt)), 'DD/MM/YYYY HH24:MI:SS') as Proposed_to_be_listed_on,
                casetype.short_description
            from
                main
            left join master.users u on
                main.diary_user_id = u.usercode
            left join defects_verification dv on
                main.diary_no = dv.diary_no
            left join defects_verification_a dva on
                main.diary_no = dva.diary_no
            left join lowerct on
                main.diary_no = lowerct.diary_no
                and lw_display = 'Y'
            left join lowerct_a la on
                main.diary_no = la.diary_no
                and la.lw_display = 'Y'
            left join heardt on
                main.diary_no = heardt.diary_no
            left join heardt_a ha on
                main.diary_no = ha.diary_no
            left join master.users lwu on
                lowerct.usercode = lwu.usercode
            left join obj_save on
                main.diary_no = obj_save.diary_no
                and obj_save.display = 'Y'
            left join obj_save_a on
                main.diary_no = obj_save_a.diary_no
                and obj_save_a.display = 'Y'
            left join master.users os on
                obj_save.usercode = os.usercode
            left join master.users rs on
                obj_save.rm_user_id = rs.usercode
            left join master.users ud on
                dv.user_id = ud.usercode
            left join master.casetype on
                main.casetype_id = casetype.casecode
            left join main_casetype_history mh on
                main.diary_no = mh.diary_no
                and mh.is_deleted = 'f'
            left join main_casetype_history_a mha on
                main.diary_no = mha.diary_no
                and mha.is_deleted = 'f'
            left join master.users mhu on
                mh.adm_updated_by = mhu.usercode
            where
            $conditionString
                        group by
                main.diary_no,
                casetype.short_description,
                u.name,
                u.empid,
                la.lw_display,
                lowerct.ent_dt,
                lwu.name,
                lwu.empid,
                os.name,
                os.empid,
                rs.name,
                rs.empid,
                mhu.name,
                dv.verification_date,
                ud.name,
                ud.empid,
                heardt.next_dt
            
            UNION
            
            select
                main_a.diary_no,
                case
                    when ack_id <> 0 then 'e-filed'
                    else ''
                end as isefiled,
                case
                    when ack_id <> 0 then CONCAT(ack_id, '/', ack_rec_dt)
                    else ''
                end as ref_id,
                CONCAT(pet_name, ' VS ', res_name) as CauseTitle,
                TO_CHAR(diary_no_rec_date, 'DD/MM/YYYY HH24:MI:SS') as Diarized_On,
                CONCAT(u.name, ' [', u.empid, ']') as Diarized_By,
                TO_CHAR(lowerct.ent_dt, 'DD/MM/YYYY HH24:MI:SS') as lowercourt_entered_on,
                CONCAT(lwu.name, ' [', lwu.empid, ']') as lowercourt_entered_by,
                TO_CHAR(MIN(coalesce(obj_save_a.save_dt, obj_save.save_dt)), 'DD/MM/YYYY HH24:MI:SS') as Defect_raised_on,
                CONCAT(os.name, ' [', os.empid, ']') as Defects_notified_by,
                TO_CHAR(MIN(coalesce(obj_save_a.rm_dt, obj_save.rm_dt)), 'DD/MM/YYYY HH24:MI:SS') as Defects_Removed_on,
                CONCAT(rs.name, ' [', rs.empid, ']') as defects_removed_by,
                reg_no_display as Registration_No,
                TO_CHAR(fil_dt, 'DD/MM/YYYY HH24:MI:SS') as Registered_on,
                mhu.name as Registered_By,
                TO_CHAR(dv.verification_date, 'DD/MM/YYYY HH24:MI:SS') as verified_on,
                CONCAT(ud.name, ' [', ud.empid, ']') as Verified_by,
                TO_CHAR(MIN(coalesce(heardt.next_dt, ha.next_dt)), 'DD/MM/YYYY HH24:MI:SS') as Proposed_to_be_listed_on,
                casetype.short_description
            from
                main_a
            left join master.users u on
                main_a.diary_user_id = u.usercode
            left join defects_verification dv on
                main_a.diary_no = dv.diary_no
            left join defects_verification_a dva on
                main_a.diary_no = dva.diary_no
            left join lowerct on
                main_a.diary_no = lowerct.diary_no
                and lw_display = 'Y'
            left join lowerct_a la on
                main_a.diary_no = la.diary_no
                and la.lw_display = 'Y'
            left join heardt on
                main_a.diary_no = heardt.diary_no
            left join heardt_a ha on
                main_a.diary_no = ha.diary_no
            left join master.users lwu on
                lowerct.usercode = lwu.usercode
            left join obj_save on
                main_a.diary_no = obj_save.diary_no
                and obj_save.display = 'Y'
            left join obj_save_a on
                main_a.diary_no = obj_save_a.diary_no
                and obj_save_a.display = 'Y'
            left join master.users os on
                obj_save.usercode = os.usercode
            left join master.users rs on
                obj_save.rm_user_id = rs.usercode
            left join master.users ud on
                dv.user_id = ud.usercode
            left join master.casetype on
                main_a.casetype_id = casetype.casecode
            left join main_casetype_history mh on
                main_a.diary_no = mh.diary_no
                and mh.is_deleted = 'f'
            left join main_casetype_history_a mha on
                main_a.diary_no = mha.diary_no
                and mha.is_deleted = 'f'
            left join master.users mhu on
                mh.adm_updated_by = mhu.usercode
            where
            $conditionString1
            
            group by
                main_a.diary_no,
                casetype.short_description,
                u.name,
                u.empid,
                la.lw_display,
                lowerct.ent_dt,
                lwu.name,
                lwu.empid,
                os.name,
                os.empid,
                rs.name,
                rs.empid,
                mhu.name,
                dv.verification_date,
                ud.name,
                ud.empid,
                heardt.next_dt
                )aa  group by aa.diary_no,isefiled,ref_id,short_description,CauseTitle,Diarized_On,Diarized_By,Registration_No,Proposed_to_be_listed_on,registered_on
    
    
    ";

        $results = $this->db->query($query)->getResult();

        return $results;
    }
	
	
	
	public function CashtrapList($condition, $condition1)
	{
		 $conditionString = '';
        foreach ($condition as $key => $value) {
            if (is_array($value) && count($value) === 2 && $key === 'date(main.diary_no_rec_date) BETWEEN') {
                // Handling the BETWEEN condition separately
                $conditionString .= "($key '{$value[0]}' AND '{$value[1]}') AND ";
            } else {
                // Handling other conditions
                if (is_array($value)) {
                    // Debug output to identify where the array-to-string conversion occurs
                    var_dump($key, $value);
                }
                $operator = strpos($key, '=') !== false ? '' : '=';
                $conditionString .= "($key $operator '{$value}') AND ";
            }
        }
        $conditionString = rtrim($conditionString, 'AND ');
        $conditionString1 = '';
        foreach ($condition1 as $key => $value) {
            if (is_array($value) && count($value) === 2 && $key === 'date(main_a.diary_no_rec_date) BETWEEN') {
                $conditionString1 .= "($key '{$value[0]}' AND '{$value[1]}') AND ";
            } else {
                if (is_array($value)) {
                    var_dump($key, $value);
                }
                $operator = strpos($key, '=') !== false ? '' : '=';
                $conditionString1 .= "($key $operator '{$value}') AND ";
            }
        }
        $conditionString1 = rtrim($conditionString1, 'AND ');

	 
$sql = "
    SELECT 
        main.diary_no,
        CASE WHEN ack_id <> 0 THEN 'e-filed' ELSE '' END AS isefiled,
        CASE WHEN ack_id <> 0 THEN CONCAT(ack_id,'/',ack_rec_dt) ELSE '' END AS ref_id,
        short_description,
        CONCAT(pet_name,' VS ', res_name) AS CauseTitle,
        TO_CHAR(diary_no_rec_date, 'DD/MM/YYYY HH24:MI:SS') AS Diarized_On,
        CONCAT(u.name,' [', u.empid,']') AS Diarized_By,
        TO_CHAR(lowerct.ent_dt,'DD/MM/YYYY HH24:MI:SS') AS \"lowercourt_entered_on\",
        CONCAT(lwu.name,' [', lwu.empid,']') AS \"Lower_court_entered_by\",
        TO_CHAR(MIN(save_dt), 'DD/MM/YYYY HH24:MI:SS') AS \"Defect_raised_on\",
        CONCAT(os.name,' [', os.empid,']') AS \"Defects_notified_by\",
        TO_CHAR(MAX(rm_dt), 'DD/MM/YYYY HH24:MI:SS') AS \"Defects_Removed_on\",
        CONCAT(rs.name,' [', rs.empid,']') AS \"defects_removed_by\",
        reg_no_display AS \"Registration_No\",
        TO_CHAR(fil_dt, 'DD/MM/YYYY HH24:MI:SS') AS \"Registered_on\",
        mhu.name AS \"Registered_By\",
        TO_CHAR(verification_date, 'DD/MM/YYYY HH24:MI:SS') AS \"verified_on\",
        CONCAT(ud.name,' [', ud.empid,']') AS \"Verified_by\",
        TO_CHAR(next_dt, 'DD/MM/YYYY HH24:MI:SS') AS \"Proposed_to_be_listed_on\"
    FROM 
        main
    LEFT JOIN 
        master.users u ON main.diary_user_id = u.usercode
    LEFT JOIN 
        defects_verification dv ON main.diary_no = dv.diary_no
    LEFT JOIN 
        lowerct ON main.diary_no = lowerct.diary_no AND lw_display = 'Y'
    LEFT JOIN 
        heardt ON main.diary_no = heardt.diary_no
    LEFT JOIN 
        master.users lwu ON lowerct.usercode = lwu.usercode
    LEFT JOIN 
        obj_save ON main.diary_no = obj_save.diary_no AND obj_save.display = 'Y'
    LEFT JOIN 
        master.users os ON obj_save.usercode = os.usercode
    LEFT JOIN 
        master.users rs ON obj_save.rm_user_id = rs.usercode
    LEFT JOIN 
        master.users ud ON dv.user_id = ud.usercode
    LEFT JOIN 
        master.casetype ON main.casetype_id = casetype.casecode
    LEFT JOIN 
        main_casetype_history mh ON main.diary_no = mh.diary_no AND mh.is_deleted = 'f'
    LEFT JOIN 
        master.users mhu ON mh.adm_updated_by = mhu.usercode
    WHERE 
        $conditionString
    GROUP BY 
        main.diary_no,       
                casetype.short_description,
                u.name,
                u.empid,
                lowerct.lw_display,
                lowerct.ent_dt,
                lwu.name,
                lwu.empid,
                os.name,
                os.empid,
                rs.name,
                rs.empid,
                mhu.name,
                dv.verification_date,
                ud.name,
                ud.empid,
                heardt.next_dt
    ORDER BY 
        diary_no_rec_date
";

$query = $this->db->query($sql);
 
return $result = $query->getResult();

if (!$result) {
    throw new \Exception('SQL query error');
}

	}
}
