<?= view('header') ?>

<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }

    .card-header {
        padding: 5px;
    }

    h4 {
        line-height: 0px;
    }

    .row {
        margin-right: 15px;
        margin-left: 15px;
    }

    a {
        color: darkslategrey
    }

    /* Unvisited link  */

    a:hover {
        color: black
    }

    /* Mouse over link */
    a:active {
        color: #0000FF;
    }

    /* Selected link   */
</style>


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">PIL(E) >> PIL Entry</h3>
                            </div>


                        </div>

                        <?php if (session()->getFlashdata('infomsg')) { ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                            </div>

                        <?php } ?>
                        <?php if (session()->getFlashdata('success_msg')) : ?>
                            <div class="alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                            </div>
                        <?php endif; ?>



                    </div>


                    <span class="alert alert-error" style="display: none;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span class="form-response"> </span>
                    </span>

                    <?= view('PIL/pilEntryHeading'); ?>

                    <div class="row" id="DivIdToPrint">
                        <div class="col-md-12">
                            <form class="form-horizontal" name="frmPilAddEdit" id="frmPilAddEdit" method="post" onsubmit="javascript:return savePilData('<?= base_url() ?>',<?= $dcmis_user_idd ?>)">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?= base_url(); ?>/PIL/PilController/rptPilCompleteData/<?= $pil_id ?>" target="_blank">
                                            <span class="btn btn-warning pull-right"><i class="fa fa-print"> Print</i></span>
                                        </a> 
                                    </div>
                                </div>
                                <br />

                                <input type="hidden" name="usercode" id="usercode" value="<?php echo $dcmis_user_idd; ?>" />
                                <?php

                                //use App\Libraries\Common;

                                //$common = new Common();

                                $diaryNumber = null;
                                $diaryYear = null;
                                $addressedto = "";
                                $receivedfrom = "";
                                $address = "";
                                $emailid = "";
                                $mobileno = "";
                                $stateCode = 0;
                                $receivedOn = null;
                                $petitionDate = null;
                                $pilCategoryCode = 0;
                                $otherGroup = "";
                                $pilGroupCode = 0;
                                $requestSummary = "";
                                $actionTakenCode = "0";
                                $lodgementDate = null;
                                $actionReasonCode = 0;
                                $writtenOn = null;
                                $writtenFor = "";
                                $writtenTo = "THE PETITIONER";
                                $returnDate = null;
                                $returnRemark = "";
                                $sentTo = "";
                                $sentOn = null;
                                $transferredTo = "";
                                $transferredOn = null;
                                $convertedDiaryNumber = null;
                                $convertedDiaryYear = null;
                                $otherRemedyRemark = "";
                                $reportReceived = "";
                                $reportDate = null;
                                $destroyOrKeepIn = "";
                                $destroyOrKeepInDate = null;
                                $destroyOrKeepInRemark = "";
                                $actionTakenText = "";
                                if (!empty($pilCompleteDetail)) {
                                    $pilCompleteDetail = $pilCompleteDetail[0];
                                    $diaryNumber = $pilCompleteDetail['diary_number'];
                                    $diaryYear = $pilCompleteDetail['diary_year'];
                                    $addressedto = $pilCompleteDetail['address_to'];
                                    $receivedfrom = $pilCompleteDetail['received_from'];
                                    $address = $pilCompleteDetail['address'];
                                    $emailid = $pilCompleteDetail['email'];
                                    $mobileno = $pilCompleteDetail['mobile'];
                                    $stateCode = $pilCompleteDetail['ref_state_id'];
                                    $receivedOn = $common->date_formatter($pilCompleteDetail['received_on'], 'd-m-Y');
                                    $petitionDate =  $common->date_formatter($pilCompleteDetail['petition_date'], 'd-m-Y');
                                    $pilCategoryCode = $pilCompleteDetail['ref_pil_category_id'];
                                    $pilGroupCode = $pilCompleteDetail['group_file_number'];
                                    $otherGroup = $pilCompleteDetail['other_text'];
                                    $requestSummary = $pilCompleteDetail['request_summary'];
                                    $actionTakenCode = $pilCompleteDetail['action_taken'];
                                    $lodgementDate = $pilCompleteDetail['lodgment_date'];
                                    $actionReasonCode = $pilCompleteDetail['ref_action_taken_id'];
                                    $writtenOn = $common->date_formatter($pilCompleteDetail['written_on'], 'd-m-Y');
                                    $writtenFor = $pilCompleteDetail['written_for'];
                                    $writtenTo = $pilCompleteDetail['written_to'];
                                    $returnDate = $common->date_formatter($pilCompleteDetail['return_date'], 'd-m-Y');
                                    $returnRemark = $pilCompleteDetail['returned_to_sender_remarks'];
                                    $sentTo = $pilCompleteDetail['sent_to'];
                                    $sentOn = $common->date_formatter($pilCompleteDetail['sent_on'], 'd-m-Y');
                                    $transferredTo = $pilCompleteDetail['transfered_to'];
                                    $transferredOn = $common->date_formatter($pilCompleteDetail['transfered_on'], 'd-m-Y');
                                    $convertedDiaryNumber = $pilCompleteDetail['ec_case_id'];
                                    $convertedDiaryYear = null;
                                    $otherRemedyRemark = $pilCompleteDetail['other_text'];
                                    $otherActionTakenOn = $common->date_formatter($pilCompleteDetail['other_action_taken_on'], 'd-m-Y') ?? '';
                                    $reportReceived = $pilCompleteDetail['report_received'];
                                    $reportDate = $common->date_formatter($pilCompleteDetail['report_received_date'], 'd-m-Y');
                                    if ($pilCompleteDetail['destroy_on'] != null && $pilCompleteDetail['destroy_on'] != "" && $pilCompleteDetail['is_deleted'] == 't') {
                                        $destroyOrKeepIn = 'Y';
                                        $destroyOrKeepInDate = $common->date_formatter($pilCompleteDetail['destroy_on'], 'd-m-Y');
                                    } else if ($pilCompleteDetail['in_record_on'] != null && $pilCompleteDetail['in_record_on'] != "") {
                                        $destroyOrKeepInDate = $common->date_formatter($pilCompleteDetail['in_record_on'], 'd-m-Y');
                                        $destroyOrKeepIn = 'N';
                                    }
                                    $destroyOrKeepInRemark = $pilCompleteDetail['remarks'];
                                    if(!empty($pilCompleteDetail['action_taken']))
                                    {
                                        switch (trim($pilCompleteDetail['action_taken'])) {
                                            case "L": {
                                                    $actionTakenText = "No Action Required";
                                                    break;
                                                }
                                            case "W": {
                                               $written_on = (!empty($pilCompleteDetail['written_on'])) ? date('d-m-Y', strtotime($pilCompleteDetail['written_on'])) : '';
                                                    $actionTakenText = "Written Letter to " . $pilCompleteDetail['written_to'] . " on " . $written_on;
                                                    break;
                                                }
                                            case "R": {
                                                    $return_date = (!empty($pilCompleteDetail['return_date'])) ?  date('d-m-Y', strtotime($pilCompleteDetail['return_date'])) : '';
                                                    $actionTakenText = "Letter Returned to Sender on " . $return_date;
                                                    break;
                                                }
                                            case "S": {
                                                    $sent_on = (!empty($pilCompleteDetail['sent_on'])) ?  date('d-m-Y', strtotime($pilCompleteDetail['sent_on'])) : '';
                                                    $actionTakenText = "Letter Sent To " . $pilCompleteDetail['sent_to'] . " on " . $sent_on;
                                                    break;
                                                }
                                            case "T": {
                                                    $transfered_on = (!empty($pilCompleteDetail['transfered_on'])) ?  date('d-m-Y', strtotime($pilCompleteDetail['transfered_on'])) : '';
                                                    $actionTakenText = "Letter Transferred To " . $pilCompleteDetail['transfered_to'] . " on " . $transfered_on;
                                                    break;
                                                }
                                            case "I": {
                                                    $actionTakenText = "Letter Converted To Writ";
                                                    break;
                                                }
                                            case "O": {
                                                    $actionTakenText = "Other Remedy";
                                                    break;
                                                }
                                            default: {
                                                    $actionTakenText = "UNDER PROCESS";
                                                    break;
                                                }
                                        }
                                    }else{
                                        $actionTakenText = "UNDER PROCESS";
                                    }
                                }
                                ?>
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">PIL Detail</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <input class="form-control" id="pilid" name="pilid" type="hidden" value="<?= $pil_id ?>">
                                    <div class="box-body">
                                        <?php if ($pil_id != 0) { ?>
                                            <div class="form-group">
                                                <input type="hidden" name="diarynumber" id="diarynumber" value="<?= $diaryNumber ?>">
                                                <input type="hidden" name="diaryyear" id="diaryyear" value="<?= $diaryYear ?>">
                                                <label for="addressedto" class="col-sm-3 control-label">Inward Number : <?= $diaryNumber ?>/<?= $diaryYear ?></label>

                                                <label for="addressedto" class="col-sm-6 control-label pull-right">Action Taken : <?= $common->convertToTitleCase($actionTakenText) ?></label>
                                            </div>
                                        <?php } ?>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="addressedto" class="control-label">Addressed To</label>

                                                    <div class="">
                                                        <input class="form-control" id="addressedto" name="addressedto" placeholder="Addressed To" class="text-muted" style="font-weight: bold!important;" type="text" value="<?= $addressedto ?>" onblur="return convertToUpperCase(this);">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="receivedfrom" class="control-label">Applicant Name</label>


                                                    <!--<input class="form-control" id="receivedfrom" name="receivedfrom" placeholder="Received From Name Only" type="text" value="<?/*=$receivedfrom*/ ?>" onblur="return convertToUpperCase(this);">-->
                                                    <div class="">
                                                        <textarea class="form-control" name="receivedfrom" id="receivedfrom" rows="2" style="font-weight: bold!important;" placeholder="Received From Name Only" onblur="return convertToUpperCase(this);"><?= $receivedfrom ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address" class="control-label">Address</label>

                                                    <div class="">
                                                        <textarea class="form-control" style="font-weight: bold!important;" name="address" id="address" rows="2" placeholder="Address ..." onblur="return convertToUpperCase(this);"><?= $address ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="emailid" class="control-label">Email ID</label>

                                                    <div class="">
                                                        <input class="form-control" style="font-weight: bold!important;" id="emailid" name="emailid" placeholder="Email" type="email" value="<?= $emailid ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="state" class="control-label">State</label>

                                                    <div class="">
                                                        <select class="form-control" name="state" id="state">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            //var_dump($state);
                                                            foreach ($state as $st) {
                                                                if ($stateCode == $st['state_code'])
                                                                    echo '<option value="' . $st['state_code'] . '" selected="selected">' . $st['state_name'] . '</option>';
                                                                else
                                                                    echo '<option value="' . $st['state_code'] . '">' . $st['state_name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="petitionDate" class="control-label">Petition Date</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <!-- input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="petitionDate" id="petitionDate" placeholder="Petition Date" value="<?= ($petitionDate != '') ? $petitionDate : '' ?>" -->
                                                            <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="petitionDate" id="petitionDate" placeholder="Petition Date" value="<?= ($petitionDate != '' && $petitionDate != '30-11--0001') ? $petitionDate : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="receivedOn" class="control-label">Received On</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="receivedOn" id="receivedOn" placeholder="Received On" value="<?= ($receivedOn != '') ? $receivedOn : date('d-m-Y') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mobileno" class="control-label">Mobile Number</label>

                                                    <div class="">
                                                        <input class="form-control" style="font-weight: bold!important;" id="mobileno" name="mobileno" placeholder="10 Digit Mobile No" type="tel" maxlength="10" onkeypress="validate(event)" value="<?= $mobileno ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="pilCategory" class="control-label">Nature/Subject Matter</label>

                                                    <div class="">
                                                        <select class="form-control" name="pilCategory" id="pilCategory" onchange="showgroup()">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            foreach ($pilCategory as $pilcat) {
                                                                if ($pilCategoryCode == $pilcat['id']) {
                                                                    echo '<option value="' . $pilcat['id'] . '" selected="selected">' . $pilcat['pil_category'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $pilcat['id'] . '">' . $pilcat['pil_category'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="otherGroup" class="control-label" name="otherTextlabel" id="otherTextlabel" style="">Other Text</label>

                                                    <div class="">
                                                        <textarea class="form-control" name="otherGroup" id="otherGroup" rows="2" placeholder="Other Text ..." style="" onblur="return convertToUpperCase(this);"><?= $otherGroup ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="pilGroup" class="control-label">Select Group</label>

                                                    <div class="">
                                                        <select class="form-control" name="pilGroup" id="pilGroup">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            foreach ($pilGroup as $pilGrp) {
                                                                if ($pilGroupCode == $pilGrp['id']) {
                                                                    echo '<option value="' . $pilGrp['id'] . '" selected="selected">' . $pilGrp['group_file_number'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $pilGrp['id'] . '">' . $pilGrp['group_file_number'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="summaryOfRequest" class="control-label" name="otherTextlabel" id="otherTextlabel">Summary Of Request</label>
                                                    <div class="">
                                                        <textarea class="form-control" name="summaryOfRequest" id="summaryOfRequest" rows="2" placeholder="Summary Of Request ..." onblur="return convertToUpperCase(this);"><?= $requestSummary ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!---------------- Next Section ---------------->
                                <div class="box box-success">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">PIL Action Taken</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <div class="box-body">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="actionTaken" class="control-label">Action Taken</label>
                                                    <?php
                                                    $arrActionTaken = array(
                                                        "0" => "Select",
                                                        "L" => "No Action Required",
                                                        "W" => "Written Letter",
                                                        "R" => "Letter Returned to Sender",
                                                        "S" => "Letter Sent To",
                                                        "T" => "Letter Transferred To",
                                                        "I" => "Letter Converted To Writ",
                                                        "O" => "Other Remedy"
                                                    );
                                                    ?>

                                                    <div class="">
                                                        <select class="form-control" name="actionTaken" id="actionTaken" onchange="showHide1(this)">
                                                            <?php
                                                            foreach ($arrActionTaken as $key => $value) {
                                                                if ($actionTakenCode == $key)
                                                                    echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
                                                                else
                                                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="lodgementDate" class="control-label">Lodgement Date</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="lodgementDate" id="lodgementDate" placeholder="Lodgement Date" value="<?= ($lodgementDate != null) ? date("d-m-Y", strtotime($lodgementDate)) : null ?>">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label for="lodgementDate" class="control-label invisible">--</label>
                                                    <select class="form-control" name="lodgedActionReason" id="lodgedActionReason">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($lodgeActionReason as $lodgeReason) {
                                                            if ($actionReasonCode == $lodgeReason['id'])
                                                                echo '<option value="' . $lodgeReason['id'] . '" selected="selected">' . $lodgeReason['pil_sub_action_code'] . ' - ' . $lodgeReason['sub_action_description'] . '</option>';
                                                            else
                                                                echo '<option value="' . $lodgeReason['id'] . '">' . $lodgeReason['pil_sub_action_code'] . ' - ' . $lodgeReason['sub_action_description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label">Written On</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="writtenOn" id="writtenOn" placeholder="Written On" value="<?= $writtenOn ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label invisible">--</label>
                                                    <select class="form-control" name="writtenActionReason" id="writtenActionReason">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($writtenActionReason as $writtenReason) {
                                                            if ($actionReasonCode == $writtenReason['id'])
                                                                echo '<option value="' . $writtenReason['id'] . '" selected="selected">' . $writtenReason['pil_sub_action_code'] . ' - ' . $writtenReason['sub_action_description'] . '</option>';
                                                            else
                                                                echo '<option value="' . $writtenReason['id'] . '">' . $writtenReason['pil_sub_action_code'] . ' - ' . $writtenReason['sub_action_description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenTo" class="control-label">Written To</label>
                                                    <div class="">
                                                        <input class="form-control" id="writtenTo" style="font-weight: bold!important;" name="writtenTo" placeholder="writtenTo" type="text" value="<?= $writtenTo ?>" onblur="return convertToUpperCase(this);">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenFor" style="font-weight: bold!important;" class=" control-label">Written For</label>
                                                    <div class="">
                                                        <input class="form-control" id="writtenFor" name="writtenFor" placeholder="writtenFor" type="text" value="<?= $writtenFor ?>" onblur="return convertToUpperCase(this);">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="returnDate" class="control-label">Return Date</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="returnDate" id="returnDate" placeholder="Return Date" value="<?= $returnDate ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label invisible">--</label>
                                                    <select class="form-control" name="returnActionReason" id="returnActionReason">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($returnActionReason as $returnReason) {
                                                            if ($actionReasonCode == $returnReason['id'])
                                                                echo '<option value="' . $returnReason['id'] . '" selected="selected">' . $returnReason['pil_sub_action_code'] . ' - ' . $returnReason['sub_action_description'] . '</option>';
                                                            else
                                                                echo '<option value="' . $returnReason['id'] . '">' . $returnReason['pil_sub_action_code'] . ' - ' . $returnReason['sub_action_description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="returnRemark" style="font-weight: bold!important;" class="control-label">Return remarks</label>

                                                    <div class="">
                                                        <textarea class="form-control" name="returnRemark" id="returnRemark" rows="2" placeholder="Return Remark ..." onblur="return convertToUpperCase(this);"><?= $returnRemark ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sentTo" class="control-label">Sent To</label>
                                                    <div class="">
                                                        <input class="form-control" style="font-weight: bold!important;" id="sentTo" name="sentTo" placeholder="sentTo" type="text" value="<?= $sentTo ?>" onblur="return convertToUpperCase(this);">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label invisible">--</label>
                                                    <select class="form-control" name="sentActionReason" id="sentActionReason">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($sentActionReason as $sentReason) {
                                                            if ($actionReasonCode == $sentReason['id'])
                                                                echo '<option value="' . $sentReason['id'] . '" selected="selected">' . $sentReason['pil_sub_action_code'] . ' - ' . $sentReason['sub_action_description'] . '</option>';
                                                            else
                                                                echo '<option value="' . $sentReason['id'] . '">' . $sentReason['pil_sub_action_code'] . ' - ' . $sentReason['sub_action_description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sentOn" class="control-label">Sent On</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="sentOn" id="sentOn" placeholder="Sent On" value="<?= $sentOn ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="transferredTo" class="control-label">Transferred To</label>
                                                    <div class="">
                                                        <input class="form-control" style="font-weight: bold!important;" id="transferredTo" name="transferredTo" placeholder="Transferred To" type="text" value="<?= $transferredTo ?>" onblur="return convertToUpperCase(this);">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label invisible">--</label>
                                                    <select class="form-control" name="transferActionReason" id="transferActionReason">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach ($transferActionReason as $transferReason) {
                                                            if ($actionReasonCode == $transferReason['id'])
                                                                echo '<option value="' . $transferReason['id'] . '" selected="selected">' . $transferReason['pil_sub_action_code'] . ' - ' . $transferReason['sub_action_description'] . '</option>';
                                                            else
                                                                echo '<option value="' . $transferReason['id'] . '">' . $transferReason['pil_sub_action_code'] . ' - ' . $transferReason['sub_action_description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="transferredOn" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="control-label">Transferred On</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           

                                                            <input type="text" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="transferredOn" id="transferredOn" placeholder="Transferred On" value="<?= $transferredOn ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="convertedDiaryNumber" class="control-label">Converted To</label>
                                                    <div class="">
                                                        <input class="form-control" id="convertedDiaryNumber" name="convertedDiaryNumber" placeholder="Converted Diary Number" type="number" maxlength="6" value="<?= $convertedDiaryNumber ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="writtenOn" class="control-label invisible">--</label>
                                                    <select class="form-control" id="convertedDiaryYear" name="convertedDiaryYear">
                                                        <?php
                                                        for ($year = date('Y'); $year >= 1950; $year--)
                                                            if ($convertedDiaryYear == $year)
                                                                echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                                                            else
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="otherRemedyRemark" class="control-label">Remarks</label>
                                                    <div class="">
                                                        <textarea class="form-control" style="font-weight: bold!important;" name="otherRemedyRemark" id="otherRemedyRemark" rows="3" placeholder="Other Remedy Remark ..." onblur="return convertToUpperCase(this);"><?= $otherRemedyRemark ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="otherActionTakenOn" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="control-label">Other Action Taken On</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" class="form-control" name="otherActionTakenOn" id="otherActionTakenOn" placeholder="Other Action date" value="<?= $otherActionTakenOn ?? '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="reportReceived" class="control-label">Report Received</label>
                                                    <div class="row">
                                                        <div class="radio col-sm-2">
                                                            <label>
                                                                <?php
                                                                //echo "reportReceived value ".$reportReceived;
                                                                $checkedY = "";
                                                                if ($reportReceived == 1 && $reportReceived != "") {
                                                                    $checkedY = "checked";
                                                                } ?>
                                                                <input name="reportReceived" id="reportReceivedYes" value="1" type="radio" <?= $checkedY ?>>
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="radio col-sm-2">
                                                            <label>
                                                                <?php
                                                                $checkedN = "";
                                                                if ($reportReceived == 0 && $reportReceived != "") {
                                                                    $checkedN = "checked";
                                                                } ?>
                                                                <input name="reportReceived" id="reportReceivedNo" value="0" type="radio" <?= $checkedN ?>>
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="reportDate" class="control-label">Report Received Date</label>
                                                    <div class="">
                                                        <div class="input-group">
                                                           
                                                            <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="reportDate" id="reportDate" placeholder="Report Date" value="<?= $reportDate ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------------- PIL Deletion ------------>
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">PIL Deletion</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                <label for="reportReceived" class="control-label">Select</label>
                                            <div class=" row">
                                                <div class="radio col-sm-2">
                                                    <label>
                                                        <?php
                                                        $checkedY = "";
                                                        if ($destroyOrKeepIn == 'Y') {
                                                            $checkedY = "checked";
                                                        } ?>
                                                        <input name="destroyOrKeepIn" id="destroy" value="Y" type="radio" <?= $checkedY ?> onclick="todaysDate();">
                                                        Destroy
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <?php
                                                        $checkedN = "";
                                                        if ($destroyOrKeepIn == 'N') {
                                                            $checkedN = "checked";
                                                        } ?>
                                                        <input name="destroyOrKeepIn" id="keepin" value="N" type="radio" <?= $checkedN ?> onclick="todaysDate();">
                                                        Keep In Record
                                                    </label>
                                                </div>
                                            </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="destroyOrKeepInDate" class="control-label">Destroy/Keep in Record Date</label>
                                            <div class="">
                                                <div class="input-group">
                                                     
                                                    <?php if ($checkedY == "" && $checkedN == "") { ?>
                                                        <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="destroyOrKeepInDate" id="destroyOrKeepInDate" placeholder="Date">
                                                    <?php
                                                    } else { ?>
                                                        <input type="text" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="destroyOrKeepInDate" id="destroyOrKeepInDate" placeholder="Date" value="<?= ($destroyOrKeepInDate != null) ? $destroyOrKeepInDate : date('d-m-Y') ?>">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="destroyOrKeepInRemark" class="control-label">Remarks</label>
                                            <div class="">
                                                <textarea class="form-control" style="font-weight: bold!important;" name="destroyOrKeepInRemark" id="destroyOrKeepInRemark" rows="3" placeholder="Destroy Or Keep In Record Remark ..." onblur="return convertToUpperCase(this);"><?= $destroyOrKeepInRemark ?></textarea>
                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="button" id="btnSave" class="btn btn-success col-sm-4" onclick="savePilData('<?= base_url() ?>',<?= $dcmis_user_idd ?>);">SAVE</button>
                                        <!--<button type="submit" id="btnSave" class="btn btn-success col-sm-4">SAVE</button>-->
                                        <button type="button" class="btn btn-danger pull-right col-sm-4" onclick="goBack('<?= base_url() ?>',<?= $dcmis_user_idd ?>);">Cancel</button>
                                    </div>
                                </div>
                                <!----------------- END PIL Deletion ---------->


                                <!-- /.box-body -->

                                <!-- /.box-footer -->


                            </form>
                        </div>
                    </div>

                </div>



            </div>


        </div>



    </div> <!-- card div -->



    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->




    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.section -->

<script src="<?= base_url() ?>/assets/js/pil.js"></script>

<script>
    $(function() {
        $("#receivedOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#orderDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#petitionDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#lodgementDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#writtenOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#returnDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#sentOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#transferredOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#registrationDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#reportDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#destroyOrKeepInDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $("#otherActionTakenOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });
</script>