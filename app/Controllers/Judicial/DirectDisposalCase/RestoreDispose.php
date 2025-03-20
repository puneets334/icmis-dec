<?php

namespace App\Controllers\Judicial\DirectDisposalCase;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\RestoreDisposeModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class RestoreDispose extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $RestoreDisposeModel;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->RestoreDisposeModel = new RestoreDisposeModel();
    }



    public function index()
    {
        $data['app_name'] = "Restoration";
        $usercode = $_SESSION['login']['usercode'];
        $data['usercode'] = $usercode;
        $data['case_type'] = $this->RestoreDisposeModel->case_types();
        $data['details'] = $this->RestoreDisposeModel->user_details($usercode);
        return view('Judicial/DirectDisposalCase/RestoreDispose_view', $data);
    }

    public function get_details()
    {
        $case_type = isset($_POST['case_type']) ? $_POST['case_type'] : '';
        $case_number = isset($_POST['case_number']) ? $_POST['case_number'] : '';
        $case_year = isset($_POST['case_year']) ? $_POST['case_year'] : '';
        $diary_number = isset($_POST['diary_number']) ? $_POST['diary_number'] : '';
        $diary_year = isset($_POST['diary_year']) ? $_POST['diary_year'] : '';

        if (($case_type != '' && $case_year != '' && $case_number != '') || ($diary_number != '' && $diary_year != '')) {

            $case_detail = $this->RestoreDisposeModel->get_case_details($case_type, $case_number, $case_year, $diary_number, $diary_year);

            if (!empty($case_detail) && $case_detail[0]['case_diary'] != '') {
                $data_array['ma_details'] = $this->RestoreDisposeModel->get_ma_details($case_detail[0]['case_diary'], $case_detail[0]['fil_dt'], $case_detail[0]['fil_no']);
            }

            /*check listing*/
            if (!empty($case_detail) && $case_detail[0]['case_diary'] != '') {
                $data_array['Check_Case_listing'] = $this->RestoreDisposeModel->Check_Case_listing($case_detail[0]['case_diary']);
            }
            
            $data_array['case_detail'] = (!empty($case_detail)) ? $case_detail : 'false';

            //return $this->response->setJSON($data_array);
            echo json_encode($data_array);
        }
    }

    public function restore_case()
    {
        $data['app_name'] = "Restoration";
        $case_diary = $this->input->post('case_diary');
        $ma_diary = $this->input->post('ma_diary');
        $restore_date = $this->input->post('restore_date');
        $usercode = $this->input->post('usercode');
        $ianum = $this->input->post('ianum');
        $restore_date = date('Y-m-d', strtotime($restore_date));
    if (isset($case_diary) && isset($ma_diary)) {
            $this->RestoreDisposeModel->restor_case($case_diary, $ma_diary, $restore_date, $usercode, $ianum);
        }

        return true;
    }
}
