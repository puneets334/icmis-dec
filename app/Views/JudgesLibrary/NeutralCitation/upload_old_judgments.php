<?= view('header'); ?>
<section class="content ">
    <form name="headerForm" method="POST">
        <!---------------- Next Section ---------------->
        <div class="row">
            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
            <?= csrf_field() ?>
            <div class="col-sm-12 form-group">
                <span style="float: left;">
                    <label class="text-primary">Search Option : </label>
                    <label class="radio-inline"><input type="radio" name="optradio" value="C" checked>Case Type</label>
                    <label class="radio-inline"><input type="radio" name="optradio" value="D">Diary No.</label>
                </span>
            </div>


            <div id="caseTypeWise" class="col-sm-12 form-group">
                <label for="lodgementDate" class="col-sm-1 control-label text-primary">Case Type</label>
                <div class="col-sm-2">
                    <select class="form-control" name="caseType" tabindex="1" id="caseType" required>
                        <option value="">Select</option>
                        <?php
                        foreach ($caseTypes as $caseType) {
                            echo '<option value="' . $caseType['casecode'] . '">' . $caseType['casename'] . '&nbsp;:&nbsp;' . $caseType['skey'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <label for="caseNo" class="col-sm-1 control-label text-primary">Case No.</label>
                <div class="col-sm-2">
                    <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                </div>
                <label for="caseYear" class="col-sm-1 control-label text-primary">Year</label>
                <div class="col-sm-2">
                    <select class="form-control" id="caseYear" name="caseYear">
                        <?php
                        for ($year = date('Y'); $year >= 1950; $year--)
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" id="view" name="view" class="btn btn-block btn-primary">View</button>
                </div>
            </div>
            <div id="diaryNoWise" class="col-sm-12 form-group">
                <label for="diaryNumber" class="col-sm-1 control-label text-primary">Diary No</label>
                <div class="col-sm-2">
                    <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                </div>

                <label for="diaryYear" class="col-sm-1 control-label text-primary">Year</label>
                <div class="col-sm-2">
                    <select class="form-control" id="diaryYear" name="diaryYear" required="required">
                        <?php
                        for ($year = date('Y'); $year >= 1950; $year--)
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" id="view" name="view" class="btn btn-block btn-primary">View</button>
                </div>
            </div>

        </div>
    </form>
    <?php
    if ($msg != "") {
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
        <form id="frmReplaceRop" enctype="multipart/form-data" action="<?= base_url() ?>index.php/CourtMasterController/insertNewOrder" method="post">
            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>" />
            <input type="hidden" name="diaryNo" id="diaryNo" value="<?= $caseDetails[0]['diary_no'] ?>" />

            <div class="col-sm-12 form-group">

                <label for="diaryNumber" class="col-sm-6 control-label"><span class="text-primary">Case No: </span> <?= $caseDetails[0]['reg_no_display'] ?>&nbsp;(D No. <?= substr($caseDetails[0]['diary_no'], 0, strlen($caseDetails[0]['diary_no']) - 4), '/', SUBSTR($caseDetails[0]['diary_no'], -4) ?>)</label>
                <label for="causeTitle" class="col-sm-6 control-label"><span class="text-primary">Causetitle : </span> <?= $caseDetails[0]['pet_name'] ?> Vs. <?= $caseDetails[0]['res_name'] ?></label>
            </div>
            <div class="col-sm-12 form-group">

                <label for="diaryYear" class="col-sm-2 control-label text-primary">Order Date</label>
                <div class="col-sm-4">

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="listingDates" id="listingDates"
                            placeholder="dd/mm/yyyy">
                    </div>
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
</section>
<script>
    $(function() {
        $("#listingDates").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        }).on('changeDate', function(ev) {
            getListedDetails(this)
        });;
    });
    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });
    $(document).ready(function() {
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
        diaryNumber = $('#diaryNo').val();
        //alert(usercode);
        if (id.value != 0) {
            $.post("<?= base_url() ?>index.php/CourtMasterController/getCaseListedDetails", {
                id: id.value,
                usercode: usercode,
                diaryNumber: diaryNumber
            }, function(result) {
                $("#divDetailsForROPUpload").html(result);
            });
        }
    }

    function validateData() {
        if ($('#fileROPList').val() == "") {
            alert("Please select pdf file to upload!!");
            return false;
        }
    }
</script>