<?php

namespace App\Controllers\Exchange;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Court\CourtMasterModel;
use App\Models\Exchange\FileMovementModel;

class FileMovement extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $FileMovementModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->FileMovementModel = new FileMovementModel();
    }

    public function dispatchReceiveReport()
    {
        $data['cases'] = $this->Transaction->getAllCaseType();
        return view('Exchange/fileMovement/dispatch_receive_report',$data);
    }

    public function getCaseTypeByMisOrReg()
    {
        $cases = $this->FileMovementModel->get_case_type();
        if(count($cases) > 0)
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $cases,
                'msg' => 'Cases found succesfully.'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => false,
                'data' => [],
                'msg' => 'No record found'
            ]);
        }
    }

    public function dispatchReceiveReportProcess()
    {
        $data['rd'] = $_REQUEST['rd'];
        $data['mf'] = $_REQUEST['mf'];
        $data['rur'] = $_REQUEST['rur'];
        $data['ct'] = $_REQUEST['ct'];
        $data['fdt'] = date('Y-m-d', strtotime($_REQUEST['dt1']));
        $data['tdt'] = date('Y-m-d', strtotime($_REQUEST['dt2']));

        $data['res_sq'] = $this->FileMovementModel->dispatch_receive_report_process();
        return view('Exchange/fileMovement/dispatch_receive_report_process',$data);
    }

    public function dispatch()
    {
        $data['cases'] = $this->FileMovementModel->getCasesForDispatch();
        return view('Exchange/fileMovement/dispatch', $data);
    }

    public function getSFileRec()
    {
        $ucode = session()->get('login')['usercode'];
        $module = $_REQUEST['module'];

        $result = $this->FileMovementModel->get_s_file_rec($ucode,$module);
        // echo "result"."<br>";
        // pr($result);
        // return view('Exchange/fileMovement/dispatch', $result);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function getUserOptions()
    {
        $ucode = session()->get('login')['usercode'];
        $dept = !empty($_REQUEST['dept']) ? $_REQUEST['dept'] : '';
        $sec = !empty($_REQUEST['sec']) ? $_REQUEST['sec'] : '';
        $desig = !empty($_REQUEST['desig']) ? $_REQUEST['desig'] : '';

        $result = $this->FileMovementModel->user_options();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function userMgmtMultiple()
    {
        $result = $this->FileMovementModel->user_mgmt_multiple();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function saveDispatchedRecord()
    {
        $result = $this->FileMovementModel->save_record();
        if($_REQUEST['module'] == 'dispatch')
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'message' => 'Successfully Dispatched'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'message' => 'Successfully Received'
            ]);
        }
    }

    public function receive()
    {

        $data['cases'] = $this->FileMovementModel->getCasesForDispatch();
        return view('Exchange/fileMovement/receive', $data);
    }
}