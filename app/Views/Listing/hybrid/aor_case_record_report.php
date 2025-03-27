<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">VC Consent - Report</h3>
                            </div>
                            <?// = view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?php
                    echo form_open();
                    csrf_field();
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto col-sm-4">
                                        <label class="col-form-label">Listing Date<span style="color:red;">*</span></label>
                                        <div class="border">
                                            <select name="listing_dts" id="listing_dts" class="form-control cus-form-ctrl" required>
                                                <?php if (count($listing_date) > 0) { ?>
                                                    <option value="-1" selected>SELECT</option>
                                                    <?php foreach ($listing_date as $row) { ?>
                                                        <option value="<?= $row['next_dt']; ?>"><?= date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="-1" selected>EMPTY</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto col-sm-4">
                                        <label class="col-form-label">List Type</label>
                                        <div class="border">
                                            <select class="form-control cus-form-ctrl" name="list_type" id="list_type">
                                                <option value="0">ALL</option>
                                                <option value="4">Misc.</option>
                                                <option value="3">Regular</option>
                                                <option value="5">Chamber</option>
                                                <option value="6">Registrar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto col-sm-4">
                                        <label class="col-form-label">Source</label>
                                        <div class="border">
                                            <select class="form-control cus-form-ctrl" name="consent_source" id="consent_source">
                                                <option value="0">All</option>
                                                <option value="1">Email</option>
                                                <option value="2">Portal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto col-sm-4">
                                        <label class="col-form-label">Hon'ble Judges</label>
                                        <div class="border">
                                            <select class="form-control cus-form-ctrl" name="judge_code" id="judge_code">
                                                <option value="0" selected>All</option>
                                                <?php
                                                if (count($honble_judges) > 0) {
                                                    foreach ($honble_judges as $row) {
                                                        ?>
                                                        <option value="<?= $row['jcode'] ?>"> <?= $row['judge_name'] ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="-1" selected>EMPTY</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto col-sm-4">
                                        <label class="col-form-label">OR Court No.</label>
                                        <div class="border">
                                            <select class="form-control cus-form-ctrl" name="court_no" id="court_no">
                                                <option value="0" selected>All</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="21">21 (Registrar)</option>
                                                <option value="22">22 (Registrar)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto col-sm-4 mt-4">
                                        <!-- <label>Action<span style="color:red;">*</span></label> -->
                                        <button id="button_search" name="button_search" type="button" class="btn btn-success btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12 m-0 p-0" id="result"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
</section>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',
            autoclose: true
        });
    });
    $(document).on('click', '#button_search', function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var listing_dts = $("select#listing_dts option:selected").val();
        var list_type = $("select#list_type option:selected").val();
        var judge_code = $("select#judge_code option:selected").val();
        var consent_source = $("select#consent_source option:selected").val();
        var court_no = $("select#court_no option:selected").val();
        if (listing_dts == '-1') {
            $('#show_error').html('');
            $('#result').html('');
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select listing date</strong></div>');
            $("#listing_dts").focus();
            // return false;
        }
        if (judge_code.length < 0 && court_no.length < 0) {
            $('#show_error').html('');
            $('#result').html('');
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select either Honble Judge Name or Court No.</strong></div>');
            $("#listing_dts").focus();
            // return false;
        } else {
            $("#result").html('');
            var postData = {};
            postData.CSRF_TOKEN = csrf;
            postData.listing_dts = listing_dts;
            postData.list_type = list_type;
            postData.judge_code = judge_code;
            postData.consent_source = consent_source;
            postData.court_no = court_no;
            $.ajax({
                url: '<?php echo base_url('/Listing/hybrid/get_aor_case_record_report_1') ?>',
                cache: false,
                async: true,
                data: postData,
                dataType: 'html',
                beforeSend: function() {
                    $("#button_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
                },
                type: 'POST',
                success: function(res) {
                    updateCSRFToken();
                    if (res) {
                        $("#result").html('');
                        $("#result").html(res);
                    } else {
                        $("#result").html('');
                    }
                    $("#button_search").html('Search');
                },
                error: function(xhr) {
                    updateCSRFToken();
                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
            updateCSRFToken();
        }
    });
    $(document).on("click", "#prnnt1", function() {
        var divContents = $("#print_area").html();
        var a = window.open('', '', 'height=1200, width=800');
        //a.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"><html>');
        a.document.write('<link rel="stylesheet" href="../../offline_copying/css/bootstrap.min.css" ><html>');
        a.document.write('<body >');
        a.document.write(divContents);
        a.document.write('</body></html>');
        a.document.close();
        a.print();
    });
</script>