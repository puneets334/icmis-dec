<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Report Type: SECTION DA WISE PENDENCY REPORT</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-1 mt-4">
                                <label for=""><b>Select Section</b></label>
                            </div>
						    <div class="col-md-2" id="section">
                                  <select class='form-control' name='section' id="section">
								      <option value="0">Select Section</option>
                                       <?php foreach($result_array as $vals){?>
									    <option value="<?=$vals['section_name']?>"><?=$vals['section_name']?></option>
									   <?php }?>
								   </select>  
								   <input type='hidden' id='mysection' value=''>
                            </div>
							<div class="col-md-1">
                                <input type="button" id="btnGetDiaryList" class="btn btn-block_ btn-primary" value="Submit" />
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <br>
                        <div id="dv_content1">
                            <div id="dv_res1" style="align-content: center"></div>
                            <div id="ank"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
	
	
	
    $("#btnGetDiaryList").click(function() {
		var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var section = $("#section option:selected").val();
        if(section==0){
			alert('Please Select Section');
			return false;
		}
		$("#dv_res1").html('');
        $.ajax({
            url: '<?php echo base_url('/PendencyReports/Physical_verify/da_Wise_get') ?>',
            type: "POST",
            cache: false,
            async: true,
			beforeSend: function() {
				$('#btnGetDiaryList').attr('disabled','disabled');
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                CSRF_TOKEN: csrf,
                section:section,
			 },
            success: function(r) {
				updateCSRFToken();
                $("#dv_res1").html(r);
				$('#btnGetDiaryList').removeAttr('disabled');
            },
            error: function() {
				$('#btnGetDiaryList').removeAttr('disabled');
                updateCSRFToken();
                alert('ERRO');
            }
        });
        updateCSRFToken();
    });
	
	
	
</script>