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
                  <h4 class="basic_heading">File Dispatch Receive</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="POST" action="<?= site_url(uri_string()) ?>">
                        <?= csrf_field() ?>
                        <div class="row">

                          <div class="col-sm-12 col-md-3 mb-3" id="search_block">
                            <label for="">Search For</label>
                            <select class="form-control" name="stype" id="stype" onChange="f2()">
                              <option value="select_dno" selected>Diary No.</option>
                              <option value="all_dno"> All Matters</option>
                            </select>
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3 span_dno">
                            <label for="">Diary No.</label>
                            <input type="text" id="dno" maxlength="6" class="form-control" size="5" autofocus />
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3 span_dno">
                            <label for="">Year</label>
                            <input type="text" id="dyr" class="form-control" maxlength="4" size="4" value="<?php echo date('Y'); ?>" />
                          </div>

                          <div class="col-sm-12 col-md-3 mb-3">
                            <button type="submit" id="showbutton" class="quick-btn mt-26">SHOW</button>
                          </div>
                        </div>
                      </form>
                      <div id="newresult"></div>
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
  function f2() {
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    const selectElement = document.querySelector('#stype');
    const output = selectElement.options[selectElement.selectedIndex].value;
    const spanDnoElements = document.getElementsByClassName('span_dno');
    if (output === 'all_dno') {
      for (let i = 0; i < spanDnoElements.length; i++) {
        spanDnoElements[i].style.display = 'none';
      }
      $.ajax({
          type: 'POST',
          beforeSend: function(xhr) {
            $('#dv_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
          },
          url: "<?php echo base_url('Filing/IncompleteFDR/fetchIncompleteMatters'); ?>",
          data: {
            stype: output,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
          }
        })
        .done(function(msg) {
          updateCSRFToken();
          $("#newresult").html(msg);
        })
        .fail(function() {
          updateCSRFToken();
          alert("ERROR, Please Contact Server Room");
        });
    } else {
      for (let i = 0; i < spanDnoElements.length; i++) {
        spanDnoElements[i].style.display = 'inline';
      }
      document.getElementById('newresult').innerText = '';
    }
  }

  function f1() {
    selectElement = document.querySelector('#stype');
    output = selectElement.options[selectElement.selectedIndex].value;
    if (output == 'select_dno') {
      var diaryno, diaryyear;
      var regNum = new RegExp('^[0-9]+$');
      diaryno = $("#dno").val();
      diaryyear = $("#dyr").val()
      if (!regNum.test(diaryno)) {
        alert("Please Enter Diary No in Numeric");
        $("#dno").focus();
        return false;
      }
      if (!regNum.test(diaryyear)) {
        alert("Please Enter Diary Year in Numeric");
        $("#dyr").focus();
        return false;
      }
      if (diaryno == 0) {
        alert("Diary No Can't be Zero");
        $("#dno").focus();
        return false;
      }
      if (diaryyear == 0) {
        alert("Diary Year Can't be Zero");
        $("#dyr").focus();
        return false;
      }
      $.ajax({
          type: 'GET',
          beforeSend: function(xhr) {
            $("#newresult").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
          },
          data: {
            dno: diaryno,
            dyr: diaryyear,
            stype: output
          }
        })
        .done(function(msg) {
          $("#dv_content1").html(msg);
          document.getElementById('dno').innerText = diaryno;
          document.getElementById('dyr').innerText = diaryyear;
        })
        .fail(function() {
          alert("ERROR, Please Contact Server Room");
        });
    }
  }


  $("#sendSMS").click(function(e) {
    e.preventDefault();
    var usercode = $('#usercode').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
      url: "<?php echo base_url('PaperBook/PaperBooksSMS/sms_godown'); ?>",
      type: "POST",
      beforeSend: function() {
        $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
      },
      data: {
        usercode: usercode,
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
      cache: false,
      success: function(r) {

        if (r != 0) {
          alert('Message has been send.');
        } else {
          alert("There is some problem while sending message please contact computer cell...");
        }
        updateCSRFToken();
      },
      error: function() {
        alert('ERROR');
        updateCSRFToken();
      }
    });
  });
</script>