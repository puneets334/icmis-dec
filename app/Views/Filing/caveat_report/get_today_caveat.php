<?= view('header'); ?>

<style>
	div.dataTables_wrapper div.dataTables_filter label {
		display: flex;
		justify-content: end;
	}

	div.dataTables_wrapper div.dataTables_filter label input.form-control {
		width: auto !important;
		padding: 4px;
	}

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

<style type="text/css">
	#sp_amo {
		cursor: pointer;
		color: blue;
	}

	#sp_amo:hover {
		text-decoration: underline
	}
</style>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header heading">
						<div class="row">
							<div class="col-sm-10">
								<h3 class="card-title">Filing >> Report >> Datewise Caveat Report</h3>
							</div>

							<?= view('Filing/filing_filter_buttons'); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
									<h4 class="basic_heading">Datewise Caveat Report</h4>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="active tab-pane">
											<form method="post" action="<?= site_url(uri_string()) ?>">
												<?= csrf_field() ?>
												<div class="row">

													<div class="col-sm-12 col-md-3 mb-3">
														<label for="">From</label>
														<input type="text" name="from_dt1" id="from_dt1" class="dtp form-control" maxsize="10" autocomplete="on" size="9" readonly />
														<div id="dv_add_dts1"></div>
														<input type="hidden" name="hd_from_dt1" id="hd_from_dt1" value="1" />
													</div>

													<div class="col-sm-12 col-md-3 mb-3">
														<label for="">To</label>
														<input type="text" name="from_dt2" id="from_dt2" class="dtp  form-control" maxsize="10" autocomplete="on" size="9" readonly />
														<div id="dv_add_dts2"></div>
														<input type="hidden" name="hd_from_dt2" id="hd_from_dt2" value="1" />
													</div>

													<div class="col-sm-12 col-md-3 mb-3">
														<label for="">Case Type</label>

														<select name="caseType" id="caseType" class="form-control">
															<option value="">Select</option>
															<?php
															$nature = $CaveatModel->getCaseType();

															foreach ($nature as $r_nature) {
															?>
																<option value="<?php echo $r_nature['casecode']; ?>"><?php echo $r_nature['casename']; ?></option>
															<?php
															}
															?>
														</select>
													</div>
													<div class="col-sm-12 col-md-3 mb-3">
														<button type="button" name="btn1" id="btnGetDiaryList" class="quick-btn mt-26">Show</button>
													</div>
												</div>
											</form>

										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="dv_content1">
						<div id="dv_res1" style="align-content: center"></div>
						<div id="ank"></div>
					</div>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
</section>

<script type="text/javascript">
	// Initialize the date picker
	$(document).on("focus", ".dtp", function() {
		$('.dtp').datepicker({
			dateFormat: 'dd-mm-yy', // The format used by the date picker
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});
	});

	
	$("#btnGetDiaryList").click(function() {
		var dateFrom = $('#from_dt1').val();
		var dateTo = $('#from_dt2').val();
		var casyTypeId = $('#caseType').val();

		
		function parseDate(dateStr) {
			var parts = dateStr.split('-');
			if (parts.length === 3) {
				return new Date(parts[2], parts[1] - 1, parts[0]);
			}
			return null;
		}

		
		if (!dateFrom || !dateTo) {
			alert('Please fill in both date fields.');
			return;
		}

		// Parse the visible date fields into Date objects
		var dateFromObj = parseDate(dateFrom);
		var dateToObj = parseDate(dateTo);

		if (!dateFromObj || isNaN(dateFromObj.getTime()) || !dateToObj || isNaN(dateToObj.getTime())) {
			alert('Please select valid dates in the correct format (dd-mm-yy).');
			return; 
		}

		// Check if dateFrom is later than dateTo
		if (dateFromObj > dateToObj) {
			alert('The start date (From Date) cannot be later than the end date (To Date).');
			return; 
		}

		$("#dv_res1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');

		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

		$.ajax({
			url: base_url + '/Filing/CaveatReport/caveat_report',
			type: "POST",
			data: {
				dateFrom: dateFrom,
				dateTo: dateTo,
				caseTypeId: casyTypeId,
				CSRF_TOKEN: CSRF_TOKEN_VALUE
			},
			success: function(r) {
				updateCSRFToken();
				$("#dv_res1").html(r);
			},
			error: function() {
				updateCSRFToken();
				alert('ERROR');
			}
		});
	});




	$(document).ready(function() {
		$('#diaryReport').DataTable();
		$(document).on('click', '#btn_pnt', function() {
			var prtContent = document.getElementById('dv_print');
			var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
			WinPrint.document.write('<link rel="stylesheet" href="../css/menu_css.css">' + prtContent.innerHTML);
			WinPrint.print();
		});
	});
</script>