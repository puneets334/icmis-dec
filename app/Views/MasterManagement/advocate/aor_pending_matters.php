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
                                <h3 class="card-title">Master Management >>Advocate </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">AOR Pending/Disposed Matters Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">AOR Name</label>
                                                        <select class="form-select formselect" id="aor">
                                                            <option value="">Select</option>
                                                            <?php foreach ($advocate as $row) { ?>
                                                                <option value=<?= $row['aor_code'] ?>>
                                                                    <?= $row['aor_code'] ?>:
                                                                    <?= $row['adv_name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Status</label>
                                                        <select class="form-control" id="status">
                                                            <option value="">All</option>
                                                            <option value="P">Pending</option>
                                                            <option value="D">Disposed</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="" class="">Filing Date:</label>
                                                                <input type="text" size="7" name="from_dt1" id="from_dt1" class="form-control dtp" placeholder="From" alue="<?php echo date('d-m-Y'); ?>" readonly />
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="" class="">&nbsp;</label>
                                                                <input type="text" size="7" name="from_dt2" id="from_dt2" class="form-control dtp" placeholder="To" alue="<?php echo date('d-m-Y'); ?>" readonly />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Case Type</label>
                                                        <select name="caseType" id="caseType" class="form-select">
                                                            <option value="">ALL CASES</option>
                                                            <?php foreach ($casetype as $r_nature) { ?>
                                                                <option value="<?php echo $r_nature['casecode']; ?>"><?php echo $r_nature['casename']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" id="btnGetDiaryList" class="quick-btn mt-26" onclick="fetch_data();">Submit</button>
                                                    </div>
                                                </div>

                                            </form>
                                            <div id="record" class="mt-3 Datacenter"></div>
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


    $(document).ready(function() {
        $(".formselect").select2();
    });

    function fetch_data() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#record').hide();
        var aor = $('#aor').val();
        var status = $('#status').val();
        var from_dt1 = $('#from_dt1').val();
        var from_dt2 = $('#from_dt2').val();
        var caseType = $('#caseType').val();
        if (aor == '') {
            alert("Please select AOR");
            return;
        }
        $.ajax({
            type: "POST",
            url: baseURL + "/MasterManagement/Advocate/CasesView",
            data: {
                aor: aor,
                status: status,
                from_dt1: from_dt1,
                from_dt2: from_dt2,
                caseType: caseType,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            beforeSend: function() {
                $('#record').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },

            success: function(data) {
                updateCSRFToken();
                $('.Datacenter').html(data);
                $('#record').show();

            },

            error: function() {
                updateCSRFToken();
                alert('Error');
            }

        });
    }
</script>