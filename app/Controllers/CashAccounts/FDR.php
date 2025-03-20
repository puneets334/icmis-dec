<?php

namespace App\Controllers\CashAccounts;

use App\Controllers\BaseController;
use App\Models\CashAccounts\Fdr_Model;


class FDR extends BaseController
{
    public $fdr_model;
    protected $db;
    function __construct()
    {
        $this->fdr_model = new Fdr_Model();
        $this->db = db_connect();
    }

    public function index()
    {
        $data['caseTypes'] = $this->fdr_model->get_caseType();

        return view('FDR/caseSearch', $data);
    }

    /*
    Created for:- Used to bind bank,fd status in order to add in fd in part
    */
    public function continueFdr($ecCaseId = null)
    {
        error_reporting(0);

        // pr($_POST);
        $data['caseInfo'] = $this->fdr_model->get_caseInfo($this->request->getPost('caseType'), $this->request->getPost('caseNo'), $this->request->getPost('caseYear'), $ecCaseId);

       //  pr($data['caseInfo'] );

        if (isset($data['caseInfo']) && is_array($data['caseInfo']) && count($data['caseInfo']) === 1) {
            $data['banks'] = $this->fdr_model->get_Banks();
            $data['status'] = $this->fdr_model->get_fdStatus();
            $data['result'] = $this->fdr_model->get_fdrRecords($data['caseInfo'][0]['id']);
            return view('FDR/fdr_main', $data);
        } else {
            $data['caseTypes'] = $this->fdr_model->get_caseType();
            $data['banks'] = $this->fdr_model->get_Banks();
            $data['status'] = $this->fdr_model->get_fdStatus();
            $data['result'] = array();
            // return view('FDR/caseSearch', $data);
            return view('FDR/fdr_main', $data);

            //$this->resolveContinueFdrAmbiguity($data['caseInfo']); 

            //$this->continueFdr(989874);
        }
    }

    public function create_fdr()
    {

        // pr($this->request->getPost());
        // pr('fdsklfjks');

        $data = json_decode(file_get_contents("php://input"));
   

        $insertData = array(
            'type'                  => $data->type,
            'document_number'       => $data->fdrNo,
            'ec_case_id'            => $data->ec_case_id,
            'petitioner_name'       => $data->petitioner_name,
           'respondent_name'       => $data->respondent_name,
            'account_number'        => $data->acNo,
            'amount'                => $data->amount,
            'ref_section_code'      => $data->section_id,
            'ref_bank_id'           => $data->bank,
            'ref_status_id'         => $data->payStatus,
            'deposit_date'          => date('Y-m-d', strtotime($data->depositDate)),
            'maturity_date'         => date('Y-m-d', strtotime($data->maturityDate)),
            'order_date'            => date('Y-m-d', strtotime($data->orderDate)),
            'mode_code'             => $data->mode,
            'mode_document_number'  => $data->modeNo,
            'remarks'               => $data->remarks,
           // 'case_number_display'   => $data->caseNoDisplay,
           //  'updated_by_id'         => $this->session->login['username'],
            // 'updated_by_name'       => $this->session->login['name'],
            'ip_address'            => $_SERVER['REMOTE_ADDR'],
            'is_deleted'            => 0,
            'roi'                   => $data->roi,
            'days'                  => $data->days,
            'month'                 => $data->month,
            'year'                  => $data->year
        );
        
        // Insert data into the database
        $builder = $this->db->table('fdr_records');
        $inserted = $builder->insert($insertData);

        // Check if insertion was successful
        if ($inserted) {
            // Send success response
            echo json_encode(['status' => 'success', 'message' => 'FDR created successfully.']);
        } else {
            // Send error response
            echo json_encode(['status' => 'error', 'message' => 'Failed to create FDR.']);
        }
    }

    public function readOneRecord()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;

        $fdr_arr = $this->fdr_model->get_OneRecord($id);
        $fdr_arr[0]['deposit_date'] = date('d-m-Y', strtotime($fdr_arr[0]['deposit_date']));
        $fdr_arr[0]['maturity_date'] = date('d-m-Y', strtotime($fdr_arr[0]['maturity_date']));
        $fdr_arr[0]['order_date'] = date('d-m-Y', strtotime($fdr_arr[0]['order_date']));

