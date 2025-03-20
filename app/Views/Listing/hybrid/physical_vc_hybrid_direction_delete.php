<?php
include("../../includes/db_inc.php");
session_start();
    $diary_no = !empty($_POST['diary_no']) ? (int)trim($_POST['diary_no']) : NULL;
    $conn_key = !empty($_POST['conn_key']) ? (int)trim($_POST['conn_key']) : NULL;
    $roster_id = !empty($_POST['roster_id']) ? (int)trim($_POST['roster_id']) : NULL;
    $judges = !empty($_POST['judges']) ? trim($_POST['judges']) : NULL;
    $main_supp_flag = !empty($_POST['main_supp_flag']) ? (int)trim($_POST['main_supp_flag']) : NULL;
    $part_no = !empty($_POST['clno']) ? (int)trim($_POST['clno']) : NULL;
    $court_no = !empty($_POST['courtno']) ? (int)trim($_POST['courtno']) : NULL;
    $list_type_id = !empty($_POST['list_type_id']) ? (int)trim($_POST['list_type_id']) : NULL;
    $mainhead = !empty($_POST['mainhead']) ? trim($_POST['mainhead']) : NULL;
    $board_type = !empty($_POST['board_type']) ? trim($_POST['board_type']) : NULL;
    $next_dt = !empty($_POST['next_dt']) ? date('Y-m-d',strtotime(trim($_POST['next_dt']))) : NULL;
    $entry_date = !empty($_POST['entry_date']) ? date('Y-m-d',strtotime(trim($_POST['entry_date']))) : date('Y-m-d');
    $update_flag =  'N';
    $from_dt = $next_dt;
    $to_dt = $next_dt;
    $from_time = NULL;
    $to_time = NULL;
    $list_number = 0;
    $list_year =!empty($_POST['next_dt']) ? date('Y',strtotime(trim($_POST['next_dt']))) : NULL;
    $user_id =  $_SESSION['dcmis_user_idd'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_POST['diary_no'])) {
    if($_POST['conn_key'] != null and $_POST['conn_key'] > 0){
            $sql = "insert into hybrid_physical_hearing_consent_log 
            (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
            list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
             updated_by,updated_date,updated_user_ip)
        select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' 
        from hybrid_physical_hearing_consent a where conn_key = " . $_POST['conn_key'];
        }
    else{
            $sql = "insert into hybrid_physical_hearing_consent_log 
            (id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
            list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,part_no,judges,
            updated_by,updated_date,updated_user_ip)
            select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' 
            from hybrid_physical_hearing_consent a where diary_no = " . $_POST['diary_no'];
        }
        $rs = mysql_query($sql);
        if (mysql_affected_rows() > 0) {
        $currentDate = date('Y-m-d');
        $sqlQuery = "select entry_date from  hybrid_physical_hearing_consent WHERE date(entry_date) = '$entry_date' and (diary_no = '".$_POST['diary_no']."' or 
        diary_no = '".$_POST['diary_no']."')";
        $result = mysql_query($sqlQuery);
        if(mysql_num_rows($result) >0){
            if($_POST['conn_key'] != null and $_POST['conn_key'] > 0) {
                $sql = "delete from hybrid_physical_hearing_consent where conn_key = '".$_POST['conn_key']."' and date(entry_date) ='".$entry_date."' " ;
            }
            else{
                $sql = "delete from hybrid_physical_hearing_consent where diary_no = '".$_POST['diary_no']."' and date(entry_date) ='".$entry_date."' " ;
            }
            $response = mysql_query($sql);
            $afrows = mysql_affected_rows();
            if ($afrows > 0) {
                $sql="";
                $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, 
                        to_dt, list_type_id, list_number, list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, roster_id,main_supp_flag,
                        part_no,judges)
                        VALUES ('" . $diary_no . "', '" . $conn_key . "', '".$update_flag."', '" . $from_time . "','" . $to_time . "', '" . $from_dt . "', 
                        '" . $to_dt . "', '" . $list_type_id . "',  '" . $list_number . "', '" . $list_year . "', '" . $mainhead . "', '" . $board_type . "', 
                        '".$user_id."', '".$entry_date."','".$user_ip."','".$court_no."','" . $roster_id . "','" . $main_supp_flag . "', 
                        '" . $part_no . "' ,'".$judges."')";
                $rs = mysql_query($sql);
                $afros = mysql_affected_rows();
                if ($afros > 0) {
                    $return_arr = array("status" => "success");
                } else {
                    $return_arr = array("status" => "Error:Not Deleted.");
                }
            }
            else {
                $return_arr = array("status" => "Error:Not Deleted.");
            }

        }
    }
    else{
        $return_arr = array("status" => "Error:Not Deleted...");
    }

    }
    else{
        $return_arr = array("status" => "Error");
    }

echo json_encode($return_arr);
?>
