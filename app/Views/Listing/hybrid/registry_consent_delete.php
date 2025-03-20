<?php
include("../../includes/db_inc.php");
session_start();
if(isset($_POST['diary_no'])) {

if($_POST['conn_key'] != null and $_POST['conn_key'] > 0){
    $sql = "insert into hybrid_physical_hearing_consent_log 
(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
updated_by,updated_date,updated_user_ip)
select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' 
from hybrid_physical_hearing_consent a where conn_key = " . $_POST['conn_key'];
}
else{
    $sql = "insert into hybrid_physical_hearing_consent_log 
(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
updated_by,updated_date,updated_user_ip)
select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' 
from hybrid_physical_hearing_consent a where diary_no = " . $_POST['diary_no'];

}
    $rs = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        if($_POST['conn_key'] != null and $_POST['conn_key'] > 0) {
            $sql = "delete from hybrid_physical_hearing_consent where conn_key = " .  $_POST['conn_key'];
        }
        else{
            $sql = "delete from hybrid_physical_hearing_consent where diary_no = " . $_POST['diary_no'];
        }
    $rs = mysql_query($sql);
    $afros = mysql_affected_rows();
    if ($afros > 0) {
        $return_arr = array("status" => "success");
    } else {
        $return_arr = array("status" => "Error:Not Deleted.");
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
