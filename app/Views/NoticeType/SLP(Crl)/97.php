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
        <font  style="font-size: 13pt"  face= "Times New Roman"> <u><b> IN THE SUPREME COURT OF INDIA <BR> CRIMINAL APPELLATE JURISDICTION</b></u></font>
    </div>
    <div align="center" style="width: 100%;clear: both">
    <b><font  style="font-size: 13pt"  face= "Times New Roman">(Certificate to the Advocate appointed as Amicus Curiae at the cost of the State)</font></b><br>
    <b><font  style="font-size: 13pt"  face= "Times New Roman">(Under Rule 16, Order XX r/w Rule 11 of Order XVIII of the Supreme Court Rules 2013)</font></b>
    </div>





    <?php
    if($row['individual_multiple']==1)
    {
        ?>
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

        //echo $tot_records; ?>
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

    <?php
    $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));;
    ?>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="center"><U><b>
        <font  style="font-size: 13pt"  face= "Times New Roman">
            CERTIFICATE

        </font></b></U></p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            <?php $rs=dispose_detail($dairy_no); $dispose_dt=date('d-m-Y', strtotime($rs));?>
            Certified that, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Amicus Curiae) was engaged at the cost of the State in the above matter which was disposed off on <?php $rs=dispose_detail($dairy_no); echo $dispose_dt=date('d-m-Y', strtotime($rs));?>   and that Rs. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; are payable to him/her as his/her fee by the  <?php echo $res_fil_det['res_name'] ?>.

        </font></p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
           <b>Dated: <?php echo date('dS F, Y') ?></b>
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



