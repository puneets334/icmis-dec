<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataUserwise)):
                if($from_date==$to_date)
                    $heading="User Wise Applications received on ".date('d-m-Y',strtotime($from_date));
                else
                    $heading="User Wise Applications received from ".date('d-m-Y',strtotime($from_date))." to ".
                        date('d-m-Y',strtotime($to_date));
                ?>
                <table  id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;"><?=$heading?></h3>
                    <tr>
                        <th>SNo.</th>
                        <th>Name</th>
                        <th>A1</th>
                        <th>A2</th>
                        <th>B</th>
                        <th>C</th>
                        <th><b>Total</b></th>

                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    $total_cat_1 = $total_cat_2= $total_cat_3 = $total_cat_4= $total_all= 0;
                    foreach($dataUserwise as $row):
                        $total_cat_1 = $total_cat_1+$row->catg1;
                        $total_cat_2 = $total_cat_2+$row->catg2;
                        $total_cat_3 = $total_cat_3+$row->catg3;
                        $total_cat_4 = $total_cat_4+$row->catg4;
                        $total_all = $total_cat_1 + $total_cat_2 + $total_cat_3 + $total_cat_4;
                        ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->name ?>(<?= $row->empid ?>)</td>
                            <td><a target="_blank" href="Report/user_cases?category=1&user=<?php echo $row->adm_updated_by;?>&from_date=<?=$from_date?>&to_date=<?=$to_date?>"> <?php echo $row->catg1;?></a></td>
                            <td><a target="_blank" href="Report/user_cases?category=2&user=<?php echo $row->adm_updated_by;?>&from_date=<?=$from_date?>&to_date=<?=$to_date?>"> <?= $row->catg2 ?></a></td>
                            <td><a target="_blank" href="Report/user_cases?category=3&user=<?php echo $row->adm_updated_by;?>&from_date=<?=$from_date?>&to_date=<?=$to_date?>"> <?= $row->catg3 ?></a></td>
                            <td><a target="_blank" href="Report/user_cases?category=4&user=<?php echo $row->adm_updated_by;?>&from_date=<?=$from_date?>&to_date=<?=$to_date?>"> <?= $row->catg4 ?></a></td>
                            <td><?= $row->catg1+$row->catg2+$row->catg3+$row->catg4 ?></td>
                            </tr>


                    <?php endforeach; ?>
                    </tbody><tfoot>
                    <tr>
                        <th colspan="2" class="text-right text-bold">Total </th>
                        <th><?php echo $total_cat_1;?></th>
                        <th><?php echo $total_cat_2;?></th>
                        <th><?php echo $total_cat_3;?></th>
                        <th><?php echo $total_cat_4;?></th>
                        <th><?php echo $total_all;?></th>
                    </tr>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function () {
                var title = '<?=$heading?>';
                $("#query_builder_report").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,"paging":false,
                    "buttons": ["copy", "csv","excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' ,title: title},{extend: 'print', title: title },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');


            });

        </script>
