<div  style="padding-left: 2px;padding-right: 2px;margin-left: 48px" width="100%">
    <div style="width: 40%;float: right;font-size: 13pt;"  face= "Times New Roman"  >
        <b><i><u>Delivery Mode:
                    <?php
                    $mod= get_delivery_mod($row['process_id'],$row['rec_dt1']);
                    echo $mod;
                    ?></u></i></b>
    </div></br></br>
    <div style="width: 49%;border-collapse: collapse;border: 1px solid black;float: left;font-size: 13pt" border="1" >
        All Communications Should be Addressed to Registrar by Designation and not by Name.
    </div>
    <div style="width: 49%;float: right;font-size: 13pt;text-align: center">
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





    <?php
    if($row['individual_multiple']==1)
    {
        ?>
        <!--<p align="left" style="margin: 0px;padding: 0px 0px 0px 2px;width: 50%;float: left" ><b><font  style="font-size: 13pt"  face= "Times New Roman" >Process Id: <?php //echo $row['process_id'] ?>/<?php //echo $row['rec_dt']; ?>(<?php //echo 'Sec '. get_section($dairy_no); ?>)</font></b></p> -->
    <?php } ?>
    <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >From:</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
                The Registrar,</b>
    </p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
                Supreme Court of India, New Delhi.</b>
    </p>

    <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >To,</font></b></p>
    <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;" >

        <b> <font style="font-size: 13pt"  face= "Times New Roman" >
                The Registrar,</b>
    </p>
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
    <p align="center" style='margin: 0px;padding: 10px 0px 0px 0px;clear: both' >

        <u><b><font style="font-size: 13pt"  face= "Times New Roman" id="append_data"><?php echo $res_fil_det['casename'] ?> <?php if(strpos($case_range, '-')==true) echo 'No(s)'; else echo 'No.';?> <?php echo $case_range; ?> OF <?php echo $reg_year; ?></font></b></u>

    </p>
    <p align="justify" style='margin: 10px;padding: 2px 0px 0px 0px;font-size: 13pt' face= "Times New Roman">
        <?php
        $lower_court= lower_court_conct($dairy_no);
        $get_last_listed_date= get_last_listed_date($dairy_no);
        $get_misc_re= get_misc_re($dairy_no);
        $listed_dt=date('dS F, Y', strtotime($get_last_listed_date));
        for ($index1 = 0; $index1 < count($lower_court); $index1++) {
        $judgement_dt=$new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
        $agency_name=$lower_court[$index1][2];
        $skey=$lower_court[$index1][3];
        $lct_caseno=$lower_court[$index1][4];
        $lct_caseyear=$lower_court[$index1][5];
        $get_order_date= get_order_date($dairy_no);
        ?>
    <div style="font-size: 13pt;margin-bottom: 10px">(Appeal by Special Leave granted by this Court's Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo date('dS F, Y', strtotime($get_order_date[0])); ?></b> in Petition for
        <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[0] ?></b> <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[1] ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $get_misc_re[2] ?></b> from the Judgment and Order dated the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $judgement_dt; ?></b>
        of the <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $agency_name;  ?></b>, <?php echo $lower_court[$index1][1] ?> in
        <?php
        $ex_skey=  explode(',',$skey );
        $ex_lct_caseno=explode(',',$lct_caseno );
        $ex_lct_caseyear=explode(',',$lct_caseyear );
        for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
            if($index2>0){ echo ',';}
            ?>
            <b style="font-size: 13pt"  face= "Times New Roman"> <?php echo $ex_skey[$index2] ?> </b> No. <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseno[$index2]; ?></b> of <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $ex_lct_caseyear[$index2]; ?> </b> <?php  }?>)</div>
