<style> .flex-wrap{     margin-right: 53%;}
        .dataTables_info{     margin-right: 70%;}
                                    </style>
                                    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
    <center><div class="cl_center" style="font-size: 17px;">SUPREME COURT OF INDIA</div>
                                  
                                  <div class="cl_center" style="font-size: 17px;margin-top: 5px">SECTION I-B</div>
                                   <div class="cl_center" style="margin-top: 5px;text-align: center">
                                       CASE SCRUTINY:LIST OF DEFECTS
                                     
                                   </div>
                                  <!-- <div class="cl_center" style="float: right;margin: 0px 35px 0px;">Total Defects 4</div> -->
                                  <div class="cl_center" style="margin-top: 5px;clear: both;font-size: 17px;font: bolder">Diary No. <b style="font-size: 17px"><?= $dno .'-'. $dyr ?></b></div>

</center>
<?php if(!empty($dreports)):?>
                                <div class="cl_center" style="margin-top: 10px">
                                                          <div style="text-align: center;">
                                        <table width="100%">
                                            <tbody><tr>
                                                <td style="text-align: left">
                                                   <div style="margin: 0px 23px 0px;">
                                               <b><?php echo $prname[0]->pet_name; if($prname[0]->pno>1) { echo " and ors.";}?></b>     
                                
                                                   Vs
                                 
                                                <b><?php echo $prname[0]->res_name; if($prname[0]->rno>1) { echo " and ors.";}?></b>   
                                                </div>     
                                                </td>
                                                <td style="text-align: right">
                                                   <div style="text-align: right;margin: 0px 35px 0px;">
                                      Advocate  <?php echo $adv[0]->name;?>                      </div>
                                                </td>
                                            </tr>
                                        </tbody></table>


                                        
           
                <table id="ReportVec" class="query_builder_report table table-bordered table-striped">
                <thead>
                                        <!-- <h3 style="text-align: center;">User wise Defect Report on <?php //echo date("d-m-Y", strtotime($on_date['on_date'])); ?></h3> -->
                                        <tr>
                                            <th >    S.No.
                         </th>
                                            <th>
                                                Defects
                                            </th>
                                            <th>
                                                Defects notified on
                                            </th>
                                            <th>
                                                Defects notified by
                                            </th>
                                            <th>
                                                Defects Removed on
                                            </th>
                                            <th>
                                                Defects Removed by
                                            </th>
                                        </tr>
                        
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [diary_no] => 224272014 [dacode] => 726 [section] => 20 [section_name] => II [short_description] => SLP(Crl) No. [fil_no] => -005984-005992 [fil_dt] => 2014 [pet_name] => JALADI MOSES AND ORS. ETC. [res_name] => POLURU PRASADA REDDY AND ORS. ETC. )
                    $total=0;
                    $total_diary=0;
    
                    foreach($dreports as $row2): //print_r($row2); exit; 
                    //$diary_no = $row['diary_no'];?>
                   <tr>
                          <td>
                           <?php echo $sno++; ?>
                         </td>
                         <td>
                          <?php echo $row2['objdesc']; ?>
                             <?php if($row2['remark']!='') { echo '(<b>'.$row2['remark'].'</b>)'; } ?> 
                             <?php if($row2['mul_ent']!='') { echo '-'.$row2['mul_ent']; } ?>
                         </td>
                        <td>
                          <?php echo date('d-m-Y H:i:s',strtotime($row2['save_dt'])); ?>
                         </td>
                         <td><?php echo $row2['ent_by']; ?></td>
                         <td>
                             <?php
                             if($row2['rm_dt']=='0000-00-00 00:00:00')                                 
							 echo " <b><span style='color: red;' >To be cured</span></b>";
                             else
                                echo date('d-m-Y H:i:s',strtotime($row2['rm_dt'])); ?>
                         </td>
                        <td>
                            <?php echo $row2['rem_by']; ?>
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

                                       