<?php

//$hd_ud = $_SESSION['dcmis_user_idd'];



if($handler =='G')
{
    $full='';
    /* $sql_act = "select * from act_master where display= 'Y' order by id ASC";
    $rs_act = mysql_query($sql_act) or die(mysql_error());	 */
	$rs_act = $CaveatModel->getActMaster();
	 
    foreach($rs_act  as $row_act)
    {
        $full .= $row_act['id']."~".$row_act['act_name']."#";
    }
    echo rtrim($full,'#');
	die;
}
else if($handler == 'D')
{
	 
    $save_in = '';
    if($sec_1!='')
    {
        $save_in .= trim($sec_1);
        if($sec_2!='')
        {    
            $save_in .= "(".trim($sec_2).")";
            if($sec_3!='')
            {
                $save_in .= "(".trim($sec_3).")";
                if($sec_4!='')
                {
                    $save_in .= "(".trim($sec_4).")";
                }
            }
        }
    }
    
    $sel = "select section from act_main where diary_no='$fil_no' and diary_year='$d_yr' and act='$act' and display='Y'";
    $sel = mysql_query($sel) or die(mysql_error());
    if(mysql_num_rows($sel)>0)
    {
        $sel = mysql_result($sel,0);
        $sel = explode('/', $sel);
        
        $save_in2 = '';
        
        for($i=0;$i<sizeof($sel);$i++)
        {
            if($sel[$i]!=$save_in)
            {
                $save_in2 .= $sel[$i].'/'; 
            }
        }
        $save_in2 = rtrim($save_in2, '/');
        
        $sql_2 = "select section from act_main_his where diary_no='$fil_no' and diary_year='$d_yr' and act='$act' and section='$save_in' ";
        $sql_2 = mysql_query($sql_2) or die(mysql_error());
        if(mysql_num_rows($sql_2)>0)
        {
            $query = "update act_main_his set entdt=now(),user=$_REQUEST[hd_ud] where diary_no='$fil_no' and diary_year='$d_yr' and act='$act' and section='$save_in'";
        }
        else
        {
            $query = "insert into act_main_his values('','$act','$save_in',now(),$_REQUEST[hd_ud],'$fil_no','$d_yr')";
        }
        mysql_query($query) or die(mysql_error());
        if($save_in2!='')
            $theek_karo = "update act_main set section = '$save_in2',entdt=now(),user=$_REQUEST[hd_ud] where diary_no='$fil_no' and diary_year='$d_yr' and act='$act'";
        else
            $theek_karo = "delete from act_main where diary_no='$fil_no' and diary_year='$d_yr' and act='$act'";
        mysql_query($theek_karo) or die(mysql_error());
        echo "1";
    }
    else
    {
        echo "Given Act and Section Combination Not Found";
    }
	die;
}
 
