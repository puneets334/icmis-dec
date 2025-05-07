<?= view('header'); ?>

<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }

    .card-header {
        padding: 5px;
    }

    h4 {
        line-height: 0px;
    }

    .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    }

    .nav-breadcrumb li a,
    .nav-breadcrumb li a:link,
    .nav-breadcrumb li a:visited {
        margin-left: -70px;
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
                                <h3 class="card-title">Filing >> DAK >> Case Search</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Search by Document Number / Year</h4>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>



                                    <div class="row">

                                        <div class="col-md-5 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_number" class="col-sm-5 col-form-label">Document No</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Document No">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_year" class="col-sm-5 col-form-label">Document Year</label>
                                                <div class="col-sm-7">
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
                                    <br />
                                    <div id="search_load_data"> </div>
                                    <?php form_close(); ?>






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
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#search').on('submit', function() {
            var diary_number = $("#diary_number").val();
            var diary_year = $('#diary_year :selected').val();
            if (diary_number.length == 0) {
                alert("Please enter diary number");
                validationError = false;
                return false;
            } else if (diary_year.length == 0) {
                alert("Please select diary year");
                validationError = false;
                return false;
            }


            if ($('#search').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/DAK/get_search_doc'); ?>",
                        data: form_data,
                        beforeSend: function() {
                           $('#submit').prop('disabled',true);
                            // $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            $('#submit').prop('disabled',false);

                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#search_load_data').html(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('#search_load_data').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            $('#submit').prop('disabled',false);

                            $('#search_load_data').html('');
                            alert('Something went wrong! please contact computer cell');
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
<script>
    function update_case() {
        var validateFlag = false;
        var case_diary = $('#case_diaryno').val();
        var case_info = $('#case_info').val();
        //alert('case_diary='+case_diary + 'case_info='+case_info)
        if (case_diary.length != 0) {
            if (case_info.length == 0) {
                alert("Enter Case Sensitive Information");
                $('#case_info').focus();
                validationError = false;
                return false;
            } else {
                validateFlag = true;
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Sensitive_info/update_case'); ?>",
                        cache: false,
                        async: true,
                        data: {
                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                            case_diary: case_diary,
                            case_info: case_info
                        },
                        beforeSend: function() {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#sensitive_load_data').html("<center><span class='text-success'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</span><center>");
                                // location.reload();
                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html(resArr[1]);
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;

                }
            }
        } else {
            if (!alert("Enter Case Details")) {
                //$('#btn-restore').prop('disabled', true);
                // location.reload();
                $('#case_type').focus();
            }
        }
    }
</script>