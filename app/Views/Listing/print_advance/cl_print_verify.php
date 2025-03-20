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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">
                                    CAUSE LIST PRINT MODULE</h3>
                            </div>

                        </div>
                    </div>
                    <div id="dv_content1">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div style="text-align: center">

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
                                                <legend>Listing Dates</legend>
                                                <select class="form-control" name="listing_dts" id="listing_dts">
                                                    <option value="-1" selected>SELECT</option>
                                                    <?php if (!empty($listingDates)) : ?>
                                                        <?php foreach ($listingDates as $date) : ?>
                                                            <option value="<?= $date->next_dt; ?>">
                                                                <?= date("d-m-Y", strtotime($date->next_dt)); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option value="-1">EMPTY</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>

                                        </td>
                                        <td id="rs_jg">
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

                                        <td id="rs_jg">
                                            <fieldset>
                                                <legend>Benches</legend>
                                                <select class="form-control" name="jud_ros" id="jud_ros">
                                                    <option value="-1" selected>SELECT</option>
                                                    <?php if (!empty($benches)) : ?>
                                                        <?php foreach ($benches as $bench) : ?>
                                                            <option value="<?= $bench['judges'] . '|' . $bench['roster_id']; ?>">
                                                                <?= $bench['jnm']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option value="-1">EMPTY</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>

                                        <td id="rs_jg">
                                            <fieldset>
                                                <legend>Part No.</legend>
                                                <select class="ele" name="part_no" id="part_no">
                                                    <option value="-1" selected>EMPTY</option>
                                                </select>
                                            </fieldset>
                                        </td>


                                        <td id="rs_actio_btn1">
                                            <fieldset>
                                                <legend>Action</legend>
                                                <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary"/>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
                                <div id="res_loader"></div>
                            </div>
                        </form>
                        <div id="dv_res1" class="p-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
     $('.select-box').select2({
        selectOnClose: true
    });
    $(document).on("change", "input[name='mainhead']",async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintWeekly/get_cl_print_mainhead'); ?>',
            cache: false,
            type: 'POST',
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: csrf,
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#res_loader').html('');
                $('#listing_dts').html(data.next_dt);
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
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintWeekly/get_cl_print_benches'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: csrf, 
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
            },
        
            success: function(data, status) {
                $('#res_loader').html('');
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
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
            url: '<?php echo base_url('Listing/PrintWeekly/get_cl_print_partno'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                board_type: board_type,
                CSRF_TOKEN: csrf, 
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
            },
           
            success: function(data, status) {
                $('#res_loader').html('');
                $('#part_no').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("change", "#board_type", async function() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintWeekly/get_cl_print_benches'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: csrf, 
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
            },
            success: function(data, status) {
                $('#res_loader').html('');
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
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
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        //    if(list_dt == "-1"){ return false; }
        //    if(jud_ros == "-1"){ return false; }
        //    if(part_no == "-1"){ return false; }
        $.ajax({
            url: '<?php echo base_url('Listing/PrintWeekly/get_cause_list_verify'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                board_type: board_type,
                CSRF_TOKEN: csrf, 
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#dv_res1').html(data);
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
            url: '<?php echo base_url('Listing/PrintWeekly/call_reshuffle_function'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                from_cl_no: from_cl_no,
                CSRF_TOKEN: csrf, 
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#res_loader').html(data);
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
        $.ajax({
          //  url: 'cl_print_save.php',
            url: '<?php echo base_url('Listing/PrintWeekly/cl_print_save'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                prtContent: prtContent
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(data, status) {
                $('#res_loader').html(data);
                alert(data);
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
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>