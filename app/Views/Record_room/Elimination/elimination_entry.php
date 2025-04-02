<?= view('header') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Eliminate >>&nbsp; Eliminate Case</h3>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <form id="eliminationForm" name="eliminationForm" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="icheck-primary">
                                        <input type="radio" name="rdbt_select" id="radiodiary" value="1"
                                            onchange="checkData(this.value);" checked>
                                        <label for="radiodiary">Case Detail</label>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="icheck-primary">
                                        <input type="radio" name="rdbt_select" id="radiocase" value="2"
                                            onchange="checkData(this.value);">
                                        <label for="radiocase">Diary Detail</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 casetype_section">
                                    <div class="form-group row">
                                        <label for="case_type" class="col-sm-5 col-form-label">Case Type</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="casetype" name="casetype"
                                                onchange="getDetail();">
                                                <option value="">Select Case Type</option>
                                                <?php foreach ($caseType as $type): ?>
                                                    <option value="<?= esc($type['casecode']) ?>">
                                                        <?= esc($type['casecode']) ?>
                                                        - <?= esc($type['casename']) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 diary_section">
                                    <div class="form-group row">
                                        <label for="diary_number" class="col-sm-5 col-form-label">Diary No</label>
                                        <div class="col-sm-7">
                                            <input type="text" id="diary_number" name="diary_number" disabled
                                                class="custom-select rounded-0" placeholder="Diary number"
                                                onchange="getDetail()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 casetype_section">
                                    <div class="form-group row">
                                        <label for="caseno" class="col-sm-5 col-form-label">Case No.</label>
                                        <div class="col-sm-7">
                                            <input type="number" class="form-control" id="caseno" name="caseno"
                                                placeholder="Case number" onchange="getDetail();">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 diary_section">
                                    <div class="form-group row">
                                        <label for="diary_year" class="col-sm-5 col-form-label">Diary Year</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="diary_year" disabled name="diary_year"
                                                onchange="getDetail();">
                                                <option value="">Select Year</option>
                                                <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                    <option value="<?= esc($year) ?>"><?= esc($year) ?></option>
                                                <?php endfor; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 casetype_section">
                                    <div class="form-group row">
                                        <label for="" class="col-sm-5 col-form-label">Case Year</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="caseyear" name="caseyear"
                                                onchange="getDetail();">
                                                <option value="">Select Year</option>
                                                <?php for ($year = date('Y'); $year >= 1950; $year--): ?>
                                                    <option value="<?= esc($year) ?>"><?= esc($year) ?></option>
                                                <?php endfor; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-xsclass-12 table-responsive" id="eliminationdata">
                        </div>
                        <div class="panel-footer" id="rslt"></div>

                        <?php if (isset($success_message)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= $success_message ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        function checkData(option) {
            if (option == 1) {
                $('#diary_year').prop('disabled', true).val("");
                $('#diary_number').prop('disabled', true).val("");
                $('#caseno').prop('disabled', false);
                $('#casetype').prop('disabled', false);
                $('#caseyear').prop('disabled', false);
            } else if (option == 2) {
                $('#diary_year').prop('disabled', false);
                $('#diary_number').prop('disabled', false);
                $('#caseno').prop('disabled', true).val("");
                $('#casetype').prop('disabled', true).val("");
                $('#caseyear').prop('disabled', true).val("");
            }
        }


        function getDetail() {
            var option = $('input:radio[name=rdbt_select]:checked').val();
            var caseNumber = $('#caseno').val();
            var caseType = $('#casetype').val();
            var caseYear = $('#caseyear').val();
            var diaryNo = $('#diary_number').val();
            var diaryYear = $('#diary_year').val();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // alert(CSRF_TOKEN_VALUE)
            if (option == 1 && !isEmpty(caseNumber) && !isEmpty(caseType) && !isEmpty(caseYear)) {
                $.ajax({
                    url: "<?php echo base_url('Record_room/Elimination/searchCaseForElimination') ?>",
                    type: 'POST',
                    data: {
                        casetype: caseType,
                        caseno: caseNumber,
                        caseyear: caseYear,
                        option: option,

                    },
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
                    },
                    success: function(result) {
                        console.log(result);
                        $("#eliminationdata").html(result);
                        updateCSRFToken();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            } else if (option == 2 && !isEmpty(diaryNo) && !isEmpty(diaryYear)) {
                $.ajax({
                    url: "<?php echo base_url('Record_room/Elimination/searchCaseForElimination') ?>",
                    type: 'GET',
                    data: {
                        diary_number: diaryNo,
                        diary_year: diaryYear,
                        option: option,
                    },
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
                    },
                    success: function(result) {
                        if (result) {
                            console.log(result);
                            $("#eliminationdata").html(result);
                            updateCSRFToken();
                        } else {
                            $('#rslt').html("<h4>Record Not found</h4>");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            }
        }

        // Function to check if a value is empty
        function isEmpty(value) {
            return value === null || value.trim() === '';
        }



        function isEmpty(value) {
            return !value || value.trim() === "";
        }


        function saveElimination() {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var actionRequired = $("#actionRequired").val();
            var diary_no = $("#diary_no").val();
            var eliminationDate = $("#eliminationDate").val();
            if (actionRequired !== "" && diary_no !== "" && eliminationDate !== "") {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>/Record_room/Elimination/updateElimination",
                    data: $("#eliminationDetail").serialize(),
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN_VALUE
                    },
                    success: function(result) {
                        updateCSRFToken();
                        $("#eliminationdata").html(result);
                        $(".alert").delay(4000).slideUp(200, function() {
                            $(this).alert('close');
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        sweetAlert("Error!!", "An error occurred while processing your request.", "error");
                    }
                });
            } else {
                sweetAlert("Error!!", "Please select proper case", "error");
            }
        }
    </script>
</body>