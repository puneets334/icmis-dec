<div class="active tab-pane" id="Fil_Trap">
    <?php
    $attribute = array('class' => 'form-horizontal fil_trap_search_form','name' => 'uoi_form', 'id' => 'uoi_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary">
                <div class="card-body">

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-6">
                            <input type="button" name="uoi_form_report" id="uoi_form_report"  class="uoi_form_report btn btn-primary" value="Get Report">
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
    $('#uoi_form_report').on('click', function () {
        var form_data = $("#uoi_form").serialize();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.alert-error').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Report/uoi_slp_search'); ?>",
            data: form_data,
            beforeSend: function () {
                //$('.uoi_form_report').val('Please wait...');
                $('#fil_trap_result_data').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                $('.uoi_form_report').prop('disabled', true);
            },
            success: function (data) {
                $('.uoi_form_report').prop('disabled', false);
                $('.uoi_form_report').val('Search');
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

