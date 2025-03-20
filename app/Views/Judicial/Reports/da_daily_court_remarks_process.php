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
    $chk_var = 1;
    echo '<table  width="100%" cellpadding="0" cellspacing="0" >';
    foreach ($results10 as $row10) {
        $con1 = "";
        $con = "";
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

        $t_fil_no = $row10['t_fil_no'];

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
            echo '<thead><tr align="left"><th colspan="6" height="25px"><br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAUSE LIST FOR <font color="red">' . date('l', strtotime($tdt1)) . ' ' . $dtd . '</font></b></th></tr>';
            echo '<tr  align="left"><th colspan="6" height="25px"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BEFORE <font color="blue">' . stripslashes(get_judges($jnm)) . '</font>&nbsp;' . $bench_from_roster . '&nbsp;[COURT NO. : ' . $jcourt . ']</b></th></tr>';
            echo '<tr><th colspan="6"><hr></th></tr>'; 
            ?>
            <tr>
                <th colspan="6" align="center">
                    <?php dcr_get_drop_note_print($row10['next_dt'], $row10['mainhead'], $row10['roster_id']); ?>
                </th>
            </tr>
        <?php
            echo '<tr><th colspan="6"><hr></th></tr>';
            echo '<tr align="left" height="20px"><th width="5%">C.L.No.</th><th>Case No.</th><th>Petitioner Vs. Respondent</th><th>Advocates for Pet./Res.</th><th>Remarks</th><th>Tentative Computer Date</th></tr>';
            echo '<tr><th colspan="6"><hr></th></tr></thead>';
        }
        else{
            $cntr = 0;
            $jc = $jnm;
            $stagec = "";
            $mf = "";
            $clno = 0;
            $chk_pslno = 0;
            $previous_brd_slno = 0;
        }


        // $verify_str = "";
        // $verify_str = $row10["diary_no"] . "_" . $row10["mainhead"] . "_" . $row10["board_type"] . "_" . $tdt1 . "_" . $jcourt . "_" . $row10['tentative_cl_dt'];
        $cntr += 1;

        $tmp_caseno = $row10["diary_no"];
        
        $caseno = "DN:".$row10["case_no"]." / ".$row10["year"];

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
            echo '<tr height="25px" valign="top"><td></td><td align="left" colspan="5"><font style="font-weight:bold;color:green; font-size:16px;"><u><span class="blink_me">SESSION NO : ' . $clno . '</span></u></font></td></tr>';
        }
        $t_stagename = "";
        if ($mf == "F") {
            $t_stage = "";
            
            // $sql_s = "select * from subheading where stagecode=" . $row10["subhead"] . " and display='Y'";
            // $result1_s = mysql_query($sql_s);
            $result1_s = $row10["result1_s"];
            if (!empty($result1_s) && isset($result1_s)) {
                foreach($result1_s as $row1_s) {
                    if (@$row1_s["stagecode4"] > 0)
                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["grp_name2"] . " - " . $row1_s["stagename"];
                    elseif (@$row1_s["stagecode3"] > 0)
                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["stagename"];
                    elseif (@$row1_s["stagecode2"] > 0)
                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["stagename"];
                    elseif (@$row1_s["stagecode1"] > 0)
                        $t_stage = $row1_s["stagename"];
                    $t_stagename = $t_stage;
                }
            }
        } else {
            $t_stagename = $row10["stagename"];
        }

        if ($stagec != $t_stagename) {
            $stagec = $t_stagename;
            echo '<tr height="20px" valign="top"><td></td><td colspan="5"><b>' . $stagec . '</b></td></tr>';
        }

        $t_cntr = 0;
        if ($previous_brd_slno == $row10["brd_slno"]) {
            $t_cntr++;
        } 
        // else {
        //     $t_cntr = 0;
        // }

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
        // $heardt_updtby = $row10["heardt_updtby"];
        $heardt_stagename = $row10["heardt_stagename"];
        // $heardt_purpose = $row10["heardt_purpose"];
        $heardt_mainhead = $row10["heardt_mainhead"];
        // $heardt_board_type = $row10["heardt_board_type"];

        $rs_lct = $row10["rs_lct"];
        $t_fil_no1="";
        $t_fil_no1 .= "<br/>";
        foreach($rs_lct as $ro_lct){
            $t_fil_no1 .= " IN ".$ro_lct['type_sname']." - ".$ro_lct['lct_caseno']."/".$ro_lct['lct_caseyear'].", ";
        }

        $tentative_cl_dt='';
        if(get_display_status_with_date_differnces($t_cl_dt)=='T')
        {
            $tentative_cl_dt= $t_cl_dt;
        }

        $result_drop = $row10['result_drop'];
        $drop_note = " <tr><td colspan=3><font color='red' style='font-family:Verdana, Arial;font-size:11px;font-weight:bold;'>Drop Case </font>";
        foreach($result_drop as $row_drop) {
            $drop_note.=" <br>[<font color='red' style='font-family:Verdana, Arial;font-size:11px;font-weight:bold;'>" . $row_drop["nrs"] . "</font>]";
            $t_drp_jname = stripslashes($row_drop["jnm"]);
        }
        $drop_note.="</td></tr>";

        $chk_slno = '';
        $con1 = '';
        $caseno = '';
        $t_fil_no = '';
        $t_fil_no1 = '';
        $chk_slno = '';
        $chk_slno = '';

        echo '<tr height="25px" valign="top"><td>' . $chk_slno . '</td><td><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td>' . $con1 . '</td><td align="left"></td><td><b>' . $caseno . '</br>'.$t_fil_no .$t_fil_no1. '</b></td><td>' . $con . '</td></tr>' . $drop_note . '</table></td><td>' . $row10["pet_name"] . '&nbsp;&nbsp;Vs.&nbsp;&nbsp;' . $row10["res_name"] .  '</td><td>Pet. Side: ' . $padv1 . '</br>Res. Side:'.$radv1. '</td><td>'.$head1.'</td><td>'.$tentative_cl_dt.'</td></tr>';

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