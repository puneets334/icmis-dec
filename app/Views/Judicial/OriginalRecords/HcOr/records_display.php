<?php
//pr($records);
?>
<?php if (!empty($paging['total_records'])): ?>
    <input type="hidden" name="hd_fst" id="hd_fst" value="<?= $paging['fst']; ?>" />
    <input type="hidden" name="hd_lst" id="hd_lst" value="<?= $paging['lst']; ?>" />
    <input type="hidden" name="inc_val" id="inc_val" value="<?= $paging['inc_val']; ?>" />
    <input type="hidden" name="inc_tot" id="inc_tot" value="<?= $paging['tot_pg']; ?>" />
    <input type="hidden" name="inc_count" id="inc_count" value="1" />

    <div style="text-align: center" class="dv_right" id="dv_le_ri">
        <span id="sp_frst"><?= $paging['fst'] + 1; ?></span>-
        <span id="sp_last"><?= min($paging['fst'] + $paging['inc_val'], $paging['total_records']); ?></span>
        of <span id="sp_nf"><?= $paging['total_records']; ?></span>
        <?php if ($paging['total_records'] > $paging['inc_val']): ?>
            <input type="button" name="btn_left" id="btn_left" onClick="getbtn_left();" value="PREV" />
            <input type="button" name="btn_right" id="btn_right" onClick="getbtn_right();" value="NEXT" />
        <?php endif; ?>
    </div>
    <?php if (!empty($getDefects)) {
        $getisVerfiy = $getDefects['if_verified']; //pr($getisVerfiy);
    } ?>

    <div id="dv_include" style="text-align: center;width: 100%">
        <table class="table_tr_th_w_clr" cellpadding="5" cellspacing="5">
            <tr>
                <th>S.No.</th>
                <th>Diary No.</th>
                <th>Case No.</th>
                <th>Status</th>
                <th>State</th>
                <?php
                if ($resultLength < 1 and $userId == $dacode) { ?>
                    <th>
                        Details
                    </th>
                <?php
                } else if (
                    isset($resultLength, $userId, $users_to_ignore, $getisVerfiy) &&
                    $resultLength > 0 &&
                    is_array($users_to_ignore) &&
                    in_array($userId, $users_to_ignore) &&
                    $getisVerfiy === 'V'
                ) {
                ?>
                    <th>
                        Details
                    </th>
                <?php }
                if ($resultLength < 1 and $userId == $dacode) { ?>
                    <th>
                        Verify Record
                    </th>
                <?php } ?>
            </tr>
            <?php
            // Initialize necessary variables
            $sno = 1;
            $ifConfirmed = 0;

            foreach ($records['result'] as $i => $record) {
                $queryVerify = $records['queryVerify'][$i] ?? null;
            ?>

                <tr class="tr_diary<?php echo $sno; ?>">
                    <td><?= $sno; ?></td>

                    <td>
                        <span id="sp_diary_no<?php echo $sno; ?>"
                            class="badge badge-primary cl_c_diary" title="Click to view Case Status Process" style="cursor: pointer;"><?php echo substr($record['diary_no'], 0, -4) . '-' . substr($record['diary_no'], -4); ?></span>
                    </td>

                    <td>
                        <?php echo $record['type_sname']; ?>-<?php echo $record['lct_caseno']; ?>-<?php echo $record['lct_caseyear']; ?>
                    </td>

                    <td>
                        <?php
                        if ($record['conformation'] == '0') {
                            echo "<span class='badge badge-warning'>Not Completed</span>";
                        } else if ($record['conformation'] == '1') {
                            echo "<span class='badge badge-success'>Completed</span>";
                            $ifConfirmed = 1;
                        }
                        ?>
                    </td>

                    <td>
                        <?php echo $record['agency_name']; ?>
                    </td>

                    <?php
                    // Conditional check for Details button
                    if ($resultLength < 1 && $userId == $dacode) { ?>
                        <td>
                            <span class="btn btn-primary btn-sm sp_details" id="sp_d_<?php echo $sno; ?>">Details</span>
                        </td>
                    <?php
                    } else if (
                        isset($resultLength, $userId, $users_to_ignore, $getisVerfiy) &&
                        $resultLength > 0 &&
                        is_array($users_to_ignore) &&
                        in_array($userId, $users_to_ignore) &&
                        $getisVerfiy === 'V'
                    ) {
                    ?>
                        <td>
                            <span class="btn btn-primary btn-sm sp_details" id="sp_d_<?php echo $sno; ?>">Details</span>
                        </td>
                    <?php } ?>

                    <?php
                    // If the record is confirmed, show the verify button
                    if ($ifConfirmed) {
                        if ($resultLength < 1 && $userId == $dacode) { ?>
                            <td>
                                <span class="sp_verify"
                                    id="spv-<?php echo substr($record['diary_no'], 0, -4) . '-' . substr($record['diary_no'], -4); ?>">verify</span>
                            </td>
                    <?php }
                    }
                    ?>

                </tr>

            <?php
                $sno++;  // Increment the serial number
            } // End of foreach loop
            ?>


            <input type="hidden" name="hd_cnt_no" id="hd_cnt_no" />
            <input type="hidden" name="hd_fil_no" id="hd_fil_no" />
            <input type='hidden' name='inc_tot_pg' id='inc_tot_pg' value="<?php echo $sno; ?>" />
        </table>
    </div>
    <?php if ($resultLength > 0) {
        // Check defect verification records
        $getDefectdata = getHcOrDefectVerification($diary_no, $ifConfirmed);
            if (!$getDefectdata) {
                echo $getDefectdata; // Print defect data if found
            ?>
        <?php } else {
            // Check pending verification records if no defect data is found
            $getPendingVerification = getHcOrPendingVerification($diary_no);
            if (!$getPendingVerification) {
                echo $getPendingVerification; // Print pending verification data if found
            ?>
        <?php
            }
        }
    } else {
        ?>
        <!-- If no records are found, display a message -->
        <div class="text-center card">
            <div class="card-body">
                <h4 class="mb-0 text-danger">No Record Found</h4>
            </div>
        </div>
    <?php
    } ?>

<?php else: ?>
    <div class="text-center card">
        <div class="card-body">
            <h4 class="mb-0 text-danger">No Record Found</h4>
        </div>
    </div>
<?php endif; ?>
<!-- Modal View -->
<div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
    &nbsp;
</div>
<div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
	left:0;
	width:100%;
	height:100%;z-index: 105;">
    <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><img src="<?php echo base_url('images/close_btn.png'); ?>"/></div>
    <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return nb(event)">
    </div>
</div>
<!-- End Modal view -->