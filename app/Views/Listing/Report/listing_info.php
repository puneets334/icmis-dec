<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Listing Information Coram Wise</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>


                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div id="dv_content1">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Board Type</label>
                                    <select class="ele form-control  form-select" name="board_type" id="board_type">
                                        <option value="J" selected>Court</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Listing Date</label>
                                    <?php
                                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                    $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                    ?>
                                    <input type="text" size="10" class="dtp form-control" name='listing_dts' id='listing_dts' value="<?php echo $next_court_work_day; ?>" readonly />
                                </div>
                                <div class="col-md-2 mt-4">
                                    <button class="btn btn-block_ btn-primary" type="button" name="btn1" id="btn1" value="Submit">Submit</button>
                                </div>
                            </div>
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
        var board_type = $("#board_type").val();
        var listing_dts = $("#listing_dts").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Report/listing_info_get'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                board_type: board_type,
                listing_dts: listing_dts
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
    }

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