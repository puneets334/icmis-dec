<?php

namespace App\Models\Reports\Listing;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class ReportModel extends Model
{

    public function __construct(){
        parent::__construct();
    }

    
    /**
     * To get cases to be listed in Chamber Judge
     */

    Public function chamber_judge_cases() 
    {
      $builder = $this->db->table('obj_save a');
      $builder->selectMin('date(a.save_dt)', 'save_dt')
      ->select('a.diary_no')
      ->select("CURRENT_DATE - date(a.save_dt) AS diff_days")
      ->join('main b', 'a.diary_no = b.diary_no')
      ->where('a.rm_dt IS NULL')
      ->where('a.display', 'Y')
      ->where('CURRENT_DATE - date(a.save_dt) >', 90)
      ->where('b.c_status', 'P')
      ->where('b.active_fil_no', '')
      ->groupBy('a.diary_no,a.save_dt');
      $query = $builder->get();
      $results = $query->getResultArray();
      return $results;
    }

     /**
     * To get count of fresh cases never listed report
     */
    public function fresh_cases_never_listed_report_action($from_date, $to_date)
    {
        if(!empty($from_date) && !empty($to_date)) {
            $from_date = date("Y-m-d", strtotime($from_date));
            $to_date = date("Y-m-d", strtotime($to_date));

            $builder = $this->db->table('main m');
            $subQuery = $this->db->table('heardt h')
            ->select('h.diary_no, h.next_dt, h.mainhead, h.clno, h.roster_id')
            ->where('h.clno >', 0)
            ->where('h.board_type', 'J')
            ->union(
                $this->db->table('last_heardt h')
                    ->select('h.diary_no, h.next_dt, h.mainhead, h.clno, h.roster_id')
                    ->where('h.clno >', 0)
                    ->where('h.board_type', 'J')
                    ->where('h.bench_flag', '')
                    ->orWhere('h.bench_flag IS NULL')
            );
            
            $builder->select('COUNT(DISTINCT m.diary_no) AS Diary_cnt, STRING_AGG(m.diary_no::text, \', \') AS diary_nos')
            ->join('(' . $subQuery->getCompiledSelect() . ') l', 'm.diary_no = l.diary_no', 'left')
            ->join('heardt h', 'h.diary_no = m.diary_no')
            ->join('cl_printed p', 'p.next_dt = l.next_dt AND p.part = l.clno AND p.roster_id = l.roster_id AND p.display = \'Y\'', 'left')
            ->where('m.c_status', 'P')
            ->where('l.diary_no IS NULL')
            ->where('m.mf_active', 'M')
            ->where('h.mainhead', 'M')
            ->where('h.board_type', 'J')
            ->whereIn('h.main_supp_flag', [0, 3])
            ->whereNotIn('h.subhead', [813, 814])
            ->where('DATE(m.diary_no_rec_date) BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'')
            ->where('h.next_dt IS NOT NULL')
            ->groupStart()
                ->where('CAST(m.conn_key AS TEXT) = CAST(m.conn_key AS TEXT)')
                ->orWhere('CAST(m.conn_key AS TEXT)', '0')
                ->orWhere('m.conn_key IS NULL')
            ->groupEnd();
            // echo $builder->getCompiledSelect();
            // die();
            return $builder->get()->getRowArray();
        }    
    }

    /**
     * To get fresh cases never listed report
     */
    public function display_cases($diary_numbers)
    {
        $arr_result = [];
        if(!empty($diary_numbers)) {
            $diaryNumbersArray = explode(',', $diary_numbers);
            $subQuery = $this->db->table('main m')
                                ->select('h.next_dt, 
                                    dv.verification_date, 
                                    COALESCE(dv.verification_date, 
                                            m.active_fil_dt, 
                                            m.diary_no_rec_date) AS fresh_case_order_by,
                                    m.diary_no, 
                                    m.reg_no_display, 
                                    m.pet_name, 
                                    m.res_name, 
                                    m.diary_no_rec_date, 
                                    m.active_fil_dt, 
                                    m.conn_key, 
                                    CASE 
                                        WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != \'\' AND s.category_sc_old::int != 0) 
                                        THEN CONCAT(\'(\', s.category_sc_old, \')\', s.sub_name1, \'-\', s.sub_name4) 
                                        ELSE CONCAT(\'(\', CONCAT(s.subcode1, \'\', s.subcode2), \')\', s.sub_name1, \'-\', s.sub_name4) 
                                    END AS CATEGORY,
                                    STRING_AGG(DISTINCT h.coram, \', \') AS coram')
            
                ->join('heardt h', 'm.diary_no = h.diary_no')
                ->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'')
                ->join('master.submaster s', 's.id = mc.submaster_id AND s.display = \'Y\'', 'inner')
                ->join('defects_verification dv', 'dv.diary_no = m.diary_no', 'left')
                ->where('m.c_status', 'P')
                ->whereIn('m.diary_no', $diaryNumbersArray)
                //->where("m.diary_no = CAST(m.conn_key AS BIGINT) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL")
                ->groupBy('m.diary_no, h.next_dt, dv.verification_date, m.active_fil_dt, m.diary_no_rec_date, s.category_sc_old, s.sub_name1, s.sub_name4, s.subcode1, s.subcode2')
                ->orderBy('fresh_case_order_by');
           
            $builder = $this->db->table("({$subQuery->getCompiledSelect()}) AS c");
            $builder->select('ROW_NUMBER() OVER () AS SNO, 
                            CONCAT(c.reg_no_display, \' @ \', c.diary_no) AS Case_NO, 
                            CONCAT(c.pet_name, \' Vs. \', c.res_name) AS Cause_Title, 
                            TO_CHAR(c.diary_no_rec_date, \'DD-MM-YYYY\') AS Diary_Date, 
                            TO_CHAR(c.verification_date, \'DD-MM-YYYY\') AS Verification_Date,
                            TO_CHAR(c.next_dt, \'DD-MM-YYYY\') AS Tentative_Listing_Date, 
                            c.category, 
                            c.coram');
            //echo $builder->getCompiledSelect();
            $query = $builder->get();
            

            //echo $finalQuery->getCompiledSelect();
          // $query = $finalQuery->get();
            foreach ($query->getResultArray() as $row_data_dis) {
                $arr_result[] = [
                    'SNO' => $row_data_dis['sno'],
                    'Case_NO' => $row_data_dis['case_no'],
                    'Cause_Title' => $row_data_dis['cause_title'],
                    'Diary_Date' => $row_data_dis['diary_date'],
                    'Verification_Date' => $row_data_dis['verification_date'],
                    'Tentative_Listing_Date' => $row_data_dis['tentative_listing_date'],
                    'Category' => $row_data_dis['category'],
                    'Coram' => $row_data_dis['coram']
                ];
            }
        }   
        echo json_encode($arr_result); 
        //print_r($this->db->last_query());
        //echo $subQuery->getCompiledSelect(); 
    }


    public function get_categories()
    {
        $builder = $this->db->table('master.submaster');
        $builder->select('id, sub_name1, sub_name4');
        $builder->where('display', 'Y');
        $builder->where('flag', 's');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;

    }
    public function listed_bench_matters($data)
    {
            $from_dt = date('Y-m-d', strtotime($data['from_dt']));
            $to_dt = date('Y-m-d', strtotime($data['to_dt']));
            
            $mainhead = explode(',', $data['mainhead']);
            $board_type = explode(',', $data['board_type']);
            $benches = $data['benches'];
            $category = $data['category'];

            $subQuery1 = $this->db->table('heardt h')
                ->select('h.diary_no, h.next_dt, h.mainhead, h.board_type, h.roster_id, h.judges, h.clno')
                ->whereIn('h.mainhead', $mainhead)
                ->whereIn('h.board_type', $board_type)
                ->where('h.next_dt >=', $from_dt)
                ->where('h.next_dt <=', $to_dt)
                ->whereIn('h.main_supp_flag', [1, 2]);
    
            if ($benches !== 'all') {
                $subQuery1->where('CHAR_LENGTH(h.judges) - CHAR_LENGTH(REPLACE(h.judges, \',\', \'\')) + 1', $benches)
                           ->where('h.judges !=', '0');
            }
            
            $subQuery2 = $this->db->table('last_heardt h')
                ->select('h.diary_no, h.next_dt, h.mainhead, h.board_type, h.roster_id, h.judges, h.clno')
                ->whereIn('h.mainhead', $mainhead)
                ->whereIn('h.board_type', $board_type)
                ->where('h.next_dt >=', $from_dt)
                ->where('h.next_dt <=', $to_dt)
                ->whereIn('h.main_supp_flag', [1, 2])
                ->groupStart()
                ->where('h.bench_flag', '')
                ->orWhere('h.bench_flag IS NULL')
                ->groupEnd();
    
            if ($benches !== 'all') {
                $subQuery2->where('CHAR_LENGTH(h.judges) - CHAR_LENGTH(REPLACE(h.judges, \',\', \'\')) + 1', $benches)
                           ->where('h.judges !=', '0');  // Compare with string '0'
            }
    
            // Compile the subqueries and combine with UNION
            $query1 = $subQuery1->getCompiledSelect();
            $query2 = $subQuery2->getCompiledSelect();
            $finalQuery = "($query1 UNION $query2) AS h";
    
            // Prepare to join with cl_printed
            $finalBuilder = $this->db->table('cl_printed p')
                ->select('COUNT(p.next_dt) as total')
                ->join($finalQuery, 'p.next_dt = h.next_dt AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'inner');
    
            if ($category !== '') {
                $finalBuilder->join('mul_category mc', 'mc.diary_no = h.diary_no', 'inner')
                             ->where('mc.submaster_id', $category)
                             ->where('mc.display', 'Y');
            }
            //pr($finalBuilder->getCompiledSelect());

            // Execute the final query and retrieve the result
            $result = $finalBuilder->get()->getRowArray();
            $total_rows = isset($result['total']) && $result['total'] > 0
                ? '<a href="javascript:void(0)" id="display_records"><h3>Total ' . intval($result['total']) . '</h3></a>'
                : '<h3>No Records Found</h3>';
            return $total_rows;
    }


    public function listed_bench_matters_action($data){
        $from_dt = date('Y-m-d', strtotime($data['from_dt']));
        $to_dt = date('Y-m-d', strtotime($data['to_dt']));
        
        // Prepare the mainhead and board_type arrays from POST
        $mainhead = explode(',', $data['mainhead']);
        $board_type = explode(',', $data['board_type']);
        $benches = $data['benches'];
        $category = $data['category'];
        $tbl_sub = $data['tbl_sub'];


        if(!empty($tbl_sub)) {
            
                 // Subquery for heardt
                $subQuery1 = $this->db->table('heardt h')
                ->select('h.diary_no, h.next_dt, h.mainhead, h.board_type, h.roster_id, h.judges, h.clno')
                ->whereIn('h.mainhead', $mainhead)
                ->whereIn('h.board_type', $board_type)
                ->where('h.next_dt >=', $from_dt)
                ->where('h.next_dt <=', $to_dt)
                ->whereIn('h.main_supp_flag', [1, 2]);

            if ($benches !== 'all') {
                $subQuery1->groupStart()
                        ->where('CHAR_LENGTH(h.judges) - CHAR_LENGTH(REPLACE(h.judges, \',\', \'\')) + 1', $benches)
                        ->where('h.judges !=', '0')
                        ->groupEnd();
            }

            // Subquery for last_heardt
            $subQuery2 = $this->db->table('last_heardt h')
                ->select('h.diary_no, h.next_dt, h.mainhead, h.board_type, h.roster_id, h.judges, h.clno')
                ->whereIn('h.mainhead', $mainhead)
                ->whereIn('h.board_type', $board_type)
                ->where('h.next_dt >=', $from_dt)
                ->where('h.next_dt <=', $to_dt)
                ->whereIn('h.main_supp_flag', [1, 2])
                ->groupStart()
                ->where('h.bench_flag', '')
                ->orWhere('h.bench_flag IS NULL')
                ->groupEnd();

            if ($benches !== 'all') {
                $subQuery2->groupStart()
                        ->where('CHAR_LENGTH(h.judges) - CHAR_LENGTH(REPLACE(h.judges, \',\', \'\')) + 1', $benches)
                        ->where('h.judges !=', '0')
                        ->groupEnd();
            }

            // Combine both subqueries
            $subQuery1Sql = $subQuery1->getCompiledSelect();
            $subQuery2Sql = $subQuery2->getCompiledSelect();
            $finalSubQuery = "$subQuery1Sql UNION $subQuery2Sql";

            // Main query
            //$builder = $this->db->table("($finalSubQuery) h")
                //->select('p.next_dt, h.diary_no, h.mainhead, h.board_type, h.judges, m.reg_no_display, m.pet_name, m.res_name, m.c_status');

                $builder = $this->db->table("($finalSubQuery) h")
                                ->select('h.diary_no, h.next_dt, 
                                        MAX(h.mainhead) AS mainhead, 
                                        MAX(h.board_type) AS board_type, 
                                        MAX(h.judges) AS judges, 
                                        MAX(m.reg_no_display) AS reg_no_display, 
                                        MAX(m.pet_name) AS pet_name, 
                                        MAX(m.res_name) AS res_name, 
                                        MAX(m.c_status) AS c_status');

            /*if (empty($category)) {
                $builder->select('s.sub_name1, s.sub_name4');
                $builder->groupBy('sub_name1,sub_name4');
            }*/
            if (empty($category)) {
                $builder->select('MAX(s.sub_name1) AS sub_name1, MAX(s.sub_name4) AS sub_name4');
            }
            

            $builder->join('cl_printed p', 'p.next_dt = h.next_dt AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = \'Y\'', 'inner')
                    ->join('main m', 'm.diary_no = h.diary_no', 'inner');

            if ($category !== '') {
                $builder->join('mul_category mc', 'mc.diary_no = h.diary_no')
                        ->where('mc.submaster_id', $category)
                        ->where('mc.display', 'Y');
            } else {
                $builder->join('mul_category mc', 'mc.diary_no = h.diary_no AND mc.display = \'Y\'', 'left')
                        ->join('master.submaster s', 's.id = mc.submaster_id', 'left');
            }

            // Ensure all selected columns are included in GROUP BY
            //$builder->groupBy('h.diary_no, h.next_dt, h.mainhead, h.board_type, h.judges, m.reg_no_display, m.pet_name, m.res_name, m.c_status,p.next_dt');
            $builder->groupBy('h.diary_no, h.next_dt');
            //$builder->groupBy('sub_name1,sub_name4');
            //pr($builder->getCompiledSelect());

            $query = $builder->get();
            
            // Execute the query
            //$result = $query->getResultArray();
            //pr($category);

            foreach ($query->getResultArray() as $key => $row_data) {
                $c_status = $get_category = $board_type = $mainhead = '';
                if($row_data['mainhead']=='M'){ 
                    $mainhead = 'Miscellaneous'; 
                }elseif($row_data['mainhead']=='F') { 
                    $mainhead = 'Regular'; 
                }

                if($row_data['board_type']=='J'){
                    $board_type = 'Court'; 
                }elseif($row_data['board_type']=='S'){
                     $board_type ='Single judge'; 
                }elseif($row_data['board_type']=='R'){ 
                    $board_type = 'Registrar'; 
                }elseif($row_data['board_type']=='C'){ 
                    $board_type = 'Chamber'; 
                }

                
                if($category != ''){
                    $cat = $this->get_category_by_id($category);
                    $get_category = $cat['sub_name1'].' - '.$cat['sub_name4'];
                } else {
                    $get_category = $row_data['sub_name1'].' - '.$row_data['sub_name4'];
                }

                if ($row_data['c_status'] == 'D') {
                    $c_status = 'Disposed';
                } elseif ($row_data['c_status'] == 'P') {
                    $c_status =  'Pending';
                }
                                

                $arr_result[] = [
                    'SNO' => $key+1,
                    'Case_NO' => $row_data['reg_no_display'].' @ '.$row_data['diary_no'],
                    'Cause_Title' => $row_data['pet_name'].', '.$row_data['res_name'],
                    'Listed_Date' => date('d-m-Y',strtotime($row_data['next_dt'])),
                    'Mainhead' =>  $mainhead,
                    'Board_Type' => $board_type,
                    'Bench_Count' => $row_data['judges'],
                    'Category' => $get_category,
                    'Case_Status' => $c_status
                ];
            }
        }   
        echo json_encode($arr_result); 
    }


    public function get_category_by_id($id)
    {
        $builder = $this->db->table('master.submaster');
        $builder->select('id, sub_name1, sub_name4');
        $builder->where('id', $id);
        $builder->where('display', 'Y');
        $builder->where('flag', 's');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;

    }


	
	
	public function getRosterRecord($mf='')
	{
		 
		$builder = $this->db->table('master.roster');

		// Select the required fields
		$builder->select('tot_cases, roster.id, roster.bench_id, roster.from_date, 
			(SELECT STRING_AGG(judge_id::text, \',\' ORDER BY judge_seniority) 
			 FROM master.roster_judge r_j 
			 JOIN master.judge jud ON r_j.judge_id = jud.jcode 
			 AND jud.display = \'Y\' 
			 WHERE r_j.roster_id = roster.id) AS judge_id, 
			master_bench.bench_name, 
			master_bench.abbr, 
			roster_bench.bench_no, 
			STRING_AGG(stage_code::text, \',\' ORDER BY ca.priority) AS sc, 
			STRING_AGG(stage_nature::text, \',\' ORDER BY ca.priority) AS stage_nature, 
			STRING_AGG(case_type::text, \',\' ORDER BY ca.priority) AS case_type, 
			STRING_AGG(submaster_id::text, \',\' ORDER BY ca.priority) AS submaster_id, 
			STRING_AGG(b_n::text, \',\' ORDER BY ca.priority) AS b_n, 
			roster.to_date, m_f, session, frm_time, entry_dt, courtno, if_print_in');

		// Join the necessary tables
		$builder->join('master.roster_bench', 'roster_bench.id = roster.bench_id AND roster.display = \'Y\'');
		$builder->join('master.master_bench', 'master_bench.id = roster_bench.bench_id AND roster_bench.display = \'Y\'', 'left');
		$builder->join('category_allottment ca', 'ca.ros_id = roster.id');

		// Add the where conditions
		$builder->where('roster.to_date IS NULL')
		   ->orWhere('CURRENT_DATE - INTERVAL \'15 days\' <= roster.to_date');
		$builder->where('roster.display', 'Y')
		   ->where('ca.display', 'Y');
			   
		if(!empty($mf))
		{
			$builder->where('roster.m_f', $mf);
		}			
			 

		$builder->groupBy('roster.id, roster.bench_id, roster.from_date, 
			master_bench.bench_name, master_bench.abbr, 
			roster_bench.bench_no, roster_bench.priority,  
			roster.to_date, m_f, session, frm_time, entry_dt, courtno, if_print_in');

		// Order by the specified columns
		$builder->orderBy('master_bench.abbr')
			   ->orderBy('roster_bench.priority');
// pr($builder->getCompiledSelect());
		// Get the results
		$query = $builder->get();
		 
		return $result = $query->getResultArray();

		// You can now use $result as needed

	}

  public function getCategories($hd_roster_id){
    $catQry = "SELECT b_n, stage_code,stage_nature, priority, case_type, submaster_id, m_f, bench_id, id FROM category_allottment a 
        JOIN master.roster b ON a.ros_id = b.id AND b.display='Y' WHERE a.display='Y' AND ros_id = '$hd_roster_id'";
    $query = $this->db->query($catQry);	
		return $result = 	$query->getResultArray();
  }

  public function getSubHeading($stage_code){
    $sql_matr = "SELECT stagename FROM master.subheading WHERE stagecode = '$stage_code' AND display='Y'";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }

  public function getCasetypeKey($casetype){
    $sql_matr = "SELECT skey FROM master.casetype WHERE display='Y' AND casecode='$casetype'";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }

  public function getSubMaster($ex_submaster_id){
    $sql_matr = "SELECT sub_name1, sub_name2, sub_name3, sub_name4, category_sc_old FROM master.submaster WHERE display = 'Y' AND  id = '$ex_submaster_id'";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }

  public function getCatSubMaster($catId){
    if($catId != ''){
      $sql_matr = "SELECT * FROM master.submaster WHERE subcode1 = (SELECT subcode1 FROM master.submaster WHERE id = '$catId' AND display = 'Y') AND     subcode2 = (SELECT subcode2 FROM master.submaster where id='$catId' AND display = 'Y') AND subcode2 != '0'  AND subcode3 != '0' AND subcode4 = '0' AND display = 'Y' ORDER BY subcode1, subcode2, subcode3, subcode4";
      $query = $this->db->query($sql_matr);	
		  return $result = 	$query->getResultArray();
    }else{
      return [];
    }
  }

  public function getSubCatSubMaster($catId){
    if($catId != ''){
      $sql_matr = "SELECT * FROM master.submaster WHERE subcode1 = (SELECT subcode1 FROM master.submaster WHERE id = '$catId' AND display = 'Y') AND subcode2 = (SELECT subcode2 FROM master.submaster WHERE id = '$catId' AND display = 'Y') AND subcode3 = (SELECT subcode3 FROM master.submaster WHERE id = '$catId' and display = 'Y') AND subcode4 != 0 AND display = 'Y' ORDER BY subcode1,subcode2, subcode3, subcode4";

      $query = $this->db->query($sql_matr);	
		  return $result = 	$query->getResultArray();
    }else{
      return [];
    }
    
  }

  public function getRosterDetails($btnroster){
    $sql_matr = "SELECT bench_id, m_f, from_date, session, frm_time, tot_cases, courtno FROM master.roster WHERE id = '$btnroster' AND display = 'Y'";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }

  public function countRosterDetail($bench_names, $res_sql_search){
    $sql_matr = "SELECT COUNT(*) AS count FROM master.roster WHERE bench_id = '$bench_names' AND m_f = '$res_sql_search[m_f]' AND display = 'Y' AND (
        (to_date IS NULL AND from_date = '$res_sql_search[from_date]') OR ('$res_sql_search[from_date]' BETWEEN from_date AND to_date))";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }

  public function countRosterDetails($rostData, $date){
    $sql_matr = "SELECT COUNT(*) FROM master.roster WHERE bench_id = '".$rostData['bench_id']."' AND m_f = '".$rostData['m_f']."' AND display = 'Y' AND from_date > '".$rostData['from_date']."' AND from_date <= '".$date."'";
    $query = $this->db->query($sql_matr);	
		return $result = 	$query->getResultArray();
  }


  public function rosterDetails($id){
    $rosterQuery = "SELECT b.bench_id,m_f FROM master.roster a, master.roster_bench b WHERE a.id = '$id' AND a.bench_id = b.id and a.display='Y'";
    $query = $this->db->query($rosterQuery);	
		return $result = 	$query->getResultArray();
  }


  public function getCountDiary($btnroster){
    $diaryQuery = "SELECT COUNT(diary_no) FROM heardt WHERE roster_id = '$btnroster'";
    $query = $this->db->query($diaryQuery);	
		return $result = 	$query->getResultArray();
  }


  public function getStageDetails($rid){
    $stgQuery = "SELECT 
                STRING_AGG(stage_code::text, ',' ORDER BY ca.priority) AS stage_code,
                STRING_AGG(ca.priority::text, ',' ORDER BY ca.priority) AS priority,
                STRING_AGG(stage_nature::text, ',' ORDER BY ca.priority) AS stage_nature,
                STRING_AGG(case_type::text, ',' ORDER BY ca.priority) AS case_type,
                STRING_AGG(submaster_id::text, ',' ORDER BY ca.priority) AS submaster_id,
                STRING_AGG(b_n::text, ',' ORDER BY ca.priority) AS b_n
                FROM category_allottment ca
                WHERE ca.display = 'Y' 
                AND ros_id = '$rid'
                GROUP BY ros_id";
    $query = $this->db->query($stgQuery);
		return $result = 	$query->getResultArray();
  }

  public function gatCaseType(){
    $caseQuery = "SELECT distinct nature from master.casetype where display='Y' order by nature";
    $query = $this->db->query($caseQuery);	
		return $result = 	$query->getResultArray();
  }

  public function gatSubMaster(){
    $caseQuery = "SELECT id, sub_name1, flag, category_sc_old  FROM master.submaster WHERE display = 'Y' AND subcode2 = '0' AND 
            subcode3 = '0' AND subcode4 = '0' ORDER BY subcode1, subcode2, subcode3, subcode4";
    $query = $this->db->query($caseQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getRosterBench($benchId){
    $rosterQuery = "SELECT id, bench_id FROM master.roster_bench WHERE id = '$benchId' AND display='Y' ";
    $query = $this->db->query($rosterQuery);	
		return $result = 	$query->getResultArray();
  }

  public function newRosterBench($benchId, $btnid){
    $rosterQuery = "SELECT id, bench_id,bench_no FROM master.roster_bench WHERE id != '$benchId' AND bench_id = '$btnid' AND display = 'Y' ORDER BY priority";
    $query = $this->db->query($rosterQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getMasterBench($benchId = ''){
    $whr = '';
    if($benchId != ''){
      $whr = " AND id = '$benchId'";
    }
    $onlyQuery = "SELECT id, bench_name FROM master.master_bench WHERE display='Y' ".$whr;
    $query = $this->db->query($onlyQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getJudgeDetils($btnroster){
    $onlyQuery = "SELECT judge_id FROM master.roster_judge WHERE roster_id = '$btnroster'";
    $query = $this->db->query($onlyQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getJudgeCourt($courtno1){
    $onlyQuery = "SELECT jcourt FROM master.judge WHERE jcode ='$courtno1' AND (to_dt IS NULL OR to_dt >= CURRENT_DATE) AND display = 'Y'";
    $query = $this->db->query($onlyQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getRosterData($bench_names, $m_f, $from_date, ){
    $onlyQuery = "SELECT from_date, to_date FROM master.roster WHERE bench_id = '$bench_names' AND m_f = '$m_f' AND display = 'Y' AND ((to_date IS NULL AND from_date = '$from_date') OR ('$from_date' BETWEEN from_date AND to_date))";
    $query = $this->db->query($onlyQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getRoterMaxId(){
    $onlyQuery = "SELECT max(id) AS id FROM master.roster";
    $query = $this->db->query($onlyQuery);	
		return $result = 	$query->getResultArray();
  }

  public function getJcodeNewOne(){
    $sq_y = "SELECT jcode,
                LTRIM(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        jname,
                                                        '\\', ''
                                                    ),
                                                    'HON''BLE SHRI JUSTICE', ''
                                                ),
                                                'HON''BLE MR. JUSTICE', ''
                                            ),
                                            'Hon''ble Shri Justice', ''
                                        ),
                                        'HON''BLE SMT. JUSTICE', ''
                                    ),
                                    'HON''BLE MRS.', ''
                                ),
                                'HON''BLE SHRI', ''
                            ),
                            'HON''BLE SHRI', ''
                        ),
                        'HON''BLE ', ''
                    )
                ) AS jname
            FROM master.judge
            WHERE display = 'Y'
              AND (
                  to_dt IS NULL OR to_dt >= CURRENT_DATE
              )
              AND is_retired = 'N'
            ORDER BY judge_seniority";

      $query = $this->db->query($sq_y);	
      return	$query->getResultArray();
  }

  public function getJcodeOnes(){
    $sq_y = "SELECT 
          jcode,
          LTRIM(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(
                    REPLACE(
                      REPLACE(
                        REPLACE(
                          REPLACE(
                            REPLACE(jname, E'\\\\', ''),
                            E'HON\\\\\'BLE SHRI JUSTICE', ''
                          ),
                          E'HON\\\\\'BLE MR. JUSTICE', ''
                        ),
                        E'Hon\\\\\'ble Shri Justice', ''
                      ),
                      E'HON\\\\\'BLE SMT. JUSTICE', ''
                    ),
                    E'HON\\\\\'BLE MRS.', ''
                  ),
                  E'HON\\\\\'BLE SHRI', ''
                ),
                E'HON\\\\\'BLE SHRI', ''
              ),
              E'HON\\\\\'BLE', ''
            )
          ) AS jname
        FROM master.judge
        WHERE display = 'Y' 
          AND is_retired = 'N'
          AND (
            to_dt IS NULL OR to_dt >= CURRENT_DATE
          )
          OR (
            jcode >= 9001 
            AND jcode != 9010 
            AND jcode != 9011 
            AND jcode != 9012 
            AND jcode != 9013
          )
        ORDER BY judge_seniority";
      $query = $this->db->query($sq_y);	
      return	$query->getResultArray();
  }
	
	
	public function getJcodeOne()
	{
		$sq_y = "SELECT 
		  jcode,
		  LTRIM(
		--    REPLACE(
			  REPLACE(
				REPLACE(
				  REPLACE(
					REPLACE(
					  REPLACE(
						REPLACE(
						  REPLACE(
							REPLACE(
							  CASE 
								WHEN jtype = 'R' 
								THEN CONCAT(first_name, ' ', sur_name, ' ', jname) 
								ELSE jname 
							  END,
							  '\\', ''
							),
							'HON''BLE SHRI JUSTICE', ''
						  ),
						  'HON''BLE MR. JUSTICE', ''
						),
						'Hon''ble Shri Justice', ''
					  ),
					  'HON''BLE SMT. JUSTICE', ''
					),
					'HON''BLE MRS.', ''
				  ),
				  'HON''BLE SHRI', ''
				),
				'HON''BLE ', ''
			  )
			-- )
		  ) AS jname
		FROM 
		  master.judge
		WHERE 
		  display = 'Y' 
		  AND (to_dt IS NULL OR to_dt >= CURRENT_DATE)
		  AND is_retired = 'N'
		ORDER BY 
		  judge_seniority";
  
		$query = $this->db->query($sq_y);	
		return $result = 	$query->getResultArray();
		
	}
	
	public function getJcodeSeven()
	{
		$sq_y = "SELECT 
		  jcode,
		  LTRIM(
		--    REPLACE(
			  REPLACE(
				REPLACE(
				  REPLACE(
					REPLACE(
					  REPLACE(
						REPLACE(
						  REPLACE(
							REPLACE(
							  CASE 
								WHEN jtype = 'R' 
								THEN CONCAT(first_name, ' ', sur_name, ' ', jname) 
								ELSE jname 
							  END,
							  '\\', ''
							),
							'HON''BLE SHRI JUSTICE', ''
						  ),
						  'HON''BLE MR. JUSTICE', ''
						),
						'Hon''ble Shri Justice', ''
					  ),
					  'HON''BLE SMT. JUSTICE', ''
					),
					'HON''BLE MRS.', ''
				  ),
				  'HON''BLE SHRI', ''
				),
				'HON''BLE ', ''
			  )
			-- )
		  ) AS jname
		FROM 
		  master.judge
		WHERE 
		  display = 'Y' 
		  AND (
				(to_dt IS NULL OR to_dt >= CURRENT_DATE) 
				OR (jcode >= 9001 AND jcode NOT IN (9010, 9011, 9012, 9013))
			) 
		  AND is_retired = 'N'
		ORDER BY 
		  judge_seniority";
  
		$query = $this->db->query($sq_y);	
		return $result = 	$query->getResultArray();
		
	}
	
	public function getJcodeEight()
	{
		$sq_y = "SELECT 
		  jcode,
		  LTRIM(
		--    REPLACE(
			  REPLACE(
				REPLACE(
				  REPLACE(
					REPLACE(
					  REPLACE(
						REPLACE(
						  REPLACE(
							REPLACE(
							  CASE 
								WHEN jtype = 'R' 
								THEN CONCAT(first_name, ' ', sur_name, ' ', jname) 
								ELSE jname 
							  END,
							  '\\', ''
							),
							'HON''BLE SHRI JUSTICE', ''
						  ),
						  'HON''BLE MR. JUSTICE', ''
						),
						'Hon''ble Shri Justice', ''
					  ),
					  'HON''BLE SMT. JUSTICE', ''
					),
					'HON''BLE MRS.', ''
				  ),
				  'HON''BLE SHRI', ''
				),
				'HON''BLE ', ''
			  )
			-- )
		  ) AS jname
		FROM 
		  master.judge
		WHERE 
		  display = 'Y'  
			AND jtype IN ('A', 'AM', 'S', 'M') 
			AND is_retired = 'N'
		ORDER BY 
		  judge_seniority";
  
		$query = $this->db->query($sq_y);	
		return $result = 	$query->getResultArray();
		
	}
	
	
	
	
		
	
	
	
}


