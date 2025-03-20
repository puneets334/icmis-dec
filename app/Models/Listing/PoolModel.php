<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class PoolModel extends Model
{
   
    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function f_ia(){
        $builder = $this->db->table('master.docmaster');
        $builder->select('doccode1, docdesc');
        $builder->where('doccode', '8');
        $builder->where('display', 'Y');
        $builder->orderBy('docdesc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function f_act(){
        $builder1 = $this->db->table('master.act_master');
        $builder1->select('id, act_name');
        $builder1->where('display', 'Y');
        $builder1->where('act_name !=', '');
        $builder1->where('act_name IS NOT NULL');
        $builder1->orderBy('act_name');
        $query1 = $builder1->get();
        return $query1->getResultArray();
    }

    public function f_keyword(){
        $builder2 = $this->db->table('master.ref_keyword');
        $builder2->select('id, keyword_description');
        $builder2->where('is_deleted', 'f');
        $builder2->orderBy('keyword_description');
        $query2 = $builder2->get();
        return $query2->getResultArray();
    }

    public function getCaseDetails($diary_no){
        $builder = $this->db->table('main m');
        $builder->select('reg_no_display, m.diary_no, diary_no_rec_date, fil_dt, active_fil_dt, fil_dt_fh, mf_active, c_status, pet_name, res_name, pno, rno, active_casetype_id');
        $builder->where('m.diary_no', $diary_no);
        $builder->where('m.c_status', 'P');
        $query = $builder->get();
        return $query->getRowArray();
         
    }

    public function getMainheadInfo($diary_no){
        $query = $this->db->table('heardt')
        ->select('mainhead')
        ->where('diary_no', $diary_no)
        ->where('mainhead', 'F')
        ->get();
        return $query->getRowArray();
    }

    public function getStageNameInfo($diary_no){
        $query = $this->db->table('heardt h')
        ->select('s.stagename, h.mainhead')
        ->join('master.subheading s', 'h.subhead = s.stagecode', 'inner')
        ->where('h.diary_no', $diary_no)
        ->where('h.mainhead', 'M')
        ->get();
        return $query->getRowArray();
    }

    public function getCategoryInfo($diary_no){
        $query= $this->db->table('mul_category mc')
        ->select('s.sub_name1, s.sub_name2, s.sub_name3, s.sub_name4')
        ->join('master.submaster s', 's.id = mc.submaster_id', 'inner')
        ->where('mc.diary_no', $diary_no)
        ->where('mc.display', 'Y')
        ->get();
        return $query->getResultArray();
    }

    public function isAlreadyInPool($diary_no){
        $builder111 = $this->db->table('vacation_registrar_pool');
        $builder111->where('diary_no', $diary_no);
        $query = $builder111->get();
        return $query->getRowArray();
    }
}