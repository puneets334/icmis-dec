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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-title">Court >> Court Master (NSH) >> Court Master Cause List >> Live Reporting </h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                            	<form id="myform" action="#" enctype="multipart/form-data" method="" class="form-inline" >
                            		<div class="form-group col-md-4">
                            			<label for="courtno">Court No.</label>
                            			<select name="courtno" id="courtno" class="form-control">
                            				<option value="">Select</option>
                            				<?php
                            					foreach (get_court_number() as $key => $row) 
                            					{
                            				?>
                            						<option value="<?php echo $key; ?>" <?php if($key == 1){ echo "selected"; } ?>><?php echo $row; ?></option>
                            				<?php
                            					}
                            				?>
                            			</select>
                            		</div>
                            		<?php
                            			if (isset($_POST["dtd"]) and $_POST["dtd"]!='')
                            			{
		                                	$dtd = $_POST["dtd"];
		                                }
		                                else
		                                {
		                                	$dtd = date("d-m-Y");
		                            	}
                            		?>
                            		<div class="form-group col-md-4">
                            			<label for="dtd">Cause List Date</label>
                            			<input type="text" name="dtd" id="dtd" class="form-control" value="<?php echo $dtd; ?>">
                            		</div>
                            		<div class="form-group col-md-4"></div>
                            		<div class="form-group col-md-12 mt-3">
                            			<span class="mr-3">
                            				<input type="radio" name="mf" id="mf" value="M" checked>Miscellaneous
                            			</span>
                            			<span class="mr-3">
			                                <input type="radio" name="mf" id="mf" value="F">Regular
			                            </span>
			                            <span class="mr-3">
			                                <input type="radio" name="mf" id="mf" value="L" >Lok Adalat
			                            </span>
			                            <span class="mr-3">
			                                <input type="radio" name="mf" id="mf" value="S">Mediation
			                            </span>
                            		</div>
                            		<div class="col-md-2 mt-3">
                            			<input type="submit" name="submit" class="btn btn-success" value="Submit">
                            		</div>
                            	</form>

                                <hr/>
                                	<a href="javascript:void(0)" class="btn btn-primary mentioning">Mentioning</a>
                                	<a href="javascript:void(0)" class="btn btn-primary display_board">Message to Display Board</a>
                                <hr/>

                                <div id="show_mention" style="display: none;">
                                	<form class="form-inline">
                                		<div class="form-group col-md-4">
                                			<label>Oral Mentioning Case No.</label>
					                		<input type="text" name="mentioningNo" id="mentioningNo" maxlength="4" onkeypress="return isNumber(event)">
					                		<span class="text-danger mt-1">(Only Number(s) Allowed)</span>
					                	</div>

					                	<div class="form-group col-md-6">
					                		<input type="button" name="bt1" id="bt1" class="btn btn-primary mr-2" value="Send">
		                                    <input type="button" name="btnClearMsg" class="btn btn-primary mr-2" id="btnClearMsg" value="Clear Mentioning">
		                                    <input type="button" name="bt2" class="btn btn-primary mr-2" id="bt2" value="Cancel">
		                                </div>
                                	</form>
				                </div>

				                <div id="show_display_board" style="display: none;">
				                	<form class="form-inline">
                                		<div class="form-group col-md-6">
                                			<label>Message</label>
					                		<textarea name="msgbox" id="msgbox" rows="1" cols="80"></textarea>
					                		<span class="text-danger mt-1">(Special Characters are not allowed except space and.)</span>
					                	</div>

					                	<div class="form-group col-md-6">
					                		<input type="button" name="bt1" id="bt1" class="btn btn-primary mr-2" value="Send">
		                                    <input type="button" name="btnClearMsg" class="btn btn-primary mr-2" id="btnClearMsg" value="Clear Message">
		                                    <input type="button" name="bt2" class="btn btn-primary mr-2" id="bt2" value="Cancel">
		                                </div>
                                	</form>
				                </div>

				                <center><span id="loader"></span> </center>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		$(".mentioning").click(function(){
			$("#show_mention").show();
			$("#show_display_board").hide();
		});

		$(".display_board").click(function(){
			$("#show_mention").hide();
			$("#show_display_board").show();
		});
	});
</script>
<?=view('sci_main_footer');?>