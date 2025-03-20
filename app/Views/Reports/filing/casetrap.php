
<?php
$attribute = array('class' => 'form-horizontal ', 'name' => 'case_trap_form', 'id' => 'case_trap_form', 'autocomplete' => 'off');
echo form_open(base_url('#'), $attribute);
?>
<div class="row_">
    <div class="col-md-12_">
        <div class="card_ card-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label for="From" class="col-sm-12 col-form-label">From</label>
                            <div class="col-sm-12">
                                <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label for="To" class="col-sm-12 col-form-label">To</label>
                            <div class="col-sm-12">
                                <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label for="Dairy No." class="col-sm-12 col-form-label">Dairy No.(With Year)</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No">
                            </div>
                        </div>
                    </div>
					 <div class="col-sm-3 mt-4">
                        <button type="submit" name="case_trap_search" id="case_trap_search" class="case_trap_search btn btn-primary mt-3" value="Search"> Search </button>
                    </div>

                </div>

                
            </div>
        </div>
    </div>

</div>
<?= form_close() ?>


<div id="result_data"></div>
</div>
</div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#case_trap_form').on('submit', function() {
        var validateFlag = true;
        var form_data = $(this).serialize();
        var diary_no = $("#diary_no").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Filing/Report/case_trap'); ?>",
            data: form_data,
            beforeSend: function() {
                $("#result_data").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                $('.case_trap_search').prop('disabled', true);
            },
            success: function(data) {
                $('.case_trap_search').prop('disabled', false);
               // $('.case_trap_search').val('Search');
                $("#result_data").html(data);

                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }

        });
        return false;
    });
</script>

