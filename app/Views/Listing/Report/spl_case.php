<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">SPECIAL CASES MODULE</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?php
                    echo form_open();
                    csrf_field();
                    ?>
                    <div class="container mt-4">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <strong>Mainhead</strong>
                                        </div>
                                        <div class="card-body">
                                            <fieldset class="p-2">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" name="mainhead" id="mainhead_m" value="M" title="Miscellaneous" class="form-check-input" checked>
                                                    <label class="form-check-label" for="mainhead_m">Misc</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" name="mainhead" id="mainhead_r" value="F" title="Regular" class="form-check-input">
                                                    <label class="form-check-label" for="mainhead_r">Regular</label>
                                                </div>
                                            </fieldset>

                                            <br />

                                            <fieldset class="p-2">
                                                <legend class="w-auto">Cause List Date</legend>
                                                <input type="text" class="form-control dtp" name="listing_dts" id="listing_dts" value="<?php echo date('d-m-Y'); ?>" readonly />
                                            </fieldset>

                                            <br />

                                            <fieldset class="p-2">
                                                <legend class="w-auto text-center">Board Type</legend>
                                                <select class="form-control" name="board_type" id="board_type">
                                                    <option value="0">-ALL-</option>
                                                    <option value="J">Court</option>
                                                    <option value="C">Chamber</option>
                                                    <option value="R">Registrar</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <strong>Case Type</strong>
                                        </div>
                                        <div class="card-body">
                                            <fieldset class="p-2">
                                                <select class="form-control" name="case_type" id="case_type" multiple size="8">
                                                    <option value="all" selected>-ALL-</option>
                                                    <?php
                                                    foreach ($getCaseTpe as $row) {
                                                        $style = ($row["nature"] == 'C') ? 'background: #c8fbe7;' : 'background:#f7cad2;';
                                                        echo '<option style="' . $style . '" value="' . $row["casecode"] . '">' . str_replace("No.", "", $row["short_description"]) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            <strong>Purpose of Listing</strong>
                                        </div>
                                        <div class="card-body">
                                            <fieldset class="p-2">
                                                <select class="form-control" name="listing_purpose" id="listing_purpose" multiple size="8">
                                                    <option value="all" selected>-ALL-</option>
                                                    <?php
                                                    foreach ($reportSplCase as $val) {
                                                        echo '<option value="' . $val['code'] . '">' . $val['code'] . ' ' . $val['purpose'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center mt-3">
                                    <fieldset>
                                        <input type="button" name="btn1" id="btn1" value="Submit" class="btn btn-primary" />
                                    </fieldset>
                                </div>
                            </div>

                            <div id="res_loader" class="mt-3"></div>
                        </div>

                        <div id="dv_res1" class="mt-3"></div>
                    </div>


                    <?php echo form_close(); ?>




                </div>
            </div>
        </div>
</section>

<script>
    function unavailable(date) {
        dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
        if ($.inArray(dmy, unavailableDates) == -1) {
            return [true, ""];
        } else {
            return [false, "", "Unavailable"];
        }
    }

    //maxDate : 'today',
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            // beforeShowDay: unavailable,
            minDate: 0,
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
        $('#dv_res1').html("");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var lp = $("#listing_purpose").val();
        var board_type = $("#board_type").val();
        var case_type = $("#case_type").val();
        if (board_type == 0) {
            alert("select Board Type");
            return false;
        }



        $.ajax({
            url: '<?php echo base_url('Listing/report/get_spl_case'); ?> ',

            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: csrf,
                list_dt: list_dt,
                mainhead: mainhead,
                lp: lp,
                board_type: board_type,
                case_type: case_type
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



    function lst_casesdf(str) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var clno = $("#clno_" + str).val();
        var ldt = $("#ldt_" + str).val();
        var mf = $("#mf_" + str).val();
        var avlj = $("#avlj_" + str).val();
        var avldno = $("#avldno_" + str).val();
        var main_suppl = $("#main_suppl_" + str).val();


        var r = confirm("Are you sure want to list these cases");
        if (r == true) {
            txt = "Case listing as You pressed OK!";
            $.ajax({
                url: '<?php echo base_url('Listing/report/lst_spl_cases'); ?>',
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: csrf,
                    clno: clno,
                    list_dt: ldt,
                    mainhead: mf,
                    avlj: avlj,
                    avldno: avldno,
                    roster_id: str,
                    main_suppl: main_suppl
                },
                beforeSend: function() {
                    $('#res_loader').html(
                        '<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>'
                    );
                },
                type: 'POST',
                success: function(data, status, message) {
                    $('#res_loader').html(data.message);
                    $("#dv_res1").html("");
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        } else {
            txt = "Not Listing due to You pressed Cancel!";
        }
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