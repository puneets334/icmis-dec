<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\EarliercourtModel;
use App\Models\Common\Dropdown_list_model;

class Earlier_court extends BaseController
{
    public $EarliercourtModel;
    public $LoginModel;
    public $Dropdown_list_model;
    function __construct()
    {
        $this->EarliercourtModel = new EarliercourtModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
        error_reporting(2);
        //error_reporting(-1);
    }
    public function index($id = null)
    {
       
       // echo ">>" . $_SESSION['filing_details']['casetype_id'];
        if (empty($_SESSION['filing_details']['diary_no'])) {

            //session()->setFlashdata("message_error", 'Please enter diary number or case number');
            return redirect()->to('Filing/Diary/search');
            exit();
            exit;
        }

        $data['fil_no'] = $_SESSION['filing_details']['fil_no'];
        $data['c_status'] = $_SESSION['filing_details']['c_status'];



        if (isset($id)) {
            $data['lower_court_details'] = $this->EarliercourtModel->getEarlierCourtData($id);
            //print_r($data['lower_court_details']);
            $data['bf_desc'] = $data['lower_court_details'][0]['brief_desc']??'';
            $data['ct_code'] = $data['lower_court_details'][0]['ct_code'];


            $data['lct_dec_dt'] = $data['lower_court_details'][0]['lct_dec_dt'];
            $date = date_create($data['lct_dec_dt']);
            $data['lct_dec_dt'] = date_format($date, "d-m-Y");

            $data['lct_judge_desg'] = $data['lower_court_details'][0]['lct_judge_desg'];
            $data['l_dist'] = $data['lower_court_details'][0]['l_dist'];
            $data['polstncode'] = $data['lower_court_details'][0]['polstncode'];
            $data['crimeno'] = $data['lower_court_details'][0]['crimeno'];
            $data['crimeyear'] = $data['lower_court_details'][0]['crimeyear'];
            $data['l_state'] = $data['lower_court_details'][0]['l_state'];
            $data['sub_law'] = $data['lower_court_details'][0]['sub_law'];
            $data['lct_casetype'] = $data['lower_court_details'][0]['lct_casetype'];
            $data['lct_caseno'] = $data['lower_court_details'][0]['lct_caseno'];
            $data['lct_caseyear'] = $data['lower_court_details'][0]['lct_caseyear'];
            $data['is_order_challenged'] = $data['lower_court_details'][0]['is_order_challenged'];
            $data['full_interim_flag'] = $data['lower_court_details'][0]['full_interim_flag'];
            $data['judgement_covered_in'] = $data['lower_court_details'][0]['judgement_covered_in'];
            $data['vehicle_code'] = $data['lower_court_details'][0]['vehicle_code'];
            $data['vehicle_no'] = $data['lower_court_details'][0]['vehicle_no'];
            $data['cnr_no'] = $data['lower_court_details'][0]['cnr_no'];
            $data['ref_court'] = $data['lower_court_details'][0]['ref_court'];
            $data['ref_case_type'] = $data['lower_court_details'][0]['ref_case_type'];
            $data['ref_case_no'] = $data['lower_court_details'][0]['ref_case_no'];
            $data['ref_case_year'] = $data['lower_court_details'][0]['ref_case_year'];
            $data['ref_state'] = $data['lower_court_details'][0]['ref_state'];
            $data['ref_district'] = $data['lower_court_details'][0]['ref_district'];
            $data['gov_not_state_id'] = $data['lower_court_details'][0]['gov_not_state_id'];
            $data['gov_not_case_type'] = $data['lower_court_details'][0]['gov_not_case_type'];
            $data['gov_not_case_no'] = $data['lower_court_details'][0]['gov_not_case_no'];
            $data['gov_not_case_year'] = $data['lower_court_details'][0]['gov_not_case_year'];

            $data['gov_not_date'] = $data['lower_court_details'][0]['gov_not_date'];
            if (!empty($data['gov_not_date'])) {
                $date_gov = date_create($data['gov_not_date']);
                $data['gov_not_date'] = date_format($date_gov, "d-m-Y");
            }


            $caseTypes = $this->Dropdown_list_model->get_case_type_court($data['l_state'], $data['ct_code']);

            $data['relied_details'] = $this->EarliercourtModel->allReliedDetailsbyLowerCourt($id);
            $data['transfer_details'] = $this->EarliercourtModel->allTransferDetails($id);

            $data['police_station_list'] = $this->Dropdown_list_model->get_police_station_list($data['l_state'], $data['l_dist']);
            if (intval($data['vehicle_code']) > 0) {
                $data['vehicle_details'] = $this->Dropdown_list_model->get_databy_rto_id($data['vehicle_code']);
                $data['rto_codes'] = $this->Dropdown_list_model->get_all_rtocode($data['vehicle_details'][0]['state']);
            } else {
                $data['vehicle_details'] = $data['rto_codes'] = "";
            }


            $data['court_type_list_rel'] = $this->Dropdown_list_model->get_all_court_type_list($data['ct_code']);
            //print_r($data['transfer_details']);


            $data['case_all_types'] = $caseTypes;
            $data['lcc_id'] =  $id;
            if (intval($data['ref_court']) > 0) {
                $data['ref_district_list'] = $this->Dropdown_list_model->get_ref_agency_code($data['ref_state'], $data['ref_court']);
                $data['case_types_ref'] = $this->Dropdown_list_model->get_case_type_court($data['ref_state'], $data['ref_court']);
            } else {
                $data['ref_district_list'] = $data['case_types_ref'] = "";
            }


            if (!empty($data['relied_details'])) {
                $data['relied_court'] = $data['relied_details']['relied_court'];
                $data['relied_state'] = $data['relied_details']['relied_state'];
                $data['relied_case_type'] = $data['relied_details']['relied_case_type'];
                $data['relied_case_no'] = $data['relied_details']['relied_case_no'];
                $data['relied_case_year'] = $data['relied_details']['relied_case_year'];
                $data['relied_district'] = $data['relied_details']['relied_district'];
                $data['relied_district_list'] = $this->Dropdown_list_model->get_ref_agency_code($data['relied_state'], $data['relied_court']);
                $data['case_types_relied'] = $this->Dropdown_list_model->get_case_type_court($data['relied_state'], $data['relied_court']);
            } else {
                $data['relied_district_list'] = $data['case_types_relied'] =   $data['relied_court'] =  $data['relied_state'] = $data['relied_case_type'] = $data['relied_case_no'] = $data['relied_case_year'] = $data['relied_district'] = "";
            }

            if (!empty($data['transfer_details'])) {
                $data['transfer_court'] = $data['transfer_details']['transfer_court'];
                $data['transfer_state'] = $data['transfer_details']['transfer_state'];
                $data['transfer_case_type'] = $data['transfer_details']['transfer_case_type'];
                $data['transfer_case_no'] = $data['transfer_details']['transfer_case_no'];
                $data['transfer_case_year'] = $data['transfer_details']['transfer_case_year'];
                $data['transfer_district'] = $data['transfer_details']['transfer_district'];
                $data['transfer_district_list'] = $this->Dropdown_list_model->get_ref_agency_code($data['transfer_state'], $data['transfer_court']);
                $data['case_types_transfer'] = $this->Dropdown_list_model->get_case_type_court($data['transfer_state'], $data['transfer_court']);
            } else {
                $data['transfer_district_list'] = $data['case_types_transfer'] =   $data['transfer_court'] =  $data['transfer_state'] = $data['transfer_case_type'] = $data['transfer_case_no'] = $data['transfer_case_year'] = $data['transfer_district'] = "";
            }



            $data['judges_list'] = $this->Dropdown_list_model->get_all_judges($data['l_state'], $data['ct_code']);

            $data['lc_judges'] = $this->EarliercourtModel->getJudgesDetailsofLowerCourt($data['lcc_id']);
            //print_r($data['lc_judges']);

        } else {
            //casetype_id 
            $data['bf_desc'] = $data['lct_dec_dt'] = $data['lct_judge_desg'] = $data['l_dist'] = $data['polstncode'] = $data['crimeno'] = $data['crimeyear'] = $data['l_state'] = "";
            $data['sub_law'] = $data['lct_casetype'] = $data['lct_caseno'] = $data['lct_caseyear'] = $data['is_order_challenged'] = $data['full_interim_flag'] = $data['judgement_covered_in'] = "";
            $data['vehicle_code'] = $data['vehicle_no'] = $data['cnr_no'] = $data['ref_court'] =  $data['ref_case_type'] = $data['ref_case_no'] = $data['ref_case_year'] = $data['ref_state'] = "";
            $data['ref_district'] = $data['gov_not_state_id'] = $data['gov_not_case_type'] = $data['gov_not_case_no'] = $data['gov_not_case_year'] = $data['gov_not_date'] = "";
            $data['vehicle_details'] = $data['rto_codes'] = $data['court_type_list_rel'] = $data['ref_district_list'] = $data['case_types_ref'] = $data['relied_district_list'] = $data['case_types_relied'] = "";
            $data['relied_court'] =  $data['relied_state'] = $data['relied_case_type'] = $data['relied_case_no'] = $data['relied_case_year'] = $data['relied_district'] = "";
            $data['transfer_district_list'] = $data['case_types_transfer'] =   $data['transfer_court'] =  $data['transfer_state'] = $data['transfer_case_type'] = $data['transfer_case_no'] = $data['transfer_case_year'] = $data['transfer_district'] = "";
            $data['lcc_id'] = $data['judges_list'] = $data['lc_judges'] = "";
            $data['ct_code'] = "";
        }
        // $model = new EarliercourtModel();
       // print_r($_SESSION['filing_details']);
        $diary_no = $_SESSION['filing_details']['diary_no'];
        $active_fil_no = $_SESSION['filing_details']['active_fil_no'];

        $section_officer = '';
        $IB_officer = '';

        if (($active_fil_no!= '' && $active_fil_no != null)) {
           
            $section_officer_array = $this->EarliercourtModel->getSectionOfficer($diary_no);
            
            if ($section_officer_array['t'] > 0) {
                $section_officer= 1;
            }
            if ($section_officer == '') {
                $check_ibuser_rs = $this->EarliercourtModel->getIBOfficer();
                if ($check_ibuser_rs['t'] > 0) {
                    $IB_officer = 1;
                }
            }
        }

        $data['section_officer'] = $section_officer;
        $data['IB_officer'] = $IB_officer;
        $data['active_fil_no'] = $active_fil_no;
        $data['session_user'] = session()->get('login')['usercode'];
        $data['casetype_idd'] =  $_SESSION['filing_details']['casetype_id'];

        $data['result'] = $this->EarliercourtModel->getAllLowerCourtDetails($diary_no);
        $data['states'] = $this->Dropdown_list_model->icmis_states();

        $data['judges_details'] = $this->EarliercourtModel->getJudgeDetailsByDiary($diary_no);
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['diary_details'] = $_SESSION['filing_details'];

        //print_r($data['diary_details']);
        //24789 print_r($data['judges_details']);

        $data['m_ljuddesc'] = get_from_table_json('post_t', 'Y', 'display');

        $data['all_ref_details'] = $this->EarliercourtModel->allReferenceDetailsByDiaryNo($diary_no);
        $data['all_gov_not_details'] = $this->EarliercourtModel->allGovernmentNotificationsByDiaryNo($diary_no);
        $data['all_relied_details'] = $this->EarliercourtModel->allReliedDetailsByDiaryNo($diary_no);
        $data['all_transfer_details'] = $this->EarliercourtModel->allTransferDetailsByDiaryNo($diary_no);

        //$data['EarliercourtModel'] = $this->EarliercourtModel;
        //pr($data['diary_details']);
         
        // print_r($data['all_transfer_details']);
        return view('Filing/earlier_court_view', $data);
    }

