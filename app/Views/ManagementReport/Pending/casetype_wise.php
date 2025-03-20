<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Case Type Wise</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="printable" class="box box-danger2">
                            <caption>
                                <h3 style="text-align: center;"> Case Type Wise Pendency (Excluding Un-Registered Listed Cases) <br> as on <?php echo date("d-m-Y h:i:s A"); ?></h3>
                            </caption>


                            <table id="reportTable1" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Case Type</th>
                                        <th>Main</th>
                                        <th>Connected</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $s_no = 1;
                                    foreach ($result_array as $result) {
                                    ?>
                                        <tr>
                                            <td><?= $s_no; ?></td>
                                            <td><?= $result['casename']; ?></td>
                                            <td><?= $result['main']; ?></td>
                                            <td><?= $result['connected']; ?></td>
                                            <td><?= $result['total']; ?></td>
                                        </tr>
                                    <?php
                                        $s_no++;
                                    }
                                    ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $(function() {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });

        var t = $('#reportTable1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ],
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [
                [1, 'asc']
            ]

        });
        t.on('order.dt search.dt', function() {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
                t.cell(cell).invalidate('dom');
            });
        }).draw();
    });
</script>