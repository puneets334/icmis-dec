<div class="border mb-5 p-3">

    <fieldset>
        <legend style="text-align:center;color:#4141E0; font-weight:bold;">CORAM</legend>
        <?php if (!empty($allocation)): ?>
            <table border="0" width="100%" style="vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing="1">
                <tr>
                    <th style="vertical-align: bottom;">
                        <input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);">All
                    </th>
                    <th>Judges</th>
                    <th>C</th>
                    <th>R</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($allocation as $row): ?>
                    <?php
                   
                    $cases = $roster->getCivilCriminalCases($cldt, $row['jcd'], $board_type, $p1);
                    $civil = $cases['civil'] ?? 0;
                    $criminal = $cases['criminal'] ?? 0;
                    $c_r = $civil + $criminal;
                    ?>
                    <tr style="vertical-align: bottom;">
                        <td style="vertical-align: bottom;">
                            <input type="checkbox" id="chkeeed" name="chk" value="<?= esc($row['jcd']) . '|' . esc($row['id']); ?>">
                            <?= esc($row['courtno']) . " " . esc($row['board_type_mb']) . " " . esc($row['bench_no']); ?>
                        </td>
                        <td><?= str_replace(",", " & ", esc($row['jnm'])); ?></td>
                        <td style='color:blue' align="left"><?= esc($civil); ?></td>
                        <td style='color:blue' align="left"><?= esc($criminal); ?></td>
                        <td style='color:red' align="left"><?= esc($c_r); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <center>No Records Found</center>
        <?php endif; ?>
    </fieldset>
</div>