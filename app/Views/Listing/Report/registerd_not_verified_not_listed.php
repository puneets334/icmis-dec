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
                                    <h4 class="basic_heading">Registered Matters( Not Verified And Not Listed)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="push-form" method="POST" action="">
                                            <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-md-3">
                                                        <label for="">Select Case Type</label>
                                                        <select name="caseType" id="caseType" class="form-control">
                                                            <option value="All">All</option>
                                                            <?php
                                                            foreach ($data as $casedata) {
                                                            ?>
                                                                <option value="<?php echo $casedata['casecode']; ?>"><?php echo $casedata['casename']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
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
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf"]
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
        let ddl_nv_r = $('#caseType').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/Report/get_data_registerd_not_verified_not_listed'); ?>",
            cache: false,
            async: true,
            beforeSend: function() {
               
                $('#dv_data').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            data: {
                ddl_nv_r: ddl_nv_r,
                idd: 1, // idd 1 only
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(response) {
                updateCSRFToken();
                if (response.success) {
                    let html = '<div class="table-responsive"><h5 style="text-align: center;text-transform: capitalize;color: blue;" class="">Registered Matters Which Are Not Verified And Not Listed</h5><table id="example1" class="table table-striped custom-table">';
                    html += '<thead><tr><th>SNo.</th><th>Caseno@DNo</th><th>Cause Title</th><th>Filing Date</th><th>Registration Date</th><th>DA[Section]</th></tr></thead>';
                    html += '<tbody>';
                    response.data.forEach((row, index) => {
                        html += '<tr>';
                        html += `<td>${index + 1}</td>`;
                        html += `<td>${row.reg_no}<br>Diary No: ${row.diary_no}</td>`; 
                        html += `<td>${row.pet_name} <br>vs<br> ${row.res_name}</td>`; 
                        html += `<td>${row.diary_date}</td>`; 
                        html += `<td>${row.reg_date}</td>`;
                        html += `<td>${row.daname}<br>Section: ${row.section}</td>`; 
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table></div>';
                    $('#dv_data').html(html);
                    // $("#csrf_token").val(response.csrfHash);
                    // $("#csrf_token").attr('name', response.csrfName);
                    updateCSRFToken();

                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "dom": 'Bfrtip',
                        "bProcessing": true,
                        "buttons": ["excel", "pdf"]
                    });
                } else {
                    updateCSRFToken();
                    $('#dv_data').html('<div style="text-align: center"><b>No Record Found</b></div>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    }
</script>