<?= view('header') ?>
<style>
    #listingTable_wrapper {
        width: 97%;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial / Report >> Work Done</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("warning")) { ?>
                                <div class="alert alert-warning text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("warning") ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header p-2" style="background-color: #fff;">
                                <?= view('Judicial/Reports/menu') ?>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Page Content Start -->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="container text-center">
                                                    <h3>Cases Verified By Monitoring Team</h3>
                                                </div>

                                                <div class="container">
                                                    <form method="post" action="<?php echo base_url('Judicial/Report/verify_index_da_wise'); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <table class="table table-bordered mx-auto" style="width: 50%;">
                                                            <tr>
                                                                <td>To be Listed On</td>
                                                                <td>
                                                                    <input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                                                </td>
                                                                <td>
                                                                    <button type="button" name="show" id="btn1" class="btn btn-primary">Show</button>
                                                                </td>
                                                                <?php if(!empty($matter_results)) { ?>
                                                                <td>
                                                                    <button type="button" id='btn_pnt' class="btn btn-primary">Print</button>
                                                                </td>
                                                                <?php } ?>
                                                            </tr>
                                                        </table>
                                                        <?php echo csrf_field(); ?>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div id="dv_res1"></div>
                                    </div>
                                    <!-- Page Content End -->
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
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

        var ldates = $("#ldates").val();
        $.ajax({
            url: '<?php echo base_url('Judicial/Report/verify_detail_report_da_wise'); ?>',
            cache: false,
            async: true,
            data: {
                verify_dt: ldates,
                CSRF_TOKEN:CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="<?php echo base_url("images/load.gif"); ?>"/></td></tr></table>');
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
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>