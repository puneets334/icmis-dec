
						<form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>
						<?php //include ('../mn_sub_menu.php'); ?>
							<div id="dv_content1">
								<div id="result1">
										
								<?php	$hdfil = $diary_no;
									 
								$fil_no_diary = " caveat_no=$diary_no ";
								 
								//$main_q = "SELECT pet_name,res_name FROM caveat WHERE $fil_no_diary ";
								//$main_rs = mysql_query($main_q);
								
								$main_row = is_data_from_table('caveat'," caveat_no= $diary_no",'pet_name,res_name','');
								if(!empty($main_row))
								{									 
								 
									?>
									<input type='hidden' value="<?php echo $hdfil;?>" id='hdfno'>
									<table align="center" width="100%">
										<tr align="center" style="color:blue"><th><?php echo "Caveat No. ".session()->get('filing_details')['diary_number'].'/'.session()->get('filing_details')['diary_year']; ?></th></tr>
										<tr align="center" style="color:blue"><th><b><?php 
										echo '<span style=color:black>Caveator:</span> '.$main_row['pet_name']; 
										 ?>
									 
										&nbsp;
										<?php echo '<span style=color:black>Caveatee:</span> '.$main_row['res_name']; 
										?></b></th></tr>
										<?php 
										 
										?>
									</table>

									<table width="100%">
										<tr>
											<td valign="top" width="50%">
												<table border="1" style="border-collapse: collapse" width="30%">
													<tr>
														<td style="border:none">Cavt/Cate:</td>
														<td style="border:none">
															<select class="form-control" id="party_flag" ><!--onchange="getParty_status(this.value);"-->
																<option value="">Select</option>
																<option value="P">Caveator</option><option value="R">Caveatee</option>
																<!--<option value="I">Impleading</option>-->
																<!--<option value="N">INTERVENER</option>-->
															</select> Party No:<span id="pno"></span>
															<input type="hidden" id="hd_party_flag"/>
														</td>
														<td style="border:none">Individual/Dept:</td><td style="border:none">
															<select class="form-control" id="party_type" onchange="activate_extra(this.value)">
															<option value="I">Individual</option>
															<option value="D1">State Department</option>
															<option value="D2">Central Department</option>
															<option value="D3">Other Organization</option>
														</select></td></tr>
														<tr id="for_I_1"><td style="border:none">Name:</td><td style="border:none"><input class="form-control" type="text" style="width:200px;" id="p_name" onkeypress="return onlyalphab(event)"/></td>
															<td style="border:none">Relation: </td><td style="border:none"><select class="form-control" id="p_rel" style="width:142px;">
															<option value="">Select</option>
															<option value="S" >Son of</option>
															<option value="D" >Daughter of</option>
															<option value="W" >Wife of</option>
															<option value="F" >Father of</option>
															<option value="M" >Mother of</option>
														</select></td></tr>
														<tr id="for_I_2"><td style="border:none">Father/Husb. Name:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="p_rel_name" onkeypress="return onlyalphab(event)"/></td>
															<td style="border:none">Gender: </td><td style="border:none"><select class="form-control" id="p_sex"><option value="">Select</option>
															<option value="M" >Male</option>
															<option value="F" >Female</option>
															<option value="N" >N.A.</option></select></td></tr>
														<tr id="for_I_3"><td style="border:none">Age: </td><td style="border:none"><input class="form-control" maxlength="3" style="width:200px;" type="text" id="p_age" onkeypress="return onlynumbers(event)"/></td>
															<td style="border:none">Caste:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="p_caste" /></td></tr>
													<tr style="display:none" id="tr_d0">
														<td style="border:none">State Name:</td><td style="border:none"> 
														<input class="form-control" type="text" id="p_statename" style="width:200px;" onkeypress="return onlyalpha(event)" />
														<input type="hidden" id="p_statename_hd"/>   
														</td>
													</tr>
													<tr style="display:none" id="tr_d">
														<td style="border:none">Department:</td><td style="border:none"> 
															<input class="form-control" type="text" id="p_deptt" style="width:200px;" onkeypress="return onlyalphabnum(event)" />
															<input type="hidden" id="p_deptt_hd"/>   
														<td style="border:none">Post:</td>
															<td style="border:none"> 
															<input class="form-control" type="text" id="p_post" style="width:200px;" onkeypress="return onlyalphabnum(event)"/> <!-- onblur="get_a_d_code(this.id)"-->
															<input type="hidden" id="post_code"/></td>

															</td>
													</tr>
													 
													<tr id="for_I_4"><td style="border:none">Occupation: </td><td style="border:none"><input class="form-control" onkeypress="return onlyalphab(event)" type="text" id="p_occ" style="width:200px;"/>
															<input type="hidden" id="p_occ_hd_code"/></td>
														<td style="border:none">Education/Qualification:  </td><td style="border:none"><input class="form-control" onkeypress="return onlyalphab(event)" type="text" id="p_edu" style="width:200px;"/>
															<input type="hidden" id="p_edu_hd_code"/></td></tr>
													<tr><td style="border:none">Address:</td><td style="border:none"> <input style="width:200px;" class="form-control" type="text" id="p_add" /></td>
														<td style="border:none">Tehsil/Place/City: </td><td style="border:none"><input type="text" class="form-control" id="p_city" style="width:200px;"/></td></tr>
													<tr><td style="border:none">Country:</td><td style="border:none">
															<?php
															/* $country = "SELECT country_name,id FROM country WHERE display='Y' ORDER BY country_name";
															$country = mysql_query($country) or die(__LINE__.'->'.mysql_error());
															 */
															$country = is_data_from_table('master.country'," display='Y' ORDER BY country_name",'country_name,id','A');
															 
															?>
															<select class="form-control" id="p_cont" style="width:200px;" >
															<?php 
															foreach($country as $country_row){
																?>
																<option value="<?php echo $country_row['id']; ?>" <?php if($country_row['id']=='96') echo "Selected"; ?>><?php echo $country_row['country_name']; ?></option>
																	<?php
															}
															?>  
															</select>
														</td></tr>
													<tr><td style="border:none">State:</td><td style="border:none"> 
															<select class="form-control" id="p_st" style="width:200px;" onchange="getDistrict(this.value)">
																<option value="">Select</option>
														<?php 
														/* $st_q = "SELECT id_no State_code, Name
																		FROM `state`
																		WHERE District_code =0
																		AND Sub_Dist_code =0
																		AND Village_code =0
																		AND display = 'Y'
																		AND State_code < 100
																		ORDER BY Name";
														$st_rs = mysql_query($st_q) or die(__LINE__.'->'.mysql_error());
														 */
														$st_rs = is_data_from_table('master.state'," district_code =0
																		AND sub_dist_code =0
																		AND village_code =0
																		AND display = 'Y'
																		AND state_code < 100
																		ORDER BY name ",'id_no, state_code, name','A');
														 
														foreach($st_rs as $st_row)
														{
															?>
															<option value="<?php echo $st_row['state_code']?>"><?php echo $st_row['name']?></option>    
															<?php 
														}
														?>
														</select></td>
														<td style="border:none">District:</td><td style="border:none"> <select class="form-control" id="p_dis" style="width:200px;"><option value="">Select</option>
															<?php 
															 
															?>
													</select></td>
													</tr>
													<tr><td style="border:none">Pin:</td><td style="border:none"> <input class="form-control" maxlength="6" type="text" style="width:200px;" id="p_pin" onkeypress="return onlynumbers(event)"/></td>
														<td style="border:none">Phone/Mobile:</td><td style="border:none"> <input class="form-control" style="width:200px;" type="text" id="p_mob" maxlength="10" onkeypress="return onlynumbers(event)"/></td></tr>
													<tr><td style="border:none">Email Id:</td><td style="border:none"> <input class="form-control" type="text" id="p_email" style="width:200px;"/></td>
														 
													</tr>
													 <tr><td colspan="4" align="center" >
														<?php 
														$disabled = '';
														 if(!empty($main_row['c_status']) && $main_row['c_status']=='D'){
																$disabled = 1;
															}
														
														if($disabled==1){ ?>
															<input type="button" value="Save" disabled/>
														<?php }else{ ?>
															<input type="button" value="Save" onclick="call_save_extra()" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()"/>
															<input type="button" value="Reset/New" onclick="call_fullReset_extra('F')" id="rstbtn" onkeydown="if (event.keyCode == 13) document.getElementById('rstbtn').click()"/>
															 
														<?php }?>
														</td></tr>
												</table>
												</td>
											<td valign="top" width="50%">
												<table width="100%" border="1" style="border-collapse: collapse" id="table_show">
													<th colspan="2">Caveator Parties</th>
													<?php 
													 
													/* $p_pet_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[dno] AND pet_res='P' AND pflag='P' ORDER BY sr_no";	
													$p_pet_rs = mysql_query($p_pet_q) or die(__LINE__.'->'.mysql_error());
													 */
													$result = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='P' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no','A');
													//pr($p_pet_rs);
													if(!empty($result))
													{
														foreach($result as $p_pet_row)
														{
														?>
														<tr><td><?php echo $p_pet_row['sr_no']; ?></td><td><?php echo $p_pet_row['partyname']; 
														 
														?></td></tr>
														<?php 
														}
													}
													?>
													<th colspan="2">Caveatee Parties</th>    
													<?php 
													 
													/* $p_res_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[dno] AND pet_res='R' AND pflag='P' ORDER BY sr_no";
													$p_res_rs = mysql_query($p_res_q) or die(__LINE__.'->'.mysql_error());
													 */
													$result = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='R' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no','A');
													if(!empty($result))
													{
														foreach($result as $r_res_rs)
														{ 
														?>
														<tr><td><?php echo $r_res_rs['sr_no']?></td><td><?php echo $r_res_rs['partyname'];
														 
														?></td></tr>
														<?php 
														}
													}
													?>
													 
													 
												</table>
											</td>
										</tr>
									</table>
								<?php 
								}
									else
									{?>
									<table align="center"><tr><th style="color:red">Record Not Found!!!</th></tr></table>
									<?php     
									} ?>
								</div>
								<div id="result2" style="text-align: center;color:green;font-size: larger"></div>
							</div>
						</form>