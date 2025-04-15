<?php

$main_heading = "Detail Report of Pending Registered matters As On $ason_dmy";
if ($flag == 'Number_of_Admission_hearing_matters') {
    $subquert1 = "  mf_active <> 'F' ";
    $headnote1 = " (Number of Admission hearing matters) ";
}
if ($flag == 'complete_court') {
    $subquert1 = "  mf_active <> 'F' and main_supp_flag in (0,1,2) and (board_type = 'J' OR board_type = 'S')";
    $headnote1 = " (Number of Admission hearing matters - Complete for Court) ";
}
if ($flag == 'misc_incomplete') {
    $subquert1 = " mf_active <> 'F'
                AND (
                    board_type IN ('R', 'C') 
                    OR main_supp_flag NOT IN (0, 1, 2)
                    OR board_type NOT IN ('J', 'S', 'C', 'R')
                ) ";
    $headnote1 = " (Number of Admission hearing matters - InComplete) ";
}
if ($flag == 'incomplete_chamber') {
    $subquert1 = "  mf_active <> 'F' and main_supp_flag in (0,1,2) and board_type = 'C' ";
    $headnote1 = " (Number of Admission hearing matters - InComplete for Chamber) ";
}
if ($flag == 'incomplete_registrar') {
    $subquert1 = "  mf_active <> 'F' and main_supp_flag in (0,1,2) and board_type = 'R' ";
    $headnote1 = " (Number of Admission hearing matters - InComplete for Registrar) ";
}
if ($flag == 'incomplete_not_updated') {
    $subquert1 = "  mf_active <> 'F' and NOT (main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R')) ";
    $headnote1 = " (Number of Admission hearing Incomplete not updated) ";
}
if ($flag == 'final_pending') {
    $subquert1 = "mf_active = 'F' ";
    $headnote1 = " (Number of Regular hearing matters) ";
}
if ($flag == 'Regular_Ready') {
    $subquert1 = "mf_active = 'F' and main_supp_flag in (0,1,2) and board_type <> 'R' ";
    $headnote1 = " (Number of Regular hearing ready matters) ";
}
if ($flag == 'Regular_Not_Ready') {
    $subquert1 = " mf_active = 'F' and NOT (main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R')) ";
    $headnote1 = " (Number of Regular hearing not ready matters) ";
}
if ($flag == 'civil_pendency') {
    $subquert1 = "  (case_grp = 'C' or case_grp is null) ";
    $headnote1 = " (Number of Civil matters) ";
}
if ($flag == 'criminal_pendency') {
    $subquert1 = "  case_grp = 'R' ";
    $headnote1 = " (Number of Criminal matters) ";
}
if ($flag == 'more_than_one_year_old') {
    $subquert1 = " fil_dt < CURRENT_DATE - INTERVAL '1 year' ";
    $headnote1 = " (More than 1 year old matters) ";
}
if ($flag == 'less_than_one_year_old') {
    $subquert1 = "fil_dt >= CURRENT_DATE - INTERVAL '1 year' ";
    $headnote1 = " (Less than 1 year old matters) ";
}
if ($flag == 'total_pending') {
    $subquert1 = " ";
    $headnote1 = " (Total Pendency) ";
}
if ($flag == 'Total_Connected') {
    $subquert1 = "  (m.diary_no <> m.conn_key and m.conn_key > 0) ";
    $headnote1 = " (Total Connected Pending Matters) ";
}
if ($flag == 'Pendency_after_excluding_connected') {
    $subquert1 = "  (m.diary_no = m.conn_key or m.conn_key = 0 or m.conn_key = '' or m.conn_key is null) ";
    $headnote1 = " (Total Pendency after excluding connected matters ) ";
}
if ($flag == 'more_than_five_year_old') {
    $subquert1 = "  fil_dt < CURRENT_DATE - INTERVAL '5 year' ";
    $headnote1 = " (More than 5 years old matters) ";
}
if ($flag == 'more_than_ten_year_old') {
    $subquert1 = "  fil_dt < CURRENT_DATE - INTERVAL '10 year' ";
    $headnote1 = " (More than 10 years old matters) ";
}
if ($flag == 'more_than_fifteen_year_old') {
    $subquert1 = "  fil_dt < CURRENT_DATE - INTERVAL '15 year' ";
    $headnote1 = " (More than 15 years old matters) ";
}
if ($flag == 'more_than_twenty_year_old') {
    $subquert1 = "  fil_dt < CURRENT_DATE - INTERVAL '20 year' ";
    $headnote1 = " (More than 20 years old matters) ";
}
if ($flag == 'tot_constitution') {
    $subquert1 = " ";
    $headnote1 = " (Constitution matters (Subject Cat. 20,21,22,23)) ";
}
if ($flag == 'referred') {
    $subquert1 = " ";
    $headnote1 = " (Referred matters (Referred to Larger Bench)) ";
}
if ($flag == 'Incomplete_Not_Ready') {
    $subquert1 = "  (
        (mf_active <> 'F' AND NOT (main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R'))) OR 
        (mf_active = 'F' AND NOT (main_supp_flag in (0,1,2) and board_type IN ('J','S','C','R'))) OR 
        (mf_active <> 'F' and main_supp_flag in (0,1,2) and board_type IN ('R','C'))
    )";
    $headnote1 = " (Incomplete / Not Ready) ";
}
if ($flag == 'Total_20') {
    $subquert1 = "  s.subcode1 in (20) ";
    $headnote1 = " Total Five Judges Bench Matter ";
}
if ($flag == 'Total_21') {
    $subquert1 = "  s.subcode1 in (21) ";
    $headnote1 = " Total Seven Judges Bench Matter ";
}
if ($flag == 'Total_22') {
    $subquert1 = "  s.subcode1 in (22) ";
    $headnote1 = " Total Nine Judges Bench Matter ";
}
if ($flag == 'Total_23') {
    $subquert1 = "s.subcode1 in (23) ";
    $headnote1 = " Total Eleven Judges Bench Matter ";
}
if ($flag == 'Main_20') {
    $subquert1 = "  s.subcode1 IN (20) AND (m.conn_key IS NOT NULL 
AND m.conn_key <> '' 
AND m.conn_key::BIGINT <> 0 
AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Five Judges Bench Main Matter ";
}
if ($flag == 'Main_21') {
    $subquert1 = " s.subcode1 in (21) AND (m.conn_key IS NOT NULL 
AND m.conn_key <> '' 
AND m.conn_key::BIGINT <> 0 
AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Seven Judges Bench Main Matter ";
}
if ($flag == 'Main_22') {
    $subquert1 = " s.subcode1 in (22) AND (m.conn_key IS NOT NULL 
AND m.conn_key <> '' 
AND m.conn_key::BIGINT <> 0 
AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Nine Judges Bench Main Matter ";
}
if ($flag == 'Main_23') {
    $subquert1 = "  s.subcode1 in (23) AND (m.conn_key IS NOT NULL 
AND m.conn_key <> '' 
AND m.conn_key::BIGINT <> 0 
AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Eleven Judges Bench Main Matter ";
}
if ($flag == 'conn_20') {
    $subquert1 = "s.subcode1 IN (20) AND (
                m.conn_key IS NOT NULL 
                AND m.conn_key <> '' 
                AND m.conn_key::BIGINT <> 0 
                AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Five Judges Bench Connected Matter ";
}
if ($flag == 'conn_21') {
    //$subquert1 = "  s.subcode1 in (21) AND (m.conn_key <> m.diary_no and m.conn_key is not null and m.conn_key <> '' and m.conn_key <> 0)";
    $subquert1 = "s.subcode1 IN (21) AND (
        m.conn_key IS NOT NULL 
        AND m.conn_key <> '' 
        AND m.conn_key::BIGINT <> 0 
        AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Seven Judges Bench Connected Matter ";
}
if ($flag == 'conn_22') {
    //$subquert1 = "  s.subcode1 in (22) AND (m.conn_key <> m.diary_no and m.conn_key is not null and m.conn_key <> '' and m.conn_key <> 0)";
    $subquert1 = "s.subcode1 IN (22) AND (
        m.conn_key IS NOT NULL 
        AND m.conn_key <> '' 
        AND m.conn_key::BIGINT <> 0 
        AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Nine Judges Bench Connected Matter ";
}
if ($flag == 'conn_23') {
    //$subquert1 = "  s.subcode1 in (23) AND (m.conn_key <> m.diary_no and m.conn_key is not null and m.conn_key <> '' and m.conn_key <> 0)";
    $subquert1 = "s.subcode1 IN (23) AND (
        m.conn_key IS NOT NULL 
        AND m.conn_key <> '' 
        AND m.conn_key::BIGINT <> 0 
        AND m.conn_key::BIGINT <> m.diary_no)";
    $headnote1 = " Eleven Judges Bench Connected Matter ";
}

