<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use App\Models\Court\CourtMasterModel;

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 6/12/19
 * Time: 6:56 PM
 */

class JudgesRoster extends BaseController
{
    public $CourtMasterModel;
    public function __construct()
    {
        helper(['form', 'url', 'html', 'download']);
        $this->CourtMasterModel = new CourtMasterModel();
    }

    public function getSession($session)
    {
        $this->session->set_userdata('dcmis_user_idd', $session);
        $this->index();
    }

    public function index()
    {
        return view('Listing/judges_roster/index');
    }

    public function ifSittingPlanExist01()
    {
        extract($_POST); 
        
        $data['causelistDate'] = $causelistDate;
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        
        $data['workingDayData']=$this->CourtMasterModel->getWorkingDayData($causelistDate);
        $data['sittingPlan']=$this->CourtMasterModel->getSittingPlan($causelistDate);
        if(isset($data['workingDayData']['is_holiday']) && !empty($data['workingDayData']['is_holiday'])){
            return "holiday";
        } else {
            $confirmation="true";
        }
       $toModify = isset($toModify) ? $toModify : false;
        if((isset($data['workingDayData']['is_holiday']) && $data['workingDayData']['is_holiday']==0) || $confirmation=="true"){
            
            if(sizeof($data['sittingPlan'])>0 && $toModify!=true){
                return $this->getSittingPlanToPrint();
            }else{
                return $this->judgesLeaveDetail();
            }
        } 
    }

    public function ifSittingPlanExist()
    {
        extract($_POST); 
        
        $data['causelistDate']=$causelistDate;
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        
        $data['workingDayData']=$this->CourtMasterModel->getWorkingDayData($causelistDate);
        $data['sittingPlan']=$this->CourtMasterModel->getSittingPlan($causelistDate);
        
        $toModify = isset($toModify) ? $toModify : false;
        
        if((empty($data['workingDayData'])|| isset($data['workingDayData'][0]['is_holiday']) && $data['workingDayData'][0]['is_holiday']==0) || $confirmation == "true"){
            if(sizeof($data['sittingPlan'])>0 and $toModify!=true){
                return $this->getSittingPlanToPrint();
            }else{
                return $this->judgesLeaveDetail();
            }
        }
        else{
            echo "holiday";
        }
    }


