<?= view('header') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Category Year Wise Pendency (including defects)</h3>
                        </div>
                    </div>
                </div>
				<div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
						<?php  $file_list = ""; $cntr = 0;  $chk_slno = 0;  $chk_pslno = 0;  $temp_msg = ""; ?>
                        <div class="row">
                           <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
						   <div class="col-md-12 mt-4" style="text-align: center;">
                                <input type="button" class="btn btn-block_ btn-primary" value="Submit" onclick="get_pending_data();">
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
						  
<script>
    $(function() {
        $("#ldates").datepicker();
    });



    function get_pending_data() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $("input.pdbutton").attr("disabled", false);
        $.ajax({
            type: "POST",
            data: {
               CSRF_TOKEN: csrf,
            },
            url: '<?php echo base_url('ManagementReports/Report/categoryProcessYear'); ?>',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(msg) {
                updateCSRFToken();
                document.getElementById("dv_res1").innerHTML = msg;
                $("input.pdbutton").attr("disabled", false);
            },
            error: function() {
                updateCSRFToken();
                alert("ERROR");
            }
        });


    }

     $(document).on("click", "#prnnt", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>