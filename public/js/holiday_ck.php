<?php 
include("../includes/db_inc.php");
//court_holiday
function next_holidays(){
    $sql="select date_format(working_date,'%e-%c-%Y') holidays from sc_working_days where working_date >= curdate() and display = 'Y' and is_holiday = 1";
    $res=mysql_query($sql) or die(mysql_error());
    $str = '';
    while($row=mysql_fetch_array($res)){
        $str .= '"'.$row['holidays'].'",';
    };
    return rtrim($str, ',');
}
function next_court_working_date($date){
    $sql="select working_date from sc_working_days where working_date>='$date' and display = 'Y' and is_holiday = 0 limit 1";
    $res=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_array($res);
    return $row['working_date'];
}

//court working
function chksDate($dt)
{
    $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt'");
    $res_h=mysql_result($sql_we, 0);
    if($res_h>0)
    {
        $dt=date('Y-m-d', strtotime($dt. ' + 1 days'));

        return chksDate($dt);

    }
    else
    {
//       $gh=date('w',  strtotime($dt));
//       echo $gh;
        return $dt;
    }

}

// $date_h=date('Y-m-d');
//  $date_h=date('Y-m-d', strtotime($date_h. ' + 1 days'));
//  $dt=chkDate($date_h);
// echo $dt;
function chksDate_emp($dt)
{
    $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt' and emp_hol='1'");
    $res_h=mysql_result($sql_we, 0);
    if($res_h>0)
    {
        $dt=date('Y-m-d', strtotime($dt. ' + 1 days'));

        return chksDate_emp($dt);

    }
    else
    {
//       $gh=date('w',  strtotime($dt));
//       echo $gh;
        return $dt;
    }

}

function chksDate_emp_sub($dt,$str)
{

    for($i=0;$i<=$str;)
    {
        $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt' and emp_hol='1'");


        $res_h=mysql_result($sql_we, 0);
        if($res_h>0)
        {
            $dt=date('Y-m-d', strtotime($dt. ' - 1 days'));
            continue;
        }
        else
        {

            if($str==$i)
                return $dt;
            else
                $dt=date('Y-m-d',  strtotime($dt. '-1 days'));
            $i++;
        }

    }
}


function chksDate_pb($dt,$str)
{

    for($i=0;$i<=$str;)
    {
        $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt'");


        $res_h=mysql_result($sql_we, 0);
        if($res_h>0)
        {
            $dt=date('Y-m-d', strtotime($dt. ' - 1 days'));
            continue;
        }
        else
        {

            if($str==$i)
                return $dt;
            else
                $dt=date('Y-m-d',  strtotime($dt. '-1 days'));
            $i++;
        }

    }
}


function chksDate_vac_reg_add($dt,$chk_tot_days)
{
    $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt' and (emp_hol='1' or emp_hol='2')");
    $res_h=mysql_result($sql_we, 0);
    if($res_h>0)
    {
        if(strtotime($dt)==strtotime($chk_tot_days))
            return $dt;
        else
            $dt=date('Y-m-d', strtotime($dt. ' + 1 days'));

        return chksDate_vac_reg_add($dt,$chk_tot_days);

    }
    else
    {
//       $gh=date('w',  strtotime($dt));
//       echo $gh;
        return $dt;
    }

}

function chksDate_vac_reg_sub($dt,$chk_tot_days)
{
//     echo $dt.'$$'.$chk_tot_days."Select count(hdate) from  holidays where hdate='$dt' and (emp_hol='1' or emp_hol='2')";

    $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt' and (emp_hol='1' or emp_hol='2')");
    $res_h=mysql_result($sql_we, 0);
    if($res_h>0)
    {
        if(strtotime($dt)==strtotime($chk_tot_days))
            return $dt;
        else
            $dt=date('Y-m-d', strtotime($dt. ' - 1 days'));

        return chksDate_vac_reg_sub($dt,$chk_tot_days);

    }
    else
    {
//       $gh=date('w',  strtotime($dt));
//       echo $gh;
        return $dt;
    }

}

function chk_curr_dat_holiday($dt)
{
    $sql_we=mysql_query("Select count(hdate) from  holidays where hdate='$dt' and (emp_hol='1' or emp_hol='2')");
    $res_h=mysql_result($sql_we, 0);
    if($res_h>0)
    {

        $dt=date('Y-m-d', strtotime($dt. ' + 1 days'));


        return $dt;
    }
    else
    {
        return $dt;
    }
}

?>