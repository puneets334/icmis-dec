<?php

namespace App\Controllers\RI;

use App\Controllers\BaseController;
use App\Models\RI\RIModel;
use App\Models\PIL\PilModel;
use App\Models\Entities\Model_state;
use CodeIgniter\Controller;
use App\Libraries\Common;

class ReceiptController extends BaseController
{
    public $RIModel;
    public $PilModel;
    public $common;
    public function __construct()
    {
        $this->RIModel = new RIModel();
        $this->PilModel = new PilModel();
        $this->common  = new Common();
    }

    public function index($msg = "")
    {
        $data['msg'] = $msg;
        $data['app_name'] = 'RI';
        $riData = $this->RIModel->showRIData();
        if ($riData) {
            $data['riData'] = $riData;
        }
        return view('RI/Receipt/showRIData', $data);
    }

    public function getRIDetailByDiaryNumber()
    {
        if (!empty($_POST)) {
            $ecReceiptId = $this->RIModel->getReceiptId($_POST['diaryNo'], $_POST['diaryYear']);
            if (!empty($ecReceiptId['id'])) {
                $this->editReceiptData($ecReceiptId['id']);
            } else {
                $this->index("No Record found.");
            }
        }
    }

    public function editReceiptData($ecReceiptId = null)
    {
        //        echo "<pre>";

        $data['receiptId'] = $ecReceiptId;
        $data['state'] = $this->PilModel->get_state_list();
        $data['judges'] = $this->RIModel->getJudge();
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['officers'] = $this->RIModel->getOfficers();
        $data['receiptModes'] = $this->RIModel->getReceiptMode();
        $data['caseTypes'] = $this->RIModel->getCaseType();
        //    echo "<pre>";
        //    print_r($data);
        //    die;
        if ($ecReceiptId != null and $ecReceiptId != 0) {
            //          UPDATE SECTION
            $data['ecReceiptCompleteData'] = $this->RIModel->getReceiptDataById($ecReceiptId);
            //        echo "<pre>";
            //        print_r($data['ecReceiptCompleteData']);  die;
        }
        //        echo "<pre>";
        //        print_r($data);
        //        die;
        echo view('RI/Receipt/addEditReceiptData', $data);
    }

    private function addSlashinString($str)
    {
        return addslashes($str);
        //return str_replace ("'","\'",$str);
    }

