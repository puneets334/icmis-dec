<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Court Master</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <?= view('Court/CourtMaster/courtMaster_breadcrumb'); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Embed QR Code</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label class="radio-inline"><input type="radio" name="embedType" value="B" checked>Bulk Embed</label>
                                                    <label class="radio-inline"><input type="radio" name="embedType" value="C">Casewise</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                                            <div id="divBulk">
                                                <form id="frmBulkQR" method="POST" name="frmBulkQR" enctype="multipart/form-data" action="<?= site_url('Court/CourtMasterController/generate') ?>" autocomplete="off">
                                                    <?= csrf_field() ?>
                                                    <div class="row">

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="">Causelist Date</label>
                                                            <input type="text" class="form-control" name="causelistDate" id="causelistDate" placeholder="dd/mm/yyyy">
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="">Select Files (PDF Only*)</label>
                                                            <input class="form-control" type="file" name="fileROPList[]" id="fileROPList" accept="application/pdf" multiple />
                                                        </div>

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <button type="button" id="btnEmbedQR" class="quick-btn mt-26">Upload & Embed QR</button>
                                                        </div>
                                                        <div id="divCasesForUploading" class="col-sm-12"></div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="divBYCase" style="display: none">
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
                                            </div>


                                            <!--</form>-->
                                            <?php
                                            if (!empty($msg) && $msg != "") {
                                            ?>
                                                <div class="alert alert-info">
                                                    <?= $msg ?>
                                                </div>
                                            <?php
                                            }
                                            ?>


                                            <!-------------Result Section ------------>
                                            <?php if (isset($caseDetails) && is_array($caseDetails)) {

                                            ?>
                                                <br /><br />
                                                <form id="frmReplaceRop" enctype="multipart/form-data"
                                                    action="<?= base_url() ?>index.php/CourtMasterController/replaceROP" method="post">
                                                    <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />

                                                    <div class="col-sm-12 form-group">
                                                        <label for="diaryNumber" class="col-sm-6 control-label"><span
                                                                class="text-primary">Case No: </span> <?= $caseDetails[0]['reg_no_display'] ?>&nbsp;(D
                                                            No.<?= $caseDetails[0]['diary_no'] ?>)</label>
                                                        <label for="causeTitle" class="col-sm-6 control-label"><span
                                                                class="text-primary">Causetitle : </span> <?= $caseDetails[0]['pet_name'] ?>
                                                            Vs. <?= $caseDetails[0]['res_name'] ?></label>
                                                    </div>
                                                    <div class="col-sm-12 form-group">

                                                        <label for="diaryYear" class="col-sm-2 control-label text-primary">Order Date</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" id="listingDates" name="listingDates" placeholder="listingDates"
                                                                onchange="getListedDetails(this);">
                                                                <option value="0">Select Listing Date</option>
                                                                <?php
                                                                foreach ($caseDetails as $detail) {
                                                                    $causeTitle = $row['pet_name'] . ' Vs. ' . $row['res_name'];
                                                                    $courtNo = "0";
                                                                    if ($detail['courtno'] == 21) {
                                                                        $courtNo = "R1";
                                                                    } elseif ($detail['courtno'] == 22) {
                                                                        $courtNo = "R2";
                                                                    } else {
                                                                        $courtNo = $detail['courtno'];
                                                                    }
                                                                    $value = $detail['diary_no'] . '#' . $detail['next_dt'] . '#' . $detail['roster_id'] . '#' . $detail['courtno'] . '#' . $detail['item_number'];
                                                                    $text = date('d-m-Y', strtotime($detail['next_dt'])) . " in Court " . $courtNo . ' as Item Number ' . $detail['item_number'];
                                                                    echo '<option value="' . $value . '" >' . $text . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <div id="divDetailsForROPUpload">

                                                    </div>
                                                </form>

                                            <?php } else {
                                                // echo "No data found";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });

    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });

    $('#btnEmbedQR').click(function(evt) {
        // Stop the button from submitting the form:
        evt.preventDefault();
        $('#frmBulkQR')[0].submit();

    });
    /*$('#btnEmbedQRCaseWise').click(function (evt) {
        // Stop the button from submitting the form:
        evt.preventDefault();
        alert("Hello");
        return false;
        $('#frmCaseWiseQR')[0].submit();

    });*/

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
                    $("#divSearchResult").html(result);
                    updateCSRFToken();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Request failed: " + textStatus + ", " + errorThrown);
                    alert("An error occurred while processing your request. Please try again.");
                    updateCSRFToken();
                });
        });
    });

    function confirmBeforeAdd() {
        var choice = confirm('Do you really want to List The Matter.....?');
        if (choice === true) {
            return true;
        }
        return false;
    }



    function validateData() {
        /*alert($("#frmCaseWiseQR").serialize());
        return false;*/
        if ($('#fileROPList').val() == "" && 1 == 2) {
            /*alert("Please select gfgfd pdf file to upload!!");
            return false;*/
            $.ajax({
                url: "<?= base_url() ?>index.php/CourtMasterController/embedQRCaseWise",
                type: "POST",
                data: $("#frmCaseWiseQR").serialize(),
                beforeSend: function() {
                    $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(result) {
                    $("#divQREmbedResult").html(result);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Request failed: " + textStatus + ", " + errorThrown);
                    alert("An error occurred while processing your request. Please try again.");
                }
            });
            return; // Prevent form submission if the condition is met
        }
        $("#frmCaseWiseQR").submit();
    }
</script>