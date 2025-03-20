<?= view('header') ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Court Master</h3>
                            </div>
                            
                        </div>
                    </div>
                    <?= view('Court/CourtMaster/courtMaster_breadcrumb'); ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                            <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading"> Generate Proceedings</h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'courtMaster', 'id' => 'courtMaster', 'autocomplete' => 'off');
                                    echo form_open('Court/CourtMasterController/generateRop', $attribute);

                                    ?>
                                    <div class="tab-content">

                                        <div class="active tab-pane">
                                            <!-- <h3 class="basic_heading"> Generate Proceedings </h3><br> -->
                                            <div class="row">

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>">
                                                    <label class="">Causelist Date</label>
                                                    <input type="text" name="causelistDate" id="causelistDate" class="form-control dtp">
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label class="">Presiding Judge</label>
                                                    <select class="form-control" id="pJudge" name="pJudge">
                                                        <option value="">Select Presiding Judge</option>
                                                        <?php
                                                        foreach ($judge as $key => $j1):
                                                            if ($j1['jtype'] == 'J') {
                                                                echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $j1['jcode'] . '" >' . $j1['jcode'] . ' - ' . $j1['first_name'] . ' ' . $j1['sur_name'] . ' ' . $j1['jname'] . '</option>';
                                                            }
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>


                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label class="">Causelist Type</label>
                                                    <select class="form-control" name="causelistType" tabindex="1" id="causelistType" onchange="getBenches()">
                                                        <option value="">Select Causelist</option>
                                                        <option value="1">Regular List</option>
                                                        <option value="3">Misc. List</option>
                                                        <option value="5">Chamber List</option>
                                                        <option value="7">Registrar List</option>
                                                        <option value="9">Review/Curative List</option>
                                                        <option value="11">Single Judge List</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label class="">Bench</label>
                                                    <select class="form-control" name="bench" tabindex="1" id="bench"></select>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="button" id="btnGetCases" class="quick-btn mt-26" onclick="getCasesForGeneration()" value="Get Cases">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr><br>
                                    <div id="divCasesForGeneration">
                                    </div>

                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>


                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });
    function getBenches() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        var causelistDate = $('#causelistDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        if (causelistDate == "") {
            alert("Please fill Causelist Date..");
            return false;
        }
        if (pJudge == "") {
            alert("Please Select Presiding Judge..");
            return false;
        }
        if (causelistType == "") {
            alert("Please Select Type of Causelist..");
            return false;
        }
        if (causelistDate != "" && pJudge != "" && causelistType != "") {

            $.ajax({
                url: "<?php echo base_url('Court/CourtMasterController/getBench'); ?>",
                type: "get",
                data: {
                    CSRF_TOKEN: csrf,
                    causelistDate: causelistDate,
                    pJudge: pJudge,
                    causelistType: causelistType
                },
                success: function(result) {
                    $("#bench").html(result);
                    updateCSRFToken();
                    //window.location.href='';
                },
                error: function() {
                    alert('Error');
                    updateCSRFToken();
                }
            });

        }
    }


    function getCasesForGeneration() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        var causelistDate = $('#causelistDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        var bench = $('#bench').val();
        var usercode = $('#usercode').val();
        if (causelistDate == "") {
            alert("Please Select Causelist Date..");
            $('#causelistDate').focus();
            return false;
        }
        if (pJudge == "") {
            alert("Please Select Presiding Judge..");
            $('#pJudge').focus();
            return false;
        }
        if (causelistType == "") {
            alert("Please Select Type of Causelist..");
            $('#causelistType').focus();
            return false;
        }
        if (bench == "") {
            alert("Please Select Bench..");
            return false;
        }

        if (causelistDate != "" && pJudge != "" && causelistType != "" & bench != "") {

            $.ajax({
                url: "<?php echo base_url('Court/CourtMasterController/getCasesForGeneration'); ?>",
                type: "get",
                data: {
                    CSRF_TOKEN: csrf,
                    causelistDate: causelistDate,
                    pJudge: pJudge,
                    causelistType: causelistType,
                    bench: bench,
                    usercode: usercode
                },
                success: function(result) {
                    $('#divCasesForGeneration').html(result);
                    updateCSRFToken();
                    //window.location.href='';
                },
                error: function() {
                    alert('Error');
                    updateCSRFToken();
                }
            });
        }
    }
</script>