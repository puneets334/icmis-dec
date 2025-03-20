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
                        <h3 style="text-align: center;">Documents applied in Application Number : <?php echo $number ?></h3>
                        <?php if(isset($document) && sizeof($document)>0 ):?>
                        <tr>
                            <th>#</th>
                            <th>Order Type</th>
                            <th>Order Date</th>
                            <th>Copies Applied</th>
                        </tr></thead><tbody>
                        <?php  $i = 0;
                        $total = 0;

                        foreach ($document as $result) {
                            $i++;?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $result['order_type'];?></td>
                                <td><?php if($result['order_date']!=null) echo date('d-m-Y',strtotime($result['order_date']));
                                    else
                                        echo '';?></td>
                                <td><?php echo $result['number_of_copies'];?></td>
                            </tr>
                        <?php  } ?>

                        </tbody>
                        <?else:?>
                            <font size='18px'; color='red';>No document applied in this application!</font>
                        <?php  endif; ?>
                    </table>

                <!-- end of fileTrap -->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var title = 'Documents applied in Application Number : <?php echo $number ?>';
            $("#ReportFileTrap").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
<?=view('sci_main_footer') ?>