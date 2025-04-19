<?php

namespace App\Controllers\Reports\Filing;

use App\Controllers\BaseController;
use App\Controllers\Filing\File_trap;
use App\Models\Reports\Filing\ReportModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_diary;
use Cassandra\Varint;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Model;


class Report extends BaseController
{
    public $Dropdown_list_model;
    public $Model_diary;

    function __construct()
    {
        //ini_set('memory_limit', '1024M'); // This also needs to be increased in some cases. )
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_diary = new Model_diary();
    }

    public function index()
    {
        $data['url'] = 'dak_search';
        return view('Reports/filing/report', $data);
    }

    public function get_search_view()
    {
        $type = $_REQUEST['type'];
        if (!empty($type)) {
            $type = (int)$type;
            switch ($type) {
                case 1: //Diary
                    $data['casetype'] = get_from_table_json('casetype');
                    return view('Reports/filing/diary_search_view', $data);
                    break;
                case 2: //Caveat
                    $data['casetype'] = get_from_table_json('casetype');
                    return view('Reports/filing/caveat_search_view', $data);
                    break;
                case 3: //Dak
                    $data['usersection'] = $this->Dropdown_list_model->get_usersection();
                    return view('Reports/filing/dak_search_view', $data);
                    break;
                case 4: //FIl Trap
                    return view('Reports/filing/filtrap_search_view');
                    break;
                case 5: //Case Search
                    $data['usersection'] = $this->Dropdown_list_model->get_usersection();
                    $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
                    $data['usersection'] = $this->Dropdown_list_model->get_usersection();
                    $data['state'] = get_from_table_json('state');
                    $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
                    $role = $this->Model_diary->get_role_fil_trap(session()->get('login')['usercode']);
                    $data['casetype'] = $this->Dropdown_list_model->get_case_type($role);
                    $data['casetype_nature_sci'] = $this->Dropdown_list_model->get_case_type($role, 'nature_sci');
                    $data['role'] = $role;
                    //print_r($role);exit;
                    return view('Reports/filing/casesearch_search_view', $data);
                    break;
                case 6: //Refiling
                    return view('Reports/filing/refiling_search_view');
                    break;
                case 7: //Reports master
                    return view('Reports/filing/report_master_search_view');
                    break;
                case 8: //Dynamic Search
                    $data['app_name'] = "Advance Query";
                    $data['caseTypes'] = $this->Dropdown_list_model->get_case_type_list();
                    $data['Sections'] = $this->Dropdown_list_model->getSections();
                    $data['MCategories'] = $this->Dropdown_list_model->getMainSubjectCategory();
                    $data['states'] = $this->Dropdown_list_model->get_state();
                    $data['judges'] = $this->Dropdown_list_model->get_judges();
                    $data['aors'] = $this->Dropdown_list_model->get_aor();
                    return view('Reports/filing/dynamic_search_view', $data);
                    break;
                case 9: //filing_monitoring
                    return view('Reports/filing_reports/defect_reports');
                    break;
                case 10: //scrutiny
                    return view('Reports/filing_reports/scrutiny_case');
                    break;
                case 11: //management_report
                    $data['param'] = null; //add all the requested params
                    $data['Sections'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/filing/defective_matters_not_listed', $data);
                    break;
                case 12: //High_Court_report
                    return view('Reports/filing/high_court_report');
                    break;
                case 13: //UOI SLPs
                    return view('Reports/filing/uoi_slp');
                    break;
                case 15: //Defective Cases Report
                    $data['usersection'] = $this->Dropdown_list_model->get_usersection();
                    return view('Reports/filing/defective_case_search_view', $data);
                    break;
                case 16: //Change Category Report
                    return view('Reports/filing/change_category_search_view');
                    break;
                case 17: //Change Category Report
                    $data['param'] = null; //add all the requested params
                    // $data['Sections'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/filing/casetrap');
                    break;
                case 18: //Change Category Report
                    $data['param'] = null; //add all the requested params
                    $empid = session()->get('login')['empid'];
                    $data['sections'] = $this->Dropdown_list_model->get_work_done_ib_section($empid);
                    return view('Reports/filing/work_done_IBextension_search', $data);
                    break;
                case 19: //Complete Filing Report
                    return view('Reports/filing/complete_filing_report');
                    break;
                case 20: //Diary RP/Cur/Cont count Report
                    return view('Reports/filing/rcc_count_view');
                    break;
                case 21: //Diary RP/Cur/Cont count Report
                    return view('Reports/filing/loose_document_view');
                    break;
                case 22: //Diary RP/Cur/Cont count Report
                    $data['param'] = null; //add all the requested params
                    $empid = session()->get('login')['empid'];
                    $data['sections'] = $this->Dropdown_list_model->getSections();
                    return view('Reports/filing/defectiveMattersSearch', $data);
                    break;
                default:
            }
        }
    }

    public function dak_search_view()
    {
        $data['url'] = 'dak_search';
        $data['tab_type'] = 3;
        return view('Reports/filing/report', $data);
    }

    public function complete_filing_report_view()
    {
        $data['url'] = 'complete_filing';
        $data['tab_type'] = 19;
        return view('Reports/filing/report', $data);
    }

    public function diary_search_view()
    {
        $data['casetype'] = get_from_table_json('casetype');
        $data['url'] = 'diary_search';
        $data['tab_type'] = 1;
        return view('Reports/filing/report', $data);
    }

    public function rcc_count_view()
    {
        $data['url'] = 'rcc_count_report';
        $data['tab_type'] = 20;
        return view('Reports/filing/report', $data);
    }


    public function diary_search()
    {
        $ReportModel = new ReportModel();
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no') . $this->request->getPost('diary_year');
        $data['isma'] = $this->request->getPost('isma');
        $data['is_inperson'] = $this->request->getPost('is_inperson');
        $data['reg_or_def'] = $this->request->getPost('reg_or_def');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');
        $data['is_efiled_pfiled'] = $this->request->getPost('is_efiled_pfiled');
        $data['diary_year'] = $this->request->getPost('diary_year');
        $data['status'] = $this->request->getPost('ddl_status');
        $data['ddl_party_type'] = $this->request->getPost('ddl_party_type');
        $ddl_party_type = $this->request->getPost('ddl_party_type');
        $csps = $this->request->getPost('csps');
        $data['cause_title'] = $cause_title = !empty($this->request->getPost('cause_title')) ? strtoupper($this->request->getPost('cause_title')) : '';
        $response_validation = '';
        if (empty($data['from_date']) && empty($data['diary_no']) && empty($cause_title)) {
            $response_validation = "Please Fill any one field .";
        }
        if (!empty($data['from_date'])) {
            if (empty($data['to_date'])) {
                $response_validation = "Please select to date.";
            } elseif (!empty($data['to_date'])) {
                $if_diffDays = 31;
                $timestamp1 = strtotime($data['from_date']);
                $timestamp2 = strtotime($data['to_date']);
                $datediff = $timestamp2 - $timestamp1;
                $diffDays = round($datediff / (60 * 60 * 24));
                if ($timestamp1 > $timestamp2) {
                    $response_validation = "To Date must be greater than From date";
                }
                // if ($diffDays > $if_diffDays) {
                //     $response_validation = "The days interval must be one month";
                // }
            }
        }
        if (!empty($data['diary_no'])) {
            if (empty($data['diary_year'])) {
                $response_validation = "Please Select Year.";
            }
        }
        if ($response_validation != '') {
            echo "3@@@" . $response_validation;
            exit();
        }
        if (!empty($data)) {
            if ($csps == 'CS') {

                if ($ddl_party_type == 'All') {
                    $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
                } else if ($ddl_party_type == 'P') {
                    $data['parties'] = ['pet_name' => $cause_title];
                } else if ($ddl_party_type == 'R') {
                    $data['parties'] = ['res_name' => $cause_title];
                } else {
                    $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
                }

                $data['ReportsofDiary'] = $ReportModel->getDiaryCauseTitleSearch($data);
                $data['report_title'] = 'Details of Dairy Search with Selected Filter';
                $result_view = view('Reports/filing/get_content_dairy', $data);
                echo '1@@@' . $result_view;
                exit();
                //  print_r($data);exit;
            } else {
                if ($ddl_party_type == 'All') {
                    $data['parties'] = ['p.partyname' => $cause_title];
                } else {
                    $data['parties'] = ['p.partyname' => $cause_title];
                    $data['party_type'] = ['p.pet_res' => $ddl_party_type];
                }

                $data['ReportsofPartySearch'] = $ReportModel->getDiaryPartySearch($data);

                $data['report_title'] = 'Details of Dairy Search with Selected Filter';
                $result_view = view('Reports/filing/get_content_party_search', $data);
                echo '1@@@' . $result_view;
                exit();
            }
        }
    }

    public function caveat_search()
    {
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['caveat_no'] = $this->request->getPost('caveat_no') . $this->request->getPost('caveat_year');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');
        $data['status'] = $this->request->getPost('ddl_status');
        $data['cause_title'] = $this->request->getPost('cause_title');
        $ddl_party_type = $this->request->getPost('ddl_party_type');
        $data['ddl_party_type'] = $this->request->getPost('ddl_party_type');
        $data['caveat_year'] = $this->request->getPost('caveat_year');
        $data['cause_title'] = $cause_title = !empty($this->request->getPost('cause_title')) ? strtoupper($this->request->getPost('cause_title')) : '';
        // $csps = $this->request->getPost('csps');
        $response_validation = '';
        if (empty($data['from_date']) && empty($data['caveat_no']) && empty($cause_title)) {
            $response_validation = "Please Fill any one field .";
        }
        if (!empty($data['from_date'])) {
            if (empty($data['to_date'])) {
                $response_validation = "Please select to date.";
            } elseif (!empty($data['to_date'])) {
                $if_diffDays = 31;
                $timestamp1 = strtotime($data['from_date']);
                $timestamp2 = strtotime($data['to_date']);
                $datediff = $timestamp2 - $timestamp1;
                $diffDays = round($datediff / (60 * 60 * 24));
                if ($timestamp1 > $timestamp2) {
                    $response_validation = "To Date must be greater than From date";
                }
                if ($diffDays > $if_diffDays) {
                    $response_validation = "The days interval must be one month";
                }
            }
        }
        if (!empty($data['caveat_no'])) {
            if (empty($data['caveat_year'])) {
                $response_validation = "Please Select Year.";
            }
        }
        if ($response_validation != '') {
            echo "3@@@" . $response_validation;
            exit();
        }
        if (!empty($data)) {
            //  if($csps=='CS') {

            if ($ddl_party_type == 'All') {
                $data['parties'] = ['c.pet_name' => $cause_title, 'c.res_name' => $cause_title];
            } else if ($ddl_party_type == 'P') {
                $data['parties'] = ['c.pet_name' => $cause_title];
            } else if ($ddl_party_type == 'R') {
                $data['parties'] = ['c.res_name' => $cause_title];
            }
            $data['Reportsofcaveat'] = $ReportModel->getCaveatCauseTitleSearch($data);
            $data['report_title'] = 'Details of Dairy Search with Selected Filter';
            $result_view = view('Reports/filing/get_content_caveat', $data);
            echo '1@@@' . $result_view;
            exit();
            //  print_r($data);exit;
            /* }else{
                 if ($ddl_party_type == 'All') {
                     $data['parties'] = ['partyname' => $cause_title];
                 } else {
                     $data['parties'] = ['partyname' => $cause_title];
                     $data['party_type'] = ['pet_res' => $ddl_party_type];
                 }


                 $data['ReportsofcaveatPartySearch'] = $ReportModel->getCaveatPartySearch($data);
                 $data['report_title'] = 'Details of Caveat Search with Selected Filter';
                 $result_view= view('Reports/filing/get_content_caveat_party_search',$data);
                 echo '1@@@'.$result_view;exit();
             }*/
        }
    }

    public function fil_trap_search()
    {

        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no') . $this->request->getPost('diary_year');
        $data['diary_year'] = $this->request->getPost('diary_year');
        $data['incompleteandcompletematter'] = $this->request->getPost('incompleteandcompletematter');
        $data['reportview'] = $this->request->getPost('reportview');
        if (!empty($data) && $data['incompleteandcompletematter'] != 'im') {
            $data['ReportsoffileTrap'] = $ReportModel->getfileTrap($data);
        }
        $data['formdata'] = $this->request->getPost();
        $data['report_title'] = 'Details of File Trap Search with Selected Filter';
        if ($data['incompleteandcompletematter'] == 'cv') {
            return view('Reports/filing/get_content_filetrap', $data);
        } elseif ($data['incompleteandcompletematter'] == 'im') {
            $File_trap = new File_trap();
            $result = $File_trap->index();
            echo $result;
            exit();
        } else {
            return view('Reports/filing/get_content_complete_filetrap', $data);
        }
        exit;
    }
    public function getSectionWiseDakDetails($from_date = null, $is_excluded_flag = null, $section = null)
    {
        $ReportModel = new ReportModel();
        $data['for_date'] = $from_date;
        $data['section'] = $section;
        $data['is_excluded_flag'] = $is_excluded_flag;
        //var_dump($data);exit();

        if (!empty($data['for_date'])) {
            $data['section_wise_dak_data1'] = $ReportModel->getDAKSectionWiseDetails($data);
        }
        return view('Reports/filing/section_wise_dak_list1', $data);
    }
    public function getSectionWiseDAKCaseDetails($from_date = null, $section = null, $is_excluded_flag = null)
    {
        $ReportModel = new ReportModel();
        $data['for_date'] = $from_date;
        $data['is_excluded_flag'] = $is_excluded_flag;
        $data['section'] = $section;
        //var_dump($data);exit();
        if (!empty($data['for_date'])) {
            // pr($data);
            $data['section_wise_dak_data2'] = $ReportModel->getDAKSectionWiseCaseDetails($data);
        }
        return view('Reports/filing/section_wise_dak_list2', $data);
    }

    public function dak_search()
    {
        $ReportModel = new ReportModel();
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['document_no'] = $this->request->getPost('document_no');
        $data['doc_year'] = $this->request->getPost('doc_year');
        $data['respondent_department'] = $this->request->getPost('respondent_department');
        $data['respondent_user'] = $this->request->getPost('respondent_user');
        $data['Case_Blocked'] = $this->request->getPost('Case_Blocked');
        $data['section'] = $this->request->getPost('section');
        $data['dak_report_type'] = $this->request->getPost('dakReportsType');
        $data['exclude_review_contempt_curative_petition'] = $this->request->getPost('exclude_review_contempt_curative_petition');
        if (!empty($data['Case_Blocked'])) {
            $data['Reportsofdakcb'] = $ReportModel->getdakcb($data);
            $data['report_title'] = 'Reportsofdakcb';
        } elseif (!empty($data['document_no'] && !empty($data['doc_year']))) {
            $data['Reportsofdak'] = $ReportModel->getDakByDocumentNo($data);
            $data['report_title'] = 'DakByDocumentNo';
        } else if (!empty($data['from_date']) && !empty($data['to_date']) && $data['dak_report_type'] == 'ld') {
            $data['Reportsofdak'] = $ReportModel->getLooseDocumentsReport($data);
            $data['report_title'] = 'Reportsofdak';
        } else if (!empty($data['from_date']) && !empty($data['to_date']) && $data['dak_report_type'] == 'ds') {
            $data['section_wise_dak_data'] = $this->dak_date_section_wise($data['from_date'], $data['to_date'], $data['section'], $data['dak_report_type'], $data['exclude_review_contempt_curative_petition']);
        } else {
            $data['Reportsofdak'] = $ReportModel->getdak($data);
            $data['report_title'] = 'Reportsofdak';
        }

        $data['formdata'] = $this->request->getPost();
        $data['casetype'] = get_from_table_json('casetype');
        $data['usersection'] = $this->Dropdown_list_model->get_usersection();
        if (!empty($data['document_no'] && !empty($data['doc_year']))) {
            return view('Reports/filing/dak_by_document_no_details', $data);
        } else if (!empty($data['from_date']) && !empty($data['to_date']) && $data['dak_report_type'] == 'ds') {
            return view('Reports/filing/section_wise_dak_list', $data);
        } else {
            return view('Reports/filing/get_content_dak', $data);
        }
    }

    public function dak_date_section_wise($from_date = null, $to_date = null, $section = null, $dak_report_type = null, $exclude_review_contempt_curative_petition = null)
    {
        //$data['section_wise_dak_data'] = null;
        //$data['param']=null;
        $data = ['section_wise_dak_data'];
        if (!empty($from_date) && !empty($to_date) && $dak_report_type == 'ds') {
            $ReportModel = new ReportModel();
            $data['section_wise_dak_data'] = $ReportModel->getDAKSectionWise($from_date, $to_date, $section, $exclude_review_contempt_curative_petition);
        }
        $data['param'] = array($from_date, $to_date, $exclude_review_contempt_curative_petition, $section);
        // pr($data);
        return $data;
    }

    public function case_search()
    {
        $ReportModel = new ReportModel();

        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no') . $this->request->getPost('diary_year');
        $data['isma'] = $this->request->getPost('isma');
        $data['is_inperson'] = $this->request->getPost('is_inperson');
        $data['reg_or_def'] = $this->request->getPost('reg_or_def');
        $data['case_type_casecode'] = $this->request->getPost('case_type_casecode');
        $data['is_pfield'] = $this->request->getPost('is_pfield');
        $data['is_efield'] = $this->request->getPost('is_efield');
        $data['caveatordiary'] = $this->request->getPost('search_against_order_details');
        $data['ddl_court'] = $this->request->getPost('ddl_court');
        $data['ddl_st_agncy'] = $this->request->getPost('ddl_st_agncy');
        $data['ddl_bench'] = $this->request->getPost('ddl_bench');
        $data['ddl_nature'] = $this->request->getPost('ddl_nature');
        $data['txt_ref_caseno'] = $this->request->getPost('txt_ref_caseno');
        $data['case_year'] = $this->request->getPost('case_year');


        if (!empty($data)) {

            $ddl_party_type = $this->request->getPost('ddl_party_type');
            $data['ddl_party_type'] = $ddl_party_type;
            $data['cause_title'] = $cause_title = !empty($this->request->getPost('case_title_search')) ? strtoupper($this->request->getPost('case_title_search')) : '';

            if ($ddl_party_type == '') {
                $data['parties'] = ['pet_name' => $cause_title, 'res_name' => $cause_title];
            } else if ($ddl_party_type == 'P') {
                $data['parties'] = ['pet_name' => $cause_title];
            } else if ($ddl_party_type == 'R') {
                $data['parties'] = ['res_name' => $cause_title];
            }


            if (!empty($data['caveatordiary']) and $data['caveatordiary'] == 'diary') {

                $data['dairyCaseSearch'] = $ReportModel->getCasesearch_diary($data);
            } else if (!empty($data['caveatordiary']) and $data['caveatordiary'] == 'caveat') {

                $data['caveatCaseSearch'] = $ReportModel->getCasesearch_caveat($data);
            } else {
                $data['ReportsofCaseSearch'] = $ReportModel->getCasesearch($data);
            }
        }
        //print_r($data); exit;
        $data['formdata'] = $this->request->getPost();
        $data['casetype'] = get_from_table_json('casetype');
        $data['usersection'] = $this->Dropdown_list_model->get_usersection();
        $data['report_title'] = 'Details of Caveat Case Search with Selected Filter';

        return view('Reports/filing/get_content_casesearch', $data);
        exit;
    }

    public function refiling_search()
    {

        $ReportModel = new ReportModel();
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['diary_no'] = $this->request->getPost('diary_no') . $this->request->getPost('diary_year');
        $data['diary_year'] = $this->request->getPost('diary_year');
        $data['Reportsrefiling'] = $ReportModel->getRefiling($data);
        $data['formdata'] = $this->request->getPost();
        $data['casetype'] = get_from_table_json('casetype');
        $data['usersection'] = $this->Dropdown_list_model->get_usersection();
        $data['report_title'] = 'Details of Refiling Search with Selected Filter';

        return view('Reports/filing/get_content_refiling', $data);
        exit;
    }

    public function report_master_search()
    {
        $ReportModel = new ReportModel();
        $data['from_date'] = $this->request->getPost('from_date');
        $data['to_date'] = $this->request->getPost('to_date');
        $data['ddl_users'] = $this->request->getPost('ddl_users');
        $data['reportview'] = $this->request->getPost('reportview');
        $reportview = $data['reportview'];
        if (!empty($reportview)) {
            switch ($reportview) {
                case 'ca': //Case Allotted
                    $data['ReportsCaseAllotted'] = $ReportModel->getCaseAllotted($data);
                    return view('Reports/filing/get_content_case_allotted', $data);
                    exit;
                    break;
                case 'cv': //Case Verification
                    $data['ReportsCaseVerification'] = $ReportModel->getCaseVerification($data);

                    return view('Reports/filing/get_content_case_verification', $data);
                    exit;
                    break;
                case 'fsm': //Fresh Scrutiny Matters
                    $data['ReportsFreshScrutinyMatters'] = $ReportModel->getFreshScrutinyMatters($data);
                    return view('Reports/filing/get_content_fresh_scrutiny_matters', $data);
                    exit;
                    break;
                case 'ldu': //Loose Doc User-Wise
                    $data['ReportsLooseDocUserWise'] = $ReportModel->getLooseDocUserWise($data);
                    return view('Reports/filing/get_content_loose_doc_userwise', $data);
                    exit;
                    break;
                case 'smpr': //Sensitive Matters - Pending and Not Ready
                    $data['ReportsSensitiveMattersPendingandNotReady'] = $ReportModel->getSensitiveMattersPendingandNotReady($data);
                    return view('Reports/filing/get_content_sensitive_matters_pending_notready', $data);
                    exit;
                    break;
                default:
            }
        }
    }

    public function get_casetype()
    {
        $data_array = $this->Dropdown_list_model->get_casetype($this->request->getGet('type'));
        echo json_encode($data_array);
    }

    public function get_Sub_Subject_Category()
    {

        $data_array = $this->Dropdown_list_model->get_Sub_Subject_Category($this->request->getGet('Mcat'));
        echo json_encode($data_array);
    }

    public function get_da()
    {
        $data_array = $this->Dropdown_list_model->get_da($this->request->getGet('section'));
        echo json_encode($data_array);
    }

    public function get_agency()
    {
        $data_array = $this->Dropdown_list_model->get_agency_code($this->request->getGet('state'), $this->request->getGet('agency'));
        echo json_encode($data_array);
    }

    public function dynamic_search()
    {
        //echo 'reach'; 
        //print_r($this->request->getGet());exit;

        $ReportModel = new ReportModel();

        // $data['from_date'] = $this->request->getGet('from_date');
        // $data['to_date'] = $this->request->getGet('to_date');
        // $data['diary_no'] = $this->request->getGet('diary_no').$this->request->getGet('diary_year');
        // $data['diary_year'] = $this->request->getGet('diary_year');
        // $data['Reportsrefiling']= $ReportModel->getRefiling($data);

        $data['formdata'] = $this->request->getGet();
        $data['casetype'] = get_from_table_json('casetype');
        $data['usersection'] = $this->Dropdown_list_model->get_usersection();
        $data['MCategories'] = $this->Dropdown_list_model->getMainSubjectCategory();
        $data['report_title'] = 'Details of Dynamic Search with Selected Filter';
        //print_r($data);exit;
        $casetype = "";
        $casetype_name = "";
        if (!empty($_GET['figure']))
            $option = "1";
        else if (!empty($_GET['full']))
            $option = "2";

        $casestatus = $this->request->getGet('rbtCaseStatus');
        $pendencyOption = $this->request->getGet('rbtPendingOption');
        $filingDateFrom = $this->request->getGet('filingDateFrom');
        $filingDateTo = $this->request->getGet('filingDateTo');
        $registrationDateFrom = $this->request->getGet('registrationDateFrom');
        $registrationDateTo = $this->request->getGet('registrationDateTo');
        $caseYear = $this->request->getGet('caseYear');
        $disposalDateFrom = $this->request->getGet('disposalDateFrom');
        $disposalDateTo = $this->request->getGet('disposalDateTo');
        $rbtCaseType = $this->request->getGet('rbtCaseType');
        $casetypeList = $this->request->getGet('caseType');

        //dd($casetypeList); exit;
        if ($casetypeList) {
            foreach ($casetypeList as $casetype1) {
                $casetype1 = explode("^", $casetype1);
                $casetype .= $casetype1[0] . ",";
                $casetype_name .= $casetype1[1] . ",";
            }
            $casetype = rtrim($casetype, ",");
            $casetype_name = rtrim($casetype_name, ",");
        }
        $matterType = $this->request->getGet('matterType');
        $respondentName = $this->request->getGet('respondentName');
        $por = $this->request->getGet('PorR');
        $diaryYear = $this->request->getGet('diaryYear');
        $subjectCategoryList = $this->request->getGet('subjectCategory');
        if ($subjectCategoryList) {
            $subjectCategoryList = explode("^", $subjectCategoryList);
            $subjectCategory = $subjectCategoryList[0];
            $subjectCategory_name = $subjectCategoryList[1];
            $subCategoryCodeList = $this->request->getGet('subCategoryCode');
            $subCategoryCodeList = explode("^", $subCategoryCodeList);
            $subCategoryCode = $subCategoryCodeList[0];
            $subCategoryCode_name_list = explode("#-#", $subCategoryCodeList[1]);
            $subCategoryCode_name = $subCategoryCode_name_list[1] . " (" . $subCategoryCode_name_list[0] . ")";
        }
        $sections = $this->request->getGet('section');
        if ($sections) {
            $sections = explode("^", $sections);
            $section = $sections[0];
            $section_name = $sections[1];
        }
        $da = $this->request->getGet('dealingAssistant');
        if ($da) {
            $das = explode("^", $da);
            $dacode = $das[0];
            $daname = $das[1];
        }
        $chkshowDA = $this->request->getGet('showDA');
        $agencyStateList = $this->request->getGet('agencyState');
        if ($agencyStateList) {
            $agencyStateList = explode("^", $agencyStateList);
            $agencyState = $agencyStateList[0];
            $state_name = $agencyStateList[1];
            $agencyCodeList = $this->request->getGet('agencyCode');
            $agencyCodeList = explode("^", $agencyCodeList);
            $agencyCode = $agencyCodeList[0];
            $code_name = $agencyCodeList[1];
        }
        $listingDate = $this->request->getGet('listingDate');
        //$coramList=$this->request->getGet('coram');
        //$coramList=explode("^",$coramList);
        //$coram=$coramList[0];
        //$judge_name=$coramList[1];
        $rbtCoram = $this->request->getGet('rbtCoram');
        $chkJailMatter = $this->request->getGet('chkJailMatter');
        $chkFDMatter = $this->request->getGet('chkFDMatter');
        $chkLegalAid = $this->request->getGet('chkLegalAid');
        $chkSpecificDate = $this->request->getGet('chkSpecificDate');
        $chkPartHeard = $this->request->getGet('chkPartHeard');
        $aor = $this->request->getGet('advocate');
        if ($aor) {
            $aors = explode("^", $aor);
            $bar_id = $aors[0];
            $aor_name = $aors[1];
        }
        $sortList = $this->request->getGet('sort');
        $sortList = explode("^", $sortList);
        $sort = $sortList[0];
        $sortOption = '';
        $sort_name = $sortList[1];
        $sortOrder = $this->request->getGet('rbtSortOrder');
        $joinCondition = '';
        $advPor = $this->request->getGet('advPorR');
        if ($casestatus == 'f') {
            $criteria = "<b>Case Status :</b> Filing <br/>";
            $condition = "1=1";
        } else if ($casestatus == 'i') {
            $criteria = "<b>Case Status :</b> Registration <br/>";
            $condition = " active_fil_no is not null and active_fil_no!='' ";
        } else if ($casestatus == 'p') {
            $criteria = "<b>Case Status :</b> Pending ";
            $condition = " c_status='P'";
        } else if ($casestatus == 'd') {
            $criteria = "<b>Case Status :</b> Disposed <br/>";
            $condition = " c_status='D'";
        }
        if ($pendencyOption == 'R' and $casestatus == 'p') {
            $criteria .= " -Registered Matters <br/>";
            $condition .= " and active_fil_no is not null and active_fil_no!=''";
        } else if ($pendencyOption == 'UR' and $casestatus == 'p') {
            $criteria .= " -Unregistered Matters <br/>";
            $condition .= " and (active_fil_no='' or active_fil_no is null)";
        } else if ($pendencyOption == 'b' and $casestatus == 'p') {
            $criteria .= " -All Matters <br/>";
        }
        if (!empty($filingDateFrom) and !empty($filingDateTo)) {
            $condition .= " and date(diary_no_rec_date) between '" . date('Y-m-d', strtotime($filingDateFrom)) . "' and '" . date('Y-m-d', strtotime($filingDateTo)) . "'";
            $criteria .= "<b>Filing Date From </b>" . $filingDateFrom . "<b> To </b>" . $filingDateTo . "<br>";
        }
        if (!empty($registrationDateFrom) and !empty($registrationDateTo)) {
            $condition .= " and date(active_fil_dt) between '" . date('Y-m-d', strtotime($registrationDateFrom)) . "' and '" . date('Y-m-d', strtotime($registrationDateTo)) . "'";
            $criteria .= "<b>Registration Date From </b>" . $registrationDateFrom . "<b> To </b>" . $registrationDateTo . "<br>";
        }
        if (!empty($disposalDateFrom) and !empty($disposalDateTo)) {
            $condition .= " and date(d.ord_dt) between '" . date('Y-m-d', strtotime($disposalDateFrom)) . "' and '" . date('Y-m-d', strtotime($disposalDateTo)) . "'";
            $criteria .= "<b>Disposal Date From </b>" . $disposalDateFrom . "<b> To </b>" . $disposalDateTo . "<br>";
        }
        if ($caseYear != 0) {
            $condition .= " and active_reg_year=$caseYear";
            $criteria .= "<b> Registration Year : </b>" . $caseYear . "<br>";
        }
        if ($rbtCaseType == 'C') {
            $condition .= " and case_grp='C'";
            $criteria .= "<b> Case Type : </b> Civil Matters<br>";
        } else if ($rbtCaseType == 'R') {
            $condition .= " and case_grp='R'";
            $criteria .= "<b> Case Type : </b> Criminal Matters<br>";
        } else if ($rbtCaseType == 'b') {
            $criteria .= "<b> Case Type : </b> All<br>";
        }
        if (!empty($casetype)) {
            $condition .= " and active_casetype_id in($casetype)";
            $criteria .= "<b> Case Type : </b>" . $casetype_name . "<br>";
        }
        if ($matterType == 'M') {
            $condition .= " and mf_active='M'";
            $criteria .= "<b> Matter Type : </b> Miscelleneous Matters <br>";
        } else if ($matterType == 'F') {
            $condition .= " and mf_active='F'";
            $criteria .= "<b> Matter Type : </b> Regular Matters <br>";
        } else if ($matterType == 'all') {
            $criteria .= "<b> Matter Type : </b> All <br>";
        }
        if (!empty($respondentName)) {
            if (!empty($diaryYear)) {
                if ($por == '1') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and pet_res='P' and pfag in('P','D'))";
                    $criteria .= "<b> Petitioner Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                } else if ($por == '2') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and pet_res='R' and pfag in('P','D'))";
                    $criteria .= "<b> Respondent Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                } else if ($por == '0') {
                    $condition .= " and (partyname like '%$respondentName%' and year(diary_no_rec_date)=$diaryYear and pet_res in('P','R') and pfag in('P','D'))";
                    $criteria .= "<b> Party Name like </b>" . $respondentName . " <b> and Filed in the year </b>" . $diaryYear . "<br>";
                }
            } else {
                if ($por == '1') {
                    $condition .= " and (partyname like '%$respondentName%' and pet_res='P' and pfag in('P','D'))";
                    $criteria .= "<b> Petitioner Name like </b>" . $respondentName . "<br>";
                } else if ($por == '2') {
                    $condition .= " and (partyname like '%$respondentName%' and pet_res='R' and pfag in('P','D'))";
                    $criteria .= "<b> Respondent Name like </b>" . $respondentName . "<br>";
                } else if ($por == '0') {
                    $condition .= " and (partyname like '%$respondentName%' and pet_res in('P','R') and pfag in('P','D'))";
                    $criteria .= "<b> Party Name like </b>" . $respondentName . "<br>";
                }
            }
        }
        if (!empty($section) && $section != 0) {
            $condition .= " and u.section=$section";
            $criteria .= "<b> Section : </b>" . $section_name . "<br>";
        }
        if (!empty($dacode) && $dacode != 0) {
            $condition .= " and u.usercode=$dacode";
            $criteria .= "Matters dealt with by <b>$daname</b><br>";
        }
        if (!empty($subjectCategory) && $subjectCategory != 0) {
            $condition .= " and s.subcode1=$subjectCategory";
            $criteria .= "<b> Main Subject Category : </b>" . $subjectCategory_name . "<br>";
        }

