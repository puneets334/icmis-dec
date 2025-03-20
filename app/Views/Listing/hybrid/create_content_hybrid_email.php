<?php
date_default_timezone_set('Asia/kolkata');
$cur_dttime = date('d-m-Y H:i:s');
$startTime = explode(' ', microtime());
set_time_limit(30000);
include("/var/www/html/supreme_court/includes/db_inc.php");
mysql_query("SET SESSION group_concat_max_len = 10000000000");
$header = ''; $subject = '';
$header = "MIME-Version: 1.0" . "\r\n";// Always set content-type when sending HTML email
$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$header .= 'From: <sci@nic.in>' . "\r\n"; //"mphc@mphc.in"; // sender
//$header .= "Reply-To: sci@nic.in\r\n";
//$header .= "Return-Path: sci@nic.in\r\n";
$header .= "Disposition-Notification-To: sci@nic.in\n";
$header .= "X-Confirm-Reading-To: sci@nic.in\n";

//START ADVOCATE CASES LISTED
$sql = "INSERT INTO email_hc_cl (title,name,email,diary_no,next_dt,mainhead,court,judges,roster_id,board_type,brd_slno, ent_time, cno, jnames, pname, rname, qry_from)           
SELECT j.* FROM (SELECT title, NAME, email, diary_no, next_dt, mainhead, court, 
judges, roster_id, board_type, brd_slno, NOW(),
IF(reg_no_display = '', CONCAT('Diary No. ',diary_no), CONCAT(reg_no_display)) AS cno,
(SELECT GROUP_CONCAT(jname ORDER BY judge_seniority) FROM roster_judge r INNER JOIN judge j ON j.jcode = r.judge_id WHERE r.roster_id = a.roster_id
GROUP BY r.roster_id) jnm, pname, rname, 'hybrid'
FROM 
(
SELECT b.title, b.name, b.email, m.active_fil_no, m.reg_no_display, 
m.diary_no, a.advocate_id, h.next_dt, h.mainhead, h.judges, h.roster_id, h.board_type, h.clno, h.brd_slno, 

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
LEFT JOIN bar b ON b.bar_id = a.advocate_id AND b.isdead != 'Y' AND b.email REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND b.email IS NOT NULL  
LEFT JOIN roster r ON r.id = h.roster_id 
WHERE a.diary_no IS NOT NULL AND b.bar_id IS NOT NULL AND r.id is not null
AND h.brd_slno > 0 AND (h.main_supp_flag = 1 OR h.main_supp_flag =2) 

AND h.next_dt > curdate()
  
GROUP BY email, diary_no) a) j 
LEFT JOIN email_hc_cl l ON j.diary_no = l.diary_no AND j.email = l.email AND l.next_dt = j.next_dt
AND l.mainhead = j.mainhead AND l.roster_id = j.roster_id AND l.brd_slno = j.brd_slno AND l.qry_from = 'hybrid'
WHERE l.diary_no IS NULL";
$rs = mysql_query($sql) or die(mysql_errno());
echo "<br/>";

