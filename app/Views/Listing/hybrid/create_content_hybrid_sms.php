<?php
date_default_timezone_set('Asia/kolkata');
$cur_dttime = date('d-m-Y H:i:s');
$startTime = explode(' ', microtime()); 
set_time_limit(30000);
extract($_REQUEST);
$vc_items_csv=implode(',',$vc_items_csv);
//$next_dt;$roster_id;
include("/var/www/html/supreme_court/includes/db_inc.php");  
mysql_query("SET SESSION group_concat_max_len = 10000000000");
//START ADVOCATE CASES LISTED

$sql = "INSERT INTO sms_hc_cl (mobile,diary_no,next_dt,mainhead,court,roster_id,brd_slno, ent_time, cno, qry_from, pet_name, res_name)           
SELECT j.* FROM (SELECT mobile, diary_no, next_dt, mainhead, court, roster_id, brd_slno, NOW(),
IF(reg_no_display = '', 
CONCAT('Diary No. ',diary_no), CONCAT('Case No. ',reg_no_display)), 'hybrid', pname, rname 
FROM 
(
SELECT b.mobile, m.active_fil_no, m.reg_no_display, 
m.diary_no, a.advocate_id, h.next_dt, h.mainhead, h.judges, h.roster_id, h.clno, h.brd_slno, 

IF((h.clno = 50 OR h.clno = 51), 'By Circulation', (CASE 
WHEN r.courtno between 31 and 60 THEN concat('Virtual Court ', r.courtno - 30)
WHEN r.courtno between 61 and 70 THEN concat('Reg. Virtual Court ', r.courtno - 60)
WHEN r.courtno = 21 THEN 'R 1' WHEN r.courtno = 22 THEN 'R 2' ELSE r.courtno END)) AS court, 

(CASE WHEN pno = 2 THEN CONCAT(m.pet_name, ' AND ANR.') WHEN pno > 2 THEN CONCAT(m.pet_name, ' AND ORS.') ELSE m.pet_name END) AS pname, 
(CASE WHEN rno = 2 THEN CONCAT(m.res_name, ' AND ANR.') WHEN rno > 2 THEN CONCAT(m.res_name, ' AND ORS.') ELSE m.res_name END) AS rname
FROM 

(select hc.diary_no as hb_diary_no, hf.list_type_id, hf.list_number, hf.list_year, hc.from_dt, hc.to_dt from hybrid_physical_hearing_consent_freeze hf
inner join hybrid_physical_hearing_consent hc on hc.list_number = hf.list_number and hc.list_year = hf.list_year
and hc.list_type_id = hf.list_type_id and hc.court_no = hf.court_no
where date(hf.to_date) > curdate() and hf.is_active = 't') hb

inner join heardt h on h.diary_no = hb.hb_diary_no
INNER JOIN main m ON m.diary_no = h.diary_no
LEFT JOIN advocate a ON a.diary_no = m.diary_no AND a.display = 'Y' AND a.advocate_id != 0 
LEFT JOIN bar b ON b.bar_id = a.advocate_id AND b.isdead != 'Y' AND LENGTH(b.mobile) = '10' AND SUBSTR(b.mobile, 1, 1) NOT BETWEEN '0' AND '6' AND 
b.name != 'ATTORNEY GENERAL FOR INDIA' AND b.name != 'SOLICITOR GENERAL OF INDIA' AND b.mobile IS NOT NULL
LEFT JOIN roster r ON r.id = h.roster_id
WHERE a.diary_no IS NOT NULL AND b.bar_id IS NOT NULL AND r.id is not null
AND h.brd_slno > 0 AND (h.main_supp_flag = 1 OR h.main_supp_flag =2) 
AND h.next_dt > curdate() 
GROUP BY mobile, diary_no) a) j 
LEFT JOIN sms_hc_cl l ON j.diary_no = l.diary_no AND j.mobile = l.mobile AND l.next_dt = j.next_dt
AND l.mainhead = j.mainhead AND l.roster_id = j.roster_id AND l.brd_slno = j.brd_slno AND l.qry_from = 'hybrid'
WHERE l.diary_no IS NULL";
$rs = mysql_query($sql) or die(mysql_errno());
//echo "<br/><br/>";
//END ADVOCATE CASES LISTED
//START SEND data TO SMS_POOL FROM sms_hc_cl

 $sql = "INSERT INTO sms_pool (mobile,msg,table_name,ent_time,template_id)   
SELECT mobile,
CONCAT(cno,' - ', pet_name,' Vs. ',res_name,' likely to be listed through Hybrid Physical hearing mode in Court No. ',court,'. Please visit https://anu.sci.gov.in/hybrid for more details - Supreme Court of India') cno1, 
'hybrid', NOW(), 'SCISMS_LISTED_FOR_HYBRID' FROM sms_hc_cl WHERE sent_to_smspool = 'N' AND qry_from = 'hybrid' ORDER BY mobile, next_dt, roster_id, diary_no";
 $rs = mysql_query($sql) or die(mysql_errno());
