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

    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .class_red {
        color: red;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
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
                                <h3 class="card-title">ALL COURTS PREVIOUS CAUSE LIST MODULE</h3>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;"> PREVIOUS CAUSE LIST </span>

                                <div class="col-md-12">
                                    <table class="table table-bordered mt-4">
                                        <tr>
                                            <td id="id_mf">
                                                <fieldset>
                                                    <legend>Mainhead</legend>
                                                    <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                                    <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;

                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                                    <select class="ele" name="board_type" id="board_type">
                                                        <option value="0">-Select-</option>
                                                        <option value="J">Court</option>
                                                        <option value="S">Single Judge</option>
                                                        <option value="C">Chamber</option>
                                                        <option value="R">Registrar</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td id="">
                                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Main/Suppl.</b></legend>
                                                    <select class="ele" name="main_suppl" id="main_suppl">
                                                        <option value="0">-Select-</option>
                                                        <option value="1">Main</option>
                                                        <option value="2">Suppl.</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td>
                                            <td id="id_dts">
                                                <fieldset>
                                                    <legend> Cause List Date</legend>
                                                    <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" readonly />
                                                </fieldset>
                                            </td>
                                            <td id="rs_actio_btn1" style="text-align:center;">
                                                <fieldset>
                                                    <legend>Action</legend>
                                                    <button class="btn btn-primary" type="button" name="btn1" id="btn1">Submit</button>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                    <div id="res_loader"></div>
                                </div>
                                <div id="dv_res1" class="p-4"></div>
                            </div>
                        </div>
                    </form>
                    <div id="jud_all_al"></div>
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

    $(document).on("click", "#btn1", function() {
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var main_suppl = $("#main_suppl").val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

        if (list_dt == "-1") {
            return false;
        }
        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        if (main_suppl == "0") {
            alert("Please Select Main/Supli. Field.");
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_prev_cause_list_all'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                main_suppl: main_suppl,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#dv_res1').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click", "#ebublish", function() {
        var prtContent = $("#prnnt").html();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var main_suppl = $("#main_suppl").val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        if (main_suppl == "0") {
            alert("Please Select Main/Supli. Field.");
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/cl_print_save_all'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                main_suppl: main_suppl,
                prtContent: prtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(message) {
                updateCSRFToken();
                $('#res_loader').html('<span style="color: green;">' + message + '</span>');
               // alert(data.message);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    });

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