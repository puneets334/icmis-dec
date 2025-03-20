
<style>
    table tr th {
    background: #918788;
}
</style>
<div id="prnnt" style="text-align: center; font-size:10px;">
    <H3>Fresh Cases Cause List for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)<br><?php echo $main_supl_head; ?> </H3>
   
    <?php   if(count($listing_dates)>0){        
    ?>
    <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">
        
<tr style="background: #918788;">
    <th width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</th>
    <th width="5%" style="font-weight: bold; color: #dce38d;">Court No.</th>        
    <th width="5%" style="font-weight: bold; color: #dce38d;">Item No.</th>  
    <th width="10%" style="font-weight: bold; color: #dce38d;">Diary No</th> 
    <th width="15%" style="font-weight: bold; color: #dce38d;">Reg No.</th>
    <th width="15%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</th>
    <th width="15%" style="font-weight: bold; color: #dce38d;">Advocate</th>
    <th width="10%" style="font-weight: bold; color: #dce38d;">DA</th>
    <th width="10%" style="font-weight: bold; color: #dce38d;">IB Ext Recv</th>
    <th width="5%" style="font-weight: bold; color: #dce38d;">IB DA</th>
    <th width="5%" style="font-weight: bold; color: #dce38d;">Loose Doc</th>
    <th width="5%" style="font-weight: bold; color: #dce38d;">Scan Status</th>
</tr>
    <?php
      $sno = 1;
     
        foreach($listing_dates as $ro){
            $sno1 = $sno % 2;
            $dno = $ro['diary_no'];
            $diary_no_rec_date = date('d-m-Y', strtotime($ro['diary_no_rec_date']));
            
            if($ro['active_fil_dt'] !== null)

                $active_fil_dt = "Rdt ".date('d-m-Y', strtotime($ro['active_fil_dt']));
            else
                $active_fil_dt = "";

            $conn_no = $ro['conn_key'];
            $m_c = "";
            if($conn_no == $dno){
                $m_c = "Main";
            }
            if($conn_no != $dno AND $conn_no > 0){
                $m_c = "Conn.";
            }            
            $coram = $ro['coram'];
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
            
            if(empty($ro['reg_no_display'])){

                $fil_no_print = "Unregistred";
            }            
            else{

                $fil_no_print = $ro['reg_no_display'];

                
            }          
            if($sno1 == '1'){ ?> 
            <tr style=" background: #ececec;" id="<?php echo $dno; ?>">        
            <?php } else { ?>
            <tr style=" background: #f6e0f3;" id="<?php echo $dno; ?>">
            <?php        
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
                          
                    $resultsadv = cl_print_func1($ro["diary_no"]);
                    if(isset($resultsadv)) {
                        $rowadv = $resultsadv[0];
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
                        $impldname = $rowadv["i_n"];
                    }
                    
                    
 if(($ro['section_name'] == null OR $ro['section_name'] == '') AND $ro['ref_agency_state_id'] != '' and $ro['ref_agency_state_id'] != 0 ){                                      
                    if($ro['active_reg_year']!=0)
    $ten_reg_yr = $ro['active_reg_year'];
else
    $ten_reg_yr = date('Y',strtotime($ro['diary_no_rec_date']));

if($ro['active_casetype_id']!=0)
    $casetype_displ = $ro['active_casetype_id'];
else if($ro['casetype_id']!=0)
    $casetype_displ = $ro['casetype_id'];
$section_ten_row = cl_print_func2($casetype_displ,$ten_reg_yr,$ro["ref_agency_state_id"]);
if(count($section_ten_row)>0){
    
    $ro['section_name']=$section_ten_row["section_name"];
}
}


            ?>  
                <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>
                <td align="left" style='vertical-align: top;'><?php if($ro['is_printed']){ echo $ro['courtno']; } else { echo "?"; }  ?></td>
                <td align="left" style='vertical-align: top;'><?php if($ro['is_printed']){ echo $ro['brd_slno']; } else { echo "?"; } echo "<br>".$m_c; ?></td>    
                <td align="left" style='vertical-align: top;'><?php echo substr_replace($ro['diary_no'], '/', -4, 0); ?></td> <!-- ."<br>Ddt ".$diary_no_rec_date //-->
                <td align="left" style='vertical-align: top;'><?php echo $fil_no_print."<br>".$active_fil_dt; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,","))." ",str_replace(",",", ",trim($impldname,",")); ?></td>
                <td align="left" style='vertical-align: top;'><?php echo $ro['daname'].'<br>'.$ro['section_name']; ?></td>

            <td align="left" style='vertical-align: top;'><?php
               
                $IBExtName = "";
                $resultdocdata = cl_print_func3($ro["diary_no"]);
                if(isset($resultdocdata)) {

                    foreach($resultdocdata as $rowdoc){
                        $IBExtName .= $rowdoc['name'].' ['.$rowdoc['empid']."]<br>".date('d-m-Y H:i:s', strtotime($rowdoc['rece_dt']));
                    }

                }
                if(empty($IBExtName))
                {
                       echo  "<i><u>NOT RECEIVED<i/></u>" ;
                }
                else {
                    echo $IBExtName;
                }



                ?></td>

                <td>
                <?php
               $rs_dtoda = cl_print_func4($ro["diary_no"]);
               $name = '';
               if(count($rs_dtoda) > 0){
                foreach($rs_dtoda as $row_dtoda)
                {
                    $name=$row_dtoda['name'];
                }
               } 
                
                echo $name;
                ?>
             </td>
                
            <td align="left" style='vertical-align: top;'><?php
               
                $kntgrp = "";
                $resultdoc = cl_print_func5($ro["diary_no"]);
                if(count($resultdoc) > 0) {
                    foreach($resultdoc as $rowdoc){
                        $kntgrp .= $rowdoc['kntgrp']."<br>";
                    }
                }
                echo $kntgrp; ?></td>

            <td align="left" style='vertical-align: top;'>
                <?php

                    if($ro["file_id"]){
                        echo "Scanned";
                    }
                    else{
                        echo "Not Scanned";
                    }
                ?>
            </td>

                </tr>
                <?php             
            $sno++;
        }
        ?>
        
    </table>
                <?php
    }
    else{
        echo "No Records Found";
    }
    ?>

</div>

<div style="width: 100%; padding-bottom:1px; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">   
<span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">  
</span>
<input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div>       

