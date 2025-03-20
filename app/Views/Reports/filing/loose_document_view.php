
<div class="active tab-pane" id="DAK">
    <?php $attribute = array('class' => 'form-horizontal loose_doc_search_form', 'name' => 'loose_doc_search_form', 'id' => 'loose_doc_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);  ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class=" col-md-12">
                        <span class="row" id="from_and_to_date_div">
                            <div class="col-sm-3 ">
                                <div class="form-group row ">
                                    <label for="From" class="col-form-label">From</label>
                                    <div class="col-sm-9">
                                        <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if (!empty($formdata['from_date'])) {echo $formdata['from_date']; } ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group row">
                                    <label for="To" class="col-form-label">To</label>
                                    <div class="col-sm-9">
                                        <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if (!empty($formdata['to_date'])) {echo $formdata['to_date'];} ?>">
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="row " id="section_wise_report_other_option_div">
                   </div>
                    <hr>
                    <div class="row ">
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" checked type="radio" name="reportType" value="ds" <?php if (!empty($formdata['reportType'])) {if ($formdata['reportType'] == 'ds') {echo 'checked';}} ?>>
                                <label class="form-check-label  mt-3">Date-wise</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportType" value="us" <?php if (!empty($formdata['reportType'])) {if ($formdata['reportType'] == 'us') {echo 'checked';}} ?>>
                                <label class="form-check-label  mt-3">Userwise Report</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-sm-5">
                        </div>
                        <div class="col-sm-7">
                            <input type="submit" name="loose_doc_submit" id="loose_doc_submit" class="loose_doc_submit btn btn-primary" value="Search">
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
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    var radio_selected = $('input[name="reportType"]:checked').val();
    radioChangeEvents(radio_selected);
    $('#loose_doc_search_form').on('submit', function() {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if (from_date.length != 0) {
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            if (date1 > date2 && date2 < date1) {
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
                } else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }
        }

        if ($('#loose_doc_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            var radio_selected = $('input[name="reportType"]:checked').val();
            if(radio_selected == 'ds'){
                    var id = 1;         
            }else{
                var id = 2;       
            }
            if (validateFlag) { //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/loose_document_report'); ?>/"+id,
                    data: form_data,
                    beforeSend: function() {
                        $('.loose_doc_submit').val('Please wait...');
                        $('.loose_doc_submit').prop('disabled', true);
                    },
                    success: function(data) {
                        $('.loose_doc_submit').prop('disabled', false);
                        $('.loose_doc_submit').val('Search');
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
    $(document).ready(function() {
        get_loose_document_details();
        $('input:radio').change(function() {
            var radio_selected = $('input[name="reportType"]:checked').val();
            radioChangeEvents(radio_selected);
        });
    });

    function radioChangeEvents(radio_selected = null) {
        $("#dateSelection input,select").each(function() {
            this.value = "";
        })
        if (radio_selected == 'ds') {
            $("#from_and_to_date_div").show();
            $("#section_wise_report_other_option_div").show();
        } else if (radio_selected == 'us') {
            $("#from_and_to_date_div").show();
            $("#section_wise_report_other_option_div").hide();
        } else {
            //by document no

            $("#from_and_to_date_div").hide();
       
            
          
            $("#section_wise_report_other_option_div").hide();

        }
    }

    function get_loose_document_details(){
        updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $("#dak_result_data").html('');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/loose_document_report/1'); ?>",
            data: { CSRF_TOKEN:CSRF_TOKEN_VALUE },
            beforeSend: function () {
                //$('.change_category_report_btn').val('Please wait...');
                //$('.change_category_report_btn').prop('disabled', true);
            },
            success: function (data) {
                $("#dak_result_data").html(data);
                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
                $("#dak_result_data").html(data);
            }

        });
        return false;
    }

</script>