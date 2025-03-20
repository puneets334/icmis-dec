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
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

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
                                    <h3 class="card-title">R & I >>Dispatch Letters >>Search By </h3>
                                </div>


                            </div>
                            

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

                        <?//= view('RI/RIDispatchHeading'); ?>
                        </br></br>

                        <div class="container-fluid">
                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'dispatchDakFromRI', 'id' => 'dispatchDakFromRI', 'autocomplete' => 'off');
                            echo form_open(base_url('#'), $attribute); ?>
                           <!-- <form id="dispatchDakFromRI" method="POST">-->
                                <!--div date start-->
                                <div class="form-group col-sm-12">
                                    <br>

                        <span>
                            <!-- <h4 class="box-title"> </h4>
                            <br>
                            <br> -->
                             <div  class="row ">
                            <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>&nbsp;
                            <input type="hidden" id="status" name="status" value="2">
                            </div>

                        </span>
                                </div>
                                <div  class="row search_form" id="divSection" style="display: block">
                                    <div class="row">
                                    <div class="form-group col-sm-2 ">
                                        <label for="from">From Date</label>
                                        <input type="text" id="fromDate" name="fromDate" class="form-control dtp" autocomplete="off" placeholder="From Date" value="">
                                    </div>
                                    <div class="form-group col-sm-2" >
                                        <label for="from">To Date</label>
                                        <input type="text" id="toDate" name="toDate" class="form-control dtp" placeholder="From Date" autocomplete="off" value="">
                                    </div>
                                        <div class="form-group col-sm-2">
                                            <label for="from">Section</label>
                                            <select class="form-control" name="dealingSection" id="dealingSection">
                                                <option value="0">All</option>
                                                <?php

                                                if(!empty($dealingSections)) {
    //                                                echo "<pre>";
    //                                                print_r($dealingSections);
    ////                                                die;
    //                                            }
                                                    foreach ($dealingSections as $dealingSection) {
    //                                                if ($dealingSectionId == $dealingSection['id'])
    //                                                    echo '<option value="' . $dealingSection['id'] . '" selected="selected">' . $dealingSection['section_name'] . '</option>';
    //                                                else
                                                        echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
                                </div>
                                <!--div date end-->
                                <!--divCaseNumber Start-->

                                <div id="divCaseTypeWise" class="search_form" style="display: none;">
                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="from">Case Type</label>
                                            <select class="form-control" name="caseType" id="caseType">
                                                <option value="">Select</option>
                                                <?php
                                                if(!empty($caseTypes)) {
                                                    foreach ($caseTypes as $caseType) {
                                                        echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="caseNo">Case Number</label>
                                            <input type="number" id="caseNo" name="caseNo" class="form-control"
                                                placeholder="Case Number" value="">
                                        </div>
                                        <div class="form-group col-sm-2">
                                        <label for="caseYear">Case Year</label>
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
                                <div id="divDiaryNoWise" class="search_form" style="display: none;">
                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="diaryNumber">Diary Number</label>
                                            <input type="number" id="diaryNumber" name="diaryNumber" class="form-control"
                                                placeholder="Diary Number" value="">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="diaryYear">Diary Year</label>
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
                                <div id="divProcessIdWise" class="search_form" style="display: none;">
                                    <div class="row">
                                        <div class="form-group col-sm-2">
                                            <label for="processId">Process Id</label>
                                            <input type="number" id="processId" name="processId" class="form-control"
                                                placeholder="Process Id" value="">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="processYear">Process Year</label>
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
                                <div Class="row">
                                <div class="form-group col-sm-2">
                                    <label for="from">Dispatch Mode</label>
                                    <select class="form-control" id="dispatchMode" name="dispatchMode" class="form-control">
                                        <option value="0">Select Mode</option>
                                        <?php
                                        if(!empty($dispatchModes)) {
                                            foreach ($dispatchModes as $mode) {
                                                //                                                if ($dispatchMode == $mode['id'])
                                                //                                                    echo '<option value="' . $mode['id'] . '" selected="selected">' . $mode['postal_type_description'] . '</option>';
                                                //                                                else
                                                    echo '<option value="' . $mode['id'] . '">' . $mode['postal_type_description'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                </div>


                                <div class="form-group col-sm-3 pull-right">
                                    <label for="from" class="text-right">&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-info form-control" onclick="checkFunction();">View</button>
                                </div>
                            <?= form_close();?>
                           <!-- </form>-->

                            <div id="dataForDispatch">

                            </div>


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
        $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
        $("input[name$='searchBy']").click(function() {
            var searchValue = $(this).val();
            $('.search_form'). find('input, select'). val('');
            if(searchValue=='s')
            {
                $('#divSection').show();
                $('#divCaseTypeWise').hide();
                $('#divDiaryNoWise').hide();
                $('#divProcessIdWise').hide();

                $('#fromDate').prop('disabled',false);
                $('#toDate').prop('disabled',false);
                $('#dealingSection').prop('disabled',false);

            }
            else if(searchValue=='c')
            {
                $('#divSection').hide();
                $('#divCaseTypeWise').show();
                $('#divDiaryNoWise').hide();
                $('#divProcessIdWise').hide();

                $('#fromDate').prop('disabled',true);
                $('#toDate').prop('disabled',true);
                $('#dealingSection').prop('disabled',true);

            }
            else if(searchValue=='d')
            {
                $('#divSection').hide();
                $('#divCaseTypeWise').hide();
                $('#divDiaryNoWise').show();
                $('#divProcessIdWise').hide();

                $('#fromDate').prop('disabled',true);
                $('#toDate').prop('disabled',true);
                $('#dealingSection').prop('disabled',true);

            }
            else if(searchValue=='p')
            {
                $('#divSection').hide();
                $('#divCaseTypeWise').hide();
                $('#divDiaryNoWise').hide();
                $('#divProcessIdWise').show();

                $('#fromDate').prop('disabled',true);
                $('#toDate').prop('disabled',true);
                $('#dealingSection').prop('disabled',true);
            }
        });

        $('.number').keypress(function(event) {
            if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
                return true;
            else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
                event.preventDefault();
        });



        function checkFunction() {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var searchBy = $("input[name='searchBy']:checked").val();
            //alert(searchBy);
            if(searchBy=="s"){
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
            }
            else if(searchBy=="c")
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
            }
            else if(searchBy=="d")
            {
               var diaryNumber = $("#diaryNumber").val();
               if (diaryNumber == "") {
                   alert("Enter Diary Number.");
                   $("#diaryNumber").focus();
                   return false;
               }
            }
            else if(searchBy=="p")
            {
               var processId = $("#processId").val();
               if (processId == "") {
                   alert("Enter Process Id.");
                   $("#processId").focus();
                   return false;
               }
            }
            //alert("RRRr");
            //$.get("<?//=base_url()?>//RI/DispatchController/getDataToDispatch", $("#dispatchDakFromRI").serialize(), function (result) {
            //    //alert(result);
            //    $("#dataForDispatch").html(result);
            //});

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    data: $("#dispatchDakFromRI").serialize(),
                    },
                dataType: 'JSON',
                url: "<?php echo base_url('RI/DispatchController/getDataToDispatch'); ?>",
                success: function(data) {
                    updateCSRFToken();
                   
                    if (data == '1' || data == '' || 1) {
                        alert("Success! PIL File Group information Saved Successfully.");
                        //$('#groupFileNumber').val('');
                        $("#dataForDispatch").html(result);


                    } else {
                        alert("There is some problem while saving data,Please Contact Computer Cell.");
                    }
                   
                },
                error: function(data) {
                   // alert('No Data Found');
                    $("#dataForDispatch").html('<center><h4 style="color:red;">Nothing to Dispatch!!</h4></center>');
                    updateCSRFToken();
                }
            });

        }


    </script>



 <?//=view('sci_main_footer') ?>