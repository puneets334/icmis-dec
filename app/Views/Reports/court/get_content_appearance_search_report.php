<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($appearanceSearchData)):?>
                <table  id="ReportsVJR" class="table table-bordered table-striped">
                    <thead>
                        <tr>

                        <th>Item No.</th>
                        <th>Case No.</th>
                        <th>Cause Title</th>
                        <th>Name of Advocates</th>

                        </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;

                    foreach($appearanceSearchData as $row):?>
                        <?php
                        if($row['pno'] == 2){
                            $pet_name = $row['pet_name']." AND ANR.";
                        }
                        else if($row['pno'] > 2){
                            $pet_name = $row['pet_name']." AND ORS.";
                        }
                        else{
                            $pet_name = $row['pet_name'];
                        }
                        if($row['pno'] == 2){
                            $res_name = $row['res_name']." AND ANR.";
                        }
                        else if($row['pno'] > 2){
                            $res_name = $row['res_name']." AND ORS.";
                        }
                        else{
                            $res_name = $row['res_name'];
                        }
                        ?>

                        <tr>

                            <td><?= $row['item_no'] ?></td>
                            <td><?=$row['reg_no_display'] ?: 'Diary No. '.$row['diary_no'];?></td>
                            <td><?=$pet_name?><br>
                                Vs.<br>
                                <?=$res_name?></td>
                            <td><?php echo "<br><u>Added By</u> - ".ucwords(strtolower($row['title'].' '.$row['name']))." : <br>"; ?><ul><?php

                                        echo "<li>".$row['advocate_title'].' '.$row['advocate_name'].', '.$row['advocate_type']."</li>";

                                    ?></ul></td>




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
                $("#ReportsVJR").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
