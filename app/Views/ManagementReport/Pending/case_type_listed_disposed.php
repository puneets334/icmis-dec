<?= view('header'); ?>
<style>
      .modal .modal-header {
        position:relative !important;
        border-bottom: 1px solid #e9ecef;
    }
</style>    
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Case Type Wise Listed and Disposed</h3>
                            </div>
                            <?//= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                                <label for="" class="mt-2">Listing Dates</label>
                                <div class="col-md-2">
                                    <?php
                                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                    $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                                    ?>
                                    <input type="text" size="10" class="dtp form-control" name='start_dt' id='start_dt' value="<?php echo $next_court_work_day; ?>" readonly />
                                </div>
                                <label for="" class="mt-2">AND</label>
                                <div class="col-md-2">
                                    <input type="text" size="10" class="dtp form-control" name='end_dt' id='end_dt' value="<?php echo $next_court_work_day; ?>" readonly />
                                </div>
                                <div class="col-md-2">
                                    <input type="button" class="btn btn-primary quick-btn" vname="btn1" id="btn1" value="Submit">
                                </div>
                                <div id="res_loader"></div>
                            </div>
                            <div id="dv_res1"></div>
                        </div>
                        <div id="overlay" style="display:none;">&nbsp;</div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function close_wcs() {
        var divname = "";
        divname = "newcs";
        document.getElementById(divname).style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }


    $(document).on("click", "#prnt2", function() {
        var prtContent = $("#prnnt2").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write("<style> .bk_out {  display:none; } </style>");
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });

    function call_fcs1(ct, flag) {
        var start_dt = $("#start_dt").val();
        var end_dt = $("#end_dt").val();
        var divname = "";
        divname = "newcs";
        var divClassName =  document.getElementById(divname).style.display = 'block';
        $("#newcs").addClass("show");

        // $('#' + divname).width($(window).width() - 150);
        // $('#' + divname).height($(window).height() - 120);
        // $('#newcs123').height($('#newcs').height() - $('#newcs1').height() - 50);
        // var newX = ($('#' + divname).width() / 2);
        var newY = ($('#' + divname).height() / 2);
        // document.getElementById(divname).style.marginLeft = "-" + newX + "px";
        // document.getElementById(divname).style.marginTop = "-" + newY + "px";
        document.getElementById(divname).style.display = 'block';
        document.getElementById(divname).style.zIndex = 10;
        $('#overlay').height($(window).height());
        document.getElementById('overlay').style.display = 'block';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('/ManagementReports/Pending/get_ct_listed_disposed_popup'); ?>",
                beforeSend: function(xhr) {
                    $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
                data: {
                    CSRF_TOKEN: csrf,
                    ct: ct,
                    flag: flag,
                    start_dt: start_dt,
                    end_dt: end_dt
                }
            })
            .done(function(msg) {
                updateCSRFToken();
                $("#newcs123").html(msg);
            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
        updateCSRFToken();
    }

    function call_fcs(ct, flag) {
        var start_dt = $("#start_dt").val();
        var end_dt = $("#end_dt").val();
        
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('/ManagementReports/Pending/get_ct_listed_disposed_popup'); ?>",
                beforeSend: function(xhr) {
                    $("#modData").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    ct: ct,
                    flag: flag,
                    start_dt: start_dt,
                    end_dt: end_dt
                }
            })
            .done(function(msg) {
                updateCSRFToken();
                $("#modData").html(msg);
            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
        updateCSRFToken();
    }

    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var start_dt = $("#start_dt").val();
        var end_dt = $("#end_dt").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('/ManagementReports/Pending/get_ct_listed_disposed') ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                start_dt: start_dt,
                end_dt: end_dt
            },
            beforeSend: function() {
                $("#btn1").attr("disabled", true);
                $("#dv_res1").html("<center><img src='../../images/load.gif' alt='Loading...' title='Loading...' /></center>");
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
                $("#btn1").attr("disabled", false);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
                $("#btn1").attr("disabled", false);
            }
        });
        updateCSRFToken();
    }

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>