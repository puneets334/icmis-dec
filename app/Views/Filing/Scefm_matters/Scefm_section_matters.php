<?=view('header'); ?>
 
	<style>
		.contents {
			min-height: 250px;
			padding: 87px;
			margin-right: auto;
			margin-left: auto;
			padding-left: 0px;
			padding-right: 0px;
			width: 100%;
		}

		.box.box-info {
			border-top-color: white;
		}

		.nav-pills>li.active>a,
		.nav-pills>li.active>a:hover,
		.nav-pills>li.active>a:focus {
			border-top-color: white;
		}

		.container,
		.container-lg,
		.container-md,
		.container-sm,
		.container-xl {
			max-width: 1300px;
		}
		button {
			border-radius: 5px;
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
                                <h3 class="card-title">Filing >> SC-eFM >> Dispatch Fresh Filed Matters</h3>
                            </div>
                           <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

								<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
						<?php
						if ($pending_matters != 'section is IB') {
						?>
						<li class="nav-item active">
							<a class="nav-link active" id="pending_matters-tab" data-toggle="pill" href="#pending_matters" role="tab"
							   aria-controls="pending_matters" aria-selected="true">List Of Pending Matters</a>
						</li>
						<?php }?>
						<li class="nav-item">
							<a class="<?php if($pending_matters != 'section is IB') echo 'nav-link'; else echo 'nav-link active';?>"
	                        id="datewise_matters-tab" data-toggle="pill" href="#datewise_matters" role="tab" aria-controls="datewise_matters"
						    aria-selected="false">Datewise Report</a>
						</li>
					</ul>

										<div class="tab-content" id="pills-tabContent">

											<?php
											if ($pending_matters != 'section is IB') {
												?>
												<div class="tab-pane fade show in active" id="pending_matters" role="tabpanel"
													aria-labelledby="pending_matters-tab">

													<?php
													// echo "<pre>";print_r($pending_matters);
													if (isset($pending_matters) && sizeof($pending_matters) > 0) {
														?>
														<div id="printable">
															<h2 align="center">SC-eFM Pending Efiled Matters</h2>

															<br>
															<div id="div_mess"></div>
															<br>

															<table id="example" class="table table-striped table-bordered custom-table" style="width:100%">
																<thead>
																<tr>
																	<th>S.No</th>
																	<th>Efiling Number</th>
																	<th>Diary Number</th>
																	<th>Case Type</th>
																	<th>Diary Date</th>
																	<th>IB Dispatch Date</th>
																	<th>Cause Title</th>
																	<th width="5%">Diary User</th>
																	<th width="5%">DA</th>
																	<th width="5%">Action</th>

																</tr>
																</thead>
																<tbody>
																<?php
																$i = 1;

																foreach ($pending_matters as $result) {
																	
																	?>
																	<tr id="<?= $result['efiling_no'] ?>">
																		<td><?= $i++; ?></td>
																		<td onclick="efiling_number('<?php echo $result['efiling_no']; ?>')">
																			<u><b><?php echo $result['efiling_no']; ?></u></b></td>
																		<td><?php echo $result['diary_number']; ?>
																			<?php if ($result['ref_special_category_filing_id'] != null and $result['ref_special_category_filing_id'] != '' and $result['ref_special_category_filing_id'] != 0) { ?>
																				<span id="blink_text">urgent</span>
																				<?php
																				echo "<br><font color='purple'>" . $result['category_name'] . "</font> ";
																			} ?>
																		</td>
																		<td><?php echo $result['short_description']; ?></td>
																		<td><?php echo $result['diary_date']; ?></td>
																		<td><?php echo date('d-m-Y', strtotime($result['updated_on'])); ?></td>
																		<td><?php echo $result['cause_title']; ?></td>
																		<td><?php echo $result['diary_user']; ?></td>
																		<td><?php echo $result['da']; ?></td>
																		<?php
																		$dno = explode("/", $result['diary_number']);
																		$id_dno = implode("_", $dno);
																		?>
																		<td><span id="span_tag"><button type="button" name="action_p"
																										id="<?= $id_dno ?>"
																										style="display:block;"
																										onClick="complete_process(this.id)">Done</button></span>
																		</td>

																	</tr>
																	<?php
																}

																?>
																</tbody>
															</table>
														</div>
													<?php } else {
														echo "Data is not available";
													}
													?>

												</div>

												<?php
											}/*else{
												*/?><!--
												<input type="hidden" name="hide_tab" id="hide_tab" value="1">
											--><?php
											/*						}*/


											?>

											<div <?php if($pending_matters != 'section is IB'){echo "class='tab-pane fade show'";}else{ echo "class='tab-pane fade show active'";}?> id="datewise_matters" role="tabpanel" aria-labelledby="datewise_matters-tab">
												<h2 align="center">SC-eFM All Efiled Matters</h2><br>
												<div >
													<form class="form-horizontal" id="push-form" method="post" action="#">
													<?= csrf_field('csrf_field') ?>
														<div class="box-body">
															<div class="form-group container">
																<div class="form-group row">
																	<label for="date" class="col-sm-2"><b>Section I-B Dispatch Date:</b></label>
																	<label for="date" class="col-sm-1">From Date</label>
																	<div class="col-sm-2">
																		<input type="text" id="fromDate" name="fromDate" autocomplete="off" class="form-control dtp" required placeholder="From Date">
																	</div>
																	<label for="to" class="col-sm-1">To Date</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control dtp" id="toDate" autocomplete="off" required name="toDate" placeholder="To Date">
																	</div>
																	<div class="col-sm-2">
																		<button type="button" id="view" name="view" onclick="check_datewise(); "  class="btn btn-block btn-primary">View</button>
																	</div>



																</div>
															</div>


															<!-- <button type="button"  style="width:15%;float:left" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button> -->


														</div>
													</form>
												</div>

												<br>
												<div id="report"></div>

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

<script>
    function efiling_number(efiling_number) {
        var link = document.createElement("a")
        link.href = "http://10.192.105.105:91/efiling_search/DefaultController/?efiling_number="+efiling_number
        link.target = "_blank"
        link.click()
    }

	$(function()
    {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            // yearRange: '1950:2050'
        });
    });

 

	$(function () {
    <?php $title =  "SC-EFM_matters";?>
    $("#example").DataTable({
            dom: 'Bfrtip',
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            ordering: false,
            "buttons": [
                {
                    extend: "csv",
                    title: "<?php echo $title?>"
                },
                {
                    extend: "excel",
                    title: "<?php echo $title?>"
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: "<?php echo $title?>",
                    customize: function ( doc ) {
                            doc.content.splice( 0, 0, {
                                margin: [ 0, 0, 0, 5 ],
                                alignment: 'center',
                                image: 'data:image/png;base64,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'
                            });
                            doc.watermark = {text: 'SUPREME COURT OF INDIA', color: 'blue', opacity: 0.05}
                        }

                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
    }).buttons().container().appendTo('#example .col-md-6:eq(0)');
});



	function check_datewise() {
		var from = $('#fromDate').val();
		var to = $('#toDate').val();
		var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('#csrf_field').val();
		if(from == '')
		{
		 alert('Please enter from date!!');
		 return false;	
		}
		if(to == '')
		{
		 alert('Please enter to date!!');
		 return false;	
		}
		$.ajax({
			type: 'POST',
			cache: false,
			url: "<?= base_url('Filing/Scefm_matters/efiled_pending_matters') ?>",
			data: {
				user:'<?php echo $user; ?>',
				from: from,
				to: to,
				CSRF_TOKEN:CSRF_TOKEN_VALUE
			},
			beforeSend: function () {
                    $("#report").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
			success: function(data) {
				updateCSRFToken();
				$('#report').html(data);

			},
			error: function(data) {
				updateCSRFToken();
				alert(data);
				$('#report').html('');
			}


		});
	}


	function complete_process(btnId)
	{

		var d_no = btnId.replace("_", "/");

		var confirmation = confirm("Are you sure to Complete this process!!");
		if (confirmation) {
			var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('#csrf_field').val();

			$.ajax({

				type: 'POST',
				cache: false,
				url: "<?= base_url('Filing/Scefm_matters/update_efiled_cases_transfer_status') ?>",
				data: {
					user:'<?php echo $user; ?>',
					dno: d_no,
					CSRF_TOKEN:CSRF_TOKEN_VALUE
				},
				success: function(data) {
					updateCSRFToken();
					$('#div_mess').html(data).show();

					setTimeout(function() {
						$('#div_mess').hide();
					}, 1500);

					var button = document.getElementById(btnId);
					var row = button.parentNode
					button.style.display='none';
					row.textContent='Completed';
				 

				},
				error: function(data) {
					updateCSRFToken();
					alert(data);
				}

			});
		}

	}
</script>
 
