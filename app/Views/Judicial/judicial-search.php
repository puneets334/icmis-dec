<div class="card">
    <?php  //echo $_SESSION["captcha"];
    $attribute = array('class' => 'form-horizontal', 'name' => 'judicial_search', 'id' => 'judicial_search', 'autocomplete' => 'off');
    echo form_open(base_url($formAction), $attribute);
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger text-white ">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php } else if (session("message_error")) { ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?= session()->getFlashdata("message_error") ?>
                    </div>
                <?php } else { ?>
                    <br />
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group clearfix">
                    <div class="icheck-primary mb-2">
                        <input type="radio" class="search_type" id="search_type_d" name="search_type" value="D" checked>
                        <label for="search_type_d">
                            Diary
                        </label>
                    </div>
                    <div class="icheck-primary mb-2">
                        <input type="radio" class="search_type" id="search_type_c" name="search_type" value="C">
                        <label for="search_type_c">
                            Case Type
                        </label>
                    </div>

                </div>
            </div>
            <div class="col-md-4 diary_section">
                <label for="diary_number" class="col-form-label">Diary No</label>
                <input type="number" class="form-control <?= \Config\Services::validation()->getError('diary_number') ? 'is-invalid' : '' ?>"
                    id="diary_number" name="diary_number"
                    value="<?= old('diary_number') ?>"
                    placeholder="Enter Diary No">
                <?php if (\Config\Services::validation()->hasError('diary_number')) : ?>
                    <div class="invalid-feedback d-block">
                        <?= \Config\Services::validation()->getError('diary_number') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-4 diary_section">
                <label for="inputEmail3" class="col-sm-6 col-form-label">Diary Year</label>
                <?php $year = 1950;
                $current_year = date('Y');
                ?>
                <select name="diary_year" id="diary_year" class="custom-select rounded-0 select-box">
                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                        <option><?php echo $x; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3 casetype_section" style="display: none;">
                <label for="case_type_casecode" class="col-form-label">Case type</label>
                <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0 select-box" style="width: 100%;">
                    <option value="">Select case type</option>
                    <?php
                    foreach ($casetype as $row) {
                        echo '<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>';
                    }
                    ?>
                </select>

                <?php if (\Config\Services::validation()->hasError('case_type_casecode')) : ?>
                    <div class="invalid-feedback d-block">
                        <?= \Config\Services::validation()->getError('case_type_casecode') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-3 casetype_section" style="display: none;">
                <label for="inputEmail3" class="col-sm-5 col-form-label">Case No</label>
                <input type="number" class="form-control <?= \Config\Services::validation()->getError('case_number') ? 'is-invalid' : '' ?>"
                    id="case_number" name="case_number"
                    value="<?= old('case_number') ?>"
                    placeholder="Enter Case No">
                <?php if (\Config\Services::validation()->hasError('case_number')) : ?>
                    <div class="invalid-feedback d-block">
                        <?= \Config\Services::validation()->getError('case_number') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-3 casetype_section" style="display: none;">
                <label for="inputEmail3" class="col-sm-5 col-form-label">Case Year</label>
                <?php $year = 1950;
                $current_year = date('Y');
                ?>
                <select name="case_year" id="case_year" class="custom-select rounded-0 select-box">
                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                        <option><?php echo $x; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" id="judicialSearch">Submit</button>
    </div>
    <?php form_close(); ?>
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
                $('#case_type_casecode').prop('required', true);
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
                $('#case_type_casecode').prop('required', false).removeClass('is-invalid');
            }
            //alert('search_type='+search_type);
        });
        $('.select-box').select2({
            selectOnClose: true
        });

    });
    $(document).ready(function() {
        $(document).on('click', '#judicialSearch', function(e) {
            e.preventDefault();

            let diaryNo, diaryYear, caseType, caseNo, caseYear, searchType;
            const regNum = /^[0-9]+$/;

            // Clear previous error messages
            $(".invalid-feedback").remove();
            $(".is-invalid").removeClass("is-invalid");

            // Validate Case Type Inputs
            if ($("#search_type_c").is(':checked')) {
                caseType = $("#case_type_casecode").val();
                caseNo = $("#case_number").val();
                caseYear = $("#case_year").val();
                searchType = $("#search_type_c").val();
                if (!regNum.test(caseType)) {
                    showError("#case_type_casecode", "Please select a valid case type.");
                    return false;
                }
                if (!regNum.test(caseNo)) {
                    showError("#case_number", "Case No must be numeric.");
                    return false;
                }
                if (!regNum.test(caseYear)) {
                    showError("#case_year", "Case Year must be numeric.");
                    return false;
                }
                if (caseNo == 0) {
                    showError("#case_number", "Case No cannot be zero.");
                    return false;
                }
                if (caseYear == 0) {
                    showError("#case_year", "Case Year cannot be zero.");
                    return false;
                }
            }
            // Validate Diary Inputs
            else if ($("#search_type_d").is(':checked')) {
                diaryNo = $("#diary_number").val();
                diaryYear = $("#diary_year").val(); // Fixed selector here
                searchType = $("#search_type_d").val();

                if (!regNum.test(diaryNo)) {
                    showError("#diary_number", "Diary No must be numeric.");
                    return false;
                }
                if (!regNum.test(diaryYear)) {
                    showError("#diary_year", "Diary Year must be numeric.");
                    return false;
                }
                if (diaryNo == 0) {
                    showError("#diary_number", "Diary No cannot be zero.");
                    return false;
                }
                if (diaryYear == 0) {
                    showError("#diary_year", "Diary Year cannot be zero.");
                    return false;
                }
            }
            // No Option Selected
            else {
                alert("Please select a search option.");
                return false;
            }

            // CSRF Token
            const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
            // AJAX Submission
            $.ajax({
                url: '<?php echo base_url($formAction); ?>',
                method: 'POST',
                data: {
                    search_type:searchType,
                    case_type_casecode:caseType,
                    case_number:caseNo,
                    case_year:caseYear,
                    diary_number:diaryNo,
                    diary_year:diaryYear,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE, // Ensure proper CSRF token submission
                },
                beforeSend: function() {
                    $("#loader").html(
                        "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>"
                    );
                },
                success: function(response) {
                    $("#loader").html('');
                    console.log("AJAX Response:", response);
                    // Handle response appropriately
                },
                error: function(xhr, status, error) {
                    $("#loader").html('');
                    console.error("AJAX Error:", error);
                    alert("Error: " + error);
                },
            });

            /**
             * Function to show error message and add `is-invalid` class.
             * @param {string} inputSelector - The selector for the input field.
             * @param {string} errorMessage - The error message to display.
             */
            function showError(inputSelector, errorMessage) {
                $(inputSelector)
                    .addClass("is-invalid")
                    .after(`<div class="invalid-feedback">${errorMessage}</div>`);
            }
        });
    });

</script>