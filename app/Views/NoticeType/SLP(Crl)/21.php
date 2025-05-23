<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 12px">(Criminal Appellate Jurisdiction)</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman'>
       <?php
     $lower_court= lower_court($dairy_no);
    $get_last_listed_date= get_last_listed_date($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
       for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
           ?>
     <div style="font-size: 13pt;margin-bottom: 10px"  face= "Times New Roman" align="justify">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> in Petition for
       <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b> against the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b> 
       of the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in 
       <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>)</div>
       <?php
         
       }
       ?>
   </p>
  
    <?php
    if($row['individual_multiple']==1)
    {
    ?>

        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
                    (Process Id:<?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>)</b>
            </font>
        </p>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >

            <font style="font-size: 13pt"  face= "Times New Roman"  >

                <b>(
                    <?php if($case_range!=''){?>

                    <?php  if($res_fil_det['short_description']!=''){echo $res_fil_det['short_description']; }
                    else echo "Diary No. ";
                    echo $case_range; ?> / <?php echo $reg_year;?>
                </b>
                <?php    }   ?> / <b><?php echo get_section($dairy_no);?> )</b>
            </font>
        </p>

    <?php } ?>
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
               <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center" face= "Times New Roman">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
  <div align="justify" style='margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman'>
 (FOR FULL CAUSE TITLE PLEASE SEE SCHEDULE'A' ANNEXED HEREWITH)
   </div>
    <div align="right" style='margin-top: 10px;padding: 2px 0px 0px 0px;'>
        <u style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></u>
   </div>
   
    <p style="margin: 10px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >CORAM :</font></b></p>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
            <?php
           $last_listed_date= last_listed_date($dairy_no);
         $get_coram=  get_coram($last_listed_date[4]);
         $jname='';
         for ($index2 = 0; $index2 < count($get_coram); $index2++) {
    if($jname=='')
        $jname=$get_coram[$index2];
    else 
          $jname=$jname.'<br/>'.$get_coram[$index2];
}
echo $jname;
            ?></b>
    </p>
    
      <!--<p style="margin-top: 20px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>-->
   <div style="margin-top: 50px">
        <table width='100%' cellpadding='5' cellspacing='5'>
            <tr>
                <td style="font-size: 13pt;"  face= "Times New Roman" width: 30%"> 
                    For the Appellant(s)
                </td>
                <td>
                     :
                </td>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php
                   $tot_petitioner_adv= tot_petitioner_adv($dairy_no);
                   $total_pet='';
                   for ($index3 = 0; $index3 < count($tot_petitioner_adv); $index3++) {
                       if($total_pet=='')
                           $total_pet=$tot_petitioner_adv[$index3][0];
                       else 
                           $total_pet=$total_pet.'<br/>'.$tot_petitioner_adv[$index3][0]; 
                   }
                   echo $total_pet;
                    ?>
                </td>
            </tr>
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    For the Respondent(s)
                </td>
                  <td>
                     :
                </td>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php
                   $tot_petitioner_adv= tot_respondent_adv($dairy_no);
                   $total_res='';
                   for ($index3 = 0; $index3 < count($tot_petitioner_adv); $index3++) {
                       if($total_res=='')
                           $total_res=$tot_petitioner_adv[$index3][0];
                       else 
                           $total_res=$total_res.'<br/>'.$tot_petitioner_adv[$index3][0]; 
                   }
                    echo $total_res;
                    ?>
                </td>
            </tr>
        </table>
    </div>
        
     <p style="color: #000000;text-indent: 40px;padding: 40px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
     
        The Appeal above-mentioned being called on for hearing before this Court on the 
         <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>; UPON perusing the records and hearing counsel for the appearing parties 
        herein; /the Court took time to consider its Judgment and the matter being called on for 
        Judgment on the  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>,/ THIS COURT DOTH ORDER THAT the criminal appeal be 
        and is hereby dismissed.
        </font>
     </p>
   
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
  The Appeal above-mentioned being called on for hearing before this Court on the  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>; 
  UPON perusing the records and hearing counsel for the appearing parties herein; /the Court took time 
  to consider its Judgment and the matter being called on for Judgment on the  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>,/ 
  THIS COURT DOTH inter-alia PASS THE FOLLOWING *ORDER:
             </font>
    </p>
    <?php
    
     if($get_last_listed_date!='')
     {
        $fil_nm_s= get_text_pdf($dairy_no,$get_last_listed_date);
     
     }
     ?>
      <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 20px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
           "<?php
    echo read_txt_file($fil_nm_s);
  ?>"
         </font></b>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
