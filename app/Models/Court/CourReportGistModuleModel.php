<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class CourReportGistModuleModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function report_gist_module($court_no, $mainhead, $main_suppl, $list_dt, $board_type){

        $builder = $this->db->table('heardt h');
        $builder->select('
                   ord.rec_dt,
                   ord.gist_last_read_datetime,
                   ord.summary,
                   m.diary_no,
                   m.conn_key,
                   r.courtno,
                   m.reg_no_display,
                   m.pet_name,
                   m.res_name,
                   m.pno,
                   m.rno,
                   h.brd_slno,
                   tentative_section(m.diary_no) as section_name
               ');
               $builder->join('main m', 'm.diary_no = h.diary_no', 'INNER');
               $builder->join('roster r', 'r.id = h.roster_id AND r.display = "Y"', 'INNER');
               $builder->join('cl_printed p', 'p.next_dt = h.next_dt AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = "Y"', 'LEFT');
               $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = "Y"', 'LEFT');
               $builder->join('office_report_details ord', 'ord.diary_no = m.diary_no AND ord.order_dt = h.next_dt AND ord.display = "Y" AND ord.web_status = 1', 'LEFT');
               $builder->where('p.id IS NOT NULL');
               $builder->where('h.mainhead', $mainhead);
               $builder->where('h.next_dt', $list_dt);
               $builder->where('(h.main_supp_flag = 1 OR h.main_supp_flag = 2)');
               $builder->where('h.roster_id > 0');
               $builder->where('m.diary_no IS NOT NULL');
               $builder->where('m.c_status', 'P');
               $builder->groupBy('h.diary_no');
               $builder->orderBy("CASE WHEN h.conn_key = h.diary_no THEN '0000-00-00' ELSE m.diary_no_rec_date END", 'ASC');
               $builder->orderBy("CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE '999' END", 'ASC');
               $builder->orderBy("CAST(SUBSTRING(m.diary_no, -4) AS SIGNED)", 'ASC');
               $builder->orderBy("CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS SIGNED)", 'ASC');
   
           if (!empty($court_no)) {
               $builder->where('r.courtno', $court_no);
           }
          
           if (!empty($board_type)) {
               $builder->where($board_type);
           }
   
           if (!empty($main_suppl)) {
               $builder->where($main_suppl);
           }

           $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    
   
   }


}