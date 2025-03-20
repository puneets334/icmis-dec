<?php
include("../../includes/db_inc.php");
session_start();
if(isset($_POST['diary_no'])) {

if($_POST['conn_key'] != null and $_POST['conn_key'] > 0){
      $sql1 = "insert into hybrid_physical_hearing_consent_log 
(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
updated_by,updated_date,updated_user_ip)
select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' from 
hybrid_physical_hearing_consent a where a.conn_key = '".$_POST['conn_key']."' ";
        $rs1 = mysql_query($sql1);

         $sql = "delete from hybrid_physical_hearing_consent where conn_key = '".$_POST['conn_key']."' ";
        $rs = mysql_query($sql);

$sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, 
from_dt, to_dt, list_type_id, list_number, list_year, mainhead, board_type, court_no, user_id, user_ip)

select m.diary_no, m.conn_key, '".$_POST['update_flag']."', 
 '" . $_POST['from_time'] . "', 
'" . $_POST['to_time'] . "', '" . $_POST['from_dt'] . "', '" . $_POST['to_dt'] . "', '" . $_POST['list_type_id'] . "', 
'" . $_POST['list_number'] . "', '" . $_POST['list_year'] . "', '" . $_POST['mainhead'] . "', '" . $_POST['board_type'] . "', 
'" . $_POST['courtno'] . "', '" . $_SESSION['dcmis_user_idd'] . "', '" . $_POST['ip'] . "'
 from 
(select m.* from main m where m.conn_key = '".$_POST['conn_key']."' and c_status = 'P'
union
select m.* from main m
inner join conct ct on ct.conn_key = m.conn_key
where m.conn_key = '".$_POST['conn_key']."' and ct.list = 'Y' and m.c_status = 'P') m
inner join heardt h on h.diary_no = m.diary_no 
where h.clno > 0 and h.next_dt = '" . $_POST['next_dt'] . "'";
        $rs = mysql_query($sql);
        $afros = mysql_affected_rows();
        if ($afros > 0) {
            $return_arr = array("status" => "success");


        } else {
            $return_arr = array("status" => "Error:Not Saved");
        }
    }
    else{
        $sql1 = "insert into hybrid_physical_hearing_consent_log 
(id, diary_no, conn_key, consent, hearing_from_time, hearing_to_time, from_dt, to_dt, list_type_id, list_number, 
list_year, mainhead, board_type, user_id, entry_date, user_ip, court_no, 
updated_by,updated_date,updated_user_ip)
select a.*, '" . $_SESSION['dcmis_user_idd'] . "', NOW(), '" . $_POST['ip'] . "' 
from hybrid_physical_hearing_consent a where diary_no = " . $_POST['diary_no'];
        $rs1 = mysql_query($sql1);

        $sql = "delete from hybrid_physical_hearing_consent where diary_no = " . $_POST['diary_no'];
        $rs = mysql_query($sql);

        $sql = "insert into hybrid_physical_hearing_consent (diary_no, conn_key, consent, hearing_from_time, hearing_to_time, 
from_dt, to_dt, list_type_id, list_number, list_year, mainhead, board_type, court_no, user_id, user_ip)
VALUES ('" . $_POST['diary_no'] . "', '" . $_POST['conn_key'] . "', '".$_POST['update_flag']."', '" . $_POST['from_time'] . "', 
'" . $_POST['to_time'] . "', '" . $_POST['from_dt'] . "', '" . $_POST['to_dt'] . "', '" . $_POST['list_type_id'] . "', 
'" . $_POST['list_number'] . "', '" . $_POST['list_year'] . "', '" . $_POST['mainhead'] . "', '" . $_POST['board_type'] . "', 
'" . $_POST['courtno'] . "', '" . $_SESSION['dcmis_user_idd'] . "', '" . $_POST['ip'] . "')";
        $rs = mysql_query($sql);
        $afros = mysql_affected_rows();
        if ($afros > 0) {
            $return_arr = array("status" => "success");


        } else {
            $return_arr = array("status" => "Error:Not Saved");
        }
    }


}
else{
    $return_arr = array("status" => "Error");
}

echo json_encode($return_arr);
?>
