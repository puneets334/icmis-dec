<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
	
	button.btn.btn-secondary.buttons-excel.buttons-html5 span {
       color: #fff;
    }
	
	button.btn.btn-secondary.buttons-pdf.buttons-html5 span {
		color: #fff;
	}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Case Verification Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-7 mb-3">
                                                        <label for=""> Case Verification Report <span style="color: #35b9cd">
                                                                <?php if ($usertype == 1) {
                                                                    echo "FOR ALL DA";
                                                                } else {
                                                                    echo "FOR SECTION ";
                                                                    $yoursection = $model->get_section_name($empid);
                                                                    if ($yoursection > 0) {
                                                                        foreach ($yoursection as $row) {
                                                                            $sec .= ',' . $row['section_name'];
                                                                        }
                                                                        $sec = ltrim($sec, ',');
                                                                        echo $sec;
                                                                    } else
                                                                        echo "YOUR SECTION NOT FOUND";
                                                                } ?>
                                                            </span>FOR THE DATE BETWEEN</label>
                                                        <input type="text" class="dtp form-control" id="date_for" size="10" value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">TO </label>
                                                        <input type="text" id="date_for2" class="dtp form-control" size="10" value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <button type="button" id="btnreport" class="quick-btn mt-26">SHOW REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="result_main"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    $(document).on("click", "#btnreport", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/workdone_verify_get_from'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                date: $("#date_for").val(),
                date2: $("#date_for2").val(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(msg_new) {
                updateCSRFToken();
                $("#result_main").html(msg_new);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });

    $(document).on("click", "[id^='dacase_']", function() {
		
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var tempid = this.id.split('_');
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
            type: "POST",
            url: "<?php echo base_url('ManagementReports/DA/DA/workdone_verify_get_full'); ?>",
            data: {
                date: $("#date_for").val(),
                type: tempid[0],
                flag: tempid[2],
                id: tempid[1],
                name: $("#name_" + tempid[1]).html(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            async: true,
			beforeSend: function() {
                $('#ggg').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(msg_new) {
				updateCSRFToken();
                $("#ggg").html(msg_new);
            }
        }).fail(function() {
            updateCSRFToken();
            $("#ggg").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
    });


    function closeData() {
        document.getElementById('ggg').scrollTop = 0;
        document.getElementById('dv_fixedFor_P').style.display = "none";
        document.getElementById('dv_sh_hd').style.display = "none";

    }
</script>