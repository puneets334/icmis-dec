<!-- caveat start -->
<div class="tab-pane" id="ePay">
    <?php $attribute = array('class' => 'form-horizontal ePay_search_form', 'name' => 'ePay_search_form', 'id' => 'ePay_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);             ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header bg-info text-white font-weight-bolder" style="font-size: 32px;">eCopying | Payment Reports | Search By -
                    <label class="radio-inline">
                        <input type="radio" name="rdbtn_select" id="radio_crn" value="CRN"> CRN
                    </label>
                    <label class="radio-inline text-black">
                        <input type="radio" name="rdbtn_select" id="radio_all" value="All" checked> All
                    </label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row" id="search_all">
                                <label for="User Type" class="col-sm-5 col-form-label">Pay Heads<span style="color:red;">*</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="pay_heads" name="pay_heads" aria-describedby="pay_heads_addon">
                                        <option value=0>-All-</option>
                                        <option value="9527">Copying Fess in Stamp</option>
                                        <option value="9528">Copying Service Charges</option>
                                        <option value="9525">Postage</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row" id="search_all1">
                                <label for="From" class="col-sm-6 col-form-label">
                                    Transactions Date</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if (!empty($formdata['from_date'])) {
                                                                                                                                                echo $formdata['from_date'];
                                                                                                                                            } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3 ">
                            <div class="form-group row" id="search_all2">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if (!empty($formdata['to_date'])) {
                                                                                                                                            echo $formdata['to_date'];
                                                                                                                                        } ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">

                            <div class="form-group row" id="search_crn">
                                <label for="To" class="col-sm-5 col-form-label">CRN<span style="color:red;">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="crn" name="crn" maxlength="15">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <input type="submit" name="ePay_search" id="ePay_search" class="ePay_search btn btn-primary" value="Search">
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
    <?= form_close() ?>

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
    $(document).ready(function() {
        $("#search_crn").hide();
    });
    $(document).on('click', '#radio_crn', function() {
        $("#search_crn").show();
        $("#search_all").hide();
        $("#search_all1").hide();
        $("#search_all2").hide();
        $('#result').html('');
    });
    $(document).on('click', '#radio_all', function() {
        $("#search_all").show();
        $("#search_all1").show();
        $("#search_all2").show();
        $("#search_crn").hide();
        $('#result').html('');
    });
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var crn = $("#crn").val();
    var pay_heads_text = $("#pay_heads option:selected").text();

    // if(crn.length != 0) {
    //     document.getElementById("from_date").required = false;
    //     document.getElementById("to_date").required = false;

    // }else {
    //     document.getElementById("from_date").required = true;
    //     document.getElementById("to_date").required = true;

    // }

    $('#ePay_search_form').on('submit', function() {

        if (from_date.length != 0) {
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
                } else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }
        }

        if ($('#ePay_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if (validateFlag) { //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Copying/Report/epay_search'); ?>",
                    data: form_data,
                    beforeSend: function() {
                        $('.ePay_search').val('Please wait...');
                        $('.ePay_search').prop('disabled', true)
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    success: function(data) {
                        $("#loader").html('');
                        $('.ePay_search').prop('disabled', false);
                        $('.ePay_search').val('Search');
                        $("#result_data").html(data);
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