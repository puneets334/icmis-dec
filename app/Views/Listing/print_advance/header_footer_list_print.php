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
                            <div class="col-sm-10 mb-2">
                                <h3 class="card-title">CAUSE LIST HEADER FOOTER MODULE</h3>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;"> HEADER FOOTER LIST</span>

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
                                                        <option value="-1" selected>SELECT</option>
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
                                                    <legend>Benches</legend>
                                                    <select class="ele" name="jud_ros" id="jud_ros">
                                                        <option value="-1" selected>SELECT</option>

                                                        <?php
                                                        if (!empty($benches)) {
                                                            foreach ($benches as $row) {
                                                        ?>
                                                                <option value="<?php echo $row["jcd"] . "|" . $row["id"]; ?>"><?php echo $row['jnm']; ?></option>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <option value="-1" selected>EMPTY</option>
                                                        <?php
                                                        }
                                                        ?>
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
                                <div id="dv_res1"></div>
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
    }, 500);
    $(document).on("change", "input[name='mainhead']", function() {
        $('#res_loader').html('');
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
                //$('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            // success: function(data, status) {
            //     updateCSRFToken();
            //     $('#dv_res1').html('');
            //     if (data != '') {
            //         $('#listing_dts').html(data);
            //     } else {
            //         ('#listing_dts').html("<option value='-1' selected>EMPTY</option>");
            //     }

            //     $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
            //     $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            // },
            success: function(data, status) {  
                updateCSRFToken();              
               $('#listing_dts').html(data);
               $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
               $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            },
            error: function(xhr) {
                updateCSRFToken();
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
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_freeze_partno'); ?>',
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
                //$('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
               $('#dv_res1').html('');
                $('#part_no').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                //    / alert("Error: " + xhr.status + " " + xhr.statusText);
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
            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_benches_from_roster'); ?>',
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
                //$('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
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

            url: '<?php echo base_url('Listing/PrintAdvance/get_cl_print_benches_from_roster'); ?>',
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
               // $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
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

    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
      
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        // alert(list_dt);
        // alert(jud_ros);
        // alert(part_no);
        if(list_dt == "-1"){ return false; }
        if(jud_ros == "-1"){ return false; }
        if(part_no == "-1"){ return false; }


        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/note_field'); ?>',
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
                toggleButtonState('bt1', 'disable');
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },

            success: function(data, status) {
                updateCSRFToken();
                toggleButtonState('bt1', 'enable');
                $('#dv_res1').html(data);
                //$('#dv_res_get').html(get_head_foot(mainhead,list_dt,jud_ros)); 
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("click", "#n_btn", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var part_no = $("#part_no").val();
        var flag = $("#flag").val();
        var hf_note = $("#hf_note").val();
        // if(list_dt == "-1"){ return false; }
        // if(jud_ros == "-1"){ return false; }
        // if(part_no == "-1"){ return false; }

        // Validate the input fields properly
        if (flag === '') { // Directly check the value of flag
            alert('Please Enter Note.');
            $('#flag').focus();
            return false;
        }

        if (hf_note === '') { // Directly check the value of hf_note
            alert('Please Enter Note.');
            $('#hf_note').focus();
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/note_field_ins'); ?>',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                jud_ros: jud_ros,
                part_no: part_no,
                flag: flag,
                hf_note: hf_note,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                toggleButtonState('n_btn', 'disable');
                $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status, message) {
                updateCSRFToken();
                toggleButtonState('n_btn', 'enable');
                $('#dv_res2').html(data);

                $('#res_loader').html("<h3 class='bg-success p-2 text-center'>" + data.message + "</h3>");
                //alert(data.message);
                 // Trigger click on #btn1 after success
                $('#btn1').trigger('click'); // Programmatically trigger the click event of #btn1
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    function del_head_foot(hfid) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('Listing/PrintAdvance/del_head_foot'); ?>',
            cache: false,
            async: true,
            data: {
                del_hf: hfid,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',

            success: function(data, status, message) {
                updateCSRFToken();
                $('#dv_res2').html(data);
                $('tr[data-id="tr_' + hfid + '"]').closest('tr').remove(); // Remove the row by matching the data-id attribute
                // Show success message
                $('#res_loader').html("<h3 class='bg-success p-2 text-center'>" + data.message + "</h3>");
                //alert(data.message);
                // get_head_foot(mainhead,list_dt,jud_ros);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
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


    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {

        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;

        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        //WinPrint.close();
        //prtContent.innerHTML=strOldOne;
        //}
    });
</script>