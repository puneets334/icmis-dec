
<div id="prnnt1" style="font-size:12px;">
    <h3 style="text-align: center; line-height: 1.5;"> SUPREME COURT OF INDIA<br>Category wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?></h3>
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
			$t_tobe_list_all = 0; $t_fresh_head_cnt=0; $t_order_cnt_fd=0; $t_order_cnt =0; $t_notice_cnt_fd=0; $t_notice_cnt =0;  $fixdt=0; $comp_dt=0; $t_fix_dt=0; $t_comp_dt=0; $t_case_cnt=0;
					
			foreach ($data as $row):
            ?>
             <tr>
                    <td align="center"><?= $sno++ ?></td>
                    <td align="left"><?= esc($row['sub_name1']) ?></td>
                    <td align="center"><?= esc($row['judge']) ?></td>
                    <td align="center">
                        <?= $total_judges = isset($row['judge']) ? count(explode(",", $row['judge'])) : 0 ?>
                    </td>

                    <td align="center" style='vertical-align: top;'><?php $t_tobe_list_all += $row['tobe_list_all']; echo $row['tobe_list_all']; ?></td>
					<td align="center" style='vertical-align: top;'><?php $t_fresh_head_cnt += $row['fresh_head_cnt']; echo $row['fresh_head_cnt']; ?></td>
					<td align="center" style='vertical-align: top;'><?php $t_order_cnt_fd += $row['order_cnt_fd']; echo $row['order_cnt_fd']; ?></td>
					<td align="center" style='vertical-align: top;'><?php $t_order_cnt += $row['order_cnt']; echo $row['order_cnt']; ?></td>        
					<td align="center" style='vertical-align: top;'><?php $t_notice_cnt_fd += $row['notice_cnt_fd']; echo $row['notice_cnt_fd']; ?></td>
					<td align="center" style='vertical-align: top;'><?php $t_notice_cnt += $row['notice_cnt']; echo $row['notice_cnt']; ?></td>
					<td align="center" style='vertical-align: top;'><?php echo $fixdt = $row['tobe_list_all'] + $row['order_cnt_fd'] + $row['notice_cnt_fd']; $t_fix_dt += $fixdt; ?></td>
					<td align="center" style='vertical-align: top;'><?php echo $comp_dt = $row['fresh_head_cnt'] + $row['order_cnt'] + $row['notice_cnt']; $t_comp_dt += $comp_dt; ?></td>
					<td align="center" style='vertical-align: top;'><?php $t_case_cnt += $row['case_cnt']; echo $row['case_cnt']; ?></td>
            </tr>
            <?php
              endforeach;
            ?>
            <tr style="background: #918788; font-weight: bold;">
                <td colspan="4" align="right" style='font-weight: bold; vertical-align: top; text-align:right;background: #918788 !important;'>TOTAL</td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_tobe_list_all; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_fresh_head_cnt; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_order_cnt_fd; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_order_cnt; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_notice_cnt_fd; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_notice_cnt; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_fix_dt; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_comp_dt; ?></td>
                <td align="center" style='font-weight: bold; vertical-align: top;background: #918788 !important;'><?php echo $t_case_cnt; ?></td>
			</tr>
        </table>
    <?php }else{ ?>
        <p>No Records Found</p>
    <?php } ?>
    <br />
    
</div>
<input name="prnnt1" type="button" id="prnnt" value="Print">
