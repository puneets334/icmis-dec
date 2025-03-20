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
                                <h3 class="card-title">Filing Trap</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Complete View</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Diary No.</label>
                                                        <input type="text" id="dno" maxlength="6" class="form-control" size="5" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="">Year</label>
                                                        <input type="text" id="dyr" maxlength="4" class="form-control" size="4" value="<?php echo date('Y'); ?>" />
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="result"></div>
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


    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var diaryno, diaryyear;
        var regNum = new RegExp('^[0-9]+$');
        diaryno = $("#dno").val();
        diaryyear = $("#dyr").val();
        if (!regNum.test(diaryno)) {
            alert("Please Enter Diary No in Numeric");
            $("#dno").focus();
            return false;
        }
        if (!regNum.test(diaryyear)) {
            alert("Please Enter Diary Year in Numeric");
            $("#dyr").focus();
            return false;
        }
        if (diaryno == 0) {
            alert("Diary No Can't be Zero");
            $("#dno").focus();
            return false;
        }
        if (diaryyear == 0) {
            alert("Diary Year Can't be Zero");
            $("#dyr").focus();
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('Filing/FileTrap/get_trap'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#result').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                dno: diaryno,
                dyr: diaryyear,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(response) {
                updateCSRFToken();
                $('#result').html(response);

                $("#csrf_token").val(response.csrfHash);
                $("#csrf_token").attr('name', response.csrfName);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                updateCSRFToken();
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });
</script>