    public function saveReceiptData()
    {
        // pr($_REQUEST);
        $this->db = \Config\Database::connect();
        $this->db->transStart();

        if (!empty($_POST)) {
            //            echo "<pre>";
            //            print_r($_POST);die;

            $receiptid = $_POST['receiptid'];
            $usercode = $_POST['usercode'];
            $postalNo = $_POST['postalNo'];
            $postalDate = $_POST['postalDate'];
            $letterNo = $_POST['letterNo'];
            $letterDate = $_POST['letterDate'];
            $senderName = $_POST['senderName'];
            $senderAddress = $_POST['senderAddress'];
            $state = $_POST['state'];
            $subject = $_POST['subject'];
            $caseType = $_POST['caseType'];
            $caseNumber = $_POST['caseNumber'];
            $caseYear = $_POST['caseYear'];
            $caseDiaryNo = $_POST['caseDiaryNo'];
            $caseDiaryYear = $_POST['caseDiaryYear'];
            $receiptMode = $_POST['receiptMode'];
            $pilDiaryNumber = $_POST['pilDiaryNumber'];
            $sentToUserType = $_POST['sentToUserType'];
            $postalAddressee = $_POST['postalAddressee'];
            $judge = $_POST['judge'];
            $officer = $_POST['officer'];
            $dealingSection = $_POST['dealingSection'];
            $remarks = $_POST['remarks'];
        }
        //        echo $receiptid."RID";die; // 331368
        if ($receiptid == 0 || $receiptid == null) {
            $columnName = "(";
            $valueField = "(";
            if (!empty($receiptMode) && $receiptMode != 0) {
                $columnName = $columnName . "ref_postal_type_id,";
                $valueField = $valueField . $receiptMode . ",";
            }
            if (!empty($_POST['isOpenable'])) {
                $columnName = $columnName . "is_openable,";
                $valueField = $valueField . "'" . $_POST['isOpenable'] . "',";
            }
            if (!empty($postalNo)) {
                $columnName = $columnName . "postal_no,";
                $valueField = $valueField . "'" . $this->addSlashinString($postalNo) . "',";
            }
            if (!empty($postalDate)) {
                $columnName = $columnName . "postal_date,";
                $valueField = $valueField . "'" . $this->common->date_formatter($postalDate, 'Y-m-d') . "',";
            }
            if (!empty($letterNo)) {
                $columnName = $columnName . "letter_no,";
                $valueField = $valueField . "'" . $this->addSlashinString($letterNo) . "',";
            }
            if (!empty($letterDate)) {
                $columnName = $columnName . "letter_date,";
                $valueField = $valueField . "'" . $this->common->date_formatter($letterDate, 'Y-m-d') . "',";
            }
            if (!empty($subject)) {
                $columnName = $columnName . "subject,";
                $valueField = $valueField . "'" . $this->addSlashinString($subject) . "',";
            }
            if ($caseType != 0 && !empty($caseNumber) && $caseYear != 0) {
                //Get diary_number of the case
                $searchBy = 'c';
                $fetchedDiaryNo = $this->RIModel->getSearchDiary($searchBy, $caseType, $caseNumber, $caseYear, $caseDiaryNo, $caseDiaryYear);
                $columnName = $columnName . "ec_case_id,";
                $valueField = $valueField . "" . $fetchedDiaryNo . ",";
            } elseif (!empty($caseDiaryNo) && !empty($caseDiaryYear) && $caseDiaryYear != 0) {
                $searchBy = 'd';
                $fetchedDiaryNo = $this->RIModel->getSearchDiary($searchBy, $caseType, $caseNumber, $caseYear, $caseDiaryNo, $caseDiaryYear);
                $columnName = $columnName . "ec_case_id,";
                $valueField = $valueField . "" . $fetchedDiaryNo . ",";
            }
            if (!empty($_POST['isOriginal'])) {
                $columnName = $columnName . "is_original_record,";
                $valueField = $valueField . "'" . $_POST['isOriginal'] . "',";
            }
            if (!empty($senderName)) {
                $columnName = $columnName . "sender_name,";
                $valueField = $valueField . "'" . $this->addSlashinString($senderName) . "',";
            }
            if (!empty($senderAddress)) {
                $columnName = $columnName . "address,";
                $valueField = $valueField . "'" . $this->addSlashinString($senderAddress) . "',";
            }
            if (!empty($state) && $state != 0) {
                $columnName = $columnName . "ref_state_id,";
                $valueField = $valueField . $state . ",";
            }
            if (!empty($pilDiaryNumber)) {
                $columnName = $columnName . "pil_diary_number,";
                $valueField = $valueField . "'" . $pilDiaryNumber . "',";
            }
            if (!empty($remarks)) {
                $columnName = $columnName . "remarks,";
                $valueField = $valueField . "'" . $this->addSlashinString($remarks) . "',";
            }

            $record = $this->RIModel->getLastDiaryNumber(date("Y"));
            //            echo "<pre>";
            //            print_r($record);
            //            die;
            if (!empty($record)) {
                $lastDiaryNumber = $record['diary_no'];
            } else {
                $lastDiaryNumber = null;
            }

            //            print_r($lastDiaryNumber);
            //            die;
            if ($lastDiaryNumber == '') {
                $lastDiaryNumber = 1;
            } else {

                $lastDiaryNumber = $lastDiaryNumber + 1;
            }
            //                        print_r($lastDiaryNumber);
            //            die;
            $columnName = $columnName . "diary_no,";
            $valueField = $valueField . "'" . $lastDiaryNumber . "',";
            $columnName = $columnName . "diary_year,";
            $valueField = $valueField . "'" . date("Y") . "',";

            $columnName = $columnName . "updated_on,";
            $valueField = $valueField . "now(),";

            $columnName = $columnName . "received_on,";
            $valueField = $valueField . "now(),";

            $columnName = $columnName . "adm_updated_by,";
            $valueField = $valueField . "" . $usercode . ",";

            $columnName = rtrim($columnName, ',') . ")";
            $valueField = rtrim($valueField, ',') . ")";

            // echo $columnName . "<br>" . $valueField;

            $query = "insert into ec_postal_received" . $columnName . " values " . $valueField;


            $insertedId = $this->RIModel->saveReceiptData($query, "i");
            // echo "<pre>";
            // print_r($insertedId); 
            // echo "world";
            // die;
            if ($insertedId != null && $insertedId != "") {
                $dispatchedTo = null;
                if ($sentToUserType == 's') {
                    $dispatchedTo = $dealingSection;
                } else if ($sentToUserType == 'o') {
                    $dispatchedTo = $officer;
                } else if ($sentToUserType == 'j') {
                    $dispatchedTo = $judge;
                }
                $dataForInsert = array('ec_postal_received_id' => $insertedId, 'dispatched_to_user_type' => $sentToUserType, 'dispatched_to' => $dispatchedTo);
                $result = $this->RIModel->insertEcPostalTransactions($dataForInsert);
                //echo "Success";
                echo $lastDiaryNumber . '/' . date("Y");
                // $this->send_SMS($mobileno,'Your Grievance/Communication has been successfully registered as Diary No.'.$lastDiaryNumber.'/SCI/PIL(E)'.date("Y").' For status, kindly logon to http://sci.gov.in and go to Grievance Management option in Case Information Tab.');
            } else {
                echo "Error";
            }
        } elseif ($receiptid > 0) {
            //            echo "elsepart";di`e;
            $updateQuery = "";
            if (!empty($addressedto)) {
                $updateQuery = $updateQuery . "address_to='" . $this->addSlashinString($addressedto) . "',";
            }


            if (!empty($receiptMode) && $receiptMode != 0) {
                $updateQuery = $updateQuery . "ref_postal_type_id=" . $receiptMode . ",";
            } else {
                $updateQuery = $updateQuery . "ref_postal_type_id=NULL,";
            }
            if (!empty($isOpenable)) {
                $updateQuery = $updateQuery . "is_openable='" . $isOpenable . "',";
            }
            if (!empty($postalNo)) {
                $updateQuery = $updateQuery . "postal_no='" . $this->addSlashinString($postalNo) . "',";
            } else {
                $updateQuery = $updateQuery . "postal_no=NULL,";
            }
            if (!empty($postalDate)) {
                $updateQuery = $updateQuery . "postal_date='" . $this->common->date_formatter($postalDate, 'Y-m-d') . "',";
            }
            if (!empty($letterNo)) {
                $updateQuery = $updateQuery . "letter_no='" . $this->addSlashinString($letterNo) . "',";
            } else {
                $updateQuery = $updateQuery . "letter_no=NULL,";
            }
            if (!empty($letterDate)) {
                $updateQuery = $updateQuery . "letter_date='" . $this->common->date_formatter($letterDate, 'Y-m-d') . "',";
            }
            if (!empty($subject)) {
                $updateQuery = $updateQuery . "subject='" . $this->addSlashinString($subject) . "',";
            } else {
                $updateQuery = $updateQuery . "subject=NULL,";
            }
            if (!empty($caseDiaryNo) && !empty($caseDiaryYear) && $caseDiaryYear != 0) {
                $updateQuery = $updateQuery . "ec_case_id=" . $caseDiaryNo . $caseDiaryYear . ",";
            } else {
                $updateQuery = $updateQuery . "ec_case_id=NULL,";
            }
            if (!empty($isOriginal)) {
                $updateQuery = $updateQuery . "is_original_record='" . $isOriginal . "',";
            }
            if (!empty($senderName)) {
                $updateQuery = $updateQuery . "sender_name='" . $this->addSlashinString($senderName) . "',";
            } else {
                $updateQuery = $updateQuery . "sender_name=NULL,";
            }
            if (!empty($senderAddress)) {
                $updateQuery = $updateQuery . "address='" . $this->addSlashinString($senderAddress) . "',";
            } else {
                $updateQuery = $updateQuery . "address=NULL,";
            }
            if (!empty($state) && $state != 0) {
                $updateQuery = $updateQuery . "ref_state_id=" . $state . ",";
            } else {
                $updateQuery = $updateQuery . "ref_state_id=NULL,";
            }
            if (!empty($pilDiaryNumber)) {
                $updateQuery = $updateQuery . "pil_diary_number='" . $pilDiaryNumber . "',";
            } else {
                $updateQuery = $updateQuery . "pil_diary_number=NULL,";
            }
            if (!empty($remarks)) {
                $updateQuery = $updateQuery . "remarks='" . $this->addSlashinString($remarks) . "',";
            } else {
                $updateQuery = $updateQuery . "remarks=NULL,";
            }

            $updateQuery = $updateQuery . "updated_on=now(),";
            $updateQuery = $updateQuery . "adm_updated_by=" . $usercode . ",";
            $updateQuery = rtrim($updateQuery, ',');

            $query = "update ec_postal_received set " . $updateQuery . " where id=" . $receiptid;
            //    echo $query;
            //    die;
            $this->RIModel->transferReceiptDataToLogtable($receiptid);
            $rowsaffected = $this->RIModel->saveReceiptData($query);
            //            echo $rowsaffected;die;
            $dispatchedTo = null;
            if ($sentToUserType == 's') {
                $dispatchedTo = $dealingSection;
            } else if ($sentToUserType == 'o') {
                $dispatchedTo = $officer;
            } else if ($sentToUserType == 'j') {
                $dispatchedTo = $judge;
            }
            $dataForInsert = array('ec_postal_received_id' => $receiptid, 'dispatched_to_user_type' => $sentToUserType, 'dispatched_to' => $dispatchedTo);
            $dataForUpdate = array('is_active' => 'f');
            $result = $this->RIModel->updateEcPostalTransactions($dataForInsert, $dataForUpdate, $receiptid);
            // pr($result);
            if ($rowsaffected != null && $rowsaffected != "") {
                echo "Success";
            } else {
                echo "Error";
            }
        }
        $this->db->transComplete();
        die;
    }

    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>BELOW FUNCTIONS ARE OF DATE-WISE REPORT<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

