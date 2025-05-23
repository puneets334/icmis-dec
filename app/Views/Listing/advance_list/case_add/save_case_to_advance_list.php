<?php


$q_clno = 1;
$q_main_supp_flag = 1;
$mainhead = "M";
$q_next_dt = $q_next_dt;
$diary_number = $diary_number;
$ro_isnmd = $model->getIsnmd($q_next_dt);
$finally_listed=0;


if (!empty($ro_isnmd))
{
    if ($ro_isnmd['is_nmd'] == 1) {
        $misc_nmd_flag = 1;
    }
    if ($ro_isnmd['is_nmd'] == 0) {
        $misc_nmd_flag = 0;
    }

    $short_categoary_array = array(343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222);


    $row_4senior_judges = $model->getJudge();
   

    $top_4senior_judges_str = $row_4senior_judges['jcode'] ?? '';
    $judge_limit_detail = array();
    if ($ro_isnmd['is_nmd'] == 1) {


        $sql = $model->isNumCheckOne($q_next_dt);
     
    }
    if ($ro_isnmd['is_nmd'] == 0) {

        $sql = $model->isNumCheckZero($q_next_dt);
    }
    $excepting_top_4courts = "";
    $top_4court = "";
    $top_4court_not = "";
    $top_4court_sno = 1;
    $top_4court_not_in = "";
    $rs = $sql;

    if (!empty($rs))
    {
        foreach ($rs  as $ro)
        {
            if ($top_4court_sno <= 4)
            {
               

                if ($top_4court_sno == 1) {
                    $top_4court .= " (";
                }
                $top_4court .= " SUBSTRING_INDEX(h.coram,',',1) = " . $ro['p1'] . " OR SUBSTRING_INDEX(h.coram,',',1) = " . $ro['p2'];
                $top_4court_not_in .= " SUBSTRING_INDEX(h.coram,',',1) != " . $ro['p1'] . " AND SUBSTRING_INDEX(h.coram,',',1) != " . $ro['p2'] . " AND ";
                if ($ro['p3'] > 0) {
                    $top_4court .= " OR SUBSTRING_INDEX(h.coram,',',1) = " . $ro['p3'];
                    $top_4court_not_in .= " SUBSTRING_INDEX(h.coram,',',1) != " . $ro['p3'] . " AND ";
                }
                if ($top_4court_sno != 4) {
                    $top_4court .= " OR ";
                }
                if ($top_4court_sno == 4) {
                    $top_4court .= " ) ";
                }
            }
            else
            {
                

                $excepting_top_4courts .= $ro['p1'] . "," . $ro['p2'] . ",";
                if ($ro['p3'] > 0) {
                    $excepting_top_4courts .= $ro['p3'] . ",";
                }
            }
            $top_4court_sno++;
            $judge_group = $ro['p1'] . ',' . $ro['p2'] . ',' . $ro['p3'];
            $judge_limit_detail[] = array($ro['p1'], $judge_group, $ro['old_limit'], $ro['listed']);
            $presiding_judge[] = $ro['p1'];
        }
        $excepting_top_4courts = rtrim($excepting_top_4courts, ",");
        $excepting_top_4courts_array = explode(",", $excepting_top_4courts);
        $presiing_judge_str = implode(",", $presiding_judge);
        
        $rslt_is_printed = advance_cl_printed($q_next_dt);
       
        if ($rslt_is_printed == 0)
        {
            
            $short_cat = "";
            $is_nmd_column_flag = "";

            if ($misc_nmd_flag == 1) {
                $is_nmd_column_flag = " (h.is_nmd = 'Y' ";
                $short_cat = " OR ($top_4court_not_in  mc.submaster_id IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,173,175,176,322,222) ) ) ";
            } else {
                $is_nmd_column_flag = " (h.is_nmd = 'N' OR h.is_nmd = '' OR h.is_nmd is null) ";
                $short_cat = " AND ( $top_4court OR mc.submaster_id NOT IN (343,15,16,17,18,19,20,21,22,23,341,353,157,158,159,160,161,162,163,166,175,176,322,222) ) ";
            }


            $rsQuery = $model->getIsPerson($q_next_dt, $presiing_judge_str, $diary_number); // MAin query 
             

            
          
            if (!empty($rsQuery))
            {

                foreach ($rsQuery as $row_c)
                {
                    $row_coram = $model->getCoram($row_c['coram']);
                    
                    
                    if (!empty($row_coram))
                    {
                        $coram = $row_coram['new_coram'];
                    } else {
                        $coram = '';
                    }
                    $coram_ex = explode(',', (string)$coram);
                    $main_key = $row_c['main_key'];
                    $subhead = $row_c['subhead'];
                    $heardt_is_nmd = $row_c['is_nmd'];
                    $board_type = $row_c['board_type'];
                    $q_listorder = $row_c['listorder'];
                    $q_diary_no = $row_c['diary_no'];
                    $cat1 = $row_c['submaster_id'];
                    if ($misc_nmd_flag == 1 and in_array($row_c['submaster_id'], $short_categoary_array)) {
                        $possible_judges = $presiing_judge_str;
                    } else {
                        $possible_judges = $row_c['rid'];
                    }
                  
                   
                    if ($cat1 == 341)
                    {
                        $cji_code = f_get_cji_code();
                        if (in_array($cji_code, $possible_judges)) {
                            $possible_judges = $cji_code;
                        } else {
                            $finally_listed = 1;
                            break;
                        }
                    }


                    $binay_vl = "";
                    $binary_wt = array();
                    if ($row_c['diary_no'] == $row_c['main_key']) {
                        $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                    } else {
                        $dairy_with_conn_k = $q_diary_no;
                    }
                  
                    $checked_notbefore_verify = "";
                    $checked_notbefore_verify = check_list_before_save_advance_list($dairy_with_conn_k, 'N');
              
                    $top_4senior_judges_array = explode(",", $top_4senior_judges_str);
                    if ($misc_nmd_flag == 1 and $q_listorder != 4 and $q_listorder != 5 and $heardt_is_nmd != 'Y' and $row_c['subhead'] != 824 and $row_c['advocate_id'] != null and $coram == "") {
                        $checked_notbefore_verify .= $checked_notbefore_verify . "," . $top_4senior_judges_str;
                    }



                    if (in_array($row_c['submaster_id'], $short_categoary_array)) {
                        if ($misc_nmd_flag == 0 and $q_listorder != 4 and $q_listorder != 5 and $row_c['subhead'] != 824 and $row_c['advocate_id'] != null and $coram == "") {
                            $checked_notbefore_verify .= $checked_notbefore_verify . "," . $excepting_top_4courts;
                        }
                    }
                    $checked_notbefore_verify = rtrim(ltrim(trim($checked_notbefore_verify), ','), ',');



                    $tobelisted = 0;
                    $finally_listed = 0;
                    $if_listed_with_coram = 0;
                    $checked_before_verify = "";
                    for ($row = 0; $row < count($judge_limit_detail); $row++)
                    {
                       
                        $judge_presiding_indv = $judge_limit_detail[$row][0];
                        $judge_group_indv = $judge_limit_detail[$row][1];
                        $to_be_listed_indv = $judge_limit_detail[$row][2];
                        $listed_indv = $judge_limit_detail[$row][3];



                        $checked_before_verify = check_list_before_save_advance_list($dairy_with_conn_k, 'B');


                        if ($checked_before_verify != '-1' and $checked_before_verify != '')
                        {


                            $checked_before_verify_exploded = explode(",", $checked_before_verify);
                            $judge_group_indv_exploded = explode(",", $judge_group_indv);
                            $result_intersected = array_intersect($judge_group_indv_exploded, $checked_before_verify_exploded);
                            if (count($checked_before_verify_exploded) == count($result_intersected)) {

                                $tobelisted = 1;
                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                $result_before_not = array_intersect($judge_group_indv_exploded, $checked_notbefore_verify_exploded);

                                if (count($result_before_not) === 0)
                                {
                                  

                                    $finally_case_listed = 1;
                                    $updating_judge = explode(",", $judge_limit_detail[$row][1]);
                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                    if ($finally_case_listed == 1) {
                                        $finally_listed = 1;
                                        $judge_limit_detail[$row][3] += 1;
                                    }
                                }
                                break;
                            }
                        } else if ($coram != null and $coram != 0 and $coram != '')
                        {
                           

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

                                            $checked_notbefore_verify = check_list_before_save_advance_list($dairy_with_conn_k, 'N');

                                            $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);

                                            $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);

                                            if (count($result_array_intersect) > 0) {
                                            } else {
                                                $updating_judge = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                $finally_listed = 1;
                                                $judge_limit_detail[$judgeForCoram][3] += 1;
                                                break (3);
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

                                                $judgeForCoram_indv_to_convert_into_array = explode(",", $judge_limit_detail[$judgeForCoram][1]);
                                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);

                                                $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);


                                                if (count($result_array_intersect) > 0) {
                                                } else {

                                                    $updating_judge = explode(",", $judge_limit_detail[$judgeForCoram][1]);

                                                    $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                                                    $finally_listed = 1;
                                                    $judge_limit_detail[$judgeForCoram][3] += 1;
                                                    break (2);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($finally_listed == 0 and $checked_before_verify == "")
                    {
                        if ($possible_judges != null)
                        {
                            $possible_judges_exploded = explode(",", $possible_judges);
                            $remaining_judge_group = remove_notbefore_judge($judge_limit_detail, $checked_notbefore_verify);

                            $remaining_judge_group = msort($remaining_judge_group, '3', '1');
                            $count_array_remaining_judge_group = (count($remaining_judge_group) - 1);
                            $rand_remaining_judge_group_key = rand(0, $count_array_remaining_judge_group);

                            $final_allocation_judges = explode(",", $remaining_judge_group[$rand_remaining_judge_group_key][1]);


                            $finally_listed = 1;
                            $judge_limit_detail_key = findInMultiDimensionalArray($judge_limit_detail, '0', $final_allocation_judges[0]);
                            $updating_judge = explode(",", $judge_limit_detail[$judge_limit_detail_key][1]);
                            $finally_case_listed = f_advance_cl_allocation($q_diary_no, $main_key, $q_next_dt, $subhead, $board_type, $q_clno, $updating_judge[0], $updating_judge[1], $updating_judge[2], $q_listorder, $q_usercode, $q_main_supp_flag);
                            $judge_limit_detail[$judge_limit_detail_key][3] += 1;
                        }
                    }
                }
            }
            else
            {
                echo "<br>";
               
               
                echo "<span style='color:red; display:block; text-align:center;'>Cases not found to list in pool</span>";

            }
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

if ($finally_listed == 1)
{
    echo "<br>";
    echo "<span style='color:green;display:block; text-align:center;'> Allocated in Advance List Successfully.</span>";
}
