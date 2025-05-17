<?=view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-title">Court >> Court Master (NSH) >> Court Master Cause List >> Cause List</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                
                            

                                    <style>
                                    
                                    #overlay {
                                        background-color: #000;
                                        opacity: 0.7;
                                        filter:alpha(opacity=70);
                                        position: fixed;
                                        top: 0px;
                                        left: 0px;
                                        width: 100%;
                                        height: 100%;
                                    }
                                    </style>
                                    <?php
                                    $db = \Config\Database::connect();
                                    $holiday_dates[]="";
                                    $current_year=date('Y');
                                    $next_year=$current_year+1;
                                    $t=rand(2,'0123456789');
                                    ?>
                                    
                                    <script type="text/javascript" src="<?php echo base_url();?>/courtMaster/reader_cl.js?version= <?php echo $t; ?>" ></script>
                                    
                                    <form method="post" name="frm" id="frm" action="<?= site_url(uri_string()) ?>">
                                    <?= csrf_field() ?>
                                        <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d');?>"/>
                                    <div id="dv_content1"   >
                                        <div style="text-align: center">
                                            <?php
                                    $file_list = "";
                                    $cntr = 0;
                                    $chk_slno = 0;
                                    $chk_pslno = 0;
                                    $temp_msg="";
                                    
                                    $ucode = $_SESSION['login']['usercode'];;
                                     
                                    ?>
                                            <style>
                                                .blink_me {
                                                    -webkit-animation-name: blinker;
                                                    -webkit-animation-duration: 1.5s;
                                                    -webkit-animation-timing-function: linear;
                                                    -webkit-animation-iteration-count: infinite;

                                                    -moz-animation-name: blinker;
                                                    -moz-animation-duration: 1.5s;
                                                    -moz-animation-timing-function: linear;
                                                    -moz-animation-iteration-count: infinite;

                                                    animation-name: blinker;
                                                    animation-duration: 1.5s;
                                                    animation-timing-function: linear;
                                                    animation-iteration-count: infinite;
                                                }
                                                @-moz-keyframes blinker {
                                                    0% { opacity: 1.0; }
                                                    50% { opacity: 0.0; }
                                                    100% { opacity: 1.0; }
                                                }
                                                @-webkit-keyframes blinker {
                                                    0% { opacity: 1.0; }
                                                    50% { opacity: 0.0; }
                                                    100% { opacity: 1.0; }
                                                }
                                                @keyframes blinker {
                                                    0% { opacity: 1.0; }
                                                    50% { opacity: 0.0; }
                                                    100% { opacity: 1.0; }
                                                }
                                                
                                                .lblclass{font-size: 12pt;}
                                                 
                                                #newparty{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}
                                                #newparty1{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}
                                              /*  #r_box {position: relative; overflow:auto; height:75%; bottom:0; width:100%;  } */
                                                #newb { position: fixed; padding-left: 12px;padding-right: 12px; padding-top: 5px;padding-bottom: 5px;left: 50%; top: 1%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
                                                #newc { position: fixed; padding: 12px; left: 50%; top: -1%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
                                                #newa { position: fixed; padding: 12px; left: 50%; top: 1%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
                                                #mrq { color: black; text-shadow: grey 0.1em 0.1em 0.2em; font-size:13px; }
                                                #jodesg { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
                                                #joname { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
                                                table.mytable3 { width: 100%; }
                                                table.mytable { width: 100%;  -moz-box-shadow: 2px 2px 2px #ccc;  -webkit-box-shadow: 2px 2px 2px #ccc;  box-shadow: 2px 2px 2px #ccc;}
                                                table.mytable3 td { font-size: 12px; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; vertical-align: top; padding: 0px;  }
                                                table.mytable td { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
                                                table.mytable th { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
                                                form hr { color: #666666; background-color:#999999; height: 1px; width:100%; margin: 5px !important; }
                                                .newb123t td {padding: 1px;}
                                                #paps123p {max-height: 100px; overflow: auto;}
                                                th, td {padding: 0px;background: #fff !important;}
                                                .card .row b {text-wrap: inherit;}
                                            </style>
                                                <input type="hidden" name="caseno" id="caseno">
                                                <input type="hidden" name="t_cs" id="t_cs">
                                                <input type="hidden" name="uid" id="uid" value="<?php echo $ucode; ?>" >
                                                <input type="hidden" name="sid" id="sid" value="" >
                                                <input type="hidden" name="flnm" id="flnm" value="" >
                                                <div id="rightcontainer" align="center">
                                                    <div id="s_box" align="center" >
                                                        <?php //added by Preeti on 03.08.2019 so that option to add remarks can be done till 4:30 PM only
                                                        date_default_timezone_set('GMT');
                                                        $temp= strtotime("+5 hours 30 minutes");
                                     
                                                       
                                                        ?> <!--Code added on 03.08.2019 ends   -->
                                                        <table border="0" height="35" width="100%">
                                                            <tr valign="middle" align="center">
                                                                <td>
                                                                <div class="row">
                                                                <div class="col-md-3"></div>
                                                                <div class="col-md-3">
                                                                    <label for="courtno">Court No.</label>
                                                                    <select name="courtno" id="courtno" class="form-control"  onchange="check_select(1);">
                                                                        <option value="">SELECT</option>
                                                                        <option value="1" selected>Hon'ble Court No.1</option>
                                                                        <option value="2">Hon'ble Court No.2</option>
                                                                        <option value="3">Hon'ble Court No.3</option>
                                                                        <option value="4">Hon'ble Court No.4</option>
                                                                        <option value="5">Hon'ble Court No.5</option>
                                                                        <option value="6">Hon'ble Court No.6</option>
                                                                        <option value="7">Hon'ble Court No.7</option>
                                                                        <option value="8">Hon'ble Court No.8</option>
                                                                        <option value="9">Hon'ble Court No.9</option>
                                                                        <option value="10">Hon'ble Court No.10</option>
                                                                        <option value="11">Hon'ble Court No.11</option>
                                                                        <option value="12">Hon'ble Court No.12</option>
                                                                        <option value="13">Hon'ble Court No.13</option>
                                                                        <option value="14">Hon'ble Court No.14</option>
                                                                        <option value="15">Hon'ble Court No.15</option>
                                                                        <option value="16">Hon'ble Court No.16</option>
                                                                        <option value="17">Hon'ble Court No.17</option>
                                                        <option value="31">Hon'ble Virtual Court No.1</option>
                                                        <option value="32">Hon'ble Virtual Court No.2</option>
                                                        <option value="33">Hon'ble Virtual Court No.3</option>
                                                        <option value="34">Hon'ble Virtual Court No.4</option>
                                                        <option value="35">Hon'ble Virtual Court No.5</option>
                                        <option value="36">Hon'ble Virtual Court No.6</option>
                                        <option value="37">Hon'ble Virtual Court No.7</option>
                                        <option value="38">Hon'ble Virtual Court No.8</option>
                                        <option value="39">Hon'ble Virtual Court No.9</option>
                                        <option value="40">Hon'ble Virtual Court No.10</option>
                                        <option value="41">Hon'ble Virtual Court No.11</option>
                                        <option value="42">Hon'ble Virtual Court No.12</option>
                                        <option value="43">Hon'ble Virtual Court No.13</option>
                                        <option value="44">Hon'ble Virtual Court No.14</option>
                                        <option value="45">Hon'ble Virtual Court No.15</option>
                                        <option value="46">Hon'ble Virtual Court No.16</option>
                                        <option value="47">Hon'ble Virtual Cou1234rt No.17</option>
                                                                        <option value="101">Chamber</option>
                                                                            <?php
                                                                            if (!empty($regular_judges)) {
                                                                                foreach ($regular_judges as $row_reg) {
                                                                                    if($row_reg["courtno"] == 21){
                                                                                        echo '<option value="' . $row_reg["courtno"] . '">Registrar Court No. 1</option>';
                                                                                    }
                                                                                    else if($row_reg["courtno"] == 22){
                                                                                        echo '<option value="' . $row_reg["courtno"] . '">Registrar Court No. 2</option>';
                                                                                    }
                                                                                    else if($row_reg["courtno"] == 61){
                                                                                        echo '<option value="' . $row_reg["courtno"] . '">Registrar Virtual Court No. 1</option>';
                                                                                    }
                                                                                    else if($row_reg["courtno"] == 62){
                                                                                        echo '<option value="' . $row_reg["courtno"] . '">Registrar Virtual Court No. 2</option>';
                                                                                    }

                                                                                }
                                                                            }
                                                                            ?>
                                                                        
                                                                            </select>&nbsp; 
                                                                            <?php
                                                                                if (isset($_POST["dtd"]) and $_POST["dtd"]!='')
                                                                                {
                                                                                    $dtd = date("d-m-Y",strtotime($_POST["dtd"]));
                                                                                }
                                                                                else
                                                                                {
                                                                                    $dtd = date("d-m-Y");
                                                                                }
                                                                                if (isset($_POST["hdate"]) and $_POST["hdate"]!='')
                                                                                    $hdate = date("d-m-Y",strtotime($_POST["hdate"]));
                                                                                else
                                                                                    $hdate = $dtd;
                                                                            ?>
                                                                    </div>
                                                                    <div class="col-md-3">                                                                    
                                                                        <label for="dtd">Cause List Date</label>                                                                    
                                                                        <input type="text" value="<?php print $dtd; ?>" name="dtd" id="dtd" size="10" class="form-control" >
                                                                    </div>
                                                                    <div class="col-md-3"></div>
                                                                </div>
                                                                </td>
                                    
                                                            </tr>
                                                            <tr valign="middle" align="center">
                                                                <td>
                                                                    <input type="radio" name="mf" id="mf" value="M" checked >Miscellaneous&nbsp;
                                                                    <input type="radio" name="mf" id="mf" value="F">Regular&nbsp;&nbsp;
                                                                    <input type="radio" name="mf" id="mf" value="L" >Lok Adalat&nbsp;
                                                                    <input type="radio" name="mf" id="mf" value="S">Mediation&nbsp;
                                                                    <span id="mf_box"></span>
                                                                    <input type="button" name="bt11" value="Submit" onclick='fsubmit();'>
                                                                </td></tr>
                                                            <tr>
                                                                <td align="center">
                                                                    <hr size="1">
                                                                    <input type="button" onClick="call_oral_mentioning();" alt="Oral Mentioning cases entry " id="oralMentioning" name="oralMentioning" value="Mentioning" >

                                                                    <input type="button" style="margin-left: 50px" onClick="call_mg();" alt="Message to Display Board" id="messagepost" name="messagepost" value="Message to Display Board" >
                                                                    <hr size="1">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                         
                                                    
                                                        <div id="intabdiv3" style="display:none;margin-left: 5%;margin-right: 5%; width:90%;">
                                                            <fieldset>
                                                                <legend><strong>Message for Display Board</strong></legend>
                                                            <table border="0">
                                                                <tr>
                                                                    <td valign="top"><b>Message</b></td>
                                                                    <td><textarea name="msgbox" id="msgbox" rows="1" cols="80"><?php echo $temp_msg; ?></textarea></td>
                                                                    <td align="center" valign="top">
                                                                        <input type="button"  name="bt1" id="bt1" value="Send" onClick="return save_r1(0)">
                                                                        <input type="button"  name="btnClearMsg" id="btnClearMsg" value="Clear Message" onClick="return save_r1(1)">
                                                                        <input type="button" name="bt2" id="bt2" value="Cancel"  onClick="call_mg();"></td>
                                                                </tr>
                                                                <tr><td></td><td align="center"><font color="red" size="10px">( Special Characters are not allowed except space and . )</font></td></tr>
                                                            </table>
                                                            </fieldset>
                                                        </div>
                                                        <div id="oralMentioningEntryDiv" style="display:none;margin-left: 5%;margin-right: 5%; width:90%;">
                                                            <fieldset>
                                                                <legend><strong>Oral Mentioning:</strong></legend>
                                                                <table border="0">
                                                                    <tr>
                                                                        <td valign="top"><b>Oral Mentioning Case No. : </b></td>
                                                                        <td><input type="text" name="mentioningNo" id="mentioningNo" maxlength="4" onkeypress="return isNumber(event)">
                                                                            <?php echo $temp_msg; ?></input></td>
                                                                        <td align="center" valign="top">
                                                                            <input type="button"  name="bt1" id="bt1" value="Send" onClick="return save_mentioning_cases_hearing_status(0)">
                                                                            <input type="button" name="btnClearMsg" id="btnClearMsg" value="Clear Mentioning" onClick="return save_mentioning_cases_hearing_status(1)">
                                                                            <input type="button" name="bt2" id="bt2" value="Cancel" onClick="call_oral_mentioning();"></td>
                                                                    </tr>
                                                                    <tr><td></td><td align="center"><font color="red" size="8px">(only Number(s) allowed)</font></td></tr>
                                                                </table>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div id="r_box" align="center" style="display: none;"></div>
                                                    <div id="hint" style="text-align: center"></div>
                                                    <div id="newb" style="overflow:auto; background-color: #fff;">
                                                        <div id="newb1" align="center" style="background-color: #898989">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                                <tr>
                                                                    <td align="center" width="400px">
                                                                        <input type='button' name='insert1' id='insert1' value="Save" onClick="return save_rec(1);">&nbsp;
                                                                        <input type="button" name="close1" id="close1" value="Close" onClick="return close_w(1)">&nbsp;
                                                                        <b><font color="#000">Pending Remark</font></b>
                                                                        <input type="hidden" name="tmp_casenop" id="tmp_casenop" value=""/>
                                                                        <input type="hidden" name="connected" id="connected" value=""/>
                                                                    </td>
                                                                    <td align="center" rowspan="2" style="background-color:#b7b7b7;">
                                                                        <b><font color="blue">List After ____Vacation  : To be updated in After Week Category corresponding to that week</font></b><br/>
                                                                        <b><font color="blue">List in / during  ..... : To be updated in week commencing category </font></b>
                                                                        <div id="paps123p"></div>
                                                                    </td>
                                                                </tr>
                                                                <tr style="background-color:#c1c1c1;">
                                                                    <td align="center" >
                                                                        <b><span id="psn" style="font-size:8pt;"> </span><span id="pend_head" style="font-size:8pt;"></span></b>
                                                                        </br>
                                                                        <b><span id="pend_head1" style="font-size:8pt;"></span></b>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div id="newb123" style="overflow:auto;">
                                                            <table class="newb123t table_tr_th_w_clr c_vertical_align table_pad" width="100%" border="1" style="border-collapse: collapse">
                                                                
                                                                <?php
                                        // $sql11 = "SELECT * FROM case_remarks_head WHERE side='P' AND display='Y'
                                        //     and sno not in (146,104,90,91,145,130,128,125,155,32,55,57,58,117,154,156,105,191,84,102,83,150,106,153,159,126,38,152,148,131,118,11,93,25,123,122,151,60,127,59,129,157,158,69)
                                        //   ORDER BY if(cat_head_id<1000,0,1), head";
                                        //                         $t11 = mysql_query($sql11);
                                                                $ttl_records = $case_remarks_heads->getNumRows();
                                                                if ($ttl_records > 0) {
                                                                    $snoo = 1;
                                                                    $chkcnt = 0;
                                                                    $chkhead = "";
                                                                    $hcntr=0;
                                                                    foreach ($case_remarks_heads->getResultArray() as $row11) {
                                                                    // if ($chkhead != $row11["category"]) {
                                                                            if ($snoo > (($ttl_records / 2)-2) and $chkcnt == 0) {
                                                                                $chkcnt++;
                                                                                echo "</table></td><td width='50%' style='vertical-align:top'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                            }
                                                                            if ($snoo == 1)
                                                                                echo "<tr valign='top'><td width='50%' style='vertical-align:top'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                            if($row11["cat_head_id"]==1000 and $hcntr==0)
                                                                            {
                                                                            ?>
                                                                            <tr><td colspan="2"><b><font color="#F9FBFD">NEGATIVE OFFICE REMARK</font></b></td></tr>
                                                                            <?php
                                                                            $hcntr++;
                                                                    }
                                                                        ?>
                                                                        <tr valign="top">
                                                                            <td>
                                                                                <input class="cls_chkp" type="checkbox" name="chkp<?php echo $row11['sno']; ?>" id="chkp<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>"/>
                                                                                <label class="lblclass" for="chkp<?php echo $row11['sno']; ?>"><?php echo $row11['head'];
                                                                if ($row11['sno'] == 21 or $row11['sno'] == 59 or $row11['sno'] == 70 or $row11['sno'] == 131)
                                                                    echo " (Date)";
                                                                        ?></label>
                                                                                <?php if ($row11['sno'] == 72) { ?><ruby>  <strong> (Type Proper Case No and Separate By ',')</strong> </ruby><?php } ?>
                                                                            </td>
                                                                            <td nowrap>
                                                                                <?php
                                                                                $int_array = array(23,25,53,54,68,122,123,133,144,149,181,204,205,190);//Array of sno of remark heads on which integer input required
                                                                                if(in_array($row11['sno'], $int_array)){
                                                                                    $check_var="NUM";
                                                                                    $check_var1="<font color=red style='font-size:x-small;'>(NUM)</font>";
                                                                                }
                                                                                else {
                                                                                    $check_var="ALPHANUM";
                                                                                    $check_var1="";
                                                                                }
                                                                                if ($row11['sno'] == 22 or $row11['sno'] == 26 or $row11['sno'] == 95 or $row11['sno'] == 142) {
                                                                                    ?>
                                                                                    <div id="hdremp<?php echo $row11['sno'] . '_div'; ?>"></div>
                                                                                    <input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" disabled="disabled" onkeypress="return remarks_input_validate(event,'<?php print $check_var;?>');"/>
                                                                                    <?php
                                                                                } else {
                                                                                    if ($row11['sno'] == 91){
                                                                                    ?>
                                                                                    <input type="button" name="partybutton" id="partybutton" value="PARTY" onclick="make_party_div();" disabled="disabled" />&nbsp;<input size=8 type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onkeypress="return remarks_input_validate(event,'<?php print $check_var;?>');"/>
                                                                                    <?php
                                                                                    }
                                                                                    elseif ($row11['sno'] == 149){
                                                                                    ?>
                                                                                    <input type="button" name="partybutton1" id="partybutton1" value="PARTY" onclick="make_party_div_popup();" disabled="disabled" />&nbsp;<input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value=""/>
                                                                                    <?php
                                                                                    }
                                                                                    else if($row11['sno'] == 190 or $row11['sno'] == 181 or $row11['sno'] == 204 or $row11['sno'] == 205){?>
                                    Day<input type="text" name="hdremp<?php echo $row11['sno']; ?>_1" id="hdremp<?php echo $row11['sno']; ?>_1" value=""  style="width:20px;" maxlength="2" onkeypress="return remarks_input_validate(event,'<?php print $check_var;?>');"/>
                                                                Week<input type="text" name="hdremp<?php echo $row11['sno']; ?>_2" id="hdremp<?php echo $row11['sno']; ?>_2" value=""  style="width:20px;" maxlength="2" onkeypress="return remarks_input_validate(event,'<?php print $check_var;?>');"/>
                                                                Mon.<input type="text" name="hdremp<?php echo $row11['sno']; ?>_3" id="hdremp<?php echo $row11['sno']; ?>_3" value=""  style="width:20px;" maxlength="1" onkeypress="return remarks_input_validate(event,'<?php print $check_var;?>');"/>
                                    <?php
                                                                                    }
                                                                                    else if($row11['sno'] == 180){
                                                                                        ?>
                                                                                        <select id="hdremp<?php echo $row11['sno']; ?>" name="hdremp<?php echo $row11['sno']; ?>">
                                                                                            
                                                                                            <option value="TUESDAY">Tuesday</option>
                                                                                        
                                                                                        </select>

                                                                                <?php }else if($row11['sno']==5){
                                                                                        ?>
                                                                                        <input type="hidden" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" /><?php print $check_var1;?>
                                                                                    <?php }else if($row11['sno']==186){
                                                                                        $sql_judge=$db->query("Select jcode,jname,first_name,sur_name from master.judge where is_retired='N' and display='Y' and jtype='J' order by judge_seniority");
                                                                                        
                                                                                        ?>
                                                                                        <select id="hdremp<?php echo $row11['sno']; ?>" name="hdremp<?php echo $row11['sno'];?>" multiple>
                                                                                            <option value="" disabled>Select one or more judge(s)</option>
                                                                                            <?php foreach($sql_judge->getResultArray() as $list) {?>
                                                                                                <option value="<?php echo 'HMJ '.$list['first_name'].' '.$list['sur_name'].'('.$list['jcode'].')';?>"><?php echo $list['jname'];?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                        <?php
                                                                                    }else {
                                                                                        ?>
                                                                                        <input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onkeypress="return remarks_input_validate(event, '<?php print $check_var;?>');"/><?php print $check_var1;?>

                                                                                        <?php
                                                                                    }

                                                                                    }
                                                                                ?>
                                                                                <input type="hidden" name="hdp<?php echo $row11['sno']; ?>" id="hdp<?php echo $row11['sno']; ?>"/>
                                                                                <input type="hidden" name="srvr" id="srvr" value="<?php echo date('Y'); ?>"/>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $snoo++;
                                                                    }// while end
                                                                }
                                                                ?>
                                                            </table></td></tr></table>
                                                        </div><div id="newb111" style="background-color:#b7b7b7;overflow:auto;border-collapse: collapse;"></div>
                                                    </div>
                                                    <div id="newc" style="overflow:auto;background-color: #fff;">
                                                        <div id="newc1" align="center" style="background-color: #898989">
                                                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td align="center" width="400px">
                                                                        <input type='button' name='insert3' id='insert3' value="Save" onClick="return save_rec(2);">&nbsp;
                                                                        <input type="button" name="close3" id="close3" value="Close" onClick="return close_w(2)">&nbsp;
                                                                        <b><font color="#000">Disposal Remark</font></b>
                                                                        <input type="hidden" name="tmp_casenod" id="tmp_casenod" value=""/>
                                                                        <input type="hidden" name="tmp_casenosub" id="tmp_casenosub" value=""/>
                                                                        <input type="hidden" name="connected" id="connected" value=""/>
                                                                    </td>
                                                                    <td align="center" rowspan="2" style="background-color:#b7b7b7;">
                                                                        <div id="paps123d"></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" style="background-color:#c1c1c1;">
                                                                        <b><span id="psn1" style="font-size:8pt;"> </span><span id="disp_head" style="font-size:8pt;"></span></b>
                                                                        <b><span id="disp_head1" style="font-size:8pt;"></span></b>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div id="newc123" style="overflow:auto;">
                                                            <table class="table_tr_th_w_clr c_vertical_align table_pad" width="100%" border="1" style="border-collapse: collapse">
                                                                <tr><td colspan="4" align="center"><b><font size="+1">Hearing Date : </font></b>&nbsp;
                                                                <input type="text" name="hdate" id="hdate" value="<?php echo $hdate?? ''; ?>" size="15" style="width:auto;" /><td></tr>
                                                                <?php


                                    //     $sql11 = "SELECT * FROM case_remarks_head WHERE side='D' AND display='Y'
                                    // and sno not in (33,42,144,163,164,40,167,29,37,31,78,73,134,168,43,41,166,169,161,160,44,173,45,187,165,34)
                                    // ORDER BY IF(sno IN (134,144,27,28,30,36),0,1), head";

                                    //                             $t11 = mysql_query($sql11);
                                                                if (!empty($case_remarks_head_for_d)) {
                                                                    $snoo = 1;
                                                                    $chkhead = "";
                                                                    foreach ($case_remarks_head_for_d as $row11) {
                                    
                                                                            ?>
                                    
                                                                            <?php
                                    
                                                                        ?>
                                                                        <tr>
                                                                            <td width="10%">&nbsp;</td>
                                                                            <td width="40%">
                                                                                <input class="cls_chkd" type="checkbox" name="chkd<?php echo $row11['sno']; ?>" id="chkd<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>"/>
                                                                                <label class="lblclass" for="chkd<?php echo $row11['sno']; ?>"><?php echo $row11['head']; ?></label>
                                                                            </td>
                                                                            <td width="40%">
                                                                                <?php
                                                                                if ($row11['sno'] == 144) {
                                                                                    ?>
                                                                                    <input type="text" class="form-control" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value=""  onBlur="textformate(<?php echo $row11['sno']; ?>);"/>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <input type="text" class="form-control" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value=""/>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <input type="hidden" name="hdd<?php echo $row11['sno']; ?>" id="hdd<?php echo $row11['sno']; ?>"/>
                                                                            </td>
                                                                            <td width="10%">&nbsp;</td>
                                                                        </tr>
                                                                        <?php
                                                                        $snoo++;
                                                                    }// while end
                                                                }
                                                                ?>
                                                            </table>
                                                        </div><div id="newc111" style="background-color:#b7b7b7;overflow:auto;border-collapse: collapse;"></div>
                                                    </div>
                                                    <div id="newa" style="max-height:450px; overflow:auto;">
                                                        <div id="newa1" align="center">
                                                            <table border="0" width="100%">
                                                                <tr>
                                                                    <td align="center" width="250px">
                                                                        <input type='button' name='insert4' id='insert4' value="Save" onClick="return save_rec1();">&nbsp;
                                                                        <input type="button" name="close4" id="close4" value="Close" onClick="return close_w(3)">
                                                                    </td>
                                                                    <td align="center">
                                                                        <b><font color="#000">Allotment for Judgement/Order writing</font></b>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div id="newa123" style="overflow:auto;"></div>
                                                    </div>
                                    <div id="newparty" style="display:none; max-height:300px; overflow:auto;"></div>
                                    <div id="newparty1" style="display:none; max-height:300px; overflow:auto;"></div>
                                                    <div id="fade"></div>
                                                </div>
                                        </div>
                                            </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
 $(document).ready(function() {
        $('.dtp,#dtd').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });                                                            
</script>

