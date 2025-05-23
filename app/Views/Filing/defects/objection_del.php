<?= view('header'); ?>

<style>
	.custom-radio {
		float: left;
		display: inline-block;
		margin-left: 10px;
	}

	.custom_action_menu {
		float: left;
		display: inline-block;
		margin-left: 10px;
	}

	.basic_heading {
		text-align: center;
		color: #31B0D5
	}

	.btn-sm {
		padding: 0px 8px;
		font-size: 14px;
	}

	.card-header {
		padding: 5px;
	}

	h4 {
		line-height: 0px;
	}
</style>
<link href="<?php echo base_url(); ?>/css/jquery-ui.css" rel="stylesheet">
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header heading">

						<div class="row">
							<div class="col-sm-10">
								<h3 class="card-title">Filing >> Defects Delete</h3>
							</div>

							<?= view('Filing/filing_filter_buttons'); ?>
						</div>
					</div>




					<script type="text/javascript" src="<?php echo base_url(); ?>/filing/objection_del.js"></script>
					<style type="text/css">
						#sp_amo {
							cursor: pointer;
							color: blue;
						}

						#sp_amo:hover {
							text-decoration: underline
						}
					</style>


					<form method="post" action="#">
						<div id="dv_content1">

							<div id="div_result">
								<?php

								$sql_res = '0';
								//$sql_jk = mysql_query("SELECT rm_dt,status   FROM obj_save WHERE diary_no = '$dairy_no'  and display='Y'") or die("Error: ".__LINE__.mysql_error());
								$sql_jk = $dModel->check_old_defect($diary_no);
								if (!empty($sql_jk)) {

									foreach ($sql_jk as $row3) {
										if ($row3['rm_dt'] != '0000-00-00 00:00:00' || $row3['status'] != '0') {
											$sql_res = 1;
										}
										
									}
								} else {

									$sql_res = 2;
								}
								if ($sql_res == '1') {
								?>
									<div style="text-align: center;color:red;"><b>Matter already refiled !!</b></div>
								<?php
								} else if ($sql_res == '2') {
								?>
									<div style="text-align: center;color:red;"><b>Diary No. Not Found !!</b></div>
								<?php
								} else if ($sql_res == '0') 
								{
								$sql_q= $dModel->getCasedetails($diary_no);

								if (!empty($sql_q) && is_array($sql_q))
								{
								?>
								
								<fieldset id="fd_md">
									<legend><b>Case Details</b></legend>
									<?php
										$cause_title = $sql_q['cause_title'] ?? '';
										$result_dt = $sql_q['dt'] ?? '';
										$case_status = $sql_q['c_status'] ?? '';
										$casetype_id = $sql_q['casetype_id'] ?? '';
										$cicri = $row['case_grp'] ?? '';
									
									?>
									<input type="hidden" name="hd_ci_cri" id="hd_ci_cri" value="<?php echo esc($cicri); ?>"/>
									<table width="100%" class="table_tr_th_w_clr c_vertical_align">
										<tr>
											<td style="width: 10%">
												<b> Cause Title </b>
											</td>
											<td style="width: 15%">
												<?php echo esc($cause_title); ?>
											</td>
											<td style="width: 8%">
												<b> Filing date </b>
											</td>
											<td style="width: 10%">
												<?php echo esc($result_dt); ?>
											</td>
											<td style="width: 8%">
												<b> Case Status </b>
											</td>
											<td style="width: 10%">
												<?php echo esc($case_status); ?>
											</td>
										</tr>
									</table>
								</fieldset>
								<?php
							if ($case_status == 'Disposed') {
								?><div style="text-align: center;color:red;"><b> Matter is Disposed!! </b></div>

							<?php exit();
							}

							$listing = $dModel->getListingdiarydetails($diary_no);
							if($listing > 0) { ?>
								<div style="text-align: center;color:red;"><b> 
									 CASE IS LISTED. Please Contact Listing Branch before making any updation!! </b></div>
							<?php exit();
							}

							$check_section_rs = $dModel->getUserDetails($ucode);

							if (!empty($check_section_rs) && $ucode != 1) {
								$check_section_user = $check_section_rs;

								if (($check_section_user['section'] ?? null) != 19 && ($check_section_user['usertype'] ?? null) != 4) {
									if (
										($check_section_user['usertype'] ?? null) == 4 ||
										($check_section_user['usertype'] ?? null) == 6
									) {
										$casetype = ['9', '10', '19', '20', '25', '26', '39'];
										if (!in_array($casetype_id, $casetype)) {
											echo '<div style="text-align: center"><font color="red">Defects can be deleted only in RP/CUR.P/CONT.P./MA</font></div>';
											exit();
										} else if (in_array($casetype_id, $casetype)) {
											echo "<input type='hidden' name='hd_flag' id='hd_flag' value='1'/>";
										}
									} else {
										echo '<div style="text-align: center"><h3><font color="red">Only DR/Addl. Reg is authorized for deletion of defects</font></h3></div>';
										exit();
									}
								} elseif (($check_section_user['section'] ?? null) == 19 && ($check_section_user['usertype'] ?? null) != 4) {
									echo '<div style="text-align: center"><h3><font color="red">Only Addl. Reg is authorized for deletion of defects!!</font></h3></div>';
									exit();
								}
							}
				
						}
						?>

									<fieldset id="fiOD">
										<legend><b>Defaults Added</b></legend>

										<span id="spAddObj" style="font-size: small;text-transform: uppercase">
											<table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" width="100%">
												<?php
												$sno = 1;
												$cn_c = '';
												/* $q_w = mysql_query("SELECT a.id,objdesc obj_name, rm_dt,remark,mul_ent FROM obj_save a, objection b WHERE a.org_id = b.objcode 
												and diary_no = '$dairy_no'  and  rm_dt='0000-00-00 00:00:00' and a.display='Y' order by id") or die("Error: ".__LINE__.mysql_error());
 */
												$q_w = $dModel->getObjectionDetails($diary_no);


												//$chk_num_row = mysql_num_rows($q_w);
												?>
												<input type="hidden" name="hdChk_num_row" id="hdChk_num_row" value="<?php if (!empty($q_w)) {
																														echo count($q_w);
																													} else {
																														echo '0';
																													}; ?>" />
												<?php
												if (!empty($q_w)) {

													foreach ($q_w as $row1) {
												?>
														<tr>
															<td>
																<input id="hd_id<?php echo $sno; ?>" type="hidden" value="<?php echo $row1['id']; ?>" />

																<span id="spAddObjjjj<?php echo $sno; ?>">

																	<b>(<?php echo $sno; ?>)</b>
																</span>
															</td>
															<!--                <td>
													<input id="chkbox_obj<?php echo $sno; ?>" type="checkbox" <?php //if($row1['rm_dt']!='0000-00-00 00:00:00') { 
																												?> disabled="true" <?php //} 
																																														?>/>
												   </td>-->
															<td>
																<span id="spAddObj<?php echo $sno; ?>"><?php echo $row1['obj_name']; ?></span>


															</td>
															<td>
																<!--             <input type="text" name="sp_remark<?php //echo $sno; 
																													?>" id="sp_remark<?php //echo $sno; 
																																						?>" value="<?php //echo $row1['remark'] 
																																														?>" onblur="getUppercase(this.id)"/>-->
																<span name="sp_remark<?php echo $sno; ?>" id="sp_remark<?php echo $sno; ?>">
																	<?php echo $row1['remark'] ?>
																</span>
															</td>

															<td>
																<span id="txtRem_mul<?php echo $sno; ?>"><?php echo $row1['mul_ent']; ?></span>


															</td>

															<td>
																<input type="button" name="btnUpdate_<?php echo $sno; ?>" id="btnUpdate_<?php echo $sno; ?>" value="Delete" onclick="UpdateData(this.id)" />
															</td>
														</tr>
												<?php
														$sno++;
													}
												}
												?>

											</table>
										</span>
										<input type="hidden" name="hdTotal" id="hdTotal" value="<?php echo $sno - 1; ?>" />
										<input type="hidden" name="hd_fc" id="hd_fc" />
									</fieldset>

								<?php } ?>
							</div>
							<div id="div_show"></div>
						</div>
					</form>

				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	</div>
</section>