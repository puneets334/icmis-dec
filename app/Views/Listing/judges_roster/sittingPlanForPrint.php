<?php

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 9/12/19
 * Time: 6:02 PM
 */ ?>

<style>
    .swal2-overflow {
        overflow-x: visible;
        overflow-y: visible;
    }

    .btn-warning {
        color: #fff;
    }

    .btn-warning:hover {
        color: #fff;
        background-color: #ec971f;
        border-color: #d58512;
    }

    .swal2-popup{
        top: -150px !important;
    }
</style>

<div class="row align-items-center">
    <div class="col-sm-2">
        <label>Causelist Date: <?= $causelistDate ?></label>
    </div>
    <div class="col-sm-10 text-right">
        <?php if ($sittingPlan[0]['if_finalized'] == 1) { ?>
            <div class="form-group ml-2 pull-right">
                <button type="button" id="btnPrintSittingList" class="btn btn-info form-control">
                    <i class="fa fa-print"></i>&nbsp;Print
                </button>
            </div>
            <div class="form-group ml-2 pull-right">
                <button type="button" id="btnGenerateRoster" class="btn btn-success form-control">
                <span class="fas fa-tasks"></span>&nbsp;Generate Roster
                </button>
            </div>
        <?php } else { ?>
            <div class="form-group ml-2 pull-right">
                <button type="button" id="btnFinalizeSittingList" class="btn btn-danger form-control" onclick="finalizeSittingPlan();">
                    <i class="fa fa-check" aria-hidden="true"></i>&nbsp;Finalize
                </button>
            </div>
            <div class="form-group ml-2 pull-right">
                <button type="button" id="btnPrintSittingList" class="btn btn-info form-control">
                    <i class="fa fa-print"></i>&nbsp;Print
                </button>
            </div>
            <div class="form-group ml-2 pull-right">
                <button type="button" id="btnModifySittingList" class="btn btn-success form-control">
                    <i class="fas fa-edit"></i>&nbsp;Modify
                </button>
            </div>
        <?php } ?>
        <div class="form-group ml-2 pull-right">
            <button type="button" id="btnCopySittingPlan" class="btn btn-warning form-control">
                <i class="fas fa-copy"></i>&nbsp;Copy Sitting Plan
            </button>
        </div>
    </div>
</div>


