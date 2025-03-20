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
    .subct4,
    #res_on_off,
    #resh_from_txt {
        display: none;
    }

    .toggle_btn {
        text-align: left;
        color: #00cc99;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
    }

    div,
    table,
    tr,
    td {
        font-size: 10px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Category Year Wise Pendency (including defects)</h3>
                        </div>
                    </div>
                </div>
                <form name="frm" id="frm">
                <?= csrf_field() ?>
                    <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                    <div id="dv_content1">
                        <div style="text-align: center">
                            <?php
                            $file_list = "";
                            $cntr = 0;
                            $chk_slno = 0;
                            $chk_pslno = 0;
                            $temp_msg = "";


                            ?>

                            <div id="rightcontainer" align="center">
                                <div id="s_box" align="center">

                                    <table align="center" cellspacing="1" cellpadding="2" border="0" width="100%">
                                        <tr>
                                            <th>
                                                <input class="pdbutton btn btn-primary quick-btn" type="button" name="bt11" value="Submit" onclick='get_pending_data();'>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <hr>
                                            </th>
                                        </tr>
                                    </table>



                                </div>
                                <div id="r_box" align="center"></div>

                            </div>
                        </div>

                </form>
            </div>
        </div>

        <div id="res_loader"></div>
    </div>

    <div id="dv_res1"></div>
</div>
</div>
</section>



<script>
    $(function() {
        $("#ldates").datepicker();
    });


    function get_pending_data() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $("input.pdbutton").attr("disabled", false);
        $.ajax({
            type: "POST",
            data: {
               
                CSRF_TOKEN: csrf,
            },
            
            url: '<?php echo base_url('ManagementReports/Report/categoryProcessYear'); ?>',
            beforeSend: function(xhr) {
                $("#r_box").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
            },
            success: function(msg) {
                updateCSRFToken();
                document.getElementById("r_box").innerHTML = msg;
                $("input.pdbutton").attr("disabled", false);
            },
            error: function() {
                updateCSRFToken();
                alert("ERROR");
            }
        });


    }

    function CallPrint(strid) {
        document.getElementById('cmdPrnRqs2').style.display = 'none';
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('cmdPrnRqs2').style.display = 'block';
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
    }
</script>