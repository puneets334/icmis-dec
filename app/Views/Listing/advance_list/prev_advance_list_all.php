<?= view('header') ?>
<style>
                fieldset{
                   padding:5px; background-color:#F5FAFF; border:1px solid #0083FF; 
                }
                legend{
                    background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF; font-weight: bold;
                }
                .table3, .subct2, .subct3, .subct4, #res_on_off, #resh_from_txt{
                    display:none;
                }
                .toggle_btn{
                    text-align: left; color: #00cc99; font-size:18px; font-weight: bold; cursor: pointer;
                }
            </style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <form method="post">
                <?= csrf_field() ?>

                <div id="dv_content1">
                    <div style="text-align: center">
                        <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ALL COURTS PREVIOUS ADVANCE LIST MODULE</span>
                        <table border="0" align="center">
                            <tr valign="middle">
                                <td>
                                    <fieldset>
                                        <legend><b>Board Type</b></legend>
                                        <select class="ele" name="board_type" id="board_type">
                                            <option value="J" SELECTED>Court</option>
                                            <!-- <option value="C">Chamber</option>
                                            <option value="CC">Chamber By Circulation</option>
                                            <option value="R">Registrar</option> -->
                                        </select>
                                    </fieldset>
                                </td>
                                <td>
                                    <fieldset>
                                        <legend>Cause List Date</legend>

                                      

                                        <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" readonly />
                                    </fieldset>
                                </td>
                                <td>
                                    <fieldset>
                                        <legend>Action</legend>
                                        <input type="button" name="btn1" id="btn1" value="Submit"/>
                                    </fieldset>
                                </td>
                                <td id="id_dts">

                                </td>
                                <td id="rs_actio_btn1">

                                </td>
                            </tr>
                        </table>
                        <div id="res_loader"></div>
                    </div>
                    <div id="dv_res1"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


    $(document).on("click", "#btn1", function() {
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        
        if (list_dt == "-1") {
            return false;
        }
        $.ajax({
            url: "<?php echo base_url('Listing/PreviousAdvanceList/prev_advance_list_all_get/'); ?>",

            data: {
                list_dt: list_dt,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#dv_res1').html('<table width="100%" align="center"><tr><td><img src="<?= base_url('images/load.gif') ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res1').html(data);
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
        if (board_type == "0") {
            alert("Please Select Board Type.");
            return false;
        }
        if (main_suppl == "0") {
            alert("Please Select Main/Supli. Field.");
            return false;
        }
        $.ajax({
            url: '<?= site_url('previous-advance-list/savePrint') ?>',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                main_suppl: main_suppl,
                prtContent: prtContent
            },
            beforeSend: function() {
                $('#res_loader').html('<table width="100%" align="center"><tr><td><img src="<?= base_url('images/load.gif') ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#res_loader').html(data);
                alert(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
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