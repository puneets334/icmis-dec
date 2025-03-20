<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Roster</title>
    <style>
        /* Add any additional styles here */
        .blue { color: blue; }
        .red { color: red; }
    </style>
</head>
<body>
    <fieldset>
        <legend style="text-align:center;color:#4141E0; font-weight:bold;">CORAM</legend>

        <?php if (!empty($judges)) : ?>
            <table border="0" width="100%" style="text-align:left; background:#f6fbf0;" cellspacing="1"> 
                <tr>
                    <th><input type="checkbox" name="chkall" id="chkall" value="ALL" onClick="chkall1(this);">All</th>
                    <th>Judges</th>
                    <th>C</th>
                    <th>R</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($judges as $row) : ?>
                    <tr>
                        <td>
                            <input type="checkbox" id="chkeeed" name="chk" value="<?= $row['jcd'] . "|" . $row['id']; ?>"  >
                            <?= $row['courtno'] . " " . $row['board_type_mb'] . " " . $row['bench_no']; ?>
                        </td>
                        <td><?= str_replace(",", " & ", $row['jnm']); ?></td>

                        <?php 
                            $caseCounts = $this->judgeModel->getCaseCounts($cldt, $row['id'], $p1);
                            $civil = $caseCounts['civil'] ?? 0;
                            $criminal = $caseCounts['criminal'] ?? 0;
                        ?>
                        <td class='blue' align='left'><?= $civil; ?></td>
                        <td class='blue' align='left'><?= $criminal; ?></td>
                        <td class='red' align='left'><?= $civil + $criminal; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <center>No Records Found</center>
        <?php endif; ?>
    </fieldset>
</body>
</html>
