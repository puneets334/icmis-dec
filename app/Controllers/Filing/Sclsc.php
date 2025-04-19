<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use App\Models\Filing\SCLSC_model;

class SCLSC extends BaseController
{
    protected $session;
    protected $form_validation;
    protected $SCLSC_model;

    public function __construct()
    {
       // helper(['form', 'url', 'html', 'security']);
        $this->SCLSC_model = new SCLSC_model();
        //$this->form_validation = \Config\Services::validation();
        //$this->session = \Config\Services::session();
    }

    public function index($session = null)
    {
        // if ($session) {
        $data = [];
        // session()->set('dcmis_user_idd', $session);
        return view('Filing/SCLSC/index', $data);
        // } else {
        //     return "Error: Access Denied";
        // }
    }


    public function refiledDocumentsReport($session = null)
    {    
        return view('Filing/SCLSC/sclsc_refiled_matters');
        /*$usercode = session()->get('login')['usercode'];
        // Fetch POST data
        $post_data = $this->request->getJSON(true);
        $post_data = $post_data['data'] ?? [];
    
        if (isset($post_data['from_date']) && isset($post_data['to_date'])) {
            $fromDate = date('Y-m-d', strtotime($post_data['from_date']));
            $toDate = date('Y-m-d', strtotime($post_data['to_date']));
            $data['refilingList'] = $this->SCLSC_model->get_sclsc_refiling_documents($fromDate, $toDate, $usercode);
            
            echo json_encode($data);
            
        } else {
            return view('Filing/SCLSC/sclsc_refiled_matters');
        } */
    }

    public function refiledDocumentsReportLIst($session = null)
    {    
        $usercode = session()->get('login')['usercode'];
        // Fetch POST data
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');   
         
        $fromDate = date('Y-m-d', strtotime($from_date));
        $toDate = date('Y-m-d', strtotime($to_date));
        $data['refilingList'] = $this->SCLSC_model->get_sclsc_refiling_documents($fromDate, $toDate, $usercode);
        
        return view('Filing/SCLSC/refilingList',$data);
        die;
            
        
    }

    public function sclsc_get_documents()
    {
        // Retrieve JSON data from the request body
        $post_data = $this->request->getJSON(true);
    
        // Check if 'data' key exists
        if (!isset($post_data['data'])) {
            return $this->response->setJSON(['error' => 'Missing data field']);
        }
    
        // Extract 'data' from the decoded JSON
        $data = $post_data['data'];
    
        // Call the model's method to get data
        $result = $this->SCLSC_model->get_sclsc_documents($data);
    
        // Return JSON response
        return $this->response->setJSON($result);
    }


    public function diaryGeneratedByAPI($session = null)
    {
        // if ($session) {
        $data = [];
        session()->set('dcmis_user_idd', $session);
      
        if ($this->request->getMethod() === 'post') {
            $status = $this->request->getPost('status');
            $data['status'] = $status;
           
            // Assuming SCLSCModel is loaded or autoloaded
            $data['result'] = $this->SCLSC_model->diaryGeneratedByAPIreport($status);
            
        }

        return view('Filing/SCLSC/diary_generated_by_api', $data);
        // } else {
        //     return 'Error: Access Denied';
        // }
    }



    public function UnFiledCases()
    {
        $data['unfiled_cases'] = $this->SCLSC_model->getUnFiledCases();
        return view('Filing/SCLSC/unfiled_cases', $data);
    }

