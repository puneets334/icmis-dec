<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\ReportModel;
use App\Models\Listing\ListingStatisticsModel;
use App\Models\Listing\SpreadOutCertificateModel;
use App\Models\Listing\RoserRegModel;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\Report\Monitoring;
use App\Models\Casetype;
use App\Models\Listing\PrintModel;

class Report extends BaseController
{
  protected $ReportModel;
  protected $ListingStatisticsModel;
  protected $SpreadOutCertificateModel;
  protected $RoserRegModel;
  protected $Monitoring;
  protected $PrintModel;

  public function __construct()
  {
    $this->PrintModel = new PrintModel;
    $this->ReportModel = new ReportModel();
    $this->ListingStatisticsModel = new ListingStatisticsModel();
    $this->SpreadOutCertificateModel = new SpreadOutCertificateModel();
    $this->RoserRegModel =  new RoserRegModel();
    $this->Monitoring =  new Monitoring();
    
    ini_set('memory_limit', '40240000000M');
  }

  public function rop_verify_daily_court_remarks()
    {
        $data = [];
        $hd_ud = $this->request->getGet('hd_ud');
        $data['aw1'] = $this->request->getPost('aw1');
        $data['get_dtd'] = $this->request->getPost('dtd');
        $data['get_hdate'] = $this->request->getPost('hdate');
        $data['get_mf'] = $this->request->getPost('mf');
        $users = $this->Monitoring->get_users($hd_ud);
        $paps = $jcode = '0';
        if (!empty($users)) {
            $paps = $users['pa_ps'];
            $jcode = session()->set('jcode', $users['jcode']);
        }
        $data['userid'] = session()->get('login')['empid'];
        $data['c_list'] = $this->Monitoring->c_list($paps, $jcode);
        $data['case_remarks_head'] = $this->Monitoring->case_remarks_head();
        $data['case_remarks_head_side'] = $this->Monitoring->case_remarks_head_side();
        return  view('Listing/Report/daily_court_remarks', $data);
    }


    public function rop_verify_daily_court_remarks_process()
    {
        $data['crt'] = $this->request->getPost('courtno');
        $data['dtd'] = $this->request->getPost('dtd');
        $data['jcd'] = $this->request->getPost('aw1');
        $data['mf'] = $this->request->getPost('mf');
        $data['r_status'] = $this->request->getPost('r_status');
        $data['vstats'] = $this->request->getPost('vstats');
        $data['usercode'] = session()->get('login')['usercode'];
        $data['tdt1'] = date('Y-m-d',strtotime($data['dtd']));
        $data['msg'] = "";
        $data['jcourt'] = "";
        $list_dt = '';
        $mainhead = '';
        $roster_id = '';


        $data['get_drop_note_print'] = $this->Monitoring->get_drop_note_print($list_dt,$mainhead,$roster_id);
        $data['get_list_data'] = $this->Monitoring->rop_verify_daily_court_remarks_process($data['crt'],$data['dtd'],$data['jcd'],$data['mf'],$data['r_status'],$data['vstats']);
        return  view('Listing/Report/daily_court_remarks_process', $data);
    }

    public function spl_case()
    {
      $data['getCaseTpe'] =  $this->ReportModel->getCaseType();
      $data['reportSplCase'] =  $this->ReportModel->getSplCase();
      return view('Listing/Report/spl_case', $data);
    }

 
  public function get_spl_case()
  {
    $data['list_dt']  = $this->request->getPost('list_dt');
    $mainhead =  $this->request->getPost('mainhead');
    $p_lp =  $this->request->getPost('lp');
    $p_board_type = $this->request->getPost('board_type');
    $p_case_type = $this->request->getPost('case_type');

    if ($mainhead == 'M') {
      $data['mainhead_descri'] = "Miscellaneous Hearing";
    }
    if ($mainhead == 'F') {
      $data['mainhead_descri'] = "Regular Hearing";
    }
    if ($mainhead == 'L') {
      $data['mainhead_descri'] = "Lok Adalat";
    }
    if ($p_lp == 'all') {
      $lp = '';
    } else {
      $lp = $p_lp;
    }
    if ($p_case_type == 'all') {
      $case_type_id = "";
    } else {
      $case_type_id = $p_case_type;
    }
    if ($p_board_type == 0) {
      $board_type = "";
      $act_ros = "";
    } else {
      $board_type = $p_board_type;
      if ($p_board_type == 'C') {
        $act_ros = 'CC';
      } else {
        $act_ros = $p_board_type;
      }
    }
    $lp_str = implode(", ", $lp);
    $case_type_id_str = implode(", ", $case_type_id);

    $data['getlist'] = $this->ReportModel->getDateSelect($data['list_dt'], $mainhead, $lp_str, $board_type, $case_type_id_str, $act_ros);

    return view('Listing/Report/spl_case_report', $data);
  }

  public function eleminationTransfer()
  {
    $data['section_name'] = $this->ReportModel->section_name();
    return view('Listing/Report/elemination_transfer',$data);
  }
  public function get_elemination_transfer()
  {
    $list_dt = date('Y-m-d', strtotime($this->request->getPost('list_dt')));    
    $board_type = $this->request->getPost('board_type');  
    $sec_id = $this->request->getPost('sec_id'); 
    $list_year = date('Y', strtotime($this->request->getPost('list_dt')));
    $data['list_dt'] = $this->request->getPost('list_dt');
    $data['mainhead'] = "M";

    $ucode = session()->get('login')['usercode'];
    $usertype=session()->get('login')['usertype'];
    $section1=session()->get('login')['section'];
    
    $data['elemination_transfer'] = $this->ReportModel->get_elemination_transfer($list_dt,$sec_id); 

    return view('Listing/Report/get_elemination_transfer',$data);
  }
  
