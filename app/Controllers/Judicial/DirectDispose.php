<?php

namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\DirectDisposeModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class DirectDispose extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $DirectDisposeModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->DirectDisposeModel = new DirectDisposeModel();
    }


    public function index_bkup()
    {

        if ($this->request->getMethod() === 'post' && $this->validate([
                'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ]))
        {
            $search_type = $this->request->getPost('search_type');
            if ($search_type=='D'){
                $diary_number = $this->request->getPost('diary_number');
                $diary_year = $this->request->getPost('diary_year');
                $diary_no=$diary_number.$diary_year;
                $get_main_table= $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            }elseif($search_type=='C'){
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                session()->setFlashdata("message_error", 'Data not Fount');
            }

            if ($get_main_table){
                $this->session->set(array('filing_details'=> $get_main_table));
                return redirect()->to('Judicial/DirectDispose/redirect_on_diary_user_type');exit();
            }else{
                session()->setFlashdata("message_error", 'Data not Found');
            }

        }
        $data['casetype']=get_from_table_json('casetype');
        $data['formAction'] = 'Judicial/DirectDispose/index/';
        return view('Judicial/diary_search',$data);
    }

    public function index() {
        if ($this->request->getMethod() === 'post') {
            
            $search_type = $this->request->getPost('search_type');
    
            // Common validation rules
            $rules = [
                'search_type' => ['label' => 'Search Type', 'rules' => 'required|min_length[1]|max_length[1]']
            ];
    
            // Conditional validation rules
            if ($search_type == 'D') {
                $rules['diary_number'] = ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'];
                $rules['diary_year'] = ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'];
            } elseif ($search_type == 'C') {
                $rules['case_number'] = ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[15]'];
                $rules['case_year'] = ['label' => 'Case Year', 'rules' => 'required|min_length[4]'];
            }
    
            // Run validation
            if ($this->validate($rules)) {
                if ($search_type == 'D') {
                    $diary_number = $this->request->getPost('diary_number');
                    $diary_year = $this->request->getPost('diary_year');
                    $diary_no = $diary_number . $diary_year;
                    $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                } elseif ($search_type == 'C') 
                {
                    $case_type_casecode = $this->request->getPost('case_type_casecode');
                    $case_number = $this->request->getPost('case_number');
                    $case_year = $this->request->getPost('case_year');
                    $get_main_table = $this->Dropdown_list_model->get_case_details_by_case_no($case_type_casecode,$case_number,$case_year);   
                    //echo "afhsd";die;
                    //echo "<pre>";print_r($get_main_table);die;            
                    //session()->setFlashdata("message_error", 'Data not Found');
                }
    
                if (!empty($get_main_table)) {
                    session()->set('filing_details', $get_main_table);
                    return redirect()->to('Judicial/DirectDispose/redirect_on_diary_user_type');
                } else {
                    session()->setFlashdata("message_error", 'Data not Found');
                }
            }
        }
        if(isset($search_type) && !empty($search_type)){
            $data['search_type'] = $search_type;
            
        } 
        else{
            $data['search_type'] ='D' ;
        }
        
        $data['casetype'] = get_from_table_json('casetype');
        $data['formAction'] = 'Judicial/DirectDispose/index/';
        return view('Judicial/diary_search', $data);
    }


    public function causetitle(){
        $diary_no = $_SESSION['filing_details']['diary_no'];
        // echo "<pre>";
        //echo "<pre>"; print_r($_SESSION);die;
        // print_r($_SESSION['filing_details']); die;
        $data['d_no'] = substr($diary_no, 0, 4);
        $data['d_year'] = substr($diary_no, -4);
        //print_r($data);die;
        
        $data['d_yr'] = $diary_no;
        $data['user_idd'] = $_SESSION['login']['usercode'];  
        $data['casedesc'] = $this->DirectDisposeModel->get_casedesc($data);
        // echo "<pre>";
        // print_r($data['casedesc']); die;
        return view('Judicial/DirectDispose_view', $data);

    }


    function redirect_on_diary_user_type() {
        if(session()->get('login')) {
            return redirect()->to('Judicial/DirectDispose/causetitle');
        }else{
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }
        return redirect()->to('Judicial/DirectDispose/causetitle');
    }

    public function get_coram(){
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->DirectDisposeModel->get_coram($data);
            echo json_encode($modal_details);
        }
    }


    public function insert_rec_an_disp(){
        
        $data = $_POST;
        if(!empty($data)){
            $modal_details = $this->DirectDisposeModel->insert_rec_an_disp($data);
            echo json_encode($modal_details);
        }
    }
    
}