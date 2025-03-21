<?= view('header') ?>
<style>
table.dataTable>thead .sorting,
table.dataTable>thead {
    background-color: #0d48be !important;
    color: #fff !important;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post">
                    <?= csrf_field() ?>
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Supreme Court Scan View</h3>
                                </div>
                                <div class="col-sm-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diary_no" class="form-label">Diary Number</label>
                                        <input class="form-control" type="text" placeholder="Enter Diary Number" id="diary_no" name="diary_no" size="5" require>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year" class="form-label">Diary Year</label>
                                        <?php $year = 1950;
                                        $total = 0;
                                        $current_year = date('Y');
                                        ?>
                                        <select name="diary_year" id="year" class="custom-select rounded-0">
                                            <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                <option value="<?php echo $x; ?>" <?php echo ($x === (int)$diary_year) ? 'selected' : ''; ?>>
                                                    <?php echo $x; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3 card-footer">
                                    <button type="button" class="btn btn-primary w-25" id="getDiaryDetail">SUBMIT</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </form>
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
    $(document).ready(function() {
        function updateCSRFToken() {
            $.get('<?= site_url('Scanning/SupremeCourtScan/SupremeCourtScanController/getCSRF'); ?>', function(data) {
                $('input[name="<?= csrf_token() ?>"]').val(data.csrf_token);
            }, 'json');
        }
        $(function() {
            $('.datepicker').datepicker({
                changeYear: true, // Allows year selection
                showButtonPanel: true, // Shows the "Today" and "Done" buttons
                dateFormat: 'yy', // Only shows the year
                yearRange: 'c-100:c+10', // Range of years you want to display
                onClose: function(dateText, inst) {
                    // Prevents the month and day from being selectable
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val(year);
                }
            });

            // Open datepicker to year selection only
            $(".datepicker").focus(function() {
                $(".ui-datepicker-calendar").hide(); // Hides the month picker
            });
        });
        $('#getDiaryDetail').on('click', function(e) {
            e.preventDefault();

            var diary_no = $('#diary_no').val();
            var year = $('#year').val();

            if (!diary_no || !year) {
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Error',
                //     text: 'Please enter a "To Date".',
                // });
                alert('Please provide both Diary Number and Diary Year.');
                return;
            }

            var csrf = $('input[name="<?= csrf_token() ?>"]').val();
            $("#getDiaryDetail").attr("disabled", true);

            $.ajax({
                url: "<?= base_url('Scanning/SupremeCourtScan/SupremeCourtScanController/getDiaryDocument') ?>",
                method: 'GET',
                data: {
                    diary_no: diary_no,
                    year: year,
                    '<?= csrf_token() ?>': csrf
                },
                beforeSend: function()
                {
                    $('#message').html('<h5 class="mb-0 text-warning">Diary data loading, please wait...</h5>');
                    $('#res_loader').html('<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="<?= base_url();?>/images/load.gif"></div>');
                },
                success: function(data) {
                    $('#res_loader').html('');
                    $('#message').html('');
                    if (data.status == '1') {
                        $('#result').html(data.html); // Set the returned HTML into the #result div
                    } else {
                        updateCSRFToken();
                        $('#result').html('');
                        $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' + data.message + '</h4>');
                    }
                },
                error: function(xhr, status, error)
                {
                    updateCSRFToken();
                    $('#res_loader').html('');
                    $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' + error + '</h4>');
                    $("#getDiaryDetail").attr("disabled", false);
                },
                complete: function()
                {
                    $('#res_loader').html('');
                    $("#getDiaryDetail").attr("disabled", false);
                }
            });
        });

        $(document).ready(function() {
            window.openModal = function(fileUrl) {
                if (typeof fileUrl !== 'string') {
                    console.error("Expected a string for fileUrl but got:", typeof fileUrl);
                    alert("Error: The file URL is not valid.");
                    return;
                }

                fileUrl = decodeURIComponent(fileUrl.replace(/\\/g, '/'));

                console.log("Opening PDF at:", fileUrl);

                const pdfViewer = document.getElementById("ob_shw");

                if (fileUrl && fileUrl.endsWith('.pdf')) {
                    pdfViewer.setAttribute("data", fileUrl);
                    $('#documentModal').modal('show');
                } else {
                    updateCSRFToken();
                    console.error("Invalid PDF URL: ", fileUrl);
                    alert("Error: The PDF file could not be found.");
                }
            };

            window.closeData = function() {
                $('#documentModal').modal('hide');
                const pdfViewer = document.getElementById("ob_shw");
                pdfViewer.setAttribute("data", "");
            }
        });
    });
</script>

<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg shadow" role="document">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Document Viewer</h5>
                <button type="button" class="close" onclick="closeData()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <object id="ob_shw" style="width: 100%; height: 550px;" type="application/pdf"></object>
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-lg btn-primary col-6 py-3 m-0 rounded-0 border-end" id="pdfLink"><strong>Download PDF</strong></button>
                <button type="button" class="btn btn-lg btn-danger col-6 py-3 m-0 rounded-0" onclick="closeData()" data-bs-dismiss="modal">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>