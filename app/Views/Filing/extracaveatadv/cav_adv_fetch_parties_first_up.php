<form method="post" action="#">
	<div id="dv_content1">

		<div id="div_result">
			<?php $ucode = $_SESSION['login']['usercode'];
			//	pr($caveat_no);
			$main_row = $CaveatModel->getCaveatDetails($caveat_no);
			// pr($main_row);
			if (empty($main_row)) {
			?>
				<table align="center">
					<tr align="center">
						<th>Record Not Found!!!</th>
					</tr>
				</table>
			<?php } else {
				$pnc = explode(',', $main_row['q']);
			?>
				<div style="text-align:center;font-size: 15px;color: #ff5d4c;font-weight: bold;display: none" id="suc_msg">Record Updated Successfully!!!</div>
				<div style="color:blue;text-align: center;font-weight: bold">
					<?php

					$casetype = $CaveatModel->getCaveatPartyDetails($caveat_no);

					echo "Diary No: " . session()->get('filing_details')['diary_number'] . '/' . session()->get('filing_details')['diary_year'];
					echo "<br><span style=color:black>Caveator:</span> " . $casetype['pet_name'];
					if ($casetype['pno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
					else if ($casetype['pno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
					echo "&nbsp; &nbsp;";
					echo "<span style=color:black>Caveatee:</span> " . $casetype['res_name'];
					if ($casetype['rno'] == 2) echo " <span style='color:#72bcd4'>AND ANR</span>";
					else if ($casetype['rno'] > 2) echo " <span style='color:#72bcd4'>AND ORS</span>";
					?>
				</div>

				<table border="1" style="border-collapse: collapse" align="center">

					<tr>
						<th colspan="11">Caveator</th>
					</tr>
					<tr>
						<th>Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
						<th>Category</th>
						<th>AOR Code</th>
						<th>Advocate Name</th>
						<th>Mobile</th>
						<th>Email</th>
						<th>Type</th>
						<th>If [AG]</th>
						<th>STATE ADV[Pri/Gov]</th>
						<th></th>
					</tr>
					<?php

					$i = 1;


					$rs = $CaveatModel->getCaveatAdvocateDetails($caveat_no, 'P');

					if (!empty($rs)) {
						foreach ($rs as $row) {
							// pr($row);
					?>
							<input type="hidden" id="adv_pet_res<?php echo $i ?>" value="P" />
							<input type="hidden" value="<?php if ($row['pet_res_no'] != '') echo $row['pet_res_no'];
														else echo '0'; ?>" id="adv_p_no_hd<?php echo $i; ?>" />
							<tr id="row<?php echo $i; ?>" <?php if ($row['adv_type'] == 'M') {
																echo "style=background-color: e2d8d3";
															} ?>>
								<td style="border:none">
									<select class="form-control" id="adv_p_no<?php echo $i; ?>" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php }
																																							if ($row['adv_type'] == 'M') echo "disabled"; ?> disabled="">
										<option value="0" <?php if ($row['pet_res_no'] == '') { ?> selected <?php } ?>>0</option>
										<?php
										$type = explode('-', $pnc[0]);
										if ($type[1] == 'P') {
											for ($j = 1; $j <= $type[0]; $j++) {
										?>
												<option value="<?php echo $j ?>" <?php if ($j == $row['pet_res_no']) { ?> selected <?php } ?>><?php echo $j ?></option>
												<?php
											}
										} else {
											$type = explode('-', $pnc[1]);
											if ($type[1] == 'P') {
												for ($j = 1; $j <= $type[0]; $j++) {
												?>
													<option value="<?php echo $j ?>" <?php if ($j == $row['pet_res_no']) { ?> selected <?php } ?>><?php echo $j ?></option>
										<?php
												}
											}
										}
										?>
									</select><span><?php echo '-' . $row['partyname']; ?></span>
								</td>

								<td><select disabled="" class="form-control">
										<option <?php if ($row['adv_type'] == 'M') echo "selected"; ?>>Main</option>
										<option <?php if ($row['adv_type'] == 'A') echo "selected"; ?>>Additional</option>
									</select>
									<input type="hidden" value="<?php echo $row['adv_type']; ?>" id="adv_type_hd<?php echo $i; ?>" />
								</td>
								<td style="border:none"><input type="text" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> value="<?php echo $row['aor_code'] ?>" maxlength="6" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
									<input type="hidden" value="<?php echo $row['aor_code'] ?>" id="adv_aor_hd<?php echo $i; ?>" />
								</td>
								<td style="border:none"><input type="hidden" value="<?php echo $row['name'] . $row['adv'] ?>" id="adv_name_hd<?php echo $i; ?>" />
									<?php
									$write = 'N';

									/* if($row['adv_code']=='9999' && $row['adv_cd_yr']=='2014')
													$write='Y'; */
									if ($row['advocate_id'] != 0) {
									?>
										<span id="adv_name<?php echo $i; ?>" <?php if ($write == 'Y') echo "style='display:none'"; ?>><?php echo $row['name'] . $row['adv']; ?></span>
										<input type="text" id="adv_name_write<?php echo $i; ?>" style="display:<?php if ($write == 'Y') echo 'block';
																												else if ($write == 'N') echo 'none'; ?>;text-transform:uppercase;width:200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['adv'] ?>" />
								</td>
							<?php } else { ?>
								<span id="adv_name<?php echo $i; ?>" style='display:none'><?php echo $row['name'] . $row['adv']; ?></span>
								<input type="text" id="adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: block; text-transform: uppercase;width: 200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" /></td>
							<?php } ?>
							<td style="border:none"><input <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> type="text" id="adv_mob<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10" value="<?php echo $row['mobile'] ?>" <?php if ($write == 'Y') echo "style='display:none'" ?> disabled="" /></td>
							<td style="border:none"><input type="text" size="10" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> id="adv_email<?php echo $i; ?>" value="<?php echo $row['email'] ?>" <?php if ($write == 'Y') echo "style='display:none'" ?> disabled="" /></td>
							<?php
							$having_ag = 0;
							if ($row['advocate_id'] == 0) {
								$type = !empty($row['adv']) ? explode('[', rtrim($row['adv'], ']')) : '';
							} else {
								$type = [];
								if (!empty($row['adv']) && strpos($row['adv'], '[') !== false) {
									$type = explode('[', rtrim($row['adv'], ']'));
								}

								if (!empty($type)) {
									foreach ($type as $key => $value) {
										if ($value == 'AG') {
											$having_ag = 1;
										}
									}

									if ($row['pet_res_no'] != 0) {
										$type[1] = $type[2] ?? '';
									}

									if (!empty($type[2]) && $type[2] == 'LR/S') {
										$type[1] = $type[2];
									}
								}
							}
							?>
							<td style="border:none">
								<select class="form-control" id="adv_type<?php echo $i; ?>" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> disabled="">
									<option value='N' <?php if (isset($type[1]) && $type[1] == 'N') { ?>selected <?php } ?>>None</option>
									<!--<option value='OBJ' <?php if (isset($type[1]) && $type[1] == 'OBJ') { ?>selected <?php } ?>>OBJ</option>-->
									<option value='SURITY' <?php if (isset($type[1]) && $type[1] == 'SURETY') { ?>selected <?php } ?>>SURETY</option>
									<option value='INT' <?php if (isset($type[1]) && $type[1] == 'INT') { ?>selected <?php } ?>>INTERVENOR</option>
									<option value='LR/S' <?php if (isset($type[1]) && $type[1] == 'LR/S') { ?>selected <?php } ?>>LR/S</option>
									<option value='AMICUS CURIAE' <?php if (isset($type[1]) && $type[1] == 'AMICUS CURIAE') { ?>selected <?php } ?>>AMICUS CURIAE</option>
									<option value='DRW' <?php if (isset($type[1]) && $type[1] == 'DRW') { ?>selected <?php } ?>>DRAWNBY</option>
								</select>
							</td>
							<td style="border:none"><select class="form-control" id='ifag<?php echo $i; ?>' <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
									<option value='N' <?php if ($having_ag == 0) echo "selected"; ?>>No</option>
									<option value='AG' <?php if ($having_ag == 1) echo "selected"; ?>>ATTORNY GENERAL</option>
								</select></td>
							<td style="border:none"><select class="form-control" id='statepg<?php echo $i; ?>'>
									<option value='N' <?php if ($row['stateadv'] == 'N') echo "selected"; ?>>No</option>
									<option value='P' <?php if ($row['stateadv'] == 'P') echo "selected"; ?>>Private</option>
									<option value='G' <?php if ($row['stateadv'] == 'G') echo "selected"; ?>>Government</option>
								</select>
								<input type='hidden' value='<?php echo $row['stateadv']; ?>' id='statepg_hd<?php echo $i; ?>' />
							</td>
							<td style="border:none"><input type="button" name="button_delete_<?php echo $i; ?>" style="background-color: #ffcccc;display: block;color:#ff351c" value="Delete" /></td>

							</tr>
					<?php
							$i++;
						}
					}
					?>
					</tbody>
				</table>
				<table>
					<tbody>
						<tr>
							<td colspan="11" style="border-left:0px;">&nbsp;</td>
						</tr>
						<tr>
							<th colspan="11">Caveatee</th>
						</tr>
						<tr>
							<th>Name</th><!--<th>State</th><th>Enroll No.</th><th>Enroll Year</th>-->
							<th>Category</th>
							<th>AOR Code</th>
							<th>Advocate Name</th>
							<th>Mobile</th>
							<th>Email</th>
							<th>Type</th>
							<th>If [AG]</th>
							<th>STATE ADV[Pri/Gov]</th>
							<th></th>
						</tr>
						<?php

						$rs = $CaveatModel->getCaveatAdvocateDetails($caveat_no, 'R');
						if (!empty($rs)) {
							foreach ($rs as $row) {
						?>
								<input type="hidden" id="adv_pet_res<?php echo $i ?>" value="R" />
								<input type="hidden" value="<?php if ($row['pet_res_no'] != '') echo $row['pet_res_no'];
															else echo '0'; ?>" id="adv_p_no_hd<?php echo $i; ?>" />
								<tr id="row<?php echo $i; ?>">
									<td style="border:none">

										<select class="form-control" id="adv_p_no<?php echo $i; ?>" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php }
																																								if ($row['adv_type'] == 'M') echo "disabled"; ?> disabled="">
											<option value="0" <?php if ($row['pet_res_no'] == '') { ?> selected <?php } ?>>0</option>
											<?php
											$type = explode('-', $pnc[1]);
											if ($type[1] == 'R') {

												for ($j = 1; $j <= $type[0]; $j++) {
											?>
													<option value="<?php echo $j ?>" <?php if ($j == $row['pet_res_no']) { ?> selected <?php } ?>><?php echo $j ?></option>
													<?php

												}
											} else {
												$type = explode('-', $pnc[2]);
												if ($type[1] == 'R') {
													for ($j = 1; $j <= $type[0]; $j++) {
													?>
														<option value="<?php echo $j ?>" <?php if ($j == $row['pet_res_no']) { ?> selected <?php } ?>><?php echo $j ?></option>
											<?php
													}
												}
											}
											?>
										</select><span><?php echo '-' . $row['partyname']; ?></span>
									</td>

									<td><select class="form-control" disabled="">
											<option <?php if ($row['adv_type'] == 'M') echo "selected"; ?>>Main</option>
											<option <?php if ($row['adv_type'] == 'A') echo "selected"; ?>>Additional</option>
										</select>
										<input type="hidden" value="<?php echo $row['adv_type']; ?>" id="adv_type_hd<?php echo $i; ?>" />
									</td>
									<td style="border:none"><input type="text" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> value="<?php echo $row['aor_code'] ?>" maxlength="6" size="4" id="adv_aor<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" onblur="getAdvocateAOR(<?php echo $i; ?>)" />
										<input type="hidden" value="<?php echo $row['aor_code'] ?>" id="adv_aor_hd<?php echo $i; ?>" />
									</td>
									<td style="border:none"><input type="hidden" value="<?php echo $row['name'] . $row['adv'] ?>" id="adv_name_hd<?php echo $i; ?>" />
										<?php
										$write = 'N';
										/* if($row['adv_code']=='9999' && $row['adv_cd_yr']=='2014')
													$write='Y'; */
										if ($row['advocate_id'] != 0) {
										?>
											<span id="adv_name<?php echo $i; ?>" <?php if ($write == 'Y') echo "style='display:none'" ?>><?php echo $row['name'] . $row['adv'] ?></span>
											<input type="text" id="adv_name_write<?php echo $i; ?>" style="display:<?php if ($write == 'Y') echo 'block';
																													else if ($write == 'N') echo 'none'; ?>;text-transform:uppercase;width:200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" />
									</td>
								<?php } else { ?>
									<span id="adv_name<?php echo $i; ?>" style='display:none'><?php echo $row['name'] . $row['adv']; ?></span>
									<input type="text" id="adv_name_write<?php echo $i; ?>" onkeypress="return advName(event)" style="display: block; text-transform: uppercase;width: 200px;" onblur="copyToSpan(<?php echo $i; ?>)" value="<?php echo $row['name'] . $row['adv'] ?>" /></td>
								<?php } ?>
								<td style="border:none"><input type="text" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> id="adv_mob<?php echo $i; ?>" onkeypress="return onlynumbers(event,this.id)" maxlength="10" size="10" value="<?php echo $row['mobile'] ?>" <?php if ($write == 'Y') echo "style='display:none'" ?> disabled="" /></td>
								<td style="border:none"><input type="text" size="10" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> id="adv_email<?php echo $i; ?>" value="<?php echo $row['email'] ?>" <?php if ($write == 'Y') echo "style='display:none'" ?> disabled="" /></td>
								<?php
								$having_ag = 0;
								if ($row['pet_res_no'] == 0) {
									$type = explode('[', rtrim($row['adv'], ']'));
								} else {
									$type = explode('[', rtrim($row['adv'], ']'));
									//print_r($type);
									for ($kk = 0; $kk < sizeof($type); $kk++) {
										if ($type[$kk] == 'AG')
											$having_ag = 1;
									}
									if ($row['pet_res_no'] != 0)
										$type[1] = $type[2] ?? '';

									if (!empty($type[2]) && $type[2] == 'LR/S')
										$type[1] = $type[2];
								}

								?>
								<td style="border:none"><select class="form-control" id="adv_type<?php echo $i; ?>" <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?> disabled="">
										<option value='N' <?php if ($type[1] == 'N') { ?>selected <?php } ?>>None</option>
										<option value='OBJ' <?php if ($type[1] == 'OBJ') { ?>selected <?php } ?>>OBJECTOR</option>
										<option value='SURETY' <?php if ($type[1] == 'SURETY') { ?>selected <?php } ?>>SURETY</option>
										<option value='INT' <?php if ($type[1] == 'INT') { ?>selected <?php } ?>>INTERVENOR</option>
										<option value='IMPL' <?php if ($type[1] == 'IMPL') { ?>selected <?php } ?>>IMPLEADER</option>
										<option value='COMP' <?php if ($type[1] == 'COMP') { ?>selected <?php } ?>>COMPLAINANT</option>
										<option value='DRW' <?php if ($type[1] == 'DRW') { ?>selected <?php } ?>>DRAWNBY</option>
										<option value='LR/S' <?php if ($type[1] == 'LR/S') { ?>selected <?php } ?>>LR/S</option>
									</select></td>
								<td style="border:none"><select class="form-control" id='ifag<?php echo $i; ?>' <?php if ($row['advocate_id'] == 0) { ?> style="display: none" <?php } ?>>
										<option value='N' <?php if ($having_ag == 0) echo "selected"; ?>>No</option>
										<option value='AG' <?php if ($having_ag == 1) echo "selected"; ?>>ATTORNY GENERAL</option>
									</select></td>
								<td style="border:none"><select class="form-control" id='statepg<?php echo $i; ?>'>
										<option value='N' <?php if ($row['stateadv'] == 'N') echo "selected"; ?>>No</option>
										<option value='P' <?php if ($row['stateadv'] == 'P') echo "selected"; ?>>Private</option>
										<option value='G' <?php if ($row['stateadv'] == 'G') echo "selected"; ?>>Government</option>
									</select>
									<input type='hidden' value='<?php echo $row['stateadv']; ?>' id='statepg_hd<?php echo $i; ?>' />
								</td>
								<td style="border:none"><input type="button" name="button_delete_<?php echo $i; ?>" style="background-color: #ffcccc;display: block;color:#ff351c" value="Delete" /></td>

								</tr>
						<?php
								$i++;
							}
						} ?>
						<tr>
							<td colspan="11" style="border-left:0px;">&nbsp;</td>
						</tr>
						<input type="hidden" value="<?php echo $i; ?>" id="all" />

					</tbody>
				</table>
				<table>
					<tbody>
						<tr>
							<td colspan="11" align="center">
								<input type="button" name="updatebutton" value="Update" <?php if ($main_row['c_status'] == 'D') { ?>disabled<?php } ?> /><!--onclick="saveAdv()"-->
								<input type="button" onclick="window.location.reload()" value="Cancel" />
							</td>
						</tr>

					</tbody>
				</table>
			<?php
			}


			?>
		</div>
		<div id="result1"></div>
	</div>
</form>