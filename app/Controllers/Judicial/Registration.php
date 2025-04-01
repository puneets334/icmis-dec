<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\RegistrationModel;

class Registration extends BaseController
{
    function __construct()
    {
        // $this->session = session();
        // $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    public function index()
    {
        $request = \Config\Services::request();

        $data = [];
        $data['report'] = '';
        
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
            'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        ])) {
            
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = 'Judicial / Regular Registration >> Register Case';
        $data['formAction'] = 'Judicial/Registration/index';

        return view('Judicial/Registration/index', $data);
    }

    public function generate_case_no() 
    {
        $request = \Config\Services::request();
        
        $data = [];

        // $_POST['ct'] = 3;
        // $_POST['qte'][] = '42024';
        // $_POST['reg_for_year'] = 0;

        $i = 0;
        $cond = "";
        $str = "";
        $output = [];
        $i = 0;
        //var_dump($request->getPost(;
        $ct = $request->getPost('ct');
        $dtd = $request->getPost('dtd');
        $year = date('Y');
    
        $registration_date = '';
        if ($request->getPost('reg_for_year') != '' && $request->getPost('reg_for_year') != '0') 
        {
            $year = $request->getPost('reg_for_year');
        }
    
        foreach ($request->getPost('qte') as $diary_no) 
        {
            
            $RegistrationModel = new RegistrationModel();

            $reg_no = $RegistrationModel->checkCaseRegisteredOrNot($diary_no);

            if (!empty($reg_no)) 
            {
                if ($reg_no['mfilno'] == "Misc NF") 
                {
                    $output[$diary_no]['success'] = 0;
                    $output[$diary_no]['message'] = "Not Registered in Miscellaneous ";
                } else if ($reg_no['rfilno'] == "Reg F") {
                    $output[$diary_no]['success'] = 0;
                    $output[$diary_no]['message'] = "No. Already Registered in Regular\n\r" . $reg_no['reg_no_display'];
                } 
                else 
                {
                    
                    $newcc = 0;
                    $newcn = "";
                    
                    $row_ct = $RegistrationModel->getCaseNature($ct);

                    if (!empty($row_ct)) {
                        $newcc = $row_ct['casecode'];
                        $newcn = $row_ct['nature'];
                    }

                    $row1 = $RegistrationModel->getTotalFiles($diary_no);
                    $ttl_files = 0;
                    if (!empty($row1)) {

                        if ($row1['fil_no'] != '')
                            $ttl_files = $row1['ttl_files'];
                        else {
                            $count_lower = $RegistrationModel->getLowerCourtCount($diary_no);
                            if ($count_lower > 0) {
                                $ttl_files = $count_lower;
                            }
                        }
                        
                        ///KOUNTER
                        $kntr = $RegistrationModel->getKCounter(['casetype_id' => $ct, 'year' => $year]);
                        if (!empty($kntr)) {

                            // in case registration is for previous year
                            if ($request->getPost('reg_for_year') != '' && $request->getPost('reg_for_year') != '0') {
    
                                $registration_date = date('Y-m-d', strtotime($dtd));
                                $reg_no = 0;
                                if (strlen($kntr) < 6) {
                                    $length = strlen($kntr);
                                    $reg_no = intval($kntr) + 1;
                                    for ($index = $length; $index < 6; $index++) {
                                        $reg_no = '0' . $reg_no;
                                    }
                                }


                                $check_reg_no = $RegistrationModel->getCaseCount(['reg_no' => $reg_no, 'casetype_id' => $ct, 'year' => $year]);

                                if (!empty($check_reg_no)) {

                                    $max_regno = $RegistrationModel->getMaxRegNo(['casetype_id' => $ct, 'filter_year' => $year]);

                                    $pos = strrpos($max_regno, '-', 0);
                                    $max_regno = substr($max_regno, $pos + 1);
                                    $kntr = ltrim($max_regno, '0');
                                }
                            } else {
                                $registration_date = date('Y-m-d H:i:s');
                            }

                            $result = $RegistrationModel->updateKCounter(['knt' => (int)($kntr + $ttl_files), 'casetype_id' => $ct, 'year' => $year]);
                        } 
                        else 
                        {
                            if ($request->getPost('reg_for_year') != '' && $request->getPost('reg_for_year') != '0') {
                                $registration_date = date('Y-m-d', strtotime($dtd));
                                
                                $max_regno = $RegistrationModel->getMaxRegNo(['casetype_id' => $ct, 'year' => $year]);
                                
                                $pos = strrpos($max_regno, '-', 0);
                                $max_regno = substr($max_regno, $pos + 1);
                                $kntr = ltrim($max_regno, '0');
                            } else
                                $registration_date = date('Y-m-d H:i:s');
                                $res_case_ct = (int)($kntr + $ttl_files);

                                $result = $RegistrationModel->addKCounter(['knt' => $res_case_ct, 'casetype_id' => $ct, 'year' => $year]);
                        }
    
                        ///KOUNTER
                        if ($ttl_files > 1)
                            $t_fil_no_fh = str_pad($ct, 2, "0", STR_PAD_LEFT) . "-" . str_pad($kntr + 1, 6, "0", STR_PAD_LEFT) . "-" . str_pad($kntr + $ttl_files, 6, "0", STR_PAD_LEFT);
                        else
                            $t_fil_no_fh = str_pad($ct, 2, "0", STR_PAD_LEFT) . "-" . str_pad($kntr + 1, 6, "0", STR_PAD_LEFT) . "-" . str_pad($kntr + 1, 6, "0", STR_PAD_LEFT);
                        
                        $regNoDisplay = $RegistrationModel->getRegistrationNumberDisplay($diary_no, $t_fil_no_fh, $year);

                        //change section after conversion to CA
                        $section_id = "";
                        $check_section = $RegistrationModel->getCase($diary_no);    

                        //UP matters
                        if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '61023' and in_array($check_section['ref_agency_code_id'], array('15', '16'), TRUE)) {
                            $section_id = "23 "; //section XI->III-A
                        }
                        //Punjab matters
                        else if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '226817' and in_array($check_section['ref_agency_code_id'], array('10'), TRUE)) {
                            $section_id = "24 "; //section IVB->IV
                        }
                        //Delhi HP North East
                        else if ($check_section['active_casetype_id'] == 1 and in_array($check_section['ref_agency_state_id'], array('490506', '571779', '291560', '537722', '349528', '348677', '355594', '184724', '511231', '167131'), TRUE) and in_array($check_section['ref_agency_code_id'], array('14', '27', '28', '29', '30', '86', '137', '138', '139', '149', '150', '153', '53', '183', '184', '9', '299'), TRUE)) {
                            $section_id = "80 "; //section XIV->XIVA
                        }
                        //Maharashtra matters from IX to III
                        else if ($check_section['active_casetype_id'] == 1 and $check_section['ref_agency_state_id'] == '358033' and in_array($check_section['ref_agency_code_id'], array('31', '32', '33', '34'), TRUE)) {
                            $section_id = "22 "; //section XIV->XIVA
                        }
    
    
                        //echo ' section '.$section;
                        $result = $RegistrationModel->updateMain(['active_casetype_id' => $newcc, 'case_grp' => $newcn, 'fil_no_fh' => $t_fil_no_fh, 'active_fil_no' => $t_fil_no_fh, 'fil_dt_fh' => $registration_date, 'active_fil_dt' => $registration_date, 'reg_year_fh' => $year, 'active_reg_year' => $year, 'reg_no_display' => $regNoDisplay, 'mf_active' => 'F', 'section_id' => $section_id, 'diary_no' => $diary_no]);

                        $row_m_h = $RegistrationModel->getCaseHistory($diary_no);

                        if (!empty($row_m_h)) {
                            $old_fil_ct = $row_m_h['ct1'];
                            $old_fil_no = $row_m_h['new_registration_number'];
                            $old_fil_dt = $row_m_h['new_registration_year'];
                        } else {
                            $old_fil_ct = 0;
                            $old_fil_no = '';
                            $old_fil_dt = 0;
                        }

                        //$ucode = session()->get('dcmis_user_idd');
                        $ucode = session()->get('login')['usercode'];                        
                        
                        // hold
                        $result = $RegistrationModel->addCaseHistory([
                            'diary_no' => $diary_no,
                            'old_registration_number' => $old_fil_no,
                            'old_registration_year' => $old_fil_dt,
                            'new_registration_number' => $t_fil_no_fh,
                            'new_registration_year' => $year,
                            'order_date' => date('Y-m-d', strtotime($dtd)), // Ensure the date is formatted correctly
                            'ref_old_case_type_id' => $old_fil_ct,
                            'ref_new_case_type_id' => $newcc,
                            'adm_updated_by' => $ucode,
                            'updated_on' => date('Y-m-d H:i:s'), // Use current date and time
                            'is_deleted' => 'f',
                            'ec_case_id' => 1
                        ]);
                        

                        $output[$diary_no]['success'] = 1;
                        $output[$diary_no]['message'] = "Registered No. is : " . $regNoDisplay . "\n\r";

                        if ($request->getPost('reg_for_year') != '' && $request->getPost('reg_for_year') != '0') 
                        {
                            //$RegistrationModel->generate_da_code($diary_no); //hold

                            $result = $RegistrationModel->addRegistrationTrack([
                                'diary_no' => $diary_no,
                                'registration_number_alloted' => $t_fil_no_fh,
                                'registration_year' => $year,
                                'usercode' => $ucode,
                                'reg_date' => date('Y-m-d H:i:s'), // Use current date and time
                            ]);
    
                            $output[$diary_no]['track'] = "<br>Track maintained Successfully</br>";
    
                            /* end of the code */
                        }
                    }
                }
            }
        }

