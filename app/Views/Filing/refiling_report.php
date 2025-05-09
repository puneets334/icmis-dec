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
                  <h4 class="basic_heading">Datewise Refiling Report</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">
                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Refiling Date:</label>
                            <input type="text" name="from_dt1" id="from_dt1" class="dtp form-control" maxlength="10" autocomplete="off" size="9" />
                          </div>
                          <div class="col-12 text-center">
                            <input type="button" id="btnGetDiaryList" value="Show" class="btn btn-primary mb-4" />
                          </div>
                        </div>
                      </form>
                      <div id="dv_res1"></div>
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
  $(document).ready(function() {
    // Initialize datepicker
    $('.dtp').datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1950:2050'
    });

   $("#btnGetDiaryList").click(function() {
    var $btn = $(this); // Cache the button element
    var dateFrom = $('#from_dt1').val().trim();

    if (dateFrom === '') {
        alert('Please enter the Refiling Date.');
        $('#from_dt1').focus();
        return; // Stop execution
    }

    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $btn.prop("disabled", true); // Disable the button

    $.ajax({
        url: '<?= base_url('Filing/RefilingReport/GetRefilingReport') ?>',
        type: "POST",
        data: {
            dateFrom: dateFrom,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        beforeSend: function() {
            $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
        },
        success: function(response) {
            updateCSRFToken();
            $("#dv_res1").html(response);
        },
        error: function() {
            updateCSRFToken();
            alert('Error occurred while fetching the report.');
        },
        complete: function() {
            $btn.prop("disabled", false); // Re-enable the button
        }
    });
});

  });

  $(document).on("click", "#prnnt1", function() {
    var prtContent = $("#divprint").html();
    var temp_str = prtContent;
    var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
  });
</script>