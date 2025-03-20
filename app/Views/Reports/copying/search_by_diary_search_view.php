
<div class="active tab-pane" id="Search_by_Diary_Number">
    <?php
    $attribute = array('class' => 'diaryorcase_search_form form-horizontal','name' => 'diaryorcase_search_form', 'id' => 'diaryorcase_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
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
        <div class="col-md-3"></div>
        <div class="col-md-3 diary_section">
            <div class="form-group row">
                <label for="diary_number" class="col-sm-5 col-form-label">Diary No</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" >
                </div>
            </div>
        </div>
        <div class="col-md-3 diary_section">
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

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-4 casetype_section" style="display: none;">
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
        <div class="col-md-2 casetype_section" style="display: none;">

            <div class="form-group row ">
                <label for="case_number" class="col-sm-5 col-form-label">Case No.</label>
                <div class="col-sm-7">
                    <input type="number" class="form-control" id="case_number" name="case_number" placeholder="Case No." >
                </div>
            </div>

        </div>
           <div class="col-md-3 casetype_section" style="display: none;">
            <div class="form-group row">
                <label for="case_year" class="col-sm-5 col-form-label">Case Year</label>
                <div class="col-sm-5">
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

        <input type="submit" name="diaryorcase_search" id="diaryorcase_search"  class="diaryorcase_search btn btn-primary" value="Search">

    </center>
    <br/>
    <center><span id="loader"></span> </center>
    <div id="result_data"></div>
    <?php form_close();?>


 </div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
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
    $(document).ready(function() {
        $('#diaryorcase_search_form').on('submit', function () {
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

            }

            if ($('#diaryorcase_search_form').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Reports/Copying/Report/diaryorcase_search'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $('.diaryorcase_search').val('Please wait...');
                            $('.diaryorcase_search').prop('disabled', true);
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (data) {
                            $("#loader").html('');
                            $('.diaryorcase_search').prop('disabled', false);
                            $('.diaryorcase_search').val('Search');
                            $("#result_data").html(data);
                            updateCSRFToken();
                        },
                        error: function () {
                            updateCSRFToken();
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
