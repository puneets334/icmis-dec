<?php
include("../../includes/db_inc.php");
extract($_POST);
session_start();
$user_id =  $_SESSION['dcmis_user_idd'];
$user_ip = $_SERVER['REMOTE_ADDR'];
$current_date =date('Y-m-d H:i:s');
$sqlQuery="INSERT INTO `consent_through_email`( `diary_no`, `conn_key`,`next_dt`,`roster_id`,`part`,`main_supp_flag`,`applicant_type`,`party_id`,`advocate_id`,
entry_source, `user_id`, `entry_date`,`user_ip`,`is_deleted`)
select c.diary_no, c.conn_key, c.next_dt, c.roster_id, c.part, c.main_supp_flag, c.applicant_type, c.party_id, c.advocate_id, c.entry_source,'$user_id' AS userid, NOW(),'$user_ip',
NULL AS isdeleted
from heardt h 
inner join consent_through_email c on h.diary_no = c.diary_no and h.next_dt = c.next_dt
where c.next_dt = '$next_dt' and h.roster_id = '$new_roster_id' and c.roster_id = '$old_roster_id' and is_deleted IS NULL
and h.clno > 0";
$resultSet =mysql_query($sqlQuery) or die(mysql_error());

$sqlQuery="update consent_through_email set is_deleted=1, deleted_by=".$user_id.", deleted_on=NOW(), deleted_ip='$user_ip' 
where next_dt = '$next_dt' and roster_id = '$old_roster_id' and is_deleted IS NULL ";
$resultSet =mysql_query($sqlQuery) or die(mysql_error());
if($resultSet > 0){
    $output = array("status" => "success");
}
else{
    $output = array("status" => "Error");
}
echo json_encode($output);
exit(0);