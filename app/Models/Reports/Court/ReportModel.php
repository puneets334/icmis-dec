<?php

namespace App\Models\Reports\Court;

use CodeIgniter\Model; 

class ReportModel extends Model
{

    protected $eservicesdb;
     
    public function __construct(){
        parent::__construct();        
        $this->eservicesdb = \Config\Database::connect('eservices');
    }
    function get_rosterid($data) {
        $builder = $this->db->table('master.roster_judge rj');
        $builder->select('roster_id');
        $builder->selectMax('board_type_mb', 'board_type_mb');
        $builder->selectMax('(CASE WHEN courtno = 0 THEN 9999 ELSE courtno END)', 'custom_order', false);
        $builder->selectMax('(CASE WHEN board_type_mb = \'J\' THEN 1 WHEN board_type_mb = \'S\' THEN 2 WHEN board_type_mb = \'C\' THEN 3 WHEN board_type_mb = \'CC\' THEN 4 WHEN board_type_mb = \'R\' THEN 5 END)', 'type_order', false);
        $builder->selectMax('judge_id', 'judge_id');
        $builder->join('master.roster r', 'rj.roster_id = r.id', 'left');
        $builder->join('master.roster_bench rb', 'rb.id = r.bench_id AND rb.display = \'Y\'', 'left');
        $builder->join('master.master_bench mb', 'mb.id = rb.bench_id AND mb.display = \'Y\'', 'left');
        $builder->where('r.m_f', '1');
        $builder->where('courtno', $data['courtno']);
        $builder->where('from_date >=', $data['cause_list_date']);
        $builder->where('to_date <=', $data['cause_list_date']);
        $builder->where('rj.display', 'Y');
        $builder->where('r.display', 'Y');
        $builder->groupBy('roster_id');
        $builder->orderBy('custom_order');
        $builder->orderBy('type_order');
        $builder->orderBy('judge_id');
        return $results = $builder->get()->getResult();

    }
    function getpaperless_court($data){

        $builder = $this->db->table("main m");
        //$builder->selectDistinct();
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
        if($data['reg_or_def']){
            $builder->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no','inner', false);
        }
        if($data['from_date']){
            $builder->where('date(m.diary_no_rec_date) >=', $data['from_date']);
        }
        if($data['to_date']){
            $builder->where('date(m.diary_no_rec_date) <=', $data['to_date']);
        }
        if($data['diary_no']){
            $builder->where('m.diary_no', $data['diary_no']);
        }
        if($data['cause_title']){
            $builder->orLike($data['parties']);
        }
        if($data['case_type_casecode']){
            $builder->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if($data['isma']){
            $builder->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if($data['is_inperson']){
            $builder->whereIn('m.pet_adv_id', [584, 666, 940]);
        }

        if($data['is_efiled_pfiled'] == 'pfiled'){
            $builder->groupStart();
            $builder->where('ack_id', 0);
           $builder->orWhere('ack_id IS NULL');
            //$builder->Where('ack_id IS NULL');
            $builder->groupEnd();
        }
        if($data['is_efiled_pfiled'] == 'efiled'){
            $builder->groupStart();
            $builder->where('ack_id <>', 0);
            $builder->Where('ack_id IS NOT NULL');
            $builder->groupEnd();
        }

        $builder->orderBy('m.diary_no_rec_date desc');
        $builder->orderBy('dno');
        $builder->limit(5000);
        $builder = $builder->get()->getResult();
        //echo $this->db->getLastQuery();
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
        if($data['reg_or_def']){
            $builder2->join("(SELECT * FROM obj_save WHERE display='Y' AND rm_dt IS NULL AND org_id !=10193) as o", 'm.diary_no = o.diary_no','inner', false);
        }
        if($data['from_date']){
            $builder2->where('m.diary_no_rec_date >=', $data['from_date']);
        }
        if($data['to_date']){
            $builder2->where('m.diary_no_rec_date <=', $data['to_date']);
        }
        if($data['diary_no']){
            $builder2->where('m.diary_no', $data['diary_no']);
        }
        if($data['cause_title']){
            $builder2->orLike($data['parties']);
        }
        if($data['case_type_casecode']){
            $builder2->whereIn('casetype_id', [$data['case_type_casecode']]);
        }
        if($data['isma']){
            $builder2->whereNotIn('m.casetype_id', [9, 10, 19, 25, 26, 20, 39]);
        }
        if($data['is_inperson']){
            $builder2->whereIn('m.pet_adv_id', [584, 666, 940]);
        }
        if($data['is_efiled_pfiled'] == 'pfiled'){
            $builder2->groupStart();
            $builder2->where('ack_id', 0);
            $builder2->orWhere('ack_id IS NULL');
            //$builder->Where('ack_id IS NULL');
            $builder2->groupEnd();
        }
        if($data['is_efiled_pfiled'] == 'efiled'){
            $builder2->groupStart();
            $builder2->where('ack_id <>', 0);
            $builder2->Where('ack_id IS NOT NULL');
            $builder2->groupEnd();
        }

        $builder2->orderBy('m.diary_no_rec_date desc');
        $builder2->orderBy('dno');
        $builder2->limit(5000);
        $builder2 = $builder2->get()->getResult();
        echo $this->db->getLastQuery();exit;

      return  $result = array_merge($builder, $builder2);

    }
    function getPartHeard($data){
     if($data['mr'] == 'l')
        {
            $usercondition = "";
        }
        else{
            $usercondition = "and h.mainhead = '" . $data['mr'] . "'";
        }
        if($data['judge'] == '0')
        {   $q2 = "";   }
        else{
            $q2 = " and n.j1 = '" . $data['judge'] . "' ";
        }

        if($data['report_type'] =='S')
        {
            $q2 .= " and (m.lastorder not like '%Heard & Reserved%' and (m.lastorder is null or m.lastorder = '' or m.lastorder != '')) and NOT (m.lastorder like '%Part Heard%' OR h.subhead = '824' OR mc.submaster_id = '913') ";
        }
        else if($data['report_type'] =='P')
        {
            $q2 .= " and (m.lastorder not like '%Heard & Reserved%' and (m.lastorder is null or m.lastorder = '' or m.lastorder != '')) and (m.lastorder like '%Part Heard%' OR h.subhead = '824' OR mc.submaster_id = '913') ";
        }
        else if($data['report_type'] == 'l')
        {
            $q2 .= " and (m.lastorder not like '%Heard & Reserved%' and (m.lastorder is null or m.lastorder = '' or m.lastorder != '')) ";
        }

        $builder = $this->db->query("SELECT  c.* FROM    (    SELECT   m.diary_no AS Diary_No,  m.reg_no_display AS Case_NO, CONCAT(m.pet_name, ' Vs. ', m.res_name) AS Cause_title,
            STRING_AGG(DISTINCT  (  SELECT STRING_AGG(abbreviation, ',')  FROM master.judge, not_before   WHERE not_before.diary_no = n.diary_no  AND judge.jcode = not_before.j1
                        AND not_before.notbef = 'B'), '') AS coram,TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Next_Listing_Dt,TO_CHAR(MAX(c1.max_cl_dt), 'DD-MM-YYYY') AS Last_listed_on,
            m.diary_no AS section, m.diary_no AS DA  FROM  heardt h
            INNER JOIN main m ON  m.diary_no = h.diary_no
            LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
        LEFT JOIN ( SELECT  c.diary_no,  MAX(cl_date) AS max_cl_dt  FROM  case_remarks_multiple c    GROUP BY  c.diary_no ) AS c1 ON cast(c1.diary_no as BIGINT) = m.diary_no
        INNER JOIN not_before n ON m.diary_no = cast(n.diary_no as BIGINT)
        WHERE c_status = 'P' ".$usercondition." ".$q2."  AND ( m.diary_no::TEXT = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') AND n.notbef = 'B'
        GROUP BY
                    m.diary_no,h.next_dt,m.reg_no_display,m.pet_name,m.res_name
        ORDER by
        right((cast(m.diary_no as text)),4) asc, left((cast(m.diary_no as text)),-4) ASC) c");

        $builder= $builder->getResultArray();

        $builder2 = $this->db->query("SELECT  c.* FROM    (    SELECT   m.diary_no AS Diary_No,  m.reg_no_display AS Case_NO, CONCAT(m.pet_name, ' Vs. ', m.res_name) AS Cause_title,
            STRING_AGG(DISTINCT  (  SELECT STRING_AGG(abbreviation, ',')  FROM master.judge, not_before   WHERE not_before.diary_no = n.diary_no  AND judge.jcode = not_before.j1
                        AND not_before.notbef = 'B'), '') AS coram,TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Next_Listing_Dt,TO_CHAR(MAX(c1.max_cl_dt), 'DD-MM-YYYY') AS Last_listed_on,
            m.diary_no AS section, m.diary_no AS DA  FROM  heardt_a h
            INNER JOIN main_a m ON  m.diary_no = h.diary_no
            LEFT JOIN mul_category_a mc ON mc.diary_no = m.diary_no AND mc.display = 'Y'
        LEFT JOIN ( SELECT  c.diary_no,  MAX(cl_date) AS max_cl_dt  FROM  case_remarks_multiple c    GROUP BY  c.diary_no ) AS c1 ON cast(c1.diary_no as BIGINT) = m.diary_no
        INNER JOIN not_before n ON m.diary_no = cast(n.diary_no as BIGINT)
        WHERE c_status = 'P' ".$usercondition." ".$q2."   AND ( m.diary_no::TEXT = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') AND n.notbef = 'B'
        GROUP BY
                    m.diary_no,h.next_dt,m.reg_no_display,m.pet_name,m.res_name
        ORDER by
        right((cast(m.diary_no as text)),4) asc, left((cast(m.diary_no as text)),-4) ASC) c");
        $builder2= $builder2->getResultArray();

        //     echo $this->db->getLastquery();
        return  $result = array_merge($builder, $builder2);


    }

    function getJname($jcode){
        $builder = $this->db->table("master.judge");
        $builder->select("distinct(jname)");
        $builder->where("jcode",$jcode);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    function max_gist_last_read_datetime($jcode){
        $builder = $this->db->table("office_report_details");
        $builder->select("MAX(gist_last_read_datetime) as max_date");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    function getGistModule($data,$board_type,$main_suppl){

            if($main_suppl == "0"){
                $main_suppl = "";
            }
            else{
                $main_suppl = "AND h.main_supp_flag = '".$main_suppl."'";
                if($main_suppl == "1"){
                    $main_supl_head = "Main List";
                }
                if($main_suppl == "2"){
                    $main_supl_head = "Supplimentary List";
                }
            }

            if($board_type == "0"){
                $board_type = "";
            }
            else{
                if($board_type == 'C'){
                    $mainhead_descri = "Chamber Matters in ";
                }
                else if($board_type == 'R'){
                    $mainhead_descri = "Registrar ";
                }
                $board_type = "AND h.board_type = '".$board_type."'";
            }


             $builder = $this->db->query("SELECT  ord.rec_dt,ord.gist_last_read_datetime, ord.summary, m.diary_no, m.conn_key, r.courtno, m.reg_no_display, m.pet_name, m.res_name,
    m.pno, m.rno,  h.brd_slno,  tentative_section(m.diary_no) AS section_name 
FROM heardt h
INNER JOIN main m ON m.diary_no = h.diary_no
INNER JOIN master.roster r ON r.id = h.roster_id  AND r.display = 'Y'  AND r.courtno = '".$data['courtno']."'
LEFT JOIN cl_printed p ON  p.next_dt = h.next_dt  AND p.part = h.clno  AND p.roster_id = h.roster_id  AND p.display = 'Y'
LEFT JOIN conct ct ON   m.diary_no = ct.diary_no   AND ct.list = 'Y' 
LEFT JOIN office_report_details ord ON  ord.diary_no = m.diary_no  AND ord.order_dt = h.next_dt  AND ord.display = 'Y'  AND ord.web_status = 1 
WHERE    p.id IS NOT NULL  AND h.mainhead = '".$data['mainhead']."'  ".$main_suppl."  AND date(h.next_dt) = '".$data['listing_dts']."'  ".$board_type."  AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
    AND h.roster_id > 0  AND m.diary_no IS NOT NULL  AND m.c_status = 'P'
GROUP BY h.diary_no,  ord.rec_dt, gist_last_read_datetime, ord.summary,  m.diary_no,  m.conn_key,  r.courtno,  m.reg_no_display,  m.pet_name,  m.res_name,  m.pno,  m.rno,  h.brd_slno
ORDER BY
         h.brd_slno,right((cast(m.diary_no as text)),4) asc, left((cast(m.diary_no as text)),-4) ASC");

        $builder= $builder->getResultArray();

        $builder2 = $this->db->query("SELECT  ord.rec_dt,ord.gist_last_read_datetime, ord.summary, m.diary_no, m.conn_key, r.courtno, m.reg_no_display, m.pet_name, m.res_name,
    m.pno, m.rno,  h.brd_slno,  tentative_section(m.diary_no) AS section_name 
FROM heardt_a h
INNER JOIN main_a m ON m.diary_no = h.diary_no
INNER JOIN master.roster r ON r.id = h.roster_id  AND r.display = 'Y'  AND r.courtno = '".$data['courtno']."'
LEFT JOIN cl_printed p ON  p.next_dt = h.next_dt  AND p.part = h.clno  AND p.roster_id = h.roster_id  AND p.display = 'Y'
LEFT JOIN conct ct ON   m.diary_no = ct.diary_no   AND ct.list = 'Y' 
LEFT JOIN office_report_details ord ON  ord.diary_no = m.diary_no  AND ord.order_dt = h.next_dt  AND ord.display = 'Y'  AND ord.web_status = 1 
WHERE    p.id IS NOT NULL  AND h.mainhead = '".$data['mainhead']."'  ".$main_suppl."  AND date(h.next_dt) = '".$data['listing_dts']."'  ".$board_type."  AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
    AND h.roster_id > 0  AND m.diary_no IS NOT NULL  AND m.c_status = 'P'
GROUP BY h.diary_no,  ord.rec_dt, gist_last_read_datetime, ord.summary,  m.diary_no,  m.conn_key,  r.courtno,  m.reg_no_display,  m.pet_name,  m.res_name,  m.pno,  m.rno,  h.brd_slno
ORDER BY
         h.brd_slno,right((cast(m.diary_no as text)),4) asc, left((cast(m.diary_no as text)),-4) ASC");
        $builder2= $builder2->getResultArray();

        //     echo $this->db->getLastquery();
        return  $result = array_merge($builder, $builder2);


    }

    function getCAV($data){
        if($data['judge'] == '0') {
            $filter = "";
        } else {
            $filter = " AND h.coram ILIKE '" . $data['judge'] . "%' ";
        }
    
        $query1 = "SELECT c.* FROM (
                    SELECT CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS REGNO_DNO,
                           CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                           TO_CHAR(c1.max_cl_dt, 'DD-MM-YYYY') AS Listed_On,
                           TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Previously_Listed_OR_Next_Listing_Dt,
                           COALESCE(
                                (SELECT STRING_AGG(abbreviation, '#' ORDER BY judge_seniority)  
                                 FROM master.judge
                                 WHERE jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]))
                           ,'') AS Coram,
                           CASE WHEN h.main_supp_flag = 0 AND h.next_dt > CURRENT_DATE THEN 'Ready'
                                WHEN h.main_supp_flag = 3 THEN 'Not Ready'
                                ELSE 'Updation Awaited'
                           END AS Status,  
                           m.lastorder
                    FROM heardt h
                    INNER JOIN main m ON h.diary_no = m.diary_no
                    INNER JOIN case_remarks_multiple c ON CAST(c.diary_no AS BIGINT) = m.diary_no
                    INNER JOIN (
                        SELECT c.diary_no, MAX(cl_date) AS max_cl_dt
                        FROM case_remarks_multiple c GROUP BY c.diary_no
                    ) c1 ON CAST(c1.diary_no AS BIGINT) = m.diary_no AND c1.max_cl_dt = c.cl_date
                    WHERE c.r_head = 7
                      AND m.c_status = 'P'  
                      ".$filter."
                      AND ( m.diary_no::TEXT = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    GROUP BY m.diary_no, c1.max_cl_dt, h.next_dt, h.coram, h.main_supp_flag,
                             m.reg_no_display, m.pet_name, m.res_name, m.lastorder
                    ORDER BY h.next_dt, RIGHT((CAST(m.diary_no AS TEXT)),4) ASC,
                             LEFT((CAST(m.diary_no AS TEXT)),-4) ASC) c";
    
        $query2 = "SELECT c.* FROM (
                    SELECT CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS REGNO_DNO,
                           CONCAT(pet_name, ' Vs. ', res_name) AS TITLE,
                           TO_CHAR(c1.max_cl_dt, 'DD-MM-YYYY') AS Listed_On,
                           TO_CHAR(h.next_dt, 'DD-MM-YYYY') AS Previously_Listed_OR_Next_Listing_Dt,
                           COALESCE(
                                (SELECT STRING_AGG(abbreviation, '#' ORDER BY judge_seniority)  
                                 FROM master.judge
                                 WHERE jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]))
                           ,'') AS Coram,
                           CASE WHEN h.main_supp_flag = 0 AND h.next_dt > CURRENT_DATE THEN 'Ready'
                                WHEN h.main_supp_flag = 3 THEN 'Not Ready'
                                ELSE 'Updation Awaited'
                           END AS Status,  
                           m.lastorder
                    FROM heardt_a h
                    INNER JOIN main_a m ON h.diary_no = m.diary_no
                    INNER JOIN case_remarks_multiple_a c ON CAST(c.diary_no AS BIGINT) = m.diary_no
                    INNER JOIN (
                        SELECT c.diary_no, MAX(cl_date) AS max_cl_dt
                        FROM case_remarks_multiple_a c GROUP BY c.diary_no
                    ) c1 ON CAST(c1.diary_no AS BIGINT) = m.diary_no AND c1.max_cl_dt = c.cl_date
                    WHERE c.r_head = 7
                      AND m.c_status = 'P'  
                      ".$filter."
                      AND ( m.diary_no::TEXT = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                    GROUP BY m.diary_no, c1.max_cl_dt, h.next_dt, h.coram, h.main_supp_flag,
                             m.reg_no_display, m.pet_name, m.res_name, m.lastorder
                    ORDER BY h.next_dt, RIGHT((CAST(m.diary_no AS TEXT)),4) ASC,
                             LEFT((CAST(m.diary_no AS TEXT)),-4) ASC) c";          
        // Execute Queries
        $builder = $this->db->query($query1)->getResultArray();
        $builder2 = $this->db->query($query2)->getResultArray();
    
        return array_merge($builder, $builder2);
    }

    function getDisposalRemarks($disposalon_date){

        $builder = $this->db->query("SELECT
        CASE  WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)    ELSE m.reg_year_mh  END AS m_year,  m.mf_active,  LEFT(CAST(m.diary_no AS TEXT), -4) AS diary_no,
        RIGHT(CAST(m.diary_no AS TEXT), 4) AS diary_year, CASE  WHEN (m.conn_key = '0' OR m.conn_key IS null) OR  (m.diary_no::text = m.conn_key) THEN 'M'
            ELSE    CASE   WHEN m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no::text THEN 'C'   END  END AS mainorconn,
        LEFT(CAST(m.conn_key AS TEXT), -4) as main_diary_no, RIGHT(CAST(m.conn_key AS TEXT), 4) AS main_diary_year, m.conn_key,h.judges, h.mainhead, h.next_dt, h.subhead, h.clno, h.brd_slno, h.tentative_cl_dt,
        m.pet_name, m.res_name, m.c_status, STRING_AGG(crm.r_head::TEXT, ',') AS Disp_Remarks,STRING_AGG(crh.head::TEXT, ',') AS Rmrk_Disp,
        STRING_AGG(crm.head_content::TEXT, ',') AS Head_Content,  CASE  WHEN cl.next_dt IS NULL THEN 'NA'  ELSE h.brd_slno::TEXT END AS brd_prnt,
        h.roster_id, Rt.courtno, crm.uid, CASE  WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1)   ELSE ''  END AS ct1,
        CASE WHEN m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', 2)    ELSE '' END AS crf1,
        CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3)   ELSE ''   END AS crl1,
        CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1)  ELSE ''  END AS ct2,
        CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', 2)   ELSE ''  END AS crf2,
        CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3)   ELSE '' END AS crl2, m.casetype_id, m.case_status_id
         FROM
        (   SELECT   t1.diary_no,t1.next_dt,t1.roster_id, t1.judges,t1.mainhead,t1.subhead,t1.clno,t1.brd_slno,t1.main_supp_flag,t1.tentative_cl_dt
            FROM  heardt t1    WHERE   date(t1.next_dt) = '$disposalon_date'  AND t1.mainhead = 'M' AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
            UNION
            SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead, t2.clno, t2.brd_slno,t2.main_supp_flag,t2.tentative_cl_dt
            FROM   last_heardt_a t2   WHERE   date(t2.next_dt) = '$disposalon_date'   AND t2.mainhead = 'M' AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                AND t2.bench_flag = '') h
        INNER JOIN main m ON (  h.diary_no = m.diary_no  AND date(h.next_dt) = '$disposalon_date' AND h.mainhead = 'M' AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
            )
        INNER JOIN case_remarks_multiple crm on cast(crm.diary_no as BIGINT) = m.diary_no LEFT JOIN master.roster Rt ON    Rt.id = h.roster_id
        LEFT JOIN master.case_remarks_head crh ON  crh.sno = crm.r_head AND (crh.display = 'Y' OR crh.display IS NULL)
        LEFT JOIN master.users u ON  u.usercode = crm.uid   AND (u.display = 'Y' OR u.display IS NULL)
        LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt  AND cl.m_f = h.mainhead  AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag
                AND cl.roster_id = h.roster_id AND cl.display = 'Y' )
        WHERE  date(crm.e_date) = '$disposalon_date'  AND cl.next_dt IS NOT NULL  AND m.c_status = 'D'
        GROUP BY
            m_year,m.diary_no,m.mf_active,m.conn_key,h.judges,h.mainhead,h.next_dt,h.subhead, h.clno, h.brd_slno,h.tentative_cl_dt,
            m.pet_name, m.res_name,  m.c_status,  brd_prnt,   h.roster_id,  ct1,  crf1,  crl1,  ct2,  crf2,  crl2,  m.casetype_id,
            m.case_status_id, rt.courtno,  crm.uid
        ORDER BY
            h.judges,CASE WHEN (CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno::TEXT END) = 'NA' THEN 2 ELSE 1 END,h.brd_slno, m.fil_dt::TEXT ASC");
        $builder= $builder->getResultArray();

