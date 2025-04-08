<?php

function tot_case_in_nature($nature)
{
    $db = \Config\Database::connect();
    $builder = $db->table('casetype');
    $builder->where('nature', $nature);
    $builder->where('display', 'Y');
    $builder->orderBy('nature', 'ASC')->orderBy('skey', 'ASC');
    $query = $builder->get();
    return $query->getNumRows();
}

function stagename($scode)
{
    $db = \Config\Database::connect();
    $builder = $db->table('subheading');
    $builder->select('stagename');
    $builder->where('display', 'Y');
    $builder->whereIn('stagecode', explode(',', $scode));
    $builder->orderBy('stagecode', 'ASC');
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $row = $query->getRowArray();
        return $row['stagename'];
    }
    return '';
}

$db = \Config\Database::connect();
$builder = $db->table('casetype');
$builder->select('*');
$builder->where('display', 'Y');
$builder->orderBy('nature', 'ASC')->orderBy('skey', 'ASC');
$query = $builder->get();
$results = $query->getResultArray();
$aff = count($results);
$j = 1;
$str = '';

foreach ($results as $r) {
    if ($j == $aff) {
        $str .= " SUM(CASE WHEN IF(active_casetype_id=0, casetype_id, active_casetype_id) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ";
    } else {
        $str .= " SUM(CASE WHEN IF(active_casetype_id=0, casetype_id, active_casetype_id) = '" . $r['casecode'] . "' THEN 1 ELSE 0 END) AS " . $r['skey'] . " ,";
    }
    $j++;
}

$bench = '';
switch ($_GET['bench']) {
    case 'all':
        $bench = '';
        break;
    case '2':
        $bench = " AND h.judges LIKE '%,%'";
        break;
    case '3':
        $bench = " AND h.judges LIKE '%,%,%'";
        break;
    case '5':
        $bench = " AND h.judges LIKE '%,%,%,%,%'";
        break;
    case '7':
        $bench = " AND h.judges LIKE '%,%,%,%,%,%,%'";
        break;
    case '9':
        $bench = " AND h.judges LIKE '%,%,%,%,%,%,%,%,%'";
        break;
    default:
        $bench = " AND h.judges NOT LIKE '%%,%'";
        break;
}

