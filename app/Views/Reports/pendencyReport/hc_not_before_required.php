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
    .table-striped tr:nth-child(odd) td {
        background: #ECEEF2 !important;
    }
    .table-striped tr:nth-child(even) td {
        background: #ffffff;
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
                                <h3 class="card-title">HC Not Before Required</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row card-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'coramDelFormId',
                                'autocomplete' => 'off',
                                'enctype' => 'multipart/form-data',
                                'method' => 'post',
                                'target' => '_blank'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <!--<div class="form-group row">
                                <div class="col-md-2" style="max-width: 9%;">
                                    <label for="m_dept">Mainhead</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R
                                </div>
                                
                                <div class="col-md-2" style="overflow: hidden;padding-top: 10px;" align="center">
                                    <input type="button" id="btngetr" name="btngetr" value="Get">
                                </div>
                            
                            </div>-->

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="m_dept">Mainhead</label>
                                    <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M
                                    <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R
                                    <input type="button" id="btngetr" name="btngetr" value="Get" class="btn btn-primary">
                                </div>
                                
                            </div>  
                            <?= form_close()?>  
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mt-2">
                            <div id="dv_res1"></div>
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


    $(document).on("click","#btngetr",function()
    {
        $('#dv_res1').html("");
        var mainhead = get_mainhead();
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type:"POST",
            data:
            {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                mainhead: mainhead
            },
            url: "<?= site_url('Reports/PendencyReport/DetailedPendency/hcNotBeforeRequiredGet') ?>",
            beforeSend: function(xhr) {
                $("#btngetr").attr("disabled", true);
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                updateCSRFToken();
                $("#btngetr").attr("disabled", false);
                $("#dv_res1").html('');
                $("#dv_res1").html(response);
            },
            error: function(xhr, status, error)
            {
                updateCSRFToken();
                $("#dv_res1").html('');
                $("#btngetr").attr("disabled", false);
                alert( "Error Occured, contact server room" );
                return false;
            }
        });
    });

    function get_mainhead()
    {
        var mainhead = "";
        $('input[type=radio]').each(function () {
            if($(this).attr("name")=="mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click","#prnnt1",function()
    {
        var prtContent = $("#prnnt").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write("<style> .bk_out {  display:none; } </style>");
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>