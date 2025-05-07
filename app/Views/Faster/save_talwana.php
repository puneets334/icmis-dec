<?php
$ucode = $_SESSION['login']['usercode'];
$dairy_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
$sq_ck = '';
$year = date('Y');
$date = date('Y-m-d');
$flag = 1;
$_REQUEST['sp_nm'] = !empty($_REQUEST['sp_nm']) ? htmlspecialchars_decode(htmlentities($_REQUEST['sp_nm'])) : '';
$_REQUEST['sp_add'] = !empty($_REQUEST['sp_add']) ? htmlspecialchars_decode(htmlentities($_REQUEST['sp_add'])) : '';
$_REQUEST['txtNote'] = !empty($_REQUEST['txtNote']) ? htmlspecialchars_decode(htmlentities($_REQUEST['txtNote'])) : '';
$_REQUEST['txtSub_nm'] = !empty($_REQUEST['txtSub_nm']) ? htmlspecialchars_decode(htmlentities(($_REQUEST['txtSub_nm']))) : '';
$db = \Config\Database::connect();
$txtFFX = !empty($_REQUEST['txtFFX']) ? date('Y-m-d',  strtotime($_REQUEST['txtFFX'])) : '';
if ($txtFFX == '1970-01-01')
    $txtFFX = '0000-00-00';

