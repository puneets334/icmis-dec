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
        </style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">SINGLE JUDGE ADVANCE CAUSE LIST DROP NOTE PRINT</h3>
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
                                                        <legend>Single Judge Advance Listing Dates</legend>
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
                                            </tr>
                                        </table>
                                    </form>



                                </div>
                            </div>

                            <div id="dv_res1" style="text-align: left;"></div>
                            <div id="dv_res2" style="text-align: left;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).on("click", "#btn1", function() {
        
        var mainhead = "M";
        var list_dt = $("#listing_dts").val();
        
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (list_dt == "-1") {
            alert('Listing Dates incorrect')
        }
        $.ajax({
            url: base_url + "/Listing/SingleJudgeAdvance/note_field",

            data: {
                list_dt: list_dt,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {

                $('#dv_res1').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken()
                $('#dv_res1').html(data);
            },
            error: function(xhr) {
                updateCSRFToken()
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

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

    //     $(document).on("click", "#prnnt1", function() {
    //     var prtContent = $("#prnnt").html();
    //     var mainhead = get_mainhead();
    //     var list_dt = $("#listing_dts").val();
    //     var temp_str = prtContent;
    //     var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
    //     WinPrint.document.write(temp_str);
    //     WinPrint.document.close();
    //     WinPrint.focus();
    //     WinPrint.print();
    // });
</script>