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

<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
    <b><i><u>Delivery Mode:
                <?php
                $mod= get_delivery_mod($row[process_id],$row[rec_dt1]);
                echo $mod;
                ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
  
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">Notice of Lodgement of Petition of Appeal to the Respondent</font> </b>
 
    </p>
     <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">(S.C.R., Order XXII Rule 11)</font> </b>
 
    </p>


    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b>(Criminal Appellate Jurisdiction)</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt;face= "Times New Roman'>
       <?php
     $lower_court= lower_court_conct($dairy_no);
    $get_last_listed_date= get_last_listed_date($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
     $chk_array=array();
     $ins_array=array();
       for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
  
           ?>
         <div style="font-size: 13pt;"  face= "Times New Roman" align="justify">(Appeal by Special Leave granted by this Court's Order dated the <b><?php echo $listed_dt; ?></b> in Petition for
       <b><?php echo $get_misc_re[0] ?></b> <b><?php echo $get_misc_re[1] ?></b> of <b><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the
        <b><?php echo $judgement_dt; ?></b>
       of the <b><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
       <?php
       $ex_skey=  explode(',',$skey );
       $ex_lct_caseno=explode(',',$lct_caseno );
       $ex_lct_caseyear=explode(',',$lct_caseyear );
       for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
           if($index2>0){ echo ',';}
       ?>
       <b> <?php echo $ex_skey[$index2] ?> </b> No. <b><?php echo ltrim($ex_lct_caseno[$index2],'0'); ?></b> of <b><?php echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
       <?php
         
       }
       ?>
   </p>
    <?php
    if($row[individual_multiple]==1)
    {
    ?>

        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->

    <?php } ?>
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
                   ... <?php echo $text;?>
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
      <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
     <?php
   if($row[individual_multiple]==1)
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

               <b>(
                   <?php if($case_range!=''){?>

                   <?php  if($res_fil_det[short_description]!=''){echo $res_fil_det[short_description]; }
                   else echo "Diary No. ";
                   echo $case_range; ?> / <?php echo $reg_year;?>
               </b>
               <?php    }   ?> / <b><?php echo get_section($dairy_no);?> )</b>
           </font>
       </p>
   <?php }
   else if($row[individual_multiple]==2)
   {
       echo $tot_records;
   }
   ?>
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
        <?php
      $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
        ?>

            TAKE NOTICE that the Appellant above-named has on the<b><?php echo $diary_no_rec_date;?>,</b> filed in the Registry of Supreme Court
            Petition for  <b><?php echo $get_misc_re[0] ?></b> <b  ><?php echo $get_misc_re[1] ?></b> of <b ><?php echo $get_misc_re[2] ?></b>(Copy enclosed)
            from Judgment and Order of the High Court above mentioned and pursuant of this Court's order dated <b><?php echo $listed_dt; ?></b>
            granting Special Leave to Appeal the case has been registered in this Court as  <b> <?php echo $res_fil_det['casename'] ?> </b> No. <b><?php echo $case_range; ?></b>
            of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $reg_year; ?></b>.
     </p>
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
  TAKE FURTHER NOTICE that in accordance with the provisions contained in Rule 8, Order XX, Supreme Court Rule 2013, the Appeal above-mentioned will be heard on the paper books
  for the  hearing of the Petition of Appeal and the paper books of the Court below (in English) if available plus additional documents to be filed by the parties, if
  the paper books of the Court below are not available.
             </font>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 NOTICE IS HEREBY GIVEN TO YOU that if you wish to contest the appeal you may enter appearance within thirty days of the receipt of this Notice before this Court either in person
 or by an advocate-on-record of this Court appointed by you in this behalf and take such part in the proceedings as you may advised.
         </font>
    </p>
     <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" ><b>
                 TAKE FURTHER NOTICE THAT in default of your appearance the matter will be decided and determined in your absence.
             </b></font>
    </p>
     
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
    Copy to :-
   </font></p>
 <?php
   if($row[individual_multiple]==1)
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
   else  if($row[individual_multiple]==2)
   {
       echo $tot_copy;
   }
?></font><font style="font-size: 13pt;"  face= "Times New Roman"  >
        You are requested to take further steps for the prosecution of the appeal in accordance with the procedure prescribed by S.C.R.2013.
 </font></p>
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
</div>

