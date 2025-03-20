<?php

namespace App\Controllers\Judicial\DirectDisposalCase;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\ConditionalDisposeModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Conditional_Dispose extends BaseController
{
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $ConditionalDisposeModel;

    function __construct(){   
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->ConditionalDisposeModel = new ConditionalDisposeModel();
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
        $data['case_types'] = $this->ConditionalDisposeModel->case_types();
        $data['prev_cases']=$this->ConditionalDisposeModel->get_prev_cases($usercode);
        //pr($data['prev_cases']);
        if ($data['prev_cases'] === false)
        {
            $data['prev_cases'] = []; // Assign an empty array if no cases are found
        }
        $data['a_states'] = $this->ConditionalDisposeModel->get_HighCourt_State();
        // echo "<pre>";
        // print_r($data); die;
        // $this->load->view('dispose_condition_update_case');
        return view('Judicial/DirectDisposalCase/dispose_condition_update_case', $data);
    }


    public function get_details(){
        $data = $_POST;
        $case_type_list = isset($data['case_type_list']) ? $data['case_type_list'] : '' ;
        $case_number_list = isset($data['case_number_list']) ? $data['case_number_list'] : '' ;
        $case_year_list = isset($data['case_year_list']) ? $data['case_year_list'] : '' ;
        $diary_number_list = isset($data['diary_number_list']) ? $data['diary_number_list'] : '' ;
        $diary_year_list = isset($data['diary_year_list']) ? $data['diary_year_list'] : '' ;
        $case_type_disp = isset($data['case_type_disp']) ? $data['case_type_disp'] : '' ;
        $case_number_disp = isset($data['case_number_disp']) ? $data['case_number_disp'] : '' ;
        $case_year_disp = isset($data['case_year_disp']) ? $data['case_year_disp'] : '' ;
        $diary_number_disp = isset($data['diary_number_disp']) ? $data['diary_number_disp'] : '' ;
        $diary_year_disp = isset($data['diary_year_disp']) ? $data['diary_year_disp'] : '' ;

        if ( (($case_type_list != '') && ($case_year_list != '') && ($case_number_list != '')) ||
             (($diary_number_list != '') && ($diary_year_list != '')) ||
             (($case_type_disp != '') && ($case_year_disp != '') && ($case_number_disp != '')) ||
             (($diary_number_disp != '') && ($diary_year_disp != '')
            )){

            $data_array['case_detail'] = $this->ConditionalDisposeModel->get_case_details($case_type_list, $case_number_list, $case_year_list, $diary_number_list, $diary_year_list, $case_type_disp, $case_number_disp, $case_year_disp, $diary_number_disp, $diary_year_disp);
        }
        $result = $data_array['case_detail'];

        if (!empty($result) && isset($result[0]['case_diary'])) {
            $caseDiary = $result[0]['case_diary'];
            $caseStatus = $result[0]['c_status'];
            $caseTitle = $result[0]['case_title'];
            $daCode = $result[0]['dacode'];
            $connData = $this->ConditionalDisposeModel->get_conn_details($caseDiary);
        $response = [
                'status' => 'success',
                'case_diary' => $caseDiary,
                'case_status' => $caseStatus,
                'case_title' => $caseTitle,
                'dacode' => $daCode,
                'conn_details' => $connData
            ];
        } else {
            // Handle case where result is empty or missing required fields
            $response = [
                'status' => 'error',
                'message' => 'Case details not found or incomplete.'
            ];
        }
        return json_encode($response);
    }


    public function update_case(){
        $data = $_POST;
        // $data['app_name'] = "Conditional Disposal";
        $list_diary =  isset($data['list_diary']) ? $data['list_diary'] : '' ; 
        $dispose_diary = isset($data['dispose_diary']) ? $data['dispose_diary'] : '' ; 
        $connected = isset($data['connected']) ? $data['connected'] : '' ; 
        $usercode = isset($data['usercode']) ? $data['usercode'] : '' ; 
        if (($list_diary != '') && ($dispose_diary !='')&& ($connected != '')){
            $this->ConditionalDisposeModel->update_case($list_diary, $dispose_diary,$connected,$usercode);
        }
    }

    public function check_case(){
        $data = $_POST;
        // $data['app_name'] = "check Disposal";
        $list_diary = isset($data['list_diary']) ? $data['list_diary'] : '' ; 
        if (($list_diary != '')){
            $data_array['case_detail'] = $this->ConditionalDisposeModel->check_case($list_diary);
        }
        echo json_encode($data_array);
    }


