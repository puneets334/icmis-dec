<?=view('header'); ?>
<section class="content " >
    <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-body" >
<?php
// if (!empty($_REQUEST['ct'])) {
//     $db = \Config\Database::connect();
//     $builder = $db->table('main');

//     // First query
//     $builder->select("SUBSTR(diary_no, 1, LENGTH(diary_no) - 4) AS dn, SUBSTR(diary_no, -4) AS dy")
//         ->where("SUBSTRING_INDEX(fil_no, '-', 1)", $_REQUEST['ct'])
//         ->where("CAST($_REQUEST[cn] AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) AND SUBSTRING_INDEX(fil_no, '-', -1)");

//     $builder->groupStart()
//         ->where("reg_year_mh", 0)
//         ->orWhere("DATE(fil_dt) >", '2017-05-10')
//         ->groupEnd()
//         ->where("YEAR(fil_dt)", $_REQUEST['cy'])
//         ->orWhere("reg_year_mh", $_REQUEST['cy']);

//     // Execute first query
//     $query = $builder->get();
    
//     if ($query->getNumRows() > 0) {
//         $get_dno = $query->getRowArray();
//         $_REQUEST['d_no'] = $get_dno['dn'];
//         $_REQUEST['d_yr'] = $get_dno['dy'];
//     } else {
//         // Second query
//         $builder = $db->table('main_casetype_history');
        
//         $builder->select("SUBSTR(h.diary_no, 1, LENGTH(h.diary_no) - 4) AS dn, 
//             SUBSTR(h.diary_no, -4) AS dy, 
//             IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', 1), '') AS ct1, 
//             IF(h.new_registration_number != '', SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1), '') AS crf1, 
//             IF(h.new_registration_number != '', SUBSTRING_INDEX(h.new_registration_number, '-', -1), '') AS crl1")
//             ->where("h.is_deleted", 'f')
//             ->groupStart()
//                 ->where("SUBSTRING_INDEX(h.new_registration_number, '-', 1)", $_REQUEST['ct'])
//                 ->where("CAST($_REQUEST[cn] AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.new_registration_number, '-', -1)")
//                 ->where("h.new_registration_year", $_REQUEST['cy'])
//             ->groupEnd()
//             ->orGroupStart()
//                 ->where("SUBSTRING_INDEX(h.old_registration_number, '-', 1)", $_REQUEST['ct'])
//                 ->where("CAST($_REQUEST[cn] AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(h.old_registration_number, '-', 2), '-', -1) AND SUBSTRING_INDEX(h.old_registration_number, '-', -1)")
//                 ->where("h.old_registration_year", $_REQUEST['cy'])
//             ->groupEnd();

//         // Execute second query
//         $query = $builder->get();
        
//         if ($query->getNumRows() > 0) {
//             $get_dno = $query->getRowArray();
//             $_REQUEST['d_no'] = $get_dno['dn'];
//             $_REQUEST['d_yr'] = $get_dno['dy'];

//             // Fetch case type description
//             $caseTypeBuilder = $db->table('casetype');
//             $caseTypeBuilder->select('short_description')
//                 ->where('casecode', $_REQUEST['ct'])
//                 ->where('display', 'Y');
                
//             $caseTypeQuery = $caseTypeBuilder->get();
//             $res_ct_typ = $caseTypeQuery->getRow()->short_description;

//             $t_slpcc = $res_ct_typ . " " . $get_dno['crf1'] . " - " . $get_dno['crl1'] . " / " . $_REQUEST['cy'];
//         } else {
//             echo $msg_404 = 404;
//             exit();
//         }
//     }
// }

// pr($_REQUEST);



// pr($_REQUEST);

$dno = $_REQUEST["d_no"];
$dyr = $_REQUEST["d_yr"];
$diary_no = $dno . $dyr;

$db = \Config\Database::connect();

// Prepare the query using Query Builder
$builder = $db->table('main');
$builder->select('section_id, dacode, diary_no_rec_date, fil_dt, pet_name, res_name, pno, rno, fil_no, casetype_id, dacode');
$builder->where('diary_no', $diary_no);
$get_da_sec = $builder->get();

