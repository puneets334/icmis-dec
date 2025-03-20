<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($mdtmReport)):?>
                <table  id="ReportsCaseAllotted" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th rowspan="2">S.No.</th>
                        <th rowspan="2">Employee Name</th>
                        <th colspan="3">Total Cases</th>
                        <th colspan="2">
                            Total Work Done
                        </th>
                    </tr>
                    <tr>
                        <th>Alloted</th>
                        <th>Completed</th>
                        <th>Remaining</th>
                        <th>Received</th>
                        <th>Dispatched</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($mdtmReport as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->name ?></td>
                            <td><?= $row->ss ?></td>
                            <td><?= $row->ss ?></td>
                            <td><?= $row->ss ?></td>
                            <td><?= $row->ss ?></td>
                            <td><?= $row->ss ?></td>
                           
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>

            $(function () {
                $("#ReportsCaseAllotted").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
