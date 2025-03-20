<?= view('header') ?>
 
<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">

<script>
  $(function() {
    $("#cdob").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd-mm-yy",
      onSelect: function() {
        console.log('s');
      },
      onChangeMonthYear: function() {
        console.log('o');
      }
    })

    $("#crd").datepicker({
      dateFormat: "dd-mm-yy"
    }).val();
  });

  $(document).ready(function() {
    $('#aorc').focus();

    $("#aorc").change(function() {

      var aorc = $("#aorc").val();
      var dataString = 'tvap=' + aorc;
      $.ajax({
        type: "get",
        url: "<?php echo base_url('Record_room/Record/getadv_name'); ?>",

        data: dataString,
        cache: false,
        success: function(result) {
          if (result) {
            $('#aorn').val(result);
            $("#aorn").attr("disabled", "disabled");
            $('#cnf').focus();
          } else {
            $('#aorn').val(aorc + " AOR NOT FOUND");
            $("#aorn").attr("disabled", "disabled");
            $('#aorc').val(" ");
            $('#aorc').focus();
          }
        }
      });
    });

    $("#register").click(function() {
      var aorc = $("#aorc").val();
      var aorn = $("#aorn").val();
      var cnf = $("#cnf").val();
      var cnm = $("#cnm").val();
      var cnl = $("#cnl").val();
      var cfn = $("#cfn").val();
      var cpal1 = $("#cpal1").val();
      var cpal2 = $("#cpal2").val();
      var cpad = $("#cpad").val();
      var cpapin = $("#cppapin").val();
      var cppal1 = $("#cppal1").val();
      var cppal2 = $("#cppal2").val();
      var cppad = $("#cppad").val();
      var cppapin = $("#cppapin").val();
      var cdob = $("#cdob").val();
      var cpob = $("#cpob").val();
      var cn = $("#cn").val();
      var cmobile = $("#cmobile").val();
      var cx = $("#cx").val();
      var cxii = $("#cxii").val();
      var cug = $("#cug").val();
      var cpg = $("#cpg").val();
      var cein = $("#cein").val();
      var crd = $("#crd").val();
      var tvap = '';
      var flag = 0;

      tvap = aorc + ";" + aorn + ";" + cnf + ";" + cnm + ";" + cnl + ";" + cfn + ";" + cpal1 + ";" + cpal2 + ";" + cpad + ";" + cpapin + ";" + cppal1 + ";" + cppal2 + ";" + cppad + ";" + cppapin + ";" + cdob + ";" + cpob + ";" + cn + ";" + cmobile + ";" + cx + ";" + cxii + ";" + cug + ";" + cpg + ";" + cein + ";" + crd;

      // Returns successful data submission message when the entered information is stored in database.
      if (!aorc && !cnf && !cpal1 && !cpapin && !cppal1 && !cppapin && !cdob && !cpob & !crd) {
        alert("Please Enter Mandatory Values");
        return false;
      }

      {
        // alert(tvap);
        var dataString = 'tvap=' + tvap;
        // alert(dataString);
        // AJAX Code To Submit Form.
        $('#rslt').html("<img src='img/loading.gif' width='50px' hight='50px' />");
        $.ajax({
          type: "get",
          url: "<?php echo base_url('Record_room/Record/AorInsert'); ?>",

          data: dataString,
          cache: false,
          success: function(result) {
            $('#rslt').html("<img src='img/loading.gif' width='50px' hight='50px' />");
            alert(result);
            tvap = result;
            flag = tvap.length;
            if (flag == 28) {
              $("#aorc").val("");
              $("#aorn").val("");
              $("#cnf").val("");
              $("#cnm").val("");
              $("#cnl").val("");
              $("#cfn").val("");
              $("#cpal1").val("");
              $("#cpal2").val("");
              $("#cpad").val("");
              $("#cppapin").val("");
              $("#cppal1").val("");
              $("#cppal2").val("");
              $("#cppad").val("");
              $("#cppapin").val("");
              $("#cdob").val("");
              $("#cpob").val("");
              $("#cn").val("");
              $("#cmobile").val("");
              $("#cx").val("");
              $("#cxii").val("");
              $("#cug").val("");
              $("#cpg").val("");
              $("#cein").val("");
              $("#crd").val("");
              $('#rslt').html(result);

            }
            $("#aorc").focus();
          }
        });
      }
      return false;
    });
  });

  function validateMobileLength(input) {
    const maxLength = 10;
    if (input.value.length > maxLength) {
      input.value = input.value.slice(0, maxLength);
    }
  }
</script>
</head>

