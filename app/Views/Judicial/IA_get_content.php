<hr />
<div class="row_">
	<div class="col-12_">
		<input type="hidden" name="diaryno" id="diaryno" value="<?php echo $diary_no; ?>" />
		<center>
			<h3>Supreme Court of India</h3>
		</center>
		<?php

		$ucode = session()->get('login')['usercode'];
		$check_for_regular_case = "";
		if (!empty($dno_data)) {
			$sno = 0; ?>
			<center>
				<h4>
					<p><b class="pdiv">Diary No. - </b> <?= substr($dno_data['diary_no'], 0, -4) . ' - ' . substr($dno_data['diary_no'], -4); ?></p>
				</h4>
			</center>

			<?php
			$pet_name = $res_name = ""; 
			if (!empty($row_fl)) { ?>

				<?php if (!empty($result)) {
					//echo "<pre>";print_r($result);
					$grp_pet_res = '';
					$pet_name = $res_name = "";
					$temp_var = "";
					if (!empty($result)) {

						foreach ($result as $row) {
							$temp_var = "";
							$temp_var .= $row['partyname'];
							if ($row['sonof'] != '') {
								$temp_var .= $row['sonof'] . "/o " . $row['prfhname'];
							}
							if ($row['deptname'] != "") {
								$temp_var .= "<br>Department : " . $row['deptname'];
							}
							$temp_var .= "<br>";
							if ($row['addr1'] == '') {
								$temp_var .= $row['addr2'];
							} else {
								$temp_var .= $row['addr1'] . ', ' . $row['addr2'];
							}

							if (!empty($row['state']) && !empty($row['city'])) {
								$district = is_data_from_table('master.state', ['state_code' => $row['state'], 'district_code' => $row['city'], 'sub_dist_code' => 0, 'village_code' => 0, 'display' => 'Y'], 'name', 'R');
								if (!empty($district)) {
									if (!empty($district['name'])) {
										$temp_var .= ", District : " . $district['name'];
									}
								}
							}
							
							if ($row['pet_res'] == 'P') {
								$pet_name = $temp_var;
							} else {
								$res_name = $temp_var;
							}
							
						}
					}
				}
				
				
				//echo "mike".$temp_var;
				?>


			<?php }
			
			?>

			<div class="row">

				<div class="col-sm-12" style="text-align: left !important;">
					<h4>Case Details</h4>
					<?php $t_fil_no = get_case_nos($diary_no, '&nbsp;&nbsp;'); ?>
					<table class="table_tr_th_w_clr c_vertical_align" width="100%">
						<tbody>
							<tr>
								<td style="width: 15%">
									Case No.
								</td>
								<td><?php echo @$t_fil_no; ?></td>

							</tr>
							<tr>
								<td style="width: 15%">
									Petitioner
								</td>
								<td><?= @$pet_name; ?></td>
							</tr>
							<tr>
								<td style="width: 15%">
									Respondent
								</td>
								<td><?= @$res_name; ?></td>
							</tr>

							<tr>
								<td style="width: 15%">
									Case Category
								</td>
								<td>
									<?php
									$case_category = "";
									$mul_category = get_mul_category($dno_data['diary_no']);

									echo @$mul_category;
									?>
								</td>
							</tr>
							<tr>
								<td>
									Act
								</td>
								<td>
									<?php
									$act_section = '';
									if (!empty($act_main)) {

										foreach ($act_main as $row1) {
											if ($act_section == '')
												$act_section = $row1['act_name'] . '-' . $row1['section'];
											else
												$act_section = $act_section . ', ' . $row1['act_name'] . '-' . $row1['section'];
										}
									}
									echo @$act_section;
									?>
								</td>
							</tr>
							<tr>
								<td>
									Provision of Law
								</td>
								<td>
									<?php if(!$dno_data['actcode'] > 0 ){
										$pol = is_data_from_table('master.caselaw', "id = $dno_data[actcode]", 'law', $row = '');
										echo (!empty($pol)) ? $pol['law'] : '';
									}else{
										echo 'Not Found';
									}
									?>
								</td>
							</tr>
							<tr>
								<td style="width: 15%">
									Petitioner Advocate
								</td>
								<td>
									<?php
									$padvname = $radvname = "";
									//$sql_adv_p = "select pet_res_no,adv, advocate_id, pet_res from advocate where diary_no='" . $row_fl['diary_no'] . "' and display='Y' ORDER BY pet_res";
								
									// $sql_adv_p = is_data_from_table('advocate', "diary_no = $dno_data[diary_no] and display='Y' ORDER BY pet_res ", 'pet_res_no,adv, advocate_id, pet_res', $row = 'Q');
									$sql_adv_p = is_data_from_table(
										'advocate', 
										"diary_no = '" . $dno_data['diary_no'] . "' AND display = 'Y' ORDER BY pet_res", 
										'pet_res_no,adv, advocate_id, pet_res', 
										$row = 'A'
									); //pr($sql_adv_p);
									if(!empty($sql_adv_p)){
										foreach ($sql_adv_p as $row_advp) {
											$tmp_advname =  "<p>&nbsp;&nbsp;";
											$tmp_advname = $tmp_advname . get_advocates_new($row_advp['advocate_id'], '') . $row_advp['adv'];
											$tmp_advname = $tmp_advname . "</p>";
	
											if ($row_advp['pet_res'] == "P")
												$padvname .= $tmp_advname;
											if ($row_advp['pet_res'] == "R")
												$radvname .= $tmp_advname;
										}
									}else{
										$padvname = 'Not Found';
									}
									?>
									<?php echo $padvname; ?>
								</td>
							</tr>
							<tr>
								<td>
									Respondent Advocate
								</td>
								<td><?php echo $radvname; ?>
								</td>

							</tr>
							<tr>
								<td>
									Last Order
								</td>
								<td><?php echo $dno_data['lastorder']; ?></td>
							</tr>
							<?php
							if ($row_fl['c_status'] == 'P') {
								//$ttv = "SELECT tentative_cl_dt FROM heardt WHERE diary_no='".$row_fl['diary_no']."' "; 
								$r_ttv =  is_data_from_table('heardt', "diary_no = $dno_data[diary_no] ", 'tentative_cl_dt', $row = '');
								if (!empty($r_ttv)) {
									$result_array =  is_data_from_table('master.case_status_flag', " to_date IS NULL and  flag_name='tentative_listing_date' ", 'display_flag, always_allowed_users', $row = '');

									if ($result_array['display_flag'] == 1 || in_array(session()->get('login')['usercode'], explode(',', $result_array['always_allowed_users']))) {
							?>
										<tr>
											<td>
												Tentative Date
											</td>
											<td>
												<?php
												if(!empty($r_ttv['tentative_cl_dt']))
												{	
													if (get_display_status_with_date_differnces($r_ttv['tentative_cl_dt']) == 'T') {
														$tentative_date = $r_ttv['tentative_cl_dt'];
														echo change_date_format($tentative_date);
													}
												}	

												?>
											</td>
										</tr>
							<?php }
								}
							} ?>
							<tr>
								<td>
									Case Status
								</td>
								<td><?php if ($dno_data['c_status'] == 'D') {
										echo '<span class="badge badge-danger">Disposed</span>';
									} else {
										echo '<span class="badge badge-warning">Pending</span>';
									} ?></td>
							</tr>

						</tbody>
					</table>

				</div>

			</div>


		<?php } else { ?>
			<div class="alert alert-danger">
				<strong>Fail!</strong> No disposed IA(s) found.
			</div>
		<?php }



		$IAS = array();
		$gtNms = array();
		$one = $IArec;
		$doc_uy = '';
		$doc_uy1 = '';

		foreach ($one as $row1) {
			$key = $row1['doccode1'] . "^" . $row1['docdesc'] . ' ->Fee::' . $row1['docfee'];
			if ($doc_uy == '')
				$doc_uy = $key;
			else
				$doc_uy = $doc_uy . '^^' . $key;
		}

		?>
		<input type="hidden" name="hd_ias" id="hd_ias" value="<?php echo $doc_uy ?>" />
		<?php
		$two = $getPartyName;
		foreach ($two as $row) {
			$keys = $row['sr_no'] . "^" . $row['partyname'];
			if ($doc_uy1 == '')
				$doc_uy1 = $keys;
			else
				$doc_uy1 = $doc_uy1 . '^^' . $keys;
		}
		?>
		<input type="hidden" name="hd_gtNms" id="hd_gtNms" value="<?php echo $doc_uy1 ?>" />
		<br />

		<div class="row">
			<div class="col-md-12">
				<div style="text-align: center">
					<table class="table table-striped custom-table" width="100%" style="text-align: left;">
						<thead>
							<tr align="center">
								<th>
									Doc Num/Year
								</th>
								<th width="25%">
									<font>Interlocutory Application </font>
								</th>
								<th width="25%">
									Description
								</th>
								<th width="25%">
									Accused
								</th>
								<th>
									IASTAT
								</th>
								<th width="15%">
									Remark
								</th>
								<th>
									Update
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							//$rvz=  mysql_query("SELECT *  FROM `docdetails` WHERE `diary_no` = '$diary_no' and doccode='8' and display='Y' order by docyear desc, docnum desc");

							$sno_rvz = 1;
							// $va=  mysql_num_rows($rvz);
							if (!empty($docdetails)) {
								foreach ($docdetails as $row2)
								{
									//pr($docdetails);
							?>
									<tr>
										<?php if ($sno_rvz == 1) {
										?>
										<?php } ?>
										<td>
											<b> <?php echo $row2['docnum'] . '/' . $row2['docyear'] ?></b>
										</td>
										<td>
											<?php
											$chk = "";
											foreach ($one as $row1) {
												$key = $row1['doccode1'] . "^" . $row1['docdesc'] . ' ->Fee::' . $row1['docfee'];
												$k = explode("^", $key);
												if ($k[0] == $row2['doccode1']) {
													$chk = "ok";
													echo $k[1];
											?>
													<input type="hidden" name="m_doc1<?php echo $sno_rvz ?>"
														id="m_doc1<?php echo $sno_rvz ?>" value="<?php echo $row2['doccode1']; ?>" />
												<?php
												}
											}
											if ($chk == "") {
												?>
												<input type="hidden" name="m_doc1<?php echo $sno_rvz ?>" id="m_doc1<?php echo $sno_rvz ?>" value="<?php echo $row2['doccode1']; ?>" />
											<?php
											}
											?>

										</td>
										<?php if ($sno_rvz == 1) {
										?>
										<?php } ?>
										<td>
											<input type="hidden" id="m_descss<?php echo $sno_rvz ?>" name="m_descss<?php echo $sno_rvz ?>" size="15" maxlength="50" value="<?php echo $row2['other1'] ?>" />
											<?php
											echo $row2['other1'];
											?>
										</td>

										<?php if ($sno_rvz == 1) {
										?>
										<?php } ?>
										<td style="text-align: left">

											<?php
											$sd = 1;
											$sp_sel_nm = '';
											if (preg_match('/,/', $row2['party'])) {
												$partys =  explode(',', $row2['party']);
												for ($index = 0; $index < count($partys); $index++) {
											?>
													<table style="text-align: center">
														<tr>
															<td>
																<?php
																foreach ($two as $row) {
																	$key1 =  $row['sr_no'] . "^" . $row['partyname'];
																	$k = explode("^", $key1);
																	if ($partys[$index] == $k[0]) {
																		echo $k[0] . '-' . $k[1];
																		if ($sp_sel_nm == '')
																			$sp_sel_nm = $k[0] . '^' . $k[1];
																		else
																			$sp_sel_nm = $sp_sel_nm . '^^' . $k[0] . '^' . $k[1];
																	}
																}
																?>
															</td>
														</tr>
													</table>
											<?php
												}
											} else {
												foreach ($two as $row) {
													$key1 =  $row['sr_no'] . "^" . $row['partyname'];
													$k = explode("^", $key1);
													if ($row2['party'] == $k[0]) {

														echo $k[0] . '-' . $k[1];
														$sp_sel_nm = $k[0] . '^' . $k[1];
													}
												}
											}

											?>
											<input type="hidden" name="hd_sp_sel_nm<?php echo $sno_rvz ?>" id="hd_sp_sel_nm<?php echo $sno_rvz ?>" value="<?php echo  $sp_sel_nm; ?>" />
											<input type="hidden" name="hd_counts<?php echo $sno_rvz ?>" id="hd_counts<?php echo $sno_rvz ?>" value="<?php echo $row2['docnum'] ?>" />
											<input type="hidden" name="hd_year<?php echo $sno_rvz ?>" id="hd_year<?php echo $sno_rvz ?>" value="<?php echo $row2['docyear'] ?>" />
											<input type="hidden" name="hd_nature<?php echo $sno_rvz ?>" id="hd_nature<?php echo $sno_rvz ?>" value="<?php echo $row2['nature'] ?? '' ?>" />
											<input type="hidden" name="hd_IANAme<?php echo $sno_rvz ?>" id="hd_IANAme<?php echo $sno_rvz ?>" value="<?php echo $row2['doccode1'] ?>" />
											<input type="hidden" name="hd_ddate<?php echo $sno_rvz ?>" id="hd_ddate<?php echo $sno_rvz ?>" value="<?php echo $row2['dispose_date'] ?>" />
											<?php //echo " the disposal date is ".$row2['dispose_date'] 
											?>
										</td>
										<td align="center">
											<input type="hidden" name="ddlIASTAT<?php echo $sno_rvz ?>" id="ddlIASTAT<?php echo $sno_rvz ?>" value="<?php echo $row2['iastat'] ?>" />
											<?php
											echo $row2['iastat'];
											?>
										</td>
										<td>
											<input type="hidden" name="txtRematk<?php echo $sno_rvz ?>" id="txtRematk<?php echo $sno_rvz ?>" value="<?php echo $row2['remark'] ?>" />
											<?php
											echo $row2['remark'];
											?>
										</td>

										<td align="center">
											<?php if (($row2['iastat'] == 'D' || $ucode == 1) && $row_fl['c_status'] == 'P') { ?>
												<button type="button" onclick="editrecord('<?php echo $sno_rvz ?>')" id="sp_edit<?php echo $sno_rvz ?>" class="btn btn-primary btn-sm">Edit</button>

											<?php } else {
												echo "<font color='red'><strike>Edit</strike></font>";
											}
											?>
										</td>

									</tr>
								<?php
									$sno_rvz++;
								}
								?>
								<input type="hidden" name="hd_sno_rvz" id="hd_sno_rvz" value="<?php echo $sno_rvz ?>" />
							<?php
							} else {
							?>
								<tr>
									<td colspan="8">
										<div style="text-align: center"><b>No Record Found</b></div>
									</td>
								</tr>
							<?php
							}
							?>
						</tbody>

					</table>
				</div>
			</div>
		</div>

		<div id="dv_sh_hd" class="card" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
			&nbsp;
		</div>
		<div id="dv_fixedFor" class="card-body" style="text-align: center;position: fixed;top:0;display: none;
			left:0px;
			width:70%;
			height:70%;z-index: 105;">
			<div style="background-color: white;padding-left: 40px;padding-top: 10px;padding-bottom: 40px;padding-right: 40px;overflow: scroll;height: 400px;" id="sp_mnb">
			</div><br /><br />
		</div>


	</div>
</div>