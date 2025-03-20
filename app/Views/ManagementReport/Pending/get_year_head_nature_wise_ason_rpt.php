<?php
/*$db_host = "172.16.180.69";
$db_user = "anshul";
$db_password = "anshul";
$db_dbname = "sci_cmis";
$db1 = mysql_connect($db_host, $db_user, $db_password);
mysql_select_db($db_dbname, $db1);*/
include('../includes/db_inc.php');
set_time_limit(200000);
if (!$db1) {
    echo "Error : Unable to open database\n";
}

function tot_case_in_nature($nature)
{
    $qry_nature = "SELECT * FROM  casetype  where nature='" . $nature . "'  and display='Y' order by  nature, skey";
    $res_nature = mysql_query($qry_nature);
    return mysql_affected_rows();
}

function stagename($scode)
{
    $query = "select * from subheading where display='Y' and stagecode in (" . $scode . ") order by stagecode";
    $result = mysql_query($query);
    /*if(mysql_affected_rows()>0)
   {*/
    while ($r = mysql_fetch_array($result))
        return $r['stagename'];
    /*}
	else return '';*/
}



$q = "SELECT * FROM  casetype  where display='Y'  order by nature, skey";
$res = mysql_query($q) or die(mysql_error());
$aff = mysql_affected_rows();
$j = 1;
while ($r = mysql_fetch_array($res)) {
    if ($j == $aff)
        $str = $str . " sum( CASE WHEN  IF(active_casetype_id=0,casetype_id ,active_casetype_id) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END ) AS " . $r['skey'] . " ";
    else
        $str = $str . " sum( CASE WHEN  IF(active_casetype_id=0,casetype_id,active_casetype_id) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END ) AS " . $r['skey'] . " ,";

    $j++;
    // echo $j;
}

if ($_GET['bench'] == 'all')
    $bench = '';
elseif ($_GET['bench'] == '2')
    $bench = " and h.judges like '%,%'";
elseif ($_GET['bench'] == '3')
    $bench = " and h.judges like '%,%,%' ";
elseif ($_GET['bench'] == '5')
    $bench = " and h.judges like '%,%,%,%,%'";
elseif ($_GET['bench'] == '7')
    $bench = " and h.judges like '%,%,%,%,%,%,%'";
elseif ($_GET['bench'] == '9')
    $bench = " and h.judges like '%,%,%,%,%,%,%,%,%,%'";
else
    $bench = " and h.judges not like '%%,%'";



