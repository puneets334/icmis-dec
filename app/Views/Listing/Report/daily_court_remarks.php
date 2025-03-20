<style>
   #newb { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;}
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
            html, body { height: 100%; }
            body { position: relative; font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; }
            .lblclass{font-size: 12pt;}
            /*#s_box { width: 100%; background-color: #ADAEC0; border-top: 1px solid #fff; position: fixed; top: 0px; left: 0; right: 0; z-index: 0; }*/
            /*#messagepost { z-index: 9999; }*/
            #newparty{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}
            #newparty1{ border: 2px solid grey; position: absolute; overflow:scroll; z-index: 9999; background-color: #ADAEC0; width:70%;}           
            #r_box { overflow:auto;  height:75%; bottom:0; width:98%;  }
            #newb { position: absolute; padding-left: 12px;padding-right: 12px; padding-top: 5px;padding-bottom: 5px;left: 50%; top: 45%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
            #newc { position: absolute; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
            #newa { position: absolute; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: lightsteelblue; border: 2px solid lightslategrey; }
            #mrq { color: black; text-shadow: grey 0.1em 0.1em 0.2em; font-size:13px; }
            #jodesg { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
            #joname { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; font-weight:bold; }
            table.mytable3 { width: 100%; }
            table.mytable { width: 100%;  -moz-box-shadow: 2px 2px 2px #ccc;  -webkit-box-shadow: 2px 2px 2px #ccc;  box-shadow: 2px 2px 2px #ccc;}
            table.mytable3 td { font-size: 12px; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; vertical-align: top; padding: 0px;  }
            table.mytable td { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
            table.mytable th { font-size: 10pt; font-family:Calibri, Arial, Helvetica, sans-serif; border: none; background-color: #F4F4F4; vertical-align: top; padding: 0px;  }
            hr { color: #666666; background-color:#999999; height: 1px; width:95%; }
            .newb123t td {padding: 1px;}
            #paps123p {max-height: 100px; overflow: auto;}
</style>



<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Moniotring Team - Verification Module </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form name="frm" id="frm">
                                <?= csrf_field() ?>
                                <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                                <?php
                                    $file_list = "";
                                    $cntr = 0;
                                    $chk_slno = 0;
                                    $chk_pslno = 0;   
                                ?> 
                                <input type="hidden" name="caseno" id="caseno">
                                <input type="hidden" name="t_cs" id="t_cs">
                                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                                <input type="hidden" name="sid" id="sid" value="">
                                <input type="hidden" name="flnm" id="flnm" value="">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="Country" class="col-sm-5 col-form-label">Court No.</label>
                                            <div class="col-sm-7">
                                                <select name="courtno" id="courtno" onchange="check_select(1);" class="form-control">
                                                    <option value="">SELECT</option>
                                                    <option value="1">Hon'ble Court No.1</option>
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
                                                    <option value="47">Hon'ble Virtual Court No.17</option>
                                                    <option value="101">Chamber</option>
                                                    <option value="102">Registrar</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center align-self-center">
                                        <span>OR</span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Judge Name</label>
                                            <div class="col-sm-7">
                                                <select name="aw1" id="aw1" onchange="check_select(2);" class="form-control">
                                                    <option value="">SELECT</option>
                                                    <?php
                                                    if (!empty($c_list)) {
                                                        foreach ($c_list as $row2) {
                                                            if ($aw1 == $row2['jcode']) {
                                                                echo '<option value="' . $row2['jcode'] . '" selected>' . str_replace("\\", "", $row2['jname']) . '</option>';
                                                            } else {
                                                                echo '<option value="' . $row2['jcode'] . '">' . str_replace("\\", "", $row2['jname']) . '</option>';
                                                            }
                                                        }
                                                    }
                                                    //echo c_list($paps);
                                                    if ($get_dtd != "")
                                                        $dtd = $get_dtd;
                                                    else
                                                        $dtd = date("d-m-Y");
                                                    if ($get_hdate != "")
                                                        $hdate = $get_hdate;
                                                    else
                                                        $hdate = $dtd;
                                                    if ($get_mf != "")
                                                        $mf = $get_mf;
                                                    else
                                                        $mf = 1;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Cause List Date</label>
                                            <div class="col-sm-7">
                                            <input class="form-control dtp" type="text" value="<?php print $dtd; ?>" name="dtd" id="dtd" size="10"  readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center align-self-center">
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </div>                
                                    <div class="col-md-4" style="display:none;">
                                        <div class="form-group row">
                                            <label for="Country" class="col-sm-5 col-form-label">Verification Status</label>
                                            <div class="col-sm-7">
                                            <select class="form-control ele" name="vstats" id="vstats">
                                                <option value="0">ALL</option>
                                                <option value="1">Verified</option>
                                                <option value="2">Not Verified</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="display:none;">
                                        <div class="form-group row">
                                            <label for="Country" class="col-sm-5 col-form-label">Stage</label>
                                            <div class="col-sm-7">
                                            <input class="" type="radio" name="mf" id="mf" value="M" checked>Miscellaneous&nbsp;
                                            <input class="" type="radio" name="mf" id="mf" value="F">Regular&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">     
                                <div class="form-group row">               
                                    <input class="btn btn-primary" type="button" name="bt11" value="Submit" onclick='fsubmit();'>
                                </div>  
                                </div>    

                                <div id="dv_content1">
                                    <div style="text-align: center">
                                      
                                        <div id="rightcontainer" align="center">
                                            
                                            <div id="r_box" align="center"></div>
                                            <div id="hint" style="text-align: center"></div>
                                            <div id="newb" style="position: fixed">
                                                <div id="newb1" align="center">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="center" width="400px" style="background-color: #B6B0DE">
                                                                <input type='button' name='insert1' id='insert1' value="Save" onClick="return save_rec(1);">&nbsp;
                                                                <input type="button" name="close1" id="close1" value="Close" onClick="return close_w(1)">&nbsp;
                                                                <b>
                                                                    <font color="#000">Pending Remark</font>
                                                                </b>
                                                                <input type="hidden" name="tmp_casenop" id="tmp_casenop" value="" />
                                                            </td>
                                                            <td align="center" rowspan="2">
                                                                <div id="paps123p"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="background-color: #fefefe">
                                                                <b><span id="psn" style="font-size:8pt;"> </span><span id="pend_head" style="font-size:8pt;"></span></b>
                                                                </br>
                                                                <b><span id="pend_head1" style="font-size:8pt;"></span></b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="newb123" style="overflow:auto;">
                                                    <table class="newb123t" width="100%" border="1" style="border-collapse: collapse">
                                                        <?php

                                                        if (!empty($case_remarks_head)) {
                                                            $snoo = 1;
                                                            $chkcnt = 0;
                                                            $chkhead = "";
                                                            $hcntr = 0;
                                                            foreach ($case_remarks_head as $row11) {

                                                                if ($snoo > ((count($case_remarks_head) / 2) - 2) and $chkcnt == 0) {
                                                                    $chkcnt++;
                                                                    echo "</table></td><td width='50%'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                }
                                                                if ($snoo == 1)
                                                                    echo "<tr valign='top'><td width='50%'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                if ($row11["cat_head_id"] == 1000 and $hcntr == 0) {
                                                        ?>
                                                                    <tr bgcolor="#688EC0">
                                                                        <td colspan="2"><b>
                                                                                <font color="#F9FBFD">NEGATIVE OFFICE REMARK</font>
                                                                            </b></td>
                                                                    </tr>
                                                                <?php
                                                                    $hcntr++;
                                                                }

                                                                if (($snoo % 2) == 0)
                                                                    $bgc = "#ECF1F7";
                                                                else
                                                                    $bgc = "#F8F9FC";
                                                                ?>
                                                                <tr bgcolor="<?php echo $bgc; ?>">
                                                                    <td>
                                                                        <input class="cls_chkp" type="checkbox" name="chkp<?php echo $row11['sno']; ?>" id="chkp<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>" />
                                                                        <label class="lblclass" for="chkp<?php echo $row11['sno']; ?>"><?php echo $row11['head'];
                                                                                                                                        if ($row11['sno'] == 21 or $row11['sno'] == 59 or $row11['sno'] == 70 or $row11['sno'] == 131)
                                                                                                                                            echo " (Date)";
                                                                                                                                        ?></label>
                                                                        <?php if ($row11['sno'] == 72) { ?><ruby> <strong> (Type Proper Case No and Separate By ',')</strong> </ruby><?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($row11['sno'] == 22 or $row11['sno'] == 26 or $row11['sno'] == 95 or $row11['sno'] == 142) {
                                                                        ?>
                                                                            <div id="hdremp<?php echo $row11['sno'] . '_div'; ?>"></div>
                                                                            <input type="hidden" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onBlur="textformate(<?php echo $row11['sno']; ?>);" />
                                                                            <?php
                                                                        } else {
                                                                            if ($row11['sno'] == 91) {
                                                                            ?>
                                                                                <input type="button" name="partybutton" id="partybutton" value="PARTY" onclick="make_party_div();" disabled="disabled" />&nbsp;<input size=8 type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onBlur="textformate(<?php echo $row11['sno']; ?>);" />
                                                                            <?php
                                                                            } elseif ($row11['sno'] == 149) {
                                                                            ?>
                                                                                <input type="button" name="partybutton1" id="partybutton1" value="PARTY" onclick="make_party_div_popup();" disabled="disabled" />&nbsp;<input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" />
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onBlur="textformate(<?php echo $row11['sno']; ?>);" />

                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" name="hdp<?php echo $row11['sno']; ?>" id="hdp<?php echo $row11['sno']; ?>" />
                                                                        <input type="hidden" name="srvr" id="srvr" value="<?php echo date('Y'); ?>" />
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                                $snoo++;
                                                            } // Foreach end
                                                        }
                                                        ?>
                                                    </table>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="newc" style="max-height:500px; overflow:auto;">
                                                <div id="newc1" align="center">
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="center" width="400px" style="background-color: #B6B0DE">
                                                                <input type='button' name='insert3' id='insert3' value="Save" onClick="return save_rec(2);">&nbsp;
                                                                <input type="button" name="close3" id="close3" value="Close" onClick="return close_w(2)">&nbsp;
                                                                <b>
                                                                    <font color="#000">Disposal Remark</font>
                                                                </b>
                                                                <input type="hidden" name="tmp_casenod" id="tmp_casenod" value="" />
                                                                <input type="hidden" name="tmp_casenosub" id="tmp_casenosub" value="" />
                                                            </td>
                                                            <td align="center" rowspan="2">
                                                                <div id="paps123d"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="background-color: #fefefe">
                                                                <b><span id="psn1" style="font-size:8pt;"> </span><span id="disp_head" style="font-size:8pt;"></span></b>
                                                                <b><span id="disp_head1" style="font-size:8pt;"></span></b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div id="newc123" style="overflow:auto;">
                                                    <table width="100%" border="1" style="border-collapse: collapse">
                                                        <tr bgcolor="#688EC0">
                                                            <td colspan="4" align="center"><b>
                                                                    <font color="#F9FBFD" size="+1">Hearing Date : </font>
                                                                </b>&nbsp;<input type="text" name="hdate" id="hdate" value="<?php echo $hdate; ?>" size="15">
                                                            <td>
                                                        </tr>
                                                        <?php
                                                        if (!empty($case_remarks_head_side)) {
                                                            $snoo = 1;
                                                            $chkhead = "";
                                                            foreach ($case_remarks_head_side as $row11) {
                                                                if (isset($row11["category"]) && ($chkhead != $row11["category"])) {
                                                        ?>
                                                                    <tr>
                                                                        <td colspan="4" align="center"><b><?php echo $row11["category"]; ?></b></td>
                                                                    </tr>
                                                                <?php
                                                                    $chkhead = $row11["category"];
                                                                }
                                                                if (($snoo % 2) == 0)
                                                                    $bgc = "#ECF1F7";
                                                                else
                                                                    $bgc = "#F8F9FC";
                                                                ?>
                                                                <tr bgcolor="<?php echo $bgc; ?>">
                                                                    <td width="25%">&nbsp;</td>
                                                                    <td width="400px">
                                                                        <input class="cls_chkd" type="checkbox" name="chkd<?php echo $row11['sno']; ?>" id="chkd<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>" />
                                                                        <label class="lblclass" for="chkd<?php echo $row11['sno']; ?>"><?php echo $row11['head']; ?></label>
                                                                    </td>
                                                                    <td width="200px">
                                                                        <?php
                                                                        if ($row11['sno'] == 144) {
                                                                        ?>
                                                                            <input type="text" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value="" onBlur="textformate(<?php echo $row11['sno']; ?>);" />
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <input type="text" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value="" />
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" name="hdd<?php echo $row11['sno']; ?>" id="hdd<?php echo $row11['sno']; ?>" />
                                                                    </td>
                                                                    <td width="25%">&nbsp;</td>
                                                                </tr>
                                                        <?php
                                                                $snoo++;
                                                            } // Foreach end
                                                        }
                                                        ?>
                                                    </table>
                                                </div>
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
                                                                <b>
                                                                    <font color="#000">Allotment for Judgement/Order writing</font>
                                                                </b>
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
                                        <div id="overlay" style="display:none;">&nbsp;</div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- Main content end -->
            </div> <!--end dv_content1-->
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

<script src="<?php echo base_url('rop_v/daily_court_remarks_report.js'); ?>"></script>
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