<?php
if (!empty($caseDetails) && is_array($caseDetails) && count($caseDetails) > 0) { ?>

  <!-- <form id="frmReplaceRop" enctype="multipart/form-data" action="" method="post"> -->
    <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />

    <div class="row">
      <div class="col-sm-12 col-md-6 mb-6">
        <label for="diaryNumber"><span
            class="text-primary">Case No: </span> <?= $caseDetails[0]['reg_no_display'] ?>&nbsp;(D
          No.<?= $caseDetails[0]['diary_no'] ?>)</label>
      </div>
      <div class="col-sm-12 col-md-6 mb-6">
        <label for="causeTitle"><span
            class="text-primary">Causetitle : </span> <?= $caseDetails[0]['pet_name'] ?>
          Vs. <?= $caseDetails[0]['res_name'] ?></label>
      </div>
    </div>
    <div class="col-sm-12 form-group">

      <label for="diaryYear" class="col-sm-2 control-label text-primary">Order Date</label>
      <div class="col-sm-4">
        <select class="form-control" id="listingDates" name="listingDates" placeholder="listingDates"
          onchange="getListedDetails(this);">
          <option value="0">Select Listing Date</option>
          <?php
          foreach ($caseDetails as $detail) {
            $causeTitle = $detail['pet_name'] . ' Vs. ' . $detail['res_name'];
            $courtNo = "0";
            if ($detail['courtno'] == 21) {
              $courtNo = "R1";
            } elseif ($detail['courtno'] == 22) {
              $courtNo = "R2";
            } else {
              $courtNo = $detail['courtno'];
            }
            $value = $detail['diary_no'] . '#' . $detail['next_dt'] . '#' . $detail['roster_id'] . '#' . $detail['courtno'] . '#' . $detail['item_number'];
            $text = date('d-m-Y', strtotime($detail['next_dt'])) . " in Court " . $courtNo . ' as Item Number ' . $detail['item_number'];
            echo '<option value="' . $value . '" >' . $text . '</option>';
          }
          ?>
        </select>
      </div>
    </div>
    <br />
    <div id="divDetailsForROPUpload">

    </div>
  <!-- </form> -->

<?php } else {
  echo 'Record Not Found.';
}
?>
<script>
  function getListedDetails(id) {
    usercode = $('#usercode').val();
    //alert(usercode);
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    if (id.value != 0) {
      $.post("<?= base_url('Court/CourtMasterController/getListedDetails'); ?>", {
          id: id.value,
          usercode: usercode,
          action_type: 'QR',
          CSRF_TOKEN: CSRF_TOKEN_VALUE
        })
        .done(function(result) {
          $("#divDetailsForROPUpload").html(result);
          updateCSRFToken();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          updateCSRFToken();
          console.error("Request failed: " + textStatus + ", " + errorThrown);
          alert("An error occurred while fetching the details. Please try again.");
        });
    }
  }
</script>