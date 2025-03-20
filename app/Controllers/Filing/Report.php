<?php

namespace App\Controllers\Filing;
use App\Controllers\BaseController;
use App\Models\Filing\ReportModel;
use App\Models\Common\Dropdown_list_model;

class Report extends BaseController
{
    public $Dropdown_list_model;
    function __construct()
    {
         ini_set('memory_limit','51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
         $this->Dropdown_list_model= new Dropdown_list_model();
    }

    public function index(){
        $data['casetype']=get_from_table_json('casetype');
        $data['usersection']=$this->Dropdown_list_model->get_usersection();
        return view('Filing/report' ,$data);

    }

    public function diary_search(){
        $ReportModel = new ReportModel();
        
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no').$this->request->getPost('diary_year');
        $data['isma'] = $this->request->getPost('isma');
        $data['is_inperson'] = $this->request->getPost('is_inperson');
        $data['reg_or_def'] = $this->request->getPost('reg_or_def');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');
        $data['is_pfield'] = $this->request->getPost('is_pfield');
        $data['is_efield'] = $this->request->getPost('is_efield');


        if (!empty($data)){

            $ddl_party_type = $this->request->getPost('ddl_party_type');
            $status = $this->request->getPost('ddl_status');
            $data['cause_title'] = $cause_title = !empty($this->request->getPost('cause_title')) ? strtoupper($this->request->getPost('cause_title')) : '';

            if($ddl_party_type=='')
            {
                $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
            }
            else if($ddl_party_type=='P')
            {
                $data['parties'] = ['pet_name' => $cause_title];
            }
             else if($ddl_party_type=='R')
            {
                $data['parties'] = ['res_name' => $cause_title];
            }

            $data['ReportsofCause']= $ReportModel->getReport($status, $data);
        }
     $data['formdata'] = $this->request->getPost();
     $data['casetype']=get_from_table_json('casetype');
     $data['usersection']=$this->Dropdown_list_model->get_usersection();
      $data['report_title'] = 'Details of Dairy Search with Selected Filter';

        return view('Filing/report',$data);
            
    }

    public function caveat_search(){
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['caveat_no'] = $this->request->getPost('caveat_no').$this->request->getPost('caveat_year');
        $data['ddl_status'] = $this->request->getPost('ddl_status');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');

        if (!empty($data)){

            $ddl_party_type = $this->request->getPost('ddl_party_type');
            $status = $this->request->getPost('ddl_status');
            $data['cause_title'] = $cause_title = !empty($this->request->getPost('cause_title')) ? strtoupper($this->request->getPost('cause_title')) : '';

            if($ddl_party_type=='')
            {
                $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
            }
            else if($ddl_party_type=='P')
            {
                $data['parties'] = ['pet_name' => $cause_title];
            }
             else if($ddl_party_type=='R')
            {
                $data['parties'] = ['res_name' => $cause_title];
            }

            $data['Reportsofcaveat']= $ReportModel->getCaveat($data);
            //echo $this->db->last_query();
        }
        
       $data['formdata'] = $this->request->getPost();
       $data['casetype']=get_from_table_json('casetype');
       $data['usersection']=$this->Dropdown_list_model->get_usersection();
       $data['report_title'] = 'Details of Caveat Search with Selected Filter';

        return view('Filing/report',$data);
            
    }

    public function fil_trap_search(){
        
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no').$this->request->getPost('diary_year');
        $data['diary_year'] = $this->request->getPost('diary_year');

       

        if (!empty($data)){
            $data['ReportsoffileTrap']= $ReportModel->getfileTrap($data);
        }
        
       $data['formdata'] = $this->request->getPost();
       $data['casetype']=get_from_table_json('casetype');
       $data['usersection']=$this->Dropdown_list_model->get_usersection();
       $data['report_title'] = 'Details of File Trap Search with Selected Filter';

        return view('Filing/report',$data);
            
    }

    public function dak_search(){
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['document_no'] = $this->request->getPost('document_no');
        $data['doc_year'] = $this->request->getPost('doc_year');
        $data['respondent_department'] = $this->request->getPost('respondent_department');
        $data['respondent_user'] = $this->request->getPost('respondent_user');

        if (!empty($data)){
            $data['Reportsofdak']= $ReportModel->getdak($data);
        }
        
       $data['formdata'] = $this->request->getPost();
       $data['casetype']=get_from_table_json('casetype');
       $data['usersection']=$this->Dropdown_list_model->get_usersection();
       $data['report_title'] = 'Details of DAK Search with Selected Filter';


        return view('Filing/report',$data);
            
    }
    
    public function case_search(){
        $ReportModel = new ReportModel();
        
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no').$this->request->getPost('diary_year');
        $data['isma'] = $this->request->getPost('isma');
        $data['is_inperson'] = $this->request->getPost('is_inperson');
        $data['reg_or_def'] = $this->request->getPost('reg_or_def');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');
        $data['is_pfield'] = $this->request->getPost('is_pfield');
        $data['is_efield'] = $this->request->getPost('is_efield');

        if (!empty($data)){

            $ddl_party_type = $this->request->getPost('ddl_party_type');
            $status = $this->request->getPost('ddl_status');
            $data['cause_title'] = $cause_title = !empty($this->request->getPost('case_title_search')) ? strtoupper($this->request->getPost('case_title_search')) : '';

            if($ddl_party_type=='')
            {
                $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
            }
            else if($ddl_party_type=='P')
            {
                $data['parties'] = ['pet_name' => $cause_title];
            }
             else if($ddl_party_type=='R')
            {
                $data['parties'] = ['res_name' => $cause_title];
            }

            $data['ReportsofCase']= $ReportModel->getCasesearch($data);
        }
     $data['formdata'] = $this->request->getPost();
     $data['casetype']=get_from_table_json('casetype');
     $data['usersection']=$this->Dropdown_list_model->get_usersection();
     $data['report_title'] = 'Details of Caveat Case Search with Selected Filter';

        return view('Filing/report',$data);
            
    }
    

    public function refiling_search(){
        
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no').$this->request->getPost('diary_year');
        $data['diary_year'] = $this->request->getPost('diary_year');

       

        if (!empty($data)){
            $data['Reportsrefiling']= $ReportModel->getRefiling($data);
            //print_r($data);
        }
        
       $data['formdata'] = $this->request->getPost();
       $data['casetype']=get_from_table_json('casetype');
       $data['usersection']=$this->Dropdown_list_model->get_usersection();
       $data['report_title'] = 'Details of Refiling Search with Selected Filter';

        return view('Filing/report',$data);
            
    }
    public function case_trap()
    {
        $param['from_date'] = $this->request->getPost('from_date');
        $param['to_date'] = $this->request->getPost('to_date');
        $param['diary_no'] = $this->request->getPost('diary_no');
        $ReportModel = new ReportModel();
        $data['results'] = [];
        $condition = [];
        $condition1 = [];

        if (!empty($param['diary_no'])) {
            $condition['main.diary_no'] = $param['diary_no'];
            $condition1['main_a.diary_no'] = $param['diary_no'];

            $data['results'] = $ReportModel->CashtrapList($condition ,$condition1);
        }
        if (!empty($param['from_date']) && !empty($param['to_date'])) {
            $condition['date(main.diary_no_rec_date) BETWEEN'] = [$param['from_date'], $param['to_date']];
            $condition1['date(main_a.diary_no_rec_date) BETWEEN'] = [$param['from_date'], $param['to_date']];

            $data['results'] = $ReportModel->CashtrapList($condition,$condition1);
        }
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no');
        // }
            return view('Reports/filing/casetrap_details', $data);
            exit;
        
    }
    
    

}