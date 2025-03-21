<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
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
                                <h3 class="card-title">R & I </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> Update Dispatch</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" id="dispatchDakToRI" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <?php $ucode = session()->get('login')['usercode'] ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processId">Process Id</label>
                                                        <input type="number" id="processId" name="processId" class="form-control" placeholder="Process Id" value="">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processYear">Process Year</label>
                                                        <select id="processYear" name="processYear" class="form-control">
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
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

<script>
    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var fromDate = $("#processId").val();
        var toDate = $("#processYear").val();

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
            url: '<?php echo base_url('RI/DispatchController/post_update_dispatch/' . $ucode); ?>',
            type: "POST",
            data: $("#dispatchDakToRI").serialize(),
            success: function(data) {
                updateCSRFToken();
                $("#dataProcessId").html(data);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.log("An error occurred: " + error);
            }
        });
    });

    $(document).ready(function() {
        $('#info-alert').show();
        $('#reportTable').DataTable({
            "bSort": true,
            dom: 'Bfrtip',
            "scrollX": true,
            iDisplayLength: 20,

            buttons: [{
                extend: 'print',
                orientation: 'landscape',
                pageSize: 'A4'
            }]
        });
    });

    function get_btn_edit() {
        updateCSRFToken();
        if (confirm("Do you want to edit the barcode")) {
            $("#txt_barcode").removeAttr("disabled");
            $("#td_save").show();
            $("#th_save").show();
            $("#td_edit").hide();
            $("#th_edit").hide();

        }

    }


    function update_barcode(id, process_id, pid_year) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var barcode = $('#txt_barcode').val();

        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/update_barcode'); ?>",
            data: {
                id: id,
                process_id: process_id,
                pid_year: pid_year,
                barcode: barcode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: "POST",
            success: function(data) {
                updateCSRFToken();
                if (data != '') {
                    alert("Record Updated Successfully.");
                    $("#td_edit").show();
                    $("#th_edit").show();
                    $("#td_save").hide();
                    $("#th_save").hide();
                    $("#txt_barcode").attr('disabled', true)
                }

            },
            error: function() {
                updateCSRFToken();
                console.log('error');
            }
        });
    }

    function delete_record(id, process_id, pid_year) {
        if (confirm("Do you want to delete this Record.")) {
            var barcode = $('#txt_barcode').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('RI/DispatchController/delete_Record'); ?>",
                data: {
                    id: id,
                    process_id: process_id,
                    pid_year: pid_year,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    barcode: barcode
                },
                type: "POST",
                success: function(data) {
                    updateCSRFToken();
                    if (data != '') {
                        alert("Record Deleted Successfully.");
                        location.reload();
                    }

                },
                error: function() {
                    updateCSRFToken();
                    console.log('error');
                }
            });
        }
    }
</script>