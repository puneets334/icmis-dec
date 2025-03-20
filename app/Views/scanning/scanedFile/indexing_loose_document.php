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
                        <h3 class="pb-0">Indexing(Loose Document) View/Download</h3>
                    </div>
                    <form id="dateForm" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="txt_frm_date" class="form-label">From Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control datepicker" id="txt_frm_date" name="txt_frm_date" placeholder="From Date" require>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <label for="txt_to_date" class="form-label">To Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control datepicker" id="txt_to_date" name="txt_to_date" placeholder="TO Date" require>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary w-25" id="submitIldForm">SUBMIT</button>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-center" id="downloadLink"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

        // Submit form via AJAX
        $('#submitIldForm').on('click', function(e) {
            e.preventDefault();

            var txt_fd = $('#txt_frm_date').val();
            var txt_td = $('#txt_to_date').val();
            var csv_type = 'indexingLooseDocument';
            var csrf = $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                url: '<?= base_url('Scanning/ScanningController/exportCsv') ?>',
                method: 'POST',
                data: {
                    txt_fd: txt_fd,
                    txt_td: txt_td,
                    csv_type: csv_type,
                    '<?= csrf_token() ?>': csrf
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.responseType = 'blob';
                    return xhr;
                },
                beforeSend: function() {
                    $('#downloadLink').html('<h5 class="mb-0 text-warning">Generating CSV, please wait...</h5>');
                    $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
                },
                success: function(blob, status, xhr) {
                    console.log('Response:', blob); // Log the blob response
                    console.log('Type:', blob instanceof Blob); // Check if it's a Blob
                    // Check for errors from the server
                    if (xhr.status !== 200 || !(blob instanceof Blob)) {
                        $('#res_loader').html('');
                        $('#downloadLink').html('<h5 class="mb-0 text-danger">An error occurred while generating the CSV.</h5>');
                        return;
                    }

                    var downloadUrl = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    var filename = xhr.getResponseHeader('Content-Disposition').split('filename=')[1].replace(/"/g, '');
                    a.href = downloadUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    $('#downloadLink').html('<h5 class="mb-0 text-success">CSV generated successfully!</h5>');
                },
                error: function(xhr, status, error) {
                    $('#res_loader').html('');
                    updateCSRFToken();
                    $('#downloadLink').html('<h5 class="mb-0 text-danger">An error occurred: ' + error + '</h5>'); // Show error message
                }

            });
        });

        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>