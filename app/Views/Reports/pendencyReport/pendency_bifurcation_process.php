<?php


$dt1=$_POST['dt1'];
$tdt1=date('d-m-Y', strtotime($dt1));
$for_date = date('Y-m-d', strtotime($dt1));


$sql_bifurcation="select count(DISTINCT diary_no) as pending, sum(case when mf_active!='F' then 1  else 0 end) as misc_pending,
sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R') then 1 else 0 end) complete,
sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and (board_type = 'J' OR board_type = 'S') then 1 else 0 end) complete_court,

sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and board_type = 'C' then 1 else 0 end) complete_chamber,
sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and board_type = 'R' then 1 else 0 end) complete_registrar,

sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and board_type = 'C' then 1 else 0 end) incomplete_chamber,
sum(case when mf_active!='F' and main_supp_flag in (0,1,2) and board_type = 'R' then 1 else 0 end) incomplete_registrar,

sum(case when mf_active!='F' and !(main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R')) then 1 else 0 end) misc_incomlete_not_updated,
sum(case when mf_active!='F' and (!(main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R')) OR (main_supp_flag in (0,1,2) and board_type IN ('R','C'))) then 1 else 0 end) misc_incomplete,

sum(case when mf_active='F' then 1  else 0 end) as final_pending,
sum(case when mf_active='F' and main_supp_flag in (0,1,2) then 1 else 0 end) ready,
sum(case when mf_active='F' and !(main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R')) then 1 else 0 end) final_not_ready,
sum(case when (case_grp='C' or case_grp is null) then 1 else 0 end) civil_pendency,
sum(case when case_grp='R' then 1 else 0 end) criminal_pendency,
sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) more_than_one_year_old,
sum(case when fil_dt >= date(DATE_SUB(now(), INTERVAL 1 YEAR)) then 1 else 0 end) less_than_one_year_old,
sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 5 YEAR)) then 1 else 0 end) more_than_five_year_old,
sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 10 YEAR)) then 1 else 0 end) more_than_ten_year_old,
sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 15 YEAR)) then 1 else 0 end) more_than_fifteen_year_old,
sum(case when date(fil_dt) < date(DATE_SUB(now(), INTERVAL 20 YEAR)) then 1 else 0 end) more_than_twenty_year_old
from
(SELECT
    distinct diary_no,mf_active,main_supp_flag,board_type,case_grp,fil_dt
FROM
    (SELECT
        m.diary_no,
            m.fil_dt,
            c_status,
            d.rj_dt,
            d.month,
            d.year,
            d.disp_dt,
            active_casetype_id,
            casetype_id,
            m.mf_active,
            h.main_supp_flag,
            h.board_type,
            m.case_grp
    FROM
        main m
    LEFT JOIN heardt h ON m.diary_no = h.diary_no
    LEFT JOIN dispose d ON m.diary_no = d.diary_no
    LEFT JOIN restored r ON m.diary_no = r.diary_no
    WHERE
        1 = 1 and board_type IN ('J','S','C','R')
            AND (CASE
            WHEN
                DATE(r.`disp_dt`) != '0000-00-00'
                    AND r.`disp_dt` IS NOT NULL
                    AND DATE(r.conn_next_dt) != '0000-00-00'
                    AND r.conn_next_dt IS NOT NULL
            THEN
                '".$for_date."' NOT BETWEEN DATE(r.disp_dt) AND DATE(`conn_next_dt`)
            ELSE DATE(r.`disp_dt`) = '0000-00-00'
                OR r.`disp_dt` IS NULL
                OR DATE(r.conn_next_dt) = '0000-00-00'
                OR r.conn_next_dt IS NULL
        END
            OR r.fil_no IS NULL)
            AND IF(DATE(unreg_fil_dt) != '0000-00-00'
            AND (DATE(unreg_fil_dt) <= DATE(m.fil_dt)
            OR DATE(m.fil_dt) = '0000-00-00'), DATE(unreg_fil_dt) <= '".$for_date."', (DATE(m.fil_dt) <= '".$for_date."'
            AND DATE(fil_dt) != '0000-00-00'))
            AND c_status = 'P'
            OR (c_status = 'D'
            AND IF(DATE(d.rj_dt) != '0000-00-00', DATE(d.rj_dt) >= '".$for_date."'
            AND DATE(d.rj_dt) >= '01-01-1950'
            AND !( DATE(d.rj_dt) > CURDATE()), IF(DATE(d.`disp_dt`) != '0000-00-00'
            AND DATE(d.`disp_dt`) IS NOT NULL, DATE(d.disp_dt) >= '".$for_date."'
            AND DATE(d.disp_dt) >= '01-01-1950'
            AND !( DATE(d.disp_dt) > CURDATE()), CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '".$for_date."'
            AND DATE(d.disp_dt) >= '01-01-1950'
            AND !( DATE(d.disp_dt) > CURDATE())))
            AND IF(DATE(unreg_fil_dt) != '0000-00-00'
            AND (DATE(unreg_fil_dt) <= DATE(m.fil_dt)
            OR DATE(m.fil_dt) = '0000-00-00'), DATE(unreg_fil_dt) <= '".$for_date."', (DATE(m.fil_dt) <= '".$for_date."'
            AND DATE(fil_dt) != '0000-00-00'))
            AND CASE
            WHEN
                DATE(r.`disp_dt`) != '0000-00-00'
                    AND r.`disp_dt` IS NOT NULL
                    AND DATE(r.conn_next_dt) != '0000-00-00'
                    AND r.conn_next_dt IS NOT NULL
            THEN
                '".$for_date."' NOT BETWEEN DATE(r.disp_dt) AND DATE(`conn_next_dt`)
            ELSE DATE(r.`disp_dt`) = '0000-00-00'
                OR r.`disp_dt` IS NULL
                OR DATE(r.conn_next_dt) = '0000-00-00'
                OR r.conn_next_dt IS NULL
        END)
            AND (SUBSTR(m.fil_no, 1, 2) NOT IN (39)
            OR m.fil_no = ''
            OR m.fil_no IS NULL)
    GROUP BY m.diary_no) a
)temp";
$res_bifurcation = mysql_query($sql_bifurcation) or die(mysql_error());
$result_bifurcation = mysql_fetch_array($res_bifurcation);
//$incomplete = (int)$result_bifurcation['misc_pending']-(int)$result_bifurcation['complete'];
$incomplete = (int)$result_bifurcation['misc_incomplete'];
$not_ready = (int)$result_bifurcation['final_not_ready'];
//$not_ready = (int)$result_bifurcation['final_pending']-(int)$result_bifurcation['ready'];
$pendency_head1 = (int)$result_bifurcation['pending'];
$pendency_difference = (int)$result_bifurcation['pending'] - (int)$pendency_head1;

