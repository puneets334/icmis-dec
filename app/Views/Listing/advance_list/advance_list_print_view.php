<div id="prnnt" style="font-size:12px;">

    <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
                <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px" /><br />

                SUPREME COURT OF INDIA


                <br />

                <?php


                ?>

            </b></div>
    <table autosize="1" border="0" class="table table-striped table-bordered" width="100%" style="font-size:12px; text-align: left; background: #ffffff; word-wrap: break-word; overflow:auto;" cellspacing=0>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php

                    $res_advn = $model->getNMDNote($list_dt);
                    if (!empty($res_advn)) {
                        $nmd_note = " ";
                    }
                    $min_brdslno = 0;
                    $max_brdslno = 0;

                    $res_minmax = $model->getBoardRange($list_dt, $board_type);

                    if (!empty($res_minmax)) {
                        $res_minmax = $res_minmax;
                        $min_brdslno = $res_minmax['min_brd'];
                        $max_brdslno = $res_minmax['max_brd'];
                    }
                    $advance_list_no = 1;
                    $res_list_no = $model->getAdvanceListNumber($list_dt, $board_type,);
                    if (!empty($res_list_no)) {
                        $row_list_no = $res_list_no;
                        $advance_list_no = $row_list_no['advance_list_no'] + 1;
                    }
                     echo "MISCELLANEOUS MATTERS TO BE LISTED ON " . date('d-m-Y', strtotime($list_dt)) . "<br/><br/>";
                    echo "ADVANCE LIST - AL/$advance_list_no/" . date('Y', strtotime($list_dt)) . "<br/><br/>";
                    echo "<b> TENTATIVE LIST OF MATTERS WHICH ARE LIKELY TO BE LISTED. </b><br/><br/>";
                    ?>
                    <?php

                    ?>

                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php



                    ?>
                </th>
            </tr>

            <?php
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            $res=$model->getMetterdata($list_dt, $board_type_in, $mainhead);
           
          
            if (!empty($res)) 
            {

                foreach ($res as $row)
                {

                    $relief = $row['relief'];
                    $main_supp_fl = $row['main_supp_flag'];
                    $diary_no = $row['diary_no'];

                    if ($row['if_sclsc'] == 1) {
                        $if_sclsc = "(SCLSC)";
                    } else {
                        $if_sclsc = "";
                    }
                    if ($mnhead_print_once == 1)
                    {
                        if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER")
                        {
                            if ($row['board_type'] == 'C') {
                                if ($part_no != "50" and $part_no != "51") {
                                    $print_mainhead = "CHAMBER MATTERS";
                                }
                            } else {
                                if ($part_no == "50" or $part_no == "51") {
                                } else {
                                }
                            }
                        }
                        if ($mainhead == 'F' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER")
                            $print_mainhead = "REGULAR HEARING";
                        if ($mainhead == 'L')
                            $print_mainhead = "LOK ADALAT HEARING";
                        if ($mainhead == 'S')
                            $print_mainhead = "MEDIATION HEARING";
                        if ($main_supp_fl == "2") {
                            echo "<tr><td colspan='4' style='font-size:13px;font-weight:bold; text-decoration:underline; text-align:center;'>SUPPLEMENTARY LIST</td></tr>";
                        }
                        ?>
                        <tr>
                            <th colspan="4" style="text-align: center; text-decoration: underline;"><?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                                                                                                        echo $print_mainhead;
                                                                                                    } ?>
                            </th>
                        </tr>
                        <tr style="font-weight: bold; background-color:#cccccc;">
                            <td style="width:5%;"><b>SNo.</b></td>
                            <td style="width:20%;"><b>Case No.</b></td>
                            <td style="padding-left:20px; width:50%;"><b>Petitioner / Respondent </b></td>
                            <td style="width:25%;">
                                <?php if ($part_no != "50" and $part_no != "51") { ?>
                                    <b>Petitioner/Respondent Advocate</b>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php
                        $mnhead_print_once++;
                    }
                    $con_no = '';
                    if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0) {
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
                   
                    //pr($filno_array);
                    if (count($filno_array) >= 2) {
                        if ($filno_array[0] == $filno_array[1]) {
                            $fil_no_print = ltrim($filno_array[0], '0');
                        } else {
                            $fil_no_print = ltrim($filno_array[0], '0') . "-" . ltrim($filno_array[1], '0');
                        }
                    } elseif (count($filno_array) == 1) {
                        $fil_no_print = ltrim($filno_array[0], '0');
                    } else {
                        $fil_no_print = '';
                    }
                    if ($row['reg_no_display'] == "") {
                        $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $comlete_fil_no_prt = $row['reg_no_display'];
                    }
                   
                    $padvname = "";
                    $radvname = "";
                    $impldname = "";
                    $intervenorname = "";
                    $casetype_displ='';


                    if ($part_no != "50" and $part_no != "51")
                    {

                        $resultsadv = $model->getRadvnamePadvname($row["diary_no"]);
                        
                       if (!empty($resultsadv))
                        {
                            $rowadv = $resultsadv;

                            if ($rowadv["r_n"] !== null) {
                                $radvname =  strtoupper($rowadv["r_n"]);
                            } else {
                                $radvname = '';
                            }
                            if ($rowadv["p_n"] !== null) {
                                $padvname = strtoupper($rowadv["p_n"]);
                            } else {
                                $padvname = '';
                            }


                            if ($rowadv["i_n"] !== null) {
                                $impldname = strtoupper($rowadv["i_n"]);
                            } else {
                                $impldname = '';
                            }


                            if ($rowadv["intervenor"] !== null) {
                                $intervenorname = strtoupper($rowadv["intervenor"]);
                            } else {
                                $intervenorname = '';
                            }
                        }
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
                    if (($row['section_name'] == null or $row['section_name'] == '') and $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                        if ($row['active_reg_year'] != 0)
                            $ten_reg_yr = $row['active_reg_year'];
                        else
                            $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));
                            
                        if ($row['active_casetype_id'] != 0)
                            $casetype_displ = $row['active_casetype_id'];
                        else if ($row['casetype_id'] != 0)
                            $casetype_displ = $row['casetype_id'];
                    }
                    //pr("SELECT tentative_section(".$row["diary_no"].") as section_name");
                    
                    $section_ten_q = $model->getSectionName($row["diary_no"]);
                   
                   
                    if (!empty($section_ten_q))
                    {
                        $section_ten_row = $section_ten_q;
                        $row['section_name'] = $section_ten_row["section_name"];
                    }
                    else
                    {
                        $row['section_name'] = '';
                    }
                   
                    $doc_desrip = "";
                    $listed_ias = $row['listed_ia'];
                    if ($listed_ias !== null) {
                        $listed_ia = rtrim(trim($listed_ias), ",");
                    } else {
                        $listed_ia = '';
                    }
                    if ($listed_ias) {
                        $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In <br>";
                        

                        $rs_dc = $model->getDocnumDocYear($row["diary_no"]);
                       
                      
                        if (!empty($rs_dc)) {
                            foreach ($rs_dc as $row_dc) {
                                $doc_desrip .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $doc_desrip .= "IA No. " . $row_dc['docnum'] . "/" . $row_dc['docyear'] . " - " . $row_dc['docdesp'];
                                $doc_desrip .= "</td><td></td></tr>";
                            }
                        }
                    }

                    $output = '';

                    $output .= "<tr><td style='vertical_align:top;' valign='top'>$print_brdslno</td>";
                    $output .= "<td style='vertical_align:top;' valign='top'>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $if_sclsc . " " . $row['section_name'] . "</td>";
                    $output .= "<td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top'>" . $pet_name . "</td>";
                    $padvname_x = str_replace(",", ", ", trim($padvname, ","));

                    if ($padvname_x) {
                        $x60 = 150;
                        $lines = explode("\n", wordwrap($padvname_x, $x60));
                        $lines_cnt = count($lines);
                        for ($k = 0; $k < count($lines); $k++) {
                            if ($k == 0) {
                                $output .= "<td valign='top'>" . $lines[$k] . "</td></tr>";
                            } else if ($k == 1 or $k == 2) {
                                $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $lines[$k] . "</td></tr>";
                            } else {
                                $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $lines[$k] . "</td></tr>";
                            }
                        }
                    } else {
                        $output .= "<td></td></tr>";
                    }
                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; font-style: italic;' valign='top'>Versus</td><td style='font-style: italic;'></td></tr>";
                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top' > " . $res_name . "</td>";
                    $radvname_x = str_replace(",", ", ", trim($radvname, ","));
                    if ($impldname) {
                        $radvname_x .= "<br/>" . str_replace(",", ", ", trim($impldname, ","));
                    }
                    if ($intervenorname) {
                        $radvname_x .= "<br/>" . str_replace(",", ", ", trim($intervenorname, ","));
                    }

                    if ($radvname_x) {
                        $x60 = 150;
                        $lines = explode("\n", wordwrap($radvname_x, $x60));
                        $lines_cnt = count($lines);
                        for ($k = 0; $k < count($lines); $k++) {
                            if ($k == 0) {
                                $output .= "<td valign='top'>" . $lines[$k] . "</td></tr>";
                            } else {
                                $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $lines[$k] . "</td></tr>";
                            }
                        }
                    } else {
                        $output .= "<td></td></tr>";
                    }

                    if ($mainhead == "M" or $mainhead == "F") {
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        if ($row['listorder'] == '5')
                            $output .= "{" . $row['purpose'] . "}";
                        $output .= "</td><td></td></tr>";
                       

                        $rs_lct = $model->getLctNoYear($diary_no);
                       

                        if (!empty($rs_lct)) {
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                            foreach ($rs_lct as $ro_lct) {
                                $output .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
                            }
                            $output .= "</td><td></td></tr>";
                        }

                        if ($part_no == "50" or $part_no == "51") {
                        } else {
                            $str_brdrem = get_cl_brd_remark($diary_no);

                            $x60 = 150;
                            $lines = explode("\n", wordwrap($str_brdrem, $x60));
                            for ($k = 0; $k < count($lines); $k++) {
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= $lines[$k];
                                $output .= "</td><td></td></tr>";
                            }


                            if ($relief != '' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= "Relief : " . $relief;
                                $output .= "</td><td></td></tr>";
                            }
                        }
                        $output .= $doc_desrip;
                    }
                    $output .= "<tr><td style='border-bottom:0px dotted #999999; padding-bottom:10px; size : 2px; height:2px;' colspan=4></td></tr>";
                    echo $output;
                    $output = "";
                } //END OF WHILE LOOP
                ?>
            <?php
            } //IF RECORDS AVAILABLE
            else {

                //echo "NO RECORDS FOUND";
            }
            ?>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php
                    ?>
                </th>
            </tr>
        </tbody>
    </table>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<br /><br /><br /><br /><br /><br /><br /><br />
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    <?php $rslt_is_printed = 0;

    $rs_lct = $model->getPrintStatus($list_dt, $board_type);
    if (!empty($rs_lct)) {
        echo "Already Printed";
    } else {


    ?>
        <input name="prnnt1" type="button" id="ebublish" value="e-Publish"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php
    }

    ?>



    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">

    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">

</div>
<center></center>