<div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Sec_list)):?>
                <table id="ReportSec" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <th >SrNo.</th>        
                        <th  >Reg No. / Diary No</th>
                        <!--<td width="10%" >Tentative Date</td>-->
                        <th  >Petitioner / Respondent</th>
                        <th >Advocate</th>
                        <th  >Subhead</th>
                        <th  >Purpose</th>
                        <th >Category</th>

                        <th >Section</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [reg_no_display] => SLP(Crl) No. 5070/2022 [submaster_id] => 339 
                    //[name] => ROHIT GARG [section_name] => II-A [stagename] => [BAIL MATTERS] 
                    //[purpose] => Fixed Date by Court [short_description] => SLP(Crl) No. 
                    //[fyr] => 2022 [active_reg_year] => 2022 [active_fil_dt] => 2022-05-19 12:08:05+05:30 [active_fil_no] => 02-005070 
                    //[pet_name] => ABDUL RAUF DAWOOD MERCHANT [res_name] => THE STATE OF MAHARASHTRA [pno] => 1 [rno] => 1 [casetype_id] => 2 
                    //[ref_agency_state_id] => 358033 [diary_no_rec_date] => 2022-01-03 00:00:00+05:30 [diary_no] => 662022 
                    //[conn_key] => 722022 [next_dt] => 2023-10-10 [mainhead] => M [subhead] => 804 [clno] => 5 [brd_slno] => 5 
                   // [roster_id] => 47543 [judges] => 271,289,299 [coram] => 271,278 [board_type] => J [usercode] => 753 
                    //[ent_dt] => 2023-10-06 17:00:39+05:30 [module_id] => 8 [mainhead_n] => M [subhead_n] => 804 [main_supp_flag] => 1 
                    //[listorder] => 4 [tentative_cl_dt] => 2023-10-10 [listed_ia] => 77828/2022, [sitting_judges] => 2 [list_before_remark] => 0 
                    //[coram_prev] => 0 [is_nmd] => N [no_of_time_deleted] => 0 [create_modify] => [updated_on] => [updated_by] => [updated_by_ip] => )
                    foreach($Sec_list as $ro): //print_r($ro); exit; 
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $conn_no = $ro['conn_key'];            
                    if($ro['board_type'] == "J"){
                        $board_type1 = "Court";
                    }
                    if($ro['board_type'] == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($ro['board_type'] == "R"){
                        $board_type1 = "Registrar";
                    }
                    $filno_array = explode("-",$ro['active_fil_no']);
                    
                    if($ro['reg_no_display']){
                        $fil_no_print = $ro['reg_no_display'];
                    }            
                    else{
                        $fil_no_print = "Unregistred";

                    }
         
           
                    if($ro['pno'] == 2){
                        $pet_name = $ro['pet_name']." AND ANR.";                     
                    }
                    else if($ro['pno'] > 2){
                        $pet_name = $ro['pet_name']." AND ORS.";                     
                    }
                    else{
                        $pet_name = $ro['pet_name'];
                    }
                    if($ro['rno'] == 2){                       
                        $res_name = $ro['res_name']." AND ANR.";     
                    }
                    else if($ro['rno'] > 2){
                        $res_name = $ro['res_name']." AND ORS.";                  
                    }
                    else{
                        $res_name = $ro['res_name'];
                    }
                     $padvname = ""; $radvname = "";
                    ?>
                    <tr>
                        <td><?php echo $sno;$sno++; ?></td>
                        <td><?php echo $fil_no_print."<br>Diary No. ".substr_replace($ro['diary_no'], '-', -4, 0); ?></td>
                         <!--<td align="left" style='vertical-align: top;'><?php /*echo date('d-m-Y', strtotime($ro['tentative_cl_dt']));  */?></td>-->
                        <td><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                        <td ><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,",")); ?></td>
                        <td><?php echo $ro['stagename']; ?></td>
                        <td><?php echo $ro['purpose']; ?></td>
                        <td><?php echo $ro['stagename']; ?></td>
                        <td><?php echo $ro['section_name']."<br/>".$ro['name']; ?></td>
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
$("#ReportSec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>
