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

    .table_tr_th_w_clr td
    {
        padding:10px;
    }

    @media print
    {
        #cmdPrnRqs2
        {
            display: none;
        }
    }

    #newb
    {
        position: fixed; padding: 12px; left: 50%; top: 50%; display: none; color: black; background-color: #D3D3D3; border: 2px solid lightslategrey; height:100%;
    }
    #newc
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

    .textColor
    {
        color: #072c76;
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
                                <h3 class="card-title">Filing Statistics</h3>
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
                                'id' => 'filing_statistics_form',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-3" style="max-width: 19.5%;">
                                    <label for="m_dept">Start Date:</label>
                                    <input class="form-control dtp" type="text" id="sdate" name="sdate">
                                </div>

                                <div class="col-md-3" style="max-width: 19.5%;">
                                    <label for="m_dept">End Date:</label>
                                    <input class="form-control dtp" type="text" id="edate" name="edate">
                                </div>
                                <div class="col-md-2" style="overflow: hidden;padding-top: 26px;">
                                    <input type="button" name="submit" id="submit_button" value="Fetch Details">
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive" id="res"></div>
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

    $("input[name=submit]").click(function()
    {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let sdate = $('#sdate').val();
        let edate = $('#edate').val();
        if(sdate == '' || edate == '')
        {
            alert('Either Start date or End date is empty');
            $("#sdate").focus();
            $("#edate").focus();
            return false;
        }
        $.ajax({
            type:"POST",
            data:
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                sdate: sdate,
                edate: edate
            },
            url: "<?= site_url('Reports/FilingMonitoring/FilingMonitoringController/index') ?>",
            beforeSend: function(xhr)
            {
                $("#res").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                updateCSRFToken();
                $("#res").html('');
                $("#res").html(response);
            },
            error: function(xhr, status, error)
            {
                updateCSRFToken();
                $("#res").html('');
                alert( "Error Occured, contact server room" );
                return false;
            }
        });
    });

    function printDiv()
    {
        document.getElementById('pritResultBtn').style.display= 'none';
        var prtContent = document.getElementById('res');
        var WinPrint = window.open();

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('pritResultBtn').style.display= 'block';
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }
</script>