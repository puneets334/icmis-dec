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
                                    <h4 class="basic_heading">Not Verified</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <!-- <label for="">User Type</label> -->
                                                        <select name="ddl_nv_r" id="ddl_nv_r" class="form-control">
                                                            <option value="2">Not Verified</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 text-center">
                                                        <input type="button" name="btn_ft" id="btn_ft" value="Submit" onclick="sub_dt()" class="btn btn-primary mt-5" />
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
    function sub_dt() {
        let csrfName = $("#csrf_token").attr('name');
        let csrfHash = $("#csrf_token").val();
        let ddl_nv_r = $('#ddl_nv_r').val();
        var reportTitle = "Not Verified Report";
        $.ajax({
            url: "<?php echo base_url('Listing/Report/getNotVerified'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
            data: {
                ddl_nv_r: ddl_nv_r,
                [csrfName]: csrfHash
            },
            cache: false,
            success: function(response) {
                if (response.success) {
                    let html = '<div class="table-responsive"><table id="example1" class="table table-striped custom-table">';
                    html += '<thead><tr><th>SNo.</th><th>Diary No.</th><th>Diary No Receiving Date</th><th>Difference</th></tr></thead>';
                    html += '<tbody>';
                    response.data.forEach((row, index) => {
                        html += '<tr>';
                        html += `<td>${index + 1}</td>`;
                        html += `<td>${row.diary_no.slice(0, -4)}-${row.diary_no.slice(-4)}</td>`;
                        html += `<td>${new Date(row.diary_no_rec_date).toLocaleDateString('en-GB')}</td>`;
                        html += `<td>${row.s}</td>`;
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table></div>';
                    $('#dv_data').html(html);
                    $("#csrf_token").val(response.csrfHash);
                    $("#csrf_token").attr('name', response.csrfName);
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