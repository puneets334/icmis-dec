<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt;"  face= "Times New Roman" padding: 5px" border="1" >
    <?php echo get_text_msg();?>
</div>
<div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
<b><i><u>Delivery Mode:
            <?php
            $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
            echo $mod;
            ?></u></i></b></br></br>
D. No. <b style="font-size: 13pt"  face= "Times New Roman "><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
/SEC-<b style="font-size: 13pt"  face= "Times New Roman "><?php echo get_section($dairy_no); ?></b>
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
if($row['individual_multiple']==1)
{
    ?>
    <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
<?php } ?>
<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman " >From:</font></b></p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size: 13pt"  face= "Times New Roman " >
            The Assistant Registrar,</b>
</p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size: 13pt"  face= "Times New Roman " >
            Supreme Court of India, New Delhi.</b>
</p>

<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman " >To,</font></b></p>
<!--<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size: 13pt"  face= "Times New Roman " >
            The Registrar,</b>
</p> -->
<?php
if($row['individual_multiple']==1)
{
    ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

        <b> <font style="font-size: 13pt"  face= "Times New Roman " >
            <?php
            echo $tw_sn_to; ?></b>,
    </p>
    <?php if($address_m!='') { ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%" >
        <b> <font style="font-size: 13pt"  face= "Times New Roman " >


                <b style="font-size: 13pt"  face= "Times New Roman "><?php echo $address_m; ?></b>,
            </font></b>
    </p>
<?php } ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;margin-bottom: 10px">
        <b> <font  style="font-size: 13pt"  face= "Times New Roman ">


                District- <b style="font-size: 13pt"  face= "Times New Roman "><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
    </p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

        <b> <font style="font-size: 13pt"  face= "Times New Roman"  >
                (Process Id:<?php echo $row['process_id'] ?>/<?php echo $row['rec_dt']; ?>)</b>
        </font>
    </p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

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
<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both;margin-bottom: 20px"><b><font style="font-size: 13pt"  face= "Times New Roman " >(Ref: )</font></b></p>

<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size: 13pt"  face= "Times New Roman " id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

</p>
<p align="justify" style='margin: 0px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman '>
    <?php
    $get_application_registration= get_application_registration($dairy_no);
    $lower_court= lower_court($dairy_no);
    $get_last_listed_date= get_last_listed_date($dairy_no);
    $get_misc_re= get_misc_re($dairy_no);
    $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
    $get_tentative_date= get_tentative_date($dairy_no);
    $tentative_dt=date('dS F, Y', strtotime($get_tentative_date));
    for ($index1 = 0; $index1 < count($lower_court); $index1++) {
        $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
        $agency_name=$lower_court[$index1][2];
        $skey=$lower_court[$index1][3];
        $lct_caseno=$lower_court[$index1][4];
        $lct_caseyear=$lower_court[$index1][5];
        $name=$lower_court[$index1][1];
        ?>
        <?php

    }
    ?>
</p>
<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="center">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        WITH
    </font>
</p>
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
                        echo ' IA No.  '.$get_application_registration[$index2][1].'- '.'APPLICATION FOR '.$get_application_registration[$index2][0];

                        ?>
                    </div>
                    <?php

                    $s_no++;
                }
                ?>
            </font></b></u></p>

<div align="center" style="width: 100%;clear: both">
    <table cellpadding="10" cellspacing="10" style="width: 100%" >
        <tr>
            <td style="font-size: 13pt"  face= "Times New Roman ">
                <?php echo $res_fil_det['pet_name'] ?>
            </td>
            <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center" face= "Times New Roman">
                VERSUS
            </td>
            <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                ... Petitioner(s)/Appellant(s)
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

<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman " > Sir/Madam, </font></b></p>

<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        In continuation of this Registry's letter dated ... please find enclosed herewith copies of the petition and other documents as received. You make like to communicate with the Petitioner(s) to take instructions and assist him/them.
    </font>
</p>


<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        The Petition/Appeal, mentioned above, has been presented through Jail to this Court by the Petitioner(s)
        above named. As the Petitioner is/are undefended, you are appointed as Amicus Curiae at State expenses to argue
        the Petition on behalf of the Petitioner(s) and, thus, be of assistance to the Court.   </font>
</p>
<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        You are required to draft the petition, prepared five sets of Paper Books thereof. Out of the five sets
        of Paper Books, one set of Paper Books be given to this Branch for registration, <b><u>within a period of 15 days</u></b>
        from the date of receipt of the documents from the Registry. <b>In case you fail to submit the Paper Books within the
        stipulated period it will be presumed that you are not interested to represent the case. In that event, another Amicus Curiae
        will be appointed with orders of the Competent Authority.</b>
    </font>
</p>
<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        As soon as paper books are received, Registry shall take steps to process the matter for listing. The date of
        listing will also be informed to you.
    </font>
</p>
<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
        Take note that the petition/paper books should not be tendered at the Filing Counter and should be filed in the Section
        concerned with the Branch Officer.
    </font>
</p>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman " >Yours faithfully,</font></b><br><br></p>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman " >ASSISTANT REGISTRAR</font></b></p>

<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman ">
    Encl. : <br>
        1) Copy of Petition and other papers received from jail,<br>
        2) Five sets of impugned Judgment of the High Court,<br>
        3) Five sets of translated judgment of Trial Court,<br>
        <br>

    </font>
</p>

<p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman " >
        Copy to :-
    </font></p>
<?php
if($row['individual_multiple']==1)
{
?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman " >
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
                                <div style="font-size: 13pt"  face= "Times New Roman "> <?php
                                    $ex_exp=  explode('~', $ex_explode[0]);
                                    echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));

                                    ?></div>
                                <div style="font-size: 13pt"  face= "Times New Roman "> <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
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


<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman " >ASSISTANT REGISTRAR</font></b></p>
</div>



