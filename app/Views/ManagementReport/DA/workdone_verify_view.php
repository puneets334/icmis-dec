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
                                    <h4 class="basic_heading">Case Verification Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
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
                                                            </span>FOR THE DATE OF</label>
                                                        <input type="text" id="date_for" class="dtp form-control" size="10" value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
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
            url: "<?php echo base_url('ManagementReports/DA/DA/workdone_verify_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                date: $("#date_for").val(),
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
        $('#dv_sh_hd').css("display", "block");
        $('#dv_fixedFor_P').css("display", "block");
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
            success: function(msg_new) {
                // alert(data);
                updateCSRFToken();
                $("#sar").html(msg_new);
            }
        }).fail(function() {
            updateCSRFToken();
            $("#sar").html("<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>");
        });
    });


    $(document).on("click", "#sp_close", function() {
        $('#dv_fixedFor_P').css("display", "none");
        $('#dv_sh_hd').css("display", "none");
    });
</script>