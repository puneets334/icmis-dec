<?php
echo "<br>";
echo "<br>";
echo $conncases;
echo "<br>";
if ($conncntr > 0) {
    echo $tbl_but??'';
}
if ($conncntr != $check_disabled)
    $t_checked1 = "";
?>
<?php
$case_grp = '';
$casetype = $modelIA->get_casetype_q($diaryNo);
if ($casetype) {
    if ($casetype['active_casetype_id'] == 1) {
        $conversion_type = 3;
    } else if ($casetype['active_casetype_id'] == 2) {
        $conversion_type = 4;
    } else if ($casetype['active_casetype_id'] == 7) {
        $conversion_type = 11;
    } /*else if($casetype['active_casetype_id']==8){
                $conversion_type=12;
            }*/ else {

        $conversion_type = '0';
        $t_checked1 = "disabled=disabled";
    }
}

?>
Case Type:
<select id="selct1" <?php print $t_checked1; ?>>
    <option value="-1">Select</option>
    <?php
    $ct_rs = $modelIA->get_ct_rs($conversion_type);
    foreach ($ct_rs as $ct_rw) {
    ?>
        <option value="<?php echo $ct_rw['casecode'] ?>" selected><?php echo $ct_rw['short_description']; ?></option>
    <?php } ?>
</select>&nbsp;&nbsp;
Registration Order date:<input class="dtp" type="text" name="dtd" id="dtd" size="10"
    style="font-family:verdana; font-size:9pt;" <?php print $t_checked1; ?>
    <?php if ($order_date != '') { ?>value="<?php echo $order_date; ?> " disabled <?php } ?> />
&nbsp;&nbsp;

<div style="text-align: center;margin-top: 20px">
    <b>Enter Year for which registration is to be done:</b>
    <?php
    $currently_selected = date('Y');
    $earliest_year = 1950;
    $latest_year = date('Y') - 1;
    print '<select id="previous_year">';
    print '<option value="">select</option>';
    foreach (range($latest_year, $earliest_year) as $i) {

        print '<option value="' . $i . '">' . $i . '</option>';
    }
    print '</select>'; ?>

</div>

<input type="button" id="add" value="Generate" onclick="generate_case();" <?php print $t_checked1; ?> />
<?php if ($order_date != '') { ?>
    <div style="text-align: center;margin-top: 20px">
        <font style="color: darkgreen;font-weight: bold">
            Note: If actual order date doesn't match with the date displayed above, Please update Previous Court Remarks in
            the actual Order date.
        </font>
    </div>
<?php } ?>
</div>
<div id="dv_load"></div>

<br><br>

<div id="newb" style="display:none">
    <div id="newb123">
        <table width="100%" border="1" style="border-collapse: collapse">
            <tr>
                <td align="center" colspan="7">
                    <b>
                        <font color="#000">ADDITION OF CONNECTED / LINKED CASES</font>
                    </b>
                </td>
            </tr>
            <tr>

                <td align="center" bgcolor="#ECF1F7">Diary No.:<input type="text" id="dno_add_c" size="4"
                        value="19241" />&nbsp;
                    Year:<input type="text" id="dyr_add_c" size="4" maxlength="4"
                        value="2016" />&nbsp;&nbsp;&nbsp;<input type="button" name="getbtn" id="getbtn" value="GET"
                        onClick="get_case_status();" /></td>
            </tr>
        </table>
        <div id="ccdiv" style="overflow:auto;"></div>
    </div>
    <div id="newb1" align="center">
        <table border="0" width="100%">
            <tr>
                <td align="center">
                    <input type="button" name="close1" id="close1" value="Cancel" onClick="return close_w(1)">
                </td>
            </tr>
        </table>
    </div>
</div>
<input type="hidden" name="sh_hidden" id="sh_hidden" value="<?php echo $shead1; ?>" />
<input type="hidden" name="diary_no" id="diary_no" value="<?php echo $diaryno; ?>" />
<input type="hidden" name="benchm" id="benchm" value="<?php echo $benchmain; ?>" />