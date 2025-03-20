  <?= view('header') ?>

  <section class="content">
  	<div class="container-fluid">
  		<div class="row">
  			<div class="col-12">
  				<div class="card">
  					<div class="card-header heading">

  						<div class="row">
  							<div class="col-sm-10">
  								<h3 class="card-title"> Diary Search</h3>
  							</div>

  							<?= view('Filing/filing_filter_buttons'); ?>
  						</div>
  					</div>

  					<form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>
  						<div style="text-align: center;color: red">List of matters filed against a particular High Court Case No..</div>
  						<!--<div style="text-align: center;color: red">Enter Caveat lower court Details to match Caveat lower court details</div>-->
  						<div id="dv_content1">
  							<div class="box-body">
  								<div class="row">

  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="">Court</label><span style="color: red">*</span>
  										<?php
											$ddl_court = is_data_from_table('master.m_from_court', "display='Y' order by order_by", 'id,court_name', 'A');
											?>
  										<select name="ddl_court" id="ddl_court">
  											<option value="">Select</option>
  											<?php
												foreach ($ddl_court as $row) {
												?>
  												<option value="<?php echo $row['id'] ?>" <?php if ($row['id'] == '1') { ?> selected="selected" <?php } ?>><?php echo $row['court_name'] ?></option>
  											<?php
												}
												?>
  										</select>
  									</div>

  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="">State</label><span style="color: red">*</span>
  										<?php
											$state = is_data_from_table('master.state', "district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = 'Y'
											AND sci_state_id !=0 ORDER BY name", 'id_no, name', 'A');
											?>
  										<select name="ddl_st_agncy" id="ddl_st_agncy">
  											<option value="">Select</option>
  											<?php foreach ($state as $value) { ?>
  												<option value="<?php echo $value['id_no']; ?>"><?php echo $value['name'] ?></option>
  											<?php
												} ?>
  										</select>
  									</div>

  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="ddl_bench">Bench</label>
  										<select name="ddl_bench" id="ddl_bench" class="form-control">
  											<option value="">Select</option>
  										</select>
  									</div>


  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="ddl_ref_case_type">Case Type</label>
  										<select name="ddl_ref_case_type" id="ddl_ref_case_type" class="form-control">
  											<option value="">Select</option>
  										</select>
  									</div>


  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="txt_ref_caseno">No</label>
  										<input type="text" name="txt_ref_caseno" id="txt_ref_caseno" size="6" class="form-control" />
  									</div>



  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="ddl_ref_caseyr">Year</label><span style="color: red">*</span>
  										<select name="ddl_ref_caseyr" id="ddl_ref_caseyr" class="form-control">
  											<option value="">Select</option>
  											<?php
												$y_r = date('Y');
												for ($t = $y_r; $t >= 1950; $t--) {
												?>
  												<option value="<?php echo $t; ?>"><?php echo $t; ?></option>
  											<?php
												}
												?>
  										</select>
  									</div>


  									<div class="col-sm-12 col-md-3 mb-3">
  										<label for="txt_order_date">Order Date</label>
  										<input type="text" name="txt_order_date" id="txt_order_date" class="dtp form-control" maxlength="10" size="9" />
  									</div>

  									<div class="col-md-4 mt-2">
  										<input type="button" name="btn_submit" id="btn_submit" value="Submit" />
  										<input type="button" onclick="printDiv('dv_result')" value="Print " name="printbutton" id="printbutton" style="display:none;" />
  									</div>
  								</div>
  							</div>

  							<div id="dv_result" style="text-align: center;margin-top: 10px"></div>
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





  <style>
  	.table_tr_th_w_clr td {
  		padding: 10px;
  	}

  	@media print {
  		#cmdPrnRqs2 {
  			display: none;
  		}
  	}
  </style>

<script type="text/javascript" src="<?php echo base_url() ?>/reports/diary_diary.js"></script>
  <script>
  	$(document).on("focus", ".dtp", function() {

  		$('.dtp').datepicker({
  			dateFormat: 'dd-mm-yy',
  			changeMonth: true,
  			changeYear: true,
  			yearRange: '1950:2050'
  		});
  	});
   
  	function printDiv(strid) {
  		document.getElementById('dv_result').style.display = 'none';
  		var prtContent = document.getElementById(strid);
  		var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

  		WinPrint.document.write(prtContent.innerHTML);
  		WinPrint.document.close();
  		WinPrint.focus();
  		WinPrint.print();
  		document.getElementById('dv_result').style.display = 'block';
  		WinPrint.close();
  		//prtContent.innerHTML=strOldOne;
  	}
  </script>