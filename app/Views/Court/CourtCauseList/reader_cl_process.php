<?php 
$t_paps="";$jcourt=0;
$dt_t1='';
$conct="";
$conct1="";
$db = \Config\Database::connect();
 
$ucode = $_SESSION['login']['usercode'];		
$msg = "";
  
$crt = $_REQUEST['courtno'];
$court_text = $_REQUEST['court_text'];
$check_text='';
if($court_text!=''){
if (stripos($court_text, 'Registrar') !== false) {
    $check_text = 'Found';
}
else
{
$check_text='';  
}
}
$dtd = $_REQUEST['dtd'];
$jcd = $_REQUEST['aw1'];
$mf = $_REQUEST['mf'];
$printFrm = 0;

$pr_mf = $mf;

$tdt1 = date('Y-m-d',strtotime($dtd));

// add after

$ttt = 0;
$msg = "";

// if ($crt != '') {

//     $sql_ro = $CourtCausesListModel->getRosterData($crt, $mf, $check_text, $tdt1);
    
//     $result = '';
//     foreach ($sql_ro as $res) {
//         if ($result == '')
//         $result .= $res['roster_id'];
//         else
//             $result .= "," . $res['roster_id'];
//     }
//     $whereStatus = "";
//     $r_status = '';
//     if ($r_status == 'A') {
//         $whereStatus = '';
//     } else if ($r_status == 'P') {
//         $whereStatus = " and m.c_status='P'";
//     } else if ($r_status == 'D') {
//         $whereStatus = " and m.c_status='D'";
//     }
//     $sql_t = $CourtCausesListModel->getCases($tdt1, $mf, $result, $whereStatus);
    
// }

// if($jcd!='')
// {
//     if($mf == 'M') 
//         $tmf='1';
//     else if($mf == 'F') 
//         $tmf='2';
//     else if($mf == 'L') 
//         $tmf='3';
//     else if($mf == 'S') 
//         $tmf='4';
     
//     $msg = "";

//     $sql_t = "";
//     $ttt = 0;

//   $sql_t = $CourtCausesListModel->getCasesjcd($tdt1, $mf, $jcd);
   
// }

$results10 = $sql_t;

