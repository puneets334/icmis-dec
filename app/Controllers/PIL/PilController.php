<?php
namespace App\Controllers\PIL;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Entities\Model_ec_pil_group_file;
use App\Models\Entities\Model_ref_pil_category;
use App\Models\Entities\Model_ec_pil;
use App\Models\Entities\Model_ec_pil_log;
use App\Models\Entities\Model_ref_state;
use App\Libraries\Fpdf;
use App\Libraries\Common;
use App\Models\PIL\PilModel;
use App\Models\Court\CourtMasterModel; 


//use Mpdf;
//use \setasign\Fpdi\PdfParser\StreamReader;
ini_set('memory_limit','-1');
class PilController extends BaseController
{
    public $ecPilGroupFile;
    public $masterRefPilCategory;
    public $ecPil;
    public $ecPilLog;
    public $pdf;
    public $common;
    public $commonHelper;
    public $masterRefState;
    public $PilModel;
    protected $CourtMasterModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $this->ecPilGroupFile =  new Model_ec_pil_group_file();
        $this->ecPil =  new Model_ec_pil();
        $this->masterRefPilCategory =  new Model_ref_pil_category();
        $this->ecPilLog =  new Model_ec_pil_log();
        $this->masterRefState = new Model_ref_state();
        $this->pdf = new Fpdf();
        $this->common = new Common();
        $this->CourtMasterModel = new CourtMasterModel();