    public function UnFiledCaseDetails()
    {
        // Use the incoming() method to handle POST data
        $diary_no = $this->request->getPost('diary_no');

        // Initialize the data array
        $data = [];

        // Fetch details using models
        $data['diary_no'] = $diary_no;
        $data['unfiled_case_details'] = $this->SCLSC_model->getUnFiledCaseDetails($diary_no);
        $data['caseType'] = $this->SCLSC_model->getCaseType($data['unfiled_case_details'][0]['casetype_id']);

        $section_id = $this->SCLSC_model->getSection(
            $data['unfiled_case_details'][0]['from_court'],
            $data['unfiled_case_details'][0]['ref_agency_code_id'],
            $data['unfiled_case_details'][0]['casetype_id']
        );

        $data['section_name'] = $this->SCLSC_model->getSectionName($section_id);
        $party_sclsc = $this->SCLSC_model->getPartyDetails($diary_no);
        $data['aor_details'] = $this->SCLSC_model->getAORdetail($data['unfiled_case_details'][0]['aor_code']);

        $data['from_court_name'] = $this->SCLSC_model->getFromCourt($data['unfiled_case_details'][0]['from_court']);

        $data['agency_state_name'] = $this->SCLSC_model->getNameOfPlace($data['unfiled_case_details'][0]['ref_agency_state_id']);
        $data['agency_bench_name'] = $this->SCLSC_model->getBenchName(
            $data['unfiled_case_details'][0]['from_court'],
            $data['unfiled_case_details'][0]['ref_agency_code_id']
        );

        // Add columns to the party_sclsc array
        foreach ($party_sclsc as &$row) {
            $row['stateName'] = $this->SCLSC_model->getNameOfPlace($row['state']);
            $row['districtName'] = $this->SCLSC_model->getNameOfPlace($row['city']);
        }

        $data['party'] = $party_sclsc;

        // Render the view
        return view('Filing/SCLSC/unfiled_case_details', $data);
    }