// Check if any rows are returned
if ($get_da_sec->getNumRows() == 0) {
    echo $msg_404 = 404;
    exit();
}

// Fetch the result
$r_get_da_sec = $get_da_sec->getRowArray();

if ($r_get_da_sec['dacode'] != (isset($_SESSION['dcmis_user_idd']) && $_SESSION['dcmis_user_idd']) && (isset($_SESSION['dcmis_user_idd']) && $_SESSION['dcmis_user_idd']) != 1341 && (isset($_SESSION['dcmis_user_idd']) && $_SESSION['dcmis_user_idd']) != 1) {
    echo '<p align="center"><font color="red">Only Concerned Dealing Assistant can generate Amended Cause Title!!!</font></p>';
    exit();
}
?>

<!--start view pdf already exist cause title-->
<?php
$created_by = isset($_SESSION['dcmis_user_idd']) ? $_SESSION['dcmis_user_idd'] : '';

// Load the database connection
$db = \Config\Database::connect();

// Prepare the query using Query Builder
$builder = $db->table('cause_title');
$builder->select('*');
$builder->where('diary_no', $diary_no);
$builder->where('is_active', 1);
$resC = $builder->get();

// Check if any rows are returned
$resultC = $resC->getRow();

if (!empty($resultC->cause_title_id) && !empty($resultC->diary_no) && !empty($resultC->path)) {
    $pdfPath = base_url("supreme_court/jud_ord_html_pdf/" . $resultC->path);
    ?>
    <textarea name="viewpdf_load" id="viewpdf_load" style="display: none;">
        <a href="<?php echo $pdfPath; ?>" target="_blank">View PDF</a>
    </textarea>
<?php 
} else { 
?>
    <textarea name="viewpdf_load" id="viewpdf_load" style="display: none;"></textarea>
<?php 
} 
?>
<!--end view pdf already exist cause title-->


<div id='prnnt' contenteditable="tue">
    <div align="right">

        <b style="font-size: 20px"><?php
// Load the database connection
$db = \Config\Database::connect();

if ($r_get_da_sec['dacode'] != 0) {
    // Query for users and their sections
    $builder = $db->table('master.users a');
    $builder->select('b.section_name');
    $builder->join('master.usersection b', 'a.section = b.id');
    $builder->where('usercode', $r_get_da_sec['dacode']);
    $builder->where('b.display', 'Y');
    $get_sec = $builder->get();
    
    // Fetch the result
    $r_get_sec = $get_sec->getRow();
} else {
    // Query for section name directly from usersection
    $builder = $db->table('master.usersection');
    $builder->select('section_name');
    $builder->where('id', $r_get_da_sec['section_id']);
    $builder->where('display', 'Y');
    $get_sec = $builder->get();
    
    // Fetch the result
    $r_get_sec = $get_sec->getRow();
}

// Display the section name if it exists
if ($r_get_sec) {
    echo "Section: " . $r_get_sec->section_name;
} else {
    echo "Section not found.";
}
?></b>
    </div>
    <div align='center'>

        <div style="text-align: center;font-size:20px;margin-top: 30px">
            IN THE SUPREME COURT OF INDIA
        </div>
        <div style="text-align: center;margin-bottom: 15px;font-size: 20px">
        <?php

$fil_no_yr = '';
if (!empty($r_get_da_sec['fil_no'])) {
    $c_code = substr($r_get_da_sec['fil_no'], 0, 2);
    $fil_no_yr = ' ' . substr($r_get_da_sec['fil_no'], 3) . '/' . substr($r_get_da_sec['fil_dt'], 0, 4);
} else {
    $c_code = $r_get_da_sec['casetype_id'];
}

// Load the database connection
$db = \Config\Database::connect();

// Prepare the query for case type
$builder = $db->table('master.casetype');
$builder->select('casename, nature');
$builder->where('casecode', $c_code);
$builder->where('display', 'Y');
$c_type = $builder->get();

// Check if any result is returned
if ($c_type->getNumRows() > 0) {
    $r_c_type = $c_type->getRow()->casename;
    $r_nature = $c_type->getRow()->nature;
} else {
    $r_c_type = '';
    $r_nature = '';
}

