<?= view('header') ?>
 
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10"> <h3 class="card-title">Caveat >> Caveat and Diary matched but not Linked</h3></div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
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

                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'caveat_diary_matched_search', 'id' => 'caveat_diary_matched_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <h3>Caveat and Diary matched but not Linked   <button type="submit" class="btn btn-primary" id="submit">Submit</button></h3>

                                    <br/>
                                    <div class="row d-none">
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Caveat No</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="caveat_number" name="caveat_number" value="1" placeholder="Enter Caveat No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Caveat Year</label>
                                                <div class="col-sm-5">
                                                    <?php $year = 1950;
                                                    $current_year = date('Y');
                                                    ?>
                                                    <select name="caveat_year" id="caveat_year" class="custom-select">
                                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                            <option><?php echo $x; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <?php form_close();?>

                                    <div id="div_result"></div>
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
        $('#caveat_diary_matched_search').on('submit', function () {
            var caveat_number = $("#caveat_number").val();
            var caveat_year = $("#caveat_year").val();

            if (caveat_number.length == 0) {
                alert("Please enter caveat number");
                $("#caveat_number").focus();
                validationError = false;
                return false;
            }else if (caveat_year.length == 0) {
                alert("Please select caveat year");
                $("#caveat_year").focus();
                validationError = false;
                return false;
            }

            if ($('#caveat_diary_matched_search').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('Caveat/Caveat_diary_matched/get_caveat_diary_matched')?>",
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
                                $('#div_result').html(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('#div_result').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function(xhr) {
                            updateCSRFToken();
                            //alert("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
        </script>
  