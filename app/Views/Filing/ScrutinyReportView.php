<?= view('header') ?>
<style>
  div.dataTables_wrapper div.dataTables_filter label {
    display: flex;
    justify-content: end;
  }

  div.dataTables_wrapper div.dataTables_filter label input.form-control {
    width: auto !important;
    padding: 4px;
  }
</style>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header heading">
            <div class="row">
              <div class="col-sm-10">
                <h3 class="card-title">Scrutiny >> Registration</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">View</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">

                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Diary No.</label>
                            <input type="text" id="t_h_cno" name="t_h_cno" class="form-control" size="5" value="<?php echo $diary_no; ?>" />
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="">Diary Year</label>

                            <?php $currently_selected = date('Y');
                            $earliest_year = 1950;
                            $latest_year = date('Y');
                            print '<select id="t_h_cyt">';
                            foreach (range($latest_year, $earliest_year) as $i) {
                              print '<option value="' . $i . '"';
                              if ($diary_year) {
                                if ($i == $diary_year) {
                                  print 'selected="selected"';
                                }
                              } else {
                                if ($i == date('Y')) {
                                  print 'selected="selected"';
                                }
                              }
                              print '>' . $i . '</option>';
                            }
                            print '</select>'; ?>
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                          </div>
                        </div>
                      </form>
                      <div id="dv_res1"> </div>
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
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "dom": 'Bfrtip',
    "bProcessing": true,
    "buttons": ["excel", "pdf"]
  });
  $(document).on("focus", ".dtp", function() {
    $('.dtp').datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1950:2050'
    });

  });

  $(document).on("click", "#btn1", function() {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    var d_no = document.getElementById('t_h_cno').value;
    var d_yr = document.getElementById('t_h_cyt').value;

    $.ajax({
      url: "<?php echo base_url('Filing/ScrutinyReport/get_lower_report'); ?>",
      method: 'POST',
      beforeSend: function() {
        $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
      },
      data: {
        d_no: d_no,
        d_yr: d_yr,
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
      cache: false,
      success: function(response) {
        updateCSRFToken();
        $('#dv_res1').html(data);
           var casetype_id = $("#hd_casetype_id").val();
            if(casetype_id=='5'||casetype_id=='6'||casetype_id=='17'||casetype_id=='24'||casetype_id=='32'||casetype_id=='33'||casetype_id=='34'||casetype_id=='35'||casetype_id=='40'){
                //find_and_set_da(d_no,d_yr);
            }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        updateCSRFToken();
        alert("Error: " + jqXHR.status + " " + errorThrown);
      }
    });
  });

  function save_verification(dno) {

    var r = confirm("Are you Verfied this case");
    if (r == true) {
      if ($("#rremark_" + dno).val() == 'R' && $("#reject_remark_" + dno).val() == "") {
        alert("Please Entry Valid Rejection Reason");
        return false;
      }
      var rremark = $("#rremark_" + dno).val();
      var rejection_remark = $("#reject_remark_" + dno).val();
      var cl_date = $("#" + dno).data('cl_date');
      var dataString = "dno=" + dno + "&rremark=" + rremark + "&rejection_remark=" + rejection_remark + "&cl_date=" + cl_date;
      var CSRF_TOKEN = 'CSRF_TOKEN';
      var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('ManagementReports/DA/DA/response_case_remarks_verification'); ?>",
        data: dataString + '&' + CSRF_TOKEN + '=' + CSRF_TOKEN_VALUE,
        cache: false,
        success: function(data) {
          // alert(data);
          updateCSRFToken();
          if (data == 1) {
            var r = "#" + dno;
            var row = "<tr><td colspan='7' style='text-align:center;color:red;'>DN : " + dno + " Verified Successfully</td></tr>";
            $(r).replaceWith(row);
          } else {
            alert("Not Verified.");
          }
        }
      }).fail(function() {
        updateCSRFToken();
        alert("ERROR, Please Contact Server Room");
      });
    } else {

      txt = "You pressed Cancel!";
    }

  }
</script>