$first_date = date('01-m-Y', strtotime($for_date));
$last_date  = date('t-m-Y', strtotime($for_date));


$first_date_pg = date('Y-m-d', strtotime($first_date));
$last_date_pg  = date('Y-m-d', strtotime($last_date));

if ($flag == 'Notice_3') {
    $subquert1 = "r_head = 3 AND cl_date BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg' ";
    $headnote1 = " Notice Order Matters between $first_date_pg and $last_date_pg ";
}

if ($flag == 'Notice_181') {
    $subquert1 = " r_head = 181 AND cl_date BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg' ";
    $headnote1 = " Notice Returnable Order Matters between $first_date_pg and $last_date_pg ";
}

if ($flag == 'Notice_182') {
    $subquert1 = " r_head = 182 AND cl_date BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg' ";
    $headnote1 = " Notice Stay Order Matters between $first_date_pg and $last_date_pg ";
}

if ($flag == 'Notice_183') {
    $subquert1 = " r_head = 183 AND cl_date BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg' ";
    $headnote1 = " Notice Tag With Order Matters between $first_date_pg and $last_date_pg ";
}

if ($flag == 'Notice_184') {
    $subquert1 = " r_head = 184 AND cl_date BETWEEN DATE '$first_date_pg' AND DATE '$last_date_pg' ";
    $headnote1 = " Notice Status Quo Order Matters between $first_date_pg and $last_date_pg ";
}