    public function get_Remove_case_details(){
        $data = $_POST;

        $case_type_list = isset($data['case_type_list']) ? $data['list_diary'] : '' ;  
        $case_number_list = isset($data['case_number_list']) ? $data['case_number_list'] : '' ;
        $case_year_list = isset($data['case_year_list']) ? $data['case_year_list'] : '' ;
        $diary_number_list = isset($data['diary_number_list']) ? $data['diary_number_list'] : '' ;
        $diary_year_list = isset($data['diary_year_list']) ? $data['diary_year_list'] : '' ;

        if ((($case_type_list != '') && ($case_year_list != '') && ($case_number_list != '')) || (($diary_number_list != '') && ($diary_year_list != ''))){
            $data_array['case_detail'] = $this->ConditionalDisposeModel->get_Remove_case_details($case_type_list, $case_number_list, $case_year_list, $diary_number_list, $diary_year_list);
        }
        $case_detail = $data_array['case_detail'];
        if (isset($case_detail[0]['case_diary'])){
            $data_array['conn_details'] = $this->ConditionalDisposeModel->get_conn_details($case_detail[0]['case_diary']);
        }
        echo json_encode($data_array);
    }

    public function HighCourt_index(){
        // $data['app_name'] = "HighCourt_index";
        $data['case_types'] = $this->ConditionalDisposeModel->case_types();
        return view('Judicial/updateCase_HighCourt', $data);
    }

    public function update_HighCourt_case(){
        // $data['app_name'] = "High Court Conditional Disposal";
        $data = $_POST;
        // $list_diary = $this->input->post('list_diary');
        // $dispose_hCourt = $this->input->post('dispose_hCourt');
        // $court_type= $this->input->post('court_type');
        // $connected = $this->input->post('connected');
        // $usercode = $this->input->post('usercode');
        $list_diary = isset($data['list_diary']) ? $data['list_diary'] : '' ;
        $dispose_hCourt = isset($data['dispose_hCourt']) ? $data['dispose_hCourt'] : '' ;  
        $court_type = isset($data['court_type']) ? $data['court_type'] : '' ;
        $connected = isset($data['connected']) ? $data['connected'] : '' ;
        $usercode = isset($data['usercode']) ? $data['usercode'] : '' ;
        if (($list_diary != '') && ($dispose_hCourt != '')&& ($connected != '')){
            $this->ConditionalDisposeModel->update_case_HighCourt($list_diary, $dispose_hCourt,$court_type,$connected,$usercode);
        }
    }



    public function get_HighCourt_State(){
        $data_array = $this->ConditionalDisposeModel->get_HighCourt_State();
        echo json_encode($data_array);
    }


    public function Remove_index($session){
        // $this->session->set_userdata('dcmis_user_idd', $session);
        // $data['app_name'] = "Remove_index";
        $data['case_types'] = $this->ConditionalDisposeModel->case_types();
        return view('Judicial/Remove_dispose_condition', $data);
    }

    public function Remove_Disposal_condition(){
        // $data['app_name'] = "Conditional Disposal";
        $list_diary = isset($data['list_diary']) ? $data['list_diary'] : '' ; 
        $dispose_diary = isset($data['dispose_diary']) ? $data['dispose_diary'] : '' ; 
        $connected = isset($data['connected']) ? $data['connected'] : '' ; 
        $usercode = isset($data['usercode']) ? $data['usercode'] : '' ; 
        if (($list_diary != '') && ($dispose_diary != '')&& ($connected != '')){
            $this->ConditionalDisposeModel->update_case($list_diary, $dispose_diary,$connected,$usercode);
        }
    }

    public function get_HighCourt_State_bench(){
        // ob_clean();
        // header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->ConditionalDisposeModel->get_HighCourt_State_bench($_POST['state'],$_POST['agency_court']);
        echo json_encode($data_array);
        // ob_end_flush();

    }

    public function get_CaseType_State_bench(){
        // ob_clean();
        // header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->ConditionalDisposeModel->get_CaseType_State_bench($_POST['state'],$_POST['court_type']);
        echo json_encode($data_array);
        // ob_end_flush();

    }

    public function get_District_State(){
        // ob_clean();
        // header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->ConditionalDisposeModel->get_District_State($_POST['dstate']);
        echo json_encode($data_array);
        // ob_end_flush();

    }

    public function get_CaseType_Tribunal(){
        // ob_clean();
        // header("Content-Type: application/json;charset=utf-8");
        $data_array = $this->ConditionalDisposeModel->get_CaseType_Tribunal($_POST['state']);
        echo json_encode($data_array);
        // ob_end_flush();

    }

    public function get_Restrict_Cases_History(){
        // ob_clean();
        // header("Content-Type: application/json;charset=utf-8");
        $list_diary = isset($data['list_diary']) ? $data['list_diary'] : '' ; 
        $arr = $this->ConditionalDisposeModel->get_Restrict_Cases_History($list_diary);
        echo json_encode($arr);
        // ob_end_flush();


    }

    public function delete_Restricted_Case(){
        $usercode   = isset($data['usercode']) ? $data['usercode'] : '' ; 
        $list_diary = isset($data['list_diary']) ? $data['list_diary'] : '' ; 
        //echo "hello";
        $deleteStatus = $this->ConditionalDisposeModel->delete_Restricted_Case($list_diary,$usercode);
        if($deleteStatus=='T'){
            $this->data['Remove_Case']='Deleted';
        }
        else{
            $this->data['Remove_Case']='Not Deleted';
        }
        echo $this->data['Remove_Case'];

      //  $this->load->view('dispose_condition/Remove_dispose_condition',$this->data);

    }


}