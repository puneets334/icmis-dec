<?php

namespace App\Controllers\RI;

use App\Controllers\BaseController;
use App\Models\RI\RIModel;

use App\Libraries\Common;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_lc_hc_casetype;
use App\Models\Entities\Model_state;

class DispatchController extends BaseController
{
    public $Dropdown_list_model;
    public $Model_lc_hc_casetype;
    public $Model_state;

    public $RIModel;
    protected $Common;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        $request = \Config\Services::request();
        $this->RIModel = new RIModel();
        $this->Common = new Common();


        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_lc_hc_casetype = new Model_lc_hc_casetype();
        $this->Model_state = new Model_state();
    }
    ####menu1
    public function showCreateLetterGroup()
    {

        return view('RI/Dispatch/showCreateLetterGroup');
    }
    ####menu1
    public function searchMainLetter()
    {
        $processId = $_POST['processId'];
        $processYear = $_POST['processYear'];
        $result = $this->RIModel->getProcessIdDetails($processId, $processYear);

        if (!empty($result)) { //print_r($result);
            $processIdDetails['processIdDetails'] = $result;
            return view('RI/Dispatch/dataSearchMainLetter', $processIdDetails);
        } else {
            return   '<center><h4 style="color:red;">No Data Found.</h4></center>';
        }
    }
    public function goToNextPage()
    {
        $selectedCase = $_POST['selectedCase'];
        echo "Latter" . $selectedCase;
        $resultLetter = $this->RIModel->getMainLetterDetails($selectedCase);
        //print_r($resultLetter);
        if (!empty($resultLetter)) {
            $data['mainLetterDetail'] = $resultLetter;
            //print_r($data);
            return   view('RI/Dispatch/connectLetters', $data);
        } else {
            echo  '<center><h4 style="color:red;">No Data Found.</h4></center>';
        }


        //return view('RI/dispatch/connectLetters',$data);
    }

    public function searchConnectedLetter()
    {
        //        var_dump($_POST);
        //        die;
        $processIdConnected = $_POST['pid'];
        $processYearConnected = $_POST['pyr'];
        $mainLetterId = $_POST['mainLetterId'];
        $usercode = $_SESSION['login']['usercode'];
        $processIdDetails = $this->RIModel->getProcessIdDetails($processIdConnected, $processYearConnected);
        //        echo "<pre>";
        //        print_r(gettype($processIdDetails));
        //        die;
        if ($processIdDetails) {
            //            var_dump($processIdDetails);
            //            die;
            $i = 0;
            $exit = 0;
            foreach ($processIdDetails as $details) {
                $dataToConnect = $this->RIModel->getMainLetterDetails($details['ec_postal_dispatch_id']);
                //                echo "<pre>";
                //                print_r($dataToConnect);
                //                die;
                echo "YYY";
                if (empty($dataToConnect[0]['connected_id'])) {
                    $this->RIModel->addConnectedLetter($details['ec_postal_dispatch_id'], $mainLetterId, $usercode);
                    $i++;
                } else {
                    echo "<span class='text-danger'>Letter Already Added!</span>";
                    $exit = 1;
                }
            }
            if ($exit == 0) {
                if ($i != 0) {
                    echo "<span class='text-success'>$i letters added to group Successfully!</span>";
                } else {
                    echo "<span class='text-danger'>Nothing added!</span>";
                }
            }
        } else {
            echo "<span class='text-danger'>Letter Not available to dispatch!</span>";
        }
    }
    //////////////menu 2
    public function dispatchDakFromRI()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $data = [];


        $section = $this->RIModel->getSection();
        if (!empty($section)) {
            $data['dealingSections'] = $section;
        }
        $casetype = $this->RIModel->getCaseType();
        if (!empty($casetype)) {
            $data['caseTypes'] = $casetype;
        }
        $rmode = $this->RIModel->getReceiptMode();
        if (!empty($rmode)) {
            $data['dispatchModes'] = $rmode;
        }
        return view('RI/Dispatch/dispatchDakFromRI', $data);
    }
    ////menu 2 receive post data to below RI/Dispatch/dispatchDakFromRI to getDataToDispatch()
    public function getDataToDispatch()
    {
        if (isset($_POST['data'])) {

            $postData = $_POST['data'];
            parse_str($postData, $parsedData);
            $csrf_token = $parsedData['CSRF_TOKEN'];
            $searchBy = $parsedData['searchBy'] ?? '';
            $status = $parsedData['status'] ?? '';
            $fromDate = $parsedData['fromDate'] ?? '';
            $toDate = $parsedData['toDate'] ?? '';
            $dealingSection = $parsedData['dealingSection'] ?? '';
            $caseType = $parsedData['caseType'] ?? '';
            $caseNo = $parsedData['caseNo'] ?? '';
            $caseYear = $parsedData['caseYear'] ?? '';
            $diaryNumber = $parsedData['diaryNumber'] ?? '';
            $diaryYear = $parsedData['diaryYear'] ?? '';
            $processId = $parsedData['processId'] ?? '';
            $processYear = $parsedData['processYear'] ?? '';
            $dispatchMode = $parsedData['dispatchMode'] ?? '';
            //echo "CSRF Token: " . $diaryNumber . "<br>";
            $data = [
                'CSRF_TOKEN' => $csrf_token,
                'searchBy' => $searchBy,
                'status' => $status,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'dealingSection' => $dealingSection,
                'caseType' => $caseType,
                'caseNo' => $caseNo,
                'caseYear' => $caseYear,
                'diaryNumber' => $diaryNumber,
                'diaryYear' => $diaryYear,
                'processId' => $processId,
                'processYear' => $processYear,
                'dispatchMode' => $dispatchMode,
            ];
            $this->RIModel->getDataToDispatchFrmRI($data);
        } else {
            echo  '<center><h4 style="color:red;">No Data Found....</h4></center>';
        }
    }
    ///// menu 3
    public function dispatchDakToRI()
    {
        //extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $data = [];


        $section = $this->RIModel->getSection();
        if (!empty($section)) {
            $data['dealingSections'] = $section;
        }
        $casetype = $this->RIModel->getCaseType();
        if (!empty($casetype)) {
            $data['caseTypes'] = $casetype;
        }
        $rmode = $this->RIModel->getReceiptMode();
        if (!empty($rmode)) {
            $data['dispatchModes'] = $rmode;
        }
        return view('RI/Dispatch/dispatchDakToRI', $data);
    }
    #menu3
    public function getDataToDispatchWithProcessId($dcmis_user_idd = 0)
    {
        // print_r($_POST);
        $fromDate = date('Y-m-d', strtotime($this->request->getPost('fromDate')));
        $toDate = date('Y-m-d', strtotime($this->request->getPost('toDate')));
        $usercode = session()->get('login')['usercode'];
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();

        if (isset($fromDate) && isset($toDate)) {
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            $data['dataToDispatchWithProcessId'] = $this->RIModel->getLettersWithProcessId(
                $fromDate,
                $toDate,
                $usercode
            );
        }
        // pr($data);

        return view('RI/Dispatch/dispatchDakToRI_list', $data);

        // echo "<pre>";
        // print_r($data);echo "</pre>";die;
        // return view('RI/Dispatch/dispatchDakToRI',$data);

    }
    public function insertDataToDispatchWithProcessId()
    {
        $usercode = session()->get('login')['usercode'];
        $userdetail = $this->RIModel->getUserDetail($usercode);
        $cases = $_POST['selectedCases'];
        $modes = $_POST['dispatchModes'];
        //print_r($userdetail);
        $usersection = $userdetail[0]['section'];
        foreach ($cases as $index => $case) {
            //echo "Case is: ".$case;
            $this->RIModel->dispatchToRIWithProcessId($case, $modes[$index], $usersection, $usercode);
        }
    }
    ##########menu4
    public function dispatchToRIWithoutProcessId()
    {
        $disabled = "";

        $usercode = session()->get('login')['usercode'];
        $data['userData'] = $this->RIModel->getUserDetail($usercode);
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();
        $data['caseTypes'] = $this->RIModel->getCaseType();
        $data['dealingSections'] = $this->RIModel->getSection();
        // pr($data['caseTypes']);
        $dealingSectionId = $data['userData'][0]['section'];

        if ($data['userData'][0]['section'] != 68) {
            $disabled = "disabled";
        }
        $data['disabled'] = $disabled;
        return view(
            'RI/Dispatch/dispatchFromSectionWithoutProcessId',
            $data
        );
    }
    ###### menu 4
    public function doDispatchFromSectionToRIWithoutProcessId()
    {
        //print_r($_POST);die();
        //$rawData = $this->request->getBody();
        $docType = $_POST['docType'];
        $referenceNumber = $_POST['referenceNumber'];
        $dispatchMode = $_POST['dispatchMode'];
        $sendTo = $_POST['sendTo'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];
        $dealingSection = $_POST['dealingSection'];
        ///
        $optradio = $_POST['optradio'];
        $caseType = $_POST['caseType'];
        $caseNo = $_POST['caseNo'];
        $caseYear = $_POST['caseYear'];
        $diaryNumber = $_POST['diaryNumber'];
        $diaryYear = $_POST['diaryYear'];

        $usercode = session()->get('login')['usercode'];
        if (!empty($docType)) {
            $docType = $docType;
        } else {
            $docType = '';
        }

        $dataToInsert = array();
        if ($docType == 'L') {
            $dataToInsert = array(
                'is_with_process_id' => 0,
                'is_case' => 0,
                'tw_notice_id' => 36, //36 for Letters sent by post
                'reference_number' => $referenceNumber,
                'send_to_name' => $sendTo,
                'send_to_address' => $address,
                'pincode' => $pincode,
                'ref_postal_type_id' => $dispatchMode,
                'ref_letter_status_id' => 1,
                'usersection_id' => $dealingSection,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s')
            );
        } else if ($docType == 'D') {

            if ($optradio == 1) {
                $searchBy = 'c';
            } elseif ($optradio == 2) {
                $searchBy = 'd';
            }
            $fetchedDiaryNo = $this->RIModel->getSearchDiary($searchBy,$caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear);
           
            $dataToInsert = array(
                'is_with_process_id' => 0,
                'is_case' => 1,
                'diary_no' => (!empty($fetchedDiaryNo)) ? $fetchedDiaryNo : NULL,
                'tw_notice_id' => 57, //57 for Decree
                'send_to_name' => $sendTo,
                'send_to_address' => $address,
                'pincode' => $pincode,
                'ref_postal_type_id' => $dispatchMode,
                'ref_letter_status_id' => 1,
                'usersection_id' => $dealingSection,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s')
            );
        }
 
        $affectedRow = $this->RIModel->saveLetterData($dataToInsert);
        if ($affectedRow > 0) {
            echo '<center><h4 style="color:red;">Letter dispatched to R&I Successfully</h4></center>';
        }
    }
    ######menu5
    public function getAddressSlip()
    {
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();
        $data['userList'] = $this->RIModel->getRIUserList();
        //pr($data['dispatchModes']);
        return view('RI/Dispatch/printAddressSlip', $data);
    }
    ######menu5
    public function getAddressReport()
    {
        //echo"hi";die();
        extract($_POST);
        if (isset($receivedDate) && !empty($receivedDate) && isset($to) && count($to) > 0) {
            $data['dataToPrintAddressSlip'] = $this->RIModel->getAddressSlipData($_POST);
        }
        //pr( $data['dataToPrintAddressSlip']);
        if ($reportType == 1) {
            return view('RI/Dispatch/addressSlipReport', $data);
        } elseif ($reportType == 2) {
            return view('RI/Dispatch/finalCompiledReport', $data);
        }
    }
    ######### menu 6
    public function reDispatchDakFromRI()
    {
        //extract($_POST);
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['caseTypes'] = $this->RIModel->getCaseType();
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();
        // pr($data);die();
        $usercode = session()->get('login')['usercode'];
        return view('RI/Dispatch/reDispatchFromRI', $data);
    }
    #### menU 6
    public function getDataToreDispatch()
    {

        // pr($data);die();
        $usercode = session()->get('login')['usercode'];
        if (isset($_POST)) {
            // print_r($_POST);die();
            //parse_str($postData, $parsedData);
            $searchBy = $_POST['searchBy'];
            $status = $_POST['status'] ?? '';
            $fromDate = $_POST['fromDate'] ?? '';
            $toDate = $_POST['toDate'] ?? '';
            $dealingSection = $_POST['dealingSection'] ?? '';
            $caseType = $_POST['caseType'] ?? '';
            $caseNo = $_POST['caseNo'] ?? '';
            $caseYear = $_POST['caseYear'] ?? '';
            $diaryNumber = $_POST['diaryNumber'] ?? '';
            $diaryYear = $_POST['diaryYear'] ?? '';
            $processId = $_POST['processId'] ?? '';
            $processYear = $_POST['processYear'] ?? '';
            $dispatchMode = $_POST['dispatchMode'] ?? '';
            //echo "CSRF Token: " . $diaryNumber . "<br>";
            $data = [
                'searchBy' => $searchBy,
                'status' => $status,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'dealingSection' => $dealingSection,
                'caseType' => $caseType,
                'caseNo' => $caseNo,
                'caseYear' => $caseYear,
                'diaryNumber' => $diaryNumber,
                'diaryYear' => $diaryYear,
                'processId' => $processId,
                'processYear' => $processYear,
                'dispatchMode' => $dispatchMode,
            ];
            //pr($data);die;
            $data['dealingSections'] = $this->RIModel->getSection();
            $data['caseTypes'] = $this->RIModel->getCaseType();
            $data['dispatchModes'] = $this->RIModel->getReceiptMode();
            $data['DataToreDispatchFrmRI'] = $this->RIModel->getDataToreDispatchFrmRI($data);
            //pr( $data['DataToreDispatchFrmRI']);die;
            return view('RI/Dispatch/getDataToreDispatch', $data);
        } else {
            echo "No data received.";
        }
    }
    ### menu 6 
    public function doReDispatchFromRI()
    {
        //print_r($_POST);die();
        extract($_POST);
        $letterStatus = 6;
        $lettersDispatched = 0;
        foreach ($selectedCases as $index => $case) {
            $isValid = 0;
            if ($dispatchModes[$index] != 4 && $dispatchModes[$index] != 5 && $dispatchModes[$index] != 6) {
                if (empty($dispatchModes[$index])) {
                    $isValid = 1;
                } elseif (empty($amounts[$index])) {
                    $isValid = 1;
                } elseif ($dispatchModes[$index] != 2 && empty($weights[$index])) { //Not Compulsory if dispatch mode is Speed post
                    $isValid = 1;
                } elseif (empty($barcodes[$index])) {
                    $isValid = 1;
                }
            } //echo $isValid;
            if ($isValid == 0) {
                // pr($amounts[$index]); pr($weights[$index]); pr($barcodes[$index]);  die;
                $usercode = session()->get('login')['usercode'];
                $this->RIModel->updateLetterStatus($case, $letterStatus, $usercode, $dispatchModes[$index], $amounts[$index], $weights[$index], $barcodes[$index]);
                $lettersDispatched++;
            }
        }
    }
    ###menu 7 Receive Letters Sent By Sections
    public function receiveDakToDispatchInRIWithProcessId()
    {
        extract($_POST);
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['caseTypes'] = $this->RIModel->getCaseType();
        return view('RI/Dispatch/receiveDakToDispatchInRIWithProcessId', $data);
    }
    ##menu 7 Receive Letters Sent By Sections
    public function getDataToReceive()
    {
        extract($_POST);
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();
        $data['dataToReciveInRI'] = $this->RIModel->enteredDakToDispatchInRIWithProcessId($_POST);
        //pr($data['dataToReciveInRI']);
        //pr($data['dataToReciveInRI']);
        return view('RI/Dispatch/dataToReceive', $data);
    }
    ### menu 777
    public function doReceiveDakWithProcessId()
    {
        print_r($_POST);
        die();
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $letterStatus = 2; //2 For Received in R&I to dispatch
        //$id,$letterStatus,$usercode,$mode=0,$amount=0,$weight=0,$barcode=""
        foreach ($selectedCases as $index => $case) {
            //echo "Case is: ".$case;
            $this->RIModel->updateLetterStatus($case, $letterStatus, $usercode, $dispatchModes[$index], $amounts[$index], $weights[$index], $barcodes[$index]);
        }
    }
    ### menu 8
    public function dispatchQuery()
    {
        $usercode = session()->get('login')['usercode'];
        $data['caseTypes'] = $this->RIModel->getCaseType();

        return view('RI/dispatch_report/dispatchQuery', $data);
    }
    ### menu 8
    public function getDispatchedData()
    {
        //extract($_POST);
        // $usercode['usercode'] = session()->get('login')['usercode'];
        $refNo = $_POST['refNo'] ?? '';
        $prYear = $_POST['prYear'] ?? '';
        $processId = $_POST['processId'] ?? '';
        $diaryYear = $_POST['diaryYear'] ?? '';
        $diaryNo = $_POST['diaryNo'] ?? '';
        $caseYear = $_POST['caseYear'] ?? '';
        $caseNo = $_POST['caseNo'] ?? '';
        $caseType = $_POST['caseType'] ?? '';
        $fromStoRI = $_POST['fromStoRI'] ?? '';
        $toStoRI = $_POST['toStoRI'] ?? '';
        $fromRItoS = $_POST['fromRItoS'] ?? '';
        $toRItoS = $_POST['toRItoS'] ?? '';
        $fromRItoR = $_POST['fromRItoR'] ?? '';
        $toRItoR = $_POST['toRItoR'] ?? '';
        //echo "CSRF Token: " . $diaryNumber . "<br>";
        $data = [
            'refNo' => $refNo,
            'prYear' => $prYear,
            'processId' => $processId,
            'diaryYear' => $diaryYear,
            'diaryNo' => $diaryNo,
            'caseYear' => $caseYear,
            'caseNo' => $caseNo,
            'caseType' => $caseType,
            'fromStoRI' => $fromStoRI,
            'toStoRI' => $toStoRI,
            'fromRItoS' => $fromRItoS,
            'toRItoS' => $toRItoS,
            'fromRItoR' => $fromRItoR,
            'toRItoR' => $toRItoR,
        ];
        $data['dispatchData'] = $this->RIModel->get_dispatch_data($data);
        return view('RI/dispatch_report/dispatchQueryData', $data);
        // $this->load->view('RI/dispatch_report/dispatchQueryData', $data);
    }

    public function date_formatter($date, $format)
    {
        if ($date != null) {
            return date($format, strtotime($date));
        } else
            return null;
    }
    ### menu 9
    public function dateWiseReceivedInRIFromSection()
    {

        $usercode = session()->get('login')['usercode'];
        $data['letterStatus'] = $this->RIModel->getLetterStatus();
        return view('RI/dispatch_report/dateWiseReceivedInRIFromSection', $data);
    }
    ### menu 9
    public function getDateWiseReceivedInRIFromSection()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $data['letterStatusId'] = $letterStatus;
        $data['receivedInRIFromSectionData'] =
            $this->RIModel->getDateWiseActionFromSection($this->date_formatter($fromDate, 'Y-m-d'), $this->date_formatter($toDate, 'Y-m-d'), $letterStatus, $usercode);
        return view('RI/dispatch_report/dataDateWisereceivedInRIFromSection', $data);
    }
    ###10
    public function dateWiseDispatchedFromSection()
    {

        $data['letterStatus'] = $this->RIModel->getLetterStatus();
        return view('RI/dispatch_report/dateWiseDispatchedFromSection', $data);
    }
    ##menu 10
    public function getDateWiseDispatchedFromSection()
    {
        extract($_POST);
        //pr($_POST);exit;
        $usercode = session()->get('login')['usercode'];
        $data['letterStatusId'] = $letterStatus;
        $data['dispatchedFromSectionData'] = $this->RIModel->getDateWiseActionFromSection($this->date_formatter($fromDate, 'Y-m-d'), $this->date_formatter($toDate, 'Y-m-d'), $letterStatus, $usercode);
        return view('RI/dispatch_report/dataDateWiseDispatchedFromSection', $data);
        // $this->load->view();
    }

    public function getCompleteDispatchTransaction($ecPostalDispatchId){
        $data['RICompleteDetail']=$this->RIModel->getRICompleteDetail($ecPostalDispatchId);       
        $data['dispatchTransactions']=$this->RIModel->getDispatchTransactions($ecPostalDispatchId);
        //pr($data['dispatchTransactions']);
        return view('RI/dispatch_report/riCompleteDetails',$data);
    }

    ### menu 11
    public function showDispatchQueryPage()
    {
        $data['caseTypes'] = $this->RIModel->getCaseType();
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['letterStatus'] = $this->RIModel->getLetterStatus();
        $searchType =  array(
            ['id' => '1', 'name' => 'Process Id'],
            ['id' => '2', 'name' => 'Diary Number'],
            ['id' => '3', 'name' => 'Case Number'],
            ['id' => '4', 'name' => 'Receipient Name'],
            ['id' => '5', 'name' => 'Receipient Address'],
            ['id' => '6', 'name' => 'Reference Number'],
            ['id' => '7', 'name' => 'Postal Number']
        );
        $data['searchType'] = $searchType;
        return view('RI/dispatch_report/showDispatchQuery', $data);
    }
    ####menu 11  
    public function getQueryData()
    {
        $data['queryRecords'] = $this->RIModel->getDispatchQueryData($_POST);
        return view('RI/dispatch_report/dataDispatchQuery', $data);
        //var_dump($data);
    }


    public function noticeAcknowledgement()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        $data = [];


        $section = $this->RIModel->getSection();
        if (!empty($section)) {
            $data['dealingSections'] = $section;
        }
        $casetype = $this->RIModel->getCaseType();
        if (!empty($casetype)) {
            $data['caseTypes'] = $casetype;
        }
        $rmode = $this->RIModel->getReceiptMode();
        if (!empty($rmode)) {
            $data['dispatchModes'] = $rmode;
        }
        return view('RI/Notices/notice_ack', $data);
    }

    public function getDataToAck()
    {
      
        if (isset($_POST)) {
            $postData = $this->request->getPost();          
            parse_str($postData['data'], $formData);

            $pro_yr='';
            if($formData['searchBy']== 'c')
            {
                //include('../extra/casetype_diary_no.php');
                $dairy_no= get_diary_case_type($formData['caseType'],$formData['caseNo'],$formData['caseYear']);
                $pro_yr=" and a.diary_no='$dairy_no'";
            }
            elseif($formData['searchBy']== 'p')
            {
                $pid=$formData['processId'];
                $pyear= $formData['processYear'];
                $sql_d="select diary_no from tw_tal_del where process_id='$pid' and year(rec_dt)='$pyear'";
                $sql_d = $this->db->query($sql_d);
                $tw_chk_details = $sql_d->getRowArray();
                $dairy_no='';
                if(!empty($tw_chk_details)) {
                    $dairy_no =$tw_chk_details['diary_no'];
                }
                
                $pro_yr=" and a.diary_no='$dairy_no' and process_id='$pid' and year(rec_dt)='$pyear'";
            }
            else
            {
                $dairy_no=$formData['diaryNumber'].$formData['diaryYear'];
                $pro_yr=" and a.diary_no='$dairy_no'";
            }


            
            $result['tw_chk'] = $this->RIModel->getDataToack($pro_yr);
            $result['dairy_no'] = $dairy_no;
            $result['RIModel'] = $this->RIModel;
           
            return view('RI/Notices/data_notice_ack', $result);
            
        } else {
            echo "No data received.";
        }
    }

    public function openNot()
    {
         
        $res_sql= $this->RIModel->getModePath($_REQUEST['hd_talw_id']);
        if(!empty($res_sql) && $res_sql['mode_path'] != '')
        {
             
            $ex_res_sql=  explode('/', $res_sql['mode_path']);
            $fil_nm="../pdf_notices/".$ex_res_sql[0].'/'.$ex_res_sql[1].'/'.$ex_res_sql[2];
            $ds=fopen($fil_nm, 'r');
            $b_z= fread($ds, filesize($fil_nm) );
            fclose($ds);
            echo utf8_encode($b_z);
        }else{
            echo '<center><h4 style="color:red;">No File Found..</h4></center>';
        }
    }

    public function save_serve()
    {
        $ucode = session()->get('login')['usercode'];
            $year = date('Y');
            //$inc_ack = $this->db->query("Select tw_max_ack from  master.tw_max_process where year ='$year'");
            $inc_ack = is_data_from_table('master.tw_max_process', "year ='$year'" , 'tw_max_ack','');
            $res_inc_ack = $inc_ack['tw_max_ack'];
            $res_inc_ack = $res_inc_ack + 1;

            $this->db->query("Update  master.tw_max_process set tw_max_ack='$res_inc_ack' where year ='$year'");            

            $major_record = explode(',', $_REQUEST['bhejo']);
            $chk_upd_sta = 0;
            for ($i = 0; $i < sizeof($major_record); $i++) {
                $rec = explode('^', $major_record[$i]);
                $rec[4] = revertDate($rec[4]);
                $ddl_l_h_s = '';
                $txt_l_h_s = '';
                $ddl_l_h_s_1 = '';
                $txt_l_h_s_1 = '';
                $ddl_l_h_s_2 = '';
                $txt_l_h_s_2 = '';
                $ddl_l_h_s_3 = '';
                $txt_l_h_s_3 = '';


                //    echo $_REQUEST['bhejo'];

                if (($rec[7] == '' && $rec[2] != 0) || $rec[7] != '') {

                    $update = "Update tw_comp_not set serve='$rec[2]',ser_type='$rec[3]',ser_date='$rec[4]', ser_dt_ent_dt=now(),ack_user_id='$ucode',ack_id='$res_inc_ack',remark='$rec[6]' where id='$rec[0]' and display='Y'";
                    if (!$this->db->query($update)) {
                        $chk_upd_sta = 1;
                        break;
                    } else {
                        //   echo $rec[7];
                        if ($rec[7] != '') {
                            $ex_rec7 = explode('@', $rec[7]);
                            //    echo "aaaa".count($ex_rec7).'aaaa';
                            for ($j = 0; $j < count($ex_rec7); $j++) {
                                $in_ex_rec =  explode('$', $ex_rec7[$j]);
                                //$chk_rec = "Select count(id) from lct_record_dis_rec where  lowerct_id='$in_ex_rec[0]' and tw_comp_not_id='$rec[0]' and display='Y'";
                                //$chk_rec = mysql_query($chk_rec) or die("Error: " . __LINE__ . mysql_error());

                                $chk_rec = is_data_from_table('lct_record_dis_rec', " lowerct_id='$in_ex_rec[0]' and tw_comp_not_id='$rec[0]' and display='Y' " , 'count(id) as total','');
                                $res_chk_rec = $chk_rec['total'];
                                if ($res_chk_rec <= 0) {
                                    $upd_rec = "Insert Into lct_record_dis_rec (lowerct_id,tw_comp_not_id,lct_remark, display,user_id,ent_date) values ('$in_ex_rec[0]','$rec[0]','$in_ex_rec[1]','Y','$ucode',now())";
                                    $upd_rec = $this->db->query($upd_rec);
                                }
                            }
                        }
                        $chk_upd_sta = 0;
                    }
                    $update = $this->db->query($update);
                }
            }
          if($chk_upd_sta==0) {  
               
                return '<div align="center">
                        <p style="color: green;font-size: 15px;font-weight: bold">Record Updated Successfully.</p>
                        </div>'; 
          } else {  
           
            return '<div align="center">
                <p style="color:  red;font-size: 15px;font-weight: bold">Error in updating Record.</p>
            </div>'; 
         }  
        

            die;
        
        
    }

    public function get_serve_type()
    {
        //$servetype = "select id,name from tw_serve where serve_stage=$_REQUEST[val] and serve_type!=0 and display='Y'";
        //$servetype = mysql_query($servetype);

        $servetype = is_data_from_table('master.tw_serve', " serve_stage=$_REQUEST[val] and serve_type!=0 and display='Y' " , 'id,name','A');
        
            $option = '<option value="0">Select</option>';
            if(!empty($servetype))
            {
                foreach($servetype as $row)
                {                   
                    $option .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';                     
                }
            }
            echo $option;
            die;
    }

    public function notice_ack_report()
    {
        $data['section'] = is_data_from_table('master.usersection', "isda='Y'", "id,section_name", 'A');
        $data['nature'] = is_data_from_table("master.casetype", "display='Y'", 'DISTINCT(nature)', $row = 'A');
        return view('RI/Notices/notice_ack_report', $data);
    }


    public function get_notice_ack_report()
    {
        //    pr($_POST);
        $txtFromDate = $_POST['txtFromDate'];
        $txtToDate   = $_POST['txtToDate'];
        $ddl_cas_nature    = $_POST['ddl_cas_nature'];
        $serveType = $_POST['serveType'];

        $ucode = session()->get('login')['usercode'];
        $results['results'] = $this->RIModel->get_notice_ack_report($txtFromDate, $txtToDate, $ddl_cas_nature, $serveType, $ucode);
        //print_r($results);
        if (!empty($results)) {
            return view('RI/Notices/data_notice_ack_report', $results);
        } else {
            echo '';
        }
    }

    public function notice_ad_ltr()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        return view('RI/Notices/ad_ltr', $data);
    }

    public function get_notice_ad_ltr()
    {
        // pr($_REQUEST);
        $ucode = session()->get('login')['usercode'];
        $txt_frmdate = date('Y-m-d',  strtotime($_REQUEST['fromDate']));
        $txt_todate = date('Y-m-d',  strtotime($_REQUEST['toDate']));
        $ddlOR = '';

        if ($_REQUEST['ddlOR'] != '') {
            $ddlOR = " and del_type='$_REQUEST[ddlOR]'";
        }

        $u_cond = '';
        if ($ucode != 1)
            $u_cond = " and d.dispatch_user_id='$ucode' ";
        else
            $u_cond = '';

        $results['results'] = $this->RIModel->getNoticeAdLtrDetails($txt_frmdate, $txt_todate, $ddlOR, $ucode, $u_cond);
        
        return view('RI/Notices/data_ad_ltr', $results);
    }

    public function UpdateDispatch()
    {
        return view('RI/Notices/notice_edit_via_pr_id');
    }
    public function post_update_dispatch()
    {
        $process_id = $_POST['processId'];
        $processYear   = $_POST['processYear'];
        $case_result['case_result'] = $this->RIModel->getNoticeDispatchUpdate($process_id, $processYear);
        return view('RI/Notices/data_notice_edit_via_pr_id', $case_result);
    }
    public function update_barcode()
    {
        $data['update_result'] = '';
        if (isset($_REQUEST) && isset($_REQUEST['id']) && isset($_REQUEST['process_id']) && isset($_REQUEST['pid_year'])) {
            $data['update_result'] = $this->RIModel->update_barcode($_REQUEST['id'], $_REQUEST['process_id'], $_REQUEST['pid_year'], $_REQUEST['barcode']);
        }
        return view('RI/Notices/notice_edit_via_pr_id', $data);
    }

    public function delete_Record()
    {
        $data['delete_result'] = '';

        if (isset($_REQUEST) && isset($_REQUEST['id']) && isset($_REQUEST['process_id']) && isset($_REQUEST['pid_year'])) {
            $data['delete_result'] = $this->RIModel->delete_Record($_REQUEST['id'], $_REQUEST['process_id'], $_REQUEST['pid_year'], $_REQUEST['barcode']);
        }
        return view('RI/Notices/notice_edit_via_pr_id', $data);
    }

    public function notice_dispatch()
    {
        return view('RI/Notices/notice_dispatch');
    }

    public function update_notice_dispatch()
    {
        $or_re_de = '';
        $teh = array();
        $up_qry = '';
        $dn_qry = '';
        $pro_yr = '';

        if ($_REQUEST['rd_ck_nt'] == 0) {
            $pro_yr = "process_id='$_REQUEST[txtProcessId]' and EXTRACT(YEAR FROM rec_dt)='$_REQUEST[pro_ty]'";
        } else {
            $ex_sp_sp_pro = explode(',', $_REQUEST['sp_sp_pro']);
            for ($index = 0; $index < count($ex_sp_sp_pro); $index++) {
                $ex_ii_exp = explode('-', $ex_sp_sp_pro[$index]);
                if ($pro_yr == '') {
                    if (count($ex_sp_sp_pro) == 1)
                        $pro_yr = '((process_id=' . $ex_ii_exp[0] . ' and EXTRACT(YEAR FROM rec_dt)=' . $ex_ii_exp[1] . '))';
                    else
                        $pro_yr = '((process_id=' . $ex_ii_exp[0] . ' and EXTRACT(YEAR FROM rec_dt)=' . $ex_ii_exp[1] . ')';
                } else {
                    // echo count($ex_sp_sp_pro);
                    if (count($ex_sp_sp_pro) == ($index + 1))
                        $pro_yr = $pro_yr . ' or' . '(process_id=' . $ex_ii_exp[0] . ' and EXTRACT(YEAR FROM rec_dt)=' . $ex_ii_exp[1] . '))';
                    else
                        $pro_yr = $pro_yr . ' or' . '(process_id=' . $ex_ii_exp[0] . ' and EXTRACT(YEAR FROM rec_dt)=' . $ex_ii_exp[1] . ')';
                }
            }
        }
        $result['ddlOR'] = $_REQUEST['ddlOR'];
        $result['rd_ck_nt'] = $_REQUEST['rd_ck_nt'];
        $result['result'] = $this->RIModel->update_notice_dispatch($pro_yr,$result['ddlOR']);
        return view('RI/Notices/data_notice_dispach', $result);

    }


    public function get_dis_max_id()
    {
        $yr=date('Y');
        if($_REQUEST['ddlOR']=='O')
        {
            $sql = is_data_from_table('master.tw_max_process', " year='$yr' ", 'tw_disp_id', '');
            $res_sq=  $sql['tw_disp_id'] ?? 0 ;
        }         
        else if($_REQUEST['ddlOR']=='R' || $_REQUEST['ddlOR']=='Z'){
            $sql = is_data_from_table('master.tw_max_process', " year='$yr' ", 'tw_disp_reg', '');
            $res_sq=  $sql['tw_disp_reg'] ?? 0;
        }
           
        
        echo $res_sq=$res_sq+1;
    }

    public function save_tw_dispatch()
    {    
        $ucode = session()->get('login')['usercode'];
        $yr=date('Y');

            $ORA='';
            if($_REQUEST['ddlOR']=='O')
            {
                $ORA='tw_disp_id';
            }
            else if($_REQUEST['ddlOR']=='R' || $_REQUEST['ddlOR']=='Z')
            {
                $ORA='tw_disp_reg';
            }
            else if($_REQUEST['ddlOR']=='A')
            {
                $ORA='tw_disp_adv_reg';
            }
        
            if($_REQUEST['ln_nl_val']=='0')
            {
                $sql = is_data_from_table('master.tw_max_process', " year='$yr' ", "'.$ORA.'", '');
                //$sql=  mysql_query("select $ORA from  tw_max_process where year='$yr'") or die("Error:".  mysql_error());

                $res_sq=   $sql[$ORA] ?? 0;
                $res_sq=$res_sq+1;
            }
            else if($_REQUEST['ln_nl_val']=='1')
            {
                $res_sq=$_REQUEST['gus_l_nl'];
            }

             
            $chk_bcr_sql = is_data_from_table('tw_comp_not', " barcode='$_REQUEST[txt_bar_cd]' and  display='Y' ", "count(barcode) as total", '');
            //exit(0);
           
            $res_barcode = $chk_bcr_sql['total'];
            if ($res_barcode <= 0) {

                $ins="Update tw_comp_not set station='$_REQUEST[ddlTehsil]',weight='$_REQUEST[txtWeight]',
                    stamp='$_REQUEST[price]',dis_remark='$_REQUEST[txtRemdis]',dispatch_user_id='$ucode',dispatch_dt=now(),
                    dispatch_id='$res_sq',barcode='$_REQUEST[txt_bar_cd]' where id='$_REQUEST[hd_talw_id]' and display='Y'";


                if($this->db->query($ins))
                {                                 
                    $sq_update=$this->db->query("Update master.tw_max_process set $ORA='$res_sq' where year='$yr'");
                    echo "Data Inserted Successsfully";
                
                
                }else{
                    echo "Data is not Inserted Successsfully";
                }               
            }
            else
            {      
                echo "Bar Code Already Used. Please try again with New One.";
            }
        
    }


    public function show_ids()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['result'] = $this->RIModel->getDispatchDetails($_REQUEST['tot_id']);
        return view('RI/Notices/show_ids', $data);
    }


    
    public function notice_dispatch_report()
    {
        $data['state_list'] = $this->RIModel->get_address_state_list();
        $data['case_type'] = is_data_from_table('master.casetype', " display='Y' order by nature", "DISTINCT(nature)", 'A');
        return view('RI/Notices/dispatch_report', $data);
    }
    public function post_dispatch_report()
    { //pr($_POST);die;
        $result['result'] = '';
        $result['result'] = $this->RIModel->post_dispatch_report(
            $_POST['ddlOR_x'],
            $_POST['fromDate'],
            $_POST['toDate'],
            $_POST['state_id'],
            $_POST['district'],
            $_POST['ddl_cas_nature']
        );
        //pr($result['result']);die;
        return view('RI/Notices/data_dispatch_report', $result);
    }
    public function transfer_cases()
    {
        $usercode  = $usercode = session()->get('login')['usercode'];
        $fil_trap_users = $this->RIModel->get_fil_trap_users();
        $data = [
            'fil_trap_users' => $fil_trap_users,
            'usercode' => $usercode
        ];
        return view('RI/dispatch_report/transfer_cases', $data);
    }
    public function getuser_for_transfer_case()
    {

        $idd = $_POST['idd'];
        $users = $this->RIModel->get_users_for_case_reansfer($idd);
        $options = '<option value="">Select</option>';
        foreach ($users as $row1) {
            $displayText = $row1['name'];
            if ($row1['section'] != '19' && $row1['section'] != '77') {
                $displayText .= ' [Transferred]';
            }
            if ($row1['display'] == 'Y') {
                $displayText .= '[Retired]';
            }
            $options .= '<option value="' . htmlspecialchars($row1['empid']) . '">' . htmlspecialchars($displayText) . '</option>';
        }
        $this->response->setHeader('Content-Type', 'text/html');
        return $options;
    }

    public function getuser_for_transfer_case_alloted()
    {
        //dd($_POST);die;
        $ddl_users  = $_POST['ddl_users'];
        $txt_frm_dt = $_POST['txt_frm_dt'];
        $txt_to_dt  = $_POST['txt_to_dt'];
        $ddl_users_nm = $_POST['ddl_users_nm'];


        $emp_id['emp_id'] = $this->RIModel->getemp_id_for_transfer_case_alloted($ddl_users, $ddl_users_nm);


        $result['result'] = $this->RIModel->getuser_for_transfer_case_alloted($ddl_users, $txt_frm_dt, $txt_to_dt, $ddl_users_nm);

        //$data = array_merge($emp_id, $result);
        // pr($data);die;

        return view('RI/dispatch_report/data_transfer_cases', array_merge($result, $emp_id));
    }

    public function nt_type_get()
    {
        $ddl_cas_nature = $_REQUEST['ddl_cas_nature'];
        $sql = $this->db->query("Select casecode,skey,casename from  master.casetype where display='Y' and nature='$ddl_cas_nature' order by skey");

        $option = '<option value="">Select</option>';
        $result = $sql->getResultArray();
        foreach ($result as $row) {
            $option .= '<option value="' . $row['casecode'] . '" title="' . $row['casename'] . '">' . $row['skey'] . '</option>';
        }
        echo $option;
        die;
    }

    public function get_rep_ack_tal()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        $data['from_date'] = date('Y-m-d', strtotime($_REQUEST['txtFromDate']));
        $data['todate'] = date('Y-m-d', strtotime($_REQUEST['txtToDate']));
        $serveType = $_REQUEST['serveType'];
        $serveCondition = "";
        if ($serveType != "") {
            $serveCondition = " and serve=" . $serveType;
        }

        if ($_REQUEST['section_name'] != 'ALL')
            $condition = " and tentative_section(m.diary_no)='" . $_REQUEST['section_name'] . "'";
        else
            $condition = "";

        $data['result'] = $this->RIModel->get_rep_ack_tal_data($serveCondition, $data['from_date'], $data['todate'], $condition);
        return view('RI/dispatch_report/get_rep_ack_tal_data', $data);
    }

    public function getCityName()
    {
        $option = '';
        if ($_REQUEST['str'] == '0') {
            $option = '<option value="0">None</option>';
        } else {
            $query =  $this->db->query("SELECT id_no, name FROM master.state WHERE state_code = (SELECT state_code FROM master.state WHERE id_no = '$_REQUEST[str]' AND display = 'Y' ) AND sub_dist_code = '0' AND district_code !=0 AND village_code =0 and display='Y' ORDER BY Name");
            $result = $query->getResultArray();
            foreach ($result as $row) {

                $option .=  '<option value="' . $row['id_no'] . '">' . $row['name'] . '</option>';
            }
        }
        return $option;
    }

    public function getCaseType()
    {
        $sql =  is_data_from_table("master.casetype", "display='Y' and nature='C' order by skey", "casecode,short_description", 'A');
        $option =  '<option value="">Select</option>';
        foreach ($sql as $row) {
            $option .=  '<option value="' . $row['casecode'] . '">' . $row['short_description'] . '</option>';
        }
        return $option;
    }

    public function getDispatchRep()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        $data['txt_frmdate'] = date('Y-m-d',  strtotime($_REQUEST['txt_frmdate']));
        $data['txt_todate'] = date('Y-m-d',  strtotime($_REQUEST['txt_todate']));

        $ddlOR = '';
        $state = '';
        $district = '';
        $nature = '';
        $casetype = '';
        $user_code = '';

        if ($_REQUEST['ddlOR'] != '')
            $ddlOR = " and del_type='$_REQUEST[ddlOR]'";

        if ($_REQUEST['ddl_state'] != '') {
            $state = " and CASE WHEN tw_sn_to = 0 THEN tal_state='$_REQUEST[ddl_state]' ELSE sendto_state='$_REQUEST[ddl_state]' END";
        }
        if ($_REQUEST['ddlDistrict'] != '') {
            $district = " and CASE WHEN tw_sn_to = 0 THEN tal_district='$_REQUEST[ddlDistrict]' ELSE sendto_district='$_REQUEST[ddlDistrict]' END";
        }
        if ($_REQUEST['ddl_cas_nature'] != '') {
            $nature = " join master.casetype ct on ct.casecode=m.active_casetype_id and ct.display='Y' and 
                  ct.nature='$_REQUEST[ddl_cas_nature]'";
        }
        if ($_REQUEST['cs_tp'] != '') {
            $casetype = " and casecode='$_REQUEST[cs_tp]'";
        }
        if ($data['ucode'] != 1) {
            $user_code = " and a.user_id='" . $data['ucode'] . "'";
        }

        $data['result'] = $this->RIModel->get_dispatch_rep_data($nature, $data['txt_frmdate'], $data['txt_todate'], $ddlOR, $state, $district, $casetype);
        return view('RI/dispatch_report/get_dispatch_rep_table', $data);
    }
}