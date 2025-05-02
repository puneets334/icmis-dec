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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Verification Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="">From Date</label>
                                                        <input type="text" name="txt_fd" id="txt_fd" class="dtp form-control" value="<?php echo date('d-m-Y') ?>" size="8" maxlength="10" />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">To Date</label>
                                                        <input type="text" name="txt_td" id="txt_td" class="dtp form-control" value="<?php echo date('d-m-Y') ?>" size="8" maxlength="10" />
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for=""></label>
                                                        <input type="button" name="btn_ft" id="btn_ft" value="Submit" onclick="sub_dt()" class="btn btn-primary mt-4" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="dv_data"></div>
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
    $(document).ready(function() {
        var reportTitle = "Verification Report";
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": [
                        {
                        extend: 'excelHtml5',
                        title: reportTitle
                        },
                        {
                        extend: 'pdfHtml5',
                        title: reportTitle
                        }
                        ]
                        });
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function sub_dt() {
        let txt_fd = $('#txt_fd').val();
        let txt_td = $('#txt_td').val();
        let csrfName = $("#csrf_token").attr('name');
        let csrfHash = $("#csrf_token").val();
        var reportTitle = "Verification Report";

        $.ajax({
            url: "<?php echo base_url('Listing/Report/get_verification'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                txt_fd: txt_fd,
                txt_td: txt_td,
                [csrfName]: csrfHash
            },
            cache: false,
            success: function(response) {

                if (response.success) {

                    let html = '<div class="table-responsive"><table id="example1" class="table table-striped custom-table">';
                    html += '<thead><tr><th>SNo.</th><th>Diary No.</th><th>Registration Date</th><th>Verification Date</th><th>Difference</th></tr></thead>';
                    html += '<tbody>';
                    response.data.forEach((row, index) => {
                        let date = new Date(row.fil_dt);
                        let formattedDate = ("0" + date.getDate()).slice(-2) + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" +
                            date.getFullYear();
                        let verificationDate = new Date(row.verification_date);
                        let formattedVerificationDate = ("0" + verificationDate.getDate()).slice(-2) + "-" +
                            ("0" + (verificationDate.getMonth() + 1)).slice(-2) + "-" +
                            verificationDate.getFullYear();
                        let duration = row.s; 
                        let days = duration.split(" ")[0];

                        html += '<tr>';
                        html += `<td>${index + 1}</td>`;
                        html += `<td>${row.diary_no.slice(0, -4)}-${row.diary_no.slice(-4)}</td>`;
                        html += `<td>${formattedDate}</td>`;
                        html += `<td>${formattedVerificationDate}</td>`;
                        html += `<td>${days}</td>`;
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table></div>';
                    $('#dv_data').html(html);
                    $("#csrf_token").val(response.csrfHash);
                    $("#csrf_token").attr('name', response.csrfName);


                    // Initialize DataTable
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "dom": 'Bfrtip',
                        "bProcessing": true,
                        "buttons": [
                        {
                        extend: 'excelHtml5',
                        title: reportTitle
                        },
                        {
                        extend: 'pdfHtml5',
                        title: reportTitle
                        }
                        ]
                    });
                } else {
                    $('#dv_data').html('<div style="text-align: center"><b>No Record Found</b></div>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    }
</script>