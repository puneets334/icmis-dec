
<div id="prnnt" style="text-align: center; font-size:10px;">
<h3><?php echo $h3_head; ?></h3>
<?php 
    if(count($spread_data)>0){        
    ?>
    <table align="left" width="100%" border="0px;" style="font-size:10px; table-layout: fixed;">
        
<tr style="background: #918788;">
    <td width="5%" style="font-weight: bold; color: #dce38d;">SrNo.</td>        
    <td width="15%" style="font-weight: bold; color: #dce38d;">Reg No. / Diary No</td>
    <!--<td width="10%" style="font-weight: bold; color: #dce38d;">Tentative Date</td>-->
    <td width="20%" style="font-weight: bold; color: #dce38d;">Petitioner / Respondent</td>
    <td width="20%" style="font-weight: bold; color: #dce38d;">Advocate</td>
    <td width="10%" style="font-weight: bold; color: #dce38d;">Subhead</td>
    <td width="10%" style="font-weight: bold; color: #dce38d;">Purpose</td>
    <td width="15%" style="font-weight: bold; color: #dce38d;">Category</td>

    <td width="10%" style="font-weight: bold; color: #dce38d;">Section</td>

</tr>
    <?php
      $sno = 1;
     
        foreach($spread_data as $ro){
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
                     $padvname = ""; $radvname = "";
                          
                    $resultsadv = vac_reg_week_fun8($ro["diary_no"]);
                    if(count($resultsadv) > 0){
                        $rowadv = $resultsadv[0];
                        $radvname=  $rowadv["r_n"];
                        $padvname=  $rowadv["p_n"];
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

$section_ten_rs = cl_print_func2($casetype_displ, $ten_reg_yr, $ref_agency_state_id);
if(count($section_ten_rs)>0){
    $section_ten_row = $section_ten_rs[0];
    $ro['section_name']=$section_ten_row["section_name"];
}
}
            ?>  <td align="left" style='vertical-align: top;'><?php echo $sno; ?></td>                                                       
                <td align="left" style='vertical-align: top;'><?php echo $fil_no_print."<br>Diary No. ".substr_replace($ro['diary_no'], '-', -4, 0); ?></td>
                <!--<td align="left" style='vertical-align: top;'><?php /*echo date('d-m-Y', strtotime($ro['tentative_cl_dt']));  */?></td>-->

                <td align="left" style='vertical-align: top;'><?php echo $pet_name."<br/>Vs<br/>".$res_name; ?></td>
                <td align="left" style='vertical-align: top;'><?php echo str_replace(",",", ",trim($padvname,","))."<br/>Vs<br/>".str_replace(",",", ",trim($radvname,",")); ?></td>
            <td align="left" style='vertical-align: top;'><?php echo $ro['stagename']; ?></td>
            <td align="left" style='vertical-align: top;'><?php echo $ro['purpose']; ?></td>
            <td align="left" style='vertical-align: top;'><?php f_get_cat_diary_basis($ro['submaster_id']); ?></td>

            <td align="left" style='vertical-align: top;'><?php echo $ro['section_name']."<br/>".$ro['name']; ?></td>
                
                </tr>
                <?php             
            $sno++;
        }
        ?>
    </table>
                <?php
    }
    else{
        echo "No Recrods Found";
    }
    ?>
<BR/><BR/><BR/><BR/> <BR/><BR/><BR/><BR/>      
</div>

<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">   
<span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">    
</span>
<input name="prnnt1" type="button" id="prnnt1" value="Print" >
</div>       
