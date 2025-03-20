
<div class="active tab-pane" id="fixedDateMatter">
    <?php
    $attribute = array('class' => 'form-horizontal fixed_date_matters_form','name' => 'fixed_date_matters_form', 'id' => 'fixed_date_matters_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="report_type" class="col-sm-5 col-form-label">Category</label>
                                <div class="col-sm-7">
                                    <select id="report_type" name="report_type" class="form-control">
                                        <option value="1">Misc</option>
                                        <option value="2">NMD</option>
                                        <!-- <option value="3">Regular</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Select Hon’ble Judge </label>
                                <div class="col-sm-7">
                                    <select id="judge" name="judge" class="form-control">
                                                                                                         <?php
                                                                       foreach ($judge as $row) { ?>

                                                                            <option value="<?=sanitize(($row['jcode']))  ?>"><?=sanitize(strtoupper($row['jname']))?></option>
                                      <?php }?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">
                        <span class="input-group-append">
                        <input type="submit" name="fixed_date_matters" id="fixed_date_matters"  class="fixed_date_matters btn btn-primary" value="Search">
                          </span>
                        </div>


                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
       <?= form_close()?>

 </div>
    <div id="result_data"></div>
    <center><span id="loader"></span> </center>
      </div>
   </div>
 </div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $('#fixed_date_matters_form').on('submit', function () {
        if ($('#fixed_date_matters_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/fixed_date_matters'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.fixed_date_matters').val('Please wait...');
                        //$('.fixed_date_matters').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.fixed_date_matters').prop('disabled', false);
                        $('.fixed_date_matters').val('Search');
                        $("#result_data").html(data);
                        $("#loader").html('');

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

</script>


