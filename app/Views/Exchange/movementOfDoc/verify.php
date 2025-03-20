<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">
<style type="text/css">
    .card-header
    {
        padding: .75rem 0;
    }

    #newb
    {
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
    }
    #overlay
    {
        background-color: #000;
        opacity: 0.7;
        filter:alpha(opacity=70);
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
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
                                <h3 class="card-title">Verify / Defect</h3>
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
                                'id' => 'bulkDispatchId',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div align="center"><?php echo $output_html; ?></div>
                            <br>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <!-- <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="dv_res1"></div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/js/jquery.tokeninput.js"></script>
<script>
    $(function()
    {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            // yearRange: '1950:2050'
        });
    });
</script>

<script>
    function verifyFunction()
    {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var full_data = new Array();
        var full_data_tb = new Array();
        var check = false; var vr='';
        $("input[type='checkbox'][name^='chk']").each(function ()
        {
            if($(this).is(":checked")==true)
            {
                full_data.push($(this).val());
                var thisid=$(this).attr('id');
                thisid=thisid.replace('chk','');
                full_data_tb.push($('#tb'+thisid).val());
                check = true;
            }
        });
        $("input[type='radio'][name^='vr']").each(function ()
        {
            if($(this).is(":checked")==true)
            {
                vr = $(this).val();
            }
        });
        if(check == false)
        {
            alert("Please select at least one document");
            return false;
        }
        if(vr == '')
        {
            alert("Please select Verify or Reject");
            return false;
        }
        if(vr == 'V')
        {
            var vr_text='Verify';
        }
            
        if(vr == 'R')
        {
            var vr_text='Reject';
        }
            
        vr_text = "Are you sure to "+vr_text+" selected documents";
        if(confirm(vr_text))
        {
            $("#btnrece").prop('disabled','disabled');
            $.ajax({
                type: "POST",
                url: "<?= base_url('Exchange/MovementOfDoc/MovementOfDocument/verifySave'); ?>",
                data:
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    alldata: full_data,
                    vr: vr,
                    tb: full_data_tb
                },
                beforeSend: function()
                {
                    $("#btnrece").prop('disabled', true); // Disable button before sending the request
                },
                success: function(msg)
                {
                    $("#btnrece").removeProp('disabled'); // Enable button on success

                    if (msg != '')
                    {
                        alert('Message from server: ' + msg);
                    }
                    updateCSRFToken();
                    setTimeout(function()
                    { // Wait for 1 second
                        location.reload(); // Reload the page
                    }, 1000);
                },
                error: function()
                {
                    updateCSRFToken();
                    $("#btnrece").removeProp('disabled'); // Enable button on error
                    alert("Error Occurred, Please Contact Server Room");
                }
            });
        }   
    }
</script>