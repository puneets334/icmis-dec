<?php

namespace App\Controllers\Judicial\OriginalRecord;

use App\Controllers\BaseController;
use App\Models\Judicial\OriginalRecord\HcOrModel;

class HcOr extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $HcOrModel;

    function __construct()
    {
        $this->HcOrModel = new HcOrModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function index()
    {
        
        if (isset($_SESSION['filing_details']['diary_no'])) {
            $diary_no = $_SESSION['filing_details']['diary_no'];
    
            // Process diary_no
            $data['d_no'] = substr($diary_no, 0, 4);  // First 4 characters
            $data['d_year'] = substr($diary_no, -4); // Last 4 characters
            $data['d_yr'] = $diary_no;               // Full diary number
        } else {
            // Default values if 'diary_no' is not set
            $data['d_no'] = '';
            $data['d_year'] = '';
            $data['d_yr'] = '';
        }
    
        return view('Judicial/OriginalRecords/HcOr/index-view-hcor', $data);
    }
    


    public function getIndexingReport()
    {
        $userId =
          session()->get('login')['usercode'];
        $validationRules = [
            'option_list' => 'required|min_length[1]|max_length[1]',
        ];

        $searchType = $this->request->getPost('option_list');

        if ($searchType === 'D') {
            $validationRules = array_merge($validationRules, [
                'diary_number' => 'required|min_length[1]|max_length[15]',
                'diary_year' => 'required|min_length[4]|max_length[4]',
            ]);
        } elseif ($searchType === 'C') {
            $validationRules = array_merge($validationRules, [
                'case_type' => 'required',
                'case_number' => 'required|min_length[1]|max_length[15]',
                'case_year' => 'required|min_length[4]|max_length[4]',
            ]);
        }

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }

        // Initialize variables based on request data
        $u_t = $this->request->getPost('u_t');
        $caseType = $this->request->getPost('case_type');
        $caseNo = $this->request->getPost('case_number');
        $caseYear = $this->request->getPost('case_year');
        $diaryNo = $this->request->getPost('diary_number');
        $diaryYear = $this->request->getPost('diary_year');

        $dairy_no = $diaryNo . $diaryYear;

        $sub_users = '';
        if ($userId != 1) {
            // Using Query Builder to fetch the cmis_state_id and id for the sub-user
            $builder = $this->db->table('lowercourt_data.mapp_uid_sciid a');
            $builder->select('b.id, b.cmis_state_id');
            // $builder->join('master.ref_agency_code b', 'a.hcid = b.id');
            $builder->join('master.ref_agency_code b', 'CAST(a.hcid AS INTEGER) = b.id');
            $builder->where('a.uid', $userId);
            $builder->where('a.display', 'Y');
            $builder->where('b.is_deleted', 'f');

            $query = $builder->get();
            $row_state_dis = $query->getRowArray();

            if ($row_state_dis) {
                $sub_users = " AND l_state = " . $this->db->escape($row_state_dis['cmis_state_id']) .
                    " AND l_dist = " . $this->db->escape($row_state_dis['id']);
            }
        }
        /****** Paging start ****/
        $condition = '';
        if ($dairy_no != '') {
            $condition = 'a.diary_no=' . $dairy_no;
        } else{
            $condition = "type_sname='$caseType' AND lct_caseno='$caseNo' AND lct_caseyear='$caseYear' ";
        }
           

        //Check matter alloted to DA start
        $sql_ck = "SELECT 
            COALESCE(m.dacode, '') AS dacode, 
            COALESCE(u.name, '') AS username, 
            u.empid 
        FROM 
            main m 
        LEFT JOIN 
            master.users u ON m.dacode = u.usercode 
        WHERE 
            m.diary_no = :diary_no";

        $data = '';
        if ($searchType === 'C') {
            if (!empty($caseType) && !empty($caseNo)) {
                $getCaseData = $this->HcOrModel->getCaseDetails($userId, $caseType, $caseNo, $caseYear);
                
                if($getCaseData){
                    $caseDairyno = $getCaseData['dn'] . $getCaseData['dy'];
                  // pr($caseDairyno);
                    $data = $this->HcOrModel->getDiaryInfo($caseDairyno);
                }
            }
        } elseif ($searchType === 'D') {
            $data = $this->HcOrModel->getDiaryInfo($dairy_no); 
        }
        if (!empty($data)) {

            if ($data["username"] == "" and ($data["dacode"] == "" or $data["dacode"] == 0))
                $output1 = "0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $data["empid"];
            else if ($data["username"] == "" and ($data["dacode"] != $userId))
                $output1 = "0|#|VERIFICATION IN THIS CASE CAN BE DONE ONLY BY DA EMP ID : " . $data["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $data["dacode"];
            else if ($data["dacode"] != $userId)
                $output1 = "0|#|VERIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $data["username"] . " [EMP ID : " . $data["empid"] . "]|#|" . $data["dacode"];
            else
                $output1 = "1|#|RIGHT DA|#|" . $data["dacode"];
                $dacodedata =  $data["dacode"];
    
            $users_to_ignore = array();

            $sql_userV = is_data_from_table('master.users', "(section='63' OR section='37' OR usercode=1) AND display='Y' AND attend='P'", 'usercode', $row = 'A');

            foreach ($sql_userV as $row) {
                $users_to_ignore[] = $row['usercode'];
            }
            $result_da = explode("|#|", $output1);

            $rmtable = "";
       
            $data['result_da_status'] = 0;

            if ($result_da[2] > 0 or (in_array($userId, $users_to_ignore))) {

                $res_sq_count = $this->HcOrModel->getDiaryCount($condition);
                //pr($res_sq_count);
       
                $data['result_da_status'] = 1;
                $data['res_sq_count'] = $res_sq_count;
            }
            
   
            $data['result_da'] = $result_da;
            $data['condition'] = $condition;
            
            $data['_REQUEST'] = $_REQUEST;
           
        }
  
        if (!empty($data)) {

            $fst = 0;
            $lst = 30;
            $inc_val = 30;
            $tot_pg = 0;
            $pagingData = [];
            $data = [];
            if ($res_sq_count > 0) {
                $tot_pg = ceil($res_sq_count / $inc_val);
    
                // Prepare paging data
                $pagingData = [
                    'fst' => $fst,
                    'lst' => $lst,
                    'inc_val' => $inc_val,
                    'tot_pg' => $tot_pg,
                    'total_records' => $res_sq_count,
                ];
                $data['paging'] =  $pagingData;
                $data['userId'] = $userId;
                $data['diary_no'] = $dairy_no;
                $data['dacode'] = $dacodedata;
                $data['users_to_ignore'] = $users_to_ignore;
                // Get paginated data
                $data['records'] = $this->HcOrModel->getRankedData($condition, $fst, $inc_val,$dairy_no);
                //pr($data['records']);

                $data['resultLength'] = count($data['records']);

                $data['count_verify'] = count($data['records']['result']); //pr($data['resultLength']);
           }
        }
   
        if (isset($data['records']['result']) && !empty($data['records']['result'])) {
            $data['getLowerctDiaryCount'] = $this->HcOrModel->getLowerctDiaryCount($dairy_no); 
            $data['getDefects'] = $this->HcOrModel->getDefects($dairy_no);  //pr($getDefects);
            return view('Judicial/OriginalRecords/HcOr/records_display', $data, $data['records'], $pagingData);
        } else {
            return '<div class="text-center card"><div class="card-body"><h4 class="mb-0 text-danger">No Record Found</h4></div></div>';
        }
    }

    public function getEntryDetailsReport()
    {
        $diary_no = $this->request->getVar('diary_no');
        $otherCondition = $this->request->getVar('_');

        $d_yr = substr($diary_no, -4);
        $d_no = substr($diary_no, 0, strlen($diary_no) - 4);

        if(isset($_SESSION['dcmis_user_idd'])){
            $dcmis_user_idd =  $_SESSION['dcmis_user_idd'];
        }else{
            $dcmis_user_idd =  $_SESSION['login']['usercode'];
        }
       
        
        $resultUser = $this->HcOrModel->getUserData();
        if (!empty($resultUser)){
            $users_to_ignore =  $resultUser;
        }else{
            $users_to_ignore =  '';
        }

        $resultMain = $this->HcOrModel->getMainData($diary_no);
        if (!empty($resultMain)){
            $row_lp123 =  $resultMain;
        }else{
            $row_lp123 =  '';
        }

        $resultLowerCourt = $this->HcOrModel->getLowerCourtData($diary_no,$d_yr,$d_no);
        //pr($resultLowerCourt);

        $data = [
            'diary_no' => $diary_no,
            'd_yr' => $d_yr,
            'd_no' => $d_no,
            'dcmis_user_idd' => $dcmis_user_idd,
            'users_to_ignore' => $users_to_ignore,
            'row_lp123' => $row_lp123,
            'resultLowerCourt' => $resultLowerCourt
        ];

        return view('Judicial/OriginalRecords/HcOr/get-entry-details-result', ['data' => $data]);
       
    }

    public function updateReOpenforEdit()
    {
        $diaryNo = $this->request->getPost('diaryNo');

        $validationRules = [
            'diaryNo' => 'required|min_length[2]|max_length[15]',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Diary Number validation failed.']);
        }
    
        $data = [
            'conformation' => '0',
        ];

        $getResult = $this->HcOrModel->updateReOpenCase($diaryNo, $data);
        if ($getResult) {
            return $this->response->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Case reopened successfully. Now High court user can edit original record details in thid case.']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Failed to reopen case. Error! Please Contact Computer Cell.']);
        }

     
    }

    public function saveRecord()
    {
        $d_no = $this->request->getPost('d_no');
        $d_year = $this->request->getPost('d_year');
        $remarks = $this->request->getPost('remarks');
        $is_verify = $this->request->getPost('is_verify');

        $validationRules = [
            'd_no' => 'required|min_length[2]|max_length[15]',
            'd_year' => 'required|min_length[4]|max_length[4]',
            'remarks' => 'required',
            'is_verify' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'validation failed. Please try again.']);
        }

        if(isset($_SESSION['dcmis_user_idd'])){
            $dcmis_user_idd =  $_SESSION['dcmis_user_idd'];
        }else{
            $dcmis_user_idd =  $_SESSION['login']['usercode'];
        }
       

        $diary_no = $d_no . $d_year;

        $data = [
            'diary_no' => $diary_no,
            'is_verify' => $is_verify,
            'remarks' => $remarks,
            'ucode' => $dcmis_user_idd,
            'notified_on' => date('Y-m-d H:i:s')
        ];

        $getResult = $this->HcOrModel->saveCaseRecord($data);
        if ($getResult) {
            return $this->response->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Successfully Verified.']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Error! Please Contact Computer Cell.']);
        }

     
    }


}
