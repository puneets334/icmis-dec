<form method="post" action="#">      
							<div id="dv_content1"   >
							  
								<div id="div_result">
								<?php $ucode = $_SESSION['login']['usercode']; 
								
								$main_row = $CaveatModel->getCaveatPartyDetails($caveat_no);
								 
								if(empty($main_row))
								{
								?>
									<table align="center"><tr align="center"><th>Record Not Found!!!</th></tr></table> 
								<?php }else
									 
								{?>
									<div style="color:blue;text-align: center;font-weight: bold">
									<?php
										echo "Caveat No: ".session()->get('filing_details')['diary_number'].'/'.session()->get('filing_details')['diary_year']; 
										echo "<br><span style=color:black>Caveator:</span> ".$main_row['pet_name']; 
										if($main_row['pno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
										else if($main_row['pno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
									//echo" <font style=color:black>&nbsp; Versus &nbsp;</font> ";
										echo "&nbsp; &nbsp;";
										echo "<span style=color:black>Caveatee:</span> ".$main_row['res_name'];
										if($main_row['rno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
										else if($main_row['rno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
									?>
									</div>
								 
										<table border="1" style="border-collapse: collapse" align='center'>
											<thead>
												<tr>
													<th colspan="2">Caveator</th>
													</tr>
												<tr>
												<th>Name</th><th>No. of Advocate(s)</th></tr>    
											</thead>
										<?php
										/* $totalP = "SELECT COUNT(caveat_no) FROM caveat_party WHERE $fil_no_diary AND pet_res='P' AND pflag='P' ";
										$totalP = mysql_query($totalP) or die(__LINE__.'->'.mysql_error());
										$totalP = mysql_result($totalP,0); */
										
										 $totalP = is_data_from_table('caveat_party'," caveat_no= $caveat_no AND pet_res='P' AND pflag='P'",'COUNT(caveat_no)','')['count'];
										 
										/* $allP = "SELECT partyname,sr_no FROM caveat_party WHERE $fil_no_diary AND pet_res='P' AND pflag='P' ";
										$allP = mysql_query($allP) or die(__LINE__.'->'.mysql_error());
										  */
										 $allP = is_data_from_table('caveat_party'," caveat_no= $caveat_no AND pet_res='P' AND pflag='P'",'partyname,sr_no','A');
										 
										$i=1;
										if(!empty($allP))
										{
											foreach($allP as $allP_row)
											{
												?>
											<tr><td><span id="adv_p_no_name<?php echo $i;?>"><?php echo $allP_row['sr_no'].'-'.$allP_row['partyname'];?></span></td>
												<?php if($i==1){?>
												<td rowspan="<?php echo $totalP;?>" style="vertical-align: middle;text-align: center"><input type="text" maxlength="2" size="2" id="p_adv_total" onkeypress="return onlynumbers(event,this.id)" class="form-control" value="0"/></td>
												<?php }?>
											</tr>
												<?php
												$i++;
											}
										}
										?>
										</table>
									<table border="1" style="border-collapse: collapse" align='center'>
									<thead>
										<tr><td colspan="2" style="border-left:0px;">&nbsp;</td></tr>
										<tr><th colspan="2">Caveatee</th></tr>    
										<tr><th>Name</th><th>No. of Advocate(s)</th></tr>
									</thead>
										<?php
										 
									 
										
										$totalR = is_data_from_table('caveat_party'," caveat_no= $caveat_no AND pet_res='R' AND pflag='P'",'COUNT(caveat_no)','')['count'];
										
									 
										$allR = is_data_from_table('caveat_party'," caveat_no= $caveat_no AND pet_res='R' AND pflag='P'",'partyname,sr_no','A');
										$i=1;
										if(!empty($allR))
										{
											foreach($allR as $allR_row)
											{
												?>
											<tr><td><span id="adv_r_no_name<?php echo $i;?>"><?php echo $allR_row['sr_no'].'-'.$allR_row['partyname'];?></span></td>
												<?php if($i==1){?>
												<td rowspan="<?php echo $totalR;?>" style="vertical-align: middle;text-align: center">
													<input type="text" maxlength="2" size="2" id="r_adv_total" onkeypress="return onlynumbers(event,this.id)" value="0" class="form-control" /></td>
												<?php }?>
											</tr>
												<?php
												$i++;
											}
										}
									?>
									</table>
									<table border="1" style="border-collapse: collapse" align='center'>
										<tr><td colspan="2" style="border-left:0px;">&nbsp;</td></tr>
										<tr><td colspan="2" align="center">
											<input type="button" value="Display All" name="setPageBtn"/> <!--onclick="setPage()"-->
											<input type="button" value="New" onclick="window.location.reload()"/>
										</td></tr>
									</table> 
									<br>
									<table border="1" style="border-collapse: collapse_" align="center">
									<thead>
										<tr><th colspan="5">Already Inserted Record</th></tr>
										 
										<tr><th colspan="5">Caveator</th></tr>
										<tr><th>Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>--><th>Advocate Name</th></tr>
									</thead>
										 
										<?php   
										 
										 
										
										$rs = $CaveatModel->getCaveatAdvocateDetails($caveat_no,'P');
										if(!empty($rs))
										{
											foreach($rs as $row)
											{
												?>
												<tr <?php if($row['adv_type']=='M'){ echo "style=background-color: e2d8d3"; } ?>><td><?php echo $row['sr_no'].'-'.$row['partyname'];?></td>
													 
													<td><?php echo $row['name'].$row['adv']; ?></td>
												</tr>
												<?php
											}
										}else{?>
												<tr>
												<td colspan="100%">No data found...</td>
												 
											</tr>
										<?php 	}
										?>

									<thead>
										<tr><td colspan="5" style="border-left:0px;">&nbsp;</td></tr>
										<tr><th colspan="5">Caveatee</th></tr>    
										<tr><th>Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>--><th>Advocate Name</th></tr>
									</thead>	 
										<?php
										 
										 
										$rs = $CaveatModel->getCaveatAdvocateDetails($caveat_no,'R');
										if(!empty($rs))
										{
											foreach($rs as $row)
											{
												?>
											<tr <?php if($row['adv_type']=='M'){ echo "style=background-color: e2d8d3"; } ?>><td><?php echo $row['sr_no'].'-'.$row['partyname'];?></td>
												 
												<td><?php echo $row['name'].$row['adv']; ?></td>
											</tr>
												<?php
											}
										}else{?>
												<tr>
												<td colspan="100%">No data found...</td>
												 
											</tr>
										<?php 	}
										?>
									</table>   
							<?php
								}	

							  
										?>
								</div>
								<div id="div_show"></div>
							</div>
						</form>