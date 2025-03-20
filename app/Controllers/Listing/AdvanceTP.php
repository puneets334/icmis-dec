<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\WorkingDaysModel;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\JudgeGroup;
use App\Models\Listing\AllocationTp;

class AdvanceTP extends BaseController
{

    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Dropdown_list_model;
    public $purposeModel;
    public $holidayModel;
    public $AllocationTp;

    function __construct()
    {
        $this->CaseInfoModel = new CaseInfoModel();
        $this->purposeModel = new ListingPurpose();
        $this->holidayModel = new WorkingDaysModel();
        $this->AllocationTp = new AllocationTp();
    }

    public function advance_allocation_tp()
    {
       
        $holidays = $this->holidayModel->getHolidays();
        
        $purposes = $this->purposeModel->getDisplayPurposes();
        
        $holiday_str = next_holidays();
       
        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));

        $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
       
        $mf = "M";
        $board_type = "J";
        $allocation_tp = $this->get_allocation_judge_advance_prepone($mf, $next_court_work_day, $board_type);
      
        return view(
            'Listing/advance_list/advance_tp/advance_allocation_tp',
            [
                'holiday_str' => $holiday_str,
                'next_court_work_day' => date("d-m-Y", strtotime($next_court_work_day)),
                'purposes' => $purposes,
                'purposeModel' => $this->purposeModel,
                'AllocationTp' => $this->AllocationTp,
                'allocation_tp' => $allocation_tp
            ]
        );
    }




    private function nextCourtWorkingDate($current_date)
    {

        return date("Y-m-d", strtotime($current_date . ' +2 days'));
    }

    public function get_allocation_judges_m_advance_prepone()
    {
        $request = service('request');
        $cldt = $request->getGet('list_dt');
        $p1 = $request->getGet('mainhead');
        $board_type = $request->getGet('bench');

        return $this->get_allocation_judge_advance_prepone($p1, $cldt, $board_type);
    }

    

    public function get_allocation_judge_advance_prepone($p1, $cldt, $board_type)
{

    $cldt = date('Y-m-d', strtotime($cldt));
    $cldtMMDDYYYY = date('d-m-Y', strtotime($cldt));
    $m_f = "";
    $from_to_dt = "";

    switch ($p1) {
        case "M":
            $m_f = "AND r.m_f = '1'";
            $from_to_dt = ($board_type == 'R') ? "AND r.to_date = '0000-00-00'" : "AND r.from_date = '$cldt'";
            break;
        case "L":
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt'";
            break;
        case "S":
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt'";
            break;
        default:
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt'";
            break;
    }

    $ro_isnmd = $this->AllocationTp->getisNMD($cldt);
   
    if ($ro_isnmd === null) {
        return "<span style='color:red;'><b>Not a Working Day</b></span><br>";
    }

    
    if ($ro_isnmd['is_nmd'] == 1) {
        $msg= "<span style='color:blue;'><b>Ready to list Regular Day Cases</b></span><br>";
    } else {
       
        $msg= "<span style='color:green;'><b>Ready to List Misc. Day Cases</b></span><br>";
    }

    $ro_isnmd = ['is_nmd' => $ro_isnmd['is_nmd']]; 
    $res = $this->AllocationTp->getJudgeGroupData($cldt, $ro_isnmd);
   

    if (!empty($res)) {
        
        $html = '<div id="prnnt2">'.
         $msg.
      
            '<fieldset>
                <legend class="text-center text-primary font-weight-bold">
                ADVANCE LIST ALLOCATION FOR DATED ' . $cldtMMDDYYYY . ' (Pre-ponement)
                </legend>
                <table class="table table-bordered table-striped" style="background:#f6fbf0;">
                    <thead>
                        <tr>
                            <th><b>SrNo.</b></th>
                            <th class="text-center">
                                <input type="checkbox" name="chkall" id="chkall" value="ALL" onclick="chkall1(this);">
                                <b>All</b>
                            </th>
                            <th class="text-center"><b>Hon\'ble Judge</b></th>
                            <th><b>Pre Notice Listed</b></th>
                            <th><b>After Notice Listed</b></th>
                            <th><b>Total Listed</b></th>
                        </tr>
                    </thead>
                    <tbody>';

        $srno = 1;
        $tot_listed = 0;
        $tot_pre_notice = 0;
        $tot_after_notice = 0;

        foreach ($res as $row) {
            $row1 = $this->AllocationTp->getListedData($board_type, $row["p1"], $cldt);
           
           
            $tot_pre_notice += isset($row1['pre_notice']) ? (int)$row1['pre_notice'] : 0;
            $tot_after_notice += isset($row1['after_notice']) ? (int)$row1['after_notice'] : 0;
            $tot_listed += isset($row1['listed']) ? (int)$row1['listed'] : 0;

            $html .= '<tr>
                <td>' . $srno++ . '</td>
                <td class="text-center" colspan="2">
                    <input type="checkbox" id="chkeeed" name="chk" value="' . $row["p1"] . '">
                    ' . $row['abbreviation'] . '
                </td>
                <td>' . (isset($row1['pre_notice']) ? $row1['pre_notice'] : 0) . '</td>
                <td>' . (isset($row1['after_notice']) ? $row1['after_notice'] : 0) . '</td>
                <td>' . (isset($row1['listed']) ? $row1['listed'] : 0) . '</td>
            </tr>';
        }

        $html .= '<tr class="font-weight-bold">
            <td colspan="3" class="text-right">TOTAL</td>
            <td>' . $tot_pre_notice . '</td>
            <td>' . $tot_after_notice . '</td>
            <td>' . $tot_listed . '</td>
        </tr>';

        $html .= '</tbody></table></fieldset></div>';

        return $html;
    } else {
        echo "<center>No Records Found</center>";
    }
}



    public function advance_allocation_process_tp()
    {
        $request = service('request');
        $q_clno = 2;
        $q_main_supp_flag = 1;
        $mainhead = "M";
        $noc = $request->getPost('noc');
        $selected_judges = $request->getPost('selected_judges');
        $listing_purpose = $request->getPost('listing_purpose');
        $short_non_short_sel = $request->getPost('short_non_short_sel');
        $q_next_dt = date("Y-m-d", strtotime($request->getPost('list_dt')));
        $pre_after_notice_sel = $request->getPost('pre_after_notice_sel');
        $data =
            [
                'q_next_dt' => $q_next_dt,
                'mainhead' => $mainhead,
                'noc' => $noc,
                'listing_purpose' => $listing_purpose,
                'pre_after_notice_sel' => $pre_after_notice_sel,
                'selected_judges' => $selected_judges,
                'short_non_short_sel' => $short_non_short_sel,
                'ucode' => $this->session->get('login')['usercode'],
                'AllocationTp' => $this->AllocationTp, // Model
            ];

        return $this->advance_allocation_tp_processing($data); // call new function
    }


    function advance_allocation_tp_processing($data)
    {
        $q_next_dt = $data['q_next_dt'];
        $noc = $data['noc'];
        $q_usercode = $data['ucode'];
        $q_main_supp_flag = 1;
        $q_clno = 2;
        $finally_case_listed = 0;
        $listorder = f_selected_values($data['listing_purpose']);

        if ($listorder != "all") {
            $p_listorder = "AND h.listorder IN ($listorder)";
        } else {
            $p_listorder = "";
        }
        $pre_after_notice_sel = $data['pre_after_notice_sel'];

        $pre_after_notice_where_condition = "";
        if ($pre_after_notice_sel == 1) {
            $pre_after_notice_where_condition = " WHERE pre_notice = 1 ";
        } else if ($pre_after_notice_sel == 2) {
            $pre_after_notice_where_condition = " WHERE pre_notice = 2 ";
        } else {
            $pre_after_notice_where_condition = "";
        }
        $listorder_only_fix_dt = "";
        $ro_isnmd = $this->AllocationTp->getisNMD($q_next_dt);
        if (!empty($ro_isnmd)) {
            //$short_categoary_array = array(343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222);
            $judge_limit_detail = array();
            $presiding_judge = array();
            $rs = $this->AllocationTp->getPresidingJudge($q_next_dt, $data['selected_judges']); 
          

            if (!empty($rs))
            {
                foreach ($rs as $ro)
                {
                    $judge_group = $ro['p1'] . ',' . $ro['p2'] . ',' . $ro['p3'];
                    $judge_limit_detail[] = array($ro['p1'], $judge_group, $noc, $ro['listed']);
                    $presiding_judge[] = $ro['p1'];
                }
                $presiing_judge_str = implode(",", $presiding_judge);
                $rslt_is_printed = advance_cl_printed($q_next_dt); // Convert


                if ($rslt_is_printed == 0)
                {
                    //$short_non_short_sel = $data['short_non_short_sel'];
                    $prepone_loop = 2;
                    for ($p = 2; $p <= $prepone_loop; $p++)
                    {
                        if ($p == 2)
                        {
                          
                            $rs_p2 = $this->AllocationTp->getPresidingJudgeP2($q_next_dt, $noc); // Convert
                        
                            $count_rs_p2 = count($rs_p2);
                            if (!empty($rs_p2)) {
                                $presiding_judge_p2 = array();
                                $p2_coram_check = " OR (";
                                $p2_coram_check_where = " AND (";
                                $while_rop2 = 1;
                                foreach ($rs_p2 as $ro_p2) {
                                    $p2_coram_check .= " SPLIT_PART(h.coram,',',1) = '" . (int)$ro_p2['p1'] . "' OR SPLIT_PART(h.coram,',',1) = '" . (int)$ro_p2['p2'] . "'";
                                    $p2_coram_check_where .= "SPLIT_PART(h.coram, ',', 1)::INT IN (" . (int)$ro_p2['p1'] . ", " . (int)$ro_p2['p2'] . ") OR ";
                                    if ($ro_p2['p3'] > 0) {
                                        $p2_coram_check .= " OR SPLIT_PART(h.coram,',',1) = '" . (int)$ro_p2['p3'] . "'";
                                        $p2_coram_check_where .= " SPLIT_PART(h.coram,',',1) = '" . $ro_p2['p3'] . "' OR";
                                    }
                                    if ($count_rs_p2 != $while_rop2) {
                                        $p2_coram_check .= " OR ";
                                    }
                                    $while_rop2++;
                                    $presiding_judge_p2[] = "'" . $ro_p2['p1'] . "'";
                                }
                                $p2_coram_check .= " )";
                                $p2_coram_check_where .= " h.coram = '' OR h.coram is null OR h.coram = '0')";
                                $presiing_judge_str_p2 = implode(",", $presiding_judge_p2);
                                $rs_c = $this->AllocationTp->getIsPerson($p_listorder, $p2_coram_check_where, $pre_after_notice_where_condition); // Convert

                            } else {
                                break;
                            }
                        }

                        if (!empty($rs_c))
                        {
                            foreach ($rs_c as $row_c)
                            {
                                $coram = $row_c['coram'];
                                $row_coram = $this->AllocationTp->getNewCoarm($coram); // convert
                                if (!empty($row_coram)) {
                                    $coram = $row_coram['new_coram'];
                                    $coram_ex = explode(",", $coram ?? '');
                                } else {
                                    $coram = "";
                                }
                                $main_key = $row_c['main_key'];
                                $subhead = $row_c['subhead'];
                                $heardt_is_nmd = $row_c['is_nmd'];
                                $board_type = $row_c['board_type'];
                                $q_listorder = $row_c['listorder'];
                                $q_diary_no = $row_c['diary_no'];
                                $cat1 = $row_c['submaster_id'];
                                $possible_judges = $presiing_judge_str;
                                $binay_vl = "";
                                $binary_wt = array();
                                if ($row_c['diary_no'] == $row_c['main_key']) {
                                    $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                                } else {
                                    $dairy_with_conn_k = $q_diary_no;
                                }
                                $checked_notbefore_verify = "";
                                $checked_notbefore_verify = check_list_before_advance($dairy_with_conn_k, 'N');
                                $checked_notbefore_verify = rtrim(ltrim(trim($checked_notbefore_verify), ','), ',');
                                $tobelisted = 0;
                                $finally_listed = 0;
                                $if_listed_with_coram = 0;
                                $checked_before_verify = "";
                                for ($row = 0; $row < count($judge_limit_detail); $row++) {
                                    $judge_presiding_indv = $judge_limit_detail[$row][0];
                                    $judge_group_indv = $judge_limit_detail[$row][1];
                                    $to_be_listed_indv = $judge_limit_detail[$row][2];
                                    $listed_indv = $judge_limit_detail[$row][3];
                                    $checked_before_verify = check_list_before_advance($dairy_with_conn_k, 'B');
                                    if ($checked_before_verify == '-1') {
                                        if ($p == 1) {
                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                        }
                                        $finally_listed = 1;
                                        break;
                                    }
                                    if ($checked_before_verify != '-1' and $checked_before_verify != '') {
                                        $checked_before_verify_exploded = explode(",", $checked_before_verify);
                                        $judge_group_indv_exploded = explode(",", $judge_group_indv);
                                        $result_intersected = array_intersect($judge_group_indv_exploded, $checked_before_verify_exploded);
                                        if (count($checked_before_verify_exploded) == count($result_intersected)) {
                                            $tobelisted = 1;
                                            $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                            $result_before_not = array_intersect($judge_group_indv_exploded, $checked_notbefore_verify_exploded);
                                            if (count($result_before_not) === 0) {
                                                if ($listed_indv >= $to_be_listed_indv and $q_listorder != 4 and $q_listorder != 5 and $q_listorder != 7 and $row_c['subhead'] != 824 and $row_c['advocate_id'] != null) {
                                                    if ($p == 1) {
                                                        insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                                    }
                                                    $finally_listed = 1;
                                                    break;
                                                } else {
                                                    if ($cat1 == 239) {
                                                        if (!if_three_judge_cat_coram($judge_limit_detail[$row][1])) {
                                                            if ($p == 1) {
                                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Matter not listed due to 3jj bench not available in before judge');
                                                            }
                                                            $finally_listed = 1;
                                                            break;
                                                        }
                                                    }
                                                    $finally_case_listed = 1;
                                                    $updating_judge = explode(",", $judge_limit_detail[$row][1]);
                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                    if ($finally_case_listed == 1) {
                                                        $finally_listed = 1;
                                                        $judge_limit_detail[$row][3] += 1;
                                                    }
                                                }
                                            } else {
                                                if ($p == 1) {
                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                                }
                                                $finally_listed = 1;
                                                break;
                                            }
                                            break;
                                        } else {
                                        }
                                        if ($row == (count($judge_limit_detail) - 1)) {
                                            if ($p == 1) {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                            }
                                            $finally_listed = 1;
                                            break;
                                        }
                                    } //end of before entry check
                                    else if ($coram != null and $coram != 0 and $coram != '') {
                                        $coram_exploaded = explode(",", $coram);
                                        for ($judgeIndex = 0; $judgeIndex < count($coram_exploaded); $judgeIndex++) {
                                            $checkJudgeInCoram = $coram_exploaded[$judgeIndex];
                                            if ($checkJudgeInCoram > 0) {
                                                for ($judgeForCoram = 0; $judgeForCoram < count($judge_limit_detail); $judgeForCoram++) {
                                                    $judgeForCoram_presiding_indv = $judge_limit_detail[$judgeForCoram][0];
                                                    $judge_group_indv = $judge_limit_detail[$judgeForCoram][1];
                                                    $to_be_listed_indv = $judge_limit_detail[$judgeForCoram][2];
                                                    $listed_indv = $judge_limit_detail[$judgeForCoram][3];
                                                    if ($checkJudgeInCoram == $judgeForCoram_presiding_indv) {
                                                        $judgeForCoram_indv_to_convert_into_array = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                        $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                        $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);
                                                        if (count($result_array_intersect) > 0) {
                                                        } else {
                                                            if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0) {
                                                                if ($cat1 == 239) {
                                                                    if (!if_three_judge_cat_coram($judge_limit_detail[$judgeForCoram][1])) {
                                                                        if ($p == 1) {
                                                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Matter not listed due to 3jj bench not available in coram judge');
                                                                        }
                                                                        $finally_listed = 1;
                                                                        break (3);
                                                                    }
                                                                }
                                                                $updating_judge = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                                $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                                $finally_listed = 1;
                                                                $judge_limit_detail[$judgeForCoram][3] += 1;
                                                                break (3);
                                                            } else {
                                                                $finally_listed = 1;
                                                                if ($p == 1) {
                                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                                                }
                                                                break (3);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if ($finally_listed == 0) {
                                            for ($judgeIndex = 0; $judgeIndex < count($coram_exploaded); $judgeIndex++) {
                                                $checkJudgeInCoram = $coram_exploaded[$judgeIndex];
                                                if ($checkJudgeInCoram > 0) {
                                                    for ($judgeForCoram = 0; $judgeForCoram < count($judge_limit_detail); $judgeForCoram++) {
                                                        $judgeForCoram_presiding_indv = $judge_limit_detail[$judgeForCoram][0];
                                                        $judge_group_indv = $judge_limit_detail[$judgeForCoram][1];
                                                        $to_be_listed_indv = $judge_limit_detail[$judgeForCoram][2];
                                                        $listed_indv = $judge_limit_detail[$judgeForCoram][3];
                                                        $judge_group_indv_exploded = explode(",", $judge_group_indv);
                                                        $result_intersected = array_intersect($judge_group_indv_exploded, $coram_exploaded);
                                                        if ($result_intersected == true) {
                                                            if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0) {
                                                                $judgeForCoram_indv_to_convert_into_array = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                                $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);
                                                                if (count($result_array_intersect) > 0) {
                                                                } else {
                                                                    if ($cat1 == 239) {
                                                                        if (!if_three_judge_cat_coram($judge_limit_detail[$judgeForCoram][1])) {
                                                                            if ($p == 1) {
                                                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'due to 3jj coram');
                                                                            }
                                                                            $finally_listed = 1;
                                                                            break (2);
                                                                        }
                                                                    }
                                                                    $updating_judge = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                                    $finally_listed = 1;
                                                                    $judge_limit_detail[$judgeForCoram][3] += 1;
                                                                    break (2);
                                                                }
                                                            } else {
                                                                if ($p == 1) {
                                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                                                }
                                                                $finally_listed = 1;
                                                                break (2);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if ($finally_listed == 0) {
                                            if ($p == 1) {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ElIMINATED DUE TO NON AVAILABILITY OF CORAM JUDGE');
                                            }
                                            $finally_listed = 1;
                                            break;
                                        }
                                    }
                                } // end of for loop to check all courts
                                if ($finally_listed == 0 and $checked_before_verify == "") {
                                    if ($possible_judges != null) {
                                        $possible_judges_exploded = explode(",", $possible_judges);
                                        $remaining_judge_group = remove_exaust_limit_judge($judge_limit_detail, $possible_judges_exploded);
                                        $remaining_judge_group = remove_notbefore_judge($remaining_judge_group, $checked_notbefore_verify);
                                        $remaining_judge_group = msort($remaining_judge_group, '3', '1');
                                        $count_array_remaining_judge_group = (count($remaining_judge_group) - 1);
                                        $rand_remaining_judge_group_key = rand(0, $count_array_remaining_judge_group);
                                        $final_allocation_judges = [];
                                        if (!empty($remaining_judge_group)) {
                                            $final_allocation_judges = explode(",", $remaining_judge_group[$rand_remaining_judge_group_key][1]);
                                        }
                                        if (!empty($remaining_judge_group)) {
                                            $to_be_listed_indv = $remaining_judge_group[$rand_remaining_judge_group_key][2];
                                            $listed_indv = $remaining_judge_group[$rand_remaining_judge_group_key][3];
                                        }
                                        if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0) {
                                            if ($cat1 == 239 && !if_three_judge_cat_coram($remaining_judge_group[$rand_remaining_judge_group_key][1])) {
                                                if ($p == 1) {
                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'due to 3jj coram');
                                                }
                                                $finally_listed = 1;
                                            } else {
                                                $finally_listed = 1;
                                                if (!empty($final_allocation_judges)) {
                                                    $judge_limit_detail_key = findInMultiDimensionalArray($judge_limit_detail, '0', $final_allocation_judges[0]);
                                                    $updating_judge = explode(",", $judge_limit_detail[$judge_limit_detail_key][1]);
                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                    $judge_limit_detail[$judge_limit_detail_key][3] += 1;
                                                }
                                            }
                                        } else {
                                            if ($p == 1) {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                            }
                                        }
                                    } else {
                                        if ($p == 1) {
                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Category Not Allocated to any Judge');
                                        };
                                    }
                                }
                                if ($p == 2) {
                                    $rs_p2 = $this->AllocationTp->getAbbreviation($q_next_dt, $noc); // Convert
                                    if (!empty($rs_p2)) {
                                    } else {
                                        break;
                                    }
                                }
                            }
                            if ($finally_case_listed > 0)
                            {
                                echo "<div style='font-size:16px; color:green; text-align: center;'>Success.</div>";
                            }
                            if ($p == 2)
                            {
                                $sql3 = $this->AllocationTp->InsTransferOldComGenCases($q_next_dt); // convert
                                $sql4 = $this->AllocationTp->InsHeart($q_next_dt); // Convert
                                $sql4 = $this->AllocationTp->UpdateHeart($q_next_dt); // Convert
                            }
                        } else {

                            if ($p == 1) {
                                echo "Cases not found to list in pool";
                            }
                        }
                    } //end of prepone for loop

                }
                else
                {
                    echo "<br/><center><span style='color:red;'>YOU CAN NOT ALLOT CASES FOR DATED " . $q_next_dt . " BECAUSE ADVANCE LIST FINALIZED</span></center>";
                }
            }
            else
            {
                echo "No Records Found";
            }
        }
        else
        {
            echo "<span style='color:red;'><b>Please select working day / Contact to Computer Cell.</b></span><br>";
        }

        
    }// Function brakets
} // COntroller brakets