$c_r = '';
$ia_crmp = '';
if ($r_nature === 'C') {
    $c_r = "Civil";
    $ia_crmp = "I.A.No.";
}
if ($r_nature === 'R') {
    $c_r = "Criminal";
    $ia_crmp = "Cr.M.P.No.";
}
?>
<b>(<?php echo $c_r; ?> Appellate Jurisdiction)</b>

        </div>


        <b><u>
                <div style="font-size: 20px;margin-top: 20px;margin-top: 50px">
                <?php
// Load the database connection
$db = \Config\Database::connect();

$builder = $db->table('main m');
$builder->select('c.casename, c.short_description, 
    COALESCE(m.active_fil_no, m.fil_no) AS active_fil_no, 
    m.active_reg_year');
$builder->join('master.casetype c', 'COALESCE(m.active_casetype_id, m.casetype_id) = c.casecode', 'left');
$builder->where('m.diary_no', $diary_no);
$c_type = $builder->get();

$r_c_type = $c_type->getRowArray();
// print_r($r_c_type);
if (empty($r_c_type['active_fil_no'])) {
    $active_fil_no = ' D.no.' . $_REQUEST['d_no'] . '/' . $_REQUEST['d_yr'];
} else {
    $a = explode('-', substr($r_c_type['active_fil_no'], 3));
    // pr($a);
    $reg_no = ((isset($a[1])) && ($a[0] == $a[1])) ? $a[0] : substr($r_c_type['active_fil_no'], 3);
    $active_fil_no = ' NO. ' . $reg_no . '/' . $r_c_type['active_reg_year'];
}

echo $r_c_type['casename'] . $active_fil_no;
?>

                </div>
            </u> </b>

        <p style="text-indent: 40px;padding: 0px 2px 0px 2px;margin: 10px 0px 0px 0px" align="justify">
            <font style="font-size: 13pt" face="Times New Roman ">

                <?php

                $filing_date = date('dS F, Y', strtotime(get_diary_rec_date($diary_no)));
                $lower_court = lower_court_conct($diary_no);
                if (count($lower_court) > 0){
                ?> (Arising out of Judgment and final order dated <b style="font-size: 13pt"
                                                                     face="Times New Roman"><?php
                    }
                    for ($index1 = 0;
                    $index1 < count($lower_court);
                    $index1++) {
                    $judgement_dt = $new_date = date('dS F, Y', strtotime($lower_court[$index1][0]));
                    $agency_name = $lower_court[$index1][2];
                    $skey = $lower_court[$index1][3];
                    $lct_caseno = $lower_court[$index1][4];
                    $lct_caseyear = $lower_court[$index1][5];
                    //  $get_order_date= get_order_date($dairy_no);
                    ?>
                    <?php
                    if ($index1 > 0) {
                        echo "<b style='font-size: 13pt'  face= 'Times New Roman'> and dated </b> ";
                    }
                    echo $judgement_dt; ?></b>
            of the <b style="font-size: 13pt"
                      face="Times New Roman"><?php echo $agency_name; ?><?php echo $lower_court[$index1][1] ?> </b> in
            <?php
            $ex_skey = explode(',', $skey);
            $ex_lct_caseno = explode(',', $lct_caseno);
            $ex_lct_caseyear = explode(',', $lct_caseyear);
            for ($index2 = 0; $index2 < count($ex_lct_caseno); $index2++) {
                if ($index2 > 0) {
                    echo ',';
                }
                ?>

                <b style="font-size: 13pt" face="Times New Roman"> <?php echo $ex_skey[$index2] ?> </b> No. <b
                        style="font-size: 13pt" face="Times New Roman"><?php echo $ex_lct_caseno[$index2]; ?></b> of <b
                        style="font-size: 13pt" face="Times New Roman"><?php echo $ex_lct_caseyear[$index2]; ?>   </b>

            <?php }
            }

            if (count($lower_court) > 0) {
                ?> )<?php
            }

            ?>


            </font>
        </p>


        <div align="center" style="width: 100%;clear: both;margin-top: 70px">
            <table cellpadding="10" cellspacing="10" style="width: 100%">

                <?php // Load the database
    $db = \Config\Database::connect();

    $builder = $db->table('party');
    // $builder->select('sr_no_show, partyname, prfhname, addr1, addr2, state, city, dstname, pet_res, remark_del, remark_lrs, pflag, partysuff, deptname, ind_dep')
    //         ->join('master.deptt b', 'state_in_name = b.deptcode', 'left')
    //         ->where('diary_no', $diary_no)
    //         ->whereNotIn('pflag', ['T', 'Z'])
    //         ->where('pet_res', 'P')
    //         ->orderBy('pet_res')
    //         ->orderBy('CAST(SUBSTRING(sr_no_show, 1, POSITION(\'.\' IN sr_no_show) - 1) AS INTEGER)') // Adjusted for PostgreSQL
    //         ->orderBy('CAST(SUBSTRING(sr_no_show FROM POSITION(\'.\' IN sr_no_show) + 1 FOR POSITION(\'.\' IN sr_no_show || \'.0\') - POSITION(\'.\' IN sr_no_show) - 1) AS INTEGER)') // Adjusted for PostgreSQL
    //         ->orderBy('CAST(SUBSTRING(sr_no_show FROM POSITION(\'.\' IN sr_no_show || \'.0.0\') + 1 FOR POSITION(\'.\' IN sr_no_show || \'.0.0.0\') - POSITION(\'.\' IN sr_no_show || \'.0.0\') - 1) AS INTEGER)') // Adjusted for PostgreSQL
    //         ->orderBy('CAST(SUBSTRING(sr_no_show FROM POSITION(\'.\' IN sr_no_show || \'.0.0.0\') + 1 FOR POSITION(\'.\' IN sr_no_show || \'.0.0.0.0\') - POSITION(\'.\' IN sr_no_show || \'.0.0.0\') - 1) AS INTEGER)'); // Adjusted for PostgreSQL

    $builder->select([
        'sr_no_show', 'partyname', 'prfhname', 'addr1', 'addr2', 'state', 
        'city', 'dstname', 'pet_res', 'remark_del', 'remark_lrs', 'pflag', 
        'partysuff', 'deptname', 'ind_dep'
    ]);
    $builder->join('master.deptt b', 'state_in_name = b.deptcode', 'left');
    $builder->where('diary_no', $diary_no);
    $builder->whereNotIn('pflag', ['T', 'Z']);
    $builder->where('pet_res', 'P');
    
    // Handling ORDER BY with PostgreSQL substring and position functions
    $orderByExpression = "CAST(
        SUBSTRING(sr_no_show FROM 1 FOR COALESCE(NULLIF(POSITION('.' IN sr_no_show) - 1, -1), LENGTH(sr_no_show))) 
        AS INTEGER
    )";
    
    $builder->orderBy('pet_res');
    $builder->orderBy($orderByExpression);

    // pr($builder->getCompiledSelect());
    $query = $builder->get();
    $results = $query->getResultArray();

