<?php
if($_REQUEST['sms_status']!='PWDRESET')
{
   // include ('../extra/lg_out_script.php'); {
   // include("../includes/db_inc.php");
   // include("../anu/response.php");
   // include("../sms_pool/mphc_sms.php");

    $diary_no=$_REQUEST['d_no'].$_REQUEST['d_yr'];
    $doc_id=$_REQUEST['doc_id'];
    $frm='';
    $template_id='';
    $wh_mobileno='';
    $templateCode='';
    $listing_date='';
    if($_REQUEST['sms_status']=='D' || $_REQUEST['sms_status']=='refiling'||$_REQUEST['sms_status']=='DIA')
    {
        if($_REQUEST['sms_status']=='D')
            $frm='Defects';
        else if($_REQUEST['sms_status']=='refiling')
            $frm='Refiling';
            else if($_REQUEST['sms_status']=='DIA')
            $frm='Defects in IA';
        if($_REQUEST['sms_status']=='D')
        {
            //template modified for defect notification
            //$template_id='1107165872917800681';
            $template_id='1107172767309969953';
            $sql_obj=mysql_query("SELECT count(id) FROM obj_save WHERE diary_no = '$diary_no' and display='Y'")
            or die("Error: ".__LINE__.  mysql_error());
        }
        else if( $_REQUEST['sms_status']=='refiling')
        {
            $template_id='1107161234619089003';
            $sql_obj=mysql_query("SELECT count(id) FROM obj_save WHERE diary_no = '$diary_no' and display='Y'
            and rm_dt='0000-00-00 00:00:00'")

            or die("Error: ".__LINE__.  mysql_error());
        }
        if($_REQUEST['sms_status']=='DIA')
        {
            //template modified for defect notification
            //$template_id='1107165872917800681';
            $template_id='1107173286097545052';   //template id to change for defects in IA
        
            $sql_obj=mysql_query("SELECT count(id) FROM obj_save_ia WHERE diary_no = '$diary_no' and docd_id=$doc_id and display='Y'") 
            or die("Error: ".__LINE__.  mysql_error());
        }
        $res_sql_obj=  mysql_result($sql_obj, 0);
        if($res_sql_obj<=0)
        {
            exit();
        }
        else{
            $res_sql_obj=  1;
        }
    }
    else if($_REQUEST['sms_status']=='R')
    {
        $frm='Registration';
        $template_id='1107165881515458494';
        $res_sql_obj=  1;
    }
    else if($_REQUEST['sms_status']=='DN')
    {
        $frm='Diary';
        //$template_id='1107161234603870863';
        $template_id='1107165900206642770';
        $res_sql_obj=  1;
    }

//Password Reset
    else if($_REQUEST['sms_status']=='PWDRESET')
    {
        //$template_id='';
        $empid= $_REQUEST['empid'];
        $password=$_REQUEST['pwd'];
        $mobileno=$_REQUEST['mob'];
        $template_id='1107162764348028579';
        //$res_sql_obj=  -1;
        $res_sql_obj=  2;
//        $testmsg="ICMIS Password has been reset. New Password for Emp ID ".$empid." in ICMIS is ".$password;
        $testmsg="ICMIS Password has been reset. New Password for Emp ID  ".$empid." in ICMIS is ".$password." -  Supreme Court of India";
        $frm = "ResetPassword";
    }
    else if($_REQUEST['sms_status']=='NEXTDAYLISTED'){
        $mobileno=$_REQUEST['mob'];
        $testmsg=$_REQUEST['msg'];
        //$template_id='1107165873011597277';
        $template_id='1107165950597744475';
        //$res_sql_obj=  -1;
        $res_sql_obj=  3;
        $frm = "LOOSEDOC";
    }
    else if($_REQUEST['sms_status']=='scrutiny'){
        $mobileno=$_REQUEST['mob'];
        $testmsg=$_REQUEST['msg'];
        $template_id='1107165872958238165';
        //$res_sql_obj=  -1;
        $res_sql_obj=  2;
        $frm = "MARKED_FOR_SCRUTINY";
    }
    else if($_REQUEST['sms_status']=='CAVEAT_FILING')
    {
        $advocate_mob = "Select mobile from caveat_advocate a join bar b on a.advocate_id=b.bar_id
            where caveat_no='$_REQUEST[caveat_no]' and display='Y' and pet_res='P'";
        $advocate_mob = mysql_query($advocate_mob) or die("Error: " . __LINE__ . mysql_error());


        if (mysql_num_rows($advocate_mob) > 0) {
            $caveat_adv = mysql_fetch_array($advocate_mob);
            if ($caveat_adv['mobile'] != '' && strlen($caveat_adv['mobile']) == '10') {
                $mobileno = $caveat_adv['mobile'];
                $wh_mobileno= "91".$caveat_adv['mobile'];
            }
        }

        $caveat_info="Select pet_name,res_name from caveat where caveat_no='$_REQUEST[caveat_no]'";
        $caveat_rs = mysql_query($caveat_info) or die("Error: " . __LINE__ . mysql_error());
        if (mysql_num_rows($caveat_rs) > 0) {
            $caveat = mysql_fetch_array($caveat_rs);
        }
        $testmsg=$_REQUEST['msg'];
        $template_id='1107166235834498119';
        //$res_sql_obj=  -1;

        $cavyear=substr($_REQUEST['caveat_no'],-4);
        $cavnum=substr($_REQUEST['caveat_no'],0,-4);
        $res_sql_obj=  2;
        $frm = "CAVEAT_FILING";
        $sms_params=array($caveat['pet_name'] . " vs " . $caveat['res_name'] ," registered with Caveat Number ".$cavnum.'/'.$cavyear);
        $purpose='Fresh Caveat Generation';
        $module='Caveat';
        $templateCode="icmis::case::caveat::status";

    }
    else if($_REQUEST['sms_status']=='VERIFY')
    {
        $res_sql_obj=  1;
        $template_id='1107165881523805462';
        $listing_date=date('d-m-Y',strtotime($_REQUEST['next_dt']));
        $testmsg = "Your Case having Diary No.".substr($diary_no,0,-4).'/'.substr($diary_no,-4)." likely to be listed on $listing_date ".". - Supreme Court of India";
        $frm = "Verification";

    }


    if($res_sql_obj>0)
    {
        if($res_sql_obj==1) {
            $mobileno = '';
            $diary_no = $_REQUEST['d_no'] . $_REQUEST['d_yr'];
            $get_pet_res = "Select pet_name,res_name from main where diary_no='$diary_no'";
            $get_pet_res = mysql_query($get_pet_res) or die("Error: " . __LINE__ . mysql_error());
            $r_get_pet_res = mysql_fetch_array($get_pet_res);

            if ($_REQUEST['sms_status'] == 'D') {
                 

                //$testmsg = "The case filed by you with Diary No. " . $_REQUEST[d_no] . '-' . $_REQUEST[d_yr] . " has been notified with " . $res_sql_obj . " objections. Please remove within statutory period.Link to view defects is ".$short_url." - Supreme Court of India";
                $testmsg = "The case filed by you with Diary No. " .$_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " has been notified with objections. Please remove within statutory period. For more details, visit website https://www.sci.gov.in  - Supreme Court of India";
             
                $sms_params=array($r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res['res_name'] ," defective with objections"."(Diary no. ".$_REQUEST['d_no'].'/'.$_REQUEST['d_yr'].")");
                $purpose='Defects Notification';
                $module='Filing';
                $templateCode="icmis::case::diarization_and_registration";
                //    echo "Select contact from  party where diary_no='$diary_no' and  pet_res='P'";
            }
            if ($_REQUEST['sms_status'] == 'DIA') {
               $sql_ia="select docnum,docyear from docdetails where docd_id=$doc_id";
                $res_sql_ia=mysql_query($sql_ia);
                $ia_num = mysql_fetch_array($res_sql_ia);
                $testmsg = "The IA/document filed by you with IA/Document No. " .$ia_num['docnum'] . '-' . $ia_num['docyear'] . "(Diary No.-".$_REQUEST['d_no'].'/'.$_REQUEST['d_yr'].")  has been notified with objections. Please remove within statutory period. For more details, visit website https://www.sci.gov.in  - Supreme Court of India";
                $sms_params=array($ia_num['docnum'] . '-' . $ia_num['docyear']."(Diary No.-".$_REQUEST['d_no'].'/'.$_REQUEST['d_yr'].")");  //to change for defects in IA
                $purpose='Defects Notification in IA';
                $module='Judicial';
                $templateCode="icmis::ia::defect::status";  //template code to change for defects in IA
            }
            else if ($_REQUEST['sms_status'] == 'R') {

                // modified as below on 28.02.2019 $testmsg="The case filed by you with Diary No. ".$_REQUEST[d_no].'-'.$_REQUEST[d_yr].' '.$r_get_pet_res[pet_name].'Vs'.$r_get_pet_res[res_name]. " is succesfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year. " and prepared for listing as per rules.";
                // modified as below on 1.08.2022 $testmsg = "The case filed by you with Diary No. " . $_REQUEST[d_no] . '-' . $_REQUEST[d_yr] . ' ' . $r_get_pet_res[pet_name] . 'Vs' . $r_get_pet_res[res_name] . " is succesfully registered with registration no. " . $res_skey . '-' . $f_no . "/" . $year.". - Supreme Court of India";
                $pet=$r_get_pet_res['pet_name'];
                $res=$r_get_pet_res['res_name'];
                if(strlen($r_get_pet_res['pet_name'])>30){
                    $pet=str_replace(substr($r_get_pet_res['pet_name'], 27, strlen($r_get_pet_res['pet_name'])),'...',$r_get_pet_res['pet_name']) ;
                }
                if(strlen($r_get_pet_res['res_name'])>30){
                    $res=str_replace(substr($r_get_pet_res['res_name'], 27, strlen($r_get_pet_res['res_name'])),'...',$r_get_pet_res['res_name']) ;
                }
                $testmsg="The case filed by you with Diary No. " . $_REQUEST[d_no]."/".$_REQUEST[d_yr] ." - ".$pet." VS ".$res." is successfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year.". - Supreme Court of India";
                $sms_params=array(' with Diary no. '.$_REQUEST['d_no'] . '-' . $_REQUEST[d_yr].' and Cause title- '.$r_get_pet_res['pet_name'] . " vs " . $r_get_pet_res['res_name'] ," registered with Registration Number ".$res_skey.'-'.$f_no."/".$year);
                $purpose='Registration';
                $module='Filing';
                $templateCode="icmis::case::diarization_and_registration";

            }
            else if ($_REQUEST['sms_status'] == 'DN') {

                //          $testmsg="The case filed by you with Diary No. ".$_REQUEST[d_no].'-'.$_REQUEST[d_yr]. " is succesfully registered with registration no. ".$res_skey.'-'.$f_no."/".$year. " and prepared for listing as per rules.";
                date_default_timezone_set('Asia/Kolkata');
                $pet=$pet_cause_title;
                $res=$res_cause_title;
                if(strlen($pet_cause_title)>30){
                    $pet=str_replace(substr($pet_cause_title, 27, strlen($pet_cause_title)),'...',$pet_cause_title) ;
                }
                if(strlen($res_cause_title)>30){
                    $res=str_replace(substr($res_cause_title, 27, strlen($res_cause_title)),'...',$res_cause_title) ;
                }

                $testmsg = "Your case " . $pet . " vs " . $res . " is filed with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " on " . date('d-m-Y H:i:s').'. - Supreme Court of India';

                //$testmsg = "Your case " . $pet_cause_title . " Vs " . $res_cause_title . " is filed with Diary No. " . $_REQUEST[d_no] . '-' . $_REQUEST[d_yr] . " on " . date('d-m-Y H:i:s');
                $sms_params=array($pet_cause_title . " vs " . $res_cause_title ," diarized with  Diary Number ".$_REQUEST['d_no']."/".$_REQUEST['d_yr']);
                $purpose='Fresh Diary Generation';
                $module='Filing';
                $templateCode="icmis::case::diarization_and_registration";
            }
            else if ($_REQUEST['sms_status'] == 'refiling') {
                $testmsg = "The case filed by you with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] . " is still defective having " . $res_sql_obj . " objections. Please collect the same from Re-filing counter. - Supreme Court of India";
                //    echo "Select contact from  party where diary_no='$diary_no' and  pet_res='P'";
            }
            else if($_REQUEST['sms_status']=='VERIFY'){
                $pet=$r_get_pet_res['pet_name'];
                $res=$r_get_pet_res['res_name'];
                $sms_params=array($pet. " vs " . $res ." with Diary No. " . $_REQUEST['d_no'] . '-' . $_REQUEST['d_yr'] ," likely to be listed on ".$listing_date);
                $purpose='Verification';
                $module='FILING';
                $templateCode="icmis::case::diarization_and_registration";
            }
            $sql = mysql_query("Select contact from  party where diary_no='$diary_no' and  pet_res='P' ") or die("Error: " . __LINE__ . mysql_error());
            if (mysql_num_rows($sql) > 0) {
                while ($r_party = mysql_fetch_array($sql)) {
                    if ($r_party['contact'] != '' && strlen($r_party['contact']) == '10') {
                        if ($mobileno == '') {
                            $mobileno = $r_party['contact'];
                            # $wh_mobileno="91".$r_party['contact'];
                        }
                        else {
                            $mobileno = $mobileno . ',' . $r_party['contact'];
                            #  $wh_mobileno=$mobileno . ',' ."91".$r_party['contact'];
                        }
                    }
                }
            }
            if($_REQUEST['sms_status']=='DIA')
            $advocate_mob="select mobile from docdetails d join bar b on d.advocate_id=b.aor_code where docd_id=$doc_id ";
            else
            $advocate_mob = "Select mobile from advocate a join bar b on a.advocate_id=b.bar_id
            where diary_no='$diary_no' and display='Y' and pet_res='P'";
            $advocate_mob = mysql_query($advocate_mob) or die("Error: " . __LINE__ . mysql_error());
            if (mysql_num_rows($advocate_mob) > 0) {
                while ($row = mysql_fetch_array($advocate_mob)) {
                    if ($row['mobile'] != '' && strlen($row['mobile']) == '10') {
                        if ($mobileno == '') {
                            $mobileno = $row['mobile'];
                            $wh_mobileno="91".$row['mobile'];
                        }
                        else {
                            $mobileno = $mobileno . ',' . $row['mobile'];
                            $wh_mobileno=$wh_mobileno . ','."91".$row['mobile'];
                        }
                    }
                }
                //$wh_mobileno.=",919871754198,919871922703,919810003580,919540028941,919881397172,918763332660,919630100950,919987508833,919341218677,918813888057";
                $wh_mobileno=explode(',',$wh_mobileno);
            }
        }
     
        $mo = $mobileno;
        $ms = $testmsg;
        $frm = $frm;
        if($res_sql_obj==3)
            echo $k=SMS_And_Email($mo,$ms,$frm,$template_id);
        else
            echo $k = mphc_sms($mo,$ms,$frm,$template_id);


        $created_by_user= array("name"=>$_SESSION['emp_name_login'],"id"=>$_SESSION['dcmis_user_idd'],"employeeCode"=>$_SESSION['icmic_empid'],"organizationName"=>'SCI');
        $response= send_sms_whatsapp_through_uni_notify(1,$wh_mobileno,$templateCode, $sms_params,null, $purpose,$created_by_user,$module,'ICMIS',null,null, null);

    }
    else
    {
        if($_REQUEST['sms_status']=='D')
        {
            ?>
            <div style="text-align: center">Please enter atleast one defect before sendind SMS.</div>
            <?php
        }
    }
}
