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
<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border: 1px solid black; border-collapse: collapse;">
    <?php
    $jc = $msg = $jcourt = "";
    if (!empty($court_data)) {
        foreach ($court_data as $row10) {
            $jcodes11 = "";
            $jnm = "";
            $sbdb = "";
            $sbdb1 = "";
            $jabr = "";
            $t_cl_dt = '';
            $cstatus = $row10["c_status"];
            $jnm = $row10["judges"];
            $jcodes = $row10["judges"];
            $jcodes11 = $row10["judges"];
            $t_fil_no = $row10['reg_no_display'];
            $jcourt = isset($row10['jcourt']) ? $row10['jcourt'] : '';
            $printFrm = 0;
            //$msg = "";

            $caseno = '';

            if ($jc != $jnm) {
                $cntr = 0;
                $jc = $jnm;
                $stagec = "";
                $mf = "";
                $clno = 0;
                $chk_pslno = 0;
                $previous_brd_slno = 0;

                //CHECK ROSTER

                //END CHECK
                $ttt = 1;
                //$verify_str = $chk_slno = $caseno  = $rop_view = $head1 = $heardt_purpose = $heardt_mainhead = $heardt_board_type = $heardt_stagename = $brdremark = $heardt_updtby = "";

    ?>

                <thead>
                    <tr style="padding: 10px;" align="left">
                        <th colspan="10" height="25px"><br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAUSE LIST FOR <font color="red"><?= date('l', strtotime($tdt1)) . ' ' . $dtd ?></font></b>&nbsp;[COURT NO. : <?= $jcourt ?> ] </th>
                    </tr>
                    <tr align="left" height="20px">
                        <th width="5%">C.L.No.</th>
                        <th>Case No.</th>
                        <th>Cause Title</th>
                        <th>Advocates</th>
                        <th>ROP/<br>C.O.</th>
                        <th>Purpose/Stage/<br>Court Type/Head</th>
                        <th>Statutary</th>
                        <th>Updated By</th>
                        <th>Tentative Date</th>
                        <th>Action</th>
                    </tr>
                <?php  } ?>
                <?php
                $verify_str = "";
                $verify_str = $row10["diary_no"] . "_" . $row10["mainhead"] . "_" . $row10["board_type"] . "_" . $tdt1 . "_" . $jcourt . "_" . $row10['tentative_cl_dt'];
                $d1 = $row10["case_no"];
                $d2 = $row10["year"];
                $caseno = "DN:<a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d1', '$d2', '', '', '');\" href='#'>" . $row10["case_no"] . " / " . $row10["year"];

                echo '<tbody style="page-break-inside: avoid;">';

                $rop_view = "";
                if (!empty($row10['ordernet'])) {
                    $rop_view = "ROP : <span style='color:blue;'>";
                    foreach ($row10['ordernet'] as $ro_rop) {
                        $rjm = explode("/", $ro_rop['pdfname']);
                        if ($rjm[0] == 'supremecourt') {
                            $rop_view .= '<a href="../jud_ord_html_pdf/' . $ro_rop['pdfname'] . '" target="_blank">View</a>';
                        } else {
                            $rop_view .= '<a href="../judgment/' . $ro_rop['pdfname'] . '" target="_blank">View</a>';
                        }
                    }
                    $rop_view .= "</span>";
                } else {
                    $rop_view = "ROP : <span style='color:red;'>Not Available</span>";
                }

                $brdremark = "";
                if (!empty($row10['brdrem'])) {
                    $brdremark = $row10['brdrem']['remark'];
                }

                if ($mf != $row10["mainhead"]) {
                    $mf = $row10["mainhead"];
                    $t_mf = "";
                    if ($mf == "M")
                        $t_mf = "MISCELLANEOUS HEARING";
                    if ($mf == "F")
                        $t_mf = "REGULAR HEARING";
                    if ($mf == "S")
                        $t_mf = "MEDIATION";
                    echo '<tr height="20px" valign="top"><td colspan="10" align="center"><u><b>' . $t_mf . '</b></u></td></tr>';
                }
                if ($clno != $row10["clno"]) {
                    $clno = $row10["clno"];
                }
                $t_stagename = "";
                /*if ($mf == "F") {
                    $t_stage = "";
                    if (!empty($row10['subheading'])) {
                        foreach ($row10['subheading'] as $row1_s) {
                            if ($row1_s["stagecode4"] > 0)
                                $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["grp_name2"] . " - " . $row1_s["stagename"];
                            elseif ($row1_s["stagecode3"] > 0)
                                $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["stagename"];
                            elseif ($row1_s["stagecode2"] > 0)
                                $t_stage = $row1_s["grp_name"] . " - " . $row1_s["stagename"];
                            elseif ($row1_s["stagecode1"] > 0)
                                $t_stage = $row1_s["stagename"];
                            $t_stagename = $t_stage;
                        }
                    }
                } else {
                    $t_stagename = $row10["stagename"];
                }*/
                $t_cntr = 0;
                if ($previous_brd_slno == $row10["brd_slno"]) {
                    $t_cntr++;
                } else {
                    $t_cntr = 0;
                }

                $previous_brd_slno = $row10["brd_slno"];

                if (!empty($row10["showlcd"])) {
                    $row_r1 = $row10["showlcd"];

                    if ($mf == $row_r1[0] and $row10["brd_slno"] == $row_r1[1])
                        $chkd = " checked='checked'";
                    else
                        $chkd = "";
                } else
                    $chkd = "";

                $chk_slno = $row10["brd_prnt"];
                if ($chk_slno == "?-")
                    if ($printFrm == 0) {
                        echo "<span class='blink_me'><font style='font-weight:bold;color:green; font-size:10px;'><b>Cause List Serial .No Will be Available after Finalization  Of The Cause List. </b></font></span>";
                        $printFrm++;
                    }

                if ($chk_slno == $chk_pslno and $chk_pslno != '') {
                    echo '<tr height="20px" valign="top"><td></td><td colspan="9"><font color=red>CONNECTED CASE</font></td></tr>';
                    $chk_slno = $previous_brd_slno . '.' . $t_cntr;
                }


                $t_enable = $find_150151 = "";
                if (!empty($row10["rhead"])) {

                    $find_150151 = $row10["rhead"][0];
                }

                if (isset($row10["jud1"])) {
                    if ($row10["jud1"] == "514" or $row10["jud1"] == "500" or $row10["jud1"] == "550")
                        $t_enable = " disabled=disabled ";
                }

                $head1 = $txt_value = "";
                if (!empty($row10["head_deails"])) {
                    foreach ($row10['head_deails'] as $row_s) {
                        $t_cl_dt = "";
                        if ($row_s['side'] == "P") {
                            $head1 .= '<b><font color="blue">';
                            $t_cl_dt = date('d-m-Y', strtotime($row10["tentative_cl_dt"]));
                        }
                        if ($row_s['side'] == "D") {
                            $head1 .= '<b><font color="red">';
                            $t_cl_dt = "";
                        }

                        $head1 .= $row_s['head'];
                        if ($row_s['head_content'] != "")
                            $head1 .= ' [' . $row_s['head_content'] . ']';
                        $head1 .= '</font></b><br>';
                        $txt_value .= $row_s['r_head'] . "|" . $row_s['head_content'] . "^^";
                    }
                }



                $heardt_updtby = "";
                $heardt_stagename = "";
                $heardt_purpose = "";
                $heardt_mainhead = "";
                $heardt_board_type = "";
                if (!empty($row10["heardt_board_type"])) {
                    $ro_hu = $row10["heardt_board_type"];
                    $t_cl_dt = date('d-m-Y', strtotime($ro_hu["tentative_cl_dt"]));
                    $heardt_updtby = $ro_hu['name'] . ' - ' . $ro_hu['empid'];
                    $heardt_stagename = $ro_hu['stagename'];
                    $heardt_purpose = '<span style="color:green;">' . $ro_hu['purpose'] . '</span>';
                    if ($ro_hu['mainhead'] == 'F') {
                        $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Regular</span>';
                    }
                    if ($ro_hu['mainhead'] == 'M') {
                        $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Misc.</span>';
                    }
                    if ($ro_hu['board_type'] == 'J') {
                        $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Court</span>';
                    }
                    if ($ro_hu['board_type'] == 'C') {
                        $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Chamber</span>';
                    }
                    if ($ro_hu['board_type'] == 'R') {
                        $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Registrar</span>';
                    }
                } else {
                    $brdremark = "";
                }


                echo '<tr id="' . $verify_str . '" height="25px" valign="top"><td>' . $chk_slno . '</td><td><b>' . $caseno . '</br>' . $t_fil_no . '</b></td>';
                echo '<td>' . $row10["pet_name"] . '&nbsp;&nbsp;Vs.<br>' . $row10["res_name"] .  '</td><td>P : ' . $row10["padv1"]  . '</br>R :' . $row10["radv1"] . '</td><td>' . $rop_view . '<br>CO : ' . $head1 . '</td><td>' . $heardt_purpose . $heardt_mainhead . $heardt_board_type . '<br>' . $heardt_stagename . '</td><td>' . $brdremark . '</td><td>' . $heardt_updtby . '</td><td>' . $t_cl_dt . '</td>';
                ?>

                <td>
                    <?php
                    if (!empty($row10["case_verify_rop"])) {
                        foreach ($row10["case_verify_rop"] as $row_verif) {
                            echo "<span style='color:green;'><b>" . $row_verif['rem_dtl'] . "</b> at " . date('d-m-Y H:i:s', strtotime($row_verif['ent_dt'])) . "</span><br>";
                        }
                    }

                    if ($vstats == 1 or (isset($row10['cl_dt']) && $row10['cl_dt'] != null)) {
                    } else {
                        if (!empty($case_verify_by_sec_remark)) {
                    ?> <?= csrf_field() ?>
                            <select class="ele" name="rremark_<?php echo $row10['diary_no']; ?>" id="rremark_<?php echo $row10['diary_no']; ?>"
                                size=3; multiple="multiple" style="width: 130px;">
                                <?php
                                foreach ($case_verify_by_sec_remark as $row_rem) {
                                    if ($row_rem['id'] == '1') {
                                        $sel_id = "selected='selected'";
                                    } else {
                                        $sel_id = "";
                                    }
                                ?>
                                    <option value="<?php echo $row_rem['id']; ?>" <?php echo $sel_id; ?>> <?php echo $row_rem['remarks']; ?></option>
                            <?php
                                }
                            }

                            ?>
                            </select>
                            <input type='button' class="btn btn-primary btn-sm" name='bsubmit' id='bsubmit' value='Verify' onClick='javascript:addRecord_rop("<?php echo $verify_str; ?>")' />
                        <?php  } ?>
                </td>
                <?php echo '</tr>';
                echo '</tbody>';
                ?>

            <?php } ?>

</table>
<?php } else { ?>
    <table border="0" width="100%">
        <tr align="center">
            <td>
                <font color="#CC0000"><b><br><br>No Records Found</b></font>
            </td>
        </tr>
    </table>
    <br>
    </table>
<?php } ?>

