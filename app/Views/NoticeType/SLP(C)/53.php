<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
  
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman">Notice of Lodgement of Petition of Appeal to the Respondent</font> </b>
 
    </p>
     <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman">(RULE 8(I) & (III) of ORDER XIX, S.C.R. 2013)</font> </b>
 
    </p>

    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 13pt"><?php  $ct=get_casetype($dairy_no); if($ct==1 or $ct==2 ){ echo "EXTRA-ORDINARY APPELLATE JURISDICTION";}else{ echo "CIVIL APPELLATE JURISDICTION";}?></b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt'>
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
  
 $get_order_date= get_order_date($dairy_no);
           ?>
         <div style="font-size: 13pt;margin-bottom: 10px;text-align: justify">(Appeal by Special Leave granted by this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo date('dS F, Y', strtotime($get_order_date[0])); ?></b> in Petition for 
       <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $judgement_dt; ?></b> 
       of the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in 
       <?php
       $ex_skey=  explode(',',$skey );
       $ex_lct_caseno=explode(',',$lct_caseno );
       $ex_lct_caseyear=explode(',',$lct_caseyear );
       for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
           if($index2>0){ echo ',';}
       ?>
       <b style="font-size: 13pt"  face= "Times New Roman"> <?php echo $ex_skey[$index2] ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseno[$index2]; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
       <?php
         
       }
       ?>
   </p>
    <?php
    if($row['individual_multiple']==1)
    {
    ?>

        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->

    <?php } ?>
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt;width: 45%"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right;width: 45%" face= "Times New Roman">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
      <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman" >To,</font></b></p>
     <?php
   if($row['individual_multiple']==1)
   {
   ?>
      <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
            <?php
              echo $tw_sn_to; ?></b>, 
    </p>
    <?php if($address_m!='') { ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
           
             
          <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $address_m; ?></b>, 
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
        <b> <font  style="font-size: 13pt"  face= "Times New Roman">
           
             
         District- <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
   <?php }
   else if($row['individual_multiple']==2)
   {
       echo $tot_records;
   }
   ?>
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
        <?php
      $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));
     $get_date_by_remark= get_date_by_remark($dairy_no, ['1,41,176,177,178']);
     if($get_date_by_remark!='')
     $remark_dt=date('dS F, Y', strtotime($get_date_by_remark));
        ?>
        TAKE NOTICE that the Appellant above-named has on the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $diary_no_rec_date; ?></b> filed in the Registry of 
      the Supreme Court, Petition for  <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[2] ?></b> (Copy enclosed) 
      from the Judgment and Order of the High Court above mentioned and pursuant to this Court's order dated 
      <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $remark_dt; ?></b> granting Special Leave to Appeal the case has been registered as
      <b style="font-size: 13pt"  face= "Times New Roman"> <?php echo $res_fil_det['casename'] ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $case_range; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $reg_year; ?></b> 
          <?php 
//          $get_order_connected=get_order_connected($dairy_no,$date,$remark_head); ?>.
</font>
     </p>
   <?php
   $represented_adv=not_represented_adv($dairy_no,$row['order_dt']);
       $cnt_rep_adv=0;
        for ($index4 = 0; $index4 < count($represented_adv); $index4++) {
            $cnt_rep_adv++;
        }
   if($cnt_rep_adv>=1)
   {
        ?>
     
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
      NOTICE IS HEREBY given to you that if you wish to contest the appeal, you may appear before 
      this Court within thirty days of the receipt of this Notice either in person or by an Advocate-on-Record 
      of this Court appointed by you in that behalf and take such part in the proceedings as you may be advised.
      </font>
     </p>
     <?php
      if($short_description_s=='CIVIL APPEAL')
      {
     $get_main_case=get_main_case_n($dairy_no);
     if($get_main_case!='')
     {
       $get_registration_diary=get_registration_diary($get_main_case);
      $ex_get_registration_diary= explode('-',$get_registration_diary[0]);
      $ex_no='';
      $chk_prv_no='';
      for ($index3 = 1; $index3 < count($ex_get_registration_diary); $index3++) {
         if($chk_prv_no!=intval($ex_get_registration_diary[$index3]))
         {
          if($ex_no=='')
          $ex_no= intval($ex_get_registration_diary[$index3]);
         else 
              $ex_no=$ex_no.'-'.intval($ex_get_registration_diary[$index3]);
         $chk_prv_no=intval($ex_get_registration_diary[$index3]);
         }
      }
      $ex_exp=$ex_get_registration_diary[1];
      $ex_get_registration_diary=  explode('-', $get_registration_diary[0]);
     $get_casetype_code= get_casetype_code($ex_get_registration_diary[0]);
    
    $get_case_details= get_case_details($get_main_case);
   $p_no='';
   $r_no='';
    if($get_case_details[5]=='2')
    {
        $p_no=" AND ANOTHER";
    }
    else  if($get_case_details[5]>'2')
    {
        $p_no=" AND OTHERS";
    }
    
    if($get_case_details[6]=='2')
    {
        $r_no=" AND ANOTHER";
    }
    else  if($get_case_details[6]>'2')
    {
        $r_no=" AND OTHERS";
    }
         ?> 
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
        TAKE FURTHER NOTICE that the instant Appeal is directed to tag with  
        <b><?php echo $get_casetype_code.' '.$ex_no.'/'.date('Y',strtotime($get_registration_diary[1])); ?></b>
        entitled "<b><?php echo $get_case_details[3].$p_no; ?> vs <?php echo $get_case_details[4].$r_no; ?></b>"
      </font>
     </p>
   <?php } }  ?>
     
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
      TAKE FURTHER NOTICE THAT in default of your appearance within the time prescribed, the appeal 
      will be proceeded with and determined in your absence and no further notice in relation thereto 
      shall be given to you. 
</font>
     </p>
   <?php } ?>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman" >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman"><?php echo date('dS F, Y'); ?></b>
             </font>
    </p><br><br><br>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman" >
    Copy to :-
   </font></p>
 <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt;text-transform: uppercase;"  face= "Times New Roman" >
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
                  <td style="font-size: 13pt;vertical-align: top" face= "Times New Roman">
                      <?php echo $c_sno; ?>
                  </td>
                    <td >
                        <div style="font-size: 13pt"  face= "Times New Roman"> <?php
                        $ex_exp=  explode('~', $ex_explode[0]);
                        echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));
                        
                        ?></div>
                        <div style="font-size: 13pt"  face= "Times New Roman"> <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
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
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
</div>


<?php
 if($short_description_s!='CIVIL APPEAL')
include("legal_aid.php");

?>
