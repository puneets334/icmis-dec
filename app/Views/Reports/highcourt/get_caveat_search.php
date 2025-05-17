<?php

if ($res_sq > 0) {
  $fst = 0;
  $inc_val = 500;
  $tot_pg = ceil($res_sq / $inc_val);
?>
  <input type="hidden" name="hd_fst" id="hd_fst" value="<?php echo $fst; ?>" />
  <input type="hidden" name="inc_val" id="inc_val" value="<?php echo $inc_val; ?>" />
  <input type="hidden" name="inc_tot" id="inc_tot" value="<?php echo $tot_pg; ?>" />

  <input type="hidden" name="inc_count" id="inc_count" value="1" />
  <div class="dv_right" id="dv_le_ri">
    <span id="sp_frst"><?php echo $fst + 1 ?></span>-<span id="sp_last"><?php if ($res_sq < $inc_val) {
                                                                        echo $res_sq;
                                                                      } else {
                                                                        echo ($fst + $inc_val);
                                                                      } ?></span> of <span id="sp_nf"><?php echo ($res_sq) ?></span>
    <?php
    if ($res_sq > $inc_val) {
    ?>
      <input type="button" name="btn_left" id="btn_left" value="<" disabled="true" />
      <input type="button" name="btn_right" id="btn_right" value=">" />
    <?php
    }
    ?>
  </div>
<?php
}



if ($res_sq > 0) {
?>
  <div id="dv_include" style="text-align: center;width: 100%">

    <?php

    $_REQUEST['u_t'] = 0;
    include('include_caveat.php');

    ?>
  </div>
<?php
} else {
?>
  <div style="text-align: center"><b>No Record Found</b></div>

<?php
}






?>