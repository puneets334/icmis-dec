<?php
$ifMain = 1;
$ifPending = 1;
$details = $model->getCaseDetailsSingleJudge($dno);

?>
<br>
<div id='report_result'>

    <input type="hidden" id="fil_hd" value="<?= $dno ?>" />
    <input type="hidden" id="side_hd" value="<?= isset($details['side']) ? $details['side'] : '' ?>" />
    <table align="center" width="100%">
        <tr align="center" style="color:blue">
            <th>
                <?php
                echo "Case No.-";
                $casetype = $model->getCaseTypeSingleJudge($dno);

                if ($casetype['fil_no'] != '' || $casetype['fil_no'] != NULL) {
                    echo '[M]' . $casetype['short_description'] . SUBSTR($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
                }

                if ($casetype['fil_no_fh'] != '' || $casetype['fil_no_fh'] != NULL) {
                    $r_case = $model->getFilNoSingleJudge($casetype['fil_no_fh']);


                    echo ',[R]' . $r_case['short_description'] . SUBSTR($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                }
                echo ", Diary No: " . substr($dno, 0, -4) . '/' . substr($dno, -4);
                navigate_diary($dno);
                ?>
            </th>
        </tr>
    </table>
    

    <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
        <?php
        if (isset($details['c_status']) && $details['c_status'] == 'D') {

            $ifPending = 0;
        ?>
            <tr>
                <th colspan="4" style="color:red">The Case is Disposed!!!</th>
            </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="4" class="center blue-text"><?= isset($details['pet_name']) ? $details['pet_name'] : '' ?>
                <span style="color:black"> - Vs - </span>
                <?= isset($details['res_name']) ? $details['res_name'] : '' ?>
            </th>
        </tr>
        <?php
        
        $query_cate = $model->getCategorySingleJudge($dno);
       
        $category = '';
        if(!empty($query_cate)) // This condition add 
        {
        foreach ($query_cate as $row_cate) {
            $category .= $row_cate['sub_name1'] . '-' . $row_cate['sub_name2'] . '-' . $row_cate['sub_name3'] . '-' . $row_cate['sub_name4'] . '<br>';
        }
        ?>
        <tr align='center'>
            <th colspan="4" align="center"><i>Category:</i> <span style="font-size:14px;color:brown"><?php echo $category; ?></span>
            </th>
        </tr>
    <?php } ?>


        <tr>
            <th colspan="4" style="text-align: center;font-size: 14px;">
                <?php
                $main_case = $model->getConnKeyDnoSingleJudge($dno);

                if (!empty($main_case)) {

                    if ($main_case['conn_key'] == $dno)
                        echo "This is Main Diary No";
                    else {
                        $ifMain = 0;
                        echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4) . "</span>";
                    }
                }
                $sq_n = $model->getHearingDetailsSingleJudge($dno);



                if (!empty($sq_n)) {
                    echo "<div align=center style='border: 1px solid black ;'>";
                    echo "<table class='table_tr_th_w_clr c_vertical_align'>";
                    echo "<tr align='center'><th colspan=5 >Already Entries of List before and not before and coram </th></tr>";
                    echo "<tr><th>Sr.</th><th>Before/Not before</th><th>Hon. Judge</th><th>Reason</th><th>Entry Date</th></tr>";
                    $s = 1;
                    // $alrady_j1 ='';
                    foreach ($sq_n as $rw_q) {
                        if ($rw_q['notbef'] == 'N') $notbef = 'Not before';
                        if ($rw_q['notbef'] == 'B') $notbef = 'Before/SPECIAL BENCH';
                        if ($rw_q['notbef'] == 'C') $notbef = "Before Coram";
                        echo "<tr><td>" . $s . "</td><td>" . $notbef . "</td><td>" . $rw_q['jname'] . "</td><td>" . $rw_q['res_add'] . "</td><td>" . $rw_q['ent_dt'] . "</td></tr>";
                        $s++;
                        //$alrady_j1=$alrady_j1.",".$rw_q['j1'];
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
            <td><?= !empty($details['diary_no_rec_date']) ? date('d-M-Y h:i A', strtotime($details['diary_no_rec_date'])) : '--' ?></td>
            <td>Registration Date:</td>
            <td><?= !empty($details['fil_dt']) ? date('d-M-Y h:i A', strtotime($details['fil_dt'])) : '--' ?></td>
        </tr>
        <tr>
            <td>Tentative Cause-List Date:</td>
            <td><?= isset($details['tentative_cl_dt']) ? $details['tentative_cl_dt'] : '--' ?></td>
            <td>Last Order:</td>
            <td><?= !empty($details['lastorder']) ? $details['lastorder'] : '--' ?></td>
        </tr>
        <tr>
            <td>Next Date:</td>
            <td><?= isset($details['next_dt']) ? $details['next_dt'] : '--' ?></td>
        </tr>


        <!-- New code  -->
        <td>Weekly Single Judge Advance List Date</td>
        <td>
            <?php
            $ifAdvanceAllocated = 0;
            $advance_dates = $model->getAdvanceDatesSingleJudge();
            if (!empty($advance_dates)) {
                $ifAdvanceAllocated = 1;

                echo "<select id='advance_list_date' name='advance_list_date'>";

                foreach ($advance_dates as $row) {
            ?>
                    <option value="<?= date("Y-m-d", strtotime($row['from_dt'])) . "_" . date("Y-m-d", strtotime($row['to_dt'])) ?>"><?= date("d-m-Y", strtotime($row['from_dt'])) . " to " . date("d-m-Y", strtotime($row['to_dt'])); ?></option>
            <?php
                }
                echo "</select>";
            } else {
                echo "<span style='color: red'>Un-published single judge advance list does not exist!</span>";
            }
            ?>
        </td>
        <td>Ready/Not Ready</td>
        <td>
            <?php 
             $ifReady=0;
             if (isset($details['main_supp_flag']) && $details['main_supp_flag'] == 0): 
                $ifReady=1;
             
             ?>
                <span class="green-text">Ready</span>
            <?php else: ?>
                <span class="red-text">Not Ready</span>
            <?php endif; ?>
        </td>
        </tr>

    </table>

    <?php
    if (!isset($details['advance_list_date']) || is_null($details['adva:nce_list_date']))
    {
       //remove vkg 
        if($ifAdvanceAllocated==1 && $ifPending==1 && $ifReady==1 && $ifMain==1) {
            ?>
            <div>
                <div>
                    <table align="center" id="tb_clr_n" border="1" style="border-collapse: collapse">
                        <tr>
                            <th colspan="5"><input type="button" value="Add in Advance List" name="savebutton"/>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
    }
    else{
        
        ?>
        <div>
            <div>
                <span style="color: red; text-align: center">Already Allocated in advance List dated <?=!empty($details['from_dt']) ? $details['from_dt']: '--' ?> and <?=!empty($details['to_dt']) ? $details['to_dt']: '--'?>.</span>
            </div>
        </div>
        <?php
    }
    ?>

    <br>

    <div style="color: blue; text-align: center" id="show_fil"></div>


</div>