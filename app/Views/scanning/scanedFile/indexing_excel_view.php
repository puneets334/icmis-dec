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
            <div class="col-md-12 mt-3">
                <div class="card">

                    <div class="card-header heading ">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Scanning > Scaned File > Indexing Excel</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
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

                                <div class="card-footer col-md-4 mt-4">
                                    <button type="button" id="submitIevForm" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>

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
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
    $('#submitIevForm').on('click', function(e) {
        e.preventDefault();
        var txt_fd = $('#txt_frm_date').val();
        var txt_td = $('#txt_to_date').val();
        var csv_type = 'indexingExcelView';
        var csrf = $('input[name="<?= csrf_token() ?>"]').val();


        if (!txt_fd || !txt_td) {
            alert('Please provide both From Date and To Date.');
            return;
        }

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
                if (xhr.status !== 200) {
                    $('#res_loader').html('');
                    $('#downloadLink').html('<h5 class="mb-0 text-danger">Error generating CSV.</h5>');
                    return;
                updateCSRFToken();
                
                var downloadUrl = URL.createObjectURL(blob);
                var a = document.createElement('a');
                var filename = xhr.getResponseHeader('Content-Disposition').split('filename=')[1].replace(/"/g, '');
                a.href = downloadUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                
                $('#downloadLink').html('<h5 class="mb-0 text-success">CSV Generated Successfully!</h5>');
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                $('#res_loader').html('');
                $('#downloadLink').html('<h5 class="text-danger">Error: ' + error + '</h5>');
            }
        });

        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>