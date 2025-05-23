
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

        <font><b style="font-size: 13pt"  face= "Times New Roman" >ORIGINAL JURISDICTION</b></font>

    </p>

    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>
    <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt'  face= "Times New Roman"  >
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


            (Petition filed on behalf of the petitioner named below for initiating  proceedings  for contempt of
            this  Court  against alleged  contemnors  named  below for willful and deliberate disobedience  of
            the order  of  this Court dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b> passed  in
            <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $skey ?> </b> <b style="font-size: 13pt"  face= "Times New Roman"  ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>
            entitled  <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['pet_name'].$pno;  ?></b> VERSUS <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;  ?></b>)
            <?php

        }
        ?>
    </p>
    <?php
    if($row['individual_multiple']==1)
    {
        ?>

        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->

    <?php } ?>
    <div align="center" style="width: 100%;clear: both">
        <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt;width: 45%" face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%">
                    VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right;width: 45%"  face= "Times New Roman"  >
                    ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
            <tr>
                <td style="font-size: 13pt;text-align: left"  face= "Times New Roman" >
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>

                <td style="font-size: 13pt;text-align: right"  face= "Times New Roman" >
                    ... Alleged Contemnor(s)
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
            <b> <font style="font-size: 13pt"  face= "Times New Roman" >


                    <b style="font-size: 13pt"><?php echo $address_m; ?></b>,
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
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            WHEREAS the Contempt Petition above mentioned filed in the Registry by <b><?php echo get_petitioner_advocate($dairy_no);?></b>, Advocate
            on behalf of the above mentioned petitioner(Copy enclosed) was listed before this Court on
            <b style="font-size: 13pt" face= "Times New Roman"><?php echo  $listed_dt; ?></b> when the Court was pleased to pass the following order :-
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
        <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
                "<?php
                // echo  utf8_encode($b_z);
                echo read_txt_file($fil_nm);
                ?>"
            </font></b>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            (A Certified copy of the Order dated <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> is enclosed herewith).
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            WHEREAS your attendance is necessary to answer a charge of Contempt of Court in the matter.

        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            You  are  hereby required to appear either in-person or through an Advocate on Record  before this Court
            at New Delhi on the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $tentative_dt; ?></b>  at 10.30 A.M.
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            You  shall  attend  the Court either in-person or through an Advocate on Record on
            the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $tentative_dt; ?> , at 10:30 A.M.</b> and shall continue to attend the Court on all days
            thereafter to which  the case against  you stands adjourned and until final orders are passed on
            the charge against you.
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            HEREIN FAIL NOT.
        </font>
    </p>

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font style="font-size: 13pt" face= "Times New Roman" >
            Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
        </font>
    </p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ADDITIONAL/DEPUTY REGISTRAR</font></b></p>
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
</div>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >ADDITIONAL/DEPUTY REGISTRAR</font></b></p>
<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px;margin-top: 20px" width="100%">
    <p align="left" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >Note :</font> </b>

    </p>

    <p style="padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            (1) Confirmation regarding Court in which the matter will be taken up for hearing and the Item
            number may be obtained from the official website of Supreme Court of India
            <span style="color: blue"><u>(http://www.sci.gov.in)</u></span>. These details bay be confirmed a day before or
            on the date of hearing.
        </font>
    </p>

    <p style="padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            (2) You will not be allowed to take Mobile phones, laptops or electronic gadgets inside the court
            rooms. These articles should not be brought or own arrangement should be made for safe
            keeping, for which Registry does not take responsibility.
        </font>
    </p>

</div>