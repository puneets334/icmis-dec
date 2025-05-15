<?php
$ucode = session()->get('login')['usercode'];
$list_dt = date('Y-m-d', strtotime($list_dt));
$mainhead = $mainhead;
$part_no = $part_no;
//$jud_ros = explode("|", $jud_ros);
$board_type = $board_type;
if ($board_type == '0') {
    $board_type_in = "";
} else {
    $board_type_in = " AND h.board_type = '$board_type'";
}


$roster_id = $roster_id;
$judges_id = $judges_id;
$exp_jcode = explode(",", $judges_id);
$first_jcd_cc = $exp_jcode[0];

$session = session();

$session->set('list_dt', $list_dt);
$session->set('advance_json_mainhead', $mainhead);
$session->set('json_judge_code', $first_jcd_cc);
$session->set('json_part_no', $part_no);


$res_ros = $model->getRosterTitle($roster_id);

if (!empty($res_ros)) {
    $row_ros = $res_ros;
    $bench_no = $row_ros['bench_no'];
    $bench_session = $row_ros['session'];
    $bench_time = $row_ros['frm_time'];
    $bench_judge_name = stripcslashes(str_replace(",", "<br/>", $row_ros['jnm']));
    $first_judge = explode(",", $row_ros['jnm']);
    $frst_judge_name = $first_judge[0];
    $bench_reg_name = $row_ros['first_names'] . " " . $row_ros['sur_names'] . ", " . $row_ros['titles'];
    $bench_court = $row_ros['courtno'];
    $jcd_rp = $row_ros['jcd'];
    $board_type_mb = $row_ros['board_type_mb'];
    $frm_time = $row_ros['frm_time'];
    $session->set('json_board_type', $board_type_mb);


    $print_in_court_no = "";
    if ($board_type_mb == "J" and $mainhead == "M") {

        if ($row_ros['if_print_in'] == 1) {
            $print_in_court_no = "IN ";
        }
        if ($first_jcd_cc == '219' and $list_dt == '2019-10-18') {
            $print_in_court_no = "IN ";
        }
        if (($first_jcd_cc == '255' or $first_jcd_cc == '256') and $list_dt == '2019-08-05') {
            $print_in_court_no = " ";
        }
    }
?>
    <div id="prnnt" style="font-size:12px;">

        <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
                    <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px" /><br />

                    <link rel="icon" href="<?php echo base_url('images/scilogo.png'); ?>" type="image/x-icon">


                    SUPREME COURT OF INDIA

                    <?php if ($board_type_mb != "R" and $part_no != "50" and $part_no != "51") { ?>

                        <br />[ IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES<br />ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS<br />LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST ]

                    <?php } ?>
                    <br />

                    <?php


                    if ($part_no == "50" or $part_no == "51" or $part_no == "52" or $part_no == "53" or $part_no == "54" or $part_no == "55") {
                        echo "LIST OF CURATIVE & REVIEW PETITIONS (BY CIRCULATION)<br/>IN THE CHAMBERS OF<br><U>" . $frst_judge_name . "</U><br><br>";
                    }

                    ?>
                    <?php
                    if ($part_no != "50" and $part_no != "51" and $part_no != "52" and $part_no != "53" and $part_no != "54" and $part_no != "55") {
                        if ($bench_court == "1") {
                            $print_court_no = "CHIEF JUSTICE'S COURT";
                        } else if ($bench_court == "21") {
                            $print_court_no = "Registrar Court";
                        } else if ($bench_court == "22") {
                            $print_court_no = "Registrar Court No. 2";
                        } else if ($bench_court == "61") {
                            $print_court_no = "Registrar Court No. 1 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "62") {
                            $print_court_no = "Registrar Court No. 2 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "31") {
                            $print_court_no = "Court No. 1 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "32") {
                            $print_court_no = "Court No. 2 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "33") {
                            $print_court_no = "Court No. 3 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "34") {
                            $print_court_no = "Court No. 4 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "35") {
                            $print_court_no = "Court No. 5 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "36") {
                            $print_court_no = "Court No. 6 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "37") {
                            $print_court_no = "Court No. 7 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "38") {
                            $print_court_no = "Court No. 8 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "39") {
                            $print_court_no = "Court No. 9 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "40") {
                            $print_court_no = "Court No. 10 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "41") {
                            $print_court_no = "Court No. 11 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "42") {
                            $print_court_no = "Court No. 12 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "43") {
                            $print_court_no = "Court No. 13 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "44") {
                            $print_court_no = "Court No. 14 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "45") {
                            $print_court_no = "Court No. 15 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "46") {
                            $print_court_no = "Court No. 16 (Hearing Through Video Conferencing)";
                        } else if ($bench_court == "47") {
                            $print_court_no = "Court No. 17 (Hearing Through Video Conferencing)";
                        } else if ($bench_court > 1 and $bench_court <= 20) {
                            $print_court_no = $print_in_court_no . "COURT NO. : " . $bench_court;
                        } else {
                        }
                    }
                    ?>
                    <!--</table>-->
                </b></div>
        <table autosize="1" border="0" width="100%" style="font-size:12px; text-align: left; word-wrap: break-word; overflow:auto;" cellspacing=0>
            <thead>
                <tr>
                    <th colspan="4" style="text-align: center;">

                        <?php echo "DAILY CAUSE LIST FOR DATED : " . date('d-m-Y', strtotime($list_dt)) . "<br/>";
                        echo $print_court_no;
                        if (strpos($bench_no, 'VACATION') !== false) {
                            echo "<br><B>VACATION BENCH</B>";
                        }
                        ?>

                    </th>
                </tr>
            </thead>
            <tbody>

                <?php //}


                ?>


                <tr>
                    <th colspan="4" style="text-align: center;"><?php
                                                                if ($board_type_mb != "R") {
                                                                    echo $bench_judge_name;
                                                                } else {
                                                                    echo $bench_reg_name;
                                                                }

                                                                if ($bench_session == "After Regular Bench") {
                                                                    echo "<br/>THIS BENCH WILL ASSEMBLE AFTER THE NORMAL COURT IS OVER";
                                                                }
                                                                ?></th>
                </tr>

                <?php if ($bench_time) {

                ?>
                    <tr>
                        <th colspan="4" style="text-align: center;">(TIME : <?php echo $bench_time; ?>)</th>
                    </tr>
                    <?php
                } else {
                    if ($board_type_mb != 'C' and $bench_session == 'Whole Day') {

                    ?>
                        <tr>
                            <th colspan="4" style="text-align: center;">
                                (TIME : <?php if ($board_type == "R") {
                                            echo "11:00 AM";
                                        } else {
                                            echo "10:30 AM";
                                        } ?>)</th>
                        </tr>

                    <?php }
                }
                if ($mainhead == "F") {
                    ?>
                    <tr>
                        <th colspan="4" style="text-align: left;"><br /><b><span style="font-size: 14px;">NOTE : Chronology is based on the date of initial filing.</span></b></th>
                    </tr>

                <?php
                }
                ?>

                <?php
               
                $res_scw = $model->getWorkingDays($list_dt);
                if (!empty($res_scw)) {
                    $misc_day = "YES";
                } else {
                    $misc_day = "NO";
                }
                if ($misc_day == 'YES'  and (strpos($bench_no, 'SPL') === false) and $mainhead == 'M' and $board_type_mb == 'J' and $first_jcd_cc == '112' and $list_dt != '2018-11-26') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <?php
                            echo "<b>NOTE :- <br>[ ITEM NOS.1-30 (INCLUDING PASS OVER ITEMS) WILL BE TAKEN UP FOR HEARING IN THIS COURT FROM 10.30 A.M. TO 11.15 A.M. ]
                                <br>[ ITEM NOS. 31 AND ONWARDS WILL BE TAKEN UP FOR HEARING IN THIS COURT AT 11.30 A.M. ONWARDS]</b><br>";

                            ?>
                        </th>
                    </tr>
                <?php
                }



                if ($first_jcd_cc == '280' and $mainhead == 'F') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <br><b><span style="font-size: 14px;">NOTE :- Parties to file list of dates and brief note of submissions not exceeding three pages, two days before the date of listing.</span></b><br>
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '279' and $mainhead == 'M') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <br><b><span style="font-size: 14px;">NOTE :- Fresh matters including the pass over fresh matters will be taken up before After Notice matters.</span></b><br>
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '280' and $mainhead == 'M') {
                    ?>
                        <tr>
                            <th colspan="4" style="text-align: left;">                                    
                                <br><b><span style="font-size: 14px;">NOTE :- Fresh matters including the pass over fresh matters will be taken up before After Notice matters.</span></b><br>
                            </th>
                        </tr>
                    <?php                        
                } if ($first_jcd_cc == '293' and $mainhead == 'M') {
                    ?>
                            <tr>
                                <th colspan="4" style="text-align: left;">                                    
                                    <br><b><span style="font-size: 14px;">NOTE :- Fresh matters, including the passed over fresh matters, will be taken up before After Notice Matters.</span></b><br>
                                </th>
                            </tr>
                        <?php
                    }
                if ($first_jcd_cc == '270' and $mainhead == 'F') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <br><b><span style="font-size: 14px;">NOTE :- NO REQUEST FOR PASS OVER OR ADJOURNMENT WILL BE ENTERTAINED IN ITEM NOS. 101 TO 105. IN THE EVENT THE PARTIES ARE NOT REPRESENTED WHEN THE MATTERS ARE CALLED OUT, THE COURT WILL HEAR AND DECIDE THE MATTERS IN THEIR ABSENCE.</span></b><br>
                        </th>
                    </tr>
                <?php
                }

                if ($board_type_mb == 'J' and $first_jcd_cc == '254') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <?php
                            ?>
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '219') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <b>NOTE :</b><br>
                            Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :
                            <br><br>
                            cmvc.dyc@gmail.com
                            <br><br>
                            The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '281') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <b>NOTE :</b><br>
                            Whenever written submissions are directed to be filed by the Court in any proceeding, advocates and parties in person are requested to email a soft copy in a pdf form on or before the stipulated date to the following email id :
                            <br><br>
                            cmvc.hk@gmail.com
                            <br><br>
                            The soft copies which are emailed should not be scanned copies of printed submissions. No other documents other than written submissions should be filed in this email.
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '288') {
                    //Whenever written submissions are filed in a matter reserved by this Hon'ble Court, Advocates and parties in person are requested to email a soft copy in a pdf form to the email id : "writtensubmissions.jbp@gmail.com"                                                                        
                    ?>
                        <tr>
                            <th colspan="4" style="text-align: left;">
                                <b>NOTE :</b><br>                                    
"Whenever written submissions are directed to be filed in matter(s) reserved for judgment/order by this Hon’ble Court, the advocates and parties in person are requested to email a soft copy in a pdf form on or before the directed/stipulated date to the following email id :
<br><br>
<center>writtensubmissions.jbp@gmail.com.</center>
<br><br>
The soft copies which are to be emailed should not be scanned copies of printed submissions. No document other than written submissions in matter(s) reserved for judgment/order should be filed in this email."


                            </th>
                        </tr>
                    <?php
                }

                ?>

                <?php
                if ($first_jcd_cc == '210') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                        </th>
                    </tr>
                <?php
                }

                if ($first_jcd_cc == '216') {
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == '198' and $mainhead == 'M' and $board_type_mb == 'J') {
                ?>

                <?php
                }
                if ($first_jcd_cc == 210 and ($misc_day == 'NO' or $mainhead == 'F')) {
                    //regular or nmd days
                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">

                        </th>
                    </tr>
                <?php
                }
                if ($first_jcd_cc == 275 and $board_type_mb == 'J') {

                ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <br><b><span style="font-size: 14px;">REMARKS :- No letter for adjournment shall be entertained in matters filed on or before the year 2019. </span></b><br>
                        </th>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <th colspan="4" style="text-align: center;">
                        <?php echo get_header_footer_printed($list_dt, $mainhead, $roster_id, $part_no, 'H'); ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: center;" align="center">
                        <center><?php get_drop_note_print($list_dt, $mainhead, $roster_id); ?> </center>
                    </th>
                </tr>
                <?php
                $clnochk = 0;
                $subheading_rep = "0";
                $mnhead_print_once = 1;

                $res = $model->getRelif($list_dt, $board_type, $mainhead, $roster_id, $part_no, $board_type_in);

                if (!empty($res)) {
                    foreach ($res as $row) {
                        $coram = $row['coram'];
                        $relief = $row['relief'];
                        $main_supp_fl = $row['main_supp_flag'];



                        $multi_main_supp_flag = explode(",", $row['multi_main_supp_flag']);
                        if (count($multi_main_supp_flag) > 1) {
                            $multi_main_supp_flag_print = "<span style='color:red; font-width:bold;'>MESSAGE : CASES LISTED IN MAIN & SUPPLEMENTARY IN SELECTED PART $part_no, PLEASE VERIFY.</span>";
                            if ($main_supp_fl == 1)
                                $multi_main_supp_show = "<span style='color:red;'>Main List</span>";
                            if ($main_supp_fl == 2)
                                $multi_main_supp_show = "<span style='color:red;'>Supply List</span>";
                            // exit();
                        } else {
                            $multi_main_supp_flag_print = "";
                            $multi_main_supp_show = "";
                        }



                        $session->set('multi_main_supp_flag_print', $multi_main_supp_flag_print);

                        $diary_no = $row['diary_no'];
                        if ($row['if_sclsc'] == 1) {
                            $if_sclsc = "(SCLSC)";
                        } else {
                            $if_sclsc = "";
                        }
                        if ($mainhead == "F") {
                            if ($row['subhead'] == "911" or $row['subhead'] == "912") {
                                $retn = $row["sub_name1"];
                            } else {
                                $retn = $row["sub_name1"];
                                if ($row["sub_name2"])
                                    $retn .= " - " . $row["sub_name2"];
                                if ($row["sub_name3"])
                                    $retn .= " - " . $row["sub_name3"];
                                if ($row["sub_name4"])
                                    $retn .= " - " . $row["sub_name4"];
                            }
                            $subheading = $retn;
                        } else {
                            $subheading = $row["stagename"];
                        }

                        if ($mnhead_print_once == 1) {



                            if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                                if ($row['board_type'] == 'C') {
                                    if ($part_no != "50" and $part_no != "51") {
                                        $print_mainhead = "CHAMBER MATTERS";
                                    }
                                } else {
                                    if ($part_no == "50" or $part_no == "51") {
                                    } else {
                                        $print_mainhead = "MISCELLANEOUS HEARING";
                                    }
                                }
                            }
                            if ($mainhead == 'F' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER")
                                $print_mainhead = "REGULAR HEARING";
                            if ($mainhead == 'L')
                                $print_mainhead = "LOK ADALAT HEARING";
                            if ($mainhead == 'S')
                                $print_mainhead = "MEDIATION HEARING";
                            if ($main_supp_fl == "2") {
                                echo "<tr><td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>";
                            }
                ?>

                            <tr>
                                <th colspan="4" style="text-align: center; text-decoration: underline;"><?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                                                                                            echo $print_mainhead;
                                                                                                        } ?></th>
                            </tr>






                            <tr style="font-weight: bold; background-color:#cccccc;">
                                <td style="width:5%;">SNo.</td>
                                <td style="width:20%;">Case No.</td>
                                <td style="padding-left:20px; width:50%;">Petitioner / Respondent</td>
                                <td style="width:25%;">
                                    <?php if ($part_no != "50" and $part_no != "51") { ?>
                                        Petitioner/Respondent Advocate
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php
                            $mnhead_print_once++;
                        }



                        if ($subheading != $subheading_rep) {

                            if ($part_no != "50" and $part_no != "51") {
                                echo "<tr><td colspan='4' style='font-size:15px; font-weight:bold; padding-top:15px; padding-bottom:15px; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";

                                $subheading_rep = $subheading;
                            }
                        }
                        //echo $subheading;

                        if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0) {
                            $print_brdslno = $row['brd_slno'];
                            $con_no = "0";
                            $is_connected = "";
                        } else {
                            $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                            $is_connected = "<span style='color:red;'>Connected</span><br/>";
                        }

                        $m_f_filno = $row['active_fil_no'];
                        $m_f_fil_yr = $row['active_reg_year'];
                        //            }
                        $filno_array = explode("-", $m_f_filno);
                        if (isset($filno_array[1]) && isset($filno_array[2])) {
                            if ($filno_array[1] == $filno_array[2]) {
                                $fil_no_print = ltrim($filno_array[1], '0');
                            } else {
                                $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                            }
                        } else {

                            $fil_no_print = ltrim($filno_array[0] ?? '', '0');
                        }


                        if ($row['reg_no_display'] == "") {
                            $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                        } else {
                            $comlete_fil_no_prt = $row['reg_no_display'];
                        }
                        $padvname = "";
                        $radvname = "";
                        $impldname = "";
                        $intervenorname = "";
                        if ($part_no != "50" and $part_no != "51") {

                            $resultsadv = $model->getAdv($row["diary_no"]);

                            if (!empty($resultsadv)) {
                                $rowadv = $resultsadv;
                                $radvname =  strtoupper($rowadv["r_n"]);
                                $padvname =  strtoupper($rowadv["p_n"]);
                                $impldname = strtoupper($rowadv["i_n"]);
                                $intervenorname = strtoupper($rowadv["intervenor"]);
                            }
                        }
                        if ($row['pno'] == 2) {
                            $pet_name = $row['pet_name'] . " AND ANR.";
                        } else if ($row['pno'] > 2) {
                            $pet_name = $row['pet_name'] . " AND ORS.";
                        } else {
                            $pet_name = $row['pet_name'];
                        }
                        if ($row['rno'] == 2) {
                            $res_name = $row['res_name'] . " AND ANR.";
                        } else if ($row['rno'] > 2) {
                            $res_name = $row['res_name'] . " AND ORS.";
                        } else {
                            $res_name = $row['res_name'];
                        }

                        /*TEMP

                     * */
                        if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                            if ($row['active_reg_year'] != 0)
                                $ten_reg_yr = $row['active_reg_year'];
                            else
                                $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                            if ($row['active_casetype_id'] != 0)
                                $casetype_displ = $row['active_casetype_id'];
                            else if ($row['casetype_id'] != 0)
                                $casetype_displ = $row['casetype_id'];
                        }




                        $section_ten_rs = $model->getSectionName1($row["diary_no"]);

                        if (!empty($section_ten_rs)) {
                            $section_ten_row = $section_ten_rs;
                            $row['section_name'] = $section_ten_row["section_name"];
                        } else {
                            $row['section_name'] = '';
                        }



                        $doc_desrip = "";
                        $listed_ias = $row['listed_ia'] ?? '';
                        $listed_ia = rtrim(trim($listed_ias), ",");



                        if ($listed_ias) {
                            $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In <br>";

                            $rs_dc = $model->getDocNumYear($diary_no);

                            if (!empty($rs_dc)) {
                                foreach ($rs_dc as $row_dc) {
                                    $doc_desrip .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $doc_desrip .= "IA No. " . $row_dc['docnum'] . "/" . $row_dc['docyear'] . " - " . $row_dc['docdesp'];
                                    $doc_desrip .= "</td><td></td></tr>";
                                }
                            }
                        }

                        if ($mainhead == 'F') {
                            $adv_count_stars_display = "";
                            if ($row['conn_key'] != null and $row['conn_key'] > 0) {
                                $count_advocates = f_get_advocate_count_with_connected($row["conn_key"], $row['next_dt']);
                            } else {
                                $count_advocates = f_get_advocate_count($row["diary_no"]);
                            }
                        }
                        $output = '';
                        if ($mainhead == "F") {
                        }
                        $output .= "<tr><td style='vertical_align:top;' valign='top'>$print_brdslno</td>";
                        $output .= "<td style='vertical_align:top;' valign='top'>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $multi_main_supp_show . $if_sclsc . " " . $row['section_name'] . "</td>";
                        $output .= "<td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top'>" . $pet_name . "</td>";
                        $padvname_x = str_replace(",", ", ", trim($padvname, ","));
                        if ($padvname_x) {
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($padvname_x, $x60));
                            $lines_cnt = count($lines);
                            for ($k = 0; $k < count($lines); $k++) {
                                if ($k == 0) {
                                    $output .= "<td valign='top'>" . $lines[$k] . "</td></tr>";
                                } else if ($k == 1 or $k == 2) {
                                    $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $lines[$k] . "</td></tr>";
                                } else {
                                    $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $lines[$k] . "</td></tr>";
                                }
                            }
                        } else {
                            $output .= "<td></td></tr>";
                        }
                        //$output .= "<tr><td>$print_brdslno</td><td rowspan=2>".$is_connected."$comlete_fil_no_prt"."<br/>".$row['name']." (".$row['section_name'].")<br/>".$cate_old_id1."</td><td>".$pet_name."</td><td>".str_replace(",",", ",trim($padvname,","))."</td></tr>";
                        if ($res_name != '') {
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; font-style: italic;' valign='top'>Versus</td><td style='font-style: italic;'></td></tr>";
                        }

                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top' > " . $res_name . "</td>";
                        $radvname_x = str_replace(",", ", ", trim($radvname, ","));
                        if ($impldname) {
                            $radvname_x .= "<br/>" . str_replace(",", ", ", trim($impldname, ","));
                        }
                        if ($intervenorname) {
                            $radvname_x .= "<br/>" . str_replace(",", ", ", trim($intervenorname, ","));
                        }

                        if ($radvname_x) {
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($radvname_x, $x60));
                            $lines_cnt = count($lines);
                            for ($k = 0; $k < count($lines); $k++) {
                                if ($k == 0) {
                                    $output .= "<td valign='top'>" . $lines[$k] . "</td></tr>";
                                } else {
                                    $output .= "<tr><td></td><td></td><td></td><td valign='top'>a " . $lines[$k] . "</td></tr>";
                                }
                            }
                        } else {
                            $output .= "<td></td></tr>";
                        }

                        if ($mainhead == "M" or $mainhead == "F") {





                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                            if ($row['listorder'] == '5')
                                $output .= "{" . $row['purpose'] . "}";
                            $output .= "</td><td></td></tr>";


                            $rs_lct = $model->getShortDescription($diary_no);

                            if (!empty($rs_lct)) {
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                foreach ($rs_lct as $ro_lct) {
                                    $output .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
                                }
                                $output .= "</td><td></td></tr>";
                            }

                            if ($part_no == "50" or $part_no == "51") {
                            } else {
                                $str_brdrem = get_cl_brd_remark($diary_no);

                                $x60 = 150;
                                $lines = explode("\n", wordwrap($str_brdrem, $x60));
                                for ($k = 0; $k < count($lines); $k++) {
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= $lines[$k];
                                    $output .= "</td><td></td></tr>";
                                }


                                if ($relief != '' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= "Relief : " . $relief;
                                    $output .= "</td><td></td></tr>";
                                }
                            }
                            $output .= $doc_desrip;
                        }


                        $output .= "<tr><td style='border-bottom:0px dotted #999999; padding-bottom:10px; size : 2px; height:2px;' colspan=4></td></tr>";
                        echo $output;

                        if ($row['c_status'] == 'D') {
                        ?>
                            <script>
                                alert('Alert : Disposed Case No. <?= $comlete_fil_no_prt ?> Listed');
                            </script>
                    <?php
                        }

                        $output = "";
                    } //END OF WHILE LOOP
                    ?>
                <?php
                } //IF RECORDS AVAILABLE
                else {
                    //echo "NO RECORDS FOUND";
                }
                ?>
                <tr>
                    <th colspan="4" style="text-align: center;"> <?php
                                                                    get_header_footer_printed($list_dt, $mainhead, $roster_id, $part_no, 'F'); ?> </th>
                </tr>
            </tbody>
        </table><br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                    echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div>
    <br /><br /><br /><br /><br /><br /><br /><br />
    <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
        <?php
        if (!empty($multi_main_supp_flag) && is_array($multi_main_supp_flag) && count($multi_main_supp_flag) > 1) {
            echo $multi_main_supp_flag_print;
        } else {

            $rslt_is_printed = 0;
            $rslt_is_printed = f_cl_is_printed($list_dt, $part_no, $mainhead, $roster_id);
            if ($rslt_is_printed == 0)
            {
                $result_case_updation = $model->getAllowedUser($ucode);
               
                if (!empty($result_case_updation)) {
                     ?>
                        <span class="ebublish"><input name="prnnt1" type="button" id="ebublish" value="e-Publish"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <?php
                } else {
                    echo "Not Published";
                }
            }
            else
            {
                echo "Already Published";
            }
            ?>
            <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">

            </span>
            <input name="prnnt1" type="button" id="prnnt1" value="Print">
        <?php } ?>
    </div>
    <center></center>
<?php
} else {
    echo "Roster Not Available";
}


?>