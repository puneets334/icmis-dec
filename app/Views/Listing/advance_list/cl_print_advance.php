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
        font-size: 12px;
        autosize: 1;
    }

    div#dv_res1 {
        width: 100%;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <form method="post">

                <?= csrf_field() ?>

                <div id="dv_content1">


                    <div style="text-align: center">
                        <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE CAUSE LIST PRINT MODULE</span>
                        <table border="0" align="center">
                            <tr valign="middle">
                                <td id="id_mf">
                                    <fieldset>
                                        <legend>Mainhead</legend>
                                        <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                        <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                    </fieldset>
                                </td>
                                <td id="id_dts">
                                    <fieldset>
                                        <legend>Advance Listing Dates</legend>
                                        <select class="ele" name="listing_dts" id="listing_dts">
                                            <?php if (!empty($dates)): ?>
                                                <option value="-1" selected>SELECT</option>
                                                <?php foreach ($dates as $row): ?>
                                                    <option value="<?= $row['next_dt']; ?>"><?= date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="-1" selected>EMPTY</option>
                                            <?php endif; ?>
                                        </select>
                                    </fieldset>
                                </td>

                                <td>
                                    <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                        <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                        <select class="ele" name="board_type" id="board_type">
                                            <option value="0">-ALL-</option>
                                            <option value="J">Court</option>
                                            <option value="S">Single Judge</option>
                                            <option value="C">Chamber</option>
                                            <option value="R">Registrar</option>
                                        </select>
                                    </fieldset>
                                </td>

                                <td id="rs_actio_btn1">
                                    <fieldset>
                                        <legend>Action</legend>
                                        <input type="button" name="btn1" id="btn1" value="Submit" />
                                    </fieldset>
                                </td>
                                <td id="res_on_off">
                                    <fieldset>
                                        <legend>Reshuffle</legend>
                                        <input type="text" name="resh_from_txt" id="resh_from_txt" value="0" maxlength="4" size="5" />
                                        <span id="resf_span" style="background: #5fa3f9; border: #ffffff; color: #ffffff; height: 12px; padding: 4px;"><b>FROM</b></span>
                                        <input type='button' name='re_shuffle' id='re_shuffle' value='Re-Shuffle' />
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </form>
        </div>

        <div id="dv_res3"></div>
        <div id="dv_res1"></div>
        <div id="dv_res2"></div>
    </div>


</div>
<script>
    $(document).on("click", "#btn1", function() {
        updateCSRFToken();
        get_cl_1();
    });

    async function get_cl_1() {


        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        if (list_dt == "-1") {
            alert("Please select a valid Advance Listing Date.");
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


        $.ajax({

            url: "<?php echo base_url('Listing/PrintPublish/get_cause_list_advance'); ?>",

            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //updateCSRFToken();
                $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
    $(document).on("click", "#resf_span", function() {
        $("#resh_from_txt").toggle("slow", "linear");
    });

    $(document).on("click", "#re_shuffle", function() {
        var list_dt = $("#listing_dts").val();
        var from_cl_no = $("#resh_from_txt").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({

            url: "<?php echo base_url('Listing/PrintPublish/call_reshuffle_function_advance'); ?>",
            data: {
                list_dt: list_dt,
                board_type: board_type,
                from_cl_no: from_cl_no,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //updateCSRFToken();
                // $('#dv_res3').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');

            },
            type: 'POST',
            success: function(data, status) {
                //updateCSRFToken();
                $('#dv_res3').html('<div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%;">' + data.message + '</div>');
                $('#dv_res3').css({
                    "display": "flex",
                    "justify-content": "center",
                    "align-items": "center",
                    "width": "100%",
                    "min-height": "50px" // Ensure some height
                });








                //if(updateCSRFToken()){
                //setTimeout(function() {get_cl_1();}, 600);
                get_cl_1();

                // }


            },
            error: function(xhr) {
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
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('Listing/PrintPublish/cl_print_save_advance'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                prtContent: prtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();

            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                // $('#dv_res1').html(data.message);
                $('#dv_res1').html('<div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%;">' + data.message + '</div>');
                $('#dv_res1').css({
                    "display": "flex",
                    "justify-content": "center",
                    "align-items": "center",
                    "width": "100%",
                    "min-height": "50px" // Ensure some height
                });




            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>