<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use App\Models\Reports\Listing\ReportModel;
use App\Models\Listing\Roster AS RosterModel;
use CodeIgniter\Controller;
use CodeIgniter\Model;



class Roster extends BaseController
{
	protected $ReportModel;
	protected $RosterModel;
	protected $diary_no;

	public function __construct()
	{
		$this->ReportModel = new ReportModel();
		$this->RosterModel = new RosterModel();


		// if (empty(session()->get('filing_details')['diary_no'])) {
		// 	$uri = current_url(true);
		// 	$getUrl = $uri->getSegment(3) . '-' . $uri->getSegment(4);
		// 	header('Location:' . base_url('Filing/Diary/search?page_url=' . base64_encode($getUrl)));
		// 	exit();
		// 	exit();
		// } else {
		// 	$this->diary_no = session()->get('filing_details')['diary_no'];
		// }
	}


	public function add()
	{
		$request = service('request');
		$data['app_name'] = 'Roster';
		$data['hd_ud'] = $this->request->getGet('hd_ud');
		return view('Listing/roster/roster', $data);
	}


	public function get_roster()
	{
		if (($_REQUEST['m_f'] == '0' || $_REQUEST['m_f'] == '')) {
			$data['rs'] =   $this->ReportModel->getRosterRecord();
		} else {
			$data['rs'] =   $this->ReportModel->getRosterRecord($_REQUEST['m_f']);
		}
		return view('Listing/roster/get_roster', $data);
	}

	public function get_categories() {
		$reqData = $this->request->getPost();
		$data['catData'] = $this->ReportModel->getCategories($reqData['hd_roster_id']);
		$data['ct_total'] = $reqData['sp_sp_spcatall'];
		$data['thiss'] = $this->ReportModel;
		$resData = [];

		if(count($data['catData']) > 0 ){
			return view('Listing/roster/get_categories', $data);
		}
		
	}

