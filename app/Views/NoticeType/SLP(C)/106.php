<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt" border="1" >
        <?php echo get_text_msg();?>
     </div>
    <div style="width: 40%;float: right;font-size: 13pt;text-align: center">
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b></br></br>
        D. No. <b style="font-size: 13pt" face= "Times New Roman"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b style="font-size: 13pt" face= "Times New Roman"><?php echo  get_section_user($ucode); ?></b>
        <div style="font-size: 13pt;" face= "Times New Roman">
            SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 13pt;" face= "Times New Roman">
            NEW DELHI
        </div>
        <div style="font-size: 13pt;" face= "Times New Roman">
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
      <p style="margin: 0px;padding: 0px 0px 20px 2px;clear: both"><b><font style="font-size: 13pt" face= "Times New Roman" >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt" face= "Times New Roman" >
         The Assistant Registrar,</b>
    </p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt" face= "Times New Roman" >
        Supreme Court of India, New Delhi.</b>
    </p>
   
   <p style="margin: 10px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt" >To,</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt" >
      </b>
    </p>
 <?php
   if($row['individual_multiple']==1)
   {
   ?>  
      <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        
        <b> <font style="font-size: 13pt" face= "Times New Roman" >
            <?php
              echo $tw_sn_to; ?></b>, 
    </p>
    <?php if($address_m!='') { ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        <b> <font style="font-size: 13pt" face= "Times New Roman" >
           
             
          <b style="font-size: 13pt"><?php echo $address_m; ?></b>, 
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
        <b> <font  style="font-size: 13pt" face= "Times New Roman">
           
             
         District- <b style="font-size: 13pt"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
    $app= get_application_registration_d($dairy_no) ;
 
     
   ?>
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt" face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
 </br>   </br><u><b><font style="font-size: 13pt" face= "Times New Roman" id="append_data"><?php echo "IA No ".$app[0][1]."-". $app[0][0];  ?></font></b></u>
         
    </p>
     <p align="justify" style='margin: 5px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
       <?php
       
      
       
        $lower_court= lower_court_conct($dairy_no);
   
 $get_last_listed_date=  get_notice_dt($dairy_no);
   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
 for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
  
           ?>
         <div style="font-size: 13pt;margin-bottom: 10px" face= "Times New Roman">(Petition under Article 136 of the Constitution of India from the judgement and Order dated  <b style="font-size: 13pt" face= "Times New Roman"><?php echo $judgement_dt; ?></b> 
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
   </p>
   
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
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman">Sir,</font></b></p>  
    <?php
      $diary_no_rec_date=get_dismissal_type($dairy_no);
     $dispose_detail=dispose_detail($dairy_no);
        ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0gn="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
        <?php
        if($short_description_s='WRIT PETITION(CRIMINAL)' || $short_description_s='WRIT PETITION (CIVIL)' ||
              $short_description_s='SUO MOTO WRIT PETITION(CIVIL)' || $short_description_s='SUO MOTO WRIT PETITION(CRIMINAL)' )
        {
           ?>
        In continuation of this Registry's letter dated &nbsp; &nbsp; I am directed to inform you that Transfer Petition above mentioned has been disposed of by this Hon'ble Court in terms of the signed Order dated 
         <u><b style="font-size: 13pt" face= "Times New Roman"><?php $disposal_dt= date('dS F, Y', strtotime($dispose_detail));
          echo $disposal_dt; ?></b></u> . I am further directed to forward herewith under Rule 5 of Order XIII of the Supreme Court Rules, 2013, a Certified Copy of the Order of this Court dated  <b><u><?php $disposal_dt= date('dS F, Y', strtotime($dispose_detail));
          echo $disposal_dt; ?></b></u>  in the petition above mentioned alongwith Settlement Agreement dated &nbsp; &nbsp; in the matter is being forwarded herewith for your information.  Certified Copy of the Decree in the aforesaid matters will be sent in due course.
   <?php if($r_nature=='Criminal') { ?> <?php } ?>
 The  stay granted  vide this Court's Order dated &nbsp; &nbsp;  stands vacated.
       
        <?php
        }
   else   if($res_fil_det[nature]=='C')
     {
        ?>
        I am directed to inform you that the Petition above mentioned for Special Leave to Appeal
        to this Court was filed by and on behalf of the Petitioner above-named against the Judgment
        and Order of the High Court noted above and that the same was 
        <b style="font-size: 13pt" face= "Times New Roman"><?php echo $diary_no_rec_date[1]; ?></b> with certain directions 
        vide order of this Court on the <u><b style="font-size: 13pt" face= "Times New Roman"><?php $disposal_dt= date('dS F, Y', strtotime($dispose_detail));
          echo $disposal_dt; ?></b></u>. 
     <?php }
     else if($res_fil_det[nature]=='R')
     {
         ?>
          I am directed to forward herewith for your information and necessary action, 
          a certified copy of this Court's Record of Proceedings dated <u><b style="font-size: 13pt" face= "Times New Roman"><?php $disposal_dt= date('dS F, Y', strtotime($dispose_detail));
          echo $disposal_dt; ?></b></u>
           in the matter above mentioned alongwith Schedule containing full cause title of the parties. 
          <?php
     }
     ?>
</font>
     </p>
       <?php
     if($res_fil_det[nature]=='C'  && $short_description_s!='WRIT PETITION(CRIMINAL)' && $short_description_s!='WRIT PETITION (CIVIL)' &&
              $short_description_s!='SUO MOTO WRIT PETITION(CIVIL)' && $short_description_s!='SUO MOTO WRIT PETITION(CRIMINAL)')
     {
        ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
        
     A certified copy of the order of this Court as contained in the Record of Proceedings dated 
     <b><u style="font-size: 13pt" face= "Times New Roman"><?php echo $disposal_dt; ?></u></b> is 
     enclosed herewith for your information and necessary action.
</font>
     </p>
     <?php } ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
        
    Please acknowledge receipt.
</font>
     </p>
    
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px" face= "Times New Roman"><b><font style="font-size: 13pt" face= "Times New Roman" >Yours faithfully,</font></b></p>
   
   <p align="right" style="padding: 50px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
   
    <p align="justify" style="padding: 2px 2px 0px 0px;margin: 0px"><font style="font-size: 13pt" face= "Times New Roman" >Copy with a Certified copy of Order forwarded to:-</font>
    <p align="right" style="padding: 50px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
  
   Copy to :-
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt;text-transform: uppercase;" face= "Times New Roman" >
   
        
       
   </font></p>
 <?php
   if($row['individual_multiple']==1)
   {
   ?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt" face= "Times New Roman" >
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
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px" face= "Times New Roman"><b><font style="font-size: 13pt" face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
</div>


