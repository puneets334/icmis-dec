<div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
   

            <?php if(!empty($Adv_list)): //print_r($Adv_list);exit;?>
                <center><caption><h3>CASES VERIFIED DETAIL REPORT</h3>
        
        <small>Verified Matters of Super User Listed on <?= $ldate ?>  </small></caption></center>
                <table id="ReportVec" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th >SNo</th>
                        <th >Diary/Reg No</th>    
                        <!--<th width="5%" style="font-weight: bold; color: #dce38d;">ROP</th>-->
                        <th >Petitioner / Respondent</th>
                        <th >Advocate</th>
                        <th >Heading/Category</th>
                        <th >LastOrder / Statutory</th>
                        <th >IA</th>
                        <!-- <th>Purpose</th> -->
                        <th >Verification<br/>Report</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $s_no = 1;                    
                    foreach($Adv_list as $row): //print_r($row);exit;
                        $dno = $row['diary_no'];        
                        //$verify_time = "<br><span style='color:red'>Verify Time : ".date('h:i:s', strtotime($row['verified_on']))."</span>";
                        $coram = $row['coram'];
                        $purpose = $row['purpose'];   
                       $lastorder = $row['lastorder'];                  
                        $stagename = $row['stagename'];    
                        //$diary_no_rec_date = "Diary Dt ".date('d-m-Y', strtotime($row['diary_no_rec_date']));                  
                        // /$fil_dt = "Reg Dt ".date('d-m-Y', strtotime($row['fil_dt']));    
                        //$radvname = $row["r_n"];
                        $radvname = "RNAME";
                        //$padvname = $row["p_n"];
                        $padvname = 'PNAME';
                        //$impldname = $row["i_n"];
                        $impldname = "INAME";
                        if ($row['pno'] == 2) {
                            $pet_name = $row['pet_name'] . " AND ANR.";
                        } else if ($row['pno'] > 2) {
                            $pet_name = $row['pet_name'] . " AND ORS.";
                        } else {
                            $pet_name = $row['pet_name'];
                        }
                        if ($row['rno'] == 2) {
                            $res_name = $row['res_name'] . " AND ANR.";
                        } else if ($row['rno'] > 2) {
                            $res_name = $row['res_name'] . " AND ORS.";
                        } else {
                            $res_name = $row['res_name'];
                        }
                    ?>
                    <tr>
                    <td><?php echo $s_no;?></td>
                    

                <td> 
                <br><strong><?php echo $row['diary_no']; ?></strong>
                </td> 
                <td ><?php 
                echo $row['section_name'] .' '. $row['name'];
                //echo "<span class='tooltip'>".$cat_code."<span class='tooltiptext'>Tooltip text</span></span>";
                /*if($coram != 0 and $coram != ''){
                    echo "<br/>CORAM: <span style='color:green'>".f_get_judge_names_inshort($coram)."</span>";
                }
                echo $ro_earlier_verify_record;
                echo $verify_time;*/
                ?></td> 

                <td ><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                <td ><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,",")); ?></td>
                <td ><?php echo $row['stagename']; ?></td>
                <!-- <td ><?php //echo "<i>".$lastorder."</i><br>".get_cl_brd_remark($dno); ?></td> -->
<!--                <td ><?php  ?></td>-->
                <!-- <td ><?php //f_get_docdetail($dno);  ?>                 -->
                </td>

            <td ><?php echo $purpose;  ?>
            </td>
            <td>
                <?=$row['remarks_by_monitoring']?>
                <br/>
                <span style="font-weight:bold; color: brown;">Verified By: <?=$row["verified_by"]?></span>
                <!-- <span style="font-weight:bold; color: brown;">Verified On: <?=$row["verified_on"]?></span> -->
            </td>

                       
                    </tr>
                    
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
        <script>

            
$(function () {
$("#ReportVec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>