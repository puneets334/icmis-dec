<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Coram Wise Cases to be list (with Category)</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Board Type</label>
                                <select class="ele form-control" name="board_type" id="board_type">
                                    <option value="J">Court</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Date</label>
                                <?php
                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));    
                                ?>
                                <input type="text" size="10" class="dtp form-control" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                            </div>
                            <div class="col-md-2 mt-4">
                                <input type="button" id="btn1" class="btn btn-primary quick-btn" value="Submit">
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
        var ldates = $("#ldates").val();
        var board_type = $("#board_type").val();
        let CSRF_TOKEN = 'CSRF_TOKEN';
        let csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: '<?php echo base_url('ManagementReports/Pending/coram_wise_cat_tobe_list_get') ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: ldates,
                board_type: board_type
            },
            beforeSend: function() {
                $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
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
    //function CallPrint(){
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