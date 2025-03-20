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

    $query = "insert into sc_working_days(working_date,is_nmd,is_holiday,holiday_description,nmd_dt,misc_dt1,sec_list_dt,holiday_for_registry) 
values ('$working_date', '$is_nmd','$is_holiday','$holiday_description','$nmd_dt','$misc_dt1','$sec_list_dt','$holiday_for_registry')";

    if (mysql_query($query) or die(mysqli_error($conn))) {
        echo "<br/><br/><span>Data Inserted Successfully</span>";
    }
    else{
        echo "<p>Insertion Failed <br/>";

    }


}
?>

