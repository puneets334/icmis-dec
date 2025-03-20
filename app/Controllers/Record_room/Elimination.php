<?php

namespace App\Controllers\Record_room;

use App\Controllers\BaseController;

use App\Models\Record_room\Model_record;
use App\Models\Record_room\TransactionModel;
use App\Models\Entities\Model_Ac;
use App\Models\Record_room\EliminationModel;
use App\Models\Listing\CaseType;

class Elimination extends BaseController
{
    public $EliminationModel;

    function __construct()
    {
        $this->EliminationModel = new EliminationModel();
    }

    public function get_sessionDeatils()
    {
        return $this->index();
    }

    public function index()
    {
        $data['app_name'] = 'Eliminate Case';
        $data['caseType'] = $this->EliminationModel->caseType();
        return view('Record_room/Elimination/elimination_entry', $data);
    }



    public function searchCaseForElimination()
    {
        $casetype = $this->request->getPost('casetype');
        $caseno = $this->request->getPost('caseno');
        $caseyear = $this->request->getPost('caseyear');
        $diary_number = $this->request->getPost('diary_number');
        $diary_year = $this->request->getPost('diary_year');

        $data['eliminationData'] = $this->EliminationModel->eliminationdatatoshow($casetype, $caseno, $caseyear, $diary_number, $diary_year);
        $data['caseRemarksHead'] = $this->EliminationModel->getCaseRemarksHead();
        $data['weededBy'] = $this->EliminationModel->getWeededBy();
        $data['judge'] = $this->EliminationModel->getJudge();

        return view('Record_room/Elimination/viewEliminationData', $data);
    }

    function updateElimination(){
        
         $result="ERROR";
         $actionRequired = $this->request->getPost('actionRequired');
         $diary_no = $this->request->getPost('diary_no');
         if($actionRequired=='I'){
             $dataArray = array(
                 'fil_no' => $this->request->getPost('diary_no'),
                 'ele_dt' => date('Y-m-d', strtotime($this->request->getPost('eliminationDate'))),
                 'usercode' => $this->request->getPost('usercode'),
                 'ent_dt' => date('Y-m-d H:i:s'),
                 'display' => 'Y',
                 'remark'  => $this->request->getPost('remark'),
                 'weeded_by'  => $this->request->getPost('weededby')
             );
            // echo $dataArray;
             $result = $this->EliminationModel->insertElimination($dataArray);
             $result="SUCCESS";
         }
         else{
             $dataArray = array(
                 'ele_dt' => date('Y-m-d', strtotime($this->request->getPost('eliminationDate'))),
                 'usercode' => $this->request->getPost('usercode'),
                 'ent_dt' => date('Y-m-d H:i:s'),
                 'remark'  => $this->request->getPost('remark'),
                 'weeded_by'  => $this->request->getPost('weededby')
             );
 
             $result = $this->EliminationModel->updateElimination($diary_no,$dataArray);
             $result="SUCCESS";
         }
         /*if($result=="SUCCESS"){
             $diary_no = $this->request->getPost('diary_no');
             $data['disposalDetail'] = $this->EliminationModel->getDisposalDetail($diary_no);
             $data['caseRemarksHead']=$this->EliminationModel->getCaseRemarksHead();
             $data['judge']=$this->EliminationModel->getJudge();
             $this->load->view('Elimination/viewDisposalDetail', $data);
         }*/
         if($result==="SUCCESS"){
             $jud_id="";
             if($this->request->getPost('judge1')!=0){
                 $jud_id=$this->request->getPost('judge1');
                 if($this->request->getPost('judge2')!=0){
                     $jud_id.=','.$this->request->getPost('judge2');
                     if($this->request->getPost('judge3')!=0){
                         $jud_id.=','.$this->request->getPost('judge3');
                         if($this->request->getPost('judge4')!=0){
                             $jud_id.=','.$this->request->getPost('judge4');
                             if($this->request->getPost('judge5')!=0){
                                 $jud_id.=','.$this->request->getPost('judge5');
                             }
                         }
                     }
                 }
             }
 
             $dataArray = array(
                 'usercode' => $this->request->getPost('usercode'),
                 'disp_type' => $this->request->getPost('caseRemarksHead'),
                 'crtstat' => $this->request->getPost('crtstat'),
                 'dispjud' => $this->request->getPost('dispJudge'),
                 'disp_dt' => date('Y-m-d', strtotime($this->request->getPost('disposalDate'))),
                 'jud_id' => $jud_id,
                 'camnt' =>  $this->request->getPost('amount'),
                 'ord_dt'    =>  date('Y-m-d', strtotime($this->request->getPost('orderDate'))),
                 'disp_type_all' =>  $this->request->getPost('caseRemarksHead'),
                 'year'  =>  date('Y', strtotime($this->request->getPost('disposalDate'))),
                 'month'  =>  date('m', strtotime($this->request->getPost('disposalDate'))),
                 'ent_dt' => date('Y-m-d H:i:s')
             );
             $this->EliminationModel->updateDisposal($diary_no,$dataArray);
             $result="<div class=\"alert alert-success alert-dismissible\">
                 <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>                
                 Data Updated Successfully.
               </div>";
             echo $result;
         }
         else{
             $result="<div class=\"alert alert-error alert-dismissible\">
                 <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>                
                 There is some problem while updating Elimination detail, Please Contact Computer-Cell.
               </div>";
             echo $result;
         }
     }
}
