<?php

namespace App\Controllers\MasterManagement;

use App\Controllers\BaseController;
use App\Models\MasterManagement\UserManagementModel;
use App\Models\Record_room\TransactionModel;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class VcRoom extends BaseController
{
    public $UserManagementModel;
    public $TransactionModel;
    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->UserManagementModel = new UserManagementModel();
        $this->TransactionModel = new TransactionModel();
    }

    public function vcRoomSearchReport()
    {
        return view('MasterManagement/VcRoom/vc_room_search_report');
    }

    public function vcRoomGetReport()
    {
        $request = service('request');


        if ($request->getPost('next_dt') == '') {
            return redirect()->back()->with('error', 'Select Listing Date');
        } else {
            $list_date = date("Y-m-d", strtotime($request->getPost('next_dt')));
            $data['results'] = $this->TransactionModel->getRecordRoomRep($list_date);
            $data['title'] = 'VC Room URL Send Report for dated ' . date("d-m-Y", strtotime($request->getPost('next_dt')));
            return view('MasterManagement/VcRoom/vc_room_get_report', $data);
        }
    }

    public function pressLounge()
    {
        return view('MasterManagement/VcRoom/press_lounge');
    }

    public function getVcLinks()
    {
        $request = service('request');
        $vc_date = $request->getPost('vc_date');



        if ($vc_date == '') {
            return $this->response->setJSON(['error' => 'Please Enter Date']);
        }


        $date = strtotime($vc_date);

        $cur_date = date('Y-m-d');


        if ($vc_date == $cur_date) {

            if (date("G") < 9) {
                return $this->response->setJSON(['error' => 'LINKS ARE UNDER PREPARATION']);
            }
        }

        if ($vc_date > $cur_date) {
            return $this->response->setJSON(['error' => 'LINKS ARE NOT PUSHED']);
        }
        $data['links'] = $this->TransactionModel->getVcLinks($vc_date);
        $data['vc_date'] = $vc_date;


        if (empty($data['links'])) {

            return $this->response->setJSON(['error' => 'NO LINKS ARE PUSHED']);
        }

        return view('MasterManagement/VcRoom/get_vc_links', $data);
    }

    public function courtMaster()
    {
        return view('MasterManagement/VcRoom/court_master');
    }

    public function courtMasterGet()
    {
        $request = service('request');
        $next_dt = $request->getPost('next_dt');
        if (empty($next_dt)) {
            return $this->response->setJSON(['error' => 'Select Listing Date']);
        }
        $data = $this->TransactionModel->getCourtDetails($next_dt);
        
        return view('MasterManagement/VcRoom/court_master_get', compact('data', 'next_dt'));
    }

    public function loadModal()
    {
        $request = service('request');
        $courtId = $request->getPost('court_id');
        $courtDetails = $this->TransactionModel->getCourtDetailsById($courtId); 

        return view('MasterManagement/VcRoom/modal_view', ['court' => $courtDetails]);
    }

    public function vc_room()
    {
        $data['UserManagementModel'] = $this->UserManagementModel;
        $ucode =  $_SESSION['login']['usercode'];
        $ipAddress =  $_SESSION['login']['ipadd'];
        
        $data['checkaccess'] = $this->UserManagementModel->getVcRoomStatus($ucode, $ipAddress);
        $data['getUpcomingDates'] =$this->UserManagementModel->getUpcomingDates();
        return view('MasterManagement/VcRoom/vc_room_index', $data);
    }


    public function index_get()
    {
        $data['UserManagementModel'] = $this->UserManagementModel;
        $data['_POST'] = $_POST;
        return view('MasterManagement/VcRoom/index_get', $data);
    }

    public function vc_room_save()
    {
        $_POST['next_dt'];
        $_POST['roster_id'];
        $_POST['vc_url'];
        $vc_url = urldecode($_POST['vc_url']);
        $vc_items_csv = implode(',', $_POST['vc_items_csv']);
        
        $ucode =  $_SESSION['login']['usercode'];

         
                $shorturl_key = "zajkk60ldkq"; //kjk540kjljkj9 for 67 server and zajkk60ldkq for 60 server
                $content_push = array("key" => $shorturl_key, "url" => $vc_url);
                $content = json_encode($content_push);
                $base64_encode = base64_encode($content);

                $result = create_shorten($base64_encode);
                $base64_decode = base64_decode($result);
                $json = json_decode($base64_decode, true);
                //var_dump($json);
                if($json['status'] == 'success' OR $json['status'] == 'Short URL Already Available.') {
                    $vc_url = $json['slug'];
                }
          

           /* $sql_check = "select next_dt from vc_room_details where next_dt = '".$_POST['next_dt']."'
            and roster_id = '".$_POST['roster_id']."' and vc_url = '".$vc_url."'
            and item_numbers_csv = '".$vc_items_csv."' and item_numbers = '".$_POST['vc_item']."' ";
            $result = mysql_query($sql_check) or die(mysql_error()); */
            $result = $this->UserManagementModel->checkVcRoomDetails($_POST['next_dt'], $_POST['roster_id'], $vc_url, $vc_items_csv,$_POST['vc_item']);
            if(!empty($result)){
                $afros = 1;
            }
            else{

                $data = [
                    'next_dt'         => $_POST['next_dt'],
                    'roster_id'       => $_POST['roster_id'],
                    'vc_url'          => $vc_url,
                    'created_by'      => $ucode,
                    'item_numbers_csv'=> $vc_items_csv,
                    'item_numbers'    => $_POST['vc_item']
                ];
                $this->db->table('vc_room_details')->insert($data);
                $afros =  $this->db->affectedRows();

                /*$sql = "insert into vc_room_details (next_dt,roster_id,vc_url,created_by,item_numbers_csv,item_numbers) values
                ('".$_POST['next_dt']."', '".$_POST['roster_id']."', '".$vc_url."', '".$ucode."','".$vc_items_csv."','".$_POST['vc_item']."')";
                $result = mysql_query($sql) or die(mysql_error()); */
               
            }


        //$afros = 2;
        if($afros > 0){
            echo "Data Saved";
            $content = "Video Conferencing link for Court No. ".$_POST['courtno']." on ".date('d-m-Y', strtotime($_POST['next_dt']))." at 10:30 AM is ".$vc_url.". - SUPREME COURT OF INDIA";
            $mobile_nos = '9899495211,9871922703';
            //kjuy@98123_-fgbvgAD for 67 server
            //sdjkfgbsjh$1232_12nmnh for 60 server
            $homepage = file_get_contents('http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos='.$mobile_nos.'&message='.urlencode($content).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.SCISMS_VC_URL_NEW);
            $json = json_decode($homepage);
        //var_dump($json);
            if ($json->{'responseFlag'} != "success") {
                //echo "Please check, SMS Services are not working";
            }
        }
        else{
            echo "Unable to Insert";
        }
    }


    public function create_content_vc_email()
    {
        date_default_timezone_set('Asia/kolkata');
        //$cur_dttime = date('d-m-Y H:i:s');
        //$startTime = explode(' ', microtime());
        set_time_limit(30000);
         
        //mysql_query("SET SESSION group_concat_max_len = 10000000000");
        $header = ''; $subject = '';
        $header = "MIME-Version: 1.0" . "\r\n";// Always set content-type when sending HTML email
        $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $header .= 'From: <sci@nic.in>' . "\r\n"; //"mphc@mphc.in"; // sender
        //$header .= "Reply-To: sci@nic.in\r\n";
        //$header .= "Return-Path: sci@nic.in\r\n";
        $header .= "Disposition-Notification-To: sci@nic.in\n";
        $header .= "X-Confirm-Reading-To: sci@nic.in\n";
        extract($_REQUEST);
        $vc_items_csv=implode(',',$vc_items_csv);

        //$next_dt;$roster_id;$vc_url;

        //START ADVOCATE CASES LISTED
       /* $sql = "INSERT INTO email_hc_cl (title,name,email,diary_no,next_dt,mainhead,court,judges,roster_id,board_type,brd_slno, ent_time, cno, jnames, pname, rname, qry_from)
        SELECT j.* FROM (SELECT title, NAME, email, diary_no, next_dt, mainhead, court,
        judges, roster_id, board_type, brd_slno, NOW(),
        IF(reg_no_display = '', CONCAT('Diary No. ',diary_no), CONCAT('Case No. ',reg_no_display)) AS cno,
        (SELECT GROUP_CONCAT(jname ORDER BY judge_seniority) FROM roster_judge r INNER JOIN judge j ON j.jcode = r.judge_id WHERE r.roster_id = a.roster_id
        GROUP BY r.roster_id) jnm, pname, rname, '$vc_qry_from'
        FROM
        (select * from (

        SELECT p.id, b.title, b.name, b.email, m.active_fil_no, m.reg_no_display,
        m.diary_no, a.advocate_id, h.next_dt, h.mainhead, h.judges, h.roster_id, h.board_type, h.clno, h.brd_slno,

        IF((h.clno = 50 OR h.clno = 51), 'By Circulation', (CASE
        WHEN r.courtno between 31 and 60 THEN concat('Court No. ', r.courtno - 30)
        WHEN r.courtno between 61 and 70 THEN concat('Registrar Court No. ', r.courtno - 60)
        WHEN r.courtno = 21 THEN 'Court No. R 1' WHEN r.courtno = 22 THEN 'Court No. R 2' ELSE concat('Court No. ',r.courtno) END)) AS court,


        (CASE WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') ELSE m.pet_name END) AS pname,
        (CASE WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') ELSE m.res_name END) AS rname
        FROM
        heardt h
        INNER JOIN main m ON m.diary_no = h.diary_no
        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.display = 'Y' AND a.advocate_id != 0
        LEFT JOIN bar b ON b.bar_id = a.advocate_id AND b.isdead != 'Y' AND b.email REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND b.email IS NOT NULL
        LEFT JOIN roster r ON r.id = h.roster_id
        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
        WHERE a.diary_no IS NOT NULL AND b.bar_id IS NOT NULL AND r.id is not null
        AND h.brd_slno > 0 AND (h.main_supp_flag = 1 OR h.main_supp_flag =2)

        AND h.next_dt ='$next_dt' and h.roster_id=$roster_id and h.brd_slno in ($vc_items_csv)

        UNION
        SELECT p.id, '' as title,  pt.partyname name, pt.email, m.active_fil_no, m.reg_no_display,
        m.diary_no, a.advocate_id, h.next_dt, h.mainhead, h.judges, h.roster_id, h.board_type, h.clno, h.brd_slno,

        IF((h.clno = 50 OR h.clno = 51), 'By Circulation', (CASE
        WHEN r.courtno between 31 and 60 THEN concat('Court No. ', r.courtno - 30)
        WHEN r.courtno between 61 and 70 THEN concat('Registrar Court No. ', r.courtno - 60)
        WHEN r.courtno = 21 THEN 'Court No. R 1' WHEN r.courtno = 22 THEN 'Court No. R 2' ELSE concat('Court No. ',r.courtno) END)) AS court,

        (CASE WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') ELSE m.pet_name END) AS pname,
        (CASE WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') ELSE m.res_name END) AS rname
        FROM
        heardt h
        INNER JOIN main m ON m.diary_no = h.diary_no
        LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.display = 'Y' AND a.advocate_id in (584,585,610,616,666,940)
        LEFT JOIN party pt ON pt.diary_no = m.diary_no AND pt.email REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND pt.email IS NOT NULL

        LEFT JOIN roster r ON r.id = h.roster_id
        LEFT JOIN cl_printed p ON p.next_dt = h.next_dt AND p.m_f = h.mainhead AND p.part = h.clno AND p.roster_id = h.roster_id AND p.display = 'Y'
        WHERE a.diary_no IS NOT NULL AND pt.diary_no IS NOT NULL AND r.id is not null
        AND h.brd_slno > 0 AND (h.main_supp_flag = 1 OR h.main_supp_flag =2)
        AND h.next_dt = '$next_dt' and h.roster_id=$roster_id and h.brd_slno in ($vc_items_csv)
        ) f where f.id is not null
        GROUP BY email, diary_no, next_dt) a) j
        LEFT JOIN email_hc_cl l ON j.diary_no = l.diary_no AND j.email = l.email AND l.next_dt = j.next_dt
        AND l.mainhead = j.mainhead AND l.roster_id = j.roster_id AND l.brd_slno = j.brd_slno AND l.qry_from = '$vc_qry_from'
        WHERE l.diary_no IS NULL
        ";
        $rs = mysql_query($sql) or die(mysql_errno()); */

        $this->UserManagementModel->insertEmailHcCl($next_dt, $roster_id, $vc_items_csv, $vc_qry_from);
        echo "<br/>";


       /* $sq_we = "SELECT email,title,name, case when (r.frm_time is null or r.frm_time='') then '10:30 AM' else r.frm_time end as start_time,
        vrd.vc_url,e.next_dt,
        e.court
        FROM email_hc_cl e inner join vc_room_details vrd on e.next_dt=vrd.next_dt
        and e.roster_id=vrd.roster_id
        inner join cl_printed p on p.next_dt = vrd.next_dt and p.roster_id = vrd.roster_id and p.display = 'Y'
        inner join roster r on p.roster_id=r.id
        WHERE r.display = 'Y' and sent_to_smspool = 'N' and qry_from='$vc_qry_from' GROUP BY email";
        $rs_we = mysql_query($sq_we) or die(mysql_error()); */

        $rs_we = $this->UserManagementModel->getEmailDetails($vc_qry_from);
        if(!empty($rs_we)){
            $subject = "Video Conferencing URL - Supreme Court of India";
            $email_count = 1;
            foreach($rs_we as $ro_we){
                $email = $ro_we['email'];
                $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
                $message .= "<div style='padding-left:5em'>";
                $message .= "Dear Sir/Madam, :<br/><br/>";
                $message .= "Your matter is listed for hearing in $ro_we[court] at $ro_we[start_time] on ".date("d-m-Y", strtotime($ro_we['next_dt'])).". The link for join the video conferencing is given herein below:<br/><br/>";
                $message .= "$ro_we[vc_url] <br/><br/>";

                //      $message .= "The link will be opened in VIDYO Desktop application in your Laptop or Desktop and Vidyo mobile application on your Android or iOS mobile device. Following link is for downloading :<br/><br/>";
                //        $message .= "<b>Links to be provided</b> :<br/><br/>";


                $message .= "1. CISCO Webex: https://signin.webex.com/join <br/><br/>";
                $message .= "2. CISCO Webex:<br/>Android Store: https://play.google.com/store <br/> Apple app store : https://apps.apple.com/us/app <br/><br/>";
                $message .= "Join the video conference link atleast 30 minutes prior to the scheduled hearing.";
                $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'><span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
                $message .= "</div>";
                $message .= "<br/><br/><br/><font color='#009900' face='Webdings' size='4'></font><font color='#009900' face='verdana,arial,helvetica' size='2'> <strong>Please consider the environment before printing this email<BR/><br/><br/>This is an electronic message. Please do not reply to this email.</strong></font>";
                $message .= "</body></html>";
                $message = wordwrap($message,70);
                //echo $message;
                //$rslt_mail = 1;
                //$email_indv_test = 'ppavan.sc@nic.in, ca.balkasaiyak@sci.nic.in';
                if($email_count == 1){
                    mail('ppavan.sc@nic.in',$subject,$message,$header);
                }

                $rslt_mail = mail($email,$subject,$message,$header);

                if($rslt_mail){
                    $email_count++;
                    $sq_ins_pt = "UPDATE email_hc_cl SET sent_to_smspool = 'Y' WHERE email = '".$ro_we['email']."' and qry_from='$vc_qry_from'";
                    $this->db->query($sq_ins_pt);
                }
                $message = ""; $content1 = "";
                echo "<br/><br/>";
            }
        }


        //$endTime = explode(' ', microtime());
        //
        //$message = 'create_content_email.php page started<br><br>Programme Started Time : <b>'.$cur_dttime.'</b><br/>Programme processed in <b/>'.round((($endTime[1] + $endTime[0]) - ($startTime[1] + $startTime[0])), 4).'</b> seconds.';
        //$subject = "Case Listed SCI email ".date('d-m-Y');
        //mail("kbalkasaiya@gmail.com",$subject,$message,$header);
        $message = "";
       // mysql_close();
    }


    public function create_content_vc_sms()
    {
        date_default_timezone_set('Asia/kolkata');
        $cur_dttime = date('d-m-Y H:i:s');
        $startTime = explode(' ', microtime());
        set_time_limit(30000);
        extract($_REQUEST);
        $vc_items_csv=implode(',',$vc_items_csv);
        //$next_dt;$roster_id;
        //include("/var/www/html/supreme_court/includes/db_inc.php");
        //mysql_query("SET SESSION group_concat_max_len = 10000000000");

        //START ADVOCATE CASES LISTED

         

        $this->UserManagementModel->insertIntoSmsHcCl($next_dt, $roster_id, $vc_items_csv, $vc_qry_from);

        //END ADVOCATE CASES LISTED
        //START SEND data TO SMS_POOL FROM sms_hc_cl
        
        $template_id = SCISMS_VC_URL_NEW;
         

        $this->UserManagementModel->insertIntoSmsPool($vc_qry_from, $template_id);

        //END SEND data TO SMS_POOL FROM sms_hc_cl

        $updt = $this->db->query("update sms_hc_cl set sent_to_smspool = 'Y' where sent_to_smspool = 'N' and roster_id=$roster_id and qry_from = '$vc_qry_from' and next_dt='$next_dt'") or die (mysql_errno());
          

            $cur_dttime = date('d-m-Y H:i:s');
            $startTime = explode(' ', microtime());
            set_time_limit(0);
            $tot_sms = 1; $sms_sleep = 1;
            //cont 53/17, wp 183/13, ma 2011/11
            $sql = "SELECT id, mobile, msg, template_id FROM sms_pool WHERE (c_status = 'N' OR c_status = '1' OR c_status = '2')  and table_name = '$vc_qry_from' ";
            $rs = $this->db->query($sql);
            $srno = 1;
            if($rs->getResultCount() > 0){
                    $result = $rs->getResultArray();
                    foreach($result as $ro)
                    {
                        $sms_pool_id = $ro['id']; 
                        $mobile = $ro['mobile'];
                       
                        $cnt = trim($ro['msg']);
                        $from_adr = "sms_module";
                        $template_id = trim($ro['template_id']);
                        //validations
                        if(empty($mobile)){
                        //  echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Mobile No. Empty.</font></td></tr>";
                            //echo "<font color='red'>Mobile No. Empty.</font>";
                        }
                        else if(empty($cnt)){
                        // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Message content Empty.</font></td></tr>";
                            //echo "<font color='red'>Message content Empty.</font>";
                        }
                        else if(empty($from_adr)){
                        // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Sender Information Empty Contact to Server Room.</font></td></tr>";
                            //echo "<font color='red'>Sender Information Empty.</font>";
                        }
                        else if(strlen($mobile) < '10') {
                        // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Not a Proper Mobile No.</font></td></tr>";
                        //        echo "<font color='red'>Not a Proper Mobile No.</font>";
                        }
                        else{
                            $frm_adr = trim($from_adr);
                            $sms_lengt = explode(",", trim($mobile));
                            $count_sms = count($sms_lengt);

                            for($k=0; $k<$count_sms;$k++){
                                //echo "<br/>";
                                if(strlen(trim($sms_lengt[$k])) != '10'){
                                    //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='red'>Not a proper mobile number.</font></td></tr>";
                                }
                                else if(!is_numeric($sms_lengt[$k])){
                                    //not a numeric value
                                    //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='red'>Mobile number contains invalid value.</font></td></tr>";
                                }
                                else{
                                    //header('Content-type: application/json;');
                                    $mm = trim($sms_lengt[$k]);
                                    //                            $homepage = file_get_contents('http://XXXX/eAdminSCI/a-push-sms-gw?mobileNos='.$mm.'&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode=sdjkfgbsjh$1232_12nmnh');
                                    $homepage = file_get_contents('http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos='.$mm.'&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.$template_id);
                                    $json = json_decode($homepage);
                                
                                    if($json->{'responseFlag'} == "success"){
                                        $sql = "update sms_pool set c_status = 'Y', update_time = NOW() where id = '$sms_pool_id'";
                                        $this->db->query($sql);
                                        
                                    }
                                    else{
                                        $sql = "update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where id = '$sms_pool_id'";
                                        $this->db->query($sql) ;
                                        
                                    }
                                }
                            }

                        }


                    }//end of while loop
                    ?>

                <?php
            }
            else{
                $updt = $this->db->query("update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where (c_status = 'N' OR c_status = '1' OR c_status = '2') and table_name = '$vc_qry_from'");
            }




    }
    
}