$jc = "";
$chk_var = 0;
$not_avail = "";
if (!empty($results10)) {
    $chk_var = 1;$pjcourt=0;
    echo '<table  width="100%" border="0" cellpadding="0" cellspacing="0" >';
    foreach ($results10 as $row10) {
        $jcodes = "";
        $jcodes11 = "";
        $jnm = "";
        $sbdb = "";
        $sbdb1 = "";
        $jabr = "";
        //$jcourt = 0;
        $cstatus = $row10["c_status"];
            $jnm = $row10["judges"];
            $jcodes = $row10["judges"];
            $jcodes11 = $row10["judges"];
               $t_fil_no="";
               $board=$row10['board_type'];
        if($row10['ct1']!=''){
         
        $ct1 = $row10['ct1'];
        $res_ct_typ = is_data_from_table('master.casetype', " casecode=$ct1 and display='Y' ", 'short_description', $row = '')['short_description'];    
        if($row10['crf1']==$row10['crl1']){

            $t_fil_no= ''.$res_ct_typ." ".ltrim($row10['crf1'],'0').'/'.$row10['m_year'];
          }
          else{
            $t_fil_no= ''.$res_ct_typ." ".ltrim($row10['crf1'],'0')." - ".ltrim($row10['crl1'],'0').'/'.$row10['m_year'];
          }

         
 }
        if($row10['ct2']!=''){
            $check_for_regular_case="FOUND";
            
          $ct2 = $row10['ct2'];
          $res_ct_typ = is_data_from_table('master.casetype', " casecode=$ct2 and display='Y' ", 'short_description', $row = '')['short_description']; 
          if($row10['crf2']==$row10['crl2']){ 
            $t_fil_no= ''.$res_ct_typ." ".ltrim($row10['crf2'],'0').'/'.$row10['f_year']; 
          }
        else{ 
            $t_fil_no= ''.$res_ct_typ." ".ltrim($row10['crf2'],'0')." - ".ltrim($row10['crl2'],'0').'/'.$row10['f_year']; 
        }
    
 }

 if(trim($t_fil_no)==''){
     
    $casetype_id = $row10['casetype_id'];
    $row_12 = is_data_from_table('master.casetype', " casecode=$casetype_id and display='Y' ", 'short_description', $row = '');
            if (!empty($row_12)) {                
                $t_fil_no=$row_12['short_description'];
  
              }
 }    
 
        if ($jc != $jnm) {
             $conct="";
             $conct1="";
            $cntr = 0;
            $jc = $jnm;
            $stagec = "";
            $mf = "";
            $clno = 0;
            $chk_pslno = 0;
            $previous_brd_slno = 0;
            
 
$bench_from_roster="";
 
  
$rosterId = $row10["roster_id"];
$row_rstr = $CourtCausesListModel->getRosterDetails($tdt1, $rosterId);
if(!empty($row_rstr)){ 
  $jcourt =$row_rstr["courtno"];
  $bench_from_roster=" (".$row_rstr["bnch"].")";
    if($row_rstr["session"]!="Whole Day")
        $bench_from_roster.=" (".$row_rstr["session"].")";
    if(trim($row_rstr["frm_time"])!="")
        $bench_from_roster.=" (From ".$row_rstr["frm_time"].") ";
        
}

$ip_address=get_client_ip();
$court_ip_address="";

$row_ip = is_data_from_table('master.court_ip', " court_no=$jcourt and display='Y' ", 'ip_address', $row = '');
if(!empty($row_ip)) {                
    $court_ip_address=$row_ip['ip_address'];
}
  
 
if($jcourt!=$pjcourt)
echo '<tr><td colspan="5" align=right><button class="pdbutton btn btn-primary" type="button" name="btn-clear" value="' . $jcourt . ':' . $mf . ':' . $tdt1 . ':'.':' . ':0" onclick="insert_disp(this.value,\'' . 0 . '\',\''.$row10["judges"].'\',\''.$jabr.'\',\''.$sbdb1.'\');" >Clear Court No. '.$jcourt.' Display</button></td></tr>';   
echo $CourtCausesListModel->getDropNotes(date("Y-m-d", strtotime($tdt1)),$row10["mainhead"],$row10["roster_id"]);

            $pjcourt=$jcourt;
            $ttt = 1;
            $courtNoDisplay="";
            if($jcourt==21){
                $courtNoDisplay="R1";
            }
            elseif ($jcourt==22){
                $courtNoDisplay="R2";
            }
elseif($jcourt==31)
{
$courtNoDisplay="VC1";
}
elseif($jcourt==32)
{
$courtNoDisplay="VC2";
}
elseif($jcourt==33)
{
$courtNoDisplay="VC3";
}
elseif($jcourt==34)
{
$courtNoDisplay="VC4";
}
elseif($jcourt==35)
{
$courtNoDisplay="VC5";
}
            else{
                $courtNoDisplay=$jcourt;
            }


            echo '<thead><tr bgcolor="#d8e0ec" align="left"><th colspan="5" style="padding-left:10px;"><b>CAUSE LIST FOR <font color="red">' . date('l', strtotime($tdt1)) . ' ' . $dtd . '</font></b></th></tr>';
            echo '<tr bgcolor="#d8e0ec" align="left"><th colspan="5" style="padding-left:10px;"><b>BEFORE <font color="blue">' . stripslashes(get_judges($jnm)) . '</font>&nbsp;'.$bench_from_roster.'&nbsp;[COURT NO. : ' . $courtNoDisplay . ']</b></th></tr>';
            echo '<tr><th colspan="5"><hr></th></tr>';        
            echo '<tr bgcolor="#e5eaf2" align="left" height="20px"><th align="center" width="5%"></th><th width="5%">C.L.No.</th><th width="30%">Case No.</th><th width="30%">Petitioner Vs. Respondent</th><th width="30%">Advocates for Pet./Res.</th></tr>';
            echo '<tr><th colspan="5"><hr></th></tr></thead>';
        }
        
        $cntr += 1; {
            
            $tmp_caseno = $row10["diary_no"];
 
            $temp_rem = "";
            
            $diary_no = $row10["diary_no"];
            $row_r1 = is_data_from_table('case_remarks_multiple', " diary_no = '" . $diary_no . "' and cl_date='" . $tdt1 . "' and jcodes='".$jcodes11."' and remove=0 order by e_date DESC ", 'r_head', $row = '');
            if (!empty($row_r1)) {                
                $temp_rem = $row_r1['r_head'];
            }
            $caseno = $row10["case_no"]." / ".$row10["year"];

 

           $padv = explode(",", trim($row10['pet_adv_id']));
            $padv1 = "";
            echo '<tbody style="page-break-inside: avoid;">';
            for ($k = 0; $k < count($padv); ++$k) {
                if($padv[$k]!=0){
                if ($k == 0)
                    $padv1.= $CourtCausesListModel->get_advocates($padv[$k]);
                else
                    $padv1.= ", " . $CourtCausesListModel->get_advocates($padv[$k]);
            }
            }
            
            $radv = explode(",", trim($row10['res_adv_id']));
            $radv1 = "";
            for ($k = 0; $k < count($radv); ++$k) {
                if($radv[$k]!=0){
                if ($k == 0)
                    $radv1.= $CourtCausesListModel->get_advocates($radv[$k]);
                else
                    $radv1.= ", " . $CourtCausesListModel->get_advocates($radv[$k]);
            }
            }

 
            $reader_remark = "";
            $reader_disp_type = "";
            $reader_disp_date = "";
            $rdrsql = "SELECT * FROM (SELECT * FROM dispose WHERE diary_no='" . $row10["diary_no"] . "' and disp_dt='" . $tdt1 . "') a LEFT JOIN master.disposal b ON a.disp_type=b.dispcode and b.display='Y'";
            
            $results_rdr = $db->query($rdrsql);
            $row_rdr = $results_rdr->getRowArray();
            if (!empty($row_rdr)) {                
                $reader_disp_type = $row_rdr["dispname"];
                $reader_disp_date = $row_rdr["disp_dt"];
            }
 
 
            $paps_allotment = "";
            $paps_allotment_name = "";
           
            
            $result_paps = $CourtCausesListModel->getJoAlottmentPaps($row10["diary_no"], $tdt1);
            if (!empty($result_paps)) {
                foreach ($result_paps as $row_paps) {
                    $paps_allotment.=$row_paps["usercode"] . "^" . $row_paps["uname"] . "|";
                    $paps_allotment_name.=$row_paps["uname"] . ", ";
                }
                $paps_allotment_name = substr($paps_allotment_name, 0, -2);
            }
  
            $brdremark = "";
           
            $diary_no = $row10["diary_no"];
            $row_b = is_data_from_table('brdrem', " cast(diary_no as BIGINT) = $diary_no ", '*', $row = '');
            if (!empty($row_b)) {
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
                echo '<tr height="20px" valign="top"><td colspan="5" align="center"><u><b>' . $t_mf . '</b></u></td></tr>';
            }
            if ($clno != $row10["clno"]) {
                $clno = $row10["clno"];
                echo '<tr height="25px" valign="top"><td></td><td align="left" colspan="4"><font style="font-weight:bold;color:green; font-size:16px;"><u><span class="blink_me">SESSION NO : ' . $clno . '</span></u></font></td></tr>';
            }
            
            $t_stagename = "";
            if ($mf == "F") {
                $t_stage = "";                
              
                $subhead = $row10["subhead"];
                $row1_s = is_data_from_table('master.subheading', " stagecode = $subhead and display='Y' ", '*', $row = '');
                if (!empty($row1_s)) {
                   
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
            else {                
                 $t_stagename = '';
            }

            if ($stagec != $t_stagename) {
                $stagec = $t_stagename;
                echo '<tr height="20px" valign="top"><td></td><td colspan="4"><b>' . $stagec . '</b></td></tr>';
            }
            if ($previous_brd_slno == $row10["brd_slno"]) {
                $t_cntr++;
                $conct.=$tmp_caseno.",";
            } else {
                
                $t_cntr = 0;
                $conct="";
                $conct1=$tmp_caseno;
            }
            $previous_brd_slno = $row10["brd_slno"];  

            $row_r1 = is_data_from_table('showlcd', " court = $jcourt and cl_dt = '$tdt1'  ", ' mf,clno ', $row = '');
             
            if (!empty($row_r1)) {               
                if ($mf == $row_r1['mf'] and $row10["brd_slno"] == $row_r1['clno'])
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

            if ($chk_slno == $chk_pslno and $chk_pslno!='0') {
                echo '<tr height="20px" valign="top"><td></td><td></td><td colspan="3"><font color=red>CONNECTED CASE</font></td></tr>';
                $chk_slno = $previous_brd_slno . '.' . $t_cntr;
            }
            $t_enable = "";


$find_150151="";
$row_150151 = is_data_from_table('case_remarks_multiple', " diary_no = '" . $tmp_caseno . "' and cl_date<'" . $tdt1 . "' and remove=0 and r_head IN ('150','151') order by cl_date DESC  ", ' r_head ', $row = '');
if (!empty($row_150151)) {  
  $find_150151=$row_150151['r_head'];   
}

$result_connected = $CourtCausesListModel->getConnectedCases($tmp_caseno);
$connected='';
if(!empty($result_connected))
{
  foreach($result_connected as $row_connected)
  {
      $connected.=$row_connected['num'].",";
  }
}

$con = '';
date_default_timezone_set('GMT');
$temp= strtotime("+5 hours 30 minutes");
if(( (strtotime("16:30:00") - strtotime(date("H:i:s",$temp))) > 0) or $row10['board_type_mb']!='J')
{

if($cstatus=="D" && $reader_disp_date!="" && strtotime($reader_disp_date) < strtotime($tdt1))
{
   $con = "<input class='pdbuttonD' type='button' name='pb" . $tmp_caseno . "' disabled=disabled >&nbsp;";
}

else {
$con = "<input class='pdbutton' type='button' name='pb" . $tmp_caseno . "' onclick='call_div(\"" . $chk_slno . "\",\"" . $tmp_caseno . "\",this,1,\"".$row10["subhead"]."\",\"".$row10['board_type']."\",\"".$connected."\")' value='P' " . $t_enable .">&nbsp;";
}

$t_judges = explode(',',$row10["judges"]);
            if(in_array(514, $t_judges) or in_array(500, $t_judges) or in_array(550, $t_judges))
            $t_enable = " disabled=disabled ";
            $con.=  "<input class='pdbutton' type='button' name='db" . $tmp_caseno . "' onclick='call_div(\"" . $chk_slno . "\",\"" . $tmp_caseno . "\",this,2,\"".$row10["subhead"]."\",\"".$row10['board_type']."\",\"".$connected."\")' value='D' " . $t_enable .">";
}

            echo '<tr height="25px" valign="top"><td align="center"></td><td><span id="cln' . $chk_slno . '">' . $chk_slno . '</span><input type="hidden" name="brd' . $tmp_caseno . '" id="brd' . $tmp_caseno . '" value=' . $row10["brd_slno"] . '><input type="hidden" name="rfinal' . $tmp_caseno . '" id="rfinal' . $tmp_caseno . '" value=' . $find_150151 . '></td><td><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td>' . '</td><td align="left">';//$con1

                if($court_ip_address==$ip_address){ 
               echo '<input type="radio" name="rbtn" style="background-color: black !important;" value="' . $jcourt . ':' . $mf . ':' . $tdt1 . ':' . str_replace(" - ", " ", $caseno) . ':' . str_replace(":", "&nbsp;",str_replace(" & ", " and ", $row10["pet_name"] . ' Vs ' . $row10["res_name"])) . ':' . $row10["brd_slno"] . '" onclick="insert_disp(this.value,\'' . $tmp_caseno . '\',\''.$row10["judges"].'\',\''.$jabr.'\',\''.$sbdb1.'\');" ' . $chkd . '/>';

            }
            $t_fil_no1="";            

                $rs_lct = $CourtCausesListModel->getLowerCourtDetails($row10['diary_no']);
                if(!empty($rs_lct)){
                    $t_fil_no1 .= "<br/>";
                    foreach($rs_lct as $ro_lct){
                        $t_fil_no1 .= " IN ".$ro_lct['type_sname']." - ".$ro_lct['lct_caseno']."/".$ro_lct['lct_caseyear'].", ";
                    }                    
                }    
                $drop_note = '';        
            echo '</td><td><b><span id="cs' . $tmp_caseno . '">' . $caseno . '</br>'.$t_fil_no . $t_fil_no1.'</span></b></td><td width="180px">' . $con . '</td></tr>' . $drop_note . '</table></td><td><span id="pn' . $tmp_caseno . '" style="background-color:#F0E9F9;">' . $row10["pet_name"] . '</span></td><td><span id="pad' . $tmp_caseno . '" style="background-color:#F0E9F9;">' . $padv1 . '</span><span id="jcodes' . $tmp_caseno . '" style="display:none;">' . $jcodes . '</span><span id="mainhead' . $tmp_caseno . '" style="display:none;">' . $mf . '</span><span id="paps' . $tmp_caseno . '" style="display:none;">' . $paps_allotment . '</span></td></tr>';
            $chk_pslno = $row10["brd_slno"];
             
             $dt_t1 = date('Y-m-d',strtotime($dtd));  
            
             $results_s =  $CourtCausesListModel->getCaseRemarks($tmp_caseno, $dt_t1, $jcodes11); 
 
            $head1 = "";
            $txt_value = "";
            if (!empty($results_s)) {
                foreach ($results_s as $row_s) {
                  
                    if ($row_s['side'] == "P")
                        $head1.='<b><font color="blue">';
                    if ($row_s['side'] == "D")
                        $head1.='<b><font color="red">';

                    $head1.=$row_s['head'];
                    if ($row_s['head_content'] != "")
                        $head1.=' [' . $row_s['head_content'] . ']';
                    $head1.='</font></b><br>';
                    $txt_value.=$row_s['r_head'] . "|" . $row_s['head_content'] . "^^";
                }
            }
 
         
            $bgcolor = "";
 
 
           $diary_no = $row10["diary_no"];
           $results_ian = $CourtCausesListModel->getDocumentDetails($diary_no);
            $t_iaval = "";
            if(!empty($results_ian))
            {
            foreach ($results_ian as $row_ian) {
                if ($row_ian["iastat"] == "P" or ( $row_ian["iastat"] == "D" and $row_ian["lstmdf"] == $dt_t1)) {
                    $t_iaval.=$row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                }
            }
          }
//IAN

            echo '<tr height="25px" valign="top"><td ' . $bgcolor . '></td><td align="left" rowspan="2" colspan="2" ' . $bgcolor . '><span id="cr_span' . $tmp_caseno . '">';
            echo $head1;
            echo '</span><span id="paps_span' . $tmp_caseno . '" style="color:#009900;font-weight:bold;">' . $paps_allotment_name . '</span><input type="hidden" name="caseval' . $tmp_caseno . '" id="caseval' . $tmp_caseno . '" value="' . $txt_value . '"><input type="hidden" name="ian' . $tmp_caseno . '" id="ian' . $tmp_caseno . '" value="' . $t_iaval . '">';
            echo '</td><td ' . $bgcolor . '>Vs.&nbsp;&nbsp;<span id="rn' . $tmp_caseno . '" style="background-color:#F9EBEB;">' . $row10["res_name"] . '</span></td><td ' . $bgcolor . '><span id="rad' . $tmp_caseno . '" style="background-color:#F9EBEB;">' . $radv1 . '</span></td></tr>';
  
            $for_final = "";
            if ($mf == 'M') {                
                $row_final = $CourtCausesListModel->getFinalDetails($row10["diary_no"]);                
                if (!empty($row_final)) {                     
                    $for_final = " [<font color=red>ADMITTED ON : " . $row_final["cldate"] . "</font>]";
                }
            }
 
             if ($mf != 'F' )
            echo '<tr height="25px" valign="top"><td ' . $bgcolor . '></td><td colspan="2" align="justify" ' . $bgcolor . '>' . $brdremark . $for_final . '</td></tr>';
            echo '<tr height="5px"><td colspan="5"><hr></td></tr></tbody>';
        }
    }
                    if($conct!='')
                echo "<input type='hidden' name='conc".$conct1."' id='conc".$conct1."' value='".$conct."'/>";
 
    echo '</table>';
} else
    echo '<table border="0" width="100%"><tr align="center"><td><font color="#CC0000"><b>No Records Found</b></font></td></tr></table>';
?> 
</div>
</div>
<?php

$dt_t1 = date('Y-m-d',strtotime($dtd));  
if ($msg != "")
    $msg = "Pass over cases : " . substr($msg, 0, -2);
 
$row_new = is_data_from_table('showlcd'," court='$jcourt'   and cl_dt='$dt_t1' ",'msg','');
$temp_msg = "";
if (!empty($row_new)) {
     
    $temp_msg = $row_new['msg'];
    if ($msg != "")
        $msg.="; Message : " . $row_new['msg'];
    else
        $msg = "Message : " . $row_new['msg'];
}
//FOR PAPS ALOTTMENT
 
?>
<input type="hidden" name="paps" id="paps" value="<?php print $t_paps; ?>">
<input type="hidden" name="msg2" id="msg2" value="<?php print $jcourt . '::' . $tdt1 . ':::'; ?>">
<input type="hidden" name="msg1" id="msg1" value="<?php print $msg; ?>">
<?php die;