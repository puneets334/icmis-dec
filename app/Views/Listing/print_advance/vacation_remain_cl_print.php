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
                            <div class="col-md-12">
                                <h3 class="card-title">
                                    ADVANCE VACATION LIST PRINT MODULE</h3>
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
                                        <td id="id_dts">
                                            <fieldset>
                                                <legend>Advance Vacation Year</legend>
                                                <select class="ele" name="vac_yr" id="vac_yr">
                                                    <?php
                                                    for ($i = 2018; $i <= $currentYear; $i++) {
                                                    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td id="rs_actio_btn1">
                                            <fieldset class="text-center">
                                                <legend>Action</legend>
                                                <button type="button" name="btn1" id="btn1" class="btn btn-primary">Submit</button>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
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
    $(document).on("click", "#btn1", async function() {
        await updateCSRFTokenSync();
        var vac_yr = $("#vac_yr").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/PrintAdvance/get_cause_list_vacation_remaining'); ?>",
            cache: false,
            async: true,
            type: 'POST',
            data: {
                vac_yr: vac_yr,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table width="100%" align="center"><tr><td class="text-center"><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
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
    });
    
    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var vac_yr = $("#vac_yr").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>