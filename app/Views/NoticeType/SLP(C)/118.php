<?php
include('../includes/db_inc.php');

include("../extra/casetype_diary_no.php");
?>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
<!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
  <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>
 
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

           <font><b style="font-size: 16px">ORIGINAL JURISDICTION</b></font>

    </p>
    
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman"  ><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
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
     <!--<p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman'>-->





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
                <td style="font-size: 13pt;width: 45%"  face= "Times New Roman" >
                    <?php echo $res_fil_det['pet_name'].$pno; ?>
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
                
                    <?php echo $res_fil_det['res_name'].$rno; ?>
                </td>
                
               <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                    ... Respondent(s)/<br>Alleged Contemnor(s)
                </td>
            </tr>
        </table>
     
   </div>

    <?php

    $lower_court= lower_court($dairy_no);

    $get_last_listed_date= get_last_listed_date($dairy_no);
    //       $get_last_listed_date= '2015-07-03';
    $get_misc_re= get_misc_re($dairy_no);
    $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
    for ($index1 = 0; $index1 < count($lower_court); $index1++)
    {
        $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
        $agency_name=$lower_court[$index1][2];
        $skey=$lower_court[$index1][3];
        $lct_caseno=$lower_court[$index1][4];
        $lct_caseyear=$lower_court[$index1][5];

        $state_name=$lower_court[$index1][1];

        $get_tentative_date= get_tentative_date($dairy_no);
        $tentative_dt=date('dS F, Y', strtotime($get_tentative_date));

        $get_first_listed_date= get_first_listed_date($dairy_no);
        $first_listed_date=date('dS F, Y', strtotime($get_first_listed_date));

        //echo $judgement_dt;
        //
        //
        //   echo $get_last_listed_date;
        echo "<center>IN</center>";

        echo "<center><b>$skey.$lct_caseno/$lct_caseyear</b></center>";
        ?>
        <p>(Petition filed on the behalf of the  petitioner/respondent named above for initiating proceedings for contempt of this Court against the alleged contemnor named above for the willfull and deliberate disobedience of the Order of this Court dated <?php  echo $get_last_listed_date;?> in <?php  echo $skey.$lct_caseno."/".$lct_caseyear;  ?> entitled <?php // cause title ?>)


        </p>


        <?php
    }

    //echo "<center>IN</center>";
    $db = \Config\Database::connect();

    $builder = $db->table('casetype');
    $row = $builder->select('casecode')
                ->where('short_description', $skey)
                ->get()
                ->getRow();

    $casecode = $row ? $row->casecode : null;

    //echo "casecode is ".$casecode;

   // exit();
    $main_diary_no = get_diary_case_type($casecode, $lct_caseno, $lct_caseyear);


  //echo " the diary_no is ".$main_diary_no;

    //    code for getting lowercourt of contempt petition
   $lower_court1= lower_court($main_diary_no);

   $get_last_listed_date1= get_last_listed_date($dairy_no);
        //  $get_last_listed_date= '2015-07-03';
    $get_misc_re= get_misc_re($main_diary_no);
    $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
    for ($index1 = 0; $index1 < count($lower_court); $index1++)
    {
        $judgement_dt1=$new_date = date('dS F, Y', strtotime($lower_court1[$index1][0]));
        $agency_name1=$lower_court1[$index1][2];
        $skey1=$lower_court1[$index1][3];
        $lct_caseno1=$lower_court1[$index1][4];
        $lct_caseyear1=$lower_court1[$index1][5];


        $state_name1=$lower_court1[$index1][1];

        $get_tentative_date1= get_tentative_date($dairy_no);
        $tentative_dt1=date('dS F, Y', strtotime($get_tentative_date1));

        $last_listed_date1=date('dS F, Y', strtotime($get_last_listed_date1));

        $get_first_listed_date1= get_first_listed_date($dairy_no);
        $first_listed_date1=date('dS F, Y', strtotime($get_first_listed_date));

        //echo $judgement_dt;
        //
        //
        //   echo $get_last_listed_date;
        echo "<center>IN</center>";




        echo "<center><b>$skey1.$lct_caseno1/$lct_caseyear1</b></center>";
        ?>


        <?php
    }
    //



    echo "<center><b></b></center>";
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
     $get_petitioner_advocate=get_petitioner_advocate($dairy_no);
    $get_application_registration= get_application_registration($dairy_no);
   $get_application_registration_all= get_application_registration_all($dairy_no);
   $doc_names='';
     for ($index22 = 0; $index22 < count($get_application_registration_all); $index22++) {
        if($doc_names=='')
            $doc_names=$get_application_registration_all[$index22][0];
        else 
            $doc_names=$doc_names.', '.$get_application_registration_all[$index22][0];
    }
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
      The Miscelleneous Application in the Contempt Petition above mentioned was listed for hearing before this Court on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt ?></b> <?php if($cnt_con_case>0) { ?>  <?php } ?>, when  the Court was pleased to pass the following order:-
</font>
     </p>
    
   <?php
       $diary_no= substr($dairy_no,0,-4);  
             $diary_year= substr($dairy_no,-4); 
            
     if($get_last_listed_date!='')
     {
        $fil_nm= get_text_pdf($dairy_no,$get_last_listed_date);
     
     }
     ?>
      <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;line-height: 100%" align="justify">
         <b><font  style="font-size: 12pt;line-height: 100%"  face= "Times New Roman"  >"<?php echo read_txt_file($fil_nm);?>"
         </font></b>
    </p>
    
     
    
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
         <?php
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

    <p>

    AND WHEREAS your attendance is necessary to anwswer a charge of Contempt of this Court by willfully and deliberately disboeying the Hon'ble Court's Order dated <?php    echo "<b>$last_listed_date1</b>";?> in <?php  echo "<b>".$skey.$lct_caseno.'/'.$lct_caseyear."</b>"; ?>.

    </p>

    <p>
        You are hereby required to appear either in-person or through an Advocate-on-Record before this Court at New Delhi on the <?php echo $tentative_dt; ?> at 10:30 A.M. in the forenoon or so soon thereafter as may be convenient to the Hon'ble Court.


    </p>
    <p>
        You shall attend the Court either in-person or through an Advocate-on-Record  on the <?php echo $tentative_dt; ?> at 10:30 A.M. and shall continue to attend the Court on all days thereafter to which the case against you stands adjourned and until final orders are passed on the charge against you.

  <p>
        HEREIN FAIL NOT.<BR>



    </p>
             </font>
    </p>
    

     
      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font style="font-size: 13pt"  face= "Times New Roman"  >
   Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo ' '. date('dS F, Y'); ?></b>
             </font>
    </p>
   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
        </font>
    </p>
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



<p style="margin: 30px;padding: 0px 0px 0px 2px;" align="justify">
   <i><u><b><font style="font-size: 13pt"  face= "Times New Roman"  >Note:<?php echo $row['note']; ?></font></b></u> </i>
</p>
    <div style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt"  face= "Times New Roman"  align="justify">
        [1] <div style="text-indent: 0px;margin-left: 10px;display: inline;font-size: 13pt"  face= "Times New Roman" >Confirmation regarding Court in which the matter will be taken up for hearing and the Item number may be obtained from the Official Website of Supreme Court of India(http://www.sci.gov.in). These details may be confirmed a day before or on the date of hearing.
        </div></div>


    <div style="margin: 30px;padding: 0px 0px 0px 2px;font-size: 13pt"  face= "Times New Roman"  align="justify">
        [2] <div style="text-indent: 0px;margin-left: 10px;display: inline;font-size: 13pt"  face= "Times New Roman" >You will not be allowed to take Mobile Phones, laptops or electronic gadgets inside the court rooms. These articles should not be brought or own arrangement should be made for safe keeping, for which Registry does not take responsibility.
        </div></div>

<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ASSISTANT REGISTRAR</font></b></p>

 
</div>

