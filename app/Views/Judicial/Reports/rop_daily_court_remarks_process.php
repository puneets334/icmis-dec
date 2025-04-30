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
<?php
$crt = $_REQUEST['courtno'];
$dtd = $_REQUEST['dtd'];
$jcd = $_REQUEST['aw1'];
$mf = $_REQUEST['mf'];
$r_status = $_REQUEST['r_status'];
$msg = "";
$jcourt = 0;
if(!empty($dtd)) {
    $tdt = explode("-", $dtd);
    $tdt1 = $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
}
$printFrm = 0;
///=====Not Show If Cause List Not Print 
$pr_mf = $mf;
$sql_t = "";
$ttt = 0;

$jc = "";
$chk_var = 0;
$not_avail = "";
if (count($results10) > 0) {
    $t_cntr = 0;
    $chk_var = 1;
    echo '<table  width="100%" border="1" cellpadding="0" cellspacing="0" style="border: 1px solid black; border-collapse: collapse;" >';
    foreach ($results10 as $row10) {
        $jcodes = "";
        $jcodes11 = "";
        $jnm = "";
        $sbdb = "";
        $sbdb1 = "";
        $jabr = "";
        $t_cl_dt = '';
        //$jcourt = 0;
        $cstatus = $row10["c_status"];
        $jnm = $row10["judges"];
        $jcodes = $row10["judges"];
        $jcodes11 = $row10["judges"];
        $t_fil_no = $row10['reg_no_display'];

        if ($jc != $jnm) {

            $cntr = 0;
            $jc = $jnm;
            $stagec = "";
            $mf = "";
            $clno = 0;
            $chk_pslno = 0;
            $previous_brd_slno = 0;

            $jcourt = $row10["jcourt"];
            $bench_from_roster = $row10["bench_from_roster"];

            $ttt = 1;
            echo '<thead><tr style="padding: 10px;" align="left"><th colspan="10" height="25px"><br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAUSE LIST FOR <font color="red">' . date('l', strtotime($tdt1)) . ' ' . $dtd . '</font></b>&nbsp;[COURT NO. : ' . $jcourt . '] .</th></tr>';

            echo '<tr align="left" height="20px"><th width="5%">C.L.No.</th><th>Case No.</th><th>Cause Title</th><th>Advocates</th><th>ROP/<br>C.O.</th><th>Purpose/Stage/<br>Court Type/Head</th><th>Statutary</th><th>Updated By</th><th>Tentative Date</th><th>Action</th></tr>';
        }

        $verify_str = "";
        $verify_str = $row10["diary_no"] . "_" . $row10["mainhead"] . "_" . $row10["board_type"] . "_" . $tdt1 . "_" . $jcourt . "_" . $row10['tentative_cl_dt'];
        $cntr += 1;

        $tmp_caseno = $row10["diary_no"];

        $d1 = $row10["case_no"];
        $d2 = $row10["year"];
        $caseno = "DN:<a data-animation=\"fade\" data-reveal-id=\"myModal\" onclick=\"call_cs('$d1', '$d2', '', '', '');\" href='#'>" . $row10["case_no"] . " / " . $row10["year"];
        //HIDE CODE FOR ADVOCATES FROM MAIN TABLE
        $padv = explode(",", trim($row10['pet_adv_id']));
        $padv1 = "";
        echo '<tbody style="page-break-inside: avoid;">';
        for ($k = 0; $k < count($padv); ++$k) {
            if ($padv[$k] != 0) {
                if ($k == 0) {
                    $padv1 .= get_advocates_by_id($padv[$k]);
                } else {
                    $padv1 .= ", " . get_advocates_by_id($padv[$k]);
                }
            }
        }
        $radv = explode(",", trim($row10['res_adv_id']));
        $radv1 = "";
        for ($k = 0; $k < count($radv); ++$k) {
            if ($radv[$k] != 0) {
                if ($k == 0)
                    $radv1 .= get_advocates_by_id($radv[$k]);
                else
                    $radv1 .= ", " . get_advocates_by_id($radv[$k]);
            }
        }

        $rop_view = $row10['rop_view'];
        $brdremark = $row10['brdremark'];

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
        
        $t_stagename = $row10["t_stagename"];

        if ($stagec != $t_stagename) {
            $stagec = $t_stagename;
            echo '<tr height="20px" valign="top"><td></td><td colspan="5"><b>' . $stagec . '</b></td></tr>';
        }

        if ($previous_brd_slno == $row10["brd_slno"]) {
            $t_cntr++;
        } else {
            $t_cntr = 0;
        }

        if ($printFrm == 0) {
            echo "<span class='blink_me'><font style='font-weight:bold;color:green; font-size:10px;'><b>Cause List Serial .No Will be Available after Finalization  Of The Cause List. </b></font></span>";
            $printFrm++;
        }
        $chk_slno = $row10["brd_prnt"];
        if ($chk_slno == $chk_pslno and $chk_pslno != '') {
            echo '<tr height="20px" valign="top"><td></td><td colspan="5"><font color=red>CONNECTED CASE</font></td></tr>';
            $chk_slno = $previous_brd_slno . '.' . $t_cntr;
        }

        $head1 = $row10["head1"];

        $t_cl_dt = $row10["head1"];
        $heardt_updtby = $row10["heardt_updtby"];
        $heardt_stagename = $row10["heardt_stagename"];
        $heardt_purpose = $row10["heardt_purpose"];
        $heardt_mainhead = $row10["heardt_mainhead"];
        $heardt_board_type = $row10["heardt_board_type"];

        echo '<tr id="' . $verify_str . '" height="25px" valign="top"><td>' . $chk_slno . '</td><td><b>' . $caseno . '</br>' . $t_fil_no . '</b></td>';
        echo '<td>' . $row10["pet_name"] . '&nbsp;&nbsp;Vs.<br>' . $row10["res_name"] .  '</td><td>P : ' . $padv1 . '</br>R :' . $radv1 . '</td><td>' . $rop_view . '<br>CO : ' . $head1 . '</td><td>' . $heardt_purpose . $heardt_mainhead . $heardt_board_type . '<br>' . $heardt_stagename . '</td><td>' . $brdremark . '</td><td>' . $heardt_updtby . '</td><td>' . $t_cl_dt . '</td>';

        ?>

        <td>
            <?php
            if (!empty($res_verif)) {
                foreach ($res_verif as $row_verif) {
                    echo "<span style='color:green;'><b>" . $row_verif['rem_dtl'] . "</b> at " . date('d-m-Y H:i:s', strtotime($row_verif['ent_dt'])) . "</span><br>";
                }
            }

            if ($_REQUEST['vstats'] == 1 or $row10['cl_dt'] != null) {
            } else {
                if (!empty($res_rem)) {
            ?>
                    <select class="ele" name="rremark_<?php echo $row10['diary_no']; ?>" id="rremark_<?php echo $row10['diary_no']; ?>"
                        size=3; multiple="multiple" style="width: 130px;">
                        <?php
                        foreach ($res_rem as $row_rem) {
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
                    <input type='button' name='bsubmit' id='bsubmit' value='Verify' onClick='javascript:addRecord_rop("<?php echo $verify_str; ?>")' />
                <?php  } ?>
        </td>
        <?php

        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
} else
    echo '<table border="0" width="100%"><tr align="center"><td><font color="#CC0000"><b><br><br>No Records Found</b></font></td></tr></table>';
?><br><br><br><br><br>
</div>
</div>
<?php
if ($msg != "")
    $msg = "Pass over cases : " . substr($msg, 0, -2);

if (!empty($jcourt)) {
    if (!empty($row10['showlcd_message'])) {
        $row_new = $row10['showlcd_message'];
        $temp_msg = $row_new[0];
        if ($msg != "")
            $msg .= "; Message : " . $row_new[0];
        else
            $msg = "Message : " . $row_new[0];
    }
}

if (!empty($jcourt)) { ?>
    <input type="hidden" name="msg2" id="msg2" value="<?php print $jcourt . '::' . $tdt1 . ':::'; ?>">
<?php } ?>

<input type="hidden" name="msg1" id="msg1" value="<?php print $msg; ?>">

<div id="newcs" style="display:none;">
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