        $builder2 = $this->db->query("SELECT
            CASE  WHEN m.reg_year_mh = 0 THEN EXTRACT(YEAR FROM m.fil_dt)    ELSE m.reg_year_mh  END AS m_year,  m.mf_active,  LEFT(CAST(m.diary_no AS TEXT), -4) AS diary_no,
            RIGHT(CAST(m.diary_no AS TEXT), 4) AS diary_year, CASE  WHEN (m.conn_key = '0' OR m.conn_key IS null) OR  (m.diary_no::text = m.conn_key) THEN 'M'
                ELSE    CASE   WHEN m.conn_key != '0' AND m.conn_key IS NOT NULL AND m.conn_key != m.diary_no::text THEN 'C'   END  END AS mainorconn,
            LEFT(CAST(m.conn_key AS TEXT), -4) as main_diary_no,  RIGHT(CAST(m.conn_key AS TEXT), 4) AS main_diary_year, m.conn_key,h.judges, h.mainhead, h.next_dt, h.subhead, h.clno, h.brd_slno, h.tentative_cl_dt,
            m.pet_name, m.res_name, m.c_status, STRING_AGG(crm.r_head::TEXT, ',') AS Disp_Remarks,STRING_AGG(crh.head::TEXT, ',') AS Rmrk_Disp,
            STRING_AGG(crm.head_content::TEXT, ',') AS Head_Content,  CASE  WHEN cl.next_dt IS NULL THEN 'NA'  ELSE h.brd_slno::TEXT END AS brd_prnt,
            h.roster_id, Rt.courtno, crm.uid, CASE  WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 1)   ELSE ''  END AS ct1,
            CASE WHEN m.fil_no != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no, '-', 2), '-', 2)    ELSE '' END AS crf1,
            CASE WHEN m.fil_no != '' THEN SPLIT_PART(m.fil_no, '-', 3)   ELSE ''   END AS crl1,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 1)  ELSE ''  END AS ct2,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(m.fil_no_fh, '-', 2), '-', 2)   ELSE ''  END AS crf2,
            CASE WHEN m.fil_no_fh != '' THEN SPLIT_PART(m.fil_no_fh, '-', 3)   ELSE '' END AS crl2, m.casetype_id, m.case_status_id
        FROM
            (   SELECT   t1.diary_no,t1.next_dt,t1.roster_id, t1.judges,t1.mainhead,t1.subhead,t1.clno,t1.brd_slno,t1.main_supp_flag,t1.tentative_cl_dt
                FROM  heardt_a t1    WHERE   date(t1.next_dt) = '$disposalon_date'  AND t1.mainhead = 'M' AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                UNION
                SELECT t2.diary_no, t2.next_dt, t2.roster_id, t2.judges, t2.mainhead, t2.subhead, t2.clno, t2.brd_slno,t2.main_supp_flag,t2.tentative_cl_dt
                FROM   last_heardt_a t2   WHERE   date(t2.next_dt) = '$disposalon_date'   AND t2.mainhead = 'M' AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                    AND t2.bench_flag = '') h
        INNER JOIN main_a m ON (  h.diary_no = m.diary_no  AND date(h.next_dt) = '$disposalon_date' AND h.mainhead = 'M' AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
            )
        INNER JOIN case_remarks_multiple_a crm on cast(crm.diary_no as BIGINT) = m.diary_no LEFT JOIN master.roster Rt ON    Rt.id = h.roster_id
        LEFT JOIN master.case_remarks_head crh ON  crh.sno = crm.r_head AND (crh.display = 'Y' OR crh.display IS NULL)
        LEFT JOIN master.users u ON  u.usercode = crm.uid   AND (u.display = 'Y' OR u.display IS NULL)
        LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt  AND cl.m_f = h.mainhead  AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag
                AND cl.roster_id = h.roster_id AND cl.display = 'Y' )
        WHERE  date(crm.e_date) = '$disposalon_date'  AND cl.next_dt IS NOT NULL  AND m.c_status = 'D'
        GROUP BY
            m_year,m.diary_no,m.mf_active,m.conn_key,h.judges,h.mainhead,h.next_dt,h.subhead, h.clno, h.brd_slno,h.tentative_cl_dt,
            m.pet_name, m.res_name,  m.c_status,  brd_prnt,   h.roster_id,  ct1,  crf1,  crl1,  ct2,  crf2,  crl2,  m.casetype_id,
            m.case_status_id, rt.courtno,  crm.uid,m.fil_dt
        ORDER BY
            h.judges,CASE WHEN (CASE WHEN cl.next_dt IS NULL THEN 'NA' ELSE h.brd_slno::TEXT END) = 'NA' THEN 2 ELSE 1 END,h.brd_slno, m.fil_dt::TEXT ASC");
        $builder2= $builder2->getResultArray();
        return  $result = array_merge($builder, $builder2);


    }

    function getmm_disposed($data){

        $builder = $this->db->table('mention_memo mm');
        $builder->select('mm.date_of_received, left((cast(m.diary_no as text)),-4) as dn, 
        right((cast(m.diary_no as text)),4) as dy, m.reg_no_display, d.ord_dt, u.name, u.empid, us.section_name');
        $builder->join('master.roster r', 'mm.m_roster_id = r.id', 'left');
        $builder->join('main_a m', "m.diary_no = mm.diary_no::INTEGER", 'inner',false); // Ensure both columns are casted to the same data type
        $builder->join('dispose d', "mm.diary_no::INTEGER = d.diary_no", 'left',false);
        $builder->join('master.users u', 'd.usercode = u.usercode', 'left',false);
        $builder->join('master.usersection us', 'u.section::bigint = us.id', 'left',false);
        $builder->where('date(mm.date_of_received) >=', $data['from_date']);
        $builder->where('date(mm.date_of_received) <=', $data['to_date']);
        $builder->where('mm.display', 'Y');
        $builder->where('mm.m_roster_id IS NOT NULL', null, false);
        $builder->where('r.display', 'Y');
        $builder->where('courtno', $data['courtno']);
        $builder->where('c_status', 'D');
        $builder->orderBy('mm.date_of_received');
        
         return       $result = $builder->get()->getResult();

   }

   function getFinalDisposalMatters($judge){
        $condition='';
        if($judge!='k'){
            $condition="AND h.coram LIKE '$judge%'";
           }

       $builder = $this->db->query("SELECT  c.Case_NO, Cause_Title, coram, to_char(c.tentative_list_date, 'DD-MM-YYYY') as tentative_list_date,
    Section, DA FROM(  SELECT concat(m.reg_no_display, ' @ ', m.diary_no) as Case_NO, concat(m.pet_name, ' Vs. ', m.res_name) as Cause_Title,
            coalesce(( SELECT string_agg(j.abbreviation, '#') FROM master.judge j WHERE j.is_retired = 'N'  AND j.display = 'Y' AND 
            j.jcode = ANY(string_to_array(h.coram, ',')::int[])), '') as coram, h.next_dt as tentative_list_date,  m.diary_no as Section, m.diary_no as DA
        FROM  main m    INNER JOIN heardt h ON h.diary_no = m.diary_no 
        LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
        WHERE rd.fil_no IS NULL  $condition   AND c_status = 'P'    AND mainhead = 'M'  AND board_type = 'J' AND subhead IN (815, 816)
       AND main_supp_flag = 0  AND h.next_dt is not null AND (m.diary_no::text = m.conn_key OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
        GROUP BY 
            m.diary_no,h.coram,h.next_dt) c");
   return $result = $builder->getResultArray();


    }

    function getFixedDateMatters($judge, $misc_nmd){
        if($misc_nmd == 1){
            $mainhead = "M";
            $nmd_flag = 0;
            $subhead_qry = " AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) ";
        }  else if ($misc_nmd == 2){
            $mainhead = "M";
            $nmd_flag = 1;
            $subhead_qry = " AND h.subhead IN (824,810,803,802,807,804,808,811,812,813,814,815,816) ";
        }else{
            $mainhead = "F";
            $nmd_flag = 1;
            $subhead_qry = "  ";
        }

        $currentDate = '2022-08-01'; // it will be replace as current date

        $currentDate = date('Y-m-d');

        $query = "SELECT next_dt, COUNT(th.diary_no) not_listed, SUM(CASE WHEN (th.listorder = 4 OR th.listorder = 5) THEN 1 ELSE 0 END) fd_not_list FROM ( select next_dt, h.coram, h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no, 
                    case when (h.subhead = 804 or mc.submaster_id = 173 or doccode1 in (40,41,48,49,71,72,118,131,211,309)) then 1 else 0 end as bail
                    ,case when a.advocate_id = 584 then 1 else 0 end as inperson, 
                    case when d.doccode1 IN (7,66,29,56,57,28,102,103,133,226,3,73,99,27,124,2,16) then 1 else 0 end as schm , 
                    position('219' in h.coram) as pos
                    FROM master.sc_working_days wd LEFT JOIN heardt h ON h.next_dt = wd.working_date INNER JOIN main m ON m.diary_no = h.diary_no LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y' AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 and mc.submaster_id != 239 AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 AND mc.submaster_id != 9999 LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' 
                    left join advocate a on a.diary_no = m.diary_no 
                    and a.advocate_id = 584 and a.display = 'Y' WHERE rd.fil_no IS NULL AND mc.diary_no IS NOT NULL AND m.c_status = 'P' AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') AND wd.display ='Y' and wd.is_holiday = 0 and wd.is_nmd = $nmd_flag 
                    and wd.working_date >= '$currentDate' AND next_dt >= '$currentDate'  and h.listorder in (4) AND h.board_type = 'J' AND h.mainhead = '$mainhead' AND h.clno = 0 AND h.brd_slno = 0 and h.main_supp_flag = 0 $subhead_qry and 
                    position('$judge' in h.coram) = 1
                    GROUP BY h.diary_no,d.doccode1,mc.submaster_id, h.listorder, h.diary_no,a.advocate_id ) th GROUP BY th.next_dt order by  th.next_dt";
        $builder = $this->db->query($query);
        return $result = $builder->getResultArray();
    }
    function getcauseListWithOR($data){
        $mainhead = $data['formdata']['mr'];
        $main_suppl=  $data['formdata']['main_suppl'];
        $listing_date=  $data['formdata']['listing_date'];
        $board_type=  $data['formdata']['board_type'];
        $courtno =  $data['formdata']['courtno'];

        if($mainhead == 'l'){
            $mainhead = "";
        }

        if ($main_suppl == "0") {
            $main_suppl = "";
        } else {
            $main_suppl = "AND h.main_supp_flag = '$main_suppl'";
        }

        if ($courtno == "0") {
            $court_no = "";
        } else {
            $court_no = "AND r.courtno = '$courtno'";
        }
        if ($board_type == "0") {
            $board_type = "";
        } else {
            $board_type = "AND h.board_type = '$board_type'";
        }

        if($listing_date != "-1"){
            $listing_date = " AND h.next_dt  = '$listing_date'";
        }else{
            $listing_dt = date('Y-m-d');
            $listing_date =" AND h.next_dt  = '$listing_dt'";
        }

        $query = "SELECT date(m.diary_no_rec_date) diary_no_rec_date,m.casetype_id, p.ent_time, tentative_section(h.diary_no) as dno,r.courtno, u.name, us.section_name, l.purpose, c1.short_description, 
  active_reg_year, active_fil_dt, active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, h.* FROM heardt h INNER JOIN main m ON
      m.diary_no = h.diary_no INNER JOIN master.listing_purpose l ON l.code = h.listorder AND l.display = 'Y' INNER JOIN master.roster r ON r.id = h.roster_id AND r.display = 'Y' $court_no
      LEFT JOIN brdrem br on CAST(br.diary_no as BIGINT)=m.diary_no LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode LEFT JOIN master.users u ON u.usercode = m.dacode and u.display = 'Y' 
      LEFT JOIN master.usersection us ON us.id = u.section LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y' 
WHERE p.next_dt is not null and h.mainhead = '$mainhead' $main_suppl $listing_date $board_type and (h.main_supp_flag = 1 OR h.main_supp_flag = 2) AND h.roster_id > 0 AND m.diary_no IS NOT NULL 
AND m.c_status = 'P' group by h.diary_no, m.diary_no_rec_date,m.casetype_id, p.ent_time,r.courtno,u.name, us.section_name, l.purpose, c1.short_description, active_reg_year, active_fil_dt, 
active_fil_no, m.reg_no_display, m.pet_name, m.res_name, m.pno, m.rno, casetype_id, ref_agency_state_id, diary_no_rec_date,remark, m.diary_no ORDER BY r.courtno, h.brd_slno,case when h.conn_key=h.diary_no
 then '1' else 99 end asc, RIGHT(CAST(m.diary_no AS TEXT), 4) ASC,LEFT(CAST(m.diary_no AS TEXT), -4) ASC
";
        //echo $query;
       $builder = $this->db->query($query);

        $result= $builder->getResultArray();

        return  $result;

    }

    function getAppearanceSearchReport($data){

        // Step 1: Query from e_services database
        $builder = $this->eservicesdb->table('appearing_in_diary ad');
        $builder->select([             
            'ad.aor_code',          
            'ad.diary_no',
            'ad.item_no',
            'ad.court_no',
            'ad.list_date',          
        ]);
        $builder->where('ad.is_submitted', '1');
        $builder->where('ad.is_active', '1');
        $builder->where('ad.list_date', $data['listing_dts']);
        $builder->where('ad.court_no', $data['courtno']);
        $builder->orderBy('ad.diary_no', 'ASC');
        $builder->orderBy('ad.item_no', 'ASC');

        $results = $builder->get()->getResultArray();

        $sql = "
            CREATE TEMP TABLE IF NOT EXISTS temp_appearing_in_diary (
                diary_no bigint,
                list_date date,
                court_no bigint ,
                item_no bigint,
                aor_code bigint,
                appearing_for VARCHAR(10),
                priority bigint,
                advocate_type VARCHAR(45),
                advocate_title VARCHAR(60),
                advocate_name VARCHAR(100),
                is_submitted boolean  NULL DEFAULT false,
                is_active boolean  NULL DEFAULT true
            )";
        $this->db->query($sql);

        // Step 2: Insert results into temp_appearing_in_diary
        if (!empty($results)) {
            $tempBuilder = $this->db->table('temp_appearing_in_diary');
            $tempBuilder->insertBatch($results);
        }

        // Step 3: Query from temp_appearing_in_diary with necessary joins
        $builder = $this->db->table('temp_appearing_in_diary ad');
        $builder->select([
            'DISTINCT ON (ad.diary_no)   a.advocate_title',
            'a.advocate_title',
            'a.advocate_name',
            'a.advocate_type',
            'm.pno',
            'ad.aor_code',
            'b.title',
            'b.name',
            'ad.diary_no',
            'ad.item_no',
            'ad.court_no',
            'ad.list_date',
            'm.reg_no_display',
            'm.pet_name',
            'm.res_name',
        ]);
        //$builder->distinct(true); // Apply DISTINCT for unique rows
        $builder->join('public.main m', 'm.diary_no = ad.diary_no', 'inner');
        $builder->join('master.bar b', 'CAST(b.aor_code as BIGINT) = CAST(ad.aor_code as BIGINT)', 'left');
        $builder->join('temp_appearing_in_diary a', "b.aor_code = CAST(a.aor_code as BIGINT) AND a.appearing_for = 'P'", 'left');
        $builder->orderBy('ad.diary_no', 'ASC');
        $builder->orderBy('ad.item_no', 'ASC');

        $results = $builder->get()->getResultArray();

         
        $builder2 = $this->db->table('temp_appearing_in_diary ad');

        $builder2->select([
            'DISTINCT ON (ad.diary_no)   a.advocate_title',            
            'a.advocate_name',
            'a.advocate_type',
            'm.pno',
            'ad.aor_code',
            'b.title',
            'b.name',
            'ad.diary_no',
            'ad.item_no',
            'ad.court_no',
            'ad.list_date',
            'm.reg_no_display',
            'm.pet_name',
            'm.res_name',
        ]);

        $builder2->join('public.main_a m', 'm.diary_no = ad.diary_no', 'inner');
        $builder2->join('master.bar b', 'b.aor_code = ad.aor_code', 'left');
        $builder2->join('temp_appearing_in_diary a', "b.aor_code = a.aor_code AND a.appearing_for = 'P'", 'left');

        $builder2->where('ad.is_submitted', '1');
        $builder2->where('ad.is_active', '1');

        // Use prepared statements with data binding for security
        $builder2->where('ad.list_date', $data['listing_dts']);
        $builder2->where('ad.court_no', $data['courtno']);

        $builder2->orderBy('ad.diary_no', 'ASC');
        $builder2->orderBy('ad.item_no', 'ASC');

        $results2 = $builder2->get()->getResultArray();

       /* $builder = $this->db->query("SELECT DISTINCT ON (ad.diary_no)   a.advocate_title,a.advocate_name,a.advocate_type,m.pno, ad.aor_code,b.title, b.name,ad.diary_no,ad.item_no,ad.court_no,ad.list_date,m.reg_no_display,m.pet_name,m.res_name
        FROM e_services.appearing_in_diary ad 
        INNER JOIN public.main m ON   m.diary_no = ad.diary_no 
        LEFT JOIN master.bar b ON    b.aor_code = ad.aor_code 
        LEFT JOIN e_services.appearing_in_diary a ON    b.aor_code = a.aor_code and a.appearing_for = 'P'
        WHERE  ad.is_submitted = '1' AND    ad.is_active = '1' AND      ad.list_date =  '".$data['listing_dts']."' AND    ad.court_no = ".$data['courtno']." ORDER BY ad.diary_no,ad.item_no");
        $builder= $builder->getResultArray(); 
        $builder2 = $this->db->query("SELECT DISTINCT ON (ad.diary_no)   a.advocate_title,a.advocate_name,a.advocate_type,m.pno, ad.aor_code,b.title, b.name,ad.diary_no,ad.item_no,ad.court_no,ad.list_date,m.reg_no_display,m.pet_name,m.res_name
        FROM e_services.appearing_in_diary ad 
        INNER JOIN public.main_a m ON   m.diary_no = ad.diary_no 
        LEFT JOIN master.bar b ON    b.aor_code = ad.aor_code 
        LEFT JOIN e_services.appearing_in_diary a ON    b.aor_code = a.aor_code and a.appearing_for = 'P'
        WHERE  ad.is_submitted = '1' AND    ad.is_active = '1' AND      ad.list_date =  '".$data['listing_dts']."' AND    ad.court_no = ".$data['courtno']." ORDER BY ad.diary_no,ad.item_no");
        $builder2= $builder2->getResultArray(); */

        return  $result = array_merge($results, $results2);
    }

    function getvernacularJudgmentsReport($data){
        $builder = $this->db->query("SELECT o.diary_no, string_agg(DISTINCT trim(s.name), ',') AS stateName, string_agg(DISTINCT rac.agency_name, ',') AS highCourt, 
        concat(b.reg_no_display, ' @ ', concat(left((cast(b.diary_no as text)),-4), '/', right((cast(b.diary_no as text)),4))) AS caseNo,
       concat(b.pet_name, ' Vs. ', b.res_name) AS causeTitle, TO_CHAR(o.order_date, 'DD-MM-YYYY') AS judgmentDate, e.name AS uploadedBy,
       TO_CHAR(o.entry_date, 'DD-MM-YYYY') AS uploadedOn,  o.pdf_name AS filePath,vl.name AS language FROM  vernacular_orders_judgments o 
       INNER JOIN master.vernacular_languages vl ON    o.ref_vernacular_languages_id = vl.id    AND vl.display = 'Y'
       LEFT JOIN main_a b ON  o.diary_no = b.diary_no 
       LEFT JOIN lowerct_a lct ON   o.diary_no = lct.diary_no   AND is_order_challenged = 'Y' 
       LEFT JOIN master.ref_agency_code rac ON   lct.l_dist = rac.id    AND rac.is_deleted = 'f' 
       LEFT JOIN master.state s ON  lct.l_state = s.id_no  AND s.display = 'Y' 
       LEFT JOIN master.users e ON  e.usercode = o.user_code 
       LEFT JOIN master.usertype u1 ON   e.usertype = u1.id 
       WHERE  date(o.entry_date) >= '".$data['from_date']."' AND date(o.entry_date) <= '".$data['to_date']."'   AND o.display = 'Y' 
       GROUP by b.reg_no_display,b.diary_no,
       o.order_date,e.name,o.entry_date,o.pdf_name, o.diary_no, vl.name,b.pet_name,b.res_name 
       
       ORDER BY  stateName ASC, highCourt ASC, o.diary_no, uploadedOn DESC;");
        return $result = $builder->getResultArray();
    }


    function get_judges_list_current(){
        $usertype = session()->get('login')['usertype'];
        if($usertype == 3 OR $usertype == 4 OR $usertype == 2 OR $usertype == 1) {
           $query = "SELECT jname, jcode FROM master.judge j WHERE j.display = 'Y' AND j.is_retired !='Y' AND jtype = 'J'
            ORDER BY j.judge_seniority LIMIT 17";
        }  else {
            $ucode = session()->get('login')['usercode'];
            $query = "select a.* from (SELECT jname, jcode FROM master.judge j WHERE j.display = 'Y' 
AND j.is_retired !='Y' AND jtype = 'J' ORDER BY j.judge_seniority LIMIT 17) a
left join master.users u on u.jcode = a.jcode where u.usercode = $ucode";
        }
        $builder = $this->db->query($query);
        return $result = $builder->getResultArray();
    }

    function judge_coram_cases_detail_get_nsh($nmd_flag,$mainhead,$jcd,$sub_list_dt,$msc_nmd_q,$subhead_qry){
        $query = "SELECT t.*, l.purpose FROM (SELECT rd.fil_no2, d.doccode1, m.pet_name, m.res_name, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder,
                  m.diary_no_rec_date, h.*, CONCAT(mc.submaster_id,'') cat1, case when (h.subhead = 804 or mc.submaster_id = 173 or doccode1 in (40,41,48,49,71,72,118,131,211,309)) then 1 else 0 end as bail
                  ,case when a.advocate_id = 584 then 1 else 0 end as inperson, case when d.doccode1 IN (7,66,29,56,57,28,102,103,133,226,3,73,99,27,124,2,16) then 1 else 0 end as schm FROM 
                  master.sc_working_days wd LEFT JOIN heardt h ON h.next_dt = wd.working_date INNER JOIN main m ON m.diary_no = h.diary_no LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND 
                  d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) LEFT JOIN 
                  mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y' AND mc.submaster_id != 911 AND mc.submaster_id != 912 AND mc.submaster_id != 914 
                  and mc.submaster_id != 239 AND mc.submaster_id != 240 AND mc.submaster_id != 241 AND mc.submaster_id != 242 AND mc.submaster_id != 243 AND mc.submaster_id != 331 
                  AND mc.submaster_id != 9999 LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' left join advocate a on a.diary_no = m.diary_no and a.advocate_id = 584 and 
                  a.display = 'Y' WHERE rd.fil_no IS NULL AND mc.diary_no IS NOT NULL AND m.c_status = 'P' AND (m.diary_no::text = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR 
                  m.conn_key = '0') $msc_nmd_q AND wd.display ='Y' and wd.is_holiday = 0 and wd.is_nmd = $nmd_flag $sub_list_dt  $subhead_qry AND
                  h.board_type = 'J' AND h.mainhead = '$mainhead' AND h.clno = 0 AND h.brd_slno = 0 and h.main_supp_flag = 0 and h.listorder in (4) and  position('$jcd' in h.coram) = 1 
                  GROUP BY h.diary_no,rd.fil_no2, d.doccode1, m.pet_name, m.res_name, m.active_fil_no, m.active_reg_year, m.reg_no_display, m.active_casetype_id, m.fil_no, m.fil_dt, m.lastorder,
                  m.diary_no_rec_date,mc.submaster_id,a.advocate_id ) t left join master.listing_purpose l on l.code = t.listorder ORDER BY RIGHT(CAST(diary_no AS TEXT), 4),LEFT(CAST(diary_no AS TEXT), -4) ";
        $builder = $this->db->query($query);
        return $result = $builder->getResultArray();
    }


   /* function getListingDates(){
        $query="SELECT c.next_dt FROM heardt c WHERE mainhead = 'M' AND c.next_dt >= (current_date - 7) AND (c.main_supp_flag = '1' OR c.main_supp_flag = '2') GROUP BY next_dt";
        $builder = $this->db->query($query);
        return $result = $builder->getResultArray();
    } */

    public function getListingDates()
    {
        // Use Query Builder to build the query
        $builder = $this->db->table('heardt c');

        // Select the column and apply GROUP BY
        $builder->select('c.next_dt');
        $builder->where('c.mainhead', 'M');
        $builder->where('c.next_dt >=', 'current_date - interval \'7 days\'', false); // Use raw PostgreSQL date arithmetic
        $builder->groupStart() // Group conditions for OR logic
                ->where('c.main_supp_flag', '1')
                ->orWhere('c.main_supp_flag', '2')
                ->groupEnd();
        $builder->groupBy('c.next_dt');

        // Execute the query and get results
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function ropNotUploaded($causeListFromDate, $causeListToDate, $pJudge)
{
    
    $sql = "SELECT DISTINCT listed.* FROM (
                SELECT DISTINCT
                    rj.roster_id,
                    m.diary_no,
                    hd.board_type,
                    hd.next_dt AS listing_date,
                    m.pet_name AS petitioner_name,
                    m.res_name AS respondent_name,
                    r.courtno AS court_number,
                    hd.brd_slno AS item_number,
                    CASE
                        WHEN COALESCE(hd.listed_ia, '') = '' THEN m.reg_no_display
                        ELSE 'IA ' || hd.listed_ia || ' in ' || m.reg_no_display
                    END AS registration_number_desc,
                    m.pno,
                    m.rno
                FROM heardt hd
                INNER JOIN main m ON hd.diary_no = m.diary_no
                INNER JOIN master.roster_judge rj ON hd.roster_id = rj.roster_id
                INNER JOIN master.roster r ON rj.roster_id = r.id
                INNER JOIN cl_printed cp ON hd.roster_id = cp.roster_id
                    AND hd.next_dt = cp.next_dt
                    AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                    AND hd.clno = cp.part
                LEFT JOIN case_remarks_multiple crm 
                    ON hd.diary_no = CAST(crm.diary_no as BIGINT) 
                    AND hd.next_dt = crm.cl_date 
                    AND crm.r_head != 19
                WHERE cp.display = 'Y'
                    AND hd.main_supp_flag != 0
                    AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no)
                    AND hd.brd_slno IS NOT NULL
                    AND hd.brd_slno > 0
                    AND hd.next_dt BETWEEN CAST(? AS DATE) AND CAST(? AS DATE)
                    AND rj.judge_id = ?
            UNION
            SELECT DISTINCT
                rj.roster_id,
                m.diary_no,
                hd.board_type,
                hd.next_dt AS listing_date,
                m.pet_name AS petitioner_name,
                m.res_name AS respondent_name,
                r.courtno AS court_number,
                hd.brd_slno AS item_number,
                CASE
                    WHEN COALESCE(hd.listed_ia, '') = '' THEN m.reg_no_display
                    ELSE 'IA ' || hd.listed_ia || ' in ' || m.reg_no_display
                END AS registration_number_desc,
                m.pno,
                m.rno
            FROM last_heardt hd
            INNER JOIN main m ON hd.diary_no = m.diary_no
            INNER JOIN master.roster_judge rj ON hd.roster_id = rj.roster_id
            INNER JOIN master.roster r ON rj.roster_id = r.id
            INNER JOIN cl_printed cp ON hd.roster_id = cp.roster_id
                AND hd.next_dt = cp.next_dt
                AND hd.brd_slno BETWEEN cp.from_brd_no AND cp.to_brd_no
                AND hd.clno = cp.part
            LEFT JOIN case_remarks_multiple crm 
                ON hd.diary_no = CAST(crm.diary_no as BIGINT) 
                AND hd.next_dt = crm.cl_date 
                AND crm.r_head != 19
            WHERE cp.display = 'Y'
                AND hd.main_supp_flag != 0
                AND (hd.conn_key IS NULL OR hd.conn_key = 0 OR hd.conn_key = hd.diary_no)
                AND hd.brd_slno IS NOT NULL
                AND hd.brd_slno > 0
                AND COALESCE(hd.bench_flag, '') = ''
                AND hd.next_dt BETWEEN CAST(? AS DATE) AND CAST(? AS DATE)
                AND rj.judge_id = ?
            ) listed
            LEFT JOIN ordernet o 
                ON listed.diary_no = o.diary_no 
                AND listed.listing_date = o.orderdate 
                AND o.display = 'Y' 
                AND o.type = 'O'
            WHERE o.diary_no IS NULL
            ORDER BY listing_date, board_type, court_number, item_number";

    $query = $this->db->query($sql, array($causeListFromDate, $causeListToDate, $pJudge, $causeListFromDate, $causeListToDate, $pJudge));
    
    return $query->getResultArray();
}

