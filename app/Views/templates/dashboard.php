<style>
	/* Start Loader Animation */
	#whole_page_loader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background-color: #f5f5f6c7;
		background-image: url('<?php echo base_url('/supreme-court-logo.png'); ?>');
		background-repeat: no-repeat;
		background-position: center;
		background-size: 104px;
		background-position: 51% 51%;
		display: block;
	}

	#main-loader {
		display: block;
		position: relative;
		left: 50%;
		top: 50%;
		width: 170px;
		height: 170px;
		margin: -75px 0 0 -75px;
		border-radius: 50%;
		border: 3px solid transparent;
		border-top-color: #3498db;
		animation: spin 2s linear infinite;
	}

	#main-loader:before {
		content: "";
		position: absolute;
		top: 5px;
		left: 5px;
		right: 5px;
		bottom: 5px;
		border-radius: 50%;
		border: 3px solid transparent;
		border-top-color: #e74c3c;
		animation: spin 3s linear infinite;
	}

	#main-loader:after {
		content: "";
		position: absolute;
		top: 15px;
		left: 15px;
		right: 15px;
		bottom: 15px;
		border-radius: 50%;
		border: 3px solid transparent;
		border-top-color: #f9c922;
		animation: spin 1.5s linear infinite;
	}

	@keyframes colorFade {
		0% {
			color: #3498db;
			opacity: 0.3;
		}

		50% {
			color: #bb0000;
			opacity: 1;
		}

		100% {
			color: #e74c3c;
			opacity: 0.5;
		}
	}

	#loader-text {
		position: absolute;
		top: 64%;
		left: 50%;
		transform: translateX(-50%);
		font-size: 20px;
		animation: colorFade 3s linear infinite;
	}

	@media screen and (max-width: 767px) {
		#loader-text {
			position: absolute;
			top: 75%;
			left: 50%;
			transform: translateX(-50%);
			font-size: 20px;
			color: #c80000;
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	/* Close Loader  */
</style>
<div class="whole-page-overlay" id="whole_page_loader">
	<div id="main-loader"></div>
	<div id="loader-text">Loading...</div>
</div>
<?= view('sci_main_header'); ?>
<!-- Main Sidebar Container -->
<?= view('header'); ?>
<!-- Sidebar -->

<?= view('templates/left_side_bar_menu'); ?>
<!-- /.sidebar -->

<div class="content-wrapper_stop">
	<div id="cover-spin" style="display: none"></div>
	<!--
    <section class="content">-->
	<div class="sci_main_content_view" id="sci_main_content_view">



		<div class="mainPanel">
			<div class="alert alert-info alert-dismissable fadein" id="info-alert">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Info! </strong>
				No Latest Updates Found.
			</div>
			<div class="panelInner">
				<div class="middleContent">
					<div class="container-fluid">

						<div class="left-content-inner comn-innercontent">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_1">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link1">
													<i class="fa fa-file-text-o" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>Today's Cases</p>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_2">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link2">
													<i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>My Pending Cases</p>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_3">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link3">
													<i class="fa fa-upload" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>Judgement Not Uploaded</p>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_4">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link4">
													<i class="fa fa-calendar-o" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>My Disposal in This Month</p>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_5">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link5">
													<i class="fa fa-files-o" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>IA/Document Filed in this month</p>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_6">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link6">
													<i class="fa fa-list-alt" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>Cases Filed in this month</p>
													<!--<span><small>Cases / Appl. Filed by You</small></span>-->
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_7">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link7">
													<i class="fa fa-file-text-o" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>Recent Documents</p>
													<span><small>By Other Parties</small></span>
												</div>
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-3 col-lg-3 mt-4">
									<div class="bg_card bg_card_8">
										<div class="tiles-comnts">
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Civil</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">02</h6>
												<p class="comnt-name">Crimnal</p>
											</div>
											<div class="tile-comnt">
												<h6 class="comts-no">08</h6>
												<p class="comnt-name">Total</p>
											</div>
										</div>
										<a href="#">
											<div class="bg_card_inner_light">
												<div class="icon_card_link icon_card_link8">
													<i class="fa fa-exclamation" aria-hidden="true"></i>
												</div>
												<div class="card_name_title">
													<p>Incomplete Filings</p>
													<span><small>Cases / Appl. Filed by You</small></span>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
							<div class="dashboard-section" style="display:none">
								<div class="row">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12">
										<div class="dash-card">
											<div class="title-sec">
												<h5 class="unerline-title">Filled Cases</h5>
											</div>
											<div class="table-sec">
												<div class="table-responsive">
													<table class="table table-striped custom-table">
														<thead>
															<tr>
																<th>Sr. No.</th>
																<th>Stage</th>
																<th>Filling No.</th>
																<th>Case Detail</th>
																<th>Type</th>
																<th>Submitted On</th>
																<th>Allocated To</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>01</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>02</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>03</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>04</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>05</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>06</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>
															<tr>
																<td>07</td>
																<td>Stage 2</td>
																<td>e-3249283</td>
																<td>Property & Money Transfer</td>
																<td>Civil</td>
																<td>28 May 2024</td>
																<td>Lawyer 1</td>
															</tr>

														</tbody>
													</table>
												</div>
												<div class="pagination-area">
													<div class="sowing-pg">
														<p>01-06 in one page</p>
													</div>
													<div class="pagination-inner">
														<nav aria-label="Page navigation example">
															<ul class="pagination">
																<li class="page-item">
																	<a class="page-link" href="#"
																		aria-label="Previous">
																		<i
																			class="fas fa-angle-double-left"></i>
																	</a>
																</li>
																<li class="page-item active"
																	aria-current="page"><a class="page-link"
																		href="#">1</a></li>
																<li class="page-item"><a class="page-link"
																		href="#">2</a></li>
																<li class="page-item"><a class="page-link"
																		href="#">3</a></li>
																<li class="page-item">
																	<a class="page-link" href="#"
																		aria-label="Next">
																		<i
																			class="fas fa-angle-double-right"></i>
																	</a>
																</li>
															</ul>
														</nav>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--</section>-->
</div>
<!-- /.content-wrapper -->
<?= view('sci_main_footer'); ?>
<script>
	$(document).ready(function() {
		$('#whole_page_loader').hide();
	});
	$(document).ready(function() {
		$('#data_page_open').on('click', function() {
			// alert('dsfad');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('DefaultController/case_status'); ?>",
				data: form_data,
				beforeSend: function() {
					$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				success: function(data) {
					$("#loader").html('');
					
				},
				error: function() {
					updateCSRFToken();
				}
			});
		})
	});
</script>
