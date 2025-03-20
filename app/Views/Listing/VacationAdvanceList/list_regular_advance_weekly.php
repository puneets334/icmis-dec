<?= view('header') ?>

    <div id="prnnt" style="font-size:12px;">
        <div align="center" style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
                    <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px" /><br />
                    SUPREME COURT OF INDIA
                    <br />
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <tr>
                <th colspan="4" style="text-align: center;">
                  
                </th>
            </tr>
            <tr>
                <th colspan="4" style="text-align: left;">
                    <BR>
                </th>
            </tr>

            <?php
            $heading_priority_rep = "0";
            if (count($list_weekly) > 0) {
                //echo mysql_num_rows($res);
                $head2013 = 1;
                $psrno = "1";
                $clnochk = 0;
                $subheading_rep = "0";
                $mnhead_print_once = 1;
                $heading='';
                $is_connected ='';
                $output ='';
                foreach ($list_weekly as $row ) {
                    $diary_no = $row['diary_no'];

                    if ($mnhead_print_once == 1) {
            ?>
                        <tr style="font-weight: bold; background-color:#cccccc;">
                            <td style="width:5%;">SNo.</td>
                            <td style="width:20%;">Case No.</td>
                            <td style="width:35%;">Petitioner / Respondent</td>
                            <td style="width:40%;">Petitioner/Respondent Advocate</td>
                        </tr>
                <?php
                        $mnhead_print_once++;
                    }
                    //$subheading = "";
                    $heading_priority = $row['heading_priority'];
                    if ($heading_priority != $heading_priority_rep) {
                        if ($heading_priority == 1) {
                            //  $heading = "Cases which are to be listed on specific dates during the vacation as per the directions of the Hon'ble Court.";
                        } else if ($heading_priority == 2) {
                            //$heading = "Regular Hearing Criminal cases in which Accused are in Custody.";
                        } else {
                            //$heading = "Regular Hearing cases in chronological order registered upto the year 2022";
                        }
                        echo "<tr><td colspan='4' style='font-size:15px; font-weight:bold; padding-top:15px; padding-bottom:15px; text-decoration:underline; text-align:left;'>$heading</td></tr>";
                        $heading_priority_rep = $heading_priority;
                    }

                    if ($row['reg_no_display'] == "") {
                        $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $comlete_fil_no_prt = $row['reg_no_display'];
                    }

                    $padvname = "";
                    $radvname = "";

                   
                    $resultsadv = vac_reg_week_fun5($row["diary_no"]);
                    if (count($resultsadv) > 0) {
                        $rowadv = $resultsadv[0];
                        $radvname =  strtoupper($rowadv["r_n"]);
                        $padvname =  strtoupper($rowadv["p_n"]);
                        $impldname = strtoupper($rowadv["i_n"]);
                        $intervenorname = strtoupper($rowadv["intervenor"]);
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




                    $cate_old_id1 = "";

                    $cate_old_id1 = '';
                    $output .= "<tr style='padding-top:5px;'><td style='vertical-align: top;'>" . $psrno . "</td><td style='vertical-align: top;' rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name'];
                    $output .= "</td><td style='vertical-align: top;'>" . $pet_name . "</td><td style='vertical-align: top;'>" . str_replace(",", ", ", trim($padvname, ","));
                    $output .= "</td></tr>";
                    $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                    $output .= "</td></tr>";
                    $output .= "<tr><td></td><td></td><td style='vertical-align: top;'";
                    $output .= ">" . $res_name . "</td>";
                   
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
                                $output .= "<tr><td></td><td></td><td></td><td valign='top'>a " . $lines[$k] . "</td></tr>";
                            }
                        }
                    } else {
                        $output .= "<td></td></tr>";
                    }

                    $str_brdrem = get_cl_brd_remark($diary_no);
                    $x60 = 150;
                    $lines = explode("\n", wordwrap($str_brdrem, $x60));
                    for ($k = 0; $k < count($lines); $k++) {
                        $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        $output .= $lines[$k];
                        $output .= "</td><td></td></tr>";
                    }

                    if ($row['diary_no'] == $row['main_key']) {
                   
                        $res2 = vac_reg_week_fun7($row['diary_no']);
                        if (count($res2) > 0) {

                            $psrno_conc = "1";
                            foreach ($res2 as $row2) {
                                $diary_no = $row2['diary_no'];
                                if ($row2['reg_no_display'] == "") {
                                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row2['diary_no'], '-', -4, 0);
                                } else {
                                    $comlete_fil_no_prt = $row2['reg_no_display'];
                                }
                                $padvname = "";
                                $radvname = "";
                                $resultsadv = vac_reg_week_fun6($row2["diary_no"]);
                                if (count($resultsadv) > 0) {
                                    $rowadv = $resultsadv[0];
                                    $radvname =  strtoupper($rowadv["r_n"]);
                                    $padvname =  strtoupper($rowadv["p_n"]);
                                    $impldname = strtoupper($rowadv["i_n"]);
                                    $intervenorname = strtoupper($rowadv["intervenor"]);
                                }

                                if ($row2['pno'] == 2) {
                                    $pet_name = $row2['pet_name'] . " AND ANR.";
                                } else if ($row2['pno'] > 2) {
                                    $pet_name = $row2['pet_name'] . " AND ORS.";
                                } else {
                                    $pet_name = $row2['pet_name'];
                                }
                                if ($row2['rno'] == 2) {
                                    $res_name = $row2['res_name'] . " AND ANR.";
                                } else if ($row2['rno'] > 2) {
                                    $res_name = $row2['res_name'] . " AND ORS.";
                                } else {
                                    $res_name = $row2['res_name'];
                                }

                                $output .= "<tr><td>" . $psrno . '.' . $psrno_conc++ . "</td><td rowspan=2> <span style='color:red;'>Connected</span><br/> " . $comlete_fil_no_prt . "<br/>" . $row2['section_name'];
                                $output .= "</td><td>" . $pet_name . "</td><td>" . str_replace(",", ", ", trim($padvname, ","));
                                $output .= "</td></tr>";
                                $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                                $output .= "</td></tr>";
                                $output .= "<tr><td></td><td></td><td";
                                $output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
                                $output .= "</td></tr>";
                                $str_brdrem = get_cl_brd_remark($row2['diary_no']);
                                $x60 = 150;
                                $lines = explode("\n", wordwrap($str_brdrem, $x60));
                                for ($k = 0; $k < count($lines); $k++) {
                                    $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                                    $output .= $lines[$k];
                                    $output .= "</td><td></td></tr>";
                                }
                            }
                        }
                    }
                    $psrno++;
                    echo $output;
                    $output = "";
                } //END OF WHILE LOOP

                ?>
        </table>
    <?php
            } else {
                echo "No Records Found";
            }

    ?>
    <br>
    <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
    <br>
    <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
    </div>
   
    <center></center>

   
    <script>
       
        $(window).on('load', function() {
    window.print();
});
    </script>
    