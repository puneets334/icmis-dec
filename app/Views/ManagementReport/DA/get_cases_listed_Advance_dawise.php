<?php 
if(COUNT($case_result)>0 && is_array($case_result)) {
?>
<h3 style="text-align: center;"><strong> Cases Listed in Advance and Daily List</strong> </h3>
<div class="table-responsive">
<table id="example1" class="table table-striped custom-table">
<thead>
<tr>
<th style="width: 5%;" rowspan='1'>SNo.</th>
<th style="width: 10%;" rowspan='1'>List Type</th>
<th style="width: 15%;" rowspan='1'>CL Date</th>
<th style="width: 5%;" rowspan='1'>Board Type</th>
<th style="width: 5%;" rowspan='1'>Court No.</th>
<th style="width: 10%;" rowspan='1'>Item No.</th>
<th style="width: 10%;" rowspan='1'>Case No.</th>
<th style="width: 40%;" rowspan='1'>Title As</th>
<th style="width: 20%;" rowspan='1'>DA</th>
</tr>
</thead>
<tbody>
<?php
$s_no = 1;
foreach ($case_result as $result) {
?>
<tr>
<td><?php echo $s_no; ?></td>
<td><?php echo $result['listtype']; ?></td>
<td><?php echo $result['cl_date']; ?></td>
<td><?php echo $result['board_type']; ?></td>
<td><?php echo $result['courtno']; ?></td>
<td><?php echo $result['brd_slno']; ?></td>
<td><?php echo $result['caseno']; ?></td>
<td><?php echo $result['pet_name'] . ' Vs ' . $result['res_name']; ?></td>
<td><?php echo $result['uid']; ?></td>
</tr>
<?php
$s_no++;
}
?>
</tbody>
</table>
</div>
<?php 
} 
?>