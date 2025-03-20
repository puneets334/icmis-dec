<?= view('header') ?>

<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }

    .card-header {
        padding: 5px;
    }

    h4 {
        line-height: 0px;
    }

    .row {
        margin-right: 15px;
        margin-left: 15px;
    }

    a {
        color: darkslategrey
    }

    /* Unvisited link  */

    a:hover {
        color: black
    }

    /* Mouse over link */
    a:active {
        color: #0000FF;
    }

    /* Selected link   */

    .box.box-success {
        border-top-color: #00a65a;
    }

    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .box-header {
        color: #444;
        display: block;
        padding: 10px;
        position: relative;
    }

    .box-header.with-border {
        border-bottom: 1px solid #f4f4f4;
    }

    .box.box-danger {
        border-top-color: #dd4b39;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">R & I >> Advanced Dispatch Query </h3>
                            </div>
                        </div>
                    </div> <br><br>
                    <div class="container-fluid">

                        <form id="dispatchQuery">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6" style="border-right: 1px solid #ccc;">

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <label for="caseType" class="text-right">Case Type</label>
                                            <select id="caseType" name="caseType" class="form-control">
                                                <option value="0">Select</option>
                                                <?php
                                                foreach ($caseTypes as $caseType) {
                                                    echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="caseNo" class="text-right">Case No.</label>
                                            <input type="number" id="caseNo" name="caseNo" class="form-control" placeholder="Case Number" value="">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="caseYear">Case Year</label>
                                            <select id="caseYear" name="caseYear" class="form-control">
                                                <?php
                                                for ($i = date('Y'); $i > 1949; $i--) {
                                                    echo "<option value=" . $i . ">$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="diaryNo" class="text-right">Diary No.</label>
                                            <input type="number" id="diaryNo" name="diaryNo" class="form-control" placeholder="Diary Number" value="">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="diaryYear" class="text-right">Diary Year</label>
                                            <select id="diaryYear" name="diaryYear" class="form-control">
                                                <?php
                                                for ($i = date('Y'); $i > 1949; $i--) {
                                                    echo "<option value=" . $i . ">$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <label for="processId" class="text-right">Process ID</label>
                                            <input type="number" id="processId" name="processId" class="form-control" placeholder="Process ID" value="">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="prYear" class="text-left">Process Year</label>
                                            <select id="prYear" name="prYear" class="form-control">
                                                <?php
                                                for ($i = date("Y"); $i > 1949; $i--) {
                                                    echo "<option value=" . $i . ">$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="refId" class="text-right">Reference Number</label>
                                            <input type="number" id="refId" name="refId" class="form-control" placeholder="Reference Number" value="">
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="fromStoRI" class="text-right">Dispatched from Section to R&I</label>
                                            <input type="text" id="fromStoRI" name="fromStoRI" class="form-control datepick" placeholder="From Date" value="<?php //=$fromStoRI
                                                                                                                                                            ?>">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="toStoRI" style="color:white;" class="text-right">To Date</label>
                                            <input type="text" id="toStoRI" name="toStoRI" class="form-control datepick" placeholder="To Date" value="<?php //=$toStoRI
                                                                                                                                                        ?>">
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-4">
                                            <label for="fromRItoS" class="text-right">Received in R&I from Section</label>
                                            <input type="text" id="fromRItoS" name="fromRItoS" class="form-control datepick" placeholder="From Date" value="<?php //=$fromRItoS
                                                                                                                                                            ?>">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="toRItoS" class="text-right" style="color:white;">Received in R&I from Section</label>
                                            <input type="text" id="toRItoS" name="toRItoS" class="form-control datepick" placeholder="To Date" value="<?php //=$toRItoS
                                                                                                                                                        ?>">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="fromRItoR" class="text-right">Dispatched from R&I to Recipient</label>
                                            <input type="text" id="fromRItoR" name="fromRItoR" class="form-control datepick" placeholder="From Date" value="<?php //=$fromRItoR
                                                                                                                                                            ?>">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="toRItoR" class="text-right" style="color:white">Dispatched from R&I to recipient</label>
                                            <input type="text" id="toRItoR" name="toRItoR" class="form-control datepick" placeholder="To Date" value="<?php //=$toRItoR
                                                                                                                                                        ?>">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="button" class="text-right">&nbsp;</label>
                                    <button type="button" style="text-align:center; width:98%; margin-left:1%; margin-right:1%;" id="view" name="view" onclick="check(); " class="btn btn-block btn-primary">View</button>
                                </div>
                            </div>
                        </form>
                        <div>

                    </div>
                    <br/>
                        <div id="printable" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,

            autoclose: true
        });
    });

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function check() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var caseType = $('#caseType').val();
        var caseNo = $('#caseNo').val();
        var caseYear = $('#caseYear').val();
        var processId = $('#processId').val();
        var prYear = $('#prYear').val();
        var diaryNo = $('#diaryNo').val();
        var diaryYear = $('#diaryYear').val();
        var fromStoRI = $('#fromStoRI').val();
        var toStoRI = $('#toStoRI').val();
        var fromRItoS = $('#fromRItoS').val();
        var toRItoS = $('#toRItoS').val();
        var fromRItoR = $('#fromRItoR').val();
        var toRItoR = $('#toRItoR').val();
        var refNo = $('#refId').val();


        if (caseNo != 0 && caseType == 0) {
            alert('Please select Case Type');
            $('#caseType').focus();
            return;
        } else if (caseType != 0 && caseNo == 0) {
            alert('Please enter Case No.');
            $('#caseNo').focus();
            return;
        } else if (caseNo != '' && diaryNo != '') {
            alert('Please enter either Diary Number or Case Number');
        } else if (check_error(fromStoRI, toStoRI, 1) == false) {
            return;
        } else if (check_error(fromRItoS, toRItoS, 2) == false) {
            return;
        } else if (check_error(fromRItoR, toRItoR, 3) == false) {
            return;
        } else if (caseNo != '' || processId != '' || diaryNo != '' || fromStoRI != '' || fromRItoS != '' || fromRItoR != '' || refNo != '') {

           
            $.ajax({
                type: "POST",
                data: $("#dispatchQuery").serialize(),
                dataType: 'html',
                url: "<?php echo base_url('/RI/DispatchController/getDispatchedData'); ?>",
                beforeSend: function() {
                    $('#printable').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                success: function(data) {
                    updateCSRFToken();                 
                    $("#printable").html(data);
                    
                },
                error: function() {
                    updateCSRFToken();
                    $("#printable").html(data);
                    
                }
            });
        } else {
            alert('Enter value in atleast one field');
            return;
        }

    }


    function check_error(fromDate, toDate, param) {
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);

        if (fromDate != toDate) {
            if (fromDate == "") {
                alert('Enter From Date');
                if (param == 1) {
                    $("#fromStoRI").focus();
                } else if (param == 2) {
                    $('#fromRItoS').focus();
                } else if (param == 3) {
                    $('#fromRItoR').focus();
                }
                return false;
            } else if (toDate == "") {
                alert('Enter To Date');
                if (param == 1) {
                    $("#toStoRI").focus();
                } else if (param == 2) {
                    $('#toRItoS').focus();
                } else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            } else if (date1 > date2) {
                alert("To Date must be greater than From date");
                if (param == 1) {
                    $("#toStoRI").focus();
                } else if (param == 2) {
                    $('#toRItoS').focus();
                } else if (param == 3) {
                    $('#toRItoR').focus();
                }
                return false;
            } else {
                return true;
            }
        }
    }
</script>