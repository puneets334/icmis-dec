<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $diary_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
        $frm = '';
        $template_id = '';
        $wh_mobileno = '';
        $templateCode = '';
        $listing_date = '';
        if ($_REQUEST['sms_status'] == 'D' || $_REQUEST['sms_status'] == 'refiling') {
            if ($_REQUEST['sms_status'] == 'D')
                $frm = 'Defects';
            else if ($_REQUEST['sms_status'] == 'refiling')
                $frm = 'Refiling';
            if ($_REQUEST['sms_status'] == 'D') {
                $template_id = '1107165872917800681';
                $sql_obj = $db->query("SELECT count(id) FROM obj_save WHERE diary_no = '$diary_no' and display='Y'");
            } else if ($_REQUEST['sms_status'] == 'refiling') {
                $template_id = '1107161234619089003';
                $sql_obj = $db->query("SELECT count(id) FROM obj_save WHERE diary_no = '$diary_no' and display='Y'
                and rm_dt='0000-00-00 00:00:00'");
            }
            $res_sql_obj =  $sql_obj->getNumRows();
            if ($res_sql_obj <= 0) {
                exit();
            } else {
                $res_sql_obj =  1;
            }
        } else if ($_REQUEST['sms_status'] == 'R') {
            $frm = 'Registration';
            $template_id = '1107165881515458494';
            $res_sql_obj =  1;
        } else if ($_REQUEST['sms_status'] == 'DN') {
            $frm = 'Diary';
            //$template_id='1107161234603870863';
            $template_id = '1107165900206642770';
            $res_sql_obj =  1;
        }

        //Password Reset
        else if ($_REQUEST['sms_status'] == 'PWDRESET') {
            //$template_id='';
            $empid = $_REQUEST['empid'];
            $password = $_REQUEST['pwd'];
            $mobileno = $_REQUEST['mob'];
            $template_id = '1107162764348028579';
            //$res_sql_obj=  -1;
            $res_sql_obj =  2;
            //        $testmsg="ICMIS Password has been reset. New Password for Emp ID ".$empid." in ICMIS is ".$password;
            $testmsg = "ICMIS Password has been reset. New Password for Emp ID  " . $empid . " in ICMIS is " . $password . " -  Supreme Court of India";
            $frm = "ResetPassword";
        } else if ($_REQUEST['sms_status'] == 'NEXTDAYLISTED') {
            $mobileno = $_REQUEST['mob'];
            $testmsg = $_REQUEST['msg'];
            //$template_id='1107165873011597277';
            $template_id = '1107165950597744475';
            //$res_sql_obj=  -1;
            $res_sql_obj =  3;
            $frm = "LOOSEDOC";
        } else if ($_REQUEST['sms_status'] == 'scrutiny') {
            $mobileno = $_REQUEST['mob'];
            $testmsg = $_REQUEST['msg'];
            $template_id = '1107165872958238165';
            //$res_sql_obj=  -1;
            $res_sql_obj =  2;
            $frm = "MARKED_FOR_SCRUTINY";
        } else if ($_REQUEST['sms_status'] == 'CAVEAT_FILING') {
            $advocate_mob = "Select mobile from caveat_advocate a join bar b on a.advocate_id=b.bar_id
            where caveat_no='$_REQUEST[caveat_no]' and display='Y' and pet_res='P'";
            $advocate_mob = $db->query($advocate_mob);


            if ($advocate_mob->getNumRows() > 0) {
                $caveat_adv = $advocate_mob->getRowArray();
                if ($caveat_adv['mobile'] != '' && strlen($caveat_adv['mobile']) == '10') {
                    $mobileno = $caveat_adv['mobile'];
                    $wh_mobileno = "91" . $caveat_adv['mobile'];
                }
            }

            $caveat_info = "Select pet_name,res_name from caveat where caveat_no='$_REQUEST[caveat_no]'";
            $caveat_rs = $db->query($caveat_info);
            if ($caveat_rs->getNumRows() > 0) {
                $caveat = $caveat_rs->getRowArray();
            }
            $testmsg = $_REQUEST['msg'];
            $template_id = '1107166235834498119';
            //$res_sql_obj=  -1;

            $cavyear = substr($_REQUEST['caveat_no'], -4);
            $cavnum = substr($_REQUEST['caveat_no'], 0, -4);
            $res_sql_obj =  2;
            $frm = "CAVEAT_FILING";
            $sms_params = array($caveat['pet_name'] . " vs " . $caveat['res_name'], " registered with Caveat Number " . $cavnum . '/' . $cavyear);
            $purpose = 'Fresh Caveat Generation';
            $module = 'Caveat';
            $templateCode = "icmis::case::caveat::status";
        } else if ($_REQUEST['sms_status'] == 'VERIFY') {
            $res_sql_obj =  1;
            $template_id = '1107165881523805462';
            $listing_date = date('d-m-Y', strtotime($_REQUEST['next_dt']));
            $testmsg = "Your Case having Diary No." . substr($diary_no, 0, -4) . '/' . substr($diary_no, -4) . " likely to be listed on $listing_date " . ". - Supreme Court of India";
            $frm = "Verification";
        }


        if ($res_sql_obj > 0) {
            if ($res_sql_obj == 1) {
                $mobileno = '';
                $diary_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
                $get_pet_res = "Select pet_name,res_name from main where diary_no='$diary_no'";
                $get_pet_res = $db->query($get_pet_res);
                $r_get_pet_res = $get_pet_res->getRowArray();

                if ($_REQUEST['sms_status'] == 'D') {
                    /* FUNCTION FOR encryption */
                    $ciphering = "AES-128-CTR";
                    $encryption_iv = '98765432123456789';
                    $encryption_key = "SCIDEFECTS_06072022";
                    $cipher = 'aes-128-cbc'; // Replace with your cipher
                    $iv_length = openssl_cipher_iv_length($ciphering);
                    
                    $predefined_iv = '98765432123456789'; // Replace with your IV
                    $iv = pad_iv($predefined_iv, $iv_length);
                    $encrypt = openssl_encrypt($diary_no, $ciphering, $encryption_key, OPENSSL_RAW_DATA, $iv);
                    $request_str = base64_encode($encrypt);
                    // $encryption = openssl_encrypt($diary_no, $ciphering, $encryption_key, 0, $encryption_iv);
                    $long_url = "https://scetransport.nic.in/get_default.php?diaryno=" . $encrypt;
                    $shorturl_key = "zajkk60ldkq"; //kjk540kjljkj9 for 67 server and zajkk60ldkq for 60 server
                    $content_push = array("key" => $shorturl_key, "url" => $long_url);
                    $content = json_encode($content_push);
                    $base64_encode = base64_encode($content);
                    $result = create_shorten($base64_encode);
                    $base64_decode = base64_decode($result);
                    $json = json_decode($base64_decode, true);
                    //var_dump($json);
                    if ($json['status'] == 'success' or $json['status'] == 'Short URL Already Available.') {
                        $short_url = $json['slug'];
                    }

                    $testmsg = "The case filed by you with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " has been notified with " . $res_sql_obj . " objections. Please remove within statutory period.Link to view defects is " . $short_url . " - Supreme Court of India";
                    $sms_params = array($r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res['res_name'], " defective with objections" . "(Diary no. " . $_REQUEST['d_no'] . '/' . $_REQUEST['d_yr'] . ")");
                    $purpose = 'Defects Notification';
                    $module = 'Filing';
                    $templateCode = "icmis::case::diarization_and_registration";
                } else if ($_REQUEST['sms_status'] == 'R') {
                    $pet = $r_get_pet_res['pet_name'];
                    $res = $r_get_pet_res['res_name'];
                    if (strlen($r_get_pet_res['pet_name']) > 30) {
                        $pet = str_replace(substr($r_get_pet_res['pet_name'], 27, strlen($r_get_pet_res['pet_name'])), '...', $r_get_pet_res['pet_name']);
                    }
                    if (strlen($r_get_pet_res['res_name']) > 30) {
                        $res = str_replace(substr($r_get_pet_res['res_name'], 27, strlen($r_get_pet_res['res_name'])), '...', $r_get_pet_res['res_name']);
                    }
                    $year = '';
                    $res_skey = '';
                    $f_no = '';
                    $testmsg = "The case filed by you with Diary No. " . $_REQUEST['d_no'] . "/" . $_REQUEST['d_yr'] . " - " . $pet . " VS " . $res . " is successfully registered with registration no. " . $res_skey . '-' . $f_no . "/" . $year . ". - Supreme Court of India";
                    $sms_params = array(' with Diary no. ' . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . ' and Cause title- ' . $r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res['res_name'], " registered with Registration Number " . $res_skey . '-' . $f_no . "/" . $year);
                    $purpose = 'Registration';
                    $module = 'Filing';
                    $templateCode = "icmis::case::diarization_and_registration";
                } else if ($_REQUEST['sms_status'] == 'DN') {
                    $pet_cause_title = '';
                    $res_cause_title = '';
                    date_default_timezone_set('Asia/Kolkata');
                    $pet = $pet_cause_title;
                    $res = $res_cause_title;
                    if (strlen($pet_cause_title) > 30) {
                        $pet = str_replace(substr($pet_cause_title, 27, strlen($pet_cause_title)), '...', $pet_cause_title);
                    }
                    if (strlen($res_cause_title) > 30) {
                        $res = str_replace(substr($res_cause_title, 27, strlen($res_cause_title)), '...', $res_cause_title);
                    }

                    $testmsg = "Your case " . $pet . " vs " . $res . " is filed with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " on " . date('d-m-Y H:i:s') . '. - Supreme Court of India';

                    $sms_params = array($pet_cause_title . " vs " . $res_cause_title, " diarized with  Diary Number " . $_REQUEST['d_no'] . "/" . $_REQUEST['d_yr']);
                    $purpose = 'Fresh Diary Generation';
                    $module = 'Filing';
                    $templateCode = "icmis::case::diarization_and_registration";
                } else if ($_REQUEST['sms_status'] == 'refiling') {
                    $testmsg = "The case filed by you with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " is still defective having " . $res_sql_obj . " objections. Please collect the same from Re-filing counter. - Supreme Court of India";
                    //    echo "Select contact from  party where diary_no='$diary_no' and  pet_res='P'";
                } else if ($_REQUEST['sms_status'] == 'VERIFY') {
                    $pet = $r_get_pet_res['pet_name'];
                    $res = $r_get_pet_res['res_name'];
                    $sms_params = array($pet . " vs " . $res . " with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'], " likely to be listed on " . $listing_date);
                    $purpose = 'Verification';
                    $module = 'FILING';
                    $templateCode = "icmis::case::diarization_and_registration";
                }
                $sql = $db->query("Select contact from  party where diary_no='$diary_no' and  pet_res='P' ");
                if ($sql->getNumRows()  > 0) {
                    $res = $sql->getResultArray();
                    foreach ($res as $r_party) {
                        if ($r_party['contact'] != '' && strlen($r_party['contact']) == '10') {
                            if ($mobileno == '') {
                                $mobileno = $r_party['contact'];
                                # $wh_mobileno="91".$r_party['contact'];
                            } else {
                                $mobileno = $mobileno . ',' . $r_party['contact'];
                                #  $wh_mobileno=$mobileno . ',' ."91".$r_party['contact'];
                            }
                        }
                    }
                }

                $advocate_mob = "Select mobile from advocate a join bar b on a.advocate_id=b.bar_id
            where diary_no='$diary_no' and display='Y' and pet_res='P'";
                $advocate_mob = $db->query($advocate_mob);
                if ($advocate_mob->getNumRows() > 0) {
                    $re = $advocate_mob->getRowArray();
                    foreach ($re as $row) {
                        if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                            if ($mobileno == '') {
                                $mobileno = $row['mobile'];
                                $wh_mobileno = "91" . $row['mobile'];
                            } else {
                                $mobileno = $mobileno . ',' . $row['mobile'];
                                $wh_mobileno = $wh_mobileno . ',' . "91" . $row['mobile'];
                            }
                        }
                    }
                    $wh_mobileno = explode(',', $wh_mobileno);
                }
            }
            $mo = $mobileno;
            $ms = $testmsg;
            $frm = $frm;
            $k = '';
            if ($res_sql_obj == 3)
                $k = SMS_And_Email($mo, $ms, $frm, $template_id);
            else
                $k = mphc_sms($mo, $ms, $frm, $template_id);

            $ret = $k;
            $created_by_user = array("name" => $_SESSION['emp_name_login'], "id" => $_SESSION['dcmis_user_idd'], "employeeCode" => $_SESSION['icmic_empid'], "organizationName" => 'SCI');
            $response = send_sms_whatsapp_through_uni_notify(1, $wh_mobileno, $templateCode, $sms_params, null, $purpose, $created_by_user, $module, 'ICMIS', null, null, null);
        } else {
            if ($_REQUEST['sms_status'] == 'D') {
               $ret  = '<div style="text-align: center">Please enter atleast one defect before sendind SMS.</div>';
            }
        }

        return $ret;
    }
}
