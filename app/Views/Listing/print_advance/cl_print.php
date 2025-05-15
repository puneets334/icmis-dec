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
    .subct4, #res_on_off, #resh_from_txt {
        display: none;
    }

   
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h3 class="card-title">CAUSE LIST PRINT MODULE</h3>
                    </div>
                    <div class="card-body">
                        <div id="dv_content1">
                            <?= csrf_field() ?>
                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;"> CAUSE LIST PRINT MODULE</span>

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
                                            <td id="id_dts">
                                                <fieldset>
                                                    <legend>Listing Dates</legend>
                                                    <select class="ele" name="listing_dts" id="listing_dts">
                                                        <?php if (!empty($listingDates)) { ?>
                                                            <option value="-1" selected>SELECT</option>
                                                            <?php foreach ($listingDates as $row) { ?>
                                                                <option value="<?php echo htmlspecialchars($row['next_dt']); ?>">
                                                                    <?php echo date("d-m-Y", strtotime($row['next_dt'])); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="-1" selected>EMPTY</option>
                                                        <?php } ?>
                                                    </select>
                                                </fieldset>

                                            </td>
                                            <td>
                                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                                    <select class="ele" name="board_type" id="board_type">
                                                        <option value="0">-ALL-</option>
                                                        <option value="J">Court</option>
                                                        <option value="C">Chamber</option>
                                                        <option value="R">Registrar</option>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td id="rs_jg">
                                                <fieldset>
                                                    <legend>Benches</legend>
                                                    <select class="ele" name="jud_ros" id="jud_ros">
                                                        <option value="0" selected>SELECT</option>

                                                        <?php if (!empty($benches)) {
                                                            foreach ($benches as $row) { ?>
                                                                <option value="<?php echo $row["jcd"] . "|" . $row["id"]; ?>"><?php echo $row['jnm']; ?></option>
                                                            <?php  }
                                                        } else { ?>
                                                            <option value="0" selected>EMPTY</option>
                                                        <?php } ?>
                                                    </select>
                                                </fieldset>
                                            </td>
                                            <td id="rs_partno">
                                                <fieldset>
                                                    <legend>Part No.</legend>
                                                    <select class="ele" name="part_no" id="part_no">
                                                        <option value="-1" selected>EMPTY</option>
                                                    </select>
                                                </fieldset>
                                            </td>

                                            <td id="rs_actio_btn1" style="text-align:center;">
                                                <fieldset>
                                                    <legend>Action</legend>
                                                    <button class="btn btn-primary" type="button" name="btn1" id="btn1">Submit</button>
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
                                    <div id="res_loader"></div>
                                </div>
                                <div id="dv_res1" class="p-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // $(document).on("change", "input[name='mainhead']",
    //  async function()
    //  {

    //     await updateCSRFTokenSync();
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var csrf = $("input[name='CSRF_TOKEN']").val();
    //     var mainhead = get_mainhead();
    //     var board_type = $("#board_type").val();



    //     $.ajax({
    //         url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_mainhead'); ?>',
    //         type: 'POST',
    //         data: {
    //             CSRF_TOKEN: csrf,
    //             mainhead: mainhead,
    //             board_type: board_type,
    //             CSRF_TOKEN: CSRF_TOKEN_VALUE
    //         },
    //         beforeSend: function() {
    //             // $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
    //         },

    //         success: function(data, status) {
    //             $('#listing_dts').html(data);
    //             $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
    //             $('#part_no').html("<option value='-1' selected>EMPTY</option>");
    //         },
    //         error: function(xhr) {
    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
    //     });
    //     $('#res_loader').html('');
    // });

    $(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_mainhead'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                mainhead: mainhead,
                board_type: board_type,
                //  CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#listing_dts').html(data);
                $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("change", "#listing_dts", async function() {

        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_benches_from_roster'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,

            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },

            success: function(data, status) {
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        $('#res_loader').html('');
    });

    $(document).on("change", "#jud_ros", async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var board_type = $("#board_type").val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_partno'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                board_type: board_type,

            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                //$('#part_no').html(data);
                $('#part_no').html(data.options);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        $('#res_loader').html('');
    });

    $(document).on("change", "#board_type", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_benches_from_roster'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

        $('#res_loader').html('');
    });

    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    async function get_cl_1() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var board_type = $("#board_type").val();


        if (list_dt == "-1") {
            alert("Please Select Listing Dates.");
            return false;
        }
        if (jud_ros == "0") {
            alert("Please Select Benches.");
            return false;
        }
        if (part_no == "-1") {
            alert("Please Select Part No.");
            return false;
        }
        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cause_list_advance_screen'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                board_type: board_type
            },
            beforeSend: function() {

                $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
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

    $(document).on("click", "#re_shuffle", async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var from_cl_no = $("#resh_from_txt").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/call_reshuffle_function'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                from_cl_no: from_cl_no,
                CSRF_TOKEN: csrf
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(response) {
                if (response.status === "success") {
                    $('#res_loader').html(response.message);
                } else {
                    $('#res_loader').html(response.message);
                }
                get_cl_1();
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

    $(document).on("click", "#ebublish", async function() {
        await updateCSRFTokenSync();
        var prtContent = $("#prnnt").html();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/cl_print_save'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                board_type: board_type,
                prtContent: prtContent,
                CSRF_TOKEN: csrf,
            },
            beforeSend: function() {
               $('#res_loader').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(data, status) {
                $(".ebublish").html('');
                $('#res_loader').html(data.message).addClass('alert alert-success');
                $(".ebublish").html('Already Published');
                
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
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>
<?= view('sci_main_footer') ?>