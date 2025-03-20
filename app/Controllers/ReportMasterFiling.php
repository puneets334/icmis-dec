<?php
namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ReportMasterFilingModel;


class ReportMasterFiling extends BaseController
{
  protected $session;
  protected $form_validation;
  protected $ReportManagementModel;

  public function __construct()
  {
    $this->ReportManagementModel = new ReportMasterFilingModel();
    $this->form_validation = \Config\Services::validation();
    $this->session = \Config\Services::session();
	 helper('function_helper');
  }

  public function case_alloted()
  {
    $userd = $this->session->get('login');
	$usercode=  $userd['usercode']; 
	$r_section= $userd['section'];  
    $r_usertype= $userd['usertype']; 
    $data['usercode'] = $usercode;
    $data['r_section'] = $r_section;
    $data['r_usertype'] =$r_usertype;
    $data['user_type'] =  $this->ReportManagementModel->get_case_usertype_details($usercode, $r_section, $r_usertype);
    return view('ReportMasterFiling/casealloted', $data);
  } 


  public function get_case_alloted_details()
  {
	  $data['dateFrom'] = date('Y-m-d', strtotime($this->request->getPost('dateFrom')));
	  $data['dateTo'] = date('Y-m-d', strtotime($this->request->getPost('dateTo')));
      $data['ddl_users'] = $this->request->getPost('ddl_users');
	  $userd = $this->session->get('login');
      $usercode=  $userd['usercode']; 
      $r_section= $userd['section'];  
      $r_usertype= $userd['usertype']; 
      $data['usercode'] = $usercode; 
      $data['r_section'] = $r_section;
      $data['r_usertype'] =$r_usertype;
      $data['result_array'] = $this->ReportManagementModel->get_case_alloted_details($data['dateFrom'], $data['dateTo'], $data['ddl_users'], $data['usercode'], $data['r_section'], $data['r_usertype']);
      return view('ReportMasterFiling/get_case_alloted_details', $data);
  }
  
  public function get_case_alloted_popup_details(){
	  $data['dateFrom'] = date('Y-m-d', strtotime($this->request->getPost('txt_frm_dt')));
	  $data['dateTo'] = date('Y-m-d', strtotime($this->request->getPost('txt_to_dt')));
      $data['ddl_users'] = $this->request->getPost('ddl_users');
      $data['case'] = $this->request->getPost('detailfor');
      $data['row_id'] = $this->request->getPost('rowid');
      $data['emp_id'] =$this->request->getPost('emp_id');
	  $userd = $this->session->get('login');
      $usercode=  $userd['usercode']; 
      $r_section= $userd['section'];  
      $r_usertype= $userd['usertype']; 
      $data['usercode'] = $usercode; 
      $data['r_section'] = $r_section;
      $data['r_usertype'] =$r_usertype;
	  $data['result_array'] = $this->ReportManagementModel->get_case_alloted_popup_details($data['dateFrom'], $data['dateTo'], $data['ddl_users'], $data['case'], $data['row_id'],$data['emp_id'], $data['usercode'], $data['r_section'], $data['r_usertype']);
      return view('ReportMasterFiling/get_case_alloted_popup_details', $data);
  }
 
 
}
