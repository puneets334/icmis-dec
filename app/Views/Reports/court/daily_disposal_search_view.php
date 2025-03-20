
<div class="active tab-pane" id="Refiling">
    <?php
    $attribute = array('class' => 'form-horizontal daily_disposal_search_form','name' => 'daily_disposal_search_form', 'id' => 'daily_disposal_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Daily Disposal On Date </label>
                                <div class="col-sm-7">
                                    <input type="text" max="<?php echo date("Y-m-d"); ?>"  class="form-control dtp" id="on_date" name="on_date" placeholder="Disposal On Date"  value="<?php if(!empty($formdata['on_date'])){ echo $formdata['on_date']; } ?>" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                        <span class="input-group-append">
                        <input type="submit" name="daily_disposal_search" id="daily_disposal_search"  class="daily_disposal_search btn btn-primary" value="Search">
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
<center><span id="loader"></span> </center>
<div id="result_data"></div>
</div>
</div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $(document).on("focus", ".dtp", function() {
		$('.dtp').datepicker({
			format: 'dd-mm-yyyy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});
	});

    $('#daily_disposal_search_form').on('submit', function () {
        if ($('#daily_disposal_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/daily_disposal_remarks'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.daily_disposal_search').val('Please wait...');
                        $('.daily_disposal_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.daily_disposal_search').prop('disabled', false);
                        $('.daily_disposal_search').val('Search');
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


