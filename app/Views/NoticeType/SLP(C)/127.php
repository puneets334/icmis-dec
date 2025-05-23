<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width:100%;font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px" align="center" >
        <u><i style="font-size: 13pt;"  face= "Times New Roman" >REGISTERED A.D.</i></u>
    </div>
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt;"  face= "Times New Roman" padding: 5px" border="1" >
       <?php echo get_text_msg();?>
     </div>
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
<b><i><u>Delivery Mode:
            <?php
            $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
            echo $mod;
            ?></u></i></b></br></br>
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
          echo date('dS F, Y')
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
    
 <?php 
    $db = \Config\Database::connect();
    $builder = $db->table('casetype');
    $row = $builder
        ->select('casecode')
        ->where('casename', $res_fil_det['casename'])
        ->get()
        ->getRow();

    $c_type = $row ? $row->casecode : null;
     // echo "case type is ".$c_type;

     $rs_ma=get_ma_info1($c_type, $case_range,$reg_year);
     //echo " the ma no is ".$rs_ma;

     $d_d=explode('-',$rs_ma);

     $dno_ma=$d_d[0];   // diary  no of ma
     $reg_ma=$d_d[1];  // registration no of ma
     $get_last_listed_date2= get_last_listed_date($dno_ma);
     $listed_dt2=date('dS F, Y', strtotime($get_last_listed_date2));

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
       <b> <font style="font-size: 13pt"  face= "Times New Roman"  >The Registrar,</b> 
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

               <b>(
                   <?php if($case_range!=''){?>

                   <?php  if($res_fil_det['short_description']!=''){echo $res_fil_det['short_description']; }
                   else echo "Diary No. ";
                   echo $case_range; ?> / <?php echo $reg_year;?>
               </b>
               <?php    }   ?> / <b><?php echo get_section($dairy_no);?> )</b>
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
 $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
 $agency_name=$lower_court[$index1][2];
 $skey=$lower_court[$index1][3];
 $lct_caseno=$lower_court[$index1][4];
  $lct_caseyear=$lower_court[$index1][5];
           ?>
     <div align="center" style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(
       <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>)</div>
       <?php
         
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
 
     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Sir,</font></b></p>  
  
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
          $last_listed_dts=date('dS F, Y', strtotime($get_last_listed_dates[1])); ?>
        
        In continuation of this Registry's letter of even number dated the
        <?php
        $get_notice_dt= get_date_by_remark($dairy_no, ['157']);
        if($get_notice_dt!='')
       $vac_date= date('dS F, Y', strtotime($get_notice_dt));
        else 
           $vac_date='......'; 
         ?>
<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $vac_date; ?></b>, I am directed to transmit herewith for necessary action a certified
copy of the Decree dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $last_listed_dts." / ".$listed_dt2; ?></b> of the Supreme Court in the said
appeal.</font></p>

      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
The Original record, if any, will follow.
             </font>
    </p>
      
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 Please acknowledge receipt.
             </font>
    </p>
<?php
$ucode = $_SESSION['dcmis_user_idd'];
$usertype=$_SESSION['dcmis_usertype'];
$section2=$_SESSION['dcmis_section'];

get_additional_reg($section2);

?>
    
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Yours faithfully,</font></b></p>
<br/><br/>
<p  align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(&nbsp;<?php echo get_additional_reg($section2);?>)</font></b></p>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ADDITIONAL REGISTRAR</font></b></p>

<p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
    Copy to :-
   </font></p>
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
<br/><br/>
<p  align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >(&nbsp;<?php echo get_additional_reg($section2);?>)</font></b></p>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ADDITIONAL REGISTRAR</font></b></p>
 
 <!--<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>-->
</div>

