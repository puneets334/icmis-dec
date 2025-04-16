<?php
$listed_ia = '';
$cldate = '';
$output = '';
$main_case = '';
$t_slpcc = '';
$t_spl = $act_section = '';
$check_for_case_is_listed_after_current_date_remark = "";
$rmtable = '';

if (isset($ct) && $ct != '') {

    // Fetch diary number
    $diaryInfo = $model->getDiaryNumber($ct, $cn, $cy);

    if ($diaryInfo) {
        $d_no = $diaryInfo['dn'];
        $d_yr = $diaryInfo['dy'];

        // Fetch case type description
        $caseType = $model->getCaseTypeDescription($ct);
        $res_ct_typ = $caseType->short_description ?? '';

        $t_slpcc = $res_ct_typ . " " . $diaryInfo['crf1'] . " - " . $diaryInfo['crl1'] . " / " . $cy;
    } else {
        echo ' <p align=center>
                <font color=red>Case Not Found</font>
            </p>';
    }
}

if ($d_no != '' and $d_yr != '') {
    $filno = $model->getDiaryDetails($d_no, $d_yr);
    $main_fh_diary_no = "";
    if ($filno) {

        if ($ucode != $filno['dacode'] && $ucode != 1) {
            $section_rs = $model->getUserSection($ucode);
            $usersection = $section_rs[0];
            $usertype = $section_rs[1];
            //section 62- registrar court, section 81-registrar court, section 11- court
            if ($usersection != 62 and $usersection != 81 and $usersection != 11 and ($usersection != 30 and $usertype != 14) and  ($usersection != 19 and $usertype != 9 and $usertype != 6 and $usertype != 4)) { ?>
                <p align=center>
                    <font color=red>Only DA can Dispose Case</font>
                </p>
        <?php
                exit();
            }
        }

        // added on 22.10.2019 by vandana

        $isconn = $filno["ccdet"];
        $connto = $filno["connto"];
        $diaryno = $filno['diary_no'];

        if ($filno['diary_no'] != $filno['conn_key'] and $filno['conn_key'] != '')
            $check_for_conn = "N";
        else
            $check_for_conn = "Y";
        if ($filno['fil_no_fh'] != '')
            $main_fh_diary_no = "EXIST";
        ?>

        <div style="text-align: center">
            <strong>Diary No.- <?php echo $d_no; ?> - <?php echo $d_yr; ?></strong>
        </div>
        <?php
        navigate_diary($diaryno);
        $output .= '<table border="0"  align="left" width="100%">';
        if ($main_case != "")
            $main_case = "<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";

        $u_name = "";
        $row_da = $model->get_diary_details($diaryno);
        if ($row_da) {
            $u_name = " by <font color='blue'>" . $row_da["name"] . "</font>";
            $u_name .= "<font> [SECTION: </font><font color='red'>" . $row_da["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
        }
        $t_res_ct_typ = $model->getShortDescription($filno['casetype_id']);
        $res_ct_typ = $t_res_ct_typ['short_description'];

        $result = $model->getPartyDetails($filno['diary_no']);
        $ctr_p = 0;
        $ctr_r = 0;

        if (count($result) > 0) {
            $grp_pet_res = '';
            $pet_name = $res_name = "";
            foreach ($result as $row) {
                $temp_var = "";
                $temp_var .= $row['partyname'];
                if ($row['sonof'] != '') {
                    $temp_var .= $row['sonof'] . "/o " . $row['prfhname'];
                }
                if ($row['deptname'] != "") {
                    $temp_var .= "<br>Department : " . $row['deptname'];
                }
                $temp_var .= "<br>";
                if ($row['addr1'] == '')
                    $temp_var .= $row['addr2'];
                else
                    $temp_var .= $row['addr1'] . ', ' . $row['addr2'];

                $t_dist = $model->getDistrictName($row['state'], $row['city']);
                $t_var = @$t_dist['name'];
                // $t_var=mysql_result($district, 0);
                if ($t_var != "")
                    $temp_var .= ", District : " . $t_var;

                if ($row['pet_res'] == 'P') {
                    $pet_name = $temp_var;
                } else {
                    $res_name = $temp_var;
                }
                $case_no = $row['case_no'];
                $year = $row['year'];
                $diary_no_rec_date = $row['diary_no_rec_date'];
            } ?>
            <div class="cl_center"><strong>Case Details</strong></div>
            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                <tr>
                    <td width='140px'>Diary No.</td>
                    <td>
                        <div width='100%'>
                            <font color='blue' style='font-size:12px;font-weight:bold;'><?php print $case_no; ?>/<?php print $year; ?></font> Received on <?php print $diary_no_rec_date . $u_name . $main_case; ?>
                        </div>
                    </td>
                </tr>

                <?php
                $t_fil_no = get_case_nos($diaryno, '&nbsp;&nbsp;');

                if (trim($t_fil_no) == '') {
                    $caseType = $model->getShortDescription($caseTypeId);

                    if ($caseType) {
                        $t_fil_no = $caseType['short_description'];
                    }
                }

                if ($t_slpcc != '') {
                    $t_slpcc = "<br>" . $t_slpcc;
                }

                // Get lower court details
                $lowerCourtDetails = $model->getLowerCourtDetails($diaryno);
                $t_fil_no1 = '';

                if (!empty($lowerCourtDetails)) {
                    foreach ($lowerCourtDetails as $ro_lct) {
                        if ($t_fil_no1 == '') {
                            $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                        } else {
                            $t_fil_no1 .= ", " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                        }
                    }
                }
                echo "<tr>
 <td>Case No.</td>
 <td><div width='100%'>" . $t_fil_no . $t_slpcc . $t_fil_no1 . "</div></td></tr>";

                if ($t_spl != "") {
                    echo "<tr >
 <td>Special Type</td>
 <td>" . $t_spl . "</td></tr>";
                }
                ?>
                <tr>
                    <td style="width: 15%">
                        Petitioner
                    </td>
                    <td>
                        <?php echo $pet_name; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%">
                        Respondant
                    </td>
                    <td>
                        <?php echo $res_name; ?>
                    </td>
                </tr>

                <tr>
                    <td style="width: 15%">
                        Case Category
                    </td>
                    <td>
                        <?php
                        $case_category = "";
                        $mul_category = get_mul_category($filno['diary_no']);
                        if (is_array($mul_category) && isset($mul_category[0])) {
                            echo $mul_category[0];
                        } else {
                            echo '';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Act
                    </td>
                    <td>
                        <?php
                        $acts = $model->getActDetails($filno['diary_no']);
                        $act_section = '';

                        if (!empty($acts)) {
                            foreach ($acts as $row1) {
                                if ($act_section == '') {
                                    $act_section = $row1['act_name'] . '-' . $row1['section'];
                                } else {
                                    $act_section .= ', ' . $row1['act_name'] . '-' . $row1['section'];
                                }
                            }
                        }
                        echo $act_section;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Provision of Law
                    </td>
                    <td>
                        <?php
                        $caseLaw = $model->getCaseLaw($filno['actcode']);
                        if ($caseLaw) {
                            $t_pol = $caseLaw['law'];
                        } else {
                            $t_pol = 'No law found';
                        }
                        echo $t_pol;
                        ?>
                    </td>
                </tr>
                <?php
                $padvname = '';
                $radvname = '';
                $advocates = $model->getAdvocatesByDiaryNo($filno['diary_no']);
                foreach ($advocates as $row_advp) {
                    $tmp_advname = "<p>&nbsp;&nbsp;" . $model->get_advocates($row_advp['advocate_id'], '') . $row_advp['adv'] . "</p>";

                    if ($row_advp['pet_res'] == "P") {
                        $padvname .= $tmp_advname;
                    }
                    if ($row_advp['pet_res'] == "R") {
                        $radvname .= $tmp_advname;
                    }
                }

                if ($filno['c_status'] == 'D') {
                    $row_rj = $model->getJudgmentDetails($filno['diary_no']);
                     $disp_str = '';
                    if (!empty($row_rj)) {

                        $judges = stripslashes($row_rj["judges"] ?? '');
                        $disp_str = " (Order Date: " . $row_rj["odt"] . " and Updated on " . $row_rj["ddt"] . ")<br> JUDGES: " . $judges;

                        $judgeNames = $model->getJudgeNames($filno['diary_no']);

                        $disptype = $row_rj['disp_type'];
                        $dispdet = '';
                        if ($disptype) {
                            $disposal = $model->getDisposalType($disptype);
                            if ($disposal) {
                                $dispdet = $disposal['dispname'];
                                if (in_array($ucode, [203, 204, 888, 912])) {
                                    $d_spk = ($disposal['spk'] == "N") ? " (Non Speaking)" : " (Speaking)";
                                    $dispdet .= $d_spk;
                                }
                                if ($disptype == 19) {
                                    $dispdet .= " by LOK ADALAT ";
                                }
                            }
                        }

                        $disp_dt = $row_rj["disp_dt"];
                        if (!empty($row_rj["rj_dt"])) {
                            $rjdate = "&nbsp;&nbsp;&nbsp;RJ Date: " . date('d-m-Y', strtotime($row_rj["rj_dt"]));
                        }
                    }
                }

                ?>
                <tr>
                    <td style="width: 15%">
                        Petitioner Advocate
                    </td>
                    <td>
                        <?php echo $padvname; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Respondant Advocate
                    </td>
                    <td>
                        <?php echo $radvname; ?>
                    </td>

                </tr>
                <?php if ($filno['c_status'] == 'D') { ?>
                    <tr>
                        <td>
                            Status
                        </td>
                        <td>
                            <label style="font-size: 12px; color: red;"> <?php echo "DISPOSED " . $disp_str . ")"; ?> </label>
                        </td>

                    </tr><?php } ?>
                <tr>
                    <td>
                        Last Order
                    </td>
                    <td>
                        <?php echo $filno['lastorder']; ?>
                    </td>
                </tr>
                <?php
                if ($filno['c_status'] == 'P') {

                    $rgoResults = $model->getRgo($filno['diary_no']);
                    $t_rgo = '';

                    if (!empty($rgoResults)) {
                        foreach ($rgoResults as $res_rgo) {
                            $formattedDiaryNo = "D.No. " . get_real_diaryno($res_rgo['fil_no2']);
                            $formattedCaseNos = str_replace('<br>', ' ', get_casenos_comma($res_rgo['fil_no2']));
                            $t_rgo .= ($t_rgo == '') ? "$formattedDiaryNo<br>$formattedCaseNos" : "<br>$formattedDiaryNo<br>$formattedCaseNos";
                        }
                    }

                    if ($t_rgo != '') {
                        echo "<tr>
                                <td>Conditional Dispose</td>
                                <td style='font-size:12px;font-weight:100;'><b><font style='font-size:12px;font-weight:100;'><b>$t_rgo</b></font></b></td>
                              </tr>";
                    }

                    $r_ttv = $model->getTentativeListingDate($filno['diary_no']);

                    $result_array = $model->getCaseStatusFlag();
                    //var_dump($result_array);
                    if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) {
                ?>

                        <tr>
                            <td>
                                Tentative Date
                            </td>
                            <td>
                                <?php

                                if (!empty($r_ttv['tentative_cl_dt']) && get_display_status_with_date_differnces($r_ttv['tentative_cl_dt']) == 'T') {
                                    $tentative_date = $r_ttv['tentative_cl_dt'];
                                    echo change_date_format($tentative_date);
                                }
                                ?>
                            </td>
                        </tr>

                    <?php
                    }

                    if ($isconn == 'Y') {
                        $connectedCases = $model->getConnectedCases($diaryno);

                        $connto = "<font color='red'>" . $connto . " </font>(Main Case)";

                        if (!empty($connectedCases)) {
                            foreach ($connectedCases as $row_oc) {
                                $connto .= "<br><font color='blue'>" . $row_oc["diary_no"] . " </font>(Connected Case)";
                            }
                        }

                        echo "<tr valign='top'><td bgcolor='#F4F5F5'>Connected To </td><td><b>" . $connto . "</b></td></tr>";
                    }
                } else {
                    ?>

                    <tr>
                        <td>
                            Case Status
                        </td>
                        <td>
                            <?php echo '<font color=red>Case is Disposed</font>'; ?>
                        </td>
                    </tr>

                <?php

                }
                ?>
            </table>
        <?php
        } else {
        ?>
            <div class="cl_center"><b>No Record Found</b></div>
        <?php
        }
        $jud1 = 0;
        $jud2 = 0;
        $jud3 = 0;
        $jud4 = 0;
        $jud5 = 0;
        $clno_1 = 0;
        $isconn = "";
        $connto = "";
        $ian = "";
        $ian_p = "";
        $oth_doc = "";
        $listorder = "";
        $jcodes = "";
        $benchmain = "";
        $t_conn_cases = '';

        $row_m = $model->getDiaryByNo($diaryno);
        if ($row_m) {
            $isconn = $row_m["ccdet"];
            $connto = $row_m["connto"];
            //IAN
            $results_ian = $model->getDocDetailsByDiaryNo($diaryno);
           // pr($results_ian);
            $iancntr = 1;
            foreach ($results_ian as $row_ian) {
                if ($ian_p == "" and $row_ian["iastat"] == "P") {
                    $ian_p = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                    $ian_p .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='4'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
                    $ian_p .= "<tr bgcolor='#F4F5F5'><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                }
                if ($iancntr == 1) {
                    $ian = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                    $ian .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='6'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
                    $ian .= "<tr bgcolor='#F4F5F5'><td align='center' width='50px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
                }
                if ($row_ian["other1"] != "")
                    $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                else
                    $t_part = $row_ian["docdesc"];
                $t_ia = "";
                if ($row_ian["iastat"] == "P")
                    $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                if ($row_ian["iastat"] == "D")
                    $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                    $row_ian_ent_dt =   $row_ian["ent_dt"] ? date("d-m-Y", strtotime($row_ian["ent_dt"])) : '';
                $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . $t_part . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . $row_ian_ent_dt . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";

                if ($row_ian["iastat"] == "P") {
                    $row_ian_ent_dt =   $row_ian["ent_dt"] ? date("d-m-Y", strtotime($row_ian["ent_dt"])) : '';
                    $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . $t_part . "' onClick='feed_rmrk();'></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . $t_part . "</td><td align='center'>" . $row_ian_ent_dt . "</td></tr>";
                }
                $iancntr++;
            }
            if ($ian != "")
                $ian .= "</table>";
            if ($ian_p != "")
                $ian_p .= "</table>";
            //IAN

            //OTHER DOCUMENTS
            $results_od = $model->getOtherDocDetailsByDiaryNo($diaryno);
            $odcntr = 1;
            foreach ($results_od as $row_od) {
                if ($odcntr == 1) {
                    $oth_doc = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                    $oth_doc .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='6'><font color='red'><b>DOCUMENTS FILED</b></font></td></tr>";
                    $oth_doc .= "<tr bgcolor='#F4F5F5'><td align='center' width='50px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                }
                if (trim($row_od["docdesc"]) == 'OTHER')
                    $docdesc = $row_od["other1"];
                else
                    $docdesc = $row_od["docdesc"];


                if ($row_od["doccode"] == 7 and  $row_od["doccode1"] == 0)
                    $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                else
                    $doc_oth = '';


                $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td></tr>";

                $odcntr++;
            }
            if ($oth_doc != "")
                $oth_doc .= "</table>";

            $p = $row_m["pet_name"];
            $r = $row_m["res_name"];
            $status = $row_m['c_status'];
            $lastorder = $row_m['lastorder'];
            $benchmain = $row_m['bench'];
            $row11 = $model->getSKeyByDiaryNo($diaryno);
            if (isset($row11[0]) && !is_null($row11[0])) {
                $case_t = $caseno = $row11[0] . " - " . intval(substr($diaryno, 5, 5)) . "/" . intval(substr($diaryno, 10, 4));
            } else {
                // Handle the case where $row11[0] is not set
                $case_t = $caseno = "Invalid Case Number";
            }

            // $case_t = $caseno = $row11[0] . " - " . intval(substr($diaryno, 5, 5)) . "/" . intval(substr($diaryno, 10, 4));

            $cstatus = "";
            switch ($status) {
                case 'P':
                    $cstatus = "<font color='blue'>Pending</font>";
                    break;

                case 'R':
                    $cstatus = "<font color='red'>Rejected</font>";
                    break;

                case 'D':
                    $cstatus = "<font color='red'>Disposed</font>";
                    break;

                case 'T':
                    $cstatus = "<font color='red'>Transferred</font>";
                    break;
            }

            $head = "";
            $head_r = "";

            $partyResults = $model->getPartyNames($diaryno);
            $p = '';
            $r = '';

            foreach ($partyResults as $row) {
                if ($row['pet_res'] == 'P') {
                    $p .= $p ? ', ' . $row['pn'] : $row['pn'];
                }
                if ($row['pet_res'] == 'R') {
                    $r .= $r ? ', ' . $row['pn'] : $row['pn'];
                }
            }

            // Get not before details
            $notBeforeResults = $model->getNotBefore($diaryno);
            $bf = '';
            $nbf = '';

            foreach ($notBeforeResults as $row) {
                $t_jn1 = stripslashes($row['jn']);
                if ($row['notbef'] == 'B') {
                    $bf .= $bf ? ', ' . $t_jn1 : $t_jn1;
                }
                if ($row['notbef'] == 'N') {
                    $nbf .= $nbf ? ', ' . $t_jn1 : $t_jn1;
                }
            }

            // Handle dispose date if status is D
            $rjdate = '';

            if ($status == 'D') {
                $resultRJ = is_data_from_table('dispose', "diary_no = $diaryno", 'rj_dt', '');

                if (!empty($resultRJ['rj_dt'])) {
                    $rjdate = date('d-m-Y', strtotime($resultRJ['rj_dt']));
                }
            }
        ?>
            <br>
            <div align="center"><br>

                <br>
                <?php
                $subhead = "";
                $next_dt = "";
                $lo = "";
                $sj = "";
                $bt = "";

                $hearings = $model->getHearings($diaryno);
                $lastHearings = $model->getLastHearings($diaryno);

                $check_for_case_is_listed_after_current_date = "";
                $check_for_case_is_listed_after_current_date_remark = "";

                // Check conditions based on hearings
                foreach ($hearings as $row) {
                    if ($row['judges'] != '' && $row['judges'] != '0' && $row['clno'] > 0 && $row['brd_slno'] > 0 && $row['roster_id'] > 0) {
                        if ((strtotime($row['next_dt']) > strtotime(date('Y-m-d'))) ||
                            (strtotime($row['next_dt']) == strtotime(date('Y-m-d')) &&
                                (strtotime("17:30:10") - strtotime(date('H:i:s'))) > 0)
                        ) {
                            $check_for_case_is_listed_after_current_date = "LISTED";
                            if (strtotime($row['next_dt']) == strtotime(date('Y-m-d'))) {
                                $check_for_case_is_listed_after_current_date_remark = "Disposal is LOCKED as Case is already Listed on " . date('d-m-Y', strtotime($row['next_dt'])) . "<br>Case is available for updation after 5:30pm";
                            } else {
                                $check_for_case_is_listed_after_current_date_remark = "Disposal is LOCKED as Case is already Listed on " . date('d-m-Y', strtotime($row['next_dt']));
                            }
                        }
                    }
                }

                if ($status != "D") {
                ?>
                    <table bgcolor="#F5F5FC" class="tbl_hr" width="98%" border="1" cellspacing="0" cellpadding="3">
                        <tr bgcolor='#EAEAF9'>
                            <td align='center'>
                                <center>
                                    <font color='red'><b>SET REMARK FOR PENDING / DISPOSE</b></font>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <center>
                                    <?php
                                    $row_courtMaster = $model->getUserDetails($ucode);
                                    $is_courtMaster = @$row_courtMaster['is_CourtMaster'];
                                    $usection = @$row_courtMaster['section'];
                                    $usertype = @$row_courtMaster['usertype'];
                                    $row_lp123 = $model->get_diaryDetails($diaryno);
                                    if ($row_lp123) {
                                        if ($row_lp123["username"] == "" and $row_lp123["dacode"] == "")
                                            $output1 = "0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $row_lp123["empid"];
                                        else if ($row_lp123["username"] == "" and ($row_lp123["dacode"] != $ucode))
                                            $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA USER ID : " . $row_lp123["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $row_lp123["dacode"];
                                        else if ($row_lp123["dacode"] != $ucode)
                                            $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $row_lp123["username"] . " [USER ID : " . $row_lp123["empid"] . "]|#|" . $row_lp123["dacode"];
                                        else
                                            $output1 = "1|#|RIGHT DA|#|" . $row_lp123["dacode"];
                                    }
                                    $result_da = explode("|#|", $output1);
                                    if ($result_da[0] > 0 or $usection == '11' or $usection == '62' or $usection == '81' or $is_courtMaster == 'Y' or ($usection = 30 and $usertype = 14) or (($usersection == 19 and ($usertype == 9 or $usertype == 6 or $usertype == 4)))) {
                                        //if(!(( $tbl == "H" and $listed == "Y") and (($mfvar == "F" and strtotime($last_date) > strtotime('last sunday')) or ($mfvar != "F" and strtotime($last_date) >= strtotime(date('Y-m-d'))))))   {
                                        if ($check_for_case_is_listed_after_current_date_remark == "") {
                                            $rmtable .= "<input type='button' name='db" . $diaryno . "' onclick='call_div(\"" . $diaryno . "\",this,2,\"\")' value='Set Dispose '>";
                                        } else {
                                            echo "<center><font color='red'><b>" . $check_for_case_is_listed_after_current_date_remark . "</b></font></center>";
                                        }
                                    } else {
                                        $rmtable .= "<center><font color='red'>" . $result_da[1] . "</font></center>";
                                    }
                                    echo $rmtable;
                                    ?>
                                </center>
                            </td>
                        </tr>
                    </table>
                <?php
                }
                echo "<br>";
                //connected cases

                $conncases = get_conn_cases($diaryno);
                if (count($conncases) > 0) {
                ?>
                    <div class="cl_center"><strong>CONNECTED / LINKED CASES</strong></div>
                    <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                        <tr>
                            <td align='center' width='30px'><b>S.N.</b></td>
                            <td><b>Case No.</b></td>
                            <td><b>M/C/L</b></td>
                            <td><b>Petitioner Vs. Respondant</b></td>
                            <td><b>Case Category</b></td>
                            <td align='center'><b>Status</b></td>
                            <td align='center'><b>Before/Not Before</b></td>
                            <td align='center'><b>List</b></td>
                            <td><b>DA</b></td>
                        </tr>
                        <?php
                        $connchks =  "<table class='table_tr_th_w_clr c_vertical_align'  width='100%'><tr><td align='center' colspan='5'><font color='red'><b>CONNECTED CASES</b></font></td></tr>";
                        $connchks .= "<tr><td align='center' width='30px'><b></b></td><td><b>Case No.</b></td><td><b>Petitioner Vs. Respondant</b></td><td align='center'><b>Status</b></td><td><b>IA</b></td></tr>";

                        $sn = 0;
                        $t_conn_cases = '';

                        foreach ($conncases as $row => $link) {
                            if ($link['c_type'] != "") {

                                $sn++;
                                $main_details = $model->get_main_details($link['diary_no'], 'diary_no,pet_name,res_name,c_status,fil_no_fh');
                                if (is_array($main_details)) {
                                    foreach ($main_details as $rowm => $linkm) {

                                        $t_pname = $linkm['pet_name'];
                                        $t_rname = $linkm['res_name'];
                                        $t_status = $linkm['c_status'];
                                        $t_fil_no_fh = $linkm['fil_no_fh'];
                                        if (isset($link["list"]) && $link["list"] == "Y")
                                            $chked = "checked";
                                        else
                                            $chked = "";
                                        if ($linkm['c_status'] == "D")
                                            $chked = " disabled=disabled";
                                    }
                                }
                                $t_brdrem = $model->get_brd_remarks($link['diary_no']);
                                $t_conn_type = "";
                                if ($link['c_type'] == "M") {
                                    $t_conn_type = "Main";
                                }
                                if ($link['c_type'] == "C") {
                                    $t_conn_type = "Connected";
                                }
                                if ($link['c_type'] == "L") {
                                    $t_conn_type = "Linked";
                                }
                                if ($link['c_type'] != "M" and $t_status == "P" and $link['diary_no'] != '') {
                                    $t_conn_cases .= '<tr><td><input type="checkbox" name="conncchk' . $link['diary_no'] . '" id="conncchk' . $link['diary_no'] . '" value="' . $link['diary_no'] . '"/><label class="lblclass" for="conncchk' . $link['diary_no'] . '">' . get_real_diaryno($link['diary_no']) . '</label></td></tr>';
                                }
                                $list = isset($link["list"]) ? $link["list"] : '';
                                $mul_cat = get_mul_category($link['diary_no']);
                                echo "<tr><td align='center' width='30px'>" . $sn . "</td><td>" . get_real_diaryno($link['diary_no']) . "</td><td>" . $t_conn_type . "</td><td>" . $t_pname . " Vs. " . $t_rname . "</td><td>" . $mul_cat[0] . "</td><td align='center'>" . $t_status . "</td><td align='center'></td><td align='center'>" . $list . "</td><td></td></tr>";
                                if ($link['c_type'] != "M") {
                                    if ($t_fil_no_fh == '') {
                                        $t_check = '<div class="fh_error" style="display:none;"><font color="red">Case is not registered in Regular Hearing</font></div>';
                                    } else
                                        $t_check = '';

                                    $connchks .= "<tr><td align='center'><input type='checkbox' name='ccchk" . $link['diary_no'] . "' id='ccchk" . $link['diary_no'] . "' value='" . $link['diary_no'] . "' " . $chked . " ></td><td>" . get_real_diaryno($link['diary_no']) . "</td><td>" . $t_pname . " Vs. " . $t_rname . $t_check . "</td><td align='center'>" . $t_status . "</td><td><input type='hidden' name='brdremh_" . $link['diary_no'] . "' id='brdremh_" . $link['diary_no'] . "' value=" . $t_brdrem . "><textarea style='width:95%' name='brdrem_" . $link['diary_no'] . "' id='brdrem_" . $link['diary_no'] . "' rows='3'>" . $t_brdrem . "</textarea>" . get_ia($link['diary_no']) . "</td></tr>";
                                }
                            }
                        }
                        $connchks .= "</table>";
                        ?>
                    </table>
                <?php
                }
                //connected cases
                //IAN
                $results_ian = $model->getDocuments($diaryno);
                $iancntr = 1;
                if (count($results_ian) > 0) {
                ?>
                    <div class="cl_center"><strong>INTERLOCUTARY APPLICATIONS</strong></div>
                    <?php
                    foreach ($results_ian as $row_ian) {
                        if ($ian_p == "" and $row_ian["iastat"] == "P") {
                            $ian_p =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                            $ian_p .= "<tr><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                        }
                        if ($iancntr == 1) {
                            $ian = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                            $ian .= "<tr><td align='center' width='30px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
                        }
                        if ($row_ian["other1"] != "")
                            $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                        else
                            $t_part = $row_ian["docdesc"];
                        $t_ia = "";
                        if ($row_ian["iastat"] == "P")
                            $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                        if ($row_ian["iastat"] == "D")
                            $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                            $row_ian_ent_dt = $row_ian["ent_dt"] ? date("d-m-Y", strtotime($row_ian["ent_dt"])) : '';

                        $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" .$row_ian_ent_dt . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";
                        if ($row_ian["iastat"] == "P") {
                            $row_ian_ent_dt = $row_ian["ent_dt"] ? date("d-m-Y", strtotime($row_ian["ent_dt"])) : '';
                            $t_iaval = $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                            if (strpos($listed_ia, $t_iaval) !== false)
                                $check = "checked='checked'";
                            else
                                $check = "";
                            $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();'  " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" .$row_ian_ent_dt . "</td></tr>";
                        }
                        $iancntr++;
                    }
                }
                if ($ian != "")
                    $ian .= "</table><br>";
                if ($ian_p != "")
                    $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";
                echo $ian;
                //IA END
                //OTHER DOCUMENTS
                $results_od =  $model->getDocuments($diaryno);
                $odcntr = 1;
                if (count($results_od) > 0) {
                    ?>
                    <div class="cl_center"><strong>DOCUMENTS FILED</strong></div>
                <?php
                    foreach ($results_od as $row_od) {
                        if ($odcntr == 1) {
                            $oth_doc =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                            $oth_doc .= "<tr><td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                        }
                        if (trim($row_od["docdesc"]) == 'OTHER')
                            $docdesc = $row_od["other1"];
                        else
                            $docdesc = $row_od["docdesc"];
                        if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0)
                            $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                        else
                        $row_od_ent_dt = $row_od["ent_dt"] ? date("d-m-Y", strtotime($row_od["ent_dt"])) : '';

                            $doc_oth = '';
                        $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" .$row_od_ent_dt . "</td><td align='center'>" . $doc_oth . "</td></tr>";
                        $odcntr++;
                    }
                    if ($oth_doc != "")
                        $oth_doc .= "</table><br>";
                }
                echo $oth_doc;
                //OTHER DOCUMENTS
                echo "<br>";

                ?>
            </div>


            <div id="newc">
                <div id="newc1" align="center">
                    <table border="0" width="100%">
                        <tr>
                            <td align="center">
                                <center>
                                    <input type='button' name='insert3' id='insert3' value="Save" onClick="return save_rec(2);">
                                    <input type="button" name="close3" id="close3" value="Cancel" onClick="return close_w(2)">
                                    <input type="hidden" name="tmp_casenod" id="tmp_casenod" value="" />
                                    <input type="hidden" name="tmp_casenosub" id="tmp_casenosub" value="" />
                                </center>
                            </td>
                            <td align="center">
                                <center><b>
                                        <font color="#000">Disposal Remark in </font>
                                    </b><b><span id="disp_head"></span></b></center>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="newc123" style="overflow:auto;">
                    <table width="100%" border="1" style="border-collapse: collapse">
                        <?php
                        if ($cldate == "")
                            $cldate = date('d-m-Y');
                        ?>
                        <tr>
                            <td align="center"><b>
                                    <font size="+1">Cause List/Order Date : </font>
                                </b>&nbsp;<input class="dtp" type="text" name="cldate" id="cldate" value="<?php echo $cldate; ?>" size="12" readonly="readonly"><input type="button" id="btn_coram" onclick="get_coram('<?php echo $diaryno; ?>','<?php echo $cldate; ?>');" name="btn_coram" value="Get Coram"></td>
                            <input type="hidden" id="hdn_cldate" value="" />
                            <td id="td_coram" align="center" rowspan="4"><b>
                                    <font size="+1">Coram : </font>
                                </b>&nbsp;
                                <select size="1" name="djudge" id="djudge" class="searchable-dropdown">


                                    <?php
                                    $sql2 = "SELECT jcode AS jcode, case when (jname like '%CHIEF JUSTICE%' OR jname like '%Registrar%') THEN concat(trim(jname),' (', first_name,' ',sur_name,' )') ELSE trim(jname) END AS jname FROM judge WHERE display = 'Y'  AND jtype IN('J','R')  ORDER BY if(is_retired='N',0,1),jtype,judge_seniority";
                                    echo '<option value =""> </option>';

                                    $results2 = $model->getJudges();
                                    $tjud1 = $tjud2 = $tjud3 = $tjud4 = $tjud5 = "";
                                    $cljudge1 = '';
                                    $cljudge2 = '';
                                    $cljudge3 = '';
                                    $cljudge4 = '';
                                    $cljudge5 = '';
                                    if (count($results2) > 0) {
                                        $djcnt = 0;
                                        foreach ($results2 as $row2) {
                                            if ($cljudge1 == $row2["jcode"])
                                                echo '<option value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '" selected>' . str_replace("\\", "", $row2["jname"]) . '</option>';
                                            else
                                                echo '<option value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '">' . str_replace("\\", "", $row2["jname"]) . '</option>';
                                            if ($cljudge1 == $row2["jcode"]) {
                                                $djcnt++;
                                                $tjud1 = '<input type="checkbox"  id="hd_chk_jd1" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . '</b></font>';
                                            }
                                            if ($cljudge2 == $row2["jcode"]) {
                                                $djcnt++;
                                                $tjud2 = '<input type="checkbox"  id="hd_chk_jd2" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . '</b></font>';
                                            }
                                            if ($cljudge3 == $row2["jcode"]) {
                                                $djcnt++;
                                                $tjud3 = '<input type="checkbox"  id="hd_chk_jd3" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . '</b></font>';
                                            }
                                            if ($cljudge4 == $row2["jcode"]) {
                                                $djcnt++;
                                                $tjud4 = '<input type="checkbox"  id="hd_chk_jd4" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . '</b></font>';
                                            }
                                            if ($cljudge5 == $row2["jcode"]) {
                                                $djcnt++;
                                                $tjud5 = '<input type="checkbox"  id="hd_chk_jd5" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . '||' . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . '</b></font>';
                                            }
                                        }
                                    }

                                    ?>

                                    <style>
                                        #select2-container--focus {
                                            width: 220px !important;
                                        }
                                    </style>

                                    <script>
                                        $("#djudge").select2({
                                            placeholder: "Select Judges/ Registrar",
                                            allowClear: true
                                        });
                                    </script>




                                </select><br><br>
                                <input type="hidden" name="djcnt" id="djcnt" value="<?php echo $djcnt; ?>" />
                                <input type="button" name="addjudge" id="addjudge" value="Add" onclick="getSlide();" />
                            </td>
                            <td rowspan="4" id="judgelist">
                                <table id="tb_new" width="100%" style="text-align:left;">
                                    <?php
                                    if ($tjud1 != "") echo "<tr id='hd_chk_jd_row1'><td>" . $tjud1 . "</td></tr>";
                                    if ($tjud2 != "") echo "<tr id='hd_chk_jd_row2'><td>" . $tjud2 . "</td></tr>";
                                    if ($tjud3 != "") echo "<tr id='hd_chk_jd_row3'><td>" . $tjud3 . "</td></tr>";
                                    if ($tjud4 != "") echo "<tr id='hd_chk_jd_row4'><td>" . $tjud4 . "</td></tr>";
                                    if ($tjud5 != "") echo "<tr id='hd_chk_jd_row5'><td>" . $tjud5 . "</td></tr>";
                                    ?>
                                </table>
                            </td>
                            <td rowspan="4" id="auto_chck">
                                <table id="jud_coram" width="100%" style="text-align:left;">

                                </table>

                            </td>
                        </tr>

                        <tr>
                            <td align="center"><b>
                                    <font size="+1">Disposal/Hearing Date : </font>
                                </b>&nbsp;<input class="dtp" type="text" name="hdate" id="hdate" value="<?php echo $cldate; ?>" maxlength="10" size="10" readonly="readonly"></td>
                        </tr>
                        <tr>
                            <td align="center"><b>
                                    <font size="+1"><span id="rjdate_fnt">R.J. Date : </span></font>
                                </b>&nbsp;<input class="dtp" type="text" name="rjdate" id="rjdate" value="" size="12" readonly="readonly" style="background-color:#CCC;"></td>
                        </tr>
                        <tr>
                            <td align="center">

                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="1" style="border-collapse: collapse">

                        <?php
                        $t11 =  $model->getCaseRemarks();
                        $ttl_disp = count($t11);
                        if ($ttl_disp > 0) {
                            $snoo = 1;
                            $chkhead = "";
                            $sno_1 = "";
                            $sno_2 = "";
                            $head_1 = "";
                            $head_2 = "";
                            $t_subhead = '';
                            foreach ($t11 as $row11) {
                        ?>
                                <?php
                                if (($snoo % 2) == 0 or $snoo == $ttl_disp) {
                                    $sno_2 = $row11['sno'];
                                    $head_2 = $row11['head'];
                                    $bgc = "#ECF1F7";
                                    if (($t_subhead == 801 or $t_subhead == 820) and $listorder != 5 and ($sno_1 != 78 and $sno_1 != 73 and $sno_1 != 37))
                                        $t_subhead1 = "disabled='disabled'";
                                    else
                                        $t_subhead1 = "";

                                ?>
                                    <tr bgcolor="<?php echo $bgc; ?>">
                                        <td align="left">
                                            <input class="cls_chkd" type="checkbox" name="chkd<?php echo $sno_1; ?>" id="chkd<?php echo $sno_1; ?>" value="<?php echo $sno_1 . "|" . $head_1; ?>" onclick="chk_checkbox();" <?php echo $t_subhead1; ?> />
                                            <label class="lblclass" for="chkd<?php echo $sno_1; ?>"><?php echo $head_1; ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="hdremd<?php echo $sno_1; ?>" id="hdremd<?php echo $sno_1; ?>" value="" />
                                            <input type="hidden" name="hdd<?php echo $sno_1; ?>" id="hdd<?php echo $sno_1; ?>" />
                                        </td>
                                        <?php
                                        if ($snoo == $ttl_disp and ($snoo % 2) == 1) {
                                        ?>
                                            <td align="left">&nbsp;</td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        <?php
                                        } else {
                                            if (($t_subhead == 801 or $t_subhead == 820) and $listorder != 5 and ($sno_2 != 78 and $sno_2 != 73 and $sno_2 != 37))
                                                $t_subhead1 = "disabled='disabled'";
                                            else
                                                $t_subhead1 = "";
                                        ?>
                                            <td align="left">
                                                <input class="cls_chkd" type="checkbox" name="chkd<?php echo $sno_2; ?>" id="chkd<?php echo $sno_2; ?>" value="<?php echo $sno_2 . "|" . $head_2; ?>" onclick="chk_checkbox();" <?php echo $t_subhead1; ?> />
                                                <label class="lblclass" for="chkd<?php echo $sno_2; ?>"><?php echo $head_2; ?></label>
                                            </td>
                                            <td>
                                                <input type="text" name="hdremd<?php echo $sno_2; ?>" id="hdremd<?php echo $sno_2; ?>" value="" />
                                                <input type="hidden" name="hdd<?php echo $sno_2; ?>" id="hdd<?php echo $sno_2; ?>" />
                                            </td>
                                        <?php
                                        }
                                        if ($snoo <= 2) {
                                        ?>
                                            <td rowspan="<?php echo (($ttl_disp + 1) / 2); ?>">
                                                <div id="concasediv" style="overflow: auto;display:fixed;max-height:550px;">
                                                    <table>
                                                        <?php
                                                        if ($t_conn_cases != '') {
                                                            $t_conn_cases = '<tr><td bgcolor=#5499c7><input type="checkbox" name="connall" id="connall" value="" onclick="chk_all_cn();"/><label class="lblclass" for="connall">CHECK ALL</label></td></tr>' . $t_conn_cases;
                                                            echo $t_conn_cases;
                                                        } ?>
                                                    </table>
                                                </div>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                <?php
                                } else {
                                    $sno_1 = $row11['sno'];
                                    $head_1 = $row11['head'];
                                    $sno_2 = "";
                                    $head_2 = "";
                                    $bgc = "#F8F9FC";
                                }
                                ?>

                        <?php
                                $snoo++;
                            } // while end
                        }
                        ?>
                    </table>
                </div>

            </div>

            <input type="hidden" name="sh_hidden" id="sh_hidden" value="" />
            <input type="hidden" name="diaryno" id="diaryno" value="<?php echo get_real_diaryno($diaryno); ?>" />
<?php
        } else {
            echo '<br><br><b>No case found for no. provided.</b><br><br>';
        }
    }
}
?>
<script>
$(document).on("focus", ".dtp", function() {
		$('.dtp').datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});   
	});
</script>
