<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Wise Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-records {
            text-align: center;
            font-size: 16px;
            color: red;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div id="prnnt" style="font-size:11px;">
        <?php if (empty($data)): ?>
            <div class="no-records">No Records Found for the selected date.</div>
        <?php else: ?>
            <?php foreach ($data as $judgeCode => $judgeData): ?>
                <div style="page-break-after:always;">
                    <h3>Supreme Court of India</h3>
                    <h4>Category wise ready cases with roster for dated: <?= date('d-m-Y', strtotime($list_dt)) ?></h4>
                    <h5><?= $judgeData['judge_name'] ?></h5>

                    <table>
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Category</th>
                                <th>Bail/Top</th>
                                <th>Orders</th>
                                <th>Fresh</th>
                                <th>Fresh(No Orders for Notice)</th>
                                <th>AN/FD</th>
                                <th>TOTAL</th>
                                <th>Ratio (Approx)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalBailTop = 0; 
                            $totalOrders = 0; 
                            $totalFresh = 0; 
                            $totalFreshNoNotice = 0; 
                            $totalANFD = 0; 
                            $totalCount = 0; 
                            $sno = 1; 
                            ?>
                            <?php foreach ($judgeData['categories'] as $row): ?>
                                <tr>
                                    <td><?= $sno++ ?></td>
                                    <td><?= $row['cat_name'] ?></td>
                                    <td><?= $row['bail_top'] ?></td>
                                    <td><?= $row['orders'] ?></td>
                                    <td><?= $row['fresh'] ?></td>
                                    <td><?= $row['fresh_no_notice'] ?></td>
                                    <td><?= $row['an_fd'] ?></td>
                                    <td><?= $row['cnt'] ?></td>
                                    <td><?= round($row['ratio_cnt']) ?></td>
                                </tr>
                                <?php 
                                $totalBailTop += $row['bail_top'];
                                $totalOrders += $row['orders'];
                                $totalFresh += $row['fresh'];
                                $totalFreshNoNotice += $row['fresh_no_notice'];
                                $totalANFD += $row['an_fd'];
                                $totalCount += $row['cnt'];
                                ?>
                            <?php endforeach; ?>
                            <tr style="font-weight: bold;">
                                <td colspan="2">TOTAL</td>
                                <td><?= $totalBailTop ?></td>
                                <td><?= $totalOrders ?></td>
                                <td><?= $totalFresh ?></td>
                                <td><?= $totalFreshNoNotice ?></td>
                                <td><?= $totalANFD ?></td>
                                <td><?= $totalCount ?></td>
                                <td><?= count($judgeData['categories']) > 0 ? round($totalCount / count($judgeData['categories'])) : 0 ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div> 

    
</body>
<input name="prnnt1" type="button" id="prnnt1" value="Print">
</html>
