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
                                <h3 class="card-title">Report of Department-wise Cases</h3>
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
                                'id' => 'dept_wise_rpt_form',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="m_dept">Main Department</label>
                                    <select class="form-control" id="m_dept">
                                        <option value="">ALL</option>
                                        <?php
                                        foreach ($main_department as $row_maindep)
                                        {
                                            echo '<option value="' . $row_maindep['dm'] . '">' . $row_maindep['dm'].' - '.$row_maindep['deptname'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="s_dept1">Sub Department 1</label>
                                    <select class="form-control" id="s_dept1">
                                        <option value="">ALL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 caseNum">
                                    <label for="fdate">From Date</label>
                                    <input class="form-control dtp" type="text" class="dtp" id="fdate">
                                </div>

                                <div class="col-md-2 caseNum">
                                    <label for="tdate">To Date</label>
                                    <input class="form-control dtp" type="text" class="dtp" id="tdate">
                                </div>

                                <div class="col-md-2 caseNum"  style="overflow: hidden;padding-top: 26px;">
                                    <select class="form-control" id="status">
                                        <option value="">Choose Status</option>
                                        <option value="P">Pending</option>
                                        <option value="D">Disposed</option>
                                    </select>
                                </div>

                                <div class="col-md-2 caseNum" style="overflow: hidden;padding-top: 26px;">
                                    <select class="form-control" id="rstatus">
                                        <option value="A">ALL Reply/NO Reply</option>
                                        <option value="R">Reply Filed</option>
                                        <option value="RN">Reply Not Filed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1" style="padding-top: 26px;">
                                    <input type="button" value="Show Cases" name="show" style="padding: 10px;">
                                </div>
                                <div class="col-md-2" style="overflow: hidden;padding-top: 26px;padding-left: 40px;">
                                    <input type="button" value="Export in Excel" name="export" style="padding: 10px;">
                                </div>
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive" id="result"></div>
                        </div>
                        <input type="hidden" name="hd_for_mdept" id="hd_for_mdept"/>
                        <input type="hidden" name="hd_for_sdept" id="hd_for_sdept"/>
                        <input type="hidden" name="hd_for_fdate" id="hd_for_fdate"/>
                        <input type="hidden" name="hd_for_tdate" id="hd_for_tdate"/>
                        <input type="hidden" name="hd_for_sts" id="hd_for_sts"/>
                        <input type="hidden" name="hd_for_city" id="hd_for_city"/>
                        <input type="hidden" name="hd_for_rstatus" id="hd_for_rstatus"/>
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
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }

    $(document).ready(function()
    {
        $("#m_dept").change(function()
        {
            let mdept = $("#m_dept").val();
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: 'POST',
                data: 
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    mdept:mdept
                },
                url: "<?= site_url('Reports/DepartmentWise/DepartmentWiseController/getSubdept1') ?>",
                beforeSend: function ()
                {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(response)
                {
                    if(response.status == true)
                    {
                        $("#loader").html('');
                        $("#s_dept1").html(response.data);
                    }
                    else
                    {
                        $("#loader").html('');
                        $("#s_dept1").html(response.data);
                    }
                    updateCSRFToken();
                },
                error: function(xhr, status, error)
                {
                    $("#loader").html('');
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                }
            });
        });

        $("input[name=show]").click(function()
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if($("#status").val()=='')
            {
                alert('Please Select Status');
                $("#status").focus();
                return false;
            }
            $.ajax({
                type:"POST",
                data:
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    mdept:$("#m_dept").val(),
                    sdept1:$("#s_dept1").val(),
                    fdate:$("#fdate").val(),
                    tdate:$("#tdate").val(),
                    cst:$("#status").val()
                    /*,city:$("#city").val()*/
                    ,
                    rsta:$("#rstatus").val()
                },
                url: "<?= site_url('Reports/DepartmentWise/DepartmentWiseController/getDepartmentWiseRPT') ?>",
                beforeSend: function(xhr)
                {
                    $("#result").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(response)
                {
                    updateCSRFToken();
                    $("#result").html('');
                    $("#result").html(response.data);
                },
                error: function(xhr, status, error)
                {
                    updateCSRFToken();
                    $("#result").html('');
                    alert( "Error Occured, contact server room" );
                    return false;
                }
            });
        });

        $("input[name=export]").click(function(event)
        {
            let CSRF_TOKEN = 'CSRF_TOKEN';
            let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if($("#status").val()=='')
            {
                alert('Please Select Status');
                $("#status").focus();
                return false;
            }
            $("#hd_for_mdept").val($("#m_dept").val());
            $("#hd_for_sdept").val($("#s_dept1").val());
            $("#hd_for_fdate").val($("#fdate").val());
            $("#hd_for_tdate").val($("#tdate").val());
            $("#hd_for_sts").val($("#status").val());
            //$("#hd_for_city").val($("#city").val());
            $("#hd_for_rstatus").val($("#rstatus").val());

            // $('#dept_wise_rpt_excel_export').submit();
            $.ajax({
                type: 'POST',
                data: 
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    hd_for_mdept: $("#hd_for_mdept").val(),
                    hd_for_sdept: $("#hd_for_sdept").val(),
                    hd_for_fdate: $("#hd_for_fdate").val(),
                    hd_for_tdate: $("#hd_for_tdate").val(),
                    hd_for_sts: $("#hd_for_sts").val(),
                    hd_for_rstatus: $("#hd_for_rstatus").val(),
                },
                url: "<?= site_url('Reports/DepartmentWise/DepartmentWiseController/departmentRPTExcel') ?>",
                beforeSend: function ()
                {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data, textStatus, jqXHR)
                {
                    $("#loader").html('');
                    updateCSRFToken();
                    // On success, you will get the file, we will create a hidden iframe for download
                    var blob = new Blob([data], { type: 'application/xls' });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'dept_data_' + new Date().toISOString() + '.xls'; // Set dynamic file name
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $("#loader").html('');
                    updateCSRFToken();
                    alert("Error: Unable to generate the Excel file. Please Contact Server Room");
                }
            });

            /*$.ajax({
                url: "<?= site_url('Reports/DepartmentWise/DepartmentWiseController/generateExcel') ?>", // Controller URL
                method: "POST", // Using GET here, but you can use POST if needed
                data: 
                {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },
                success: function (data, textStatus, jqXHR) {
                    // On success, you will get the file, we will create a hidden iframe for download
                    var blob = new Blob([data], { type: 'application/xls' });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'dept_data_' + new Date().toISOString() + '.xls'; // Set dynamic file name
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error: Unable to generate the Excel file.");
                }
            });*/
        });
    });
</script>