    public function dateWiseReceived()
    {

        if (!empty($_POST)) {
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            if (isset($fromDate) && isset($toDate)) {
                $data['fromDate'] = $fromDate;
                $data['toDate'] = $toDate;
                $receiptDatewise = $this->RIModel->getReceiptDateWise($this->common->date_formatter($fromDate, 'Y-m-d'), $this->common->date_formatter($toDate, 'Y-m-d'));
                if ($receiptDatewise) {
                    $data['receiptData'] = $receiptDatewise;
                } else {
                    $data['receiptData'] = '';
                }
            }
            return view('RI/Receipt/receiptReport', $data);
        }
        return view('RI/Receipt/receiptReport');
    }

    public function completeDetail($ecPostalReceived)
    {
        
        $detail = $this->RIModel->getCompleteDetail($ecPostalReceived);
        if ($detail) {
            $data['completeDetails'] = $detail;
        } else {
            $data['completeDetails'] = '';
        }
        $gettransactions = $this->RIModel->getTransactions($ecPostalReceived);
        if ($gettransactions) {
            $data['transactions'] = $gettransactions;
        } else {
            $data['transactions'] = '';
        }

        return view('RI/Receipt/rptCompleteDetails', $data);
    }

    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>END OF DATE-WISE REPORT<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>BELOW FUNCTIONS ARE OF DISPATCH AD TO SECTION<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */


