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
                                    <h4 class="basic_heading">Monitoring Error Report As On <?php echo date('d-m-Y h:m:s A') ?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <div class="table-responsive">
                                                <table id="reportTable1" class="table table-striped custom-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;" rowspan='1'>S.No.</th>
                                                            <th style="width: 10%;" rowspan='1'>Case No.</th>
                                                            <th style="width: 10%;" rowspan='1'>DA</th>
                                                            <th style="width: 5%;" rowspan='1'>Section</th>
                                                            <th style="width: 10%;" rowspan='1'>Remarks Given By.</th>
                                                            <th style="width: 10%;" rowspan='1'>Remarks Head</th>
                                                            <th style="width: 10%;" rowspan='1'>Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $s_no = 1;
                                                        foreach ($case_result as $result) {
                                                        ?>
                                                            <tr>
                                                                <td><?= $s_no; ?></td>
                                                                <td><?php echo $result['caseno']; ?></td>
                                                                <td><?php echo $result['daname']; ?></td>
                                                                <td><?php echo $result['section_name']; ?></td>
                                                                <td><?php echo $result['rmrkby']; ?></td>
                                                                <td><?php echo $result['remarks']; ?></td>
                                                                <td><button class="btn btn-primary" data-toggle="modal" data-target="#modal-default" onclick="get_detail(<?php echo $result['id']; ?>,<?php echo $result['diary_no']; ?>);"> <?php echo $result['count_remarks']; ?></button></td>


                                                                </button>
                                                            </tr>
                                                        <?php
                                                            $s_no++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal fade" id="modal-default" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel">Error Track Report</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table width="100%" id="reportTable2" class="table table-striped custom-table">
                                                                    <?= csrf_field() ?>
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2" style="text-align: center;">
                                                                                <h3>Error Track Report</h3>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>S.No.</th>
                                                                            <th>Tracked Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" onclick="closeModal()">Close</button>
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



    function get_detail(id, diary_no) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('#reportTable2 tbody').empty(); // Clear previous data

        $.ajax({
            url: "<?= base_url('Listing/Report/monitoring_Error_Details'); ?>",
            type: "POST",
            data: {
                id: id,
                diary_no: diary_no,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            dataType: "json",
            success: function(data) {
                updateCSRFToken();

                $('#reportTable2 tbody').empty();
                let sno = 1;

                $.each(data, function(index, item) {
                    $('#reportTable2 tbody').append(
                        "<tr><td>" + sno + "</td><td>" + item.ent_dt + "</td></tr>"
                    );
                    sno++;
                });

                $("#modal-default").modal('show');
            },
            error: function() {
                updateCSRFToken();
                console.log('Error fetching data');
            }
        });
    }


    function closeModal() {
      
        // var modal = document.getElementById('modal-default');
        
        // var modalInstance = bootstrap.Modal.getInstance(modal);

        // if (!modalInstance) {
        //     modalInstance = new bootstrap.Modal(modal);
        // }

        // modalInstance.hide();
        $("#modal-default").modal('hide');
    }
</script>