<?= view('header') ?>
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
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    @-webkit-keyframes blinker {
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    @keyframes blinker {
        0% {
            opacity: 1.0;
        }

        50% {
            opacity: 0.0;
        }

        100% {
            opacity: 1.0;
        }
    }

    html,
    body {
        height: 100%;
    }

    body {
        position: relative;
        font-size: 10pt;
        font-family: Calibri, Arial, Helvetica, sans-serif;
    }

    .lblclass {
        font-size: 12pt;
    }

    /*#s_box { width: 100%; background-color: #ADAEC0; border-top: 1px solid #fff; position: fixed; top: 0px; left: 0; right: 0; z-index: 0; }*/
    /*#messagepost { z-index: 9999; }*/
    #newparty {
        border: 2px solid grey;
        position: absolute;
        overflow: scroll;
        z-index: 9999;
        background-color: #ADAEC0;
        width: 70%;
    }

    #newparty1 {
        border: 2px solid grey;
        position: absolute;
        overflow: scroll;
        z-index: 9999;
        background-color: #ADAEC0;
        width: 70%;
    }

    #r_box {
        overflow: auto;
        height: 75%;
        bottom: 0;
        width: 98%;
    }

    #newb {
        position: absolute;
        padding-left: 12px;
        padding-right: 12px;
        padding-top: 5px;
        padding-bottom: 5px;
        left: 50%;
        top: 45%;
        display: none;
        color: black;
        background-color: lightsteelblue;
        border: 2px solid lightslategrey;
    }

    #newc {
        position: absolute;
        padding: 12px;
        left: 50%;
        top: 50%;
        display: none;
        color: black;
        background-color: lightsteelblue;
        border: 2px solid lightslategrey;
    }

    #newa {
        position: absolute;
        padding: 12px;
        left: 50%;
        top: 50%;
        display: none;
        color: black;
        background-color: lightsteelblue;
        border: 2px solid lightslategrey;
    }

    #mrq {
        color: black;
        text-shadow: grey 0.1em 0.1em 0.2em;
        font-size: 13px;
    }

    #jodesg {
        font-size: 10pt;
        font-family: Calibri, Arial, Helvetica, sans-serif;
        font-weight: bold;
    }

    #joname {
        font-size: 10pt;
        font-family: Calibri, Arial, Helvetica, sans-serif;
        font-weight: bold;
    }

    table.mytable3 {
        width: 100%;
    }

    table.mytable {
        width: 100%;
        -moz-box-shadow: 2px 2px 2px #ccc;
        -webkit-box-shadow: 2px 2px 2px #ccc;
        box-shadow: 2px 2px 2px #ccc;
    }

    table.mytable3 td {
        font-size: 12px;
        font-family: Calibri, Arial, Helvetica, sans-serif;
        border: none;
        vertical-align: top;
        padding: 0px;
    }

    table.mytable td {
        font-size: 10pt;
        font-family: Calibri, Arial, Helvetica, sans-serif;
        border: none;
        background-color: #F4F4F4;
        vertical-align: top;
        padding: 0px;
    }

    table.mytable th {
        font-size: 10pt;
        font-family: Calibri, Arial, Helvetica, sans-serif;
        border: none;
        background-color: #F4F4F4;
        vertical-align: top;
        padding: 0px;
    }

    hr {
        color: #666666;
        background-color: #999999;
        height: 1px;
        width: 95%;
    }

    .newb123t td {
        padding: 1px;
    }

    #paps123p {
        max-height: 100px;
        overflow: auto;
    }
