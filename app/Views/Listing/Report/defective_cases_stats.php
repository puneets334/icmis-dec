<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> STATISTICS ABOUT DEFECTIVE MATTERS LISTED</h3>
                            </div>
                          
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="col-sm-2"></div>
                        <div id="dv_content1">
                            <input type="button" id="btngetr" name="btngetr" value=" Get " />
                            <div style="text-align: center">
                                <!-- <table class="table table-striped custom-table">
                                    <tr>
                                        <td>
                                               Mainhead :
                        <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked" >M&nbsp;
                        <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <hr>
                                        </td>
                                    </tr>
                                </table> -->
                                <div id="res_loader"></div>
                            </div>
                            <div id="dv_res1"></div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
</section>
<script>
    $(document).on("click", "#btngetr", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $('#dv_res1').html("");
        var mainhead = 'M'
        $.ajax({
            url: '<?php echo base_url('Listing/report/defective_cases_stats_get'); ?>',
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
                //$('#show_fil').html("");       
                $('#dv_res1').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
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