        // make it json format
        print_r(json_encode($fdr_arr));
    }

    public function deleteFdr()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $updateData = array(
            'updated_by_id'         => $this->session->login['username'],
            'updated_by_name'       => $this->session->login['name'],
            'updated_datetime'      => date('Y-m-d h:i:s'),
            'ip_address'            => $_SERVER['REMOTE_ADDR'],
            'is_deleted'            => 1
        );
        $this->fdr_model->form_update($updateData, $id);
    }

    public function updateFdr()
    {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;

        $updateData = array(
            'type'                  => $data->type,
            'document_number'       => $data->fdrNo,
            'account_number'        => $data->acNo,
            'amount'                => $data->amount,
            'ref_bank_id'           => $data->bank,
            'ref_status_id'         => $data->payStatus,
            'deposit_date'          => date('Y-m-d', strtotime($data->depositDate)),
            'maturity_date'         => date('Y-m-d', strtotime($data->maturityDate)),
            'order_date'            => date('Y-m-d', strtotime($data->orderDate)),
            'mode_code'             => $data->mode,
            'mode_document_number'  => $data->modeNo,
            'roi'                   => $data->roi,
            'days'                  => $data->days,
            'month'                 => $data->month,
            'year'                  => $data->year,
            'remarks'               => $data->remarks,
            'updated_by_id'         => $this->session->login['username'],
            'updated_by_name'       => $this->session->login['name'],
            'updated_datetime'      => date('Y-m-d h:i:s'),
            'ip_address'            => $_SERVER['REMOTE_ADDR']
        );
        $this->fdr_model->form_update($updateData, $id);
    }

    public function fdr_search()
    {
        $data['sections'] = $this->fdr_model->get_section();
        $data['banks'] = $this->fdr_model->get_Banks();
        $data['caseTypes'] = $this->fdr_model->get_caseType();

        $data['DiscaseTypes'] = $this->fdr_model->get_Disposedcases();

        return view('FDR/search', $data);
    }

    public function fdr_search_result()
    {
        error_reporting(0);
        // For bank list bind
        $data['banks'] = $this->fdr_model->get_Banks();

        $request = \Config\Services::request();

        if (
            $request->getPost('type') != 0 ||
            $request->getPost('section') != 0 ||
            $request->getPost('bank') != 0 ||
            !empty($request->getPost('depositDate')) ||
            !empty($request->getPost('maturityDate')) ||
            $request->getPost('disposedCase') != 0 ||
            ($request->getPost('caseType') != 0 &&
                $request->getPost('caseNo') != 0 &&
                $request->getPost('caseYear') != 0) ||
            $request->getPost('days') != 0 ||
            $request->getPost('month') != 0 ||
            $request->getPost('year') != 0
        ) {
            $condition_array = [];
            $spl_condition = "1=1";

            if ($request->getPost('section') != 0) {
                $condition_array['ref_section_code'] = $request->getPost('section');
            }
            if ($request->getPost('type') != 0) {
                $condition_array['type'] = $request->getPost('type');
            }
            if ($request->getPost('bank') != 0) {
                $condition_array['ref_bank_id'] = $request->getPost('bank');
            }

            if (!empty($request->getPost('depositDate'))) {
                $dates = explode(" - ", $request->getPost('depositDate'));
                $spl_condition .= " AND (deposit_date BETWEEN '" . date('Y-m-d', strtotime($dates[0])) . "' AND '" . date('Y-m-d', strtotime($dates[1])) . "')";
            }

            if (!empty($request->getPost('maturityDate'))) {
                $dates = explode(" - ", $request->getPost('maturityDate'));
                $spl_condition .= " AND (maturity_date BETWEEN '" . date('Y-m-d', strtotime($dates[0])) . "' AND '" . date('Y-m-d', strtotime($dates[1])) . "')";
            }

            if ($request->getPost('disposedCase') != 0) {
                $data['searchResult'] = $this->fdr_model->disposedReport($request->getPost('disposedCase'));
            } elseif (
                $request->getPost('caseType') != 0 &&
                $request->getPost('caseNo') != 0 &&
                $request->getPost('caseYear') != 0
            ) {
                $caseNo = $request->getPost('caseNo');
                $caseType = $request->getPost('caseType');
                $caseYear = $request->getPost('caseYear');

                $data['searchResult'] = $this->fdr_model->caseTypeReport($caseNo, $caseType, $caseYear);
                // pr($data['searchResult']);
            } elseif (
                $request->getPost('days') != 0 ||
                $request->getPost('month') != 0 ||
                $request->getPost('year') != 0
            ) {
                $days = $request->getPost('days');
                $month = $request->getPost('month');
                $year = $request->getPost('year');

                $data['searchResult'] = $this->fdr_model->tenureWiseReport($days, $month, $year);
            } else {
                $data['searchResult'] = $this->fdr_model->searchResult($condition_array, $spl_condition);
            }

            return view('FDR/search_result', $data);
        } else {
            return "Please select some input.";
        }
    }

    /************************ Bank Master ************************/
    public function bankList()
    {
        $banks = $this->fdr_model->bankMaster();
        echo json_encode($banks);
    }

    public function bankMaster($session)
    {
        $mySession = array('id' => $session, 'username' => $session, 'name' => $session);
        $this->session->set_userdata('login', $mySession);
        if (isset($this->session->login)) {
            $this->load->view('FDR/bank_master');
        } else show_404();
    }

    public function addBank()
    {
        $data = json_decode(file_get_contents("php://input"));
        $insertData = array(
            'bank_name'        => $data->bank_name,
            'updated_by'       => $this->session->login['name'],
            'updated_datetime' => date('Y-m-d h:i:s'),
            'Contact_Person'        => $data->Contact_Person,
            'Email_ID'        => $data->Email_ID,
            'Ph_No'        => $data->Ph_No
        );
        $this->fdr_model->form_insert($insertData, 'master_banks');
    }

    public function updateBank()
    {

        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;

        $updateData = array(
            'bank_name'        => $data->bank_name,
            'updated_by'       => $this->session->login['name'],
            'updated_datetime' => date('Y-m-d h:i:s'),
            'Contact_Person'    => $data->Contact_Person,
            'Email_ID'        => $data->Email_ID,
            'Ph_No'        => $data->Ph_No
        );
        $this->fdr_model->Bank_update($updateData, $id);
    }

    public function section_report()
    {
        $data['sections'] = $this->fdr_model->get_section();
        $section_id = 0;
        if ($this->input->post('section') != 0)
            $section_id = $this->input->post('section');
        $data['report'] = $this->fdr_model->sectionwise_report($section_id);
        $this->load->view('FDR/section_report', $data);
    }
}
