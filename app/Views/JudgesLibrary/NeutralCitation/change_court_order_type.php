<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Change Court Order Type</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action = "";
                        $attribute = "name='headerForm'";
                        echo form_open($action, $attribute);
                        csrf_token();
                        ?>
                        <!---------------- Next Section ---------------->
                        <div class="row">
                            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                            <div class="col-sm-12 form-group">
                                <span style="float: left;">
                                    <label class="text-primary">Search Option : </label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="C">Case Type</label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="D" checked>Diary No.</label>
                                </span>
                            </div>


                            <div id="caseTypeWise" class="row col-sm-12 form-group">
                                <div class="col-sm-2">
                                    <label for="lodgementDate" class="">Case Type</label>
                                    <select class="form-control" name="caseType" tabindex="1" id="caseType" required>
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($caseTypes as $caseType) {
                                            echo '<option value="' . $caseType['casecode'] . '">' . $caseType['casename'] . '&nbsp;:&nbsp;' . $caseType['skey'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <label for="caseNo" class="">Case No.</label>
                                    <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                                </div>

                                <div class="col-sm-2">
                                    <label for="caseYear" class="">Year</label>
                                    <select class="form-control" id="caseYear" name="caseYear">
                                        <?php
                                        for ($year = date('Y'); $year >= 1950; $year--)
                                            echo '<option value="' . $year . '">' . $year . '</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 mt-4">
                                    <button type="submit" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>

                            <div id="diaryNoWise" class="row col-sm-12 form-group">
                                <div class="col-sm-2">
                                    <label for="diaryNumber" class="">Diary No</label>
                                    <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                                </div>
                                <div class="col-sm-2">
                                    <label for="diaryYear" class="">Year</label>
                                    <select class="form-control" id="diaryYear" name="diaryYear" required="required">
                                        <?php
                                        for ($year = date('Y'); $year >= 1950; $year--)
                                            echo '<option value="' . $year . '">' . $year . '</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 mt-4">
                                    <button type="submit" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <?php if (isset($caseDetails ) && count($caseDetails) > 0) {
                        ?>
                            <br /><br />
                            <?php
                            $action = base_url('/CourtMasterController');
                            $attribute = "id='frmReplaceRop' enctype='multipart/form-data'";
                            echo form_open($action, $attribute);
                            csrf_token();
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />

                            <div class="col-sm-12 form-group">

                                <label for="diaryNumber" class="col-sm-6 control-label"><span class="text-primary">Case No: </span> <?= $diaryNumberForSearch->reg_no_display ?>&nbsp;(D No.<?= $diaryNumberForSearch->diary_no ?>)</label>
                                <label for="causeTitle" class="col-sm-6 control-label"><span class="text-primary">Cause Title : </span> <?= $diaryNumberForSearch->pet_name ?> Vs. <?= $diaryNumberForSearch->res_name ?></label>
                            </div>
                            <div class="col-sm-12 form-group">

                                <label for="diaryYear" class="col-sm-2 control-label text-primary">Order Date</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="listingDates" name="listingDates" placeholder="listingDates" onchange="getListedDetails(this);">
                                        <option value="0">Select Listing Date</option>
                                        <?php

                                        foreach ($caseDetails as $detail) {
                                            //$value=$detail['diary_no'].'##'.$detail['order_date'].'##'.$detail['order_type'].'##'.$detail['id'].'##'.$detail['file_address'].'##'.$detail['order_type_short'].'##'.$detail['tbl_name'].'##'.$detail['d_no'].'##'.$detail['d_year'];
                                            $value = $detail['id'] . '##' . $detail['file_address'] . '##' . $detail['order_type_short'] . '##' . $detail['tbl_name'];
                                            $text = date('d-m-Y', strtotime($detail['order_date']));
                                            if ($detail['order_type_short'] == 'O') {
                                                $order_type = ' [ROP]';
                                            } else if ($detail['order_type_short'] == 'J') {
                                                $order_type = ' [Judgment]';
                                            }
                                            if ($detail['order_type_short'] == 'FO') {
                                                $order_type = ' [Final Order]';
                                            }
                                            echo '<option value="' . $value . '" >' . $text . ' ' . $order_type . ' ' . $detail['nc_display'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div id="divDetailsForROPUpload">

                            </div>
                            <?php echo form_close(); ?>

                        <?php } else {
                            // echo "No data found";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // $(".alert").delay(4000).slideUp(500, function() {
    //     $(this).alert('close');
    // });
    $(document).ready(function() {
        /*      $('#diaryNumber').prop("disabled",'disabled');
                $('#diaryYear').prop("disabled",'disabled');
                $('#diaryNoWise').hide();*/
        $('#caseType').prop("disabled", 'disabled');
        $('#caseNo').prop("disabled", 'disabled');
        $('#caseYear').prop("disabled", 'disabled');

        $('#caseTypeWise').hide();


        $("input[name$='optradio']").click(function() {
            var searchValue = $(this).val();
            if (searchValue == 'C') {
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
        });
    });

    function confirmBeforeAdd() {
        var choice = confirm('Do you really want to List The Matter.....?');
        if (choice === true) {
            return true;
        }
        return false;
    }

    function getListedDetails(id) {
        usercode = $('#usercode').val();
        alert(usercode);
        if (id.value != 0) {
            $.post("<?= base_url() ?>/CourtMasterController/getListedDetailsForJudgmentFlag", {
                id: id.value,
                usercode: usercode
            }, function(result) {
                $("#divDetailsForROPUpload").html(result);
            });
        }
    }
</script>