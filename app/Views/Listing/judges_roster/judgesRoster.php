<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 9/12/19
 * Time: 6:02 PM
 */ ?>

<style>
    ul {
        width: 150px;
        padding-right: 10px;
        padding-bottom: 20px;
        min-height: 150px;
        display: inline-block;
        vertical-align: top;

    }

    .hr-inside {
        margin-top: 10px;
        margin-bottom: 0px;
    }

    li, #court_normal_bench_main li {
        background-color: transparent;
        padding: 2px;
        font-size: 14px;
        list-style-type: none;
    }

    #court_normal_bench_main {
        width: 100%;
        height: 100%;
        min-height: 500px;
        display: inline-block;
        vertical-align: top
    }

    .connectedSortable {
        min-height: 80px !important;
    }

    .input-group {
        display: table;
    }

    .showSweetAlert{
        top: 326px;
    }
    #bench_start_time{
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        width: 98%;
    }
    .btn-set{
        padding: 10px 20px !important;
        margin-right: -10px !important;
    }
</style>
<?php
$if_sitting_plan_exist = false;
$if_non_sitting_exist = false;
$valid_non_sitting_judges = array();
if (count($sittingPlan) > 0) {
    $if_sitting_plan_exist = true;
}
if (count($nonSittingJudges) > 0) {
    $valid_non_sitting_judges = $nonSittingJudges;
    $if_non_sitting_exist = true;
}
//var_dump($valid_non_sitting_judges);exit;
$judges_in_plan = array();
?>
<link rel="stylesheet" href="<?= base_url('assets/css/clockpicker.css') ?>">
<!--<div class="row">-->
    <div class="form-group text-right">
        <button type="button" id="btnSaveSittingList" class="form-control btn btn-success">
        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;SAVE
        </button>
    </div>

    <div id="divSaveResult" class="landscape">

    </div>
<!--</div>-->
<div class="row">
    <input type="hidden" id="causelistDate" value="<?= $causelistDate ?>">
    <div class="col-sm-2">
        <label>Causelist Date: <?= $causelistDate ?></label>
    </div>
    <div class="col-sm-10">
        <label>Non-Sitting Judges:
            <?php
            if ($if_non_sitting_exist) {
                foreach ($valid_non_sitting_judges as $judge) {
                    if ($judge['jtype'] == 'J') {
                        echo $judge['first_name'] . " " . $judge['sur_name'] . ", ";
                    }
                }
            }
            ?>
        </label>
    </div>
