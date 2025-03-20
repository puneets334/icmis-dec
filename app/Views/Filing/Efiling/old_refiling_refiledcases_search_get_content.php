
<?php if(isset($result) && !empty($result)){ ?>
                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <caption>
                    <h4 style="text-align: center;">
                        List of Re-Filied Cases Details between <strong><?=date('d-m-Y', strtotime($from_date)) ;?> </strong> To <strong><?=date('d-m-Y', strtotime($to_date));?> </strong>
                    </h4>
                </caption>
                    <br/>
                <table  id="DataTable_report" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Case Details</th>
                        <th>Cause Title</th>
                        <th>Re-Filed On</th>
                        <th>Re-Filed By AOR</th>
                        <th>Allocated to Scrutiny DA</th>
                        <th>EFiling No.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    foreach ($result as $row) {
                        $ef_url = $row['case_no'] != null ? E_FILING_URL."/efiling_search/DefaultController/?efiling_number=".$row['case_no'] : ''
                        ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td><?=(!empty($row['reg_no_display']) && $row['reg_no_display'] !=null) ? $row['reg_no_display'].'@'.$row['case_no'] : $row['case_no'];?></td>
                            <td><?=(!empty($row['pet_name']) && $row['pet_name'] !=null) ? $row['pet_name'] : '';?> <span style="font-weight: 600;"> Vs </span> <?=(!empty($row['pet_name']) && $row['res_name'] !=null) ? $row['res_name'] : '';?></td>
                            <td><?=(!empty($row['created_at']) && $row['created_at'] !=null) ? $row['created_at'] : '';?></td>
                            <td><?=(!empty($row['bar_adv']) && $row['bar_adv'] !=null) ? $row['bar_adv'] : '';?></td>
                            <td><?=(!empty($row['user_detail']) && $row['user_detail'] !=null) ? $row['user_detail'] : '';?></td>
                            <td><a target="_blank" href="<?=$ef_url;?>"> <?=(!empty($row['efiling_no']) && $row['efiling_no'] !=null) ? $row['efiling_no'] : '';?> </a></td>
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
                $("#DataTable_report").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });
        </script>
