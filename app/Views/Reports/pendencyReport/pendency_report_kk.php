<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Pendency Report (New) </h3>
                            </div>
                        </div>
                    </div>



                    <form name="frm" id="frm">
                    <?= csrf_field() ?>
                        <input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />
                        <div id="dv_content1" class="container mt-4">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="text-center">
                                        <?php
                                        $file_list = "";
                                        $cntr = 0;
                                        $chk_slno = 0;
                                        $chk_pslno = 0;
                                        $temp_msg = "";
                                        ?>

                                        <div id="rightcontainer" class="text-center">
                                            <div id="s_box" class="my-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="dtd1" class="font-weight-bold">Between Dates:</label>
                                                        <input class="form-control dtp" type="text" value="<?php echo $dtd; ?>" name="dtd1" id="dtd1" readonly="readonly">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="dtd2" class="font-weight-bold">and</label>
                                                        <input class="form-control dtp" type="text" value="<?php echo $dtd; ?>" name="dtd2" id="dtd2" readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button class="btn btn-primary" type="button" name="bt11" onclick="get_pending_data();">Submit</button>
                                                </div>
                                            </div>

                                            <div id="r_box" class="mt-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>




                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<script>
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

    function get_pending_data() {
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dt = document.getElementById("dtd1").value;
        var dt1 = dt.split("-");
        var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var dt2 = document.getElementById("dtd2").value;
        var dt21 = dt2.split("-");
        var dt_new2 = dt21[2] + "-" + dt21[1] + "-" + dt21[0];
        var url = "<?php echo base_url('Reports/PendencyReport/Reports/pendency_report_process_kk'); ?>";
        $("input.pdbutton").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: url,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dt1: dt_new1,
                dt2: dt_new2
            },
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
</script>
<script>
    $(function() {
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