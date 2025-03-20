<?php

namespace App\Models\Coram;

use CodeIgniter\Model;

class CoramQueryModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function get_main($diary_no){

        $tbl = is_table_a('main');

        $builder = $this->db->table("public.".$tbl);
        $builder->select("pet_name,res_name,c_status");
        $builder->where("diary_no",$diary_no);
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_advocate_ntl_judge($diary_no){

        $tbl_advocate = is_table_a('advocate');

        $builder = $this->db->table("public.".$tbl_advocate." a");
        $builder->select("j.jname, b.name, j.abbreviation, b.aor_code, n.display");
        $builder->join("master.ntl_judge n","ON a.advocate_id = n.org_advocate_id","left");
        $builder->join("master.bar b","ON b.bar_id = n.org_advocate_id","left");
        $builder->join("master.judge j","ON j.jcode = n.org_judge_id ","left");
        $builder->where("a.diary_no",$diary_no);
        $builder->where("a.display","Y");
        $builder->where("org_advocate_id IS NOT NULL");
        $builder->where("j.jcode IS NOT NULL");
        $builder->groupBy("abbreviation,j.jname,b.name,b.aor_code,n.display");
        $builder->orderBy("n.display","DESC");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_party_ntl_judge($diary_no){

        $tbl_party = is_table_a('party');

        $builder = $this->db->table("public.".$tbl_party." a");
        $builder->select("j.jname,j.abbreviation,d.deptname,n.display");
        $builder->join("master.ntl_judge_dept n","ON a.deptcode = n.dept_id","left");
        $builder->join("master.judge j","ON j.jcode = n.org_judge_id ","left");
        $builder->join("master.deptt d","ON d.deptcode = n.dept_id AND d.display = 'Y'","left");
        $builder->where("d.display","Y");
        $builder->where("a.diary_no",$diary_no);
        $builder->where("a.pflag != 'T'");
        $builder->where("a.deptcode IS NOT NULL");
        $builder->where("j.jcode IS NOT NULL");
        $builder->groupBy("n.org_judge_id, n.dept_id,j.jname,j.abbreviation,d.deptname,n.display");
        $builder->orderBy("n.display","DESC");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    }

    public function get_coram_detail_data($diary_no){

        $tbl_coram = is_table_a('coram');
        $tbl_heardt = is_table_a('heardt');

        $builder1 = $this->db->table("public.".$tbl_coram." c");
        $builder1->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,CAST(c.diary_no AS TEXT) AS diary_no,'C' AS notbef,c.ent_dt,n.res_add, CAST(c.jud AS TEXT) as coram,j.judge_seniority,c.usercode,c.display");
        $builder1->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(c.jud AS VARCHAR), ',')::int[]) > '0'");
        $builder1->join("master.not_before_reason n", "n.res_id = c.res_id","left");
        $builder1->where("c.diary_no",$diary_no);
        $builder1->where("c.to_dt",NULL);
        $builder1->groupBy("c.diary_no,j.jcode,c.ent_dt,n.res_add,c.jud,c.usercode,c.display");


        $builder2 = $this->db->table("public.".$tbl_heardt." h");
        $builder2->select("j.jcode,string_agg(concat(j.jname,'[',j.abbreviation,']'),'') as jname,CAST(h.diary_no AS TEXT) AS diary_no,'C' AS notbef,h.ent_dt,n.res_add, CAST(h.coram AS TEXT),j.judge_seniority,h.usercode, 'Y' as display");
        $builder2->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]) > '0'");
        $builder2->join("master.not_before_reason n", "n.res_id = h.list_before_remark","left");
        $builder2->where("h.diary_no",$diary_no);
        $builder2->groupBy("h.diary_no,j.jcode,h.ent_dt,n.res_add,h.coram,h.usercode");


        $builder4 = $this->db->table("public.not_before n");
        $builder4->select("j.jcode,concat(j.jname,'[',j.abbreviation,']') as jname,CAST(n.diary_no AS TEXT) AS diary_no,n.notbef,n.ent_dt,nbs.res_add, CAST(0 AS TEXT),j.judge_seniority,n.usercode, 'Y' as display");
        $builder4->join("master.judge j", "j.jcode = n.j1","left");
        $builder4->join("master.not_before_reason nbs", "n.res_id = nbs.res_id","left");
        $builder4->where("n.diary_no",$diary_no);


        $builder5 = $this->db->table("public.not_before_his n");
        $builder5->select("j.jcode,concat(j.jname,'[',j.abbreviation,']') as jname,CAST(n.diary_no AS TEXT) AS diary_no,n.notbef,n.ent_dt,nbs.res_add, CAST(0 AS TEXT),j.judge_seniority,n.usercode, 'N' as display");
        $builder5->join("master.judge j", "j.jcode = n.j1","left");
        $builder5->join("master.not_before_reason nbs", "n.old_res_id = nbs.res_id","left");
        $builder5->where("n.diary_no",$diary_no);
        $builder5->orderBy("display desc, j.judge_seniority");


        $finalQuery = $builder1->union($builder2)->union($builder4)->union($builder5);

        $query =$finalQuery->get();

        if($query->getNumRows() >= 1) {
            $result1 = $query->getResultArray();

            $result2 = $this->get_coram_detail_data1($diary_no);

            return array_merge($result1,$result2);
        }else{
            return [];
        }
        
    }

    public function get_coram_detail_data1($diary_no){

        $tbl_last_heardt = is_table_a('last_heardt');

        $builder3 = $this->db->table("public.".$tbl_last_heardt." h");
        $builder3->select("distinct(j.jcode),concat(j.jname,'[',j.abbreviation,']') as jname,h.diary_no,nbs.notbef,min(h.ent_dt) as ent_dt,nbs.res_add, coram,j.judge_seniority,h.usercode, 'N' as display");
        $builder3->join("master.judge j", "j.jcode = ANY(string_to_array(CAST(h.coram AS VARCHAR), ',')::int[]) > '0'");
        $builder3->join("master.not_before_reason nbs", "nbs.res_id = h.list_before_remark","left");
        $builder3->where("h.diary_no",$diary_no);
        $builder3->groupBy("concat(j.jname,'[',j.abbreviation,']'),j.jcode,h.diary_no,nbs.notbef,nbs.res_add,h.coram,h.usercode");

        $query =$builder3->get();

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
        
        $builder1 = $this->db->table("public.".$tbl_coram." c");
        $builder1->select("CAST(jud As TEXT) as coram,ent_dt,name");
        $builder1->join("master.users u", "c.usercode = u.usercode");
        $builder1->where("diary_no",$diary_no);
        $builder1->where("to_dt",NULL);
        $builder1->where("c.display","Y");
        $builder1->where("CAST(jud As TEXT)",$coram);

        $builder2 = $this->db->table("public.".$tbl_heardt." h");
        $builder2->select("CAST(coram as TEXT),ent_dt,name");
        $builder2->join("master.users u", "h.usercode = u.usercode");
        $builder2->where("diary_no",$diary_no);
        $builder2->where("coram",$coram);

        $builder3 = $this->db->table("public.".$tbl_last_heardt." lh");
        $builder3->select("CAST(coram as TEXT),ent_dt,name");
        $builder3->join("master.users u", "lh.usercode = u.usercode");
        $builder3->where("diary_no",$diary_no);
        $builder3->where("coram",$coram);
        $builder3->orderBy("ent_dt","ASC");

        $final_query = $builder1->union($builder2)->union($builder3);

        $query =$final_query->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

}