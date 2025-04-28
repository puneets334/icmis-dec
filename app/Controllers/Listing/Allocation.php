<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\AdvocateModel;
use App\Models\Listing\CaseInfoModel;
use App\Models\Listing\AllocationTp;
use App\Models\Common\Dropdown_list_model;
use App\Models\Listing\Subheading;
use App\Models\Listing\AdvanceAllocated;
use App\Models\Listing\CaseType;
use App\Models\Listing\Submaster;
use App\Models\Listing\ListingPurpose;
use App\Models\Listing\Roster;
use App\Models\Listing\CaseAdd;
use App\Models\Listing\Heardt;
use App\Models\Listing\PrintModel;

use App\Models\CaseModel;
use App\Models\Listing\WorkingDaysModel;

class Allocation extends BaseController
{


    public $model;
    public $diary_no;
    public $CaseInfoModel;
    public $Subheading;
    public $Dropdown_list_model;
    public $CaseType;
    public $Submaster;
    public $ListingPurpose;
    public $AdvanceAllocated;
    public $WorkingDaysModel;
    public $CaseAdd;
    public $Roster;
    public $Heardt;
    public $AllocationTp;
    public $PrintModel;



    function __construct()
    {
        $this->CaseInfoModel = new CaseInfoModel();
        $this->Subheading = new Subheading();
        $this->CaseType = new CaseType();
        $this->Submaster = new Submaster();
        $this->ListingPurpose = new ListingPurpose();
        $this->AdvanceAllocated = new AdvanceAllocated();
        $this->WorkingDaysModel = new WorkingDaysModel();
        $this->CaseAdd = new CaseAdd();
        $this->Roster = new Roster();
        $this->Heardt = new Heardt();
        $this->AllocationTp = new AllocationTp();
        $this->Roster = new Roster();
        $this->PrintModel = new PrintModel();
        $this->session = session();
        ini_set('memory_limit', '4024M');
    }

    // START Listing=>Advance List->Advance Alloction
    public function advance_allocation()
    {
        $cur_ddt = date('Y-m-d', strtotime('+1 day'));
        $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
        $mf = "M";
        $board_type = "J";
        $allocation = $this->get_allocation_judge_advance_b($mf, $next_court_work_day, $board_type);

        $data = [
            'subheadings' =>  $this->Subheading->getActiveSubheadings(),
            'caseTypes' => $this->CaseType->getActiveCaseTypes(),
            'submasters' => $this->Submaster->getActiveSubmasters(),
            'purposes' => $this->ListingPurpose->getActivePurposes(),
            'next_court_work_day' => date("d-m-Y", strtotime($this->nextCourtWorkingDate($cur_ddt))),
            'allocation' => $allocation
        ];

        $data['advanceAllocated'] = $this->AdvanceAllocated;

        return view('Listing/advance_list/advance_allocation', $data);
    }
    private function nextCourtWorkingDate($current_date)
    {

        return date("Y-m-d", strtotime($current_date . ' +2 days'));
    }
    public function get_allocation_judges_m_advance()
    {
        $request = service('request');
        $params = $this->request->getGet();
        $p1 = $request->getGet('mainhead');
        $cldt = $request->getGet('list_dt');
        $board_type = $request->getGet('bench');
        $cldtFormatted = date('Y-m-d', strtotime($cldt));
        return $this->get_allocation_judge_advance_b($p1, $cldtFormatted, $board_type);
        // return $this->AdvanceAllocated->get_allocation_judge_advance_b($p1, $cldtFormatted, $board_type);

    }

