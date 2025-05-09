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
        font-size: 14px;
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
                                    SECTION LIST PRINT MODULE</h3>
                            </div>

                        </div>
                    </div>
                    <div id="dv_content1">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div style="text-align: center">
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">SECTION LIST PRINT MODULE</span>
                                <table border="0" align="center">
                                    <tr valign="middle">
                                        <td id="id_mf">
                                            <?php field_mainhead(); ?>
                                        </td>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                                <select class="ele" name="board_type" id="board_type">
                                                    <option value="J">Court</option>
                                                    <option value="C">Chamber</option>
                                                    <option value="R">Registrar</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td id="id_dts">
                                            <fieldset>
                                                <legend>Cause List Date</legend>
                                                <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" />
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
    $(document).on("focus", ".dtp", function() {
        $('#listing_dts').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
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

    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    async function get_cl_1() {
        await updateCSRFTokenSync();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
		$.ajax({
            url: "<?php echo base_url('Listing/PrintAdvance/sec_list_get'); ?>",
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                board_type: board_type,
                mainhead: mainhead,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                $('#btn1').attr('disabled','disabled');
			},
 
            success: function(data, status) {
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
				$('#btn1').removeAttr('disabled');
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
				$('#btn1').removeAttr('disabled');
            }
        });
    }

    $(document).on("click", "#ebublish",  async function() {
        await updateCSRFTokenSync();
        var prtContent = $("#prnnt").html();
        var list_dt = $("#listing_dts").val();
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
		
        $.ajax({
            // url: 'sec_list_save.php',
            url: "<?php echo base_url('Listing/PrintAdvance/sec_list_save'); ?>",
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                board_type: board_type,
                mainhead: mainhead,
                prtContent: prtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                $('#ebublish').attr('disabled','disabled');
			},
            success: function(data, status) {
				$('#res_loader').html(`<h3 class="text-success">${data}</h3>`); // Template literals
                alert(data);
				$('#ebublish').removeAttr('disabled');
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
				$('#ebublish').removeAttr('disabled');
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