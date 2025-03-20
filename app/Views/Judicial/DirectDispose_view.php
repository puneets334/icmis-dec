<?= view('header') ?>


 <!-- Main content -->
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Judicial > Data Updation - Direct Dispose</h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">                                        
                                        <a href="<?= base_url() ?>/Judicial/DirectDispose">
                                            <button class="btn btn-primary btn-sm" type="button">
                                                <i class="fa fa-search-plus" aria-hidden="true" style="font-size:21px"></i></button>
                                            </a>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <? //view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->
                        <style>
                            #newc{
                                display: none;
                                position: absolute;
                                top: 2%;
                                background: #fff;
                                border: 1px dotted #000;
                                padding: 1%;
                            }
                        </style>
                        <link rel="stylesheet" href="<?php echo base_url();?>/dp/jquery-ui.css" type="text/css"/>
                        <script src="<?php echo base_url();?>/js/menu_js.js"></script>
                        <script src="<?php echo base_url();?>/dp/jquery-ui.js"></script>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <?php
                                                $attribute = array('class' => 'form-horizontal','name' => 'desposeview', 'id' => 'desposeview', 'autocomplete' => 'off');
                                                echo form_open(base_url(''), $attribute);
                                            ?>
                                            <div class="active tab-pane" id="">

                                                <div>
                                                    <?php
                                                    $listed_ia = "";
                                                    $cldate = "";
                                                    $output = "";
                                                    $main_case = "";
                                                    $t_slpcc = "";
                                                    $t_spl = $act_section = "";
                                                    $check_for_case_is_listed_after_current_date_remark = "";
                                                    $rmtable = "";
                                                    $ucode = $user_idd;

                                                    if ( !isset($casedesc["notFound"]) ) {
                                                        $filno = $casedesc['filno'];
                                                        $main_fh_diary_no = "";
                                                        if (count($filno) > 0) {

                                                            if ( $user_idd != $filno['dacode'] && $user_idd != 1 ) {            
                                                                $section_rs = $casedesc['section_rs'];
                                                                // echo "<pre>"; print_r($section_rs); die;
                                                                $usersection = $section_rs[0]['section'] ? $section_rs[0]['section'] : $section_rs[0];
                                                                $usertype = $section_rs[0]['usertype'] ? $section_rs[0]['usertype'] : $section_rs[1];
                                                                //section 62- registrar court, section 81-registrar court, section 11- court
                                                                if ($usersection != 62 && $usersection != 81 && $usersection != 11 && ($usersection != 30 && $usertype != 14) && ($usersection != 19 && $usertype != 9 && $usertype != 6 && $usertype != 4) ) { ?>
                                                                    <p align=center><font color=red>Only DA can Dispose Case</font></p>
                                                                <?php }
                                                            }

                                                            $isconn = $filno["ccdet"];
                                                             $connto = $filno["connto"];
                                                            $diaryno = $filno["diary_no"];

                                                            if ( $filno["diary_no"] != $filno["conn_key"] && $filno["conn_key"] != "") {
                                                                $check_for_conn = "N";
                                                            } else {
                                                                $check_for_conn = "Y";
                                                            }
                                                            if ($filno["fil_no_fh"] != "") {
                                                                $main_fh_diary_no = "EXIST";
                                                            }
                                                            ?>
                                                            <div style="text-align: center">
                                                            <?php
                                                                $dyr = substr($d_yr, -4); 
                                                                $dyear = $dyr;
                                                                $dnum = str_replace($dyr,"",$d_yr);
                                                            ?>
                                                                <strong>Diary No.- <?php echo $dnum; ?> - <?php echo $dyear; ?></strong>
                                                            </div>
                                                            <?php
                                                            //navigate_diary($diaryno);
                                                            
                                                            $output .= '<table border="0"  align="left" width="100%">';
                                                            if ($main_case != "") {
                                                                $main_case = "<br>&nbsp;&nbsp;<font color='red' >[Connected with : " .$main_case ."</font>]";
                                                            }

                                                            $u_name = "";
                                                            $results_da = $casedesc['results_da'];
                                                            if (count($results_da) > 0) {
                                                                $row_da = $results_da[0];
                                                                $u_name = " by <font color='blue'>" . $row_da["name"] . "</font>";
                                                                $u_name .=
                                                                    "<font> [SECTION: </font><font color='red'>" .
                                                                    $row_da["section_name"] .
                                                                    "</font><font style='font-size:12px;font-weight:bold;'>]</font>";
                                                            }
                                                            
                                                            $t_res_ct_typ = $casedesc['t_res_ct_typ'];
                                                            if(!empty($t_res_ct_typ)){
                                                                $t_res_ct_typ = $t_res_ct_typ[0];
                                                                $res_ct_typ = $t_res_ct_typ["short_description"];
                                                            }

                                                            $result_data = $casedesc['result'];
                                                            $ctr_p = 0; //for counting petining
                                                            $ctr_r = 0; // for couting respondent

                                                            if (count($result_data) > 0) {

                                                                $grp_pet_res = "";
                                                                $pet_name = $res_name = "";
                                                                foreach ($result_data as $row) {                
                                                                    $temp_var = "";
                                                                    $temp_var .= $row["partyname"];
                                                                    if ($row["sonof"] != "") {
                                                                        $temp_var .= $row["sonof"] . "/o " . $row['prfhname'];
                                                                    }
                                                                    if ($row["deptname"] != "") {
                                                                        $temp_var .= "<br>Department : " . $row["deptname"];
                                                                    }
                                                                    $temp_var .= "<br>";
                                                                    if ($row["addr1"] == "") {
                                                                        $temp_var .= $row["addr2"];
                                                                    } else {
                                                                        $temp_var .= $row["addr1"] . ", " . $row["addr2"];
                                                                    }

                                                                    $t_var = $row["temp_district_name"];
                                                                    if ($t_var != "") {
                                                                        $temp_var .= ", District : " . $t_var;
                                                                    }

                                                                    if ($row["pet_res"] == "P") {
                                                                        $pet_name = $temp_var;
                                                                    } else {
                                                                        $res_name = $temp_var;
                                                                    }
                                                                    $case_no = $row["case_no"];
                                                                    $year = $row["year"];
                                                                    $diary_no_rec_date = $row["diary_no_rec_date"];
                                                                }
                                                                ?>
                                                                <div class="cl_center"><strong>Case Details</strong></div>  
                                                                <table class="table_tr_th_w_clr c_vertical_align" width="100%">
                                                                        <tr>
                                                                            <td width='140px'>Diary No.</td>
                                                                            <td><div width='100%'><font color='blue' style='font-size:12px;font-weight:bold;'><?php print $case_no; ?>/<?php print $year; ?></font> Received on <?php print $diary_no_rec_date .$u_name .$main_case; ?></div>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                        $t_fil_no = $casedesc['case_nos'];
                                                                        if (trim($t_fil_no) == "") {
                                                                            $results12 = $casedesc['results12'];
                                                                            if (count($results12) > 0) {
                                                                                $row_12 = $results12;
                                                                                $t_fil_no = $row_12["short_description"];
                                                                            }
                                                                        }
                                                                        if ($t_slpcc != "") {
                                                                            $t_slpcc = "<br>" . $t_slpcc;
                                                                        }
                                                                        $t_fil_no1 = "";
                                                                        
                                                                        $rs_lct = $casedesc['rs_lct'];
                                                                        if (count($rs_lct) > 0) {
                                                                            $t_fil_no1 .= "";
                                                                            foreach ($rs_lct as $ro_lct) {
                                                                                if ($t_fil_no1 == "") {
                                                                                    $t_fil_no1 .=" IN " .$ro_lct["type_sname"] ." - " .$ro_lct["lct_caseno"] ."/" .$ro_lct["lct_caseyear"];
                                                                                } else {
                                                                                    $t_fil_no1 .= ", " . $ro_lct["type_sname"] . " - " . $ro_lct["lct_caseno"] . "/" . $ro_lct["lct_caseyear"];
                                                                                }
                                                                            }
                                                                        }
                                                                        echo "<tr>
                                                                            <td>Case No.</td>
                                                                            <td><div width='100%'>" .$t_fil_no .$t_slpcc .$t_fil_no1 ."</div></td></tr>";
                                                                        if ($t_spl != "") {
                                                                            echo "<tr >
                                                                            <td>Special Type</td>
                                                                            <td>" .$t_spl ."</td></tr>";
                                                                        }
                                                                        ?>
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
                                                                                if(!empty($casedesc['mul_category'])){
                                                                                    echo $mul_category = $casedesc['mul_category'][0];
                                                                                }
                                                                                ?> 
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Act</td>
                                                                            <td>
                                                                                <?php
                                                                                $act = $casedesc['act'];
                                                                                if (count($act) > 0) {
                                                                                    $act_section = "";
                                                                                    foreach ($act as $row1) {
                                                                                        if ($act_section == "") {
                                                                                            $act_section = $row1["act_name"] . "-" . $row1["section"];
                                                                                        } else {
                                                                                            $act_section = $act_section . ", " . $row1["act_name"] . "-" . $row1["section"];
                                                                                        }
                                                                                    }
                                                                                }
                                                                                echo $act_section;
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Provision of Law</td>
                                                                            <td>
                                                                                <?php
                                                                                $t_pol = $casedesc['t_pol'];
                                                                                if(!empty($t_pol)){
                                                                                    echo $t_pol["law"];
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $padvname = $radvname = "";
                                                                        $result_advp = $casedesc['result_advp'];
                                                                        foreach ($result_advp as $row_advp) {
                                                                            $tmp_advname = "<p>&nbsp;&nbsp;";
                                                                            $tmp_advname =$tmp_advname .$row_advp["adv_det"] .$row_advp["adv"];
                                                                            $tmp_advname = $tmp_advname . "</p>";

                                                                            if ($row_advp["pet_res"] == "P") {
                                                                                $padvname .= $tmp_advname;
                                                                            }
                                                                            if ($row_advp["pet_res"] == "R") {
                                                                                $radvname .= $tmp_advname;
                                                                            }
                                                                        }


                                                                        $disp_str = '';
                                                                        if ($filno["c_status"] == "D") {
                                                                            $results_rj = $casedesc['results_rj'];
                                                                            // echo "<pre>";
                                                                            // print_r($results_rj); die;
                                                                            if (count($results_rj) > 0) {
                                                                                $row_rj = $results_rj;
                                                                                $disp_str .=" (Order Date: " .$row_rj["ord_dt"] ." and Updated on " .$row_rj["ddt"] .")<br> JUDGES: " .stripslashes($row_rj["judges"]);

                                                                                $row_k1 = $casedesc['row_k1'];
                                                                                $row_k2 = $casedesc['row_k2'];
                                                                                $row_k3 = $casedesc['row_k3'];
                                                                                // echo "<pre>";
                                                                                // print_r($row_k3); die;
                                                                                if (count($row_k3) > 0) {
                                                                                    if (count($row_k1) > 0) {
                                                                                        $results_k3 = $row_k3[0];
                                                                                        $results_k2 = $row_k2[0];
                                                                                        $disp_str .= "<br><span style='color:green;'>Judgement Pronaunced by : " . stripslashes($results_k2["jname"]) . " of the bench comprising : " . stripslashes($results_k3["judges"] ) . "</span>";
                                                                                    }
                                                                                }
                                                                                $disp_dt = $row_rj["disp_dt"];
                                                                                if ($row_rj["rj_dt"] != "0000-00-00" && $row_rj["rj_dt"] != '' && $row_rj["rj_dt"] != null ) {
                                                                                    $rjdate = "&nbsp;&nbsp;&nbsp;RJ Date: " . date("d-m-Y", strtotime($row_rj["rj_dt"]) );
                                                                                }
                                                                                ////Disp type
                                                                                $disptype = $row_rj["disp_type"];
                                                                                if ($disptype != "") {
                                                                                    $results_dsql = $casedesc['results_dsql'];
                                                                                    if (count($results_dsql) > 0) {
                                                                                        $drow = $results_dsql[0];
                                                                                        $d_spk = '';
                                                                                        if ( $ucode == 203 || $ucode == 204 || $ucode == 888 || $ucode == 912) {
                                                                                            if ($drow["spk"] == "N") {
                                                                                                $d_spk .= " (Non Speaking)";
                                                                                            } else {
                                                                                                $d_spk .= " (Speaking)";
                                                                                            }
                                                                                        }
                                                                                        $dispdet = $drow["dispname"] . $d_spk;
                                                                                        if ($disptype == 19) {
                                                                                            $dispdet = $dispdet . " by LOK ADALAT ";
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
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
                                                                        <?php if ($filno["c_status"] == "D" && $disp_str != '') { ?>
                                                                            <tr>
                                                                            <td>
                                                                                Status
                                                                            </td>
                                                                            <td>
                                                                                <label style="font-size: 12px; color: red;"> <?php echo "DISPOSED " . $disp_str . ")"; ?> </label>
                                                                            </td>
                                                                        </tr><?php } ?>
                                                                        <tr>
                                                                            <td>
                                                                                Last Order
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $filno["lastorder"]; ?>
                                                                            </td>
                                                                        </tr>    

                                                                    <?php if ($filno["c_status"] == "P") {
                                                                        $rgo_sql = $casedesc['res_rgo'];
                                                                        $t_rgo = "";
                                                                        if (count($rgo_sql) > 0) {
                                                                            foreach ($rgo_sql as $res_rgo) {
                                                                                if ($t_rgo == "") {
                                                                                    $t_rgo = "D.No. " .get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>"," ",get_casenos_comma($res_rgo["fil_no2"]));
                                                                                } else {
                                                                                    $t_rgo = "<br> " . "D.No. " . get_real_diaryno($res_rgo["fil_no2"]) . "<br>" . str_replace("<br>"," ",get_casenos_comma($res_rgo["fil_no2"]));
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($t_rgo != "") {
                                                                            echo "<tr>
                                                                                <td>Conditional Dispose</td>
                                                                                <td style='font-size:12px;font-weight:100;'><b> <font style='font-size:12px;font-weight:100;'><b>" .
                                                                                $t_rgo .
                                                                                "</b></font></b></td></tr>";
                                                                        }

                                                                        $r_ttv = $casedesc['ttv'];
                                                                        $result_array = $casedesc['result_sql_display'];
                                                                        if(!empty($result_array)){
                                                                            if ( $result_array["display_flag"] == 1 || in_array($ucode, explode(",", $result_array["always_allowed_users"]) )) { ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        Tentative Date
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($casedesc['get_display_status_with_date_differnces'] == "T") {
                                                                                            echo $casedesc['change_date_format'];
                                                                                        } ?>
                                                                                    </td>
                                                                                </tr>                   
                                                                            <?php }
                                                                        }
                                                                        
                                                                        
                                                                        if ($isconn == "Y") {              
                                                                            if($connto !=  '0')
                                                                            {             
                                                                                $connto ="<font color='red'>" .$connto ." </font>(Main Case)";
                                                                            }
                                                                            if($connto == "0"){
                                                                                $connto = "";
                                                                            }    
                                                                            $results_oc = $casedesc['results_oc'];
                                                                            if (count($results_oc) > 0) {
                                                                                foreach ($results_oc as $row_oc) {
                                                                                    $connto .= "<br><font color='blue'>" . $row_oc["diary_no"] . " </font>(Connected Case)";
                                                                                }
                                                                            }
                                                                            
                                                                            echo "<tr valign='top'><td bgcolor='#F4F5F5'>Connected To </td><td><b>" .$connto . "</b></td></tr>";
                                                                        }
                                                                    } else { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    Case Status
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo "<font color=red>Case is Disposed</font>"; ?>
                                                                                </td>
                                                                            </tr>             
                                                                    <?php } ?>            
                                                                </table> 
                                                            <?php } else { ?>
                                                                    <div class="cl_center"><b>No Record Found</b></div>
                                                            <?php }

                                                            $jud1 = 0;
                                                            $jud2 = 0;
                                                            $jud3 = 0;
                                                            $jud4 = 0;
                                                            $jud5 = 0;
                                                            $clno_1 = 0;
                                                            $isconn = "";
                                                            $connto = "";
                                                            $ian = "";
                                                            $ian_p = "";
                                                            $oth_doc = "";
                                                            $listorder = "";
                                                            $jcodes = "";
                                                            $benchmain = "";
                                                            $connchks = "";

                                                            $results_m = $casedesc['results_m'];
                                                            if (count($results_m) > 0) {
                                                                $row_m = $results_m[0];
                                                                $isconn = $row_m["ccdet"];
                                                                $connto = $row_m["connto"];
                                                                //IAN
                                                                $results_ian = $casedesc['results_ian'];
                                                                $iancntr = 1;
                                                                foreach ($results_ian as $row_ian) {
                                                                    if ($ian_p == "" && $row_ian["iastat"] == "P") {
                                                                        $ian_p = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                                                                        $ian_p .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='4'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
                                                                        $ian_p .= "<tr bgcolor='#F4F5F5'><td align='center'><b>&nbsp;</b></td><td align='center'><b>Reg.No.</b></td><td><b>Particular</b></td><td align='center'><b>Date</b></td></tr>";
                                                                    }
                                                                    if ($iancntr == 1) {
                                                                        $ian = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                                                                        $ian .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='6'><font color='red'><b>INTERLOCUTARY APPLICATIONS</b></font></td></tr>";
                                                                        $ian .= "<tr bgcolor='#F4F5F5'><td align='center' width='50px'><b>IA.NO.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Particular</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center' width='70px'><b>Status</b></td></tr>";
                                                                    }
                                                                    if ($row_ian["other1"] != "") {
                                                                        $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                                                                    } else {
                                                                        $t_part = $row_ian["docdesc"];
                                                                    }
                                                                    $t_ia = "";
                                                                    if ($row_ian["iastat"] == "P") {
                                                                        $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                                                                    }
                                                                    if ($row_ian["iastat"] == "D") {
                                                                        $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                                                                    }
                                                                    $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . $t_part . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";

                                                                    if ($row_ian["iastat"] == "P") {
                                                                        $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . $t_part . "' onClick='feed_rmrk();'></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . $t_part . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
                                                                    }
                                                                    $iancntr++;
                                                                }
                                                                if ($ian != "") {
                                                                    $ian .= "</table>";
                                                                }
                                                                if ($ian_p != "") {
                                                                    $ian_p .= "</table>";
                                                                }
                                                                //IAN

                                                                //OTHER DOCUMENTS
                                                                $results_od = $casedesc['results_od'];
                                                                $odcntr = 1;
                                                                foreach ($results_od as $row_od) {
                                                                    if ($odcntr == 1) {
                                                                        $oth_doc = "<table border='1' bgcolor='#FBFFFD' class='tbl_hr' width='98%' cellspacing='0' cellpadding='3'>";
                                                                        $oth_doc .= "<tr bgcolor='#EDF0EE'><td align='center' colspan='6'><font color='red'><b>DOCUMENTS FILED</b></font></td></tr>";
                                                                        $oth_doc .= "<tr bgcolor='#F4F5F5'><td align='center' width='50px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                                                                    }
                                                                    if (trim($row_od["docdesc"]) == "OTHER") {
                                                                        $docdesc = $row_od["other1"];
                                                                    } else {
                                                                        $docdesc = $row_od["docdesc"];
                                                                    }

                                                                    if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0) {
                                                                        $doc_oth = " Fees Mode: " . $row_od["feemode"] . " For Resp: " . $row_od["forresp"];
                                                                    } else {
                                                                        $doc_oth = "";
                                                                    }

                                                                    $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td></tr>";

                                                                    $odcntr++;
                                                                }
                                                                if ($oth_doc != "") {
                                                                    $oth_doc .= "</table>";
                                                                }

                                                                $p = $row_m["pet_name"];
                                                                $r = $row_m["res_name"];
                                                                $status = $row_m["c_status"];
                                                                $lastorder = $row_m["lastorder"];
                                                                $benchmain = $row_m["bench"];
                                                            
                                                                $row11 = $casedesc['row11'];
                                                                if(!empty($row11)){
                                                                    $case_t = $caseno = $row11[0]['skey'] . " - " . intval(substr($diaryno, 5, 5)) . "/" . intval(substr($diaryno, 10, 4));
                                                                }

                                                                $cstatus = "";
                                                                switch ($status) {
                                                                    case "P":
                                                                        $cstatus = "<font color='blue'>Pending</font>";
                                                                        break;

                                                                    case "R":
                                                                        $cstatus = "<font color='red'>Rejected</font>";
                                                                        break;

                                                                    case "D":
                                                                        $cstatus = "<font color='red'>Disposed</font>";
                                                                        break;

                                                                    case "T":
                                                                        $cstatus = "<font color='red'>Transferred</font>";
                                                                        break;
                                                                }

                                                                $head = "";
                                                                $head_r = "";
                                                                $results_party = $casedesc['results_party'];
                                                                foreach ($results_party as $row_pty) {
                                                                    if ($row_pty["pet_res"] == "P") {
                                                                        if ($p == "") {
                                                                            $p .= $row_pty["pn"];
                                                                        } else {
                                                                            $p .= ", " . $row_pty["pn"];
                                                                        }
                                                                    }
                                                                    if ($row_pty["pet_res"] == "R") {
                                                                        if ($r == "") {
                                                                            $r .= $row_pty["pn"];
                                                                        } else {
                                                                            $r .= ", " . $row_pty["pn"];
                                                                        }
                                                                    }
                                                                }

                                                                ////LIST BEFORE
                                                                $pr_bf = $nbf = $bf = "";
                                                                $t_nb = $casedesc['t_nb'];
                                                                if (count($t_nb) > 0) {
                                                                    foreach ($t_nb as $rownb) {
                                                                        $t_jn = $rownb["jn"];
                                                                        $t_jn1 = stripslashes($t_jn);
                                                                        if ($rownb["notbef"] == "B") {
                                                                            if ($bf == "") {
                                                                                $bf .= $t_jn1;
                                                                            } else {
                                                                                $bf .= ",  " . $t_jn1;
                                                                            }
                                                                        }

                                                                        if ($rownb["notbef"] == "N") {
                                                                            if ($nbf == "") {
                                                                                $nbf .= $t_jn1;
                                                                            } else {
                                                                                $nbf .= ",  " . $t_jn1;
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                //LIST BEFORE

                                                                //RJDATE
                                                                $rjdate = "";
                                                                if ($status == "D") {
                                                                    $results_rj = $casedesc['results_rj2'];
                                                                    if (count($results_rj) > 0) {
                                                                        $row_rj = $results_rj[0];
                                                                        if ($row_rj["rj_dt"] != "0000-00-00" && $row_rj["rj_dt"] != '' && $row_rj["rj_dt"] != null) {
                                                                            $rjdate = date("d-m-Y", strtotime($row_rj["rj_dt"]));
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <br>
                                                                <div align="center" style=""><br><br>

                                                                    <?php
                                                                    //Listing Start
                                                                    $result_listing = $casedesc['result_listing'];
                                                                    $result_listing1 = $casedesc['result_listing1'];
                                                                        
                                                                    $subhead = "";
                                                                    $next_dt = "";
                                                                    $lo = "";
                                                                    $sj = "";
                                                                    $bt = "";
                                                                    $check_for_case_is_listed_after_current_date_remark = "";
                                                                    if ( count($result_listing) > 0 || count($result_listing1) > 0 ) {
                                                                        $check_for_case_is_listed_after_current_date = "";
                                                                        $check_for_case_is_listed_after_current_date_remark = "";
                                                                        foreach ($result_listing as $row_listing) {
                                                                            if ( $row_listing["judges"] != "" && $row_listing["judges"] != "0" && $row_listing["clno"] > 0 && $row_listing["brd_slno"] > 0 && $row_listing["roster_id"] > 0 ) {
                                                                                if ( strtotime($row_listing["next_dt"]) > strtotime(date("Y-m-d")) || strtotime($row_listing["next_dt"]) == strtotime(date("Y-m-d")) && strtotime("17:30:10") - strtotime(date("H:i:s")) > 0 ) {
                                                                                    $check_for_case_is_listed_after_current_date = "LISTED";
                                                                                    if ( strtotime($row_listing["next_dt"]) == strtotime(date("Y-m-d")) ) {
                                                                                        $check_for_case_is_listed_after_current_date_remark = "Disposal is LOCKED as Case is already Listed on " . date("d-m-Y", strtotime($row_listing["next_dt"])) . "<br>Case is available for updation after 5:30pm";
                                                                                    } else {
                                                                                        $check_for_case_is_listed_after_current_date_remark = "Disposal is LOCKED as Case is already Listed on " . date("d-m-Y", strtotime($row_listing["next_dt"]));
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                    if ($status != "D") { ?>
                                                                        <table bgcolor="#F5F5FC" class="tbl_hr" width="98%" border="1" cellspacing="0" cellpadding="3">
                                                                            <tr bgcolor='#EAEAF9'>
                                                                                <td align='center'><center>
                                                                                    <font color='red'><b>SET REMARK FOR PENDING / DISPOSE</b></font></center>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align='center'><center>
                                                                                            <?php
                                                                                            $row_courtMaster = $casedesc['row_courtMaster'];
                                                                                            if(!empty($row_courtMaster)){
                                                                                                $row_courtMaster = $row_courtMaster[0];
                                                                                                $is_courtMaster = $row_courtMaster["is_courtmaster"];
                                                                                                $usection = $row_courtMaster["section"];
                                                                                                $usertype = $row_courtMaster["usertype"];
                                                                                            }
                                                                                            
                                                                                        
                                                                                            $sq_ck_da_cd = $casedesc['sq_ck_da_cd'];
                                                                                            if (count($sq_ck_da_cd) > 0) {
                                                                                                $row_lp123 = $sq_ck_da_cd[0];
                                                                                                if ($row_lp123["username"] == "" && $row_lp123["dacode"] == "") {
                                                                                                    $output1 ="0|#|NO DA INFORMATION AVAILABLE FOR THIS CASE|#|" . $row_lp123["empid"];
                                                                                                } elseif ($row_lp123["username"] == "" && $row_lp123["dacode"] != $ucode) {
                                                                                                    $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA USER ID : " . $row_lp123["empid"] . " [DA NAME NOT AVAILABLE]|#|" . $row_lp123["dacode"];
                                                                                                } elseif ($row_lp123["dacode"] != $ucode) {
                                                                                                    $output1 = "0|#|UPDATION/MODIFICATION IN THIS CASE CAN BE DONE ONLY BY DA : " . $row_lp123["username"] . " [USER ID : " . $row_lp123["empid"] . "]|#|" . $row_lp123["dacode"];
                                                                                                } else {
                                                                                                    $output1 = "1|#|RIGHT DA|#|" . $row_lp123["dacode"];
                                                                                                }
                                                                                            }

                                                                                            $result_da = explode("|#|", $output1);
                                                                                            if ( $result_da[0] > 0 || $usection == "11" || $usection == "62" || $usection == "81" || $is_courtMaster == "Y" || ($usection = 30) && ($usertype = 14) || $usersection == 19 && ($usertype == 9 || $usertype == 6 || $usertype == 4) ) {
                                                                                                if ( $check_for_case_is_listed_after_current_date_remark == "" ) {
                                                                                                    $rmtable .= "<input type='button' name='db" . $diaryno . "' onclick='call_div(\"" . $diaryno . "\",this,2,\"\")' value='Set Dispose '>";
                                                                                                } else {
                                                                                                    echo "<center><font color='red'><b>" . $check_for_case_is_listed_after_current_date_remark . "</b></font></center>";
                                                                                                }
                                                                                            } else {
                                                                                                $rmtable .= "<center><font color='red'>" . $result_da[1] . "</font></center>";
                                                                                            }
                                                                                            echo $rmtable;
                                                                                            ?> 
                                                                                </center></td>
                                                                            </tr>
                                                                        </table>
                                                                    <?php }
                                                                    echo "<br>";

                                                                    //connected cases
                                                                    $conncases = $casedesc['conn_cases'];
                                                                    if (count($conncases) > 0) { ?>
                                                                        <div class="cl_center"><strong>CONNECTED / LINKED CASES</strong></div>        
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
                                                                                echo $casedesc['html_conn_cases']['connchks'];
                                                                            ?>
                                                                        </table>
                                                                    <?php }
                                                                    //connected cases


                                                                    //IAN
                                                                    $results_ian = $casedesc['results_ian'];
                                                                    $iancntr = 1;
                                                                    if (count($results_ian) > 0) { ?>
                                                                        <div class="cl_center"><strong>INTERLOCUTARY APPLICATIONS</strong></div>          
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
                                                                            if ($row_ian["other1"] != "") {
                                                                                $t_part = $row_ian["docdesc"] . " [" . $row_ian["other1"] . "]";
                                                                            } else {
                                                                                $t_part = $row_ian["docdesc"];
                                                                            }
                                                                            $t_ia = "";
                                                                            if ($row_ian["iastat"] == "P") {
                                                                                $t_ia = "<font color='blue'>" . $row_ian["iastat"] . "</font>";
                                                                            }
                                                                            if ($row_ian["iastat"] == "D") {
                                                                                $t_ia = "<font color='red'>" . $row_ian["iastat"] . "</font>";
                                                                            }
                                                                            $ian .= "<tr><td align='center'>" . $iancntr . "</td><td align='center'><b>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</b></td><td>" . str_replace("XTRA", "", $t_part) . "</td><td>" . $row_ian["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td><td align='center'><b>" . $t_ia . "</b></td></tr>";

                                                                            if ($row_ian["iastat"] == "P") {
                                                                                $t_iaval = $row_ian["docnum"] . "/" . $row_ian["docyear"] . ",";
                                                                                if (strpos($listed_ia, $t_iaval) !== false) {
                                                                                    $check = "checked='checked'";
                                                                                } else {
                                                                                    $check = "";
                                                                                }
                                                                                $ian_p .= "<tr><td align='center'><input type='checkbox' name='iachbx" . $iancntr . "' id='iachbx" . $iancntr . "' value='" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "|#|" . str_replace("XTRA", "", $t_part) . "' onClick='feed_rmrk();'  " . $check . "></td><td align='center'>" . $row_ian["docnum"] . "/" . $row_ian["docyear"] . "</td><td align='left'>" . str_replace("XTRA", "", $t_part) . "</td><td align='center'>" . date("d-m-Y", strtotime($row_ian["ent_dt"])) . "</td></tr>";
                                                                            }
                                                                            $iancntr++;
                                                                        }
                                                                    }
                                                                    if ($ian != "") {
                                                                        $ian .= "</table><br>";
                                                                    }
                                                                    if ($ian_p != "") {
                                                                        $ian_p .= "</table><br><span style='font-align:left;'><font size=+1 color=blue>If any disposed IA is listed here then disposed it off using IA UPDATE module before proposal updation</font></span>";
                                                                    }
                                                                    echo $ian;
                                                                    //IA END


                                                                    //OTHER DOCUMENTS
                                                                    $results_od = $casedesc['results_od'];
                                                                    $odcntr = 1;
                                                                    if (count($results_od) > 0) { ?>
                                                                        <div class="cl_center"><strong>DOCUMENTS FILED</strong></div>          
                                                                        <?php
                                                                        foreach ($results_od as $row_od) {
                                                                            if ($odcntr == 1) {
                                                                                $oth_doc = '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                                                                                $oth_doc .= "<tr><td align='center' width='30px'><b>S.N.</b></td><td align='center' width='120px'><b>Reg.No.</b></td><td><b>Document Type</b></td><td><b>Filed By</b></td><td align='center' width='80px'><b>Date</b></td><td align='center'><b>Other</b></td></tr>";
                                                                            }
                                                                            if (trim($row_od["docdesc"]) == "OTHER") {
                                                                                $docdesc = $row_od["other1"];
                                                                            } else {
                                                                                $docdesc = $row_od["docdesc"];
                                                                            }
                                                                            if ($row_od["doccode"] == 7 and $row_od["doccode1"] == 0) {
                                                                                $doc_oth = " Fees Mode: " . $row_od["feemode"] . " For Resp: " . $row_od["forresp"];
                                                                            } else {
                                                                                $doc_oth = "";
                                                                            }
                                                                            $oth_doc .= "<tr><td align='center'>" . $odcntr . "</td><td align='center'><b>" . $row_od["docnum"] . "/" . $row_od["docyear"] . "</b></td><td>" . $docdesc . "</td><td>" . $row_od["filedby"] . "</td><td align='center'>" . date("d-m-Y", strtotime($row_od["ent_dt"])) . "</td><td align='center'>" . $doc_oth . "</td></tr>";
                                                                            $odcntr++;
                                                                        }
                                                                        if ($oth_doc != "") {
                                                                            $oth_doc .= "</table><br>";
                                                                        }
                                                                    }
                                                                    echo $oth_doc;
                                                                    //OTHER DOCUMENTS
                                                                    echo "<br>";
                                                                    ?>
                                                                </div>


                                                                 <!-- Modal -->
                                                                <!-- <div id="myModal" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog" style="margin: 3rem 20rem;">
                                                                        <div class="modal-content" style="width: 90rem;">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title"></h4>
                                                                            </div>
                                                                            <div class="modal-body" >
                                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div> -->
                                                                <div id="newc">
                                                                    <div id="newc1" align="center">
                                                                        <table border="0" width="100%">
                                                                            <tr>
                                                                                <td align="center" ><center>
                                                                                <input type='button' name='insert3' id='insert3' value="Save" onClick="return save_rec(2);">
                                                                                <input type="button" name="close3" id="close3" value="Cancel" onClick="return close_w(2)">
                                                                                <input type="hidden" name="tmp_casenod" id="tmp_casenod" value=""/>
                                                                                <input type="hidden" name="tmp_casenosub" id="tmp_casenosub" value=""/></center>              
                                                                                </td>
                                                                                <td align="center">
                                                                                    <center><b><font color="#000">Disposal Remark in </font></b><b><span id="disp_head"></span></b></center>
                                                                            </td>                
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div id="newc123" style="overflow:auto;"> 
                                                                        <table width="100%" border="1" style="border-collapse: collapse">
                                                                            <?php if ($cldate == "") {
                                                                                $cldate = date("d-m-Y");
                                                                            } ?>
                                                                            <tr>
                                                                                <td align="center"><b><font size="+1">Cause List/Order Date : </font></b>&nbsp;<input class="dtp" type="text" name="cldate" id="cldate" value="<?php echo $cldate; ?>" size="12" readonly="readonly"><input type="button" id="btn_coram" onclick="get_coram('<?php echo $diaryno; ?>','<?php echo $cldate; ?>');" name="btn_coram" value="Get Coram"></td>
                                                                                <input type="hidden" id="hdn_cldate" value=""/>
                                                                                <td id="td_coram" align="center" rowspan="4"><b><font size="+1">Coram : </font></b>&nbsp;
                                                                                    <select size="1" name="djudge" id="djudge" class="searchable-dropdown">

                                                                                        <?php     
                                                                                        echo '<option value =""> </option>';
                                                                                        $results2 = $casedesc['results2'];
                                                                                        $tjud1 = $tjud2 = $tjud3 = $tjud4 = $tjud5 = "";
                                                                                        $cljudge1 = "";
                                                                                        $cljudge2 = "";
                                                                                        $cljudge3 = "";
                                                                                        $cljudge4 = "";
                                                                                        $cljudge5 = "";
                                                                                        if (count($results2) > 0) {
                                                                                            $djcnt = 0;
                                                                                            foreach ($results2 as $row2) {
                                                                                                if ($cljudge1 == $row2["jcode"]) {
                                                                                                    echo '<option value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '" selected>' . str_replace("\\", "", $row2["jname"]) . "</option>";
                                                                                                } else {
                                                                                                    echo '<option value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '">' . str_replace("\\", "", $row2["jname"]) . "</option>";
                                                                                                }
                                                                                                if ($cljudge1 == $row2["jcode"]) {
                                                                                                    $djcnt++;
                                                                                                    $tjud1 = '<input type="checkbox"  id="hd_chk_jd1" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . "</b></font>";
                                                                                                }
                                                                                                if ($cljudge2 == $row2["jcode"]) {
                                                                                                    $djcnt++;
                                                                                                    $tjud2 = '<input type="checkbox"  id="hd_chk_jd2" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . "</b></font>";
                                                                                                }
                                                                                                if ($cljudge3 == $row2["jcode"]) {
                                                                                                    $djcnt++;
                                                                                                    $tjud3 = '<input type="checkbox"  id="hd_chk_jd3" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . "</b></font>";
                                                                                                }
                                                                                                if ($cljudge4 == $row2["jcode"]) {
                                                                                                    $djcnt++;
                                                                                                    $tjud4 = '<input type="checkbox"  id="hd_chk_jd4" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . "</b></font>";
                                                                                                }
                                                                                                if ($cljudge5 == $row2["jcode"]) {
                                                                                                    $djcnt++;
                                                                                                    $tjud5 = '<input type="checkbox"  id="hd_chk_jd5" onclick="getDone_upd_cat(this.id);" checked="true" value="' . $row2["jcode"] . "||" . str_replace("\\", "", $row2["jname"]) . '"/>&nbsp;<font color=yellow><b>' . str_replace("\\", "", $row2["jname"]) . "</b></font>";
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                        ?>

                                                                                        <style>
                                                                                        #select2-container--focus{
                                                                                            width:220px !important;
                                                                                        }</style>

                                                                                       

                                                                                    </select><br><br>
                                                                                    <input type="hidden" name="djcnt" id="djcnt" value="<?php echo $djcnt; ?>"/>
                                                                                    <input type="button" name="addjudge" id="addjudge" value="Add" onclick="getSlide();"/>
                                                                                </td>
                                                                                <td rowspan="4" id="judgelist">
                                                                                    <table id="tb_new" width="100%" style="text-align:left;">
                                                                                        <?php
                                                                                        if ($tjud1 != "") {
                                                                                            echo "<tr id='hd_chk_jd_row1'><td>" . $tjud1 . "</td></tr>";
                                                                                        }
                                                                                        if ($tjud2 != "") {
                                                                                            echo "<tr id='hd_chk_jd_row2'><td>" . $tjud2 . "</td></tr>";
                                                                                        }
                                                                                        if ($tjud3 != "") {
                                                                                            echo "<tr id='hd_chk_jd_row3'><td>" . $tjud3 . "</td></tr>";
                                                                                        }
                                                                                        if ($tjud4 != "") {
                                                                                            echo "<tr id='hd_chk_jd_row4'><td>" . $tjud4 . "</td></tr>";
                                                                                        }
                                                                                        if ($tjud5 != "") {
                                                                                            echo "<tr id='hd_chk_jd_row5'><td>" . $tjud5 . "</td></tr>";
                                                                                        }
                                                                                        ?>
                                                                                    </table>
                                                                                </td>
                                                                                <td rowspan="4" id="auto_chck">
                                                                                    <table id="jud_coram" width="100%" style="text-align:left;">
                                                                                    </table>
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td align="center"><b><font  size="+1">Disposal/Hearing Date : </font></b>&nbsp;<input class="dtp" type="text" name="hdate" id="hdate" value="<?php echo $cldate; ?>" size="12" readonly="readonly"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center"><b><font size="+1"><span id="rjdate_fnt">R.J. Date : </span></font></b>&nbsp;<input class="dtp" type="text" name="rjdate" id="rjdate" value="" size="12" readonly="readonly" style="background-color:#CCC;"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center">
                                                                                </td>
                                                                            </tr>
                                                                        </table>

                                                                        <table width="100%" border="1" style="border-collapse: collapse">
                                                                            <?php
                                                                            $t11 = $casedesc['t11'];
                                                                            $ttl_disp = count($t11);
                                                                            if ($ttl_disp > 0) {
                                                                                $snoo = 1;
                                                                                $chkhead = "";
                                                                                $sno_1 = "";
                                                                                $sno_2 = "";
                                                                                $head_1 = "";
                                                                                $head_2 = "";
                                                                                $t_subhead = "";
                                                                                foreach ($t11 as $row11) {   
                                                                                    if ($snoo % 2 == 0 or $snoo == $ttl_disp) {
                                                                                        $sno_2 = $row11["sno"];
                                                                                        $head_2 = $row11["head"];
                                                                                        $bgc = "#ECF1F7";
                                                                                        if ( ($t_subhead == 801 || $t_subhead == 820) && $listorder != 5 && ($sno_1 != 78 && $sno_1 != 73 && $sno_1 != 37)) {
                                                                                            $t_subhead1 = "disabled='disabled'";
                                                                                        } else {
                                                                                            $t_subhead1 = "";
                                                                                        }
                                                                                        ?>
                                                                                        <tr bgcolor="<?php echo $bgc; ?>">
                                                                                        <td align="left">
                                                                                            <input class="cls_chkd" type="checkbox" name="chkd<?php echo $sno_1; ?>" id="chkd<?php echo $sno_1; ?>" value="<?php echo $sno_1 . "|" . $head_1; ?>" onclick="chk_checkbox();" <?php echo $t_subhead1; ?>/>
                                                                                            <label class="lblclass" for="chkd<?php echo $sno_1; ?>"><?php echo $head_1; ?></label>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" name="hdremd<?php echo $sno_1; ?>" id="hdremd<?php echo $sno_1; ?>" value=""/>
                                                                                            <input type="hidden" name="hdd<?php echo $sno_1; ?>" id="hdd<?php echo $sno_1; ?>"/>
                                                                                        </td>
                                                                                        <?php
                                                                                        if ($snoo == $ttl_disp and $snoo % 2 == 1) { ?>
                                                                                            <td align="left">&nbsp;</td>
                                                                                            <td>&nbsp;</td>
                                                                                        <?php } else {
                                                                                            if ( ($t_subhead == 801 or $t_subhead == 820) and $listorder != 5 and ($sno_2 != 78 and $sno_2 != 73 and $sno_2 != 37) ) {
                                                                                                $t_subhead1 = "disabled='disabled'";
                                                                                            } else {
                                                                                                $t_subhead1 = "";
                                                                                            } ?>
                                                                                            <td align="left">
                                                                                                <input class="cls_chkd" type="checkbox" name="chkd<?php echo $sno_2; ?>" id="chkd<?php echo $sno_2; ?>" value="<?php echo $sno_2 ."|" . $head_2; ?>" onclick="chk_checkbox();" <?php echo $t_subhead1; ?>/>
                                                                                                <label class="lblclass" for="chkd<?php echo $sno_2; ?>"><?php echo $head_2; ?></label>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" name="hdremd<?php echo $sno_2; ?>" id="hdremd<?php echo $sno_2; ?>" value=""/>
                                                                                                <input type="hidden" name="hdd<?php echo $sno_2; ?>" id="hdd<?php echo $sno_2; ?>"/>
                                                                                            </td>
                                                                                        <?php }
                                                                                        if ($snoo <= 2) { ?>
                                                                                            <td rowspan="<?php echo ($ttl_disp + 1) / 2; ?>">
                                                                                                <div id="concasediv" style="overflow: auto;display:fixed;max-height:550px;">
                                                                                                    <table>
                                                                                                        <?php
                                                                                                        $t_conn_cases = $casedesc['html_conn_cases']['t_conn_cases'];
                                                                                                        if ($t_conn_cases != "") {
                                                                                                            $t_conn_cases = '<tr><td bgcolor=#5499c7><input type="checkbox" name="connall" id="connall" value="" onclick="chk_all_cn();"/><label class="lblclass" for="connall">CHECK ALL</label></td></tr>' .
                                                                                                                $t_conn_cases;
                                                                                                            echo $t_conn_cases;
                                                                                                        } ?>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </td>
                                                                                        <?php }
                                                                                        ?>
                                                                                    </tr>
                                                                                    <?php
                                                                                    } else {
                                                                                        $sno_1 = $row11["sno"];
                                                                                        $head_1 = $row11["head"];
                                                                                        $sno_2 = "";
                                                                                        $head_2 = "";
                                                                                        $bgc = "#F8F9FC";
                                                                                    } 
                                                                                    $snoo++;
                                                                                } // while end
                                                                            }
                                                                            ?>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                                <input type="hidden" name="sh_hidden" id="sh_hidden" value=""/>
                                                                <input type="hidden" name="diaryno" id="diaryno" value="<?php echo $casedesc['get_real_diaryno']; ?>"/>
                                                            <?php } else {
                                                                echo "<br><br><b>No case found for no. provided.</b><br><br>";
                                                            }

                                                        }else{ ?>
                                                            <p align=center><font color=red>Case Not Found</font></p>
                                                        <?php }
                                                    }else{ ?>
                                                        <p align=center><font color=red>Case Not Found</font></p>
                                                    <?php }
                                                    ?>
                                                    
                                                </div>
                                                    
                                            </div>

                                            

                                            <?php form_close();?>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <div id="overlay" style="display: none; height: 1891px;">&nbsp;</div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


<script>
       
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }



    function call_div(cn, e, cnt,subh){
        if (cnt == 1){
            var div1 = "chkp";
            var div2 = "hdremp";
            $('#tmp_casenop').val(cn);
            $('#pend_head').html('<font color=red>' + $('#cs' + cn).html() + '</font>');
        }else{
            var div1 = "chkd";
            var div2 = "hdremd";
            $('#tmp_casenod').val(cn);
            $('#tmp_casenosub').val(subh);        
            $('#disp_head').html('<font color=red>Diary No. : ' + $('#diaryno').val() + '</font>');
        }
        var csval = "";
        if(document.getElementById("caseval" + cn)){
            csval = document.getElementById("caseval" + cn).value;
        }
   
        var csvalspl = csval.split("^^");
        var t_val;
        var chk_val;
        $("input[type='checkbox'][name^='" + div1 + "']").each(function() {
            chk_val = $(this).val().split("|");
            int_chk = 0;
        });
        call_f1(cnt);
    }

    function call_f1(cnt){
        var divname = "";
        if (cnt == 1){
            divname = "newb";
            $('#' + divname).width($(window).width() - 150);
            $('#' + divname).height($(window).height() - 120);
            $('#newb123').height($('#newb').height() - $('#newb1').height() - 50);
        }
        if (cnt == 2){
            divname = "newc";
            // $('#' + divname).width($(window).width() - 350);
            // $('#' + divname).height($(window).height() - 10);
            // $('#newc123').height($('#newc').height() - $('#newc1').height() - 150);
            // $('#concasediv').height($('#newc').height() - $('#newc1').height() - 150);
        }
        if (cnt == 3){
            divname = "newp";
            $('#' + divname).width($(window).width() - 150);
            $('#' + divname).height($(window).height() - 120);
            $('#newp123').height($('#newp').height() - $('#newp1').height() - 50);
        }
        if (cnt == 4){
            divname = "newadv";
            $('#' + divname).width('600px');
            $('#' + divname).height($(window).height() - 150);
            $('#newadv123').height($('#newadv').height() - $('#newadv1').height() - 50);
        }
        // var newX = ($('#' + divname).width() / 2);
        // var newY = ($('#' + divname).height() / 2);
        // document.getElementById(divname).style.marginLeft = "-" + newX + "px";
        // document.getElementById(divname).style.marginTop = "-" + newY + "px";
        document.getElementById(divname).style.display = 'block';
        document.getElementById(divname).style.zIndex = 10;
        $('#overlay').height($(window).height());
        document.getElementById('overlay').style.display = 'block';

        var curr_date=document.getElementById("cldate").value;
        var d=new Date(curr_date);
        $("#hdate").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2,changeMonth:true, changeYear:true, maxDate: d});
        $("#cldate").datepicker({dateFormat: "dd-mm-yy", numberOfMonths: 2,changeMonth:true, changeYear:true, maxDate: d});
        $("#rjdate").datepicker({dateFormat: "dd-mm-yy",changeMonth:true, changeYear:true, maxDate: d});

        $("#hdate").keypress(function(e) {
            e.preventDefault();
        });
        $("#cldate").keypress(function(e) {
            e.preventDefault();
        });
        $("#rjdate").keypress(function(e) {
            e.preventDefault();
        });
        $("#rjdate").keyup(function(e) {
            if (e.keyCode == 8 || e.keyCode == 46) {
                $.datepicker._clearDate(this);
            }
        });
        $("#djudge").select2( {
            placeholder: "Select Judges/ Registrar",
            allowClear: true
        });

    }

    

    
   
    function get_coram(diary_no){
        updateCSRFToken()
        var cl_dt=$('#cldate').val();
        //alert (diary_no);
        //alert(cl_dt);
        $('#td_coram').hide();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('Judicial/DirectDispose/get_coram'); ?>",
            cache: false,
            async: true,
            data: { CSRF_TOKEN: CSRF_TOKEN_VALUE, cl_dt: cl_dt, diary_no:diary_no },
            type: 'POST',
            success: function(data, status) {

                if(data!=''){
                    $('#jud_coram').html(data);
                    updateCSRFToken()
                }else{
                    alert("No Coram Found");
                    location.reload();
                }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
    

    async function save_rec(cnt){
        await updateCSRFTokenSync();
        var cn = "";
        var stat = "";
        var cr_head = "";
        var rjdt = "00-00-0000";
            var subh="";
        if (cnt == 1){
            var div1 = "chkp";
            var div2 = "hdremp";
            cn = $('#tmp_casenop').val();
            stat = "P";
            cr_head = '<b><font color="blue">';
        }else{
            var div1 = "chkd";
            var div2 = "hdremd";
            cn = $('#tmp_casenod').val();
            subh=$('#tmp_casenosub').val();        
            stat = "D";
            cr_head = '<b><font color="red">';
        }
        var chk_val;
        var cval = "";
        var crem = "";
        var str_new = "";
        var str_caseval = "";
        var isfalse = 0;
        var jcodes = "";
        var jcnt = 0;
        var chk_var = false;
        var chk_var1 = false;
        $("input[type='checkbox'][id^='hd_chk_jd']").each(function() {
            if (document.getElementById($(this).attr('id')).checked) {
                jcodes += $(this).val().split("||")[0] + ",";
                jcnt++;
            }
        });
        if (jcodes == ""){
            alert("Select Judge");
            return false;
        }

        $("input[type='checkbox'][name^='" + div1 + "']").each(function() {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked){
                chk_var = true;
                chk_val = $(this).val().split("|");
                cval = $("#" + div1 + chk_val[0]).val().split("|");
                if (cnt == 1){
                    if (textformate(cval[0]) == false){
                        isfalse = 1;
                    }
                    if (cval[0] == 24 || cval[0] == 21 || cval[0] == 70 || cval[0] == 59){
                        if ($("#" + div2 + cval[0]).val() == ''){
                            alert('Please Enter Date');
                            setFocusToTextBox(cval[0]);
                            isfalse = 1;
                        }
                    }
                }
                else{
                    if (cval[0] == 37 || cval[0] == 78 || cval[0] == 73){
                        chk_var1 = true;
                        rjdt = document.getElementById("rjdate").value;
                    }
                }
                crem = $("#" + div2 + chk_val[0]).val();
                str_new += cval[0] + "|" + crem + "!";
                str_caseval += cval[0] + "|" + crem + "^^";
                cr_head += cval[1];
                if (crem != ""){
                    cr_head += ' [' + crem + ']';
                }
                cr_head += '<br>';
            }
        });

        cr_head += '</font></b>';
        if (document.getElementById("cldate").value==""){
            alert("Select CauseList Date!");
            return false;
        }
        if (document.getElementById("hdate").value==""){
            alert("Select Hearing Date!");
            return false;
        }    
        if (!(chk_var)){
            alert("Select atleast one disposal type from the list.");
            return false;
        }
        if ((rjdt == "" || rjdt == "00-00-0000") && chk_var1==true){
            alert("Select RJ Date");
            return false;
        }
        if (isfalse == 0){

            

            var str1 = "";
            var dt = document.getElementById("cldate").value;
            var hdt = document.getElementById("hdate").value;  
            var subh = document.getElementById("tmp_casenosub").value;
            var concstr='';
            $("input[type='checkbox'][name^='conncchk']").each(function () {
                if($(this).is(':checked')){
                concstr+= $(this).val()+',';  
                }
            });
        
            var dt1 = dt.split("-");
            var dt_new = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
            var hdt1 = hdt.split("-");
            var hdt_new = hdt1[2] + "-" + hdt1[1] + "-" + hdt1[0];
            var rjdt1 = rjdt.split("-");
            var rjdt_new = rjdt1[2] + "-" + rjdt1[1] + "-" + rjdt1[0];
            if(rjdt_new == '0000-00-00'){
                rjdt_new = 'null';
            }
            str1 = jcodes.replace(/,\s*$/, "");
            str_new = cn + "#" + stat + "#" + str_new + "#" + subh;

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


            let dataObj = {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                'str' : str_new,
                'str1' : str1,
                'dt' : dt_new,
                'hdt' : hdt_new,
                'rjdt' : rjdt_new,
                'concstr' : concstr,
            }

            $.ajax({
                type: 'POST',
                url:"<?php echo base_url('Judicial/DirectDispose/insert_rec_an_disp'); ?> ",
                data: dataObj
            })
            .done(function(msg){
                if (msg == null || msg == 'null'){
                    // fsubmit();
                    location.reload()
                }else if(msg != "" || msg != null || msg != 'null'){
                    alert(msg);
                }
                close_w(cnt);
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room"); 
            });



            // var url = "insert_rec_an_disp.php";
            // var http = new getXMLHttpRequestObject();
           
            // var parameters =   "str=" + str_new;
            //     parameters += "&str1=" + str1;
            //     parameters += "&dt=" + dt_new;
            //     parameters += "&hdt=" + hdt_new;
            //     parameters += "&rjdt=" + rjdt_new;
            //     parameters += "&concstr=" + concstr;            
            // http.open("POST", url, true);
            // http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            // http.setRequestHeader("Content-length", parameters.length);
            // http.setRequestHeader("Connection", "close");
            // http.onreadystatechange = function() {//Handler function for call back on state change.
            //     if (http.readyState == 4) {
            //         var data = http.responseText;
            //         if (data != ""){
            //             alert(data);
            //         }else{
            //             fsubmit();
            //         }
            //     }
            // }
            // http.send(parameters);
            // close_w(cnt);
        }
    }

    function chk_checkbox() {
        var isfound = false;
        $('input:checkbox.cls_chkd').each(function() {
            if (this.checked) {
                var chkVal = (this.checked ? $(this).val() : "");
                var chkVal1 = parseInt(chkVal.split('||')[0]);
                if (chkVal1 == 37 || chkVal1 == 78 || chkVal1 == 73)
                    isfound = true;
            }
        });
        if (isfound) {
            $("#rjdate").attr('readonly', false);
            $("#rjdate").css('background-color', '');
        } else {
            $("#rjdate").attr('readonly', true);
            $("#rjdate").css('background-color', '#CCC');
        }
    }
    
    function close_w(cnt){
        var divname = "";
        if (cnt == 1){
            divname = "newb";
        }
        if (cnt == 2){
            divname = "newc";
        }
        if (cnt == 3){
            divname = "newp";
        }
        if (cnt == 4){
            divname = "newadv";
        }
        document.getElementById(divname).style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
        if (cnt == 3){
            // fsubmit();
            location.reload()
        }
       
    }

    var cnt_data1 = 1;
    var ck_subhead = 0;
    var ck_subhead_s = 0;
   
    function getSlide(){
        $('#btn_coram').hide();
        var cnt_data = parseInt(document.getElementById('djcnt').value);
        var cnt_data1 = cnt_data + 1;
        var mf_select = document.getElementById('djudge').value;

        var mf_select1 = mf_select.split("||")[1];
        for (var i = 1; i <= cnt_data; i++)
        {
            if (document.getElementById('hd_chk_jd' + i))
            {
                if (document.getElementById('hd_chk_jd' + i).value == mf_select)
                {
                    alert("Already Selected");
                    return false;
                }
            }
        }
        var hd_chk_add = document.createElement('input');
        hd_chk_add.setAttribute('type', 'checkbox');
        hd_chk_add.setAttribute('id', 'hd_chk_jd' + cnt_data1);
        hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
        hd_chk_add.setAttribute('value', mf_select);
        var row0 = document.createElement("tr");
        row0.setAttribute('id', 'hd_chk_jd_row' + cnt_data1);
        var column0 = document.createElement("td");
        column0.appendChild(hd_chk_add);
        column0.innerHTML = column0.innerHTML + '&nbsp;<font color=red><b>' + mf_select1 + '</b></font>';
        row0.appendChild(column0);
        var tb_res = document.getElementById('tb_new');
        tb_res.appendChild(row0);
        document.getElementById('hd_chk_jd' + cnt_data1).checked = true;
        document.getElementById('djcnt').value = cnt_data1;
    }

    function getDone_upd_cat(str){
        var str1 = str.split('hd_chk_add');
        var tb = 0;
        var hd_co_tot = document.getElementById('hd_co_tot').value;
        if (document.getElementById('hd_sp_b' + str1[1]).value == '850' || document.getElementById('hd_sp_b' + str1[1]).value == '851'){
            if (document.getElementById('hd_sp_b' + str1[1]).value == '850'){
                ck_subhead = 0;
            }
            else if (document.getElementById('hd_sp_b' + str1[1]).value == '851'){
                ck_subhead_s = 0;
            }
            if ($("#bench").val() == "S"){
                $("#sbj").val("250");
            }

            if ($("#bench").val() == "D"){
                $("#dbj1").val("200");
                $("#dbj2").val("999");
            }
        }
        for (var itt = 1; itt <= hd_co_tot; itt++){
            if (document.getElementById('hd_sp_b' + itt)){
                tb++;
            }
        }
        $("#tr_uo" + str1[1]).remove();
    }

    function feed_rmrk(){
        var ccstr = "";
        var obrdrem = document.getElementById("brdremh").value;
        document.getElementById("brdrem").value = '';
        ccstr = obrdrem;
        $("input[type='checkbox'][name^='iachbx']").each(function() {
            var isChecked = document.getElementById($(this).attr('id')).checked;
            if (isChecked){
                var tval = $(this).val().split("|#|");
                if (ccstr != '')
                    ccstr += " \nFOR " + tval[1] + " ON IA " + tval[0];
                else
                    ccstr += " FOR " + tval[1] + "  ON IA " + tval[0];
            }
        });
        document.getElementById("brdrem").value = ccstr;
    }

    function setFocusToTextBox(cb) {
        var textbox = document.getElementById('hdremp' + cb);
        $("#hdremp" + cb).focus();
        textbox.scrollIntoView();
    }

    function textformate(cb) {
        var y = document.getElementById('hdremp' + cb).value;
        x = y.split(",");
        if (cb == 72) {
            for (var i = 0; i < x.length; i++){
                var iChars = "~`!#$%^&*+=-[]\\\';/{}|\":<>?";
                for (var j = 0; j < x[i].length; j++){
                    if (iChars.indexOf(x[i].charAt(j)) !== -1){
                        alert("Special characters ~`!#$%^&*+=-[]\\\';/{}|\":<>? \nThese are not allowed\n");
                        return false;
                    }
                }
                casenoyr = (x[i].replace(/[^0-9]/g, "").length);
                casetyp = (x[i].replace(/[^a-zA-Z]/g, "").length);
                ctype = x[i].replace(/[^a-zA-Z]/g, "");
                ctyp = ctype.toUpperCase();
                var cpa = 0;
                switch (ctyp){
                    case 'AA':
                        break;
                    case 'AC':
                        break;
                    case 'AR':
                        break;
                    case 'ARBA':
                        break;
                    case 'ARBC':
                        break;
                    case 'CA':
                        break;
                    case 'CEA':
                        break;
                    case 'CER':
                        break;
                    case 'CESR':
                        break;
                    case 'COMA':
                        break;
                    case 'COMP':
                        break;
                    case 'COMPA':
                        break;
                    case 'CONA':
                        break;
                    case 'CONC':
                        break;
                    case 'CONCR':
                        break;
                    case 'CONT':
                        break;
                    case 'CONTR':
                        break;
                    case 'CR':
                        break;
                    case 'CRA':
                        break;
                    case 'CRR':
                        break;
                    case 'CRRE':
                        break;
                    case 'CRRF':
                        break;
                    case 'CRRFC':
                        break;
                    case 'CS':
                        break;
                    case 'EP':
                        break;
                    case 'FA':
                        break;
                    case 'FEMA':
                        break;
                    case 'GTR':
                        break;
                    case 'ITA':
                        break;
                    case 'ITR':
                        break;
                    case 'LPA':
                        break;
                    case 'MA':
                        break;
                    case 'MACE':
                        break;
                    case 'MACOM':
                        break;
                    case 'MACTR':
                        break;
                    case 'MAIT':
                        break;
                    case 'MAVAT':
                        break;
                    case 'MCC':
                        break;
                    case 'MCOMA':
                        break;
                    case 'MCP':
                        break;
                    case 'MCRC':
                        break;
                    case 'MCRP':
                        break;
                    case 'MP':
                        break;
                    case 'MWP':
                        break;
                    case 'OTA':
                        break;
                    case 'RP':
                        break;
                    case 'SA':
                        break;
                    case 'SLP':
                        break;
                    case 'STR':
                        break;
                    case 'TR':
                        break;
                    case 'VATA':
                        break;
                    case 'WA':
                        break;
                    case 'WP':
                        break;
                    case 'WPS':
                        break;
                    case 'WTA':
                        break;
                    case 'WTR':
                        break;
                    default:
                        {
                            alert("Please Enter proper Case ");
                            cpa++;
                            return false;
                        }
                }
                casetyp = x[i].slice(-casetyp);
                cnyr = x[i].slice(-casenoyr);
                var x1 = x[i].slice(-cnyr);
                if (casenoyr <= 4) {
                    alert("Please Type Correct Case No And Year");
                    return false;
                }
                if (casenoyr == 5)
                    cnyr = '0000' + cnyr;
                if (casenoyr == 6)
                    cnyr = '000' + cnyr;
                if (casenoyr == 7)
                    cnyr = '00' + cnyr;
                if (casenoyr == 8)
                    cnyr = '0' + cnyr;
                var yr = cnyr.slice(-4);
                var srvr = document.getElementById('srvr').value;
                if (yr <= 1959){
                    alert("Please Enter Correct Year Greater then 1959");
                    return false;
                }
                if (yr > srvr) {
                    alert("Please Enter Correct Year Less  then " + srvr);
                    return false;
                }
            }
        }
        if (cb == 68 || cb == 23 || cb == 53 || cb == 54 || cb == 25 || cb == 122 || cb == 123) {
            if (isNaN(y)){
                alert('Please Enter Numeric Value');
                setFocusToTextBox(cb);
                return false;
            }
        }
        if (cb == 53 || cb == 25){
            if (y >= 31){
                alert('Please Enter Numeric Value Between 1 TO 31 Which Is No Of Days In A Month');
                setFocusToTextBox(cb);
                return false;
            }
        }
        if (cb == 23 || cb == 122){
            if (y >= 54){
                alert('Please Enter Numeric Value Between 1 TO 52 Which Is Week No Of The Year');
                setFocusToTextBox(cb);
                return false;
            }
        }
        if (cb == 68 || cb == 123 || cb == 54){
            if (y >= 12 && y !== 0){
                alert('Please Enter Numeric Value Between 1 TO 12 Which Is Month Of The Year');
                setFocusToTextBox(cb);
                return false;
            }
        }
        return true;
    }

    function chk_all_cn(){
        $("input[type='checkbox'][name^='conncchk']").each(function () {
            if(document.getElementById('connall').checked){
                $(this).prop('checked',true);
            }else{
                $(this).prop('checked',false);
            }
        });
    }

    // function fsubmit(){
    //     updateCSRFToken()
    //     var diaryno, diaryyear, cstype, csno, csyr;
    //     var regNum = new RegExp('^[0-9]+$');
         
    //     if($("#radioct").is(':checked')){
    //         cstype = $("#selct").val();
    //         csno = $("#case_no").val();
    //         csyr = $("#case_yr").val();
            
    //         if(!regNum.test(cstype)){
    //             alert("Please Select Casetype");
    //             $("#selct").focus();
    //             return false;
    //         }
    //         if(!regNum.test(csno)){
    //             alert("Please Fill Case No in Numeric");
    //             $("#case_no").focus();
    //             return false;
    //         }
    //         if(!regNum.test(csyr)){
    //             alert("Please Fill Case Year in Numeric");
    //             $("#case_yr").focus();
    //             return false;
    //         }
    //         if(csno == 0){
    //             alert("Case No Can't be Zero");
    //             $("#case_no").focus();
    //             return false;
    //         }
    //         if(csyr == 0){
    //             alert("Case Year Can't be Zero");
    //             $("#case_yr").focus();
    //             return false;
    //         }
           
    //     }
    //     else if($("#radiodn").is(':checked')){
    //         diaryno = $("#dno").val();
    //         diaryyear = $("#dyr").val();
    //         if(!regNum.test(diaryno)){
    //             alert("Please Enter Diary No in Numeric");
    //             $("#dno").focus();
    //             return false;
    //         }
    //         if(!regNum.test(diaryyear)){
    //             alert("Please Enter Diary Year in Numeric");
    //             $("#dyr").focus();
    //             return false;
    //         }
    //         if(diaryno == 0){
    //             alert("Diary No Can't be Zero");
    //             $("#dno").focus();
    //             return false;
    //         }
    //         if(diaryyear == 0){
    //             alert("Diary Year Can't be Zero");
    //             $("#dyr").focus();
    //             return false;
    //         }
    //     }
    //     else{
    //         alert('Please Select Any Option');
    //         return false;
    //     }

    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    //     $.ajax({
    //         type: 'POST',
    //         url:"<?php echo base_url('Judicial/DirectDispose/set_dispose_process'); ?>",
    //         beforeSend: function (xhr) {
    //             // $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
    //         },
    //         data:{ CSRF_TOKEN: CSRF_TOKEN_VALUE, d_no:diaryno, d_yr:diaryyear, ct:cstype, cn:csno, cy:csyr }
    //     })
    //     .done(function(msg){
    //         // $("#dv_res1").html(msg);
    //         location 
    //     })
    //     .fail(function(){
    //         alert("ERROR, Please Contact Server Room"); 
    //     });

    // }

</script>


 <?=view('sci_main_footer') ?>