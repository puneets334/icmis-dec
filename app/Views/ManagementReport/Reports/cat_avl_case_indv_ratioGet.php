<style>
.table-striped tr:nth-child(odd) td {
    background: #fff !important;
    box-shadow: none;
    border: 1px solid #8080805e;
    text-align: center;
}

.table-striped tr:nth-child(even) td {
    background: #f5f5f5;
	border: 1px solid #8080805e;
    text-align: center;
}

td {
    line-height: 1.5 !important;
}

th {
    line-height: 1.5 !important;
}
</style>
<div id="prnnt" style="font-size:11px;">
        <?php if (empty($data)): ?>
            <div class="no-records" style="text-align: center; font-size: 18px">No Records Found for the selected date.</div>
        <?php else: ?>
            <?php foreach ($data as $judgeCode => $judgeData): ?>
                <div style="page-break-after:always;">
					<h3 style="text-align: center; line-height: 1.5;"> SUPREME COURT OF INDIA<br>Categoray wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?><br><?php echo $judgeData['judge_name']; ?></h3>
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
                                <th>Cat. Allotted in No. of Courts</th>
                                <th>Total Required</th>
                            </tr>
                        </thead>
                        <tbody>
						 <tr>
							    <td><b>1</b></td>
							    <td><b>2</b></td>
							    <td><b>3</b></td>
							    <td><b>4</b></td>
							    <td><b>5</b></td>
							    <td><b>6</b></td>
							    <td><b>7</b></td>
							    <td><b>8</b></td>
							    <td><b>9</b></td>
							    <td><b>10</b></td>
							    <td><b>11</b></td>
							</tr>
                            <?php 
                            $totalBailTop = 0; 
                            $totalOrders = 0; 
                            $totalFresh = 0; 
                            $totalFreshNoNotice = 0; 
                            $totalANFD = 0; 
                            $totalCount = 0; 
							$total_this_cat_ratio = 0;
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
                                    <td><?= round($row['ratio_cnt'],3) ?></td>
									<td><?= round($row['cattlk']) ?></td>
									<td><?= round($row['totcatlk'],3) ?></td>
                                </tr>
                                <?php 
                                $totalBailTop += $row['bail_top'];
                                $totalOrders += $row['orders'];
                                $totalFresh += $row['fresh'];
                                $totalFreshNoNotice += $row['fresh_no_notice'];
                                $totalANFD += $row['an_fd'];
                                $totalCount += $row['cnt'];
								$total_this_cat_ratio += round($row['ratio_cnt'],3);
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
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"> <?= round($total_this_cat_ratio) ?></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"></td>
                                <td style="font-weight: bold; text-align:center; background: #918788 !important;"></td>
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
