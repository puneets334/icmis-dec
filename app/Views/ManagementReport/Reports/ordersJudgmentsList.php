<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Management Reports >> Uploaded Orders/Judgement List >> Report</h3>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-2" style="margin-bottom: 10px;">
                                <label for=""><b>Select Order Type:</b></label>
                            </div>
							<div class="col-md-10" style="margin-bottom: 10px;">
							    <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('dcmis_user_idd'); ?>">
								<div class="custom-control custom-radio custom-control-inline">
									<label class="radio-inline"><input type="radio" name="rptType" value="1" checked> Judgments</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<label class="radio-inline"><input type="radio" name="rptType" value="2"> Orders</label>
								</div>
							</div>
						    <div class="col-md-2 mt-4">
                                <label for=""><b>From Date :</b></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="fromDate" id="fromDate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9" style="width: 15%;">
                                <input type="hidden" name="hd_from_dt1" id="hd_from_dt1" value="1" />
                            </div>
                            <div class="col-md-2 mt-4">
                                <label for=""><b>To Date :</b></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="toDate" id="toDate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9" style="width: 15%;">
                                <input type="hidden" name="hd_from_dt2" id="hd_from_dt2" value="1" />
                            </div>
                            <div class="col-md-12 mt-4">
                                <input type="button" id="btnGetDiaryList" class="btn btn-block_ btn-primary" value="Get Cases" />
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <br>
                        <div id="dv_content1">
                            <div id="dv_res1" style="align-content: center"></div>
                            <div id="ank"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
					
<script>
    $(document).ready(function() {

		$(document).on("focus", ".dtp", function() {
			$('.dtp').datepicker({
				dateFormat: 'dd-mm-yy',
				changeMonth: true,
				changeYear: true,
				yearRange: '1950:2050'
			});
		});
		
		$("#btnGetDiaryList").click(function() {
			var CSRF_TOKEN = 'CSRF_TOKEN';
			var csrf = $("input[name='CSRF_TOKEN']").val();
			var rptType = $('input[name="rptType"]:checked').val();
			var fromDate = $('#fromDate').val();
			var toDate = $('#toDate').val();
			$("#dv_res1").html('');
			$.ajax({
				url: '<?php echo base_url('/ManagementReports/Report/UploadedJudgmentOrdersList_get') ?>',
				type: "POST",
				cache: false,
				async: true,
				beforeSend: function() {
					$('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				data: {
					CSRF_TOKEN: csrf,
					rptType: rptType,
					fromDate: fromDate,
					toDate: toDate,
				},
				success: function(r) {
					updateCSRFToken();
					$("#dv_res1").html(r);
				},
				error: function() {
					updateCSRFToken();
					alert('ERRO');
				}
			});
			updateCSRFToken();
    });

});
</script>
