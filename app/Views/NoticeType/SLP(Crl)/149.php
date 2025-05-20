<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row[process_id],$row[rec_dt1]);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 13pt">SECTION <?php echo get_section($dairy_no); ?></font> </b>
    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b><u> <font style="font-size: 16px">BAILABLE WARRANT</font> </u></b>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 13pt"  face= "Times New Roman" ><?php  $ct=get_casetype($dairy_no); if($ct==1 or $ct==2 ){ echo "EXTRA-ORDINARY APPELLATE JURISDICTION";}else{ echo "CIVIL APPELLATE JURISDICTION";}?></b></font>

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


            <b>(Petition for initiating proceedings for contempt against the alleged Contemnors  named  below for non-compliance of this
                Hon'ble Court dated the <b style="font-size: 13pt" face= "Times New Roman" ><?php echo $judgement_dt; ?></b> passed  in
                <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $skey ?> </b> <b style="font-size: 13pt"  face= "Times New Roman"  ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>
                entitled "<b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['pet_name'].$pno;  ?></b> VERSUS <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;  ?>"</b></b>)
            <?php

        }
        ?>
    </p>

    <?php
    if($row[individual_multiple]==1)
    {
        ?>

        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->

    <?php } ?>
    <div align="center" style="width: 100%;clear: both">
        <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt;width: 45%"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
                </td>
                <!-- <td rowspan="2" style="vertical-align: middle;font-size: 13px;text-align: center">-->
                <td rowspan="2" style="vertical-align: middle;font-size: 13pt;text-align: center;width: 10%" face= "Times New Roman">
                    VERSUS
                </td>
                <td style="font-size: 13pt;text-align: right;width: 45%" face= "Times New Roman">
                    ... Petitioner(s)/Appellant(s)
                </td>
            </tr>
            <tr>
                <td style="font-size: 13pt;text-align: left" face= "Times New Roman">
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>

                <td style="font-size: 13pt;text-align: right" face= "Times New Roman" >
                    ... Alleged Contemnor/Respondent(s)
                </td>
            </tr>
        </table>

    </div>
    <?php
    $no_of_petitioner='';
    if($pno>1)
        $no_of_petitioner='s';
    ?>
    <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman"  >To,</font></b></p>
    <?php
    if($row[individual_multiple]==1)
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

                    <?php  if($res_fil_det[short_description]!=''){echo $res_fil_det[short_description]; }
                    else echo "Diary No. ";
                    echo $case_range; ?> / <?php echo $reg_year;?>
                </b>
                <?php    }   ?> / <b><?php echo get_section($dairy_no);?> )</b>
            </font>
        </p>
    <?php }
    else if($row[individual_multiple]==2)
    {
        echo $tot_records;
    }
    ?>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            <?php

            $get_pet_adv= get_pet_adv($dairy_no);

            $get_pet_adv[0];

            $get_last_listed_date=  get_notice_dt($dairy_no);
            $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
            ?>
            Whereas the <?php echo $res_fil_det['casename'] ?>
            above-mentioned was listed before the Hon'ble Court on
            <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt ?></b> when the Court was pleased to pass the following order:-
        </font>
    </p>
    <?php
    if($get_last_listed_date!='')
    {
        $fil_nm= get_text_pdf($dairy_no,$get_last_listed_date);
    }
    ?>

    <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px" align="justify">
        <b><font style="font-size: 12pt"  face= "Times New Roman"  >
                " <?php echo read_txt_file($fil_nm); ?>"
            </font></b>
    </p>
    <?php
    //     $get_last_listed_date_lst= get_last_listed_date($dairy_no);
    $get_last_listed_date_lst=last_listed_date($dairy_no);
    $listed_dt_lst=date('dS F, Y', strtotime($get_last_listed_date_lst[1]));
    ?>
    <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px" align="justify">
        <b><font style="font-size: 12pt"  face= "Times New Roman"  >
                [Copy of Record of Proceedings dated
                <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt ?></b> are enclosed herewith]
            </font></b>
    </p>
    <?php

    //    echo $last_listed_date[0].'$$'.$get_last_listed_date_lst[1];
    if(strtotime($get_last_listed_date)!=strtotime($get_last_listed_date_lst[1]))
    {
        ?>
        <div style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 20px 0px;font-size: 13pt"  face= "Times New Roman"  align="justify">
            WHEREAS, the <?php echo $res_fil_det['casename'] ?>
            above-mentioned was lastly listed before the Hon'ble Court on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt_lst; ?></b>, when the Court was pleased to pass the following order:-
        </div>
        <?php
        if($get_last_listed_date_lst[1]!='')
        {
            $fil_nm_lst= get_text_pdf($dairy_no,$get_last_listed_date_lst[1]);

        }
        ?>
        <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;" align="justify">
            <b><font  style="font-size: 13pt"  face= "Times New Roman"  >
                    "<?php

                    echo read_txt_file($fil_nm_lst);
                    ?>"
                </font></b>
        </p>
        <?php

    } ?>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            <?php
            $get_application_registration= get_application_registration($dairy_no);
            $doc_name='';
            for ($index2 = 0; $index2 < count($get_application_registration); $index2++) {
                if($doc_name=='')
                    $doc_name=$get_application_registration[$index2][0];
                else
                    $doc_name=$doc_name.', '.$get_application_registration[$index2][0];
            }

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
    <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px" align="justify">
        <b><font style="font-size: 12pt"  face= "Times New Roman"  >
                [Copy of Record of Proceedings dated
                <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt_lst; ?></b> are enclosed herewith]
                <br><br></font></b>
    </p>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 40px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            <?php

            $get_pet_adv= get_pet_adv($dairy_no);

            $get_pet_adv[0];

            $get_last_listed_date=  get_notice_dt($dairy_no);
            $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
            ?>
            AND WHEREAS this Hon'ble Court noticing that despite the orders dated
            <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt ?></b> passed by this Hon'ble Court against <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;  ?>....</b> to remain present in the Court in person on <b style="font-size: 13pt" face= "Times New Roman" ><?php echo $listed_dt_lst; ?></b> the said <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;?></b>, has not chosen to appear before this Court on the said date and the hon'ble Court, therefore, directed issuance of bailable warrants against the said <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;?>.</b>
        </font>
    </p>



    <p>
        NOW, THEREFORE, you are hereby directed to execute the aforesaid bailable warrants executed through Station House Officer of the Police Station concerned against the said <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;  ?></b> forthwith, ensuring his production before the Hon'ble Court on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $tentative_dt ?> at 10.30 O' Clock</b> in the forenoon and to continue so to attend until otherwise directed by this Court.
        </font>
    </p>
    <!--      <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
         <font  style="font-size: 13pt"  face= "Times New Roman" >
 NOW, THEREFORE, TAKE NOTICE that the above Petition<?php echo $no_of_petitioner; ?> with a prayer for interim relief will
 be posted for hearing before this Court on <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $last_listed_date[2];  ?></b> at 10:30 in the forenoon or so soon thereafter
 as may be convenient to the Court when you may appear before the Court either in person or through
 advocate on-record and show cause to the Court as to why Special Leave Petition and interim relief,
 as prayed for, be not granted and the resultant appeal be not allowed.
             </font>
    </p>-->
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            HEREIN FAIL NOT
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman" >
            If the said <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $res_fil_det['res_name'].$rno;?></b> shall give bail to the satisfaction of the S.H.O. of the Police Station concerned to appear before this Hon'ble Court on the <?php echo $tentative_dt ?> and to continue so to attend until otherwise directed by this COurt, he may be released.
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font style="font-size: 13pt"  face= "Times New Roman"  >
            Dated : <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo ' '. date('dS F, Y'); ?></b>
        </font>
    </p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >( Ramkumar Choubey )</font></b></p>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman"  >Registrar (J-1)</font></b><br><br></p>


