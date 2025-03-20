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
                                    <h4 class="basic_heading">Case Remarks Verification</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">From</label>
                                                        <input type="text" size="7" class="dtp form-control" name='from_dt' id='from_dt' value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">To</label>
                                                        <input type="text" size="7" class="dtp form-control" name='to_dt' id='to_dt' value="<?php echo date('d-m-Y'); ?>" readonly />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Status</label>
                                                        <select class="ele form-control" name="verify_status" id="verify_status">
                                                            <option value="0">-All-</option>
                                                            <option value="1" selected>Not Verified</option>
                                                            <option value="2">Verified</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                                                    </div>
                                                    <!-- <div class="col-12 text-center">
                                                        <div class="button-center">
                                                       <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary mt-4" /> 
                                                        <button type="button" name="btn1" id="btn1"class="quick-btn mt-3" >Submit</button>
                                                        </div>
                                                    </div> -->

                                                </div>
                                            </form>
                                            <div id="dv_res1"></div>
                                        </div>
                                        <div id="res_loader"></div>
                                        <div id="dv_data"></div>

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

    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let from_dt = $("#from_dt").val();
        let to_dt = $("#to_dt").val();
        let verify_status = $("#verify_status").val();
        $.ajax({
            url: "<?php echo base_url('ManagementReports/DA/DA/verification_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                from_dt: from_dt,
                to_dt: to_dt,
                verify_status: verify_status,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(response) {
                updateCSRFToken();
                $('#dv_res1').html(response);

                $("#csrf_token").val(response.csrfHash);
                $("#csrf_token").attr('name', response.csrfName);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });

    function save_verification(dno) {

        var r = confirm("Are you Verfied this case");
        if (r == true) {
            if ($("#rremark_" + dno).val() == 'R' && $("#reject_remark_" + dno).val() == "") {
                alert("Please Entry Valid Rejection Reason");
                return false;
            }
            var rremark = $("#rremark_" + dno).val();
            var rejection_remark = $("#reject_remark_" + dno).val();
            var cl_date = $("#" + dno).data('cl_date');
            var dataString = "dno=" + dno + "&rremark=" + rremark + "&rejection_remark=" + rejection_remark + "&cl_date=" + cl_date;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('ManagementReports/DA/DA/response_case_remarks_verification'); ?>",
                data: dataString + '&' + CSRF_TOKEN + '=' + CSRF_TOKEN_VALUE,
                cache: false,
                success: function(data) {
                    // alert(data);
                    updateCSRFToken();
                    if (data == 1) {
                        var r = "#" + dno;
                        var row = "<tr><td colspan='7' style='text-align:center;color:red;'>DN : " + dno + " Verified Successfully</td></tr>";
                        $(r).replaceWith(row);
                    } else {
                        alert("Not Verified.");
                    }
                }
            }).fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
        } else {

            txt = "You pressed Cancel!";
        }

    }
</script>