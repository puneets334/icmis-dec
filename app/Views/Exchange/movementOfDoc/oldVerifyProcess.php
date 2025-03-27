<?php {
    $check_for_regular_case = "";

    $ucode = $usercode;

    if ($ct != '')
    {
       


        //$get_dno = $model->getDiaryNumber1($ct, $cn, $cy);

        $get_dno = $model1->get_case_details_by_case_no($ct, $cn, $cy);
       

        if (!empty($get_dno))
        {
            $d_no = $get_dno['diary_no'];
            $d_yr = $cy;
        }
        else
        {
           
            
            $d_no = '';
            $d_yr = '';
        }
        
    }
   


    $sql = $model->getDiaryDetails1($d_no, $d_yr);

  


    $main_fh_fil_no = "";
    $fil_no=[];
    if (!empty($sql))
    {
        $fil_no = $sql;


        if ($fil_no['diary_no'] != $fil_no['conn_key'] and $fil_no['conn_key'] != '')
            $check_for_conn = "N";
        else
            $check_for_conn = "Y";
        if ($fil_no['fil_no_fh'] != '')
            $main_fh_fil_no = "EXIST";
        ?>
        <h4 align="center">
            Supreme Court of India
        </h4>
        <div style="text-align: center">
            <h3>Diary No.- <?php echo $d_no; ?> - <?php echo $d_yr; ?></h3>
        </div>

        <?php
        $d_no_yr = $d_no . $d_yr;
    

        navigate_diary($d_no_yr);
        $t_res_ct_typ = $model->getShortDescription($fil_no['casetype_id']);

        $res_ct_typ = $t_res_ct_typ['short_description'];
        $res_bnch = $model->getBenchName($fil_no['bench']);


        $result = $model->get_diary_details1($fil_no['diary_no']);

        $ctr_p = 0; //for counting petining 
        $ctr_r = 0; // for couting respondent

        if (!empty($result)) {
            $grp_pet_res = '';
            $pet_name = $res_name = "";
            foreach ($result as $row) {
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


                    $t_var = $model->getDistrictName1($row['state'], $row['city']);

                    if (!empty($t_var) && isset($t_var['name'])) {
                        $temp_var .= ", District : " . $t_var['name'];
                    }
                    

                if ($row['pet_res'] == 'P') {
                    $pet_name = $temp_var;
                } else {
                    $res_name = $temp_var;
                }
            } ?>
            <div class="cl_center">
                <h3>Case Details</h3>
            </div>
            <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                <tr>
                    <td style="width: 15%">
                        Case No.
                    </td>
                    <td>
                        <?php

                        if ($fil_no['ct1'] != '') {

                            $res_ct_typ = $model->getShortDescription($fil_no['ct1']);

                            $short_desc = isset($res_ct_typ['short_description']) ? $res_ct_typ['short_description'] : '';

                            echo $short_desc . " " . $fil_no['crf1'] . " - " . $fil_no['crl1'] . "&nbsp;&nbsp;&nbsp;Registered on " . $fil_no['fil_dt_f'];
                        }
                        if ($fil_no['ct2'] != '') {
                            $check_for_regular_case = "FOUND";
                            $res_ct_typ = $model->getShortDescription($fil_no['ct1']);
                            $short_desc = isset($res_ct_typ['short_description']) ? $res_ct_typ['short_description'] : '';

                            echo "</br>" . $short_desc . " " . $fil_no['crf2'] . " - " . $fil_no['crl2'] . "&nbsp;&nbsp;&nbsp;Registered on " . $fil_no['fil_dt_fh'];
                        }
                        ?>
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
                        //                          

                        $mul_category = get_mul_category($fil_no['diary_no']);
                        echo $mul_category;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Act
                    </td>
                    <td>
                        <?php
                       

                        $act = $model->getActSections1($fil_no['diary_no']);
                        $act_section = '';
                        if (!empty($act)) {
                            $act_section = '';
                            foreach ($act as $row1) {
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
                        $pol = $model->getLawById($fil_no['actcode']);
                        echo $pol;
                        ?>
                    </td>
                </tr>
                <?php

                ?>
                <tr>
                    <td style="width: 15%">
                        Petitioner Advocate
                    </td>
                    <td>
                        <?php echo get_advocates1($fil_no['pet_adv_id'], 'wen'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Respondant Advocate
                    </td>
                    <td>
                        <?php echo get_advocates1($fil_no['res_adv_id'], 'wen'); ?>
                    </td>

                </tr>
                <tr>
                    <td>
                        Last Order
                    </td>
                    <td>
                        <?php echo $fil_no['lastorder']; ?>
                    </td>
                </tr>
                <?php
           
                if ($fil_no['c_status'] == 'P') {

                    $ttv = $model->getTentativeClDt($fil_no['diary_no']);
                    $r_ttv = $ttv;
                   

                    $result_sql_display = $model->getDisplayFlag1();
                   
                    $result_array = $result_sql_display;
                    if ($result_array['display_flag'] == 1 || in_array($ucode, explode(',', $result_array['always_allowed_users'])))
                    {
                        
                ?>
                
                        <tr>
                            <td>
                                Tentative Date
                            </td>
                            <td>
                                <?php
                               
                               $tentativeDate = $r_ttv['tentative_cl_dt'] ?? null;
                               if ($tentativeDate && get_display_status_with_date_differnces($tentativeDate) == 'T') {
                                    $tentative_date = $r_ttv['tentative_cl_dt'];
                                    echo change_date_format($tentative_date);
                                }
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
                            <?php echo '<font color=red>Case is Disposed</font>'; ?>
                        </td>
                    </tr>

                <?php

                }
                ?>
            </table>

        <?php
        } else {
        ?>

            <div class="cl_center"><b>No Record Found</b></div>
        <?php
        }


        //IAN
       

        $results_ian = $model->getIANDoccode($fil_no['diary_no']);
        
        $iancntr = 1;
        $counter = 1;
        $vcounter = 0;
        $ian='';
        $ian_p='';
        if (!empty($results_ian)) {
        ?>
            <div class="cl_center">
                <h3>INTERLOCUTARY APPLICATIONS</h3>
            </div>
            <?php

            foreach ($results_ian as $row_ian) {
                $verified = '';
                $ian_p ='';
                if ($row_ian['verified'] == 'V')
                    $verified = "Verified";
                if ($row_ian['verified'] == 'R')
                    $verified = "Rejected";
                if ($ian_p == "" and $row_ian["iastat"] == "P") {
                    $ian_p =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $ian_p .= "<tr><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                }
                if ($iancntr == 1) {
                    $ian = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $ian .= "<tr><td></td><td align='center' width='30px'><b>S.NO.</b></td><td align='center' width='120px'><b>Doc. Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td><b>Verified/ Rejected</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
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
                $t_check = '';
                if ($verified == '') {
                    $vcounter++;
                    $t_check = "<input type='checkbox' name='chk" . $counter . "' id='chk" . $counter . "' value='" . $row_ian['diary_no'] . '-' . $row_ian['doccode'] . '-' . $row_ian['doccode1'] . '-' . $row_ian['docnum'] . '-' . $row_ian['docyear'] . "'/>";
                }
                $ian .= "<tr><td>" . $t_check . "</td><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'>" . $verified . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";

                $iancntr++;
                $counter++;
            }
        }
        if ($ian != "")
            $ian .= "</table><br>";
        if ($ian_p != "")
            $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";
        echo $ian;
        //IA END
        //OTHER DOCUMENTS

        $results_od = $model->getOtherDoccode($fil_no['diary_no']);
       
        $odcntr = 1;
        $oth_doc ='';
        if (!empty($results_od)) {
            ?>
            <div class="cl_center">
                <h3>DOCUMENTS FILED</h3>
            </div>
            <?php
            foreach ($results_od as $row_od) {
                $verified = '';
                if ($row_od['verified'] == 'V')
                    $verified = "Verified";
                if ($row_od['verified'] == 'R')
                    $verified = "Rejected";
                if ($odcntr == 1) {
                    $oth_doc =  '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                    $oth_doc .= "<tr><td></td><td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td><td><b>Verified/ Rejected</b></td></tr>";
                }
                if (trim($row_od["docdesc"]) == 'OTHER')
                    $docdesc = $row_od["other1"];
                else
                    $docdesc = $row_od["docdesc"];
                if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0)
                    $doc_oth = ' Fees Mode: ' . $row_od["feemode"] . ' For Resp: ' . $row_od["forresp"];
                else
                    $doc_oth = '';
                $t_check = '';

                if ($verified == '') {
                    $vcounter++;
                    $t_check = "<input type='checkbox' name='chk" . $counter . "' id='chk" . $counter . "' value='" . $row_od['diary_no'] . '-' . $row_od['doccode'] . '-' . $row_od['doccode1'] . '-' . $row_od['docnum'] . '-' . $row_od['docyear'] . "'/>";
                }
                $oth_doc .= "<tr><td>" . $t_check . "</td><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td><td align='center'>" . $verified . "</td></tr>";
                $odcntr++;
                $counter++;
            }
            if ($oth_doc != "")
                $oth_doc .= "</table><br>";
        }
        echo $oth_doc;
        if ($vcounter > 0) {
            ?>
            <p align="center"><input type="button" class="vrbutton" value="Registration of I.A./Doc." onclick="verifyFunction('V');">&nbsp;
            <input type="button" class="vrbutton" value="Reject" onclick="verifyFunction('R');"></p>
        <?php
        }
    }
}
?>
<input type="hidden" name="dn" id="dn" 
       value="<?php echo isset($fil_no['diary_no']) ? $fil_no['diary_no'] : ''; ?>" />

<input type="hidden" name="sh" id="sh" value="<?php /* print $subhead; */ ?>" />   
<input type="hidden" name="da_hidden" id="da_hidden" value="<?php echo ''; ?>" />
<input type="hidden" name="ucode" id="ucode" value="<?php echo $ucode; ?>" />
<input type="hidden" name="check_for_regular_case" id="check_for_regular_case" value="<?php echo $check_for_regular_case; ?>" />