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
        $data['usercode'] = session()->get('login')['usercode'];
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
        $data['diaryNumberForSearch'] = $diaryNumberForSearch;
        $DNumber_main = $diaryNumberForSearch['conn_key'];
        $with_all_connected = $diaryNumberForSearch['diary_no'];
        
        if ($DNumber_main != '' && $DNumber_main != null && $DNumber_main != 0) {
            $with_all_connected = $this->JudgesLibraryModel->getWithAllConnected($DNumber_main);
          
        }

        if (!empty($with_all_connected) && !empty($with_all_connected['conn_list'])) {
            $diary_nos = $with_all_connected['conn_list'];
            $data['caseDetails'] = $this->JudgesLibraryModel->getCaseDetailsJudgementFlagChange($diary_nos);
        } elseif ($msg == "") {
            $data['caseDetails'] = '';
            $data['msg'] = "No record found!!";
        }
        return view('JudgesLibrary/NeutralCitation/change_court_order_type_data', $data);
    }



    public function getListedDetailsForJudgmentFlag()
    {
        $id = $this->request->getPost('id');
        $usercode = session()->get('login')['usercode'];
        if (!$id) {
            return $this->response->setJSON(['error' => 'Invalid request.']);
        }

        $listedInfo = explode('##', $id);

        $data = [
            'id' => $listedInfo[0] ?? null,
            'file_address' => $listedInfo[1] ?? null,
            'order_type_short' => $listedInfo[2] ?? null,
            'tbl_name' => $listedInfo[3] ?? null,
        ];

        return view('JudgesLibrary/NeutralCitation/showJudgmentFlagDetails', $data);
    }

    public function changeJudgementFlag()
    {
        $session = session(); // Get session instance

        // Get POST data safely
        $usercode = $this->request->getPost('usercode');
        $listingDates = $this->request->getPost('listingDates');
        $orderType = $this->request->getPost('orderType');

        // Validate input
        if (!$listingDates) {
            $session->setFlashdata('flsh_msg', "Error: Invalid request.");
            return redirect()->to(base_url("JudgesLibrary/NeutralCitation/change_court_order_type/$usercode"));
        }

        // Explode data to extract values
        $data = explode('##', $listingDates);
        $id = $data[0] ?? null;
        $type = $data[2] ?? null;
        $tbl_name = $data[3] ?? null;
        $msg = '';

        // Map order type to short code
        $orderTypeShort = match ($orderType) {
            'Judgement' => 'J',
            'FinalOrder' => 'FO',
            'Order' => 'O',
            default => null,
        };

        if (!$orderTypeShort) {
            $session->setFlashdata('flsh_msg', "Error: Wrong Flag");
            return redirect()->to(base_url("JudgesLibrary/NeutralCitation/change_court_order_type/$usercode"));
        }

        // Check if change is needed
        if ($orderTypeShort == $type) {
            $session->setFlashdata('flsh_msg', "No changes");
            return redirect()->to(base_url("JudgesLibrary/NeutralCitation/change_court_order_type/$usercode"));
        }

        // Fetch details
        $res = $this->JudgesLibraryModel->getDiaryJudmentFinalOrderDetail($id, $tbl_name);

        if (!empty($res)) {

            $fileProceedingDetail = $res[0] ?? null;

            if (!empty($fileProceedingDetail)) {
                $result2 = [
                    "tbl_name" => $tbl_name,
                    "modified_date" => date('Y-m-d H:i:s'),
                    "modified_by" => $usercode
                ];

                $combined_result = array_merge($fileProceedingDetail, $result2);
                $this->JudgesLibraryModel->insertOrdernetDeleted($combined_result);

                // Update the flag
                $result = $this->JudgesLibraryModel->updateOrdernetFlag($id, $orderTypeShort, $usercode, $tbl_name);
                $msg = $result ? "Success" : "Not Updated";
            }
        } else {
            $msg = "ROP is not uploaded for this case yet.";
        }

        // Set flash message and redirect
        $session->setFlashdata('flsh_msg', $msg);
        return redirect()->to(base_url("JudgesLibrary/NeutralCitation/change_court_order_type/$usercode"));
    }



    public function upload_old_judgments()
    {
        $usercode = session()->get('login')['usercode'];
        $data['usercode'] = session()->set('usercode', $usercode);
        $data['caseTypes'] = $this->JudgesLibraryModel->getCaseType();
        return view('JudgesLibrary/NeutralCitation/upload_old_judgments', $data);
    }
}
