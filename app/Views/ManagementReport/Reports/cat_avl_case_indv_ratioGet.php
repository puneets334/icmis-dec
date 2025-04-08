<div id="prnnt" style="font-size:11px;">
        <?php if (empty($data)): ?>
            <div class="no-records" style="text-align: center; font-size: 18px">No Records Found for the selected date.</div>
        <?php else: ?>
            <?php foreach ($data as $judgeCode => $judgeData): ?>
                <div style="page-break-after:always;">
					<h3 style="text-align: center; line-height: 1.5;"> SUPREME COURT OF INDIA<br>Categoray wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?><br><?= $judgeData['judge_name'] ?></h3>
                   <div class="table-responsive">
					<table class="table table-striped custom-table" id="example1">
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
                            <tr >
                                <td colspan="2" style="font-weight: bold; text-align:right; background: #918788 !important;">TOTAL</td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalBailTop ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalOrders ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalFresh ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalFreshNoNotice ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalANFD ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"><?= $totalCount ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"> <?= count($judgeData['categories']) > 0 ? round($totalCount / count($judgeData['categories'])) : 0 ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
			 </div>	
            <?php endforeach; ?>
        <?php endif; ?>
    </div> 

<input name="prnnt" type="button" id="prnnt" value="Print">
</html>
