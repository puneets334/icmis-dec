<?php

namespace App\Controllers\Faster;
use App\Controllers\BaseController;
use App\Models\Court\CourtMasterModel;
use App\Models\FasterModel;
use App\Models\StakeHolder_model;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use UnexpectedValueException;
use ZipArchive;
use Mpdf\Mpdf;
use App\Models\Common\Dropdown_list_model;

class FasterController extends BaseController
{
    protected $CourtMasterModel;
    protected $FasterModel;
    protected $StakeHolder_model;
    public $Dropdown_list_model;

    public function __construct()
    {
        // parent::__construct();
        $this->session = \Config\Services::session();
        $this->session->start();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->FasterModel = new FasterModel();
        $this->StakeHolder_model = new StakeHolder_model();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }

    public function index(){
        $usercode = $_SESSION['login']['usercode'];
        $msg = '';
        $this->session->set('dcmis_user_idd', $usercode);
        $data['msg'] = $msg;
        $data['caseTypes'] = $this->CourtMasterModel->getCaseType();
        $data['usercode'] = $usercode;
        $this->clearFasterSession();
        return view('Faster/caseSearch', $data);
    }

    public function startFasterWithId($diaryNumber,$nextDate=""){
        $this->clearFasterSession();
        $this->goToCurrentStage($diaryNumber,$nextDate);
    }

    public function fasterProcess($usercode,$msg=""){
        $this->session->set_userdata('dcmis_user_idd', $usercode);
        $data['msg']=$msg;
        $data['caseTypes'] = $this->CourtMasterModel->getCaseType();
        $data['usercode'] = $usercode;
        //var_dump($data['caseDetails']);
        return view('Faster/caseSearch', $data);
    }

    public function getFasterCaseDetails()
    {
        extract($_POST);
        $diaryNumberForSearch = null;
        // if ($optradio == 'C') {
        //     $diaryNumberForSearch = $this->FasterModel->getSearchDiary($optradio, $caseType, $caseNo, $caseYear, null, null);
        // } else if ($optradio == 'D') {
        //     $diaryNumberForSearch = $this->FasterModel->getSearchDiary($optradio, null, null, null, $diaryNumber, $diaryYear);
        // }

        if ($optradio == 'C') {
            $diaryNumberForSearch = $this->Dropdown_list_model->get_case_details_by_case_no($caseType, $caseNo, $caseYear);
        } else if ($optradio == 'D') {
            $diary_no = $diaryNumber . $diaryYear;
            $diaryNumberForSearch = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
        }
        // pr($diaryNumberForSearch);
        
        if ($diaryNumberForSearch != null) {
            $userDetails=$this->FasterModel->getUserDetail($_SESSION['dcmis_user_idd']);

            $_SESSION['sessionUserName']=$userDetails[0]['name'];
            $_SESSION['sessionUserSection']=$userDetails[0]['section_name'];
            $_SESSION['sessionUserDesignation']=$userDetails[0]['type_name'];
            $_SESSION['sessionUserEmployeeCode']=$userDetails[0]['empid'];
            return $this->goToCurrentStage($diaryNumberForSearch['diary_no'], $causelistDateSingle);

        } else {
            $data['msg'] = "No record found!!";
            $usercode = $_SESSION['login']['usercode'];
            $this->session->set('dcmis_user_idd', $usercode);
            $data['caseTypes'] = $this->CourtMasterModel->getCaseType();
            $data['usercode'] = $usercode;
            $this->clearFasterSession();
            return view('Faster/caseSearch', $data);
        }
    }

    private function goToCurrentStage($diaryNumberForSearch, $nextDate=""){
        $_SESSION['diaryNumberForSearch']=$diaryNumberForSearch;
        
        if(!empty($nextDate)){
            $_SESSION['nextDate']=convertToYmd($nextDate);
        }
        
        $caseDetails=$this->getCaseDetails($diaryNumberForSearch, $nextDate);        
        
        $data['caseDetails']=$caseDetails;
        $casetype_id=$caseDetails[0]['casetype_id'];
        if(!empty($caseDetails[0]['active_casetype_id'])){
            $casetype_id=$caseDetails[0]['active_casetype_id'];
        }
        $data['noticeTypes']=$this->FasterModel->getNoticeType($caseDetails[0]['nature'],$caseDetails[0]['section_id'],$caseDetails[0]['c_status'],$casetype_id);
        
        if(isset($caseDetails[0]['last_step_id']) && !empty($caseDetails[0]['last_step_id']))
        {
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
            $data['documentsInICMIS']=$this->FasterModel->getAvailableDocumetsInICMIS($diaryNumberForSearch,$_SESSION['nextDate']);
            $data['multiStepFlag']='AddDocuments';
        }
        // pr($data);
        return view('Faster/multiStep', $data);
    }

    public function getFasterCaseDetailsDNo(){
        //getAvailableDocumetsInICMIS
        $diaryNumberForSearch = $_SESSION['diaryNumberForSearch'];
        if(!empty($diaryNumberForSearch)){
            $data['documentsInICMIS']=$this->FasterModel->getAvailableDocumetsInICMIS($diaryNumberForSearch,$_SESSION['nextDate']);
            $caseDetails=$this->getCaseDetails($diaryNumberForSearch,$_SESSION['nextDate']);
            $data['caseDetails']=$caseDetails;
            $casetype_id=$caseDetails[0]['casetype_id'];
            if(!empty($caseDetails[0]['active_casetype_id'])){
                $casetype_id=$caseDetails[0]['active_casetype_id'];
            }
            $data['noticeTypes']=$this->FasterModel->getNoticeType($caseDetails[0]['nature'],$caseDetails[0]['section_id'],$caseDetails[0]['c_status'],$casetype_id);
        }
        else{
            $data['caseDetails']=NULL;
        }

        $data['multiStepFlag']='AddDocuments';
        return view('Faster/multiStep',$data);
    }

