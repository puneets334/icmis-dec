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
                                    <h4 class="basic_heading">Pendency - Category Wise</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label for="dtd1">As on</label>
                                                        <input class="dtp form-control" type="text" value="<?php print $dtd; ?>" name="dtd1" id="dtd1" size="10" style="font-family:verdana; font-size:9pt;" readonly="readonly">
                                                    </div>

                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label>Including Defects?</label>
                                                        <div> <label for="include_defects_yes">Yes</label>
                                                            <input type="radio" name="include_defects" id="include_defects" value="1" title="Yes">
                                                            <label for="include_defects_no">No</label>
                                                            <input type="radio" name="include_defects" id="include_defects" value="2" title="No" checked>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-2 mb-3">
                                                        <label>Details?</label>
                                                        <div>
                                                            <input type="radio" name="include_details" id="include_details" value="1" title="Yes">
                                                            <label for="include_details_yes">Yes</label>
                                                            <input type="radio" name="include_details" id="include_details" value="2" title="No" checked>
                                                            <label for="include_details_no">No</label>
                                                        </div>
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

    function get_include_defects() {
        var str = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "include_defects" && this.checked)
                str = $(this).val();
        });
        return str;
    }

    function get_include_details() {
        var str = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "include_details" && this.checked)
                str = $(this).val();
        });
        return str;
    }

    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dt = document.getElementById("dtd1").value;
        var dt1 = dt.split("-");
        var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var include_defects = get_include_defects();
        var include_details = get_include_details();

        $.ajax({
            url: "<?php echo base_url('ManagementReports/PendingReport/Report/category_process'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                dt1: dt_new1,
                include_defects: include_defects,
                include_details: include_details,
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