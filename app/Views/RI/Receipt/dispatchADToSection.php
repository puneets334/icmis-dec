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
                                <h3 class="card-title">Receipt</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Dispatch AD to Section</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'dispatchADToSection', 'id' => 'dispatchADToSection', 'autocomplete' => 'off');
                                            echo form_open(base_url('RI/ReceiptController/dateWiseReceived'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>

                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h4 class="box-title">Search By : </h4><br>
                                                    <div class="form-group ">

                                                        <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>

                                                    </div>
                                                </div>


                                            </div><br>


                                            <div class="rowww">
                                                <!--start 1 section-->
                                                <div id="divSection" style="display: block">
                                                    <div class="row">

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="from">From Date: </label>
                                                            <div class="input-group date" id="fromDate" data-target-input="nearest">
                                                                <input type="text" size="7" class="dtp form-control" id="fromDate" name="fromDate" value="<?php echo date('d-m-Y'); ?>" readonly data-target="#fromDate">
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="to_date">To Date:</label>
                                                            <div class="input-group date" id="toDate" data-target-input="nearest">
                                                                <input type="text" size="7" class="dtp form-control" id="toDate" name="toDate" value="<?php echo date('d-m-Y'); ?>" readonly data-target="#toDate">
                                                            </div>

                                                        </div>


                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="section">Section:</label>

                                                            <select class="form-control" name="dealingSection" id="dealingSection">
                                                                <option value="0">All</option>
                                                                <?php
                                                                if (!empty($dealingSections)) {
                                                                    foreach ($dealingSections as $dealingSection) {
                                                                        echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end 1 section-->


                                            <!--start 2 section-->
                                            <div id="divCaseTypeWise" style="display: none;">
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="from">Case Type: </label>
                                                        <select class="form-control" name="caseType" id="caseType">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            if (!empty($caseTypes)) {
                                                                foreach ($caseTypes as $caseType) {
                                                                    echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="caseNo">Case Number:</label>
                                                        <input type="number" id="caseNo" name="caseNo" class="form-control" placeholder="Case Number" value="">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="caseYear">Case Year:</label>
                                                        <select id="caseYear" name="caseYear" class="form-control">
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end 2 section-->



                                            <!--start 3 section-->
                                            <div id="divDiaryNoWise" style="display: none;">
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="diaryNumber">Diary Number: </label>
                                                        <input type="number" id="diaryNumber" name="diaryNumber" class="form-control" placeholder="Diary Number" value="">
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="diaryYear">Diary Year:</label>
                                                        <select id="diaryYear" name="diaryYear" class="form-control">
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                            </div>



                                            <!--end 3 section-->



                                            <!--start 4 section-->
                                            <div id="divProcessIdWise" style="display: none;">
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processId">Process Id: </label>
                                                        <input type="number" id="processId" name="processId" class="form-control" placeholder="Process Id" value="">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="processYear">Process Year:</label>
                                                        <select id="processYear" name="processYear" class="form-control">
                                                            <?php
                                                            for ($i = date("Y"); $i > 1949; $i--) {
                                                                echo "<option value=" . $i . ">$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--end 4 section-->
                                            <br>
                                            <div class="row">

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="status" class="">Serve Stage:</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="9999">All</option>
                                                        <option value="4">Serve</option>
                                                        <option value="5">Un-Serve</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div style="display:flex;justify-content:center">
                                                <button type="button" id="btnGetCases" class="quick-btn mt-26" onclick="checkFunction();">View</button>
                                            </div>
                                        </div>


                                        <?php form_close(); ?>
                                    </div>
                                    <div id="dataForDispatchAD"></div>

                                    <br><br>

                                </div>
                            </div> <!-- card div -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- /.section -->
<script>
    $(document).ready(function() {
        $('#fromDate,#toDate').datetimepicker({
            format: 'DD-MM-YYYY',
        });
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function checkFunction() {
        var searchBy = $("input[name='searchBy']:checked").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (searchBy == "s") {

            var fromDate = $('#fromDate').find('input').val();
            var toDate = $('#toDate').find('input').val();
            if (fromDate == "") {
                alert("Select Received From Date.");
                $("#fromDate").focus();
                return false;
            }
            if (toDate == "") {
                alert("Select Received To Date.");
                $("#toDate").focus();
                return false;
            }
        } else if (searchBy == "c") {
            var caseType = $("#caseType").val();
            var caseNo = $("#caseNo").val();
            if (caseType == 0) {
                alert("Select Case Type.");
                $("#caseType").focus();
                return false;
            }
            if (caseNo == "") {
                alert("Enter Case Number.");
                $("#caseNo").focus();
                return false;
            }
        } else if (searchBy == "d") {
            var diaryNumber = $("#diaryNumber").val();
            if (diaryNumber == "") {
                alert("Enter Diary Number.");
                $("#diaryNumber").focus();
                return false;
            }
        } else if (searchBy == "p") {
            var processId = $("#processId").val();
            if (processId == "") {
                alert("Enter Process Id.");
                $("#processId").focus();
                return false;
            }
        }

        $.ajax({
            type: 'POST',

            url: '<?= base_url('/RI/ReceiptController/getDataForADToDispatch'); ?>',
            data: $("#dispatchADToSection").serialize(),
            success: function(result) {
                updateCSRFToken();
                $("#dataForDispatchAD").html(result);
            }
        });
    }

    $("input[name$='searchBy']").click(function() {
        var searchValue = $(this).val();
        if (searchValue == 's') {
            $('#divSection').show();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').hide();

        } else if (searchValue == 'c') {
            $('#divSection').hide();
            $('#divCaseTypeWise').show();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').hide();
        } else if (searchValue == 'd') {
            $('#divSection').hide();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').show();
            $('#divProcessIdWise').hide();
        } else if (searchValue == 'p') {
            $('#divSection').hide();
            $('#divCaseTypeWise').hide();
            $('#divDiaryNoWise').hide();
            $('#divProcessIdWise').show();
        }
        //alert(test);
        // $("div.desc").hide();
        // $("#"+test).show();
    });

    $('.number').keypress(function(event) {

        if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
            return true;

        else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();

    });
</script>