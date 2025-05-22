
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman" >SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
  <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman"   >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 13pt"  face= "Times New Roman" ><?php  $ct=get_casetype($dairy_no); if($ct==1 or $ct==2 ){ echo "EXTRA-ORDINARY APPELLATE JURISDICTION";}else{ echo "CIVIL APPELLATE JURISDICTION";}?></b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  ><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
   
     <!--<p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt'>-->
       <?php
    
     $lower_court= lower_court($dairy_no);
     
    $get_last_listed_date= get_last_listed_date($dairy_no);
//       $get_last_listed_date= '2015-07-03';
   $get_misc_re= get_misc_re($dairy_no);
     $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
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
                <td style="font-size: 13pt"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'].$pno; ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center">
                   VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right">
                   ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
             <tr>
                <td style="font-size: 13pt;text-align: left">
                    <?php echo $res_fil_det['res_name'].$rno; ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
 
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
        <font  style="font-size: 13pt"  face= "Times New Roman" >
          <?php
     $get_petitioner_advocate=get_petitioner_advocate($dairy_no);
    $get_application_registration= get_application_registration($dairy_no);
    $doc_name='';
    for ($index2 = 0; $index2 < count($get_application_registration); $index2++) {
        if($doc_name=='')
            $doc_name=$get_application_registration[$index2][0];
        else 
            $doc_name=$doc_name.', '.$get_application_registration[$index2][0];
    }
    $conn_cases='';
   $connected_cases= connected_cases($dairy_no);
   $cnt_con_case=0;
    for ($index3 = 0; $index3 < count($connected_cases); $index3++) {
        if($conn_cases=='')
            $conn_cases=$connected_cases[$index3][0];
        else 
            $conn_cases=$conn_cases.','.$connected_cases[$index3][0];
        $cnt_con_case++;
    }
        ?>
        
                  
	TAKE NOTICE that the Special Leave Petition above mentioned will be listed before the Hon'ble Court
        for hearing on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $tentative_dt;?></b> and will be taken up by 
        the Court on that date at 10.30'O Clock in the 
        forenoon or so soon thereafter as may be convenient to the Court.
</font>
     </p>
    
  
    
     
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
        
         
         You are hereby required to remain present before this Court at New Delhi on the 
         <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $tentative_dt;?></b>at 
         10.30 O'Clock in the forenoon.

             </font>
    </p>
     <p style="padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 1.&nbsp;&nbsp;&nbsp;&nbsp;	You will not be allowed to take Mobile phones, laptops or electronic gadgets inside the 
 court rooms. These articles should not be brought or own arrangement should be made for safe keeping, 
 for which Registry does not take responsibility.
             </font>
    </p>
       <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
   2.&nbsp;&nbsp;&nbsp;&nbsp;	Confirmation regarding Court in which the matter will be taken up for hearing and the Item 
   Number may be obtained from the Official Website of Supreme Court of India 
   (http://www.supremecourtofindia.nic.in). These details may be confirmed a day before or on the date
   of hearing.
             </font>
    </p>
    
      <p style="padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
             </font>
    </p>
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>
   
</div>

<?php
include("legal_aid.php");

?>

