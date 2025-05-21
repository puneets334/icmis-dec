<?= view('header') ?>

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

    h5 {
        margin: 0 0;
    }

    /* .nav-breadcrumb li a {
        background-image: none;
        background-repeat: no-repeat;
        background-position: 100% 3px;
        position: relative;
    } */

    /* .nav-breadcrumb li a,
    .nav-breadcrumb li a:link,
    .nav-breadcrumb li a:visited {
        margin-left: -70px;
    } */
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Case Status</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
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
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'case_status_form', 'id' => 'case_status_form', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                             <?php if(empty($_REQUEST['diaryno'])){?>   
                                    <?php
                                    echo component_html();
                                    //echo $case_details=get_diary_case_type(2,1242,2023); //ok
                                    ?>


                                    <center>
                                        <button type="submit" class="btn btn-primary" id="submit">Search</button>
                                    </center>
                                    <br />
                                    <?php }?>
                                    <div id="case_status_load_data"> </div>
                                    <?php form_close(); ?>

                      

                                    <center><span id="loader"></span> </center>
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
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
                $('#case_year').prop('selectedIndex', 1);
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
                $('#diary_year').prop('selectedIndex', 1);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#case_status_form').on('submit', function() {
            
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year = $('#diary_year :selected').val();

            var case_type = $('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_number_to = '';
            var case_year = $('#case_year :selected').val();

            if (search_type == 'D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                } else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            } else if (search_type == 'C') {

                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                } else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                } else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#case_status_form').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                    $('.alert-error').hide();
                    //$("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Common/Case_status/case_status'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $("#case_status_load_data").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            updateCSRFToken();
                            //$("#loader").html('');
                            $('#case_status_load_data').html(data);
                            /*
                             updateCSRFToken();
                             var resArr = data.split('@@@');
                             alert(resArr);
                             if (resArr[0] == 1) {
                                 $('.alert-error').hide();
                                 $(".form-response").html("");
                                 $('#case_status_load_data').html(resArr[1]);
                             } else if (resArr[0] == 3) {
                                 $('#case_status_load_data').html('');
                                 $('.alert-error').show();
                                 $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                             }*/
                        },
                        error: function() {
                            updateCSRFToken();
                            $('#case_status_load_data').html('');
                           // alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        }); 
    });

    // ONLOAD 
    <?php if(!empty($_REQUEST['diaryno'])){?>       
        getCaseStatusBYLoad();
    <?php }?>  
        function getCaseStatusBYLoad()
        {
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "GET",
                    url: "<?php echo  base_url('Common/Case_status/case_status_by_diaryno')   ?>",
                    data: {diaryno: '<?php echo (!empty($_REQUEST['diaryno'])) ?  $_REQUEST['diaryno'] : '';?>' },
                    beforeSend: function() {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    success: function(data) {
                        updateCSRFToken();
                        $("#loader").html('');
                        $('#case_status_load_data').html(data);
                        
                    },
                    error: function() {
                        updateCSRFToken();
                        $('#case_status_load_data').html('');
                        alert('Something went wrong! please contact computer cell');
                    }
                });
        }


</script>
 