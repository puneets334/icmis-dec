<?php

namespace App\Models\Listing;

use CodeIgniter\Model;
use DateTime;

class MiscAllocationModel extends Model
{
    public function check_otp_verification($data)
    {
        //$data['list_dt'] = '2019-04-12';
        $builder = $this->db->table('otp_based_login_history');
        $builder->select('*');
        $builder->where('updated_by', $data['usercode']);
        $builder->where('DATE(next_dt)', $data['list_dt']);
        $builder->where('mainhead', $data['mainhead']);
        $builder->where('board_type', $data['bench']);
        $builder->where('main_supp_flag', $data['main_supp']);
        $builder->where('display', 'Y');
        $builder->orderBy('otp_session_start_time', 'DESC');
        $builder->limit(1);
        
        // Execute the query
        $query = $builder->get();
        $row = $query->getRowArray();
        if($row){
            date_default_timezone_set("Asia/Kolkata");
            $otp_session_start_time = $row['otp_session_start_time'];
            $expireTime = date("Y-m-d H:i:s", strtotime($otp_session_start_time) + 600);
            $dateTime = new DateTime($expireTime);
            //pr($_SESSION['otp_based_login_history_id']);
            if ($dateTime->diff(new DateTime)->format('%R') == '-') {
                if ($data['from_function'] == 'doa') {
                    // Update the number of times used (if necessary)
                    $builder = $this->db->table('otp_based_login_history');
                    $builder->set('no_of_times_used', 'no_of_times_used + 1', false);
                    $builder->where('id', $_SESSION['otp_based_login_history_id']);
                    $builder->update();
                }

                // OTP is available
                $_SESSION['is_otp_verified'] = true;
                echo 'available';
            } else if ($dateTime->diff(new DateTime)->format('%R') == '+') {
                // OTP is expired
                $_SESSION['is_otp_verified'] = false;
                echo 'expired';
            }

        }else {
            $_SESSION['is_otp_verified'] = false;
            echo 'expired';
        }
    }

    public function generate_otp_sml_mail($list_dt, $mainhead, $bench, $main_supp, $usercode)
    {
        //$this->checkIfAlreadyOTPApproved($list_dt, $mainhead, $bench, $main_supp, $usercode);
        if(!$this->checkIfAlreadyOTPApproved($list_dt, $mainhead, $bench, $main_supp, $usercode)){
            $this->generate_otp($list_dt, $mainhead, $bench, $main_supp, $usercode);
        }
    }

    public function checkIfAlreadyOTPApproved($list_dt, $mainhead, $bench, $main_supp, $usercode)
    {
        $ifApproved = false;

        // Check if OTP is already verified
        $session = session();
        
        if ($session->has('is_otp_verified') && $session->get('is_otp_verified') === true) {
            
            $builder = $this->db->table('otp_based_login_history oblh');
            $builder->select('DISTINCT oblh.*');
            $builder->join('otp_sent_detail osd', 'oblh.id = osd.otp_based_login_history_id');
            $builder->where('oblh.next_dt', $list_dt);
            $builder->where('oblh.display', 'Y');
            $builder->where('oblh.mainhead', $mainhead);
            $builder->where('oblh.main_supp_flag', $main_supp);
            $builder->where('oblh.board_type', $bench);
            $builder->where('oblh.updated_by', $usercode);
            $builder->where('NOW() < ADDTIME(oblh.otp_session_start_time, INTERVAL 10 MINUTE)'); // 10 minutes validity

            $query = $builder->get();
            $row = $query->getRowArray();

            // If OTP is approved
            if ($row) {
                $otp_based_login_history_id = $row['id'];
                $ifApproved = true;

                // Update `no_of_times_used`
                $builder = $this->db->table('otp_based_login_history');
                $builder->set('no_of_times_used', 'no_of_times_used + 1', false);
                $builder->where('id', $otp_based_login_history_id);
                $builder->update();
            }
        }

        return $ifApproved;
    }

    function generate_otp($list_dt, $mainhead, $bench, $main_supp, $usercode)
    {
        $session = session();
        try {
            
            $session->set('current_next_date', $list_dt);
            
            $data = [
                'updated_by' => $usercode,
                'otp_send_time' => date('Y-m-d H:i:s'),
                'otp_session_start_time' => date('Y-m-d H:i:s'),
                'next_dt' => $list_dt,
                'mainhead' => $mainhead,
                'board_type' => $bench,
                'main_supp_flag' => $main_supp,
            ];

            $builder = $this->db->table('otp_based_login_history');
            $builder->insert($data);

            // Get the last inserted ID
            //$otp_based_login_history_id = 0;
            $otp_based_login_history_id = $this->db->insertID();
            $session->set('otp_based_login_history_id', $otp_based_login_history_id);

            // Query authorized OTP users
            $authorizedOtpUsersQuery = "SELECT usercode, name, usertype, mobile_no, email_id, ut.type_name 
                FROM master.users u 
                INNER JOIN master.usertype ut ON u.usertype = ut.id 
                WHERE usercode IN (10102, 559, 10575) AND u.display = 'Y' 
                ORDER BY usertype DESC";
            //pr($authorizedOtpUsersQuery);
            $queryResult = $this->db->query($authorizedOtpUsersQuery);
            $authorizedUsers = $queryResult->getResultArray();
            //pr($authorizedUsers);
            // Generate OTP and send notifications
            $otp = $this->generateRandomOTP(5);
            $list_type = ($main_supp == 1) ? "Main " : (($main_supp == 2) ? "Supplementary " : "");
            $subject = "OTP For Allocation of {$list_type}Misc. List Dated {$list_dt}";

            foreach ($authorizedUsers as $user) {
                $mobile_no = $user['mobile_no'];
                $email_id = $user['email_id'];
                $text = "OTP of {$user['type_name']} For {$list_type}Misc. List Dated {$list_dt} is: {$otp}";

                // Send SMS/Email
                //$this->sendSMSEmail($mobile_no, $email_id, $text, $subject);

                // Insert OTP sent details
                $otpDetailData = [
                    'otp_based_login_history_id' => $otp_based_login_history_id,
                    'usercode' => $user['usercode'],
                    'otp_sent' => $otp,
                    'otp_sent_time' => date('Y-m-d H:i:s'),
                ];
                $this->db->table('otp_sent_detail')->insert($otpDetailData);
            }
        } catch (\Exception $e) {
            // Handle errors
            echo "Error: " . $e->getMessage();
        }
    }

