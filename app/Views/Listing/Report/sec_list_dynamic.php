<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Section List</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>



                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                    <div class="container mt-4">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Board Type</label>
                                    <select class="form-control" name="board_type" id="board_type">
                                        <option value="J" selected>Court</option>
                                        <option value="C">Chamber</option>
                                        <option value="R">Registrar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Date</label>
                                    <?php
                                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                    $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                    ?>
                                    <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Section Name</label>
                                    <select class="form-control" name="sec_id" id="sec_id">
                                        <option value="0">-ALL-</option>
                                        <?php foreach ($section_name as $ro_u): ?>
                                            <option value="<?php echo $ro_u['id']; ?>"><?php echo $ro_u['section_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <div class="text-center">
                                    <input type="button" class="btn btn-primary mb-2" name="btn1" id="btn_blue" value="SecList" style="background-color:#2980b9!important; border: #afa3a3 1px solid!important;color: #d5d5d5; font-weight: bold;" />
                                    <input type="button" class="btn btn-danger mb-2" name="btn2" id="btn_red" value="Deletion" style="background-color: #f98689!important;border: #afa3a3 1px solid!important; color: #fff; font-weight: bold;" />
                                    <input type="button" class="btn btn-success mb-2" name="btn3" id="btn_green" value="Addition" style="background-color: #00d529!important;border: #afa3a3 1px solid!important; color: #fff; font-weight: bold;" />
                                    <input type="button" class="btn btn-secondary mb-2" name="btn4" id="btn_all" value="All" />
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4" id="tblrow">
                        <!-- <table cellpadding="1" cellspacing="0" border="1" > -->
                        <!-- <tr style="font-weight: bold; background-color:#cccccc;">
                        <td style="width:5%;">SNo.</td>
                        <td style="width:20%;">Case No.</td>
                        <td style="width:35%;">Petitioner / Respondent</td>
                        <td style="width:40%;">
                                Petitioner/Respondent Advocate
                        </td>
                    </tr> -->
                    </table>
                            <div id="res_loader"></div>
                            <br><br>
                            <div id="dv_res1"></div>
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
    $(document).on("click", "#btn_all", function() {
        var list_type = $("#btn_all").val();
        get_cl_1(list_type);
    });
    $(document).on("click", "#btn_blue", function() {
        var list_type = $("#btn_blue").val();
        get_cl_1(list_type);
    });
    $(document).on("click", "#btn_red", function() {
        var list_type = $("#btn_red").val();
        get_cl_1(list_type);
    });
    $(document).on("click", "#btn_green", function() {
        var list_type = $("#btn_green").val();
        get_cl_1(list_type);
    });

    function get_cl_1(list_type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var list_dt = $("#ldates").val();
        var board_type = $("#board_type").val();
        var sec_id = $("#sec_id").val();
        $.ajax({
            url: '<?php echo base_url('Listing/Report/sec_list_dynamic_get_data'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                board_type: board_type,
                sec_id: sec_id,
                list_type: list_type
            },
            beforeSend: function() {
                $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                $('#tblrow').html(data);
                $('#dv_res1').html('');
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