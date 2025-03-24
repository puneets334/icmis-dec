<?php

namespace App\Controllers\Listing;
// ini_set('memory_limit', '8192M');
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Common\Dropdown_list_model;

class CaseInfo extends BaseController
{

    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Dropdown_list_model;

    function __construct()
    {
        ini_set('memory_limit', '1024M');
        $this->CaseInfoModel = new CaseInfoModel();
    }
    public function index()
    {
        $diary_no = $this->diary_no;
        $data['case_info'] = $this->CaseInfoModel->get_case_info();
        return view('Listing/gate_info/case_search', $data);
    }

    public function search()
    {
        $current_page_url = '';
        if ($this->request->getMethod() === 'post') {
            if (!isset($_REQUEST['redirect_url'])) {
                $_REQUEST['redirect_url'] = base_url('Filing/Diary_modify');
            }
            $redirect_url = $_REQUEST['redirect_url'];
            $search_type = $this->request->getPost('search_type');
            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');
            $case_type = $this->request->getPost('case_type');
            $case_number = $this->request->getPost('case_number');
            $case_year = $this->request->getPost('case_year');
            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');

            if (!empty($search_type) && $search_type != null) {
                if ($search_type == 'D') {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'diary_number' => $diary_number,
                        'diary_year' => $diary_year,
                    ];
                } else {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'case_type' => $case_type,
                        'case_number' => $case_number,
                        'case_year' => $case_year,
                    ];
                }
            } else {
                $data = [
                    'search_type' => $search_type
                ];
            }

            if (!$this->validation->run($data)) {
                // handle validation errors
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }

            $search_type = $this->request->getPost('search_type');
            if ($search_type == 'D') {
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
            } elseif ($search_type == 'C') {
                $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
                if (!empty($diary_no)) {
                    $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                } else {
                    $get_main_table = array();
                }
            }
            if ($get_main_table) {
                $this->session->set(array('filing_details' => $get_main_table));
                echo '1@@@' . $redirect_url;
                exit();
            } else {
                echo '3@@@Data not found!';
                exit();
            }
            exit();
        }

        $data['current_page_url'] = $current_page_url;
        return view('Filing/diary_search', $data);
    }

    public function case_info_process()
    {
        $ucode = session()->get('login')['usercode'];
        $t_slpcc = '';
        if ($this->request->getMethod() === 'post') {
            $search_type = $this->request->getPost('search_type');
            $diary_number = $this->request->getPost('diary_number');
            $diary_year = $this->request->getPost('diary_year');
            $case_type = $this->request->getPost('case_type');
            $case_number = $this->request->getPost('case_number');
            $case_year = $this->request->getPost('case_year');

            $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
            if (!empty($search_type) && $search_type != null) {
                if ($search_type == 'D') {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('diary_number', 'Diary number', 'required');
                    $this->validation->setRule('diary_year', 'Diary year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'diary_number' => $diary_number,
                        'diary_year' => $diary_year,
                    ];
                } else {
                    $this->validation->setRule('search_type', 'Select Diary or Case type', 'required');
                    $this->validation->setRule('case_type', 'Case type', 'required');
                    $this->validation->setRule('case_number', 'Case number', 'required');
                    $this->validation->setRule('case_year', 'Case year', 'required');

                    $data = [
                        'search_type' => $search_type,
                        'case_type' => $case_type,
                        'case_number' => $case_number,
                        'case_year' => $case_year,
                    ];
                }
            } else {
                $data = [
                    'search_type' => $search_type
                ];
            }

            if (!$this->validation->run($data)) {
                echo '3@@@';
                echo $this->validation->listErrors();
                exit();
            }

            $search_type = $this->request->getPost('search_type');
            
            $get_main_table = [];
            if ($search_type == 'D') {
                $diary_no = $diary_number . $diary_year;
                $get_main_table = $this->CaseInfoModel->get_diary_details_by_diary_no($diary_no);                
                $get_case_status = $this->get_case_status($diary_no, $ucode, $t_slpcc);                
            } elseif ($search_type == 'C') {
                $diary_no = get_diary_case_type($case_type, $case_number, $case_year);
                if (!empty($diary_no)) {
                    $get_main_table = $this->CaseInfoModel->get_diary_details_by_diary_no($diary_no);
                    $get_case_status = $this->get_case_status($diary_no, $ucode, $t_slpcc);
                } else {
                    $get_main_table = [];
                    $get_case_status = '';
                }
            }
            //echo component_case_status_process_tab($diary_no);exit();            
            
            $data = [];
            if (count($get_main_table) >= 1) {
                $data = $this->getCaseDetails($diary_no);
            }
            $data['case_result'] = $get_main_table;
            $data['case_status'] = $get_case_status;            
            $resul_view = view('Listing/gate_info/case_details', $data);
            echo '1@@@' . $resul_view;
            exit();
        }
    }

    public function insertCase()
    {
        $user_code = session()->get('login')['usercode'];
        $data = [
            'diary_no' => $this->request->getPost('dno'),
            'message' => $this->request->getPost('info'),
            'insert_time' => date("Y-m-d H:i:s"),
            'usercode' => $user_code,
            'userip' => getClientIP(),
            'display' => 'Y'
        ];

        $isedit = $this->request->getPost('is_edit');
        //$success = false;
        if (empty($isedit)) {
            $success = $this->CaseInfoModel->insert_case_info($data);
        } else {
            $success = $this->CaseInfoModel->update_case_info($isedit, $data);
        }

        if ($success) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Case information added successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add case information']);
        }
    }

    public function deleteCase()
    {
        $id = $this->request->getPost('id');
        if ($this->CaseInfoModel->delete_case_info($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Case information deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete case information']);
        }
    }

    public function getCaseDetails($main_diary_number)
    {
        $model = new \App\Models\Common\Component\Model_case_status();
        $dropdownlist_model = new \App\Models\Common\Dropdown_list_model();

        $data['diary_disposal_date'] = array();
        $diary_details = is_data_from_table('main', ['diary_no' => $main_diary_number], '*', 'R');
        $flag = "";
        if (empty($diary_details)) {

            $flag = "_a";
            $diary_details = is_data_from_table('main_a', ['diary_no' => $main_diary_number], '*', 'R');
            $data['diary_disposal_date'] = json_decode($model->get_diary_disposal_date($main_diary_number), true);
        }
        $data['diary_details'] = $diary_details;
        $data['party_details'] = json_decode($this->CaseInfoModel->get_party_details($main_diary_number, $flag), true);

        $data['pet_res_advocate_details'] = json_decode($model->get_pet_res_advocate($main_diary_number, $flag), true);

        $data['old_category'] = json_decode($model->get_old_category($main_diary_number, $flag), true);
        $data['new_category'] = json_decode($model->get_new_category($main_diary_number, $flag), true);

        $category_nm = '';
        $mul_category = '';
        $data['main_case'] = '';
        $data['new_category_name'] = '';
        if (!empty($data['old_category'])) {
            foreach ($data['old_category'] as $old_category) {
                if ($old_category['subcode1'] > 0 and $old_category['subcode2'] == 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] == 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] == 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name4'];
                elseif ($old_category['subcode1'] > 0 and $old_category['subcode2'] > 0 and $old_category['subcode3'] > 0 and $old_category['subcode4'] > 0)
                    $category_nm =  $old_category['sub_name1'] . " : " . $old_category['sub_name2'] . " : " . $old_category['sub_name3'] . " : " . $old_category['sub_name4'];

                if ($mul_category == '') {
                    $mul_category = $old_category['category_sc_old'] . '-' . $category_nm;
                } else {
                    $mul_category = $old_category['category_sc_old'] . '-' . $mul_category . ',<br> ' . $category_nm;
                }
            }
            $data['old_category_name'] = $mul_category;
        }

        if (!empty($data['new_category'])) {
            $data['new_category_name'] = $data['new_category'][0]['category_sc_old'] . '-' . $data['new_category'][0]['sub_name1'] . ' : ' . $data['new_category'][0]['sub_name4'];
        }
        $data['no_of_defect_days'] = json_decode($model->get_defect_days($main_diary_number, $flag), true);

        $data['recalled_matters'] = json_encode($model->get_recalled_matters($main_diary_number), true, JSON_UNESCAPED_SLASHES);
        $data['consignment_status'] = json_decode($model->get_consignment_status($main_diary_number, $flag), true);
        $data['sensitive_case'] = json_decode($model->get_sensitive_cases($main_diary_number), true);
        $data['efiled_cases'] = json_decode($model->get_efiled_cases($main_diary_number), true);
        $data['heardt_case'] = json_decode($model->get_heardt_case($main_diary_number, $flag), true);

        $last_listed_on = "";
        $last_listed_on_jud = "";
        if (!empty($data['heardt_case'])) {
            $row1 = $data['heardt_case'];
            // foreach ($data['heardt_case'] as $row1) {
                if ($row1['tbl'] == 'H') {
                    $tentative_cl_dt = $row1['tentative_cl_dt'];
                }
                $mc = $row1["filno"];
                if (!empty($mc)) {
                    $main_case = get_main_case($mc, $flag);
                    $data['main_case'] = $main_case;
                }
                $chk_next_dt = $row1["next_dt"];
                if ($row1["porl"] == "L" and $last_listed_on == "") {
                    $next_dt = date("Y-m-d", strtotime($row1["next_dt"]));
                    $cl_printed = $model->get_cl_printed_data($next_dt, $row1['mainhead'], $row1["clno"], $row1["roster_id"]);
                }
            // }
        }

        $data['case_type_history'] = json_decode($model->get_case_type_history($main_diary_number, $flag), true);
        $data['fill_dt_case'] = json_decode($model->get_fill_dt_case($main_diary_number, $flag), true);
        $data['diary_section_details'] = json_decode($model->get_diary_section_details($main_diary_number, $flag), true);
        $data['da_section_details'] = json_decode($model->get_da_section_details($main_diary_number, $flag), true);
        $data['autodiary_details'] = json_decode($model->get_autodiary_details($main_diary_number), true);
        $data['filing_stage'] = json_decode($model->get_fil_trap_details($main_diary_number, $flag), true);
        $data['acts_sections'] = json_decode($model->get_acts_sections_details($main_diary_number), true);
        $data['diary_number'] = $main_diary_number;
        $data['IB_DA_Details'] = json_decode($model->get_IB_DA_Details($main_diary_number, $flag), true);
        $data['file_movement_data'] = json_decode($model->get_file_movement_data($main_diary_number, $flag), true);

        if (!empty($data['IB_DA_Details'])) {
            $IbDaName = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['IB_DA_Details']['name'] . " [" . $data['IB_DA_Details']["section_name"] . "]" . "</font>";;
        } else {
            $IbDaName = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['diary_section_details']["name"] . " [" .  $data['diary_section_details']["section_name"] . "]" . "</font>";;
        }
        $section_da_name =  "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $data['da_section_details']["name"] . "</font>";
        if ($data['da_section_details']["dacode"] != "0") {
            $section_da_name .= "<font style='font-size:12px;font-weight:bold;'> [SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $data['da_section_details']["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
        } else {
            $tentative_section = json_decode($model->get_tentative_section($main_diary_number, $flag), true);

            $section_da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $tentative_section['section_name'] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
        }
        if (!empty($data['fill_dt_case'])) {
            if ($data['fill_dt_case']['last_u'] != '')
                $data['last_updated_by'] = $data['fill_dt_case']['last_u'];
            if ($data['fill_dt_case']['last_dt'] != '') {
                $last_dt = date_create($data['fill_dt_case']['last_dt']);
                $last_dt = date_format($last_dt, "d-m-Y h:i A");
                $data['last_updated_by'] .= " On " . $last_dt;
            }
        }
        $pname = "";
        $rname = "";
        $impname = "";
        $intname = "";
        $padvname = "";
        $radvname = "";
        $iadvname = "";
        $nadvname = "";
        $ac_court = "";        

        if (!empty($data['party_details'])) {
            foreach ($data['party_details']  as $row_p) {
                $tmp_addr = "";
                $tmp_name = "";

                if ($row_p["pflag"] == 'O')
                    $tmp_name = $tmp_name . "<p style=color:red>&nbsp;&nbsp;";
                else if ($row_p["pflag"] == 'D')
                    $tmp_name = $tmp_name . "<p style=color:#9932CC>&nbsp;&nbsp;";
                else
                    $tmp_name = $tmp_name . "<p>&nbsp;&nbsp;";

                $tmp_name = $tmp_name . $row_p["sr_no_show"];
                $tmp_name = $tmp_name . " ";
                $tmp_name = $tmp_name . $row_p["partyname"];
                if ($row_p["prfhname"] != "")
                    $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
                if ($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] != NULL)
                    $tmp_name .= " [" . $row_p["remark_lrs"] . "]";

                if ($row_p["pflag"] == 'O' || $row_p["pflag"] == 'D')
                    $tmp_name .= " [" . $row_p["remark_del"] . "]";

                if ($row_p["addr1"] != "")
                    $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";
                if ($row_p['ind_dep'] != 'I' && !empty($row_p['deptname']))
                    $tmp_addr = $tmp_addr . " " . trim(str_replace($row_p['deptname'], '', $row_p['partysuff'])) . ", ";
                if ($row_p["addr2"] != "")
                    $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";
                if ($row_p["city"] != "") {

                    $dstName = '';
                    if ($row_p["dstname"] != "") {
                        $dstName .= " , DISTRICT: " . $row_p["dstname"];
                    }
                    $city_name = get_state_data($row_p["city"])[0]['name'] ?? "";



                    $tmp_addr = $tmp_addr . $dstName . " ," . $city_name . " ";
                }
                if ($row_p["state"] != "") {
                    $state_name = get_state_data($row_p["state"])[0]['name'] ?? "";
                    $tmp_addr = $tmp_addr . ", " . $state_name . " ";
                }
                if ($tmp_addr != "")
                    $tmp_name = $tmp_name . "<br>&nbsp;&nbsp;" . $tmp_addr . "";
                $tmp_name = $tmp_name . "</p>";

                if ($row_p["pet_res"] == "P") {
                    $pname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "R") {
                    $rname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "I") {
                    $impname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "N") {
                    $intname .= $tmp_name;
                }
            }
        }
        $data['IB_da_name'] = $IbDaName;
        $data['section_da_name'] = $section_da_name;
        $data['petitioner_name'] = $pname;
        $data['respondent_name'] = $rname;
        $data['impleader'] = $impname;
        $data['intervenor'] = $intname;

        if (!empty($data['pet_res_advocate_details'])) {
            foreach ($data['pet_res_advocate_details'] as $row_advp) {
                $tmp_advname =  "<p>&nbsp;&nbsp;";
                if ($row_advp['is_ac'] == 'Y') {
                    if ($row_advp['if_aor'] == 'Y')
                        $advType = "AOR";
                    else if ($row_advp['if_sen'] == 'Y')
                        $advType = "Senior Advocate";
                    else if ($row_advp['if_aor'] == 'N' && $row_advp['if_sen'] == 'N')
                        $advType = "NON-AOR";
                    else if ($row_advp['if_other'] == 'Y')
                        $advType = "Other";
                    $ac_text = '[Amicus Curiae- ' . $advType . ']';
                } else
                    $ac_text = '';
                if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null)) {
                    $for_court = "[For Court]";
                } else {
                    $for_court = "";
                }
                $t_adv = $row_advp['name'];
                if ($row_advp['isdead'] == 'Y') {
                    $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
                }
                $tmp_advname = $tmp_advname . $t_adv .  $row_advp['adv'] . $ac_text . '</p>';
                if ($row_advp['pet_res'] == "P")
                    $padvname .= $tmp_advname;
                if ($row_advp['pet_res'] == "R")
                    $radvname .= $tmp_advname;
                if ($row_advp['pet_res'] == "I")
                    $iadvname .= $tmp_advname;
                if ($row_advp['pet_res'] == "N")
                    $nadvname .= $tmp_advname;
                if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null))
                    $ac_court .= $tmp_advname;
            }
        }
        $data['ac_court'] = $ac_court;
        $data['padvname'] = $padvname;
        $data['radvname'] = $radvname;
        $data['respondent_name'] = $rname;
        $data['iadvname'] = $iadvname;
        $data['nadvname'] = $nadvname;
        return $data;
        //return $result_view = view('Common/Component/case_status/case_status_details_tab',$data);
    }

    public function case_getinfo()
    {
        $return = false;
        if ($this->request->getMethod() == 'post') {
            $case_id = $this->request->getPost('case_id');
            $message = $this->CaseInfoModel->get_message($case_id);
            $return = $message;
        }
        return $return;
    }


    function get_case_status($diaryno, $ucode, $t_slpcc)
    {
        $output = $lastorder = $listorder = $t_chk = $check_for_final_hearing = $dispdet = $head = $rest_main = $next_date = $pstage1 = $sta_infd = "";
        $results_rj = $this->CaseInfoModel->diaryDispos($diaryno);
        
        if ($results_rj) {        
            $disp_dt = $results_rj[0]->disp_dt;
            if ($disp_dt != "" and $disp_dt != NULL){
                $t_chk = $disp_dt . " 23:59:59";
            }
        }

        $case_details = $this->CaseInfoModel->case_details($diaryno);
        
        if ($case_details) {
            $pname = "";
            $rname = "";
            $impname = "";
            $intname = "";
            
            // $result_p = $this->CaseInfoModel->get_party_details($diaryno);
            $result_p = json_decode($this->CaseInfoModel->get_party_details($diaryno), true);
            foreach ($result_p as $row_p) {
                $tmp_addr = "";
                $tmp_name = "";

                if ($row_p["pflag"] == 'O')
                    $tmp_name = $tmp_name . "<p style=color:red>&nbsp;&nbsp;";
                else if ($row_p["pflag"] == 'D')
                    $tmp_name = $tmp_name . "<p style=color:#9932CC>&nbsp;&nbsp;";
                else
                    $tmp_name = $tmp_name . "<p>&nbsp;&nbsp;";

                $tmp_name = $tmp_name . $row_p["sr_no_show"];
                $tmp_name = $tmp_name . " ";
                $tmp_name = $tmp_name . $row_p["partyname"];
                if ($row_p["prfhname"] != "")
                    $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
                if ($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] != NULL)
                    $tmp_name .= " [" . $row_p["remark_lrs"] . "]";

                if ($row_p["pflag"] == 'O' || $row_p["pflag"] == 'D')
                    $tmp_name .= " [" . $row_p["remark_del"] . "]";

                if ($row_p["addr1"] != "")
                    $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";

                
                if ($row_p['ind_dep'] != 'I' && $row_p['deptname'] != ''){
                    if(trim(str_replace($row_p['deptname'], '', $row_p['partysuff']) != '')){
                        $tmp_addr = $tmp_addr . " " . trim(str_replace($row_p['deptname'], '', $row_p['partysuff'])) . ", ";
                    }
                }
                    

                if ($row_p["addr2"] != "")
                    $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";
                


                if ($row_p["city"] != "") {
                    $dstName = '';
                    if ($row_p["dstname"] != "") {
                        $dstName .= " , DISTRICT: " . $row_p["dstname"];
                    }

                    $tmp_addr = $tmp_addr . $dstName . " ," . get_state($row_p["city"]) . " ";
                }
                if ($row_p["state"] != "") {
                    $tmp_addr = $tmp_addr . ", " . get_state($row_p["state"]) . " ";
                }
                if ($tmp_addr != "")
                    $tmp_name = $tmp_name . "<br>&nbsp;&nbsp;" . $tmp_addr . "";
                $tmp_name = $tmp_name . "</p>";

                if ($row_p["pet_res"] == "P") {
                    $pname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "R") {
                    $rname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "I") {
                    $impname .= $tmp_name;
                }
                if ($row_p["pet_res"] == "N") {
                    $intname .= $tmp_name;
                }
            }

            $padvname = "";
            $radvname = "";
            $iadvname = "";
            $nadvname = "";

            $i = 1;
            $conn_case = "";
            $bailno = "";
            $t_spl = "";
            $t_outside = "";
            
            foreach($case_details as $row) {
                
                $pet_app = "";
                if ($row['fil_no_fh'] != '') {
                    $in_array_var = get_ma_info($row['ct2'], $row['crf2'], $row['f_year']);
                } elseif ($row['fil_no'] != '') {
                    $in_array_var = get_ma_info($row['ct1'], $row['crf1'], $row['m_year']);
                }

                if (!(empty($in_array_var))) {
                    for ($i = 0; $i < count($in_array_var); $i++) {
                        $t_fil_no_in = get_casenos_comma($in_array_var[$i][0]);
                        if ($i > 0)
                            $pet_app .= "<br>";
                        $pet_app .= "D.No. " . get_real_diaryno($in_array_var[$i][0]);
                        if (trim($t_fil_no_in) != '') {
                            $pet_app .= " (" . $t_fil_no_in . ")";
                        } else {
                            $t_fil_no_in = $this->CaseInfoModel->shortDescMain($in_array_var[$i][0]);
                            $pet_app .= " (" . $t_fil_no_in . ")";
                        }
                    }
                }
                
                $t_nat      = $row['nature'];
                $t_prevno   = $row['prevno'];
                $t_outside  = $row['outside'];
                $conn_case  = $row['conn_key'];
                $lastorder  = $row['lastorder'];
                // $listorder  = $row[20];
                $petadv     = $row['pet_adv_id'];
                $resadv     = $row['res_adv_id'];
                $filedon    = $row['fil_dt'];
                $bailno     = $row['bailno'];

                $result_advp = $this->CaseInfoModel->getAdvocateId($diaryno);
                
                foreach($result_advp as $row_advp) {
                    $tmp_advname =  "<p>&nbsp;&nbsp;";
                    $tmp_advname = $tmp_advname . $this->get_advocates($row_advp['advocate_id'], '') . $row_advp['adv'];
                    $tmp_advname = $tmp_advname . "</p>";

                    if ($row_advp['pet_res'] == "P")
                        $padvname .= $tmp_advname;
                    if ($row_advp['pet_res'] == "R")
                        $radvname .= $tmp_advname;
                    if ($row_advp['pet_res'] == "I")
                        $iadvname .= $tmp_advname;
                    if ($row_advp['pet_res'] == "N")
                        $nadvname .= $tmp_advname;
                }
                
                $bench = "";
                
                $category = get_mul_category($diaryno);
                
                $tentative_cl_dt = "";
                $adminorder = "";
                if ($row['if_sclsc'] == 1)
                    $status = 'SCLSC ';
                elseif ($row['nature'] == 6)
                    $status = 'JAIL PETITION ';
                else
                    $status = '';

                $result_array = $this->CaseInfoModel->getDefDays($diaryno);
                
                if (!is_null($result_array) && $result_array < 90) 
                {
                    $status .= "Cases under Defect List valid for 90 Days";
                    $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                } 
                elseif (!is_null($result_array) && $result_array >= 90) {
                    $status .= "Defective Matters Not Re-filed after 90 Days";
                    $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                } 
                else if ($row['c_status'] == 'P') {
                    $num_rows = $this->CaseInfoModel->getDiaryRecall($diaryno);
                    if ($num_rows) {
                        $status .= "PENDING(RECALLED)";
                        $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                    } else {
                        $status .= "PENDING";
                        $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='blue' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                    }
                }

                if ($row['c_status'] == 'D') {
                    $status .= "DISPOSED";
                    $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                }

                $fil_dt = $row['fil_dt_f'];

                $consign_status = '';

                $result_consign = $this->CaseInfoModel->getConsign($diaryno);
                
                foreach($result_consign AS $row_consigned) {
                    $consign_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>Consigned On : " . $row_consigned['consignment_date'] . "</font></span></span>";
                }
                
                $sensitive_case_status = '';
                $result_sensitive_case = $this->CaseInfoModel->getSensitiveCase($diaryno, $ucode);                
                if ($result_sensitive_case) {
                    $sensitive_case_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>Sensitive Case</font></span></span>";
                }

                $last_listed_on = "";
                $last_listed_on_jud = "";
                $proposed_on = "";  
                $proposed_on_jud = "";  
                $stage = "";
                $chk_next_dt = "";
                $drop_note = "";
                $cl_printed = "";
                $main_case = "";
                $mh = "";
                $mc = "";
                $result1 = $this->CaseInfoModel->get_last_heardt($diaryno, $t_chk);
                
                foreach($result1 AS $row1) 
                {
                    if ($row1['tbl'] == 'H') {
                        $tentative_cl_dt = $row1['tentative_cl_dt'];
                    }
                    
                    $mc = $row1["filno"];
                    if ($mc != "" && $main_case == "") {
                        $main_case = $this->CaseInfoModel->get_main_case($mc);
                    }

                    if ($row1["porl"] == "L" and $last_listed_on == "") 
                    {
                        $result_cl = $this->CaseInfoModel->cl_printed($row1["next_dt"], $row1["mainhead"], $row1["clno"], $row1["roster_id"]);
                        
                        if ($result_cl > 0) {
                            $cl_printed = "Y";
                        } else {
                            $cl_printed = "N";
                        }
                        if ($row1["tbl"] != "H" or strtotime($row1["next_dt"]) < strtotime(date("Y-m-d")))
                            $cl_printed = "";

                        $chk_next_dt = $row1["next_dt"];
                       
                        $result_drop = $this->CaseInfoModel->drop_details($diaryno, $row1["next_dt"], $row1["roster_id"]);
                        if ($result_drop){
                            $drop_note = " <br><font color='red' style='font-size:11px;font-weight:bold;'>Drop Case from Hon'ble Court</font>";
                            foreach($result_drop as $row_drop) {
                                $drop_note .= " <br>[<font color='red' style='font-size:11px;font-weight:bold;'>" .  stripslashes($row_drop["jnm"]) . " - CL.NO. : " . $row_drop["clno"] . " - " . $row_drop["nrs"] . "</font>]";
                                $t_drp_jname = stripslashes($row_drop["jnm"]);
                            }
                        }
                            
                        
                        $last_listed_on = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row1["next_dt"] . "</font>";
                        $next_date = date("Y-m-d", strtotime($row1["next_dt"]));
                        if ($row1['board_type'] == 'J')
                            $last_listed_on_jud = " [Before Hon. Coram]";
                        else if ($row1['board_type'] == 'C')
                            $last_listed_on_jud = " [Before Hon. Chamber Judge]";
                        else if ($row1['board_type'] == 'R')
                            $last_listed_on_jud = " [Registrar]";

                        if (($row1['main_supp_flag'] == "1" or $row1['main_supp_flag'] == "2") and $row1['judges'] != 0 and $row1['judges'] != '') {
                            $judgesnames = get_judges($row1['judges']);
                            $last_listed_on_jud .= " [<font color='green' style='font-size:12px;font-weight:bold;'>" . stripslashes($judgesnames) . "</font>]";
                        }

                        $t_lst_jname = stripslashes(get_judges($row1['judges']));
                        if ($row1["mainhead"] == "F")
                            $crdt = date("Y-m-d", strtotime('monday this week'));
                        else
                            $crdt = date("Y-m-d");

                        if (strtotime($crdt) <= strtotime($row1["next_dt"])) 
                        {
                            $court_no = $this->CaseInfoModel->get_Courtno($row['diary_no']);
                            if (($court_no == 21) || ($court_no == 22)) {

                                $last_listed_on_jud .= " [ITEM.NO. : <font color='red' style='font-size:12px;font-weight:bold;'>" . stripslashes($row1["brdslno"]) . "</font>]";
                            } else {


                                $last_listed_on_jud .= " [ITEM.NO. : <font color='red' style='font-size:12px;font-weight:bold;'>" . stripslashes($row1["brdslno"]) . "</font>]";
                                $last_listed_on_jud .= " [COURT.NO : <font color='red' style='font-size:12px;font-weight:bold;'>" . stripslashes($court_no) . "</font>]";
                            }
                        }

                        if ($main_case != "") $last_listed_on_jud .= " <font color='red' style='font-size:12px;font-weight:bold;'> [as connected matter ] </font>";
                    }
                    
                    if ($row1["porl"] == "P" and $proposed_on == "" and $row['c_status'] != 'D') {
                        if (($row1["tbl"] != "H" and (strtotime(date("Y-m-d")) > strtotime($chk_next_dt))) or $row1['main_supp_flag'] == "3") {
                            $proposed_on = "<font color='red' style='font-size:20px;font-weight:100;'>NOT UPDATED</font>";
                        } else if (strtotime(date("Y-m-d")) == strtotime($chk_next_dt)) {
                            if ($drop_note != "" and $t_drp_jname == $t_lst_jname)
                                $proposed_on = "<font color='red' style='font-size:20px;font-weight:100;'>Dropped</font><font style='font-family: Arial;font-size:25px;font-weight:150;color:red;'></font>";
                            else
                                $proposed_on = "<font color='red' style='font-size:20px;font-weight:100;'>Listed Today</font><font style='font-family: Arial;font-size:25px;font-weight:150;color:red;'></font>";
                        } else if (strtotime(date("Y-m-d")) < strtotime($chk_next_dt)) {

                            if ($drop_note != "" and $t_drp_jname == $t_lst_jname)
                                $proposed_on = "<font color='red' style='font-size:20px;font-weight:100;'>Dropped on : " . $chk_next_dt . "</font>";
                            else
                                $proposed_on = "<font color='red' style='font-size:20px;font-weight:100;'>Listed on : " . $chk_next_dt . "</font>";

                        } else {
                            if (date('Y', strtotime($row1["next_dt"])) == 2077 or date('Y', strtotime($row1["next_dt"])) == 2088 or date('Y', strtotime($row1["next_dt"])) == 2099)
                                $next_dt_upd = "";
                            else
                                $next_dt_upd = $row1["next_dt"];
                            $proposed_on = "<font color='blue' style='font-size:20px;font-weight:100;'>" . $next_dt_upd . "</font>";
                            if (strtotime(date("Y-m-d")) < strtotime($row1["next_dt"]))
                                $t_prop = "&nbsp;&nbsp;&nbsp;<font style='font-size:20px;font-weight:100;'>UPDATED</font>";
                            else
                                $t_prop = "&nbsp;&nbsp;&nbsp;<font style='font-size:20px;font-weight:100;'>NOT LISTED, UPDATED</font>";

                            $temp_jn = stripslashes($row1["judges"]);
                            if ($temp_jn != "0" and $temp_jn != "") {
                                $temp_jn = "[" . $temp_jn . "]";
                            } else {
                                $temp_jn = "";
                            }
                            $proposed_on_jud = " <font style='font-size:20px;font-weight:100;'><font color='green' style='font-size:20px;font-weight:100;'>" . $temp_jn . "</font></font>" . $t_prop;
                            if ($main_case != "")
                                $proposed_on .= " <font style='font-size:20px;font-weight:100;'>[Connected with : <font color='red' style='font-size:20px;font-weight:100;'>" . $main_case . "</font>]</font>";
                        }
                    }
                    
                    if ($mh == "")
                        $mh = $row1["mainhead"];
                    if ($stage == "") {
                        if ($row1["mainhead"] == "M") {
                            $stage = "Motion Hearing";
                        } elseif ($row1["mainhead"] == "F") {
                            $stage = "Final Hearing";
                            $check_for_final_hearing = "YES";
                        } elseif ($row1["mainhead"] == "N") {
                            $stage = "Not Reached Cases";
                        } elseif ($row1["mainhead"] == "L") {
                            $stage = "Lok Adalat";
                        }
                        if ($row1["subhead"] != "") {
                            $t_stage = "";
                            $result1_s = $this->CaseInfoModel->get_subheading($row1["subhead"]);
                            if ($result1_s) {
                                foreach($result1_s AS $row1_s) {
                                    if ($row1["mainhead"] == "F" && isset($row1_s["stage_nature"]))
                                    {
                                        switch ($row1_s["stage_nature"]) {
                                            case "C":
                                                $sn = "Civil - ";
                                                break;
                                            case "R":
                                                $sn = "Criminal - ";
                                                break;
                                            case "WC":
                                                $sn = "Writ Civil - ";
                                                break;
                                            case "WR":
                                                $sn = "Writ Criminal - ";
                                                break;
                                            case "EP":
                                                $sn = "Election Petition - ";
                                                break;
                                            case "PIL":
                                                $sn = "PIL - ";
                                                break;
                                            case "":
                                                $sn = "";
                                                break;
                                        }
                                        
                                        $criteria = "";
                                        if ($row1_s["stagecode4"] > 0) {
                                            $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["grp_name2"] . " - " . $row1_s["stagename"];
                                        } elseif ($row1_s["stagecode3"] > 0) {
                                            $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["stagename"];
                                        } elseif ($row1_s["stagecode2"] > 0) {
                                            $t_stage = $row1_s["grp_name"] . " - " . $row1_s["stagename"];
                                        } elseif ($row1_s["stagecode1"] > 0) {
                                            $t_stage = $row1_s["stagename"];
                                        }
                                        
                                        $result_fh = $this->CaseInfoModel->tbl_schema();
                                        if ($result_fh) {
                                            $row_fh = $result_fh[0];
                                            if ($row['c_status'] == 'P')
                                                $head = "<br/><font color=red><u>Note</u>: SrNo. of final hearing pending cases under each head is last updated on " . $row_fh["create_time"] . "</font>";
                                            $row_t = $this->CaseInfoModel->getSchematblRec($row_fh["tbl"], $row['diary_no'], $row1_s);
                                            if ($row_t) {
                                                $t_stage .= "  (at SrNo.: <u>" . $row_t[0]["sno2"] . "</u> and at SrNo.: <u>" . $row_t[0]["sno"] . "</u> in overall FH cases)";
                                            }
                                        }
                                        
                                        $stage = $stage . " <br> (" . $sn . $t_stage . ")";
                                    } else {
                                        $pstage1 = "";
                                        $stage = $stage . " <br> " . $row1_s["stagename"];
                                        if ($row1_s["stagecode"] == 849 or $row1_s["stagecode"] == 850) 
                                        {
                                            $pstage1 = " / " . $this->CaseInfoModel->get_previous_stage($row['diary_no'], $row1_s["stagecode"]);
                                            $stage = $stage . $pstage1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $for_final = "";
                {
                    $row_fh_final = $this->CaseInfoModel->get_Admit_dt($diaryno);
                    if ($row_fh_final) {
                        if ($row_fh_final->admit_dt != NULL) {
                            $for_final = " [<font color=red>ADMITTED ON : " . date('d-m-Y', strtotime($row_fh_final->admit_dt)) . "</font>]";
                        } 
                        else {
                            $row_final = $this->CaseInfoModel->get_fillClose($diaryno);
                            if ($row_final) {
                                $for_final = " [<font color=red>ADMITTED ON : " . $row_final->cldate . "</font>]";
                            }
                        }
                    }
                }
                
                
                if ($row['c_status'] != 'D') {
                    $row1_demoas = $this->CaseInfoModel->case_remark($diaryno, $next_date);
                    if ($row1_demoas) {
                        if ($row1_demoas[0]['status'] == 'P')
                            $status = "Pending - " . $row1_demoas[0]['aggregated_heads'];
                        if ($row1_demoas[0]['status'] == 'D')
                            $status = "Disposed - " . $row1_demoas[0]['aggregated_heads'];
                        
                    } else {
                        if ($lastorder != "")
                            $status .= " (" . $lastorder . ")";
                    }                    
                }
                
                
                
                if ($listorder != "") 
                {
                    $row_lp = $this->CaseInfoModel->list_purpose($listorder);                    
                    if ($row_lp) {
                        $t_lo = $row_lp->lp;
                        // $t_lo .= $row_lp->lp;
                    }
                    $stage = $stage . "<br> (" . $t_lo . ")";
                }

               
                $jud = "";
                for ($i = 1; $i <= 5; $i += 1) {
                    if (isset($row1["jud" . $i]) && $row1["jud" . $i] > 0) {
                        $result1_j = $this->CaseInfoModel->judgeDetails($row1["jud" . $i]);;
                        if ($result1_j) {
                            $jud = $jud . $result1_j->jname . ", ";
                        }
                    }
                }

                if ($jud != "")
                    $jud = substr($jud, 0, (strlen($jud) - 2));
                $jud = str_replace("\\", "", $jud);
                
                $act_code = $row['actcode'];
                $sect = "";
                $fil_date_for = $this->CaseInfoModel->mainUserinfo($diaryno);
                $output .= '<table border="0"  align="left" width="100%">';
                if ($main_case != "")
                    $main_case = "<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";



                $u_name = "";
                $row_da = $this->CaseInfoModel->mainUsersectionInfo($diaryno);
                if ($row_da) {
                    $u_name = " by <font color='blue'>" . $row_da["name"] . "</font>";
                    $u_name .= "<font> [SECTION: </font><font color='red'>" . $row_da["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                }

                $output .= "<tr>
                <td width='140px'>Diary No.</td>
                <td><div width='100%'><font color='blue' style='font-size:12px;font-weight:bold;'>" .  $row['case_no'] . "/" . $row['year'] . "</font> Received on " . $row['diary_no_rec_date'] . $u_name . $main_case . $main_status . $consign_status . $sensitive_case_status . "</div></td></tr>";

                $t_fil_no = $this->get_case_nos($diaryno, '&nbsp;&nbsp;');
                
                if (trim($t_fil_no) == '') {
                    $results12 = $this->CaseInfoModel->shortDesc($row['casetype_id'], false);
                    if ($results12) {
                        $t_fil_no = $results12->short_description;
                    }
                }
                if ($t_slpcc != '')
                    $t_slpcc = "<br>" . $t_slpcc;


                $t_fil_no1 = "";
                $rs_lct = $this->CaseInfoModel->getCasetypeLwr($diaryno);
                if ($rs_lct) {
                    $t_fil_no1 .= "";
                    foreach ($rs_lct AS $ro_lct) {
                        if ($t_fil_no1 == '')
                            $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . "  " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                        else
                            $t_fil_no1 .= ", " . $ro_lct['type_sname'] . "  " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                    }
                }

                $output .= "<tr>
                    <td width='140px'>Case No.</td>
                    <td  ><div width='100%'>" . $t_fil_no . $t_slpcc . $t_fil_no1 . "</div></td></tr>";

                if ($t_spl != "") {
                    $output .= "<tr>
                        <td width='140px'  >Special Type</td>
                        <td  >" . $t_spl . "</td></tr>";
                }

                if ($pet_app != "") {
                    $output .= "<tr>
                        <td width='140px'>Petitions/Appn. against case</td>
                        <td nowrap>" . $pet_app . "</td></tr>";
                }


                // $results13 = $this->CaseInfoModel->getmCaseStaus($row['case_status_id']);
                // if ($results13) {
                //     $t_s_d = $$results13->description;
                // }
        
                $da_name = "";
                
                $row_da = $this->CaseInfoModel->getUsersectionDetails($diaryno);
                if ($row_da) {
                    $da_name = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row_da->name . "</font>";
                    if ($row_da->dacode != "0")
                        $da_name .= "<font style='font-size:12px;font-weight:bold;'> [SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_da->section_name . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                    else 
                    {
                        if ($row_da->active_reg_year != 0)
                            $ten_reg_yr = $row_da->active_reg_year;
                        else if ($row_da->reg_year_fh != 0)
                            $ten_reg_yr = $row_da->reg_year_fh;
                        else if ($row_da->reg_year_mh != 0)
                            $ten_reg_yr = $row_da->reg_year_mh;
                        else
                            $ten_reg_yr = date('Y', strtotime($row_da->diary_no_rec_date));

                        if ($row_da->active_casetype_id != 0)
                            $casetype_displ = $row_da->active_casetype_id;
                        else if ($row_da->casetype_id != 0)
                            $casetype_displ = $row_da->casetype_id;
                        
                        $row_section = $this->CaseInfoModel->main_tentative($diaryno);
                        
                        $sec = $row_section->tentative_section;
                        
                        $section_ten_rs = $this->CaseInfoModel->getDaCaseDistribution($casetype_displ, $ten_reg_yr, $row_da->ref_agency_state_id);
                        
                        if ($section_ten_rs) {
                            $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $sec . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                        } 
                        else {
                            
                            $diff_da_sec_name = array(5, 6, 7, 8, 39, 9, 10, 19, 20, 25, 26);                            

                            if (in_array($casetype_displ, $diff_da_sec_name)) {
                                
                                $lower_case_temp = $this->CaseInfoModel->getlowerct($diaryno);
                                if ($lower_case_temp) {                                    
                                    $for_da_temp_row = $this->CaseInfoModel->getMainCaseHistory($lower_case_temp->lct_casetype, $lower_case_temp->lct_caseyear, str_pad($lower_case_temp->lct_caseno, 6, 0, STR_PAD_LEFT));

                                    if ($for_da_temp_row->section_name != NULL || $for_da_temp_row->section_name != '') {
                                        $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $for_da_temp_row->section_name . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                    } else {
                                        if ($for_da_temp_row->active_reg_year != 0)
                                            $ten_reg_yr = $for_da_temp_row->active_reg_year;
                                        else if ($for_da_temp_row->reg_year_fh != 0)
                                            $ten_reg_yr = $for_da_temp_row->reg_year_fh;
                                        else if ($for_da_temp_row->reg_year_mh != 0)
                                            $ten_reg_yr = $for_da_temp_row->reg_year_mh;
                                        else
                                            $ten_reg_yr = date('Y', strtotime($for_da_temp_row->diary_no_rec_date));

                                        if ($for_da_temp_row->active_casetype_id != 0)
                                            $casetype_displ = $for_da_temp_row->active_casetype_id;
                                        else if ($for_da_temp_row->casetype_id != 0)
                                            $casetype_displ = $for_da_temp_row->casetype_id;

                                        $section_ten_rs = $this->CaseInfoModel->getDaCaseDistribution($casetype_displ, $ten_reg_yr, $for_da_temp_row->ref_agency_state_id);

                                        if ($section_ten_rs) {
                                            $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $section_ten_rs->section_name . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                        }
                                    }
                                } else {
                                    $row_section = $this->CaseInfoModel->main_tentative($diaryno);                                    
                                    $sec = $row_section->tentative_section;
                                    $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $sec . ".</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                }
                            }
                        }
                    }
                }
                
                $output .= "<tr >
                <td width='140px'  >DA Name</td>
                <td  >" . $da_name . "</td></tr>";

                
                $t_fd = "";
                if($fil_date_for){
                    if ($fil_date_for['last_u'] != '')
                        $t_fd = $fil_date_for['last_u'];
                    if ($fil_date_for['last_dt'] != '')
                        $t_fd .= " On " . $fil_date_for['last_dt'];
                }
                
                $output .= "<tr>
                <td width='140px'>Last Updated By</td>
                <td style='font-size:12px;font-weight:100;'>" . $t_fd . "</td></tr>";
                $output .= "<tr >
                <td width='140px'>Last Listed On</td>
                <td style='font-size:12px;font-weight:bold;'><b>";
                
                if (isset($row1) && $row1['benchflag'] == "F")
                    $benchfailed = "<font color='red'>Bench Failed</font>";
                else
                    $benchfailed = "";
                if ($cl_printed == "N")
                    $output .= "-----";
                else
                    $output .= $last_listed_on . $last_listed_on_jud . $drop_note . $benchfailed;

                $tbl = (isset($row1) && $row1['tbl']) ? $row1['tbl'] : '';
                $output .= $tbl . "</b></td></tr>";
                
                $rjdate = "";
                $disp_str = $disp_dt = "";
                
                if ($row['c_status'] == 'D') 
                {
                    $row_rj = $this->CaseInfoModel->get_dispose($row['diary_no']);
                    if ($row_rj)
                    {
                        $disp_str = " (Order Date: " . $row_rj["ord_dt"] . " and Updated on " . $row_rj["ddt"] . ")<br> JUDGES: " . stripslashes($row_rj["judges"]);
                        
                        $row_k1 = $this->CaseInfoModel->getHeardtCat($row['diary_no']);

                        $results_k3 = $this->CaseInfoModel->judgeCaseRemark($row['diary_no']);
                        if ($results_k3) {
                            if ($row_k1 > 0) {
                                $results_k2 = $this->CaseInfoModel->judgeDetails($row_rj['dispjud'], 0);
                                $disp_str .= "<br><span style='color:green;'>Judgement Pronaunced by : " . stripslashes($results_k2->jname) . " of the bench comprising : " . stripslashes($results_k3[0]["judges"]) . "</span>";
                            }
                        }
                        $disp_dt = $row_rj["disp_dt"];
                        if ($row_rj["rj_dt"])
                            $rjdate = "&nbsp;&nbsp;&nbsp;RJ Date: " . date('d-m-Y', strtotime($row_rj["rj_dt"]));
                        
                        $disptype = $row_rj['disp_type'];
                        if ($disptype != "") {
                            $drow = $this->CaseInfoModel->get_disposal($disptype);
                            $d_spk = '';
                            if ($drow) {
                                if ($ucode == 203 || $ucode == 204 || $ucode == 888 || $ucode == 912) {
                                    if ($drow->spk == "N")
                                        $d_spk = " (Non Speaking)";
                                    else
                                        $d_spk = " (Speaking)";
                                }
                                $dispdet = $drow->dispname . $d_spk;
                                
                                if ($disptype == 19)
                                    $dispdet = $dispdet . " by LOK ADALAT ";
                            }
                        }                        
                    }
                }
                
                $output .= "<tr>
                        <td width='140px'  >Last Order</td>
                        <td  style='font-size:12px;font-weight:100;'>" . $lastorder . $rjdate . "</td></tr>";
                if ($tentative_cl_dt != NULL and $tentative_cl_dt != "") {
                    $act_tt_dt = "";
                    if ($tentative_cl_dt == "2099-12-12" or $tentative_cl_dt == "2099-12-13")
                        $act_tt_dt = "Referred to Lok Adalat</font>";
                    else if ($tentative_cl_dt == "2088-12-12" or $tentative_cl_dt == "2088-12-13")
                        $act_tt_dt = "List Next Lok Adalat</font>";
                    else if ($tentative_cl_dt == "2077-12-12" or $tentative_cl_dt == "2077-12-13")
                        $act_tt_dt = "Case referred to Mediation Centre</font>";
                    else
                        $act_tt_dt = date('d-m-Y', strtotime($tentative_cl_dt)) . "</font> (Computer generated)";

                    

                    $curative_review_flag = 0;
                    if ($this->CaseInfoModel->count_main($row['diary_no']) > 0) {
                        $curative_review_flag = 1;
                    }

                    
                    $morethan_three_judge_cs = 0;
                    if ($this->CaseInfoModel->count_mul_cat($row['diary_no']) > 0) {
                        $morethan_three_judge_cs = "1";
                    }
                    
                    $check_larger_bench_remark = "";
                    if ($this->CaseInfoModel->count_caseRemark($row['diary_no']) > 0) {
                        $check_larger_bench_remark = "yes";
                    }
                    
                    
                    if ($check_for_final_hearing == "" && $morethan_three_judge_cs == 0 && $row['c_status'] == 'P' && $check_larger_bench_remark == "" && $curative_review_flag == "") 
                    {
                        $result_array = $this->CaseInfoModel->getCase_status('tentative_listing_date');
                        
                        if ($result_array->display_flag == 1 || in_array($ucode, explode(',', $result_array->always_allowed_users))) {
                            $output .= "<tr>
                        <td width='140px'  >Tentatively case &nbsp;&nbsp;may be listed on</td>
                        <td  style='font-size:12px;font-weight:100;'><font color='blue' style='font-size:12px;font-weight:100;'>" . $act_tt_dt;
                            if ($adminorder != "")
                                $output .= " (AO)";
                            $output .= "</td></tr>";
                        }
                    }
                }
                
                if($rowstinf = $this->CaseInfoModel->getbrdrem($diaryno)) {
                    if(!empty($rowstinf->remark)){
                        $sta_infd = " [<font color='green' style='font-size:12px;font-weight:100;'>" . stripslashes(str_replace('[', '', str_replace(']', '', $rowstinf->remark))) . "</font>]";
                    }else{
                        $sta_infd =  $rowstinf->remark ;
                    }
                }

                if ($proposed_on != "") {
                    if ($row['c_status'] != 'D')
                        $t_p = $proposed_on . $proposed_on_jud;
                    else
                        $t_p = "";
                } else {
                    
                    if ($row['c_status'] != 'D' and (isset($row1) && $row1["porl"] == "P"))
                        $t_p = "NOT UPDATED";
                    else
                        $t_p = "";
                }

                $morethan_three_judge_cs = 0;
                if ($this->CaseInfoModel->count_mul_cat($row['diary_no']) > 0) {
                    $morethan_three_judge_cs = "1";
                }

                if ($check_for_final_hearing == "" and ($morethan_three_judge_cs == 0)) {
                    $result_array = $this->CaseInfoModel->getCase_status('case_updated_for_date');   
                                     
                    if ($result_array->display_flag == 1 || in_array($ucode, explode(',', $result_array->always_allowed_users))) {

                        $output .= "<tr >
                        <td width='140px'  >Updated for</td>
                        <td style='font-size:20px;font-weight:100;color:red;'><b><font style='font-size:20px;font-weight:100;color:red;'><span class='blink_me'>";
                        if ($cl_printed == "N")
                            $output .= "UPDATED";
                        else


                            $output .= $t_p;
                        $output .= "</span></font>&nbsp;&nbsp; </b></td></tr>";
                    }
                }
                
                $output .= "<tr>
                <td width='140px'  >Status</td>
                <td  style='font-size:12px;font-weight:100;'><font color='red' style='font-size:12px;font-weight:100;'>" . trim($status) . $disp_str . "</font></td></tr>";

                if ($dispdet != "") {
                    $output .= "<tr>
                    <td width='140px'  >Disp.Type</td>
                    <td  style='font-size:12px;font-weight:100;'><font color='red' style='font-size:12px;font-weight:100;'>" . trim($dispdet) . "</font></td></tr>";
                }
                
                if ($rest_main != "") {
                    $output .= "<tr>
                        <td width='140px'  >Restoration Details</td>
                        <td  style='font-size:12px;font-weight:100;'><font color='red' style='font-size:12px;font-weight:100;'>" . trim($rest_main) . "</font></td></tr>";
                }
                            $output .= "<tr>
            <td width='140px'  >";
                            if ($pstage1 != "")
                                $output .= "Stage / Previous Stage</td>";
                            else
                                $output .= "Stage</td>";
                            $output .= "<td  style='font-size:12px;font-weight:100;'>" . trim($stage) . trim($head) . "</td></tr>";
                            if ($bailno != "")
                                $output .= "<tr>
            <td width='140px'  >Bail No.</td>
            <td  style='font-size:12px;font-weight:100;'>" . $this->convert_number_to_words(trim($bailno)) . " Bail</td></tr>";
                            if (trim($for_final) != "")
                                $output .= "<tr>
            <td width='140px'  >Admitted</td>
            <td  style='font-size:12px;font-weight:100;'>" . trim($for_final) . "</td></tr>";
            
                $rgo_sql = $this->CaseInfoModel->get_rgo_default($row['diary_no']);
                
                $t_rgo = '';
                if ($rgo_sql) {
                    foreach ($rgo_sql AS $res_rgo) {
                        $lower_sql = '';
                        if ($res_rgo['court_type'] != 'S') {
                            $arr_hcourt = explode("~", $res_rgo['hcourt_no']);
                        }
                        
                        if ($res_rgo['court_type'] == 'L') {
                            $res_lower = $this->CaseInfoModel->get_lchc_casetype($arr_hcourt[0], $arr_hcourt[2], $res_rgo['court_type']);

                            if ($res_lower) {
                                $t_rgo = "Lower Court Case No. " . $res_lower->lccasename . " " . $arr_hcourt[3] . "/" . $arr_hcourt[4];
                            }
                        } else if ($res_rgo['court_type'] == 'H') {
                            $res_lower = $this->CaseInfoModel->get_lchc_casetype($arr_hcourt[0], $arr_hcourt[2], $res_rgo['court_type']);
                            if ($res_lower) {
                                $t_rgo = "Lower Court Case No. " . $res_lower->lccasename . " " . $arr_hcourt[3] . "/" . $arr_hcourt[4];
                            }
                        } else if ($res_rgo['court_type'] == 'A') {
                            $res_lower = $this->CaseInfoModel->get_lchc_casetype($arr_hcourt[0], $arr_hcourt[2], false, true);
                            if ($res_lower) {
                                $t_rgo = "Lower Court Case No. " . $res_lower->lccasename . " " . $arr_hcourt[3] . "/" . $arr_hcourt[4];
                            }
                        } else {
                            if ($t_rgo == '')
                                $t_rgo = "D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . get_casenos_comma($res_rgo['fil_no2']);
                            else
                                $t_rgo = "<br> " . "D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . get_casenos_comma($res_rgo['fil_no2']);
                        }
                    }
                }
                
                if ($t_rgo != '') {
                    $output .= "<tr>
                    <td width='140px'  >Conditional Dispose</td>
                    <td  style='font-size:12px;font-weight:100;'><b> <font style='font-size:12px;font-weight:100;'><b>" . $t_rgo . "</b></font></b></td></tr>";
                                    }

                                    $output .= "<tr>
                    <td width='140px'  >Statutory Info.</td>
                    <td  style='font-size:12px;font-weight:100;'><b> <font style='font-size:12px;font-weight:100;'>" . $sta_infd . "</font></b></td></tr>";
                    $res_bnch = $this->CaseInfoModel->getbench($row['bench']);
                    $res_bnchname = isset($res_bnch->bench_name) ? $res_bnch->bench_name : '';
                    $output .= "<tr>
                    <td width='140px'  >Bench</td>
                    <td  >" . trim($res_bnchname) . "</td></tr>";
                                    if ($t_outside == "Y")
                                        $output .= "<tr>
                    <td width='140px'  >Is Outside Councel</td>
                    <td  >YES </td></tr>";
                
                $filename_path = "/mnt/copying/copying/";
                $t_file_exist = "";
                $filename = $filename_path . $row['res_name'] . "/" . $row['year'] . "/" . $row['year'] . "-" . $row['res_name'] . "-" . intval($row['pet_name']) . ".pdf";
                $filename1 = $filename_path . $row['res_name'] . "/" . $row['year'] . "1/" . $row['year'] . "-" . $row['res_name'] . "-" . intval($row['pet_name']) . ".pdf";
                $filename2 = $filename_path . $row['res_name'] . "/" . $row['year'] . "2/" . $row['year'] . "-" . $row['res_name'] . "-" . intval($row['pet_name']) . ".pdf";
                if (file_exists($filename) || file_exists($filename1) || file_exists($filename2))
                    $t_file_exist = "<font color='green'>The Judgment/Order has been Scanned ..! (" . date("F d Y H:i:s", filemtime($filename)) . ")</font>";
                else
                    $t_file_exist = "<font color='red'>The Judgment/Order has NOT been Scanned ..!</font>";
                $t_file_exist = "";
                if ($t_file_exist != "")
                    $output .= "<tr><td width='140px'  >Scanning Info</td><td  >" . $t_file_exist . "</td></tr>";
                
                
                if ($ucode == 1) {
                    $return_bfnbf = $this->getBfNbf($diaryno);
                    
                    $t_return_bfnbf = explode('^|^', $return_bfnbf);
                    if ($t_return_bfnbf[0] != "")
                        $output .= "<tr> <td width='140px'  >LIST BEFORE</td><td  style='font-weight:bold;'><font color='red'>" . $t_return_bfnbf[0] . "</font></td></tr>";
                                                        
                    if ($t_return_bfnbf[1] != "")
                        $output .= "<tr><td width='140px'  >NOT LIST BEFORE</td><td  style='font-weight:bold;'><font color='red'>" . $t_return_bfnbf[1] . "</font></td></tr>";
                }
                                                    
                // $not_go_before = $this->check_not_go_before($diaryno);
                
                // if ($not_go_before != "") {
                //     $output .= "<tr><td width='140px'  >NOT GO BEFORE (By Advocate)</td>
                //                     <td  style='font-weight:bold;'><font color='red'>" . $not_go_before . "</font></td></tr>";
                // }

                $output .= "<tr><td width='140px'  >Category</td><td  >" . $category . "</td></tr>";
                
                $row1 = $this->CaseInfoModel->get_actmain($diaryno);
                $act_section = '';
                if ($row1) {
                    if ($row1->section != '')
                        $t_as = $row1->act_name . '-' . $row1->section;
                    else
                        $t_as = $row1->act_name;

                    if ($act_section == '')
                        $act_section = $t_as;
                    else
                        $act_section = $act_section . ', ' . $t_as;
                }
                
                $output .= "<tr >
                <td width='140px'  >Act</td>
                <td  >" . $act_section . "</td></tr>";
                
                $output .= "<tr >
                <td width='140px' >Petitioner(s)</td>
                <td  >" . $pname . "</td></tr>";
                                $output .= "<tr >
                <td width='140px'  >Respondent(s)</td>
                <td  >" . $rname . "</td></tr>";
                                $output .= "<tr >
                <td width='140px' >Impleader(s)</td>
                <td  >" . $impname . "</td></tr>";
                                $output .= "<tr >
                <td width='140px' >Intervenor(s)</td>
                <td  >" . $intname . "</td></tr>";
                                $output .= "<tr >
                <td width='140px'  >Pet. Advocate(s)</td>
                <td  >" . $padvname . "</td></tr>";
                                $output .= "<tr >
                <td width='140px'  >Resp. Advocate(s)</td>
                <td  >" . $radvname . "</td></tr>";

                if ($iadvname != '') {
                    $output .= "<tr ><td width='140px'  >Impleaders Advocate(s)</td><td  >" . $iadvname . "</td></tr>";
                }

                if ($nadvname != '') {
                    $output .= "<tr ><td width='140px'  >Intervenor Advocate(s)</td> <td  >" . $nadvname . "</td></tr>";
                }
                
                
                $act_sec = $this->CaseInfoModel->get_Sub_actmain($diaryno);
                $act_sec_des = '';
                if ($act_sec) {
                    foreach($act_sec AS $act_sec_r){
                        $act_sec_des .= $act_sec_r['act_name'];
                    }
                    
                    $act_sec_des = rtrim($act_sec_des, ',');
                }
                $output .= "<tr>
                <td width='140px'  >U/Section</td>
                <td  >" . trim($sect) . ' ' . $act_sec_des . "</td></tr>";

                $output .= "<tr><td width='140px'>File Movement</td>";
                
                $fil_mov = $this->CaseInfoModel->get_fil_trap($diaryno);;
                if ($fil_mov) {
                    $t_table = '<td><table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $t_table .= "<tr><th align='center'><b>Dispatch By</b></th><th><b>Dispatch On</b></th><th><b>Remarks</b></th><th><b>Dispatch to</b></th><th align='center'><b>Receive by</b></th><th align='center'><b>Receive On</b></th><th><b>Completed On</b></th></tr>";
                    foreach($fil_mov as $fil_mov_r) {
                        if ($fil_mov_r['comp_dt'] == '' || $fil_mov_r['comp_dt'] == null || $fil_mov_r['comp_dt'] == NULL)
                            $fil_mov_r['comp_dt'] = '0000-00-00 00:00:00';
                        else
                            $fil_mov_r['comp_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['comp_dt']));
                        if ($fil_mov_r['rece_dt'] == '' || $fil_mov_r['rece_dt'] == null || $fil_mov_r['rece_dt'] == NULL)
                            $fil_mov_r['rece_dt'] = '0000-00-00 00:00:00';
                        else
                            $fil_mov_r['rece_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['rece_dt']));
                        if ($fil_mov_r['disp_dt'] == '' || $fil_mov_r['disp_dt'] == null || $fil_mov_r['disp_dt'] == NULL)
                            $fil_mov_r['disp_dt'] = '0000-00-00 00:00:00';
                        else
                            $fil_mov_r['disp_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['disp_dt']));

                        $t_table .= "<tr><td align='center'>" . $fil_mov_r['d_by_name'] . "</td><td>" . $fil_mov_r['disp_dt'] . "</td><td>" . $fil_mov_r['remarks'] . "</td>"
                            . "<td>" . $fil_mov_r['d_to_name'] . "</td><td align='center'>" . $fil_mov_r['r_by_name'] . "</td><td align='center'>" . $fil_mov_r['rece_dt'] . "</td>"
                            . "<td align='center'>" . $fil_mov_r['comp_dt'] . "</td></tr>";
                    }
                    $t_table .= '</table></td>';
                } else {
                    $t_table = "<td><strong>No Record Found</strong></td>";
                }

                // echo $diary_mov = "select u1.name AS d_by_name,us1.section_name AS dby_section, u2.name AS d_to_name, us2.section_name AS dto_section, disp_dt,remark,rece_dt from diary_movement dm
                // inner join diary_copy_set ds on ds.id=dm.diary_copy_set
                // inner join main m on m.diary_no=ds.diary_no 
                // inner join users u1 on u1.usercode=dm.disp_by
                // inner join users u2 on u2.usercode=dm.disp_to
                // left join usersection us1 on us1.id=u1.section and us1.display='Y' 
                // left join usersection us2 on us2.id=u2.section and us2.display='Y'
                // where ds.diary_no='$diaryno' group by ds.diary_no;";
                // die;

                $diary_mov = $this->CaseInfoModel->get_diary_movement($diaryno);
                
                if ($diary_mov) {
                    $t_table = "";
                    $t_table = '<td><table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $t_table .= "<tr><th align='center'><b>Dispatch By</b></th><th><b>Dispatch On</b></th><th align='center'><b>Dispatch To</b></th><th align='center'><b>Remarks</b></th><th align='center'><b>Receive On</b></th></tr>";
                    foreach($diary_mov AS $diary_mov_r) {
                        if ($diary_mov_r['rece_dt'] == '' || $diary_mov_r['rece_dt'] == null || $diary_mov_r['rece_dt'] == NULL)
                            $diary_mov_r['rece_dt'] = '0000-00-00 00:00:00';
                        else
                            $diary_mov_r['rece_dt'] = date('d-m-Y h:i:s A', strtotime($diary_mov_r['rece_dt']));
                        if ($diary_mov_r['disp_dt'] == '' || $diary_mov_r['disp_dt'] == null || $diary_mov_r['disp_dt'] == NULL)
                            $diary_mov_r['disp_dt'] = '0000-00-00 00:00:00';
                        else
                            $diary_mov_r['disp_dt'] = date('d-m-Y h:i:s A', strtotime($diary_mov_r['disp_dt']));

                        $t_table .= "<tr><td align='center'>" . $diary_mov_r['d_by_name'] . ' (' . $diary_mov_r['dby_section'] . ')' . "</td><td>" . $diary_mov_r['disp_dt'] . "</td>"
                            . "<td>" . $diary_mov_r['d_to_name'] . ' (' . $diary_mov_r['dto_section'] . ')' . "</td><td align='center'>" . $diary_mov_r['remark'] . "</td><td align='center'>" . $diary_mov_r['rece_dt'] . "</td></tr>";
                    }
                    $t_table .= '</table></td>';
                }



                $output .= $t_table . "</tr>";
            }
            $output .= '</table>';
        } else
            $output .= "<p align=center><font color=red><b>CASE NOT FOUND</b></font></p>";
        return $output;
    }

    public function get_advocates($adv_id, $wen = '')
    {
        $t_adv = "";
        $t11a = $this->CaseInfoModel->getBarDetails($adv_id);
        
        if (count($t11a) > 0) {
            foreach($t11a AS $row11a) {
                $t_adv = $row11a['name'];
                if ($row11a['isdead'] == 'Y')
                    $t_adv = "<font color=red>" . $t_adv . " (Dead / Retired / Elevated) </font>";
                if ($wen == 'wen')
                    $t_adv .= " [" . $row11a['enroll_no'] . "/" . $row11a['eyear'] . "]";
            }
        }
        return $t_adv;
    }

    public function get_case_nos($dn, $separator, $rby = ''){
        $t_fil_no='';
        $row_main = $this->CaseInfoModel->get_maininfo($dn);
        $cases = "";

        if($row_main)
        {    
            if($row_main->ad!=''){
                $t_m_y = explode(':',$row_main->ad);

                if($t_m_y[0]!='')
                {
                    $cases .= $t_m_y[0].",";
                    $t_m1   = substr($t_m_y[0],0,2);
                    $t_m2   = substr($t_m_y[0],3,6);
                    $t_m21  = substr($t_m_y[0],10,6);
                    $t_m3   = $t_m_y[1];
                    $t_m4   = $t_m_y[2];
                    
                    $res_ct_typ = $this->CaseInfoModel->shortDesc($t_m1);
                    // $res_ct_typ = $row->short_description;
                    // $res_ct_typ_mf = $row->cs_m_f;
    
                    if($t_m2==$t_m21 || $t_m21=='')
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    else
                        $t_fil_no.= '<font color="#043fff"  style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                }
            }
            
            if($row_main->rd != '')
            {
                $t_m_y = explode(':',$row_main->rd);
                if($t_m_y[0] != '')
                {
                    $cases.=$t_m_y[0].",";
                    $t_m1=substr($t_m_y[0],0,2);
                    $t_m2=substr($t_m_y[0],3,6);
                    $t_m21=substr($t_m_y[0],10,6);
                    $t_m3=$t_m_y[1];
                    $t_m4=$t_m_y[2];
                    $res_ct_typ = $this->CaseInfoModel->shortDesc($t_m1);
                    // $res_ct_typ = $row['short_description'];   
                    // $res_ct_typ_mf = $row['cs_m_f'];

                    $short_description = isset($res_ct_typ->short_description) ? $res_ct_typ->short_description : '';
    
                    if($t_m2 == $t_m21)
                        $t_fil_no.='<font color="#043fff" style=" white-space: nowrap;">'.$short_description." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    else
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$short_description." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                }
            }
            
            if($row_main->md != ''){
    
                $t_m_y = explode(':',$row_main->md);
                if($t_m_y[0] != ''){
                    $cases .= $t_m_y[0].",";
                    $t_m1   = substr($t_m_y[0],0,2);
                    $t_m2   = substr($t_m_y[0],3,6);
                    $t_m21  = substr($t_m_y[0],10,6);
                    $t_m3   = $t_m_y[1];
                    $t_m4   = $t_m_y[2];
                    $res_ct_typ = $this->CaseInfoModel->shortDesc($t_m1);
                    // $res_ct_typ = $row['short_description'];   
                    // $res_ct_typ_mf = $row['cs_m_f'];
                    
                    if($t_m2==$t_m21 || $t_m21=='')
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    else
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                }
            }
        }
    
        $result_mc_h = $this->CaseInfoModel->getCasetypeHistory($dn);

        if($result_mc_h){
            $cnt = 0;
            foreach($result_mc_h AS $row_mc_h)
            {
                // echo $row_mc_h['oldno'].":".$row_mc_h['newno'].":<br>";
    
                if($row_mc_h['oldno']!='')
                {
                    $t_m    = explode(',',$row_mc_h['oldno']);
                    $t_m_y  = explode(':',$t_m[0]);
                    $pos    = strpos($cases, $t_m_y[0]);
    
                    if ($pos === false) 
                    {
                        $cnt++;
                        if($cnt%2 == 0)
                            $bgcolor = "#ff0015";
                        else
                            $bgcolor = "#ff01c8";
    
                        $cases .= $t_m_y[0].",";
                        $t_m1   = substr($t_m_y[0],0,2);
                        $t_m2   = substr($t_m_y[0],3,6);
                        $t_m21  = substr($t_m_y[0],10,6);
                        $t_m3   = $t_m_y[1];
                        $t_m4   = $t_m_y[2];    
                        
                        $res_ct_typ = $this->CaseInfoModel->shortDesc($t_m1);
                        // $res_ct_typ = $row['short_description'];   
                        // $res_ct_typ_mf = $row['cs_m_f'];
    
                        if($t_m2==$t_m21 || $t_m21=='')
                            $t_fil_no.= '<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                        else
                            $t_fil_no.= '<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    } 
                }
                
                $t_chk="";
    
                if($row_mc_h['newno']!=''){
                    $t_m=explode(',',$row_mc_h['newno']);
                    for ($i = 0; $i < count($t_m); $i++) 
                    {
                        $t_m_y=explode(':',$t_m[$i]);
                        $pos = strpos($cases, $t_m_y[0]);
    
                        if ($pos === false) 
                        {
                            $cases.=$t_m_y[0].",";
                            $t_m1=substr($t_m_y[0],0,2);
                            $t_m2=substr($t_m_y[0],3,6);
                            $t_m21=substr($t_m_y[0],10,6);
                            $t_m3=$t_m_y[1];
                            $t_m4=$t_m_y[2];  
                            $t_fn=$t_m_y[0];
    
                            if($t_chk!=$t_fn)
                            {
                                $cnt++;
                                if($cnt%2==0)
                                    $bgcolor="#ff0015";
                                else
                                    $bgcolor="#ff01c8";
                                
                                $res_ct_typ = $this->CaseInfoModel->shortDesc($t_m1);
                                // $res_ct_typ = $row['short_description'];   
                                // $res_ct_typ_mf = $row['cs_m_f'];
    
                                if($t_m2 == $t_m21 || $t_m21 == '' )
                                    $t_fil_no.='<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                                else
                                    $t_fil_no.='<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ->short_description." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                            }
                            $t_chk = $t_fn;
                        }
                    }
                }
            }
        }
    
        if(trim($t_fil_no) == '' && !empty($res_ct_typ))
        {
            $res_ct_typ = $this->CaseInfoModel->shortDesc($res_ct_typ->casetype_id, false);
            if ($res_ct_typ) {
                $t_fil_no = $res_ct_typ;
            }
        }
        return $t_fil_no;   
    }

    public function convert_number_to_words($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'First',
            2                   => 'Second',
            3                   => 'Third',
            4                   => 'Fourth',
            5                   => 'Fifth',
            6                   => 'Sixth',
            7                   => 'Seventh',
            8                   => 'Eighth',
            9                   => 'Ninth',
            10                  => 'Tenth',
            11                  => 'Eleventh',
            12                  => 'Twelveth',
            13                  => 'Thirteenth',
            14                  => 'Fourteenth',
            15                  => 'Fifteenth',
            16                  => 'Sixteenth',
            17                  => 'Seventeenth',
            18                  => 'Eighteenth',
            19                  => 'Nineteenth',
            20                  => 'Twentyth',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    function getBfNbf($tdiary_no)
    {
        $sql_nb = "select a.diary_no,GROUP_CONCAT(b.jname) as jn,a.notbef,DATE_FORMAT(a.ent_dt,'%d-%m-%Y %H:%i:%s') as entdt from not_before a, judge b where b.jcode=a.j1 and a.diary_no='" . $tdiary_no . "' GROUP BY a.diary_no,a.notbef";
        $pr_bf = $nbf = $bf = "";
        $t_nb = $this->CaseInfoModel->getnbf($tdiary_no);
        if ($t_nb) {
            foreach ($t_nb AS $rownb) {
                $t_jn = $rownb["jn"];
                $t_jn1 = stripslashes($t_jn);
                if ($rownb["notbef"] == "B")
                    if ($bf == "")
                        $bf .= $t_jn1 . " <font size=-2 color=grey>(" . $rownb["entdt"] . ")</font>";
                    else
                        $bf .= ",  " . $t_jn1 . " <font size=-2 color=grey>(" . $rownb["entdt"] . ")</font>";
                if ($rownb["notbef"] == "N")
                    if ($nbf == "")
                        $nbf .= $t_jn1 . " <font size=-2 color=grey>(" . $rownb["entdt"] . ")</font>";
                    else
                        $nbf .= ",  " . $t_jn1 . " <font size=-2 color=grey>(" . $rownb["entdt"] . ")</font>";
            }
        }
        return $bf . "^|^" . $nbf;
    }

    function check_not_go_before($diaryno)
    {
        $ret_data = "";
        // $sql="SELECT 
        //  GROUP_CONCAT(DISTINCT j.jname) AS jname 
        //FROM
        //  (
        //    (SELECT 
        //      a.adv_code AS enr_no,
        //      a.adv_cd_yr AS enr_yr 
        //    FROM
        //      advocate a 
        //    WHERE a.diary_no = '".$diaryno."' 
        //      AND a.display = 'Y') 
        //    UNION
        //    (
        //      (SELECT 
        //        IF(
        //          LOCATE('/', m.petadven) > 0,
        //          TRIM(
        //            SUBSTRING_INDEX(m.petadven, '/', 1)
        //          ),
        //          '0'
        //        ) AS enr_no,
        //        IF(
        //          LOCATE('/', m.petadven) > 0,
        //          TRIM(
        //            SUBSTRING_INDEX(m.petadven, '/', - 1)
        //          ),
        //          '0'
        //        ) AS enr_yr 
        //      FROM
        //        main m 
        //      WHERE m.diary_no = '".$diaryno."')
        //    ) 
        //    UNION
        //    (
        //      (SELECT 
        //        IF(
        //          LOCATE('/', m.resadven) > 0,
        //          TRIM(
        //            SUBSTRING_INDEX(m.resadven, '/', 1)
        //          ),
        //          '0'
        //        ) AS enr_no,
        //        IF(
        //          LOCATE('/', m.resadven) > 0,
        //          TRIM(
        //            SUBSTRING_INDEX(m.resadven, '/', - 1)
        //          ),
        //          '0'
        //        ) AS enr_yr 
        //      FROM
        //        main m 
        //      WHERE m.diary_no = '".$diaryno."')
        //    )
        //  ) adv 
        //  INNER JOIN adv_notgo_judg b 
        //    ON (
        //      adv.enr_no = b.enroll_no 
        //      AND adv.enr_yr = b.enroll_yr
        //    ) 
        //  INNER JOIN judge j 
        //    ON (j.jcode = b.jud)"; 
        //$result1 = mysql_query($sql) or die(mysql_error()." SQL:".$sql);
        //if(mysql_affected_rows() > 0) {
        //$row1 = mysql_fetch_array($result1);
        //$ret_data.=stripslashes(trim(strtoupper($row1['jname'])));
        //}
        //return $ret_data;
    }
}
