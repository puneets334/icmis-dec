<?= view('header') ?>


<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12  mt-6">
        <div class="card">
          <div class="card-header heading">

            <div class="row">
              <div class="col-sm-10">
                <h3 class="card-title">Advocate On Record >> AOR Pending Matters With Orders and Judgement</h3>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card mt-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class=""  style="width:100%">
                        <div class="panel panel-info">
                          <div class="panel-body" id="frm">
                            <form class="form-horizontal" role="form" name="form1" autocomplete="off" id="fid">
                              <?= csrf_field() ?>
                              <div class="form-group row">
                                <label class="control-label col-sm-2" for="atitle">AOR Name *</label>
                                <div class="col-sm-6">
                                  <select name="aor" id="aor" class="form-control" onchange="resetdiv();">
                                    <option value="">Select an option</option>
                                    <?php foreach ($aor_list as $aor): ?>
                                      <option value="<?php echo esc($aor['aor_code']); ?>">
                                        <?php echo esc($aor['aor_code']); ?>:<?php echo esc($aor['adv_name']); ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>

                                <div class="col-sm-2">
                                  <button type="button" class="btn btn-primary" name="submit" onclick="fetch_data();"
                                    id="submit_btn">
                                    <i class="fas fa-search"></i> Search
                                  </button>
                                </div>

                              </div>
                              <div class="form-group row justify-content-center">
                                <div class="col-sm-12 text-center">
                                  <button type="button" class="btn btn-primary" name="submit" id="download_b" onclick="download()">
                                    <i class="fas fa-envelope"></i> Send E-mail / SMS to AOR
                                  </button>
                                </div>
                              </div>

                            </form>
                          </div>



                          <div id="getrecordtable">

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
      </div>
    </div>
  </div>
  <?php if (isset($success_message)): ?>
    <div class="alert alert-success" role="alert">
      <?= $success_message ?>
    </div>
  <?php endif; ?>
</section>
<script>
  function resetdiv() {
    $('#download_b').hide();
  }


  // function download() 
  // {

  //   var aor_id = document.getElementById('aor').value;
  //   alert(aor_id);

  //   var a = document.body.appendChild(
  //     document.createElement("a")
  //   );

  //   var myBlob = new Blob([document.getElementById("content").innerHTML], { type: "text/html" });
  //   // (B) FORM DATA
  //   var data = new FormData();
  //   data.append("upfile", myBlob);
  //   data.append("aor_code", aor_id);

  //   // (C) AJAX UPLOAD TO SERVER
  //   var formData = new FormData();
  //   formData.append("upfile", myBlob);
  //   formData.append("aor_code", aor_id);
  //   $.ajax({
  //     url: "3b-upload.php",
  //     type: 'POST',
  //     cache: false,
  //     async: false,
  //     data: formData,
  //     contentType: false,
  //     processData: false,
  //     success: function (data) {
  //       alert(data);
  //     },
  //     error: function () {
  //       alert("error");
  //     }
  //   });
  // }


  function download() {
    var aor_id = document.getElementById('aor').value;
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    // var content = document.getElementById('content').innerHTML;

    // // Create a Blob with the HTML content
    // var myBlob = new Blob([content], {type: "text/html"});
    // console.log(myBlob);

    // // Create a FormData object
    var formData = new FormData();

    formData.append('aor_code', aor_id);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('Record_room/advt_on_record/sendMail'); ?>",
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
      },
      success: function(data) {
        $('#getrecordtable').html(data);
        updateCSRFToken();
        $('#download_b').show();
      },
      error: function() {
        alert('Error');
      }
    });
  }



  $('#download_b').hide();

  function fetch_data() {

    // var url = 'Record_room/advt_on_record/getRecord';
    $('#record').hide();
    var aor = $('#aor').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    if (aor != "") {

      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Record_room/advt_on_record/getRecord'); ?>",
        data: {
          aor: aor,
          CSRF_TOKEN: CSRF_TOKEN_VALUE,
        },

        // cache: false,
        success: function(data) {
          $('#getrecordtable').html(data);
          updateCSRFToken();
          $('#download_b').show();
        },
        error: function() {
          alert('Error');
        }
      });
    } else {
      alert('Please select AOR name ....');
      $('#aor').focus();
      return false;
    }

  }
</script>