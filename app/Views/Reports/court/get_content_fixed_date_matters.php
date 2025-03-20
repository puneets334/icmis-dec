<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="4" style="text-align: center;">
                        <?php echo $judges_data['jname']; ?><br>
                        <?php echo $heading; ?> Date Wise Cases Ready to list
                    </th>
                </tr>
                </thead>
            </table>

            <?php  if(!empty($fdmReport)):?>
                <table  id="ReportsFixedDateMatters" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Listing Date</th>
                        <th>Court Dated</th>
                        </tr>

                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($fdmReport as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= date('d-m-Y',strtotime($row['next_dt'])) ?></td>
                            <td><a target="_blank" href="Report/judge_coram_cases_detail_get_nsh?list_dt=<?php echo $row['next_dt']?>&flag=f&jcd=<?=$judge_id;?>&misc_nmd=<?=$report_type;?>"><?=$row['fd_not_list'] ?></a></td>
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
                $("#ReportsFixedDateMatters").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
