<?= view('header') ?>

<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }

    .card-header {
        padding: 5px;
    }

    h4 {
        line-height: 0px;
    }

    .row {
        margin-right: 15px;
        margin-left: 15px;
    }

    a {
        color: darkslategrey
    }

    /* Unvisited link  */

    a:hover {
        color: black
    }

    /* Mouse over link */
    a:active {
        color: #0000FF;
    }

    /* Selected link   */

    .box.box-success {
        border-top-color: #00a65a;
    }

    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .box-header {
        color: #444;
        display: block;
        padding: 10px;
        position: relative;
    }

    .box-header.with-border {
        border-bottom: 1px solid #f4f4f4;
    }

    .box.box-danger {
        border-top-color: #dd4b39;
    }
</style>


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">R & I >> Dispatch To R&I </h3>
                            </div>
                        </div>

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
                    </div>

                    <span class="alert alert-error" style="display: none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span class="form-response"> </span>
                    </span>

                    <!-- Form Section -->
                    <br>
                    <div class="container-fluid">
                        <?php $ucode = session()->get('login')['usercode'] ?>
                        <!-- <h3 class="page-header">Dispatch To R&I</h3> -->
                        <form id="dispatchDakToRI" method="post" action="">
                            <?= csrf_field(); ?>
                            <div class="row" id="divSection" style="display: block">
                                <div class="row">
                                    <div class="form-group col-sm-2">
                                        <label for="from">From Date</label>
                                        <input type="text" id="fromDate" name="fromDate" class="form-control dtp" autocomplete="off" placeholder="From Date">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="to">To Date</label>
                                        <input type="text" id="toDate" name="toDate" class="form-control dtp" placeholder="To Date" autocomplete="off">
                                    </div>

                                    <div class="form-group col-sm-3 pull-right">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-info form-control mt-4" onclick="return checkFunction();">View</button>
                                </div>

                                </div>
                                
                            </div>
                        </form>

                        <div id="dataProcessId"></div>

                    </div>
                    <br><br><br>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.section -->

<!-- <script src="<?= base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?= base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script> -->

<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function checkFunction() {
        // alert("rrrr"); 
        //updateCSRFToken(); 
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
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
        var dynamicUrl = "<?php echo base_url('RI/DispatchController/getDataToDispatchWithProcessId/' . $ucode); ?>";
        $.ajax({
            url: dynamicUrl,
            type: "POST",
            data: $("#dispatchDakToRI").serialize(),
            success: function(data) {
                updateCSRFToken();
               // $('.card-title').hide();
                //$('.page-header').hide();
                $("#dataProcessId").html(data);
                //$("#dispatchDakToRI").hide();
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
<?= view('sci_main_footer') ?>