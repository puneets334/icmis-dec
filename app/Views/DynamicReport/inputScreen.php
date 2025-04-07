<?= view('header') ?>
<style>
	table .gridtable {
		width: 100%;
		-moz-width: 100% !important;
		border-width: 1px !important;
		border-color: #666666 !important;
		border-collapse: collapse !important;
		table-layout: fixed !important;
	}
	div.dataTables_wrapper div.dataTables_filter label {
		display: flex;
		justify-content: end;
	}
	table input {
		min-width: 0px;
	}
	div.dataTables_wrapper div.dataTables_filter label input.form-control {
		width: auto !important;
		padding: 4px;
	}
</style>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.css">
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header heading">
						<div class="row">
							<div class="col-sm-10">
								<h3 class="card-title">DYNAMIC REPORT</h3>
							</div>
							<div class="col-sm-2"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header p-0" style="background-color: #fff; border-bottom: none;">
									<h4 class="basic_heading">DYNAMIC REPORT</h4>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="active tab-pane">
											<form name="advanceQuery" id="advanceQuery" action="<?= base_url('DynamicReport/DynamicReport/getResult'); ?>" method="POST" onsubmit="return submitForm();">
												<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
												<table cellpadding="3" cellspacing="0" class="gridtable">
													<!-- <tr></tr>
													<tr></tr> -->
													<!-- <thead>
														<caption class="bg-grey-3">
														<h2 class="h2head">SUPREME COURT OF INDIA</h2>
														<h3 class="h3head">DYNAMIC REPORT</h3>
														</caption>
													</thead> -->
													<tbody>
														<tr class="bg-grey-1">
															<td colspan="5" class="heading" style="text-align:center">Select Search Parameters</td>
														</tr>
														<tr class="bg-grey-1">
															<td></td>
															<td class="first"><input type="radio" name="rbtCaseStatus" id="filing" value="f" checked="checked">Filling</td>
															<td class="first"><input type="radio" name="rbtCaseStatus" id="institution" value="i" checked="checked">Registration</td>
															<td class="first"><input type="radio" name="rbtCaseStatus" id="pending" value="p"> Pendency</td>
															<td class="first"><input type="radio" name="rbtCaseStatus" id="disposal" value="d"> Disposal</td>
														</tr>
														<tr class="bg-grey-1" id="filDate">
															<td class="label">Filing Date:</td>
															<td colspan="2" class="first" style="text-align:left;"> From:
																<input type="text" class="datepick" name="filingDateFrom" id="filingDateFrom" placeholder="Select Filing From date" value="">
															</td>
															<td colspan="2" class="first" style="text-align:left;">To:
																<input type="text" class="datepick" name="filingDateTo" id="filingDateTo" placeholder="Select Filing To date" value="">
															</td>
														</tr>
														<tr class="bg-grey-1" id="regDate">
															<td class="label">Registration Date:</td>
															<td colspan="2" class="first" style="text-align:left;"> From:
																<input type="text" class="datepick" name="registrationDateFrom" id="registrationDateFrom" placeholder="Select Registration From date" value="">
															</td>
															<td colspan="2" class="first" style="text-align:left;">To:
																<input type="text" class="datepick" name="registrationDateTo" id="registrationDateTo" placeholder="Select Registration To date" value="">
															</td>
														</tr>
														<tr class="bg-grey-1" id="pendency">
															<td class="label" style="text-align:left;">Pendency Type:</td>
															<td class="first" colspan="4">
																<input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="R">Registered
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="UR">Un-Registered
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="b" checked="checked">Both
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label">Case Year:</td>
															<td colspan="4">
																<select id="caseYear" name="caseYear" style="width:20%;">
																	<option value="0">Select</option>
																	<?php
																	for ($year = date('Y'); $year >= 1950; $year--)
																		echo '<option value="' . $year . '">' . $year . '</option>';
																	?>
																</select>
															</td>
														</tr>
														<tr class="bg-grey-1" id="dispDate">
															<td class="label">Disposal Date:</td>
															<td colspan="2" class="first" style="text-align:left;">From:
																<input type="text" class="datepick" name="disposalDateFrom" id="disposalDateFrom" placeholder="Select Disposal From date" value="">
															</td>
															<td colspan="2" class="first" style="text-align:left;">To:
																<input type="text" class="datepick" name="disposalDateTo" id="disposalDateTo" placeholder="Select Disposal To date" value="">
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Case Type:</td>
															<td class="first" colspan="2">
																<input type="radio" name="rbtCaseType" id="rbtCaseType" value="C" onclick="get_casetype()">Civil
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="rbtCaseType" id="rbtCaseType" value="R" onclick="get_casetype()">Criminal
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" name="rbtCaseType" id="rbtCaseType" value="b" checked="checked" onclick="get_casetype()">Both
															</td>
															<td colspan="2">
																<select style="width:70%;" id="caseType" name="caseType[]" multiple>
																	<option value="0" disabled>Select Multiple</option>
																</select>
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Matter Type:</td>
															<td class="first"><input type="radio" name="matterType" id="Admission" value="M">Admission</td>
															<td class="first"><input type="radio" name="matterType" id="Regular" value="F"> Regular</td>
															<td class="first" colspan="2"><input type="radio" name="matterType" id="Both" value="all" checked="checked"> Both</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Party Name:</td>
															<td colspan="3" class="first">
																<input type="text" name="respondentName" placeholder="Enter full or part of name" id="respondentName" style="width:230px;" value="">
																<input type="hidden" name="petitionerName" value="">&nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="PorR" value="1">&nbsp;Petitioner &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="PorR" value="2">&nbsp;Respondent &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="PorR" value="0" checked="checked">&nbsp;Both &nbsp;&nbsp;&nbsp;&nbsp;
															</td>
															<td class="first">
																<label for="caseYear">Filing Year :</label>
																<select id="diaryYear" name="diaryYear">
																	<option value="0">Select</option>
																	<?php
																	for ($year = date('Y'); $year >= 1950; $year--)
																		echo '<option value="' . $year . '">' . $year . '</option>';
																	?>
																</select>
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Subject Category:</td>
															<td>
																<select style="width:90%;" id="subjectCategory" name="subjectCategory" onchange="get_sub_sub_cat()">
																	<option value="0">All</option>
																	<?php
																	if (!empty($MCategories))
																		foreach ($MCategories as $MCategory)
																			echo '<option value="' . $MCategory['subcode1'] . '^' . $MCategory['sub_name1'] . '" ' . (isset($_POST['subjectCategory']) && $_POST['subjectCategory'] == $MCategory['subcode1'] ? 'selected="selected"' : '') . '>' . $MCategory['subcode1'] . ' # ' . $MCategory['sub_name1'] . '</option>';
																	?>
																</select>
															</td>
															<td class="first">Sub Category:</td>
															<td colspan="2">
																<select id="subCategoryCode" name="subCategoryCode" style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
																	<option value="0">All</option>
																</select>
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Flag:</td>
															<td colspan="4" class="first" style="text-align:left;">
																<input type="checkbox" name="chkJailMatter" id="chkJailMatter" value="1"> Jail Matter
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkFDMatter" id="chkFDMatter" value="1"> FD
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkLegalAid" id="chkLegalAid" value="1"> Legal Aid
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkSpecificDate" id="chkSpecificDate" value="1"> Specific Date
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkPartHeard" id="chkPartHeard" value="1"> Part Heard
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Section: </td>
															<td>
																<select style="width:35%;" id="section" name="section" onchange="get_da()">
																	<option value="0">All</option>
																	<?php
																	foreach ($Sections as $Section)
																		echo '<option value="' . $Section['id'] . '^' . $Section['section_name'] . '" ' . (isset($_POST['section']) && $param[5] == $Section['section_name'] ? 'selected="selected"' : '') . '>' . $Section['section_name'] . '</option>';
																	?>
																</select>
															</td>
															<td class="first">Dealing Assistant:</td>
															<td colspan="1">
																<select id="dealingAssistant" name="dealingAssistant" style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
																	<option value="0">All</option>
																</select>
															</td>
															<td class="first">
																<input type="checkbox" name="showDA" id="showDA" value="1"> Show DA name in result
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">State:</td>
															<td>
																<div class="contentdiv1">
																	<select style="width:200px;" id="agencyState" name="agencyState" onchange="get_agency()">
																		<option value="0">All</option>
																		<?php
																		foreach ($states as $state)
																			echo '<option value="' . $state['cmis_state_id'] . '^' . $state['agency_state'] . '" ' . (isset($_POST['agencyState']) && $_POST['agencyState'] == $state['cmis_state_id']  ? 'selected="selected"' : '') . '>' . $state['agency_state'] . '</option>';
																		?>
																	</select>
																	<input type="hidden" name="agencyState_hidden" id="agencyState_hidden">
																</div>
															</td>
															<td class="first" colspan="2"><input type="radio" class="first" name="agency" value="1" onchange="get_agency()">&nbsp;High Court &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="agency" value="2" onchange="get_agency()">&nbsp;Tribunal &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="agency" value="0" onchange="get_agency()">&nbsp;Both &nbsp;
															</td>
															<td>
																<select id="agencyCode" name="agencyCode" style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
																	<option value="0">All</option>
																</select>
															</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">
																Advocate(AOR):
															</td>
															<td colspan="4" class="first">
																<select style="width:21%;" id="advocate" name="advocate">
																	<option value="0">All</option>
																	<?php
																	foreach ($aors as $aor)
																		echo '<option value="' . $aor['bar_id'] . '^' . $aor['name_display'] . '" ' . (isset($_POST['bar_id']) && $_POST['bar_id'] == $aor['bar_id'] ? 'selected="selected"' : '') . '>' . $aor['name_display'] . '</option>';
																	?>
																</select>
																<input type="radio" class="first" name="advPorR" value="1">&nbsp;Petitioner &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="advPorR" value="2">&nbsp;Respondent &nbsp;&nbsp;&nbsp;&nbsp;
																<input type="radio" class="first" name="advPorR" value="0" checked="checked">&nbsp;Both &nbsp;&nbsp;&nbsp;
															</td>
														</tr>
														<tr class="bg-grey-1" hidden>
															<td class="label" style="text-align:left;">
																Listing Date:
															</td>
															<td colspan="4">
																<input type="text" class="datepick" name="listingDate" id="listingDate" placeholder="Select Listing Date" value="">
															</td>
														</tr>
														<tr class="bg-grey-1" id="coram" hidden>
															<td class="label" style="text-align:left;">Coram:</td>
															<td colspan="2" class="first">
																<select style="width:200px;" id="coram" name="coram">
																	<option value="0">select</option>
																	<?php
																	foreach ($judges as $judge)
																		echo '<option value="' . $judge['jcode'] . '^' . $judge['jname'] . '">' . $judge['jname'] . '</option>';
																	?>
																</select>
															</td>
															<td class="first"><input type="radio" name="rbtCoram" id="Presiding" value="p" checked="checked">As Presiding Judge</td>
															<td class="first"><input type="radio" name="rbtCoram" id="Part" value="p1">As Part of Coram</td>
														</tr>
														<tr class="bg-grey-1">
															<td class="label" style="text-align:left;">Sort Option:</td>
															<td colspan="1" class="first">
																<select style="width:35%;" id="sort" name="sort">
																	<option value="0^None">Select</option>
																	<option value="1^Diary Number">Diary Number</option>
																	<option value="2^Case Number">Case Number</option>
																	<option value="3^Filing Date">Filing Date</option>
																	<option value="4^Registration Date">Registration Date</option>
																	<option value="5^Section">Section</option>
																	<option value="6^Subject">Subject</option>
																	<option value="7^State">State</option>
																	<option value="8^Case Status">Case Status</option>
																	<!--<option value="9^Listing Date">Listing Date</option>-->
																</select>
															</td>
															<td><input type="radio" name="rbtSortOrder" id="asc" value="asc" checked="checked">Ascending</td>
															<td><input type="radio" name="rbtSortOrder" id="desc" value="desc">Descending</td>
															<td></td>
														</tr>
														<tr class="bg-grey-1">
															</br>
															<td colspan="5" class="first" style="text-align:center;">
																<input type="submit" name="figure" id="figure" value="Show Figures">
																<input type="submit" name="full" id="full" value="Show Full Report">
																<input type="button" value="Reset" onclick="reset()">
															</td>
														</tr>
													</tbody>
												</table>
											</form>
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
</section>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script>
	function reset() {
		document.getElementById('advanceQuery').reset();
	}
	function printDiv(printable) {
		var printContents = document.getElementById(printable).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	function submitForm() {
		if (advanceQuery.rbtCaseStatus[0].checked == true) {
			var fromdate = document.forms["advanceQuery"]["filingDateFrom"].value;
			if (fromdate == null || fromdate == "") {
				alert("Filing From Date must be filled out");
				document.getElementById("filingDateFrom").focus();
				return false;
			}
			var todate = document.forms["advanceQuery"]["filingDateTo"].value;
			if (todate == null || todate == "") {
				alert("Filing To Date must be filled out");
				document.getElementById("filingDateTo").focus();
				return false;
			}
			date1 = new Date(fromdate.split('-')[2], fromdate.split('-')[1] - 1, fromdate.split('-')[0]);
			date2 = new Date(todate.split('-')[2], todate.split('-')[1] - 1, todate.split('-')[0]);
			if (date1 > date2) {
				alert("To Date must be greater than From date");
				return false;
			}
			// Reset the form fields
			this.reset();
		} else if (advanceQuery.rbtCaseStatus[1].checked == true) {
			var fromdate = document.forms["advanceQuery"]["registrationDateFrom"].value;
			if (fromdate == null || fromdate == "") {
				alert("Registration From Date must be filled out");
				document.getElementById("registrationDateFrom").focus();
				return false;
			}
			var todate = document.forms["advanceQuery"]["registrationDateTo"].value;
			if (todate == null || todate == "") {
				alert("Registration To Date must be filled out");
				document.getElementById("registrationDateTo").focus();
				return false;
			}
			date1 = new Date(fromdate.split('-')[2], fromdate.split('-')[1] - 1, fromdate.split('-')[0]);
			date2 = new Date(todate.split('-')[2], todate.split('-')[1] - 1, todate.split('-')[0]);
			if (date1 > date2) {
				alert("To Date must be greater than From date");
				return false;
			}
			// Reset the form fields
			this.reset();
		}
	}
	$(function() {
		$('.datepick').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	});
	$(document).ready(function() {
		$("#dispDate").hide();
		$("#pendency").hide();
		$("#pending").click(function() {
			$("#dispDate").hide();
			$("#regDate").show();
			$("#pendency").show();
			// $("#coram").show();
		});
		$("#filing").click(function() {
			$("#dispDate").hide();
			$("#regDate").show();
			$("#pendency").hide();
			// $("#coram").show();
		});
		$("#institution").click(function() {
			$("#dispDate").hide();
			$("#regDate").show();
			$("#pendency").hide();
			// $("#coram").show();
		});
		$("#disposal").click(function() {
			$("#regDate").show();
			$("#dispDate").show();
			$("#pendency").hide();
			//$("#coram").hide();
		});
	});
	function get_sub_sub_cat() {
		var Mcat = $("#subjectCategory option:selected").val();
		let csrfName = $("#csrf_token").attr('name');
		let csrfHash = $("#csrf_token").val();
		Mcat = Mcat.split('^')[0];
		$.ajax({
			url: '<?php echo base_url('DynamicReport/DynamicReport/getSubSubjectCategory/'); ?>',
			type: "POST",
			data: {
				[csrfName]: csrfHash,
				Mcat: Mcat
			},
			cache: false,
			dataType: "json",
			success: function(response) {
				updateCSRFToken();
				var data = response.data; // Extract the 'data' part of the response
				var options = '<option value="0">All</option>'; // Default option
				// Loop through each item in the data array
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].id + '^' + data[i].dsc + '">' + data[i].dsc + '</option>';
				}
				// Append the options to the select element
				$("#subCategoryCode").html(options);
			},
			error: function() {
				updateCSRFToken();
				alert('Error occurred while fetching data.');
			}
		});
	}

	function get_da() {
		var section = $("#section option:selected").val();
		let csrfName = $("#csrf_token").attr('name');
		let csrfHash = $("#csrf_token").val();
		section = section.split('^')[0];
		$.ajax({
			url: '<?php echo base_url('DynamicReport/DynamicReport/getDa/'); ?>',
			type: "POST",
			data: {
				section: section,
				[csrfName]: csrfHash,
			},
			cache: false,
			dataType: "json",
			success: function(response) {
				updateCSRFToken();
				var data = response.data;
				var options = '<option value="0">All</option>';
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].usercode + '^' + data[i].name + '">' + data[i].name + '</option>';
				}
				$("#dealingAssistant").html(options);
			},
			error: function() {
				updateCSRFToken();
				alert('ERRO');
			}
		});
	}

	function get_agency() {
		var state = $("#agencyState option:selected").val();
		let csrfName = $("#csrf_token").attr('name');
		let csrfHash = $("#csrf_token").val();
		state = state.split('^')[0];
		var agency = $('input[name="agency"]:checked').val();
		$.ajax({
			url: '<?php echo base_url('DynamicReport/DynamicReport/get_agency/'); ?>',
			type: "POST",
			data: {
				state: state,
				agency: agency,
				[csrfName]: csrfHash
			},
			cache: false,
			dataType: "json",
			success: function(data) {
				updateCSRFToken();
				var options = '';
				options = '<option value="0">All</option>'
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].id + '^' + data[i].agency_name + '">' + data[i].agency_name + '</option>';
				}
				$("#agencyCode").html(options);
			},
			error: function() {
				updateCSRFToken();
				alert('ERRO');
			}
		});
	}
	
	function get_casetype() {
		var type=$("input[name='rbtCaseType']:checked").val();
		let csrfName = $("#csrf_token").attr('name');
		let csrfHash = $("#csrf_token").val();
		$.ajax
		({
			url: '<?php echo base_url('DynamicReport/DynamicReport/get_casetype/'); ?>',
			type: "POST",
			data: {
				type: type,
				[csrfName]: csrfHash
			},
			cache: false,
			dataType:"json",
			success: function(data)
			{
				updateCSRFToken();
				var options = '';
				options = '<option value="0">All</option>'
				for (var i = 0; i < data.length; i++) {
					options += '<option value="' + data[i].casecode +'^'+data[i].casename+ '">' + data[i].casename + '</option>';
				}
				$("#caseType").html(options);
			},
			error: function () {
				updateCSRFToken();
				alert('ERRO');
			}
		});
    }
</script>

<!-- <script>
        function handleSubmit(event) {
            event.preventDefault(); // Prevent the default form submission
            // Here you can handle the form data, e.g., send it to a server

            // Reset the form
            document.getElementById('advanceQuery').reset();
            alert('Form submitted and reset!');
        }
    </script> -->