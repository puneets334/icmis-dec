
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

        <font><b style="font-size: 16px"><?php echo strtoupper($r_nature); ?> APPELLATE JURISDICTION</b></font>

    </p>


    <?php
    // $get_application_registration= get_application_registration($dairy_no);

    $get_application=get_last_listed_ia($dairy_no);
    $ia1=array();
    $ia1=explode(',',$get_application);

    $ia3='';
    $iadate='';
    ?>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <u><b><font style="font-size: 13pt" face= "Times New Roman">
                    <?php
                    $s_no=0;
                    foreach ($ia1 as $value) {
                        if ($value != '') {

                            //echo $value;
                            $ia2 = explode('/', $value);
                            $get_application_registration = get_last_listed_ia_info($ia2[0], $ia2[1]);
                            echo $ia2[2];
                            if($s_no > 0)
                            {
                                if($s_no>=1){
                                    $ia3=$ia3.' and  ';
                                    //$iadate=$iadate.' ,  ';
                                }

                                ?>
                                <div align="center" style="font-size: 13pt;margin-top: 5px;margin-bottom: 5px"
                                     face="Times New Roman">
                                    AND
                                </div>
                                <?php
                            }
                            ?>
                            <div align="center" style="font-size: 13pt" face="Times New Roman">
                                <?php
                                echo $get_application_registration[1] . '- ' . 'APPLICATION FOR ' . $get_application_registration[0];
                                $ia3=$ia3.$get_application_registration[0];
                                //$iadate=$iadate.$get_application_registration[2];
                                $iadate=$get_application_registration[2];
                                ?>
                            </div>
                            <?php

                            $s_no++;

                        }
                    }

                    /* $s_no=0;
                     for ($index2 = 0; $index2 < count($ia1); $index2++) {

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
                             echo $get_application_registration[$index2][1].'- '.'APPLICATION FOR '.$get_application_registration[$index2][0];

                             ?>
                         </div>
                         <?php

                         $s_no++;
                     } */


                    ?>
                </font></b></u></p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >
        <u><b><font style="font-size: 13pt" face= "Times New Roman">IN</font></b></u></p>

    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u><b><font style="font-size: 13pt" face= "Times New Roman" ><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;' >

        <u><b><font style="font-size: 13pt" face= "Times New Roman">
                    WITH PRAYER FOR INTERIM RELIEF
                </font></b></u>
    </p>
    <!--<p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt'>-->
    <?php

    $lower_court= lower_court($dairy_no);

    $get_last_listed_date= get_last_listed_date($dairy_no);
    //$get_last_listed_date=  get_notice_dt($dairy_no);
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

        <?php
    }
    ?>
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
                <td style="font-size: 13pt;width: 45%" face= "Times New Roman">
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
                    ... Respondent(s)
                </td>
            </tr>
        </table>

    </div>

    <p style="margin: 0px;padding: 0px 0px 0px 2px;"><b><font style="font-size: 13pt"  face= "Times New Roman">To,</font></b></p>
    <?php
    if($row['individual_multiple']==1)
    {

        $check_party="Select ind_dep from party where diary_no='$dairy_no' 
                    and pet_res='$row['pet_res']' and pflag='P' and sr_no='$row['sr_no']'";
        $check_party=  mysql_query($check_party) or die("Error: ".__LINE__.mysql_error());
        $res_check_party=  mysql_result($check_party, 0);
        if($res_check_party!='I')
        {
            $ind_org=1;
        }

        ?>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;" >

            <b> <font style="font-size: 13pt" face= "Times New Roman" >
                    <?php
                    echo $tw_sn_to; ?></font></b>,
        </p>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 40%;float: right;text-transform: uppercase;">
            <b> <font  style="font-size: 13pt" face= "Times New Roman">
                    <b style="font-size: 13pt"><?php echo $row['p_sno']; ?></b></font></b>
        </p>
        <?php if($address_m!='') { ?>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;text-transform: uppercase;" >
            <b> <font style="font-size: 13pt" face= "Times New Roman" >


                    <b style="font-size: 13pt" face= "Times New Roman"><?php echo $address_m; ?></b>,
                </font></b>
        </p>
    <?php } ?>
        <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;text-transform: uppercase;">
            <b> <font  style="font-size: 13pt" face= "Times New Roman">


                    District- <b style="font-size: 13pt" face= "Times New Roman"><?php echo $district_nm; ?>, <?php echo $state_nm;?></b></font></b>
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
        <font  style="font-size: 13pt" face= "Times New Roman">
            <?php
            $get_petitioner_advocate=get_petitioner_advocate($dairy_no);

            /* $doc_name='';
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
             $no_of_petitiner='';
             if($pno>1)
                 $no_of_petitiner='s';

             $get_application_registration_all= get_application_registration_all($dairy_no);
             $doc_names='';
             for ($index22 = 0; $index22 < count($get_application_registration_all); $index22++) {
                 if($doc_names=='')
                     $doc_names=$get_application_registration_all[$index22][0];
                 else
                     $doc_names=$doc_names.', '.$get_application_registration_all[$index22][0];
             } */
            ?>
            WHEREAS the applications for <?php if($ia3!='') { ?>   <b style="font-size: 13pt" face= "Times New Roman"><?php echo $ia3; ?></b><?php } ?>
            above mentioned filed in the Registry <b style="font-size: 13pt" face= "Times New Roman">on <?php echo  $iadate;?> by <?php echo $get_petitioner_advocate; ?> </b>, Advocate on behalf of the
            above mentioned petitioners. (Copy enclosed)
        </font>
    </p>

    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 20px 0px 20px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
            AND WHEREAS the said applications for <?php if($ia3!='') { ?>   <b style="font-size: 13pt" face= "Times New Roman"><?php echo $ia3; ?></b><?php } ?> being
            called on for Preliminary hearing before the Hon'ble Court on <b style="font-size: 13pt" face= "Times New Roman"><?php echo $listed_dt ?></b>,
            when  the Court was pleased to pass the following order:-
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
    <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;line-height: 1" align="justify">
        <b><font  style="font-size: 13pt" face= "Times New Roman" >
                "<?php
                // echo  utf8_encode($b_z);
                echo read_txt_file($fil_nm);
                ?>"
            </font></b>
    </p>
    <?php /*
    //     $get_last_listed_date_lst= get_last_listed_date($dairy_no);
    $get_last_listed_date_lst=last_listed_date($dairy_no);
    $listed_dt_lst=date('dS F, Y', strtotime($get_last_listed_date_lst[1])); */
    ?>

<!--


    <div style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt;">
            <?php /*
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
            } */
            ?>

            <?php
/*

            if(strtotime($get_last_listed_date_lst[1])!=  strtotime($get_last_listed_date))
            {
                if($get_last_listed_date_lst[1]!='')
                {
                    $fil_nm_lst= get_text_pdf($dairy_no,$get_last_listed_date_lst[1]);

                }
                ?>
                <p style="text-indent: 80px;padding: 0px 2px 0px 2px;margin: 5px 60px 0px 60px;" align="justify">
                    <b><font  style="font-size: 13pt;line-height: 1" face= "Times New Roman">
                            "<?php

                            echo read_txt_file($fil_nm_lst);
                            ?>"
                        </font></b>
                </p>
                <?php
            } */
            ?>
    </div>
-->
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
            NOW, THEREFORE, TAKE NOTICE that the applications above mentioned will be posted for hearing before this Court
            on the <?php echo $tentative_dt;?> and will be taken up by this Court on that day at 10.30 O'Clock in the forenoon
            or so soon thereafter as may be convenient to the Court when you may appear before this Court and show cause to
            the Court as to why the application mentioned above be not allowed as prayed for.

        </font>
    </p>

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
            You may file your affidavit in opposition to the application as provided under Rule 3 Order XL, S.C.R. 2013 <b><u>forthwith</u></b>.

        </font>
    </p>

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt" face= "Times New Roman">
            TAKE FURTHER NOTICE that in default of appearance the applications will be heard and determined in your absence.
        </font>
    </p>

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font style="font-size: 13pt" face= "Times New Roman" >
            Dated :<b style="font-size: 13pt" face= "Times New Roman"><?php echo date('dS F, Y'); ?></b>
        </font>
    </p>
    <p align="right" style="padding: 13pt 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face= "Times New Roman">ASSISTANT REGISTRAR</font></b></p>
    <p style="padding: 4px 0px 0px 2px;margin: 0px;" align="justify"><font style="font-size: 13pt"  face= "Times New Roman">
            Copy to :-
        </font></p>
    <?php
    if($row['individual_multiple']==1)
    {
    ?>
    <p style="text-indent: 40px;padding: 4px 0px 0px 2px;margin: 0px" align="justify"><font style="font-size: 13pt;text-transform: uppercase;" face= "Times New Roman" >
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
                                    <div style="font-size: 13pt" face= "Times New Roman"> <?php
                                        $ex_exp=  explode('~', $ex_explode[0]);
                                        echo $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));

                                        ?></div>
                                    <div style="font-size: 13pt" face= "Times New Roman"> <?php echo  ucwords(strtolower($ex_explode[2])); ?>, <?php echo  ucwords(strtolower($ex_explode[1])); ?></div>
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




    <p align="right" style="padding: 48px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt" face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
    <?php
    if($row['individual_multiple']==1 && $fn_del_type=='H')
    {
        ?>
        <p style="font-size: 13pt" face= "Times New Roman">
            (*Copies of dasti notice are enclosed herewith. You are requested to file affidavit of service forthwith.)
        </p>
    <?php } ?>
</div>

<?php
include("legal_aid.php");

?>