$totalNotReady=$incomplete+$not_ready;
$percentage=((int)$totalNotReady*100)/(int)$pendency_head1;

$sqlConstitution="select count(distinct m.diary_no) as tot_constitution,
count(distinct case when (m.conn_key=m.diary_no or m.conn_key is null or m.conn_key='' or m.conn_key=0) then m.diary_no else null end) as main_constitution,
count(distinct case when (m.conn_key!=m.diary_no and m.conn_key is not null and m.conn_key!='' and m.conn_key!=0) then m.diary_no else null end) as connected_constitution
FROM main m INNER JOIN heardt h ON m.diary_no = h.diary_no
INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
INNER JOIN submaster s ON mcat.submaster_id = s.id
WHERE c_status = 'P' and mcat.display = 'Y' and s.display = 'Y'
# and  DATE(mcat.e_date) <= '".$for_date."'
and s.subcode1 in (20,21,22,23)";
$res_constitution = mysql_query($sqlConstitution) or die(mysql_error());
$result_constitution = mysql_fetch_array($res_constitution);

$sqlReferred="select count(distinct m.diary_no) as referred
FROM main m INNER JOIN case_remarks_multiple mcat ON m.diary_no = mcat.diary_no
WHERE c_status = 'P' and DATE(mcat.e_date) <= '".$for_date."'
and mcat.r_head=174";
$res_referred = mysql_query($sqlReferred) or die(mysql_error());
$result_referred = mysql_fetch_array($res_referred);
//echo $pendency_difference;

$sql_connected="select # count(DISTINCT diary_no) as pending_connected,
 sum(case when (diary_no != conn_key and conn_key > 0) then 1 else 0 end) pending_connected,
 sum(case when (diary_no = conn_key or conn_key = 0 or conn_key = '' or conn_key is null) then 1 else 0 end) pending_main

