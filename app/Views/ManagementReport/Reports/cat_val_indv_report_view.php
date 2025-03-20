<!DOCTYPE html>
<html lang="en">
<div id="prnnt" style="font-size:12px;">
<head>
    <meta charset="UTF-8">
    <title>Report</title>
</head>
<body>
    <h1>Report for <?= date('d-m-Y', strtotime($list_dt)); ?></h1>

    <table border="1" class="table table-bordered">
        <thead>
            <tr>
                <th>SNo</th>
                <th>Category</th>
                <th>Orders</th>
                <th>Fresh</th>
                <th>Fresh (No Orders)</th>
                <th>Notice</th>
                <th>Total Cases</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($reportData) && count($reportData) > 0): ?>
                <?php $sno = 1; ?>
                <?php foreach ($reportData as $row): ?>
                    <tr>
                        <td><?= $sno++; ?></td>
                        <td><?= esc($row['sub_name1']); ?></td>
                        <td><?= esc($row['order_cnt']); ?></td>
                        <td><?= esc($row['fresh_cnt']); ?></td>
                        <td><?= esc($row['fresh_head_cnt']); ?></td>
                        <td><?= esc($row['notice_cnt']); ?></td>
                        <td><?= esc($row['case_cnt']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No Records Found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <input name="prnnt1" type="button" id="prnnt1" value="Print">
</body>
            </div>
</html>
