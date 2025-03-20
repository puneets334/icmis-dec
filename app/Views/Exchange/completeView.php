<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }
    .centerview
    {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
    }
    .nofound
    {
        text-align: center;
        color: red;
        font-size: 17px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Complete View</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                       <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'filingTrapComViewId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="causelistDate">Diary No.</label>
                                    <input class="form-control" type="text" id="dno" maxlength="6" size="5"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="causelistDate">Year</label>
                                    <input class="form-control" type="text" id="dyr" maxlength="4" size="4" value="<?php echo date('Y');?>"/>
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="showbutton" class="btn btn-info" style="width: 100%">View</button>
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="result"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function()
    {
        $("#showbutton").click(function()
        {
            var diaryno, diaryyear;
            var regNum = new RegExp('^[0-9]+$');
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if(!regNum.test(diaryno))
            {
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }

            if(!regNum.test(diaryyear))
            {
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }

            if(diaryno == 0)
            {
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }

            if(diaryyear == 0)
            {
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
            
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                data: 
                { 
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno: diaryno,
                    dyr: diaryyear
                },
                url: "<?= site_url('Exchange/Filingtrap/getTrap') ?>",
                beforeSend: function ()
                {
                    $("#result").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result)
                {
                    $("#result").html('');
                    $("#result").html(result);
                    updateCSRFToken();
                },
                error: function(xhr, status, error)
                {
                    $("#result").html('');
                    // alert("Error: " + xhr.status + " " + xhr.statusText);
                    alert("ERROR, Please Contact Server Room");
                    updateCSRFToken();
                }
            });
        });
    });


</script>