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
                            <h4 class="page-header" style="margin-left: 1%">Dispatch to Officer/Section</h4>
                            <br><br>


                            <?php
                            $attribute = array('class' => 'form-horizontal','name' => 'dispatchDak', 'id' => 'dispatchDak', 'autocomplete' => 'off');
                            echo form_open(base_url('#'), $attribute);
                            ?>




                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="box-title">Search By Name Of : </h4><br>
                                    <div class="form-group ">

                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByAll" value="a" checked onclick="showHideDiv(this.value)">All</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByJudge" value="j"   onclick="showHideDiv(this.value)">Hon'ble Judge</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchByOfficer" value="o"  onclick="showHideDiv(this.value)">Officer</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="radio-inline"><input type="radio" name="searchBy" id="searchBySection" value="s"  onclick="showHideDiv(this.value)">Section</label>

                                    </div>
                                </div>


                            </div><br>


                            <div class="rowww">
                                <!--start 1 section-->
                                <div >
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row " >
                                                <label for="from" class="col-sm-4 col-form-label">From Date: </label>
                                                <div class="col-sm-7">
                                                    <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($fromDate)?$fromDate:null;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="to_date" class="col-sm-4 col-form-label">To Date:</label>
                                                <div class="col-sm-7">
                                                    <input type="date" id="toDate" name="toDate" class="form-control datepick" required placeholder="From Date" value="<?=!empty($toDate)?$toDate:null;?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-4 col-form-label">Parcel Receipt Mode:</label>
                                                <div class="col-sm-7">
                                                    <?php
                                                    $options = array("All", "Ordinary", "Other Receipt Mode.");
                                                    ?>
                                                    <select  class="form-control" name="parcelReceiptMode" id="parcelReceiptMode">
                                                        <?php
                                                        foreach($options as $index=>$option){
                                                            if(!empty($reportType)){
                                                                if($reportType==$index)
                                                                    echo "<option value='".$index."' selected>".$option."</option>";
                                                            } else
                                                                echo "<option value='".$index."'>".$option."</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end 1 section-->



                                <br>
                                <div>
                                    <div class="row" >
                                        <div class="col-sm-6" id="divJudge" style="display: none">
                                            <div class="form-group row " >
                                                <label for="judge" class="col-sm-4 col-form-label">By Name(Hon'ble Judge): </label>
                                                <div class="col-sm-8">
                                                    <select  class="form-control" name="judge" id="judge">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        if(!empty($judges) || !empty($judgeid)) {
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
                                            <div class="form-group row " >
                                                <label for="officer" class="col-sm-4 col-form-label">By Name(Officer): </label>
                                                <div class="col-sm-8">
                                                    <select  class="form-control" name="officer" id="officer">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        if(!empty($officers) || !empty($officerid)) {

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
                                            <div class="form-group row " >
                                                <label for="dealingSection" class="col-sm-4 col-form-label">Dealing Section: </label>
                                                <div class="col-sm-8">
                                                    <select  class="form-control" name="dealingSection" id="dealingSection">
                                                        <option value="0">Select</option>
                                                        <?php
                                                        if(!empty($dealingSections) || !empty($dealingSectionid)) {
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

                                <div style="display:flex;justify-content:center" >
                                    <button type="button" id="btnGetCases" class="btn btn-primary col-sm-2" onclick="check();">View</button>
                                </div>
                            </div>


                            <?php form_close();?>

                            <div id="printable"></div>

                            <br><br>

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
        function check() {
            //var searchBy = document.getElementsByName('seachBy').value;
            if (!$("input[name=searchBy]:checked").val()) {
                alert("Please Select Serch By option.");
                return false;
            }
            var searchBy=$("input[name=searchBy]:checked").val()
            if(searchBy=='j'){
                var judgeName=$( "#judge option:selected" ).val();
                if (judgeName=="0") {
                    alert("Please Select Hon'ble Judge Name.");
                    return false;
                }
            }
            else if(searchBy=='o'){
                var Officer=$("#officer option:selected" ).val();
                if (Officer=="0") {
                    alert("Please Select Officer Name.");
                    return false;
                }
            }
            else if(searchBy=='s'){
                var DealingSection=$("#dealingSection option:selected" ).val();
                if (DealingSection=="0") {
                    alert("Please Select Dealing Section.");
                    return false;
                }
            }
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            if(fromDate==""){
                alert("Select Received From Date.");
                $("#fromDate").focus();
                return false;
            }
            if(toDate==""){
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
                url:'<?=base_url('/RI/ReceiptController/getDispatchData');?>',
                data: $("#dispatchDak").serialize(),
                success: function (result) {
                    $("#printable").html(result);
                       $('#tblDispatchDak').DataTable({
                           "bSort": false,
                           "bPaginate": false,
                           "bLengthChange": false,
                           dom: 'Bfrtip',
                           buttons: [
                               'print'
                           ],
                       } );

                }
            });



        }

        window.onload=showHideDiv(0);
        function showHideDiv(id)
        {
            // alert(id);

            if(id=='0'){
                // alert("Inside 0");
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            if(id=='a'){
                //alert("Inside 0");
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            if(id=='j'){
                document.getElementById("divJudge").style.display = "block";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            else if(id=='o'){
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="block";
                document.getElementById("divSection").style.display="none";
            }else if(id=='s'){
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="block";
            }
        }

        function doDispatch(){
            var selectedCases = [];
            $('#tblDispatchDak input:checked').each(function() {
                if($(this).attr('name')!='allCheck')
                    selectedCases.push($(this).attr('value'));
            });
            if(selectedCases.length<=0){
                alert("Please Select at least one dak for dispatch..");
                return false;
            }
            $.post("<?=base_url()?>index.php/RIController/doDispatchDak", {'selectedCases':selectedCases},function(result){

                //alert(usercode);
                $("#printable").html(result);
                $('#tblDispatchReport').DataTable({
                    "bSort": false,
                    "bPaginate": false,
                    "bLengthChange": false,
                    dom: 'Bfrtip',
                    buttons: [
                        'print'
                    ],
                } );
            });
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

    </script>