if ($flag == 'In_Limine') {
    $subquert1 = " ";
    $headnote1 = " In Limine Cases ";
}
$result = $model->pendency_bifurcation_process_detail($flag,$for_date, $subquert1,$headnote1,$first_date_pg,$last_date_pg );

if(!empty($result))
    {

            $sno = 1;
            //$headnote1=1; // remove vkg
            ?>
        <style>div, table, tr, td{
                font-size:12px;
                vertical-align: top;
            }</style>
            <div id="prnnt" style="font-size:12px;">
            
            <center><h3><?php echo $main_heading."<br>".$headnote1; ?></h3> </center>
            <table border="1" width="100%" id="example" class="display" cellspacing="0" width="100%">
            <thead>
            <th>Sno.</th>
            <th>Diary No.</th>
            <th>Registration No.</th>
            <th>Cause Title</th>
            </thead>
            <tbody>
            <?php
        foreach ($result as $row)
        {
            ?>
            <tr>
                <td><?php echo $sno++; ?></td>
                <td> <?php echo substr_replace($row['diary_no'], '-', -4, 0); ?> </td>
                <td> <?php echo $row['case_no']; ?> </td>
                <td>
                    <?php
                    echo $row['cause_title'];
                    ?>
                </td>
            </tr>

            <?php
        }
        ?>
        <table>
        <?php
    }
    else
    {
        echo "No Records Found";
    }





    ?>
