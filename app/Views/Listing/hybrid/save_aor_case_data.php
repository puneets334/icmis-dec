<?php
include("../../includes/db_inc.php");
session_start();
//echo '<pre>'; print_r($_POST); exit;
$updation_method = !empty($_POST['updation_method']) ? trim($_POST['updation_method']) : null;
$action = !empty($_POST['action']) ? trim($_POST['action']) : null;
$userArr = !empty($_POST['userArr']) ? $_POST['userArr'] : null;
$output= array();
if(isset($updation_method) && !empty($updation_method)){
    $diary_no = !empty($_POST['diary_no']) ? (int)trim($_POST['diary_no']) : NULL;
    $conn_key = !empty($_POST['conn_key']) ? (int)trim($_POST['conn_key']) : 0;
    $roster_id = !empty($_POST['roster_id']) ? (int)trim($_POST['roster_id']) : NULL;
    $part = !empty($_POST['clno']) ? (int)$_POST['clno'] : NULL;
    $main_supp_flag = !empty($_POST['main_supp_flag']) ? (int)$_POST['main_supp_flag'] : NULL;
    $current_date =date('Y-m-d H:i:s');
    $next_dt = !empty($_POST['next_dt']) ? date('Y-m-d',strtotime(trim($_POST['next_dt']))) : NULL;
    $user_id =  $_SESSION['dcmis_user_idd'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $count_success=0;$count_error=0; $successArr = [];
    $previousState = false;
   // $party_id = 0;
   // $advocate_id= 0;
   // $applicant_type=1;
    //$partyInPersonArr = array(584,585,610,616,666,940);
    switch ($updation_method){
    case 'single':
        switch ($action){
            case 'save':
                if(isset($_POST['diary_no']) && !empty($_POST['diary_no']) && isset($userArr) && !empty($userArr) && count($userArr)>0) {
                    foreach ($userArr as $k=>$v){
                        $sqlQuery ='';
                        $where = "";
                        $applicant_id= !empty($v['applicant_id'])  ? (int)$v['applicant_id'] : NULL;
                        $applicant_type = !empty($v['applicant_type']) ? $v['applicant_type'] : NULL;
                        if(!empty($applicant_id) && !empty($applicant_type) && $applicant_type == 1){
                            $where .=" and advocate_id=$applicant_id";
                            $advocate_id = $applicant_id;
                            $party_id = 0;
                        }
                        else{
                            $where .=" and party_id=$applicant_id";
                            $advocate_id = 0;
                            $party_id = $applicant_id;
                        }
                        $sqlQuery="update consent_through_email set is_deleted=1, deleted_by=".$user_id.", 
                        deleted_on='$current_date', deleted_ip='$user_ip' where diary_no=".$diary_no." and next_dt = '$next_dt' 
                        and is_deleted IS NULL $where";
                        $resultSet =mysql_query($sqlQuery) or die(mysql_error());
                        $sqlQuery ='';
                        $sqlQuery="INSERT INTO `consent_through_email`( `diary_no`, `conn_key`,`next_dt`,`roster_id`,`part`,`main_supp_flag`,`applicant_type`,`party_id`,`advocate_id`,
                        `user_id`, `entry_date`,`user_ip`,`is_deleted`,`deleted_by`, `deleted_on`,`deleted_ip`)
                         VALUES($diary_no,$conn_key,'$next_dt',$roster_id,$part,$main_supp_flag,$applicant_type,$party_id,$advocate_id,$user_id,'$current_date','$user_ip',NULL,NULL,NULL ,NULL )";
                        $resultSet =mysql_query($sqlQuery) or die(mysql_error());

                        if(mysql_affected_rows()>0){
                            $count_success++;
                        }
                        else{
                            $count_error++;
                        }
                    }
                    $output = array("status" => "success","count_success"=>$count_success,"count_error"=>$count_error);
                }
                else{
                    $output = array("status" => "Error");
                }
              break;
            case 'modify':
                if(isset($_POST['diary_no']) && !empty($_POST['diary_no']) && isset($userArr) && !empty($userArr) && count($userArr)>0) {
                    foreach ($userArr as $k=>$v){
                        $applicant_id = !empty($v['applicant_id'])  ? (int)$v['applicant_id'] : NULL;
                        $applicant_type = !empty($v['applicant_type']) ? $v['applicant_type'] : NULL;
                        $where ="";
                        if(!empty($applicant_id) && !empty($applicant_type) && $applicant_type == 1){
                            $where .=" and advocate_id=$applicant_id";
                        }
                        else{
                            $where .=" and party_id=$applicant_id";
                        }
                        $sqlQuery ='';
                        $sqlQuery="update consent_through_email set is_deleted=1, deleted_by=".$user_id.", 
                        deleted_on='$current_date', deleted_ip='$user_ip' where diary_no=".$diary_no." and next_dt = '$next_dt' 
                        and is_deleted IS NULL $where";
                        $resultSet =mysql_query($sqlQuery) or die(mysql_error());
                        if(mysql_affected_rows()>0){
                            $count_success++;
                        }
                        else{
                            $count_error++;
                        }
                    }
                    $output = array("status" => "success","count_success"=>$count_success,"count_error"=>$count_error);
                }
                else{
                    $output = array("status" => "Error");
                }

                break;
            default:
        }
        break;
        case 'bulk':
            break;
        default:
    }
}
echo json_encode($output);
exit(0);

?>
