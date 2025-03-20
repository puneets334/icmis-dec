<?=view('header'); ?>
 
<style>
    .custom-radio{float: left; display: inline-block; margin-left: 10px; }
    .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
    .basic_heading{text-align: center;color: #31B0D5}
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
	.c_vertical_align th {
		vertical-align: middle;
	}
</style>
 <script type="text/javascript" src="<?php echo base_url();?>/filing/diary_search.js"></script>
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Scrutiny >> Report >> Party Search</h3>
                                </div>

                                <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						
 
						<form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>

							<div id="dv_content1"   >								
								<div >
								<div class="row">
									<div class="col-md-4 diary_section">
										<div class="form-group row">
											<b>Diary/Caveat</b>  
											<select name="ddl_diary_caveat" id="ddl_diary_caveat" class="form-control">
												 <option value="D">Diary</option>
												  <option value="C">Caveat</option>
											</select>
										</div>
									</div>
									<div class="col-md-4 diary_section">
										<div class="form-group row">
										<b>Party</b>
										<select name="ddl_party_type" id="ddl_party_type" class="form-control">
											<option value="">All</option>
											<option value="P">Petitioner</option>
											<option value="R">Respondent</option>
											 <option value="I">Impleading</option>
											   <option value="N">Intervenor</option>
										</select>&nbsp;&nbsp;
										</div>
									</div>
									<div class="col-md-4 diary_section">
										<div class="form-group row">
										<b>Name</b>
										<input type="text" name="txt_name" id="txt_name" class="form-control"/>
										</div>
									</div>
									<div class="col-md-4 diary_section">
										<div class="form-group row">
										<b>Status</b>
										<select name="ddl_status" id="ddl_status" class="form-control">
											<option value="">All</option>
											 <option value="P">Pending</option>
											  <option value="D">Disposed</option>
										</select>
										</div>
									</div>
									<div class="col-md-4 diary_section">
										<div class="form-group row">
										 &nbsp;
										 <b>Diary/Caveat Year</b>
										 <select name="ddl_year" id="ddl_year" class="form-control">
											 <option value="">All</option>
											 <?php
															 for ($index = date('Y'); $index>='1930'; $index--) {
																 ?>
											   <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
											 <?php
															 }
											 ?>
										 </select>
										</div>
									</div>
										<div class="col-md-4 diary_section">
										<div class="form-group row">
										<input type="button" name="btn_submit" id="btn_submit" value="Submit"/>
									</div>
									</div>
								</div>
								<div id="div_result"></div>
							</div>
						</form>
		
			<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
		</div>
</section>   
     