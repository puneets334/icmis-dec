<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
   <?php


    if( $res_fil_det['casename']=='CIVIL APPEAL' || $res_fil_det['casename']=='CRIMINAL APPEAL')
{
$text='Appellant(s)';
$text1="Appeal";

}
else if( $res_fil_det['casename']=='SPECIAL LEAVE PETITION (CIVIL)' || $res_fil_det['casename']=='SPECIAL LEAVE PETITION (CRIMINAL)')
{
$text='Petitioner(s)';
$text1="Petition for Special Leave to Appeal";

}
else if( $res_fil_det['casename']=='TRANSFER PETITION (CIVIL)' || $res_fil_det['casename']=='TRANSFER PETITION (CRIMINAL)' || $res_fil_det['casename']=='WRIT PETITION (CIVIL)' || $res_fil_det['casename']=='WRIT PETITION(CRIMINAL)')
{
$text='Petitioner(s)';
$text1="Petition";

}
?>
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>





    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 12px">(<?php echo $r_nature;?> Appellate/Original Jurisdiction)</b></font>

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
     <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> in Petition for 
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

        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >

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
<br>
<?php
$get_total_pet_parties= get_total_pet_parties($dairy_no,'P');

?>
<div align="center" style="width: 100%;clear: both">
    <!--       <table cellpadding="10" cellspacing="10" style="width: 100%" >
                <tr>
                    <td style="font-size: 13pt;"  face= "Times New Roman" >-->
    <table width="100%" border="0" style="border-collapse: collapse;" cellspacing="5" cellpadding="0">
        <?php
        $p_sno=1;
        $chk_p_sno=1;
        for ($index5 = 0; $index5 < count($get_total_pet_parties); $index5++) {
            $p_sno++;
        }
        for ($index5 = 0; $index5 < count($get_total_pet_parties); $index5++) {


            ?>
            <tr>
                <td style="font-size: 13pt;width:10%" face= "Times New Roman">
                    <?php
                    echo $get_total_pet_parties[$index5][7].'['.$get_total_pet_parties[$index5][6].']';
                    ?>
                </td>
                <td style="font-size: 13pt;width:70%" face= "Times New Roman">
                    <?php
                    echo  $get_total_pet_parties[$index5][0].' '. $get_total_pet_parties[$index5][8];
                    ?>

                    <?php
                    echo $get_total_pet_parties[$index5][1];

                    $city= $get_total_pet_parties[$index5][4];
                    $state= $get_total_pet_parties[$index5][3];
                    echo ' '.get_district($city).' ';
                    echo ' '.get_district($state);
                    ?>
                </td>
                <?php if($chk_p_sno==1) { ?>
                    <td   style="vertical-align: middle;font-size: 13pt;text-align: right;width: 20%" face= "Times New Roman" rowspan="<?php echo $p_sno; ?>">
                        ... <?php echo $text; ?>
                    </td>
                <?php } ?>
            </tr>

            <?php

            $chk_p_sno++;
        } ?>
        <tr>
            <td colspan="3" style="text-align: center;font-size: 13pt;text-align: center;width: 10%" face= "Times New Roman">
                <B>VERSUS</B>
            </td>
        </tr>
        <!--                    </table>-->
        <?php $get_total_res_parties= get_total_pet_parties($dairy_no,'R'); ?>
        <!--<table width="100%" border="1" style="border-collapse: collapse;">-->
        <?php
        $r_sno=1;
        $chk_r_sno=1;
        for ($index5 = 0; $index5 < count($get_total_res_parties); $index5++) {
            $r_sno++;
        }
        for ($index5 = 0; $index5 < count($get_total_res_parties); $index5++) {


            ?>
            <tr>
                <td style="font-size: 13pt;width:10%" face= "Times New Roman">
                    <?php
                    echo $get_total_res_parties[$index5][7].'['.$get_total_res_parties[$index5][6].']';
                    ?>
                </td>
                <td style="font-size: 13pt;width:70%" face= "Times New Roman">
                    <?php
                    echo  $get_total_res_parties[$index5][0].' '. $get_total_res_parties[$index5][8];
                    ?>

                    <?php
                    echo $get_total_res_parties[$index5][1];

                    $city= $get_total_res_parties[$index5][4];
                    $state= $get_total_res_parties[$index5][3];
                    echo ' '.get_district($city).' ';
                    echo ' '.get_district($state);
                    ?>
                </td>
                <?php if($chk_r_sno==1) { ?>
                    <td   style="vertical-align: middle;font-size: 13pt;text-align: right;width: 20%" face= "Times New Roman" rowspan="<?php echo $p_sno; ?>">
                        ... Respondent(s)
                    </td>
                <?php } ?>
            </tr>

            <?php

            $chk_r_sno++;
        } ?>
    </table>

    <!--                </td>-->
    <!--               <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%" face= "Times New Roman">
                       VERSUS
                    </td>-->
    <!--
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php// echo $res_fil_det['res_name'] ?>
                </td>

                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>-->

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
                    <?php // echo $res_fil_det['res_name'] ?>
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
 <b>(FOR FULL CAUSE TITLE PLEASE SEE SCHEDULE 'A' ANNEXED HEREWITH)
 </b></div>