        if (!empty($subCategoryCode) && $subCategoryCode != 0) {
            $condition .= " and s.id=$subCategoryCode";
            $criteria .= "<b> Sub Subject Category : </b>" . $subCategoryCode_name . "<br>";
        } else
            $criteria .= "<b> Sub Subject Category : </b>" . "All<br>";

        if (!empty($agencyState) && $agencyState != 0) {
            $condition .= " and ref_agency_state_id=$agencyState";
            $criteria .= "<b> State : </b>" . $state_name . "<br>";
        }

        if (!empty($agencyCode) && $agencyCode != 0) {
            $condition .= " and ref_agency_code_id=$agencyCode";
            $criteria .= "<b> Agency : </b>" . $code_name . "<br>";
        } else
            $criteria .= "<b> Agency : </b>" . "All<br>";

        if ((int)$chkLegalAid == 1) {
            $condition .= " and if_sclsc=1";
            $criteria .= " Legal Aid Matters <br>";
        }
        if ((int)$chkJailMatter == 1) {
            $joinCondition = "left join jail_petition_details jpd on m.diary_no=jpd.diary_no and jail_display='Y'
                left join brdrem brd on m.diary_no=brd.diary_no and (brd.remark like '%jail%' or brd.remark like '%Jail%')
                left join advocate adv1 on m.diary_no=adv1.diary_no and adv1.advocate_id=613 and adv1.pet_res='P' and adv1.pet_res_no=1 and adv1.display='Y'";
            $condition .= " and (nature=6 or pet_adv_id=613)";
            $criteria .= " Jail Petition Matters <br>";
        }
        if ((int)$chkFDMatter == 1) {
            $condition .= " and h.subhead in(815,816)";
            $criteria .= " Final Disposal Matters <br>";
        }
        if ((int)$chkSpecificDate == 1) {
            $condition .= " and crm.r_head=24";
            $criteria .= " Specific Date Matters <br>";
        }
        if ((int)$chkPartHeard == 1) {
            $condition .= " and h.subhead=824";
            $criteria .= " Part Heard Matters <br>";
        }

