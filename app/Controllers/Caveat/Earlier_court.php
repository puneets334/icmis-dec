<?php

namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Caveat\EarliercourtModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_Caveat;
use App\Models\Entities\Model_CaveatA;
use App\Models\Entities\Model_CaveatLowerct;
use App\Models\Entities\Model_CaveatLowerctA;

class Earlier_court extends BaseController
{
    public $EarliercourtModel;
    public $Dropdown_list_model;
    public $Model_caveat_lowerct;
    public $Model_caveat_lowerct_a;
    public $Model_caveat;
    public $Model_caveat_a;
    function __construct()
    {
        if (isset($_SESSION['login'])) {
            if (!isset($_SESSION['caveat_details'])) { header('Location:'.base_url('Caveat/Search'));exit(); }
        }else{  header('Location:'.base_url('Signout'));exit(); }
        $this->EarliercourtModel = new EarliercourtModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Model_caveat_lowerct = new Model_CaveatLowerct();
        $this->Model_caveat_lowerct_a = new Model_CaveatLowerctA();
        $this->Model_caveat = new Model_Caveat();
        $this->Model_caveat_a = new Model_CaveatA();
        ini_set('memory_limit','4024M');
    }
    public function index()
    {
        $data['action_status']='Y';
        $caveat_no = $_SESSION['caveat_details']['caveat_no'];
        $lower_court_details=array();
        $data['is_c_status']='P';
        $data_from_main_caveat = $this->EarliercourtModel->get_caveat_details($caveat_no);
        if (empty($data_from_main_caveat)){
            $data['is_c_status']='D';
            $data['action_status']='N';
            $data_from_main_caveat = $this->EarliercourtModel->get_caveat_details($caveat_no,'_a');
        }
        if (!empty($data_from_main_caveat)) {

            $data['caveat_main'] = $data_from_main_caveat;
            $data['ct_code'] = $data_from_main_caveat['from_court'];
            $data['l_state'] =$data_from_main_caveat['ref_agency_state_id'];
            $data['l_dist'] = $data_from_main_caveat['ref_agency_code_id'];
            $data['lct_casetype'] = $data_from_main_caveat['casetype_id'];
            $data['lct_dec_dt'] = '';


            $data['cnr_no'] = '';

            $data['lct_judge_desg'] = '';
            $data['polstncode'] = '';
            $data['crimeno'] = '';
            $data['crimeyear'] = '';
            $data['sub_law'] = '';
            //$data['lct_casetype'] = '';
            $data['lct_caseno'] = '';
            $data['lct_caseyear'] = '';
            $data['lc_judges'] =array();
            $str='';

            if($str=='' && $data_from_main_caveat['from_court']==3) {
                $str = 'rd_dc';
                //$ct=3;
            }
            else if($str=='' && $data_from_main_caveat['from_court']==1) {
                $str = 'rd_hc';
                // $ct=1;
            }
            else if($str=='' && $data_from_main_caveat['from_court']==5) {
                $str = 'rd_sa';
                //$ct=5;
            }
            else if($str=='' && $data_from_main_caveat['from_court']==4) {
                $str = 'rd_sc';
                //$ct=4;

            }
            $data['str'] = $str;

            $data['case_all_types'] = $this->Dropdown_list_model->get_lc_hc_casetype($data['l_state'],$data['ct_code']);
            $data['m_ljuddesc'] = $this->Dropdown_list_model->get_post_t();
            //echo '<pre>';print_r($data['m_ljuddesc']);exit();

            $data['police_station_list'] = $this->Dropdown_list_model->get_police_station_list($data['l_state'], $data['l_dist']);

            $data['lcc_id'] = null;
            $data['judges_list'] = $this->Dropdown_list_model->get_all_judges($data['l_state'], $data['ct_code']);
            $data['lc_judges'] = $this->EarliercourtModel->getJudgesDetailsofLowerCourt($data['lcc_id']);
            $result = $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no);
            if (empty($result)) {
                $data['result'] = $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no, '_a');
            } else {
                $data['result'] = $result;
            }
            if(!empty($data['result'])){
                if (!empty($data['result'][0]['lct_dec_dt'])){
                    //$date = date_create($data['result'][0]['lct_dec_dt']);
                    $data['lct_dec_dt'] =date("d-m-Y", strtotime($data['result'][0]['lct_dec_dt']));
                }

            }


