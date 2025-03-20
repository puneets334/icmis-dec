<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">ADVANCE ALLOCATION WITH PRE-PONEMENT (Only TP)</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <?= csrf_field() ?>
                            <div id="dv_content1">
                                <div class="text-center">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ldates">Listing Date</label>
                                                    <input type="text" class="form-control dtp" name="ldates" id="ldates" value="<?= esc($next_court_work_day) ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center" hidden>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pre_after_notice_sel">Pre/After Notice</label>
                                                    <select class="form-control" name="pre_after_notice_sel" id="pre_after_notice_sel">
                                                        <option value="0">-All-</option>
                                                        <option value="1">Pre Notice</option>
                                                        <option value="2">After Notice</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center" hidden>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="short_non_short_sel">Short/Non Short Category</label>
                                                    <select class="form-control" name="short_non_short_sel" id="short_non_short_sel">
                                                        <option value="0">-As per Misc./NMD Days-</option>
                                                        <option value="1">Short Category</option>
                                                        <option value="2">Non Short Category</option>
                                                        <option value="3">All Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-12 jud_all_al">
                                                <?= $allocation_tp;?>

                                            </div>
                                        </div>
                                        <?php
                                        $db = \Config\Database::connect();
                                        $sql = "SELECT * FROM master.listing_purpose WHERE display = 'Y' AND code != 99 ORDER BY priority";
                                        $query = $db->query($sql);
                                        $data = $query->getResultArray();
                                        ?>
                                        <div class="row justify-content-center" hidden>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="listing_purpose">Purpose of Listing</label>
                                                    <select class="ele" name="listing_purpose" id="listing_purpose" multiple="multiple" size="13" style="width:200px;">
                                                        <option value="all" selected="selected">-ALL-</option>
                                                        <?php foreach ($data as $row) : ?>
                                                            <option value="<?= $row['code']; ?>"><?= $row['code']; ?>. <?= $row['purpose']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        Allotment of Cases
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-row align-items-center">
                                                            <div class="col-auto">
                                                                <label for="noc">Number of Cases per Bench:</label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <input type="text" class="form-control" name="noc" id="noc" value="5" size="5">
                                                            </div>
                                                            <div class="col-auto">
                                                                <input type="button" class="btn btn-primary" name="doa" id="doa" value="Do Allottment">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div id="dv_res2"></div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $(document).ready(function() {
        $('#component_search').on('submit', function() {
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year = $('#diary_year :selected').val();
            var case_type = $('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_year = $('#case_year :selected').val();
            if (search_type == 'D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                } else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            } else if (search_type == 'C') {
                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                } else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                } else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#component_search').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $(".form-response").html("");
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Listing/Caseinfo/case_info_process/'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#report_result').html(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('#div_result').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
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

    $(document).ready(function() {
        function unavailable(date) {
            return [true, ''];
        }
        $('.dtp').datepicker({
            beforeShowDay: unavailable,
            minDate: 0,
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
    $(document).on("change", "#ldates", function() {
        var list_dt = $("#ldates").val();
        var mainhead = "M";
        var bench = "J";
        ddd(mainhead, list_dt, bench);
    });

    function ddd(mainhead, list_dt, bench) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/AdvanceTP/get_allocation_judges_m_advance_prepone/'); ?>",
            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                bench: bench,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                updateCSRFToken();
                //$('.jud_all_al').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'GET',
            success: function(data, status) {
                updateCSRFToken();
                $('.jud_all_al').html(data);
            },
            error: function(xhr) {
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

    $(document).on("click", "#doa", function()
    {
        $("#doa").hide();
        var list_dt = $("#ldates").val();
        var noc = $("#noc").val();
        var partno = $("#partno").val();
        var listing_purpose = $("#listing_purpose").val();
        var pre_after_notice_sel = $("#pre_after_notice_sel").val();
        var short_non_short_sel = $("#short_non_short_sel").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var mainhead = "M";
        var cchk = "";

        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk" && this.checked)
                cchk += $(this).val() + ",";
        });

        if (cchk == "") {

            $('#dv_res2').html('<table width="100%" style="margin: 0 auto;"><tr><td class="class_red" style="text-align: center;">Select at least one bench</td></tr></table>');
            $("#doa").show();
            return false;
        }
        if (noc == "" || noc == 0) {
            alert('else');
            $('#dv_res2').html('<table width="100%" style="margin: 0 auto;"><tr><td class="class_red" style="text-align: center;">Please enter no. of cases to be listed</td></tr></table>');
            $("#doa").show();
            return false;
        }
        cchk = cchk.replace(/,\s*$/, "");
        $.ajax({

            url: "<?php echo base_url('Listing/AdvanceTP/advance_allocation_process_tp/'); ?>",

            cache: false,
            async: true,
            data: {
                list_dt: list_dt,
                listing_purpose: listing_purpose,
                pre_after_notice_sel: pre_after_notice_sel,
                short_non_short_sel: short_non_short_sel,
                noc: noc,
                selected_judges: cchk,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {

                $('#dv_res2').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {
                console.log(data)
                updateCSRFToken();
                $('#dv_res2').html(data);
                var bench = "J";
                ddd(mainhead, list_dt, bench)
                $("#doa").show();
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