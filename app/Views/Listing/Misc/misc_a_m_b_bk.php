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

    /*#customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }*/

    .custom-table thead th {
        background: #072c76;
        color: #fff;
        font-family: 'noto_sansmedium';
    }

    .custom-table tbody td {
        font-size: 14px;
        color: #000;
    }

    .custom-table thead th:first-child,
    .custom-table tbody td:first-child {
        border-radius: 28px 0 0 28px;
        text-align: center;
    }

    .custom-table thead th,
    .custom-table tbody td {
        font-size: 14px;
        padding: 13px 7px;
        line-height: 14px;
    }

    .custom-table thead th:last-child,
    .custom-table tbody td:last-child {
        border-radius: 0 28px 28px 0;
    }

    .table-striped tr:nth-child(odd) td {
        background: #fff !important;
        box-shadow: none;
    }

    .table-striped tr:nth-child(even) td {
        background: #ECEEF2;
    }

    .table-striped tr:hover td {
        background: #1104583b !important;
    }

    .class_red {
        color: red;
    }

    .table3,
    .subct2,
    .subct3,
    .subct4 {
        display: none;
    }

    .misc_selcted_box:focus {
        background-color: #D55C21;
        position: static;
        width: 80px;
        height: 35px;
    }

    .misc_selcted_box {
        position: static;
        width: 80px;
        height: 35px;
    }

    .bold-row td,
    th {
        font-weight: bold;
    }

    .align_center {
        text-align: center;
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
                                <h3 class="card-title">MISC. ALLOCATION MODULE (ROSTER BASED)</h3>
                            </div>
                        </div>
                    </div>

                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div style="text-align: center">
                                <!-- <span style="font-weight: bold; color:#4141E0; text-decoration: underline;">ADVANCE ALLOCATION MODULE</span> -->
                                <?php
                                include("get_otp.php");
                                ?>
                                <table border="0" align="center" class="w-50 mx-auto">
                                    <tr valign="top">

                                        <td>
                                            <fieldset>
                                                <legend>Listing Date</legend>
                                                <?php

                                                $holiday_str = next_holidays();
                                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                                $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
                                                ?>
                                                <input type="text" size="10" class="dtpp" name='ldates' id='ldates' value="<?php echo $next_court_work_day; ?>" readonly />

                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Pre/After Notice</b></legend>
                                                <select class="ele" name="pre_after_notice_sel" id="pre_after_notice_sel">
                                                    <option value="0">-All-</option>
                                                    <option value="1">Pre Notice</option>
                                                    <option value="2">After Notice</option>
                                                </select>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset style="display:none; padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Short/Non Short Category</b></legend>
                                                <select class="ele" name="short_non_short_sel" id="short_non_short_sel" style="width:200px;">
                                                    <option value="3">All Category</option>
                                                    <option value="0">-As per Misc./NMD Days-</option>
                                                    <option value="1">Short Category</option>
                                                    <option value="2">Non Short Category</option>

                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>

                                <table border="0" align="center" class="w-75 mx-auto">
                                    <tr>
                                        <td class="subhead_class">
                                            <fieldset>
                                                <legend>Subhead</legend>
                                                <select name="subhead" id="subhead" multiple="multiple" size="8">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($subheadings)): ?>
                                                        <?php foreach ($subheadings as $subheading): ?>
                                                            <option value="<?= esc($subheading['stagecode']) ?>">
                                                                <?= esc(str_replace(["[", "]"], "", $subheading['stagename'])) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No subheadings available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>


                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Case Type</b></legend>
                                                <select name="case_type" id="case_type" multiple="multiple" size="8">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($caseTypes)): ?>
                                                        <?php foreach ($caseTypes as $caseType): ?>
                                                            <?php
                                                            $background = ($caseType['nature'] === 'C') ? '#c8fbe7' : '#f7cad2';
                                                            $description = str_replace("No.", "", $caseType['short_description']);
                                                            ?>
                                                            <option style="background: <?= esc($background); ?>;" value="<?= esc($caseType['casecode']); ?>">
                                                                <?= esc($description); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No case types available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>

                                <table border="0" class="table_sub_cat w-100 mx-auto" align="center">
                                    <tr>
                                        <td>
                                            <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                                <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Subject Main Category</b></legend>
                                                <select name="subject_cat" id="subject_cat" multiple="multiple" size="10">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($submasters)) : ?>
                                                        <?php foreach ($submasters as $row) : ?>
                                                            <?php
                                                            // Determine the background color and spacing based on subcode values
                                                            if ($row["subcode2"] == 0 && $row["subcode3"] == 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #FF7F50;";
                                                                $spaces = "";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] == 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #FFE4C4;";
                                                                $spaces = "&nbsp;&nbsp;";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] > 0 && $row["subcode4"] == 0) {
                                                                $bgcolor = "background: #CCFFCC;";
                                                                $spaces = "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            } elseif ($row["subcode2"] > 0 && $row["subcode3"] > 0 && $row["subcode4"] > 0) {
                                                                $bgcolor = "background: #CCFFAA;";
                                                                $spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            }
                                                            ?>
                                                            <option style="<?= $bgcolor; ?>" value="<?= esc($row["id"]); ?>">
                                                                <?= $spaces . $row["old_sc_c_kk"] . ' - ' . esc($row["sub_name4"]); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option>No data available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
                                <table border="0" align="center" class="w-75 mx-auto">

                                    <td class="jud_all_al" style="width: 60%; vertical-align: top;">
                                        <?php
                                        $mf = "M";
                                        //$jud_count = "2";
                                        $board_type = "J";
                                        get_allocation_judge_m_alc_b($mf, $next_court_work_day, $board_type);
                                        ?>
                                    </td>

                                    <td style="width: 40%; vertical-align: top;">
                                        <fieldset style="padding:5px; background-color:#F5FAFF; border:1px solid #0083FF;">
                                            <legend style="background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF;"><b>Purpose of Listing</b></legend>

                                            <select name="purpose" id="listing_purpose" multiple="multiple" size="13" style="width: 100%;">
                                                <option value="all" selected="selected">-ALL-</option>
                                                <?php if (!empty($getListPurposes)): ?>
                                                    <?php foreach ($getListPurposes as $row): ?>
                                                        <option value="<?= $row['code'] ?>"><?= $row['code'] ?>. <?= $row['purpose'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">Error...</option>
                                                <?php endif; ?>
                                            </select>
                                        </fieldset>
                                    </td>
                                </table>

                                <table border="0" align="center" class="w-25 mx-auto">

                                    <td>
                                        <fieldset>
                                            <legend style="text-align:center;color:#4141E0; font-weight:bold;">Allotment of Cases</legend>
                                            <!-- Number of Cases per Bench : -->
                                            <input type="hidden" name="noc" id="noc" value="1" size="5">
                                            <br />
                                            Part No.:<input type="text" name="partno" id="partno" value="1" size="5">
                                            <br />

                                            <?php if ($isAllowed): ?>
                                                Main Cause List&nbsp;<input type="radio" name="main_supp" id="main_supp" value="1" title="Main Cause List" checked="checked"><br />
                                                Supplementary Cause List&nbsp;<input type="radio" name="main_supp" id="main_supp" value="2" title="Supplementary Cause List">
                                            <?php endif; ?>

                                            <br />
                                            <input type="checkbox" name="select_advance" id="select_advance" value="Y" checked="checked"> Allocate From Advance List(Court dated & Mention Memo are exceptional)
                                            <br />

                                            <?php if ($isAllowed): ?>
                                                <div id="doa_and_otp_btn">
                                                    <!--<input type="button" name="doa" id="doa" value="Do Allotment" class="btn btn-primary">-->
                                                    <?php if ($isOtpVerified): ?>
                                                        <input type="button" name="doa" id="doa" value="Do Allotment" class="btn btn-primary">
                                                    <?php else: ?>
                                                        <input type="button" name="getOTP" id="getOTP" value="Get OTP" class="btn btn-primary">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </fieldset>
                                    </td>
                                </table>




                            </div>


                            <div id="dv_res2"></div>
                            <!--<div id="dv_res1"></div>-->
                    </form>
                    <!--<div id="jud_all_al"></div>-->
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->

<!-- jQuery UI -->
<!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>-->


<script>
    var unavailableDates = [<?= $holiday_str; ?>];
    //console.log(unavailableDates);                                                        ;
    function unavailable(date) {

        let parsedDate = new Date(date);
        dmy = parsedDate.getDate() + "-" + (parsedDate.getMonth() + 1) + "-" + parsedDate.getFullYear();
        if ($.inArray(dmy, unavailableDates) == -1) {
            return [true, ""];
        } else {
            return [false, "", "Unavailable"];
        }
    }
    //console.log(unavailable('02-01-2025'));
    //$(document).on("focus", ".dtpp", function() {
    $('.dtpp').datepicker({
        beforeShowDay: unavailable,
        minDate: 0,
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050',
        autoclose: true
    });
    //});

    $(document).ready(function() {
        $(document).on("changeDate", "#ldates", function() {
            var list_dt = $("#ldates").val();
            var mainhead = "M";
            var bench = "J";
            ddd(mainhead, list_dt, bench);
        });
    });


    function ddd(mainhead, list_dt, bench) {
        //console.log('ddddddddd');
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            //url: '../common/check_otp_verification.php',
            url: "<?php echo base_url('Listing/AllocationMisc/check_otp_verification/'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: 'M',
                bench: 'J',
                main_supp: get_main_supp(),
                from_function: 'ddd',
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                if (data == 'expired') {
                    //alert("OTP is not generated for Selected Listing Date, Please regenerate OTP !!");
                    $('#doa_and_otp_btn').load('a_m_b #doa_and_otp_btn');
                    // get_record_t();
                } else {
                    $('#doa_and_otp_btn').load('a_m_b #doa_and_otp_btn');
                }

            },
            error: function(xhr) {
                updateCSRFToken();
            }
        });

        setTimeout(function() {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                //url: '../common/get_allocation_judges_m_al_b.php',
                url: "<?php echo base_url('Listing/AllocationMisc/get_allocation_judges_m_al_b/'); ?>",
                cache: false,
                async: true,
                data: {
                    list_dt: list_dt,
                    mainhead: mainhead,
                    bench: bench,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {
                    $('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    //alert('test..');
                    $('.jud_all_al').html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });
        }, 900)

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

    function generate_otp_open_modal(next_dt) {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            //url: '../common/generate_otp_sml_mail.php',
            url: "<?php echo base_url('Listing/AllocationMisc/generate_otp_sml_mail'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: next_dt,
                mainhead: 'M',
                bench: 'J',
                main_supp: get_main_supp(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $('#dv_res2').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function(data, status) {
                //alert(status);
                $('#enterOTPDialog').modal('show');
                updateCSRFToken();
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
            }
        });
    }

    $(document).on("click", "#getOTP", function() {
        var list_dt = $("#ldates").val();
        generate_otp_open_modal(list_dt);

    });

    $("#enterOTPDialog .btn-otp").click(function() {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        checking();
        $otpData = $('#divOtpEntry :input').serialize();

        var list_dt = $("#next_dt").val();
        $.ajax({
            //url: '../common/verify_otp.php',
            url: "<?php echo base_url('Listing/AllocationMisc/verify_otp'); ?>",
            cache: false,
            async: true,
            data: {
                list_date: list_dt,
                mainhead: 'M',
                bench: 'J',
                main_supp: get_main_supp(),
                otpList: $otpData,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                console.log('data' + data);
                if (data == 'SUCCESS') {
                    $('#enterOTPDialog').modal('toggle');
                    //get_record_t();
                    $('#dv_res2').html('');
                    location.reload();
                    updateCSRFToken();
                    //$('#doa_and_otp_btn').load('a_m_b.php #doa_and_otp_btn');
                    //$('#doa_and_otp_btn').load('a_m_b #doa_and_otp_btn');
                }
                /*          else if(data=='5'){
                                alert("OTP Entry Attempts exaust !!");
                                exit;
                            }*/
                else {
                    alert("Entered OTP is Incorrect !!");
                    updateCSRFToken();
                }
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
                updateCSRFToken();
            }
        });
    });

    function checking() {
        var empty = 0;
        $('#divOtpEntry input[type=text]').each(function() {
            if (this.value == "") {
                empty++;
                $("#error").show();
            }
        })
        if (empty != 0) {
            alert('OTP must be entered !!')
            exit;
        }
    }


    $(document).on("click", "#doa", function() {
        $("#doa").hide();
        var list_dt = $("#ldates").val();
        var pre_after_notice_sel = $("#pre_after_notice_sel").val();
        var short_non_short_sel = $("#short_non_short_sel").val();
        var noc = $("#noc").val();
        var partno = $("#partno").val();
        var chk_tr = "";
        var cchk_sel = "";
        var cchk_unsel = "";
        var mainhead = "M";
        var listing_purpose = $("#listing_purpose").val();

        var subhead = $("#subhead").val();
        var case_type = $("#case_type").val();
        var subject_cat = $("#subject_cat").val();

        //    $('input[type=radio]').each(function (){
        //        if($(this).attr("name")=="mainhead" && this.checked)
        //            mainhead = $(this).val();
        //    });
        /*  var shortc = ""; var select_advance = "";
          if($("#shortc").is(':checked'))
              shortc = "N";
          else
              shortc = "Y";*/

        if ($("#select_advance").is(':checked'))
            select_advance = "Y";
        else
            select_advance = "N";

        $('input[type=checkbox]').each(function() {
            var fr_id = "";
            var or_id = "";
            var tot_id = "";
            if ($(this).attr("name") == "chk" && this.checked) {
                fr_id = $(this).val().split("|");
                or_id = $(this).val().split("|");
                tot_id = $(this).val().split("|");
                cchk_sel += $(this).val() + "|" + $("#fr_" + fr_id[1]).val() + "|" + $("#or_" + or_id[1]).val() + "|" + $("#tot_" + tot_id[1]).val() + "|" + "JG";
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

        //$('#dv_res2').html(cchk_sel);
        if (cchk_sel == "") {
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red align_center">Select atleast one bench</table>');
            $("#doa").show();
            return false;
        } else if (isEmpty(document.getElementById('partno'))) {
            $('#dv_res2').html('<table widht="100%" align="center"><tr><td class="class_red align_center">Please Enter Part No.</table>');
            $("#doa").show();
            return false;
        } else {
            <?php
            //alert($_SESSION['is_otp_verified']);
            //if(isset($_SESSION['is_otp_verified']) && $_SESSION['is_otp_verified']==true){ 
            ?>
            //Start Checking if already OTP verifief
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                //url: '../common/check_otp_verification.php',
                url: "<?php echo base_url('Listing/AllocationMisc/check_otp_verification/'); ?>",
                cache: false,
                async: true,
                data: {
                    list_dt: list_dt,
                    mainhead: 'M',
                    bench: 'J',
                    main_supp: get_main_supp(),
                    from_function: 'doa',
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data, status) {
                    if (data == 'expired') {
                        alert("Verified OTP is expired, Please regenerate OTP !!");
                        //get_record_t();
                    }
                    updateCSRFToken();
                },
                error: function(xhr) {
                    updateCSRFToken();
                }
            });
            //END
            <?php //} 
            ?>

            setTimeout(function() {

                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    //url: 'coram_q_b.php',
                    url: "<?php echo base_url('Listing/AllocationMisc/coram_q_b'); ?>",
                    cache: false,
                    async: true,
                    data: {
                        subhead: subhead,
                        case_type: case_type,
                        subject_cat: subject_cat,
                        list_dt: list_dt,
                        mainhead: mainhead,
                        noc: noc,
                        partno: partno,
                        chked_jud_sel: cchk_sel,
                        chked_jud_unsel: cchk_unsel,
                        main_supp: get_main_supp(),
                        select_advance: select_advance,
                        listing_purpose: listing_purpose,
                        pre_after_notice_sel: pre_after_notice_sel,
                        short_non_short_sel: short_non_short_sel,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    beforeSend: function() {
                        //$('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                        $("#dv_res2").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        $('#dv_res2').html(data);
                        var bench = "J";
                        //ddd(mainhead, list_dt, bench)
                        $("#partno, #noc").val("");
                        $("#doa").show();
                        updateCSRFToken();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                        updateCSRFToken();
                    }
                });

            }, 600)


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
        var fr_vl = parseInt($("#fr_" + str_id[1]).val());
        var or_vl = parseInt($("#or_" + str_id[1]).val());
        var tot = parseInt(fr_vl + or_vl);
        $("#tot_" + str_id[1]).val(tot);

        //alert(fr_vl);
    }

    function make_zero() {
        $(".make_zero").val(0);
    }

    $(document).on("change", "input[name='main_supp']", function() {
        var main_supp = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "main_supp" && this.checked)
                main_supp = $(this).val();
        });
        $.ajax({
            url: '../common/get_listing_purps.php',
            cache: false,
            async: true,
            data: {
                main_supp: main_supp
            },
            beforeSend: function() {
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#listing_purpose').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    });
    $(document).on("click", "#prnnt1", function() {
        var prtContent = $("#prnnt").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'border=1,left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
    $(document).on("click", "#prnnt_btn", function() {
        var prtContent = $("#prnnt2").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'border=1,left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>