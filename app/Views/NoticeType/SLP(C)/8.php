
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
  <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt" face= "Times New Roman"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 13pt" face= "Times New Roman">CIVIL APPELLATE JURISDICTION</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt" face= "Times New Roman" ><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
   
     <!--<p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt'>-->
       <?php
    
     $lower_court= lower_court($dairy_no);
     
    $get_last_listed_date = last_listed_date($dairy_no,'1');
//       $get_last_listed_date= '2015-07-03';
   $get_misc_re= get_misc_re($dairy_no);
   if($get_last_listed_date){
    $listed_dt = date('dS F, Y', strtotime($get_last_listed_date));
   }else{
    $listed_dt = NULL;
   }
     
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
         <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u style="font-size: 13pt">IN</u>
    </p>
     <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt" face= "Times New Roman"><b style="font-size: 13pt" face= "Times New Roman"><?php echo $skey ?> </b> <b style="font-size: 13pt" face= "Times New Roman"><?php echo $lct_caseno; ?></b> OF <b style="font-size: 13pt" face= "Times New Roman"><?php echo $lct_caseyear; ?></b></u>
         
    </p>
    <?php
         }
       ?>
<!--   </p>-->
    <?php
    if($row['individual_multiple']==1)
    {
    ?>

        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->

    <?php } ?>
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt" face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno; ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php echo $res_fil_det['res_name'].$rno; ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
      <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt" face= "Times New Roman" >To,</font></b></p>
     <?php
   if($row['individual_multiple']==1)
   {
   ?>

      <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        
        <b> <font style="font-size: 13pt" face= "Times New Roman">
            <?php
              echo $tw_sn_to; ?></b>, 
    </p>
    <?php if($address_m!='') { ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        <b> <font style="font-size: 13pt" face= "Times New Roman">
           
             
          <b style="font-size: 13pt"><?php echo $address_m; ?></b>, 
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
        <b> <font  style="font-size: 13pt" face= "Times New Roman">
           
             
         District- <b style="font-size: 13pt" face= "Times New Roman"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
         WHEREAS the Curative Petition above mentioned (copy enclosed) filed in the Registry by 
        <?php
     $get_petitioner_advocate=get_petitioner_advocate($dairy_no);
        ?>
         <b style="font-size: 13pt" face= "Times New Roman"><?php echo $get_petitioner_advocate; ?></b>, Advocate on behalf of the Petitioner above named was listed for hearing before  
         this <b style="font-size: 13pt" face= "Times New Roman"><?php echo $get_last_listed_date[3]; ?></b> on <b style="font-size: 13pt" face= "Times New Roman"><?php echo $first_listed_date ?></b>, when  the Court was pleased to pass the following
         order:-
</font>
     </p>
   <?php
       $diary_no= substr($dairy_no,0,-4);  
             $diary_year= substr($dairy_no,-4); 
            
     if($get_first_listed_date!='')
     {
        $fil_nm= get_text_pdf($dairy_no,$get_first_listed_date);
     
     }
     ?>
      <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 12px" face= "Times New Roman" >
           "<?php
          // echo  utf8_encode($b_z); 
           echo read_txt_file($fil_nm);
  ?>"
         </font></b>
    </p>
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt" face= "Times New Roman">
         <?php 
          $get_last_listed_dates= last_listed_date($dairy_no,'');
          $last_listed_dts=date('dS F, Y', strtotime($get_last_listed_dates[1]));
         ?>
AND WHEREAS, the matter above mentioned was listed before the Ld. 
<b style="font-size: 13pt"><?php echo $get_last_listed_dates[3] ?></b> on 
<b style="font-size: 13pt"><?php echo $last_listed_dts ?></b> , 
when the following order was passed:-
             </font>
    </p>
    
     <?php
    
     if($get_last_listed_dates[1]!='')
     {
        $fil_nm_s= get_text_pdf($dairy_no,$get_last_listed_dates[1]);
     
     }
     ?>
      <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 20px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt" face= "Times New Roman" >
           "<?php
          // echo  utf8_encode($b_z); 
           echo read_txt_file($fil_nm_s);
  ?>"
         </font></b>
    </p>
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt" face= "Times New Roman">
NOW, THEREFORE, TAKE NOTICE that the above curative petition will be posted for hearing before this 
Court in due course and you may enter appearance before this Court either  in person or through an advocate 
on  record of this Court duly appointed by you in that behalf within 30 days from the date of service of notice.  You may thereafter show cause to the Court on the day that may subsequently  be specified as  to why Curative Petition  and Review Petition be not allowed and Special Leave Petition be not granted and the resultant appeal be not allowed. 
             </font>
    </p>
     <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt" face= "Times New Roman">
      You may file your affidavit in opposition to the petition as provided under Rule 14(1) 
      of Order XXI, S.C.R.2013 forthwith from the date of receipt of notice or not later than 2 weeks
      before the date appointed for hearing, whichever be earlier, but shall do so only by setting out 
      the grounds in opposition to the questions of law or grounds set out in the petition and may produce 
      such pleadings and documents filed before the Court/Tribunal against whose order the petition is filed 
      and shall also set out the grounds for not granting interim order or for vacating interim order 
      if already granted.
             </font>
    </p>
     
     
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman">
   Dated :<b style="font-size: 13pt" face= "Times New Roman"><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>
   
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt" face= "Times New Roman" >
    Copy to :-
   </font></p>
 <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt;text-transform: uppercase;" face= "Times New Roman"  >
<?php  if($tot_copy_send_to!='') { ?>  
<div style="margin-left: 30px" face= "Times New Roman"><?php
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
                        <div style="font-size: 13pt" face= "Times New Roman"> <?php
                        $ex_exp=  explode('~', $ex_explode[0]);
                        echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));
                        
                        ?></div>
                        <div style="font-size: 13pt" face= "Times New Roman"> <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
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
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px" face= "Times New Roman"><b><font style="font-size: 13pt" face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
</div>
<?php
include("legal_aid.php");
?>
