<section class="content ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?php
                        // $diary_no = 0;
                        // if ($chk_status == 1) {
                        //     $diary_no = get_diary_case_type_notice($ct, $cn, $cy);
                        //     $diaryNumberForSearch = $this->Dropdown_list_model->get_case_details_by_case_no($ct, $cn, $cy);
                        // } else {
                        //     $diary_no = $d_no . $d_yr;
                        // }
                        // pr($diary_no);
                        navigate_diary($diary_no);
                        $sessionUserId = isset($_SESSION['dcmis_user_idd']) ? $_SESSION['dcmis_user_idd'] : $_SESSION['login']['usercode'];

                        $db = \Config\Database::connect();
                        $builder = $db->table('main');
                        $builder->select('dacode, c_status');
                        $builder->where('diary_no', $diary_no);
                        $daResult = $builder->get()->getRowArray();

                        if ($daResult) {
                            $dacode = $daResult['dacode'];
                            $status = $daResult['c_status'];
                            if ($sessionUserId != $dacode && $sessionUserId != 663 && $sessionUserId != 1) {
                                $sectionBuilder = $db->table('master.users');
                                $sectionBuilder->select('section');
                                $sectionBuilder->where('usercode', $sessionUserId);
                                $sectionResult = $sectionBuilder->get()->getRowArray();
                                if ($sectionResult) {
                                    $usersection = $sectionResult['section'];
                                    if (
                                        !($usersection == 76 && $status == 'D') &&
                                        $usersection != 77 &&
                                        !($usersection == 46 && $status == 'D') &&
                                        !($usersection == 47 && $status == 'D')
                                    ) {
                                        echo '<p align=center><font color=red>Only DA can generate Notice</font></p>';
                                        exit();
                                    }
                                }
                            }
                        }

                        ?>
                        <input type="hidden" name="hd_diary_no" id="hd_diary_no" value="<?php echo  $diary_no; ?>" />
                        <?php
                        $chk_dts = '0';
                        $date = date('Y-m-d');
                        $ck_pf_nt = '';
                        $ck_pfnt = '';
                        $res_cont = 0;
                        $row = getAdvisors($diary_no);
                        if (count($row) > 0) {
                        ?>
                            <input type="hidden" name="hd_n_status" id="hd_n_status" value="<?php echo $row['c_status']; ?>" />
                            <input type="hidden" name="hd_casetype_id" id="hd_casetype_id" value="<?php echo $row['casetype_id']; ?>" />
                            <?php
                            $res_sq_fi_sub = getTalDelData($diary_no, $date);
                            $res_cont = !empty($res_sq_fi_sub) ? count($res_sq_fi_sub) : 0;
                            ?>
                            <div class="cl_center" style="text-align: center;">
                                <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="1" <?php if (!empty($res_sq_fi_sub) && $res_sq_fi_sub['individual_multiple'] == '1') { ?> checked="checked" <?php } ?> /><b>Individual</b>
                                <input type="radio" name="ddl_ind_mul" id='ddl_ind_mul' class="cl_ind_mul" value="2" <?php if (!empty($res_sq_fi_sub) && $res_sq_fi_sub['individual_multiple'] == '2') { ?> checked="checked" <?php } ?> /><b>Multiple</b>
                            </div>
                            <?php

                            if ($row['c_status'] == 'P') {
                                $ret_res = get_max_dt($diary_no, '');
                            ?>
                                <?php

                                $ex = !empty($row['lastorder']) ? explode("Ord dt:", $row['lastorder']) : date('d-m-Y');
                                $dmy = is_array($ex) ? explode('-', $ex[1]) : explode('-', $ex);
                                $Y = $dmy[2];
                                $m = $dmy[1];
                                $d = $dmy[0];

                                // list($get_sel_con, $fx_dt, $chk_remark, $or_dt) = getRemarkData($diary_no, $ret_res, $row);
                                $var = getRemarkData($diary_no, $ret_res, $row);
                                $get_sel_con = $var['get_sel_con'];
                                $fx_dt = $var['fx_dt'];
                                $chk_remark = $var['chk_remark'];
                                $or_dt = $var['or_dt'];
                                $res_sql_pf = '';
                                if ($fx_dt == 1) {
                                    $conn_main_cs = 0;
                                    $rowCount = 0;
                                    if (in_array($chk_remark, ['24', '21', '59', '91', '131'])) {
                                        $query = $db->table('case_remarks_multiple')
                                            ->select("TO_CHAR(TO_DATE(SUBSTRING(head_content, 1, 10), 'DD-MM-YYYY'), 'YYYY-MM-DD') AS head_content")
                                            ->where('diary_no', $diary_no)
                                            ->whereIn('r_head', ['24', '21', '59', '91', '131'])
                                            ->where('cl_date', $ret_res)
                                            ->get();
                                        $rowCount = $query->getNumRows();
                                    } elseif (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68', '113'])) {
                                        $query = $db->table('heardt')
                                            ->select('tentative_cl_dt')
                                            ->where('diary_no', $diary_no)
                                            ->get();
                                        $rowCount = $query->getNumRows();
                                    }
                                    if ($rowCount > 0) {
                                        $check_rec_pre = 1;
                                        $res_sq_pf = $query->getRow()->tentative_cl_dt ?? null; // Change based on the query context
                                        if ($res_sq_pf) {
                                            $res_sql_pf = date('d-m-Y', strtotime($res_sq_pf));
                                            $ck_pf_nt = 0;
                                            $ck_pfnt = 1;
                                        }
                                    }
                                } else if ($fx_dt == 0 && (isset($res_sql_bnnn) && $res_sql_bnnn > 0)) {
                                    $conn_main_cs = 0;
                                    $check_rec_pre = '0';
                                    $res_sql_pf = '';

                                    // Query to select entry date
                                    $sql_pf = $db->table('docdetails')
                                        ->select("TO_CHAR(ent_dt, 'YYYY-MM-DD') AS dt")
                                        ->where('display', 'Y')
                                        ->where('diary_no', $diary_no)
                                        ->whereIn('doccode', ['7', '29'])
                                        ->where('doccode1', '0')
                                        ->where("TO_CHAR(ent_dt, 'YYYY-MM-DD') >= '$or_dt'")
                                        ->orderBy('docyear', 'DESC')
                                        ->orderBy('ent_dt', 'ASC')
                                        ->limit(1)
                                        ->get();

                                    if ($sql_pf->getNumRows() > 0) {
                                        // Get next_dt and mainhead from heardt
                                        $sq_con_not = $db->table('heardt')
                                            ->select('next_dt, mainhead')
                                            ->where('diary_no', $diary_no)
                                            ->get()
                                            ->getRowArray();

                                        if ($sq_con_not) {
                                            $next_dt = $sq_con_not['next_dt'];
                                            $r_bx = $db->table('main a')
                                                ->select('a.*')
                                                ->join('heardt b', 'a.diary_no = b.diary_no')
                                                ->where('a.conn_key', function ($query) use ($diary_no) {
                                                    $query->select('conn_key')->from('main')->where('diary_no', $diary_no);
                                                })
                                                ->where('a.conn_key IS NOT NULL')
                                                ->where('a.conn_key !=', '')
                                                ->where('next_dt', $next_dt)
                                                ->get();

                                            if ($r_bx->getNumRows() > 0) {
                                                $conn_main_cs = 0;
                                                foreach ($r_bx->getResultArray() as $row2) {
                                                    if ($row2['diary_no'] != $row2['conn_key']) {
                                                        $conn_main_cs = ($diary_no != $row2['conn_key']) ? $row2['conn_key'] : 0;

                                                        // Check remarks
                                                        if (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68'])) {
                                                            $sqy_pf = $db->table('heardt')
                                                                ->select('tentative_cl_dt')
                                                                ->where('heardt', $conn_main_cs)
                                                                ->get();
                                                        } else {
                                                            $sqy_pf = $db->table('case_remarks_multiple')
                                                                ->select("TO_CHAR(TO_DATE(SUBSTRING(head_content, 1, 10), 'DD-MM-YYYY'), 'YYYY-MM-DD') AS head_content")
                                                                ->where('diary_no', $conn_main_cs)
                                                                ->whereIn('r_head', ['24', '21', '59', '91', '131'])
                                                                ->where('cl_date', $ret_res)
                                                                ->get();
                                                        }

                                                        if ($sqy_pf->getNumRows() > 0) {
                                                            $check_rec_pre = 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                            } else {
                                                $conn_main_cs = 0;
                                                if (in_array($chk_remark, ['23', '8', '12', '20', '53', '54', '68'])) {
                                                    $sqy_pf = $db->table('heardt')
                                                        ->select('tentative_cl_dt')
                                                        ->where('diary_no', $diary_no)
                                                        ->get();
                                                } else {
                                                    $sqy_pf = $db->table('case_remarks_multiple')
                                                        ->select("TO_CHAR(TO_DATE(SUBSTRING(head_content, 1, 10), 'DD-MM-YYYY'), 'YYYY-MM-DD') AS head_content")
                                                        ->where('diary_no', $diary_no)
                                                        ->whereIn('r_head', ['24', '21', '59', '91', '131'])
                                                        ->where('cl_date', $ret_res)
                                                        ->get();
                                                }

                                                if ($sqy_pf->getNumRows() > 0) {
                                                    $check_rec_pre = 1;
                                                }
                                            }

                                            if ($check_rec_pre == 1) {
                                                $res_sql_pf = date('d-m-Y', strtotime($sqy_pf->getRow()->head_content ?? ''));
                                                $ck_pf_nt = 0;
                                                $ck_pfnt = 1;
                                            } else {
                                                $res_sql_pf = $sql_pf->getRow()->dt;
                                                $ff_dt = date('d-m-Y', strtotime($res_sql_pf . '+ 45 days'));
                                                $ck_pf_nt = 1;
                                                $ck_pfnt = 0;
                                                $get_sel_con = 2;
                                            }
                                        }

                                        echo '<input type="hidden" name="hd_hd_mn_con" id="hd_hd_mn_con" value="' . $conn_main_cs . '"/>';
                                    } else {
                                        $ff_dt = date('d-m-Y', strtotime($or_dt . '+ 45 days'));
                                        $ck_pf_nt = 1;
                                        $ck_pfnt = 0;
                                    }
                                } else {
                                    $get_sel_con = 3;

                                    if ($or_dt == '--') {
                                        $sqy_pf_no_r = $db->table('heardt')
                                            ->select('tentative_cl_dt')
                                            ->where('diary_no', $diary_no)
                                            ->get();

                                        $ff_dt = $sqy_pf_no_r->getRow()->tentative_cl_dt ?? null; // Get the first row or null
                                    } else {
                                        $ff_dt = date('d-m-Y', strtotime($or_dt . '+ 45 days'));
                                    }

                                    $ck_pf_nt = 1;
                                    $ck_pfnt = 1;

                                    if ($ff_dt == '0000-00-00') {
                                        $chk_dts = '1';
                                    }
                                }
                                ?>
                                <input type="hidden" name="hd_ck_pf_nt" id="hd_ck_pf_nt" value="<?php echo $ck_pfnt; ?>" />
                                <input type="hidden" name="hd_order_date" id="hd_order_date" value="<?php echo $or_dt; ?>" />
                                <?php
                                if ($ck_pf_nt == 1) {
                                    $cu_ff_dt = chksDate(date('Y-m-d',  strtotime($ff_dt)));
                                    $cu_ff_dt = date('d-m-Y',  strtotime($cu_ff_dt));
                                    $todays_dt = date('d-m-Y');
                                    if (strtotime($cu_ff_dt) < strtotime($todays_dt)) {
                                        $cu_ff_dt = date('d-m-Y',  strtotime(date('d-m-Y') . '+ 45 days'));
                                        $cu_ff_dt = chksDate(date('Y-m-d',  strtotime($cu_ff_dt)));
                                        $cu_ff_dt = date('d-m-Y',  strtotime($cu_ff_dt));
                                    }
                                    if ($get_sel_con == 2) {
                                        $cu_ff_dt = date('Y-m-d',  strtotime($cu_ff_dt));
                                        $cu_ff_dt = get_next_working_date_new($cu_ff_dt, '', $res_sq_con_not['mainhead']);
                                        $cu_ff_dt = date('d-m-Y',  strtotime($cu_ff_dt));
                                    }
                                } else {
                                    $cu_ff_dt = $res_sql_pf;
                                }
                                ?>
                                <div style="text-align: left;color: red">
                                    <h4 style="padding: 0px;margin: 0px">If date is not proper in Fixed For please check reader remark is correct or not or contact in server room.</h4>
                                </div>
                                <div width="100%" style="text-align: center;padding-top: 20px">
                                    <b>Order Date</b> <?php echo date('d-m-Y', strtotime($or_dt)); ?>
                                    <?php
                                    if (get_display_status_with_date_differnces($cu_ff_dt) == 'T') {
                                    ?>
                                        <b> Fixed For</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="text" name="txtFFX" id="txtFFX" value="<?php if ($res_cont <= 0) { echo $cu_ff_dt; } else { echo date('d-m-Y', strtotime($res_sq_fi_sub['fixed_for'])); } ?>" class="dtp" maxlength="10" size="10" onfocus="clear_data(this.id)" style="background-color: white;color: black" />
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php } ?>
                                    <b>Subject</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" name="txtSub_nm" id="txtSub_nm" style="width: 30%;" value="<?php if ($res_cont > 0) { echo $res_sq_fi_sub['sub_tal']; }  ?>" />
                                </div>
                                <div style="height: 111px;width: 100%;overflow-x:hidden;overflow-y:scroll;overflow: -moz-scrollbars-vertical;margin-top: 10px">
                                    <div class="fl_prj" style="width: 50%;float: left;">
                                        <fieldset class="border p-2">
                                            <legend class="w-auto"><b>Petitioner Details</b></legend>
                                            <table width="100%">
                                                <tr>
                                                    <th style="text-align: left">
                                                        Name
                                                    </th>
                                                    <td>
                                                        <?php echo $row['pet_name']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left">
                                                        Advocate Name
                                                    </th>
                                                    <td>
                                                        <?php
                                                        echo $row['name'];
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                    <div class="fl_prj" style="width: 50%;float: left;">
                                    <fieldset class="border p-2">
                                        <legend class="w-auto"><b>Respondent Details</b></legend>
                                            <table width="100%">
                                                <tr>
                                                    <th style="text-align: left">
                                                        Name
                                                    </th>
                                                    <td>
                                                        <?php echo $row['res_name']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left">
                                                        Advocate Name
                                                    </th>
                                                    <td>
                                                        <?php
                                                        echo $row['res_adv_nm'];
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                    <?php
                                    ?>
                                </div>
                                <?php
                                $builder = $db->table('tw_tal_del');
                                $builder->selectCount('id');
                                $builder->where('diary_no', $diary_no);
                                $builder->where('rec_dt', $date);
                                $builder->where('display', 'Y');
                                $builder->where('print', 0);

                                $query = $builder->get();
                                $res_cont_ck = $query->getRow()->id;

                                $ck_mul_re_st = $res_cont_ck > 0 ? '1' : '0';
                                ?>
                                <input type="hidden" name="hd_ck_mul_re_st" id="hd_ck_mul_re_st" value="<?php echo $ck_mul_re_st; ?>" />
                                <?php
                                $notice = [];
                                $send_too = [];
                                $state = [];
                                $res_section = '';
                                $nature = '';
                                // Fetch nature and skey from casetype table
                                $builder = $db->table('master.casetype');
                                $builder->select('nature, skey');
                                $builder->where('casecode', $row['casetype_id']);
                                $query = $builder->get();
                                $res_ca_nt = $query->getRow();

                                if ($res_ca_nt) {
                                    $nature = $res_ca_nt->nature;
                                    $skey = $res_ca_nt->skey;
                                    $builder = $db->table('master.tw_section');
                                    $builder->select('id');
                                    $builder->where('name', $skey);
                                    $query = $builder->get();
                                    $section = $query->getRow();
                                    $res_section = !empty($section) ? $section->id : '';
                                }
                                ?>
                                <input type="hidden" name="hd_hd_sec_id" id="hd_hd_sec_id" value="<?php echo $res_section; ?>" />
                                <input type="hidden" name="hd_hd_res_ca_nt" id="hd_hd_res_ca_nt" value="<?php echo $nature; ?>" />
                                <?php
                                $c_case = getNotice($nature, $res_section, $row['c_status'], $row['casetype_id']);
                                $sen_cp_to = send_to();
                                $get_states = getState();
                                ?>

                                <div style="margin-top: 10px;">
                                    <table width="100%" id="tb_ap_ck" class="c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
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
                                        <?php
                                        $sql_party =  getParties($diary_no, $date);


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
                                            // $ck_en_nt_x = null;

                                            if($row1['sr_no'] != '0') {                                 
                                                $builder = $db->table('tw_tal_del');
                                                $builder->select(['id', 'name', 'address', 'nt_type', 'amount'])
                                                    ->where('diary_no', $diary_no)
                                                    ->where('sr_no', $row1['sr_no']) 
                                                    ->where('pet_res', $row1['pet_res'])
                                                    ->where('rec_dt', $date)
                                                    ->where('display', 'Y')
                                                    ->where('print', 0);
                                                $query = $builder->get();

                                                if ($query->getNumRows() > 0) {
                                                    $ck_en_nt = '1';
                                                    $ck_en_nt_x = $query->getRowArray(); // Fetch the first row as an associative array
                                                }
                                            } else {
                                                $ck_en_nt_x = [];
                                            }
                                            if ($row1['sr_no'] == '0') {
                                                $ck_en_nt = '1';
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['name'] == '') {
                                                $ck_en_nt_x['name'] = $row1['partyname'];
                                            }

                                            if (isset($ck_en_nt_x['address']) && $ck_en_nt_x['address'] == '') {
                                                $ck_en_nt_x['address'] = $row1['addr1'];
                                            }
                                            if (isset($ck_en_nt_x['nt_type']) && $ck_en_nt_x['nt_type'] == '') {
                                                $ck_en_nt_x['nt_type'] = $row1['nt_type'];
                                            }
                                            if (isset($ck_en_nt_x['del_type']) && $ck_en_nt_x['del_type'] == '') {
                                                $ck_en_nt_x['del_type'] = $row1['del_type'];
                                            }
                                            if (isset($ck_en_nt_x['send_to']) && $ck_en_nt_x['send_to'] == '') {
                                                $ck_en_nt_x['send_to'] = $row1['send_to'];
                                            }
                                            if (isset($ck_en_nt_x['cp_sn_to']) && $ck_en_nt_x['cp_sn_to'] == '') {
                                                $ck_en_nt_x['cp_sn_to'] = $row1['cp_sn_to'];
                                            }

                                            if (isset($ck_en_nt_x['id']) && $ck_en_nt_x['id'] == '') {
                                                $ck_en_nt_x['id'] = $row1['id'];
                                            }

                                            if (isset($ck_en_nt_x['jud1']) && $ck_en_nt_x['jud1'] == '') {
                                                $ck_en_nt_x['jud1'] = $row1['jud1'];
                                            }
                                            if (isset($ck_en_nt_x['jud2']) && $ck_en_nt_x['jud2'] == '') {
                                                $ck_en_nt_x['jud2'] = $row1['jud2'];
                                            }
                                            if (isset($ck_en_nt_x['note']) && $ck_en_nt_x['note'] == '') {
                                                $ck_en_nt_x['note'] = $row1['note'];
                                            }
                                            if (isset($ck_en_nt_x['amount']) && $ck_en_nt_x['amount'] == '') {
                                                $ck_en_nt_x['amount'] = $row1['amount'];
                                            }
                                            if ($row1['pet_res'] == 'P' && $c_pet == 0 && $row1['sr_no'] == 1) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_pet++;
                                            } else if ($row1['pet_res'] == 'R' && $c_res == 0 && $row1['sr_no'] == 1) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_res++;
                                            } else if ($row1['pet_res'] == 'P' && $c_add_pet == 0 && $row1['sr_no'] > 1) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_p' onclick="CheckedAll_P()" /></b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_pet++;
                                            } else if ($row1['pet_res'] == 'R' && $c_add_res == 0 && $row1['sr_no'] > 1) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_r' onclick="CheckedAll_R()" /></b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_res++;
                                            } else if ($row1['pet_res'] == 'P' && $c_add_pet_add == 0 && $row1['sr_no'] == 0) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner(Additional Party)(Extra)</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_pet_add++;
                                            } else if ($row1['pet_res'] == 'R' && $c_add_res_add == 0 && $row1['sr_no'] == 0) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent(Additional Party)(Extra)</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_res_add++;
                                            } else if ($row1['pet_res'] == '' && $c_other == 0 && $row1['sr_no'] == 0) {
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
                                                }
                                                ?>
                                                <td>
                                                    <input type="checkbox" name="chk_id<?php echo $sno; ?>" id="chk_id<?php echo $sno; ?>" style="background-color:  black" <?php if ($ck_en_nt == '1') {  ?> checked="checked" <?php } ?> class="cl_chk_parties <?= !empty($classToAdd) ? $classToAdd : null; ?> <?= !empty($add_class) ? $add_class : null; ?> " />
                                                    <br />
                                                    <span style="color: #2b15db"><b id="sp_pet_res_id<?php echo $sno; ?>"><?php echo $row1['pet_res'] . '-' . $row1['sr_no']; ?></b></span>
                                                </td>
                                                <td style="width: 23%;" id="td_cell_s<?php echo $sno; ?>">
                                                    <textarea id="sp_nm<?php echo $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php if ($ck_en_nt == '0') { echo trim($row1['partyname']); if ($row1['sonof'] != '') { if ($row1['sonof'] == 'S') echo " S/o "; else if ($row1['sonof'] == 'D') echo " D/o "; else if ($row1['sonof'] == 'W') echo " W/o "; else echo ""; echo $row1['prfhname']; } } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['name']; $get_advocates =  get_advocates($diary_no); $get_lc_highcourt = get_lc_highcourt($diary_no); } ?></textarea>
                                                    <?php
                                                    if ($row1['enrol_no'] != '' && $row1['enrol_yr'] != '') {
                                                    ?>
                                                        <span id="sp_enroll<?php echo $sno; ?>">No.</span>
                                                        <input onfocus="clear_data(this.id)" name="hdinenroll_<?php echo $sno; ?>" id="hdinenroll_<?php echo $sno; ?>" maxlength="6" size="1" type="text" value="<?php echo $row1['enrol_no'] ?>" />
                                                        <span id="sp_enrollyr<?php echo $sno; ?>">Yr</span>
                                                        <input onfocus="clear_data(this.id)" name="hdinenrollyr_<?php echo $sno; ?>" id="hdinenrollyr_<?php echo $sno; ?>" onblur="get_eroll_yr(this.id)" maxlength="4" size="1" type="text" value="<?php echo $row1['enrol_yr'] ?>" />
                                                    <?php } ?>
                                                    <input type="hidden" name="hd_sr_no<?php echo $sno; ?>" id="hd_sr_no<?php echo $sno; ?>" value="<?php echo $row1['sr_no'] ?>" />
                                                    <input type="hidden" name="hd_pet_res<?php echo $sno; ?>" id="hd_pet_res<?php echo $sno; ?>" value="<?php echo $row1['pet_res'] ?>" />
                                                </td>
                                                <td style="width: 23%;">
                                                    <textarea id="sp_add<?php echo $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php if ($ck_en_nt == '0') { echo trim($row1['addr1'] . ' ' . $row1['addr2']); } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['address']; } ?></textarea>
                                                </td>
                                                <td style="width: 9%;">
                                                    <div>
                                                        <select name="ddlState<?php echo $sno; ?>" id="ddlState<?php echo $sno; ?>" onchange="getCity(this.value,this.id,'0')" style="width: 120px" onfocus="clear_data(this.id)">
                                                            <option value="">Select</option>
                                                            <?php

                                                            foreach ($get_states as $k2) {
                                                                $key2 =  explode('^', $k2);
                                                                if (preg_match('/[0-9]/', $row1['state']) && $row1['state'] != NULL && $row1['state'] != '') {
                                                            ?>
                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($key2[0] == $row1['state']) { ?> selected="selected" <?php }  ?>><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="<?php echo $key2[0]; ?>"><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                }
                                                            }
                                                            if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1') {
                                                                ?>
                                                                <option value="0" selected="selected">None</option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div style="margin-top: 10px">
                                                        <select name="ddlCity<?php echo $sno; ?>" id="ddlCity<?php echo $sno; ?>" style="width: 100%" onfocus="clear_data(this.id)">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (preg_match('/[0-9]/', $row1['state'])) {
                                                                $query_city =  getCityById($row1['state']);
                                                                foreach ($query_city as $row_c) {
                                                            ?>
                                                                    <option value="<?php echo $row_c['district_code']; ?>" <?php if ($row_c['district_code'] == $row1['city']) { ?> selected="selected" <?php } ?>><?php echo $row_c['name']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <option value="0" <?php if ($row1['city'] == 0) { ?> selected="selected" <?php } ?>></option>
                                                                <?php
                                                            } else {
                                                                foreach ($get_districts as $k2) {
                                                                    $key2 =  explode('^', $k2);
                                                                ?>
                                                                    <option value="<?php echo $key2[0]; ?>"><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                }
                                                            }
                                                            if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1') {
                                                                ?>
                                                                <option value="0" selected="selected">None</option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td style="width: 30%;">
                                                    <div style="text-align: center">
                                                        <span id="sp_mul<?php echo $sno; ?>" style="color: red;font-size: 9px;display: none"
                                                            onclick="get_mul_si(this.id)" class="sp_c_mul">
                                                            <?php if ($ck_en_nt == '0') { ?>
                                                                Multiple
                                                                <?php } else if ($ck_en_nt == '1') {
                                                                if (preg_match('/,/', $ck_en_nt_x['nt_type'])) {
                                                                ?>
                                                                    Single
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    Multiple
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <select name="ddl_nt<?php echo $sno; ?>" id="ddl_nt<?php echo $sno; ?>" style="width: 100%;" onfocus="clear_data(this.id)" <?php if ($ck_en_nt == '1' && preg_match('/,/', $ck_en_nt_x['nt_type'])) { ?> multiple="multiple" <?php } ?> onchange="get_wh_p_r(this.value,this.id)">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($c_case as $k) {
                                                            $key =  explode('^', $k);
                                                            if ($ck_en_nt == '0') {
                                                        ?>
                                                                <option value="<?php echo $key[0]; ?>"><?php echo $key[1]; ?></option>
                                                            <?php

                                                            } else if ($ck_en_nt == '1') {
                                                                $nt_type =  explode(',', $ck_en_nt_x['nt_type']);
                                                            ?>
                                                                <option value="<?php echo $key[0]; ?>" <?php for ($index = 0; $index < count($nt_type); $index++) { if ($nt_type[$index] == $key[0]) { ?> selected="selected" <?php } } ?>><?php echo $key[1]; ?></option>
                                                                <?php
                                                                if ($key[0] == '269' && $row1['pet_res'] == '') {
                                                                ?>
                                                                    <option value="<?php echo $key[0]; ?>" <?php for ($index = 0; $index < count($nt_type); $index++) { if ($nt_type[$index] == $key[0]) { ?> selected="selected" <?php } } ?>><?php echo $key[1]; ?></option>
                                                            <?php
                                                                }
                                                            }
                                                        }

                                                        ?>

                                                    </select>

                                                </td>
                                                <td style="width: 6%;">
                                                    <input type="text" size="9" name="txtAmount<?php echo $sno; ?>" id="txtAmount<?php echo $sno; ?>" onkeypress="return OnlyNumbersTalwana(event,this.id)" <?php if ($ck_en_nt == '1') { ?> value="<?php echo $ck_en_nt_x['amount'];  ?>" <?php } ?> />
                                                </td>
                                            </tr>
                                            <tr style="border: 0px;border-color: white;<?php if ($ck_en_nt != '1') { ?>display: none; <?php } ?>" id="tr_del_send_copy<?php echo  $sno;  ?>">
                                                <td colspan="7" style="border: 0px;border-color: white;">
                                                    <table style="width: 100%" class="c_vertical_align tbl_border table_tr_th_w_clr">
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
                                                        $main_id = '';

                                                        if(isset($ck_en_nt_x['id']) && !empty($ck_en_nt_x['id'])){
                                                            $delTypeQuery = $db->table('tw_o_r')
                                                                ->select('del_type')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('display', 'Y')
                                                                ->get();
                                                            if ($delTypeQuery->getNumRows() > 0) {
                                                                foreach ($delTypeQuery->getResultArray() as $row) {
                                                                    $del_modes .= ($del_modes ? '' : '') . $row['del_type'];
                                                                }
                                                            }
                                                       
                                                            // Get main send_to data
                                                            $twSendToQuery = $db->table('tw_o_r a')
                                                                ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
                                                                ->join('tw_comp_not b', 'a.id = b.tw_o_r_id')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('a.display', 'Y')
                                                                ->where('b.display', 'Y')
                                                                ->where('copy_type', 0)
                                                                ->get();

                                                            if ($twSendToQuery->getNumRows() > 0) {
                                                                foreach ($twSendToQuery->getResultArray() as $row) {
                                                                    if ($del_tw_send_to == '') {
                                                                        $del_tw_send_to = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                    } else {
                                                                        $del_tw_send_to .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                    }
                                                                }
                                                            }

                                                            // Get copy send_to data
                                                            $twCpSendToQuery = $db->table('tw_o_r a')
                                                                ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
                                                                ->join('tw_comp_not b', 'a.id = b.tw_o_r_id')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('a.display', 'Y')
                                                                ->where('b.display', 'Y')
                                                                ->where('copy_type', 1)
                                                                ->orderBy('id')
                                                                ->orderBy('del_type')
                                                                ->orderBy('copy_type')
                                                                ->get();
                                                            if ($twCpSendToQuery->getNumRows() > 0) {
                                                                $main_id = '';
                                                                foreach ($twCpSendToQuery->getResultArray() as $row) {
                                                                    if ($main_id != $row['id']) {
                                                                        if ($del_tw_copysend_to == '') {
                                                                            $del_tw_copysend_to = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        } else {
                                                                            $del_tw_copysend_to .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        }
                                                                        $main_id = $row['id'];
                                                                    } else {
                                                                        if ($ex_c_st == '') {
                                                                            $ex_c_st = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        } else {
                                                                            $ex_c_st .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        }
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
                                                                    }
                                                                }
                                                            }
                                                            $tw_send_s = '';
                                                            $sendto_state = '';
                                                            $sendto_district = '';
                                                            $sendto_type = '';
                                                            if ($del_tw_send_to != '') {
                                                                $ex_del_tw_send_to = explode('#', $del_tw_send_to);
                                                                for ($index3 = 0; $index3 < count($ex_del_tw_send_to); $index3++) {
                                                                    $ex_in_exp = explode('~', $ex_del_tw_send_to[$index3]);
                                                                    if ($ex_in_exp[0] == $sht_nm) {
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
                                                            if ($del_tw_copysend_to != '') {
                                                                $ex_del_c_tw_send_to = explode('#', $del_tw_copysend_to);
                                                                for ($index4 = 0; $index4 < count($ex_del_c_tw_send_to); $index4++) {
                                                                    $ex_in_exp = explode('~', $ex_del_c_tw_send_to[$index4]);
                                                                    if ($ex_in_exp[0] == $sht_nm) {
                                                                        $c_tw_send_s = $ex_in_exp[1];
                                                                        $c_sendto_state = $ex_in_exp[2];
                                                                        $c_sendto_district = $ex_in_exp[3];
                                                                        $c_sendto_type = $ex_in_exp[4];
                                                                    }
                                                                }
                                                            }

                                                        ?>
                                                            <tr style="border: 0px;border-color: white">
                                                                <td>
                                                                    <input class="cl_del_mod<?php echo $sno; ?>" value="<?php echo $sht_nm; ?>" title="<?php echo $del_mode; ?>" type="checkbox"
                                                                        name="<?php echo $id_name . $sno; ?>" id="<?php echo $id_name . $sno; ?>"
                                                                        onclick="show_hd(this.id)" <?php if ($ck_en_nt == 1) echo $ck_not; ?> />&nbsp;
                                                                    <span id="sp_ordinary_ck<?php echo $sno; ?>"><?php echo $sht_nm; ?></span>
                                                                </td>
                                                                <td>
                                                                    <select name="ddl_send_type<?php echo $mode . $sno; ?>" id="ddl_send_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'1','<?php echo $mode; ?>')">
                                                                        <option value="">Select</option>
                                                                        <option value="2" <?php if ($sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                        <option value="1" <?php if ($sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                        <option value="3" <?php if ($sendto_type == 3 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Court</option>
                                                                    </select>
                                                                    <select name="ddlSendTo_<?php echo $mode . $sno; ?>" id="ddlSendTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" onchange="get_nms(this.value,this.id)" style="width: 130px">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if ($ck_en_nt == '1') {
                                                                            $s_to_d = '';
                                                                            if ($sendto_type == 2)
                                                                                $s_to_d = $sen_cp_to;
                                                                            else  if ($sendto_type == 1)
                                                                                $s_to_d = $get_advocates;
                                                                            else  if ($sendto_type == 3)
                                                                                $s_to_d = $get_lc_highcourt;
                                                                            foreach ($s_to_d as $k1) {
                                                                                $key1 =  explode('^', $k1);
                                                                                if ($ck_en_nt == '0') {
                                                                        ?>
                                                                                    <option value="<?php echo $key1[0]; ?>"><?php echo $key1[1]; ?></option>
                                                                                <?php
                                                                                } else if ($ck_en_nt == '1') {
                                                                                ?>
                                                                                    <option value="<?php echo $key1[0]; ?>" <?php if ($tw_send_s == $key1[0]) { ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <select name="ddl_sndto_state_<?php echo $mode . $sno; ?>" id="ddl_sndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'1','<?php echo $mode; ?>')">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        foreach ($get_states as $k2) {
                                                                            $key2 =  explode('^', $k2);
                                                                        ?>
                                                                            <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <select name="ddl_sndto_dst_<?php echo $mode . $sno; ?>" id="ddl_sndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if ($sendto_district != '') {
                                                                            $get_districts = get_citys($sendto_state);
                                                                            foreach ($get_districts as $k2) {
                                                                                $key2 =  explode('^', $k2);
                                                                        ?>
                                                                                <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <select name="ddl_send_copy_type<?php echo $mode . $sno; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'2','<?php echo $mode; ?>')">
                                                                            <option value="">Select</option>
                                                                            <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                            <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                        </select>
                                                                        <select name="ddlSendCopyTo_<?php echo $mode . $sno; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" style="width: 130px">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            if ($ck_en_nt == '1') {
                                                                                $s_to_d = '';
                                                                                if ($c_sendto_type == 2)
                                                                                    $s_to_d = $sen_cp_to;
                                                                                else  if ($c_sendto_type == 1)
                                                                                    $s_to_d = $get_advocates;
                                                                                foreach ($s_to_d as $k1) {
                                                                                    $key1 =  explode('^', $k1);
                                                                                    if ($ck_en_nt == '0') {
                                                                            ?>
                                                                                        <option value="<?php echo $key1[0]; ?>"><?php echo $key1[1]; ?></option>
                                                                                    <?php
                                                                                    } else if ($ck_en_nt == '1') {
                                                                                    ?>
                                                                                        <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <select name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'2','<?php echo $mode; ?>')">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            foreach ($get_states as $k2) {
                                                                                $key2 =  explode('^', $k2);
                                                                            ?>
                                                                                <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <select name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            if ($c_sendto_district != '') {
                                                                                $get_districts = get_citys($c_sendto_state);
                                                                                foreach ($get_districts as $k2) {
                                                                                    $key2 =  explode('^', $k2);
                                                                            ?>
                                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <div id="dv_ext_cst<?php echo $mode . $sno; ?>">
                                                                            <?php
                                                                            if ($ex_c_st != '') {
                                                                                $ex_ex_c_st = explode('#', $ex_c_st);
                                                                                for ($index4 = 0; $index4 < count($ex_ex_c_st); $index4++) {
                                                                                    $ex_in_exp = explode('~', $ex_ex_c_st[$index4]);
                                                                                    if ($ex_in_exp[0] == $sht_nm) {
                                                                                        $ini_val = $ini_val + 1;
                                                                                        $c_tw_send_s = $ex_in_exp[1];
                                                                                        $c_sendto_state = $ex_in_exp[2];
                                                                                        $c_sendto_district = $ex_in_exp[3];
                                                                                        $c_sendto_type = $ex_in_exp[4];
                                                                            ?>
                                                                                        <div style="margin-top: 10px">
                                                                                            <select name="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>">
                                                                                                <option value="">Select</option>
                                                                                                <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                                                <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                                            </select>
                                                                                            <select name="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" onfocus="clear_data(this.id)" style="width: 130px;">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                if ($ck_en_nt == '1') {
                                                                                                    $s_to_d = '';
                                                                                                    if ($c_sendto_type == 2)
                                                                                                        $s_to_d = $sen_cp_to;
                                                                                                    else  if ($c_sendto_type == 1)
                                                                                                        $s_to_d = $get_advocates;
                                                                                                    else  if ($sendto_type == 3)
                                                                                                        $s_to_d = $get_lc_highcourt;

                                                                                                    foreach ($s_to_d as $k1) {
                                                                                                        $key1 =  explode('^', $k1);
                                                                                                ?>
                                                                                                        <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                            <select name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'3','r')">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                foreach ($get_states as $k2) {
                                                                                                    $key2 =  explode('^', $k2);
                                                                                                ?>
                                                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                            <select name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                if ($c_sendto_district != '') {
                                                                                                    $get_districts = get_citys($c_sendto_state);
                                                                                                    foreach ($get_districts as $k2) {
                                                                                                        $key2 =  explode('^', $k2);
                                                                                                ?>
                                                                                                        <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                            <?php

                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <div style="text-align: center" id="dvad_<?php echo $mode . '_' . $sno; ?>" class="cl_add_cst">Add</div>
                                                                        <input type="hidden" name="hd_Sendcopyto_<?php echo $mode . $sno; ?>" id="hd_Sendcopyto_<?php echo $mode . $sno; ?>" value="<?php echo $ini_val; ?>" />

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>

                                                    </table>
                                                    <input type="hidden" name="hd_new_upd<?php echo $sno; ?>" id="hd_new_upd<?php echo $sno; ?>" value="<?php echo $ck_en_nt; ?>" />
                                                    <input type="hidden" name="hd_mn_id<?php echo $sno; ?>" id="hd_mn_id<?php echo $sno; ?>" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['id']; } ?>" />
                                                    <?php if ($ck_en_nt == '1' && $ct_tt == 0) {
                                                        $ct_tt = 1;
                                                    ?>
                                                        <input type="hidden" name="hd_jud1" id="hd_jud1" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['jud1']; } ?>" />
                                                        <input type="hidden" name="hd_jud2" id="hd_jud2" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['jud2']; } ?>" />
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                            $sno++;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center">
                                                <input type="button" name="btn_sp_aex" id="btn_sp_aex" class="sp_aex" onclick="appendRow('tb_ap_ck')" value="Add Extra Party" />
                                                <input type="button" name="btn_sp_aex_hc" id="btn_sp_aex_hc" class="sp_aex_hc" onclick="appendRow_hc('tb_ap_ck')" value="High Court" />

                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="hd_tot" id="hd_tot" value="<?php echo $sno; ?>" />
                                    <div style="text-align: center;margin-top: 10px;">
                                        <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="get_save_dt()" class="bb_sub_m" />
                                    </div>
                                </div>
                            <?php
                            }   // if case status is pending
                            else {
                            ?>
                                <div style="text-align: center;margin-top: 10px"><b>Case already disposed</b></div>

                                <div style="height: 111px;width: 100%;overflow-x:hidden;overflow-y:scroll;overflow: -moz-scrollbars-vertical;margin-top: 10px">
                                    <div class="fl_prj" style="width: 50%;float: left;">
                                    <fieldset class="border p-2">
                                    <legend class="w-auto"><b>Petitioner Details</b></legend>
                                            <table width="100%">
                                                <tr>
                                                    <th style="text-align: left">
                                                        Name
                                                    </th>
                                                    <td>
                                                        <?php echo $row['pet_name']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left">
                                                        Advocate Name
                                                    </th>
                                                    <td>
                                                        <?php
                                                        echo $row['name'];
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                    <div class="fl_prj" style="width: 50%;float: left;">
                                    <fieldset class="border p-2">
                                        <legend class="w-auto"><b>Respondent Details</b></legend>
                                            <table width="100%">
                                                <tr>
                                                    <th style="text-align: left">
                                                        Name
                                                    </th>
                                                    <td>
                                                        <?php echo $row['res_name']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left">
                                                        Advocate Name
                                                    </th>
                                                    <td>
                                                        <?php
                                                        echo $row['res_adv_nm'];
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                                <?php
                                $builder = $db->table('tw_tal_del');
                                $builder->selectCount('id');
                                $builder->where('diary_no', $diary_no);
                                $builder->where('rec_dt', $date);
                                $builder->where('display', 'Y');
                                $builder->where('print', 0);

                                $query = $builder->get();
                                $res_cont_ck = $query->getRow()->id;

                                $ck_mul_re_st = $res_cont_ck > 0 ? '1' : '0';
                                ?>
                                <input type="hidden" name="hd_ck_mul_re_st" id="hd_ck_mul_re_st" value="<?php echo $ck_mul_re_st; ?>" />
                                <?php
                                $notice = [];
                                $send_too = [];
                                $state = [];
                                $res_section = '';
                                $nature = '';
                                // Fetch nature and skey from casetype table
                                $builder = $db->table('master.casetype');
                                $builder->select('nature, skey');
                                $builder->where('casecode', $row['casetype_id']);
                                $query = $builder->get();
                                $res_ca_nt = $query->getRow();

                                if ($res_ca_nt) {
                                    $nature = $res_ca_nt->nature;
                                    $skey = $res_ca_nt->skey;
                                    $builder = $db->table('master.tw_section');
                                    $builder->select('id');
                                    $builder->where('name', $skey);
                                    $query = $builder->get();
                                    $section = $query->getRow();
                                    $res_section = !empty($section) ? $section->id : '';
                                }
                                ?>
                                <input type="hidden" name="hd_hd_sec_id" id="hd_hd_sec_id" value="<?php echo $res_section; ?>" />
                                <input type="hidden" name="hd_hd_res_ca_nt" id="hd_hd_res_ca_nt" value="<?php echo $nature; ?>" />
                                <?php
                                $c_case = getNotice($nature, $res_section, $row['c_status'], $row['casetype_id']);
                                $sen_cp_to = send_to();
                                $get_states = getState();

                                ?>

                                <div style="margin-top: 10px;">
                                    <table width="100%" id="tb_ap_ck" class="c_vertical_align tbl_border" cellpadding="5" cellspacing="5">
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
                                        <?php

                                        $sql_party =  getParties($diary_no, $date);

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
                                            $ck_en_nt_x = null;

                                            if ($row1['sr_no'] != '0') {
                                                $builder = $db->table('tw_tal_del');
                                                $builder->select(['id', 'name', 'address', 'nt_type', 'amount'])
                                                    ->where('diary_no', $diary_no)
                                                    ->where('sr_no', $row1['sr_no'])
                                                    ->where('pet_res', $row1['pet_res'])
                                                    ->where('rec_dt', $date)
                                                    ->where('display', 'Y')
                                                    ->where('print', 0);
                                                $query = $builder->get();

                                                if ($query->getNumRows() > 0) {
                                                    $ck_en_nt = '1';
                                                    $ck_en_nt_x = $query->getRowArray(); // Fetch the first row as an associative array
                                                }
                                            } else {
                                                $ck_en_nt_x = '';
                                            }
                                            if ($row1['sr_no'] == '0') {
                                                $ck_en_nt = '1';
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['name'] == '') {
                                                $ck_en_nt_x['name'] = $row1['partyname'];
                                            }

                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['address'] == '') {
                                                $ck_en_nt_x['address'] = $row1['addr1'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['nt_type'] == '') {
                                                $ck_en_nt_x['nt_type'] = $row1['nt_type'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['del_type'] == '') {
                                                $ck_en_nt_x['del_type'] = $row1['del_type'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['send_to'] == '') {
                                                $ck_en_nt_x['send_to'] = $row1['send_to'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['cp_sn_to'] == '') {
                                                $ck_en_nt_x['cp_sn_to'] = $row1['cp_sn_to'];
                                            }

                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['id'] == '') {
                                                $ck_en_nt_x['id'] = $row1['id'];
                                            }

                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['jud1'] == '') {
                                                $ck_en_nt_x['jud1'] = $row1['jud1'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['jud2'] == '') {
                                                $ck_en_nt_x['jud2'] = $row1['jud2'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['note'] == '') {
                                                $ck_en_nt_x['note'] = $row1['note'];
                                            }
                                            if (isset($ck_en_nt_x['name']) && $ck_en_nt_x['amount'] == '') {
                                                $ck_en_nt_x['amount'] = $row1['amount'];
                                            }
                                            if ($row1['pet_res'] == 'P' && $c_pet == 0 && $row1['sr_no'] == 1) {
                                        ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_pet++;
                                            } else if ($row1['pet_res'] == 'R' && $c_res == 0 && $row1['sr_no'] == 1) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_res++;
                                            } else if ($row1['pet_res'] == 'P' && $c_add_pet == 0 && $row1['sr_no'] > 1) {
                                                $classToAdd = 'cl_chk_parties_P';
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_P' onchange="CheckedAll_P()" /></b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_pet++;
                                            } else if ($row1['pet_res'] == 'R' && $c_add_res == 0 && $row1['sr_no'] > 1) {
                                                $classToAdd = 'cl_chk_parties_R';
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent(Additional Party) <font color="blue">Select All</font><input type='checkbox' name='all' id='all_R' onchange="CheckedAll_R" /></b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_res++;
                                            } else if ($row1['pet_res'] == 'P' && $c_add_pet_add == 0 && $row1['sr_no'] == 0) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Petitioner(Additional Party)(Extra)</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_pet_add++;
                                            } else if ($row1['pet_res'] == 'R' && $c_add_res_add == 0 && $row1['sr_no'] == 0) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" style="text-align: center">
                                                        <b>Respondent(Additional Party)(Extra)</b>
                                                    </td>
                                                </tr>
                                            <?php
                                                $c_add_res_add++;
                                            } else if ($row1['pet_res'] == '' && $c_other == 0 && $row1['sr_no'] == 0) {
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
                                                }
                                                ?>
                                                <td>
                                                    <input type="checkbox" name="chk_id<?php echo $sno; ?>" id="chk_id<?php echo $sno; ?>" style="background-color:  black" <?php if ($ck_en_nt == '1') {  ?> checked="checked" <?php } ?> class="cl_chk_parties <?= !empty($classToAdd) ? $classToAdd : null; ?> <?= !empty($add_class) ? $add_class : null; ?>" />
                                                    <br />
                                                    <span style="color: #2b15db"><b><?php echo $row1['pet_res'] . '-' . $row1['sr_no']; ?></b></span>
                                                </td>
                                                <td style="width: 23%;" id="td_cell_s<?php echo $sno; ?>">
                                                    <textarea id="sp_nm<?php echo $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php if ($ck_en_nt == '0') { echo trim($row1['partyname']); if ($row1['sonof'] != '') { if ($row1['sonof'] == 'S') echo " S/o "; else if ($row1['sonof'] == 'D') echo " D/o "; else if ($row1['sonof'] == 'W') echo " W/o "; else echo ""; echo $row1['prfhname']; } } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['name']; $get_advocates =  get_advocates($diary_no); } ?></textarea>
                                                    <?php
                                                    if ($row1['enrol_no'] != '' && $row1['enrol_yr'] != '') {
                                                    ?>
                                                        <span id="sp_enroll<?php echo $sno; ?>">No.</span>
                                                        <input onfocus="clear_data(this.id)" name="hdinenroll_<?php echo $sno; ?>" id="hdinenroll_<?php echo $sno; ?>" maxlength="6" size="1" type="text" value="<?php echo $row1['enrol_no'] ?>" />
                                                        <span id="sp_enrollyr<?php echo $sno; ?>">Yr</span>
                                                        <input onfocus="clear_data(this.id)" name="hdinenrollyr_<?php echo $sno; ?>" id="hdinenrollyr_<?php echo $sno; ?>" onblur="get_eroll_yr(this.id)" maxlength="4" size="1" type="text" value="<?php echo $row1['enrol_yr'] ?>" />
                                                    <?php } ?>
                                                    <input type="hidden" name="hd_sr_no<?php echo $sno; ?>" id="hd_sr_no<?php echo $sno; ?>" value="<?php echo $row1['sr_no'] ?>" />
                                                    <input type="hidden" name="hd_pet_res<?php echo $sno; ?>" id="hd_pet_res<?php echo $sno; ?>" value="<?php echo $row1['pet_res'] ?>" />
                                                </td>
                                                <td style="width: 23%;">
                                                    <textarea id="sp_add<?php echo $sno; ?>" style="resize:none;width: 80%" onfocus="clear_data(this.id)"><?php if ($ck_en_nt == '0') { echo trim($row1['addr1'] . ' ' . $row1['addr2']); } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['address']; } ?></textarea>
                                                </td>
                                                <td style="width: 9%;">
                                                    <div>
                                                        <select name="ddlState<?php echo $sno; ?>" id="ddlState<?php echo $sno; ?>" onchange="getCity(this.value,this.id,'0')" style="width: 120px" onfocus="clear_data(this.id)">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($get_states as $k2) {
                                                                $key2 =  explode('^', $k2);
                                                                if (preg_match('/[0-9]/', $row1['state']) && $row1['state'] != NULL && $row1['state'] != '') {
                                                            ?>
                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($key2[0] == $row1['state']) { ?> selected="selected" <?php }  ?>><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="<?php echo $key2[0]; ?>"><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                }
                                                            }
                                                            if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1') {
                                                                ?>
                                                                <option value="0" selected="selected">None</option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div style="margin-top: 10px">
                                                        <select name="ddlCity<?php echo $sno; ?>" id="ddlCity<?php echo $sno; ?>" style="width: 100%" onfocus="clear_data(this.id)">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (preg_match('/[0-9]/', $row1['state'])) {
                                                                $query_city =  getCityById($row1['state']);
                                                                foreach ($query_city as $row_c) {
                                                            ?>
                                                                    <option value="<?php echo $row_c['district_code']; ?>" <?php if ($row_c['district_code'] == $row1['city']) { ?> selected="selected" <?php } ?>><?php echo $row_c['name']; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                <option value="0" <?php if ($row1['city'] == 0) { ?> selected="selected" <?php } ?>></option>
                                                                <?php
                                                            } else {
                                                                foreach ($get_districts as $k2) {
                                                                    $key2 =  explode('^', $k2);
                                                                ?>
                                                                    <option value="<?php echo $key2[0]; ?>"><?php echo $key2[1]; ?></option>
                                                                <?php
                                                                }
                                                            }
                                                            if (($row1['state'] == NULL || $row1['state'] == 0) && $ck_en_nt == '1') {
                                                                ?>
                                                                <option value="0" selected="selected">None</option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td style="width: 30%;">
                                                    <div style="text-align: center">
                                                        <span id="sp_mul<?php echo $sno; ?>" style="color: red;font-size: 9px;display: none"
                                                            onclick="get_mul_si(this.id)" class="sp_c_mul">
                                                            <?php if ($ck_en_nt == '0') { ?>
                                                                Multiple
                                                                <?php } else if ($ck_en_nt == '1') {

                                                                if (preg_match('/,/', $ck_en_nt_x['nt_type'])) {
                                                                ?>
                                                                    Single
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    Multiple
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <select name="ddl_nt<?php echo $sno; ?>" id="ddl_nt<?php echo $sno; ?>" style="width: 100%;" onfocus="clear_data(this.id)" <?php if ($ck_en_nt == '1' && preg_match('/,/', $ck_en_nt_x['nt_type'])) {  ?> multiple="multiple" <?php } ?> onchange="get_wh_p_r(this.value,this.id)">
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($c_case as $k) {
                                                            $key =  explode('^', $k);
                                                            if ($ck_en_nt == '0') {
                                                        ?>
                                                                <option value="<?php echo $key[0]; ?>"><?php echo $key[1]; ?></option>
                                                            <?php
                                                            } else if ($ck_en_nt == '1') {
                                                                $nt_type =  explode(',', $ck_en_nt_x['nt_type']);
                                                            ?>
                                                                <option value="<?php echo $key[0]; ?>" <?php for ($index = 0; $index < count($nt_type); $index++) { if ($nt_type[$index] == $key[0]) { ?> selected="selected" <?php } } ?>><?php echo $key[1]; ?></option>
                                                                <?php
                                                                if ($key[0] == '269' && $row1['pet_res'] == '') {
                                                                ?>
                                                                    <option value="<?php echo $key[0]; ?>" <?php for ($index = 0; $index < count($nt_type); $index++) { if ($nt_type[$index] == $key[0]) { ?> selected="selected" <?php } } ?>><?php echo $key[1]; ?></option>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td style="width: 6%;">
                                                    <input type="text" size="9" name="txtAmount<?php echo $sno; ?>" id="txtAmount<?php echo $sno; ?>" onkeypress="return OnlyNumbersTalwana(event,this.id)" <?php if ($ck_en_nt == '1') { ?> value="<?php echo $ck_en_nt_x['amount'];  ?>" <?php } ?> />
                                                </td>

                                            </tr>
                                            <tr style="border: 0px;border-color: white;<?php if ($ck_en_nt != '1') { ?>display: none;<?php } ?>" id="tr_del_send_copy<?php echo  $sno;  ?>">
                                                <td colspan="7" style="border: 0px;border-color: white">
                                                    <table style="width: 100%" class="c_vertical_align tbl_border">
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
                                                        // Get del_type
                                                        if(isset($ck_en_nt_x['id']) && $ck_en_nt_x['id']){
                                                            $delTypeQuery = $db->table('tw_o_r')
                                                                ->select('del_type')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('display', 'Y')
                                                                ->get();
                                                            if ($delTypeQuery->getNumRows() > 0) {
                                                                foreach ($delTypeQuery->getResultArray() as $row) {
                                                                    $del_modes .= ($del_modes ? '' : '') . $row['del_type'];
                                                                }
                                                            }
                                                        
                                                            // Get send_to data
                                                            $twSendToQuery = $db->table('tw_o_r a')
                                                                ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
                                                                ->join('tw_comp_not b', 'a.id = b.tw_o_r_id')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('a.display', 'Y')
                                                                ->where('b.display', 'Y')
                                                                ->where('copy_type', 0)
                                                                ->get();
                                                            if ($twSendToQuery->getNumRows() > 0) {
                                                                foreach ($twSendToQuery->getResultArray() as $row) {
                                                                    if ($del_tw_send_to == '') {
                                                                        $del_tw_send_to = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                    } else {
                                                                        $del_tw_send_to .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                    }
                                                                }
                                                            }
                                                            // Get copy send_to data
                                                            $twCpSendToQuery = $db->table('tw_o_r a')
                                                                ->select('a.id, del_type, tw_sn_to, sendto_state, sendto_district, copy_type, send_to_type')
                                                                ->join('tw_comp_not b', 'a.id = b.tw_o_r_id')
                                                                ->where('tw_org_id', $ck_en_nt_x['id'])
                                                                ->where('a.display', 'Y')
                                                                ->where('b.display', 'Y')
                                                                ->where('copy_type', 1)
                                                                ->orderBy('id')
                                                                ->orderBy('del_type')
                                                                ->orderBy('copy_type')
                                                                ->get();
                                                            if ($twCpSendToQuery->getNumRows() > 0) {
                                                                $main_id = '';
                                                                foreach ($twCpSendToQuery->getResultArray() as $row) {
                                                                    if ($main_id != $row['id']) {
                                                                        if ($del_tw_copysend_to == '') {
                                                                            $del_tw_copysend_to = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        } else {
                                                                            $del_tw_copysend_to .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        }
                                                                        $main_id = $row['id'];
                                                                    } else {
                                                                        if ($ex_c_st == '') {
                                                                            $ex_c_st = $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        } else {
                                                                            $ex_c_st .= '#' . $row['del_type'] . '~' . $row['tw_sn_to'] . '~' . $row['sendto_state'] . '~' . $row['sendto_district'] . '~' . $row['send_to_type'];
                                                                        }
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
                                                                    }
                                                                }
                                                            }
                                                            $tw_send_s = '';
                                                            $sendto_state = '';
                                                            $sendto_district = '';
                                                            $sendto_type = '';
                                                            if ($del_tw_send_to != '') {
                                                                $ex_del_tw_send_to = explode('#', $del_tw_send_to);
                                                                for ($index3 = 0; $index3 < count($ex_del_tw_send_to); $index3++) {
                                                                    $ex_in_exp = explode('~', $ex_del_tw_send_to[$index3]);
                                                                    if ($ex_in_exp[0] == $sht_nm) {
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
                                                            if ($del_tw_copysend_to != '') {
                                                                $ex_del_c_tw_send_to = explode('#', $del_tw_copysend_to);
                                                                for ($index4 = 0; $index4 < count($ex_del_c_tw_send_to); $index4++) {
                                                                    $ex_in_exp = explode('~', $ex_del_c_tw_send_to[$index4]);
                                                                    if ($ex_in_exp[0] == $sht_nm) {
                                                                        $c_tw_send_s = $ex_in_exp[1];
                                                                        $c_sendto_state = $ex_in_exp[2];
                                                                        $c_sendto_district = $ex_in_exp[3];
                                                                        $c_sendto_type = $ex_in_exp[4];
                                                                    }
                                                                }
                                                            }

                                                        ?>
                                                            <tr style="border: 0px;border-color: white">
                                                                <td>
                                                                    <input class="cl_del_mod<?php echo $sno; ?>" value="<?php echo $sht_nm; ?>" title="<?php echo $del_mode; ?>" type="checkbox"
                                                                        name="<?php echo $id_name . $sno; ?>" id="<?php echo $id_name . $sno; ?>"
                                                                        onclick="show_hd(this.id)" <?php if ($ck_en_nt == 1) echo $ck_not; ?> />&nbsp;
                                                                    <span id="sp_ordinary_ck<?php echo $sno; ?>"><?php echo $sht_nm; ?></span>
                                                                </td>
                                                                <td>
                                                                    <select name="ddl_send_type<?php echo $mode . $sno; ?>" id="ddl_send_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'1','<?php echo $mode; ?>')">
                                                                        <option value="">Select</option>
                                                                        <option value="2" <?php if ($sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                        <option value="1" <?php if ($sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                        <option value="3" <?php if ($sendto_type == 3 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Court</option>
                                                                    </select>
                                                                    <select name="ddlSendTo_<?php echo $mode . $sno; ?>" id="ddlSendTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" onchange="get_nms(this.value,this.id)" style="width: 130px">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if ($ck_en_nt == '1') {
                                                                            $s_to_d = '';
                                                                            if ($sendto_type == 2)
                                                                                $s_to_d = $sen_cp_to;
                                                                            else  if ($sendto_type == 1)
                                                                                $s_to_d = $get_advocates;
                                                                            else  if ($sendto_type == 3)
                                                                                $s_to_d = $get_lc_highcourt;

                                                                            foreach ($s_to_d as $k1) {
                                                                                $key1 =  explode('^', $k1);
                                                                                if ($ck_en_nt == '0') {

                                                                        ?>
                                                                                    <option value="<?php echo $key1[0]; ?>"><?php echo $key1[1]; ?></option>
                                                                                <?php
                                                                                } else if ($ck_en_nt == '1') {
                                                                                ?>
                                                                                    <option value="<?php echo $key1[0]; ?>" <?php if ($tw_send_s == $key1[0]) { ?> selected="selected" <?php } ?>><?php echo $key1[1]; ?></option>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>

                                                                    </select>

                                                                    <select name="ddl_sndto_state_<?php echo $mode . $sno; ?>" id="ddl_sndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'1','<?php echo $mode; ?>')">
                                                                        <option value="">Select</option>
                                                                        <?php

                                                                        foreach ($get_states as $k2) {
                                                                            $key2 =  explode('^', $k2);
                                                                        ?>

                                                                            <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>

                                                                    <select name="ddl_sndto_dst_<?php echo $mode . $sno; ?>" id="ddl_sndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if ($sendto_district != '') {
                                                                            $get_districts = get_citys($sendto_state);
                                                                            foreach ($get_districts as $k2) {
                                                                                $key2 =  explode('^', $k2);
                                                                        ?>
                                                                                <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div>

                                                                        <select name="ddl_send_copy_type<?php echo $mode . $sno; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>" onchange="get_send_to_type(this.id,this.value,'2','<?php echo $mode; ?>')">
                                                                            <option value="">Select</option>
                                                                            <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                            <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                        </select>
                                                                        <select name="ddlSendCopyTo_<?php echo $mode . $sno; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>" onfocus="clear_data(this.id)" style="width: 130px">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            if ($ck_en_nt == '1') {
                                                                                $s_to_d = '';
                                                                                if ($c_sendto_type == 2)
                                                                                    $s_to_d = $sen_cp_to;
                                                                                else  if ($c_sendto_type == 1)
                                                                                    $s_to_d = $get_advocates;
                                                                                foreach ($s_to_d as $k1) {
                                                                                    $key1 =  explode('^', $k1);
                                                                                    if ($ck_en_nt == '0') {
                                                                            ?>
                                                                                        <option value="<?php echo $key1[0]; ?>"><?php echo $key1[1]; ?></option>
                                                                                    <?php
                                                                                    } else if ($ck_en_nt == '1') {
                                                                                    ?>
                                                                                        <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <select name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'2','<?php echo $mode; ?>')">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            foreach ($get_states as $k2) {
                                                                                $key2 =  explode('^', $k2);
                                                                            ?>
                                                                                <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <select name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>" style="width: 100px">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            if ($c_sendto_district != '') {
                                                                                $get_districts = get_citys($c_sendto_state);
                                                                                foreach ($get_districts as $k2) {
                                                                                    $key2 =  explode('^', $k2);
                                                                            ?>
                                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <div id="dv_ext_cst<?php echo $mode . $sno; ?>">
                                                                            <?php
                                                                            if ($ex_c_st != '') {
                                                                                $ex_ex_c_st = explode('#', $ex_c_st);
                                                                                for ($index4 = 0; $index4 < count($ex_ex_c_st); $index4++) {
                                                                                    $ex_in_exp = explode('~', $ex_ex_c_st[$index4]);
                                                                                    if ($ex_in_exp[0] == $sht_nm) {
                                                                                        $ini_val = $ini_val + 1;
                                                                                        $c_tw_send_s = $ex_in_exp[1];
                                                                                        $c_sendto_state = $ex_in_exp[2];
                                                                                        $c_sendto_district = $ex_in_exp[3];
                                                                                        $c_sendto_type = $ex_in_exp[4];
                                                                            ?>
                                                                                        <div style="margin-top: 10px">
                                                                                            <select name="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_send_copy_type<?php echo $mode . $sno; ?>_<?php echo $index4; ?>">
                                                                                                <option value="">Select</option>
                                                                                                <option value="2" <?php if ($c_sendto_type == 2 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Other</option>
                                                                                                <option value="1" <?php if ($c_sendto_type == 1 && $ck_en_nt == '1') { ?> selected="selected" <?php } ?>>Advocate</option>
                                                                                            </select>
                                                                                            <select name="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddlSendCopyTo_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" onfocus="clear_data(this.id)" style="width: 130px;">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                if ($ck_en_nt == '1') {
                                                                                                    $s_to_d = '';
                                                                                                    if ($c_sendto_type == 2)
                                                                                                        $s_to_d = $sen_cp_to;
                                                                                                    else  if ($c_sendto_type == 1)
                                                                                                        $s_to_d = $get_advocates;
                                                                                                    else  if ($sendto_type == 3)
                                                                                                        $s_to_d = $get_lc_highcourt;
                                                                                                    foreach ($s_to_d as $k1) {
                                                                                                        $key1 =  explode('^', $k1);
                                                                                                ?>
                                                                                                        <option value="<?php echo $key1[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_tw_send_s == $key1[0]) { ?> selected="selected" <?php } } ?>><?php echo $key1[1]; ?></option>
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                            <select name="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_state_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px" onchange="getCity(this.value,this.id,'3','r')">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                foreach ($get_states as $k2) {
                                                                                                    $key2 =  explode('^', $k2);
                                                                                                ?>
                                                                                                    <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_state == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                            <select name="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" id="ddl_cpsndto_dst_<?php echo $mode . $sno; ?>_<?php echo $index4; ?>" style="width: 100px">
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                if ($c_sendto_district != '') {
                                                                                                    $get_districts = get_citys($c_sendto_state);
                                                                                                    foreach ($get_districts as $k2) {
                                                                                                        $key2 =  explode('^', $k2);
                                                                                                ?>
                                                                                                        <option value="<?php echo $key2[0]; ?>" <?php if ($ck_en_nt == 1) { if ($c_sendto_district == $key2[0]) { ?> selected="selected" <?php } } ?>><?php echo $key2[1]; ?></option>
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                            <?php

                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <div style="text-align: center" id="dvad_<?php echo $mode . '_' . $sno; ?>" class="cl_add_cst">Add</div>
                                                                        <input type="hidden" name="hd_Sendcopyto_<?php echo $mode . $sno; ?>" id="hd_Sendcopyto_<?php echo $mode . $sno; ?>" value="<?php echo $ini_val; ?>" />
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                    <input type="hidden" name="hd_new_upd<?php echo $sno; ?>" id="hd_new_upd<?php echo $sno; ?>" value="<?php echo $ck_en_nt; ?>" />
                                                    <input type="hidden" name="hd_mn_id<?php echo $sno; ?>" id="hd_mn_id<?php echo $sno; ?>" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['id']; } ?>" />
                                                    <?php if ($ck_en_nt == '1' && $ct_tt == 0) {
                                                        $ct_tt = 1;
                                                    ?>
                                                        <input type="hidden" name="hd_jud1" id="hd_jud1" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['jud1']; } ?>" />
                                                        <input type="hidden" name="hd_jud2" id="hd_jud2" value="<?php if ($ck_en_nt == '0') { echo ''; } else if ($ck_en_nt == '1') { echo $ck_en_nt_x['jud2']; } ?>" />
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                            $sno++;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center">
                                                <input type="button" name="btn_sp_aex" id="btn_sp_aex" class="sp_aex" onclick="appendRow('tb_ap_ck')" value="Add Extra Party" /> &nbsp;&nbsp;
                                                <input type="button" name="btn_sp_aex_hc" id="btn_sp_aex_hc" class="sp_aex_hc" onclick="appendRow_hc('tb_ap_ck')" value="High Court" />

                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="hd_tot" id="hd_tot" value="<?php echo $sno; ?>" />
                                    <div style="text-align: center;margin-top: 10px;">
                                        <input type="button" name="btnSubmit" id="btnSubmit" value="Submit" onclick="get_save_dt()" class="bb_sub_m" />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>