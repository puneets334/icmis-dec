<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class Office_report_model extends Model
{

    public function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    public function getSections()
    {
        $builder = $this->db->table('master.usersection');
        $builder->where(['display' => 'Y', 'isda' => 'Y']);
        $builder->orderBy('section_name');
        $sections = $builder->get();
        return $sections->getResultArray();
    }

    public function getCasetype()
    {
        $builder = $this->db->table('master.casetype');
        $builder->distinct();
        $builder->select('nature');
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getOfficeReportMaster($nature)
    {
        $builder = $this->db->table('master.office_report_master');
        $builder->select('id, r_nature');
        $builder->where('case_nature', $nature);
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_chk_status($dairy_no)
    {
        $builder = $this->db->table('main');
        $builder->select('fil_no, c_status, pno, rno, dacode');
        $builder->where('diary_no', $dairy_no);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_user_section($ucode)
    {
        $builder = $this->db->table('master.users');
        $builder->select('section');
        $builder->where('usercode', $ucode);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_chk_pnt($diary_no)
    {
        $builder = $this->db->table('heardt a');
        $builder->select('a.next_dt');
        $builder->where('a.diary_no', $diary_no);
        $builder->where('a.next_dt >= CURDATE()');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_office_report($diary_no, $r_chk_pnt)
    {
        $builder = $this->db->table('office_report_details');
        $builder->where('diary_no', $diary_no);
        $builder->where('order_dt', $r_chk_pnt);
        $builder->where('display', 'Y');
        $builder->select('COUNT(id) as total');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_connected_cases($diary_no)
    {
        $builder = $this->db->table('main');
        $builder->select('conn_key');
        $builder->where('diary_no', $diary_no);
        $builder->where('conn_key IS NOT NULL');
        $builder->where('conn_key !=', '');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_cnt_cases($r_connected_cases, $diary_no)
    {
        $builder = $this->db->table('conct');
        $builder->select('diary_no');
        $builder->where('conn_key', $r_connected_cases);
        $builder->where('diary_no !=', $diary_no);
        $builder->orderBy("SUBSTR(diary_no, LENGTH(diary_no) - 3, 4)", 'ASC');
        $builder->orderBy("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4)", 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getFilDet($dairy_no)
    {
        return $this->db->table('main a')
            ->select('a.active_casetype_id as casetype_id, a.active_fil_no as fil_no, b.short_description, a.casename, a.fil_dt, a.pet_name, a.res_name, a.pet_adv_id, a.lastorder')
            ->join('master.casetype b', 'a.casetype_id = b.casecode')
            ->where('a.diary_no', $dairy_no)
            ->where('a.display', 'Y')
            ->get()
            ->getRowArray();
    }

    public function getOfficeReport($diary_no, $order_dt)
    {
        $builder = $this->db->table('office_report_details');
        $builder->select('office_repot_name, office_report_id, summary');
        $builder->where('diary_no', $diary_no);
        $builder->where('order_dt', $order_dt);
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getOfficeReportDetails($dairy_no, $r_chk_pnt)
    {
        $builder = $this->db->table('office_report_details');
        $builder->select('batch');
        $builder->where('diary_no', $dairy_no);
        $builder->where('order_dt', $r_chk_pnt);
        $builder->where('display', 'Y');
        $batch_query = $builder->get();
        return $batch_query->getRow()->batch ?? '';
    }

    public function getRchkAlCon($diary_no, $r_chk_pnt, $res_max_o_r, $r_get_batch)
    {
        $builder = $this->db->table('office_report_details');
        $builder->select('diary_no');
        $builder->where('diary_no', $diary_no);
        $builder->where('order_dt', $r_chk_pnt);
        $builder->where('display', 'Y');
        $builder->where('office_report_id', $res_max_o_r);
        $builder->where('batch', $r_get_batch);
        $chk_al_con_query = $builder->get();
        return $chk_al_con_query->getRow()->diary_no ?? '';
    }

    public function getRegNoDisplay($diary_no)
    {
        $builder = $this->db->table('main');
        $builder->select('reg_no_display');
        $builder->where('diary_no', $diary_no);
        $case_query = $builder->get();
        return $case_query->getRow();
    }

    public function getDocDetails($diary_no)
    {
        $builder = $this->db->table('docdetails a');
        $builder->select('b.docdesc, a.docnum, a.docyear, a.verified, a.ent_dt, a.other1');
        $builder->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1');
        $builder->where('a.display', 'Y');
        $builder->where('b.display', 'Y');
        $builder->where('a.diary_no', $diary_no);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function sq_u()
    {
        $sq_u = "SELECT STRING_AGG(u2.usercode::TEXT, ',') AS allda
                FROM master.users u
                LEFT JOIN master.users u2 ON u2.section = u.section
                WHERE u.display = 'Y' AND u.usercode = '1'
                GROUP BY u.section;";
        $query = $this->db->query($sq_u);
        return $result = $query->getRowArray();
    }

    public function get_user_emid($ucode)
    {
        $builder = $this->db->table('master.users');
        $builder->select('empid');
        $builder->where('usercode', $ucode);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCauseListSectionBulkOrUploadData($court_no, $cl_print_jo, $cl_print_jo2, $mainhead, $main_suppl, $sec_id2, $sec_id, $mdacode, $lp, $board_type, $section, $orderby, $list_dt)
    {
        $sql = "SELECT 
                    tentative_section(h.diary_no) as dno, 
                    r.courtno, 
                    u.name, 
                    us.section_name, 
                    l.purpose, 
                    c1.short_description, 
                    EXTRACT(YEAR FROM m.active_fil_dt) AS fyr,
                    active_reg_year, 
                    active_fil_dt, 
                    active_fil_no, 
                    m.reg_no_display, 
                    m.pet_name, 
                    m.res_name, 
                    m.pno, 
                    m.rno, 
                    casetype_id, 
                    ref_agency_state_id, 
                    diary_no_rec_date, 
                    remark, 
                    h.* 
                    FROM 
                    heardt h 
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    INNER JOIN master.listing_purpose l ON l.code = h.listorder 
                    AND l.display = 'Y' 
                    INNER JOIN master.roster r ON r.id = h.roster_id  $court_no
                    LEFT JOIN brdrem br on br.diary_no::text = m.diary_no::text 
                    LEFT JOIN master.casetype c1 ON m.active_casetype_id = c1.casecode $cl_print_jo
                    LEFT JOIN master.users u ON u.usercode = m.dacode 
                    and (u.display = 'Y' || u.display is null) 
                    LEFT JOIN master.usersection us ON us.id = u.section 
                    $sec_id
                    WHERE 
                    $cl_print_jo2 
                    h.mainhead = 'S' 
                    '$mainhead' $main_suppl $sec_id2
                    and h.next_dt = '" . $list_dt . "' 
                            $mdacode 
                            $lp $board_type 
                    and (
                        h.main_supp_flag = 1 
                        OR h.main_supp_flag = 2
                    ) 
                    AND h.roster_id > 0 
                    AND m.diary_no IS NOT NULL 
                    AND m.c_status = 'P'  $section
                    GROUP BY 
                    h.diary_no, 
                    r.courtno, 
                    u.name, 
                    us.section_name, 
                    l.purpose, 
                    c1.short_description, 
                    m.active_fil_dt, 
                    active_reg_year, 
                    active_fil_dt, 
                    active_fil_no, 
                    m.reg_no_display, 
                    m.pet_name, 
                    m.res_name, 
                    m.pno, 
                    m.rno, 
                    casetype_id, 
                    ref_agency_state_id, 
                    diary_no_rec_date, 
                    remark, 
                    h.brd_slno, 
                    h.conn_key 
                    ORDER BY 
                    $orderby
                    r.courtno, 
                    r.courtno, 
                    CASE WHEN us.section_name IS NULL THEN 9999 ELSE 0 END ASC, 
                    us.section_name, 
                    u.name, 
                    h.brd_slno, 
                    CASE 
                        WHEN h.conn_key = h.diary_no THEN null 
                        ELSE m.diary_no_rec_date 
                    END ASC";
        // pr($sql);
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function advsql($diary_no)
    {
        $sql = "SELECT a.*, 
                STRING_AGG(a.name || CASE WHEN pet_res = 'R' THEN grp_adv ELSE '' END, '') 
                    FILTER (WHERE pet_res = 'R') AS r_n,
                STRING_AGG(a.name || CASE WHEN pet_res = 'P' THEN grp_adv ELSE '' END, '') 
                    FILTER (WHERE pet_res = 'P') AS p_n,
                STRING_AGG(a.name || CASE WHEN pet_res = 'I' THEN grp_adv ELSE '' END, '') 
                    FILTER (WHERE pet_res = 'I') AS i_n
            FROM (
                SELECT a.diary_no, 
                    b.name, 
                    STRING_AGG(COALESCE(a.adv, '')::text, '' ORDER BY 
                                CASE WHEN pet_res = 'I' THEN 99 ELSE 0 END ASC, 
                                adv_type DESC, pet_res_no ASC) AS grp_adv, 
                    a.pet_res, 
                    a.adv_type, 
                    a.pet_res_no
                FROM advocate a
                LEFT JOIN master.bar b 
                    ON a.advocate_id = b.bar_id AND b.isdead != 'Y'
                WHERE a.diary_no = '$diary_no' AND a.display = 'Y'
                GROUP BY a.diary_no, b.name, a.pet_res, a.adv_type, a.pet_res_no
            ) a
            GROUP BY a.diary_no,a.name,a.grp_adv,a.pet_res,a.adv_type,a.pet_res_no";

        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function sectionTenRow($casetype_displ, $ten_reg_yr, $ref_agency_state_id)
    {
        $sql = "SELECT dacode,section_name,name FROM  master.da_case_distribution a
                LEFT JOIN  master.users b ON usercode=dacode
                LEFT JOIN  master.usersection c ON b.section=c.id
                WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$ref_agency_state_id' AND a.display='Y' ";
       
       $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function officeReportData($diary_no,$list_dt1){
        $sql = "Select substr( diary_no, 1, length( diary_no ) -4 ) as dno, diary_no as dno1, substr(diary_no,-4) as d_yr, office_repot_name,office_report_id,order_dt,rec_dt, summary from office_report_details where diary_no='$diary_no' and order_dt='$list_dt1' and display='Y' and web_status=1";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
        
    }
}
