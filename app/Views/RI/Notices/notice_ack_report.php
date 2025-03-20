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
                                <h3 class="card-title">R & I</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> Acknowledgement Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" id="dispatchDakToRI" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="txtFromDate">From</label>
                                                        <input type="text" name="txtFromDate" id="txtFromDate" size="7" class="dtp form-control" value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="txtToDate">To</label>
                                                        <input type="text" size="7" name="txtToDate" id="txtToDate" class="dtp form-control" value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="section_name">Select Section</label>
                                                        <select class="ele form-control" id="section_name" name="section_name">
                                                            <option value="ALL">ALL</option>
                                                            <?php
                                                            foreach ($section as $row_sec) {
                                                            ?>
                                                                <option value="<?php echo $row_sec['section_name']; ?>"><?php echo $row_sec['section_name']; ?></option>
                                                            <?php
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3 mt-3">
                                                        <label for="ddl_cas_nature"> </label>
                                                        <select name="ddl_cas_nature" id="ddl_cas_nature" onchange="get_nat_type()" class="ele form-control">
                                                            <option value="">All</option>
                                                            <?php
                                                            foreach ($nature as $row3) {
                                                            ?>
                                                                <option value="<?php echo $row3['nature']; ?>"><?php if ($row3['nature'] == 'C') {; ?>
                                                                        Civil <?php } else if ($row3['nature'] == 'R') {; ?>Criminal <?php } elseif ($row3['nature'] == 'W') { ?>Writ <?php } ?></option>

                                                            <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>

                                                    <select name="cs_tp" id="cs_tp" style="width: 80px; display: none" disabled="true;">
                                                        <option value="">All</option>
                                                    </select>

                                                    <div class="col-sm-12 col-md-3 mb-3 mt-3">
                                                        <select name="serveType" id="serveType" class="ele form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Served</option>
                                                            <option value="5">Un-Served</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Order By</label>
                                                        <input type="radio" name="ddl_endt_da" id="dll_ent_dt" value="0" class="cl_endt_da" checked="checked" /> <b>Entry Date</b> &nbsp;
                                                        <input type="radio" name="ddl_endt_da" id="dll_da" value="1" class="cl_endt_da" /> <b>DA</b>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26" onclick="get_data()">Submit</button>
                                                    </div>


                                                </div>
                                            </form>
                                        </div>
                                        <div id="app_data"></div>
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

    function get_nat_type() {
        var ddl_cas_nature = $('#ddl_cas_nature').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (ddl_cas_nature === '') {
            $('#cs_tp').prop("disabled", true);
            return;
        } else {
            $('#cs_tp').prop("disabled", false);
        }

        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/nt_type_get'); ?>",
            type: "GET",
            data: {
                ddl_cas_nature: ddl_cas_nature,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            dataType: "html",
            success: function(response) {
                $('#cs_tp').html(response);
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                updateCSRFToken();
            }
        });
    }

    function get_data() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var cs_tp = $('#cs_tp').val() || ''; 
        var txtFromDate = $('#txtFromDate').val();
        var txtToDate = $('#txtToDate').val();
        var ddl_cas_nature = $('#ddl_cas_nature').val();
        var section_name = $('#section_name').val();
        var serveType = $('#serveType').val();
        var ddl_endt_da = $('.cl_endt_da:checked').val() || ''; 

        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/get_rep_ack_tal');?>",
            type: "GET",
            beforeSend: function() {
                $('#app_data').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                txtFromDate: txtFromDate,
                txtToDate: txtToDate,
                cs_tp: cs_tp,
                ddl_cas_nature: ddl_cas_nature,
                section_name: section_name,
                ddl_endt_da: ddl_endt_da,
                serveType: serveType,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(response) {
                $('#app_data').html(response);
                $('#sp_ctt').text($('#hd_ct_pr_id').val()); 
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                updateCSRFToken();
                $('#app_data').html('<p style="color: red;">Failed to load data.</p>');
            }
        });
    }
</script>