<?= view('header'); ?>
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
                                <h3 class="card-title">Judges Library </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Neutral Citation >> Change Court Order Type</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form name="headerForm" id="headerForm" method="POST">
                                                <?= csrf_field() ?>
                                                <!---------------- Next Section ---------------->
                                                <div class="row">
                                                    <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <span style="float: left;">
                                                            <label class="text-primary">Search Option : </label>
                                                            <label class="radio-inline"><input type="radio" name="optradio" value="C">Case Type</label>
                                                            <label class="radio-inline"><input type="radio" name="optradio" value="D" checked>Diary No.</label>
                                                        </span>
                                                    </div>

                                                    <div id="caseTypeWise" class="col-sm-12 form-group">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-3 mb-3">
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

                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="caseNo">Case No.</label>
                                                                <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                                                            </div>

                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="caseYear">Year</label>
                                                                <select class="form-control" id="caseYear" name="caseYear">
                                                                    <?php
                                                                    for ($year = date('Y'); $year >= 1950; $year--)
                                                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <button type="button" id="submitForm1" name="view" class="quick-btn mt-26">View</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="diaryNoWise" class="col-sm-12 form-group">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="diaryNumber" class="">Diary No</label>
                                                                <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                                                            </div>


                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <label for="diaryYear" class="">Year</label>
                                                                <select class="form-control" id="diaryYear" name="diaryYear" required="required">
                                                                    <?php
                                                                    for ($year = date('Y'); $year >= 1950; $year--)
                                                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <button type="button" id="submitForm" name="view" class="quick-btn mt-26">View</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="responseMessage"></div>
                                            <?php if (session()->getFlashdata('flsh_msg')): ?>
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <?= session()->getFlashdata('flsh_msg'); ?>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
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
    $(document).ready(function() {
        // Function to control field visibility and enable/disable inputs
        function toggleFieldsBasedOnRadio() {
            var selectedOption = $("input[name='optradio']:checked").val();
            if (selectedOption === 'C') {
                $('#caseTypeWise').show();
                $('#diaryNoWise').hide();
                $('#caseType, #caseNo, #caseYear').prop("disabled", false);
                $('#diaryNumber, #diaryYear').prop("disabled", true);
            } else {
                $('#caseTypeWise').hide();
                $('#diaryNoWise').show();
                $('#caseType, #caseNo, #caseYear').prop("disabled", true);
                $('#diaryNumber, #diaryYear').prop("disabled", false);
            }
        }

        // Call once on page load to apply default logic
        toggleFieldsBasedOnRadio();

        // Change fields when radio button is clicked
        $("input[name='optradio']").click(function() {
            toggleFieldsBasedOnRadio();
        });

        // Form submit logic
        $("#submitForm,#submitForm1").click(function(e) {
            e.preventDefault();

            var selectedOption = $("input[name='optradio']:checked").val();
            var isValid = true;
            var errorMessage = '';

            if (selectedOption === 'C') {
                var caseType = $('#caseType').val().trim();
                var caseNo = $('#caseNo').val().trim();
                var caseYear = $('#caseYear').val().trim();

                if (caseType === '' || caseNo === '' || caseYear === '') {
                    isValid = false;
                    errorMessage = 'Please fill all the Case-wise fields.';
                } else if (caseNo.length > 9) {
                    isValid = false;
                    errorMessage = 'Case No. should not be more than 9 digits.';
                }

            } else {
                var diaryNumber = $('#diaryNumber').val().trim();
                var diaryYear = $('#diaryYear').val().trim();

                if (diaryNumber === '' || diaryYear === '') {
                    isValid = false;
                    errorMessage = 'Please fill both Diary Number and Year.';
                }
            }

            if (!isValid) {
                alert(errorMessage);
                return;
            }

            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = $('[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                url: "<?= base_url('JudgesLibrary/NeutralCitation/change_court_order_type_new'); ?>",
                type: "POST",
                data: $("#headerForm").serialize(),
                beforeSend: function() {
                    $('#responseMessage').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url('images/load.gif'); ?>'></div>");
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

        // Auto-close alert after 4 seconds
        $(document).on('DOMNodeInserted', ".alert", function() {
            $(".alert").delay(4000).slideUp(500, function() {
                $(this).alert('close');
            });
        });
    });
</script>