        $this->PilModel = new PilModel();
        ini_set('memory_limit','-1');
        define('FPDF_FONTPATH', include(FCPATH.'assets/font/timesb.php'));


    }

    public function index($msg='')
    {
        $data['msg']=$msg;
        $data['app_name'] = 'PIL';
        $data['pilData'] = $this->PilModel->getPilData();
 
        return view('PIL/showPilData', $data);

    }
    public function addToPilGroupShow($msg="",$ecPilGroupId=0,$searchedYear=0)
    {
 
        $usercode = $_SESSION['login']['usercode'];
        $diaryNo='';
        $diaryYear='';
        $ecPilGroupId='';
        if($searchedYear==0){
            $searchedYear=date("Y");
        }
        $data['msg']=$msg;
        $data['pilGroup']=array();
        $resultArray = $this->PilModel->getPilGroup();
   
        if(!empty($resultArray))
        {
            $data['pilGroup'] = $resultArray;
        }else{
            $data['pilGroup']=array();
        }

        
        $data['ecPilGroupId']=$ecPilGroupId;
        $data['searchedYear']=$searchedYear;
   
        return view('PIL/addToPilGroup', $data);
    }


    public function addToPilGroupResult()
    {
        $usercode = $_SESSION['login']['usercode'];
        if(!empty($_POST))
        {
            if (!empty($_POST['ecPilGroupId']))
            {
                $ecPilGroupId = $_POST['ecPilGroupId'];

            }
            
            $diaryNo = $_POST['diaryNo'];
            $diaryYear = $_POST['diaryYear'];
            if ($diaryNo != '' && $diaryYear != '')
            {
                $ecPilId = $this->PilModel->getPilId($diaryNo, $diaryYear);
                
                if (!empty($ecPilId) && $ecPilId['id'] != null) {
 
                    $rowsaffected = $this->PilModel->addInPilGroup($ecPilGroupId, $ecPilId['id'], $usercode);
                    

                    if ($rowsaffected > 0) {
                       
                        $data['msg']="Added Successfully.";
                        $data['casesInPilGroup'] = $this->PilModel->getCasesInPilGroup($ecPilGroupId);
                        $data['ecPilGroupId']=$ecPilGroupId;
 
                    }
                } else {
 
                    $data['msg']="No Record found.";
                    $data['casesInPilGroup'] = $this->PilModel->getCasesInPilGroup($ecPilGroupId);
                    $data['ecPilGroupId']=$ecPilGroupId;
 
                }
            } else
            {
                $data['casesInPilGroup'] = $this->PilModel->getCasesInPilGroup($ecPilGroupId);
                $data['ecPilGroupId']=$ecPilGroupId;

            }
 
            return view('PIL/addToPilGroupResult', $data);

        }
    }


    public function removeCaseFromPilGroup()
    {

 // var_dump($_GET);
  //die;
        $ecPilId = $_POST['id'];
        $ecPilGroupId = $_POST['pilgpid'];
        $userCode = $_SESSION['login']['usercode'];
        $client_ip = getClientIP();
//      ECPIL TABLE SE LOG M S=DAAL RAHE HI VERNACULAR LANGUAGE DATATYPE
        $insertInLog = $this->PilModel->transferPilDataToLogtable($ecPilId);
        if(!empty($insertInLog))
        {
            $data = array(
               'group_file_number'=>0,
                'adm_updated_by'=>$userCode,
                'updated_on'=>date('Y-m-d H:i:s'),
                'updated_by'=>$userCode,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by_ip'=>$client_ip
            );

           // pr($data);
        }

        $builder2 = $this->db->table('ec_pil');
        $builder2->where('id', $ecPilId)->where('group_file_number', $ecPilGroupId);
        $query = $builder2->update($data);

        //$result = $this->ecPil->where('id', $ecPilId)->where('group_file_number', $ecPilGroupId)->update('group_file_number', '');

        if($query>0){
          // $this->addToPilGroupShow("PIL removed from this PIL Group.",$ecPilGroupId);
            return "1";
        }
        else{
            return "0";
          // echo "There is some problem while removing PIL from this PIL Group";
        }


    }
    public function getPilDetailByDiaryNumber(){
        
        $ecPilId=$this->PilModel->getPilId($_POST['diaryNo'],$_POST['diaryYear']);

        if($ecPilId!=null)
            return redirect()->to('PIL/PilController/editPilData/'.$ecPilId['id']);
        else
        session()->setFlashdata('infomsg', 'No data found!');
            return redirect()->to('PIL/PilController/index/');
    }
    public function editPilData($ecPilId=null){
         
        $data['dcmis_user_idd']= session()->get('login')['usercode'];
        $data['common'] =  $this->common ;
        $data['pil_id']=$ecPilId;
        $data['state'] = $this->PilModel->get_state_list();
        $data['pilCategory'] = $this->PilModel->getPilCategory();
        //$data['language'] = $this->PilModel->getLanguage();
        $data['lodgeActionReason'] = $this->PilModel->getActionReason('a');
        $data['writtenActionReason'] = $this->PilModel->getActionReason('b');
        $data['returnActionReason'] = $this->PilModel->getActionReason('c');
        $data['sentActionReason'] = $this->PilModel->getActionReason('d');
        $data['transferActionReason'] = $this->PilModel->getActionReason('e');
        $data['pilGroup'] = $this->PilModel->getPilGroup();
        $data['pilCompleteDetail'] = array();
        if($ecPilId!=null and $ecPilId!=0)
            $data['pilCompleteDetail'] = $this->PilModel->getPilDataById($ecPilId);

        return view('PIL/addEditPilData', $data);
    }

    public function savePilData(){
        //$usercode=1;
        //var_dump($_POST);
        extract($_POST);
        if($pilid==0 || $pilid==null){
            $columnName="(";
            $valueField="(";
            if(!empty($addressedto)){
                $columnName=$columnName."address_to,";
                $valueField=$valueField."'".$this->addSlashinString($addressedto)."',";
            }
            if(!empty($receivedfrom)){
                $columnName=$columnName."received_from,";
                $valueField=$valueField."'".$this->addSlashinString($receivedfrom)."',";
            }
            if(!empty($address)){
                $columnName=$columnName."address,";
                $valueField=$valueField."'".$this->addSlashinString($address)."',";
            }
            if(!empty($emailid)){
                 $columnName=$columnName."email,";
                 $valueField=$valueField."'".$emailid."',";
            }
            if(!empty($mobileno)){
                $columnName=$columnName."mobile,";
                $valueField=$valueField."'".$mobileno."',";
            }
            if(!empty($state) && $state!=0){
                $columnName=$columnName."ref_state_id,";
                $valueField=$valueField.$state.",";
            }
            if(!empty($receivedOn)){
                $columnName=$columnName."received_on,";
                $valueField=$valueField."'".$this->common->date_formatter($receivedOn,'Y-m-d')."',";
            }
            if(!empty($petitionDate)){
                $columnName=$columnName."petition_date,";
                $valueField=$valueField."'".$this->common->date_formatter($petitionDate,'Y-m-d')."',";
            }
            if(!empty($pilCategory) && $pilCategory!=0){
                $columnName=$columnName."ref_pil_category_id,";
                $valueField=$valueField.$pilCategory.",";
            }
            if(!empty($otherGroup)){
                $columnName=$columnName."other_text,";
                $valueField=$valueField."'".$this->addSlashinString($otherGroup)."',";
            }
            if(!empty($pilGroup) && $pilGroup!=0){
                $columnName=$columnName."group_file_number,";
                $valueField=$valueField.$pilGroup.",";
            }
            if(!empty($summaryOfRequest)){
                $columnName=$columnName."request_summary,";
                $valueField=$valueField."'".$this->addSlashinString($summaryOfRequest)."',";
            }
            if(!empty($actionTaken)){
                $columnName=$columnName."action_taken,";
                $valueField=$valueField."'".$actionTaken."',";
            }
            if($actionTaken=='L'){
                if(!empty($lodgementDate)){
                    $columnName=$columnName."lodgment_date,";
                    $valueField=$valueField."'".$this->common->date_formatter($lodgementDate,'Y-m-d')."',";
                }
                if(!empty($lodgedActionReason) && $lodgedActionReason!=0){
                    $columnName=$columnName."ref_action_taken_id,";
                    $valueField=$valueField.$lodgedActionReason.",";
                }
            }
            else if($actionTaken=='W'){
                if(!empty($writtenOn)){
                    $columnName=$columnName."written_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($writtenOn,'Y-m-d')."',";
                }
                if(!empty($writtenActionReason) && $writtenActionReason!=0){
                    $columnName=$columnName."ref_action_taken_id,";
                    $valueField=$valueField.$writtenActionReason.",";
                }
                if(!empty($writtenTo)){
                    $columnName=$columnName."written_to,";
                    $valueField=$valueField."'".$this->addSlashinString($writtenTo)."',";
                }
                if(!empty($writtenFor)){
                    $columnName=$columnName."written_for,";
                    $valueField=$valueField."'".$this->addSlashinString($writtenFor)."',";
                }
            }
            else if($actionTaken=='R'){
                if(!empty($returnDate)){
                    $columnName=$columnName."return_date,";
                    $valueField=$valueField."'".$this->common->date_formatter($returnDate,'Y-m-d')."',";
                }
                if(!empty($returnActionReason) && $returnActionReason!=0){
                    $columnName=$columnName."ref_action_taken_id,";
                    $valueField=$valueField.$returnActionReason.",";
                }
                if(!empty($returnRemark)){
                    $columnName=$columnName."returned_to_sender_remarks,";
                    $valueField=$valueField."'".$this->addSlashinString($returnRemark)."',";
                }
            }
            else if($actionTaken=='S'){
                if(!empty($sentTo)){
                    $columnName=$columnName."sent_to,";
                    $valueField=$valueField."'".$this->addSlashinString($sentTo)."',";
                }
                if(!empty($sentActionReason) && $sentActionReason!=0){
                    $columnName=$columnName."ref_action_taken_id,";
                    $valueField=$valueField.$sentActionReason.",";
                }
                if(!empty($sentOn)){
                    $columnName=$columnName."sent_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($sentOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='T'){
                if(!empty($transferredTo)){
                    $columnName=$columnName."transfered_to,";
                    $valueField=$valueField."'".$this->addSlashinString($transferredTo)."',";
                }
                if(!empty($transferActionReason) && $transferActionReason!=0){
                    $columnName=$columnName."ref_action_taken_id,";
                    $valueField=$valueField.$transferActionReason.",";
                }
                if(!empty($transferredOn)){
                    $columnName=$columnName."transfered_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($transferredOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='I'){
                if(!empty($convertedDiaryNumber)){
                    $columnName=$columnName."ec_case_id,";
                    $valueField=$valueField.$convertedDiaryNumber.$convertedDiaryYear.",";
                }
            }
            else if($actionTaken=='O'){
                if(!empty($otherRemedyRemark)){
                    $columnName=$columnName."other_text,";
                    $valueField=$valueField."'".$this->addSlashinString($otherRemedyRemark)."',";
                }
                if(!empty($otherActionTakenOn)){
                    $columnName=$columnName."other_action_taken_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($otherActionTakenOn,'Y-m-d')."',";
                }
            }
            if(isset($reportReceived)){
                    $columnName=$columnName."report_received,";
                    $valueField=$valueField."'".$reportReceived."',";
            }
            if(!empty($reportDate)){
                $columnName=$columnName."report_received_date,";
                $valueField=$valueField."'".$this->common->date_formatter($reportDate,'Y-m-d')."',";
            }
            if(isset($destroyOrKeepIn)){
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='Y'){
                    $columnName=$columnName."destroy_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";

                    $columnName=$columnName."is_deleted,";
                    $valueField=$valueField."'t',";
                }
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='N'){
                    $columnName=$columnName."in_record_on,";
                    $valueField=$valueField."'".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";
                }
            }
            if(!empty($destroyOrKeepInRemark)){
                $columnName=$columnName."remarks,";
                $valueField=$valueField."'".$this->addSlashinString($destroyOrKeepInRemark)."',";
            }

            $lastDiaryNumber=$this->PilModel->getLastDiaryNumber(date("Y"));
            if($lastDiaryNumber==null){
                $lastDiaryNumber=1;
            }
            else{
                $lastDiaryNumber=$lastDiaryNumber+1;
            }
            $columnName=$columnName."diary_number,";
            $valueField=$valueField."'".$lastDiaryNumber."',";
            $columnName=$columnName."diary_year,";
            $valueField=$valueField."'".date("Y")."',";

            $columnName=$columnName."updated_on,";
            $valueField=$valueField."now(),";

            $columnName=$columnName."adm_updated_by,";
            $valueField=$valueField."".$usercode.",";

            $columnName=rtrim($columnName,',').")";
            $valueField=rtrim($valueField,',').")";

            $query="insert into ec_pil ".$columnName." values ".$valueField;

            $rowsaffected=$this->PilModel->savePilData($query,$pilid);
            if($rowsaffected!=null && $rowsaffected!=""){
                echo $lastDiaryNumber.'/SCI/PIL/'.date("Y");
                //echo "Success";
                //$lastDiaryNumber.'/SCI/PIL(E)'.date("Y");
                //Commented on 21-01-2021 in pursuance to note dated 19-01-2021
                //$this->send_SMS($mobileno,'Your Grievance/Communication has been successfully registered as Diary No.'.$lastDiaryNumber.'/SCI/PIL(E)'.date("Y").' For status, kindly logon to http://sci.gov.in and go to Grievance Management option in Case Information Tab.');
                if(!empty($mobileno) && strlen($mobileno)==10){
                    $this->send_SMS($mobileno,'Your Grievance/Communication has been given Inward No.'.$lastDiaryNumber.'/SCI/PIL/'.date("Y").' For status, kindly logon to http://sci.gov.in and go to Grievance Management option in Case Information Tab. - Supreme Court of India',SCISMS_GRIEVANCE_REGISTRATION);
                }
            }
            else{
                echo "Error";
            }
        }
        else if($pilid>0){

            /*$columnName="(";
            $valueField="(";*/
            $updateQuery="";
            if(!empty($addressedto)){
                $updateQuery=$updateQuery."address_to='".$this->addSlashinString($addressedto)."',";
            }
            if(!empty($receivedfrom)){
                $updateQuery=$updateQuery."received_from='".$this->addSlashinString($receivedfrom)."',";
            }
            if(!empty($address)){
                $updateQuery=$updateQuery."address='".$this->addSlashinString($address)."',";
            }
            if(!empty($emailid)){
                $updateQuery=$updateQuery."email='".$emailid."',";
            }
            if(!empty($mobileno)){
                $updateQuery=$updateQuery."mobile='".$mobileno."',";
            }
            if(!empty($state) && $state!=0){
                $updateQuery=$updateQuery."ref_state_id=".$state.",";
            }
            if(!empty($receivedOn)){
                $updateQuery=$updateQuery."received_on='".$this->common->date_formatter($receivedOn,'Y-m-d')."',";
            }
            if(!empty($petitionDate)){
                $updateQuery=$updateQuery."petition_date='".$this->common->date_formatter($petitionDate,'Y-m-d')."',";
            }
            if(!empty($pilCategory) && $pilCategory!=0){
                $updateQuery=$updateQuery."ref_pil_category_id=".$pilCategory.",";
            }
            if(!empty($otherGroup)){
                $updateQuery=$updateQuery."other_text='".$this->addSlashinString($otherGroup)."',";
            }
            if(!empty($pilGroup) && $pilGroup!=0){
                $updateQuery=$updateQuery."group_file_number=".$pilGroup.",";
            }
            else{
                $updateQuery=$updateQuery."group_file_number=null,";
            }
            if(!empty($summaryOfRequest)){
                $updateQuery=$updateQuery."request_summary='".$this->addSlashinString($summaryOfRequest)."',";
            }

            if(!empty($actionTaken)){
                $updateQuery=$updateQuery."action_taken='".$actionTaken."',";
            }
            else{
                $updateQuery=$updateQuery."action_taken=null,";
            }
            if($actionTaken=='L'){
                if(!empty($lodgementDate)){
                    $updateQuery=$updateQuery."lodgment_date='".$this->common->date_formatter($lodgementDate,'Y-m-d')."',";
                }
                if(!empty($lodgedActionReason) && $lodgedActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$lodgedActionReason.",";
                }
            }
            else if($actionTaken=='W'){
                if(!empty($writtenOn)){
                    $updateQuery=$updateQuery."written_on='".$this->common->date_formatter($writtenOn,'Y-m-d')."',";
                }
                if(!empty($writtenActionReason) && $writtenActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$writtenActionReason.",";
                }
                if(!empty($writtenTo)){
                    $updateQuery=$updateQuery."written_to='".$this->addSlashinString($writtenTo)."',";
                }
                if(!empty($writtenFor)){
                    $updateQuery=$updateQuery."written_for='".$this->addSlashinString($writtenFor)."',";
                }
            }
            else if($actionTaken=='R'){
                if(!empty($returnDate)){
                    $updateQuery=$updateQuery."return_date='".$this->common->date_formatter($returnDate,'Y-m-d')."',";
                }
                if(!empty($returnActionReason) && $returnActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$returnActionReason.",";
                }
                if(!empty($returnRemark)){
                    $updateQuery=$updateQuery."returned_to_sender_remarks='".$this->addSlashinString($returnRemark)."',";
                }
            }
            else if($actionTaken=='S'){
                if(!empty($sentTo)){
                    $updateQuery=$updateQuery."sent_to='".$this->addSlashinString($sentTo)."',";
                }
                if(!empty($sentActionReason) && $sentActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$sentActionReason.",";
                }
                if(!empty($sentOn)){
                    $updateQuery=$updateQuery."sent_on='".$this->common->date_formatter($sentOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='T'){
                if(!empty($transferredTo)){
                    $updateQuery=$updateQuery."transfered_to='".$this->addSlashinString($transferredTo)."',";
                }
                if(!empty($transferActionReason) && $transferActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$this->addSlashinString($transferActionReason).",";
                }
                if(!empty($transferredOn)){
                    $updateQuery=$updateQuery."transfered_on='".$this->common->date_formatter($transferredOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='I'){
                if(!empty($convertedDiaryNumber)){
                    $updateQuery=$updateQuery."ec_case_id=".$convertedDiaryNumber.$convertedDiaryYear.",";
                }
            }
            else if($actionTaken=='O'){
                if(!empty($otherRemedyRemark)){
                    $updateQuery=$updateQuery."other_text='".$this->addSlashinString($otherRemedyRemark)."',";
                }
                if(!empty($otherActionTakenOn)){
                    $updateQuery=$updateQuery."other_action_taken_on='".$this->common->date_formatter($otherActionTakenOn,'Y-m-d')."',";
                }
            }
            if(isset($reportReceived)){
                $updateQuery=$updateQuery."report_received='".$reportReceived."',";
            }
            if(!empty($reportDate)){
                $updateQuery=$updateQuery."report_received_date='".$this->common->date_formatter($reportDate,'Y-m-d')."',";
            }
            if(isset($destroyOrKeepIn)){
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='Y'){
                    $updateQuery=$updateQuery."destroy_on='".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";
                    $updateQuery=$updateQuery."is_deleted='t',";
                }
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='N'){
                    $updateQuery=$updateQuery."in_record_on='".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";
                }
            }
            if(!empty($destroyOrKeepInRemark)){
                $updateQuery=$updateQuery."remarks='".$this->addSlashinString($destroyOrKeepInRemark)."',";
            }
            $updateQuery=$updateQuery."updated_on=now(),";
            $updateQuery=$updateQuery."adm_updated_by=".$usercode.",";
            $updateQuery=rtrim($updateQuery,',');

            $query="update ec_pil set ".$updateQuery." where id=".$pilid;
            $this->PilModel->transferPilDataToLogtable($pilid);
            $rowsaffected=$this->PilModel->savePilData($query,$pilid);
            if($rowsaffected!=null && $rowsaffected!=""){
                echo "Success";
                $msg=$this->getSMSText($pilid);
                $msgs=explode('#',$msg);
                //echo $actionTaken;
                //echo "Action:".$actionTaken." sizeof(msg)".sizeof($msgs);


                if($actionTaken!='' && $actionTaken!='0' && sizeof($msgs)>0){
                    $this->send_SMS($msgs[1],$msgs[0],$msgs[2]);
                }
            }
            else{
                echo "Error";
            }

        }
    }
/*PIL ENTRY >>> PIL GROUP*/


    public function showPilGroup(){
        $data['pilGroup'] = $this->PilModel->getPilGroup();
        return view('PIL/showPilGroupData', $data);
    }

    public function editPilGroupData($ecPilGroupId=null){
//        echo "ddd".$ecPilGroupId;
//        die;
        $data['pil_group_id']=$ecPilGroupId;
        if($ecPilGroupId!=null and $ecPilGroupId!=0){
//            echo "idddd".$ecPilGroupId;
//            die;
            $data['lodgeActionReason'] = $this->PilModel->getActionReason('a');
            $data['writtenActionReason'] = $this->PilModel->getActionReason('b');
            $data['returnActionReason'] = $this->PilModel->getActionReason('c');
            $data['sentActionReason'] = $this->PilModel->getActionReason('d');
            $data['transferActionReason'] = $this->PilModel->getActionReason('e');
            $data['pilGroupDetail'] = $this->PilModel->getPilGroupDataById($ecPilGroupId);
            $data['casesInPilGroup'] = $this->PilModel->getCasesInPilGroup($ecPilGroupId);
        }
//        echo "<pre>";
//        print_r($data['casesInPilGroup']);
//        die;
        return view('PIL/addEditPilGroupData', $data);
    }

    public function savePilGroupData()
    {
//        echo "hello";
//        var_dump($_POST);
//        die;
        $pilGroupId = $_POST['pid'];
        $groupFileNumber = $_POST['gpid'];
        $usercode = $_POST['ucode'];
        $result=$this->PilModel->savePilGroupData($pilGroupId,$this->addSlashinString($groupFileNumber),$usercode);
        echo $result;
    }

    private function addSlashinString($str){
        return addslashes($str);

    }

    public function groupUpdate()
    {
//        echo "TEWRTEWR";die;
        if(!empty($_POST))
        {
//            echo "<pre>";
//            print_r($_POST);die;
            $pilGroupId = $_POST['pilGroupId'];
            $groupFileNumber = $_POST['groupFileNumber'];
            $actionTaken = $_POST['actionTaken'];
            $lodgementDate = $_POST['lodgementDate'];
            $lodgedActionReason = $_POST['lodgedActionReason'];
            $writtenOn = $_POST['writtenOn'];
            $writtenActionReason = $_POST['writtenActionReason'];
            $writtenTo = $_POST['writtenTo'];
            $writtenFor = $_POST['writtenFor'];
            $returnDate = $_POST['returnDate'];
            $returnActionReason = $_POST['returnActionReason'];
            $returnRemark = $_POST['returnRemark'];
            $sentTo = $_POST['sentTo'];
            $sentActionReason = $_POST['sentActionReason'];
            $sentOn = $_POST['sentOn'];
            $transferredTo = $_POST['transferredTo'];
            $transferActionReason = $_POST['transferActionReason'];
            $transferredOn = $_POST['transferredOn'];
            $convertedDiaryNumber = $_POST['convertedDiaryNumber'];
            $convertedDiaryYear = $_POST['convertedDiaryYear'];
            $otherRemedyRemark = $_POST['otherRemedyRemark'];
            $reportDate = $_POST['reportDate'];
            $destroyOrKeepIn = $_POST['destroyOrKeepIn'] ?? '';
            $destroyOrKeepInDate = $_POST['destroyOrKeepInDate'];
            $destroyOrKeepInRemark = $_POST['destroyOrKeepInRemark'];
            $pils = $_POST['pils'];

            $result=0;
            $ecPils=implode(',', $pils);
            $updateQuery="";

            if(!empty($actionTaken)){
                $updateQuery=$updateQuery."action_taken='".$actionTaken."',";
            }
            else{
                $updateQuery=$updateQuery."action_taken=null,";
            }
            if($actionTaken=='L'){
                if(!empty($lodgementDate)){
                    $updateQuery=$updateQuery."lodgment_date='".$this->common->date_formatter($lodgementDate,'Y-m-d')."',";
                }
                if(!empty($lodgedActionReason) && $lodgedActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$lodgedActionReason.",";
                }
            } else if($actionTaken=='W'){
                if(!empty($writtenOn)){
                    $updateQuery=$updateQuery."written_on='".$this->common->date_formatter($writtenOn,'Y-m-d')."',";
                }
                if(!empty($writtenActionReason) && $writtenActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$writtenActionReason.",";
                }
                if(!empty($writtenTo)){
                    $updateQuery=$updateQuery."written_to='".$this->addSlashinString($writtenTo)."',";
                }
                if(!empty($writtenFor)){
                    $updateQuery=$updateQuery."written_for='".$this->addSlashinString($writtenFor)."',";
                }
            }else if($actionTaken=='R'){
                if(!empty($returnDate)){
                    $updateQuery=$updateQuery."return_date='".$this->common->date_formatter($returnDate,'Y-m-d')."',";
                }
                if(!empty($returnActionReason) && $returnActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$returnActionReason.",";
                }
                if(!empty($returnRemark)){
                    $updateQuery=$updateQuery."returned_to_sender_remarks='".$this->addSlashinString($returnRemark)."',";
                }
            }
            else if($actionTaken=='S'){
                if(!empty($sentTo)){
                    $updateQuery=$updateQuery."sent_to='".$this->addSlashinString($sentTo)."',";
                }
                if(!empty($sentActionReason) && $sentActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$sentActionReason.",";
                }
                if(!empty($sentOn)){
                    $updateQuery=$updateQuery."sent_on='".$this->common->date_formatter($sentOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='T'){
                if(!empty($transferredTo)){
                    $updateQuery=$updateQuery."transfered_to='".$this->addSlashinString($transferredTo)."',";
                }
                if(!empty($transferActionReason) && $transferActionReason!=0){
                    $updateQuery=$updateQuery."ref_action_taken_id=".$this->addSlashinString($transferActionReason).",";
                }
                if(!empty($transferredOn)){
                    $updateQuery=$updateQuery."transfered_on='".$this->common->date_formatter($transferredOn,'Y-m-d')."',";
                }
            }
            else if($actionTaken=='I'){
                if(!empty($convertedDiaryNumber)){
                    $updateQuery=$updateQuery."ec_case_id=".$convertedDiaryNumber.$convertedDiaryYear.",";
                }
            }
            else if($actionTaken=='O'){
                if(!empty($otherRemedyRemark)){
                    $updateQuery=$updateQuery."other_text='".$this->addSlashinString($otherRemedyRemark)."',";
                }
            }
            if(isset($reportReceived)){
                $updateQuery=$updateQuery."report_received='".$reportReceived."',";
            }
            if(!empty($reportDate)){
                $updateQuery=$updateQuery."report_received_date='".$this->common->date_formatter($reportDate,'Y-m-d')."',";
            }
            if(isset($destroyOrKeepIn)){
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='Y'){
                    $updateQuery=$updateQuery."destroy_on='".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";
                    $updateQuery=$updateQuery."is_deleted='t',";
                }
                if(!empty($destroyOrKeepInDate) && $destroyOrKeepIn=='N'){
                    $updateQuery=$updateQuery."in_record_on='".$this->common->date_formatter($destroyOrKeepInDate,'Y-m-d')."',";
                }
            }
            if(!empty($destroyOrKeepInRemark)){
                $updateQuery=$updateQuery."remarks='".$this->addSlashinString($destroyOrKeepInRemark)."',";
            }
            $usercode = $_SESSION['login']['usercode'];
            $updateQuery=$updateQuery."updated_on=NOW(),";
            $updateQuery=$updateQuery."adm_updated_by=".$usercode.",";
            $updateQuery=rtrim($updateQuery,',');

//            echo $updateQuery;die;

            $query="update ec_pil set ".$updateQuery." where group_file_number=$pilGroupId and is_deleted='f' and id in ($ecPils)";

            $this->PilModel->transferPilDataToLogtableUsingGroup($pilGroupId,$ecPils);
            $result=$this->PilModel->performGroupUpdate($query);
//            TO BE DELETED LATER HARDCODED VALUE
            $result=1;
            if($result){
//                print_r($pils);die;
                foreach($pils as $pilIdForSMS){
                    $msg=$this->getSMSText($pilIdForSMS);
//                    echo "<pre>";print_r($msg);die;
                    $msgs=explode('#',$msg);
//                    echo "<pre>";print_r($msgs);die;
                    if($actionTaken!='' && $actionTaken!='0' && sizeof($msgs)>0){
                        send_sms($msgs[1],$msgs[0],'ec_pil',$msgs[2]);
                    }
                }
            }
            echo $result;
            exit();

        }
    }

    private function getSMSText($ecPilId){
        $result=$this->PilModel->getActionTakenInformation($ecPilId);
//        echo "<pre>";
//        print_r($result);die;
        $msg="";$diaryNumber="";
        $diaryNumber=$result[0]['diary_number'].'/'.$result[0]['diary_year'];
        $pilSubActionCode=$result[0]['pil_sub_action_code'];
        $mobileNo=$result[0]['mobile'];
//        ALL TEMPLATE ID VALUE IS USED HERE NOT CONSTANT NAMES PLEASE REFER OLD CODE FILE PilController/groupUpdate AND CONSTANTS DEFINITION IN includes/sms_template
//        $templateId='SCISMS_GENERIC_TEMPLATE';
        $templateId='1107161243622980738';
        //            TO BE DELETED LATER HARDCODED VALUE  395,396 above template id text ask kal
//        $result[0]['action_taken']='R';
//       echo  $pilSubActionCode.">>";die;
//        echo $mobileNo.">>";

        if($result[0]['action_taken']=='L'){
            $lodgementDate=$result[0]['lodgment_date'];
            if ($lodgementDate!=null){
                $lodgementDate=date('d-m-Y',strtotime($lodgementDate));
            }

            switch ($pilSubActionCode){
                case 'A1':{
                    $msg = "Your Grievance/Communication registered against Inward No.".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." as  contents of complaint are not covered under PIL guidelines.";
//                    $templateId='SCISMS_GRIEVEANCE_NOT_COVERED_PIL';
                    $templateId='1107161243033590430';
                    break;
                }
                case 'A2':{
                    $msg = "Your Grievance/Communication registered against Inward No.".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." being not addressed to Supreme Court of India.";
//                    $templateId='SCISMS_GRIEVANCE_IMPROPER_ADDRESSED';
                    $templateId='1107161243038586331';
                    break;
                }
                case 'A3':{
                    $msg = "Your Grievance/Communication registered against Inward No.".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." being unsigned.";
//                    $templateId='SCISMS_GRIEVANCE_UNSIGNED';
                    $templateId='1107161243043518094';
                    break;
                }
                case 'A4':{
                    $msg = "Your Grievance/Communication registered against Inward No.".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." being anonymous.";
//                    $templateId='SCISMS_GRIEVANCE_ANONYMOUS';
                    $templateId='1107161243048102363';
                    break;
                }
                case 'A5':{
                    $msg = "Your Grievance/Communication registered against Inward No.".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." since address is incomplete / pseudonymous.";
//                    $templateId='SCISMS_GRIEVANCE_INCOMPLETE_ADDRESS';
                    $templateId='1107161243054908462';
                    break;
                }
                case 'A6':{
                    $msg = "Your Grievance/Communication registered against Inward No. ".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." since email is not digitally signed.";
//                    $templateId='SCISMS_GRIEVANCE_EMAIL_UNSIGNED';
                    $templateId='1107161243060817022';
                    break;
                }
                case 'A7':{
                    $msg = "Your Grievance/Communication registered against Inward No. ".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." being incomprehensible.";
//                    $templateId='SCISMS_GRIEVANCE_INCOMPREHENSIBLE';
                    $templateId='1107161243065540526';
                    break;
                }
                case 'A8':{
                    $msg = "Your Grievance/Communication registered against Inward No. ".$diaryNumber.", has been lodged/filed  on ".$lodgementDate." Repititive in nature.No Action Required.";
//                    $templateId='SCISMS_GRIEVANCE_REPETITIVE';
                    $templateId='1107161243074421372';
                    break;
                }
            }

        }
        else if($result[0]['action_taken']=='W')
        {
            switch ($pilSubActionCode) {
                case 'B1':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to approach concerned Court/Authority in accordance with law.";
//                    $templateId=SCISMS_GRIEVANCE_APPROACH_COURT;
                    $templateId='1107161243082205857';
                    break;
                }
                case 'B2':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to  approach Supreme Court Legal Services Committee for legal aid.";
//                    $templateId=SCISMS_GRIEVANCE_APPROACH_SCLSC;
                    $templateId='1107161243088367408';
                    break;
                }
                case 'B3':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to  approach Supreme Court Legal Services Committee for legal aid.";
//                    $templateId=SCISMS_GRIEVANCE_APPROACH_SCLSC;
                    $templateId='1107161243088367408';
                    break;
                }
                case 'B4':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been informed that your letter petition stands forwarded to Supreme Court Legal Services Committee for necessary action.";
//                    $templateId=SCISMS_GRIEVANCE_LETTER_FORWARDED_SCLSC;
                    $templateId='1107161243094245809';
                    break;
                }
                case 'B5':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to file  requisite transfer petition in accordance with law.";
//                    $templateId=SCISMS_GRIEVANCE_TRANSFER_PETITION;
                    $templateId='1107161243101406355';
                    break;
                }
                case 'B6':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been informed that earlier letter petition with annexures has been returned as requested.";
//                    $templateId=SCISMS_GRIEVANCE_TP_RETURN_REQUEST;
                    $templateId='1107161243108554880';
                    break;
                }
                case 'B7':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to take legal Recourse in accordance with Law.";
