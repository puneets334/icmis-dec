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
      /*  .nav-breadcrumb li a {
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
                            <a href="<?= $url_sensitive_info; ?>" style="z-index:2" class="<?php echo $status_color; ?>">Sensitive Information</a>
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
                            <a href="<?= $url_sensitive_report; ?>" class="<?php echo $status_color; ?>" style="z-index:1"> Sensitive Report</a>

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

                                    <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'sensitive_search', 'id' => 'sensitive_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>

                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-6">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                                                    <label for="search_type_d">
                                                        Diary Detail
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                                                    <label for="search_type_c">
                                                        Case Detail
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        </div>

                                    <div class="row">

                                      <div class="col-md-5 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_number" class="col-sm-5 col-form-label">Diary No</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Diary No" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 diary_section">
                                            <div class="form-group row">
                                                <label for="diary_year" class="col-sm-5 col-form-label">Diary Year</label>
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

                                    <div class="row">

                                        <div class="col-sm-3 casetype_section" style="display: none;">
                                            <div class="form-group row">
                                                <label for="case_type" class="col-sm-5 col-form-label">Case type</label>
                                                <div class="col-sm-7">
                                                    <select name="case_type" id="case_type" class="custom-select rounded-0 select2" style="width: 100%;">
                                                        <option value="">Select case type</option>
                                                        <?php
                                                        foreach ($casetype as $row) {
                                                            echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                            <div class="col-sm-3 casetype_section" style="display: none;">

                                                <div class="form-group row ">
                                                    <label for="case_number" class="col-sm-5 col-form-label">Case No. From </label>
                                                    <div class="col-sm-7">
                                                        <input type="number" class="form-control" id="case_number" name="case_number" placeholder="Case No. From" >
                                                    </div>
                                                </div>

                                            </div>
                                        <div class="col-sm-3 casetype_section" style="display: none;">
                                        <div class="form-group row ">
                                            <label for="case_number_to" class="col-sm-3 col-form-label">To</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control" id="case_number_to" name="case_number_to" placeholder="Case No. To" >
                                            </div>
                                        </div>
                                        </div>

                                            <div class="col-sm-3 casetype_section" style="display: none;">
                                                <div class="form-group row">
                                                    <label for="case_year" class="col-sm-5 col-form-label">Case Year</label>
                                                    <div class="col-sm-7">
                                                        <?php $year = 1950;
                                                        $current_year = date('Y');
                                                        ?>
                                                        <select name="case_year" id="case_year" class="custom-select rounded-0">
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
                                    <br/>
                                    <div id="sensitive_load_data"> </div>
                                    <?php form_close();?>






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
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            $('#sensitive_search').on('submit', function () {
                var search_type = $("input[name='search_type']:checked").val();
                if (search_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                }
                var diary_number = $("#diary_number").val();
                var diary_year =$('#diary_year :selected').val();

                var case_type =$('#case_type :selected').val();
                var case_number = $("#case_number").val();
                var case_number_to = $("#case_number_to").val();
                var case_year =$('#case_year :selected').val();

                if (search_type=='D') {
                    if (diary_number.length == 0) {
                        alert("Please enter diary number");
                        validationError = false;
                        return false;
                    }else if (diary_year.length == 0) {
                        alert("Please select diary year");
                        validationError = false;
                        return false;
                    }
                }else if (search_type=='C') {

                    if (case_type.length == 0) {
                        alert("Please select case type");
                        validationError = false;
                        return false;
                    }else if (case_number.length == 0) {
                        alert("Please enter case number");
                        validationError = false;
                        return false;
                    }else if (case_year.length == 0) {
                        alert("Please select case year");
                        validationError = false;
                        return false;
                    }
                    if (case_number_to.length != 0) {
                        if (case_number > case_number_to) {
                            alert("To Case No. must be greater than From Case No.");
                            $("#case_number").focus();
                            validationError = false;
                            return false;
                        }
                    }
                }

                if ($('#sensitive_search').valid()) {
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if(validateFlag){
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide();
                        $("#loader").html('');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Filing/Sensitive_info/get_details'); ?>",
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
                                    $('#sensitive_load_data').html(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    $('#sensitive_load_data').html('');
                                    $('.alert-error').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                }
                            },
                            error: function() {
                                updateCSRFToken();
                                $('#sensitive_load_data').html('');
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

        function update_case()
        {
            var validateFlag = false;
            var case_diary=$('#case_diaryno').val();
            var case_info=$('#case_info').val();
            //alert('case_diary='+case_diary + 'case_info='+case_info)
            if(case_diary.length != 0) {
                if(case_info.length == 0)
                {
                    alert("Enter Case Sensitive Information");
                    $('#case_info').focus();
                    validationError = false;
                    return false;
                }else{
                    validateFlag = true;
                    if(validateFlag) {
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Filing/Sensitive_info/update_case'); ?>",
                            cache: false,
                            async: true,
                            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,case_diary: case_diary,case_info:case_info},
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
            }
            else {
                if (!alert("Enter Case Details"))
                {
                    //$('#btn-restore').prop('disabled', true);
                    // location.reload();
                    $('#case_type').focus();
                }
            }
        }

    </script>