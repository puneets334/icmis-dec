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
                <div class="card">


                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Scanning >> Supreme Court Scan >> HC DC Indexing View/Download</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <form id="dateForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_frm_date" class="col-md-4 col-form-label">From Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_frm_date"
                                                name="txt_frm_date" placeholder="From Date" require>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txt_to_date" class="col-md-4 col-form-label">To Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control datepicker" id="txt_to_date"
                                                name="txt_to_date" placeholder="To Date" require>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ddl_dt_type" class="col-form-label">Select Date Type</label>
                                        <select class="form-control select-box" name="ddl_status" id="ddl_status">
                                            <option value="12">All</option>
                                            <option value="1">Completed</option>
                                            <option value="2">Not Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4 card-footer">
                                    <button type="button" id="submitHdcIForm" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </div>

                            <div class="text-center" id="message"></div>
                            <div class="ml-3 mr-3">

                                <table class="table table-hover table-striped table-bordered" id="hcdc_index">
                                    <thead>
                                        <tr>
                                            <th scope="col"><strong>S.No.</strong></th>
                                            <th scope="col"><strong>Diary No.</strong></th>
                                            <th scope="col"><strong>Case No.</strong></th>
                                            <th scope="col"><strong>Status</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>

                        </form>
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

$(document).ready(function() {
    var table = $('#hcdc_index').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"],

        "columns": [{
                "data": "srno"
            },
            {
                "data": "diary_no"
            },
            {
                "data": "type_sname"
            },
            {
                "data": "conformation"
            },
        ]
    });

    $('#submitHdcIForm').on('click', function(e) {
        e.preventDefault();
        var txt_frm_date = $("#txt_frm_date").val();
        var txt_to_date = $("#txt_to_date").val();
        var ddl_status = $('#ddl_status').val();

        if (!txt_frm_date) {
            alert('Please enter a "From Date"');
            return;
        }
        if (!txt_to_date) {
            alert('Please enter a "To Date"');
            return;
        }
        if (!ddl_status) {
            alert('Please select a "Date Type"');
            return;
        }

        $("#submitHdcIForm").attr("disabled", true);
        var csrf = $('input[name="<?= csrf_token() ?>"]').val();
        $.ajax({
            url: '<?= base_url('Scanning/SupremeCourtScan/SupremeCourtScanController/fetchDetails') ?>',
            method: 'GET',
            data: {
                txt_frm_date: txt_frm_date,
                txt_to_date: txt_to_date,
                ddl_status: ddl_status,
                '<?= csrf_token() ?>': csrf
            },
            beforeSend: function() {
                $('#res_loader').html(
                    '<div style="position: absolute;top: 50%;left: 50%;text-align: center;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);"><img src="<?= base_url(); ?>/images/load.gif"/></div>'
                    );
            },
            success: function(data) {
                if (data.status == '1') {
                    $('#res_loader').html('');
                    $("#message").html('');
                    // console.log(data.html);
                    table.clear();
                    table.rows.add(data.html);
                    table.draw();


                    // $('#result').html(data.html);
                } else {
                    updateCSRFToken();
                    $('#res_loader').html('');
                    $('#result').html('');
                    table.clear();
                    table.draw();
                    $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' + data.message + '</h4>');
                }
                $("#submitHdcIForm").attr("disabled", false);

            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                alert('Something went wrong!');
                $('#sub').attr('disabled', false);
                $("#message").html('<h4 class="text-center text-danger mb-0">Error: ' +
                    error + '</h4>');
            },
            complete: function() {
                $("#submitHdcIForm").attr("disabled", false);
            }
        });

    });

});
</script>