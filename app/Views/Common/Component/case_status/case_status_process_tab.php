 
<?php
$db = \Config\Database::connect();
$ucode = session()->get('login')['usercode'];
//pr($diary_details);
if(!empty($diary_details)){?>

<style>
       #newb { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;}
    #newc { position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;}

    #overlay {
    background-color: #000;
    opacity: 0.7;
    filter:alpha(opacity=70);
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
</style>


<input type="hidden" name="diaryno1" id="diaryno1" value="<?php echo $diary_number; ?>">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
<div class="row" style="margin-left: 1%">
    <div class="nav-tabs-custom">
        <ul class="nav-breadcrumb">
            <li><a class="first active" href="101" style="z-index:17;" data-parent="#accordion1" data-toggle="tab" aria-expanded="false"><strong>Case Details</strong></a></li>
            <li><a href="102" style="z-index:16;" data-parent="#accordion1" data-toggle="tab" aria-expanded="false"><strong>Earlier Court Details</strong></a></li>
            <li><a href="103" style="z-index:15;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Tagged Matters</strong></a></li>
            <li><a href="104" style="z-index:14;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Listing Dates</strong></a></li>
            <li><a href="105" style="z-index:13;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Interlocutory Application / Documents</strong></a></li>
            <li><a href="106" style="z-index:12;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Court Fees</strong></a></li>
            <li><a href="107" style="z-index:11;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Notices</strong></a></li>
            <li><a href="108" style="z-index:10;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Defects</strong></a></li>
            <li><a href="109" style="z-index:9;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Judgement/Orders</strong></a></li>
            <li><a href="110" style="z-index:8;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Mention Memo</strong></a></li>
            <li><a href="111" style="z-index:7;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Restoration Details</strong></a></li>
            <li><a href="112" style="z-index:6;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>DropNote</strong></a></li>
            <li><a href="113" style="z-index:5;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Appearance</strong></a></li>
            <li><a href="114" style="z-index:4;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Office Report</strong></a></li>
            <li><a href="115" style="z-index:3;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Similarities</strong></a></li>
            <li><a href="116"  style="z-index:2;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Caveat</strong></a></li>
            <li><a href="117" style="z-index:1;" data-parent="#accordion1" data-toggle="tab" aria-expanded="true"><strong>Gate Information</strong></a></li>
        </ul>
        <div class="tab-content">
            <div id="caseSearchPanel"></div>
            <div class="tab-pane active" id="part1">
                <br>
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>
</div>
<div align="left">
    <input type="button" id="btnPrint" class="btn btn-primary" value="Print" onclick="printDiv();">
</div>

<div id="divPrint">
    <h5 align="center" style="color:green;">Diary No.- <?php echo substr($diary_details['diary_no'], 0, -4) . ' - ' . substr($diary_details['diary_no'], -4); ?> <!--<img src="../images/qr-code.png" width="40px" height="40px" align="right" id="myBtn"style="cursor: pointer;margin-right:20px;">-->

        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">×</span>
                </div>
                <div class="modal-body">
                    <object id="qr-object" data="" width="100%" height="100%"></object>
                </div>
            </div>
        </div>
    </h5><? //print_r($diary_details);
            ?>
    <h5 align="center"><?php echo $diary_details['pet_name'] . ' <span style="color:red;">vs</span> ' . $diary_details['res_name'] ?></h5>

    <h5 align="right"> <?php
        $urgent_category='';

        //$sql_urg_cat="SELECT  ref_special_category_filing_id, category_name FROM special_category_filing s  join ref_special_category_filing r on s.ref_special_category_filing_id=r.id WHERE s.display='Y'and r.display='Y' and diary_no=$diaryno";
        //$rs_urg_cat=mysql_query($sql_urg_cat);
        if(!empty($row_urg_cat))
        {
            //$row_urg_cat = mysql_fetch_array($rs_urg_cat);
            if($row_urg_cat['ref_special_category_filing_id']!=null and $row_urg_cat['ref_special_category_filing_id']!='' and $row_urg_cat['ref_special_category_filing_id']!='0')
            { ?>
                <br><br><span style='float:right;text-align:center;padding-bottom: 10px;padding-right:0px;padding-bottom:5px;padding-right:10px;font-size: x-large;'><span id='blink_text'>URGENT:</span><font color='purple'><?php echo $row_urg_cat['category_name']; ?> </font> </span>
                <?php
            }
        }


        ?>
    </h5>
    <div id="collapse118">
        <div id="result118"></div>

    </div>
    <div id="caseDetails">
        <table border="0" align="left" width="100%" class="table table-bordered table-striped">
            <tbody>
                <?php if (!empty($filing_stage) && $diary_details['c_status'] != 'D'): ?>
                    <tr>
                        <td width="140px">Filing Stage</td>
                        <td>
                            <div width="100%">
                                <font color="#006400" style="font-size:12px;font-weight:bold;"><?php echo $filing_stage ?></font>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <?php

                    $diary_recieved_date = date_create($diary_details['diary_no_rec_date']);
                    $diary_recieved_date = date_format($diary_recieved_date, "d-m-Y h:i A");
                    $u_name = $last_updated_by = $act_sec_des = $act_section = $act_sec_des = $sect = "";
                    if (!empty($diary_section_details['section_name'])) {
                        $d_name = "<font color='blue'>" . $diary_section_details["name"] . " [" . $diary_section_details["section_name"] . "]</font>";
                        $u_name = " by <font color='blue'>" . $diary_section_details["name"] . "</font>";
                        $u_name .= "<font> [SECTION: </font><font color='red'>" . $diary_section_details["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                    }
                    if (!empty($main_case))
                        $main_case = "<br>&nbsp;&nbsp;<font color='red' >[Connected with : " . $main_case . "</font>]";

                    if ($diary_details['if_sclsc'] == 1)
                        $status = 'SCLSC ';
                    elseif ($diary_details['nature'] == 6)
                        $status = 'JAIL PETITION ';
                    else
                        $status = '';

                    if (!empty($no_of_defect_days['no_of_days']) && $no_of_defect_days['no_of_days'] < 90) {
                        $status .= "Cases under Defect List valid for 90 Days";
                        $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                    } elseif (!empty($no_of_defect_days['no_of_days']) && $no_of_defect_days['no_of_days'] >= 90) {
                        $status .= "Defective Matters Not Re-filed after 90 Days";
                        $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                    } else if ($diary_details['c_status'] == 'P') {
                        if ($recalled_matters != '"false"') {
                            $status .= "PENDING(RECALLED)";
                            $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                        } else {
                            $status .= "PENDING";
                            $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='blue' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                        }
                    }

                    if ($diary_details['c_status'] == 'D') {
                        $status .= "DISPOSED";
                        $main_status = "<div style='float:right;text-align:center;padding-right:5px;'><span class='blink_me'><font color='red' style='font-size:20px;font-weight:bold;'>" . $status . "</font></span></div>";
                    }
                    $consign_status = $efiled_status = '';
                    $efiled_case=0;
                    if (!empty($consignment_status)) {
                        foreach ($consignment_status as $row_consigned) {
                            $consignment_date = date_create($row_consigned['consignment_date']);
                            $consignment_date = date_format($consignment_date, "d-m-Y");

                            $consign_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>Consigned On : " . $consignment_date . "</font></span></span>";
                        }
                    }

                    $sensitive_case_status='';
                  /*  $sql_sensitive_case = "select diary_no from sensitive_cases where diary_no='".$diaryno."' and display='Y'
                and (find_in_set(".$_SESSION['dcmis_user_idd'].",(select users_empid from sensitive_case_users))>=1)";
                    $result_sensitive_case = mysql_query($sql_sensitive_case) or die(mysql_error()." SQL:".$sql_sensitive_case); */
                       if(!empty($result_sensitive_case)) {
                            $sensitive_case_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>Sensitive Case</font></span></span>";
                        }


                    if ($diary_details['ack_id'] > 0) {
                        $efiled_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>E-Filed Matter</font></span></span>";
                    }

                    if (!empty($efiled_cases)) {
                        $efiled_case=1;
                        $efiled_status = "<span style='float:right;text-align:center;padding-right:5px;padding-right:10px;'><span class='blink_me'><font color='red' style='font-size:15px;font-weight:bold;'>E-Filed Matter</font></span></span>";
                    }

                    if (!empty($acts_sections)) {

                        foreach ($acts_sections as $act_section_array) {
                            if ($act_section_array['section'] != '')
                                $t_as = $act_section_array['act_name'] . '-' . $act_section_array['section'];
                            else
                                $t_as = $act_section_array['act_name'];

                            $act_sec_des .= $act_section_array['act_name'];


                            if ($act_section == '')
                                $act_section = $t_as;
                            else
                                $act_section = $act_section . ', ' . $t_as;
                        }
                        $act_sec_des = rtrim($act_sec_des, ',');
                        $act_sec_des = trim($sect) . ' ' . $act_sec_des;
                    }

                    //code to check if autodiarized efiled matter
                    $efm_diaryuser='';
                    //$sql_autodiary="select diary_no from efiled_cases where diary_no=$diaryno and display='Y' and efiled_type='new_case' and (created_by=10531 or date(created_at)>'2023-07-19')";
                    //$rs_autodiary=mysql_query($sql_autodiary);
                    if(!empty($rs_autodiary))
                    {
                        $efm_diaryuser ="<font color='red'> [AUTO GENERATED] </font>";

                    }

                    ?>
                    <td width="140px">Diary No.</td>
                    <td>
                        <div width="100%">
                            <font color="blue" style="font-size:12px;font-weight:bold;"><?php echo substr($diary_details['diary_no'], 0, -4) . '/' . substr($diary_details['diary_no'], -4); ?></font> <?php echo $efm_diaryuser;?> Received on <?php echo $diary_recieved_date; ?> <?php echo $u_name . $main_case . $main_status . $consign_status . $sensitive_case_status.$efiled_status ?>
                    </td>
                </tr>
                <tr><td width='140px'>Supreme Court<br> CNR Number</td><td><?php echo $diary_details['cnr']?></td></tr> 
                <tr>
                    <td width="140px">Case No.</td>
                    <td>
                        <div width="100%">
                            <?php echo $case_no;?>
                           <!-- <font color="#043fff" style=" white-space: nowrap;">SLP(Crl) No. 001110 / 2023</font>&nbsp;&nbsp;(Reg.Dt.21-01-2023)<br> -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="140px">IB- DA Name</td>
                    <td><?php echo $IB_da_name; ?></td>
                </tr>
                <tr>
                <tr>
                    <td width="140px">Section - DA Name</td>
                    <td><?php echo $section_da_name; ?></td>
                </tr>
                <tr>
                    <td width="140px">Last Updated By</td>
                    <td style="font-size:12px;font-weight:100;"><?php echo $fill_dt_case['last_u'] ?? '' ?> On <?php echo (!empty($fill_dt_case['last_dt'])) ?  date('d-m-Y h:i a',strtotime($fill_dt_case['last_dt'])) : '' ?></td>
                </tr>
                <tr>
                    <td width="140px">Last Listed On</td>
                    <td style="font-size:12px;font-weight:bold;"><b>-----</b></td>
                </tr>
                <?php
               // pr($diary_disposal_date);
               $disp_upd= '';
               $disp_str = '';
               $rjdate = '';
               $dispdet = '';
               if(!empty($diary_disposal_date))
               {
                    $disp_dt = $diary_disposal_date["disp_dt"] ?? '';
                    $d_spk = '';
                    $rjdate = '';
                        if (!empty($diary_disposal_date) && $diary_disposal_date["rj_dt"] != "")
                            $rjdate = "&nbsp;&nbsp;&nbsp;RJ Date: " . date('d-m-Y', strtotime($diary_disposal_date["rj_dt"]));

                        ////Disp type
                        $disptype = $diary_disposal_date['disp_type'];
                        if ($disptype != "") {
                            $dsql = "select * from master.disposal where dispcode='$disptype'";
                            $results_dsql = $db->query($dsql);
                            $drow = $results_dsql->getRowArray();
                            if (!empty($drow)) {                                 
                                if($ucode==203 || $ucode==204 || $ucode==888 || $ucode==912)
                                {
                                    if($drow['spk']=="N")
                                        $d_spk=" (Non Speaking)";
                                    else
                                        $d_spk=" (Speaking)";
                                }
                                $dispdet = $drow['dispname'].$d_spk;
        
                                if ($disptype == 19)
                                    $dispdet = $dispdet . " by LOK ADALAT ";
                            }
                        }

                        
                        $disp_upd=" updated by ".$diary_disposal_date['name']."(".$diary_disposal_date['empid'].")-".$diary_disposal_date['section_name'];
                        $disp_str = " (Order Date: " . $diary_disposal_date["ord_dt"] . " and Updated on ".$diary_disposal_date["ddt"]. ")<br> JUDGES: " . stripslashes($diary_disposal_date["judges"]);
                }
                ?>
                <tr>
                    <td width="140px">Last Order</td>
                    <td style="font-size:12px;font-weight:100;"><?php echo $diary_details['lastorder'] ?? ''?> <?php echo $rjdate?> </td>
                </tr>
                <tr>
                    <td width="140px">Status</td>
                    <td style="font-size:12px;font-weight:100;">
                        <font color="red" style="font-size:12px;font-weight:100;"><?php echo trim($status) .$disp_upd."<br/>". $disp_str ?></font>
                    </td>
                </tr>
                <tr>
                    <td width="140px">Disp.Type</td>
                    <td style="font-size:12px;font-weight:100;">
                        <font color="red" style="font-size:12px;font-weight:100;"><?php echo (!empty($dispdet)) ?  trim($dispdet) : '';?></font>
                    </td>
                </tr>
                <?php
                $head='';
                $stage = '';       
                    // pr($heardt_case);
                if ($stage == "" && !empty($heardt_case) )  {
                    if ($heardt_case["mainhead"] == "M") {
                        $stage = "Motion Hearing";
                    } elseif ($heardt_case["mainhead"] == "F") {
                        $stage = "Final Hearing";
                        $check_for_final_hearing = "YES";
                    } elseif ($heardt_case["mainhead"] == "N") {
                        $stage = "Not Reached Cases";
                    } elseif ($heardt_case["mainhead"] == "L") {
                        $stage = "Lok Adalat";
                    }
                    if ($heardt_case["subhead"] != "") {
                        $t_stage = "";
                        
                        // echo $heardt_case["subhead"];
                        // die;
                        $row1_s = $Model_case_status->getSubheadings($heardt_case);
                        // pr($row1_s);
                        if (!empty($row1_s)) {
                            //while ($row1_s = mysql_fetch_array($result1_s)) {
                                if ($heardt_case["mainhead"] == "F" && isset($row1_s["stage_nature"])) {
                                    switch ($row1_s["stage_nature"]) {
                                        case "C":
                                            $sn = "Civil - ";
                                            break;
                                        case "R":
                                            $sn = "Criminal - ";
                                            break;
                                        case "WC":
                                            $sn = "Writ Civil - ";
                                            break;
                                        case "WR":
                                            $sn = "Writ Criminal - ";
                                            break;
                                        case "EP":
                                            $sn = "Election Petition - ";
                                            break;
                                        case "PIL":
                                            $sn = "PIL - ";
                                            break;
                                        case "":
                                            $sn = "";
                                            break;
                                    }
                                    $criteria="";
                                    if ($row1_s["stagecode4"] > 0){
                                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["grp_name2"] . " - " . $row1_s["stagename"];
                                        $criteria.=" and stagecode1='".$row1_s["stagecode1"]."' and stagecode2='".$row1_s["stagecode2"]."' and stagecode3='".$row1_s["stagecode3"]."' and stagecode4='".$row1_s["stagecode4"]."'";
                                    }
                                    elseif ($row1_s["stagecode3"] > 0){
                                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["grp_name1"] . " - " . $row1_s["stagename"];
                                        $criteria.=" and stagecode1='".$row1_s["stagecode1"]."' and stagecode2='".$row1_s["stagecode2"]."' and stagecode3='".$row1_s["stagecode3"]."'";
                                    }
                                    elseif ($row1_s["stagecode2"] > 0){
                                        $t_stage = $row1_s["grp_name"] . " - " . $row1_s["stagename"];
                                        $criteria.=" and stagecode1='".$row1_s["stagecode1"]."' and stagecode2='".$row1_s["stagecode2"]."'";
                                    }
                                    elseif ($row1_s["stagecode1"] > 0){
                                        $t_stage = $row1_s["stagename"];
                                        $criteria.=" and stagecode1='".$row1_s["stagecode1"]."'";
                                    }
                                    //GET FH SRNO
                                    
                                    // $sql_fh="SELECT CONCAT( table_schema, '.', table_name) as tbl, DATE_FORMAT(create_time,'%d-%m-%Y %h:%i %p') create_time FROM information_schema.`TABLES` WHERE table_schema = 'demoas' AND table_name LIKE  '%fh_temp_for_srno%' ORDER BY create_time DESC LIMIT 1";
                                    // $result_fh=mysql_query($sql_fh) or die(mysql_error()." SQL:".$sql_fh);
                                    //     if(mysql_affected_rows()>=1)
                                    //     {
                                    //         $row_fh=mysql_fetch_array($result_fh);
                                    //         if ($row['c_status'] == 'P')
                                    //             $head="<br/><font color=red><u>Note</u>: SrNo. of final hearing pending cases under each head is last updated on ".$row_fh["create_time"]."</font>";
                                    //         $t="select * from ".$row_fh["tbl"]." WHERE 1=1 and diary_no='".$row['diary_no']."'".$criteria ;
                                    //         $result_t=mysql_query($t) or die(mysql_error()." SQL:".$t);
                                    //         if(mysql_affected_rows()>=1)
                                    //         {
                                    //             $row_t=mysql_fetch_array($result_t);
                                    //             $t_stage.= "  (at SrNo.: <u>".$row_t["sno2"]."</u> and at SrNo.: <u>".$row_t["sno"]."</u> in overall FH cases)";
                                    //         }

                                    //     }


                                    //GET FH SRNO
                                    $stage = $stage . " <br> (" . $sn . $t_stage . ")";
                                }
                                else {
                                    $pstage1="";
                                    $stage = $stage . " <br> " . $row1_s["stagename"];
                                    if($row1_s["stagecode"] == 849 or $row1_s["stagecode"] == 850){
                                        $pstage1=" / ".$Model_case_status->get_previous_stage($row['diary_no'],$row1_s["stagecode"]);
                                        $stage = $stage . $pstage1;
                                    }

                                }
                            //}
                        }
                    }
                }

               
                ?>
                <tr>
                    <td width="140px">Stage</td>
                    <td style="font-size:12px;font-weight:100;"><?php echo trim($stage) .trim($head)?></td>
                </tr>
                <?php 
                $sta = "SELECT remark, ent_dt as entdt FROM brdrem WHERE diary_no = '".$diary_details['diary_no']."' union SELECT remark, ent_dt as entdt FROM brdrem_a WHERE diary_no = '".$diary_details['diary_no']."'";
                $sta_inf = $db->query($sta);
                $rowstinf = $sta_inf->getRowArray();                
                $sta_infd = '';
                if(!empty($rowstinf) && !empty($rowstinf["remark"])) {
                   $sta_infd = " [<font color='green' style='font-size:12px;font-weight:100;'>" . stripslashes(str_replace('[', '', str_replace(']', '', $rowstinf["remark"]))) . "</font>]";
                }
                ?>
                <tr>
                    <td width="140px">Statutory Info.</td>
                    <td style="font-size:12px;font-weight:100;"><?php echo $sta_infd;?></td>
                </tr>
                <?php 
                $bench = array();
                if(!empty($diary_details['bench']))
                {
                    $bn_sql = $db->query("select bench_name from master.master_bench where display='Y' and id= '".$diary_details['bench']."' ");
                    $bench = $bn_sql->getRowArray();
                }
                ?>
                <tr>
                    <td width="140px">Bench</td>
                    <td><?php echo (!empty($bench) && $bench['bench_name'] != '') ? $bench['bench_name'] : ''; ?></td>
                </tr>
                <tr>
                    <td width="140px">Old Category</td>
                    <td><?php echo $old_category_name ?? ''; ?></td>
                </tr>
                <tr>
                    <td width="140px">
                        <font color="red">New Category</font>
                    </td>
                    <td>
                        <font color="red"><?php echo $new_category_name;?></font>
                    </td>
                </tr>
                <tr>
                    <td width="140px">Act</td>
                    <td><?php echo $act_section; ?>
                    </td>
                </tr>
                <?php
                 $pname = "";$rname = "";$impname="";$intname="";
                 if(!empty($party_details))
                 {
                    foreach($party_details as $row_p) {
                        $tmp_addr = "";$tmp_name ="";
        
                        if($row_p["pflag"]=='O')
                            $tmp_name = $tmp_name . "<p style=color:red>&nbsp;&nbsp;";
                        else if($row_p["pflag"]=='D')
                            $tmp_name = $tmp_name . "<p style=color:#9932CC>&nbsp;&nbsp;";
                        else
                            $tmp_name = $tmp_name . "<p>&nbsp;&nbsp;";
        
                        $tmp_name = $tmp_name . $row_p["sr_no_show"];
                        $tmp_name = $tmp_name . " ";
                        $tmp_name = $tmp_name . $row_p["partyname"];
                        if ($row_p["prfhname"] != "")
                            $tmp_name = $tmp_name . " S/D/W/Thru:- " . $row_p["prfhname"];
                        if($row_p["remark_lrs"] != '' || $row_p["remark_lrs"] !=NULL)
                            $tmp_name .= " [".$row_p["remark_lrs"]."]";
        
                        if($row_p["pflag"]=='O' || $row_p["pflag"]=='D')
                            $tmp_name .= " [".$row_p["remark_del"]."]";
        
                        if ($row_p["addr1"] != "")
                            $tmp_addr = $tmp_addr . $row_p["addr1"] . ", ";
                        if($row_p['ind_dep']!='I' && isset($row_p['deptname']) && $row_p['deptname'] && trim(str_replace($row_p['deptname'],'',$row_p['partysuff'])!=''))
                            $tmp_addr = $tmp_addr . " ".trim(str_replace($row_p['deptname'],'',$row_p['partysuff'])).", " ;
                        if ($row_p["addr2"] != "")
                            $tmp_addr = $tmp_addr . $row_p["addr2"] . " ";     
        
        
                        if ($row_p["city"] != "") {
        
                            $dstName='';
                            if($row_p["dstname"]!="")
                            {
                                $dstName.=" , DISTRICT: ". $row_p["dstname"];
                            }
        
                            $tmp_addr = $tmp_addr . $dstName ." ," . get_state($row_p["city"]) . " ";
    
                        }
                        if ($row_p["state"] != "") {
                            $tmp_addr = $tmp_addr . ", " . get_state($row_p["state"]) . " ";
                        }
                        if ($tmp_addr != "")
                            $tmp_name = $tmp_name . "<br>&nbsp;&nbsp;" . $tmp_addr . "";
                        $tmp_name = $tmp_name . "</p>";
        
                        if($row_p["pet_res"]=="P"){
                            $pname .= $tmp_name;
                        }
                        if($row_p["pet_res"]=="R"){
                            $rname .= $tmp_name;
                        }
                        if($row_p["pet_res"]=="I"){
                            $impname .= $tmp_name;
                        }
                        if($row_p["pet_res"]=="N"){
                            $intname .= $tmp_name;
                        }
                    }
                }
                
                ?>
                <tr>
                    <td width="140px">Petitioner(s)</td>
                    <td><?php echo $pname; ?> </td>
                </tr>
                <tr>
                    <td width="140px">Respondent(s)</td>
                    <td><?php echo $rname; ?></td>
                </tr>
                <tr>
                    <td width="140px">Impleader(s)</td>
                    <td><?php echo $impname; ?></td>
                </tr>
                <tr>
                    <td width="140px">Intervenor(s)</td>
                    <td><?php echo $intname; ?></td>
                </tr>
                <tr>
                    <td width="140px">Amicus Curie(For Court Assistance)</td>
                    <td><?php echo $ac_court; ?></td>
                </tr>
                <tr>
                    <td width="140px">Pet. Advocate(s)</td>
                    <td><?php echo $padvname; ?></td>
                </tr>
                <tr>
                    <td width="140px">Resp. Advocate(s)</td>
                    <td><?php echo $radvname; ?></td>
                </tr>
                <tr>
                    <?php if ($iadvname != '') { ?>
                <tr>
                    <td width="140px">Impleaders Advocate(s)</td>
                    <td><?php echo $iadvname; ?></td>
                </tr>
                <tr>
                <?php }
                    if ($nadvname != '') { ?>
                <tr>
                    <td width="140px">Intervenor Advocate(s)</td>
                    <td><?php echo $nadvname; ?></td>
                </tr>
                <tr>
                <?php } ?>
                <td width="140px">U/Section</td>
                <td><?php echo $act_sec_des; ?>
                </td>
                </tr>
                <?php if (!empty($file_movement_data)) {
                ?>
                    <tr>
                        <td width="140px">File Movement</td>
                        <td>
                            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center"><b>Dispatch By</b></th>
                                        <th><b>Dispatch On</b></th>
                                        <th><b>Remarks</b></th>
                                        <th><b>Dispatch to</b></th>
                                        <th align="center"><b>Receive by</b></th>
                                        <th align="center"><b>Receive On</b></th>
                                        <th><b>Completed On</b></th>
                                    </tr>
                                    <?php foreach ($file_movement_data as $fil_mov_r) {
                                        if ($fil_mov_r['comp_dt'] == '' || $fil_mov_r['comp_dt'] == null || $fil_mov_r['comp_dt'] == "")
                                            $fil_mov_r['comp_dt'] = "0000-00-00 00:00:00";
                                        else
                                            $fil_mov_r['comp_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['comp_dt']));
                                        if ($fil_mov_r['rece_dt'] == '' || $fil_mov_r['rece_dt'] == null || $fil_mov_r['rece_dt'] == "")
                                            $fil_mov_r['rece_dt'] = '0000-00-00 00:00:00';
                                        else
                                            $fil_mov_r['rece_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['rece_dt']));
                                        if ($fil_mov_r['disp_dt'] == '' || $fil_mov_r['disp_dt'] == null || $fil_mov_r['disp_dt'] == "")
                                            $fil_mov_r['disp_dt'] = '0000-00-00 00:00:00';
                                        else
                                            $fil_mov_r['disp_dt'] = date('d-m-Y h:i:s A', strtotime($fil_mov_r['disp_dt']));
                                    ?>
                                        <tr>
                                            <td align="center"><?php echo $fil_mov_r['d_by_name']; ?></td>
                                            <td><?php echo $fil_mov_r['disp_dt']; ?></td>
                                            <td><?php echo $fil_mov_r['remarks']; ?></td>
                                            <td><?php echo $fil_mov_r['d_to_name']; ?></td>
                                            <td align="center"><?php echo $fil_mov_r['r_by_name']; ?></td>
                                            <td align="center"><?php echo $fil_mov_r['rece_dt']; ?></td>
                                            <td align="center"><?php echo $fil_mov_r['comp_dt']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div id="newb" style="display:none;">
    <table width="100%" border="0" style="border-collapse: collapse">
        <tbody>
            <tr style="background-color: #A9A9A9;">
                <td align="center">
                    <b>
                        <font color="black" style="font-size:14px;">Case Status</font>
                    </b>
                </td>
                <td>
                    <input style="float:right;" type="button" name="close_b" id="close_b" value="CLOSE WINDOW" onclick="close_w();">
                </td>

            </tr>
        </tbody>
    </table>
    <div id="newb123" style="overflow:auto; background-color: #FFF;">
    </div>
    <div id="newb1" align="center">
        <table border="0" width="100%">
            <tbody>
                <tr>
                    <td align="center" width="250px">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="dv_fixedFor_P" style="display: none;position: fixed;top:75px;left:10% !important;width:85%;height:100%;z-index: 105;">  
  <div id="close_s" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="close_cs()"><b><img src="<?php echo base_url('images/close_btn.png');?>" style="width:30px;height:30px" /></b></div>
  <div id="newcs123" style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;">
  </div>
  </div>
  
  <div id="overlay" class="overlay" style="display:none;">&nbsp;</div>
<script>
    $(".nav-breadcrumb li").click(function(event) {
        updateCSRFToken();
        var url = "";
        $(".nav-breadcrumb li a").removeClass('active');
        var activeTab = $(this).find('a').attr('href').split('#')[0];
        var accname = $(this).find('a').attr('data-parent');
        var accname1 = accname.replace("#accordion", "");
        var diaryno = document.getElementById('diaryno' + accname1).value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        //var diaryno = document.getElementById('diaryno').value;
        //alert(diaryno);
        
        if (activeTab == 102) url = "case_status/earlier_court";
        if (activeTab == 103) url = "case_status/get_connected";
        if (activeTab == 104) url = "case_status/get_listings";
        if (activeTab == 105) url = "case_status/get_ia";
        if (activeTab == 106) url = "case_status/get_court_fees";
        if (activeTab == 107) url = "case_status/get_notices";
        if (activeTab == 108) url = "case_status/get_default";
        if (activeTab == 109) url = "case_status/get_judgement_order";
        /*if(activeTab==110) url="get_adjustment.php";*/
        if (activeTab == 110) url = "case_status/get_mention_memo";
        if (activeTab == 111) url = "case_status/get_restore";
        if (activeTab == 112) url = "case_status/get_drop";
        if (activeTab == 113) url = "case_status/get_appearance";
        if (activeTab == 114) url = "case_status/get_office_report";
        if (activeTab == 115) url = "case_status/get_similarities";
        if (activeTab == 116) url = "case_status/get_caveat";
        if (activeTab == 117) url = "case_status/get_gateinfo";



        if (activeTab != 101) {
            $("#caseDetails").hide();

            $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Common')?>/'+url,
                    beforeSend: function(xhr) {
                        $("#collapse" + 118).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    },
                    data: {
                        diaryno: diaryno,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(response) {
                    updateCSRFToken();
                    $("#collapse" + 118).show();
                    $("#collapse" + 118).html('');
                    $("#collapse" + 118).html(response);
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });

        } else {
            $("#collapse" + 118).hide();
            $("#caseDetails").show();
            updateCSRFToken();
        }

    });

    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    /* var diaryNo = document.getElementById("myBtn").value;
     alert(diaryNo);*/

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
        $("#qr-object").attr('data', '');
        $("#qr-object").attr('data', 'https://10.25.78.69:44434/api/safe_transit/qr_code/generate/case_file?caseIdCsv=1232023&afterHook=print')
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function printDiv()
{      
    let printElement = document.getElementById('divPrint');
    var printWindow = window.open('', 'PRINT');
    printWindow.document.write(document.documentElement.innerHTML);
    setTimeout(() => { // Needed for large documents
      printWindow.document.title = "SC : CMIS";
      printWindow.document.body.style.margin = '0 0';
      printWindow.document.body.innerHTML = printElement.outerHTML;
      printWindow.document.close(); // necessary for IE >= 10
      printWindow.focus(); // necessary for IE >= 10*/
      printWindow.print();
      printWindow.close();
    }, 1000)
}



function call_f1(d_no,d_yr,ct,cn,cy)
{
    var divname = "";
    divname = "newcs123";
    document.getElementById(divname).style.display = 'block';
    document.getElementById(divname).style.width = 'auto';
    document.getElementById(divname).style.height = '500px';
    document.getElementById(divname).style.overflow = 'scroll';
    document.getElementById(divname).style.marginLeft = '18px';
    document.getElementById(divname).style.marginRight = '18px';
    document.getElementById(divname).style.marginBottom = '25px';
    document.getElementById(divname).style.marginTop = '30px';
    document.getElementById('dv_fixedFor_P').style.display = 'block';
    document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
    $('.overlay').height($(window).height());
    //document.getElementById('overlays').style.display = 'block';
    $('.overlay').show();
   

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
      $.ajax({
            type: 'POST',
            url: base_url+'/Common/Case_status/case_status_same',
            beforeSend: function (xhr) {
                $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            //data:{d_no:d_no,d_yr:d_yr,ct:ct,cn:cn,cy:cy,tab:'Case Details',opt:2,CSRF_TOKEN: CSRF_TOKEN_VALUE}
            data:{diary_number:d_no,diary_year:d_yr,ct:ct,cn:cn,cy:cy,search_type:'D',opt:2,CSRF_TOKEN: CSRF_TOKEN_VALUE}
        })
        .done(function(msg){
            updateCSRFToken();
            $("#newcs123").html(msg);           
           
        })
        .fail(function(){
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room"); 
        });
}

function close_cs()
{
    var divname = "";
    divname = "newcs123";
    $("#newcs123").html(''); 
    document.getElementById('dv_fixedFor_P').style.display = "none";
    document.getElementById(divname).style.display = 'none';
    $('.overlay').hide();
}

function close_w()
{
    var divname = "";
    divname = "newb";
    document.getElementById(divname).style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}


</script>

<?php }else{?>
    <div class="row" style="margin-left: 1%">
        <div class="col-md-12">
        <h3>No data found</h3>
        </div>       
    </div>
    <?php }?>