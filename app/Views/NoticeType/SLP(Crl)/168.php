<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 11/7/24
 * Time: 11:51 AM
 */
?>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%" >

    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt;padding: 5px" border="1" >
        <?php echo get_text_msg();?>
    </div>
    <div style="width: 40%;float: right;font-size: 13pt;text-align: center">
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b></br></br>
        D. No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>

        <div style="font-size: 13pt;"  face= "Times New Roman">
            SEC-<b style="font-size: 13pt"  face= "Times New Roman"><?php echo get_section($dairy_no); ?></b>
        </div>
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
                The Assistant Registrar,</b>
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


       <?php
     $lower_court= lower_court_conct($dairy_no);
     $get_last_listed_dates= last_listed_date($dairy_no);
     $get_misc_re= get_misc_re($dairy_no);
echo    $get_application_registration= get_application_registration($dairy_no);
   ?>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <u><b><font style="font-size: 13pt" face= "Times New Roman">
                    <?php
                    $s_no=0;
                    for ($index2 = 0; $index2 < count($get_application_registration); $index2++) {
                      // echo $get_application_registration[$index2][1];
                         //echo $get_application_registration[$index2][1];
                       if($get_application_registration[$index2][2]==99)
                       {
                            ?>
                            <div align="center" style="font-size: 13pt;margin-top: 5px;margin-bottom: 5px" face= "Times New Roman">
                                <?php
                                echo "I.A. No. ".$get_application_registration[$index2][1].'<br>'.'(APPLICATION FOR '.$get_application_registration[$index2][0].")";

                                ?>
                            </div>
                            <?php
                        }
                        ?>

                        <?php

                        $s_no++;
                    }
                    ?>
                </font></b></u></p>

    <p align="center" style='margin: 0px;padding: 0px 0px 0px 0px;' >
        <u><b><font style="font-size: 13pt" face= "Times New Roman">IN</font></b></u></p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

        <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>

   <div align="center" style="width: 100%;clear: both">
       <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
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
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>

                <td style="font-size: 13pt;text-align: right">
                   ... Respondent(s)
                </td>
            </tr>
        </table>

   </div>

     <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >Sir,</font></b></p>

      <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
        <?php
          $last_listed_dts=date('dS F, Y', strtotime($get_last_listed_dates[1])); ?>
      I am directed to forward herewith a certified copy of the this Court's Record of Proceedings dated
         <b style="font-size: 13pt"  face= "Times New Roman">
             <?php echo $last_listed_dts ?></b> alongwith memo of parties, made in above mentioned matter, for your information and necessary action.
        </font>
     </p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
           *You are requested to kindly communicate this Order to the concerned authorities.
        </font>
    </p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            **You are further requested to furnish information/report as to whether such accused person/petitioner/appellant has surrendered within the period
            stipulated by this Hon'ble Court. You may engage the services of the District Legal Services Authority and/or the counsel deputed to attend the jail
            for the purpose of furnishing such information.
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman">
        Please acknowledge the receipt.
         </font>
    </p>


   <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >Yours faithfully,</font></b></p>
    <p align="right" style="padding: 40px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>

</div>
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
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>

 <!--<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>-->