</div>



<div style="page-break-before: always">
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b> <font style="font-size: 16px"  >IN THE SUPREME COURT OF INDIA</font> </b>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>

        <font><b style="font-size: 13pt"  face= "Times New Roman" >CIVIL APPELLATE JURISDICTION</b></font>

    </p>
    <p align="center" style='margin: 0px;padding: 2px 0px 0px 0px;'>
        <b><u> <font style="font-size: 16px">BAILABLE WARRANT</font> </u></b>

    </p>
       <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u><b><font style="font-size: 13pt"  face= "Times New Roman"  id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <u style="font-size: 13pt">IN</u>
    </p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u style="font-size: 13pt" face= "Times New Roman"><b style="font-size: 13pt" face= "Times New Roman"><?php echo $skey ?> </b> <b style="font-size: 13pt" face= "Times New Roman"><?php echo $lct_caseno; ?></b> OF <b style="font-size: 13pt" face= "Times New Roman"><?php echo $lct_caseyear; ?></b></u>

    </p>
    <?php
    for ($index1 = 0; $index1 < count($lower_court); $index1++) {
    $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
    $agency_name=$lower_court[$index1][2];
    $skey=$lower_court[$index1][3];
    $lct_caseno=$lower_court[$index1][4];
    $lct_caseyear=$lower_court[$index1][5];
    ?>
    <div style="font-size: 13pt;"  face= "Times New Roman" margin-bottom: 10px">(Appeal by Special Leave granted vide this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt; ?></b> in Petition for
    <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $get_misc_re[2] ?></b> against the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $judgement_dt; ?></b>
    of the <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
    <b style="font-size: 13pt"  face= "Times New Roman" > <?php echo $skey ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseno; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman" ><?php echo $lct_caseyear; ?></b>)</div>
<?php







}
?>
<div align="center" style="width: 100%;clear: both">
    <table cellpadding="10" cellspacing="10" style="width: 100%" >
        <tr>
            <td style="font-size: 13pt"  face= "Times New Roman" >
                <?php echo $res_fil_det['pet_name'] ?>
            </td>
            <td rowspan="2" style="vertical-align: middle;font-size: 13pt;"  face= "Times New Roman"  "text-align: center">
            VERSUS
            </td>
            <td style="font-size: 13pt;text-align: right"  face= "Times New Roman"  >
                ... Petitioner(s) <?php echo $text;?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 13pt;text-align: left"  face= "Times New Roman"  >
                <?php echo $res_fil_det['res_name'] ?>
            </td>

            <td style="font-size: 13pt;text-align: right"  face= "Times New Roman" >
                ... Alleged Contemnor/Respondent(s)
            </td>
        </tr>
    </table>

