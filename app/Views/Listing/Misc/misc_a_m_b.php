<?= view('header') ?>
<style>
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

    .bold-row td,th {
        font-weight: bold;
    }

    .card .row b {
        color: inherit;
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
                            <div class="text-center">
                                <div class="card-body">
                                    <?php include("get_otp.php"); ?>
                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-md-4 mb-3">
                                                <label for="ldates">Listing Date</label>
                                                <?php
                                                $holiday_str = next_holidays();
                                                $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                                $next_court_work_day = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
                                                ?>
                                                <input type="text" class="form-control dtpp" name="ldates" id="ldates" value="<?php echo $next_court_work_day; ?>" readonly />
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="pre_after_notice_sel">Pre/After Notice</label>
                                                <select class="form-control" name="pre_after_notice_sel" id="pre_after_notice_sel">
                                                    <option value="0">-All-</option>
                                                    <option value="1">Pre Notice</option>
                                                    <option value="2">After Notice</option>
                                                </select>
                                            </div>

                                            <!--<div class="col-md-4 mb-3" style="display:none;">-->
                                            <div class="col-md-4 mb-3">    
                                                <label for="short_non_short_sel">Short/Non Short Category</label>
                                                <select class="form-control" name="short_non_short_sel" id="short_non_short_sel">
                                                    <option value="3">All Category</option>
                                                    <option value="0">-As per Misc./NMD Days-</option>
                                                    <option value="1">Short Category</option>
                                                    <option value="2">Non Short Category</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-md-6 mb-3">
                                                <label for="subhead">Subhead</label>
                                                <select name="subhead" id="subhead" class="form-control" multiple="multiple" size="8">
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
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="case_type">Case Type</label>
                                                <select name="case_type" id="case_type" class="form-control" multiple="multiple" size="8">
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
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8 mb-3">
                                                <label for="subject_cat">Subject Main Category</label>
                                                <select name="subject_cat" id="subject_cat" class="form-control" multiple="multiple" size="10">
                                                    <option value="all" selected="selected">-ALL-</option>
                                                    <?php if (!empty($submasters)): ?>
                                                        <?php foreach ($submasters as $row): ?>
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
                                                    <?php else: ?>
                                                        <option>No data available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8 mb-3">
                                                <div class="form-group">
                                                    <div class="jud_all_al">
                                                        <?php
                                                        $mf = "M";
                                                        //$jud_count = "2";
                                                        $board_type = "J";
                                                        get_allocation_judge_m_alc_b($mf, $next_court_work_day, $board_type);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="listing_purpose">Purpose of Listing</label>
                                                    <select name="purpose" id="listing_purpose" multiple="multiple" size="13" class="form-control">
                                                        <option value="all" selected="selected">-ALL-</option>
                                                        <?php if (!empty($getListPurposes)): ?>
                                                            <?php foreach ($getListPurposes as $row): ?>
                                                                <option value="<?= $row['code'] ?>"><?= $row['code'] ?>. <?= $row['purpose'] ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <option value="">Error...</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>         
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-header text-center bg-light">
                                                        <strong>Allotment of Cases</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Number of Cases per Bench -->
                                                        <input type="hidden" name="noc" id="noc" value="1">

                                                        <div class="form-group">
                                                            <label for="partno">Part No.:</label>
                                                            <input type="text" name="partno" id="partno" value="1" class="form-control" size="5">
                                                        </div>

                                                        <?php if ($isAllowed): ?>
                                                            <div class="form-check">
                                                                <input type="radio" name="main_supp" id="main_supp_1" value="1" class="form-check-input" checked>
                                                                <label class="form-check-label" for="main_supp_1">Main Cause List</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="radio" name="main_supp" id="main_supp_2" value="2" class="form-check-input">
                                                                <label class="form-check-label" for="main_supp_2">Supplementary Cause List</label>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="form-check">
                                                            <input type="checkbox" name="select_advance" id="select_advance" value="Y" class="form-check-input" checked>
                                                            <label class="form-check-label" for="select_advance">Allocate From Advance List (Court dated & Mention Memo are exceptional)</label>
                                                        </div>

                                                        <?php if ($isAllowed): ?>
                                                            <div id="doa_and_otp_btn" class="mt-3">
                                                            <button type="button" name="doa" id="doa" class="btn btn-primary btn-block">Do Allotment</button>
                                                                <!--<?php //if ($isOtpVerified): ?>
                                                                    <button type="button" name="doa" id="doa" class="btn btn-primary btn-block">Do Allotment</button>
                                                                <?php //else: ?>
                                                                    <button type="button" name="getOTP" id="getOTP" class="btn btn-primary btn-block">Get OTP</button>
                                                                <?php //endif; ?>-->
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


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
    /*$('.dtpp').datepicker({
        beforeShowDay: unavailable,
        minDate: 0,
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2050',
        autoclose: true
    });*/
    //});

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

    $(document).ready(function() {
        $(document).on("changeDate", "#ldates", function() {
            var list_dt = $("#ldates").val();
            var mainhead = "M";
            var bench = "J";
            ddd(mainhead, list_dt, bench);
        });
    });


    function ddd(mainhead, list_dt, bench) {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
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
                if (data == 'expired') {
                    //alert("OTP is not generated for Selected Listing Date, Please regenerate OTP !!");
                    $('#doa_and_otp_btn').load('a_m_b #doa_and_otp_btn');
                    // get_record_t();
                } else {
                    $('#doa_and_otp_btn').load('a_m_b #doa_and_otp_btn');
                }
                getAllocationJudges(list_dt, mainhead, bench);
            },
            error: function(xhr) {
                updateCSRFToken();
            }
        });


        //getAllocationJudges(list_dt, mainhead, bench);
        //setTimeout(function() {
            
        //}, 900)

    }

    async function getAllocationJudges(list_dt, mainhead, bench){
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
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
                    //$('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                    $(".jud_all_al").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                type: 'POST',
                success: function(data, status) {
                    $('.jud_all_al').html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
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

    function generate_otp_open_modal(next_dt) {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
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
                //$('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $('#dv_res2').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
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
                    $('#dv_res2').html('');
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
            
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
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
                        //alert("Verified OTP is expired, Please regenerate OTP !!");
                        //get_record_t();
                    }
                    //updateCSRFToken();
                    coram_q_b (subhead, case_type, subject_cat, list_dt, mainhead, noc, partno, cchk_sel, cchk_unsel, select_advance, listing_purpose, pre_after_notice_sel, short_non_short_sel);
                }
            });
            //END
            <?php //} 
            ?>


                //coram_q_b (subhead, case_type, subject_cat, list_dt, mainhead, noc, partno, cchk_sel, cchk_unsel, select_advance, listing_purpose, pre_after_notice_sel, short_non_short_sel);

                /*setTimeout(function() {

                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    //url: 'coram_q_b.php',
                    url: "<?php //echo base_url('Listing/AllocationMisc/coram_q_b'); ?>",
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

            }, 600)*/


        }
    });


    async function coram_q_b (subhead, case_type, subject_cat, list_dt, mainhead, noc, partno, cchk_sel, cchk_unsel, select_advance, listing_purpose, pre_after_notice_sel, short_non_short_sel){
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/AllocationMisc/coram_q_b'); ?>",
            cache: false,
            //async: true,
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
    }

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
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/AllocationMisc/get_listing_purps'); ?>",
            cache: false,
            async: true,
            data: {
                main_supp: main_supp,CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('#rs_jg').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
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