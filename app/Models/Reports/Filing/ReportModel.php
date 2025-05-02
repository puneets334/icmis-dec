<?php

namespace App\Models\Reports\Filing;

use CodeIgniter\Model;

//use CodeIgniter\Database\BaseBuilder;


class ReportModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getDiaryCauseTitleSearch($data)
    {

        $builder = $this->db->table("main m");
        //$builder->selectDistinct();
        $builder->select("m.ack_id, m.diary_no_rec_date, case when c_status='P' then 'Pending' else 'Disposed' end as status,short_description");
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
            // $builder->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
            $subQuery = $this->db->table('obj_save')
                ->select('diary_no')
                ->where('display', 'Y')
                ->where('org_id !=', '10193');

            $builder->groupStart();
            $builder->whereIn('m.diary_no', $subQuery, false);
            $builder->orWhere('active_fil_no is not null');
            $builder->where('active_fil_no !=', '');
            $builder->groupEnd();
        }
        // if ($data['from_date']) {
        //     $builder->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        // }
        // if ($data['to_date']) {
        //     $builder->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        // }

        if ($data['from_date'] && $data['to_date']) {
            $builder->where("date_trunc('day', diary_no_rec_date) BETWEEN '" . $data['from_date'] . "' AND '" . $data['to_date'] . "' ");
        }

        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        //        if($data['status']!='All'){
        //            $builder->where('m.c_status', $data['status']);
        //        }
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

        if ($data['is_efiled_pfiled'] == 'pfiled') {
            $builder->groupStart();
            $builder->where('ack_id', 0);
            $builder->orWhere('ack_id IS NULL');
            $builder->groupEnd();

            $builder->groupStart();
            $builder->where('efiling_no', '');
            $builder->orWhere('efiling_no IS NULL');
            $builder->groupEnd();
            // $builder->orWhere('ack_id IS NULL');
            //$builder->Where('ack_id IS NULL');
        }
        if ($data['is_efiled_pfiled'] == 'efiled') {
            $builder->groupStart();
            $builder->where('ack_id <>', 0);
            $builder->Where('ack_id IS NOT NULL');
            $builder->groupEnd();
        }

        //  $builder->orderBy('m.diary_no_rec_date desc');
        $builder->orderBy('dno');
        // $queryString = $builder->getCompiledSelect();
        // echo $queryString;
        // exit();
        $builder = $builder->get()->getResult();

        $builder2 = $this->db->table("main_a m");
        //$builder->selectDistinct();
        $builder2->select("m.ack_id, m.diary_no_rec_date, case when c_status='P' then 'Pending' else 'Disposed' end as status,short_description");
        $builder2->select("CASE 
                    WHEN m.ack_id <> 0 THEN 'e-filed'
                    WHEN efiled_type = 'new_case' THEN 'e-filed'
                    ELSE ''
                END as isefiled", false);
        $builder2->select("CASE 
                    WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
                    WHEN efiled_type = 'new_case' THEN efiling_no
                    ELSE ''
                END as ref_id", false);
        $builder2->select("m.diary_no as dno");
        $builder2->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder2->select("TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') as diary_date", false);
        $builder2->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder2->select("m.active_fil_dt, m.pet_name, m.res_name");
        $builder2->select("b.name as pet_adv_id, m.pet_adv_id as padvid");
        $builder2->select("m.c_status, u.name as diary_user_id, m.reg_no_display");
        $builder2->select("sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id");
        $builder2->select("m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email");
        $builder2->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder2->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder2->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder2->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder2->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder2->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder2->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['reg_or_def']) {
            // $builder2->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
            $subQuery2 = $this->db->table('obj_save')
                ->select('diary_no')
                ->where('display', 'Y')
                ->where('org_id !=', '10193');

            $builder2->groupStart();
            $builder2->whereIn('m.diary_no', $subQuery2, false);
            $builder2->orWhere('active_fil_no is not null');
            // $builder2->orWhere('active_fil_no !=', '');
            $builder2->where('active_fil_no !=', '');
            $builder2->groupEnd();
        }
        // if ($data['from_date']) {
        //     $builder2->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        // }
        // if ($data['to_date']) {
        //     $builder2->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        // }

        if ($data['from_date'] && $data['to_date']) {
            $builder2->where("date_trunc('day', diary_no_rec_date) BETWEEN '" . $data['from_date'] . "' AND '" . $data['to_date'] . "'");
        }

        if ($data['diary_no']) {
            $builder2->where('m.diary_no', $data['diary_no']);
        }
        //        if($data['status']!='All'){
        //            $builder2->where('m.c_status', $data['status']);
        //        }
        if ($data['cause_title']) {
            $builder2->orLike($data['parties']);
        }
        if ($data['case_type_casecode']) {
            $builder2->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['isma']) {
            $builder2->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if ($data['is_inperson']) {
            $builder2->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        if ($data['is_efiled_pfiled'] == 'pfiled') {
            // $builder2->where('ack_id', 0);
            // $builder2->orWhere('ack_id IS NULL');
            // //$builder->Where('ack_id IS NULL');
            // $builder2->groupEnd();

            $builder2->groupStart();
            $builder2->where('ack_id', 0);
            $builder2->orWhere('ack_id IS NULL');
            $builder2->groupEnd();

            $builder2->groupStart();
            $builder2->where('efiling_no', '');
            $builder2->orWhere('efiling_no IS NULL');
            $builder2->groupEnd();
        }
        if ($data['is_efiled_pfiled'] == 'efiled') {
            $builder2->groupStart();
            $builder2->where('ack_id <>', 0);
            $builder2->Where('ack_id IS NOT NULL');
            $builder2->groupEnd();
        }

        //  $builder2->orderBy('m.diary_no_rec_date desc');
        $builder2->orderBy('dno');
        // $queryString = $builder2->getCompiledSelect();
        // echo $queryString;
        // exit();
        $builder2 = $builder2->get()->getResult();

        return $result = array_merge($builder, $builder2);
    }

    function getDiaryPartySearch($data)
    {
        //pr($data);
        $statusvar = '';
        if ($data['status'] != 'All') {
            $statusvar = ' AND m.c_status = \'' . $data['status'] . '\'';
        }
        $builder = $this->db->table('party p');
        $builder->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder->select("CONCAT(left((cast(p.diary_no as text)),-4), '-', right((cast(p.diary_no as text)),4)) AS diary_number,p.diary_no, p.partyname, p.pet_res, p.sr_no, p.sr_no_show,m.reg_no_display, m.pno, m.rno,m.ack_id, m.diary_no_rec_date,m.c_status,m.reg_no_display");
        $builder->join('main m', "m.diary_no = p.diary_no $statusvar", 'inner');
        if (!empty($data['diary_no'])) {
            $builder->where('p.diary_no', $data['diary_no']);
        }
        /*if (!empty($data['diary_no'])) {
            $builder->where('left((cast(p.diary_no as text)),-4)', $data['diary_no']);
        }
        
        if (!empty($data['diary_year'])) {
            $builder->where('right((cast(p.diary_no as text)),4)', $data['diary_year']);
        } */
        if ($data['parties']) {
            $builder->like($data['parties']);
        }
        if (isset($data['party_type'])) {
            $builder->where($data['party_type']);
        }

        if (!empty($data['from_date']) && !empty($data['to_date'])) {
            //$builder->where('DATE(ent_dt) >=', $data['from_date']);
            //$builder->where('DATE(ent_dt) <=', $data['to_date']);
            $builder->where("date_trunc('day', diary_no_rec_date) BETWEEN '" . $data['from_date'] . "' AND '" . $data['to_date'] . "'");
        
        }

        $builder->orderBy('p.diary_no');
        //$builder->orderBy('LEFT(CAST(p.diary_no AS TEXT), -4)');
        //$builder->orderBy('right((cast(p.diary_no as text)),4)');
        $builder_result = $builder->get()->getResult();
         
        //  echo $this->db->getLastquery();exit;
        $builder2 = $this->db->table('party_a p');
        $builder2->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder2->select("CONCAT(left((cast(p.diary_no as text)),-4), '-', right((cast(p.diary_no as text)),4)) AS diary_number,p.diary_no, p.partyname, p.pet_res, p.sr_no, p.sr_no_show,m.reg_no_display, m.pno, m.rno,m.ack_id, m.diary_no_rec_date,m.c_status,m.reg_no_display");
        $builder2->join('main_a m', "m.diary_no = p.diary_no $statusvar", 'inner');
        if (!empty($data['diary_no'])) {
            $builder2->where('p.diary_no', $data['diary_no']);
        }
        /* if (!empty($data['diary_no'])) {
             $builder2->where('left((cast(p.diary_no as text)),-4)', $data['diary_no']);
         }
        
        if (!empty($data['diary_year'])) {
            $builder2->where('right((cast(p.diary_no as text)),4)', $data['diary_year']);
        } */
        if ($data['parties']) {
            $builder2->like($data['parties']);
        }
        if (isset($data['party_type'])) {
            $builder2->where($data['party_type']);
        }

        if (!empty($data['from_date']) && !empty($data['to_date'])) {
            //$builder2->where('DATE(ent_dt) >=', $data['from_date']);
            //$builder2->where('DATE(ent_dt) <=', $data['to_date']);
            $builder2->where("date_trunc('day', diary_no_rec_date) BETWEEN '" . $data['from_date'] . "' AND '" . $data['to_date'] . "'");
        
        }
        $builder2->orderBy('p.diary_no');
        //$builder2->orderBy('LEFT(CAST(p.diary_no AS TEXT), -4)');
        //$builder2->orderBy('right((cast(p.diary_no as text)),4)');
        $builder2_result = $builder2->get()->getResult();
        //   echo $this->db->getLastQuery(); exit;

        $result = array_merge($builder_result, $builder2_result);
        return $result;
        //echo '<pre>'; print_r($result);exit;

    }

    function getDiary($data)
    {

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
        $builder->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder->select('m.active_fil_dt, m.pet_name, m.res_name,b.name as pet_adv_id, m.pet_adv_id as padvid,
        m.ack_id,m.c_status,m.diary_no_rec_date, u.name as diary_user_id, m.reg_no_display,sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id,
        m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email');

        $builder->join('party p', 'm.diary_no=p.diary_no', 'inner');
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
            $builder->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        if ($data['status'] != 'All') {
            $builder->where('m.c_status', $data['ddl_status']);
        }
        if ($data['cause_title'] && $data['ddl_party_type'] != 'All') {
            $builder->Like($data['parties']);
        } else {
            $builder->orLike($data['parties']);
            $builder->where($data['party_type']);
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

        if ($data['is_efiled_pfiled'] == 'pfiled') {
            $builder->groupStart();
            $builder->where('ack_id', 0);
            $builder->orWhere('ack_id IS NULL');
            //$builder->Where('ack_id IS NULL');
            $builder->groupEnd();
        }
        if ($data['is_efiled_pfiled'] == 'efiled') {
            $builder->groupStart();
            $builder->where('ack_id <>', 0);
            $builder->Where('ack_id IS NOT NULL');
            $builder->groupEnd();
        }
        $builder->orderBy('dno');
        $builder = $builder->get()->getResult();
        //   echo $this->db->getLastQuery(); exit;
        $builder2 = $this->db->table("main_a m");
        //$builder->selectDistinct();
        $builder2->select("CASE 
                    WHEN m.ack_id <> 0 THEN 'e-filed'
                    WHEN efiled_type = 'new_case' THEN 'e-filed'
                    ELSE ''
                END as isefiled", false);
        $builder2->select("CASE 
                    WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
                    WHEN efiled_type = 'new_case' THEN efiling_no
                    ELSE ''
                END as ref_id", false);
        $builder2->select("m.diary_no as dno");
        $builder2->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder2->select("CASE 
                    WHEN m.active_fil_no IS NULL THEN ''
                    ELSE 
                   CASE 
                        WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                        ELSE m.reg_no_display
                    END
                END as fil_no", false);
        $builder2->select('m.active_fil_dt, m.pet_name, m.res_name,b.name as pet_adv_id, m.pet_adv_id as padvid,
        m.ack_id,m.c_status,m.diary_no_rec_date, u.name as diary_user_id, m.reg_no_display,sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id,
        m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email');
        $builder2->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder2->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder2->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder2->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder2->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder2->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder2->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['reg_or_def']) {
            $builder2->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
        }
        if ($data['from_date']) {
            $builder2->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder2->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder2->where('m.diary_no', $data['diary_no']);
        }
        if ($data['status'] != 'All') {
            $builder2->where('m.c_status', $data['ddl_status']);
        }
        if ($data['cause_title'] && $data['ddl_party_type'] != 'All') {
            $builder2->Like($data['parties']);
        } else {
            $builder2->orLike($data['parties']);
        }
        if ($data['case_type_casecode']) {
            $builder2->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['isma']) {
            $builder2->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if ($data['is_inperson']) {
            $builder2->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        if ($data['is_efiled_pfiled'] == 'pfiled') {
            $builder2->groupStart();
            $builder2->where('ack_id', 0);
            $builder2->orWhere('ack_id IS NULL');
            //$builder->Where('ack_id IS NULL');
            $builder2->groupEnd();
        }
        if ($data['is_efiled_pfiled'] == 'efiled') {
            $builder2->groupStart();
            $builder2->where('ack_id <>', 0);
            $builder2->Where('ack_id IS NOT NULL');
            $builder2->groupEnd();
        }
        $builder2->orderBy('dno');
        $builder2 = $builder2->get()->getResult();
        //echo $this->db->getLastquery();

        return $result = array_merge($builder, $builder2);
    }

    function getCaveatCauseTitleSearch($data)
    {

        $builder = $this->db->table('caveat c');
        $builder->select(
            "SUBSTRING(c.caveat_no::text, 1, LENGTH(c.caveat_no::text) - 4) AS caveat_no1,
            right(c.caveat_no::text, 4) AS caveat_year,
            TO_CHAR(c.diary_no_rec_date, 'YYYY-MM-DD') AS caveat_date,
            c.pet_name,
            c.res_name,
            b.name AS pet_adv_id,
            u.name AS diary_user_id,
            sis.name AS ref_agency_state_id,
            rac.agency_name AS ref_agency_code_id,
            c.court_fee,
            c.total_court_fee,
            c.caveat_no AS c_no,
            DATE_PART('day', NOW() - c.diary_no_rec_date) AS no_of_days,
            array_to_string(array_agg(distinct cdm.diary_no),',') diary_no,string_agg(distinct b_m.name,',') as main_adv,string_agg(distinct b_ma.name,',') as main_a_adv,,string_agg(distinct type_sname||'-'||lct_caseno||'/'||lct_caseyear||'#'||TO_CHAR(lct_dec_dt, 'DD-MM-YYYY'),',') as ct_details"
        );
        $builder->join('master.bar b', 'c.pet_adv_id = b.bar_id', 'left', false);
        $builder->join('master.users u', 'c.diary_user_id = u.usercode', 'left', false);
        $builder->join('master.state sis', 'c.ref_agency_state_id = sis.id_no', 'left', false);
        $builder->join('master.ref_agency_code rac', 'c.ref_agency_code_id = rac.id', 'left', false);
        $builder->join('caveat_diary_matching cdm', 'c.caveat_no = cdm.caveat_no and cdm.display=\'Y\'', 'left', false);
        $builder->join('main m', 'cdm.diary_no = m.diary_no ', 'left', false);
        $builder->join('main_a ma', 'cdm.diary_no = ma.diary_no', 'left', false);
        $builder->join('master.bar b_m', 'm.pet_adv_id = b_m.bar_id', 'left', false);
        $builder->join('master.bar b_ma', 'ma.pet_adv_id = b_ma.bar_id', 'left', false);
        $builder->join('caveat_lowerct cl', 'c.caveat_no=cl.caveat_no', 'left', false);
        $builder->join('master.lc_hc_casetype lhc', 'cl.lct_casetype=lhc.lccasecode', 'left', false);
        // $builder->where('cdm.display','Y');
        if ($data['from_date']) {
            $builder->where('date(c.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(c.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['caveat_no']) {
            $builder->where('c.caveat_no', $data['caveat_no']);
        }
        if ($data['case_type_casecode']) {
            $builder->whereIn('c.casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['status'] != 'All') {
            if ($data['status'] == 'P') {
                $builder->where('DATE_PART(\'day\', NOW() - c.diary_no_rec_date)<=', 90);
            }
            if ($data['status'] == 'D') {
                $builder->where('DATE_PART(\'day\', NOW() - c.diary_no_rec_date)>', 90);
            }
        }
        if (!empty($data['cause_title'])) {
            if ($data['ddl_party_type'] != 'All') {
                $builder->Like($data['parties']);
            } else {
                $builder->orLike($data['parties']);
            }
        }
        $builder->groupBy("c.caveat_no,c.diary_no_rec_date,c.pet_name,c.res_name, b.name, u.name,sis.name,rac.agency_name,c.court_fee");
        $builder->groupBy("c.total_court_fee");
        //  $builder->orderBy('caveat_year');
        //  $builder->orderBy('caveat_no1');
        $builder->orderBy('c.caveat_no');
        //echo $builder->getCompiledSelect();
        $builder = $builder->get()->getResult();
        /** Caveat_a archive table **/
        $builder2 = $this->db->table('caveat_a c');
        $builder2->select(
            "SUBSTRING(c.caveat_no::text, 1, LENGTH(c.caveat_no::text) - 4) AS caveat_no1,
            right(c.caveat_no::text, 4) AS caveat_year,
            TO_CHAR(c.diary_no_rec_date, 'YYYY-MM-DD') AS caveat_date,
            c.pet_name,
            c.res_name,
            b.name AS pet_adv_id,
            u.name AS diary_user_id,
            sis.name AS ref_agency_state_id,
            rac.agency_name AS ref_agency_code_id,
            c.court_fee,
            c.total_court_fee,
            c.caveat_no AS c_no,
            DATE_PART('day', NOW() - c.diary_no_rec_date) AS no_of_days,
            array_to_string(array_agg(distinct cdm.diary_no),',') diary_no,string_agg(distinct b_m.name,',') as main_adv,string_agg(distinct b_ma.name,',') as main_a_adv,string_agg(distinct type_sname||'-'||lct_caseno||'/'||lct_caseyear||'#'||TO_CHAR(lct_dec_dt, 'DD-MM-YYYY'),',') as ct_details"
        );
        /* ,string_agg(type_sname||'-'||lct_caseno||'/'||lct_caseyear||'#'||TO_CHAR(lct_dec_dt, 'YYYY-MM-DD'),',') as ct_details*/
        $builder2->join('master.bar b', 'c.pet_adv_id = b.bar_id', 'left', false);
        $builder2->join('master.users u', 'c.diary_user_id = u.usercode', 'left', false);
        $builder2->join('master.state sis', 'c.ref_agency_state_id = sis.id_no', 'left', false);
        $builder2->join('master.ref_agency_code rac', 'c.ref_agency_code_id = rac.id', 'left', false);
        $builder2->join('caveat_diary_matching cdm', 'c.caveat_no = cdm.caveat_no and cdm.display=\'Y\'', 'left', false);
        $builder2->join('main m', 'cdm.diary_no = m.diary_no', 'left', false);
        $builder2->join('main_a ma', 'cdm.diary_no = ma.diary_no', 'left', false);
        $builder2->join('master.bar b_m', 'm.pet_adv_id = b_m.bar_id', 'left', false);
        $builder2->join('master.bar b_ma', 'ma.pet_adv_id = b_ma.bar_id', 'left', false);
        $builder2->join('caveat_lowerct_a cl', 'c.caveat_no=cl.caveat_no', 'left', false);
        $builder2->join('master.lc_hc_casetype lhc', 'cl.lct_casetype=lhc.lccasecode', 'left', false);
        //$builder2->where('cdm.display','Y');
        if ($data['from_date']) {
            $builder2->where('date(c.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder2->where('date(c.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['caveat_no']) {
            $builder2->where('c.caveat_no', $data['caveat_no']);
        }
        if ($data['case_type_casecode']) {
            $builder2->whereIn('c.casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['status'] != 'All') {
            if ($data['status'] == 'P') {
                $builder2->where('DATE_PART(\'day\', NOW() - c.diary_no_rec_date)<=', 90);
            }
            if ($data['status'] == 'D') {
                $builder2->where('DATE_PART(\'day\', NOW() - c.diary_no_rec_date)>', 90);
            }
        }
        if (!empty($data['cause_title'])) {
            if ($data['ddl_party_type'] != 'All') {
                $builder2->Like($data['parties']);
            } else {
                $builder2->orLike($data['parties']);
            }
        }
        $builder2->groupBy("c.caveat_no,c.diary_no_rec_date,c.pet_name,c.res_name, b.name, u.name,sis.name,rac.agency_name,c.court_fee");
        $builder2->groupBy("c.total_court_fee");
        //  $builder2->orderBy('caveat_year');
        //  $builder2->orderBy('caveat_no1');
        $builder2->orderBy('c.caveat_no');

        // echo $builder2->getCompiledSelect(); exit();
        $builder2 = $builder2->get()->getResult();
        //var_dump($builder2);
        return $result = array_merge($builder, $builder2);
    }

    function getCaveatPartySearch($data)
    {
        $builder = $this->db->table('caveat_party c');
        $builder->select("CONCAT(left((cast(caveat_no as text)),-4), '-', right((cast(caveat_no as text)),4)) AS diary_no , partyname, pet_res");
        if (!empty($data['caveat_year'])) {
            $builder->where('right((cast(caveat_no as text)),4)', $data['caveat_year']);
        }
        if ($data['parties']) {
            $builder->like($data['parties']);
        }
        if (isset($data['party_type'])) {
            $builder->where($data['party_type']);
        }
        $builder->orderBy('LEFT(CAST(caveat_no AS TEXT), -4)');
        $builder->orderBy('right((cast(caveat_no as text)),4)');
        $builder = $builder->get()->getResult();
        $builder2 = $this->db->table('caveat_party_a c');
        $builder2->select("CONCAT(left((cast(caveat_no as text)),-4), '-', right((cast(caveat_no as text)),4)) AS diary_no , partyname, pet_res");
        if (!empty($data['caveat_year'])) {
            $builder2->where('right((cast(caveat_no as text)),4)', $data['caveat_year']);
        }
        if ($data['parties']) {
            $builder2->like($data['parties']);
        }
        if (isset($data['party_type'])) {
            $builder2->where($data['party_type']);
        }
        $builder2->orderBy('LEFT(CAST(caveat_no AS TEXT), -4)');
        $builder2->orderBy('right((cast(caveat_no as text)),4)');
        $builder2 = $builder2->get()->getResult();
        $result = array_merge($builder, $builder2);
        // echo $this->db->getLastquery();exit;
        return $result;
        //echo '<pre>'; print_r($result);exit;

    }

    function getDak($data)
    {
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
        $dak_users = array(env('DAK_USER'));
        //echo $dak_users;exit();
        $subQuery = $this->db->table('master.users')
            ->select('usercode')
            ->whereIn('empid', [2011, 2130, 4121, 4518, 4265, 4295, 4361, 4371, 4384, 4389, 4574, 4592, 4595, 4621, 4939, 4940, 4974, 4975, 4345, 4984, 2654, 4659, 5008, 4566]);
        //->whereIn('empid', $dak_users);
        $query = $this->db->table('docdetails dc')
            ->select("remark,DATE(dc.ent_dt) AS dak_date,
              CASE
                  WHEN dc.diary_no IS NULL THEN 'Others'
                  ELSE CAST(dc.diary_no AS VARCHAR)
              END AS section,
              dc.diary_no AS case_da,
              CONCAT(left((cast(dc.diary_no as text)),-4), '/', right((cast(dc.diary_no as text)),-4)) AS diary_no,
              m.reg_no_display AS case_no,
              CONCAT(m.pet_name, ' Vs ', m.res_name) AS causetitle,
              dm.docdesc,
              CONCAT(dc.docnum, '/', dc.docyear) AS document")
            ->join('master.docmaster dm', 'dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1', 'LEFT')
            ->join('main m', 'dc.diary_no = m.diary_no', 'LEFT')
            ->where('date(dc.ent_dt) >=', $fromDate)
            ->where('date(dc.ent_dt) <=', $toDate)
            ->whereIn('dc.usercode', $subQuery, false)
            ->where('dm.display', 'Y')
            ->where('dc.display', 'Y')
            ->orderBy('dc.diary_no')
            ->orderBy('dc.docyear')
            ->orderBy('dc.docnum');
        
        $builder = $query->get()->getResult();
        /****archive table*/
        $subQuery2 = $this->db->table('master.users')
            ->select('usercode')
            ->whereIn('empid', [2011, 2130, 4121, 4518, 4265, 4295, 4361, 4371, 4384, 4389, 4574, 4592, 4595, 4621, 4939, 4940, 4974, 4975, 4345, 4984, 2654, 4659, 5008, 4566]);

        $query2 = $this->db->table('docdetails_a dc')
            ->select("remark,DATE(dc.ent_dt) AS dak_date,
              CASE
                  WHEN dc.diary_no IS NULL THEN 'Others'
                  ELSE CAST(dc.diary_no AS VARCHAR)
              END AS section,
              dc.diary_no AS case_da,
              CONCAT(left((cast(dc.diary_no as text)),-4), '/', right((cast(dc.diary_no as text)),-4)) AS diary_no,
              m.reg_no_display AS case_no,
              CONCAT(m.pet_name, ' Vs ', m.res_name) AS causetitle,
              dm.docdesc,
              CONCAT(dc.docnum, '/', dc.docyear) AS document")
            ->join('master.docmaster dm', 'dc.doccode = dm.doccode AND dc.doccode1 = dm.doccode1', 'LEFT')
            ->join('main m', 'dc.diary_no = m.diary_no', 'LEFT')
            ->where('date(dc.ent_dt) >=', $fromDate)
            ->where('date(dc.ent_dt) <=', $toDate)
            ->whereIn('dc.usercode', $subQuery2, false)
            ->where('dm.display', 'Y')
            ->where('dc.display', 'Y')
            ->orderBy('dc.diary_no')
            ->orderBy('dc.docyear')
            ->orderBy('dc.docnum');

        $builder2 = $query2->get()->getResult();
        return $result = array_merge($builder, $builder2);
    }

    function getDakcb($data)
    {
        $builder = $this->db->table('loose_block a');
        $builder->select('a.id,	reason_blk,	section_name,	pet_name,	res_name,	a.ent_dt,	u.name');
        $builder->select("left((cast(a.diary_no as text)),-4) as diary_no, right((cast(a.diary_no as text)),4) as diary_year");
        $builder->join('master.users u', 'a.usercode = u.usercode ', 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');
        $builder->join('master.usersection c', 'u.section = c.id', 'left');
        $builder->join('docdetails_a da', 'da.diary_no = m.diary_no', 'left');
        if ($data['from_date']) {
            $builder->where('date(a.ent_dt) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(a.ent_dt) <=', $data['to_date']);
        }
        $builder->where('a.display', 'Y');
        $builder->limit(5000);
        return $result = $builder->get()->getResult();
    }
    /* function getDAKSectionWiseDetails($data)
    {
        $for_date=$data['for_date'];
        $section=(!empty($data['section']))?$data['section']:null;
        $is_excluded_flag=(!empty($data['is_excluded_flag']))?$data['is_excluded_flag']:null;

        if($is_excluded_flag=='ercc') {
            $query1 = $this->db->table('docdetails d1')
                ->where('d1.display', 'Y')
                ->getCompiledSelect();

            $query2 = $this->db->table('docdetails_a d2')
                ->where('d2.display', 'Y')
                ->getCompiledSelect();

            $subquery = "($query1 UNION ALL $query2) d";
            $builder = $this->db->table($subquery);
            $builder->select("DATE(d.ent_dt) AS dak_date");
            $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
            $builder->select('COUNT(*) AS total');
            $builder->join('main m', 'm.diary_no = d.diary_no','left');
            $builder->join('main_a m1', 'm1.diary_no = d.diary_no ','left');
            $builder->where("DATE(d.ent_dt) = '$for_date'");
            $builder->groupStart()
                    ->where('m.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                    ->orWhere('m1.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                    ->groupEnd();


            
        }
        else
        {
            $query3 = $this->db->table('docdetails d1')
                ->where('d1.display', 'Y')
                ->getCompiledSelect();
            $query4 = $this->db->table('docdetails_a d2')
                ->where('d2.display', 'Y')
                ->getCompiledSelect();

            $subquery = "($query3 UNION ALL $query4) d";
            $builder = $this->db->table($subquery);
            $builder->select("DATE(d.ent_dt) AS dak_date");
            $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
            $builder->select('COUNT(*) AS total');
            $builder->where("DATE(d.ent_dt)", $for_date); // Assuming $for_date is already sanitized

        }
        if(!empty($section))
        {
            // section wise
        }

        $builder->groupBy('dak_date, section');

        $query = $builder->get();
        //echo $this->db->getLastquery();exit();
        $result = $query->getResult();
        if($result)
            return $result;
        else
            return null;

    } */

    public function getDAKSectionWiseDetails($data)
    {
        $for_date=$data['for_date'];
        $section=(!empty($data['section']))?$data['section']:null;
        $is_excluded_flag=(!empty($data['is_excluded_flag']))?$data['is_excluded_flag']:null;

        if($is_excluded_flag=='ercc') {
            $query1 = $this->db->table('docdetails d1')
                ->where('d1.display', 'Y')
                ->getCompiledSelect();

            $query2 = $this->db->table('docdetails_a d2')
                ->where('d2.display', 'Y')
                ->getCompiledSelect();

            $subquery = "($query1 UNION ALL $query2) d";
            $builder = $this->db->table($subquery);
            $builder->select("DATE(d.ent_dt) AS dak_date");
            $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
            $builder->select('COUNT(*) AS total');
            $builder->join('main m', 'm.diary_no = d.diary_no','left');
            $builder->join('main_a m1', 'm1.diary_no = d.diary_no ','left');
            $builder->where("DATE(d.ent_dt) = '$for_date'");
            $builder->groupStart()
                    ->where('m.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                    ->orWhere('m1.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                    ->groupEnd();


            /*$query1 = $this->db->table('main m1')
                ->select('diary_no')
                ->where('m1.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                ->getCompiledSelect();
            $query2 = $this->db->table('main_a m2')
                ->select('diary_no')
                ->where('m2.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                ->getCompiledSelect();

            $subquery = "($query1 UNION ALL $query2) m";
            $builder = $this->db->table($subquery);
            $builder->select("DATE(d.ent_dt) AS dak_date");
            $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
            $builder->select('COUNT(*) AS total');
            $builder->join('docdetails d', 'm.diary_no = d.diary_no','left');
            $builder->join('docdetails_a d1', 'm.diary_no = d1.diary_no','left');
            $builder->where("DATE(d.ent_dt) = '$for_date'");
            $builder->groupStart()
                ->where('d.display','Y')
                ->orWhere('d1.display','Y')
                ->groupEnd();*/
        }
        else
        {
            $query3 = $this->db->table('docdetails d1')
                ->where('d1.display', 'Y')
                ->getCompiledSelect();
            $query4 = $this->db->table('docdetails_a d2')
                ->where('d2.display', 'Y')
                ->getCompiledSelect();

            $subquery = "($query3 UNION ALL $query4) d";
            $builder = $this->db->table($subquery);
            $builder->select("DATE(d.ent_dt) AS dak_date");
            $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
            $builder->select('COUNT(*) AS total');
            $builder->where("DATE(d.ent_dt)", $for_date); // Assuming $for_date is already sanitized

        }
        if(!empty($section))
        {
            // section wise
        }

        $builder->groupBy('dak_date, section');

        $query = $builder->get();
        //echo $this->db->getLastquery();exit();
        $result = $query->getResult();
        if($result)
            return $result;
        else
            return null;
    //     $for_date = $data['for_date'];
    //     $section = (!empty($data['section'])) ? $data['section'] : null;
    //     $is_excluded_flag = (!empty($data['is_excluded_flag'])) ? $data['is_excluded_flag'] : null;

    //     if ($is_excluded_flag == 'ercc') {
    //         // Query for docdetails d1
    //         $query1 = $this->db->table('docdetails d1')
    //             ->select("d1.diary_no,d1.ent_dt AS ent_dt")
    //             ->where('d1.display', 'Y')
    //             ->getCompiledSelect();

    //         // Query for docdetails_a d2
    //         $query2 = $this->db->table('docdetails_a d2')
    //             ->select("d2.diary_no,d2.ent_dt AS ent_dt")
    //             ->where('d2.display', 'Y')
    //             ->getCompiledSelect();

    //         // Combine queries with UNION ALL
    //         $subquery = "($query1 UNION ALL $query2) d";
    //         $builder = $this->db->table($subquery); // Properly format the subquery

    //         // Selecting fields
    //         $builder->select("d.ent_dt AS dak_date");

    //         // Ensure `tentative_section` is a valid function; check its actual signature
    //         //$builder->select("COALESCE(tentative_section(CAST(d.diary_no AS BIGINT)), 'Others') AS section");
    //         $builder->select("COALESCE(tentative_section(d.diary_no::text), 'Others') AS section");
    //         $builder->select('COUNT(*) AS total');

    //     // Filter by date
    //     $builder->where("DATE(d.ent_dt) = '$for_date'");

    //         // Filter by date
    //         $builder->where("d.ent_dt = '$for_date'");

    //         // Exclude specific casetype_ids
    //         $builder->groupStart()
    //             ->where('m.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
    //             ->orWhere('m1.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
    //             ->groupEnd();
    //     } else {
    //         // Query for docdetails d1
    //         $query3 = $this->db->table('docdetails d1')
    //             ->select("d1.diary_no,d1.ent_dt AS ent_dt")
    //             ->where('d1.display', 'Y')
    //             ->getCompiledSelect();

    //         // Query for docdetails_a d2
    //         $query4 = $this->db->table('docdetails_a d2')
    //             ->select("d2.diary_no,d2.ent_dt AS ent_dt")
    //             ->where('d2.display', 'Y')
    //             ->getCompiledSelect();

    //         // Combine queries with UNION ALL
    //         $subquery = "($query3 UNION ALL $query4) d";
    //         $builder = $this->db->table($subquery); // Properly format the subquery

    //         // Selecting fields
    //         $builder->select("d.ent_dt AS dak_date");

    //         // Ensure `tentative_section` is a valid function; check its actual signature
    //         //$builder->select("COALESCE(tentative_section(CAST(d.diary_no AS BIGINT)), 'Others') AS section");
    //         $builder->select("COALESCE(tentative_section(d.diary_no::text), 'Others') AS section");
    //         $builder->select('COUNT(*) AS total');
    //     // Filter by date
    //     $builder->where("DATE(d.ent_dt) = '$for_date'");
    // }

    // // Additional section-wise filtering
    // if (!empty($section)) {
    //     $builder->where('section', $section);
    // }

    // // Grouping by dak_date and section
    // $builder->groupBy('dak_date, section');
    
    // // Execute the query
    // $query = $builder->get();
    // $result = $query->getResult();

    // // Return the result or null if no data is found
    // return $result ? $result : null;
}


    function getDAKSectionWiseCaseDetails($data)
    {
        $for_date = $data['for_date'];
        $section = (!empty($data['section'])) ? $data['section'] : null;
        $is_excluded_flag = (!empty($data['is_excluded_flag'])) ? $data['is_excluded_flag'] : null;

        $query1 = $this->db->table('docdetails d1')
            ->where('d1.display', 'Y')
            ->getCompiledSelect();
        $query2 = $this->db->table('docdetails_a d2')
            ->where('d2.display', 'Y')
            ->getCompiledSelect();
        $subquery1 = "($query1 UNION ALL $query2) d";

        $builder = $this->db->table($subquery1);
        #$builder->distinct();
        $builder->select("DISTINCT ON (d.diary_no,diary_year,dak_date,section,document) *");
        $builder->select("DATE(d.ent_dt) AS dak_date");
        $builder->select("CASE WHEN tentative_section(d.diary_no) IS NULL THEN 'Others' ELSE tentative_section(d.diary_no) END AS section");
        $builder->select('tentative_da(d.diary_no::int)as case_da');
        $builder->select("SUBSTRING(d.diary_no::text, 1, LENGTH(d.diary_no::text) - 4) AS diary_no");
        $builder->select("right((cast(d.diary_no as text)),4) AS diary_year");
        $builder->select("CONCAT((case when(m.pet_name is null) then m1.pet_name else m.pet_name end) , ' Vs ', (case when (m.res_name is null) then m1.res_name else m.res_name end)) AS cause_title");
        $builder->select("(case when(m.reg_no_display is null) then m1.reg_no_display else m.reg_no_display end) as case_no,dm.docdesc,concat(d.docnum,'/',d.docyear) as document");
        $builder->join('main m', 'm.diary_no = d.diary_no', 'left');
        $builder->join('main_a m1', 'm1.diary_no = d.diary_no ', 'left');
        $builder->join('master.docmaster dm', '(d.doccode = dm.doccode and d.doccode1=dm.doccode1)', 'left');
        $builder->where("DATE(d.ent_dt) = '$for_date'");
        $builder->where("tentative_section(d.diary_no)", $section);
        if ($is_excluded_flag == 'ercc') {
            $builder->groupStart()
                ->where('m.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                ->orWhere('m1.casetype_id NOT IN (9, 10, 19, 20, 25, 26)')
                ->groupEnd();
        }
        $builder->orderBy('d.diary_no asc');
        $builder->orderBy('dak_date asc'); // Include ORDER BY expression in select list

        if (!empty($section)) {
            // TODO later
        }
        $query = $builder->get();
        // echo $this->db->getLastquery();exit();
        $result = $query->getResult();
        if ($result)
            return $result;
        else
            return null;
    }

    function getSectionWiseDAKDetails($data)
    {
        $builder = $this->db->table('loose_block a');
        $builder->select('a.id,	reason_blk,	section_name,	pet_name,	res_name,	a.ent_dt,	u.name');
        $builder->select("left((cast(a.diary_no as text)),-4) as diary_no, right((cast(a.diary_no as text)),4) as diary_year");
        $builder->join('master.users u', 'a.usercode = u.usercode ', 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');
        $builder->join('master.usersection c', 'u.section = c.id', 'left');
        $builder->join('docdetails_a da', 'da.diary_no = m.diary_no', 'left');
        if ($data['from_date']) {
            $builder->where('date(a.ent_dt) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(a.ent_dt) <=', $data['to_date']);
        }
        $builder->where('a.display', 'Y');
        $builder->limit(5000);
        return $result = $builder->get()->getResult();
    }

    function getDakByDocumentNo($data)
    {
        $doc_number = $data['document_no'];
        $doc_year = $data['doc_year'];

        $query1 = $this->db->table('docdetails t1')
            ->where('docnum', $doc_number)
            ->where('docyear', $doc_year)
            ->whereIn('t1.display', ['Y', 'E'])
            ->getCompiledSelect();

        $query2 = $this->db->table('docdetails_a t2')
            ->where('docnum', $doc_number)
            ->where('docyear', $doc_year)
            ->whereIn('t2.display', ['Y', 'E'])
            ->getCompiledSelect();
        //$subQuery = $this->db->query("$query1 UNION ALL $query2");

        $builder = $this->db->table("($query1 UNION ALL $query2) a");
        $builder->select('a.diary_no, a.doccode, a.doccode1, docnum, docyear, a.remark, other1, filedby, iastat, ent_dt, advocate_id, verified, docdesc, c.name AS advname, u.name AS entryuser,
         case when (m.active_fil_no is null) then m1.active_fil_no else m.active_fil_no end as active_fil_no,   
          case when (m.active_reg_year is null) then m1.active_reg_year else m.active_reg_year end as active_reg_year,  
          case when (ct.short_description is null) then ct1.short_description else ct.short_description end as short_description');
        $builder->join('master.docmaster b', "a.doccode = b.doccode AND a.doccode1 = b.doccode1 AND (b.display = 'Y' OR b.display = 'E')", 'left');
        $builder->join('master.bar c', 'advocate_id = bar_id', 'left');
        $builder->join('master.users u', 'a.usercode = u.usercode', 'left');
        $builder->join('main m', 'a.diary_no = m.diary_no', 'left');
        $builder->join('main_a m1', 'a.diary_no = m1.diary_no', 'left');
        $builder->join('master.casetype ct', 'ct.casecode = m.active_casetype_id', 'left');
        $builder->join('master.casetype ct1', 'ct1.casecode = m1.active_casetype_id', 'left');
        $query = $builder->get();
        $result = $query->getResult();
        if ($result)
            return $result;
        else
            return null;
    }

    public function getDAKSectionWise($from_date, $to_date, $section, $exclude_review_contempt_curative_petition)
    {
        $query3 = $this->db->table('docdetails d1')
            ->where('d1.display', 'Y')
            ->getCompiledSelect();
        $query4 = $this->db->table('docdetails_a d2')
            ->where('d2.display', 'Y')
            ->getCompiledSelect();
        $builder = $this->db->table("($query3 UNION ALL $query4) d");
        if ($exclude_review_contempt_curative_petition == 'ercc') {
            $builder->select('DATE(d.ent_dt) AS date1, COUNT(*) AS total');
            $builder->join('main m', 'd.diary_no = m.diary_no', 'left');
            $builder->join('main_a m1', 'd.diary_no = m1.diary_no', 'left');
            $builder->where('d.display', 'Y');
            $builder->groupStart()
                ->whereNotIn('m.casetype_id', [9, 10, 19, 20, 25, 26])
                ->orWhereNotIn('m1.casetype_id', [9, 10, 19, 20, 25, 26])
                ->groupEnd();
            $builder->where('DATE(d.ent_dt) BETWEEN ' . $this->db->escape($from_date) . ' AND ' . $this->db->escape($to_date));
            $builder->groupBy('date1');
        } else {
            $builder->select('DATE(ent_dt) AS date1, COUNT(*) AS total');
            $builder->where('display', 'Y');
            $builder->where('DATE(ent_dt) BETWEEN ' . $this->db->escape($from_date) . ' AND ' . $this->db->escape($to_date));
            $builder->groupBy('date1');
        }

        $query = $builder->get();
        //echo $this->db->getLastquery();exit();
        $result = $query->getResult();
        if ($result)
            return $result;
        else
            return null;
    }

    function getLooseDocumentsReport($data) {}

    function revertDate($date)
    {
        $date = explode('-', $date);
        return $date[2] . '-' . $date[1] . '-' . $date[0];
    }

    function getfileTrap($data)
    {
        if ($data['incompleteandcompletematter'] == 'cv') {

            $where = '';
            if ($data['diary_no']) {
                $where .= "WHERE diary_no =" . $data['diary_no'] . "";
            }
            //$wheredate = 'DATE(comp_dt) BETWEEN \''.$data['from_date'].'\' AND \''.$data['to_date'].'\'';
            $builder = $this->db->table('((SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other FROM fil_trap
            ' . $where . ') UNION (SELECT diary_no, d_by_empid, disp_dt, remarks, r_by_empid, d_to_empid, rece_dt, comp_dt, other FROM fil_trap_his ' . $where . ') ) a');
            $builder->select('a.*,u1.name d_by_name,u2.name r_by_name,u3.name o_name,u4.name d_to_name');
            $builder->join('master.users u1', 'd_by_empid = u1.empid', 'left');
            $builder->join('master.users u2', 'r_by_empid = u2.empid', 'left');
            $builder->join('master.users u3', 'other = u3.empid', 'left');
            $builder->join('master.users u4', 'd_to_empid = u4.empid', 'left');
            //$builder->where($wheredate);
            $builder->orderBy('comp_dt', 'ASC');
            //$builder->orderBy('rece_dt', 'DESC');
            $builder->limit(5000);
            $results = $builder->get()->getResult();
            //echo $this->db->getLastquery();exit();
            return $results;
        }

        if ($data['incompleteandcompletematter'] == 'cm') {
            $empId = session()->get('login')['empid'];
            //$empId=145;
            $fromDate = $_REQUEST['from_date'];
            $toDate = $_REQUEST['to_date'];
            $logged_in_user_code = session()->get('login')['usercode'];
            $cat = 0;
            $ref = 0;
            $fil = 0;
            $builder = $this->db->table('fil_trap_users a');
            $builder->select('usertype');
            $builder->join('master.usertype b', "a.usertype = b.id AND b.display = 'E'", 'left');
            $builder->where('a.usercode', $logged_in_user_code);
            $builder->where('a.display', 'Y');
            $fil_trap_type_q = $builder->get()->getResult();

            if (!empty($fil_trap_type_q)) {
                foreach ($fil_trap_type_q as $row) {
                    if ($row->usertype == 104)
                        $ref = 1;
                    if ($row->usertype == 105)
                        $cat = 1;
                    if ($row->usertype == 101)
                        $fil = 1;
                }
            }
            //echo $cat . '#' . $ref . '#' . $fil;
            if ($cat == 0 && $ref == 0) {
                if ($fil == 1) {
                    $subQuery1 = $this->db->table('fil_trap')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('d_by_empid', $empId)
                        ->where("DATE(disp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery2 = $this->db->table('fil_trap_his')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('d_by_empid', $empId)
                        ->where("DATE(disp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery3 = $this->db->table('fil_trap_a')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('d_by_empid', $empId)
                        ->where("DATE(disp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery4 = $this->db->table('fil_trap_his a')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('d_by_empid', $empId)
                        ->where("DATE(disp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $query = $this->db->table("($subQuery1 UNION $subQuery2 UNION $subQuery3 UNION $subQuery4) a")
                        ->select('ec.efiling_no, a.*, pet_name, res_name, u.name AS d_by_name')
                        ->join('main b', 'a.diary_no = b.diary_no')
                        ->join('master.users u', 'a.d_by_empid = u.empid', 'left')
                        ->join('efiled_cases ec', "ec.diary_no = b.diary_no AND ec.display = 'Y'", 'left')
                        ->orderBy('a.disp_dt', 'DESC');
                } else {
                    $subQuery1 = $this->db->table('fil_trap')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('r_by_empid', $empId)
                        ->where("DATE(comp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery2 = $this->db->table('fil_trap_his')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('r_by_empid', $empId)
                        ->where("DATE(comp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery3 = $this->db->table('fil_trap_a')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('r_by_empid', $empId)
                        ->where("DATE(comp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $subQuery4 = $this->db->table('fil_trap_his_a')
                        ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, d_to_empid')
                        ->where('r_by_empid', $empId)
                        ->where("DATE(comp_dt) BETWEEN '$fromDate' AND '$toDate'")
                        ->getCompiledSelect();

                    $query = $this->db->table("($subQuery1 UNION $subQuery2 UNION $subQuery3 UNION $subQuery4) a")
                        ->select('ec.efiling_no, a.*,
                        case when ( b.pet_name is null) then b1.pet_name else b.pet_name end as pet_name,
                        case when  (b.res_name is null) then b1.res_name else b.res_name end as res_name, 
                        u.name AS d_by_name')
                        ->join('main b', 'a.diary_no = b.diary_no', 'left')
                        ->join('main_a b1', 'a.diary_no = b1.diary_no', 'left')
                        ->join('master.users u', 'a.d_by_empid = u.empid', 'left')
                        ->join('efiled_cases ec', "ec.diary_no = b.diary_no AND ec.display = 'Y'", 'left')
                        ->join('efiled_cases ec1', "ec1.diary_no = b1.diary_no AND ec1.display = 'Y'", 'left')
                        ->orderBy('a.disp_dt', 'DESC');
                }
            } else {
                if ($cat == 1) {
                    if ($_REQUEST['type_rep'] == 'C') {
                        $subQuery1 = $this->db->table('fil_trap')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "C" AS sendto')
                            ->where('r_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery2 = $this->db->table('fil_trap_his')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "C" AS sendto')
                            ->where('r_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery3 = $this->db->table('fil_trap')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "T" AS sendto')
                            ->where('d_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery4 = $this->db->table('fil_trap_his')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "T" AS sendto')
                            ->where('d_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();


                        $subQuery5 = $this->db->table('fil_trap_a')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "C" AS sendto')
                            ->where('r_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery6 = $this->db->table('fil_trap_his_a')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "C" AS sendto')
                            ->where('r_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery7 = $this->db->table('fil_trap_a')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "T" AS sendto')
                            ->where('d_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        $subQuery8 = $this->db->table('fil_trap_his_a')
                            ->select('diary_no, d_by_empid, disp_dt, remarks, rece_dt, comp_dt, other, "T" AS sendto')
                            ->where('d_by_empid', function ($subquery, $empId) {
                                $subquery->select('empid')
                                    ->from('master.users')
                                    ->where('usertype', $empId)
                                    ->like('name', '%CATEGORIZATION%', 'both');
                            })
                            ->where('comp_dt BETWEEN', '2018-05-01', '2018-05-30')
                            ->getCompiledSelect();

                        // Combining subqueries using UNION ALL
                        $subQuery = $this->db->query("$subQuery1 UNION ALL $subQuery2 UNION ALL $subQuery3 UNION ALL $subQuery4 UNION ALL $subQuery5 UNION ALL $subQuery6 UNION ALL $subQuery7 UNION ALL $subQuery8");

                        // Main query
                        $query = $this->db->table("($subQuery) a")
                            ->select('ec.efiling_no, a.*, b.pet_name, b.res_name, u.name as d_by_name, u1.name as o_name')
                            ->select("STRING_AGG(DISTINCT CONCAT(category_sc_old, '-', sub_name1, ':', sub_name4), ',') AS cat_name", false)
                            ->select("STRING_AGG(CASE WHEN notbef = 'B' THEN j.jname END, ',') AS beforejudgegrp", false)
                            ->select("STRING_AGG(CASE WHEN notbef = 'N' THEN j.jname END, ',') AS notbeforejudgegrp", false)
                            ->select("STRING_AGG(DISTINCT j2.jname, ',') AS coramjudges", false)
                            ->join('main b', 'a.diary_no = b.diary_no')
                            ->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = "Y"', 'left')
                            ->join('master.submaster s', 'mc.submaster_id = s.id AND s.display = "Y"', 'left')
                            ->join('master.users u', 'a.d_by_empid = u.empid', 'left')
                            ->join('master.users u1', 'a.other = u1.empid', 'left')
                            ->join('not_before nb', 'a.diary_no = nb.diary_no', 'left')
                            ->join('master.judge j', 'nb.j1 = j.jcode', 'left')
                            ->join('heardt h', 'a.diary_no = h.diary_no', 'left')
                            ->join('master.judge j2', 'CAST(j2.jcode AS TEXT) = ANY(string_to_array(h.coram, ","))', 'left')
                            ->join('efiled_cases ec', 'ec.diary_no = b.diary_no AND ec.display = "Y"', 'left')
                            ->groupBy('a.diary_no, ec.efiling_no, a.d_by_empid, a.disp_dt, remarks, rece_dt, comp_dt, a.sendto, a.other, b.pet_name, b.res_name, u.name, u1.name')
                            ->orderBy('comp_dt');
                    } else {
                        //TODO code for otehr condition if required as per logic written in old ICMIS
                    }
                } else if ($ref == 1) {
                    //TODO code for otehr condition if required as per logic written in old ICMIS
                }
            }


            //TODO the SQL part as per old ICMIS
        }
        if ($data['incompleteandcompletematter'] == 'im') {
            //TOFO code TO SHOW iNCOMPLETE MATTER LIST : if Required as per old ICMIS logic
        }
        $result = $query->get()->getResult();
        //var_dump($result);exit();
        return $result;
        //echo $this->db->getLastquery();exit();
        //var_dump($result);

    }

    function getCasesearch($data)
    {
        $builder = $this->db->table("main m");
        $builder->select("m.ack_id, m.diary_no_rec_date, case when c_status='P' then 'Pending' else 'Disposed' end as status");
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
            $builder->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder->where('m.diary_no', $data['diary_no']);
        }
        if ($data['cause_title']) {
            //pr($data);
            //$builder->orLike($data['parties']);
        

            if ($data['ddl_party_type'] == '') {
                $builder->groupStart(); 
                    $builder->like('pet_name', $data['cause_title']);
                    $builder->orLike('res_name', $data['cause_title']);
                $builder->groupEnd();
            } else if ($data['ddl_party_type'] == 'P') {
                $builder->like('pet_name' , $data['cause_title']);
            } else if ($data['ddl_party_type'] == 'R') {
                $builder->like('res_name' , $data['cause_title']);
            }

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

        $builder->orderBy('m.diary_no_rec_date');
        $builder->orderBy('dno');
        $builder->limit(5000);

        //echo $builder->getCompiledSelect();
        //  die;
        $builder = $builder->get();
        $builder = $builder->getResult();
        /**main_a archive data**/
        $builder2 = $this->db->table("main_a m");
        //$builder->selectDistinct();
        $builder2->select("m.ack_id, m.diary_no_rec_date, case when c_status='P' then 'Pending' else 'Disposed' end as status");
        $builder2->select("CASE 
             WHEN m.ack_id <> 0 THEN 'e-filed'
             WHEN efiled_type = 'new_case' THEN 'e-filed'
             ELSE ''
         END as isefiled", false);
        $builder2->select("CASE 
             WHEN m.ack_id <> 0 THEN CONCAT(ack_id, '/', ack_rec_dt)
             WHEN efiled_type = 'new_case' THEN efiling_no
             ELSE ''
         END as ref_id", false);
        $builder2->select("m.diary_no as dno");
        $builder2->select("left((cast(m.diary_no as text)),-4) as diary_no, right((cast(m.diary_no as text)),4) as diary_year");
        $builder2->select("TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') as diary_date", false);
        $builder2->select("CASE 
             WHEN m.active_fil_no IS NULL THEN ''
             ELSE 
            CASE 
                 WHEN m.reg_no_display IS NULL OR m.reg_no_display = '' THEN m.active_fil_no
                 ELSE m.reg_no_display
             END
         END as fil_no", false);
        $builder2->select("m.active_fil_dt, m.pet_name, m.res_name");
        $builder2->select("b.name as pet_adv_id, m.pet_adv_id as padvid");
        $builder2->select("m.c_status, u.name as diary_user_id, m.reg_no_display");
        $builder2->select("sis.name as ref_agency_state_id, rac.agency_name as ref_agency_code_id");
        $builder2->select("m.reg_no_display, m.pno, m.rno, section_name, b.mobile, b.email");
        $builder2->join('master.bar b', 'm.pet_adv_id = b.bar_id', 'left');
        $builder2->join('master.users u', 'm.diary_user_id = u.usercode', 'left');
        $builder2->join('master.usersection', 'section_id = usersection.id', 'left');
        $builder2->join('master.casetype', 'casetype_id = casecode', 'left');
        $builder2->join('master.state sis', 'm.ref_agency_state_id = sis.id_no', 'left');
        $builder2->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $builder2->join('efiled_cases ef', "m.diary_no = ef.diary_no AND ef.display ='Y' AND efiled_type ='new_case'", 'left', false);
        if ($data['reg_or_def']) {
            $builder2->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no', 'inner', false);
        }
        if ($data['from_date']) {
            $builder2->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        }
        if ($data['to_date']) {
            $builder2->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        }
        if ($data['diary_no']) {
            $builder2->where('m.diary_no', $data['diary_no']);
        }
        if ($data['cause_title']) {
            //$builder2->orLike($data['parties']);
            if ($data['ddl_party_type'] == '') {
                $builder2->groupStart(); 
                    $builder2->like('pet_name', $data['cause_title']);
                    $builder2->orLike('res_name', $data['cause_title']);
                $builder2->groupEnd();
            } else if ($data['ddl_party_type'] == 'P') {
                $builder2->like('pet_name' , $data['cause_title']);
            } else if ($data['ddl_party_type'] == 'R') {
                $builder2->like('res_name' , $data['cause_title']);
            }
        }
        if ($data['case_type_casecode']) {
            $builder2->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if ($data['isma']) {
            $builder2->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if ($data['is_inperson']) {
            $builder2->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        $builder2->orderBy('m.diary_no_rec_date');
        $builder2->orderBy('dno');
        $builder2->limit(5000);
        $builder2 = $builder2->get()->getResult();
        return $result = array_merge($builder, $builder2);
    }

    function getCasesearch_diary($data)
    {
        $builder = $this->db->table('lowerct b');
        $builder->select('name');
        $builder->select('CASE  WHEN b.ct_code = 3 THEN  (SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND s.display = \'Y\')
            ELSE    (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND c.is_deleted = \'f\')
            END AS agency_name');
        $builder->select('CASE   WHEN b.ct_code = 4 THEN  (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = b.lct_casetype)     ELSE   (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = \'Y\')
            END AS type_sname');
        $builder->select('short_description');
        $builder->select('court_name');
        $builder->select('d.pet_name');
        $builder->select('d.res_name');
        $builder->select('b.lct_dec_dt');
        $builder->select('b.l_dist');
        $builder->select('b.l_state');
        $builder->select('b.lct_casetype');
        $builder->select('b.lct_caseno');
        $builder->select('b.lct_caseyear');
        $builder->select('b.diary_no');
        $builder->select('b.ct_code');
        $builder->select('date(d.diary_no_rec_date) AS diary_no_rec_date');
        $builder->join('master.state c', 'b.l_state = c.id_no AND c.display = \'Y\'', 'left');
        $builder->join('main d', 'd.diary_no = b.diary_no', 'left');
        $builder->join('master.casetype e', 'cast(e.casecode as text) = SUBSTRING(d.fil_no::text, 1, LENGTH(d.fil_no::text) -2) AND e.display = \'Y\'', 'left');
        $builder->join('master.m_from_court f', 'f.id = b.ct_code AND f.display = \'Y\'', 'left');
        $builder->join('caveat_diary_matching cdm', 'cdm.diary_no = b.diary_no AND cdm.display = \'Y\'', 'left');
        $builder->where('ct_code', $data['ddl_court']);
        $builder->where('l_state', $data['ddl_st_agncy']);
        $builder->where('lct_caseyear', $data['case_year']);
        $builder->where('b.lct_dec_dt IS NOT NULL');
        $builder->where('b.lw_display', 'Y');
        $builder->orderBy('b.diary_no');
        $builder = $builder->get()->getResult();
        /**lowerct_a archive data**/
        $builder2 = $this->db->table('lowerct_a b');
        $builder2->select('name');
        $builder2->select('CASE  WHEN b.ct_code = 3 THEN  (SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND s.display = \'Y\')
            ELSE    (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND c.is_deleted = \'f\')
            END AS agency_name');
        $builder2->select('CASE   WHEN b.ct_code = 4 THEN  (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = b.lct_casetype)     ELSE   (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = \'Y\')
            END AS type_sname');
        $builder2->select('short_description');
        $builder2->select('court_name');
        $builder2->select('d.pet_name');
        $builder2->select('d.res_name');
        $builder2->select('b.lct_dec_dt');
        $builder2->select('b.l_dist');
        $builder2->select('b.l_state');
        $builder2->select('b.lct_casetype');
        $builder2->select('b.lct_caseno');
        $builder2->select('b.lct_caseyear');
        $builder2->select('b.diary_no');
        $builder2->select('b.ct_code');
        $builder2->select('date(d.diary_no_rec_date) AS diary_no_rec_date');
        $builder2->join('master.state c', 'b.l_state = c.id_no AND c.display = \'Y\'', 'left');
        $builder2->join('main_a d', 'd.diary_no = b.diary_no', 'left');
        $builder2->join('master.casetype e', 'cast(e.casecode as text) = SUBSTRING(d.fil_no::text, 1, LENGTH(d.fil_no::text) -2) AND e.display = \'Y\'', 'left');
        $builder2->join('master.m_from_court f', 'f.id = b.ct_code AND f.display = \'Y\'', 'left');
        $builder2->join('caveat_diary_matching cdm', 'cdm.diary_no = b.diary_no AND cdm.display = \'Y\'', 'left');
        $builder2->where('ct_code', $data['ddl_court']);
        $builder2->where('l_state', $data['ddl_st_agncy']);
        $builder2->where('lct_caseyear', $data['case_year']);
        $builder2->where('b.lct_dec_dt IS NOT NULL');
        $builder2->where('b.lw_display', 'Y');
        $builder2->orderBy('b.diary_no');
        $builder2 = $builder2->get()->getResult();
        return $result = array_merge($builder, $builder2);
    }

    function getCasesearch_caveat($data)
    {
        $builder = $this->db->table('caveat_lowerct b')
            ->select('name')
            ->select('CASE WHEN b.ct_code = 3 THEN (SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = \'Y\') ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = \'f\') END AS agency_name')
            ->select('CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = b.lct_casetype) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = \'Y\') END AS type_sname')
            ->select('short_description')
            ->select('court_name')
            ->select('d.pet_name')
            ->select('d.res_name')
            ->select('b.lct_dec_dt')
            ->select('b.l_dist')
            ->select('b.l_state')
            ->select('b.lct_casetype')
            ->select('b.lct_caseno')
            ->select('b.lct_caseyear')
            ->select('b.caveat_no')
            ->select('b.ct_code')
            ->select('date(d.diary_no_rec_date) AS diary_no_rec_date')
            ->join('master.state c', 'b.l_state = c.id_no AND c.display = \'Y\'', 'left')
            ->join('caveat d', 'd.caveat_no = b.caveat_no', 'left')
            ->join('master.casetype e', 'cast(e.casecode as text) = SUBSTRING(d.fil_no::text, 1, LENGTH(d.fil_no::text) -2) AND e.display = \'Y\'', 'left')
            ->join('master.m_from_court f', 'f.id = b.ct_code AND f.display = \'Y\'', 'left')
            //->join('master.caveat_diary_matching cdm', 'cdm.caveat_no = b.caveat_no AND cdm.display = \'Y\'', 'left')
            ->where('ct_code', $data['ddl_court'])
            ->where('l_state', $data['ddl_st_agncy'])
            ->where('lct_caseyear', $data['case_year'])
            ->where('b.lct_dec_dt IS NOT NULL')
            ->where('b.lw_display', 'Y')
            ->orderBy('b.caveat_no')
            ->get();
        $builder = $builder->getResult();
        /**caveat lowerct archive data**/
        $builder2 = $this->db->table('caveat_lowerct_a b')
            ->select('name')
            ->select('CASE WHEN b.ct_code = 3 THEN (SELECT name FROM master.state s WHERE s.id_no = b.l_dist AND display = \'Y\') ELSE (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = b.l_state AND c.id = b.l_dist AND is_deleted = \'f\') END AS agency_name')
            ->select('CASE WHEN b.ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = \'Y\' AND ct.casecode = b.lct_casetype) ELSE (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = b.lct_casetype AND d.display = \'Y\') END AS type_sname')
            ->select('short_description')
            ->select('court_name')
            ->select('d.pet_name')
            ->select('d.res_name')
            ->select('b.lct_dec_dt')
            ->select('b.l_dist')
            ->select('b.l_state')
            ->select('b.lct_casetype')
            ->select('b.lct_caseno')
            ->select('b.lct_caseyear')
            ->select('b.caveat_no')
            ->select('b.ct_code')
            ->select('date(d.diary_no_rec_date) AS diary_no_rec_date')
            ->join('master.state c', 'b.l_state = c.id_no AND c.display = \'Y\'', 'left')
            ->join('caveat_a d', 'd.caveat_no = b.caveat_no', 'left')
            ->join('master.casetype e', 'cast(e.casecode as text) = SUBSTRING(d.fil_no::text, 1, LENGTH(d.fil_no::text) -2) AND e.display = \'Y\'', 'left')
            ->join('master.m_from_court f', 'f.id = b.ct_code AND f.display = \'Y\'', 'left')
            //->join('master.caveat_diary_matching cdm', 'cdm.caveat_no = b.caveat_no AND cdm.display = \'Y\'', 'left')
            ->where('ct_code', $data['ddl_court'])
            ->where('l_state', $data['ddl_st_agncy'])
            ->where('lct_caseyear', $data['case_year'])
            ->where('b.lct_dec_dt IS NOT NULL')
            ->where('b.lw_display', 'Y')
            ->orderBy('b.caveat_no')
            ->get();
        $builder2 = $builder2->getResult();
        return $result = array_merge($builder, $builder2);
    }

    function getRefiling($data)
    {
        if (!empty($data['diary_no'])) {
            $conditions = ['remarks' => 'FDR -> SCR', 'diary_no' => $data['diary_no']];
        } else {
            $conditions = ['remarks' => 'FDR -> SCR', 'DATE(disp_dt) >=' => $data['from_date'], 'DATE(disp_dt) <=' => $data['to_date']];
        }
        $subQuery = $this->db->table('fil_trap')
            ->select('token_no, LEFT(CAST(diary_no AS TEXT), -4) AS dn, RIGHT(CAST(diary_no AS TEXT), 4) AS dy, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt, remarks')
            ->where($conditions)
            ->getCompiledSelect();
        $subQuery .= " UNION ";
        $subQuery .= $this->db->table('fil_trap_his')
            ->select('token_no, LEFT(CAST(diary_no AS TEXT), -4) AS dn, RIGHT(CAST(diary_no AS TEXT), 4) AS dy, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt, remarks')
            ->where($conditions)
            ->getCompiledSelect();
        $finalQuery = $this->db->query("SELECT a.*, u1.name AS dispatch_by, u2.name AS dispatch_to, u2.attend 
                        FROM ($subQuery) a 
                        JOIN master.users u1 ON a.d_by_empid = u1.empid 
                        JOIN master.users u2 ON a.d_to_empid = u2.empid 
                        ORDER BY dispatch_to, token_no");
        $builder = $finalQuery->getResult();
        /**archive table data**/
        $subQuery2 = $this->db->table('fil_trap_a')
            ->select('token_no, LEFT(CAST(diary_no AS TEXT), -4) AS dn, RIGHT(CAST(diary_no AS TEXT), 4) AS dy, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt, remarks')
            ->where($conditions)
            ->getCompiledSelect();
        $subQuery2 .= " UNION ";
        $subQuery2 .= $this->db->table('fil_trap_his_a')
            ->select('token_no, LEFT(CAST(diary_no AS TEXT), -4) AS dn, RIGHT(CAST(diary_no AS TEXT), 4) AS dy, d_by_empid, d_to_empid, disp_dt, r_by_empid, rece_dt, remarks')
            ->where($conditions)
            ->getCompiledSelect();
        $finalQuery2 = $this->db->query("SELECT a.*, u1.name AS dispatch_by, u2.name AS dispatch_to, u2.attend 
                        FROM ($subQuery2) a 
                        JOIN master.users u1 ON a.d_by_empid = u1.empid 
                        JOIN master.users u2 ON a.d_to_empid = u2.empid 
                        ORDER BY dispatch_to, token_no");
        $builder2 = $finalQuery2->getResult();
        return $result = array_merge($builder, $builder2);
    }

    function getCaseAllotted($data)
    {
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
        $builder = $this->db->table("master.users u");
        $builder->select('u.name,u.empid AS d_to_empid, COUNT(c.diary_no) AS ss');
        $builder->join('fil_trap_users t_u', 'u.usercode = t_u.usercode');
        $builder->join('main c', "c.diary_user_id = u.usercode AND date(c.diary_no_rec_date) BETWEEN '$fromDate' AND '$toDate'", 'left', false);
        $builder->where('t_u.usertype', '101');
        $builder->where('t_u.display', 'Y');
        $builder->where('u.display', 'Y');
        $builder->groupBy('u.empid,u.name');
        $builder = $builder->get();

        return $results = $builder->getResult();
    }

    function getCaseVerification($data)
    {
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
        $subQuery1 = $this->db->table('master.users u')
            ->select('usercode, u.name, empid, section_name, type_name, section, usertype')
            ->join('master.usersection us', 'section = us.id', 'left')
            ->join('master.usertype ut', 'usertype = ut.id', 'left')
            ->where('isda', 'Y')
            ->where('u.display', 'Y')
            ->where('us.display', 'Y')
            ->whereIn('usertype', [17, 50, 51])
            ->getCompiledSelect();

        $subQuery2 = $this->db->table('heardt h')
            ->select('m.dacode upby, COUNT(h.diary_no) da_case')
            ->join('main m', 'h.diary_no = m.diary_no')
            ->join('master.users u', 'u.usercode = m.dacode')
            ->where('h.main_supp_flag', 0)
            ->whereIn('u.usertype', [17, 50, 51])
            ->where('u.display', 'Y')
            ->where("DATE(h.ent_dt) BETWEEN '$fromDate' AND '$toDate'")
            ->groupBy('m.dacode')
            ->getCompiledSelect();
        $subQuery22 = $this->db->table('heardt_a h')
            ->select('m.dacode upby, COUNT(h.diary_no) da_case')
            ->join('main_a m', 'h.diary_no = m.diary_no')
            ->join('master.users u', 'u.usercode = m.dacode')
            ->where('h.main_supp_flag', 0)
            ->whereIn('u.usertype', [17, 50, 51])
            ->where('u.display', 'Y')
            ->where("DATE(h.ent_dt) BETWEEN '$fromDate' AND '$toDate'")
            ->groupBy('m.dacode')
            ->getCompiledSelect();

        $subQuery3 = $this->db->table('heardt h')
            ->select('m.dacode upby_o, 
    SUM(CASE WHEN tt.bo_ent_dt IS NOT NULL THEN 1 ELSE 0 END) bo_v,
    SUM(CASE WHEN tt.bo_ent_dt IS NULL THEN 1 ELSE 0 END) bo_nv,
    SUM(CASE WHEN tt.ar_ent_dt IS NOT NULL THEN 1 ELSE 0 END) ar_v,
    SUM(CASE WHEN tt.bo_ent_dt IS NOT NULL AND tt.ar_ent_dt IS NULL THEN 1 ELSE 0 END) ar_nv,
    SUM(CASE WHEN tt.dy_ent_dt IS NOT NULL THEN 1 ELSE 0 END) dy_v,
    SUM(CASE WHEN tt.ar_ent_dt IS NOT NULL AND tt.dy_ent_dt IS NULL THEN 1 ELSE 0 END) dy_nv,
    SUM(CASE WHEN tt.adr_ent_dt IS NOT NULL THEN 1 ELSE 0 END) adr_v,
    SUM(CASE WHEN tt.dy_ent_dt IS NOT NULL AND tt.adr_ent_dt IS NULL THEN 1 ELSE 0 END) adr_nv')
            ->join('main m', 'h.diary_no = m.diary_no')
            ->join('master.users u', 'u.usercode = m.dacode')
            ->join('case_verify_by_sec tt', 'tt.diary_no = h.diary_no AND tt.bo_ent_dt > h.ent_dt AND tt.display = \'Y\'', 'left')
            ->where('h.main_supp_flag', 0)
            ->whereIn('u.usertype', [17, 50, 51])
            ->where('u.display', 'Y')
            ->where("DATE(h.ent_dt) BETWEEN '$fromDate' AND '$toDate'")
            ->groupBy('m.dacode')
            ->getCompiledSelect();
        $subQuery33 = $this->db->table('heardt_a h')
            ->select('m.dacode upby_o, 
    SUM(CASE WHEN tt.bo_ent_dt IS NOT NULL THEN 1 ELSE 0 END) bo_v,
    SUM(CASE WHEN tt.bo_ent_dt IS NULL THEN 1 ELSE 0 END) bo_nv,
    SUM(CASE WHEN tt.ar_ent_dt IS NOT NULL THEN 1 ELSE 0 END) ar_v,
    SUM(CASE WHEN tt.bo_ent_dt IS NOT NULL AND tt.ar_ent_dt IS NULL THEN 1 ELSE 0 END) ar_nv,
    SUM(CASE WHEN tt.dy_ent_dt IS NOT NULL THEN 1 ELSE 0 END) dy_v,
    SUM(CASE WHEN tt.ar_ent_dt IS NOT NULL AND tt.dy_ent_dt IS NULL THEN 1 ELSE 0 END) dy_nv,
    SUM(CASE WHEN tt.adr_ent_dt IS NOT NULL THEN 1 ELSE 0 END) adr_v,
    SUM(CASE WHEN tt.dy_ent_dt IS NOT NULL AND tt.adr_ent_dt IS NULL THEN 1 ELSE 0 END) adr_nv')
            ->join('main_a m', 'h.diary_no = m.diary_no')
            ->join('master.users u', 'u.usercode = m.dacode')
            ->join('case_verify_by_sec tt', 'tt.diary_no = h.diary_no AND tt.bo_ent_dt > h.ent_dt AND tt.display = \'Y\'', 'left')
            ->where('h.main_supp_flag', 0)
            ->whereIn('u.usertype', [17, 50, 51])
            ->where('u.display', 'Y')
            ->where("DATE(h.ent_dt) BETWEEN '$fromDate' AND '$toDate'")
            ->groupBy('m.dacode')
            ->getCompiledSelect();

        $query = $this->db->query("SELECT *  FROM ($subQuery1) t1
            LEFT JOIN ($subQuery2) t2 ON t1.usercode = t2.upby
            LEFT JOIN ($subQuery22) t22 ON t1.usercode = t22.upby
            LEFT JOIN ($subQuery3) t3 ON t1.usercode = t3.upby_o
            LEFT JOIN ($subQuery33) t33 ON t1.usercode = t33.upby_o
            ORDER BY section_name,
            CASE WHEN usertype = 17 THEN 1 WHEN usertype = 51 THEN 2 ELSE 3 END ASC,empid");

        return $results = $query->getResult();
        //     echo $this->db->getLastquery();


    }

    function getFreshScrutinyMatters($data)
    {
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
        $builder = $this->db->table('master.users AS u');
        $builder->select('u.name, u.empid, us.type_name, us.id,
        SUM(CASE WHEN diary_no IS NOT NULL THEN 1 ELSE 0 END) AS total,
        SUM(CASE WHEN comp_dt is not null THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN comp_dt is null THEN 1 ELSE 0 END) AS pending');
        $builder->join("(SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                FROM fil_trap f 
                WHERE remarks IN ('DE -> SCR') AND date(disp_dt) BETWEEN '$fromDate' AND '$toDate' AND diary_no IS NOT NULL
                UNION ALL
                SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                FROM fil_trap_a fa 
                WHERE remarks IN ('DE -> SCR') AND date(disp_dt) BETWEEN '$fromDate' AND '$toDate' AND diary_no IS NOT NULL
                UNION ALL
                SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                FROM fil_trap_his_a ha 
                WHERE remarks IN ('DE -> SCR') AND date(disp_dt) BETWEEN '$fromDate' AND '$toDate' AND diary_no IS NOT NULL
                UNION ALL
                SELECT diary_no, d_to_empid, disp_dt, remarks, comp_dt 
                FROM fil_trap_his h 
                WHERE remarks IN ('DE -> SCR') AND date(disp_dt) BETWEEN '$fromDate' AND '$toDate' AND diary_no IS NOT NULL) temp", 'temp.d_to_empid = u.empid');
        $builder->join('master.usertype us', 'u.usertype = us.id');
        $builder->groupBy('u.name, u.empid, us.type_name, us.id');
        $builder->orderBy("CASE us.id
                            WHEN '14' THEN 1
                            WHEN '17' THEN 2
                            WHEN '51' THEN 3
                            WHEN '50' THEN 4
                            ELSE 5
                        END", 'ASC');
        $builder->orderBy('u.empid', 'ASC');

        return $builder = $builder->get()->getResult();
    }

    function getLooseDocUserWise($data)
    {
        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];
        $subQuery = $this->db->table('docdetails d')
            ->select('DATE(ent_dt) AS date1,
        ARRAY_TO_STRING(ARRAY_AGG(DISTINCT section_name), \',\') AS section,
        ARRAY_TO_STRING(ARRAY_AGG(DISTINCT us.id), \',\') AS sec_id,
        COUNT(*) AS documents,
        verified,
        SUM(CASE WHEN verified = \'V\' THEN 1 ELSE 0 END) AS verify,
        SUM(CASE WHEN verified != \'V\' THEN 1 ELSE 0 END) AS not_verify')
            ->join('main m', 'm.diary_no = d.diary_no')
            ->join('master.users u', 'u.usercode = m.dacode AND (u.display = \'Y\' OR u.display IS NULL)', 'left')
            ->join('master.usersection us', 'us.id = u.section AND us.display = \'Y\'', 'left')
            ->where('d.display', 'Y')
            ->where('m.c_status', 'P')
            ->where("date(ent_dt) BETWEEN '$fromDate' AND '$toDate'")
            ->groupBy('DATE(ent_dt), verified')
            ->getCompiledSelect();
        $mainQuery = $this->db->query("SELECT date1,  STRING_AGG(section, ',') AS section, STRING_AGG(sec_id, ',') AS sec_id,
                    SUM(documents) AS total,COALESCE(SUM(verify), 0) AS verify,COALESCE(SUM(not_verify), 0) AS not_verify 
                    FROM ($subQuery) a GROUP BY date1");
        $results = $mainQuery->getResult();
        return $results;
    }

    function getSensitiveMattersPendingandNotReady()
    {
        $query = $this->db->table('sensitive_cases sc')
            ->select("reg_no_display || ' @ ' || left((cast(m.diary_no as text)),-4)  || '/' || right((cast(m.diary_no as text)),4) as \"CaseNo_DiaryNo\",
        pet_name || ' vs ' || res_name as Cause_Title,
        category_sc_old as category,
        sub_name4 as Subject_category,
        reason as \"Sensitive_Note\",
        name as \"Sensitive_Updated_by\",
        CASE WHEN h.main_supp_flag = 3 THEN 'Not Ready' ELSE 'Ready' END as Ready_Not_Ready,
        c_status")
            ->join('main m', 'sc.diary_no = m.diary_no', 'left')
            ->join('heardt h', 'sc.diary_no = h.diary_no', 'left')
            ->join('mul_category', 'm.diary_no = mul_category.diary_no AND mul_category.display = \'Y\'', 'left')
            ->join('master.submaster', 'mul_category.submaster_id = submaster.id', 'left')
            ->join('master.users', 'sc.updated_by = users.usercode', 'left')
            ->where('sc.display', 'Y')
            ->where('c_status', 'P')
            ->whereIn('h.main_supp_flag', [3])
            ->orderBy('diary_no_rec_date', 'ASC');

        $results = $query->get()->getResult();

        return $results;
    }

    public
    function get_result($option, $condition, $sort, $joinCondition)
    {
        $subQuery = $this->db->table('case_remarks_multiple')
            ->select('diary_no, cl_date, STRING_AGG(CAST(r_head AS VARCHAR), \', \' ORDER BY cl_date DESC) AS r_head')
            ->groupBy(['diary_no', 'cl_date'])
            ->limit(1)
            ->getCompiledSelect();


        $queryBuilder = $this->db->table('main m');
        $queryBuilder->select('m.diary_no, reg_no_display, pet_name, res_name, diary_no_rec_date, active_fil_dt, d.ord_dt, mf_active, agency_state, agency_name, STRING_AGG(CONCAT(sub_name1, \'--\', sub_name4, \' (\', category_sc_old, \')\'), \', \') AS subject, u.name, u.empid, us.section_name, c_status');
        $queryBuilder->join('master.ref_agency_state ras', 'm.ref_agency_state_id = ras.cmis_state_id', 'left');
        $queryBuilder->join('master.ref_agency_code rac', 'm.ref_agency_code_id = rac.id', 'left');
        $queryBuilder->join('master.users u', 'm.dacode = u.usercode', 'left');
        $queryBuilder->join('master.usersection us', 'u.section = us.id', 'left');
        $queryBuilder->join("($subQuery) crm", 'm.diary_no = crm.diary_no', 'left');
        $queryBuilder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
        $queryBuilder->join('dispose d', 'm.diary_no = d.diary_no', 'left');
        $queryBuilder->join('mul_category mc', 'm.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left', false); //AND mc.display = \'Y\'
        $queryBuilder->join('master.submaster s', 'mc.submaster_id::text = s.id::text AND s.display = \'Y\' AND s.flag = \'s\' AND s.flag_use IN (\'S\', \'L\')', 'left', false); //AND s.display = \'Y\'
        $queryBuilder->join('party p', 'm.diary_no = p.diary_no', 'left');
        $queryBuilder->join('advocate adv', 'm.diary_no = adv.diary_no AND adv.display = \'Y\'', 'left');

        if (!empty($joinCondition)) {
            $queryBuilder->where($joinCondition);
        }

        // Add the dynamic condition
        if (!empty($condition)) {
            $queryBuilder->where($condition);
        }

        $queryBuilder->groupBy('m.diary_no, reg_no_display, pet_name, res_name, diary_no_rec_date, active_fil_dt, d.ord_dt, mf_active, agency_state, agency_name, u.name, u.empid, us.section_name, c_status');
        // Add the ROW_NUMBER() and sorting
        $queryBuilder->select('ROW_NUMBER() OVER () AS serial_number');

        if (!empty($sort)) {
            $queryBuilder->orderBy($sort);
        }

        $result = $queryBuilder->get()->getResultArray();

        // Display the generated query for debugging
        //echo $this->db->getLastQuery();
        //exit;

        if ($option == 1) {
            return count($result);
        } elseif ($option == 2) {
            return $result;
        } else {
            return false;
        }
    }


    function uoi_slp()
    {
        $db = \Config\Database::connect();
        $sql = "select * from(
                select right(diary_no::varchar,4) AS filing_year, sum(1) total, SUM(CASE when (TRIM(pet_name) ILIKE 'UOI%' OR TRIM(pet_name) ILIKE '%U.O.I.%'
                                           OR TRIM(pet_name) ILIKE '%Union of india%' OR TRIM(pet_name) ILIKE 'U O I%'
                                           OR TRIM(pet_name) ILIKE 'Govt of india%' OR TRIM(pet_name) ILIKE 'Govt. of india%'
                                           OR TRIM(pet_name) ILIKE '%State%' OR TRIM(pet_name) ILIKE 'Govt%'
                                           OR TRIM(pet_name) ILIKE '%commission%' OR TRIM(pet_name) ILIKE '%department%'
                                           OR TRIM(pet_name) ILIKE '%dept.%' OR TRIM(pet_name) ILIKE '%revenue%'
                                           OR TRIM(res_name) ILIKE 'UOI%' OR TRIM(res_name) ILIKE '%U.O.I.%'
                                           OR TRIM(res_name) ILIKE '%Union of india%' OR TRIM(res_name) ILIKE 'U O I%'
                                           OR TRIM(res_name) ILIKE 'Govt of india%' OR TRIM(res_name) ILIKE 'Govt. of india%'
                                           OR TRIM(res_name) ILIKE '%State%' OR TRIM(res_name) ILIKE 'Govt%'
                                           OR TRIM(res_name) ILIKE '%commission%' OR TRIM(res_name) ILIKE '%department%'
                                           OR TRIM(res_name) ILIKE '%dept.%' OR TRIM(res_name) ILIKE '%revenue%') THEN 1 ELSE 0 END) AS matched
                from(
                select diary_no,pet_name,res_name from main where  casetype_id IN (1,2) 
                union 
                select diary_no,pet_name,res_name from main_a where  casetype_id IN (1,2) 
                )x group by right(diary_no::varchar,4) HAVING right(diary_no::varchar,4)::integer > 1949
                )y where matched>0 and total>0 order by 1 desc";
        return $db->query($sql)->getResultArray();
    }

    function defective_case_count($param)
    {
        $condition = ' and 1=1';
        if (!empty($param['section']))
            $condition = " and tentative_section(b.diary_no)='" . $param['section'] . "'";
        $db = \Config\Database::connect();
        $sql = "SELECT 
                SUM(CASE WHEN between_28to60 = 'YES' THEN 1 ELSE 0 END) days_28,
                SUM(CASE WHEN between_61to90 = 'YES' THEN 1 ELSE 0 END) days_60,
                SUM(CASE WHEN days90_crossed = 'YES' THEN 1 ELSE 0 END) days_90
                FROM (
                SELECT 'defect', b.diary_no,
                CASE WHEN def_days BETWEEN 28 AND 60 THEN 'YES' ELSE '' END between_28to60,
                CASE WHEN def_days BETWEEN 61 AND 90 THEN 'YES' ELSE '' END between_61to90,
                CASE WHEN def_days > 90 THEN 'YES' ELSE '' END days90_crossed
                FROM main b 
                INNER JOIN (SELECT diary_no, 
                       MAX(EXTRACT(DAY FROM CURRENT_DATE - save_dt)) AS def_days
                FROM obj_save a 
                WHERE rm_dt is null
                  AND a.display = 'Y' 
                  AND CURRENT_DATE - save_dt > INTERVAL '28 days' 
                GROUP BY diary_no) xy ON b.diary_no = xy.diary_no
                LEFT JOIN heardt h ON h.diary_no = b.diary_no 
                WHERE c_status = 'P' 
                AND active_fil_no = '' AND b.diary_no_rec_date between '" . date('Y-m-d', strtotime($param['from_date'])) . "' and '" . date('Y-m-d', strtotime($param['to_date'])) . "'
                AND b.diary_no not in (select diary_no from last_heardt lh where lh.diary_no = b.diary_no AND (lh.brd_slno != 0 OR lh.brd_slno IS NOT NULL) AND (lh.roster_id != 0 OR lh.roster_id IS NOT NULL) AND (trim(lh.judges) != '0' OR lh.judges IS NOT NULL) AND lh.bench_flag != 'X') GROUP BY b.diary_no,def_days
                ) a;";
        return $db->query($sql)->getResultArray();
    }

    public function defectiveMattersNotListed($days = null, $section = [])
    {

        $builder = $this->db->table('obj_save os');
        $builder->select("DISTINCT SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) AS diary_no");
        $builder->select("SUBSTRING(m.diary_no::text, -4) AS diary_year");
        $builder->select("CONCAT(pet_name, ' Vs ', res_name) AS title");
        $builder->select("DATE(m.diary_no_rec_date) AS diary_date");
        $builder->select("DATE(os.save_dt) AS save_dt");
        $builder->select("EXTRACT(DAY FROM NOW() - os.save_dt) AS diff");
        $builder->select("(SELECT tentative_section(m.diary_no)) AS tentative_section");
        $builder->select("u.name");
        $builder->select("u.empid");
        $builder->select("us.section_name");
        $builder->join('main m', 'm.diary_no = os.diary_no');
        $builder->join('master.users u', 'u.usercode = m.dacode', 'left');
        $builder->join('master.usersection us', 'us.id = u.section', 'left');
        $builder->join('heardt h', 'os.diary_no = h.diary_no', 'left');
        $builder->join('last_heardt lh', 'os.diary_no = lh.diary_no', 'left');
        $builder->where("EXTRACT(DAY FROM NOW() - os.save_dt)>", $days);
        $builder->where("(rm_dt IS NULL)");
        $builder->where('os.display', 'Y');
        $builder->where('c_status', 'P');
        $builder->where('(fil_no IS NULL)');
        $builder->where('h.diary_no', null);
        $builder->where('lh.diary_no', null);
        if (!empty($section)) {
            $builder->groupStart()
                ->whereIn('section_name', $section)
                ->orWhereIn("tentative_section(m.diary_no)", $section)
                ->groupEnd();
        }
        $builder->orderBy('diff', 'desc');
        $results = $builder->get()->getResult();
        //echo $this->db->getLastQuery();   exit();
        if ($results)
            return $results;
        else
            return false;
    }





    function change_category_report_data($frm_dt, $to_dt, $report_type = 1)
    {
        $frm_dt = date('Y-m-d', strtotime($frm_dt));
        $to_dt = date('Y-m-d', strtotime($to_dt));
        $db = \Config\Database::connect();
        

        if($report_type==1){

            $users = $this->db->table('master.users')
            ->select('STRING_AGG(usercode::text, \',\') AS concatenated_usercodes')
            ->where('(usertype = 6 AND section = 19) OR (usertype = 4 AND section = 19)')
            ->where('display', 'Y')
            ->get();
            $result = $users->getRow();
            $concatenated_usercodes = $result->concatenated_usercodes;


         /*   $query1 = $this->db->table('mul_category a')
                ->join('master.users b', 'a.mul_cat_user_code = b.usercode')
                ->where('a.mul_cat_user_code in (' . $concatenated_usercodes . ')')
                ->where('b.display', 'Y')
                ->where("DATE(e_date) BETWEEN '$frm_dt' AND '$to_dt'")
                ->getCompiledSelect();

            $query2 = $this->db->table('mul_category_a a')
                ->join('master.users b', 'a.mul_cat_user_code = b.usercode')
                ->where('a.mul_cat_user_code in (' . $concatenated_usercodes . ')')
                ->where('b.display', 'Y')
                ->where("DATE(e_date) BETWEEN '$frm_dt' AND '$to_dt'")
                ->getCompiledSelect();
            //$query = $this->db->table("($query1 UNION $query2) a")->orderBy('a.name')->get();
            $query = $this->db->table("($query1 UNION $query2) a")->select('COUNT(DISTINCT a.diary_no) AS s, a.mul_cat_user_code, a.name, a.mul_cat_user_code AS usercode')->groupBy(['a.mul_cat_user_code', 'a.name'])->orderBy('a.name')->get();
            $results = $query->getResultArray(); */

            $builder = $this->db->table('mul_category a');
            $builder->select('COUNT(DISTINCT a.diary_no) AS s, a.mul_cat_user_code, b.name, a.mul_cat_user_code AS usercode');
            $builder->join('master.users b', 'a.mul_cat_user_code = b.usercode');            
            $builder->where('a.mul_cat_user_code in ('.$concatenated_usercodes.')');            
            $builder->where('b.display', 'Y');
            $builder->where("DATE(e_date) BETWEEN '$frm_dt' AND '$to_dt'");            
            $builder->groupBy('a.mul_cat_user_code');
            $builder->groupBy('b.name');            
            $builder->orderBy('b.name');            
            $query = $builder->get();             
            $results = $query->getResultArray();    
             
        }
        if ($report_type == 2) {
            $query = $this->db->table('conct_history a')
                ->select('COUNT(DISTINCT a.diary_no) AS s, b.name, STRING_AGG(distinct a.chng_by::text, \',\') AS usercode')
                ->join('master.users b', 'a.chng_by = b.usercode')
                ->where('a.usercode', 9666)
                ->where('b.display', 'Y')
                ->where("DATE(chng_date) BETWEEN '$frm_dt' AND '$to_dt'")
                ->whereIn('a.chng_by', [587, 146])
                ->groupBy('b.name')
                ->orderBy('b.name')
                ->get();
            $results = $query->getResultArray();
        }
        if ($report_type == 3) {
            $sql = "SELECT COUNT(DISTINCT aa.diary_no) AS s, b.name, aa.usercode 
                    FROM (
                        SELECT DISTINCT diary_no, usercode
                        FROM conct
                        WHERE usercode IN (587, 146) and date(ent_dt) between '$frm_dt' and '$to_dt'
                        UNION
                        SELECT DISTINCT diary_no, usercode
                        FROM conct_a
                        WHERE usercode IN (587, 146) and date(ent_dt) between '$frm_dt' and '$to_dt'
                        UNION
                        SELECT DISTINCT diary_no, usercode
                        FROM conct_history
                        WHERE usercode IN (587, 146) and date(chng_date)  between '$frm_dt' and '$to_dt'
                        UNION
                        SELECT DISTINCT diary_no, usercode
                        FROM conct_history_a
                        WHERE usercode IN (587, 146) and date(chng_date)  between '$frm_dt' and '$to_dt'
                    ) aa
                    JOIN master.users b ON aa.usercode = b.usercode AND b.display = 'Y'
                    GROUP BY aa.usercode, b.name
                    ORDER BY b.name;";
            $results = $db->query($sql)->getResultArray();
        }
        //echo $this->db->getLastQuery();
        return $results;
    }


    function change_category_report_details($frm_dt, $to_dt, $report_type, $usercode)
    {
        $frm_dt = date('Y-m-d', strtotime($frm_dt));
        $to_dt = date('Y-m-d', strtotime($to_dt));
        $db = \Config\Database::connect();

        if ($report_type == 1) {
            $sql = "SELECT 
    diary_no,
    string_agg(
        concat(sub_name1,'-',sub_name2,'-', sub_name3,'-', sub_name4), 
        '$'
        ORDER BY e_date, submaster_id 
    ) AS submaster_id,
    string_agg(a.display, ',') AS display, string_agg(e_date::varchar, ',') AS e_date
FROM 
    (select * from mul_category where mul_cat_user_code = '" . $usercode . "' AND date_trunc('day', e_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
    union
    select * from mul_category_a where mul_cat_user_code = '" . $usercode . "' AND date_trunc('day', e_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "') a 
JOIN 
    master.submaster b 
ON 
    a.submaster_id = b.id
WHERE 
    a.mul_cat_user_code = '" . $usercode . "'  
    AND date_trunc('day', e_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
GROUP BY 
    a.diary_no  
ORDER BY  
    right(a.diary_no::varchar, 4)::int, left(a.diary_no::varchar, -4)::int";
            $results = $db->query($sql)->getResultArray();
        }
        if ($report_type == 2) {
            $sql = "SELECT diary_no, conn_key 
                    FROM conct_history a 
                    WHERE a.usercode = 9666 
                    AND chng_by = $usercode
                    AND date(chng_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
                    GROUP BY diary_no,conn_key  
                    ORDER BY substr(a.diary_no::varchar, -4)::int;";
            $results = $db->query($sql)->getResultArray();
        }
        if ($report_type == 3) {
            $sql = "SELECT diary_no, conn_key, conn_type
                    FROM (
                        SELECT DISTINCT diary_no, conn_key, conn_type
                        FROM conct
                        WHERE 
                        usercode = '" . $usercode . "' AND date(ent_dt) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
                        UNION 
                        SELECT DISTINCT diary_no, conn_key, conn_type
                        FROM conct_history
                        WHERE 
                        usercode = '" . $usercode . "' AND date(chng_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
                        union 
                        SELECT DISTINCT diary_no, conn_key, conn_type
                        FROM conct_a
                        WHERE 
                        usercode = '" . $usercode . "' AND date(ent_dt) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
                        UNION 
                        SELECT DISTINCT diary_no, conn_key, conn_type
                        FROM conct_history_a
                        WHERE 
                        usercode = '" . $usercode . "' AND date(chng_date) BETWEEN '" . $frm_dt . "' AND '" . $to_dt . "'
                    ) aa
                    GROUP BY aa.diary_no,conn_key,conn_type
                    ORDER BY right(diary_no::varchar, 4)::int, left(diary_no::varchar, -4)";
            $results = $db->query($sql)->getResultArray();
        }
        //echo $this->db->getLastQuery();
        return $results;
    }

    function category_enetered_by_checker($diary_no, $usercode)
    {
        $sql = "SELECT submaster_id, sub_name1, sub_name2, sub_name3, sub_name4, name, e_date, a.display  
                FROM (select * from mul_category where diary_no='$diary_no' AND mul_cat_user_code != '$usercode' union select * from mul_category_a where diary_no='$diary_no' AND mul_cat_user_code != '$usercode')  a 
                JOIN master.submaster b ON a.submaster_id = b.id 
                LEFT JOIN master.users c ON c.usercode = a.mul_cat_user_code ;";
        $results = $this->db->query($sql)->getResultArray();
        return $results;
    }

    public function getTrapData($date)
    {
        $query = "
        SELECT   
        a.name AS name,
        a.d_to_empid,
        SUM(a.pending) AS pending,
        SUM(a.comp) AS comp,
        SUM(a.sent) AS sent
    FROM (
        SELECT
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name,
            COUNT(*) AS pending,
            SUM(CASE WHEN DATE(comp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
        FROM fil_trap a
        LEFT JOIN master.users b ON d_to_empid = empid
        GROUP BY
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name
        UNION 
        SELECT
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name,
            COUNT(*) AS pending,
            SUM(CASE WHEN DATE(comp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
        FROM fil_trap_a a
        LEFT JOIN master.users b ON d_to_empid = empid
        GROUP BY
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name
            union
            
            SELECT
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name,
            0 AS pending,
            SUM(CASE WHEN DATE(comp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
        FROM fil_trap_his a
        LEFT JOIN master.users b ON d_to_empid = empid
        GROUP BY
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name
            union
            SELECT
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name,
            0 AS pending,
            SUM(CASE WHEN DATE(comp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS comp,
            SUM(CASE WHEN DATE(disp_dt) = '$date' AND d_to_empid = empid THEN 1 ELSE 0 END) AS sent
        FROM fil_trap_his_a a
        LEFT JOIN master.users b ON d_to_empid = empid
        GROUP BY
            diary_no,
            d_by_empid,
            d_to_empid,
            r_by_empid,
            disp_dt,
            rece_dt,
            comp_dt,
            remarks,
            other,
            name
            
    ) a
    GROUP by
    a.name,
    a.d_to_empid
    ORDER BY
  d_to_empid
";


        $results = $this->db->query($query)->getResult();

        $groupedResult = [];
        foreach ($results as $row) {
            $name = $row->name;
            if (!isset($groupedResult[$name])) {
                $groupedResult[$name] = (object)[
                    'name' => $name,
                    'pending' => 0,
                    'comp' => 0,
                    'sent' => 0,
                ];
            }
            $groupedResult[$name]->pending += $row->pending;
            $groupedResult[$name]->comp += $row->comp;
            $groupedResult[$name]->sent += $row->sent;
        }

        return $groupedResult;
    }

    function rcc_report($from_date, $to_date, $condition)
    {
        $to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));
        $db = \Config\Database::connect();
        $sql = "select case when tentative_section(diary_no)  is null then 'Others' else tentative_section(diary_no)  end as section , count(*) as total
                from main   where diary_no_rec_date between '$from_date' and  '$to_date' $condition group by section
                union all
                select case when tentative_section(diary_no)  is null then 'Others' else tentative_section(diary_no)  end as section , count(*) as total
                from main_a   where diary_no_rec_date between '$from_date' and  '$to_date' $condition group by section";
        $result = $db->query($sql);
        //echo $this->db->getLastQuery();
        //die;
        return $result->getResultArray();
    }

    function rcc_section_detail_report($from_date, $to_date, $condition, $section)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));
        if ($section != '' && $section != 'Others')
            $condition1 = " and us.section_name='$section'";
        else if ($section == 'Others')
            $condition1 = " and (us.section_name='' or us.section_name is null)";
        else
            $condition1 = "";

        $db = \Config\Database::connect();
        $sql = "select left(m.diary_no::varchar, -4) AS diary_no,right(m.diary_no::varchar, 4) as diary_year,
                 diary_no_rec_date as diary_date,        
                pet_name,res_name,b.name as pet_adv_id,
                c_status,u.name as diary_user_id,reg_no_display,sis.Name as ref_agency_state_id,
                rac.agency_name ref_agency_code_id,
                reg_no_display, pno,rno,u1.name as DA_name,c.casename,us.section_name,
                 string_agg(concat(docnum,'/',docyear, ' (', docdesc, ')') , ', ') as ia_info 
                from (select diary_no, pet_name,res_name, c_status, reg_no_display,pet_adv_id,diary_user_id,ref_agency_state_id,ref_agency_code_id,dacode,casetype_id,diary_no_rec_date,pno,rno from main  where diary_no_rec_date between '$from_date' and '$to_date' $condition
                union select diary_no, pet_name,res_name, c_status, reg_no_display,pet_adv_id,diary_user_id,ref_agency_state_id,ref_agency_code_id,dacode,casetype_id,diary_no_rec_date,pno,rno from main_a   where diary_no_rec_date between '$from_date' and '$to_date' $condition)m
                inner join master.bar b on m.pet_adv_id=b.bar_id
                left  join master.users u on m.diary_user_id=u.usercode
                left join master.state sis on m.ref_agency_state_id=sis.id_no
                left join master.ref_agency_code rac on m.ref_agency_code_id=rac.id
                left join master.users u1 on m.dacode=u1.usercode
                join master.casetype c on m.casetype_id=c.casecode
                left join master.usersection us on u1.section=us.id  
                left join docdetails dd on m.diary_no=dd.diary_no
                left join master.docmaster dm on dd.doccode=dm.doccode and dd.doccode1=dm.doccode1
            where diary_no_rec_date between '$from_date' and '$to_date'
            $condition $condition1
             group by m.diary_no,b.name,
                c_status,u.name,reg_no_display,sis.Name,
                rac.agency_name, ref_agency_code_id,
                reg_no_display, pno,rno,u1.name,c.casename,us.section_name,diary_no_rec_date,pet_name,res_name
            order by us.section_name,u1.name,c.casename desc";
        $result = $db->query($sql);
         //echo $this->db->getLastQuery();
        //die;
        return $result->getResultArray();
    }

    function get_complete_filing_report($report_date, $report_for)
    {
        $report_date = date('Y-m-d', strtotime($report_date));
        $ddl_pip_caveat = '';
        if ($report_for != '0') {
            if ($report_for == '584') {
                $ddl_pip_caveat = " join advocate adv on adv.diary_no=a.diary_no and advocate_id='584' and adv.display='Y'";
            } else if ($report_for == 'C') {
                $ddl_pip_caveat = " join caveat_diary_matching adv on adv.diary_no=a.diary_no and adv.display='Y'";
            }
        }

        $db = \Config\Database::connect();

        // Query 1
        $sql = "SELECT SUM(total_fil) total_fil, SUM(total_reg) total_reg FROM (
                SELECT SUM(CASE WHEN DATE(diary_no_rec_date) = '$report_date' THEN 1 ELSE 0 END) total_fil, 
                       SUM(CASE WHEN DATE(fil_dt) = '$report_date' THEN 1 ELSE 0 END) total_reg
                FROM main
                UNION
                SELECT SUM(CASE WHEN DATE(diary_no_rec_date) = '$report_date' THEN 1 ELSE 0 END) total_fil, 
                       SUM(CASE WHEN DATE(fil_dt) = '$report_date' THEN 1 ELSE 0 END) total_reg
                FROM main_a
        ) cases";
        $result = $db->query($sql)->getRowArray();
        $result_array['filed'] = $result['total_fil'];
        $result_array['registered'] = $result['total_reg'];
        // Query 2
        $sql1 = "SELECT SUM(e_filed) e_filed, SUM(counter_filed) counter_filed FROM (
                 SELECT SUM(CASE WHEN (ack_id != 0) THEN 1 ELSE 0 END) e_filed, 
                        SUM(CASE WHEN (ack_id = 0) THEN 1 ELSE 0 END) counter_filed 
                 FROM main 
                 WHERE DATE(diary_no_rec_date) = '$report_date'
                 UNION   
                 SELECT SUM(CASE WHEN (ack_id != 0) THEN 1 ELSE 0 END) e_filed, 
                        SUM(CASE WHEN (ack_id = 0) THEN 1 ELSE 0 END) counter_filed 
                 FROM main_a 
                 WHERE DATE(diary_no_rec_date) = '$report_date'
        ) efiled_Cases";
        $result = $db->query($sql1)->getRowArray();
        $result_array['counter_filed'] = $result['counter_filed'];
        $result_array['e_filed'] = $result['e_filed'];

        // Query 3
        $sql2 = "SELECT COUNT(diary_no) refiled FROM (
                 SELECT DISTINCT diary_no FROM obj_save WHERE DATE(rm_dt) = '$report_date' AND display='Y' 
                 UNION 
                 SELECT DISTINCT diary_no FROM obj_save_a WHERE DATE(rm_dt) = '$report_date' AND display='Y'
        ) a";
        $result = $db->query($sql2)->getRowArray();
        $result_array['refiled'] = $result['refiled'];

        // Query 4
        $sql3 = "SELECT SUM(total) total, SUM(veri) veri FROM (
                 SELECT COUNT(*) total, 
                        SUM(CASE verification_status WHEN '0' THEN 1 ELSE 0 END) veri 
                 FROM defects_verification
                 WHERE DATE(verification_date) = '$report_date'
                 UNION 
                 SELECT COUNT(*) total, 
                        SUM(CASE verification_status WHEN '0' THEN 1 ELSE 0 END) veri 
                 FROM defects_verification_a
                 WHERE DATE(verification_date) = '$report_date'
        ) b";
        $result = $db->query($sql3)->getRowArray();
        $result_array['verified'] = $result['veri'];
        $result_array['t_crawl_veri'] = $result['total'];

        // Query 5 - Adding table alias for listorder
        $sql4 = "SELECT COUNT(DISTINCT a.diary_no) pending_tagging
                 FROM defects_verification a
                 JOIN main b ON a.diary_no = b.diary_no $ddl_pip_caveat
                 WHERE verification_status = '1' AND b.c_status = 'P'";
        $result = $db->query($sql4)->getRowArray();
        $result_array['pending_tagging'] = $result['pending_tagging'];

        // Query 6 - Correction for ambiguous column reference (listorder)
        $sql5 = "SELECT COUNT(*) tt, SUM(CASE WHEN fil_dt IS NOT NULL THEN 1 ELSE 0 END) reg
                 FROM (
                 SELECT a.diary_no, m.fil_dt
                 FROM obj_save a
                 LEFT JOIN heardt b ON a.diary_no = b.diary_no
                 LEFT JOIN main m ON a.diary_no = m.diary_no $ddl_pip_caveat
                 WHERE a.display = 'Y'
                 AND (
                     (b.diary_no IS NULL)
                     OR (b.next_dt IS NULL AND b.listorder = 0 AND subhead = 0 AND mainhead = 'M')
                     OR (b.next_dt IS NULL AND mainhead IS NULL)
                 )
                 AND c_status = 'P' 
                 GROUP BY a.diary_no, m.fil_dt
        ) bb";
        $result = $db->query($sql5)->getRowArray();
        $result_array['pending_ver_aft_ref'] = $result['tt'];
        $result_array['pending_ver_aft_ref_registered'] = $result['reg'];

        return $result_array;
    }


    function get_complete_filing_details($report_for, $type)
    {
        $ddl_pip_caveat = '';
        $pip_caveat = '';
        $ddl_pip_caveat1 = '';
        $pip_caveat1 = '';
        $db = \Config\Database::connect();
        if ($report_for != '0') {
            if ($report_for == '584') {
                $ddl_pip_caveat = " join advocate adv on adv.diary_no=a.diary_no and advocate_id='584' and adv.display='Y'";
                $pip_caveat = ",CASE WHEN string_agg(DISTINCT pet_res, '') iLIKE '%P%' THEN 'Y' ELSE ''  END AS caveat_no";
            } else if ($report_for == 'C') {
                $ddl_pip_caveat = " join caveat_diary_matching adv on adv.diary_no=a.diary_no  and adv.display='Y'";
                $pip_caveat = ",string_agg(DISTINCT CONCAT(SUBSTRING(caveat_no::varchar, 1, CHAR_LENGTH(caveat_no::varchar) - 4), '-', right(caveat_no::varchar, 4)),' ,') AS caveat_no";
            }
        } else {
            $ddl_pip_caveat = " left join advocate adv on adv.diary_no=a.diary_no and advocate_id='584' and adv.display='Y'";
            $pip_caveat = ",CASE WHEN string_agg(DISTINCT pet_res, '') iLIKE '%P%' THEN 'Petitioner In Person' ELSE ''  END AS caveat_no";
            $ddl_pip_caveat1 = " left join caveat_diary_matching adv1 on adv1.diary_no=a.diary_no  and adv1.display='Y'";
            $pip_caveat1 = ",string_agg(DISTINCT CONCAT(SUBSTRING(caveat_no::varchar, 1, CHAR_LENGTH(caveat_no::varchar) - 4), '-', right(caveat_no::varchar, 4)),' ,') AS caveat_no1";
        }

        if ($type == 1) {
            /*$sql = "SELECT distinct a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year $pip_caveat $pip_caveat1
                    FROM obj_save a
                    LEFT JOIN heardt b ON a.diary_no = b.diary_no
                    LEFT JOIN main m ON a.diary_no = m.diary_no 
                    LEFT JOIN master.casetype c ON casetype_id=casecode $ddl_pip_caveat $ddl_pip_caveat1
                    WHERE a.display = 'Y' AND ((b.diary_no IS NULL) OR (b.next_dt is null AND listorder =0 AND subhead =0 AND mainhead = 'M') OR (b.next_dt is null AND mainhead IS NULL)) AND c_status = 'P' 
                    group by a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year";*/
            $sql = "SELECT distinct a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year 
                    FROM obj_save a
                    LEFT JOIN heardt b ON a.diary_no = b.diary_no
                    LEFT JOIN main m ON a.diary_no = m.diary_no 
                    LEFT JOIN master.casetype c ON casetype_id=casecode 
                    WHERE a.display = 'Y' AND ((b.diary_no IS NULL) OR (b.next_dt is null AND listorder =0 AND subhead =0 AND mainhead = 'M') OR (b.next_dt is null AND mainhead IS NULL)) AND c_status = 'P' 
                    group by a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year";
        } else if ($type == 2) {
            $sql = "SELECT a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year,verification_date $pip_caveat $pip_caveat1
                    FROM defects_verification a 
                    LEFT JOIN main m ON a.diary_no = m.diary_no
                    LEFT JOIN master.casetype c ON casetype_id=casecode $ddl_pip_caveat $ddl_pip_caveat1
                    WHERE verification_status='1' and c_status='P' 
                    group by a.diary_no, m.fil_no, m.fil_dt,short_description,active_reg_year,verification_date
                    ORDER BY fil_dt";
        }
        return $db->query($sql)->getResultArray();
    }
    /* Added by Shilpa Start - 05032024*/
    function loose_document_report($first_date, $to_date, $report)
    {
        $db = \Config\Database::connect();
        if ($report == 1) {
            $sql3 = "select date1, sum(documents) as documents from (select date(ent_dt) as date1,count(*) as documents from docdetails where display='Y' and date(ent_dt) between '$first_date' and '$to_date' 
group by date(ent_dt) UNION select date(ent_dt) as date1,count(*) as documents from docdetails_a where display='Y' and date(ent_dt) between '$first_date' and '$to_date' group by date(ent_dt)) as a
group by date1; ";
        } else {
            $sql3 = "select usercode, name, empid, sum(documents) as documents from (select dc.usercode,name,empid,count(1) as documents from docdetails dc left join 
            master.users u on dc.usercode=u.usercode where date(ent_dt) between '$first_date' and '$to_date'
            and dc.display='Y' and dc.usercode  is not null and dc.usercode != 0  group by dc.usercode,u.name,u.empid
            UNION
            select dc.usercode,name,empid,count(1) as documents from docdetails_a dc left join 
            master.users u on dc.usercode=u.usercode where date(ent_dt) between '$first_date' and '$to_date'
            and dc.display='Y' and dc.usercode  is not null and dc.usercode != 0  group by dc.usercode,u.name,u.empid)a group by usercode, name, empid";
        }
        $result = $db->query($sql3)->getResultArray();
        return $result;
    }

    function loose_document_detail_report($date = 0, $first_date = 0, $to_date = 0, $user = 0, $sorting = 1)
    {
        $db = \Config\Database::connect();
        $sql = "";
        if ($sorting == 1)
            $sorting_condition = "docyear, docnum";
        else if ($sorting == 2)
            $sorting_condition = "da_section";

        if ($date != 0 && empty($first_date) && empty($to_date) && empty($user)) {

            $sql = "select diary_no,concat(pet_name,' Vs ',res_name) as causetitle ,docdesc,docnum,docyear,filedby,dak_name, dak_empid,ent_dt,da_name,da_empid,da_section,next_date,concat(docnum,'/',docyear) as document,(CURRENT_DATE - next_date) as diff from (
                    select dc.diary_no as diary_no,pet_name , res_name ,docdesc,docnum,docyear,filedby,
                    u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
                    us.section_name as da_section,date(h.next_dt) as next_date from docdetails dc left join master.docmaster dm 
                    on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
                    left join master.users u_dak on u_dak.usercode=dc.usercode left join master.users da on da.usercode=m.dacode 
                    left join master.usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where 
                    date(dc.ent_dt)='$date' and dm.display='Y' and dc.display='Y'
                    UNION
                    select dc.diary_no as diary_no,pet_name , res_name ,docdesc,docnum,docyear,filedby,
                    u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
                    us.section_name as da_section,date(h.next_dt) as next_date from docdetails_a dc left join master.docmaster 
                    dm 
                    on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main_a m on dc.diary_no=m.diary_no 
                    left join master.users u_dak on u_dak.usercode=dc.usercode left join master.users da on da.usercode=m.dacode 
                    left join master.usersection us on us.id=da.section left join heardt_a h on h.diary_no=dc.diary_no where 
                    date(dc.ent_dt)='$date' and dm.display='Y' and dc.display='Y') a  order by $sorting_condition";
        } else if (!empty($first_date) && !empty($to_date) && !empty($user) != 0 && empty($date)) {

                $sql = "select diary_no,causetitle, docdesc, docdesc,docnum, docyear,filedby,dak_name,dak_empid,ent_dt,da_name,
                    da_empid,da_section,next_date,concat(docnum,'/',docyear) as document,(CURRENT_DATE - next_date) as diff from (
                    select dc.diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,docnum, docyear,filedby,
                    u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
                    us.section_name as da_section,date(h.next_dt) as next_date from docdetails dc left join master.docmaster dm 
                    on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main m on dc.diary_no=m.diary_no 
                    left join master.users u_dak on u_dak.usercode=dc.usercode left join master.users da on da.usercode=m.dacode 
                    left join master.usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt) between 
                    '$first_date' and '$to_date' and dc.usercode = $user
                    and dm.display='Y' and dc.display='Y'
                    UNION
                    select dc.diary_no,concat(pet_name,' Vs ',res_name) as causetitle,docdesc,docnum, docyear,filedby,
                    u_dak.name as dak_name,u_dak.empid as dak_empid,dc.ent_dt,da.name as da_name,da.empid as da_empid,
                    us.section_name as da_section,date(h.next_dt) as next_date from docdetails_a dc left join master.docmaster dm 
                    on dc.doccode=dm.doccode and dc.doccode1=dm.doccode1 left join main_a m on dc.diary_no=m.diary_no 
                    left join master.users u_dak on u_dak.usercode=dc.usercode left join master.users da on da.usercode=m.dacode 
                    left join master.usersection us on us.id=da.section left join heardt h on h.diary_no=dc.diary_no where date(dc.ent_dt) between 
                    '$first_date' and '$to_date' and dc.usercode = $user
                    and dm.display='Y' and dc.display='Y') a order by $sorting_condition";
        }

        $result = $db->query($sql)->getResultArray();
        return $result;
    }
    /* Added by Shilpa End - 05032024*/


    public function getSectionLIst($empid)
    {
        $builder = $this->db->table('master.user_sec_map a');
        $builder->select('b.section_name,a.usec')
            ->join('master.usersection b', 'a.usec = b.id', 'left')
            ->where('a.empid', $empid)
            ->where('a.display', 'Y')
            ->where('b.display', 'Y');

        // Execute the query
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function ibget_workdone_get_data()
    {
        $sql = "select name, empid ,usercode,type_name from master.users join master.usertype on users.usertype=usertype.id and section=77 and usertype.id in (17,50,51) order by empid,type_name";

        $query = $this->db->query($sql);

        $result = $query->getResultArray();
        return $result;
    }

    public function get_sql_or($usercode, $date)
    {
        $sql  = "select * from office_report_details where rec_user_id=$usercode and DATE(rec_dt)='" . revertDate_hiphen($date) . "' and display='Y' and web_status=1 ";

        $query = $this->db->query($sql);
        $result = $query->getRow();
        return $result;
    }

    public function ibget_workdone_full_get_data($date, $id)
    {
        $sql = "SELECT 
                    main.diary_no,
                    reg_no_display,
                    CONCAT(pet_name, ' VS ', res_name) AS ct,
                    TO_CHAR(rec_dt, 'DD/MM/YYYY HH24:MI') AS rec_dt,
                    TO_CHAR(order_dt, 'DD/MM/YYYY') AS order_dt
                FROM 
                    office_report_details 
                JOIN 
                    main 
                ON 
                    office_report_details.diary_no = main.diary_no 
                WHERE 
                    rec_user_id = $id 
                    AND rec_dt::date = '" . revertDate_hiphen($date) . "' 
                    AND display = 'Y' 
                    AND web_status = 1";
        $query = $this->db->query($sql);
        $result =  $query->getResultArray();
        return $result;
    }
}
