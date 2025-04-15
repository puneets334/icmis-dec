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
                                <h3 class="card-title">Coram Given By</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
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
                                            <div id="dv_content1">
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-1 mt-1">
                                                        <?php field_mainhead(); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="m_dept">Judge</label>
                                                        <select class="form-control" id="judge" name="judge">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            foreach ($judges as $ct_rw) {
                                                                echo '<option value="' . $ct_rw['jcode'] . '">' . $ct_rw['jname'].' ('.$ct_rw['abbreviation'].')' . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="m_dept">Detail</label>
                                                        <select class="form-control" id="crm_dtl" name="crm_dtl">
                                                            <option value="0">ALL</option>                    
                                                            <option value="1">Coram Given by CJI</option>
                                                            <option value="2">Special Bench Coram Given by CJI</option>
                                                            <option value="3">Special Bench</option>
                                                            <option value="4">Part Heard</option>
                                                            <option value="5">Other</option>
                                                        </select> 
                                                    </div>
                                                    <div class="col-md-2 mt-26">
                                                        <input type="button" class="btn btn-primary" name="btngetr" value="Get" id="btngetr"/>
                                                    </div>
                                                </div>
                                                <div id="res_loader"></div>

                                                <div id="dv_res1"></div>
                                            </div>
                                            <?php echo form_close(); ?>
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
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var judge = $("#judge").val();    
        var crm_dtl = $("#crm_dtl").val();
        var mainhead = get_mainhead();
        if(judge == 0) {
            alert('Please Select Judge Name');
            return false; 
        }

        $.ajax({
            type:"POST",
            data: {
                judge: judge,
                crm_dtl: crm_dtl,
                mainhead: mainhead,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            url: "<?= site_url('Reports/PendencyReport/CoramGivenBy/removeCoram') ?>",
            beforeSend: function(xhr) {
                $("#btngetr").attr("disabled", true);
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response) {
                updateCSRFToken();
                $("#btngetr").attr("disabled", false);
                $("#dv_res1").html('');
                $('#dv_res1').html(response);
                
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                $("#btngetr").attr("disabled", false);
                $("#dv_res1").html('');
                alert( "Error Occured, contact server room" );
                return false;
            }
        });
    });

    function get_mainhead()
    {
        var mainhead = "";
        $('input[type=radio]').each(function ()
        {
            if($(this).attr("name")=="mainhead" && this.checked)
            {
                mainhead = $(this).val();
            }
        });
        return mainhead;
    }

    $(document).on("click","#prnnt1",function()
    {
        var prtContent = $("#prnnt").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>