<div id="divToPrint">
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 10mm 10mm;
                font-family: "Liberation Serif";
            }
        }

        ul {
            width: 140px;
            padding-right: 5px;
            padding-bottom: 5px;
            min-height: 80px;
            height: 100%;
            display: inline-block;
            font-size: 13px;
            vertical-align: top;
        }

        li,
        #court_normal_bench_main li {
            background-color: transparent;
            font-size: 12px;
            list-style-type: none;
        }

        span {
            background-color: transparent;
            font-size: 12px;
            list-style-type: none;
        }

        span strong {
            font-size: 15px;
        }

        ul label {
            text-decoration: underline;
            font-size: 12px;
        }
    </style>

    <?php
    $if_sitting_plan_exist = false;
    $valid_non_sitting_judges = array();
    if (count($sittingPlan) > 0) {
        $if_sitting_plan_exist = true;
    }
    $judges_in_plan = array();
    $list_type = "";
    if (sizeof($workingDayData) > 0) {
        if (isset($workingDayData[0]['is_nmd']) && $workingDayData[0]['is_nmd'] == 0) {
            $list_type = "MISCELLANEOUS MATTERS";
        } else {
            $list_type = "REGULAR HEARING MATTERS";
        }
    }

    $working_day = isset($workingDayData[0]['working_date']) ? strtoupper(date('l', strtotime($workingDayData[0]['working_date']))) : '';
    $working_date = isset($workingDayData[0]['working_date']) ? strtoupper(date('jS F, Y', strtotime($workingDayData[0]['working_date']))) : '';
    ?>

    <?php if ($if_sitting_plan_exist) { ?>
        <div class="row"><span class="col-sm-12 text-center text-bold"><strong>SITTING LIST OF <?= $list_type ?> FOR <?= $working_day ?> THE <?= $working_date ?></strong></span></div>
        <br />
        <div class="row">
            <div class="col-sm-12">
                <?php for ($i = 1; $i <= 17; $i++) {
                    $if_court_sitting_plan_exist = false;
                    $benches_in_a_court = array();
                    $header = "";
                    $footer = "";
                    $judges_in_bench = array();
                    $bench_type = 'N';
                    $special_bench = "";
                    if ($i % 4 == 1) {
                        echo "<div class=\"row\">";
                    }
                    foreach ($sittingPlan as $plan) {
                        if ($i == $plan['court_number']) {
                            array_push($benches_in_a_court, $plan);
                        }
                    }
                ?>
                    <div id="div_court_<?= $i ?>" class="form-group col-sm-3" data-court-number="<?= $i ?>">
                        <?php
                        $time = "";
                        foreach ($benches_in_a_court as $bench) {
                            if ($bench['board_type'] == 'J' && $bench['if_special_bench'] == 0) {
                                $bench_type = 'N';
                                $header = $bench['header_remark'];
                                $footer = $bench['footer_remark'];
                                $judges_in_bench = explode(',', $bench['judges']);
                            } else if ($bench['board_type'] == 'J' && $bench['if_special_bench'] == 1) {
                                $bench_type = 'S';
                                $special_bench = "SPECIAL BENCH";
                                $header = $bench['header_remark'];
                                $footer = $bench['footer_remark'];
                                $judges_in_bench = explode(',', $bench['judges']);
                                if ($bench['bench_start_time']) {
                                    $time = ' At ' . date("g:i a", strtotime($bench['bench_start_time']));
                                }
                            } else if ($bench['board_type'] == 'C' && $bench['if_in_printed'] == 1) {
                                $bench_type = 'C';
                                $special_bench = "IN CHANMBER";
                                $header = $bench['header_remark'];
                                $footer = $bench['footer_remark'];
                                $judges_in_bench = explode(',', $bench['judges']);
                                if ($bench['bench_start_time']) {
                                    $time = ' At ' . date("g:i a", strtotime($bench['bench_start_time']));
                                }
                            } else if ($bench['board_type'] == 'C') {
                                $bench_type = 'C';
                                $special_bench = "CHANMBER MATTERS";
                                $header = $bench['header_remark'];
                                $footer = $bench['footer_remark'];
                                $judges_in_bench = explode(',', $bench['judges']);
                                if ($bench['bench_start_time'] != '00:00:00') {
                                    $time = ' At ' . date("g:i A", strtotime($bench['bench_start_time']));
                                }
                            } else if ($bench['board_type'] == 'CC') {
                                $bench_type = 'CC';
                                $special_bench = "CHANMBER CIRCULATION";
                                $header = $bench['header_remark'];
                                $footer = $bench['footer_remark'];
                                $judges_in_bench = explode(',', $bench['judges']);
                            }
                            if ($bench_type == 'N') {

                                $court_name = "COURT NO. " . $i;
                                if ($i == 1) {
                                    $court_name = "CHIEF JUSTICE'S COURT";
                                }
                                if ($bench['if_in_printed'] == 1) {
                                    $court_name = "IN " . $court_name;
                                }
                        ?>
                                <!--Start-->
                                <div>
                                    <ul id="court_normal_bench_<?= $i ?>" class="connectedSortable col-sm-12"
                                        data-bench-type="<?= $bench_type ?>">
                                        <?php if (!$if_court_sitting_plan_exist) {
                                            $if_court_sitting_plan_exist = true;
                                        ?>
                                            <li><label class="text-bold text-md" for="sortable1"><?= $court_name ?>&nbsp;
                                                </label></li>
                                        <?php } ?>
                                        <?php
                                        if ($header != "") {
                                            echo "<span data-header='" . $header . "'>" . $header . "</span>";
                                        }
                                        $judges_count = sizeof($judges_in_bench);
                                        $underline = "";
                                        foreach ($judges_in_bench as $index => $judge_in_bench) {
                                            if ($judges_count == $index + 1) {
                                                $underline = "style=\"text-decoration: underline !important;\"";
                                            }
                                            foreach ($sittingJudges as $judge) {
                                                if ($judge['jtype'] == 'J' && $judge['jcode'] == $judge_in_bench) {
                                                    if ($judge['cji_date'] != '0000-00-00') {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . 'HON. THE CHIEF JUSTICE' . '</li>';
                                                    } else if ($judge['jcode'] == 219) {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . "HON. DR. " . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                                                    } else {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . "HON. " . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                                                    }
                                                    array_push($judges_in_plan, $judge['jcode']);
                                                }
                                            }
                                        }
                                        if ($footer != "") {
                                            echo "<span data-footer='" . $footer . "'>" . $footer . "</span>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <!--END-->
                                <?php } else {

                                if (!$if_court_sitting_plan_exist) {
                                    $if_court_sitting_plan_exist = true;
                                    $court_name = "COURT NO. " . $i;
                                    if ($i == 1) {
                                        $court_name = "CHIEF JUSTICE'S COURT";
                                    }
                                ?>
                                    <div>
                                        <ul id="court_normal_bench_<?= $i ?>" class="connectedSortable col-sm-12"
                                            data-bench-type="N">
                                            <label class="text-bold text-md" for="sortable1"><?= $court_name ?></label>
                                        </ul>
                                    </div>
                                <?php }

                                $idValue = rand(10, 100);
                                $divId = "div_special_" . $bench['court_number'] . "_" . $idValue;
                                $ulId = "ul_special_" . $bench['court_number'] . "_" . $idValue;
                                ?>
                                <div id="<?= $divId ?>">
                                    <ul class="col-sm-12" id="<?= $ulId ?>" data-bench-type="<?= $bench_type ?>">
                                        <li><label class="text-bold text-md"><?= $special_bench . $time ?>
                                                &nbsp;</label></li>
                                        <?php
                                        if ($header != "") {
                                            echo "<label class=\"text-muted text-xs\" data-header='" . $header . "'>" . $header . "</label>";
                                        }
                                        $judges_count = sizeof($judges_in_bench);
                                        $underline = "";
                                        foreach ($judges_in_bench as $index => $judge_in_bench) {
                                            if ($judges_count == $index + 1) {
                                                $underline = "style=\"text-decoration: underline !important;\"";
                                            }
                                            foreach ($sittingJudges as $judge) {
                                                if ($judge['jtype'] == 'J' && $judge['jcode'] == $judge_in_bench) {
                                                    if ($judge['cji_date'] != '0000-00-00') {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . 'HON. THE CHIEF JUSTICE' . '</li>';
                                                    } else if ($judge['jcode'] == 219) {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . "HON. DR. " . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                                                    } else {
                                                        echo '<li ' . $underline . ' id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . "HON. " . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                                                    }
                                                }
                                            }
                                        }
                                        if ($footer != "") {
                                            echo "<label class=\"text-muted text-xs\" data-footer='" . $footer . "'>" . $footer . "</label>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                        <?php }
                        }

                        ?>
                    </div>
                <?php
                    if ($i % 4 == 0 || $i == 17) {
                        echo "</div>";
                    }
                    //For Signing

                } ?>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-8">
                    <label>Non-Sitting Judges:
                        <?php
                        if (sizeof($nonSittingJudges) > 0) {
                            echo "<span>";
                            foreach ($nonSittingJudges as $judge) {
                                if ($judge['jtype'] == 'J') {
                                    if ($judge['cji_date'] != '0000-00-00') {
                                        echo "HON. THE CHIEF JUSTICE, ";
                                    } else if ($judge['jcode'] == 219) {
                                        echo "HON. DR. " . $judge['first_name'] . " " . $judge['sur_name'] . ",";
                                    } else {
                                        echo "HON. " . $judge['first_name'] . " " . $judge['sur_name'] . ", ";
                                    }
                                }
                            }
                            echo "</span>";
                        }
                        ?>
                    </label>
                </div>
                <div class="col-sm-4 pull-right">
                    <span class="pull-right">BY THE DIRECTIONS OF HON'BLE THE CJI</span>
                    <br /><br /><br />
                    <span class="pull-right">REGISTRAR(J-II)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REGISTRAR(J-I)</span>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script src="<?php echo base_url('assets/js/printThis.js'); ?>"></script>


<script type="text/javascript">
    $('#btnPrintSittingList').on('click', function() {
        $("#divToPrint").printThis();
    });
    $('#btnModifySittingList').on('click', async function() {
        causelistDate = $("#causelistDate").val();
        confirmation = true;
        toModify = true;
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.post("<?= base_url('Listing/JudgesRoster/ifSittingPlanExist'); ?>", {
            'causelistDate': causelistDate,
            'confirmation': confirmation,
            'toModify': toModify,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
        }, function(result) {
            $("#judgesLeaveDetail").html(result);
        });
    });

    $('#btnGenerateRoster').on('click', function() {
    const causelistDate = $("#causelistDate").val();
    
    Swal.fire({
        text: 'Do you really want to generate roster of sitting plan, dated ' + causelistDate + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, For Misc.',
        cancelButtonText: 'No',
        showDenyButton: true,
        denyButtonText: 'Yes, For Regular',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Handle "Yes, For Misc."
            const mainhead = 'M';
            const rosterType = "Misc.";
            generateRoster(causelistDate, mainhead, rosterType);
        } else if (result.isDenied) {
            // Handle "Yes, For Regular"
            const mainhead = 'F';
            const rosterType = "Regular";
            generateRoster(causelistDate, mainhead, rosterType);
        }
    });
});

async function generateRoster(causelistDate, mainhead, rosterType) {
    await updateCSRFTokenSync();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.post("<?= base_url('Listing/JudgesRoster/generateRoster'); ?>", {          
        'causelistDate': causelistDate,
        'mainhead': mainhead,
        CSRF_TOKEN:CSRF_TOKEN_VALUE
    }, async function(result) {
        await updateCSRFTokenSync();
        if (result == '1') {
            Swal.fire("Generated", "Roster dated " + causelistDate + " generated successfully!", "success");
        } else if (result == 'exist') {
            Swal.fire("Already Exist", rosterType + " Roster for dated " + causelistDate + " already exists!", "warning");
        } else {
            Swal.fire("Error", "There is some problem while generating roster dated " + causelistDate, "error");
        }
    });
}



    $('#btnCopySittingPlan').on('click', async function() {
        causelistDate = $("#causelistDate").val();
        await updateCSRFTokenSync();
        swal.fire({
            title: "Copy To Date",
            html: '<input type="date" class="datepick" id="copyToDate" name="copyToDate" required>',
            showCancelButton: true,
            confirmButtonText: 'Copy',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const toDate = document.getElementById('copyToDate').value;
                if (!toDate) {
                    swal.fire('Warning!', 'Please Select Date to Copy Sitting Plan', 'warning');
                    return false; // Prevent form submission
                }
                return {
                    toDate: toDate
                }; // Return the date for the POST request
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const toDate = result.value.toDate;
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                //$.post("<?= base_url() ?>index.php/JudgesRosterController/copySittingPlan", {
                $.post("<?= base_url('Listing/JudgesRoster/copySittingPlan'); ?>", {      
                    'causelistDate': causelistDate,
                    'toDate': toDate,
                    CSRF_TOKEN:CSRF_TOKEN_VALUE
                }, function(result) {
                    // updateCSRFTokenSync();
                    if (result == '1') {
                        swal.fire("Copied", "Sitting Plan Copied Successfully!", "success");
                    } else if (result == 'exist') {
                        swal.fire("Already Exist", "Sitting Plan for date " + getFormattedDate(toDate) + " already exists!", "warning");
                    } else {
                        swal.fire("Error", "There is some problem while copying", "error");
                    }
                });
            }
        });

    });

    function getFormattedDate(date) {
        var d = date.split("-");
        return d[2] + "-" + d[1] + "-" + d[0];
    }
</script>