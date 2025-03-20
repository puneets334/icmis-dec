
<div id="prnnt1" style="font-size:12px;">
    <table border="0" width="100%" style="font-size:12px; text-align: left; background: #ffffff;" cellspacing=0>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                    SUPREME COURT OF INDIA<BR>Categoray wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?>
                </th>
            </tr>
        </thead>
    </table>
    <?php if (!empty($data)): ?>
        <table align="left" width="100%" border="1px;" style="border-collapse:collapse; border-color:black; font-size:12px; table-layout: fixed;" cellspacing=0>
            <tr>
                <th style="text-align: center; font-weight: bold;">SNo</th>
                <th style="text-align: center; font-weight: bold;">Category</th>
                <th style="text-align: center; font-weight: bold;">Roster</th>
                <th style="text-align: center; font-weight: bold;">Total Courts</th>
                <th style="text-align: center; font-weight: bold;">Fresh/Fix Dt</th>
                <th style="text-align: center; font-weight: bold;">Fresh(Comp. Dt)</th>
                <th style="text-align: center; font-weight: bold;">Orders Fix Dt</th>
                <th style="text-align: center; font-weight: bold;">Order Comp. Dt</th>
                <th style="text-align: center; font-weight: bold;">AN/FD Fix Dt</th>
                <th style="text-align: center; font-weight: bold;">AN/FD Comp. Dt</th>
                <th style="text-align: center; font-weight: bold;">TOTAL Fix Dt</th>
                <th style="text-align: center; font-weight: bold;">TOTAL Comp Dt</th>
                <th style="text-align: center; font-weight: bold;">TOTAL</th>
            </tr>
            <?php
            $sno = 1;
            $totals = array_fill(0, 12, 0);
            foreach ($data as $row):
            ?>
                <tr>
                    <td align="center"><?= $sno++ ?></td>
                    <td align="center"><?= esc($row['sub_name1']) ?></td>
                    <td align="center"><?= esc($row['judge']) ?></td>
                    <td align="center">
                        <?= $total_judges = isset($row['judge']) ? count(explode(",", $row['judge'])) : 0 ?>
                    </td>

                    <td align="center"><?= $row['tobe_list_all'] ?></td>
                    <td align="center"><?= $row['fresh_head_cnt'] ?></td>
                    <td align="center"><?= $row['order_cnt_fd'] ?></td>
                    <td align="center"><?= $row['order_cnt'] ?></td>
                    <td align="center"><?= $row['notice_cnt_fd'] ?></td>
                    <td align="center"><?= $row['notice_cnt'] ?></td>
                    <td align="center"><?= ($fixdt = $row['tobe_list_all'] + $row['order_cnt_fd'] + $row['notice_cnt_fd']) ?></td>
                    <td align="center"><?= ($comp_dt = $row['fresh_head_cnt'] + $row['order_cnt'] + $row['notice_cnt']) ?></td>
                    <td align="center"><?= $row['case_cnt'] ?></td>
                </tr>
            <?php
                // Update totals
                foreach ($row as $key => $value) {
                    if (isset($totals[$key])) {
                        $totals[$key] += $value;
                    }
                }
            endforeach;
            ?>
            <tr style="background: #918788; font-weight: bold;">
                <td colspan="4" align="right">TOTAL</td>
                <td align="center"><?= $totals[0] ?></td>
                <td align="center"><?= $totals[1] ?></td>
                <td align="center"><?= $totals[2] ?></td>
                <td align="center"><?= $totals[3] ?></td>
                <td align="center"><?= $totals[4] ?></td>
                <td align="center"><?= $totals[5] ?></td>
                <td align="center"><?= $totals[6] ?></td>
                <td align="center"><?= $totals[7] ?></td>
                <td align="center"><?= $totals[8] ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>No Records Found</p>
    <?php endif; ?>
    <br />
    
</div>
<input name="prnnt1" type="button" id="prnnt" value="Print">
