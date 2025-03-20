<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Old Judgments</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action =  '#';
                        $attribute = "name='headerForm' method='post'";
                        echo form_open();
                        csrf_token();
                        ?>
                        <!---------------- Next Section ---------------->
                        <div class="row">
                            <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                            <div class="col-sm-12 form-group">
                                <span>
                                    <label>Search Option : </label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="C" checked>Case Type</label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="D">Diary No.</label>
                                </span>
                            </div>


                            <div id="caseTypeWise" class="row col-sm-12 form-group">

                                <div class="col-sm-2">
                                    <label for="lodgementDate">Case Type</label>
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
                                    <label for="caseNo">Case No.</label>
                                    <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                                </div>
                                <div class="col-sm-2">
                                    <label for="caseYear">Year</label>
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
                                    <label for="diaryNumber">Diary No</label>
                                    <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                                </div>


                                <div class="col-sm-2">
                                    <label for="diaryYear">Year</label>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
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