if ($_GET['ason_type'] == 'dt') {
    $til_date = explode("-", $_GET['til_date']);
    $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

    $ason_str = " IF(d.rj_dt != '0000-00-00', d.rj_dt >= '" . $til_dt . "', 
        IF(d.`disp_dt` != '0000-00-00' AND d.`disp_dt` IS NOT NULL, d.disp_dt >= '" . $til_dt . "', 
        CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '" . $til_dt . "'))";

    $ason_str_res = " IF(disp_rj_dt != '0000-00-00', disp_rj_dt >= '" . $til_dt . "', 
        IF(r.disp_dt != '0000-00-00' AND r.disp_dt IS NOT NULL, r.disp_dt >= '" . $til_dt . "', 
        CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

    $exclude_cond = " CASE WHEN r.`disp_dt` != '0000-00-00' AND r.`disp_dt` IS NOT NULL 
        AND r.conn_next_dt != '0000-00-00' AND r.conn_next_dt IS NOT NULL 
        THEN '" . $til_dt . "' NOT BETWEEN r.disp_dt AND `conn_next_dt` 
        ELSE r.`disp_dt` = '0000-00-00' OR r.`disp_dt` IS NULL OR r.conn_next_dt = '0000-00-00' OR r.conn_next_dt IS NULL 
        END OR r.fil_no IS NULL";

    $exclude_cond_other = $exclude_cond;
} elseif ($_GET['ason_type'] == 'month') {
    $til_dt = $_GET['lst_year'] . "-" . str_pad($_GET['lst_month'], 2, "0", STR_PAD_LEFT) . "-01";

    $ason_str = " IF(d.rj_dt != '0000-00-00', d.rj_dt >= '" . $til_dt . "', 
        IF(d.month = 0, d.disp_dt >= '" . $til_dt . "', 
        CONCAT(d.year, '-', LPAD(d.month, 2, 0), '-01') >= '" . $til_dt . "'))";

    $ason_str_res = " IF(r.disp_rj_dt != '0000-00-00', r.disp_rj_dt >= '" . $til_dt . "', 
        IF(r.disp_month = 0, r.disp_dt >= '" . $til_dt . "', 
        CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, 0), '-01') >= '" . $til_dt . "'))";

    $exclude_cond = " CASE 
        WHEN r.disp_month != '0' AND r.disp_month IS NOT NULL AND r.month != '0' AND r.month IS NOT NULL 
        THEN '" . $til_dt . "' NOT BETWEEN CONCAT(r.disp_year, '-', LPAD(r.disp_month, 2, '0'), '-01') 
        AND CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') 
        WHEN r.month != '0' AND r.month IS NOT NULL 
        THEN CONCAT(r.year, '-', LPAD(r.month, 2, '0'), '-01') != '" . $til_dt . "' 
        ELSE r.disp_month = '0' OR r.`disp_month` IS NULL OR r.month = '0' OR r.month IS NULL 
        END OR r.fil_no IS NULL";

    $exclude_cond_other = $exclude_cond;
} elseif ($_GET['ason_type'] == 'ent_dt') {
    $til_date = explode("-", $_GET['til_date']);
    $til_dt = $til_date[2] . "-" . $til_date[1] . "-" . $til_date[0];

    $ason_str = " d.ent_dt >= '" . $til_dt . "' ";
    $ason_str_res = " r.disp_ent_dt >= '" . $til_dt . "' ";

    $exclude_cond = " CASE WHEN DATE(r.entry_date) != '0000-00-00' AND r.`entry_date` IS NOT NULL 
        AND DATE(r.disp_ent_dt) != '0000-00-00' AND r.disp_ent_dt IS NOT NULL 
        THEN '" . $til_dt . "' NOT BETWEEN DATE(r.disp_ent_dt) AND `entry_date` 
        ELSE DATE(r.`disp_ent_dt`) = '0000-00-00' OR r.`disp_ent_dt` IS NULL OR DATE(r.entry_date) = '0000-00-00' OR r.entry_date IS NULL 
        END OR r.fil_no IS NULL";

    $exclude_cond_other = $exclude_cond;
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
    $subhead = " AND l." . $subhead_name . " IN (" . substr($_GET['subhead'], 0, -1) . ")";
    $subhead_if_heardt = " AND h." . $subhead_name . " IN (" . substr($_GET['subhead'], 0, -1) . ") ";
    $subhead_if_last_heardt = " AND f2." . $subhead_name . " IN (" . substr($_GET['subhead'], 0, -1) . ") ";

    $subhead_if_heardt_con = " h." . $subhead_name . " IN (" . substr($_GET['subhead'], 0, -1) . ") ";
    $subhead_if_last_heardt_con = " f2." . $subhead_name . " IN (" . substr($_GET['subhead'], 0, -1) . ") ";

    if ($_GET['til_date'] != date('d-m-Y')) {
        $subhead_condition = " AND IF(DATE(h.ent_dt) < '" . $til_dt . "' AND DATE(h.ent_dt) > med, " . $subhead_if_heardt_con . ", " . $subhead_if_last_heardt_con . ")";
        $head_subhead = stagename(substr($_GET['subhead'], 0, -1));
    } else {
        $subhead_condition = " AND " . $subhead_if_heardt_con;
        $head_subhead = stagename(substr($_GET['subhead'], 0, -1));
    }
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
                <td colspan=<?= ($civil_colspan + $cr_colspan + 3); ?> align="center">
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
                <th colspan=<?= $civil_colspan; ?> align="center">
                    <font color=blue>CIVIL CASES</font>
                </th>
                <th colspan=<?= $cr_colspan; ?> align="center">
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
                                <td align=right><span style="cursor: pointer;"><?= $count; ?></span></td>
                            <?
                            } else {

                            ?>

                                <td align=right><span style="cursor: pointer;" id="<?= "$row[year]" . "_" . "$row_case1[casecode]"; ?>"
                                        onclick="open_tab(
'<?= $_GET['nature_wise_tot'] ?>','<?= $_GET['subject'] ?>','<?= $_GET['subject_length']; ?>',
'<?= $_GET['cat']; ?>','<?= $_GET['cat_length']; ?>','<?= $_GET['subcat']; ?>',
'<?= $_GET['subcat_length']; ?>','<?= $row['year']; ?>','<?= $row_case1['skey']; ?>',
'<?= $_GET['subhead']; ?>','<?= $_GET['mf']; ?>','<?= $_GET['til_date']; ?>',
'<?= $_GET['from_year']; ?>','<?= $_GET['to_year']; ?>','<?= $_GET['rpt_type']; ?>',
'<?= $_GET['pet_res']; ?>','<?= $_GET['party_name']; ?>','<?= $_GET['act_msc']; ?>',
'<?= $_GET['lst_month']; ?>','<?= $_GET['lst_year']; ?>','<?= $_GET['ason_type']; ?>',
'<?= $_GET['from_fil_dt']; ?>','<?= $_GET['upto_fil_dt']; ?>','<?= $_GET['rpt_purpose']; ?>',
'<?= $_GET['spl_case']; ?>','<?= $_GET['concept']; ?>','<?= $_GET['main_connected']; ?>',
'<?= $_GET['act']; ?>','<?= $_GET['order_by']; ?>','<?= $_GET['adv_opt']; ?>',
'<?= $_GET['case_status_id'] ?>','<?= $_GET['subcat2']; ?>',
'<?= $_GET['subcat2_length']; ?>'); " class="ank"><?= $count; ?></span>
                                </td>
                    <?php
                            } // else end
                        } // else end
                    } //while end 
                    ?><th align=right><?= $year_wise_tot; ?></th>
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