<form method="post" action="" id="sec_list_form">
               
<div class="row  g-3">
<div class="col-md-12 mt-2 text-center">
	<span style="font-weight: bold; color:#4141E0; text-decoration: underline;text-align: center">Sec List</span>
	</div>
</div>
<div class="row g-3">

  <div class="col-md-3 mt-2">
		<label for="from_date" class="">Board Type</label>
        <select class="form-control" name="board_type" id="board_type">            
            <option value="J" selected="">Court</option>
            <!--<option value="C">Chamber</option>
            <option value="R">Registrar</option>-->
        </select>

</div>

<div class="col-md-3 mt-2">
		<label for="from_date" class="">Tentative Listing Date</label>
                    <select name="ldates" id="ldates" class="form-control">           
                        <option value="0">-ALL-</option>
                        <?php
                        if(!empty($tentavive_date_lists)){
                            foreach($tentavive_date_lists as $tentavive_date_lists){
                        ?>
                            <option value="<?php echo $tentavive_date_lists['working_date']; ?>"><?php echo $tentavive_date_lists['working_date']; ?></option>
                        <?php
                            }
                        } 
                        ?>

                        
                    </select>
                <!-- input type="text" size="10" class="dtp" name='ldates' id='ldates' value="01-02-2024" readonly / -->
        <!--<input type="text" size="10" class="dtp" name='ldates_to' id='ldates_to' value="" readonly />-->

</div>

 <div class="col-md-3 mt-2">
 <label for="from_date" class="">Section Name</label>
<select name="sec_id" id="sec_id" class="form-control">
                            <option value="0">--All--</option>
                            <?php foreach($section as $sec) :?>
                            <option value="<?php echo $sec->id; ?>" > <?php echo $sec->section_name; ?></option>
                            <?php endforeach ?>
              
        </select>
</div>
 <div class="col-md-3 mt-4">
	<input type="button" name="btn1" id="sec_list" value="Submit" class="btn btn-primary">
</div>
</div>
</form>

<div id="result_data"></div>

<script>
    $('#sec_list').on('click', function () {
        //alert('hi');


            var form_data = $('#sec_list_form').serialize();
            if(form_data){ //alert('readt post form');
                //var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/sec_list'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#sec_list').val('Please wait...');
                        $('#sec_list').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#sec_list').prop('disabled', false);
                        $('#sec_list').val('Submit');
                        $("#result_data").html(data);

                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }

                });
                return false;
            }
    });

</script>

<style>
    /* div.dt-buttons {
    position: relative;
    margin-right: 50%;
} */
    </style>

