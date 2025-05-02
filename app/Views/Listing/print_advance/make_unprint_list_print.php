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

    .class_red{color:red;}
    .class_green{color:green;}

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
                                <h3 class="card-title">CAUSE LIST MAKE UNPRINT MODULE</h3>
                            </div>


                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;"> MAKE UNPRINT </span>

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
                                                    <legend>Cause List Date</legend>
                                                    <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" readonly />
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
                                                    <legend>Benches</legend>
                                                    <select class="ele" name="jud_ros" id="jud_ros">
                                                        <option value="0" selected>SELECT</option>

                                                        <?php if (!empty($results)) {
                                                            foreach ($results as $row) { ?>
                                                                <option value="<?php echo $row["judges"] . "|" . $row["roster_id"]; ?>"> <?php echo $row['jnm']; ?> </option>
                                                            <?php  }
                                                        } else { ?>
                                                            <option value="0" selected>EMPTY</option>
                                                        <?php } ?>
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
                                                    <input class="btn btn-primary" type="button" name="btn1" id="btn1" value="Submit" />
                                                </fieldset>

                                            </td>
                                        </tr>
                                    </table>
                                    <div id="res_loader"></div>
                                </div>
                                <div id="dv_res1"> </div>
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
    setTimeout(function() {
        $('#res_loader').html('');
        $('#dv_res1').html('');
    }, 500);
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    $(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var board_type = $("#board_type").val();
        var list_dt = $("#listing_dts").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_printed_roster_id'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                list_dt: list_dt,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html('');
                $('#jud_ros').html(data);

                /*updateCSRFToken();
                $('#dv_res1').html('');
                if (data != '') {
                    $('#listing_dts').html(data);
                } else {
                    $('#listing_dts').html("<option value='-1' selected>EMPTY</option>");
                }

                $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                $('#part_no').html("<option value='-1' selected>EMPTY</option>");*/
            },
            error: function(xhr) {
                updateCSRFToken();
                $('#dv_res1').html('');
                //alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


    $(document).on("change", "#jud_ros", function() {
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_printed_partno'); ?>',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#dv_res1').html('');
                updateCSRFToken();
                $('#part_no').html(data);
            },
            error: function(xhr) {
                $('#dv_res1').html('');
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
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_printed_roster_id'); ?>',
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
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },

            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html('');
                $('#jud_ros').html(data);
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

            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_printed_roster_id'); ?>',
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
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html('');
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                $('#dv_res1').html('');
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

       
    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        $('#dv_res1').html('');
        $('#res_loader').html('');
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        // if(list_dt == "-1"){ return false; }
        // if(jud_ros == "-1"){ return false; }
        // if(part_no == "-1"){ return false; }
        $.ajax({

            url: '<?php echo base_url('Listing/PrintAdvance/get_make_unprint'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $("#btn1").attr("disabled", true);
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                updateCSRFToken();
                $("#btn1").attr("disabled", false);
                $('#dv_res1').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                $("#btn1").attr("disabled", false);
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("click", "#del_btn", function() {
        var del_cl_id = $("#del_cl_id").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({

            url: '<?php echo base_url('Listing/PrintAdvance/get_make_unprint_updt'); ?>',
            cache: false,
            type: 'POST',
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                del_cl_id: del_cl_id,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data) {
                updateCSRFToken();
                $('#dv_res1').html(data);
                $('#res_loader').html('<h3><div class=class_' + data.status + '>' + data.message + '</div></h3>');
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>