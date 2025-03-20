<?php {

?>

    <head>

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
        <title>Cases Verified By Monitoring Team</title>
        <link rel="stylesheet" href="../../css/menu_css.css">

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

        <?php

        ?>

        <?php {

        ?>
            <div style="text-align: center">
                <H3>CASES VERIFIED DETAIL REPORT</H3>
                <?php



                $list_dt = $list_dt;

                $usertype = session()->get('login')['usertype'];
                $ucode = session()->get('login')['usercode'];
                $ro_u2 = $model->getUser($ucode);
                $sec_id = '';

                $username_uby = $ro_u2['name'];
                $checkDaCode = "";
               
               
                if ($ucode == 1) {
                    $checkDaCode = "";
                } else if ($usertype == '14' and $ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184) {

                    $ro_u = $model->getAllDa($ucode);
                    $all_da = $ro_u['allda'];
                    $checkDaCode = "AND (m.dacode=$ucode OR m.dacode IN($all_da))";
                    $mdacode = "";
                } else if (($usertype == '17' or $usertype == '50' or $usertype == '51') and ($ucode != 3564 and $ucode != 722 and $ucode != 1182 and $ucode != 184)) {

                    $checkDaCode = "AND m.dacode=$ucode";
                } else {
                    $mdacode = "";
                }


                $res = $model->getMainQuery($sec_id, $list_dt, $checkDaCode);

                if (!empty($res)) {
                    echo "Verified Matters of " . $username_uby . " Listed on " . date('d-m-Y', strtotime($list_dt));
                ?>

                    <table align="left" width="100%" border="0px;" style="table-layout: fixed;">

                        <tr style="background: #918788 !important;">
                            <td width="5%" style="font-weight: bold; color: #dce38d;">SNo</td>
                            <td width="13%" style="font-weight: bold; color: #dce38d;">Diary/Reg No</td>
                         
                            <td width="15%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</td>
                            <td width="15%" style="font-weight: bold; color: #dce38d;">Advocate</td>
                            <td width="10%" style="font-weight: bold; color: #dce38d;">Heading/Category</td>
                            <td width="16%" style="font-weight: bold; color: #dce38d;">LastOrder / Statutory</td>
                            <td width="12%" style="font-weight: bold; color: #dce38d;">IA</td>
                            <td width="6%" style="font-weight: bold; color: #dce38d;">Purpose</td>
                            <td width="14%" style="font-weight: bold; color: #dce38d;">Verification<br />Report</td>

                        </tr>
                        <?php
                        $sno = 1;
                        $psrno = 1;
                        foreach ($res as $row) {
                            $sno1 = $sno % 2;
                            $dno = $row['diary_no'];
                            $verify_time = "<br><span style='color:red'>Verify Time : " . date('h:i:s', strtotime($row['verified_on'])) . "</span>";
                            $coram = $row['coram'];
                            $purpose = $row['purpose'];
                            $lastorder = $row['lastorder'];
                            $stagename = $row['stagename'];
                            $diary_no_rec_date = "Diary Dt " . date('d-m-Y', strtotime($row['diary_no_rec_date']));

                            //$fil_dt = "Reg Dt ".date('d-m-Y', strtotime($row['fil_dt']));     
                            $fil_dt = "";    // Remove  vkg


                            $cate_old_id1 = "";
                            $board_type = "";
                            $mainhead = "";

                            $res_sm = $model->categoryScOld($dno);
                            

                            if (!empty($res_sm)) {
                                foreach ($res_sm as $cate_old_id) {
                                    $cate_old_id1 .= $cate_old_id['category_sc_old'] . ",";
                                }
                                $cat_code = rtrim($cate_old_id1, ",");
                            }

                            $verify_str = $dno . "_" . $board_type . "_" . $mainhead;

                            if ($sno1 == '1') { ?>
                                <tr style=" background: #ececec;" id="<?php echo $verify_str; ?>">
                                <?php } else { ?>
                                <tr style=" background: #f6e0f3;" id="<?php echo $verify_str; ?>">
                                <?php
                            }
                            if ($row['diary_no'] == $row['main_key'] or $row['main_key'] == 0 or $row['main_key'] == "") {
                                // $print_brdslno = $row['brd_slno'];
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
                            //            }
                            $filno_array = explode("-", $m_f_filno);
                            //pr($filno_array);
                           
                            if (isset($filno_array[1]) && isset($filno_array[2]) && $filno_array[1] == $filno_array[2]) {
                                $fil_no_print = ltrim($filno_array[1], '0');
                            } elseif (isset($filno_array[1]) && isset($filno_array[2])) {
                                $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                            } elseif (isset($filno_array[1])) {
                                $fil_no_print = ltrim($filno_array[1], '0');
                            } else {
                                $fil_no_print = ltrim($filno_array[0], '0'); // Default to first element
                            }

                            //if ($row['active_fil_no'] == "") {
                            $diary_str = substr_replace($row['diary_no'], '-', -4, 0);
                            $d_str = explode("-", $diary_str);
                            $comlete_fil_no_prt = "Diary No. <a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d_str[0]','$d_str[1]','','','');\" href='#'>" . $d_str[0] . '/' . $d_str[1] . "</a><br>" . $diary_no_rec_date;

                            if (!empty($fil_no_print)) {
                                $comlete_fil_no_prt .= "<br>" . $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr . "<br>" . $fil_dt;
                            } else {
                                $comlete_fil_no_prt .= "<br>Unreg.";
                            }
                            //}
                            $padvname = "";
                            $radvname = "";



                            $resultsadv = $model->getAdv($dno);
                           
                            if (!empty($resultsadv)) {
                                $rowadv = $resultsadv;
                                $radvname = $rowadv["r_n"];
                                $padvname = $rowadv["p_n"];
                                $impldname = $rowadv["i_n"];
                                // }
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
                            if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                                if ($row['active_reg_year'] != 0)
                                    $ten_reg_yr = $row['active_reg_year'];
                                else
                                    $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                                if ($row['active_casetype_id'] != 0)
                                    $casetype_displ = $row['active_casetype_id'];
                                else if ($row['casetype_id'] != 0)
                                    $casetype_displ = $row['casetype_id'];



                                $section_ten_rs = $model->getSectionName($casetype_displ, $ten_reg_yr, $ref_agency_state_id);
                                pr($section_ten_rs);
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

                                                                                ?></td>


                               

                                <td align="left" style='vertical-align: top;'><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                                <td align="left" style='vertical-align: top;'>
                                        <?php 
                                        echo str_replace(",", ", ", trim($padvname ?? "", ",")) 
                                            . "<br/>Vs<br/>" 
                                            . str_replace(",", ", ", trim($radvname ?? "", ","));
                                        ?>
                                </td>

                                <td align="left" style='vertical-align: top;'><?php echo $row['stagename']; ?></td>
                                <td align="left" style='vertical-align: top;'><?php echo "<i>" . $lastorder . "</i><br>" . get_cl_brd_remark($dno); ?></td>

                                <td align="left" style='vertical-align: top;'><?php f_get_docdetail($dno);  ?>
                                </td>

                                <td align="left" style='vertical-align: top;'><?php echo $purpose;  ?>
                                </td>
                                <td>
                                    <?= $row['remarks_by_monitoring'] ?>
                                    <br />
                                    <span style="font-weight:bold; color: brown;">Verified By: <?= $row["verified_by"] ?></span>
                                    <span style="font-weight:bold; color: brown;">Verified On: <?= $row["verified_on"] ?></span>
                                </td>

                                </tr>
                            <?php
                            $sno++;
                        }
                            ?>
                    </table>
                <?php
                } else {
                    echo "No Recrods Found";
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
        <?php } ?>


        </form>
    </body>

    </html>
<?php } ?>
<?php

?>