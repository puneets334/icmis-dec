

<?php
error_reporting(0);

?>
<style>
p.small {
  line-height: 0.7;
}

p.big {
  line-height: 1.5;
}
</style>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt;"  face= "Times New Roman" padding: 5px" border="1" >
       <?php echo get_text_msg();?>
     </div>
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
        D. No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo get_section($dairy_no); ?></b>
        <div style="font-size: 13pt;"  face= "Times New Roman" >
            SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 13pt;"  face= "Times New Roman" >
            NEW DELHI
        </div>
        <div style="font-size: 13pt;"  face= "Times New Roman" >
           <?php 
          echo date('dS \of F, Y')
           ?>
        </div>
     </div>

<?php if( $res_fil_det['casename']=='CIVIL APPEAL' || $res_fil_det['casename']=='CRIMINAL APPEAL')
{
    $text='Appellant(s)';

}
else
{
    $text='Petitioner(s)';
}
?>
       <?php if( $res_fil_det['casename']=='CIVIL APPEAL' || $res_fil_det['casename']=='CRIMINAL APPEAL')
    {
        $text='Appellant(s)';
        $text1="appeal";

    }
    else if( $res_fil_det['casename']=='SPECIAL LEAVE PETITION (CIVIL)' || $res_fil_det['casename']=='SPECIAL LEAVE PETITION (CRIMINAL)')
    {
        $text='Petitioner(s)';
        $text1="petition for Special Leave to Appeal";

    }
    else if( $res_fil_det['casename']=='TRANSFER PETITION (CIVIL)' || $res_fil_det['casename']=='TRANSFER PETITION (CRIMINAL)' || $res_fil_det['casename']=='WRIT PETITION (CIVIL)' || $res_fil_det['casename']=='WRIT PETITION(CRIMINAL)')
    {
        $text='Petitioner(s)';
        $text1="petition";

    }
    ?>
    
    
   
     <?php
     if($row['individual_multiple']==1)
   {
      ?>
       <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
   <?php } ?>
      <p style="margin: 10px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
       Assistant Registrar</b>
    </p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
        Supreme Court of India, New Delhi.</b>
    </p>
   
   <p style="margin: 10px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
       <b> <font style="font-size: 13pt"  face= "Times New Roman"  >The Registrar,</b> 
   </p>
    <?php
     if($row['individual_multiple']==1)
   {
      ?>
      <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        
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
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
        <b> <font  style="font-size: 13pt"  face= "Times New Roman" >
           
             
         District- <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
    </p>
       <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >

           <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
                   (Process Id:<?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>)</b>
           </font>
       </p>
       <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >

           <font style="font-size: 13pt"  face= "Times New Roman"  >

               <b>
                   <?php if($case_range!=''){?>

                   <?php  if($res_fil_det['short_description']!=''){echo $res_fil_det['short_description']; }
                   else echo "Diary No. ";
                   echo $case_range; ?> / <?php echo $reg_year;?>
               </b>
               <?php    }   ?> / <b><?php echo get_section($dairy_no);?> </b>
           </font>
       </p>

       <?php
   }
   else  if($row['individual_multiple']==2)
   {
       echo $tot_records;
   }
   ?>
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
   
       <?php
         $get_last_listed_date1=    get_last_listed_date1($dairy_no);
     $lower_court= lower_court($dairy_no);
