<?php

namespace App\Models\Coram;

use CodeIgniter\Model;

class RegistrarCoramShiftingModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_judge(){

        $tbl_coram = is_table_a('coram');
        $tbl_main = is_table_a('main');

        $builder = $this->db->table("public.".$tbl_coram." c, public.".$tbl_main." m, master.judge j");
        $builder->select("count(distinct c.diary_no) total_cases, jud, jcode, jname, abbreviation, first_name, sur_name");
        $builder->where("m.diary_no = c.diary_no");
        $builder->where("m.c_status","P");
        $builder->where("c.jud = j.jcode");
        $builder->where("c.to_dt",NULL);
        $builder->where("c.display","Y");
        $builder->where("c.board_type","R");
        $builder->groupBy("jud,jcode");
        $builder->orderBy("judge_seniority");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_sub_judge(){
        $builder = $this->db->table("master.judge j");
        $builder->select("jcode, jname, abbreviation, first_name, sur_name");
        $builder->where("j.jtype","R");
        $builder->where("is_retired != 'Y'");
        $builder->where("length(CAST(jcode AS TEXT)) >= 3");
        $builder->where("jname not like '%Migration%'");
        $builder->orderBy("jtype,judge_seniority");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_data($judge,$judge_to,$usercode){

        $tbl_coram = is_table_a('coram');
        $tbl_main = is_table_a('main');

        $cur_dt = date('Y-m-d');

        $builder = $this->db->table("public.".$tbl_coram." c, public.".$tbl_main." m");
        $builder->select("c.diary_no,(select 'R' as board_type),(select ".$judge_to." as jud),(select '2' as res_id),(select '".$cur_dt."' as from_dt),(select NULL as to_dt),(select ".$usercode." as usercode),(select 'NOW()' as ent_dt)");
        $builder->where("m.diary_no = c.diary_no");
        $builder->where("m.c_status = 'P'");
        $builder->where("c.to_dt",NULL);
        $builder->where("c.display","Y");
        $builder->where("c.board_type","R");
        $builder->where("c.jud",$judge);
        $builder->where("$judge_to != $judge");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {

            $insertData = $query->getResultArray();

            $res = $this->db->table('public.coram')->insertBatch($insertData);

            if($res > 0){

                $upd_arr = [
                            'to_dt'      =>      $cur_dt,
                            'del_reason'    =>      'By coram shifting'
                            ];

                $upd = update('public.coram',$upd_arr,['board_type' => 'R' , 'jud' => $judge]);

                return $upd;
            }

        }else{
            return 0;
        }   
    }

}