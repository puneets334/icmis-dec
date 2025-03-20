<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($consumedBarcode)):
                $from_date = date("d-m-Y", strtotime($from_date));
                $to_date = date("d-m-Y", strtotime($to_date));
                $title = "eCopying Consumed Barcode Reports : ";

                $title .= " Dated ".$from_date." to ".$to_date;
                ?>
                <table  id="ReportRefiling" class="table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;"><?php echo $title;?></h3>
                    <tr>
                        <th>SNo. </th>
                        <th>CRN </th>
                        <th>Barcode</th>
                        <th>Consumed Date </th>


                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($consumedBarcode as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->crn ?></td>
                            <td><?= $row->barcode ?></td>
                            <td><?=isset($row->consumed_on) ? date('d-m-Y H:i:s',strtotime($row->consumed_on)) : ''?></td>

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
                var title = '<?=$title?>';
                $("#ReportRefiling").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title },{extend: 'print', title: title },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
