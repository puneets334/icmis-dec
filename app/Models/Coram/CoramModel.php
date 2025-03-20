<?php

namespace App\Models\Coram;

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

        $builder = $this->db->table("public.coram");
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

        $builder = $this->db->table("public.heardt h, public.main m");
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

        $builder = $this->db->table("public.last_heardt");
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

        $builder = $this->db->table("public.conct c, public.heardt h");
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

        $builder1 = $this->db->table("public.coram c");
        $builder1->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,c.diary_no,'C' AS notbef,c.ent_dt,n.res_add, c.jud as coram,j.judge_seniority,c.usercode");
        $builder1->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(c.jud AS VARCHAR), ',')::int[]) > '0'");
        $builder1->join("master.not_before_reason n", "n.res_id = c.res_id","left");
        $builder1->where("c.diary_no",$diary_no);
        $builder1->where("c.to_dt",NULL);
        $builder1->where("c.display","Y");
        $builder1->groupBy("c.diary_no,j.jcode,c.ent_dt,n.res_add,c.jud,c.usercode");


        $builder2 = $this->db->table("public.heardt h");
        $builder2->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,h.diary_no,'C' AS notbef,h.ent_dt,n.res_add, CAST(h.coram AS int),j.judge_seniority,h.usercode");
        $builder2->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]) > '0'");
        $builder2->join("master.not_before_reason n", "n.res_id = h.list_before_remark","left");
        $builder2->where("h.diary_no",$diary_no);
        $builder2->groupBy("h.diary_no,j.jcode,h.ent_dt,n.res_add,h.coram,h.usercode");


        $builder3 = $this->db->table("public.not_before n");
        $builder3->select("j.jcode,concat(j.jname,'[',j.abbreviation,']') as jname,n.diary_no,n.notbef,n.ent_dt,nbs.res_add, 0,j.judge_seniority,n.usercode");
        $builder3->join("master.judge j", "j.jcode = n.j1","left");
        $builder3->join("master.not_before_reason nbs", "n.res_id = nbs.res_id","left");
        $builder3->where("n.diary_no",$diary_no);
        $builder3->orderBy("j.judge_seniority");

        $subquery = $builder1->union($builder2)->union($builder3);

        $final_query  = $this->db->newQuery()->select('a.*, u.empid, u.name')->fromSubquery($subquery, 'a')->join("master.users u", "u.usercode = a.usercode","left");

        $query =$final_query->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
        
    }

    public function get_coram_entry_date($diary_no,$coram){
        
        $builder1 = $this->db->table("public.coram");
        $builder1->select("jud as coram,ent_dt,usercode");
        $builder1->where("diary_no",$diary_no);
        $builder1->where("to_dt",NULL);
        $builder1->where("display","Y");
        $builder1->where("jud",$coram);

        $builder2 = $this->db->table("public.heardt");
        $builder2->select("CAST(coram as BIGINT),ent_dt,usercode");
        $builder2->where("diary_no",$diary_no);
        $builder2->where("coram",$coram);

        $builder3 = $this->db->table("public.last_heardt");
        $builder3->select("CAST(coram as BIGINT),ent_dt,usercode");
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

        $builder = $this->db->table("public.coram");
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

        $builder = $this->db->table("public.heardt");
        $builder->select("*");
        $builder->where("diary_no",$diary_no);
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

}