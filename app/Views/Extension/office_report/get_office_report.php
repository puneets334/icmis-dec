<?php
$year = date('Y');
$res_status = $officeReportModel->get_chk_status($diary_no);
if (count($res_status) > 0) {

    $pno = '';
    $rno = '';
    if ($res_status['pno'] != 0) {
        if ($res_status['pno'] == 2)
            $pno = " AND ANOTHER";
        else if ($res_status['pno'] > 2)
            $pno = " AND OTHERS";
    }
    if ($res_status['rno'] != 0) {
        if ($res_status['rno'] == 2)
            $rno = " AND ANOTHER";
        else if ($res_status['rno'] > 2)
            $rno = " AND OTHERS";
    }
    
    if ($res_status['c_status'] == 'P') {

        $checkSection = $officeReportModel->get_user_section($ucode);
        $check_section = @$checkSection['section'];

        //check if concerned DA is generating Office Report
        if (($res_status['dacode'] == '' || $res_status['dacode'] == null || $res_status['dacode'] == 0) && $check_section != 77) {
            echo "<div align='center'><font style='color: red'>DA not found in matter.. Office Report can not be generated. Please Update DA in matter</font></div>";
            return;
        } elseif ($res_status['dacode'] != $ucode && $check_section != 77) {
            echo "<div align='center'><font style='color: red'>Only Concerned Dealing Assistant can upload Office Report !!!!</font></div>";
            return;
        }


        //       $chk_pnt="Select a.next_dt from heardt a join cl_printed b on a.next_dt=b.next_dt and  	
        //a.roster_id=b.roster_id and a.mainhead=b.m_f and a.clno=b.part and b.main_supp=a.main_supp_flag 
        //where diary_no='$dairy_no' and a.next_dt>=curdate() and  	
        //(main_supp_flag=1 or main_supp_flag=2) and a.roster_id!=0 and clno!=0 and brd_slno!=0 and display='Y'";

        $chk_pnt = $officeReportModel->get_chk_pnt();
        if (count($chk_pnt) > 0) {
            $r_chk_pnt = $chk_pnt['next_dt'];
           

?>
            <input type="hidden" name="hd_next_dt" id="hd_next_dt" value="<?php echo $r_chk_pnt ?>" />

            <?php
            $res_office_report = $officeReportModel->get_office_report($diary_no, $r_chk_pnt);
            if (!empty($res_office_report)) {
                $emp_full_con = 0;
            ?>
                <div>
                    <div class="cl_center" style="text-align: center">

                        <?php
                        $res_connected_cases = '';
                        $r_connected_cases = $officeReportModel->get_connected_cases($diary_no);
                        if (count($r_connected_cases) > 0) {
                            $r_connected_cases = $r_connected_cases['conn_key'];
                        ?>
                            <b>Connected Cases</b>
                            <?php
                            if ($r_connected_cases != $dairy_no && $r_connected_cases != '')
                                $res_connected_cases = $r_connected_cases;
                            $cnt_cases = $officeReportModel->get_cnt_cases($r_connected_cases, $diary_no);
                            if (count($cnt_cases) > 0) {
                                foreach ($cnt_cases as $row6) {
                                    if ($res_connected_cases == '' && $row6['diary_no'] != $dairy_no)
                                        $res_connected_cases = $row6['diary_no'];
                                    else if ($row6['diary_no'] != $dairy_no)
                                        $res_connected_cases = $res_connected_cases . ',' . $row6['diary_no'];
                                }
                            }
                            //     echo $res_connected_cases;
                            $ex_connected_cases = explode(',', $res_connected_cases);
                            $p_cnt = 1;
                            foreach ($ex_connected_cases as $diary_no) {
                            ?>
                                <table align="center">
                                    <tr>
                                        <td>
                                            <?php
                                            if ($emp_full_con == 1) {

                                                $r_get_batch = $officeReportModel->getOfficeReportDetails($dairy_no, $r_chk_pnt);
                                                $r_chk_al_con = $officeReportModel->getRchkAlCon($diary_no, $r_chk_pnt, $res_max_o_r, $r_get_batch);
                                            }
                                            ?>
                                            <input type="checkbox" name="chk_cnt_case<?php echo $p_cnt; ?>"
                                                id="chk_cnt_case<?php echo $p_cnt; ?>" value="<?php echo $diary_no; ?>" class="cl_chk_cnt_case" <?php if (!empty($r_chk_al_con)) { ?> checked="checked" <?php } ?> />
                                        </td>
                                        <td>
                                            <span id="sp_dname<?php echo $p_cnt; ?>">
                                                <?php
                                                // Get reg_no_display                                                
                                                $row = $officeReportModel->getRegNoDisplay($diary_no);

                                                if (!empty($row) && trim($row->reg_no_display) != '') {
                                                    echo $row->reg_no_display;
                                                } else {
                                                    echo "Diary No. " . substr($diary_no, 0, strlen($diary_no) - 4) . '/' . substr($diary_no, -4);
                                                }
                                                ?></span>
                                        </td>
                                    </tr>
                                </table>
                        <?php

                                $p_cnt++;
                            }
                        } ?>
                        <!--<input type="hidden" name="hd_chk_cnt_case" id="hd_chk_cnt_case" value="<?php //echo $res_connected_cases; 
                                                                                                    ?>"/>-->
                    </div>
                </div>


                <textarea placeholder="enter summary" class="btn-block summary" cols="88" rows="4" maxlength="500" style=" color:red;" name="summary" id="summary"></textarea>


                <div style="text-align: center; background-color: white; clear: both;" id="dv_edi">
                    <input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()" />
                    <input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()" />
                    <input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()" />
                    <b>Font Size</b>
                    <select name="ddlFS" id="ddlFS" onchange="getFS(this.value)">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()" />
                    <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()" />
                    <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()" />
                    <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()" />
                    <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="get_set_prt()" />
                    <select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="'Kruti Dev 010'">Kruti Dev</option>
                    </select>
                    <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()" />
                    <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()" />
                    <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo" />
                    <input type="text" name="txtReplace" id="txtReplace" />
                    <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All" />
                    <input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none" />
                    <input type="button" name="btn_publish" id="btn_publish" value="Publish" onclick="publish_record()" />
                    <input type="button" name="btn_prnt" id="btn__prnt" value="Print" onclick="draft_record1()" />
                </div>


                <div contenteditable="true" style="width: auto; margin-left: 40px; margin-right: 40px; margin-bottom: 25px; margin-top: 10px; padding-left: 10px; padding-right: 10px; word-wrap: break-word; border: 1px solid black;" id="ggg" onkeypress="return nb(event)" onmouseup="checkStat()">
                    <?php
                    $case_details = $officeReportModel->getFilDet($dairy_no);
                    if (!empty($case_details)) {
                        $case_range = intval(substr($case_details['fil_no'], 3));
                        $reg_year = date('Y', strtotime($case_details['fil_dt']));

                        switch ($ddl_rt) {
                            case 1:
                                include('office_report/criminal/after_notice_criminal.php');
                                break;
                            case 2:
                                include('office_report/civil/2.php');
                                break;
                            case 3:
                                include('office_report/criminal/3.php');
                                break;
                            case 4:
                                include('office_report/criminal/4.php');
                                break;
                            case 5:
                                include('office_report/criminal/5.php');
                                break;
                            case 6:
                                include('office_report/criminal/6.php');
                                break;
                            case 7:
                                include('office_report/criminal/7.php');
                                break;
                            case 8:
                                include('office_report/criminal/8.php');
                                break;
                            case 9:
                                include('office_report/criminal/9.php');
                                break;
                            case 10:
                                include('office_report/civil/10.php');
                                break;
                            case 11:
                                include('office_report/civil/11.php');
                                break;
                            case 12:
                                include('office_report/civil/12.php');
                                break;
                            case 13:
                                include('office_report/criminal/13.php');
                                break;
                            case 14:
                                include('office_report/criminal/14.php');
                                break;
                            case 15:
                                include('office_report/civil/15.php');
                                break;
                            case 16:
                                include('office_report/civil/16.php');
                                break;
                            case 17:
                                include('office_report/civil/17.php');
                                break;
                            case 18:
                                include('office_report/civil/17.php');
                                break;
                            case 19:
                                include('office_report/civil/18.php');
                                break;
                            case 20:
                                include('office_report/civil/18.php');
                                break;
                            case 21:
                                include('office_report/civil/21.php');
                                break;
                            case 22:
                                include('office_report/civil/21.php');
                                break;
                            default:
                                break;
                        }
                    }
                    ?>
                </div>
            <?php
            } else {
                $office_report = $officeReportModel->getOfficeReport($dairy_no, $r_chk_pnt);
                $res_office_report = $office_report['office_repot_name'];
                $res_summary = $office_report['summary'];
                echo "<br><br>";
            ?>
                <textarea placeholder="enter summary" class="btn-block summary" cols="88" rows="4" maxlength="500" style=" color:red;" name="summary" id="summary"><?= $res_summary ?></textarea>
                <?php
                //echo $summary = "Summary : ".mysql_result($office_report, 0, 'summary');
                echo "<br><br>";

                $res_max_o_r = $office_report['office_report_id'];
                $emp_full_con = 1;
                ?>

                <div>
                    <div class="cl_center" style="text-align: center">

                        <?php
                        $res_connected_cases = '';
                        $r_connected_cases = $officeReportModel->get_connected_cases($diary_no);
                        if (count($r_connected_cases) > 0) {
                            $r_connected_cases = $r_connected_cases['conn_key'];
                        ?>
                            <b>Connected Cases</b>
                            <?php
                            if ($r_connected_cases != $dairy_no && $r_connected_cases != '')
                                $res_connected_cases = $r_connected_cases;
                            $cnt_cases = $officeReportModel->get_cnt_cases($r_connected_cases, $diary_no);
                            if (count($cnt_cases) > 0) {
                                foreach ($cnt_cases as $row6) {
                                    if ($res_connected_cases == '' && $row6['diary_no'] != $dairy_no)
                                        $res_connected_cases = $row6['diary_no'];
                                    else if ($row6['diary_no'] != $dairy_no)
                                        $res_connected_cases = $res_connected_cases . ',' . $row6['diary_no'];
                                }
                            }
                            //     echo $res_connected_cases;
                            $ex_connected_cases = explode(',', $res_connected_cases);
                            $p_cnt = 1;
                            foreach ($ex_connected_cases as $diary_no) {
                            ?>
                                <table align="center">
                                    <tr>
                                        <td>
                                            <?php
                                            if ($emp_full_con == 1) {
                                                // Get batch
                                                $r_get_batch = $officeReportModel->getOfficeReportDetails($dairy_no, $r_chk_pnt);
                                                $r_chk_al_con = $officeReportModel->getRchkAlCon($diary_no, $r_chk_pnt, $res_max_o_r, $r_get_batch);
                                            }
                                            ?>
                                            <input type="checkbox" name="chk_cnt_case<?php echo $p_cnt; ?>"
                                                id="chk_cnt_case<?php echo $p_cnt; ?>" value="<?php echo $diary_no; ?>" class="cl_chk_cnt_case" <?php if (!empty($r_chk_al_con)) { ?> checked="checked" <?php } ?> />
                                        </td>
                                        <td>
                                            <span id="sp_dname<?php echo $p_cnt; ?>">
                                                <?php
                                                $row = $officeReportModel->getRegNoDisplay($diary_no);
                                                if (!empty($row) && trim($row->reg_no_display) != '') {
                                                    echo $row->reg_no_display;
                                                } else {
                                                    echo "Diary No. " . substr($diary_no, 0, strlen($diary_no) - 4) . '/' . substr($diary_no, -4);
                                                }
                                                ?></span>
                                        </td>
                                    </tr>
                                </table>
                        <?php

                                $p_cnt++;
                            }
                        } ?>
                        <!--<input type="hidden" name="hd_chk_cnt_case" id="hd_chk_cnt_case" value="<?php //echo $res_connected_cases; 
                                                                                                    ?>"/>-->
                    </div>
                </div>


                <div style="text-align: center; background-color: white; clear: both;" id="dv_edi">
                    <input type="button" name="btnItalic" id="btnItalic" value="I" onclick="getItalic()" />
                    <input type="button" name="btnBold" id="btnBold" value="B" onclick="getBold()" />
                    <input type="button" name="btnUnderline" id="btnUnderline" value="U" onclick="getUnderline()" />
                    <b>Font Size</b>
                    <select name="ddlFS" id="ddlFS" onchange="getFS(this.value)">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="button" name="btnJustify" id="btnJustify" value="Center" onclick="jus_cen()" />
                    <input type="button" name="btnAliLeft" id="btnAliLeft" value="Align Left" onclick="jus_left()" />
                    <input type="button" name="btnAliRight" id="btnAliRight" value="Align Right" onclick="jus_right()" />
                    <input type="button" name="btnFull" id="btnFull" value="Justify" onclick="jus_full()" />
                    <input type="button" name="btnPrintable" id="btnPrintable" value="Print and Save" onclick="get_set_prt()" />
                    <select name="ddlFontFamily" id="ddlFontFamily" onchange="getFonts(this.value)">
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="'Kruti Dev 010'">Kruti Dev</option>
                    </select>
                    <input type="button" name="btnIndent" id="btnIndent" value="Indent" onclick="get_intent()" />
                    <input type="button" name="btnsupScr" id="btnsupScr" value="Superscript" onclick="get_supScr()" />
                    <input type="button" name="txtRedo" id="txtRedo" onclick="gt_redo()" value="Redo" />
                    <input type="text" name="txtReplace" id="txtReplace" />
                    <input type="button" name="btnReplace" id="btnReplace" onclick="fin_rep()" value="Replace All" />
                    <input type="button" name="btn_sign" id="btn_sign" value="Sign" onclick="sign()" style="display:none" />
                    <input type="button" name="btn_publish" id="btn_publish" value="Publish" onclick="publish_record()" />
                    <input type="button" name="btn_prnt" id="btn__prnt" value="Print" onclick="draft_record1()" />
                </div>


                <div contenteditable="true" style="width: auto;margin-left: 40px;margin-right: 40px;margin-bottom: 25px;margin-top: 10px;padding-left: 10px;padding-right: 10px;word-wrap: break-word;border: 1px solid black" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
                <?php


                $fil_nm = getBasePath() . "/officereport/" . $d_yr . '/' . $d_no . '/' . $res_office_report;
                if (file_exists($fil_nm)) {
                    $file_content = file_get_contents($fil_nm);
                    echo utf8_encode($file_content);
                }
            }
                ?>
                </div>
                <input type="hidden" name="hd_or_id" id="hd_or_id" value="<?php echo $res_max_o_r; ?>" />
            <?php
        } else {
            ?>
                <div style="text-align: center"><b>Can't generate office report because cause list not yet printed.</b></div>
            <?php
        }
    } else {
            ?>
            <div style="text-align: center"><b>Case already disposed</b></div>
        <?php
    }
} else {
        ?>
        <div style="text-align: center"><b>No Record Found</b></div>
    <?php
}
    ?>