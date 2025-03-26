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

    public function change_court_order_type($usercode="",$msg="")
    {
        $data['usercode'] = session()->get('login')['usercode'];
        $data['msg'] = $msg;
        session()->set('usercode', $data['usercode']);
        $data['caseTypes'] = $this->JudgesLibraryModel->getCaseType();
        $data['diaryNumberForSearch'] = '';

        if (!empty($this->request->getPost('diaryNumber'))) {
            pr($data['diaryNumberForSearch']);
            if ($this->request->getPost('optradio') == 'C') {
                $optradio = $this->request->getPost('optradio');
                $caseType = $this->request->getPost('caseType');
                $caseNo = $this->request->getPost('caseNo');
                $caseYear = $this->request->getPost('caseYear');
                $data['diaryNumberForSearch'] = $this->JudgesLibraryModel->getSearchDiaryAllFieldsC($caseType, $caseNo, $caseYear, null, null, $optradio);
            } elseif ($this->request->getPost('optradio') == 'D') {
                $optradio = $this->request->getPost('optradio');
                $diaryNumber = $this->request->getPost('diaryNumber');
                $diaryYear = $this->request->getPost('diaryYear');
                $data['diaryNumberForSearch'] = $this->JudgesLibraryModel->getSearchDiaryAllFieldsD($diaryNumber, $diaryYear, $optradio);
            }
            $DNumber_main = $data['diaryNumberForSearch'][0]['conn_key'];
            $with_all_connected = $data['diaryNumberForSearch'][0]['diary_no'];
            if ($DNumber_main != '' && $DNumber_main != null && $DNumber_main != 0) {
                $with_all_connected = $this->JudgesLibraryModel->getWithAllConnected($DNumber_main);
            }
            if ($with_all_connected != null) {
                $data['caseDetails'] = $this->JudgesLibraryModel->getCaseDetailsJudgementFlagChange($with_all_connected);
                pr($data['caseDetails']);
            } else if ($msg == "") {
                $data['msg'] = "No record found!!";
            }
        }
        return view('JudgesLibrary/NeutralCitation/change_court_order_type', $data);
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
        return view('JudgesLibrary/NeutralCitation/upload_old_judgments',$data);
    }
}
