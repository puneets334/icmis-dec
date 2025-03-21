<?php

namespace App\Controllers\Exchange\MovementOfDoc;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;

class MovementOfDocument extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $MovementOfDocumentModel;

    function __construct()
    {
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->MovementOfDocumentModel = new MovementOfDocumentModel();
    }

    public function bulkDispatch()
    {
        return view('Exchange/movementOfDoc/bulkDispatch');
    }

    public function get_s_file_rec()
    {
        $ucode = session()->get('login')['usercode'];
        $module = $this->request->getVar('module');

        $ct = $this->request->getVar('ct');
        $cn = $this->request->getVar('cn');
        $cy = $this->request->getVar('cy');

        if (!empty($ct)) {
            $diaryData = $this->MovementOfDocumentModel->getDiaryNumber($ct, $cn, $cy);

            if ($diaryData) {
                $d_no = $diaryData['dn'];
                $d_yr = $diaryData['dy'];
                $ctTypeData = $this->MovementOfDocumentModel->getCaseTypeDescription($ct);

                if ($ctTypeData) {
                    $t_slpcc = $ctTypeData['short_description'] . " " . $diaryData['crf1'] . " - " . $diaryData['crl1'] . " / " . $cy;
                }
            } else {
                return '<p align=center>
                    <font color=red>Case Not Found</font>
                </p>';
            }
        }

        return view('Exchange/movementOfDoc/get_s_file_rec', compact('ucode', 'd_no', 'd_yr', 't_slpcc', 'module'));
    }

    public function bulk_dispatch_pro()
    {
        $data['ucode'] = session()->get('login')['usercode'];
        $data['model'] = $this->MovementOfDocumentModel;
        $data['select_rs'] = $this->MovementOfDocumentModel->getRecentDocuments($data['ucode']);

        return view('Exchange/movementOfDoc/bulk_dispatch_pro', $data);
    }





    public function bulkReceive()
    {
        $ucode = session()->get('login')['usercode'];
        $data['ucode'] = $ucode;
        $condition = " and 1=1";
        $user = $this->MovementOfDocumentModel->getUserSection($ucode);
        if ($user) {
            $officerSection = $user['section'];
            $userType = $user['usertype'];

            if ($userType == 14 || $userType == 9) {
                // Get all DA user codes
                $allDAUsercodes = $this->MovementOfDocumentModel->getAllDAUsercodes($officerSection);
                if ($allDAUsercodes) {
                    $condition .= " and a.disp_to in (" . $allDAUsercodes['allDA'] . ")";
                }
            } else {
                $condition .= " and a.disp_to = " . $this->db->escape($ucode);
            }
        }

        // Current date logic
        $cur_date = date('d-m-Y');
        $data['cur_date'] = date('d-m-Y');
        $data['new_date'] = date('d-m-Y', strtotime($cur_date . ' + 60 days'));
        $data['select_rs'] = $this->MovementOfDocumentModel->get_select_rs($condition);
        $data['model']=$this->MovementOfDocumentModel;
        

        return view('Exchange/movementOfDoc/bulkReceive', $data);
    }

    public function save_receive()
    {
        $ucode = session()->get('login')['usercode'];
        $alldata = $this->request->getGet('alldata');
        $updateStatus = $this->MovementOfDocumentModel->updateRecords($alldata, $ucode);
        if ($updateStatus) {
            echo 'Update Successfully!';
        }
    }

    public function verifiedDefective()
    {
        $usercode = session()->get('login')['usercode'];
        $data['output_html'] = $this->MovementOfDocumentModel->verified_defective();
        return view('Exchange/movementOfDoc/verifiedDefective', $data);
    }

















    //........created by Deepak........//
    public function oldVerify()
    {
        $data['cases'] = $this->MovementOfDocumentModel->getOldCases();
        return view('Exchange/movementOfDoc/oldVerify', $data);
    }

    public function oldVerifyProcess()
    {
        $usercode = session()->get('login')['usercode'];
        $result = $this->MovementOfDocumentModel->old_verify_process();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function verify()
    {
        $usercode = session()->get('login')['usercode'];
        $data['output_html'] = $this->MovementOfDocumentModel->verify_defect();
        return view('Exchange/movementOfDoc/verify', $data);
    }

    public function verifySave()
    {
        // pr($_REQUEST);
        $usercode = session()->get('login')['usercode'];
        $update = $this->MovementOfDocumentModel->verify_save();
    }

   
}
