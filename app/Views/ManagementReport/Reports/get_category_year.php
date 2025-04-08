<style>
td {
    line-height: 1.5 !important;
}
th {
    line-height: 1.5 !important;
}
</style>
<div id="prnnt" style="font-size:12px;">
        <h3 style="text-align:center;">Category & Year Wise Detailed Pendency Report (including defects) as on : <?= date('d-m-Y H:i:s'); ?></h3>
        <?php if (!empty($categoryReportData)){ ?>
		<div class="table-responsive">
		  <table class="table table-striped custom-table" id="example1">
		   <thead> 
                <tr style="background: #A9A9A9;">
                    <th>SrNo.</th>
                    <th>Category Code</th>
                    <th>Category Name</th>
                    <th>Total</th>
                    <th>Upto 1990</th>
                    <?php for ($i = 1991; $i <= 2021; $i++): ?>
                        <th><?= $i ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categoryReportData) && is_array($categoryReportData)){ ?>
                    <?php $sno = 1; $sno_detail = 1;  $total_row = count($categoryReportData);?>
                    <?php foreach ($categoryReportData as $ro){						
					     if($ro['subcode2'] == 0){?>
							<tr>
								<td align="left" colspan="3" style='font-weight:bold; vertical-align: top;'>
									<?php  if($total_row == $ro['sno']){
												$sno_detail = 1;
												echo "TOTAL PENDENCY";
											}else{
												$sno_detail = 1;
												if($ro['org_subcode1'] == 99){
													echo "TOTAL - ".$ro['sub_name1'];
												}else{
													echo "TOTAL - ".$ro['main_name']." [".$ro['org_subcode1'] . '00'."]";
												}
											}
									?>
								</td>
								<td align="left" style='font-weight:bold; vertical-align: top;'><?= $ro['gt'] ?></td>
								<td align="left" style='font-weight:bold; vertical-align: top;'><?= $ro['upto_1990'] ?></td>
								 <?php  for($i=1991;$i<=2021;$i++){    
										  $col_name = "year_".$i; ?>
											<td align="left" style='font-weight:bold; vertical-align: top;'><?= $ro[$col_name] ?></td>
								<?php } ?> 
							</tr>
							<?php }else{
								 if($ro['org_subcode1'] != 99) {?>
									 <tr onclick="call_cs('<?= $ro['subcode1'] ?>', '<?= date('d-m-Y'); ?>')" ;>
												<td align="left" style='vertical-align: top;'><?= $sno_detail ?></td>
												<td align="left" style='vertical-align: top;'>
													<?php  if ($ro['subcode2'] == null)
														echo $ro['org_subcode1'] . '00';
													else
														echo $ro['subcode1'];
													?>
												</td>
												<td align="left" style='vertical-align: top;'><?= $ro['sub_name1'] ?></td>
												<td align="left" style='vertical-align: top;'><?= $ro['gt'] ?></td>
												<td align="left" style='vertical-align: top;'><?= $ro['upto_1990'] ?></td>
									         <?php  for($i=1991;$i<=2021;$i++){    
												    $col_name = "year_".$i; ?>
											      <td align="left" style='vertical-align: top;'><?= $ro[$col_name] ?></td>
								              <?php } ?> 
							        </tr>
							<?php }
								$sno++;$sno_detail++;
						}  
                    }}?>
				<tbody>	
		  </table>
	</div>
<?php }else{ ?>
        <p>No Records Found</p>
<?php } ?>
</div>						
<input name="prnnt" type="button" id="prnnt" value="Print">
