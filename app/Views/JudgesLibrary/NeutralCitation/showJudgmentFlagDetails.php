<hr size="20">
<?php
$rjm = explode("/", $file_address);
if ($rjm[0] == 'supremecourt') {
  $temp_var = 'jud_ord_html_pdf/' . $file_address;
} else {
  $temp_var = 'judgment/' . $file_address;
}
$server_address_live = base_url(). "/" . $temp_var;
// pr($server_address_live);
?>
<div class="col-sm-12 form-group">
  <label class="col-sm-2 text-primary"></label>
  <label class="col-sm-3"><a class="text-danger" href="<?= $server_address_live ?>" target="_blank">View</a></label>
</div>
<div class="col-sm-12 form-group">
  <label class="col-sm-2 text-primary">Judgment / Final Order :</label>
  <label class="col-sm-3"><input type="radio" name="orderType" value="Order" <?= $order_type_short == 'O' ? 'checked' : '' ?>> Record of Proceedings</label>
  <label class="col-sm-4"><input type="radio" name="orderType" value="Judgement" <?= $order_type_short == 'J' ? 'checked' : '' ?>> Judgment / Reportable Order / Signed Order with Reason</label>
  <label class="col-sm-3"><input type="radio" name="orderType" value="FinalOrder" <?= $order_type_short == 'FO' ? 'checked' : '' ?>> Final Order</label>
</div>
<hr size="20">

<div class="col-sm-12 col-md-3 mb-3">
  <button type="submit" id="btnSaveROP" class="quick-btn mt-26">SAVE</button>
  </button>
</div>