if ($_GET['ason_type'] == 'dt') {
    $til_date = explode("-", $_GET['til_date']);
    $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

    $ason_str = " IF(d.rj_dt != '0000-00-00',d.rj_dt >= '" . $til_dt . "',
              IF( d.`disp_dt` != '0000-00-00' AND d.`disp_dt` IS NOT NULL ,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";


    $ason_str_res = " IF(disp_rj_dt != '0000-00-00',disp_rj_dt >= '" . $til_dt . "',
              IF( r.disp_dt != '0000-00-00' AND r.disp_dt IS NOT NULL ,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01') >= '" . $til_dt . "'	 )    )  ";

    $exclude_cond = " CASE WHEN r.`disp_dt` != '0000-00-00' AND r.`disp_dt` IS NOT NULL 
            AND r.conn_next_dt != '0000-00-00'  AND r.conn_next_dt IS NOT NULL
       THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND `conn_next_dt` 
       ELSE  r.`disp_dt` = '0000-00-00' OR r.`disp_dt` IS NULL OR r.conn_next_dt = '0000-00-00' OR r.conn_next_dt IS NULL 
	   END 
  OR r.fil_no IS NULL	";

    $exclude_cond_other = " CASE WHEN r.`disp_dt` != '0000-00-00' AND r.`disp_dt` IS NOT NULL 
            AND r.conn_next_dt != '0000-00-00'  AND r.conn_next_dt IS NOT NULL
       THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND `conn_next_dt` 
       ELSE  r.`disp_dt` = '0000-00-00' OR r.`disp_dt` IS NULL OR r.conn_next_dt = '0000-00-00' OR r.conn_next_dt IS NULL 
	   END 
	";
} else
if ($_GET['ason_type'] == 'month') {
    $til_dt = $_GET['lst_year'] . "-" . str_pad($_GET['lst_month'], 2, "0", STR_PAD_LEFT) . "-01";

    $ason_str = " IF(d.rj_dt != '0000-00-00',d.rj_dt >= '" . $til_dt . "', 
                IF(d.month =0,d.disp_dt >='" . $til_dt . "', concat(d.year,'-',lpad(d.month,2,0),'-01' ) >= '" . $til_dt . "' 
				  ) 
			) ";

    $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00',r.disp_rj_dt >= '" . $til_dt . "', 
                IF(r.disp_month =0,r.disp_dt >='" . $til_dt . "', concat(r.disp_year,'-',lpad(r.disp_month,2,0),'-01' ) >= '" . $til_dt . "' 
				  ) 
			) ";

    $exclude_cond = " CASE 
WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
WHEN  r.month != '0' AND r.month IS NOT NULL 
THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END OR r.fil_no IS NULL 	";

    $exclude_cond_other = " CASE 
WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
THEN '" . $til_dt . "' NOT BETWEEN concat(r.disp_year,'-',lpad(r.disp_month,2,'0'),'-01') 
AND concat(r.year,'-',lpad(r.month,2,'0'),'-01') 
WHEN  r.month != '0' AND r.month IS NOT NULL 
THEN concat(r.year,'-',lpad(r.month,2,'0'),'-01')!='" . $til_dt . "'
ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL END 	";
} else
if ($_GET['ason_type'] == 'ent_dt') {
    $til_date = explode("-", $_GET['til_date']);
    $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

    $ason_str = " d.ent_dt >= '" . $til_dt . "' ";

    $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "' ";


    $exclude_cond = " CASE WHEN date(r.entry_date) != '0000-00-00' AND r.`entry_date` IS NOT NULL 
            AND date(r.disp_ent_dt) != '0000-00-00'  AND r.disp_ent_dt IS NOT NULL
  THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND `entry_date` 
  ELSE  date(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR date(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL  END 
  OR r.fil_no IS NULL	";

    $exclude_cond_other = " CASE WHEN date(r.entry_date) != '0000-00-00' AND r.`entry_date` IS NOT NULL 
            AND date(r.disp_ent_dt) != '0000-00-00'  AND r.disp_ent_dt IS NOT NULL
  THEN '" . $til_dt . "' NOT BETWEEN date(r.disp_ent_dt) AND `entry_date` 
  ELSE  date(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR date(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL  END ";
}


if ($_GET['rpt_purpose'] == 'sw') {
    $subhead_name = "subhead_n";
    $mainhead_name = "mainhead_n";
} else {
    $subhead_name = "subhead";
    $mainhead_name = "mainhead";
}
if ($_GET['subhead'] == 'all,' || $_GET['subhead'] == '') {
    $subhead = '';
    $subhead_if_heardt = " ";
    $subhead_if_last_heardt = " ";
    $subhead_condition = " ";
    $head_subhead = ' ';
} else {
    $subhead = "  and l." . $subhead_name . " in (" . substr($_GET['subhead'], 0, -1) . ")";
    $subhead_if_heardt = " and h." . $subhead_name . " in (" . substr($_GET['subhead'], 0, -1) . ") ";
    $subhead_if_last_heardt = " and f2." . $subhead_name . " in (" . substr($_GET['subhead'], 0, -1) . ") ";

    $subhead_if_heardt_con = "  h." . $subhead_name . " in (" . substr($_GET['subhead'], 0, -1) . ") ";
    $subhead_if_last_heardt_con = "  f2." . $subhead_name . " in (" . substr($_GET['subhead'], 0, -1) . ") ";

    if ($_GET['til_date'] != date('d-m-Y')) {
        $subhead_condition = " AND
if(date(h.ent_dt)<'" . $til_dt . "' and date(h.ent_dt)>med," . $subhead_if_heardt_con . "," . $subhead_if_last_heardt_con . "  )";
        $head_subhead = stagename(substr($_GET['subhead'], 0, -1));
    } else {
        $subhead_condition = "  AND " . $subhead_if_heardt_con;
        $head_subhead = stagename(substr($_GET['subhead'], 0, -1));
    }
}





if ($_GET['concept'] == 'new') {

    if ($_GET['mf'] == 'M') {
        $mf_f2_table = " f2." . $mainhead_name . "= 'M' AND (admitted='' OR admitted IS NULL)";
        $mf_h_table = " h." . $mainhead_name . "= 'M' AND (admitted='' OR admitted IS NULL)";
    }
    if ($_GET['mf'] == 'F') {
        $mf_f2_table = " (f2." . $mainhead_name . "= 'F' OR (admitted!='' AND admitted IS NOT NULL) ) ";
        $mf_h_table = "( h." . $mainhead_name . "= 'F' OR (admitted!='' AND admitted IS NOT NULL) )";
        //$mf_f2_table=" mainhead_n = 'F' OR (admitted != '' AND admitted IS NOT NULL) ";
    }
    if ($_GET['mf'] == 'N') {
        $mf_f2_table = " (f2." . $mainhead_name . " not in ('M','F') ) ";
        $mf_h_table = "( h." . $mainhead_name . " not in ('M','F') )";
        //$mf_f2_table=" mainhead_n = 'F' OR (admitted != '' AND admitted IS NOT NULL) ";
    }
} elseif ($_GET['concept'] == 'old') {
    if ($_GET['mf'] == 'M') {
        $mf_f2_table = " f2." . $mainhead_name . "= '" . $_GET['mf'] . "' ";
        $mf_h_table = " h." . $mainhead_name . "= '" . $_GET['mf'] . "' ";
    }
    if ($_GET['mf'] == 'F') {
        $mf_f2_table = " f2." . $mainhead_name . "= '" . $_GET['mf'] . "'  ";
        $mf_h_table = " h." . $mainhead_name . "= '" . $_GET['mf'] . "'  ";
    }
    if ($_GET['mf'] == 'N') {
        $mf_f2_table = " (f2." . $mainhead_name . " not in ('M','F') ) ";
        $mf_h_table = "( h." . $mainhead_name . " not in ('M','F') )";
        //$mf_f2_table=" mainhead_n = 'F' OR (admitted != '' AND admitted IS NOT NULL) ";
    }
}


//if(trim($_GET['subject'])!='all,' || trim($_GET['act'])!='all,' || trim($_GET['act_msc'])!='' || trim($_GET['spl_case'])!='')
if (trim($_GET['subject']) != 'all,' || trim($_GET['act']) != 'all,' || trim($_GET['act_msc']) != '') {
    //$mul_cat_join=" left join mul_category mc on mc.fil_no=m.fil_no ";
    $mul_cat_join = " LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no
				LEFT JOIN submaster s ON mc.submaster_id = s.id";
} else {
    $mul_cat_join = " ";
}



if (trim($_GET['subcat2']) == 'all,') {
    if (trim($_GET['subcat']) == 'all,') {
        if (trim($_GET['cat']) == 'all,') {
            if (trim($_GET['subject']) == 'all,') {
                $all_category = " ";
            } else
                $all_category = "  s.subcode1 in (" . substr($_GET['subject'], 0, -1) . ")";
        } else {
            $head1 = explode(',', $_GET['cat']);
            for ($m = 0; $m < $_GET['cat_length']; $m++) {
                $head = explode('|', $head1[$m]);
                if ($m == 0)
                    $str_all_cat = "  (s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . ")";
                else
                    $str_all_cat = " (( s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . ") OR " . $str_all_cat . ")";
            }
            $all_category = $str_all_cat;
        }
    } else {
        $head1 = explode(',', $_GET['subcat']);
        for ($m = 0; $m < $_GET['subcat_length']; $m++) {
            $head = explode('|', $head1[$m]);

            if ($m == 0)
                $str_all_cat = "  (s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . " and s.subcode3=" . $head[2] . ")";
            else
                $str_all_cat = " (( s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . " and s.subcode3=" . $head[2] . ") OR " . $str_all_cat . ")";
        }

        $all_category = $str_all_cat;
    }
} else {
    $head1 = explode(',', $_GET['subcat2']);
    for ($m = 0; $m < $_GET['subcat2_length']; $m++) {
        $head = explode('|', $head1[$m]);

        if ($m == 0)
            $str_all_cat = "  (s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . " and s.subcode3=" . $head[2] . " and s.subcode4=" . $head[3] . ")";
        else
            $str_all_cat = " (( s.subcode1 =" . $head[0] . " and s.subcode2=" . $head[1] . " and s.subcode3=" . $head[2] . " and s.subcode4=" . $head[3] . ") OR " . $str_all_cat . ")";
    }

    $all_category = $str_all_cat;
}


