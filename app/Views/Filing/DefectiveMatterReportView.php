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
                  <h4 class="basic_heading">Defective Paperbook Report</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">
                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Record Entered Between Dates</label>
                            <input class="dtp form-control" type="text" value="<?php print $dtd; ?>" name="dtd1" id="dtd1" size="10" readonly="readonly">
                          </div>
                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">And</label>
                            <input class="dtp form-control" type="text" value="<?php print $dtd; ?>" name="dtd2" id="dtd2" size="10" readonly="readonly">
                          </div>
                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                          </div>
                        </div>
                      </form>
                      <div id="result"></div>
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
  $(document).on("focus", ".dtp", function() {
    $('.dtp').datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1950:2050'
    });
  });

  $(document).on("click", "#btn1", function() {
    var $btn = $(this);

    // Check if button is already in processing state
    if ($btn.data('processing')) {
      return false;
    }

    // Set processing state
    $btn.data('processing', true);
    $btn.prop('disabled', true);
    $btn.html('Processing...');

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('Filing/DefectiveMatter/GetDefectiveReport'); ?>",
        beforeSend: function(xhr) {
          $("#result").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        },
        data: {
          type: $("#se_re_val").val(),
          dtd1: $("#dtd1").val(),
          dtd2: $("#dtd2").val(),
          CSRF_TOKEN: CSRF_TOKEN_VALUE
        }
      })
      .done(function(msg) {
        updateCSRFToken();
        $("#result").html(msg);
        
      })
      .fail(function() {
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room");
      })
      .always(function() {
        setTimeout(function() {
          $btn.data('processing', false);
          $btn.prop('disabled', false);
          $btn.html('Submit');
        }, 500); // 500ms delay
      });
  });
</script>