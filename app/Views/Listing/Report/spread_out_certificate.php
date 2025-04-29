<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Spread Out Certificate</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>



                    <?php
                    echo form_open();
                    csrf_field();
                    ?>
                    <div class="container mt-4">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <label for="sec_id">Board Type</label>
                                        </div>
                                        <div class="card-body">
                                            <select class="form-control" name="board_type" id="board_type">
                                                <option value="J">Court</option>
                                                <!-- Uncomment if needed
                            <option value="C">Chamber</option>
                            <option value="R">Registrar</option>
                            -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <label for="sec_id"> Date </label>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <input type="text" class="form-control dtp" name="ldates" id="ldates"
                                                    value="<?php echo $next_court_work_day; ?>" readonly />
                                            </div>
                                            <label>TO</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control dtp" name="ldates_to" id="ldates_to"
                                                    value="<?php echo $next_court_work_day; ?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center" style="margin-bottom: 127px;">
                                            <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="res_loader" class="mt-3"></div>
                        </div>

                        <div id="dv_res1" class="mt-3"></div>
                        <?php
                        // pr($spread_out_certificate);
                        // die();
                        ?>
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
        var ldates = $("#ldates").val();
        var ldates_to = $("#ldates_to").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Report/spread_out_certificate_get_data'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: ldates,
                list_dt_to: ldates_to,
                board_type: board_type
            },
            beforeSend: function() {
                $('#dv_res1').html(
                    '<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>'
                );
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
               console.log('updated darta: '+data);
                $('#dv_res1').html(data);
                
               
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
       
    }
    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '',
            'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1'
        );
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>