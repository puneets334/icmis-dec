<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt;"  face= "Times New Roman"padding: 5px" border="1" >
    <?php echo get_text_msg();?>
</div>
<div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"text-align: center">
<b><i><u>Delivery Mode:
            <?php
            $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
            echo $mod;
            ?></u></i></b></br></br>
D. No. <b style="font-size:13pt"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
/SEC-<b style="font-size:13pt"><?php echo get_section($dairy_no); ?></b>
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
<br>

<p align="center" style="color: #000000;margin: 0px;padding: 70px 0px;width: 50%;" >

    <b> <font style="font-size:13pt" >
            <U>  SPEED-POST </U></b>
</p>

<?php
if($row['individual_multiple']==1)
{
    ?>

    <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
<?php } ?>



<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size:13pt" >From:</font></b></p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size:13pt" >
            The Assistant Registrar,</b>
</p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size:13pt" >
            Supreme Court of India, New Delhi.</b>
</p>

<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size:13pt" >To,</font></b></p>
<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

    <b> <font style="font-size:13pt" >
            The Registrar,</b>
</p>
<?php
if($row['individual_multiple']==1)
{
    ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >

        <b> <font style="font-size:13pt" >
            <?php
            echo $tw_sn_to; ?></b>,
    </p>
    <?php if($address_m!='') { ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
        <b> <font style="font-size:13pt" >


                <b style="font-size:13pt"><?php echo $address_m; ?></b>,
            </font></b>
    </p>
<?php } ?>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;margin-bottom: 10px;text-transform: uppercase;">
        <b> <font  style="font-size:13pt">


                District- <b style="font-size:13pt"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
<p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

    <u><b><font style="font-size:13pt" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

</p>
<p align="justify" style='margin: 0px;padding: 2px 0px 0px 0px;font-size:13pt'>
    <?php
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
    <div style="margin-bottom: 10px;font-size:13pt">(From the Judgment and Order dated the <b style="font-size:13pt"><?php echo $judgement_dt; ?></b>
        of the <b style="font-size:13pt"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
        <b style="font-size:13pt"> <?php echo $skey ?> </b> No. <b style="font-size:13pt"><?php echo $lct_caseno; ?></b> of <b style="font-size:13pt"><?php echo $lct_caseyear; ?></b>)</div>
<?php

}
?>
</p>

<div align="center" style="width: 100%;clear: both">
    <table cellpadding="10" cellspacing="10" style="width: 100%" >
        <tr>
            <td style="font-size:13pt">
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
<p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size:13pt" >Sir,</font></b></p>

<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size:13pt">
        This is with reference to the petition of above mentioned petitioner received in this Registry from Superintendent,.................... In order
        to process the aforesaid jail petition, following documents are required, urgently:-<br>

        <ol>
            <li>
                Six identical copies (including one Certified copy) of judgment and order dated <?php
                for ($index1 = 0; $index1 < count($lower_court); $index1++) {
                    $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
                    $agency_name=$lower_court[$index1][2];
                    $skey=$lower_court[$index1][3];
                    $lct_caseno=$lower_court[$index1][4];
                    $lct_caseyear=$lower_court[$index1][5];
                    $name=$lower_court[$index1][1];
                    ?>
                    <?php echo $judgement_dt; ?></b>
                        of the <b style="font-size:13pt"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
                        <b style="font-size:13pt"> <?php echo $skey ?> </b> No. <b style="font-size:13pt"><?php echo $lct_caseno; ?></b> of <b style="font-size:13pt"><?php echo $lct_caseyear; ?></b>
                    <?php

                }
                ?>
                . <b><u>(One Sided Print Only)</u></b>
            </li>
            <li>
                Six identical copies (including one Certified copy) of Judgment and order dated .................
                . <b><u>(One Sided Print Only)</u></b>
            </li>

        </ol>
    </font>
</p>


<p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size:13pt">
        The copies, should be <b><u>Identical and Legible with neat print</u></b> and should be free from any kind of defect, along-with
        <b><u> English Translation, if judgment is vernacular.</u></b>
    </font>
</p>

<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size:13pt" >Yours faithfully,</font></b></p>
<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size:13pt" >ASSISTANT REGISTRAR</font></b></p>




<p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size:13pt;text-transform: uppercase;" >
        Copy to :-
    </font></p>
<?php
if($row['individual_multiple']==1)
{
?>
<p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size:13pt" >
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
                                <div style="font-size:13pt"> <?php
                                    $ex_exp=  explode('~', $ex_explode[0]);
                                    echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));

                                    ?></div>
                                <div style="font-size:13pt"> <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
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


<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size:13pt" >ASSISTANT REGISTRAR</font></b></p>
</div>

<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 25/5/18
 * Time: 3:14 PM
 */