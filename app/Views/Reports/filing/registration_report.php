<div class="active tab-pane" id="Fil_Trap">
    <?php
    $attribute = array('class' => 'form-horizontal registration_report_form','name' => 'registration_report_form', 'id' => 'registration_report_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary">
                <div class="card-body">
                        <div id="diary_search" class="col-sm-12 row">
                            <div class="form-group col-sm-4">
                                <label for="Dairy No." class=" col-form-label">Dairy No.</label>
                                <div>
                                    <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No"  value="<?php echo !empty($formdata['diary_no']) ? $formdata['diary_no'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="Year" class=" col-form-label">Year</label>
                                <div >
                                    <select class="form-control select2" name="diary_year" id="diary_year" >
                                        <?php echo !empty($formdata['diary_year']) ? '<option value='.$formdata['diary_year'].'>'.$formdata['diary_year'].'</option>': '' ?>
                                        <option value="">Year</option>
                                        <?php
                                        $end_year = 47; $sel = '';
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" name="registration_report_search" id="registration_report_search"  class="fil_trap_search btn btn-primary" value="Search">
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
    <?= form_close();?>
</div>
<!-- /.Fil_Trap -->
<div id="fil_trap_result_data"></div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#registration_report_search').on('click', function () {
        var form_data = $("#registration_report_form").serialize();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/display_registration_report'); ?>",
            data: form_data,
            beforeSend: function () {
                $('.fil_trap_search').val('Please wait...');
                $('.fil_trap_search').prop('disabled', true);
            },
            success: function (data) {
                $('.fil_trap_search').prop('disabled', false);
                $('.fil_trap_search').val('Search');
                $("#fil_trap_result_data").html(data);
                updateCSRFToken();
            },
            error: function () {
                updateCSRFToken();
            }

        });
        return false;

    });
</script>

