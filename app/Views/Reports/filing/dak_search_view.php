<div class="active tab-pane" id="DAK">
    <?php $attribute = array('class' => 'form-horizontal dak_search_form', 'name' => 'dak_search_form', 'id' => 'dak_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);  ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class=" col-md-12">
                        <span class="row" id="from_and_to_date_div">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 ">
                                <div class="form-group row ">
                                    <label for="From" class="col-form-label">From</label>
                                    <input type="text" max="<?php echo date("Y-m-d"); ?>" class="form-control dtp" id="from_date" name="from_date" placeholder="From Date" value="<?php if (!empty($formdata['from_date'])) { echo $formdata['from_date'];} ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group row">
                                    <label for="To" class="col-form-label">To</label>
                                    <input type="text" max="<?php echo date("Y-m-d"); ?>" class="form-control dtp" id="to_date" name="to_date" placeholder="TO Date" value="<?php if (!empty($formdata['to_date'])) {echo $formdata['to_date'];} ?>">

                                </div>
                            </div>
                        </span>
                        <div id="search_by_ducument_no" class="row">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group ">
                                    <label for="Document No." class="col-sm-6 col-form-label">Document No.</label>

                                    <input type="text" maxlength="10" class="form-control numbersonly" id="document_no" name="document_no" placeholder="Document No" value="<?php if (!empty($formdata['document_no'])) {
                                                                                                                                                        echo $formdata['document_no'];
                                                                                                                                                    } ?>">

                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group ">
                                    <label for="Year" class="col-sm-4 col-form-label">Year</label>

                                    <select class="form-control" name="doc_year" id="doc_year" style="width: 100%;">
                                        <option value="">Year</option>
                                        <?php echo !empty($formdata['doc_year']) ? '<option selected value=' . $formdata['doc_year'] . '>' . $formdata['doc_year'] . '</option>' : '' ?>
                                        <?php
                                        $end_year = 47;
                                        $sel = '';
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <!--    <span class="col-sm-4" id="section_div">
        <div class="form-group row">
            <label for="Section" class="col-form-label">Section</label>
            <div class="col-sm-6">
                <select name="section" id="section" class="custom-select rounded-0">
                    <option value="" title="Select">Select section</option>
                      <option value="all">All Sections</option>
                    <?php
                    /*                    foreach ($usersection as $row) { */ ?>
                        <option value="<?/*= sanitize(($row['id'])) */ ?>" <?php /*if(!empty($formdata["section"]) && $formdata["section"]==sanitize(($row['id']))) { */ ?> selected="selected" <?php /*} */ ?>> <?/*= sanitize(strtoupper($row['section_name'])) */ ?> </option>
                   <?php /*}
                    */ ?>
                </select>
            </div>
        </div>
    </span>-->

                    </div>
                    <div class=" row " id="dak_user_div">
                        <div class="form-group col-sm-9">
                            <label for="Section" class="col-form-label">DAK User:</label>
                            <!--<input type="text" id="dak_user"  value="<?php /*if($formdata['dak_user']!=null && isset($formdata['dak_user'])) { echo $formdata['dak_user']; } else { echo '2011,2130,4121,4518,4265,4295,4361,4371,4384,4389,4574,4592,4595,4621,4939,4940,4974,4975,4345,4984,2654,4659,5008,4566'; } */ ?>" name="dak_user"  class="form-control col-sm-3" placeholder="DAK User" required="required" readonly>-->
                            <input type="text" id="dak_user" value="2011,2130,4121,4518,4265,4295,4361,4371,4384,4389,4574,4592,4595,4621,4939,4940,4974,4975,4345,4984,2654,4659,5008,4566" name="dak_user" class="form-control " placeholder="DAK User" readonly>

                        </div>
                    </div>
                    <div class="row" id="section_wise_report_other_option_div">
                        <!--<div class="icheck-primary d-inline" id="case_block_and_receive_hide_doc">
                <input type="checkbox" name="Case_Blocked" id="Case_Blocked" value="cb" <?php /*if(!empty($formdata['exclude']) && ($formdata['Case_Blocked'] == 'Case_Blocked')) {echo 'checked';} */ ?>>
                <label for="Case_Blocked">Case Blocked & Receive Hide Doc</label >
            </div>-->
                        <div class="icheck-primary d-inline" style="margin-left:15px;">
                            <input type="checkbox" name="exclude_review_contempt_curative_petition" id="exclude_review_contempt_curative_petition" value="ercc" <?php if (!empty($formdata['exclude_review_contempt_curative_petition']) && ($formdata['exclude_review_contempt_curative_petition'] == 'exclude_review_contempt_curative_petition')) {
                                                                                                                                                                    echo 'checked';
                                                                                                                                                                } ?>>
                            <label for="exclude_review_contempt_curative_petition">Exclude Review/Contempt/Curative Petition</label>
                        </div>
                    </div>
                    <div class="row " id="section_wise_report_other_option_div">

                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" checked type="radio" name="dakReportsType" value="ds" <?php if (!empty($formdata['dakReportsType'])) {if ($formdata['dakReportsType'] == 'ds') {echo 'checked'; }} ?>>
                                <label class="form-check-label mt-3">DAK Section-wise</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dakReportsType" value="dr" <?php if (!empty($formdata['dakReportsType'])) { if ($formdata['dakReportsType'] == 'dr') {echo 'checked';}} ?>>
                                <label class="form-check-label mt-3">DAK Report</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dakReportsType" value="dd" <?php if (!empty($formdata['dakReportsType'])) {if ($formdata['dakReportsType'] == 'dd') {echo 'checked'; }} ?>>
                                <label class="form-check-label mt-3">Search by Document Number</label>
                            </div>
                        </div>

                      <!--  <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dakReportsType" value="ld" <?php if (!empty($formdata['dakReportsType'])) {if ($formdata['dakReportsType'] == 'ld') {echo 'checked';}} ?>>
                                <label class="form-check-label  mt-3">Loose Document Report</label>
                            </div>
                        </div> -->
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-sm-5">
                        </div>
                        <div class="col-sm-7">
                            <input type="submit" name="dak_submit" id="dak_submit" class="dak_submit btn btn-primary" value="Search">
                            <input type="reset" name="reset_search" id="reset_search" class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--/.col (right) -->
    <?= form_close() ?>

