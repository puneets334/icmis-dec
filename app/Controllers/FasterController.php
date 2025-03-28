<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 2/8/21
 * Time: 12:05 PM
 */
namespace App\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\FasterModel;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FasterController extends BaseController
{
    public $fasterModel;
    public $courtMasterModel;
    public $diary_no;
    public $qrlib;
    public $Fpdf;

    public function __construct()
    {
        // parent::__construct();
        // $this->load->helper('form');
        // $this->load->helper('url');
        // $this->load->helper('html');
        // $this->load->database('sci_cmis_final');
        // $this->load->model('CourtMasterModel');
        // $this->load->model('FasterModel');
        // $this->load->model('StakeHolder_model');
        // $this->load->library('form_validation');
        // $this->load->helper('download');
        // $this->load->library('zip');
        // $this->load->helper('functions_helper');
        $this->fasterModel = new FasterModel();
        $this->courtMasterModel = new CourtMasterModel();
    }

    public function index($usercode)
    {
    //public function index(){
      //  echo "Hello";

        //$usercode=1;
        $this->session->set_userdata('dcmis_user_idd', $usercode);
        $data['msg']=$msg;
        $data['caseTypes'] = $this->courtMasterModel->getCaseType();
        $data['usercode'] = $usercode;
        $this->clearFasterSession();
        //var_dump($data['caseDetails']);
        return  view('Faster/caseSearch', $data);
    }

    public function startFasterWithId($diaryNumber,$nextDate="")
    {
        $this->clearFasterSession();
        $this->goToCurrentStage($diaryNumber,$nextDate);
    }

    public function fasterProcess($usercode,$msg="")
    {
        $this->session->set_userdata('dcmis_user_idd', $usercode);
        $data['msg']=$msg;
        $data['caseTypes'] = $this->courtMasterModel->getCaseType();
        $data['usercode'] = $usercode;
        //var_dump($data['caseDetails']);
        return  view('Faster/caseSearch', $data);
    }

    public function getFasterCaseDetails()
    {
        extract($_POST);

        $diaryNumberForSearch = null;
        if ($optradio == 'C') {
            $diaryNumberForSearch = $this->courtMasterModel->getSearchDiary($caseType, $caseNo, $caseYear, null, null, $optradio);
        } else if ($optradio == 'D') {
            $diaryNumberForSearch = $this->courtMasterModel->getSearchDiary(null, null, null, $diaryNumber, $diaryYear, $optradio);
        }
        if ($diaryNumberForSearch != null) {
            $userDetails=$this->fasterModel->getUserDetail($_SESSION['dcmis_user_idd']);

            $_SESSION['sessionUserName']=$userDetails[0]['name'];
            $_SESSION['sessionUserSection']=$userDetails[0]['section_name'];
            $_SESSION['sessionUserDesignation']=$userDetails[0]['type_name'];
            $_SESSION['sessionUserEmployeeCode']=$userDetails[0]['empid'];
            $this->goToCurrentStage($diaryNumberForSearch,$causelistDateSingle);

            /*$caseDetails=$this->getCaseDetails($diaryNumberForSearch);
            $data['caseDetails']=$caseDetails;

            $casetype_id=$caseDetails[0]['casetype_id'];
            if(!empty($caseDetails[0]['active_casetype_id'])){
                $casetype_id=$caseDetails[0]['active_casetype_id'];
            }
            $data['noticeTypes']=$this->fasterModel->getNoticeType($caseDetails[0]['nature'],$caseDetails[0]['section_id'],$caseDetails[0]['c_status'],$casetype_id);

            if(isset($caseDetails[0]['last_step_id']) && !empty($caseDetails[0]['last_step_id'])){
                switch ($caseDetails[0]['last_step_id']){
                    case ADD_DOCUMENTS:
                        $data['multiStepFlag']='AddDocuments';
                        break;
                    case DIGITAL_SIGNATURE:
                        $data['multiStepFlag']='DigitalSign';
                        break;
                    case DIGITAL_CERTIFICATION:
                        $data['multiStepFlag']='DigitalCertification';
                        break;
                    case DOWNLOAD:
                        $data['multiStepFlag']='Download';
                        break;
                }
            }
            else{
                $data['multiStepFlag']='AddDocuments';
            }
            return  view('Faster/multiStep',$data);*/
        } else if ($msg == "") {
            $data['msg'] = "No record found!!";
        }
    }
    private function goToCurrentStage($diaryNumberForSearch,$nextDate=""){
        $_SESSION['diaryNumberForSearch']=$diaryNumberForSearch;
        if(!empty($nextDate)){
            $_SESSION['nextDate']=convertToYmd($nextDate);
        }
        $caseDetails=$this->getCaseDetails($diaryNumberForSearch,$nextDate);
        
        
        $data['caseDetails']=$caseDetails;
        $casetype_id=$caseDetails[0]['casetype_id'];
        if(!empty($caseDetails[0]['active_casetype_id'])){
            $casetype_id=$caseDetails[0]['active_casetype_id'];
        }
        $data['noticeTypes']=$this->fasterModel->getNoticeType($caseDetails[0]['nature'],$caseDetails[0]['section_id'],$caseDetails[0]['c_status'],$casetype_id);

        if(isset($caseDetails[0]['last_step_id']) && !empty($caseDetails[0]['last_step_id'])){
            switch ($caseDetails[0]['last_step_id']){
                case ADD_DOCUMENTS:
                    $data['multiStepFlag']='AddDocuments';
                    break;
                case DIGITAL_SIGNATURE:
                    $data['multiStepFlag']='DigitalSign';
                    break;
                case DIGITAL_CERTIFICATION:
                    $data['multiStepFlag']='DigitalCertification';
                    break;
                case DOWNLOAD:
                    $data['multiStepFlag']='Download';
                    break;
            }
        }
        else{
            $data['documentsInICMIS']=$this->fasterModel->getAvailableDocumetsInICMIS($diaryNumberForSearch,$_SESSION['nextDate']);
            $data['multiStepFlag']='AddDocuments';
        }
        return  view('Faster/multiStep',$data);
    }
    public function getFasterCaseDetailsDNo(){
        //getAvailableDocumetsInICMIS
        $diaryNumberForSearch = $_SESSION['diaryNumberForSearch'];
        if(!empty($diaryNumberForSearch)){
            $data['documentsInICMIS']=$this->fasterModel->getAvailableDocumetsInICMIS($diaryNumberForSearch,$_SESSION['nextDate']);
            $caseDetails=$this->getCaseDetails($diaryNumberForSearch,$_SESSION['nextDate']);
            $data['caseDetails']=$caseDetails;
            $casetype_id=$caseDetails[0]['casetype_id'];
            if(!empty($caseDetails[0]['active_casetype_id'])){
                $casetype_id=$caseDetails[0]['active_casetype_id'];
            }
            $data['noticeTypes']=$this->fasterModel->getNoticeType($caseDetails[0]['nature'],$caseDetails[0]['section_id'],$caseDetails[0]['c_status'],$casetype_id);
        }
        else{
            $data['caseDetails']=NULL;
        }

        $data['multiStepFlag']='AddDocuments';
        return  view('Faster/multiStep',$data);
    }
    private function getCaseDetails($diaryNo,$nextDate=""){
        $caseDetails=$this->fasterModel->caseDetails($diaryNo,$nextDate);
        if(count($caseDetails)>0){
            $_SESSION['caseNumber']="Case No.: ".$caseDetails[0]['reg_no_display']."(".substr($caseDetails[0]['diary_no'], 0, -4)."/".substr($caseDetails[0]['diary_no'], -4).")";
            $_SESSION['causetitle']="Causetitle :".$caseDetails[0]['pet_name']. "Vs. ".$caseDetails[0]['res_name'];
            $_SESSION['main_case_dno']=$caseDetails[0]['conn_key'];
            if(!empty($caseDetails[0]['faster_cases_id'])){
                $_SESSION['fasterCasesId']=$caseDetails[0]['faster_cases_id'];
            }
            else{
                $_SESSION['fasterCasesId']=NULL;
            }
            return $caseDetails;
        }
        return false;
    }

