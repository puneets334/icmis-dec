<?php
include ('../extra/lg_out_script.php');
{
    include("../includes/db_inc.php");
    $working_date = date('Y-m-d', strtotime($_POST['working_date']));
    //$working_date = $_POST['working_date'];
    $is_nmd = $_POST['is_nmd'];
    $is_holiday = $_POST['is_holiday'];
    $holiday_description = $_POST['holiday_description'];
    $nmd_dt = date('Y-m-d', strtotime($_POST['nmd_dt']));
    $misc_dt1 =  date('Y-m-d', strtotime($_POST['misc_dt1']));
    $sec_list_dt = date('Y-m-d', strtotime($_POST['sec_list_dt']));
    $holiday_for_registry = $_POST['holiday_for_registry'];
    $updated_on=date('Y-m-d H:i:s');

    $query = "update sc_working_days set is_nmd='$is_nmd',is_holiday='$is_holiday',holiday_description='$holiday_description',updated_on='$updated_on',nmd_dt='$nmd_dt',misc_dt1='$misc_dt1',sec_list_dt='$sec_list_dt',holiday_for_registry='$holiday_for_registry'
where working_date='$working_date'";
    $res = mysql_query($query) or die(mysql_error());
    if($res){
        echo "Record Updated Successfully";
        //<div>Demo Text, with <span>some other</span> text.</div>
    }
    else{
        echo "Error";
    }


}
?>

