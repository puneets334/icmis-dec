<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($mdtmReport)):?>
                <table  id="ReportMatterDisposalThroughMM" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Diary Number</th>
                        <th>Case Number</th>
                        <th>Mentioned On</th>
                        <th>Disposed On</th>
                        <th>Disposed By</th>
                    </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($mdtmReport as $row):?>
                        <tr>

                            <td><?= $sno++ ?></td>
                            <td><?= $row->dn ?> / <?= $row->dy ?></td>
                            <td><?= $row->reg_no_display ?></td>
                            <td><?= date('d-m-Y',strtotime($row->date_of_received)) ?></td>
                            <td><?= date('d-m-Y',strtotime($row->ord_dt)) ?></td>
                            <td><?= $row->name." (".$row->empid.")/".$row->section_name ?></td>

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
                $("#ReportMatterDisposalThroughMM").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
