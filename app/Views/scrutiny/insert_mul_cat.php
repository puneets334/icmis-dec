<?php
include('../extra/lg_out_script.php'); {

    include("../includes/db_inc.php");

    if (($_REQUEST['total_old_cat'] == 1)) {


        $total_new_category = $_REQUEST['total_new_cat'];
        $old_submaster_id = $_REQUEST[hd_sp_d];

        $sectionsCategory = [12,13,14,64];
        $logged_in_usersection = $_SESSION['dcmis_section'];

        if (in_array($logged_in_usersection, $sectionsCategory) || ((!in_array($logged_in_usersection, $sectionsCategory))  && ($_REQUEST['total_new_cat'] == 1))) {

            $dairy_no = $_REQUEST[t_h_cno] . $_REQUEST[t_h_cyr];
            $ucode = $_SESSION['dcmis_user_idd'];
            // $other_cat_rem=$_REQUEST[other_cat];
            $other_cat_rem = trim($_REQUEST[other_cat], ' ');

            $verify_req_page = $_REQUEST[verify_req_page];
            $hd_sp_d_new = $_REQUEST['hd_sp_d_new'];

            $sq_upd = mysql_query("Update mul_category set display='N',updated_on=now(),updated_by='$ucode' where diary_no='$dairy_no' ") or die("Error: " . __LINE__ . mysql_error());
            $sql =  mysql_query("Insert Into  mul_category (od_cat,diary_no,submaster_id,mul_cat_user_code,new_submaster_id,updated_on,updated_by) values 
        ('$_REQUEST[ytq1]','$dairy_no','$_REQUEST[hd_sp_d]','$ucode','$hd_sp_d_new',now(),'$ucode')")
                or die("Error: " . __LINE__ . mysql_error());

            $_REQUEST[hd_sp_d] = (int)$_REQUEST[hd_sp_d];
            //echo "entered category is".$_REQUEST[hd_sp_d];

            if (($_REQUEST[hd_sp_d] == 349) || ($_REQUEST[hd_sp_d] == 118) || ($_REQUEST[hd_sp_d] == 119) || ($_REQUEST[hd_sp_d] == 120) || ($_REQUEST[hd_sp_d] == 121) || ($_REQUEST[hd_sp_d] == 122) || ($_REQUEST[hd_sp_d] == 123) || ($_REQUEST[hd_sp_d] == 124) || ($_REQUEST[hd_sp_d] == 125) || ($_REQUEST[hd_sp_d] == 126) || ($_REQUEST[hd_sp_d] == 127) || ($_REQUEST[hd_sp_d] == 128) || ($_REQUEST[hd_sp_d] == 129) || ($_REQUEST[hd_sp_d] == 130) || ($_REQUEST[hd_sp_d] == 131) || ($_REQUEST[hd_sp_d] == 132) || ($_REQUEST[hd_sp_d] == 133) || ($_REQUEST[hd_sp_d] == 318) || ($_REQUEST[hd_sp_d] == 332) || ($_REQUEST[hd_sp_d] == 567) || ($_REQUEST[hd_sp_d] == 568) || ($_REQUEST[hd_sp_d] == 569) || ($_REQUEST[hd_sp_d] == 570) || ($_REQUEST[hd_sp_d] == 571) || ($_REQUEST[hd_sp_d] == 572) || ($_REQUEST[hd_sp_d] == 573) || ($_REQUEST[hd_sp_d] == 574) || ($_REQUEST[hd_sp_d] == 575) || ($_REQUEST[hd_sp_d] == 576) || ($_REQUEST[hd_sp_d] == 577) || ($_REQUEST[hd_sp_d] == 578) || ($_REQUEST[hd_sp_d] == 579) || ($_REQUEST[hd_sp_d] == 580) || ($_REQUEST[hd_sp_d] == 581) || ($_REQUEST[hd_sp_d] == 582)) {

                $sql_update_main = "update main set section_id=32 where diary_no=$dairy_no and (casetype_id in(5,6) or active_casetype_id in(5,6) )";
                $rs_update_main = mysql_query($sql_update_main);
            }
            //}

            if ($verify_req_page == 'Y') {

                //other category
                $other_category = array('10', '20', '46', '75', '87', '101', '115', '129', '141', '151', '163', '182', '201', '215', '227', '250', '259', '262', '270', '276', '289', '295', '300', '304', '311');
                if (in_array($_REQUEST[hd_sp_d], $other_category) && $other_cat_rem != '') {
                    $check_dno_qr = "select * from other_category where diary_no='$dairy_no' and display='Y'";
                    $check_dno_rs = mysql_query($check_dno_qr)   or die("Error: " . __LINE__ . mysql_error());
                    if (mysql_num_rows($check_dno_rs) > 0) {
                        //echo "update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'";
                        $update_other_cat = mysql_query("update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'");


                        $insert_other_cat = mysql_query("Insert Into  other_category (diary_no,submaster_id,remarks,ent_user,ent_datetime,display) values 
        ('$dairy_no','$_REQUEST[hd_sp_d]','$other_cat_rem','$ucode',now(),'Y')")
                            or die("Error: " . __LINE__ . mysql_error());
                    } else {

                        $insert_other_cat = mysql_query("Insert Into  other_category (diary_no,submaster_id,remarks,ent_user,ent_datetime,display) values 
        ('$dairy_no','$_REQUEST[hd_sp_d]','$other_cat_rem','$ucode',now(),'Y')")
                            or die("Error: " . __LINE__ . mysql_error());
                    }
                } else {
                    $check_dno_qr = "select * from other_category where diary_no='$dairy_no' and display='Y'";
                    $check_dno_rs = mysql_query($check_dno_qr)   or die("Error: " . __LINE__ . mysql_error());
                    if (mysql_num_rows($check_dno_rs) > 0) {
                        //echo "update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'";
                        $update_other_cat = mysql_query("update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'");
                    }
                }
            }
        }
    }
}
