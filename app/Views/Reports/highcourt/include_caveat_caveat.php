<?php

if ($_REQUEST['u_t'] == '1') {

    $ddl_court = '';
    $txt_order_date = '';
    $ddl_bench = '';
    $ddl_st_agncy = '';
    $ddl_ref_case_type = '';
    $txt_ref_caseno = '';
    $ddl_ref_caseyr = '';

    $ddl_court_t = '';
    $txt_order_date_t = '';
    $ddl_bench_t = '';
    $ddl_st_agncy_t = '';
    $ddl_ref_case_type_t = '';
    $txt_ref_caseno_t = '';
    $ddl_ref_caseyr_t = '';

    $cur_date = date('Y-m-d');


    if ($_REQUEST['ddl_court'] != '') {
        $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
        $ddl_court_t = "  a.ct_code = b.ct_code";
    }
    if ($_REQUEST['txt_order_date'] != '') {
        $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
        $txt_order_date = " and lct_dec_dt = '$_REQUEST[txt_order_date]'";
        $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
    }
    if ($_REQUEST['ddl_bench'] != '') {
        $ddl_bench = " and  l_dist = '$_REQUEST[ddl_bench]'";
        $ddl_bench_t = " AND a.l_dist = b.l_dist";
    }
    if ($_REQUEST['ddl_st_agncy'] != '') {
        $ddl_st_agncy = " and  l_state = '$_REQUEST[ddl_st_agncy]'";
        $ddl_st_agncy_t = " AND a.l_state = b.l_state";
    }
    if ($_REQUEST['ddl_ref_case_type'] != '') {
        $ddl_ref_case_type = " and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
        $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
    }
    if ($_REQUEST['txt_ref_caseno'] != '') {
        $txt_ref_caseno = " and  lct_caseno = '$_REQUEST[txt_ref_caseno]'";
        $txt_ref_caseno_t = " and a.lct_caseno=b.lct_caseno";
    }
    if ($_REQUEST['ddl_ref_caseyr'] != '') {
        $ddl_ref_caseyr = " and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
        $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
    }
    $fst = intval($_REQUEST['nw_hd_fst']);
    $inc_val = intval($_REQUEST['inc_val']);
} else {
    $ddl_court = '';
    $txt_order_date = '';
    $ddl_bench = '';
    $ddl_st_agncy = '';
    $ddl_ref_case_type = '';
    $txt_ref_caseno = '';
    $ddl_ref_caseyr = '';

    $ddl_court_t = '';
    $txt_order_date_t = '';
    $ddl_bench_t = '';
    $ddl_st_agncy_t = '';
    $ddl_ref_case_type_t = '';
    $txt_ref_caseno_t = '';
    $ddl_ref_caseyr_t = '';




    if ($_REQUEST['ddl_court'] != '') {
        $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
        $ddl_court_t = "  a.ct_code = b.ct_code";
    }
    if ($_REQUEST['txt_order_date'] != '') {
        $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
        $txt_order_date = " and lct_dec_dt = '$_REQUEST[txt_order_date]'";
        $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
    }
    if ($_REQUEST['ddl_bench'] != '') {
        $ddl_bench = " and  l_dist = '$_REQUEST[ddl_bench]'";
        $ddl_bench_t = " AND a.l_dist = b.l_dist";
    }
    if ($_REQUEST['ddl_st_agncy'] != '') {
        $ddl_st_agncy = " and  l_state = '$_REQUEST[ddl_st_agncy]'";
        $ddl_st_agncy_t = " AND a.l_state = b.l_state";
    }
    if ($_REQUEST['ddl_ref_case_type'] != '') {
        $ddl_ref_case_type = " and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
        $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
    }
    if ($_REQUEST['txt_ref_caseno'] != '') {
        $txt_ref_caseno = " and   trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
        $txt_ref_caseno_t = " and  trim(leading '0' from a.lct_caseno)= trim(leading '0' from b.lct_caseno)";
    }
    if ($_REQUEST['ddl_ref_caseyr'] != '') {
        $ddl_ref_caseyr = " and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
        $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
    }
}


$sql = "
SELECT 
    name,
    CASE 
        WHEN b.ct_code = 3 THEN (
            SELECT name 
            FROM master.state s 
            WHERE s.id_no = b.l_dist 
              AND display = 'Y'
        )
        ELSE (
            SELECT agency_name 
            FROM master.ref_agency_code c 
            WHERE c.cmis_state_id = b.l_state 
              AND c.id = b.l_dist 
              AND is_deleted = 'f'
        )
    END AS agency_name,
    
    CASE 
        WHEN b.ct_code = 4 THEN (
            SELECT skey 
            FROM master.casetype ct 
            WHERE ct.display = 'Y' 
              AND ct.casecode = b.lct_casetype
        )
        ELSE (
            SELECT type_sname 
            FROM master.lc_hc_casetype d 
            WHERE d.lccasecode = b.lct_casetype 
              AND d.display = 'Y'
        )
    END AS type_sname,
    
    short_description, 
    court_name, 
    d.pet_name, 
    d.res_name, 
    link_dt,
    cdm.diary_no,
    b.lct_dec_dt, 
    b.l_dist, 
    b.l_state, 
    b.lct_casetype, 
    b.lct_caseno, 
    b.lct_caseyear, 
    b.caveat_no, 
    b.ct_code,
    d.diary_no_rec_date::DATE AS diary_no_rec_date
    