from
(SELECT
    distinct diary_no,mf_active,main_supp_flag,board_type,case_grp,fil_dt, conn_key
FROM
    (SELECT
        m.diary_no,
            m.fil_dt,
            c_status,
            d.rj_dt,
            d.month,
            d.year,
            d.disp_dt,
            active_casetype_id,
            casetype_id,
            m.mf_active,
            h.main_supp_flag,
            h.board_type,
            m.case_grp,
            m.conn_key


    FROM
        main m
    LEFT JOIN heardt h ON m.diary_no = h.diary_no
    LEFT JOIN dispose d ON m.diary_no = d.diary_no
    LEFT JOIN restored r ON m.diary_no = r.diary_no
    WHERE
        1 = 1 and board_type IN ('J','S','C','R')
        # and (m.diary_no != m.conn_key and m.conn_key > 0)
            AND (CASE
            WHEN
                DATE(r.`disp_dt`) != '0000-00-00'
                    AND r.`disp_dt` IS NOT NULL
                    AND DATE(r.conn_next_dt) != '0000-00-00'
                    AND r.conn_next_dt IS NOT NULL
            THEN
                '".$for_date."' NOT BETWEEN DATE(r.disp_dt) AND DATE(`conn_next_dt`)
            ELSE DATE(r.`disp_dt`) = '0000-00-00'
                OR r.`disp_dt` IS NULL
                OR DATE(r.conn_next_dt) = '0000-00-00'
                OR r.conn_next_dt IS NULL
        END
            OR r.fil_no IS NULL)
            AND IF(DATE(unreg_fil_dt) != '0000-00-00'
            AND (DATE(unreg_fil_dt) <= DATE(m.fil_dt)
            OR DATE(m.fil_dt) = '0000-00-00'), DATE(unreg_fil_dt) <= '".$for_date."', (DATE(m.fil_dt) <= '".$for_date."'
            AND DATE(fil_dt) != '0000-00-00'))
            AND c_status = 'P'
            OR (c_status = 'D'
            AND IF(DATE(d.rj_dt) != '0000-00-00', DATE(d.rj_dt) >= '".$for_date."'
            AND DATE(d.rj_dt) >= '01-01-1950'
            AND !( DATE(d.rj_dt) > CURDATE()), IF(DATE(d.`disp_dt`) != '0000-00-00'
            AND DATE(d.`disp_dt`) IS NOT NULL, DATE(d.disp_dt) >= '".$for_date."'
            AND DATE(d.disp_dt) >= '01-01-1950'
            AND !( DATE(d.disp_dt) > CURDATE()), CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '".$for_date."'
            AND DATE(d.disp_dt) >= '01-01-1950'
            AND !( DATE(d.disp_dt) > CURDATE())))
            AND IF(DATE(unreg_fil_dt) != '0000-00-00'
            AND (DATE(unreg_fil_dt) <= DATE(m.fil_dt)
            OR DATE(m.fil_dt) = '0000-00-00'), DATE(unreg_fil_dt) <= '".$for_date."', (DATE(m.fil_dt) <= '".$for_date."'
            AND DATE(fil_dt) != '0000-00-00'))
            AND CASE
            WHEN
                DATE(r.`disp_dt`) != '0000-00-00'
                    AND r.`disp_dt` IS NOT NULL
                    AND DATE(r.conn_next_dt) != '0000-00-00'
                    AND r.conn_next_dt IS NOT NULL
            THEN
                '".$for_date."' NOT BETWEEN DATE(r.disp_dt) AND DATE(`conn_next_dt`)
            ELSE DATE(r.`disp_dt`) = '0000-00-00'
                OR r.`disp_dt` IS NULL
                OR DATE(r.conn_next_dt) = '0000-00-00'
                OR r.conn_next_dt IS NULL
        END)
            AND (SUBSTR(m.fil_no, 1, 2) NOT IN (39)
            OR m.fil_no = ''
            OR m.fil_no IS NULL)
    GROUP BY m.diary_no) a group by diary_no
)temp";
$res_connected = mysql_query($sql_connected) or die(mysql_error());
$result_connected = mysql_fetch_array($res_connected);
$pending_connected = (int)$result_connected['pending_connected'];
//$pending_main_exluded_connected = ($result_bifurcation['pending']-$pendency_difference) - $result_connected['pending_connected'];
$pending_main_exluded_connected = ($result_connected['pending_main']);



