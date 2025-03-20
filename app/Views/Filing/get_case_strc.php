

<input type="text" name="hd_mn" id="hd_mn" value="<?php echo $bench; ?>"/>
<select name="cs_tp" id="cs_tp">
    <option value="">Select</option>
<?php if (!empty($lc_hc_casetype)){
    foreach ($lc_hc_casetype as $row){?>
        <option value="<?php echo $row['lccasecode']; ?>" <?php if($r_lower_ct_det['lct_casetype']==$row['lccasecode']) { ?>
            selected="selected" <?php } ?>><?php echo $row['type_sname']; ?></option>
   <?php } } ?>
</select>&nbsp;

<input type="text" name="txtFNo" id="txtFNo" maxlength="5" size="5" value="<?php echo $r_lower_ct_det['lct_caseno']; ?>" onblur="com_filingNo()" />&nbsp;&nbsp;

<input type="text" name="txtYear" id="txtYear" maxlength="4" size="4"   value="<?php echo $r_lower_ct_det['lct_caseyear']; ?>"/>&nbsp;&nbsp;

<input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="getDetails();"/>