    public function getADToDispatch()
    {
        $getCaseType = $this->RIModel->getCaseType();
        if ($getCaseType) {
            $data['caseTypes'] = $getCaseType;
        } else {
            $data['caseTypes'] = '';
        }
        $getSection = $this->RIModel->getSection();
        if ($getSection) {
            $data['dealingSections'] = $getSection;
        } else {
            $data['dealingSections'] = '';
        }
        $getServeType = $this->RIModel->getServeType(1);
        if ($getServeType) {
            $data['serveStage'] = $getServeType;
        } else {
            $data['serveStage'] = '';
        }
        //          echo "<pre>";  print_r($data);  die;
        return view('RI/Receipt/dispatchADToSection', $data);
    }
    #####menu 

    public function getDataForADToDispatch()
    {

        if (!empty($_POST)) {
            $searchBy = $_POST['searchBy'];
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            $dealingSection = $_POST['dealingSection'];
            $caseType = $_POST['caseType'];
            $caseNo = $_POST['caseNo'];
            $caseYear = $_POST['caseYear'];
            $diaryNumber = $_POST['diaryNumber'];
            $diaryYear = $_POST['diaryYear'];
            $processId = $_POST['processId'];
            $processYear = $_POST['processYear'];
            $status = $_POST['status'];
        }
        $data = [

            'searchBy' => $searchBy,
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
            'status' => $status,

        ];

        $getDakToDispatch = $this->RIModel->enteredDakToDispatchInRIWithProcessId21($data);
        if ($getDakToDispatch) {
            $data['dataForADToDispatch'] = $getDakToDispatch;
        } else {
            $data['dataForADToDispatch'] = '';
        }

        return view('RI/Receipt/dataToDispatchAD', $data);
    }

    public function doDispatchADToSection()
    {
        extract($_POST);
        $usercode = session()->get('login')['usercode'];
        foreach ($selectedCases as $index => $case) {
            $this->RIModel->dispatchADToSection($case, $sendToSection[$index], $usercode);
        }
    }




    public function getDispatch()
    {

        $data['judges'] = $this->RIModel->getJudge();
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['officers'] = $this->RIModel->getOfficers();
        $data['judgeid'] = '';
        $data['officerid'] = '';
        $data['dealingSectionid'] = '';
        return view('RI/Receipt/dispatchReceiptDak', $data);
    }

