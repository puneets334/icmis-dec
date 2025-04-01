<?php

namespace App\Models\Filing;

use CodeIgniter\Model;

class CoramModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function get_reason($save_as_val){

        if($save_as_val=='C'){
            $save_as_val = 'B';
        }

        $builder = $this->db->table("master.not_before_reason");
        $builder->select("*");
        $builder->where('notbef',$save_as_val);
        $builder->where('display','Y');
        $builder->orderBy('res_id');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function insert_not_before($ins_arr){
        
        $builder = $this->db->table('public.not_before');
        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_coram($diary_no){

        $tbl_coram = is_table_a('coram');

        $builder = $this->db->table("public.".$tbl_coram);
        $builder->select("*");
        $builder->where('diary_no',$diary_no);
        $builder->where('to_dt',NULL);
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function update_coram($upd_arr,$diary_no){
        
        $builder = $this->db->table('public.coram');

        $builder->where('diary_no',$diary_no);

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function insert_coram($ins_coram_arr){
        
        $builder = $this->db->table('public.coram');

        $query = $builder->insert($ins_coram_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_heardt_main($diary_no){

        $tbl_heardt = is_table_a('heardt');
        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_heardt." h, public.".$tbl_main." m");
        $builder->select("h.*, m.conn_key as main_key");
        $builder->where("m.diary_no = h.diary_no");
        $builder->where("h.diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_last_heardt($diary_no,$get_heardt_main){

        $tbl_last_heardt = is_table_a('last_heardt');

        $builder = $this->db->table("public.".$tbl_last_heardt);
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
        $builder->where("conn_key",$get_heardt_main[0]['conn_key']);
        $builder->where("next_dt",$get_heardt_main[0]['next_dt']);
        $builder->where("mainhead",$get_heardt_main[0]['mainhead']);
        $builder->where("subhead",$get_heardt_main[0]['subhead']);
        $builder->where("clno",$get_heardt_main[0]['clno']);
        $builder->where("brd_slno",$get_heardt_main[0]['brd_slno']);
        $builder->where("roster_id",$get_heardt_main[0]['roster_id']);
        $builder->where("judges",$get_heardt_main[0]['judges']);
        $builder->where("coram",$get_heardt_main[0]['coram']);
        $builder->where("board_type",$get_heardt_main[0]['board_type']);
        $builder->where("usercode",$get_heardt_main[0]['usercode']);
        $builder->where("ent_dt",$get_heardt_main[0]['ent_dt']);
        $builder->where("module_id",$get_heardt_main[0]['module_id']);
        $builder->where("mainhead_n",$get_heardt_main[0]['mainhead_n']);
        $builder->where("subhead_n",$get_heardt_main[0]['subhead_n']);
        $builder->where("main_supp_flag",$get_heardt_main[0]['main_supp_flag']);
        $builder->where("listorder",$get_heardt_main[0]['listorder']);
        $builder->where("tentative_cl_dt",$get_heardt_main[0]['tentative_cl_dt']);
        $builder->where("listed_ia",$get_heardt_main[0]['listed_ia']);
        $builder->where("sitting_judges",$get_heardt_main[0]['sitting_judges']);
        $builder->where("list_before_remark",$get_heardt_main[0]['list_before_remark']);
        $builder->where("is_nmd",$get_heardt_main[0]['is_nmd']);
        $builder->where("no_of_time_deleted",$get_heardt_main[0]['no_of_time_deleted']);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function insert_last_heardt($ins_last_heardt_arr){
        
        $builder = $this->db->table('public.last_heardt');

        $query = $builder->insert($ins_last_heardt_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function update_heardt($upd_heardt_arr,$diary_no){
        
        $builder = $this->db->table('public.heardt');

        $builder->where('diary_no',$diary_no);

        $query = $builder->update($upd_heardt_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function insert_heardt($ins_heardt_arr){
        
        $builder = $this->db->table('public.heardt');

        $query = $builder->insert($ins_heardt_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_conct_heardt($diary_no,$main_key){

        $tbl_conct = is_table_a('conct');
        $tbl_heardt = is_table_a('heardt');

        $builder = $this->db->table("public.".$tbl_conct." c, public.".$tbl_heardt." h");
        $builder->select("h.*");
        $builder->where("c.diary_no",$diary_no);
        $builder->where("c.conn_key",$main_key);
        $builder->where("c.conn_key != c.diary_no");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function coram_detail($diary_no){

        
        $tbl_coram = is_table_a('coram');
        $tbl_heardt = is_table_a('heardt');

        $builder1 = $this->db->table("public.".$tbl_coram." c");
        $builder1->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,CAST(c.diary_no AS TEXT) AS diary_no,'C' AS notbef,c.ent_dt,n.res_add, CAST(c.jud AS TEXT) as coram,j.judge_seniority,c.usercode");
        $builder1->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(c.jud AS VARCHAR), ',')::int[]) > '0'");
        $builder1->join("master.not_before_reason n", "n.res_id = c.res_id","left");
        $builder1->where("c.diary_no",$diary_no);
        $builder1->where("c.to_dt",NULL);
        $builder1->where("c.display","Y");
        $builder1->groupBy("c.diary_no,j.jcode,c.ent_dt,n.res_add,c.jud,c.usercode");

       
        $builder2 = $this->db->table("public.".$tbl_heardt." h");
        $builder2->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,CAST(h.diary_no AS TEXT) AS diary_no,'C' AS notbef,h.ent_dt,n.res_add, CAST(h.coram AS TEXT),j.judge_seniority,h.usercode");
        $builder2->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]) > '0'");
        $builder2->join("master.not_before_reason n", "n.res_id = h.list_before_remark","left");
        $builder2->where("h.diary_no",$diary_no);
        $builder2->groupBy("h.diary_no,j.jcode,h.ent_dt,n.res_add,h.coram,h.usercode");
       

        $builder3 = $this->db->table("public.not_before n");
        $builder3->select("j.jcode,concat(j.jname,'[',j.abbreviation,']') as jname,CAST(n.diary_no AS TEXT) AS diary_no,n.notbef,n.ent_dt,nbs.res_add, CAST(0 AS TEXT),j.judge_seniority,n.usercode");
        $builder3->join("master.judge j", "j.jcode = n.j1","left");
        $builder3->join("master.not_before_reason nbs", "n.res_id = nbs.res_id","left");
        $builder3->where("n.diary_no",$diary_no);
        $builder3->orderBy("j.judge_seniority");
        
        $subquery = $builder1->union($builder2)->union($builder3);
        
        $final_query  = $this->db->newQuery()->select('a.*, u.empid, u.name')->fromSubquery($subquery, 'a')->join("master.users u", "u.usercode = a.usercode","left");
       // pr($final_query->getCompiledSelect());
        $query =$final_query->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_coram_entry_date($diary_no,$coram){

        $tbl_coram = is_table_a('coram');
        $tbl_heardt = is_table_a('heardt');
        $tbl_last_heardt = is_table_a('last_heardt');
        
        $builder1 = $this->db->table("public.".$tbl_coram);
        $builder1->select("CAST(jud AS TEXT) as coram,ent_dt,usercode");
        $builder1->where("diary_no",$diary_no);
        $builder1->where("to_dt",NULL);
        $builder1->where("display","Y");
        $builder1->where("CAST(jud AS TEXT)",$coram);

        $builder2 = $this->db->table("public.".$tbl_heardt);
        $builder2->select("CAST(coram as TEXT),ent_dt,usercode");
        $builder2->where("diary_no",$diary_no);
        $builder2->where("coram",$coram);

        $builder3 = $this->db->table("public.".$tbl_last_heardt);
        $builder3->select("CAST(coram as TEXT),ent_dt,usercode");
        $builder3->where("diary_no",$diary_no);
        $builder3->where("coram",$coram);

        $subquery = $builder1->union($builder2)->union($builder3);

        $final_query  = $this->db->newQuery()->select('a.*, u.empid, u.name')->fromSubquery($subquery, 'a')->join("master.users u", "u.usercode = a.usercode","left");

        $query =$final_query->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function get_not_before($jcode,$diary_no){

        $builder = $this->db->table("public.not_before");
        $builder->select("*");
        $builder->where('j1',$jcode);
        $builder->where('diary_no',$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }


    public function insert_not_before_his($ins_arr){
        
        $builder = $this->db->table('public.not_before_his');

        $query = $builder->insert($ins_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function delete_not_before($diary_no,$j1){
        
        $builder = $this->db->table('public.not_before');

        $builder->where('diary_no',$diary_no);
        $builder->where('j1',$j1);

        $query = $builder->delete();

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_coram_by_jud($diary_no,$jcode){

        $tbl_coram = is_table_a('coram');

        $builder = $this->db->table("public.".$tbl_coram);
        $builder->select("*");
        $builder->where('diary_no',$diary_no);
        $builder->where('jud',$jcode);
        $builder->where('to_dt',NULL);        
        $builder->where('display','Y');
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getNumRows();
        }else{
            return 0;
        }
        
    }

    public function update_coram_by_jud($upd_arr,$diary_no){
        
        $builder = $this->db->table('public.coram');

        $builder->where('diary_no',$diary_no);
        $builder->where('to_dt',NULL);        
        $builder->where('display','Y');

        $query = $builder->update($upd_arr);

        if($query) {
            return 1;
        }else
        {
            return 0;
        }
        
    }

    public function get_heardt($diary_no){

        $tbl_heardt = is_table_a('heardt');

        $builder = $this->db->table("public.".$tbl_heardt);
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function not_before_judge($diary_no){

        $tbl_lowerct = is_table_a('lowerct');
        $tbl_lowerct_judges = is_table_a('lowerct_judges');

        $builder = $this->db->table("public.".$tbl_lowerct." a");
        $builder->select("judge_id, supreme_court_jud_id, d.first_name, d.sur_name, court_name, f.name");
        $builder->select("(CASE WHEN ct_code = 3 THEN (SELECT NAME FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y') ELSE 
                (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f') END) AS agency_name", false);
        $builder->select("lct_casetype, lct_caseno, lct_caseyear");
        $builder->select("(CASE WHEN ct_code = 4 THEN (SELECT skey FROM master.casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) ELSE 
                (SELECT type_sname FROM master.lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') END) AS type_sname", false);
        $builder->select("lct_dec_dt", "lower_court_id");
        $builder->join("public.".$tbl_lowerct_judges." b", "a.lower_court_id = b.lowerct_id");
        $builder->join("master.org_lower_court_judges c", "c.id = b.judge_id");
        $builder->join("master.judge d", "d.jcode = c.supreme_court_jud_id");
        $builder->join("master.m_from_court e", "e.id = a.ct_code");
        $builder->join("master.state f", "a.l_state = f.id_no AND f.display = 'Y'", "left");
        $builder->join('not_before g', 'g.diary_no = (a.diary_no)::text', 'left');
        $builder->where("lw_display", "Y");
        $builder->where("lct_display", "Y");
        $builder->where("a.diary_no", $diary_no);
        $builder->where("supreme_court_jud_id !=", 0);
        $builder->where("is_deleted", "f");
        $builder->where("d.display", "Y");
        $builder->where("d.is_retired", "N");
        $builder->where("e.display", "Y");
        $builder->where("g.diary_no IS NULL");
        $builder->where("g.j1 IS NULL");
        //echo $builder->getCompiledSelect();
        //die;
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function count_id_not_before($diary_no,$hd_jud_id){

        $builder = $this->db->table("public.not_before");
        $builder->select("count(id)");        
        $builder->where('diary_no',$diary_no);
        $builder->where('j1',$hd_jud_id);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }


    public function get_case_status($diary_no){

        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_main);
        $builder->select("pet_name,res_name,c_status");        
        $builder->where('diary_no',$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }
	
	public function getSensitiveCases()
{
    $sql = "
        SELECT 
            tentative_section(CAST(a.diary_no AS text)) AS ten_sec, 
            a.diary_no, 
            COALESCE(
                STRING_AGG(CAST(n.j1 AS text), ','), 
                c.coram
            ) AS coram, 
            nr.res_add, 
            reason, 
            b.pet_name, 
            b.res_name, 
            next_dt, 
            active_fil_no, 
            short_description, 
            EXTRACT(YEAR FROM active_fil_dt) AS active_fil_dt, 
            b.reg_no_display 
        FROM 
            sensitive_cases a 
        JOIN 
            main b ON a.diary_no = b.diary_no
        LEFT JOIN 
            heardt c ON a.diary_no = c.diary_no
        LEFT JOIN 
            master.casetype d ON d.casecode = CAST(SUBSTRING(b.active_fil_no FROM 1 FOR 2) AS INTEGER) AND d.display = 'Y'
        LEFT JOIN 
            not_before n ON n.diary_no = a.diary_no AND n.notbef = 'B'
        LEFT JOIN 
            master.not_before_reason nr ON nr.res_id = n.res_id
        WHERE 
            a.display = 'Y' 
            AND c_status = 'P'
        GROUP BY 
            a.diary_no, nr.res_add, reason, b.pet_name, b.res_name, next_dt, active_fil_no, short_description, active_fil_dt, b.reg_no_display, c.coram
        ORDER BY 
            SUBSTRING(CAST(a.diary_no AS text) FROM LENGTH(CAST(a.diary_no AS text)) - 3 FOR 4),
            SUBSTRING(CAST(a.diary_no AS text) FROM 1 FOR LENGTH(CAST(a.diary_no AS text)) - 4),
            next_dt
    ";

    $query = $this->db->query($sql);
    if ($query->getNumRows() > 0) {
        return $query->getResultArray();
    } else {
        return false;
    }
}




}