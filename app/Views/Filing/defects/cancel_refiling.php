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
								<h3 class="card-title">Filing >> Cancel Refiling</h3>
							</div>

							<?= view('Filing/filing_filter_buttons'); ?>
						</div>
					</div>




					<script type="text/javascript" src="<?php echo base_url(); ?>/filing/cancel_refiling.js"></script>
					<style type="text/css">
						#sp_amo {
							cursor: pointer;
							color: blue;
						}

						#sp_amo:hover {
							text-decoration: underline
						}
					</style>
					<div class="clearfix"></div>
					<?php
						$db = \Config\Database::connect();
						$filing_details= session()->get('filing_details');
						$show = (!empty($show)) ? $show : '';
						?>
						<?php if (!empty($filing_details) && (  $show == '' || $show == 'Y')){?>
							
							<div class="row">
								<div class="col-sm-12 mt-4">
									<div class="pg-breif-sec">
										<div class="row ">
											<div class="col-md-4">
												<div class="breif-detlais-inner">
													<label><b>Diary Number :</b> </label>
													<label class="lable-rslt"> <?=substr($filing_details['diary_no'], 0, -4).'/'.substr($filing_details['diary_no'],-4);?> </label>
												</div>
											</div>
											<?php if (!empty($filing_details['reg_no_display'])){?>
											<div class="col-md-4">
												<div class="breif-detlais-inner">
													<label><b>Case Number :</b></label>
													<label class="lable-rslt"> <?=$filing_details['reg_no_display'];?></label>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-4">
												<div class="breif-detlais-inner">
													<label><b>Case Title :</b></label>
													<label class="lable-rslt"> <?=$filing_details['pet_name'].'  <b>Vs</b>  '.$filing_details['res_name'];?> </label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="breif-detlais-inner">
													<label><b>Filing Date :</b></label>
													<label class="lable-rslt"><?=(!empty($filing_details['diary_no_rec_date'])) ? date('d-m-Y',strtotime($filing_details['diary_no_rec_date'])): NULL ?>
													<span class="text-blue"><?php if ($filing_details['c_status'] =='P'){ echo '<span class="text-blue">Pending</span>';}else{echo '<span class="text-red">Disposed</span>';} ?></span> </label>
												</div>
											</div>
										</div>
										
									</div>
								</div>
						</div>

							
							
						<?php } ?>
					<div class="row">	
					<div class="col-sm-12 mt-4">
					<form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>

						<div id="dv_content1">

							<div id="div_result">
								<?php
								$ucode = $_SESSION['login']['usercode'];
								if ($flag == 'A') {

								?>

									<input type="hidden" name="hd_diary_no" id="hd_diary_no" value="<?php echo $diary_no; ?>" />
									<?php
									 
									$sql_q = $dModel->getMainTableData($diary_no);

									if (!empty($sql_q)) 
									{									 

										?>
										<fieldset id="fd_md">
											<legend style="margin-left: 10px;margin-top:10px;"><h3>Main Party Details</h3></legend>
											<?php

											$result_pet = $sql_q["pet_name"];
											$result_res = $sql_q["res_name"];
											$result_dt = $sql_q["dt"];

											$result_pending = $sql_q["c_status"];

											 
											$cicri = $sql_q["case_grp"];

											?>
											<input type="hidden" name="hd_ci_cri" id="hd_ci_cri" value="<?php echo $cicri; ?>" />
											<table width="100%" class="table_tr_th_w_clr c_vertical_align" id="t1">
												<tr>
													<td style="width: 10%">
														<b> Petitioner Name </b>
													</td>
													<td style="width: 15%">
														<?php echo $result_pet; ?>
													</td>
													<td style="width: 10%">
														<b> Respondent Name </b>
													</td>
													<td style="width: 15%">
														<?php echo $result_res; ?>
													</td>
													<td style="width: 8%">
														<b> Receiving date </b>
													</td>
													<td style="width: 10%">
														<?php echo $result_dt; ?>
													</td>
												</tr>
												<tr>
													 
												</tr>
												<tr>
													 
												</tr>
											</table>
										</fieldset>
										<?php if ($result_pending == 'D') { ?>
											<div style="text-align: center;color: red">
												<h3 style='text-align:center;color: red'>Matter is Disposed!!!!</h3>
											</div>
										<?php
											exit(0);
										}

										 

										$check_if_listed = $dModel->checkifListed($diary_no);
										
										if (!empty($check_if_listed)) {
											 
											if ($check_if_listed[0] != null && $check_if_listed[0] != '') {
												 
												echo "<div style='text-align:center;color: red'><h3 style='text-align:center;color: red'>Case Is Listed. Defects cannot be added!!!!</h3></div>";
												exit(0);
											}
										}
										 
										$check_if_ver = $dModel->defectsVerification($diary_no);
										 
										if (!empty($check_if_ver)) {
											echo "<div style='text-align:center;color: red'><h3 style='text-align:center;color: red'>Case Is Verified. Defects cannot be added!!!!</h3></div>";
											exit(0);
										}

										 
										$check_if_reg = $dModel->checkIfRegd($diary_no);
										if ($check_if_reg > 0) {
											echo "<div style='text-align:center;color: red'><h3 style='text-align:center;color: red'>Case Is Registered. Defects cannot be added!!!!</h3></div>";
											exit(0);
										}


										?>
										<?php
										$sql_res = 0;

										 $sql_jk = $dModel->getObjSaveData($diary_no);
										//pr($sql_jk);
										if (!empty($sql_jk)) {
											foreach ($sql_jk as $row3) {
												if ($row3['rm_dt'] != '' && $row3['status'] == '0') {
													$sql_res = 1;
												} else if ($row3['rm_dt'] == '' && $row3['status'] == '0') {
													$sql_res = 2;
													break;
												}
											}
										} else
											echo "<div style='text-align:center'><h3 style='text-align:center;color: red'>No Defects Found!!<h3><div>";

										if ($sql_res == 1) 
										{
											?>

												<fieldset id="fiOD">
													<legend><b>Default Details</b></legend>

													<span id="spAddObj" style="font-size: small;text-transform: uppercase">
														<table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5" width="100%">
															<?php


															$sno = 1;
															$cn_c = '';

															
															$q_w = $dModel->getObjectionDetails($diary_no);
															?>
															<input type="hidden" name="hdChk_num_row" id="hdChk_num_row"
																value="<?php if (!empty($q_w)) {
																			echo count($q_w);
																		} else {
																			echo '0';
																		}; ?>" />
															<?php
															if (!empty($q_w)) {

																foreach ($q_w as $row1) {
																	if ($cn_c == '')
																		$cn_c = $row1['org_id'];
																	else
																		$cn_c = $cn_c . ',' . $row1['org_id'];
															?>
																	<tr>
																		
																		<td class="c_vertical_align">

																			<span id="spAddObjjjj<?php echo $sno; ?>" style="display: none">

																				<?php echo $sno; ?>
																			</span>
																			<?php echo $sno; ?>
																		</td>
																		<td>
																			<span id="spAddObj<?php echo $sno; ?>"><?php echo $row1['obj_name']; ?></span>

																			<span id="sp_hide<?php echo $sno; ?>"><br /></span>
																		</td>
																		<td>
																			<span id="spRema<?php echo $sno; ?>"><?php echo $row1['remark'] ?></span>
																		</td>
																		<td>
																			<span id="spRem_mula<?php echo $sno; ?>"><?php
																														$ex_ui = explode(',', $row1['mul_ent']);
																														$r = '';
																														for ($index = 0; $index < count($ex_ui); $index++) {
																															// echo 'ererere' .$ex_ui[$index];
																															if (trim($ex_ui[$index] == '')) {


																																$r = $r . '-' . ',';
																															} else {

																																$r = $r . $ex_ui[$index] . ',';

																																// echo $row1['mul_ent'] ;
																															}
																														}

																														echo substr($r, 0, -1);
																														?></span>
																		</td>
																	</tr>
																<?php
																	$sno++;
																}
																?>

															<?php
															}
															?>
															
														</table>
													</span>
													<div style="text-align: center">
														<?php
														
														$check_if_bo = $dModel->checkIfBO($ucode);

														if ($check_if_bo > 0 || $ucode == 1 || $ucode == 1504 || $ucode == 94) {

														?>
															<input type="button" name="btn_backdate" id="btn_backdate" value="Cancel Refiling" />
														<?php

														}

														?>




														<div id="sp_sms_status" style="text-align: center"></div>
													</div>


												</fieldset>
											<?php

										} else if ($sql_res == 2) {
											?>
												<div style="text-align: center;color:red">
													<h3 style='text-align:center;color: red'>Matter is Defective!!!!!</h3>
												</div>
											<?php
										}

										?>


										<?php
									} else {
									?>
										<div style="text-align: center">
											<h3 style='text-align:center;color: red'>Diary No. Not Found</h3>
										</div>
									<?php
									}
								}

								if ($flag == 'B') {

									$sql_que = $db->query("Update obj_save set rm_dt='0000-00-00 00:00:00',rm_user_id='',refil_cancel_user='$ucode',refil_cancel_date=now() where diary_no = '$diary_no' and date(rm_dt)!='0000-00-00' and status='0'");
									
									echo "<div style='text-align:center'><h3 style='text-align:center;color: red'>Data Updated Successfully<h3><div>";
								}
								?>
							</div>
							<div id="div_show"></div>
						</div>
					</form>
				</div>
				</div>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
</section>