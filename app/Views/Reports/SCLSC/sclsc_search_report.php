<?= view('header') ?>
 
<style>
    .row {
        margin-right: 15px;
        margin-left: 15px;
        margin-top: -3px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10"> <h3 class="card-title">SLCSC Search</h3></div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>

                                    <?php  //echo $_SESSION["captcha"];
                                    $attribute = array('class' => 'form-horizontal','name' => 'sclsc_search_report', 'id' => 'sclsc_search_report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <div class="row">

                                      <div class="col-md-4">
                                            <div class="form-group row">
                                                <!--<label for="c_type" class="col-sm-5 col-form-label">Select Case Type:</label>-->
                                                <label for="c_type">Select Case Type:</label>
                                                <div class="col-sm-7">
                                                    <select id="c_type" name="c_type" class="form-control">
                                                        <option value="">Select State</option>
                                                        <option value="A">All</option>
                                                        <option value="C">Civil</option>
                                                        <option value="R">Criminal</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <!--<label for="status" class="col-sm-3 col-form-label">Select Status:</label>-->
                                                <label for="status">Select Status:</label>
                                                <div class="col-sm-9">
                                                    <div class="radio">
                                                        <label><input checked type="radio" name="status" value="A"/>All</label>
                                                        <label><input type="radio" name="status" value="P"/>Pending</label>
                                                        <label><input type="radio" name="status" value="D"/>Disposed of</label>
                                                        <label><input type="radio" name="status" value="PD"/>Pending-Defective</label>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" id="submit">Search</button>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <?php form_close();?>


                                    <div id="report_result"></div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            $('#sclsc_search_report').on('submit', function () {
                var c_type = $("#c_type").val();
                var status = $("input[name='status']:checked").val();

                if (c_type.length == 0) {
                    alert("Please select case type");
                    $("#caveat_number").focus();
                    validationError = false;
                    return false;
                }else if (status.length == 0) {
                    alert("Please select Status");
                    $("#caveat_year").focus();
                    validationError = false;
                    return false;
                }

                if ($('#sclsc_search_report').valid()) {
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if(validateFlag){
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Reports/SCLSC/Report/SCLSC_pending_report'); ?>",
                            data: form_data,
                            beforeSend: function () {
                                $('.dak_submit').val('Please wait...');
                                $('.dak_submit').prop('disabled', true);
                            },
                            success: function (data) {
                                updateCSRFToken();
                                var resArr = data.split('@@@');
                                if (resArr[0] == 1) {
                                    $('.alert-error').hide();
                                    $(".form-response").html("");
                                    $('#report_result').html(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    $('#div_result').html('');
                                    $('.alert-error').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                }
                            },
                            error: function() {
                                updateCSRFToken();
                                $('#report_result').html('');
                            }
                        });
                        return false;
                    }
                } else {
                    return false;
                }
            });
        });
        </script>