//                    $templateId=SCISMS_GRIEVANCE_LEGAL_RECOURSE;
                    $templateId='1107161243115348334';
                    break;
                }
                case 'B8':
                {
                    $msg ="Your Grievance/Communication registered against Inward No. " . $diaryNumber . ", you have been infomed that  fate of earlier letter-petition has been conveyed to the petitioner.";
//                    $templateId=SCISMS_GRIEVANCE_FATE_CONVEYED;
                    $templateId="1107161243122261156";
                    break;
                }
                case 'B9':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been asked to Furnish in writing  complete facts of the matter.";
//                    $templateId=SCISMS_GRIEVANCE_COMPLETE_FACTS;
                    $templateId='1107161243133394568';
                    break;

                }
                case 'B10':
                {
                    $msg ="Your Grievance/Communication registered against Inward No. " . $diaryNumber . ", Visit www.sci.nic.in  for necessary information.";
//                    $templateId=SCISMS_GRIEVANCE_VISIT_WEBSITE;
                    $templateId="1107161243142625534";
                    break;
                }
            }
        }
        else if($result[0]['action_taken']=='R')
        {
            switch ($pilSubActionCode) {
                case 'C1':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", has been returned to you  view of Article 235 of the Constitution of India vide this registry letter.";
//                    $templateId=SCISMS_GRIEVANCE_ARTICLE_235;
                    $templateId='1107161243148975839';
                    break;
                }
                case 'C2':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", you have been informed that no action was taken , in view of  Article 235 of the Constitution of India.";
//                    $templateId=SCISMS_GRIEVANCE_NO_ACTION;
                    $templateId='1107161243154208023';
                    break;
                }
                case 'C3':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", has been returned to  you as  matter is sub-judice  before concerned Court vide this registry letter.";
