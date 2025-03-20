
<div class="active tab-pane" id="Refiling">
    <?php
    $attribute = array('class' => 'form-horizontal report_master_search_form','name' => 'report_master_search_form', 'id' => 'report_master_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
       <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">From</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="from_date"  name="from_date" placeholder="From Date"  value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="to_date"  name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="User Type" class="col-sm-5 col-form-label">User Type</label>
                                <div class="col-sm-7">
                            <select name="ddl_users" id="ddl_users"  class="form-control ">
                                <option value="">Select</option>
                                <option value="101">Filing</option>
                                <option value="102">Data Entry</option>
                                <option value="103">Scrutiny</option>
                                <option value="105">Category</option>
                                <option value="106">Tagging</option>
                                <option value="107">IB-Extension</option>
                                <option value="108">Filing Dispatch Receive</option>
                                <option value="109">Loose Document</option>
                                <option value="9796">Scaning</option>
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">

                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportview" value="ca" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'cv'){echo 'checked';}}?> checked>
                                <label class="form-check-label">Case Allotted</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportview" value="cv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?><!
                                <label class="form-check-label">Case Verification</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportview" value="fsm" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?><!
                                <label class="form-check-label">Fresh Scrutiny Matters </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportview" value="ldu" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?><!
                                <label class="form-check-label">Loose Doc User-Wise </label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reportview" value="smpr" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'smpr'){echo 'checked';}}?><!
                                <label class="form-check-label">Sensitive Matters - Pending and Not Ready </label>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                        </div>
                        <div class="col-sm-7">
                        <span class="input-group-append">
                        <input type="submit" name="report_master_search" id="report_master_search"  class="report_master_search btn btn-primary" value="Search">
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
    $('#report_master_search_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
            if (to_date < from_date) {
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
        var ele = document.getElementsByName('reportview');
        for(var i = 0; i < ele.length; i++) {
            if(ele[i].checked)
                var radio_slct_valid=ele[i].value;
        }

        if($('#report_master_search').is(':empty')) {
            if (radio_slct_valid == 'ca') {
                var ddl_users = $('#ddl_users').val();
                if (ddl_users == '') {
                    alert("Please Select User Type.");
                    $("#ddl_users").css('border-color', 'red');
                    return false;

                }
            }else {
                $('#ddl_users').val('');
                $("#ddl_users").css('border-color', '');
            }
        }


        if ($('#report_master_search').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/report_master_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.report_master_search').val('Please wait...');
                        $('.report_master_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.report_master_search').prop('disabled', false);
                        $('.report_master_search ').val('Search');
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

