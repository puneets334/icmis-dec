<?= view('header') ?>

<!-- Main content -->
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">R & I</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> AD Letter</h4>
                                </div>
                                <div class="card-body">
                                    <?php if (session()->getFlashdata('infomsg')): ?>
                                        <div class="alert alert-success">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (session()->getFlashdata('success_msg')): ?>
                                        <div class="alert alert-danger alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" id="dispatchDakToRI" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="fromDate">From Date</label>
                                                        <input type="text" id="fromDate" name="fromDate" size="8" maxlength="10" value="<?php echo date('d-m-Y'); ?>" class="dtp form-control" readonly />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="to">To Date</label>
                                                        <input type="text" id="toDate" name="toDate" size="8" maxlength="10" value="<?php echo date('d-m-Y'); ?>" class="dtp form-control" readonly />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="ddlOR">Delivery Type</label>
                                                        <select name="ddlOR" id="ddlOR" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="O">Ordinary</option>
                                                            <option value="R">Registry</option>
                                                            <option value="A">Humdust</option>
                                                            <option value="Z">Adv Registry</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26" onclick="return checkFunction();">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="dataProcessId"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.section -->

<script src="<?= base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?= base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    function checkFunction() {
        // alert("rrrr"); 
        //updateCSRFToken(); 
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var ddlOR = $("#ddlOR").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (fromDate == "") {
            alert("Select Received From Date.");
            $("#fromDate").focus();
            return false;
        }
        if (toDate == "") {
            alert("Select Received To Date.");
            $("#toDate").focus();
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/get_notice_ad_ltr'); ?>",
            type: "POST",
            beforeSend: function() {
                $('#dataProcessId').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: $("#dispatchDakToRI").serialize() + "&CSRF_TOKEN=" + encodeURIComponent(CSRF_TOKEN_VALUE),
            success: function(data) {
                $("#dataProcessId").html(data);
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.log("An error occurred: " + error);
            }
        });
    }

    function doDispatch() {
        //alert('fsdf');
        var selectedCases = [];
        var dispatchModes = [];
        $('#tblDispatchDak input:checked').each(function() {
            if ($(this).attr('name') != 'allCheck') {
                var dispatchId = $(this).attr('value');
                selectedCases.push(dispatchId);
                dispatchModes.push($('#dispatchMode_' + dispatchId).val());
            }
        });

        if (selectedCases.length <= 0) {
            alert("Please select at least one dak for dispatch.");
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var formData = {
            'selectedCases': selectedCases,
            'dispatchModes': dispatchModes,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        };
        //alert(formData);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('RI/DispatchController/insertDataToDispatchWithProcessId'); ?>",
            data: formData,
            success: function(data) {
                updateCSRFToken();
                alert("Selected Letters Dispatched to R&I Successfully!");
                $('#dispatchDakToRI').submit();
            },
            error: function(xhr, status, error) {
                console.error("Dispatch failed: " + error);
            }
        });
    }
</script>