<?php

$ifPending = 1;
$ifMain = 1;
if ($ct != '') {
    $get_dno = $model->getDiaryNo($ct, $cn, $cy);

    if (!empty($get_dno)) {
        $get_dno = $get_dno;
        $dno = $get_dno['dn'] . $get_dno['dy'];
    } else
        $dno = 0;
} else {
    $dno = $dn . $dyr;
}

$details = $model->getCaseDetails($dno);

?>
<input type="hidden" id="fil_hd" value="<?= $dno ?>" />
<input type="hidden" id="side_hd" value="<?= isset($details['side']) ? $details['side'] : '' ?>" />

<table align="center" width="100%">
    <tr align="center" style="color:blue">
        <th><?php
            echo "Case No.-";

            $casetype = $model->getCaseType($dno);
            if (is_array($casetype) && isset($casetype['fil_no']) && $casetype['fil_no'] != '') {
                echo '[M]' . $casetype['short_description'] . substr($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
            } else {

                echo "--";
            }


            if (isset($casetype['fil_no_fh']) != '' && $casetype['fil_no_fh'] != NULL) {
                $r_case = $model->getShortDesc($casetype['fil_no_fh']);
                if (is_array($r_case) && isset($r_case['short_description'])) {
                    echo ',[R]' . $r_case['short_description'] . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                } else {
                    echo '--';
                }
            }

            echo ", Diary No: " . substr($dno, 0, -4) . '/' . substr($dno, -4);

            navigate_diary($dno);
            ?></th>
    </tr>
</table>

<table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
    <?php

    if (isset($details['c_status']) && $details['c_status'] == 'D')
    {
        $ifPending = 0;
    ?>
        <tr>
            <th colspan="4" style="color:red; text-align:center;">The Case is Disposed!!!</th>
        </tr>

    <?php
    }
    ?>
    <tr>
        <th colspan="4" style="color:blue; text-align:center;">
            <?php
            if (isset($details['pet_name']) && isset($details['res_name']))
            {

                echo $details['pet_name'] . "<span style='color:black'> - Vs - </span>" . $details['res_name'];
            }
            else
            {

                echo "--";
            }
            ?>
        </th>
    </tr>
    <?php

    $query_cate = $model->getSubName($dno);
    $category = '';

    foreach ($query_cate as $row_cate) {
        $category .= $row_cate['sub_name1'] . '-' . $row_cate['sub_name2'] . '-' . $row_cate['sub_name3'] . '-' . $row_cate['sub_name4'] . '<br>';
    }
    ?>
    <tr>
        <th colspan="4" style=" text-align:center;"><i>Category:</i> <span style="font-size:14px;color:brown"><?php echo $category; ?></span></th>
    </tr>
    <tr>
        <th colspan="4" style="text-align:center;font-size: 14px;">
            <?php

            $main_case = $model->getConnKey($dno);


            if (!empty($main_case)) {
                $main_case = $main_case;
                if ($main_case['conn_key'] == $dno)
                    echo "This is Main Diary No";

                else {
                    $ifMain = 0;
                    echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4) . "</span>";
                }
            }

            $rs_n = $model->getJname($dno);
            //pr($rs_n);
            if (!empty($rs_n)) {
                echo "<div align=center style='border: 1px solid black ;'>";
                echo "<table class='table_tr_th_w_clr c_vertical_align'>";
                echo "<tr><th colspan='5' style='text-align: center;'>Already Entries of List before and not before and coram </th></tr>";
                echo "<tr><th>Sr.</th><th>Before/Not before</th><th>Hon. Judge</th><th>Reason</th><th>Entry Date</th></tr>";
                $s = 1;
                foreach ($rs_n as $rw_q) {
                    if ($rw_q['notbef'] == 'N') $notbef = 'Not before';
                    if ($rw_q['notbef'] == 'B') $notbef = 'Before/SPECIAL BENCH';
                    if ($rw_q['notbef'] == 'C') $notbef = "Before Coram";
                    echo "<tr><td>" . $s . "</td><td>" . $notbef . "</td><td>" . $rw_q['jname'] . "</td><td>" . $rw_q['res_add'] . "</td><td>" . $rw_q['ent_dt'] . "</td></tr>";
                    $s++;
                    //$alrady_j1 = $alrady_j1 . "," . $rw_q['j1'];
                }


                echo "</table>";
                echo "</div>";
                echo "<br>";
            } else {
                echo "<div style=text-align:center;padding:10px;>LIST BEFORE/NOT BEFORE/CORAM NOT FOUND</div>";
            }

            ?>
        </th>
    </tr>
    <tr>
        <td>Filing Date:</td>
        <td><?php
            if (isset($details['diary_no_rec_date']) && !empty($details['diary_no_rec_date'])) {
                echo date('d-M-Y', strtotime($details['diary_no_rec_date'])) . ' on ' . date('h:i A', strtotime($details['diary_no_rec_date']));
            } else {
                echo '--';
            }
            ?></td>
        <td>Registration Date:</td>
        <td><?php
            if (isset($details['fil_dt']) && !empty($details['fil_dt'])) {
                echo date('d-M-Y', strtotime($details['fil_dt'])) . ' on ' . date('h:i A', strtotime($details['fil_dt']));
            } else {
                echo '--';
            }
            ?></td>
    </tr>
    <tr>
        <td>Tentative Cause-List Date:</td>
        <td><?php echo isset($details['tentative_cl_dt']) && !empty($details['tentative_cl_dt']) ? revertDate($details['tentative_cl_dt']) : '--'; ?></td>
        <td>Last Order:</td>
        <td><?php
            if (isset($details['lastorder']) && !empty($details['lastorder'])) {
                echo $details['lastorder'];
            } else {
                echo '--';
            }
            ?></td>
    </tr>
    <tr>
        <td>Next Date:</td>
        <td><?php echo isset($details['next_dt']) && !empty($details['next_dt']) ? revertDate($details['next_dt']) : '--'; ?></td>
    </tr>



    <tr>
        <td>Advance List Date</td>
        <td>
            <?php
            $ifAdvanceAllocated = 0;
            if (isset($details['next_dt'])) {
                $advance_dates = $model->getAdvanceDate($details['next_dt']);
            }


            if (!empty($advance_dates)) {
                $ifAdvanceAllocated = 1;
                echo "<select id='advance_list_date' name='advance_list_date'>";
                foreach ($advance_dates as $row) {
                    if (isset($row['next_dt'])) {
                        echo "<option value='" . htmlspecialchars($row['next_dt']) . "'>" . htmlspecialchars($row['next_dt']) . "</option>";
                    } else {

                        echo "<option disabled>--</option>";
                    }
                }
                echo "</select>";
            } else {
                $next_dt_value = isset($details['next_dt']) ? $details['next_dt'] : 'Not available';
                echo "<span style='color: red'>No un-published advance list dated " . revertDate($next_dt_value) . " exist!</span>";
            }
            ?>

        </td>
        <td>Ready/Not Ready</td>
        <td>
            <?php
            $ifReady = 0;
            if (isset($details['main_supp_flag'])) {
                if ($details['main_supp_flag'] == 0) {
                    $ifReady = 1;
                    echo "<span style='color: green'>Ready</span>";
                } else {
                    echo "<span style='color: red'>Not Ready</span>";
                }
            } else {
                echo "<span style='color: red'>--</span>";
            }
            ?>
        </td>
    </tr>

</table>

<!-- Remove vkg div -->

<!-- <div>
    <div>
        <table align="center" id="tb_clr_n" border="1" style="border-collapse: collapse">
            <tr>
                <th colspan="5">
                    <input type="button" value="Add in Advance List" name="savebutton" />
                </th>
            </tr>
        </table>
    </div>
</div> -->
<?php
if (!isset($details['advance_list_date']) || is_null($details['advance_list_date'])) {
    // Debugging line (Remove if not needed)
    //pr('vinit garg'); 

    if ($ifAdvanceAllocated == 1 && $ifPending == 1 && $ifReady == 1 && $ifMain == 1) {
?>
        <div>
            <div>
                <table align="center" id="tb_clr_n" border="1" style="border-collapse: collapse">
                    <tr>
                        <th colspan="5">
                            <input type="button" value="Add in Advance List" name="savebutton" />
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    <?php
    }
} else {
    $advance_list_date = htmlspecialchars($details['advance_list_date'] ?? '--');
    ?>
    <div>
        <div style="color: red; text-align: center">
            <span>
                Already Allocated in Advance List dated <?= $advance_list_date ?>.
            </span>
        </div>
    </div>
<?php
}
?>




<br>
<?php
