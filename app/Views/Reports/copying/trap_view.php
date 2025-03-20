<?php
$uri = current_url(true);
?>
<?= view('header') ?>

    <div class="card">
        <div class="card-body" >
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <?php
                if(isset($trap) && sizeof($trap)>0 ):

                    ?>
                    <table  id="ReportFileTrap" class="table table-bordered table-striped">
                        <thead>
                        <h3 style="text-align: center;">History of Status updated in Application Number : <?php echo $number ?></h3>
                        <tr>
                            <th>#</th>
                            <th>Previous Value</th>
                            <th>New Value</th>
                            <th>Updated By</th>
                            <th>Updated On</th>
                        </tr></thead><tbody>
                        <?php  $i = 0;
                        $total = 0;
                        foreach ($trap as $result) {
                            $i++;?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $result['prev'];?></td>
                                <td><?php echo $result['new'];?></td>
                                <td><?php echo $result['name']."(".$result['empid'].")";?></td>
                                <td><?php if($result['updated_on']!=null && $result['updated_on']!='0000-00-00 00:00:00')
                                        echo date('d-m-Y h:i:s A',strtotime($result['updated_on']));
                                    else
                                        echo '';?></td>
                            </tr>
                        <?php  } ?>
                        </tbody>
                    </table>
                    <center><font size="18"><a target="_blank" href="defects_history?id=<?=$id?>&num=<?=$number?>">List of Defects</a></font></center>
                <?php  endif; ?>
                <!-- end of fileTrap -->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var title = 'History of Status updated in Application Number : <?php echo $number ?>';
            $("#ReportFileTrap").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
<?=view('sci_main_footer') ?>