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
                                    <h4 class="basic_heading">Sectionwise Pendency Not Ready Incomplete
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">                                                    
                                                    
                                                    <div class="col-sm-12  mb-3">
                                                        <button type="button" id="btn1" class="quick-btn mt-26">Get Records</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="dv_res1"></div>
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
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var sec_id = 'II';

        $.ajax({
            url: "<?php echo base_url('ManagementReports/PendingReport/Report/sectionwise_pendency_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $("#btn1").attr("disabled", true);
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><span>Please wait It can be take few mins....</span><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                //sec_id:sec_id,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                //updateCSRFToken();
                $('#dv_res1').html(data);
                $("#btn1").attr("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });

    /*$(document).on("click","#prnnt1",function(){    
        var prtContent = $("#prnnt").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });*/
</script>