<?php
include("../../includes/db_inc.php");
session_start();
$updation_method = !empty($_POST['updation_method']) ? trim($_POST['updation_method']) : null;
$output= array();
if(isset($updation_method) && !empty($updation_method)){
    $diary_no = !empty($_POST['diary_no']) ? (int)trim($_POST['diary_no']) : NULL;
    $conn_key = !empty($_POST['conn_key']) ? (int)trim($_POST['conn_key']) : NULL;
    $roster_id = !empty($_POST['roster_id']) ? (int)trim($_POST['roster_id']) : NULL;
    $court_no = !empty($_POST['courtno']) ? (int)trim($_POST['courtno']) : NULL;
    $judges = !empty($_POST['judges']) ? trim($_POST['judges']) : NULL;
    $main_supp_flag = !empty($_POST['main_supp_flag']) ? (int)trim($_POST['main_supp_flag']) : NULL;
    $part_no = !empty($_POST['clno']) ? (int)trim($_POST['clno']) : NULL;
    $mainhead = !empty($_POST['mainhead']) ? trim($_POST['mainhead']) : NULL;
    $board_type = !empty($_POST['board_type']) ? trim($_POST['board_type']) : NULL;
    $current_date =date('Y-m-d H:i:s');
    $next_dt = !empty($_POST['next_dt']) ? date('Y-m-d',strtotime(trim($_POST['next_dt']))) : NULL;
    $entry_date = !empty($_POST['entry_date']) ? date('Y-m-d',strtotime(trim($_POST['entry_date']))) : $current_date;
     // $entry_date = date("Y-m-d", strtotime("- 2 day"));
    $update_flag = !empty($_POST['update_flag']) ? trim($_POST['update_flag']) : NULL;
    $from_time = NULL;
    $to_time = NULL;
    $list_number = 0;
    $list_type_id = 0;
    if(isset($mainhead) && !empty($mainhead) && isset($board_type) && !empty($board_type)){
        if($mainhead == 'M' && $board_type == 'J'){
            $list_type_id = 4;
        }
        else if($mainhead == 'M' && $board_type == 'C'){
            $list_type_id = 5;
        }
        else if($mainhead == 'M' && $board_type == 'R'){
            $list_type_id = 6;
        }
        else if($mainhead == 'F' && $board_type == 'J'){
            $list_type_id = 3;
        }
    }
    $list_year =!empty($_POST['next_dt']) ? date('Y',strtotime(trim($_POST['next_dt']))) : NULL;
    $user_id =  $_SESSION['dcmis_user_idd'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $count_success=0;$count_error=0; $successArr = [];
    $previousState = false;
    switch ($updation_method){
        case 'single':
            if(isset($_POST['diary_no']) && !empty($_POST['diary_no'])) {
                $from_dt = $next_dt;
                $to_dt = $next_dt;
                $sqlQuery ="";
                $sqlQuery="select diary_no,conn_key,consent,from_dt,mainhead,board_type,roster_id,main_supp_flag,part_no,judges,entry_date from hybrid_physical_hearing_consent where mainhead='$mainhead'  and date(from_dt) = '$next_dt' and board_type='$board_type'
                            and judges='$judges' and main_supp_flag=$main_supp_flag and part_no=$part_no and diary_no=$diary_no  ";
                $resultSet =mysql_query($sqlQuery) or die(mysql_error());
                if(mysql_num_rows($resultSet) > 0){
                    if($conn_key != null and $conn_key > 0){
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent_log 
                    (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                    list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                     updated_by,updated_date,updated_user_ip)
                    select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' from 
                    hybrid_physical_hearing_consent a where a.conn_key = '".$conn_key."' ";
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "delete from hybrid_physical_hearing_consent where conn_key =$conn_key and from_dt = '$next_dt' ";
                        $rs = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt,
                        list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges)
                    select m.diary_no, m.conn_key, '".$update_flag."',  '" . $from_time. "',   '" . $to_time . "', '" . $from_dt . "', '" . $to_dt . "', 
                    '" . $list_type_id . "', '" . $list_number . "', '" . $list_year. "', '" . $mainhead. "', '" . $board_type . "', '" . $user_id. "','".$current_date."',
                     '" . $user_ip . "' ,'" . $court_no . "', '" . $roster_id . "', '" . $main_supp_flag . "', '" . $part_no . "','".$judges."'
                         from 
                        (select m.* from main m where m.conn_key = '".$conn_key."' and c_status = 'P'
                        union
                        select m.* from main m
                        inner join conct ct on ct.conn_key = m.conn_key
                        where m.conn_key = '".$conn_key."' and ct.list = 'Y' and m.c_status = 'P') m
                        inner join heardt h on h.diary_no = m.diary_no 
                        where h.clno > 0 and h.next_dt = '" . $next_dt . "'";
                        $rs = mysql_query($sql);
                        $afros = mysql_affected_rows();
                        if ($afros > 0) {
                            $output = array("status" => "success","diary_no"=>$diary_no,"entry_date"=>$entry_date);

                        } else {
                            $output = array("status" => "Error:Not Saved");
                        }
                    }
                    else{
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent_log 
                        (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                        list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                         updated_by,updated_date,updated_user_ip)
                        select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' 
                        from hybrid_physical_hearing_consent a where diary_no = " . $diary_no;
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "delete from hybrid_physical_hearing_consent where diary_no =$diary_no and  from_dt = '$next_dt' ";
                        $rs = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, 
                        to_dt, list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,
                        part_no,judges)
                        VALUES ('" . $diary_no . "', '" . $conn_key . "', '".$update_flag."', '" . $from_time . "','" . $to_time . "', '" . $from_dt . "', 
                        '" . $to_dt . "', '" . $list_type_id . "',  '" . $list_number . "', '" . $list_year . "', '" . $mainhead . "', '" . $board_type . "', 
                        '".$user_id."', '".$current_date."','".$user_ip."','".$court_no."','" . $roster_id . "','" . $main_supp_flag . "', 
                        '" . $part_no . "' ,'".$judges."')";
                       // echo $sql; exit;
                        $rs = mysql_query($sql);
                        $afros = mysql_affected_rows();
                        if ($afros > 0) {
                            $output = array("status" => "success","diary_no"=>$diary_no,"entry_date"=>$current_date);
                        } else {
                            $output = array("status" => "Error:Not Saved");
                        }
                    }

                }
                else{
                    if($conn_key != null and $conn_key > 0){
                    $sql="";
                    $sql = "insert into hybrid_physical_hearing_consent_log 
                    (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                    list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                     updated_by,updated_date,updated_user_ip)
                    select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' from 
                    hybrid_physical_hearing_consent a where a.conn_key = '".$conn_key."' ";
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt,
                        list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,
                        judges)
                        select m.diary_no, m.conn_key, '".$update_flag."',  '" . $from_time. "',   '" . $to_time . "', '" . $from_dt . "', '" . $to_dt . "', 
                        '" . $list_type_id . "', '" . $list_number . "', '" . $list_year. "', '" . $mainhead. "', '" . $board_type . "', '" . $user_id. "','".$current_date."',
                         '" . $user_ip . "' ,'" . $court_no . "', '" . $roster_id . "', '" . $main_supp_flag . "', '" . $part_no . "','".$judges."'
                         from 
                        (select m.* from main m where m.conn_key = '".$conn_key."' and c_status = 'P'
                        union
                        select m.* from main m
                        inner join conct ct on ct.conn_key = m.conn_key
                        where m.conn_key = '".$conn_key."' and ct.list = 'Y' and m.c_status = 'P') m
                        inner join heardt h on h.diary_no = m.diary_no 
                        where h.clno > 0 and h.next_dt = '" . $next_dt . "'";
                        $rs = mysql_query($sql);
                        $afros = mysql_affected_rows();
                        if ($afros > 0) {
                            $output = array("status" => "success","diary_no"=>$diary_no,"entry_date"=>$current_date);
                        } else {
                            $output = array("status" => "Error:Not Saved");
                        }
                    }
                    else{
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent_log 
                        (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                        list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                         updated_by,updated_date,updated_user_ip)
                        select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' 
                        from hybrid_physical_hearing_consent a where diary_no = " . $diary_no;
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, 
                        to_dt, list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,
                        part_no,judges)
                        VALUES ('" . $diary_no . "', '" . $conn_key . "', '".$update_flag."', '" . $from_time . "','" . $to_time . "', '" . $from_dt . "', 
                        '" . $to_dt . "', '" . $list_type_id . "',  '" . $list_number . "', '" . $list_year . "', '" . $mainhead . "', '" . $board_type . "', 
                        '".$user_id."', '".$current_date."','".$user_ip."','".$court_no."','" . $roster_id . "','" . $main_supp_flag . "', 
                        '" . $part_no . "' ,'".$judges."')";
                        $rs = mysql_query($sql);
                        $afros = mysql_affected_rows();
                        if ($afros > 0) {
                            $output = array("status" => "success","diary_no"=>$diary_no,"entry_date"=>$current_date);
                        } else {
                            $output = array("status" => "Error:Not Saved");
                        }
                    }
                }
            }
            else{
                $output = array("status" => "Error");
            }
            break;
        case 'bulk':
            if(isset($_POST['diaryConnKeyArr']) && !empty($_POST['diaryConnKeyArr'])) {
                $diaryConnKeyArr = $_POST['diaryConnKeyArr'];
                foreach ($diaryConnKeyArr as $k=>$v){ //echo '<pre>'; print_r($v); exit;
                    $diary_no ='';$court_no=''; $next_dt ='';$conn_key=''; $entry_date ='';
                    $diary_no = !empty($v['diary_no']) ? (int)$v['diary_no'] : 0;
                    $conn_key = !empty($v['conn_key']) ? (int)$v['conn_key'] : 0;
                    $court_no = !empty($v['courtno']) ? (int)$v['courtno'] : 0;
                    $next_dt = !empty($v['next_dt']) ? date('Y-m-d',strtotime($v['next_dt']))  : NULL;
                    $entry_date = !empty($v['entry_date']) ? date('Y-m-d',strtotime($v['entry_date'])) : $current_date;
                    $from_dt = $next_dt;
                    $to_dt = $next_dt;
                    $sqlQuery ="";
                    $sqlQuery="select diary_no,conn_key,consent,from_dt,mainhead,board_type,roster_id,main_supp_flag,part_no,judges from hybrid_physical_hearing_consent where mainhead='$mainhead'  and date(from_dt) = '$next_dt' and board_type='$board_type'
                    and judges='$judges' and main_supp_flag=$main_supp_flag and part_no=$part_no and diary_no=$diary_no ";
                    $resultSet =mysql_query($sqlQuery) or die(mysql_error());
                    if(mysql_num_rows($resultSet) > 0){
                    if($conn_key != null and $conn_key > 0){
                    $sql="";
                    $sql = "insert into hybrid_physical_hearing_consent_log 
                    (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                    list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                     updated_by,updated_date,updated_user_ip)
                    select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' from 
                    hybrid_physical_hearing_consent a where a.conn_key = '".$conn_key."' ";
                    $rs1 = mysql_query($sql);
                    $sql="";
                    $sql = "delete from hybrid_physical_hearing_consent where conn_key = $conn_key and  from_dt = '$next_dt'  ";
                    $rs = mysql_query($sql);
                    $sql="";
                    $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt,
                        list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges)
                    select m.diary_no, m.conn_key, '".$update_flag."',  '" . $from_time. "',   '" . $to_time . "', '" . $from_dt . "', '" . $to_dt . "', 
                    '" . $list_type_id . "', '" . $list_number . "', '" . $list_year. "', '" . $mainhead. "', '" . $board_type . "', '" . $user_id. "','".$current_date."',
                     '" . $user_ip . "' ,'" . $court_no . "', '" . $roster_id . "', '" . $main_supp_flag . "', '" . $part_no . "','".$judges."'
                         from 
                        (select m.* from main m where m.conn_key = '".$conn_key."' and c_status = 'P'
                        union
                        select m.* from main m
                        inner join conct ct on ct.conn_key = m.conn_key
                        where m.conn_key = '".$conn_key."' and ct.list = 'Y' and m.c_status = 'P') m
                        inner join heardt h on h.diary_no = m.diary_no 
                        where h.clno > 0 and h.next_dt = '" . $next_dt . "'";
                            $rs = mysql_query($sql);
                            $afros = mysql_affected_rows();
                            if ($afros > 0) {
                                $count_success++;
                                $successArr[] = array("success"=>1,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            } else {
                                $count_error++;
                                $successArr[] = array("success"=>0,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            }
                        }
                        else{
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent_log 
                        (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                        list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                         updated_by,updated_date,updated_user_ip)
                        select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' 
                        from hybrid_physical_hearing_consent a where diary_no = " . $diary_no;
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "delete from hybrid_physical_hearing_consent where diary_no = $diary_no and from_dt = '$next_dt' ";
                        $rs = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, 
                        to_dt, list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,
                        part_no,judges)
                        VALUES ('" . $diary_no . "', '" . $conn_key . "', '".$update_flag."', '" . $from_time . "','" . $to_time . "', '" . $from_dt . "', 
                        '" . $to_dt . "', '" . $list_type_id . "',  '" . $list_number . "', '" . $list_year . "', '" . $mainhead . "', '" . $board_type . "', 
                        '".$user_id."', '".$current_date."','".$user_ip."','".$court_no."','" . $roster_id . "','" . $main_supp_flag . "', 
                        '" . $part_no . "','".$judges."')";
                            $rs = mysql_query($sql);
                            $afros = mysql_affected_rows();
                            if ($afros > 0) {
                                $count_success++;
                                $successArr[] = array("success"=>1,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            } else {
                                $count_error++;
                                $successArr[] = array("success"=>0,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            }
                        }

                    }
                    else{
                    if($conn_key != null and $conn_key > 0){
                    $sql="";
                    $sql = "insert into hybrid_physical_hearing_consent_log 
                    (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                    list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                     updated_by,updated_date,updated_user_ip)
                    select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' from 
                    hybrid_physical_hearing_consent a where a.conn_key = '".$conn_key."' ";
                    $rs1 = mysql_query($sql);
                    $sql="";
                    $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt,
                        list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges)
                    select m.diary_no, m.conn_key, '".$update_flag."',  '" . $from_time. "',   '" . $to_time . "', '" . $from_dt . "', '" . $to_dt . "', 
                    '" . $list_type_id . "', '" . $list_number . "', '" . $list_year. "', '" . $mainhead. "', '" . $board_type . "', '" . $user_id. "','".$current_date."',
                     '" . $user_ip . "' ,'" . $court_no . "', '" . $roster_id . "', '" . $main_supp_flag . "', '" . $part_no . "','".$judges."'
                         from 
                        (select m.* from main m where m.conn_key = '".$conn_key."' and c_status = 'P'
                        union
                        select m.* from main m
                        inner join conct ct on ct.conn_key = m.conn_key
                        where m.conn_key = '".$conn_key."' and ct.list = 'Y' and m.c_status = 'P') m
                        inner join heardt h on h.diary_no = m.diary_no 
                        where h.clno > 0 and h.next_dt = '" . $next_dt . "'";
                            $rs = mysql_query($sql);
                            $afros = mysql_affected_rows();
                            if ($afros > 0) {
                                $count_success++;
                                $successArr[] = array("success"=>1,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            } else {
                                $count_error++;
                                $successArr[] = array("success"=>0,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            }
                        }
                        else{
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent_log 
                        (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
                        list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
                         updated_by,updated_date,updated_user_ip)
                        select a.*, '" . $user_id . "', NOW(), '" . $user_ip . "' 
                        from hybrid_physical_hearing_consent a where diary_no = " . $diary_no;
                        $rs1 = mysql_query($sql);
                        $sql="";
                        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, 
                        to_dt, list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,
                        part_no,judges)
                        VALUES ('" . $diary_no . "', '" . $conn_key . "', '".$update_flag."', '" . $from_time . "','" . $to_time . "', '" . $from_dt . "', 
                        '" . $to_dt . "', '" . $list_type_id . "',  '" . $list_number . "', '" . $list_year . "', '" . $mainhead . "', '" . $board_type . "', 
                        '".$user_id."', '".$current_date."','".$user_ip."','".$court_no."','" . $roster_id . "','" . $main_supp_flag . "', 
                        '" . $part_no . "','".$judges."')";
                            $rs = mysql_query($sql);
                            $afros = mysql_affected_rows();
                            if ($afros > 0) {
                                $count_success++;
                                $successArr[] = array("success"=>1,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            } else {
                                $count_error++;
                                $successArr[] = array("success"=>0,"diary_no"=>$diary_no,"entry_date"=>$current_date);
                            }
                        }
                    }
                }
                $output = array("status" => "success","count_success"=>$count_success,"count_error"=>$error_count,"successArr"=>$successArr);
            }
            else{
                $output = array("status" => "Error");
            }
            break;
        default:
    }
}
echo json_encode($output);
exit(0);


























//if(isset($_POST['diary_no'])) {
//
//if($_POST['conn_key'] != null and $_POST['conn_key'] > 0){
//      $sql1 = "insert into hybrid_physical_hearing_consent_log
//(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number,
//list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no,
//updated_by,updated_date,updated_user_ip)
//select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' from
//hybrid_physical_hearing_consent a where a.conn_key = '".$_POST['conn_key']."' ";
//        $rs1 = mysql_query($sql1);
//
//         $sql = "delete from hybrid_physical_hearing_consent where conn_key = '".$_POST['conn_key']."' ";
//        $rs = mysql_query($sql);
//
//$sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time,
//from_dt, to_dt, list_type_id, list_number, list_year, mainhead, board_type, court_no, user_id, user_ip)
//
//select m.diary_no, m.conn_key, '".$_POST['update_flag']."',
// '" . $_POST['from_time'] . "',
//'" . $_POST['to_time'] . "', '" . $_POST['from_dt'] . "', '" . $_POST['to_dt'] . "', '" . $_POST['list_type_id'] . "',
//'" . $_POST['list_number'] . "', '" . $_POST['list_year'] . "', '" . $_POST['mainhead'] . "', '" . $_POST['board_type'] . "',
//'" . $_POST['courtno'] . "', '" . $_SESSION['dcmis_user_idd'] . "', '" . $_POST['ip'] . "'
// from
//(select m.* from main m where m.conn_key = '".$_POST['conn_key']."' and c_status = 'P'
//union
//select m.* from main m
//inner join conct ct on ct.conn_key = m.conn_key
//where m.conn_key = '".$_POST['conn_key']."' and ct.list = 'Y' and m.c_status = 'P') m
//inner join heardt h on h.diary_no = m.diary_no
//where h.clno > 0 and h.next_dt = '" . $_POST['next_dt'] . "'";
//        $rs = mysql_query($sql);
//        $afros = mysql_affected_rows();
//        if ($afros > 0) {
//            $return_arr = array("status" => "success");
//
//
//        } else {
//            $return_arr = array("status" => "Error:Not Saved");
//        }
//    }
//    else{
//        $sql1 = "insert into hybrid_physical_hearing_consent_log
//(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number,
//list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no,
//updated_by,updated_date,updated_user_ip)
//select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "'
//from hybrid_physical_hearing_consent a where diary_no = " . $_POST['diary_no'];
//        $rs1 = mysql_query($sql1);
//
//        $sql = "delete from hybrid_physical_hearing_consent where diary_no = " . $_POST['diary_no'];
//        $rs = mysql_query($sql);
//
//        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time,
//from_dt, to_dt, list_type_id, list_number, list_year, mainhead, board_type, court_no, user_id, user_ip)
//VALUES ('" . $_POST['diary_no'] . "', '" . $_POST['conn_key'] . "', '".$_POST['update_flag']."', '" . $_POST['from_time'] . "',
//'" . $_POST['to_time'] . "', '" . $_POST['from_dt'] . "', '" . $_POST['to_dt'] . "', '" . $_POST['list_type_id'] . "',
//'" . $_POST['list_number'] . "', '" . $_POST['list_year'] . "', '" . $_POST['mainhead'] . "', '" . $_POST['board_type'] . "',
//'" . $_POST['courtno'] . "', '" . $_SESSION['dcmis_user_idd'] . "', '" . $_POST['ip'] . "')";
//        $rs = mysql_query($sql);
//        $afros = mysql_affected_rows();
//        if ($afros > 0) {
//            $return_arr = array("status" => "success");
//
//
//        } else {
//            $return_arr = array("status" => "Error:Not Saved");
//        }
//    }
//
//
//}
//else{
//    $return_arr = array("status" => "Error");
//}
//
//echo json_encode($return_arr);
?>
