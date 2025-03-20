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
								<h3 class="card-title">Filing >> Refiling on back date</h3>
							</div>
							<?= view('Filing/filing_filter_buttons'); ?>
						</div>
					</div>

					<script type="text/javascript" src="<?php echo base_url(); ?>/filing/objection_upd.js"></script>
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
								$ucode = $_SESSION['login']['usercode'];

								?>

								<input type="hidden" name="hd_diary_no" id="hd_diary_no" value="<?php echo $diary_no; ?>" />
								<?php


								/* $sql_q = mysql_query("SELECT pet_name,res_name,date_format(diary_no_rec_date,'%d-%m-%Y') dt,
												  case_grp,fil_no,c_status FROM main    WHERE  diary_no = '$diary_no'  ") or die("Error: " . __LINE__ . mysql_error()); */

								$sql_q = $dModel->getMainTableData($diary_no);

								if (!empty($sql_q)) {
									//include("../d_navigation/func.php");
									//navigate_diary($diary_no);

								?>
									<fieldset id="fd_md">
										<legend><b>Main Party Details</b></legend>
										<?php

										$result_pet = $sql_q["pet_name"];
										$result_res = $sql_q["res_name"];
										$result_dt = $sql_q["dt"];

										$result_pending = $sql_q["c_status"];

										//$nature=mysql_result($sql_q, 0,"nature");
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
												<!--                        <td>
														<b> Respondent Name </b>
													</td>
													<td>
														   <?php //echo $result_res;
																?>
													</td>-->
											</tr>
											<tr>
												<!--                        <td>
														<b>Filing Date </b>
													</td>
													<td>
														   <?php //echo $result_dt;
																?>
													</td>-->
											</tr>
										</table>
									</fieldset>
									<?php if ($result_pending == 'D') { ?>
										<div style="text-align: center;color: red">
											<h3>Matter is Disposed!!!!</h3>
										</div>
									<?php
										exit(0);
									} ?>
									<?php
									$sql_res = 0;
									//$sql_jk = mysql_query("SELECT rm_dt,status   FROM obj_save WHERE diary_no = '$diary_no'  and display='Y'") or die("Error: " . __LINE__ . mysql_error());

									$sql_jk = $dModel->getObjSaveData($diary_no);

									if (!empty($sql_jk)) {
										foreach ($sql_jk as $row3) {
											if ($row3['rm_dt'] == '0000-00-00 00:00:00' && $row3['status'] == '0') {
												$sql_res = 1;
											}
										}
									}

									if ($sql_res == 0) {/*
											$check_if_bo = "select * from users where usercode='$ucode' and section='19' and usertype='14'";
											$check_if_bo = mysql_query($check_if_bo);
											if (mysql_num_rows($check_if_bo) > 0) {
												$sql_res = 1;
											} else*/
										echo "<div style='text-align:center;color:red'><h3>Matter has been refiled!!!</h3></div>";
									}
									if ($sql_res == 1) {

									?>
										<fieldset id="fiOD">
											<legend><b>Default Details</b></legend>

											<span id="spAddObj" style="font-size: small;text-transform: uppercase">
												<table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5"
													width="100%">
													<?php


													$sno = 1;
													$cn_c = '';
													/* $q_w = mysql_query("SELECT a.org_id,objdesc obj_name, rm_dt,remark, group_concat(mul_ent) mul_ent FROM obj_save a, objection b WHERE a.org_id = b.objcode
												and diary_no = '$diary_no'
													and a.display='Y' and a.rm_dt='0000-00-00 00:00:00' and a.status=0 GROUP BY diary_no,org_id, remark  order by id") or die("Error: " . __LINE__ . mysql_error());
 */
													//$chk_num_row = mysql_num_rows($q_w);
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
																<!--            <td></td>-->
																<td class="c_vertical_align">

																	<span id="spAddObjjjj<?php echo $sno; ?>" style="display: none">

																		<?php echo $sno; ?>
																	</span>
																	<?php echo $sno; ?>
																</td>
																<td>
																	<span id="spAddObj<?php echo $sno; ?>"><?php echo $row1['obj_name']; ?></span>

																	<!--        <span id="rm_dt">
									<?php
															//        if($row1['rm_dt']!='0000-00-00 00:00:00')
															//        echo "<span style='color:red'>Removed On</span> ". $row1['rm_dt'];
									?>
									</span>
									--> <span id="sp_hide<?php echo $sno; ?>"><br /></span>
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
													<!--         <input type="hidden" name="hdTotal" id="hdTotal" value="<?php echo $sno - 1; ?>"/>-->
												</table>
											</span>
											<div style="text-align: center">

												<?php
												/* $def_notify = "select min( date(save_dt) ) as save_dt ,min(date(rm_dt)) as rm_dt from obj_save where diary_no='$diary_no' and display='Y' group by diary_no ";
												$def_notify = mysql_query($def_notify) or die("Error: " . __LINE__ . mysql_error());
												 */
												$def_notify =  $dModel->def_notify($diary_no);
												if (!empty($def_notify)) {
													foreach ($def_notify as $result) {
														$def_rm_date = $result['rm_dt'];
													}
												}/*
												$c_date = date('Y-m-d');
												$get_no_of_days = "Select no_of_days from defect_policy where master_module='1'  AND (('$c_date'  BETWEEN from_date AND to_date)
												 or (from_date<= '$c_date' and to_date= '0000-00-00'))";
												$get_no_of_days = mysql_query($get_no_of_days) or die("Error: " . __LINE__ . mysql_error());
												$res_no_of_days = mysql_result($get_no_of_days, 0);
												$def_rem_max_date = date('Y-m-d', strtotime($def_notify_date . ' + ' . $res_no_of_days . ' days'));
												$nextdate = next_date($def_rem_max_date, 1);

												$diffq = "select datediff('$c_date','$nextdate') as days";
												$diffrs = mysql_query($diffq) or die("Error: " . __LINE__ . mysql_error());
												$diff = mysql_result($diffrs, 0);*/
												// echo $diff;
												if ($def_rm_date == '0000-00-00') {
													/* $check_if_bo="select * from users where (usercode='$ucode' and section='19' and usertype='14') ";
													$check_if_bo=mysql_query($check_if_bo);
													if(mysql_num_rows($check_if_bo)>0||$ucode==1) */
													if ($ucode == 1  || $ucode == 1504 || $ucode == 94) {
												?>

														<span style="color: red">Back Date</span><input type="date" name="back_dt" id="back_dt" />
														<input type="button" name="btn_backdate" id="btn_backdate" value="Save" />

												<?php }
												} ?>
												<div id="sp_sms_status" style="text-align: center"></div>
											</div>
										</fieldset>
										</span>
										</fieldset>
									<?php

									}

									?>


								<?php
								} else {
								?>
									<div style="text-align: center">
										<h3>Diary No. Not Found</h3>
									</div>
								<?php
								} ?>


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