  public function lst_spl_cases(){
        $q_next_dt = $this->request->getPost('list_dt');
        $partno = $this->request->getPost('clno');
        $q_brd_slno = "1001";
        $mainhead = $this->request->getPost('mainhead');
        $q_judges = $this->request->getPost('avlj');
        $q_roster_id = $this->request->getPost('roster_id');
        $md_module_id = "20";
        $main_supp = $this->request->getPost('main_suppl');
        $cat1 = "0";
        $q_usercode=session()->get('login')['usercode'];

      $rslt_is_printed = f_cl_is_printed($q_next_dt,$partno,$mainhead,$q_roster_id);     
      if($rslt_is_printed == 0){ 
       
      $arr_dno = explode(",",$this->request->getPost('avldno'));
      for($i=0;$i<count($arr_dno);$i++){    
          $q_diary_no = $arr_dno[$i];
          //echo " kk ";
          q_from_heardt_to_last_heardt($q_diary_no);
          $total_case_listed  = f_heardt_cl_update($q_diary_no,$q_next_dt,$partno,$q_brd_slno,$q_roster_id,$q_judges,$q_usercode,$md_module_id,$main_supp,$mainhead,$cat1);
          if($total_case_listed > 0){
              return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">Successfully</h3>']);
          }
          else {
            // Handle insert error.  Log it!
            log_message('error', 'Failed to insert data.'); // Use CodeIgniter's logging
            return $this->response->setJSON(['message' => '<h3 class="bg-danger p-2 text-center">Failed. Please Try Again.</h3>']); // More appropriate message
          }
          
      }
      f_cl_reshuffle($q_next_dt,$q_judges,$mainhead,$partno,$q_roster_id);       
      }//end if cl printed        
      else{
        return $this->response->setJSON(['message' => '<h3 class="bg-success p-2 text-center">YOU CAN NOT ALLOT CASES IN SESSION '.$partno.' BECAUSE SESSION '.$partno.' FINALIZED.</h3>']);
      }
  }
  public function vac_reg_cl(){
    $data['section_name'] = $this->PrintModel->section_name();
    $data['registeredName'] = $this->ReportModel->registeredName();
    return view('Listing/Report/vac_reg_cl',$data);
    
  }
  public function vac_reg_cl_get(){
    $list_dt = date('Y-m-d', strtotime($this->request->getPost('ldates')));
    $reg_code = $this->request->getPost('reg_code');
    $sec_id = $this->request->getPost('sec_id');
    $data['list_dt'] = $this->request->getPost('ldates');
    $data['jcode'] = $this->request->getPost('reg_code');
    $data['sec_id'] = $this->request->getPost('sec_id');
    $data['getlistingCount'] = $this->ReportModel->vac_reg_cl_get($list_dt,$reg_code,$sec_id);
    return view('Listing/Report/vac_reg_cl_get',$data);
    
  }

  public function listing_statistics()
  {
    $data['test'] = '';

    return view('Listing/Report/listing_statistics', $data);
  }
  public function get_listing_statistics()
  {
    $session = session();
    $list_dt = $this->request->getPost('list_dt');
    $data['list_dt'] = date('Y-m-d', strtotime($list_dt));
    $ucode = $session->get('usercode');
    $usertype = $session->get('usertype');
    $section1 = $session->get('section');
    $data['getlistingCount'] = $this->ListingStatisticsModel->getListingStatistics($data['list_dt']);
   
    $data['getlistingCountAd'] = $this->ListingStatisticsModel->getListingStatisticsAdvanceList($data['list_dt']);
    $data['getlistingCountFinal'] = $this->ListingStatisticsModel->getListingStatisticsFinalIndex($data['list_dt']);
    $data['getListedFromAdvance'] = $this->ListingStatisticsModel->getListedFromAdvance($data['list_dt']);
    $data['getEliminatedFinalList'] = $this->ListingStatisticsModel->getEliminatedFinalList($data['list_dt']);
    $data['getUpdatedAfterFinalList'] = $this->ListingStatisticsModel->getUpdatedAfterFinalList($data['list_dt']);
    $data['getAllocatedSupplementaryList'] = $this->ListingStatisticsModel->getAllocatedSupplementaryList($data['list_dt']);
    $data['getEliminatedSupplementaryList'] = $this->ListingStatisticsModel->getEliminatedSupplementaryList($data['list_dt']);
    $data['getUpdatedAfterSupplementaryList'] = $this->ListingStatisticsModel->getUpdatedAfterSupplementaryList($data['list_dt']);
    
    
     // print_r($data['getlistingCount']);
    // die();
    return view('Listing/Report/listing_statistics_report', $data);
  }
  public function spread_out(){
    $data['section_name'] = $this->ReportModel->section_name();
    $data['listing_date'] = $this->ReportModel->tentative_listing_date();
    return view('Listing/Report/spread_out', $data);
    
  }
  public function get_spread_out(){
    $from_dt = date('Y-m-d', strtotime($_POST['from_dt']));
    $sec_list_dt = date('d-m-Y', strtotime($_POST['from_dt']));
    $board_type = $_POST['board_type'];
    $sec_id = $_POST['sec_id'];
    $data['h3_head'] = "Sec List for Dated ".$sec_list_dt." (To be list before court)";
    
    $data['spread_data'] = $this->ReportModel->get_spread_out_data($from_dt,$board_type,$sec_id);
    return view('Listing/Report/get_spread_out', $data);
    
  }
  public function spread_out_certificate()
  {
    $data['cur_ddt'] = date('Y-m-d', strtotime(' +1 day'));
    $data['next_court_work_day'] = date("d-m-Y", strtotime($data['cur_ddt']));
    return view('Listing/Report/spread_out_certificate', $data);
  }
  public function spread_out_certificate_get_data()
  {
   
    $data['form_date']  = $this->request->getPost('list_dt');
    $data['to_date']  = $this->request->getPost('list_dt_to');
    $list_dt = date('Y-m-d', strtotime($data['form_date']));
    $list_dt_to = date('Y-m-d', strtotime($data['to_date']));
   
    $data['spread_out_certificate'] = $this->SpreadOutCertificateModel->getCaseType($list_dt, $list_dt_to);
    
    $data['spread_out_second'] = $this->SpreadOutCertificateModel->getCaseNotListedReason($list_dt, $list_dt_to);
    // pr($data);
    // die();
    
    return view('Listing/Report/spread_out_certificate_get', $data);
  }

