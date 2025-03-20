<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($ReportsCaseVerification)): ?>
                <table  id="ReportsCaseVerification" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th rowspan="2">SNo.</th>
                        <th rowspan="2">Section</th>
                        <th rowspan="2">Name</th>
                        <th rowspan="2">Designation</th>
                        <th rowspan="2">Total Cases</th>
                         <?php
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 14 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                        ?>
                        <th colspan="2">Branch Officer</th>
                    <?php }
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                        ?>
                        <th colspan="2">Assistant Registrar</th>
                    <?php }
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                    ?>
                    <th colspan="2">Additional / Deputy Registrar</th>
                    <?php } ?>

                    </tr>
                    <tr>
                         <?php
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 14 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                        ?>
                        <th>Verified</th>
                        <th>Not Verified</th>
                    <?php }
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                        ?>
                        <th>Verified</th>
                        <th>Not Verified</th>
                    <?php }
                    if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                    ?>
                    <th>Verified</th>
                    <th>Not Verified</th>
                    <?php } ?>

                          </tr>
                    </thead><tbody>
                     <?php
                    $sno = 1;
                    foreach($ReportsCaseVerification as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->section_name ?></td>
                            <td><?= $row->usercode ?> / <?= $row->name ?> / <?= $row->empid ?></td>
                            <td><?= $row->type_name ?></td>


                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_1'>" . $row->da_case . "</span>"; ?></td>
                            <?php
                            if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 14 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                            ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_2'>" . $row->bo_v . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_3'>" . $row->bo_nv . "</span>"; ?></td>
                            <?php }
                            if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 9 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                            ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_4'>" . $row->ar_v . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_5'>" . $row->ar_nv . "</span>"; ?></td>
                            <?php }
                            if($_SESSION['login']['usertype'] == 1 OR $_SESSION['login']['usertype'] == 6 OR $_SESSION['login']['usertype'] == 4){
                            ?>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_6'>" . $row->dy_v . "</span>"; ?></td>
                            <td><?php echo "<span style='cursor:pointer' id='dacase_".$row->usercode."_7'>" . $row->dy_nv . "</span>"; ?></td>
                            <?php } ?>
                           
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>

            $(function () {
                $("#ReportsCaseVerification").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