    public function get_allocation_judge_advance_b($p1, $cldt, $board_type)
    {

        $db = \Config\Database::connect();
        $cldtMMDDYYYY = date('d-m-Y', strtotime($cldt));
        $cldt = date('Y-m-d', strtotime($cldt));
        $html = '';
        if ($p1 == "M") 
        {
            $m_f = "AND r.m_f = '1'";
            if ($board_type == 'R')
                $from_to_dt = "AND r.to_date IS NULL ";
            else
                $from_to_dt = "AND r.from_date = '$cldt' ";
        }
        else if ($p1 == "L")
        {
            $m_f = "AND r.m_f = '3'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }
        else if ($p1 == "S")
        {
            $m_f = "AND r.m_f = '4'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }
        else
        {
            $m_f = "AND r.m_f = '2'";
            $from_to_dt = "AND r.from_date = '$cldt' ";
        }

        $ro_isnmd = $this->AdvanceAllocated->getisNMD($cldt); 

        if (!empty($ro_isnmd) && isset($ro_isnmd['is_nmd']))
        {
            $is_nmd = $ro_isnmd['is_nmd'];
            
            if ($is_nmd == 1)
            {
                $html .= "<span style=\"color:blue ; font-weight: bold;\"><b>Ready to list Regular Day Cases</b></span><br>";
                
            }
            if ($is_nmd == 0)
            {
                $html .= "<span style=\"color:green;font-weight: bold;\"><b>Ready to List Misc. Day Cases</b></span><br>";
                
               
            }
        }
        else
        {
            echo "<span style=\"color:red; font-weight: bold;\">Not a Working Day</span><br>";
            return;
           
        }
      
        if ($ro_isnmd['is_nmd'] == 1) {

            $results = $this->AdvanceAllocated->isNMDOne($cldt);
        }
        if ($ro_isnmd['is_nmd'] == 0) {


            $results = $this->AdvanceAllocated->isNMDZero();
        }




        $totals = [
            'listed' => 0,
            'tp' => 0,
            'bail' => 0,
            'old_after_notice' => 0,
            'pre_notice' => 0,
            'after_notice' => 0,
        ];

        $judgeCounts = [];
        
        if (!empty($results)) {

            $html .= '<div id="prnnt2">
                    <fieldset>
                        <legend style="text-align:center;color:#4141E0; font-weight:bold;">
                            ADVANCE LIST ALLOCATION FOR DATED ' . esc($cldtMMDDYYYY) . '
                        </legend>

                        <div class="table-responsive">
                            <table border="1" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; vertical-align: top;">
                                            <input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);"> <b>All</b>
                                        </th>
                                        <th style="text-align: center; vertical-align: top;"> <b>Hon\'ble Judge</b></th>
                                        <th><b>To be Listed</b></th>
                                        <th> <b>TP</b></th>
                                        <th> <b>Bail</b></th>
                                        <th> <b>Old After Notice</b></th>
                                        <th> <b>Pre Notice Listed</b></th>
                                        <th> <b>After Notice Listed</b></th>
                                        <th> <b>Total Listed</b></th>
                                    </tr>
                                </thead>
                                <tbody>';
            $srno = 1;
            $old_limit1 = 0;
            $tot_listed = 0;
            $tot_tp = 0;
            $tot_bail = 0;
            $tot_Pre_Notice = 0;
            $tot_After_Notice = 0;

            foreach ($results as $row) {
                if (!isset($judgeCounts[$row["jcd"]])) {
                    $judgeCounts[$row["jcd"]] = 1;
                } else {
                    $judgeCounts[$row["jcd"]] += 1;
                }

                $row1 = $this->AdvanceAllocated->getListedData($row["jcd"], $row["p1"], $cldt);

                $html .= '<tr style="vertical-align: bottom;">
                                <td style="vertical-align: bottom;">
                                    <input type="checkbox" id="chkeeed" name="chk" value="' . esc($row["p1"]) . '|' . esc($row["jcd"]) . '|' . esc($judgeCounts[$row["jcd"]]) . '">
                                </td>
                                <td style="vertical-align: bottom;">' . esc($row['abbreviation']) . '</td>
                                <td style="vertical-align: bottom;">
                                    <select class="misc_selected_box make_zero" name="or_' . esc($row["p1"]) . '" id="or_' . esc($row["p1"]) . '" onchange="calc_tot(this.id)">
                                        ' . $this->generateOptions(301, $row['old_limit']) . '
                                    </select>
                                </td>
                                <td style="vertical-align: bottom;">' . (isset($row1['tp']) ? $row1['tp'] : 0) . '</td>
                                <td style="vertical-align: bottom;">' . (isset($row1['bail']) ? $row1['bail'] : 0) . '</td>
                                <td style="vertical-align: bottom;">' . (isset($row1['old_after_notice']) ? $row1['old_after_notice'] : 0) . '</td>
                                <td style="vertical-align: bottom;">' . (isset($row1['pre_notice']) ? $row1['pre_notice'] : 0) . '</td>
                                <td style="vertical-align: bottom;">' . (isset($row1['after_notice']) ? $row1['after_notice'] : 0) . '</td>
                                <td style="vertical-align: bottom;">' . (isset($row1['listed']) ? $row1['listed'] : 0) . '</td>
                            </tr>';

                $totals['listed'] += (isset($row1['listed']) ? $row1['listed'] : 0);
                $totals['tp'] += (isset($row1['tp']) ? $row1['tp'] : 0);
                $totals['bail'] += (isset($row1['bail']) ? $row1['bail'] : 0);
                $totals['old_after_notice'] +=  (isset($row1['old_after_notice']) ? $row1['old_after_notice'] : 0);
                $totals['pre_notice'] += (isset($row1['pre_notice']) ? $row1['pre_notice'] : 0);
                $totals['after_notice'] += (isset($row1['after_notice']) ? $row1['after_notice'] : 0);
            }

            $html .= '<tr style="font-weight:bold;">
                    <td colspan="3" style="text-align:right;">TOTAL</td>
                  
                    <td>' . esc($totals['tp']) . '</td>
                    <td>' . esc($totals['bail']) . '</td>
                    <td>' . esc($totals['old_after_notice']) . '</td>
                    <td>' . esc($totals['pre_notice']) . '</td>
                    <td>' . esc($totals['after_notice']) . '</td>
                    <td>' . esc($totals['listed']) . '</td>
                </tr>';

            $html .= '</tbody>
                </table>
            </div>
            </fieldset>
            </div>
            <input name="prnnt_btn" type="button" id="prnnt_btn" value="Print" class="btn btn-primary"><br>
            <input type="button" name="doa" id="doa" value="Do Allottment" class="btn btn-primary">';
            return $html;
        } else {
            return "<center>No Records Found</center>";
        }
    }



    public function generateOptions($max, $selectedValue)
    {
        $options = "";
        for ($i = 0; $i < $max; $i++) {
            $selected = ($i == $selectedValue) ? 'selected' : '';
            $options .= "<option value='$i' $selected>$i</option>";
        }
        return $options;
    }

    public function advance_allocation_process()
    {
        $request = service('request');

        $list_dt = $request->getPost('list_dt');
        $listing_purpose = $request->getPost('listing_purpose');
        $subhead = $request->getPost('subhead');
        $casetype = $request->getPost('casetype');
        $subject_cat = $request->getPost('subject_cat');
        $pre_after_notice_sel = $request->getPost('pre_after_notice_sel');
        $chked_jud_sel = $request->getPost('chked_jud_sel');
        $chked_jud_unsel = $request->getPost('chked_jud_unsel');
        $short_non_short_sel = $request->getPost('short_non_short_sel');
        $board_type = $request->getPost('bench');

        $data =
            [
                'list_dt' => $list_dt,
                'listing_purpose' => $listing_purpose,
                'subhead' => $subhead,
                'casetype' => $casetype,
                'subject_cat' => $subject_cat,
                'pre_after_notice_sel' => $pre_after_notice_sel,
                'chked_jud_sel' => $chked_jud_sel,
                'chked_jud_unsel' => $chked_jud_unsel,
                'short_non_short_sel' => $short_non_short_sel,
                'board_type' => $board_type,

            ];


        $data['ucode'] = $this->session->get('login')['usercode'];
        //return $this->AdvanceAllocated->advanceAllocationProcess($data);
        return $this->advanceAllocationProcessing($data);
    }


    

    public function advanceAllocationProcessing($data)
    {

        $db = \Config\Database::connect();
        $q_clno = 1;
        $q_main_supp_flag = 1;
        $mainhead = "M";
        $q_next_dt = date("Y-m-d", strtotime($data['list_dt']));
        $session = session();
        $q_usercode = $session->get('login')['usercode'];
        $listorder = f_selected_values1($data['listing_purpose']);
        
        if ($listorder != "all")
        {
            if ($data['listing_purpose'][0] == 'all')
            {
                unset($data['listing_purpose'][0]);
            }
            $listorderarray = f_selected_values1($data['listing_purpose']);
            $p_listorder = "AND h.listorder IN ($listorderarray)";
        }
        else
        {
            $p_listorder = "";
        }
        
        $subhead_arry = f_selected_values1($data['subhead']);
        if ($subhead_arry != "all")
        {
            if ($data['subhead'][0] == 'all')
            {
                unset($data['subhead'][0]);
            }
            $subhead_arry = f_selected_values1($data['subhead']);
            $subhead_select = "AND h.subhead IN ($subhead_arry)";
        }
        else
        {
            $subhead_select = "";
        }

        $case_type_arry = f_selected_values1($data['casetype']);
        if ($case_type_arry != 'all')
        {
            if ($data['casetype'][0] == 'all')
            {
                unset($data['casetype'][0]);
            }
            $case_type_arry = f_selected_values1($data['casetype']);
            $case_type_select = "AND m.active_casetype_id IN ($case_type_arry)";
        }
        else
        {
            $case_type_select = "";
        }

        $subject_cat_arry = f_selected_values1($data['subject_cat']);
        if ($subject_cat_arry != "all")
        {
            if ($data['subject_cat'][0] == 'all')
            {
                unset($data['subject_cat'][0]);
            }
            $case_type_arry = f_selected_values1($data['subject_cat']);
            $subject_cat_select = "AND mc.submaster_id IN ($subject_cat_arry)";
        }
        else
        {
            $subject_cat_select = "";
        }
        
        $pre_after_notice_sel = $data['pre_after_notice_sel'];
        $pre_after_notice_where_condition = "";
        if ($pre_after_notice_sel == 1)
        {
            $pre_after_notice_where_condition = " WHERE pre_notice = 1 ";
        }
        else if ($pre_after_notice_sel == 2)
        {
            $pre_after_notice_where_condition = " WHERE pre_notice = 2 ";
        }
        
        $listorder_only_fix_dt = "";
        
        $ro_isnmd = $this->AdvanceAllocated->getisNMD($q_next_dt);
        
        if (!empty($ro_isnmd))
        {
            if ($ro_isnmd['is_nmd'] == 1)
            {
                $misc_nmd_flag = 1;
            }
            if ($ro_isnmd['is_nmd'] == 0)
            {
                $misc_nmd_flag = 0;
            }
            $presiding_judge = [];
            $selected_judges_limit_detail = [];
            
            if (!empty($data['chked_jud_sel']))
            {
                $selectedJudgesDetails = explode('|JG', $data['chked_jud_sel']);
                foreach ($selectedJudgesDetails as $detail)
                {
                    if (!empty($detail))
                    {
                        $courtDetails = explode('|', $detail);
                        if (array_key_exists(0, $courtDetails) && array_key_exists(1, $courtDetails) && array_key_exists(3, $courtDetails))
                        {
                            $selected_judges_limit_detail[$courtDetails[0]] = [$courtDetails[0], $courtDetails[1], $courtDetails[3]];
                            $presiding_judge[] = $courtDetails[0];
                        }
                    }
                }
            }
            //END

            $short_categoary_array = [173, 176, 222];
            $judge_limit_detail = [];
            $is_nmd = ($ro_isnmd['is_nmd'] == 1); 
           
            $results = $this->AdvanceAllocated->getIsNumConditionBase($is_nmd ,$q_next_dt);
          
            if (!empty($results))
            {
                foreach ($results as $ro) {
                    $judge_group = $ro['p1'] . ',' . $ro['p2'] . ',' . $ro['p3'];
                    $judge_limit_detail[$ro['p1']] = [$ro['p1'], $judge_group, $ro['old_limit'], $ro['listed']];
                }

                // pr($presiding_judge);

                // foreach ($presiding_judge as $judge)
                // {
                //     if (isset($judge_limit_detail[$judge])) {
                //         if (isset($selected_judges_limit_detail[$judge])) {
                //             $selected_judges_limit_detail[$judge][] = $judge_limit_detail[$judge][3];
                //         }
                //     }
                // }

                foreach(array_keys($judge_limit_detail) as $key){
                    if(in_array($key,$presiding_judge)){
                        array_push($selected_judges_limit_detail[$key],$judge_limit_detail[$key][3]);
                    }
                }

                // pr($presiding_judge);

                $selected_judges_limit_detail = array_values($selected_judges_limit_detail);
                $presiing_judge_str = implode(",", $presiding_judge);
                $rslt_is_printed = advance_cl_printed($q_next_dt);

                // pr($presiing_judge_str);
               
                if ($rslt_is_printed == 0)
                {
                    $short_cat = "";
                    // $is_nmd_column_flag = "";
                    // $misc_nmd_flag = 1; //remove vkg
                    if ($misc_nmd_flag == 1)
                    {
                        $result = $this->AdvanceAllocated->getSno($q_next_dt);
                        
                        $is_nmd_column_flag = " ";
                        $short_cat = " (mc.submaster_id IN (173,176,222) OR h.subhead IN (804,831) ) ";
                    }
                    else
                    {
                        $is_nmd_column_flag = " ";
                        $short_cat = " true ";
                    }
                    $short_non_short_sel = $data['short_non_short_sel'];
                    $prepone_loop = 2;
                    for ($p = 1; $p <= $prepone_loop; $p++)
                    {
                       
                        if ($p == 1)
                        {
                            //Main query check with vijay sir
                            
                            $rs_c = $this->AdvanceAllocated->getIsPersonendOne($q_next_dt ,$presiing_judge_str , $misc_nmd_flag,$p_listorder, $subhead_select ,$case_type_select ,$subject_cat_select );
                           
                        }
                        if ($p == 2)
                        {
                           
                            if ($misc_nmd_flag == 1 or $misc_nmd_flag == 0)
                            {
                                if ($misc_nmd_flag == 1)
                                {
                                    $ro_isnmd= $this->AdvanceAllocated->miscNmdFlagOne($q_next_dt);
                                }
                                if ($misc_nmd_flag == 0)
                                {
                                    $ro_isnmd= $this->AdvanceAllocated->miscNmdFlagZero($q_next_dt);
                                }

                                // pr($ro_isnmd);
                               
                                //$count_rs_p2 = count($ro_isnmd);
                                if (!empty($ro_isnmd))
                                {
                                    $presiding_judge_p2 = array();
                                    $while_rop2 = 1;
                                    $rs_p2_data = $ro_isnmd;
                                    $p1_values = [];
                                    $p3_values = [];
                                    $p1_string = '';
                                    $p3_string = '';
                                    $p2_coram_check = '';
                                    $p2_coram_check_where = '';
                                    // pr($rs_p2_data);
                                    foreach ($rs_p2_data as $ro_p2)
                                    {
                                        // pr($ro_p2['p1']);
                                        $p1_values[] = isset($ro_p2['p1']) ? $ro_p2['p1'] : 0;
                                        if ((isset($ro_p2['p3'])) && ($ro_p2['p3'] > 0))
                                        {
                                            $p3_values[] = isset($ro_p2['p3']) ? $ro_p2['p3'] : 0;
                                        }
                                        $while_rop2++;
                                        $presiding_judge_p2[] = isset($ro_p2['p1']) ? $ro_p2['p1'] : 0;
                                    }
                                    // pr($presiding_judge_p2);

                                    if (!empty($p1_values))
                                    {
                                        $p1_string = implode(',', $p1_values);
                                        $p2_coram_check .= " (split_part(h.coram,',',1)::int IN (" . $p1_string . ")";
                                    }
                                    
                                    if (!empty($p3_values))
                                    {
                                        $p3_string = implode(',', $p3_values);
                                        if (!empty($p2_coram_check))
                                        {
                                            $p2_coram_check .= " OR split_part(h.coram,',',1)::int IN (" . $p3_string . ")";
                                        }
                                        else
                                        {
                                            $p2_coram_check .= " (split_part(h.coram,',',1)::int IN (" . $p3_string . ")";
                                        }
                                    }

                                    if (!empty($p2_coram_check))
                                    {
                                        $p2_coram_check .= ")";
                                        $p2_coram_check_where .= " and (" . $p2_coram_check . " OR h.coram = '' OR h.coram is null OR h.coram = '0')";
                                    }
                                    $presiing_judge_str_p2 = implode(",", $presiding_judge_p2);
                                    
                                    $rs_c= $this->AdvanceAllocated->getIsPersonendTwo($presiing_judge_str_p2 ,$misc_nmd_flag ,$q_next_dt ,$short_cat ,$pre_after_notice_where_condition);
                                   // pr($rs_c);
                                }
                                else
                                {
                                    break;
                                }
                            }
                            else
                            {
                                break;
                            }
                        }
                      

                        if (!empty($rs_c))
                        {
                         
                            foreach ($rs_c as $row_c)
                            {
                              
                                $coramString = $row_c['coram'];
                                $row_coram =$this->AdvanceAllocated->getCoarm($coramString);
                                if ($row_coram)
                                {
                                    $coram = $row_coram['new_coram'];
                                }
                                else
                                {
                                    $coram = "";
                                }
                                $main_key = $row_c['main_key'];
                                $subhead = $row_c['subhead'];
                                $board_type = $row_c['board_type'] ?? '';
                                $q_listorder = $row_c['listorder'];
                                $q_diary_no = $row_c['diary_no'];
                                $cat1 = $row_c['submaster_id'];
                                if ($row_c)
                                {
                                    if (isset($row_c['rid']) && $row_c['rid'] !== null)
                                    {
                                        $possible_judges = explode(',', $row_c['rid']);
                                    }
                                    else
                                    {
                                        $possible_judges = [];
                                    }
                                }
                                if ($cat1 == 341)
                                {
                                    $cji_code = f_get_cji_code();
                                    if (in_array($cji_code, $possible_judges))
                                    {
                                        $possible_judges = $cji_code;
                                    }
                                    else
                                    {
                                        $finally_listed = 1;
                                        break;
                                    }
                                }
                                //END
                                $binay_vl = "";
                                $binary_wt = array();
                                if ($row_c['diary_no'] == $row_c['main_key'])
                                {
                                    $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                                }
                                else
                                {
                                    $dairy_with_conn_k = $q_diary_no;
                                }
                                $checked_notbefore_verify = "";
                                $checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                                $checked_notbefore_verify = rtrim(ltrim(trim($checked_notbefore_verify), ','), ',');
                                $tobelisted = 0;
                                $finally_listed = 0;
                                $if_listed_with_coram = 0;
                                $checked_before_verify = "";
                               // pr($judge_limit_detail);
                                foreach ($judge_limit_detail as $key => $inner_array) {
                                    $judge_presiding_indv = $inner_array[0];
                                    $judge_group_indv = $inner_array[1];
                                    $to_be_listed_indv = $inner_array[2];
                                    $listed_indv = $inner_array[3];
                                    $checked_before_verify = check_list_before($dairy_with_conn_k, 'B');
                                    //if before entry available
                                    if ($checked_before_verify == '-1')
                                    {
                                        if ($p == 1)
                                        {
                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                        }
                                        $finally_listed = 1;
                                        break;
                                    }
                                    if ($checked_before_verify != '-1' and $checked_before_verify != '')
                                    {
                                        $checked_before_verify_exploded = explode(",", $checked_before_verify);
                                        $judge_group_indv_exploded = explode(",", $judge_group_indv);
                                        $result_intersected = array_intersect($judge_group_indv_exploded, $checked_before_verify_exploded);
                                        if (count($checked_before_verify_exploded) == count($result_intersected))
                                        {
                                            $tobelisted = 1;
                                            $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                            $result_before_not = array_intersect($judge_group_indv_exploded, $checked_notbefore_verify_exploded);
                                            if (count($result_before_not) === 0)
                                            {
                                                if ($listed_indv >= $to_be_listed_indv and $q_listorder != 4 and $q_listorder != 5 and $q_listorder != 7 and $row_c['subhead'] != 824 and $row_c['advocate_id'] != null)
                                                {
                                                    if ($p == 1)
                                                    {
                                                        insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                                    }
                                                    $finally_listed = 1;
                                                    break;
                                                }
                                                else
                                                {
                                                    if ($cat1 == 239)
                                                    {
                                                        if (!if_three_judge_cat_coram($inner_array[1]))
                                                        {
                                                            if ($p == 1) {
                                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Matter not listed due to 3jj bench not available in before judge');
                                                            }
                                                            $finally_listed = 1;
                                                            break;
                                                        }
                                                    }
                                                    $finally_case_listed = 1;
                                                    $updating_judge = explode(",", $inner_array[1]);
                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                    if ($finally_case_listed == 1)
                                                    {
                                                        $finally_listed = 1;
                                                        $inner_array[3] += 1;
                                                      
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if ($p == 1)
                                                {
                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                                }
                                                $finally_listed = 1;
                                                break;
                                            }
                                            break;
                                        }
                                        else
                                        {
                                        }
                                        if ($inner_array == (count($judge_limit_detail) - 1))
                                        {
                                            if ($p == 1)
                                            {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                            }
                                            $finally_listed = 1;
                                            break;
                                        }
                                    } else if ($coram != null and $coram != 0 and $coram != '')
                                    {
                                        $coram_exploaded = explode(",", $coram);
                                        for ($judgeIndex = 0; $judgeIndex < count($coram_exploaded); $judgeIndex++)
                                        {
                                            $checkJudgeInCoram = $coram_exploaded[$judgeIndex];
                                            if ($checkJudgeInCoram > 0)
                                            {
                                                foreach ($judge_limit_detail as $key => $judgeForCoram)
                                                {
                                                    $judgeForCoram_presiding_indv = $judgeForCoram[0];
                                                    $judge_group_indv = $judgeForCoram[1];
                                                    $to_be_listed_indv = $judgeForCoram[2];
                                                    $listed_indv = $judgeForCoram[3];
                                                    if ($checkJudgeInCoram == $judgeForCoram_presiding_indv)
                                                    {
                                                        $judgeForCoram_indv_to_convert_into_array = explode(",", $judgeForCoram[1]);
                                                        $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                        $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);
                                                        if (count($result_array_intersect) > 0)
                                                        {
                                                        }
                                                        else
                                                        {
                                                            if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0)
                                                            {
                                                                if ($cat1 == 239)
                                                                {
                                                                    if (!if_three_judge_cat_coram($judgeForCoram[1]))
                                                                    {
                                                                        if ($p == 1)
                                                                        {
                                                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Matter not listed due to 3jj bench not available in coram judge');
                                                                        }
                                                                        $finally_listed = 1;
                                                                        break (3);
                                                                    }
                                                                }
                                                                $updating_judge = explode(",", $judgeForCoram[1]);
                                                               
                                                                $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                              
                                                                $finally_listed = 1;
                                                                $judgeForCoram[3] += 1;
                                                                break (3);
                                                            }
                                                            else
                                                            {
                                                                $finally_listed = 1;
                                                                if ($p == 1)
                                                                {
                                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                                                }
                                                                break (3);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if ($finally_listed == 0)
                                        {
                                            for ($judgeIndex = 0; $judgeIndex < count($coram_exploaded); $judgeIndex++)
                                            {
                                                $checkJudgeInCoram = $coram_exploaded[$judgeIndex];
                                                if ($checkJudgeInCoram > 0)
                                                {
                                                    foreach ($judge_limit_detail as $judgeForCoram)
                                                    {
                                                        $judgeForCoram_presiding_indv = $judgeForCoram[0];
                                                        $judge_group_indv = $judgeForCoram[1];
                                                        $to_be_listed_indv = $judgeForCoram[2];
                                                        $listed_indv = $judgeForCoram[3];
                                                        $judge_group_indv_exploded = explode(",", $judge_group_indv);
                                                        $result_intersected = array_intersect($judge_group_indv_exploded, $coram_exploaded);
                                                        if ($result_intersected == true)
                                                        {
                                                            if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0)
                                                            {
                                                                $judgeForCoram_indv_to_convert_into_array = explode(",", $judgeForCoram[1]);
                                                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                                $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);
                                                                if (count($result_array_intersect) > 0)
                                                                {}
                                                                else
                                                                {
                                                                    if ($cat1 == 239)
                                                                    {
                                                                        if (!if_three_judge_cat_coram($judgeForCoram[1]))
                                                                        {
                                                                            if ($p == 1)
                                                                            {
                                                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'due to 3jj coram');
                                                                            }
                                                                            $finally_listed = 1;
                                                                            break (2);
                                                                        }
                                                                    }
                                                                    $updating_judge = explode(",", $judgeForCoram[1]);
                                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                                    $finally_listed = 1;
                                                                    $judgeForCoram[3] += 1;
                                                                    break (2);
                                                                }
                                                            }
                                                            else
                                                            {
                                                                if ($p == 1)
                                                                {
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
                                        if ($finally_listed == 0)
                                        {
                                            if ($p == 1)
                                            {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'ElIMINATED DUE TO NON AVAILABILITY OF CORAM JUDGE');
                                            }
                                            $finally_listed = 1;
                                            break;
                                        }
                                    }
                                }
                                if ($finally_listed == 0 and $checked_before_verify == "")
                                {
                                    if ($possible_judges != null)
                                    {
                                        $possible_judges_exploded = explode(",", $possible_judges);
                                        $remaining_judge_group = remove_exaust_limit_judge($judge_limit_detail, $possible_judges_exploded);
                                        $remaining_judge_group = remove_notbefore_judge($remaining_judge_group, $checked_notbefore_verify);
                                        $remaining_judge_group = msort($remaining_judge_group, '3', '1');
                                        $count_array_remaining_judge_group = (count($remaining_judge_group) - 1);
                                        $rand_remaining_judge_group_key = rand(0, $count_array_remaining_judge_group);
                                        $final_allocation_judges = explode(",", $remaining_judge_group[$rand_remaining_judge_group_key][1]);
                                        $to_be_listed_indv = $remaining_judge_group[$rand_remaining_judge_group_key][2];
                                        $listed_indv = $remaining_judge_group[$rand_remaining_judge_group_key][3];
                                        if ($to_be_listed_indv > $listed_indv or $q_listorder == 4 or $q_listorder == 5 or $q_listorder == 7 or $row_c['subhead'] == 824 or $row_c['advocate_id'] > 0)
                                        {
                                            if ($cat1 == 239 && !if_three_judge_cat_coram($remaining_judge_group[$rand_remaining_judge_group_key][1]))
                                            {
                                                if ($p == 1)
                                                {
                                                    insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'due to 3jj coram');
                                                }
                                                $finally_listed = 1;
                                            }
                                            else
                                            {
                                                $finally_listed = 1;
                                                $judge_limit_detail_key = findInMultiDimensionalArray($judge_limit_detail, '0', $final_allocation_judges[0]);
                                                $updating_judge = explode(",", $judge_limit_detail[$judge_limit_detail_key][1]);
                                                $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                $judge_limit_detail[$judge_limit_detail_key][3] += 1;
                                            }
                                        }
                                        else
                                        {
                                            if ($p == 1)
                                            {
                                                insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'DUE TO EXCESS MATTERS');
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if ($p == 1)
                                        {
                                            insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'A', 'Category Not Allocated to any Judge');
                                        }
                                    }
                                }

                                if ($p == 2)
                                {
                                    if ($misc_nmd_flag == 1)
                                    {
                                        $rs_p2 =$this->AdvanceAllocated->miscNmdFlagOne($q_next_dt);
                                    }
                                    if ($misc_nmd_flag == 0)
                                    {
                                        $rs_p2 =$this->AdvanceAllocated->miscNmdFlagZero($q_next_dt);
                                    }
                                    if (!empty($rs_p2))
                                    {
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                            }
                            if ($p == 2)
                            {
                                //PREPONEMENT
                                $res3 = $this->AdvanceAllocated->insertTransferOldComGenCases($q_next_dt);
                                $res4 = $this->AdvanceAllocated->insLastHeardt($q_next_dt);
                               
                                $res5 = $this->AdvanceAllocated->updateHeardt($q_next_dt);
                            }
                             if ($p == 1)
                            {
                                echo "<center><span style='color: green;'>Advance list allocation done successfully for Dated " . $data['list_dt'] . " . </span></center>";
                                $res_eliminate_print = $this->AdvanceAllocated->getEliminatePrint($q_next_dt);
                                if (!empty($res_eliminate_print))
                                {
                                    ?>
                                    <div id="prnnt" style="text-align: center">
                                        <style>
                                            #customers {
                                                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                                                border-collapse: collapse;
                                            }

                                            #customers td,
                                            #customers th {
                                                border: 1px solid #ddd;
                                                padding: 8px;
                                            }

                                            #customers tr:nth-child(even) {
                                                background-color: #f2f2f2;
                                            }

                                            #customers tr:hover {
                                                background-color: #ddd;
                                            }

                                            #customers th {
                                                padding-top: 12px;
                                                padding-bottom: 12px;
                                                text-align: left;
                                                background-color: #4CAF50;
                                                color: white;
                                            }
                                        </style>
                                        <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ON <?php echo $_POST['list_dt']; ?>
                                            , NOT LISTED IN ADVANCE LIST DUE TO COMPELLING REASONS</span>
                                        <table id="customers" align="center">
                                            <table id="customers" width="100%">
                                                <tr>
                                                    <th width="6%" style="text-align: center; font-weight: bold;">SNo</th>
                                                    <th width="22%" style="text-align: center; font-weight: bold;">Case No./Diary No.</th>
                                                    <th width="22%" style="text-align: center; font-weight: bold;">Cause Title</th>
                                                    <th width="10%" style="text-align: center; font-weight: bold;">Coram</th>
                                                    <th width="10%" style="text-align: center; font-weight: bold;">Purpose of Listing</th>
                                                    <th width="15%" style="text-align: center; font-weight: bold;">Reason</th>
                                                    <th width="15%" style="text-align: center; font-weight: bold;">Before / Not Before</th>
                                                </tr>
                                                <?php
                                                $sno_elimi = 1;
                                                foreach ($res_eliminate_print as $row_eliminate_print)
                                                {
                                                ?>
                                                    <tr>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $sno_elimi++; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['reg_no_display'] . " @ " . $row_eliminate_print['diary_no']; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['res_name'] . " <br>Vs<br> " . $row_eliminate_print['pet_name']; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['judge_coram']; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['purpose']; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['reason']; ?> </td>
                                                        <td style='text-align: left; vertical-align: top;'>
                                                            <?php
                                                            if ($row_eliminate_print['diary_no'] == $row_eliminate_print['conn_key'])
                                                            {
                                                                $dairy_with_conn_k = f_cl_conn_key($row_eliminate_print['diary_no']);
                                                            }
                                                            else
                                                            {
                                                                $dairy_with_conn_k = $row_eliminate_print['diary_no'];
                                                            }
                                                            $res_nnn = $this->AdvanceAllocated->getDairyWithConn_k($dairy_with_conn_k);
                                                            if (!empty($res_nnn))
                                                            {
                                                                foreach ($res_nnn as $row_nnn) {
                                                                    if ($row_nnn['nb_remark'] == 'N')
                                                                    {
                                                                        echo "<span style='color:red;'>Not Before : </span>";
                                                                    }
                                                                    if ($row_nnn['nb_remark'] == 'B')
                                                                    {
                                                                        echo "<span style='color:green;'>Before : </span>";
                                                                    }
                                                                    echo $row_nnn['judge_name'] . " in diary no. " . $row_nnn['diary_no'] . "<br>";
                                                                }
                                                            }


                                                            ?>
                                                        </td>

                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </table>
                                    </div>
                                    <input name="prnnt1" type="button" id="prnnt1" value="Print">
                                    <?php
                                }
                            }
                        }
                        else
                        {
                            if ($p == 1)
                            {
                              
                                echo "<span style=\"color:red; font-weight: bold;\">Cases not found to list in pool</span><br>";
                            }
                        }
                    }
                }
                else
                {
                    echo "<br/><center><span style=\"color:red;font-weight: bold;\">YOU CAN NOT ALLOT CASES FOR DATED " . $data['list_dt'] . " BECAUSE ADVANCE LIST FINALIZED</span></center>";
                }
            }
            else
            {
                echo "<span style=\"color:red; font-weight: bold;\">No Records Found</span><br>";
            }
        }
        else
        {
            echo "<span style=\"color:red;font-weight: bold;\"><b>Please select working day / Contact to Computer Cell.</b></span><br>";
        }
    }


//End Listing=>Advance List->Advance Alloction





    //Listing=>Allocation
    public function index_a()
    {


        $cur_ddt = date('Y-m-d', strtotime('+1 day'));

        $data = [
            'subheadings' =>  $this->Subheading->getActiveSubheadings(),
            'caseTypes' => $this->CaseType->getActiveCaseTypes(),
            'submasters' => $this->Submaster->getActiveSubmasters(),
            'purposes' => $this->ListingPurpose->getActivePurposes(),
            'next_court_work_day' => $this->nextCourtWorkingDate($cur_ddt),
            'getKeywords' => $this->AdvanceAllocated->getKeywords(),
            'getDocs' => $this->AdvanceAllocated->getDocs(),
            'getActs' => $this->AdvanceAllocated->getActs(),

        ];

        return view('Listing/allocation/index_a', $data);
    }



    public function get_allocation_judges_p()
    {
        $request = service('request');
        $p1 = $request->getPost('mainhead');
        $cldt = $request->getPost('list_dt');
        $jud_count = $request->getPost('sitting_judges');
        $board_type = $request->getPost('bench');
        $cldt = date('Y-m-d', strtotime($cldt));
        $data['allocation'] = $this->Roster->getAllocationJudge($p1, $cldt, $jud_count, $board_type);
        $data['cldt'] = $cldt;
        $data['p1'] = $p1;
        $data['board_type'] = $board_type;
        $data['roster'] = $this->Roster;

        return view('Listing/allocation/allocation_judge', $data);
    }

    public function get_ros_to_tans_p($p1, $cldt, $jud_count, $board_type)
    {
        $data['judges'] = $this->Roster->getJudgeRoster($p1, $cldt, $board_type);
        return view('Listing/allocation/judge_roster_view', $data);
    }

    public function transfer_without_coram_check_bk()
    {
        $request = service('request');
        set_time_limit(25000);

        $session = session();
        //$ucode = $session->get('dcmis_user_idd');
        $ucode = session()->get('login')['usercode'];
        $output = "";
        $total_case_listed = 0;
        $listing_dt_from = $request->getPost('list_dt_from');
        $listing_dt = $request->getPost('list_dt');
        $sitting_judges = $request->getPost('sitting_judges');
        $mainhead = $request->getPost('mainhead');
        $is_nmd = $request->getPost('is_nmd') == '0' ? "" : " AND h.is_nmd = '" . $request->getPost('is_nmd') . "'";
        $listorder = $request->getPost('listing_purpose');
        //$order_by = "CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";
        $order_by = "CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) ASC, CAST(LEFT(m.diary_no::TEXT,LENGTH(m.diary_no::TEXT)-4) AS INTEGER) ASC";

        $query = $this->AllocationTp->getCases1($listing_dt_from, $listing_dt, $is_nmd, $listorder, $mainhead, $order_by);

        if ($query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $output .= "<tr><td>{$row->diary_no}</td><td>{$row->short_description}</td></tr>"; // Example output
                $total_case_listed++;
            }
        }

        // Listing/allocation/judge_roster_view

        return view('Listing/allocation/result', ['output' => $output, 'total_case_listed' => $total_case_listed]);
    }

    public function transfer_asitis()
    {
        $request = service('request');
        //$ucode = $this->session->get('dcmis_user_idd');
        $ucode = session()->get('login')['usercode'];
        $listing_dt_from = $request->getPost('list_dt_from');
        $listing_dt = $request->getPost('list_dt');
        $main_supp = $request->getPost('main_supp');
        $partno = $request->getPost('partno');
        $mainhead = $request->getPost('mainhead');
        $chked_jud = rtrim($request->getPost('chked_jud'), "JG");
        $chked_jg_arry = explode("JG", $chked_jud);
        $chked_jg_arry_to = explode("|", $chked_jg_arry[0]);
        $from_tran_jd_rs = explode("|", $request->getPost('from_tran_jd_rs'));
        $from_tran_part_no_fr = $request->getPost('from_tran_partno');
        $bench = $request->getPost('bench');

        // Perform database operations
        return $this->AllocationTp->transferCases($listing_dt_from, $listing_dt, $main_supp, $partno, $mainhead, $chked_jg_arry_to, $from_tran_jd_rs, $ucode, $from_tran_part_no_fr, $bench);

        //return view('Listing/allocation/transfer_asitis_result', ['output' => $output]);
    }

    public function do_allotment()
    {
        $request = service('request');
        $is_nmd = $request->getPost('is_nmd');
        $from_yr = $request->getPost('from_yr');
        $to_yr = $request->getPost('to_yr');
        $civil_criminal = $request->getPost('civil_criminal');
        $bench = $request->getPost('bench');

        $data = [
            'is_nmd' => isset($is_nmd) ? $is_nmd : null,
            'from_yr' => $from_yr ?? null,
            'to_yr' => $to_yr ?? null,
            'civil_criminal' => $civil_criminal ?? null,
            'param_bench' => $bench ?? null,
            'listing_dt_from' => $request->getPost('list_dt_from'),
            'listing_dt' => $request->getPost('list_dt'),
            'sitting_judges' => $request->getPost('sitting_judges'),
            'listing_purpose' => $request->getPost('listing_purpose'),
            'mainhead' => $request->getPost('mainhead'),
            'diary_reg' => $request->getPost('diary_reg'),
            'subhead' => $request->getPost('subhead'),
            'subject_cat' => $request->getPost('subject_cat'),
            'kword' => $request->getPost('kword'),
            'ia' => $request->getPost('ia'),
            'act' => $request->getPost('act'),
            'section' => $request->getPost('section'),
            'reg_unreg' => $request->getPost('reg_unreg'),
            'case_type' => $request->getPost('case_type'),
            'get_chked_jud' => $request->getPost('chked_jud'),
            'main_supp' => $request->getPost('main_supp'),
            'md_name' => $request->getPost('md_name'),
            'partno' => $request->getPost('partno'),
            'chk_tr' => $request->getPost('chk_tr'),
            'from_tran_partno' => $request->getPost('from_tran_partno'),
            'get_from_tran_jd_rs' => $request->getPost('from_tran_jd_rs'),
            'get_noc' => $request->getPost('noc'),
        ];
        $data['ucode'] = $this->session->get('login')['usercode'];
        return $this->AllocationTp->doAllocation($data);
    }


    public function get_listing_purps()
    {
        $request = service('request');

        $main_supp = $request->getPost('main_supp');

        $data['purposes'] = $this->ListingPurpose->getListingPurposes($main_supp);
        $data['main_supp'] = $main_supp;

        return view('Listing/allocation/listing_purposes', $data);
    }







    public function get_records()
    {
        $request = service('request');
        $params = [
            'is_nmd' => $request->getPost('is_nmd'),
            'list_dt' => $request->getPost('list_dt'),
            'mainhead' => $request->getPost('mainhead'),
            'main_supp' => $request->getPost('main_supp'),
            'forFixedDate' => $request->getPost('forFixedDate'),
            'from_yr' => $request->getPost('from_yr'),
            'to_yr' => $request->getPost('to_yr'),
            'bench' => $request->getPost('bench'),
            'pool_adv' => $request->getPost('pool_adv'),
            'md_name' => $request->getPost('md_name'),
            'listing_purpose' => $request->getPost('listing_purpose'),
            'civil_criminal' => $request->getPost('civil_criminal'),
            'subhead' => $request->getPost('subhead'),
            'subject_cat' => $request->getPost('subject_cat'),
            'kword' => $request->getPost('kword'),
            'ia' => $request->getPost('ia'),
            'act' => $request->getPost('act'),
            'reg_unreg' => $request->getPost('reg_unreg'),
            'case_type' => $request->getPost('case_type'),
            'roster_judges_id' => $request->getPost('roster_judges_id'),
            'part_no' => $request->getPost('part_no'),
            'section' => $request->getPost('section')
        ];
        $data['cases'] = $this->CaseAdd->getCasesAdd($params);
        $diaryNumbers = array_column($data['cases'], 'diary_no');
        $data['ropOrders'] = $this->CaseAdd->getRopOrders($diaryNumbers);
        if (!isset($data['cases'])) {
            $data['cases'] = [];
        }

        if (!isset($data['ropOrders'])) {
            $data['ropOrders'] = [];
        }
        $data['params'] = $params;
        $data['caseAddModel'] = $this->CaseAdd;

        return view('Listing/allocation/get_records', $data);
    }

    //vkg
    // Allocation=>Regular
    public function regular()
    {
        $cur_ddt = date('Y-m-d', strtotime('+1 day'));

        $data = [
            'subheadings' =>  $this->Subheading->getActiveSubheadings(),
            'caseTypes' => $this->CaseType->getActiveCaseTypes(),
            'submasters' => $this->Submaster->getActiveSubmasters(),
            'purposes' => $this->ListingPurpose->getActivePurposes(),
            'next_court_work_day' => $this->nextCourtWorkingDate($cur_ddt),
            'getKeywords' => $this->AdvanceAllocated->getKeywords(),
            'getDocs' => $this->AdvanceAllocated->getDocs(),
            'getActs' => $this->AdvanceAllocated->getActs(),

        ];

        return view('Listing/Regular/regular', $data);
    }



    public function get_allocation_judges_final()
    {
        $cldt = $this->request->getGet('list_dt');
        $p1 = $this->request->getGet('mainhead');
        $board_type = $this->request->getGet('bench');


        if (empty($cldt) || empty($p1) || empty($board_type)) {
            return 'Invalid request data';
        }

        $allocationData = $this->Roster->getJudgeAllocation($p1, $cldt, $board_type);

        $data = [
            'allocationData' => $allocationData,
            'cldt' => date('Y-m-d', strtotime($cldt)),
            'p1' => $p1
        ];
        $data['roster'] = $this->Roster;

        return view('Listing/Regular/allocation_judge_final_b', $data);
    }

    public function coram_q_r_b()
    {
        $request = service('request');
        $response = service('response');
        $list_dt = $request->getPost('list_dt');
        $q_next_dt = date("Y-m-d", strtotime($request->getPost('list_dt')));
        $mainhead = $request->getPost('mainhead');
        $noc = $request->getPost('noc');
        $partno = $request->getPost('partno');
        $roster_selected = $request->getPost('cchk_sel');

        $explode_rs = explode("JG", $roster_selected);
        $chked_jud_unsel = $request->getPost('chked_jud_unsel');
        $cars = array();
        $md_module_id = "16";
        $cchk_sel = $request->getPost('cchk_sel');
        $listing_purpose = $request->getPost('listing_purpose');
        $listorder = $this->f_selected_values($listing_purpose);
        $main_supp = $request->getPost('main_supp');
        $q_usercode = session()->get('login')['usercode'];
        if ($mainhead == 'M') {
            $m_ff = "1";
        }
        if ($mainhead == 'F') {
            $m_ff = "2";
        }


        $cars = [];
        $sel_ros_id_for_cl_print = "";

        foreach ($explode_rs as $key => $rs) {
            $explode_rs_jg = explode("|", $rs);
            if (count($explode_rs_jg) >= 5) {
                $cars[] = [$explode_rs_jg[1], $explode_rs_jg[0], "Y", $explode_rs_jg[3], $explode_rs_jg[4]];
                $sel_ros_id_for_cl_print .= $explode_rs_jg[1] . ",";
            } 
        }


        $explode_not_rs = explode("JG", $chked_jud_unsel);
        $not_sel_rs = [];

        foreach ($explode_not_rs as $key => $not_rs) {
            if (!empty($not_rs)) {
                $explode_not_rs_jg = explode("|", $not_rs);
                $not_sel_rs[$explode_not_rs_jg[1]] = $explode_not_rs_jg[0];
                $cars[] = [$explode_not_rs_jg[1], $explode_not_rs_jg[0], "N"];
            }
        }
        $judges_coram = "";
        foreach ($cars as $car) {
            $ex_cr22 = explode(",", $car[1]);
            foreach ($ex_cr22 as $judge_id) {
                if ($car[2] == "Y") {
                    $judges_coram .= "FIND_IN_SET(" . intval($judge_id) . ", coram) OR ";
                }
            }
        }
        $all_sel_ros_id = rtrim($sel_ros_id_for_cl_print, ",");

        $isPrinted = $this->PrintModel->isCaseListPrinted($q_next_dt, $partno, $mainhead, $all_sel_ros_id);

        if ($isPrinted) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'The case list is already printed.']);
        } else {

            return $this->getAllocationData($mainhead, $q_next_dt, $m_ff);
        }
    }
    public function getAllocationData($mainhead, $q_next_dt, $m_ff)
    {

        $p_listorder = '';
        $purposeData = $this->ListingPurpose->getPurposeCodes($p_listorder);
        $allocationData = [];
        foreach ($purposeData as $ro_pr) {
            $sell_roster_id = "1,2,3";
            $main_supp = 1;
            $allocationDetails = $this->ListingPurpose->getAllocationDetails(
                $sell_roster_id,
                $m_ff,
                $q_next_dt,
                $mainhead,
                $main_supp,
                $ro_pr['code']
            );
            $allocationData[] = $allocationDetails;
        }
        return view('Listing/Regular/regular_view', [
            'purposeData' => $purposeData,
            'allocationData' => $allocationData
        ]);
    }


    private function f_selected_values($parm1)
    {
        if ((count($parm1) > 1) && $parm1[0] == 'all') {
            unset($parm1[0]);
        }
        if (is_array($parm1)) {
            return implode(",", $parm1);
        }
        return '';
    }


    //  Allocation=>Single Judge


    public function single_judge()
    {
        return view('Listing/SingleJudge/single_judge');
    }

    public function singleJudgeFinalGet()
    {
        $request = service('request');
        $nextDate = date('Y-m-d', strtotime($request->getPost('next_dt')));


        $data['post_data'] = $request->getPost();
        $data['roster'] = $this->AllocationTp->getSingleJudgeRoster($nextDate);
        $data['listed_cases_count'] = $this->AllocationTp->getSingleJudgeFinalAllocationCount($nextDate);
        $data['case_in_pool'] = $this->AllocationTp->getSingleJudgeFinalPoolMain($nextDate);
        $data['fresh_cases_in_pool'] = $this->AllocationTp->getSingleJudgeFinalFreshCasesPool($nextDate);
        $data['listing_purpose'] = $this->ListingPurpose->getListingPurpose();
        return view('Listing/SingleJudge/single_judges_final_allocation_inputs', $data);
    }


    public function singleJudgeFinalAllocationAction()
    {
        $request = service('request');
        $post =$request->getPost();
        if (empty($post['chk_roster'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Roster required to be selected']);
        } elseif (empty($post['chk_lp'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Purpose of Listing Required']);
        } elseif (empty($post['next_dt_selected'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Listing date Required']);
        } elseif (empty($post['part_no'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Part No. Required']);
        } elseif (empty($post['main_supp'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Main / Supplementary Required']);
        } elseif (empty($post['number_of_cases'])) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'No. of Cases to list Required']);
        } else {
            $selectedJudgeCount = count($post['chk_roster']);
            $rosterIdImploded = implode(',', $post['chk_roster']);
            $isListPrinted = $this->Heardt->validate_final_cl_printed(date('Y-m-d', strtotime($post['next_dt_selected'])), $post['part_no'], 'M', $rosterIdImploded);
            if ($isListPrinted) {
                return $this->response->setJSON(['status' => 'error', 'msg' => 'List Already Published']);
            } else {
                $selectedJudges = $update_field = $selected_judges2_temp = [];
                $rosterOfSelectedDate = $this->Heardt->singleJudgeRosterDetails($post['next_dt_selected'], $rosterIdImploded, 'S');
                $allJudgesCodeOfDay = ""; //judges selected by users
                foreach ($rosterOfSelectedDate as $row) {
                    if ($row['is_selected'] == 1) {
                        $allJudgesCodeOfDay .= $row['judge_id'] . ",";
                        $selectedJudges[$row['judge_id']] = [
                            'roster_id' => $row['id'],
                            'judge' => $row['judge_id'],
                            'limit' => $post['number_of_cases'],
                            'allocated' => 0
                        ]; //judge, tobe list, total listed
                    }
                }
                unset($row);
                $allJudgesCodeOfDay = rtrim($allJudgesCodeOfDay, ",");
                $inArr = [
                    'next_dt' => date('Y-m-d', strtotime($post['next_dt_selected'])),
                    'listorder' => implode(',', $post['chk_lp']),
                    'main_supp' => $post['main_supp'],
                    'number_of_cases' => $post['number_of_cases']
                ];

                $otherGroupJudges = $this->CaseInfoModel->getOtherGroupSingleJudgeNominatedByJCode($allJudgesCodeOfDay);
                if (is_array($otherGroupJudges)) {
                    $otherGroupJudgeArray = $otherGroupJudges;
                } else {
                    $otherGroupJudgeArray = explode(",", $otherGroupJudges);
                }
                

                $caseAvailableInPool = $this->CaseInfoModel->singleJudgeFinalProcessGetCases($inArr);
                if ($caseAvailableInPool) {
                    $itemNumber = $totalCasesListed = $totalCasesEliminated = 0;
                    foreach ($caseAvailableInPool as $data) {
                        $finallyListed = $ignoreLimit = 0;
                        unset($updateField); 
                        //unset($elimination_field);
                        $updateField = [
                            'diary_no' => $data['diary_no'],
                            'conn_key' => !empty($data['main_key']) ? $data['main_key'] : 0,
                            'next_dt' => $post['next_dt_selected'],
                            'tentative_cl_dt' => $post['next_dt_selected'],
                            'usercode' => session()->get('login')['usercode'],
                            'main_supp_flag' => $post['main_supp'],
                            'clno' => $post['part_no'],
                            'module_id' => 29
                        ];
                        $table_allocation = "heardt";
                        
                        if ($data['diary_no'] == $data['main_key']) {
                            $dairyWithConnected = $this->Heardt->get_diary_with_connected_cases($data['diary_no']);
                        } else {
                            $dairyWithConnected = $data['diary_no'];
                        }
                        
                        $checkedNotBeforeVerify = $this->CaseInfoModel->check_list_before($dairyWithConnected, 'N');
                        $checkedBeforeVerify = $this->CaseInfoModel->check_list_before($dairyWithConnected, 'B');
                        if ($data['listorder'] == 4 || $data['listorder'] == 5 || $data['listorder'] == 7 || $data['advocate_id'] != null) {
                            $ignoreLimit = 1;
                        }

                        if (is_null($checkedNotBeforeVerify) || !is_string($checkedNotBeforeVerify)) {
                            $checkedNotBeforeVerifyArray = [];
                        } else {
                            $checkedNotBeforeVerifyArray = explode(',', $checkedNotBeforeVerify);
                        }

                        $filteredJudge = array_diff(array_keys($selectedJudges), $checkedNotBeforeVerifyArray);
                        if ($checkedBeforeVerify) {

                            if (in_array($checkedBeforeVerify, array_keys($selectedJudges))) {
                                if (in_array($selectedJudges[$checkedBeforeVerify]['judge'], $checkedNotBeforeVerifyArray)) {
                                    $finallyEliminated = 1;
                                    if ($finallyEliminated) {
                                        $totalCasesEliminated += $totalCasesEliminated;
                                    }
                                } elseif ($ignoreLimit == 1 || $selectedJudges[$checkedBeforeVerify]['allocated'] < $selectedJudges[$checkedBeforeVerify]['limit']) {
                                    $itemNumber++;
                                    $updateField['brd_slno'] = $itemNumber + 1;
                                    $updateField['judges'] = $selectedJudges[$checkedBeforeVerify]['judge'];
                                    $updateField['roster_id'] = $selectedJudges[$checkedBeforeVerify]['roster_id'];
                                    $updateField['ent_dt'] = date('Y-m-d H:i:s');
                                    $updateField['updated_by_ip'] = getClientIP();
                                    $updateField['updated_by'] = session()->get('login')['usercode'];
                                    $updateField['updated_on'] = date('Y-m-d H:i:s');
                                    $updateField['create_modify'] = date('Y-m-d H:i:s');

                                    $this->Heardt->q_from_heardt_to_last_heardt($data['diary_no']);
                                    $finallyCaseListed = $this->Heardt->updateHeardtData($table_allocation, $data['diary_no'], $updateField);

                                    if ($finallyCaseListed) {
                                        $this->CaseInfoModel->single_judge_final_connected_cases_allocation($data['diary_no']);
                                        $totalCasesListed++;
                                        $selectedJudges[$checkedBeforeVerify]['allocated']++;
                                    }
                                    unset($updateField);
                                }
                            } else {
                                $finallyEliminated = 1;
                                if ($finallyEliminated) {
                                    $totalCasesEliminated += $totalCasesEliminated;
                                }
                            }
                            $finallyListed = 1;
                        } elseif (empty($filteredJudge)) {
                            $finallyListed = 1;
                            //$elimination_field['reason'] = "After Not Before Judge filtered Honble Bench Not Available";
                            //$finally_eliminated = $this->Causelist_model->single_judge_advance_case_eliminate($table_elimination,$elimination_field);
                        } else {
                            
                            if ($data['coram'] != "" && $data['coram'] != 0 && $data['coram'] != null) {
                                $coram = explode(",", $data['coram']);
                                foreach ($coram as $coramData) {
                                    if (in_array($coramData, $filteredJudge)) {
                                        if ($ignoreLimit == 1 || $selectedJudges[$coramData]['allocated'] < $selectedJudges[$coramData]['limit']) {
                                            $itemNumber++;
                                            $updateField['brd_slno'] = $itemNumber + 1;
                                            $updateField['judges'] = $coramData;
                                            $updateField['roster_id'] = $selectedJudges[$coramData]['roster_id'];
                                            $updateField['ent_dt'] = date('Y-m-d H:i:s');
                                            $updateField['updated_by_ip'] = getClientIP();
                                            $updateField['updated_by'] = session()->get('login')['usercode'];
                                            $updateField['updated_on'] = date('Y-m-d H:i:s');
                                            $updateField['create_modify'] = date('Y-m-d H:i:s');

                                            $this->Heardt->q_from_heardt_to_last_heardt($data['diary_no']);
                                            $finallyCaseListed = $this->Heardt->updateHeardtData($table_allocation, $data['diary_no'], $updateField);

                                            if ($finallyCaseListed) {
                                                $this->CaseInfoModel->single_judge_final_connected_cases_allocation($data['diary_no']);
                                                $totalCasesListed++;
                                                $selectedJudges[$coramData]['allocated']++;
                                            }
                                            unset($updateField);
                                            $finallyListed = 1;
                                            break;
                                        }
                                    }
                                }
                            }



                            //if case not listed through coram then list randome
                            if($finallyListed == 0){
                                //echo "test9 ";
                                //check coram for other group judges cases
                                if ($data['coram'] != "" && $data['coram'] != 0 && $data['coram'] != null) {
                                    //echo "<br>Inside to check coram in other group";
                                    //echo "test10 ";
                                    //var_dump($other_group_judge_array);
                                    foreach ($coram as $coram_data) {
                                        if (in_array($coram_data, $otherGroupJudgeArray)) {
                                            //echo "coram found in other group" . $coram_data . "<br>";
                                            if ($ignoreLimit != 1){
                                                //not to allocate due to other group judge and not mandatory
                                                $finallyListed = 1;
                                                //$elimination_field['reason'] = "Other Group Coram Case";
                                                //$finally_eliminated = $this->Causelist_model->single_judge_advance_case_eliminate($table_elimination,$elimination_field);
                                            }
                                            break;
                                        }
                                    }
                                }
                                if ($ignoreLimit == 1 || $finallyListed == 0){
                                   // echo "test11 ";
                                    //echo "<br>Without Coram and other group case allocation";

                                    unset($selected_judges2_temp);
                                    $selected_judges2_temp = $selectedJudges;
                                    
                                    foreach($checkedNotBeforeVerifyArray as $key=>$value) {
                                        unset($selected_judges2_temp[$value]);
                                    }
                                    //echo "<br>Checked Not Before entry and filtered result:";
                                    usort($selected_judges2_temp, function($v1, $v2) {
                                        return $v1['allocated'] - $v2['allocated'];
                                    });
                                    //echo "<br>sorted:";
                                    //print_r($selected_judges2_temp);
                                    //echo "<br>get first index:";
                                    //echo print_r($selected_judges2_temp[0]);
                                    if($ignoreLimit == 0 && $selected_judges2_temp[0]['allocated'] >= $selected_judges2_temp[0]['limit']){
                                        $finallyListed = 1;
                                        //$elimination_field['reason'] = "DUE TO EXCESS MATTERS";
                                        //$finally_eliminated = $this->Causelist_model->single_judge_advance_case_eliminate($table_elimination,$elimination_field);
                                    }
                                    //print_r($selected_judges2_temp);
                                    if(!empty($selected_judges2_temp)){
                                        /*foreach ($max_item_no_array as $item_key => $item_value) {
                                            $selected_judges2_temp[0]['allocated'];
                                            if ($max_item_no_array[$item_key]['jcode'] == $selected_judges2_temp[0]['judge']){
                                                $max_item_no_array[$item_key]['max_item_number'] += 1;
                                                $item_number = $item_number+1;
                                                $update_field['brd_slno'] = $item_number;
                                                $update_field['single_judge_nominate_id'] = $max_item_no_array[$item_key]['id'];
                                                break;
                                            }
                                        }*/
                                        //$update_field['j1'] = $selected_judges2_temp[0]['judge'];
                                        
                                        $itemNumber = $itemNumber+1;
                                        $updateField['brd_slno'] = $itemNumber+1;
                                        $updateField['judges'] = $selected_judges2_temp[0]['judge'];
                                        $updateField['roster_id'] = $selected_judges2_temp[0]['roster_id'];
                                        $updateField['ent_dt'] = date('Y-m-d H:i:s');
                                        $updateField['updated_by_ip'] = getClientIP();
                                        $updateField['updated_by'] = session()->get('login')['usercode'];
                                        $updateField['updated_on'] = date('Y-m-d H:i:s');
                                        $updateField['create_modify'] = date('Y-m-d H:i:s');
                                        
                                        //$this->Causelist_model->q_from_heardt_to_last_heardt($data['diary_no']);
                                        $this->Heardt->q_from_heardt_to_last_heardt($data['diary_no']);
                                        $finallyCaseListed = $this->Heardt->updateHeardtData($table_allocation, $data['diary_no'], $updateField);
                                        if ($finallyCaseListed) {
                                            //$this->Causelist_model->single_judge_final_connected_cases_allocation($data['diary_no']);
                                            $this->CaseInfoModel->single_judge_final_connected_cases_allocation($data['diary_no']);
                                            //echo "<br>ALLOCATED Judge ".$update_field['j1'];
                                            $totalCasesListed += 1;
                                            $selectedJudges[$selected_judges2_temp[0]['judge']]['allocated'] += 1;
                                        }
                                        unset($update_field);
                                        $finallyListed = 1;
                                    }
                                    else{
                                        $finallyListed = 1;
                                        //$elimination_field['reason'] = "Judge Not Available";
                                        //$finally_eliminated = $this->Causelist_model->single_judge_advance_case_eliminate($table_elimination,$elimination_field);
                                    }
                                }
                                else{
                                    $finallyListed = 1;
                                    //$elimination_field['reason'] = "Other Group Judge and not Mandatory Case";
                                    //$finally_eliminated = $this->Causelist_model->single_judge_advance_case_eliminate($table_elimination,$elimination_field);
                                }
                            }
                        }

                        if($totalCasesListed == ($inArr['number_of_cases']*$selectedJudgeCount)){
                            break;
                        }
                    }

                    if ($totalCasesListed > 0) {
                        //echo json_encode(array("status" => "success", "msg"=>"Total $totalCasesListed Cases Listed"));
                        return $this->response->setJSON(['status' => 'success', 'msg' => "Total $totalCasesListed Cases Listed"]);
                        //echo "Total Cases Listed : ".$total_cases_listed;
                    } else {
                        //echo json_encode(array("status" => "success", "msg"=>"Total 0 Cases Listed"));
                        return $this->response->setJSON(['status' => 'success', 'msg' => "Total 0 Cases Listed"]);
                    }
                } else {
                    return $this->response->setJSON(['status' => 'error', 'msg' => "Cases not found for allocation"]);
                    //echo json_encode(array("status" => "error", "msg"=>"Cases not found for allocation"));
                    //exit;
                }
            }   
        }
    }



    public function transfer_without_coram_check()
    {
        $request = service('request');
        set_time_limit(25000);
        $post_data = $request->getPost();
        $ucode = session()->get('login')['usercode'];
        return $this->AllocationTp->transfer_without_coram_check($post_data, $ucode);
    }
}
