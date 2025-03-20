<div class="active tab-pane" id="Diary">
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
    <?php } else {
        if (!empty($error_msg)) {
            echo '<span class=alert-danger>' . $error_msg . '</span>';
        }
    ?>
        <br />

    <?php } ?>
    <?php
    $attribute = array('class' => 'form-horizontal diary_search_form', 'name' => 'diary_search_form', 'id' => 'diary_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute); ?>

    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="From" class=" col-form-label">From</label>
                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="from_date form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if (!empty($formdata['from_date'])) {
                                                                                                                                                                            echo $formdata['from_date'];
                                                                                                                                                                        } ?>">
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="To" class="col-form-label">To</label>
                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if (!empty($formdata['to_date'])) {
                                                                                                                                                            echo $formdata['to_date'];
                                                                                                                                                        } ?>">

                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Dairy No." class=" col-form-label">Dairy No.</label>
                    <input type="number" class="diary_no form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" value="<?php if (!empty($formdata['diary_no'])) {
                                                                                                                                                echo $formdata['diary_no'];
                                                                                                                                            } ?>">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Year" class=" col-form-label">Year</label>
                    <select class="diary_year form-control" name="diary_year" id="diary_year" style="width: 100%;">
                        <option value="">Year</option> <?php $yr = date('Y');
                                                        for ($year_val = $yr; $year_val >= 1947; $year_val--) {    ?>
                            <option value="<?php echo $year_val; ?>" <?php if (!empty($formdata["diary_year"]) && $formdata["diary_year"] == $year_val) { ?> selected="selected" <?php } ?>><?php echo $year_val; ?></option> <?php  }  ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="row ">

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Party" class=" col-form-label">Party</label>
                    <select name="ddl_party_type" id="ddl_party_type" class="form-control" style="width: 100%;">
                        <option value="All">All</option>
                        <option value="P">Petitioner</option>
                        <option value="R">Respondent</option>
                        <option value="I">Impleading</option>
                        <option value="N">Intervenor</option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Cause Title" class=" col-form-label">Cause Title</label>
                    <input type="text" class="cause_title form-control" name="cause_title" id="cause_title" value="" placeholder="Cause Title">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Case Type" class=" col-form-label">Case Type</label>
                    <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0" style="width: 100%;">
                        <?php //if(!empty($formdata['case_type_casecode'])){ echo '<option value='.$formdata['case_type_casecode'].'>'.$formdata['case_type_casecode'].'</option>';}
                        ?>
                        <option value="">Select case type</option>
                        <?php
                        foreach ($casetype as $row) {
                        ?>
                            <option value=<?= sanitize(($row['casecode']))  ?> <?php if (!empty($formdata['case_type_casecode']) && sanitize(($row['casecode'] == $formdata['case_type_casecode']))) {
                                                                                    echo 'selected=selected';
                                                                                } ?>><?= sanitize(strtoupper($row['casename'])) ?></option>
                        <?php  }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group row">
                    <label for="Status" class=" col-form-label">Status</label>
                    <select name="ddl_status" id="ddl_status" class="form-control " style="width: 100%;">
                        <option value="All">All</option>
                        <option value="P">Pending</option>
                        <option value="D">Disposed</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="csps" value="CS" checked>
                    <label class="form-check-label mt-3">Cause Title Search</label>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="csps" value="PS">
                    <label class="form-check-label mt-3">Party Search</label>
                </div>

            </div>


        </div>
        <div class="row callout callout-info">
            <div class="col-sm-6">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" name="isma" id="isma" <?php if (!empty($formdata['isma'])) {
                                                                        echo 'checked';
                                                                    } ?>>
                    <label for="isma">Exclude Review/Curative/Contempt/MA</label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" name="is_inperson" id="is_inperson"
                        <?php if (!empty($formdata['is_inperson'])) {
                            echo 'checked';
                        } ?>>
                    <label for="is_inperson">Show Only In-Persons Filed matters </label>
                </div>
            </div>
        </div>
        <div class="row card card-primary card-outline">
            <div class="col-sm-12 mt-2">
                <div class="icheck-primary d-inline">
                    <input onclick="uncheck_p_or_e_filed(this.id)" type="checkbox" name="is_efiled_pfiled" id="is_efiled_efiled" value="efiled" <?php if (!empty($formdata['is_efiled_pfiled'])) {
                                                                                                                                                    if ($formdata['is_efiled_pfield'] == 'efiled') {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                } ?>>
                    <label for="is_efiled_efiled">E-Filed matters</label>
                </div>
            </div>

            <div class="col-sm-12 mt-2 mb-2">
                <div class="icheck-primary d-inline">
                    <input onclick="uncheck_p_or_e_filed(this.id)" class="form-check-input" type="checkbox" name="is_efiled_pfiled" id="is_efiled_pfiled" value="pfiled"
                        <?php if (!empty($formdata['is_efiled_pfiled'])) {
                            if ($formdata['is_efiled_pfiled'] == 'pfiled') {
                                echo 'checked';
                            }
                        } ?>>
                    <label for="is_efiled_pfiled">Physically Filed Matters</label>
                </div>
            </div>

        </div>
        <div class="row card card-primary card-outline">
            <div class="col-sm-12 mt-2">
                <div class="icheck-primary d-inline">
                    <input onclick="uncheck_reg_or_def(this.id)" class="form-check-input" id="reg_rmdr" type="checkbox" name="reg_or_def" value="rd"
                        <?php if (!empty($formdata['reg_or_def'])) {
                            if ($formdata['reg_or_def'] == 'rd') {
                                echo 'checked';
                            }
                        } ?>>
                    <label for="reg_rmdr" class="form-check-label">Registered Matters/Un-Registered Matters but Defects Removed</label>
                </div>

            </div>
            <div class="col-sm-12 mt-2 mb-2">
                <div class="icheck-primary d-inline">
                    <input onclick="uncheck_reg_or_def(this.id)" class="form-check-input" id="reg_dm" type="checkbox" name="reg_or_def" value="rd_dm"
                        <?php if (!empty($formdata['reg_or_def'])) {
                            if ($formdata['reg_or_def'] == 'rd_dm') {
                                echo 'checked';
                            }
                        } ?>>
                    <label for="reg_dm" class="form-check-label">Defective Matters</label>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-6"> </div>
            <div class="col-sm-6">
                <input type="submit" name="diary_search" id="diary_search" class="diary_search btn btn-primary" value="Search">
                <input type="reset" name="reset_search" id="reset_search" class="reset_search btn btn-primary" value="Reset">

            </div>
        </div>
        <span class="alert alert-error" style="display: none;text-align: center;color: red;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <span class="form-response"> </span>
        </span>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

</div>

</div>
<?= form_close(); ?>
</div>
<!-- /.diary -->
<center><span id="loader"></span> </center>
<div id="result_data"></div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#diary_search_form').on('submit', function() {

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var diary_no = $("#diary_no").val();
        var cause_title = $("#cause_title").val();

        if (from_date.length == 0 && diary_no.length == 0 && cause_title.length == 0) {
            alert("Please Fill any one field .");
            $("#from_date").focus();
            validationError = false;
            return false;
        }

        if (from_date.length != 0) {
            if (to_date.length == 0) {
                alert("Please select to date.");
                $("#to_date").focus();
                validationError = false;
                return false;
            }
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            var diffMonths = (date2.getFullYear() - date1.getFullYear()) * 12 + (date2.getMonth() - date1.getMonth());
            var diffMilliseconds = Math.abs(date2 - date1);
            var oneDayMilliseconds = 1000 * 60 * 60 * 24;
            var diffDays = Math.ceil(diffMilliseconds / oneDayMilliseconds);

            var dateString1 = new Date(date1.getTime() - (date1.getTimezoneOffset() * 60000))
                .toISOString()
                .split("T")[0];
            var dateString2 = new Date(date2.getTime() - (date2.getTimezoneOffset() * 60000))
                .toISOString()
                .split("T")[0];
            // console.log( dateString2 ,dateString1, diffDays < 31 )
            if (dateString2 < dateString1) {
                alert("To Date must be greater than From date and the days interval must be one month");
                $("#to_date").focus();
                validationError = false;
                return false;
            } else {
                if (from_date.length == 0) {
                    alert("Please select from date.");
                    $("#from_date").focus();
                    validationError = false;
                    return false;
                } else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }

        }

        if (diary_no.length != 0) {
            var diary_year = $('#diary_year').val();
            if (diary_year == '') {
                alert("Please Select Year.");
                $("#diary_year").css('border-color', 'red');
                $('#diary_year').prop('disabled', false);
                return false;
            }

        }



        if ($('#diary_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if (validateFlag) { //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/diary_search'); ?>",
                    data: form_data,
                    beforeSend: function() {
                        //    $('.diary_search').val('Please wait...');
                        //  $('.diary_search').prop('disabled', true);
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

                    },
                    success: function(data) {
                        updateCSRFToken();
                        $('.diary_search').prop('disabled', false);
                        $('.diary_search').val('Search');
                        $("#loader").html('');
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
                    }

                });
                return false;
            }
        } else {
            return false;
        }
    });
