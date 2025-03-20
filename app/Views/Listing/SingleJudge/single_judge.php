<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
    }

    .toggle_btn {
        text-align: left;
        color: #00cc99;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
    }

    .class_red {
        color: red;
    }

    .class_green {
        color: green;
    }
</style>
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">Single Judge - Final Allocation Module</h5>
                    </div>

                    <div class="card-body">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div id="dv_content1">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="next_dt">Listing Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="next_dt" id="next_dt"
                                                    autocomplete="off" readonly>
                                                <span id="error_next_dt" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="btn_click">&nbsp;</label> <br>
                                                <input type="button" name="btn_click" id="btn_click" value="Get Details"
                                                    class="btn btn-primary w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="msg" style="padding-top: 5px;"></div>
            </div>
            <div id="single_judges_final_allocation_inputs" class="col-12 mt-3"> </div>
        </div>

    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $(document).ready(function(){
        $('#next_dt').datepicker({
            format: 'dd-mm-yyyy',
            startDate: new Date(),
            autoclose:true
        });

    });

    $(document).on('click', '#btn_click', function(e) {
        $('.msg').html('');
        $('#error_next_dt').html("");
        $("#next_dt").removeClass("input_error");
        var next_dt = $('#next_dt').val();

        var CSRF_TOKEN = 'CSRF_TOKEN';

        if (next_dt == '') {

            $("#next_dt").focus();
            $("#next_dt").addClass("input_error");
            $('#error_next_dt').html('<p style="color:red;">Listing Date Required.</p>').show();
            return false;
        } else {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('Listing/Allocation/singleJudgeFinalGet'); ?>",
                data: {
                    next_dt: next_dt,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $("#single_judges_final_allocation_inputs").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $("#single_judges_final_allocation_inputs").html(data);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });

    $(document).on('change', '#all_roster', function() {
        if (this.checked) {
            $(".chk_roster").each(function() {
                this.checked = true;
            })
        } else {
            $(".chk_roster").each(function() {
                this.checked = false;
            })
        }
    });

    $(document).on('change', '#all_lp', function() {
        if (this.checked) {
            $(".chk_lp").each(function() {
                this.checked = true;
            })
        } else {
            $(".chk_lp").each(function() {
                this.checked = false;
            })
        }
    });

    $(document).on('click', '#btn_allocate', function() {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var formValues = $("#single_judge_final_form").serialize() + "&CSRF_TOKEN=" + CSRF_TOKEN_VALUE;
        var params = new URLSearchParams(formValues);
        var next_dt = params.get('next_dt_selected');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Listing/Allocation/singleJudgeFinalAllocationAction'); ?>",
            beforeSend: function() {
                $(".msg").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: formValues,
            cache: false,
            dataType: "JSON",
            success: function(data) {
                if (data.status == 'success') {
                    updateCSRFToken();
                    //$("#btn_click").click();
                    singleJudgeFinalGet();
                    $('.msg').html('<div class="callout alert alert-success text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');
                } else {
                    updateCSRFToken();
                    console.log(data.status);
                    $('.msg').html('<div class="callout alert alert-danger text-bold"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;' + data.msg + '</div>');
                }
            }
        });
    });

    $(document).on("click", "#print_action", function() {
        var prtContent = $("#print_area").html();
        var temp_str = prtContent;

        var a = window.open('', '', 'height=500, width=500');

        a.document.write('<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css"><html>');
        a.document.write('<body >');
        a.document.write(temp_str);
        a.document.write('</body></html>');
        a.focus();
        a.document.close();
        a.print();
        return false;
    });

    async function singleJudgeFinalGet(){
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var next_dt = $('#next_dt').val();
        $.ajax({

            url: "<?php echo base_url('Listing/Allocation/singleJudgeFinalGet'); ?>",
            data: {
                next_dt: next_dt,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $("#single_judges_final_allocation_inputs").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $("#single_judges_final_allocation_inputs").html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
</script>