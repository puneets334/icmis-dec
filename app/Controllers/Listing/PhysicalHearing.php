<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;
use App\Models\Listing\PhysicalHearingModel;
use App\Models\Common\Dropdown_list_model;

class PhysicalHearing extends BaseController
{

    public $model;
    public $diary_no;
    public  $PhysicalHearingModel;
    public $Dropdown_list_model;

    function __construct()
    {
         $this->PhysicalHearingModel = new PhysicalHearingModel();
         $this->Dropdown_list_model = new Dropdown_list_model();
    }

    /**
     * To display drop request add menu page
     *
     * @return void
     */
    public function index()
    {
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            $getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
           exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }

        $filing_details = session()->get('filing_details');
        $data['diary_number'] = $filing_details['diary_number'];
        $data['diary_year'] = $filing_details['diary_year'];
        $data['dno'] = $filing_details['diary_no'];

        
        $data['case_name'] = $this->PhysicalHearingModel->get_short_description($filing_details['diary_no']);
        
        //$data['chk_heardt'] = $this->PhysicalHearingModel->chk_heardt($filing_details['diary_no']);
        $data['chk_heardt'] = $this->PhysicalHearingModel->get_next_dt($filing_details['diary_no']);
        $data['details'] = $this->PhysicalHearingModel->get_case_details($filing_details['diary_no']);        
        $data['fil_details'] = $this->PhysicalHearingModel->get_fil_details($filing_details['diary_no']);
        
        if (isset($data['fil_details']) && ($data['fil_details']['fil_no_fh'] != '' ||  $data['fil_details']['fil_no_fh'] != NULL)){
            $data['short_description_by_casecode'] = $this->PhysicalHearingModel->short_description_by_casecode($data['fil_details']['fil_no_fh']);
        }
        