    private function getCaseDetails($diaryNo,$nextDate="")
    {
        $caseDetails=$this->FasterModel->caseDetails($diaryNo,$nextDate);
        
        if(count($caseDetails) > 0){
            $_SESSION['caseNumber']="Case No.: ".$caseDetails[0]['reg_no_display']."(".substr($caseDetails[0]['diary_no'], 0, -4)."/".substr($caseDetails[0]['diary_no'], -4).")";
            $_SESSION['causetitle']="Causetitle :".$caseDetails[0]['pet_name']. "Vs. ".$caseDetails[0]['res_name'];
            $_SESSION['main_case_dno']=$caseDetails[0]['conn_key'];
            if(!empty($caseDetails[0]['faster_cases_id'])){
                // $_SESSION['fasterCasesId'] = $caseDetails[0]['faster_cases_id'];
                $this->session->set('fasterCasesId', $caseDetails[0]['faster_cases_id']);
            }
            else{
                // $_SESSION['fasterCasesId'] = NULL;
                $this->session->set('fasterCasesId', NULL);
            }
            return $caseDetails;
        }
        return false;
    }

    public function getFasterDigitalSign(){
        $dataAttached=$this->FasterModel->attachedDocumentByFasterCasesId($_SESSION['fasterCasesId']);
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DIGITAL_SIGNATURE);
        $result=1;
        if($result){
            $data['dataAttached'] = $dataAttached;
            $data['multiStepFlag']='DigitalSign';
            return view('Faster/multiStep',$data);
        }
    }

    public function getFasterDigitalCertification(){
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DIGITAL_CERTIFICATION);
        $result=1;
        if($result){
            $dataAttached=$this->FasterModel->attachedDocumentByFasterCasesId($_SESSION['fasterCasesId']);
            $data['dataAttached'] = $dataAttached;
            $data['multiStepFlag']='DigitalCertification';
            return view('Faster/multiStep',$data);
        }
    }

    public function getFasterDownload(){
        //$result=$this->checkStageBeforeProceed($_SESSION['fasterCasesId'],DOWNLOAD);
        $result=1;
        if($result){
            $data['multiStepFlag']='Download';
            return view('Faster/multiStep',$data);
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
        // echo json_encode($contactDetails);
        echo $output;
        exit(0);
    }

    public function getFasterSendEmail(){
        $data['stakeholderType'] = $this->StakeHolder_model->getStakeHolderType();
        $data['states']=$this->StakeHolder_model->getState();
        $data['multiStepFlag']='sendEmail';
        return view('Faster/multiStep',$data);
    }

    public function getDocumentsDates(){
        // pr($_GET);
        extract($_GET); 
        // echo $docType; // 162. 165, 163
        
        if($_SESSION['main_case_dno'] != $_SESSION['diaryNumberForSearch'] && $_SESSION['main_case_dno'] != null && $_SESSION['main_case_dno'] != 0 && ($docType == 162 || $docType == 163 || $docType == 165)){
            $docDates=$this->FasterModel->documentsDates($_SESSION['main_case_dno'],$docType);
        }
        else{
            $docDates=$this->FasterModel->documentsDates($_SESSION['diaryNumberForSearch'],$docType);
        }       
        //var_dump($docDates);
        $htmlStr="";
        foreach($docDates as $date){
            $htmlStr.="<option value='$date[pdfname]'>$date[orderdate]</option>";
        }
        echo $htmlStr;
    }

    public function showPDF(){
        extract($_GET);
        //pdf_notices/2021/5166/1473709_68_R.pdf
        if($docType==DOCUMENT_MEMO_OF_PARTY){
            $docDates=$this->FasterModel->documentsDates($_SESSION['diaryNumberForSearch'],DOCUMENT_MEMO_OF_PARTY);
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

        $documentDetails = $this->FasterModel->attachedDocumentById($id);
        // pr($documentDetails);

        //$showDigitallyCertifiedFile
        if($showDigitallyCertifiedFile=='true'){
            if($documentDetails[0]['is_digitally_certified']==1){
                $filename = pathinfo($documentDetails[0]['file_name'], PATHINFO_FILENAME);
                $filename = $filename."_Certified.pdf";
                $completeFilePath = WEB_ROOT."/jud_ord_html_pdf".$documentDetails[0]['file_path'].$filename;
                // $completeFilePath = WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$filename;
            }
        }
        else if($showDigitallySignedFile=='true' && $documentDetails[0]['is_digitally_signed']==1 && !in_array($documentDetails[0]['tw_notice_id'],array(DOCUMENT_ROP,DOCUMENT_JUDGMENT,DOCUMENT_SIGNED_ORDER,DOCUMENT_MEMO_OF_PARTY))){
            $filename = pathinfo($documentDetails[0]['file_name'], PATHINFO_FILENAME);
            $filename=$filename."_Signed.pdf";
            $completeFilePath = WEB_ROOT."/jud_ord_html_pdf".$documentDetails[0]['file_path'].$filename;
            //$completeFilePath = WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$filename;
        }
        else{
            $completeFilePath = WEB_ROOT."/jud_ord_html_pdf".$documentDetails[0]['file_path'].$documentDetails[0]['file_name'];
            //$completeFilePath=WEB_ROOT."/supreme_court/".$documentDetails[0]['file_path'].$documentDetails[0]['file_name'];
        }
        // pr($completeFilePath);

        // $headers = @get_headers($completeFilePath);
        // if($headers && strpos( $headers[0], '200')) {
        if($completeFilePath){
            $document_date_check = "";
            if($documentDetails[0]['dated'] == NULL){
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
        $documentsDetails=$this->FasterModel->attachedDocumentById($id);
        if(count($documentsDetails)>0){
            //set is_deleted in faster_shared_document_details
            $fileToUnlink = "/home/reports/".$documentsDetails[0]['file_path'].$documentsDetails[0]['file_name'];
            $dataToUpdate=array('is_deleted'=>1,'deleted_on'=>date('Y-m-d H:s:i'));
            $conditions=array('id'=>$id,'is_deleted'=>0);
            $deletedRows=$this->FasterModel->updateInDB('faster_shared_document_details',$dataToUpdate,$conditions);
            if($deletedRows>0){
                $fastreTransactionData=array('ref_faster_steps_id'=>DELETE_ATTACHED_FILE,'faster_cases_id'=>$_SESSION['fasterCasesId'],'faster_shared_document_details_id'=>$id,'created_by'=>$usercode,'created_by_ip'=>get_client_ip());
                $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);
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

        $fasterCaseDetails=$this->FasterModel->fasterCases($_SESSION['diaryNumberForSearch'],$_SESSION['nextDate'],'P');

        if(count($fasterCaseDetails)>0){
            $fasterCasesId=$fasterCaseDetails[0]['id'];
        }
        else{
            $dataFasterCases=array('diary_no'=>$_SESSION['diaryNumberForSearch'],'next_dt'=>$_SESSION['nextDate'],'created_by'=>$usercode,'last_step_id'=>ADD_DOCUMENTS);
            $fasterCasesId=$this->FasterModel->insertInDBwithInsertedId('faster_cases',$dataFasterCases);
        }

        $_SESSION['fasterCasesId']=$fasterCasesId;
        $sharedDocumentDetails=$this->FasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch'],$fasterCasesId,$docType,$process_id,$dateDetails[0]);
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
            $sharedDocumentId=$this->FasterModel->insertInDBwithInsertedId('faster_shared_document_details',$sharedDocumentData);

            $fastreTransactionData=array('ref_faster_steps_id'=>ADD_DOCUMENTS,'faster_cases_id'=>$fasterCasesId,'faster_shared_document_details_id'=>$sharedDocumentId,'created_by'=>$usercode,'created_by_ip'=>get_client_ip());
            $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);
            $data['message']['code']="SUCCESS";
            $data['message']['msg']="Document attached successfully!";
        }
        echo json_encode($data);
    }

    public function getSharedDocuments(){
        /*extract($_POST);
        if(!empty($fasterCasesId)){
            $data['sharedDocuments']=$this->FasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch']);
        }*/

        $sharedDocuments=$this->FasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch'],"","","",$_SESSION['nextDate']);
        echo json_encode($sharedDocuments);
        return $sharedDocuments;
    }

    public function getTransactions(){
        extract($_GET);
        $transactions=$this->FasterModel->transationList($_SESSION['fasterCasesId'],$step);
        echo json_encode($transactions);
    }

    public function getDigitalSignInput(){
        extract($_POST);
        $data['faster_case_id'] = $faster_case_id;
        $data['file_path'] = $file_path;
        $data['faster_shared_doc_id'] = $faster_shared_doc_id;
        return view('Faster/get_token_certificates', $data);
    }

    public function getDigitalCertificateInput(){
        extract($_POST);
        $data['faster_case_id'] = $faster_case_id;
        $data['file_path'] = $file_path;
        $data['faster_shared_doc_id'] = $faster_shared_doc_id;
        return view('Faster/get_token_certification_token', $data);
    }

    public function setTokenTask(){
       $this->getSessionDetail($_SESSION['dcmis_user_idd']);
        date_default_timezone_set("Asia/Kolkata");
            $doc_id = $_POST['doc_id'];
            $documentDetails=$this->FasterModel->attachedDocumentById($doc_id);
            // supremecourt/faster_assets/2019/43/1/43_2019_Order_09-Apr-2019.pdf
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

            $source_url = $json_result[1]->{'signed_file'};
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
                    $this->db->transBegin();
                    $fastreTransactionData=array('ref_faster_steps_id'=>DIGITAL_SIGNATURE,'faster_cases_id'=>$_POST['faster_case_id'],'faster_shared_document_details_id'=>$_POST['doc_id'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
                    $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);

                    $dataToUpdate=array('last_step_id'=>DIGITAL_SIGNATURE);
                    $conditions=array('id'=>$_POST['faster_case_id'],'is_deleted'=>0);

                    $updateRows=$this->FasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

                    $dataToUpdateInDocuments=array('is_digitally_signed'=>1,'digitally_signed_on'=>date('Y-m-d H:i:s'));
                    $conditionsInDocuments=array('id'=>$_POST['doc_id'],'is_deleted'=>0);
                    $updateRowsInDocuments=$this->FasterModel->updateInDB('faster_shared_document_details',$dataToUpdateInDocuments,$conditionsInDocuments);
                    $this->db->transCommit();
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
            $documentDetails=$this->FasterModel->attachedDocumentById($doc_id);

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
                'image_url'=>WEB_ROOT.'/asterController/generateImage/'.$certificateNumber, //TODO::Change icmis to supreme_court when going live
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
            $source_url = $json_result[1]->{'signed_files'};
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
                    $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);

                    $dataToUpdate=array('last_step_id'=>DIGITAL_CERTIFICATION);
                    $conditions=array('id'=>$_POST['faster_case_id'],'is_deleted'=>0);

                    $updateRows=$this->FasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

                    $dataToUpdateInDocuments=array('is_digitally_certified'=>1,'digitally_certified_on'=>date('Y-m-d H:i:s'));
                    $conditionsInDocuments=array('id'=>$_POST['doc_id'],'is_deleted'=>0);
                    $updateRowsInDocuments=$this->FasterModel->updateInDB('faster_shared_document_details',$dataToUpdateInDocuments,$conditionsInDocuments);
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

    private function rrmdir($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir); 
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
            } 
          } 
          reset($objects); 
          rmdir($dir); 
        } 
    }

    public function downloadAll(){
        extract($_SESSION);
        $sharedDocuments=$this->FasterModel->fasterSharedDocuments(0,$fasterCasesId);
        // pr($sharedDocuments);
        // $folderLocation=FILE_ROOT_PATH.$sharedDocuments[0]['file_path'];
        $folderLocation=getBasePath().'reports'.$sharedDocuments[0]['file_path'];
        // $folderLocationaa='/var/www/html/public/reports'.$sharedDocuments[0]['file_path'];
        $zipFileDirectory=$folderLocation."complete_file/";
        
        $pathDir = getBasePath().'reports';

        // pr($zipFileDirectory);

        if(is_dir($zipFileDirectory)){
            exec("chmod -R 0777 ".$pathDir);
            array_map('unlink', glob("$zipFileDirectory/*.*"));
            // $this->rrmdir($zipFileDirectory);
            rmdir($zipFileDirectory);
        }
        else{
            mkdir($zipFileDirectory, 0777, true);
        }
        exec("chmod -R 0777 ".$pathDir);
        // pr($folderLocation);

        foreach($sharedDocuments as $index=>$document){
            $fileNameArray  = explode(".",$document['file_name']);
            $fileName       = $fileNameArray[0]."_Certified.pdf";
            $fileToAttach   = $folderLocation.$fileName;
            copy($fileToAttach, $zipFileDirectory.$fileName);
        }
        exec("chmod -R 0777 ".$zipFileDirectory);

        // pr('Hello');
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
        $this->rrmdir($zipFileDirectory);


        // $this->db->trans_start();
        // $this->db->transBegin();
        $fastreTransactionData=array('ref_faster_steps_id'=>DOWNLOAD,'faster_cases_id'=>$_SESSION['fasterCasesId'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
        $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);

        $dataToUpdate = array('last_step_id'=>DOWNLOAD);
        $conditions = array('id'=>$_SESSION['fasterCasesId'],'is_deleted'=>0);

        $updateRows = $this->FasterModel->updateInDB('faster_cases',$dataToUpdate,$conditions);

        // $this->db->trans_complete();
        // $this->db->transCommit();





        /*$fastreTransactionData=array('ref_faster_steps_id'=>DOWNLOAD,'faster_cases_id'=>$_SESSION['fasterCasesId'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
        $this->FasterModel->insertInDB('faster_transactions',$fastreTransactionData);*/


        force_download($name, $data);
    }

    private function delete_directory($folderName)
    {
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
        $details = $this->FasterModel->getCurrentStage($fasterCasesId);
        if (count($details) > 0) {
            $current_stage = $details[0]['last_step_id'];
            if (($expectedStage <= $current_stage)) {

            } else {
                $details=$this->FasterModel->fasterSharedDocuments($_SESSION['diaryNumberForSearch']);
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
                            $isDigitallySigned=NULL;
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
                            $isDigitallySigned=NULL;
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

    public function addEmailId()
    {
        extract($_POST);
        if(!empty($_SESSION['fasterCasesId'])){
            $details = $this->StakeHolder_model->getDataById($stakeholderDetails);
            $data = array('faster_cases_id'=>$_SESSION['fasterCasesId'],'stakeholder_details_id'=>$stakeholderDetails,'email_id'=>$details[0]['jcn_email_id'],'mobile_number'=>$details[0]['mobile_number'],'created_by'=>$_SESSION['dcmis_user_idd'],'created_by_ip'=>get_client_ip());
            $insertedRows=$this->FasterModel->insertInDB('faster_communication_details',$data);
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
        $details=$this->FasterModel->recipientDetails($_SESSION['fasterCasesId']);
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
            $deletedRows=$this->FasterModel->updateInDB('faster_communication_details',$dataToUpdate,$conditions);
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
        $markedCases=$this->FasterModel->casesMarkedForFaster($causelistDate);
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
        $certificateNumber= $this->FasterModel->getNextCertificateNumber();
		
        $data=array('certificate_number'=>$certificateNumber,'certificate_year'=>date('Y'),'faster_cases_id'=>$_SESSION['fasterCasesId'],
            'faster_shared_document_details_id'=>$fasterSharedDocumentDetailsId,'created_by'=>$_SESSION['dcmis_user_idd']);

        $affected_rows=$this->FasterModel->insertInDB('digital_certification_details',$data);
        if(!empty($affected_rows) && $affected_rows>0){
            return $certificateNumber;
        }
        return false;
    }

    public function getListedInfo()
    {
        extract($_POST);
        $causelistDate = date("Y-m-d", strtotime($causelistDate));
        $data['caseListedInfo']=$this->FasterModel->ListedInfo($courtNo,$causelistDate);
        return view('Faster/sendForFaster', $data);
    }

    public function addCaseForFaster(){
        $output = array();
        if(isset($_POST['diaryArr']) && !empty($_POST['diaryArr'])){
            $diaryArr = $_POST['diaryArr'];
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
                        $res  = $this->FasterModel->insertInDBwithInsertedId("faster_opted",$inArr);
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
                        $res = $this->FasterModel->updateInDB("faster_opted",$updateArr,$updateWhere);
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
        else{
            $output = array("status" => "error","message"=>"Error");
        }
        echo json_encode($output);
        exit(0);
    }

    public function checkDiaryAndNextDtInTable($table_name,$diary_no,$next_dt){
        $output = true;
        if(isset($diary_no) && !empty($diary_no) && isset($next_dt) && !empty($next_dt) && isset($table_name) && !empty($table_name)){
            $output = $this->FasterModel->existDiaryNextDt($table_name,$diary_no,$next_dt);
        }
        return $output;
    }

    public function modifyCaseForFaster(){
        $output = array();
        if(isset($_POST['diaryArr']) && !empty($_POST['diaryArr'])) {
            $diaryArr = $_POST['diaryArr'];
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
                        $res = $this->FasterModel->updateInDB("faster_opted",$updateArr,$updateWhere);
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
        return view('Faster/reportCaseFaster', $data);
    }

    public function getReport(){
        $output = array();
        if($_POST['causelistDate'] && !empty($_POST['causelistDate'])){
            $causelistDate = !empty($_POST['causelistDate']) ? date('Y-m-d',strtotime($_POST['causelistDate'])) : NULL;
            $courtNo = !empty($_POST['courtNo']) ? (int)trim($_POST['courtNo']) : NULL;
            $output = $this->FasterModel->getFasterReport($causelistDate,$courtNo);
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
                    $res =  $this->FasterModel->getJudgeName($jcode);
                    if(isset($res) && !empty($res)){
                        $judgeName .=$res[0]['abbreviation'].', ';
                    }
                }
            }
            $judgeName = rtrim($judgeName,', ');
        }
        return $judgeName;
    }

    public function sendForFaster($usercode){
        //$usercode = $_SESSION['dcmis_user_idd'];
        $this->getSessionDetail($usercode);
        if(isset($_SESSION['sessionUserEmployeeCode'])){
            return view('Faster/sendForFaster');
        }
        else{
            echo "Please Login, user authentication required. <a href='http://XXXX/supreme_court/' target='_self'>Click Here</a>";
        }
    }

    public function getSessionDetail($usercode){
        $userDetails=$this->FasterModel->getUserDetail($usercode);
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

    public function send_to_faster(){
        $data['app_name']='Faster';
        $orderDate= isset($_POST['orderDate']) ? $_POST['orderDate'] : '';
        if(isset($orderDate) && !empty($orderDate)){
            $orderDate=date("Y-m-d", strtotime($orderDate) );
            $data['orderDateResult'] =$this->FasterModel->orderSendToFaster($orderDate);
            $data['orderDate'] = $orderDate;            
        }
        // pr($data);
        return view('Faster/send_to_faster', $data);
    }

    public function get_send_to_faster(){
        $orderDate= isset($_GET['orderDate']) ? $_GET['orderDate'] : '';
        if(isset($orderDate) && !empty($orderDate)){
            $orderDate=date("Y-m-d", strtotime($orderDate) );
            $data['orderDateResult'] =$this->FasterModel->orderSendToFaster($orderDate);
            $data['orderDate'] = $orderDate;
            return view('Faster/get_sent_to_faster', $data);
        }
        
    }

    public function startsendtoFasterWithId(){

        $_SESSION['sessionUserEmployeeCode']=$_POST['session_user'];
        $highCourtID = isset($_POST['highCourtID']) ? $_POST['highCourtID'] : '';
        $buttonID = isset($_POST['buttonID']) ? $_POST['buttonID'] : '';
        $session_user = $ucode = session()->get('login')['usercode'];
        $faster_id = isset($_POST['faster_id']) ? $_POST['faster_id'] : '';
        // $agency_or_court = $_POST['agency_or_court'];
        $dataid = isset($_POST['dataid']) ? $_POST['dataid'] : '';
        $data['app_name']='Send to Faster Popup';
        $data['dataid']=$dataid;
        $data['agency_or_court']=1;
        $data['highCourtID']=$highCourtID;
        $data['faster_id']=$faster_id;
        $data['session_user']=$session_user;
        $data['buttonID']=$buttonID;
        //   print_r($data);
        $data['getHighCourt']=$this->FasterModel->getHighCourt($data);
        if($highCourtID!=''){
            $data['updateFasterCases'] = $this->FasterModel->updateSendtoFaster($data);
        }

        return view('Faster/startsendtoFasterWithId', $data);
    }

    public function generateMemoOfUser(){
        return view('Faster/generateMemoOfUser');
    }
    
    public function get_cause_title_request()
    {
        // pr($_REQUEST);
        $renderer = service('renderer');
        $data = [];
        if (!empty($_REQUEST['optradio'] == 'C')) {
            // $diaryNumberForSearch = $this->Dropdown_list_model->get_case_details_by_case_no($caseType, $caseNo, $caseYear);
            $data = $this->Dropdown_list_model->get_case_details_by_case_no($_REQUEST['ct'], $_REQUEST['cn'], $_REQUEST['cy']);
            if($data){
                $_REQUEST['d_no'] = $data['dn'];
                $_REQUEST['d_yr'] = $data['dy'];
            }
            else {
                echo $msg_404 = '404';
                exit;
            }
        }
        else if (!empty($_REQUEST['optradio'] == 'D')) {
            $dno = $_REQUEST["d_no"];
            $dyr = $_REQUEST["d_yr"];
            $diary_no = $dno . $dyr;
        }
        else{
            echo $msg_404 = '404';
            exit;
        }

        return view('Faster/get_cause_title_request');
    }
    
    public function get_cause_title_request_save(){
        // pr($_POST['ct']);
        if (!empty($_POST['optradio'] == 'C')) {
            $data = $this->Dropdown_list_model->get_case_details_by_case_no($_POST['ct'], $_POST['cn'], $_POST['cy']);
            if($data){
                $_POST['d_no'] = $data['dn'];
                $_POST['d_yr'] = $data['dy'];
            }
        }
        $pdfContent = $_POST['pdfcontent'] ?? '';
        $dNo = $_POST['d_no'] ?? '';
        $dYr = $_POST['d_yr'] ?? '';
        $createdBy = $_SESSION['login']['usercode'];
        $ip = $this->request->getIPAddress();

        $postDiary = $dNo . $dYr;
        $date = date('Y_m_d');
        $createdOn = date('Y-m-d H:i:s');
        $year = substr($postDiary, -4);
        $diaryNo = substr($postDiary, 0, strlen($dNo) - 4);

        // $fileFolder = "supremecourt/party_details/$year/$diaryNo/";
        $pdfFile = $date."_".$postDiary.".pdf";
        // $pathDir = "/home/reports/$fileFolder";
        // pr(FCPATH);
        $fileDir = FASTER_STORAGE_PARTY."$year/$diaryNo/";
        // $fileDir = "/reports/$fileFolder";
        $pathDir = getBasePath().$fileDir;
        exec("chmod -R 0777 ".$pathDir);
        // pr($pathDir);
        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0777, true);
        }

        exec("chmod -R 0777 ".$pathDir);

        // Save HTML content to a file
        $dataFile = $pathDir.$date."_".$postDiary.".html";
        if (file_exists($dataFile)) {
            unlink($dataFile);
        }
        file_put_contents($dataFile, $pdfContent);
        // pr($dataFile);
        // Generate PDF
        // include '/var/www/html/supreme_court/MPDF60/mpdf.php';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($pdfContent);
        $pdfFilePath = $pathDir . $pdfFile;
        $pdfFileUrl = $fileDir . $pdfFile;
        $mpdf->Output($pdfFilePath, 'F');
        // pr($mpdf);
        // Direct database connection
        $db = \Config\Database::connect();

        // Check for existing cause title
        $existingCauseTitle = $db->table('cause_title')
            ->where('diary_no', $postDiary)
            ->where('is_active', 1)
            ->get()
            ->getRow();

        if ($existingCauseTitle) {
            // Deactivate the existing cause title
            $db->table('cause_title')->update([
                'is_active' => 0,
                'updated_on' => $createdOn,
                'updated_by' => $createdBy,
                'updated_ip' => $ip,
            ], ['cause_title_id' => $existingCauseTitle->cause_title_id]);
        }

        // Insert new cause title
        $newData = [
            'diary_no' => $postDiary,
            // 'path' => $pdfFilePath,
            'path' => $pdfFileUrl,
            'created_on' => $createdOn ? $createdOn : null,
            'created_by' => $createdBy ? $createdBy : null,
            'created_ip' => $ip,
            'is_active' => 1,
        ];

        if ($db->table('cause_title')->insert($newData)) {
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Cause Title PDF uploaded successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            // session()->setFlashdata('success', "Cause Title PDF uploaded successfully.");
            // return redirect()->to(base_url()."/Faster/FasterController/generateMemoOfUser?diaryno=$postDiary&statusCheck=1")
                            //  ->with('message', 'Cause Title PDF uploaded successfully.');
            
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: Something went wrong.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            // session()->setFlashdata('fail', "Error: Something went wrong.");
            // return redirect()->to(base_url()."/Faster/FasterController/generateMemoOfUser?diaryno=$postDiary&statusCheck=0")
                            //  ->with('error', 'Error: Something went wrong.');            
        }
        return $message;
    }

    public function generate_notice(){
        return view('Faster/generate_notice');
    }
    
    public function get_notice()
    {
        $chk_status = $this->request->getGet('chk_status');
        
        $d_no = $this->request->getGet('d_no');
        $d_yr = $this->request->getGet('d_yr');

        if ($chk_status == 1) {
            $ct = $this->request->getGet('ct');
            $cn = $this->request->getGet('cn');
            $cy = $this->request->getGet('cy');
            $data['diary_no'] = $this->Dropdown_list_model->get_case_details_by_case_no($ct, $cn, $cy)['diary_no'];
        } else {
            
            $data['diary_no'] = $d_no . $d_yr;
        }

        return view('Faster/get_notice', $data);
    }
    
    public function add_additional_data(){
        $data['chk_status'] = $this->request->getPost('chk_status');
        $data['ct'] = $this->request->getPost('ct');
        $data['cn'] = $this->request->getPost('cn');
        $data['cy'] = $this->request->getPost('cy');
        $data['d_no'] = $this->request->getPost('d_no');
        $data['d_yr'] = $this->request->getPost('d_yr');

        return view('Faster/add_additional_data', $data);
    }
    
    public function get_dynamic_cst(){
        $hd_Sendcopyto_o= $_REQUEST['hd_Sendcopyto_o'];
        $o_r_h = $_REQUEST['o_r_h'];
        $mode = '';
        // pr($_REQUEST);
        $html = "<div style='margin-top: 10px'>
            <select name='".$_REQUEST['ddl_send_copy_typeo_id']."_".$hd_Sendcopyto_o."' class='form-control' 
                    id='".$_REQUEST['ddl_send_copy_typeo_id']."_".$hd_Sendcopyto_o."' onchange=\"get_send_to_type(this.id, this.value, '3', '".$mode."')\">".$_REQUEST['ddl_send_copy_typeo__html']."
            </select>
            <select name='".$_REQUEST['ddlSendCopyTo_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
                    id='".$_REQUEST['ddlSendCopyTo_o_id']."_".$hd_Sendcopyto_o."' 
                    onfocus='clear_data(this.id)' style='width: 130px;'>".$_REQUEST['ddlSendCopyTo_o_html']."</select>
            <select name='".$_REQUEST['ddl_cpsndto_state_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
                    id='".$_REQUEST['ddl_cpsndto_state_o_id']."_".$hd_Sendcopyto_o."' 
                    style='width: 100px' onchange=\"getCity(this.value, this.id, '3', '".$o_r_h."')\">".$_REQUEST['ddl_cpsndto_state_o_html']."</select>
            <select name='".$_REQUEST['ddl_cpsndto_dst_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
                    id='".$_REQUEST['ddl_cpsndto_dst_o_id']."_".$hd_Sendcopyto_o."' 
                    style='width: 100px'>".$_REQUEST['ddl_cpsndto_dst_o_html']."</select>
        </div>";

        // $html = "<div style='margin-top: 10px'>
        //     <select name='".$_REQUEST['ddl_send_copy_typeo_id']."_".$hd_Sendcopyto_o."' class='form-control' 
        //             id='".$_REQUEST['ddl_send_copy_typeo_id']."_".$hd_Sendcopyto_o."' onchange=\"get_send_to_type(this.id, this.value, '3', '".$mode."')\"></select>
        //     <select name='".$_REQUEST['ddlSendCopyTo_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
        //             id='".$_REQUEST['ddlSendCopyTo_o_id']."_".$hd_Sendcopyto_o."' 
        //             onfocus='clear_data(this.id)' style='width: 130px;'></select>
        //     <select name='".$_REQUEST['ddl_cpsndto_state_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
        //             id='".$_REQUEST['ddl_cpsndto_state_o_id']."_".$hd_Sendcopyto_o."' 
        //             style='width: 100px' onchange=\"getCity(this.value, this.id, '3', '".$o_r_h."')\"></select>
        //     <select name='".$_REQUEST['ddl_cpsndto_dst_o_id']."_".$hd_Sendcopyto_o."' class='form-control' 
        //             id='".$_REQUEST['ddl_cpsndto_dst_o_id']."_".$hd_Sendcopyto_o."' 
        //             style='width: 100px'></select>
        // </div>";
        return $html;
    }

    public function get_send_to_type(){
        $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $db = \Config\Database::connect();
        $builder = $db->table('master.tw_send_to');
        $flag = 0;

        if ($_REQUEST['id_val'] == 2) {
            $flag = 1;
            $query = $builder->select('id, desg')
                            ->where('display', 'Y')
                            ->get();
        } else if ($_REQUEST['id_val'] == 1) {
            $flag = 1;
            $query = $db->table('advocate a')
                                ->select("advocate_id as id, concat(name, '-', aor_code) as desg")
                                ->join('master.bar b', 'a.advocate_id = b.bar_id', 'left')
                                ->where('a.display', 'Y')
                                ->where('diary_no', $dairy_no)
                                ->orderBy('pet_res')
                                ->get();
        } else if ($_REQUEST['id_val'] == 3) {
            $flag = 1;
            $additional_diary = '';
            $casetype_id = $db->table('main')
                                    ->select('active_casetype_id')
                                    ->where('diary_no', $dairy_no)
                                    ->get()
                                    ->getRow();

            $res_casetype_id = $casetype_id->active_casetype_id;

            if (in_array($res_casetype_id, ['9', '10', '25', '26'])) {
                $r_c_lct = $db->table('lowerct')
                                    ->select('lct_casetype, lct_caseno, lct_caseyear')
                                    ->where('diary_no', $dairy_no)
                                    ->where('ct_code', '4')
                                    ->where('lw_display', 'Y')
                                    ->whereNotIn('lct_casetype', ['9', '10', '25', '26'])
                                    ->get();

                if ($r_c_lct->getNumRows() > 0) {
                    $add_diary = '';
                    foreach ($r_c_lct->getResultArray() as $row1) {
                        $get_diary_case_type = get_diary_case_type($row1['lct_casetype'], $row1['lct_caseno'], $row1['lct_caseyear']);
                        $add_diary = $add_diary ? $add_diary . ',' . $get_diary_case_type : $get_diary_case_type;
                    }
                    $additional_diary = " or diary_no in ($add_diary)";
                }
            }

            $is_order_challenged = '';
            if ($res_casetype_id != '7' && $res_casetype_id != '8') {
                $is_order_challenged = " and is_order_challenged = 'Y'";
            }

            $lct_judge_desg = '';
            if ($res_casetype_id == '7' || $res_casetype_id == '8') {
                $lct_judge_desg = ", lct_judge_desg";
            }

            $query = $db->table('lowerct a')
                ->select("DISTINCT MIN(lower_court_id) as id, 
                            CONCAT(
                                CASE 
                                    WHEN ct_code = 3 THEN 
                                        (SELECT name FROM master.state s WHERE s.id_no = a.l_dist AND display = 'Y')
                                    ELSE 
                                        (SELECT agency_name FROM master.ref_agency_code c WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND is_deleted = 'f')
                                END,
                                ' ', b.Name
                            ) as desg $lct_judge_desg, ct_code")
                ->join('master.state b', 'a.l_state = b.id_no')
                ->where('diary_no', $dairy_no)
                ->where('lw_display', 'Y')
                ->where('b.display', 'Y')
                ->groupBy("l_state, l_dist, ct_code, b.name $lct_judge_desg")
                ->get();

        }
        $html = '';
        // Fetching results
        if($flag){
            foreach ($query->getResultArray() as $row) {
                $lct_judge_desg_s = '';
                if (isset($row['lct_judge_desg']) && $row['lct_judge_desg'] != '0') {
                    $get_lower_court_judge = get_lower_court_judge($row['lct_judge_desg']);
                    $lct_judge_desg_s = $get_lower_court_judge;
                }
                $html .= '<option value="'.$row['id'].'">'.$lct_judge_desg_s . $row['desg'].'</option>';
            }
        }
        return $html;
    }

    public function getCityName(){
        $html = '';
        $db = \Config\Database::connect();
        if ($_REQUEST['str'] == '0' || empty($_REQUEST['str'])) {
            $html .= '<option value="0">None</option>';
        } else {
            $state_code_query = $db->table('master.state')
                ->select('state_code')
                ->where('id_no', $_REQUEST['str'])
                ->where('display', 'Y')
                ->get()
                ->getRow();
            if ($state_code_query) {
                $state_code = $state_code_query->state_code;
                $builder = $db->table('master.state');
                $query = $builder->select('id_no, name')
                        ->where('state_code', $state_code)
                        ->where('sub_dist_code', '0')
                        ->where('district_code !=', '0')
                        ->where('village_code', '0')
                        ->where('display', 'Y')
                        ->orderBy('name')
                        ->get();
                foreach ($query->getResultArray() as $row) {
                    $html .= '<option value="' . $row['id_no'] . '">' . $row['name'] . '</option>';
                }
            }
        }
        return $html;
    }

    public function add_additional_data_hc(){
        // $data['chk_status'] = $this->request->getPost('chk_status');
        // $data['ct'] = $this->request->getPost('ct');
        // $data['cn'] = $this->request->getPost('cn');
        // $data['cy'] = $this->request->getPost('cy');
        // $data['d_no'] = $this->request->getPost('d_no');
        // $data['d_yr'] = $this->request->getPost('d_yr');

        $chk_status = $this->request->getGet('chk_status');
        $ct = $this->request->getGet('ct');
        $cn = $this->request->getGet('cn');
        $cy = $this->request->getGet('cy');
        $d_no = $this->request->getGet('d_no');
        $d_yr = $this->request->getGet('d_yr');

        if ($chk_status == 1) {
            $data['diary_no'] = $this->Dropdown_list_model->get_case_details_by_case_no($ct, $cn, $cy)['diary_no'];
        } else {
            
            $data['diary_no'] = $d_no . $d_yr;
        }

        return view('Faster/add_additional_data_hc', $data);
    }
    
    public function save_talwana(){
        return view('Faster/save_talwana');
    }

    public function get_records(){
        return view('Faster/get_records');
    }
    
    public function get_ck_mul_rem(){
        $db = \Config\Database::connect();
        $dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $date = date('Y-m-d');

        // Fetch the latest remarks
        $builder = $db->table('case_remarks_multiple');
        $builder->select('cl_date, r_head');
        $builder->where('diary_no', $dairy_no);
        $builder->whereIn('r_head', [
            '90', '91', '9', '10', '117', '62', '11',
            '60', '74', '75', '65', '2', '1', '94',
            '3', '4', '96', '57', '93', '59'
        ]);
        $builder->where('cl_date', function($subquery) use ($dairy_no) {
            $subquery->select('MAX(cl_date)')
                    ->from('case_remarks_multiple')
                    ->where('diary_no', $dairy_no);
        });

        // Execute the query
        $query = $builder->get();

        foreach ($query->getResultArray() as $res) {
            // Check if record already exists
            $countBuilder = $db->table('tw_not_pen_sta');
            $countBuilder->selectCount('id');
            $countBuilder->where('diary_no', $dairy_no);
            $countBuilder->where('ck_cl_dt', $res['cl_date']);
            $countBuilder->where('ck_hd', $res['r_head']);
            
            $countQuery = $countBuilder->get();
            $res_sq_sql_ins = $countQuery->getFirstRow()->id;

            if ($res_sq_sql_ins <= 0) {
                // Insert the new record
                $insertData = [
                    'diary_no' => $dairy_no,
                    'ck_rec_dt' => $date,
                    'ck_cl_dt' => $res['cl_date'],
                    'ck_hd' => $res['r_head'],
                ];
                $db->table('tw_not_pen_sta')->insert($insertData);
            }
        }
    }
}