//                    $templateId=SCISMS_GRIEVANCE_SUBJUDICE_MATTER;
                    $templateId='1107161243160186430';
                    break;
                }
                case 'D1':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", a copy of your complaint/letter has been forwarded to  concerned authority for taking necessary action.";
//                    $templateId=SCISMS_GRIEVANCE_COPY_FOWARDED;
                    $templateId='1107161243166287442';
                    break;
                }
                case 'D2':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", a copy of your complaint/letter has been forwarded to  concerned authority  for taking necessary action and submit report to this Court.";
//                    $templateId=SCISMS_GRIEVANCE_FORWARD_REPORT;
                    $templateId='1107161243173106684';
//                    echo $msg;die;
                    break;
                }
            }
        }
//        else if($result[0]['action_taken']=='R')   THIS IS SAME CONDITION IS WRITTEN ABOVE SO THESE TWO CASE IS COMBINED IN ABOVE CONDITION
//        {
//            switch ($pilSubActionCode) {
//                case 'D1':
//                {
//                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", a copy of your complaint/letter has been forwarded to  concerned authority for taking necessary action.";
//                    $templateId=SCISMS_GRIEVANCE_COPY_FOWARDED;
//                    break;
//                }
//                case 'D2':
//                {
//                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", a copy of your complaint/letter has been forwarded to  concerned authority  for taking necessary action and submit report to this Court.";
//                    $templateId=SCISMS_GRIEVANCE_FORWARD_REPORT;
////                    echo $msg;die;
//                    break;
//                }
//            }
//        }
        else if($result[0]['action_taken']=='T')
        {
            switch ($pilSubActionCode) {
                case 'E1':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", has been forwarded to   concerned  section/authority of Supreme Court.";
//                    $templateId=SCISMS_GRIEVANCE_FORWARDED_SECTION;
                    $templateId='1107161243178467678';
                    break;
                }
                case 'E2':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", has been transferred to  concerned  Bar Council  for action deemed fit.";
//                    $templateId=SCISMS_GRIEVANCE_TRANSFER_BAR_COUNCIL;
                    $templateId='1107161243185446906';
                    break;
                }
                case 'E3':
                {
                    $msg ="Your Grievance/Communication registered against Inward No." . $diaryNumber . ", has been  registered as writ petition as ";//Writ petition number to be print
//                    $templateId=SCISMS_GRIEVANCE_WRIT_PETITION;
                    $templateId='1107161243191296798';
                    break;
                }
            }

        }
        $msg=$msg." - Supreme Court of India";
        if($msg!="" && $mobileNo!="" && $mobileNo!=null && $mobileNo!="0")
        {
//            echo "RRR";die;
            $msg=$msg.'#'.$mobileNo.'#'.$templateId;
        }
