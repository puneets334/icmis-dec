<div id="prnnt" style="font-size:12px;">
    <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
                <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px" /><br />
                SUPREME COURT OF INDIA
                <br />
            </b></div>
    <table autosize="1" border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff; word-wrap: break-word; overflow:auto;" cellspacing=0>

        <tbody>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php
                   
                    $sql_list_no = $model->getWeeklyInfo($from_dt, $to_dt);
                   
                    
                   
                    if (!empty($sql_list_no))
                    {
                        $row_list_no = $sql_list_no;
                        $advance_weekly_no = $row_list_no['weekly_no'];
                        $advance_weekly_year = $row_list_no['weekly_year'];
                    }
                    echo "Advance List of Single Judge Bench Matters to be listed from " . date('l', strtotime($from_dt)) . " " . date('d-m-Y', strtotime($from_dt)) . " To " . date('l', strtotime($to_dt)) . " " . date('d-m-Y', strtotime($to_dt)) . "<br/>";
                    ?>
                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: center;"></th>
            </tr>
            <?php

            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            $res = $model->getPetName($from_dt, $to_dt, $board_type, $mainhead);
            if (!empty($res))
            {
               
                foreach ($res as $row) {
                    // $coram = $row['coram']; // hide 
                    $relief = $row['relief'];
                    $main_supp_fl = $row['main_supp_flag'];
                    $diary_no = $row['diary_no'];
                    if ($row['if_sclsc'] == 1) {
                        $if_sclsc = "(SCLSC)";
                    } else {
                        $if_sclsc = "";
                    }
                    if ($mnhead_print_once == 1) {                            ?>
                        <tr style="font-weight: bold; background-color:#cccccc;">
                            <td style="width:5%;">SNo.</td>
                            <td style="width:20%;">Case No.</td>
                            <td style="padding-left:20px; width:50%;">Petitioner / Respondent</td>
                            <td style="width:25%;">
                                Petitioner/Respondent Advocate
                            </td>
                        </tr>
                <?php
                        $mnhead_print_once++;
                    }
                    if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0) {
                        $print_brdslno = $row['brd_slno'];
                        $con_no = "0";
                        $is_connected = "";
                    } else {
                        $print_brdslno = "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                        $is_connected = "<span style='color:red;'>Connected</span><br/>";
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
                    $part_no = "";
                  
                    if ($part_no != "50" and $part_no != "51")
                    {
                        $resultsadv = $model->getIntervenorData($row["diary_no"]);
                        $resultsadv =[];
                       
                        if (!empty($resultsadv)) {
                            $rowadv = $resultsadv;
                       
                            $radvname =  $rowadv["r_n"];
                            $padvname =  $rowadv["p_n"];
                            $impldname = $rowadv["i_n"];
                            $intervenorname = $rowadv["intervenor"];
                        }
                    }
                    if ($row['pno'] == 2) {
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
                    $section_ten_rs = $model->getTentativeSection($row["diary_no"]);
                   
                   if (!empty($section_ten_rs))
                   {
                        $section_ten_row = $section_ten_rs;
                        $row['section_name'] = $section_ten_row["section_name"];
                    }
                    else
                    {
                        $row['section_name'] = '';
                    }
                    $doc_desrip = "";
                    $listed_ias = $row['listed_ia'];

                    $listed_ia = rtrim(trim(isset($listed_ias)), ",");
                   
                   

                    if ($listed_ias)
                    {
                        $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In <br>";
                       
                        $rs_dc=$model->getDoc($row["diary_no"]);
                       
                        
                        
                       
                      
                        if (!empty($rs_dc))
                        {
                            foreach ($rs_dc as $row_dc )
                            {
                                $doc_desrip .= "<tr><td></td><td></td> <td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
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
                  
                    if ($padvname_x)
                    {
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

                    $radvname_x = str_replace(",", ", ", trim(isset($radvname), ","));
                   
                    
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
                        // pr($lines_cnt);
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
                    if ($mainhead == "M" or $mainhead == "F")
                    {
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        if ($row['listorder'] == '5')
                            $output .= "{" . $row['purpose'] . "}";
                        $output .= "</td><td></td></tr>";
                      

                        $rs_lct = $model->getTypeSname($diary_no);
                       
                       
                       
                      


                        if (!empty($rs_lct)) {
                            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                            foreach ($rs_lct as $ro_lct) {
                                $output .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
                            }
                            $output .= "</td><td></td></tr>";
                        }

                        if ($part_no == "50" or $part_no == "51")
                        {
                        } else
                        {
                          
                            $str_brdrem = get_cl_brd_remark($diary_no);
                            $x60 = 150;

                            $lines = explode("\n", wordwrap($str_brdrem, $x60));
                            $lines=[];
                            for ($k = 0; $k < count($lines); $k++)
                            {
                                $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                $output .= $lines[$k];
                                $output .= "</td><td></td></tr>";
                            }
                            if ($relief != '' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER")
                            {
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
                } //END OF Foreach LOOP
                ?>
            <?php
            } //IF RECORDS AVAILABLE
            else {
                echo "NO RECORDS FOUND";
            }
            ?>
            <tr>
                <th colspan="4" style="text-align: center;"> <?php
                                                               
                                                                ?> </th>
            </tr>
        </tbody>
    </table><br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
</div>
<br /><br /><br /><br /><br /><br /><br /><br />
<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php $rslt_is_printed = 0;
    $q_rs = $model->checkIfPrinteds($from_dt,$to_dt);
    if (!empty($q_rs))
    {
        echo "Already Printed";
    }
    else {
    ?>
        <input name="prnnt1" type="button" id="ebublish" value="e-Publish" data-weekly_number="<?= isset($advance_weekly_no) ?>" data-weekly_year="<?= isset($advance_weekly_year) ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php
    }
    ?>
    <span id="toggle_hw" style="color: #0066cc; font-weight: bold; cursor: pointer; padding-right: 1px;">
    </span>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">

</div>
<center></center>