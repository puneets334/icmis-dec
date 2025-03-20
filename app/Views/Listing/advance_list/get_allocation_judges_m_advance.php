<?php if ($isNMD == 1): ?>
    <span style="color:blue;"><b>Ready to list NMD Cases</b></span><br>
<?php elseif ($isNMD == 0): ?>
    <span style="color:green;"><b>Ready to List Misc. Day Cases</b></span><br>
<?php else: ?>
    <span style="color:red;"><b>Not a Working Day</b></span><br>
<?php endif; ?>

<?php if (!empty($judges)): ?>
    <fieldset>
        <legend style="text-align:center;color:#4141E0; font-weight:bold;">ADVANCE LIST ALLOCATION FOR DATED <?= $cldt; ?> </legend>
        <table border="1" width="100%" style="border-collapse:collapse; border-color:black; vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=0>
            <tr>
                <th>SrNo.</th>
                <th>Hon'ble Judge</th>
                <th>To be Listed</th>
                <th>Pre Notice Listed</th>
                <th>After Notice Listed</th>
                <th>Total Listed</th>
            </tr>

            <?php $srno = 1; $total = ['listed' => 0, 'Pre_Notice' => 0, 'After_Notice' => 0]; ?>
            <?php foreach ($allocationData as $data): ?>
                <tr>
                    <td><?= $srno++; ?></td>
                    <td><?= $data['judge']['abbreviation']; ?></td>
                    <td><?= $data['judge']['old_limit']; ?></td>
                    <td><?= $data['details']['Pre_Notice'] ?? 0; ?></td>
                    <td><?= $data['details']['After_Notice'] ?? 0; ?></td>
                    <td><?= $data['details']['listed'] ?? 0; ?></td>
                </tr>
                <?php
                $total['Pre_Notice'] += $data['details']['Pre_Notice'] ?? 0;
                $total['After_Notice'] += $data['details']['After_Notice'] ?? 0;
                $total['listed'] += $data['details']['listed'] ?? 0;
                ?>
            <?php endforeach; ?>

            <tr style="font-weight:bold;">
                <td colspan="2">TOTAL</td>
                <td><?= $total['Pre_Notice']; ?></td>
                <td><?= $total['After_Notice']; ?></td>
                <td><?= $total['listed']; ?></td>
            </tr>
        </table>
    </fieldset>
<?php else: ?>
    <center>No Records Found</center>
<?php endif; ?>