</script>


<script>
    function uncheck_p_or_e_filed(id) {
        if (id == 'is_efiled_efiled') {
            if (document.getElementById("is_efiled_efiled").checked == true) {
                document.getElementById("is_efiled_pfiled").checked = false;
            }
        }
        if (id == 'is_efiled_pfiled') {
            if (document.getElementById("is_efiled_pfiled").checked == true) {
                document.getElementById("is_efiled_efiled").checked = false;
            }
        }
    }

    function uncheck_reg_or_def(id) {
        if (id == 'reg_rmdr') {
            if (document.getElementById("reg_dm").checked == true) {
                document.getElementById("reg_dm").checked = false;
            }
        }
        if (id == 'reg_dm') {
            if (document.getElementById("reg_rmdr").checked == true) {
                document.getElementById("reg_rmdr").checked = false;
            }
        }
    }



    $(document).ready(function() {
        $(document).on('click', '#from_date', function() {
            $("#diary_year").prop('disabled', true);
            $('#diary_no').val('');
            $('#diary_year').val('');
            $('#cause_title').val('');
        });

        $(document).on('click', '#diary_no', function() {
            $("#diary_year").prop('disabled', false);
            $('#from_date').val('');
            $('#to_date').val('');
            $('#cause_title').val('');
        });
        $(document).on('click', '#cause_title', function() {
            //  $("#diary_year").prop('disabled', true);
            $('#from_date').val('');
            $('#to_date').val('');
            $('#diary_no').val('');
            $('#diary_year').val('');
        });



    });
</script>