<br>

<?php
if ($msg != "")
    $msg = "Pass over cases : " . substr($msg, 0, -2);
$temp_msg = "";
if (!empty($row10["showlcd1"])) {
    $row_new = $row10["showlcd1"];
    $temp_msg = $row_new['msg'];
    if ($msg != "")
        $msg .= "; Message : " . $row_new['msg'];
    else
        $msg = "Message : " . $row_new['msg'];
}

$t_paps = '';
?>

<input type="hidden" name="paps" id="paps" value="<?php print $t_paps; ?>">
<input type="hidden" name="msg2" id="msg2" value="<?php print $jcourt . '::' . $tdt1 . ':::'; ?>">
<input type="hidden" name="msg1" id="msg1" value="<?php print $msg; ?>">

<div id="dv_fixedFor_P" style="display: none;position: fixed;top:75px;left:10% !important;width:85%;height:100%;z-index: 105;">
    <div id="close_b" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="close_cs()"><b><img src="<?php echo base_url('images/close_btn.png'); ?>" style="width:30px;height:30px" /></b></div>
    <div id="newcs123" style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;">
    </div>
</div>
<!--<div id="newcs" style="display:none;">
    <table width="100%" border="0" style="border-collapse: collapse">
        <tr style="background-color: #A9A9A9;">
            <td align="center">
                <b>
                    <font color="black" style="font-size:14px;">Case Status</font>
                </b>
            </td>
            <td>
                <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_cs();" />
            </td>

        </tr>
    </table>
    <div id="newcs123" style="overflow:auto; background-color: #FFF;"></div>
    <div id="newcs1" align="center">
        <table border="0" width="100%">
            <tr>
                <td align="center" width="250px">
                </td>
            </tr>
        </table>
    </div>
</div>-->