// Process results
foreach ($results as $row) {
    $tmp_name = '';
    $tmp_addr = '';
    $dstName = '';
    $dis_remark = '';

    // Output generation
    echo '<tr>';
    echo '<td style="font-size: 13pt" face="Times New Roman" WIDTH="4%" align="left" valign="top">';
    echo $row['sr_no_show'];
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        echo '*';
        $dis_remark .= $row['remark_del'] . '<br>';
    }
    echo '</td>';

    echo '<td style="font-size: 13pt" face="Times New Roman" align="left" WIDTH="45%">';
    $tmp_name = trim($row['partyname']);
    if ($row['prfhname'] != "") {
        $tmp_name .= " S/D/W/Thru:- " . $row['prfhname'];
    }

    if ($row['addr1'] != "") {
        $tmp_addr .= $row['addr1'] . ', ';
    }
    if ($row['ind_dep'] != 'I' && trim(str_replace($row['deptname'] ?? '', '', $row['partysuff'])) != '') {
        $tmp_addr .= " " . trim(str_replace($row['deptname'] ?? '', '', $row['partysuff'])) . ', ';
    }
    if ($row['addr2'] != "") {
        $tmp_addr .= $row['addr2'] . ' ';
    }

    if ($row['city'] != "") {
        if ($row['dstname'] != "") {
            $dstName .= " , DISTRICT: " . $row['dstname'];
        }
        $tmp_addr .= $dstName . " ," . get_state($row['city']) . " ";
    }
    if ($row['state'] != "") {
        $tmp_addr .= ", " . get_state($row['state']) . " ";
    }
    if ($tmp_addr != "") {
        $tmp_name .= "<br>" . $tmp_addr;
    }

    if ($row['remark_lrs'] != '') {
        $tmp_name .= "<br><b>[" . $row['remark_lrs'] . "]</b>";
    }
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        $tmp_name .= "<br><b>[" . $row['remark_del'] . "]</b>";
    }

    echo strtoupper($tmp_name);
    echo '</td>';

    echo '<td style="font-size: 13pt;text-align: right" WIDTH="50%">';
    if ($c_code == '3' || $c_code == '4') {
        echo "...APPELLANT NO. {$row['sr_no_show']}";
    } else {
        echo "...PETITIONER NO. {$row['sr_no_show']}";
    }
    echo '</td>';
    echo '</tr>';
}
?>
            </table>


            <table cellpadding="10" cellspacing="10" style="width: 100%">
                <tr>
                    <td rowspan="3" style="vertical-align: middle;font-size: 13pt;text-align: center">
                        VERSUS
                    </td>
                </tr>


            </table>


            <table cellpadding="10" cellspacing="10" style="width: 100%">

                <?php
                $db = \Config\Database::connect();

                $builder = $db->table('party');
                $builder->select([
                    'sr_no_show', 'partyname', 'prfhname', 'addr1', 'addr2', 'state', 
                    'city', 'dstname', 'pet_res', 'remark_del', 'remark_lrs', 'pflag', 
                    'partysuff', 'deptname', 'ind_dep'
                ]);
                $builder->join('master.deptt b', 'state_in_name = b.deptcode', 'left');
                $builder->where('diary_no', $diary_no);
                $builder->whereNotIn('pflag', ['T', 'Z']);
                $builder->where('pet_res', 'R');
                
                // Handling ORDER BY with PostgreSQL substring and position functions
                $orderByExpression = "CAST(
                    SUBSTRING(sr_no_show FROM 1 FOR COALESCE(NULLIF(POSITION('.' IN sr_no_show) - 1, -1), LENGTH(sr_no_show))) 
                    AS INTEGER
                )";
                
                $builder->orderBy('pet_res');
                $builder->orderBy($orderByExpression);

                $query = $builder->get();
                $results = $query->getResultArray();


