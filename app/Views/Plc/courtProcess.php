<?= view('header') ?>
<style>
	.dis_pls_supre {
		display: flex;
		justify-content: space-between;
		width: 100%;
	}

	.column2 {
		padding: 10px;
		margin-bottom: 15px;
	}

	.column4 {
		padding: 10px;
		margin-bottom: 15px;
	}

	.card-header {
		padding: .75rem 0;
	}

	.is_noe_supr_cust {
		margin: 0 !important;
	}
</style>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header heading">
						<div class="row">
							<div class="col-md-12">
								<h3 class="card-title">E-court - Live court</h3>
							</div>
						</div>
					</div>
					<div class="row mt-2" style="width: 100% !important;">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<?php if (session()->getFlashdata('msg')): ?>
								<?= session()->getFlashdata('msg') ?>
							<?php endif; ?>

							<?php
							$attribute = array('class' => 'form-horizontal appearance_search_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => 'post');
							echo form_open(base_url('#'), $attribute);
							?>
							<input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>" />
							<input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
							<div class="">
								<div class="" id="">
									<div class="row is_noe_supr_cust">
										<div class="col-md-3 p-0">
											<div class="dis_pls_supre">
												<div class="form-group">
													<label for="caseYear" class="control-label">Cause List Date</label>
													<input type="text" class="form-control dtp" name="dtd" id="dtd" value="<?php echo date('d-m-Y'); ?>" maxlength="10" size="10" readonly>
												</div>
												<div class="form-group">
													<label for="caseYear" class="control-label">&nbsp;</label>
													<select name="courtno" id="courtno" class="form-control" style="<?= $select_display_none; ?> font-size: 0.7vw; width:150px!important">
														<option value="">Select</option>
														<?php
														foreach ($courtNos as $court) {
															echo '<option value="' . $court["courtno"] . '">' . str_replace("\\", "", $court["jname"]) . '</option>';
														}
														?>
													</select>
												</div>
											</div>
											<div class="row_column11"></div>
											<div class="left_panel_data_row1" style="height:95vh; overflow-y: scroll;">
												<div class="column11">
													<div class="row">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-9">
											<center><span id="loader"></span></center>
											<div class="column2"></div>
											<div class="column4"></div>
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
	$(document).on("focus", ".dtp", function() {
		$('.dtp').datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});
	});

	function updateCSRFToken() {
		$.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
			$('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
		});
	}

	$(document).ready(function() {
		let CSRF_TOKEN = 'CSRF_TOKEN';
		let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
		var dtd = $("#dtd").val();
		$.ajax({
			type: 'POST',
			data: {
				CSRF_TOKEN: CSRF_TOKEN_VALUE,
				dtd: dtd,
				flag: 'court'
			},
			url: "<?php echo base_url('Plc/LiveCourt/getClDateJudges'); ?>",
			beforeSend: function() {
				$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
			},
			success: function(data) {
				$("#loader").html('')
				$('#courtno').html(data);
				// get_item_nos();
				updateCSRFToken();
			},
			error: function(xhr) {
				alert("Error: " + xhr.status + " " + xhr.statusText);
				updateCSRFToken();
			}
		});
	});

	// $(document).on("focus",".dtp",function()
	// {
	//     $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
	//     });
	// });

	$(".scrollup").hide();

	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();

		// get_item_nos();

		$(document).on('change', '#courtno', function() {
			get_item_nos();
			console.log('#courtno');
		});

		$(document).on('change', '#dtd', function() {
			// get_item_nos();
			console.log('#dtd');
		});

		$(document).on('click', '.item_no', function() {
			$(".item_no").removeClass("active");
			$(this).addClass("active");
		});

		$(document).on('change', '#dtd', function() {
			let CSRF_TOKEN = 'CSRF_TOKEN';
			let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
			var dtd = $("#dtd").val();
			$.ajax({
				type: 'POST',
				data: {
					CSRF_TOKEN: CSRF_TOKEN_VALUE,
					dtd: dtd,
					flag: 'court'
				},
				url: "<?php echo base_url('Plc/LiveCourt/getClDateJudges'); ?>",
				beforeSend: function() {
					$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				success: function(data) {
					$('#loader').html('');
					$('#courtno').html(data);
					// get_item_nos();
					updateCSRFToken();
				},
				error: function(xhr) {
					alert("Error: " + xhr.status + " " + xhr.statusText);
					updateCSRFToken();
				}
			});
		});

		function get_item_nos() {
			let CSRF_TOKEN = 'CSRF_TOKEN';
			let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
			var courtno = $("#courtno").val();
			var dtd = $("#dtd").val();
			$.ajax({
				type: 'POST',
				data: {
					CSRF_TOKEN: CSRF_TOKEN_VALUE,
					courtno: courtno,
					dtd: dtd,
				},
				url: "<?php echo base_url('Plc/LiveCourt/getTitle'); ?>",
				beforeSend: function() {
					$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				success: function(response) {
					// $("#loader").html('');
					//    $('.row_column11').html(data);
					//    updateCSRFToken();

					if (response.status) {
						$("#loader").html('');
						$('.row_column11').html(response.data);
					} else {
						$("#loader").html('');
						$('.row_column11').html(response.msg);
					}
					updateCSRFToken();
				},
				error: function(xhr) {
					$("#loader").html('');
					alert("Error: " + xhr.status + " " + xhr.statusText);
					updateCSRFToken();
				}
			});

			if (courtno != '' || courtno != null) {
				$.ajax({
					type: 'GET',
					data: {
						CSRF_TOKEN: CSRF_TOKEN_VALUE,
						courtno: courtno,
						dtd: dtd,
					},
					url: "<?php echo base_url('Plc/LiveCourt/getItemNos'); ?>",
					beforeSend: function() {
						$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
					},
					success: function(response) {
						if (response.status) {
							$("#loader").html('');
							$('.left_panel_data_row1').html(response.data);
						} else {
							$("#loader").html('');
							$('.left_panel_data_row1').html(response.msg);
						}
						updateCSRFToken();
					},
					error: function(xhr) {
						alert("Error: " + xhr.status + " " + xhr.statusText);
						updateCSRFToken();
					}
				});
			}
		}

		$(document).on('click', '.item_no', function() {
			let CSRF_TOKEN = 'CSRF_TOKEN';
			let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
			var diary_no = this.getAttribute("data-dno");
			var listdt = this.getAttribute("data-listdt");
			var displayboardval1 = this.getAttribute("data-displayboardval1");
			var displayboardval2 = this.getAttribute("data-displayboardval2");
			var cName = this.classList[2];
			var withoutLastFourChars = diary_no.slice(0, -4);
			var lastFour = diary_no.substr(diary_no.length - 4);

			if (cName == 'disabled') {
				$('.column2').html("<h3 style='color:#D81800;'>Deleted From List.</h3>");
				$('.column4').html("");
				return false;
			}

			var curr_date = $("#curr_date").val();
			var jcodes = "";
			var sbdb = "";
			if (listdt == curr_date) {
				// insert_disp(displayboardval1,diary_no,displayboardval2,jcodes,sbdb);
			}

			// $.ajax({
			//     url: 'get_right_panel_data_row2.php',
			//     cache: false,
			//     async: true,
			//     data: {diary_no:diary_no,listdt:listdt},
			//     beforeSend:function(){
			//         $('.column4').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
			//     },
			//     type: 'POST',
			//     success: function(data, status) {
			//         $('.column4').html(data);
			//     },
			//     error: function(xhr) {
			//         alert("Error: " + xhr.status + " " + xhr.statusText);
			//     }
			// });

			$.ajax({
				type: 'POST',
				data: {
					CSRF_TOKEN: CSRF_TOKEN_VALUE,
					diary_no: diary_no,
					listdt: listdt,
				},
				url: "<?php echo base_url('Plc/LiveCourt/getRightPanelDataRow2'); ?>",
				beforeSend: function() {
					$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				success: function(response) {
					if (response.status) {
						$("#loader").html('');
						$('.column4').html(response.data);
					} else {
						$("#loader").html('');
						$('.column4').html(response.msg);
					}
					updateCSRFToken();
				},
				error: function(xhr) {
					$("#loader").html('');
					alert("Error: " + xhr.status + " " + xhr.statusText);
					updateCSRFToken();
				}
			});


			// $.ajax({
			//     url: 'get_gist_details.php',
			//     cache: false,
			//     async: true,
			//     data: {diary_no:diary_no,listdt:listdt,withoutLastFourChars:withoutLastFourChars,lastFour:lastFour},
			//     beforeSend:function(){
			//         $('.column2').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
			//     },
			//     type: 'POST',
			//     success: function(data, status) {
			//         $('.column2').html(data);
			//     },
			//     error: function(xhr) {
			//         alert("Error: " + xhr.status + " " + xhr.statusText);
			//     }
			// });

			$.ajax({
				cache: false,
				type: 'GET',
				data: {
					CSRF_TOKEN: CSRF_TOKEN_VALUE,
					diary_no: diary_no,
					listdt: listdt,
					withoutLastFourChars: withoutLastFourChars,
					lastFour: lastFour
				},
				url: "<?php echo base_url('Plc/LiveCourt/getGistDetails'); ?>",
				beforeSend: function() {
					$("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
				},
				success: function(response) {
					if (response.status) {
						$("#loader").html('');
						$('.column2').html(response.data);
					} else {
						$("#loader").html('');
						$('.column2').html(response.msg);
					}
					updateCSRFToken();
				},
				error: function(xhr) {
					$("#loader").html('');
					alert("Error: " + xhr.status + " " + xhr.statusText);
					updateCSRFToken();
				}
			});
		});

		$(document).on('click', '.scrollup', function() {
			$(".left_panel_data_row1").scrollTop(0);
		});

		$(".left_panel_data_row1").on('scroll', function() {
			console.log('Event Fired');
			var y = $(this).scrollTop();
			if (y > 500) {
				$('.scrollup').fadeIn();
			} else {
				$('.scrollup').fadeOut();
			}
		});
	});

	function insert_disp(str, filno, j1, jcodes, sbdb) {
		var xhr2 = getXMLHTTP();
		var str1 = str;
		//document.getElementById("clrbrd").disabled = '';
		//alert("Here");
		str1 = str1 + ": :D" + ":" + filno + ":" + j1 + ":" + jcodes + ":" + sbdb;
		var str = "../reader/insert_show.php?str=" + encodeURIComponent(str1);
		//alert(str);
		xhr2.open("GET", str, true);
		xhr2.onreadystatechange = function() {
			if (xhr2.readyState == 4 && xhr2.status == 200) {
				var data = xhr2.responseText;
			}
		} // inner function end
		xhr2.send(null);
	}
</script>