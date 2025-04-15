 <?=view('header') ?>
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title"> Causelist OR-ROP</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
				   <form method="post" action="<?= site_url(uri_string()) ?>">
						<?= csrf_field() ?>

                <div id="dv_content1"   >
                    <div  class="row">
						<div class="col-md-2">
							<div class="form-group row">								 
								<label class="col-form-label">From Date</label>
								<input type="text" name="txt_frm_date" class="form-control dtp" id="txt_frm_date" size="9" maxlength="10" value="<?php echo date('d-m-Y') ?>"/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group row">								 
								<label class="col-form-label">To Date</label>
								<input type="text" name="txt_to_date" class="form-control dtp" id="txt_to_date" size="9" maxlength="10" value="<?php echo date('d-m-Y') ?>"/>  &nbsp;&nbsp;
							 </div>
						</div>
						<div class="col-md-2">
							<div class="form-group row">
							<label class="col-form-label">&nbsp;</label>
							<input type="text" name="txt_aor_code" class="form-control" id="txt_aor_code"  placeholder="Enter Only digits"  onkeypress="return isNumberKey(event)"/>
						 </div>
						</div>
				   
				   <div class="col-md-2">
							<div class="form-group row">
							<label class="col-form-label">&nbsp;</label>
							   <select name="ddl_judge" id="ddl_judge" class="form-control">
								   <option value="">All</option>
								   <?php
									foreach ($judge as $row) {
										?>
									   <option value="<?php echo $row['jcode'] ?>"><?php echo $row['jname'] ?></option>   
									   <?php
									}
								   ?>
							   </select>
							</div>
						</div>
				   
				   <div class="col-md-2 mt-26">
						<div class="form-group row text-right">
							<input class="btn btn-primary quick-btn" type="button" name="btn_submit" id="btn_submit" value="Submit"/>
						 </div>
					</div>
            </div>
            <div id="dv_f_t_dates"></div>
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
	<script type="text/javascript" src="<?php echo base_url();?>/reports/causelist_or_rop.js" defer="defer"></script> 

	