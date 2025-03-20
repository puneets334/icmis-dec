    <div id="prnnt" style="font-size:12px;">
        <div align=center style="font-size:12px;"><SPAN style="font-size:12px;" align="center"><b>
            <img src="<?= base_url('images/scilogo.png'); ?>" width="50px" height="80px"/><br/>
            SUPREME COURT OF INDIA
            <br/>
        </div>
        <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
            <thead>
            <tr>
                <th colspan="4" style="text-align: center;">

                    <?php echo "DAILY LIST DATED : " . date('d-m-Y', strtotime($list_dt)); ?>
                    <BR>
                    REGISTRAR CHAMBER<br>
                    <?PHP 
                        $row_reg=get_judge_data($jcode);
                        if(isset($row_reg)){
                            echo $row_reg['first_name'].' '.$row_reg['sur_name'].', '.$row_reg['title'];
                        }
                        
                    ?>
                    <BR>
                    NOT READY/INCOMPLETE MATTERS
                </th>
            </tr>
            </thead>
            <?php

            if($_POST['sec_id'] != '0'){
                $ten_sect = " tentative_section(m.diary_no) = '$_POST[sec_id]' AND ";
            }

            if (count($getlistingCount) > 0){
            $head2013 = 1;
            $psrno = "1";
            $clnochk = 0;
            $subheading_rep = "0";
            $mnhead_print_once = 1;
            $output = "";
            foreach($getlistingCount as $row) {
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
                if ($row['reg_no_display'] == "") {
                    $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                } else {
                    $comlete_fil_no_prt = $row['reg_no_display'];
                }
                $padvname = "";
                $radvname = "";
                $resultsadv = get_advocate_data($row["diary_no"]);
                if (count($resultsadv) > 0) {
                    $rowadv = $resultsadv[0];
                    $radvname=  strtoupper($rowadv["r_n"]);
                    $padvname=  strtoupper($rowadv["p_n"]);
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
                $is_connected = "";
                $res_sm = vac_reg_cl_fun1($diary_no);
                if (count($res_sm) > 0) {
                    $cate_old_id = $res_sm;
                    $cate_old_id1 = $cate_old_id['category_sc_old'];
                }
                $output .= "<tr><td style='vertical-align: top;'>" . $psrno . "</td><td style='vertical-align: top;' rowspan=2>" . $is_connected . "$comlete_fil_no_prt" . "<br/>" . $row['section_name']."<br>".$row['name']."<br/>" . $cate_old_id1;
                $output .= "</td><td style='vertical-align: top;'>" . $pet_name . "</td><td style='vertical-align: top;'>" . str_replace(",", ", ", trim($padvname, ","));
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td style='vertical-align: top; font-style: italic;'>Versus</td><td style='font-style: italic;'>";
                $output .= "</td></tr>";
                $output .= "<tr><td></td><td></td><td style='vertical-align: top;'";
                $output .= ">" . $res_name . "</td><td style='vertical-align: top;'>" . str_replace(",", ", ", trim($radvname, ","));
                $output .= "</td></tr>";

                $res_lw = vac_reg_cl_fun2($row["diary_no"]);
                if(count($res_lw)>0){
                    foreach ($res_lw as $ro_lw)
                    {
                    $output .= "<tr><td></td><td></td><td style='vertical-align: top; font-size:10px;'> <u>Challenged Lower Court Case No. : ".$ro_lw['type_sname']."-".$ro_lw['lct_caseno']."-".$ro_lw['lct_caseyear']." Order Date : ";
                        if($ro_lw['lct_dec_dt']=='1970-01-01' || $ro_lw['lct_dec_dt']=='0000-00-00' )
                        { $output.=" ";} else { 
                        if ($ro_lw['lct_dec_dt'] == 0 && !is_null($ro_lw['lct_dec_dt'])) 
                        {
                            $output.= date('d-m-Y',strtotime($ro_lw['lct_dec_dt']));
                        }
                        }
                        $output.= " State : ".$ro_lw['name'];
                        $output.= " Agency Name : ".$ro_lw['agency_name'];
                        if($ro_lw['ct_code']=='4')
                            $output.= " (Supreme Court)";
                        else  if($ro_lw['ct_code']=='1')
                            $output.= " (High Court)";
                        else  if($ro_lw['ct_code']=='3')
                            $output.= " (District Court)";
                        else  if($ro_lw['ct_code']=='2')
                            $output.= " (Other)";
                        else  if($ro_lw['ct_code']=='5')
                            $output.= " (State Agency)";
                    }
                    $output .= "</u></td><td style='vertical-align: top;'></td></tr>";
                }
                $res_def = vac_reg_cl_fun3($row["diary_no"]);
                if(isset($res_def)){
                    $ro_def = $res_def;
                    if(!empty($ro_def->defect_notified)){
                        $output .= "<tr><td></td><td></td><td style='vertical-align: top;' >Defects Notified Date : ".date('d-m-Y',strtotime($ro_def->defect_notified));
                        
                        $ro_ref = vac_reg_cl_fun4($row["diary_no"]);
                        if ($ro_ref->count_zero == 0 && !is_null($ro_ref->refiled)) {
                            $output .= "<br>Re-Filed On " . date('d-m-Y', strtotime($ro_ref->refiled));
                        }
                        $output .= "</td><td style='vertical-align: top;'></td></tr>";
                    }

                }

                $output .= "<tr style='height:5px;'><td></td><td></td><td style='vertical-align: top;' ></td><td style='vertical-align: top;'></td></tr>";

                $psrno++;
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
        <input name="prnnt1" type="button" id="prnnt1" value="Print" >
    </div>
    <center></center>
   