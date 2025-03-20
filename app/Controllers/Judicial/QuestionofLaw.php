<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Judicial\QuestionofLawModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_diary;
use App\Models\Entities\fil_trap_a;

class QuestionofLaw extends BaseController
{
    public $QuestionofLawModel;

    function __construct()
    {
        //ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        // ini_set('memory_limit', '-1');
        // $this->Dropdown_list_model = new Dropdown_list_model();
        // $this->Model_diary = new Model_diary();
        $this->QuestionofLawModel = new QuestionofLawModel();
    }

    // public function Insert($usercode){
    //     $this->session->set_get('dcmis_user_idd', $usercode);
    //     $this->DoUpdateCase();
    // }

    public function Insert()
    {
        $data['app_name'] = "Update Consignment Information";
        $data['case_type'] = $this->QuestionofLawModel->case_types();
        $userCode = session()->get('login')['usercode'];

        $data['usercode'] = $userCode;
        $data['keywords'] = $this->QuestionofLawModel->get_keyword_list();
        $data['acts'] = $this->QuestionofLawModel->get_acts_list();
        $data['param'] = $this->QuestionofLawModel->da_details($userCode);

        /*var_dump($res);
        if(sizeof($res)>0)
        {
            $sec_id=$res[0]['id'];
            $user_type=$res[0]['usertype'];
            $this->data['param']=array($sec_id,$user_type);
        }*/

        return view('Judicial/QuestionofLaw/update_case', $data);
    }