<?php

}
?>
    </p>

    <div align="center" style="width: 100%;clear: both">
        <table cellpadding="10" cellspacing="10" style="width: 100%" >
            <tr>
                <td style="font-size: 13pt;width: 45%"  face= "Times New Roman">
                    <?php echo $res_fil_det['pet_name'].$pno ?>
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
                    <?php echo $res_fil_det['res_name'].$rno ?>
                </td>

                <td style="font-size: 13pt;text-align: right" face= "Times New Roman">
                    ... Respondent(s)
                </td>
            </tr>
        </table>

    </div>

    <p style="margin: 0px;padding: 0px 0px 0px 2px;clear: both"><b><font style="font-size: 13pt"  face= "Times New Roman" >Sir,</font></b></p>
    <?php
    $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));
    ?>
    <?php
    $diary_no_rec_date=date('dS F, Y', strtotime($res_fil_det['diary_no_rec_date']));
    $get_date_by_remark= get_date_by_remark($dairy_no,'1,41,176,177,178');
    if($get_date_by_remark!='')
        $remark_dt=date('dS F, Y', strtotime($get_date_by_remark));
    $get_order_connected= get_order_connected($dairy_no,$remark_dt,'72');
    ?>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            I am  to  forward  herewith  for  your  information and record a certified copy
            of the Petition for Special Leave to Appeal filed by the Appellant above named
            in this Registry and taken on  record as Petition of Appeal pursuant to this Court's
            Order dated <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $remark_dt; ?></b> (A certified copy of this Court's relevant record of proceeding
            is enclosed) granting Special Leave to Appeal to the Appellant above named and to state that the
            case above mentioned has been registered as <b style="font-size: 13pt"  face= "Times New Roman">
                <?php echo $res_fil_det['casename'] ?> </b> No.
            <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $case_range; ?></b> of
            <b style="font-size: 13pt"  face= "Times New Roman"><?php echo $reg_year; ?></b>
            <?php if($get_order_connected!='') { ?> and tagged with  <?php echo $get_order_connected; }  ?>.
        </font>
    </p>
    <?php
    $cnt_res=0;
    $get_respondents= get_respondents($dairy_no);
    for ($index3 = 0; $index3 < count($get_respondents); $index3++) {
        $cnt_res++;
    }
    ?>
    <p style="color: #000000;text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            There are <?php echo $cnt_res; ?> respondent<?php if($cnt_res>1) { ?>(s)<?php } ?> in this/these appeal
            <?php
            $represented_adv=represented_adv($dairy_no,$row['order_dt']);

            for ($index4 = 0; $index4 < count($represented_adv); $index4++) {
                $res_name='';
                $get_parties_frm_adv=  get_parties_frm_adv($dairy_no,$represented_adv[$index4][1]);
                $chk_pet='';
                for ($index5 = 0; $index5 < count($get_parties_frm_adv); $index5++) {

                    $get_parties_frm_adv[$index5][1];

                    if($index5>0)
                    {
                        $res_name=$res_name.", ";
                    }
//          $party_name=$get_parties_frm_adv[$index5][0];
//          $ex_party_name=explode(',',$party_name);
//          $total_party='';

//          $tot_respondents=0;
////          for ($index5 = 0; $index5 < count($ex_party_name); $index5++) {
//              if($index5!=0)
//                $res_name=$res_name.', ';
//             
//              $in_exp=  explode('-', $ex_party_name[$index5]);
                    if($chk_pet!=$get_parties_frm_adv[$index5][0])
                    {
                        if($get_parties_frm_adv[$index5][0]=='P')
                            $res_name=$res_name." Petitioner No. ";
                        $chk_pet='P';
                    }
                    if($chk_pet!=$get_parties_frm_adv[$index5][0])
                    {
                        if($get_parties_frm_adv[$index5][0]=='R')
                            $res_name=$res_name." Respondent No. ";
                        $chk_pet='R';
                    }
//          $res_name=$res_name.' '.
                    $res_name=$res_name.$get_parties_frm_adv[$index5][1];
                    $tot_respondents++;
                }
                echo $res_name;
                ?>
                <?php if($tot_respondents==1) { ?> is <?php } else if($tot_respondents>1) { ?> are <?php } ?> represented through <?php
                $send_to_name=  send_to_name(1,$represented_adv[$index4][1]);
                $send_to_name=  explode('<br/>', $send_to_name);
                echo $send_to_name[0];
                ?>, Advocate. They have been served directly with the Notice under Rule 8, Order XIX, S.C.R.2013


                <?php
            }
            ?>  <?php
            $get_parties_frm_adv_not=get_parties_frm_adv_not($dairy_no);
            $not_res_res='';
            for ($index44 = 0; $index44 < count($get_parties_frm_adv_not); $index44++) {
                if($not_res_res=='')
                {
                    echo " and ";
                    $not_res_res=$get_parties_frm_adv_not[$index44][1];

                }
                else
                    $not_res_res=$not_res_res.', '.$get_parties_frm_adv_not[$index44][1];
            }
            ?> Respondent No. <?php echo $not_res_res; ?> has been served with notice at SLP stage but no one has entered appearance on his behalf so far.
        </font>
    </p>
    <!--     <p style="color: #000000;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px;clear: both" align="justify">
             <font  style="font-size: 13pt"  face= "Times New Roman">
                 Service Position is as under:
             </font>
         </p>-->

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            You may now as required under Rule 8(I) and (III), Order XIX, SCR 2013 cause the enclosed notice of
            lodgement of petition of appeal to be served on <?php
            $not_represented_adv=not_represented_adv($dairy_no,$row['order_dt']);
            for ($index4 = 0; $index4 < count($not_represented_adv); $index4++) {
                if($index4==0)
                {
                    $res_name=" and ";
                }
                $party_name=$not_represented_adv[$index4][0];
                $ex_party_name=explode(',',$party_name);
                $total_party='';
                $chk_pet='';

                for ($index5 = 0; $index5 < count($ex_party_name); $index5++) {
                    if($index5!=0)
                        $res_name=$res_name.', ';

                    $in_exp=  explode('-', $ex_party_name[$index5]);
                    if($chk_pet!=$in_exp[0])
                    {
                        if($in_exp[0]=='P')
                            $res_name=$res_name." Petitioner No. ";
                        $chk_pet='P';
                    }
                    if($chk_pet!=$in_exp[0])
                    {
                        if($in_exp[0]=='R')
                            $res_name=$res_name." Respondent No. ";
                        $chk_pet='R';
                    }
//          $res_name=$res_name.' '.
                    $res_name=$res_name.$in_exp[1];
                }
                echo $res_name;
                ?>

                <?php
            }
            ?>

            <!--both the respondents-->
            and transmit to this court a certificate
            as  to date or dates on which the said notice has been served on the respondents.  This
            may kindly be done as early as possible but in any case not later than sixty days and if the same is likely to take more time you are requested to send a letter of request of extension of time which will be placed before the Court for further directions.
        </font>
    </p>
    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            As regards preparation of the Record, I am directed to inform that the Original Record of the
            High Court and the courts below may be requisitioned at a later stage as and when it is specifically
            directed by this Hon'ble Court and, therefore, such Original Record of the High Court and courts below
            may not be weeded out during the pendency of the matter in this Hon'ble Court till a communication
            regarding its disposal is received from the Registry of this Hon'ble Court.
        </font>
    </p>

    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >Yours faithfully,</font></b></p><br><br>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
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

    <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
        <font  style="font-size: 13pt"  face= "Times New Roman">
            You are requested to take further steps for the prosecution of the appeal in accordance with the
            procedure prescribed by <u  style="font-size: 13pt"  face= "Times New Roman">S.C.R.</u> 2013.
        </font>
    </p><br><br>
    <p align="right" style="padding: 16px 2px 0px 0px;margin: 0px"><b><font style="font-size: 13pt"  face= "Times New Roman" >ASSISTANT REGISTRAR</font></b></p>
</div>


<?php
include("legal_aid.php");

?>