        $data['success'] = 1;
        
        $data['html'] = view('Judicial/Registration/success_table', ['data' => $output], ['saveData' => true]);

        return $this->response->setJSON($data);
    }

    public function register() 
    {
        $request = \Config\Services::request();
        $RegistrationModel = new RegistrationModel();
        $Dropdown_list_model = new Dropdown_list_model();
        
        $data = [];

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
            session()->setFlashdata("error", 'Data not Fount');

            $data['success'] = 0;
            $data['redirect'] = base_url('Judicial/Registration/index');

            return $this->response->setJSON($data);
        }

        // pr($filing_details);

        $dairy_no = $filing_details['diary_no'];
        $row_detail = $RegistrationModel->getCaseDetails($dairy_no);


        // if ($request->getMethod() === 'post' && $this->validate([
        //     'search_type' => ['label' => 'Search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
        //     'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
        //     'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        // ])) {
            
            // $dairy_no = $request->getPost('diary_number').$request->getPost('diary_year');

            // $row_detail = $RegistrationModel->getCaseDetails($dairy_no);
            
        if(!empty($row_detail)) {
            $data['p'] = $row_detail["pet_name"];
            $data['r'] = $row_detail["res_name"];
            $data['padv'] = $row_detail['pet_adv'];
            $data['radv'] = $row_detail['res_adv'];
            $data['status'] = $row_detail['c_status'];
            // $data['ccat'] = $row_detail['ccat'];
            $data['lastorder'] = $row_detail['lastorder'];
            // $data['listorder'] = $row_detail['listorder'];
            // $data['benchmain'] = $row_detail['bench'];
            $data['case_t'] = $row_detail["diary_no"];

            $cstatus = "";
            switch ($row_detail['c_status']) {
                case 'P':
                    $cstatus = "<font color='blue'>Pending</font>";
                    break;

                case 'R':
                    $cstatus = "<font color='red'>Rejected</font>";
                    break;

                case 'D':
                    $cstatus = "<font color='red'>Disposed</font>";
                    break;

                case 'T':
                    $cstatus = "<font color='red'>Transferred</font>";
                    break;
            }
            
            $data['cstatus'] = $cstatus;
            $data['category'] = get_mul_category($row_detail["diary_no"]);
            
            // pr($data['category']);

            $data['diary_no'] = $row_detail["diary_no"];
            $data['real_diaryno'] = get_real_diaryno($row_detail["diary_no"]);
            $data['real_caseno'] = get_case_nos($row_detail["diary_no"], '&nbsp;&nbsp;&nbsp;', '');
            
            $text_list_before = $text_list_not_before = "";

            if ($row_detail['c_status'] != "D") {
                $return_bfnbf = $RegistrationModel->getBfNbf($dairy_no);
                $t_return_bfnbf = explode('^|^', $return_bfnbf);
                
                $text_list_before = $t_return_bfnbf[0];
                $text_list_not_before = $t_return_bfnbf[1];
            }
            
            $data['text_list_before'] = $text_list_before;
            $data['text_list_not_before'] = $text_list_not_before;

            $data['listings'] = [];
            $listings = $RegistrationModel->getCaseListing($dairy_no);
            if($listings !== false) {
                $data['listings'] = $listings;
            }           

            $case_grp = $row_detail['case_grp'];

            $data['connected_cases'] = [];
            $connectedCases = $RegistrationModel->getConnectedCases($dairy_no);
            if($connectedCases !== false) {

                foreach ($connectedCases as $key => $row_conn) {

                    $connectedCases[$key]['real_diary_no'] = get_real_diaryno($row_conn["diary_no"]);
                    $connectedCases[$key]['real_caseno'] = get_case_nos($row_conn["diary_no"], '<br>');

                    // Define case Status
                    $connectedCases[$key]['t_cs'] = "";
                    if ($row_conn["c_status"] == "P") {
                        $connectedCases[$key]['t_cs'] = "<font color='blue'>" . $row_conn["c_status"] . "</font>";
                    }
                    if ($row_conn["c_status"] == "D") {
                        $connectedCases[$key]['t_cs'] = "<font color='red'>" . $row_conn["c_status"] . "</font>";
                    }
                    ##########################

                    // Define Before and Not before
                    $connectedCases[$key]['t_bfnbf'] = "";
                    if ($row_conn["c_status"] != "D") {
                        
                        $return_bfnbf1 = $RegistrationModel->getBfNbf($row_conn["diary_no"]);
                        
                        $t_return_bfnbf1 = explode('^|^', $return_bfnbf1);
                        
                        if ($t_return_bfnbf1[0] != "")
                            $connectedCases[$key]['t_bfnbf'] .= "<b>BEFORE</b>: <font color=green>" . $t_return_bfnbf1[0] . "</font>";
                        if ($t_return_bfnbf1[1] != "")
                            $connectedCases[$key]['t_bfnbf'] .= "<b>NOT BEFORE</b>: <font color=green>" . $t_return_bfnbf1[1] . "</font>";
                    }
                    ##########################

                    // Define case type
                    if ($row_conn["diary_no"] == $dairy_no) 
                    {
                        $connectedCases[$key]['t_ct'] = 'M';
                    } else {
                        $connectedCases[$key]['t_ct'] = $row_conn["conn_type"];
                    }
                    ##########################

                    // Count lower court cases
                    $lower_count = 0;
                    if ($row_conn["fil_no"] == '' or $row_conn["fil_no"] === NULL) {
                        $lower_count = $RegistrationModel->getLowerCourtCount($row_conn["diary_no"]);
                    }
                    
                    $connectedCases[$key]['lower_count'] = $lower_count; // echo "No Lower Court Details Found!<br>";
                    ##########################

                    if(
                            $row_conn["c_status"] == 'D' 
                            or ($row_conn["fil_no"] != '' and $row_conn["fil_no_fh"] != '')
                            or ($row_conn["c_status"] == 'D' and $row_conn["ct"] == 'M') 
                            or $row_conn["fil_no_fh"] != '' 
                            or (($row_conn["fil_no"] == '' or $row_conn["fil_no"] === NULL) && ($lower_count == 0)) //or $count == $count 
                            or $row_conn['active_casetype_id'] == '13' 
                            or $row_conn['active_casetype_id'] == '14'  
                            or  ($row_conn['case_grp'] != $case_grp)
                        ) 
                    {
                        $connectedCases[$key]['t_checked_disabled'] = true; // Disabled
                    } else {
                        $connectedCases[$key]['t_checked_disabled'] = false;
                    }

                }
            }

            // Assign Updated Connected Cases
            $data['connected_cases'] = $connectedCases;


            $activeCaseType = $RegistrationModel->getActiveCaseType($dairy_no);
            
            $data['case_type_disabled'] = 0;
            if(isset($activeCaseType['conversion_type']) && empty($activeCaseType['conversion_type'])) 
            {
                $data['case_type_disabled'] = 1;
            }

            $data['case_types'] = [];
            if(isset($activeCaseType['results']) && !empty($activeCaseType['results'])) 
            {
                $data['case_types'] = $activeCaseType['results'];
            }

            $order_date = $RegistrationModel->getOrderDate($dairy_no);
            $data['order_date'] = $order_date;

            $data['success'] = 1;
            $data['html'] = view('Judicial/Registration/register', $data, ['saveData' => true]);
        } else {
            session()->setFlashdata("message_error", 'Record could not be fetched.');
            $data['redirect'] = base_url('Judicial/Registration/index');
            $data['success'] = 0;
            $data['error'] = "Record could not be fetched.";
        }

        return $this->response->setJSON($data);
    }

    public function update()
    {
        $request = \Config\Services::request();

        $RegistrationModel = new RegistrationModel();

        $dairy_no = $request->getPost('dairy_no');
        // $dairy_no = "12024";
        
        if ($RegistrationModel->cancelRegistration($dairy_no) === true) {
            $data['success'] = 1;
            $data['message'] = "Case registration canceled successfully.";
        } else {
            $data['success'] = 0;
            $data['error'] = "Record could not be updated.";
        }

        return $this->response->setJSON($data);
    }

    public function cancel()
    {
        $request = \Config\Services::request();

        // print_r($_POST);

        $filing_details = [];
        $Dropdown_list_model = new Dropdown_list_model();
        
        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]']
        ])) {

            $search_type = $request->getGetPost('search_type');

            if ($search_type == 'D' && $this->validate([
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $diary_number = $request->getGetPost('diary_number');
                $diary_year = $request->getGetPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $filing_details = $Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                
                $input_query['diary_number'] = $diary_number;
                $input_query['diary_year'] = $diary_year;

            } elseif ($search_type == 'C' && $this->validate([
                'case_type_casecode' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
                'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $case_type = $request->getGetPost('case_type_casecode');
                $case_number = $request->getGetPost('case_number');
                $case_year = $request->getGetPost('case_year');
                
                $filing_details = $Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

                if(!empty($filing_details)) {
                    $diary_info = get_diary_numyear($filing_details['diary_no']);

                    $diary_number = $diary_info[0];
                    $diary_year = $diary_info[1];
                }
            }

            if (!empty($filing_details)) {

                // pr($filing_details);

                return $this->processCancel($filing_details);

                // $this->session->set(array('filing_details' => $get_main_table));
                // return redirect()->to('Judicial/Proposal/redirect_on_diary_user_type');

            } else if($search_type == 'D') {
                session()->setFlashdata("message_error", 'Case not Found');
            } else if($search_type == 'C') {
                session()->setFlashdata("message_error", 'Case not Found');
            }

            redirect()->to('Judicial/Registration/cancel')->withInput();
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = 'Judicial / Registration >> Cancel Registration';
        $data['formAction'] = 'Judicial/Registration/cancel';

        return view('Judicial/diary_search', $data);
    }

    public function processCancel($filing_details = [])
    {
        // pr($filing_details);
        
        $RegistrationModel = new RegistrationModel();

        $dairy_no = $filing_details['diary_no'];

        $data['message'] = "";
        $data['allow_cancel'] = "0";

        $row = $RegistrationModel->getCase($dairy_no);

        if($row === false) {
            $data['message'] = 'No Record Found';
        } elseif ($row['c_status'] == 'P') {
            if ($row['active_fil_no'] == '' || $row['active_fil_dt'] == '0000-00-00 00:00:00') {
                $data['message'] = 'Case is not yet registered.';
            } elseif (($row['active_fil_no'] == $row['fil_no'] && $row['active_fil_dt'] == $row['fil_dt'])
                || ($row['active_fil_no'] == $row['fil_no_fh'] && $row['active_fil_dt'] == $row['fil_dt_fh'])
            ) {

                $casecode = substr($row['active_fil_no'], 0, 2);
                
                $res_casetype = '';
                $caseTypeRow = $RegistrationModel->getCaseType($casecode);

                // Fetch the result if available
                if (!empty($caseTypeRow)) {
                    $res_casetype = $caseTypeRow['short_description']; // Using getRow() to fetch the first row
                }

                // Check the conditions
                if ($row['active_fil_no'] == $row['fil_no'] && $row['active_fil_dt'] == $row['fil_dt']) {
                    $chk_m_f = " in motion stage";
                } else if ($row['active_fil_no'] == $row['fil_no_fh'] && $row['active_fil_dt'] == $row['fil_dt_fh']) {
                    $chk_m_f = " in Regular stage";
                }

                $data['allow_cancel'] = "1";
                $data['message'] = "Active case No. is $res_casetype. " . substr($row['active_fil_no'], 3) . '/' . substr($row['active_fil_dt'], 0, 4) . " $chk_m_f Click OK to cancel registration.";

                $row_roster = $RegistrationModel->getRoaster($dairy_no);

                // Check if any rows were returned
                if (!empty($row_roster)) {

                    if ($row_roster['next_dt'] != null || $row_roster['next_dt'] != "0000-00-00 00:00:00") {
                        $data['message'] = "Case is listed on " . date("d-m-Y", strtotime($row_roster['next_dt'])) . ", Registration can't be cancelled.";
                        $data['allow_cancel'] = "0";
                    }
                }
            } else {
                $data['message'] = 'Error in record. Please contact server room';
            }
        } else {
            $data['message'] = 'Case is already disposed';
        }

        $data['dairy_no'] = $dairy_no;
        $data['formAction'] = 'Judicial/Registration/update';

        // pr($data);

        return view('Judicial/Registration/cancel', $data);
    }
}
