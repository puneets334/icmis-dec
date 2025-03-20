<?php

namespace App\Controllers\Judicial\DirectDisposalCase;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\ConditionalDisposeModel;
use App\Models\Judicial\DirectDisposalCase\RemoveConditionDispose;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class RemoveDisposeCondition extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $ConditionalDisposeModel;
    private $RemoveConditionDispose;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->ConditionalDisposeModel = new ConditionalDisposeModel();
        $this->RemoveConditionDispose = new RemoveConditionDispose();
    }

    public function getCSRF()
    {
        return $this->response->setJSON([
            'csrf_token' => csrf_hash()
        ]);
    }


    public function index()
    {
        $data['app_name'] = "Disposal";

        $data['user_idd'] = $_SESSION['login']['usercode'];
        $usercode = $_SESSION['login']['usercode'];
        // $data['prev_cases']=$this->RemoveDisposeConditionModel->get_prev_cases($usercode);
        // $data['a_states'] = $this->RemoveDisposeConditionModel->get_HighCourt_State();
        $data['case_types'] = $this->ConditionalDisposeModel->case_types();
        // echo "<pre>";
        // print_r($data); die;
        return view('Judicial/DirectDisposalCase/RemoveDisposeCondition_view', $data);
    }


    public function get_details()
    {
        $validationRules = [
            'search_type' => 'required|min_length[1]|max_length[1]',
        ];

        $searchType = $this->request->getPost('search_type');
        if ($searchType === '2') {
            $validationRules = array_merge($validationRules, [
                'diary_number_list' => 'required|min_length[1]|max_length[15]',
                'diary_year_list' => 'required|min_length[4]',
            ]);
        } elseif ($searchType === '1') {
            $validationRules = array_merge($validationRules, [
                'case_type_list' => 'required',
                'case_number_list' => 'required|min_length[1]|max_length[15]',
                'case_year_list' => 'required|min_length[4]',
            ]);
        }

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }

        $ct = $this->request->getPost('case_type_list');
        $cn = $this->request->getPost('case_number_list');
        $cy = $this->request->getPost('case_year_list');
        $dyr = $this->request->getPost('diary_number_list');
        $dn = $this->request->getPost('diary_year_list');
        $ctd = $this->request->getPost('case_type_disp') ?? '';
        $cnd = $this->request->getPost('case_number_disp') ?? '';
        $cyd = $this->request->getPost('case_year_disp') ?? '';
        $dnd = $this->request->getPost('diary_number_disp') ?? '';
        $dyd = $this->request->getPost('diary_year_disp') ?? '';

        $result = $this->RemoveConditionDispose->get_case_details($ct, $cn, $cy, $dyr, $dn, $ctd, $cnd, $cyd, $dnd, $dyd);
        // pr($result);
        if (!empty($result) && isset($result[0]['case_diary'])) {
            $caseDiary = $result[0]['case_diary'];
            $caseStatus = $result[0]['c_status'];
            $caseTitle = $result[0]['case_title'];
            $daCode = $result[0]['dacode'];

            // Fetch connected details
            $connData = $this->RemoveConditionDispose->get_conn_details($caseDiary);

            // Prepare response data
            $response = [
                'status' => 'success',
                'case_diary' => $caseDiary,
                'case_status' => $caseStatus,
                'case_title' => $caseTitle,
                'dacode' => $daCode,
                'conn_details' => $connData ?? []
            ];
        } else {
            // Handle case where result is empty or missing required fields
            $response = [
                'status' => 'error',
                'message' => 'Case details not found or incomplete.'
            ];
        }
        // Print the response in JSON format
        return json_encode($response);
    }


    public function get_Restrict_Cases_History()
    {
        $validationRules = [
            'list_diary' => 'required|min_length[1]|max_length[15]',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }

        $list_diary = $this->request->getPost('list_diary');
        $result = $this->RemoveConditionDispose->get_Restrict_Cases_History($list_diary);
        //pr($result);
        if ($result) {
            $response = [
                'status' => 'success',
                'data' => $result
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Case details not found or incomplete.'
            ];
        }

        echo json_encode($response);
    }

    // public function delete_Restricted_Case()
    // {
    //     $usercode   = isset($_POST['usercode']) ? $_POST['usercode'] : '';
    //     $list_diary = isset($_POST['list_diary']) ? $_POST['list_diary'] : '';
    //     $deleteStatus = $this->RemoveConditionDispose->delete_Restricted_Case($list_diary, $usercode);
    //     if ($deleteStatus == 'T') {
    //         $data['Remove_Case'] = 'Deleted';
    //     } else {
    //         $data['Remove_Case'] = 'Not Deleted';
    //     }

    //     return $this->response->setJSON($data);
    // }
    public function delete_Restricted_Case()
    {
        $validationRules = [
            'list_diary' => 'required|min_length[1]|max_length[15]',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('message_error', 'Validation errors occurred');
            return redirect()->back()->withInput();
        }
        
        $usercode = $this->request->getPost('usercode');
        $list_diary = $this->request->getPost('list_diary');

        if (empty($usercode) || empty($list_diary)) {
            return $this->response->setJSON([
                'Remove_Case' => 'Not Deleted',
                'message' => 'Invalid input provided.'
            ]);
        }

        $deleteStatus = $this->RemoveConditionDispose->delete_Restricted_Case($list_diary, $usercode);

        if ($deleteStatus == 'T') {
            $response = ['Remove_Case' => 'Deleted'];
        } else {
            $response = [
                'Remove_Case' => 'Not Deleted',
                'message' => 'Deletion failed. Please contact Computer Cell.'
            ];
        }

        return json_encode($response);
    }
}
