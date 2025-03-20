<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Cases Filed against Judgement</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <label for="" class="mt-2"> Mainhead :</label>&nbsp;
                            <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked"><span class="mt-2">M</span>&nbsp;
                            <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular"><span class="mt-2">R</span>&nbsp;
                            <input type="button" id="btngetr" class="btn btn-primary quick-btn" name="btngetr" value=" Get " />
                            <div id="res_loader"></div>
                        </div>
                        <div id="dv_res1"></div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script>
    $(document).on("click", "#btngetr", function() {
        $('#dv_res1').html("");
        var mainhead = get_mainhead();
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/sc_disposed_cav_verification_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                mainhead: mainhead
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
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
        updateCSRFToken();
    });

    function get_mainhead() {
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>