//    $get_last_listed_date= get_last_listed_date($dairy_no);
     $get_last_listed_dates= last_listed_date($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
//     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
//       for ($index1 = 0; $index1 < count($lower_court); $index1++) {
// $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
// $agency_name=$lower_court[$index1][2];
//  $skey=$lower_court[$index1][3];
// $lct_caseno=$lower_court[$index1][4];
//  $lct_caseyear=$lower_court[$index1][5];
//  $lct_case_code=$lower_court[$index1][6];
           ?>
<!--        <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u style="font-size: 13pt"  face= "Times New Roman" >IN</u>
    </p>
     <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt"  face= "Times New Roman" ><b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $skey ?> </b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> OF <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b></u>
         
    </p>-->
       <?php
         
            
//       }
       ?>
    <?php
   for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('d.m.Y', strtotime($lower_court[$index1][0]));
 
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
           ?>
   <div align="center" style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Ref: High Court's/District/Family Court Order dated
       <?php echo $judgement_dt; ?>  in <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ></b> )</div>
      
    <!--  <div align="center" style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Ref: High Court's Order dated
       <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b> <b style="font-size: 13pt"  face= "Times New Roman" >dated <?php echo $judgement_dt; ?></b> )</div>
       --><?php
         
       }
       ?>
   
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center" face= "Times New Roman">
            VERSUS 
                </td>
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   <?php echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                     <?php echo $res_fil_det['res_name'].$rno ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                     Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><font style="font-size: 13pt"  face= "Times New Roman"  >Sir,</font></p>  
  
      <p  class ="big" style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
          $last_listed_dts=date('dS \of F Y', strtotime($get_last_listed_dates[1])); ?>
        
        In continuation of this Registry's letter of even number dated the
        <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'157');
        if($get_notice_dt!='')
       $vac_date= date('dS \of F Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         ?>
<b  class ="big" style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b>, I am directed to transmit herewith for necessary action a certified
copy of the Decree dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $last_listed_dts ?></b> of the Supreme Court in the said
<?php echo $text1; ?>.</font></p>

      <p  class ="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
The Original record, if any, will follow.
             </font>
    </p>
      
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 Please acknowledge receipt.
             </font>
    </p>
      
    
   <p class ="big" align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Yours faithfully,</font></b></p>
   <br><br>
<p  class ="big" align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>

<p  class ="big"style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
    Copy to :-
   </font></p>


<p class ="big" style="page-break-after: always;">&nbsp;</p>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    //echo $mod;
                    ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
    <?php if( $res_fil_det['casename']=='CIVIL APPEAL'|| $res_fil_det['casename']=='CRIMINAL APPEAL')
    {
        $text='Appellant(s)';
        if($res_fil_det['casename']=='CRIMINAL APPEAL')
        {
            $j="Criminal";
        }

       else
       {
           $j='Civil';
       }
    }
    else
    {
        $text='Petitioner(s)';
        $j='Civil';
    }
    ?>
    <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 16px">(<?php echo $r_nature;?> Appellate/Original Jurisdiction)</b></font>

    </p>
    
    <p class="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p class="big" align="justify" style='margin: 0px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman'>
       <?php
     $lower_court= lower_court($dairy_no);
     $get_last_listed_date= get_last_listed_date($dairy_no);
     $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS \of F Y', strtotime($get_last_listed_date));
       for ($index1 = 0; $index1 < count($lower_court); $index1++) {
          $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
          $agency_name=$lower_court[$index1][2];
          $skey=$lower_court[$index1][3];
          $lct_caseno=$lower_court[$index1][4];
          $lct_caseyear=$lower_court[$index1][5];
       }  ?>
        <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px"><center>(Arising out of 
          <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b> ) </center></div>      <?php
        
       
       ?>
  </center> </p>
  
    <?php
    if($row['individual_multiple']==1)
    {
    ?>

        <p class="big" style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
                    (Process Id:<?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>)</b>
            </font>
        </p>
        <p class="big" style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

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
<br>
<?php
$get_total_pet_parties= get_total_pet_parties($dairy_no,'P');

?>
<div align="center" style="width: 100%;clear: both">
    <!--       <table cellpadding="10" cellspacing="10" style="width: 100%" >
                <tr>
                    <td style="font-size: 13pt;"  face= "Times New Roman" >-->
  

    <!--                </td>-->
    <!--               <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%" face= "Times New Roman">
                       VERSUS
                    </td>-->
    <!--
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php //echo// $res_fil_det['res_name'] ?>
                </td>

                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>-->

</div>
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center" face= "Times New Roman">
                    VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   <?php echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                     <?php echo $res_fil_det['res_name'].$rno ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                     Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
  <!-- <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php //echo $res_fil_det['pet_name'] ?>
                </td>
               <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center" face= "Times New Roman">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   ... <?php // echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php // echo// $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>-
            </tr>
        </table>
     
   </div>-->
   <?php
  $get_application_registration_all= get_application_registration_all($dairy_no);
  $tot_application='';
  for ($index4 = 0; $index4 < count($get_application_registration_all); $index4++) {
      if($tot_application=='')
          $tot_application=$get_application_registration_all[$index4][0];
      else 
         $tot_application=$tot_application.', '.$get_application_registration_all[$index4][0]; 
  }
  if($tot_application!='')
  {
  ?>
   <div align="justify" style="margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
 (with application(s) for <?php echo $tot_application; ?>)
   </div>
  <?php } ?>
  <div align="justify" style="margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
 <b>(For Full cause title and details of the Court Appealed from please see Schedule 'A' attached herewith)
 </b></div>
<?php

        $get_notice_dt= get_date_by_remark($dairy_no,'7');
        if($get_notice_dt!='')
       $vac_date= date('d-m-Y', strtotime($get_notice_dt));
        else
           $vac_date='......';

?>

<div align="justify" style="margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
    <b>Date : <?php echo $vac_date; ?>  </b> &nbsp;&nbsp;   This petition was called on for pronouncement of Judgment today.
</div>

    <div align="right" style='margin-top: 10px;padding: 2px 0px 0px 0px;'>
        <u style="font-size: 13pt"  face= "Times New Roman" ><?php // echo date('dS F, Y'); ?></u>
   </div>
   
    <p class="big" style="margin: 10px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >CORAM :</font></b></p>
     <p  class="big" style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width:100%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
            <?php
           $last_listed_date= last_listed_date($dairy_no);
         $get_coram=  get_coram($last_listed_date[4]);
         $jname='';
         for ($index2 = 0; $index2 < count($get_coram); $index2++) {
    if($jname=='')
        $jname=$get_coram[$index2];
    else 
           echo $get_coram[$index2] ."<br>";         
// $jname=$jname.'<br/>'.$get_coram[$index2];
}
echo $jname;
            ?></b>
    </p>
    
      <!--<p style="margin-top: 20px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>-->
   <div style="margin-top: 50px">
       <table width='100%' cellpadding='5' cellspacing='5'>
            <tr>
                <td style="font-size: 13pt;"  face= "Times New Roman" width: 30%"> 
                    For the <?php  echo $text;?>
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
               <?php  $res_adv=get_res_adv_all($dairy_no);
               //var_dump($res_adv);
              foreach ($res_adv as $ad)
              { 
                  ?>
               <?php echo $ad.""; ?>
              <?php
                      
                } ?>
                </td>
            </tr>
        </table>
    </div>
        
     <p   class="big" style="color: #000000;text-indent: 40px;padding: 40px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
        <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'7');
        if($get_notice_dt!='')
       $vac_date= date('dS \of F, Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         $jname_v='';
        if($get_notice_dt!='')
        {
         $get_coram_vac=  get_coram($get_notice_dt);
         
         for ($index22 = 0; $index22 < count($get_coram_vac); $index22++) {
    if($jname_v=='')
        $jname_v=$get_coram[$index22];
    else 
          $jname_v=$jname_v.' and '.$get_coram[$index22];
}

        }
         ?>
        The Petition for  Special leave to appeal <?php if($tot_application!='') { ?> alongwith Interlocutory Application <?php } ?> 
        above-mentioned being called on for hearing before this Court on the 
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b>, UPON
perusing the record and hearing counsel for the parties herein, the
Court granted Leave to Appeal and  took time to consider its Judgment and the matter being called
on for Judgment on the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>, 
, THIS COURT DOTH grant special leave to appeal and for the reasons and
observations recorded in its Judgment DOTH in the resultant appeal
inter-alia PASS the following ORDER :
       
</font>
     </p>
   
    <?php
   
     if($get_last_listed_date!='')
     {
        $fil_nm_s= get_text_pdf($dairy_no,$get_last_listed_date);
     
     }
     ?>
      <p class="big" style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 20px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
           "<?php
    echo read_txt_file($fil_nm_s);
  ?>"
         </font></b>
    </p>
     
      <p class="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'157');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         ?>
             AND THIS COURT DOTH <b>FURTHER</b>/Lastly ORDER that this ORDER be punctually observed and carried into execution by all concerned
      </font>
    </p>
     <p  class="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
<!-- AND THIS COURT DOTH LASTLY ORDER that this ORDER
be punctually observed and carried into execution by all concerned;-->
             </font>
    </p>
      <p class="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 WITNESS the <b style="font-size: 13pt"  face= "Times New Roman" > Hon'ble Shri <?php echo $chief_name=chief_name_order_dt($get_last_listed_date1);
 ?></b>, Chief Justice of India at the Supreme Court, New Delhi, 
 dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>.
             </font>
    </p>
<!--      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>-->
    <br>
    <br>
    <br>
    <p class="big" align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(T.I. RAJPUT)</font></b>
    
   
 <br><b>DEPUTY REGISTRAR</font></b></p>
</div>
<div style="page-break-before: always">
    <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>

    </p>
    <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 16px">(<?php echo $r_nature;?> Appellate/Original Jurisdiction)</b></font>

    </p>

    <p class="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >


    <p class="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
    <?php
     for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
     $lct_caseyear=$lower_court[$index1][5];}
           ?>
     <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px"><center>(Arising out of 
          <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b> ) </center></div>
       <?php
         
       
       ?>
     <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right"  face= "Times New Roman"  >
                   <?php echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left"  face= "Times New Roman"  >
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right"  face= "Times New Roman" >
                   Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
    <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">
             DECREE DISPOSING OF/DISMISING/ALLOWING THE APPEAL. <?php 
         //  $get_dismissal_type= get_dismissal_type($dairy_no); 
          // echo strtoupper( $get_dismissal_type[1]);
            ?></font> </b>
 
    </p>
       <p class="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
       <u><b> <font style="font-size: 16px">
           Dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>
               </font> </b></u>
 
    </p>
    <div style="margin-top: 190px;margin-left: 390px">
     <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p class="big" style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
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
                  <td style="font-size: 13pt;vertical-align: top">
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
             ?>  </table></div> <br><br>
   <?php } }
   else  if($row['individual_multiple']==2)
   {
       echo $tot_copy;
   }
   ?>
    </div>
<div style="margin-top: 50px" align =right>
    <div align='right'>
        <table>
                  <?php $pet_adv=get_pet_adv_all($dairy_no);
              foreach ($pet_adv as $ad)
                {
                  ?>
            <tr><td><?php echo $ad.",";?> <?php
                      
                }
         
                  
            echo "<br>Advocate on record for the ".$text;
              
               
                
         ?>
            </tr>
        </table>
    </div>
    <br>
      
         <div align='right'>
                <table>
        <?php  $res_adv=get_res_adv_all($dairy_no);
               //var_dump($res_adv);
              foreach ($res_adv as $ad)
              { 
                  ?>
                <tr><td><?php echo $ad.","; ?>
              <?php
                      
                }
                           
            echo "<br>Advocate on record for the Respondents.";   
            
                             
         ?>
                     
            </tr>
       
            </table>
    
    </div>
    
</div>
   
</div>

