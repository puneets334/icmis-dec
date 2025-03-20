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
                                <h3 class="card-title">FREEZE UN-FREEZE MODULE</h3>
                            </div>


                        </div>
                    </div>
                    <form method="post">

                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'freeze', 'id' => 'freeze', 'autocomplete' => 'off');
                        echo form_open(base_url('#'), $attribute);
                        ?>

                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">CAUSE LIST FREEZE UN-FREEZE MODULE</span>

                                <div style="col-md-6">
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
                                                        <option value="0" selected>SELECT</option>
                                                        <?php foreach ($listing_date as $row) { ?>
                                                            <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>

                                                        <?php  } ?>
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

                                            <td>
                                                <fieldset>
                                                    <legend>Part No.</legend>
                                                    <select class="ele" name="part_no" id="part_no">
                                                        <option value="-1" selected>SELECT</option>
                                                    </select>
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
                        <?php form_close(); ?>
                    </form>
                    <div id="jud_all_al"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var board_type = $("#board_type").val();
        $.ajax({

            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_mainhead'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#res_loader').html('');
                if (data != '') {
                    $('#listing_dts').html(data);
                } else {
                    ('#listing_dts').html("<option value='-1' selected>EMPTY</option>");
                }

                $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            },
            error: function(xhr) {
                updateCSRFToken();
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on("change", "#listing_dts", function() {
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_freeze_partno'); ?>',

            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(data, status) {
                updateCSRFToken();
                $('#res_loader').html('');
                $('#part_no').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                // alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("change", "#board_type", function() {
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({

            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_freeze_partno'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(data, status) {
                $('#res_loader').html('');
                updateCSRFToken();

                $('#part_no').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                // alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });



    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    async function get_cl_1() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        /*var jud_ros = $("#jud_ros").val();*/
        var part_no = $("#part_no").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        toggleButtonState('btn1', 'disable');
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_freeze_unfreeze'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                part_no: part_no,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                toggleButtonState('btn1', 'enable');
                $('#res_loader').html('');
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                updateCSRFToken();
                toggleButtonState('btn1', 'enable');
                // alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }
    $(document).on("click", ".btnSubmit", function() {
        var mainhead = get_mainhead();
        var action_type = $(this).data("action_type");
        var list_dt = $("#listing_dts").val();
        var part_no = $("#part_no").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        toggleButtonState('unfreeze', 'disable');
        $.ajax({

            url: '<?php echo base_url('Listing/PrintAdvance/freeze_unfreeze_save'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                part_no: part_no,
                board_type: board_type,
                action_type: action_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },

            success: function(data, status, message) {
                updateCSRFToken();
                toggleButtonState('unfreeze', 'enable');
                $('#res_loader').html('');
                $('#res_loader').html(data.message);
                //alert(data.message);
                // alert(data);
            },
            error: function(xhr) {
                toggleButtonState('unfreeze', 'enable');
                updateCSRFToken();
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });
</script>