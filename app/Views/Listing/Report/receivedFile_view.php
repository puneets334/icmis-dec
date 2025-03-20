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
                                    <h4 class="basic_heading">Pending For Scan Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="" method="POST" action="">
                                            <?= csrf_field() ?>
                                                <input type="hidden" name="ddl_users" id="ddl_users" value="<?= $usercode ?>">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="">Received From Date</label>
                                                        <input type="text" name="txt_frm_dt" id="txt_frm_dt" class="dtp form-control" maxlength="10" size="9" value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">To</label>
                                                        <input type="text" name="txt_to_dt" id="txt_to_dt" class="dtp form-control" maxlength="10" size="9" value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <!-- <label for="">Select Case Type</label> -->
                                                        <select id="ddlCategory" name="ddlCategory" class="form-control mt-4">
                                                            <option value="0">ALL</option>
                                                            <option value="1">Red</option>
                                                            <option value="2">Orange</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <input type="button" name="btn_submit" id="btn_submit" value="Submit" class="btn btn-primary mt-4" />
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
        // $("#reportTable1").DataTable({
        //     "responsive": true,
        //     "lengthChange": false,
        //     "autoWidth": false,
        //     "dom": 'Bfrtip',
        //     "bProcessing": true,
        //     "buttons": ["excel", "pdf"]
        // });

        $(document).on('click', '#btn_submit', function()
        {
            let txt_frm_dt = $('#txt_frm_dt').val();
            let txt_to_dt = $('#txt_to_dt').val();
            let category = $('#ddlCategory').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/Report/getReceivedFile'); ?>",
                cache: false,
                async: true,
                beforeSend: function()
                {
                    $('#dv_data').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
                },
                data: {
                    txt_frm_dt: txt_frm_dt,
                    txt_to_dt: txt_to_dt,
                    category: category,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(response)
                {
                    updateCSRFToken();
                    $('#dv_data').html(response);
                  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    updateCSRFToken();
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