date_default_timezone_set("Asia/Kolkata");
echo "<br>";
echo "<table class='table_tr_th_w_clr c_vertical_align' border=1><tr><td colspan='4' align='center'><h4>Bifurcation of Pending Registered matters as On  ".$tdt1."</h4></td></tr>";
echo "<tr><td colspan='4' align='right'>[generated on".date("d-m-Y h:i:s A")." ]</td></tr>";
echo "<tr><td>Number of Admission hearing matters</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Number_of_Admission_hearing_matters' target='_blank'>".($result_bifurcation['misc_pending']-$pendency_difference)."</a></td></tr>";


echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Complete</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=complete_court' target='_blank'>".($result_bifurcation['complete_court']-$pendency_difference) ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  InComplete</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=misc_incomplete' target='_blank'>".($result_bifurcation['misc_incomplete']-$pendency_difference) ."</a></td></tr>";

echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Chamber</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=incomplete_chamber' target='_blank'>".($result_bifurcation['incomplete_chamber']-$pendency_difference) ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Registrar</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=incomplete_registrar' target='_blank'>".($result_bifurcation['incomplete_registrar']-$pendency_difference) ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Not Updated</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=incomplete_not_updated' target='_blank'>".($result_bifurcation['misc_incomlete_not_updated']-$pendency_difference)."</a></td></tr>";

