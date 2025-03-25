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
                                <h3 class="card-title">R & I </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Notices >> Dispatch Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for=""> Delivery Type</label>
                                                        <select class="ele form-control" name="ddlOR" id="ddlOR">
                                                            <option value="">All</option>
                                                            <option value="O">Ordinary</option>
                                                            <option value="R">Registry</option>
                                                            <option value="A">Humdust</option>
                                                            <option value="Z">Adv Registry</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">From Date</label>
                                                        <input type="text" size="7" class="dtp form-control" name="txt_frmdate" id="txt_frmdate" value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">To Date</label>
                                                        <input type="text" size="7" class="dtp form-control" name="txt_todate" id="txt_todate" value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">State</label>
                                                        <select class="form-control" name="ddl_state" id="ddl_state" onchange="getCity(this.value,this.id)">
                                                            <option value="">All</option>
                                                            <?php foreach ($state_list as $row) {
                                                            ?>
                                                                <option value="<?php echo $row['cmis_state_id'] ?>"><?php echo $row['agency_state'] ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">District</label>
                                                        <select class="form-control" name="ddlDistrict" id="ddlDistrict">
                                                            <option value="">All</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Case Nature / Case Type</label>
                                                        <select class="form-control" name="ddl_cas_nature" id="ddl_cas_nature" onchange="get_nat_type()">
                                                            <option value="">Select</option>
                                                            <?php foreach ($case_type as $row3) {
                                                            ?>
                                                                <option value="<?php echo $row3['nature']; ?>"><?php if ($row3['nature'] == 'C') { ?>Civil <?php
                                                                                                                                                        } else if ($row3['nature'] == 'R') { ?> Criminal <?php } else if ($row3['nature'] == 'W') { ?>Writ <?php } ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3 mt-3">
                                                        <label for=""></label>
                                                        <select class="form-control" name="cs_tp" id="cs_tp">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btnSubmit" id="btnSubmit" class="quick-btn mt-26">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="dv_get_ids"></div>
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
    function getCity(str, idd, sta) {
        var city_id = idd.split('ddl_state');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/getCityName'); ?>",
            type: "GET",
            data: {
                str: str,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(response) {
                updateCSRFToken();
                $('#ddlDistrict' + city_id[1]).html("<option value=''>All</option>" + response);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.error("AJAX Error: ", status, error);
            }
        });
    }

    function get_nat_type() {
        var ddl_cas_nature = $('#ddl_cas_nature').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/getCaseType'); ?>",
            type: "GET",
            data: {
                ddl_cas_nature: ddl_cas_nature,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(response) {
                updateCSRFToken();
                $('#cs_tp').html(response);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.error("AJAX Error: ", status, error);
            }
        });
    }

    $(document).on("click", "#btnSubmit", function() {
        var hd_folder = $('#hd_folder').val();
        var hd_ud = $('#hd_ud').val();
        var txt_frmdate = $('#txt_frmdate').val();
        var txt_todate = $('#txt_todate').val();
        var ddlOR = $('#ddlOR').val();

        var ddl_state = $('#ddl_state').val();
        var ddlDistrict = $('#ddlDistrict').val();
        var ddl_cas_nature = $('#ddl_cas_nature').val();
        var cs_tp = $('#cs_tp').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            beforeSend: function() {
                $('#dv_get_ids').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            url: "<?php echo base_url('RI/DispatchController/getDispatchRep'); ?>",
            type: "GET",
            cache: false,
            async: true,
            data: {
                txt_frmdate: txt_frmdate,
                txt_todate: txt_todate,
                hd_ud: hd_ud,
                ddlOR: ddlOR,
                ddl_state: ddl_state,
                ddlDistrict: ddlDistrict,
                ddl_cas_nature: ddl_cas_nature,
                cs_tp: cs_tp,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_get_ids').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + ' ' + xhr.statusText);
            }
        });
    });
</script>