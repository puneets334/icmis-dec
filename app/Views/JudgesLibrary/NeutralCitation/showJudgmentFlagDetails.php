<hr size="20">
<?php
$rjm = explode("/", $file_address);
if ($rjm[0] == 'supremecourt') {
  $temp_var = 'jud_ord_html_pdf/' . $file_address;
} else {
  $temp_var = 'judgment/' . $file_address;
}

// Full server-side path (for checking file existence)
$local_file_path = FCPATH . $temp_var;  // FCPATH is the root of your CodeIgniter project (like $_SERVER['DOCUMENT_ROOT'])
$server_address_live = base_url($temp_var);
?>

<div class="col-sm-12 form-group">
  <label class="col-sm-2 text-primary"></label>
  <label class="col-sm-3">
    <?php if (file_exists($local_file_path)) { ?>
      <a class="text-danger" href="<?= $server_address_live ?>" target="_blank">View</a>
    <?php } else { ?>
      <span class="text-danger" onclick="alert('Data not found. PDF is missing!')" style="cursor:pointer;">View</span>
    <?php } ?>
  </label>
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
</div>