// Process results
foreach ($results as $row) {
    $tmp_name = '';
    $tmp_addr = '';
    $dstName = '';

    echo '<tr>';
    echo '<td style="font-size: 13pt" face="Times New Roman" WIDTH="4%" align="left" valign="top">';
    echo $row['sr_no_show'];
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        echo '*';
        $dis_remark .= $row['remark_del'] . '<br>';
    }
    echo '</td>';

    echo '<td style="font-size: 13pt" face="Times New Roman" align="left" WIDTH="45%">';
    $tmp_name = $row['partyname'];
    if ($row['prfhname'] != "") {
        $tmp_name .= " S/D/W/Thru:- " . $row['prfhname'];
    }

    if ($row['addr1'] != "") {
        $tmp_addr .= $row['addr1'] . ', ';
    }
    if ($row['ind_dep'] != 'I' && trim(str_replace($row['deptname'] ?? '', '', $row['partysuff'])) != '') {
        $tmp_addr .= " " . trim(str_replace($row['deptname'] ?? '', '', $row['partysuff'])) . ', ';
    }
    if ($row['addr2'] != "") {
        $tmp_addr .= $row['addr2'] . ' ';
    }

    if ($row['city'] != "") {
        if ($row['dstname'] != "") {
            $dstName .= " , DISTRICT: " . $row['dstname'];
        }
        $tmp_addr .= $dstName . " ," . get_state($row['city']) . " ";
    }
    if ($row['state'] != "") {
        $tmp_addr .= ", " . get_state($row['state']) . " ";
    }
    if ($tmp_addr != "") {
        $tmp_name .= "<br>" . $tmp_addr;
    }

    if ($row['remark_lrs'] != '') {
        $tmp_name .= "<br><b>[" . $row['remark_lrs'] . "]</b>";
    }
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        $tmp_name .= "<br><b>[" . $row['remark_del'] . "]</b>";
    }
    echo strtoupper($tmp_name);
    echo '</td>';

    echo '<td style="font-size: 13pt;text-align: right" WIDTH="50%">';
    echo "...RESPONDENT NO. {$row['sr_no_show']}";
    echo '</td>';

    echo '</tr>';
}
?>
            </table>



            <table cellpadding="10" cellspacing="10" style="width: 100%">

                <?php
                // Load the database
