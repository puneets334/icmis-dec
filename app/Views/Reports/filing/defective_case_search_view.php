<div class="active tab-pane" id="defective_case">
<?php $attribute = array('class' => 'form-horizontal defective_case_search_form', 'name' => 'defective_case_search_form', 'id' => 'defective_case_search_form', 'autocomplete' => 'off');
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
                <input required type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>" >
            </div>
        </div>

    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label for="To" class="col-form-label">To</label>
            <div class="col-sm-9">
                <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group row">
            <label for="Section" class="col-form-label">Section</label>
            <div class="col-sm-6">
                <select name="section" id="section" class="custom-select rounded-0">
                    <option value="" title="Select">Select section</option>
                    <?php
                    foreach ($usersection as $row) { ?>
                        <option value="<?= sanitize(($row['section_name'])) ?>" <?php if(!empty($formdata["section"]) && $formdata["section"]==sanitize(($row['id']))) { ?> selected="selected" <?php } ?>> <?= sanitize(strtoupper($row['section_name'])) ?> </option>
                   <?php }
                    ?>
                </select>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-5">
     </div>
      <div class="col-sm-7">
        <input type="submit" name="defective_report_btn" id="defective_report_btn"  class="defective_report_btn btn btn-primary" value="Search">
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
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#defective_case_search_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0) {
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            if (date1 > date2 &&  date2 < date1  ) {
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
        }

        if ($('#defective_case_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/get_defective_cases_report'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.defective_report_btn').val('Please wait...');
                        $('.defective_report_btn').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.defective_report_btn').prop('disabled', false);
                        $('.defective_report_btn').val('Search');
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

</script>

