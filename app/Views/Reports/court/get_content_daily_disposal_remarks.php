<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <h5 class="text-center">Report as on <?=$disposalon_date;?></h5>
            <?php  if(!empty($dataDisposalRemarks)):?>
                <table  id="ReportsDailyDispRemarks" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Ct No.</th>
                        <th>Item No.</th>
                        <th>Updated By</th>
                        <th>Diary No.</th>
                        <th>Titled As</th>
                        <th>Main or Connected</th>
                        <th>Connected With DNo.</th>
                        <th>Remarks</th>
                       </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataDisposalRemarks as $result):?>
                        <tr>
                            <td><?= $sno++ ?></td>

                            <td><?php echo $result['courtno'];?></td>
                            <td><?php echo $result['brd_prnt'];?></td>
                            <td><?php echo $result['uid'];?></td>
                            <td><?php echo $result['diary_no'].'/'.$result['diary_year'];?></td>
                            <td><?php echo $result['pet_name'].' vs. '.$result['res_name'];?></td>
                            <td><?php echo $result['mainorconn']==='M'?'Main':'Connected';?></td>
                            <?php if($result['main_diary_no']!='') {?>
                                <td><?php echo $result['main_diary_no'].'/'.$result['main_diary_year'];?></td>
                            <?php } else{ ?>
                                <td>No Connected</td>
                            <?php } ?>
                            <td><?php echo $result['rmrk_disp'];?></td>
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
                $("#ReportsDailyDispRemarks").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