<?php

        $get_notice_dt= get_date_by_remark($dairy_no,'7');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else
           $vac_date='......';

?>

<div align="justify" style="margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
    <b>Date : <?php echo $listed_dt; ?>  </b>   This petition was called on for hearing today.
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
                    For the <?php echo $text;?>
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
        <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'7');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
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
        <?php echo $text1;  ?> <?php if($tot_application!='') { ?> alongwith Interlocutory Application <?php } ?>
        above-mentioned being called on for hearing before this Court on the 
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b>, UPON
perusing the record and hearing counsel for the parties herein, the
Court took time to consider its Judgment and the matter being called
on for Judgment on the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>, 
when <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo str_replace('<br/>', ' and ', $jname); ?></b> pronounced the reportable
Judgment of the Bench comprising <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $jname_v; ?></b> and His Lordship, THIS COURT DOTH grant Special leave to Appeal and for the reasons and
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
      <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 20px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
           "<?php
    echo read_txt_file($fil_nm_s);
  ?>"
         </font></b>
    </p>
     
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
        $get_notice_dt= get_date_by_remark($dairy_no,'157');
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         ?>
         <!--    AND THIS COURT DOTH <b>FURTHER</b> ORDER that this ORDER be punctually observed and carried into execution by all concerned
      </font>-->
    </p>
     <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
<!-- AND THIS COURT DOTH LASTLY ORDER that this ORDER
be punctually observed and carried into execution by all concerned;-->
             </font>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 <!--WITNESS the <b style="font-size: 13pt"  face= "Times New Roman" > Hon'ble Shri <?php echo $chief_name=chief_name();
 ?></b>, Chief Justice of India at the Supreme Court, New Delhi, 
 dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b>.
             </font>--.
    </p>
<!--      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>-->

<?php $sql_ct="select casecode from casetype where casename ='$res_fil_det[casename]'";
      $rs=mysql_query($sql_ct);

      $rw=mysql_fetch_array($rs);
      $c_type=$rw[0];
     // echo "case type is ".$c_type;

$rs_ma=get_ma_info1($c_type, $case_range,$reg_year);
//echo " the ma no is ".$rs_ma;

$d_d=explode('-',$rs_ma);

$dno_ma=$d_d[0];   // diary  no of ma
$reg_ma=$d_d[1];  // registration no of ma
//$get_total_pet_parties[$index5][1];
?>
  <U align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
      <b> <CENTER><font style="font-size: 16px"  ><b><U></b><?php  echo "MISCELLANEOUS APPLICATION NO. ". $reg_ma; ?></b></U></CENTER></font>

    </p>
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo "IN" ?></font></b></u>

</p>
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

</p>
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
    <u><b><font style="font-size: 13pt" face= "Times New Roman">WITH</font></b></u></p>
<?php
$get_application_registration= get_application_registration_all($dno_ma);
?>
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
    <u><b><font style="font-size: 13pt" face= "Times New Roman">
                <?php
                $s_no=0;
                for ($index2 = 0; $index2 < count($get_application_registration); $index2++) {

                    if($s_no>0)
                    {
                        ?>
                        <div align="center" style="font-size: 13pt;margin-top: 5px;margin-bottom: 5px" face= "Times New Roman">
                            AND
                        </div>
                        <?php
                    }
                    ?>
                    <div align="center" style="font-size: 13pt" face= "Times New Roman">
                        <?php
                        echo 'I.A. No. '. $get_application_registration[$index2][1].' - '.'APPLICATION FOR '.$get_application_registration[$index2][0];

                        ?>
                    </div>
                    <?php

                    $s_no++;
                }
                ?>
            </font></b></u></p>
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
                ... <?php echo $text;?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 13pt;text-align: left"  face= "Times New Roman"  >
                <?php echo $res_fil_det['res_name'] ?>
            </td>

            <td style="font-size: 13pt;text-align: right"  face= "Times New Roman" >
                ... Respondent(s)
            </td>
        </tr>
    </table>


