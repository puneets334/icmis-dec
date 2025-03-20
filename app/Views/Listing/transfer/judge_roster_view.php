<!--<h2>Judge Roster for <?php echo esc($p1); ?> on <?php echo esc($cldt); ?></h2>-->
<?php if (!empty($judges)): ?>
    <table border="0" cellspacing="1" style="background:#f6fbf0;">
        <tr>
            <th><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);">All</th>
            <th>Judges</th>
            <th>C</th>
            <th>R</th>
            <th>Total</th>
        </tr>
        <?php foreach ($judges as $row): ?>
            <tr>
                <td>
                    <input type="checkbox" name="chk" value="<?php echo esc($row["jcd"]) . "|" . esc($row["id"]); ?>">
                    <?php echo esc($row['courtno'] . " " . $row['board_type_mb'] . " " . $row['bench_no']); ?>
                </td>
                <td><?php echo esc(str_replace(",", " & ", $row['jnm'])); ?></td>
                <?php 
                    //$row1 = $this->Roster->getCivilCriminalCounts($cldt, $p1, $row["id"]);
                    $row1 = getCivilCriminalCounts($cldt, $p1, $row["id"]);
                ?>                            
                <td style='color:blue' align="left"><?php echo esc($row1['civil'] ?? '0'); ?></td>
                <td style='color:blue' align="left"><?php echo esc($row1['criminal'] ?? '0'); ?></td>
                <td style='color:red' align="left"><?php echo esc(($row1['civil'] ?? 0) + ($row1['criminal'] ?? 0)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <center>No Records Found</center>
<?php endif; ?>

