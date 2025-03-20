<?php
if($u_t==0){
    $sno=1; $s_no=$inc_tot_pg;$res_sq=$total_count; $fst=$offset_right;$inc_val=$offset_left;
    $tot_pg=ceil($res_sq/$inc_val);
?>
<input type="hidden" name="total_count" id="total_count" value="<?php echo $total_count; ?>" />
<input type="hidden" name="hd_fst" id="hd_fst" value="<?php echo $fst; ?>" />
<input type="hidden" name="inc_val" id="inc_val" value="<?php echo $inc_val; ?>"/>
<input type="hidden" name="inc_tot" id="inc_tot" value="<?php echo $tot_pg; ?>" />
<input type="hidden" name="inc_count" id="inc_count" value="1">
<div  class="dv_right" id="dv_le_ri">
    <span id="sp_frst"><?php echo $fst+1 ?></span>-<span id="sp_last"><?php if($res_sq<$inc_val) { echo $res_sq;}else{echo($fst+$inc_val);}?></span> of  <span id="sp_nf"><?php echo ($res_sq) ?></span>
    <?php  if($res_sq>$inc_val){?>
        <input type="button" name="btn_left" id="btn_left" value="<" disabled="true" onclick="get_content_caveat_lower_court_details('L');">
        <input type="button" name="btn_right" id="btn_right" value=">" onclick="get_content_caveat_lower_court_details('R');">
        <?php
    }?></div>
<?php } ?>