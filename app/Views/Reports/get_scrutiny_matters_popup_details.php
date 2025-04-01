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
</style>
<?php 
if(!empty($result_array)){ 
$temp='';
if ($case == 'spallot') {
  $temp=" Total Scrutiny Matters assigned To ";
} elseif ($case == 'spcomp') { 
  $temp=" Completed Scrutiny Matters of ";
} elseif ($case == 'spnotcomp') {  
   $temp=" Pending Scrutiny Matters of ";
} elseif ($case == 'sptotal') {
    $temp=" Total Scrutiny Matters ";  
} elseif ($case == 'spcomplete') {
    $temp=" Completed Scrutiny Matters ";
}elseif ($case == 'sppend') {
    $temp=" Pending Scrutiny Matters ";	
}

?>
<div align="right"><input name="print2" type="button" id="print2" value="Print" ></div><div id="printDiv2">
	<h2> 
	   <?php echo $temp;?>
	   <?php
		if($empid!='total') {
			 echo  get_user_name_info($empid) . ' [' . $empid . ']' . ' between ' . date('d-m-Y', strtotime($frm_dt)) . ' and ' . date('d-m-Y', strtotime ($to_dt));
		} else {
			echo ' between ' . $frm_dt . ' and ' . $to_dt;
		} ?>
	</h2>

<div class="table-responsive">
 <table class="table table-striped custom-table" id="example1">
  <thead>
     <tr>
		<th>S.No.</th>
		<th>Diary No.</th>
		<th>Cause Title</th>
		<th>Dispatch Date</th>
	</tr>
  </thead>
	 <?php
		$sno = 1;
		foreach($result_array as $row) {?>
			<tr>
				<td>
					<?php echo $sno; ?>
				</td>
				<td>
					<?php echo substr($row['diary_no'], 0, strlen($row['diary_no']) - 4); ?>-<?php echo substr($row['diary_no'], -4); ?>
				</td>
				<td>
					<?php echo $row['causetitle'];?>
				</td>
				<td>
					<?php echo date('d-m-Y H:i:s', strtotime($row['disp_dt'])); ?>
				</td>
			</tr>
			<?php $sno++;
		} ?> 
   </table>
</div>
<?php }else{?>
	<h3 style='margin-top: 20px; text-align:center;' >Not Found</h3>
<?php }?>		
		
		
		
		
		
		
		
		
		
		
		