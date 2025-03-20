<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">ADVANCE ALLOCATION MODULE</h5>
                    </div>

                    <?php
                    $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                    $next_court_work_day1 = date("d-m-Y", strtotime(next_court_working_date($cur_ddt)));
                    //pr($next_court_work_day1);
                    ?>

                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="text-center">
                                <div class="row justify-content-center">

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ldates"><b>Listing Date</b></label>
                                                    <input type="text" class="form-control dtpp" name="ldates" id="ldates" value="<?php echo $next_court_work_day1; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pre_after_notice_sel"><b>Pre/After Notice</b></label>
                                                    <select class="form-control" name="pre_after_notice_sel" id="pre_after_notice_sel">
                                                        <option value="0">-All-</option>
                                                        <option value="1">Pre Notice</option>
                                                        <option value="2">After Notice</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="display:none;">
                                                <div class="form-group">
                                                    <label for="short_non_short_sel"><b>Short/Non Short Category </b></label>
                                                    <select class="form-control" name="short_non_short_sel" id="short_non_short_sel">
                                                        <option value="3">All Category</option>
                                                        <option value="0">-As per Misc./NMD Days-</option>
                                                        <option value="1">Short Category</option>
                                                        <option value="2">Non Short Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="subheading"><b>Subhead </b></label>
                                                    <select class="form-control" name="subheading" id="subheading" multiple size="8">
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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="casetype"><b>Case Type</b></label>
                                                    <select class="form-control" name="casetype" id="casetype" multiple size="8">
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

                                        <div class="form-group">
                                            <label for="subject_cat"><b>Subject Main Category </b></label>
                                            <select class="form-control" name="subject_category" id="subject_cat" multiple size="10">
                                                <option value="all" selected="selected">-ALL-</option>
                                                <?php if (!empty($submasters)) : ?>
                                                    <?php foreach ($submasters as $row) : ?>
                                                        <?php
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
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group jud_all_al">
                                                <?php  echo $allocation; ?>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="listing_purpose"><b>Purpose of Listing</b></label>
                                                    <select class="form-control" name="purpose" id="listing_purpose" multiple size="13">
                                                        <option value="all" selected="selected">-ALL-</option>
                                                        <?php foreach ($purposes as $purpose): ?>
                                                            <option value="<?= esc($purpose['code']); ?>">
                                                                <?= esc($purpose['code']) . '. ' . esc($purpose['purpose']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>
                            <div id="dv_res2"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>



<script>
    $(document).ready(function() {
        function unavailable(date) {
            return [true, ''];
        }

        var today = new Date();
        $('.datepicker').datepicker({
            beforeShowDay: unavailable,
            minDate: today,
            format: 'dd-mm-yyyy', // Set date format
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
        $('.datepicker').datepicker("setDate", today);
        $('.datepicker').datepicker('setStartDate', today);

    });


    //$(document).on("changeDate", "#ldates", function() {
    $(document).on("change", "#ldates", function() {
        // alert('hlo');

        var list_dt = $("#ldates").val();
        var mainhead = "M";
        var bench = "J";
        ddd(mainhead, list_dt, bench);



    });

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


    function ddd(mainhead, list_dt, bench)
    {

        $.ajax({


            url: "<?php echo base_url('Listing/Allocation/get_allocation_judges_m_advance/'); ?>",

            data: {

                mainhead: mainhead,
                list_dt: list_dt,
                bench: bench,
                // CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'GET',
            success: function(data, status) {
                // updateCSRFToken();
                $('.jud_all_al').html(data);
            },
            error: function(xhr) {
                // updateCSRFToken();
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
        // Prevent multiple clicks
        if ($(this).data('loading')) return; // If already loading, do nothing
        $(this).data('loading', true); // Set loading state

        var list_dt = $("#ldates").val();
        var noc = $("#noc").val();
        var partno = $("#partno").val();
        var listing_purpose = $("#listing_purpose").val();
        var subhead = $("#subheading").val();
        var case_type = $("#casetype").val();
        var subject_cat = $("#subject_cat").val();
        var pre_after_notice_sel = $("#pre_after_notice_sel").val();
        var short_non_short_sel = $("#short_non_short_sel").val();
        var mainhead = "M";
        var cchk_sel = "";
        var cchk_unsel = "";

        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk" && this.checked) {
                var tot_id = $(this).val().split("|");
                cchk_sel += $(this).val() + "|" + $("#or_" + tot_id[1]).val() + "|" + "JG";
            }
        });

        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk" && !$(this).is(':checked')) {
                cchk_unsel += $(this).val() + "JG";
            }
        });

        if (cchk_sel == "") {
            $('#dv_res2').html('<table width="100%" align="center"><tr><td class="class_red">Select at least one bench</td></tr></table>');
            $(this).data('loading', false); // Reset loading state
            return false;
        } else {
            $.ajax({
                url: "<?php echo base_url('Listing/Allocation/advance_allocation_process/'); ?>",
                data: {
                    subhead: subhead,
                    casetype: case_type,
                    subject_cat: subject_cat,
                    list_dt: list_dt,
                    listing_purpose: listing_purpose,
                    pre_after_notice_sel: pre_after_notice_sel,
                    short_non_short_sel: short_non_short_sel,
                    chked_jud_sel: cchk_sel,
                    chked_jud_unsel: cchk_unsel,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function()
                {
                    $('#dv_res2').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    updateCSRFToken();
                    $('#dv_res2').html(data);
                    var bench = "J";

                    // Ensure ddd does not trigger the click event again
                    if (typeof ddd === 'function') {
                        ddd(mainhead, list_dt, bench);
                    }

                    // Reset loading state
                    $("#doa").data('loading', false);
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    $("#doa").data('loading', false); // Reset loading state on error
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
        var fr_vl = parseInt($("#fr_" + str_id[1]).val());
        var or_vl = parseInt($("#or_" + str_id[1]).val());
        var tot = parseInt(fr_vl + or_vl);
        $("#tot_" + str_id[1]).val(tot);
    }

    $(document).on("change", "input[name='main_supp']", function() {
        var main_supp = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "main_supp" && this.checked)
                main_supp = $(this).val();
        });
        $.ajax({


            url: "<?php echo base_url('Listing/Allocation/get_listing_purps/'); ?>",
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