$db = \Config\Database::connect();
$builder = $db->table('party p');
$builder->select('p.*, 
                  (REGEXP_MATCHES(sr_no_show, \'\\.(\\d+)\\.(\\d+)\\.(\\d+)\'))[1]::INTEGER AS num1, 
                  (REGEXP_MATCHES(sr_no_show, \'\\.(\\d+)\\.(\\d+)\\.(\\d+)\'))[2]::INTEGER AS num2, 
                  (REGEXP_MATCHES(sr_no_show, \'\\.(\\d+)\\.(\\d+)\\.(\\d+)\'))[3]::INTEGER AS num3')
         ->join('master.deptt b', 'state_in_name = b.deptcode', 'left')
         ->where('diary_no', $diary_no)
         ->whereNotIn('pflag', ['T', 'Z'])
         ->where('pet_res', 'I')
         ->orderBy('pet_res')
         ->orderBy('CAST(SUBSTRING(sr_no_show FROM \'^([^.]+)\') AS INTEGER)') // First part before the dot
         ->orderBy('num1') // First number after the dot
         ->orderBy('num2') // Second number after the second dot
         ->orderBy('num3'); // Third number after the third dot

// Execute the query
$query = $builder->get();
$results = $query->getResultArray();



// Process results
foreach ($results as $row) {
    $tmp_name = '';
    $tmp_addr = '';
    $dstName = '';

    echo '<tr>';
    echo '<td style="font-size: 13pt" face="Times New Roman" WIDTH="4%" align="left" valign="top">';
    echo $row['sr_no_show'];
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        echo '*';
        $dis_remark .= $row['remark_del'] . '<br>';
    }
    echo '</td>';

    echo '<td style="font-size: 13pt" face="Times New Roman" align="left" WIDTH="45%">';
    $tmp_name = $row['partyname'];
    if ($row['prfhname'] != "") {
        $tmp_name .= " S/D/W/Thru:- " . $row['prfhname'];
    }

    if ($row['addr1'] != "") {
        $tmp_addr .= $row['addr1'] . ', ';
    }
    if (trim(str_replace($row['deptname'], '', $row['partysuff'])) != '') {
        $tmp_addr .= " " . trim(str_replace($row['deptname'], '', $row['partysuff'])) . ', ';
    }
    if ($row['addr2'] != "") {
        $tmp_addr .= $row['addr2'] . ' ';
    }

    if ($row['city'] != "") {
        if ($row['dstname'] != "") {
            $dstName .= " , DISTRICT: " . $row['dstname'];
        }
        $tmp_addr .= $dstName . " ," . get_state($row['city']) . " ";
    }
    if ($row['state'] != "") {
        $tmp_addr .= ", " . get_state($row['state']) . " ";
    }
    if ($tmp_addr != "") {
        $tmp_name .= "<br>" . $tmp_addr;
    }

    if ($row['remark_lrs'] != '') {
        $tmp_name .= "<br><b>[" . $row['remark_lrs'] . "]</b>";
    }
    if ($row['pflag'] == 'O' || $row['pflag'] == 'D') {
        $tmp_name .= "<br><b>[" . $row['remark_del'] . "]</b>";
    }
    echo strtoupper($tmp_name);
    echo '</td>';

    echo '<td style="font-size: 13pt;text-align: right" WIDTH="50%">';
    echo "...IMPLEADER NO. {$row['sr_no_show']}";
    echo '</td>';

    echo '</tr>';
}

                ?>
            </table>


        </div>

    </div>

</div>
        </div>
        </div>
        </div>
        </div>
    </div>
</section>

