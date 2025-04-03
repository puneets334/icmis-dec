<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Data of Video Conferencing hearing matters</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-2 mt-4">
                                <label for=""><b>From Date :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="from_dt1" id="from_dt1" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9">
                                <input type="hidden" name="hd_from_dt1" id="hd_from_dt1" value="1" />
                            </div>
                            <div class="col-md-2 mt-4">
                                <label for=""><b>To Date :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="from_dt2" id="from_dt2" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9" />
                                <input type="hidden" name="hd_from_dt2" id="hd_from_dt2" value="1" />
                            </div>
                            <div class="col-md-2 mt-4">
							    <input type="hidden" name="submit" value="submit">
                                <input type="button" id="btnGetDiaryList" class="btn btn-block_ btn-primary" value="Show" />
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
        var dateFrom = $('#from_dt1').val();
        var dateTo = $('#from_dt2').val();
		date1 = new Date(dateFrom.split('-')[2], dateFrom.split('-')[1] - 1, dateFrom.split('-')[0]);
        date2 = new Date(dateTo.split('-')[2], dateTo.split('-')[1] - 1, dateTo.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");
           return false;
        }
		
		
		$("#dv_res1").html('');
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/VC_Report/VCStats') ?>',
            type: "POST",
            cache: false,
            async: true,
			beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                CSRF_TOKEN: csrf,
                dateFrom: dateFrom,
                dateTo: dateTo,
			 },
            success: function(r) {
				updateCSRFToken();
				window.open("<?php echo base_url('/ManagementReports/VC_Report/VCStats') ?>", "_blank");
                $("#dv_res1").html('');
			},
            error: function() {
                updateCSRFToken();
                alert('ERRO');
            }
        });
        updateCSRFToken();
    });


</script>