</div>
<p align="left" style='margin: 0px;padding: 2px 0px 0px 0px;'>
    <b> <font style="font-size: 16px">
            ORDER DIRECTING ISSUE OF BAILABLE WARRANTS <?php
            $get_dismissal_type= get_dismissal_type($dairy_no);
            echo strtoupper( $get_dismissal_type[1]);
            ?></font> </b>

</p>
<p align="left" style='margin: 0px;padding: 2px 0px 0px 0px;'>
    <u><b> <font style="font-size: 13pt">
                Dated this the <font style="font-size: 13pt"  face= "Times New Roman" ><?php echo $listed_dt_lst; ?></font>
            </font> </b></u>
</p>



<div style="margin-top: 190px;margin-left: 390px">
    <?php
    if($row[individual_multiple]==1)
    {
    ?>
    <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman"  >
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
            else  if($row[individual_multiple]==2)
            {

                if($tot_copy_send_to_adv!='')
                {
                    $c_sno=1;
                    $tot_copy='';
                    $ex_c_s_t=explode('@',$tot_copy_send_to_adv);
                    $tot_copy=$tot_copy.'<div style="margin-left: 30px"><table>';
                    for ($index = 0; $index < count($ex_c_s_t); $index++) {
                        $ex_explode=explode('!',$ex_c_s_t[$index]);
                        $tot_copy=$tot_copy.'<tr>
                  <td style="font-size: 13pt;vertical-align: top">'.$c_sno;
                        $tot_copy=$tot_copy.'</td>
                    <td >
                        <div style="font-size: 13pt; " face="Times New Roman" >';
                        $ex_exp=  explode('~', $ex_explode[0]);
                        $tot_copy=$tot_copy. $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2])).$ex_explode[3];
                        $tot_copy=$tot_copy.'</div>
                        <div style="font-size: 13pt; " face="Times New Roman" >'.ucwords(strtolower($ex_explode[2])).', '.ucwords(strtolower($ex_explode[1])).'</div></td>
              </tr>';
                        $c_sno++;

                    }  $tot_copy=$tot_copy.'</table></div>';

                }
                echo $tot_copy;
            }
            ?>
            <div align="left" style="margin-top: 20px">
                <table width='50%' cellpadding='5' cellspacing='5'>
                    <tr>
                        <td style="font-size: 13pt;"  face= "Times New Roman" width: 30%">
                        For the Petitioner(s)<?php echo $text;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td style="font-size: 13pt"  face= "Times New Roman" >
                            <?php
                            $tot_petitioner_adv= tot_petitioner_adv($dairy_no);
                            $total_pet='';
                            for ($index3 = 0; $index3 < count($tot_petitioner_adv); $index3++) {
                                if ($total_pet == '')
                                    $total_pet = $tot_petitioner_adv[$index3][0];
                                else
                                    $total_pet = $total_pet . '<br/>' . $tot_petitioner_adv[$index3][0] . $tot_petitioner_adv[$index3][1];
                            }
                            echo $total_pet;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 13pt"  face= "Times New Roman" >
                            For the Respondent(s)
                        </td>
                        <td>
                            :
                        </td>
                        <td style="font-size: 13pt"  face= "Times New Roman" >
                            <?php
                            $tot_petitioner_adv= tot_respondent_adv($dairy_no);
                            $total_res='';
                            for ($index3 = 0; $index3 < count($tot_petitioner_adv); $index3++) {
                                if($total_res=='')
                                    $total_res=$tot_petitioner_adv[$index3][0];
                                else
                                    $total_res=$total_res.'<br/>'.$tot_petitioner_adv[$index3][0];

                            }
                            echo $total_res;
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
</div>
</div>

