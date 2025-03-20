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
</style>
<link href="<?php echo base_url();?>/css/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>/filing/up_extra_cav_adv.js"></script>
<style type="text/css">
	#sp_amo
	{
		cursor: pointer;
		color: blue;
	}
	#sp_amo:hover
	{
		text-decoration: underline
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
                                    <h3 class="card-title">Filing >> Extra Advocate >> Update</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
						
						
						<form method="post" action="<?= site_url(uri_string()) ?>">
							<?= csrf_field() ?>
						  <div id="dv_content1"   >
						<div style="text-align: center">
						   <table align="center" >
									<tr >
									   <td><b>Caveat No.</b>
										   
											<input type="text" class="form-control" id="dno" size="4" value="<?php echo $_SESSION['caveat_d_no'] ?? ''; ?>"/>
									  
										</td>
										<td><b>Caveat Year</b> 
										   
											<!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
									<?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y'); 
			   print '<select id="dyr" style="width:100% !important" class="form-control">';   foreach ( range( $latest_year, $earliest_year ) as $i ) {
			   print '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';   }
			   print '</select>'; ?>                      
										</td>
										<td style="vertical-align:bottom;">
											<input type="button" name="btnGetAdvUp" value="GET DETAILS"  /></th>
										</td>

									</tr>
									
								</table>
						   
						</div>
						  <div id="result1">
        
							</div>
							<div id="result2" style="text-align: center;color:green;font-size: larger"></div>
						
					</form>

   
						
					</div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
		</div>
    </section>   
   
   
 