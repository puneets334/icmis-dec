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
                                <h3 class="card-title">CJI Office</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Cause List</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="" method="POST" action="">
                                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="">List Date</label>
                                                        <input type="text" name="txt_frm_dt" id="txt_frm_dt" class="dtp form-control" maxlength="10" size="9" value=""
                                                            aria-describedby="list_date_addon" placeholder="Date..." readonly
                                                            <?php echo date('d-m-Y'); ?> />

                                                    </div>

                                                    <div>
                                                        <input id="btn_search" name="btn_search" type="button" class="btn btn-success ml-3 mt-4" value="Search">
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

        $(document).on('click', '#btn_search', function() {
            let txt_frm_dt = $('#txt_frm_dt').val();
            let csrfName = $("#csrf_token").attr('name');
            let csrfHash = $("#csrf_token").val();
            $.ajax({
                url: "<?php echo base_url('Cji/CauseList/cause_list_process'); ?>",
                headers: {
                    'X-CSRF-Token': $("#csrf_token").val()
                },
                cache: false,
                async: true,
                beforeSend: function() {
                    $('#dv_data').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url('/images/load.gif'); ?>"/></td></tr></table>');
                },
                data: {
                    txt_frm_dt: txt_frm_dt,
                    [csrfName]: csrfHash
                },
                type: 'POST',
                success: function(response) {
                    if (response.success) {

                        let html = '<div class="table-responsive"><table id="example1" class="table table-striped custom-table">';
                        html += '<thead><tr><th>S.No.</th><th>Case No.</th><th>Cause Title</th>';
                        if (response.usercode === '9796') {
                            html += '<th>Listed/Updated For</th>';
                        }
                        let today = new Date();
                        let dayAfterTomorrow = new Date();
                        dayAfterTomorrow.setDate(today.getDate() + 2);
                        response.data.forEach((row, index) => {
                            let nextDate = new Date(row.next_dt);
                            let color = 'light-gray';
                            let displayStyle = 'display:none;';

                            if (row.clno != 0 && row.brd_slno != 0 && nextDate >= today && nextDate <= dayAfterTomorrow) {
                                color = 'red';
                                if (response.category == 1 || response.category == 0) {
                                    displayStyle = '';
                                }
                            } else if (row.clno != 0 && row.brd_slno != 0 && nextDate >= today) {
                                color = 'orange';
                                if (response.category == 2 || response.category == 0) {
                                    displayStyle = '';
                                }
                            } else {
                                if (response.category == 0) {
                                    displayStyle = '';
                                }
                            }

                            html += `<tr style="background-color: ${color}; ${displayStyle}">`;
                            html += `<td>${index + 1}</td>`;
                            html += `<td>${row.case_no}</td>`;
                            html += `<td>${row.cause_title}</td>`;
                            if (response.usercode === '9796') {
                                html += `<td>${new Date(row.next_dt).toLocaleDateString('en-GB')}</td>`;
                            }
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
                            "buttons": ["excel", "pdf"]
                        });
                    } else {
                        $('#dv_data').html('<div style="text-align: center"><b>No Record Found</b></div>');
                        $("#csrf_token").val(response.csrfHash);
                        $("#csrf_token").attr('name', response.csrfName);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + jqXHR.status + " " + errorThrown);
                }
            });
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
</script>