</div>
<hr>
<?php //echo '<pre>';print_r($sittingJudges);?>
<?php if ($if_sitting_plan_exist) { ?>
    <div class="row">
        <div class="form-group col-sm-3" style="height: 100%">
            <label for="from[]">Sitting Judges</label>
            <ul id="court_normal_bench_main" data-court-number="" class="connectedSortable" data-bench-type="T">

                <?php
                foreach ($sittingJudges as $index => $judge) {
                    //echo '<pre>';print_r($judge);
                    if ($judge['jtype'] == 'J' && $index >= 17) {
                        echo '<li id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                    }
                }
                ?>
            </ul>
        </div>

        <div class="col-sm-9 ff">
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
                    foreach ($benches_in_a_court as $bench) {
                        $if_in_printed_checked="";
                        if(trim($bench['if_in_printed']) == 1){
                            $if_in_printed_checked="checked";
                        }
                        if (trim($bench['board_type']) == 'J' && $bench['if_special_bench'] == 0) {
                            $header = $bench['header_remark'];
                            $footer = $bench['footer_remark'];
                            $judges_in_bench = explode(',', $bench['judges']);
                        } else if (trim($bench['board_type']) == 'J' && $bench['if_special_bench'] == 1) {
                            $bench_type = 'S';
                            $special_bench = "Special Bench";
                            $header = $bench['header_remark'];
                            $footer = $bench['footer_remark'];
                            $judges_in_bench = explode(',', $bench['judges']);
                        } else if (trim($bench['board_type']) == 'C') {
                            $bench_type = 'C';
                            $special_bench = "Chamber Matters";
                            $header = $bench['header_remark'];
                            $footer = $bench['footer_remark'];
                            $judges_in_bench = explode(',', $bench['judges']);
                        }
                        //echo $bench_type;
                        if ($bench_type == 'N') {
                            $if_court_sitting_plan_exist = true;
                            ?>
                            <!--Start-->
                            <div>
                                <ul id="court_normal_bench_<?= $i ?>" class="connectedSortable col-sm-12"
                                    data-bench-type="<?= $bench_type ?>" data-bench_start_time="10:30">
                                    <label class="text-danger text-md" for="sortable1">Court Noss. <?= $i ?>
                                        &nbsp; <a href="#Foo_<?= $i ?>" class="btn btn-primary btn-xs btn-set" style="padding: 10px 20px !important; margin-right: -10px !important;"
                                                  data-toggle="collapse">
                                                  <i class="fas fa-edit"></i>
                                            <!--<span class="glyphicon glyphicon-edit"></span>-->
                                        </a>
                                        &nbsp;
                                        <button type="button" class="btn btn-success btn-xs btn-set"
                                                data-court-number="<?= $i ?>"
                                                style="padding: 10px 20px !important; margin-right: -10px !important;"
                                                data-toggle="modal" data-target="#myModal">
                                                <i class="fas fa-plus"></i>
                                            <!--<span class="glyphicon glyphicon-plus"></span>-->
                                        </button>
                                    </label>
                                    <div id="Foo_<?= $i ?>" class="collapse">
                                        <label class="text-info">
                                            <input id="if_in_printed_<?= $i ?>" type="checkbox" <?=$if_in_printed_checked?> data-if_in_printed value="<?=$bench['if_in_printed']?>">&nbsp;&nbsp;Print In before</label>
                                        <input type="text" name="header_remark" id="header_remark_<?= $i ?>" data-header
                                               placeholder="Header Remark" value="<?= $header ?>">
                                        <input type="text" name="footer_remark" id="footer_remark_<?= $i ?>" data-footer
                                               placeholder="Footer Remark" value="<?= $footer ?>">
                                    </div>
                                    <?php
                                    foreach ($judges_in_bench as $judge_in_bench) {
                                        foreach ($sittingJudges as $index => $judge) {
                                            if ($judge['jtype'] == 'J' && $judge['jcode'] == $judge_in_bench) {
                                                echo '<li id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                                                array_push($judges_in_plan, $judge['jcode']);
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!--END-->
                        <?php } else {
                            $idValue = rand(10, 100);
                            $divId = "div_special_" . $bench['court_number'] . "_" . $idValue;
                            $ulId = "ul_special_" . $bench['court_number'] . "_" . $idValue;
                            ?>

                            <!--Special Bench Data.-->

                            <div id="<?= $divId ?>">
                                <ul class="col-sm-12" id="<?= $ulId ?>" data-bench-type="<?= $bench_type ?>" data-if_in_printed="<?=$bench['if_in_printed']?>" data-bench_start_time="<?=$bench['bench_start_time']?>">
                                    <li><span class=" text-info text-xs\"><?= $special_bench ?>
                                            &nbsp;<button type="button" onClick="javascript:removeDiv(this)"
                                                          class="btn btn-danger btn-xs pull-right">
                                                          <i class="fa fa-trash" aria-hidden="true"></i>
                                <!--<span class="glyphicon glyphicon-trash"></span>-->
                             </button></span></li>
                                    <?php
                                    if ($header != "") {
                                        echo "<label class=\"text-muted text-xs\" data-header='" . $header . "'>" . $header . "</label>";
                                    }
                                    foreach ($judges_in_bench as $judge_in_bench) {
                                        foreach ($sittingJudges as $index => $judge) {
                                            if ($judge['jtype'] == 'J' && $judge['jcode'] == $judge_in_bench) {
                                                echo '<li id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
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
                    if (!$if_court_sitting_plan_exist) { ?>
                        <div>
                            <ul id="court_normal_bench_<?= $i ?>" class="connectedSortable col-sm-12"
                                data-bench-type="N" data-bench_start_time="10:30">
                                <label class="text-danger text-md" for="sortable1">Court Noaa. <?= $i ?>
                                    &nbsp; <a href="#Foo_<?= $i ?>" class="btn btn-primary btn-xs btn-set" style="padding: 10px 20px !important; margin-right: -10px !important;" data-toggle="collapse">
                                              <i class="fas fa-edit"></i>
                                        <!--<span class="glyphicon glyphicon-edit"></span>-->
                                    </a>
                                    &nbsp;
                                    <button type="button" class="btn btn-success btn-xs btn-set"
                                            data-court-number="<?= $i ?>"
                                            style="padding: 10px 20px !important; margin-right: -10px !important;"
                                            data-toggle="modal" data-target="#myModal">
                                            <i class="fas fa-plus"></i>
                                        <!--<span class="glyphicon glyphicon-plus"></span>-->
                                    </button>
                                </label>
                                <div id="Foo_<?= $i ?>" class="collapse">
                                    <label class="radio-inline">
                                        <input id="if_in_printed_<?= $i ?>" type="checkbox"  data-if_in_printed value="0">&nbsp;&nbsp;Print In before</label>
                                    <input type="text" name="header_remark" id="header_remark_<?= $i ?>" data-header
                                           placeholder="Header Remark" value="">
                                    <input type="text" name="footer_remark" id="footer_remark_<?= $i ?>" data-footer
                                           placeholder="Footer Remark" value="">
                                </div>
                            </ul>
                        </div>
                    <?php }
                    ?>

                </div>

                <?php
                if ($i % 4 == 0 || $i == 17) {
                // if ($i % 4 == 0 || $i != 1) {
                    echo "</div>";
                }
            } ?>

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">                            
                            <h4 class="modal-title">Add Bench in Court No. <label name="courtNumber" id="courtNumber"></label></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <hr>
                            <form id="frmSpecialBench" method="post">
                                <input type="hidden" name="hiddenCourtnumber" id="hiddenCourtnumber">
                                <input type="hidden" name="hiddenCauselistDate" id="hiddenCauselistDate"
                                       value="<?= $causelistDate ?>">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="S" checked>Special Bench
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="C">Chamber Bench
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="CC">Chamber Circulation
                                        </label>
                                        <label class="radio-inline">
                                            <input id="if_in_printed" type="checkbox" value="1">&nbsp;&nbsp;Print In before</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bench_start">Bench Start Time</label>
                                        <div class="input-group clockpicker">
                                            <input type="text" class="form-control" style="border-top-left-radius:15px !important;border-bottom-left-radius:15px !important;" id="bench_start_time" value="" readonly>
                                            <span class="input-group-addon">                                            
                                                <i class="fa fa-clock-o"></i>
                                                <!--<span class="glyphicon glyphicon-time"></span>-->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="available_judges_array[]">Hon'ble Judges</label>
                                        <select name="available_judges_array[]" id="available_judges"
                                                class="form-control" size="8" multiple="multiple">
                                            <?php
                                            foreach ($sittingJudges as $judge) {
                                                if ($judge['jtype'] == 'J') {
                                                    echo '<option style="margin-bottom:10px" value="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <br><br><br>
                                        <button type="button" id="available_judges_rightSelected"
                                                class="btn btn-primary btn-block"><i class="fas fa-chevron-right"></i>
                                                <!--<i class="glyphicon glyphicon-chevron-right"></i></button>-->
                                        <button type="button" id="available_judges_leftSelected"
                                                class="btn btn-primary btn-block"><i class="fas fa-chevron-left"></i>
                                                <!--<i class="glyphicon glyphicon-chevron-left"></i>-->
                                            </button>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label for="bench_composition[]">Bench Composition</label>
                                        <select name="bench_composition[]" id="available_judges_to" class="form-control"
                                                size="8" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="footer_remark">Header Remark</label>
                                        <input type="text" class="form-control" id="header_remark" name="header_remark"
                                               placeholder="Header Remark">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="footer_remark">Footer Remark</label>
                                        <input type="text" class="form-control" id="footer_remark" name="footer_remark"
                                               placeholder="Footer Remark">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="from" class="text-right">&nbsp;</label>
                                        <button type="button" id="btnSaveExtraBench"
                                                class="btn btn-success form-control">SAVE
                                        </button>
                                    </div>
                                    <div id="specialBenchResult">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END -->
        </div>
    </div>
<?php } else { ?>
    <!--Start-->
    <div class="row">
        <div class="form-group col-sm-3" style="height: 100%">
            <label for="from[]">Sitting Judges-</label>
            <ul id="court_normal_bench_main" data-court-number="" class="connectedSortable" data-bench-type="T">
                <?php
                //var_dump($sittingJudges);
                foreach ($sittingJudges as $index => $judge) {
                    if ($judge['jtype'] == 'J' && $index >= 17) {
                        echo '<li id="' . $judge['jcode'] . '"  data-seniority-level="' . $judge['jcode'] . '" data-judge-code="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</li>';
                    }
                }
                ?>
            </ul>
        </div>

       
        <div class="col-sm-9">
            <?php for ($i = 1; $i <= 17; $i++) {
              if ($i % 4 == 1) {
                    echo "<div class=\"row\">";
                } ?>
                <div id="div_court_<?= $i ?>" class="form-group col-sm-3" data-court-number="<?= $i ?>">
                    <div>
                        <ul id="court_normal_bench_<?= $i ?>" class="connectedSortable col-sm-12" data-bench-type="N" data-bench_start_time="10:30">
                            <label class="text-danger text-md" for="sortable1">Court No. <?= $i ?>
                                &nbsp; <a href="#Foo_<?= $i ?>" class="btn btn-primary btn-xs btn-set" data-toggle="collapse" style="padding: 10px 20px !important; margin-right: -10px !important;">
                                <i class="fas fa-edit"></i>
                                    <!--<span class="glyphicon glyphicon-edit"></span>-->
                                    
                                </a>
                                &nbsp;
                                <button type="button" class="btn btn-success btn-xs btn-set" data-court-number="<?= $i ?>"
                                        style="padding: 10px 20px !important; margin-right: -10px !important;"
                                        data-toggle="modal" data-target="#myModal">
                                        <i class="fas fa-plus"></i>
                                    <!--<span class="glyphicon glyphicon-plus"></span>-->
                                </button>
                            </label>
                            <div id="Foo_<?= $i ?>" class="collapse">
                                <label class="text-info">
                                    <input id="if_in_printed_<?= $i ?>" type="checkbox" data-if_in_printed value="0">&nbsp;&nbsp;Print In before</label>
                                <input type="text" name="header_remark" id="header_remark_<?= $i ?>" data-header
                                       placeholder="Header Remark">
                                <input type="text" name="footer_remark" id="footer_remark_<?= $i ?>" data-footer
                                       placeholder="Footer Remark">
                            </div>
                            <li id="<?= (isset($sittingJudges[$i - 1]['jcode']) ? $sittingJudges[$i - 1]['jcode'] :'') ?>"
                                data-seniority-level="<?= (isset($sittingJudges[$i - 1]['jcode']) ?$sittingJudges[$i - 1]['jcode']:'') ?>"
                                data-judge-code="<?= (isset($sittingJudges[$i - 1]['jcode']) ? $sittingJudges[$i - 1]['jcode']:'') ?>"><?php echo (isset($sittingJudges[$i - 1]['first_name']) ? $sittingJudges[$i - 1]['first_name'] :'').  (isset($sittingJudges[$i - 1]['sur_name']) ? " " .$sittingJudges[$i - 1]['sur_name']:'') ?></li>
                        </ul>
                    </div>
                </div>

                <?php
               if ($i % 4 == 0 || $i == 17) {
                    echo "</div>";
                }
            } ?>

            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="myModal" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Bench in Court No. 
                                <label name="courtNumber" id="courtNumber"></label>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <hr>
                            <form id="frmSpecialBench" method="post">
                                <input type="hidden" name="hiddenCourtnumber" id="hiddenCourtnumber">
                                <input type="hidden" name="hiddenCauselistDate" id="hiddenCauselistDate"
                                       value="<?= $causelistDate ?>">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="S" checked>Special Bench
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="C">Chamber Bench
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="benchType" value="CC">Chamber Circulation
                                        </label>

                                        <label class="radio-inline">
                                            <input id="if_in_printed" type="checkbox" value="true">&nbsp;&nbsp;Print In before
                                        </label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bench_start">Bench Start Time</label>
                                        <div class="input-group clockpicker">
                                            <input type="text" class="form-control" style="border-top-left-radius:15px !important;border-bottom-left-radius:15px !important;" id="bench_start_time" value="" readonly>
                                            
                                            <span class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>    
                                                <!--<span class="glyphicon glyphicon-time"></span>-->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="available_judges_array[]">Hon'ble Judges</label>
                                        <select name="available_judges_array[]" id="available_judges"
                                                class="form-control" size="8" multiple="multiple">
                                            <?php
                                            foreach ($sittingJudges as $judge) {
                                                if ($judge['jtype'] == 'J') {
                                                    echo '<option style="margin-bottom:10px" value="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <br><br><br>
                                        <button type="button" id="available_judges_rightSelected"
                                                class="btn btn-primary btn-block"><i class="fas fa-chevron-right"></i>
                                                <!--<i class="glyphicon glyphicon-chevron-right"></i>-->
                                            </button>
                                        <button type="button" id="available_judges_leftSelected"
                                                class="btn btn-primary btn-block"><i class="fas fa-chevron-left"></i>
                                                <!--<i class="glyphicon glyphicon-chevron-left"></i>-->
                                                </button>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="bench_composition[]">Bench Composition</label>
                                        <select name="bench_composition[]" id="available_judges_to" class="form-control"
                                                size="8" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="footer_remark">Header Remark</label>
                                        <input type="text" class="form-control" id="header_remark" name="header_remark"
                                               placeholder="Header Remark">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="footer_remark">Footer Remark</label>
                                        <input type="text" class="form-control" id="footer_remark" name="footer_remark"
                                               placeholder="Footer Remark">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="from" class="text-right">&nbsp;</label>
                                        <button type="button" id="btnSaveExtraBench"
                                                class="btn btn-success form-control">SAVE
                                        </button>
                                    </div>
                                    <div id="specialBenchResult">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END -->
        </div>
    </div>
    <!--END-->
<?php }
?>


<script src="<?= base_url('assets/plugins/jQueryUI/jquery-ui.js') ?>"></script>
<!--<script src="<?/*= base_url() */?>assets/js/multiselect.min.js"></script>-->
<script src="<?= base_url('assets/js/clockpicker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/printThis.js') ?>"></script>
<script type="text/javascript">
    var sittingJudges =<?=json_encode($sittingJudges)?>;
    $('.clockpicker').clockpicker({
        placement: 'bottom',
        autoclose: true
    });

    $(function () {
        $('[id^=court_normal_bench_]').sortable({
            connectWith: ".connectedSortable",
            update: function (event, ui) {
                $('#' + event.target.id + '>li').sort(function (a, b) {
                    var compA = parseInt($(a).data('seniority-level'));
                    var compB = parseInt($(b).data('seniority-level'));
                    return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                }).appendTo($('#' + event.target.id));
            }
        });

        $('#btnSaveSittingList').on('click', async function () {
            var sittingPlan = [];
            $('[data-bench-type]').each(function () {
                var judges = [];
                $(this).find('[data-judge-code]').each(function () {
                    judges.push($(this).data('judge-code'));
                });
                sittingPlan.push({
                    'court_number': $(this).closest('[data-court-number]').data('court-number'),
                    'bench_type': $(this).data('bench-type'),
                    'bench_start_time': $(this).data('bench_start_time'),
                    'if_in_printed': $(this).find('[data-if_in_printed]').data('if_in_printed') ? $(this).find('[data-if_in_printed]').data('if_in_printed') : $(this).find('[data-if_in_printed]').val(),
                    'header': $(this).find('[data-header]').data('header') ? $(this).find('[data-header]').data('header') : $(this).find('[data-header]').val(),
                    'footer': $(this).find('[data-footer]').data('footer') ? $(this).find('[data-footer]').data('footer') : $(this).find('[data-footer]').val(),
                    'judges': judges
                });
            });
            
            var causelistDate = $("#causelistDate").val();
            await updateCSRFTokenSync();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            //updateCSRFToken();
            $.post("<?= base_url('Listing/JudgesRoster/saveSittingList'); ?>", {        
                'causelistDate': causelistDate,
                'sittingPlan': sittingPlan,
                CSRF_TOKEN:CSRF_TOKEN_VALUE
            }, function (result) {
                //updateCSRFToken();
                if(result=="1"){
                    //swal("Sitting Plan Saved", "Dated "+causelistDate, "success");
                    swal.fire({
                        title: "Sitting Plan Saved",
                        text: "Dated " + causelistDate,
                        icon: "success",
                        confirmButtonText: "OK"
                    });

                    // updateCSRFTokenSync();
                    // setTimeout(function(){
                    (async () => {
                        await updateCSRFTokenSync();
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.post("<?= base_url('Listing/JudgesRoster/ifSittingPlanExist'); ?>", {      
                            'causelistDate':causelistDate,'confirmation':true,CSRF_TOKEN:CSRF_TOKEN_VALUE}, function (result) {
                            $("#divResult").hide();
                            $("#divCauselistDate").show();
                            $("#judgesLeaveDetail").show();
                            $("#judgesLeaveDetail").html(result);
                            updateCSRFTokenSync();
                        });
                    })();
                    // }, 1500)    
                }else{
                    //swal("Error", "There is some problem while saving Sitting Plan for Dated "+causelistDate, "error");
                    swal.fire("Error", "There is some problem while saving Sitting Plan for Dated "+causelistDate, "error");
                }
            });
        });

        $('#available_judges').multiselect({keepRenderingSort: true, sort: true});

        $('#btnSaveExtraBench').on('click', function () {
            selectCompostion();
            var composition = $('select#available_judges_to').val();
            var benchType = $("input[name='benchType']:checked").val();
            var headerRemark = $("#header_remark").val();
            var footerRemark = $("#footer_remark").val();
            var courtNumber = $("#hiddenCourtnumber").val();
            var benchStartTime = $("#bench_start_time").val();
            var ifInPrinted=$('#if_in_printed').prop('checked');
            var idValue = (Math.floor(Math.random() * 1000) + 1);
            var divId = "div_special_" + courtNumber + "_" + idValue;
            var ulId = "ul_special_" + courtNumber + "_" + idValue;
            var benchData = "<div id=" + divId + ">";
            benchData += createSpecialBench(composition, benchType, headerRemark, footerRemark, ulId, benchStartTime,ifInPrinted);
            benchData += "</div>";
            $("#div_court_" + courtNumber).append(benchData);
            $('#myModal').modal('hide');
        });

        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                $(this).attr("checked",true);
                $(this).val(1);
            }
            else if($(this).prop("checked") == false){
                $(this).attr("checked",false);
                $(this).val(0);
            }
        });

    });

    function createSpecialBench(composition, benchType, headerRemark, footerRemark, ulId, benchStartTime,ifInPrinted) {
        var benchCompostion = "";
        var specialBench = "";
        if (benchType == 'S') {
            specialBench = "SPECIAL BENCH";
        }
        else if(benchType == 'C' && ifInPrinted==true){
            specialBench = "IN CHAMBER";
        }
        else if (benchType == 'C') {
            specialBench = "CHAMBER MATTERS";
        }
        else if (benchType == 'CC') {
            specialBench = "CHAMBER CIRCULATION";
        }
        if (composition.length > 0) {
            benchCompostion += "<ul class=\"col-sm-12\" id=" + ulId + " data-bench-type=" + benchType + " data-if_in_printed="+ifInPrinted+" data-bench_start_time="+benchStartTime+">";
            benchCompostion += "<li><span class=\" text-info text-xs\">" + specialBench;
            benchCompostion += "&nbsp;<button type=\"button\" onClick=\"javascript:removeDiv(this)\" class=\"btn btn-danger btn-xs pull-right\">" +
                "<i class=\"fa fa-trash\"></i> </button>";
            benchCompostion += "</span></li>";
            if (headerRemark != "") {
                benchCompostion += "<label class=\"text-muted text-xs\" data-header='" + headerRemark + "'>" + headerRemark + "</label>";
            }
            $.each(sittingJudges, function (index, value) {
                //alert( index + ": " + value );
                if (sittingJudges[index]['jtype'] == 'J') {
                    if ($.inArray(sittingJudges[index]['jcode'], composition) !== -1) {
                        benchCompostion += '<li id="' + sittingJudges[index]['jcode'] + '"  data-seniority-level="' + sittingJudges[index]['jcode'] + '" data-judge-code="' + sittingJudges[index]['jcode'] + '">' + sittingJudges[index]['first_name'] + " " + sittingJudges[index]['sur_name'] + '</li>';
                    }
                }
            });
            if (footerRemark != "") {
                benchCompostion += "<label class=\"text-muted text-xs\" data-footer='" + footerRemark + "'>" + footerRemark + "</label>";
            }
            benchCompostion += "</ul>";
        }
        return benchCompostion;
    }

    function selectCompostion() {
        for (var i = 0; i < document.getElementById("available_judges_to").options.length; i++) {
            document.getElementById("available_judges_to").options[i].selected = true;
        }
    }

    $('#myModal').on('show.bs.modal', function (e) {
        var courtNumber = $(e.relatedTarget).data('court-number');
        $(e.currentTarget).find('input[name="hiddenCourtnumber"]').val(courtNumber);
        $(e.currentTarget).find('#courtNumber').html(courtNumber);
    });
    $('#myModal').on('hidden.bs.modal', function () {
        //print(sittingJudges);
        var option = '';
        for (var i = 0; i < sittingJudges.length; i++) {
            if (sittingJudges[i]['jtype'] == 'J') {
                option += '<option style="margin-bottom:10px" value="' + sittingJudges[i]['jcode'] + '">' + sittingJudges[i]['first_name'] + ' ' + sittingJudges[i]['sur_name'] + '</option>';
            }
        }
        $('#available_judges').html(option);
        $('#available_judges_to').empty();
        $(this).find('form')[0].reset();
    });

    function removeDiv(elem) {
        $(elem).closest('div').remove();
    }

    function reloadSittingJudges(judgeList=[]) {
        if (judgeList.length > 0) {
            var option = '';
            for (var i = 0; i < sittingJudges.length; i++) {
                if (sittingJudges[i]['jtype'] == 'J' && judgeList.indexOf(sittingJudges[i]['jcode']) == -1) {
                    //option += '<option style="margin-bottom:10px" value="' + sittingJudges[i]['jcode'] + '">' + sittingJudges[i]['first_name'] +' '+ sittingJudges[i]['sur_name'] +'</option>';
                    option += '<li id="' + sittingJudges[i]['jcode'] + '"  data-seniority-level="' + sittingJudges[i]['jcode'] + '" data-judge-code="' + sittingJudges[i]['jcode'] + '">' + sittingJudges[i]['first_name'] + " " + sittingJudges[i]['sur_name'] + '</li>';
                }
            }
            $('#court_normal_bench_main').html(option);
        }
    }

    var judges_in_plan =<?=json_encode($judges_in_plan)?>;
    console.log(judges_in_plan);
    $(reloadSittingJudges(judges_in_plan));

</script>

