<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Stastistical Data of Hearing by Courts through Video Conferencing</h3>
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
		var ddl_users = $('#ddl_users').val();
		$("#dv_res1").html('');
        //$("#dv_res1").html('<center><img src="../images/load.gif"/></center>');
        $.ajax({
            url: '<?php echo base_url('/ReportMasterFiling/get_case_alloted_details') ?>',
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
				ddl_users:ddl_users
            },
            success: function(r) {
				updateCSRFToken();
                $("#dv_res1").html(r);
            },
            error: function() {
                updateCSRFToken();
                alert('ERRO');
            }
        });
        updateCSRFToken();
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