if (trim($_GET['act']) == 'all,') {
    $all_act = " ";
} else {
    if (trim($_GET['subject']) == 'all,')
        $all_act = " a.act in (" . substr($_GET['act'], 0, -1) . ")";
    else
        $all_act = " or a.act in (" . substr($_GET['act'], 0, -1) . ")";
}

if (trim($_GET['act']) == 'all,' && trim($_GET['subject']) == 'all,')
    $cat_and_act = " ";
else
    $cat_and_act = " and ( " . $all_category . " " . $all_act . " )";



if ($_GET['from_year'] == '' || $_GET['to_year'] == '') {
    if ($_GET['from_year'] == '' && $_GET['to_year'] != '') {
        $year_main = " AND substr(m.diary_no, -4) <= '" . $_GET['to_year'] . "' ";
        $year_lastheardt = " AND substr(l.diary_no, -4) <= '" . $_GET['to_year'] . "' ";
    } elseif ($_GET['from_year'] != '' && $_GET['to_year'] == '') {
        $year_main = " AND substr(m.diary_no, -4) >= '" . $_GET['from_year'] . "' ";
        $year_lastheardt = " AND substr(l.diary_no, -4) >= '" . $_GET['from_year'] . "' ";
    } else {
        $year_main = " ";
        $year_lastheardt = " ";
    }
} else {
    $year_main = " AND substr(m.diary_no, -4) BETWEEN '" . $_GET['from_year'] . "' AND '" . $_GET['to_year'] . "' ";
    $year_lastheardt = " AND substr(l.diary_no, -4) BETWEEN '" . $_GET['from_year'] . "' AND '" . $_GET['to_year'] . "' ";
}