Consequent upon the dismissal of the criminal appeal above mentioned, the order of this 
Court dated _________ granting bail/stay in the matter be and is hereby vacated.
      </font>
    </p>
     <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 AND THIS COURT DOTH FURTHER ORDER that this ORDER be punctually observed and carried into execution 
 by all concerned.
             </font>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 WITNESS the <b style="font-size: 13pt"  face= "Times New Roman" > Hon'ble Shri <?php echo $chief_name=chief_name();
 ?></b>, Chief Justice of India at the Supreme Court, New Delhi, 
 dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>.
             </font>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>
    <p align=" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</font></b></p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Additional/Deputy Registrar</font></b></p>
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
    Copy to :-
   </font></p>
 <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
<?php  if($tot_copy_send_to!='') { ?>  
<div style="margin-left: 30px"><?php
  $c_sno=1;          
  $ex_c_s_t=explode('@',$tot_copy_send_to);
  ?>
             <table>
          <?php
             for ($index = 0; $index < count($ex_c_s_t); $index++) {
                 $ex_explode=explode('!',$ex_c_s_t[$index]);
                 ?>
       
              <tr>
                  <td style="font-size: 13px;vertical-align: top">
                      <?php echo $c_sno; ?>
                  </td>
                    <td >
                        <div style="font-size: 13pt"  face= "Times New Roman" > <?php
                        $ex_exp=  explode('~', $ex_explode[0]);
                        echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));
                        
                        ?></div>
                        <div style="font-size: 13pt"  face= "Times New Roman" > <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
                  </td>
              </tr>
         
        
    
      <?php
      $c_sno++;
             }
    ?>  </table></div>
   <?php } }
   else  if($row['individual_multiple']==2)
   {
       echo $tot_copy;
   }
   ?>
 </font></p>
  <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 *(Please refer to the copy of this Court's Signed Reportable/Non-Reportable 
 Judgment dated <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> already sent by this Registry.)
</font>
    </p>
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
</div>
<div style="page-break-before: always">
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 12px">(Criminal Appellate Jurisdiction)</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
                   VERSUS
                </td>
                <td style="font-size: 13pt;"  face= "Times New Roman"  "text-align: right">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;"  face= "Times New Roman"  "text-align: left">
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;"  face= "Times New Roman"  "text-align: right">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
    <div style="margin-top: 60px">
        <?php
       $lower_court= lower_court($dairy_no);
        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
   
 $get_tentative_date= get_tentative_date($dairy_no);
  $tentative_dt=date('dS F, Y', strtotime($get_tentative_date));
  
  $get_first_listed_date= get_first_listed_date($dairy_no);
  $first_listed_date=date('dS F, Y', strtotime($get_first_listed_date));
           ?>
          <p align="left" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt"  face= "Times New Roman" ><b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $agency_name ?> </b> <b style="font-size: 13pt"  face= "Times New Roman" >, <?php echo $lower_court[$index1][1] ?></b></u>
         
    </p>
     <p align="left" style='margin: 0px;padding: 10px 0px 0px 0px;' >

   (<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $skey ?> </b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> OF <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>)
         
    </p>
    
      <p align="left" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt"  face= "Times New Roman" ><b style="font-size: 13pt"  face= "Times New Roman" >ORDER ALLOWING THE APPEAL</b></u>
         
    </p>
      <p align="left" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt"  face= "Times New Roman" ><b style="font-size: 13pt"  face= "Times New Roman" >DATED THIS THE  <?php echo  $listed_dt; ?></b></u>
         
    </p>
   
    <?php
         }
       ?>
       
    </div>
    <div style="margin-top: 190px;margin-left: 390px">
      <?php
   if($row['individual_multiple']==1)
   {
   ?>
    
   
  <p style="color: #000000;margin: 10px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
            <?php
              echo $tw_sn_to; ?></b>, 
    </p>
    <?php if($address_m!='') { ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
           
             
          <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $address_m; ?></b>, 
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;float: left">
        <b> <font  style="font-size: 13pt"  face= "Times New Roman" >
           
             
         District- <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
    </p>
   <?php }
   else if($row['individual_multiple']==2)
   {
       echo $tot_records;
   }
   ?>
    </div>
</div>