FROM caveat_lowerct b
LEFT JOIN master.state c ON b.l_state = c.id_no AND c.display = 'Y'
LEFT JOIN caveat d ON d.caveat_no = b.caveat_no
LEFT JOIN master.casetype e ON e.casecode = CAST(SUBSTRING(d.fil_no FROM 1 FOR 2) AS BIGINT) AND e.display = 'Y'
LEFT JOIN master.m_from_court f ON f.id = b.ct_code AND f.display = 'Y'
LEFT JOIN caveat_diary_matching cdm ON cdm.caveat_no = b.caveat_no AND cdm.display = 'Y'
WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
AND b.lct_dec_dt IS NOT NULL AND b.lw_display = 'Y' 
ORDER BY caveat_no 
LIMIT $inc_val OFFSET $fst;
";



$db = \Config\Database::connect();
$query = $db->query($sql);

$result = $query->getResultArray();
$cur_date = date('Y-m-d');
if (!empty($result)) {

    if ($_REQUEST['u_t'] == 0)
        $s_no = 1;
    else if ($_REQUEST['u_t'] == 1)
        $s_no = $_REQUEST['inc_tot_pg'];
?>
    <div class="table-responsive">
        <table id="customers" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>
                        S.No.
                    </th>
                    <th>
                        Caveat No. /<br />Receiving Date
                    </th>

                    <th>
                        Petitioner<br />Vs<br />Respondent
                    </th>
                    <th>
                        Advocate
                    </th>
                    <th>
                        From Court
                    </th>
                    <th>
                        State
                    </th>
                    <th>
                        Bench
                    </th>
                    <th>
                        Case No.
                    </th>
                    <th>
                        Judgement Date
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>

                <?php

                foreach ($result as $row) {
                ?>
                    <tr>
                        <td>
                            <?php echo  $s_no; ?>
                        </td>
                        <td>
                            <?php echo substr($row['caveat_no'], 0, -4) . '-' .  substr($row['caveat_no'], -4); ?>
                            <span style="color: red"><?php echo $caveat_date = date('d-m-Y', strtotime($row['diary_no_rec_date'])); ?></span>
                        </td>
                        <!--        <td>
           <?php
                    //           $active_fil_no='';
                    //           $active_fil_dt='';
                    //           if($row['active_fil_no']!='')
                    //              $active_fil_no= '-'.intval(substr($row['active_fil_no'],3));
                    //           if($row[active_fil_dt]!='0000-00-00 00:00:00')
                    //              $active_fil_dt= '/'.date('Y',strtotime($row[active_fil_dt]));
                    //           echo $row['type_sname'].$active_fil_no.$active_fil_dt;
            ?>
        </td>-->
                        <td>
                            <?php

                            echo $row['pet_name'] . '<br/>Vs<br/>' . $row['res_name'];
                            ?>
                        </td>
                        <td>
                            <?php
                            $caveat_adv = "Select aor_code,name from caveat_advocate a join  master.bar b on a.advocate_id=b.bar_id 
                                            where a.caveat_no='$row[caveat_no]' and  a.display='Y'";
                            $caveat_adv =  $db->query($caveat_adv);
                            $result = $caveat_adv->getResultArray();
                            if (!empty($result)) {
                                $tot_advocate = '';
                                foreach ($result as $row1) {
                                    if ($tot_advocate == '')
                                        $tot_advocate = $row1['aor_code'] . '- ' . $row1['name'];
                                    else
                                        $tot_advocate = $tot_advocate . ', ' . $row1['aor_code'] . '- ' . $row1['name'];
                                }
                                echo $tot_advocate;
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $row['court_name']; ?>
                        </td>
                        <td>
                            <?php
                            echo $row['name'];
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row['agency_name'];
                            ?>
                        </td>
                        <td>
                            <?php
                            echo  $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                            ?>
                        </td>
                        <td>
                            <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                        </td>
                        <td>
                            <?php
                            $date1 = date_create($caveat_date);
                            $date2 = date_create($cur_date);
                            $diff = date_diff($date1, $date2);
                            $date_diff = $diff->format("%R%a days");
                            $rep_date_diff = intval(str_replace('+', '', $date_diff));
                            if ($rep_date_diff <= 90) {
                            ?>
                                <span style="color: green">Active</span>
                            <?php
                            } else {
                            ?>
                                <span style="color: red">Expired</span>
                            <?php
                            }
                            ?>

                        </td>
                        <!--        <td>
          
             <?php echo isset($row['diary_no'])   ? substr($row['diary_no'], 0, -4) . '-' . substr($row['diary_no'], -4) : '';  ?>
           
        </td>
        <td>
       <?php echo $row['link_dt']; ?>
        </td>-->
                    </tr>
                <?php
                    $s_no++;
                }
                ?>

            </tbody>
        </table>
    </div>
    <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />

<?php
} else {
?>
    <div class="cl_center"><b>No Record Found.</b></div>
<?php
}?>

 
