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
                                <h3 class="card-title">ELIMINATION LIST PRINT MODULE</h3>
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
                                <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ELIMINATION LIST PRINT MODULE</span>

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
                                                    <input type="text" size="10" class="dtp" name='listing_dts' id='listing_dts' value="<?php echo date('d-m-Y'); ?>" />
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

                                <div id="dv_res1" class="p-4"> </div>
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
    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Listing/PrintAdvance/get_cause_list_elimination'); ?>',
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $("#btn1").attr("disabled", true);
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
            },

            success: function(data, status) {
                $("#btn1").attr("disabled", false);
                $('#res_loader').html('');
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                $("#btn1").attr("disabled", false);
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("click", "#ebublish", async function() {
        await updateCSRFTokenSync();
        var button = $(this); // Reference to the button
        button.prop('disabled', true); // Disable the button
        var prtContent = $("#prnnt").html();
        var list_dt = $("#listing_dts").val();
        var mainhead = get_mainhead();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            //url: 'cl_print_save_elimination.php',
            url: '<?php echo base_url('Listing/PrintAdvance/cl_print_save_elimination'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                prtContent: prtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>'); 
            },
            success: function(data) {
                $('#res_loader').html('<h3 class="bg-success p-2">' + data + '</h3>');

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            },
            complete: function(){
              button.remove();
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