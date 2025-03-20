
<!-- caveat start -->
<div class="tab-pane" id="VernacularJR">
    <?php  $attribute = array('class' => 'form-horizontal vernacular_search_form','name' => 'vernacular_search_form', 'id' => 'vernacular_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);             ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-6 col-form-label">
                                    From</label>
                                <div class="col-sm-6">
                                    <input required type="text" class="form-control dtp" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control dtp" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="submit" name="vernacular_search" id="vernacular_search"  class="vernacular_search btn btn-primary" value="Search">
                                </div>
                            </div>
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
<center><span id="loader"></span> </center>
<div id="result_data"></div>


<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
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

    $('#vernacular_search_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0) {
            // alert('fromDate='+from_date+'fromDate='+to_date);
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);

            if (date1 > date2) {
                // $('#result_load').hide();
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

        if ($('#vernacular_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Court/Report/vernacular_judgments_report'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.vernacular_search').val('Please wait...');
                        $('.vernacular_search').prop('disabled', true);
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

                    },
                    success: function (data) {
                        $("#loader").html('');
                        $('.vernacular_search').prop('disabled', false);
                        $('.vernacular_search').val('Search');
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


