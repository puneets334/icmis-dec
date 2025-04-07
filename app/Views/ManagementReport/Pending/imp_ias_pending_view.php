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
                                    <h4 class="basic_heading">IMPORTANT IAs PENDING</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="datetype">Section Name</label>
                                                        <select name="sec_id" id="sec_id" class="ele form-control">
                                                            <option value="0">-ALL-</option>
                                                            <?php
                                                            foreach ($get_section_list as $ro_u) {
                                                                $ro_id = $ro_u['id'];
                                                                $ro_name = $ro_u['section_name'];
                                                            ?>
                                                                <option value="<?php echo $ro_name; ?>"> <?php echo $ro_name; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" id="btn1" class="quick-btn mt-26">Get</button>
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

    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var sec_id = $("#sec_id").val();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/PendingReport/Report/imp_ias_pending_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                sec_id:sec_id,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                updateCSRFToken();
                $('#dv_res1').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
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