    public function generateDiary()
    {
        $details = $this->SCLSC_model->getUnFiledCaseDetails($_POST['diary_no']);
        $sclsc_party = $this->SCLSC_model->getSCLSCparty($_POST['diary_no']);
        $aor_details = $this->SCLSC_model->getAORdetail($details[0]['aor_code']);


        $casecode = $this->SCLSC_model->getCaseType($details[0]['casetype_id']);
        $nature = $this->SCLSC_model->getCaseNature($details[0]['casetype_id']);
        $section_id = $this->SCLSC_model->getSection($details[0]['from_court'], $details[0]['ref_agency_code_id'], $details[0]['casetype_id']);

        $result = array();
        $fil_no = $this->SCLSC_model->getMaxDiaryNo();
        $fil_no++;
        //Step 1 get max diary number
        $year = date('Y');
        $diary_no = $fil_no . $year;

        /*        $main_data['diary_no'] = $details[0]['diary_no'];
        $main_data['pet_name'] = $details[0]['pet_name'];
        $main_data['res_name'] = $details[0]['res_name'];
        $main_data['pet_adv_id'] = 0;
        $main_data['diary_no_rec_date'] = date('Y-m-d H:i:s');
        $main_data['diary_user_id'] = $icmis_user_code;
        $main_data['from_court'] = 0;
        $main_data['res_name'] = $details[0]['res_name'];
        $main_data['res_name'] = $details[0]['res_name']; */

        //TODO::GET FROM API
        $pet_adv_id = 1;
        $icmis_user_code = $_SESSION['login']['usercode'];
        $court_type = 1;
        $ref_agency_state_id = 1;
        $ref_agency_code_id = 1;
        //$nature = 'C';
        //$casecode = 31;
        $pno = 1;
        $caseSectionId = 1;
        $dacode = 1;
        $total_pages = 3;
    

        // $main_data = array(
        //     'pet_name' => $details[0]['pet_name'],
        //     'res_name' => $details[0]['res_name'],
        //     'pet_adv_id' => $pet_adv_id,
        //     'diary_no' => $diary_no,
        //     'diary_no_rec_date' => date('Y-m-d H:i:s'),
        //     'diary_user_id' => $icmis_user_code,
        //     'from_court' => $details[0]['from_court'],
        //     'ref_agency_state_id' => $details[0]['ref_agency_state_id'],
        //     'ref_agency_code_id' => $details[0]['ref_agency_code_id'],
        //     'c_status' => 'P',
        //     'case_grp' => $nature,
        //     'casetype_id' => $casecode,
        //     'nature' => $nature,
        //     'pno' => $pno,
        //     'rno' => $pno,
        //     'if_sclsc' => 1,
        //     'section_id' => $section_id,
        //     'case_pages' => $total_pages
        // );
        $main_data = array(
            'pet_name' => $details[0]['pet_name'],
            'res_name' => $details[0]['res_name'],
            'pet_adv_id' => $pet_adv_id,
            'diary_no' => is_numeric($diary_no) ? (int)$diary_no : 0,
            'diary_no_rec_date' => date('Y-m-d H:i:s'),
            'diary_user_id' => $icmis_user_code,
            'from_court' => $details[0]['from_court'],
            'ref_agency_state_id' => is_numeric($details[0]['ref_agency_state_id']) ? (int)$details[0]['ref_agency_state_id'] : 0,
            'ref_agency_code_id' => is_numeric($details[0]['ref_agency_code_id']) ? (int)$details[0]['ref_agency_code_id'] : 0,
            'c_status' => 'P',
            'case_grp' => $nature,
            'casetype_id' => (int)$casecode,
            'nature' => $nature,
            'pno' => $pno,
            'rno' => $pno,
            'if_sclsc' => 1,
            'section_id' => $section_id,
            'case_pages' => $total_pages,
        );
        $rows_inserted = $this->SCLSC_model->insertInDB('main', $main_data);
        $result['records_inserted']['main'] = $rows_inserted;
        if ($rows_inserted < 1) {
            $result["status"] = "ERROR_MAIN";
            $result["error"] = "There is some problem while generating diary of this case.";
            echo json_encode($result);
            exit();
        }
        $this->SCLSC_model->updateDiaryCounter($fil_no);

        $this->SCLSC_model->updateSCLSCDiaryFiled($_POST['diary_no']);

        $sclsc_details['diary_no'] = $diary_no;
        $sclsc_details['sclsc_diary_no'] = substr($_POST['diary_no'], 0, -4);
        $sclsc_details['sclsc_diary_year'] = substr($_POST['diary_no'], -4);
        $sclsc_details['display'] = 'Y';
        $sclsc_details['sclsc_ent_dt'] = date('Y-m-d H:i:s');
        $this->SCLSC_model->insertInDB('sclsc_details', $sclsc_details);



        foreach ($sclsc_party as $party) {
            //TODO::main petitioner and multiple party details to handle
            $party_data = array(
                'diary_no' => $diary_no,
                'pet_res' => $party['pet_res'],
                'sr_no' => $party['sr_no'],
                'sr_no_show' => $party['sr_no_show'],
                'ind_dep' => $party['ind_dep'],
                'partysuff' => $party['partysuff'],
                'partyname' => $party['partyname'],
                'prfhname' => $party['prfhname'],
                'addr2' => $party['addr2'],
                'state' => $party['state'],
                'city' => $party['city'],
                'pin' => $party['pin'],
                'email' => $party['email'],
                'contact' => $party['contact'],
                'pflag' => $party['pflag']
            );
            $this->SCLSC_model->insertInDB('party', $party_data);
        }

        if ($aor_details) {
            $advocate['diary_no'] = $diary_no;
            $advocate['adv_type'] = 'M';
            $advocate['pet_res'] = 'P';
            $advocate['pet_res_no'] = '1';
            $advocate['advocate_id'] = $aor_details[0]['bar_id'];
            $advocate['usercode'] = $icmis_user_code;
            $advocate['ent_dt'] = date('Y-m-d H:i:s');
            $advocate['display'] = 'Y';
            $advocate['pet_res_show_no'] = 1;
            $this->SCLSC_model->insertInDB('advocate', $advocate);
        }

        //diary_copy_set started
        $copyset = array('A', 'B', 'C', 'D');
        foreach ($copyset as $set) {
            $copy_set_data[] = array('diary_no' => $diary_no, 'copy_set' => $set);
        }
        $rows_inserted = $this->SCLSC_model->batchInsertInDB('diary_copy_set', $copy_set_data);

        //Allocate to entry counter user
        $result['alloted_to'] = $this->SCLSC_model->allot_to_EC($diary_no, $icmis_user_code);

        $result['diary_no'] = $diary_no;
        $result["status"] = "SUCCESS";
        echo json_encode($result);

        /*  $table_allocation = "main";
        $finally_case_listed = $this->Causelist_model->insertData($table_allocation,$main_data);*/

        //  var_dump($details);
    }

