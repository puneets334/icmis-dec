<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
<!--    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt" border="1" >
       All Communications Should be Addressed to Registrar by Designation and not by Name.
     </div>-->
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
    <div style="width: 100%;float: right;font-size: 13pt;text-align: center;font-weight:bold;" face="Times New Roman">
        SECTION - <b style="font-size: 13pt"  face= "Times New Roman"><?php echo get_section($dairy_no); ?></b><br><br>
        <div style="font-size: 13pt;font-weight:bold;" face="Times New Roman">
            IN THE SUPREME COURT OF INDIA
        </div>
          <div style="font-size: 13pt;font-weight:bold;" face="Times New Roman">
           <?php  $ct=get_casetype($dairy_no); if($ct==1 or $ct==2 ){ echo "EXTRA-ORDINARY APPELLATE JURISDICTION";}else{ echo "CIVIL APPELLATE JURISDICTION";}?>
        </div>
        <div style="font-size: 13pt;"  face= "Times New Roman">
           <?php 
//          echo date('dS F, Y')
           ?>
        </div>
     </div>

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


    <!--      <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >From:</font></b></p>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman" >
             The Assistant Registrar,</b>
        </p>
       <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman" >
            Supreme Court of India, New Delhi.</b>
        </p>

       <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >To,</font></b></p>

          <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

            <b> <font style="font-size: 13pt"  face= "Times New Roman" >
                <?php
    //              echo $tw_sn_to; ?></b>,
        </p>
        <?php // if($address_m!='') { ?>
         <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%" >
            <b> <font style="font-size: 13pt"  face= "Times New Roman" >


              <b style="font-size: 13pt"  face= "Times New Roman"><?php // echo $address_m; ?></b>,
                </font></b>
        </p>
        <?php // } ?>
         <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left">
            <b> <font  style="font-size: 13pt"  face= "Times New Roman">


             District- <b style="font-size: 13pt"  face= "Times New Roman"><?php // echo $district_nm; ?>, <?php // echo $state_nm;?></b></font></b>
        </p>-->

            <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u style="font-size: 13pt"  face= "Times New Roman"></u>
    </p>
   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>
         
    </p>
   
       <?php
     $lower_court= lower_court($dairy_no);
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
        <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
<u style="font-size: 13pt"  face= "Times New Roman">IN</u>
    </p>
     <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

    <u style="font-size: 13pt"  face= "Times New Roman"><b style="font-size: 13pt"  face= "Times New Roman"><?php echo $skey ?> </b> <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $lct_caseno; ?></b> OF <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $lct_caseyear; ?></b></u>
         
    </p>
       <?php
         
       }
       ?>
  
   
   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'] ?>
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
                    <?php echo $res_fil_det['res_name'] ?>
                </td>
                
                <td style="font-size: 13pt;text-align: right">
                   ... Respondent(s)
                </td>
            </tr>
        </table>
     
   </div>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >
    <b><font style="font-size: 13pt"  face= "Times New Roman">
            <u>OFFICE  REPORT ON LIMITATION</u>
</font></b>
    </p>
     <!--<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >Sir,</font></b></p>-->  
    <?php
      $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
        ?>
      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
        <?php
        $get_first_listed_date= get_first_listed_date($dairy_no);
  $first_listed_date=date('dS F, Y', strtotime($get_first_listed_date));
   $diary_rec_dt=$new_date = date('dS F, Y', strtotime(get_diary_rec_date($dairy_no)));
        ?>
The Review Petition is within time.
</font>
     </p>

      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman">
Dated <?php echo date('dS F, Y');?>.</font>
    </p>
      
    
   <!--<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >Yours faithfully,</font></b></p>-->
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >BRANCH OFFICER</font></b></p>
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
                  <td style="font-size: 13pt;vertical-align: top">
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

 
 <!--<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>-->
</div>


<?php
include("legal_aid.php");

?>
