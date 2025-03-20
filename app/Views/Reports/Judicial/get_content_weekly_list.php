<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Weekly_list)):?>
                <table id="ReportWeekly" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th>SrNo.</th>
                    <th>Court No.</th>
                    <th>Main/Conn.</th>
                    <th>Diary No</th>
                    <th>Reg No.</th>
                    <th>Petitioner / Respondent</th>
                    <th>Advocate</th>
                    <th>Section Name</th>
                    <th>Assistant Name</th>
                    <th>Statutory Info.</th>
                    <!-- <th>Listed Before</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [ent_dt] => 2014-09-09 10:28:09+05:30 [dno] => XIV-A [courtno] => 2 [name] => BHARATI SHARMA 
                    //[section_name] => [purpose] => Next Week / Week Commencing / C.O.Week [short_description] => C.A. No.
                    // [fyr] => 2015 [active_reg_year] => 2015 [active_fil_dt] => 2015-11-02 00:00:00+05:30 
                    //[conn_key] => 331572013 [active_fil_no] => 03-013333-013333 [pet_name] => M/S. HARVEL AGUA INDIA PRIVATE LIMITED 
                    //[res_name] => THE STATE OF HIMACHAL PRADESH [pno] => 1 [rno] => 4 [casetype_id] => 3 [ref_agency_state_id] => 571779 
                    //[diary_no_rec_date] => 2013-10-17 00:00:00+05:30 [remark] => [diary_no] => 331572013 [next_dt] => 2022-11-02 
                    //[subhead] => 82 [judges] => 219,281 [coram] => 219,273,288 [brd_slno] => 46 [clno] => 1 [listorder] => 7 
                    //[reg_no_display] => C.A. No. 13333/2015 )
                    foreach($Weekly_list as $ro): //print_r($ro); exit; 
                    
                    $remark=$ro['remark'];
                    $sno1 = $sno % 2;
                    $dno = $ro['diary_no'];
                    $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
                    // $active_fil_dt = date('d-m-Y', strtotime($ro['active_fil_dt']));
                    $active_fil_dt = $ro['active_fil_dt'];
                    $conn_no = $ro['conn_key'];
                    $m_c = "";
                    
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    $coram = $ro['coram'];
                 
                    // if($ro['board_type'] == "J"){
                    //     $board_type1 = "Court";
                    // }
                    // if($ro['board_type'] == "C"){
                    //     $board_type1 = "Chamber";
                    // }
                    // if($ro['board_type'] == "R"){
                    //     $board_type1 = "Registrar";
                    // }
                    $filno_array = explode("-",$ro['active_fil_no']);

                    if(empty($ro['reg_no_display'])){
                        $fil_no_print = "Unregistred";
                    }
                    else{
/*                        $fil_no_print = $ro['short_description']."/".ltrim($filno_array[1], '0');
                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                        $fil_no_print .= "/".$ro['active_reg_year'];*/
                        $fil_no_print = $ro['reg_no_display'];

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
                    $padvname = ""; $radvname = ""; $impldname= "";
                    ?>
                    <tr>
                        <td><?php echo $sno;$sno++; ?></td>
                        <td><?php echo $ro['courtno']; ?></td>
                        <td><?php echo $m_c; ?></td>
                        <td><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td>
                        <td><?php echo $fil_no_print."<br>Rdt ".$active_fil_dt; ?></td>
                        <td><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                        <td><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,","))." ",str_replace(",",", ",trim($impldname,",")); ?></td>
                        <td><?php echo $ro['section_name']; ?></td>
                        <td><?php echo $ro['name']; ?></td>
                        <td><?php echo $remark?></td>
                        <!-- <td><?php //echo $board_type1?></td> -->
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
$("#ReportWeekly").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>
