<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <h5 class="text-center mb-0">Fix Date Given By User</h5>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <form method="post" action="<?= site_url(uri_string()) ?>">
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <!-- <div class="col-12 col-md-3 mb-3"></div> -->
                                                <div class="col-12 col-md-3 mb-3">
                                                    <label for="listing_dts">Listing Date</label>
                                                    <input type="text" size="10" class="dtp form-control" name="listing_dts" id="listing_dts" value="<?php echo date('d-m-Y'); ?>" />
                                                </div>
                                                <div class="col-12 col-md-3 mb-3">
                                                    <button type="button" id="rs_actio_btn1" class="quick-btn btn btn-primary mt-4">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="dv_res1"></div>
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

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    $(document).on("click", "#rs_actio_btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let list_dt = $("#listing_dts").val();
        $('#rs_actio_btn1').prop('disabled',true);
        $.ajax({
            url: "<?php echo base_url('ManagementReports/Listing/Report/date_given_by_da_get'); ?>",
            method: 'POST',
            beforeSend: function() {
                $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            data: {
                list_dt: list_dt,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            cache: false,
            success: function(data) {
                $('#dv_res1').html(data);
            },
            complete:function()
            {
                updateCSRFToken();
                $('#rs_actio_btn1').prop('disabled',false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + jqXHR.status + " " + errorThrown);
            }
        });
    });
</script>