</style>
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
<script type="text/javascript" src="<?= base_url() ?>/judicial/da_daily_court_remarks.js"></script>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> Daily Remarks</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff;">
                                <?= view('Judicial/Reports/menu') ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
                                    <div class="card">
                                            <div class="card-body">
                                                <div class="container text-center">
                                                    <h3>Daily Remarks</h3>
                                                </div>
                                    <form name="frm" id="frm">
                                        <?php echo csrf_field('CSRF_TOKEN'); ?>
                                        <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                                        <div id="dv_content1">
                                            <div style="text-align: center">
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
                                                <div id="rightcontainer" align="center">
                                                    <div id="s_box" align="center">
                                                        <table border="0" height="35" width="100%">
                                                            <tr valign="middle">
                                                                <td>Court No.</td>
                                                                <td>OR</td>
                                                                <td>Judge Name</td>
                                                                <td>Cause List Date</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr valign="middle">
                                                                <td style="width: 40%;">
                                                                    <select name="courtno" id="courtno" onchange="check_select(1);">
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
                                                                </td>
                                                                <td> / </td>
                                                                <td>
                                                                    <select name="aw1" id="aw1" style="font-family:verdana; font-size:9pt;" onchange="check_select(2);">
                                                                        <option value="">SELECT</option>

                                                                        <?php
                                                                        foreach ($all_judges as $row2) {
                                                                            if ($aw1 == $row2["jcode"])
                                                                                echo '<option value="' . $row2["jcode"] . '" selected>' . str_replace("\\", "", $row2["jname"]) . '</option>';
                                                                            else
                                                                                echo '<option value="' . $row2["jcode"] . '">' . str_replace("\\", "", $row2["jname"]) . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input class="" type="date" value="<?php print $dtd; ?>" name="dtd" id="dtd" value="" pattern="\d{2}-\d{2}-\d{4}" placeholder="dd-mm-yyyy">
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr valign="middle">
                                                                <th>Causelist Type :</th>
                                                                <th colspan="3">Court Remark Status :</th>
                                                                <th></th>
                                                            </tr>
                                                            <tr valign="middle">
                                                                <td>
                                                                    <label><input type="radio" name="mf" id="mf" value="M" checked>Miscellaneous</label>
                                                                    <label><input type="radio" name="mf" id="mf" value="F">Regular</label>
                                                                    <label><input type="radio" name="mf" id="mf" value="L">Lok Adalat</label>
                                                                    <label><input type="radio" name="mf" id="mf" value="S">Mediation</label>
                                                                </td>
                                                                <td colspan="3">
                                                                <label><input type="radio" name="r_status" id="r_status" value="A" checked>All</label>
                                                                <label><input type="radio" name="r_status" id="r_status" value="P">Pending</label>
                                                                <label><input type="radio" name="r_status" id="r_status" value="D">Disposed</label>
                                                                    <span id="mf_box"></span>
                                                                </td>
                                                                <td>
                                                                    <input type="button" name="bt11" value="Submit" onclick='fsubmit();'>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                </div>
                                                <div id="show_message_process_submit"></div>
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
                                                            $ttl_records = count($sql11);
                                                            if ($ttl_records > 0) {
                                                                $snoo = 1;
                                                                $chkcnt = 0;
                                                                $chkhead = "";
                                                                $hcntr = 0;
                                                                foreach ($sql11 as $row11) {
                                                                    if ($snoo > (($ttl_records / 2) - 2) and $chkcnt == 0) {
                                                                        $chkcnt++;
                                                                        echo "</table></td><td width='50%'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                    }

                                                                    if ($snoo == 1)
                                                                        echo "<tr valign='top'><td width='50%'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                                                                    
                                                                    if ($row11["cat_head_id"] == 1000 and $hcntr == 0) 
                                                                    { ?>
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
                                                                } // while end
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
                                                            $t11 = count($sql12);
                                                            if ($t11 > 0) {
                                                                $snoo = 1;
                                                                $chkhead = "";
                                                                foreach ($sql12 as $row11) {
                                                                    if (!empty($row11["category"])) {
                                                            ?>
                                                                        <tr>
                                                                            <td colspan="4" align="center"><b><?php echo $row11["category"]; ?></b></td>
                                                                        </tr>
                                                                    <?php
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
                                                                } // while end
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
                                        </div>
                                    </form>
                                    </div></div>
                                    <!-- Page Content End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>