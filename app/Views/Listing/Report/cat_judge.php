<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Roster Category Allocated</h3>
                            </div>
                          
                        </div>
                    </div>

                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                    <div class="container mt-4">
                        <div class="text-center">
                            <table class="table table-borderless">
                                <tr>
                                    <td id="id_mf">
                                        <fieldset class="p-3" style="background-color:#F5FAFF;">
                                            <legend class="w-auto">
                                                Mainhead
                                            </legend>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="mainhead" id="mainhead_m" value="M" title="Miscellaneous" checked>
                                                <label class="form-check-label" for="mainhead_m">Misc</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input" name="mainhead" id="mainhead_f" value="F" title="Regular">
                                                <label class="form-check-label" for="mainhead_f">Regular</label>
                                            </div>
                                        </fieldset>
                                    </td>
                                    <td>
                                        <fieldset class="p-2">
                                            <legend>Date</legend>
                                            <?php
                                            $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                            $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                            ?>
                                            <input type="text" class="form-control dtp" size="10" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                        </fieldset>
                                    </td>
                                    <td id="rs_actio_btn1">
                                        <fieldset class="p-2"><br />
                                            <button type="button" class="btn btn-primary" id="btn1">Submit</button>
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
                            <div id="res_loader"></div>
                        </div>
                        <div id="dv_res1"></div>
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
    $(document).on("change", "input[name='mainhead']", function() {
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        $.ajax({
            // url: '../common/get_cl_print_mainhead.php',
            url: '<?php echo base_url('Listing/report/get_cl_print_mainhead'); ?>',
            cache: false,
            async: true,
            data: {
                mainhead: mainhead,
                board_type: board_type
            },
            beforeSend: function() {
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'GET',
            success: function(data, status) {
                $('#listing_dts').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });


    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#ldates").val();

        $.ajax({
            url: '<?php echo base_url('Listing/report/get_cat_judge'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead
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