    public function saveJudgesOnLeave()
    {    
        extract($_POST);
        $if_success=true;
        $usercode  =1;
        if(isset($from)) {
            foreach($from as $sittingJudge){
                $dataForUpdate=array('jcode' => $sittingJudge, 'next_dt' => date_format(date_create($causelistDate), 'Y-m-d'),'is_on_leave' => 0,'usercode' => $usercode,'updated_on'=>date('Y-m-d h:i:s'),'display'=>'Y');
                $if_success=$this->CourtMasterModel->saveJudgesOnLeave($dataForUpdate);
            }
        }
        if(isset($to)) {    
            foreach($to as $nonSittingJudge){
                $dataForUpdate=array('jcode' => $nonSittingJudge, 'next_dt' => date_format(date_create($causelistDate), 'Y-m-d'),'is_on_leave' => 1,'usercode' => $usercode,'updated_on'=>date('Y-m-d h:i:s'),'display'=>'Y');
                $if_success=$this->CourtMasterModel->saveJudgesOnLeave($dataForUpdate);
            }
        }
        
        $data['causelistDate']=$causelistDate;
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $data['sittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'0');
        
        $data['nonSittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'1');
        $data['sittingPlan']=$this->CourtMasterModel->getSittingPlan($causelistDate);
        return view('Listing/judges_roster/judgesRoster', $data);
    }

    public function judgesLeaveDetail()
    {
        extract($_POST);
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $data['judges']=$this->CourtMasterModel->getJudge();
        
        $data['sittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'0');
    
        $data['nonSittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'1');
        return view('Listing/judges_roster/judgesLeaveDetail', $data);
    }

    public function getSittingPlanToPrint()
    {
        extract($_POST);
        $data['causelistDate']=$causelistDate;
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $data['sittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'0');
        $data['nonSittingJudges']=$this->CourtMasterModel->getJudgeLeaveDetail($causelistDate,'1');
        $data['sittingPlan']=$this->CourtMasterModel->getSittingPlan($causelistDate,1);
        $data['workingDayData']=$this->CourtMasterModel->getWorkingDayData($causelistDate);
        
        return view('Listing/judges_roster/sittingPlanForPrint', $data);
    }
    public function saveSittingList()
    {
        extract($_POST);
        $result=false;
        $usercode =  session()->get('login')['usercode'];
        $sitting_plan_details_id=0;
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $savedData=$this->CourtMasterModel->getSittingPlan($causelistDate);
        
        if(sizeof($savedData)>0){
            $sitting_plan_details_id=$savedData[0]['sitting_plan_details_id'];
            $if_finalized=$savedData[0]['if_finalized'];
            $this->CourtMasterModel->deleteSittingPlan($sitting_plan_details_id,$if_finalized);
        }
        if($sitting_plan_details_id == 0){
            $dataForSittingPlan=array('next_dt'=>$causelistDate,'user_ip'=>'','updated_on'=>date('Y-m-d H:i:s'),'usercode'=>$usercode);
            $sitting_plan_details_id=$this->CourtMasterModel->saveSittingDeatils($dataForSittingPlan);
        }
        foreach($sittingPlan as $index=>$bench){
            if($bench['court_number']=='' && $bench['bench_type']=='T'){
                $judges = isset($bench['judges']) ? $bench['judges']: [];
                if(sizeof($judges)>0){
                    //print_r($bench['judges']); //::TODO check if any judge not alloted any bench
                }
            } else{
                $if_in_printed=0;
                if(isset($bench['if_in_printed']) && $bench['if_in_printed']){
                    $if_in_printed=1;
                }
                $board_type="J";
                $if_special_bench=0;
                if(trim($bench['bench_type'])=='C'){
                    $board_type="C";
                }
                elseif (trim($bench['bench_type'])=='CC'){
                    $board_type="CC";
                }
                if(trim($bench['bench_type'])=='S'){
                    $if_special_bench=1;
                }
                $dataCourtDetails=array('sitting_plan_details_id'=>$sitting_plan_details_id,'court_number'=>$bench['court_number'],'board_type'=>$board_type,'if_special_bench'=>$if_special_bench,'header_remark'=>$bench['header'],'footer_remark'=>$bench['footer'],'usercode'=>$usercode,'updated_on'=>date('Y-m-d H:i:s'),'display'=>'Y','if_in_printed'=>$if_in_printed,'bench_start_time'=>$bench['bench_start_time']);
                //$dataJudges=$bench['judges'];
                $dataJudges = isset($bench['judges']) ? $bench['judges']: [];
                $this->CourtMasterModel->saveSittingPlan($dataCourtDetails,$dataJudges);
            }
        }
        echo $result=true;
    }
    public function finalizeSittingPlan()
    {
        extract($_POST);
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $result=$this->CourtMasterModel->finalizeSittingPlan($causelistDate);
        echo $result;
    }
    public function generateRoster()
    {
        extract($_POST);
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $ifRostergenerated=$this->CourtMasterModel->ifRostergenerated($causelistDate,$mainhead);
        if($ifRostergenerated == '0'){
            echo $status=$this->CourtMasterModel->generateRoster($causelistDate,$mainhead);
        } else{
         echo "exist";
        }
    }
    public function copySittingPlan()
    {
        extract($_POST);
        $causelistDate=date_format(date_create($causelistDate), 'Y-m-d');
        $savedData=$this->CourtMasterModel->getSittingPlan($toDate);
        if(sizeof($savedData)>0){
            echo "exist";
        } else{
            $status=$this->CourtMasterModel->doCopySittingPlan($causelistDate,$toDate);
            echo $status;
        }
    }
}