<?php

namespace App\Controllers\ManagementReports;

use App\Controllers\BaseController;
use App\Models\ManagementReport\CaseRemarksVerification;
use App\Models\Listing\Heardt;
use App\Models\ManagementReport\ReportModel;

class Report extends BaseController
{

    public $CaseRemarksVerification;
    public $Model_diary;
    public $Heardt;
    public $ReportModel;
    protected $db;

    function __construct()
    {
        set_time_limit(10000000000);
        ini_set('memory_limit', '-1');
        $this->CaseRemarksVerification = new CaseRemarksVerification();
        $this->Heardt = new Heardt();
        $this->ReportModel = new ReportModel();
        $this->session = session();
        $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    public function catAvlCase()
    {
        return view('ManagementReport/Reports/cat_avl_case');
    }
  
    public function catAvlCaseGet()
    {
        $request = service('request');
        $list_dt = $request->getPost('list_dt');
        if (!$list_dt) {
            return redirect()->back()->with('error', 'Date is required.');
        }
        $list_dt = (new \DateTime('2024-10-28'))->format('Y-m-d');
        $data = $this->Heardt->getReportData($list_dt);
        return view('ManagementReport/Reports/cat_val_report_view', [
            'data' => $data,
            'list_dt' => $list_dt
        ]);
    }

    public function catAvlCaseIndv()
    {
        return view('ManagementReport/Reports/cat_avl_case_indv');
    }

    public function catAvlCaseIndvGet()
    {
        $request = service('request');
        $ucode = session()->get('login')['usercode'];
        $list_dt = $request->getPost('list_dt');
        $court_no = $request->getPost('court_no');
        $data_save = $request->getPost('data_save');
        $data['reportData'] = $this->Heardt->getcatAvlCaseIndvGetReportData($list_dt, $court_no);
        $data['list_dt'] = $list_dt;
        $data['court_no'] = $court_no;
        return view('ManagementReport/Reports/cat_val_indv_report_view', $data);
    }

    public function catAvlCaseIndvSaved()
    {
        return view('ManagementReport/Reports/cat_avl_case_indv_saved');
    }

    public function catAvlCaseIndvSavedGet()
    {
        $request = service('request');
        $ucode = session()->get('login')['usercode'];
        $board_type = $request->getPost('board_type');
        $list_dt = $request->getPost('list_dt');
        $list_dt = date('Y-m-d', strtotime($list_dt));
        $judges = $this->CaseRemarksVerification->getJudgesSaved($list_dt);
        $data = [];
        foreach ($judges as $judge) {
            $categoryData = $this->CaseRemarksVerification->getCategoryData($list_dt, $judge['jcode']);
            $data[$judge['jcode']] = [
                'judge_name' => $judge['jname'],
                'categories' => $categoryData
            ];
        }
        return view('ManagementReport/Reports/cat_avl_case_indv_saved_get', ['data' => $data, 'list_dt' => $list_dt]);
    }

    public function UploadedJudgmentOrdersList(){
       return view('ManagementReport/Reports/ordersJudgmentsList');
    }
	
	public function UploadedJudgmentOrdersList_get(){
        $request = service('request');
        $data['app_name'] = '';
        $data['reports'] = '';
        $data['param'] = '';		   
	    $Reports_model = new ReportModel();
		$reportType = $request->getPost('rptType');
		$fromDate = date('Y-m-d', strtotime($request->getPost('fromDate')));
		$toDate = date('Y-m-d', strtotime($request->getPost('toDate')));
		$data['app_name'] = 'UploadedJudgmentOrdersList';
		$data['uploadedOrdersJudgmentsList'] = $Reports_model->getUploadedJudgmentOrdersList($reportType, $fromDate, $toDate);
		$data['param'] = array($reportType, $fromDate, $toDate);
		return view('ManagementReport/Reports/ordersJudgmentsList_get', $data);
	}
	

    public function catAvlCaseIndvRatio()
    {
        return view('ManagementReport/Reports/cat_avl_case_indv_ratio');
    }

    public function catAvlCaseIndvRatioGet()
    {
        $request = service('request');
        $ucode = session()->get('login')['usercode'];
        $board_type = $request->getPost('board_type');
        $list_dt = $request->getPost('list_dt');
        $list_dt = date('Y-m-d', strtotime($list_dt));
        $judges = $this->CaseRemarksVerification->getJudges($list_dt);
        $data = [];
        foreach ($judges as $judge) {
            $categoryData = $this->CaseRemarksVerification->getCategoryData_judge($list_dt, $judge['jcode']);
            $data[$judge['jcode']] = [
                'judge_name' => $judge['jname'],
                'categories' => $categoryData
            ];
        }
        return view('ManagementReport/Reports/cat_avl_case_indv_ratioGet', ['data' => $data, 'list_dt' => $list_dt]);
    }

    public function categoryYear()
    {
        return view('ManagementReport/Reports/category_year');
    }

    public function categoryProcessYear()
    {
        $categoryReportData = $this->ReportModel->getCatYearWise();
		return view('ManagementReport/Reports/get_category_year', ['categoryReportData' => $categoryReportData]);
    }

    public function categoryUi()
    {
        $judges = $this->ReportModel->getJudges();
        $categories = $this->ReportModel->getCategories();
        return view('ManagementReport/Reports/category_ui', [
            'judges' => $judges['judges'],
            'judge_count' => $judges['judge_count'],
            'categories' => $categories,
        ]);
    }

    public function getSubcategories()
    {
        $request = service('request');
        $cat = $request->getPost('cat');
        $db = \Config\Database::connect();
        $builder = $db->table('master.submaster');
        $builder->select('id as p, id || \'-\' || sub_name4 as id, subcode1, sub_name1, sub_name4');
        $builder->where('subcode1', $cat);
        $builder->orderBy('id');
        $query = $builder->get();
        $subcategories = $query->getResultArray();
        return $this->response->setJSON($subcategories);
    }

    public function category_data_fetch_old()
    {
        $request = service('request');
        $selsubcat = $request->getPost('selsubcat');
        $mainhead = $request->getPost('mainhead');
        $tdate = $request->getPost('tdate');
        $fdate = $request->getPost('fdate');
        $judge = $request->getPost('judge');
        $jud_num = $request->getPost('jud_num');
        $jud_num = $jud_num + 1;
        $dfdate = $request->getPost('dfdate');
        $dtdate = $request->getPost('dtdate');
        $jud_coram = $this->ReportModel->getJudgeCoram($judge, $jud_num);       
        $selcat = $this->ReportModel->getSubCategoryCondition($selsubcat);
        $data['report'] = $this->ReportModel->fetchJudgesReport($selsubcat, $mainhead, $tdate, $fdate, $jud_coram, $dfdate, $dtdate, $selcat);
        return view('ManagementReport/Reports/category_ui_get', $data);
    }

    public function category_data_fetch(){
        $request = service('request');
		$selsubcat = $request->getPost('selsubcat');
		$mainhead = $request->getPost('mainhead');
		$fdate = date('Y-m-d', strtotime($request->getPost('fdate')));
        $tdate = date('Y-m-d', strtotime($request->getPost('tdate')));
        $jud_coram = $request->getPost('jud_coram');
        $dfdate = date('Y-m-d', strtotime($request->getPost('dfdate')));
        $dtdate = date('Y-m-d', strtotime($request->getPost('dtdate'))); 
        $judge =  $request->getPost('judge');
        $jud_num = $request->getPost('jud_num');        
        $jud_num = $jud_num + 1;
        if ($mainhead == 'a') {
            $mainhead = 'IN (\'M\', \'F\')';
        } else {
            $mainhead = "IN ('" . $mainhead . "')";
        }
        $jud_len = count($judge);
        $jud_flag = 0;        
        $selcat = implode(",", array_map(function ($item) {
							return explode('-', $item)[0];
						}, $selsubcat));
		$builder = $this->db->table('main m');

		if ($jud_num == $jud_len) {
			$jud_flag = 1; 
		} else {
			$coramConditions = [];

			foreach ($judge as $i => $j) {
				if ($j == 'b') {
					$coramConditions[] = "(h.coram = '' OR h.coram = '0' OR h.coram IS NULL)";
				} else {
					$coramConditions[] = "h.coram = '{$j}'";
				}
			}

			if (count($coramConditions) > 0) {
				$jud_coram = '(' . implode(' OR ', $coramConditions) . ')';
			}
		}

		$builder->join('heardt h', 'h.diary_no = m.diary_no', 'inner')
			->join('master.listing_purpose l', 'l.code = h.listorder', 'inner')
			->join('(SELECT n.conn_key, COUNT(*) AS total_connected
					 FROM main m
					 INNER JOIN heardt h ON m.diary_no = h.diary_no
					 INNER JOIN main n ON m.diary_no = 
					 CASE 
						WHEN n.conn_key IS NULL OR n.conn_key = \'\' THEN NULL 
						ELSE CAST(n.conn_key AS bigint) 
					 END
					 WHERE n.diary_no != 
					 CASE 
						WHEN n.conn_key IS NULL OR n.conn_key = \'\' THEN NULL 
						ELSE CAST(n.conn_key AS bigint) 
					 END AND m.c_status = \'P\'
					 GROUP BY n.conn_key) aa', 'm.diary_no = CAST(aa.conn_key AS bigint)', 'left')
			->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
			->join('mul_category mc', 'mc.diary_no = m.diary_no AND mc.display = \'Y\'', 'left')
			->join('master.submaster s', 'mc.submaster_id = s.id AND s.flag = \'s\' AND s.display = \'Y\'', 'left');

		$builder->select([
			'ROW_NUMBER() OVER (ORDER BY CAST(m.diary_no AS bigint)) AS sno',
			'CONCAT(COALESCE(m.reg_no_display, \'\'), \' @ \', CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), \'-\', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 4 FOR 4))) AS Case_no',
			'CONCAT(m.pet_name, \' Vs. \', m.res_name) AS Cause_title',
			'COALESCE(aa.total_connected, 0) AS Group_count',
			'COALESCE((SELECT STRING_AGG(abbreviation, \'#\' ORDER BY judge_seniority)
					   FROM master.judge
					   WHERE h.coram LIKE CONCAT(\'%\', jcode, \'%\')), \'\') AS Coram',
			'CASE 
				WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old != \'\' AND s.category_sc_old != \'0\') 
				THEN CONCAT(\'(\', s.category_sc_old, \')\', s.sub_name1, \'-\', s.sub_name4)
				ELSE CONCAT(\'(\', CONCAT(s.subcode1, \'\', s.subcode2), \')\', s.sub_name1, \'-\', s.sub_name4)
			 END AS Subject_category',
			'tentative_section(m.diary_no) AS Section',
			'tentative_da(CAST(m.diary_no AS INTEGER)) AS DA'
		]);

		if (isset($jud_coram) && !empty($jud_coram)) {
			$builder->where($jud_coram);
		}

		$builder->whereIn('h.mainhead', ['M', 'F'])
			->where('(CAST(m.diary_no AS bigint) = CAST(m.conn_key AS bigint) OR m.conn_key = \'0\' OR m.conn_key = \'\' OR m.conn_key IS NULL)')
			->where('h.board_type', 'J')
			->where('s.subcode1 IS NOT NULL')
			->where('rd.fil_no IS NULL')
			->where('m.c_status', 'P')
			->where('h.main_supp_flag', 0)
			->where('h.next_dt >=', $fdate)
			->where('h.next_dt <=', $tdate)
			->whereIn('s.id', explode(',', $selcat));

		if(!empty($dfdate) && !empty($dtdate)){
			$builder->where('DATE(m.diary_no_rec_date) >=', $dfdate)
					->where('DATE(m.diary_no_rec_date) <=', $dtdate);
		}

		$builder->where('mc.submaster_id !=', 911)
			->where('mc.submaster_id !=', 913)
			->groupStart()
				->notLike('m.lastorder', 'Heard & Reserved') 
				->orWhere('m.lastorder', '')
				->orWhere('m.lastorder IS NULL')
			->groupEnd();

		$builder->orderBy("CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER)", 'ASC')
			->orderBy("CAST(SUBSTRING(m.diary_no::text FROM 1 FOR LENGTH(m.diary_no::text) - 4) AS INTEGER)", 'ASC');

		$query = $builder->get();
		$results = $query->getResultArray();
        return view('ManagementReport/Reports/category_ui_get', ['results' => $results]);
    }
    
    public function main_subject_categorywise_pendency()
	{
        $data['reports'] = $this->ReportModel->get_main_subject_categorywise_pending_cases();
        return view('ManagementReport/Reports/main_subject_category', $data);
	}    
    
    public function to_be_list_priority()
	{
        return view('ManagementReport/Reports/to_be_list_priority');
	}

    public function to_be_list_priority_process()
    {
        $request = service('request');
        $limit_number = $request->getVar('limit_number');
        $sortby = $request->getVar('sortby');
        $mainhead = $request->getVar('mainhead');
        $list_date = $request->getVar('list_date');
        $data = $this->ReportModel->to_be_list_priority_process($limit_number, $sortby, $mainhead, $list_date);
        return view('ManagementReport/Reports/to_be_list_priority_process', $data);
    }

    public function sensitive_case()
	{
        return view('ManagementReport/Reports/sensitive_case');
	}

    public function get_sensitive_cases()
    {
        $data['reports'] = $this->ReportModel->get_sensitive_cases();
        return view('ManagementReport/Reports/get_sensitive_cases', $data);
    }

}