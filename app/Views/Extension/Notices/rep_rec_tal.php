<?=view('header'); ?>
 
<script>
	$(document).on("focus", ".dtp", function() {

		$('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true, yearRange: '1950:2050'
		});
	});
</script>
 <script type="text/javascript" src="<?php echo base_url();?>/filing/rep_rec_tal.js"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Notice >> Reprint</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>

 
	
				<form method="post" action="<?= site_url(uri_string()) ?>">
					<?= csrf_field() ?>
						<div id="dv_content1"   >
				   
					<div class="row mt-4">
					   <div class="col-md-4">
							<b>From Date</b> 
							<input type="text" class="form-control dtp" name="txtFromDate" id="txtFromDate" value="<?php echo date('d-m-Y'); ?>" maxlength="10" size="10" />
						</div>
						<div class="col-md-4">
							<b>To Date</b> 
							<input type="text" class="form-control dtp" name="txtToDate" id="txtToDate" value="<?php echo date('d-m-Y'); ?>"   size="10" maxlength="10"/>
						</div>
						<div class="col-md-4 mt-3">
							<input type="button" class="btn btn-primary" name="btnSubmit" id="btnSubmit" value="Submit" onclick="get_data()"/>
						</div>
					</div>
					<div id="app_data" style="padding-top: 10px"></div>
					<div id="dis_notice" style="display: none"></div>
						</div>
				   
					  
				</form>
		
				</div>
                 <!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
</section>   