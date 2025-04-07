<?php
namespace App\Controllers\Traits;

use App\Models\Filing\RegistrationModel;
use App\Models\Filing\VerificationModel;

trait  ScrunityRegistration {

    public function register_case_supreme()
    {
        $diary_no = $this->request->getPost('d_no') . $this->request->getPost('d_yr');

        $year = date('Y');

        $registration_date = '';
        if ($this->request->getPost('reg_for_year') != 0) {
            $year = $this->request->getPost('reg_for_year');
        }

        if ($this->request->getPost('txt_order_dt') == '') {
            $txt_order_dt = NULL;
        } else {
            $txt_order_dt = date('Y-m-d', strtotime($this->request->getPost('txt_order_dt')));
        }

        $hd_casetype_id = $this->request->getPost('hd_casetype_id');
        $num = $this->request->getPost('num');
        $fn_val = $this->request->getPost('fn_val');


        $f_no = 0;
        $l_no = 0;
        $s_no = 0;
        $cnt_total = 0;

        $db = \Config\Database::connect();
        $db->transStart();
        // $this->db->transComplete();

        $RegistrationModel = new RegistrationModel();

        $get_kounter = $RegistrationModel->get_kounter($year, $hd_casetype_id);

        if ($get_kounter) {
            $res_case_ct = $get_kounter[0]['knt'];
        } else {
            $get_last_reg_no = $RegistrationModel->get_last_reg_no($year, $hd_casetype_id);
            $last_reg_no = !empty($get_last_reg_no[0]['fil_no']) ? $get_last_reg_no[0]['fil_no'] : 0;
            $pos = strrpos($last_reg_no, '-', 0);
            $last_reg_no = substr($last_reg_no, $pos + 1);
            $res_case_ct = !empty(ltrim($last_reg_no, '0')) ? ltrim($last_reg_no, '0') : 0;

            $ins_arr = [
                'year'          =>      $year,
                'knt'           =>      $res_case_ct,
                'casetype_id'   =>      $hd_casetype_id,
                'create_modify' =>      date("Y-m-d H:i:s"),
                'updated_on'    =>      date("Y-m-d H:i:s"),
                'updated_by'    =>      session()->get('login')['usercode'],
                'updated_by_ip' =>      getClientIP()
            ];

            $RegistrationModel->insert_kounter($ins_arr);
        }

        // in case registration is for previous year
        if ($this->request->getPost('reg_for_year') != 0) {
            $registration_date = $txt_order_dt;
            $reg_no = 0;
            if (strlen($res_case_ct) < 6) {
                $length = strlen($res_case_ct);
                $reg_no = intval($res_case_ct) + 1;
                for ($index = $length; $index < 6; $index++) {
                    $reg_no = '0' . $reg_no;
                }
            }

            $check_reg_no = $RegistrationModel->check_reg_no($reg_no, $year, $hd_casetype_id);

            if ($check_reg_no > 0) {
                $get_last_reg_no = $RegistrationModel->get_last_reg_no($year, $hd_casetype_id);
                $max_regno = $get_last_reg_no[0]['fil_no'];

                $pos = strrpos($max_regno, '-', 0);
                $max_regno = substr($max_regno, $pos + 1);
                $res_case_ct = ltrim($max_regno, '0');
            }
        } else {
            $registration_date = date('Y-m-d H:i:s');
        }

        $cnt_no = $res_case_ct + 1;

        $upd_case_ct = $RegistrationModel->update_kounter(['knt' => $cnt_no, 'create_modify' => date("Y-m-d H:i:s"), 'updated_on' => date("Y-m-d H:i:s"), 'updated_by' => session()->get('login')['usercode'], 'updated_by_ip' => getClientIP()], $year, $hd_casetype_id);

        if ($upd_case_ct) {

            $new_case_no=$cnt_no;
            $reg_no=str_pad($_REQUEST['hd_casetype_id'],2,"0",STR_PAD_LEFT).'-'.str_pad($new_case_no,6,"0",STR_PAD_LEFT).'-'.str_pad($cnt_no,6,"0",STR_PAD_LEFT);
            
            $regNoDisplay = $this->getRegistrationNumberDisplay($diary_no, $reg_no, $year);

            $res_cur_det = $RegistrationModel->get_cur_date($diary_no);

            $pre_case_type = 0;
            if (!empty($res_cur_det[0]['fil_no'])) {
                $pre_case_type = substr($res_cur_det[0]['fil_no'], 0, 2);
            }

            $upd_arr = [
                'fil_no'                =>      $reg_no,
                'fil_dt'                =>      $registration_date,
                'usercode'              =>      session()->get('login')['usercode'],
                'mf_active'             =>      'M',
                'active_fil_no'         =>      $reg_no,
                'active_fil_dt'         =>      $registration_date,
                'active_reg_year'       =>      $year,
                'active_casetype_id'    =>      $this->request->getPost('hd_casetype_id'),
                'reg_no_display'        =>      !empty($regNoDisplay) ? $regNoDisplay : '',
                'create_modify'         =>      date("Y-m-d H:i:s"),
                'updated_on'            =>      date("Y-m-d H:i:s"),
                'updated_by'            =>      session()->get('login')['usercode'],
                'updated_by_ip'         =>      getClientIP()
            ];

            $upd_main = $RegistrationModel->update_main($upd_arr, $diary_no);

            if ($upd_main) {

                $hd_casetype_id = strlen($this->request->getPost('hd_casetype_id'));

                $app_zero_ct = '';
                if ($hd_casetype_id < 2) {

                    for ($index = $hd_casetype_id; $index < 2; $index++) {
                        if ($app_zero_ct == '')
                            $app_zero_ct = '0';
                        else
                            $app_zero_ct = $app_zero_ct . '0';
                    }
                }

                $hd_casetype_id1 = $app_zero_ct . $this->request->getPost('hd_casetype_id');

                $hd_res_case_ct = strlen($res_case_ct);

                $app_zero_cno = '';
                if ($hd_res_case_ct < 6) {

                    for ($index = $hd_res_case_ct; $index < 6; $index++) {
                        if ($app_zero_cno == '')
                            $app_zero_cno = '0';
                        else
                            $app_zero_cno = $app_zero_cno . '0';
                    }
                }

                $hd_res_case_ct1 = $app_zero_cno . $res_case_ct;

                $fil_no = $hd_casetype_id1 . $hd_res_case_ct1 . $year;
                
                $f_no = substr($fil_no, 2, 6);
                
                $reg_no = $hd_casetype_id1 . '-' . $f_no;

                $res_sel_his = $RegistrationModel->get_main_casetype_history($diary_no, $res_cur_det[0]['fil_no'], $res_cur_det[0]['fil_dt'], $reg_no, $year, $txt_order_dt);

                if ($res_sel_his[0]['count'] <= 0) {
                    $ins_arr = [
                        'diary_no'                     =>      $diary_no,
                        'old_registration_number'      =>      $res_cur_det[0]['fil_no'],
                        'old_registration_year'        =>      $res_cur_det[0]['fil_dt'],
                        'new_registration_number'      =>      $reg_no,
                        'new_registration_year'        =>      $year,
                        'order_date'                   =>      $txt_order_dt,
                        'ref_old_case_type_id'         =>      $pre_case_type,
                        'ref_new_case_type_id'         =>      $this->request->getPost('hd_casetype_id'),
                        'adm_updated_by'               =>      session()->get('login')['usercode'],
                        'updated_on'                   =>      'now()',
                        'is_deleted'                   =>      'f',
                        'create_modify'                =>       date("Y-m-d H:i:s"),
                        'updated_by'                   =>       session()->get('login')['usercode'],
                        'updated_by_ip'                =>       getClientIP()
                    ];

                    $upd_his = $RegistrationModel->insert_main_casetype_history($ins_arr);

                }

                $ins_arr = [
                    'diary_no'          =>      $diary_no,
                    'fil_no'            =>      $fil_no,
                    'entuser'           =>      session()->get('login')['usercode'],
                    'entdt'             =>      'now()',
                    'casetype_id'       =>      $this->request->getPost('hd_casetype_id'),
                    'case_no'           =>      $res_case_ct,
                    'case_year'         =>      $year,
                    'create_modify'     =>      date("Y-m-d H:i:s"),
                    'updated_on'        =>      date("Y-m-d H:i:s"),
                    'updated_by'        =>      session()->get('login')['usercode'],
                    'updated_by_ip'     =>      getClientIP()
                ];

                $RegistrationModel->insert_registered_cases($ins_arr);


                $skey = $RegistrationModel->get_casetype($this->request->getPost('hd_casetype_id'));

                $res_skey = $skey[0]['short_description'];

                $registration_arr = [];
                $track_inserted_arr = [];

                $registration_arr = ["registration" => "Registration No.: " . $res_skey . '-' . $f_no . "/" . $year];

                if ($num <> 0 || $this->request->getPost('reg_for_year') != 0) {

                    $ins_arr = [
                        'diary_no'                       =>      $diary_no,
                        'num_to_register'                =>      $num,
                        'registration_number_alloted'    =>      $reg_no,
                        'registration_year'              =>      $year,
                        'usercode'                       =>      session()->get('login')['usercode'],
                        'reg_date'                       =>      'NOW()',
                        'create_modify'                  =>      date("Y-m-d H:i:s"),
                        'updated_on'                     =>      date("Y-m-d H:i:s"),
                        'updated_by'                     =>      session()->get('login')['usercode'],
                        'updated_by_ip'                  =>      getClientIP()
                    ];

                    $ins_registration_track = $RegistrationModel->insert_registration_track($ins_arr);

                    if ($ins_registration_track) {
                        $track_inserted_arr = ["track_inserted" => "Track maintained Successfully"];
                    }
                }


                $sms_status = 'R';

                $err_msg_arr = [];
                // $send_sms = $this->send_sms($sms_status, $diary_no);
                // $err_msg_arr = ["err_msg" => $send_sms];

                echo json_encode(array_merge($registration_arr, $track_inserted_arr, $err_msg_arr));
            }
        }
    }

