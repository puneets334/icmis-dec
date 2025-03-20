<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($aor_signature)):?>
                <table  id="ReportRefiling" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo. </th>
                        <th>AOR Code </th>
                        <th>Name</th>
                        <th>Signature Available </th>


                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($aor_signature as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->aor_code ?></td>
                            <td><?= $row->aor_name ?></td>
                            <td> <?php if($row->if_sen=='N') echo "<font color=red> NO</font>";?></td>

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

