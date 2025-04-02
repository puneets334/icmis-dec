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
                                    <h4 class="basic_heading">Dispatch to Officer/Section</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">

                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'dispatchDak', 'id' => 'dispatchDak', 'autocomplete' => 'off');
                                            echo form_open(base_url('#'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <h4 class="box-title">Search By Name Of : </h4><br>
                                                    <div class="form-group ">

                                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByAll" value="a" checked onclick="showHideDiv(this.value)">All</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByJudge" value="j" onclick="showHideDiv(this.value)">Hon'ble Judge</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByOfficer" value="o" onclick="showHideDiv(this.value)">Officer</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchBySection" value="s" onclick="showHideDiv(this.value)">Section</label>

                                                    </div>
                                                </div>


                                            </div><br>


                                            <div class="rowww">
                                                <!--start 1 section-->
                                                <div>
                                                    <div class="row">

                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="fromDate">From Date: </label>
                                                            <input type="text" id="fromDate" name="fromDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($fromDate) ? $fromDate : null; ?>" maxlength="10" size="10" readonly>
                                                        </div>


                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="toDate">To Date:</label>
                                                            <input type="text" id="toDate" name="toDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($toDate) ? $toDate : null; ?>" maxlength="10" size="10" readonly>
                                                        </div>


                                                        <div class="col-sm-12 col-md-3 mb-3">
                                                            <label for="from">Parcel Receipt Mode:</label>
                                                            <?php
                                                            $options = array("All", "Ordinary", "Other Receipt Mode.");
                                                            ?>
                                                            <select class="form-control" name="parcelReceiptMode" id="parcelReceiptMode">
                                                                <?php
                                                                foreach ($options as $index => $option) {
                                                                    if (!empty($reportType)) {
                                                                        if ($reportType == $index)
                                                                            echo "<option value='" . $index . "' selected>" . $option . "</option>";
                                                                    } else
                                                                        echo "<option value='" . $index . "'>" . $option . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="row">
                                                        <div class="col-sm-6" id="divJudge" style="display: none">
                                                            <div class="form-group row ">
                                                                <label for="judge" class="col-sm-4 col-form-label">By Name(Hon'ble Judge): </label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" name="judge" id="judge">
                                                                        <option value="0">Select</option>
                                                                        <?php
                                                                        if (!empty($judges) || !empty($judgeid)) {
                                                                            foreach ($judges as $judge) {
                                                                                if ($judgeid == $judge['jcode'])
                                                                                    echo '<option value="' . $judge['jcode'] . '" selected="selected">' . $judge['jname'] . '</option>';
                                                                                else
                                                                                    echo '<option value="' . $judge['jcode'] . '">' . $judge['jname'] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6" id="divOfficer" style="display: none">
                                                            <div class="form-group row ">
                                                                <label for="officer" class="col-sm-4 col-form-label">By Name(Officer): </label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" name="officer" id="officer">
                                                                        <option value="0">Select</option>
                                                                        <?php
                                                                        if (!empty($officers) || !empty($officerid)) {

                                                                            foreach ($officers as $officer) {
                                                                                if ($officerid == $officer['usercode'])
                                                                                    echo '<option value="' . $officer['usercode'] . '" selected="selected">' . $officer['name'] . ' (' . $officer['empid'] . '), ' . $officer['type_name'] . '</option>';
                                                                                else
                                                                                    echo '<option value="' . $officer['usercode'] . '">' . $officer['name'] . ' (' . $officer['empid'] . '), ' . $officer['type_name'] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6" id="divSection" style="display: none">
                                                            <div class="form-group row ">
                                                                <label for="dealingSection" class="col-sm-4 col-form-label">Dealing Section: </label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control" name="dealingSection" id="dealingSection">
                                                                        <option value="0">Select</option>
                                                                        <?php
                                                                        if (!empty($dealingSections) || !empty($dealingSectionid)) {
                                                                            foreach ($dealingSections as $dealingSection) {
                                                                                if ($dealingSectionid == $dealingSection['id'])
                                                                                    echo '<option value="' . $dealingSection['id'] . '" selected="selected">' . $dealingSection['section_name'] . '</option>';
                                                                                else
                                                                                    echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <br>

                                                <div style="display:flex;justify-content:center">
                                                    <button type="button" id="btnGetCases" class="btn btn-primary col-sm-2" onclick="check();">View</button>
                                                </div>
                                            </div>


                                            <?php form_close(); ?>

                                            <div id="printable"></div>

                                            <br><br>

                                        </div>
                                    </div>
                                </div>

                            </div> <!-- card div -->

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
            </div>
        </div>
    </div>

    <!-- /.container-fluid -->
</section>
<!-- /.section -->
<script>
    function check() {
        //var searchBy = document.getElementsByName('seachBy').value;
        if (!$("input[name=searchBy]:checked").val()) {
            alert("Please Select Serch By option.");
            return false;
        }
        var searchBy = $("input[name=searchBy]:checked").val()
        if (searchBy == 'j') {
            var judgeName = $("#judge option:selected").val();
            if (judgeName == "0") {
                alert("Please Select Hon'ble Judge Name.");
                return false;
            }
        } else if (searchBy == 'o') {
            var Officer = $("#officer option:selected").val();
            if (Officer == "0") {
                alert("Please Select Officer Name.");
                return false;
            }
        } else if (searchBy == 's') {
            var DealingSection = $("#dealingSection option:selected").val();
            if (DealingSection == "0") {
                alert("Please Select Dealing Section.");
                return false;
            }
        }
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
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

        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");
            $("#toDate").focus();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?= base_url('/RI/ReceiptController/getDispatchData'); ?>',
            data: $("#dispatchDak").serialize(),
            beforeSend: function() {
                $('#printable').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(result) {
                updateCSRFToken();
                $("#printable").html(result);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.error("Error occurred: " + error);
                alert("Failed to dispatch AD letters. Please try again.");
            }
        });



    }

    window.onload = showHideDiv(0);

    function showHideDiv(id) {
        // alert(id);

        if (id == '0') {
            // alert("Inside 0");
            document.getElementById("divJudge").style.display = "none";
            document.getElementById("divOfficer").style.display = "none";
            document.getElementById("divSection").style.display = "none";
        }
        if (id == 'a') {
            //alert("Inside 0");
            document.getElementById("divJudge").style.display = "none";
            document.getElementById("divOfficer").style.display = "none";
            document.getElementById("divSection").style.display = "none";
        }
        if (id == 'j') {
            document.getElementById("divJudge").style.display = "block";
            document.getElementById("divOfficer").style.display = "none";
            document.getElementById("divSection").style.display = "none";
        } else if (id == 'o') {
            document.getElementById("divJudge").style.display = "none";
            document.getElementById("divOfficer").style.display = "block";
            document.getElementById("divSection").style.display = "none";
        } else if (id == 's') {
            document.getElementById("divJudge").style.display = "none";
            document.getElementById("divOfficer").style.display = "none";
            document.getElementById("divSection").style.display = "block";
        }
    }

    function selectallMe() {
        var checkBoxList = $('[name="daks[]"]');

        if ($('#allCheck').is(':checked')) {

            for (var i1 = 0; i1 < checkBoxList.length; i1++) {
                checkBoxList[i1].checked = true;
            }

        } else {
            for (var i1 = 0; i1 < checkBoxList.length; i1++) {
                checkBoxList[i1].checked = false;
            }
        }
    }
    $(document).on("focus", ".dtp", function() {
		$('.dtp').datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:2050'
		});   
	});

    function doDispatch() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var selectedCases = [];

        $('#tblDispatchDak input:checked').each(function() {
            if ($(this).attr('name') != 'allCheck')
                selectedCases.push($(this).val());
        });

        if (selectedCases.length <= 0) {
            alert("Please select at least one dak for dispatch.");
            return false;
        }

        $.ajax({
            url: "<?= base_url('/RI/ReceiptController/doDispatchDak'); ?>",
            type: "POST",
            data: {
                selectedCases: selectedCases,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(result) {
                updateCSRFToken();
                $("#printable").html(result);
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.error("AJAX Error: ", error);
                alert("An error occurred while dispatching. Please try again.");
            }
        });
    }
</script>