echo "<br/>";
$sq_we = "SELECT email,title,name, diary_no, 
GROUP_CONCAT(h.from_dt,'##', h.to_dt,'##', h.list_type_id,'##', h.list_number,'##', h.list_year,'##',cno,'##',court,'##',jnames,'##',pname,'##',rname ORDER BY h.from_dt, court SEPARATOR '~~') tt
FROM email_hc_cl e 
inner join (select hc.diary_no as hb_diary_no, hf.list_type_id, hf.list_number, hf.list_year, hc.from_dt, hc.to_dt from hybrid_physical_hearing_consent_freeze hf
inner join hybrid_physical_hearing_consent hc on hc.list_number = hf.list_number and hc.list_year = hf.list_year
and hc.list_type_id = hf.list_type_id and hc.court_no = hf.court_no
where date(hf.to_date) > curdate() and hf.is_active = 't') h on e.diary_no = h.hb_diary_no 
where # e.next_dt > curdate() and 
sent_to_smspool = 'N' and qry_from = 'hybrid' GROUP BY email";
$rs_we = mysql_query($sq_we) or die(mysql_error());
if(mysql_num_rows($rs_we)>0){
    $testing_mail = 1;
    $subject = "Cases for Hybrid Physical Hearing - Supreme Court of India";  
    while($ro_we = mysql_fetch_array($rs_we)){  
        $title = $ro_we['title'];
        $aname = $ro_we['name'];
        
        $email = strtolower($ro_we['email']);
        $tt_expl = explode("~~",$ro_we['tt']);
        $sno = 1;
        for($i=0;$i<count($tt_expl);$i++){
            $tt_ro = explode("##",$tt_expl[$i]);
            $from_dt = date('d-m-Y', strtotime($tt_ro[0]));
            $to_dt = date('d-m-Y', strtotime($tt_ro[1]));
            $list_type_id = $tt_ro[2];
            $list_number = $tt_ro[3];
            $list_year = $tt_ro[4];

            $cno = $tt_ro[5]; $court = $tt_ro[6]; $jnames = $tt_ro[7];
            $pname = $tt_ro[8]; $rname = $tt_ro[9];
            if($list_type_id == 1){ $list_name = "WEEKLY LIST NO. ".$list_number." OF ".$list_year." : ".$from_dt." To ".$to_dt; }
            
            $content1 .= "<tr><td>".$sno++."</td>"; 
            $content1 .= "<td>".$cno."</td>";            
            $content1 .= "<td>".stripslashes($jnames)."</td>";
            $content1 .= "<td style='text-align:center;'>".$court."</td>";
            $content1 .= "<td style='text-align:center;'>".$pname."<br/>Vs.<br/>".$rname."</td>";                        
            $content1 .= "</tr>"; 
        }
        $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";     
        $message .= "<br/>".ucfirst(strtolower($title))." ".ucwords(strtolower($aname)).", <br/><br/>";
        $message .= "<div style='padding-left:5em'>";
        $message .= "Following matters are likely to be listed through Hybrid Physical Hearing mode : <br/><br/>";
        $message .= $list_name."<br/><br/>";
        $message .= "<style> table, td{border:1px solid black;padding: 4px;}</style>";
        $message .= "<table border='1' style='color: #00220d; border-collapse:collapse; border:1px solid #7a0707; font-family:verdana; font-size:13px; font-weight:bold'>";
        $message .= "<tr>"; 
        $message .= "<td style='background:#FFCCFF; padding:4px;'>SNo.</td>";
        $message .= "<td style='background:#FFCCFF; padding:4px;'>Case No.</td>";
        $message .= "<td style='background:#FFCCFF; padding:4px;'>Hon'ble Court</td>";
        $message .= "<td style='background:#FFCCFF; padding:4px;'>Court No.</td>";                 
        $message .= "<td style='background:#FFCCFF; padding:4px;'>Petitioner/Respondent</td>";
        $message .= "</tr>"; 
        $message .= $content1;         
        $message .= "</table>";
        $message .= "<br/>For e-Nomination of Counsel/Clerk, Self Declaration & Special Hearing Pass etc. Please <a target='_blank' href='https://registry.sci.gov.in/court/hearings/hybrid_physical_hearing'>click here</a><br/></div>";
        $message .= "<br/><div style='font-family:verdana; font-size:13px; font-weight:bold'><span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
        $message .= "</div>";
        $message .= "<br/><br/><br/><font color='#009900' face='Webdings' size='4'></font><font color='#009900' face='verdana,arial,helvetica' size='2'> <strong>Please consider the environment before printing this email<br/><br/>This is an electronic message. Please do not reply to this email.</strong></font>";            
        $message .= "</body></html>";
        $message = wordwrap($message,70);
        echo $message;
        if($testing_mail == 1){
            mail("kbalkasaiya@gmail.com",$subject,$message,$header);                    
        }
        $testing_mail++;
        $rslt_mail = 1;
        //$rslt_mail = mail($email,$subject,$message,$header);                     
        if($rslt_mail){               
               $sq_ins_pt = "UPDATE email_hc_cl SET sent_to_smspool = 'Y' WHERE email = '".$ro_we['email']."' and qry_from = 'hybrid'";
               mysql_query($sq_ins_pt) or die(mysql_error());                     
        }
        $message = ""; $content1 = "";
        echo "<br/><br/>";
    }
}


//$endTime = explode(' ', microtime());
//
//$message = 'create_content_email.php page started<br><br>Programme Started Time : <b>'.$cur_dttime.'</b><br/>Programme processed in <b/>'.round((($endTime[1] + $endTime[0]) - ($startTime[1] + $startTime[0])), 4).'</b> seconds.';
//$subject = "Case Listed SCI email ".date('d-m-Y');
//mail("kbalkasaiya@gmail.com",$subject,$message,$header);
$message = "";
mysql_close();
?>