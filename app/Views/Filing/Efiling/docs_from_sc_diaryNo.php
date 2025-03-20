<?=view('header'); ?>
 
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
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Efiling >> Admin</h3>
                            </div>
                            <div class="col-sm-2">
                              
                            </div>
                        </div>
                    </div>
                    <?=view('Filing/Efiling/Efiling_breadcrumb');?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="box-title  pl-4">Docs by Diary Number</h5>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
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

                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>


                                    <div class="row">
                                        <div class="col-md-2"></div>
                                      <div class="col-md-4 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_number" class="col-sm-5 col-form-label">Diary No</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_year" class="col-sm-5 col-form-label">Diary Year</label>
                                                <div class="col-sm-5">
                                                    <?php $year = 1950;
                                                    $current_year = date('Y');
                                                    ?>
                                                    <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                            <option><?php echo $x; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                         </div>
                                         </div>


                                    <center>
                                        <button type="submit" class="btn btn-primary" id="submit">Search</button>
                                    </center>

                                    <?php form_close();?>
                                    <br/>
                                    <div id="result_data"> </div>
                                    <center><span id="loader"></span> </center>
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
        $('#report').on('submit', function () {
            var diary_number = $("#diary_number").val();
            var diary_year = $("#diary_year").val();

                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    $("#diary_number").focus();
                    validationError = false;
                    return false;
                }
                else if (diary_year.length == 0) {
                    alert("Please select to date.");
                    $("#diary_year").focus();
                    validationError = false;
                    return false;
                }

            if ($('#report').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $('#result_data').html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Efiling/docs_from_sc_diary_no'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (response) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = response.split('@@@');
                            if (resArr[0] == 1) {
                                $('#result_data').html(resArr[1]);
                            }else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            $('#result_data').html('');
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;

                }
            } else {
                return false;
            }
        });

    </script>