<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Copy Search</h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_breadcrumb'); ?>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-primary">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('error') ?></strong>
                                        </div>

                                    <?php } ?>
                                    <?php if (session()->getFlashdata('success_msg')) : ?>
                                        <div class="alert alert-success alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="card mt-2 ">
                                            <div class="card-header bg-info font-weight-bolder" style="color:black !important">Copy Search By -
                                                <label class="radio-inline text-black">
                                                    <input type="radio" name="rdbtn_select" id="radio_ano" value="ANO" checked> Application No.
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="rdbtn_select" id="radio_crn" value="CRN"> CRN
                                                </label>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row" id="search_application_no">
                                                    <div class="form-group col-md-4">
                                                        <label for="application_type">Type</label>
                                                        <select id="application_type" name="application_type" class="form-control">
                                                            <option value="0">Select</option>

                                                            <?php foreach ($copy_category as $category) { ?>
                                                                <option value="<?php echo $category['id'] ?>"><?php echo $category['code'] ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="application_no">No.</label>
                                                        <input type="text" class="form-control" id="application_no" name="application_no" onkeypress="return isNumber(event)" maxlength="10">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="application_year">Year</label>
                                                        <select id="application_year" class="form-control">
                                                            <?php
                                                            $currently_selected = date('Y');
                                                            $earliest_year = 1950;
                                                            $latest_year = date('Y');
                                                            foreach (range($latest_year, $earliest_year) as $i) {
                                                                print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-row" id="search_crn">
                                                    <div class="form-group col-md-4">
                                                        <label for="crn">CRN.</label>
                                                        <input type="text" class="form-control" id="crn" name="crn" maxlength="15">
                                                    </div>

                                                </div>

                                                <div class="form-row text-right">
                                                    <div class="col-md-12">
                                                        <input id="sub" name="sub" type="button" class="btn btn-success" value="Search">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                    </div>

                </div>
                <div id="result_data"></div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <script>
              function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        
        $(document).ready(function() {
            $("#search_crn").hide();
        });
        $(document).on('click', '#radio_crn', function() {
            $("#search_crn").show();
            $("#search_application_no").hide();
            $('#result').html('');
        });
        $(document).on('click', '#radio_ano', function() {
            $("#search_application_no").show();
            $("#search_crn").hide();
            $('#result').html('');
        });
        $(document).on('click', '#sub', function() {

            var application_type = $("#application_type").val();
            var application_no = $("#application_no").val();
            var application_year = $("#application_year").val();
            var crn = $("#crn").val();
            var flag = '';
            var regNum = new RegExp('^[0-9]+$');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


            if ($("#radio_ano").is(':checked')) {
                flag = 'ano';
                if (!regNum.test(application_type)) {
                    alert("Please Select Type");
                    $("#application_type").focus();
                    return false;
                }
                if (!regNum.test(application_no)) {
                    alert("Please Fill Application No. in Numeric");
                    $("#application_no").focus();
                    return false;
                }
                if (!regNum.test(application_year)) {
                    alert("Please Fill Application Year in Numeric");
                    $("#application_year").focus();
                    return false;
                }
                if (application_no == 0) {
                    alert("Application No. Can't be Zero");
                    $("#case_no").focus();
                    return false;
                }
                if (application_year == 0) {
                    alert("Application Year Can't be Zero");
                    $("#case_yr").focus();
                    return false;
                }
            } else {
                flag = 'crn';
                if (crn.length != 15) {
                    alert("Please enter CRN");
                    $('#crn').focus();
                    return false;
                }
            }

            $.ajax({
                url: '<?php echo base_url('Copying/Copying/get_copy_search'); ?>',
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#result_data').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
                },
                data: {
                    flag: flag,
                    crn: crn,
                    application_type: application_type,
                    application_no: application_no,
                    application_year: application_year,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    $('#result_data').html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });
        }); 


    </script>