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
        font-size: 12px;
        autosize: 1;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">SINGLE JUDGE ADVANCE CAUSE LIST PRINT MODULE</h3>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>
                                    <form method="post">
                                        <?= csrf_field() ?>
                                        <table border="0" align="center" class="w-75 mx-auto">
                                            <tr valign="middle">
                                                <td id="id_dts">
                                                    <fieldset>
                                                        <legend>Weekly List Dates</legend>
                                                        <select class="ele" name="listing_dts" id="listing_dts">
                                                            <option value="-1" selected>SELECT</option>
                                                            <?php if (!empty($listing_dates)): ?>
                                                                <?php foreach ($listing_dates as $date): ?>
                                                                    <option value="<?= date('Y-m-d', strtotime($date['from_dt'])) . "_" . date('Y-m-d', strtotime($date['to_dt'])) ?>">
                                                                        <?= date('d-m-Y', strtotime($date['from_dt'])) . " to " . date('d-m-Y', strtotime($date['to_dt'])) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <option value="-1" selected>EMPTY</option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td id="rs_actio_btn1">
                                                    <fieldset>
                                                        <legend>Action</legend>
                                                        <input type="button" name="btn1" id="btn1" value="Submit" />
                                                    </fieldset>
                                                </td>
                                                <td id="res_on_off">
                                                    <fieldset>
                                                        <legend>Reshuffle</legend>
                                                        <input type="text" name="resh_from_txt" id="resh_from_txt" value="0" maxlength="4" size="5" />
                                                        <span id="resf_span" style="background: #5fa3f9; border: #ffffff; color: #ffffff; height: 12px; padding: 4px;"><b>FROM</b></span>
                                                        <input type='button' name='re_shuffle' id='re_shuffle' value='Re-Shuffle' />
                                                    </fieldset>
                                                </td>
                                            </tr>
                                        </table>


                                    </form>



                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div id="res_loader"></div>

                <div id="dv_res1"></div>
            </div>
        </div>
    </div>
    </div>
</section>

<script>
    $(document).on("click", "#btn1", function() {

        get_cl_1();
    });

    function get_cl_1() {

        var mainhead = "M";
        var list_dt = $("#listing_dts").val();

        if (list_dt == -1) {
            alert('Date formate incorrect');
            return false;
        }

        var board_type = 'S';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({

            url: base_url + "/Listing/SingleJudgeAdvance/get_cause_list_single_judge_advance",

            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken()
                $('#dv_res1').html(data);
                if (data)
                    $('#res_on_off').show();
            },
            error: function(xhr) {
                updateCSRFToken()
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
    $(document).on("click", "#resf_span", function() {
        $("#resh_from_txt").toggle("slow", "linear");
    });

    $(document).on("click", "#re_shuffle", function() {
        var list_dt = $("#listing_dts").val();
        var from_cl_no = $("#resh_from_txt").val();
        var board_type = 'S';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        updateCSRFToken();
        $.ajax({


            url: base_url + "/Listing/SingleJudgeAdvance/call_reshuffle_function_single_judge_advance",

            data: {
                list_dt: list_dt,
                board_type: board_type,
                from_cl_no: from_cl_no,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#res_loader').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();

                $('#res_loader').html(data.message);
                if (updateCSRFToken()) {
                    get_cl_1();
                }

            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).on("click", "#ebublish", function() {
        var prtContent = $("#prnnt").html();
        var encprtContent = JSON.stringify(prtContent);
        var mainhead = 'M';
        var list_dt = $("#listing_dts").val();
        var board_type = 'S';
        var weekly_number = $(this).data('weekly_number');
        var weekly_year = $(this).data('weekly_year');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({

            url: base_url + "/Listing/SingleJudgeAdvance/cl_print_save_single_judge_advance",
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                board_type: board_type,
                weekly_number: weekly_number,
                weekly_year: weekly_year,
                encprtContent: encprtContent,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {

                $('#res_loader').html(`
    <div style="display:flex; justify-content:center; align-items:center; width:100%;">
        <img src="../../images/load.gif" alt="Loading..."/>
    </div>
`);



            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#res_loader').html(data.message);

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    //function CallPrint(){
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var mainhead = 'M';
        var list_dt = $("#listing_dts").val();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>