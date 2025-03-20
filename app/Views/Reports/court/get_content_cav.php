<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataCAV)):?>
                <table  id="ReportsCAV" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Case Number @ Diary No</th>
                        <th>Title</th>
                        <th>Listed  On</th>
                        <th>Previously Listed or Next hearing Date</th>
                        <th>Coram</th>
                        <th>Status</th>
                        <th>Last order</th>
                       </tr>
                     </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataCAV as $result):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?php echo $result['regno_dno'];?></td>
                            <td><?php echo $result['title'];?></td>
                            <td><?php echo $result['listed_on'];?></td>
                            <td><?php echo $result['previously_listed_or_next_listing_dt'];?></td>
                            <td><?php echo $result['coram'];?></td>
                            <td><?php echo $result['status'];?></td>
                            <td><?php echo $result['lastorder'];?></td>
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
                $("#ReportsCAV").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
