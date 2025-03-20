
<div class="active tab-pane" id="Refiling">
    <?php
    $attribute = array('class' => 'form-horizontal refiling_search_form','name' => 'refiling_search_form', 'id' => 'refiling_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Listing Dates</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="listing_dts" name="listing_dts" placeholder="From Date"  value="<?php if(!empty($formdata['listing_dts'])){ echo $formdata['listing_dts']; } ?>">
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-3">
                        <span class="input-group-append">
                        <input type="submit" name="refiling_search" id="refiling_search"  class="refiling_search btn btn-primary" value="Search">
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
</div>
</div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.min.js'); ?>"></script>

<script>
    $('#refiling_search_form').on('submit', function () {
        if ($('#refiling_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/upload_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.refiling_search').val('Please wait...');
                        $('.refiling_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.refiling_search').prop('disabled', false);
                        $('.refiling_search').val('Search');
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

</script>


