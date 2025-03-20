<?php {

?>

    <head>
        <script src="monitoring_report.js"></script>
        <style>
            #newb {
                position: fixed;
                padding: 12px;
                left: 50%;
                top: 50%;
                display: none;
                color: black;
                background-color: #D3D3D3;
                border: 2px solid lightslategrey;
                height: 100%;
            }

            #newcs {
                position: fixed;
                padding: 12px;
                left: 50%;
                top: 50%;
                display: none;
                color: black;
                background-color: #D3D3D3;
                border: 2px solid lightslategrey;
                height: 100%;
            }

            #overlay {
                background-color: #000;
                opacity: 0.7;
                filter: alpha(opacity=70);
                position: fixed;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
            }
        </style>
    </head>

    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Moniotring Team - Verification Module</title>

        <style>
            fieldset {
                padding: 5px;
                background-color: #F5FAFF;
                border: 1px solid #0083FF;
            }

            legend {
                background-color: #E2F1FF;
                width: 100%;
                text-align: center;
                border: 1px solid #0083FF;
                font-weight: bold;
            }

            .table3,
            .subct2,
            .subct3,
            .subct4,
            #res_on_off,
            #resh_from_txt {
                display: none;
            }

            .toggle_btn {
                text-align: left;
                color: #00cc99;
                font-size: 18px;
                font-weight: bold;
                cursor: pointer;
            }

            div,
            table,
            tr,
            td {
                font-size: 10px;
            }

            .tooltip {
                position: relative;
                display: inline-block;
                border-bottom: 1px dotted black;
            }

            .tooltip .tooltiptext {
                visibility: hidden;
                width: 120px;
                background-color: #d0f1af;
                color: #aa810a;
                text-align: center;
                border-radius: 6px;
                padding: 5px 0;
                /* Position the tooltip */
                position: absolute;
                z-index: 1;
            }

            .tooltip:hover .tooltiptext {
                visibility: visible;
            }
        </style>
    </head>

    <body>
        <form method="post" action="">
            <?php

            ?>
            <div id="dv_content1">
                <?php {

                ?>
                    <div style="text-align: center">


                        <?php


                        $str = $str;
                        $remarks = $remarks;
                        $str_exp = $str_exp;
                        $list_dt = $list_dt;
                        $userid_str = $userid_str;
                        $ucode = $ucode;
                        $usertype = $usertype;
                        $ro_u2 = $MonitoringModel->getUserName($userid_str);
                        $username_uby = $ro_u2['name'];
                        $sec_id = '0';


                        if ($usertype == '14' and $ucode != 199 and $ucode != 205 and $ucode != 801 and $ucode != 2444 and $ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184) {

                            $ro_u = $MonitoringModel->getAllDa($ucode);

                            $all_da = $ro_u['allda'];
                            $mdacode = "";
                        } else if (($usertype == '17' or $usertype == '50' or $usertype == '51') and ($ucode != 199 and $ucode != 205 and $ucode != 801 and $ucode != 2444 and $ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184)) {
                            $mdacode = "";
                        } else {
                            $mdacode = "";
                        }


                        $sec_id = "0";
                        if ($sec_id == "0") {
                            $sec_id = "";
                            $sec_id2 = "";
                        } else {
                            $sec_id = "AND us.id = '" . $sec_id . "'";
                            $sec_id2 = "AND us.id IS NOT NULL";
                        }

                        $condition = "";

                        if ($remarks == 1) {
                            $condition = " AND tt.remark_id = '1' ";  
                            $heading = "CASES ACCEPTED DETAIL REPORT";
                        } else if ($remarks == 2) {
                            $condition = " AND (tt.remark_id <> '1' OR tt.remark_id IS NULL OR tt.remark_id = '') ";  
                            $heading = "CASES DEFECTIVE DETAIL REPORT";
                        } else if ($remarks == 0) {
                            $condition = " ";
                            $heading = "CASES VERIFIED DETAIL REPORT";
                        }



                        $res = $MonitoringModel->getROPPurpose($sec_id, $userid_str, $list_dt, $mdacode, $sec_id2, $condition); 
                      
                        echo "<H3>$heading</H3>";
                        if (!empty($res)) {
                            echo "Verified On " . date('d-m-Y', strtotime($list_dt)) . " By " . $username_uby;
                        ?>

                            <table align="left" width="100%" border="0px;" style="table-layout: fixed;">

                                <tr style="background: #918788;">
                                    <td width="5%" style="font-weight: bold; color: #dce38d;">SNo</td>
                                    <td width="12%" style="font-weight: bold; color: #dce38d;">Diary/Reg No</td>
                                    <td width="5%" style="font-weight: bold; color: #dce38d;">ROP</td>
                                    <td width="14%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</td>
                                    <td width="14%" style="font-weight: bold; color: #dce38d;">Advocate</td>
                                    <td width="10%" style="font-weight: bold; color: #dce38d;">Heading/Category</td>
                                    <td width="14%" style="font-weight: bold; color: #dce38d;">LastOrder / Statutory</td>
                                    <td width="12%" style="font-weight: bold; color: #dce38d;">IA</td>
                                    <td width="6%" style="font-weight: bold; color: #dce38d;">Purpose</td>
                                    <td width="8%" style="font-weight: bold; color: #dce38d;">Action</td>

                                </tr>
                                <?php
                                $sno = 1;
                                $psrno = 1;
                                foreach ($res as $row) {
                                    $sno1 = $sno % 2;
                                    $dno = $row['diary_no'];
                                    $verify_time = "<br><span style='color:red; font-size:10px;'>Verify Time : " . date('h:i:s', strtotime($row['verify_dt'])) . "</span>";
                                    $coram = $row['coram'];
                                    $purpose = $row['purpose'];
                                    $lastorder = $row['lastorder'];
                                    $stagename = $row['stagename'];
                                    $diary_no_rec_date = "Diary Dt " . change_date_format($row['diary_no_rec_date']);

                                    $fil_dt = "Reg Dt " . change_date_format($row['fil_dt']);

                                    $rs_earlier_verify = $MonitoringModel->getEntDate($dno);


                                    $ro_earlier_verify_record = "";
                                    if (!empty($rs_earlier_verify)) {
                                        $ro_earlier_verify = $rs_earlier_verify;
                                        $ro_earlier_verify_record = "<br><span style='background-color:yellow; font-size:10px;'>Last Verified On " . date('d-m-Y', strtotime($ro_earlier_verify['max_edt'])) . "</span>";
                                    }
                                    $cate_old_id1 = "";

                                    $res_sm = $MonitoringModel->getCatdata($dno);


                                    if (!empty($res_sm)) {
                                        foreach ($res_sm as $cate_old_id) {
                                            $cate_old_id1 .= $cate_old_id['category_sc_old'] . ",";
                                        }
                                        $cat_code = rtrim($cate_old_id1, ",");
                                    }
                                    // Remove vkg (board_type and mainhead is not found)
                                    $board_type = '';
                                    $mainhead = '';

                                    // $verify_str = $dno . "_" . $board_type . "_" . $mainhead;
                                    $verify_str = $dno . "_" . $board_type . "_" . $mainhead;


                                    if ($sno1 == '1') { ?>
                                        <tr style=" background: #ececec;" id="<?php echo $verify_str; ?>">
                                        <?php } else { ?>
                                        <tr style=" background: #f6e0f3;" id="<?php echo $verify_str; ?>">
                                        <?php
                                    }
                                    if ($row['diary_no'] == $row['main_key'] or $row['main_key'] == 0 or $row['main_key'] == "") {
                                        $print_srno = $psrno;
                                        $con_no = "0";
                                        $is_connected = "";
                                        $is_main = "";
                                        if ($row['diary_no'] == $row['main_key']) {
                                            $print_srno = $psrno;
                                            $con_no = "0";
                                            $is_connected = "";
                                            $is_main = "<span style='color:blue;'>Main</span><br/>";
                                        }
                                    } else if ($row['listed'] == 1 or ($row['diary_no'] != $row['main_key'] and $row['main_key'] != null)) {
                                        $is_main = "";
                                        $is_connected = "<span style='color:red;'>Connected</span><br/>";
                                    }
                                    $m_f_filno = $row['active_fil_no'];
                                    $m_f_fil_yr = $row['active_reg_year'];

                                    $filno_array = explode("-", $m_f_filno);
                                    if (isset($filno_array[1]) && isset($filno_array[2])) {
                                        if ($filno_array[1] == $filno_array[2]) {
                                            $fil_no_print = ltrim($filno_array[1], '0');
                                        } else {
                                            $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                                        }
                                    } else {

                                        $fil_no_print = "";
                                    }

                                    $diary_str = substr_replace($row['diary_no'], '-', -4, 0);
                                    $d_str = explode("-", $diary_str);
                                    $comlete_fil_no_prt = "Diary No. <a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d_str[0]','$d_str[1]','','','');\" href='#'>" . $d_str[0] . '/' . $d_str[1] . "</a><br>" . $diary_no_rec_date;

                                    if (!empty($fil_no_print)) {
                                        $comlete_fil_no_prt .= "<br>" . $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr . "<br>" . $fil_dt;
                                    } else {
                                        $comlete_fil_no_prt .= "<br>Unreg.";
                                    }

                                    $padvname = "";
                                    $radvname = "";
                                    $resultsadv = $MonitoringModel->getAdv($row["diary_no"]);
                                    if (!empty($resultsadv)) {
                                        $rowadv = $resultsadv;
                                        $radvname = $rowadv["r_n"];
                                        $padvname = $rowadv["p_n"];
                                        $impldname = $rowadv["i_n"];
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
                                    //pr($row);


                                    if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {

                                        if ($row['active_reg_year'] != 0)
                                            $ten_reg_yr = $row['active_reg_year'];
                                        else
                                            $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                                        if ($row['active_casetype_id'] != 0)
                                            $casetype_displ = $row['active_casetype_id'];
                                        else if ($row['casetype_id'] != 0)
                                            $casetype_displ = $row['casetype_id'];
                                        $section_ten_rs = $MonitoringModel->getSectionTenRs($casetype_displ, $ten_reg_yr, $row['ref_agency_state_id']);


                                        if (!empty($section_ten_rs)) {
                                            $section_ten_row = $section_ten_rs;
                                            $row['section_name'] = $section_ten_row["section_name"];
                                        }
                                    }




                                    if ($is_connected != '') {
                                        $print_srno = "";
                                    } else {
                                        $print_srno = $print_srno;
                                        $psrno++;
                                    }



                                        ?>
                                        <td align="right" style='vertical-align: top;'>
                                            <br><strong><?php echo $print_srno; ?></strong>
                                        </td>
                                        <td align="left" style='vertical-align: top;'><?php
                                                                                        echo $is_main . $is_connected . $comlete_fil_no_prt . "<br>(" . $row['section_name'] . ") " . $row['name'] . "<br/>";
                                                                                        echo "<span class='tooltip'>" . $cat_code . "<span class='tooltiptext'>Tooltip text</span></span>";
                                                                                        if ($coram != 0 and $coram != '') {
                                                                                            echo "<br/>CORAM: <span style='color:green'>" . f_get_judge_names_inshort($coram) . "</span>";
                                                                                        }





                                                                                        ?></td>

                                        <td align="left" style='vertical-align: top;'><?php
                                                                                        $resus = $MonitoringModel->getPdfName($dno);

                                                                                        if (!empty($resus)) {
                                                                                            echo "<span class='tooltip'>ROP<span class='tooltiptext'>";
                                                                                            foreach ($resus as $ro_rop) {
                                                                                                $rjm = explode("/", $ro_rop['pdfname']);

                                                                                                if ($rjm[0] == 'supremecourt') {
                                                                                                    echo '<a href="../../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($ro_rop['orderdate'])) . '</a><br>';
                                                                                                } else {
                                                                                                    echo '<a href="../../judgment/' . $ro_rop['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($ro_rop['orderdate'])) . '</a><br>';
                                                                                                }
                                                                                            }
                                                                                            echo "</span></span>";
                                                                                        }

                                                                                        ?></td>

                                        <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                                        <td align="left" style='vertical-align: top;'>
                                            <?php

                                            $padvname = $padvname ?? '';
                                            $radvname = $radvname ?? '';

                                            echo str_replace(",", ", ", trim($padvname, ",")) . "<br/>Vs<br/>" . str_replace(",", ", ", trim($radvname, ","));
                                            ?>
                                        </td>
                                        <td align="left" style='vertical-align: top;'><?php echo $row['stagename']; ?></td>
                                        <td align="left" style='vertical-align: top;'><?php echo "<i>" . $lastorder . "</i><br>" . get_cl_brd_remark($dno); ?></td>
                                        <!--                <td align="left" style='vertical-align: top;'><?php  ?></td>-->
                                        <td align="left" style='vertical-align: top;'><?php $MonitoringModel->f_get_docdetail($dno);  ?>
                                        </td>

                                        <td align="left" style='vertical-align: top;'><?php echo $purpose;  ?>
                                        </td>
                                        <td align="left" style='font-weight:bold; color:green; vertical-align: top;'>
                                            <?php
                                            echo $ro_earlier_verify_record;
                                            echo $verify_time;
                                            //die;


                                            $res_verif = $MonitoringModel->getRemDtl($row['remark_id']);

                                            if (!empty($res_verif) && isset($res_verif['rem_dtl'])) {
                                                $row_verif = $res_verif;
                                                echo "<br>" . $row_verif['rem_dtl'];
                                            } else {

                                                echo "<br>No remarks found.";
                                            }
                                            ?>
                                        </td>

                                        </tr>
                                    <?php
                                    $sno++;
                                }
                                    ?>
                            </table>
                        <?php
                        } else {
                            echo "No Records Found";
                        }
                        ?>

                    </div>
                    <div id="newcs" style="display:none;">
                        <table width="100%" border="0" style="border-collapse: collapse">
                            <tr style="background-color: #A9A9A9;">
                                <td align="center">
                                    <b>
                                        <font color="black" style="font-size:14px;">Case Status</font>
                                    </b>
                                </td>
                                <td>
                                    <input style="float:right;" type="button" name="close_s" id="close_s" value="CLOSE WINDOW" onclick="close_cs();" />
                                </td>

                            </tr>
                        </table>
                        <div id="newcs123" style="overflow:auto; background-color: #FFF;">
                        </div>
                        <div id="newcs1" align="center">
                            <table border="0" width="100%">
                                <tr>
                                    <td align="center" width="250px">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="dv_res1"></div>
                    <div id="overlay" style="display:none;">&nbsp;</div>
                <?php } ?>
            </div>

        </form>
    </body>

    </html>
<?php } ?>
<?php

?>