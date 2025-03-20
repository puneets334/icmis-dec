<?php
$mul_category = "";
$act_section = "";
$main_case = '';
$t_slpcc = '';
$t_spl = '';
if ($d_no != '' and $d_yr != '') {
    $sql = $model->getDiaryDetails($d_no, $d_yr);

    $main_fh_diary_no = "";
    if ($sql) {
        $fil_no = $sql;
        $isconn = $fil_no["ccdet"];
        $connto = $fil_no["connto"];
        $diaryno = $fil_no['diary_no'];

        if ($fil_no['diary_no'] != $fil_no['conn_key'] and $fil_no['conn_key'] != '')
            $check_for_conn = "N";
        else
            $check_for_conn = "Y";
        if ($fil_no['fil_no_fh'] != '')
            $main_fh_diary_no = "EXIST";
        $conn_type = "";

        $diary_no = $d_no . "/" . $d_yr;
        if ($fil_no['conn_key'] != '')
            if ($fil_no['conn_key'] == $fil_no['diary_no'])
                $conn_type = 'M';
            else
                $conn_type = 'C';
?>
        <div style="text-align: center">
            <strong>Diary No.- <?php echo $d_no; ?> - <?php echo $d_yr; ?></strong>
        </div>
        <?php
        $fil_date_for = $model->get_fil_date_for($diaryno);

        echo '<table border="0"  align="left" width="100%">';
        if ($main_case != "")
            $main_case = "<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";


        $u_name = "";
        $row_da = $model->getUserDetailsByDiaryNo($diaryno);
        if ($row_da) {
            $u_name = " by <font color='blue'>" . $row_da["name"] . "</font>";
            $u_name .= "<font> [SECTION: </font><font color='red'>" . $row_da["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
        }
        $t_res_ct_typ = $model->getShortDescription($fil_no['casetype_id']);
        $res_ct_typ = $t_res_ct_typ['short_description'];


        $result = $model->get_diary_details($diaryNo);
        $ctr_p = 0; //for counting petining 
        $ctr_r = 0; // for couting respondent

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
                $t_var = $t_dist['Name'];
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
                    $caseType = $model->getShortDescription($row['casetype_id']);
                    if ($caseType) {
                        $t_fil_no = $caseType['short_description'];
                    }
                }

                $t_slpcc = '';
                if ($t_slpcc != '') {
                    $t_slpcc = "<br>" . $t_slpcc;
                }

                $t_fil_no1 = '';
                $lowerCourtDetails = $model->getLowerCourtDetails($diaryNo);

                if (!empty($lowerCourtDetails)) {
                    foreach ($lowerCourtDetails as $ro_lct) {
                        if ($t_fil_no1 == '') {
                            $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                        } else {
                            $t_fil_no1 .= ", " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                        }
                    }
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
                        $mul_category = get_mul_category($fil_no['diary_no']);
                        echo $mul_category;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Act
                    </td>
                    <td>
                        <?php
                        $acts = $model->getActSections($diaryNo);

                        $act_section = '';

                        if (!empty($acts)) {
                            foreach ($acts as $row) {
                                if ($act_section == '') {
                                    $act_section = $row['act_name'] . '-' . $row['section'];
                                } else {
                                    $act_section .= ', ' . $row['act_name'] . '-' . $row['section'];
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
                        $law = $model->getLawById($fil_no['actcode']);
                        if ($law) {
                            echo esc($law['law']);
                        } else {
                            echo 'No law found.';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td style="width: 15%">
                        Petitioner Advocate
                    </td>
                    <td>
                        <?php echo get_advocates($fil_no['pet_adv_id'], 'wen'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Respondant Advocate
                    </td>
                    <td>
                        <?php echo get_advocates($fil_no['res_adv_id'], 'wen'); ?>
                    </td>

                </tr>
                <tr>
                    <td>
                        Last Order
                    </td>
                    <td>
                        <?php echo $fil_no['lastorder']; ?>
                    </td>
                </tr>
                <?php
                if ($fil_no['c_status'] == 'P') {
                    $rgoResults = $model->getDistinctFilNo($fil_no['diary_no']);
                    $t_rgo = '';

                    if (!empty($rgoResults)) {
                        foreach ($rgoResults as $res_rgo) {
                            if ($t_rgo == '') {
                                $t_rgo = "D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . str_replace('<br>', ' ', get_casenos_comma($res_rgo['fil_no2']));
                            } else {
                                $t_rgo .= "<br> D.No. " . get_real_diaryno($res_rgo['fil_no2']) . "<br>" . str_replace('<br>', ' ', get_casenos_comma($res_rgo['fil_no2']));
                            }
                        }
                    }

                    if ($t_rgo != '') {
                        echo "<tr>
                                <td>Conditional Dispose</td>
                                <td style='font-size:12px;font-weight:100;'><b><font style='font-size:12px;font-weight:100;'><b>" . esc($t_rgo) . "</b></font></b></td>
                              </tr>";
                    }
                    $r_ttv = $model->getTentativeClDt($fil_no['diary_no']);
                    $result_array = $model->getDisplayFlag();

                    if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) {
                ?>
                        <tr>
                            <td>
                                Tentative Date
                            </td>
                            <td>
                                <?php
                                if (get_display_status_with_date_differnces($r_ttv['tentative_cl_dt']) == 'T') {
                                    $tentative_date = $r_ttv['tentative_cl_dt'];
                                    echo change_date_format($tentative_date);
                                }

                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php


                    if ($isconn == 'Y') {
                        $connto = "<font color='red'>Main Case</font>";
                        $connectedCases = $model->getConnectedCases($diaryNo);

                        if (!empty($connectedCases)) {
                            foreach ($connectedCases as $row_oc) {
                                $connto .= "<br><font color='blue'>" . esc($row_oc["diary_no"]) . " </font>(Connected Case)";
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
        }
        if ($conn_type == '') {
            get_diary_set_fm($fil_no['diary_no'], $module, $conn_type);
        } else {
            $conncases = get_conn_cases($fil_no['diary_no']);
            foreach ($conncases as $row => $link) {
                if ($link['c_type'] != "") {
                    if ($link['c_type'] == "M")
                        echo '<b>Main Case</b>';
                    if ($link['c_type'] == "C")
                        echo '<b>Connected Case</b>';
                    if ($link['c_type'] == "L")
                        echo '<b>Linked Cases</b>';
                    get_diary_set_fm($link['diary_no'], $module, $link['c_type']);
                }
            }
        }
        if ($module == 'receive') {
        ?>
            <p align="center"><input type="button" name="receive" id="receive" value="Receive File" /></p>
        <?php
        }
        if ($module == 'dispatch') {
            include("users.php");
        ?>
            <p align="center"><input type="button" name="dispatch" id="dispatch" value="Dispatch File" /></p>
<?php
        }
    } else {
        echo '<p align="center"><font color=red>Case Details Not Found</font></p>';
    }
}
?>