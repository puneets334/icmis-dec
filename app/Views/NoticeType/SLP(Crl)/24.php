<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <!--    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px">SECTION <?php echo get_section($dairy_no); ?></font> </b>
   </p>-->
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <font><b style="font-size: 16px">CRIMINAL APPELLATE JURISDICTION</b></font>
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <font><b style="font-size: 13pt"  face= "Times New Roman" >******</b></font>
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <font><b style="font-size: 13pt"  face= "Times New Roman" >(Certificate to the Advocate appointed<br/> as Amicus Curiae at the cost of the State)
            </b></font>
    </p>
    <p align="center" style='margin: 20px;padding: 2px 0px 0px 0px;'>
        <font><b style="font-size: 13pt"  face= "Times New Roman" >(Under Rule 7(3) Order XXII, <br/>
                <i style="font-size: 13pt"  face= "Times New Roman" >Supreme Court Rules, 2013 as amended</i>)

            </b></font>
    </p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>
    <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt"  face= "Times New Roman">
    <?php
    $lower_court= lower_court($dairy_no);
    $get_last_listed_date= dispose_detail($dairy_no);
    $get_misc_re= get_misc_re($dairy_no);
    $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
    for ($index1 = 0; $index1 < count($lower_court); $index1++) {
    // $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
    // $agency_name=$lower_court[$index1][2];
    // $skey=$lower_court[$index1][3];
    // $lct_caseno=$lower_court[$index1][4];
    //  $lct_caseyear=$lower_court[$index1][5];
    $state_name=$lower_court[$index1][1];
    ?>
            <!--     <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ></b> <?php echo $agency_name;  ?></b><b style="font-size: 13pt"  face= "Times New Roman" > </b><b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b></div>
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
            <td style="font-size: 13pt"  face= "Times New Roman" >
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
<!-- <div align="center" style='margin-top: 10px;padding: 2px 0px 0px 0px;font-size: 13pt;" face= "Times New Roman" font-style: italic;text-decoration: underline'> -->
<div style="margin-top: 10px; padding: 2px 0 0 0; font-size: 13pt; font-family: 'Times New Roman'; font-style: italic; text-decoration: underline; text-align: center;">
    C  E  R  T  I  F  I  C  A  T  E
</div>

<p style="color: #000000;text-indent: 40px;padding: 40px 2px 0px 2px;margin: 40px 0px 0px 0px;clear: both" align="justify">
    <font  style="font-size: 13pt"  face= "Times New Roman" >
        CERTIFIED that <b style="font-size: 13pt"  face= "Times New Roman" >
            <?php
            $ex_tw_sn_to= explode('<br/>', $tw_sn_to);

            echo $ex_tw_sn_to[0] ?></b>, Advocate, was engaged as Amicus Curiae at the cost of the State
        in the <?php echo $res_fil_det['casename'] ?> above mentioned, which was dismissed on <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $listed_dt ?></b>.
        and that <b><i style="font-size: 13pt"  face= "Times New Roman" >Rs. 6,000/- (Rupees six thousand only)</i></b>  is payable to him/her as fees by the State of
        <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $state_name; ?></b>.
    </font>
</p>
<p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
    <font style="font-size: 13pt"  face= "Times New Roman"  >
        Dated :<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo date('dS F, Y'); ?></b>
    </font>
</p>


<p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" > Deputy Registrar </font></b></p>
<p style="margin: 20px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
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
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;">
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


</div>