    public function register_case()
    {
        $diary_no = $this->request->getPost('d_no') . $this->request->getPost('d_yr');

        $year = date('Y');

        $registration_date = '';
        if ($this->request->getPost('reg_for_year') != 0) {
            $year = $this->request->getPost('reg_for_year');
        }

        if ($this->request->getPost('txt_order_dt') == '') {
            $txt_order_dt = NULL;
        } else {
            $txt_order_dt = date('Y-m-d', strtotime($this->request->getPost('txt_order_dt')));
        }

        $hd_casetype_id = $this->request->getPost('hd_casetype_id');
        $num = $this->request->getPost('num');
        $fn_val = $this->request->getPost('fn_val');


        $f_no = 0;
        $l_no = 0;
        $s_no = 0;
        $cnt_total = 0;

        $db = \Config\Database::connect();
        $db->transStart();
        // $this->db->transComplete();

        $RegistrationModel = new RegistrationModel();

        $get_kounter = $RegistrationModel->get_kounter($year, $hd_casetype_id);

        if ($get_kounter) {
            $res_case_ct = $get_kounter[0]['knt'];
        } else {
            $get_last_reg_no = $RegistrationModel->get_last_reg_no($year, $hd_casetype_id);
            $last_reg_no = !empty($get_last_reg_no[0]['fil_no']) ? $get_last_reg_no[0]['fil_no'] : 0;
            $pos = strrpos($last_reg_no, '-', 0);
            $last_reg_no = substr($last_reg_no, $pos + 1);
            $res_case_ct = !empty(ltrim($last_reg_no, '0')) ? ltrim($last_reg_no, '0') : 0;

            $ins_arr = [
                'year'          =>      $year,
                'knt'           =>      $res_case_ct,
                'casetype_id'   =>      $hd_casetype_id,
                'create_modify' =>      date("Y-m-d H:i:s"),
                'updated_on'    =>      date("Y-m-d H:i:s"),
                'updated_by'    =>      session()->get('login')['usercode'],
                'updated_by_ip' =>      getClientIP()
            ];

            $RegistrationModel->insert_kounter($ins_arr);
        }

        // in case registration is for previous year
        if ($this->request->getPost('reg_for_year') != 0) {
            $registration_date = $txt_order_dt;
            $reg_no = 0;
            if (strlen($res_case_ct) < 6) {
                $length = strlen($res_case_ct);
                $reg_no = intval($res_case_ct) + 1;
                for ($index = $length; $index < 6; $index++) {
                    $reg_no = '0' . $reg_no;
                }
            }

            $check_reg_no = $RegistrationModel->check_reg_no($reg_no, $year, $hd_casetype_id);

            if ($check_reg_no > 0) {
                $get_last_reg_no = $RegistrationModel->get_last_reg_no($year, $hd_casetype_id);
                $max_regno = $get_last_reg_no[0]['fil_no'];

                $pos = strrpos($max_regno, '-', 0);
                $max_regno = substr($max_regno, $pos + 1);
                $res_case_ct = ltrim($max_regno, '0');
            }
        } else {
            $registration_date = date('Y-m-d H:i:s');
        }

        if ($num == '' || $num == 0) {
            $ex_explode = explode('@', $fn_val);
        } else {

            $fn = '';
            for ($i = 0; $i < $num; $i++) {
                //"6502602!Y@6502603!Y"
                $fn = $fn .   $i . '!Y@';
            }

            $x = substr($fn, 0, -1);
            $ex_explode = explode('@', $x);
        }

        $cnt_no = $res_case_ct;

        for ($i = 0; $i < count($ex_explode); $i++) {

            $sub_exp = explode('!', $ex_explode[$i]);
            if ($sub_exp[1] == 'Y') {
                $cnt_no++;
                $cnt_total++;
            }
        }


        $upd_case_ct = $RegistrationModel->update_kounter(['knt' => $cnt_no, 'create_modify' => date("Y-m-d H:i:s"), 'updated_on' => date("Y-m-d H:i:s"), 'updated_by' => session()->get('login')['usercode'], 'updated_by_ip' => getClientIP()], $year, $hd_casetype_id);

        if ($upd_case_ct) {

            for ($i = 0; $i < count($ex_explode); $i++) {

                $sub_exp = explode('!', $ex_explode[$i]);
                if ($sub_exp[1] == 'Y') {

                    $res_case_ct++;
                }

                if ($this->request->getPost('hd_casetype_id') != '7' && $this->request->getPost('hd_casetype_id') != '8' && $this->request->getPost('hd_casetype_id') != '19' && $this->request->getPost('hd_casetype_id') != '20' && $this->request->getPost('hd_casetype_id') != '11' && $this->request->getPost('hd_casetype_id') != '12') {

                    $get_lowerct = $RegistrationModel->get_lowerct_by_lower_court_id($sub_exp[0], $sub_exp[1]);

                    if ($get_lowerct[0]['count'] <= 0) {

                        $RegistrationModel->update_lowerct(['is_order_challenged' => $sub_exp[1], 'create_modify' => date("Y-m-d H:i:s"), 'updated_on' => date("Y-m-d H:i:s"), 'updated_by' => session()->get('login')['usercode'], 'updated_by_ip' => getClientIP()], $sub_exp[0]);
                    }
                }

                if ($sub_exp[1] == 'Y') {

                    $hd_casetype_id = strlen($this->request->getPost('hd_casetype_id'));

                    $app_zero_ct = '';
                    if ($hd_casetype_id < 2) {

                        for ($index = $hd_casetype_id; $index < 2; $index++) {
                            if ($app_zero_ct == '')
                                $app_zero_ct = '0';
                            else
                                $app_zero_ct = $app_zero_ct . '0';
                        }
                    }

                    $hd_casetype_id1 = $app_zero_ct . $this->request->getPost('hd_casetype_id');

                    $hd_res_case_ct = strlen($res_case_ct);

                    $app_zero_cno = '';
                    if ($hd_res_case_ct < 6) {

                        for ($index = $hd_res_case_ct; $index < 6; $index++) {
                            if ($app_zero_cno == '')
                                $app_zero_cno = '0';
                            else
                                $app_zero_cno = $app_zero_cno . '0';
                        }
                    }
                    $hd_res_case_ct1 = $app_zero_cno . $res_case_ct;

                    $fil_no = $hd_casetype_id1 . $hd_res_case_ct1 . $year;

                    $ins_arr = [
                        'lowerct_id'        =>      $sub_exp[0],
                        'diary_no'          =>      $diary_no,
                        'fil_no'            =>      $fil_no,
                        'entuser'           =>      session()->get('login')['usercode'],
                        'entdt'             =>      'now()',
                        'casetype_id'       =>      $this->request->getPost('hd_casetype_id'),
                        'case_no'           =>      $res_case_ct,
                        'case_year'         =>      $year,
                        'create_modify'     =>      date("Y-m-d H:i:s"),
                        'updated_on'        =>      date("Y-m-d H:i:s"),
                        'updated_by'        =>      session()->get('login')['usercode'],
                        'updated_by_ip'     =>      getClientIP()
                    ];

                    $RegistrationModel->insert_registered_cases($ins_arr);
                    $s_no++;

                    if ($s_no == 1) {
                        $f_no = substr($fil_no, 2, 6);
                    } elseif ($s_no == $cnt_total) {
                        $l_no = substr($fil_no, 2, 6);
                    }
                }
            }

            if ($l_no == 0) {
                $reg_no = $hd_casetype_id1 . '-' . $f_no;
            } else {
                $reg_no = $hd_casetype_id1 . '-' . $f_no . '-' . $l_no;
            }

            $res_cur_det = $RegistrationModel->get_cur_date($diary_no);

            $pre_case_type = 0;
            if (!empty($res_cur_det[0]['fil_no'])) {
                $pre_case_type = substr($res_cur_det[0]['fil_no'], 0, 2);
            }

            $res_sel_his = $RegistrationModel->get_main_casetype_history($diary_no, $res_cur_det[0]['fil_no'], $res_cur_det[0]['fil_dt'], $reg_no, $year, $txt_order_dt);

            if ($res_sel_his[0]['count'] <= 0) {
                $ins_arr = [
                    'diary_no'                     =>      $diary_no,
                    'old_registration_number'      =>      $res_cur_det[0]['fil_no'],
                    'old_registration_year'        =>      $res_cur_det[0]['fil_dt'],
                    'new_registration_number'      =>      $reg_no,
                    'new_registration_year'        =>      $year,
                    'order_date'                   =>      $txt_order_dt,
                    'ref_old_case_type_id'         =>      $pre_case_type,
                    'ref_new_case_type_id'         =>      $this->request->getPost('hd_casetype_id'),
                    'adm_updated_by'               =>      session()->get('login')['usercode'],
                    'updated_on'                   =>      'now()',
                    'is_deleted'                   =>      'f',
                    'create_modify'                =>       date("Y-m-d H:i:s"),
                    'updated_by'                   =>       session()->get('login')['usercode'],
                    'updated_by_ip'                =>       getClientIP()
                ];

                $upd_his = $RegistrationModel->insert_main_casetype_history($ins_arr);

                if ($upd_his) {

                    $regNoDisplay = $this->getRegistrationNumberDisplay($diary_no, $reg_no, $year);

                    $upd_arr = [
                        'fil_no'                =>      $reg_no,
                        'fil_dt'                =>      $registration_date,
                        'usercode'              =>      session()->get('login')['usercode'],
                        'mf_active'             =>      'M',
                        'active_fil_no'         =>      $reg_no,
                        'active_fil_dt'         =>      $registration_date,
                        'active_reg_year'       =>      $year,
                        'active_casetype_id'    =>      $this->request->getPost('hd_casetype_id'),
                        'reg_no_display'        =>      !empty($regNoDisplay) ? $regNoDisplay : '',
                        'create_modify'         =>      date("Y-m-d H:i:s"),
                        'updated_on'            =>      date("Y-m-d H:i:s"),
                        'updated_by'            =>      session()->get('login')['usercode'],
                        'updated_by_ip'         =>      getClientIP()
                    ];

                    $RegistrationModel->update_main($upd_arr, $diary_no);
                }
            }

            $skey = $RegistrationModel->get_casetype($this->request->getPost('hd_casetype_id'));

            $res_skey = $skey[0]['short_description'];

            $registration_arr = [];
            $track_inserted_arr = [];
            if ($l_no == 0) {
                $registration_arr = ["registration" => "Registration No.: " . $res_skey . '-' . $f_no . "/" . $year];
            } else {
                $registration_arr = ["registration" => "Registration No.: " . $res_skey . '-' . $f_no . '-' . $l_no . "/" . $year];
            }

            if ($num <> 0 || $this->request->getPost('reg_for_year') != 0) {

                $ins_arr = [
                    'diary_no'                       =>      $diary_no,
                    'num_to_register'                =>      $num,
                    'registration_number_alloted'    =>      $reg_no,
                    'registration_year'              =>      $year,
                    'usercode'                       =>      session()->get('login')['usercode'],
                    'reg_date'                       =>      'NOW()',
                    'create_modify'                  =>      date("Y-m-d H:i:s"),
                    'updated_on'                     =>      date("Y-m-d H:i:s"),
                    'updated_by'                     =>      session()->get('login')['usercode'],
                    'updated_by_ip'                  =>      getClientIP()
                ];

                $ins_registration_track = $RegistrationModel->insert_registration_track($ins_arr);

                if ($ins_registration_track) {
                    $track_inserted_arr = ["track_inserted" => "Track maintained Successfully"];
                }
            }


            $sms_status = 'R';

            $err_msg_arr = [];
            // $send_sms = $this->send_sms($sms_status, $diary_no);
            // $err_msg_arr = ["err_msg" => $send_sms];

            echo json_encode(array_merge($registration_arr, $track_inserted_arr, $err_msg_arr));
        }
    }



