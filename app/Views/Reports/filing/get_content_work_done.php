<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($reports)):?>
                <table  id="ReportCaveat" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Empid</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Office Report Prepared</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sno = 1; $total_sum=0;$office_report_details_count=0;
                    foreach($reports as $row){
                        $total_sum=$row['total_sum'];
                        $usercode=$row['usercode'];
                        ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?=$row['empid'];?></td>
                            <td><?=$row['name'];?></td>
                            <td><?=$row['type_name'];?></td>
                            <td><?php echo "<span  style='cursor:pointer' id='off_$usercode'>".$total_sum."</span>"; ?></td>

                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>

        </div>
        <script>

            $(function () {
                $("#ReportCaveat").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>