        $data['multiple_category'] = $this->PhysicalHearingModel->get_multiple_category($filing_details['diary_no']);
        $data['main_case'] = $this->PhysicalHearingModel->get_conct($filing_details['diary_no']);
        $data['usercode'] =  session()->get('login')['usercode'];
        $data['navigate_diary'] = $this->PhysicalHearingModel->navigate_diary($filing_details['diary_no']);
        // die('Hello');
        return  view('Listing/physical_hearing/get_addCase_details', $data);
    }

    public function save_case_in_vacation_list()
    {
        $data['fil_no'] = $this->request->getPost('fil_no');
        $data['usercode'] = $this->request->getPost('usercode');
        $data['main_case'] = $this->PhysicalHearingModel->physical_hearing_consent_required($data['fil_no'], $data['usercode']);
    }

    public function physical_hearing_cases()
    {
        $data = [];
        $request = \Config\Services::request();
        $data['report'] = '';
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
            'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        ])) {
            
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = 'ADD CASE CONSENT FOR HEARING MODE';
        $data['formAction'] = 'Listing/PhysicalHearing/index';

        return  view('Listing/physical_hearing/physical_hearing_cases', $data);
    }

    public function get_vacation_advance_list()
    {
        $caseCategory = $_POST['case_category'];
        $data['vacation_advance_list'] = $this->PhysicalHearingModel->get_vacation_advance_lists($caseCategory);
        $data['userid'] = session()->get('login')['empid'];
        return view('Listing/physical_hearing/get_vacation_advance_list', $data);
    }

    public function decline_vacation_list_cases()
    {
        $all_cases = $this->request->getPost('diary_no');
        // $updated_by = $this->request->getPost('usercode');
        $updated_by = session()->get('login')['usercode'];
        $updated_from_system = $_SERVER['REMOTE_ADDR'];
        $data['vacation_advance_list'] = $this->PhysicalHearingModel->decline_vacation_list_cases($all_cases, $updated_by, $updated_from_system);
    }

    public function get_case_details_with_advocates()
    {
        //$all_cases = $this->request->getPost('diary_no');

        $request = \Config\Services::request();        
        $data = [];
        $data['check_physical_hearing_pool_result'] = $data['chk_heardt'] = [];
        
        //if ($request->getMethod() === 'post' && $this->validate([
        if ($request->getMethod() === 'post') {
            $input_query = [];
            $filing_details = [];
            $search_type    = $request->getPost('search_type');
            $diary_number   = $request->getPost('diary_number');
            $diary_year     = $request->getPost('diary_year');

            if ($search_type == 'D') {                
                $diary_no       = $diary_number . $diary_year;
                $filing_details = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                
                $input_query['diary_number'] = $diary_number;
                $input_query['diary_year'] = $diary_year;

            } elseif ($search_type == 'C') {
                $case_type      = $request->getPost('ct');
                $case_number    = $request->getPost('cn');
                $case_year      = $request->getPost('cy');
                //session()->setFlashdata("error", 'Data not Found');
                //return redirect()->to('Listing/PhysicalHearing/physical_hearing_cases');

                $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
                // pr($diary_no);
                if (!empty($diary_no)) {
                    $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                } else {
                    $get_main_table = array();
                }
                if ($get_main_table) {
                    $get_main_table['diary_number'] = $diary_number;
                    $get_main_table['diary_year'] = $diary_year;
                    $this->session->set(array('filing_details' => $get_main_table));
                }

            }

            $data['check_physical_hearing_pool_result'] = $this->PhysicalHearingModel->check_case_in_physical_hearing($diary_no);
            $data['chk_heardt'] = $this->PhysicalHearingModel->get_next_dt($filing_details['diary_no']);
            
            if(empty($filing_details)) {
                session()->setFlashdata("error", 'Data not Found');
                $data['success'] = 1;
                $data['html'] = view('Listing/physical_hearing/get_case_details_with_advocates', $data, ['saveData' => true]);
                return $this->response->setJSON($data);
            }

            $filing_details['input_query'] = $input_query;
            $data['diary_number'] = $filing_details['input_query']['diary_number'];
            $data['diary_year'] = $filing_details['input_query']['diary_year'];
            $data['pet_name'] = $filing_details['pet_name'];
            $data['res_name'] = $filing_details['res_name'];
            $data['diary_no'] = $filing_details['diary_no'];

            //$data['vacation_advance_list'] = $this->PhysicalHearingModel->get_vacation_advance_list();
            //$data['userid'] = session()->get('login')['empid'];

            
            $data['case_name'] = $this->PhysicalHearingModel->get_short_description($filing_details['diary_no']);
            
            $data['details'] = $this->PhysicalHearingModel->get_case_details($filing_details['diary_no']);
            $data['fil_details'] = $this->PhysicalHearingModel->get_fil_details($filing_details['diary_no']);
            if (isset($data['fil_details']) && ($data['fil_details']['fil_no_fh'] != '' ||  $data['fil_details']['fil_no_fh'] != NULL)){
                $data['short_description_by_casecode'] = $this->PhysicalHearingModel->short_description_by_casecode($data['fil_details']['fil_no_fh']);
            }
    
            $data['multiple_category'] = $this->PhysicalHearingModel->get_multiple_category($filing_details['diary_no']);
            $data['main_case'] = $this->PhysicalHearingModel->get_conct($filing_details['diary_no']);
            //$data['usercode'] =  session()->get('login')['usercode'];
            $data['navigate_diary'] = $this->PhysicalHearingModel->navigate_diary($filing_details['diary_no']);
            $data['case_aor_list'] = $this->PhysicalHearingModel->get_advocate_details($filing_details['diary_no']);
            $data['success'] = 1;
            $data['html'] = view('Listing/physical_hearing/get_case_details_with_advocates', $data, ['saveData' => true]);
        } else {
            $data['success'] = 0;
            $data['error'] = "Record could not be fetched.";
        }

        return $this->response->setJSON($data);
    }


    public function update_advocate_consent()
    {
        $diary_no = $this->request->getPost('diary_no');
        $advocate_ids = $this->request->getPost('advocate_ids');
        $data['vacation_advance_list'] = $this->PhysicalHearingModel->update_hearing_consent_required($advocate_ids, $diary_no);
    }


    public function consent_report()
    {
        $data = [];
        return  view('Listing/physical_hearing/consent_report', $data);
    }

    public function get_consent_report()
    {
        $case_category = $this->request->getPost('case_category');
        $consent_type = $this->request->getPost('consent_type');
        
        $tital= '';
        if($consent_type == 'M'){
            $tital .= "MISC. HEARING CASES<BR>";
        }
        else{
            $tital .= "REGULAR HEARING CASES<BR>";
        }
        if($case_category == 'P'){
            $tital .= "PHYSICAL";
        }
        else if($case_category == 'F'){
            $tital .= "VIRTUAL";
        }
        else{
            $tital .= "NO RESPONSE";
        }
        $tital .= " CASES CONSENT REPORT (AS ON ".date('d-m-Y H:i:s').")";

        $data['tital'] = $tital;
        $data['consent_report'] = $this->PhysicalHearingModel->get_consent_report($case_category, $consent_type);
        return  view('Listing/physical_hearing/get_consent_report', $data);
    }


    public function restore_vacation_advance_list()
    {
        $diary_no = $this->request->getPost('diary_no');
        $emp_id = $this->request->getPost('empID');
        $user_id =  session()->get('login')['usercode'];
        $updated_from_system = $_SERVER['REMOTE_ADDR'];
        $vacation_advance_list  = $this->PhysicalHearingModel->restore_vacation_advance_list($diary_no, $emp_id, $user_id, $updated_from_system);
        
        $var =' <a>';
        if ((isset($vacation_advance_list['declined_by_admin'])) && ($vacation_advance_list['declined_by_admin'] == 't')) {
            echo "<a class='btn btn-xs btn-danger'   title=\"List\"  onclick=\"javascript:confirmBeforeList($vacation_advance_list[diary_no]);\">";

            $var .= '<span style="color:red;text-align: center !important;margin-left: 45%;" id="deleteButton" class="ui-icon ui-icon-closethick"></span> Declined</a>';
        } else {
            if($vacation_advance_list['is_fixed']!='Y') {
                echo "<input type='checkbox' name='vacationList' id='vacationList' value='$vacation_advance_list[diary_no]'>";
            } else {
                echo "<span style='color:green;'>Fixed For <br> Vacation</span><br/>";
            }
        }
        return $var;
    }

    

}
