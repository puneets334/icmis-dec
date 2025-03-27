<?= view('header') ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Court Master</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= view('Court/CourtMaster/courtMaster_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <h3 class="basic_heading"> Upload One By One </h3><br>
                                            <form id="frmCaseWiseQR" name="frmCaseWiseQR" enctype="multipart/form-data"
                                                method="post" target="_blank" action="<?= site_url() ?>Court/CourtMasterController/embedQRCaseWise">
                                                <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <span style="float: left;">
                                                            <label class="text-primary">Search Option : </label>
                                                            <label class="radio-inline"><input type="radio" name="optradio" value="C"
                                                                    checked>Case Type</label>
                                                            <label class="radio-inline"><input type="radio" name="optradio" value="D">Diary No.</label>
                                                        </span>
                                                    </div>
                                                </div>


                                                <div id="caseTypeWise" class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="causelistDate">Case Type</label>
                                                        <select class="form-control" name="caseType" tabindex="1" id="caseType" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($caseTypes as $caseType) {
                                                                echo '<option value="' . $caseType['casecode'] . '">' . $caseType['casename'] . '&nbsp;:&nbsp;' . $caseType['skey'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="caseNo">Case Number</label>
                                                        <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number"
                                                            maxlength="10" required="required">
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="caseNo">Year</label>
                                                        <select class="form-control" id="caseYear" name="caseYear">
                                                            <?php
                                                            for ($year = date('Y'); $year >= 1950; $year--)
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="caseNo">&nbsp;</label>
                                                        <input type="button" id="btnGetCaseDetails" name="btnGetCaseDetails" class="btn btn-block btn-primary" value="View"></input>
                                                    </div>
                                                </div>


                                                <div id="diaryNoWise" class="row">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="diaryNumber">Diary Number</label>
                                                        <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number"
                                                            type="number" maxlength="20" required="required">
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="diaryYear">Year</label>
                                                        <select class="form-control" id="diaryYear" name="diaryYear" required="required">
                                                            <?php
                                                            for ($year = date('Y'); $year >= 1950; $year--)
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="view">&nbsp;</label>
                                                        <input type="button" id="btnGetDiaryDetails" name="btnGetCaseDetails" class="quick-btn mt-26" value="View"></input>
                                                    </div>
                                                </div>
                                                <div id="divSearchResult"></div>
                                            </form>

                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'courtMaster', 'id' => 'courtMaster', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                            echo form_open('Court/CourtMasterController/replaceROP', $attribute); ?>

                                            <?php if (!empty($caseDetails)) { ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Case No : </span> <?= $caseDetails[0]['reg_no_display'] ?>&nbsp;(D No.<?= $caseDetails[0]['diary_no'] ?>)</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label" style="font-weight:bold;"><span class="text-primary">Causetitle : </span> <?= $caseDetails[0]['pet_name'] ?> Vs. <?= $caseDetails[0]['res_name'] ?></label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label" style="font-weight:bold;"><span class="text-primary">Order Date : </span></label>
                                                            <div class="col-sm-4">
                                                                <select class="form-control" id="listingDates" name="listingDates" placeholder="listingDates" onchange="getListedDetails(this);">
                                                                    <option value="0">Select Listing Date</option>
                                                                    <?php
                                                                    foreach ($caseDetails as $detail):

                                                                        $courtNo = "0";
                                                                        if ($detail['courtno'] == 21) {
                                                                            $courtNo = "R1";
                                                                        } elseif ($detail['courtno'] == 22) {
                                                                            $courtNo = "R2";
                                                                        } else if ($detail['courtno'] == 31) {
                                                                            $courtNo = "VC- 1";
                                                                        } else if ($detail['courtno'] == 32) {
                                                                            $courtNo = "VC- 2";
                                                                        } else if ($detail['courtno'] == 33) {
                                                                            $courtNo = "VC- 3";
                                                                        } else if ($detail['courtno'] == 34) {
                                                                            $courtNo = "VC 4";
                                                                        } else if ($detail['courtno'] == 35) {
                                                                            $courtNo = "VC- 5";
                                                                        } else {
                                                                            $courtNo = $detail['courtno'];
                                                                        }
                                                                        $value = $detail['diary_no'] . '#' . $detail['next_dt'] . '#' . $detail['roster_id'] . '#' . $detail['courtno'] . '#' . $detail['item_number'];
                                                                        $text = date('d-m-Y', strtotime($detail['next_dt'])) . " in Court " . $courtNo . ' as Item Number ' . $detail['item_number'];
                                                                        echo '<option value="' . $value . '" >' . $text . '</option>';

                                                                    endforeach;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <hr><br>
                                        <div id="showData">

                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#causelistDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('#diaryNumber').prop("disabled", 'disabled');
        $('#diaryYear').prop("disabled", 'disabled');
        $('#diaryNoWise').hide();

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

        $("input[name$='embedType']").click(function() {
            var searchValue = $(this).val();
            if (searchValue == 'C') {
                $("#divBYCase").css("display", "block");
                $("#divBulk").css("display", "none");
            } else {
                $("#divBYCase").css("display", "none");
                $("#divBulk").css("display", "block");
            }
        });

        $("input[name$='btnGetCaseDetails']").click(function() {
            var btn = $(this); // store reference to the button
            btn.prop("disabled", true); // disable the button

            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var formData = $("#frmCaseWiseQR").serializeArray();
            formData.push({
                name: 'CSRF_TOKEN',
                value: CSRF_TOKEN_VALUE
            });

            $.ajax({
                    url: "<?= base_url('Court/CourtMasterController/getCaseListingDetails') ?>",
                    type: "POST",
                    data: formData,
                    beforeSend: function() {
                        $('#divSearchResult').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                    }
                })
                .done(function(result) {
                    updateCSRFToken();
                    $("#divSearchResult").html(result);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    updateCSRFToken();
                    console.error("Request failed: " + textStatus + ", " + errorThrown);
                    alert("An error occurred while processing your request. Please try again.");
                })
                .always(function() {
                    btn.prop("disabled", false); // re-enable the button regardless of success or failure
                });
        });
    });

    function getListedDetails(id) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        var usercode = $('#usercode').val();

        if (id.value != 0) {

            $.post("<?= base_url('Court/CourtMasterController/getListedDetails') ?>", {
                CSRF_TOKEN: csrf,
                id: id.value,
                usercode: usercode
            }, function(result) {
                $("#showData").html(result);
                updateCSRFToken();
            });
            updateCSRFToken();
        }
    }

    function validateData() {
        if ($('#fileROPList').val() == "") {
            alert("Please select pdf file to upload!!");
            return false;
        }
    }
</script>