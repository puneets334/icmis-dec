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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <form>

                <?= csrf_field() ?>

                <div id="dv_content1">


                    <div style="text-align: center">
                        <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE CAUSE LIST DROP NOTE PRINT</span>
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
                                        <?php
                                        // $sql = "SELECT c.next_dt FROM advance_cl_printed c WHERE c.next_dt >= CURDATE() GROUP BY c.next_dt";
                                        // $res=mysql_query($sql) or die(mysql_error());
                                        
                                        $res = $AdvancedDropNote->getUpcomingDates();
                                        if (!empty($res)) {
                                        ?>
                                           
                                                <option value="-1" selected>SELECT</option>
                                                <?php
                                                foreach ($res as $row) {
                                                ?>
                                                    <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="-1" selected>EMPTY</option>
                                            <?php
                                            }
                                            ?>
                                            </select>
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
                            </tr>
                        </table>
                    </div>

                 
                </div>
            </form>
        </div>
        <div class="container">
                        <div class="col-md-12 ">
                            <div id="dv_res1" style="text-align: left;"></div>    
                        </div>
                    </div>
                    


                    

                    <div class="container">
                        <div class="col-md-8 ">
                            <div id="dv_res2" style="text-align: left;"></div>    
                        </div>
                    </div>
    </div>
</div>
<script>
    // $(document).on("change", "input[name='mainhead']", function() {
    //     return false;
    //     var mainhead = get_mainhead();
    //     var board_type = $("#board_type").val();
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    //     $.ajax({

    //         url: "<?php echo base_url('Listing/DropNoteAdvance/get_cl_print_mainhead/'); ?>",
    //         cache: false,
    //         async: true,
    //         data: {
    //             mainhead: mainhead,
    //             board_type: board_type,
    //             CSRF_TOKEN: CSRF_TOKEN_VALUE

    //         },
    //         beforeSend: function() {
    //             updateCSRFToken();
    //             //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
    //         },
    //         type: 'GET',
    //         success: function(data, status) {
    //             updateCSRFToken();
    //             $('#listing_dts').html(data);
    //             $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
    //             $('#part_no').html("<option value='-1' selected>EMPTY</option>");
    //         },
    //         error: function(xhr) {

    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
    //     });
    // });

    // $(document).on("change", "#listing_dts", function() {
    //     return false;
    //     var mainhead = get_mainhead();
    //     var list_dt = $("#listing_dts").val();
    //     var board_type = $("#board_type").val();
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //     $.ajax({

    //         url: "<?php echo base_url('Listing/DropNoteAdvance/get_cl_print_benches/'); ?>",
    //         cache: false,
    //         async: true,
    //         data: {
    //             list_dt: list_dt,
    //             mainhead: mainhead,
    //             board_type: board_type,
    //             CSRF_TOKEN: CSRF_TOKEN_VALUE
    //         },
    //         beforeSend: function() {

    //             // updateCSRFToken();
    //         },
    //         type: 'POST',
    //         success: function(data, status) {
    //             updateCSRFToken();
    //             $('#jud_ros').html(data);
    //         },
    //         error: function(xhr) {
    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
    //     });
    // });
    // $(document).on("change", "#jud_ros", function() {
    //     return false;
    //     var mainhead = get_mainhead();
    //     var list_dt = $("#listing_dts").val();
    //     var jud_ros = $("#jud_ros").val();
    //     var board_type = $("#board_type").val();
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    //     $.ajax({

    //         url: "<?php echo base_url('Listing/DropNoteAdvance/get_cl_print_partno/'); ?>",
    //         cache: false,
    //         async: true,
    //         data: {
    //             list_dt: list_dt,
    //             mainhead: mainhead,
    //             jud_ros: jud_ros,
    //             board_type: board_type,
    //             CSRF_TOKEN: CSRF_TOKEN_VALUE
    //         },
    //         beforeSend: function() {
    //             updateCSRFToken();
    //             //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
    //         },
    //         type: 'POST',
    //         success: function(data, status) {
    //             updateCSRFToken();
    //             $('#part_no').html(data);
    //         },
    //         error: function(xhr) {
    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
            
    //     });
    // });
    // $(document).on("change", "#board_type", function() {
       
    //     var mainhead = get_mainhead();
    //     var list_dt = $("#listing_dts").val();
    //     var board_type = $("#board_type").val();
    //     var CSRF_TOKEN = 'CSRF_TOKEN';
    //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


    //     $.ajax({

    //         url: "<?php echo base_url('Listing/DropNoteAdvance/get_cl_print_benches'); ?>",
    //         cache: false,
    //         async: true,
    //         data: {
    //             list_dt: list_dt,
    //             mainhead: mainhead,
    //             board_type: board_type,
    //             CSRF_TOKEN: CSRF_TOKEN_VALUE

    //         },
    //         beforeSend: function() {
    //             updateCSRFToken();
    //         },
    //         type: 'POST',
    //         success: function(data, status) {
    //             updateCSRFToken();
    //             $('#jud_ros').html(data);
    //         },
    //         error: function(xhr) {
    //             alert("Error: " + xhr.status + " " + xhr.statusText);
    //         }
    //     });
    // });

    

    //main function 
    $(document).on("click", "#btn1", function()
    {
        
        if (updateCSRFToken()) {
            var mainhead = get_mainhead();

        }

        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


        if (list_dt == "-1" || list_dt === "" || list_dt === null)
        {
            alert("Please select a valid listing date.");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Listing/DropNoteAdvance/note_field'); ?>",
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                [CSRF_TOKEN]: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
            },
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


    function get_mainhead() {
        updateCSRFToken();

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

    });
</script>