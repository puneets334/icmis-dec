<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($Reportsrefiling)):?>
                <table  id="ReportRefiling" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo. </th>
                        <th>Diary No </th>
                        <th>FDR User</th>
                        <th>Refiling Date </th>
                        <th>Scrutiny User </th>
                        <th>Token No.</th>
                        <th>Remarks</th>

                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($Reportsrefiling as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->dn ?>/<?= $row->dy ?></td><td><?=$row->dispatch_by ?></td>
                            <td><?=isset($row->disp_dt) ? date('d-m-Y',strtotime($row->disp_dt)) : ''?></td>
                            <td><?= $row->dispatch_to ?> <?php if($row->attend=='A') echo "<font color=red> [Absent]</font>";?></td>
                            <td><?= $row->token_no ?></td>
                            <td><?= $row->remarks ?></td>
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
                $("#ReportRefiling").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
