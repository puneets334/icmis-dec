<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ALLOCATION REPORT</h3>
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
        <div>
            <div class="row">
                <!-- Main Head Field -->
                <div class="col-md-2 mt-2">
                    <?php field_mainhead(); ?>
                </div>

                <!-- Listing Date Field -->
                <div class="col-md-3 mt-2">
                    <fieldset class="p-3">
                        <legend class="w-auto">
                            Listing Date
                        </legend>
                        <?php
                        // Generate next court work day
                        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                        $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                        ?>
                        <input type="text" size="10" class="form-control dtp" name="listing_dts" id="listing_dts" value="<?php echo $next_court_work_day; ?>" readonly />
                    </fieldset>
                    
                </div>

                <!-- Board Type Field -->
                <div class="col-md-3 mt-2">
                    <label for="board_type" class="form-label">Board Type</label>
                    <select class="form-control" name="board_type" id="board_type">
                        <option value="0">-ALL-</option>
                        <option value="J">Court</option>
                        <option value="C">Chamber</option>
                        <option value="R">Registrar</option>
                    </select>
                </div>

                <!-- Action Button -->
                <div class="col-md-2 mt-2">
                    <?php field_action_btn1(); ?>
                </div>
            </div>

            <!-- Loading indicator -->
            <div id="res_loader" class="mt-3"></div>
        </div>

        <!-- Results Container -->
        <div id="dv_res1" class="mt-3"></div>
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
            yearRange: '2019:2050'
        });
    });

    $(document).on("change", "input[name='mainhead']", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Common/get_cl_print_mainhead') ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                mainhead: mainhead,
                board_type: board_type
            },
            beforeSend: function() {
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#listing_dts').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
    });


    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();

        $.ajax({
            url: '<?php echo base_url('Listing/Report/get_allocation_report'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type
            },
            beforeSend: function() {
                $('#dv_res1').html(
                    '<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>'
                );
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
        var WinPrint = window.open('', '',
            'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>