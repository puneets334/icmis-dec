
<?php if(isset($case_result) && sizeof($case_result)>0 && is_array($case_result)){ ?>
                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <caption>
                    <h4 style="text-align: center;">
                        List of Sensitive Cases added between <strong><?=date('d-M-Y', strtotime($from_date)) ;?> </strong> To <strong><?=date('d-M-Y', strtotime($to_date));?> </strong>
                    </h4>
                </caption>
                <table  id="report" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width:4%;">S.No.</th>
                        <th>Diary No/Year</th>
                        <th>CauseTitle</th>
                        <th>Registration Number</th>
                        <th>Section</th>
                        <th>Sensitive Case <br> Reason</th>
                        <th>Sensitive Case <br>Entered On</th>
                        <th>Sensitive Case <br>Entered By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    foreach ($case_result as $result) { ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td><?=$result['diary_no'];?></td>
                            <td><?=$result['case_title'];?></td>
                            <td><?=$result['reg_no_display'];?></td>
                            <td><?=$result['user_section'];?></td>
                            <td><?php if($result['reason'] != null && !empty($result['reason'])) { echo $result['reason']; }  ?></td>
                            <td><?=$result['updated_on'];?></td>
                            <td><?=$result['updatedBy'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>

                </table>
        </div>
            <?php }else { ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php } ?>

        <script>
            $(function () {
                $("#report").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>
