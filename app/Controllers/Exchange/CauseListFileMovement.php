<?php

namespace App\Controllers\Exchange;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\SqlReportModel;
use App\Models\Court\CourtMasterModel;

class CauseListFileMovement extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $SqlReportModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->SqlReportModel = new SqlReportModel();
    }
    
    public function Matters_Listed()
    {
        return view('Exchange/matter_listed');
    }

    public function fetchData()
    {
        $courtNo = $this->request->getPost('courtNo');
        $date1 = $this->request->getPost('date1');
        $model = new Matters_Listed();
        $data['records'] = $model->getListingData($courtNo, $date1);
        return view('Exchange/matters_report', $data);
    }

    public function transaction()
    {
        $data['cases'] = $this->Transaction->getAllCaseType();
        return view('Exchange/transaction', $data);
    }

    public function transactionProcess()
    {
        $request = \Config\Services::request();
        $searchby = $request->getPost('searchby');
        $caseType = !empty($request->getPost('caseType')) ? $request->getPost('caseType') : Null;
        $caseNo = !empty($request->getPost('caseNo')) ? $request->getPost('caseNo') : Null;
        $caseYear = !empty($request->getPost('caseYear')) ? $request->getPost('caseYear') : Null;
        $dNo = !empty($request->getPost('dNo')) ? $request->getPost('dNo') : Null;
        $dYear = !empty($request->getPost('dYear')) ? $request->getPost('dYear') : Null;
        $data['transactionData'] = $this->Transaction->transaction_process($searchby, $caseType, $caseNo, $caseYear, $dNo, $dYear);
        return view('Exchange/transaction_process',$data);
    }
    
    public function transactionData()
    {
        $request = \Config\Services::request();
        $searchby = $request->getPost('searchby');
        $caseType = $request->getPost('caseType');
        $caseNo = $request->getPost('caseNo');
        $caseYear = $request->getPost('caseYear');
        $dNo = $request->getPost('dNo');
        $dYear = $request->getPost('dYear');
        $model = new Transaction();
        $result = $model->fetchTransactionData($searchby, $caseType, $caseNo, $caseYear, $dNo, $dYear);
        return $this->response->setJSON($result);
    }

    public function receiveFromDA()
    {
        return view('Exchange/receive_da');
    }

    public function casesToReceiveFromDA()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $causelistDate = date('Y-m-d', strtotime($request->getPost('causelistDate')));
        $data['caseList']=$this->CauseListFileMovementModel->getCasesToReceiveFromDA($causelistDate, $usercode, 1);
        return view('Exchange/dataToReceiveFileFromDA',$data);
    }

    public function doReceiveFromDA()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $result = 0;
        $selectedCases = $request->getPost('selectedCases');
        $action = $request->getPost('action');
        foreach($selectedCases as $index => $case)
        {
            $dataForMovementTransaction = array(
                'causelist_file_movement_id' => $case,
                'ref_file_movement_status_id' => $action,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP(),
                'updated_by' => session()->get('login')['usercode'],
                'create_modify' => date("Y-m-d H:i:s")
            );
            $this->CauseListFileMovementModel->saveTransactionDetails($dataForMovementTransaction);
            $result++;
        }
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
            'msg' => 'Data retrieved successfully.'
        ]);
    }

    public function receiveFromCM()
    {
        $usercode = session()->get('login')['usercode'];
        $data['caseList']=$this->CauseListFileMovementModel->getCasesToReceiveFromCM($usercode);
        return view('Exchange/receive_cm', $data);
    }

    public function doReceiveFromCM()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $selectedCases = $request->getGet('selectedCases');
        $result = 0;
        $action = 5; //File received by dealing assistant
        foreach($selectedCases as $index => $case)
        {
            $dataForMovementTransaction = array(
                'causelist_file_movement_id' => $case,
                'ref_file_movement_status_id' => $action,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP(),
                'updated_by' => session()->get('login')['usercode'],
                'create_modify' => date("Y-m-d H:i:s")
            );
            $this->CauseListFileMovementModel->saveTransactionDetails($dataForMovementTransaction);
            $result++;
        }
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
            'msg' => 'Data retrieved successfully.'
        ]);
    }

    public function sendBackToDA()
    {
        return view('Exchange/sendBackToDA');
    }

    public function casesForSendBackToDA()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $causelistDate = date('Y-m-d', strtotime($request->getPost('causelistDate')));
        $data['causelistDate'] = $causelistDate;
        $data['attendants']=$this->CauseListFileMovementModel->getAttendant();
        $data['caseList']=$this->CauseListFileMovementModel->getCasesForSendBackToDA($causelistDate,$usercode,1);
        return view('Exchange/dataToSendBackToDA',$data);
    }

    public function doSendBackToDA()
    {   
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $selectedCases = $request->getPost('selectedCases');
        $attendant = $request->getPost('attendant');
        $result = 0;
        $status_id = 4;
        foreach($selectedCases as $index=>$case)
        {
            $dataForMovementTransaction = array(
                'causelist_file_movement_id' => $case,
                'ref_file_movement_status_id' => $status_id,
                'attendant_usercode'=> $attendant,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP(),
                'updated_by' => session()->get('login')['usercode'],
                'create_modify' => date("Y-m-d H:i:s")
            );
            $this->CauseListFileMovementModel->saveTransactionDetails($dataForMovementTransaction);
            $result++;
        }
        return $this->response->setJSON([
            'status' => true,
            'data' => $result
        ]);
    }

    public function sendFileToCM()
    {
        return view('Exchange/sendFileToCM_NSH');
    }

    public function listedCases()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $causelistDate = date('Y-m-d', strtotime($_REQUEST['causelistDate']));
        $courtNo = $request->getPost('courtNo');
        $data['causelistDate'] = $causelistDate;
        $data['caseList'] = $this->CauseListFileMovementModel->getListedCases($causelistDate, $courtNo, $usercode);
        $data['cmnsh'] = $this->CourtMasterModel->getCmNsh();
        $data['attendants'] = $this->CauseListFileMovementModel->getAttendant();
        return view('Exchange/listedCases',$data);
    }

    public function dispatchFileToCM()
    {
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $causelistDate = date('Y-m-d', strtotime($request->getPost('causelistDate')));
        $selectedCases = $request->getPost('selectedCases');
        $dacodes = $request->getPost('dacodes');
        $attendant = $request->getPost('attendant');
        $cmnshusercodes = $request->getPost('cmnshusercodes');


        $result = 0;
        foreach($selectedCases as $index=>$case)
        {
            $diaryAndRoster = explode("#",$case);
            $dataForMovement = array(
                'diary_no' => $diaryAndRoster[0],
                'next_dt' => $causelistDate,
                'roster_id' => $diaryAndRoster[1],
                'dacode' => $dacodes[$index],
                'cm_nsh_usercode' => $cmnshusercodes[$index],
                'ref_file_movement_status_id' => 1,//1 for File sent to CM(NSH)
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by_ip' => getClientIP(),
                'updated_by' => session()->get('login')['usercode'],
                'create_modify' => date("Y-m-d H:i:s")
            );
            $this->CauseListFileMovementModel->saveDispatchFileToCM($dataForMovement,$attendant);
            $result++;
        }
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function sqlReport()
    {
        return view('Exchange/sql_report');
    }

    public function processReport()
    {
        $request = \Config\Services::request();
        $fromDate = $request->getPost('FDate');
        $toDate = $request->getPost('TDate');
        $usercode = session()->get('login')['usercode'];
        if ($fromDate && $toDate) {
            $frmDate = date("Y-m-d", strtotime($fromDate));
            $toDate = date("Y-m-d", strtotime($toDate));
            $data['records'] = $this->SqlReportModel->getProcessReport($frmDate, $toDate, $usercode);
            return view('Exchange/processReport',$data);
        }
    
        return $this->response->setJSON(['success' => false,'message' => 'Invalid dates.']);
    }

    public function getSQLProcessReport()
    {
        $request = \Config\Services::request();
        $tDate = date("Y-m-d", strtotime($request->getGet('date')));
        $sId = $request->getGet('id');
        $usercode = session()->get('login')['usercode'];
        $data['records'] = $this->SqlReportModel->getSQLProcessReport($tDate, $sId, $usercode);
        return view('Exchange/getSQLProcessReport',$data);
    }

    public function dispatchClCases()
    {
        $usercode = session()->get('login')['usercode'];
        $data['dispatch_data'] = $this->CauseListFileMovementModel->dispatch_cl_cases($usercode);
        return view('Exchange/dispatchClCases',$data);
    }

}