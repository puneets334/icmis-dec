<?php

namespace App\Models\Coram;

use CodeIgniter\Model;

class AorModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_bar(){
        $builder = $this->db->table("master.bar");
        $builder->select("bar_id, aor_code, name");
        $builder->where("isdead != 'Y'");
        $builder->where("if_aor","Y");
        $builder->where("name != '0'");
        $builder->orderBy("name");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_ntl_judge(){ 
        $builder = $this->db->table("master.ntl_judge n, master.judge j, master.bar b");
        $builder->select("jname, b.name, b.aor_code, n.org_advocate_id, n.org_judge_id");
        $builder->where("n.org_judge_id = j.jcode");
        $builder->where("n.org_advocate_id = b.bar_id");
        $builder->where("n.display","Y");
        $builder->where("j.is_retired != 'Y'");
        $builder->orderBy("j.judge_seniority, b.name");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        } 
    }

}