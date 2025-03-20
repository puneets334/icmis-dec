<?= view('header') ?>
<style>
    #grp_hv {
        width: 50px !important;
    }

    #example2_wrapper{
        padding:5px;
    }

    a:link {
        text-decoration: none;
    }

    a:visited {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    a:active {
        text-decoration: underline;
    }

    #newcs {
        position: fixed;
        padding: 12px;
        left: 50%;
        top: 50%;
        display: none;
        color: black;
        background-color: #D3D3D3;
        border: 2px solid lightslategrey;
        height: auto;
    }

    #overlay {
        background-color: #000;
        opacity: 0.7;
        filter: alpha(opacity=70);
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Bunch Matters</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php echo form_open();
                                            csrf_token();
                                            ?>
                                            <div id="dv_content1">
                                                <div class="row">
                                                <div class="col-md-2"></div>
                                                    <div class="col-md-1 mt-1">
                                                        <?php field_mainhead(); ?>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="grp_hv">Group having More Than</label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" size="10" name="grp_hv" id="grp_hv" value="10" />
                                                            <span class="mt-10 ml-1">Cases</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Report Type</label>
                                                        <select class="form-control" name="bunch_type" id="bunch_type">
                                                            <option value="1">Category Wise</option>
                                                            <option value="2">Diary Wise</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mt-26">
                                                        <input type="button" class="btn btn-primary quick-btn" value="Submit" id="btnSubmit" />
                                                    </div>
                                                </div>
                                                <div id="res_loader"></div>

                                                <div id="dv_res1"></div>
                                            </div>
                                            <div id="overlay" style="display:none;">&nbsp;</div>
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
<script>
    $(document).on("click", "#btnSubmit", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var bunch_type = $("#bunch_type").val();
        var grp_hv = $("#grp_hv").val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/bunch_matter_get'); ?>',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                grp_hv: grp_hv,
                bunch_type: bunch_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
            },
            type: 'POST',
            success: function(data, status) {
                $('#dv_res1').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
            }
        });
    }

    function close_wcs() {
        var divname = "";
        divname = "newcs";
        document.getElementById(divname).style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });

    $(document).on("click", "#prnnt2", function() {
        var prtContent = $("#prnnt2").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });

    function call_fcs(d_no1) {

        var divname = "";
        divname = "newcs";
        document.getElementById(divname).style.display = 'block';
        $('#' + divname).width($(window).width() - 150);
        $('#' + divname).height($(window).height() - 480);
        $('#newcs123').height($('#newcs').height() - $('#newcs1').height() - 50);
        var newX = ($('#' + divname).width() / 2);
        var newY = ($('#' + divname).height() / 2);
        document.getElementById(divname).style.marginLeft = "-" + newX + "px";
        document.getElementById(divname).style.marginTop = "-" + newY + "px";
        document.getElementById(divname).style.display = 'block';
        document.getElementById(divname).style.zIndex = 10;
        $('#overlay').height($(window).height());
        document.getElementById('overlay').style.display = 'block';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                type: 'POST',
                //url: "bunch_matter_dno_detail.php",
                url: '<?php echo base_url('ManagementReports/Pending/bunch_matter_dno_detail'); ?>',
                beforeSend: function(xhr) {
                    $("#newcs123").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
                data: {
                    diary_no: d_no1,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#newcs123").html(msg);
                updateCSRFToken();
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            });
    }
</script>