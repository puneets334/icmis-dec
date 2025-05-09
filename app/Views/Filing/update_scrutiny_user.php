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
                  <h4 class="basic_heading">Scrutiny User Updation</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="POST" id="frm" name="frm" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">
                          <div class="col-md-3">
                            <label for="dno"><b>Diary No.</b></label>
                            <input type="text" id="dno" name="dno" onChange='gct(1);' class="form-control numbersonly" />
                          </div>

                          <div class="col-md-3">
                            <label for="dno"><b>Diary Year</b></label>
                            <input type="text" id="dyr" name="dyr" value="<?php echo date("Y"); ?>" onChange='gct(1);' class="form-control" />
                          </div>

                          <div class="col-12 text-center">
                            <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div id="txtHint"><b></b></div>
                    <div id="result" class="mt-4"></div>
                    <div id="result1" class="mt-4"></div>
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
<script type="text/javascript">
  $('#btn1').prop('disabled',true);
  function gct(id) {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    document.getElementById('txtHint').innerHTML = "";
    if (id == 1)
      var dno = document.getElementById('dno').value;
    var dyr = document.getElementById('dyr').value;

    if ((dno == '')) {
      alert("Diary no can't be blank");
      document.getElementById('dno').focus();
      return;
    }
    if (dyr == '') {
      alert("Diary year can't be blank");
      document.getElementById('dyr').focus();
      return;
    }

    $('#btn1').prop('disabled',true);

    $.ajax({
      type: 'POST',
      url: '<?= base_url('Filing/UpdateScrutinyUser/GetCauseTitle') ?>',
      cache: false,
      async: true,
      data: {
        d_no: $('#dno').val(),
        d_yr: $('#dyr').val(),
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfHash);
      },
      success: function(data, status) {
        updateCSRFToken();
        $('#result').html(data);
        $('#btn1').prop('disabled',false);
      },
      error: function(xhr) {
        updateCSRFToken();
        $('#btn1').prop('disabled',false);
        alert("Error: " + xhr.status + " " + xhr.statusText);
      }
    });

  }


  $(document).on("click", "#btn1", function() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
    var dno1 = $('#dno').val();
    var dyr1 = $('#dyr').val();
    var d1 = dno1 + dyr1;

    if (dno1 == '') {
      alert("Diary no can't be blank");
      $('#dno').focus();
      return;
    }
    if (dyr1 == '') {
      alert("Diary year can't be blank");
      $('#dyr').focus();
      return;
    }

    $.ajax({
      url: "<?php echo base_url('Filing/UpdateScrutinyUser/update_info'); ?>",
      method: 'POST',
      beforeSend: function() {
        $('#result1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
      },
      data: {
        d1: dno1 + dyr1,
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
      cache: false,
      success: function(response) {
        updateCSRFToken();
        $('#result1').html(response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        updateCSRFToken();
        alert("Error: " + jqXHR.status + " " + errorThrown);
      }
    });
  });
</script>