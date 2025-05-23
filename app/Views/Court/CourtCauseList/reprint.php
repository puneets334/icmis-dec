<?=view('header'); ?>
<style>
    /* input[type="text"], input[type="date"], input[type="email"], input[type="tel"], input[type="number"], input[type="url"], input[type="password"], input[type="search"], select, textarea 
    {
	    border: 1px solid #e1e1e1 !important;
	    width: 100% !important;
	    height: 38px !important;
	    padding: 5px 10px !important;
	    border-radius: 0 !important;
	} */

	.form-control, .btn {
		font-size: 14px !important;
	}
</style>
<script type="text/javascript" src="<?php echo base_url();?>/courtMaster/reprint_j_o.js"></script> 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-title">Court >> Court Master (NSH) >> Court Master Cause List >> Reprint</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                            <form method="post" name="frm" id="frm" action="<?= site_url(uri_string()) ?>">
                            <?= csrf_field() ?>
                                <div class="row"> 
                            		<div class="form-group col-md-12">
                            			<span class="col-md-4">
                            				Order Date
                                            <input type="radio" name="ddl_oud" id="ddl_od" value="O"/>
                            			</span>
                            			<span class="col-md-4">
			                                Uploaded Date
                                            <input type="radio" name="ddl_oud" id="ddl_ud" value="U"/>
			                            </span>
                            		</div>
									<div class="col-md-12" style="display: inline-flex">
										<div class="form-group col-md-4">
											<div class="form-group">
												<label for="inputField" class="col-sm-6 col-form-label">From Date:</label>
												<div class="col-sm-9">
													<input type="text" name="txt_o_frmdt" id="txt_o_frmdt" value="<?php echo date('d-m-Y'); ?>" size="6" maxlength="10" class="dtp form-control"/>
												</div>
											</div>
										</div>
										<div class="form-group col-md-4">
											<div class="form-group">
												<label for="inputField" class="col-sm-6 col-form-label">To Date:</label>
												<div class="col-sm-9">
													<input type="text" name="txt_o_todt" id="txt_o_todt" value="<?php echo date('d-m-Y'); ?>" size="6" maxlength="10" class="dtp form-control"/>
												</div>
											</div>
										</div>
										<div class="col-md-2 mt-4">
										   <label for="inputField" class="col-sm-6 col-form-label"></label>
										   <input type="button" name="btn_submit" id="btn_submit" value="Submit" onclick="get_records()"/>
										</div>
									</div>	
                                </div>

                                <div id="dv_get_res"></div>
            
                                        <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103" >
                                            &nbsp;
                                        </div>
                                        <div id="dv_fixedFor_P2" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 105">
                                            <!--<div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()">
                                                <img src="close_btn.png" style="width: 30px;height: 30px;">
                                            </div>-->
                                            <div id="sp_close2" style="text-align: right;cursor: pointer;position: absolute;right: 4%;" onclick="closeData2()">
                                                <img src="<?php echo base_url()?>/images/close_btn.png" style="width: 30px;height: 30px;">
                                            </div>
                                            <div id="sar1" style="margin: 0 auto; background-color: white;overflow: hidden;width: 90%;height: 97%;margin-top: 5px;">
                                                <object style="width: 100%;height: 100%" type="application/pdf" id="ggg_object2" ></object>
                                            
                                            </div>
                                            <input type="button" name="btn_update_app" id="btn_update_app" value="Upload" onclick="update_app()"/>
                                        </div>
                                            <input type="hidden" name="hd_main_id" id="hd_main_id" />
                                            <input type="hidden" name="hd_fl_nm" id="hd_fl_nm" />
                                            <input type="hidden" name="hd_orderdate" id="hd_orderdate" />
                                            <input type="hidden" name="hd_fil_no" id="hd_fil_no" />
                                            <input type="hidden" name="hd_p_pdf" id="hd_p_pdf" />
                                            <input type="hidden" name="hd_f_size" id="hd_f_size" value="15" />
                            	</form>

				                 

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?=view('sci_main_footer');?>