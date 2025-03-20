<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class CourReportPaperLessModel extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }

    public function getJudgeDetails($dtd, $courtno)
    {
      
        $builder = $this->db->table("master.roster r");
        $builder->select("GROUP_CONCAT(DISTINCT j.jcode ORDER BY j.judge_seniority) AS jcd");
        $builder->select("GROUP_CONCAT(DISTINCT j.jname ORDER BY j.judge_seniority SEPARATOR ', ') AS jnm");
        $builder->select('j.first_name, j.sur_name, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session');
        
        $builder->join('roster_bench rb', 'rb.id = r.bench_id', 'left');
        $builder->join('master_bench mb', 'mb.id = rb.bench_id', 'left');
        $builder->join('roster_judge rj', 'rj.roster_id = r.id', 'left');
        $builder->join('judge j', 'j.jcode = rj.judge_id', 'left');
        $builder->join('cl_printed cp', "cp.roster_id = r.id AND cp.next_dt = '{$dtd}' AND cp.display = 'Y'", 'left');

        $builder->where('cp.next_dt IS NOT NULL', null, false);
        $builder->where('j.is_retired !=', 'Y');
        $builder->where('j.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.display', 'Y');
        
        if ($courtno > 0) {
            $builder->where('r.courtno', $courtno);
        }
        
        $builder->groupBy('r.id');
        $builder->orderBy('r.id');
        $builder->orderBy('j.judge_seniority');
        
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }
    }

    public function getRosterIds($crt, $dtd, $mf)
    {


        $builder = $this->db->table("master.roster_judge rj");
        $builder->select('DISTINCT rj.roster_id, mb.board_type_mb');
        $builder->join('roster r', 'rj.roster_id = r.id');
        $builder->join('roster_bench rb', 'rb.id = r.bench_id AND rb.display = "Y"', 'left');
        $builder->join('master_bench mb', 'mb.id = rb.bench_id AND mb.display = "Y"', 'left');

        $tdt1 = date('Y-m-d', strtotime($dtd));
       
        $t_cn = " AND r.courtno = '{$crt}' AND IF(r.to_date = '0000-00-00' AND r.m_f = 2, r.to_date = '0000-00-00', '{$tdt1}' BETWEEN r.from_date AND r.to_date)";

        $builder->where('r.m_f IN (1, 2)', null, false);
        $builder->where('r.display', 'Y');
        $builder->where('rj.display', 'Y');
        $builder->where('rb.display', 'Y');
        $builder->where('mb.display', 'Y');
        $builder->where('r.courtno', $crt);
        $builder->where("IF(r.to_date = '0000-00-00' AND r.m_f = 2, r.to_date = '0000-00-00', '{$tdt1}' BETWEEN r.from_date AND r.to_date)", null, false);

        $builder->orderBy('IF(r.courtno = 0, 9999, r.courtno)');
        $builder->orderBy("CASE 
            WHEN mb.board_type_mb = 'J' THEN 1
            WHEN mb.board_type_mb = 'S' THEN 2
            WHEN mb.board_type_mb = 'C' THEN 3
            WHEN mb.board_type_mb = 'CC' THEN 4
            WHEN mb.board_type_mb = 'R' THEN 5
            END");
        $builder->orderBy('rj.judge_id');

        $query = $builder->get();
        $result = $query->getResultArray();
        $rosterIds = array_column($result, 'roster_id');
        return implode(',', $rosterIds);
    }

    public function getCaseDetails($dtd, $rosterIds, $r_status)
    {
        $builder = $this->db->table('heardt t1');
        $builder->select('
            SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS case_no,
            SUBSTR(m.diary_no, -4) AS year,
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
            IF(cl.next_dt IS NULL, "NA", h.brd_slno) AS brd_prnt,
            h.roster_id,
            m.casetype_id,
            m.case_status_id,
            m.short_description,
            m.list_status
        ');
        $builder->join('main m', 'h.diary_no = m.diary_no', 'inner');
        $builder->join('cl_printed cl', 'cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.roster_id = h.roster_id AND cl.display = "Y"', 'left');
        $builder->join('casetype c', 'm.casetype_id = c.casecode', 'left');
        $builder->join('conct ct', 'm.diary_no = ct.diary_no AND ct.list = "Y"', 'left');

      
        $tdt1 = date('Y-m-d', strtotime($dtd));

     
        $builder->where('h.next_dt', $tdt1);
        $builder->where("FIND_IN_SET(h.roster_id, '{$rosterIds}') > 0");
        $builder->whereIn('h.mainhead', ['M', 'F']);
        $builder->groupStart();
        $builder->whereIn('h.main_supp_flag', [1, 2]);
        $builder->groupEnd();
        $builder->groupBy('h.diary_no');
        
        if ($r_status == 'P') {
            $builder->where('m.c_status', 'P');
        } elseif ($r_status == 'D') {
            $builder->where('m.c_status', 'D');
        }

        $builder->orderBy('IF(h.brd_prnt = "NA", 2, 1)');
        $builder->orderBy('h.brd_slno');
        $builder->orderBy('IF(m.conn_key = m.diary_no, "0000-00-00", 99) ASC');
        $builder->orderBy('IF(ct.ent_dt IS NOT NULL, ct.ent_dt, 999) ASC');
        $builder->orderBy('CAST(SUBSTRING(m.diary_no, -4) AS SIGNED) ASC');
        $builder->orderBy('CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS SIGNED) ASC');

        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
          }
        }
    }

    public function getCaseType(){
        $builder = $this->db->table("master.casetype");
        $builder->select("casecode, skey, casename,short_description");
        $builder->where("display","Y");
        $builder->where("casecode != 9999");
        $query =$builder->get();

        if($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        }else{
            return [];
        }   
    


}