<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">SECTION LIST</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>



                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                    <div id="dv_content1" class="container mt-4">

                        <div class="text-center">
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Board Type</label>
                                            <select class="form-control" name="board_type" id="board_type">
                                                <option value="0">-ALL-</option>
                                                <option value="J">Court</option>
                                                <option value="C">Chamber</option>
                                                <option value="R">Registrar</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <fieldset class="p-2">
                                            <label for="sec_id">Tentative Listing Date</label>
                                            <?php
                                            $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                            $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                            ?>
                                            <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                        </fieldset>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="sec_id">Section Name</label>
                                        <select class="form-control" name="sec_id" id="sec_id">
                                            <option value="0">-ALL-</option>
                                            <?php foreach ($section_name as $ro_u) {
                                                $ro_id = $ro_u['id'];
                                                $ro_name = $ro_u['section_name'];
                                            ?>
                                                <option value="<?php echo $ro_id; ?>"><?php echo $ro_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <fieldset class="p-2"><br/>
                                            <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                                        </fieldset>
                                    </div>
                                </div>

                              
                            </form>

                            <div id="res_loader" class="text-center"></div>
                        </div>
                        <input name="prnnt1" type="button" id="prnnt1" value="Print" style="display:none;">
                        <div id="prnnt" style="font-size:12px;">
                        <div align="center" id="getlogo" style="font-size:12px;display:none;" class="mb-5">
                            <img src="<?php echo base_url('images/scilogo.png'); ?>" width="50px" height="80px" /><br />
                            <span style="text-align: center;font-weight: 600;font-size: 14px;font-family: verdana;" align="center">
                                SUPREME COURT OF INDIA
                            </span>
                        </div>

                        <div id="dv_res1"></div>
                       
                      </div>
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
        $("#getlogo").hide();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var ldates = $("#ldates").val();
        var board_type = $("#board_type").val();
        var sec_id = $("#sec_id").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Report/seclist_get'); ?>',
            cache: false,
            async: true,
            type: 'POST',
            data: {
                CSRF_TOKEN: csrf,
                ldates: ldates,
                board_type: board_type,
                sec_id: sec_id
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },

            success: function(data, status) {
                if(data === 'Section List Not Available/May be not published yet'){
                    $("#getlogo").hide();
                    $("#prnnt1").hide();
                    $('#dv_res1').html(data);
                } else {
                    $("#getlogo").show();
                    $("#prnnt1").show();
                    $('#dv_res1').html(data);
                }
                
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