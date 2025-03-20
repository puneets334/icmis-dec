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
                        <input type="hidden" name="hd_fil_no" id="hd_fil_no" />
                        <input type="hidden" name="hd_pdf_name" id="hd_pdf_name" />
                        <div id="result"></div>
                        <div id="dv_result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<script>
    $('.datepicker').datepicker({
        format: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });
    $('#submitHdcIForm').on('click', function(e) {
        e.preventDefault();
        var txt_frm_date = $("#txt_frm_date").val();
        var txt_to_date = $("#txt_to_date").val();

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

        $("#submitHdcIForm").attr("disabled", true);
        var csrf = $('input[name="<?= csrf_token() ?>"]').val();
        $.ajax({
            url: '<?= base_url('Scanning/ScanningController/exportHCDCCsv') ?>',
            method: 'GET',
            data: {
                txt_frm_date: txt_frm_date,
                txt_to_date: txt_to_date,
                '<?= csrf_token() ?>': csrf
            },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function() {
                // Show loader before sending the request
                $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="../../images/load.gif"/></div>');
            },
            success: function(blob, status, xhr) {
                $('#res_loader').html(''); 

                var contentDisposition = xhr.getResponseHeader('Content-Disposition');

                    var filename = "download.csv";
                    if (contentDisposition) {
                        var matches = /filename\*=UTF-8''(.+)$/.exec(contentDisposition) || /filename="(.+)"/.exec(contentDisposition);
                        if (matches) {
                            filename = decodeURIComponent(matches[1].replace(/"/g, ''));
                        }
                    }

                var downloadUrl = URL.createObjectURL(blob);

                var downloadLink = `<a href="${downloadUrl}" download="${filename}" style="text-decoration: underline;">Download CSV</a>`;
                $('#message').html(`<h3 class="mb-0 text-success">CSV generated successfully! ${downloadLink}</h3>`);

                var reader = new FileReader();
                reader.onload = function(e) {
                    var text = e.target.result;
                    var lines = text.trim().split('\n'); 
                    let table = '<table class="table table-striped table-hover"><thead class="thead-dark"><tr>';

                    const headers = lines[0].split(',');
                    headers.forEach(header => {
                        table += `<th><strong>${header.replace(/["]+/g, '').trim()}</strong></th>`;
                    });
                    table += '</tr></thead><tbody>';

                    for (let i = 1; i < lines.length; i++) {
                        const cells = lines[i].split(',');
                        table += '<tr>';
                        cells.forEach(cell => {
                            let cleanedCell = cell.trim();
                            if (/^-\d+-\d+$/.test(cleanedCell)) {
                                cleanedCell = `<strong>${cleanedCell}</strong>`; // Make the negative number bold
                            }
                            table += `<td>${cleanedCell}</td>`;
                        });
                        table += '</tr>';
                    }
                    table += '</tbody></table>';

                    $("#result").html(table);
                };

                reader.readAsText(blob);
            },
            error: function(xhr, status, error) {
                $('#res_loader').html('');
                updateCSRFToken();
                $("#result").html('<h4 class="text-center text-danger mb-0">Error: ' + error + '</h4>');
            },
            complete: function() {
                $("#submitHdcIForm").attr("disabled", false);
            }
        });

        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>