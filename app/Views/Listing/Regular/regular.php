<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">MISC. ALLOCATION MODULE (ROSTER BASED)</h5>
                    </div>


                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="text-center">

                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <fieldset class="border p-2">
                                            <legend class="w-auto px-2">Mainhead</legend>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="mainhead" id="mainheadM" value="M" title="Miscellaneous">
                                                <label class="form-check-label" for="mainheadM">M</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="mainhead" id="mainheadR" value="F" title="Regular" checked>
                                                <label class="form-check-label" for="mainheadR">R</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-2">
                                            <legend class="w-auto px-2">Listing Date</legend>
                                            <?php
                                            $holiday_str = next_holidays();
                                            $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                            $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
                                            ?>
                                            <input type="text" class="form-control dtpp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly>
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-3">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8 jud_all_al">
                                                <?php
                                                $mf = "F";
                                                $board_type = "J";
                                                // get_allocation_judge($mf,$next_court_work_day,$board_type); 
                                                ?>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="border p-2 fieldset-container">
                                                    <legend class="w-auto px-2 fieldset-legend"><b>Purpose of Listing</b></legend>
                                                    <select class="form-control" name="purpose" id="listing_purpose" multiple="multiple" size="13">
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <fieldset class="border p-2 mt-3">
                                            <legend class="w-auto px-2 text-center text-primary font-weight-bold">Allotment of Cases</legend>
                                            <div class="form-group">
                                                <label for="partno">Part No.:</label>
                                                <input type="text" class="form-control" name="partno" id="partno" value="1" size="5">
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="main_supp" id="main_supp_main" value="1" title="Main Cause List" checked>
                                                <label class="form-check-label" for="main_supp_main">Main Cause List</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="main_supp" id="main_supp_supp" value="2" title="Supplementary Cause List">
                                                <label class="form-check-label" for="main_supp_supp">Supplementary Cause List</label>
                                            </div>
                                            <button type="button" class="btn btn-primary mt-3" name="doa" id="doa">Do Allottment</button>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div id="dv_res2"></div>
                            <div id="dv_res3"></div>
                        </div>
                    </form>

                    <!-- <div id="jud_all_al" class="jud_all_al">
                    </div> -->
                </div>
            </div>


        </div>
    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>


<script>
    var list_dt = $("#ldates").val();
    var mainhead = "";
    var bench = "J";
    $('input[type=radio]').each(function() {
        if ($(this).attr("name") == "mainhead" && this.checked)
            mainhead = $(this).val();
    });
    ddd(mainhead, list_dt, bench);
    
    var leavesOnDates = <?= next_holidays_new(); ?>;
    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $('.dtpp').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: date,
            todayHighlight: true,
            changeMonth : true, 
            changeYear : true,
            yearRange : '1950:2050',
            datesDisabled: leavesOnDates,
            isInvalidDate: function(date) {
                return (date.day() == 0 || date.day() == 6);
            },
        });
    });

    $(document).on("change", "#ldates", function() {
        var list_dt = $("#ldates").val();
        var mainhead = "";
        var bench = "J";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        ddd(mainhead, list_dt, bench);
    });

    $("input[name='mainhead']").change(function() {
        var list_dt = $("#ldates").val();
        var mainhead = "";
        var bench = "J";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        ddd(mainhead, list_dt, bench);
    });



    function get_mainhead() {
        updateCSRFToken();
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }

    function ddd(mainhead, list_dt, bench) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({

            url: "<?php echo base_url('Listing/Allocation/get_allocation_judges_final'); ?>",

            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                bench: bench,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'GET',
            success: function(data, status) {
                updateCSRFToken();
                $('.jud_all_al').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }

    function chkall1(e) {
        var elm = e.name;
        if (document.getElementById(elm).checked) {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk")
                    this.checked = true;
            });
        } else {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk")
                    this.checked = false;
            });
        }

    }

    function get_main_supp() {

        var main_supp = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "main_supp" && this.checked)
                main_supp = $(this).val();
        });
        return main_supp;
    }

    $(document).on("click", "#doa", function() {

        $("#doa").hide();
        var list_dt = $("#ldates").val();
        var noc = $("#noc").val();
        var partno = $("#partno").val();
        var chk_tr = "";
        var cchk_sel = "";
        var cchk_unsel = "";
        var mainhead = "F";
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('input[type=checkbox]').each(function() {
            var fr_id = "";
            var or_id = "";
            var tot_id = "";
            if ($(this).attr("name") == "chk" && this.checked) {
                fr_id = $(this).val().split("|");
                or_id = $(this).val().split("|");
                tot_id = $(this).val().split("|");
                cchk_sel += $(this).val() + "|" + $("#fd_" + fr_id[1]).val() + "|" + $("#or_" + or_id[1]).val() + "|" + $("#tot_" + tot_id[1]).val() + "|" + "JG";
            }
        });
        
        var chall = 1;
        $('input[type=checkbox]').each(function() {
            if (chall > 1) {
                if ($(this).attr("name") == "chk" && !$(this).is(':checked')) {
                    cchk_unsel += $(this).val() + "JG";
                }
            }
            chall++;
        });
       
        $('#dv_res2').html(cchk_sel);
        if(cchk_sel == "") {
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Select atleast one bench</table>');
            $("#doa").show();
            return false;
        } else if (isEmpty(document.getElementById('listing_purpose'))) {
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Select Purpose of Listing</table>');
            $("#doa").show();
            return false;
        } else if (isEmpty(document.getElementById('partno'))) {
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red">Please Enter Part No.</table>');
            $("#doa").show();
            return false;
        } else {
            var mainhead = get_mainhead();
            updateCSRFToken();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var listing_purpose = $("#listing_purpose").val();
            $.ajax({

                url: "<?php echo base_url('Listing/Allocation/coram_q_r_b/'); ?>",

                data: {
                    list_dt: list_dt,
                    mainhead: mainhead,
                    noc: noc,
                    partno: partno,
                    cchk_sel: cchk_sel,
                    chked_jud_unsel: cchk_unsel,
                    listing_purpose: listing_purpose,
                    main_supp: get_main_supp(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {

                    $('#dv_res2').html(data);
                    var bench = "J";

                    if (updateCSRFToken()) {
                        ddd(mainhead, list_dt, bench);
                    }

                    $("#partno, #noc").val("");
                    $("#doa").show();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });

    function isEmpty(xx) {
        var yy = xx.value.replace(/^\s*/, "");
        if (yy == "") {
            xx.focus();
            return true;
        }
        return false;
    }

    function calc_tot(str) {
        str_id = str.split("_");
        var fr_vl = parseInt($("#fd_" + str_id[1]).val());
        var or_vl = parseInt($("#or_" + str_id[1]).val());
        var tot = parseInt(fr_vl + or_vl);
        $("#tot_" + str_id[1]).val(tot);
    }
    $(document).on("change", "input[name='main_supp']", function() {
        var main_supp = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/Allocation/get_listing_purps/'); ?>",
            data: {
                main_supp: main_supp,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#listing_purpose').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });

    $(document).ready(function() {
        $("input[name='main_supp']:first").prop("checked", true);
        $("input[name='main_supp']:checked").trigger('change');
    });

    $(function() {
        $("#ldates").datepicker();
    });
</script>