<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($dataPartHeard)):?>
                <?php
                    $report_type_head = '';
                    $mh = '';
                    if($report_type == 'S'){
                        $report_type_head = 'Special Bench';
                    }
                    elseif($report_type == 'P'){
                        $report_type_head = 'Part Heard';
                    }
                    else{

                    }

                    if($mr == 'F'){
                        $mh = 'Regular';
                    }
                    elseif($mr == 'M'){
                        $mh = 'Miscellaneous';
                    }
                ?>
                <h3>
                    <center>
                    <?php
                        $Jname = '';
                        if($judge == 0){
                            echo 'List of '.$report_type_head.'_'.$mh.' cases by Hon\'ble Judges as on '.date("d-m-Y h:i:s A");
                        }else{
                            echo 'List of '.$report_type_head.'_'.$mh.' cases by '.$Jname.' as on '.date("d-m-Y h:i:s A");
                        }
                    ?>
                    </center>        
                </h3>
                <table  id="ReportsPartHead" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Diary No</th>
                        <th>Case No</th>
                        <th>Cause Title</th>
                        <th>Coram</th>
                        <th>Next Listing Date</th>
                        <th>Last Listed on</th>
                        <th>Section</th>
                        <th>DA Name</th>
                       </tr>
                     </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($dataPartHeard as $result):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?php echo $result['diary_no'];?></td>
                            <td><?php echo $result['case_no'];?></td>
                            <td><?php echo $result['cause_title'];?></td>
                            <td><?php echo $result['coram'];?></td>
                            <td><?php echo $result['next_listing_dt'];?></td>
                            <td><?php echo $result['last_listed_on'];?></td>
                            <td><?php echo $result['section'];?></td>
                            <td><?php echo $result['da'];?></td>
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
                $("#ReportsPartHead").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
