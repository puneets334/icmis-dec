<div id="printable">
<?php if(!empty($result_array)){ ?>
<div class="table-responsive">
 <table class="table table-striped custom-table" id="example1">
    <thead>
       <tr>
			<tr>
				<th>SNo.</th>
				<th>Dealing Assistant</th>
				<th>Pending Matters</th>
			</tr>
		</tr>
	</thead>
	<tbody>
			<?php
			$i=0;
			$pendency=0;
			foreach ($result_array as $result)
			{$i++;
				?>
				<tr>
					<td ><?php echo $i;?></td>
					<td><?php echo $result['name'];?></td>
					<td><a href="<?=base_url();?>/PendencyReports/Physical_verify/da_pen?usercode=<?php echo $result['usercode'];?>&sect=<?php echo $section;?>" target="_blank" style="color: #0d48be;"><?php echo $result['pendency'];?></td>

				</tr>
				<?php
				$pendency += $result['pendency'];
			}
			?>
	 <tr style="font-weight: bold;"><td colspan="2" ><b>Total</b></td><td><b><?= $pendency ?></b></tr>
	</tbody>
</table>
<?php }else { ?>
   <h3 style="text-align:center">Records Not Found.!!</h3>
<?php }?>
</div>
<script>
$(document).ready(function() {
        $('#example1').DataTable();
    });
	
		
      
</script>

<script type="text/javascript">
    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