    public function get_and_set_da()
    {
        $model = new RegistrationModel();

        $diary_no = $this->request->getPost('dno') . $this->request->getPost('dyr');

        $row_main = $model->get_and_set_da($diary_no);

        $sec_da_upto_disposal = array(21, 55);

        if ($row_main) {
            $rcasetype = array(1, 3);

            if ($row_main[0]['dacode'] != 0 && $row_main[0]['dacode'] != '') {
                if (in_array($row_main[0]['section_id'], $sec_da_upto_disposal)) {
                    echo "DA already alloted";
                    exit();
                }
            }

            $previous_daname = array(39, 9, 10, 19, 20, 25, 26);
            $forXandPIL = array(5, 6);

            if (in_array($row_main[0]['casetype_id'], $previous_daname)) {

                $lower_case_temp_row = $model->get_lower_case_temp($diary_no);

                if (!empty($lower_case_temp_row)) {

                    $row_da = $model->get_for_da_temp($lower_case_temp_row);

                    if (!empty($row_da)) {
                        $check_section = $this->check_section($row_da[0]['dacode'], $row_main[0]['section_id']);

                        $upd_arr = [
                            'dacode'          =>    $row_da[0]['dacode'],
                            'last_usercode'   =>    session()->get('login')['usercode'],
                            'last_dt'         =>    'NOW()',
                            'create_modify'   =>     date("Y-m-d H:i:s"),
                            'updated_on'      =>     date("Y-m-d H:i:s"),
                            'updated_by'      =>     session()->get('login')['usercode'],
                            'updated_by_ip'   =>     getClientIP()
                        ];

                        $model->update_main($upd_arr, $diary_no);
                        echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                    } else {
                        echo "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD DOES NOT HAVE DA";
                    }
                } else {
                    echo "SORRY, DA NOT FOUND BECAUSE FOR CONT,RP,CURT AND MA PREVIOUS RECORD NOT FOUND";
                }
            } else {
                $dacodeallotted = 0;

                if (in_array($row_main[0]['casetype_id'], $forXandPIL)) {

                    $submaster_rs = $model->get_submaster_id($diary_no);

                    if ($submaster_rs > 0) {

                        $result_num_rows = $model->get_da_case_distribution_pilwrit_num_rows($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $row_main[0]['ref_agency_state_id']);

                        if ($result_num_rows > 0) {
                            if ($result_num_rows > 1) {
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted = 0;
                            } else {
                                $row_da = $model->get_da_case_distribution_pilwrit($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $row_main[0]['ref_agency_state_id']);

                                $check_section = $this->check_section($row_da[0]['dacode'], $row_main[0]['section_id']);

                                $upd_arr = [
                                    'dacode'          =>    $row_da[0]['dacode'],
                                    'last_usercode'   =>    session()->get('login')['usercode'],
                                    'last_dt'         =>    'NOW()',
                                    'create_modify'   =>     date("Y-m-d H:i:s"),
                                    'updated_on'      =>     date("Y-m-d H:i:s"),
                                    'updated_by'      =>     session()->get('login')['usercode'],
                                    'updated_by_ip'   =>     getClientIP()
                                ];

                                $model->update_main($upd_arr, $diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted = 1;
                            }
                        } else {
                            echo "SORRY, DA NOT FOUND";
                            $dacodeallotted = 0;
                        }
                    }
                } elseif ($row_main[0]['from_court'] == '5') {

                    $tribunal = '';
                    $tribunal_sec_arr = $model->get_tribunal_sec_qr($row_main[0]['ref_agency_code_id']);

                    if ($tribunal_sec_arr) {
                        $tribunal = $tribunal_sec_arr[0]['agency_or_court'];
                    }

                    if ($tribunal == 5) {

                        $result_num_rows = $model->get_da_case_distribution_tri_new_num_rows($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $tribunal);

                        if ($result_num_rows > 0) {
                            if ($result_num_rows > 1) {
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted = 0;
                            } else {
                                $row_da = $model->get_da_case_distribution_tri_new($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $tribunal);

                                $check_section = $this->check_section($row_da[0]['dacode'], $row_main[0]['section_id']);

                                $upd_arr = [
                                    'dacode'          =>    $row_da[0]['dacode'],
                                    'last_usercode'   =>    session()->get('login')['usercode'],
                                    'last_dt'         =>    'NOW()',
                                    'create_modify'   =>     date("Y-m-d H:i:s"),
                                    'updated_on'      =>     date("Y-m-d H:i:s"),
                                    'updated_by'      =>     session()->get('login')['usercode'],
                                    'updated_by_ip'   =>     getClientIP()
                                ];

                                $model->update_main($upd_arr, $diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted = 1;
                            }
                        } elseif (in_array($row_main[0]['casetype_id'], $rcasetype)) {

                            $rw_bo = $model->get_user_by_section('82');
                            $bocode = $rw_bo[0]['usercode'];

                            $check_section = $this->check_section($bocode, $row_main[0]['section_id']);

                            $upd_arr = [
                                'dacode'          =>    $bocode,
                                'last_usercode'   =>    session()->get('login')['usercode'],
                                'last_dt'         =>    'NOW()',
                                'create_modify'   =>     date("Y-m-d H:i:s"),
                                'updated_on'      =>     date("Y-m-d H:i:s"),
                                'updated_by'      =>     session()->get('login')['usercode'],
                                'updated_by_ip'   =>     getClientIP()
                            ];

                            $model->update_main($upd_arr, $diary_no);
                            echo "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";
                            $dacodeallotted = 1;
                        }
                    } else {

                        $result_num_rows = $model->get_da_case_distribution_tri_new_num_rows($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $tribunal);

                        if ($result_num_rows > 0) {
                            if ($result_num_rows > 1) {
                                echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                                $dacodeallotted = 0;
                            } else {
                                $row_da = $model->get_da_case_distribution_tri_new($row_main[0]['casetype_id'], $row_main[0]['filregdate'], $tribunal);

                                $check_section = $this->check_section($row_da[0]['dacode'], $row_main[0]['section_id']);

                                $upd_arr = [
                                    'dacode'          =>    $row_da[0]['dacode'],
                                    'last_usercode'   =>    session()->get('login')['usercode'],
                                    'last_dt'         =>    'NOW()',
                                    'create_modify'   =>     date("Y-m-d H:i:s"),
                                    'updated_on'      =>     date("Y-m-d H:i:s"),
                                    'updated_by'      =>     session()->get('login')['usercode'],
                                    'updated_by_ip'   =>     getClientIP()
                                ];

                                $model->update_main($upd_arr, $diary_no);
                                echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                                $dacodeallotted = 1;
                            }
                        } elseif (in_array($row_main[0]['casetype_id'], $rcasetype)) {

                            $rw_bo = $model->get_user_by_section('52');
                            $bocode = $rw_bo[0]['usercode'];

                            $check_section = $this->check_section($bocode, $row_main[0]['section_id']);

                            $upd_arr = [
                                'dacode'          =>    $bocode,
                                'last_usercode'   =>    session()->get('login')['usercode'],
                                'last_dt'         =>    'NOW()',
                                'create_modify'   =>     date("Y-m-d H:i:s"),
                                'updated_on'      =>     date("Y-m-d H:i:s"),
                                'updated_by'      =>     session()->get('login')['usercode'],
                                'updated_by_ip'   =>     getClientIP()
                            ];

                            $model->update_main($upd_arr, $diary_no);
                            echo "SUCCESSFUL, Branch officer Name Sucessfully Alloted as there is no DA";
                            $dacodeallotted = 1;
                        }
                    }
                }

                if ($dacodeallotted == 0) {

                    if ($row_main[0]['regyear'] < date("Y") and  !in_array($row_main[0]['section_id'], $sec_da_upto_disposal)) {
                        $row_main[0]['regyear'] = date("Y");
                    }

                    $row_number_for = $model->get_number_for($row_main[0]['ref_agency_state_id'], $row_main[0]['casetype_id'], $row_main[0]['regyear']);

                    $current_no = 1;
                    foreach ($row_number_for as $row_number_for_val):
                        if ($row_number_for_val['diary_no'] == $diary_no) {
                            $current_no = $row_number_for_val['rownum'];
                        }
                    endforeach;

                    if (in_array($row_main[0]['section_id'], $sec_da_upto_disposal)) {

                        $result = $model->get_da_case_distribution_new("master.da_case_distribution_new", $row_main[0]['casetype_id'], $current_no, $row_main[0]['fildate'], $row_main[0]['ref_agency_state_id']);
                    } else {
                        $result = $model->get_da_case_distribution_new("master.da_case_distribution_new", $row_main[0]['casetype_id'], $current_no, $row_main[0]['filregdate'], $row_main[0]['ref_agency_state_id']);

                        $res_num_rows = $model->get_da_case_distribution_new_num_rows($row_main[0]['casetype_id'], $current_no, $row_main[0]['filregdate'], $row_main[0]['ref_agency_state_id']);

                        if ($res_num_rows <= 0) {

                            $result = $model->get_da_case_distribution_new("master.da_case_distribution", $row_main[0]['casetype_id'], $current_no, $row_main[0]['regyear'], $row_main[0]['ref_agency_state_id']);
                        }
                    }

                    $res_numrows = $model->get_da_case_distribution_new_num_rows($row_main[0]['casetype_id'], $current_no, $row_main[0]['filregdate'], $row_main[0]['ref_agency_state_id']);

                    if ($res_numrows > 0) {
                        if ($res_numrows > 1) {
                            echo "ERROR, DA CAN NOT ALLOT BECAUSE MORE THAN ONE DA FOUND";
                        } else {
                            $row_da = $result;

                            $check_section = $this->check_section($row_da[0]['dacode'], $row_main[0]['section_id']);

                            $upd_arr = [
                                'dacode'          =>    $row_da[0]['dacode'],
                                'last_usercode'   =>    session()->get('login')['usercode'],
                                'last_dt'         =>    'NOW()',
                                'create_modify'   =>     date("Y-m-d H:i:s"),
                                'updated_on'      =>     date("Y-m-d H:i:s"),
                                'updated_by'      =>     session()->get('login')['usercode'],
                                'updated_by_ip'   =>     getClientIP()
                            ];

                            $model->update_main($upd_arr, $diary_no);
                            echo "SUCCESSFUL, DA ALLOTTED SUCCESSFULLY";
                        }
                    } else {
                        echo "SORRY, DA NOT FOUND";
                    }
                }
            }
        } else {
            echo "SORRY, DIARY NUMBER NOT FOUND";
        }
    }

    function check_section($dacode,$matter_section){
        
        $model = new RegistrationModel();

        $diary_no = $this->request->getPost('dno') . $this->request->getPost('dyr');

        $da_data = $model->get_check_section($dacode);

        if($da_data[0]['section']!=$matter_section){

            $ins_arr = [
                        'diary_no'            =>      $diary_no,
                        'dacode'              =>      $dacode,
                        'da_section_id'       =>      $da_data[0]['section'],
                        'matter_section_id'   =>      $matter_section,
                        'ent_by'              =>      session()->get('login')['usercode'],
                        'ent_on'              =>      'NOW()',
                        'create_modify'       =>     date("Y-m-d H:i:s"),
                        'updated_on'          =>     date("Y-m-d H:i:s"),
                        'updated_by'          =>     session()->get('login')['usercode'],
                        'updated_by_ip'       =>     getClientIP()
                        ];

            $model->insert_matters_with_wrong_section($ins_arr);

        }
    }



    public function show_proposal(){

        $model = new RegistrationModel();
        $diary_no = $this->request->getPost('dno') . $this->request->getPost('dyr');

        $case_name = $model->get_case_name_q($diary_no);
        $chk_heardt = $model->get_chk_heardt($diary_no);
        
        if($chk_heardt == 0){
            //echo "DATA NOT IN HEARDT TABLE";
            //exit();
        }

        $details = $model->get_popup_details($diary_no);
        $data['row_cate'] = $model->get_query_cate($diary_no);
        $data['main_case'] = $model->get_main_case($diary_no);

        if($details[0]['mainhead']!='F'){
            if(trim($details[0]['side'])=='C'){
                $stage_based_on_side = "stagecode!=811 and stagecode!=814 and stagecode!=815 ";
            }
            elseif(trim($details[0]['side'])=='R'){
                $stage_based_on_side = "stagecode!=812 and stagecode!=813 and stagecode!=816 ";
            }

            $data['rw_subh'] = $model->get_subheading($details[0]['mainhead'],$stage_based_on_side);
        }
        elseif($details[0]['mainhead']=='F'){

            $data['rw_subh'] = $model->get_mul_category_with_submaster($diary_no);
        }

        $if_list_is_printed = false;
        $n_dt = $details[0]['next_dt'];

        if($n_dt==NULL || $n_dt == ''){
            $details[0]['next_dt'] = NULL;
        }

        $if_printed = $model->get_if_printed($details[0]['next_dt'],$details[0]['mainhead'],$details[0]['roster_id'],$details[0]['clno'],$details[0]['main_supp_flag']);

        if($if_printed>0){
            $if_list_is_printed = true;
        }
        else{
            $if_list_is_printed = false;
        }

        $data['row_judge'] = $model->get_judge($details[0]['next_dt']);

        $data['main_supp_row'] = $model->get_master_main_supp();

        $data['row_purpose'] = $model->get_listing_purpose();

        $data['row'] = $model->get_ia_details($diary_no);

        $data['details'] = $details;

        $data['diary_no'] = $diary_no;

        return view('Filing/popup',$data);
    }



    public function check_listing(){

        $diary_no = $this->request->getPost('d_no') . $this->request->getPost('d_yr');

        $model = new RegistrationModel();

        $for_heardt_zero = $model->check_for_heardt_zero($diary_no);

        if($for_heardt_zero<=0){

            $for_mention = $model->get_mention_memo($diary_no);
            if($for_mention<=0){
                echo "Please Contact Additional Registrar IB for listing";
            }
        }
        else{
            $for_drop_note = $model->get_drop_note($diary_no);
            if($for_drop_note>0){
                echo "Matter is listed and dropped. Please Contact Listing ";
            }
            else{
                echo "listed";
            }
        }
    }

    public function call_listing()
    {
        $main_flow_content_1 = $main_flow_content_2 = "";
        $list = '';
        $obj = 'Y';
        if ($list != '') {
            if ($list == 'Y')
                $obj = 'Y';
            else if ($list == 'N' || $list == 'Z')
                $obj = 'Y';
        }

        // $sessionData = $this->session->get();
        // $ucode = $sessionData['login']['usercode'];
        // $empid = $sessionData['login']['empid'];
        // $diary_no = $sessionData['filing_details']['diary_no'];

        $ucode = session()->get('login')['usercode'];
        $empid = session()->get('login')['empid'];
        $diary_no = $this->request->getPost('dno') . $this->request->getPost('dyr');

        $model = new VerificationModel();
        $main = $model->main($diary_no);
        if ($main !== null && is_array($main) && !empty($main)) {
            $firstElement = $main[0];
            $fixed = $firstElement->fixed;
            $active_fil_no = $firstElement->active_fil_no;
            $casetype_id = $firstElement->casetype_id;
            $case_grp = $firstElement->case_grp;
            $bailno = $firstElement->bailno;
            $nature = $firstElement->nature;
        }
        if ($fixed == 0)
            $fixed = 1;

        if (
            $fixed == 1 || $fixed == '2' || $fixed == 3 || $fixed == 5 || $fixed == 6 || $fixed == 7
            || $fixed == 8 || $fixed == 9 || $fixed == 10 || $fixed == 11 || $fixed == 12
            || $fixed == 13 || $fixed == 14 || $fixed == 15 || $fixed == 'G' || $fixed == 'H' || $fixed == 'I' || $fixed == 'J'
        ) {
            $result = $model->getHeardtByDiaryNo($diary_no);
            $chk_hr = $result['count'];
            $data = $result['data'];
            if (($chk_hr == 0 || $obj == 'Y')) {
                if ($result['count'] > 0) {
                    foreach ($data as $row) {

                        if ($row->roster_id != 0 && $row->clno > 0 && $row->next_dt >= date('Y-m-d'))
                            exit();
                    }
                }
                $check_mention_memo = $model->check_mention_memo($diary_no);
                $check_mention_memo_count = $check_mention_memo['count'];
                if ($check_mention_memo_count > 0) {
                    //  exit();
                    return;
                } {
                    $sus = $sus50 = $sus63 = $con = $exe = $amd = $refiling = $withdrawl = $surrender = $del_respondent = $exemp_court_fee = $substitution = $intervention_impleadment = 0;
                    $Jn = $brdrem = '';
                    $refiling_date = date('Y-m-d');
                    $sess = 0;
                    $ia = $model->ia($diary_no);
                    $chk_hr = $ia['count'];
                    $data = $ia['data'];
                    $iades = '';
                    if ($ia['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->doccode1 == 50) {
                                $sus50 = 1;
                                $sus = 1;
                            }
                            if ($row->doccode1 == 63) {
                                $sus63 = 1;
                                $sus = 1;
                            }
                            if ($row->doccode1 == 28)
                                $con = 1;
                            if ($row->doccode1 == 17)
                                $exe = 1;
                            if ($row->doccode1 == 7)
                                $amd = 1;
                            if ($row->doccode1 == 226) {
                                $refiling = 0;
                                $refiling_date = date('Y-m-d', strtotime($row->ent_dt));
                            }
                            if ($row->doccode1 == 16)
                                $withdrawl = 1;
                            if ($row->doccode1 == 99)
                                $surrender = 1;
                            if ($row->doccode1 == 235)
                                $del_respondent = 1;
                            if ($row->doccode1 == 79)
                                $exemp_court_fee = 1;
                            // begin added on 11.3.2019
                            if ($row->doccode1 == 29)
                                $substitution = 1;
                            if ($row->doccode1 == 93)
                                $intervention_impleadment = 1;
                            // end
                            if (trim($row->docdesc) == "XTRA")
                                $iades .= " and IA No." . $row->docnum . '/' . $row->docyear . '-' . $row->other1;
                            else
                                $iades .= " and IA No." . $row->docnum . '/' . $row->docyear . '-' . $row->docdesc;
                        }
                        $brdrem .= $iades;
                    }
                    $board_type = 'J';
                    $ia_of_case = $model->ia_of_case($diary_no);
                    $chk_hr = $ia_of_case['count'];
                    $data = $ia_of_case['data'];
                    if ($ia_of_case['count'] > 0) {

                        foreach ($data as $row) {
                            $board_type = $row->listable;
                        }
                    }
                    if ($withdrawl == 1) {
                        if ($active_fil_no == '' || $active_fil_no == NULL || substr($active_fil_no, 0, 2) == '31')
                            $board_type = 'R';
                        else
                            $board_type = 'C';
                    }
                    if ($del_respondent == 1 && $refiling != 1) {
                        $board_type = 'J';
                    }

                    if ($exemp_court_fee == 1) {
                        $board_type = 'C';
                    }

                    if ($surrender == 1) {
                        $board_type = 'C';
                    }
                    $array_cur_rev = array(9, 10, 25, 26);
                    if (in_array($casetype_id, $array_cur_rev)) {
                        $board_type = 'C';
                    }
                    if ($board_type == 'J')
                        $sitting_jud = 2;
                    else if ($board_type == 'C' || $board_type == 'R')
                        $sitting_jud = 1;
                    if ($fixed == 10)
                        $brdrem = "FOR ORDERS ON THE QUESTION OF TERRITORIAL JURISDICTION OF PETITION";
                    else if ($fixed == 7)
                        $brdrem = "FOR ADMISSION and I.R." . $brdrem;
                    else if ($fixed == 9)
                        $brdrem = "FOR ORDERS ON THE QUESTION OF MAINTAINABILITY OF PETITION";
                    else if ($fixed == 11)
                        $brdrem = "FOR ADMISSION with Office Report" . $brdrem;
                    else if ($fixed == 12)
                        $brdrem = "FOR ADMISSION and I.R. with Office Report" . $brdrem;
                    else if ($fixed == 8 || $con == 1 || $exe == 1 || $amd == 1 || $fixed == 13 || $fixed == 'I')
                        $brdrem = ltrim(trim($brdrem), "and");
                    else
                        $brdrem = "FOR ADMISSION" . $brdrem;
                    $if14ASCST = 0;
                    $if53JJA = 0;
                    $if397_401 = 0;
                    $ifbail = 0;
                    $ifquash = 0;
                    $ifsuspension = 0;
                    $ifbailsus = 0;
                    $ifbail438byact = 0;
                    $ifbail439byact = 0;
                    $ifhabeas = 0;
                    $category = $model->category($diary_no);
                    $chk_hr = $category['count'];
                    $data = $category['data'];
                    if ($category['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->subcode1 == 14 && $row->subcode2 == 9)
                                $ifbail = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 29)
                                $ifquash = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 36)
                                $ifsuspension = 1;
                            if ($row->subcode1 == 14 && $row->subcode2 == 37)
                                $ifbailsus = 1;
                            if ($row->subcode1 == 13)
                                $ifhabeas = 1;
                        }
                    }
                    $act_main = $model->act_main($diary_no);
                    $chk_hr = $act_main['count'];
                    $data = $act_main['data'];
                    if ($act_main['count'] > 0) {
                        foreach ($data as $row) {

                            if ($row->act == 231 && trim($row->section) == 438)
                                $ifbail438byact = 1;
                            if ($row->act == 231 && trim($row->section) == 439)
                                $ifbail439byact = 1;
                            if ($row->act == 231 && (trim($row->section) == 397 || trim($row->section) == 401))
                                $if397_401 = 1;
                            if ($row->act == 935 && trim($row->section) == '14(A)')
                                $if14ASCST = 1;
                            if ($row->act == 575 && (trim($row->section) == 53 || trim($row->section) == 102))
                                $if53JJA = 1;
                        }
                    }
                    $if_cav = 0;
                    $caveat_mat = $model->caveat_mat($diary_no);
                    $chk_hr = $caveat_mat['count'];
                    $data = $caveat_mat['data'];
                    if ($caveat_mat['count'] > 0) {
                        $if_cav = 1;

                        $proof = $model->proof($diary_no);
                        $chk_hr = $proof['count'];
                        $data = $proof['data'];
                        if ($proof['count'] > 0) {
                            $if_cav = 0;
                        }
                    }
                    if ($if_cav == 1) {
                        $chk_w = $model->chk_w($diary_no);
                        $chk_hr = $chk_w['count'];
                        $data = $chk_w['data'];

                        if ($chk_w['count'] > 0) {
                            $chk_status = 0;
                        } else {
                            // echo "Proposal can not made, Caveat Found";
                        }
                    }
                    $subhead = 0;
                    // $subhead ='';
                    if (trim($case_grp) == 'C') {
                        $subhead = 812;
                    } elseif (trim($case_grp) == 'R') {
                        if ($ifbail == 1 && $ifbail438byact == 1) {
                            $subhead = 804;
                        } elseif ($ifbail == 1 && $ifbail439byact == 1) {
                            $subhead = 805;
                        } elseif ($ifsuspension == 1 && $sus == 1) {
                            if ($sus50 == 1) {
                                $subhead = 806;
                            } elseif ($sus63 == 1) {
                                $subhead = 821;
                            }
                        } else {
                            $subhead = 811;
                        }

                        if ($ifsuspension == 1 && $if14ASCST == 1) {
                            if ($bailno > 0) {
                                $subhead = 823;
                            }
                        }

                        if ($ifsuspension == 1 && $if53JJA == 1) {
                            if ($bailno > 0) {
                                $subhead = 822;
                            }
                        }
                    }
                    if ($if397_401 == 1 && $if53JJA == 1) {
                        $if397_401 = 0;
                    }
                    if ($fixed == 8 || $fixed  == 9 || $fixed  == 10 || $fixed  == 13)
                        $subhead = 808;
                    if ($list == 'Z' || $fixed  == 2 || $fixed  == 17) {
                        if ($case_grp == 'C')
                            $subhead = 801;
                        else
                            $subhead = 808;

                        if ($fixed  == 13)
                            $subhead = 808;

                        if ($_REQUEST['check_ia_not'] == 1)
                            $subhead = 808;

                        if ($fixed  == 17)
                            $subhead = 808;
                    }
                    if ($ifhabeas == 1)
                        $listorder = 32;
                    else
                        $listorder = 32;
                    $brdrem = addslashes($brdrem);
                    $listnxtday = 0;
                    $inperson_case = 0;
                    $inperson_delhi_case = 0;
                    $pipchk = $model->pipchk($diary_no);
                    $chk_hr = $pipchk['count'];
                    $data = $pipchk['data'];
                    if ($pipchk['count'] > 0) {
                        $inperson_case = 1;

                        foreach ($data as $row) {
                            if ($row->state == 490506) {
                                $inperson_delhi_case = 1;
                            }
                        }
                    }
                    $resl_short_cat_case = $model->shortCatCase($diary_no);
                    if ($resl_short_cat_case == 2) {
                        $resl_short_cat_case = $model->top4CourtCase($diary_no);
                    }
                    if ($ifhabeas == 1) {
                        $subhead = 810;
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+1 day'));
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else if ($nature == 6) {
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else if ($inperson_case == 1) {
                        $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        if ($inperson_delhi_case == 1) {
                            $agli_dinank = date('Y-m-d', strtotime(date('Y-m-d') . '+28 day'));
                        }
                        $nxt_dt = $model->nmd_misc_after_desired_dt($resl_short_cat_case, $agli_dinank);
                    } else {
                        $nxt_dt = $model->nmd_misc_dt($resl_short_cat_case);
                    }
                    // $check_ra = $model->check_ra($diary_no ,$nxt_dt);
                    $new_coram = '';
                    $conn_key = 0;
                    $if_listed = 0;
                    $if_fixed = 0;
                    $update_main_case = 0;
                    $next_fixed = ''; // Or set to an empty string if it's empty
                    // Convert empty string to NULL if necessary
                    if ($next_fixed === '') {
                        $next_fixed = $nxt_dt;
                    }
                    $conn_key_disp = 0;
                    $mainhead_conn_main_case = '';
                    $subhead_conn_main_case = '';
                    $headings_conn_main_case = '';
                    $conn_chk_q = $model->conn_chk_q($diary_no, $nxt_dt);
                    $chk_hr = $conn_chk_q['count'];
                    $data = $conn_chk_q['data'];
                    if ($conn_chk_q['count'] > 0) {
                        foreach ($data as $row) {
                            if ($row->c_status == 'P') {
                                $conn_key = $row->conn_key;

                                if ($row->is_retired == 'N')
                                    $new_coram = $new_coram . ',' . $row->jcode;
                            } else
                                $conn_key_disp = $row->conn_key;

                            if ($row->next_dt >= date('Y-m-d') && $row->roster_id != 0 && $row->clno != 0 && $row->brd_slno != 0)
                                $if_listed = 1;

                            /*if($row_conn_ck['is_retired']=='N')
                                $new_coram = $new_coram.','.$row_conn_ck['jcode'];*/

                            $mainhead_conn_main_case = $row->mainhead;
                            $subhead_conn_main_case = $row->subhead;
                        }
                    }
                    $new_coram = ltrim($new_coram, ',');
                    /* patch for Registrar court added on 02-11-2022 */
                    if ($board_type == 'R') {
                        $new_coram = '';
                    }
                    /* end of the patch */
                    if ($new_coram == '')
                        $new_coram = '0';


                    if (!$conn_key == 0) {
                        if ($if_listed == 0) {
                            $check_if_FD = $model->check_if_FD($conn_key);
                            $chk_hr = $check_if_FD['count'];
                            $data = $check_if_FD['data'];

                            if ($check_if_FD['count'] > 0) {
                                foreach ($data as $row) {
                                    $next_fixed = $model->revertDate($row->head_content);
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            $check_if_FD2 = $model->check_if_FD2($conn_key);
                            $chk_hr = $check_if_FD2['count'];
                            $data = $check_if_FD2['data'];

                            if ($check_if_FD2['count'] > 0) {
                                foreach ($data as $row) {

                                    $next_fixed = $row->tentative_cl_dt;
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            $check_ra = $model->check_ra1($conn_key);
                            $chk_hr = $check_ra['count'];
                            $data = $check_ra['data'];

                            if ($check_ra['count'] > 0) {
                                foreach ($data as $row) {
                                    $next_fixed = $row->next_dt;
                                    if ($next_fixed <= $nxt_dt) {
                                        $nxt_dt = $next_fixed;
                                        $if_fixed = 1;
                                    } else if ($next_fixed > $nxt_dt) {
                                        $next_fixed = $nxt_dt;
                                        $update_main_case = 1;
                                    }
                                }
                            }
                            if ($mainhead_conn_main_case == '')
                                $mainhead_conn_main_case = 'M';
                            if ($subhead_conn_main_case == 0)
                                $subhead_conn_main_case = $subhead;
                            if ($mainhead_conn_main_case == 'F') {
                                $headings_conn_main_case = " mainhead='M', subhead='808', mainhead_n='F', subhead_n='$subhead_conn_main_case', ";
                            } else {
                                // $headings_conn_main_case=" mainhead='$mainhead_conn_main_case',subhead='808', "; (commented bcoz main matter is getting effected after tagging done on 07072018)
                                $headings_conn_main_case = " mainhead='$mainhead_conn_main_case',";
                            }


                            if ($next_fixed == '0000-00-00')
                                $next_fixed = $nxt_dt;


                            if ($if_fixed == 0) {
                                $chk_in_l_h = $model->chk_in_l_h($conn_key, $if_fixed, $board_type, $mainhead_conn_main_case, $next_fixed);
                            } else {
                                if ($update_main_case == 1) {
                                    $chk_in_l_h = $model->updateMainCase($conn_key, $update_main_case, $board_type, $mainhead_conn_main_case, $next_fixed);
                                }
                            }
                        }
                    }
                }
                $newCoram = $model->updateCoram($conn_key_disp, $diary_no);
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                foreach ($data as $row) {

                    $chk_row = $row->coram;
                }
                $result = $model->getlastHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                foreach ($data as $row) {

                    $next_dt = $row->next_dt;
                }
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                $next_dt = null;
                if ($diary_no) {
                    $next_dt = '0000-00-00';
                }
                $newCoram = ltrim($newCoram, ',');
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];
                // print_r($result);
                if ($result['count'] > 0) {
                    foreach ($data as $row) {
                        $chk_row = $row->coram;
                        $next_dt = $row->next_dt;
                        $mainhead = $row->mainhead;
                        $mainhead_n = $row->mainhead_n;
                        $subhead_n = $row->subhead_n;
                        $clno = $row->clno;
                        $brd_slno = $row->brd_slno;
                        $roster_id = $row->roster_id;
                        $board_type = $row->board_type;
                        $main_supp_flag = $row->main_supp_flag;
                        $listorder = $row->listorder;
                        $sitting_judges = $row->sitting_judges;
                        $usercode = $row->usercode;
                        $coram = $row->coram;
                        $is_nmd = $row->is_nmd;
                        $no_of_time_deleted = $row->no_of_time_deleted;
                    }
                }
                $selFromHeardt = $model->getSelFromHeardt($diary_no);

                if ($selFromHeardt > 0) {
                    $chkInLH = $model->checkLastHeardt($selFromHeardt);
                    if (empty($chkInLH)) {
                        $isInserted = $model->insertLastHeardt($selFromHeardt);
                        if (!$isInserted) {
                            die("Error inserting into last_heardt table");
                        }
                    }
                }
                $subhead = 0;
                if (trim($case_grp) == 'C') {
                    $subhead = 812;
                    // exit;
                } elseif (trim($case_grp) == 'R') {
                    if ($ifbail == 1 && $ifbail438byact == 1) {
                        $subhead = 804;
                    } elseif ($ifbail == 1 && $ifbail439byact == 1) {
                        $subhead = 805;
                    } elseif ($ifsuspension == 1 && $sus == 1) {
                        if ($sus50 == 1) {
                            $subhead = 806;
                        } elseif ($sus63 == 1) {
                            $subhead = 821;
                        }
                    } else {
                        $subhead = 811;
                    }

                    if ($ifsuspension == 1 && $if14ASCST == 1) {
                        if ($bailno > 0) {
                            $subhead = 823;
                        }
                    }

                    if ($ifsuspension == 1 && $if53JJA == 1) {
                        if ($bailno > 0) {
                            $subhead = 822;
                        }
                    }
                }
                // print_r($new_coram);
                // exit;
                $result = $model->getHeardtByDiaryNo($diary_no);
                $chk_hr = $result['count'];
                $data = $result['data'];

                if ($result['count'] > 0) {

                    $data = [
                        'diary_no' => $diary_no,
                        'conn_key' => $conn_key,
                        'next_dt' => $nxt_dt,
                        'mainhead' => 'M',
                        'subhead' => $subhead,
                        'clno' => 0,
                        'brd_slno' => 0,
                        'roster_id' => 0,
                        'judges' => '0',
                        'board_type' => $board_type,
                        'usercode' => $ucode,
                        'ent_dt' => date('Y-m-d H:i:s'),
                        'module_id' => 4,
                        'mainhead_n' => 'M',
                        'subhead_n' => $subhead,
                        'main_supp_flag' => 0,
                        'listorder' => 32,
                        'tentative_cl_dt' => $nxt_dt,
                        'sitting_judges' => $sitting_jud,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];

                    $model->updateheardt($diary_no, $data);
                } else {
                    $data = [
                        'diary_no' => $diary_no,
                        'conn_key' => $conn_key,
                        'next_dt' => $nxt_dt,
                        'mainhead' => 'M',
                        'subhead' => $subhead,
                        'clno' => 0,
                        'brd_slno' => 0,
                        'roster_id' => 0,
                        'judges' => '0',
                        'coram' => $new_coram,
                        'board_type' => $board_type,
                        'usercode' => $ucode,
                        'ent_dt' => date('Y-m-d H:i:s'),
                        'module_id' => 2,
                        'mainhead_n' => 'M',
                        'subhead_n' => $subhead,
                        'main_supp_flag' => 0,
                        'listorder' => 32,
                        'tentative_cl_dt' => $nxt_dt,
                        'sitting_judges' => $sitting_jud,
                        'create_modify' => date("Y-m-d H:i:s"),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP()
                    ];
                    $model->insertheardt($data);
                    echo 'Proposal Generated Successfully';
                }
            }
        }
    }



    function getRegistrationNumberDisplay($diary_no, $registrationNumber, $registrationYear)
    {

        $RegistrationModel = new RegistrationModel();

        $previousRegistrationNumber = $regNoDisplay = "";
        $caseType = substr($registrationNumber, 0, 2);
        $reg1 = substr($registrationNumber, 3, 6);

        if (strlen($registrationNumber) > 9)
            $reg2 = substr($registrationNumber, 10, 6);
        else
            $reg2 = substr($registrationNumber, 3, 6);

        $row = $RegistrationModel->get_casetype($caseType);

        $res_ct_typ = $row[0]['short_description'];
        $res_ct_typ_mf = $row[0]['cs_m_f'];

        if ($caseType == 9 || $caseType == 10 || $caseType == 19 || $caseType == 20 || $caseType == 25 || $caseType == 26 || $caseType == 39) {
            $row_result = $RegistrationModel->get_reg_no_display_from_main($diary_no);
            $previousRegistrationNumber = !empty($row_result[0]['reg_no_display']) ? $row_result[0]['reg_no_display'] : '';
        }

        if ($reg1 == $reg2) {
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '/' . $registrationYear;
        } else {
            $regNoDisplay = $res_ct_typ . " " . (int)$reg1 . '-' . (int)$reg2 . '/' . $registrationYear;
        }

        if ($previousRegistrationNumber != "" && $previousRegistrationNumber != null) {
            $regNoDisplay .= " in " . $previousRegistrationNumber;
        }
        return $regNoDisplay;
    }

    function send_sms($sms_status, $diary_no)
    {
        $RegistrationModel = new RegistrationModel();

        $frm = '';
        $template_id = '';
        $res_skey = '';
        $f_no = '';
        $year = '';

        if ($sms_status == 'R') {
            $frm = 'Registration';
            $template_id = '1107165881515458494';
            $res_sql_obj =  1;
        }

        if ($res_sql_obj > 0) {
            if ($res_sql_obj == 1) {
                $mobileno = '';

                if ($sms_status == 'R') {

                    $r_get_pet_res = $RegistrationModel->get_main($diary_no);

                    $pet = $r_get_pet_res[0]['pet_name'];
                    $res = $r_get_pet_res[0]['res_name'];

                    if (strlen($pet) > 30) {
                        $pet = str_replace(substr($pet, 27, strlen($pet)), '...', $pet);
                    }
                    if (strlen($res) > 30) {
                        $res = str_replace(substr($res, 27, strlen($res)), '...', $res);
                    }
                    $testmsg = "The case filed by you with Diary No. " . $diary_no . " - " . $pet . " VS " . $res . " is successfully registered with registration no. " . $res_skey . '-' . $f_no . "/" . $year . ". - Supreme Court of India";
                }

                $r_party = $RegistrationModel->get_party($diary_no);

                foreach ($r_party as $r_party_val):

                    if ($r_party_val['contact'] != '' && strlen($r_party_val['contact']) == '10') {
                        if ($mobileno == '') {
                            $mobileno = $r_party_val['contact'];
                        } else {
                            $mobileno = $mobileno . ',' . $r_party_val['contact'];
                        }
                    }

                endforeach;

                $advocate_mob = $RegistrationModel->advocate_mob($diary_no);

                foreach ($advocate_mob as $advocate_mob_val):

                    if ($advocate_mob_val['mobile'] != '' && strlen($advocate_mob_val['mobile']) == '10') {
                        if ($mobileno == '') {
                            $mobileno = $advocate_mob_val['mobile'];
                        } else {
                            $mobileno = $mobileno . ',' . $advocate_mob_val['mobile'];
                        }
                    }

                endforeach;
            }

            $mo = $mobileno;
            $ms = $testmsg;
            $frm = $frm;

            return send_sms($mo, $ms, $frm, $template_id);
        }
    }
}