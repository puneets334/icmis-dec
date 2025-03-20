<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Having Non Presiding Coram</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>


                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                    <div id="dv_content1" class="container mt-4">

                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php field_mainhead(); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php field_board_type(); ?>
                                </div>
                                <div class="col-md-3">
                                    <fieldset class="border p-2">
                                        <legend class="w-auto">Reg./Unreg.</legend>
                                        <select class="form-control" name="reg_unreg" id="reg_unreg">
                                            <option value="0">-ALL-</option>
                                            <option value="1">Reg.</option>
                                            <option value="2">Unreg.</option>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="row mt-3">
                                    <div id="rs_actio_btn1">
                                        <?php field_action_btn1(); ?>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div id="res_loader" class="text-center mt-3"></div>
                        <div id="dv_res1" class="mt-3"></div>

                    </div>

                    <?php echo form_close(); ?>



                </div>
            </div>
        </div>
</section>

<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var reg_unreg = $("#reg_unreg").val();

        $.ajax({
            url: '<?php echo base_url('Listing/Report/non_presiding_coram_get');  ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                mainhead: mainhead,
                board_type: board_type,
                reg_unreg: reg_unreg
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#dv_res1').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
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
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>