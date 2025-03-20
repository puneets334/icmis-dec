<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Reportsofcaveat)):?>
                <table  id="ReportCaveat" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align: center;">Sr.No.</th>
                        <th width="13%" style="text-align: left;">Caveat No#Caveat Date</th>
                        <th>Lower Court Details</th>
                        <th width="8%">Diary No.</th>
                        <th width="30%">Cause Title</th>
                        <!-- <th width="10%">Caveator Advocate </th> -->
                        <th> DAYS </th>
                        <th width="10%">Caveator Advocate</th>
                        <th width="10%">Petitioner Advocate</th>
                        <th>Court Fee#Total Court Fee</th>
                        <th width="7%">Diary User</th>
                        <!--<th width="10%">State/Lower Court Information</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sno = 1;
                    foreach($Reportsofcaveat as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td>
                             <?php echo $row->caveat_no1.'/'.$row->caveat_year ?>#<?php echo date('d-m-Y',strtotime($row->caveat_date));?><?php
                                if($row->no_of_days > 90)
                                {?> <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:red"><?php echo "Expired";?></span> <?php
                                }
                                else
                                { ?>
                                    <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:green"><?php echo "Active";?></span> <?php
                                }
                                ?>
                            </td>
                            <td><?= '<b>'.$row->ref_agency_state_id .'</b><br>('.$row->ref_agency_code_id.')'.'<br/>'.$row->ct_details?></td>
                            <td><?php if(!empty($row->diary_no) ) {
                                $result=explode(',',$row->diary_no);
                            foreach($result as $data)
                                echo substr($data,0,-4 )."/".substr($data,-4);}
                            else echo '';?></td>

                            <td><?= $row->pet_name ?> <b>Vs.</b> <?= $row->res_name ?></td>
                            <td><?= $row->no_of_days?></td>
                            <td><?= $row->pet_adv_id?></td>
                            <td><?= $row->main_adv.'<br/>'. $row->main_a_adv;?></td>
                            <td><?= $row->court_fee ?> # <?= $row->total_court_fee ?></td>
                            <td><?= $row->diary_user_id ?></td>

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
                $("#ReportCaveat").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });


        </script>
