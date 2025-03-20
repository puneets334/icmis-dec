<?php
include('../extra/lg_out_script.php'); {
    include("../includes/db_inc.php");

    if (($_REQUEST['total_old_cat'] == 1) && ($_REQUEST['total_new_cat'] == 1)) {

        $dairy_no = $_REQUEST[t_h_cno] . $_REQUEST[t_h_cyr];
        $ex_a = explode('^', $_REQUEST['hd_sp_a_rem']);
        $ex_b = explode('^', $_REQUEST['hd_sp_b_rem']);
        $ex_c = explode('^', $_REQUEST['hd_sp_c_rem']);

        $ex_d = explode('^', $_REQUEST['hd_sp_d_id']);
        for ($index = 0; $index <= count($ex_a); $index++) {


            //$sql_ck_del=mysql_query("SELECT count(*)   FROM  mul_category  WHERE diary_no='$dairy_no' 
            //                     and cat='$ex_a[$index]' and subcat='$ex_b[$index]'
            //                     and subcat1='$ex_c[$index]' and display='Y' and id='$ex_d[$index]'");

            $sql_ck_del = mysql_query("SELECT count(*)   FROM  mul_category  WHERE diary_no='$dairy_no' 
                      and display='Y' and submaster_id='$ex_d[$index]'");
            $result_del = mysql_result($sql_ck_del, 0);
            if ($result_del != 0) {
                $sq_upd = mysql_query("Update mul_category set display='N',updated_on=now(),updated_by='$_SESSION[dcmis_user_idd]' where diary_no='$dairy_no' 
                      and submaster_id='$ex_d[$index]'") or die("Error: " . __LINE__ . mysql_error());
            }
        }
    }
}
