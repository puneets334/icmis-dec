<?php

namespace App\Models\Coram;

use CodeIgniter\Model;

class DeptModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_department(){
        $builder = $this->db->table("master.deptt");
        $builder->select("deptcode,deptname");
        $builder->orderBy("deptname");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_ntl_judge_dept(){ 
        $builder = $this->db->table("master.ntl_judge_dept n, master.judge j, master.deptt b");
        $builder->select("jname, b.deptname, n.dept_id, n.org_judge_id");
        $builder->where("n.org_judge_id = j.jcode");
        $builder->where("n.dept_id = b.deptcode");
        $builder->where("n.display","Y");
        $builder->where("j.is_retired != 'Y'");
        $builder->orderBy("j.judge_seniority, b.deptname");
        $query = $builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        } 
    }

}