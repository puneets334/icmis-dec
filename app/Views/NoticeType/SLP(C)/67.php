<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 49%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt" border="1" >
       <?php echo get_text_msg();?>
     </div>
    <div style="width: 49%;float: right;font-size: 13pt;text-align: center">
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b></br></br>
        D. No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b style="font-size: 13pt"  face= "Times New Roman"><?php echo get_section($dairy_no); ?></b>
        <div style="font-size: 13pt;"  face= "Times New Roman">
            SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 13pt;"  face= "Times New Roman">
            NEW DELHI
        </div>
        <div style="font-size: 13pt;"  face= "Times New Roman">
           <?php 
          echo date('dS F, Y')
           ?>
        </div>
     </div>
    
   
    
    
   
     <?php
   if($row['individual_multiple']==1)
   {
   ?>
       <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
   <?php } ?>
      <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
        ASSISTANT REGISTRAR,</b>
    </p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
        Supreme Court of India, New Delhi.</b>
    </p>
   
   <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >To,</font></b></p>
  
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

               <b>(
                   <?php if($case_range!=''){?>

                   <?php  if($res_fil_det['short_description']!=''){echo $res_fil_det['short_description']; }
                   else echo "Diary No. ";
                   echo $case_range; ?> / <?php echo $reg_year;?>
               </b>
               <?php    }   ?> / <b><?php echo get_section($dairy_no);?> )</b>
           </font>
       </p>
    <?php }
   else if($row['individual_multiple']==2)
   {
       echo $tot_records;
   }
   ?>
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
     <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt" face= "Times New Roman">
         WITH PRAYER FOR INTERIM RELIEF
            </font></b></u>
    </p>
<!--    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u><b><font style="font-size: 13pt" face= "Times New Roman">WITH</font></b></u></p>
   <?php
    $get_application_registration= get_application_registration($dairy_no);
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
           echo "IA No. ". $get_application_registration[$index2][1].'- '.'APPLICATION FOR '.$get_application_registration[$index2][0];
           
           ?>
        </div>
        <?php
       
        $s_no++;
    }
    ?>
        </font></b></u></p>-->
     <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
       <?php
     $lower_court= lower_court_conct($dairy_no);
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
<!--           <div style="font-size: 13pt;margin-bottom: 10px">(Appeal by Special Leave granted by this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $listed_dt; ?></b> in Petition for 
       <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $judgement_dt; ?></b> 
       of the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in 
       <?php
       $ex_skey=  explode(',',$skey );
       $ex_lct_caseno=explode(',',$lct_caseno );
       $ex_lct_caseyear=explode(',',$lct_caseyear );
       for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
           if($index2>0){ echo ',';}
       ?>
       <b style="font-size: 13pt"  face= "Times New Roman"> <?php echo $ex_skey[$index2] ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseno[$index2]; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>-->
       <?php
         
       }
       ?>
   </p>
   
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt;width: 45%"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%" face= "Times New Roman">
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
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >Sir,</font></b></p>  
   
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
   I am directed to enclose herewith the notice for effecting Dasti service on the 
   <b><?php
  $get_respondents= get_respondents($dairy_no);
  $respondents='';
  for ($index4 = 0; $index4 < count($get_respondents); $index4++) {
    if($respondents=='')
        $respondents=$get_respondents[$index4][0];
    else 
         $respondents=$respondents.', '.$get_respondents[$index4][0];
}
echo "Respondent No. ". $respondents;
?></b> and to request you to file an Affidavit of Dasti Service within 28 days from the date of issue of the notice under the proviso of Rule 3 of Order LIII, SCR 2013.
   
   
</font>
     </p>
    
    
    
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >Yours faithfully,</font></b></p>
    <p align="right" style="padding: 50px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
   

 
</div>


