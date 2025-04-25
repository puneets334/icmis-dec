<?php
if ($chk_status == 1) {
    $dairy_no = $noticesModel->get_diary_case_type($ct, $cn, $cy);
} else {
    $dairy_no = $d_no . $d_yr;
}
$navigateDiary = $noticesModel->navigate_diary($dairy_no);
$diaryStatus = $noticesModel->getDiaryStatus($dairy_no);
$dacode = $diaryStatus['dacode'] ?? null;
$status = $diaryStatus['c_status'] ?? null;


// Get the user ID from session
$user_id = session()->get('login')['usercode'];

// Check if the user ID is not the same as DACode and is not 663 or 1
if ($user_id != $dacode && $user_id != 663 && $user_id != 1) {

    // Get the user's section from the 'users' table
    
    $row = is_data_from_table('master.users', 'usercode = $user_id', 'section', '');
    // Fetch the user's section
    if ($row) {
        $usersection = $row['section'];

        // Section 76 and other checks
        if (
            !($usersection == 76 && $status == 'D') && $usersection != 77
            && !($usersection == 46 && $status == 'D') && !($usersection == 47 && $status == 'D')
        ) {

            // Display the message and stop further execution
            echo '<p align="center"><font color="red">Only DA can generate Notice</font></p>';
            exit();
        }
    }
}

//   $noticesModel->get_next_working_date_new($dt, $head_no, $mf);
$chk_dts = '0';

