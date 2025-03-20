<style>
    fieldset {
        padding: 5px !important;
        background-color: #F5FAFF !important;
        border: 1px solid #0083FF !important;
    }

    legend {
        background-color: #E2F1FF !important;
        width: 100% !important;
        text-align: center !important;
        border: 1px solid #0083FF !important;
        font-weight: bold !important;
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
<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> Moniotring Team - Verification Module </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form method="post">
                                <?= csrf_field() ?>
                                <div id="dv_content1">
                                <div style="text-align: center">
                                <div class="row">
                                        <div class="col-md-1">
                                            <label class="text-left">Date</label>
                                            <?php
                                            $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                            $get_date = $MonitoringModel->chksDate(date('Y-m-d', strtotime($cur_ddt)));
                                            $next_court_work_day = date("d-m-Y", strtotime($get_date));
                                            ?>
                                            <input type="text" size="10" class="form-control dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                        </div>
                                        <div class="col-md-1">
                                            <label class="text-left">Mainhead</label>
                                            <div class="row">
                                            <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                            <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="board_type" class="text-left">Board Type</label>
                                            <select class="form-control" name="board_type" id="board_type">
                                                <option value="0">-ALL-</option>
                                                <option value="J">Court</option>
                                                <option value="S">Single Judge</option>
                                                <option value="C">Chamber</option>
                                                <option value="R">Registrar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="text-left">Section Name</label>
                                            <select class="form-control" name="sec_id" id="sec_id">
                                                <option value="0">-ALL-</option>
                                                <?php if (!empty($sections)) {
                                                foreach ($sections as $section) { ?>
                                                <option value="<?php echo $section['id']; ?>"><?php echo $section['section_name']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="text-left">No. of Records</label>
                                            <select class="form-control" name="no_rec" id="no_rec">
                                                <option value="0">-ALL-</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500" selected>500</option>
                                                <option value="1000">1000</option>
                                                <option value="2000">2000</option>
                                                <option value="5000">5000</option>
                                                <option value="10000">10000</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="text-left">Listed/Not</label>
                                            <select class="form-control" name="listed_not" id="listed_not">
                                                <option value="0">Not Listed/Ready Not Verified</option>
                                                <option value="1">Listed Not Verified</option>
                                                <option value="2">Listed Verified</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="text-left">Purpose of Listing</label>
                                            <select class="form-control" size="1" name="listorder" id="listorder">
                                                <option value="0">Select</option>
                                                <?php if(!empty($purpose_list)) { ?>
                                                <?php foreach($purpose_list as $porpose){ ?>
                                                    <?php $temp_check = $lo =" ";
                                                    if ($lo == $porpose["code"])
                                                        echo '<option value="' . $porpose["code"] . '" selected="selected" ' . $temp_check . '>' . $porpose["lp"] . '</option>';
                                                    else
                                                        echo '<option value="' . $porpose["code"] . '"' . $temp_check . '>' . $porpose["lp"] . '</option>';
                                                    ?>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="text-left ml-3">Action</label>
                                            <div class="row" style="margin-top: -5px;">
                                            <input type="button" class="btn btn-primary" name="btn1" id="btn1" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                    </div>    














                                    <!--<div style="text-align: center">
                                        <table border="0" align="center">
                                            <tr valign="middle">
                                                <td>
                                                    <fieldset>
                                                        <legend>Date</legend>
                                                        <?php
                                                        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                                        $get_date = $MonitoringModel->chksDate(date('Y-m-d', strtotime($cur_ddt)));
                                                        $next_court_work_day = date("d-m-Y", strtotime($get_date));
                                                        ?>
                                                        <input type="text" size="10" class="dtp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />
                                                    </fieldset>
                                                </td>
                                                <td id="id_mf">
                                                    <fieldset>
                                                        <legend>Mainhead</legend>
                                                        <input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;
                                                        <input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset>
                                                        <legend><b>Board Type</b></legend>
                                                        <select class="ele" name="board_type" id="board_type">
                                                            <option value="0">-ALL-</option>
                                                            <option value="J">Court</option>
                                                            <option value="S">Single Judge</option>
                                                            <option value="C">Chamber</option>
                                                            <option value="R">Registrar</option>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset>
                                                        <legend>Section Name</legend>
                                                        <select class="ele" name="sec_id" id="sec_id">
                                                            <option value="0">-ALL-</option>
                                                            <?php if (!empty($sections)) {
                                                                foreach ($sections as $section) { ?>
                                                                    <option value="<?php echo $section['id']; ?>"><?php echo $section['section_name']; ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset>
                                                        <legend>No. of Records</legend>
                                                        <select class="ele" name="no_rec" id="no_rec">
                                                            <option value="0">-ALL-</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                            <option value="200">200</option>
                                                            <option value="500" selected>500</option>
                                                            <option value="1000">1000</option>
                                                            <option value="2000">2000</option>
                                                            <option value="5000">5000</option>
                                                            <option value="10000">10000</option>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset>
                                                        <legend>Listed/Not</legend>
                                                        <select class="ele" name="listed_not" id="listed_not">
                                                            <option value="0">Not Listed/Ready Not Verified</option>
                                                            <option value="1">Listed Not Verified</option>
                                                            <option value="2">Listed Verified</option>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset>
                                                        <legend>Purpose of Listing</legend>
                                                        <select size="1" name="listorder" id="listorder">
                                                            <option value="0">Select</option>
                                                            <?php if(!empty($purpose_list)) { ?>
                                                            <?php foreach($purpose_list as $porpose){ ?>
                                                                <?php $temp_check = $lo =" ";
                                                                if ($lo == $porpose["code"])
                                                                    echo '<option value="' . $porpose["code"] . '" selected="selected" ' . $temp_check . '>' . $porpose["lp"] . '</option>';
                                                                else
                                                                    echo '<option value="' . $porpose["code"] . '"' . $temp_check . '>' . $porpose["lp"] . '</option>';
                                                                ?>
                                                                <?php }
                                                            } ?>
                                                        </select>
                                                    </fieldset>
                                                </td>
                                                <td id="rs_actio_btn1">
                                                    <fieldset>
                                                        <legend>Action</legend>
                                                        <input type="button" class="btn btn-primary" name="btn1" id="btn1" value="Submit" />
                                                    </fieldset>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>-->
                                    <div id="dv_res1" class="mt-5"></div>
                                    <div id="overlay" style="display:none;">&nbsp;</div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Main content end -->

                </div> <!--end dv_content1-->
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
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
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        var sec_id = $("#sec_id").val();
        var no_rec = $("#no_rec").val();
        var listed_not = $("#listed_not").val();
        var listorder = $("#listorder").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/MonitoringTeam/verify_get'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: ldates,
                mainhead: mainhead,
                board_type: board_type,
                sec_id: sec_id,
                no_rec: no_rec,
                listed_not: listed_not,
                listorder: listorder,
                CSRF_TOKEN:CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                $("#dv_res1").html('<div style="margin:0 auto;margin-top:10px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>'); 
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
        var WinPrint = window.open('', '', 'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>