    public function doDispatchDak(){

        extract($_POST);
        $usercode= session()->get('login')['usercode'];
        foreach ($selectedCases as $dak){
            $this->RIModel->transferReceiptDataToLogtable($dak);
            $result=$this->RIModel->doDispatch($dak,$usercode);
            if($result==1){
                $data['dispatchedDetails']=$this->RIModel->getDispatchedDak($selectedCases);

            }
        }
       return view('RI/Receipt/dispatchedReport',$data);
    }

    public function getDispatchData()
    {
       
        if (!empty($_POST)) {
            //            echo "RRR";
            $whereCondition = "";
            $receiptModeCondition = "";
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            $searchBy = $_POST['searchBy'];
            $parcelReceiptMode = $_POST['parcelReceiptMode'];
            $judge = $_POST['judge'];
            $officer = $_POST['officer'];
            $dealingSection = $_POST['dealingSection'];
            //            echo "PP".$parcelReceiptMode.":::";
            if (isset($fromDate) && isset($toDate) && isset($searchBy)) {

                if ($searchBy == 'j') {
                    $whereCondition = " ept.dispatched_to_user_type='j' and ept.dispatched_to=" . $judge;
                } else if ($searchBy == 'o') {
                    $whereCondition = " ept.dispatched_to_user_type='o' and ept.dispatched_to=" . $officer;
                } else if ($searchBy == 's') {
                    $whereCondition = " ept.dispatched_to_user_type='s' and ept.dispatched_to=" . $dealingSection;
                }
                if ($parcelReceiptMode == 1) {
                    $receiptModeCondition = " ecpd.ref_postal_type_id=5";
                } else if ($parcelReceiptMode == 2) {
                    $receiptModeCondition = " ecpd.ref_postal_type_id != 5";
                }


                $data['receiptData'] = $this->RIModel->getDispatchData($whereCondition, $receiptModeCondition, $fromDate, $toDate);
            }
            return view('RI/Receipt/getDispatchReceiptDak', $data);
        }
    }


    public function showServeUnServe()
    {
        //       var_dump($_POST);

        $data['dealingSections'] = $this->RIModel->getSection();
        $data['caseTypes'] = $this->RIModel->getCaseType();
        //           if (!isset($_SESSION['dcmis_user_idd'])) {
        //               $this->session->set_userdata('dcmis_user_idd', $usercode);
        //           }
        return view('RI/Receipt/serveUnserve', $data);
    }