//END SEND data TO SMS_POOL FROM sms_hc_cl

$updt = mysql_query("update sms_hc_cl set sent_to_smspool = 'Y' where sent_to_smspool = 'N' and qry_from = 'hybrid'") or die (mysql_errno());
 


    $cur_dttime = date('d-m-Y H:i:s');
    $startTime = explode(' ', microtime());
    set_time_limit(0);
    $tot_sms = 1; $sms_sleep = 1;
//cont 53/17, wp 183/13, ma 2011/11
echo $sql = "SELECT id, mobile, msg, template_id FROM sms_pool WHERE (c_status = 'N' OR c_status = '1' OR c_status = '2') 
and table_name = 'hybrid' ";
    $rs = mysql_query($sql) or die(mysql_errno());
    $srno = 1;
    if(mysql_num_rows($rs)>0){
        ?>

            <?php
            while($ro = mysql_fetch_array($rs)){
                $sms_pool_id = $ro['id'];//$_REQUEST['mobile'];
                echo $mobile = $ro['mobile'];//$_REQUEST['mobile'];
                //echo $mobile = '9630100950';//$_REQUEST['mobile'];
                echo "<br>";
                echo $cnt = trim($ro['msg']);//$_REQUEST['message'];
                echo "<br><br><br>";
                $from_adr = "sms_module";
                $template_id = trim($ro['template_id']);
                //validations
                if(empty($mobile)){
                  //  echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Mobile No. Empty.</font></td></tr>";
                    //echo "<font color='red'>Mobile No. Empty.</font>";
                }
                else if(empty($cnt)){
                   // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Message content Empty.</font></td></tr>";
                    //echo "<font color='red'>Message content Empty.</font>";
                }
                else if(empty($from_adr)){
                   // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Sender Information Empty Contact to Server Room.</font></td></tr>";
                    //echo "<font color='red'>Sender Information Empty.</font>";
                }
                else if(strlen($mobile) < '10') {
                   // echo "<tr><td>".$srno++."</td><td>".$mobile."</td><td><font color='red'>Not a Proper Mobile No.</font></td></tr>";
//        echo "<font color='red'>Not a Proper Mobile No.</font>";
                }
                else{
                    $frm_adr = trim($from_adr);
                    $sms_lengt = explode(",", trim($mobile));
                    $count_sms = count($sms_lengt);

                    for($k=0; $k<$count_sms;$k++){
                        //echo "<br/>";
                        if(strlen(trim($sms_lengt[$k])) != '10'){
                            //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='red'>Not a proper mobile number.</font></td></tr>";
                        }
                        else if(!is_numeric($sms_lengt[$k])){
                            //not a numeric value
                            //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='red'>Mobile number contains invalid value.</font></td></tr>";
                        }
                        else{
                            //header('Content-type: application/json;');
                            //$mm = trim($sms_lengt[$k]);

                            //echo $mm = '7048963619';

                            //echo 'http://XXXX/eAdminSCI/a-push-sms-gw?mobileNos='.$mm.'&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.$template_id;
                            $template_id = null;

                            if($tot_sms == 1){
                                $homepage = file_get_contents('http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos=9630100950&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.$template_id);
                            }
                            $tot_sms++;
                            $homepage = file_get_contents('http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos='.$mm.'&message='.urlencode($cnt).'&typeId=29&myUserId=NIC001001&myAccessId=root&authCode='.SMS_KEY.'&templateId='.$template_id);
                            $json = json_decode($homepage);
                           //$abcd == "success";
                            //var_dump($json);
                            //if($abcd == "success"){
                            if($json->{'responseFlag'} == "success"){
                                $sql = "update sms_pool set c_status = 'Y', update_time = NOW() where id = '$sms_pool_id'";
                                mysql_query($sql) or die(mysql_errno());
                                //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='green'>Success.</font></td></tr>";
                                //echo "<br/>Sent Successfully to ".$mm." msg ".$cnt." id ".$sms_pool_id;
                            }
                            else{
                                $sql = "update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where id = '$sms_pool_id'";
                                mysql_query($sql) or die(mysql_errno());
                                //echo "<tr><td>".$srno++."</td><td>".$sms_lengt[$k]."</td><td><font color='red'>Error:Not Sent, SMS may send later.</font></td></tr>";
                                //echo "Error:Not Sent ".$mm;
                            }
                        }
                    }

                }


            }//end of while loop
            ?>

        <?php
    }
    else{
        $updt = mysql_query("update sms_pool set c_status = case when c_status = 'N' then 1 when 1 then 2 else 3 end, update_time = NOW() where (c_status = 'N' OR c_status = '1' OR c_status = '2') and table_name = 'hybrid'") or die (mysql_errno());
    }









mysql_close();

?>