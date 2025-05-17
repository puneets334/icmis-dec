<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judges Disposal</h3>
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
                                <label for=""><b>From Date :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="fromDate" id="fromDate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9">
                                <input type="hidden" name="fromDate" id="fromDate" value="1" />
                            </div>
                            <div class="col-md-1 mt-4">
                                <label for=""><b>To Date :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="toDate" id="toDate" class="form-control dtp" maxsize="10" value="<?= date('d-m-Y')?>"  autocomplete="on" size="9" />
                                <input type="hidden" name="toDate" id="toDate" value="1" />
                            </div>
                            <div class="col-md-1 mt-4">
                                <label for=""><b>Hon'ble Judge </b></label>
                            </div>
						    <div class="col-md-2">
                                    <select class="form-control" id="judge_selected" name="judge_selected">
										<?php foreach($judges as $judge) {
                                            echo '<option value = "' . $judge['jcode'].'^'.$judge['jname'] . '">' . $judge['jname'] . '</option>';
										}   ?>
									</select>
                            </div>
                            <div class="col-md-2 ">
                                <input type="button" id="btnGetDiaryList" class="btn btn-block_ btn-primary" value="View" />
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
        var dateFrom = $('#fromDate').val();
        var dateTo = $('#toDate').val();
		var judge_selected = $('#judge_selected').val();
		if(judge_selected==''){
			alert('Pleas Select Judge');
			return false;
		}
        if(dateFrom==''){
			alert('Please Select From Date');
			return false;
		}
        if(dateTo==''){
			alert('Pleas Select To Date');
			return false;
		}

        date1 = new Date(dateFrom.split('-')[2], dateFrom.split('-')[1] - 1, dateFrom.split('-')[0]);
		date2 = new Date(dateTo.split('-')[2], dateTo.split('-')[1] - 1, dateTo.split('-')[0]);
		if (date1 > date2) {
			alert("To Date must be greater than From date");

			return false;
		}

		$("#dv_res1").html('');
        $.ajax({
            url: '<?php echo base_url('Court/CourtMasterReports/reports_listing_get') ?>',
            type: "POST",
            cache: false,
            async: true,
			beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                $('#btnGetDiaryList').attr('disabled','disabled');
            },
            data: {
                CSRF_TOKEN: csrf,
                dateFrom: dateFrom,
                dateTo: dateTo,
				judge_selected:judge_selected
            },
            success: function(r) {
                updateCSRFToken();
                $("#dv_res1").html(r);
                $('#btnGetDiaryList').removeAttr('disabled');
            },
            error: function() {
                updateCSRFToken();
                alert('ERRO');
                $('#btnGetDiaryList').removeAttr('disabled');
            }
        });
    });


    $(document).ready(function() {
        $('#diaryReport').DataTable();
    });

    function get_rec(str) {
		//alert(str); 
		var sp_split=str.split('_');
        var rowid = sp_split[1];
		var detailfor = sp_split[0];
		var emp_id = $('#hd_nm_id'+rowid).val();
		var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var txt_frm_dt = $('#from_dt1').val();
        var txt_to_dt = $('#from_dt2').val();
        var ddl_users = $('#ddl_users').val();
        document.getElementById('ggg').style.width = 'auto';
        document.getElementById('ggg').style.height = ' 500px';
        document.getElementById('ggg').style.overflow = 'scroll';

        document.getElementById('ggg').style.marginLeft = '18px';
        document.getElementById('ggg').style.marginRight = '18px';
        document.getElementById('ggg').style.marginBottom = '25px';
        document.getElementById('ggg').style.marginTop = '20px';
        document.getElementById('dv_sh_hd').style.display = 'block';
        document.getElementById('sp_close').style.display = 'block';

        document.getElementById('dv_fixedFor_P').style.display = 'block';
        document.getElementById('dv_fixedFor_P').style.marginTop = '3px';

        $.ajax({
            url: '<?php echo base_url('/ReportMasterFiling/get_case_alloted_popup_details') ?>',
            cache: false,
            async: true,
            beforeSend: function() {
                $('#ggg').html('<table widht="100%" align="center"><tr><td style="text-align: center;"><img src="../images/load.gif"/></td></tr></table>');
            },
            data: {
				CSRF_TOKEN: csrf,
                rowid: rowid,
                detailfor: detailfor,
                txt_frm_dt: txt_frm_dt,
                txt_to_dt: txt_to_dt,
				ddl_users:ddl_users,
				emp_id:emp_id
            },
            type: 'POST',
            success: function(data, status) {
				updateCSRFToken();
                $('#ggg').html(data);
            },
            error: function(xhr) {
				updateCSRFToken();
				$("#ggg").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
		updateCSRFToken();
    }

    function closeData() {
        document.getElementById('ggg').scrollTop = 0;
        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";

    }
</script>