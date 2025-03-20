<?= view('header'); ?>
<style>
    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="url"],
    input[type="password"],
    input[type="search"],
    select,
    textarea {
        border: 1px solid #e1e1e1 !important;
        width: 100% !important;
        height: 38px !important;
        padding: 5px 10px !important;
        border-radius: 0 !important;
    }

    .form-control,
    .btn {
        font-size: 14px !important;
    }

    * {
        box-sizing: border-box;
    }

    .my_B {
        font-size: 16px;
        color: Black;
        font-weight: bold;
    }

    .dl-horizontal dd {
        margin-left: 180px;
        line-height: 0.2em;
    }

    dl {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .dl-horizontal dt {
        float: left;
        width: 160px;
        overflow: hidden;
        clear: left;
        text-align: right;
        text-overflow: ellipsis;
        white-space: nowrap;
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
                                <h3 class="card-title">Mention Memo >> Add</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <span class="alert-danger align-center"><?= \Config\Services::validation()->listErrors() ?>
                                    <?php if (session()->getFlashdata('error-msg')) :
                                        echo session()->getFlashdata('error-msg');
                                    endif; ?>
                                </span>
                                <div class="row">
                                    <?php if (session()->getFlashdata('msg')) : ?>
                                        <div class="alert alert-success alert-dismissible align-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong> <?= session()->getFlashdata('msg') ?></strong>
                                        </div>
                                    <?php endif; ?>
<<<<<<< .mine
                                </div>
                                <h3> <i class="fa fa-list-alt" aria-hidden="true"></i> &nbsp; Case Details</h3>
                                <form id="myform" action="<?php echo base_url('Court/CourtMentionMemoController/saveMentionMemo'); ?>" enctype="multipart/form-data" method="POST" class="form-inline">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
||||||| .r114520

                                <p>Hi</p>
                        </div>

                    <div class="row">
               
                        <div class="col-md-12">
                            <div class="card-body">

                            <h3> <i class="fa fa-list-alt" aria-hidden="true"></i>  &nbsp; Case Details</h3>

                            	<form id="myform" action="<?php echo base_url('Court/CourtMentionMemoController/saveMentionMemo');?>" enctype="multipart/form-data" method="POST" class="form-inline" >
                                <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
=======

                                
                        </div>

                    <div class="row">
               
                        <div class="col-md-12">
                            <div class="card-body">

                            <h3> <i class="fa fa-list-alt" aria-hidden="true"></i>  &nbsp; Case Details</h3>

                            	<form id="myform" action="<?php echo base_url('Court/CourtMentionMemoController/saveMentionMemo');?>" enctype="multipart/form-data" method="POST" class="form-inline" >
                                <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'report', 'id' => 'report', 'autocomplete' => 'off');
>>>>>>> .r114596
                                    echo form_open(base_url('Court/CourtMentionMemoController/saveMentionMemo'), $attribute);
                                    ?>
                                    <div class="col-md-6 mt-2">
                                        <dl class="dl-horizontal">
                                            <dt>Case No.</dt>
                                            <dd class="my_B"><?= esc($caseInfodata['reg_no_display']) ?>&nbsp;(D No.<?= esc($caseInfodata['diary_no']) ?>-<?= esc($caseInfodata['diary_year']) ?>)</dd>

                                            <dt>Petitioner</dt>
                                            <dd class="my_B"><?= esc($caseInfodata['pet_name']) ?></dd>

                                            <dt>Respondant</dt>
                                            <dd class="my_B"><?= esc($caseInfodata['res_name']) ?></dd>

                                            <?php if (!empty($caseInfo)): ?>
                                                <?php
                                                foreach ($caseInfo as $row) { ?>
                                                    <dt>Status</dt>
                                                    <dd class="my_B">
                                                        <?php if ($row['mainhead'] == 'M'): ?>
                                                            Misc
                                                        <?php elseif ($row['mainhead'] == 'F'): ?>
                                                            Regular
                                                        <?php endif; ?>
                                                        <?php if ($row['c_status'] == 'P'): ?>
                                                            (Pending)
                                                        <?php elseif ($row['c_status'] == 'D'): ?>
                                                            (Disposed)
                                                        <?php endif; ?>
                                                    </dd>

                                                    <dt>Petitioner Advocate</dt>
                                                    <dd class="my_B"><?= esc($row['pet_adv_name']) ?>-<?= esc($row['pet_aor_code']) ?></dd>

                                                    <dt>Respondant Advocate</dt>
                                                    <dd class="my_B"><?= esc($row['res_adv_name']) ?>-<?= esc($row['res_aor_code']) ?></dd>

                                                <?php } ?>
                                            <?php else: ?>
                                                <!-- <p>No case information available.</p> -->
                                            <?php endif; ?>

                                        </dl>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <dl class="dl-horizontal">
                                            <dt class="my_B"> Dealing Assistant</dt>
                                            <dd class="my_B">-</dd>
                                            <dt class="my_B">Category</dt>
                                            <dt></dt>
                                            <dd class="my_B"></dd>
                                            <dt></dt>
                                            <dd class="my_B"> </dd>
                                            <dt></dt>
                                            <dd class="my_B"></dd>
                                            <dt></dt>
                                            <dd class="my_B"></dd>
                                        </dl>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <h3> <i class="fa fa-list" aria-hidden="true"></i> &nbsp; Listing Details</h3>
                                <?php if (!empty($listingInfo)) {
                                    echo $listingInfo;
                                } ?>
                            </div>
                        </div>
                    </div>

                    <div id="forMentioningList">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <label class="radio-inline control-label"><input type="radio" name="forListType" value="1" checked><b>Oral Mentioning</b></label>
                                                &nbsp;&nbsp;
                                                <label class="radio-inline control-label"><input type="radio" name="forListType" value="2"><b>For Mentioning List</b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="from_date" class="col-sm-4 col-form-label">Date of Mention Memo Received <span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="mmReceivedDate" value="<?php echo date("d-m-Y") ?>" id="mmReceivedDate" placeholder="Date of Mention Memo Received" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="to_date" class="col-sm-4 col-form-label">Date on which Mention Memo<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
<<<<<<< .mine
                                                    <input type="text" class="form-control" name="mmPresentedDate" id="mmPresentedDate" placeholder="dd-mm-yyyy" required autocomplete="off">
||||||| .r114520
                                                <input type="Date" class="form-control" name="mmPresentedDate" id="mmPresentedDate" placeholder="dd-mm-yyyy" required>
=======
                                                <input type="text" class="form-control" name="mmPresentedDate" id="mmPresentedDate" placeholder="dd-mm-yyyy" required>
>>>>>>> .r114596
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="from_date" class="col-sm-4 col-form-label">Date on Which Matter was <br>Directed to be Listed</label>
                                                <div class="col-sm-7">
<<<<<<< .mine
                                                    <input type="text" class="form-control" name="mmDecidedDate" id="mmDecidedDate" placeholder="dd-mm-yyyy" required="required" autocomplete="off">
||||||| .r114520
                                                <input type="date" class="form-control" name="mmDecidedDate" id="mmDecidedDate" placeholder="dd-mm-yyyy" required="required">
=======
                                                <input type="text" class="form-control" name="mmDecidedDate" id="mmDecidedDate" placeholder="dd-mm-yyyy" required="required">
>>>>>>> .r114596
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="order" id="o2" value="R">
                                                    <input type="radio" name="order" id="o3" value="N">&nbsp;&nbsp;As Per Schedule
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group row">
                                                <label for="remark" class="col-sm-2 col-form-label">Remarks</label>
                                                <div class="col-sm-7">
                                                    <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Remarks......."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="forOralMentioning">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="pJudge" class="col-sm-4 col-form-label">Presiding Judge<span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" id="pJudge" name="pJudge" placeholder="pJudge" required>
                                                        <option value="">Select Presiding Judge</option>
                                                        <?php
                                                        foreach ($judge as $j1) {
                                                            echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="causelistType" class="col-sm-4 col-form-label">Causelist Type<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="causelistType" tabindex="1" id="causelistType" onchange="getBenches();" required>
                                                        <option value="">Select Causelist</option>
                                                        <option value="1">Regular List</option>
                                                        <option value="3">Misc. List</option>
                                                        <option value="5">Chamber List</option>
                                                        <option value="7">Registrar List</option>
                                                        <option value="9">Review/Curative List</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="pJudge" class="col-sm-4 col-form-label">Bench<span class="text-red">*</span> : </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="bench" tabindex="1" id="bench" required>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group row">
                                                <label for="causelistType" class="col-sm-4 col-form-label">Item No<span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" id="itemNo" name="itemNo" placeholder="Item Number" type="number" maxlength="20" required="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">Modal title</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="te"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <!--pending remark div ending -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <button id="#saveButton" onclick="return confirm('Do you really want to List The Matter.....?');" type="submit" value="submit" value="Save" style="width:15%;float:right" class="btn btn-block btn-primary">Save</button>
                                <?php form_close(); ?>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(".alert").delay(6000).slideUp(200, function() {
        $(this).alert('close');
    });
    $(document).ready(function() {
        $("#mmPresentedDate, #mmDecidedDate").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });


        $('#diaryNumber').prop("disabled", 'disabled');
        $('#diaryYear').prop("disabled", 'disabled');
        $('#diaryNoWise').hide();


        /*$("input[name$='optradio']").click(function() {
            var searchValue = $(this).val();
            if (searchValue == 1) {
                $('#caseType').removeAttr('disabled');
                $('#caseNo').removeAttr('disabled');
                $('#caseYear').removeAttr('disabled');

                $('#diaryNumber').prop("disabled", 'disabled');
                $('#diaryYear').prop("disabled", 'disabled');

                $('#diaryNoWise').hide();
                $('#caseTypeWise').show();
            } else {
                $('#caseType').prop("disabled", 'disabled');
                $('#caseNo').prop("disabled", 'disabled');
                $('#caseYear').prop("disabled", 'disabled');

                $('#caseTypeWise').hide();

                $('#diaryNumber').removeAttr('disabled');
                $('#diaryYear').removeAttr('disabled');

                $('#diaryNoWise').show();
            }
            // $("div.desc").hide();
            // $("#"+test).show();
        });*/
        $('#forMentioningList').show();
        $('#forOralMentioning').show();
        $("input[name$='forListType']").click(function() {
            var searchValue = $(this).val();
            if (searchValue == 1) {
                $('#forMentioningList').show();
                $('#forOralMentioning').show();
                $('#pJudge').removeAttr('disabled');
                $('#causelistType').removeAttr('disabled');
                $('#bench').removeAttr('disabled');
                $('#itemNo').removeAttr('disabled');
            } else {
                $('#forMentioningList').show();
                $('#forOralMentioning').hide();
                $('#pJudge').prop("disabled", 'disabled');
                $('#causelistType').prop("disabled", 'disabled');
                $('#bench').prop("disabled", 'disabled');
                $('#itemNo').prop("disabled", 'disabled');
            }
        });
        $('#listinngDetails').DataTable({
            "scrollY": "50px",
            "scrollCollapse": true,
            "paging": false
        });

        function confirmBeforeAdd() {
            var choice = confirm('Do you really want to List The Matter.....?');
            if (choice === true) {
                return true;
            }
            return false;
        }

        function myfunc() {
            var start = $("#mmPresentedDate").datepicker("getDate");
            var end = $("#mmDecidedDate").datepicker("getDate");
            days = (end - start) / (1000 * 60 * 60 * 24);
            alert(Math.round(days));
        }
    });

    function make_party_div_popup() {
        document.getElementById("newparty1").style.display = 'block';

    }

    function getBenches() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var causelistDate = $('#mmPresentedDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        if (causelistDate == "") {
            alert("Please fill Causelist Date..");
            $('#mmPresentedDate').focus();
            return false;
        }
        if (pJudge == "") {
            alert("Please Select Presiding Judge..");
            return false;
        }
        if (causelistType == "") {
            alert("Please Select Type of Causelist..");
            return false;
        }
        if (causelistDate != "" && pJudge != "" && causelistType != "") {
            $.get("<?php echo base_url('Court/CourtMasterController/getBench'); ?>", {
                causelistDate: causelistDate,
                pJudge: pJudge,
                causelistType: causelistType,
                CSRF_TOKEN: csrf
            }, function(result) {
                $("#divCasesForGeneration").html("");
                $("#bench").empty();
                $("#bench").append(result);
            });
        }
    }
</script>