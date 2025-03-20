<?php
// include('../../extra/lg_out_script.php'); {
    // session_start();
    $ucode = session()->get('login')['usercode'];
    $list_dt = date('Y-m-d', strtotime($_POST['list_dt']));
    $mainhead = $_POST['mainhead'];
    $part_no = $_POST['part_no'];
    $jud_ros = explode("|", $_POST['jud_ros']);
    $board_type = $_POST['board_type'];
    if ($board_type == '0') {
        $board_type_in = "";
    } else {
        $board_type_in = " and h.board_type = '$board_type'";
    }
    //    $list_dt = date('Y-m-d', strtotime("2017-05-12"));
    //    $mainhead = "M";
    //    $part_no = "1";
    //    $jud_ros = explode("|","514|27");
    // pr($jud_ros);
    $roster_id = (isset($jud_ros[1]) && !empty($jud_ros[1])) ? $jud_ros[1] : '';
    $judges_id = $jud_ros[0];
    $exp_jcode = explode(",", $judges_id);
    $first_jcd_cc = $exp_jcode[0];
    $_SESSION['list_dt'] = $list_dt;
    $_SESSION['advance_json_mainhead'] = $mainhead;
    $_SESSION['json_judge_code'] = $first_jcd_cc;
    $_SESSION['json_part_no'] = $part_no;
    // include("../../includes/db_inc.php");
    // include("../common/field_function.php");
    // include("../common/function.php");
    if ($mainhead != 'F') {
        $sub_head_name = "s.stagename";
        $leftjoin_subhead = "LEFT JOIN subheading s ON s.stagecode = h.subhead and s.display = 'Y' and s.listtype = '$mainhead'";
        // $order_by = "s.priority ASC, m.diary_no_rec_date ASC";
    } else {
        $sub_head_name = "sm.sub_name1, sm.sub_name2, sm.sub_name3, sm.sub_name4";
        $leftjoin_subhead = "LEFT JOIN category_allottment c ON  h.subhead = c.submaster_id and c.ros_id = '$roster_id' AND c.display = 'Y'";
        $leftjoin_submaster = "LEFT JOIN submaster sm ON h.subhead = sm.id AND sm.display = 'Y'";
        // $order_by = "c.priority ASC, m.diary_no_rec_date ASC";
    }
    $db = \Config\Database::connect();
    $sql_ros = "SELECT r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) jcd, GROUP_CONCAT(j.jname ORDER BY j.judge_seniority) jnm, j.first_name, j.sur_name, title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session,r.if_print_in FROM master.roster r 
        LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
        LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
        LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
        LEFT JOIN master.judge j on j.jcode = rj.judge_id
        WHERE j.is_retired != 'Y' and j.display  = 'Y' and rj.display = 'Y' and rb.display = 'Y' and mb.display = 'Y' 
        and r.display = 'Y' and r.id = '$roster_id' GROUP BY r.id ORDER BY r.id, j.judge_seniority";
    $res_ros = $db->query($sql_ros);
    if (count($res_ros->getResultArray()) > 0) {
        $row_ros = $res_ros->getResultArray();
        $bench_no = $row_ros['bench_no'];
        $bench_session = $row_ros['session'];
        $bench_time = $row_ros['frm_time'];
        $bench_judge_name = stripcslashes(str_replace(",", "<br/>", $row_ros['jnm']));
        $first_judge = explode(",", $row_ros['jnm']);
        $frst_judge_name = $first_judge[0];
        $bench_reg_name = $row_ros['first_name'] . " " . $row_ros['sur_name'] . ", " . $row_ros['title'];
        $bench_court = $row_ros['courtno'];
        $jcd_rp = $row_ros['jcd'];
        $board_type_mb = $row_ros['board_type_mb'];
        $frm_time = $row_row['frm_time'];
        $_SESSION['json_board_type'] = $board_type_mb;
        $print_in_court_no = "";
        if ($board_type_mb == "J" and $mainhead == "M") {
            /*$in_judge = "SELECT j.p1 from judge_group j where if('$list_dt' BETWEEN j.from_dt AND j.to_dt, '$list_dt' BETWEEN j.from_dt AND j.to_dt, j.to_dt = '0000-00-00') and display = 'Y' and p1 = $first_jcd_cc";
            $res_in_judge=mysql_query($in_judge) or die(mysql_error());
            if(mysql_num_rows($res_in_judge)>0){
                $print_in_court_no = " ";
            } else {
                $print_in_court_no = "IN ";
            }*/
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
            <!--<table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0> -->
            <!--    <tr><th colspan="4" style="text-align: center;">-->
            <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
                        <img src="scilogo.png" width="50px" height="80px" /><br />
                        <!--</th></tr>-->
                        <!--<tr><th colspan="4" style="text-align: center;">-->
                        SUPREME COURT OF INDIA
                        <!--</th></tr>-->
                        <?php if ($board_type_mb != "R" and $part_no != "50" and $part_no != "51") { ?>
                            <!--<tr><th colspan="4" style="text-align: center;">-->
                            <br />[ IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES<br />ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS<br />LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST ]
                            <!--</th></tr>-->
                        <?php } ?>
                        <br />
                        <!--<tr><th colspan="4" style="text-align: center;">-->
                        <?php

                        //echo "DAILY CAUSE LIST FOR DATED : ".date('d-m-Y', strtotime($_POST['list_dt']))."<br/>";
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
                                //$print_court_no = $print_in_court_no."COURT NO. : ".$bench_court;
                            }
                        }
                        ?>
                        <!--</table>-->
                    </b></div>
            <table autosize="1" border="0" width="100%" style="font-size:12px; text-align: left; word-wrap: break-word; overflow:auto;" cellspacing=0>
                <thead>
                    <tr>
                        <th colspan="4" style="text-align: center;">
                            <!--        <table style="font-size:12px;" width="100%">
                                    <thead><tr><td style="font-size:12px;" colspan="5" align="center">-->
                            <?php echo "DAILY CAUSE LIST FOR DATED : " . date('d-m-Y', strtotime($_POST['list_dt'])) . "<br/>";
                            echo $print_court_no;
                            if (strpos($bench_no, 'VACATION') !== false) {
                                echo "<br><B>VACATION BENCH</B>";
                            }
                            ?>
                            <!--                    </td>
                                        </tr>
                                    </thead>-->
                            <!--</table>-->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!--</table>
                <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>         -->
                    <?php //}


                    ?>
                    <!--    <tr><th colspan="4" style="text-align: center;">SECOND SUPPLEMENTARY LIST </th></tr>-->

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
                        <!--<tr><th colspan="4" style="text-align: left;"><br/><b><span style="font-size: 14px;">NOTE : Cases having more than 20 advocates denoted by <span style='color:red;'>***</span></b></th></tr>-->
                    <?php
                    }
                    ?>

                    <?php
                    $sql_scw = "select * from sc_working_days where working_date = '$list_dt' and is_nmd = 0 and display ='Y'";
                    $res_scw = $db->query($sql_scw);
                    if (count($res_scw->getResultArray()) > 0) {
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
                                <!-- <br><b><span style="font-size: 14px;">NOTE :- We Will appreciate if the parties get ready/file list of dates and brief submissions not exceeding three pages.</span></b><br> -->
                                <br><b><span style="font-size: 14px;">NOTE :- Parties to file list of dates and brief note of submissions not exceeding three pages, two days before the date of listing.</span></b><br>
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
                                // echo "<b>NOTE :- Parties to get ready with short synopsis of not more than three (3) pages each in the final hearing/disposal matters.</b><br>";
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

                    ?>

                    <?php
                    if ($first_jcd_cc == '210') {
                    ?>
                        <tr>
                            <th colspan="4" style="text-align: left;">
                                <!-- 17.03.2020 br><b><span style="font-size: 14px;">NOTE :- No request for adjournment will be granted in any of the matter(s) listed for hearing.</span></b><br -->
                            </th>
                        </tr>
                    <?php
                    }
                    //For printing text in Hon'ble U.U. Lalit's court
                    if ($first_jcd_cc == '216') {
                    ?>
                        <tr>
                            <th colspan="4" style="text-align: left;">
                                <!-- 14.03.2020 br><b><span style="font-size: 14px;">NOTE :- FRESH PASS OVER MATTERS WILL BE TAKEN UP BEFORE TAKING UP AFTER NOTICE MATTERS.</span></b><br -->
                            </th>
                        </tr>
                    <?php
                    }
                    //For printing text in Chief Court
                    if ($first_jcd_cc == '198' and $mainhead == 'M' and $board_type_mb == 'J') {
                    ?>
                        <!-- tr><th colspan="4" style="text-align: left;">
                              <br><b><span style="font-size: 14px;">NOTE :-<br>1. Request for 'not to delete a matter', shall be mentioned before the Mentioning Officer.
                              <br>2. All Circulations (If the matters are not on Board for the day) need not be mentioned before the Bench and
                                  the same shall be handed over to the concerned Court Masters in advance before 10.30 a.m.</span></b><br>
                          </th></tr --17.03.2020 -->
                    <?php
                    }
                    if ($first_jcd_cc == 210 and ($misc_day == 'NO' or $mainhead == 'F')) {
                        //regular or nmd days
                    ?>
                        <tr>
                            <th colspan="4" style="text-align: left;">
                                <!-- 17.03.2020 br><b><span style="font-size: 14px;">NOTE :- Fresh matters will be taken up first, before Three Judges Bench matters. -->

                            </th>
                        </tr>
                    <?php
                    }
                    if ($first_jcd_cc == 275 and $board_type_mb == 'J') {
                        //HMJ ravindra bhat, tsk-4663 d/t 06/04/2023
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
                            <?php echo get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'H'); ?>
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
                    $sql = "SELECT 
    (select group_concat(distinct h2.main_supp_flag) from heardt h2 where
    h2.next_dt = '$list_dt' AND h2.board_type = '$board_type' AND h2.mainhead = '$mainhead'
	AND h2.roster_id = '$roster_id' AND h2.clno = $part_no AND h2.brd_slno > 0) multi_main_supp_flag,
m.c_status, m.relief, u.name, us.section_name, h.*, l.purpose, c1.short_description, active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, YEAR(m.fil_dt) fil_year, m.fil_no, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno, rno, m.if_sclsc, m.diary_no_rec_date, $sub_head_name FROM heardt h 
            INNER JOIN main m on m.diary_no = h.diary_no 
            LEFT JOIN casetype c1 ON active_casetype_id = c1.casecode
            LEFT JOIN listing_purpose l ON l.code = h.listorder
            $leftjoin_submaster
            $leftjoin_subhead             
            LEFT JOIN users u ON u.usercode = m.`dacode` AND u.display = 'Y'
            LEFT JOIN usersection us ON us.id = u.section  
            LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'   
            WHERE next_dt = '$list_dt' $board_type_in and mainhead = '$mainhead' and 
            roster_id = '$roster_id' and clno = $part_no and brd_slno > 0 AND l.display = 'Y'  
            and (main_supp_flag = 1 OR main_supp_flag = 2) 
            GROUP BY h.diary_no ORDER BY h.brd_slno, if(h.conn_key=h.diary_no,'0000-00-00',99) ASC, 
            if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
            cast(SUBSTRING(m.diary_no,-4) as signed) ASC, cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC";
                    // echo $sql;
                    //# AND (h.diary_no = h.conn_key or h.conn_key = '0')
                    $res = $db->query($sql);
                    if (count($res->getResultArray()) > 0) {
                        foreach($res->getResultArray() as $row) {
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

                            $_SESSION['multi_main_supp_flag_print'] = $multi_main_supp_flag_print;

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
                                            //$print_mainhead = "MISCELLANEOUS HEARING";
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
                                //                if($subheading == "FOR JUDGEMENT" OR $subheading == "FOR ORDER"){
                                //                    echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>".$subheading."</td></tr>";
                                //                }
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
                            //            if($row['mf_active'] == "F"){
                            //                $m_f_filno = $row['fil_no_fh'];
                            //                $m_f_fil_yr = $row['fil_year_f'];
                            //            }
                            //            else{
                            $m_f_filno = $row['active_fil_no'];
                            $m_f_fil_yr = $row['active_reg_year'];
                            //            }
                            $filno_array = explode("-", $m_f_filno);
                            if ($filno_array[1] == $filno_array[2]) {
                                $fil_no_print = ltrim($filno_array[1], '0');
                            } else {
                                $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                            }
                            if ($row['reg_no_display'] == "") {
                                $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                            }
                            //            else if($row['reg_no_display'] != ""){
                            //                $comlete_fil_no_prt = $row['reg_no_display'];
                            //            }
                            else {
                                $comlete_fil_no_prt = $row['reg_no_display'];
                                /*if($filno_array[0]==31)
                            $comlete_fil_no_prt = $row['short_description']." D -".$fil_no_print."/".$m_f_fil_yr;
                        else {
                            $comlete_fil_no_prt = $row['short_description']."-".$fil_no_print."/".$m_f_fil_yr;
                        }*/
                            }
                            $padvname = "";
                            $radvname = "";
                            $impldname = "";
                            $intervenorname = "";
                            if ($part_no != "50" and $part_no != "51") {
                                /*                            $advsql = "SELECT a.*, GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'N' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) intervenor FROM
                        (SELECT a.diary_no, b.name, b.mobile,
                        GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
                        a.pet_res, a.adv_type, pet_res_no
                        FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$row["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
                        ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";
                          */
                                $advsql = "SELECT a.*, GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n,
GROUP_CONCAT(a.name,'',(CASE WHEN pet_res = 'N' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) intervenor FROM 
(SELECT a.diary_no, b.name, b.mobile, 
GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv, 
a.pet_res, a.adv_type, pet_res_no
FROM advocate a LEFT JOIN bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' 
WHERE a.diary_no='" . $row["diary_no"] . "' AND a.display = 'Y' GROUP BY a.diary_no, b.name
ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no";


                                /*
                        $advsql = "select group_concat(r_n) r_n, group_concat(p_n) p_n, group_concat(i_n) i_n, group_concat(intervenor) intervenor from (
                                                      select * from (SELECT a.*, GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'N' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) intervenor FROM
                        (
                        SELECT s.diary_no, a.advocate_name as name, a.mobile,
                        GROUP_CONCAT(IFNULL(ia.adv,'') ORDER BY IF(ia.pet_res in ('I','N'), 99, 0) ASC, ia.adv_type DESC, ia.pet_res_no ASC) grp_adv,
                        ia.pet_res, ia.adv_type, ia.pet_res_no
                        FROM e_services.sr_advocate_nomination s
                        inner join e_services.advocate_master a on a.id = s.nominated_to_advocate
                        inner join icmis.advocate ia on ia.diary_no = s.diary_no and ia.advocate_id = s.nominated_by_aor
                        where ia.display = 'Y' and s.diary_no = '".$row["diary_no"]."'
                        and s.removed_on is null and (('$list_dt' between s.hearing_date_from and s.hearing_date_to) or ('$list_dt' between s.hearing_date_from and s.hearing_date_to) )
                        GROUP BY ia.diary_no, name
                        ORDER BY IF(ia.pet_res in ('I','N'), 99, 0) ASC, ia.adv_type DESC, ia.pet_res_no ASC
                        ) a GROUP BY diary_no
                        ) nonaor
                        union
                        select * from (
                        SELECT a.*, GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'R' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) r_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'P' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) p_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'I' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) i_n,
                        GROUP_CONCAT(a.name,' - ',a.mobile,'',(CASE WHEN pet_res = 'N' THEN grp_adv END) ORDER BY adv_type DESC, pet_res_no ASC) intervenor FROM
                        (SELECT a.diary_no, b.name, b.mobile,
                        GROUP_CONCAT(IFNULL(a.adv,'') ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) grp_adv,
                        a.pet_res, a.adv_type, pet_res_no
                        FROM icmis.advocate a LEFT JOIN icmis.bar b ON a.advocate_id = b.bar_id AND b.isdead != 'Y' WHERE a.diary_no='".$row["diary_no"]."' AND a.display = 'Y' GROUP BY a.diary_no, b.name
                        ORDER BY IF(pet_res in ('I','N'), 99, 0) ASC, adv_type DESC, pet_res_no ASC) a GROUP BY diary_no) aor
                        ) z group by diary_no";
                        */
                                $resultsadv = $db->query($advsql);
                                if (count($resultsadv->getResultArray()) > 0) {
                                    $rowadv = $resultsadv->getResultArray();
                                    // if($jcd_rp !== "117,210" AND $jcd_rp != "117,198"){
                                    $radvname =  strtoupper($rowadv["r_n"]);
                                    $padvname =  strtoupper($rowadv["p_n"]);
                                    $impldname = strtoupper($rowadv["i_n"]);
                                    $intervenorname = strtoupper($rowadv["intervenor"]);
                                    // }
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

                                /*$section_ten_q = "SELECT dacode,section_name,name FROM da_case_distribution a
LEFT JOIN users b ON usercode=dacode
LEFT JOIN usersection c ON b.section=c.id
WHERE case_type=$casetype_displ AND $ten_reg_yr BETWEEN case_f_yr AND case_t_yr AND state='$row[ref_agency_state_id]' AND a.display='Y' ";*/
                                /*                    $section_ten_q="SELECT tentative_section(".$row["diary_no"].") as section_name";
                                            $section_ten_rs = mysql_query($section_ten_q) or die(__LINE__.'->'.mysql_error());
                                            if(mysql_num_rows($section_ten_rs)>0){
                                                $section_ten_row = mysql_fetch_array($section_ten_rs);
                                                $row['section_name']=$section_ten_row["section_name"];
                                            }*/
                            }

                            $section_ten_q = "SELECT tentative_section(" . $row["diary_no"] . ") as section_name";
                            $section_ten_rs = $db->query($section_ten_q);
                            if (count($section_ten_rs->getResultArray()) > 0) {
                                $section_ten_row = $section_ten_rs->getResultArray();
                                $row['section_name'] = $section_ten_row["section_name"];
                            } else {
                                $row['section_name'] = '';
                            }


                            //$cate_old_id1 = "";
                            //$sql_sm = "SELECT category_sc_old FROM mul_category mc INNER JOIN submaster s ON s.id = mc.submaster_id WHERE mc.diary_no = '$diary_no' limit 1";
                            //$res_sm = mysql_query($sql_sm) or die(__LINE__.'->'.mysql_error());
                            //if(mysql_num_rows($res_sm)>0){
                            //      $cate_old_id = mysql_fetch_array($res_sm);
                            //      $cate_old_id1=$cate_old_id['category_sc_old'];
                            //}
                            $doc_desrip = "";
                            $listed_ias = $row['listed_ia'];
                            $listed_ia = rtrim(trim($listed_ias), ",");
                            if ($listed_ias) {
                                $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In <br>";

                                $sql_dc = "SELECT * FROM (SELECT h.diary_no, d.docnum, d.docyear, d.doccode1, 
(CASE WHEN dm.doccode1 = 19 THEN other1 ELSE docdesc END) docdesp, 
d.other1, d.iastat FROM heardt h
INNER JOIN docdetails d ON d.diary_no = h.diary_no 
INNER JOIN docmaster dm ON dm.doccode1 = d.doccode1 AND dm.doccode = d.doccode
WHERE h.diary_no = '" . $row["diary_no"] . "' AND d.doccode = 8 AND dm.display = 'Y' AND d.iastat = 'P' AND 
# FIND_IN_SET(CAST(CONCAT(docnum,docyear) AS UNSIGNED), REPLACE(TRIM(BOTH ',' FROM listed_ia), '/', '')) > 0
FIND_IN_SET(CAST(CONCAT(docnum,docyear) as SIGNED), TRIM(BOTH ',' FROM REPLACE(REPLACE(REPLACE(listed_ia,'/',''),' ',''),' ',''))) > 0
) a
WHERE docdesp != ''
ORDER BY docdesp";
                                $rs_dc = $db->query($sql_dc);
                                if (count($rs_dc->getResultArray()) > 0) {
                                    foreach($rs_dc as $row_dc) {
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

                                /* if($count_advocates > 20){
                             $adv_count_stars_display = "<br>";
                             $output .= "<tr><td colspan=4><span style='color:red;'><b>***</b></span></td></tr>";
                         }
                         $hearing_mode = "";
                         $hearing_mode = f_get_hybrid_hearing_mode($row["diary_no"]);
                         if ($hearing_mode == 'P') {
                            // $output .= "<tr style='padding:2px;'><td colspan=4><span ><u>HEARING MODE : HYBRID</u></span></td></tr>";
                         }
                         else{
                            // $output .= "<tr style='padding:2px;'><td colspan=4><span ><u>HEARING MODE : VC</u></span></td></tr>";
                         }*/
                            }

                            if ($mainhead == "F") {

                                // $hearing_mode = "";
                                //  $hearing_mode = f_get_hybrid_hearing_mode($diary_no);
                                /*                            if ($hearing_mode == 'P') {
                           //                             $output .= "<tr style='padding:2px;'><td colspan=4><U>MODE OF HEARING : PHYSICAL</U></td></tr>";
                                                    }
                                                    if ($hearing_mode == 'V') {
                             //                           $output .= "<tr style='padding:2px;'><td colspan=4><U>MODE OF HEARING : VC</U></td></tr>";
                                                    }
                                                    if ($hearing_mode == 'H') {
                               //                         $output .= "<tr style='padding:2px;'><td colspan=4><U>MODE OF HEARING : HYBRID</U></td></tr>";
                                                    }*/
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
                                $sq_lct = "SELECT lct_dec_dt, lct_caseno, lct_caseyear, short_description type_sname
                FROM lowerct a
                LEFT JOIN casetype ct ON ct.casecode = a.lct_casetype AND ct.display = 'Y'
                WHERE a.diary_no = '$diary_no' AND a.is_order_challenged = 'Y' AND lw_display = 'Y' AND ct_code =4 ORDER BY a.lct_dec_dt desc";
                                $rs_lct = $db->query($sq_lct);
                                if (count($rs_lct->getResultArray()) > 0) {
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    foreach($rs_lct->getResultArray() as $ro_lct) {
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

                                //$output .= get_cl_brd_remark($diary_no)."</td></tr>";
                            }

                            /*$output .= "<tr><td style='border-bottom:2px dotted #999999; padding-bottom:1px; size : 1px; height:1px;' colspan=4></td></tr>";   */
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
                                                                        get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'F'); ?> </th>
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
            if (count($multi_main_supp_flag) > 1) {
                echo $multi_main_supp_flag_print;
            } else {

                $rslt_is_printed = 0;
                $rslt_is_printed = f_cl_is_printed($list_dt, $part_no, $mainhead, $roster_id);
                if ($rslt_is_printed == 0) {
                    //            if($rslt_is_printed != 0){
                    $sql_case_updation = "select always_allowed_users from case_status_flag where display_flag = 0 and date(to_date)='0000-00-00' 
                and flag_name='cl_publish' and find_in_set($ucode,always_allowed_users) > 0 ";
                    $result_case_updation = $db->query($sql_case_updation);
                    if (count($result_case_updation->getResultArray()) > 0) {
                        // include_once("../common/ip_bind.php");
            ?>
                        <input name="prnnt1" type="button" id="ebublish" value="e-Publish"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                    } else {
                        echo "Not Published";
                    }
                } else {
                    echo "Already Published";
                }
                ?>
                <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
                    <!--    <input name="sh4" type="button" id="sh4" onClick="toggle_note4(this.id);" value="Header Note">
    <input name="sh5" type="button" id="sh5" onClick="toggle_note5(this.id);" value="Footer Note">
    <input name="sh3" type="button" id="sh3" onClick="toggle_note3(this.id);" value="Drop Note">    -->
                </span>
                <input name="prnnt1" type="button" id="prnnt1" value="Print">
            <?php } ?>
        </div>
        <center></center>
<?php
    } else {
        echo "Roster Not Available";
    }
    //  echo $output;
// }
?>