    public function getDataForServeUnserve()
    {
        //        var_dump($_POST);
        //        die;
        //        array(12) { ["searchBy"]=> string(1) "p" ["status"]=> string(4) "8888" ["fromDate"]=> string(0) "" ["toDate"]=> string(0) ""
        //        ["dealingSection"]=> string(1) "0" ["caseType"]=> string(1) "0" ["caseNo"]=> string(0) "" ["caseYear"]=> string(4) "2024"
        //        ["diaryNumber"]=> string(0) "" ["diaryYear"]=> string(4) "2024" ["processId"]=> string(3) "123" ["processYear"]=> string(4) "2024" }

        //        extract($_POST);

        if (!empty($_POST)) {
            $searchBy = $_POST['searchBy'];
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            $dealingSection = $_POST['dealingSection'];
            $caseType = $_POST['caseType'];
            $caseNo = $_POST['caseNo'];
            $caseYear = $_POST['caseYear'];
            $diaryNumber = $_POST['diaryNumber'];
            $diaryYear = $_POST['diaryYear'];
            $processId = $_POST['processId'];
            $processYear = $_POST['processYear'];
            $status = $_POST['status'];
        }
        $data = [

            'searchBy' => $searchBy,
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
            'status' => $status,

        ];
        //        echo $status."OOO";

        if (!empty($_POST['dispatchMode']))
            $dispatchMode = $_POST['dispatchMode'];
        else $dispatchMode = "";
        $wherePostalDispatch = "";
        $whereDateRange = "";
        if ($status == 0) {
            $wherePostalDispatch = " epd.id is null";
        } elseif ($status == 8888) { //For Dispatched or Re-dispatched
            $wherePostalDispatch = " epd.ref_letter_status_id in (3,6)";
        } elseif ($status == 9999) {
            $wherePostalDispatch = " epd.ref_letter_status_id in (4,5)";
        } else {
            $wherePostalDispatch = " epd.ref_letter_status_id=" . $status;
        }
        if (isset($searchBy)) {
            if ($searchBy == 's') {
                if ($fromDate != '' && $toDate != '') {
                    if ($dealingSection != 0) {
                        $whereDateRange = " and date(epdt.updated_on) between '" . date('Y-m-d', strtotime($fromDate)) . "' and '" . date('Y-m-d', strtotime($toDate)) . "' and epd.usersection_id=$dealingSection";
                    } else {
                        $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($fromDate)) . "' and '" . date('Y-m-d', strtotime($toDate)) . "'";
                    }
                }
            } else if ($searchBy == 'c' || $searchBy == 'd') {
                $fetchedDiaryNo = $this->RIModel->getSearchDiary($caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear, $searchBy);
                $whereDateRange = " and epd.diary_no=" . $fetchedDiaryNo;
            } else if ($searchBy == 'p') {
                $whereDateRange = " and epd.process_id=$processId and process_id_year=" . $processYear;
            }

            if ($dispatchMode != 0 && $dispatchMode != '') {

                $whereDateRange .= " and epd.ref_postal_type_id=$dispatchMode";
            }
        } else {
            if ($fromDate != '' && $toDate != '') {
                if ($dealingSection != 0) {
                    $whereDateRange = " and date(epdt.updated_on) between '" . date('Y-m-d', strtotime($fromDate)) . "' and '" . date('Y-m-d', strtotime($toDate)) . "' and epd.usersection_id=$dealingSection";
                } else {
                    $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($fromDate)) . "' and '" . date('Y-m-d', strtotime($toDate)) . "'";
                }
            }
            if ($dispatchMode != 0 && $dispatchMode != '') {
                $whereDateRange .= " and epd.ref_postal_type_id=$dispatchMode";
            }
        }

        //        epd.ref_letter_status_id in (4,5)>> and epd.process_id=12345 and process_id_year=2024 and epd.ref_postal_type_id=

        //        $getDakToDispatch = $this->RIModel->enteredDakToDispatchInRIWithProcessId($wherePostalDispatch, $whereDateRange);
        //        epd.ref_letter_status_id in (3,6)>> and epd.process_id=134 and process_id_year=2024
        $serveStage = $this->RIModel->getServeType(1);
        if ($serveStage) {
            $data['serveStage'] = $serveStage;
        } else {
            $data['serveStage'] = '';
        }
        $dataToUpdateServeStatus = $this->RIModel->enteredDakToDispatchInRIWithProcessId22($data);

        if ($dataToUpdateServeStatus) {
            $data['dataToUpdateServeStatus'] = $dataToUpdateServeStatus;
        } else {
            $data['dataToUpdateServeStatus'] = '';
        }
        //        echo "<pre>";
        //        print_r($dataToUpdateServeStatus);
        //        die;

        echo view('RI/Receipt/dataToUpdateServeUnserve', $data);
    }

    public function getServeType()
    {
        //        extract($_GET);
        //pr($_POST);
        //exit;
        $stage = $_POST['stage'];
        $id = $_POST['id'];
        $serveType = $this->RIModel->getServeType($id, $stage);
        echo "<select id=\"serveType_$id\" class=\"form-control\">";
        echo "<option value=0>Select Serve Type </option>";
        foreach ($serveType as $type) {
            echo "<option value=$type[id]>$type[name] </option>";
        }
        echo "</select>";
    }

    public function doUpdateServeUnServe()
    {
        //        extract($_POST);
        // pr($_POST);exit; 
        $selectedCases = $_POST['selectedCases'];
        $serveStage = $_POST['serveStage'];
        $serveType = $_POST['serveType'];
        $remarks = $_POST['remarks'];
        $usercode = session()->get('login')['usercode'];
        $letterStatus = 0;
        $serveUnserve = 0;
        foreach ($selectedCases as $index => $case) {
            $isValid = 0;
            if (empty($serveStage[$index])) {
                $isValid = 1;
            } elseif (empty($serveType[$index])) {
                $isValid = 1;
            }
            //::TODO Add 12-Affixed,15-Awaited,43-Recalled Letter Status
            if ($serveStage[$index] == 1 || $serveStage[$index] == 17) {
                $letterStatus = 4; //Server to Concerned
            } elseif ($serveStage[$index] == 5) {
                $letterStatus = 5; //Un-Served to Concerned
            }

            if ($isValid == 0) {
                $this->RIModel->updateServeStaus($case, $letterStatus, $usercode, $serveStage[$index], $serveType[$index], $remarks[$index]);
                $serveUnserve++;
            }
        }
        echo $serveUnserve;
    }
    public function date_formatter($date, $format)
    {
        if ($date != null) {
            return date($format, strtotime($date));
        } else
            return null;
    }
    ###menu 


    public function dateWiseReceivedByConcern()
    {

        $usercode = session()->get('login')['usercode'];

        if (!empty($_POST)) {
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            $reportType = $_POST['reportType'];
            $userDetails = $this->RIModel->getUserDetails($usercode);
          
            if (isset($fromDate) && isset($toDate)) {
                $data['reportType'] = $reportType;
                $data['fromDate'] = $fromDate;
                $data['toDate'] = $toDate;

                // Access section correctly from the stdClass object
                $userSection = isset($userDetails->section) ? $userDetails->section : null;

                $data['receivedData'] = $this->RIModel->getReceivedByConcernDateWise(
                    $this->date_formatter($fromDate, 'Y-m-d'),
                    $this->date_formatter($toDate, 'Y-m-d'),
                    $reportType,
                    $usercode,
                    $userSection 
                );
                return view('RI/Receipt/dateWiseReceivedReport', $data);
            }
        }
        return view('RI/Receipt/dateWiseReceivedReport');
    }



    public function receivedQuery()
    {
        //        if (!isset($_SESSION['dcmis_user_idd'])) {
        //            $this->session->set_userdata('dcmis_user_idd', $usercode);
        //        }
        $data['caseTypes'] = $this->RIModel->getCaseType();
        $data['dispatchModes'] = $this->RIModel->getReceiptMode();
        return view('RI/Receipt/receivedQuery', $data);
    }


    public function getReceivedData()
    {
        $usercode = session()->get('login')['usercode'];
        if (!empty($_POST)) {
            $sa = $_POST['sa'];
            $rName = $_POST['rName'];
            $rAdd = $_POST['rAdd'];
            $caseType = $_POST['caseType'];
            $caseNo = $_POST['caseNo'];
            $caseYear = $_POST['caseYear'];
            $postNo = $_POST['postNo'];
            $dMode = $_POST['dMode'];
        }

        if ($sa == 1) {
            $usercondition = "epr.sender_name like '%" . $rName . "%'";
        } else if ($sa == 2) {
            $usercondition = "epr.address like '%" . $rAdd . "%'";
        } else if ($sa == 4) {
            $usercondition = "postal_no = '" . $postNo . "'";
        } else if ($sa == 3) {
            $diaryNo = $this->RIModel->getSearchDiary('c', $caseType, $caseNo, $caseYear);
            if ($diaryNo != '') {
                $usercondition = "epr.ec_case_id='" . $diaryNo . "'";
            } else {
                return;
            }
        } else {
            return;
        }
        if ($dMode != 0 && $usercondition != '') {
            $usercondition = $usercondition . " and epr.ref_postal_type_id= '" . $dMode . "'";
        }
        $data['receivedData'] = $this->RIModel->get_received_data($usercondition);
        echo view('RI/Receipt/receivedQueryData', $data);
    }


    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>END OF Received Query REPORT<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>BELOW FUNCTIONS ARE OF  RECEIVE DAK<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */


    public function getDakDataForReceive()
    {
        $this->db = \Config\Database::connect();
        //$this->db->transStart();
        $usercode = session()->get('login')['usercode'];
        $userDetails = $this->RIModel->getUserDetails($usercode);
        $userSection = isset($userDetails->section) ? $userDetails->section : null;
        $data['dealingSections'] = $this->RIModel->getSection();
        $data['forReceiveInSection'] = $this->RIModel->getDakDataForReceive($usercode, $userSection, 'P');
        $data['forInitiatedReceivedInSection'] = $this->RIModel->getInitiatedDakDataForReceive($usercode, $userSection, 'P');
      
        return view('RI/Receipt/getReceiptDakForSection', $data);
    }


    public function doReceiveDakForSection()
    {
        //        echo "<pre>";print_r($_POST);
        //        die;
        $this->db = \Config\Database::connect();
        $this->db->transStart();

        if (!empty($_POST)) {
            $selectedCases = $_POST['selectedCases'];
            $actionType = $_POST['actionType'];
            $returnReason = $_POST['returnReason'];
            $dealingSection = $_POST['dealingSection'];
            $officer = $_POST['officer'];
        }
        $ecPostalTransaction_id = $isForwarded = $ec_postal_user_initiated_letter_id = $dispatchedBy = $letterPriority = '';

        $usercode = session()->get('login')['usercode'];
        foreach ($selectedCases as $dak) {
            $id = explode('#', $dak);
            //            echo "<pre>";  print_r($id);    die;
            $ecPostalTransaction_id = $id[1];
            $dakTransactionData['dakTransactionFullData'] = $this->RIModel->getDakTransactionDetails($ecPostalTransaction_id);
            //            echo "<pre>";
            //            print_r($dakTransactionData['dakTransactionFullData']);
            //            die;
            $isForwarded = $dakTransactionData['dakTransactionFullData']['0']['is_forwarded'];

            $ec_postal_user_initiated_letter_id = $dakTransactionData['dakTransactionFullData']['0']['ec_postal_user_initiated_letter_id'];

            $dispatchedBy = $dakTransactionData['dakTransactionFullData']['0']['dispatched_by'];
            $letterPriority = $dakTransactionData['dakTransactionFullData']['0']['letterpriority'];
            //            echo $letterPriority.">>";die;
            //            echo $isForwarded.">>";die;
            if ($isForwarded == 't') {
                $result = $this->RIModel->doReceiveForwardableDakForSection($dak, $actionType, $usercode, $returnReason, $ec_postal_user_initiated_letter_id, $dispatchedBy, $letterPriority, $officer);
            } else {
                $result = $this->RIModel->doReceiveDakForSection($dak, $actionType, $usercode, $returnReason, $letterPriority, $officer);
            }
        }
        $data['receivedBySectionDetails'] = $this->RIModel->getReceivedBySectionDak($selectedCases);
        $data['initiatedReceivedBySectionDetails'] = $this->RIModel->getInitiatedReceivedBySectionDak($selectedCases);
        $this->db->transComplete();
        //        echo "<pre>";
        //        print_r($data);die;
        echo view('RI/Receipt/receivedBySectionReport', $data);
        exit();
    }


    public function getOfficersListBySection()
    {
        //       var_dump($_POST);
        //       die;
        if (!empty($_POST)) {
            $dealingSection = $_POST['dealingSection'];
        }
        $currentusercode = session()->get('login')['usercode'];
        $officersOfSectionArray = [];
        $officersListBySection = [];

        $officersOfSection = $this->RIModel->getOfficersListBySection($dealingSection);
        foreach ($officersOfSection as $officers) {
            if ($officers != "") {
                $registrar = $officers['registrar'];
                $allOfficers = explode(',', $registrar);
                $officersOfSectionArray = array_merge($officersOfSectionArray, $allOfficers);
                $additional_registrar = $officers['additional_registrar'];
                $allOfficers = explode(',', $additional_registrar);
                $officersOfSectionArray = array_merge($officersOfSectionArray, $allOfficers);
                $deputy_registrar = $officers['deputy_registrar'];
                $allOfficers = explode(',', $deputy_registrar);
                $officersOfSectionArray = array_merge($officersOfSectionArray, $allOfficers);
                $assistant_registrar = $officers['assistant_registrar'];
                $allOfficers = explode(',', $assistant_registrar);
                $officersOfSectionArray = array_merge($officersOfSectionArray, $allOfficers);
                $branch_officer = $officers['branch_officer'];
                $allOfficers = explode(',', $branch_officer);
                $officersOfSectionArray = array_merge($officersOfSectionArray, $allOfficers);
            }
        }
        //ADD SG START
        $sectionName =  $this->RIModel->getSectionNameBySection($dealingSection);
        if ($sectionName == 'OTHERS') {
            $SGlist = $this->RIModel->getSecretaryGeneral();
            foreach ($SGlist as $SG) {
                array_push($officersOfSectionArray, $SG['empid']);
            }
        }
        //ADD SG END
        $i = 0;
        foreach ($officersOfSectionArray as $empId) {
            if ($empId == '') {
                continue;
            }
            $officerDetail = $this->RIModel->getOfficerDetailByEmpId($empId);
            $usercode = $officerDetail[0]['usercode'];
            if ($currentusercode == $usercode) {
                continue;
            }
            $empid = $officerDetail[0]['empid'];
            $name = $officerDetail[0]['name'];
            $type_name = $officerDetail[0]['type_name'];
            $officersListBySection[$i] = array('usercode' => $usercode, 'empid' => $empid, 'name' => $name, 'empTypeName' => $type_name);
            $i++;
        }
        echo json_encode($officersListBySection);
    }



    public function getImagesForTransactionId()
    {
        //        var_dump($_POST);
        //        die;
        if (!empty($_POST)) {
            $id = $_POST['id'];
        }
        if (isset($id) && isset($id)) {
            $imageData = $this->RIModel->getImagesForTransactionId($id);
        }
        if (!empty($imageData)) {
            if (isset($imageData) && sizeof($imageData) > 0) {
                foreach ($imageData as $imageinfo) {

                    //echo base_url().$imageinfo['file_path']."/".$imageinfo['file_name'];

                    echo "<img src='" . base_url($imageinfo["file_path"] . "/" . $imageinfo["file_name"]) . "' class='img-responsive'>";
                }
            }
        } else {

            echo "0";
        }

        //          view('RI/Receipt/imagesForTransactionId.php',$data);

    }
}