<?= view('header'); ?>
<section class="content ">
    <form name="headerForm" id="headerForm" method="POST">
        <?= csrf_field() ?>
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
                    <button type="button" id="submitForm1" name="view" class="btn btn-block btn-primary">View</button>
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
                    <button type="button" id="submitForm" name="view" class="btn btn-block btn-primary">View</button>
                </div>
            </div>

        </div>
    </form>
    <div id="responseMessage"></div>
</section>
<script>
    $(document).ready(function() {
        $("#submitForm,#submitForm1").click(function(e) {
            e.preventDefault(); 
            var csrfName = '<?= csrf_token() ?>'; 
            var csrfHash = $('[name="<?= csrf_token() ?>"]').val();
            $.ajax({
                url: "<?php echo base_url('JudgesLibrary/NeutralCitation/change_court_order_type_new'); ?>",
                type: "POST",
                data: $("#headerForm").serialize(),
                beforeSend: function() {
                    $('#dv_res').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(response) {
                    updateCSRFToken();
                    $("#responseMessage").html(response);
                },
                error: function() {
                    updateCSRFToken();
                    $("#responseMessage").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    });


    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });
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
        //alert(usercode);
        if (id.value != 0) {
            $.post("<?= base_url() ?>index.php/CourtMasterController/getListedDetailsForJudgmentFlag", {
                id: id.value,
                usercode: usercode
            }, function(result) {
                $("#divDetailsForROPUpload").html(result);
            });
        }
    }
</script>