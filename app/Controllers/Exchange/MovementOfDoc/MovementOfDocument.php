<?php

namespace App\Controllers\Exchange\MovementOfDoc;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Exchange\MovementOfDocumentModel;
use App\Models\Court\CourtMasterModel;
use App\Models\Common\Dropdown_list_model;


class MovementOfDocument extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $MovementOfDocumentModel;
    public $Dropdown_list_model;

    function __construct()
    {
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->MovementOfDocumentModel = new MovementOfDocumentModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
    }

    public function bulkDispatch()
    {
        return view('Exchange/movementOfDoc/bulkDispatch');
    }

    public function get_s_file_rec()
    {
        $request = \Config\Services::request();
        $ucode = session()->get('login')['usercode'];
        $module = $request->getVar('module');
        $ct = $request->getVar('ct');
        $cn = $request->getVar('cn');
        $cy = $request->getVar('cy');

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
           
            foreach($user as $row)
            {
                $officerSection=$row['section'];
                $userType=$row['usertype'];
            }
          

            if ($userType == 14 || $userType == 9) {
                $allDAUsercodes = $this->MovementOfDocumentModel->getAllDAUsercodes($officerSection);
               
            
                if (!empty($allDAUsercodes) && isset($allDAUsercodes['allda']) && !is_null($allDAUsercodes['allda'])) {
                  
                    $condition .= " and a.disp_to in (" . $allDAUsercodes['allda'] . ")";
                    
                }
            } else {
               
                $condition .= " and a.disp_to = " . $this->db->escape($ucode);
            }
            
            
        }

        $cur_date = date('d-m-Y');
        $data['cur_date'] = date('d-m-Y');
        $data['new_date'] = date('d-m-Y', strtotime($cur_date . ' + 60 days'));
        $data['select_rs'] = $this->MovementOfDocumentModel->get_select_rs($condition);
        $data['model'] = $this->MovementOfDocumentModel;
        return view('Exchange/movementOfDoc/bulkReceive', $data);
    }

    public function save_receive()
    {
        $request = \Config\Services::request();
        $ucode = session()->get('login')['usercode'];
        $alldata = $request->getGet('alldata');
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

    public function save_dispatch()
    {
        $request = \Config\Services::request();
        $session = session();
        $ucode = $session->get('login')['usercode']; // Get user ID from session
        $alldata = $request->getPost('alldata');

        if (!$alldata) {
            return $this->response->setJSON(['error' => 'No data received']);
        }

        $errors = [];

        foreach ($alldata as $value) {
            $new_value = explode('-', $value);
            $chk_if_move_rs = $this->MovementOfDocumentModel->verified_defectives($new_value[0], $new_value[1], $new_value[2], $new_value[3], $new_value[4]);
            if (empty($chk_if_move_r)) {
                $insert = $this->MovementOfDocumentModel->insertDatas($new_value[0], $new_value[1], $new_value[2], $new_value[3], $new_value[4], $ucode, $new_value[5], date('Y-m-d H:i:s'));
            }
        }
    }

    public function oldVerify()
    {
        $data['cases'] = $this->MovementOfDocumentModel->getOldCases();
        
        return view('Exchange/movementOfDoc/oldVerify', $data);
    }

    public function oldVerifyProcess()
    {
        $request = \Config\Services::request();
        $data['usercode'] = session()->get('login')['usercode'];
        $data['d_no']= $request->getPost('d_no');
        $data['d_yr'] = $request->getPost('d_yr');
        $data['ct'] = $request->getPost('ct');
        $data['cn'] = $request->getPost('cn');
        $data['cy'] = $request->getPost('cy');
        $data['tab'] = $request->getPost('tab');
        $data['model'] = $this->MovementOfDocumentModel;
        $data['model1'] = $this->Dropdown_list_model;
        return view('Exchange/movementOfDoc/oldVerifyProcess', $data);
        
    }

    public function verify()
    {
        $usercode = session()->get('login')['usercode'];
        $data['output_html'] = $this->MovementOfDocumentModel->verify_defect();
        return view('Exchange/movementOfDoc/verify', $data);
    }

    public function verifySave()
    {
        $usercode = session()->get('login')['usercode'];
        $update = $this->MovementOfDocumentModel->verify_save();
    }
}