</div>
<!-- /.DAK -->
<div id="dak_result_data"></div>
<div id="dak_result_data1"></div>
<div id="dak_result_data2"></div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>

$(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });

    $(document).ready(function() {
        // Initialize the radio button change event
        var radio_selected = $('input[name="dakReportsType"]:checked').val();
        radioChangeEvents(radio_selected);

        // Handle form submission
        $('#dak_search_form').on('submit', function() {

            var radio_selected = $('input[name="dakReportsType"]:checked').val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var document_no = $('#document_no').val();
            let doc_year = $('#doc_year').val();

            if (radio_selected === 'ds' || radio_selected === 'dr') {
                if (from_date.length == 0) {
                    alert("Please select from date.");
                    $("#from_date").focus();
                    return false;
                } else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    return false;
                } else {
                    var date1 = new Date(from_date.split('-')[2], from_date.split('-')[1] - 1, from_date.split('-')[0]);
                    var date2 = new Date(to_date.split('-')[2], to_date.split('-')[1] - 1, to_date.split('-')[0]);
                    if (date1 > date2) {
                        alert("To Date must be greater than From date");
                        $("#to_date").focus();
                        return false;
                    }
                }
            }
            else if (radio_selected === 'dd') {
                // Validate the document number field
                if (document_no == '') {
                    alert("Please enter a Document Number.");
                    $("#document_no").focus();
                    return false;
                }else if(doc_year == ''){
                    alert("Please Select Year.");
                    $("#doc_year").focus();
                    return false;
                }
            }


            if ($('#dak_search_form').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Reports/Filing/Report/dak_search'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $('.dak_submit').val('Please wait...');
                            $('.dak_submit').prop('disabled', true);
                        },
                        success: function(data) {
                            $('.dak_submit').prop('disabled', false);
                            $('.dak_submit').val('Search');
                            $("#dak_result_data").html(data);
                            updateCSRFToken();
                        },
                        error: function() {
                            updateCSRFToken();
                            $("#dak_result_data").html(data);
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });

        // Handle radio button change event
        $('input:radio').change(function() {
            var radio_selected = $('input[name="dakReportsType"]:checked').val();
            radioChangeEvents(radio_selected);
        });
    });

    function radioChangeEvents(radio_selected = null) {
        $("#diary_search,#dateSelection input,select").each(function() {
            this.value = "";
        });
        if (radio_selected == 'ds') {
            $("#search_by_ducument_no").hide();
            $("#dak_user_div").hide();
            $("#section_div").show();
            $("#from_and_to_date_div").show();
            $("#section_wise_report_other_option_div").show();
        } else if (radio_selected == 'dr') {
            $("#search_by_ducument_no").hide();
            $("#dak_user_div").show();
            $("#section_div").hide();
            $("#from_and_to_date_div").show();
            $("#section_wise_report_other_option_div").hide();
        } else if (radio_selected == 'dd') {
            // Show fields related to "Search by Document Number"
            $("#search_by_ducument_no").show(); // Show the document number search fields
            $("#dak_user_div").hide();
            $("#section_div").hide();
            $("#from_and_to_date_div").hide(); // Hide date fields
            $("#section_wise_report_other_option_div").hide();
        } else {
            // Handle other cases if needed
            $("#from_and_to_date_div").hide();
            $("#search_by_ducument_no").show();
            $("#dak_user_div").hide();
            $("#section_div").hide();
            $("#section_wise_report_other_option_div").hide();
        }
    }
</script>