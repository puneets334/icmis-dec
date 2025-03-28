<div id="prnnt" style="font-size:12px;">
        <?php
       $output='';

        ?>

        <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
            <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px"/><br/>
                  
            SUPREME COURT OF INDIA
                    <!--<br/><br/>[ IT WILL BE APPRECIATED IF THE LEARNED ADVOCATES<br/>ON RECORD DO NOT SEEK ADJOURNMENT IN THE MATTERS<br/>LISTED BEFORE ALL THE COURTS IN THE CAUSE LIST ]-->
            <br/>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                    <?php echo "ELIMINATION LIST<br/><br/>(Eliminated due to excess matters/not availability of bench etc. for listing)<br>DATE OF LISTING : " . date('d-m-Y', strtotime($_POST['list_dt']));
                    
                    ?>
                </th>
            </tr>
            </thead>
            <?php 
            if (count($elemination_transfer) > 0){


            $psrno = "1";
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            foreach ($elemination_transfer as $row) {                                
                $next_dt_new = date('d-m-Y', strtotime($row['next_dt_new']));                
                $diary_no = $row['diary_no'];          
                $listorder_new = $row['listorder_new'];          

                if ($mnhead_print_once == 1) {
                   
                            $print_mainhead = "MISCELLANEOUS MATTERS";
                      
      
                    ?>
                    <tr>
                        <th colspan="4"
                            style="text-align: center; text-decoration: underline;">
                            <?php    echo $print_mainhead;  ?></th>
                    </tr>
                    <tr style="font-weight: bold; background-color:#cccccc;">
                        <td style="width:5%;">SNo.</td>
                        <td style="width:20%;">Case No.</td>
                        <td style="width:35%;">Petitioner / Respondent</td>
                        <td style="width:40%;">
                            <?php  ?>
                                Petitioner/Respondent Advocate
                            <?php  ?>
                        </td>
                    </tr>

                    <?php
                    $mnhead_print_once++;
                }


                if ($row['diary_no'] == $row['main_key'] OR $row['main_key'] == 0) {
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                } else if ($row['listed'] == 1 OR ($row['diary_no'] != $row['main_key'] AND $row['main_key'] != null)) {
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }
                $m_f_filno = $row['active_fil_no'];
                $m_f_fil_yr = $row['active_reg_year'];
                $filno_array = explode("-", $m_f_filno);
                if ($filno_array[1] == $filno_array[2]) {
                    $fil_no_print = ltrim($filno_array[1], '0');
                } else {
                    $fil_no_print = ltrim($filno_array[1], '0') . "-" . ltrim($filno_array[2], '0');
                }
                if ($row['active_fil_no'] == "") {
                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                }else {
                    $comlete_fil_no_prt = $row['short_description'] . "-" . $fil_no_print . "/" . $m_f_fil_yr;
                }
                $padvname = "";
                $radvname = "";
                                
                $resultsadv = getNotesAdvocate($row["diary_no"]);
                if (isset($resultsadv)) {
                    $rowadv = $resultsadv[0];
                    $radvname = $rowadv["r_n"];
                    $padvname = $rowadv["p_n"];
                    $impldname = $rowadv["i_n"];
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

                /*TEMP

                 * */
                if (($row['section_name'] == null OR $row['section_name'] == '') AND $row['ref_agency_state_id'] != '' and $row['ref_agency_state_id'] != 0) {
                    if ($row['active_reg_year'] != 0)
                        $ten_reg_yr = $row['active_reg_year'];
                    else
                        $ten_reg_yr = date('Y', strtotime($row['diary_no_rec_date']));

                    if ($row['active_casetype_id'] != 0)
                        $casetype_displ = $row['active_casetype_id'];
                    else if ($row['casetype_id'] != 0)
                        $casetype_displ = $row['casetype_id'];

                    $section_ten_rs = cl_print_func2($casetype_displ, $ten_reg_yr, $row['ref_agency_state_id']);
                    if (count($section_ten_rs) > 0) {
                        $section_ten_row = $section_ten_rs[0];
                        $row['section_name'] = $section_ten_row["section_name"];
                    }
                }

                if ($is_connected != '') {
                    $print_srno = "";

                } else {
                    $print_srno = $print_srno;
                    $psrno++;
                }
                $output .= "<tr><td>$print_srno</td><td rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name'] ."<br/>" . $row['name'];

                    if($listorder_new == 4 OR $listorder_new == 5 OR $listorder_new == 25 OR $listorder_new == 32 OR $listorder_new == 7)
                        $output .= "<br/><span style='font-size:10px; color:red'>{SUBJECT TO FURTHER DIRECTION}</span>";
                    else
                        $output .= "<br/><span style='font-size:10px; color:green'>{Likely to be listed on " . $next_dt_new . "}</span>"; 
                    
                $output .= "</td><td>" . $pet_name . "</td><td>" . str_replace(",", ", ", trim($padvname, ","));
                
                
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td style='font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td></td><td";

                $output .= ">" . $res_name . "</td><td>" . str_replace(",", ", ", trim($radvname, ","));
                $output .= "</td></tr>";
                if ($mainhead == "M" OR $mainhead == "F") {
                    $output .= "<tr><td colspan='2'></td><td colspan='2' style='font-weight:bold; color:blue;'></td></tr>";
                 
                }

                echo $output;
                $output = "";

            }//END OF WHILE LOOP
            ?>
        </table>
        <?php
        }
        else {
            echo "No Records Found";
        }

        ?>

        <br>
        <p align='left' style="font-size: 12px;"><b>NEW DELHI<BR/><?php date_default_timezone_set('Asia/Kolkata');
                echo date('d-m-Y H:i:s'); ?></b>&nbsp; &nbsp;</p>
        <br>
        <p align='right' style="font-size: 12px;"><b>ADDITIONAL REGISTRAR</b>&nbsp; &nbsp;</p>
 
    </div>

    <div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: fixed; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">
       
        <input name="prnnt1" type="button" id="prnnt1" value="Print">

    </div>
    <center></center>
    