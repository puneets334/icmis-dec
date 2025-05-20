<style>
    table tr:nth-child(even) td {
        background: none !important;
    }
    th, td {
        border: none;
        padding: 0px 10px !important;
    }
    #dv_content1{
        height: 82vh;
        overflow-y: scroll;
    }
    .fixed-bottom{position: absolute !important;}

    .text-bold{font-weight: bold;}
</style>
<div id="prnnt" style="font-size:10px;">
    <?php if($rosterDetails) { 
        $jcd_rp = $benchJudges['jcd'];
        ?>
    <table border="0" width="100%" style="font-size:10px; text-align: left; background: #ffffff;" cellspacing="0">
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center">
                <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px"/><br/>
            </th>
        </tr>
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center">SUPREME COURT OF INDIA</th>
        </tr>
        <?php if (isset($benchJudges['courtno']) && $benchJudges['courtno'] == "1") { ?>
            <tr>
                <th colspan="4" class="center text-bold" style="text-align:center">
                    <br/>[ IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES<br/>
                    ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS<br/>
                    LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST ]
                </th>
            </tr>
        <?php } ?>
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center">
                <?php
                if (isset($benchJudges['jcd']) && ($benchJudges['jcd'] == "117,210" || $benchJudges['jcd'] == "117,198")) {
                    echo "LIST OF REVIEW PETITIONS (BY CIRCULATION) FOR TUESDAY, THE 9TH MAY, 2017<br/><U>IN THE CHAMBERS OF HON'BLE MR. JUSTICE KURIAN JOSEPH</U>";
                } else {
                    echo "DAILY CAUSE LIST FOR DATED : " . date('d-m-Y', strtotime($list_dt));
                }
                ?>
            </th>
        </tr>
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center"><?php echo $printCourtNo; ?></th>
        </tr>
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center">
                <?php 
                    $bench_judge_name = '';
                    if (isset($benchJudges['jnm']) && !empty($benchJudges['jnm'])) {
                        $bench_judge_name = stripcslashes(str_replace(",", "<br/>", $benchJudges['jnm']));
                    }
                    echo $bench_judge_name;
                ?>
            </th>
        </tr>
        <tr>
            <th colspan="4" class="center text-bold" style="text-align:center">
                <?php get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'H'); ?>
            </th>
        </tr>
        <?php if (isset($benchJudges['frm_time'])) { ?>
            <tr>
                <th colspan="4" class="center text-bold" style="text-align:center">(TIME : <?php echo $benchJudges['frm_time']; ?>)</th>
            </tr>
        <?php } ?>
    


    <?php 
        $clnochk=0; $subheading_rep = "0"; $mnhead_print_once = 1;    
        foreach ($rosterDetails as $row) {
            $coram = $row['coram'];
            $relief = $row['relief'];
            $main_supp_fl = $row['main_supp_flag'];
            $diary_no = $row['diary_no'];
            if ($mainhead == "F") {
                $retn = $row["sub_name1"];
                if ($row["sub_name2"])
                    $retn .= " - " . $row["sub_name2"];
                if ($row["sub_name3"])
                    $retn .= " - " . $row["sub_name3"];
                if ($row["sub_name4"])
                    $retn .= " - " . $row["sub_name4"];
                $subheading = $retn;
            } else {
                $subheading = $row["stagename"];
            }

            if ($mnhead_print_once == 1) {
                if ($mainhead == 'M' AND $subheading != "FOR JUDGEMENT" AND $subheading != "FOR ORDER")
                    $print_mainhead = "MISCELLANEOUS HEARING";
                if ($mainhead == 'F')
                    $print_mainhead = "REGULAR HEARING";
                if ($mainhead == 'L')
                    $print_mainhead = "LOK ADALAT HEARING";
                if ($mainhead == 'S')
                    $print_mainhead = "MEDIATION HEARING";
                if ($main_supp_fl == "2") {
                    echo "<tr><td colspan='4' style='font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>";
                }
                ?>
                <tr>
                    <th colspan="4"
                        style="text-align: center; text-decoration: underline;font-weight: bold;"><?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
                            echo $print_mainhead;
                        } ?></th>
                </tr>
                <tr style="font-weight: bold; background-color:#cccccc;">
                    <td style="width:5%;"><b>SNo.</b></td>
                    <td style="width:20%;"><b>Case No.</b></td>
                    <td style="width:35%;"><b>Petitioner / Respondent</b></td>
                    <td style="width:40%;"><b>
                        <?php if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") { ?>
                            Petitioner/Respondent Advocate
                        <?php } ?>
                        </b>
                    </td>
                </tr>

                <?php
                $mnhead_print_once++;
            } 
            if ($subheading != $subheading_rep) {
                if ($subheading == "FOR JUDGEMENT" OR $subheading == "FOR ORDER") {
                    echo "<tr><td colspan='4' style='font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                }
                if ($jcd_rp !== "117,210" AND $jcd_rp != "117,198") {
                    echo "<tr><td colspan='4' style='font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                }
                $subheading_rep = $subheading;
            }    
            if ($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == 0) {
                $print_brdslno = $row['brd_slno'];
                $con_no = "0";
                $is_connected = "";
            } else {
                $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                $is_connected = "<span style='color:red;'>Connected</span><br/>";
            }

            $m_f_filno = $row['active_fil_no'];
            $m_f_fil_yr = $row['active_reg_year'];
            $filno_array = explode("-", $m_f_filno);
            
            
            
            /*if ($filno_array[1] == $filno_array[2]) {
                $fil_no_print = ltrim($filno_array[1], '0');
            } else {
                $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
            }*/

            $fil_no_print = '';
            if (isset($filno_array[1]) && !empty($filno_array[1])) {
                if (isset($filno_array[2]) && !empty($filno_array[2])) {
                    if ($filno_array[1] == $filno_array[2]) {
                        $fil_no_print = ltrim($filno_array[1], '0');
                    } else {
                        $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                    }
                } else {
                    $fil_no_print = ltrim($filno_array[1], '0');
                }
            }
        
            if ($row['reg_no_display'] == "") {
                $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
            }else {
                //$comlete_fil_no_prt = $row['short_description']."-".$fil_no_print."/".$m_f_fil_yr;
                $comlete_fil_no_prt = $row['reg_no_display'];
            }
            
            $cate_old_id1 = $sensitive_case = $is_nmd_case = $last_order = $pet_name = $padvname = "";
            $output = '';

            
            $rowadv = $printModel->get_advocate_details($row["diary_no"]);
            $res_name = $radvname = $impldname = $intervenorname = '';
            if($rowadv){
                $radvname = !empty($rowadv["r_n"]) ? str_replace(",", ", ", trim($rowadv["r_n"], ",")) : '';
                $padvname = !empty($rowadv["p_n"]) ? str_replace(",", ", ", trim($rowadv["p_n"], ",")) : '';
                $impldname = !empty($rowadv["i_n"]) ? str_replace(",", ", ", trim($rowadv["i_n"], ",")) : '';
                $intervenorname = !empty($rowadv["intervenor"]) ? str_replace(",", ", ", trim($rowadv["intervenor"], ",")) : '';
            }

            if ($row['pno'] == 2) {
                $pet_name = $row['pet_name'] . " AND ANR.";
            } else if ($row['pno'] > 2) {
                $pet_name = $row['pet_name'] . " AND ORS.";
            } else {
                $pet_name = $row['pet_name'];
            }
            if ($row['rno'] == 2) {
                $res_name = $row['res_name'] . " AND ANR.";
            } else if ($row['rno'] > 2) {
                $res_name = $row['res_name'] . " AND ORS.";
            } else {
                $res_name = $row['res_name'];
            }

            if (($row['section_name'] == null OR $row['section_name'] == '') AND $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                if ($row['active_reg_year'] != 0)
                    $ten_reg_yr = $row['active_reg_year'];
                else
                    $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                if ($row['active_casetype_id'] != 0)
                    $casetype_displ = $row['active_casetype_id'];
                else if ($row['casetype_id'] != 0)
                    $casetype_displ = $row['casetype_id'];
            }    

            $output .= "<tr><td>$print_brdslno</td><td rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>Dno. " . $row['diary_no'] . "<br/>" . $row['name'] . " (" . $row['section_name'] . ")<br/>" . $cate_old_id1 . $sensitive_case . $is_nmd_case . "<br/>" . $last_order . "</td><td>" . $pet_name . "</td><td>" . $padvname;
                    $party_petioner_contact = "";
                    $party_petioner_contact = $printModel->get_party_contact_details($row["diary_no"], 'P');
                    if ($party_petioner_contact) {
                        $output .= $party_petioner_contact;
                    }

                    $output .= "</td></tr>";
                    $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                    if ($jcd_rp != "117,210" AND $jcd_rp != "117,198") {
                        //$output .= "Versus";
                    }
                    $output .= "</td></tr>";
                    $output .= "<tr><td></td><td></td><td";

                    $output .= ">" . $res_name . "</td><td>" . $radvname;
                    if ($impldname) {
                        $output .= "<br/>" . $impldname;
                    }
                    if ($intervenorname != '') {
                        $output .= "<br/>" . $intervenorname;
                    }
                    $party_respondent_contact = "";
                    $party_respondent_contact = $printModel->get_party_contact_details($row["diary_no"], 'R');
                    if ($party_respondent_contact){
                        $output .= $party_respondent_contact;
                    }
                    $output .= "</td></tr>";  
                    if($mainhead == "M" OR $mainhead == "F"){
                        $output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'>";
                        //$output .= $row['section_name'];
                        //if($jcd_rp != "117,210" AND $jcd_rp != "117,198"){
                            $output .= "{".$row['purpose']."} <br>";
                        if($row['c_status'] == 'D'){
                            $output .= "<font color=red>ALERT : DISPOSED CASE LISTED</font><br>";
                        }

                         $output .= "</td></tr>";
                    }    
                        $output .= "<tr><td style='border-bottom:2px dotted #999999; padding-bottom:1px; size : 1px; height:1px;' colspan=4></td></tr>";            
                    echo $output;
                    $output = "";
            
        } //END OF FOR LOOP   
        ?>
    
    </table>
    <?php get_header_footer_print($list_dt, $mainhead, $roster_id, $part_no, 'F'); ?>
    <br><p align='left' style="font-size: 10px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata'); echo date('d-m-Y H:i:s');?></
    b>&nbsp; &nbsp;</p>
    <br><p align='right' style="font-size: 10px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>

    <center></center>
    <?php } else {?>
        <?php echo "No Records Found"; } ?>
</div>
<br/><br/><br/><br/><br/><br/><br/><br/><br/>
<div class="footer bg-light text-center border-top fixed-bottom">
<span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">    
</span>
<input name="prnnt1" type="button" id="prnnt1" value="Print" class="btn btn-primary">
</div>
