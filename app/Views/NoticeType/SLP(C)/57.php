
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
       <b> <font style="font-size: 13pt"  face= "Times New Roman"  >THE REGISTRAR,</b> 
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
   <div align="center" style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Ref: High Court's Order dated
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
          $last_listed_dts=date('dS \of  F, Y', strtotime($get_last_listed_dates[1])); ?>
        
        In continuation of this Registry's letter of even number dated the
        <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'157');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
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
 <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p  class ="big" style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt;text-transform: uppercase;"  face= "Times New Roman"  >
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

<br><br>
<p class ="big" align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
 
 <!--<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>-->
</div>

<p class ="big" style="page-break-after: always;">&nbsp;</p>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
   <!-- <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div>-->
                    </br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no);
$section=$_SESSION['dcmis_section'];
$get_mfactive=get_mfactive($dairy_no);
 $_mfactive=$get_mfactive[0];
 $_casetype=$get_mfactive[1];
 $_activecasetype=$get_mfactive[2];
        ?></font> </b>
   </p>-->
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
    <p  class ="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p class ="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 16px"><?php echo strtoupper($r_nature); ?> APPELLATE/ORIGINAL JURISDICTION</b></font>

    </p>
      <p class ="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <?php $get_misc_re= get_misc_re($dairy_no); ?>
        <u><b>
                <font style="font-size: 13pt"  face= "Times New Roman"  id="append_data">
                    <?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?>
                    <?php echo $case_range; ?> OF <?php echo $reg_year; ?>
                   </font></b></u>

    </p>
    <p class ="big" align="justify" style='margin: 0px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
        <?php
        $lower_court= lower_court_conct($dairy_no);
        $get_diary_date= get_last_listed_date($dairy_no);
        $diary_dt=  date('dS F, Y', strtotime($get_diary_date));
        // $get_last_listed_date=  get_notice_dt($dairy_no);
        $get_last_listed_date= get_last_listed_date($dairy_no);
        $get_last_listed_date1=    get_last_listed_date1($dairy_no);
        $get_misc_re= get_misc_re($dairy_no);
        $listed_dt=date('dS F, Y', strtotime($get_last_listed_date1));

        //$listed_dt=  date("l jS \of F Y h:i:s A",strtotime($get_last_listed_date1)) . "<br>";
        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
        $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
        $agency_name=$lower_court[$index1][2];
        $skey=$lower_court[$index1][3];
        $lct_caseno=$lower_court[$index1][4];
        $lct_caseyear=$lower_court[$index1][5];
        }
        ?>
    <div style="font-size: 13pt;margin-bottom: 10px" face= "Times New Roman" align="center">(Arising out of  <b style="font-size: 13pt"  face= "Times New Roman" ></b>  
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b>
        <?php
        $ex_skey=  explode(',',$skey );
        $ex_lct_caseno=explode(',',$lct_caseno );
        $ex_lct_caseyear=explode(',',$lct_caseyear );
        for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
            if($index2>0){ echo ',';}
            ?>
            <b style="font-size: 13pt" face= "Times New Roman"> <?php //echo $ex_skey[$index2] ?> </b>  <b style="font-size: 13pt"><?php //echo $ex_lct_caseno[$index2]; ?></b>  <b style="font-size: 13pt" face= "Times New Roman"><?php //echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
    <?php

    
    ?>
</b></p>
   <!-- <p class ="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <?php $get_misc_re= get_misc_re($dairy_no); ?>
    <u><b>
            <font style="font-size: 13pt"  face= "Times New Roman"  id="append_data">
                <?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?>
                <?php echo $case_range; ?> OF <?php echo $reg_year; ?>
                <?php if($_mfactive=='F')echo ' @ '.$get_misc_re[0].' No. '.$get_misc_re[1].' of '.$get_misc_re[2] ;?>   @ Diary No. <?php  echo substr($dairy_no,1,strlen($dairy_no)-4); ?>  Of <?php echo substr($dairy_no,-4,4) ;?></font></b></u>
         
    </p>-->
     <!--<p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman'>-->
       <?php
//     $lower_court= lower_court($dairy_no);
//    $get_last_listed_date= get_last_listed_date($dairy_no);
//   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date1));
     ?>

     
  <!--</p>-->


    <p class ="big" align="justify" style='margin: 5px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
       <?php
        $lower_court= lower_court_conct($dairy_no);
   $get_diary_date= get_last_listed_date($dairy_no);
 $diary_dt=  date('dS F, Y', strtotime($get_diary_date));
// $get_last_listed_date=  get_notice_dt($dairy_no);
 $get_last_listed_date= get_last_listed_date($dairy_no);
       $get_last_listed_date1=    get_last_listed_date1($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
    $listed_dt=date('dS F, Y', strtotime($get_last_listed_date1));

       //$listed_dt=  date("l jS \of F Y h:i:s A",strtotime($get_last_listed_date1)) . "<br>";
 for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];

           ?>
      <!--   <div style="font-size: 13pt;margin-bottom: 10px" face= "Times New Roman" align="justify">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $diary_dt; ?></b>  in Petition for
             <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b>
             against the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b>
       of the <b style="font-size: 13pt" face= "Times New Roman" ><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
       <?php
       $ex_skey=  explode(',',$skey );
       $ex_lct_caseno=explode(',',$lct_caseno );
       $ex_lct_caseyear=explode(',',$lct_caseyear );
       for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
           if($index2>0){ echo ',';}
       ?>
       <b style="font-size: 13pt" face= "Times New Roman"> <?php echo $ex_skey[$index2] ?> </b> No. <b style="font-size: 13pt"><?php echo $ex_lct_caseno[$index2]; ?></b> of <b style="font-size: 13pt" face= "Times New Roman"><?php echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
       <?php

       }
       ?>
   </p> -->
  
    <?php
    if($row['individual_multiple']==1 || $row['individual_multiple']==2)
    {
    ?>

      <!--  <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
                    (Process Id:<?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>)</b>
            </font>
        </p> 
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <font style="font-size: 13pt"  face= "Times New Roman"  >

                <b>
                    <?php if($case_range!=''){?>

                    <?php  if($res_fil_det['short_description']!=''){echo $res_fil_det['short_description']; }
                    else echo "Diary No. ";
                    echo $case_range; ?> / <?php echo $reg_year;?>
                </b>
                <?php    }   ?> / <b><?php echo get_section($dairy_no);?> </b>
            </font>
        </p>-->
                

    <?php } ?>
                
    <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
            <br>
            <br>
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
   <br>
   <?php
  // echo $get_last_listed_date1;
   
 
   $get_application_registration_all= get_application_registration_all_decree($dairy_no, $get_last_listed_date1);
   $x= substr_count($get_application_registration_all,',') ;
   
   $t=explode(',',$get_application_registration_all);
  echo '(';
   for($i=0;$i<=$x;$i++)
   {
     
       
       
             //  echo $i;
          //     echo $x;
       if($i==$x)
       {
           echo "IA No. ".$t[$i].")<br>";
          
       }
       else
           echo "IA No. ".$t[$i]."<br>";
      
   }
        
  
   ?>

   <div align="justify"><b>
           <br>
       (For full cause title and details of the Court Appealed from please see Schedule 'A' attached herewith )
       </b>
   </div>
    <div align="justify" style="margin-top: 10px;margin-bottom: 20px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
  <b style="font-size: 13pt"  face= "Times New Roman" >

     
      Date :<?php echo date('d-m-Y', strtotime($get_last_listed_date1));//echo $listed_dt;// ?> </b> 
        This <?php if((($_activecasetype=='3'||$_activecasetype=='4')&& sizeof($get_misc_re)>0 )|| ($_activecasetype!='3'&& $_activecasetype!='4')) echo 'petition'; else echo 'appeal'; ?> was called on for hearing today.   </div>
   
   
    <div style="margin: 10px 10px 10px 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >CORAM :</font></div>
     <p  class ="big" style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 100%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
            <?php
           $last_listed_date= last_listed_date($dairy_no);
         $get_coram=  get_coram_decree($last_listed_date[4]); 
          $total_judges=substr_count($get_coram,",") +1;
       $get_coram=str_replace(",","<br>",$get_coram);
       
     echo $get_coram;
            ?></b>
    </p>
    </b>
      <!--<p style="margin-top: 20px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>-->
   <div style="margin-top: 20px">
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
                   echo  ucwords(strtolower($total_pet));
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
               <?php echo ucwords(strtolower($ad)).""."<br>"; ?>
              <?php
                      
                } ?>
                </td>
            </tr>
        </table>
    </div>
        
     <p class ="big" style="color: #000000;text-indent: 40px;padding: 40px 2px 0px 2px;margin: 1px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
     The <?php /*if(sizeof(get_misc_re($dairy_no))>0) */  if((($_activecasetype=='3'||$_activecasetype=='4')&& sizeof($get_misc_re)>0 )|| ($_activecasetype!='3'&&$_activecasetype!='4'))  echo 'petition'; else echo 'appeal'; ?><?php if($tot_application!='') { ?> alongwith Interlocutory Application <?php } ?> above-mentioned being called on for hearing
