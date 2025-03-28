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
    .disablePreBtnForFisrtPage
    {
        cursor: not-allowed;
        pointer-events: none;
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
                                <h3 class="card-title">Received / Dispatched Between Dates</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <?php
                        $attribute = array(
                            'class' => 'form-horizontal appearance_search_form',
                            'id' => 'frm',
                            'name' => 'frm',
                            'autocomplete' => 'off',
                            'enctype'=>'multipart/form-data',
                            'method' => 'post',
                            'style' => 'width:100%'
                        );
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="causelistDate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input name="rd" id="rd" value="R" checked="" type="radio">Received&nbsp;
                                    <input name="rd" id="rd" value="D" type="radio">Dispatched&nbsp;&nbsp;
                                </div>
                                <div class="col-md-3">
                                    <label for="causelistDate">Misc./Regular</label>
                                    <select class="form-control" name="mf" id="mf" onchange="get_ct();">
                                        <option value="all">All</option>
                                        <option value="M">Miscellaneous</option>
                                        <option value="F">Regular</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="causelistDate">Registered / Un-Registered</label>
                                    <select class="form-control" name="rur" id="rur">
                                        <option value="all">All</option>
                                        <option value="R">Registered</option>
                                        <option value="U">Un-Registered</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="causelistDate">Case Type</label>
                                    <select class="form-control" name="ct" id="ct">
                                        <option value="all">All</option>
                                        <?php
                                        foreach ($cases as $case)
                                        {
                                            echo '<option value="' . $case["casecode"] . '">' . $case["casename"] . '</option>';
                                        }
                                        ?>
                                        <option value="all">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="causelistDate">Start Date</label>
                                    <input type="text" name="dtd1" id="dtd1" class="form-control datepick" required placeholder="Causelist Date" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="causelistDate">End Date</label>
                                    <input type="text" name="dtd2" id="dtd2" class="form-control datepick" required placeholder="Causelist Date" value="">
                                </div>
                                <div class="col-md-1">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" class="btn btn-primary" name="bt11" value="Submit" style="width: 100%" onclick="get_data();">Submit</button>
                                </div>
                            </div>
                        </div>
                        <?= form_close()?>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="r_box" align="center" ></div>
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
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    function get_ct()
    {
        var mf = document.getElementById("mf").value;
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        console.log('get_ct '+CSRF_TOKEN_VALUE);
        $("input.pdbutton").attr("disabled", true);
        $.ajax({
            type: 'POST',
            data: 
            { 
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                mf: mf
            },
            url: "<?= site_url('Exchange/FileMovement/getCaseTypeByMisOrReg') ?>",
            beforeSend: function ()
            {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                $("input.pdbutton").attr("disabled", false);
                if(response.status == true)
                {
                    $("#loader").html('');
                    $('#ct').html('');
                    var options = '<option value="all">All</option>';
                    $.each(response.data, function(index, caseItem)
                    {
                        options += '<option value="' + caseItem.casecode + '">' + caseItem.short_description + '</option>';
                    });
                    $('#ct').html(options);
                }
                updateCSRFToken();
            },
            error: function(xhr, status, error)
            {
                $("input.pdbutton").attr("disabled", false);
                $("#loader").html('');
                // alert("Error: " + xhr.status + " " + xhr.statusText);
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            }
        });
    }

    function get_data()
    {
        $("#r_box").html('');
        if (document.frm.rd[0].checked)
        {
            var rd = document.frm.rd[0].value;
        }
        if (document.frm.rd[1].checked)
        {
            var rd = document.frm.rd[1].value;
        }
        var mf = document.getElementById("mf").value; 
        var rur = document.getElementById("rur").value;
        var ct = document.getElementById("ct").value;
        var dt = document.getElementById("dtd1").value;
        var dt1 = dt.split("-");
        var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var dt2 = document.getElementById("dtd2").value;
        var dt21 = dt2.split("-");
        var dt_new2 = dt21[2] + "-" + dt21[1] + "-" + dt21[0];

        let CSRF_TOKEN_TWO = 'CSRF_TOKEN';
        let CSRF_TOKEN_TWO_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            data: 
            { 
                CSRF_TOKEN: CSRF_TOKEN_TWO_VALUE,
                rd : rd, 
                mf : mf, 
                rur : rur, 
                ct : ct, 
                dt1: dt_new1, 
                dt2: dt_new2
            },
            url: "<?= site_url('Exchange/FileMovement/dispatchReceiveReportProcess') ?>",
            beforeSend: function ()
            {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(response)
            {
                $("#loader").html('');
                $("#r_box").html(response);
                if($('#inc_count').val() == 1) {
                    $('#btn_left').attr('disabled',true);
                }
                updateCSRFToken();
            },
            error: function(xhr, status, error)
            {
                $("#loader").html('');
                $("#r_box").html('');
                // alert("Error: " + xhr.status + " " + xhr.statusText);
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            }
        });
    }

    $(document).ready(function()
    {
        $(document).on('click','#btn_left',function()
        {
            $('#btn_left').attr('disabled',true);
            if (document.frm.rd[0].checked)
            {
                var rd = document.frm.rd[0].value;
            }
            if (document.frm.rd[1].checked)
            {
                var rd = document.frm.rd[1].value;
            }

            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var mf = document.getElementById("mf").value;
            var rur = document.getElementById("rur").value;
            var ct = document.getElementById("ct").value;
            var dt = document.getElementById("dtd1").value;
            var dt1 = dt.split("-");
            var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
            var dt2 = document.getElementById("dtd2").value;
            var dt21 = dt2.split("-");
            var dt_new2 = dt21[2] + "-" + dt21[1] + "-" + dt21[0];
            var ct_count = parseInt($('#inc_count').val());
            var hd_fst = parseInt($('#hd_fst').val());
            var inc_val = parseInt($('#inc_val').val());
            var inc_tot = parseInt($('#inc_tot').val());

            var sp_frst = parseInt($('#sp_frst').html()) - inc_val;
            var inc_tot_pg = sp_frst - 1;
            if($('#btn_right').is(':disabled'))
            {
                $('#btn_right').attr('disabled',false);
            }
            var nw_hd_fst = hd_fst - inc_val;
            $('#inc_count').val(ct_count - 1);
            if($('#inc_count').val() == 1)
            {
                $('#btn_left').attr('disabled',true);
            }
            $.ajax({
                url:"<?= site_url('Exchange/FileMovement/dispatchReceiveReportProcess') ?>",
                type:"GET",
                cache:false,
                async:true,
                beforeSend:function()
                {
                    $("#r_box").empty();
                    $("#r_box").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data:
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    nw_hd_fst: nw_hd_fst,
                    inc_val: inc_val,
                    u_t: 1,
                    inc_tot_pg: inc_tot_pg,
                    rd: rd,
                    mf: mf,
                    rur: rur,
                    ct: ct,
                    dt1: dt_new1,
                    dt2: dt_new2
                },
                success:function(data,status)
                {
                    $("#r_box").empty();
                    $('#r_box').html(data);
                    $('#hd_fst').val(nw_hd_fst);
                    $('#sp_frst').html(sp_frst);
                    $('#sp_last').html(hd_fst);

                    if(sp_frst == 1)
                    {
                        $('#btn_left').attr('disabled',true);
                    }
                    else
                    {
                        $('#btn_left').attr('disabled',false);
                    }
                    updateCSRFToken();
                },
                error:function(xhr)
                {
                    updateCSRFToken();
                    $("#r_box").empty();
                    alert("Error: "+xhr.status+' '+xhr.statusText);
                    return false;
                }
            });
        });

        $(document).on('click','#btn_right',function()
        {
            $('#btn_right').attr('disabled',true);

            if (document.frm.rd[0].checked)
            {
                var rd = document.frm.rd[0].value;
            }
            if (document.frm.rd[1].checked)
            {
                var rd = document.frm.rd[1].value;
            }

            var mf = document.getElementById("mf").value;
            var rur = document.getElementById("rur").value;
            var ct = document.getElementById("ct").value;
            var dt = document.getElementById("dtd1").value;
            var dt1 = dt.split("-");
            var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
            var dt2 = document.getElementById("dtd2").value;
            var dt21 = dt2.split("-");
            var dt_new2 = dt21[2] + "-" + dt21[1] + "-" + dt21[0];
            var ct_count=parseInt($('#inc_count').val());
            var hd_fst=parseInt($('#hd_fst').val());
            var inc_val=parseInt($('#inc_val').val());
            var inc_tot=parseInt($('#inc_tot').val());
            var inc_tot_pg=parseInt($('#inc_tot_pg').val());
            if(hd_fst == 0)
            {
                $('#btn_left').attr('disabled',true);
            }
            var nw_hd_fst = hd_fst + inc_val;
            if(ct_count == inc_tot - 1)
            {
                $('#btn_right').attr('disabled',true);
            }
            $.ajax({
                url:"<?= site_url('Exchange/FileMovement/dispatchReceiveReportProcess') ?>",
                type:"GET",
                cache:false,
                async:true,
                beforeSend:function()
                {
                    $("#r_box").empty();
                    $("#r_box").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data:
                {
                    nw_hd_fst:nw_hd_fst,
                    inc_val:inc_val,
                    u_t:1,
                    inc_tot_pg:inc_tot_pg,
                    rd : rd,
                    mf : mf,
                    rur : rur,
                    ct : ct,
                    dt1: dt_new1,
                    dt2: dt_new2
                },
                success:function(data,status)
                {
                    $("#r_box").empty();
                    $('#r_box').html(data);
                    $('#inc_count').val(ct_count+1);
                    $('#hd_fst').val(nw_hd_fst);

                    $('#sp_frst').html(parseInt($('#hd_fst').val())+1);

                    var sp_last_ck= parseInt($('#hd_fst').val())+inc_val;
                    var sp_nf = parseInt($('#sp_nf').html());
                    if(sp_last_ck<=sp_nf)
                    {
                        $('#sp_last').html(parseInt($('#hd_fst').val())+inc_val);
                        $('#btn_right').attr('disabled',false);
                    }
                    else
                    {
                        $('#sp_last').html(sp_nf);
                        $('#btn_right').attr('disabled',true);
                    }
                    updateCSRFToken();
                },
                error:function(xhr)
                {
                    updateCSRFToken();
                    $("#r_box").empty();
                    alert("Error: "+xhr.status+' '+xhr.statusText);
                    return false;
                }
            });
        });
    });

    function CallPrint(strid)
    {
        document.getElementById('cmdPrnRqs2').style.display= 'none';
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
     
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('cmdPrnRqs2').style.display= 'block';
    }
</script>