//        echo $msg;die;

        return $msg;
    }



//  ************************************************************* BELOW SECTION IS FOR PIL REPORT SUBMENUS **************************************************************
    //BELOW FUNCTION IS NOT IN USED BECAUSE SUBMUDULE IS NOT IN USED GENERATE BRIEF HISTORY AND LETTERS
    public function reportsSection()
    {
        return view('PIL/pilReport');
    }

    public function reportPilGroup($msg="",$ecPilGroupId=0)
    {
        $data['msg']=$msg;
        $data['pilGroup'] = $this->PilModel->getPilGroup();
//        echo "<pre>";
//        print_r($data['pilGroup']);
        $data['ecPilGroupId']=$ecPilGroupId;
        if($ecPilGroupId!=0){
            $casesInPilGroup = $this->PilModel->getCasesInPilGroup($ecPilGroupId);
//            print_r($casesInPilGroup); die;
            $data['casesInPilGroup'] = $casesInPilGroup;
           return $data;

        }
//       var_dump($data['casesInPilGroup']);
//        echo "<pre>";
//        print_r($data);
//        die;
        return view('PIL/reportPilGroupView', $data);

    }

    public function addToPilGroupReport()
    {
//        var_dump($_POST);
//        die;
        $record = $this->reportPilGroup("",$_REQUEST['dt']);
//        echo "<pre>";
//        print_r($record);
//        die;
        echo json_encode($record);
    }


    public function downloadFormatReport()
    {
        $usercode = $_REQUEST['id'];
        $ecPilGroupId = $_REQUEST['eid'];
        $reportType = $_REQUEST['uid'];

 

        $userdetail = getUserNameAndDesignation($usercode);
 
        $pilData=$this->PilModel->getCasesInPilGroup_asc($ecPilGroupId);
 
        $this->pdf->AddPage();
        $this->pdf->setleftmargin(40);
        $this->pdf->setrightmargin(20);
        if($reportType==1){
 
            $this->pdf->ln(5);
            $this->pdf->SetFont('times','BU',12);
            $this->pdf->Cell(0,3,'SUPREME COURT OF INDIA',0,1,'C');
            $this->pdf->Cell(0,8,'PIL(ENGLISH) CELL',0,1,'C');
            $this->pdf->SetFont('times','',11);
            $this->pdf->Cell(0,8,'Dated: '. date('d-m-Y'),0,1,'R');
            $this->pdf->Write(5,'              Letter-petition being not addressed to SCI ');

            $this->pdf->SetFont('times','B',11);
            $this->pdf->Write(5,'(only copy of letter-petition endorsed to SCI)');
            $this->pdf->SetFont('times','',11);
            $this->pdf->Write(5,' and not covered under PIL Guidelines.');
            $this->pdf->ln(5);
            $this->pdf->SetFont('times','',11);
            $this->pdf->Write(5,'              Hence, if approved, the same may be filed. ');

            $this->pdf->ln(10);
            $this->pdf->SetFont('times','',10);
            foreach($pilData as $index=>$data){
                $this->pdf->Cell(20,8,'',0,0,'L');
                $this->pdf->Cell(10,8,($index+1).'.',0,0,'L');
                $this->pdf->Cell(50,8,$data['pil_diary_number'],0,0,'L');
                $this->pdf->Cell(100,8,$data['received_from'],0,1,'L');
            }

            $this->pdf->SetFont('times','',11);
            $this->pdf->ln(15);

            $this->pdf->SetFont('times','B',9);
            $this->pdf->Cell(80,8,$userdetail['name'],0,1,'L');
            $this->pdf->Cell(80,0,$userdetail['type_name'],0,1,'L');
            $this->pdf->ln(15);
            $this->pdf->Cell(80,0,'BRANCH OFFICER',0,1,'L');
            
            $this->pdf->ln(15);
            $this->pdf->Cell(80,0,'DEPUTY REGISTRAR',0,1,'L');
         
            $this->pdf->ln(15);
            
            $this->pdf->Cell(80,0,'Ld.REGISTRAR(PIL E)',0,1,'L');
            ob_end_clean();
            $this->pdf->Output();

            exit;

        }
 


    }


