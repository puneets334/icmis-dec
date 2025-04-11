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
                                    <h4 class="basic_heading">Pre-Notice/After Notice Cases</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Board Type</label>
                                                        <select class="ele form-control" name="board_type" id="board_type">
                                                            <!--<option value="0">-ALL-</option>-->
                                                            <option value="J" selected>Court</option>
                                                            <!--<option value="C">Chamber</option>
                                                            <option value="R">Registrar</option>-->
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Section Name</label>
                                                        <select class="ele form-control" name="sec_id" id="sec_id">
                                                            <option value="0">-ALL-</option>
                                                            <?php
                                                            foreach ($get_section_list as $ro_u) {
                                                                $ro_id = $ro_u['id'];
                                                                $ro_name = $ro_u['section_name'];
                                                            ?>
                                                                <option value="<?php echo $ro_id; ?>"> <?php echo $ro_name; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Ready/Not Ready</label>
                                                        <select class="ele form-control" name="rnr" id="rnr">
                                                            <option value="0">-ALL-</option>
                                                            <option value="1">Ready</option>
                                                            <option value="2">Not Ready</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Coram</label>
                                                        <select class="ele form-control" name="coram_having" id="coram_having">
                                                            <option value="0">-ALL-</option>
                                                            <option value="1">Having Coram</option>
                                                            <option value="2">Having No Coram</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="">Report Type</label>
                                                        <select class="ele form-control" name="pre_after" id="pre_after">
                                                            <option value="0">-ALL-</option>
                                                            <option value="1">Pre Notice</option>
                                                            <option value="2">After Notice</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <button type="button" id="rs_actio_btn1" class="quick-btn mt-26">SHOW REPORT</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="res_loader"></div>
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
    // $("#example1").DataTable({
    //     "responsive": true,
    //     "lengthChange": false,
    //     "autoWidth": false,
    //     "dom": 'Bfrtip',
    //     "bProcessing": true,
    //     "buttons": ["excel", "pdf", "print"]
    // });

    $(document).on("click", "#rs_actio_btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var board_type = $("#board_type").val();
        var coram_having = $("#coram_having").val();
        var pre_after = $("#pre_after").val();
        var rnr = $("#rnr").val();
        var sec_id = $("#sec_id").val();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/get_pre_notice'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                board_type: board_type,
                sec_id: sec_id,
                rnr: rnr,
                coram_having: coram_having,
                pre_after: pre_after,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(msg_new) {
                updateCSRFToken();
                $('#dv_res1').html(msg_new);    
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
        updateCSRFToken();
    });
</script>