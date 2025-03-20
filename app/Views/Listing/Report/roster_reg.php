<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Regular Hearing Roster</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?php
                    echo form_open();
                    csrf_field();
                    
                    ?>
                  <div id="dv_content1" class="container mt-4">
    <div class="text-center">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="font-weight-bold">Week Commencing Date</label>
                    <?php
                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                    $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                    ?>
                    <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" />
                </div>
            </div>
            <div class="col-md-2"><br/>
                <div class="form-group text-center">
                    <input type="button" class="btn btn-primary mt-2" name="btn1" id="btn1" value="Submit" />
                </div>
            </div>
        </div>
        <div id="res_loader" class="mt-4"></div>
    </div>
    <div id="dv_res1" class="mt-4"></div>
</div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</section>
<script>
    // $(document).on("focus", ".dtp", function() {
    //     $('.dtp').datepicker({
    //         dateFormat: 'dd-mm-yy',
    //         changeMonth: true,
    //         changeYear: true,
    //         yearRange: '1950:2050'
    //     });
    // });

    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var ldates = $("#ldates").val();
        $.ajax({
            url: '<?php echo base_url('Listing/report/roster_reg_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                ldates: ldates
            },
            beforeSend: function() {
                updateCSRFToken();
                $('#dv_res1').html(
                    '<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>'
                );
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



    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '',
            'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>