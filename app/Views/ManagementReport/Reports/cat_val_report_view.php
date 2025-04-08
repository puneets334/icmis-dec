
<div id="prnnt1" style="font-size:12px;">
    <h3 style="text-align: center; line-height: 1.5;"> SUPREME COURT OF INDIA<br>Categoray wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?></h3>
    <?php if (!empty($data)){ ?>
       <div class="table-responsive">
		 <table class="table table-striped custom-table" id="example1">
		 <thead> 
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
		 </thead>
		 
        <tbody>	
		<tr>    
					<td style="text-align: center; font-weight: bold; ">1</td>
					<td style="text-align: center; font-weight: bold; ">2</td>    
					<td style="text-align: center; font-weight: bold; ">3</td>    
					<td style="text-align: center; font-weight: bold; ">4</td>
					<td style="text-align: center; font-weight: bold; ">5</td>    
					<td style="text-align: center; font-weight: bold;">6</td>
					<td style="text-align: center; font-weight: bold; ">7</td>
					<td style="text-align: center; font-weight: bold; ">8</td>
					<td style="text-align: center; font-weight: bold; ">9</td>
					<td style="text-align: center; font-weight: bold; ">10</td>
					<td style="text-align: center; font-weight: bold; ">11</td>
					<td style="text-align: center; font-weight: bold; ">12</td>
					<td style="text-align: center; font-weight: bold; ">13</td>
				</tr>		
            <?php
            $sno = 1;
            $totals = array_fill(0, 12, 0);
            foreach ($data as $row):
            ?>
                <tr>
                    <td align="center"><?= $sno++ ?></td>
                    <td align="left"><?= esc($row['sub_name1']) ?></td>
                    <td align="center"><?= esc($row['judge']) ?></td>
                    <td align="center">
                        <?= $total_judges = isset($row['judge']) ? count(explode(",", $row['judge'])) : 0 ?>
                    </td>

                    <td align="center" style='vertical-align: top;' ><?= $row['tobe_list_all'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['fresh_head_cnt'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['order_cnt_fd'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['order_cnt'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['notice_cnt_fd'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['notice_cnt'] ?></td>
                    <td align="center" style='vertical-align: top;'><?= ($fixdt = $row['tobe_list_all'] + $row['order_cnt_fd'] + $row['notice_cnt_fd']) ?></td>
                    <td align="center" style='vertical-align: top;'><?= ($comp_dt = $row['fresh_head_cnt'] + $row['order_cnt'] + $row['notice_cnt']) ?></td>
                    <td align="center" style='vertical-align: top;'><?= $row['case_cnt'] ?></td>
                </tr>
            <?php
               foreach ($row as $key => $value) {
                    if (isset($totals[$key])) {
                        $totals[$key] += $value;
                    }
                }
            endforeach;
            ?>
            <tr style="background: #918788; font-weight: bold;">
                <td colspan="4" align="right" style='font-weight: bold; vertical-align: top; text-align:right;background: #918788 !important;'>TOTAL</td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[0] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[1] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[2] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[3] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[4] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[5] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[6] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[7] ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?= $totals[8] ?></td>
            </tr>
        </table>
    <?php }else{ ?>
        <p>No Records Found</p>
    <?php } ?>
    <br />
    
</div>
<input name="prnnt1" type="button" id="prnnt" value="Print">