        if (!empty($bar_id) && $bar_id != 0) {
            if ($advPor == '1') {
                $condition .= " and advocate_id=$bar_id and pet_res='P'";
                $criteria .= "<b> Petitioner Advocate : </b>$aor_name<br>";
            } else if ($advPor == '2') {
                $condition .= " and advocate_id=$bar_id and pet_res='R'";
                $criteria .= "<b> Respondent Advocate : </b>$aor_name<br>";
            } else if ($advPor == '0') {
                $condition .= " and advocate_id=$bar_id";
                $criteria .= "<b> Advocate : </b>$aor_name<br>";
            }
        }

        if ($sort != 0) {
            if ($sort == 1)
                $sortOption = "cast(substring(m.diary_no,-4) as unsigned) " . $sortOrder . " ,cast(substr(m.diary_no,1,length(m.diary_no)-4) as unsigned) " . $sortOrder;
            else if ($sort == 2)
                $sortOption = "active_reg_year " . $sortOrder . " ,cast(substring(active_fil_no,1,2) as unsigned) " . $sortOrder . " ,cast(substring(active_fil_no,4,6) as unsigned) " . $sortOrder;
            else if ($sort == 3)
                $sortOption = "date(diary_no_rec_date) " . $sortOrder;
            else if ($sort == 4)
                $sortOption = "date(active_fil_dt) " . $sortOrder;
            else if ($sort == 5)
                $sortOption = "us.section_name " . $sortOrder;
            else if ($sort == 6)
                $sortOption = "subcode1 " . $sortOrder . " ,category_sc_old " . $sortOrder;
            else if ($sort == 7)
                $sortOption = "agency_state " . $sortOrder;
            else if ($sort == 8)
                $sortOption = "c_status " . $sortOrder;
            else if ($sort == 9)
                $sortOption = "h.next_dt " . $sortOrder;
            $criteria .= " <b> Sort by : </b>" . $sort_name . "<br>";
        }
        $data['option'] = $option;
        $data['criteria'] = $criteria;
        $data['showDA'] = $chkshowDA;
        $data['result'] = $ReportModel->get_result($option, $condition, $sortOption, $joinCondition);
        //print_r($data['result']);exit;
        // to do====$listingDate,$coram,$rbtCoram,$sort

