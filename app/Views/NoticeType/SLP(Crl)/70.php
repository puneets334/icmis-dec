<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">NOTICE TO THE RESPONDENT TO SHOW CAUSE</font> </b>
 
    </p>
     <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">[SCR, Order XXII]</font> </b>
 
    </p>

    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 13pt"  face= "Times New Roman" >CRIMINAL APPELLATE JURISDICTION</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data">PETITION FOR <?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
      <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u><b><font style="font-size: 13pt" face= "Times New Roman">WITH</font></b></u></p>
   <?php
    $get_application_registration= get_application_registration_all($dairy_no);
   ?>
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u><b><font style="font-size: 13pt" face= "Times New Roman">
        <?php
        $s_no=0;
        $total_application='';
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
            if($total_application=='')
                $total_application=$get_application_registration[$index2][0];
            else 
                $total_application=$total_application.', '.$get_application_registration[$index2][0];
            
           echo 'I.A. No. '. $get_application_registration[$index2][1].' - '.'APPLICATION FOR '.$get_application_registration[$index2][0];
           
           ?>
        </div>
        <?php
       
        $s_no++;
    }
    
   
    ?>
        </font></b></u></p>
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
               <!-- <td rowspan="2" style="vertical-align: middle;font-size: 13px;text-align: center">-->
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
                
                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 <?php
 $no_of_petitioner='';
 if($pno>1)
     $no_of_petitioner='s';
 ?>
      <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
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
     <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
       <?php
     
      $get_pet_adv= get_pet_adv($dairy_no);

       $get_pet_adv[0];
      
     $get_last_listed_date=  get_notice_dt($dairy_no);
       $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
       ?>
    WHEREAS the Appeal together with the Application for bail above mentioned (copies enclosed) 
    filed in this Registry by  <b style="font-size: 13pt"  face= "Times New Roman" ><?php
       $name='';
     
           echo $get_pet_adv[0].' '.$get_pet_adv[1];
      
           ?></b>, Advocate on behalf of the Petitioner above-named, was listed 
    for hearing before this Hon'ble Court on the 
    <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt ?></b> 
    when the Court was pleased to pass the 
    following Order:-    
  
</font>
     </p>
    <?php
    if($get_last_listed_date!='')
    {
          $fil_nm= get_text_pdf($dairy_no,$get_last_listed_date);
    }
    ?>
     
       <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px" align="justify">
         <b><font style="font-size: 12pt"  face= "Times New Roman"  >
           " <?php echo read_txt_file($fil_nm); ?>"
         </font></b>
    </p>
     <?php
//     $get_last_listed_date_lst= get_last_listed_date($dairy_no);
     $get_last_listed_date_lst=last_listed_date($dairy_no);
       $listed_dt_lst=date('dS F, Y', strtotime($get_last_listed_date_lst[1]));
    ?>
    <?php
    
