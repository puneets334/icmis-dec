<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(isset($case_result) && sizeof($case_result)>0 && is_array($case_result)){
                $string1 = "";
                if ($param == 'P') {
                    $string1 = "Pending Cases ";
                } elseif ($param == 'D') {
                    $string1 = "Disposed Cases";
                } elseif ($param == 'PD') {
                    $string1 = "Pending Defective Cases";
                } else {
                    $string1 = "All Cases";
                }

                ?>
                <caption>
                    <h4 style="text-align: center;">
                        SCLSC <strong><?=$string1;?> </strong> Report as on <?php echo date('d-m-Y h:m:s A')?>
                    </h4>
                </caption>
                <table  id="ReportCaveat" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th rowspan='1' style="width:10%;">SNo.</th>
                        <th rowspan='1' style="width:40%;">Case No.</th>
                        <th rowspan='1' style="width:25%;">Advocate</th>
                        <th rowspan='1' style="width:25%;">Cause Title</th>
                        <?php if($param=="PD") { ?>
                            <th rowspan='1'>Defect <br>Notification <br> Date</th>
                            <th rowspan='1'>Last <br> List on</th>
                            <th rowspan='1'>No. of <br> Delay Days</th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=0;
                    foreach ($case_result as $result)
                    {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $result['case_no'];?>
                                <?php if($result['user_section']) { ?><br>Section:<strong><?php echo $result['user_section'];?></strong> <?php } ?>
                                <?php if($result['user_section']) { ?><br>Allotted to DA:<strong><?php echo $result['alloted_to_da'];?></strong><?php } ?>
                                <br>Main or Connected:<strong><?php echo $result['main_connected']==='M'?'Main':'Connected';?></strong>
                                <br>Civil or Criminal:<strong><?=(!empty($result['case_grp'] && trim($result['case_grp'])=='C')) ? 'Civil':'Criminal';?></strong>
                                <br>Civil or Criminal:<strong><?=(!empty($result['c_status'] && trim($result['c_status'])=='P')) ? 'Pending':'Disposed';?></strong>
                            </td>
                            <td><?php echo $result['advocate'];?></td>
                            <td><?php echo $result['pet_name'].' <strong>Vs.</strong> '.$result['res_name'];?></td>
                            <?php if(trim($param)=="PD") { ?>
                                <th><?= $result['first_defect_notified_date'];?></th>
                                <th><?= $result['last_listed_on'];?></th>
                                <th><?= $result['noofdelaydays'];?></th>
                            <?php } ?>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                </table>
            <?php }else { ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php } ?>

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
