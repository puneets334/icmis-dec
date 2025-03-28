
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
                    <div class="card-header heading ">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Scanning >> Scaned File >> Diary No Details</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>


                    <div class="card-body">
                        <form id="dateForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ddl_dt_type" class="col-form-label">Select Date Type</label>
                                        <select class="form-control" name="ddl_dt_type" id="ddl_dt_type">
                                            <!-- <option value="">Select</option> -->
                                            <option value="1">Registration Date</option>
                                            <option value="2">Listing Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_frm_date" class="col-form-label">From Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_frm_date" name="txt_frm_date" placeholder="From Date" require>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_to_date" class="col-form-label">To Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_to_date" name="txt_to_date" placeholder="To Date" require>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 card-footer mt-4">
                                    <button type="button" id="diaryNoDetail" class="btn btn-primary w-100">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header text-center" id="message"></div>
                    <div class="card-body">
                        <div class="table-responsive" id="result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="res_loader"></div>
<script>
    $('.select-box').select2({
        selectOnClose: true
    });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050'
    });

    $('#diaryNoDetail').on('click', function(e) {
        e.preventDefault();

        var txt_frm_date = $('#txt_frm_date').val();
        var txt_to_date = $('#txt_to_date').val();
        var ddl_dt_type = $('#ddl_dt_type').val();

        if (!txt_frm_date || !txt_to_date) {
            alert('Please provide both From Date and To Date.');
            return;
        }

        var csrf = $('input[name="<?= csrf_token() ?>"]').val();

        $.ajax({
            url: '<?= base_url('Scanning/ScanningController/getDiaryDetails') ?>',
            method: 'GET',
            data: {
                txt_frm_date: txt_frm_date,
                txt_to_date: txt_to_date,
                ddl_dt_type: ddl_dt_type,
                '<?= csrf_token() ?>': csrf,
                download: 'csv' // Add flag to indicate CSV download
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.responseType = 'blob'; // Set response type to blob for file download
                return xhr;
            },
            beforeSend: function() {
                $('#downloadLink').html('<h5 class="mb-0 text-warning">Diary data loading, please wait...</h5>');
                $('#res_loader').html('<div style="position: absolute; top: 50%; left: 50%; text-align: center; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= base_url();?>/images/load.gif"/></div>');
            },
            success: function(blob, status, xhr) {
                updateCSRFToken();
                $("#result").html('');
                $('#res_loader').html(''); // Clear the loading message
                var contentDisposition = xhr.getResponseHeader('Content-Disposition');
                var filename = "Diary_no_details-<?= date('YmdHis') ?>.csv"; // Default file name

                if (contentDisposition) {
                    var matches = /filename\*=UTF-8''(.+)$/.exec(contentDisposition) || /filename="(.+)"/.exec(contentDisposition);
                    if (matches) {
                        filename = decodeURIComponent(matches[1].replace(/"/g, ''));
                    }
                }

                // Create a download link for the CSV file
                var downloadUrl = URL.createObjectURL(blob);
                var downloadLink = `<a href="${downloadUrl}" download="${filename}" style="text-decoration: underline;">Download CSV</a>`;
                $('#message').html(`<h3 class="mb-0 text-success">CSV generated successfully! ${downloadLink}</h3>`);

                // // Optional: Read the CSV file content to display in a table
                // var reader = new FileReader();
                // reader.onload = function(e) {
                //     var text = e.target.result;
                //     var lines = text.trim().split('\n');
                //     let table = '<table class="align-items-center table table-hover table-striped"><thead class="thead-dark"><tr>';

                //     // Build the table header
                //     const headers = lines[0].split(',');
                //     headers.forEach(header => {
                //         table += `<th scope="col"><strong>${header.replace(/["]+/g, '').trim()}</strong></th>`;
                //     });
                //     table += '</tr></thead><tbody>';

                //     // Build the table rows
                //     for (let i = 1; i < lines.length; i++) {
                //         const cells = lines[i].split(',');
                //         table += '<tr>';
                //         cells.forEach(cell => {
                //             let cleanedCell = cell.trim();
                //             // Optionally bold negative numbers
                //             if (/^-\d+-\d+$/.test(cleanedCell)) {
                //                 cleanedCell = `<strong>${cleanedCell}</strong>`;
                //             }
                //             table += `<td>${cleanedCell}</td>`;
                //         });
                //         table += '</tr>';
                //     }
                //     table += '</tbody></table>';

                //     // Display the table in the result div
                //     $("#result").html(table);
                // };

                reader.readAsText(blob);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                $('#res_loader').html(''); // Clear the loading message
                $('#downloadLink').html('');
                $('#message').html('');
                $("#result").html('<h4 class="text-center text-danger mb-0">Error: ' + error + '</h4>');
            },
            complete: function() {
                updateCSRFToken();
                $("#submitPrtD").attr("disabled", false);
            }
        });

        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/ScanningController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
    });
</script>