//END OF GENERATE BRIEF HISTORY FUNCTIONS


// FOR QUERRY SUB MENUS BELOW IS THE CODE ----- START

    public function queryPilData()
    {
        $data=[];
        if(!empty($_POST))
        { 
            $columnName = $_POST['columnName'];
            $qryText = $_POST['qryText'];

            if(!empty($columnName) && !empty($qryText))
            {
                $result_array = $this->PilModel->getQueryPilData($columnName, $qryText);

                if(!empty($result_array)) {
                    $data['pil_result'] = $result_array;
                }
                $data['column_name'] = $columnName;
                $data['text'] = $qryText;
 
            }
            echo view('PIL/queryPilReportData',$data);
            die;
        }
        return view('PIL/queryPilReport');
    }


    public function rptPilCompleteData($ecPilId=null){
 
        $data['pil_id']=$ecPilId;
        $data['state'] = $this->PilModel->get_state_list();
        $data['pilCategory'] = $this->PilModel->getPilCategory();
        $data['pilGroup'] = $this->PilModel->getPilGroup();
        if($ecPilId!=null and $ecPilId!=0)
        $recordArr = $this->PilModel->getPilDataById($ecPilId);

        if(!empty($recordArr))
        {
            foreach ($recordArr as $record)
            {
                $data['pilCompleteDetail'] = $record;
            }
        }
//                echo "<pre>";
//        print_r($record);die;

        $data['date_formatter_received_on'] = $this->common->date_formatter($record['received_on'],'d-m-Y');
        $data['date_formatter_petition_date'] =  $this->common->date_formatter($record['petition_date'],'d-m-Y');
        $data['date_formatter_written_on'] = $this->common->date_formatter($record['written_on'],'d-m-Y');
        $data['date_formatter_return_date'] = $this->common->date_formatter($record['return_date'],'d-m-Y');
        $data['date_formatter_sent_on'] = $this->common->date_formatter($record['sent_on'],'d-m-Y');
        $data['date_formatter_transfered_on'] = $this->common->date_formatter($record['transfered_on'],'d-m-Y');
        $data['date_formatter_other_action_taken_on'] = $this->common->date_formatter($record['other_action_taken_on'],'d-m-Y');
        $data['date_formatter_report_received_date'] = $this->common->date_formatter($record['report_received_date'],'d-m-Y');
        $data['date_formatter_destroy_on'] = $this->common->date_formatter($record['destroy_on'],'d-m-Y');
        $data['date_formatter_in_record_on'] = $this->common->date_formatter($record['in_record_on'],'d-m-Y');

        if($record['destroy_on']!=null && $record['destroy_on']!="" && $record['is_deleted']=='t'){
            $data['destroyOrKeepIn']='Y';
            $data['destroyOrKeepInDate']=$this->common->date_formatter($record['destroy_on'],'d-m-Y');
        }else if($record['in_record_on']!=null && $record['in_record_on']!=""){
            $data['destroyOrKeepIn']='N';
            $data['destroyOrKeepInDate']=$this->common->date_formatter($record['in_record_on'],'d-m-Y');

        }

        if($record['action_taken'] != '') {

            switch (trim($record['action_taken'])) {
                case "L":
                {
                   if(!empty($record['lodgment_date']))
                   {
                       $data['actionTakenText'] = "No Action Required on " . date('d-m-Y', strtotime($record['lodgment_date']));
                   }else{
                       $data['actionTakenText'] = " ";
                   }
                    break;
                }
                case "W":
                {
                    if(!empty($record['written_to']))
                    {
                        $data['actionTakenText'] = "Written Letter to " . $record['written_to'] . " on " . date('d-m-Y', strtotime($record['written_on']));
                    }else{
                        $data['actionTakenText'] = " ";
                    }
                    break;
                }
                case "R":
                {
                    if(!empty($record['return_date']))
                    {
                        $data['actionTakenText'] = "Letter Returned to Sender on " . date('d-m-Y', strtotime($record['return_date']));
                    }else{
                        $data['actionTakenText'] = " ";
                    }

                    break;
                }
                case "S":
                {
                   $data['actionTakenText'] = "Letter Sent To " . $record['sent_to'] . " on " . date('d-m-Y', strtotime($record['sent_on']));
                    break;
                }
                case "T":
                {
                    $data['actionTakenText']= "Letter Transferred To " . $record['transfered_to'] . " on " . date('d-m-Y', strtotime($record['transfered_on']));
                    break;
                }
                case "I":
                {

                    $data['actionTakenText'] = "Letter Converted To Writ";
                    break;
                }
                case "O":
                {
                   $data['actionTakenText'] = "Other Remedy <br/>" .$record['other_text'] . " on dated " .$data['date_formatter_other_action_taken_on'];
                    break;
                }
                default:
                {
                    $data['actionTakenText'] = "UNDER PROCESS";
                    break;
                }
            }
        }
        else{
            $data['actionTakenText'] =" ";
        }
        
        return view('PIL/rptCompletePilData', $data);

    }




