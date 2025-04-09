<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Management Report >> Pending >> Year - Section wise Pendency</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="col-md-2">
                            <input type="button" id="btnGetR" class="btn btn-primary quick-btn" value="Show" />
                        </div>
                        <div id="dv_res1" style="align-content: center"></div>
                        <div id="ank"></div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $("#btnGetR").click(function() {
        $("#dv_res1").html('<center><img src="<?= base_url(); ?>/images/load.gif"/></center>');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/year_section_wise_pendency'); ?>',
            type: "POST",
             data: {CSRF_TOKEN:csrf},
            cache: false,
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
    })
</script>