<body>
  <?php {
  ?><section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header heading">
                <div class="row">
                  <div class="col-sm-10">
                    <h3 class="card-title">Registration >>&nbsp; Advocate Clerk</h3>
                  </div>
                  <div class="col-sm-2">
                    <div class="custom_action_menu">
                      <button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                      <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen   " aria-hidden="true"></i>
                      </button>
                      <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <br><br>
              <div class="row">
                <div class="col-md-12">
                  <div class="container">
                    <div class="panel panel-info">
                      <div class="panel-heading">

                        <h4><strong><span class="fas fa-search "></span>&nbsp; Registration >>&nbsp; Advocate Clerk</strong></h4>

                      </div>
                      <div class="panel-body" id="frm">
                        <form class="form-horizontal" role="form" name="form1" autocomplete="off" id="fid">
                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="atitle">AOR/Firm Code *</label>
                            <div class="col-sm-2"><input class="form-control" name="aorc" type="number" id="aorc" placeholder="Code"></div>
                            <div class="col-sm-6"><input class="form-control" name="aorn" type="text" id="aorn" placeholder="Name"></div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Name *</label>
                            <div class="col-sm-2"><input class="form-control " name="cnf" type="text" id="cnf" placeholder="First"> </div>
                            <div class="col-sm-2"><input class="form-control " name="cnm" type="text" id="cnm" placeholder="Middle"> </div>
                            <div class="col-sm-2"><input class="form-control " name="cnl" type="text" id="cnl" placeholder="Last"> </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Father Name *</label>
                            <div class="col-sm-6"> <input class="form-control " name="cfn" type="text" id="cfn" placeholder="Father Name"> </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Present Address</label>
                            <div class="col-sm-3"><input class="form-control " name="cpal1" type="text" id="cpal1" placeholder="Address Line1"></div>
                            <div class="col-sm-3"><input class="form-control " name="cpal2" type="text" id="cpal2" placeholder="Address Line2"></div>
                            <div class="col-sm-2"><input class="form-control " name="cpad" type="text" id="cpad" placeholder="District"></div>
                            <div class="col-sm-2"><input class="form-control " name="cpapin" type="number" maxlength="6" id="cpapin" placeholder="Pincode"></div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Permanent Address</label>
                            <div class="col-sm-3"><input class="form-control " name="cppal1" type="text" id="cppal1" placeholder="Address Line1"></div>
                            <div class="col-sm-3"><input class="form-control " name="cppal2" type="text" id="cppal2" placeholder="Address Line2"></div>
                            <div class="col-sm-2"><input class="form-control " name="cppad" type="text" id="cppad" placeholder="District"></div>
                            <div class="col-sm-2"><input class="form-control " name="cppapin" type="number" maxlength="6" id="cppapin" placeholder="Pincode"></div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Date of Birth</label>
                            <div class="col-sm-2">
                              <input class="form-control" name="cdob" type="date" id="cdob">
                            </div>
                            <label class="control-label col-sm-2" for="anumber">Age</label>
                            <div class="col-sm-2">
                              <input class="form-control" name="cage" type="number" maxlength="3" id="cage" placeholder="Age">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Place of Birth</label>
                            <div class="col-sm-3"> <input class="form-control " name="cpob" type="text" id="cpob" placeholder="Birth Place"> </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Nationality</label>
                            <div class="col-sm-2">
                              <input class="form-control " name="cn" type="text" id="cn" placeholder="Nationality" value="INDIAN" disabled>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Mobile Number</label>
                            <div class="col-sm-2">
                              <input class="form-control " name="cmobile" type="number" maxlength="10" id="cmobile" placeholder="mobile" oninput="validateMobileLength(this)">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Educational Qualifications</label>
                            <div class="col-sm-2"><input class="form-control " name="cx" type="text" id="cx" placeholder="X"></div>
                            <div class="col-sm-2"><input class="form-control " name="cxii" type="text" id="cxii" placeholder="XII"></div>
                            <div class="col-sm-2"><input class="form-control " name="cug" type="text" id="cug" placeholder="UG"></div>
                            <div class="col-sm-2"><input class="form-control " name="cpg" type="text" id="cpg" placeholder="PG"></div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">New Icard No. *</label>
                            <div class="col-sm-2">
                              <input class="form-control " name="cein" type="number" id="cein" placeholder="ICard Number">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="control-label col-sm-2" for="anumber">Registration Date:</label>
                            <div class="col-sm-2">
                              <input class="form-control" name="crd" type="date" id="crd">
                            </div>
                          </div>

                          <div class="form-group row">
                            <div class="col-sm-offset-2 col-sm-10">
                              <button type="submit" class="btn btn-info" name="submit" id="register" onclick="">
                                <i class="fas fa-plus"></i> Register
                              </button>
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
        <?php if (isset($success_message)) : ?>
          <div class="alert alert-success" role="alert">
            <?= $success_message ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php

  }
  ?>
   <?=view('sci_main_footer') ?>