    public function getFasterDigitalSign(){
        $dataAttached=$this->fasterModel->attachedDocumentByFasterCasesId($_SESSION['fasterCasesId']);
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DIGITAL_SIGNATURE);
        $result=1;
        if($result){
            $data['dataAttached'] = $dataAttached;
            $data['multiStepFlag']='DigitalSign';
            return  view('Faster/multiStep',$data);
        }
    }
    public function getFasterDigitalCertification(){
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DIGITAL_CERTIFICATION);
        $result=1;
        if($result){
            $dataAttached=$this->fasterModel->attachedDocumentByFasterCasesId($_SESSION['fasterCasesId']);
            $data['dataAttached'] = $dataAttached;
            $data['multiStepFlag']='DigitalCertification';
            return  view('Faster/multiStep',$data);
        }
    }
    public function getFasterDownload(){
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DOWNLOAD);
        $result=1;
        if($result){
            $data['multiStepFlag']='Download';
            return  view('Faster/multiStep',$data);
        }
    }
    public function getStakeholderDetails(){
        $output = false;
        if(isset($_POST) && !empty($_POST) && count($_POST) >0){
            $contactDetails = $this->StakeHolder_model->getReportData($_POST);
            // echo '<pre>'; print_r($output); exit;
        }
        $output="<option value='0'>Select</option>";
        foreach($contactDetails as $detail){
            $data = (!empty($detail->designation) ? trim($detail->designation).',' : '').(!empty($detail->district_name) ? trim($detail->district_name).',' : '').(!empty($detail->state_name) ? trim($detail->state_name) : '').(!empty($detail->email_id) ? ' ('.trim($detail->email_id).')' : '');
            $output.="<option value='$detail->id'>".$data."</option>";
        }
        echo json_encode($contactDetails);
        echo $output;
        exit(0);
    }
    public function getFasterSendEmail(){
        $data['stakeholderType'] = $this->StakeHolder_model->getStakeHolderType();
        $data['states']=$this->StakeHolder_model->getState();
        $data['multiStepFlag']='sendEmail';
        return  view('Faster/multiStep',$data);
    }
    public function getDocumentsDates(){
        extract($_POST); 
        echo $docType; // 162. 165, 163
        
if($_SESSION['main_case_dno'] != $_SESSION['diaryNumberForSearch'] && $_SESSION['main_case_dno'] != null && $_SESSION['main_case_dno'] != 0 && ($docType == 162 || $docType == 163 || $docType == 165)){
    $docDates=$this->fasterModel->documentsDates($_SESSION['main_case_dno'],$docType);
}
else{
    $docDates=$this->fasterModel->documentsDates($_SESSION['diaryNumberForSearch'],$docType);
}       
        //var_dump($docDates);
        $htmlStr="";
        foreach($docDates as $date){
            $htmlStr.="<option value='$date[pdfname]'>$date[orderdate]</option>";
        }
        echo $htmlStr;
    }
    public function showPDF(){
        extract($_POST);
        //pdf_notices/2021/5166/1473709_68_R.pdf
        if($docType==DOCUMENT_MEMO_OF_PARTY){
            $docDates=$this->fasterModel->documentsDates($_SESSION['diaryNumberForSearch'],DOCUMENT_MEMO_OF_PARTY);
            $path=$docDates[0]['pdfname'];
            $completeFilePath=WEB_ROOT."/supreme_court/";
        }else{
            $completeFilePath=LIVE_PATH;
        }
        //unserialize(ROP_MEMO);
        if(in_array($docType,unserialize(ROP_MEMO))){
            $completeFilePath.="jud_ord_html_pdf/".$path;
        }
        else{
            $completeFilePath.="pdf_notices/".$path;
        }
//echo $completeFilePath;
        $headers = @get_headers($completeFilePath);
        if($headers && strpos( $headers[0], '200')) {
            echo '<input type="hidden" id="completeFilePath" name="completeFilePath" value="'.$completeFilePath.'">';
            echo '<object data="'.$completeFilePath.'" type="application/pdf" id="fileDoc" name="fileDoc" width="100%" height="900px" internalinstanceid="9" ></object>';
        }
        else {
            echo $status = "<div class=\"alert alert-danger\" role=\"alert\"> File not Exists!</div>";
        }
    }
    public function showAttachedFile(){
        extract($_POST);

        if (strpos($documentId, '_') !== false) {
            $details=explode("_",$documentId);
            $id=$details[1];
        }
        else{
            $id=$documentId;
        }

        $documentDetails=$this->fasterModel->attachedDocumentById($id);
        //print_r($documentDetails);

        //$showDigitallyCertifiedFile
        if($showDigitallyCertifiedFile=='true'){
            if($documentDetails[0]['is_digitally_certified']==1){
                $filename = pathinfo($documentDetails[0]['file_name'], PATHINFO_FILENAME);
                $filename=$filename."_Certified.pdf";
                $completeFilePath=WEB_ROOT."/supreme_court/jud_ord_html_pdf/".$documentDetails[0]['file_path'].$filename;
                //$completeFilePath=WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$filename;
            }
        }
        else if($showDigitallySignedFile=='true' && $documentDetails[0]['is_digitally_signed']==1 && !in_array($documentDetails[0]['tw_notice_id'],array(DOCUMENT_ROP,DOCUMENT_JUDGMENT,DOCUMENT_SIGNED_ORDER,DOCUMENT_MEMO_OF_PARTY))){
            $filename = pathinfo($documentDetails[0]['file_name'], PATHINFO_FILENAME);
            $filename=$filename."_Signed.pdf";
            $completeFilePath=WEB_ROOT."/supreme_court/jud_ord_html_pdf/".$documentDetails[0]['file_path'].$filename;
            //$completeFilePath=WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$filename;
        }
        else{
            $completeFilePath=WEB_ROOT."/supreme_court/jud_ord_html_pdf/".$documentDetails[0]['file_path'].$documentDetails[0]['file_name'];
            //$completeFilePath=WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$documentDetails[0]['file_name'];
        }
        //echo $completeFilePath;
        $headers = @get_headers($completeFilePath);
        if($headers && strpos( $headers[0], '200')) {
            $document_date_check = "";
            if($documentDetails[0]['dated'] == '0000-00-00'){
                $document_date_check = "";
            }
            else{
                $document_date_check = " (".convertTodmY($documentDetails[0]['dated']).")";
            }
            echo "<h4>".$documentDetails[0]['name']." ".$document_date_check."</h4>";
            echo '<object data="'.$completeFilePath.'" type="application/pdf" id="showdoc" name="showdoc" width="100%" height="900px" internalinstanceid="9" ></object>';
            exit;
        }
        else {
            echo $status = "<div class=\"alert alert-danger\" role=\"alert\"> File not Exists!</div>";
        }
    }
    public function deleteAttachedFile(){
        extract($_POST);
        if (strpos($documentId, '_') !== false) {
            $details=explode("_",$documentId);
            $id=$details[1];
        }
        //DELETE_ATTACHED_FILE
        $desired_dir = "/home/reports/";
        $documentsDetails=$this->fasterModel->attachedDocumentById($id);
        if(count($documentsDetails)>0){
            //set is_deleted in faster_shared_document_details
            $fileToUnlink = "/home/reports/".$documentsDetails[0]['file_path'].$documentsDetails[0]['file_name'];
            $dataToUpdate=array('is_deleted'=>1,'deleted_on'=>date('Y-m-d H:s:i'));
            $conditions=array('id'=>$id,'is_deleted'=>0);
            $deletedRows=$this->fasterModel->updateInDB('faster_shared_document_details',$dataToUpdate,$conditions);
            if($deletedRows>0){
                $fastreTransactionData=array('ref_faster_steps_id'=>DELETE_ATTACHED_FILE,'faster_cases_id'=>$_SESSION['fasterCasesId'],'faster_shared_document_details_id'=>$id,'created_by'=>$usercode,'created_by_ip'=>get_client_ip());
                $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);
                unlink($fileToUnlink); // To delete concerned file from storage
                echo "SUCCESS";
            }
            else{
                echo "ERROR";
            }
            //add row in faster_transactions
        }
        else{
            echo "ERROR";
        }

    }
    public function attachDocument(){
        extract($_POST);
        $usercode=1;
        $dateDetails=explode(" ",$hiddenDocDate);
        $process_id=NULL;
        $data=array();
        $ifExit=false;
        if(count($dateDetails)>1){
            $pids=explode('-',$dateDetails[1]);
            $process_id=$pids[1];
        }
        if(!empty($docDate)){
            $pathDetails=explode("/",$docDate);
            $filename=$pathDetails[count($pathDetails)-1];
        }


        //TODO:: 1. Copy file in faster repository.
        // 2. Make entry in faster_cases,faster_shared_document_details and faster_transactions
        // 3. Show attached files list in the view.

        $fasterCaseDetails=$this->fasterModel->fasterCases($_SESSION['diaryNumberForSearch'],$_SESSION['nextDate'],'P');

        if(count($fasterCaseDetails)>0){
            $fasterCasesId=$fasterCaseDetails[0][id];
        }
        else{
            $dataFasterCases=array('diary_no'=>$_SESSION['diaryNumberForSearch'],'next_dt'=>$_SESSION['nextDate'],'created_by'=>$usercode,'last_step_id'=>ADD_DOCUMENTS);
            $fasterCasesId=$this->fasterModel->insertInDBwithInsertedId('faster_cases',$dataFasterCases);
        }

        $_SESSION['fasterCasesId']=$fasterCasesId;
        $sharedDocumentDetails=$this->fasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch'],$fasterCasesId,$docType,$process_id,$dateDetails[0]);
        //var_dump($sharedDocumentDetails);
        if(count($sharedDocumentDetails)>0){
            $data['message']['code']="ERROR";
            $data['message']['msg']="This document is already attached!";
        }
        else{
            //Copy File
            $diary_number_only = substr($_SESSION['diaryNumberForSearch'], 0, -4);
            $diary_year = substr($_SESSION['diaryNumberForSearch'], -4);
            $desired_dir = FASTER_STORAGE . $diary_year . "/" . $diary_number_only."/".$fasterCasesId;
//            $desired_dir_in_db = FASTER_STORAGE_FOR_DB . $diary_year . "/" . $diary_number_only."/".$fasterCasesId."/".$filename;
            $desired_dir_in_db = FASTER_STORAGE_FOR_DB . $diary_year . "/" . $diary_number_only."/".$fasterCasesId."/";
            if (is_dir($desired_dir) == false) {
                mkdir("$desired_dir", 0755, true);
            }
            if (is_dir("$desired_dir/" . $filename) == false) {
                copy($completeFilePath,$desired_dir."/".$filename);
            }
            //END
            $isSigned=0;
            if(in_array($docType,unserialize(DOCUMENT_EXEMPTED_FROM_SIGNING))){
                $isSigned=1;
            }

            $sharedDocumentData=array('faster_cases_id'=>$fasterCasesId,'tw_notice_id'=>$docType,'dated'=>$dateDetails[0],'file_path'=>$desired_dir_in_db,'file_name'=>$filename,'process_id'=>$process_id,'is_digitally_signed'=>$isSigned,'created_by'=>$usercode,'created_by_ip'=>get_client_ip());
            $sharedDocumentId=$this->fasterModel->insertInDBwithInsertedId('faster_shared_document_details',$sharedDocumentData);

            $fastreTransactionData=array('ref_faster_steps_id'=>ADD_DOCUMENTS,'faster_cases_id'=>$fasterCasesId,'faster_shared_document_details_id'=>$sharedDocumentId,'created_by'=>$usercode,'created_by_ip'=>get_client_ip());
            $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);
            $data['message']['code']="SUCCESS";
            $data['message']['msg']="Document attached successfully!";
        }
        echo json_encode($data);
    }
    public function getSharedDocuments(){
        /*extract($_POST);
        if(!empty($fasterCasesId)){
            $data['sharedDocuments']=$this->fasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch']);
        }*/

        $sharedDocuments=$this->fasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch'],"","","",$_SESSION['nextDate']);
        echo json_encode($sharedDocuments);
        return $sharedDocuments;
    }
    public function getTransactions(){
        extract($_POST);
        $transactions=$this->fasterModel->transationList($_SESSION['fasterCasesId'],$step);
        echo json_encode($transactions);
    }

    public function getDigitalSignInput(){
        extract($_POST);
        $data['faster_case_id'] = $faster_case_id;
        $data['file_path'] = $file_path;
        $data['faster_shared_doc_id'] = $faster_shared_doc_id;
        return  view('Faster/get_token_certificates', $data);
    }
    public function getDigitalCertificateInput(){
        extract($_POST);
        $data['faster_case_id'] = $faster_case_id;
        $data['file_path'] = $file_path;
        $data['faster_shared_doc_id'] = $faster_shared_doc_id;
        return  view('Faster/get_token_certification_token', $data);
    }

    public function setTokenTask(){
       $this->getSessionDetail($_SESSION['dcmis_user_idd']);
        date_default_timezone_set("Asia/Kolkata");
            $doc_id = $_POST['doc_id'];
            $documentDetails=$this->fasterModel->attachedDocumentById($doc_id);
        //supremecourt/faster_assets/2019/43/1/43_2019_Order_09-Apr-2019.pdf
            $uploadedFileName= explode(".",$documentDetails[0]['file_name']);
            //$sign_str = "Signed By : ".$_SESSION['sessionUserName']."\n".$_SESSION['sessionUserDesignation']."\n".$_SESSION['sessionUserSection'][0]."\nSupreme Court of India\n".date('jS \of F Y h:i:s A');
        $sign_str = "Signed By : ".$_SESSION['sessionUserName']."\n".$_SESSION['sessionUserDesignation']."\nSupreme Court of India\n".date('jS \of F Y h:i:s A');
        //$signature_position = array('x'=>200,'y'=>200,'x1'=>55,'y1'=>70);
            $x=array('certificateId'=>$_POST['dd_certificate'],
                'email'=>'',
                'font_size'=>7,
                'label'=>$_POST['token_label'],
                'pdf_path'=> urldecode($_POST['url_pdf_embed_path']),
                'pin'=>$_POST['token_pin'],
                'signature'=> $sign_str,
                'sign_location'=>array('x'=>100,'y'=>770,'x1'=>250,'y1'=>820)
            );
        //top
        //'x'=>100,'y'=>770,'x1'=>250,'y1'=>820
        //bottom
        //'x'=>100,'y'=>30,'x1'=>250,'y1'=>80



        //'sign_location'=>json_decode($signature_position)
            ini_get('allow_url_fopen');
            json_encode( $x );
            //var_dump($x);
            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode( $x ),
                    'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
                )
            );

            $context  = stream_context_create( $options );

            $token_sign = "http://".get_client_ip().":8100/api/v1/tokensigner";
        //$token_sign = "http://".get_client_ip().":8000/api/v2/tokensigner/pdf";
            $result = file_get_contents( $token_sign, false, $context );
            //var_dump($result);
            $json_result = json_decode($result);

            //var_dump($json_result);

            $source_url = $json_result[1]->{signed_file};
            if(!empty($source_url) && $source_url != ''){
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );

                $destination_path = FILE_ROOT_PATH.$documentDetails[0]['file_path'].$uploadedFileName[0]."_Signed.pdf";
                $destination_path2 = $documentDetails[0]['file_path'].$uploadedFileName[0]."_Signed.pdf";

                $documentDetails[0]['file_path'];

                if(!file_put_contents( $destination_path,file_get_contents($source_url, false, stream_context_create($arrContextOptions)))){
                    echo "failed";
                }
                else {
                    $this->db->trans_start();
                    $fastreTransactionData=array('ref_faster_steps_id'=>DIGITAL_SIGNATURE,'faster_cases_id'=>$_POST['faster_case_id'],'faster_shared_document_details_id'=>$_POST['doc_id'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
                    $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);

                    $dataToUpdate=array('last_step_id'=>DIGITAL_SIGNATURE);
                    $conditions=array('id'=>$_POST['faster_case_id'],'is_deleted'=>0);

                    $updateRows=$this->fasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

                    $dataToUpdateInDocuments=array('is_digitally_signed'=>1,'digitally_signed_on'=>date('Y-m-d H:i:s'));
                    $conditionsInDocuments=array('id'=>$_POST['doc_id'],'is_deleted'=>0);
                    $updateRowsInDocuments=$this->fasterModel->updateInDB('faster_shared_document_details',$dataToUpdateInDocuments,$conditionsInDocuments);
                    $this->db->trans_complete();
                    echo '<object data="'.WEB_ROOT.'/supreme_court/jud_ord_html_pdf/'.$destination_path2.'" type="application/pdf" id="fileDoc" name="fileDoc" width="100%" height="900px" internalinstanceid="9" ></object>';
                }
            }
            else{
                echo "Error:No Response";
                //exit();
            }
    }
    public function setTokenCertificate(){
        date_default_timezone_set("Asia/Kolkata");
        $doc_id = $_POST['doc_id'];
		$_SESSION['fasterCasesId']=$_POST['faster_case_id'];
		
        
		$certificateNumber=$this->generateCertificateNumber($doc_id);
		
        if($certificateNumber){
            $documentDetails=$this->fasterModel->attachedDocumentById($doc_id);

            //supremecourt/faster_assets/2019/43/1/43_2019_Order_09-Apr-2019.pdf
            $uploadedFileName= explode(".",$documentDetails[0]['file_name']);
            //echo "lfkg";
            $sign_str = "Signed By : ".$_SESSION['sessionUserName']."\n".$_SESSION['sessionUserDesignation']."\n".$_SESSION['sessionUserSection'][0]."\nSupreme Court of India\n".date('jS \of F Y h:i:s A');
            //"sign_location":['x':120, 'y':20]


            $x=array('certificateId'=>$_POST['dd_certificate'],
                'email'=>'',
                'font_size'=>7,
                'label'=>trim($_POST['token_label']),
                'pdf_path'=> urldecode($_POST['url_pdf_embed_path']),
                'pin'=>$_POST['token_pin'],
                'signature'=> $sign_str,
                'image_url'=>WEB_ROOT.'/supreme_court/Copying/index.php/FasterController/generateImage/'.$certificateNumber, //TODO::Change icmis to supreme_court when going live
                'sign_location'=>array('x'=>275,'y'=>770,'x1'=>310,'y1'=>820)
            );
            //production
            //'sign_location'=>array('x'=>120,'y'=>50,'x1'=>155,'y1'=>100)
            //for top
            //'sign_location'=>array('x'=>275,'y'=>770,'x1'=>310,'y1'=>820)
            //for bottom
            //'sign_location'=>array('x'=>275,'y'=>40,'x1'=>310,'y1'=>85)

            ini_get('allow_url_fopen');
            json_encode( $x );
            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode( $x ),
                    'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
                )
            );

            $context  = stream_context_create( $options );

            $token_sign = "http://".get_client_ip().":8000/api/v2/tokensigner/pdf";
            $result = file_get_contents( $token_sign, false, $context );
            $json_result = json_decode($result);
            $source_url = $json_result[1]->{signed_files};
            if(!empty($source_url) && $source_url != ''){

                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );

                $destination_path = FILE_ROOT_PATH.$documentDetails[0]['file_path'].$uploadedFileName[0]."_Certified.pdf";
                $destination_path2 = $documentDetails[0]['file_path'].$uploadedFileName[0]."_Certified.pdf";

                $documentDetails[0]['file_path'];

                if(!file_put_contents( $destination_path,file_get_contents($source_url, false, stream_context_create($arrContextOptions)))){
                    echo "failed";
                }
                else {
                    $this->db->trans_start();
                    $fastreTransactionData=array('ref_faster_steps_id'=>DIGITAL_CERTIFICATION,'faster_cases_id'=>$_POST['faster_case_id'],'faster_shared_document_details_id'=>$_POST['doc_id'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
                    $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);

                    $dataToUpdate=array('last_step_id'=>DIGITAL_CERTIFICATION);
                    $conditions=array('id'=>$_POST['faster_case_id'],'is_deleted'=>0);

                    $updateRows=$this->fasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

                    $dataToUpdateInDocuments=array('is_digitally_certified'=>1,'digitally_certified_on'=>date('Y-m-d H:i:s'));
                    $conditionsInDocuments=array('id'=>$_POST['doc_id'],'is_deleted'=>0);
                    $updateRowsInDocuments=$this->fasterModel->updateInDB('faster_shared_document_details',$dataToUpdateInDocuments,$conditionsInDocuments);
                    $this->db->trans_complete();
                    echo '<object data="'.WEB_ROOT.'/supreme_court/jud_ord_html_pdf/'.$destination_path2.'" type="application/pdf" id="fileDoc" name="fileDoc" width="100%" height="900px" internalinstanceid="9" ></object>';
                }
            }
            else{
                echo "Error:No Response";
                //exit();
            }
        }
        else{
            echo "Error:There is some problen while generating Certificate Number.";
        }
    }
    public function downloadAll(){
        extract($_SESSION);
        $sharedDocuments=$this->fasterModel->fasterSharedDocuments(0,$fasterCasesId);
        $folderLocation=FILE_ROOT_PATH.$sharedDocuments[0]['file_path'];
        $zipFileDirectory=$folderLocation."complete_file/";
        if(is_dir($zipFileDirectory)){
            rmdir($zipFileDirectory);
        }
        else{
            mkdir($zipFileDirectory,'0755',true);
        }
        foreach($sharedDocuments as $index=>$document){
            $fileNameArray=explode(".",$document['file_name']);
            $fileName=$fileNameArray[0]."_Certified.pdf";
            $fileToAttach=FILE_ROOT_PATH.$document['file_path'].$fileNameArray[0]."_Certified.pdf";
            copy($fileToAttach,$zipFileDirectory.$fileName);
        }
        exec("chmod -R 0755 ".$zipFileDirectory);


        $zip = new ZipArchive();
        $zip->open($folderLocation . 'complete_file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        try{
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($zipFileDirectory),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
        }
        catch (UnexpectedValueException $e) {
            printf($e);
        }


        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($zipFileDirectory) );
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        ob_clean();
        $data = file_get_contents($folderLocation . '/complete_file.zip'); //assuming my file is on localhost
        $name = $_SESSION['diaryNumberForSearch']."_".date('d-m-Y').".zip";
        $this->delete_directory($zipFileDirectory);





        $this->db->trans_start();
        $fastreTransactionData=array('ref_faster_steps_id'=>DOWNLOAD,'faster_cases_id'=>$_SESSION['fasterCasesId'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
        $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);

        $dataToUpdate=array('last_step_id'=>DOWNLOAD);
        $conditions=array('id'=>$_SESSION['fasterCasesId'],'is_deleted'=>0);

        $updateRows=$this->fasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

        $this->db->trans_complete();





        /*$fastreTransactionData=array('ref_faster_steps_id'=>DOWNLOAD,'faster_cases_id'=>$_SESSION['fasterCasesId'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
        $this->fasterModel->insertInDB('faster_transactions',$fastreTransactionData);*/


        force_download($name, $data);
    }
    private function delete_directory($folderName)
    {
        return  helper('file'); // Load codeigniter file helper

        if(is_dir($folderName))
        {
            delete_files($folderName, true); // Delete files into the folder
            rmdir($folderName); // Delete the folder
            return true;
        }
        return false;
    }

    private function checkStageBeforeProceed($fasterCasesId, $expectedStage)
    {
        //Check stage while landing on any page either by clicking or directly after search of case number
        //$_SESSION['warning_message']="";
        if (empty($fasterCasesId)) {
            $_SESSION['warning_message']="Please add atleast one document to proceed.";
            redirect('FasterController/getFasterCaseDetailsDNo');
        }
        $details = $this->fasterModel->getCurrentStage($fasterCasesId);
        if (count($details) > 0) {
            $current_stage = $details[0]['last_step_id'];
            if (($expectedStage <= $current_stage)) {

            } else {
                $details=$this->fasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch']);
                switch ($expectedStage) {
                    case DIGITAL_SIGNATURE:
                        if(count($details)>0){
                            return true;
                        }
                        else{
                            $_SESSION['warning_message']="Please add atleast one document to proceed.";
                            redirect('FasterController/getFasterCaseDetailsDNo');
                        }
                        break;
                    case DIGITAL_CERTIFICATION:
                        //Check if atleast a document is uploaded and all uploaded documents must be digitally signed

                        if(count($details)>0){
                            $isDigitallySigned=NUUL;
                            foreach ($details as $detail){
                                if($detail['is_digitally_signed']==1){
                                    $isDigitallySigned=true;
                                }
                                else{
                                    $isDigitallySigned=false;
                                    break;
                                }
                            }
                            if($isDigitallySigned){
                                return true;
                            }
                            else{
                                $_SESSION['warning_message']="Please digitally sign all document(s) to proceed.";
                                redirect('FasterController/getFasterDigitalSign');
                            }
                        }
                        else{
                            $_SESSION['warning_message']="Please add atleast one document to proceed.";
                            redirect('FasterController/getFasterCaseDetailsDNo');
                        }
                        break;
                    case DOWNLOAD:
                        //Check if atleast a document is uploaded and all uploaded documents must be digitally signed
                        //Check if all documents are digitally certified
                        if(count($details)>0){
                            $isDigitallySigned=NUUL;
                            foreach ($details as $detail){
                                if($detail['is_digitally_signed']==1){
                                    $isDigitallySigned=true;
                                }
                                else{
                                    $isDigitallySigned=false;
                                    break;
                                }
                            }
                            if($isDigitallySigned){
                                $isDigitallyCertified=NULL;
                                foreach ($details as $detail){
                                    if($detail['is_digitally_certified']==1){
                                        $isDigitallyCertified=true;
                                    }
                                    else{
                                        $isDigitallyCertified=false;
                                        break;
                                    }
                                }
                                if($isDigitallyCertified){
                                    return true;
                                }
                                else{
                                    //redirect to digital Certification page
                                    $_SESSION['warning_message']="Please digitally certify all document(s) to proceed.";
                                    redirect('FasterController/getFasterDigitalCertification');
                                }
                            }
                            else{
                                //Redirect to digital signature
                                $_SESSION['warning_message']="Please digitally sign all document(s) to proceed.";
                                redirect('FasterController/getFasterDigitalSign');
                            }

                        }
                        else{
                            //redirect to add document page
                            $_SESSION['warning_message']="Please add atleast one document to proceed.";
                            redirect('FasterController/getFasterCaseDetailsDNo');
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }
    public function addEmailId(){
        extract($_POST);
        if(!empty($_SESSION['fasterCasesId'])){
            $details=$this->StakeHolder_model->getDataById($stakeholderDetails);
            $data=array('faster_cases_id'=>$_SESSION['fasterCasesId'],'stakeholder_details_id'=>$stakeholderDetails,'email_id'=>$details[0]['jcn_email_id'],'mobile_number'=>$details[0]['mobile_number'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
            $insertedRows=$this->fasterModel->insertInDB('faster_communication_details',$data);
            if($insertedRows>0){
                echo "SUCCESS";
            }
            else{
                echo "ERROR";
            }
        }
        else{
            echo "ERROR";
        }
    }
    public function getRecipientDetails(){
        $details=$this->fasterModel->recipientDetails($_SESSION['fasterCasesId']);
        echo json_encode($details);
    }
    public function doDeleteContact(){
        extract($_POST);
        $id=$fasterCommunicationDetailsId;
        if (strpos($fasterCommunicationDetailsId, '_') !== false) {
            $details=explode("_",$fasterCommunicationDetailsId);
            $id=$details[1];
        }
        if(!empty($id)){
            $dataToUpdate=array('is_deleted'=>1,'deleted_on'=>date('Y-m-d H:s:i'),'deleted_by'=>$_SESSION['dcmis_user_idd']);
            $conditions=array('id'=>$id,'is_deleted'=>0);
            $deletedRows=$this->fasterModel->updateInDB('faster_communication_details',$dataToUpdate,$conditions);
            if($deletedRows>0){
                echo "SUCCESS";
            }
            else{
                echo "ERROR";
            }
        }
        else{
            echo "ERROR";
        }
    }
    public function sendSMSNotification(){
        $smsText="";
    }
    public function sendEmail(){
        $file_type_name = unserialize(DOCUMENT_EXEMPTED_FROM_SIGNING);
        var_dump($file_type_name);
    }
    public function getCasesMarkedForFaster(){
        extract($_POST);
        if(empty($causelistDate)){
            $causelistDate=date('Y-m-d');
        }
        $causelistDate=date("Y-m-d", strtotime($causelistDate) );
        $markedCases=$this->fasterModel->casesMarkedForFaster($causelistDate);
        echo json_encode($markedCases);
    }
    private function clearFasterSession(){
        unset($_SESSION['diaryNumberForSearch']);
        unset($_SESSION['nextDate']);
    }
    public function generateImage($number="000000",$year=NULL){
        if(!isset($year)){
            $year=date('y');
        }
        $length = 6;
        $number = substr(str_repeat(0, $length).$number, - $length);
        $this->load->library('image_lib');
        $config['source_image'] = "./assets/images/sci_logo_new_new.jpg";
        //$config['wm_text'] = $number.date('y');
        $config['wm_text'] = $year.$number;
        $config['wm_type'] = 'text';
        $config['wm_font_path'] = './system/fonts/texb.ttf';
        $config['wm_font_size'] = '10';
        $config['wm_font_color'] = '000000';
        $config['wm_vrt_alignment'] = 'bottom';
        $config['wm_hor_alignment'] = 'center';
        $config['dynamic_output']= TRUE;
        //$config['wm_padding'] = '20';

        $this->image_lib->initialize($config);

        if ( ! $this->image_lib->watermark())
        {
            echo $this->image_lib->display_errors();
        }
    }
    private function generateCertificateNumber($fasterSharedDocumentDetailsId){
        
		if(empty($_SESSION['fasterCasesId'])){
            return false;
        }
        $certificateNumber= $this->fasterModel->getNextCertificateNumber();
		
        $data=array('certificate_number'=>$certificateNumber,'certificate_year'=>date('Y'),'faster_cases_id'=>$_SESSION['fasterCasesId'],
            'faster_shared_document_details_id'=>$fasterSharedDocumentDetailsId,'created_by'=>$_SESSION['dcmis_user_idd']);

        $affected_rows=$this->fasterModel->insertInDB('digital_certification_details',$data);
        if(!empty($affected_rows) && $affected_rows>0){
            return $certificateNumber;
        }
        return false;
    }

    public function getListedInfo()
    {
        extract($_POST);
        $causelistDate = date("Y-m-d", strtotime($causelistDate));
        $data['caseListedInfo']=$this->fasterModel->ListedInfo($courtNo,$causelistDate);
        return view('Faster/sendForFasterResult', $data);
    }

    public function addCaseForFaster()
    {
        $output = array();
        if(isset($_POST['postData']['diaryArr']) && !empty($_POST['postData']['diaryArr']))
        {
            $diaryArr = $_POST['postData']['diaryArr'];
            $inArr = array();
            $actionArr = array();
            $entryCount =0;
            $errorCount =0;
            $existCount =0;
            foreach ($diaryArr as $k=>$v){
                $inArr['diary_no']  = !empty($v['diary_no']) ? (int)$v['diary_no'] : NULL;
                $inArr['conn_key'] = !empty($v['conn_key']) ? (int)$v['conn_key'] : 0;
                $inArr['next_dt']  = !empty($v['next_dt']) ? date('Y-m-d',strtotime($v['next_dt'])) : NULL;
                $inArr['mainhead'] =  !empty($v['mainhead']) ? $v['mainhead'] : NULL;
                $inArr['board_type'] =  !empty($v['board_type']) ? $v['board_type'] : NULL;
                $inArr['roster_id'] = !empty($v['roster_id']) ? (int)$v['roster_id'] : NULL;
                $inArr['main_supp_flag']= !empty($v['main_supp_flag']) ? (int)$v['main_supp_flag'] : NULL;
                $inArr['judges'] =  !empty($v['judges']) ? $v['judges'] : NULL;
                $inArr['user_id'] = !empty($_SESSION['dcmis_user_idd']) ? $_SESSION['dcmis_user_idd'] : NULL;
                $inArr['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $inArr['is_active'] = 1;
                $inArr['deleted_by'] = 0;
                $inArr['deleted_date'] = NULL;
                $inArr['deleted_ip'] = NULL;
                $inArr['court_no']  = !empty($v['courtno']) ? (int)$v['courtno'] : NULL;
                $inArr['item_number'] = !empty($v['brd_slno']) ? (int)$v['brd_slno'] : NULL;
                $diaryNoExistInFasterCases = $this->checkDiaryAndNextDtInTable("faster_cases",$inArr['diary_no'],$inArr['next_dt'] );
                if(empty($diaryNoExistInFasterCases)){
                    $diaryNoExistInFasterOpted = $this->checkDiaryAndNextDtInTable("faster_opted",$inArr['diary_no'],$inArr['next_dt'] );
                    if(empty($diaryNoExistInFasterOpted)){
                       
                        $res  = $this->fasterModel->insertInDBwithInsertedId("faster_opted",$inArr);

                        if(!empty($res)){
                            $entryCount++;
                            $actionArr[] = array("status"=>"success","message"=>"Added","diary_no"=>$inArr['diary_no']);
                        }
                        else{
                            $errorCount++;
                            $actionArr[] = array("status"=>"error","message"=>"","diary_no"=>$inArr['diary_no']);
                        }
                    }
                    else{
                        $updateArr = array();
                        $updateWhere = array();
                        $updateArr['is_active'] = 1;
                        $updateArr['deleted_by'] = 0;
                        $updateArr['deleted_date'] = NULL;
                        $updateArr['deleted_ip'] =  NULL;
                        $updateArr['entry_date'] =  date('Y-m-d H:i:s');
                        $updateWhere['diary_no'] = $inArr['diary_no'];
                        $updateWhere['next_dt'] = $inArr['next_dt'];
                        $res = $this->fasterModel->updateInDB("faster_opted",$updateArr,$updateWhere);
                        if($res >0){
                            $entryCount++;
                            $actionArr[] = array("status"=>"exist","message"=>"Added","diary_no"=>$inArr['diary_no']);
                        }
                        else{
                            $errorCount++;
                            $actionArr[] = array("status"=>"exist","message"=>"Added","diary_no"=>$inArr['diary_no']);
                        }
                    }
                }
                else{
                    $existCount++;
                    $actionArr[] = array("status"=>"exist","message"=>"Added","diary_no"=>$inArr['diary_no']);
                }
            }
            $output = array("status" => "success","message"=>"Success","entryCount"=>$entryCount,"existCount"=>$existCount,"errorCount"=>$errorCount,"actionArr"=>$actionArr);
        }
        else
        {
            $output = array("status" => "error","message"=>"Error");
        }
        echo json_encode($output);
        exit(0);
    }

    public function checkDiaryAndNextDtInTable($table_name,$diary_no,$next_dt){
        $output = true;
        if(isset($diary_no) && !empty($diary_no) && isset($next_dt) && !empty($next_dt) && isset($table_name) && !empty($table_name)){
            $output = $this->fasterModel->existDiaryNextDt($table_name,$diary_no,$next_dt);
        }
        return $output;
    }

    public function modifyCaseForFaster(){
        $output = array();
        if(isset($_POST['postData']['diaryArr']) && !empty($_POST['postData']['diaryArr'])) {
            $diaryArr = $_POST['postData']['diaryArr'];
            $actionArr = array();
            $updateCount = 0;
            $errorCount = 0;
            $existCount = 0;
            $notExist= 0;
            foreach ($diaryArr as $k => $v) {
                $diary_no = !empty($v['diary_no']) ? (int)$v['diary_no'] : NULL;
                $next_dt = !empty($v['next_dt']) ? date('Y-m-d', strtotime($v['next_dt'])) : NULL;
                $diaryNoExistInFasterCases = $this->checkDiaryAndNextDtInTable("faster_cases",$diary_no,$next_dt);
                if(empty($diaryNoExistInFasterCases)){
                    $diaryNoExistInFasterCases = $this->checkDiaryAndNextDtInTable("faster_opted", $diary_no, $next_dt);
                    if(!empty($diaryNoExistInFasterCases)){
                        $updateArr = array();
                        $updateWhere = array();
                        $updateArr['is_active'] = 0;
                        $updateArr['deleted_by'] = !empty($_SESSION['dcmis_user_idd']) ? $_SESSION['dcmis_user_idd'] : NULL;
                        $updateArr['deleted_date'] =date('Y-m-d H:i:s');
                        $updateArr['deleted_ip'] =  $_SERVER['REMOTE_ADDR'];
                        $updateWhere['diary_no'] = $diary_no;
                        $updateWhere['next_dt'] = $next_dt;
                        $res = $this->fasterModel->updateInDB("faster_opted",$updateArr,$updateWhere);
                        if($res >0){
                            $updateCount++;
                            $actionArr[] = array("status"=>"success","message"=>"Modify","diary_no"=>$diary_no);
                        }
                        else{
                            $errorCount++;
                            $actionArr[] = array("status"=>"error","message"=>"","diary_no"=>$diary_no);
                        }
                    }
                    else{
                        $notExist++;
                        $actionArr[] = array("status"=>"error","message"=>"","diary_no"=>$diary_no);
                    }
                }
                else{
                    $existCount++;
                    $actionArr[] = array("status"=>"error","message"=>"","diary_no"=>$diary_no);
                }
            }
            $message ='Success';
            if($errorCount > 0){
                $message ='Error';
            }
            else if($notExist > 0){
                $message ='Add case for faster cases';
            }
            else if($existCount > 0){
                $message ='This case processed for faster cases';
            }
            $output = array("status" => "success","message"=>$message,"updateCount"=>$updateCount,"errorCount"=>$errorCount,"actionArr"=>$actionArr);
        }
        else{
            $output = array("status" => "error","message"=>"Error");
        }
        echo json_encode($output);
        exit(0);
    }
    public function report(){
        $data = array();
        return  view('Faster/reportCaseFaster', $data);
    }
    public function getReport(){
        $output = array();
        if($_POST['causelistDate'] && !empty($_POST['causelistDate'])){
            $causelistDate = !empty($_POST['causelistDate']) ? date('Y-m-d',strtotime($_POST['causelistDate'])) : NULL;
            $courtNo = !empty($_POST['courtNo']) ? (int)trim($_POST['courtNo']) : NULL;
            $output = $this->fasterModel->getFasterReport($causelistDate,$courtNo);
            if(isset($output) && !empty($output)){
                foreach ($output as $k=>&$v){
                    $judgesids = !empty($v['judges']) ? $v['judges'] : NULL;
                    $response = $this->getJudgeName($judgesids);
                    $v['judge_name'] = $response;
                }
            }
        }
        echo json_encode($output);
        exit(0);
    }
    function getJudgeName($judgesid){
        $judgeName ='';
        if(isset($judgesid) && !empty($judgesid)){
            $jarray = explode(',',$judgesid);
            if(count($jarray)>0){
                foreach ($jarray as $v){
                    $jcode = (int)$v;
                    $res =  $this->fasterModel->getJudgeName($jcode);
                    if(isset($res) && !empty($res)){
                        $judgeName .=$res[0]['abbreviation'].', ';
                    }
                }
            }
            $judgeName = rtrim($judgeName,', ');
        }
        return $judgeName;
    }
    public function sendForFaster_OLD($usercode){
        //$usercode = $_SESSION['dcmis_user_idd'];
        $this->getSessionDetail($usercode);
        if(isset($_SESSION['sessionUserEmployeeCode'])){
            return view('Faster/sendForFaster');
        }
        else{
            echo "Please Login, user authentication required. <a href='http://XXXX/supreme_court/' target='_self'>Click Here</a>";
        }
    }

    public function sendForFaster()
    {
        $usercode = session()->get('login')['usercode'];
        if(!empty($usercode))
        {
            return view('Faster/sendForFaster');
        }
        else
        {
            echo "Please Login, user authentication required. <a href='http://XXXX/supreme_court/' target='_self'>Click Here</a>";
        }
    }

    public function getSessionDetail($usercode){
        $userDetails=$this->fasterModel->getUserDetail($usercode);
        if(count($userDetails) > 0){
            $_SESSION['sessionUserName']=$userDetails[0]['name'];
            $_SESSION['sessionUserSection']=$userDetails[0]['section_name'];
            $_SESSION['sessionUserDesignation']=$userDetails[0]['type_name'];
            $_SESSION['sessionUserEmployeeCode']=$userDetails[0]['empid'];
        }
        else{
            echo "Please Login, user authentication required. <a href='http://XXXX/supreme_court/' target='_self'>Click Here</a>";
            exit();
        }
    }

    public function send_to_faster($orderdate){
        $data['app_name']='Faster';
        $orderDate=$this->input->post('orderDate');
          if(isset($orderDate)){
            $orderDate=date("Y-m-d", strtotime($orderDate) );
            $data['orderDateResult'] =$this->fasterModel->orderSendToFaster($orderDate);
            $data['orderDate'] = $orderDate;
       
        }
         
        return  view('Faster/send_to_faster', $data);
    }
    public function startsendtoFasterWithId(){

        $_SESSION['sessionUserEmployeeCode']=$_POST['session_user'];
        $highCourtID = $_POST['highCourtID'];
        $buttonID = $_POST['buttonID'];
        $session_user = $_POST['session_user'];
        $faster_id = $_POST['faster_id'];
        $agency_or_court = $_POST['agency_or_court'];
        $dataid = $_POST['dataid'];
        $data['app_name']='Send to Faster Popup';
        $data['dataid']=$dataid;
        $data['agency_or_court']=1;
        $data['highCourtID']=$highCourtID;
        $data['faster_id']=$faster_id;
        $data['session_user']=$session_user;
        $data['buttonID']=$buttonID;
        //   print_r($data);
        $data['getHighCourt']=$this->fasterModel->getHighCourt($data);
        if($highCourtID!=''){
        $data['updateFasterCases'] = $this->fasterModel->updateSendtoFaster($data);
        }
     
    return  view('Faster/startsendtoFasterWithId', $data);
    }    
}