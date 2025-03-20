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
								<h3 class="card-title">Filing >> Defects Modify</h3>
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

								$sql_res = '0';

								$ucode = $_SESSION['login']['usercode'];


								/* $sql_q=  mysql_query("SELECT pet_name,res_name,date_format(diary_no_rec_date,'%d-%m-%Y') dt,
											  case_grp,fil_no,c_status,casetype_id FROM main    WHERE  diary_no = '$dairy_no'  ") or die("Error: ".__LINE__.mysql_error()); */

								$sql_q = $dModel->getMainTableData($diary_no);
					
								if (!empty($sql_q)) {

									// for Defects entry in R.P./Cur.P/Cont.P/MA

									$result_casetype = $sql_q["casetype_id"];

									// pr($result_casetype);
									/* $check_section = "select * from users u join usersection us on u.section=us.id where u.usercode='$ucode' and us.isda='Y' and u.display='Y'";
								$check_section_rs = mysql_query($check_section) or die("Error: " . __LINE__ . mysql_error()); */
									// pr($ucode);
									$check_section_rs = $dModel->check_section($ucode);
									
									if ($check_section_rs > 0 && $ucode != 1) {
										
										$casetype = array('9', '10', '19', '20', '25', '26', '39');
								
										if (!in_array($result_casetype, $casetype)) {
											echo '<div style="text-align: center"><h3>Defects can be updated in RP/CUR.P/CONT.P./MA</h3></div>';
											exit();
										}
										$da = $dModel->get_da($diary_no);
										// pr($da);
										if ($da != $ucode) {
											echo '<div style="text-align: center"><h3>Defects can be updated by concerned Dealing Assistant</h3></div>';
											exit();
										}
									}
								} else {
								?>
									<div style="text-align: center"><b>Diary No. Not Found</b></div>
								<?php exit();
								}

								//$sql_jk = mysql_query("SELECT rm_dt,status   FROM obj_save WHERE diary_no = '$dairy_no'  and display='Y'") or die("Error: ".__LINE__.mysql_error());
								$sql_jk = $dModel->check_old_defect($diary_no);

								if (!empty($sql_jk)) {
									//include("../d_navigation/func.php");
									//navigate_diary($dairy_no); 
									foreach ($sql_jk as $row3) {
										if ($row3['rm_dt'] != '0000-00-00 00:00:00' || $row3['status'] != '0') {
											$sql_res = 3;
										} else {
											$sql_res = 0;
											break;
										}
										// else {
										//         $sql_res=3;
										//    }
									}
								} else {

									$sql_res = 2;
								}
								if ($sql_res == '3') {
								?>
									<div style="text-align: center"><b>Default Already Removed.Can't Update Data</b></div>
								<?php
								} else if ($sql_res == '2') {
								?>
									<div style="text-align: center"><b>Defects Not Found</b></div>
								<?php
								} else if ($sql_res == '0') {
								?>
									<fieldset id="fiOD">
										<legend><b>Defaults Added</b></legend>

										<span id="spAddObj" style="font-size: small;text-transform: uppercase">
											<table id="tb_nm" class="table_tr_th_w_clr c_vertical_align" cellpadding="5" cellspacing="5">
												<?php
												$sno = 1;
												$cn_c = '';

												/* $q_w = mysql_query("SELECT group_concat(a.id ORDER BY a.id ) id, objdesc obj_name, rm_dt, remark, group_concat( mul_ent ORDER BY a.id ) mul_ent, org_id
										FROM obj_save a, objection b WHERE a.org_id = b.objcode
										AND diary_no = '$dairy_no' AND rm_dt = '0000-00-00 00:00:00' AND a.display = 'Y'
										GROUP BY diary_no,org_id, remark order by id") or die("Error: ".__LINE__.mysql_error());
						  */

												$q_w = $dModel->getObjectionDetails($diary_no)
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
																<input id="hd_obj_id<?php echo $sno; ?>" type="hidden" value="<?php echo $row1['org_id']; ?>" />
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
																<input type="text" name="sp_remark<?php echo $sno; ?>" id="sp_remark<?php echo $sno; ?>" value="<?php echo $row1['remark'] ?>" onblur="getUppercase(this.id)" style="width: 300px" />

															</td>

															<td>
																<input type="text" name="txtRem_mul<?php echo $sno; ?>" id="txtRem_mul<?php echo $sno; ?>" value="<?php echo $row1['mul_ent'] ?>" onblur="getUppercase(this.id)" style="width: 200px" />

															</td>

															<td>
																<input type="button" name="btnUpdate_<?php echo $sno; ?>" id="btnUpdate_<?php echo $sno; ?>" value="Update" onclick="UpdateData(this.id)" />
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