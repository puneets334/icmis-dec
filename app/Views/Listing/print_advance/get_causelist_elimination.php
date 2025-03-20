<style>
    table tr:nth-child(even) td {
        background: none !important;
    }
    th, td {
        border: none;
        padding: 0px 10px !important;
    }
    #dv_content1{
        height: 82vh;
        overflow-y: scroll;
    }
    .fixed-bottom{position: absolute !important;}
</style>
    <div id="prnnt" style="font-size:12px;">
    <?php if ($isPrinted == 0) { ?>
        <div align="center" style="font-size:12px;">
            <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px" /><br />
            <span style="text-align: center;font-weight: 600;font-size: 14px;font-family: verdana;" align="center">
                SUPREME COURT OF INDIA
            </span>
        </div>

        <table border="0" width="100%" style="font-size:12px; text-align: left; font-family: verdana; background: #ffffff;line-height: 1.9;" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="4" style="text-align: center;font-weight: 600;font-size: 13px;font-family: verdana;">
                        <?php echo "ELIMINATION LIST DATED : " . date('d-m-Y', strtotime($list_dt)); ?>
                    </th>
                </tr>
            </thead>
            <?php if (!empty($getCases)) : ?>
                <tbody>
                    <?php foreach ($getCases as $case) :
                        $output = '';
                        //$case["stagename"] data not found know for testing we set null
                        $case["stagename"] = '';
                        $coram = $case['coram'];
                        $fix_dt = date('d-m-Y', strtotime($case['next_dt']));
                        $main_supp_fl = $case['main_supp_flag'];
                        $diary_no = $case['diary_no'];

                        if ($mainhead == "F") {
                            $retn = isset($case["sub_name1"]) ? $case["sub_name1"] : '';
                            if (isset($case["sub_name2"]) && $case["sub_name2"])
                                $retn .= " - " . $case["sub_name2"];
                            if (isset($case["sub_name3"]) && $case["sub_name3"])
                                $retn .= " - " . $case["sub_name3"];
                            if (isset($case["sub_name4"]) && $case["sub_name4"])
                                $retn .= " - " . $case["sub_name4"];
                            $subheading = $retn;
                        } else {
                            $subheading = $case["stagename"];
                        }

                        if ($mnhead_print_once == 1) {
                            if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                                if ($case['board_type'] == 'C') {
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
                                <th colspan="4" style="text-align: center;text-decoration: underline;font-size: 14px;font-family: verdana;font-weight: 600;">
                                    <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                        echo $print_mainhead;
                                    } ?>
                                </th>
                            </tr>
                            <tr style="font-weight: bold; background-color:#cccccc;font-size: 14px;">
                                <td style="width:5%;"><b>SNo.</b></td>
                                <td style="width:20%;"><b>Case No.</b></td>
                                <td style="width:35%;"><b>Petitioner / Respondent<b></td>
                                <td style="width:40%;"><b>
                                    <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") { ?>
                                        Petitioner/Respondent Advocate
                                    <?php } ?>
                                    </b>
                                </td>
                            </tr>
                    <?php
                            $mnhead_print_once++;
                        }

                        if ($subheading != $subheading_rep) {
                            if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                                $subheading_rep = $subheading;
                            }
                        }

                        if ($case['diary_no'] == $case['conn_key'] or $case['conn_key'] == 0) {
                            $print_srno = $psrno;
                            $con_no = "0";
                            $is_connected = "";
                        } else if ($case['listed'] == 1) {
                            $is_connected = "<span style='color:red;'>Connected</span><br/>";
                        }

                        $m_f_filno = $case['active_fil_no'];
                        $m_f_fil_yr = $case['active_reg_year'];
                        $filno_array = explode("-", $m_f_filno);
                        
                        /*if ($filno_array[0] == $filno_array[1]) {
                            $fil_no_print = ltrim($filno_array[0], '0');
                        } else {
                            $fil_no_print = ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');
                        }*/
                        $fil_no_print = '';
                        if (isset($filno_array[0])) {
                            if (isset($filno_array[1]) && $filno_array[0] == $filno_array[1]) {
                                $fil_no_print = ltrim($filno_array[0], '0');
                            } elseif (isset($filno_array[1])) { 
                                $fil_no_print = ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');
                            } else {
                                $fil_no_print = ltrim($filno_array[0], '0');
                            }
                        } else {
                            $fil_no_print = '';
                        }
                        
                        if ($case['active_fil_no'] == "") {
                            $comlete_fil_no_prt = "Diary No. " . substr_replace($case['diary_no'], '-', -4, 0);
                        } else {
                            $comlete_fil_no_prt = $case['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
                        }

                        if (!empty($case['advocate'])) {
                            /*foreach ($case['advocate'] as $advocate) :
                                $radvname = $advocate["r_n"];
                                $padvname = $advocate["p_n"];
                                $impldname = $advocate["i_n"];
                            endforeach;*/
                            $radvname = $case['advocate']["r_n"];
                            $padvname = $case['advocate']["p_n"];
                            $impldname = $case['advocate']["i_n"];
                            $radvname = !empty($radvname) ? str_replace(",", ", ", trim($radvname, ",")) : '';
                            $padvname = !empty($padvname) ? str_replace(",", ", ", trim($padvname, ",")) : '';
                            $impldname = !empty($impldname) ? str_replace(",", ", ", trim($impldname, ",")) : '';
                        }

                        if ($case['pno'] == 2) {
                            $pet_name = $case['pet_name'] . " AND ANR.";
                        } else if ($case['pno'] > 2) {
                            $pet_name = $case['pet_name'] . " AND ORS.";
                        } else {
                            $pet_name = $case['pet_name'];
                        }

                        if ($case['rno'] == 2) {
                            $res_name = $case['res_name'] . " AND ANR.";
                        } else if ($case['rno'] > 2) {
                            $res_name = $case['res_name'] . " AND ORS.";
                        } else {
                            $res_name = $case['res_name'];
                        }

                        if (!empty($case['tentativeSec'])) {
                            foreach ($case['tentativeSec'] as $tentativeSec) :
                                $case['section_name'] = $tentativeSec["section_name"];
                            endforeach;
                        }

                        if ($is_connected != '') {
                            $print_srno = "";
                        } else {
                            $print_srno = $print_srno;
                            $psrno++;
                        }

                        $output .= "<tr><td>$print_srno</td><td rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $case['section_name'] . "</td><td>" . $pet_name . "</td><td>" . $padvname;
                        $output .= "</td></tr>";
                        $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                        if ($jcd_rp != "117,210" and $jcd_rp != "117,198") {
                            //$output .= "Versus";
                        }
                        $output .= "</td></tr>";
                        $output .= "<tr><td></td><td></td><td";

                        $output .= ">" . $res_name . "</td><td>" . $radvname;
                        if ($impldname) {
                            $output .= "<br/>" . $impldname;
                        }
                        $output .= "</td></tr>";
                        if ($mainhead == "M" or $mainhead == "F") {
                            $output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'>";
                            if ($case['listorder'] == '4' or $case['listorder'] == '5')
                                $output .= "{" . $case['purpose'] . " for $fix_dt } ";

                            $output .= "</td></tr>";
                        }

                        echo $output;
                        $output = "";
                    endforeach; ?>
                </tbody>
        
    <?php else : ?>
        <?php 
            if(isset($cl_content))
            echo base64_decode($cl_content); 
        ?>
        <h3 class="bg-warning text-center p-2">No Records Found</h3>
    <?php endif; ?>
    </table>
    <br>
    <p align='left' style="font-size: 14px;"><b>NEW DELHI<BR />
            <?php
            date_default_timezone_set('Asia/Kolkata');
            echo date('d-m-Y H:i:s');
            ?>
        </b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 14px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div><!-- End Of Print Div-->
    <div class="footer bg-light text-center border-top fixed-bottom">
    <?php if ($isPrinted == 0) { ?>
        <button class="btn btn-primary" name="prnnt1" type="button" id="ebublish">e-Publish</button>
    <?php }else{ ?>
        <?  echo "<h3 class='text-success'>Already Published</h3>"; ?>
    <?php } ?>
        <button class="btn btn-primary" name="prnnt1" type="button" id="prnnt1">Print</button>
        <?php
        if ($ucode == '1' or $ucode == '9782' or $ucode == '9785') {
        ?>
        <?php } ?>
    </div>
    <center></center>
    </div>
<?php } else { ?>
    <? echo '<h3 class="p-2 text-center bg-danger">No Records Found</h3>' ?>
<?php } ?>