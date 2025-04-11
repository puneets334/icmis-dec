<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p id="show_error"></p>
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Sensitive Cases</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            echo form_open();
                                            csrf_token();
                                            ?>
                                                <div id="dv_content1" style="margin-top: -50px;">
                                                    <div class="row">
                                                        <div class="col-md-5"></div>
                                                        <div class="col-md-7">
                                                            <input type="button" class="btn btn-primary quick-btn" value="Submit" id="btn_sensetive" name="btn_sensetive" />
                                                        </div>
                                                    </div>
                                                    <div id="res_loader"></div>
                                                    <div id="div_result"></div>
                                                </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $(document).on('click', '#btn_sensetive', function () {
            get_report();
        });
        $(document).on("click", "#prnnt1", function () {
            var prtContent = $("#prnnt").html();
            var temp_str = prtContent;
            var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=5, cellpadding=5');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        });
        $('#reportTable').DataTable( {
            paging: true,
            ordering: false,
            info: true,
            searching: true,
        } )
    });
    function get_report() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Report/get_sensitive_cases'); ?>',
            cache: false,
            async: true,
            data: {
                // d_no: t_h_cno,
                // d_yr: t_h_cyt,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            beforeSend: function () {
                updateCSRFToken();
                $('#div_result').html('<table widht="100%" style="text-align: center !important;"><tr><td><img src="<?php echo base_url('/images/load.gif'); ?>"/></td></tr></table>');
            },
            type: 'POST',
            success: function (data, status) {
                updateCSRFToken();
                $('#div_result').html(data);
            },
            error: function (xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
</script>