    public function get_details()
    {
        $request = \Config\Services::request();
        $Dropdown_list_model = new Dropdown_list_model();
        
        $case_type = $request->getPost('case_type');
        $case_number = $request->getPost('case_number');
        $case_year = $request->getPost('case_year');
        $diary_number = $request->getPost('diary_number');
        $diary_year = $request->getPost('diary_year');
        
        $filing_details = [];

        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'Search Type', 'rules' => 'required|min_length[1]|max_length[1]']
        ])) {
            $input_query = [];
            $filing_details = [];
            $search_type = $request->getPost('search_type');
            if ($search_type == 'D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $diary_number = $request->getPost('diary_number');
                $diary_year = $request->getPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $filing_details = $Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                
                $input_query['diary_number'] = $diary_number;
                $input_query['diary_year'] = $diary_year;

            } elseif ($search_type == 'C' && $this->validate([
                'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $case_type = $this->request->getPost('case_type');
                $case_number = $this->request->getPost('case_number');
                $case_year = $this->request->getPost('case_year');
                
                $filing_details = $Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

                if($filing_details === false) {
                    return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
                }
                                
                $diary_info = get_diary_numyear($filing_details['diary_no']);

                $diary_number = $diary_info[0];
                $diary_year = $diary_info[1];

                $input_query['diary_number'] = $diary_number;
                $input_query['diary_year'] = $diary_year;

            }
        }

        // pr($filing_details);

        if(empty($filing_details)) 
        {
            
            $data['success'] = 0;
            $data['redirect'] = base_url('Judicial/QuestionofLaw/Insert');

            return $this->response->setJSON($data);
        }

        // pr($filing_details);

        $dairy_no = $filing_details['diary_no'];

        $data_array['case_detail'] = $this->QuestionofLawModel->get_case_details($dairy_no);

        return $this->response->setJSON($data_array);
        // echo json_encode($data_array);
    }

    public function update_dnos_to_verify()
    {
        // $this->session->set_userdata('dcmis_user_idd', $session);
        $request = \Config\Services::request();

        $dnos = $request->getPost('diaryNos');
        $userCode = $request->getPost('userCode');

        if (isset($dnos)) {
            //$data_array['update_details'] = $this->QuestionofLawModel->update_cases_to_verify($dnos,$userCode);
            $NoRowVerified = $this->QuestionofLawModel->update_cases_to_verify($dnos, $userCode);
        }

        if ($NoRowVerified >= 1) {
            session()->setFlashdata('msg', '<div class="alert alert-success text-center col-md-12">Diary Nos (<?=$dnos?> Successfully Verified</div>');
            redirect()->to("Judicial/QuestionofLaw/VerifyReport");
            // echo json_encode($dnos);
        }
        //echo json_encode($data_array);

    }

    public function update_case()
    {
        $request = \Config\Services::request();

        $data['app_name'] = "Update LawPoint Information";
        
        $case_diary = $request->getPost('case_diary');
        $usercode = $request->getPost('usercode');
        $usertype = $request->getPost('usertype');
        $law_point = trim($request->getPost('law_point'));
        $acts = $request->getPost('acts');
        $keywords = $request->getPost('keywords');
        $catchwords = $request->getPost('catchwords');
        $act_update_count = 0;
        $keyword_update_count = 0;
        $NoRowAffected_LawPoint = 0;

        if (isset($case_diary) && isset($law_point)) {
            $case_count = $this->QuestionofLawModel->check_case($case_diary);
            //echo $case_count[0]['count_no'];
            if ($usertype != 6) {

                $NoRowAffected_LawPoint = $this->QuestionofLawModel->update_case($case_count[0]['count_no'], $case_diary, $usercode, $usertype, $law_point);
            } else {
                $NoRowAffected_LawPoint = $this->QuestionofLawModel->update_case($case_count[0]['count_no'], $case_diary, $usercode, $usertype, $law_point, $catchwords);
            }
        }

        if (isset($acts)) {

            foreach ($acts as $act) {
                $NoRowAffected_Act = 0;
                $act_count = $this->QuestionofLawModel->check_act_entry($case_diary, $act);
                if (! $act_count) {
                    $NoRowAffected_Act = $this->QuestionofLawModel->insert_acts($case_diary, $act, $usercode);
                }

                if ($NoRowAffected_Act >= 1) {
                    $act_update_count++;
                }
            }
        }

        $already_acts = [];
        $already_entered_acts = $this->QuestionofLawModel->get_all_entered_acts($case_diary);

        if(!empty($already_entered_acts['acts'])) {
            $already_acts = explode(",", $already_entered_acts['acts']);
            $already_acts = array_map('trim', $already_acts);
        }

        if (isset($acts) && !empty($acts)) {

            $not_selected_acts = array_diff($already_acts, $acts);

            foreach ($not_selected_acts as $not_selected_act) {
                $NoRowDisplayN = $this->QuestionofLawModel->update_non_selected_acts($case_diary, $not_selected_act, $usercode);
            }
        } else {

            foreach ($already_acts as $already_act) {
                $NoRowDisplayN = $this->QuestionofLawModel->update_non_selected_acts($case_diary, $already_act, $usercode);
            }
        }

        $already_keywords = [];
        $already_entered_keywords = $this->QuestionofLawModel->get_all_entered_keywords($case_diary);
        if(!empty($already_entered_keywords['keywords'])) {
            $already_keywords = explode(",", $already_entered_keywords['keywords']);
            $already_keywords = array_map('trim', $already_acts);
        }

        if (isset($keywords)) {
            foreach ($keywords as $keyword) {
                $NoRowAffected_Keyword = 0;
                $keyword_count = $this->QuestionofLawModel->check_keyword_entry($case_diary, $keyword);
                if (! $keyword_count) {
                    $NoRowAffected_Keyword = $this->QuestionofLawModel->insert_keywords($case_diary, $keyword, $usercode);
                }
                if ($NoRowAffected_Keyword >= 1) {
                    $keyword_update_count++;
                }
            }
        }
        //echo "No of Act Updated:".$act_update_count."# No of Keyword Updated:".$keyword_update_count;
        if (isset($keywords) && !empty($keywords)) {
            $not_selected_keywords = array_diff($already_keywords, $keywords);
            foreach ($not_selected_keywords as $not_selected_keyword) {
                $NoRowDisplayN = $this->QuestionofLawModel->update_non_selected_keywords($case_diary, $not_selected_keyword, $usercode);
            }
        } else {
            foreach ($already_keywords as $already_keyword) {
                $NoRowDisplayN = $this->QuestionofLawModel->update_non_selected_keywords($case_diary, $already_keyword, $usercode);
            }
        }

        if ($NoRowAffected_LawPoint >= 1)
            echo "Data Updated Successfully";
        else
            echo "Error";
    }

    public function VerifyReport()
    {
        $request = \Config\Services::request();

        // $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
        // $this->session->set_get('dcmis_user_idd', $session);
        $userCode = session()->get('login')['usercode'];
        //echo $userCode;

        // Form validation rules
        $validationRules = [
            'fromDate' => 'required|min_length[10]',
            'toDate' => 'required|min_length[10]'
        ];

        if ($request->getMethod() === 'post' && !$this->validate($validationRules)) {
            // Validation failed, redirect back with error messages and old input
            return redirect()->to('Judicial/QuestionofLaw/VerifyReport')->withInput();
        }

        $data['lawPointReports'] = 'Law Point Report';
        $data['app_name'] = '';
        $data['param'] = [old('fromDate', date('d-m-Y')), old('toDate', date('d-m-Y'))];

        // pr($_POST);

        if (!empty($_POST['fromDate']) && !empty($_POST['toDate'])) {
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            // $userCode = $_POST['usercode'];
            // echo $userCode;

            $sec_id = "";
            $user_type = "";
            $res = $this->QuestionofLawModel->da_details($userCode);
            if (sizeof($res) > 0) {
                $sec_id = $res[0]['id'];
                $user_type = $res[0]['usertype'];
            }

            $data['lawPointReports'] = 'Law Point Report';

            $data['lawPointReports'] = $this->QuestionofLawModel->get_law_point_verify($fromDate, $toDate, $sec_id);
            $data['app_name'] = 'lawpoint';
            $data['param'] = array($fromDate, $toDate);
        }

        $data['validation'] = \Config\Services::validation();

        return view('Judicial/QuestionofLaw/verify_law_point', $data);
    }

    public function Report()
    {
        $request = \Config\Services::request();

        $data['lawPointReports'] = '';
        $data['app_name'] = '';
        $data['param'] = [old('fromDate', date('d-m-Y')), old('toDate', date('d-m-Y'))];

        // Form validation rules
        $validationRules = [
            'fromDate' => 'required|min_length[10]',
            'toDate' => 'required|min_length[10]'
        ];

        if ($request->getMethod() === 'post' && !$this->validate($validationRules)) {
            // Validation failed, redirect back with error messages and old input
            return redirect()->to('Judicial/QuestionofLaw/Report')->withInput();
        }

        if (!empty($_POST['fromDate']) && !empty($_POST['toDate'])) {
            $fromDate = $_POST['fromDate'];
            $toDate = $_POST['toDate'];
            $data['lawPointReports'] = 'Law Point Report';

            //echo "post";
            $data['lawPointReports'] = $this->QuestionofLawModel->get_law_point_report($fromDate, $toDate);
            $data['app_name'] = 'lawpoint';
            $data['param'] = array($fromDate, $toDate);
        }

        $data['validation'] = \Config\Services::validation();
        
        //var_dump($data['mentioningReports']);
        return view('Judicial/QuestionofLaw/law_point_report', $data);
    }
}