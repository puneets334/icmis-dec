<?php

namespace App\Controllers\Court;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterReports_model;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\Common\Dropdown_list_model;
use App\Controllers\Court\LIVE_URL;
use DirectoryIterator;
use FilesystemIterator;

class CourtMasterReports extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;
    public $Dropdown_list_model;

    function __construct(){

        $this->model = new CourtMasterReports_model();
        $this->Fpdf = new Fpdf();
        $this->request = service('request');
		$uri = $this->request->uri;
      
    }

   public function index(){
	    $usercode = session()->get('login')['usercode'];
		$usertype = $this->model->getUsertype($usercode);
		if(!empty($usertype)){
		    $usertype= $usertype[0]['usertype'];
		}else{
			$usertype = 0;
		}
		$data['judges']= $this->model->getJudges($usertype, $usercode);
		$data['usertype']=$usertype;
		return view('Court/CourtMasterReports/judge_disposal_view', $data);
   }


    public function reports_listing_get(){
        $request = \Config\Services::request();
		$fromDate = date('Y-m-d', strtotime($request->getPost('dateFrom')));
		$toDate = date('Y-m-d', strtotime($request->getPost('dateTo')));
		$judge= $request->getPost('judge_selected');
		if($judge!=''){
			$judge= explode('^',$judge);
			$judgename= $judge[1];
			$jcode=  $judge[0];
		}else{
			$judgename= '';
			$jcode=  0;
		}
		
		$judge_result= array();
		$usercode = session()->get('login')['usercode'];
    
		if(isset($fromDate) && isset($toDate) && isset($jcode) && $fromDate!='1970-01-01' && $toDate!='1970-01-01'){
			$point1= $this->model->stats_point1($fromDate,$toDate,$jcode);
            $point2a= $this->model->stats_point2a($fromDate,$toDate,$jcode);
			$point2b= $this->model->stats_point2b($fromDate,$toDate,$jcode);
			$point2c= $this->model->stats_point2c($fromDate,$toDate,$jcode);
			$point3= $this->model->stats_point3($fromDate,$toDate,$jcode);
			$point3a= $this->model->stats_point3a($fromDate,$toDate,$jcode);
			$point3b= $this->model->stats_point3b($fromDate,$toDate,$jcode);
			$point3c= $this->model->stats_point3c($fromDate,$toDate,$jcode);
			$point_judgment= $this->model->stats_judgment($fromDate,$toDate,$jcode);
			$point_notice_disposal= $this->model->stats_notice_disposal($fromDate,$toDate,$jcode);
			$point_notice_disposal_misc= $this->model->stats_notice_disposal_misc($fromDate,$toDate,$jcode);
			$point_notice_disposal_regular= $this->model->stats_notice_disposal_regular($fromDate,$toDate,$jcode);
			$point_notice= $this->model->stats_notice($fromDate,$toDate,$jcode);
			$stats_point1=count($point1);
			$stats_point2a=count($point2a);
			 $stats_point2b=count($point2b);
			 $stats_point2c=count($point2c);
			 $stats_point3=count($point3);
			 $stats_point3a=count($point3a);
			 $stats_point3b=count($point3b);
			 $stats_point3c=count($point3c);
			 $stats_point_judgment=count($point_judgment);
			 $stats_notice_disposal=count($point_notice_disposal);
			 $stats_notice_disposal_misc=count($point_notice_disposal_misc);
			 $stats_notice_disposal_regular=count($point_notice_disposal_regular);
			 $stats_notice=count($point_notice);
			 $judge_result['point1']= $stats_point1;
			 $judge_result['point2a']= $stats_point2a;
			 $judge_result['point2b']=$stats_point2b;
			 $judge_result['point2c']=$stats_point2c;
			 $judge_result['point3']=$stats_point3;
			 $judge_result['point3a']=$stats_point3a;
			 $judge_result['point3b']=$stats_point3b;
			 $judge_result['point3c']=$stats_point3c;
			 $judge_result['judgment']=$stats_point_judgment;
			 $judge_result['notice_disposal']=$stats_notice_disposal;
			 $judge_result['notice_disposal_misc']=$stats_notice_disposal_misc;
			 $judge_result['notice_disposal_regular']=$stats_notice_disposal_regular;
			 $judge_result['notice']=$stats_notice;
			 $data['judge_result']=$judge_result;
			 $data['fromDate']=$fromDate;
			 $data['toDate']=$toDate;
			 $data['judgename']= $judgename;
			 $data['jcode']=$jcode;
		}

		return view('Court/CourtMasterReports/judge_disposal',$data);
   }


   public function detailed_result(){
	    $point_no = $this->request->getPost('point_no');
		$fromDate = $this->request->getPost('fromDate');
		$toDate = $this->request->getPost('toDate');
		$jcode = $this->request->getPost('jcode');
		$judgename = $this->request->getPost('judgename');
		$CSRF_TOKEN = $this->request->getPost('CSRF_TOKEN');

		$data['fromDate']= $fromDate;
		$data['toDate']= $toDate;
		$data['judgename']= $judgename;
		$curdate= date('d-m-Y');
		$cur_time= date('h:i A');
		if($point_no=='1') {
			$data['heading']='List of matters which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by the bench in which <b>'.$judgename.'</b> was participant as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point1($fromDate, $toDate, $jcode);
		}
		if($point_no=='2a') {
			$data['heading']='List of <b>Reportable Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by the bench in which <b>'.$judgename.'</b> was participant as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point2a($fromDate, $toDate, $jcode);
		}
		if($point_no=='2b') {
			$data['heading']='List of <b>Non-Reportable Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by the bench in which <b>'.$judgename.'</b> was participant as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point2b($fromDate, $toDate, $jcode);
		}
		if($point_no=='2c') {
			$data['heading']='List of matters for which Record of Proceeding not available or Connected matters which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by the bench in which <b>'.$judgename.'</b> was participant as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point2c($fromDate, $toDate, $jcode);
		}
		if($point_no=='3') {
			$data['heading']='List of matters which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point3($fromDate, $toDate, $jcode);
		}
		if($point_no=='3a') {
			$data['heading']='List of <b>Reportable Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point3a($fromDate, $toDate, $jcode);
		}
		if($point_no=='3b') {
			$data['heading']='List of <b>Non-Reportable Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point3b($fromDate, $toDate, $jcode);
		}
		if($point_no=='3c') {
			$data['heading']='List of matters for which Record of Proceeding not available or Connected matters which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_point3c($fromDate, $toDate, $jcode);
		}
		if($point_no=='judgment') {
			$data['heading']='List of matters in which Judgment is delivered from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_judgment($fromDate, $toDate, $jcode);
		}
		if($point_no=='notice_disposal') {
			$data['heading']='List of <b>After Notice Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_notice_disposal($fromDate, $toDate, $jcode);
		}
		if($point_no=='notice_disposal_misc') {
			$data['heading']='List of <b>Misc. After Notice Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_notice_disposal_misc($fromDate, $toDate, $jcode);
		}
		if($point_no=='notice_disposal_regular') {
			$data['heading']='List of <b>Regular After Notice Matters </b>which are disposed off from <b>'.date('d-m-Y',strtotime($fromDate)).'</b> to <b>'.date('d-m-Y',strtotime($toDate)).
				'</b> by <b>'.$judgename.'</b> as Presiding Judge as on '.$curdate.' at '.$cur_time;
			$data['detail_result'] = $this->model->stats_notice_disposal_regular($fromDate, $toDate, $jcode);
		}
		return view('Court/CourtMasterReports/judge_disposal_detail', $data);
   }




}