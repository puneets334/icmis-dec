<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\UpdateDACodeModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class UpdateDACode extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $UpdateDACodeModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->UpdateDACodeModel = new UpdateDACodeModel();
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }

    public function index(){
        if ($this->request->getMethod() === 'post' && $this->validate([
                'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]']
            ])) {
            $search_type = $this->request->getPost('search_type');
            if ($search_type=='D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])){
                $diary_number = $this->request->getPost('diary_number');
                $diary_year = $this->request->getPost('diary_year');
                $diary_no=$diary_number.$diary_year;
                $get_main_table= $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no); //pr($get_main_table);
            }elseif($search_type=='C' && $this->validate([
                'case_type_casecode' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])){
                $case_type = $this->request->getPost('case_type_casecode');
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);
            }

            if (!empty($get_main_table)) {
                $this->session->set(array('filing_details'=> $get_main_table));
                return redirect()->to('Judicial/UpdateDACode/redirectScreen');
            } else {
                session()->setFlashdata("message_error", 'Case not Found');
            }

        }
        $data['formAction'] = 'Judicial/UpdateDACode/index/';
        $data['casetype']= get_from_table_json('casetype');
        return view('Judicial/diary_search',$data);
    }


    //public function index()
    // {
    //     $data['casetype'] = get_from_table_json('casetype');
    //     $data['formAction'] = 'Judicial/UpdateDACode/handlePostRequest';
    //     $data['getCSRFPath'] = 'Judicial/UpdateDACode/getCSRF';
    //     return view('Judicial/UpdateDACode/update-da-code', $data);
    // }

    // public function handlePostRequest()
    // {
    //     $validationRules = [
    //         'search_type' => 'required|min_length[1]|max_length[1]',
    //     ];
    
    //     $searchType = $this->request->getPost('search_type'); 
    //     if ($searchType === 'D') {
    //         $validationRules = array_merge($validationRules, [
    //             'diary_number' => 'required|min_length[1]|max_length[15]',
    //             'diary_year' => 'required|min_length[4]',
    //         ]);
    //     } elseif ($searchType === 'C') {
    //         $validationRules = array_merge($validationRules, [
    //             'case_type_casecode' => 'required',
    //             'case_number' => 'required|min_length[1]|max_length[15]',
    //             'case_year' => 'required|min_length[4]',
    //         ]);
    //     }
    
    //     if (!$this->validate($validationRules)) {
    //         session()->setFlashdata('message_error', 'Validation errors occurred');
    //         return redirect()->back()->withInput();
    //     }
    
    //     if ($searchType === 'D') {
    //         $diaryNumber = $this->request->getPost('diary_number');
    //         $diaryYear = $this->request->getPost('diary_year');
    //         $diaryNo = $diaryNumber . $diaryYear;
    
    //         //$getMainTable = $this->UpdateDACodeModel->getDiaryDeatils($diaryNo);
    //         $getDiaryData= $this->UpdateDACodeModel->getDiaryDetails($diaryNo);
    //         print_r($getDiaryData); die;
    //         if ($getDiaryData) {
    //             session()->set('filing_details', $getDiaryData);
    //             return redirect()->to('Judicial/UpdateDACode/redirectScreen');
    //         } else {
    //             session()->setFlashdata('message_error', 'Data not found');
    //             return redirect()->back()->withInput();
    //         }
    //     }
    
    //     // Handle Case Search
    //     if ($searchType === 'C') {
    //         $ct = $this->request->getPost('case_type_casecode');
    //         $cn = $this->request->getPost('case_number');
    //         $cy = $this->request->getPost('case_year');
    
    //         $getCasedata = $this->UpdateDACodeModel->caseData($ct,$cn,$cy);
    //         print_r($getCasedata); die;
    //         if ($getCasedata) {
    //             session()->set('filing_details', $getCasedata);
    //             return redirect()->to('Judicial/UpdateDACode/redirect_on_diary_user_type');
    //         } else {
    //             session()->setFlashdata('message_error', 'Data not found');
    //             return redirect()->back()->withInput();
    //         }
    //         session()->setFlashdata('message_error', 'Data not found for Case Type');
    //         return redirect()->back()->withInput();
    //     }
    // }

    function redirectScreen() {
        if(session()->get('login')) {
            return redirect()->to('Judicial/UpdateDACode/updateCode');
        }else{
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }
        return redirect()->to('Judicial/UpdateDACode/updateCode');
    }


    public function updateCode(){
        $diary_no = $_SESSION['filing_details']['diary_no'];
        $data['dno'] = $diary_no;
        $diary_year = substr($diary_no, -4);
        $data['dyr'] = $diary_year;
        $data['casedesc'] = $this->UpdateDACodeModel->get_casedesc($diary_no);
        // echo "<pre>";
        // print_r($data['casedesc']); die;
        return view('Judicial/updateCode_view', $data);
    }


    public function set_dacode(){
        $data = $_POST;
        if(!empty($data)){
            $msg = $this->UpdateDACodeModel->set_dacode($data);
            echo $msg;
        }
    }


}