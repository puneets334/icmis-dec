 <?= view('header') ?>
 <section class="content">
 	<div class="container-fluid">
 		<div class="row">
 			<div class="col-12">
 				<div class="card">
 					<div class="card-header heading">

 						<div class="row">
 							<div class="col-sm-10">
 								<h3 class="card-title"> REPORT OF WORK DONE</h3>
 							</div>

 							<? //= view('Filing/filing_filter_buttons'); 
								?>
 						</div>
 					</div>

 					<form method="post" action="">
 						<?= csrf_field() ?>
 						<div id="dv_content1">
 							<div class="row">
 								<div class="col-md-12">
 									<div class="card card-primary">
 										<div class="card-body">
 											<div class="ml-3 mt-3 mb-3 font-weight-bold" >
 												REPORT OF WORK DONE OF DA
 												<span style="color: #35b9cd">
 													<?php

														$dcmis_usertype = session()->get('login')['usertype'];

														if ($dcmis_usertype == 1) {
															echo "FOR ALL DA";
														} else {
															echo "FOR SECTION ";
															if (!empty($yoursection)) {
																foreach ($yoursection as $row) {
																	$sec .= ',' . $row['section_name'];
																}
																$sec = ltrim($sec, ',');
																echo $sec;
															} else
																echo "YOUR SECTION NOT FOUND";
														}
														?>
 												</span>
 											</div>
 											<div class="row">

 												<div class="col-sm-12 col-md-3 mb-3">
 													<label for="From" class="col-form-label">FOR THE DATE OF</label>

 													<input type="text" class="form-control dtp" id="date_for" name="date_for" placeholder="Select Date" required>

 												</div>


 												<div class="col-sm-12 col-md-3 mb-3">
 													<label for="To" class="col-form-label">Select Type</label>
 													<select name="ddl_all_blank" id="ddl_all_blank" class="form-control" required>
 														<option value="1">All</option>
 														<option value="2">Blank</option>
 														<option value="3">Atleast One</option>
 													</select>
 												</div>


 												<div class="col-sm-2">
 													<div class="form-group row">
 														<input type="button" value="SHOW REPORT" id="btnreport" class="quick-btn mt-26" />
 													</div>
 												</div>
 											</div>


 											<div id="result_main"></div>
 										</div>
 									</div>
 								</div>
 							</div>
 						</div>
 					</form>


 				</div> <!--end dv_content1-->



 				<!-- /.card -->
 			</div>
 			<!-- /.col -->
 		</div>
 		<!-- /.row -->
 	</div>
 	<!-- /.container-fluid -->
 </section>


 <script>
 	$(document).on("focus", ".dtp", function() {

 		$('.dtp').datepicker({
 			dateFormat: 'dd-mm-yy',
 			changeMonth: true,
 			changeYear: true,
 			yearRange: '1950:2050'
 		});
 	});

 	$(document).ready(function() {
 		$("#btnreport").click(function() {
 			let date_for = $('#date_for').val();
 			var CSRF_TOKEN = 'CSRF_TOKEN';
 			var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
 			var ddl_all_blank = $('#ddl_all_blank').val();
 			if (date_for == '') {
 				alert("Select the date");
 				$("#date_for").focus();
 				return false;
 			} else {
 				$.ajax({
 						type: 'POST',
 						url: "<?php echo base_url('Reports/Filing/Report/ibget_workdone'); ?>",
 						beforeSend: function(xhr) {
 							$("#result_main").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
 						},
 						data: {
 							date: $("#date_for").val(),
 							ddl_all_blank: ddl_all_blank,
 							CSRF_TOKEN: CSRF_TOKEN_VALUE
 						}
 					})
 					.done(function(msg_new) {
 						updateCSRFToken();
 						$("#result_main").html(msg_new);
 					})
 					.fail(function() {
 						updateCSRFToken();
 						alert("ERROR, Please Contact Server Room");
 					});
 			}
 		});
 	});
 </script>