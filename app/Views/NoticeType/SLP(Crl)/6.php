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
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt"  face= "Times New Roman"  border="1" >
       All Communications Should be Addressed to Registrar by Designation and not by Name.
     </div>
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
    <b><i><u>Delivery Mode:
                <?php
                $mod= get_delivery_mod($row[process_id],$row[rec_dt1]);
                echo $mod;
                ?></u></i></b></br></br>
        D. No. <b ><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b ><?php echo get_section($dairy_no); ?></b>
        <div style="font-size: 13pt;"  face= "Times New Roman" >
            SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 13pt;"  face= "Times New Roman" >
            NEW DELHI
        </div>
        <div style="font-size: 13pt;"  face= "Times New Roman" >
           <?php 
          echo date('dS F, Y')
           ?>
        </div>
     </div>
    
   
    
    
   
    <?php
   if($row[individual_multiple]==1)
   {
   ?>
       <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
   <?php } ?>
      <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
         The Assistant Registrar,</b>
    </p>
   <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >
        
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
        Supreme Court of India, New Delhi.</b>
    </p>
   
   <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
    
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
           
             
          <b ><?php echo $address_m; ?></b>,
            </font></b>
    </p>
    <?php } ?>
     <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
        <b> <font  style="font-size: 13pt"  face= "Times New Roman" >
           
             
         District- <b><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
    <?php
   }
   else  if($row[individual_multiple]==2)
   {
       echo $tot_records;
   }
   ?>
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
<p align="justify" style='margin: 5px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
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
    <div style="font-size: 13pt;margin-bottom: 10px" face= "Times New Roman" align="justify">(Appeal by Special Leave granted by this Court's Order dated the <b ><?php echo $listed_dt; ?></b> in Petition for
       <b ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b>
       of the <b ><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
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
                  ... <?php echo $text; ?>
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
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Sir,</font></b></p>  
    <?php
      $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
        ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
      In pursuance of Rule 10 of  Order XXII Supreme Court Rules 2013, I am directed to forward herewith 
      for your information and record a certified copy of the Petition for Special Leave to Appeal filed on 
      <b><?php echo $diary_no_rec_date; ?></b>, in the Supreme Court by the Appellant above-named and taken on record as Petition
      of Appeal pursuant to this Court's Order dated <b><?php echo $listed_dt; ?></b> granting Special Leave to Appeal to
      the appellant above named from the Judgment and Order of the High Court above mentioned and to say that 
      the case has been registered in this Court as <b> <?php echo $res_fil_det['casename'] ?> </b> No. <b><?php echo $case_range; ?></b> of <b><?php echo $reg_year; ?></b>. A certified copy of this
      Court's Record of Proceedings dated <b><?php echo $listed_dt; ?></b> containing the said order is enclosed herewith.
</font>
     </p>
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman" >
        The sole Respondent is represented by Mr. &nbsp;&nbsp;&nbsp;, Advocate at the Special Leave Petition stage.
        He/She is, therefore, being served directly with the notice under Rule 11 Order XXII, Supreme Court Rules, 2013.

    </font>
</p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 You may now as required under rule 11 Order XXII, Supreme Court Rules, 2013 cause the enclosed notice of 
 lodgement of petition of appeal to be served on respondent no. &nbsp;&nbsp;&nbsp; and transmit to this court a certificate
 as  to date or dates on which the said notice has been served.
             </font>
    </p>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 Regarding preparation of the Record, I am to state that in accordance with the provisions contained 
 in Rule 8, Order XX, SCR 2013, all Criminal Appeals will be heard on the paper books of the Special 
 Leave Petition filed in this Court and the paper books of the Court below (in English), if available 
 plus additional documents to be filed by the parties from the record of the case, if the paper books of the 
 Court below be not available. As such, no action need be taken at your end for preparation of Record but 
 you are required to send to this Court at an early date all available copies except one of the paper books 
 prepared for use in the High Court along with the entire Original Records of the High Court as well as the 
 Trial Court for reference of this Court, in English language. If the original record or any part thereof is in
vernacular language, <b>the translation be done at the registry of the High Court</b> and it shall then be
transmitted, duly translated in English language, to this Court. In terms of the order dated 20th November,2018,
passed by this Court in <u> Hari Om @ Hero versus State of Uttar Pradesh [Criminal Appeal No. 1256 of 2017] </u>
and the order dated 27th April, 2010, passed in <u>Pehtu Kanwar & ors Vs. State of Bihar (Now Jharkhand)
[Criminal Appeal No. 1257 of 2007]</u>, the entire original record be sent to this Court, duly translated, in
English language.</font>
    </p>
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman" >
            The scanned/digitized/photocopy of the original record of the High Court and the Trial Court,
            <b>in English language</b>, be transmitted,  <u>instead of the original,</u> and the digitally signed
            authenticated copy be uploaded on <b>"https://registry.sci.gov.in/hcor_upload/"</b>. The original
            shall not be weeded out during the pendency of the matter before this Court, till a communication
            regarding disposal of the matter is received from the Registry of this Court.
        </font>
</p>
    
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Yours faithfully,</font></b></p>
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
   ?>
 </font></p>

 <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 He is requested to take further steps for the prosecution of the appeal in accordance with the 
 procedure prescribed by <u  style="font-size: 13pt"  face= "Times New Roman" >S.C.R.</u> 2013.
             </font>
    </p>
 <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
</div>

