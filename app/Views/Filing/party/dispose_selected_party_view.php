<?= view('header'); ?>
<style>
    #wrapper_1:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }

    #wrapper_2:after {
        content: "";
        background-color: #000;
        position: absolute;
        width: 0.2%;
        height: 100%;
        top: 0;
        left: 100%;
        display: block;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                             <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
						
                    </div>
					<?=view('Filing/filing_breadcrumb');?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                <?=view('Filing/party/party_breadcrumb');?>
                                    <?php
$filing_details= session()->get('filing_details');
$allow_user=0; 
$ucode=  $_SESSION['login']['usercode'];
$check_if_fil_user = is_data_from_table('fil_trap_users', " usertype=101 AND display='Y'  and usercode=$ucode ", '*', $row = 'N');

if($check_if_fil_user > 0 ){
    $allow_user=1;
}

                                    $attribute = array('class' => 'form-horizontal', 'name' => 'party_view_form', 'id' => 'party_view_form', 'autocomplete' => 'off');
                                    echo form_open('Filing/Party/save_party_details', $attribute);
                                    ?>
                                </div><!-- /.card-header -->
                                <div class="">
                                    <div class="tab-content">

                                         
										
										<div class="tab-pane active" id="multi_party_dispose_tab_panel">
                                             
                                            <div class="row mt-5 mb-3">
                                                
 
												<?php 
												$_REQUEST['dno'] =  $_SESSION['filing_details']['diary_no'];    
												$fil_no_diary = " diary_no=$_REQUEST[dno] ";
												$hdfil = $_REQUEST['dno'];
												
												$main_row = is_data_from_table('main', $fil_no_diary, 'pet_name,res_name,c_status', $row = '');
												if(!empty($main_row))
													{ 
												
												?>
													<input type='hidden' value="<?php echo $hdfil;?>" id='hdfno_dispose'>
													<table align="center" width="100%" id="table_causetitle" cellpadding="10">
														<tr align="center" style="color:blue"><th><?php
																echo "Case No.-";
															   
																$casetype = $casetypeDetail;
																 
																if($casetype['fil_no'] !='' || $casetype['fil_no'] !=NULL){
																	echo '[M]'.$casetype['short_description'].SUBSTR($casetype['fil_no'],3).'/'.$casetype['m_year'];
																}

																if($casetype['fil_no_fh'] !='' || $casetype['fil_no_fh'] !=NULL){
																	
																	$fil_no_fh = SUBSTR($casetype['fil_no_fh'], 0, 2);
																	$r_case = is_data_from_table('master.casetype', "casecode = $fil_no_fh ", 'short_description', $row = '');
																   if(!empty($r_case)){
																	    echo ',[R]'.$r_case['short_description'].SUBSTR($casetype['fil_no_fh'],3).'/'.$casetype['f_year'];
                                                                   }
																}
																echo ", Diary No: ".substr($_REQUEST['dno'],0,-4).'/'.substr($_REQUEST['dno'],-4); ?></th></tr>
														<tr  align="center" style="color:blue"><th><b><?php
																	echo $main_row['pet_name'];
																	if($casetype['pno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
																	else if($casetype['pno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
																	?>
																</b><font style="color:black">&nbsp; Versus &nbsp;</font>
																<b><?php echo $main_row['res_name'];
																	if($casetype['rno']==2) echo " <span style='color:#72bcd4'>AND ANR</span>";
																	else if($casetype['rno']>2) echo " <span style='color:#72bcd4'>AND ORS</span>";
																	?></b></th></tr>
															<?php
															if($main_row['c_status']=='D')
															{
																?>
																<tr><th style="color:red;">!!!The Case is Disposed!!!</th></tr>
																<?php
																 
															}
															?>
													</table>
													<?php
													
															if($main_row['c_status']!='D')
															{
																?>
														  <div class="col-12 col-sm-12 col-md-4 col-lg-4">
																<div class="form-group row clearfix">
																	<label class="col-form-label"><strong>Select Petitioner/Respondent</strong></label>
																	<Select class="form-control" id="forparty" name="forparty" style="width: 300px;" onchange="getParty(<?php echo $hdfil; ?>)">
																		<option value="">----SELECT----</option>
																		<option value="P">Petitioner</option>
																		<option value="R">Respondent</option>
																	</Select>
																</div>
															</div>
													
															 <div class="col-12 col-sm-12 col-md-4 col-lg-4">
																<div class="form-group row clearfix">
																	<label class="col-form-label"><strong>Select Party</strong></label>
																	<Select class="form-control" id="dispparty" name="dispparty[]" style="height: 100px" multiple>
																
																	</Select>
																</div>
															</div>
													
															<div class="col-12 col-sm-12 col-md-4 col-lg-4">
																<div class="form-group row clearfix">
																	<label class="col-form-label"><strong>Select Dipose/Delete</strong></label>
																	<Select class="form-control" id="dispby" name="dispby" style="width: 300px;" >
																		<option value="">----SELECT----</option>
																			<!--<option value="T">Delete Entered By Mistake[Party No. will Shift]</option>-->
																			<option value="D">Dispose By Court Order</option>
																			<option value="O">Delete By Court Order</option>
																	</Select>
																</div>
															</div>
															<div class="col-12 col-sm-12 col-md-4 col-lg-4">
																<div class="form-group row clearfix">
																	<label class="col-form-label"><strong>Remarks</strong></label>
																	<input class="form-control" type="text" id="resremark" name="resremark" >
																</div>
															</div>
															 
															<div class="col-md-12 mb-3 mt-5 text-center">
																<button type="button" class="btn btn-primary" id="btn-update" onclick="disposeParty();"> Submit </button>
															</div>
															<?php
																 
															}
															?>
												
													<?php }
														else
														{?>
															<table align="center"><tr><th style="color:red">Record Not Found!!!</th></tr></table>
															<?php
														}?>
												
												 


                                                <div id="div_result" class="col-md-12 text-center"></div>
                                            </div>
                                             

                                           


                                        </div>
										
										
										 
										
										 
										
                                        <!-- /.copy_party_tab_panel -->

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    $('.col-sm-12.col-form-label.ml-4').hide()

    
function getParty(diaryno)
{
    var forparty = $('#forparty').val();
    if(forparty!='')
    {
		var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: base_url+'/Filing/Party/get_party_info',
            //cache: false,
            //async: true,
            data: {diaryno: diaryno,forparty:forparty,CSRF_TOKEN: CSRF_TOKEN_VALUE},
            type: 'POST',
            beforeSend: function (xhr) {
                $("#div_result").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data, status) {
				updateCSRFToken();
                $('#dispparty').html(data);
                $("#div_result").html('');

            },
            error: function(xhr) {
				updateCSRFToken();
                $("#div_result").html('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
}

function disposeParty(){
	
    if( $("#forparty").val()==''){
        alert("Please Select For Petitioner/Respondent");
        $("#dispby").focus();
        return false;
    }
    var x = parseInt(document.getElementById("dispparty").length);	 
    if(x <= 1) {
        alert("Please add more parties before dispose");
        return false;
    }
    var opts = getSelectedOptions( document.getElementById('dispparty'));
    var party='';
	 
    if(opts.length<1) {
        alert("Please Select Party");
        $("#dispparty").focus();
        return false;
    }
    else if(opts.length==x){
        alert("All parties can not be deleted!!!!");
        $("#dispparty").focus();
        return false;
    }
    else{
        for (i = 0; i < opts.length; i++) {
            party += opts[i] + "?";
        }
    }

    if( $("#dispby").val()==''){
        alert("Please Select Dispose/Delete");
        $("#dispby").focus();
        return false;
    }
    if( $("#resremark").val()==''){
        alert("Please enter remarks");
        $("#resremark").focus();
        return false;
    }
    //var partyid = opts;
    var hdfno = $("#hdfno_dispose").val();
    var resremark = $("#resremark").val();

	var CSRF_TOKEN = 'CSRF_TOKEN';
		var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POST',
        url: base_url+"/Filing/Party/dispose_selected_party",
        beforeSend: function (xhr) {
            $("#div_result").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{CSRF_TOKEN: CSRF_TOKEN_VALUE,forparty:$("#forparty").val() ,partyid:party,diaryno: hdfno,restremark: resremark,dispby: $("#dispby").val()}
    })
        .done(function(msg){
			updateCSRFToken();
            $("#div_result").html('');
            alert(msg);
			$('#forparty').val('');
			$('#dispparty').val('');
			$('#dispby').val('');
			$('#resremark').val('');
            // call_fetch_infoAgain(document.getElementById('hdfno_dispose').value);
            // call_fetch_causetitle(document.getElementById('hdfno_dispose').value);
            location.reload();

        })
        .fail(function(){
			updateCSRFToken();
            $("#div_result").html('');
            alert("ERROR, Please Contact Server Room");
        });
}

function getSelectedOptions(sel) {
    var opts = [], opt;
    // loop through options in select list
    for (var i=0, len=sel.options.length; i<len; i++) {
        opt = sel.options[i];
        // check if selected
        if ( opt.selected ) {
            // add to array of option elements to return from this function
            //alert(opt.value);
            opts.push(opt.value);
        }
    }
    // return array containing references to selected option elements
    return opts;
}
     

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39 || charCode == 46) {
            return true;
        }
        return false;
    }

    function onlyalpha(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }

     

    function onlyalphabnum(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 48 && charCode <= 57) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 40 || charCode == 41 ||
            charCode == 37 || charCode == 39 || charCode == 44) {
            return true;
        }
        return false;
    }

    function onlyalphab(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) ||
            charCode == 9 || charCode == 8 || charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 ||
            charCode == 40 || charCode == 41 || charCode == 37 || charCode == 39 || charCode == 44) {
            return true;
        }
        return false;
    }

    

 
    

    function isFloat(n) {
        return Number(n) === n && n % 1 !== 0;
    }
 


</script>