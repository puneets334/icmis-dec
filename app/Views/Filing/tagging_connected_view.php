<input type="hidden" name="h_dno1" id="h_dno1" value="<?php echo $dno_conn; ?>" />
<input type="hidden" name="h_dyr1" id="h_dyr1" value="<?php echo $dyr_conn; ?>" />

<?php
$list_before = $list_before_judge = $list_not_before = $list_not_before_judge = '';
if(!empty($connected_data)):
    foreach($connected_data as $row1){

        $check_already_conn = "N";
        if(!empty($row1['diary_details']))
        {
                    $row_m = $row1['diary_details'];

                    $reslt_validate_verification = validate_verification($row_m['diary_no']);
                    $p = $row_m["pet_name"];
                    $r = $row_m["res_name"];
                    $padv = $row_m['pet_adv'];
                    $radv = $row_m['res_adv'];
                    $status = $row_m['c_status'];
                    $lastorder = $row_m['lastorder'];
                    $isconn = $row_m["ccdet"];
                    $connto = $row_m["connto"];
                    $reg_no_display_main = $row_m["reg_no_display"];
                    $shead = "";
                    $benchmain = "";
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
                    $padvname = $radvname = "";
                    if(!empty($row1['party_details_diary'])){
                        foreach($row1['party_details_diary'] as $row_pty){
                            if ($row_pty["pet_res"] == "P")
                                if ($p == "")
                                    $p .= $row_pty["pn"];
                                else
                                    $p .= ", " . $row_pty["pn"];

                            if ($row_pty["pet_res"] == "R")
                                if ($r == "")
                                    $r .= $row_pty["pn"];
                                else
                                    $r .= ", " . $row_pty["pn"];
                        }
                    }

                    if(!empty($row1['advocate_details'])){
                        foreach($row1['advocate_details'] as $row_advp){
                            $tmp_advname =  "<p>&nbsp;&nbsp;";
                            $t_adv=$row_advp['name'];
                            if($row_advp['isdead']=='Y')
                                $t_adv="<font color=red>".$t_adv." (Dead / Retired / Elevated) </font>";
                            $t_adv.=" [".$row_advp['enroll_no']."/".$row_advp['eyear']."]";
                            $tmp_advname = $tmp_advname . $t_adv;

                            $tmp_advname = $tmp_advname . "</p>";

                            if ($row_advp['pet_res'] == "P")
                                $padvname .= $tmp_advname;
                            if ($row_advp['pet_res'] == "R")
                                $radvname .= $tmp_advname;
                        }
                    }

if ($status =='D') {?>
    <center><b>
            <font color='red' style='font-size:16px;'>Disposed off matter cannot be Connected</font>
        </b></center>
<?php  }else{
if ($reslt_validate_verification > 0 && $section != 19) {?>
    <center><b>
            <font color='red' style='font-size:16px;'>Verification Pending From IB Section</font>
        </b></center>
<?php }else{?>
<div align="center" style="background-color:mintcream; border: 1px solid #5AFFAC;"><br>
                <table bgcolor="#FBFFFD" class="table" width="90%" border="0" cellpadding="3" align="center">
                    <tbody>
                                <tr>
                                    <th style="width: 20%;">Diary No</th>
                                    <td><?php echo substr($row_m['diary_no'], 0, -4).'/'.substr($row_m['diary_no'], -4);?></td>
                                </tr>
                                <?php if(!empty($row_m['reg_no_display'])):?>
                                <tr>
                                    <th style="width: 20%;">Case No.</th>
                                    <td><font color="blue"><?php echo $row_m['reg_no_display'];?></font> (Reg. Dt. <?php echo $row_m['active_fil_dt'];?>)</td>
                                </tr>
                                <?php endif;?>
                                <tr>
                                    <th style="width: 20%;">Petitioner</th>
                                    <td><?php echo $p;?></td>
                                </tr>
                                <tr>
                                    <th>Respondant</th>
                                    <td><?php echo $r;?></td>
                                </tr>
                                <tr>
                                    <th>Petitioner Advocate(s)</th>
                                    <td><?php echo $padvname; ?></td>
                                </tr>
                                <tr>
                                    <th>Respondant Advocate(s)</th>
                                    <td><?php echo $radvname; ?></td>
                                </tr>
                                <tr>
                                    <th>Case Category</th>
                                    <td><?php echo $old_category_name?></td>
                                </tr>
                                <tr>
                                    <th>Bench</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><font color="blue"><?echo $cstatus;?></font></td>
                                </tr>
                                <tr>
                                    <th>Last Order</th>
                                    <td>
                                        <font color="blue"><?php echo $lastorder; ?></font>
                                    </td>
                                </tr>
                                <?php if ($row_m["c_status"] != "D") {

                                    if(!empty($row1['not_before'])){
                                        foreach($row1['not_before'] as $not_before){
                                            if($not_before['notbef'] == 'B'){
                                                $list_before = 'B';
                                                $list_before_judge = $not_before['jn'];
                                            }elseif($not_before['notbef'] == 'N'){
                                                $list_not_before = 'N';
                                                $list_not_before_judge = $not_before['jn'];
                                            }
                                        }?>
                                        <?php if(!empty($list_before) && $list_before == 'B'):?>
                                            <tr>
                                                <th>List Before</th>
                                                <td>
                                                    <font color="green"><?php echo $list_before_judge; ?></font>
                                                </td>
                                            </tr>
                                        <?php endif;?>
                                        <?php if(!empty($list_not_before) && $list_not_before == 'N'):?>
                                            <tr>
                                                <th>Not List Before</th>
                                                <td>
                                                    <font color="red"><?php echo $list_not_before_judge; ?></font>
                                                </td>
                                            </tr>
                                        <?php endif;?>
                                    <?php  }
                                }?>

                                <?php $list_after = "";  if(!empty($row1['heardt'])){
                                    foreach($row1['heardt'] as $row_listed_after){

                                        date_default_timezone_set('GMT');
                                        $temp = strtotime("+5 hours 30 minutes");
                                        if ((strtotime($row_listed_after['nd1']) > strtotime(date('Y-m-d'))) or strtotime($row_listed_after['nd1']) == strtotime(date('Y-m-d')) and (strtotime("17:00:00") - strtotime(date("H:i:s", $temp))) > 0) {
                                            $list_after = "YES";
                                            echo "<tr><th>List On</th><td><font style='font-weight:bold;color:red;'><span class='blink_me' style='font-size:20px;'>Case is listed on <font color='blue' style='font-size:20px;'>" . $row_listed_after["next_dt"] . "</font> before <font color='blue' style='font-size:20px;'>[" . stripcslashes(get_judges($row_listed_after["judges"])) . "]</font>. PLEASE DO NOT ADD/REMOVE CONNECTED / LINKED CASES.<span class='blink_me'></font></td></tr>";
                                        } ?>
                                    <?php }
                                    }
                                ?>
                                <?php   if ($isconn == 'Y') {
                                    if(!empty($row1['conct_matters'])){

                                        ?>
                                        <tr valign="top"><th>Connected / LINKED </th>
                                            <td width="100%" nowrap="">
                                                <div width="100%" nowrap="">
                                                    <?php
                                                    $connto1 = substr($connto, 0, -4)."/".substr($connto, -4);

                                                    $conct_matter_diary_reg_no_display = (!empty($row1['conct_matter_diary']) && !empty($row1['conct_matter_diary']['reg_no_display'])) ?  $row1['conct_matter_diary']['reg_no_display'] : '';

                                                    $connto = "<font color='red'>Main Case </font>: DN - " . substr($connto, 0, -4)."/".substr($connto, -4) . "&nbsp;&nbsp;&nbsp;&nbsp;[<font color='#043fff'>" . $conct_matter_diary_reg_no_display . "</font>] ";echo $connto;?>
                                                    <?php  foreach($row1['conct_matters'] as $row_oc){
                                                        $connected_d = $row_oc['diary_no'];
                                                        $reg_no_display =  $row_oc['reg_no_display'];
                                                        $t_conn_type = (!empty($row_oc["llist"])) ? explode('-', $row_oc["llist"]) : '';
                                                        $conn_type = (!empty($t_conn_type)) ? $t_conn_type[1] : '';
                                                        if($conn_type == 'L'){
                                                            $t_c_l = "Linked Case";
                                                        }elseif($conn_type == 'C'){
                                                            $t_c_l = "Connected Case";
                                                        }else{
                                                            $t_c_l = '';
                                                        }
                                                        if ($diaryno_conn == $row_oc["diary_no"])
                                                            $check_already_conn = "Y";

                                                        ?>
                                                        <?php $connto = "<br><font color='blue'>" . $t_c_l . "</font> : " . substr($connected_d, 0, -4)."/".substr($connected_d, -4) . "&nbsp;&nbsp;&nbsp;&nbsp;[<font color='#043fff'>" . $reg_no_display . "</font>] "; echo $connto;?>
                                                    <?php }?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if ($check_already_conn == "Y") {
                                        echo "<tr><th>Note </th><td><b><font size='14px' color='red'>The Diary No. " .  substr($diaryno_conn, 0, -4)."/".substr($diaryno_conn, -4)  . " is already Connected to Main Case Diary No. : " . $connto1 . ". Please enter  Main Case Diary No. </font></b></td></tr>";
                                        }?>



                                    <?php }     
                                }?>
                    </tbody>
                </table>
                <?php 
                
                if(!empty($row1['connected_matters'])){
                    ?>
                            <table class="table" width="98%">
                                <tbody>
                                    <tr>
                                        <td align="center" colspan="8"><font color="red"><b>CONNECTED CASES / LINKED CASES</b></font></td>
                                    </tr>
                                    <tr bgcolor="#F4F5F5">
                                        <td align="center" width="50px"><input type="checkbox" name="all" id="all" value=""></td>
                                        <td width="5%"><b>S. NO.</b></td>
                                        <td><b>Diary No.</b></td><td><b>Case No.</b></td><td><b>Petitioner vs. Respondant</b></td>
                                        <td><b>Category</b></td><td align="center"><b>Status</b></td><td><b>Before/Not Before</b></td>
                                        <td align="center"><b>List</b></td><td align="center"><b>Linked/ connected</b></td>
                                    </tr>
                                    <?php
                                    $conncntr = 0;
                                    $conncntr_p = 0;
                                    $conncntr_d = 0;
                                    $sr_no = 0;
                                    foreach($row1['connected_matters'] as $row_conn)
                                    {
                                        ++$sr_no;
                                        $t_bfnbf = "";
                                        $conncntr++;
                                        $connected_d = $row_conn['diary_no'];
                                        $reg_no_display =  $row_conn['reg_no_display'];
                                        $conn_type = $row_conn['conn_type'];
                                        if($row_conn['c_status'] == 'P'){
                                            $conncntr_p++;
                                            $conncntr_d_text= 'blue';
                                        }else if ($row_conn["c_status"] == "D") {
                                            $conncntr_d = 0;
                                            $conncntr_d_text= 'red';
                                        }

                                        $return_bfnbf1 = getBeforeNotBeforeData($row_conn["diary_no"]);
                                        $t_return_bfnbf1 = explode('^|^', $return_bfnbf1);
                                        if ($t_return_bfnbf1[0] != "")
                                            $t_bfnbf .= "<b>BEFORE</b>: <font color=green>" . $t_return_bfnbf1[0] . "</font>";
                                        if ($t_return_bfnbf1[1] != "")
                                            $t_bfnbf .= "<b>NOT BEFORE</b>: <font color=green>" . $t_return_bfnbf1[1] . "</font>";


                                        if ($row_conn["list"] == "Y")
                                            $chked = "checked";
                                        else
                                            $chked = "checked";


                                        ?>
                                        <tr>
                                            <td align="center">
                                                <input type='checkbox' name="ccchkadd<?php echo $conncntr?>" id='ccchkadd<?php echo $conncntr?>' value="<?php echo $connected_d;?>" <?php echo $chked;?> disabled=disabled>
                                            </td>
                                            <td><b><?php echo $sr_no;?></b></td>
                                            <td><b><span id="cn<?php echo $connected_d;?>"><?=substr($connected_d, 0, -4)."/".substr($connected_d, -4) ?></span></b></td>
                                            <td><font color="#043fff" style=" white-space: nowrap;"><?php echo $reg_no_display;?></font>&nbsp;&nbsp;&nbsp;<?php echo '<br>( Reg. Dt. '.$row_conn['active_fil_dt'].')'?>&nbsp;&nbsp;<br><br></td>
                                            <td><?=$row_conn['pet_name']?> vs.<br><?=$row_conn['res_name']?></td>
                                            <td><?php echo get_mul_category($connected_d)?></td>
                                            <td align="center"><b><font color="<?=$conncntr_d_text;?>"><?=$row_conn['c_status']?></font></b></td>
                                            <td><?php echo $t_bfnbf;?></td>
                                            <td align="center"><b><?=$row_conn["list"];?></b></td>
                                            <td align="center"><b><?=$row_conn["conn_type"];?></b></td>
                                        </tr>
                                    <?php }?>

                                </tbody>
                            </table>
                    <br><br>

                <?php } ?>
                <?php if($check_already_conn == 'N'){?>
                        <table class="table">
                            <tbody>
                                <tr align="center">
                                    <td>&nbsp;&nbsp;<button type="button" class="btn btn-success" name="addconn1" id="addconn1" onclick="save_rec(1,'L')">CONNECT/ LINK CASE</button</td>
                                </tr>
                            </tbody>
                        </table>
                <?php }?>
                <input type="hidden" name="fil_noadd" id="fil_noadd" value="<?php echo $diaryno_conn; ?>">
                <br>
                <br>
    <?php form_close();?>
    <?php }
        } 
    }
    else{ ?>

        <center><b>
                <font color='red' style='font-size:16px;'>Diary No. does not exist</font>
            </b></center>
    <?php
     } }
     endif;?>
    <!-- /.tab-content -->
</div>