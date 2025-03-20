<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($ReportsLooseDocUserWise)): //print_r($ReportsLooseDocUserWise); exit;?>
                <table  id="ReportsLooseDocUserWise" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>SNo.</th><th>Date</th><th>Total</th><th >Verify</th><th>Not Verify</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($ReportsLooseDocUserWise as $row):?>

                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= date('d-m-Y',strtotime($row->date1)) ?></td>
                            <td><?= $row->total ?></td>
                            <td><?= $row->verify ?></td>
                            <td><?= $row->not_verify ?></td>
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
                $("#ReportsLooseDocUserWise").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
