<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Pre Notice and After Notice Matters</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="button" id="btngetr" onclick="fetch_data();" class="btn btn-primary quick-btn" name="btngetr" value=" Get Records " />
                                </div>
                            </div>
                            <div id="dv_res1"></div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="center" id="record"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>

function fetch_data()
    {
        $('#dv_res1').html("");
        var mainhead = 'M';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Pending/pre_after_notice_get'); ?>',
            cache: false,
            async: true,
            type: 'post',
            data: {
                CSRF_TOKEN: csrf,
                mainhead: mainhead
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
       
    }
   
</script>