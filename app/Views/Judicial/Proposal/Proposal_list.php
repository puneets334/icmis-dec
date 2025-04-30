<?php
$listorder = '0';
$lastorder = '';
$fhc1 = '';
$t_checked = '';
$benchmain = '';
$mfvar = '';
$mainhead_new1 = '';
$check_for_regular_case = "";
?>
<script src="<?= base_url() ?>/judicial/proposal.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pt-4" style="text-align: center;">
                                <h3>Diary No.- <?php echo $diary_number; ?> - <?php echo $diary_year; ?></h3>
                            </div>
                            <div class="cl_center"><u>
                                    <h3>CASE DETAILS</h3>
                                </u></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td style="width: 15%">Case No.</td>
                                    <td><?php echo $case_no; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">DA Name</td>
                                    <td>
                                        <?php echo $da_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Petitioner</td>
                                    <td>
                                        <?php echo $pet_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Respondant</td>
                                    <td>
                                        <?php echo $res_name; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 15%">Case Category</td>
                                    <td><?php echo $mul_category; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        Act
                                    </td>
                                    <td><?php echo $act_section; ?></td>
                                </tr>
                                <tr>
                                    <td>Provision of Law</td>
                                    <td><?php echo $provision_of_law; ?></td>
                                </tr>
                                <tr>
                                    <td>Amicus Curie(For Court Assistance)</td>
                                    <td>
                                        <?php echo $ac_court; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">Petitioner Advocate</td>
                                    <td>
                                        <?php echo $padvname; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Respondant Advocate</td>
                                    <td>
                                        <?php echo $radvname; ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Last Order</td>
                                    <td>
                                        <?php echo $fil_no['lastorder']; ?>
                                    </td>
                                </tr>
                                <?php
                                if ($fil_no['c_status'] == 'P') {
                                    if ($t_rgo != '') { ?>
                                        <tr>
                                            <td>Conditional Dispose</td>
                                            <td style='font-size:12px;font-weight:100;'>
                                                <b>
                                                    <font style='font-size:12px;font-weight:100;'><b><?php echo $t_rgo; ?></b></font>
                                                </b>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            Tentative Date
                                        </td>
                                        <td>
                                            <input type="hidden" name="ttd" id="ttd" value="<?php echo $tentative_date; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Matter Type </td>
                                        <td>
                                            <?php echo $matter_type; ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>

                                    <tr>
                                        <td>Case Status</td>
                                        <td>
                                            <b>
                                                <font color=red style="font-size:14px;">Case is Disposed</font>
                                            </b>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class='cl_center'><u>
                                <h3>LISTINGS / PROPOSALS</h3>
                            </u></div>

                        <?php echo $perposal_listing; ?>

                        <input type='hidden' name='pendingIAs' id='pendingIAs' value=<?php echo $pendingIAs; ?> />
                        <!-- // added on 28.01.2020 -->
                        <input type='hidden' name='last_remarks' id='last_remarks' value=<?php echo $remarks; ?> />
                        <input type='hidden' name='last_cl_date' id='last_cl_date' value=<?php echo $last_cl_date; ?> />

                        <?php if (!empty($ian_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>INTERLOCUTARY APPLICATIONS</h3>
                                </u></div>
                            <?php echo $ian_listing; ?>
                        <?php } ?>

                        <?php if (!empty($doc_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>DOCUMENTS FILED</h3>
                                </u></div>
                            <?php echo $doc_listing; ?>
                        <?php } ?>
                        
                        <?php if (!empty($rmtable) && $fil_no['c_status'] == 'P') { ?>
                            <?php echo $rmtable; ?>
                        <?php } ?>
                        
                        <?php if (!empty($linked_case_listing)) { ?>
                            <div class="cl_center"><u>
                                    <h3>CONNECTED / LINKED CASES</h3>
                                </u></div>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tr>
                                    <td align='center' width='30px'><b>S.N.</b></td>
                                    <td><b>Diary No.</b></td>
                                    <td><b>Case No.</b></td>
                                    <td><b>Proposed for</b></td>
                                    <td><b>Petitioner Vs. Respondant</b></td>
                                    <td><b>Case Category</b></td>
                                    <td align='center'><b>Status</b></td>
                                    <td align='center'><b>Before/Not Before</b></td>
                                    <td align='center'><b>List</b></td>
                                    <td><b>DA</b></td>
                                </tr>
                                <?php echo $linked_case_listing; ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php echo csrf_field(); ?>
</section>
<?= csrf_field() ?>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button> -->
<div id="model-proposal-form" data-bs-backdrop='static' data-bs-keyboard="false" class="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <?php
        ////Proposal Form
        $editable = 1;
        ?>
        <div id="newb">
            <table width="100%" border="1" style="border-collapse: collapse">
                <tr style="background-color: #A9A9A9;">
                    <td align="center">
                        <b>
                            <font color="black" style="font-size:14px;">Proposal in Diary No. <?php echo $diary_number; ?> - <?php echo $diary_year; ?></font>
                        </b>
                    </td>
                </tr>
            </table>
            <div id="newb123" style="overflow:auto; background-color: #FFF;">
                <table class="table_tr_th_w_clr c_vertical_align" border="1" width="100%" style="border-collapse: collapse">
                    <!--                        <tr>
                                                            <td colspan="3">DO YOU WANT TO RECEIVE THE CASE <input type="checkbox" id="da_case_rec_chkbx" /></td>
                                                        </tr>-->

                    <tr>
                        <td align="right">READY to list before :</td>
                        <td colspan="2">
                            <?php
                            $next_dt = $proposal_form['next_dt'];
                            $is_nmd = $proposal_form['is_nmd'];
                            $r_nr = $proposal_form['r_nr'];
                            $lo = $proposal_form['lo'];
                            $bt = $proposal_form['bt'];
                            $sj = $proposal_form['sj'];

                            $reslt_validate_verification = validate_verification($fil_no['diary_no']);
                            if ($reslt_validate_verification > 0) {
                            ?>
                                <font color='red' style='font-size:16px;'>Verification Pending From IB Section</font><br>
                            <?php
                            }

                            // Handle case where no result was found (optional)
                            $lastProposed = "";
                            $lastListedOn = "";
                            $lastSubHead = "";

                            // Check if any row was returned
                            if (!empty($rowLastProposed)) {
                                $lastProposed = $rowLastProposed['board_type'];
                                $lastListedOn = $rowLastProposed['next_dt'];
                                $lastSubHead = $rowLastProposed['subhead'];
                            }
                            ?>


                            <!--<select size="1" name="jrc" id="jrc" onChange="javascript:get_tentative_date(); get_subheading();changeNumJudge();checkInAdvance(); " >
                                            <option value="" <?php /*if($bt=="") echo "selected=selected";*/ ?> > Select </option>
                            --> <?php
                            if ($reslt_validate_verification == 0) {  ?>
                                <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "J") echo "checked"; ?> value="J" onclick="jrc_changed()"> Judge</label>
                                <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "S") echo "checked"; ?> value="S" onclick="jrc_changed()"> Single Judge</label>
                            <?php }  ?>
                            <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "C") echo "checked"; ?> value="C" onclick="jrc_changed()"> Chamber</label>
                            <label><input type="radio" name="jrc" id="jrc" <?php if (substr($bt, 0, 1) == "R") echo "checked"; ?> value="R" onclick="jrc_changed()"> Registrar</label>

                            <!-- No. of Sitting Judges :-->
                            <select name="sj" id="sj" hidden>
                                <option value="1" <?php if ($sj == 1) echo "selected=selected"; ?>>1</option>
                                <option value="2" <?php if ($sj == 2) echo "selected=selected"; ?>>2</option>
                                <option value="3" <?php if ($sj == 3) echo "selected=selected"; ?>>3</option>
                                <option value="4" <?php if ($sj == 4) echo "selected=selected"; ?>>4</option>
                                <option value="5" <?php if ($sj == 5) echo "selected=selected"; ?>>5</option>
                                <option value="6" <?php if ($sj == 6) echo "selected=selected"; ?>>6</option>
                                <option value="7" <?php if ($sj == 7) echo "selected=selected"; ?>>7</option>
                                <option value="8" <?php if ($sj == 8) echo "selected=selected"; ?>>8</option>
                                <option value="9" <?php if ($sj == 9) echo "selected=selected"; ?>>9</option>
                                <option value="10" <?php if ($sj == 10) echo "selected=selected"; ?>>10</option>
                                <option value="11" <?php if ($sj == 11) echo "selected=selected"; ?>>11</option>
                                <option value="12" <?php if ($sj == 12) echo "selected=selected"; ?>>12</option>
                                <option value="13" <?php if ($sj == 13) echo "selected=selected"; ?>>13</option>
                                <option value="14" <?php if ($sj == 14) echo "selected=selected"; ?>>14</option>
                                <option value="15" <?php if ($sj == 15) echo "selected=selected"; ?>>15</option>
                            </select>
                        </td>
                        <td><input type="hidden" value="<?php echo $lastProposed; ?>" name="lastProposed" id="lastProposed"></td>
                        <td><input type="hidden" value="<?php echo $lastListedOn; ?>" name="lastListedOn" id="lastListedOn"></td>
                        <td><input type="hidden" value="<?php echo $lastSubHead; ?>" name="lastSubHead" id="lastSubHead"></td>
                        <td><input type="hidden" name="usercode" id="usercode" value="<?= $ucode ?>"></td>
                    </tr>
                    <tr valign="top">
                        <td align="right">Purpose of Listing : </td>
                        <td align="left" colspan="2">
                            <select size="1" name="listorder" id="listorder" onChange="javascript:get_tentative_date(); chg_def1();">
                                <option value="">Select</option>
                                <?php
                                    // Check if any rows are returned
                                    if (!empty($sql_lp1)) {
                                            foreach ($sql_lp1 as $row_lp1) {
                                            if ($row_list > 0 and $row_lp1["code"] == 32 and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))))
                                                $temp_check = " disabled=disabled ";

                                            else if (($row_lp1["code"] == 24 or $row_lp1["code"] == 2  or $row_lp1["code"] == 48) and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users'])))) //fresh or $row_lp1["code"]==32
                                                $temp_check = " disabled=disabled ";
                                            else if (($row_lp1["code"] == 49 or $row_lp1["code"] == 5  or ($mainhead_kk == 'F' and ($row_lp1["code"] == 4))) and !($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))))
                                                $temp_check = " disabled=disabled "; //OR ($link['c_type'] != 'M' AND ($row_lp1["code"]==4 OR $row_lp1["code"]==7 OR $row_lp1["code"]==8) modified on 11.02.2019)
                                            else
                                                $temp_check = " ";
                                            if ($lo == $row_lp1["code"])
                                                echo '<option value="' . $row_lp1["code"] . '" selected="selected" ' . $temp_check . '>' . $row_lp1["lp"] . '</option>';
                                            else
                                                echo '<option value="' . $row_lp1["code"] . '"' . $temp_check . '>' . $row_lp1["lp"] . '</option>';
                                        }
                                    }
                                if ($listorder == 22)
                                    echo '<option value="22" selected="selected">REGISTRAR AUTHENTICATED</option>';
                                ?>
                            </select>&nbsp;
                            (Regular hearing Court orders : list on/next week/after week etc. may update by previous court remark module)
                            <?php
                            if ($listorder == 22)
                                echo "<br><font color='red'>REGISTRAR AUTHENTICATED</font>&nbsp;&nbsp;";
                            if ($lastorder != "" and $fhc1 == "")
                                echo "<br>Last Order: <font color='red'>" . $lastorder . "</font>&nbsp;&nbsp;";
                            if ($fhc1 != "")
                                echo "<br>" . $fhc1;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">Proposed Listing Date : </td>
                        <td>
                            <?php
                            $pdate = '';
                            // echo $next_dt."-".$tentative_date;
                            if ($next_dt != "" and $next_dt != "0000-00-00")
                                $pdate = date('d-m-Y', strtotime($next_dt));
                            if ($tentative_date != "" and $tentative_date != "0000-00-00")
                                if (strtotime($tentative_date) > strtotime($next_dt))
                                    $pdate = date('d-m-Y', strtotime($tentative_date));
                            //echo $pdate;

                            if (!empty($tentative_date) && date("Y", strtotime($tentative_date)) == 2077) {
                                $tomorrow = strtotime('+1 day');
                                $pdate = date('d-m-Y', $tomorrow);
                            }

                            $t_pdate = $pdate;
                            if ($listorder == 4 or $listorder == 5 or $listorder == 7)
                                $editable = 0;

                            if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) {
                                $allow = 1;
                                if ($editable == 0) {
                            ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>" size="15" onchange="checkFutureDate();checkInAdvance();" onload="">&nbsp;(dd-mm-yyyy)
                                    <!--<br>[<font color="green">Tentative Date : <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                <?php
                                } else if ($t_pdate == '' or $t_pdate == '00-00-0000' or $tentative_date == '0000-00-00' or trim($tentative_date) == '') {

                                ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>"
                                        onchange="checkFutureDate();checkInAdvance();" size="15">&nbsp;(dd-mm-yyyy)&nbsp;
                                    <!--[<font color="green">Tentative Date: <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                <?php
                                } else {

                                ?>
                                    <input class="dtp" type="text" name="thdate" id="thdate" value="<?php echo $t_pdate; ?>"
                                        onchange="checkFutureDate();checkInAdvance();" size="15" disabled="disabled">&nbsp;(dd-mm-yyyy)&nbsp;
                                    <!--[<font color="green">Tentative Date: <?php /*echo change_date_format($t_pdate) */ ?></font>]-->
                                    <input class="dtp" type="hidden" name="prev_thdate" id="prev_thdate" value="<?php echo $t_pdate; ?>" size="15">
                                <?php
                                }
                            } else {
                                $allow = 0;
                                ?> <input class="dtp" type="text" name="thdate" id="thdate" value="" size="15" onchange="checkFutureDate();checkInAdvance();">
                                <input class="dtp" type="hidden" name="prev_thdate" id="prev_thdate" value="<?php echo $t_pdate; ?>" size="15">
                            <?php

                            }
                            ?>

                            <input type="hidden" name="thdate_h" id="thdate_h" value="<?php echo $pdate; ?>">
                            <input type="hidden" name="thdate_nm" id="thdate_nm" value="<?php echo $nextmonday; ?>">

                        </td>
                        <td>
                            <?php if (($user_case_updation['display_flag'] == '1' || in_array($ucode, explode(',', $user_case_updation['always_allowed_users']))) || (($ucode == 1504 || $ucode == 94) and (($row_sensitive != null and $row_sensitive != '') or ($row_PIP != null and $row_PIP != '')))) { ?>
                                <select name="r_nr" id="r_nr">
                                    <option value="R" <?php if ($r_nr != 3) echo "selected=selected"; ?>>READY</option>

                                    <option value="NR" <?php if ($r_nr == 3)  echo "selected=selected"; ?>>NOT READY</option>
                                </select>
                            <?php } else { ?>
                                <select name="r_nr" id="r_nr" hidden>
                                    <option value="R" <?php if ($r_nr != 3) echo "selected=selected"; ?>>READY</option>
                                </select>

                            <?php   } ?>

                        </td>
                        <td><input type="hidden" name="future_date" id="future_date" value=<?php echo $future_dates; ?>></td>
                    </tr>
                    <tr>
                        <td align="right">Hearing Head :</td>
                        <td align="left">
                            <select size="1" name="mf_select" id="mf_select" onChange="subheading_change()">
                                <option value="M" <?php if ($t11 == "M") echo "selected"; ?>>Miscellaneous Hearing</option>
                                <option value="F" <?php if ($t11 == "F") echo "selected"; ?>>Regular Hearing</option>
                            </select>&nbsp;&nbsp;
                        </td>
                        <td>
                            <?php
                            if ($main_fh_fil_no == '') {
                            ?>
                                <div class="fh_error" style="display:none;">
                                    <font color="red">Check whether Direct Appeal or Not. If Not inform Computer Cell</font>
                                </div>
                            <?php
                            }
                            ?>
                        </td>

                    </tr>
                    <tr valign="top">
                        <td align="right">Case Category :</td>
                        <td align="left" colspan="2"><?php echo $mul_category; ?></td>
                    </tr>
                    <?php
                    $bf = "";
                    $nbf = "";
                    if ($bf != "")
                        $bf = "<tr><td width='110px'><b><u>LIST BEFORE</u></b> : </td><td><font color='green'><b>" . $bf . "</b></font></td></tr>";
                    if ($nbf != "")
                        $nbf = "<tr><td width='110px'><b><u>NOT LIST BEFORE</u></b> : </td><td><font color='red'><b>" . $nbf . "</b></font></td></tr>";
                    if ($bf != "" or $nbf != "")
                        $pr_bf = "<table>" . $bf . $nbf . "</table>";
                    if ($benchmain == "S") {
                        if ($judge1 > 0)
                            $t_jud1 = $judge1;
                        else
                            $t_jud1 = "250";
                    }
                    if ($benchmain == "D") {
                        if ($judge1 > 0)
                            $t_jud1 = $judge1;
                        else
                            $t_jud1 = "200";
                        if ($judge2 > 0)
                            $t_jud2 = $judge2;
                        else
                            $t_jud2 = "999";
                    }
                    ?>

                    <?php

                    if ($mfvar != $mainhead_new1 and $mainhead_new1 != '') {
                    ?>

                        <tr valign="top">
                            <td align="right">Pre. Heading :</td>
                            <td align="left"><b>
                                    <font color='blue'><?php echo $mainhead_new1 . " [" . $subhead_new1 . "]"; ?></font>
                                </b></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr valign="top">
                        <td align="right">Sub Heading :</td>
                        <td align="left" colspan="2">
                            <select size="1" style="width:100%;" name="subhead_select" id="subhead_select">
                            <option value="">SELECT</option>
                            </select>
                        </td>
                    </tr>
                    <!--purpose//-->
                    <tr valign="top">
                        <td align="right">
                            Statutory Information :
                        </td>
                        <td align="left"><b>IAs to be list :</b><span style='color:green; font-weight: bold;' id="ianp_jshow"><?php echo $listed_ia; ?></span><?php echo $ian_p; ?>&nbsp;</td>
                        <td align="left">(Info. regarding IA not to be inserted in the statutary box it will come automatically in the proposal.)
                            <?php
                            // $br = get_brd_remarks($fil_no['diary_no']);
                            echo '<font color=green><b>' . $brdremh . '</b></font>'; ?>
                            <input type="hidden" name="brdremh" id="brdremh" value="<?php echo $brdremh; ?>">
                            <textarea cols="50" name="brdrem" id="brdrem" rows="5" style='width:95%;min-height:75%;'><?php echo $brdremh; ?></textarea>
                        </td>

                    </tr>
                    <?php
                    //echo $conncases."cxzcxzc";
                    if (count($conncases) > 0 and $check_for_conn != 'N') {
                        $lconn = "Y";
                    ?>
                        <tr valign="top">
                            <th align="right">
                                Connected Case :
                            </th>
                            <td align="left" colspan="2">
                                <br>
                                <div id="conncasediv" <?php
                                                        if ($lconn == "Y")
                                                            echo "style='display:block;'";
                                                        else
                                                            echo "style='display:none;'";
                                                        ?>>
                                    <?php echo $connchks; ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    } else {
                        $lconn = "N";
                    }
                    ?>
                    <tr bgcolor="#FAFAFE" valign="top">
                        <td height="100%" style="bottom:0">&nbsp;
                        </td>
                        <td colspan="2">
                        </td>
                    </tr>

                </table>
            </div>
            <div id="newb1" align="center">
                <input type="hidden" name="diaryno" id="diaryno" value="<?php echo $fil_no['diary_no']; ?>">
                <table border="0" width="100%">
                    <tr>
                        <td align="center" width="250px">
                            <input type='button' name='insert1' id='insert1' value="Save" onClick="return check_details();">&nbsp;
                            <input type="button" name="close1" id="close1" value="Cancel" onClick="return close_w()">
                            <input type="hidden" name="tmp_casenop" id="tmp_casenop" value="" />
                        </td>
                    </tr>
                </table>
            </div>

        </div>

</div>
  </div>
</div>


<?php
////Proposal Form end
?>
<div id="newcs" style="display:none;">
    <table width="100%" border="0" style="border-collapse: collapse">
        <tr style="background-color: #A9A9A9;">
            <td align="center">
                <b>
                    <font color="black" style="font-size:14px;">Case Status</font>
                </b>
            </td>
            <td>
                <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_wcs();" />
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

<input type="hidden" name="sh" id="sh" value="<?php if ($lastSubHead == '') print $subhead;
                                                else print $lastSubHead; ?>" />
<input type="hidden" name="da_hidden" id="da_hidden" value="<?php echo ''; ?>" />
<input type="hidden" name="ucode" id="ucode" value="<?php echo $ucode; ?>" />
<input type="hidden" name="check_for_regular_case" id="check_for_regular_case" value="<?php echo $check_for_regular_case; ?>" />

<script>
var excluded_dates=<?php echo json_encode($holiday_dates) ?>;
$(function() {
    var date = new Date();
    date.setDate('<?php echo $t_pdate; ?>');
    $('.dtp').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        // startDate: date,
        todayHighlight: true,
        changeMonth : true, 
        changeYear : true,
        minDate:'+1',
        yearRange : '-0:+1',
        datesDisabled: excluded_dates,
        isInvalidDate: function(date) {
            return (date.day() == 0 || date.day() == 6);
        },
    });
});
   /*var excluded_dates=<?php //echo json_encode($holiday_dates) ?>;
    $(document).on("focus",".dtp",function() 
    {

        console.log(excluded_dates);

        $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,minDate:'+1', yearRange: '-0:+1',beforeShowDay: function(date) 
            {
                console.log(date);

                date = $.datepicker.formatDate('yy-mm-dd', date);
                
                console.log(date);

                var excluded = $.inArray(date, excluded_dates) > -1;
                return [!excluded, ''];
            }
        });
    });*/
</script>
<script type="text/javascript">
    function check_details() {
        var ucode = '<?php echo $ucode ?>';
        var r_nr = document.getElementById('r_nr').value;
        if (r_nr == 'R' && (ucode == '1504' || ucode == '94')) {
            if (confirm("Matter will be Proposed for Listing.Do you want to propose the matter?")) {
                return check_proposal();
            }
        } else
            return check_proposal();
    }

    function checkInAdvance() {
        var inAdvance = '<?php echo $reslt_validate_caseInAdvanceList ?>';
        var inAdvanceSingle = '<?php echo $reslt_validate_caseInAdvanceListSingleJudge ?>';
        var infinal = '<?php echo $result_caseInFinalList ?>';
        var infinalSingle = '<?php echo $result_caseInFinalListSingleJudge ?>';
        var allowed = '<?php echo $allowed ?>';
        var noticeissued = '<?php echo $noticeissued ?>';
        if (inAdvance == true && noticeissued == 0) {
            alert("Case Listed in Advance List.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (infinal == true && noticeissued == 1) {
            alert("Case Listed in Final List.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (inAdvanceSingle == true && noticeissued == 0) {
            alert("Case Listed in Advance List before Single Judge.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        } else if (infinalSingle == true && noticeissued == 1) {
            alert("Case Listed in Final List before Single Judge.COURT,DATE AND HEARING HEAD cannot be updated. Contact to DEU-II Section");
            document.getElementById('insert1').hidden = true;

        }
    }


    function checkFutureDate() {
        var date1 = document.getElementById('thdate').value;
        /* added on 30.11.2018 */
        if (date1 == '')
            date1 = '<?php echo $t_pdate; ?>';
        /* end */
        var future_date = document.getElementById('future_date').value;
        var usercode = '<?php echo $ucode; ?>';
        var user_updation = '<?php echo $user_case_updation['always_allowed_users']; ?>';
        user_updation = user_updation.split(",");
        date1 = date1.split('-')[2] + "-" + date1.split('-')[1] + "-" + date1.split('-')[0];
        future_date = future_date.split(",");
        /*
         for(var j=0;j<=user_updation.length;j++){
            if(usercode!=user_updation[j] ) {

        */
        if (!user_updation.includes(usercode)) {
            for (var i = 0; i <= future_date.length; i++) {
                if (date1 == future_date[i]) {
                    alert("Cause List has been published for the date. Please select other future date!");
                    document.getElementById('thdate').value = "";
                    break;
                }

            }
        }
    }

    function hearingHeadChange() {

        var listorder = document.getElementById('listorder').value;
        var hearingHead = document.getElementById('mf_select').value;
        var category = '<?php echo $category_id; ?>';
        var is_nmd = '<?php echo $is_nmd; ?>';

        //  var short_category_id = "343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222"; //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        //   short_category_id=short_category_id.split(","); //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
        if (hearingHead == 'F' && listorder != 4 && listorder != 5)
            document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
        else if (hearingHead == 'M' && listorder != 4 && listorder != 5) {
            /*   for (var i = 0; i <= short_category_id.length; i++) {  //commented on 8.4.2024 to remove short category and first 4 judges concept by preeti
                if(category==short_category_id[i].trim())
                {
                    document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
                    break;
                }
                else */
            if (is_nmd == 'Y') {
                document.getElementById('thdate').value = '<?php echo $nexttuesday; ?>';
                //break;
            } else {
                document.getElementById('thdate').value = '<?php echo $nextmonday; ?>';
            }
            //  }
        }
    }

    function changeNumJudge() {
        var option = $("input[name='jrc']:checked").val();
        var allow = '<?php echo $allow; ?>';
        if (option == 'J') {
            document.getElementById('sj').value = 2;
            if (allow == 0)
                document.getElementById('thdate').hidden = true;
        } else
            document.getElementById('sj').value = 1;

        var category = '<?php echo $mul_category; ?>';
        if ((option == 'J' || option == 'S') && category == '') { //Condition modified by Preeti Agrawal on 17062022. Added condition for Single Judge also
            alert("Subject Category not updated. Hence, case cannot be proposed for listing in Hon'ble Court");
            document.getElementById('insert1').hidden = true;
        } else
            document.getElementById('insert1').hidden = false;
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function subheading_change() {
        await get_tentative_date();
        await get_subheading();
        checkInAdvance();
    }

    async function jrc_changed() {
        $("input[name='jrc']").prop('disabled', true);
        await get_tentative_date();
        await sleep(500);
        await get_subheading();
        changeNumJudge();
        checkInAdvance();
        $("input[name='jrc']").prop('disabled', false);
    }

    $(document).ready(function() {

        var option = $("input[name='jrc']:checked").val();
        if (option == 'J')
            document.getElementById('sj').value = 2;
        else
            document.getElementById('sj').value = 1;

        var category = '<?php echo $mul_category; ?>';
        if ((option == 'J' || option == 'S') && category == '') { //Condition modified by Preeti Agrawal on 17062022. Added condition for Single Judge also
            // alert("Subject Category not updated. Hence, case cannot be proposed for listing in Hon'ble Court");
            document.getElementById('insert1').hidden = true;
        } else {
            document.getElementById('insert1').hidden = false;
        }

        // Make default click
        // jrc_changed();
    });
</script>