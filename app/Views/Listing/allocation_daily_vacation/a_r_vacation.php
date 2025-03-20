<?= view('header') ?>
<style>            
    .class_red{color:red;}
    .class_green{color:green;}
    .align_center{text-align: center;}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <h5 class="font-weight-bold text-center mb-0">VACATION ALLOCATION MODULE (ROSTER BASED)</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group border p-3">
                                        <legend class="font-weight-bold">Mainhead</legend>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mainhead" id="mainheadM"
                                                value="M" title="Miscellaneous">
                                            <label class="form-check-label" for="mainheadM">M</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="mainhead" id="mainheadR"
                                                value="F" title="Regular" checked>
                                            <label class="form-check-label" for="mainheadR">R</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group border p-3">
                                        <legend class="font-weight-bold">Listing Date</legend>
                                        <?php
                                        $cur_ddt = date('Y-m-d', strtotime(' +1 day'));
                                        $next_court_work_day = date("d-m-Y", strtotime(chksDate($cur_ddt)));
                                        ?>
                                        <input type="text" class="form-control dtp" name='ldates' id='ldates'
                                            value="<?php echo $next_court_work_day; ?>" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 jud_all_al">
                                    <div class="form-group border p-3">
                                        <legend class="font-weight-bold text-primary text-center">CORAM</legend>
                                        <?php
                                        $mf = "F";
                                        $jud_count = "2";
                                        $board_type = "J";
                                        get_allocation_judge($mf, $next_court_work_day, $jud_count, $board_type);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group border p-3">
                                        <legend class="font-weight-bold text-center">Purpose of Listing</legend>
                                        <select class="form-control" name="listing_purpose" id="listing_purpose"
                                            multiple>
                                            <option value="all" selected>-ALL-</option>
                                            <?php if (!empty($purposes)) { ?>
                                            <?php foreach ($purposes as $purpose) { ?>
                                            <option value="<?= esc($purpose['code']); ?>">
                                                <?= $purpose["code"] . '. ' . esc($purpose["purpose"]); ?>
                                            </option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group border p-3">
                                        <legend class="font-weight-bold text-primary text-center">Allotment of Cases
                                        </legend>
                                        <div class="form-inline align-items-center mb-2">
                                            <label for="partno" class="mr-2">Part No.:</label>
                                            <input type="text" class="form-control form-control-sm" name="partno"
                                                id="partno" value="1" size="5">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="main_supp"
                                                id="main_supp1" value="1" title="Main Cause List" checked>
                                            <label class="form-check-label" for="main_supp1">Main Cause List</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="main_supp"
                                                id="main_supp2" value="2" title="Supplementary Cause List">
                                            <label class="form-check-label" for="main_supp2">Supplementary Cause
                                                List</label>
                                        </div>
                                        <button type="button" name="get_record" id="get_record"
                                            class="btn btn-primary mt-3">Get Records</button>
                                    </div>
                                </div>
                            </div>
                            <div id="dv_res2" class="p-3"></div>
                            <div id="dv_res3"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
</section>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
$(document).on("changeDate", "#ldates", function() {
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
    updateCSRFToken();
    ddd(mainhead, list_dt, bench);
});



function get_mainhead() {
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
        url: "<?php echo base_url('Listing/Allocation/get_allocation_judges_p/'); ?>",
        cache: false,
        async: true,
        data: {
            list_dt: list_dt,
            mainhead: mainhead,
            bench: bench,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        beforeSend: function() {
            $('.jud_all_al').html(
                '<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>'
            );
        },
        type: 'POST',
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

function all_case_v(e) {

    var elm = e.name;

    if (document.getElementById(elm).checked) {
        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk2")
                this.checked = true;
        });
    } else {
        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk2")
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

$(document).on("click", "#get_record", function() {

    //$("#get_record").hide();
    var list_dt = $("#ldates").val();
    var cchk_sel = "";
    var mainhead = "";
    $('input[type=radio]').each(function() {
        if ($(this).attr("name") == "mainhead" && this.checked)
            mainhead = $(this).val();
    });
    var partno = $("#partno").val();
    var listing_purpose = $("#listing_purpose").val();
    $('input[type=checkbox]').each(function() {
        if ($(this).attr("name") == "chk" && this.checked) {
            cchk_sel += $(this).val();
        }
    });

    $('#dv_res2').html(cchk_sel);
    if (cchk_sel == "") {
        $('#dv_res2').html(
            '<table width="100%" align="center"><tr><td class="class_red align_center">Select atleast one bench</table>'
        );
        $("#get_record").show();
        return false;
    } else if (isEmpty(document.getElementById('listing_purpose'))) {
        $('#dv_res2').html(
            '<table width="100%" align="center"><tr><td class="class_red align_center">Please Select Purpose of Listing</table>'
        );
        $("#get_record").show();
        return false;
    } else if (isEmpty(document.getElementById('partno'))) {
        $('#dv_res2').html(
            '<table width="100%" align="center"><tr><td class="class_red align_center">Please Enter Part No.</table>'
        );
        $("#get_record").show();
        return false;
    } else {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({

            url: "<?php echo base_url('Listing/AllocationDailyVacation/get_vacation/'); ?>",


            data: {
                list_dt: list_dt,
                mainhead: mainhead,
                partno: partno,
                chked_jud_sel: cchk_sel,
                listing_purpose: listing_purpose,
                main_supp: get_main_supp(),
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                //$('#dv_res2').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                $("#dv_res2").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>'); 
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#dv_res2').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });

    }
});

function addRecord() {
    var r = confirm("Do you want to list this case");
    if (r == true) {
        txt = "You pressed OK!";

        var list_dt = $("#ldates").val();
        var cchk_sel = "";
        var mainhead = "";
        $('input[type=radio]').each(function() {
            if ($(this).attr("name") == "mainhead" && this.checked)
                mainhead = $(this).val();
        });
        var partno = $("#partno").val();
        var listing_purpose = $("#listing_purpose").val();
        var chk_dno = '';
        $('input[type=checkbox]').each(function() {
            if ($(this).attr("name") == "chk" && this.checked) {
                //cchk_sel += $(this).val();
                cchk_sel+= $(this).val() + "JG";
            }
            if ($(this).attr("name") == "chk2" && this.checked) {
                chk_dno += $(this).val() + "_";
            }
        });
        var chk_dno_s = chk_dno.replace(/(^,)|(,$)/g, '');

        if (cchk_sel == "") {
            $('#dv_res3').html(
                '<table widht="100%" align="center"><tr><td class="class_red align_center">Select atleast one bench</table>'
            );
            $("#bsubmit").show();
            return false;
        } else if (chk_dno_s == "") {
            $('#dv_res3').html(
                '<table widht="100%" align="center"><tr><td class="class_red align_center">Select atleast one case</table>'
            );
            $("#bsubmit").show();
            return false;
        } else if (isEmpty(document.getElementById('listing_purpose'))) {
            $('#dv_res3').html(
                '<table widht="100%" align="center"><tr><td class="class_red align_center">Please Select Purpose of Listing</table>'
            );
            $("#bsubmit").show();
            return false;
        } else if (isEmpty(document.getElementById('partno'))) {
            $('#dv_res3').html(
                '<table widht="100%" align="center"><tr><td class="class_red align_center">Please Enter Part No.</table>'
            );
            $("#bsubmit").show();
            return false;
        } else {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({

                type: "POST",

                url: "<?php echo base_url('Listing/AllocationDailyVacation/response_get_vacation/'); ?>",
                data: {
                    list_dt: list_dt,
                    mainhead: mainhead,
                    partno: partno,
                    chked_jud_sel: cchk_sel,
                    chk_dno: chk_dno_s,
                    main_supp: get_main_supp(),
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                cache: false,
                success: function(data) {
                    updateCSRFToken();
                    $("#dv_res2").html(data);
                    $("#get_record").show();
                    /*                if (data == 1) {

                                    }
                                    else {
                                        alert("Not Listed.");
                                    }*/
                },
                error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
                
            });
        }
    } else {
        txt = "You pressed Cancel!";
    }

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
    var fr_vl = parseInt($("#fd_" + str_id[1]).val());
    var or_vl = parseInt($("#or_" + str_id[1]).val());
    var tot = parseInt(fr_vl + or_vl);
    $("#tot_" + str_id[1]).val(tot);
}
$(document).on("change", "input[name='main_supp']", function() {
    var main_supp = "";
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $('input[type=radio]').each(function() {
        if ($(this).attr("name") == "main_supp" && this.checked)
            main_supp = $(this).val();
    });
    $.ajax({
        url: "<?php echo base_url('Listing/Allocation/get_listing_purps/'); ?>",

        data: {
            main_supp: main_supp,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
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
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });

});
//function CallPrint(){
$(document).on("click", "#prnnt1", function() {
    var prtContent = $("#prnnt").html();
    var temp_str = prtContent;
    var WinPrint = window.open('', '',
        'left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,cellspacing=1'
    );
    WinPrint.document.write(temp_str);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
});

$(function() {
    //$( "#ldates" ).datepicker();
});
</script>