<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt" border="1" >
        <?php echo get_text_msg();?>
    </div>
    <div style="width: 40%;float: right;font-size: 13pt;text-align: center">
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b></br></br>
        D. No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo substr($dairy_no,0,-4).'/'.  substr($dairy_no,-4); ?></b>
        /SEC-<b style="font-size: 13pt"  face= "Times New Roman"><?php echo get_section($dairy_no); ?></b>
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


    <div align="center" style="width: 100%;clear: both">
    <font  style="font-size: 13pt"  face= "Times New Roman"> <u><b>REGISTERED A.D. INSURED PARCEL FOR RS. 1000/-</b></u></font>
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


                    <font style="font-size: 13pt"  face= "Times New Roman"><?php echo $address_m; ?>,
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
    <!--   <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >
        <b><font style="font-size: 13pt"  face= "Times New Roman">
                <u>PETITION FOR SPECIAL LEAVE PETITION (CIVIL) NO.________ of 20_______</u>
    </font></b>
        </p>-->
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <u style="font-size: 13pt"  face= "Times New Roman"></u>
    </p>
    <p style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' align="center"  >
        <?php $a=get_misc_re($dairy_no);
        $castype=$a[3];
        //$casno=trim($a[1],'0');
        $casno=$a[1];
        // $splitno=split('-',$casno);
        $splitno = explode('-',$casno);

        if($splitno[0]==$splitno[1])
            $casno=ltrim($splitno[0],'0');
        else
            $casno=ltrim($a[1],'0');
        $casyear=$a[2];

        ?> <br>
        <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u><br>
        <font style="font-size: 13pt"  face= "Times New Roman" id="append_data">(Arising out of <?php echo $castype; ?> No. <?php echo $casno; ?> OF <?php echo $casyear; ?>)</font>
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

    <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><font style="font-size: 13pt"  face= "Times New Roman" >Sir,</font></p>
    <?php
    $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
    ?>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            I am directed to return herewith the entire Original Records of the High Court & Trial Court forwarded to this Court vide your letter No. &nbsp;&nbsp;&nbsp;&nbsp; dated &nbsp;&nbsp;&nbsp;&nbsp; (Copy enclosed) in the above mentioned matter.<br>

        </font></p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            You are, therefore, requested to get the records, on its receipt, opened, and checked in the presence of an Officer not below the rank of an Assistant Registrar of the Trial Court/ Concerned Court and to acknowledge receipt of the Original Records within three days of receipt of the Record.<br>
        </font>
    </p>




    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >Yours faithfully,</font></b></p><br><br><br>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman" >
            Copy to :-
        </font></p>
    <?php
    if($row['individual_multiple']==1)
    {
    ?>
    <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman" >
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

    <br><br>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
</div>



