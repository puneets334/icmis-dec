<div class="active tab-pane" id="Refiling">
    <?php
    $attribute = array('class' => 'form-horizontal cause_list_withor_search_form', 'name' => 'cause_list_withor_search_form', 'id' => 'cause_list_withor_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Mainhead" class="col-sm-5 col-form-label">Mainhead</label>
                                <div class="col-sm-7">
                                    <select id="mr" name="mr" class="form-control">
                                        <option value="M">Miscellaneous</option>
                                        <option value="F">Regular</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Listing date" class="col-sm-5 col-form-label">Listing Date</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="listing_date" id="listing_date">
                                        <option value="">SELECT</option>
                                        <?php if (!empty($listing_dates)) {
                                            foreach ($listing_dates as $listing_date) { ?>
                                                <option value="<?php echo $listing_date['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($listing_date['next_dt'])); ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Board Type" class="col-sm-5 col-form-label">Board Type</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="board_type" id="board_type">
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
                                <label for="Board Type" class="col-sm-5 col-form-label">Court No.</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="courtno" id="courtno_">
                                        <option value="0">-ALL-</option>
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
                                        <option value="31">1 (Virtual Court)</option>
                                        <option value="32">2 (Virtual Court)</option>
                                        <option value="33">3 (Virtual Court)</option>
                                        <option value="34">4 (Virtual Court)</option>
                                        <option value="35">5 (Virtual Court)</option>
                                        <option value="36">6 (Virtual Court)</option>
                                        <option value="37">7 (Virtual Court)</option>
                                        <option value="38">8 (Virtual Court)</option>
                                        <option value="39">9 (Virtual Court)</option>
                                        <option value="40">10 (Virtual Court)</option>
                                        <option value="41">11 (Virtual Court)</option>
                                        <option value="42">12 (Virtual Court)</option>
                                        <option value="43">13 (Virtual Court)</option>
                                        <option value="44">14 (Virtual Court)</option>
                                        <option value="45">15 (Virtual Court)</option>
                                        <option value="46">16 (Virtual Court)</option>
                                        <option value="47">17 (Virtual Court)</option>
                                        <option value="21">1 (Registrar)</option>
                                        <option value="22">2 (Registrar)</option>
                                        <option value="61">1 (Registrar Virtual Court)</option>
                                        <option value="62">2 (Registrar Virtual Court)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Board Type" class="col-sm-5 col-form-label">Category</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="main_suppl" id="main_suppl">
                                        <option value="0">-ALL-</option>
                                        <option value="1">Main</option>
                                        <option value="2">Suppl.</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <span class="input-group-append">
                                <input type="submit" name="cause_list_withor_search" id="cause_list_withor_search" class="cause_list_withor_search btn btn-primary" value="Search">
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
    $('#cause_list_withor_search_form').on('submit', function() {
        // e.preventDefault();
        if ($('#listing_date').val() == '') {
            alert('Select Listing Date.');
            return false;
        }
        if ($('#cause_list_withor_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if (validateFlag) { //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/cause_list_with_or'); ?>",
                    data: form_data,
                    beforeSend: function() {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.cause_list_withor_search').val('Please wait...');
                        $('.cause_list_withor_search').prop('disabled', true);
                    },
                    success: function(data) {
                        $('.cause_list_withor_search').prop('disabled', false);
                        $('.cause_list_withor_search').val('Search');
                        $("#result_data").html(data);
                        $("#loader").html('');

                        updateCSRFToken();
                    },
                    error: function() {
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