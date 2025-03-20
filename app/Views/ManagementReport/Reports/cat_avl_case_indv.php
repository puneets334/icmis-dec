<?= view('header') ?>
<style>
    fieldset {
        padding: 5px;
        background-color: #F5FAFF;
        border: 1px solid #0083FF;
    }

    legend {
        background-color: #E2F1FF;
        width: 100%;
        text-align: center;
        border: 1px solid #0083FF;
        font-weight: bold;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4,
    #res_on_off,
    #resh_from_txt {
        display: none;
    }

    .toggle_btn {
        text-align: left;
        color: #00cc99;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
    }

    div,
    table,
    tr,
    td {
        font-size: 10px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header heading">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="card-title">Categoray wise cases available with roster</h3>
                        </div>
                    </div>
                </div>

                <form method="post">
                    <?= csrf_field() ?>
                    <table border="0" align="center">
                        <tr valign="middle">
                            <td>
                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Board Type</b></legend>
                                    <select class="ele" name="board_type" id="board_type">
                                        <option value="J">Court</option>
                                    </select>
                                </fieldset>
                            </td>
                            <td style="display: none;">
                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Court No.</b></legend>
                                    <select class="ele" name="court_no" id="court_no">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                    </select>
                                </fieldset>
                            </td>
                            <td>
                                <fieldset>
                                    <legend>Date</legend>
                                    <?php
                                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                    $next_court_work_day = date("d-m-Y", strtotime($cur_ddt));
                                    ?>
                                    <input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?= esc($next_court_work_day) ?>" readonly />
                                </fieldset>
                            </td>

                            <td>
                                <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                    <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Data Save</b></legend>
                                    <select class="ele" name="data_save" id="data_save">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </fieldset>
                            </td>
                            <td id="rs_actio_btn1">
                                <fieldset>
                                    <legend>Action</legend>
                                    <input type="button" name="btn1" id="btn1" value="Submit" />
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <div id="res_loader"></div>
    </div>

    <div id="dv_res1"></div>
</div>
</div>
</section>



<script>
    $(function() {
        $("#ldates").datepicker();
    });
    $(document).on("click", "#btn1", function() {
        get_cl_1();
    });

    function get_cl_1() {
        var ldates = $("#ldates").val();
        var board_type = $("#board_type").val();
        var court_no = $("#court_no").val();
        var data_save = $("#data_save").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            //url: 'cat_avl_case_indv_get.php',
            url: '<?php echo base_url('ManagementReports/Report/catAvlCaseIndvGet'); ?>',
            cache: false,
            async: true,
            data: {
                list_dt: ldates,
                board_type: board_type,
                court_no: court_no,
                data_save: data_save,
                CSRF_TOKEN: csrf,
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