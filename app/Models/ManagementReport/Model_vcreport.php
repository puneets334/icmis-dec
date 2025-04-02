<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;

class Model_vcreport extends Model
{
    protected $db;
	
    public function __construct(){
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
	
	public function getMainConnTakenup_matters($start_date, $end_date){
		$builder1 = $this->db->table('heardt h')
			->select('h.diary_no, 
					CASE 
						WHEN CAST(m.diary_no AS TEXT) = m.conn_key OR m.conn_key = \'\' OR m.conn_key IS NULL OR m.conn_key = \'0\' 
						THEN \'Main\' 
						ELSE \'Connected\' 
					END as main_connected, 
					CASE 
						WHEN m.c_status = \'P\' THEN \'Pending\' 
						ELSE \'Disposed\' 
					END as case_status,
					h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, 
					m.reg_no_display, h.board_type')
			->join('main m', 'm.diary_no = h.diary_no')
			->where('h.next_dt >=', $start_date)
			->where('h.next_dt <=', $end_date)
			->whereIn('h.main_supp_flag', [1, 2]);

		$builder2 = $this->db->table('last_heardt h')
			->select('h.diary_no, 
					CASE 
						WHEN CAST(m.diary_no AS TEXT) = m.conn_key OR m.conn_key = \'\' OR m.conn_key IS NULL OR m.conn_key = \'0\' 
						THEN \'Main\' 
						ELSE \'Connected\' 
					END as main_connected, 
					CASE 
						WHEN m.c_status = \'P\' THEN \'Pending\' 
						ELSE \'Disposed\' 
					END as case_status,
					h.next_dt, h.mainhead, h.brd_slno, h.clno, h.roster_id, h.judges, h.main_supp_flag, 
					m.reg_no_display, h.board_type')
			->join('main m', 'm.diary_no = h.diary_no')
			->where('h.next_dt >=', $start_date)
			->where('h.next_dt <=', $end_date)
			->whereIn('h.main_supp_flag', [1, 2])
			->whereIn('h.bench_flag', ['', null]);

		$subquery = $builder1->union($builder2)->getCompiledSelect();

		$finalQuery = $this->db->table("($subquery) a")
			->select('board_type, main_connected, COUNT(1) as total')
			->join('cl_printed p', 'p.next_dt = a.next_dt AND p.m_f = a.mainhead AND p.part = a.clno AND p.roster_id = a.roster_id AND p.display = \'Y\'')
			->where('p.next_dt IS NOT NULL')
			->where('a.board_type', 'J')
			->groupBy('board_type, main_connected')
			->orderBy('main_connected');

		$query = $finalQuery->get();
		return $query->getResultArray();
    }
	
	
	public function getMainConnDisposal_matters($start_date, $end_date){
			$builder = $this->db->table('dispose d')
			->select("h.board_type, 
				CASE 
					WHEN (CAST(m.diary_no AS TEXT) = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
					THEN 'Main' 
					ELSE 'Connected' 
				END AS main_connected, 
				COUNT(*) AS total", false) // false prevents escaping special SQL functions
			->join('main m', 'd.diary_no = m.diary_no AND m.c_status = \'D\' AND d.ord_dt BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'')
			->join('heardt h', 'm.diary_no = h.diary_no')
			->where('h.board_type', 'J')
			->groupBy('h.board_type, main_connected')
			->orderBy('main_connected');

			$query = $builder->get();
			return $query->getResultArray();
	}
	
	public function get_total_judgment($start_date, $end_date){
		$builder = $this->db->table('ordernet')
			->select('COUNT(*) as total')
			->where('orderdate >=', $start_date)
			->where('orderdate <=', $end_date)
			->where('type', 'J');

		$query = $builder->get();
		return $query->getRowArray();
	}
	  
	
	public function get_IA_disposed($start_date, $end_date){
		$builder = $this->db->table('docdetails')
			->select('COUNT(*) as total')
			->where('dispose_date >=', $start_date)
			->where('dispose_date <=', $end_date)
			->where('doccode', '8')
			->where('iastat', 'D');

		$query = $builder->get();
		return $query->getRowArray();
	}
	
	public function get_MA_disposed($start_date, $end_date){
		$builder = $this->db->table('dispose d')
		           ->select('count(*) as total')
				   ->join('main m', 'd.diary_no = m.diary_no AND m.c_status = \'D\' AND d.ord_dt BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'')
		           ->join('heardt h', 'm.diary_no = h.diary_no')
				   ->where('m.casetype_id', '39');
		$query = 	$builder->get();
		return $query->getRowArray();
		
	}
	
	public function get_SLP_Appeals_disposed($start_date, $end_date){
		$builder = $this->db->table('dispose d')
		           ->select('count(*) as total')
				   ->join('main m', 'd.diary_no = m.diary_no AND m.c_status = \'D\' AND d.ord_dt BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'')
		           ->join('heardt h', 'm.diary_no = h.diary_no')
				   ->whereIn('m.casetype_id', [1,2,3,4]);
		$query = 	$builder->get();
		return $query->getRowArray();
	}
	
	public function get_Writ_Petitions_disposed($start_date, $end_date){
		$builder = $this->db->table('dispose d')
		           ->select('count(*) as total')
				   ->join('main m', 'd.diary_no = m.diary_no AND m.c_status = \'D\' AND d.ord_dt BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'')
		           ->join('heardt h', 'm.diary_no = h.diary_no')
				   ->whereIn('m.casetype_id', [5,6]);
		$query = 	$builder->get();
		return $query->getRowArray();
	}
	
	public function get_Transfer_Petitions_disposed($start_date, $end_date){
		$builder = $this->db->table('dispose d')
		           ->select('count(*) as total')
				   ->join('main m', 'd.diary_no = m.diary_no AND m.c_status = \'D\' AND d.ord_dt BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'')
		           ->join('heardt h', 'm.diary_no = h.diary_no')
				   ->whereIn('m.casetype_id', [7,8]);
		$query = 	$builder->get();
		return $query->getRowArray();
	}
	
	public function get_total_filed($start_date, $end_date){
		$builder = $this->db->table('main')
		           ->select('count(*) as total')
				   ->where('diary_no_rec_date>=', $start_date)
				   ->where('diary_no_rec_date<=', $end_date);
		$query = 	$builder->get();
		return $query->getRowArray();
	}
	
	public function get_filing_SLP_Appeals($start_date, $end_date){
		$builder = $this->db->table('main')
				->distinct()
				->select('COUNT(diary_no) as total')
				->where('diary_no_rec_date >=', $start_date)
				->where('diary_no_rec_date <=', $end_date)
				->groupStart() 
					->whereIn('casetype_id', [1, 2, 3, 4])
					->orWhereIn('active_casetype_id', [1, 2, 3, 4])
				->groupEnd();

			$query = $builder->get();
			return $query->getRowArray();
	}
	
	public function get_filing_IA($start_date, $end_date){
		$builder = $this->db->table('docdetails')
			->select('COUNT(*) as total')
			->where('ent_dt >=', $start_date)
			->where('ent_dt <=', $end_date)
			->where('doccode', '8')
			->where('display', 'Y');

		$query = $builder->get();
		return $query->getRowArray();
	}
	
	
	public function get_filing_MA($start_date, $end_date){
		$builder = $this->db->table('main')
			->select('COUNT(*) as total')
			->where('diary_no_rec_date >=', $start_date)
			->where('diary_no_rec_date <=', $end_date)
			->where('casetype_id', '39');
			

		$query = $builder->get();
		return $query->getRowArray();
		
	}
	
	
	
	
}