// PIL REPORTS >> REPORTS SUB MENU

    public function getPilReport(){
//        echo "<pre>";
//        print_r($_POST);  die;
        
        if(!empty($_POST)) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $reportType = $_POST['reportType'];
            
        }
       
        return view('PIL/pilReport');

    }

   /* public function getpilReportResult()
{
    $request = service('request');

    // 🔹 Get Pagination Parameters
    $page = (int) ($request->getGet('page') ?? 1); // Default to 1
    $perPage = 10; // Number of records per page
    $offset = ($page - 1) * $perPage;

    // 🔹 Get Filter Parameters
    $from_date = date('Y-m-d', strtotime($request->getGet('from_date')));
    $to_date = date('Y-m-d', strtotime($request->getGet('to_date')));
    $reportType = $request->getGet('reportType');

    // 🔹 Load Model
    $pilModel = new PilModel();

    // 🔹 Fetch Data & Total Count in One Query (Efficient!)
    $pilData = $pilModel->getPilReportData($from_date, $to_date, $reportType, $perPage, $offset);
    
    // 🔹 Load Pagination Library
    $pager = \Config\Services::pager();
    
    $pager->setPath('/PIL/PilController/getpilReportResult');

    $data = [
        'pil_result'   => $pilData['data'],   // Paginated results
        'pager'        => $pager->makeLinks($page, $perPage, $pilData['total']),  // Generate pagination links
        'total'        => $pilData['total'],  // Total records
        'perPage'      => $perPage,           // Per page count
        'currentPage'  => $page,              // Current page
        'first_date'   => $from_date,         // Start date
        'to_date'      => $to_date,           // End date
        'reportType'   => $reportType         // Report type
    ];

    return view('PIL/pilReportResult', $data);
} */


    public function getpilReportResult()
    {
        $from_date=$to_date=$reportType='';
        $from_date = date('Y-m-d', strtotime($this->request->getGet('from_date')));
        $to_date = date('Y-m-d', strtotime($this->request->getGet('to_date')));
        $reportType = $this->request->getGet('reportType');
    
        if(!empty($from_date) && !empty($to_date)) {
            $first_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));

            if (!empty($reportType)) {
                $reportTypeData = $reportType;
            }
            $result_array = $this->PilModel->getPilReportData($first_date,$to_date,$reportTypeData);
           
            $data['pil_result']=$result_array;
            $data['first_date']=$first_date;
            $data['to_date']=$to_date;
            $data['reportType']=$reportTypeData;
            return view('PIL/pilReportResult',$data);
        }
    }

    public function getPilUserWise()
    {

        if (!empty($_POST)) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            //  echo "TTT";
            $first_date = date('Y-m-d', strtotime($from_date));
            $to_date = date('Y-m-d', strtotime($to_date));
            $reportTypeData = 'C';
            $result_array = $this->PilModel->getUserWorkDone($first_date, $to_date, $reportTypeData);
 
            if ($result_array === 'false') {
                $data['pil_result'] = '';
            } else {
                $data['pil_result'] = $result_array;
            }
            $data['first_date'] = $first_date;
            $data['to_date'] = $to_date;
            $data['reportType'] = $reportTypeData;
 
            echo view('PIL/pilReportUserWiseDetail', $data);
            exit();
        }else{
            return view('PIL/pilReportUserWise');
        }

    }

    public function getWorkDone($dated,$updatedBy){
//        print_r($_GET);die;
        $result_array=$this->PilModel->getWorkDone($dated,$updatedBy);
        if($result_array === 'false') {
            $data['pil_result']='';
        }else{
            $data['pil_result']=$result_array;
        }

        $data['dated']=$dated;
        return view('PIL/pilEachUserWiseDetail',$data);
    }


    public function downloadGeneratedReport($reportType, $ecPilId, $ecPilGroupId)
    {

       
        // Load necessary models
        //$this->CourtMasterModel = new \App\Models\Court\CourtMasterModel();
    
        // Fetch user details and report data
        $userdetail = $this->CourtMasterModel->getUserNameAndDesignation($_SESSION['login']['usercode']);
        $pilGroupData = $this->PilModel->getCasesInPilGroup_asc($ecPilGroupId);
        $pilData = $this->PilModel->getPilDataById($ecPilId);

    // pr($userdetail);

        // Load FPDF
        $pdf = new FPDF();

        
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetLeftMargin(40);
        $pdf->SetRightMargin(20);
        
        // Check report type
        if ($reportType == 1) {
            // Report Type 1 Content
            $pdf->SetFont('times', '', 12);
            $pdf->Image(base_url('assets/images/sci_logo_new.jpg'), 90, 15, 20);
            $pdf->Ln(6);
            $pdf->Cell(120, 65, 'By Registered AD', 0, 0, 'C');
            $pdf->Cell(0, 10, 'SUPREME COURT OF INDIA', 0, 0, 'R');
            $pdf->Cell(0, 20, 'NEW DELHI-110201', 0, 1, 'R');
            $pdf->Ln(20);
            $pdf->SetFont('Courier', '', 11);
            $pdf->Cell(0, 8, 'Inward No. ' . $pilData[0]['diary_number'] . '/SC/PIL(E)/' . $pilData[0]['diary_year'], 0, 0, 'R');
            $pdf->Cell(0, 16, 'Dated: ' . date('d-m-Y'), 0, 1, 'R');
            $pdf->Ln(15);
            $pdf->Write(5, 'From:  Deputy Registrar');
            $pdf->Ln(5);
            $pdf->Write(5, 'Public Interest Litigation Cell(E)');
            $pdf->Ln(15);
            $pdf->Write(5, 'To:');
            $pdf->Ln(5);
            $pdf->Write(5, $_POST['comment']);
            $pdf->Ln(17);
            $pdf->Write(5, 'Sir,');
            $pdf->Ln(5);
            $pdf->Write(5, 'I am directed to forward herewith the letter-petition dated ' . substr($pilData[0]['petition_date'], 0, 10) . ', sent by ' . $_POST['Title'] . $pilData[0]['received_from'] . ', which is self-explanatory, for action as deemed fit in the matter.');
            $pdf->Ln(5);
            $pdf->Write(5, 'Thanking you,');
            $pdf->Ln(5);
            $pdf->Cell(0, 8, 'Yours faithfully,', 0, 1, 'R');
            $pdf->Ln(10);
            $pdf->Cell(0, 8, 'Deputy Registrar', 0, 1, 'R');
            //$pdf->Output();
    
        } elseif ($reportType == 2) {
            // Report Type 2 Content
            $pdf->Ln(6);
            $pdf->SetFont('times', '', 12);
            $pdf->Image(base_url('assets/images/sci_logo_new.jpg'), 90, 15, 20);
            $pdf->Cell(120, 65, 'By Registered AD', 0, 0, 'C');
            $pdf->Cell(0, 10, 'SUPREME COURT OF INDIA', 0, 0, 'R');
            $pdf->Cell(0, 20, 'NEW DELHI-110201', 0, 1, 'R');
            $pdf->Ln(20);
            $pdf->SetFont('Courier', '', 11);
            $pdf->Cell(0, 8, 'Inward No. ' . $pilData[0]['diary_number'] . '/SC/PIL(E)/' . $pilData[0]['diary_year'], 0, 0, 'R');
            $pdf->Cell(0, 16, 'Dated: ' . date('d-m-Y'), 0, 1, 'R');
            $pdf->Ln(15);
            $pdf->Write(5, 'From:  Deputy Registrar');
            $pdf->Ln(5);
            $pdf->Write(5, 'Public Interest Litigation Cell(E)');
            $pdf->Ln(15);
            $pdf->Write(5, 'To:');
            $pdf->Ln(5);
            $pdf->Write(5, $_POST['comment']);
            $pdf->Ln(17);
            $pdf->Write(5, 'Sir,');
            $pdf->Ln(5);
            $pdf->Write(5, 'You are requested to take necessary action and submit a report in English at the earliest.');
            $pdf->Ln(5);
            $pdf->Write(5, 'Thanking you,');
            $pdf->Ln(5);
            $pdf->Cell(0, 8, 'Yours faithfully,', 0, 1, 'R');
            $pdf->Ln(10);
            $pdf->Cell(0, 8, 'Deputy Registrar', 0, 1, 'R');
            //$pdf->Output();
    
        } elseif ($reportType == 3) {
            // Report Type 3 Content
            $pdf->Ln(6);
            $pdf->SetFont('times', '', 12);
            $pdf->Image(base_url('assets/images/sci_logo_new.jpg'), 90, 15, 20);
            $pdf->Cell(120, 65, 'By Registered AD', 0, 0, 'C');
            $pdf->Cell(0, 10, 'SUPREME COURT OF INDIA', 0, 0, 'R');
            $pdf->Cell(0, 20, 'NEW DELHI-110201', 0, 1, 'R');
            $pdf->Ln(20);
            $pdf->SetFont('Courier', '', 11);
            $pdf->Cell(0, 8, 'Inward No. ' . $pilData[0]['diary_number'] . '/SC/PIL(E)/' . $pilData[0]['diary_year'], 0, 0, 'R');
            $pdf->Cell(0, 16, 'Dated: ' . date('d-m-Y'), 0, 1, 'R');
            $pdf->Ln(15);
            $pdf->Write(5, 'From:  Branch Officer');
            $pdf->Ln(5);
            $pdf->Write(5, 'Public Interest Litigation Cell(E)');
            $pdf->Ln(15);
            $pdf->Write(5, 'To:');
            $pdf->Ln(5);
            $pdf->Write(5, $_POST['comment']);
            $pdf->Ln(17);
            $pdf->Write(5, 'Sir,');
            $pdf->Ln(5);
            $pdf->Write(5, 'With reference to your complaint, we are returning the original complaint under Article 235 of the Constitution of India.');
            $pdf->Ln(5);
            $pdf->Write(5, 'No further correspondence in this regard will be entertained.');
            $pdf->Ln(5);
            $pdf->Write(5, 'Thanking you,');
            $pdf->Ln(5);
            $pdf->Cell(0, 8, 'Branch Officer', 0, 1, 'R');
            //$pdf->Output();
    
        }elseif ($reportType=='With_Brief_History'){

           // error_reporting(0);
           // pr($pilData);
            /*var_dump($pilGroupData);
            exit(0);*/
                    /*foreach($pilData as $index=>$data){*/
                        foreach($pilGroupData as $index=>$data){
                        if($index==0){
                            $reportContent="Inward Nos. ";
                            $receivedFrom="Received from :- ".$this->common->convertToTitleCase($data['received_from']);
                        }
                        $reportContent.=$data['pil_diary_number'].", ";
                    }

                    if(!empty($pilData))
                    $totalPils=count($pilData);
                    else
                    $totalPils=0;
                  
                    $reportContent=rtrim($reportContent,', ');
                    $pdf->ln(5);
                    $pdf->SetFont('times','BU',12);
                    $pdf->Cell(0,3,'SUPREME COURT OF INDIA',0,1,'C');
                    $pdf->Cell(0,8,'PIL(ENGLISH) CELL',0,1,'C');
                    $pdf->SetFont('times','',11);
                    $pdf->Cell(0,8,'Dated: '. date('d-m-Y'),0,1,'R');
        
                    $pdf->MultiCell(0, 8, $reportContent.".");

                 
                    /*$i=1;
                    while($i<=40){
                        $pdf->Cell(20,8,'',0,0,'L');
                        $pdf->Cell(10,8,$i.'.',0,0,'L');
                        $pdf->Cell(50,8,'D.No.7827/2019',0,0,'L');
                        $pdf->Cell(100,8,'Vijay Kumar Sharma',0,1,'L');
                        $i++;
                    }*/
                    $pdf->ln(5);
                    $pdf->SetFont('times','B',11);
                    $pdf->Cell(80,8,$receivedFrom,0,1,'L');
                    /*$pdf->Cell(80,8,$totalPils.' - letter petitions.',0,1,'L');*/
                    /*$pdf->Cell(15,8,'    ',0,0,'L');*/
                    $pdf->ln(5);
                    $pdf->SetFont('times','BU',11);
                    /*$pdf->Cell(80,0,'Brief History of the case and relief sought',0,1,'L');*/
                    $pdf->Write(5,'Brief History of the case and relief sought');
                    $pdf->ln(10);
                    $pdf->SetFont('times','',11);
                    $pdf->Write(5,$_POST['comment']);
        
                 
        
                    $pdf->ln(15);
                    $pdf->SetFont('times','B',9);
                    $pdf->Cell(80,8,$userdetail->name,0,1,'L');
                    $pdf->Cell(80,0,$userdetail->type_name,0,1,'L');
                    $pdf->ln(15);
                    $pdf->Cell(80,0,'BRANCH OFFICER',0,1,'L');
                    /*$pdf->Cell(80,0,'Branch Officer',0,1,'L');*/
                    $pdf->ln(15);
                    $pdf->Cell(80,0,'DEPUTY REGISTRAR',0,1,'L');
                   /* $pdf->Cell(80,0,'Deputy Registrar',0,1,'L');*/
                    $pdf->ln(15);
                    /*$pdf->Cell(80,0,'Ld.Registrar(PIL E)',0,1,'L');*/
                    $pdf->Cell(80,0,'Ld.REGISTRAR(PIL E)',0,1,'L');
                    
                   // $pdf->Output();
                }
    
                if (ob_get_length()) ob_end_clean();
                    $pdf->Output();
                   // exit();
                   die;
    }


    public function getPilDetailByDiaryNumberForLetterGeneration(){

        $data['msg']='';
       return view('PIL/generateLetters',$data);
    }

    public function getSenderAndAddressForLetterGeneration(){
        extract($_GET);

        /*var_dump($_POST);*/
        $ecPilId=$this->PilModel->getPilId($diaryNo,$diaryYear);
         
        $data['reportType']=$reportType;
        $data['pilDetails']= '';
        if(!empty($ecPilId['id']))
            $data['pilDetails']=$this->PilModel->getPilDataById($ecPilId['id']);
        else
            $data['message']="No record found!!";
       
       // $data['dataForADToDispatch'] = $this->PilModel->enteredDakToDispatchInRIWithProcessId($_POST); 
        return view('PIL/downloadLetters',$data);
      }

}



?>