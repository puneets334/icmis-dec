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
<div id="prnnt1" style="font-size:11px;">
<?php
if(!empty($reportData)){
	foreach($reportData as $k=>$data){
		$t_tobe_list_all = 0;
		$t_order_cnt = 0;
		$t_fresh_cnt = 0;
		$t_fresh_head_cnt = 0;
		$t_notice_cnt = 0;
		$t_case_cnt = 0;
		$total_this_cat_ratio = 0;
	if(!empty($data)){
?>
<div style="page-break-after:always;">
<h3 style="text-align: center; line-height: 1.5;"> SUPREME COURT OF INDIA<BR>Category wise ready cases with roster for dated : <?= date('d-m-Y', strtotime($list_dt)) ?>
      <br><?=$get_judges[$k]?>
</h3>
 <div class="table-responsive">
     <table class="table table-striped custom-table" id="example1">
	  <thead>
            <tr>
                     <th>SNo</th>
					 <th >Category</th>        
					 <th >Bail/Top/Appearance/settlement/direction</th>
					 <th >Orders</th>
					 <th >Fresh</th>
					 <th >Fresh(No Orders for Notice)</th>
					 <th >AN/FD</th>    
					 <th >TOTAL</th>    
					 <th >Ratio (Approx)</th>  
            </tr>
        </thead>
        <tbody>
		<tr>    
			<td ><b>1</b></td>
			<td ><b>2</b></td>    
			<td ><b>3</b></td>    
			<td ><b>4</b></td>
			<td ><b>5</b></td>    
			<td ><b>6</b></td>
			<td ><b>7</b></td>
			<td ><b>8</b></td>
			<td ><b>9</b></td>
		</tr>
      
            <?php $sno = 1; ?>
                <?php foreach ($data as $row){
					if(!empty($row['sub_name1'])){
						$this_crt_avl = $row['case_cnt'];
					}
					if ($this_crt_avl != 0) {
						$this_cat_ratio = (int)$row['case_cnt'] * 60 / (float)$this_crt_avl;
					} else {
						$this_cat_ratio = 0; // Or whatever value you prefer for this case
					}
				
				?>
                    <tr>
                        <td style="text-align: center;" ><?php echo $sno++; ?></td>
						<td style="text-align: center;"><?php echo $row['sub_name1']; ?></td>        
						<td style="text-align: center;"><?php $t_tobe_list_all += (int)$row['tobe_list_all']; echo $row['tobe_list_all']; ?></td>
						<td style="text-align: center;" ><?php $t_order_cnt += $row['order_cnt']; echo $row['order_cnt']; ?></td>
						<td style="text-align: center;"><?php $t_fresh_cnt += $row['fresh_cnt']; echo $row['fresh_cnt']; ?></td>
						<td style="text-align: center;"><?php $t_fresh_head_cnt += $row['fresh_head_cnt']; echo $row['fresh_head_cnt']; ?></td>
						<td style="text-align: center;"><?php $t_notice_cnt += $row['notice_cnt']; echo $row['notice_cnt']; ?></td>
						<td style="text-align: center;"><?php $t_case_cnt += $row['case_cnt']; echo $row['case_cnt']; ?></td>                
						<td style="text-align: center;"><?php $total_this_cat_ratio += $this_cat_ratio; echo round($this_cat_ratio, 2); ?></td>                
                    </tr>
			   <?php } ?>
				 <tr style="background: #918788; font-weight: bold; text-align:right;  background: #918788 !important;"><td colspan="2" align="right" style="background: #918788; font-weight: bold; text-align:right;  background: #918788 !important;"> TOTAL </td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_tobe_list_all; ?></td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_order_cnt; ?></td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_fresh_cnt; ?></td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_fresh_head_cnt; ?></td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_notice_cnt; ?></td>
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $t_case_cnt; ?></td>                
					<td align="center" style='font-weight: bold; vertical-align: top;  background: #918788 !important;'><?php echo $total_this_cat_ratio; ?></td>
                </tr>  
		</tbody>
    </table>	
<?php }}}else { ?>
    <div class="no-records" style="text-align: center; font-size: 18px">No Records Found for the selected date.</div>
<?php } ?>
        
</div>	
</div>
</div>
    <input name="prnnt1" type="button" id="prnnt1" value="Print">