// pr($_REQUEST['hd_new_upd']);
if ($_REQUEST['hd_new_upd'] == '0') {

    $year = $_REQUEST['d_yr'];
    $builder = $db->table('master.tw_max_process');
    $process = $builder->select('processid')->where('year', $year)->get()->getRow();
    $res = 1;
    if ($process) {
        $res = $process->processid + 1;

        // Prepare data for insertion
        $data = [
            'name'                  => $_REQUEST['sp_nm'],
            'address'               => $_REQUEST['sp_add'],
            'nt_type'               => $_REQUEST['ddl_val'],
            'process_id'            => $res,
            'diary_no'              => $dairy_no,
            'rec_dt'                => $date,
            'sr_no'                 => $_REQUEST['hd_sr_no'],
            'pet_res'               => $_REQUEST['hd_pet_res'],
            'amount'                => ($_REQUEST['txtAmount']) ? $_REQUEST['txtAmount'] : 0,
            'user_id'               => $ucode,
            'display'               => 'Y',
            'amt_wor'               => $_REQUEST['nm_wd'],
            'fixed_for'             => ($txtFFX) ? $txtFFX : NULL,
            'sub_tal'               => $_REQUEST['txtSub_nm'],
            'tal_state'             => $_REQUEST['ddlState'],
            'tal_district'          => $_REQUEST['ddlCity'],
            'enrol_no'              => $_REQUEST['hdinenroll'],
            'enrol_yr'              => $_REQUEST['hdinenrollyr'],
            'order_dt'              => (isset($_REQUEST['hd_order_date']) && ($_REQUEST['hd_order_date'] != 'undefined')) ? $_REQUEST['hd_order_date'] : NULL,
            'office_notice_rpt'     => (isset($_REQUEST['ddl_not_office']) && ($_REQUEST['ddl_not_office'])) ? $_REQUEST['ddl_not_office'] : '',
            'individual_multiple'   => isset($_REQUEST['individual_multiple']) ? $_REQUEST['individual_multiple'] : NULL,
            'print'                 => 0,
            'notice_path'           => '',
            'create_modify'         => date("Y-m-d H:i:s"),
            'updated_by'            => session()->get('login')['usercode'],
            'updated_by_ip'         => getClientIP()
        ];

        $builder = $db->table('tw_tal_del');
        $s_ins_tw = $builder->select('id')
            ->where(['process_id' => $res, 'rec_dt' => $date])
            ->get()
            ->getRow();

        // Insert data into tw_tal_del table
        // $insert = $db->table('tw_tal_del')->replace($data);
        if (!$s_ins_tw) {
            $insert = $db->table('tw_tal_del')->insert($data);
            if (!$insert) {
                $sq_ck = 0; // Or log the error as needed
                $flag = 0;
            }
        } else {
            $flag = 0;                      ////// NEED to change value 0 after test
            // $res = $process->processid;     ////// NEED to remove after test
            $sq_ck = 0;
        }
    } else {
        $flag = 0;
        $sq_ck = 0;
    }



    if (!$flag) {
        $sq_ck = 0;
        // echo 'Step-1 ';
    } else {
        $data = [
            'processid' => $res,
            'create_modify'   => date("Y-m-d H:i:s"),
            'updated_by'      => session()->get('login')['usercode'],
            'updated_by_ip'   => getClientIP()
        ];

        // Update the tw_max_process table
        $builder = $db->table('master.tw_max_process');
        $update = $builder->where('year', $year)->update($data);
        // echo 'Step-2 ';

        if (!$update) {
            // Handle error
            $sq_ck = 0; // Or log the error as needed
        } else {
            $builder = $db->table('tw_tal_del');

            // Fetching id
            $s_ins_tw = $builder->select('id')
                ->where(['diary_no' => $dairy_no, 'rec_dt' => $date, 'display' => 'Y', 'process_id' => $res, 'print' => 0])
                ->get()
                ->getRow();

            // echo 'Step-3 ';

            if (!$s_ins_tw) {
                // echo 'Step-4 ';
                $return['message'] = 'No recrd found';
                return json_encode($return); // Handle no result scenario if needed
            }

            $r_s_ins_tw = $s_ins_tw->id;
            // pr(base64_decode($_GET['del_ty']));
            // echo 'Step-5 ';
            // pr($_REQUEST['del_ty']);
            // $ex_explode = explode(',', $this->request->getGet('del_ty'));
            $ex_explode = explode(',', $_REQUEST['del_ty']);

            foreach ($ex_explode as $k => $value) {
                $ex_in_exp = explode('!', $value);

                // Check if record exists
                $res_sel_dt = $db->table('tw_o_r')
                    ->where(['tw_org_id' => $r_s_ins_tw, 'del_type' => $ex_in_exp[0], 'display' => 'Y'])
                    ->countAllResults();

                if ($res_sel_dt <= 0) {
                    // echo 'Step-6 ';
                    // Insert new record
                    $db->table('tw_o_r')->insert([
                        'tw_org_id'       => $r_s_ins_tw,
                        'del_type'        => $ex_in_exp[0],
                        'create_modify'   => date("Y-m-d H:i:s"),
                        'updated_by'      => session()->get('login')['usercode'],
                        'updated_by_ip'   => getClientIP()
                    ]);
                }

                // Fetch the tw_o_r id
                $res_tw_o_r = $db->table('tw_o_r')
                    ->select('id')
                    ->where(['tw_org_id' => $r_s_ins_tw, 'del_type' => $ex_in_exp[0], 'display' => 'Y'])
                    ->get()
                    ->getRow();


                // echo 'Step-7 ';

                if (!$res_tw_o_r) {
                    // echo 'Step-8 ';
                    continue; // Handle no result scenario if needed
                }

                $res_tw_o_r = $res_tw_o_r->id;
                $ex_send_to = explode('~', $ex_in_exp[1]);
                // echo $res_tw_o_r;
                // pr($ex_send_to);

                // $key = array_search('undefined', $ex_send_to);
                // if (false !== $key) {
                //     unset($ex_send_to[$key]);
                // }

                // pr($ex_send_to);

                // Check tw_comp_not count
                $res_comp_not = $db->table('tw_comp_not')
                    ->where(['tw_o_r_id' => $res_tw_o_r, 'copy_type' => '0', 'display' => 'Y'])
                    ->countAllResults();

                if ($res_comp_not <= 0) {
                    // echo 'Step-9 ';
                    // Insert new tw_comp_not record

                    // $db->table('tw_comp_not')->insert([
                    //     'tw_o_r_id'       => $res_tw_o_r,
                    //     'tw_sn_to'        => $ex_send_to[0],
                    //     'copy_type'       => '0',
                    //     'sendto_state'    => $ex_send_to[1] ?? '',
                    //     'sendto_district' => $ex_send_to[2] ?? '',
                    //     'send_to_type'    => $ex_send_to[3] ?? ''
                    // ]);

                    if ($ex_send_to[0] != 'undefined') {
                        // echo 'Step-10 ';
                        $db->table('tw_comp_not')->insert([
                            'tw_o_r_id'       => $res_tw_o_r,
                            'tw_sn_to'        => $ex_send_to[0] ?? '',
                            'copy_type'       => '0',
                            'sendto_state'    => $ex_send_to[1] ?? '',
                            'sendto_district' => $ex_send_to[2] ?? '',
                            'send_to_type'    => $ex_send_to[3] ?? '',
                            'l_ljs_p_d'       => '',
                            'l_hjs_p_d'       => '',
                            'l_ljs_pt'        => '',
                            'l_hjs_pt'        => '',
                            't_ljs_p_d'       => '',
                            't_hjs_p_d'       => '',
                            't_ljs_rem'       => '',
                            't_hjs_rem'       => '',
                            'dis_remark'      => '',
                            'create_modify'   => date("Y-m-d H:i:s"),
                            'updated_by'      => session()->get('login')['usercode'],
                            'updated_by_ip'   => getClientIP()
                        ]);
                    }
                } else {
                    // echo 'Step-11 ';
                    // Update existing record
                    $db->table('tw_comp_not')
                        ->where(['tw_o_r_id' => $res_tw_o_r, 'copy_type' => '0', 'display' => 'Y'])
                        ->update([
                            'tw_sn_to' => $ex_send_to[0] ?? '',
                            'sendto_state' => $ex_send_to[1] ?? '',
                            'sendto_district' => $ex_send_to[2] ?? '',
                            'send_to_type' => $ex_send_to[3] ?? '',
                            'create_modify'   => date("Y-m-d H:i:s"),
                            'updated_by'      => session()->get('login')['usercode'],
                            'updated_by_ip'   => getClientIP()
                        ]);
                }

                // Handle additional sends if applicable
                if (!empty($ex_in_exp[2])) {
                    $ex_send_to = explode('$', $ex_in_exp[2]);

                    foreach ($ex_send_to as $send) {
                        // echo 'Step-12 ';
                        $in_exp = explode('~', $send);

                        // Check for existing records
                        $res_comp_not = $db->table('tw_comp_not')
                            ->where(['tw_o_r_id' => $res_tw_o_r, 'tw_sn_to' => $in_exp[0], 'copy_type' => '1', 'display' => 'Y'])
                            ->countAllResults();

                        if ($res_comp_not <= 0) {
                            // echo 'Step-13 ';
                            // Insert new record
                            $db->table('tw_comp_not')->insert([
                                'tw_o_r_id' => $res_tw_o_r,
                                'tw_sn_to' => $in_exp[0] ?? '',
                                'copy_type' => '1',
                                'sendto_state' => $in_exp[1],
                                'sendto_district' => $in_exp[2] ?? '',
                                'send_to_type' => $in_exp[3] ?? '',
                                'l_ljs_p_d'       => '',
                                'l_hjs_p_d'       => '',
                                'l_ljs_pt'        => '',
                                'l_hjs_pt'        => '',
                                't_ljs_p_d'       => '',
                                't_hjs_p_d'       => '',
                                't_ljs_rem'       => '',
                                't_hjs_rem'       => '',
                                'dis_remark'      => '',
                                'create_modify'   => date("Y-m-d H:i:s"),
                                'updated_by'      => session()->get('login')['usercode'],
                                'updated_by_ip'   => getClientIP()
                            ]);
                        }
                    }
                }
            }

            $data = [
                'state'           => $_REQUEST['ddlState'],
                'city'            => $_REQUEST['ddlCity'],
                'create_modify'   => date("Y-m-d H:i:s"),
                'updated_by'      => session()->get('login')['usercode'],
                'updated_by_ip'   => getClientIP()
            ];

            $builder = $db->table('party');
            $builder->where([
                'diary_no' => $dairy_no,
                'pet_res' => $_REQUEST['hd_pet_res'],
                'sr_no' => $_REQUEST['hd_sr_no']
            ]);
            // echo 'Step-14 ';
            $result = $builder->update($data);
        }
    }
} else if ($_REQUEST['hd_new_upd'] == '1') {
    // die('In Step-2');
    // echo 'Step-15 ';
    $data = [
        'name' => $_REQUEST['sp_nm'],
        'address' => $_REQUEST['sp_add'],
        'nt_type' => $_REQUEST['ddl_val'],
        'amount' => $_REQUEST['txtAmount'],
        'user_id' => $ucode,
        'display' => 'Y',
        'amt_wor' => $_REQUEST['nm_wd'],
        'fixed_for' => ($_REQUEST['txtFFX']) ? $_REQUEST['txtFFX'] : NULL,
        'sub_tal' => $_REQUEST['txtSub_nm'],
        'tal_state' => $_REQUEST['ddlState'],
        'tal_district' => $_REQUEST['ddlCity'],
        'enrol_no' => $_REQUEST['hdinenroll'],
        'enrol_yr' => $_REQUEST['hdinenrollyr'],
        'order_dt' => (isset($_REQUEST['hd_order_date']) && ($_REQUEST['hd_order_date'] != 'undefined')) ? $_REQUEST['hd_order_date'] : NULL,
        'individual_multiple' => $_REQUEST['individual_multiple'],
        'create_modify'   => date("Y-m-d H:i:s"),
        'updated_by'      => session()->get('login')['usercode'],
        'updated_by_ip'   => getClientIP()
    ];

    $builder = $db->table('tw_tal_del');
    // $builder->set($data);
    $builder->where('id', $_REQUEST['hd_mn_id']);

    //     $sql = $builder->getCompiledUpdate();

    // // Print or log the compiled query
    // echo $sql;
    // die;

    $result = $builder->update($data);

    // pr($result);

    if (!$result) {
        return false; // Update failed
    } else {
        // echo 'Step-15 ';
        $r_s_ins_tw = $_REQUEST['hd_mn_id'];
        $ex_explode = explode(',', $_REQUEST['del_ty']);
        $del_type_del = [];

        $db = db_connect(); // Get a database connection
        foreach ($ex_explode as $k => $item) {
            $ex_in_exp = explode('!', $item);
            $del_type = $ex_in_exp[0];

            if (!in_array($del_type, $del_type_del)) {
                $del_type_del[] = $del_type;

                $res_sel_dt = $db->table('tw_o_r')
                    ->where('tw_org_id', $r_s_ins_tw)
                    ->where('del_type', $del_type)
                    ->where('display', 'Y')
                    ->countAllResults();

                if ($res_sel_dt <= 0) {
                    $db->table('tw_o_r')->insert([
                        'tw_org_id' => $r_s_ins_tw,
                        'del_type' => $del_type,
                    ]);
                }

                $res_tw_o_r = $db->table('tw_o_r')
                    ->select('id')
                    ->where('tw_org_id', $r_s_ins_tw)
                    ->where('del_type', $del_type)
                    ->where('display', 'Y')
                    ->get()
                    ->getRowArray();

                if ($res_tw_o_r) {
                    $ex_send_to = explode('~', $ex_in_exp[1]);
                    $res_comp_not = $db->table('tw_comp_not')
                        ->where('tw_o_r_id', $res_tw_o_r['id'])
                        ->where('copy_type', '0')
                        ->where('display', 'Y')
                        ->countAllResults();

                    if ($res_comp_not <= 0) {
                        $db->table('tw_comp_not')->insert([
                            'tw_o_r_id'       => $res_tw_o_r['id'],
                            'tw_sn_to'        => $ex_send_to[0],
                            'copy_type'       => '0',
                            'sendto_state'    => $ex_send_to[1] ?? '',
                            'sendto_district' => $ex_send_to[2] ?? '',
                            'send_to_type'    => $ex_send_to[3] ?? '',
                            'l_ljs_p_d'       => '',
                            'l_hjs_p_d'       => '',
                            'l_ljs_pt'        => '',
                            'l_hjs_pt'        => '',
                            't_ljs_p_d'       => '',
                            't_hjs_p_d'       => '',
                            't_ljs_rem'       => '',
                            't_hjs_rem'       => '',
                            'dis_remark'      => '',
                            'create_modify'   => date("Y-m-d H:i:s"),
                            'updated_by'      => session()->get('login')['usercode'],
                            'updated_by_ip'   => getClientIP()
                        ]);
                    } else {
                        $db->table('tw_comp_not')
                            ->where('tw_o_r_id', $res_tw_o_r['id'])
                            ->where('copy_type', '0')
                            ->where('display', 'Y')
                            ->update([
                                'tw_sn_to' => $ex_send_to[0],
                                'sendto_state' => $ex_send_to[1] ?? '',
                                'sendto_district' => $ex_send_to[2] ?? '',
                                'send_to_type' => $ex_send_to[3] ?? '',
                                'create_modify'   => date("Y-m-d H:i:s"),
                                'updated_by'      => session()->get('login')['usercode'],
                                'updated_by_ip'   => getClientIP()
                            ]);
                    }

                    $tot_copt_send_to = '';
                    if (isset($ex_in_exp[2]) && $ex_in_exp[2] != '') {
                        $ex_send_to = explode('$', $ex_in_exp[2]);
                        foreach ($ex_send_to as $index => $send_to_item) {
                            $in_exp = explode('~', $send_to_item);
                            $tot_copt_send_to .= ($tot_copt_send_to ? ',' : '') . $in_exp[0];

                            $res_comp_not = $db->table('tw_comp_not')
                                ->where('tw_o_r_id', $res_tw_o_r['id'])
                                ->where('tw_sn_to', $in_exp[0])
                                ->where('copy_type', '1')
                                ->where('display', 'Y')
                                ->countAllResults();

                            if ($res_comp_not <= 0) {
                                $db->table('tw_comp_not')->insert([
                                    'tw_o_r_id' => $res_tw_o_r['id'],
                                    'tw_sn_to' => $in_exp[0],
                                    'copy_type' => '1',
                                    'sendto_state' => $in_exp[1] ?? '',
                                    'sendto_district' => $in_exp[2] ?? '',
                                    'send_to_type' => $in_exp[3] ?? '',
                                    'l_ljs_p_d'       => '',
                                    'l_hjs_p_d'       => '',
                                    'l_ljs_pt'        => '',
                                    'l_hjs_pt'        => '',
                                    't_ljs_p_d'       => '',
                                    't_hjs_p_d'       => '',
                                    't_ljs_rem'       => '',
                                    't_hjs_rem'       => '',
                                    'dis_remark'      => '',
                                    'create_modify'   => date("Y-m-d H:i:s"),
                                    'updated_by'      => session()->get('login')['usercode'],
                                    'updated_by_ip'   => getClientIP()
                                ]);
                            }
                        }
                    }

                    $not_send_to_in = $tot_copt_send_to ? $tot_copt_send_to : '';

                    $builder = $db->table('tw_comp_not')
                        ->set('display', 'N')
                        ->where('tw_o_r_id', $res_tw_o_r['id'])
                        ->where('copy_type', '1')
                        ->where('display', 'Y')
                        ->whereNotIn('tw_sn_to', [$not_send_to_in]); // Note: Adapt as needed

                    // echo $sql = $builder->getCompiledUpdate();
                    // die;

                    $builder->update();
                }
            }
        }
        $db->table('tw_o_r')
            ->set(
                [
                    'display' => 'N',
                    'create_modify'   => date("Y-m-d H:i:s"),
                    'updated_by'      => session()->get('login')['usercode'],
                    'updated_by_ip'   => getClientIP()
                ]
            )
            ->where('tw_org_id', $r_s_ins_tw)
            ->where('display', 'Y')
            ->whereNotIn('del_type', $del_type_del)
            ->update();

        // Update party table
        $data = [
            'state'           => $_REQUEST['ddlState'],
            'city'            => $_REQUEST['ddlCity'],
            'create_modify'   => date("Y-m-d H:i:s"),
            'updated_by'      => session()->get('login')['usercode'],
            'updated_by_ip'   => getClientIP()
        ];

        $updated = $db->table('party')
            ->where('diary_no', $dairy_no)
            ->where('pet_res', $_REQUEST['hd_pet_res'])
            ->where('sr_no', $_REQUEST['hd_sr_no'])
            ->update($data);

        $sq_ck = ($updated) ? 1 : 0;
    }
}
// echo 'hioh';
// pr($sq_ck);
?>
<input type="hidden" name="hd_ent_suc_f" id="hd_ent_suc_f" value="<?php echo $sq_ck; ?>" />
<input type="hidden" name="hd_new_upd" id="hd_new_upd" value="<?php echo $_REQUEST['hd_new_upd']; ?>" />