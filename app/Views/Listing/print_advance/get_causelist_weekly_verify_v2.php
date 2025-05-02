<div id="prnnt" style="font-size:12px;">
<?php if (!empty($getCases)) : ?>
      
            <div align="center" style="font-size:12px;">
                <span style="font-size:12px;" align="center">
                    <b>
                        <img src="<?php echo base_url('images/scilogo.png'); ?>"width="50px" height="80px" /><br />
                        SUPREME COURT OF INDIA
                        <br /><br />
                        [ IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES<br />
                        ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS<br />
                        LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST ]
                        <br />
                    </b>
                </span>

                <?php
                    $list_year = date('Y', strtotime($list_dt));
                    $for_2017 = ($list_year == 2017) ? 18 : 0;

                    echo "WEEKLY LIST No. X OF $list_year FROM: " . date('d-m-Y', strtotime($list_dt)) .
                        " To " . date('d-m-Y', strtotime($list_dt_to));

                    $dir_dt = glob("/home/judgment/cl/wk/$list_dt*");
                    $dir = glob("/home/judgment/cl/wk/$list_year*");

                    $num_files = count($dir);
                    $num_files_dt = count($dir_dt);

                    if ($num_files == 0) {
                        $num_files = 1;
                    } elseif ($num_files_dt == 0 && $num_files != 0) {
                        $num_files += 1;
                    }

                    echo "WEEKLY LIST No. $num_files OF $list_year FROM: " . date('d-m-Y', strtotime($list_dt)) .
                        " To " . date('d-m-Y', strtotime($list_dt_to));

                    $_SESSION['json_weekly_num_file'] = $num_files; 
                ?>
            </div>

            <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing="0">
            <?php foreach ($getCases as $case) :
                $bench_no = $case['bench_no'];
                $roster_id = $case['id'];
                $bench_session = $case['session'];
                $bench_time = $case['frm_time'];
                $bench_judge_name = stripcslashes(str_replace(",", "<br/>", $case['jnm']));
                $bench_court = $case['courtno'];
                $jcd_rp = $case['jcd'];
                $board_type_mb = $case['board_type_mb'];
            ?>

                <?php if ($board_type_mb !== "R") : ?>
                    <?php
                    $virtualCourts = [
                        "31" => "Virtual Court No. 1",
                        "32" => "Virtual Court No. 2",
                        "33" => "Virtual Court No. 3",
                        "34" => "Virtual Court No. 4",
                        "35" => "Virtual Court No. 5",
                        "36" => "Virtual Court No. 6",
                        "37" => "Virtual Court No. 7",
                        "38" => "Virtual Court No. 8",
                        "39" => "Virtual Court No. 9",
                        "40" => "Virtual Court No. 10",
                        "41" => "Virtual Court No. 11",
                        "42" => "Virtual Court No. 12",
                        "43" => "Virtual Court No. 13",
                        "44" => "Virtual Court No. 14",
                        "45" => "Virtual Court No. 15",
                        "46" => "Virtual Court No. 16",
                        "47" => "Virtual Court No. 17",
                    ];

                    if ($bench_court == "1") {
                        $print_court_no = "CHIEF JUSTICE'S COURT";
                    } elseif (isset($virtualCourts[$bench_court])) {
                        $print_court_no = $virtualCourts[$bench_court];
                    } else {
                        $print_court_no = "COURT NO. : " . $bench_court;
                    }
                    ?>
                <?php endif; ?>    
                <thead>
                    <tr>
                        <th colspan="4" style="text-align: center;">
                            <?= $print_court_no; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="4" style="text-align: center;">
                            <?= $bench_judge_name; ?>
                            <?php if ($bench_session == "After Regular Bench") : ?>
                                <br />THIS BENCH WILL ASSEMBLE AFTER THE NORMAL COURT IS OVER
                            <?php endif; ?>
                        </th>
                    </tr>
                    <?php if (!empty($case['details'])): ?>
                        <?php foreach ($case['details'] as $detail): ?>
                            <?php
                       
                            $coram = $detail['coram'];
                            $fix_dt = date('d-m-Y', strtotime($detail['next_dt']));
                            $main_supp_fl = $detail['main_supp_flag'];
                            $diary_no = $detail['diary_no'];

                            if ($mainhead == "F") {
                                $detail["sub_name1"] = '';
                                $detail["sub_name2"] = '';
                                $detail["sub_name3"] = '';
                                $detail["sub_name4"] = '';
                                $retn = $detail["sub_name1"];
                                if ($detail["sub_name2"])
                                    $retn .= " - " . $detail["sub_name2"];
                                if ($detail["sub_name3"])
                                    $retn .= " - " . $detail["sub_name3"];
                                if ($detail["sub_name4"])
                                    $retn .= " - " . $detail["sub_name4"];
                                //$subheading = $retn;
                            } else {
                                $subheading = $detail["stagename"];
                            }

                            if ($mnhead_print_once == 1) {
                                if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                                    if ($detail['board_type'] == 'C') {
                                        $print_mainhead = "CHAMBER MATTERS";
                                    } else {
                                        $print_mainhead = "MISCELLANEOUS HEARING";
                                    }
                                }
                                if ($mainhead == 'F')
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
                                    <th colspan="4" style="text-align: center; text-decoration: underline;">
                                        <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                            echo $print_mainhead;
                                        } ?>
                                    </th>
                                </tr>
                                <tr style="font-weight: bold; background-color:#cccccc;">
                                    <td style="width:5%;">SNo.</td>
                                    <td style="width:20%;">Case No.</td>
                                    <td style="width:35%;">Petitioner / Respondent</td>
                                    <td style="width:40%;">
                                        <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") { ?>
                                            Petitioner/Respondent Advocate
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php $mnhead_print_once++;
                            } ?>

                            <?php if(!empty($subheading != $subheading_rep)) {
                                if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                    echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                                    $subheading_rep = $subheading;
                                }
                            } ?>

                            <?php
                            if ($detail['diary_no'] == $detail['conn_key'] or $detail['conn_key'] == 0) {
                                // $print_brdslno = $detail['brd_slno'];
                                $print_srno = $psrno;
                                $con_no = "0";
                                $is_connected = "";
                            } else {
                                // $print_brdslno = "&nbsp;".$detail["brd_slno"].".".++$con_no;
                                $is_connected = "<span style='color:red;'>Connected</span><br/>";
                            }

                            $m_f_filno = $detail['active_fil_no'];
                            $m_f_fil_yr = $detail['active_reg_year'];

                            $filno_array = explode("-", $m_f_filno); 
                            //pr($filno_array);
                            if ($filno_array[0] == $filno_array[1]) {
                                $fil_no_print = ltrim($filno_array[0], '0');
                            } else {
                                $fil_no_print = ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');
                            }
                            if ($detail['reg_no_display'] == "") {
                                $comlete_fil_no_prt = "Diary No. " . substr_replace($detail['diary_no'], '-', -4, 0);
                            } else {
                                $comlete_fil_no_prt = $detail['reg_no_display'];
                            }
                            if (!empty($detail['advocate'])):
                               foreach ($detail['advocate'] as $advocate): 

                                    $radvname = strtoupper($advocate["r_n"]);
                                    $padvname = strtoupper($advocate["p_n"]);
                                    $impldname = strtoupper($advocate["i_n"]);
                                    $intervenorname = strtoupper($advocate["intervenor"]);
                                endforeach;
                            endif;
                            if ($detail['pno'] == 2) {
                                $pet_name = $detail['pet_name'] . " AND ANR.";
                            } else if ($detail['pno'] > 2) {
                                $pet_name = $detail['pet_name'] . " AND ORS.";
                            } else {
                                $pet_name = $detail['pet_name'];
                            }
                            if ($detail['rno'] == 2) {
                                $res_name = $detail['res_name'] . " AND ANR.";
                            } else if ($detail['rno'] > 2) {
                                $res_name = $detail['res_name'] . " AND ORS.";
                            } else {
                                $res_name = $detail['res_name'];
                            }
                            if (($detail['section_name'] == null or $detail['section_name'] == '') and $detail['ref_agency_state_id'] != '' and $detail['ref_agency_state_id'] != 0) {
                                if ($detail['active_reg_year'] != 0)
                                    $ten_reg_yr = $detail['active_reg_year'];
                                else
                                    $ten_reg_yr = date('Y', strtotime($detail['diary_no_rec_date']));

                                if ($detail['active_casetype_id'] != 0)
                                    $casetype_displ = $detail['active_casetype_id'];
                                else if ($detail['casetype_id'] != 0)
                                    $casetype_displ = $detail['casetype_id'];
                            }

                            if (!empty($case['tentativeSec'])) {
                                foreach ($case['tentativeSec'] as $tentativeSec) {
                                    $detail['section_name'] = $tentativeSec["section_name"];
                                }
                            } else {
                                $detail['section_name'] = '';
                            }

                            if ($is_connected != '') {
                                $print_srno = "";
                            } else {
                                $print_srno = $print_srno;
                                $psrno++;
                            }

                            $count_advocates = f_get_advocate_count($detail["diary_no"]);
                            $adv_count_stars_display = "";
                            $output = "";
                            if ($count_advocates > 20) {
                                $adv_count_stars_display = "<br>";
                                $output .= "<tr><td></td><td colspan=3><span style='color:red;'><b>***</b></span></td></tr>";
                            }

                            $output .= "<tr><td>$print_srno</td><td rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $detail['section_name'] . "</td><td>" . $pet_name . "</td><td>" . str_replace(",", ", ", trim($padvname, ","));
                            $output .= "</td></tr>";

                            $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                            if ($jcd_rp != "117,210" and $jcd_rp != "117,198") {
                                //$output .= "Versus";
                            }
                            $output .= "</td></tr>";
                            $output .= "<tr><td></td><td></td><td";

                            $output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
                            if ($impldname) {
                                $output .= "<br/>" . str_replace(",", ", ", trim($impldname, ","));
                            }
                            if ($intervenorname) {
                                $output .= "<br/>" . str_replace(",", ", ", trim($intervenorname, ","));
                            }
                            $output .= "</td></tr>";
                            if ($mainhead == "M" or $mainhead == "F") {
                                $output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'>";
                                //$output .= $row['section_name'];
                                //if($jcd_rp != "117,210" AND $jcd_rp != "117,198"){


                                if ($detail['listorder'] == '4' or $detail['listorder'] == '5')
                                    $output .= "{" . $detail['purpose'] . " for $fix_dt } ";

                                if (!empty($case['lowerCourtDt'])) {
                                    foreach ($case['lowerCourtDt'] as $lowerCourtDt) {
                                        $detail['section_name'] = $lowerCourtDt["section_name"];
                                    }
                                } else {
                                    $detail['section_name'] = '';
                                }


                                $output .= get_cl_brd_remark($diary_no) . "</td></tr>";
                            }
                            $output .= "<tr><td style='border-bottom:2px dotted #999999; padding-bottom:1px; size : 1px; height:1px;' colspan=4></td></tr>";
                                echo $output;
                            ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            <?php get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'H'); ?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: center;">
                            <?php get_drop_note_print($list_dt, $mainhead, $roster_id); ?>
                        </th>
                    </tr>
                    <?php if (!empty($bench_time)) : ?>
                        <tr>
                            <th colspan="4" style="text-align: center;">
                                (TIME : <?= $bench_time; ?>)
                            </th>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php endforeach; ?>
            </table>
        

        <?php get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'F'); ?>
    <?php else : ?>
        <h3 class="bg-warning text-center p-2">No Records Found</h3>
    <?php endif; ?>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR />
        <?php 
            date_default_timezone_set('Asia/Kolkata'); 
            echo date('d-m-Y H:i:s'); 
        ?>
        </b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div>
    <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;" class="p-3">
        <button class="btn btn-primary" name="mbublish" type="button" id="mbublish">Merge All And Publish</button>
        <button class="btn btn-primary"  name="prnnt1" type="button" id="ebublish">e-Publish</button>
        <button class="btn btn-primary"  name="prnnt1" type="button" id="prnnt1">Print</button>
        <?php

        if ($ucode == '1' or $ucode == '9782' or $ucode == '9785') {
        ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn btn-primary" name="unpub" type="button" id="unpub">Un-Publish</button>
        <?php } ?>
    </div>
    <center></center>
</div>