        //print_r($data['casetype']);
        return view('Reports/filing/get_content_dynamic', $data);
    }

    public function uoi_slp_search()
    {
        $ReportModel = new ReportModel();
        $uoi_slp_counts = $ReportModel->uoi_slp();
        $table = '<div class="table-head">UOI as Petitioner/Respondent SLPs</div>
        <div class="table-responsive">
        <table class="table table-striped custom-table dataTable no-footer">
                    <thead><tr><th>Filing Year</th><th>UOI SLPs</th> <th>Total Matters</th></tr></thead>';
        foreach ($uoi_slp_counts as $row) {
            $table .= '<tbody><tr><td>' . $row['filing_year'] . '</td><td>' . $row['matched'] . '</td><td>' . $row['total'] . '</td></tr></tbody>';
        }
        $table .= '</table>';
        echo $table;
    }

    public function display_registration_report()
    {
        $data['diary_no'] = $this->request->getPost('diary_no');
        $data['diary_year'] = $this->request->getPost('diary_year');
        return view('Reports/filing/get_registration_report', $data);
        exit;
    }

    public function get_defective_cases_report()
    {
        $param['from_date'] = $this->request->getPost('from_date');
        $param['to_date'] = $this->request->getPost('to_date');
        $param['section'] = $this->request->getPost('section');
        $ReportModel = new ReportModel();
        $uoi_slp_counts = $ReportModel->defective_case_count($param);
        $table = '<table class="table table-striped custom-table table-hover dt-responsive" align="center" border="1" width="100%" style="vertical-align: middle; text-align: center;" cellspacing=0>
                     <thead><tr><th style="text-align: center;">28 to 59 Days Old</th>
                     <th style="text-align: center;">60 to 89 Days Old</th>
                     <th style="text-align: center;">90 Days Old</th>
                     <th style="text-align: center;">Total</th>
                     </tr></thead>';
        foreach ($uoi_slp_counts as $row) {
            $table .= '<tr><td>' . $row['days_28'] . '</td><td>' . $row['days_60'] . '</td><td>' . $row['days_90'] . '</td><td>' . $row['days_28'] + $row['days_60'] + $row['days_90'] . '</td></tr>';
        }
        $table .= '</table>';
        echo $table;
    }

    public function get_change_category_report()
    {
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $report_for = $this->request->getPost('report_for');
        $ReportModel = new ReportModel();
        
        $report_data = $ReportModel->change_category_report_data($from_date, $to_date, $report_for);
        if (sizeof($report_data)) {
            $table = '<div style="margin-top: 30px"></div>
            <table class="table table-striped custom-table table-hover dt-responsive"  align="center"  border="1" style="width:100%;border-collapse: collapse">
            <thead><tr>
                <th>SNo.</th>
                <th>Name</th>
                <th>' . (($report_for == 1) ? 'Total Diary No Category Changed' : 'Total Auto Linked Cases Changed') . '</th>
               </thead>  
            </tr>';
            $sno = 0;
            foreach ($report_data as $row) {
                $table .= '<tr><td>' . ++$sno . '</td>
                            <td>' . $row['name'] . '</td>
                            <td><input type="hidden" name="hd_usr_id' . $sno . '" id="hd_usr_id' . $sno . '" value="' . $row['usercode'] . '"/><span id="sp_tot_cases' . $sno . '" class="cl_add_cst">' . $row['s'] . '</span></td>
                            </tr>';
            }
        } else
            $table = '<div style="text-align: center"><b>No Record Found</b></div>';
        
            /*$table = '<table align="center" border="1" width="60%" style="vertical-align: middle; text-align: center;" cellspacing=0>
                     <thead><tr><th style="text-align: center;">28 to 59 Days Old</th>
                     <th style="text-align: center;">60 to 89 Days Old</th>
                     <th style="text-align: center;">90 Days Old</th>
                     <th style="text-align: center;">Total</th>
                     </tr></thead>';
        foreach($uoi_slp_counts as $row){
            $table .= '<tr><td>'.$row['days_28'].'</td><td>'.$row['days_60'].'</td><td>'.$row['days_90'].'</td><td>'.$row['days_28']+$row['days_60']+$row['days_90'].'</td></tr>';
        }
        $table .= '</table>';*/
        echo $table;
    }

    public function get_defective_matters_not_listed()
    {
        $ReportModel = new ReportModel();
        $days = $this->request->getPost('days');
        $i = 0;
        $sel_section = "";
        if(!empty($this->request->getPost('section')))
        {
            foreach ($this->request->getPost('section') as $selected) {
                $sel_section .= $selected . ",";
                $i = $i + 1;
            }
        }
        $sel_section = rtrim($sel_section, ',');

        $sections = ($this->request->getPost('section')) ? $this->request->getPost('section') : [];
        
        $data['app_name'] = 'Defective Matters Not Listed';
        $data['days'] = $days;
        $data['sel_section'] = $sel_section;
        if (isset($days) && isset($sel_section))
            $result_array = $ReportModel->defectiveMattersNotListed($days, $sections);
        $data['section'] = $sel_section;
        $data['defectiveMattersNotListed'] = $result_array;
        return view('Reports/filing/defective_matters_not_listed_details', $data);
        exit;
    }

    public function get_complete_filing_report()
    {
        $report_date = $this->request->getPost('report_date');
        $report_for = $this->request->getPost('report_for');
        $ReportModel = new ReportModel();
        $result_array = $ReportModel->get_complete_filing_report($report_date, $report_for);
        $table = '';
        
        if (sizeof($result_array)) {
            $table = '<div class="table-sec">
                        <div class="table-responsive">
                    <table class="table table-striped custom-table dataTable no-footer"><thead>
                        <tr><th>Total Filed</th><th>Counter Filed</th><th>e- Filed</th><th>Re-filed</th><th>Registered</th><th>Checked Verification</th><th>Verified</th></tr></thead>
                        <tbody>
                        <tr><td>' . $result_array['filed'] . '</td><td>' . $result_array['counter_filed'] . '</td><td>' . $result_array['e_filed'] . '</td><td>' . $result_array['refiled'] . '</td><td>' . $result_array['registered'] . '</td><td>' . $result_array['t_crawl_veri'] . '</td><td>' . $result_array['verified'] . '</td></tr>
                    </table><br><br>
                    <table class="table table-striped custom-table dataTable no-footer"><thead>
                        <tr><th colspan="2">Total Pendency till above Date</th></tr>
                        <tr><th>Pending for Tagging Verification</th><th>Pending Verification after Re-filing</th></tr></thead>
                        <tbody>
                        <tr><td>' . $result_array['pending_tagging'] . '</td><td><span onclick="get_complete_filing_details(' . $report_for . ',1)">' . $result_array['pending_ver_aft_ref'] . '</span> ( <span onclick="get_complete_filing_details(' . $report_for . ',2)">' . $result_array['pending_ver_aft_ref_registered'] . ' Reg)</span></td></tr><tbody>
                    </table>
                    </div></div>';
        }
        echo $table;
    }

    public function get_complete_filing_details()
    {
        $table = '<br><br>';
        $ReportModel = new ReportModel();
        $result_array = $ReportModel->get_complete_filing_details($_POST['report_for'], $_POST['type']);
        if ($_POST['type'] == 1 or $_POST['type'] == 2) {
            $sno = 1;
            $table .= '<table style="margin-left: auto;margin-right: auto;border-collapse: collapse" border="1">
                        <tr><th colspan="5">Records for Pending Verification after Re-filing</th></tr>
                        <tr><th>SNo.</th><th>Diary No</th><th>Reg. No.</th><th>Date of Sending to Tagging</th><th>Date of Registration</th>' . (($_POST['report_for'] == '584' || $_POST['report_for'] == '0') ? '<th>Petitioner In Person</th>' : '') . (($_POST['report_for'] == 'C' || $_POST['report_for'] == '0') ? '<th>Caveat No.</th>' : '');
            foreach ($result_array as $row) {
                $row['verification_date'] = (isset($row['verification_date'])) ? $row['verification_date'] : '';
                $row['caveat_no'] = (isset($row['caveat_no'])) ? $row['caveat_no'] : '';
                $row['caveat_no1'] = (isset($row['caveat_no1'])) ? $row['caveat_no1'] : '';
                $table .= '<tr><th>' . $sno++ . '</th><td>' . substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4) . '</td><td>' . ((!is_null($row['fil_dt'])) ? $row['short_description'] . SUBSTR($row['fil_no'], 3) . '/' . $row['active_reg_year'] : '') . '</td><td>' . date('d-m-Y h:i:s A', strtotime($row['verification_date'])) . '</td><td>' . ((!is_null($row['fil_dt'])) ? date('d-m-Y h:i:s A', strtotime($row['fil_dt'])) : '') . '</td>' . (($_POST['report_for'] == 'C' or $_POST['report_for'] == '584') ? ('<td>' . $row['caveat_no'] . '</td>') : '') . ((($_POST['report_for']) == '0') ? ('<td>' . $row['caveat_no'] . '</td><td>' . $row['caveat_no1'] . '</td>') : '') . '</tr>';
            }
        } else {
            $table .= '<div style="text-align: center;color: red">SORRY, NO RECORD FOUND!!!</div>';
        }
        echo $table;
    }

    function get_rcc_count_report()
    {
        $condition = " and 1=1 ";
        $table = '';
        $casetype = '';
        $report_for = $_POST['report_for'];
        if ($_POST['report_for'] == 0) {
            $condition = $condition. " and casetype_id in(9,10,19,20,25,26)";
        } else {
            $condition = $condition . " and casetype_id=" . $_POST['report_for'];
            $msg="";
            //$caseTypeQuery="Select casename from casetype where display='Y' and casecode=$caseTypeId";
            //$result1=mysql_query($caseTypeQuery) or die("Error: ".__LINE__.mysql_error());
            //$row = mysql_fetch_assoc($result1);

            $row = is_data_from_table('master.casetype', " display='Y' and casecode= $report_for ", "casename","" );
            $msg=$msg."Case Type:".$row['casename'];
           // $caseType_model = new \App\Models\Entities\Model_Casetype();
            //$casetype = $caseType_model->select('casename')->where('casecode', $_POST['report_for'])->where('display', 'Y')->get()->getRowArray();
        }
        $table .= '<h6 style="text-align: center;text-transform: capitalize;color: blue;"> Diary Received between ' . date('d-m-Y', strtotime($_POST['from_date'])) . ' and ' . date('d-m-Y', strtotime($_POST['to_date'])) . '<br>' . (($casetype != '') ? 'Case Type:' . $casetype['casename'] : ' IN     R.P/Curative/Contempt Petition') . '</h6>';
        $ReportModel = new ReportModel();
        $result_array = $ReportModel->rcc_report(date('Y-m-d', strtotime($_POST['from_date'])), date('Y-m-d', strtotime($_POST['to_date'])), $condition);
        //pr($result_array);
        $table .= '<table class="table table-striped custom-table table-hover dt-responsive" id="diaryReport" style="width:100%;">
                    <thead>
                    <tr bgcolor="#dcdcdc">
                        <th style="text-align: center;">Sr.No.</th>
                        <th style="text-align: center;">Section</th>
                        <th style="text-align: center;">Total Filing</th>
                    </tr>
                    </thead>
                    ';
        if (sizeof($result_array) > 0) {
            $sno = 0;
            $total = 0;
            foreach ($result_array as $row) {
                $total += intval($row['total']);
                $table .= '<tbody><tr>
                        <td style="text-align: center;">' . ++$sno . '</td>
                        <td style="text-align: center;">' . $row['section'] . '</td>
                        <td style="text-align: center;"><button class = "btn btn-primary btn-link" onclick="get_rcc_details(\'' . date('d-m-Y', strtotime($_POST['from_date'])) . '\', \'' . date('d-m-Y', strtotime($_POST['to_date'])) . '\', \'' . $condition . '\', \'' . $row['section'] . '\')">' . $row['total'] . '</button></td>
                    </tr></tbody>';
            }
            $table .= '<tbody><tr><td colspan="2" style="text-align: center;font-weight:bold">Total:</td><td style="text-align: center;font-weight:bold">' . $total . '</td></tr></tbody>';
        }
        echo $table;
    }

    public function get_rcc_case_details()
    {
        $ReportModel = new ReportModel();
        $report_data = $ReportModel->rcc_section_detail_report($_POST['from_dt'], $_POST['to_date'], $_POST['condition'], $_POST['section']);
        if (sizeof($report_data)) {
            $table = '<div style="margin-top: 0px"></div>
                <table class="table table-striped custom-table table-hover dt-responsive" style="width:100%;" id="diaryReport" width="100%" border="1" cellspacing="1">
                        <thead>
                        <tr bgcolor="#dcdcdc">
                            <th style="text-align: center;">Sr.No.</th>
                            <th width="17%" style="text-align: left;">Diary No-Year<br>#Diary Date</th>
                            <th>Cause Title</th>
                            <th>Document/IA</th>
                            <th>DA Name</th>' . (($_POST['section'] == '') ? '<th>Section Name</th>' : '') . '
                        </tr>
                        </thead>
                        <tbody>';
            $sno = 0;
            $total = 0;
            foreach ($report_data as $row) {
                $doc = '';
                $a = explode(',', $row['ia_info']);
                for ($i = 0; $i < sizeof($a) - 1; $i++) {
                    $doc_sno = $i + 1;
                    $doc .= "$doc_sno" . ")" . "$a[$i]<br>";
                }

                $table .= '<tr>
                        <td>' . ++$sno . '</td>
                        <td>' . $row['diary_no'] . '-' . $row['diary_year'] . '<br>#' . date('d-m-Y', strtotime($row['diary_date'])) . '<br>' . $row['casename'] . '</td>
                        <td>' . $row['pet_name'] . '<strong> Vs. </strong>' . $row['res_name'] . '</td>
                        <td>' . $doc . '</td>
                        <td>' . $row['da_name'] . '</td>' . (($_POST['section'] == '') ? '<td>' . $row['section_name'] . '</td>' : '') . '</tr>';
            }
            $table .= '</table>';
        } else
            $table = '<div style="text-align: center"><b>No Record Found</b></div>';
        echo $table;
    }
    public function loose_document_view()
    {
        $data['url'] = 'loose_document';
        $data['tab_type'] = 21;
        return view('Reports/filing/report', $data);
    }

    public function loose_document_report($id)
    {
        $ReportModel = new ReportModel();
        if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
            $first_date = date('Y-m-d', strtotime($this->request->getPost('from_date')));
            $to_date = date('Y-m-d', strtotime($this->request->getPost('to_date')));
        } else {
            $first_date = date('Y-m-d', strtotime('first day of this month'));
            $to_date = date('Y-m-d');
        }
        if ($id == 1) {
            $data['report'] = 1;
            $result_array = $ReportModel->loose_document_report($first_date, $to_date, $id);
            $data['loose_document_result'] = $result_array;
            $data['first_date'] = $first_date;
            $data['to_date'] = $to_date;
            // pr($data);
            return view('Reports/filing/get_loose_doc_date_view', $data);
        } else if ($id == 2) {
            $data['report'] = 2;
            $result_array = $ReportModel->loose_document_report($first_date, $to_date, $id);
            $data['loose_document_result1'] = $result_array;
            $data['first_date'] = $first_date;
            $data['to_date'] = $to_date;
            // pr($data);
            return view('Reports/filing/get_loose_doc_user_view', $data);
        }
    }

    public function loose_document_detail()
    {
        $ReportModel = new ReportModel();
        if (isset($_GET['date']))
            $date = $this->request->getGet('date');
        else
            $date = '';
        if (isset($_GET['first_date']))
            $first_date = $this->request->getGet('first_date');
        else
            $first_date = '';
        if (isset($_GET['to_date']))
            $to_date = $this->request->getGet('to_date');
        else
            $to_date = '';
        if (isset($_GET['user']))
            $user = $this->request->getGet('user');
        else
            $user = '';
        if (isset($_GET['sorting']))
            $sorting = $this->request->getGet('sorting');
        else
            $sorting = '1';
        $data['app_name'] = 'Loose Document Report';
        $result_array = $ReportModel->loose_document_detail_report($date, $first_date, $to_date, $user, $sorting);
        $data['document_detail_result'] = $result_array;
        
        $data['date'] = $date;
        $data['first_date'] = $first_date;
        $data['to_date'] = $to_date;
        $data['user'] = $user;
        return view('Reports/filing/get_loose_doc_detail_view', $data);
    }


    public function workdone()
    {
        $ReportModel = new ReportModel();
        $data['app_name'] = 'Work Done IB Extension';
        $empid = session()->get('login')['empid'];
        $data['yoursection'] = $ReportModel->getSectionLIst($empid);
        return view('Reports/workdone', $data);
    }

    public function get_workdone()
    {

        $ReportModel = new ReportModel();
        $data['app_name'] = 'Work Done IB Extension';

        $sec = '';
        $section = '';
        $dcmis_usertype = session()->get('login')['usertype'];

        if ($dcmis_usertype == 1) {
            $empid = session()->get('login')['empid'];
            $yoursection = $ReportModel->getSectionLIst($empid);

            if (!empty($yoursection)) {
                foreach ($yoursection as $row) {
                    $sec .= ',' . $row['usec'];
                }
                $sec = ltrim($sec, ',');
                $section = " AND section IN ($sec) ";
            }
        }
        $ddl_all_blank_a = '';
        if ($_REQUEST['ddl_all_blank'] == 2) {
            $ddl_all_blank_a = " where totdoc is null and totup is null and totoff is null and totnot is null AND 
        supuser is null and red is null AND p_notice_not_made is null AND d_notice_not_made is null 
        AND totdoc_not is null";
        }
        if ($_REQUEST['ddl_all_blank'] == 3) {
            $ddl_all_blank_a = " where totdoc is not null or totup is not null or totoff is not null or 
        totnot is not null or supuser is not null or red is not null or 
        p_notice_not_made is not null or d_notice_not_made is not null or totdoc_not is not null ";
        }


        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "
                    SELECT * FROM (
                        SELECT usercode, u.name, empid, section_name, ut.type_name, section, usertype 
                        FROM master.users u 
                        LEFT JOIN master.usersection us ON u.section = us.id
                        LEFT JOIN master.usertype ut ON u.usertype = ut.id
                        WHERE us.isda = 'Y' 
                        AND u.display = 'Y' 
                        AND us.display = 'Y' 
                        AND u.usertype IN (17,50,51)
                        $section

                        UNION

                        SELECT 0, 'NO DACODE', '0', '0', '0', 0, 0
                    ) t1

                    LEFT JOIN (
                        SELECT COUNT(*) AS totdoc, dacode
                        FROM ld_move a 
                        INNER JOIN docdetails d 
                            ON a.diary_no = d.diary_no 
                            AND a.doccode = d.doccode 
                            AND a.doccode1 = d.doccode1 
                            AND a.docnum = d.docnum 
                            AND a.docyear = d.docyear 
                        INNER JOIN main m ON d.diary_no = m.diary_no
                        LEFT JOIN master.docmaster dm ON d.doccode = dm.doccode 
                            AND d.doccode1 = dm.doccode1
                        WHERE d.ent_dt::date = '$date'  
                        AND d.display = 'Y'
                        GROUP BY dacode
                    ) t2 ON t1.usercode = t2.dacode

                    LEFT JOIN (
                    SELECT COUNT(*) AS totup, usercode AS daheardt
                    FROM (
                        SELECT diary_no, usercode 
                        FROM heardt 
                        WHERE ent_dt::date = '$date'
                        
                        UNION
                        
                        SELECT diary_no, usercode 
                        FROM last_heardt 
                        WHERE ent_dt::date = '$date'
                        GROUP BY diary_no, usercode
                    ) t1
                    GROUP BY usercode
                    ) t3 ON t1.usercode = t3.daheardt

                    LEFT JOIN (
                        SELECT DISTINCT m.dacode AS dddcc, 
                            SUM(CASE WHEN t1.usercode = 1 THEN 1 ELSE 0 END) AS supuser
                        FROM (
                            SELECT diary_no, usercode 
                            FROM heardt 
                            WHERE ent_dt::date = '$date'
                            
                            UNION
                            
                            SELECT diary_no, usercode 
                            FROM last_heardt 
                            WHERE ent_dt::date = '$date'
                            GROUP BY diary_no, usercode
                        ) t1
                        LEFT JOIN main m ON t1.diary_no = m.diary_no
                        LEFT JOIN master.users \"user\" ON \"user\".usercode = m.dacode
                        WHERE \"user\".usertype IN (17, 50, 51) 
                        AND \"user\".display = 'Y'
                        GROUP BY m.dacode
                    ) t3a ON t1.usercode = t3a.dddcc

                    LEFT JOIN (
                        SELECT COUNT(*) AS totoff, rec_user_id 
                        FROM office_report_details 
                        WHERE rec_dt::date = '$date' 
                        AND display = 'Y'
                        GROUP BY rec_user_id
                    ) t4 ON t1.usercode = t4.rec_user_id

                    LEFT JOIN (
                        SELECT COUNT(*) AS totnot, user_id 
                        FROM tw_tal_del 
                        WHERE rec_dt::date = '$date' 
                        AND display = 'Y'
                        GROUP BY user_id
                    ) t5 ON t1.usercode = t5.user_id 

                    LEFT JOIN (
                        SELECT dacode AS rogy_da, 
                            COUNT(DISTINCT total) AS total_tt,
                            COUNT(DISTINCT red) AS red,
                            COUNT(DISTINCT orange) AS orange,
                            COUNT(DISTINCT green) AS green,
                            COUNT(DISTINCT yellow) AS yellow 
                        FROM (
                            SELECT empid, dacode, name, ut.type_name, us.section_name, m.diary_no AS total,
                                CASE WHEN (h.tentative_cl_dt - NOW()) < INTERVAL '2 days' THEN m.diary_no END AS red,
                                CASE WHEN (h.tentative_cl_dt - NOW()) >= INTERVAL '2 days' THEN m.diary_no END AS orange,
                                CASE WHEN h.mainhead = 'M' THEN m.diary_no END AS green,
                                CASE WHEN h.main_supp_flag = 3 THEN m.diary_no END AS yellow
                            FROM main m
                            INNER JOIN master.casetype c ON c.casecode = COALESCE(m.active_casetype_id, m.casetype_id)
                            LEFT JOIN heardt h ON m.diary_no = h.diary_no
                            LEFT JOIN master.users \"user\" ON m.dacode = \"user\".usercode
                            LEFT JOIN master.usersection as  us ON us.id = \"user\".section
                            LEFT JOIN master.usertype as  ut ON ut.id = \"user\".usertype
                            WHERE m.c_status = 'P'
                        ) a 
                        GROUP BY empid, dacode, name, type_name, section_name
                    ) t6 ON t1.usercode = t6.rogy_da

                    $ddl_all_blank_a
                    ORDER BY section_name, type_name DESC;
                ";



        $query = $this->db->query($sql);
        $data['result'] = $query->getResultArray();
        return view('Reports/get_workdone', $data);
    }


    public function diaryProgressReport()
    {
        $data['url'] = 'case_trap_report';
        return view('Reports/filing/report', $data);
    }

    public function defectiveMattersNotListed()
    {
        $data['url'] = 'defective_matters_not_listed';
        return view('Reports/filing/report', $data);
    }

    public function defectiveMattersDetails()
    {
        $ReportModel = new ReportModel();
        $days = $_REQUEST['days'];
        $i = 0;
        $sel_section = '';
        foreach ($_REQUEST['section'] as $selected) {
            $sel_section .= "'" . $selected . "',";

            $i = $i + 1;
        }

        $sel_section = rtrim($sel_section, ',');

        $result_array = $ReportModel->defectiveMattersNotListed($days, [$sel_section]);
        $data['days'] = $days;
        $data['sel_section'] = $sel_section;
        $data['defects_result'] = $result_array;
        return view('Reports/filing/defectiveMattersDetails', $data);
    }

    public function ibget_workdone()
    {
        $data['ReportModel'] = new ReportModel();
        $data['result'] =  $data['ReportModel']->ibget_workdone_get_data();

        return view('Reports/ibget_workdone_data', $data);
    }

    public function ibget_workdone_full()
    {
        // pr($_REQUEST);
        if ($_REQUEST['type'] == 'off') {

            $data['date'] = $_REQUEST['date'];
            $data['id'] = $_REQUEST['id'];

            $data['ReportModel'] = new ReportModel();
            $data['result'] =  $data['ReportModel']->ibget_workdone_full_get_data($data['date'],$data['id']);

            return view('Reports/ibget_workdone_full_data', $data);
        }
       
    }
    
}
