<?php

namespace App\Controllers\Court;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\Mentioning_Model;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class CourtMentionMemoController extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf; 
    public $cmodel;
 

    function __construct()
    {   
        $this->cmodel = new CourtMasterModel();
        $this->model = new Mentioning_Model();

        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();

        if(empty(session()->get('filing_details')['diary_no'])){
            header('Location:'.base_url('Filing/Diary/search'));
            exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }


    public function index()
    {
        $data['caseTypes'] = $this->model->get_case_type_list();
        $diaryNumber = $this->diary_no;
        $data['Mentioning_Model'] = $this->model;
        // pr(session()->get('filing_details'));
        $caseInfo = $this->model->getCaseDetails($diaryNumber);
        if($caseInfo){
            $data['caseInfodata'] = session()->get('filing_details');
            $data['caseInfo'] = $caseInfo;
            $data['listingInfo'] = $this->model->get_listings($diaryNumber);
            $data['judge'] = $this->cmodel->getJudge();
        }else{
            $caseInfo = [];
            session()->setFlashdata("error", 'Case Not Found');
        }
        return view('Court/CourtMentionMemo/mention_add', $data);
        
    }


    public function saveMentionMemo()
    {
        $this->model = new Mentioning_Model();
        $receivedDate = $this->request->getPost('mmReceivedDate');
        $data['receivedDate'] = date('Y-m-d', strtotime($receivedDate));
        $presentedDate = $this->request->getPost('mmPresentedDate');
        $data['presentedDate'] = date('Y-m-d', strtotime($presentedDate));
        $mmDecidedDate = $this->request->getPost('mmDecidedDate');
        $data['mmDecidedDate'] = $mmDecidedDate = date('Y-m-d', strtotime($mmDecidedDate));
        $forListType = $this->request->getPost('forListType');
        $data['forListType'] = $forListType;
        $data['roster_id'] = $this->request->getPost('bench');
        $data['item_no'] = $this->request->getPost('itemNo');
        $data['remarks'] = $this->request->getPost('remarks');
        //$data['diary_no'] = $this->diary_no;
        $cur_date_timestamp = strtotime(date('Y-m-d'));

        $caseListType = $this->model->getMiscOrRegular($this->diary_no);
        
        $MorF = (!empty($caseListType)) ? $caseListType[0]['mainhead'] : '';

        $dno_array = $this->model->get_main_connected_array($this->diary_no);
        
        $pFlag = 'T';
        $tFlag = 'T';
        $piaFlag = 'T';

        if ($MorF == 'M') {
            if ($forListType == 2) {
                $proposalConditionResult = $this->model->proposal_condition_status($dno_array, $MorF);
                foreach ($proposalConditionResult as $proposalResult) {
                    if ($proposalResult['judges'] == 0 && $proposalResult['brd_slno'] == 0 && $proposalResult['clno'] == 0 && $proposalResult['c_status'] == 'P' && $proposalResult['mainhead'] == 'M') {
                        $pFlag = ($pFlag == 'T') ? 'T' : 'F';
                    } else {
                        $pFlag = 'F';
                    }
                }

                if ($pFlag == 'F') {
                    session()->setFlashdata("msg", 'Cases Proposal stage(brd_no, sl_no, judges, c_status, mainhead) OF Misc Case condition False');
                    return redirect()->to('Court/CourtMentionMemoController/index');
                }

                $tentativeDateCondition = $this->model->tentative_date_condition_status($dno_array, $MorF);
                foreach ($tentativeDateCondition as $tentativeDateResult) {
                    if (strtotime($tentativeDateResult['tentative_cl_dt']) >= $cur_date_timestamp) {
                        $tFlag = ($tFlag == 'T') ? 'T' : 'F';
                    } else {
                        $tFlag = 'F';
                    }
                }

                if ($tFlag == 'F') {
                    session()->setFlashdata("msg", 'Tentative CL Date of Misc Matters Cannot be lass Than Today.. condition False');
                    return redirect()->to('Court/CourtMentionMemoController/index');
                }
            }
        }

        if ($MorF == 'F') {
            if ($forListType == 2) {
                $proposalConditionResult = $this->model->proposal_condition_status($dno_array, $MorF);

                foreach ($proposalConditionResult as $proposalResult) {
                    if ($proposalResult['c_status'] == 'P' && $proposalResult['mainhead'] == 'F' && (($proposalResult['main_supp_flag'] != 1 || $proposalResult['main_supp_flag'] != 2) && ($proposalResult['next_dt'] < $cur_date_timestamp || $proposalResult['tentative_cl_dt'] < $cur_date_timestamp))) {
                        $pFlag = ($pFlag == 'T') ? 'T' : 'F';
                    } else {
                        $pFlag = 'F';
                    }
                }

                if ($pFlag == 'F') {
                    session()->setFlashdata("msg", 'Cases is already Listed (Regular Case Proposal condition False)');
                    return redirect()->to('Court/CourtMentionMemoController/index');
                }
            }
        }

        if ($pFlag == 'T' && $tFlag == 'T' && $piaFlag == 'T') {
            $status = $this->model->add_new_mentionmemo($dno_array, $MorF, $data);
            $data = [
                'foreach_count' => $status['foreach_count'],
                'insert_count' => $status['insert_count'],
                'insert_status' => $status['insert_status']
            ];

            $data['save']       = 'saveMentionMemo';
            $data['caseInfo']   = '';
            $data['listingInfo'] = '';

            if ($data['insert_status'] == 'T' && $data['foreach_count'] == 1 && $data['insert_count'] == 1) {
                session()->setFlashdata("msg", 'Mention Memo Save Successfully.');
                return redirect()->to('Court/CourtMentionMemoController/index');
            } elseif ($data['insert_status'] == 'T' && $data['foreach_count'] == 2 && $data['insert_count'] == 1) {
                session()->setFlashdata("msg", 'Connected Case Save Successfully and Main Case Already added with some other connected Before.');
                return redirect()->to('Court/CourtMentionMemoController/index');
            } elseif(isset($status['msg']) && !empty($status['msg']) && $data['insert_status'] == 'F'){
                session()->setFlashdata("error-msg", $status['msg']);
                return redirect()->to('Court/CourtMentionMemoController/index');
            } else {
                session()->setFlashdata("msg", 'Mention Memo of Connected Case With Respective Main Case Save Successfully.');
                return redirect()->to('Court/CourtMentionMemoController/index');
            }
        }
    }


    public function mention_memo_report()
    {
        $data['app_name'] = [];
        $data['mentioningReports'] = [];
        $data['session_id_url'] = session()->get('login')['usercode'];
        $data['dateForDecided'] = '';
        $data['reportType'] = '';
         
        return view('Court/CourtMentionMemo/mention_report', $data);
    }

    public function mention_memo_report_list()
    {
        $data['app_name'] = [];
        $data['mentioningReports'] = [];
        $data['session_id_url'] = session()->get('login')['usercode'];
        $data['dateForDecided'] = '';
        $data['reportType'] = '';
        if($this->request->getMethod() == 'post'){
            $request = \Config\Services::request();
            $reportType = $request->getPost('reportType');
            $data['dateForDecided'] = date('Y-m-d',strtotime($request->getPost('decidedDate')));
            $data['reportType'] = $reportType;
            
            if ($reportType) {
                $reportType = (int) $reportType;
                if ($reportType === 1) {
                    $data['mentioningReports'] = $this->model->get_decided_mentioning($data['dateForDecided']);
                    $data['app_name'] = 'DecidedDatewise';
                } elseif ($reportType === 2) {
                    $data['mentioningReports'] = $this->model->get_onDate_mentioning();                    
                    $data['app_name'] = 'OnDatewise';
                } else {
                    $data['app_name'] = [];
                    $data['mentioningReports'] = [];
                }
            }
        }
        return view('Court/CourtMentionMemo/mention_memo_report_list', $data);
    }

    private function get_advocates($adv_id, $wen = '')
    {

        $db = \Config\Database::connect();
        $builder = $db->table('master.bar');
        $builder->select('name, enroll_no, EXTRACT(YEAR FROM enroll_date) AS eyear, isdead');
        $builder->where('bar_id', $adv_id);
        $query = $builder->get();
        $t_adv ='';
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $t_adv = $row['name'];
            if ($row['isdead'] == 'Y') {
                $t_adv = "<font color='red'>" . $t_adv . " (Dead) </font>";
            }
            if ($wen == 'wen') {
                $t_adv .= " [" . $row['enroll_no'] . "/" . $row['eyear'] . "]";
            }
        }

        return $t_adv;
    }

    public function getDiaries($diary_no)
    {
        $db = \Config\Database::connect();
   
              $builder = $db->table('lowerct a');
       
                $builder->select("
                a.lct_dec_dt, a.l_dist, a.l_state, a.lct_casetype, a.lct_caseno, a.lct_caseyear,
                b.diary_no AS c_diary, a.ct_code,
                CASE
                    WHEN a.ct_code = 3 THEN (
                        SELECT s.name
                        FROM master.state s
                        WHERE s.id_no = a.l_dist
                        AND s.display = 'Y'
                    )
                    ELSE (
                        SELECT c.agency_name
                        FROM master.ref_agency_code c
                        WHERE c.cmis_state_id = a.l_state
                        AND c.id = a.l_dist
                        AND c.is_deleted = 'f'
                    )
                END AS agency_name,
                CASE
                    WHEN a.ct_code = 4 THEN (
                        SELECT ct.skey
                        FROM master.casetype ct
                        WHERE ct.display = 'Y'
                        AND ct.casecode = a.lct_casetype::TEXT 
                    )
                    ELSE (
                        SELECT d.type_sname
                        FROM master.lc_hc_casetype d
                        WHERE d.lccasecode = a.lct_casetype::TEXT 
                        AND d.display = 'Y'
                    )
                END AS type_sname,
                SUBSTRING(b.fil_no FROM 4) AS fil_no,
                EXTRACT(YEAR FROM b.fil_dt) AS fil_dt,
                b.is_order_challenged, b.full_interim_flag, b.short_description, b.court_name, b.c_status, b.case_status_id
            ");

            // $builder->join('lowerct b', 'a.lct_dec_dt = b.lct_dec_dt
            //     AND a.l_dist = b.l_dist
            //     AND a.l_state = b.l_state
            //     AND TRIM(LEADING 0 FROM a.lct_caseno::TEXT) = TRIM(LEADING 0 FROM b.lct_caseno::TEXT)
            //     AND a.lct_caseyear = b.lct_caseyear
            //     AND a.ct_code = b.ct_code', 'left'
            // );
            $builder->join('master.state c', 'a.l_state = c.id_no', 'left');
            $builder->join('main d', 'd.diary_no = a.diary_no', 'left');
            $builder->join('master.casetype e', 'e.casecode = SUBSTRING(d.fil_no FROM 1 FOR 2)::TEXT AND e.display = Y', 'left');
            $builder->join('master.m_from_court f', 'f.id = a.ct_code AND f.display = Y', 'left');

            $builder->where('a.diary_no', $this->diary_no);
            $builder->where('a.lct_dec_dt IS NOT NULL');
            $builder->where('a.lw_display', 'Y');
            $builder->where('b.lw_display', 'Y');
            $builder->orderBy('b.diary_no');

            $query = $builder->get();

            return $query->getResultArray();
        }
        
    
    public function mention_memo_search(){
        $advocates = $this->model->advocate_by_diary_number($this->diary_no);
        $padvname = $advocates['padvname'];
        $radvname = $advocates['radvname'];
       
        $categories = $this->model->get_categories($this->diary_no);
        $catName = $catCode ='';
        if (!empty($categories)) {
            foreach ($categories as $rowCat) {
                $catName .= !empty($rowCat['category_sc_old']) ? ($rowCat['category_sc_old'] . ' - ' . $rowCat['sub_name1']) : $rowCat['sub_name1'] . (!empty($rowCat['sub_name4']) ? (' : ' . $rowCat['sub_name4']) : '') . '<br>';
                $catCode .= $rowCat['id'];
            }
            $hasOtherCategories = $this->model->check_other_categories($this->diary_no);
            $catCode .= '~' . ($hasOtherCategories ? 't' : 'f');
        }

      
        $newCategories = $this->model->get_new_categories($this->diary_no);
        $newCatName = '';
        if (!empty($newCategories)) {
            $newCategoryData = $newCategories[0]; 
            $newCatName = !empty($newCategoryData['category_sc_old']) ? ($newCategoryData['category_sc_old'] . ' - ' . $newCategoryData['sub_name1']): $newCategoryData['sub_name1'] . (!empty($newCategoryData['sub_name4']) ? (' : ' . $newCategoryData['sub_name4']) : '') . '<br>';
        }

        $data = [
            'catName' => $catName,
            'newCatName' => $newCatName,
            'padvname' => $padvname,
            'radvname' => $radvname,
        ];
    
        // $data['similarities_based']  = $this->getDiaries($this->diary_no);
        $data['not_before'] = $this->model->get_not_before_details($this->diary_no);
        $data['pending_Ai'] = $this->model->ia_pending_details($this->diary_no);
        $data['proof_of_service'] = $this->model->Proof_of_service($this->diary_no);
        $data['results'] = $this->model->get_caveat_advocate($this->diary_no);
        $data['search_details_data'] = $this->model->get_search_details($this->diary_no);
        $data['search_details'] = session()->get('filing_details');
        $data['search_details']['short_description'] = $this->model->get_short_description($this->diary_no);
        $data['f_get_ntl_judge'] = $this->model->f_get_ntl_judge($this->diary_no);
        $data['f_get_ndept_judge'] = $this->model->f_get_ndept_judge($this->diary_no);
        $data['f_get_category_judge'] = $this->model->f_get_category_judge($this->diary_no);
        
        return view('Court/CourtMentionMemo/mention_memo_search', $data);
     }
	 
	 
	 public function updateMm(){
		 $session = \Config\Services::session();
		if ($this->request->getPost()) {
            $access =  $this->model->getAccessDetails($session->get('dcmis_user_idd')? $session->get('dcmis_user_idd') : $this->request->getPost('session_id_url'));
            $data['access'] = $access;
           if ($updated = $this->model->UpdateMmData($this->request->getPost())) {
			    session()->setFlashdata("msg", 'Updated Successfully !!');
                return redirect()->to('Court/CourtMentionMemoController/updateMentionMemo');
           } else {
                session()->setFlashdata("message_error", 'Try Again Something wrong..!!');
                return redirect()->to('Court/CourtMentionMemoController/updateMentionMemo');
		  } 
        }
    }
	
	
	 function deleteMm() {
        $session = \Config\Services::session();
		if ($this->request->getPost()) {
            $access =  $this->model->getAccessDetails($session->get('dcmis_user_idd')? $session->get('dcmis_user_idd') : $this->request->getPost('session_id_url'));
            $data['access'] = $access;
            if ($this->Mentioning_Model->DeleteMmData($this->request->getPost())) {
                session()->setFlashdata("msg", 'Deleted Successfully !!');
                return redirect()->to('Court/CourtMentionMemoController/updateMentionMemo');
            }else {
                session()->setFlashdata("message_error", 'Try Again Something wrong..!!');
                return redirect()->to('Court/CourtMentionMemoController/updateMentionMemo');
		   } 
        }
    }
	 
	public function updateMentionMemo(){
		$data['session_id_url'] = session()->get('login')['usercode'];
        if (!empty($this->request->getPost('session_id_url'))) {
           $this->session->set('dcmis_user_idd', $this->request->getPost('session_id_url'));
        } else {
            $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
        }
		

        $access =  $this->model->getAccessDetails(session()->get('dcmis_user_idd'));

        if (empty($access)) {
            $data['msg'] = '<div class="alert alert-danger text-center">You are not authorized to view this page</div>';
        }

        $data['app_name'] = 'MentionMemo';
        $data['caseTypes'] = $this->model->get_case_type_list();
        $diaryNumber = '';
        $data['caseInfo'] = null;
        $data['listingInfo'] = null;
        $data['access'] = $access;
        $data['Mentioning_Model'] = $this->model;
		$data['session_id_url'] = session()->get('dcmis_user_idd');
		return view('Court/CourtMentionMemo/MentioningUpdate', $data);
    }
	
	
	public function updateMentionMemo_view(){
		 $data['Mentioning_Model'] = $this->model;
		 if (!empty($this->request->getPost('session_id_url'))) {

            if ($this->request->getPost('search_type') == 'C') {
                if ((!empty($this->request->getPost('case_type'))) && (!empty($this->request->getPost('case_number')))) {

                    $caseTypeId = $this->request->getPost('case_type');
                    $caseNo = $this->request->getPost('case_number');
                    $caseYear = $this->request->getPost('case_year');
                    $data['diaryDetails'] = $this->model->get_diary_details($caseTypeId, $caseNo, $caseYear);
                }
            }
            if ($this->request->getPost('search_type') == 'D') {
                $diaryNo = $this->request->getPost('diary_number');
                $diaryYear = $this->request->getPost('diary_year');
				$data['diaryDetails'] = $this->model->get_diary_details($diaryNo, $diaryYear);
			}
			
            if ((!empty($data['diaryDetails']))) {
                foreach ($data['diaryDetails'] as $row) {
                    $diaryNumber = $row['diary_no'];
					$this->session->set('diaryNo', $row['dn']);
                    $this->session->set('diaryYear', $row['dy']);
                    $this->session->set('diaryNumber', $row['diary_no']);
                  //$this->session->set('roaster_id', $row['m_roaster_id']);
                }

                $data['caseInfo'] = $this->model->getCaseDetails($diaryNumber);
                $data['listingInfo'] = $this->model->get_listings($diaryNumber);
			    $data['judge'] = $this->cmodel->getJudge();
                
           } else {
                return view('Court/CourtMentionMemo/mention_update_meno_view', $data);
            }

            if (!empty($data['caseInfo'])) {

                $dn = $data['caseInfo'][0]['diary_no'];
                $dy = $data['caseInfo'][0]['diary_year'];
            } else {
                $dn = $this->request->getPost('diary_number');
                $dy = $this->request->getPost('diary_year');
            }
			
			
            $recivedate = '';

            if (!empty($this->request->getPost('case_year'))) {
                $receivedDate = $this->request->getPost('case_year');
            } else {
                $receivedDate = $this->request->getPost('diary_year');
            }

            if (!empty($this->request->getPost('diary_forListType'))) {
                $forListType = $this->request->getPost('diary_forListType');
            } else {
                $forListType = $this->request->getPost('forListType');
            }


            // $bench = $data['mmData'] = $this->model->get_mmData_code($receivedDate, $dy, $dn, $forListType);  // get data from table to view in table
            $bench = $data['mmData'] = $this->model->get_mmData($receivedDate, $dy, $dn, $forListType);  // get data from table to view in table
            if (!$bench) {
                $data['update'] = 'updateMentionMemo';
                $data['caseInfo'] = '';
                $data['listingInfo'] = '';
                $data['session_id_url'] = $this->request->getPost('session_id_url');

            }
           
		    $bench_desc = "";
             
            if ($bench['courtno'] == 21) {
                $court = "R1";
            } else if ($bench['courtno'] == 22) {
                $court = "R2";
            } else if ($bench['courtno'] > 30 && $bench['courtno'] <= 60) {
                $court = "VC- " . ($bench['courtno'] - 30);
            } else if ($bench['courtno'] > 60 && $bench['courtno'] <= 62) {
                $court = "RVC- " . ($bench['courtno'] - 60);
            } else {
                $court = $bench['courtno'];
            }
            if ($bench['session'] == 'Whole Day' && $bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' in Court ' . $court;
            } else if ($bench['courtno'] != "" && $bench['courtno'] != 0 && $bench['courtno'] != null) {
                $bench_desc = $bench['session'] . ' @ ' . $bench['frm_time'] . ' in Court ' . $court;
            } else {
                $bench_desc = $bench['session'];
            }


            $data['roster_id'] = $bench['roster_id'];
            $data['bench_desc'] = $bench_desc;
            $data['formData'] = $this->request->getPost();
            $data['session_id_url'] = $this->request->getPost('session_id_url');
            $data['caseInfo'] = $this->model->getCaseDetails($diaryNumber);
           //$user = $data ['mm_data'] = $this->model->get_mmData_code($receivedDate, $dy, $dn, $forListType);
			$user = $data ['mm_data'] = $this->model->get_mmData($receivedDate, $dy, $dn, $forListType);
           //$user_code = $data ['user_code'] = $this->model->getmain_data($receivedDate, $dy, $dn, $forListType);
           //$dacode = $user_code->user_id;
		   $dacode = session()->get('login')['usercode'];
		   $user_detail = $data ['user_detail'] = $this->model->getuser_details($dacode);
		   $data['user_id'] ='1';//$user->user_id;
           $data['user_det'] =  $user_detail['name'] .' (' . $user_detail['empid']  .')'; 
        
           $msg_combined = ''; 

            if (!empty($bench['pdfname']) || !empty($bench['upload_date'])) {
                $data['session_id_url'] = $_POST['session_id_url'];
                $msg_combined .= 'Order is already Updated !!'.'<br>';
            }
            if ($data['user_id'] !== $this->request->getPost('session_id_url')) {
               $msg_combined .= 'Mentioned Information can be updated by ' . $data['user_det'] . '.<br>';
           }
           if(empty($data['mm_data']))

           {
            $msg_combined .= '<div class="alert alert-danger text-center">Case Not Found</div>';

           }
           
           
           if (!empty($msg_combined)) {
              $data['msg_combined'] = '<div class="alert alert-danger text-center">' . $msg_combined . '</div>';
           }

        }
        $data['session_id_url'] = session()->get('dcmis_user_idd');
         
		return view('Court/CourtMentionMemo/mention_update_meno_view', $data);
	}
	
	 
    
}



