<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Listing Statistics</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo form_open();
                        csrf_field();
                        //include ('../../mn_sub_menu.php');
                        ?>
                        <div id="dv_content1">

                            <div style="text-align: center">
                                <table>
                                    <tr valign="middle">
                                        <td>
                                            <fieldset>
                                                <legend>Date</legend>
                                                <?php
                                                // include_once("../../js/holiday_ck.php");
                                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                                // $next_court_work_day = date("d-m-Y"));    
                                                ?>
                                                <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates'
                                                    value="<?php echo date("d-m-Y"); ?>" readonly />

                                            </fieldset>
                                        </td>
                                        <td id="rs_actio_btn1">
                                            <fieldset style="margin-bottom: 127px;">
                                                <legend>Action</legend>
                                                <input type="button" name="btn1" id="btn1" value="Submit" />
                                            </fieldset>
                                            <?php // field_action_btn1(); 
                                            ?>
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
        var board_type = $("#board_type").val();
        $.ajax({
            url: '<?php echo base_url('Listing/report/get_listing_statistics'); ?> ',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: ldates,
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