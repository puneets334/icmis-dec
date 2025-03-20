<div class="active tab-pane" id="defective_case">
<?php $attribute = array('class' => 'form-horizontal complete_filing_search_form', 'name' => 'complete_filing_search_form', 'id' => 'complete_filing_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);  ?>
<div class="row">
<div class="col-md-12">
<div class="card card-primary">
<div class="card-body">
<div class="row col-md-12">
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="From" class="col-form-label">From</label>
            <div class="col-sm-9">
                <input required type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="report_date" name="report_date" placeholder="Enter Date" value="<?php if(!empty($formdata['report_date'])){ echo $formdata['report_date']; } ?>" >
            </div>
        </div>

    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="Section" class="col-form-label"></label>
            <div class="col-sm-6">
                <select name="report_for" id="report_for" class="custom-select rounded-0">
                    <option value="0">All</option>
                    <option value="584">Person In Petition</option>
                    <option value="C">Caveat</option>
                </select>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-5">
     </div>
      <div class="col-sm-7">
        <input type="submit" name="complete_filing_report_btn" id="complete_filing_report_btn"  class="complete_filing_report_btn btn btn-primary" value="Search">
        <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">
    </div>
 </div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
</div>
<!--/.col (right) -->
<?= form_close()?>

</div>
<!-- /.DAK -->
<div id="dak_result_data"></div>
<div id="category_detailed_report"></div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#complete_filing_search_form').on('submit', function () {
        //$("#category_detailed_report").html('');
        var report_date = $("#report_date").val();
        if (report_date.length == 0) {
            alert("Please select from date.");
            $("#report_date").focus();
            validationError = false;
            return false;
        }

        if ($('#complete_filing_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/get_complete_filing_report'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.complete_filing_report_btn').val('Please wait...');
                        $('.complete_filing_report_btn').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.complete_filing_report_btn').prop('disabled', false);
                        $('.complete_filing_report_btn').val('Search');
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
        } else {
            return false;
        }
    });

    function get_complete_filing_details(report_type, type){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/get_complete_filing_details'); ?>",
            data: { report_for : report_type , type : type, CSRF_TOKEN:CSRF_TOKEN_VALUE },
            beforeSend: function () {
                //$('.change_category_report_btn').val('Please wait...');
                //$('.change_category_report_btn').prop('disabled', true);
            },
            success: function (data) {
                //$('.change_category_report_btn').prop('disabled', false);
                //$('.change_category_report_btn').val('Search');
                $("#category_detailed_report").html(data);
                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
                $("#category_detailed_report").html(data);
            }

        });
        return false;
    }
</script>

