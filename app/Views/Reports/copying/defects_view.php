<?php
$uri = current_url(true);
?>
<?= view('header') ?>

    <div class="card">
        <div class="card-body" >
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <?php


                    ?>
                    <table  id="ReportFileTrap" class="table table-bordered table-striped">
                        <thead>
                        <h3 style="text-align: center;">History of Defects updated in Application Number : <?php echo $number ?></h3>

                        <tr>
                            <th>#</th>
                            <th>Defects Description</th>
                            <th>Notified By</th>
                            <th>Notified On</th>
                            <th>Cured By</th>
                            <th>Cured On</th>
                        </tr></thead><tbody>
                        <?php  $i = 0;
                        $total = 0;
                        if(isset($defects) && sizeof($defects) > 0 ):
                        foreach ($defects as $result) {
                            $i++;?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $result['description'].$result['remark'];?></td>
                                <td><?php echo $result['def_name']."(".$result['def_empid'].")";?></td>
                                <td><?php if($result['defect_notification_date']!=null && $result['defect_notification_date']!='0000-00-00 00:00:00')
                                        echo date('d-m-Y h:i:s A',strtotime($result['defect_notification_date']));
                                    else
                                        echo '';?></td>
                                <td><?php if($result['cure_name']!=null && $result['cure_name']!='')
                                        echo $result['cure_name']."(".$result['cure_empid'].")";
                                    else
                                        echo "<font color='red'>". 'Not Cured'."</font>";?></td>
                                <td><?php if($result['defect_cure_date']!=null && $result['defect_cure_date']!='0000-00-00 00:00:00')
                                        echo date('d-m-Y h:i:s A',strtotime($result['defect_cure_date']));
                                    else
                                        echo '';?></td>
                            </tr>
                        <?php  } ?>
                        <?php  endif; ?>
                        </tbody>

                    </table>

                <!-- end of fileTrap -->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var title = 'History of Defects updated in Application Number : <?php echo $number ?>';
            $("#ReportFileTrap").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
<?=view('sci_main_footer') ?>