<?php

$ucode = $usercode;
     if(!empty($get_drop_note_print)>0){
         ?>        
         <table class="mobview" border="1" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
             <tr><td style="text-align:left" colspan="3"><U>DROP NOTE</U>:-</td></tr>
            <tr><td style="text-align:left">Item No.</td><td style="text-align:left">Case No.</td><td style="text-align:left">Reason</td></tr>
        <?php
          foreach($get_drop_note_print as $row){    
        ?>
            <tr>
                <td style="text-align:left">
                    <?php echo $row['clno'] ?>
                </td>
                <td style="text-align:left">
                    <?php echo $row['case_no'] ?>
                </td>
                <td style="text-align:left">
                    <?php echo $row['nrs'] ?>
                </td>
            </tr>      
    <?php
    }
    ?>   </table><?php
     }    

$results10 = $get_list_data;
$jc = "";
$chk_var = 0;
$not_avail = "";
if (count($get_list_data) > 0) {
    $chk_var = 1;
    echo '<table  width="100%" border="1" cellpadding="0" cellspacing="0" style="border: 1px solid black; border-collapse: collapse;" >';
    foreach($results10 as $row10) {
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
            $t_fil_no=$row10['reg_no_display'];

        if ($jc != $jnm) {
             
            $cntr = 0;
            $jc = $jnm;
            $stagec = "";
            $mf = "";
            $clno = 0;
            $chk_pslno = 0;
            $previous_brd_slno = 0;
            
//CHECK ROSTER
$bench_from_roster="";
$results_rstr = rop_verify_fun1($row10["roster_id"]);
if(count($results_rstr) > 0){
$row_rstr = $results_rstr;
 $jcourt =$row_rstr["courtno"];
$bench_from_roster=" (".$row_rstr["bnch"].")";
if($row_rstr["session"]!="Whole Day")
    $bench_from_roster.=" (".$row_rstr["session"].")";
if(trim($row_rstr["frm_time"])!="")
    $bench_from_roster.=" (From ".$row_rstr["frm_time"].") ";
    
}            
//END CHECK
$ttt = 1;
            echo '<thead><tr style="padding: 10px;" align="left"><th colspan="10" height="25px"><br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAUSE LIST FOR <font color="red">' . date('l', strtotime($tdt1)) . ' ' . $dtd . '</font></b>&nbsp;[COURT NO. : ' . $jcourt . '] .</th></tr>';
            ?>

            <?php
            echo '<tr align="left" height="20px"><th width="5%">C.L.No.</th><th>Case No.</th><th>Cause Title</th><th>ROP/<br>C.O.</th><th>Purpose/Stage/<br>Court Type/Head</th><th>Updated By</th><th>Tentative Date</th><th>Action</th></tr>';
            
        }
        $verify_str = "";
        $verify_str = $row10["diary_no"]."_".$row10["mainhead"]."_".$row10["board_type"]."_".$tdt1."_".$jcourt."_".$row10['tentative_cl_dt'];
        $cntr += 1; {//2
            
            $row11 = rop_verify_fun2($row10["diary_no"]);
            $tmp_caseno = $row10["diary_no"];
//////Get Remarks
            $temp_rem = "";
            $results_r1 = rop_verify_fun3($row10["diary_no"],$tdt1,$jcodes11);
            if (count($results_r1) > 0) {
                $row_r1 = $results_r1;
                $temp_rem = $row_r1[0];
            }
            $caseno = "DN:".$row10["case_no"]." / ".$row10["year"];
//HIDE CODE FOR ADVOCATES FROM MAIN TABLE
           $padv = explode(",", trim($row10['pet_adv_id']));
            $padv1 = "";
            echo '<tbody style="page-break-inside: avoid;">';
            for ($k = 0; $k < count($padv); ++$k) {
                if($padv[$k]!=0){
                if ($k == 0)
                    $padv1.= get_advocates_by_id($padv[$k]);
                else
                    $padv1.= ", " . get_advocates_by_id($padv[$k]);
            }
            }
            $radv = explode(",", trim($row10['res_adv_id']));
            $radv1 = "";
            for ($k = 0; $k < count($radv); ++$k) {
                if($radv[$k]!=0){
                if ($k == 0)
                    $radv1.= get_advocates_by_id($radv[$k]);
                else
                    $radv1.= ", " . get_advocates_by_id($radv[$k]);
            }
            }
//END HIDE CODE FOR ADVOCATES FROM MAIN TABLE            
///Readers Remark
            $reader_remark = "";
            $reader_disp_type = "";
            $reader_disp_date = "";
           
            $results_rdr = rop_verify_fun4($row10["diary_no"],$tdt1);
            //column not found in table
            // if (count($results_rdr)>0 && count($results_rdr) !== 0) {       
            //     $row_rdr = $results_rdr[0];
            //     $reader_remark = $row_rdr["disp_rem"];
            //     $reader_disp_type = $row_rdr["dispname"];
            //     $reader_disp_date = $row_rdr["disp_dt"];
            // }
//DROP CHECK DROP CHECK
            $drop_note = "";
            //column not found in table
           // $sql_drop = "SELECT d.*, (SELECT GROUP_CONCAT(jname ORDER BY IF(jsen=0,99999,jsen)) FROM judge where (jcode=d.jud1 OR jcode=d.jud2) AND jcode!=0 ) as jnm FROM drop_note d WHERE d.diary_no='" . $row10["diary_no"] . "' AND d.display='Y' AND d.cl_date='" . date("Y-m-d", strtotime($tdt1)) . "' AND d.clno=" . $row10["brd_slno"] . " AND d.jud1=" . $row10["jud1"] . " AND d.jud2=" . $row10["jud2"] . " ORDER BY d.ent_dt ASC";
            $result_drop = rop_verify_fun5($row10["diary_no"], $tdt1, $row10["brd_slno"]);
            if (count($result_drop) > 0) {
                $drop_note = " <tr><td colspan=3><font color='red' style='font-family:Verdana, Arial;font-size:11px;font-weight:bold;'>Drop Case </font>";
                foreach ($result_drop as $row_drop) {
                    $drop_note.=" <br>[<font color='red' style='font-family:Verdana, Arial;font-size:11px;font-weight:bold;'>" . $row_drop["nrs"] . "</font>]";
                    $t_drp_jname = stripslashes($row_drop["jnm"]);
                }
                $drop_note.="</td></tr>";
            }
//DROP CHECK
//PAPS ALOTTMENT
            $paps_allotment = "";
            $paps_allotment_name = "";
           $result_paps = rop_verify_fun6($row10["diary_no"], $tdt1);
            if (count($result_paps) > 0) {
                foreach ($result_paps as $row_paps) {
                    $paps_allotment.=$row_paps["usercode"] . "^" . $row_paps["uname"] . "|";
                    $paps_allotment_name.=$row_paps["uname"] . ", ";
                }
                $paps_allotment_name = substr($paps_allotment_name, 0, -2);
            }


$rop_view = "";
$resus = rop_verify_fun7($row10['diary_no'], $tdt1);
    if(count($resus)>0){
        foreach($resus as $ro_rop){
            $rjm=explode("/",$ro_rop['pdfname']);
            if( $rjm[0]=='supremecourt') {
                $rop_view = 'ROP : <span style="color:blue;"><a href="../jud_ord_html_pdf/'. $ro_rop['pdfname'].'" target="_blank">View</a><br>';
            } else {
                $rop_view = 'ROP : <span style="color:blue;"><a href="../judgment/'. $ro_rop['pdfname'].'" target="_blank">View</a><br>';
            }

        }
        echo "</span>";
    }
    else{
        $rop_view = "ROP : <span style='color:red;'>Not Available</span>";
    }
///Readers Remark  
            $brdremark = "";
            $results1_b = rop_verify_fun8($row10["diary_no"]);
            
            $row_b = $results1_b[0];
            if (count($results1_b) > 0) {
                $brdremark = $row_b['remark'];
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
            //if ($mf == "F") {
                $t_stage = "";
                //9
                $result1_s = rop_verify_fun9($row10["subhead"]);
                // pr($result1_s);
                // die();
                if (count($result1_s) > 0) {
                    foreach ($result1_s as $row1_s) {
                        //column not found in table
                        // if ($row1_s["stagecode4"] > 0)
                        //     $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["grp_name2"] . " - " . $row1_s["stagename"];
                        // elseif ($row1_s["stagecode3"] > 0)
                        //     $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["stagename"];
                        // elseif ($row1_s["stagecode2"] > 0)
                        //     $t_stage = $row1_s["grp_name"] . " - " . $row1_s["stagename"];
                        // elseif ($row1_s["stagecode1"] > 0)
                            $t_stage = $row1_s["stagename"];
                        $t_stagename = $t_stage;
                    }
                }
            // }
            // else {
            //     $t_stagename = $row10["stagename"];
            // }
            if ($previous_brd_slno == $row10["brd_slno"]) {
                $t_cntr++;
            } else {
                $t_cntr = 0;
            }
            $previous_brd_slno = $row10["brd_slno"];
            $results_s = rop_verify_fun10($jcourt,$tdt1);
            if (count($results_s) > 0) {
                $row_r1 = $results_s;

                if ($mf == $row_r1[0] and $row10["brd_slno"] == $row_r1[1])
                    $chkd = " checked='checked'";
                else
                    $chkd = "";
            } else
                $chkd = "";


                $chk_slno = $row10["brd_prnt"];
                if($chk_slno == "?-")
                if ($printFrm == 0) {
                    echo "<span class='blink_me'><font style='font-weight:bold;color:green; font-size:10px;'><b>Cause List Serial .No Will be Available after Finalization  Of The Cause List. </b></font></span>";
                    $printFrm++;
                }

            if ($chk_slno == $chk_pslno and $chk_pslno!='') {
                echo '<tr height="20px" valign="top"><td></td><td colspan="5"><font color=red>CONNECTED CASE</font></td></tr>';
                $chk_slno = $previous_brd_slno . '.' . $t_cntr;
            }
            $t_enable = "";
////FOR NEW two case heads
$results_r150151 = rop_verify_fun11($tmp_caseno,$tdt1); 
$find_150151="";
if (count($results_r150151) > 0) {
$row_150151 = $results_r150151;
$find_150151=$row_150151[0];   
}
             //column not found in table
            //if($row10["jud1"]=="514" or $row10["jud1"]=="500" or $row10["jud1"]=="550")
            $t_enable = " disabled=disabled ";
            $chk_pslno = $row10["brd_slno"];
            $dt_t1 = date('Y-m-d',strtotime($dtd));
            $results_s = rop_verify_fun12($tmp_caseno,$dt_t1,$jcodes11);
            $head1 = "";
            $txt_value = "";
            if (count($results_s) > 0) {
                foreach ($results_s as $row_s) {
                    if ($row_s['side'] == "P"){
                        $head1.='<b><font color="blue">';
                        $t_cl_dt = date('d-m-Y',strtotime($row10["tentative_cl_dt"]));
                    }
                    if ($row_s['side'] == "D"){
                        $head1.='<b><font color="red">';
                        $t_cl_dt = "";
                    }

                    $head1.=$row_s['head'];
                    if ($row_s['head_content'] != "")
                        $head1.=' [' . $row_s['head_content'] . ']';
                    $head1.='</font></b><br>';
                    $txt_value.=$row_s['r_head'] . "|" . $row_s['head_content'] . "^^";
                }
            }
            $heardt_updtby = ""; $heardt_stagename = ""; $heardt_purpose = ""; $heardt_mainhead ="";
            $heardt_board_type = "";
            $results_hu = rop_verify_fun13($row10["diary_no"],$tdt1);
            if(count($results_hu)>0){
            $ro_hu= $results_hu[0];
            $heardt_updtby = $ro_hu['name'].' - '.$ro_hu['empid'];
            $heardt_stagename = $ro_hu['stagename'];
            
            $heardt_purpose = '<span style="color:green;">'.$ro_hu['purpose'].'</span>';
            if($ro_hu['mainhead'] == 'F'){
                $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Regular</span>';
            }
            if($ro_hu['mainhead'] == 'M'){
                $heardt_mainhead = '<br><span style="font-weight:bold; color: #1b6d85;">Misc.</span>';
            }
            if($ro_hu['board_type'] == 'J'){
                $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Court</span>';
            }
        if($ro_hu['board_type'] == 'C'){
            $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Chamber</span>';
        }
        if($ro_hu['board_type'] == 'R'){
            $heardt_board_type = ', <span style="font-weight:bold; color: brown;">Registrar</span>';
        }
    }

 
            echo '<tr id="'.$verify_str.'" height="25px" valign="top"><td>' . $chk_slno . '</td><td><b>' . $caseno . '</br>'.$t_fil_no. '</b></td>';
            echo '<td>' . $row10["pet_name"] . '&nbsp;&nbsp;Vs.<br>' . $row10["res_name"] .  '</td><td>'.$rop_view.'<br>CO : '.$head1.'</td><td>'.$heardt_purpose.$heardt_mainhead.$heardt_board_type.'<br>'.$heardt_stagename.'</td><td>'.$heardt_updtby.'</td><td>'.$t_cl_dt.'</td>';

?>

    <td>
        <?php
        echo $row10['remarks_by_monitoring'];
           ?>
        <br/>
        <span style="font-weight:bold; color: brown;">Verified By: <?=$row10["verified_by"]?></span>
        <span style="font-weight:bold; color: brown;">Verified On: <?=$row10["verified_on"]?></span>
    </td>
<?php

echo '</tr>';

/////RECEIVED INFO
            $sql_rd = " SELECT * FROM (
(
SELECT diary_no filno, DATE_FORMAT(entry_date,'%d-%m-%Y %h:%i %p') ed, entry_by eb, 'R' sd, entry_date dtd11
FROM case_receive
WHERE diary_no = '" . $row10["diary_no"] . "' or linked_c LIKE '%" . $row10["diary_no"] . "%'
)
UNION 
(
SELECT diary_no filno, DATE_FORMAT(entry_date,'%d-%m-%Y %h:%i %p') ed, entry_by eb, 'D' sd, entry_date dtd11
FROM case_dispatch
WHERE diary_no = '" . $row10["diary_no"] . "' or linked_c LIKE '%" . $row10["diary_no"] . "%'
)
) c
ORDER BY DATE(c.dtd11) DESC, TIME(c.dtd11) DESC LIMIT 1";
            $bgcolor = "";
/////RECEIVED INFO
//IAN
//14        
            $clnochk ='';
            $results_ian = rop_verify_fun15($row10["diary_no"]);
            $t_iaval = "";
            foreach ($results_ian as $row_ian) {
                if ($row_ian["iastat"] == "P" or ( $row_ian["iastat"] == "D" and $row_ian["lstmdf"] == $dt_t1)) {
                    $t_iaval.=$row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                }
            }
//IAN
            if ($row10["brd_slno"] == $clnochk)
                $clno = "<br/>&nbsp;" . $row1["brd_slno"] . "." . ++$con_no;

//CHECK FOR FINAL ADMITTED CASE START   
            $for_final = "";
            if ($mf == 'M') {
                 $results_final = rop_verify_fun14($row10["diary_no"]);
                if (count($results_final) > 0) {
                    $row_final = $results_final[0];
                    $for_final = " [<font color=red>ADMITTED ON : " . $row_final["cldate"] . "</font>]";
                }
            }
//CHECK FOR FINAL ADMITTED CASE END
            echo '</tbody>';
        }
    }
    echo '</table>';
   
} else {
    echo '<table border="0" width="100%"><tr align="center"><td><font color="#CC0000"><b><br><br>No Records Found</b></font></td></tr></table>';
}
?><br><br><br><br><br>
</div>
</div>
<?php

if ($msg != "")
    $msg = "Pass over cases : " . substr($msg, 0, -2);
$results_new = rop_verify_fun16($jcourt,$dtd);
$temp_msg = "";
if (count($results_new) > 0) {
    $row_new = $results_new;
    $temp_msg = $row_new[0]['msg'];
    if ($msg != "")
        $msg.="; Message : " . $row_new[0]['msg'];
    else
        $msg = "Message : " . $row_new[0]['msg'];
}

?>
<!-- <input type="hidden" name="paps" id="paps" value="<?php //print $t_paps; ?>"> -->
<input type="hidden" name="msg2" id="msg2" value="<?php echo $jcourt . '::' . $tdt1 . ':::'; ?>">
<input type="hidden" name="msg1" id="msg1" value="<?php echo $msg; ?>">
