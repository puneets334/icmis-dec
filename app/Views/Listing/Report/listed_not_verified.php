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
                                    <h4 class="basic_heading">Listed Not Verified</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                                <?= csrf_field() ?>
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
                                                        <input type="button" name="btn_ft" id="btn_ft" value="Submit" onclick="sub_dt()" class="btn btn-primary mt-4" />
                                                    </div>
                                                </div>
                                                <div id="dv_data"></div>
                                            </form>
                                        </div>
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
        var reportTitle = "Listed Not Verified Reports";
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
            pageSize: 'A3',
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
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var reportTitle = "Listed Not Verified Reports";
        $.ajax({
            url: "<?php echo base_url('Listing/Report/get_listed_not_verified'); ?>",
            cache: false,
            async: true,
            beforeSend: function() {
            $('#dv_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                txt_fd: txt_fd,
                txt_td: txt_td,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(response) {
                updateCSRFToken();
                if (response.success) {
                    let html = '<div class="table-responsive"><table id="example1" class="table table-striped custom-table">';
                    html += '<thead><tr><th>SNo.</th><th>Diary No.</th><th>Case No.</th><th>Listed Date</th><th>Petitioner</th><th>Respondent</th></tr></thead>';
                    html += '<tbody>';
                    response.data.forEach((row, index) => {
                        let diaryNoFormatted = row.diary_no.slice(0, -4) + '-' + row.diary_no.slice(-4);
                        let caseNo = '-';
                        if (row.active_fil_no && row.active_fil_no !== '') {
                            let caseCode = row.active_fil_no.slice(0, 2);
                            let caseYear = row.active_fil_dt;
                            caseNo = `${caseCode} ${row.active_fil_no.slice(3)}/${caseYear}`;
                        }

                        let listedDate = new Date(row.next_dt).toLocaleDateString('en-GB');
                        html += '<tr>';
                        html += `<td>${index + 1}</td>`; 
                        html += `<td>${diaryNoFormatted}</td>`; 
                        html += `<td>${caseNo}</td>`; 
                        html += `<td>${listedDate}</td>`; 
                        html += `<td>${row.pet_name}</td>`; 
                        html += `<td>${row.res_name}</td>`;
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table></div>';
                    $('#dv_data').html(html);
                    // $("#csrf_token").val(response.csrfHash);
                    // $("#csrf_token").attr('name', response.csrfName);

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
                            pageSize: 'A3',
                            title: reportTitle
                            }
                            ]
                    });
                } else {
                    $('#dv_data').html('<div style="text-align: center"><b>No Record Found</b></div>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    }
</script>