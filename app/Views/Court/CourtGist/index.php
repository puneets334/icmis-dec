<?= view('header'); ?>
<style>
	.form-control,
	.btn {
		font-size: 14px !important;
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
								<h3 class="card-title">Court >> Court Master (NSH) >> Court Master Gist >> New/Modify </h3>
							</div>
							<?= view('Filing/filing_filter_buttons'); ?>
						</div>
					</div>
					<b>
						<div id="div_result1" style="text-align: center"></div>
					</b>
					<hr>
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<div class="card-body">
								<form method="post" name="frm" id="frm" action="<?= site_url(uri_string()) ?>">
									<?= csrf_field() ?>


									<div class="col-md-6">
										<div class="form-group">
											<label for="courtno">Listing Date:</label>
											<input type="text" name="ddl_ord_date" id="ddl_ord_date" autocomplete="off" class="dtp form-control col-md-6_ "  />
										</div>
									</div>

									<div class="form-group col-md-6">
										<div class="form-group">
											<label for="courtno">Gist Message:</label>
											<textarea placeholder="Enter Gist value" class="btn-block summary" cols="6" rows="4" maxlength="500" style="width:100%; color:red;" name="summary" id="summary"></textarea>
										</div>
									</div>

									<div class="form-group col-md-12">
										<input type="button" name="sub" value="SUBMIT" id="sub" />

									</div>

								</form>


							</div>
						</div>
						<div class="col-md-2"></div>
					</div>



					<b>
						<div id="div_result" style="text-align: center"></div>
					</b>
					<center><span id="loader"></span> </center>

				</div>
			</div>
		</div>
	</div>
</section>
 
<script type="text/javascript" src="<?php echo base_url(); ?>/courtMaster/gist_module_report.js?random=3"></script>
<script type="text/javascript">

$(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });


	$(document).ready(function() {
		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
		$.ajax({
			url: '<?php echo base_url(); ?>/Court/CourtCauseGist/get_cause_title',
			cache: false,
			async: true,
			data: {
				CSRF_TOKEN: CSRF_TOKEN_VALUE
			},

			type: 'POST',
			success: function(data, status) {
				updateCSRFToken();
				$('#div_result1').html(data);

			},
			error: function(xhr) {
				updateCSRFToken();
				alert("Error: " + xhr.status + " " + xhr.statusText);
			}

		});
	});
</script>