<?php

namespace CodeIgniter\Validation;

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\SimilarityModel;
use App\Models\Common\Dropdown_list_model;

class Similarity extends BaseController
{
    public $SimilarityModel;
    public $LoginModel;
    public $Dropdown_list_model;
    protected $diary_no;
    function __construct()
    {
        $this->SimilarityModel = new SimilarityModel();
        $this->Dropdown_list_model = new Dropdown_list_model();
        
        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);             
            $getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:' . base_url('Filing/Diary/search?page_url=' . base64_encode($getUrl)));
            exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }


    public function index()
    {
        $data['param']=array();       
        return view('Filing/similarity_view',$data);
    }

    public function viewSimilarity($without_header='without_header')
    {
        $data_array_a = array();
        $data_array = array();
        

        $diary_no = $_REQUEST['diary_number'].$_REQUEST['diary_year'];         
        $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
        $data['current_filing_detail'] = $get_main_table;
        if (!empty($get_main_table)) 
            {
                
                $data['m_diary_no'] = $diary_no;

                $main_details = is_data_from_table('main', ['diary_no' => $diary_no], 'pet_name, res_name','R');
                if(empty($main_details)){
                    $main_details = is_data_from_table('main_a', ['diary_no' => $diary_no], 'pet_name, res_name','R');
                }
                if(!empty($main_details))
                {
                    $pet_name = trim($main_details['pet_name']);
                    $res_name = trim($main_details['res_name']);
                    
                    $data['pending_cause_title'] = $this->SimilarityModel->getCauseTitleSimilary($diary_no,$pet_name,$res_name,"");
                    $data['disposed_cause_title'] = $this->SimilarityModel->getCauseTitleSimilary($diary_no,$pet_name,$res_name,"_a");
                }

        


                $lower_ct_details = $this->SimilarityModel->getLowerCtDetails($diary_no,'');

                if (empty($lower_ct_details)) {
                    $lower_ct_details = $this->SimilarityModel->getLowerCtDetails($diary_no, '_a');
                }

                if (!empty($lower_ct_details)) {
                    foreach ($lower_ct_details as $row_key => $row) {
                        $lct_dec_dt = $row['lct_dec_dt'];
                        $l_dist = $row['l_dist'];
                        $polstncode = $row['polstncode'];

                        if (!empty($row['crimeno']) && substr($row['crimeno'], 0, 1) == '0') {
                            $crimeno = substr($row['crimeno'], 1);
                        } else {
                            $crimeno = $row['crimeno'];
                        }
                        $crimeyear = $row['crimeyear'];
                        $usercode = $row['usercode'];
                        $ct_code = $row['ct_code'];
                        $l_state = $row['l_state'];
                        $lct_casetype = $row['lct_casetype'];
                        if (!empty($row['lct_caseno']) && substr($row['lct_caseno'], 0, 1) == '0') {
                            $lct_caseno = substr($row['lct_caseno'], 1);
                        } else {
                            $lct_caseno = $row['lct_caseno'];
                        }
                        // $lct_caseno = $row['lct_caseno'];
                        $lct_caseyear = $row['lct_caseyear'];
                        $is_order_challenged = $row['is_order_challenged'];
                        $full_interim_flag = $row['full_interim_flag'];
                        $vehicle_code = $row['vehicle_code'];
                        $vehicle_no = $row['vehicle_no'];
                        $cnr_no = $row['cnr_no'];
                        $ref_court = $row['ref_court'];
                        $ref_case_type = $row['ref_case_type'];
                        $ref_case_no = $row['ref_case_no'];
                        $ref_case_year = $row['ref_case_year'];
                        $ref_state = $row['ref_state'];
                        $ref_district = $row['ref_district'];
                        $gov_not_state_id = $row['gov_not_state_id'];
                        $gov_not_case_type = $row['gov_not_case_type'];
                        $gov_not_case_no = $row['gov_not_case_no'];
                        $gov_not_case_year = $row['gov_not_case_year'];
                        $gov_not_date = $row['gov_not_date'];
                        $d_no = $row['diary_no'];
                        $lw_display = $row['lw_display'];


                        $relied_court = $row['relied_court'];
                        $relied_case_type = $row['relied_case_type'];
                        $relied_case_no = $row['relied_case_no'];
                        $relied_case_year = $row['relied_case_year'];
                        $relied_state = $row['relied_state'];
                        $relied_district = $row['relied_district'];

                        $transfer_court = $row['transfer_court'];
                        $transfer_case_type = $row['transfer_case_type'];
                        $transfer_case_no = $row['transfer_case_no'];
                        $transfer_case_year = $row['transfer_case_year'];
                        $transfer_state = $row['transfer_state'];
                        $transfer_district = $row['transfer_district'];

                        $data['relied_details_pending'] =   $data['relied_details_disposed'] = $data['transfer_details_disposed'] = $data['transfer_details_pending']= "";


                        if ($lw_display == 'Y') {
                            /* Now compare data with both tables lowerct and lowercta for -- Similarities based on State, Bench, Case No. and Judgement Date Start*/
                            $all_matched_cases = is_data_from_table('lowerct', ['lct_dec_dt' => $lct_dec_dt, 'lct_caseno' => $lct_caseno, 'l_dist' => $l_dist, 'l_state' => $l_state, 'lct_caseyear' => $lct_caseyear, 'ct_code' => $ct_code, 'diary_no!=' => $d_no, 'lct_dec_dt is not' => null], 'lower_court_id');
                            // echo '<pre>';
                            //print_r($all_matched_cases);
                            if (!empty($all_matched_cases)) {
                                foreach ($all_matched_cases as $all_row) {
                                    $data_array[] = $all_row['lower_court_id'];
                                }
                            }

                            $all_matched_cases_a = is_data_from_table('lowerct_a', ['lct_dec_dt' => $lct_dec_dt, 'lct_caseno' => $lct_caseno, 'l_dist' => $l_dist, 'l_state' => $l_state, 'lct_caseyear' => $lct_caseyear, 'ct_code' => $ct_code, 'diary_no!=' => $d_no, 'lct_dec_dt is not' => null], 'lower_court_id');
                            if (!empty($all_matched_cases_a)) {
                                foreach ($all_matched_cases_a as $all_row_a) {
                                    $data_array_a[] = $all_row_a['lower_court_id'];
                                }
                            }

                            /* Now compare data with both tables lowerct and lowercta for -- Similarities based on State, Bench, Case No. and Judgement Date Start*/

                            /* Now compare data with both tables lowerct and lowercta for -- Similarities based on Court, State, District and Reference No. Start*/

                            if ($ref_court != 0 && $ref_case_type != 0) {
                                $all_matched_cases_4 = is_data_from_table('lowerct', ['ct_code' => $ref_court, 'lct_caseno' => $ref_case_no, 'lct_caseyear' => $ref_case_year, 'l_state' => $ref_state, 'l_dist' => $ref_district, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                            //  $all_matched_cases_4 = is_data_from_table('lowerct', ['ct_code' => $ref_court, 'cast(lct_caseno as integer)' => $ref_case_no, 'lct_caseyear' => $ref_case_year, 'l_state' => $ref_state, 'l_dist' => $ref_district, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                // echo '<pre>';
                                //print_r($all_matched_cases);
                                if (!empty($all_matched_cases_4)) {
                                    foreach ($all_matched_cases_4 as $all_row4) {
                                        $data_array4[] = $all_row4['lower_court_id'];
                                    }
                                }

                                $all_matched_cases_4a = is_data_from_table('lowerct_a', ['ct_code' => $ref_court, 'lct_caseno' => $ref_case_no, 'lct_caseyear' => $ref_case_year, 'l_state' => $ref_state, 'l_dist' => $ref_district, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                //$all_matched_cases_4a = is_data_from_table('lowerct_a', ['ct_code' => $ref_court, 'cast(lct_caseno as integer)' => $ref_case_no, 'lct_caseyear' => $ref_case_year, 'l_state' => $ref_state, 'l_dist' => $ref_district, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_4a)) {
                                    foreach ($all_matched_cases_4a as $all_row_4a) {
                                        $data_array_4a[] = $all_row_4a['lower_court_id'];
                                    }
                                }
                            }

                            /* Now compare data with both tables lowerct and lowercta for -- Similarities based on Court, State, District and Reference No. end*/

                            if ($polstncode != 0 && $crimeno != '0' && $crimeyear != 0) {
                                $all_matched_cases_2 = is_data_from_table('lowerct', ['polstncode' => $polstncode, 'crimeno' => $crimeno, 'crimeyear'=>$crimeyear,'l_dist' => $l_dist, 'l_state' => $l_state, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_2)) {
                                    foreach ($all_matched_cases_2 as $all_row2) {
                                        $data_array2[] = $all_row2['lower_court_id'];
                                    }
                                }

                                $all_matched_cases_2a = is_data_from_table('lowerct_a', ['polstncode' => $polstncode, 'crimeno' => $crimeno, 'crimeyear'=>$crimeyear,'l_dist' => $l_dist, 'l_state' => $l_state, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_2a)) {
                                    foreach ($all_matched_cases_2a as $all_row2a) {
                                        $data_array2a[] = $all_row2a['lower_court_id'];
                                    }
                                }

        
                            }

                            if($vehicle_code != 0){     
                                $all_matched_cases_3 = is_data_from_table('lowerct', ['vehicle_code' => $vehicle_code, 'vehicle_no' => $vehicle_no, 'l_state' => $l_state, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_3)) {
                                    foreach ($all_matched_cases_3 as $all_row3) {
                                        $data_array3[] = $all_row3['lower_court_id'];
                                    }
                                }


                                $all_matched_cases_3a = is_data_from_table('lowerct_a', ['vehicle_code' => $vehicle_code, 'vehicle_no' => $vehicle_no, 'l_state' => $l_state, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_3a)) {
                                    foreach ($all_matched_cases_3a as $all_row3a) {
                                        $data_array3a[] = $all_row3a['lower_court_id'];
                                    }
                                }


                            }

                            if($gov_not_state_id != 0 && $gov_not_case_type != "" && $gov_not_case_no != 0 && $gov_not_case_year != 0 && $gov_not_date != "null"){
                                $all_matched_cases_5 = is_data_from_table('lowerct', ['gov_not_state_id' => $gov_not_state_id, 'gov_not_case_type' => $gov_not_case_type, 'gov_not_case_no'=>$gov_not_case_no,'gov_not_case_year' => $gov_not_case_year, 'gov_not_date' => $gov_not_date, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_5)) {
                                    foreach ($all_matched_cases_5 as $all_row5) {
                                        $data_array5[] = $all_row5['lower_court_id'];
                                    }
                                }

                                $all_matched_cases_5a = is_data_from_table('lowerct_a', ['gov_not_state_id' => $gov_not_state_id, 'gov_not_case_type' => $gov_not_case_type, 'gov_not_case_no'=>$gov_not_case_no,'gov_not_case_year' => $gov_not_case_year, 'gov_not_date' => $gov_not_date, 'diary_no!=' => $d_no, 'lw_display' => 'Y'], 'lower_court_id');
                                if (!empty($all_matched_cases_5a)) {
                                    foreach ($all_matched_cases_5a as $all_row5a) {
                                        $data_array5a[] = $all_row5a['lower_court_id'];
                                    }
                                }

                            }


                            if($relied_court != 0 && $relied_case_type != 0){
                                $data['relied_details_pending'] = $this->SimilarityModel->getSimilarityForReliedDetails($relied_court, $relied_state, $relied_district, $relied_case_no, $relied_case_year,"");
                                $data['relied_details_disposed'] = $this->SimilarityModel->getSimilarityForReliedDetails($relied_court, $relied_state, $relied_district, $relied_case_no, $relied_case_year,"_a");
                            }

                            if($transfer_court != 0 && $transfer_case_type != 0){
                                $data['transfer_details_pending'] = $this->SimilarityModel->getSimilarityForTransferDetails($transfer_court, $transfer_state, $transfer_district, $transfer_case_no, $transfer_case_year,"");
                                $data['transfer_details_disposed'] = $this->SimilarityModel->getSimilarityForTransferDetails($transfer_court, $transfer_state, $transfer_district, $transfer_case_no, $transfer_case_year,"_a");
                            }

                        }
                    }
                }

                $lower_ct_data_ids =  $lower_ct_a_data_ids =  $data['state_bench_pending'] =  $data['state_bench_disposed'] = $data['police_station_data_disposed'] = $data['police_station_data']=$data['reference_similarity'] ="";
                $data['reference_similarity_disposed'] = $data['vehicle_data_pending']= $data['vehicle_data_disposed']="";

                if (!empty($data_array)) {
                    $lower_ct_data_ids = implode(",", $data_array);
                    $data['state_bench_pending'] = $this->SimilarityModel->getStateBenchSimilarity($lower_ct_data_ids, "");
                }
                if (!empty($data_array_a)) {
                    $lower_ct_a_data_ids = implode(",", $data_array_a);
                    $data['state_bench_disposed'] = $this->SimilarityModel->getStateBenchSimilarity($lower_ct_a_data_ids, "_a");
                }

                if (!empty($data_array4)) {
                    $lower_ct_data_4ids = implode(",", $data_array4);
                    $data['reference_similarity'] = $this->SimilarityModel->getReferenceSimilarity($lower_ct_data_4ids, "");
                }
                if (!empty($data_array_4a)) {
                    $lower_ct_a_data_4ids = implode(",", $data_array_4a);
                    $data['reference_similarity_disposed'] = $this->SimilarityModel->getReferenceSimilarity($lower_ct_a_data_4ids, "_a");
                }

                if (!empty($data_array2)) {
                    $lower_ct_data_2ids = implode(",", $data_array2);
                    $data['police_station_data'] = $this->SimilarityModel->getPoliceStationSimilarity($lower_ct_data_2ids, "");
                }
                if (!empty($data_array2a)) {
                    $lower_ct_a_data_2ids = implode(",", $data_array2a);
                    $data['police_station_data_disposed'] = $this->SimilarityModel->getPoliceStationSimilarity($lower_ct_a_data_2ids,"_a");
                }

                if (!empty($data_array3)) {
                    $lower_ct_data_3ids = implode(",", $data_array3);
                    $data['vehicle_data_pending'] = $this->SimilarityModel->getVehicleSimilarity($lower_ct_data_3ids, "");
                }
                if (!empty($data_array3a)) {
                    $lower_ct_a_data_3ids = implode(",", $data_array3a);
                    $data['vehicle_data_disposed'] = $this->SimilarityModel->getVehicleSimilarity($lower_ct_a_data_3ids,"_a");
                }

                if (!empty($data_array5)) {
                    $lower_ct_data_5ids = implode(",", $data_array5);
                    $data['govt_notification_similarity'] = $this->SimilarityModel->getGovtNotificationSimilarity($lower_ct_data_5ids, "");
                }
                if (!empty($data_array5a)) {
                    $lower_ct_a_data_5ids = implode(",", $data_array5a);
                    $data['govt_notification_similarity_disposed'] = $this->SimilarityModel->getGovtNotificationSimilarity($lower_ct_a_data_5ids,"_a");
                }

                $data['current_stage'] = $this->SimilarityModel->getStageOfDiary($diary_no);
                $case_status = $_SESSION['filing_details']['c_status'];
                if($case_status == 'P'){
                    $table_pref = "";
                }else{
                    $table_pref = "_a";
                }

                $data['res_listed'] = $this->SimilarityModel->getDiaryHearingDt($diary_no,$table_pref);
                $data['main_diary_number'] = $this->SimilarityModel->getCheckForMainDairyNumber($diary_no,$table_pref);
                $data['without_header'] =  $without_header;

                return view('Filing/similarity', $data);
            }else {
                echo '3@@@Data not found!';
                exit(); //session()->setFlashdata("message_error", 'Data not Fount');
            }
            exit();
    }

    public function updateLinkedCase()
    {
        $hd_link = $this->request->getPost('hd_link');
        $dv_mn_case = $this->request->getPost('dv_mn_case');
        $m_diary_n = $this->request->getPost('m_diary_n');
        $reason = $this->request->getPost('reason');
        if (!empty($dv_mn_case)) {
            $checkForCountConct = $this->SimilarityModel->getDiaryConnection($m_diary_n, 'Y', $dv_mn_case);
            $totalCount = $checkForCountConct['count'];
            if ($totalCount == 0) {
                $insertConct = [
                    'conn_key' => $dv_mn_case,
                    'diary_no' => $m_diary_n,
                    'list' => 'Y',
                    'usercode' => session()->get('login')['usercode'],
                    'conn_type' => 'L',
                    'linking_reason' => !empty($reason) ? $reason : NULL,
                    'ent_dt' => date("Y-m-d H:i:s"),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()
                ];

                $this->SimilarityModel->insertConct($insertConct);
                // $insertLinkedCaseArray = [
                //     'conn_key' => $dv_mn_case,
                //     'diary_no' => $hd_link,
                //     'display' => 'Y',
                //     'linked_to' => $hd_link,
                //     'create_modify' => date("Y-m-d H:i:s"),
                //     'updated_on' => date("Y-m-d H:i:s"),
                //     'updated_by' => session()->get('login')['usercode'],
                //     'updated_by_ip' => getClientIP()
                // ];
                // $this->SimilarityModel->insertLinkedCase($insertLinkedCaseArray);
                $this->SimilarityModel->updateMainCaseDiaryNumber($m_diary_n, $dv_mn_case);
                echo "Y";
            } else {
                echo "Dairy No. already Linked";
            }
        } else {
            $checkForCountConct = $this->SimilarityModel->getDiaryConnection($m_diary_n, 'N', $hd_link);
            $totalCount = $checkForCountConct['count'];
            if ($totalCount == 0) {
                $insertConct = [
                    'conn_key' => $hd_link,
                    'diary_no' => $m_diary_n,
                    'list' => 'Y',
                    'usercode' => session()->get('login')['usercode'],
                    'conn_type' => 'L',
                    'linked_to' => $hd_link,
                    'linking_reason' => !empty($reason) ? $reason : NULL,
                    'ent_dt' => date("Y-m-d H:i:s"),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()
                ];
                $this->SimilarityModel->insertConct($insertConct);

                // $insertLinkedCaseArray = [
                //     'conn_key' => $hd_link,
                //     'diary_no' => $m_diary_n,
                //     'display' => 'Y',
                //     'linked_to' => $hd_link,
                //     'create_modify' => date("Y-m-d H:i:s"),
                //     'updated_on' => date("Y-m-d H:i:s"),
                //     'updated_by' => session()->get('login')['usercode'],
                //     'updated_by_ip' => getClientIP()
                // ];
                // $this->SimilarityModel->insertLinkedCase($insertLinkedCaseArray);
                $this->SimilarityModel->updateCaseDiaryNumber($hd_link, $m_diary_n);

                echo "Y";
            } else {
                echo "Dairy No. already Linked";
            }
        }
    }

    public function case_status($d=null)
    {
        if (isset($d)) {
            echo $this->component_case_status_process_tab($d);exit();
        }
    }

    public function case_details(){
        ini_set('memory_limit', '1024M');
        $diary_no = $this->request->getPost('diary_no');
        $diary_info = get_diary_numyear($diary_no);
        $main_diary_number = array('dn' => $diary_info[0] , 'dy' => $diary_info[1]);
        echo $this->component_case_status_process_tab($main_diary_number);exit();
    }

    public function component_case_status_process_tab($diary_no = '')
    {
        $model = new \App\Models\Common\Component\Model_case_status();
        $html = "";
        $data = getCaseDetails($diary_no);
        $data['component'] = 'component_for_case_status_process';
        $data['Model_case_status'] = $model;
        $html = view('Common/Component/case_status/case_status_process_tab', $data);
        return $html;
    }
}