$Brep = "";
$Brep1 = "";
if ($_GET['from_fil_dt'] == '')
    $from_fil_dt = " ";
else {
    $ffdt = explode("-", $_GET['from_fil_dt']);
    $from_fil_date = $ffdt[2] . "-" . $ffdt[1] . "-" . $ffdt[0];

    $from_fil_dt = " AND date( m.diary_no_rec_date) >'" . $from_fil_date . "' ";
}

if ($_GET['upto_fil_dt'] == '')
    $upto_fil_dt = " ";
else {
    $ufdt = explode("-", $_GET['upto_fil_dt']);
    $upto_fil_date = $ufdt[2] . "-" . $ufdt[1] . "-" . $ufdt[0];

    $upto_fil_dt = " AND date( m.diary_no_rec_date) <'" . $upto_fil_date . "' ";
}
$add_table = '';

if ($_GET['case_status_id'] == 'all,') {
    $case_status_id = " and case_status_id in (1, 2, 3, 6, 7, 9 ) ";
    $add_table = '';
} elseif ($_GET['case_status_id'] == 103) {
    $case_status_id = " ";
    $registration = " ";
} elseif ($_GET['case_status_id'] == 101) {
    $registration = " and (active_fil_no ='' OR active_fil_no IS NULL) ";
} elseif ($_GET['case_status_id'] == 102) {
    $registration = " and !(active_fil_no='' OR active_fil_no IS NULL) ";
} elseif ($_GET['case_status_id'] == 104) {
    $Brep = " INNER JOIN
(SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
 INNER JOIN docdetails b ON m.diary_no = b.diary_no
 LEFT OUTER JOIN
(SELECT DISTINCT diary_no FROM obj_save WHERE
(rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
os ON m.diary_no=os.diary_no
 WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
AND(
(doccode = '8' AND doccode1 = '28') OR 
(doccode = '8' AND doccode1 = '95') OR 
(doccode = '8' AND doccode1 = '214') OR 
(doccode = '8' AND doccode1 = '215')
)
AND b.iastat='P') aa ON m.diary_no=aa.dd ";
} elseif ($_GET['case_status_id'] == 105) {
    $Brep = " INNER JOIN
(SELECT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
 INNER JOIN docdetails b ON m.diary_no = b.diary_no
 LEFT OUTER JOIN
(SELECT DISTINCT diary_no FROM obj_save WHERE
(rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
os ON m.diary_no=os.diary_no
 WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
AND(
(doccode = '8' AND doccode1 = '16') OR 
(doccode = '8' AND doccode1 = '79') OR 
(doccode = '8' AND doccode1 = '99') OR 
(doccode = '8' AND doccode1 = '300')
)
AND b.iastat='P') aa ON m.diary_no=aa.dd ";
} elseif ($_GET['case_status_id'] == 106) {
    $Brep = " LEFT OUTER JOIN
                    (SELECT DISTINCT diary_no FROM obj_save WHERE
                    (rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
                    os ON m.diary_no=os.diary_no
                    ";
    $Brep1 = " and os.diary_no IS NOT NULL and c_status = 'P' AND (active_fil_no IS NULL OR  active_fil_no='') AND h.board_type='J'";
} elseif ($_GET['case_status_id'] == 107) {
    $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
INNER JOIN
(SELECT DISTINCT diary_no FROM obj_save WHERE
(rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y' AND DATEDIFF(NOW(),save_dt)>60) os
ON m.diary_no=os.diary_no ";
    $Brep1 = " and m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
} elseif ($_GET['case_status_id'] == 108) {
    $Brep = " INNER JOIN docdetails b ON m.diary_no=b.diary_no
INNER JOIN
(SELECT DISTINCT diary_no FROM obj_save WHERE
(rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y' AND DATEDIFF(NOW(),save_dt)<=60) os
ON m.diary_no=os.diary_no ";
    $Brep1 = " and  m.c_status = 'P' AND (m.active_fil_no IS NULL OR  m.active_fil_no='')
AND doccode = '8' AND doccode1 = '226' AND b.iastat='P' ";
} elseif ($_GET['case_status_id'] == 109) {
    $Brep = " LEFT JOIN (SELECT DISTINCT CASE WHEN os.diary_no IS NULL THEN m.diary_no ELSE 0 END AS dd FROM main m
 INNER JOIN docdetails b ON m.diary_no = b.diary_no
 LEFT OUTER JOIN
(SELECT DISTINCT diary_no FROM obj_save WHERE
(rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
os ON m.diary_no=os.diary_no
 WHERE  c_status = 'P' AND (active_fil_no IS NULL OR active_fil_no='')
AND (((
(doccode = '8' AND doccode1 = '28') OR 
(doccode = '8' AND doccode1 = '95') OR 
(doccode = '8' AND doccode1 = '214') OR 
(doccode = '8' AND doccode1 = '215') OR 
(doccode = '8' AND doccode1 = '16') OR 
(doccode = '8' AND doccode1 = '79') OR 
(doccode = '8' AND doccode1 = '99') OR 
(doccode = '8' AND doccode1 = '300') OR
(doccode = '8' AND doccode1 = '226') OR 
(doccode = '8' AND doccode1 = '288') OR 
(doccode = '8' AND doccode1 = '322')
)
AND b.iastat='P' ))) aa ON m.diary_no=aa.dd
LEFT OUTER JOIN
                    (SELECT DISTINCT diary_no FROM obj_save WHERE
                    (rm_dt IS NULL OR rm_dt='0000-00-00 00:00:00') AND display='Y')
                    os1 ON m.diary_no=os1.diary_no ";
    $Brep1 = " and m.c_status = 'P' AND IF((m.active_fil_no IS NULL OR m.active_fil_no=''),(aa.dd !=0 OR (os1.diary_no IS NOT NULL AND h.board_type='J')),3=3) ";
}
/*elseif($_GET['case_status_id']==101){
$case_status_id=" and o.rm_dt = '0000-00-00 00:00:00' 
    AND o.display = 'Y' 
    AND m.c_status = 'P' 
    AND (m.fil_no IS NULL 
      OR m.fil_no = '')"; 
$add_table=' LEFT JOIN obj_save o ON o.diary_no = m.diary_no ';
}
elseif($_GET['case_status_id']==102){
//$case_status_id=" and m.diary_no NOT IN (SELECT DISTINCT diary_no FROM `obj_save` WHERE rm_dt = '0000-00-00 00:00:00' AND display = 'Y' ) "; 
 $case_status_id=" and (!(m.fil_no IS NULL OR m.fil_no = '')) "; 
    $add_table='';
}*/ else {
    $case_status_id = " and case_status_id in (" . substr($_GET['case_status_id'], 0, -1) . ")";
    $add_table = '';
}

if ($_GET['mf'] != 'ALL') {
    if ($_GET['til_date'] != date('d-m-Y')) {
        echo '<br>' .
            $t = "create TEMPORARY TABLE vw2 
						SELECT diary_no,max( ent_dt ) med," . $subhead_name . "," . $mainhead_name . "
						FROM `last_heardt` l
						WHERE date(ent_dt) < '" . $til_dt . "' " . $year_lastheardt . " 
						GROUP BY diary_no  ";
        mysql_query($t);

        echo '<br>' .
            $t2 = "CREATE INDEX id_index ON vw2 (diary_no) ";
        mysql_query($t2);

        echo '<br>' .
            $t3 = "create TEMPORARY TABLE vw3 SELECT l.diary_no, l." . $subhead_name . ", l.judges, med,next_dt,l." . $mainhead_name . "
				FROM vw2 
				INNER JOIN last_heardt l ON vw2.diary_no = l.diary_no
				AND l.ent_dt = med
				AND l." . $mainhead_name . " = '" . $_GET['mf'] . "'  " . $subhead;
        mysql_query($t3);

        echo '<br>' .
            $t4 = "CREATE INDEX id_index2 ON vw3 (diary_no) ";
        mysql_query($t4);
    } //if($_GET['til_date']!=date('d-m-Y'))
}



if ($_GET['mf'] != 'ALL') {
    if ($_GET['til_date'] != date('d-m-Y'))
        $sql = "
select  substr(diary_no, -4) year, " . $str . "  from 
 (
	SELECT m.diary_no,m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt,active_casetype_id,casetype_id
	FROM `main` m " . $Brep . " 
	LEFT JOIN heardt h ON m.diary_no = h.diary_no 
	LEFT JOIN dispose d ON m.diary_no = d.diary_no
	LEFT JOIN restored r ON m.diary_no = r.diary_no
	LEFT JOIN vw3 f2 ON m.diary_no = f2.diary_no 
	LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
	WHERE 1=1 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . "
	and if(med>h.ent_dt AND f2." . $mainhead_name . " is not null ," . $mf_f2_table . "  " . $subhead_if_last_heardt . "," . $mf_h_table . " " . $subhead_if_last_heardt . ") " . $main_connected . "
 and  
(
 " . $exclude_cond . "
 ) " . $subhead_condition . " and date( m.diary_no_rec_date ) < '" . $til_dt . "' AND  c_status = 'P' 
	 
	OR 
	(
	 c_status = 'D' " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . "
	 and if(med>h.ent_dt AND f2." . $mainhead_name . " is not null ," . $mf_f2_table . " " . $subhead_if_last_heardt . "," . $mf_h_table . " " . $subhead_if_last_heardt . ")
	 AND  " . $ason_str . " AND date( m.diary_no_rec_date ) < '" . $til_dt . "' " . $year_main . "  " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . "  " . $bench . " " . $pc_act . " " . $women . " " . $children . "  " . $land . "  " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . " " . $main_connected . "
	)
	OR ( 
      " . $ason_str_res . "
	  and if(med>h.ent_dt AND f2." . $mainhead_name . " is not null ," . $mf_f2_table . " " . $subhead_if_last_heardt . "," . $mf_h_table . " " . $subhead_if_last_heardt . ")
	   AND date( m.diary_no_rec_date ) < '" . $til_dt . "' " . $year_main . "  " . $from_fil_dt . " " . $upto_fil_dt . " " . $cat_and_act . " " . $bench . " 
	   " . $pc_act . " " . $women . " " . $children . "  " . $land . "  " . $cr_compound . " " . $commercial_code . " " . $party_name . " " . $pet_res . " " . $act_msc . " AND " . $exclude_cond_other . " " . $main_connected . "
	)
	
	GROUP BY m.diary_no
)t
  
GROUP BY  substr( diary_no, -4) with rollup";


    else
        $sql = "
select  substr(diary_no, -4) year, " . $str . "  from 
(
	SELECT m.diary_no,m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt,active_casetype_id,casetype_id
	FROM `main` m " . $Brep . " 
	LEFT JOIN dispose d ON m.diary_no = d.diary_no
	LEFT JOIN heardt h ON m.diary_no = h.diary_no 
	LEFT JOIN restored r ON m.diary_no = r.diary_no
	LEFT JOIN act_main a ON a.diary_no = m.diary_no
	" . $add_table . $mul_cat_join . " " . $act_join . "
	WHERE  " . $registration . " " . $mf_h_table . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . "  " . $case_status_id . $Brep1 . "
	and case_status_id in (1,2,3,6,7,9) 
	and ( c_status = 'P' AND date( m.diary_no_rec_date ) < '" . $til_dt . "') " . $subhead_condition . "
	GROUP BY m.diary_no
)t
GROUP BY  substr( diary_no, -4) with rollup";
} else {
    if ($_GET['til_date'] != date('d-m-Y'))
        $sql = "
select  substr(diary_no, -4) year, " . $str . "  from 
(
	SELECT m.diary_no,m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt,active_casetype_id,casetype_id
	FROM `main` m " . $Brep . " 
	LEFT JOIN heardt h ON m.diary_no = h.diary_no 
	LEFT JOIN dispose d ON m.diary_no = d.diary_no 
	LEFT JOIN restored r ON m.diary_no = r.diary_no 
	LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
	WHERE 1=1  " . $Brep1 . $registration . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . "   " . $main_connected . " and 
(
  " . $exclude_cond . "
 ) and date( m.diary_no_rec_date ) < '" . $til_dt . "' AND  c_status = 'P' 
	
	OR 
	(
	 c_status = 'D' AND  " . $ason_str . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " AND date( m.diary_no_rec_date ) < '" . $til_dt . "' AND " . $exclude_cond_other . "  " . $main_connected . "
	)
	GROUP BY m.diary_no
)t
GROUP BY  substr( diary_no, -4) with rollup";

    else
        $sql = "
select  substr(diary_no, -4) year, " . $str . "  from 
(
	SELECT m.diary_no,m.fil_dt, c_status, d.rj_dt, d.month, d.year, d.disp_dt,active_casetype_id,casetype_id
	FROM `main` m " . $Brep . " 
	LEFT JOIN dispose d ON m.diary_no = d.diary_no
	LEFT JOIN restored r ON m.diary_no = r.diary_no
	LEFT JOIN heardt h ON m.diary_no = h.diary_no 
	LEFT JOIN act_main a ON a.diary_no = m.diary_no " . $add_table . $mul_cat_join . " " . $act_join . "
	WHERE 2=2 " . $Brep1 . $registration . " " . $bench . " " . $cat_and_act . " " . $year_main . " " . $from_fil_dt . " " . $upto_fil_dt . " " . $case_status_id . " and ( c_status = 'P' AND date( m.diary_no_rec_date ) <= '" . $til_dt . "')
	GROUP BY m.diary_no
)t
GROUP BY  substr(diary_no, -4) with rollup";
}
//and case_status_id in (1, 2, 3, 6, 7, 9 )
echo $sql;
$rs = mysql_query($sql) or die(mysql_error());
$tot_row = mysql_affected_rows();

if ($tot_row > 0) {
    $civil_colspan = tot_case_in_nature('C');
    $cr_colspan = tot_case_in_nature('R');

?>
    <div id="prnTable" align="center">
        <table cellpadding=1 cellspacing=0 border=1>
            <tr>
                <td colspan=<?php echo ($civil_colspan + $cr_colspan + 3); ?> align="center">
                    <font color=blue size=+1>Year and Nature wise
                        <?php
                        $til_date2 = explode("-", $til_dt);
                        $til_dt2 = $til_date2[2] . "-" . $til_date2[1] . "-" . $til_date2[0];

                        echo $head_subhead . ' pending cases as on ' . $til_dt2; ?></font>
                </td>
            </tr>
            <!-- ************************************** For Second Row *********************************************************	 -->
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <th colspan=<?php echo $civil_colspan; ?> align="center">
                    <font color=blue>CIVIL CASES</font>
                </th>
                <th colspan=<?php echo $cr_colspan; ?> align="center">
                    <font color=blue>CRIMINAL CASES</font>
                </th>
                <th>&nbsp;</th>
            </tr>

            <?php
            $i = 1;
            $total = 0;
            echo '</tr><tr><th>Sno</th><th>Year</th>';

            while ($row = mysql_fetch_array($rs)) {
                if ($_GET['rpt_type'] == 'bench')
                    $bench_or_year = $row['bench'];
                else
                    $bench_or_year = $row['pend_year'];

                $sql_case = "SELECT skey,nature FROM casetype where display='Y' order by  nature, skey";
                $result = mysql_query($sql_case);
                // **************************************** For Second Row *********************************************************	
                if ($i == 1) {
                    while ($row_case = mysql_fetch_array($result))
                        echo "<th>" . $row_case['skey'] . "</th>";
                    echo "<th>Total</th></tr>";
                }

                if ($i == $tot_row) {
                    $year_wise_tot_str = 'all';
            ?><tr>
                        <th colspan=2>Total</th>
                        <?php
                    } else {
                        echo "<tr><td>" . $i . "</td><td>" . $row['year'] . "</td>";
                        $year_wise_tot_str = 'y';
                    }



                    $sql_case1 = "SELECT skey,casecode FROM casetype where  display='Y' order by  nature, skey";
                    $result1 = mysql_query($sql_case1);
                    $year_wise_tot = 0;
                    while ($row_case1 = mysql_fetch_array($result1)) {
                        if ($row[$row_case1[skey]] == "0")
                            $count = "-";
                        else {
                            $count = $row[$row_case1[skey]];
                            $year_wise_tot = $year_wise_tot + $count;
                        }

                        if ($row[$row_case1[skey]] == "0") {
                        ?><td align=right>-</td>
                            <?php
                        } else {
                            if ($tot_row == $i) {
                            ?>
                                <td align=right><span style="cursor: pointer;"><?php echo $count; ?></span></td>
                            <?
                            } else {

                            ?>

                                <td align=right><span style="cursor: pointer;" id="<?php echo "$row[year]" . "_" . "$row_case1[casecode]"; ?>"
                                        onclick="open_tab(
'<?php echo $_GET['nature_wise_tot'] ?>','<?php echo $_GET['subject'] ?>','<?php echo $_GET['subject_length']; ?>',
'<?php echo $_GET['cat']; ?>','<?php echo $_GET['cat_length']; ?>','<?php echo $_GET['subcat']; ?>',
'<?php echo $_GET['subcat_length']; ?>','<?php echo $row['year']; ?>','<?php echo $row_case1['skey']; ?>',
'<?php echo $_GET['subhead']; ?>','<?php echo $_GET['mf']; ?>','<?php echo $_GET['til_date']; ?>',
'<?php echo $_GET['from_year']; ?>','<?php echo $_GET['to_year']; ?>','<?php echo $_GET['rpt_type']; ?>',
'<?php echo $_GET['pet_res']; ?>','<?php echo $_GET['party_name']; ?>','<?php echo $_GET['act_msc']; ?>',
'<?php echo $_GET['lst_month']; ?>','<?php echo $_GET['lst_year']; ?>','<?php echo $_GET['ason_type']; ?>',
'<?php echo $_GET['from_fil_dt']; ?>','<?php echo $_GET['upto_fil_dt']; ?>','<?php echo $_GET['rpt_purpose']; ?>',
'<?php echo $_GET['spl_case']; ?>','<?php echo $_GET['concept']; ?>','<?php echo $_GET['main_connected']; ?>',
'<?php echo $_GET['act']; ?>','<?php echo $_GET['order_by']; ?>','<?php echo $_GET['adv_opt']; ?>',
'<?php echo $_GET['case_status_id'] ?>','<?php echo $_GET['subcat2']; ?>',
'<?php echo $_GET['subcat2_length']; ?>'); " class="ank"><?php echo $count; ?></span>
                                </td>
                    <?php
                            } // else end
                        } // else end
                    } //while end 
                    ?><th align=right><?php echo $year_wise_tot; ?></th>
                    </tr>
                <?php
                $i++;
                //	**************************************** Second Row End*********************************************************
            } // while end
                ?>
        </table>
    </div>
    <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
        &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105;">
        <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><b><img src="../images/close_btn.png" style="width:30px;height:30px" /></b></div>
        <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()">
        </div>
    </div>
    <br><br>
    <div align="center"><input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('prnTable');" value="PRINT"></div>

<?php
} else
    echo "<center><h2>Record Not Found</h2></center>";

echo " <span id='s' align='left'>Date :  " . date('d-m-Y H:i:s') . "</span>";
?>