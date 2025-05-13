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
                <h3 class="card-title">Appearance Reports</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">Appearance List</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="post" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        
                        <p id="show_error"></p>

                        <div class="row">
                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="list_date">List Date</label>
                            <input type="text" size="7" class="dtp form-control" name='list_date' id='list_date' value="<?php echo date('d-m-Y'); ?>" readonly />
                          </div>


                          <div class="col-sm-12 col-md-3 mb-3">
                            <label for="courtno">Court No.</label>
                            <select class="ele form-control" name="courtno" id="courtno">
                              <option value="0">-Select-</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="21">Registrar Court</option>
                              <!-- <option value="21">21 (Registrar)</option> -->
                              <!-- <option value="22">22 (Registrar)</option> -->
                            </select>
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                          </div>

                        </div>
                      </form>

                    </div>
                    <div class="row col-md-12 m-0 p-0" id="result"></div>
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

    $("#result").html("");
    $('#show_error').html("");
    var list_date = $("#list_date").val();
    var courtno = $("#courtno").val();

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if (list_date.length == 0) {
      $('#show_error').append('<div class="sm alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
      return false;
    } else if (courtno == 0) {
      $('#show_error').append('<div class="sm alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
      return false;
    } else {


      $.ajax({
        url: "<?php echo base_url('Listing/Appearance/listProcess'); ?>",
        method: 'POST',
        beforeSend: function() {
          $('#result').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
          $('#btn1').attr('disabled','disabled');
        },
        data: {
          list_date: list_date,
          courtno: courtno,
          CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        cache: false,
        success: function(response) {
          updateCSRFToken();
          $('#result').html(response);
          $("#csrf_token").val(response.csrfHash);
          $("#csrf_token").attr('name', response.csrfName);
          $('#btn1').removeAttr('disabled');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          updateCSRFToken();
          alert('Records Not Found');
          $('#result').html('');
          $('#btn1').removeAttr('disabled');
         // alert("Error: " + jqXHR.status + " " + errorThrown);
        }
      });
    }
  });
</script>