function getUsercode($order_date,$roster_id,$diary_no,$court_no,$item_no)
	{
		$sql_generated_by="select generated_by from proceedings where roster_id=$roster_id and order_date='".$order_date."' and display='Y' and court_number=$court_no and item_number=$item_no and diary_no=$diary_no";
		$query_generated_by=$this->db->query($sql_generated_by);
		$result_generated_by= $query_generated_by->getRowArray();
		if(!empty($result_generated_by)) {
			$generated_by = $result_generated_by['generated_by'];			 
			$generated_by =trim($generated_by ,",");
			$sql = "select STRING_AGG(name, ', ') AS user_name from master.users where usercode in($generated_by)";
			 
			$query = $this->db->query($sql);
			return $query->getRowArray();
		}
		else
			return false;
	}


    public function getRosterDetails($dtd, $judge_code = '')
{
    $db = \Config\Database::connect();
    
    $sql = "SELECT r.id, 
                STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority) AS jcd,
                STRING_AGG(j.jname, ', ' ORDER BY j.judge_seniority) AS jnm, 
                j.first_name, 
                j.sur_name, 
                title,
                r.courtno, 
                rb.bench_no, 
                mb.abbr, 
                mb.board_type_mb, 
                r.tot_cases, 
                r.frm_time, 
                r.session 
            FROM master.roster r 
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            LEFT JOIN cl_printed cp ON cp.next_dt = ? AND cp.roster_id = r.id AND cp.display = 'Y'
            WHERE cp.next_dt IS NOT NULL 
                AND j.is_retired != 'Y' 
                AND j.display = 'Y' 
                AND rj.display = 'Y' 
                AND rb.display = 'Y' 
                AND mb.display = 'Y' 
                AND r.display = 'Y' 
                $judge_code
            GROUP BY r.id, j.first_name, j.sur_name, title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, 
                     r.tot_cases, r.frm_time, r.session
            ORDER BY r.id";

    $query = $db->query($sql, [$dtd]);  // Bind the date parameter safely

    return $query->getRowArray();  // Fetch results as an array
}




