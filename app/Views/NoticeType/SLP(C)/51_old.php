<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
<!--    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 14.5px" border="1" >
       All Communications Should be Addressed to Registrar by Designation and not by Name.
     </div>-->
    <div style="width: 40%;float: right;font-size: 14.5px;text-align: center">
        D. No. <b style="font-size: 14.5px"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b style="font-size: 14.5px"><?php echo get_section($dairy_no); ?></b>
        <div style="font-size: 14.5px;">
            SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 14.5px;">
            NEW DELHI
        </div>
        <div style="font-size: 14.5px;">
           <?php 
          echo date('dS F, Y')
           ?>
        </div>
     </div>
    
   
    
    
   
    
   <p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 14.5px">Process Id: <?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>(<?php echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> 

      <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 14.5px" >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 14.5px" >
         The Assistant Registrar,</b>
    </p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 14.5px" >
        Supreme Court of India, New Delhi.</b>
    </p>
   
   <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 14.5px" >To,</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
<!--        <b> <font style="font-size: 14.5px" >
         The Registrar,</b>
    </p>-->
      <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 14.5px" >
            <?php
              echo $tw_sn_to; ?></b>, 
    </p>
    <?php if($address_m!='') { ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%" >
        <b> <font style="font-size: 14.5px" >
           
             
          <b style="font-size: 14.5px"><?php echo $address_m; ?></b>, 
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left">
        <b> <font  style="font-size: 14.5px">
           
             
         District- <b style="font-size: 14.5px"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
    </p>
   
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 14.5px" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 14.5px'>
       <?php
     $lower_court= lower_court($dairy_no);
 $get_last_listed_date=  get_notice_dt($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
//       for ($index1 = 0; $index1 < count($lower_court); $index1++) {
// $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
// $agency_name=$lower_court[$index1][2];
//  $skey=$lower_court[$index1][3];
// $lct_caseno=$lower_court[$index1][4];
//  $lct_caseyear=$lower_court[$index1][5];
           ?>
<!--     <div style="font-size: 14.5px;margin-bottom: 10px">
          (Appeal by Special Leave granted by this Court's Order dated the <b style="font-size: 14.5px"><?php echo $listed_dt; ?></b> in Petition for 
       <b style="font-size: 14.5px"><?php echo $get_misc_re[0] ?></b> <b style="font-size: 14.5px"><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 14.5px"><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the <b style="font-size: 14.5px"><?php echo $judgement_dt; ?></b> 
       of the <b style="font-size: 14.5px"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in 
       <b style="font-size: 14.5px"> <?php echo $skey ?> </b> No. <b style="font-size: 14.5px"><?php echo $lct_caseno; ?></b> of <b style="font-size: 14.5px"><?php echo $lct_caseyear; ?></b>)</div>-->
       <?php
         
//       }
       ?>
   </p>
   
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 14.5px">
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 14.5px;text-align: center">
                   VERSUS
                </td>
                <td style="font-size: 14.5px;text-align: right">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 14.5px;text-align: left">
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 14.5px;text-align: right">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 14.5px" >Sir,</font></b></p>  
    <?php
      $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
        ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 14.5px">
        The matter above mentioned was listed before the Hon'ble Court on 
        <b style="font-size: 14.5px"><?php echo $listed_dt; ?></b> when the Court directed issue of notice. 
    
</font>
     </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 14.5px">
 You are, therefore, requested to file affidavit of valuation and Court fee accordingly as per the 
 Supreme Court Rules, 2013 within 7 days failing which the matter will be reported to the Hon'ble Court. 
             </font>
    </p>
     
    
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 14.5px" >Yours faithfully,</font></b></p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 14.5px" >ASSISTANT REGISTRAR</font></b></p>
   
</div>

