<?= view('header') ?>
<script type="text/javascript" src="<?=base_url(); ?>/judicial/prev_case_remarks.js?version=1"></script>
<?php
$t_iaval = '';
$tot_next_dt = '';
$t_mh = '';
$oth_doc = '';
$t_table = '';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial > Previous Case Remarks > Update</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url() ?>/Judicial/PrevCaseRemarks/index"><button class="btn btn-info btn-sm" type="button" title="Diary Search"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="text-align: center">
                            <h3>Diary No.- <?php echo $diary_num; ?> - <?php echo $diary_year; ?></h3>
                        </div>
                        <?php    
                        // Initialize counters
                        $ctr_p = 0; // for counting petitioner
                        $ctr_r = 0; // for counting respondent
                        
                        if (!empty($party_details)) 
                        {
                            $grp_pet_res = '';
                            $pet_name = $res_name = "";
                            foreach ($party_details as $row) {
                                $temp_var = "";
                                $temp_var .= $row['partyname'];
                                if ($row['sonof'] != '') {
                                    $temp_var .= $row['sonof'] . "/o " . $row['prfhname'];
                                }
                                if ($row['deptname'] != "") {
                                    $temp_var .= "<br>Department : " . $row['deptname'];
                                }
                                $temp_var .= "<br>";
                                if ($row['addr1'] == '')
                                    $temp_var .= $row['addr2'];
                                else
                                    $temp_var .= $row['addr1'] . ', ' . $row['addr2'];
                                $t_var = "";

                                // Check if the result exists and fetch the value
                                $t_var = '';
                                if (!empty($row['district_name'])) {
                                    $t_var = $row['district_name'];
                                }

                                if ($t_var != "")
                                    $temp_var .= ", District : " . $t_var;

                                if ($row['pet_res'] == 'P') {
                                    $pet_name = $temp_var;
                                } else {
                                    $res_name = $temp_var;
                                }
                            }
                            ?>
                             <div class="cl_center">
                                <h3>Case Details</h3>
                            </div>
                            <table class="table_tr_th_w_clr c_vertical_align table_pad" width="100%">
                                <tr>
                                    <td style="width: 15%">
                                        Case No.
                                    </td>
                                    <td>
                                        <?php
                                        $t_fil_no1 = "";
                                        
                                        // Check if there are any results and loop through them

                                        if (!empty($lowerct_details)) {
                                            foreach ($lowerct_details as $ro_lct) {

                                                if ($t_fil_no1 == '')
                                                    $t_fil_no1 .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                                                else
                                                    $t_fil_no1 .= ", " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'];
                                            }
                                        }
                                        echo get_case_nos($diary_no, '&nbsp;&nbsp;&nbsp;') . $t_fil_no1; ?>
                                    </td>

                                </tr>
                                <?php
                                //DA NAME START
                                $da_name = "";

                                // Check if the result exists
                                if (!empty($row_da)) 
                                {

                                    $da_name = "<font color='blue' style='font-size:12px;font-weight:bold;'>" . $row_da["name"] . "</font>";
                                    //if ($row_da["username"] != "")
                                    //$da_name.="<font style='font-size:12px;font-weight:bold;'> [</font><font color='green' style='font-size:12px;font-weight:bold;'>" . $row_da["username"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                    if ($row_da["dacode"] != "0") {
                                        $da_name .= "<font style='font-size:12px;font-weight:bold;'> [SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $row_da["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                    }
                                    else 
                                    {
                                        $casetype_displ = $row_da['casetype_displ'];
                                        $section_ten_row = $row_da['section_ten_row'];
                                        
                                        // Check if the result exists
                                        if (!empty($section_ten_row)) {
                                            $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $section_ten_row["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                        } 
                                        else 
                                        {
                                            $diff_da_sec_name = array(39, 9, 10, 19, 20, 25, 26);
                                            if (in_array($casetype_displ, $diff_da_sec_name)) 
                                            {

                                                $for_da_temp_row = $row_da['for_da_temp_row'];

                                                if (!empty($for_da_temp_row)) 
                                                {

                                                    if ($for_da_temp_row['section_name'] != NULL || $for_da_temp_row['section_name'] != '') 
                                                    {
                                                        $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $for_da_temp_row["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                                    } 
                                                    else 
                                                    {
                                                        $section_ten_row = $for_da_temp_row['section_ten_row'];

                                                        // Check if the result exists
                                                        if (!empty($section_ten_row)) {

                                                            $da_name .= "<font style='font-size:12px;font-weight:bold;'> [Tentative SECTION: </font><font color='red' style='font-size:12px;font-weight:bold;'>" . $section_ten_row["section_name"] . "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //DA NAME ENDS
                                ?>
                                <tr>
                                    <td style="width: 15%">
                                        DA Name
                                    </td>
                                    <td>
                                        <?php echo $da_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">
                                        Petitioner
                                    </td>
                                    <td>
                                        <?php echo $pet_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">
                                        Respondant
                                    </td>
                                    <td>
                                        <?php echo $res_name; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 15%">
                                        Case Category
                                    </td>
                                    <td>
                                        <?php
                                        $case_category = "";
                                        $mul_category = get_mul_category($diary_no);
                                        //echo $mul_category;
                                        if(!empty($mul_category)) {
                                            echo $mul_category[0];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Act
                                    </td>
                                    <td>
                                        <?php
                                        $act_section = '';

                                        // Check if the result exists
                                        if (!empty($act_rows)) {
                                            foreach ($act_rows as $row1) {
                                                if ($act_section == '')
                                                    $act_section = $row1['act_name'] . '-' . $row1['section'];
                                                else
                                                    $act_section = $act_section . ', ' . $row1['act_name'] . '-' . $row1['section'];
                                            }
                                        }
                                        echo $act_section;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Provision of Law
                                    </td>
                                    <td>
                                        <?php

                                        $padvname = $radvname = $ac_text = $for_court = $ac_court = $advType = "";

                                        // Check if the result exists
                                        if (!empty($advocate_rows)) 
                                        {
                                            foreach ($advocate_rows as $row_advp) 
                                            {                           
                                                $tmp_advname =  "<p>&nbsp;&nbsp;";
                                                if ($row_advp['is_ac'] == 'Y') 
                                                {
                                                    if ($row_advp['if_aor'] == 'Y')
                                                        $advType = "AOR";
                                                    else if ($row_advp['if_sen'] == 'Y')
                                                        $advType = "Senior Advocate";
                                                    else if ($row_advp['if_aor'] == 'N' && $row_advp['if_sen'] == 'N')
                                                        $advType = "NON-AOR";
                                                    else if ($row_advp['if_other'] == 'Y')
                                                        $advType = "Other";
                                                    $ac_text = '[Amicus Curiae- ' . $advType . ']';
                                                } else
                                                    $ac_text = '';
                                                
                                                if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null)) 
                                                {
                                                    $for_court = "[For Court]";
                                                } else {
                                                    $for_court = "";
                                                }

                                                $tmp_advname = $tmp_advname . get_advocates_new($row_advp['advocate_id'], '') . $row_advp['adv'] . $ac_text;
                                                //                if ($row_advp[advocate_id] != '')
                                                //                    $tmp_advname = $tmp_advname . " [" . $row_advp[2] . "/" . $row_advp[3] . "]";
                                                //                if ($row_advp[0] > 0)
                                                //                    $tmp_advname = $tmp_advname . " [".$row_advp[4]."-" . $row_advp[0] . "]";
                                                $tmp_advname = $tmp_advname . "</p>";

                                                if ($row_advp['pet_res'] == "P")
                                                    $padvname .= $tmp_advname;
                                                if ($row_advp['pet_res'] == "R")
                                                    $radvname .= $tmp_advname;
                                                if ($row_advp['is_ac'] == 'Y' && ($row_advp['pet_res'] == '' || empty($row_advp['pet_res']) || $row_advp['pet_res'] == null))
                                                    $ac_court .= $tmp_advname;
                                            }
                                        }
                                        
                                        // Check if the result exists
                                        if (!empty($law_row)) {
                                            // Output the 'law' column from the first row
                                            echo $law_row['law'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Amicus Curie(For Court Assistance)
                                    </td>
                                    <td>
                                        <?php echo $ac_court; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">
                                        Petitioner Advocate
                                    </td>
                                    <td>
                                        <?php echo $padvname; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Respondant Advocate
                                    </td>
                                    <td>
                                        <?php echo $radvname; ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Last Order
                                    </td>
                                    <td>
                                        <?php echo $lastorder; ?>
                                    </td>
                                </tr>
                                <?php
                                if ($c_status == 'P') 
                                {

                                    $result_array = $flag_tentative_listing_date;

                                    // echo "date    ".(date('Y-m-d',strtotime($result_remarks['cl_date'].'+'.$result_remarks['head_content'].'days'))>=strtotime(date('Y-m-d')));
                                    //var_dump($result_array);
                                    if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))) 
                                    {
                                        if (!empty($result_next_dt['next_dt']) and $result_next_dt['next_dt'] > date('Y-m-d')) 
                                        {
                                            ?>
                                            <tr>
                                                <td>
                                                    Matter is proposed to be listed on
                                                </td>
                                                <td>
                                                    <font color="red"> <?= change_date_format($result_next_dt['next_dt']); ?></font>
                                                </td>
                                            </tr>
                                            <tr>
                                            <?php 
                                        }

                                        if (!empty($result_remarks) && $result_remarks['r_head'] == 133 and date_diff($result_remarks['cl_date'], date('Y-m-d')) <= $result_remarks['head_content']) 
                                        { 
                                            ?>
                                            <tr>
                                                <td>
                                                    Court's Last Order
                                                </td>
                                                <td>
                                                    <font color="red">
                                                        Peremptory order was ordered on <?= change_date_format($result_remarks['cl_date']) ?> for <?= $result_remarks['head_content'] ?> days which will expire on <?= date('d-m-Y', strtotime($result_remarks['cl_date'] . '+' . $result_remarks['head_content'] . 'days')) ?>
                                                    </font>
                                                </td>
                                            </tr>
                                            <?php 
                                        } 
                                        
                                        if (!empty($r_ttv)) 
                                        { ?>
                                        <td>
                                            Tentative Date
                                        </td>
                                        <td>
                                            <?php
                                            //$tentative_date = (!empty( $r_ttv['tentative_cl_dt'])) ? $r_ttv['tentative_cl_dt'] : '';
                                            $tentative_date = @$r_ttv['tentative_cl_dt'];
                                            echo change_date_format($tentative_date);
                                            ?>
                                        </td>
                                        <?php } ?>
                                        </tr>
                                        <?php
                                    } elseif(!empty($r_ttv)) {
                                        // for users except always_allowed_users list
                                        if (get_display_status_with_date_differnces(@$r_ttv['tentative_cl_dt']) == 'T') 
                                        { ?>
                                            <tr>
                                                <td>
                                                    Tentative Date
                                                </td>
                                                <td>
                                                    <?php
                                                    $tentative_date = @$r_ttv['tentative_cl_dt'];
                                                    echo change_date_format($tentative_date);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    
                                    } else {
                                        ?>

                                    <tr>
                                        <td>
                                            Case Status
                                        </td>
                                        <td>
                                            <?php echo '<font color="red" style="font-weight:bold;font-size:14px;">Case is Disposed</font>'; ?>
                                        </td>
                                    </tr>

                                    <?php } ?>
                                    <?php
                                }
                                ?>
                            </table>
                        <?php
                        } 
                        else
                        { 
                            echo '<div class="cl_center"><b>No Record Found</b></div>';
                            
                        } 
                    
                       //Listing Start
                       $listed_ia = '';
                       $subhead = "";
                       $next_dt = "";
                       $lo = "";
                       $sj = "";
                       $bt = "";
                       $sno = 0;

                       if (!empty($result_listing)) 
                       {
                       ?>
                           <div class="cl_center">
                               <h3>Listing Details</h3>
                           </div>
                           <?php

                           $t_table = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                           $t_table .= "<tr><td align='center'><b>CL Date</b></td><td><b>Misc./Regular</b></td><td><b>Stage</b></td><td><b>Purpose</b></td><td align='center'><b>Listed in</b></td><td align='center'><b>Judges</b></td><td><b>IA</b></td><td><b>Remarks</b></td><td></td></tr>";

                           date_default_timezone_set('GMT');
                           $temp = strtotime("+5 hours 30 minutes");
                           foreach ($result_listing as $row_listing) {
                               $listed_ia = (!empty($row_listing['listed_ia'])) ? $row_listing['listed_ia'] : '';
                               if ($row_listing['mainhead'] == "M")
                                   $t_mainhead = "Misc.";
                               if ($row_listing['mainhead'] == "F")
                                   $t_mainhead = "Regular";
                               if ($row_listing['mainhead'] == "L")
                                   $t_mainhead = "Lok Adalat";
                               if ($row_listing['mainhead'] == "S")
                                   $t_mainhead = "Mediation";
                               $t_stage = "";
                               $subhead = $row_listing['subhead'];
                               if ($row_listing['mainhead'] == "M") {
                                   $t_stage = get_stage($row_listing['subhead'], 'M');
                                   //$t_stage.=get_stage($row_listing['sub_cat'],'F');
                               }
                               if ($row_listing['mainhead'] == "F") {
                                   //$t_stage=get_stage($row_listing['sub_cat'],'F');
                                   $t_stage = get_stage($row_listing['subhead'], 'F');
                               }
                               $next_dt = $row_listing['next_dt'];
                               $lo = $row_listing['listorder'];
                               $sj = $row_listing['sitting_judges'];
                               $bt = $row_listing['board_type'];

                               if ($bt == 'J') {
                                   $bt = 'Court';
                                   // $bt1='J';
                               } else if ($bt == 'C') {
                                   $bt = 'Chamber';
                                   // $bt1='C';
                               } else if ($bt == 'R') {
                                   $bt = 'Registrar';
                                   // $bt1='R';
                               } else {
                                   $bt = '';
                                   $bt1 = '';
                               }
                               if ($row_listing['main_supp_flag'] == "1" or $row_listing['main_supp_flag'] == "2") {
                                   $judgesnames = get_judges($row_listing['judges']);
                               } else {
                                   $judgesnames = "";
                               }

                               $row_cr = $row_listing['row_cr'];

                               // Check if result exists
                               if (!empty($row_cr)) {
                                   $cval = $row_cr['caseval'];
                                   $crem = $row_cr['crem'];
                               } else {
                                   $cval = '';
                                   $crem = '';
                               }

                               $row_lp123 = $row_listing['row_lp123'];

                               // Check if result exists
                               if (!empty($row_lp123)) {

                                   if ($row_lp123["username"] == "" and $row_lp123["dacode"] == "")
                                       $output1 = "0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $row_lp123["empid"];
                                   else if ($row_lp123["username"] == "" and ($row_lp123["dacode"] != $ucode))
                                       $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA USER ID : " . $row_lp123["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $row_lp123["dacode"];
                                   else if ($row_lp123["dacode"] != $ucode)
                                       $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $row_lp123["username"] . " [USER ID : " . $row_lp123["empid"] . "]|#|" . $row_lp123["dacode"];
                                   else
                                       $output1 = "1|#|RIGHT DA|#|" . $row_lp123["dacode"];
                               }

                               $result_da = explode("|#|", $output1);

                               $t_pd_button = "";

                               $result_array = $flag_case_updation;

                               //Added on 14.03.2019 by preeti to allow CM to change remarks for 2 days then DA for 3rd and 4th day and after that DMT or Listing Section
                               $reslt_validate_caseInAdvanceList = $row_listing['reslt_validate_caseInAdvanceList'];
                               $sno += 1;
                               $dateDiff = round((strtotime(date('Y-m-d')) - strtotime($row_listing['next_dt'])) / (60 * 60 * 24));
                               $users_to_ignore = array(1, 559, 146);
                               // $last_listing_date = $row_listing['next_dt'];

                               $row_working_dates = $row_listing['row_working_dates'];
                               
                               $working_dates = $row_working_dates['dates'];
                               $working_date = explode(',', $working_dates);

                               $results = $row_listing['row_connected_cases'];

                               $connected = '';
                               foreach ($results as $row_connected) {
                                   $connected .= $row_connected['num'] . ",";
                               }

                               //$connected=$row_listing['connected'];  // added on 17122019 to show connected matters from last heardt also
                               //echo "diff".(strtotime("13:30:00") - strtotime(date("H:i:s", $temp)));
                               if ($c_status == 'P') {
                                   if ((strtotime($row_listing['next_dt']) < strtotime('2019-04-02'))) {  // before implementation of the modified code, no checking


                                       if ($result_da[0] > 0 or ($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) {
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                       }
                                   } else if (strtotime($row_listing['next_dt']) <= strtotime(date('Y-m-d'))) {
                                       if ($dateDiff == 0 and ($usection == '11' or $usection == '62' or $usection == '81' or $is_courtMaster == 'Y') and (strtotime("16:30:00") - strtotime(date("H:i:s", $temp))) > 0 /*and !(in_array($ucode,$users_to_ignore))*/) {
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                       }
                                       /*
                                           else if ((strtotime(date('Y-m-d')) == strtotime($row_listing['next_dt'])) and $usection == '11' and (strtotime("16:30:00") - strtotime(date("H:i:s", $temp))) < 0) {
                                               $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>Today Updation can be done by concerned Court Master upto 4:30 PM. After that contact Listing Section.</font></b></center>";
                                           } else if ((strtotime(date('Y-m-d')) == strtotime($working_date[0])) and $usection == '11') {  // if date diff=2 and CM
                                           */ else if (strtotime(date('Y-m-d')) == strtotime($row_listing['next_dt']) and ((strtotime("19:00:00") - strtotime(date("H:i:s", $temp))) < 0)) {  // if current date and after 7PM
                                           $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>Updation cannot be done after 7PM on the date of listing.</font></b></center>";
                                       } else if (((strtotime(date('Y-m-d')) == strtotime($working_date[0])) or (strtotime(date('Y-m-d')) == strtotime($row_listing['next_dt']))) and ($usection == '11' or $usection == '62' or $usection == '81' or $is_courtMaster == 'Y')) {  // if CM
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                       } else if ((strtotime(date('Y-m-d')) == strtotime($row_listing['next_dt']) or (strtotime(date('Y-m-d')) == strtotime($working_date[0]))) and ($usection != '11' and $usection != '62' and $usection != '81' and $is_courtMaster == 'N')) { // if datediff=2 and not CM
                                           $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>Updation can be done by concerned Court Master for 2 days from date of listing.</font></b></center>";
                                       } // to modify
                                       else if ((strtotime(date('Y-m-d')) == strtotime($working_date[1]) or (strtotime(date('Y-m-d')) == strtotime($working_date[2]))) and $ucode == $row_lp123["dacode"]) { // if datediff>=2 and <4 and right DA
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                       } else if ((strtotime(date('Y-m-d')) == strtotime($working_date[1]) or (strtotime(date('Y-m-d')) == strtotime($working_date[2]))) and $ucode != $row_lp123["dacode"]) { // if datediff>=2 and <4 and not right DA
                                           $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>Updation can be done by concerned Dealing Assistant only for 2 days after 2 days of listing.</font></b></center>";
                                       } else if ((strtotime(date('Y-m-d')) >= strtotime($working_date[3])) and ($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { //if datediff>4 and login user is from DMT or Listing
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $connected . "\"," . $sno . ")'/>";
                                       } /*else if($dateDiff<0)    //if next date is of future date
                                           {
                                               $t_pd_button="";
                                           }*/ else if ($reslt_validate_caseInAdvanceList == true && !($result_array['display_flag'] == '1' || in_array($ucode, explode(',', $result_array['always_allowed_users'])))) { // if case is in Advance List
                                           $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>Case Listed in Advance List, Contact Data Monitoring Team or Listing Section</font></b></center>";
                                       } else { // default
                                           $t_pd_button = "<center><b><font color='red' style='font-size:16px;'>You cannot update remarks.Contact Court Master for 2 days from date of listing then DA for next 2 days and then Data Monitoring Team or Listing section.</font></b></center>";
                                       }
                                   }
                               }
                               /*else if((strtotime($row_listing['next_dt']) <= strtotime(date('Y-m-d'))) and in_array($ucode,$users_to_ignore)){
                                           $t_pd_button = "<input class='pdbutton' type='button' name='btnpnd_$sno' id='btnpnd_$sno' value='P' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,1,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $row_listing['connected'] . "\"," . $sno.",\"" .$bt1. "\")'/>";
                                           $t_pd_button .= "<input class='pdbutton' type='button' name='db_$sno' id='db_$sno' value='D' onclick='call_div(\"" . $row_listing['brd_slno'] . "\",\"" . $row_listing['diary_no'] . "\",this,2,\"" . date('d-m-Y', strtotime($row_listing['next_dt'])) . "\",\"" . $cval . "\",\"" . $row_listing['judges'] . "\",\"" . $row_listing['mainhead'] . "\",\"" . $row_listing['hl'] . "\"," . "\"" . $row_listing['connected'] . "\"," . $sno.",\"" . $bt1."\")'/>";
                                       }*/
                               //end
                               //}

                               $t_table .= "<tr><td align='center'>" . change_date_format($row_listing['next_dt']) . "</td><td>" . $t_mainhead . "</td><td>" . $t_stage . "</td><td>" . get_purpose($row_listing['listorder']) . "</td><td align='center'>" . $bt . "</td><td align='center'>" . $judgesnames . "</td><td align='center'>" . $row_listing['listed_ia'] . "</td><td>$crem</td><td>$t_pd_button</td></tr>";
                           }

                           $t_table .= "</table>";
                       }
                       echo $t_table;
                       //Listing End
                       //IAN
                       $iancntr = 1;
                       $ian_p = "";
                       $ian = "";
                       if (count($results_ian) > 0) 
                       {
                           ?>
                           <div class="cl_center">
                               <h3>INTERLOCUTARY APPLICATIONS</h3>
                           </div>
                           <?php
                           foreach ($results_ian as $row_ian) {
                               if ($ian_p == "" and $row_ian["iastat"] == "P") {
                                   $ian_p = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                                   $ian_p .= "<tr><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                               }
                               if ($iancntr == 1) {
                                   $ian = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                                   $ian .= "<tr><td align='center' width='30px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
                               }
                               if ($row_ian["other1"] != "")
                                   $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                               else
                                   $t_part = $row_ian["docdesc"];
                               $t_ia = "";
                               if ($row_ian["iastat"] == "P")
                                   $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                               if ($row_ian["iastat"] == "D")
                                   $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                               $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";
                               if ($row_ian["iastat"] == "P") {
                                   $t_iaval = $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                                   if (strpos($listed_ia, $t_iaval) !== false)
                                       $check = "checked='checked'";
                                   else
                                       $check = "";
                                   $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();'  " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
                               }
                               $iancntr++;
                           }
                       }
                       if ($ian != "")
                           $ian .= "</table><br>";
                       if ($ian_p != "")
                           $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";
                       echo $ian;
                       //IA END
                       //OTHER DOCUMENTS
                       $odcntr = 1;
                       if (count($results_od) > 0) 
                       {
                           ?>
                           <div class="cl_center">
                               <h3>DOCUMENTS FILED</h3>
                           </div>
                           <?php
                           foreach ($results_od as $row_od) {
                               if ($odcntr == 1) {
                                   $oth_doc = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                                   $oth_doc .= "<tr><td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                               }
                               if (trim($row_od["docdesc"]) == 'OTHER')
                                   $docdesc = $row_od["other1"];
                               else
                                   $docdesc = $row_od["docdesc"];
                               if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0)
                                   $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                               else
                                   $doc_oth = '';
                               $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td></tr>";
                               $odcntr++;
                           }
                           if ($oth_doc != "")
                               $oth_doc .= "</table><br>";
                       }
                       echo $oth_doc;
                       //OTHER DOCUMENTS

                       //connected cases
                       $conncases = $get_conn_cases;
                       if (count($conncases) > 0) 
                       {
                           ?>
                           <div class="cl_center">
                               <h3>CONNECTED / LINKED CASES</h3>
                           </div>
                           <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                               <tr>
                                   <td align='center' width='30px'><b>S.N.</b></td>
                                   <td><b>Case No.</b></td>
                                   <td><b>M/C/L</b></td>
                                   <td><b>Petitioner Vs. Respondant</b></td>
                                   <td><b>Case Category</b></td>
                                   <td align='center'><b>Status</b></td>
                                   <td align='center'><b>Before/Not Before</b></td>
                                   <td align='center'><b>List</b></td>
                                   <td><b>DA</b></td>
                               </tr>
                               <?php
                               $connchks = "<table class='table_tr_th_w_clr c_vertical_align'  width='100%'><tr><td align='center' colspan='5'><font color='red'><b>CONNECTED CASES</b></font></td></tr>";
                               $connchks .= "<tr><td align='center' width='30px'><b></b></td><td><b>Case No.</b></td><td><b>Petitioner Vs. Respondant</b></td><td align='center'><b>Status</b></td><td><b>IA</b></td></tr>";

                               $sn = 0;
                               foreach ($conncases as $row => $link) {
                                   if ($link['c_type'] != "") {

                                       $sn++;
                                       $main_details = $link['get_main_details'];
                                       if (is_array($main_details)) {
                                           foreach ($main_details as $rowm => $linkm) {
                                               $t_pname = $linkm['pet_name'];
                                               $t_rname = $linkm['res_name'];
                                               $t_status = $linkm['c_status'];
                                               if ($link["list"] == "Y")
                                                   $chked = "checked";
                                               else
                                                   $chked = "";
                                               if ($linkm['c_status'] == "D")
                                                   $chked = " disabled=disabled";
                                           }
                                       }
                                       $t_brdrem = $link['get_brd_remarks'];
                                       $t_conn_type = "";
                                       if ($link['c_type'] == "M") {
                                           $t_conn_type = "Main";
                                       }
                                       if ($link['c_type'] == "C") {
                                           $t_conn_type = "Connected";
                                       }
                                       if ($link['c_type'] == "L") {
                                           $t_conn_type = "Linked";
                                       }
                                       //                        $t_current_proposed=str_replace('|#|','<br>',get_listing_dates($link['diary_no']));
                                       echo "<tr><td align='center' width='30px'>" . $sn . "</td><td>" . $link['get_real_diaryno'] . "</td><td>" . $t_conn_type . "</td><td>" . $t_pname . " Vs. " . $t_rname . "</td><td>" . $link['get_mul_category'] . "</td><td align='center'>" . $t_status . "</td><td align='center'></td><td align='center'>" . $link["list"] . "</td><td></td></tr>";
                                       if ($link['c_type'] != "M") {
                                           $connchks .= "<tr><td align='center'><input type='checkbox' name='ccchk" . $link['diary_no'] . "' id='ccchk" . $link['diary_no'] . "' value='" . $link['diary_no'] . "' " . $chked . " ></td><td>" . $link['get_real_diaryno'] . "</td><td>" . $t_pname . " Vs. " . $t_rname . "</td><td align='center'>" . $t_status . "</td><td><input type='hidden' name='brdremh_" . $link['diary_no'] . "' id='brdremh_" . $link['diary_no'] . "' value=" . $t_brdrem . "><textarea style='width:95%' name='brdrem_" . $link['diary_no'] . "' id='brdrem_" . $link['diary_no'] . "' rows='3'>" . $t_brdrem . "</textarea>" . $link['get_ia'] . "</td></tr>";
                                       }
                                   }
                               }
                               $connchks .= "</table>";
                               ?>
                           </table>
                       <?php
                       }
                       //connected cases
                       ?>
                       <?php
                           // }
                       ?>
                       <input type="hidden" name="sh" id="sh" value="<?php print $subhead ?? ''; ?>" />
                       <input type="hidden" name="da_hidden" id="da_hidden" value="<?php echo ''; ?>" />
                       <input type="hidden" name="ucode" id="ucode" value="<?php echo $ucode; ?>" />
                       <input type="hidden" name="dtd" id="dtd" value=""/>
                       <input type="hidden" name="clno" id="clno" value="">
                       <input type="hidden" name="mh" id="mh" value="">
                       <input type="hidden" name="jcodes" id="jcodes" value="">
                       <input type="hidden" name="old_new" id="old_new" />
                       <input type="hidden" name="sno" id="sno" />
                       <?= csrf_field() ?>
                       <?
                       $nm_cd = array();
                       $tot_next_dt = '';
                       ?>

                       <input type="hidden" name="tot_next_dt" id="tot_next_dt" value="<?php echo $tot_next_dt; ?>" />
                   </div>
               </div>
           </div>
       </div>
   </div>
</section>
<!-- /.content -->
<!-- The Modal -->
<div class="modal" id="model-pending-remarks" data-bs-backdrop='static' data-bs-keyboard="false">
 <div class="modal-dialog modal-xl">
   <div class="modal-content">

     <!-- Modal Header -->
     <div class="modal-header" style="width: 100%;">
       <div class="row w-100">
           <div class="col-6">
               <h4 class="modal-title text-left">Pending Remark</h4>
           </div>
           <div class="col-3 text-right">
           <input type='button' name='insert1' id='insert1' value="Save" onClick="return save_rec(1);">                
           </div>
           <div class="col-3">
               <input type="button" name="close1" id="close1" value="Cancel" onClick="close_w(1); enable_buttons();">
           </div>
       </div>
     </div>

     <!-- Modal body -->
     <div class="modal-body">

       <!-- <div id="newb" style="overflow:auto;background-color: #fff;"> -->
       <div id="newb123" style="overflow:auto;">
           <table class='table_tr_th_w_clr c_vertical_align table_pad' width="100%" border="1" style="border-collapse: collapse">
               <!-- Added by Preeti on 26.3.2019 to show proposed in row in case of chamber and registrar court -->
               <tr>
                   <td><input type="hidden" name="prvCourt" id="prvCourt" value=""></td>
               </tr>

               <tr id="R" style="display:none;">
                   <td><b>Proposed to be Listed in</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <input type="radio" name="nextCourt" id="nextCourt" value="J">Court
                       <input type="radio" name="nextCourt" id="nextCourt" value="C">Chamber
                       <input type="radio" name="nextCourt" id="nextCourt" value="R">Registrar
                   </td>
               </tr>
               <tr id="C" style="display:none;">
                   <td><b>Proposed to be Listed in</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <input type="radio" name="nextCourt" id="nextCourt" value="J">Court
                       <input type="radio" name="nextCourt" id="nextCourt" value="C">Chamber
                   </td>
               </tr>

               <!-- End -->
               <!--                            <tr>
                                   <td align="center"  style="background-color: #fefefe">
                                       <b><span id="pend_head"></span></b>
                                   </td>
                                   <td align="center"  style="background-color: #fefefe">
                                       <b><span id="pend_head1"></span></b>
                                   </td>
                               </tr>-->
               <?php
               $t11 = $caseRemarksHeadPending;
               $ttl_records = count($t11);  // Get the total number of records
               
               if ($ttl_records > 0) {

                   $snoo = 1;
                   $chkcnt = 0;
                   $chkhead = "";
                   $hcntr = 0;
                   foreach ($t11 as $row11) {
                       // if ($chkhead != $row11["category"]) {
                       if ($snoo > ($ttl_records / 2) and $chkcnt == 0) {
                           $chkcnt++;
                           echo "</table></td><td width='50%' style='vertical-align:top;'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                       }
                       if ($snoo == 1)
                           echo "<tr valign='top'><td width='50%' style='vertical-align:top;'><br><table border=0 style='border-collapse: collapse' width='98%'>";
                       if ($row11["cat_head_id"] == 1000 and $hcntr == 0) {
               ?>
                           <tr>
                               <td colspan="2"><b>
                                       <font color="#F9FBFD">NEGATIVE OFFICE REMARK</font>
                                   </b></td>
                           </tr>
                       <?php
                           $hcntr++;
                       }
                       // $chkhead = $row11["category"];
                       //}
                       //                                    if (($snoo % 2) == 0)
                       //                                        $bgc = "#ECF1F7";
                       //                                    else
                       //                                        $bgc = "#F8F9FC";
                       // echo $t_mh.$row11['sno'];
                       if (!($t_mh == "M" and ($row11['sno'] == 150 or $row11['sno'] == 151))) {
                       ?>
                           <tr>
                               <td align="left">
                                   <input class="cls_chkp" type="checkbox" name="chkp<?php echo $row11['sno']; ?>" id="chkp<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>" />
                                   <label class="lblclass" for="chkp<?php echo $row11['sno']; ?>"><?php
                                                                                                   echo $row11['head'];
                                                                                                   if ($row11['sno'] == 21 or $row11['sno'] == 59 or $row11['sno'] == 70 or $row11['sno'] == 131 or $row11['sno'] == 91)
                                                                                                       echo " (Date)";
                                                                                                   ?></label>
                                   <?php if ($row11['sno'] == 72) { ?><ruby> <strong> (Type Proper Case No and Separate By ',')</strong> </ruby><?php } ?>
                               </td>
                               <td nowrap>
                                   <?php
                                   $int_array = array(23, 25, 53, 54, 68, 122, 123, 133, 144, 149, 181, 204, 205, 190); //Array of sno of remark heads on which integer input required
                                   if (in_array($row11['sno'], $int_array)) {
                                       $check_var = "NUM";
                                       $check_var1 = "<font color=red style='font-size:x-small;'>(NUM)</font>";
                                   } else {
                                       $check_var = "ALPHANUM";
                                       $check_var1 = "";
                                   }
                                   if ($row11['sno'] == 22 or $row11['sno'] == 26 or $row11['sno'] == 95 or $row11['sno'] == 142) {
                                   ?>
                                       <div id="hdremp<?php echo $row11['sno'] . '_div'; ?>"></div>
                                       <input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" disabled="disabled" onkeypress="return remarks_input_validate(event,'<?php print $check_var; ?>');" />
                                       <?php
                                   } //added
                                   else {
                                       if ($row11['sno'] == 91) {
                                       ?>
                                           <input type="button" name="partybutton" id="partybutton" value="PARTY" onclick="make_party_div();" disabled="disabled" />&nbsp;<input size=8 type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onkeypress="return remarks_input_validate(event,'<?php print $check_var; ?>');" />
                                       <?php
                                       } elseif ($row11['sno'] == 149) {
                                       ?>
                                           <input type="button" name="partybutton1" id="partybutton1" value="PARTY" onclick="make_party_div_popup();" disabled="disabled" />&nbsp;<input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" />
                                       <?php
                                       } else if ($row11['sno'] == 190 or $row11['sno'] == 181 or $row11['sno'] == 204 or $row11['sno'] == 205) { ?>
                                           Day<input type="text" name="hdremp<?php echo $row11['sno']; ?>_1" id="hdremp<?php echo $row11['sno']; ?>_1" value="" style="width:20px;" maxlength="2" onkeypress="return remarks_input_validate(event,'<?php print $check_var; ?>');" />
                                           Week<input type="text" name="hdremp<?php echo $row11['sno']; ?>_2" id="hdremp<?php echo $row11['sno']; ?>_2" value="" style="width:20px;" maxlength="2" onkeypress="return remarks_input_validate(event,'<?php print $check_var; ?>');" />
                                           Mon.<input type="text" name="hdremp<?php echo $row11['sno']; ?>_3" id="hdremp<?php echo $row11['sno']; ?>_3" value="" style="width:20px;" maxlength="1" onkeypress="return remarks_input_validate(event,'<?php print $check_var; ?>');" />
                                       <?php
                                       } else if ($row11['sno'] == 180) {
                                       ?>
                                           <select id="hdremp<?php echo $row11['sno']; ?>" name="hdremp<?php echo $row11['sno']; ?>">
                                               <!--<option value="ANY" selected="selected">Any</option>-->
                                               <option value="TUESDAY">Tuesday</option>
                                               <!-- <option value="WEDNESDAY">Wednesday</option> commented on 8.4.2024 by preeti as per order
                                                               <option value="THURSDAY">Thursday</option>-->
                                           </select>

                                       <?php } else if ($row11['sno'] == 5) {
                                       ?>
                                           <input type="hidden" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" /><?php print $check_var1; ?>
                                       <?php
                                       } else if ($row11['sno'] == 186) {
                                       ?>
                                           <select id="hdremp<?php echo $row11['sno']; ?>" name="hdremp<?php echo $row11['sno']; ?>" multiple>
                                               <option value="" disabled>Select one or more judge(s)</option>
                                               <?php foreach ($sql_judges as $list) { ?>
                                                   <option value="<?php echo 'HMJ ' . $list['first_name'] . ' ' . $list['sur_name'] . '(' . $list['jcode'] . ')'; ?>"><?php echo $list['jname']; ?></option>
                                               <?php } ?>
                                           </select>
                                       <?php
                                       } else {
                                       ?>
                                           <input type="text" name="hdremp<?php echo $row11['sno']; ?>" id="hdremp<?php echo $row11['sno']; ?>" value="" onkeypress="return remarks_input_validate(event, '<?php print $check_var; ?>');" /><?php print $check_var1; ?>

                                   <?php
                                       }
                                   }
                                   ?>
                                   <input type="hidden" name="hdp<?php echo $row11['sno']; ?>" id="hdp<?php echo $row11['sno']; ?>" />
                                   <input type="hidden" name="srvr" id="srvr" value="<?php echo date('Y'); ?>" />
                               </td>
                           </tr>
               <?php
                           $snoo++;
                       }
                   } // while end
               }
               ?>
           </table>
           </td>
           </tr>
           </table>
       </div>
       <div id="newb111" style="background-color:#b7b7b7;overflow:auto;border-collapse: collapse;"></div>

       <!-- </div> -->

     </div>
     <!-- Modal footer -->
     <div class="modal-footer">
       <input type="hidden" name="tmp_casenop" id="tmp_casenop" value="" />
       <input type="hidden" name="listing_date" id="listing_date" value="" />
       <input type="hidden" name="connected" id="connected" value="" />
       <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
     </div>

   </div>
 </div>
</div>

<!-- The Modal -->
<div class="modal" id="model-dispose" data-bs-backdrop='static'>
 <div class="modal-dialog modal-xl">
   <div class="modal-content">

     <!-- Modal Header -->
     <div class="modal-header" style="width: 100%;">
       <div class="row w-100">
           <div class="col-6">
               <h4 class="modal-title text-left">Disposal Remark</h4>
           </div>
           <div class="col-3 text-right">
               <input type='button' name='insert3' id='insert3' value="Save" onClick="return save_rec(2);" />
           </div>
           <div class="col-3">
               <input type="button" name="close3" id="close3" value="Close" onClick="return close_w(2)" />
           </div>
       </div>
     </div>

     <!-- Modal body -->
     <div class="modal-body">

       <!-- <div id="newc" style="overflow:auto;background-color: #fff;"> -->

       <div id="newc123" style="overflow:auto;">
           <table class='table_tr_th_w_clr c_vertical_align table_pad' width="100%">
               <tr>
                   <td colspan="4">&nbsp;</td>
               </tr>
               <tr style="background-color: #999999;">
                   <td class="text-right"><b>Hearing Date : </b></td>
                   <td><input type="text" name="hdate" id="hdate" value="" size="15"></td>
                   <td colspan="2"></td>
               </tr>
               <?php
               //FOR LOK ADALAT
               $t11 = $caseRemarksHeadDisposed;

               if (count($t11) > 0) {
                   $snoo = 1;
                   $chkhead = "";
                   foreach ($t11 as $row11) {
                       //                                    if ($chkhead != $row11["category"]) {
                       //                                        
               ?>
                       <!--<tr><td colspan="4" align="center"><b>//<?php // echo $row11["category"]; 
                                                                   ?></b></td></tr>-->
                       <?php
                       //                                        $chkhead = $row11["category"];
                       //                                    }
                       //                                    if (($snoo % 2) == 0)
                       //                                        $bgc = "#ECF1F7";
                       //                                    else
                       //                                        $bgc = "#F8F9FC";
                       ?>
                       <tr>
                           <td width="25%">&nbsp;</td>
                           <td width="400px" align="left">
                               <input class="cls_chkd" type="checkbox" name="chkd<?php echo $row11['sno']; ?>" id="chkd<?php echo $row11['sno']; ?>" value="<?php echo $row11['sno'] . "|" . $row11['head']; ?>" />
                               <label class="lblclass" for="chkd<?php echo $row11['sno']; ?>"><?php echo $row11['head']; ?></label>
                           </td>
                           <td width="200px">
                               <?php
                               if ($row11['sno'] == 144) {
                               ?>
                                   <input type="text" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value="" onBlur="textformate(<?php echo $row11['sno']; ?>);" />
                               <?php
                               } else {
                               ?>
                                   <input type="text" name="hdremd<?php echo $row11['sno']; ?>" id="hdremd<?php echo $row11['sno']; ?>" value="" />
                               <?php
                               }
                               ?>
                               <input type="hidden" name="hdd<?php echo $row11['sno']; ?>" id="hdd<?php echo $row11['sno']; ?>" />
                           </td>
                           <td width="25%">&nbsp;</td>
                       </tr>
               <?php
                       $snoo++;
                   } // while end
               }
               ?>
           </table>
       </div>

       <div id="newc111" style="background-color:#b7b7b7;overflow:auto;border-collapse: collapse;"></div>
   
       <!-- </div> -->

     </div>
     <!-- Modal footer -->
     <div class="modal-footer">
           <input type="hidden" name="tmp_casenod" id="tmp_casenod" value="" />
           <input type="hidden" name="tmp_casenosub" id="tmp_casenosub" value="" />
           <input type="hidden" name="connected_d" id="connected_d" value="" />

           <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
     </div>

   </div>
 </div>
</div>
   <?php
   $doc_num_yr = '';
   $s_s_no = 1;

   // Initialize variables
   $ian = "";
   $ian_p = "";
   $iancntr = 1;
   $t_iaval = '';

   foreach ($results_ian as $row_ian) {
       if ($ian_p == "" and $row_ian["iastat"] == "P") {
           $ian_p = "<table border='1' bgcolor='#F5F5FC' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
           $ian_p .= "<tr bgcolor='#EAEAF9'><td align='center' colspan='4'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
           $ian_p .= "<tr bgcolor='#EEEEFA'><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
       }
       if ($iancntr == 1) {
           $ian = "<table border='1' bgcolor='#F5F5FC' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
           $ian .= "<tr bgcolor='#EAEAF9'><td align='center' colspan='6'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
           $ian .= "<tr bgcolor='#EEEEFA'><td align='center' width='30px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
       }
       if ($row_ian["other1"] != "")
           $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
       else
           $t_part = $row_ian["docdesc"];
       $t_ia = "";
       if ($row_ian["iastat"] == "P")
           $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
       if ($row_ian["iastat"] == "D")
           $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
       $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";
       if ($row_ian["iastat"] == "P") {
           $t_iaval .= $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
           if (strpos($listed_ia, $t_iaval) !== false)
               $check = "checked='checked'";
           else
               $check = "";
           $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();' disabled=disabled checked=checked " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
       }
       $iancntr++;
   }
   if ($ian != "")
       $ian .= "</table><br>";
   if ($ian_p != "")
       $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";
   ?>

   <input type="hidden" name="ian<?php echo $filling_no ?>" id="ian<?php echo $filling_no ?>" value="<?php echo $t_iaval; ?>" />
   <input type="hidden" name="hdate" id="hdate" />
   <input type="hidden" name="ian_cx<?php echo $filling_no ?>" id="ian_cx<?php echo $filling_no ?>" value="<?php echo $doc_num_yr; ?>" />

   <input type="hidden" name="hd_curr_dt" id="hd_curr_dt" value="<?php echo date('d-m-Y'); ?>" />
   <input type="hidden" name="caseval<?php echo $filling_no ?>" id="caseval<?php echo $filling_no ?>" />
   <b><span style="display: none" id="cs<?php echo $filling_no; ?>">
       </span></b>
   <?php
   // $sq_mn = mysql_query("Select pet_name,res_name,date(fil_dt) fil_dt from main where diary_no='" . $diary_no['diary_no'] . "'") or die("Error: " . __LINE__ . mysql_error());
   // $main_row = mysql_fetch_array($sq_mn);
   ?>
   <span id="pn<?php echo $filling_no; ?>" style="background-color:#F0E9F9;display: none"><?php //echo $main_row['pet_name']  
                                                                                                       ?></span>
   <span id="rn<?php echo $filling_no; ?>" style="background-color:#F9EBEB;display: none"><?php //echo $main_row['res_name']  
                                                                                                       ?></span>
   <input type="hidden" name="o_d" id="o_d" />
   <span id="jcodes<?php echo $filling_no; ?>" style="display:none;"></span>
   <span id="mainhead<?php echo $filling_no; ?>" style="display:none;"></span>
   <input type="hidden" name="brd<?php echo $filling_no; ?>" id="brd<?php echo $filling_no; ?>" />
   <span id="sp_ffno<?php echo $filling_no; ?>" style="font-size: 12px"></span>

   <input type="hidden" name="hd_fil_dt_dt" id="hd_fil_dt_dt" value="" />

   <script>
       var excluded_dates=<?php echo json_encode($holiday_dates) ?>;
       $(document).on("focus",".dtp",function(){
           $('.dtp').datepicker({
               format: 'dd-mm-yyyy',
               autoclose: true,
               todayHighlight: true,
               changeMonth : true, 
               changeYear : true,
               minDate:'+1',
               yearRange : '-0:+1',
               datesDisabled: excluded_dates,
               isInvalidDate: function(date) {
                   return (date.day() == 0 || date.day() == 6);
               },
           });
       });
   </script>


   <?php
   //            $sql2 = "SELECT skey, nature FROM casetype WHERE casecode=" . intval(substr($diary_no['diary_no'], 2, 3)) . " and display='Y'";
   //            $results1 = mysql_query($sql2);
   //            $row11 = mysql_fetch_array($results1);
   //            $nature = $row11[1];
   //
   //            $sql_lp1 = "SELECT code, CONCAT(code,'. ',purpose) AS lp FROM listing_purpose WHERE code!=22 and purpose!='NULL' AND display='Y' ORDER BY code";
   //            $results_lp1 = mysql_query($sql_lp1);
   //            if (mysql_affected_rows() > 0) {
   //                while ($row_lp1 = mysql_fetch_array($results_lp1)) {
   //
   //                    echo '<option value="' . $row_lp1["code"] . '">' . $row_lp1["lp"] . '</option>';
   //                }
   //            }



   echo '<br>';


   function get_jc_jnm()
   {
       $sq_y = mysql_query("Select jcode,jname from judge where display='Y' order by judge_seniority");
       while ($row1 = mysql_fetch_array($sq_y)) {
           $nm_cd[] = $row1['jcode'] . '^' . $row1['jname'];
       }
       return $nm_cd;
    }