    public function insertEarlierCourt()
    {
        // pr($_REQUEST);
        $errorResponse = "";
        if (empty($_SESSION['filing_details']['diary_no'])) {

           // session()->setFlashdata("message_error", 'Please enter diary number or case number');
            return redirect()->to('Filing/Diary/search');
            exit();
            exit;
        }

        $diary_no = $_SESSION['filing_details']['diary_no'];

        $res_case_id =  $_SESSION['filing_details']['casetype_id'];
        $chk_diary_once = 0;
        $data['result'] = $this->EarliercourtModel->getAllLowerCourtDetails($diary_no);
        $data['states'] = $this->Dropdown_list_model->icmis_states();

        $data['m_ljuddesc'] = get_from_table_json('post_t', 'Y', 'display');

        $court_type = $this->request->getPost('radio_selected_court');
        if(empty($court_type)){
            $court_type = $this->request->getPost('ct_court_type');
        }

        $rule = array(
            //'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
        );


        if ($court_type == 1) {
            $high_court_rules = array(
                'state_agency_h' => ['label' => 'State', 'rules' => 'required'],
                'h_bench_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_1' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($high_court_rules, $rule);
        } elseif ($court_type == 4) {
            $supreme_court_rules = array(
                'state_agency' => ['label' => 'State', 'rules' => 'required'],
                'district_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_s' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($supreme_court_rules, $rule);
        } elseif ($court_type == 3) {
            $district_court_rules = array(
                'state_agency_d' => ['label' => 'State', 'rules' => 'required'],
                'district_idd' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type_d' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_5' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
            );
            $rule = array_merge($district_court_rules, $rule);
        } else {
            $state_rules = array(
                'state_agency_s' => ['label' => 'State', 'rules' => 'required'],
                'district_ids' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_2' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($state_rules, $rule);
        }


        if ($this->request->getMethod() == 'post') {

            // $ddl_vch_state = $this->request->getPost('ddl_vch_state');
            // if ($ddl_vch_state > 0 && $this->request->getPost('rto_code') == "") {
            //     session()->setFlashdata("message_error", 'Please enter rto code');
            //     return view('Filing/earlier_court_view', $data);
            // }
            // if($ddl_vch_state > 0 && $this->request->getPost('rto_code') != ""){
            //     session()->setFlashdata("message_error", 'Please enter vehicle number');
            //     return view('Filing/earlier_court_view',$data);
            // }
            // else {

            $diary_number = session()->get('filing_details')['diary_no'];

            if ($court_type == 1) {
                $state_agency = $this->request->getPost('state_agency_h');
                $district_id = $this->request->getPost('h_bench_id');
                $case_type = $this->request->getPost('case_type');
                $lc_case_no  = $this->request->getPost('lc_case_no_from');
                $lc_case_no_to = $this->request->getPost('lc_case_no');
                $lc_case_year = $this->request->getPost('lc_case_year');
                $lct_dec_dt = $this->request->getPost('impugned_date_1');
                $judges = $this->request->getPost('judge_name');
            } elseif ($court_type == 4) {
                $state_agency = $this->request->getPost('state_agency');
                $district_id = $this->request->getPost('district_id');
                $case_type = $this->request->getPost('case_type');
                $lc_case_no  = $this->request->getPost('lc_case_no_from');
                $lc_case_no_to = $this->request->getPost('lc_case_no');
                $lc_case_year = $this->request->getPost('lc_case_year');
                $lct_dec_dt = $this->request->getPost('impugned_date_s');
                $judges = $this->request->getPost('judge_name');
            } elseif ($court_type == 3) {
                $state_agency = $this->request->getPost('state_agency_d');
                $district_id = $this->request->getPost('district_idd');
                $case_type = $this->request->getPost('case_type_d');
                $lc_case_no  = $this->request->getPost('lc_case_no_d');
                $lc_case_no_to = $this->request->getPost('lc_case_no_to');
                $lc_case_year = $this->request->getPost('lc_case_year_2');
                $lct_dec_dt = $this->request->getPost('impugned_date_5');
                $judges = $this->request->getPost('judge_name_3');
            } elseif ($court_type == 5) {
                $state_agency = $this->request->getPost('state_agency_s');
                $district_id = $this->request->getPost('district_ids');
                $case_type = $this->request->getPost('case_type');
                $lc_case_no  = $this->request->getPost('lc_case_no_from');
                $lc_case_no_to = $this->request->getPost('lc_case_no');
                $lc_case_year = $this->request->getPost('lc_case_year');
                $lct_dec_dt = $this->request->getPost('impugned_date_2');
                $judges = $this->request->getPost('judge_name_5');
            }

            if ($court_type == 3) {
                $order_description =  $this->request->getPost('order_description_d');
                $subject_law =  $this->request->getPost('lc_subject_law_3');
            } else {
                $order_description =  $this->request->getPost('order_description');
                $subject_law =  $this->request->getPost('lc_subject_law');
            }

            $m_policestn = intval($this->request->getPost('m_policestn')) > 0 ? $this->request->getPost('m_policestn') : '0';
            $crimeno = $this->request->getPost('crimeno') ? $this->request->getPost('crimeno') : '';

            if ($m_policestn > 0) {
                $crimeyear = $this->request->getPost('crimeyear');
            } else {
                $crimeyear = 0;
            }


            $lct_dec_dt = date_create($lct_dec_dt);
            $lct_dec_dt  = date_format($lct_dec_dt, "Y-m-d");

            $is_order_challenged = "";
            if (!empty($this->request->getPost('chk_judge_challenged'))) {
                if ($this->request->getPost('chk_judge_challenged') == "on") {
                    $is_order_challenged = 'Y';
                } else {
                    $is_order_challenged = 'N';
                }
            }

            $userid = session()->get('login')['empid'];

            if (intval($this->request->getPost('lc_idd')) > 0) {
                //update
                if (!empty($this->request->getPost('government_notification_date'))) {
                    $gov_not_dt = date_create($this->request->getPost('government_notification_date'));
                    $gov_not_dt  = date_format($gov_not_dt, "Y-m-d");
                } else {
                    $gov_not_dt = NULL;
                }


                $updateEarlierCourtArray = [[
                    'lct_dec_dt' => $lct_dec_dt,
                    'l_dist' => $district_id,
                    'usercode' => $userid,
                    'ct_code' => $court_type,
                    'ent_dt' =>  date('Y-m-d H:i:s'),
                    'diary_no' => $diary_no,
                    'l_state' => $state_agency,
                    'lw_display' => 'Y',
                    'lct_judge_desg' => !empty($this->request->getPost('m_ljuddesc')) ? $this->request->getPost('m_ljuddesc') : NULL,
                    'polstncode' => $m_policestn,
                    'crimeno' => $crimeno,
                    'crimeyear' => $crimeyear,
                    'brief_desc' => $order_description,
                    'sub_law' => $subject_law,
                    'lct_casetype' =>  $case_type,
                    'lct_caseno' =>  $lc_case_no,
                    'lct_caseyear' =>  $lc_case_year,
                    'is_order_challenged' =>  $is_order_challenged,
                    'full_interim_flag' =>  !empty($this->request->getPost('lc_judgment_type')) ? $this->request->getPost('lc_judgment_type') : " ",
                    'judgement_covered_in' =>  $this->request->getPost('lc_judgement_covered_in'),
                    'vehicle_code' =>  !empty($this->request->getPost('rto_code')) ? $this->request->getPost('rto_code') : NULL,
                    'vehicle_no' =>  $this->request->getPost('lc_vehicle_no'),
                    'cnr_no' => !empty($this->request->getPost('filing_no')) ? $this->request->getPost('filing_no') : " ",
                    'ref_court' =>  !empty($this->request->getPost('ddl_ref_court')) ? $this->request->getPost('ddl_ref_court') : NULL,
                    'ref_case_type' =>  !empty($this->request->getPost('ddl_ref_case_type')) ? $this->request->getPost('ddl_ref_case_type') : NULL,
                    'ref_case_no' =>  !empty($this->request->getPost('txt_ref_caseno')) ? $this->request->getPost('txt_ref_caseno') : NULL,
                    'ref_case_year' => !empty($this->request->getPost('ddl_ref_caseyr')) ? $this->request->getPost('ddl_ref_caseyr') : NULL,
                    'ref_state' =>  !empty($this->request->getPost('ddl_ref_state')) ? $this->request->getPost('ddl_ref_state') : NULL,
                    'ref_district' =>  !empty($this->request->getPost('ddl_ref_district')) ? $this->request->getPost('ddl_ref_district') : NULL,
                    'gov_not_state_id' =>  !empty($this->request->getPost('ddl_gov_not_state')) ? $this->request->getPost('ddl_gov_not_state') : NULL,
                    'gov_not_case_type' =>  !empty($this->request->getPost('txt_gov_not_no')) ? $this->request->getPost('txt_gov_not_no') : " ",
                    'gov_not_case_no' =>  !empty($this->request->getPost('txt_g_n_no')) ? $this->request->getPost('txt_g_n_no') : NULL,
                    'gov_not_case_year' =>  !empty($this->request->getPost('ddl_g_n_y')) ? $this->request->getPost('ddl_g_n_y') : " ",
                    'gov_not_date' =>  $gov_not_dt,
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                    'lower_court_id' => $this->request->getPost('lc_idd')
                ]];
                //$this->db = \Config\Database::connect();
               // $this->db->transStart();

                
                $earlier_court_old = is_data_from_table('lowerct',['lower_court_id'=> $this->request->getPost('lc_idd')],'*');
                    if (!empty($earlier_court_old)) {
                        foreach ($earlier_court_old as $earlier_court_row) {
                            $data_addon_party = [
                                'update_datetime' => date("Y-m-d H:i:s"),
                                'update_userip' => getClientIP(),
                            ];
                         
                            $final_array_ec = array_merge($data_addon_party, $earlier_court_row);
                            $rs3 = insert('lowerct_history', $final_array_ec);
                        }
                    }   
                $EarlierCourtId = $this->EarliercourtModel->updateEarlierCourt($updateEarlierCourtArray);

                $deleteT = $this->EarliercourtModel->deleteTransferDetails($this->request->getPost('lc_idd'));
                $deleteRelied = $this->EarliercourtModel->deleteReliedDetails($this->request->getPost('lc_idd'));
                $deleteJudge = $this->EarliercourtModel->deleteJudgesDetails($this->request->getPost('lc_idd'));

                if (is_array($judges)) {
                    foreach ($judges as $judge_id) {
                        if(!empty($judge_id)){
                            $insertJudgesArray = [
                                'lct_display' => 'Y',
                                'lowerct_id' => $this->request->getPost('lc_idd'),
                                'create_modify' => date("Y-m-d H:i:s"),
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'judge_id' => $judge_id
                            ];

                            $insertedJudges = $this->EarliercourtModel->insertLowerCourtJudges($insertJudgesArray);
                        }
                    }
                }

                if (!empty($this->request->getPost('ddl_relied_court'))) {
                    $insertReliedDetails = [
                        'display' => 'Y',
                        'lowerct_id' => $this->request->getPost('lc_idd'),
                        'relied_court' => !empty($this->request->getPost('ddl_relied_court')) ? $this->request->getPost('ddl_relied_court') : NULL,
                        'relied_case_type' => !empty($this->request->getPost('ddl_relied_case_type')) ? $this->request->getPost('ddl_relied_case_type') : NULL,
                        'relied_case_no' => !empty($this->request->getPost('txt_relied_caseno')) ? $this->request->getPost('txt_relied_caseno') : NULL,
                        'relied_case_year' => !empty($this->request->getPost('ddl_relied_caseyr')) ? $this->request->getPost('ddl_relied_caseyr') : NULL,
                        'relied_state' => !empty($this->request->getPost('ddl_relied_state')) ? $this->request->getPost('ddl_relied_state') : NULL,
                        'relied_district' => !empty($this->request->getPost('ddl_relied_district')) ? $this->request->getPost('ddl_relied_district') : NULL,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];
                    $getReliedDetails = $this->EarliercourtModel->insertReliedDetails($insertReliedDetails);
                }


                if (!empty($this->request->getPost('ddl_transfer_to'))) {
                    // check for Relied Details
                    $insertTransferDetails = [
                        'display' => 'Y',
                        'lowerct_id' => $this->request->getPost('lc_idd'),
                        'transfer_court' => !empty($this->request->getPost('ddl_transfer_to')) ? $this->request->getPost('ddl_transfer_to') : NULL,
                        'transfer_case_type' => !empty($this->request->getPost('ddl_tra_to_case_type')) ? $this->request->getPost('ddl_tra_to_case_type') : NULL,
                        'transfer_case_no' => !empty($this->request->getPost('txt_tra_to_caseno')) ? $this->request->getPost('txt_tra_to_caseno') : NULL,
                        'transfer_case_year' => !empty($this->request->getPost('ddl_tra_to_caseyr')) ? $this->request->getPost('ddl_tra_to_caseyr') : NULL,
                        'transfer_state' => !empty($this->request->getPost('ddl_tra_to_state')) ? $this->request->getPost('ddl_tra_to_state') : NULL,
                        'transfer_district' => !empty($this->request->getPost('ddl_tra_to_district')) ? $this->request->getPost('ddl_tra_to_district') : NULL,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];

                    $getTransferetails = $this->EarliercourtModel->insertTransferDetails($insertTransferDetails);
                }

                // Add here for notbefore code

                if (
                    $court_type == '4' &&
                    (($res_case_id == '9' || $res_case_id == '10') && $case_type != 19 && $case_type != 20) ||
                    (($res_case_id == '25' && $case_type == 9) || ($res_case_id == '26' && $case_type == 10))
                ) {

                    if ($chk_diary_once == 0) {
                        $builder = $this->db->table("not_before");
                        $builder->select('*');
                        $builder->where('diary_no', $diary_number);
                        $builder->where('enterby', 19);
                        $query = $builder->get();


                        if ($query->getNumRows() <= 0) {
                            $diary_details = get_diary_case_type($case_type, $lc_case_no, $lc_case_year);
                            if(!empty($diary_details)){
                                $get_diary_case_type = $diary_details;

                                $disposal_det = is_data_from_table('dispose', ['diary_no' => $get_diary_case_type], 'jud_id');

                                if (!empty($disposal_det)) {
                                    foreach ($disposal_det as $disposal_judge_id) {
                                        $ex_disposal =  explode(',', $disposal_judge_id['jud_id']);

                                        for ($index2 = 0; $index2 < count($ex_disposal); $index2++) {
                                            if ($ex_disposal[$index2] != 0) {
                                                $disposal_ex_index = $ex_disposal[$index2];
                                                $res_chk_bef_not = is_data_from_table('not_before', ['diary_no' => $diary_number, 'j1' => $disposal_ex_index, 'notbef' => 'B'], "*");

                                                if (empty($res_chk_bef_not)) {
                                                    $insertnotBeforeArray = [
                                                        'diary_no' => $diary_number,
                                                        'j1' => $disposal_ex_index,
                                                        'notbef' => 'B',
                                                        'ent_dt' => date("Y-m-d H:i:s"),
                                                        'usercode' => session()->get('login')['usercode'],
                                                        'updated_on' => date("Y-m-d H:i:s"),
                                                        'updated_by' => session()->get('login')['usercode'],
                                                        'updated_by_ip' => getClientIP(),
                                                        'u_ip' => getClientIP(),
                                                        'enterby' => "19",
                                                        'u_mac' => " ",
                                                        'res_id'=>0,
                                                        'res_add'=>" "
                                                    ];
                                                    insert('not_before', $insertnotBeforeArray);
                                                }
                                            }
                                        }
                                    }
                                }
                            }


                        }
                        $chk_diary_once = 1;
                    }
                }

                //$this->db->transComplete();

                session()->setFlashdata("success_msg", 'Earlier Court Detatils updated successfully.');
                return redirect()->to('/Filing/Earlier_court/index/');
                //  $this->response->redirect(site_url('/Filing/Earlier_court/index/' . $this->request->getPost('lc_idd')));
            } else {
                if (!empty($lc_case_no_to)) {
                    $lc_case_no_end = $lc_case_no_to;
                } else {
                    $lc_case_no_end = $lc_case_no;
                }

                for ($l_case_no = $lc_case_no; $l_case_no <= $lc_case_no_end; $l_case_no++) {

                    $EarlierCourtArray = array('state_agency' => $state_agency, 'district_id' => $district_id, 'case_type' => $case_type, 'lc_case_no' => $l_case_no, 'lc_case_year' => $lc_case_year, 'lct_dec_dt' => $lct_dec_dt, 'm_policestn' => $m_policestn, 'crimeno' => $crimeno, 'crimeyear' => $crimeyear, 'diary_no' => $diary_no, 'c_type' => $court_type);

                    $is_already_added = $this->EarliercourtModel->getAlreadyAddeLowerCourtDetails($EarlierCourtArray);

                    $cnr_no = !empty($this->request->getPost('cnr_no')) ? $this->request->getPost('cnr_no') : " ";
                    if (!empty($this->request->getPost('government_notification_date'))) {
                        $gov_not_dt = date_create($this->request->getPost('government_notification_date'));
                        $gov_not_dt  = date_format($gov_not_dt, "Y-m-d");
                    } else {
                        $gov_not_dt = NULL;
                    }

                    if (empty($is_already_added)) {
                        $insertEarlierCourtArray = [
                            'lct_dec_dt' => $lct_dec_dt,
                            'l_dist' => $district_id,
                            'usercode' => $userid,
                            'ct_code' => $court_type,
                            'ent_dt' =>  date('Y-m-d H:i:s'),
                            'diary_no' => $diary_no,
                            'l_state' => $state_agency,
                            'lw_display' => 'Y',
                            'lct_judge_desg' => !empty($this->request->getPost('m_ljuddesc')) ? $this->request->getPost('m_ljuddesc') : NULL,
                            'polstncode' => $m_policestn,
                            'crimeno' => $crimeno,
                            'crimeyear' => $crimeyear,
                            'brief_desc' => $order_description,
                            'sub_law' => $subject_law,
                            'lct_casetype' =>  $case_type,
                            'lct_caseno' =>  $l_case_no,
                            'lct_caseyear' =>  $lc_case_year,
                            'is_order_challenged' =>  $is_order_challenged,
                            'full_interim_flag' =>  !empty($this->request->getPost('lc_judgment_type')) ? $this->request->getPost('lc_judgment_type') : " ",
                            'judgement_covered_in' =>  $this->request->getPost('lc_judgement_covered_in'),
                            'vehicle_code' =>  !empty($this->request->getPost('rto_code')) ? $this->request->getPost('rto_code') : NULL,
                            'vehicle_no' =>  $this->request->getPost('lc_vehicle_no'),
                            'cnr_no' => !empty($this->request->getPost('filing_no')) ? $this->request->getPost('filing_no') : " ",
                            'ref_court' =>  !empty($this->request->getPost('ddl_ref_court')) ? $this->request->getPost('ddl_ref_court') : NULL,
                            'ref_case_type' =>  !empty($this->request->getPost('ddl_ref_case_type')) ? $this->request->getPost('ddl_ref_case_type') : NULL,
                            'ref_case_no' =>  !empty($this->request->getPost('txt_ref_caseno')) ? $this->request->getPost('txt_ref_caseno') : NULL,
                            'ref_case_year' => !empty($this->request->getPost('ddl_ref_caseyr')) ? $this->request->getPost('ddl_ref_caseyr') : NULL,
                            'ref_state' =>  !empty($this->request->getPost('ddl_ref_state')) ? $this->request->getPost('ddl_ref_state') : NULL,
                            'ref_district' =>  !empty($this->request->getPost('ddl_ref_district')) ? $this->request->getPost('ddl_ref_district') : NULL,
                            'gov_not_state_id' =>  !empty($this->request->getPost('ddl_gov_not_state')) ? $this->request->getPost('ddl_gov_not_state') : NULL,
                            'gov_not_case_type' =>  !empty($this->request->getPost('txt_gov_not_no')) ? $this->request->getPost('txt_gov_not_no') : " ",
                            'gov_not_case_no' =>  !empty($this->request->getPost('txt_g_n_no')) ? $this->request->getPost('txt_g_n_no') : NULL,
                            'gov_not_case_year' =>  !empty($this->request->getPost('ddl_g_n_y')) ? $this->request->getPost('ddl_g_n_y') : " ",
                            'gov_not_date' =>  $gov_not_dt,
                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];

                        /*$this->db = \Config\Database::connect();
                        $this->db->transStart();*/
                        $insertedEarlierCourtId = $this->EarliercourtModel->insertEarlierCourt($insertEarlierCourtArray);
                        if ($insertedEarlierCourtId) {

                            // Insert judges data

                            if (is_array($judges)) {
                                foreach ($judges as $judge_id) {
                                    $insertJudgesArray = [
                                        'lct_display' => 'Y',
                                        'lowerct_id' => $insertedEarlierCourtId,
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP(),
                                        'judge_id' => $judge_id
                                    ];
                                    $insertedJudges = $this->EarliercourtModel->insertLowerCourtJudges($insertJudgesArray);
                                }
                            }

                            if (!empty($this->request->getPost('ddl_relied_court'))) {
                                $relied_detailsArray = array(
                                    'lowerct_id' => $insertedEarlierCourtId,
                                    'relied_court' => !empty($this->request->getPost('ddl_relied_court')) ? $this->request->getPost('ddl_relied_court') : NULL,
                                    'relied_case_type' => !empty($this->request->getPost('ddl_relied_case_type')) ? $this->request->getPost('ddl_relied_case_type') : NULL,
                                    'relied_case_no' => !empty($this->request->getPost('txt_relied_caseno')) ? $this->request->getPost('txt_relied_caseno') : NULL,
                                    'relied_case_year' => !empty($this->request->getPost('ddl_relied_caseyr')) ? $this->request->getPost('ddl_relied_caseyr') : NULL,
                                    'relied_state' => !empty($this->request->getPost('ddl_relied_state')) ? $this->request->getPost('ddl_relied_state') : NULL,
                                    'relied_district' => !empty($this->request->getPost('ddl_relied_district')) ? $this->request->getPost('ddl_relied_district') : NULL
                                );

                                $getReliedDetails = $this->EarliercourtModel->getReliedDetails($relied_detailsArray);
                                $totalReliedDetails = $getReliedDetails[0]['count'];

                                if (intval($totalReliedDetails) == 0) {
                                    $insertReliedDetails = [
                                        'display' => 'Y',
                                        'lowerct_id' => $insertedEarlierCourtId,
                                        'relied_court' => !empty($this->request->getPost('ddl_relied_court')) ? $this->request->getPost('ddl_relied_court') : NULL,
                                        'relied_case_type' => !empty($this->request->getPost('ddl_relied_case_type')) ? $this->request->getPost('ddl_relied_case_type') : NULL,
                                        'relied_case_no' => !empty($this->request->getPost('txt_relied_caseno')) ? $this->request->getPost('txt_relied_caseno') : NULL,
                                        'relied_case_year' => !empty($this->request->getPost('ddl_relied_caseyr')) ? $this->request->getPost('ddl_relied_caseyr') : NULL,
                                        'relied_state' => !empty($this->request->getPost('ddl_relied_state')) ? $this->request->getPost('ddl_relied_state') : NULL,
                                        'relied_district' => !empty($this->request->getPost('ddl_relied_district')) ? $this->request->getPost('ddl_relied_district') : NULL,
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP()
                                    ];

                                    $getReliedDetails = $this->EarliercourtModel->insertReliedDetails($insertReliedDetails);
                                }
                            }


                            if (!empty($this->request->getPost('ddl_transfer_to'))) {
                                $transfer_detailsArray = array(
                                    'lowerct_id' => $insertedEarlierCourtId,
                                    'transfer_court' => !empty($this->request->getPost('ddl_transfer_to')) ? $this->request->getPost('ddl_transfer_to') : NULL,
                                    'transfer_case_type' => !empty($this->request->getPost('ddl_tra_to_case_type')) ? $this->request->getPost('ddl_tra_to_case_type') : NULL,
                                    'transfer_case_no' => !empty($this->request->getPost('txt_tra_to_caseno')) ? $this->request->getPost('txt_tra_to_caseno') : NULL,
                                    'transfer_case_year' => !empty($this->request->getPost('ddl_tra_to_caseyr')) ? $this->request->getPost('ddl_tra_to_caseyr') : NULL,
                                    'transfer_state' => !empty($this->request->getPost('ddl_tra_to_state')) ? $this->request->getPost('ddl_tra_to_state') : NULL,
                                    'transfer_district' => !empty($this->request->getPost('ddl_tra_to_district')) ? $this->request->getPost('ddl_tra_to_district') : NULL
                                );

                                $getTransferDetails = $this->EarliercourtModel->getTransferDetails($transfer_detailsArray);
                                $totalTDetails = $getTransferDetails[0]['count'];

                                if (intval($totalTDetails) == 0) {
                                    $insertTransferDetails = [
                                        'display' => 'Y',
                                        'lowerct_id' => $insertedEarlierCourtId,
                                        'transfer_court' => !empty($this->request->getPost('ddl_transfer_to')) ? $this->request->getPost('ddl_transfer_to') : NULL,
                                        'transfer_case_type' => !empty($this->request->getPost('ddl_tra_to_case_type')) ? $this->request->getPost('ddl_tra_to_case_type') : NULL,
                                        'transfer_case_no' => !empty($this->request->getPost('txt_tra_to_caseno')) ? $this->request->getPost('txt_tra_to_caseno') : NULL,
                                        'transfer_case_year' => !empty($this->request->getPost('ddl_tra_to_caseyr')) ? $this->request->getPost('ddl_tra_to_caseyr') : NULL,
                                        'transfer_state' => !empty($this->request->getPost('ddl_tra_to_state')) ? $this->request->getPost('ddl_tra_to_state') : NULL,
                                        'transfer_district' => !empty($this->request->getPost('ddl_tra_to_district')) ? $this->request->getPost('ddl_tra_to_district') : NULL,
                                        'create_modify' => date("Y-m-d H:i:s"),
                                        'updated_on' => date("Y-m-d H:i:s"),
                                        'updated_by' => session()->get('login')['usercode'],
                                        'updated_by_ip' => getClientIP()
                                    ];

                                    $getTransferetails = $this->EarliercourtModel->insertTransferDetails($insertTransferDetails);
                                }
                            }

                            // Add here for notbefore code
                            
                            if (
                                $court_type == '4' &&
                                (($res_case_id == '9' || $res_case_id == '10') && $case_type != 19 && $case_type != 20) ||
                                (($res_case_id == '25' && $case_type == 9) || ($res_case_id == '26' && $case_type == 10))
                            ) {

                                if ($chk_diary_once == 0) {
                                    $builder = $this->db->table("not_before");
                                    $builder->select('*');
                                    $builder->where('diary_no', $diary_number);
                                    $builder->where('enterby', 19);
                                    $query = $builder->get();


                                    if ($query->getNumRows() <= 0) {
                                        $diary_details = get_diary_case_type($case_type, $l_case_no, $lc_case_year);
                                        if(!empty($diary_details)){
                                        $get_diary_case_type = $diary_details;
                                        
                                        $disposal_det = is_data_from_table('dispose', ['diary_no' => $get_diary_case_type], 'jud_id');
                                        
                                        if (!empty($disposal_det)) {
                                            foreach ($disposal_det as $disposal_judge_id) {
                                                $ex_disposal =  explode(',', $disposal_judge_id['jud_id']);
                                                
                                                for ($index2 = 0; $index2 < count($ex_disposal); $index2++) {
                                                    if ($ex_disposal[$index2] != 0) {
                                                        $disposal_ex_index = $ex_disposal[$index2];
                                                        $res_chk_bef_not = is_data_from_table('not_before', ['diary_no' => $diary_number, 'j1' => $disposal_ex_index, 'notbef' => 'B'], "*");

                                                         if (empty($res_chk_bef_not)) {
                                                             $insertnotBeforeArray = [
                                                                 'diary_no' => $diary_number,
                                                                 'j1' => $disposal_ex_index,
                                                                 'notbef' => 'B',
                                                                 'ent_dt' => date("Y-m-d H:i:s"),
                                                                 'usercode' => session()->get('login')['usercode'],
                                                                 'updated_on' => date("Y-m-d H:i:s"),
                                                                 'updated_by' => session()->get('login')['usercode'],
                                                                 'updated_by_ip' => getClientIP(),
                                                                 'u_ip' => getClientIP(),
                                                                 'enterby' => "19",
                                                                 'u_mac' => " ",
                                                                 'res_id'=>0,
                                                                 'res_add'=>" "
                                                             ];
                                                             insert('not_before', $insertnotBeforeArray);
                                                         }
                                                    }
                                                }
                                            }
                                        }
                                    }    


                                    }
                                    $chk_diary_once = 1;
                                }
                            }

                            // End for the not before code
                       }
                        $errorResponse = 0;
                    } else {

                        $errorResponse = 1;
                    }
                }
               // exit;
                if ($errorResponse == 1) {
                    session()->setFlashdata("message_error", 'Earlier Court Details already existed.');
                    $this->response->redirect(site_url('/Filing/Earlier_court'));
                } else {
                    session()->setFlashdata("success_msg", 'Earlier Court Detatils inserted successfully.');
                    $this->response->redirect(site_url('/Filing/Earlier_court'));
                }
               // $this->db->transComplete();

            }
        } else {
            return view('Filing/earlier_court_view', $data);
        }
    }


    public function deleteEarliercourt()
    {

        $lowerCourt_id = $this->request->getPost('lower_court_id');
        $d_no = $this->request->getPost('d_no');
        $lc_cno = $this->request->getPost('lc_cno');
        $lc_cyear = $this->request->getPost('lc_cyear');
        $lc_oc = $this->request->getPost('lc_oc');
        $case_type = $this->request->getPost('lc_ct');
        $res_case_id = $this->request->getPost('d_ct_id'); 
        $court_type = $this->request->getPost('court_type'); 
        // print_r($_POST);
        // exit;
        $response = "";
        if ($lc_oc == 'Y') {
            $caveatDetails = $this->EarliercourtModel->getCaveatDetails($d_no, $lc_cno, $lc_cyear);
        }
        if (empty($caveatDetails) || $lc_oc != 'Y') {
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $deleteT = $this->EarliercourtModel->deleteTransferDetails($lowerCourt_id);
            $deleteRelied = $this->EarliercourtModel->deleteReliedDetails($lowerCourt_id);
            $deleteJudge = $this->EarliercourtModel->deleteJudgesDetails($lowerCourt_id);
            $deleteLowerCourt = $this->EarliercourtModel->deleteLowerCourtDetails($lowerCourt_id);
            // Add here for code -- Start

            if (
                $court_type == '4' &&
                (($res_case_id == '9' || $res_case_id == '10') && $case_type != 19 && $case_type != 20) ||
                (($res_case_id == '25' && $case_type == 9) || ($res_case_id == '26' && $case_type == 10))
            ) {
               
                if ($res_case_id == '9' || $res_case_id == '10'){
                    $lower_court_data = is_data_from_table('lowerct', ['diary_no' => $d_no,  'lw_display' => 'Y'], 'lower_court_id');
                }
                else if ($res_case_id == '25'){
                    $lower_court_data = is_data_from_table('lowerct', ['diary_no' => $d_no,  'lw_display' => 'Y','lct_casetype'=>'9'], 'lower_court_id');
                }
                else if ($res_case_id == '26'){ 
                    $lower_court_data = is_data_from_table('lowerct', ['diary_no' => $d_no,  'lw_display' => 'Y','lct_casetype'=>'10'], 'lower_court_id');
                }
                if(empty($lower_court_data)){
                    $builder = $this->db->table("not_before");
                    $builder->select('j1,notbef,usercode,ent_dt,enterby, u_mac,u_ip');
                    $builder->where('diary_no', $d_no);
                    $builder->where('enterby', 19);
                    $builder->where('notbef', 'B');
                    $query = $builder->get();

                    if ($query->getNumRows() >= 0) {
                        $result = $query->getResultArray();
                        foreach($result as $r_res_chk_bef_not){
                            $insertnotBeforeArray = [
                                'diary_no' => $d_no,
                                'j1' => $r_res_chk_bef_not['j1'],
                                'notbef' => $r_res_chk_bef_not['notbef'],
                                'c_dt' => date("Y-m-d H:i:s"),
                                'cur_ucode' => session()->get('login')['usercode'],
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'usercode' => $r_res_chk_bef_not['usercode'],
                                'enterby_old' => $r_res_chk_bef_not['enterby'],
                                'old_u_mac' =>$r_res_chk_bef_not['u_mac'],
                                'ent_dt'=>$r_res_chk_bef_not['ent_dt'],
                                'old_u_ip'=>$r_res_chk_bef_not['u_ip'],
                                'cur_u_ip'=>getClientIP(),
                                'cur_u_mac'=> " "
                            ];
                            insert('not_before_his', $insertnotBeforeArray);
                        }
                    }

                    delete('not_before', ['diary_no'=>$d_no,'enterby'=>19, 'notbef'=>'B']);

                }
            }

            //End

            $this->db->transComplete();
            $response = "Y";
        } else {
            $caveatDetailNumber = $this->EarliercourtModel->getCaveatDetailsNumber($d_no, $lc_cno, $lc_cyear);
            $caveatDetailNo = $caveatDetailNumber[0]['string_agg'];
            $response = " Caveat No " . $caveatDetailNo . " is associated with this matter , hence you cannot delete this case Details.";
        }

        return $response;

        //

    }


    public function checkforUpdateEarliercourt()
    {
        $lowerCourt_id = $this->request->getPost('lower_court_id');
        $d_no = $this->request->getPost('d_no');
        $lc_cno = $this->request->getPost('lc_cno');
        $lc_cyear = $this->request->getPost('lc_cyear');
        $lc_oc = $this->request->getPost('lc_oc');
        $response = "";
        if ($lc_oc == 'Y') {
            $caveatDetails = $this->EarliercourtModel->getCaveatDetails($d_no, $lc_cno, $lc_cyear);
        }
        if (empty($caveatDetails) || $lc_oc != 'Y') {
            $response = "Y";
        } else {
            $caveatDetailNumber = $this->EarliercourtModel->getCaveatDetailsNumber($d_no, $lc_cno, $lc_cyear);
            $caveatDetailNo = $caveatDetailNumber[0]['string_agg'];
            $response = "Caveat No " . $caveatDetailNo . " is associated with this matter , hence you cannot modify this case Details.";
        }

        return $response;

        //

    }

    public function copy(){
        return view('Filing/earlier_court_copy_view');
    }

    public function getCauseTitle(){
        $diary_no = $this->request->getPost('diary_no');
        $diary_details = is_data_from_table('main', ['diary_no' => $diary_no], 'pet_name, res_name','R');
        if(empty($diary_details)){
            $diary_details = is_data_from_table('main_a', ['diary_no' => $diary_no], 'pet_name, res_name','R');
        }
        if(empty($diary_details)){
            $details = "<font style='text-align: center;font-size: 20px;color: black'><b>Case not found</b></font>";
        }else{
            $details = "</br><font style='text-align: center;font-size: 20px;color: red'>Cause Title: </font></br><font style='text-align: center;font-size: 20px;color: blue'>".$diary_details['pet_name']."</font></br><font style='text-align: center;font-size: 20px;color: blue'>VS</font></br><font style='text-align: center;font-size: 20px;color: blue'>".$diary_details['res_name']."</font></br>";
        }
        $html = '<div class="col-3"></div>';
        $html .= '<div class="col-6"><center></centr><label>'.$details.'</label></center>';
        $html .= "</div>";
        $html .= '<div class="col-3"></div>';
        echo $html;
    }

    public function copylowercourt(){
        $from_diary_no = $this->request->getPost('from_diary_no');
        $to_diary_number = $this->request->getPost('to_diary_number');

        $diary_details = is_data_from_table('main', ['diary_no' => $to_diary_number, 'c_status' => 'D'], 'c_status','R');
        if(empty($diary_details))
        {   
            $diary_details = is_data_from_table('main_a', ['diary_no' => $to_diary_number], 'c_status','R');
        }
        

        if(!empty($diary_details) && $diary_details['c_status'] == 'D') {
            echo '<div class="col-2"></div><div class="col-8"><font style="text-align: center;font-size: 20px;color: red"><center><br>DETAILS CANNOT BE COPIED IN A DISPOSED OFF MATTER</center></font></div><div class="col-2"></div>';
            exit();
        }

        $all_lowercourt_data = is_data_from_table('lowerct', ['diary_no' => $from_diary_no], '*');

        if(empty($all_lowercourt_data)){
            $all_lowercourt_data = is_data_from_table('lowerct_a', ['diary_no' => $from_diary_no], '*');
        }

        if(!empty($all_lowercourt_data)){
            foreach($all_lowercourt_data as $row){

                $lowerct_id = $row['lower_court_id'];

                $insertEarlierCourtArray = [
                    'lct_dec_dt' => $row['lct_dec_dt'],
                    'lct_judge_name'=>$row['lct_judge_name'],
                    'lctjudname2'=>$row['lctjudname2'],
                    'lct_jud_id'=>$row['lct_jud_id'],
                    'lct_jud_id1'=>$row['lct_jud_id1'],
                    'lct_jud_id2'=>$row['lct_jud_id2'],
                    'lct_jud_id3'=>$row['lct_jud_id3'],
                    'lctjudname3'=>$row['lctjudname3'],
                    'doi'=>$row['doi'],
                    'hjs_cnr'=>$row['hjs_cnr'],
                    'ljs_doi'=>$row['ljs_doi'],
                    'ljs_cnr'=>$row['ljs_cnr'],
                    'l_dist' => $row['l_dist'],
                    'polstncode' => $row['polstncode'],
                    'ct_code' => $row['ct_code'],
                    'ent_dt' =>  'now()',
                    'diary_no' => $to_diary_number,
                    'l_state' => $row['l_state'],
                    'lw_display' => $row['lw_display'],
                    'lct_judge_desg' => $row['lct_judge_desg'],
                    'crimeno' => $row['crimeno'],
                    'crimeyear' => $row['crimeyear'],
                    'brief_desc' => $row['brief_desc'],
                    'sub_law' => $row['sub_law'],
                    'lct_casetype' =>  $row['lct_casetype'],
                    'lct_caseno' =>  $row['lct_caseno'],
                    'lct_caseyear' =>  $row['lct_caseyear'],
                    'is_order_challenged' =>  $row['is_order_challenged'],
                    'full_interim_flag' =>  $row['full_interim_flag'],
                    'judgement_covered_in' =>  $row['judgement_covered_in'],
                    'vehicle_code' =>  $row['vehicle_code'],
                    'vehicle_no' => $row['vehicle_no'],
                    'cnr_no' =>$row['cnr_no'],
                    'ref_court' =>  $row['ref_court'],
                    'ref_case_type' =>  $row['ref_case_type'],
                    'ref_case_no' =>  $row['ref_case_no'],
                    'ref_case_year' => $row['ref_case_year'],
                    'ref_state' =>  $row['ref_state'],
                    'ref_district' =>  $row['ref_district'],
                    'gov_not_state_id' =>  $row['gov_not_state_id'],
                    'gov_not_case_type' =>  $row['gov_not_case_type'],
                    'gov_not_case_no' =>  $row['gov_not_case_no'],
                    'gov_not_case_year' =>  $row['gov_not_case_year'],
                    'gov_not_date' =>  $row['gov_not_date'],
                    'fir_lodge_date'=> $row['fir_lodge_date'],
                    'l_inddep' => $row['l_org'] ?? 0,
                    'l_org' => $row['l_org'] ?? 0,
                    'l_orgname' => $row['l_orgname'] ?? 0,
                    'l_ordchno' => $row['l_ordchno'] ?? 0,
                    // 'create_modify' => date("Y-m-d H:i:s"),
                    // 'updated_on' => date("Y-m-d H:i:s"),
                    // 'updated_by' => session()->get('login')['usercode'],
                    // 'updated_by_ip' => getClientIP(),
                    // 'usercode'=> session()->get('login')['usercode'],
                ];
                $insertedEarlierCourtId = $this->EarliercourtModel->insertEarlierCourt($insertEarlierCourtArray);

                //Get Transfer Details

                $all_transfer_data = is_data_from_table('transfer_to_details', ['lowerct_id' => $lowerct_id], '*');
                if(!empty($all_transfer_data)) {
                    foreach ($all_transfer_data as $row_transfer) {
                        $insertTransferDetails = [
                            'display' => 'Y',
                            'lowerct_id' => $insertedEarlierCourtId,
                            'transfer_court' => $row_transfer['transfer_court'],
                            'transfer_case_type' => $row_transfer['transfer_case_type'],
                            'transfer_case_no' => $row_transfer['transfer_case_no'],
                            'transfer_case_year' => $row_transfer['transfer_case_year'],
                            'transfer_state' => $row_transfer['transfer_state'],
                            'transfer_district' => $row_transfer['transfer_district'],
                            'l_inddep' => $row['l_org'] ?? 0,
                            'l_org' => $row['l_org'] ?? 0,
                            'l_orgname' => $row['l_orgname'] ?? 0,
                            'l_ordchno' => $row['l_ordchno'] ?? 0,
                            // 'create_modify' => date("Y-m-d H:i:s"),
                            // 'updated_on' => date("Y-m-d H:i:s"),
                            // 'updated_by' => session()->get('login')['usercode'],
                            // 'updated_by_ip' => getClientIP()
                        ];
                        $getTransferetails = $this->EarliercourtModel->insertTransferDetails($insertTransferDetails);
                    }
                }
                $all_relied_details = is_data_from_table('relied_details', ['lowerct_id' => $lowerct_id], '*');
                if(!empty($all_relied_details)) {
                    foreach ($all_relied_details as $row_relied) {
                        $insertReliedDetails = [
                            'display' => 'Y',
                            'lowerct_id' => $insertedEarlierCourtId,
                            'relied_court' => $row_relied['relied_court'],
                            'relied_case_type' => $row_relied['relied_case_type'],
                            'relied_case_no' => $row_relied['relied_case_no'],
                            'relied_case_year' => $row_relied['relied_case_year'],
                            'relied_state' => $row_relied['relied_state'],
                            'relied_district' => $row_relied['relied_district'],
                            // 'l_inddep' =>$row['l_org'] ?? '0',
                            // 'l_org' => $row['l_org'] ?? 0,
                            // 'l_orgname' => $row['l_orgname'] ?? 0,
                            // 'l_ordchno' => $row['l_ordchno'] ?? 0,
                            // 'create_modify' => date("Y-m-d H:i:s"),
                            // // 'updated_on' => date("Y-m-d H:i:s"),
                            // 'updated_by' => session()->get('login')['usercode'],
                            // 'updated_by_ip' => getClientIP()
                        ];

                        $getReliedDetails = $this->EarliercourtModel->insertReliedDetails($insertReliedDetails);

                    }
                }

                //Get Judges Details
                $all_judge_data = is_data_from_table('lowerct_judges', ['lowerct_id' => $lowerct_id], '*');
                if(!empty($all_judge_data)) {
                    foreach ($all_judge_data as $row_judge) {
                        $insertJudgeDetails = [
                            'lowerct_id' => $insertedEarlierCourtId,
                            'judge_id' => $row_judge['judge_id'],
                            'lct_display' => $row_judge['lct_display'],
                            // 'create_modify' => date("Y-m-d H:i:s"),
                            // 'updated_on' => date("Y-m-d H:i:s"),
                            // 'updated_by' => session()->get('login')['usercode'],
                            // 'updated_by_ip' => getClientIP()
                        ];
                        $getJudgeDetails = $this->EarliercourtModel->insertJudgeDetails($insertJudgeDetails);
                    }
                }

            }
            $html = '<div class="col-3"></div>';
            $html .= '<div class="col-6"><center><font style="text-align: center;font-size: 20px;color: green"><br><b>Records Found and Copied Successfully</b></font></center>';
            $html .= "</div>";
            $html .= '<div class="col-3"></div>';
            echo $html;

        }

    }

}