$date = date('Y-m-d');
//$date = '2025-04-07';
$ck_pf_nt = '';
$ck_pfnt = '';
$individual_multiple = '';
$row = $noticesModel->getResAdvNm($dairy_no);
if ($row) {
    // Hidden input fields
    echo '<input type="hidden" name="hd_n_status" id="hd_n_status" value="' . esc($row['c_status']) . '"/>';
    echo '<input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="' . esc($row['casetype_id']) . '"/>';

    $res_cont = $noticesModel->geTwTalDel($dairy_no, $date);
     
    if ($res_cont > 0) {
        $res_sq_fi_sub = $noticesModel->getTalDelData($dairy_no, $date);
        $individual_multiple = $res_sq_fi_sub['individual_multiple'] ?? '';     
    }    
?>
        <div class="cl_center">
            <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="1" <?php if ($individual_multiple == '1') { ?> checked="checked" <?php } ?> /><b>Individual</b>
            <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="2" <?php if ($individual_multiple == '2') { ?> checked="checked" <?php } ?> /><b>Multiple</b>
        </div>
        <?php
  

  /*  $res_cont = $noticesModel->get_res_cont($dairy_no, $date);
    if ($res_cont > 0) {

        $res_sq_fi_sub = $noticesModel->res_sq_fi_sub($dairy_no, $date);

        if (!empty($res_sq_fi_sub)) {
            $individual_multiple = $res_sq_fi_sub['individual_multiple'] ?? '';

        ?>
            <div class="cl_center">
                <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="1" <?php if ($individual_multiple == '1') { ?> checked="checked" <?php } ?> /><b>Individual</b>
                <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="2" <?php if ($individual_multiple == '2') { ?> checked="checked" <?php } ?> /><b>Multiple</b>
            </div>
        <?php
        }
    } */
     
    if ($row['c_status'] == 'P') {

        $ret_res = $noticesModel->get_max_dt($dairy_no, '');
         
        // Explode and date handling
        $ex = explode("Ord dt:", $row['lastorder'] ?? '');
       // $dmy = (!empty($ex[1])) ?  explode('-', trim($ex[1])) : '';

        //$Y = $dmy[2];
        //$m = $dmy[1];
        //$d = $dmy[0];
        //$or_dt = date($Y . '-' . $m . '-' . $d);
        $ex1 = (!empty($ex[1])) ? str_replace('/', '-', $ex[1]) : '';
        $or_dt = (!empty($ex[1])) ? date('Y-m-d',strtotime($ex1)) : '';

        $get_sel_con = 0;
        $sql_bnnn = $noticesModel->getCaseRemarksMultiple($dairy_no, $ret_res);
        // Extract results
        $res_sql_bnnn = $sql_bnnn['ct_fn'] ?? 0;
        $res_sql_r_head = $sql_bnnn['r_head'] ?? '';

        $ex_res_sql_r_head = explode(',', $res_sql_r_head);
        $fx_dt = 0;
        $chk_remark = '';
        $get_sel_con = 0;

        // List of numbers to match against
        $matching_values = ['24', '21', '59', '91', '23', '8', '12', '20', '53', '54', '68', '131', '113'];

        foreach ($ex_res_sql_r_head as $value) {
            if (in_array($value, $matching_values)) {
                $fx_dt = 1;
                $chk_remark = $value;
                $get_sel_con = 1;
                break;
            }
        }

        if ($fx_dt == 1) {
            $conn_main_cs = 0;
            $sqy_pf = '';

            if (in_array($chk_remark, ['24', '21', '59', '91', '131'])) {

                $sqy_pf = $noticesModel->case_remarks_multiple($dairy_no, $ret_res);
            } else if (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68', '113'])) {

                $sqy_pf = $noticesModel->get_heardt($dairy_no);
            }

            // Check if we have a result from the query
            if ($sqy_pf) {
                $check_rec_pre = 1;

                if ($check_rec_pre == 1) {
                    // Get the date and format it to 'd-m-Y'
                    $res_sql_pf = date('d-m-Y', strtotime($sqy_pf->head_content ?? $sqy_pf->tentative_cl_dt));
                    $ck_pf_nt = 0;
                    $ck_pfnt = 1;
                }
            }
        } elseif ($fx_dt == 0 && $res_sql_bnnn > 0) {

            $sql_pf = $noticesModel->get_docdetails($dairy_no, $or_dt);

            if ($sql_pf) {
                $res_sq_con_not = $noticesModel->get_sq_con_not($dairy_no);
                $r_bx = $noticesModel->get_r_bx($dairy_no, $res_sq_con_not);

                $check_rec_pre = 0;

                if (!empty($r_bx)) {
                    $conn_main_cs = 0;
                    foreach ($r_bx as $row2) {
                        if ($row2['diary_no'] != $row2['conn_key']) {
                            if ($dairy_no != $row2['conn_key']) {
                                $conn_main_cs = $row2['conn_key'];
                            } else {
                                $conn_main_cs = 0;
                            }

                            // Determine the type of query based on $chk_remark
                            if (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68'])) {
                                $sqy_pf = $noticesModel->get_sqy_pf($conn_main_cs);
                            } else {
                                $sqy_pf = $noticesModel->get_sqy_pf2($conn_main_cs, $ret_res);
                            }

                            if (!empty($sqy_pf)) {
                                $check_rec_pre = 1;
                                break;
                            }
                        }
                    }
                } else {
                    $conn_main_cs = 0;

                    if (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68'])) {
                        $sqy_pf = $noticesModel->get_tentative_cl_dt($dairy_no);
                    } else {
                        $sqy_pf = $noticesModel->get_tentative_cl_dt2($dairy_no, $ret_res);
                    }

                    if (!empty($sqy_pf)) {
                        $check_rec_pre = 1;
                    }
                }

                // Handling the results and setting the necessary flags and values
                if ($check_rec_pre == 1) {
                    $res_sql_pf = date('d-m-Y', strtotime($sqy_pf->head_content ?? $sqy_pf->tentative_cl_dt));
                    $ck_pf_nt = 0;
                    $ck_pfnt = 1;
                } else {
                    $res_sql_pf = $sql_pf->dt;
                    $ff_dt = date('d-m-Y', strtotime($res_sql_pf . ' + 45 days'));
                    $ck_pf_nt = 1;
                    $ck_pfnt = 0;
                    $get_sel_con = 2;
                }

                // Hidden input field for `hd_hd_mn_con`
                echo '<input type="hidden" name="hd_hd_mn_con" id="hd_hd_mn_con" value="' . $conn_main_cs . '"/>';
            } else {
                $ff_dt = date('d-m-Y', strtotime($or_dt . ' + 45 days'));
                $ck_pf_nt = 1;
                $ck_pfnt = 0;
            }
        } else {
            $get_sel_con = 3;

            if ($or_dt == '--') {
                $sqy_pf_no_r = $noticesModel->get_sqy_pf_no_r($dairy_no);

                if ($sqy_pf_no_r) {
                    $ff_dt = $sqy_pf_no_r['tentative_cl_dt'];
                } else {
                    $ff_dt = null;
                }
            } else {
                $ff_dt = date('d-m-Y', strtotime($or_dt . ' + 45 days'));
            }

            // Set flags
            $ck_pf_nt = 1;
            $ck_pfnt = 1;

            if ($ff_dt == '0000-00-00') {
                $chk_dts = 1;
            }
        }
        ?>
        <!-- Hidden Inputs -->
        <input type="hidden" name="hd_ck_pf_nt" id="hd_ck_pf_nt" value="<?= esc($ck_pf_nt) ?>" />
        <input type="hidden" name="hd_order_date" id="hd_order_date" value="<?= esc($or_dt) ?>" />
        <?php
        // Load holiday check script (adjust the path according to your project structure)
        if ($ck_pf_nt == 1) {

            // Process date
            $cu_ff_dt = $noticesModel->chksDate(date('Y-m-d', strtotime($ff_dt)));
            $cu_ff_dt = date('d-m-Y', strtotime($cu_ff_dt));
            $todays_dt = date('d-m-Y');

            // Compare dates and adjust accordingly
            if (strtotime($cu_ff_dt) < strtotime($todays_dt)) {
                $cu_ff_dt = date('d-m-Y', strtotime(date('d-m-Y') . ' + 45 days'));
                $cu_ff_dt = $noticesModel->chksDate(date('Y-m-d', strtotime($cu_ff_dt)));
                $cu_ff_dt = date('d-m-Y', strtotime($cu_ff_dt));
            }

            // Additional check for `$get_sel_con`
            if ($get_sel_con == 2) {
                $cu_ff_dt = date('Y-m-d', strtotime($cu_ff_dt));

                $cu_ff_dt = $noticesModel->get_next_working_date_new($cu_ff_dt, '', $res_sq_con_not['mainhead']);
                $cu_ff_dt = date('d-m-Y', strtotime($cu_ff_dt));
            }
        } else {
            // Handle other case
            $cu_ff_dt = $res_sql_pf;
        }

        ?>
        <div style="text-align: left; color: red;">
            <h4 style="padding: 0; margin: 0;">If date is not proper in Fixed For please check reader remark is correct or not or contact the server room.</h4>
        </div>

        <div style="text-align: center; padding-top: 20px; width: 100%;">
            <b>Order Date:</b> <?php echo (!empty($or_dt)) ? date('d-m-Y', strtotime($or_dt)) : ''; ?>

            <?php
            if (get_display_status_with_date_differnces($cu_ff_dt) == 'T') { ?>
                <b>Fixed For:</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input style="width:50%"
                    type="text"
                    name="txtFFX"
                    id="txtFFX"
                    value="<?php echo ($res_cont <= 0) ? $cu_ff_dt : date('d-m-Y', strtotime($res_sq_fi_sub['fixed_for'])); ?>"
                    class="dtp"
                    maxlength="10"
                    size="10"
                    onfocus="clear_data(this.id)"
                    style="background-color: white; color: black;" />
                &nbsp;&nbsp;&nbsp;&nbsp;
            <?php } 
            
            
            ?>

            <b>Subject:</b>&nbsp;&nbsp;&nbsp;&nbsp;
            <input
                style="width:50%"
                type="text"
                name="txtSub_nm"
                id="txtSub_nm"
                size="80"
                value="<?php echo ($res_cont > 0) ? $res_sq_fi_sub['sub_tal'] : ''; ?>" />
        </div>
                
        <div style="height: 140px; width: 100%; overflow-x: hidden; overflow-y: auto; margin-top: 10px;">
            <div class="fl_prj" style="width: 50%; float: left;">
                <fieldset>
                    <legend><b>Petitioner Details</b></legend>
                    <table width="100%" style="margin:0">
                        <tr>
                            <th style="text-align: left;">Name</th>
                            <td style="background: transparent;"><?php echo $row['pet_name']; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Advocate Name</th>
                            <td style="background: transparent;"><?php echo $row['name'] ?? ''; ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>

            <div class="fl_prj" style="width: 50%; float: left;">
                <fieldset>
                    <legend><b>Respondent Details</b></legend>
                    <table width="100%"  style="margin:0">
                        <tr>
                            <th style="text-align: left;">Name</th>
                            <td style="background: transparent;"><?php echo $row['res_name']; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Advocate Name</th>
                            <td style="background: transparent;"><?php echo $row['res_adv_nm']; ?></td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </div>
        <?php
        $ck_mul_re_st = $noticesModel->checkMultipleRecords($dairy_no, $date);
        ?>
        <input type="hidden" name="hd_ck_mul_re_st" id="hd_ck_mul_re_st" value="<?php echo $ck_mul_re_st; ?>" />
        <?php
        // Initialize arrays
        $notice = array();
        $send_too = array();
        $state = array();
        $sq_ca_nt = $noticesModel->getCaseAndSectionDetails($row);
        if ($sq_ca_nt) {
            $res_ca_nt = $sq_ca_nt->nature;
            $res_skey = $sq_ca_nt->skey;
        } else {
            $res_ca_nt = null;
            $res_skey = null;
        }
        $sql_section = $noticesModel->getCaseAndSectionDetails2($res_skey);
        if ($sql_section) {
            $res_section = $sql_section->id;
        } else {
            $res_section = null;
        }
        ?>
        <input type="hidden" name="hd_hd_sec_id" id="hd_hd_sec_id" value="<?php echo $res_section; ?>" />
        <input type="hidden" name="hd_hd_res_ca_nt" id="hd_hd_res_ca_nt" value="<?php echo $res_ca_nt; ?>" />
        <?php
        $c_case = $noticesModel->getNotice($res_ca_nt, $res_section, $row['c_status'], $row['casetype_id']);

        $sen_cp_to = $noticesModel->getSendTo();

        $get_states = $noticesModel->getState();
        ?>
        <div style="margin-top: 10px;">
            <table width="100%" id="tb_ap_ck" class="custom-table_ table c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
                <thead>
                <tr>
                    <th>
                        Check
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Address
                    </th>
                    <th>
                        State/City
                    </th>
                    <th>
                        Notice Type
                    </th>
                    <th>
                        Amount
                    </th>

                </tr>
                </thead>
                <?php
                $sql_party = $noticesModel->get_sql_party($dairy_no, $date);
                $sno = 0;
                $c_pet = 0;
                $c_res = 0;
                $c_add_pet = 0;
                $c_add_res = 0;
                $c_add_pet_add = 0;
                $c_add_res_add = 0;
                $c_other = 0;
                $ct_tt = 0;

                foreach ($sql_party as $row1) {
                    $ck_en_nt = '0';

                    if ($row1['sr_no'] != '0') {
                        // Prepare and execute the query
                        $result = $noticesModel->get_tw_tal_del($dairy_no, $row1, $date);
                        if ($result) {
                            $ck_en_nt = '1';
                            $ck_en_nt_x = $result;
                        } else {
                            $ck_en_nt_x = '';
                        }
                    } else {
                        $ck_en_nt_x = '';
                    }
                    if ($row1['sr_no'] == '0') {
                        $ck_en_nt = '1';
                    }

                    $ck_en_nt_x = $ck_en_nt_x ?: [];

                    $ck_en_nt_x['name'] = @$ck_en_nt_x['name'] ?: $row1['partyname'];
                    $ck_en_nt_x['address'] = @$ck_en_nt_x['address'] ?: $row1['addr1'];
                    $ck_en_nt_x['nt_type'] = @$ck_en_nt_x['nt_type'] ?: $row1['nt_type'];
                    $ck_en_nt_x['del_type'] = @$ck_en_nt_x['del_type'] ?: @$row1['del_type'];
                    $ck_en_nt_x['send_to'] = @$ck_en_nt_x['send_to'] ?: @$row1['send_to'];
                    $ck_en_nt_x['cp_sn_to'] = @$ck_en_nt_x['cp_sn_to'] ?: @$row1['cp_sn_to'];
                    $ck_en_nt_x['id'] = @$ck_en_nt_x['id'] ?: @$row1['id'];
                    $ck_en_nt_x['jud1'] = @$ck_en_nt_x['jud1'] ?: @$row1['jud1'];
                    $ck_en_nt_x['jud2'] = @$ck_en_nt_x['jud2'] ?: @$row1['jud2'];
                    $ck_en_nt_x['note'] = @$ck_en_nt_x['note'] ?: @$row1['note'];
                    $ck_en_nt_x['amount'] = @$ck_en_nt_x['amount'] ?: @$row1['amount'];

                    if ($row1['pet_res'] == 'P' && $c_pet == 0 && $row1['sr_no'] == 1) {
                ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner</b>
                            </td>
                        </tr>
                    <?php
                        $c_pet++;
                    } elseif ($row1['pet_res'] == 'R' && $c_res == 0 && $row1['sr_no'] == 1) {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent</b>
                            </td>
                        </tr>
                    <?php
                        $c_res++;
                    } elseif ($row1['pet_res'] == 'P' && $c_add_pet == 0 && $row1['sr_no'] > 1) {
                        $classToAdd = 'cl_chk_parties_P';
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_P' onchange="CheckedAll_P()" /></b>
                            </td>
                        </tr>
                    <?php
                        $c_add_pet++;
                    } elseif ($row1['pet_res'] == 'R' && $c_add_res == 0 && $row1['sr_no'] > 1) {
                        $classToAdd = 'cl_chk_parties_R';
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_R' onchange="CheckedAll_R()" /></b>
                            </td>
                        </tr>
                    <?php
                        $c_add_res++;
                    } elseif ($row1['pet_res'] == 'P' && $c_add_pet_add == 0 && $row1['sr_no'] == 0) {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner(Additional Party)(Extra)</b>
                            </td>
                        </tr>
                    <?php
                        $c_add_pet_add++;
                    } elseif ($row1['pet_res'] == 'R' && $c_add_res_add == 0 && $row1['sr_no'] == 0) {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent(Additional Party)(Extra)</b>
                            </td>
                        </tr>
                    <?php
                        $c_add_res_add++;
                    } elseif ($row1['pet_res'] == '' && $c_other == 0 && $row1['sr_no'] == 0) {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Other</b>
                            </td>
                        </tr>
                    <?php
                        $c_other++;
                    }
                    ?>
                    <tr>
                        <?php
                        if (($sno != '1') && ($sno != '0') && ($row1['pet_res'] == 'R')) {
                            $add_class = $row1['pet_res'];
                        } elseif (($sno != '1') && ($sno != '0') && ($row1['pet_res'] == 'P')) {
                            $add_class = $row1['pet_res'];
                        } else {
                            $add_class = '';
                        }

                        ?>
                        <td>
                            <input type="checkbox"
                                name="chk_id<?= esc($sno); ?>"
                                id="chk_id<?= esc($sno); ?>"
                                style="background-color: black"
                                <?= $ck_en_nt == '1' ? 'checked="checked"' : '' ?>
                                class="cl_chk_parties <?= !empty($classToAdd) ? $classToAdd : null; ?> <?= !empty($add_class) ? $add_class : null; ?>" />
                            <br />
                            <span style="color: #2b15db">
                                <b id="sp_pet_res_id<?= esc($sno); ?>">
                                    <?= esc($row1['pet_res'] . '-' . $row1['sr_no']); ?>
                                </b>
                            </span>
                        </td>
                        <td style="width: 23%;" id="td_cell_s<?= esc($sno); ?>">
                            <textarea  id="sp_nm<?= esc($sno); ?>"
                                style="resize:none;width: 80%"
                                onfocus="clear_data(this.id)">
        <?php if ($ck_en_nt == '0'): ?>
            <?= esc(trim($row1['partyname'])); ?>
            <?php if ($row1['sonof'] != ''): ?>
                <?= $row1['sonof'] == 'S' ? ' S/o ' : ($row1['sonof'] == 'D' ? ' D/o ' : ($row1['sonof'] == 'W' ? ' W/o ' : '')) ?>
                <?= esc($row1['prfhname']); ?>
            <?php endif; ?>
        <?php elseif ($ck_en_nt == '1'): ?>
            <?= esc($ck_en_nt_x['name']); ?>
            <?php
                        // Sample function calls, replace with actual logic
                        $get_advocates = get_advocates_new($dairy_no);
                        pr($get_advocates);
                        $get_lc_highcourt = get_lc_highcourt($dairy_no);
            ?>
        <?php endif; ?>
    </textarea>
                            <?php if ($row1['enrol_no'] != '' && $row1['enrol_yr'] != ''): ?>
                                <span id="sp_enroll<?= esc($sno); ?>">No.</span>
                                <input onfocus="clear_data(this.id)"
                                    name="hdinenroll_<?= esc($sno); ?>"
                                    id="hdinenroll_<?= esc($sno); ?>"
                                    maxlength="6"
                                    size="1"
                                    type="text"
                                    value="<?= esc($row1['enrol_no']); ?>" />
                                <span id="sp_enrollyr<?= esc($sno); ?>">Yr</span>
                                <input onfocus="clear_data(this.id)"
                                    name="hdinenrollyr_<?= esc($sno); ?>"
                                    id="hdinenrollyr_<?= esc($sno); ?>"
                                    onblur="get_eroll_yr(this.id)"
                                    maxlength="4"
                                    size="1"
                                    type="text"
                                    value="<?= esc($row1['enrol_yr']); ?>" />
                            <?php endif; ?>
                            <input type="hidden" name="hd_sr_no<?= esc($sno); ?>" id="hd_sr_no<?= esc($sno); ?>" value="<?= esc($row1['sr_no']); ?>" />
                            <input type="hidden" name="hd_pet_res<?= esc($sno); ?>" id="hd_pet_res<?= esc($sno); ?>" value="<?= esc($row1['pet_res']); ?>" />
                        </td>

                        <td style="width: 23%;">
                            <textarea id="sp_add<?= $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)">
        <?= ($ck_en_nt == '0') ? trim($row1['addr1'] . ' ' . $row1['addr2']) : $ck_en_nt_x['address']; ?>
    </textarea>
                        </td>
                        <td style="width: 9%;">
                            <div>
                                <select name="ddlState<?= $sno; ?>" id="ddlState<?= $sno; ?>" onchange="getCity(this.value, this.id, '0')" style="width: 120px" onfocus="clear_data(this.id)">
                                    <option value="">Select</option>
                                    <?php foreach ($get_states as $k2): ?>
                                        <?php
                                        $key2 = explode('^', $k2);
                                        $selected = (!empty($row1['state']) && preg_match('/[0-9]/', $row1['state']) && $row1['state'] !== NULL && $row1['state'] !== '') ? ($key2[0] == $row1['state'] ? 'selected' : '') : '';
                                        ?>
                                        <option value="<?= $key2[0]; ?>" <?= $selected; ?>><?= $key2[1]; ?></option>
                                    <?php endforeach; ?>
                                    <?php if (($row1['state'] === NULL || $row1['state'] == 0) && $ck_en_nt == '1'): ?>
                                        <option value="0" selected="selected">None</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div style="margin-top: 10px">
                                <select name="ddlCity<?= $sno; ?>" id="ddlCity<?= $sno; ?>" style="width: 100%" onfocus="clear_data(this.id)">
                                    <option value="">Select</option>
                                    <?php if (!empty($row1['state']) && preg_match('/[0-9]/', $row1['state'])): ?>
                                        <?php
                                        $query_city = $noticesModel->getCities($row1['state']);
                                        foreach ($query_city as $row_c):
                                        ?>
                                            <option value="<?= $row_c['district_code']; ?>" <?= ($row_c['district_code'] == $row1['city']) ? 'selected' : ''; ?>>
                                                <?= $row_c['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="0" <?= ($row1['city'] == 0) ? 'selected' : ''; ?>></option>
                                    <?php else: ?>
                                        <?php 
                                            if(!empty($get_districts))
                                            {
                                                foreach ($get_districts as $k2): ?>
                                                <?php
                                                $key2 = explode('^', $k2);
                                                ?>
                                                <option value="<?= $key2[0]; ?>"><?= $key2[1]; ?></option>
                                                <?php endforeach; 
                                            }
                                            ?>
                                    <?php endif; ?>
                                    <?php if (($row1['state'] === NULL || $row1['state'] == 0) && $ck_en_nt == '1'): ?>
                                        <option value="0" selected="selected">None</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </td>
                        <td style="width: 30%;">
                            <div style="text-align: center">
                                <span id="sp_mul<?= $sno; ?>" style="color: red;font-size: 9px;display: none" onclick="get_mul_si(this.id)" class="sp_c_mul">
                                    <?= ($ck_en_nt == '0') ? 'Multiple' : (preg_match('/,/', $ck_en_nt_x['nt_type']) ? 'Single' : 'Multiple'); ?>
                                </span>
                            </div>
                            <select name="ddl_nt<?= $sno; ?>" id="ddl_nt<?= $sno; ?>" style="width: 100%;" onfocus="clear_data(this.id)" <?= ($ck_en_nt == '1' && preg_match('/,/', $ck_en_nt_x['nt_type'])) ? 'multiple="multiple"' : ''; ?> onchange="get_wh_p_r(this.value, this.id)">
                                <option value="">Select</option>
                                <?php foreach ($c_case as $k): ?>
                                    <?php
                                    $key = explode('^', $k);
                                    $selected = ($ck_en_nt == '1') ? (in_array($key[0], explode(',', $ck_en_nt_x['nt_type'])) ? 'selected' : '') : '';
                                    ?>
                                    <option value="<?= $key[0]; ?>" <?= $selected; ?>><?= $key[1]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td style="width: 6%;">
                            <input type="text" size="9" name="txtAmount<?= $sno; ?>" id="txtAmount<?= $sno; ?>" onkeypress="return OnlyNumbersTalwana(event,this.id)" value="<?= ($ck_en_nt == '1') ? $ck_en_nt_x['amount'] : ''; ?>" />
                        </td>
                    </tr>


                    <tr style="border: 0px; border-color: white; <?= $ck_en_nt != '1' ? 'display: none;' : '' ?>" id="tr_del_send_copy<?= esc($sno, 'attr') ?>">
                        <td colspan="7" style="border: 0px; border-color: white;">
                            <table style="width: 100%" class="table c_vertical_align tbl_border table_tr_th_w_clr">
                                <tr>
                                    <th style="width: 10%">
                                        Delivery Mode
                                    </th>
                                    <th style="width: 45%">
                                        Send To / State / District
                                    </th>
                                    <th style="width: 45%">
                                        Copy Send To / State / District
                                    </th>
                                </tr>
                                <?php
                                $del_modes = '';
                                $del_tw_send_to = '';
                                $del_tw_copysend_to = '';
                                $ex_c_st = '';
                                $tw_o_r_s = $noticesModel->get_tw_o_r_s($ck_en_nt_x);
                                if (!empty($tw_o_r_s)) {
                                    foreach ($tw_o_r_s as $row) {
                                        if ($del_modes == '') {
                                            $del_modes = $row['del_type'];
                                        } else {
                                            $del_modes .= $row['del_type'];
                                        }
                                    }
                                }
                                $tw_send_to = $noticesModel->get_tw_o_r($ck_en_nt_x);
                                if (!empty($tw_send_to)) {
                                    foreach ($tw_send_to as $row4) {
                                        $row_data = $row4['del_type'] . '~' . $row4['tw_sn_to'] . '~' . $row4['sendto_state'] . '~' . $row4['sendto_district'] . '~' . $row4['send_to_type'];

                                        if ($del_tw_send_to == '') {
                                            $del_tw_send_to = $row_data;
                                        } else {
                                            $del_tw_send_to .= '#' . $row_data;
                                        }
                                    }
                                }
                                $tw_cp_send_to = $noticesModel->get_tw_cp_send_to($ck_en_nt_x);
                                if (!empty($tw_cp_send_to)) {
                                    foreach ($tw_cp_send_to as $row5) {
                                        if ($main_id != $row5['id']) {
                                            $row_data = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];

                                            if ($del_tw_copysend_to == '') {
                                                $del_tw_copysend_to = $row_data;
                                            } else {
                                                $del_tw_copysend_to .= '#' . $row_data;
                                            }
                                            $main_id = $row5['id'];
                                        } else {
                                            $row_data = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];

                                            if ($ex_c_st == '') {
                                                $ex_c_st = $row_data;
                                            } else {
                                                $ex_c_st .= '#' . $row_data;
                                            }
                                        }
                                    }
                                }


                                for ($q = 0; $q < 2; $q++) {
                                    $del_mode = '';
                                    $id_name = '';
                                    $mode = '';
                                    $sht_nm = '';
                                    $ini_val = 0;

                                    if ($q == 0) {
                                        $del_mode = 'Ordinary';
                                        $id_name = 'chkOrd';
                                        $mode = 'o';
                                        $sht_nm = 'O';
                                    } else if ($q == 1) {
                                        $del_mode = 'Registry';
                                        $id_name = 'chkReg';
                                        $mode = 'r';
                                        $sht_nm = 'R';
                                    } else if ($q == 2) {
                                        $del_mode = 'Humdust';
                                        $id_name = 'chkAdvHum';
                                        $mode = 'h';
                                        $sht_nm = 'H';
                                    } else if ($q == 3) {
                                        $del_mode = 'Speed Post';
                                        $id_name = 'chkAdvReg';
                                        $mode = 'a';
                                        $sht_nm = 'A';
                                    }
                                    $ck_not = '';
                                    if (!empty($del_modes)) {
                                        for ($index2 = 0; $index2 < strlen($del_modes); $index2++) {
                                            // Check if the current character matches the short name
                                            if ($del_modes[$index2] == $sht_nm) {
                                                $ck_not = " checked='checked'";
                                            }
                                        }
                                    }
                                    $tw_send_s = '';
                                    $sendto_state = '';
                                    $sendto_district = '';
                                    $sendto_type = '';
                                    if (!empty($del_tw_send_to)) {
                                        $ex_del_tw_send_to = explode('#', $del_tw_send_to);

                                        foreach ($ex_del_tw_send_to as $entry) {
                                            $ex_in_exp = explode('~', $entry);

                                            // Ensure the exploded array has the expected number of elements
                                            if (count($ex_in_exp) >= 5 && $ex_in_exp[0] == $sht_nm) {
                                                $tw_send_s = $ex_in_exp[1];
                                                $sendto_state = $ex_in_exp[2];
                                                $sendto_district = $ex_in_exp[3];
                                                $sendto_type = $ex_in_exp[4];
                                            }
                                        }
                                    }
                                    $c_tw_send_s = '';
                                    $c_sendto_state = '';
                                    $c_sendto_district = '';
                                    $c_sendto_type = '';
                                    if (!empty($del_tw_copysend_to)) {
                                        $ex_del_c_tw_send_to = explode('#', $del_tw_copysend_to);

                                        foreach ($ex_del_c_tw_send_to as $entry) {
                                            $ex_in_exp = explode('~', $entry);

                                            // Ensure the exploded array has the expected number of elements
                                            if (count($ex_in_exp) >= 5 && $ex_in_exp[0] == $sht_nm) {
                                                $c_tw_send_s = $ex_in_exp[1];
                                                $c_sendto_state = $ex_in_exp[2];
                                                $c_sendto_district = $ex_in_exp[3];
                                                $c_sendto_type = $ex_in_exp[4];
                                            }
                                        }
                                    }

                                ?>
                                    <tr style="border: 0px; border-color: white">
                                        <td>
                                            <input class="cl_del_mod<?= $sno; ?>"
                                                value="<?= $sht_nm; ?>"
                                                title="<?= $del_mode; ?>"
                                                type="checkbox"
                                                name="<?= $id_name . $sno; ?>"
                                                id="<?= $id_name . $sno; ?>"
                                                onclick="show_hd(this.id)"
                                                <?= ($ck_en_nt == 1) ? $ck_not : ''; ?> />
                                            <span id="sp_ordinary_ck<?= $sno; ?>"><?= $sht_nm; ?></span>
                                        </td>
                                        <td>
                                            <select name="ddl_send_type<?= $mode . $sno; ?>"
                                                id="ddl_send_type<?= $mode . $sno; ?>"
                                                onchange="get_send_to_type(this.id, this.value, '1', '<?= $mode; ?>')">
                                                <option value="">Select</option>
                                                <option value="2" <?= ($sendto_type == 2 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Other</option>
                                                <option value="1" <?= ($sendto_type == 1 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                <option value="3" <?= ($sendto_type == 3 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Court</option>
                                            </select>

                                            <select name="ddlSendTo_<?= $mode . $sno; ?>"
                                                id="ddlSendTo_<?= $mode . $sno; ?>"
                                                onfocus="clear_data(this.id)"
                                                onchange="get_nms(this.value, this.id)"
                                                style="width: 130px">
                                                <option value="">Select</option>
                                                <?php if ($ck_en_nt == '1') :
                                                    $s_to_d = ($sendto_type == 2) ? $sen_cp_to : (($sendto_type == 1) ? $get_advocates : $get_lc_highcourt);
                                                    foreach ($s_to_d as $k1) :
                                                        $key1 = explode('^', $k1);
                                                ?>
                                                        <option value="<?= $key1[0]; ?>" <?= ($tw_send_s == $key1[0]) ? 'selected="selected"' : ''; ?>><?= $key1[1]; ?></option>
                                                <?php endforeach;
                                                endif; ?>
                                            </select>

                                            <select name="ddl_sndto_state_<?= $mode . $sno; ?>"
                                                id="ddl_sndto_state_<?= $mode . $sno; ?>"
                                                style="width: 100px"
                                                onchange="getCity(this.value, this.id, '1', '<?= $mode; ?>')">
                                                <option value="">Select</option>
                                                <?php foreach ($get_states as $k2) :
                                                    $key2 = explode('^', $k2);
                                                ?>
                                                    <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $sendto_state == $key2[0]) ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select name="ddl_sndto_dst_<?= $mode . $sno; ?>"
                                                id="ddl_sndto_dst_<?= $mode . $sno; ?>"
                                                style="width: 100px">
                                                <option value="">Select</option>
                                                <?php if ($sendto_district != '') :
                                                    $get_districts = get_citys($sendto_state);
                                                    foreach ($get_districts as $k2) :
                                                        $key2 = explode('^', $k2);
                                                ?>
                                                        <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $sendto_district == $key2[0]) ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                <?php endforeach;
                                                endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <select name="ddl_send_copy_type<?= $mode . $sno; ?>"
                                                    id="ddl_send_copy_type<?= $mode . $sno; ?>"
                                                    onchange="get_send_to_type(this.id, this.value, '2', '<?= $mode; ?>')">
                                                    <option value="">Select</option>
                                                    <option value="2" <?= ($c_sendto_type == 2 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Other</option>
                                                    <option value="1" <?= ($c_sendto_type == 1 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                </select>

                                                <select name="ddlSendCopyTo_<?= $mode . $sno; ?>"
                                                    id="ddlSendCopyTo_<?= $mode . $sno; ?>"
                                                    onfocus="clear_data(this.id)"
                                                    style="width: 130px;">
                                                    <option value="">Select</option>
                                                    <?php if ($ck_en_nt == '1') :
                                                        $s_to_d = ($c_sendto_type == 2) ? $sen_cp_to : ($c_sendto_type == 1 ? $get_advocates : []);
                                                        foreach ($s_to_d as $k1) :
                                                            $key1 = explode('^', $k1);
                                                    ?>
                                                            <option value="<?= $key1[0]; ?>" <?= ($ck_en_nt == '1' && $c_tw_send_s == $key1[0]) ? 'selected="selected"' : ''; ?>>
                                                                <?= $key1[1]; ?>
                                                            </option>
                                                    <?php endforeach;
                                                    endif; ?>
                                                </select>

                                                <select name="ddl_cpsndto_state_<?= $mode . $sno; ?>"
                                                    id="ddl_cpsndto_state_<?= $mode . $sno; ?>"
                                                    style="width: 100px"
                                                    onchange="getCity(this.value, this.id, '2', '<?= $mode; ?>')">
                                                    <option value="">Select</option>
                                                    <?php foreach ($get_states as $k2) :
                                                        $key2 = explode('^', $k2);
                                                    ?>
                                                        <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $c_sendto_state == $key2[0]) ? 'selected="selected"' : ''; ?>>
                                                            <?= $key2[1]; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <select name="ddl_cpsndto_dst_<?= $mode . $sno; ?>"
                                                    id="ddl_cpsndto_dst_<?= $mode . $sno; ?>"
                                                    style="width: 100px">
                                                    <option value="">Select</option>
                                                    <?php if ($c_sendto_district != '') :
                                                        $get_districts = get_citys($c_sendto_state);
                                                        foreach ($get_districts as $k2) :
                                                            $key2 = explode('^', $k2);
                                                    ?>
                                                            <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $c_sendto_district == $key2[0]) ? 'selected="selected"' : ''; ?>>
                                                                <?= $key2[1]; ?>
                                                            </option>
                                                    <?php endforeach;
                                                    endif; ?>
                                                </select>

                                                <div id="dv_ext_cst<?= $mode . $sno; ?>">
                                                    <?php if ($ex_c_st != '') :
                                                        $ex_ex_c_st = explode('#', $ex_c_st);
                                                        foreach ($ex_ex_c_st as $index4 => $ex_cst) :
                                                            $ex_in_exp = explode('~', $ex_cst);
                                                            if ($ex_in_exp[0] == $sht_nm) :
                                                                $ini_val++;
                                                                $c_tw_send_s = $ex_in_exp[1];
                                                                $c_sendto_state = $ex_in_exp[2];
                                                                $c_sendto_district = $ex_in_exp[3];
                                                                $c_sendto_type = $ex_in_exp[4];
                                                    ?>
                                                                <div style="margin-top: 10px">
                                                                    <select name="ddl_send_copy_type<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        id="ddl_send_copy_type<?= $mode . $sno; ?>_<?= $index4; ?>">
                                                                        <option value="">Select</option>
                                                                        <option value="2" <?= ($c_sendto_type == 2 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Other</option>
                                                                        <option value="1" <?= ($c_sendto_type == 1 && $ck_en_nt == '1') ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                                    </select>

                                                                    <select name="ddlSendCopyTo_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        id="ddlSendCopyTo_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        onfocus="clear_data(this.id)"
                                                                        style="width: 130px;">
                                                                        <option value="">Select</option>
                                                                        <?php if ($ck_en_nt == '1') :
                                                                            $s_to_d = ($c_sendto_type == 2) ? $sen_cp_to : ($c_sendto_type == 1 ? $get_advocates : ($sendto_type == 3 ? $get_lc_highcourt : []));
                                                                            foreach ($s_to_d as $k1) :
                                                                                $key1 = explode('^', $k1);
                                                                        ?>
                                                                                <option value="<?= $key1[0]; ?>" <?= ($ck_en_nt == '1' && $c_tw_send_s == $key1[0]) ? 'selected="selected"' : ''; ?>>
                                                                                    <?= $key1[1]; ?>
                                                                                </option>
                                                                        <?php endforeach;
                                                                        endif; ?>
                                                                    </select>

                                                                    <select name="ddl_cpsndto_state_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        id="ddl_cpsndto_state_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        style="width: 100px"
                                                                        onchange="getCity(this.value, this.id, '3', 'r')">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($get_states as $k2) :
                                                                            $key2 = explode('^', $k2);
                                                                        ?>
                                                                            <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $c_sendto_state == $key2[0]) ? 'selected="selected"' : ''; ?>>
                                                                                <?= $key2[1]; ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>

                                                                    <select name="ddl_cpsndto_dst_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        id="ddl_cpsndto_dst_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        style="width: 100px">
                                                                        <option value="">Select</option>
                                                                        <?php if ($c_sendto_district != '') :
                                                                            $get_districts = get_citys($c_sendto_state);
                                                                            foreach ($get_districts as $k2) :
                                                                                $key2 = explode('^', $k2);
                                                                        ?>
                                                                                <option value="<?= $key2[0]; ?>" <?= ($ck_en_nt == '1' && $c_sendto_district == $key2[0]) ? 'selected="selected"' : ''; ?>>
                                                                                    <?= $key2[1]; ?>
                                                                                </option>
                                                                        <?php endforeach;
                                                                        endif; ?>
                                                                    </select>
                                                                </div>
                                                    <?php endif;
                                                        endforeach;
                                                    endif; ?>
                                                </div>

                                                <div style="text-align: center"
                                                    id="dvad_<?= $mode . '_' . $sno; ?>"
                                                    class="cl_add_cst">
                                                    Add
                                                </div>

                                                <input type="hidden"
                                                    name="hd_Sendcopyto_<?= $mode . $sno; ?>"
                                                    id="hd_Sendcopyto_<?= $mode . $sno; ?>"
                                                    value="<?= $ini_val; ?>" />
                                            </div>
                                        </td>

                                    </tr>

                                <?php
                                }
                                ?>
                            </table>
                            <input type="hidden" name="hd_new_upd<?= $sno; ?>" id="hd_new_upd<?= $sno; ?>" value="<?= $ck_en_nt; ?>" />
                            <input type="hidden" name="hd_mn_id<?= $sno; ?>" id="hd_mn_id<?= $sno; ?>" value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt == '1' ? $ck_en_nt_x['id'] : ''); ?>" />

                            <?php if ($ck_en_nt == '1' && $ct_tt == 0): ?>
                                <?php $ct_tt = 1; ?>
                                <input type="hidden" name="hd_jud1" id="hd_jud1" value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt_x['jud1'] ?? ''); ?>" />
                                <input type="hidden" name="hd_jud2" id="hd_jud2" value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt_x['jud2'] ?? ''); ?>" />
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
                <tr>
                    <td colspan="7" style="text-align: center">
                        <!-- Uncomment the following lines if needed for radio buttons
        <input type="radio" name="rdnChkPet_res" id="rdnChkPet"/>Petitioner
        <input type="radio" name="rdnChkPet_res" id="rdnChkRes"/>Respondent
        -->
                        <input type="button" name="btn_sp_aex" id="btn_sp_aex" class="sp_aex" onclick="appendRow('tb_ap_ck')" value="Add Extra Party" />
                        <input type="button" name="btn_sp_aex_hc" id="btn_sp_aex_hc" class="sp_aex_hc" onclick="appendRow_hc('tb_ap_ck')" value="High Court" />
                    </td>
                </tr>
               

            </table>
            <input type="hidden" name="hd_tot" id="hd_tot" value="<?= $sno; ?>" />
                <div style="text-align: center; margin-top: 10px;">
                    <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="get_save_dt()" class="bb_sub_m" />
                    <!-- Uncomment if needed -->
                    <!-- <input type="button" name="btnDummy" id="btnDummy" value="Dummy" onclick="dummy()"/> -->
                </div>
        </div>
    <?php

    } else {
       
    ?>
        <div style="text-align: center; margin-top: 10px;">
            <b style="color: red;">Case already disposed</b>
        </div>

        <div style="width: 100%;  margin-top: 10px;">
            <div class="fl_prj" style="width: 50%; float: left;">
                <fieldset>
                    <legend><b>Petitioner Details</b></legend>
                    <table width="100%">
                        <thead>
                        <tr>
                            <th style="text-align: left">Name</th>
                            <td style="background: transparent;"><?= $row['pet_name'] ?? ''; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Advocate Name</th>
                            <td style="background: transparent;"><?= $row['name'] ?? ''; ?></td>
                        </tr>
                    </thead>
                    </table>
                </fieldset>
            </div>
            <div class="fl_prj" style="width: 50%; float: left;">
                <fieldset>
                    <legend><b>Respondent Details</b></legend>
                    <table width="100%">
                        <thead>
                        <tr>
                            <th style="text-align: left">Name</th>
                            <td style="background: transparent;"><?= esc($row['res_name']); ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Advocate Name</th>
                            <td style="background: transparent;"><?= esc($row['res_adv_nm']); ?></td>
                        </tr>
                        </thead>
                    </table>
                </fieldset>
            </div>
        </div>
        <?php
        $sql_tal_ck = $noticesModel->countRecords($dairy_no, $date);
        if ($sql_tal_ck > 0) {
            $ck_mul_re_st = '1';
        } else {
            $ck_mul_re_st = '0';
        }
        ?>
        <input type="hidden" name="hd_ck_mul_re_st" id="hd_ck_mul_re_st" value="<?= esc($ck_mul_re_st); ?>" />
        <?php
        $notice = array();
        $send_too = array();
        $state = array();
        $sq_ca_nt = $noticesModel->getCaseTypeByCode($row['casetype_id']);
        if ($sq_ca_nt) {
            $res_ca_nt = $sq_ca_nt['nature'];
            $res_skey = $sq_ca_nt['skey'];
        } else {
            $res_ca_nt = null;
            $res_skey = null;
        }
        $res_section = $noticesModel->getIdByName($res_skey);
        ?>
        <input type="hidden" name="hd_hd_sec_id" id="hd_hd_sec_id" value="<?php echo $res_section; ?>" />
        <input type="hidden" name="hd_hd_res_ca_nt" id="hd_hd_res_ca_nt" value="<?php echo $res_ca_nt; ?>" />
        <?php
        $c_case = $noticesModel->getNotice($res_ca_nt, $res_section, $row['c_status'], $row['casetype_id']);
        $sen_cp_to = $noticesModel->getSendTo();
        $get_states = $noticesModel->getState();
        ?>
        <div style="margin-top: 10px;">
            <table width="100%" id="tb_ap_ck" class="table c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
                <thead>
                <tr>
                    <th>Check</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>State/City</th>
                    <th>Notice Type</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <?php
                $sql_party = $noticesModel->get_sql_party($dairy_no, $date);
                
                $sno = 0;
                //        $res_p='';
                $c_pet = 0;
                $c_res = 0;
                $c_add_pet = 0;
                $c_add_res = 0;
                $c_add_pet_add = 0;
                $c_add_res_add = 0;
                $c_other = 0;
                $ct_tt = 0;
                foreach ($sql_party as $row1) 
                {
                   
                    $ck_en_nt = '0';
                    $sq_ck_en_pr_nt = $noticesModel->get_tw_tal_del($dairy_no, $row1, $date);
                    
                    if ($row1['sr_no'] != '0') {
                        $sq_ck_en_pr_nt = $noticesModel->getDetails($dairy_no, $row1['sr_no'], $row1['pet_res'], $date);
                       
                        if (!empty($sq_ck_en_pr_nt)) {
                            $ck_en_nt = '1';
                            $ck_en_nt_x = $sq_ck_en_pr_nt;
                        }
                    } else {
                        $ck_en_nt_x = '';
                    }

                    

                    if ($row1['sr_no'] == '0') {
                        $ck_en_nt = '1';  // Set flag or value
                    }

                    // Populate $ck_en_nt_x if fields are empty
                    if (empty($ck_en_nt_x['name'])) {
                        $ck_en_nt_x['name'] = $row1['partyname'] ?? '';
                    }

                    if (empty($ck_en_nt_x['address'])) {
                        $ck_en_nt_x['address'] = $row1['addr1'] ?? '';
                    }

                    if (empty($ck_en_nt_x['nt_type'])) {
                        $ck_en_nt_x['nt_type'] = $row1['nt_type'] ?? '';
                    }

                    if (empty($ck_en_nt_x['del_type'])) {
                        $ck_en_nt_x['del_type'] = $row1['del_type'] ?? '';
                    }

                    if (empty($ck_en_nt_x['send_to'])) {
                        $ck_en_nt_x['send_to'] = $row1['send_to'] ?? '';
                    }

                    if (empty($ck_en_nt_x['cp_sn_to'])) {
                        $ck_en_nt_x['cp_sn_to'] = $row1['cp_sn_to'] ?? '';
                    }

                    if (empty($ck_en_nt_x['id'])) {
                        $ck_en_nt_x['id'] = $row1['id'] ?? '';
                    }

                    if (empty($ck_en_nt_x['jud1'])) {
                        $ck_en_nt_x['jud1'] = $row1['jud1'] ?? '';
                    }

                    if (empty($ck_en_nt_x['jud2'])) {
                        $ck_en_nt_x['jud2'] = $row1['jud2'] ?? '';
                    }

                    if (empty($ck_en_nt_x['note'])) {
                        $ck_en_nt_x['note'] = $row1['note'] ?? '';
                    }

                    // Uncomment the mobile number check if needed
                    // if (empty($ck_en_nt_x['mob_no'])) {
                    //     $ck_en_nt_x['mob_no'] = $row1['mob_no'];
                    // }

                    if (empty($ck_en_nt_x['amount'])) {
                        $ck_en_nt_x['amount'] = $row1['amount'] ?? '';
                    }
                    
                    ?>
                    <?php if ($row1['pet_res'] == 'P' && $c_pet == 0 && $row1['sr_no'] == 1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner</b>
                            </td>
                        </tr>
                        <?php $c_pet++; ?>
                    <?php elseif ($row1['pet_res'] == 'R' && $c_res == 0 && $row1['sr_no'] == 1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent</b>
                            </td>
                        </tr>
                        <?php $c_res++; ?>
                    <?php elseif ($row1['pet_res'] == 'P' && $c_add_pet == 0 && $row1['sr_no'] > 1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner(Additional Party) <font color="blue">Select All</font>
                                    <input type="checkbox" name="all" id="all_P" onchange="CheckedAll_P()" /></b>
                            </td>
                        </tr>
                        <?php $c_add_pet++; ?>
                    <?php elseif ($row1['pet_res'] == 'R' && $c_add_res == 0 && $row1['sr_no'] > 1): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent(Additional Party) <font color="blue">Select All</font>
                                    <input type="checkbox" name="all" id="all_R" onchange="CheckedAll_R()" /></b>
                            </td>
                        </tr>
                        <?php $c_add_res++; ?>
                    <?php elseif ($row1['pet_res'] == 'P' && $c_add_pet_add == 0 && $row1['sr_no'] == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Petitioner(Additional Party)(Extra)</b>
                            </td>
                        </tr>
                        <?php $c_add_pet_add++; ?>
                    <?php elseif ($row1['pet_res'] == 'R' && $c_add_res_add == 0 && $row1['sr_no'] == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Respondent(Additional Party)(Extra)</b>
                            </td>
                        </tr>
                        <?php $c_add_res_add++; ?>
                    <?php elseif ($row1['pet_res'] == '' && $c_other == 0 && $row1['sr_no'] == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                <b>Other</b>
                            </td>
                        </tr>
                        <?php $c_other++; ?>
                    <?php endif; ?>
                    <tr>
                        <?php
                        $add_class = '';
                        if (($sno != '1') && ($sno != '0') && ($row1['pet_res'] == 'R')) {
                            $add_class = $row1['pet_res'];
                        } elseif (($sno != '1') && ($sno != '0') && ($row1['pet_res'] == 'P')) {
                            $add_class = $row1['pet_res'];
                        }
                        ?>

                        <td>
                            <input type="checkbox"
                                name="chk_id<?= $sno; ?>"
                                id="chk_id<?= $sno; ?>"
                                style="background-color: black"
                                <?php if ($ck_en_nt == '1') { ?> checked="checked" <?php } ?>
                                class="cl_chk_parties <?= !empty($classToAdd) ? $classToAdd : null; ?> <?= !empty($add_class) ? $add_class : null; ?>" />
                            <br />
                            <span style="color: #2b15db"><b><?= $row1['pet_res'] . '-' . $row1['sr_no']; ?></b></span>
                        </td>

                        <td style="width: 23%;" id="td_cell_s<?= $sno; ?>">
                            <!-- Textarea for displaying partyname -->
                             
                            <textarea id="sp_nm<?= $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)">                    <?php                    
                    if ($ck_en_nt == '0') {
                        echo trim($row1['partyname']);

                        if ($row1['sonof'] != '') {
                            if ($row1['sonof'] == 'S')
                                echo " S/o ";
                            else if ($row1['sonof'] == 'D')
                                echo " D/o ";
                            else if ($row1['sonof'] == 'W')
                                echo " W/o ";

                            echo $row1['prfhname'];
                        }
                    } elseif ($ck_en_nt == '1') {
                        echo $ck_en_nt_x['name'];   

                       
                    }
                            ?>
                        </textarea>

                            <!-- Enrollment details if available -->
                            <?php if ($row1['enrol_no'] != '' && $row1['enrol_yr'] != ''): ?>
                                <span id="sp_enroll<?= $sno; ?>">No.</span>
                                <input onfocus="clear_data(this.id)" name="hdinenroll_<?= $sno; ?>" id="hdinenroll_<?= $sno; ?>" maxlength="6" size="1" type="text" value="<?= $row1['enrol_no'] ?>" />
                                <span id="sp_enrollyr<?= $sno; ?>">Yr</span>
                                <input onfocus="clear_data(this.id)" name="hdinenrollyr_<?= $sno; ?>" id="hdinenrollyr_<?= $sno; ?>" onblur="get_eroll_yr(this.id)" maxlength="4" size="1" type="text" value="<?= $row1['enrol_yr'] ?>" />
                            <?php endif; ?>

                            <!-- Hidden inputs -->
                            <input type="hidden" name="hd_sr_no<?= $sno; ?>" id="hd_sr_no<?= $sno; ?>" value="<?= $row1['sr_no'] ?>" />
                            <input type="hidden" name="hd_pet_res<?= $sno; ?>" id="hd_pet_res<?= $sno; ?>" value="<?= $row1['pet_res'] ?>" />
                        </td>
                        <td style="width: 23%;">
                            <!-- Textarea for displaying address -->
                            <textarea id="sp_add<?= $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)">
                    <?php
                    if ($ck_en_nt == '0') {
                        echo trim($row1['addr1'] . ' ' . $row1['addr2']);
                    } elseif ($ck_en_nt == '1') {
                        echo $ck_en_nt_x['address'];
                    }
                        ?>
                    </textarea>
                        </td>
                        <td style="width: 9%;">
                            <div>
                                <!-- Dropdown for selecting state -->
                                <select name="ddlState<?= $sno; ?>" id="ddlState<?= $sno; ?>" onchange="getCity(this.value,this.id,'0')" style="width: 120px" onfocus="clear_data(this.id)">
                                    <option value="">Select</option>
                                    <?php foreach ($get_states as $state):
                                        $key2 = explode('^', $state); ?>
                                        <option value="<?= $key2[0]; ?>" <?= ($key2[0] == $row1['state']) ? 'selected="selected"' : ''; ?>>
                                            <?= $key2[1]; ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1'): ?>
                                        <option value="0" selected="selected">None</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div style="margin-top: 10px">
                                <!-- Dropdown for selecting city -->
                                <select name="ddlCity<?= $sno; ?>" id="ddlCity<?= $sno; ?>" style="width: 100%" onfocus="clear_data(this.id)">
                                    <option value="">Select</option>
                                    <?php 
                                   $get_districts = get_citys($row1['state']);
                                   if(!empty($get_districts))
                                   {
                                    foreach ($get_districts as $district):
                                        $key2 = explode('^', $district); ?>
                                        <option value="<?= $key2[0]; ?>" <?= ($key2[0] == $row1['city']) ? 'selected="selected"' : ''; ?>>
                                            <?= $key2[1]; ?>
                                        </option>
                                    <?php endforeach; 
                                    }?>
                                    <?php if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1'): ?>
                                        <option value="0" selected="selected">None</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </td>
                        <td style="width: 30%;">
                            <div style="text-align: center">
                                <!-- Multiple/Single selection display -->
                                <span id="sp_mul<?= $sno; ?>" style="color: red;font-size: 9px;display: none" onclick="get_mul_si(this.id)" class="sp_c_mul">
                                    <?php if ($ck_en_nt == '0'): ?>
                                        Multiple
                                    <?php elseif ($ck_en_nt == '1'): ?>
                                        <?php if (preg_match('/,/', $ck_en_nt_x['nt_type'])): ?>
                                            Single
                                        <?php else: ?>
                                            Multiple
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <!-- Dropdown for case selection -->
                            <select name="ddl_nt<?= $sno; ?>" id="ddl_nt<?= $sno; ?>" style="width: 100%;"
                                onfocus="clear_data(this.id)"
                                <?php if ($ck_en_nt == '1' && preg_match('/,/', $ck_en_nt_x['nt_type'])): ?> multiple="multiple" <?php endif; ?>
                                onchange="get_wh_p_r(this.value, this.id)">

                                <option value="">Select</option>

                                <?php foreach ($c_case as $k):
                                    $key = explode('^', $k); ?>

                                    <?php if ($ck_en_nt == '0'): ?>
                                        <option value="<?= $key[0]; ?>"><?= $key[1]; ?></option>
                                    <?php elseif ($ck_en_nt == '1'):
                                        $nt_type = explode(',', $ck_en_nt_x['nt_type']); ?>
                                        <option value="<?= $key[0]; ?>"
                                            <?php for ($index = 0; $index < count($nt_type); $index++): ?>
                                            <?php if ($nt_type[$index] == $key[0]): ?> selected="selected" <?php endif; ?>
                                            <?php endfor; ?>>
                                            <?= $key[1]; ?>
                                        </option>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td style="width: 6%;">
                            <!-- Text input for amount -->
                            <input type="text" size="9"
                                name="txtAmount<?= $sno; ?>"
                                id="txtAmount<?= $sno; ?>"
                                onkeypress="return OnlyNumbersTalwana(event, this.id)"
                                <?php if ($ck_en_nt == '1'): ?> value="<?= $ck_en_nt_x['amount']; ?>" <?php endif; ?> />
                        </td>

                    </tr>

                    <tr style="border: 0px; border-color: white; <?= ($ck_en_nt != '1') ? 'display: none;' : ''; ?>" id="tr_del_send_copy<?= $sno; ?>">
                        <td colspan="7" style="border: 0px; border-color: white;">
                            <table style="width: 100%;" class="table c_vertical_align tbl_border">
                                <tr>
                                    <th style="width: 10%;">Delivery Mode</th>
                                    <th style="width: 45%;">Send To / State / District</th>
                                    <th style="width: 45%;">Copy Send To / State / District</th>
                                </tr>
                                <?php
                                $del_modes = '';
                                $del_tw_send_to = '';
                                $del_tw_copysend_to = '';
                                $ex_c_st = '';
                               
                                $tw_o_r_s = $noticesModel->get_tw_o_r_s($ck_en_nt_x);
                                if (!empty($tw_o_r_s)) {
                                    foreach ($tw_o_r_s as $row) {
                                        if ($del_modes == '') {
                                            $del_modes = $row['del_type'];
                                        } else {
                                            $del_modes .= $row['del_type'];
                                        }
                                    }
                                }
                                $tw_send_to = $noticesModel->get_tw_o_r($ck_en_nt_x);
                                if (!empty($tw_send_to)) {
                                    foreach ($tw_send_to as $row4) {
                                        $row_data = $row4['del_type'] . '~' . $row4['tw_sn_to'] . '~' . $row4['sendto_state'] . '~' . $row4['sendto_district'] . '~' . $row4['send_to_type'];

                                        if ($del_tw_send_to == '') {
                                            $del_tw_send_to = $row_data;
                                        } else {
                                            $del_tw_send_to .= '#' . $row_data;
                                        }
                                    }
                                }
                                $tw_cp_send_to = $noticesModel->get_tw_cp_send_to($ck_en_nt_x);
                                if (!empty($tw_cp_send_to)) {
                                    foreach ($tw_cp_send_to as $row5) {
                                        if ($main_id != $row5['id']) {
                                            $row_data = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];

                                            if ($del_tw_copysend_to == '') {
                                                $del_tw_copysend_to = $row_data;
                                            } else {
                                                $del_tw_copysend_to .= '#' . $row_data;
                                            }
                                            $main_id = $row5['id'];
                                        } else {
                                            $row_data = $row5['del_type'] . '~' . $row5['tw_sn_to'] . '~' . $row5['sendto_state'] . '~' . $row5['sendto_district'] . '~' . $row5['send_to_type'];

                                            if ($ex_c_st == '') {
                                                $ex_c_st = $row_data;
                                            } else {
                                                $ex_c_st .= '#' . $row_data;
                                            }
                                        }
                                    }
                                }
                                for ($q = 0; $q < 2; $q++) {
                                    $del_mode = '';
                                    $id_name = '';
                                    $mode = '';
                                    $sht_nm = '';
                                    $ini_val = 0;

                                    if ($q == 0) {
                                        $del_mode = 'Ordinary';
                                        $id_name = 'chkOrd';
                                        $mode = 'o';
                                        $sht_nm = 'O';
                                    } else if ($q == 1) {
                                        $del_mode = 'Registry';
                                        $id_name = 'chkReg';
                                        $mode = 'r';
                                        $sht_nm = 'R';
                                    } else if ($q == 2) {
                                        $del_mode = 'Humdust';
                                        $id_name = 'chkAdvHum';
                                        $mode = 'h';
                                        $sht_nm = 'H';
                                    } else if ($q == 3) {
                                        $del_mode = 'Speed Post';
                                        $id_name = 'chkAdvReg';
                                        $mode = 'a';
                                        $sht_nm = 'A';
                                    }
                                    $ck_not = '';
                                    if ($del_modes != '') {
                                        for ($index2 = 0; $index2 < strlen($del_modes); $index2++) {
                                            if ($del_modes[$index2] == $sht_nm) {
                                                $ck_not = " checked='checked'";
                                                break; // Exit the loop once we find a match
                                            }
                                        }
                                    }
                                    $tw_send_s = '';
                                    $sendto_state = '';
                                    $sendto_district = '';
                                    $sendto_type = '';
                                    if ($del_tw_send_to != '') {
                                        $ex_del_tw_send_to = explode('#', $del_tw_send_to);
                                        foreach ($ex_del_tw_send_to as $item) {
                                            $ex_in_exp = explode('~', $item);
                                            if (isset($ex_in_exp[0]) && $ex_in_exp[0] == $sht_nm) {
                                                // Check if the required number of elements exist
                                                if (isset($ex_in_exp[1])) {
                                                    $tw_send_s = $ex_in_exp[1];
                                                }
                                                if (isset($ex_in_exp[2])) {
                                                    $sendto_state = $ex_in_exp[2];
                                                }
                                                if (isset($ex_in_exp[3])) {
                                                    $sendto_district = $ex_in_exp[3];
                                                }
                                                if (isset($ex_in_exp[4])) {
                                                    $sendto_type = $ex_in_exp[4];
                                                }
                                            }
                                        }
                                    }
                                    $c_tw_send_s = '';
                                    $c_sendto_state = '';
                                    $c_sendto_district = '';
                                    $c_sendto_type = '';
                                    if ($del_tw_copysend_to != '') {
                                        $ex_del_c_tw_send_to = explode('#', $del_tw_copysend_to);
                                        foreach ($ex_del_c_tw_send_to as $item) {
                                            $ex_in_exp = explode('~', $item);
                                            if (isset($ex_in_exp[0]) && $ex_in_exp[0] == $sht_nm) {
                                                // Check if the required number of elements exist
                                                if (isset($ex_in_exp[1])) {
                                                    $c_tw_send_s = $ex_in_exp[1];
                                                }
                                                if (isset($ex_in_exp[2])) {
                                                    $c_sendto_state = $ex_in_exp[2];
                                                }
                                                if (isset($ex_in_exp[3])) {
                                                    $c_sendto_district = $ex_in_exp[3];
                                                }
                                                if (isset($ex_in_exp[4])) {
                                                    $c_sendto_type = $ex_in_exp[4];
                                                }
                                            }
                                        }
                                    }
                                ?>
                                    <tr style="border: 0px; border-color: white">
                                        <td>
                                            <input class="cl_del_mod<?= $sno; ?>" value="<?= $sht_nm; ?>" title="<?= $del_mode; ?>" type="checkbox"
                                                name="<?= $id_name . $sno; ?>" id="<?= $id_name . $sno; ?>"
                                                onclick="show_hd(this.id)" <?= $ck_en_nt == 1 ? $ck_not : ''; ?> />&nbsp;
                                            <span id="sp_ordinary_ck<?= $sno; ?>"><?= $sht_nm; ?></span>
                                        </td>
                                        <td>
                                            <select name="ddl_send_type<?= $mode . $sno; ?>" id="ddl_send_type<?= $mode . $sno; ?>"
                                                onchange="get_send_to_type(this.id, this.value, '1', '<?= $mode; ?>')">
                                                <option value="">Select</option>
                                                <option value="2" <?= $sendto_type == 2 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Other</option>
                                                <option value="1" <?= $sendto_type == 1 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                <option value="3" <?= $sendto_type == 3 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Court</option>
                                            </select>
                                            <select name="ddlSendTo_<?= $mode . $sno; ?>" id="ddlSendTo_<?= $mode . $sno; ?>"
                                                onfocus="clear_data(this.id)" onchange="get_nms(this.value, this.id)" style="width: 130px">
                                                <option value="">Select</option>
                                                <?php if ($ck_en_nt == '1') {
                                                    $s_to_d = '';
                                                    if ($sendto_type == 2) $s_to_d = $sen_cp_to;
                                                    elseif ($sendto_type == 1) $s_to_d = $get_advocates;
                                                    elseif ($sendto_type == 3) $s_to_d = $get_lc_highcourt;

                                                    foreach ($s_to_d as $k1) {
                                                        $key1 = explode('^', $k1); ?>
                                                        <option value="<?= $key1[0]; ?>" <?= $tw_send_s == $key1[0] ? 'selected="selected"' : ''; ?>><?= $key1[1]; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <select name="ddl_sndto_state_<?= $mode . $sno; ?>" id="ddl_sndto_state_<?= $mode . $sno; ?>"
                                                style="width: 100px" onchange="getCity(this.value, this.id, '1', '<?= $mode; ?>')">
                                                <option value="">Select</option>
                                                <?php foreach ($get_states as $k2) {
                                                    $key2 = explode('^', $k2); ?>
                                                    <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $sendto_state == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <select name="ddl_sndto_dst_<?= $mode . $sno; ?>" id="ddl_sndto_dst_<?= $mode . $sno; ?>" style="width: 100px">
                                                <option value="">Select</option>
                                                <?php if ($sendto_district != '') {
                                                    $get_districts = get_citys($sendto_state);
                                                    foreach ($get_districts as $k2) {
                                                        $key2 = explode('^', $k2); ?>
                                                        <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $sendto_district == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <div>
                                                <select name="ddl_send_copy_type<?= $mode . $sno; ?>" id="ddl_send_copy_type<?= $mode . $sno; ?>"
                                                    onchange="get_send_to_type(this.id, this.value, '2', '<?= $mode; ?>')">
                                                    <option value="">Select</option>
                                                    <option value="2" <?= $c_sendto_type == 2 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Other</option>
                                                    <option value="1" <?= $c_sendto_type == 1 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                </select>
                                                <select name="ddlSendCopyTo_<?= $mode . $sno; ?>" id="ddlSendCopyTo_<?= $mode . $sno; ?>"
                                                    onfocus="clear_data(this.id)" style="width: 130px">
                                                    <option value="">Select</option>
                                                    <?php if ($ck_en_nt == '1') {
                                                        $s_to_d = '';
                                                        if ($c_sendto_type == 2) $s_to_d = $sen_cp_to;
                                                        elseif ($c_sendto_type == 1) $s_to_d = $get_advocates;

                                                        foreach ($s_to_d as $k1) {
                                                            $key1 = explode('^', $k1); ?>
                                                            <option value="<?= $key1[0]; ?>" <?= $c_tw_send_s == $key1[0] ? 'selected="selected"' : ''; ?>><?= $key1[1]; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                                <select name="ddl_cpsndto_state_<?= $mode . $sno; ?>" id="ddl_cpsndto_state_<?= $mode . $sno; ?>"
                                                    style="width: 100px" onchange="getCity(this.value, this.id, '2', '<?= $mode; ?>')">
                                                    <option value="">Select</option>
                                                    <?php foreach ($get_states as $k2) {
                                                        $key2 = explode('^', $k2); ?>
                                                        <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $c_sendto_state == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <select name="ddl_cpsndto_dst_<?= $mode . $sno; ?>" id="ddl_cpsndto_dst_<?= $mode . $sno; ?>" style="width: 100px">
                                                    <option value="">Select</option>
                                                    <?php if ($c_sendto_district != '') {
                                                        $get_districts = get_citys($c_sendto_state);
                                                        foreach ($get_districts as $k2) {
                                                            $key2 = explode('^', $k2); ?>
                                                            <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $c_sendto_district == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                                <div id="dv_ext_cst<?= $mode . $sno; ?>">
                                                    <?php if ($ex_c_st != '') {
                                                        $ex_ex_c_st = explode('#', $ex_c_st);
                                                        for ($index4 = 0; $index4 < count($ex_ex_c_st); $index4++) {
                                                            $ex_in_exp = explode('~', $ex_ex_c_st[$index4]);
                                                            if ($ex_in_exp[0] == $sht_nm) {
                                                                $ini_val++;
                                                                $c_tw_send_s = $ex_in_exp[1];
                                                                $c_sendto_state = $ex_in_exp[2];
                                                                $c_sendto_district = $ex_in_exp[3];
                                                                $c_sendto_type = $ex_in_exp[4]; ?>
                                                                <div style="margin-top: 10px">
                                                                    <select name="ddl_send_copy_type<?= $mode . $sno; ?>_<?= $index4; ?>" id="ddl_send_copy_type<?= $mode . $sno; ?>_<?= $index4; ?>">
                                                                        <option value="">Select</option>
                                                                        <option value="2" <?= $c_sendto_type == 2 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Other</option>
                                                                        <option value="1" <?= $c_sendto_type == 1 && $ck_en_nt == '1' ? 'selected="selected"' : ''; ?>>Advocate</option>
                                                                    </select>
                                                                    <select name="ddlSendCopyTo_<?= $mode . $sno; ?>_<?= $index4; ?>" id="ddlSendCopyTo_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        onfocus="clear_data(this.id)" style="width: 130px;">
                                                                        <option value="">Select</option>
                                                                        <?php if ($ck_en_nt == '1') {
                                                                            $s_to_d = '';
                                                                            if ($c_sendto_type == 2) $s_to_d = $sen_cp_to;
                                                                            elseif ($c_sendto_type == 1) $s_to_d = $get_advocates;
                                                                            foreach ($s_to_d as $k1) {
                                                                                $key1 = explode('^', $k1); ?>
                                                                                <option value="<?= $key1[0]; ?>" <?= $c_tw_send_s == $key1[0] ? 'selected="selected"' : ''; ?>><?= $key1[1]; ?></option>
                                                                        <?php }
                                                                        } ?>
                                                                    </select>
                                                                    <select name="ddl_cpsndto_state_<?= $mode . $sno; ?>_<?= $index4; ?>" id="ddl_cpsndto_state_<?= $mode . $sno; ?>_<?= $index4; ?>"
                                                                        style="width: 100px" onchange="getCity(this.value, this.id, '3', 'r')">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($get_states as $k2) {
                                                                            $key2 = explode('^', $k2); ?>
                                                                            <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $c_sendto_state == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <select name="ddl_cpsndto_dst_<?= $mode . $sno; ?>_<?= $index4; ?>" id="ddl_cpsndto_dst_<?= $mode . $sno; ?>_<?= $index4; ?>" style="width: 100px">
                                                                        <option value="">Select</option>
                                                                        <?php if ($c_sendto_district != '') {
                                                                            $get_districts = get_citys($c_sendto_state);
                                                                            foreach ($get_districts as $k2) {
                                                                                $key2 = explode('^', $k2); ?>
                                                                                <option value="<?= $key2[0]; ?>" <?= $ck_en_nt == '1' && $c_sendto_district == $key2[0] ? 'selected="selected"' : ''; ?>><?= $key2[1]; ?></option>
                                                                        <?php }
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                    <?php }
                                                        }
                                                    } ?>
                                                </div>
                                                <div style="text-align: center" id="dvad_<?= $mode . '_' . $sno; ?>" class="cl_add_cst">Add</div>
                                                <input type="hidden" name="hd_Sendcopyto_<?= $mode . $sno; ?>" id="hd_Sendcopyto_<?= $mode . $sno; ?>" value="<?= $ini_val; ?>" />
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                            <input type="hidden" name="hd_new_upd<?= $sno; ?>" id="hd_new_upd<?= $sno; ?>" value="<?= $ck_en_nt; ?>" />
                            <input type="hidden" name="hd_mn_id<?= $sno; ?>" id="hd_mn_id<?= $sno; ?>"
                                value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt == '1' ? $ck_en_nt_x['id'] : ''); ?>" />

                            <?php if ($ck_en_nt == '1' && $ct_tt == 0): ?>
                                <?php $ct_tt = 1; ?>
                                <input type="hidden" name="hd_jud1" id="hd_jud1"
                                    value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt == '1' ? $ck_en_nt_x['jud1'] : ''); ?>" />
                                <input type="hidden" name="hd_jud2" id="hd_jud2"
                                    value="<?= ($ck_en_nt == '0') ? '' : ($ck_en_nt == '1' ? $ck_en_nt_x['jud2'] : ''); ?>" />
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php
                    $sno++;
                } ?>
                <tr>
                    <td colspan="7" style="text-align: center">
                        <input type="button" name="btn_sp_aex" id="btn_sp_aex" class="sp_aex"
                            onclick="appendRow('tb_ap_ck')" value="Add Extra Party" /> &nbsp;&nbsp;
                        <input type="button" name="btn_sp_aex_hc" id="btn_sp_aex_hc" class="sp_aex_hc"
                            onclick="appendRow_hc('tb_ap_ck')" value="High Court" />
                    </td>
                </tr>

            </table>
            <input type="hidden" name="hd_tot rrrrrr" id="hd_tot" value="<?= $sno; ?>" />

            <div style="text-align: center; margin-top: 10px;">
                <input type="button" name="btnSubmit" id="btnSubmit" value="Submit"
                    onclick="get_save_dt()" class="bb_sub_m" />
                <!-- <input type="button" name="btnDummy" id="btnDummy" value="Dummy" onclick="dummy()" /> -->
            </div>

        </div>
    <?php
    }
    ?>
    <input type="hidden" name="hd_c_stat" id="hd_c_stat" value="<?php echo $row['c_status'] ?>" />
<?php
} else {
?>
    <div style="text-align: center;margin-top: 10px"><b>No Record Found</b></div>
<?php
}
?>