    function generateRandomOTP($length = 5)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
    }

    function sendSMSEmail($mobile,$email,$text,$subject){
        $url = 'http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos=' . $mobile . '&message=' . rawurlencode($text) . '&typeId=34&myUserId=NIC001001&myAccessId=root';
        $responseObj = (array)json_decode(file_get_contents($url));
        $url = "http://xxxx/eAdminSCI/stealth-a-push-mail-gw?toIds=" . rawurlencode($email) . "&subject=" . rawurlencode($subject) . "&msg=" . rawurlencode($text) . "&typeId=35";
        $result = (array)json_decode(file_get_contents($url));
    }

    function verify_otp($listing_date, $otpList, $mainhead, $loggedInUser, $main_supp, $bench){
        $otpListArray = explode('&', $otpList);
        $matchOtpCount = 0;

        //pr($otpListArray);
         // Loop through each OTP entered by the user
         foreach ($otpListArray as $otpEntry) {
            $userOtp = explode('=', $otpEntry);
            $usercode = $userOtp[0];
            $enteredOtp = $userOtp[1];
            $otp_based_login_history_id = null;
            
            if (!empty($enteredOtp)) {
                // Query to match OTP
                $builder = $this->db->table('otp_based_login_history oblh');
                $builder->join('otp_sent_detail osd', 'oblh.id = osd.otp_based_login_history_id');
                $builder->where('oblh.next_dt', $listing_date);
                $builder->where('oblh.display', 'Y');
                $builder->where('oblh.mainhead', $mainhead);
                $builder->where('oblh.main_supp_flag', $main_supp);
                $builder->where('oblh.board_type', $bench);
                $builder->where('oblh.updated_by', $loggedInUser);
                $builder->where('osd.usercode', $usercode);
                $builder->where('osd.otp_sent', $enteredOtp);
                //pr($builder->getCompiledSelect());
                $resultMatchOtp = $builder->get()->getResultArray();
                //pr($resultMatchOtp);

                if (!empty($resultMatchOtp)) {
                    $row = $resultMatchOtp[0];
                    $otp_based_login_history_id = $row['otp_based_login_history_id'];

                    // Update OTP entered and time
                    $builder = $this->db->table('otp_sent_detail');
                    $builder->set([
                        'otp_entered' => $enteredOtp,
                        'otp_entered_time' => date('Y-m-d H:i:s')
                    ]);
                    $builder->where('id', $row['id']);
                    $builder->update();

                    $matchOtpCount++;
                }
            }
        }

        $wrongAttemptsCount = 0;
        if ($matchOtpCount == 1) {
            // If OTP matched, mark OTP as verified
            session()->set('is_otp_verified', true);
            echo 'SUCCESS';
        } else {
            // Update wrong attempts
            $builder = $this->db->table('otp_sent_detail osd');
            $builder->set('no_of_times_wrong_attemt', 'no_of_times_wrong_attemt+1', false);
            $builder->where('otp_based_login_history_id', session()->get('otp_based_login_history_id'));
            $builder->update();

            // Check if there are 5 wrong attempts
            $builder = $this->db->table('otp_sent_detail');
            $builder->where('otp_based_login_history_id', session()->get('otp_based_login_history_id'));
            $result = $builder->get()->getResultArray();

            if (!empty($result)) {
                // Handle maximum attempts (you can disable the OTP if needed)
                
                /*$session = session();
                $otpBasedLoginHistoryId = $session->get('otp_based_login_history_id');
                if ($otpBasedLoginHistoryId) {
                    // Use the query builder to update the record
                    $builder = $this->db->table('otp_based_login_history');
                    $builder->set('display', 'N');
                    $builder->where('id', $otpBasedLoginHistoryId);
                    $builder->update();
                }*/
            }
        }

    }

    function coram_q_b($postData, $q_usercode){
        $mainhead = $postData['mainhead'];
        
        $pre_after_notice_sel = $postData['pre_after_notice_sel'];
        $pre_after_notice_where_condition = "";
        if($pre_after_notice_sel == 1){
            //pre notice
            $pre_after_notice_where_condition = " (c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) 
        and h.subhead not in (813,814) ) AND ";
        }
        else if($pre_after_notice_sel == 2){
            //after notice
            $pre_after_notice_where_condition = " !(c.diary_no is null and (m.fil_no_fh = '' or m.fil_no_fh is null) 
        and h.subhead not in (813,814) ) AND ";
        }
        else{
            //pre and after notice
            $pre_after_notice_where_condition = "";
        }
        $board_type = "J";
        $q_next_dt = date("Y-m-d", strtotime($postData['list_dt']));
        $SNo_tobefixed = 0;

        //$q_next_dt  = '2023-10-10';
        //$q_next_dt  = '2024-12-31';


        $sql_SNo = "
            SELECT ROW_NUMBER() OVER (ORDER BY working_date) AS SNo, *
            FROM master.sc_working_days
            WHERE EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM DATE ?)
            AND is_holiday = 0
            AND is_nmd = 1
            AND display = 'Y'
            AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM DATE ?)
            AND working_date = ?";
        
        $query = $this->db->query($sql_SNo, [$q_next_dt, $q_next_dt, $q_next_dt]);
        if ($query->getNumRows() > 0) {
            $row_SNo = $query->getRowArray();
            //pr($row_SNo);
            $SNo_tobefixed = $row_SNo['sno'];
        } else {
            $SNo_tobefixed = 0;
        }

        $main_supp = $postData['main_supp'];

    
        $sq_pre_allocation = "INSERT INTO master.listed_info (main_supp, remark, next_dt, mainhead, bench_flag, roster_id, 
            fix_dt, mentioning, week_commencing, freshly_filed, freshly_filed_adj, part_heard, inperson, bail, after_week, imp_ia, ia, nr_adj, adm_order, ordinary, total, usercode, ent_dt)
            SELECT '$main_supp', 'Pre_Allocation', a.next_dt, a.mainhead, 
                a.board_type, 
                CAST(a.coram AS bigint) AS roster_id,
                a.fix_dt, 
                a.mentioning, 
                a.week_commencing, 
                a.freshly_filed, 
                a.freshly_filed_adj, 
                a.part_heard, 
                a.inperson, 
                a.bail, 
                a.after_week, 
                a.imp_ia, 
                a.ia_other_than_imp_ia, 
                a.nradj_not_list, 
                a.adm_order, 
                a.ordinary, 
                a.total, $q_usercode, NOW() 
            FROM (
                SELECT next_dt, mainhead, board_type, COALESCE(jcd, '0') AS coram,
                    SUM(CASE WHEN (listorder = 4) THEN 1 ELSE 0 END) AS fix_dt,        
                    SUM(CASE WHEN (listorder = 5) THEN 1 ELSE 0 END) AS mentioning,        
                    SUM(CASE WHEN (listorder = 7) THEN 1 ELSE 0 END) AS week_commencing,         
                    SUM(CASE WHEN (listorder = 32) THEN 1 ELSE 0 END) AS freshly_filed,
                    SUM(CASE WHEN (listorder = 25) THEN 1 ELSE 0 END) AS freshly_filed_adj,
                    SUM(CASE WHEN subhead = 824 AND listorder NOT IN (4,5,7,32,25) THEN 1 ELSE 0 END) AS part_heard,
                    SUM(CASE WHEN inperson = 1 AND bail != 1 AND subhead != 824 AND listorder NOT IN (4,5,7,32,25) THEN 1 ELSE 0 END) AS inperson,
                    SUM(CASE WHEN bail = 1 AND inperson != 1 AND subhead != 824 AND listorder NOT IN (4,5,7,32,25) THEN 1 ELSE 0 END) AS bail,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 8) THEN 1 ELSE 0 END) AS after_week,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 24) THEN 1 ELSE 0 END) AS imp_ia,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 21) THEN 1 ELSE 0 END) AS ia_other_than_imp_ia,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 48) THEN 1 ELSE 0 END) AS nradj_not_list,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 2) THEN 1 ELSE 0 END) AS adm_order,
                    SUM(CASE WHEN (inperson != 1 AND bail != 1 AND subhead != 824 AND listorder = 16) THEN 1 ELSE 0 END) AS ordinary,
                    COUNT(*) AS total
                FROM (
                    SELECT h.next_dt, h.mainhead, h.board_type, jcd, h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no,
                        CASE WHEN (h.subhead = 804 OR mc.submaster_id = 173 OR doccode1 IN (40,41,48,49,71,72,118,131,211,309)) THEN 1 ELSE 0 END AS bail,
                        CASE WHEN a.advocate_id IN (584,585,610,616,666,940) THEN 1 ELSE 0 END AS inperson       
                    FROM heardt h       
                    INNER JOIN main m ON m.diary_no = h.diary_no 
                    LEFT JOIN docdetails d ON d.diary_no = m.diary_no AND d.display = 'Y' AND d.iastat = 'P' AND d.doccode = 8 
                        AND d.doccode1 IN (7,66,29,56,57,28,103,133,226,3,309,73,99,40,48,72,71,27,124,2,16,41,49,71,72,102,118,131,211,309) 
                    LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'  
                    LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584,585,610,616,666,940) AND a.display = 'Y'          
                    LEFT JOIN (
                        SELECT r.id, 
                            CAST(SPLIT_PART(STRING_AGG(j.jcode::TEXT, ',' ORDER BY j.judge_seniority),',', 1) AS INTEGER) AS jcd, 
                            rb.bench_no, mb.abbr, r.tot_cases, r.courtno 
                        FROM master.roster r 
                        LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id 
                        LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id 
                        LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id 
                        LEFT JOIN master.judge j ON j.jcode = rj.judge_id
                        WHERE j.is_retired != 'Y' 
                        AND mb.board_type_mb = '$board_type' 
                        AND j.display = 'Y' 
                        AND rj.display = 'Y' 
                        AND rb.display = 'Y' 
                        AND mb.display = 'Y' 
                        AND r.display = 'Y' 
                        AND r.m_f = '1' 
                        AND r.from_date = '$q_next_dt'
                        GROUP BY r.id , rb.bench_no, mb.abbr, j.judge_seniority
                        ORDER BY r.courtno, r.id, j.judge_seniority
                    ) ab ON ab.jcd = CAST(CASE WHEN SPLIT_PART(h.coram, ',', 1) = '' THEN NULL ELSE SPLIT_PART(h.coram, ',', 1)END AS INTEGER)
                    WHERE (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                    AND m.c_status = 'P' 
                    AND h.board_type = '$board_type'
                    AND h.mainhead = 'M' 
                    AND h.next_dt = '$q_next_dt'
                    AND h.main_supp_flag = 0 
                    GROUP BY m.diary_no, h.next_dt, h.mainhead, h.board_type, ab.jcd, h.subhead, d.doccode1, mc.submaster_id, h.listorder, h.diary_no, a.advocate_id
                ) t 
                GROUP BY jcd, t.next_dt, t.mainhead, t.board_type
            ) a
            LEFT JOIN master.listed_info l ON l.next_dt = a.next_dt AND l.mainhead = a.mainhead AND l.bench_flag = a.board_type
            AND l.roster_id = a.coram::INTEGER AND l.main_supp = $main_supp AND l.remark = 'Pre_Allocation' 
            WHERE l.next_dt IS NULL";
        
        $query = $this->db->query($sq_pre_allocation);
        
        $q_brd_slno = "123";
        $listorder = isset($postData['listing_purpose']) ? f_selected_values($postData['listing_purpose']) : '';
        $listorder_only_fix_dt ="";

        $builder = $this->db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('working_date', $q_next_dt);
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        //pr($builder->getCompiledSelect());
        $query = $builder->get();
        $res_isnmd = $query->getRowArray();

        if (!empty($res_isnmd)) {
            if ($res_isnmd['is_nmd'] == 1) {
                $misc_nmd_flag = 1;
            }
            if ($res_isnmd['is_nmd'] == 0) {
                $misc_nmd_flag = 0;
            }
        

            if ($main_supp == 2 and $postData['select_advance'] == 'Y') {
                $p_listorder = "l.code in (4,5) ";
            } else {
                if ($listorder != "all") {
                    $p_listorder = "l.code in ($listorder)";
                } else {
                    $p_listorder = "";
                }
            }
            $p_listorder = "l.code in (4,5) ";
            $subhead_arry = f_selected_values($postData['subhead']);
            if($subhead_arry != "all"){
                $subhead_select = "and h.subhead in ($subhead_arry)";
            }
            else{
                $subhead_select = "";
            }

            $case_type_arry = f_selected_values($postData['case_type']);
            if($case_type_arry != "all"){
                $case_type_select = "and m.active_casetype_id in ($case_type_arry)";
            }
            else{
                $case_type_select = "";
            }

            $subject_cat_arry = f_selected_values($postData['subject_cat']);
            if($subject_cat_arry != "all"){
                $subject_cat_select = "and mc.submaster_id in ($subject_cat_arry)";
            }
            else{
                $subject_cat_select = "";
            }

            $noc = $postData['noc']; //no. of cases to be list per judge
            $partno = $postData['partno'];
            $md_module_id = "16";
            $roster_selected = $postData['chked_jud_sel'];

            $explode_rs = explode("JG", $roster_selected);
            $cars = array();
            $sel_ros_id_for_cl_print = '';
            for ($i = 0; $i < (count($explode_rs) - 1); $i++) {
                $explode_rs_jg = explode("|", $explode_rs[$i]);
                //$main_supp = 0;
                if($main_supp == 1){
                    
                    $builder = $this->db->table('heardt h')
                    ->select('COALESCE(SUM(CASE WHEN listorder = 32 AND h.subhead NOT IN (829, 804) THEN 1 ELSE 0 END), 0) AS fr')
                    ->select('COALESCE(SUM(CASE WHEN listorder != 32 THEN 1 ELSE 0 END), 0) AS ors')
                    ->join('main m', 'm.diary_no = h.diary_no', 'inner')
                    ->where('h.roster_id', $explode_rs_jg[1])
                    ->where('h.mainhead', $mainhead)
                    ->where('h.next_dt', $q_next_dt)
                    ->where('h.main_supp_flag', $main_supp)
                    // ->where('h.clno', $partno)
                    ->where('h.brd_slno >', 0)
                    ->groupStart()
                        ->where('m.diary_no = CAST(m.conn_key AS bigint)')
                        ->orWhere('m.conn_key', '0')
                        ->orWhere('m.conn_key', '')
                        ->orWhere('m.conn_key IS NULL')
                    ->groupEnd();

                $query = $builder->get();
                } else {
                    $builder = $this->db->table('heardt h')
                    ->select('COALESCE(SUM(CASE WHEN listorder = 32 AND h.subhead NOT IN (829, 804) THEN 1 ELSE 0 END), 0) AS fr')
                    ->select('COALESCE(SUM(CASE WHEN listorder != 32 THEN 1 ELSE 0 END), 0) AS ors')
                    ->join('main m', 'm.diary_no = h.diary_no', 'inner')
                    ->where('h.roster_id', $explode_rs_jg[1])
                    ->where('h.mainhead', $mainhead)
                    ->where('h.next_dt', $q_next_dt)
                    ->where('h.main_supp_flag', $main_supp)
                    ->where('h.clno', $partno)
                    ->where('h.brd_slno >', 0)
                    ->groupStart()
                        ->where('m.diary_no = CAST(m.conn_key AS bigint)')
                        ->orWhere('m.conn_key', '0')
                        ->orWhere('m.conn_key', '')
                        ->orWhere('m.conn_key IS NULL')
                    ->groupEnd();

                    $query = $builder->get();
                }
                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray();
                    $fr_listed_cnt = $row['fr'] ?? 0; 
                    $ors_listed_cnt = $row['ors'] ?? 0;
                } else{
                    $fr_listed_cnt = 0;
                    $ors_listed_cnt = 0;
                }    


            //roster id, judges, is_selected
            $cars[] = array($explode_rs_jg[1], $explode_rs_jg[0], "Y", $explode_rs_jg[3], $explode_rs_jg[4], $fr_listed_cnt, $ors_listed_cnt);// 5 - freshlisted, 6 - old listed
            $sel_ros_id_for_cl_print .= $explode_rs_jg[1] . ",";

            }

            $roster_not_selected = $postData['chked_jud_unsel'];
            $explode_not_rs = explode("JG", $roster_not_selected);

            $not_sel_rs = array();

            for ($i = 0; $i < (count($explode_not_rs) - 1); $i++) {
                $explode_not_rs_jg = explode("|", $explode_not_rs[$i]);
                $not_sel_rs[$explode_not_rs_jg[1]] = $explode_not_rs_jg[0];
                $cars[] = array($explode_not_rs_jg[1], $explode_not_rs_jg[0], "N");
            }

            $selectedJudges = array();
            $notSelectedJudges = array();
            $selected_presiding_judges = "";
            $judges_coram = "";
            //pr($cars);
            foreach ($cars as $car) {
                if ($car[2] == "Y") {
                    $judge_exploded_for_presiding = explode(",", $car[1]);
                    $selected_presiding_judges .= $judge_exploded_for_presiding[0] . ",";
                    $selectedJudges[] = $car;
                    //$judges_coram .= "FIND_IN_SET($ex_cr22[$j], coram) OR ";
                } elseif ($car[2] == "N") {
                    // Handle not selected judges
                    $notSelectedJudges[] = $car;
                }
            
                // Insert into `allocation_trap` table
                $data = [
                    'list_dt' => $q_next_dt,
                    'is_roster_selected' => $car[2],
                    'roster_id' => $car[0],
                    'fresh_limit' => isset($car[3]) ? $car[3] : 0,
                    'old_limit' => isset($car[4]) ? $car[4] : 0,
                    'clno' => $partno,
                    'main_supp_flag' => $main_supp,
                    'short_cat_flag' => $postData['short_non_short_sel'],
                    'advance_flag' => $postData['select_advance'],
                    'usercode' => $q_usercode,
                    'ent_dt' => date('Y-m-d H:i:s'),
                    'listorder' => $listorder,
                ];
                
                
                $this->db->table('allocation_trap')->ignore(true)->insert($data);
            }

            $selected_presiding_judges = rtrim($selected_presiding_judges,",");
            $srno_notlisted = 1;
            $total_case_listed_plus = 0;
            //VERIFY CAUSE LIST PRINTED OR NOT
            $all_sel_ros_id = rtrim($sel_ros_id_for_cl_print, ",");
            $rslt_is_printed = f_cl_is_printed($q_next_dt, $partno, $mainhead, $all_sel_ros_id);
            $rslt_is_freezed = f_cl_is_freezed($q_next_dt, $board_type, $partno, $mainhead);
            if($rslt_is_freezed == 1 && $main_supp == 1){
                echo "<br/><span style='color:red;'>YOU CAN NOT ALLOT CASES IN SESSION $partno. BECAUSE SESSION $partno <U>FREEZED</U>.</span>";
            } else {
                if ($rslt_is_printed == 0) {
                    $judges_coram = rtrim(trim($judges_coram), "OR");
                    
                    $builder = $this->db->table('master.listing_purpose l');
                    $builder->select('l.code, l.purpose, l.priority, l.fx_wk')
                            ->where('l.display', 'Y')
                            ->where('l.code !=', '0')
                            ->where('l.code !=', '49')
                            ->orderBy('l.priority');

                    if (!empty($p_listorder)) {
                        $builder->where($p_listorder, null, false);
                    }
                    
                    $query = $builder->get();
                    //pr($query->getResultArray());
                    // Process the results
                    foreach ($query->getResultArray() as $ro_pr) {
                        if ($ro_pr['code'] == '32') {
                            $fx_fr_or = "FR";
                        } else {
                            $fx_fr_or = "ORS";
                        }
                    
                        $sel_rs = array();
                        $sell_roster_id = "";
                        for ($i = 0; $i < count($explode_rs); $i++) {
                            $explode_rs_jg = explode("|", $explode_rs[$i]);
                            if(!empty($explode_rs_jg)){
                                //roster id, judges, is_selected
                                //$sell_roster_id .= $explode_rs_jg[1] . ",";
                                //$sel_rs[$explode_rs_jg[0]] = $explode_rs_jg[1];
                                $sell_roster_id .= isset($explode_rs_jg[1]) ? $explode_rs_jg[1] . "," : "";
                                $sel_rs[$explode_rs_jg[0]] = $explode_rs_jg[1] ?? null;
                            }
                        }
                        $sell_roster_id = rtrim($sell_roster_id, ",");

                        $short_categoary_array = array(173,176,222);

                        $short_cat = "1=1";
                        $is_nmd_column_flag = "";
                        $short_cat_sq = "";

                        if ($ro_pr['code'] != 4 AND $ro_pr['code'] != 5) {
                            if ($misc_nmd_flag == 1) {
                                //echo "<br>Inside of misc_nmd_flag one : <br>";
                                $is_nmd_column_flag = " ";
                                //$short_cat = " OR if(a.advocate_id is not null,1=1,  (mc.submaster_id IN (173,176,222) or h.subhead in (804,831) )   )  ) ";
                                $short_cat = " (mc.submaster_id IN (173,176,222) or h.subhead in (804,831) ) ";
                                $short_cat_sq = " or mc.submaster_id = 173 ";
                            } else {
                                $is_nmd_column_flag = " ";                        
                                $short_cat = " 1=1 ";                       
                            }
                        }

                        if($main_supp == 2){
                            $mandatory_selection = " and h.listorder = '" . $ro_pr['code'] . "'  ";
                        }              
                        else if($ro_pr['code'] == 4 OR $ro_pr['code'] == 5 OR $ro_pr['code'] == 32){                    
                            $mandatory_selection = " and h.listorder = '" . $ro_pr['code'] . "'  ";
                        } else {
                            $mergeTo_mandatory_selction = "";
                            if($misc_nmd_flag == 1){}
                            $mandatory_selection = " and (CASE WHEN 
                            h.listorder in (4,5) THEN
                            h.listorder = '" . $ro_pr['code'] . "'
                            WHEN 1=$misc_nmd_flag
                            THEN   
                            (a.advocate_id is not null OR 
                            (
                            (mc.submaster_id IN (173,176,222) or h.subhead in (804,831))
                            AND h.listorder = '" . $ro_pr['code'] . "')
                            )
                            WHEN
                            h.listorder NOT IN (4,5,32)
                            THEN
                            (h.listorder = '" . $ro_pr['code'] . "' or h.subhead = '824' or h.subhead = '810' or h.subhead = '802' or h.subhead = '803' or h.subhead = '807' or h.subhead = '804' 
                            or dd.doccode1 is not null or a.advocate_id is not null $short_cat_sq)                                 
                            ELSE h.listorder = '" . $ro_pr['code'] . "' END)  ";
                        
                        }

                        $short_non_short_sel = $postData['short_non_short_sel'];
                        $cars = array();
                        $arr_dumpp = "";
                        if ($ro_pr['code'] == '32') {
                            $orderby = "date(fresh_case_order_by) asc";
                        } else {
                            //$orderby = "CAST(RIGHT(diary_no, 4) AS SIGNED) ASC, CAST(LEFT(diary_no,LENGTH(diary_no)-4) AS SIGNED) ASC";
                            $orderby = "CAST(RIGHT(CAST(diary_no AS TEXT), 4) AS INTEGER) ASC,
                                        CAST(LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) AS INTEGER) ASC";
                        }

                        //echo "<br><br>";
                        //commented on 31-01-2018 RP case will list if coram available AND m.active_casetype_id != 9 AND m.active_casetype_id != 10

                        //Start In supplementary ignore cases which are dropped in Main list
                        $ignore_dropped_cases=" and h.diary_no not in (select diary_no from drop_note where cl_date='$q_next_dt' and display in ('B','Y'))";
                    
                        //END
                        //#AND coram :: integer != 0 
                        $sql = "SELECT COALESCE(verification_date,CASE WHEN active_fil_dt IS NOT NULL THEN active_fil_dt ELSE diary_no_rec_date END) AS fresh_case_order_by,
                                    t.rid, judge_id_on_rid,STRING_AGG(mc.submaster_id::TEXT, ',') AS cat1,t.cat,submaster_id,m.conn_key AS main_key,dd.doccode1,a.advocate_id,ad_al.j1 AS allocated_j1,l.priority,h.*,'coram' AS listed_by,
                                    CASE WHEN ad_al.diary_no IS NOT NULL THEN 1 ELSE 0 END AS in_advance_list,
                                    CASE WHEN c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813, 814) THEN 1 ELSE 2 END AS pre_notice
                                FROM heardt h
                                LEFT JOIN main m ON h.diary_no = m.diary_no
                                LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no 
                                AND dd.iastat = 'P' 
                                AND dd.doccode = 8 
                                AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
                                LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                                AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                                AND a.display = 'Y'
                                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
                                LEFT JOIN (
                                    SELECT 
                                        j.submaster_id AS cat, 
                                        STRING_AGG(DISTINCT j.j1 :: TEXT, ',') AS judge_id_on_rid,
                                        STRING_AGG(DISTINCT a.id::TEXT, ',') AS rid
                                    FROM (
                                        SELECT 
                                            r.id, SPLIT_PART(STRING_AGG(rj.judge_id::TEXT, ','), ',', 1) AS judge_code
                                        FROM master.roster r
                                        INNER JOIN master.roster_judge rj ON rj.roster_id = r.id
                                        LEFT JOIN master.judge j ON j.jcode = rj.judge_id
                                        WHERE j.is_retired != 'Y' 
                                        AND r.id IN ($sell_roster_id) 
                                        AND r.m_f = '1' 
                                        AND '$q_next_dt' BETWEEN r.from_date AND r.to_date 
                                        AND r.display = 'Y' 
                                        AND rj.display = 'Y'
                                        GROUP BY r.id, j.judge_seniority 
                                        ORDER BY j.judge_seniority
                                    ) a
                                    LEFT JOIN master.judge_category j ON j.j1 =  CAST(a.judge_code AS bigint)
                                    AND ('$q_next_dt' BETWEEN j.from_dt AND j.to_dt OR j.to_dt IS NULL)
                                    WHERE j.display = 'Y'
                                    GROUP BY j.submaster_id
                                ) t ON mc.submaster_id = t.cat
                                LEFT JOIN advance_allocated ad_al ON CAST(ad_al.diary_no AS bigint) = h.diary_no 
                                AND ad_al.next_dt = '$q_next_dt' 
                                AND ad_al.board_type = 'J'
                                LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no 
                                AND ad_al.next_dt = ad_d.cl_date
                                LEFT JOIN defects_verification dv ON dv.diary_no = m.diary_no
                                LEFT JOIN case_remarks_multiple c ON CAST(c.diary_no AS bigint) = m.diary_no 
                                AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                                WHERE $pre_after_notice_where_condition
                                rd.fil_no IS NULL
                                AND m.diary_no IS NOT NULL
                                AND (h.listorder IN (4, 5) OR ad_d.diary_no IS NULL)
                                AND mc.display = 'Y' 
                                AND mc.submaster_id NOT IN (911, 912, 914, 0, 239, 240, 241, 242, 243)
                                AND mc.submaster_id IS NOT NULL
                                AND m.active_casetype_id NOT IN (25, 26)
                                AND m.c_status = 'P'
                                AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)  OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL)
                                $subhead_select
                                $case_type_select
                                $subject_cat_select
                                AND ( CASE WHEN (h.listorder IN (4, 5) OR a.advocate_id IS NOT NULL) THEN TRUE
                                            ELSE ( CASE WHEN EXTRACT(DOW FROM '$q_next_dt'::DATE) != 3 THEN h.is_nmd != 'Y' ELSE TRUE END 
                                                    AND (CASE WHEN $misc_nmd_flag = 0 THEN TRUE ELSE $short_cat END))
                                                    END)
                                AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)
                                AND (CASE WHEN h.listorder = '32' THEN h.next_dt <= '$q_next_dt' 
                                        ELSE (CASE WHEN h.listorder IN (4, 5) AND $main_supp = 1 THEN h.next_dt = '$q_next_dt' OR h.next_dt < CURRENT_DATE ELSE h.next_dt = '$q_next_dt' END)END)
                                $mandatory_selection
                                AND h.next_dt IS NOT NULL
                                AND h.roster_id = 0
                                AND h.brd_slno = 0
                                AND h.board_type = 'J'
                                AND h.mainhead = '$mainhead'
                                AND h.main_supp_flag = 0
                                
                                AND coram != '0' 
                                AND coram IS NOT NULL
                                AND TRIM(coram) != ''
                                $ignore_dropped_cases
                                GROUP BY h.diary_no, dv.verification_date, m.active_fil_dt, m.diary_no_rec_date, t.rid, t.judge_id_on_rid, t.cat, mc.submaster_id, m.conn_key, dd.doccode1, a.advocate_id, ad_al.j1, l.priority, ad_al.diary_no, c.diary_no, m.fil_no_fh

                                UNION
                                
                                SELECT CASE WHEN verification_date IS NOT NULL THEN verification_date 
                                WHEN verification_date IS NULL AND active_fil_dt IS NOT NULL THEN active_fil_dt ELSE diary_no_rec_date END AS fresh_case_order_by, t.rid, judge_id_on_rid, NULL AS cat1, t.cat, submaster_id, m.conn_key AS main_key, doccode1, a.advocate_id, ad_al.j1 AS allocated_j1, l.priority, h.*, 'category' AS listed_by,
                                CASE WHEN ad_al.diary_no IS NOT NULL THEN 1 ELSE 0 END AS in_advance_list,
                                CASE WHEN c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813,814) THEN 1 ELSE 2 END AS pre_notice      
                                FROM main m 
                                LEFT JOIN heardt h ON h.diary_no = m.diary_no
                                LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
                                LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no AND dd.iastat = 'P' AND dd.doccode = 8 AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
                                LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.advocate_id IN (584, 585, 610, 616, 666, 940) AND a.display = 'Y'
                                LEFT JOIN (
                                    SELECT 
                                        j.submaster_id AS cat, 
                                        STRING_AGG(DISTINCT j.j1 :: TEXT, ',') AS judge_id_on_rid,
                                        STRING_AGG(DISTINCT a.id::TEXT, ',') AS rid
                                    FROM (
                                        SELECT 
                                            r.id, 
                                            SPLIT_PART(STRING_AGG(rj.judge_id::TEXT, ','), ',', 1) AS judge_code
                                        FROM master.roster r
                                        INNER JOIN master.roster_judge rj ON rj.roster_id = r.id 
                                        LEFT JOIN master.judge j ON j.jcode = rj.judge_id 
                                        WHERE j.is_retired != 'Y' 
                                            AND r.id IN (" . rtrim($sell_roster_id, ',') . ")
                                            AND r.m_f = '1' 
                                            AND '$q_next_dt' BETWEEN r.from_date AND r.to_date 
                                            AND r.display = 'Y' 
                                            AND rj.display = 'Y'
                                        GROUP BY r.id, j.judge_seniority
                                        ORDER BY j.judge_seniority
                                    ) a
                                    LEFT JOIN master.judge_category j ON j.j1 = CAST(a.judge_code AS bigint) 
                                    WHERE ('$q_next_dt' BETWEEN j.from_dt AND j.to_dt OR j.to_dt is null) AND j.display = 'Y'
                                    GROUP BY j.submaster_id
                                ) t ON mc.submaster_id = t.cat
                                LEFT JOIN advance_allocated ad_al ON CAST(ad_al.diary_no AS bigint) = h.diary_no AND ad_al.next_dt = '$q_next_dt' AND ad_al.board_type = 'J'
                                LEFT JOIN advanced_drop_note ad_d ON ad_d.diary_no = ad_al.diary_no AND ad_al.next_dt = ad_d.cl_date
                                LEFT JOIN defects_verification dv ON dv.diary_no = m.diary_no
                                LEFT JOIN case_remarks_multiple c ON CAST(c.diary_no AS bigint) = m.diary_no AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                                WHERE  
                                    rd.fil_no IS NULL 
                                    $pre_after_notice_where_condition
                                    AND ((h.listorder IN (4, 5)) OR ad_d.diary_no IS NULL)
                                    AND m.active_casetype_id NOT IN (9, 10, 25, 26)
                                    AND mc.display = 'Y' 
                                    AND mc.submaster_id NOT IN (0, 911, 912, 914, 239, 240, 241, 242, 243) 
                                    AND m.c_status = 'P' 
                                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) 
                                    AND h.main_supp_flag = 0 
                                    AND (h.coram = '0' OR h.coram IS NULL OR trim(h.coram) = '')
                                    $subhead_select
                                    $case_type_select
                                    $subject_cat_select
                                    AND ((h.listorder IN (4, 5) OR a.advocate_id IS NOT NULL) OR (EXTRACT(DOW FROM '$q_next_dt'::DATE) != 3 AND h.is_nmd != 'Y') AND CASE WHEN $misc_nmd_flag = 0 THEN TRUE ELSE $short_cat END)
                                    AND h.subhead NOT IN (0, 801, 817, 818, 819, 820, 848, 849, 850, 854)
                                    AND h.mainhead = '$mainhead' 
                                    AND h.next_dt IS NOT NULL 
                                    AND h.roster_id = 0 
                                    AND h.brd_slno = 0 
                                    AND h.board_type = 'J'
                                    $mandatory_selection
                                    AND CASE 
                                        WHEN h.listorder = 32 THEN h.next_dt <= '$q_next_dt'
                                        ELSE CASE 
                                            WHEN h.listorder IN (4, 5) AND $main_supp = 1 THEN h.next_dt = '$q_next_dt' OR h.next_dt < CURRENT_DATE
                                            ELSE h.next_dt = '$q_next_dt'
                                        END 
                                    END
                                    $ignore_dropped_cases
                                GROUP BY m.diary_no, dv.verification_date, t.rid, t.judge_id_on_rid, t.cat, mc.submaster_id, dd.doccode1, a.advocate_id, ad_al.j1, l.priority, h.diary_no, ad_al.diary_no, c.diary_no ";
                                
                                // ORDER BY 
                                //     CASE WHEN subhead = '831' THEN 0 ELSE 999 END ASC,
                                //     CASE WHEN subhead = '824' THEN 1 ELSE 999 END ASC,
                                //     CASE WHEN listorder IN (4, 5) THEN 2 ELSE 999 END ASC,
                                //     CASE WHEN advocate_id IS NOT NULL THEN 3 ELSE 999 END ASC,
                                //     CASE WHEN subhead = '804' OR doccode1 IN (40, 41, 48, 49, 71, 72, 118, 131, 211, 309) OR submaster_id = 173 THEN 4 ELSE 999 END ASC,
                                //     CASE WHEN listorder = 7 THEN 5 ELSE 999 END ASC,
                                //     CASE WHEN listorder = 25 THEN 6 ELSE 999 END ASC,
                                //     CASE WHEN subhead IN ('810', '802', '803', '807') THEN 7 ELSE 999 END ASC,
                                //     pre_notice ASC, 
                                //     CASE WHEN doccode1 IN (56, 57, 102, 73, 99, 27, 124, 2, 16) THEN 8 ELSE 999 END ASC,
                                //     in_advance_list ASC,
                                //     priority ASC,
                                //     no_of_time_deleted DESC,
                                //     CASE WHEN coram IS NOT NULL AND coram != '0' AND trim(coram) != '' THEN 1 ELSE 999 END ASC,
                                //     $orderby";
                                //pr($sql);
                                $query = $this->db->query($sql);
                                $getQueryResult = $query->getResultArray();
                                //pr($getQueryResult);
                                if($getQueryResult){
                                    
                                    foreach ($getQueryResult as $row_c) {
                                        $finally_case_listed = 0;
                                        $submaster_id = $row_c['submaster_id'];
                                        $listorder = $row_c['listorder'];

                                        $sql_crm = "SELECT STRING_AGG(jcode::TEXT, ',' ORDER BY judge_seniority) AS new_coram
                                                FROM master.judge
                                                WHERE is_retired = 'N' 
                                                AND display = 'Y' 
                                                AND jtype = 'J' 
                                                AND jcode = ANY(string_to_array(:jcodes:, ',')::int[])";

                                        
                                        $query_crm = $this->db->query($sql_crm, ['jcodes' => $row_c['coram']]);
                                        $row_coram = $query_crm->getRowArray();
                                        if ($row_coram) {
                                            $coram = $row_coram['new_coram'];
                                        } else {
                                            $coram = "";
                                        }
                                        $coram_ex = !empty($coram) ? explode(",", $coram) : [];
                                        $binay_vl = "";
                                        $binary_wt = array();
                                        $q_diary_no = $row_c['diary_no'];
                                        $cat1 = $row_c['cat1'];
                                        $note_category_coram = "";
                                        $tobelisted = 0;
                                        $finally_listed = 0;
                                        $checked_before_verify = "";
                                        $ifMandatoryCases = false;
                                        if ($row_c['diary_no'] == $row_c['main_key']) {
                                            $dairy_with_conn_k = f_cl_conn_key($q_diary_no);
                                        } else {
                                            $dairy_with_conn_k = $q_diary_no;
                                        }

                                        if($row_c['submaster_id'] == '173' OR $row_c['doccode1'] != null OR $row_c['doccode1'] != '' OR $row_c['advocate_id'] != '' OR $row_c['advocate_id'] != null OR $row_c['subhead'] == 802 OR $row_c['subhead'] == 803 OR $row_c['subhead'] == 804 OR $row_c['subhead'] == 807 OR $row_c['subhead'] == 810 OR $row_c['subhead'] == 824 OR $row_c['listorder'] == 4 OR $row_c['listorder'] == 5 OR $row_c['listorder'] == 7 OR $row_c['listorder'] == 25){
                                            $ifMandatoryCases = true;
                                        }
                                        //pr($ifMandatoryCases);
                                        if ($ifMandatoryCases == 1 || (($row_c['in_advance_list'] == 1 AND $postData['select_advance'] == 'Y') || $postData['select_advance']=='N') || $listorder == 32) {

                                            $checked_notbefore_verify = "";
                                            //echo "<br>";
                                            $checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                                            //echo "<br>";
                                            
                                            //pr($checked_notbefore_verify);
                                        
                                            //echo "<br>second : ";
                                            $checked_notbefore_verify = rtrim(ltrim(trim($checked_notbefore_verify),','),',');
                                            //pr($checked_notbefore_verify);
                                            /*end top 4 court order*/
                                            if ($finally_listed == 0) {
                                                $checked_before_verify = check_list_before($dairy_with_conn_k, 'B');
                                                if ($checked_before_verify == '-1'){
                                                    insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','ELIMINATED DUE TO BENCH NOT AVAILABLE');
                                                    $finally_listed = 1;
                                                    //break;
                                                }

                                                //pr($selectedJudges);
                                                if ($checked_before_verify != '-1' && $checked_before_verify != '') {

                                                    for ($rowJudge = 0; $rowJudge < count($selectedJudges); $rowJudge++) {
                                                        //pr($selectedJudges[$rowJudge][1]);            
                                                        $rosterId = $selectedJudges[$rowJudge][0];
                                                        $judge_group_indv = $selectedJudges[$rowJudge][1];
                                                        $freshCasesLimit = $selectedJudges[$rowJudge][3];
                                                        $oldCasesLimit = $selectedJudges[$rowJudge][4];
                                                        $freshCasesListed = $selectedJudges[$rowJudge][5];
                                                        $oldCasesListed = $selectedJudges[$rowJudge][6];

                                                        // echo "<br>In verify before<br>";
                                                        $checked_before_verify_exploded = explode(",", $checked_before_verify);
                                                        $judge_group_indv_exploded = explode(",", $judge_group_indv);
                                                        $result_intersected = array_intersect($judge_group_indv_exploded, $checked_before_verify_exploded);
                                                        //print_r($result_intersected);
                                                        //pr($checked_before_verify_exploded);
                                                        
                                                        if (count($checked_before_verify_exploded) == count($result_intersected)) {
                                                            //  echo "<br>before count matched:<br>";
                                                            //exit(0);
                                                            //if ($result_intersected == true) {
                                                            $tobelisted = 1;
                                                            // $checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                                                            $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                            $result_before_not = array_intersect($judge_group_indv_exploded, $checked_notbefore_verify_exploded);
                                                            //pr($result_before_not);
                                                            if (count($result_before_not) === 0) {
                                                                // echo "<br/>List before count result_before_not :<br/>";
                                                                //echo "<br/>";
                                                                $may_i_list = 'N';
                                                                if ($freshCasesListed < $freshCasesLimit AND $listorder == "32") {
                                                                    $may_i_list = "Y";
                                                                    // echo "Fresh cases listed";
                                                                } else if ($oldCasesListed < $oldCasesLimit AND $listorder != "32") {
                                                                    $may_i_list = "Y";
                                                                    //echo "old cases listed";
                                                                }
                                                                //if ($may_i_list == "Y" OR $ifMandatoryCases == 1) {
                                                                if ($may_i_list == "Y" OR $listorder == 4 OR $listorder == 5 OR $listorder == 25 OR $listorder == 7 OR $row_c['subhead'] == 824 OR $row_c['advocate_id'] > 0) {
                
                                                                    
                                                                    q_from_heardt_to_last_heardt($q_diary_no);
                                                                    $total_case_listed = f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, $q_brd_slno, $rosterId, $judge_group_indv, $q_usercode, $md_module_id, $main_supp, $mainhead, $cat1);
                                                                    //echo "<br/>List before:<br/>";
                                                                    // print_r($judge_group_indv);
                                                                    $total_case_listed_plus += $total_case_listed;
                                                                    $finally_case_listed = 1;
                                                                    if ($finally_case_listed == 1) {
                                                                        if ($listorder == "32") {
                                                                            $selectedJudges[$rowJudge][5] += 1;
                                                                        }
                                                                        if ($listorder != "32") {
                                                                            $selectedJudges[$rowJudge][6] += 1;
                                                                        }
                                                                        $finally_listed = 1;
                                                                    }
                                                                    break;
                
                                                                } else {
                                                                    if ($cat1 == 239) {
                                                                        if (!if_three_judge_cat_coram($judge_group_indv)) {
                                                                            insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Matter not listed due to 3jj bench not available in before judge');
                                                                            
                                                                            $finally_listed = 1;
                                                                            break;
                                                                        }
                                                                    }
                
                
                                                                    // echo "<br/>checking List before:<br/>";
                                                                    //print_r($judge_group_indv);
                
                                                                    $may_i_list = 'N';
                                                                    if ($freshCasesListed < $freshCasesLimit AND $listorder == "32") {
                                                                        $may_i_list = "Y";
                                                                        // echo "Fresh cases listed";
                                                                    } else if ($oldCasesListed < $oldCasesLimit AND $listorder != "32") {
                                                                        $may_i_list = "Y";
                                                                        //echo "old cases listed";
                                                                    }
                                                                    //if ($may_i_list == "Y" OR $ifMandatoryCases == 1) {
                                                                    if ($may_i_list == "Y" OR $listorder == 4 OR $listorder == 5 OR $listorder == 7 OR $listorder == 25 OR $row_c['subhead'] == 824 OR $row_c['advocate_id'] > 0) {
                                                                        //q_from_heardt_to_last_heardt($q_diary_no);
                                                                        $total_case_listed = f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, $q_brd_slno, $rosterId, $judge_group_indv, $q_usercode, $md_module_id, $main_supp, $mainhead, $cat1);
                                                                        //echo "<br/>Listed before<br>";
                                                                        //  print_r($judge_group_indv);
                                                                        $total_case_listed_plus += $total_case_listed;
                                                                        $finally_case_listed = 1;
                                                                    }
                
                
                                                                    if ($finally_case_listed == 1) {
                                                                        if ($listorder == "32") {
                                                                            $selectedJudges[$rowJudge][5] += 1;
                                                                        }
                                                                        if ($listorder != "32") {
                                                                            $selectedJudges[$rowJudge][6] += 1;
                                                                        }
                                                                        $finally_listed = 1;
                                                                        break;
                                                                    }
                                                                }
                
                                                            }
                                                            break;
                                                        } else {
                                                            /*  echo "<br>before Count Not Matched";
                                                            echo "<br/>Eliminated Beacuse Bench Not Available :<br/>";
                                                            $finally_listed = 1;
                                                            break;
                                                            //exit(0);*/
                                                        }
                                                        
                                                        if ($rowJudge == (count($selectedJudges) - 1)) {
                                                            
                                                            insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Eliminated Due to Bench Not Available');
                                                            $finally_listed = 1;
                                                            break;
                                                        }



                                                    }


                                                }    

                                            } //if ($finally_listed == 0)



                                            if ($row_c['listed_by'] == 'coram' AND $finally_listed == 0) { //Coram allocation start


                                                //$note_category_coram = " Coram : " . $row_c['coram'] . "<br>  ";
                                                //$checked_before_verify = check_list_before($dairy_with_conn_k, 'B');
                                                $doNotList = 0;
                                                for ($rowCoram = 0; $rowCoram < count($coram_ex) && $finally_listed == 0; $rowCoram++) {
                                                    // echo "rowCoram" . $rowCoram . ": Value:" . $coram_ex[$rowCoram];
                                                    // print_r($coram_ex);
                                                    $checkJudgeInCoram = $coram_ex[$rowCoram];
                                                    if ($checkJudgeInCoram > 0 && $checkJudgeInCoram != null) {
                                                        // echo "<br/>checkJudgeInCoram: " . $checkJudgeInCoram;
                                                        // echo "<br/>notSelectedJudges:";
                                                        // print_r($notSelectedJudges);
                                                        if (checkIfJudgeNotSelected($notSelectedJudges, $checkJudgeInCoram) && !checkIfJudgeNotSelected($selectedJudges, $checkJudgeInCoram) && $finally_listed == 0) {
                                                            //echo "<br/>Coram Judge Available but not selected in before:" . $checkJudgeInCoram . " Diary:" . $dairy_with_conn_k . "<br/>";
                                                            $finally_listed = 1;
                                                            insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Due to Judge Unselected.');
                                                            //echo "Due to Judge Unselected.<br>";
                                                            break;
                                                        }
                                                    }
                                                }
                                                /*exit(0);*/
                
                
                                                if ($finally_listed == 0) {
                
                                                    // echo "<br>Inside if doNotList <br>";
                                                    // print_r($selectedJudges);
                
                                                    for ($rowJudge = 0; $rowJudge < count($selectedJudges); $rowJudge++) {
                                                        //    echo "<br>";
                                                        $rosterId = $selectedJudges[$rowJudge][0];
                                                        $judge_group_indv = $selectedJudges[$rowJudge][1];
                                                        $freshCasesLimit = $selectedJudges[$rowJudge][3];
                                                        $oldCasesLimit = $selectedJudges[$rowJudge][4];
                                                        $freshCasesListed = $selectedJudges[$rowJudge][5];
                                                        $oldCasesListed = $selectedJudges[$rowJudge][6];
                                                        // echo "<br>";
                                                        //echo $coram;
                                                        // echo "<br>";
                                                        //if before entry available
                                                        
                                                        if ($coram != null and $coram != 0 and $coram != '') {
                                                            //echo "In coram" . $coram . " <br/>";
                
                                                            $coram_exploaded = explode(",", $coram);
                                                            for ($judgeIndex = 0; $judgeIndex < count($coram_exploaded); $judgeIndex++) {
                                                                $checkJudgeInCoram = $coram_exploaded[$judgeIndex];
                                                                //echo "checkJudgeInCoram".$checkJudgeInCoram;
                                                                if ($checkJudgeInCoram > 0 && $checkJudgeInCoram != null){
                
                                                                // echo "<br/>Coram Selected Judge : ";
                                                                    // print_r($selectedJudges);
                                                                    for ($judgeForCoram = 0; $judgeForCoram < count($selectedJudges); $judgeForCoram++) {
                
                
                                                                        $rosterId = $selectedJudges[$judgeForCoram][0];
                                                                        $judgeForCoram_presiding_indv = explode(",", $selectedJudges[$judgeForCoram][1])[0];
                
                                                                        $judge_group_indv = $selectedJudges[$judgeForCoram][1];
                                                                        $freshCasesLimit = $selectedJudges[$judgeForCoram][3];
                                                                        $oldCasesLimit = $selectedJudges[$judgeForCoram][4];
                
                                                                        $freshCasesListed = $selectedJudges[$judgeForCoram][5];
                                                                        $oldCasesListed = $selectedJudges[$judgeForCoram][6];
                
                                                                        // echo "<br/>freshCasesListed:" . $freshCasesListed;
                                                                        // echo "=freshCasesLimit:" . $freshCasesLimit;
                
                                                                        // echo "<br/>oldCasesListed:" . $oldCasesListed;
                                                                        // echo "=oldCasesLimit:" . $oldCasesLimit . "<br/>";
                                                                        //First check presiding in all court
                                                                        //  echo "checkJudgeInCoram:" . $checkJudgeInCoram;
                                                                        //  echo "<br/>judgeForCoram_presiding_indv:" . $judgeForCoram_presiding_indv;
                
                                                                        if ($checkJudgeInCoram == $judgeForCoram_presiding_indv) {
                
                                                                            //echo "Coram matched" . $selectedJudges[$judgeForCoram][0] . "</br>";
                
                                                                            // echo "<br>Inside if<br>";
                                                                            $may_i_list = 'N';
                                                                            if ($freshCasesListed < $freshCasesLimit AND $listorder == "32") {
                                                                                $may_i_list = "Y";
                                                                                // echo "Fresh cases listed";
                                                                            } else if ($oldCasesListed < $oldCasesLimit AND $listorder != "32") {
                                                                                $may_i_list = "Y";
                                                                                //echo "old cases listed";
                                                                            }
                
                                                                            //if ($may_i_list == "Y" OR $ifMandatoryCases == 1) {
                                                                            if ($may_i_list == "Y" OR $listorder == 4 OR $listorder == 5 OR $listorder == 7 OR $listorder == 25 OR $row_c['subhead'] == 824 OR $row_c['advocate_id'] > 0) {
                                                                                if ($cat1 == 239) {
                                                                                    if (!if_three_judge_cat_coram($selectedJudges[$judgeForCoram][1])) {
                                                                                        insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Matter not listed due to 3jj bench not available in coram judge');
                                                                                        // echo "<br/>Eliminated due to 3JJ";
                                                                                        $finally_listed = 1;
                                                                                        break(3);
                                                                                    }
                                                                                }
                
                                                                                // echo "<br> Diary_No with Connected : ".$dairy_with_conn_k."<br>";
                                                                                $judgeForCoram_indv_to_convert_into_array = explode(",", $selectedJudges[$judgeForCoram][1]);
                                                                                //$checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                                                                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                                                //  echo "<br> CC : ";
                                                                                //  print_r($checked_notbefore_verify_exploded);
                                                                                //   echo "<br> SJ : ";
                                                                                //   print_r($judgeForCoram_indv_to_convert_into_array);
                                                                                //   echo "<br>";
                                                                                $result_array_intersect = array_intersect($judgeForCoram_indv_to_convert_into_array, $checked_notbefore_verify_exploded);
                                                                                //echo " CNT : " . count($result_array_intersect);
                                                                                if (count($result_array_intersect) > 0) {
                                                                                    //  echo "<br>presiding coram not listed due to not before<br>";
                                                                                    //  echo "<br/>Eliminated due to Not Before<br>";
                                                                                    //$finally_listed = 1;
                                                                                    // break(3);
                                                                                } else {
                                                                                    // echo "<br>Listed through coram sec1 <br>";
                                                                                    q_from_heardt_to_last_heardt($q_diary_no);
                                                                                    $total_case_listed = f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, $q_brd_slno, $rosterId, $judge_group_indv, $q_usercode, $md_module_id, $main_supp, $mainhead, $cat1);
                                                                                    // echo "<br/>Listed Coram:" . $coram . "<br>";
                                                                                    // print_r($judge_group_indv);
                
                                                                                    $total_case_listed_plus += $total_case_listed;
                                                                                    $finally_case_listed = 1;
                                                                                    if ($finally_case_listed == 1) {
                                                                                        if ($listorder == "32") {
                                                                                            $selectedJudges[$judgeForCoram][5] += 1;
                                                                                        }
                                                                                        if ($listorder != "32") {
                                                                                            $selectedJudges[$judgeForCoram][6] += 1;
                                                                                        }
                                                                                        $finally_listed = 1;
                                                                                    }
                                                                                    break(3);
                                                                                }
                                                                            } else {
                                                                                insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','DUE TO EXCESS MATTERS');
                                                                                // echo "<br/>Eliminated Coram:";
                                                                                $finally_listed = 1;
                                                                                break(3);
                                                                            }
                                                                        }
                                                                    }
                                                                    if ($finally_listed == 0) {
                                                                        //  echo "IF Coram Presiding Not Avl :<br>";
                                                                        for ($judgeForCoram = 0; $judgeForCoram < count($selectedJudges); $judgeForCoram++) {
                                                                            //echo "<br>Coram matching <br>" . $selectedJudges[$judgeForCoram][0] . "</br>";
                                                                            $rosterId = $selectedJudges[$judgeForCoram][0];
                                                                            $judge_group_indv_exploded = explode(",", $selectedJudges[$judgeForCoram][1]);
                
                                                                            $judge_group_indv = $selectedJudges[$judgeForCoram][1];
                                                                            $freshCasesLimit = $selectedJudges[$judgeForCoram][3];
                                                                            $oldCasesLimit = $selectedJudges[$judgeForCoram][4];
                
                                                                            $freshCasesListed = $selectedJudges[$judgeForCoram][5];
                                                                            $oldCasesListed = $selectedJudges[$judgeForCoram][6];
                
                                                                            // echo "<br/>freshCasesListed:" . $freshCasesListed;
                                                                            //  echo "=freshCasesLimit:" . $freshCasesLimit;
                
                                                                            //  echo "<br/>oldCasesListed:" . $oldCasesListed;
                                                                            //  echo "=oldCasesLimit:" . $oldCasesLimit . "<br/>";
                                                                            //First check presiding in all court
                                                                            // echo "checkJudgeInCoram:" . $checkJudgeInCoram;
                                                                            $checkJudgeInCoramArray = array();
                                                                            $checkJudgeInCoramArray[] = $checkJudgeInCoram . "<br/>";
                
                                                                            // echo "judge_group_indv_exploded<br/>";
                                                                            //  print_r($judge_group_indv_exploded);
                                                                            //  echo "<br>";
                                                                            //  echo "Check Judge in coram<br/>";
                                                                            //  print_r($checkJudgeInCoram);
                                                                            //  echo "<br>";
                                                                            //$result_intersected = array_intersect($judge_group_indv_exploded, $checkJudgeInCoramArray);
                                                                            $result_intersected = in_array($checkJudgeInCoram, $judge_group_indv_exploded);
                                                                            // echo "result_intersected" . $result_intersected;
                                                                            // echo "<br>";
                                                                            if (in_array($checkJudgeInCoram, $judge_group_indv_exploded)) {
                                                                                //      echo "not presiting Inside if";
                                                                                //exit(0);
                
                
                                                                                //$checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                                                                                $checked_notbefore_verify_exploded = explode(",", $checked_notbefore_verify);
                                                                                $result_array_intersect = array_intersect($judge_group_indv_exploded, $checked_notbefore_verify_exploded);
                                                                                if (count($result_array_intersect) > 0) {
                                                                                    // echo "<br/>Eliminated due to Not Before in non presiding judge<br>";
                                                                                    // $finally_listed = 1;
                                                                                    // break(3);
                                                                                } else {
                                                                                    $may_i_list = 'N';
                                                                                    if ($freshCasesListed < $freshCasesLimit AND $listorder == "32") {
                                                                                        $may_i_list = "Y";
                                                                                        //  echo "<br>Fresh may_i_list = Y :<br>";
                                                                                        // echo "Fresh cases listed";
                                                                                    } else if ($oldCasesListed < $oldCasesLimit AND $listorder != "32") {
                                                                                        $may_i_list = "Y";
                                                                                        // echo "<br>OLD may_i_list = Y :<br>";
                                                                                        //echo "old cases listed";
                                                                                    }
                                                                                    //if ($may_i_list == "Y" OR $ifMandatoryCases == 1) {
                                                                                    if ($may_i_list == "Y" OR $listorder == 4 OR $listorder == 5 OR $listorder == 7 OR $listorder == 25 OR $row_c['subhead'] == 824 OR $row_c['advocate_id'] > 0) {
                                                                                        if ($cat1 == 239) {
                                                                                            if (!if_three_judge_cat_coram($selectedJudges[$judgeForCoram][1])) {
                                                                                                insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Matter not listed due to 3jj bench not available in coram judge');
                                                                                                //echo "<br/>Eliminated due to 3JJ";
                                                                                                $finally_listed = 1;
                                                                                                break(3);
                                                                                            }
                                                                                        }
                                                                                        //echo "<br>Listed through coram<br>";
                                                                                        q_from_heardt_to_last_heardt($q_diary_no);
                                                                                        $total_case_listed = f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, $q_brd_slno, $rosterId, $judge_group_indv, $q_usercode, $md_module_id, $main_supp, $mainhead, $cat1);
                                                                                        // echo "<br/>Listed Coram:" . $coram;
                                                                                        // print_r($judge_group_indv);
                
                                                                                        $total_case_listed_plus += $total_case_listed;
                                                                                        $finally_case_listed = 1;
                                                                                        if ($finally_case_listed == 1) {
                                                                                            if ($listorder == "32") {
                                                                                                $selectedJudges[$judgeForCoram][5] += 1;
                                                                                            }
                                                                                            if ($listorder != "32") {
                                                                                                $selectedJudges[$judgeForCoram][6] += 1;
                                                                                            }
                                                                                            $finally_listed = 1;
                                                                                        }
                                                                                        break(3);
                                                                                    } else {
                                                                                        insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','DUE TO EXCESS MATTERS');
                                                                                        // echo "<br/>Eliminated Coram:<br>";
                                                                                        $finally_listed = 1;
                                                                                        break(3);
                                                                                    }
                                                                                }
                
                
                                                                            }
                
                                                                        }
                                                                    }
                                                                }
                                                            }
                
                
                
                                                        }
                                                    }
                
                                                    if($finally_listed==0 AND $coram != null and $coram != 0 and $coram != ''){
                                                        //echo "<br>Eliminated coram due to judge not available on $q_next_dt <br>";
                                                        $finally_listed = 1;
                                                        //break;
                                                    }
                                                }
                                            }

                                            if (($row_c['listed_by'] == 'category' OR $finally_listed == 0) AND $checked_before_verify == "") {//category allocation start
                                                // echo "<br> Category if or not listed by coram :<br>";
                                                //if($misc_nmd_flag == 1 AND in_array($row_c['submaster_id'], $short_categoary_array) ){
                                                if($row_c['submaster_id']==341){ //Arbitration Matter 1101
                                                    $possible_judges = $selected_presiding_judges;
                                                    //$possible_judges = explode(",", $selected_presiding_judges);
                                                    //Assign chief code in possible_judge here for 1101 category
                                                    //print_r($possible_judges);
                                                    $cji_code = f_get_cji_code();
                                                    if(in_array($cji_code, explode(",", $selected_presiding_judges))){
                                                        $possible_judges=$cji_code;
                                                    } else{
                                                        insert_eliminated_cases($q_diary_no, $q_next_dt, $board_type, 'F', 'Special Category(1101) to CJI.');
                                                        $finally_listed = 1;
                                                    // break;
                                                    }
                                                    //}
                                                    //END
                
                                                }
                                                else{
                                                    $possible_judges = $row_c['judge_id_on_rid'];
                                                }
                                                if ($possible_judges != null) {
                                                    $possible_judges = judge_seniority_reset($possible_judges);
                                                }
                                                //echo "<br>after sorted possible Judge : " . $possible_judges . "<br>";
                                                $ro_ct2 = $row_c;
                                                // $note_category_coram = "<br> category : " . $row_c['submaster'] . " <br> ";
                                                #################category Start
                                                //LIST AS PER CATEGORY
                                                if ($possible_judges != null) {
                                                    // echo "<br> if possible judge not blank $dairy_with_conn_k <br>";
                                                    $possible_judges_exploded = explode(",", $possible_judges);
                                                    //$checked_notbefore_verify = check_list_before($dairy_with_conn_k, 'N');
                
                
                
                                                    //Check limit and category allocation
                                                    $remaining_judge_group = $possible_judges_exploded;
                                                    //print_r($remaining_judge_group);
                                                    // print_r($selectedJudges);
                                                    // echo "<br>".$listorder."<br>";
                                                    // if($ifMandatoryCases!=1){
                                                    //$remaining_judge_group = remove_exaust_limit_judge_final($selectedJudges, $possible_judges_exploded, $listorder, $ifMandatoryCases);
                                                    $remaining_judge_group = remove_exaust_limit_judge_final($selectedJudges, $possible_judges_exploded, $listorder);
                                                    //echo "<br>after checking exaust : <br>";
                                                    //print_r($remaining_judge_group);
                                                    // echo "<br> Count Array Key : " . count($remaining_judge_group) . "<br>";
                                                    // }
                                                    if (count($remaining_judge_group) > 0) {
                                                        //remove_top4_courts
                                                        //echo "<br>";
                                                        //echo $checked_notbefore_verify." categroy loop<br>";
                                                        $remaining_judge_group = remove_notbefore_judge($remaining_judge_group, $checked_notbefore_verify);
                
                                                        //echo "<br>after checking remove_notbefore_judge : <br>";
                                                        //print_r($remaining_judge_group);
                                                        if (count($remaining_judge_group) > 0) {
                                                            // echo "<br> inside ifCount<br>";
                
                                                            if ((($row_c['in_advance_list'] == 1 AND $_POST['select_advance'] == 'Y') || $_POST['select_advance']=='N') AND $row_c['allocated_j1'] != null AND $listorder != 32) {
                                                                $remaining_judge_group = msort($remaining_judge_group, '6', '1');
                                                                //  echo "After sort:";
                                                                // print_r($remaining_judge_group);
                                                                //  echo "<br>Passing Judge".$row_c['allocated_j1']."<br>";
                                                                $previously_allocated_j1_key = findInMultiDimensionalArrayInArray($remaining_judge_group,'1', $row_c['allocated_j1']);
                
                                                                // echo "<br> previously_allocated_j1_key : <br>".$previously_allocated_j1_key."<br>";
                                                                //  print_r($previously_allocated_j1_key);
                                                                moveElement($remaining_judge_group, $previously_allocated_j1_key, 0);
                                                                //echo "After Move:";
                                                                //print_r($remaining_judge_group);
                                                                $rosterId = $remaining_judge_group[0][0];
                                                                $judge_group_indv = $remaining_judge_group[0][1];
                                                                $oldCasesLimit = $remaining_judge_group[0][4];
                                                                $oldCasesListed = $remaining_judge_group[0][6];
                                                                if(($oldCasesListed < $oldCasesLimit AND $listorder != "32")){
                                                                    //if(($oldCasesListed < $oldCasesLimit AND $listorder != "32") || $ifMandatoryCases == 1){
                
                                                                }
                                                                else{
                                                                    $remaining_judge_group = msort($remaining_judge_group, '6', '1');
                                                                }
                                                                // break;
                
                                                                //$remaining_judge_group = msort($remaining_judge_group, array('1', $row_c['allocated_j1']));
                                                                // echo "<br>after checking in_advance_list : <br>";
                                                                // print_r($remaining_judge_group);
                                                                // exit(0);
                                                            } else {
                                                                //  echo "<br>before_sorting:<br>";
                                                                //  print_r($remaining_judge_group);
                                                                if ($listorder == 32) {
                                                                    // echo "<br>inif listorder: ".$listorder;
                                                                    #$remaining_judge_group = msort($remaining_judge_group, array('5', '1'));
                                                                    $remaining_judge_group = msort($remaining_judge_group, '5', '1');
                                                                } else {
                                                                    //  echo "<br>inelse listorder: ".$listorder;
                                                                    $remaining_judge_group = msort($remaining_judge_group, '6', '1');
                                                                }
                                                                //echo "<br>after checking msort : <br>";
                                                                //print_r($remaining_judge_group);
                                                            }
                
                
                                                            //$final_allocation_judges = explode(",", $remaining_judge_group[0][1]);
                
                                                            if($row_c['in_advance_list'] == 1){
                                                                $rand_remaining_judge_group_key = 0;
                                                            }
                                                            else{
                                                                //echo "<br>Sorting: ";
                                                                $count_array_remaining_judge_group = (count($remaining_judge_group)-1);
                                                                $rand_remaining_judge_group_key = rand(0,$count_array_remaining_judge_group);
                                                            }
                
                                                            $rosterId = $remaining_judge_group[$rand_remaining_judge_group_key][0];
                                                            $judge_group_indv = $remaining_judge_group[$rand_remaining_judge_group_key][1];
                                                            $freshCasesLimit = $remaining_judge_group[$rand_remaining_judge_group_key][3];
                                                            $oldCasesLimit = $remaining_judge_group[$rand_remaining_judge_group_key][4];
                                                            $freshCasesListed = $remaining_judge_group[$rand_remaining_judge_group_key][5];
                                                            $oldCasesListed = $remaining_judge_group[$rand_remaining_judge_group_key][6];
                
                
                                                            //echo "<br>judge selected to be listed judge_group_indv :" . $judge_group_indv . "<br>";
                                                            //print_r($remaining_judge_group);
                
                                                            //if ((($freshCasesListed < $freshCasesLimit AND $listorder = "32") OR ($oldCasesListed < $oldCasesLimit AND $listorder != "32")) || $ifMandatoryCases == 1) {
                                                            if ((($freshCasesListed < $freshCasesLimit AND $listorder = "32") OR ($oldCasesListed < $oldCasesLimit AND $listorder != "32") ) OR $listorder == 4 OR $listorder == 5 OR $listorder == 7 OR $listorder == 25 OR $row_c['subhead'] == 824 OR $row_c['advocate_id'] > 0) {
                                                                //echo "<br> IF to be listed <br>";
                
                                                                if ($cat1 == 239 && !if_three_judge_cat_coram($remaining_judge_group[$rand_remaining_judge_group_key][1])) {
                                                                    insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','due to 3jj coram');
                                                                    //   echo "<br/>Eliminated category due to 3jj coram<br>";
                                                                    $finally_listed = 1;
                                                                    //break;
                                                                } else {
                                                                    $judge_limit_detail_key = findInMultiDimensionalArray($selectedJudges, '0', $rosterId);
                                                                    //  echo "<br>judge_limit_detail_key : " . $judge_limit_detail_key;
                                                                    //  print_r($selectedJudges[$judge_limit_detail_key]);
                
                                                                    q_from_heardt_to_last_heardt($q_diary_no);
                                                                    $total_case_listed = f_heardt_cl_update($q_diary_no, $q_next_dt, $partno, $q_brd_slno, $selectedJudges[$judge_limit_detail_key][0], $selectedJudges[$judge_limit_detail_key][1], $q_usercode, $md_module_id, $main_supp, $mainhead, $cat1);
                                                                    //echo "<br/>Listed category " . $submaster_id . "<br>";
                                                                    //print_r($selectedJudges[$judge_limit_detail_key][1]);
                                                                    $total_case_listed_plus += $total_case_listed;
                                                                    $finally_case_listed = 1;
                                                                    if ($finally_case_listed == 1) {
                                                                        if ($listorder == "32") {
                                                                            $selectedJudges[$judge_limit_detail_key][5] += 1;
                                                                        }
                                                                        if ($listorder != "32") {
                                                                            $selectedJudges[$judge_limit_detail_key][6] += 1;
                                                                        }
                                                                        $finally_listed = 1;
                                                                    }
                                                                }
                                                            } else {
                                                                insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','DUE TO EXCESS MATTERS');
                                                                // echo "<br/>Eliminated category" . $submaster_id . "<br>";
                                                                //break;//this break is not required because its not in for loop
                                                            }
                                                        } else {
                                                            //echo "<br/>Not Before Eliminated category" . $submaster_id . "<br>";
                                                        }
                
                
                                                    } else {
                                                        //echo "<br/>Exausted Eliminated category" . $submaster_id . "<br>";
                                                    }
                                                    //print_r($judge_limit_detail);
                                                } else {
                
                                                    insert_eliminated_cases($q_diary_no,$q_next_dt,$board_type,'F','Category Not Allocated to any Judge');
                                                    //echo "<br/>Eliminated category" . $submaster_id . "<br>";
                                                    //break; //this break is not required because its not in for loop
                                                }
                                            }  
                                        }//Mandatory or in advance list completed here
                                
                                    }//End Of Loop
                                } else {
                                    /*echo "Records Not Found FOR CORAM";*/
                                }
                                
                                //END OF LIST ACCORDING TO CORAM

                    }  //end of while purpose of listing   
                    
                    //start reshuffle   
                    if ($total_case_listed_plus >= 1) {
                        echo "<br/><div style='text-align: center;'><span style='color:green;'><b>Allocation Done Successfully.</b></span></div>";
                        $eliminate_print = "SELECT m.diary_no, m.conn_key, m.reg_no_display, m.res_name, m.pet_name, l.purpose, e.reason, jc.judge_coram FROM eliminated_cases e INNER JOIN main m ON m.diary_no = e.diary_no LEFT JOIN master.listing_purpose l ON l.code = e.listorder LEFT JOIN heardt h ON e.diary_no = h.diary_no LEFT JOIN LATERAL ( SELECT STRING_AGG(j.abbreviation, ', ' ORDER BY j.judge_seniority) AS judge_coram FROM master.judge j WHERE POSITION(j.jcode::TEXT IN COALESCE(h.coram, '')) > 0 AND j.display = 'Y' AND j.is_retired = 'N') jc ON true WHERE c_status = 'P' AND next_dt_old = '$q_next_dt' AND e.listtype = 'F' AND e.board_type = 'J' AND e.reason NOT IN ('DUE TO EXCESS MATTERS', 'Due to Judge Unselected.') AND h.clno = 0 GROUP BY e.diary_no, m.diary_no, m.conn_key, m.reg_no_display, m.res_name, m.pet_name, l.purpose, e.reason, jc.judge_coram ORDER BY m.diary_no_rec_date";
                        
                        $res_eliminate_print = $this->db->query($eliminate_print);
                        
                        if ($res_eliminate_print->getNumRows() > 0) {
                            
                            ?>
                            <div class="row">
                            <div class="col-md-12">
                            <div class="card-body">
                            <div id="prnnt" style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">
                                ON <?php echo $_POST['list_dt']; ?>, NOT LISTED IN FINAL LIST DUE TO COMPELLING REASONS</span>
                                <div class="table-responsive">
                                <table id="customers" align="center" class="table table-striped custom-table">
                                    <table id="customers" width="100%" class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th width="6%" style="text-align: center; font-weight: bold;">SNo</th>
                                            <th width="22%" style="text-align: center; font-weight: bold;">Case No./Diary No.</th>
                                            <th width="22%" style="text-align: center; font-weight: bold;">Cause Title</th>
                                            <th width="10%" style="text-align: center; font-weight: bold;">Coram</th>
                                            <th width="10%" style="text-align: center; font-weight: bold;">Purpose of Listing</th>
                                            <th width="15%" style="text-align: center; font-weight: bold;">Reason</th>
                                            <th width="15%" style="text-align: center; font-weight: bold;">Before / Not Before</th>
                                        </tr>
                                        <thead>
                                        <tbody>
                                        <?php
                                        $sno_elimi = 1;
                                        $row_eliminate_prints = $res_eliminate_print->getResultArray();
                                        
                                        foreach($row_eliminate_prints as $row_eliminate_print) {
                                            ?>
                                            <tr>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $sno_elimi++; ?> </td>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['reg_no_display']." @ ".$row_eliminate_print['diary_no']; ?> </td>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['res_name']." <br>Vs<br> ".$row_eliminate_print['pet_name']; ?> </td>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['judge_coram']; ?> </td>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['purpose']; ?> </td>
                                                <td style='text-align: left; vertical-align: top;'> <?php echo $row_eliminate_print['reason']; ?> </td>
        
                                                <td style='text-align: left; vertical-align: top;'>
                                                    <?php
                                                    if ($row_eliminate_print['diary_no'] == $row_eliminate_print['conn_key']) {
                                                        $dairy_with_conn_k = f_cl_conn_key($row_eliminate_print['diary_no']);
                                                    } else {
                                                        $dairy_with_conn_k = $row_eliminate_print['diary_no'];
                                                    }
                                                    
                                                    $sql_nnn = "SELECT abc.*, string_agg(j.abbreviation, ',' ORDER BY j.judge_seniority) AS judge_name FROM (SELECT a.diary_no::bigint, org_judge_id AS j1, 'N' AS nb_remark FROM advocate a INNER JOIN master.ntl_judge n ON n.org_advocate_id = a.advocate_id WHERE a.diary_no IN ($dairy_with_conn_k) AND n.display = 'Y' AND a.display = 'Y' UNION SELECT a.diary_no::bigint,  n.org_judge_id AS j1, 'N' AS nb_remark FROM party a INNER JOIN master.ntl_judge_dept n ON a.deptcode = n.dept_id WHERE a.diary_no IN ($dairy_with_conn_k) AND a.pflag != 'T' AND n.display = 'Y' UNION SELECT n.diary_no::bigint, n.j1, n.notbef AS nb_remark FROM not_before n INNER JOIN master.judge j ON j.jcode = n.j1 WHERE n.diary_no IN ('$dairy_with_conn_k') AND j.is_retired = 'N' UNION SELECT diary_no::bigint, n.org_judge_id AS j1, 'N' AS nb_remark FROM (SELECT diary_no::bigint, s.id FROM (SELECT c.diary_no, s.id, sub_name1 FROM mul_category c, master.submaster s WHERE s.id = c.submaster_id AND c.diary_no IN ($dairy_with_conn_k) AND c.display = 'Y' AND s.display = 'Y') a INNER JOIN master.submaster s ON s.sub_name1 = a.sub_name1 WHERE flag = 's') a INNER JOIN master.ntl_judge_category n ON n.cat_id = a.id WHERE n.display = 'Y') abc INNER JOIN (SELECT DISTINCT j.abbreviation, j.jcode, j.judge_seniority FROM master.judge j WHERE j.is_retired = 'N') j ON j.jcode = abc.j1 GROUP BY abc.diary_no, abc.nb_remark, abc.j1 ORDER BY abc.nb_remark, abc.diary_no";
                                                    $res_nnn = $this->db->query($sql_nnn);
                        
                                                    if ($res_nnn->getNumRows() > 0) {
                                                        $res_nnn_result = $res_nnn->getResultArray();
                                                        foreach($res_nnn_result as $row_nnn ) {
                                                            if($row_nnn['nb_remark'] == 'N')
                                                            {
                                                                echo "<span style='color:red;'>Not Before : </span>";
                                                            }
                                                            if($row_nnn['nb_remark'] == 'B')
                                                            {
                                                                echo "<span style='color:green;'>Before : </span>";
                                                            }
                                                            echo $row_nnn['judge_name']." in diary no. ".$row_nnn['diary_no']."<br>";
                                                        }
                                                    }
        
        
                                                    ?>
                                                </td>        
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tbody>
                                    </table>
                                </div>    
                            </div>
                            <input name="prnnt1" type="button" id="prnnt1" value="Print" >
                            </div>
                            </div>
                            </div>
                            <?php
                        }
        
                    } else {
                        echo "<br/><div style='text-align: center;'><span style='color:red;'><b>Not Allocated.</b></span></div>";
                    }

                    $output = '';
                    if ($output) {

                        ?>
                        <table border="0" width="70%" style="font-size:11px; text-align: left; background: #ffffff;" cellspacing=0>
                            <tr>
                                <td><b>Not Listed Reason with Diary Numbers</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    echo "<br>" . $output;
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }

                    for ($i = 0; $i < count($explode_rs); $i++) {
                        if(!empty($explode_rs[$i])){
                            $explode_rs_jg = explode("|", $explode_rs[$i]);
                            f_cl_reshuffle($q_next_dt, $explode_rs_jg[0], $mainhead, $partno, $explode_rs_jg[1]);
                        }
                    }
                    //end reshuffle


                } //end if cl printed
                else {
                    echo "<br/><span style='color:red;'>YOU CAN NOT ALLOT CASES IN SESSION $partno. BECAUSE SESSION $partno FINALIZED.</span>";
                }
            } //else of freeze
        } else {
            echo "<span style='color:red;'><b>Please select working day / Contact to Computer Cell.</b></span><br>";
        }    
    }

    public function get_section_autoc($section, $actid) {
        $json = [];

        if ($actid == "all") {
            $json[] = ['value' => "", 'label' => ""];
        } else {
            $builder = $this->db->table('act_main a')
                ->select('b.section')
                ->join('master.act_section b', 'a.id = b.act_id')
                ->whereIn('a.act', explode(',', $actid))
                ->where('a.display', 'Y')
                ->where('b.display', 'Y')
                ->like('b.section', $section)
                ->groupBy('b.section');
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                foreach ($query->getResultArray() as $row) {
                    $json[] = [
                        'value' => $row['section'],
                        'label' => $row['section']
                    ];
                }
            }
            return json_encode($json);
        }

    }


}
