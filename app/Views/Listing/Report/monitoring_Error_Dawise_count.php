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
                                    <h4 class="basic_heading">Error DA Wise Count</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                        

                                                    <?php
                                                    //if(is_array($reports))
                                                    if (isset($case_result) && sizeof($case_result) > 0 && is_array($case_result))
                                                    {
                                                        ?>
                                                        <caption>
                                                            <h3 style="text-align: center;"><strong> Monitoring Error Report as on <?php echo date('d-m-Y h:m:s A') ?></strong></h3>
                                                        </caption>
                                                        <div class="table-responsive">
                                                            <table id="reportTable1" class="table table-striped custom-table showData">
                                                                <thead>
                                                                    <tr>
                                                                        <th>SNo.</th>
                                                                        <th>DA</th>
                                                                        <th>Court Remark</th>
                                                                        <th>SubHead</th>
                                                                        <th>Purpose</th>
                                                                        <th>Cause Title</th>
                                                                        <th>AOR NA</th>
                                                                        <th>Statutary Info</th>
                                                                        <th>Proposal Missing</th>
                                                                        <th>IA</th>
                                                                        <th>ROP</th>
                                                                        <th>Before/NotBefore</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $s_no = 1;
                                                                    foreach ($case_result as $result)
                                                                    {
                                                                        $sum_row = $result['courtremark'] + $result['subhead'] + $result['purpose'] + $result['causetitle'] + $result
                                                                        ['aor_na']  + $result['statutaryinfo'] + $result['proposalmissing'] + $result['ia'] + $result['rop'] + $result['before_notbefore'];
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= $s_no; ?></td>
                                                                            <td><?php echo $result['daname']; ?></td>
                                                                            <td><?php echo $result['courtremark']; ?></td>
                                                                            <td><?php echo $result['subhead']; ?></td>
                                                                            <td><?php echo $result['purpose']; ?></td>
                                                                            <td><?php echo $result['causetitle']; ?></td>
                                                                            <td><?php echo $result['aor_na']; ?></td>
                                                                            <td><?php echo $result['statutaryinfo']; ?></td>
                                                                            <td><?php echo $result['proposalmissing']; ?></td>
                                                                            <td><?php echo $result['ia']; ?></td>
                                                                            <td><?php echo $result['rop']; ?></td>
                                                                            <td><?php echo $result['before_notbefore']; ?></td>
                                                                            <td><?php echo $sum_row; ?></td>
                                                                        </tr>
                                                                        <?php
                                                                        $s_no++;
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <?PHP
                                                    }
                                                    ?>
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
    var reportTitle = "Error DA Wise Count";
        $("#reportTable1").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing" : true,
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
</script>