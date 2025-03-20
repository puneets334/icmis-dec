<?= view('header') ?>
<style>
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group>.form-control,
    .input-group>.form-floating,
    .input-group>.form-select {
        position: relative;
        flex: 1 1 auto;
        width: 1% !important;
        min-width: 0;
    }

    .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: calc(var(--bs-border-width)* -1);
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="mb-0">HC DC Indexing View/Download</h3>
                    </div>
                    <div class="card-body">
                        <form id="dateForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_frm_date" class="col-md-4 col-form-label">From Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_frm_date" name="txt_frm_date" placeholder="From Date" require>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="txt_to_date" class="col-md-4 col-form-label">To Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_to_date" name="txt_to_date" placeholder="To Date" require>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ddl_dt_type" class="col-form-label">Select Date Type</label>
                                        <select class="form-control select-box" name="ddl_status" id="ddl_status">
                                            <option value="12">All</option>
                                            <option value="1">Completed</option>
                                            <option value="2">Not Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="submitHdcIForm" class="btn btn-primary w-25">SUBMIT</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-center" id="message"></div>
                    <div class="card-body">
                        <div id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<script>
    function updateCSRFToken() {
        $.get('<?= site_url('Scanning/SupremeCourtScan/SupremeCourtScanController/getCSRF'); ?>', function(data) {
            $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
        }, 'json');
    }
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
    $('#submitHdcIForm').on('click', function(e) {
        e.preventDefault();
        var txt_frm_date = $("#txt_frm_date").val();
        var txt_to_date = $("#txt_to_date").val();
        var ddl_status = $('#ddl_status').val();

        if (!txt_frm_date) {
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Error',
            //     text: 'Please enter a "From Date".',
            // });
            alert('Please enter a "From Date"');
            return;
        }
        if (!txt_to_date) {
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Error',
            //     text: 'Please enter a "To Date".',
            // });
            alert('Please enter a "To Date"');
            return;
        }
        if (!ddl_status) {
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Error',
            //     text: 'Please select a "Date Type".',
            // });
            alert('Please select a "Date Type"');
            return;
        }

        $("#submitHdcIForm").attr("disabled", true);
        var csrf = $('input[name="<?= csrf_token() ?>"]').val();
        $.ajax({
            url: '<?= base_url('Scanning/SupremeCourtScan/SupremeCourtScanController/fetchDetails') ?>',
            method: 'POST',
            data: {
                txt_frm_date: txt_frm_date,
                txt_to_date: txt_to_date,
                ddl_status: ddl_status,
                '<?= csrf_token() ?>': csrf
            },
            beforeSend: function() {
                $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
            },
            success: function(data) {
                if (data.status == '1') {
                    $('#res_loader').html(''); 
                    $('#result').html(data.html);  // Set the returned HTML into the #result div
                } else {
                    updateCSRFToken();
                    $('#res_loader').html(''); 
                    $('#result').html(''); 
                    $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' + data.message + '</h4>');
                }
                $("#submitHdcIForm").attr("disabled", false);
              
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                alert('Something went wrong!');
                $('#sub').attr('disabled', false);
                $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' + error + '</h4>');
            },
            complete: function() {
                $("#submitHdcIForm").attr("disabled", false);
            }
        });

    });
</script>