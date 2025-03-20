<form method="post" action="" id="vac_list_form">
               
<div id="dv_content1">
 
 
<div class="row  g-3">
<div class="col-md-12 mt-2 text-center">
	<span style="font-weight: bold; color:#4141E0; text-decoration: underline;">VACATION REGISTRAR CAUSE LIST</span>
	</div>
</div>
<div class="row g-3">
    
  <div class="col-md-3 mt-2">   
		<label for="from_date" class="">Date</label>
                <input type="date" size="10" class="form-control" name="ldates" id="ldates" value="<?php echo date('Y-m-d')?>" >
</div>

<div class="col-md-3 mt-2"> 
			<label for="from_date" class="">Registrar</label>
            <select class="form-control" name="reg_code" id="reg_code">
                <option value="0"><b>--All--</b></option>
                <option value="536"> SH. H. SHASHIDHARA SHETTY, REGISTRAR (J-I)</option>
                <option value="539"> SH. VIVEK SAXENA, REGISTRAR</option>
            </select>
</div>
      
<div class="col-md-3 mt-2">
			<label for="from_date" class="">Section Name</label>
            <select name="sec_id" id="sec_id" class="form-control">
            <option value="0"><b>--All--</b></option>
            <?php foreach($section as $sec) :?>
            <option value="<?php echo $sec->id; ?>" > <?php echo $sec->section_name; ?></option>
            <?php endforeach ?>
              
        </select>
</div>
       
<div class="col-md-3 mt-4">  
    <input type="button" name="btn1" id="vac_list" value="Submit" class="btn btn-primary">
</div>
 
</div>
       
</form>
            </div>
            </div>
            <br>
<div id="result_data"></div>

<script>
    $('#vac_list').on('click', function () {
        //alert('hi');


            var form_data = $('#vac_list_form').serialize();
            if(form_data){ //alert('readt post form');
                //var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/vac_list'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#vac_list').val('Please wait...');
                        $('#vac_list').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#vac_list').prop('disabled', false);
                        $('#vac_list').val('Submit');
                        $("#result_data").html(data);

                        //updateCSRFToken();
                    },
                    error: function () {
                        //updateCSRFToken();
                    }

                });
                return false;
            }
    });

</script>
