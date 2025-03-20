<?= view('header') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header heading">
            <div class="row">
              <div class="col-sm-10">
                <h3 class="card-title">Filing Trap</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">Defective Paperbook Bulk Update Form</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" id="ucode" name="ucode" value="<?php echo $_SESSION['login']['usercode'];?>">
                        <div class="row">
                          <div class="col-md-3">
                            <label for="dno"><b>From Sequence No.</b></label>
                            <input type="text" id="seqno1" name="seqno1" maxlength="20" size="20" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="dyr"><b>To Sequence No.</b></label>
                            <input type="text" id="seqno2" name="seqno2" maxlength="20" size="20" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="section"><b>Rack No.</b></label>
                            <input type="text" id="rackno" name="rackno" maxlength="10" size="10" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="courtfee"><b>Shelf No.</b></label>
                            <input type="text" id="shelfno" name="shelfno" maxlength="10" size="10" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-12 text-center">
                            <input type="button" name="update" class="btn btn-primary mt-5" value="Update" onclick="update_data()" />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  $(document).on("blur", "#seqno2", function() {
    var sequenceno1 = $('#seqno1').val();
    var sequenceno2 = $('#seqno2').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if ($('#seqno1').val() == '') {
      alert("Please enter Sequenc no. 1");
      $('#seqno1').focus();
      return false;
    }
  });


  function update_data() {
    var sequence1 =  Number($('#seqno1').val());
    var sequence2 =  Number($('#seqno2').val());
    var rackno = $('#rackno').val();
    var shelfno = $('#shelfno').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    if (sequence1 == '') {
      alert("Please enter Sequence no. 1");
      $('#seqno1').focus();
      return false;
    }
    if (sequence2 == '') {
      alert("Please enter Sequence no. 2");
      $('#seqno2').focus();
      return false;
    }
    if (sequence1 > sequence2) {
      alert("Seqence no. 1 should be less or equal to seqeuence no. 2")
      $('#seqno1').focus();
    }
    if (rackno == '') {
      alert("Please enter rack no.");
      $('#rackno').focus();
      return false;
    }
    if (shelfno == '') {
      alert("Please enter shelf no.");
      $('#shelfno').focus();
      return false;
    }


    var xmlhttp;
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var vcal = xmlhttp.responseText;
        if (vcal == 1) {
          alert("Record Updated Successfully ");
          document.getElementById('seqno1').focus();
          document.getElementById('seqno1').value = "";
          document.getElementById('seqno2').value = "";
          document.getElementById('rackno').value = "";
          document.getElementById('shelfno').value = "";
          location.reload();
        }
        else {
          alert(vcal);
        }
      }
    }

    var url = "<?php echo base_url('Filing/DefectiveMatter/DefectUpdateBulk'); ?>?controller=BU" + "&seqno1=" + sequence1 + "&seqno2=" + sequence2 + "&rackno=" + rackno + "&shelfno=" + shelfno + "&CSRF_TOKEN=" + CSRF_TOKEN_VALUE;

    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
  }
</script>