echo "<tr><td>Number of Regular hearing matters</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=final_pending' target='_blank'>".$result_bifurcation['final_pending']."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Ready</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Regular_Ready' target='_blank'>".$result_bifurcation['ready'] ."</a></td></tr>";
echo "<tr style='padding:10px;'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Not Ready</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Regular_Not_Ready' target='_blank'>".$not_ready."</a></td></tr>";
echo "<tr><td>Number of Civil matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=civil_pendency' target='_blank'>".($result_bifurcation['civil_pendency']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Number of Criminal matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=criminal_pendency' target='_blank'>".$result_bifurcation['criminal_pendency']."</a></td></tr>";
echo "<tr><td>More than 1 year old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=more_than_one_year_old' target='_blank'>".($result_bifurcation['more_than_one_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Less than 1 year old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=less_than_one_year_old' target='_blank'>".$result_bifurcation['less_than_one_year_old']."</a></td></tr>";
echo "<tr><td>Total Pendency</td><td align='right' style='font-weight: bold;'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=total_pending' target='_blank'>".($result_bifurcation['pending']-$pendency_difference)."</a></td></tr>";

echo "<tr><td>Total Connected</td><td align='right' ><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Total_Connected' target='_blank'>".$pending_connected."</a></td></tr>";
echo "<tr><td>Pendency after excluding connected matters </td><td align='right' ><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Pendency_after_excluding_connected' target='_blank'>".(int)$pending_main_exluded_connected."</a></td></tr>";

echo "<tr><td>More than 5 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=more_than_five_year_old' target='_blank'>".($result_bifurcation['more_than_five_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 10 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=more_than_ten_year_old' target='_blank'>".($result_bifurcation['more_than_ten_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 15 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=more_than_fifteen_year_old' target='_blank'>".($result_bifurcation['more_than_fifteen_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>More than 20 years old matters</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=more_than_twenty_year_old' target='_blank'>".($result_bifurcation['more_than_twenty_year_old']-$pendency_difference)."</a></td></tr>";
echo "<tr><td>Constitution matters (Subject Cat. 20,21,22,23)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=tot_constitution' target='_blank'>".$result_constitution['tot_constitution']."</a></td></tr>";
echo "<tr><td>Referred matters (Reffered to Larger Bench)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=referred' target='_blank'>".$result_referred['referred']."</a></td></tr>";
echo "<tr><td>Total (Incomplete + Not Ready)</td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Incomplete_Not_Ready' target='_blank'>".$totalNotReady."</a></td></tr>";
echo "<tr><td>Percentage of (Incomplete + Not Ready) with Total Pendency </td><td align='right'><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Incomplete_Not_Ready' target='_blank'>".round($percentage,2)."</a></td></tr>";
echo "</table> ";

$first_date=date('01-m-Y', strtotime($dt1));
$last_date= date('t-m-Y', strtotime($dt1));
?>
<br/>

<table cellpadding=1 cellspacing=0 border=1 >
    <tr >
        <th colspan="4"> Constitution Bench Matters Classification</th>
    </tr>
    <tr><td>&nbsp;</td><td>Total</td><td>Main</td><td>Connected</td></tr>
    <?php
    $sqlConstitutionBench="select subcode1, sub_name1,count(distinct m.diary_no) as tot_constitution,
count(distinct case when (m.conn_key=m.diary_no or m.conn_key is null or m.conn_key='' or m.conn_key=0) then m.diary_no else null end) as main_constitution,
count(distinct case when (m.conn_key!=m.diary_no and m.conn_key is not null and m.conn_key!='' and m.conn_key!=0) then m.diary_no else null end) as connected_constitution
FROM main m
INNER JOIN heardt h on h.diary_no = m.diary_no
INNER JOIN mul_category mcat ON m.diary_no = mcat.diary_no
INNER JOIN submaster s ON mcat.submaster_id = s.id
WHERE c_status = 'P' and mcat.display = 'Y' and s.display = 'Y' and  DATE(mcat.e_date) <= '".$for_date."'
and s.subcode1 in (20,21,22,23) group by sub_name1 order by subcode1";
    $res_constitutionBench = mysql_query($sqlConstitutionBench) or die(mysql_error());
    while($row =mysql_fetch_array($res_constitutionBench)){
        echo "<tr><td>".$row['sub_name1']."</td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Total_$row[subcode1]' target='_blank'>".$row['tot_constitution']."</a></td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Main_$row[subcode1]' target='_blank'>".$row['main_constitution']."</a></td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=conn_$row[subcode1]' target='_blank'>".$row['connected_constitution']."</a></td></tr>";
    }
    ?>
</table>
<br>

<table cellpadding=1 cellspacing=0 border=1 >
    <tr >
        <th colspan="2"> <?php echo "Total Cases between $first_date and $last_date";?></th>
    </tr>
    <?php

    $sql_notice="select crm.r_head,crh.head, count(distinct diary_no) as tot_cases from  case_remarks_multiple crm inner join
case_remarks_head crh on crm.r_head=crh.sno where r_head in (3,181,182,183,184) and cl_date between '".date('Y-m-d', strtotime($first_date))."' and '".date('Y-m-d', strtotime($last_date))."'
group by crm.r_head,crh.head order by crm.r_head";

    $res_notice = mysql_query($sql_notice) or die(mysql_error());
    while($row =mysql_fetch_array($res_notice)){
        echo "<tr><td>".$row['head']."</td>";
        echo "<td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=Notice_$row[r_head]' target='_blank'>".$row['tot_cases']."</a></td></tr>";
    }

    $sql_inlimine="select count(1) as tot_matters from main m inner join
(select diary_no,count(next_dt) as no_of_times_listed from
(select diary_no,next_dt, clno, roster_id, judges from heardt where clno!=0 and clno is not null and brd_slno!=0 and brd_slno is not null
and roster_id!=0 and roster_id is not null and (board_type='J' OR board_type='S') and mainhead='M' and next_dt between '".date('Y-m-d', strtotime($dt1))."' and '".date('Y-m-d', strtotime($dt2))."'
union
select diary_no,next_dt, clno, roster_id, judges from last_heardt where clno!=0 and clno is not null and brd_slno!=0 and brd_slno is not null
and roster_id!=0 and roster_id is not null and (board_type='J' OR board_type='S') and mainhead='M' and (bench_flag='' or bench_flag is null)
and next_dt between '".date('Y-m-d', strtotime($first_date))."' and '".date('Y-m-d', strtotime($last_date))."') listed group by diary_no) listed_count
on m.diary_no=listed_count.diary_no where m.c_status='D' and no_of_times_listed=1";

    $res_inlimine = mysql_query($sql_inlimine) or die(mysql_error());
    $result_inlimine = mysql_fetch_array($res_inlimine);
    echo "<tr><td>In Limine Cases</td><td><a style='text-decoration: none;' href='pendency_bifurcation_process_detail.php?ason=$for_date&flag=In_Limine' target='_blank'>".$result_inlimine['tot_matters']."</a></td></tr>";
    echo "</table>";


    ?>
    <div align="center"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('r_box');" value="PRINT"></div>
    <p>*Accurate bifurcation figures are only possible for pendency as on date</p>

