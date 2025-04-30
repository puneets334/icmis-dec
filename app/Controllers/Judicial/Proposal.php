<?php

namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\ProposalModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Proposal extends BaseController
{
    public $Dropdown_list_model;
    public $ProposalModel;

    function __construct()
    {
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->ProposalModel = new ProposalModel();

        // $this->session = session();
        // $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    public function index()
    {

        $request = \Config\Services::request();

        if ($request->getMethod() === 'post' && $this->validate([
            'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
            'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        ])) {
            $search_type = $request->getPost('search_type');
            if ($search_type == 'D') {
                $diary_number = $request->getPost('diary_number');
                $diary_year = $request->getPost('diary_year');
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);

                $get_main_table['input_query']['diary_number'] = $diary_number;
                $get_main_table['input_query']['diary_year'] = $diary_year;

            } elseif ($search_type == 'C') {
                $case_number = $request->getPost('case_number');
                $case_year = $request->getPost('case_year');
                session()->setFlashdata("message_error", 'Data not Fount');
            }

            if ($get_main_table) {
                
                $this->session->set(array('filing_details' => $get_main_table));

                return redirect()->to('Judicial/Proposal/redirect_on_diary_user_type');

            } else {
                session()->setFlashdata("message_error", 'Data not Fount');
            }
        }

        $data['casetype'] = get_from_table_json('casetype');
        $data['formAction'] = 'Judicial/Proposal/ListProposal';

        return view('Judicial/diary_search', $data);
    }

    function redirect_on_diary_user_type()
    {
        if (session()->get('login')) {
            return redirect()->to('Judicial/Proposal/ListProposal');
        } else {
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }

        return redirect()->to('Judicial/Proposal/ListProposal');
    }

    public function getReport($data=[])
    {
        $data = [];

        return $data;
    }

    public function RemovalofDefault()
    {
        return view('Judicial/ProposalObjection_view');
    }

    public function ListObjection()
    {
        $request = \Config\Services::request();

        $search_type = $request->getGetPost('search_type');
        if ($search_type == 'D' && $this->validate([
            'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        ])) {
            $diary_number = $request->getGetPost('diary_number');
            $diary_year = $request->getGetPost('diary_year');
            $diary_no = $diary_number . $diary_year;
            $filing_details = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            
            $input_query['diary_number'] = $diary_number;
            $input_query['diary_year'] = $diary_year;

        } elseif ($search_type == 'C' && $this->validate([
            'case_type' => ['label' => 'Case Type', 'rules' => 'required|min_length[1]|max_length[2]'],
            'case_number' => ['label' => 'Case Number', 'rules' => 'required|min_length[1]|max_length[8]'],
            'case_year' => ['label' => 'Case Year', 'rules' => 'required|min_length[4]'],
        ])) {
            $case_type = $request->getGetPost('case_type');
            $case_number = $request->getGetPost('case_number');
            $case_year = $request->getGetPost('case_year');
            
            $filing_details = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

            if($filing_details === false) {
                return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
            }
                            
            $diary_info = get_diary_numyear($filing_details['diary_no']);

            $diary_number = $diary_info[0];
            $diary_year = $diary_info[1];
        }

        // pr($filing_details);
        
        if(!empty($filing_details)) 
        {
            $diaryNo = $filing_details['diary_no'];

            $results = $this->ProposalModel->getRemovalofDefaultList($diaryNo);
            
            $data['results'] = $results;

            return view('Judicial/ProposalObjection_list', $data);
        } else {
            return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
        }
    }

    public function insert_rec_prop()
    {
        $request = \Config\Services::request();

        $data = [];
        $data['ucode'] = session()->get('login')['usercode'];
        $data['diaryno'] = $request->getPost('diaryno');
        $data['jrc'] = $request->getPost('jrc');
        $data['thdt_new'] = $request->getPost('thdt_new');
        $data['mf'] = $request->getPost('mf');
        $data['sh'] = $request->getPost('sh');
        $data['supp_flag'] = $request->getPost('supp_flag');
        $data['ias'] = $request->getPost('ias');
        $data['sj'] = $request->getPost('sj');
        $data['module_id'] = 5;
        $data['lo'] = $request->getPost('lo');
        $data['br'] = addslashes(trim($request->getPost('br')));
        $data['ccstr'] = $request->getPost('ccstr');
        $data['tcntr'] = $request->getPost('tcntr');
        $data['r_nr'] = $request->getPost('r_nr');
        $data['connlist'] = $request->getPost('connlist');
        $data['t_rnr'] = 0;
        if ($data['r_nr'] == 'R')
            $data['t_rnr'] = 0;
        if ($data['r_nr'] == 'NR')
            $data['t_rnr'] = 3;
        if ($data['ccstr'] != "")
            $data['conncs'] = "Y";
        else
            $data['conncs'] = "N";

        $err = "";
        $od_pd = '';
        $conn_key = 0;

        $main_details = $this->ProposalModel->get_main_details($data['diaryno'], 'diary_no,conn_key');

        if (is_array($main_details)) {
            foreach ($main_details as $rowm => $linkm) {
                $conn_key = (!empty($linkm['conn_key'])) ? $linkm['conn_key'] : 0;
            }
        }
        
        $data['conn_key'] = $conn_key;

        if ($data['thdt_new'] == '') {
            echo "Please select some date!";
            exit(0);
        }
        
        return $this->ProposalModel->insert_rec_prop($data); 
    }

    public function get_tentative_date()
    {
        $request = \Config\Services::request();

        $diary_no = $request->getPost('diaryno');
        $mainhead_after_change = $request->getPost('mainhead');
        $board_type_after_change = $request->getPost('board_type');
        $next_dt_after_change = $request->getPost('next_dt');
        $listorder_after_change = $request->getPost('listorder');        
        $prev_board= $request->getPost('prev_board_type');
        $lastListedOn= $request->getPost('lastListedOn');
        
        return $this->ProposalModel->get_tentative_date_after_change($diary_no, $mainhead_after_change,$board_type_after_change,$next_dt_after_change,$listorder_after_change,$prev_board,$lastListedOn);
    }

    public function check_proposal() 
    {
        $request = \Config\Services::request();
        
        $diaryno = $request->getPost('diaryno');
        
        return $this->ProposalModel->check_proposal($diaryno);
    }

    public function get_mf_subhead()
    {
        $request = \Config\Services::request();

        $ucode = session()->get('login')['usercode'];
        $mf = $request->getPost('mf');
        $sh = $request->getPost('sh');
        $jrc = $request->getPost('jrc');
        
        return $this->ProposalModel->get_mf_subhead($ucode, $mf, $sh, $jrc);
    }

    public function ListProposal()
    {
        $request = \Config\Services::request();

        $data = [];

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
                $filing_details = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                
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
                
                $filing_details = $this->Dropdown_list_model->get_case_details_by_case_no($case_type, $case_number, $case_year);

                if($filing_details === false) {
                    return $this->response->setJSON(['success' => 0, 'error' => 'Case not Found']);
                }
                                
                $diary_info = get_diary_numyear($filing_details['diary_no']);

                $diary_number = $diary_info[0];
                $diary_year = $diary_info[1];

                $input_query['diary_number'] = $diary_number;
                $input_query['diary_year'] = $diary_year;

            }

            if(empty($filing_details)) 
            {
                session()->setFlashdata("error", 'Data not Fount');

                $data['success'] = 0;
                $data['redirect'] = base_url('Judicial/Proposal/proposalAdd');

                return $this->response->setJSON($data);
            }

            $filing_details['input_query'] = $input_query;
            $data['diary_no'] = $diary_number.$diary_year;
            $data['diary_number'] = $filing_details['input_query']['diary_number'];
            $data['diary_year'] = $filing_details['input_query']['diary_year'];

            $data['pet_name'] = $filing_details['pet_name'];
            $data['res_name'] = $filing_details['res_name'];

            $data['holiday_dates'] = $this->ProposalModel->getSCHolidays();
            $data['rowLastProposed'] = $this->ProposalModel->getLastProposed($filing_details['diary_no']);
            $data['row_list'] = $this->ProposalModel->getTotalHearings($filing_details['diary_no']);
            $data['sql_lp1'] = $this->ProposalModel->getListingPurpose($filing_details['diary_no']);

            $case_no = '';
            $case_no = $this->ProposalModel->getCaseNo($filing_details);            
            $data['case_no'] = $case_no;

            $mul_category = '';
            $mul_category = $this->ProposalModel->getCaseCategories($filing_details);            
            $data['mul_category'] = $mul_category['mul_category'];
            $data['category_id'] = $mul_category['category_id'];

            $act_section = '';
            $act_section = $this->ProposalModel->getActInfo($filing_details);            
            $data['act_section'] = $act_section;

            $provision_of_law = '';
            $provision_of_law = $this->ProposalModel->getProvisionofLaw($filing_details);            
            $data['provision_of_law'] = $provision_of_law;

            $da_name = $this->ProposalModel->getDaName($filing_details);
            $data['da_name'] = $da_name['da_name'];

            $data['row_da'] = $da_name['row_da'];

            $matter_type = $da_name['row_da']['mf_active'];
            $data['matter_type'] = '';
            if($matter_type == 'M') {
                $data['matter_type'] = 'Miscellaneous';
            } elseif($matter_type == 'F') {
                $data['matter_type'] = 'Regular';
            }
            
            $ac_court = $this->ProposalModel->getAmicusCurie($filing_details);
            
            $data['ac_court'] = $ac_court['ac_court'];
            $data['padvname'] = $ac_court['padvname'];
            $data['radvname'] = $ac_court['radvname'];
            
            $data['t_rgo'] = $this->ProposalModel->getConditionalDispose($filing_details);
            $data['tentative_date'] = $this->ProposalModel->getTentativeDates($filing_details);
            
            $data['provision_of_law'] = $this->ProposalModel->getProvisionofLaw($filing_details) ?? 'No Law Found';
            
            $perposal_listing = $this->ProposalModel->getProposalList($filing_details);
            $data['perposal_listing'] = $perposal_listing['html'];
            
            // Assign listed_ia var from perposal listing
            $filing_details['listed_ia'] = $perposal_listing['listed_ia'];
            $filing_details['remarks'] = $perposal_listing['remarks'];
            $filing_details['last_cl_date'] = $perposal_listing['last_cl_date'];
            $filing_details['pendingIAs'] = $perposal_listing['pendingIAs'];
            $filing_details['row_sensitive'] = $perposal_listing['row_sensitive'];
            $filing_details['row_PIP'] = $perposal_listing['row_PIP'];
            $filing_details['only_can_update_469'] = $perposal_listing['only_can_update_469'];
            $filing_details['check_for_case_is_listed_after_current_date'] = $perposal_listing['check_for_case_is_listed_after_current_date'];
            $filing_details['check_for_case_is_listed_after_current_date_remark'] = $perposal_listing['check_for_case_is_listed_after_current_date_remark'];

            $ian_listing =  $this->ProposalModel->getInterlocutaryApplications($filing_details);
            $data['ian_listing'] = $ian_listing['html'];
            $data['ian_p'] = $ian_listing['ian_p'];
            $data['brdremh'] = $this->ProposalModel->get_brd_remarks($filing_details['diary_no']);
            
            $data['remarks'] = $perposal_listing['remarks'];
            $data['pendingIAs'] = $perposal_listing['pendingIAs'];
            $data['last_cl_date'] = $perposal_listing['last_cl_date'];
            $data['mainhead_kk'] = $perposal_listing['mainhead_kk'];
            $data['listed_ia'] = $perposal_listing['listed_ia'];
            $data['subhead'] = $perposal_listing['subhead'];
            
            $proposal_form = $perposal_listing['proposal_form'];
            $data['proposal_form'] = $proposal_form;

            // pr($proposal_form);
            
            $data['future_dates'] = $this->ProposalModel->getFutureDates();
            
            $data['q_next_dt'] = "";
            $data['nextmonday'] = "";
            $data['nexttuesday'] = "";

            if(!empty($proposal_form['next_dt'])) {
                $data['q_next_dt'] = date("Y-m-d", strtotime($proposal_form['next_dt']));
                $data['nextmonday'] = $this->ProposalModel->getNextMonday($proposal_form['next_dt']);
                $data['nexttuesday'] = $this->ProposalModel->getNextTuesday($proposal_form['next_dt']);
            }

            $data['t11'] = $this->ProposalModel->getMainHead($filing_details['diary_no']);

            $data['check_for_conn'] = ($filing_details['diary_no'] != $filing_details['conn_key'] && $filing_details['conn_key'] != '' && $filing_details['conn_key'] != '0') ? "N" : "Y";
            $data['main_fh_fil_no'] = ($filing_details['fil_no_fh'] != '') ? "EXIST" : "";

            $data['result_array'] = $perposal_listing['result_array'];
            $data['user_case_updation'] = $perposal_listing['user_case_updation'];
            $data['ucode'] = session()->get('login')['usercode'];

            // pr($data['proposal_form']);

            $data['doc_listing'] = $this->ProposalModel->getOtherDocuments($filing_details);

            $doc_updation = [];
            $doc_updation = $this->ProposalModel->checkUpdation($filing_details);
            $data['rmtable'] = $doc_updation['rmtable'];
            $data['allowed'] = $doc_updation['allowed'];
            $data['noticeissued'] = $doc_updation['noticeissued'];
            
            $linked_cases =  $this->ProposalModel->getConnectedLinkedCases($filing_details);
            $data['linked_case_listing'] = $linked_cases['html'];
            $data['connchks'] = $linked_cases['connchks'];
            $data['conncases'] = $linked_cases['conncases'];
            
            // $data['reslt_validate_caseInAdvanceList'] = $linked_cases['conncases'];
            // $data['result_caseInFinalList'] = $linked_cases['conncases'];
            // $data['reslt_validate_caseInAdvanceListSingleJudge'] = $linked_cases['conncases'];
            // $data['result_caseInFinalListSingleJudge'] = $linked_cases['conncases'];

            $data['reslt_validate_caseInAdvanceList']  = $this->ProposalModel->ifInAdvanceList($filing_details['diary_no']);
            $data['result_caseInFinalList']  = $this->ProposalModel->ifInFinalList($filing_details['diary_no']);
            $data['reslt_validate_caseInAdvanceListSingleJudge'] = $this->ProposalModel->ifInAdvanceListSingleJudge($filing_details['diary_no']);
            $data['result_caseInFinalListSingleJudge'] = $this->ProposalModel->ifInFinalListSingleJudge($filing_details['diary_no']);

            $data['fil_no'] = $filing_details;
            $data['fil_no']['lastorder'] = $filing_details['lastorder'];
            $data['fil_no']['c_status'] = $filing_details['c_status'];
            
            $data['success'] = 1;
            $data['html'] = view('Judicial/Proposal/Proposal_list', $data, ['saveData' => true]);
        } else {
            $data['success'] = 0;
            $data['error'] = "Record could not be fetched.";
        }

        return $this->response->setJSON($data);
    }

    public function get_result_1() 
    {
        $request = \Config\Services::request();

        $count = $this->ProposalModel->get_result_1($request->getGet('idn_id'), $request->getGet('id_id'));

        echo '<input type="hidden" name="hd_id_id" id="hd_id_id" value="' . $count . '"/>';
    }

    public function proposalAdd()
    {
        $request = \Config\Services::request();

        $data = [];
        $data['report'] = '';
        
        // if ($request->getMethod() === 'post' && $this->validate([
        //     'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
        //     'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
        //     'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
        // ])) {
            
        // }

        $data['casetype'] = get_from_table_json('casetype');
        $data['sectionHeading'] = 'Judicial / Proposal >> Add / Update';
        $data['formAction'] = 'Judicial/Proposal/index';

        return view('Judicial/Proposal/index', $data);
    }
}
