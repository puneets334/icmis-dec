<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <?php echo form_open();
                    csrf_token();
                    ?>
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Roster Print</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Tentaive Listing Date</label>
                                <?php
                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                ?>
                                <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                            </div>
                            <div class="col-md-2 mt-4">
                                <input type="button" class="btn btn-block_ btn-primary" name="btn1" id="btn1" value="Submit" />
                            </div>
                        </div>
                        <div id="res_loader"></div>
                    </div>

                    <div id="dv_res1"></div>
                    <?php form_close(); ?>
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
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var from_dt = $("#ldates").val();
        var to_dt = $("#ldates_to").val();
        $.ajax({
            url: '<?php echo base_url('Listing/report/get_roster_j_c'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                from_dt: from_dt,
                to_dt: to_dt
            },
            beforeSend: function() {
                
                $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
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