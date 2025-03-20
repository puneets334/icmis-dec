<div class="active tab-pane" id="GistModule">
    <?php
    $attribute = array('class' => 'form-horizontal gist_module_search_form', 'name' => 'gist_module_search_form', 'id' => 'gist_module_search_form', 'autocomplete' => 'off');
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
                                    <input type="text" max="<?php echo date("Y-m-d"); ?>" class="form-control dtp" id="listing_dts" name="listing_dts" placeholder="Listing Dates" value="<?php if (!empty($formdata['listing_dts'])) {
                                                                                                                                                                                        echo $formdata['listing_dts'];
                                                                                                                                                                                    } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Court No.</label>
                                <div class="col-sm-7">
                                    <select name="courtno" id="courtno_" class="form-control">
                                        <option value="">-Select-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="21">21 (Registrar)</option>
                                        <option value="22">22 (Registrar)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Mainhead</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="mainhead" id="mainhead" style="width: 100%;">

                                        <option value="M">Misc</option>
                                        <option value="F">Regular</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Board Type</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="board_type" id="board_type" style="width: 100%;">

                                        <option value="0">-ALL-</option>
                                        <option value="J">Court</option>
                                        <option value="S">Single Judge</option>
                                        <option value="C">Chamber</option>
                                        <option value="R">Registrar</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Main/Suppl.</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="main_suppl" id="main_suppl" style="width: 100%;">

                                        <option value="0">-ALL-</option>
                                        <option value="1">Main</option>
                                        <option value="2">Suppl.</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <span class="input-group-append">
                                <input type="submit" name="gist_module_search" id="gist_module_search" class="gist_module_search btn btn-primary" value="Search">
                            </span>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
    <?= form_close() ?>

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
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});
	});                                                                                                                                                                                 

   $('#gist_module_search_form').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    if ($('#listing_dts').val() == '') {
        alert('Select Listing Date.');
        return false;
    }
    if ($('#courtno').val() == '') {
        alert('Select Court Number.');
        return false;
    }

    if ($(this).valid()) {
        var form_data = $(this).serialize();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('.alert-error').hide();
        $("#loader").html('');

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Court/Report/gist_module_search'); ?>",
            data: form_data,
            beforeSend: function() {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                $('.gist_module_search').val('Please wait...');
                $('.gist_module_search').prop('disabled', true);
            },
            success: function(data) {
                $('.gist_module_search').prop('disabled', false);
                $('.gist_module_search').val('Search');
                $("#result_data").html(data);
                $("#loader").html('');

                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }
});
</script>