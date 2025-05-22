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
            
            $section_ten_q = "SELECT tentative_section(" . $row["diary_no"] . ") as section_name";
            $section_ten_rs = $printModel->get_section_name($row["diary_no"]);
           
            if (count($section_ten_rs) > 0) {
                $row['section_name'] = $section_ten_rs["section_name"];
            } else {
                $row['section_name'] = '';
            }

            $cate_old_id1 = "";
            $diary_no = $row["diary_no"];
            
            $res_sm = get_mul_category($row["diary_no"], $flag = null);
            if (!empty($res_sm)) {
                $cate_old_id1 = $res_sm;
            }
            $last_order_query = $last_order = "";

            $last_order_query = case_verification_report_popup_inside_details($row["diary_no"]);
            if (count($last_order_query) > 0) {
                $last_order_url = $last_order_query;

                $rjm = explode("/", $last_order_url[0]['pdfname']);
                if ($rjm[0] == 'supremecourt') {
                    $last_order = 'Last ROP Dated : <a href="../../jud_ord_html_pdf/' . $last_order_url[0]['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($last_order_url[0]['orderdate'])) . '</a>';
                } else {
                    $last_order = 'Last ROP Dated : <a href="../../judgment/' . $last_order_url[0]['pdfname'] . '" target="_blank">' . date("d-m-Y", strtotime($last_order_url[0]['orderdate'])) . '</a>';
                }
            }
   

            $doc_desrip = "";
            $listed_ias = $row['listed_ia'];
            if (is_string($listed_ias)) {
                $listed_ia = rtrim(trim($listed_ias), ",");
            } else {
                $listed_ia = ''; // Or some other appropriate default value
            }
           
            if ($listed_ias) {
                $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In<br>";

                $rs_dc = $printModel->getDocnumDocYear($row["diary_no"]);
                if (count($rs_dc) > 0) {
                    foreach ($rs_dc as $row_dc) {
                        $doc_desrip .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        $doc_desrip .= "IA No. " . $row_dc['docnum'] . "/" . $row_dc['docyear'] . " - " . $row_dc['docdesp'];
                        $doc_desrip .= "</td><td></td></tr>";
                    }
                }


            }

            $is_nmd_case = "";
            if ($row['is_nmd'] == 'Y') {
                $is_nmd_case = "<BR><span style='color:RED;'><U>LIST ON NMD</U></span>";
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
                        
                        $output .= "{".$row['purpose']."} <br>";
                        if($row['c_status'] == 'D'){
                            $output .= "<font color=red>ALERT : DISPOSED CASE LISTED</font><br>";
                        }
        
                      
                        $checked_notbefore_verify = check_list_before($diary_no, 'N');
                        $sql_sensitive = "SELECT diary_no, reason FROM sensitive_cases WHERE diary_no = '" . $row["diary_no"] . "' and display = 'Y' ";
                        $rs_sensitive = is_data_from_table('sensitive_cases', ['diary_no' => $row["diary_no"], 'display' => 'Y'], 'diary_no', 'R');
                       
                        $sensitive_case = "";
                        if (!empty($rs_sensitive)) {
                            $sensitive_row=$rs_sensitive;
                            $output .= "<BR><span style='color:red;'>SENSITIVE : </span>".$sensitive_row['reason'];
                        }
                        if($coram != 0 and $coram != ''){
                        
                            $output .= "<br/> CORAM : ".f_get_judge_names($coram);
                            $sql_not_before_reason = "SELECT res_add FROM not_before_reason WHERE res_id = '" . $row["list_before_remark"] . "' ";
                            $rs_not_before_reason = $printModel->get_not_before_reason($row["list_before_remark"]);
                            $not_before_reason = "";
                            if (count($rs_not_before_reason) > 0) {
                                $not_before_reason_row=$rs_not_before_reason;
                                $output .= " SOURCE : ".$not_before_reason_row['res_add']." ";
                            }
        
                            $output .= "</font>";
                        }
                     
                        if($checked_notbefore_verify){
                            if(f_get_judge_names($checked_notbefore_verify))
                                $output .= "<br/> <font color=red>Not To List Before ".f_get_judge_names($checked_notbefore_verify)."</font>";
                        }
                      
                        $rs_lct = $printModel->get_lowerct_casetype($row["diary_no"]);
                        if(count($rs_lct)>0){
                            $output .= "<br/>";
                            foreach($rs_lct as $ro_lct){
                                $output .= "<br> IN ".$ro_lct['type_sname']." - ".$ro_lct['lct_caseno']."/".$ro_lct['lct_caseyear'].", ";
                            }                    
                       }
                        
                        $output .= "</td></tr>";
                      
                        if($part_no == "50" OR $part_no == "51"){
                            
                        }
                        else{
                            $output .= $doc_desrip;
                            $str_brdrem = get_cl_brd_remark($diary_no);
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($str_brdrem, $x60));
                            for ($k = 0; $k < count($lines); $k++) {
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= $lines[$k];
                                $output .= "</td><td></td></tr>";
                            }
                            if($relief){
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= "Relief : ".$relief;
                                $output .= "</td><td></td></tr>";
                            }
                        }
        
                               
                                $str_gateinfo = get_gateinfo($diary_no);
        
                                $x60 = 150; 
                                $linesgateinfo = explode("\n", wordwrap($str_gateinfo, $x60));
                                for ($k = 0; $k < count($linesgateinfo); $k++) {
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= $linesgateinfo[$k];
                                    $output .= "</td><td></td></tr>";
                                }
                             
        
                        $get_last_listed_before_judge_code = "";
                        $get_last_listed_before_judge_code = get_last_hearing_judge_before_court_code($row["diary_no"], $list_dt);
        
                        if(!empty($get_last_listed_before_judge_code)){
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                            $output .= "<br/>Last Listed Before Hon'ble Judge : ".f_get_all_judges_names_by_code($get_last_listed_before_judge_code)." ";
                            $output .= "</td><td></td></tr>";
                        }
                        
                     
        
                        if($row['lastorder'] != ''){
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:1px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        $output .= "<br/>Last Order :".$row['lastorder'];    
                        $output .= "</td><td></td></tr>";
        
                        }
        
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
