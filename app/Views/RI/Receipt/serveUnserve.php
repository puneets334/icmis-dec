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
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
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


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">R & I >> Receipt </h3>
                                </div>


                            </div>
                            <br><br>

                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>



                        </div>


                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?php //= view('RI/RIReceiptHeading'); ?>

                        <br><br>
                        <div class="container-fluid">
                            <h4 class="page-header" style="margin-left: 1%">Update Serve Status</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'updateServeUnserve', 'id' => 'updateServeUnserve', 'autocomplete' => 'off');
                            echo form_open(base_url('RI/ReceiptController/dateWiseReceived'), $attribute);
                            ?>



                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="box-title">Search By : </h4><br>
                                    <div class="form-group ">

                                        <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>
                                        <input type="hidden" id="status" name="status" value="8888">
                                    </div>
                                </div>


                            </div><br>


                            <div class="rowww">
                                <!--start 1 section-->
                                <div  id="divSection" style="display: block">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row " >
                                                <label for="from" class="col-sm-4 col-form-label">From Date: </label>
                                                <div class="col-sm-7">

                                                    <input type="text" id="fromDate" name="fromDate" class="form-control dtp" autocomplete="off" placeholder="From Date" value="<?= !empty($fromDate)?$fromDate:null; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="to_date" class="col-sm-4 col-form-label">To Date:</label>
                                                <div class="col-sm-7">

                                                    <input type="text" id="toDate" name="toDate" class="form-control dtp" placeholder="From Date" autocomplete="off" value="<?= !empty($toDate)?$toDate:null; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="section" class="col-sm-4 col-form-label">Section:</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="dealingSection" id="dealingSection">
                                                        <option value="0">All</option>
                                                        <?php
                                                        if(!empty($dealingSections)) {
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
                                </div>
                                <!--end 1 section-->


                                <!--start 2 section-->
                                <div  id="divCaseTypeWise" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row " >
                                                <label for="from" class="col-sm-4 col-form-label">Case Type: </label>
                                                <div class="col-sm-7">
                                                    <select class="form-control" name="caseType" id="caseType">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        if(!empty($caseTypes)) {
                                                            foreach ($caseTypes as $caseType) {
                                                                echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="caseNo" class="col-sm-4 col-form-label">Case Number:</label>
                                                <div class="col-sm-7">
                                                    <input type="number" id="caseNo" name="caseNo" class="form-control" placeholder="Case Number" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="caseYear" class="col-sm-4 col-form-label">Case Year:</label>
                                                <div class="col-sm-7">
                                                    <select id="caseYear" name="caseYear" class="form-control">
                                                        <?php
                                                        for($i=date("Y");$i>1949;$i--){
                                                            echo "<option value=".$i.">$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--end 2 section-->



                                <!--start 3 section-->
                                <div  id="divDiaryNoWise" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row " >
                                                <label for="diaryNumber" class="col-sm-4 col-form-label">Diary Number: </label>
                                                <div class="col-sm-7">
                                                    <input type="number" id="diaryNumber" name="diaryNumber" class="form-control" placeholder="Diary Number" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="diaryYear" class="col-sm-4 col-form-label">Diary Year:</label>
                                                <div class="col-sm-7">
                                                    <select id="diaryYear" name="diaryYear" class="form-control">
                                                        <?php
                                                        for($i=date("Y");$i>1949;$i--){
                                                            echo "<option value=".$i.">$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--end 3 section-->



                                <!--start 4 section-->
                                <div  id="divProcessIdWise" style="display: none;">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row " >
                                                <label for="processId" class="col-sm-4 col-form-label">Process Id: </label>
                                                <div class="col-sm-7">
                                                    <input type="number" id="processId" name="processId" class="form-control" placeholder="Process Id" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="processYear" class="col-sm-4 col-form-label">Process Year:</label>
                                                <div class="col-sm-7">
                                                    <select id="processYear" name="processYear" class="form-control">
                                                        <?php
                                                        for($i=date("Y");$i>1949;$i--){
                                                            echo "<option value=".$i.">$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--end 4 section-->
                                <br>

                                <br>
                                <br>

                                <div style="display:flex;justify-content:center" >
                                    <button type="button" id="btnGetCases" class="btn btn-primary col-sm-2" onclick="check();">View</button>
                                </div>
                            </div>


                            <?php form_close();?>

                            <div id="dataForServeUnServe"></div>

                            <br><br>






                            <!-- /.content -->
                            <!--</div>-->
                            <!-- /.container -->
                        </div>
                        <br>
                        <br>
                        <br>

                    </div> <!-- card div -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->
    <script>

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

        function check() {

            var searchBy = $("input[name='searchBy']:checked").val();
            // alert(searchBy);
            if(searchBy=="s"){
                var fromDate = $("#fromDate").val();
                var toDate = $("#toDate").val();
                if (fromDate == "") {
                    alert("Select Received From Date.");
                    $("#fromDate").focus();
                    return false;
                }
                if(toDate == "") {
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
            }else if(searchBy=="c")
            {
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
            }else if(searchBy=="d")
            {
                var diaryNumber = $("#diaryNumber").val();
                if (diaryNumber == "") {
                    alert("Enter Diary Number.");
                    $("#diaryNumber").focus();
                    return false;
                }
            }else if(searchBy=="p")
            {
                var processId = $("#processId").val();
                if (processId == "") {
                    alert("Enter Process Id.");
                    $("#processId").focus();
                    return false;
                }
            }

            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/ReceiptController/getDataForServeUnserve');?>',
                beforeSend: function() {
                    $('#dataForServeUnServe').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: $("#updateServeUnserve").serialize(),
                success: function (result) {
                    updateCSRFToken();
                    $("#dataForServeUnServe").html(result);
                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    $("#dataForServeUnServe").html("<p style='color:red;text-align:center;'>Error loading data. Please try again.</p>");
                }
            });

            //$.post("<?//=base_url()?>//RI/ReceiptController/getDataForADToDispatch", $("#dispatchADToSection").serialize(), function (result) {
            //    //alert(result);
            //    $("#dataForDispatchAD").html(result);
            //});
        }

        function selectallMe() {
            var checkBoxList=$('[name="daks[]"]');

            if ($('#allCheck').is(':checked'))
            {

                for (var i1 = 0; i1<checkBoxList.length; i1++){
                    checkBoxList[i1].checked=true;
                }

            }else{
                for (var i1 = 0; i1<checkBoxList.length; i1++){
                    checkBoxList[i1].checked=false;
                }
            }
        }

        function doUpdateServeUnserve() {
            var selectedCases = [];
            var serveStage= [];
            var serveType= [];
            var remarks= [];
            var toExit=0;
            $('#tblUpdateServeUnserve input:checked').each(function () {
                if ($(this).attr('name') != 'allCheck'){
                    var dispatchId=$(this).attr('value');
                    selectedCases.push(dispatchId);
                    if($('#serveStage_'+dispatchId).val()==0){
                        alert("Select Serve Stage.");
                        $("#serveStage_"+dispatchId).focus();
                        toExit=1;
                        return false;
                    }
                    else if($('#serveType_'+dispatchId).length && $('#serveType_'+dispatchId).val()==0){
                        alert("Select Serve Type.");
                        $("#serveType_"+dispatchId).focus();
                        toExit=1;
                        return false;
                    }
                    serveStage.push($('#serveStage_'+dispatchId).val());
                    serveType.push($('#serveType_'+dispatchId).val());
                    remarks.push($('#remarks_'+dispatchId).val().trim());
                }
            });
            if(toExit==1){
                return false;
            }
            if (selectedCases.length <= 0) {
                alert("Please Select at least one dak for dispatch..");
                return false;
            }

            $.ajax({
                type: 'GET',
                url:'<?=base_url('RI/ReceiptController/doUpdateServeUnServe')?>',
                data: {
                    selectedCases: selectedCases,
                    serveStage: serveStage,
                    serveType: serveType,
                    remarks: remarks
                },
                success: function(result){                   
                        if(result>0){
                            alert(result+ " Selected Letters Serve Status Updated Successfully!");
                            check();
                        }
                    },
                error: function() {                  
                    alert("Error, Something Went Wrong!!");
                    
                }

            });


        }

        $("input[name$='searchBy']").click(function() {
            var searchValue = $(this).val();
            if(searchValue=='s')
            {
                $('#divSection').show();
                $('#divCaseTypeWise').hide();
                $('#divDiaryNoWise').hide();
                $('#divProcessIdWise').hide();

            }
            else if(searchValue=='c')
            {
                $('#divSection').hide();
                $('#divCaseTypeWise').show();
                $('#divDiaryNoWise').hide();
                $('#divProcessIdWise').hide();
            }
            else if(searchValue=='d')
            {
                $('#divSection').hide();
                $('#divCaseTypeWise').hide();
                $('#divDiaryNoWise').show();
                $('#divProcessIdWise').hide();
            }
            else if(searchValue=='p')
            {
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

            if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
                return true;

            else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
                event.preventDefault();

        });


    </script>