before this Court on the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS \of F Y', strtotime($get_last_listed_date1));?></b>, UPON perusing
the record and hearing counsel for the appearing parties above-mentioned parties herein, THIS COURT while condoning the delay
DOTH <u>inter-alia</u>  PASS the following ORDER :
       
        </font>
     </p>
   
    <?php
   
     if($get_last_listed_date1!='')
     {
        $fil_nm_s= get_text_pdf($dairy_no,$get_last_listed_date1);
     
     }
     ?>
      <p class ="big" padding: 0px 2px 0px 2px;margin: 10px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
           "<?php
    echo read_txt_file_judgement($fil_nm_s);
  ?>"
         </font></b>
    </p>
      <p class ="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 20px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
      
         ?>
         <?php
        $pre_casetype_id= get_prev_case_type($dairy_no);
        if((($pre_casetype_id[0] ==1) || ($pre_casetype_id[0] ==2) )&& (($pre_casetype_id[1]==3) || ($pre_casetype_id[1]==4)) ) // slp id filed and leave is granted and appeal is filed
        {
            ?>
         <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 20px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'157');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         ?>
AND THIS COURT DOTH FURTHER ORDER that stay granted by the Order of this Court dated the ...  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b> passed in this  <?php  echo  " ".$text1." "; ?>
and is hereby vacated;
      </font>
    </p>
         <?php
        }
        else {
            

        ?>
AND THIS COURT DOTH FURTHER ORDER that the Order 
of this Court dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b> passed in this <?php echo $text1; ?> be
and is hereby vacated;
      </font>
    </p>
  
        <?php   }?>
    
     <p class ="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 20px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 AND THIS COURT DOTH FURTHER/LASTLY ORDER that this ORDER
