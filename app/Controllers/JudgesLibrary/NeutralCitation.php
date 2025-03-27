<?php

namespace App\Controllers\JudgesLibrary;

use App\Controllers\BaseController;
use App\Models\JudgesLibrary\JudgesLibraryModel;

class NeutralCitation extends BaseController
{
    protected $JudgesLibraryModel;
    public function __construct()
    {
        $this->JudgesLibraryModel = new JudgesLibraryModel();
    }

    public function change_court_order_type($usercode = "", $msg = "")
    {
        $data['usercode'] = session()->get('login')['usercode'];
        $data['msg'] = $msg;
        $data['caseTypes'] = $this->JudgesLibraryModel->getCaseType();
        $data['usercode'] = $usercode;
        $diaryNumberForSearch = null;


        return view('JudgesLibrary/NeutralCitation/change_court_order_type', $data);
    }

    public function change_court_order_type_new()
    {
      
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
        }
        // Your existing logic for searching cases...
        $msg = "";
        $optradio = $this->request->getPost('optradio');
        $caseType = $this->request->getPost('caseType');
        $caseNo = $this->request->getPost('caseNo');
        $caseYear = $this->request->getPost('caseYear');
        $diaryNumber = $this->request->getPost('diaryNumber');
        $diaryYear = $this->request->getPost('diaryYear');

       
            $diaryNumberForSearch = $this->JudgesLibraryModel->getSearchDiaryAllFields($caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear, $optradio);
            pr($diaryNumberForSearch);
       
        $data['diaryNumberForSearch'] = $diaryNumberForSearch;
        $DNumber_main = $diaryNumberForSearch->conn_key;
        $with_all_connected = $diaryNumberForSearch->diary_no;
        if ($DNumber_main != '' && $DNumber_main != null && $DNumber_main != 0) {
            $with_all_connected = $this->JudgesLibraryModel->getWithAllConnected($DNumber_main);
        }
        if ($with_all_connected != null) {
            $data['caseDetails'] = $this->JudgesLibraryModel->getCaseDetailsJudgementFlagChange($with_all_connected);
            //var_dump($data['caseDetails']);
            //exit;
        } else if ($msg == "") {
            $data['msg'] = "No record found!!";
        }
    }



    public function getListedDetailsForJudgmentFlag()
    {
        // pr('sfsd');
    }

    public function upload_old_judgments()
    {
        $usercode = session()->get('login')['usercode'];
        $data['usercode'] = session()->set('usercode', $usercode);
        $data['caseTypes'] = $this->JudgesLibraryModel->getCaseType();
        return view('JudgesLibrary/NeutralCitation/upload_old_judgments', $data);
    }
}
