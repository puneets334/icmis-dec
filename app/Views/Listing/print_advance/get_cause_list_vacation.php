<?php
$filePath = "C:\\laragon\\www\\icmis\\writable\\judgment\\cl\\vacation\\{$v_year}\\AV_{$v_year}.pdf";
//Live Server check file 
//$path_dir = "/home/judgment/cl/vacation/" . $v_year . "/AV_" . $v_year . ".pdf";

?>
<div class="card ignore_in_print">
    <div class="card-body text-center">
        <div class="row">
            <div class="col-md-6">
                <?php if (!file_exists("$filePath")) { ?>
                    <button class="btn btn-primary" type="button" id="ebublish">e-Publish</button>
                <?php } else {
                    echo "<a href='{$filePath}' class='btn btn-success' target='_blank'>Already Published</a>"; ?>
                <?php }  ?>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary" type="button" id="prnnt1">Print</button>
            </div>
        </div>

    </div>
</div>
<div id="resultPrintSec" align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
            <img src="<?= base_url('images/scilogo.png') ?>" width="50px" height="80px" /><br />
            SUPREME COURT OF INDIA
            <br />
</div>
<table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;font-weight: 600;">
                <?php echo "ADVANCE REGULAR HEARING MATTERS LIST TO BE LISTED DURING SUMMER VACATION " . $v_year; ?>
            </th>
        </tr>
        <tr>
            <th colspan="4" style="font-size: 18px; text-align: center; text-decoration: underline;font-weight: 600;">
                <?php echo "CASES WHICH ARE DIRECTED TO BE LISTED DURING SUMMER VACATION"; ?>
            </th>
        </tr>
    </thead>
    <?php
    if (!empty($results)) {
        $psrno = "1";
        $clnochk = 0;
        $subheading_rep = "0";
        $mnhead_print_once = 1;

        foreach ($results as $row_index => $row) {
            $coram = $row['coram'];
            $fix_dt = date('d-m-Y', strtotime($row['next_dt']));
            $main_supp_fl = $row['main_supp_flag'];
            $diary_no = $row['diary_no'];
            $mainhead = $row['mainhead'];
            $vaca_note = '';
            $jcd_rp  = '';
            if ($row['is_fixed'] == 'Y') {
                $vaca_note = 1;
            }
            if ($vaca_note == 1 and $row['listorder'] != 4 and $row['listorder'] != 5 and $row['listorder'] != 7 and $row['listorder'] != 8) {
                $vaca_note++;
    ?>
                <tr>
                    <td colspan="4" style="font-size: 18px; text-align: left; text-decoration: underline;">
                        <?php echo "READY REGULAR HEARING MATTERS REGISTERED UPTO YEAR 2013"; ?>
                    </td>
                </tr>
            <?php
            }

            if ($mainhead == "F") {
                $retn = $row["sub_name1"];
                if ($row["sub_name2"])
                    $retn .= " - " . $row["sub_name2"];
                if ($row["sub_name3"])
                    $retn .= " - " . $row["sub_name3"];
                if ($row["sub_name4"])
                    $retn .= " - " . $row["sub_name4"];
            } else {
                if (!empty($subheading)) {
                    foreach ($subheading as $subh_index => $data) {
                        if ($row_index == $subh_index) {
                            $subheading = $data["stagename"];
                        }
                    }
                } else {
                    $subheading = '';
                }
            }

            if ($mnhead_print_once == 1) {
                if ($mainhead == 'M' and $subheading != "FOR JUDGEMENT" and $subheading != "FOR ORDER") {
                    if ($row['board_type'] == 'C') {
                        $print_mainhead = "CHAMBER MATTERS";
                    } else {
                        $print_mainhead = "MISCELLANEOUS HEARING";
                    }
                }
                if ($mainhead == 'F')
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
                    <th colspan="4"
                        style="text-align: center; text-decoration: underline;">
                        <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                            echo $print_mainhead;
                        } ?>
                    </th>
                </tr>
                <tr style="font-weight: bold; background-color:#cccccc;">
                    <td style="width:5%;">SNo.</td>
                    <td style="width:20%;">Case No.</td>
                    <td style="width:35%;">Petitioner / Respondent</td>
                    <td style="width:40%;">
                        <?php if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") { ?>
                            Petitioner/Respondent Advocate
                        <?php } ?>
                    </td>
                </tr>

        <?php
                $mnhead_print_once++;
            }
            if ($subheading != $subheading_rep) {

                //old comment condition
                //  if($subheading == "FOR JUDGEMENT" OR $subheading == "FOR ORDER"){
                // echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold;text-decoration:underline; text-align:center;'>".$subheading."</td></tr>";}


                if ($jcd_rp !== "117,210" and $jcd_rp != "117,198") {
                    // echo "<tr><td colspan='4' style='font-size:12px; font-weight:bold; text-decoration:underline; text-align:center;'>" . $subheading . "</td></tr>";
                    // $subheading_rep = $subheading;
                }
            }
            if ($row['diary_no'] == $row['conn_key'] or $row['conn_key'] == 0) {

                $print_brdslno = $psrno;
                $print_srno = $psrno;
                $con_no = "0";
                $is_connected = "";
            } else if ($row['main_or_connected'] == 1) {


                $print_brdslno = "&nbsp;" . $print_srno . "." . ++$con_no;
                $is_connected = "<span style='color:red;'>Connected</span><br/>";
            }

            $m_f_filno = $row['active_fil_no'];
            $m_f_fil_yr = $row['active_reg_year'];

            $filno_array = explode("-", $m_f_filno);

            if (isset($filno_array[1]) && isset($filno_array[2]) && $filno_array[1] == $filno_array[2]) {
                $fil_no_print = ltrim($filno_array[1], '0');
            } else {
                // Ensure both elements exist before attempting to join
                if (isset($filno_array[1]) && isset($filno_array[2])) {
                    $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                } elseif (isset($filno_array[1])) {
                    $fil_no_print = ltrim($filno_array[1], '0'); // If only one part exists
                } else {
                    $fil_no_print = ''; // If no valid parts exist
                }
            }
            
            if ($row['active_fil_no'] == "") {
                $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
            } else {
                $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
            }

            $padvname = "";
            $radvname = "";
            $impldname = "";
            $intervenorname = "";

            if (!empty($advocatesData)) {
                foreach ($advocatesData as $rowadv) {
                    $radvname =  $rowadv["radvname"];
                    $padvname =  $rowadv["padvname"];
                    $impldname = $rowadv["impldname"];
                    $intervenorname = $rowadv["intervenorname"];
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

                $row['section_name'] = $tentativeSecData["section_name"];
            }




            if ($is_connected != '') {
            } else {
                $print_srno = $print_srno;
                $psrno++;
            }
            $doc_desrip = "";
            $listed_ias = $row['listed_ia'];
            $listed_ia = rtrim(trim($listed_ias), ",");
            if ($listed_ias) {
                $listed_ia = "I.A. " . str_replace(',', '<br>I.A.', $listed_ia) . " In <br>";

                if (!empty($rs_dc)) {
                    foreach ($rs_dc as $row_dc) {
                        $doc_desrip .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                        $doc_desrip .= "IA No. " . $row_dc['docnum'] . "/" . $row_dc['docyear'] . " - " . $row_dc['docdesp'];
                        $doc_desrip .= "</td><td></td></tr>";
                    }
                }
            }

            $cate_old_id1 = "";

            // Extract category_sc_old from mulCategoryData
            if (!empty($mulCategoryData)) {
                foreach ($mulCategoryData as $item) {
                    if (is_object($item)) {
                        $cate_old_id1 = $item->category_sc_old ?? '';
                    } elseif (is_array($item)) {
                        $cate_old_id1 = $item['category_sc_old'] ?? '';
                    } else {
                        $cate_old_id1 = ''; // Fallback if structure is unexpected
                    }
                }
            }
            $output = "";
            $if_sclsc = "";
            // Start building the output
            $output .= "<tr><td style='vertical_align:top;' valign='top'>$print_brdslno</td>";
            $output .= "<td style='vertical_align:top;' valign='top'>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $if_sclsc . " " . $row['section_name'] . "<br/>" . $cate_old_id1 . "</td>";
            $output .= "<td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top'>" . $pet_name . "</td>";

            // Process petitioner advocate names
            $padvname_x = str_replace(",", ", ", trim($padvname, ","));
            if ($padvname_x) {
                $x60 = 150;
                $lines = explode("\n", wordwrap($padvname_x, $x60));
                foreach ($lines as $k => $line) {
                    if ($k == 0) {
                        $output .= "<td valign='top'>" . $line . "</td></tr>";
                    } else {
                        $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $line . "</td></tr>";
                    }
                }
            } else {
                $output .= "<td></td></tr>";
            }

            // Add versus section
            $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; font-style: italic;' valign='top'>Versus</td><td style='font-style: italic;'></td></tr>";
            $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px;' valign='top'>" . $res_name . "</td>";

            // Process respondent advocate names
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
                foreach ($lines as $k => $line) {
                    if ($k == 0) {
                        $output .= "<td valign='top'>" . $line . "</td></tr>";
                    } else {
                        $output .= "<tr><td></td><td></td><td></td><td valign='top'>" . $line . "</td></tr>";
                    }
                }
            } else {
                $output .= "<td></td></tr>";
            }

            // Add purpose row if conditions are met
            $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
            if (in_array($row['listorder'], ['4', '5', '7', '8'])) {
                $output .= "{" . $row['purpose'] . "}";
            }
            $output .= "</td><td></td></tr>";



            // if (!empty($rs_lct)) {
            //     $output .= "<tr><td></td><td></td><td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
            //     foreach ($rs_lct as $index=> $ro_lct) {
            //         if($row['index'] == $ro_lct['index'] ){
            //             $output .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
            //         }

            //     }
            //     $output .= "</td><td></td></tr>";
            // }

            if (!empty($rs_lct)) {
                foreach ($rs_lct as $rs_lct_index => $ro_lct) {
                    if ($row_index == $rs_lct_index) {
                        $output .= "<tr><td></td><td></td>";
                        $output .= "<td style='vertical_align:top; text-align: left; padding-left:20px; padding-right:15px;' valign='top'>";
                        $output .= " IN " . $ro_lct['type_sname'] . " - " . $ro_lct['lct_caseno'] . "/" . $ro_lct['lct_caseyear'] . ", ";
                        $output .= "</td><td></td></tr>";
                    }
                }
            }

            if (!empty($str_brdrem)) {
                foreach ($str_brdrem as $str_brdrem_index => $data) {
                    if ($row_index == $str_brdrem_index) {
                        if (!empty($data['remark'])) {
                            $x60 = 150;
                            $lines = explode("\n", wordwrap($data['remark'], $x60));
                            // Loop through the wrapped lines and append them to the output
                            for ($k = 0; $k < count($lines); $k++) {
                                // Generate table rows for each line of the remark
                                $output .= "<tr><td></td><td></td><td style='vertical-align: top; text-align: left; padding-left: 20px; padding-right: 15px; font-weight: bold; color: blue;' valign='top'>";
                                $output .= htmlspecialchars($lines[$k]); // Escape any special HTML characters
                                $output .= "</td><td></td></tr>";
                            }
                        }
                    }
                }
            }

            $relief = '';
            if ($relief) {
                $output .= "<tr><td></td><td></td><td style='vertical_align:top; padding-left:20px; padding-right:15px; font-weight:bold; color:blue;' valign='top'>";
                $output .= "Relief : " . $relief;
                $output .= "</td><td></td></tr>";
            }


            $output .= $doc_desrip;

            $output .= "<tr><td style='border-bottom:0px dotted #999999; padding-bottom:10px; size : 2px; height:2px;' colspan=4></td></tr>";
            echo $output;
            $output = "";

            /// Start Connected case details here

            /// END Connected case details

        } //END OF WHILE LOOP
        ?>
</table>
<?php } else {
        echo "No Records Found";
    }
?>

<?php
//FIRST WHILE LOOP
//
?>
<br>
<p align='left' style="font-size: 12px;"><b>NEW DELHI<BR /><?php date_default_timezone_set('Asia/Kolkata');
                                                            echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
<p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>

</div>