            $data['states'] = $this->Dropdown_list_model->icmis_states($data['l_state']);

            $data['judges_details'] = $this->EarliercourtModel->getJudgeDetailsByDiary($caveat_no);
        }
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['diary_details'] = $_SESSION['caveat_details'];

        //echo '<pre>';print_r($data);exit();
        //return view('Caveat/earlier_court_view', $data);
        //echo '<pre>';print_r($data['caveat_main']);exit();
        return view('Caveat/earlier_court_add_view', $data);
    }
    public function save()
    {
        $errorResponse = "";
        $caveat_no = $_SESSION['caveat_details']['caveat_no'];
        $data['result'] = $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no);
        $data['states'] = $this->Dropdown_list_model->icmis_states();

        $data['m_ljuddesc'] = get_from_table_json('post_t', 'Y', 'display');

        $court_type = $this->request->getPost('radio_selected_court');

        $rule = array(
            //'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
        );


        if ($court_type == 1) {
            $high_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_h' => ['label' => 'State', 'rules' => 'required'],
                'h_bench_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_1' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($high_court_rules, $rule);
        } elseif ($court_type == 4) {
            $supreme_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency' => ['label' => 'State', 'rules' => 'required'],
                'district_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_s' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($supreme_court_rules, $rule);
        } elseif ($court_type == 3) {
            $district_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_d' => ['label' => 'State', 'rules' => 'required'],
                'district_idd' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type_d' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_5' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
            );
            $rule = array_merge($district_court_rules, $rule);
        } else {
            $state_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_s' => ['label' => 'State', 'rules' => 'required'],
                'district_ids' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_2' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($state_rules, $rule);
        }


        if ($this->request->getMethod() == 'post') {
            $controller=$_REQUEST['controller'];
            $caveat_no = $_SESSION['caveat_details']['caveat_no'];
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
                //$judges = $this->request->getPost('judge_name_5');
                $judges=array();
            }

            if ($court_type == 3) {
                // $order_description =  $this->request->getPost('order_description_d');
                // $subject_law =  $this->request->getPost('lc_subject_law_3');
            } else {
                //$order_description =  $this->request->getPost('order_description');
                //$subject_law =  $this->request->getPost('lc_subject_law');
            }

            $m_policestn = intval($this->request->getPost('m_policestn')) > 0 ? $this->request->getPost('m_policestn') : '0';
            $crimeno = $this->request->getPost('crimeno') ? $this->request->getPost('crimeno') : null;

            if ($m_policestn > 0) {
                $crimeyear = $this->request->getPost('crimeyear');
            } else {
                $crimeyear = 0;
            }


            $lct_dec_dt = date_create($lct_dec_dt);
            $lct_dec_dt  = date_format($lct_dec_dt, "Y-m-d");


            $usercode = session()->get('login')['usercode'];

                if (!empty($lc_case_no_to)) {
                    $lc_case_no_end = $lc_case_no_to;
                } else {
                    $lc_case_no_end = $lc_case_no;
                }

                for ($l_case_no = $lc_case_no; $l_case_no <= $lc_case_no_end; $l_case_no++) {

                    $EarlierCourtArray = array('state_agency' => $state_agency, 'district_id' => $district_id, 'case_type' => $case_type, 'lc_case_no' => $l_case_no, 'lc_case_year' => $lc_case_year, 'lct_dec_dt' => $lct_dec_dt, 'm_policestn' => $m_policestn, 'crimeno' => $crimeno, 'crimeyear' => $crimeyear, 'caveat_no' => $caveat_no, 'c_type' => $court_type);

                    $is_already_added = $this->EarliercourtModel->getAlreadyAddeLowerCourtDetails($EarlierCourtArray);

                    //$is_already_added = "";
                    $cnr_no = !empty($this->request->getPost('cnr_no')) ? $this->request->getPost('cnr_no') : " ";

                    if (empty($is_already_added)) {
                        $empty_space=' ';$zero=0;
                        $is_lower_court_id = $this->Model_caveat_lowerct->select('lower_court_id')->orderBy('lower_court_id', 'DESC')->get()->getRowArray();
                        $auto_lower_court_id=$is_lower_court_id['lower_court_id'];
                        $lower_court_id=$auto_lower_court_id + 1;
                        $insertEarlierCourtArray = [
                            // 'lower_court_id' => $lower_court_id,
                            'lct_dec_dt' => $lct_dec_dt,
                            'l_dist' => $district_id,
                            'usercode' => $usercode,
                            'ct_code' => $court_type,
                            'ent_dt' =>  date('Y-m-d H:i:s'),
                            'caveat_no' => $caveat_no,
                            'l_state' => $state_agency,
                            'lw_display' => 'Y',
                            'lct_judge_desg' => !empty($this->request->getPost('m_ljuddesc')) ? $this->request->getPost('m_ljuddesc') : NULL,
                            'polstncode' => $m_policestn,
                            'crimeno' => $crimeno,
                            'crimeyear' => $crimeyear,
                            'lct_casetype' =>  $case_type,
                            'lct_caseno' =>  $l_case_no,
                            'lct_caseyear' =>  $lc_case_year,

                            'cnr_no' => !empty($this->request->getPost('filing_no')) ? $this->request->getPost('filing_no') : " ",


                            'l_inddep' => $empty_space,
                            'l_iopb' => null,
                            'l_iopbn' => null,
                            'l_org' => $zero,
                            'l_orgname' => $empty_space,
                            'l_ordchno' =>$empty_space,
                            'lct_jud_id' =>$empty_space,
                            'lct_jud_id1' => $zero,
                            'lct_jud_id2' => $zero,
                            'lct_jud_id3' => $zero,
                            'lctjudname3' => $empty_space,
                            'hjs_cnr' => $empty_space,
                            'ljs_cnr' => $empty_space,
                            'l_state_old' => $zero,
                            'is_order_challenged' =>$empty_space,
                            'full_interim_flag' =>$empty_space,
                            'judgement_covered_in' =>$empty_space,
                            'vehicle_code' =>$zero,
                            'vehicle_no' =>$empty_space,
                            'ref_court' =>$zero,
                            'ref_case_type' =>$zero,
                            'ref_case_no' =>$zero,
                            'ref_case_year' =>$zero,
                            'ref_state' =>$zero,
                            'ref_district' =>$zero,
                            'gov_not_state_id' =>$zero,
                            'gov_not_case_type' =>null,
                            'gov_not_case_no' =>$zero,
                            'gov_not_case_year' =>$zero,

                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];
                        //echo '<pre>';print_r($insertEarlierCourtArray);exit();
                        $this->db = \Config\Database::connect();
                        $this->db->transStart();
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

                        }
                        $errorResponse = 0;
                    }else{

                        $errorResponse = 1;
                    }

                }
                if($errorResponse == 1){
                    session()->setFlashdata("message_error", 'Earlier Court Details already existed.');
                    $this->response->redirect(site_url('/Caveat/Earlier_court'));
                }else{
                    session()->setFlashdata("success_msg", 'Earlier Court Detatils inserted successfully.');
                    $this->response->redirect(site_url('/Caveat/Earlier_court'));
                }
                $this->db->transComplete();


        } else {
            return view('Caveat/earlier_court_view', $data);
        }
    }






    public function update($id = null)
    {
        $data['action_status']='Y';
        $caveat_no = $_SESSION['caveat_details']['caveat_no'];
       $lower_court_details=array();
        if (isset($id)) {
            $lower_court_details = $this->Model_caveat_lowerct->select('*')->where(['lower_court_id'=>$id])->get()->getResultArray();
            if (empty($lower_court_details)){
                $lower_court_details = $this->Model_caveat_lowerct_a->select('*')->where(['lower_court_id'=>$id])->get()->getResultArray();
                $data['action_status']='N';
            }

        } else {
            $is_caveat_lowerct = $this->Model_caveat_lowerct->select('lower_court_id,l_dist,ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear,lct_dec_dt')->where(['caveat_no'=>$caveat_no])->orderBy('lower_court_id', 'DESC')->get()->getResultArray();
            if (empty($is_caveat_lowerct)){
                $is_caveat_lowerct = $this->Model_caveat_lowerct_a->select('lower_court_id,l_dist,ct_code,l_state,lct_casetype,lct_caseno,lct_caseyear,lct_dec_dt')->where(['caveat_no'=>$caveat_no])->orderBy('lower_court_id', 'DESC')->get()->getResultArray();
                $data['action_status']='N';
                if (!empty($is_caveat_lowerct)){
                    $id=$is_caveat_lowerct['lower_court_id'];
                    $lower_court_details = $this->Model_caveat_lowerct_a->select('*')->where(['lower_court_id'=>$id])->get()->getResultArray();
                }
            }else{
                if (!empty($is_caveat_lowerct)){
                    $id=$is_caveat_lowerct['lower_court_id'];
                    $lower_court_details = $this->Model_caveat_lowerct->select('*')->where(['lower_court_id'=>$id])->get()->getResultArray();
                }
            }



        }
     //echo '<pre>';print_r($lower_court_details);exit();
        $data['ct_code'] = $_SESSION['caveat_details']['from_court'];

        $data['lower_court_details']=$lower_court_details;

        $data['ct_code'] = $_SESSION['caveat_details']['from_court'];
        $data['l_state'] = $data['lower_court_details'][0]['l_state'];
        $data['l_dist'] = $data['lower_court_details'][0]['l_dist'];

        $data['lct_dec_dt'] = $data['lower_court_details'][0]['lct_dec_dt'];
        if (!empty($data['lct_dec_dt'])){
            //$date = date_create($data['lct_dec_dt']);
            $data['lct_dec_dt'] =date("d-m-Y", strtotime($data['lct_dec_dt']));
        }else{
            $data['lct_dec_dt'] = '';
        }


        $data['lct_judge_desg'] = $data['lower_court_details'][0]['lct_judge_desg'];
        $data['polstncode'] = $data['lower_court_details'][0]['polstncode'];
        $data['crimeno'] = $data['lower_court_details'][0]['crimeno'];
        $data['crimeyear'] = $data['lower_court_details'][0]['crimeyear'];
        $data['sub_law'] = $data['lower_court_details'][0]['sub_law'];
        $data['lct_casetype'] = $data['lower_court_details'][0]['lct_casetype'];
        $data['lct_caseno'] = $data['lower_court_details'][0]['lct_caseno'];
        $data['lct_caseyear'] = $data['lower_court_details'][0]['lct_caseyear'];


        $data['cnr_no'] = $data['lower_court_details'][0]['cnr_no'];

        $caseTypes = $this->Dropdown_list_model->get_case_type_court($data['l_state'], $data['ct_code']);
        $data['police_station_list'] = $this->Dropdown_list_model->get_police_station_list($data['l_state'], $data['l_dist']);
        $data['case_all_types'] = $caseTypes;
        $data['lcc_id'] =  $id;
        $data['judges_list'] = $this->Dropdown_list_model->get_all_judges($data['l_state'], $data['ct_code']);
        $data['lc_judges'] = $this->EarliercourtModel->getJudgesDetailsofLowerCourt($data['lcc_id']);

        $id_no=$data['l_state'];
        $result = $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no);
        if (empty($result)){
            $data['result']= $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no,'_a');
        }else{
            $data['result'] =$result;
        }

        $data['states'] = $this->Dropdown_list_model->icmis_states($id_no);

        $data['judges_details'] = $this->EarliercourtModel->getJudgeDetailsByDiary($caveat_no);
        $data['court_type_list'] = $this->Dropdown_list_model->get_court_type_list();
        $data['diary_details'] = $_SESSION['caveat_details'];

        $data['m_ljuddesc'] = get_from_table_json('post_t', 'Y', 'display');

        return view('Caveat/earlier_court_view', $data);
    }

    public function insertEarlierCourt()
    {
        $errorResponse = "";
        $caveat_no = $_SESSION['caveat_details']['caveat_no'];
        $data['result'] = $this->EarliercourtModel->getAllLowerCourtDetails($caveat_no);
        $data['states'] = $this->Dropdown_list_model->icmis_states();

        $data['m_ljuddesc'] = get_from_table_json('post_t', 'Y', 'display');

        $court_type = $this->request->getPost('radio_selected_court');

        $rule = array(
            //'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
        );


        if ($court_type == 1) {
            $high_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_h' => ['label' => 'State', 'rules' => 'required'],
                'h_bench_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_1' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($high_court_rules, $rule);
        } elseif ($court_type == 4) {
            $supreme_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency' => ['label' => 'State', 'rules' => 'required'],
                'district_id' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_s' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($supreme_court_rules, $rule);
        } elseif ($court_type == 3) {
            $district_court_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_d' => ['label' => 'State', 'rules' => 'required'],
                'district_idd' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type_d' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_5' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
            );
            $rule = array_merge($district_court_rules, $rule);
        } else {
            $state_rules = array(
                'controller' => ['controller' => 'Controller', 'rules' => 'required'],
                'state_agency_s' => ['label' => 'State', 'rules' => 'required'],
                'district_ids' => ['label' => 'Bench ', 'rules' => 'required'],
                'case_type' => ['label' => 'Case Type', 'rules' => 'required'],
                'impugned_date_2' => ['label' => 'Date of Impugned Judgement/ Order/ Award/ Notification/ Circular', 'rules' => 'required|valid_date'],
                'lc_case_no_from' => ['label' => 'Case Number', 'rules' => 'required'],
            );
            $rule = array_merge($state_rules, $rule);
        }


        if ($this->request->getMethod() == 'post') {
            $controller=$_REQUEST['controller'];
            $caveat_no = $_SESSION['caveat_details']['caveat_no'];
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
                //$judges = $this->request->getPost('judge_name_5');
            }

            if ($court_type == 3) {
                // $order_description =  $this->request->getPost('order_description_d');
                // $subject_law =  $this->request->getPost('lc_subject_law_3');
            } else {
                //$order_description =  $this->request->getPost('order_description');
                //$subject_law =  $this->request->getPost('lc_subject_law');
            }
            
            $m_policestn = intval($this->request->getPost('m_policestn')) > 0 ? $this->request->getPost('m_policestn') : '0';
            $crimeno = $this->request->getPost('crimeno') ? $this->request->getPost('crimeno') : null;

            if ($m_policestn > 0) {
                $crimeyear = $this->request->getPost('crimeyear');
            } else {
                $crimeyear = 0;
            }


            $lct_dec_dt = date_create($lct_dec_dt);
            $lct_dec_dt  = date_format($lct_dec_dt, "Y-m-d");


            $userid = session()->get('login')['empid'];
            if($_REQUEST['controller']=='U') {
                //update


                $lower_court_id=$this->request->getPost('lc_idd');
                $updateEarlierCourtArray = [
                    'lct_dec_dt' => $lct_dec_dt,
                    'l_dist' => $district_id,
                    'usercode' => $userid,
                    'ct_code' => $court_type,
                    'ent_dt' =>  date('Y-m-d H:i:s'),
                    'caveat_no' => $caveat_no,
                    'l_state' => $state_agency,
                    'lw_display' => 'Y',
                    'lct_judge_desg' => !empty($this->request->getPost('m_ljuddesc')) ? $this->request->getPost('m_ljuddesc') : NULL,
                    'polstncode' => $m_policestn,
                    'crimeno' => $crimeno,
                    'crimeyear' => $crimeyear,
                    'lct_casetype' =>  $case_type,
                    'lct_caseno' =>  $lc_case_no,
                    'lct_caseyear' =>  $lc_case_year,
                    'cnr_no' => !empty($this->request->getPost('filing_no')) ? $this->request->getPost('filing_no') : " ",


                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                ];
                //pr($updateEarlierCourtArray);
                 $is_caveat_lowerct=update('caveat_lowerct',$updateEarlierCourtArray,['lower_court_id'=>$lower_court_id,'lw_display'=>'Y']);
                $this->db = \Config\Database::connect();
                $this->db->transStart();
                $EarlierCourtId = $this->EarliercourtModel->updateEarlierCourt($updateEarlierCourtArray,$lower_court_id);
                $deleteJudge = $this->EarliercourtModel->deleteJudgesDetails($this->request->getPost('lc_idd'));

                if (is_array($judges)) {
                    foreach ($judges as $judge_id) {
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

                $this->db->transComplete();

                session()->setFlashdata("success_msg", 'Earlier Court Detatils updated successfully.');
                return redirect()->to('/Caveat/Earlier_court/index/'.$this->request->getPost('lc_idd'));
            } else {
                if (!empty($lc_case_no_to)) {
                    $lc_case_no_end = $lc_case_no_to;
                } else {
                    $lc_case_no_end = $lc_case_no;
                }

                for ($l_case_no = $lc_case_no; $l_case_no <= $lc_case_no_end; $l_case_no++) {

                    $EarlierCourtArray = array('state_agency' => $state_agency, 'district_id' => $district_id, 'case_type' => $case_type, 'lc_case_no' => $l_case_no, 'lc_case_year' => $lc_case_year, 'lct_dec_dt' => $lct_dec_dt, 'm_policestn' => $m_policestn, 'crimeno' => $crimeno, 'crimeyear' => $crimeyear, 'caveat_no' => $caveat_no, 'c_type' => $court_type);

                    $is_already_added = $this->EarliercourtModel->getAlreadyAddeLowerCourtDetails($EarlierCourtArray);

                    //$is_already_added = "";
                    $cnr_no = !empty($this->request->getPost('cnr_no')) ? $this->request->getPost('cnr_no') : " ";

                    if (empty($is_already_added)) {
                        $empty_space=' ';$zero=0;
                        $is_lower_court_id = $this->Model_caveat_lowerct->select('lower_court_id')->orderBy('lower_court_id', 'DESC')->get()->getRowArray();
                        $auto_lower_court_id=$is_lower_court_id['lower_court_id'];
                        $lower_court_id=$auto_lower_court_id + 1;
                        $insertEarlierCourtArray = [
                           // 'lower_court_id' => $lower_court_id,
                            'lct_dec_dt' => $lct_dec_dt,
                            'l_dist' => $district_id,
                            'usercode' => $userid,
                            'ct_code' => $court_type,
                            'ent_dt' =>  date('Y-m-d H:i:s'),
                            'caveat_no' => $caveat_no,
                            'l_state' => $state_agency,
                            'lw_display' => 'Y',
                            'lct_judge_desg' => !empty($this->request->getPost('m_ljuddesc')) ? $this->request->getPost('m_ljuddesc') : NULL,
                            'polstncode' => $m_policestn,
                            'crimeno' => $crimeno,
                            'crimeyear' => $crimeyear,
                            'lct_casetype' =>  $case_type,
                            'lct_caseno' =>  $l_case_no,
                            'lct_caseyear' =>  $lc_case_year,

                            'cnr_no' => !empty($this->request->getPost('filing_no')) ? $this->request->getPost('filing_no') : " ",


                            'l_inddep' => $empty_space,
                            'l_iopb' => null,
                            'l_iopbn' => null,
                            'l_org' => $zero,
                            'l_orgname' => $empty_space,
                            'l_ordchno' =>$empty_space,
                            'lct_jud_id' =>$empty_space,
                            'lct_jud_id1' => $zero,
                            'lct_jud_id2' => $zero,
                            'lct_jud_id3' => $zero,
                            'lctjudname3' => $empty_space,
                            'hjs_cnr' => $empty_space,
                            'ljs_cnr' => $empty_space,
                            'l_state_old' => $zero,
                            'is_order_challenged' =>$empty_space,
                            'full_interim_flag' =>$empty_space,
                            'judgement_covered_in' =>$empty_space,
                            'vehicle_code' =>$zero,
                            'vehicle_no' =>$empty_space,
                            'ref_court' =>$zero,
                            'ref_case_type' =>$zero,
                            'ref_case_no' =>$zero,
                            'ref_case_year' =>$zero,
                            'ref_state' =>$zero,
                            'ref_district' =>$zero,
                            'gov_not_state_id' =>$zero,
                            'gov_not_case_type' =>null,
                            'gov_not_case_no' =>$zero,
                            'gov_not_case_year' =>$zero,

                            'create_modify' => date("Y-m-d H:i:s"),
                            'updated_on' => date("Y-m-d H:i:s"),
                            'updated_by' => session()->get('login')['usercode'],
                            'updated_by_ip' => getClientIP(),
                        ];
                        $this->db = \Config\Database::connect();
                        $this->db->transStart();
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

                        }
                        $errorResponse = 0;
                    }else{

                        $errorResponse = 1;
                    }

                }
                if($errorResponse == 1){
                    session()->setFlashdata("message_error", 'Earlier Court Details already existed.');
                    $this->response->redirect(site_url('/Caveat/Earlier_court'));
                }else{
                    session()->setFlashdata("success_msg", 'Earlier Court Detatils inserted successfully.');
                    $this->response->redirect(site_url('/Caveat/Earlier_court'));
                }
                $this->db->transComplete();

            }
            // }
        } else {
            return view('Caveat/earlier_court_view', $data);
        }
    }


    public function deleteEarliercourt()
    {

        $lowerCourt_id = $this->request->getPost('lower_court_id');
        $caveat_no = $this->request->getPost('caveat_no');
        $lc_cno = $this->request->getPost('lc_cno');
        $lc_cyear = $this->request->getPost('lc_cyear');
        $lc_oc = $this->request->getPost('lc_oc');
        $response = "";
        if ($lc_oc == 'Y') {
            $caveatDetails = $this->EarliercourtModel->getCaveatDetails($caveat_no, $lc_cno, $lc_cyear);
        }
        if (empty($caveatDetails) || $lc_oc != 'Y') {
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $deleteT = $this->EarliercourtModel->deleteTransferDetails($lowerCourt_id);
            $deleteRelied = $this->EarliercourtModel->deleteReliedDetails($lowerCourt_id);
            $deleteJudge = $this->EarliercourtModel->deleteJudgesDetails($lowerCourt_id);
            $deleteLowerCourt = $this->EarliercourtModel->deleteLowerCourtDetails($lowerCourt_id);
            $this->db->transComplete();
            $response = "Y";
        } else {
            $caveatDetailNumber = $this->EarliercourtModel->getCaveatDetailsNumber($caveat_no, $lc_cno, $lc_cyear);
            $caveatDetailNo = $caveatDetailNumber[0]['string_agg'];
            $response = " Caveat No " . $caveatDetailNo . " is associated with this matter , hence you cannot delete this case Details.";
        }

        return $response;

        //

    }


    public function checkforUpdateEarliercourt()
    {
        $lowerCourt_id = $this->request->getPost('lower_court_id');
        $caveat_no = $this->request->getPost('caveat_no');
        $lc_cno = $this->request->getPost('lc_cno');
        $lc_cyear = $this->request->getPost('lc_cyear');
        $lc_oc = $this->request->getPost('lc_oc');
        $response = "";
        if ($lc_oc == 'Y') {
            $caveatDetails = $this->EarliercourtModel->getCaveatDetails($caveat_no, $lc_cno, $lc_cyear);
        }
        if (empty($caveatDetails) || $lc_oc != 'Y') {
            $response = "Y";
        } else {
            $caveatDetailNumber = $this->EarliercourtModel->getCaveatDetailsNumber($caveat_no, $lc_cno, $lc_cyear);
            $caveatDetailNo = $caveatDetailNumber[0]['string_agg'];
            $response = "Caveat No " . $caveatDetailNo . " is associated with this matter , hence you cannot modify this case Details.";
        }

        return $response;

        //

    }


}