</div>
<?php
$get_application_registration_all= get_application_registration_all($dno_ma);
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
         <?php }

 $get_last_listed_date2= get_last_listed_date($dno_ma);
$listed_dt2=date('dS F, Y', strtotime($get_last_listed_date2));

         ?>

</u>
<div align="justify" style="margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
    <b>Date : <?php echo $listed_dt2; ?>  </b>   This petition was called on for hearing today.
</div>

<div align="right" style='margin-top: 10px;padding: 2px 0px 0px 0px;'>
    <u style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></u>
</div>

<p style="margin: 10px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >CORAM :</font></b></p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
        <?php
        $last_listed_date= last_listed_date($dno_ma);
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
<div style="margin-top: 50px">
    <table width='100%' cellpadding='5' cellspacing='5'>
        <tr>
            <td style="font-size: 13pt;"  face= "Times New Roman" width: 30%">
            For the Applicant
            </td>
            <td>
                :
            </td>
            <td style="font-size: 13pt"  face= "Times New Roman" >
                <?php
                $tot_petitioner_adv= tot_petitioner_adv($dno_ma);
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
                For Non-applicant
            </td>
            <td>
                :
            </td>
            <td style="font-size: 13pt"  face= "Times New Roman" >
                <?php
                $tot_petitioner_adv= tot_respondent_adv($dno_ma);
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
        <?php
        $get_notice_dt= get_date_by_remark($dno_ma,'7');
        if($get_notice_dt!='')
            $vac_date= date('dS F, Y', strtotime($get_notice_dt));
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
        ?></u>
        The Miscelleneous Application in the Appeal above mentioned <?php if($tot_application!='') { ?> alongwith Interlocutory Application <?php } ?>
        above-mentioned being called on for hearing before this Court on the <b> <?php echo $listed_dt2;?></b>
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php //echo ; ?></b>, UPON
        perusing the record and hearing counsel for the parties herein,THIS COURT DOTH
      PASS the following ORDER :

    </font>
</p>

<?php

if($get_last_listed_date2!='')
{
    $fil_nm_s= get_text_pdf($dno_ma,$get_last_listed_date2);

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
        <?php
        $get_notice_dt= get_date_by_remark($dno_ma,'157');
        if($get_notice_dt!='')
            $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else
            $vac_date='......';
        ?>
        <!--    AND THIS COURT DOTH <b>FURTHER</b> ORDER that this ORDER be punctually observed and carried into execution by all concerned
     </font>-->
</p>
<p style="color: #000000;text-indent: 40px;padding: 40px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman" >
      AND THIS COURT DOTH <B>FURTHER</B> ORDER that this ORDER be punctually observed and carried into execution  by all concerned;


    </font>
</p>
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman" >
        WITNESS the <b style="font-size: 13pt"  face= "Times New Roman" > Hon'ble Shri <?php echo $chief_name=chief_name();
        ?></b>, Chief Justice of India at the Supreme Court, New Delhi,
 dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt."/".$listed_dt2; ?></b>.
             </font>
    </p>
<!--      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>-->


    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(&nbsp;&nbsp;  &nbsp;&nbsp;)</font></b></p>
    
   
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
</div>
<div style="page-break-before: always">
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 16px">(<?php echo $r_nature;?> Appellate/Original Jurisdiction)</b></font>

    </p>

    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >


    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
    <?php
     for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
           ?>
     <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> in Petition for 
       <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b> against the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b> 
       of the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in 
       <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>)</div>
       <?php
         
       }
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
                   ... <?php echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left"  face= "Times New Roman"  >
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right"  face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>

     
   </div>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px" align="center">
            DECREE <?php 
           $get_dismissal_type= get_dismissal_type($dairy_no); 
           echo strtoupper( $get_dismissal_type[1]);
            ?></font> </b>
 
    </p>
       <p align="right" style='margin: 0px;padding: 2px 0px 0px 0px;'>
       <u><b> <font style="font-size: 16px">
           Dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b><br>
                   Dated this the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt2; ?></b>
               </font> </b></u>
 
    </p>
</p>

    <div style="margin-top: 190px;margin-left: 390px">
     <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt;text-transform: uppercase;"  face= "Times New Roman"  >
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
       echo $tot_copy;
   }
   ?>
    </div>
<div style="margin-top: 50px" align =center>
    <table width='50%' cellpadding='5' cellspacing='5'>
        <tr>
            <td style="font-size: 13pt;"  face= "Times New Roman" width: 30%">
            For the <?php echo $text;?>
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
                        $total_pet=$total_pet.'<br/>'.$tot_petitioner_adv[$index3][0].$tot_petitioner_adv[$index3][1];
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
</div>

