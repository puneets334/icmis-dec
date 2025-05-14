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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Defects >> Report</h3>
                                </div>

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
					
					<script type="text/javascript" src="<?php echo base_url();?>/filing/report.js"></script>
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
						
            <form method="post" action="<?php //echo $PHP_SELF; ?>">
                 
				<?= csrf_field() ?>
                <div id="dv_content1"   >
                    <div style="text-align: center">
               <table align="center" >
                        <tr >
                           <td><b>Diary No.</b>
                               
                                <input class="form-control" type="text" id="t_h_cno" name="t_h_cno"  size="5" value="<?php echo session()->get('filing_details')['diary_number']; ?>"/>
                          
                            </td>
                            <td><b>Diary Year</b> 
                               
                                <!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
                                <?php  
 
								$currently_selected = date('Y'); 
										$earliest_year = 1950; 
										$latest_year = date('Y'); 
										$diary_year = session()->get('filing_details')['diary_year'];
										print '<select id="t_h_cyt" class="form-control">';   
											foreach ( range( $latest_year, $earliest_year ) as $i ) 
											{
										print '<option value="'.$i.'"';
										  if($diary_year){
											if($i == $diary_year){
												print 'selected="selected"';
											}
										}
										else{
											if($i == date('Y')){
												print 'selected="selected"';
											}   
										}  
										print '>'.$i.'</option>';   
										}
										   print '</select>'; ?>                 
                            </td>
                            <td>
								<p style="margin: 0;">&nbsp;</p>
                                <input type="button" class="btn btn-primary" name="sub" id='sub' value="SUBMIT"  >
                            </td>

                        </tr>
                        
                    </table>
               
            </div>
                     <div id="div_result"></div>
                   
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
   
