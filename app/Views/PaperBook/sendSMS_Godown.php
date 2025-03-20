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
                                <h3 class="card-title">Paper Book</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">SMS Godown</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="usercode" id="usercode" value="<?php echo $_SESSION['login']['usercode']; ?>" />
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="submit" id="sendSMS" class="quick-btn mt-26">Send Godown SMS</button>
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
    $("#sendSMS").click(function (e) {
        e.preventDefault();
        var usercode = $('#usercode').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('PaperBook/PaperBooksSMS/sms_godown'); ?>",
            type: "POST",
            beforeSend: function() {
                $('#result_main').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                usercode: usercode,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(r) {
               
                if (r != 0) {
                    alert('Message has been send.');
                } else {
                    alert("There is some problem while sending message please contact computer cell...");
                }
                updateCSRFToken();
            },
            error: function() {
                alert('ERROR');
                updateCSRFToken();
            }
        });
    });
</script>