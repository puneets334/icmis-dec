<div id='report_result'>

    <input type="hidden" id="fil_hd" value="<?= $dno ?>" />
    <input type="hidden" id="side_hd" value="<?= isset($details['side']) ? $details['side'] : '' ?>" />

    <table align="center" width="100%">
        <tr class="center blue-text">
            <th>
                <?php
                echo "Case No.-";
                if (!empty($casetype['fil_no'])) {
                    echo '[M]' . $casetype['short_description'] . substr($casetype['fil_no'], 3) . '/' . $casetype['m_year'];
                }

                if (!empty($casetype['fil_no_fh'])) {
                    $r_case = $casetype['short_description'];
                    echo ',[R]' . $r_case . substr($casetype['fil_no_fh'], 3) . '/' . $casetype['f_year'];
                }

                echo ", Diary No: " . substr($dno, 0, -4) . '/' . substr($dno, -4);
                ?>
            </th>
        </tr>
    </table>

    <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
        <?php if (isset($details['c_status']) && $details['c_status'] == 'D'): ?>
            <tr>
                <th colspan="4" class="center red-text">The Case is Disposed!!!</th>
            </tr>
        <?php endif; ?>
        <tr>
            <th colspan="4" class="center blue-text"><?= isset($details['pet_name']) ? $details['pet_name'] : '' ?>
                <span style="color:black"> - Vs - </span>
                <?= isset($details['res_name']) ? $details['res_name'] : '' ?>
            </th>
        </tr>
        <tr>
            <th colspan="4"><i>Category:</i> <span class="brown-text"><?= $category ?></span></th>
        </tr>
        <tr>
            <th colspan="4" class="center" style="font-size: 14px;">
                <?php if (isset($main_case['conn_key']) && $main_case['conn_key'] == $dno): ?>
                    This is Main Diary No
                <?php else: ?>
                    <?php $ifMain = 0; ?>
                    <span class="red-text"><?= isset($main_case) ? substr($main_case, 0, -4) . '/' . substr($main_case, -4) : '' ?></span>
                <?php endif; ?>
            </th>
        </tr>
    </table>


    <!-- <div align="center" style="border: 1px solid black;"> -->
    <table align="center" class="table-bordered table-striped">
        <tr>
            <th colspan="5">Already Entries of List before and not before and coram</th>
        </tr>
        <tr>
            <th>Sr.</th>
            <th>Before/Not before</th>
            <th>Hon. Judge</th>
            <th>Reason</th>
            <th>Entry Date</th>
        </tr>
        <?php
        $s = 1;
        if (!empty($hearingDetails)):
            foreach ($hearingDetails as $row):
                $notbef = '';
                if ($row['notbef'] === 'N') {
                    $notbef = 'Not before';
                } elseif ($row['notbef'] === 'B') {
                    $notbef = 'Before/SPECIAL BENCH';
                } elseif ($row['notbef'] === 'C') {
                    $notbef = 'Before Coram';
                }  <tr>
                <td style="">Filing Date:</td><td style=""><?php if($details['diary_no_rec_date']!='') echo date('d-M-Y',strtotime($details['diary_no_rec_date'])).' on '.date('h:i A',strtotime($details['diary_no_rec_date'])); else echo '--';?>
                </td>
                <td style="">Registration Date:</td><td style=""><?php if($details['fil_dt']!='') echo date('d-M-Y',strtotime($details['fil_dt'])).' on '.date('h:i A',strtotime($details['fil_dt'])); else echo '--';?></td></tr>
            <tr><td>Tentative Cause-List Date:</td><td><?php echo revertDate($details['tentative_cl_dt']);?></td>
                <td>Last Order:</td><td><?php if($details['lastorder']!=''||$details['lastorder']!=NULL) echo $details['lastorder']; else echo '--'; ?></td></tr>
            <tr><td>Next Date:</td><td><?php echo revertDate($details['next_dt']);?></td>
                </tr>
    
    
    
            <tr>
        ?>
                <tr>
                    <td><?= $s++; ?></td>
                    <td><?= $notbef; ?></td>
                    <td><?= $row['jname']; ?></td>
                    <td><?= $row['res_add']; ?></td>
                    <td><?= $row['ent_dt']; ?></td>
                </tr>
            <?php endforeach; ?>
    </table>
    <!-- </div> -->
    <br>
<?php else: ?>
    <div style="text-align:center; padding:10px;">LIST BEFORE/NOT BEFORE/CORAM NOT FOUND</div>
<?php endif; ?>




<table align="center">
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
    <tr>
        <td>Weekly Single Judge Advance List Date</td>
        <td>
            <?php if ($advance_list): ?>
                <select id="advance_list_date" name="advance_list_date">
                    <?php foreach ($advance_list as $date_item): ?>
                        <option value="<?= $date_item['next_dt'] ?>"><?= $date_item['next_dt'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <span class="red-text">No un-published advance list dated <?= isset($details['next_dt']) ? $details['next_dt'] : '--' ?> exist!</span>
            <?php endif; ?>
        </td>

        <td>Ready/Not Ready</td>
        <td>
            <?php if (isset($details['main_supp_flag']) && $details['main_supp_flag'] == 0): ?>
                <span class="green-text">Ready</span>
            <?php else: ?>
                <span class="red-text">Not Ready</span>
            <?php endif; ?>
        </td>
    </tr>

</table>

<?php if (!isset($details['advance_list_date']) || is_null($details['advance_list_date'])): ?>
    <?php if ($advance_list && $details['c_status'] != 'D' && $details['main_supp_flag'] == 0 && $ifMain == 1): ?>
        <div>
            <div>
                <table align="center" id="tb_clr_n" border="1" style="border-collapse: collapse">
                    <tr>
                        <th colspan="5"><input type="button" value="Add in Advance List" name="savebutton" /></th>
                    </tr>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div>
        <div class="center red-text">Already Allocated in advance List dated <?= $details['advance_list_date'] ?>.</div>
    </div>
<?php endif; ?>

</div>

<style>
    div#sesframe {
        background-color: #c7fabf;
        color: #212221;
        opacity: 0.9;
        filter: alpha(opacity=90);
        /* For IE8 and earlier */
        padding: 5px;
        position: fixed;
        top: 0;
        left: 0;
        margin: auto;
        padding: 5px;
    }
</style>