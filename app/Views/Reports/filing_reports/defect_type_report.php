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
 
 <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Scrutiny >> Report >> Defect Type Report</h3>
                                </div>

                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
	
	<script>
        $(document).on("focus",".dtp",function(){   
            $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
            });
        });
        </script>
				   <form method="post" action="<?= site_url(uri_string()) ?>">
					<?= csrf_field() ?>

						<div id="dv_content1"  >

						<div style="text-align: center">
								<?php $ucode= $_SESSION['login']['usercode']; ?>
								<table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
										<tr align="center">
											<th>Get Cases in particular defect type</th>
										</tr>
										<tr align="center_">
										<th>
											<div class="box-body">
												<div class="row">
													<div class="col-md-4">
													Defect Type :
													<select id="obj_type" name="obj_type" class="form-control"><option value="0">Select defect type</option>
														<?php 
													 
													$ct_rs =  is_data_from_table('master.objection',"is_deleted='f' and display='Y' order by objdesc",'objcode,objdesc',$row='A');
													 
													foreach($ct_rs as $ct_rw)
													{
														?>
														<option value="<?php echo $ct_rw['objcode']?>"><?php echo $ct_rw['objdesc']; ?></option>
														<?php 
													}
													?></select>
													</div>
												 
													<div class="col-md-4">
														Status : <select id="status" name="status" class="form-control">
															<option value="0">ALL</option>
															<option value="1">Still defective</option>
															<option value="2">Defect Cured</option>
														</select>
													</div>
												 
													<div class="col-md-4">
														Defect Notified From Date <input type="text"  size="10" class="dtp form-control" name='from_date ' id='from_date' value="" readonly/>
													</div>
													<div class="col-md-4">
														To <input type="text" size="10" class="dtp form-control" name='to_date' id='to_date' value="" readonly/>
													</div>
													<div class="col-md-4 mt-4">
														<input type="button" id="btngetr" name="btngetr" value="Get"/>
													</div>
												</div>
														
											</div>
										</th>
										</tr>
									<tr><th ><hr></th></tr>
								</table>
								 
								<div id="res_loader"></div>
							</div>              
							<div id="dv_res1" class="p-2"></div>    
						</div>
							   
						</form> 
    <!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
		</div>
</section>   
<script>
$(document).on("click","#btngetr",function(){
    
    $('#dv_res1').html(""); 
    var obj_type = $("#obj_type").val();
    var obj_text= $("#obj_type option:selected").text();
    var status = $("#status").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    if(obj_type == 0){
        $('#dv_res1').html("Please Select Defect type."); return false;
    }
var CSRF_TOKEN = 'CSRF_TOKEN';
 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    
      $.ajax({
            url: base_url+'/Reports/Filing/Filing_Reports/get_defect_type_report',
            cache: false,
            async: true,
            data: {obj_type: obj_type,status: status,from_date: from_date,to_date:to_date,obj_text:obj_text,CSRF_TOKEN: CSRF_TOKEN_VALUE},
            beforeSend:function(){
                $("#btngetr").prop('disabled',true);
               $('#dv_res1').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status){                
               $('#dv_res1').html(data);                  
            },
            complete: function(){                
               updateCSRFToken();      
               $("#btngetr").prop('disabled',false);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
});

$(document).on("click","#prnnt1",function(){    
var prtContent = $("#prnnt").html();
var temp_str=prtContent;
var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
 WinPrint.document.write(temp_str);
 WinPrint.document.close();
 WinPrint.focus();
 WinPrint.print();
});

$(document).on("focus",".dtp",function(){
    $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
    });
});
</script>