    public function documents_index($session)
    {
        if ($session) {
            $data = array();
            $this->session->set_userdata('dcmis_user_idd', $session);
            $this->load->view('SCLSC/documents_index', $data);
        } else {
            echo "Error:Access Denied";
        }
    }
    public function UnFiledDocuments()
    {
        $data['unfiled_documents'] = $this->SCLSC_model->getUnFiledDocuments();
        $this->load->view('SCLSC/unfiled_documents', $data);
    }
    public function UnFiledDocumentsDetails()
    {
        $data['docd_id'] = $_POST['docd_id'];
        $data['diary_no'] = $_POST['diary_no'];

        $data['unfiled_documents_details'] = $this->SCLSC_model->getUnFiledDocumentsDetails($_POST['docd_id']);

        $data['case_listed_count'] = $this->SCLSC_model->getCaseListedCount($_POST['diary_no']);



        $data['section_name'] = $this->SCLSC_model->getSectionName($data['unfiled_documents_details'][0]['section_id']);

        $data['subject_category'] = $this->SCLSC_model->getSubjectCategory($_POST['diary_no']);

        $data['listing_in_future_date'] = $this->SCLSC_model->getListingInFutureDates($_POST['diary_no']);
        $data['stage_name'] = $this->SCLSC_model->getCaseStageName($_POST['diary_no']);

        $data['agency_state_name'] = $this->SCLSC_model->getAgencyState($data['unfiled_documents_details'][0]['ref_agency_state_id']);

        $data['party'] = $this->SCLSC_model->getParty($_POST['diary_no']);

        $data['advocate'] = $this->SCLSC_model->getAdvocate($_POST['diary_no']);

        $data['document_filed_advocate'] = $this->SCLSC_model->getAORdetail($data['unfiled_documents_details'][0]['advocate_id']);

        $data['filed_documents'] = $this->SCLSC_model->getFiledDocuments($data['unfiled_documents_details'][0]['doccode'], $data['unfiled_documents_details'][0]['doccode1']);

        $this->load->view('SCLSC/unfiled_documents_details', $data);
    }



    public function generateDocuments()
    {
        $year = date('Y');
        $details = $this->SCLSC_model->getUnFiledDocumentsDetails($_POST['doc_id']);

        $aor_details = $this->SCLSC_model->getAORdetail($details[0]['advocate_id']);

        $result = array();

        $maxDockcount = $this->SCLSC_model->getMaxDockount();
        if ($maxDockcount > 0) {
            $maxDockcount = $maxDockcount + 1;
        } else {
            $dockount_data = array(
                'year' => $year,
                'knt' => 0
            );
            $is_inserted = $this->SCLSC_model->insertInDB('dockount', $dockount_data);
            if ($is_inserted) {
                $maxDockcount = 1;
            } else {
                echo "Unable to insert in dockount table ";
                exit;
            }
        }

        $icmis_user_code = $_SESSION['dcmis_user_idd'];
        $diary_no = $details[0]['diary_no'];
        $docdetail_data = array(
            'diary_no' => $details[0]['diary_no'],
            'doccode' => $details[0]['doccode'],
            'doccode1' => $details[0]['doccode1'],
            'docnum' => $maxDockcount,
            'docyear' => $year,
            'usercode' => $icmis_user_code,
            'ent_dt' => date('Y-m-d H:i:s'),
            'filedby' => trim($aor_details[0]['name']),
            'advocate_id' => $aor_details[0]['bar_id']
        );
        $rows_inserted = $this->SCLSC_model->insertInDB('docdetails', $docdetail_data);

        //$result['records_inserted']['main']=$rows_inserted;
        if ($rows_inserted < 1) {
            $result["status"] = "ERROR_MAIN";
            $result["error"] = "There is some problem while generating document no.";
            echo json_encode($result);
            exit();
        }
        $this->SCLSC_model->updateDocCounter($maxDockcount);

        $this->SCLSC_model->updateSCLSCDocdetails($_POST['doc_id']);


        if ($details[0]['dacode'] > 0) {
            $this->SCLSC_model->dispatch_ld($details[0]['dacode'], $details[0]['diary_no'], $details[0]['doccode'], $details[0]['doccode1'], $maxDockcount, $year, $icmis_user_code);
        }

        if ($details[0]['doccode'] == 8) {
            $call_listing_response = file_get_contents('http://xxxx/supreme_court/loosedoc/call_listing2.php?dno=' . $diary_no . '&ignore_session_key=xyz');
        }

        $result['document_number'] = $maxDockcount . '/' . $year;
        $result["status"] = "SUCCESS";
        echo json_encode($result);
    }

    public function direct_allot_to_EC($diary_no, $icmis_user_code)
    {
        $result = $this->SCLSC_model->allot_to_EC($diary_no, $icmis_user_code);
        var_dupm($result);
    }
}