//    echo $last_listed_date[0].'$$'.$get_last_listed_date_lst[1];
    if(strtotime($get_last_listed_date)!=strtotime($get_last_listed_date_lst[1]))
    {
    ?>
    <div style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 20px 0px;font-size: 13pt"  face= "Times New Roman"  align="justify">
        AND WHEREAS, the service of show cause notice could not be effected on unserved Respondent No. ..... and the matter above-mentioned was listed before  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_last_listed_date_lst[3]; ?></b>
        on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt_lst; ?></b>, When the following order was passed:-
    </div>
    <?php
      if($get_last_listed_date_lst[1]!='')
     {
        $fil_nm_lst= get_text_pdf($dairy_no,$get_last_listed_date_lst[1]);
     
     }
    ?>
    <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;" align="justify">
         <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
           "<?php
        
           echo read_txt_file($fil_nm_lst);
  ?>"
         </font></b>
    </p>
    <?php
    
    } ?>
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
          $get_application_registration= get_application_registration($dairy_no);
    $doc_name='';
    for ($index2 = 0; $index2 < count($get_application_registration); $index2++) {
        if($doc_name=='')
            $doc_name=$get_application_registration[$index2][0];
        else 
            $doc_name=$doc_name.', '.$get_application_registration[$index2][0];
    }
    
     if($conn_cases!='')
         {
             $con_nm='';
             $ex_conn_cases=  explode(',', $conn_cases);
             for ($index4 = 0; $index4 < count($ex_conn_cases); $index4++) {
               $get_registration_diary=get_registration_diary($ex_conn_cases[$index4]);
               if($get_registration_diary[0]!='')
               {
                 $get_casetype_code=get_casetype_code(substr($get_registration_diary[0],0,2));
                 if($con_nm=='')
                     $con_nm=$get_casetype_code.' '.substr($get_registration_diary[0],3).'/'.$get_registration_diary[1];
                 else 
                  $con_nm=$con_nm.', '.$get_casetype_code.' '.substr($get_registration_diary[0],3).'/'.$get_registration_diary[1];    
               }
               else 
               {
                   if($con_nm=='')
                       $con_nm=substr($ex_conn_cases[$index4],0,-4).'-'.substr($ex_conn_cases[$index4],-4); 
                   else 
                      $con_nm=$con_nm.', '.substr($ex_conn_cases[$index4],0,-4).'-'.substr($ex_conn_cases[$index4],-4);  
               }
             }
         }
       
    ?>
      NOW, THEREFORE, TAKE NOTICE that the above appeal together with the application for 
      <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $total_application ?></b> will 
      be posted for hearing before this Court <b><u style="font-size: 13pt">IN DUE COURSE</u></b> when you may appear before this Court 
      either-in-person or through an Advocate-on-Record of this Court duly appointed by you in that 
      behalf within thirty days from the date of service of the notice and show-cause to the Court as 
      to why appeal as prayed for be not admitted and bail be not confirmed.
	
   
         
        
             </font>
    </p>

      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo ' '. date('dS F, Y'); ?></b>
             </font>
    </p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
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
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
<p align="left" style="padding: 16px 2px 0px 0px;margin: 0px"><font style="font-size: 13pt"  face= "Times New Roman"  >Encl: As Above</font></p>
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
   *You are requested to file Vakalatnama/appearance on behalf of STATE OF 
       <?php 
         $lower_court= lower_court($dairy_no);
          for ($index1 = 0; $index1 < count($lower_court); $index1++) {
 
  
  $state_name=$lower_court[$index1][1];
   
 
           ?>
       
    <?php
         }
     
       echo $state_name; ?>
             </font>
    </p>
<p style="margin: 30px;padding: 0px 0px 0px 2px;" align="justify">
   <i><u><b><font style="font-size: 13pt"  face= "Times New Roman"  >Note:<?php echo $row['note']; ?></font></b></u> </i>
</p>
<div style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt"  face= "Times New Roman"  align="justify">
    [1] <div style="text-indent: 0px;margin-left: 10px;display: inline;font-size: 13pt"  face= "Times New Roman" >"LEGAL AID: Legal service of an advocate is provided by the Supreme Court Legal Services Committee and the Supreme Court Middle Income Group Legal Aid Society to eligible Litigants.
</div></div>
<p style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt;"  face= "Times New Roman" text-indent: 40px;font-size: 13pt"  face= "Times New Roman"  align="justify">
    For further information, please contact the Secretary, Supreme Court Legal Services Committee or the Member Secretary, Supreme Court Middle Income Group Legal Aid Society, 107-108, Lawyers' Chambers, R.K. Jain Block - Near Post Office, Supreme Court Compound, Tilak Marg, New Delhi-110001 (Tel Nos. 011-23116353,23116354 (Additional Building Complex) and 011-23381257 (Front Office)).
</p>

<div style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt"  face= "Times New Roman"  align="justify">
    [2] <div style="text-indent: 0px;margin-left: 10px;display: inline;font-size: 13pt"  face= "Times New Roman" >MEDIATION:	The facility of amicable settlement of disputes by trained mediators in cases pending in the Supreme Court is available in the Supreme Court.
</div></div>
<p style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt;"  face= "Times New Roman" text-indent: 40px;font-size: 13pt"  face= "Times New Roman"  align="justify">
    For further information, please contact the Co-ordinator, Supreme Court Mediation Center, 109, Lawyers' Chambers, R.K. Jain Block-Near Post Office, Supreme Court Compound, Tilak Marg, New Delhi-110001 (Tel No. 011-2307432).
</p>
</div>