  public function spread_out_certificate_detail()
  {
    $data['form_date']  = $this->request->getGet('list_dt');
    $data['to_date']  = $this->request->getGet('list_dt_to');
    $data['flag']  = $this->request->getGet('flag');
    $data['purpose']  = $this->request->getGet('purpose');
    $data['mainhead'] = 'M';
    $data['bench'] = '';
    $list_dt = date('Y-m-d', strtotime($data['form_date']));
    $list_dt_to = date('Y-m-d', strtotime($data['to_date']));
    $purpose  = $this->request->getGet('purpose');
    $flag  = $this->request->getGet('flag');
    IF($purpose == 'f'){
      $data['headnote1'] = " Court Dated Cases ";
  }
  IF($purpose == 'fr'){
      $data['headnote1'] = " Fresh / Fresh Adjourned Cases ";
  }
  IF($purpose == 'aw'){
      $data['headnote1'] = " After Week Cases ";
  }
  IF($purpose == 'imp'){
      $data['headnote1'] = " Important IAs Cases ";
  }
  IF($purpose == 'cmp'){
      $data['headnote1'] = " Computer Dated (Not Taken/Adj./Notice etc) ";
  }
  IF($purpose == 'all'){
    $data['headnote1'] = "";
  }
  
  if($flag == 'not_disp'){
    $data['headnote2'] = "Not to list till dispose of other diary";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getNotDisp($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'spl'){
    $data['headnote2'] = "Special Bench Matters";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getSpecialBenchMatters($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'not_before'){
    $data['headnote2'] = "AOR Case Not to list before Judge";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getNotBefore($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'defect'){
    $data['headnote2'] = "Defective Category";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getDefectiveCategory($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'constution'){
    $data['headnote2'] = "Constitutional Bench Matters";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getConstitutionBenchMatters($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'cat_not'){
    $data['headnote2'] = "Category Not Mentioned";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getCatNot($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'short'){
    $data['headnote2'] = "Short Category Matters";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getShortCategoryMatters($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'excess'){
    $data['headnote2'] = "Excess Matters";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getExcessMatters($list_dt, $list_dt_to,$purpose);
  }
  
  if($flag == 'tot_tot'){
    $data['headnote2'] = "Total Not Listed";
    $data['spread_out_result'] = $this->SpreadOutCertificateModel->getTotalNotListed($list_dt, $list_dt_to,$purpose);
  }
  // pr($data);
  // die();
  
    
    return view('Listing/Report/spread_out_certificate_details', $data);
  }
  public function roster_reg()
  {
    return view('Listing/Report/roster_reg');
  }
  public function roster_reg_get()
  {
    //$data['getrosereg']  = $this->RoserRegModel->getRoserReg($this->request->getPost('ldates'));
    $list_dt = date('Y-m-d', strtotime($this->request->getPost('ldates')));
    $data['list_dt'] =$list_dt ;
    $data['RoserRegModel'] =$this->RoserRegModel ;
   // $data['getRosterRegData'] = $this->RoserRegModel->getRosterRegData($list_dt);
    return view('Listing/Report/roster_reg_get', $data);
  }

  public function listed_matter()
  {

    if (!empty($this->request->getPost('from_date'))) {
      $first_date = date('Y-m-d', strtotime($this->request->getPost('from_date')));
      $to_date = date('Y-m-d', strtotime($this->request->getPost('to_date')));
    } else {
      $first_date = date('Y-m-d', strtotime('first day of this month'));
      $to_date = date('Y-m-d');
    }
    $data['app_name'] = 'Listing Report';
    $result_array = $this->ReportModel->listing_matter($first_date, $to_date);
    $data['listed_result'] = $result_array;
    $data['first_date'] = $first_date;
    $data['to_date'] = $to_date;
    return view('Listing/Report/listed_report', $data);
  }
  public function listed_detail()
  {
    $date = $this->request->getGet('date');
    $flag = $this->request->getGet('flag');
    $result_array = $this->ReportModel->listed_detail($date, $flag);
    $data['listed_detail_result'] = $result_array;
    $data['date'] = $date;
    $data['flag'] = $flag;
    return view('Listing/Report/listed_detail_report', $data);
  }
  public function defective_cases_stats()
  {
    return view('Listing/Report/defective_cases_stats');
  }
  public function defective_cases_stats_get()
  {
    if (($this->request->getPost('mainhead'))) {
      $mainhead = $this->request->getPost('mainhead');
    }
    $date =  date('d-m-Y');
    $data['defective_cases_stats'] = $this->ReportModel->defective_cases_stats($date);
    $data['defectove_un_not_listed'] = $this->ReportModel->defectove_un_not_listed();
    $data['defect_notified_not_listed'] = $this->ReportModel->defect_notified_not_listed();
    $data['refiled_dealy_more_than_1_year'] = $this->ReportModel->refiled_dealy_more_than_1_year();
    $data['refiled_dealy_less_than_1_year'] = $this->ReportModel->refiled_dealy_less_than_1_year();
    $data['defect_not_notified_not_listed'] = $this->ReportModel->defect_not_notified_not_listed();
    return view('Listing/Report/defective_cases_stats_get', $data);
  }

  public function roster_j_c()
  {
    //pr('roster_j_c');

    return view('Listing/Report/roster_j_c');
  }
  public function get_roster_j_c()
  {
    $request = service('request');
    $from_dt = date('Y-m-d', strtotime($request->getPost('from_dt')));
    $data['h3_head'] = "Roster For Dated " . $request->getPost('from_dt');
    $data['get_roster_j_c'] = $this->ReportModel->get_roster_j_c($from_dt);
   
    return view('Listing/Report/get_roster_j_c', $data);
  }

  public function showHeardt()
  {

    if (!empty($this->request->getPost('fromDate'))) {
      $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
    }
    if (!empty($this->request->getPost('toDate'))) {
      $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));
    }
    $data['app_name'] = 'Heardt Report';
    if (!empty($fromDate) && !empty($toDate)) {
      $result_array = $this->ReportModel->showHeardt($fromDate, $toDate);
      $data['listing_result'] = $result_array;
    }
    return view('Listing/Report/DatewiseHeardtUpdate', $data);
  }

  public function showUsers()
    {
      
      $entDate = $this->request->getGet('date'); // Use $this->request->getGet()
      $module = $this->request->getGet('mod');
       
        $data['app_name']='Heardt Userwise Report';
        if(isset($entDate) && isset($module)) {
            $result_array = $this->ReportModel->showUsers($entDate,$module);
            $data['list_users'] = $result_array;
            $data['date']=$entDate;

        }
        
        return view('Listing/Report/HeardtUpdateUser',$data);
    }

    public function showMatters()
    {
        $entDate=$this->request->getGet('date');
        $module=$this->request->getGet('mod');
        $userid=$this->request->getGet('user');
        $data['app_name']='Heardt Report Drill Down';
        if(isset($entDate) && isset($module)) {
            $result_array = $this->ReportModel->showMatters($entDate,$module,$userid);
            $data['list_matters'] = $result_array;
            $data['date']=$entDate;

        }
        return view('Listing/Report/HeardtUpdateMatters',$data);
    }


  public function showHeardt_get()
  {

    if (!empty($this->request->getPost('fromDate'))) {
      $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
    }
    if (!empty($this->request->getPost('toDate'))) {
      $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));
    }
    $data['app_name'] = 'Heardt Report';
    if (!empty($fromDate) && !empty($toDate)) {
      $result_array = $this->ReportModel->showHeardt($fromDate, $toDate);
      $data['listing_result'] = $result_array;
    }
    return view('Listing/Report/DatewiseHeardtUpdate', $data);
  }


  public function cat_judge()
  {
    return view('Listing/Report/cat_judge');
  }
  public function get_cat_judge()
  {
    $mainhead = $this->request->getPost('mainhead');
    if ($mainhead == 'M') {
      $data['mainhead_descri'] = "Miscellaneous Hearing";
    }
    if ($mainhead == 'F') {
      $data['mainhead_descri'] = "Regular Hearing";
    }
    if ($mainhead == 'L') {
      $data['mainhead_descri'] = "Lok Adalat";
    }
    $data['list_dt'] = date('d-m-Y', strtotime($this->request->getPost('list_dt')));
    $list_dt1 = date('Y-m-d', strtotime($this->request->getPost('list_dt')));
    $data['result_array'] = $this->ReportModel->get_cat_judge($mainhead, $list_dt1);

    return view('Listing/Report/get_cat_judge', $data);
  }
  public function get_cl_print_mainhead()
  {
    $mainhead = $this->request->getGet('mainhead');
    $board_type = $this->request->getGet('board_type');
    get_cl_print_mainhead($mainhead, $board_type);
  }
  public function causelist_info()
  {

    if (!empty($this->request->getPost('courtNo'))) {
      $courtNo = $this->request->getPost('courtNo');
      $itemNo = $this->request->getPost('itemNo');
      $data['result_array'] = $this->ReportModel->causelist_info($courtNo, $itemNo);
      return view('Listing/Report/causelist_info', $data);
    } else {

      return view('Listing/Report/causelist_info');
    }
  }
  public function sensitive_listed()
  {

    return view('Listing/Report/sensitive_listed');
  }
  public function sensitive_listed_get()
  {
    $data['from_date'] = date("Y-m-d", strtotime($this->request->getPost('from_date')));
    $data['to_date'] = date("Y-m-d", strtotime($this->request->getPost('to_date')));
    $data['result_array'] = $this->ReportModel->sensitive_listed_get($data['from_date'], $data['to_date']);
    
    // print_r($data);
    // die();
    return $data['result_array'];
    //return view('Listing/Report/sensitive_listed_get', $data);
  }

  public function ntl_judge()
  {
    $data['result_array'] = $this->ReportModel->ntl_judge();
    return view('Listing/Report/ntl_judge', $data);
  }

  public function vacation_advance_list()
  {
    $data['result_array'] = $this->ReportModel->vacation_advance_list();
    return view('Listing/Report/vacation_advance_list', $data);
  }

  public function registered_matters_verified_not_listed()
  {
    $caseTypeModel = new Casetype();
    $data['data'] = $caseTypeModel->getCaseType();
    return view('Listing/Report/registered_verified_not_listed', $data);
  }

  public function get_data()
  {
    $ddl_nv_r = $this->request->getPost('ddl_nv_r');
    $idd = $this->request->getPost('idd');
    $newCsrfHash = csrf_hash();
    $model = new Casetype();
    $condition = '';

    if ($ddl_nv_r != 'All') {
      $active_casetype_id = $ddl_nv_r;
      $condition = " AND m.active_casetype_id = " . $model->escape($ddl_nv_r);
    }

    $data = $this->Monitoring->getVerifiedMatters($condition);

    if (count($data) > 0)
    {
      return $this->response->setJSON([
        'success' => true,
        'data' => $data,
        'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    }
    else
    {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'No records found.',
        'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    }
  }

  //Start(Reports=>Monitoring=>Error DA Wise Count)

  public function monitoring_Error_Dawise_count()
  {
    //$data['model'] = new Casetype();
    $data['case_result'] = '';
    $result_array = $this->Monitoring->monitoring_Error_Dawise_count();
    $data['case_result'] = $result_array;
    return view('Listing/Report/monitoring_Error_Dawise_count', $data);
  }

  //End(Reports=>Monitoring=>Error DA Wise Count)
  public function sec_list_dynamic()
  {
    $data['section_name'] = $this->ReportModel->section_name();
    return view('Listing/Report/sec_list_dynamic', $data);
  }
  public function sec_list_dynamic_get_data()
  {
    // $data['list_dt'] = $this->request->getPost('list_dt');
    // $data['board_type'] = $this->request->getPost('board_type');
    // $data['sec_id'] = $this->request->getPost('sec_id');
    // $data['list_type'] = $this->request->getPost('list_type');
    // $data['ucode'] = session()->get('login')['usercode'];
    // $data['usertype'] = session()->get('login')['usertype'];
    $list_dt = $this->request->getPost('list_dt');
    $board_type = $this->request->getPost('board_type');
    $sec_id = $this->request->getPost('sec_id');
    $list_type = $this->request->getPost('list_type');
    $ucode = session()->get('login')['usercode'];
    $usertype = session()->get('login')['usertype'];
   
    $data['result']= $this->ReportModel->getSecListNewFun1($list_dt,$board_type,$sec_id,$ucode,$list_type,$usertype);
            
    // print_r($data['result']);
    // die();
    return view('Listing/Report/sec_list_dynamic', $data);
  }

  public function seclist()
  {
    $data['section_name'] = $this->ReportModel->section_name();
    return view('Listing/Report/seclist', $data);
  }

  public function seclist_get()
  {
    date_default_timezone_set('Asia/kolkata');
    $data['mainhead'] = "M";
    $list_dt = date('Y-m-d', strtotime($this->request->getPost('ldates')));
    $board_type = $this->request->getPost('board_type');
    $sec_id = $this->request->getPost('sec_id');
    return $data['result'] = 'Section List Not Available/May be not published yet'; // Or an appropriate message
    // Set the directory path
    // $file_path = getBasePath() . "/judgment/cl/sectionlist/$list_dt";
    $file_path = getBasePath() . "/judgment/cl/sectionlist";
    if (!is_dir($file_path)) {  // Check if the directory exists
      return $data['result'] = 'Section List Not Available/May be not published yet'; // Or an appropriate message
    }
    $files = scandir($file_path);
    
    $filecount = count($files);
    // Construct the file path based on sec_id
    if ($sec_id == "0") {
      $file_path .= "/sectionlist_M_" . $board_type . "_" . $list_dt . ".html";
    } else {
      $file_path .= "/sectionlist_M_" . $board_type . "_section-" . $sec_id . "_" . $list_dt . ".html";
    }

    // Check if the file exists
    if (file_exists($file_path)) {
      $content = file_get_contents($file_path);
      $content = str_replace("/home/judgment/cl/scilogo.png", "../print/scilogo.png", $content);
      return $data['result'] = $content;
     // return view('Listing/Report/seclist_get', ['content' => $content]);

    } else {
      return $data['result'] = 'Section List Not Available/May be not published yet';
      //return view('Listing/Report/seclist_get', ['error' => 'Section List Not Available/May be not published yet']);
    }
  }

  public function ntl_judge_dept()
  {
    $data['result_array']  = $this->ReportModel->ntl_judge_dept();
    return view('Listing/Report/ntl_judge_dept', $data);
  }

  public function cron_p()
  {
    $data['result_array'] = $this->ReportModel->ntl_judge();
    return view('Listing/Report/cron_p', $data);
  }


  public function cron_p_get()
  {
    $data['list_dt'] = $this->request->getGET('list_dt');
    $data['ucode'] = session()->get('login')['usercode'];
    $data['usertype'] = session()->get('login')['usertype'];

    $holiday_dates[] = "";
    $current_year = date('Y');
    $next_year = $current_year + 1;
    $result_holidays = $this->ReportModel->get_holidays($current_year, $next_year);

    foreach ($result_holidays as $row_holidays) {
      $holiday_dates[] = $row_holidays['working_date'];
    }
    $data['holiday_dates'] = $holiday_dates;
    $data['ct_q'] = $this->ReportModel->get_ct_q();

    return view('Listing/Report/cron_p_get', $data);
  }

  public function fresh_cases_stats()
  {
    return view('Listing/Report/fresh_cases_stats');
  }
  public function fresh_cases_stats_get()
  {
    $data['listing_dts_from'] = date('Y-m-d', strtotime($this->request->getPost('listing_dts_from')));;
    $data['listing_dts_to'] = date('Y-m-d', strtotime($this->request->getPost('listing_dts_to')));
    $data['result_array']  = $this->ReportModel->fresh_cases_stats_get($data['listing_dts_from'], $data['listing_dts_to']);
    return $data['result_array'];
  }
  public function allocated()
  {
    return view('Listing/Report/allocated');
  }

  public function get_allocation_report()
  {
    $data['mainhead'] = $this->request->getPost('mainhead');
    $data['board_type'] = $this->request->getPost('board_type');
    $data['list_dt'] = date('d-m-Y', strtotime($this->request->getPost('list_dt')));

    $data['board_type'] = $this->request->getPost('board_type');
    if ($data['mainhead'] == 'M') {
      $data['mainhead_descri'] = "Miscellaneous Hearing";
    }
    if ($data['mainhead'] == 'F') {
      $data['mainhead_descri'] = "Regular Hearing";
    }
    if ($data['mainhead'] == 'L') {
      $data['mainhead_descri'] = "Lok Adalat";
    }
    $data['result_array'] = $this->ReportModel->get_allocation_report($data['mainhead'], $data['board_type'], $data['list_dt']);
    $data['ReportModel'] = $this->ReportModel;
    return view('Listing/Report/get_allocation_report', $data);
  }
  //Start(Reports=>Monitoring=>Not Verified)
  public function not_verified()
  {
    return view('Listing/Report/not_verified');
  }



  public function getNotVerified()
  {
    if ($this->request->isAJAX()) {
      $request = service('request');
      $ddl_nv_r = $request->getPost('ddl_nv_r');
      $newCsrfHash = csrf_hash();
      $data = $this->Monitoring->getNotVerifiedData($ddl_nv_r);
      if ($data) {
        return $this->response->setJSON(['success' => true, 'data' => $data, 'csrfHash' => $newCsrfHash]);
      } else {
        return $this->response->setJSON(['success' => false, 'message' => 'No records found.']);
      }
    } else {
      return $this->response->setJSON(['success' => false, 'error' => 'Invalid request type.']);
    }
  }

  //End(Reports=>Monitoring=>Not Verified)



  //Start(Reports=>Monitoring=>Court Remark modify)

  public function CtRemarks_Changeby_user()
  {

    $data = [
      'case_result' => '',
      'app_name' => 'Change By User Count',
    ];

    $session = session()->get('login');

    if ($this->request->getMethod() === 'post') {
      $request = service('request');
      $on_date = date('Y-m-d', strtotime($request->getPost('on_date')));
      $result_array = $this->Monitoring->CtRemarks_Changeby_user_data($on_date);
     $data['case_result'] = $result_array;
      $data['on_date1'] = date('d-m-Y', strtotime($request->getPost('on_date')));
    }
    return view('Listing/Report/CtRemarks_changeby_user_count', $data);
  }



  public function CtRemarks_user_details()
  {
    if ($this->request->isAJAX()) {
      $newCsrfHash = csrf_hash();
      $request = service('request');
      $cl_date = $request->getPost('cl_date');
      $flag = $request->getPost('flag');
      $usercode = $request->getPost('usercode');
      $result = $this->Monitoring->CtRemarks_user_details($cl_date, $flag, $usercode);

      return $this->response->setJSON([
        'success' => count($result) > 0,
        'data' => $result,
        'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    }

    return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
  }

  // End(Reports=>Monitoring=>Court Remark modify)



  // Start(Reports=>Monitoring=>Listed Not Verified)

  public function listed_not_verified()
  {


    return view('Listing/Report/listed_not_verified');
  }


  public function get_listed_not_verified()
  {

    // Get input dates
    $txt_fd = $this->request->getPost('txt_fd');
    $txt_td = $this->request->getPost('txt_td');

    // Convert to proper date format
    $txt_fd = date('Y-m-d', strtotime($txt_fd));
    $txt_td = date('Y-m-d', strtotime($txt_td));
    $newCsrfHash = csrf_hash();
    $results = $this->Monitoring->getListedNotVerified($txt_fd, $txt_td);
    return $this->response->setJSON([
      'success' => count($results) > 0,
      'data' => $results,
      'message' => count($results) > 0 ? '' : 'No records found.',
      'csrfHash' => $newCsrfHash,
      'csrfName' => csrf_token()
    ]);
  }

  // End(Reports=>Monitoring=>Listed Not Verified)


 
  public function registerd_not_verified_not_listed()
  {

    $data['data'] = $this->Monitoring->getActiveCaseTypes();

    return view('Listing/Report/registerd_not_verified_not_listed', $data);
  }
 
  public function get_data_registerd_not_verified_not_listed()
  {
    //pr('get_data_registerd_not_verified_not_listed');
    $request = service('request');
    $ddl_nv_r = $request->getPost('ddl_nv_r');
    $idd = $request->getPost('idd');
    $newCsrfHash = csrf_hash();


    $results = $this->Monitoring->getRegisteredNotVerifiedNotListed($ddl_nv_r);

    if (!empty($results)) {
      return $this->response->setJSON([
        'success' => true,
        'data' => $results,
        'csrfHash' => $newCsrfHash,
       // 'csrfName' => csrf_token()
      ]);
    } else {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'No records found.',
        'csrfHash' => $newCsrfHash,
        //'csrfName' => csrf_token()
      ]);
    }
  }
  //Start(Reports=>Monitoring=>Case Alloted)

  public function case_alloted()
  {
    $usercode = (int)session()->get('login')['usercode'];
    $r_section = session()->get('login')['section'];
    $r_usertype = session()->get('login')['usertype'];
    $userModel = new Casetype();
    $chk_da = '';

    if ($usercode != '1' && $r_section != '30' && $r_usertype != '4' && $r_section != '20') {
      $sql = "Select empid,usertype  from master.users where usercode='$usercode'";
      $query = $this->db->query($sql);
      $results = $query->getResultArray();
      $r_user_id = $results[0]['empid'];
      $r_user_type = $results[0]['usertype'];
      $chk_da = " AND a.empid = '$r_user_id'";
    }

    $fil_trap_users = $userModel->getFilteredUsers($chk_da);
    return view('Listing/Report/case_alloted_view', [
      'usercode' => $usercode,
      'r_section' => $r_section,
      'r_usertype' => $r_usertype,
      'fil_trap_users' => $fil_trap_users,
      'r_user_type' => $r_user_type ?? null
    ]);
  }

  public function get_filing()
  {
    $request = \Config\Services::request();
    $chk_users = '';
    $chk_da = '';
    $ddl_users = $request->getPost('ddl_users');
    $usercode = (int)session()->get('login')['usercode'];
    $r_section = session()->get('login')['section'];
    $r_usertype =  session()->get('login')['usertype'];
    $chk_da= $this->Monitoring->getChkDa($usercode , $r_section ,$r_usertype,$ddl_users);

    $frm_dt = date('Y-m-d', strtotime($request->getPost('txt_frm_dt')));
    $to_dt = date('Y-m-d', strtotime($request->getPost('txt_to_dt')));
    $model = new Casetype();
    $data['frm_dt'] = $frm_dt;
    $data['to_dt'] = $to_dt;
    $data['model'] = $model;
    $data['chk_da'] = $chk_da;
    $data['chk_users'] = $chk_users;
    $data['sql'] = $model->get_filling($frm_dt, $to_dt, $ddl_users, $chk_da, $chk_users);
    $data['ddl_users'] = $ddl_users;

    $d_to_empids = [];

    if (count($data['sql']) > 0) {
      foreach ($data['sql'] as $record) {
        $d_to_empids[] = $record['d_to_empid'];
      }


      $userNames = [];

      foreach ($d_to_empids as $empid) {
        $userName = $model->getUserName($empid);
        if ($userName) {
          $userNames[$empid] = $userName;
        }
      }
      $data['get_usr_nm'] = $userNames;
    } else {
      $data['get_usr_nm'] = "";
    }


    return view('Listing/Report/get_filling_data', $data);
  }

  public function get_fil_record()
  {
    $request = service('request');
    $newCsrfHash = csrf_hash();
    $usercode = (int)session()->get('login')['usercode'];
    $r_section = session()->get('login')['section'];
    $r_usertype = session()->get('login')['usertype'];
    $chk_users = '';
    $ddl_users = $request->getPost('ddl_users');
    $hd_nm_id = $request->getPost('hd_nm_id');


    pr($hd_nm_id);

    if ($usercode != '1' && $r_section != '30' && $r_usertype != '4') {
      $chk_users = " and u.usercode='$usercode'";
    }

    $total_pages = 0;
    $frm_dt = date('Y-m-d', strtotime($request->getPost('txt_frm_dt')));
    $to_dt = date('Y-m-d', strtotime($request->getPost('txt_to_dt')));
    $l_sp_split = $request->getPost('l_sp_split');
    $r_sp_split = $request->getPost('r_sp_split');
    $remarks = '';
    $remarks1 = '';
    $emp_nm = '';
    $jn_users = '';
    $model = new Casetype();
    $data['sql'] = $model->get_fil_record($frm_dt, $to_dt, $ddl_users, $chk_users, $l_sp_split, $jn_users, $emp_nm, $remarks1, $remarks, $total_pages, $usercode, $r_section, $r_usertype, $hd_nm_id, $r_sp_split);
 
    $data['l_sp_split'] = $l_sp_split;
    $data['ddl_users'] = $ddl_users;
    $data['total_pages'] = 0;
    $d_to_empids = [];

    if (count($data['sql']) > 0) {
      foreach ($data['sql'] as $record) {
        $d_to_empids[] = $record['d_to_empid'];
      }


      $userNames = [];

      foreach ($d_to_empids as $empid) {
        $userName = $model->getUserName($empid);
        if ($userName) {
          $userNames[$empid] = $userName;
        }
      }
      $data['get_usr_nm'] = $userNames;
    } else {
      $data['get_usr_nm'] = "";
    }
    $data['Monitoring'] = $this->Monitoring;

    //pr($data);

    return view('Listing/Report/get_fil_record', $data);
  }



  public function getCsrfToken()
  {
    return $this->response->setJSON([
      'csrfToken' => csrf_hash(),
      'csrfName' => csrf_token()
    ]);
  }

  public function non_presiding_coram()
  {
    return view('Listing/Report/non_presiding_coram');
  }

  public function non_presiding_coram_get()
  {
    $data['board_type'] = $this->request->getPost('board_type');
    $data['mainhead'] = $this->request->getPost('mainhead');
    $data['reg_unreg'] = $this->request->getPost('reg_unreg');
    $data['result_array'] = $this->ReportModel->non_presiding_coram_get($data['board_type'], $data['mainhead'], $data['reg_unreg']);
    return view('Listing/Report/non_presiding_coram_get', $data);
  }

  public function matters_listed()
  {
      $data['section_name'] = $this->ReportModel->section_name();
      return view('Listing/Report/matters_listed', $data);
   
  }
  
  public function matters_listed_get()
  {
      $section = $this->request->getPost('section') ?? '';
      $da = $this->request->getPost('da') ?? '';
      $stage = $this->request->getPost('stage') ?? '';
      $fromDays = $this->request->getPost('fromDays') ?? '';
      $toDays = $this->request->getPost('toDays') ?? '';
      $year = $this->request->getPost('year') ?? '';
      $daysRange = $this->request->getPost('daysRange') ?? '';
      
        $result_array = $this->ReportModel->matters_listed($section, $da, $stage, $fromDays, $toDays, $year, $daysRange);
        $data['cases'] = $result_array;
      return $data['cases'];
     
  }

  public function get_DA_sectionwise()
  {
    $data_array = $this->ReportModel->get_DA_sectionwise($this->request->getPost('secId'));
    return $this->response->setJSON($data_array);
  }

  public function get_matters_listed()
  {
    pr($this->request->getPost());
  }

  public function receivedFile()
  {
    $data['usercode'] = (int)session()->get('login')['usercode'];
    return view('Listing/Report/receivedFile_view', $data);
  }

  public function getReceivedFile_old()
  {
    $chk_users = '';
    $usercode  = (int)session()->get('login')['usercode'];
    $r_section  = session()->get('login')['section'];
    $r_usertype =  session()->get('login')['usertype'];
    $newCsrfHash = csrf_hash();

    $jn_users_r = '';
    $remarks = '';
    $com_rmk = '';
    $emp_nm = '';
    $sno = 0;

    if ($usercode != '1' && $r_section != '30' && $r_usertype != '4') {
      $chk_users = " AND u.usercode = '$usercode'";
    }

    $category = $this->request->getPost('category');
    $frm_dt = date('Y-m-d', strtotime($this->request->getPost('txt_frm_dt')));
    $to_dt = date('Y-m-d', strtotime($this->request->getPost('txt_to_dt')));
    $remarks = '';
    $com_rmk = '';

    if ($usercode == '9796') {
      $remarks = "TAG -> SCN' OR remarks = 'CAT -> SCN";
      $com_rmk = "SCN -> IB-Ex";
    }

    $emp_nm = "AND r_by_empid = $usercode";
 
    $results = $this->Monitoring->getCaseTitle($frm_dt,$to_dt ,$emp_nm , $remarks ,$jn_users_r);
    //pr($results);
    
    if ($results) {
    
      return $this->response->setJSON([
        'success' => true,
        'data' => $results,
        'usercode' => $usercode,
        // 'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    } else {
      return $this->response->setJSON([
        'success' => false,
        'usercode' => $usercode,
        'message' => 'No records found.',
        // 'csrfHash' => $newCsrfHash,
        //'csrfName' => csrf_token()
      ]);
    }
  }

  public function getReceivedFile()
  {
    $chk_users = '';
    $usercode  = (int)session()->get('login')['usercode'];
    $r_section  = session()->get('login')['section'];
    $r_usertype =  session()->get('login')['usertype'];
    $newCsrfHash = csrf_hash();

    $jn_users_r = '';
    $remarks = '';
    $com_rmk = '';
    $emp_nm = '';
    $sno = 0;

    if ($usercode != '1' && $r_section != '30' && $r_usertype != '4') {
      $chk_users = " AND u.usercode = '$usercode'";
    }

    $category = $this->request->getPost('category');
    $frm_dt = date('Y-m-d', strtotime($this->request->getPost('txt_frm_dt')));
    $to_dt = date('Y-m-d', strtotime($this->request->getPost('txt_to_dt')));
    $remarks = '';
    $com_rmk = '';

    if ($usercode == '9796') {
      $remarks = "TAG -> SCN' OR remarks = 'CAT -> SCN";
      $com_rmk = "SCN -> IB-Ex";
    }

    $emp_nm = "AND r_by_empid = $usercode";
 
    $data['results'] = $this->Monitoring->getCaseTitle($frm_dt,$to_dt ,$emp_nm , $remarks ,$jn_users_r);
    $data['usercode'] = $usercode;
    $data['Monitoring'] = $this->Monitoring;
    $data['category'] = $category;
    return view('Listing/Report/receivedFile_view1', $data);
  }

  private function get_usr_nm($empid)
  {
    $query = $this->db->query("SELECT name FROM master.users WHERE empid = ?", [$empid]);
    $row = $query->getRow();
    return $row ? $row->name : '';
  }

  private function get_usr_nm_uid($usercode)
  {
    $query = $this->db->query("SELECT name FROM master.users WHERE usercode = ?", [$usercode]);
    $row = $query->getRow();
    return $row ? $row->name : '';
  }


  public function monitoring_Error()
  {
  
    $data['model'] = new Casetype();
    
    $result_array = $data['model']->monitoring_Error_Report();
    $data['case_result'] = $result_array;
    //pr($data);
    return view('Listing/Report/monitoring_Error_view', $data);
  }


  // public function monitoring_Error_Details()
  // {
  //   $newCsrfHash = csrf_hash();
  //   $diary_no = $this->request->getPost('diary_no');
  //   $id = $this->request->getPost('id');
  //   $data['model'] = new Casetype();
  //   $arr =  $data['model']->monitoring_Error_details($diary_no, $id);
  //   //pr($arr);

  //   if (!empty($arr)) {
  //     return $this->response->setJSON([
  //       'success' => true,
  //       'data' => $arr,
  //       'csrfHash' => $newCsrfHash,
  //       'csrfName' => csrf_token()
  //     ]);
  //   } else {
  //     return $this->response->setJSON([
  //       'success' => false,
  //       'message' => 'No records found.',
  //       'csrfHash' => $newCsrfHash,
  //       'csrfName' => csrf_token()
  //     ]);
  //   }
  // }

  public function monitoring_Error_Details()
    {
        $this->response->setContentType('application/json');

        $id = $this->request->getPost('id');
        $diary_no = $this->request->getPost('diary_no');

        $data['model'] = new Casetype();
        $arr =$data['model']->monitoring_Error_details($diary_no, $id);

        return $this->response->setJSON($arr ?? []);
    }

    public function monitoringErrorDawiseCount()
    {
        $model = new ReportsModel();
        $data = $model->getMonitoringErrorDawiseCount();

        return $this->response->setJSON($data ?? []);
    }
  public function listing_info()
  {
    return view('Listing/Report/listing_info');
  }
  public function listing_info_get()
  {
    $data['board_type'] = $this->request->getPost('board_type');
    $data['list_dt'] = date('Y-m-d', strtotime($this->request->getPost('listing_dts')));
    $data['h3_head'] = "Listing Information Coram Wise for dated " . $this->request->getPost('listing_dts');
    $data['ucode'] = session()->get('login')['usercode'];
    $data['result_array'] = $this->ReportModel->listing_info_get($data['list_dt'], $data['board_type']);
    return view('Listing/Report/listing_info_get', $data);
  }

  public function causelistOrRop()
  {
    $data['section_name'] = $this->ReportModel;
    $data['judge'] = is_data_from_table('master.judge', "is_retired='N' and display='Y' order by judge_seniority", ' jcode,jname ', $row = 'A');
    return view('Listing/Report/causelist_or_rop', $data);
  }

  public function get_causelist_or_rop()
  {
    $data['ReportModel'] = $this->ReportModel;
    $frm_dt = date('Y-m-d', strtotime($this->request->getPost('txt_frm_date')));
    $to_dt = date('Y-m-d', strtotime($this->request->getPost('txt_to_date')));
    $aor_code = $this->request->getPost('txt_aor_code');
    $ddl_judge = $this->request->getPost('ddl_judge');

    $advocate = $judge = '';
    if ($aor_code != '') {
      $advocate = " join advocate adv on adv.diary_no=b.diary_no and adv.display='Y' join bar z on z.bar_id=adv.advocate_id
					   and aor_code='$aor_code'";
    }
    if ($ddl_judge != '') {
      $judge = " join roster_judge rj on rj.roster_id=a.roster_id and rj.display='Y' and  judge_id='$ddl_judge'";
    }

    //$data['results'] = $this->ReportModel->getMatters($frm_dt, $to_dt, $advocate, $judge);
    $data['results'] = $this->ReportModel->getMattersReport($frm_dt, $to_dt, $advocate, $judge, $aor_code, $ddl_judge);
    return view('Listing/Report/get_causelist_or_rop', $data);
  }

  public function get_text_file()
  {
    $idd = $this->request->getPost('idd');
    //$baseurl = base_url();
    //$fil_nm = $baseurl."/".$idd;
    //$fil_nm = "officereport/2024/29/1_2024_2024-02-05_231.html";
    $fil_nm = $idd;
    if (file_exists($fil_nm)) {
      $fileSize = filesize($fil_nm);
      $fileContent = file_get_contents($fil_nm);
      $fileContentUtf8 = mb_convert_encoding($fileContent, 'UTF-8', 'auto');
      echo $fileContentUtf8;
    }
  }

  public function verification()
  {
    return view('Listing/Report/verification_view');
  }

  public function get_verification()
  {
    $newCsrfHash = csrf_hash();
    $txt_fd = date('Y-m-d', strtotime($this->request->getPost('txt_fd')));
    $txt_td = date('Y-m-d', strtotime($this->request->getPost('txt_td')));
    $data['model'] = new Casetype();
    //$arr =  $data['model']->get_verification_data($txt_fd, $txt_td);
    $arr =  $this->Monitoring->get_verification_data($txt_fd, $txt_td);

    if (count($arr) > 0) {
      return $this->response->setJSON([
        'success' => true,
        'data' => $arr,
        'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    } else {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'No records found.',
        'csrfHash' => $newCsrfHash,
        'csrfName' => csrf_token()
      ]);
    }
  }
  public function listing_statistics_details()
  {
    $type = $this->request->getGet('type');
    $listingdate = $this->request->getGet('listingdate');
    if($type === "SL"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_sl($type, $listingdate);
      $data['report_name'] = 'Listed in Supplementary List';
    }
    if($type === "SU"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_su($type, $listingdate);
      $data['report_name'] = 'Updated after Supplementary List';
    }
    if($type === "SE"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_se($type, $listingdate);
      $data['report_name'] = 'Elimination in Supplementary List';
    }
    if($type === "AL"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_al($type, $listingdate);
      $data['report_name'] = 'Advance Listed';
    }
    if($type === "AE"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_ae($type, $listingdate);
      $data['report_name'] = 'Advance Eliminated';
    }
    if($type === "AU"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_au($type, $listingdate);
      $data['report_name'] = 'Updated After Advance List ';
    }
    if($type === "FL"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_fl($type, $listingdate);
      $data['report_name'] = 'Allocated in Final List';
    }
    if($type === "FE"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_fe($type, $listingdate);
      $data['report_name'] = 'Eliminated in Final List';
    }
    if($type === "FU"){
      $data['result_array'] = $this->ListingStatisticsModel->listing_statistics_details_fu($type, $listingdate);
      $data['report_name'] = 'Updated after Final List published';
    }
    
   
   
    return view('Listing/Report/listing_statistics_details', $data);
  }

  public function AdvanceList(){
    return view('Listing/Report/advance_list');
    
  }

  public function verify_detail_report_da_wise()
  {
    $data['list_dt'] = $this->request->getPost('ldates');
    $data['model']=$this->Monitoring;
    return view('Listing/Report/verify_detail_report_da_wise',$data);
    
  }
}
