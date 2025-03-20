<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($ReportsSensitiveMattersPendingandNotReady)): //print_r($ReportsLooseDocUserWise); exit;?>
                <table  id="ReportsSensitiveMattersPendingandNotReady" class="table table-bordered table-striped">
                    <thead>
                    <tr><th>SNo</th>
                        <th>Case@Dno</th>
                        <th>Cause Title</th>
                        <th>Ready/NotReady</th>
                        <th>Category</th>
                        <th>Sensitive Note</th>
                        <th>Note Entered By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    foreach($ReportsSensitiveMattersPendingandNotReady as $row):?>
                       <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->CaseNo_DiaryNo ?></td>
                            <td><?= $row->cause_title ?></td>
                           <td><?= $row->ready_not_ready ?></td>
                            <td><?= $row->category ?> - <?= isset($row->Subject_category)?$row->Subject_category:'' ?></td>
                            <td><?= $row->Sensitive_Note ?></td>
                            <td><?= $row->Sensitive_Updated_by ?></td>
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
                $("#ReportsSensitiveMattersPendingandNotReady").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
