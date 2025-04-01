<?php

namespace App\Controllers\Exchange;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Court\CourtMasterModel;
use App\Models\Exchange\FileMovementModel;
use App\Models\Common\Dropdown_list_model;

class FileMovement extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $FileMovementModel;
    public $Dropdown_list_model;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->FileMovementModel = new FileMovementModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
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
        $request = \Config\Services::request();
        $usercode = session()->get('login')['usercode'];
        $data['rd'] = $request->getVar('rd');
        $data['mf'] = $request->getVar('mf');
        $data['rur'] = $request->getVar('rur');
        $data['ct'] = $request->getVar('ct');
        $data['fdt'] = date('Y-m-d', strtotime($request->getVar('dt1')));
        $data['tdt'] = date('Y-m-d', strtotime($request->getVar('dt2')));
        $data['res_sq'] = $this->FileMovementModel->dispatch_receive_report_process($usercode, $data['rd'], $data['mf'], $data['rur'], $data['ct'], $data['fdt'], $data['tdt']);
        return view('Exchange/fileMovement/dispatch_receive_report_process',$data);
    }

    public function dispatch()
    {
        $data['cases'] = $this->FileMovementModel->getCasesForDispatch();
        return view('Exchange/fileMovement/dispatch', $data);
    }

    public function getSFileRec()
    {
        $request = \Config\Services::request();
        $ucode = session()->get('login')['usercode'];
        $module = $request->getPost('module');
        $caseType = $request->getPost('ct');
        $caseNumber = $request->getPost('cn');
        $caseYear = $request->getPost('cy');
        $result = $this->FileMovementModel->get_s_file_rec1($ucode, $module);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function getUserOptions()
    {
        $request = \Config\Services::request();
        $ucode = session()->get('login')['usercode'];
        $dept = !empty($request->getPost('dept')) ? $request->getPost('dept') : '';
        $sec = !empty($request->getPost('sec')) ? $request->getPost('sec') : '';
        $desig = !empty($request->getPost('desig')) ? $request->getPost('desig') : '';

        $result = $this->FileMovementModel->user_options($ucode, $dept, $sec, $desig);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function userMgmtMultiple()
    {
        $request = \Config\Services::request();
        $requestKey = $request->getVar('key');
        $setter = !empty($request->getPost('setter')) ? $request->getPost('setter') : '';
        $deptname = !empty($request->getPost('deptname')) ? $request->getPost('deptname') : '';
        $cur_user_type = !empty($request->getPost('cur_user_type')) ? $request->getPost('cur_user_type') : '';
        $section = !empty($request->getPost('section')) ? $request->getPost('section') : '';

        return $result = $this->FileMovementModel->user_mgmt_multiple($requestKey, $setter, $deptname, $cur_user_type, $section);
        //return $this->response->setJSON(['status' => true,'data' => $result,]);
    }

    public function saveDispatchedRecord()
    {
        $request = \Config\Services::request();
        $result = $this->FileMovementModel->save_record();
        $module = $request->getPost('module');
        if($module == 'dispatch') {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'message' => 'Successfully Dispatched'
            ]);
        } else {
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