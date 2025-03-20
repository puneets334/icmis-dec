<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($details)):?>
                <table  id="FreshMatter" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align: center;">Sr.No.</th>
                        <th style="text-align: left;">Court No#Item No</th>
                        <th>Diary No. <br>Case No. # Registration Date</th>
                        <th>Cause Title</th>
                        <th>Advocates </th>
                        <th> DA</th>
                        <th>IB Ext. DA</th>
                        <th>IB DA</th>
                        <th>Loose Documents</th>
                        <th>Scanned Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sno = 1;
                    foreach($details as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td>
                                <?php echo $row['courtno']?>#<?php echo $row['brd_slno'];?>
                            </td>
                            <td>
                                <?php echo substr($row['diary_no'],0,-4 )."/".substr($row['diary_no'],-4).'<br/>';
                                if(!empty($row['reg_no_display'])) echo $row['reg_no_display']; else '';?><br/><?php if(!empty($row['active_fil_dt']))
                                    echo '#'.date('d-m-Y',strtotime($row['active_fil_dt'])); else '';?>
                            </td>
                            <td><?= $row['pet_name'] ?> <b><br/>Vs.<br/></b> <?= $row['res_name'] ?></td>
                            <td><?php echo '*'.$row['pet_adv'].'<b>[P]</b>'.'<br/>';
                                if($row['res_adv']!='()') echo '*'.$row['res_adv'].'<b>[R]</b>'; else echo ''; ?></td>
                            <td><?php if(!empty($row['da_name'])) echo $row['da_name'].'['.$row['da_empid'].']<br/>'.$row['da_sec']; else echo '';?></td>
                            <td><?= str_replace(']',']<br/>',$row['ib_ext'])?></td>
                            <td><?php if(($row['ib_da'])!='[]') echo $row['ib_da']; else '' ;?></td>
                            <td><?php if(!empty($row['doc_details']) && $row['doc_details']!='/-') echo '*'.str_replace(',','<br/>*',$row['doc_details']); else echo ''; ?></td>
                            <td><?php if(!empty($row['file_id'])) echo "Scanned"; else echo "Not Scanned";?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>

        </div>
        <script>

            $(function () {
                $("#FreshMatter").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>

