<?= view('header') ?>
<style>
    .dataTables_filter input.form-control.form-control-sm {
        width: auto !important;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Uploaded Original Record</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" id="alert_header" style="display: none;">
                                <div class="card-body">
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong id="alert_message"></strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode'] ?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fromDate">Upload Date <i class="fa fa-calendar"></i></label>
                                            <div class="input-group">
                                                <input type="input" class="form-control dtp" name="fromDate" id="fromDate" placeholder="dd-mm-yyyy">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="toDate">To <i class="fa fa-calendar"></i></label>
                                            <div class="input-group">
                                                <input type="input" class="form-control dtp" name="toDate" id="toDate" placeholder="dd-mm-yyyy">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="btnShowCases" class="btn btn-info form-control" onclick="getCasesForDownloading();">Show Cases
                                    </button>
                                </div>

                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive" id="divCasesForUploading"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="loader"></div>
<script>
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function showErrorAlert(message) {
        $('#alert_message').text(message);
        $('#alert_header').fadeIn();
        setTimeout(function() {
            $('#alert_header').fadeOut();
        }, 5000);
    }

    function getCasesForDownloading() {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var usercode = $('#usercode').val();
        const csrfToken = $('input[name="<?= csrf_token() ?>"]').val();

        var validationError = true; // Flag to track validation state
        $(".invalid-feedback").remove(); // Remove any previous error messages
        $(".is-invalid").removeClass('is-invalid border-danger'); // Reset error styl

        if (!fromDate) {
            $("#fromDate").addClass('is-invalid').after("<div class='invalid-feedback'>Please enter Upload Date</div>");
            validationError = false;
        }
        if (!toDate) {
            $("#toDate").addClass('is-invalid').after("<div class='invalid-feedback'>Please enter Upload Date</div>");
            validationError = false;
        }

        if (fromDate == "" || toDate == "") {
            showErrorAlert("Please select proper date");
            $("#fromDate").addClass('is-invalid');
            $("#toDate").addClass('is-invalid');
            validationError = false;
            //alert("Please select proper date");
            return false;
        }

        if (fromDate && toDate) {
            var fromParts = fromDate.split('-');
            var toParts = toDate.split('-');

            // Make sure parts are parsed correctly and convert to Date
            var dateFrom = new Date(fromParts[2], fromParts[1] - 1, fromParts[0]);
            var dateTo = new Date(toParts[2], toParts[1] - 1, toParts[0]);

            if (dateFrom > dateTo) {
                $("#fromDate").addClass('is-invalid');
                $("#toDate").addClass('is-invalid');
                validationError = false;
                showErrorAlert("To Date must be greater than From Date.");
                //alert("To Date must be greater than From Date.");
                return false;
            }
        }

        if (fromDate != "" && toDate != "") {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('Judicial/OriginalRecord/UploadScannedFile/getCasesData'); ?>",
                data: {
                    'fromDate': fromDate,
                    'toDate': toDate,
                    'usercode': usercode,
                    '<?= csrf_token() ?>': csrfToken
                },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    $("#divCasesForUploading").html(result);
                    $('#tblCasesForUploading').DataTable({
                        "bSort": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bInfo": false
                    });
                    $("#loader").html('');

                    updateCSRFToken();
                },
                error: function() {
                    updateCSRFToken();
                    alert('something went wrong. Please try after sometime');
                    return false;
                }

            });
        }
    }

    $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });
</script>