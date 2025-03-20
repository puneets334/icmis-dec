<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h5 class="text-center mb-0">Cases Listed then Deleted</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="datetype">Deletion</label>
                                                        <select id="datetype" name="datetype" class="form-control">
                                                            <option value="1" selected="">Before Publication</option>
                                                            <option value="2">After Publication</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="listing_dts">Cause List Date</label>
                                                        <input type="text" size="10" class="form-control dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" id="btn1" class="quick-btn mt-26">Submit</button>
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
        var list_dt = $("#listing_dts").val();
        var datetype = $("#datetype").val();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/Listing/Report/dropped_cases_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                list_dt: list_dt,
                datetype: datetype,
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
</script>