public function getRosterJudgeDetails($stg, $t_cn = '')
{
    $db = \Config\Database::connect();

    $sql = "SELECT * FROM (
                SELECT DISTINCT 
                    rj.roster_id, 
                    mb.board_type_mb,
                    r.courtno,  -- Required for ORDER BY
                    rj.judge_id -- Required for ORDER BY
                FROM master.roster_judge rj
                JOIN master.roster r 
                    ON rj.roster_id = r.id
                JOIN master.roster_bench rb 
                    ON rb.id = r.bench_id AND rb.display = 'Y'
                JOIN master.master_bench mb 
                    ON mb.id = rb.bench_id AND mb.display = 'Y'
                WHERE r.m_f = '$stg'  $t_cn
                    AND rj.display = 'Y'
                    AND r.display = 'Y'
            ) subquery
            ORDER BY 
                CASE WHEN courtno = 0 THEN 9999 ELSE courtno END,
                CASE 
                    WHEN board_type_mb = 'J' THEN 1
                    WHEN board_type_mb = 'S' THEN 2
                    WHEN board_type_mb = 'C' THEN 3
                    WHEN board_type_mb = 'CC' THEN 4
                    WHEN board_type_mb = 'R' THEN 5
                END,
                judge_id";

    $query = $db->query($sql);  // Bind the parameter safely

    return $query->getResultArray();  // Fetch results as an array
}




    public function getCaseDetails($tdt1, $mf, $result, $whereStatus = '')
    {
        $db = \Config\Database::connect();

        $sql = "SELECT 
                    SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS case_no,
                SUBSTRING(m.diary_no::TEXT, -4) AS year, 
                    m.diary_no,
                    m.reg_no_display,    
                    m.conn_key,   
                    h.mainhead,
                    h.judges,
                    h.board_type,
                    h.next_dt,   
                    h.clno,
                    h.brd_slno,
                    m.pet_name,
                    m.res_name,
                    m.c_status,
                    CASE
                    WHEN cl.next_dt IS NULL THEN 0   
                    ELSE 1
                    END AS brd_prnt,
                    h.roster_id,    
                    m.casetype_id,
                    m.case_status_id,
                    c.short_description,
                    h.list_status
                FROM (
                    SELECT 
                        t1.diary_no,
                        t1.next_dt,
                        t1.judges,
                        t1.roster_id,  
                        t1.mainhead,
                        t1.board_type,
                        t1.clno,
                        t1.brd_slno,  
                        t1.main_supp_flag,
                        'Heardt' AS list_status
                    FROM heardt t1 
                    WHERE 
                        t1.next_dt = ? 
                        AND t1.mainhead = ? 
                        AND t1.roster_id::TEXT = ANY(string_to_array(?, ','))
                        AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2)
                    UNION
                    SELECT 
                        t2.diary_no,
                        t2.next_dt,
                        t2.judges,
                        t2.roster_id,  
                        t2.mainhead,
                        t2.board_type,
                        t2.clno,
                        t2.brd_slno,  
                        t2.main_supp_flag,
                        'Last_Heardt' AS list_status  
                    FROM last_heardt t2 
                    WHERE 
                        t2.next_dt = ? 
                        AND t2.mainhead = ? 
                        AND t2.roster_id::TEXT = ANY(string_to_array(?, ','))
                        AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                        AND (t2.bench_flag = '' OR t2.bench_flag IS NULL)
                    UNION  
                    SELECT 
                        t3.diary_no,
                        t3.cl_date AS next_dt,
                        'Judges' AS judges,
                        t3.roster_id,  
                        t3.mf AS mainhead,
                        'Board_Type' AS board_type,  
                        t3.part AS clno,
                        t3.clno AS brd_slno, 
                        NULL AS main_supp_flag,
                        'DELETED' AS list_status 
                    FROM drop_note t3 
                    WHERE 
                        t3.cl_date = ? 
                        AND t3.mf = ? 
                        AND t3.roster_id::TEXT = ANY(string_to_array(?, ','))
                ) h 
                INNER JOIN main m ON h.diary_no = m.diary_no   
                LEFT JOIN cl_printed cl 
                    ON cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                WHERE cl.next_dt IS NOT NULL $whereStatus
                GROUP BY h.diary_no, m.diary_no, c.short_description, 
                h.mainhead,
                h.judges,
                h.board_type,
                h.next_dt,
                h.clno,
                h.brd_slno,
                m.pet_name,
                m.res_name,
                m.c_status,
                
                list_status,
                cl.next_dt,
                h.roster_id
                ORDER BY 
                    h.roster_id,
                    cl.next_dt,
                    h.brd_slno,
                   CASE WHEN m.conn_key::TEXT = m.diary_no::TEXT THEN '0000-00-00' ELSE '99' END ASC,
                     CAST(SUBSTRING(m.diary_no::TEXT, -4) AS INTEGER) ASC, 
                    CAST(SUBSTRING(m.diary_no::TEXT, 1, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC";

        $query = $db->query($sql, [$tdt1, $mf, $result, $tdt1, $mf, $result, $tdt1, $mf, $result]);

        return $query->getResultArray();
    }



    public function getOrderDates($condition,$rop_text_web)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT DISTINCT dated AS orderdate FROM (  
                    SELECT ord.diary_no,                          
                        SUBSTRING(ord.diary_no::TEXT, 1, LENGTH(ord.diary_no::TEXT) - 4) AS d_no,
                        SUBSTRING(ord.diary_no::TEXT, -4) AS d_year, 
                        ord.office_repot_name AS jm,
                        TO_CHAR(ord.order_dt::DATE, 'YYYY-MM-DD') AS dated,
                        'Office Report Details' AS jo
                    FROM office_report_details ord
                    WHERE ord.display='Y' AND ord.web_status=1 AND ord.diary_no IN ($condition)
                    
                    UNION

                    SELECT o.diary_no,                          
                        SUBSTRING(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                        SUBSTRING(o.diary_no::TEXT, -4) AS d_year, 
                        o.jm AS jm,
                        TO_CHAR(o.dated::DATE, 'YYYY-MM-DD') AS dated,
                        CASE 
                            WHEN o.jt='rop' THEN 'ROP' 
                            WHEN o.jt='judgment' THEN 'Judgement'  
                            WHEN o.jt='or' THEN 'Office Report' 
                        END AS jo
                    FROM tempo o
                    WHERE o.diary_no IN ($condition)

                    UNION

                    SELECT o.diary_no, 
                        SUBSTRING(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                        SUBSTRING(o.diary_no::TEXT, -4) AS d_year, 
                        o.pdfname AS jm,
                        TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated, 
                        CASE 
                            WHEN o.type='O' THEN 'ROP' 
                            WHEN o.type='J' THEN 'Judgement' 
                        END AS jo
                    FROM ordernet o
                    WHERE o.diary_no IN ($condition)

                    UNION

                    SELECT o.dn AS diary_no, 
                       SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                        SUBSTRING(o.dn::TEXT, -4) AS d_year, 
                        CONCAT('ropor/rop/all/', o.pno, '.pdf') AS jm,
                        TO_CHAR(o.orderDate::DATE, 'YYYY-MM-DD') AS dated,
                        'ROP' AS jo
                    FROM $rop_text_web.old_rop o
                    WHERE o.dn IN ($condition)

                    UNION

                    SELECT o.dn AS diary_no, 
                        SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                        SUBSTRING(o.dn::TEXT, -4) AS d_year, 
                        CONCAT('judis/', o.filename, '.pdf') AS jm,
                        TO_CHAR(o.juddate::DATE, 'YYYY-MM-DD') AS dated,
                        'Judgment' AS jo
                    FROM scordermain o
                    WHERE o.dn IN ($condition)

                    UNION

                    SELECT o.dn AS diary_no, 
                        SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                        SUBSTRING(o.dn::TEXT, -4) AS d_year, 
                        CONCAT('bosir/orderpdf/', o.pno, '.pdf') AS jm,
                        TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                        'ROP' AS jo
                    FROM $rop_text_web.ordertext o
                    WHERE o.dn IN ($condition) AND o.display='Y'

                    UNION

                    SELECT o.dn AS diary_no, 
                        SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                        SUBSTRING(o.dn::TEXT, -4) AS d_year,  
                        CONCAT('bosir/orderpdfold/', o.pno, '.pdf') AS jm,
                        TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                        'ROP' AS jo
                    FROM $rop_text_web.oldordtext o
                    WHERE o.dn IN ($condition)
                ) tbl1
                ORDER BY tbl1.dated DESC";

        $query = $db->query($sql);
        
        return $query->getResultArray();
    }


    public function getRopDetails($condition, $orderdate,$rop_text_web)
{
    $db = \Config\Database::connect();

    $sql = "SELECT jm AS rop_path, jo, dated AS orderdate FROM (  
                SELECT o.diary_no, 
                    SUBSTRING(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                    SUBSTRING(o.diary_no::TEXT, -4) AS d_year,  
                    o.jm, 
                    TO_CHAR(o.dated::DATE, 'YYYY-MM-DD') AS dated, 
                    CASE 
                        WHEN o.jt = 'rop' THEN 'ROP' 
                        WHEN o.jt = 'judgment' THEN 'Judgement'  
                        WHEN o.jt = 'or' THEN 'Office Report' 
                    END AS jo
                FROM tempo o
                WHERE o.diary_no IN ($condition) 
                AND o.dated::DATE = ?

                UNION

                SELECT o.diary_no, 
                    SUBSTRING(o.diary_no::TEXT, 1, LENGTH(o.diary_no::TEXT) - 4) AS d_no,
                    SUBSTRING(o.diary_no::TEXT, -4) AS d_year,  
                    o.pdfname AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated, 
                    CASE 
                        WHEN o.type = 'O' THEN 'ROP' 
                        WHEN o.type = 'J' THEN 'Judgement' 
                    END AS jo
                FROM ordernet o
                WHERE o.diary_no IN ($condition) 
                AND o.orderdate::DATE = ?

                UNION

                SELECT o.dn AS diary_no, 
                    SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTRING(o.dn::TEXT, -4) AS d_year, 
                    CONCAT('ropor/rop/all/', o.pno, '.pdf') AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                    'ROP' AS jo
                FROM $rop_text_web.old_rop o
                WHERE o.dn IN ($condition) 
                AND o.orderdate::DATE = ?

                UNION

                SELECT o.dn AS diary_no, 
                    SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTRING(o.dn::TEXT, -4) AS d_year, 
                    CONCAT('judis/', o.filename, '.pdf') AS jm,
                    TO_CHAR(o.juddate::DATE, 'YYYY-MM-DD') AS dated,
                    'Judgment' AS jo
                FROM scordermain o
                WHERE o.dn IN ($condition) 
                AND o.juddate = ?

                UNION

                SELECT o.dn AS diary_no, 
                    SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTRING(o.dn::TEXT, -4) AS d_year,
                    CONCAT('bosir/orderpdf/', o.pno, '.pdf') AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                    'ROP' AS jo
                FROM $rop_text_web.ordertext o
                WHERE o.dn IN ($condition) 
                AND o.orderdate::DATE = ?
                AND o.display = 'Y'

                UNION

                SELECT o.dn AS diary_no, 
                    SUBSTRING(o.dn::TEXT, 1, LENGTH(o.dn::TEXT) - 4) AS d_no,
                    SUBSTRING(o.dn::TEXT, -4) AS d_year,
                    CONCAT('bosir/orderpdfold/', o.pno, '.pdf') AS jm,
                    TO_CHAR(o.orderdate::DATE, 'YYYY-MM-DD') AS dated,
                    'ROP' AS jo
                FROM $rop_text_web.oldordtext o
                WHERE o.dn IN ($condition) 
                AND o.orderdate::DATE = ?
            ) tbl1";

    // Bind parameters securely
    $query = $db->query($sql, [$orderdate, $orderdate, $orderdate, $orderdate, $orderdate, $orderdate]);
    //echo $db->getLastQuery();
    //die;
    return $query->getResultArray();
}

public function getRegisteredJudges($dtd, $judge_code = '')
{
    $db = \Config\Database::connect();

    $builder = $db->table('master.roster AS t1')
        ->select("t1.courtno, (t3.jname || ' ' || t3.first_name || ' ' || t3.sur_name) AS jname", false)  // Using PostgreSQL string concatenation (||)
        ->join('master.roster_judge AS t2', 't1.id = t2.roster_id', 'inner')
        ->join('master.judge AS t3', 't3.jcode = t2.judge_id', 'inner')
        ->join('cl_printed AS cp', "cp.next_dt = '{$dtd}' AND cp.roster_id = t1.id AND cp.display = 'Y'", 'left')
        ->where('cp.next_dt IS NOT NULL', null, false)
        ->where("'{$dtd}' >= t1.from_date", null, false)  // Ensure date comparison in PostgreSQL
        ->where('t1.to_date IS NULL', null, false)
        ->where('t3.jtype', 'R')
        ->where('t3.is_retired', 'N')
        ->where('t1.display', 'Y')
        ->where('t2.display', 'Y');

    if (!empty($judge_code)) {
        $builder->where($judge_code);
    }

    $builder->orderBy('t3.jcode');

    $query = $builder->get();
    
    return $query->getResultArray();
}


public function getConnectedList($DNumber_main)
{
    $db = \Config\Database::connect();

    $builder = $db->table('main')
        ->select("STRING_AGG(diary_no::TEXT, ',') AS conn_list", false) // Convert BIGINT to TEXT
        ->where('conn_key', $DNumber_main);

    $query = $builder->get();
    
    return $query->getResultArray(); // Fetch single row as array
}








}
