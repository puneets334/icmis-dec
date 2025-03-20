<div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <?php if(!empty($Section_list)):?>
                <table  id="query_builder_report" class="query_builder_report table table-bordered table-striped">
      
                    <thead>
                    <tr>
                    <th  style="">SrNo.</th>
                    <th  style="">Court No.</th>
                    <th  style="">Item No.</th>
                    <th  style="">Diary No</th>
                    <th  style="">Reg No.</th>
                    <th  style="">Petitioner / Respondent</th>
                    <!-- <th  style="">Advocate</th> -->
                    <th  style="">Section Name</th>
                    <th  style="">DA Name</th>
                    <th  style="">Statutory Info.</th>
                    <!-- <th  style="">Listed Before</th> -->
                    <th  style="">Published On</th>
                    <!-- <th  style="">Purpose</th> -->
                    <th  style="">Trap</th>
                    <!-- <th  style="">Office<br> Report</th> -->
                        <!--<th width="10%">State/Lower Court Information</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <?php
                    $sno = 1;
                    $today=date('Y-m-d');
                    $case_type= array(39,9,10,19,20,25,26);
                    //$row->diary_no = $row->diary_no ? $row->diary_no : '';
                    foreach($Section_list as $row): //print_r($row); exit;
                    $row->diary_no = !empty($row->diary_no) ? $row->diary_no : '';
                    if(strtotime($row->diary_no_rec_date)>=strtotime('2017-05-08') ){
                    if($row->diary_no == 0 and !in_array($row->casetype_id, $case_type) and $row->board_type !='R' and $row->board_type!='C')
                    {
                        continue;
                    } 
                    else if ($row->diary_no == 1 and !in_array($row->casetype_id, $case_type) and $row->board_type!='R' and $row->board_type!='C') 
                    {

                            if (strtotime(last_listed_date($row->diary_no)) >= strtotime($today)) {

                                continue;
                            }
                        }
                    }
                    
                    $remark=$row->remark;
                    $sno1 = $sno % 2;
                    $dno = $row->diary_no;
                    //$diary_no_rec_date = date('d-m-Y', strtotime($row->diary_no_rec_date));
                    //$active_fil_dt = date('d-m-Y', strtotime($row->active_fil_dt));
                    $active_fil_dt = date('d-m-Y');// temp
                    $conn_no = !empty($row->conn_key) ? $row->conn_key : '';
                    
                    $m_c = "";
                   
                    if($conn_no == $dno){
                        $m_c = "Main";
                    }
                    if($conn_no != $dno AND $conn_no > 0){
                        $m_c = "Conn.";
                    }
                    
                    $coram = !empty($row->coram) ? $row->coram : '';

                    $row->board_type = !empty($row->board_type) ? $row->board_type : '';
                    $board_type1 ='';
                    if($row->board_type == "J"){
                        $board_type1 = "Court";
                    }
                    
                    if($row->board_type == "C"){
                        $board_type1 = "Chamber";
                    }
                    if($row->board_type == "R"){
                        $board_type1 = "Registrar";
                    }
                    
                    $filno_array = explode("-",$row->active_fil_no);

                    if(empty($row->reg_no_display)){
                        $fil_no_print = "Unregistred";
                    }
                    else{
                        /*$fil_no_print = $row->short_description."/".ltrim($filno_array[1], '0');
                        if(!empty($filno_array[2]) and $filno_array[1] != $filno_array[2])
                            $fil_no_print .= "-".ltrim($filno_array[2], '0');
                        $fil_no_print .= "/".$row->active_reg_year;*/
                        $fil_no_print = $row->reg_no_display;

                    }
                                   
                    if($row->pno == 2){
                        $pet_name = $row->pet_name." AND ANR.";
                    }
                    else if($row->pno > 2){
                        $pet_name = $row->pet_name." AND ORS.";
                    }
                    else{
                        $pet_name = $row->pet_name;
                    }


                   
                    if($row->rno == 2){
                        $res_name = $row->res_name." AND ANR.";
                    }
                    else if($row->rno > 2){
                        $res_name = $row->res_name." AND ORS.";
                    }
                    else{
                        $res_name = $row->res_name;
                    }
                    $padvname = ""; $radvname = ""; $impldname= ""; 

                  


                    if($row->pno == 2){
                        $pet_name = $row->pet_name." AND ANR.";
                    }
                    else if($row->pno > 2){
                        $pet_name = $row->pet_name." AND ORS.";
                    }
                    else{
                        $pet_name = $row->pet_name;
                    }



                    if($row->rno == 2){
                        $res_name = $row->res_name." AND ANR.";
                    }
                    else if($row->rno > 2){
                        $res_name = $row->res_name." AND ORS.";
                    }
                    else{
                        $res_name = $row->res_name;
                    }
                    $padvname = ""; $radvname = ""; $impldname= "";


                    // if(($row->section_name == null OR $row->section_name == '') AND $row->ref_agency_state_id != '' and $row->ref_agency_state_id != 0 )
                    // {

                    //     if($row->active_reg_year!=0)
                    //         $ten_reg_yr = $row->active_reg_year;
                    //     else
                    //         $ten_reg_yr = date('Y',strtotime($row->diary_no_rec_date));

                    //     if($row->active_casetype_id!=0)
                    //         $casetype_displ = $row->active_casetype_id;
                    //     else if($row->casetype_id!=0)
                    //         $casetype_displ = $row->casetype_id;

                    //  } ?>
                    
                    <td ><?php echo $sno; ?></td>
                    <td ><?php 
                            if($row->courtno == 31)
                                echo 'VC 1';
                            else if($row->courtno == 32)
                                echo 'VC 2';
                            else if($row->courtno == 33)
                                echo 'VC 3';
                            else if($row->courtno == 34)
                                echo 'VC 4';
                            else if($row->courtno == 35)
                                echo 'VC 5';
                            else if($row->courtno == 36)
                                echo 'VC 6';
                            else if($row->courtno == 37)
                                echo 'VC 7';
                            else if($row->courtno == 38)
                                echo 'VC 8';
                            else if($row->courtno == 39)
                                echo 'VC 9';
                            else if($row->courtno == 40)
                                echo 'VC 10';
                            else if($row->courtno == 41)
                                echo 'VC 11';
                            else if($row->courtno == 42)
                                echo 'VC 12';
                            else if($row->courtno == 43)
                                echo 'VC 13';
                            else if($row->courtno == 44)
                                echo 'VC 14';
                            else if($row->courtno == 45)
                                echo 'VC 15';
                            else if($row->courtno == 46)
                                echo 'VC 16';
                            else if($row->courtno == 47)
                                echo 'VC 17';                            
                            else if($row->courtno == 21)
                                echo 'R 1';
                            else if($row->courtno == 22)
                                echo 'R 2';
                            else if($row->courtno == 61)
                                echo 'R VC 1';
                            else if($row->courtno == 62)
                                echo 'R VC 2';                            
                            else
                                echo $row->courtno;
                        //                $q_c = "SELECT courtno from roster where id = '".$row->roster_id."'";
                        //                    $qc_rds = mysql_query($q_c) or die(mysql_error());
                        //                    $ros_cc = mysql_fetch_array($qc_rds);
                        //                echo $ros_cc['courtno;
                        ?></td>
                    <td ><?php echo $row->brd_slno."<br>".$m_c; ?></td>
                    <td ><?php echo substr_replace($row->diary_no, '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                    <td ><?php echo $fil_no_print."<br>Rdt ".$active_fil_dt; ?></td>
                    <td ><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                    <!-- <td ><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,","))." ",str_replace(",",", ",trim($impldname,",")); ?></td> -->
                    <td ><?php echo $row->section_name; ?></td>
                    <td ><?php echo $row->name; ?></td>
                    <td ><?php echo $remark?></td>
                    <!-- <td ><?php echo $board_type1?></td> -->
                    <td ><?php
                        if($row->ent_time){
                            echo date('d-m-Y H:i:s', strtotime($row->ent_time));
                        } else{ echo "<span style='color:red;'>Not Published</span>"; } ?></td>
                    <td ><?php echo $row->purpose?></td>
                    <!-- <td ></td> -->

                    

                    </tr>
                    
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
      
  <script src="<?php echo base_url('js/data_table_script.js'); ?>"></script>

