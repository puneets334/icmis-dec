<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\AmendCausetitleModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class AmendCausetitle extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $AmendCausetitleModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->AmendCausetitleModel = new AmendCausetitleModel();
    }


    // public function index(){
    //     if ($this->request->getMethod() === 'post' && $this->validate([
    //             'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
    //             'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
    //             'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
    //         ])) {
    //         $search_type = $this->request->getPost('search_type');
    //         if ($search_type=='D'){
    //             $diary_number = $this->request->getPost('diary_number');
    //             $diary_year = $this->request->getPost('diary_year');
    //             $diary_no=$diary_number.$diary_year;
    //             $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
              
    //         }elseif($search_type=='C'){
    //             $case_number = $this->request->getPost('case_number');
    //             $case_year = $this->request->getPost('case_year');
    //             session()->setFlashdata("message_error", 'Data not Fount');
    //         }


    //         if ($get_main_table){
    //             $this->session->set(array('filing_details'=> $get_main_table));
    //             return redirect()->to('Judicial/AmendCausetitle/redirect_on_diary_user_type');exit();
    //         }else{
    //             session()->setFlashdata("message_error", 'Data not Fount');
    //         }

    //     }
    //     $data['casetype']=get_from_table_json('casetype');
    //     $data['formAction'] = 'Judicial/AmendCausetitle/index/';
    //     return view('Judicial/diary_search',$data);
    // }

    public function index()
    {
        $data['casetype'] = get_from_table_json('casetype');
        $data['formAction'] = 'Judicial/AmendCausetitle/handlePostRequest';
        return view('Judicial/diary_search', $data);
    }


    public function handlePostRequest()
    {
        $validationRules = [
            'search_type' => 'required|min_length[1]|max_length[1]',
        ];
    
        $searchType = $this->request->getPost('search_type');
        if ($searchType === 'D') {
            $validationRules = array_merge($validationRules, [
                'diary_number' => [
                    'rules' => 'required|min_length[1]|max_length[15]',
                    'errors' => [
                        'required' => 'The diary number is required.',
                        'min_length' => 'The diary number must be at least {param} characters long.',
                        'max_length' => 'The diary number cannot exceed {param} characters.'
                    ]
                ],
                'diary_year' => [
                    'rules' => 'required|min_length[4]',
                    'errors' => [
                        'required' => 'The diary year is required.',
                        'min_length' => 'The diary year must be at least {param} characters long.'
                    ]
                ],
            ]);
        } elseif ($searchType === 'C') {
            $validationRules = array_merge($validationRules, [
                'case_type_casecode' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'The case type or case code is required.'
                    ]
                ],
                'case_number' => [
                    'rules' => 'required|min_length[1]|max_length[15]',
                    'errors' => [
                        'required' => 'The case number is required.',
                        'min_length' => 'The case number must be at least {param} characters long.',
                        'max_length' => 'The case number cannot exceed {param} characters.'
                    ]
                ],
                'case_year' => [
                    'rules' => 'required|min_length[4]',
                    'errors' => [
                        'required' => 'The case year is required.',
                        'min_length' => 'The case year must be at least {param} characters long.'
                    ]
                ],
            ]);
        }
        
        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }
    
        if ($searchType === 'D') {
            $diaryNumber = $this->request->getPost('diary_number');
            $diaryYear = $this->request->getPost('diary_year');
            $diaryNo = $diaryNumber . $diaryYear;

            $getMainTable = $this->Dropdown_list_model->get_diary_details_by_diary_no($diaryNo);
            if ($getMainTable) {
                session()->set('filing_details', $getMainTable);
                return redirect()->to('Judicial/AmendCausetitle/redirect_on_diary_user_type');
            } else {
                session()->setFlashdata('message_error', 'Data not found');
                return redirect()->back()->withInput();
            }
        }
    
        // Handle Case Search
        if ($searchType === 'C') {
            $ct = $this->request->getPost('case_type_casecode');
            $cn = $this->request->getPost('case_number');
            $cy = $this->request->getPost('case_year');
    
            $getCasedata = $this->Dropdown_list_model->get_case_details_by_case_no($ct,$cn,$cy);
            // print_r($getCasedata); die;
            if ($getCasedata) {
                session()->set('filing_details', $getCasedata);
                return redirect()->to('Judicial/AmendCausetitle/redirect_on_diary_user_type');
            } else {
                session()->setFlashdata('message_error', 'Data not found');
                return redirect()->back()->withInput();
            }
            session()->setFlashdata('message_error', 'Data not found for Case Type');
            return redirect()->back()->withInput();
        }
    }

    public function causetitle(){
       $diary_no = $_SESSION['filing_details']['diary_no'];

  
        $data['dno'] = $diary_no;
        $diary_year = substr($diary_no, -4);
        $data['dyr'] = $diary_year;

        try {
            $casedesc = $this->AmendCausetitleModel->get_casedesc($diary_no);
        } catch (\Exception $e) {
            
            //Log the diary no;
            log_message('error', 'Invalid data: ' . $diary_no);
            log_message('error', 'Invalid data message: ' . $e->getMessage());
            
            return redirect()->to('error');
        }

        $data['casedesc'] = $casedesc;

        return view('Judicial/AmendCausetitle_view', $data);

    }


    function redirect_on_diary_user_type() {
        if(session()->get('login')) {
            return redirect()->to('Judicial/AmendCausetitle/causetitle');
        }else{
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }
        return redirect()->to('Judicial/AmendCausetitle/causetitle');
    }




}