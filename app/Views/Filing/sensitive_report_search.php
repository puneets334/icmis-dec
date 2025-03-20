<?=view('header'); ?>
 
    <style>
        .custom-radio{float: left; display: inline-block; margin-left: 10px; }
        .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
        .basic_heading{text-align: center;color: #31B0D5}
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
        .row {
             margin-right: 15px;
             margin-left: 15px;
         }
       /* .nav-breadcrumb li a {
            background-image: none;
            background-repeat: no-repeat;
            background-position: 100% 3px;
            position: relative;
        }
        .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
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
                                <h3 class="card-title">Filing >> Sensitive Search</h3>
                            </div>
                             <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>

                    <?php
                    $url_sensitive_info = $url_sensitive_report= '#';
                    $uri = current_url(true); ?>
                    <ul class="nav-breadcrumb">
                        <li>
                            <?php
                            if (($uri->getSegment(2) == 'Sensitive_info' && $uri->getSegment(3) != 'report')){
                                $ColorCode = 'background-color: #01ADEF';
                                $status_color = 'first active';
                                $url_sensitive_info = base_url('Filing/Sensitive_info');
                            }else{
                                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                $status_color = '';
                                $url_sensitive_info = base_url('Filing/Sensitive_info');
                            }
                            ?>
                            <a href="<?= $url_sensitive_info; ?>" class="<?php echo $status_color; ?>" style="z-index:2">Sensitive Information</a>
                        </li>

                        <li>
                            <?php
                            if (($uri->getSegment(2) == 'Sensitive_info' && $uri->getSegment(3) == 'report')) {
                                $ColorCode = 'background-color: #01ADEF';
                                $status_color = 'first active';
                                $url_sensitive_report = base_url('Filing/Sensitive_info/report');
                            } else{
                                $ColorCode = 'background-color: #169F85;color:#ffffff;';
                                $status_color = '';
                                $url_sensitive_report = base_url('Filing/Sensitive_info/report');
                            }
                            ?>
                            <a href="<?= $url_sensitive_report; ?>" class="<?php echo $status_color; ?>" style="z-index:1"> Sensitive Report</button> </a>

                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
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

                                    <?php  //echo $_SESSION["captcha"];
                                    $attribute = array('class' => 'form-horizontal','name' => 'sensitive_report', 'id' => 'sensitive_report', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>

                                    <div class="row">

                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="from_date" class="col-sm-5 col-form-label">Date From<span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <input type="date"  class="form-control" id="from_date" name="from_date" placeholder="From Date"  value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="to_date" class="col-sm-5 col-form-label">Date To<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="date"  class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" id="submit">View</button>
                                        </div>

                                    </div>

                                    <?php form_close();?>
                                      <br/>
                                    <div id="result_data"></div>

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
        $(document).ready(function() {
            $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type=='C'){
                $('.casetype_section').show();
                $('.diary_section').hide();
            }else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });
        });
        </script>



    <script>
        $('#sensitive_report').on('submit', function () {

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
                var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
                var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
                if (date1 > date2) {
                    alert("To Date must be greater than From date");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                } else {
                    if (from_date.length == 0) {
                        alert("Please select from date.");
                        $("#from_date").focus();
                        validationError = false;
                        return false;
                    }
                    else if (to_date.length == 0) {
                        alert("Please select to date.");
                        $("#to_date").focus();
                        validationError = false;
                        return false;
                    }
                }


            if ($('#sensitive_report').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Sensitive_info/get_report'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#result_data').html(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('#result_data').html('');
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
