<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/css/menu_css.css">
<link rel="stylesheet" href="<?= base_url(); ?>/da_defect/dp/jquery-ui.css" type="text/css"/>
<style>
    table#tbData tr th {
    font-weight: bold;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
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
                                <h3 class="card-title">Judicial >> Defects >> Add Defects</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                echo form_open(base_url('#'), $attribute);
                                ?>
		                              <div id="dv_content1"  >
											<div style="text-align: center">
												<table align="center" >
													<tr >
														<td><b>Diary No.</b>

															<input type="text" id="t_h_cno" name="t_h_cno"  size="5" value="<?php echo @$_SESSION['session_diary_no']; ?>"/>

														</td>
														<td><b>Diary Year</b>

															<!--<input type="text" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php // echo date('Y'); ?>"/>-->
															<?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y');
															print '<select id="t_h_cyt">';   foreach ( range( $latest_year, $earliest_year ) as $i ) {
																print '<option value="'.$i.'"';
																if(@$_SESSION['session_diary_yr']){
																	if($i == @$_SESSION['session_diary_yr']){
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
														<td><div>&nbsp;</div> 
															<input type="button" name="sub" value="SUBMIT" onClick="getDetailsNew()"  >
														</td>

													</tr>

												</table>

											</div>
											
										</div>
									  

                                <?php form_close(); ?>
                            </div>
                        </div>
                    </div>

					<div class="row mb-3 mb-4">
						<div class="col-md-12">							
								 <div id="div_result"></div>
            					 <div id="div_show"></div>						
						</div>
					</div>								

                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var base_url_judicial = "<?= base_url('/Judicial/Defects') ?>"; 
</script>
<script src="<?= base_url(); ?>/da_defect/js/menu_js.js"></script>
<script src="<?= base_url(); ?>/da_defect/jquery/jquery-1.9.1.js"></script>
<script src="<?= base_url(); ?>/da_defect/calendar/datetimepicker_css.js"></script>

<script src="<?= base_url('/judicial/objection.js') ?>"></script>
<script src="<?= base_url(); ?>/da_defect/d_navigation/d_jq.js"></script>
<script src="<?= base_url(); ?>/da_defect/dp/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
                $('#case_type_casecode').prop('required', true);
                $('#diary_number').prop('required', false);
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
                $('#case_type_casecode').prop('required', false).removeClass('is-invalid');
                $('#diary_number').prop('required', true);
            }
            //alert('search_type='+search_type);
        });
        $('form').on('submit', function(e) {
            
            $('#submit').html("Loading...").prop('disabled', true);

            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                var caseType = $('#case_type_casecode').val();
                if (!caseType) {
                    e.preventDefault(); // Prevent form submission
                    $('#case_type_casecode').addClass('is-invalid');
                    $('#case_type_casecode-error').remove(); // Remove existing error if any
                    $('#case_type_casecode').after('<div id="case_type_casecode-error" class="invalid-feedback">Please select a case type.</div>');
                }
            }
        });

        $("input[name=search_type]:checked").click();
    });
</script>