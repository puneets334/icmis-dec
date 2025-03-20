 
<div id="prnnt" style="text-align: center; font-size:14px;">
    <h3>SENSITIVE CASES</h3>
<!--<table class="table_tr_th_w_clr c_vertical_align" cellspacing="5" cellpadding="5">-->
	<table border="1" width="100%" style="vertical-align: top; border-collapse:collapse; border-color:black; vertical-align: bottom; text-align: left; background:#f6fbf0;" cellspacing=0>     
				<thead>
				<tr>
					<th>
						SNo.
					</th>
					 <th>
						Diary No.
					</th>
					<th>
						Case No.
					</th>
					<th>
						Cause Title
					</th>
					<th>
						Coram
					</th>
					<th>
						Not Before
					</th>
					<th>
						Reason
					</th>        
					<th>
						Next Date
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($results))
				{
					$sno=1;
					 foreach ($results as $row) 
					 {
						 ?>
						<tr>
							<td>
								<?php echo $sno; ?>
							</td>
							<td>
								<?php 
						 echo substr( $row[diary_no], 0, strlen( $row[diary_no] ) -4 ) ; ?>-<?php echo substr( $row[diary_no] , -4 );
						 echo "<br>".$row[ten_sec]; ?>
							</td>
							<td>
								<?php
								if($row['active_fil_no']!='')
								{
									if($row[reg_no_display]){
										echo $row[reg_no_display];
									}
									else{
										echo $row['short_description']." / ".substr($row['active_fil_no'],3)."/".$row[active_fil_dt]; 
									}
								}
								?>
							</td>
							<td>
								<?php echo $row[pet_name]." Vs. ".$row[res_name]; ?>
							</td>
							<td>
								<?php if($row[coram] != '' AND $row[coram] != '0'){
									$sq = "select GROUP_CONCAT(abbreviation) abr from judge where jcode in ($row[coram]) and jtype = 'J' GROUP BY jtype";
									  $sqqq =  mysql_query($sq) or die("Error: ".__LINE__.  mysql_error());
									  $ros = mysql_fetch_array($sqqq);
									  echo $ros[abr];
								}
								?>
							</td>
							<td>
								<?php f_get_ntl_judge($row['diary_no']);               
								  f_get_ndept_judge($row['diary_no']);            
								  f_get_category_judge($row['diary_no']);            
								  f_get_not_before($row['diary_no']);
								  ?>
							</td>
							<td>
								<?php echo $row[reason]; ?>
							</td>
							
							  <td>
								<?php
							   if($row[next_dt]!='0000-00-00' && $row[next_dt]!=NULL && get_display_status_with_date_differnces($row['next_dt'])=='T')
									echo date('d-m-Y',  strtotime($row[next_dt]));
								?>
							</td>
						</tr>
						<?php
						$sno++;
					}
				}else{?>
					<tr>
						<td colspan="100%">No Record Found</td>
					</tr>
				<?php }
					?>
			</tbody>
		</table>
	</div>

<?php if(!empty($results)){?>
	<div style="width: 100%; padding-bottom:1px; background-color: #ddf1f9; text-align: center; border-top: 1px solid #000; position: relative; bottom: 0; left: 0; right: 0; z-index: 0; display:block;">   

	<input name="prnnt1" type="button" id="prnnt1" value="Print">
	</div>
<?php }?>

	 