be punctually observed and carried into execution by all concerned;
             </font>
    </p>
      <p class ="big" style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 20px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 WITNESS the <b style="font-size: 13pt"  face= "Times New Roman" > Hon'ble Shri <?php echo $chief_name=  ucwords(strtolower(chief_name_order_dt($get_last_listed_date1)));
 ?></b>, Chief Justice of India at the Supreme Court, New Delhi, 
 dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS \of F Y', strtotime($get_last_listed_date1)); ?></b>.
             </font>
    </p>
<!--      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>-->
   <!--<p  align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(<?php // echo get_additional_reg($section);?>)</font></b></p>-->
    <br><br><br>
    <p class ="big" align="right" style="padding: 5px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(T.I. RAJPUT)</font></b><br>
 
   
<b><font style="font-size: 13pt"  face= "Times New Roman"  >(DEPUTY REGISTRAR)</font></b></p>
 
        <!-- <p  style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >
           * As referred in the Order/Judgment.
         </font></b></p>
          <p  style="padding: 2px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >
           **
         </font></b></p> -->
</div>
<p class ="big" style="page-break-after: always;">&nbsp;</p>
<div style="page-break-before: always">
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p class ="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 16px"><?php echo strtoupper($r_nature); ?> APPELLATE/ORIGINAL JURISDICTION</b></font>

    </p>

    <p class ="big" align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <?php $get_misc_re= get_misc_re($dairy_no); ?>
        <u><b>
                <font style="font-size: 13pt"  face= "Times New Roman"  id="append_data">
                    <?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?>
                    <?php echo $case_range; ?> OF <?php echo $reg_year; ?>
                   </font></b></u>

    </p>
    <p class ="big" align="justify" style='margin: 0px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
        <?php
        $lower_court= lower_court_conct($dairy_no);
        $get_diary_date= get_last_listed_date($dairy_no);
        $diary_dt=  date('dS F, Y', strtotime($get_diary_date));
        // $get_last_listed_date=  get_notice_dt($dairy_no);
        $get_last_listed_date= get_last_listed_date($dairy_no);
        $get_last_listed_date1=    get_last_listed_date1($dairy_no);
        $get_misc_re= get_misc_re($dairy_no);
        $listed_dt=date('dS F, Y', strtotime($get_last_listed_date1));

        //$listed_dt=  date("l jS \of F Y h:i:s A",strtotime($get_last_listed_date1)) . "<br>";
        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
        $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
        $agency_name=$lower_court[$index1][2];
        $skey=$lower_court[$index1][3];
        $lct_caseno=$lower_court[$index1][4];
        $lct_caseyear=$lower_court[$index1][5];
        }
        ?>
    <div style="font-size: 13pt;margin-bottom: 10px" face= "Times New Roman" align="center">(Arising out of  <b style="font-size: 13pt"  face= "Times New Roman" ></b>  
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b>
        <?php
        $ex_skey=  explode(',',$skey );
        $ex_lct_caseno=explode(',',$lct_caseno );
        $ex_lct_caseyear=explode(',',$lct_caseyear );
        for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
            if($index2>0){ echo ',';}
            ?>
            <b style="font-size: 13pt" face= "Times New Roman"> <?php //echo $ex_skey[$index2] ?> </b>  <b style="font-size: 13pt"><?php //echo $ex_lct_caseno[$index2]; ?></b>  <b style="font-size: 13pt" face= "Times New Roman"><?php //echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
    <?php

    
    ?>
    </p>
     <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'] ?>
                </td>
            <br>
            <br>
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
    <p class ="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">
            DECREE DISPOSING OF/DISMISING/ALLOWING THE APPEAL. <?php 
           $get_dismissal_type= get_dismissal_type($dairy_no); 
          // echo strtoupper( $get_dismissal_type[1]);
            ?></font> </b>
       
    </p>
    
    <br>
       <p class ="big" align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
       <u><b> <font style="font-size: 16px">
                
           Dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS \of F Y', strtotime($get_last_listed_date1)); ?></b>
               </font> </b></u>
 
    </p>


    <div style="margin-top: 190px;margin-left: 390px">
     <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p class ="big" style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
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
    ?>  </table></div>
   <?php } }
   else  if($row['individual_multiple']==2)
   {
      
       if($tot_copy_send_to_adv!='')
{
    $c_sno=1;
   $tot_copy='';
  $ex_c_s_t=explode('@',$tot_copy_send_to_adv);
    $tot_copy=$tot_copy.'<div style="margin-left: 30px"><table>';
            for ($index = 0; $index < count($ex_c_s_t); $index++) {
                 $ex_explode=explode('!',$ex_c_s_t[$index]);
                  $tot_copy=$tot_copy.'<tr>
                  <td style="font-size: 13pt;vertical-align: top">'.$c_sno;
                   $tot_copy=$tot_copy.'</td>
                    <td >
                        <div style="font-size: 13pt; " face="Times New Roman" >';
                    $ex_exp=  explode('~', $ex_explode[0]);
                        $tot_copy=$tot_copy. $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2])).$ex_explode[3];
                         $tot_copy=$tot_copy.'</div>
                        <div style="font-size: 13pt; " face="Times New Roman" >'.ucwords(strtolower($ex_explode[2])).', '.ucwords(strtolower($ex_explode[1])).'</div></td>
              </tr>';
               $c_sno++;   
               
             }  $tot_copy=$tot_copy.'</table></div>';
          
}
echo $tot_copy;
   }


   ?>

    </div>
    </b>
    <br><br>
    <div align='right'>
        <table>
              <?php $pet_adv=get_pet_adv_all($dairy_no);
              foreach ($pet_adv as $ad)
                {
                  ?>
            <tr><td><?php echo ucwords(strtolower($ad)).",";?> <?php
                      
                }
         
                  
            echo "<br>Advocate on record for the ".$text;
              
               
                
         ?>
                </td></tr>
        </table>
    </div>
    <br>
      <div align='right'>
        <table>
              <?php $pet_adv=get_res_adv_all($dairy_no);
              foreach ($pet_adv as $ad)
                {
                  ?>
            <tr><td><?php echo ucwords(strtolower($ad)).",";?> <?php
                      
                }
         
                  
            echo "<br>Advocate on record for the ".$text;
              
               
                
         ?>
                </td></tr>
        </table>
    </div>
        <br>
    
</div>


