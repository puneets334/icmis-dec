<?php if (is_array($caseDetails)) {
?>
  <br /><br />
  <form id="frmReplaceRop" name="frmReplaceRop" enctype="multipart/form-data" action="<?php echo base_url('JudgesLibrary/NeutralCitation/changeJudgementFlag'); ?>" method="post">
    <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />
    <?= csrf_field() ?>

    <div class="col-sm-12 form-group">

      <label for="diaryNumber" class="col-sm-6 control-label"><span class="text-primary">Case No: </span> <?= $diaryNumberForSearch['reg_no_display']  ?>&nbsp;(D No.<?= $diaryNumberForSearch['diary_no'] ?>)</label>
      <label for="causeTitle" class="col-sm-6 control-label"><span class="text-primary">Cause Title : </span> <?= $diaryNumberForSearch['pet_name']  ?> Vs. <?= $diaryNumberForSearch['res_name'] ?></label>
    </div>
    <div class="col-sm-12 form-group">

      <label for="diaryYear" class="col-sm-2 control-label text-primary">Order Date</label>
      <div class="col-sm-4">
        <select class="form-control" id="listingDates" name="listingDates" placeholder="listingDates" onchange="getListedDetails(this);">
          <option value="0">Select Listing Date</option>
          <?php

          foreach ($caseDetails as $detail) {
            //$value=$detail['diary_no'].'##'.$detail['order_date'].'##'.$detail['order_type'].'##'.$detail['id'].'##'.$detail['file_address'].'##'.$detail['order_type_short'].'##'.$detail['tbl_name'].'##'.$detail['d_no'].'##'.$detail['d_year'];
            $value = $detail['id'] . '##' . $detail['file_address'] . '##' . $detail['order_type_short'] . '##' . $detail['tbl_name'];
            $text = date('d-m-Y', strtotime($detail['order_date']));
            if ($detail['order_type_short'] == 'O') {
              $order_type = ' [ROP]';
            } else if ($detail['order_type_short'] == 'J') {
              $order_type = ' [Judgment]';
            }
            if ($detail['order_type_short'] == 'FO') {
              $order_type = ' [Final Order]';
            }
            echo '<option value="' . $value . '" >' . $text . ' ' . $order_type . ' ' . $detail['nc_display'] . '</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <br />
    <div id="divDetailsForROPUpload">
    </div>
  </form>

<?php } else { ?>
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <? echo "No data found";
}
  ?>

  <script>
    function confirmBeforeAdd() {
      var choice = confirm('Do you really want to List The Matter.....?');
      if (choice === true) {
        return true;
      }
      return false;
    }

    function getListedDetails(id) {
      usercode = $('#usercode').val();
      var CSRF_TOKEN = 'CSRF_TOKEN';
      var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
      //alert(usercode);
      if (id.value != 0) {
        $.ajax({
          url: "<?php echo base_url('JudgesLibrary/NeutralCitation/getListedDetailsForJudgmentFlag'); ?>",
          type: "POST",
          data: {
            id: id.value,
            usercode: usercode,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
          },
          beforeSend: function() {
            $('#divDetailsForROPUpload').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
          },
          success: function(result) {
            updateCSRFToken();
            $("#divDetailsForROPUpload").html(result);
          },
          error: function(xhr, status, error) {
            updateCSRFToken();
            console.error("AJAX Error:", status, error); // Log error
            alert("An error occurred while fetching details. Please try again.");
          }
        });
      }
    }
  </script>