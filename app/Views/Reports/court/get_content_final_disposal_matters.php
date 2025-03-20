<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataFDM)):?>
                <table  id="ReportsFinalDispMatters" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Case No</th>
                        <th>Cause Title</th>
                        <th>Tentative Date List</th>
                    </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataFDM as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row['case_no'] ?></td>
                            <td><?= $row['cause_title'] ?></td>
                            <td><?= date('d-m-Y',strtotime($row['tentative_list_date'])) ?></td>


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
                $("#ReportsFinalDispMatters").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
