  <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        
			
              <div class="box box-info">

                    <form method="post" action="<?= site_url(uri_string()) ?>">
					<?= csrf_field() ?>
                        <div class="box-body">

                            <div class="form-group" >
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="days" >Enter No. of days:</label>
                                        <input type="number" id="days" name="days" class="form-control"   required="required">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="section" >Select Section</label>
                                        <select class="form-control" id="section" name="section[]" multiple required="required">
                                            <option value="0" disabled>Select multiple</option>                                            
                                            <?php
											 
                                            foreach($sections as $section)
                                                echo '<option value="'.$section->section_name.'">'.$section->section_name.'</option>';
                                            ?>
                                        </select>
                                    </div>
                                    <div  class="col-sm-5">
                                        <button type="button" onclick="getData();" style="width:25%;float:left" id="view" name="view" class="form-control btn btn-block btn-primary">View</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
					<div class="row">
                        <div class="col-md-12">
							<div id="result1"></div>
						</div>
					</div>
                </div>
				
                
           
           
		   </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
			</div>
    </section> 
	
	<script>
	
	function getData()
	{
		 var CSRF_TOKEN = 'CSRF_TOKEN';
		 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
		 var days =  $('#days').val();
		 var section =  $('#section').val();
		 if(days =='')
		 {
			 alert('Please input days!!');
			 $('#days').focus();
			 return false;
		 }
		 if(section =='')
		 {
			 alert('Please select section!!');
			 $('#section').focus();
			 return false;
		 }
		 
			$.ajax({
				type: 'POST',
				url: base_url+"/Reports/Filing/Report/get_defective_matters_not_listed",
				beforeSend: function (xhr) {
					$("#view").prop('disabled', true);
					$("#result1").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
				},
				data:{days:$('#days').val(),section:$('#section').val(),CSRF_TOKEN: CSRF_TOKEN_VALUE}
			})
			.done(function(msg){
				updateCSRFToken();
				$("#view").prop('disabled', false);
				$("#result1").html(msg);
				$("#result2").html("");
				if(val=='D')
				{
					$('#suc_msg').show();
					//$('#suc_msg').hide(5000);
				}
			})
			.fail(function(){
				updateCSRFToken();
				$("#view").prop('disabled', false);
				alert("ERROR, Please Contact Server Room"); 
			});
	}
	
	</script>