	public function get_benc_no()
	{
		$sql1 = is_data_from_table('master.roster_bench',  " display='Y' and bench_id=$_REQUEST[str] order by  priority ", " id,bench_no ", 'A');
		$html = '<option value="">Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $row) {
				$html .= '<option value="' . $row['id'] . '">' . $row['bench_no'] . '</option>';
			}
		}
		return $html;
	}

	public function get_jud_org()
	{
		$db = \Config\Database::connect();
		if (
			$_REQUEST['str'] == '1' || $_REQUEST['str'] == '2' || $_REQUEST['str'] == '3' || $_REQUEST['str'] == '4' || $_REQUEST['str'] == '5'
			|| $_REQUEST['str'] == '6'  || $_REQUEST['str'] == '9' || $_REQUEST['str'] == '10' || $_REQUEST['str'] == '11' || $_REQUEST['str'] == '12' || $_REQUEST['str'] == '13' || $_REQUEST['str'] == '14'
		) {
			$sql1 =	$this->ReportModel->getJcodeOne();
		} else if ($_REQUEST['str'] == '7') {
			$sql1 =	$this->ReportModel->getJcodeSeven();
		} else if ($_REQUEST['str'] == '8') {
			$sql1 =	$this->ReportModel->getJcodeEight();
		}


		$html = '<option value="" disabled>Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $row1) {
				$color = '';
				$infodata = '';
				if ($row1['jcode'] >= 9001) {
					$color = 'style="color: blue"';
				}
				//$html .= '<option value="'.$row1['jcode'].'" '.$color.'>';

				if (substr($row1['jname'], 0, 5) == 'SHRI ') {
					$infodata = str_replace('SHRI ', '',  $row1['jname']);
				} else if (substr($row1['jname'], 0, 5) == 'Shri ') {
					$infodata = str_replace('Shri ', '',  $row1['jname']);
				} else if (substr($row1['jname'], 0, 5) == 'Smt. ') {
					$infodata = str_replace('Smt. ', '',  $row1['jname']);
				} else if (substr($row1['jname'], 0, 5) == 'SMT. ') {
					$infodata = str_replace('SMT. ', '',  $row1['jname']);
				} else if (substr($row1['jname'], 0, 8) == 'JUSTICE ') {
					$infodata = str_replace('JUSTICE ', '',  $row1['jname']);
				} else {
					$infodata =  $row1['jname'];
				}

				$html .= '<option value="' . $row1['jcode'] . '" ' . $color . ' >' . $infodata . '</option>';
			}
		}
		return $html;
	}

	public function get_mot_details()
	{
		return view('Listing/roster/get_mot_details');
	}

	public function extra_cat(){
		$data = $this->request->getGet();

		$data['thiss'] = $this->RosterModel;
		$data['roastData'] = $this->RosterModel->rosterDetails($data['btnroster']);
		$data['stageData'] = $this->RosterModel->getStageDetails($data['btnroster']);

		$data['case_type'] = $this->RosterModel->gatCaseType();
		$data['sub_master'] = $this->RosterModel->gatSubMaster();

		// $data['get_mot'] = view('Listing/roster/get_mot_details1', $data);

		// print_r($data);
		return view('Listing/roster/extra_cat', $data);
	}

	public function get_sub_subcat_ans(){
		$reqData = $this->request->getGet();
		$cat = $reqData['subject'];
		
		$data['catSubMaster'] = $this->RosterModel->getSubCatSubMaster($cat);
		return view('Listing/roster/sub_subcat_ans', $data);
	}


	public function check_fr_to_dt(){
		$reqData = $this->request->getGet();
		$rost_date = $this->RosterModel->getRosterDetails($reqData['btnroster']);
		$s = 0;
		if(!empty($rost_date)){
			$en_dt = date('d-m-Y',  strtotime($rost_date['from_date']));
			if( strtotime($reqData['en_dt'])>= strtotime($en_dt)){
				$s = 2;
			}else{
				$s = 1;
			}
		}
		
		return '<input type="hidden" name="hd_frr_too_dt" id="hd_frr_too_dt" value="'.$s.'"/>';
	}

	public function close_roaster(){
		$reqData = $this->request->getGet();
		$sno = 0;
		$date = date('Y-m-d',  strtotime($reqData['ck_to_dt']));
		$rost_data = $this->RosterModel->getRosterDetails($reqData['btnroster']);
		if(!empty($rost_data)){
			$rostCount = $this->RosterModel->countRosterDetails($rost_data, $date);

			if(!empty($rostCount))
			{
				$query_update_res = $this->db->table('master.roster');
				$query_update_res->where('id', $reqData['btnroster']);
                $query_update_res->update(['to_date' => $date]);

            	if ($query_update_res) {
					$sno = 3;
				}else{
					$sno = 2;
				}
			}
		}
		return '<input type="hidden" name="hd_sno_s" id="hd_sno_s" value="'.$sno.'"/>';
	}


	public function delete_roaster(){
		$reqData = $this->request->getGet();
		$sno = 0;

		$query_update_res = $this->db->table('category_allottment');
		$query_update_res->where('display', 'Y');
		$query_update_res->where('ros_id', $reqData['btnroster']);
		$query_update_res->update(['display' => 'Z']);

		if ($query_update_res) {
			$update_res = $this->db->table('category_allottment');
			$update_res->where('display', 'Y');
			$update_res->where('ros_id', $reqData['btnroster']);
			$update_res->update(['display' => 'Z']);
			
			if ($update_res) {
				$sno = 3;
			}else{
				$sno = 2;
			}
		}else{
			$sno = 1;
		}
		return '<input type="hidden" name="hd_sno_ss" id="hd_sno_ss" value="'.$sno.'"/>';
	}


	public function check_listed_cases(){
		$reqData = $this->request->getPost();
		$res_rec = 0;
		$rost_data = $this->RosterModel->getCountDiary($reqData['btnroster']);
		if(isset($rost_data)){
			if($rost_data[0]['count'] > 0){
				$res_rec = 1;
			}
			else{
				$res_rec = 2;
			}
		}
		
		echo $res_rec;
	}

	public function get_tra_ros_det(){
		$reqData = $this->request->getGet();
		$judgeData = $this->RosterModel->getJcodeOnes();
		$judgeNewData = $this->RosterModel->getJcodeNewOne();
		$data = [];
		$jud_ids = '';
		$reqData['hd_ud'] = isset($reqData['hd_ud']) ? $reqData['hd_ud'] : '';
		$data['request'] = $reqData;
		
		foreach($judgeData as $judgeVal){
			$data['jcode_name'][] = $judgeVal['jcode'].'^'.$judgeVal['jname'];
		}

		foreach($judgeNewData as $judgeNewVal){
			$data['one'][] = $judgeNewVal['jcode'].'^'.$judgeNewVal['jname'];
		}
		
		$data['res_sql'] = $this->RosterModel->getRosterBench($reqData['btnbench_id'])[0]['bench_id'];
		$data['st_bench'] = $this->RosterModel->getMasterBench($data['res_sql'])[0]['bench_name'];
		$data['res_sq_roster'] = $this->RosterModel->getRosterDetails($reqData['btnroster']);
		$data['all_bench'] = $this->RosterModel->getMasterBench();
		$data['res_roster'] = $this->RosterModel->newRosterBench($reqData['btnbench_id'], $data['res_sql']);
		$judge = $this->RosterModel->getJudgeDetils($reqData['btnroster']);

		foreach($judge as $row2) 
		{
			if($jud_ids == '')
				$jud_ids = $row2['judge_id'];
			else
				$jud_ids = $jud_ids.','.$row2['judge_id']; 
		}

		$data['jud_ids'] = $jud_ids;

		return view('Listing/roster/get_tra_ros_det', $data);

	}


	public function transfer_roaster(){
		$reqData = $this->request->getGet();
		$sno = 0;
		$to_date = '';
		$rost_details = $this->RosterModel->getRosterDetails($reqData['btnroster']);
		
		if($rost_details['m_f'] == '2')
		{
			$to_date = $rost_details['from_date'];
		}
		else
		{
			$to_date = $rost_details['from_date'];
		}

		if($rost_details['m_f'] == '1' || $rost_details['m_f'] == '2' || $rost_details['m_f'] == '3' || $rost_details['m_f'] == '4')
		{
			$sq_bb = $this->RosterModel->getStageDetails($reqData['btnroster']);

			if(!empty($sq_bb)){
				$res_sq_bb			= $sq_bb['stage_code'];
				$ex_stage_nature 	= $sq_bb['stage_nature'];
				$ex_case_type 		= $sq_bb['case_type'];
				$ex_b_n 			= $sq_bb['b_n'];
				$ex_submaster_id 	= $sq_bb['submaster_id'];
			}else{
				$res_sq_bb			= '';
				$ex_stage_nature 	= '';
				$ex_case_type 		= '';
				$ex_b_n 			= '';
				$ex_submaster_id 	= '';
			}			
		}

		$res_sql_chks = $this->RosterModel->countRosterDetail($reqData['bench_names'], $rost_details);
		
		if($res_sql_chks['count'] <= 0)
		{
			$bench_id = $reqData['bench_names'];
			$judge_code = explode(',', $reqData['j1_j2']);
			$ct_less = '';

			for ($index = 0; $index < count($judge_code); $index++)
			{
				if($ct_less == '')
				{
					$ct_less=$judge_code[$index];
				}
				else
				{
					if($ct_less < $judge_code[$index])
					{
						$ct_less = $ct_less;
					}
					else
					{
						$ct_less = $judge_code[$index];
					}
				}
			}
			$courtno1 = $ct_less;
						
			$sq_court = $this->RosterModel->getJudgeCourt($courtno1);

			$courtno = $sq_court['jcourt'];

			if(isset($judge_code[0]))
				$judge_code1 = $judge_code[0];
			else
				$judge_code1 = '0';    
			if(isset($judge_code[1]))
				$judge_code2 = $judge_code[1];
			else
				$judge_code2 = '0';    
			if(isset($judge_code[2]))
				$judge_code3 = $judge_code[2];
			else
				$judge_code3 = '0';    
			if(isset($judge_code[3]))
				$judge_code4 = $judge_code[3];
			else
				$judge_code4 = '0';    
			if(isset($judge_code[4]))
				$judge_code5 = $judge_code[4];
			else
				$judge_code5 = '0';    

			$from_dt = $rost_details['from_date'];

			$row = $this->RosterModel->getRoterMaxId();
			
			$maxid = $row['id'] + 1;

			$sitting_dt = '';
			if($reqData['ddl_hrs'] != '')
			{
				$sitting_dt = $reqData['ddl_hrs'].':'.$reqData['ddl_min'].' '.$reqData['ddl_am_pm'];
			}

			if($reqData['ck_ct_hall'] == '1')
				$courtno = '999';
			else 
				$courtno = $reqData['txt_court_nos'];

			$data = [
				'id' => $maxid,
				'bench_id' 	=> $bench_id,
				'from_date' => "$from_dt",
				'to_date' 	=> "$to_date",
				'entry_dt' 	=> date('Y-m-d H:i:s'), // Current timestamp
				'display' 	=> 'Y',
				'courtno' 	=> "$courtno",
				'm_f' 		=> $rost_details['m_f'],
				'frm_time' 	=> "$sitting_dt",
				'tot_cases' => $reqData['txt_no_cases'],
				'session' 	=> $reqData['sesss'],
				'judges'	=> ''
			];

			$builder = $this->db->table('master.roster');
			$builder->insert($data);

			// Check for errors
			if ($this->db->affectedRows() > 0) {

				for ($j_n = 0; $j_n < count($judge_code); $j_n++)
				{
					$data1 = [
						'roster_id' => $maxid,
						'judge_id' 	=> $judge_code[$j_n]
					];
					$builder1 = $this->db->table('master.roster_judge');
					$builder1->insert($data1);
				}
				
				echo "Record Added Successfully";

				$matter_ex 			= explode(',', $res_sq_bb);
				$ex_stage_nature_e 	= explode(',', $ex_stage_nature);
				$ex_case_type_e		= explode(',', $ex_case_type);
				$ex_submaster_id_e	= explode(',', $ex_submaster_id);
				$ex_b_n_e			= explode(',', $ex_b_n);

				for ($index1 = 0; $index1 < count($matter_ex); $index1++)
				{
					$pr_ind = $index1+1;

					if($rost_details['m_f']==1 || $rost_details['m_f']==2 || $rost_details['m_f']==3 || $rost_details['m_f']==4)
					{
						$data2 = [
							'stage_code' 	=> "$matter_ex[$index1]",
							'stage_nature' 	=> "$ex_stage_nature_e[$index1]",
							'ros_id' 		=> "$maxid",
							'priority' 		=> "$pr_ind",
							'case_type' 	=> "$ex_case_type_e[$index1]",
							'b_n' 			=> "$ex_b_n_e[$index1]",
							'submaster_id' 	=> "$ex_submaster_id_e[$index1]"
						];

						$builder2 = $this->db->table('category_allottment');
						$builder2->insert($data2);
					}
				}

				$query_update_res = $this->db->table('category_allottment');
				$query_update_res->where('ros_id', $reqData['btnroster']);
				$query_update_res->where('display', 'Y');
                $query_update_res->update(['display' => 'T']);

				if($query_update_res){
					$sno = 1;
				}
				else{
					$query_update1_res = $this->db->table('master.roster');
					$query_update1_res->where('id', $reqData['btnroster']);
					$query_update1_res->where('display', 'Y');
					$query_update1_res->update(['display' => 'T']);

					if($query_update1_res)
					{
						$sno = 2;
					}
					else 
					{
						$sno = 3;
					}
				}
			}
			else{
				echo "Problem to Add Record";
			}
		}
		else 
		{
			$roderData = $this->RosterModel->getRosterData($reqData['bench_names'], $rost_details['m_f'], $rost_details['from_date']);

			if(!empty($roderData))
			{
				$res_sql_chks 	= $roderData[0]['to_date'];
				$res_sql_chks_x = $roderData[0]['from_date'];			

				if($res_sql_chks == NULL)
					$error= "Close roster to make another roster for ".$reqData['sp_bnch_nm'].'('.$reqData['bench_name_inn'].') on '.$reqData['from_dt'];
				else 
					$error=  "Roster closed for ".$reqData['sp_bnch_nm'].'('.$reqData['bench_name_inn'].') between '.$res_sql_chks_x.'and '.$res_sql_chks;
				echo '<input type="hidden" name="hd_error" id="hd_error" value="'.$error.'"/>';
				$sno=4;
			}
		}
		echo '<input type="hidden" name="hd_sno_ss" id="hd_sno_ss" value="'.$sno.'"/>';
		
	}

	public function extend_roster(){
		$reqData = $this->request->getGet();
		$data['reqData'] = $reqData;
		$data['roster_data'] = $this->RosterModel->getRosterDetails($reqData['btnroster']);
		return view('Listing/roster/extend_roster', $data);
	}


	public function save_ext_rec(){
		$reqData = $this->request->getGet();
		
		$res_sq_benc_a = $this->RosterModel->getRosterDetails($reqData['hd_ex_r_id']);
		
		$reqData['bench_name']	= $res_sq_benc_a['bench_id'];
		$reqData['rdn_ckk']		= $res_sq_benc_a['m_f'];

		$jud_ids='';

		$judge	= $this->RosterModel->getJudgeDetils($reqData['hd_ex_r_id']);
		
		foreach($judge as $row2) 
		{
			if($jud_ids == '')
				$jud_ids = $row2['judge_id'];
			else
				$jud_ids = $jud_ids.','.$row2['judge_id']; 
		}

		$h_m_s_c = explode('#', $reqData['h_m_s_c']);

		for ($index2 = 0; $index2 < count($h_m_s_c); $index2++)
		{
			$ex_h_m_s_c             = explode('~', $h_m_s_c[$index2]);
			$reqData['from_dt']    	= $ex_h_m_s_c[0];
			$from_dt                = convertDateFormat($reqData['from_dt']);
			$to_date                = '';
			
			if($reqData['rdn_ckk'] == 2)
			{
				$to_date	= NULL;
			}
			else
			{
				$to_date	= $from_dt;
			}

			$sitting_dt = '';
			$reqData['ddl_hrs']		= $ex_h_m_s_c[2];
			$reqData['ddl_min']		= $ex_h_m_s_c[3];
			$reqData['ddl_am_pm']	= $ex_h_m_s_c[4];

			if($reqData['ddl_hrs'] != '')
			{
				$sitting_dt = $reqData['ddl_hrs'].':'.$reqData['ddl_min'].' '.$reqData['ddl_am_pm'];
			}

			$res_whr['m_f'] 		= $reqData['rdn_ckk'];
			$res_whr['from_date'] 	= $from_dt;

			$res_sql_chks	= $this->RosterModel->countRosterDetail($reqData['bench_name'], $res_whr);
			
			if($res_sql_chks['count'] <= 0)
			{
				$bench_id = $reqData['bench_name'];

				$row = $this->RosterModel->getRoterMaxId();
				$maxid = $row['id'] + 1;
				
				$rost_insert = [
					'id'		=> $maxid,
					'bench_id'	=> $res_sq_benc_a['bench_id'],
					'from_date'	=> $from_dt,
					'to_date'	=> $to_date,
					'entry_dt'	=> date('Y-m-d'),
					'display'	=> 'Y',
					'courtno'	=> $res_sq_benc_a['courtno'],
					'm_f'		=> $res_sq_benc_a['m_f'],
					'frm_time'	=> $sitting_dt,
					'tot_cases'	=> $ex_h_m_s_c[5],
					'session'	=> $ex_h_m_s_c[1],
					'judges'	=> 0
				];

				$rosterQry = $this->db->table('master.roster');				
				$rosterQry->insert($rost_insert);
								
				if($this->db->affectedRows() > 0)
				{
					$ex_jud_ids = explode(',', $jud_ids);

					for ($index = 0; $index < count($ex_jud_ids); $index++) 
					{
						$rostJudge = [
							'roster_id'	=> $maxid,
							'judge_id'	=> $ex_jud_ids[$index]
						];

						$rosterJQry = $this->db->table('master.roster_judge');
						$rosterJQry->insert($rostJudge);
					}

					echo "Record Added Successfully for ".$reqData['hd_sp_bn_r'].' on '.$reqData['from_dt'].'<br/>';

					$sq_cat_s = $this->RosterModel->getCategoriesAlot($reqData['hd_ex_r_id']);

					foreach ($sq_cat_s AS $row1)
					{
						$catAlot = [
							'stage_code'	=> $row1['stage_code'],
							'ros_id'		=> $maxid,
							'priority'		=> $row1['priority'],
							'case_type'		=> $row1['case_type'],
							'b_n'			=> $row1['b_n'],
							'stage_nature'	=> $row1['stage_nature'],
							'submaster_id'	=> $row1['submaster_id']
						];

						$catAlotQry = $this->db->table('category_allottment');
						$catAlotQry->insert($catAlot);						
					}
				}
				else
					echo "Problem to Add Record";
			}
			else 
			{
				$res_sql = $this->RosterModel->getRosterData($reqData['bench_name'], $reqData['rdn_ckk'], $from_dt);
				
				// $res_sql_chks 	= $res_sql[0]['to_date'];
				// $res_sql_chks_x	=	$res_sql[0]['from_date'];
				
				if(!isset($res_sql_chks[0]['to_date']))    /////  $res_sql_chks == NULL
					echo "Close roster to make another roster for ".$reqData['hd_sp_bn_r'].' on '.$reqData['from_dt'].'<br/>';
				else 
					echo "Roster closed for ".$reqData['hd_sp_bn_r'].') between '.$res_sql[0]['from_date'].'and '.$res_sql[0]['to_date'].' <br/>';

			}
		}
	}

	public function update_ros_cases(){
		$reqData = $this->request->getGet();

		$query_update_res = $this->db->table('master.roster');
		$query_update_res->where('id', $reqData['btnroster']);
		$query_update_res->where('display', 'Y');
        $query_update_res->update(['courtno' => $reqData['ros_cases']]);
		
		if($query_update_res)
		{
			echo "Data Updated Successfully";
		}
		else
		{
			echo "Server Error!!";
		}
	}

	public function update_ros_time(){
		$reqData = $this->request->getGet();

		$query_update_res = $this->db->table('master.roster');
		$query_update_res->where('id', $reqData['btnroster']);
		$query_update_res->where('display', 'Y');
        $query_update_res->update(['frm_time' => $reqData['ros_cases']]);
		
		if($query_update_res)
		{
			echo "Data Updated Successfully";
		}
		else
		{
			echo "Server Error!!";
		}
	}

	public function update_print_in_before_court(){
		$reqData = $this->request->getGet();

		$query_update_res = $this->db->table('master.roster');
		$query_update_res->where('id', $reqData['rosterId']);
		$query_update_res->where('display', 'Y');
        $query_update_res->update(['if_print_in' => $reqData['printInBeforeCourt']]);
		
		if($query_update_res)
		{
			echo "Data Updated Successfully";
		}
		else
		{
			echo "Server Error!!";
		}
	}

	public function delete_record_mot(){
		$reqData = $this->request->getGet();

		$catAloteRec = $this->RosterModel->getCategoriesAlotCount($reqData['hd_rosterid']);

		if($catAloteRec[0]['count'] == 1)
		{
			$query_updateQry = $this->db->table('master.roster');
			$query_updateQry->where('id', $reqData['hd_rosterid']);
			$query_updateQry->where('display', 'Y');
			$query_updateQry->update(['display' => 'N']);
		}

		$query_update_res = $this->db->table('category_allottment');
		$query_update_res->where('display', 'Y');
		$query_update_res->where('stage_code', $reqData['hdcatid']);
		$query_update_res->where('ros_id', $reqData['hd_rosterid']);
		$query_update_res->where('case_type', $reqData['hd_case_type']);
		$query_update_res->where('submaster_id', $reqData['hd_cat3']);
		$query_update_res->where('stage_nature', $reqData['hd_stage_nature']);
		$query_update_res->update(['display' => 'N']);

		echo '1';
	}
	

	public function save_mot_det(){
		$reqData = $this->request->getGet();
		$sno = '';
		$data = [
			'stage_code' 	=> $reqData['hd_sp_b'],
			'ros_id' 		=> $reqData['hd_ros_id'],
			'priority' 		=> $reqData['hd_sp_g'],
			'display' 		=> 'Y',
			'case_type' 	=> $reqData['hd_sp_a'],
			'b_n' 			=> $reqData['hd_befote_not'],
			'stage_nature' 	=> $reqData['hd_sp_f'],
			'submaster_id' 	=> $reqData['hd_sp_c']
		];

		// Use the Query Builder to prepare the insert
		$builder = $this->db->table('category_allottment');
		$builder->insert($data);

		// Check for errors
		if ($this->db->affectedRows() > 0) {
			$sno = 1;
		}else{
			$sno = 0;
		}

		return '<input type="hidden" name="hd_mn_no" id="hd_mn_no" value="'.$sno.'"/>';
	}

	public function nt_type_get()
	{
		$sql1 = is_data_from_table('master.casetype',  " display='Y' and nature='$_REQUEST[ddl_cas_nature]' order by skey ", " casecode,skey,casename ", 'A');
		$html = '<option value="" disabled>Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $row) {
				$html .= '<option value="' . $row['casecode'] .'">' . $row['skey'] . '</option>';
			}
		}
		return $html;
	}

	public function getcat_ans()
	{
		$subject = $_REQUEST['subjectId'];

		$sql1 = is_data_from_table('master.submaster',  " subcode1=(select subcode1 from master.submaster where id='$subject' and display='Y') and 
     display='Y' and subcode3=0 and subcode4=0 and subcode2!= '0' order by  subcode1,subcode2, subcode3, subcode4 ", "*", 'A');
		$html = '<option value="" disabled>Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $subcat) {
				$asdd = '';
				if ($subcat['flag'] == 's') {
					$asdd =  $subcat['category_sc_old'] . '-';
				}
				if ($subcat['flag'] == 's') {
					$cl_supreme = 'cl_supreme';
				} else {
					$cl_supreme = 'cl_other';
				}

				$html .= '<option  value="' . $subcat['id'] . '" class="' . $cl_supreme . '">' .  $asdd . $subcat['sub_name4'] . '</option>';
			}
		}
		return $html;
	}

	public function getsubcat_ans()
	{
		$subjectId = intval($_GET['subject']);
		$catId = intval($_GET['cat']);
		$sql1 = is_data_from_table('master.submaster', " subcode1=(select subcode1 from master.submaster where id='$catId' and display='Y') and 
    subcode2=(select subcode2 from master.submaster where id='$catId' and display='Y') and subcode2!= '0'  and subcode3!=0 and subcode4=0 and display='Y' order by subcode1,subcode2, subcode3, subcode4 ", "*", 'A');

		$html = '<option value="">Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $subcat) {
				$asdd = '';
				//if($subcat['flag']=='s'){ $asdd =  $subcat['category_sc_old'].'-'; }
				if ($subcat['flag'] == 's') {
					$cl_supreme = 'cl_supreme';
				} else {
					$cl_supreme = 'cl_other';
				}

				$html .= '<option  value="' . $subcat['id'] . '" class="' . $cl_supreme . '">' .  $asdd . $subcat['sub_name4'] . '</option>';
			}
		}
		return $html;
	}



	public function roster_save()
	{
		$h_m_s_c = explode('#', $_REQUEST['h_m_s_c']);
		for ($index2 = 0; $index2 < count($h_m_s_c); $index2++) {

			$ex_h_m_s_c = explode('~', $h_m_s_c[$index2]);

			$_REQUEST['from_dt'] = $ex_h_m_s_c[0];
			$from_dt = date('Y-m-d', strtotime($_REQUEST['from_dt']));
			$to_date = '';
			if ($_REQUEST['rdn_ckk'] == 2) {

				$to_date = $from_dt;
			} else {
				if ($_REQUEST['ddlBench'] == 10) {
					$to_date = '';
				} else {
					$to_date = $from_dt;
				}
			}
			$sitting_dt = '';
			$_REQUEST['ddl_hrs'] = $ex_h_m_s_c[2];
			$_REQUEST['ddl_min'] = $ex_h_m_s_c[3];
			$_REQUEST['ddl_am_pm'] = $ex_h_m_s_c[4];
			if ($_REQUEST['ddl_hrs'] != '') {
				$sitting_dt = $_REQUEST['ddl_hrs'] . ':' . $_REQUEST['ddl_min'] . ' ' . $_REQUEST['ddl_am_pm'];
			}

			$sql_chks = is_data_from_table('master.roster',  " bench_id='$_REQUEST[bench_name]' and 
							  m_f='$_REQUEST[rdn_ckk]' and display='Y' and ((to_date IS NULL and from_date='$from_dt' )  or ('$from_dt' between  from_date and to_date)) ", " count(id) s ", '');

			$res_sql_chks = $sql_chks['s'];
			if ($res_sql_chks <= 0) {

				$bench_id = $_REQUEST['bench_name'];


				$judge_code = explode(',', $_REQUEST['judge_code']);
				$ct_less = '';

				if ($_REQUEST['ck_ct_hall'] == '0') {
					for ($index = 0; $index < count($judge_code); $index++) {
						if ($ct_less == '') {
							$ct_less = $judge_code[$index];
						} else {
							if ($ct_less < $judge_code[$index]) {
								$ct_less = $ct_less;
							} else {
								$ct_less = $judge_code[$index];
							}
						}
					}
					$courtno1 = $ct_less;

					$sq_court = is_data_from_table('master.judge',  " jcode ='$courtno1' and (to_dt IS NULL or to_dt >= CURRENT_DATE) and display='Y' ", " jcourt ", '');


					$courtno = $sq_court['jcourt'];
				} else if ($_REQUEST['ck_ct_hall'] == '1') {
					$courtno = '999';
				}
				if (isset($judge_code[0]))
					$judge_code1 = $judge_code[0];
				else
					$judge_code1 = '0';
				if (isset($judge_code[1]))
					$judge_code2 = $judge_code[1];
				else
					$judge_code2 = '0';
				if (isset($judge_code[2]))
					$judge_code3 = $judge_code[2];
				else
					$judge_code3 = '0';

				if(isset($judge_code[3]))
					$judge_code4 = $judge_code[3];
				else
					$judge_code4 = '0';
				if(isset($judge_code[4]))
					$judge_code5 = $judge_code[4];
				else
					$judge_code5 = '0';

				$matter = $_REQUEST['matter'];

				$sess_no = 0;
				$sql = "SELECT max(id) as id FROM master.roster";

				$result = $this->db->query($sql);
				$row = $result->getRowArray();
				$maxid = $row['id'] + 1;

				$courtno = $_REQUEST['txt_court_no'];
				$printInBeforeCourt = $_REQUEST['printInBeforeCourt'];

				$data = [
					'id' => $maxid,
					'bench_id' => $bench_id,
					'from_date' => "$from_dt",
					'to_date' => "$to_date",
					'entry_dt' => date('Y-m-d H:i:s'), // Current timestamp
					'display' => 'Y',
					'courtno' => !empty($courtno) ? $courtno : 0, // Use null if courtno is empty
					'm_f' => $_REQUEST['rdn_ckk'],
					'frm_time' => "$sitting_dt",
					'tot_cases' => !empty($ex_h_m_s_c[5]) ? $ex_h_m_s_c[5] : 0, // Use null if tot_cases is empty
					'session' => "$ex_h_m_s_c[1]",
					'if_print_in' => $printInBeforeCourt,
					'judges' => "$_REQUEST[judge_code]"
				];

				// Use the Query Builder to prepare the insert
				$builder = $this->db->table('master.roster');
				$builder->insert($data);

				// Check for errors
				if ($this->db->affectedRows() > 0) {
					for ($j_n = 0; $j_n < count($judge_code); $j_n++) {

						$data = array(
							'roster_id' => $maxid,
							'judge_id' => $judge_code[$j_n]
						);
						
						$ins_rj = $this->db->table('master.roster_judge');
						$ins_rj->insert($data);
						
						
						if ($this->db->affectedRows() == 0) {
							echo "Error: " . $this->db->error();
						}
					}
					echo "Record Added Successfully for " . $_REQUEST['ddlBench_x'] . '(' . $_REQUEST['bench_name_x'] . ') on ' . $_REQUEST['from_dt'] . '<br/>';


					if ($_REQUEST['rdn_ckk'] == 1 || $_REQUEST['rdn_ckk'] == 2 || $_REQUEST['rdn_ckk'] == 3 || $_REQUEST['rdn_ckk'] == 4) {
						$matter_ex = explode('@', $matter);
						for ($index1 = 0; $index1 < count($matter_ex); $index1++) {
							$matter_ex_ex = explode('^', $matter_ex[$index1]);
							$pr_ind = $index1 + 1;

							$data = array(
								'stage_code' 	=> $matter_ex_ex[1],
								'ros_id' 		=> $maxid,
								'priority' 		=> $pr_ind,
								'case_type' 	=> $matter_ex_ex[0],
								'b_n' 			=> $matter_ex_ex[4],
								'stage_nature' 	=> $matter_ex_ex[3],
								'submaster_id' 	=> !empty($matter_ex_ex[2]) ? $matter_ex_ex[2] : 0
							);
							$sql_allot = $this->db->table('category_allottment');
							$sql_allot->insert($data);
							if ($this->db->affectedRows() == 0) {
								echo "Error: " . $this->db->error();
							}
						}
					}
				} else {
					echo "Problem to Add Record";
				}
			} else {

				$sq_court = is_data_from_table('master.roster',  " bench_id='$_REQUEST[bench_name]' and 
							  m_f='$_REQUEST[rdn_ckk]' and display='Y' and ((to_date IS NULL and from_date='$from_dt' ) or ('$from_dt' between  from_date and to_date)) ", " from_date,to_date ", '');

				$res_sql_chks = (!empty($sq_court['to_date'])) ? $sq_court['to_date'] : '';
				$res_sql_chks_x = (!empty($sq_court['from_date'])) ? $sq_court['from_date'] : '';
				if ($res_sql_chks == '')
					echo "Close roster to make another roster for " . $_REQUEST['ddlBench_x'] . '(' . $_REQUEST['bench_name_x'] . ') on ' . $_REQUEST['from_dt'] . '<br/>';
				else
					echo "Roster closed for " . $_REQUEST['ddlBench_x'] . '(' . $_REQUEST['bench_name_x'] . ') between ' . $res_sql_chks_x . 'and ' . $res_sql_chks . ' <br/>';
			}
		}
	}

	public function get_next_date()
	{
		$pre_from_dt = $this->request->getGet('pre_from_dt');
		$date = date('Y-m-d',  strtotime($pre_from_dt . ' + 1 day'));
		$nx_dt = chksDate($date);
		$nx_dt_s = date('d-m-Y',  strtotime($nx_dt));
		echo $nx_dt_s;
	}

	public function get_heading_type()
	{
		$ddl_cas_nature = $this->request->getGet('ddl_cas_nature');
		$sql1 = is_data_from_table('master.subheading',  " listtype ='L' AND display LIKE 'Y' and stagecode not in(850,852) order by stagecode ", " stagecode, stagename ", 'A');
		$html = '<option value="" disabled>Select</option>';
		if (!empty($sql1)) {
			foreach ($sql1 as $row) {
				//$html .= '<option value="'.$row['id'].'">'.$row['bench_no'].'</option>';	
				if (($ddl_cas_nature == 'C' || $ddl_cas_nature == 'W') && ($row['stagecode'] != 804 && $row['stagecode'] != 805 &&
					$row['stagecode'] != 806 &&  $row['stagecode'] != 811 &&  $row['stagecode'] != 814 &&
					$row['stagecode'] != 815)) {
					$html .= '<option value="' . $row['stagecode'] . '">' . $row['stagename'] . '</option>';
				} else if ($ddl_cas_nature == 'R' && ($row['stagecode'] != 812 && $row['stagecode'] != 813 && $row['stagecode'] != 816)) {
					$html .= '<option value="' . $row['stagecode'] . '">' . $row['stagename'] . '</option>';
				}
			}
		}
		return $html;
	}
}
