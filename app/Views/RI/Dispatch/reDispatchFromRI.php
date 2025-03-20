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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">R & I >> Re-Dispatch Letters </h3>
                            </div>
                        </div>
                    </div> <br><br>       
                    <div class="container-fluid">
                                <form id="dispatchDakFromRI" method="post">
                                        <?= csrf_field(); ?>    
                                        <div class="form-group col-sm-12">
                                            <span style="float: left;">
                                                <!-- <h4 class="box-title">Search By : </h4> -->
                                                <label class="radio-inline"><input type="radio" name="searchBy" value="s" checked="">Date & Section</label>
                                                <label class="radio-inline"><input type="radio" name="searchBy" value="c">Case Type</label>
                                                <label class="radio-inline"><input type="radio" name="searchBy" value="d">Diary No.</label>
                                                <label class="radio-inline"><input type="radio" name="searchBy" value="p">Process Id</label>
                                                <input type="hidden" id="status" name="status" value="3">
                                            </span>
                                        </div><br><br>
                                        <div id="divSection" class="search_form" style="display: block">
                                            <div class="row">
                                                <div class="form-group col-sm-2">
                                                    <label for="from">From Date</label>
                                                    <input type="text" id="fromDate" name="fromDate" class="form-control datepick" autocomplete="off" placeholder="From Date" value="<?php //= $fromDate ?>">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="from">To Date</label>
                                                    <input type="text" id="toDate" name="toDate" class="form-control datepick" placeholder="From Date" autocomplete="off" value="<?php //= $toDate ?>">
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="from">Section</label>
                                                    <select class="form-control" name="dealingSection" id="dealingSection">
                                                        <option value="0">All</option>
                                                        <?php
                                                        foreach ($dealingSections as $dealingSection) {
                                                            //if ($dealingSectionId == $dealingSection['id'])
                                                            // echo '<option value="' . $dealingSection['id'] . '" selected="selected">' . $dealingSection['section_name'] . '</option>';
                                                        // else
                                                                echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
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
                                                        <option value="0">Select</option>
                                                        <?php
                                                        foreach($caseTypes as $caseType){
                                                            echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
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
                                    <div class="row">    
                                    <div class="form-group col-sm-2">
                                        <label for="from">Dispatch Mode</label>
                                        <select class="form-control" id="dispatchMode" name="dispatchMode">
                                            <option value="0">Select Mode</option>
                                            <?php
                                            foreach($dispatchModes as $mode){
                                            // if($dispatchMode==$mode['id'])
                                                // echo '<option value="' . $mode['id'] . '" selected="selected">' . $mode['postal_type_description'] . '</option>';
                                            // else
                                                    echo '<option value="' . $mode['id'] . '">' . $mode['postal_type_description'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3 pull-right">
                                            <label for="from" class="text-right">&nbsp;</label>
                                            <button type="button" id="btnGetCases" class="btn btn-info form-control mt-4"
                                                    onclick="check();">View
                                            </button>
                                        </div>
                                    </div>

                                       

                                </form>

                            <div id="dataForDispatch"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            autocomplete: false
        });
    });
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
      }
      $("input[name$='searchBy']").click(function() {
            var searchValue = $(this).val();
            $('.search_form'). find('input, select'). val('');
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
        });

        $('.number').keypress(function(event) {
            if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
                return true;
            else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
                event.preventDefault();
        });


    function check() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var searchBy = $("input[name='searchBy']:checked").val();
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
        $.ajax({
            type: "POST",
            data: $("#dispatchDakFromRI").serialize(),  // Serializes form data including CSRF_TOKEN if part of form
            dataType: 'html',  // Assuming you want HTML data back from the server
            url: "<?php echo base_url('RI/DispatchController/getDataToreDispatch'); ?>",
            beforeSend: function () {
                $('#dataForDispatch').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data) {
               // alert("Success!");
               // $('.card-title').hide();
               // $('.page-header').hide();
                $("#dataForDispatch").html(data);  // Assuming the server returns HTML
               // $("#dispatchDakFromRI").hide(); 
                updateCSRFToken();  // Make sure this function updates the CSRF token as needed
            },
            error: function() {
                alert('No Data Found');
                updateCSRFToken();  // Ensure CSRF token is refreshed even on error
            }
        });
    }
    function checkIfAlreadyEntered(obj){
        var dataToCheck=obj.value;
        if(dataToCheck!=""){
            var barcodeData=$("[name='barcode'][id!='"+obj.id+"']").serializeArray().map(a=>a.value);
            if($.inArray(dataToCheck, barcodeData) !== -1){
                swal({
                    icon: "warning",
                    text: 'It seems, you are entering same postal code as entered earlier.',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        confirm: {
                            text: "No, Enter New",
                            value: true,
                            visible: true,
                            className: "btn-danger",
                            closeModal: true,
                            focused: true
                        },
                        modify: {
                            text: "Yes, Continue with the same",
                            value: false,
                            visible: true,
                            className: "btn-success",
                            closeModal: true
                        }
                    }
                }).then(function (ifDuplicateNotAllowed) {
                    if (ifDuplicateNotAllowed === true) {
                        $(obj).val('').focus();
                    }
                    else if (ifDuplicateNotAllowed === false) {
                        var clickedId=obj.id.split('_');
                        $("#daks_"+clickedId[1]).focus();
                    }
                });
            }
        }
    }
    function doReDispatch() {
        var selectedCases = [];
        var dispatchModes= [];
        var amounts= [];
        var weights= [];
        var barcodes= [];
        var toExit=0;
        $('#tblDispatchDak input:checked').each(function () {
            if ($(this).attr('name') != 'allCheck'){
                var dispatchId=$(this).attr('value');
                selectedCases.push(dispatchId);
                if($('#dispatchMode_'+dispatchId).val()==0){
                    alert("Select Dispatch Mode.");
                    $("#dispatchMode_"+dispatchId).focus();
                    toExit=1;
                    return false;
                }
                if($('#dispatchMode_'+dispatchId).val()!=4 && $('#dispatchMode_'+dispatchId).val()!=5 && $('#dispatchMode_'+dispatchId).val()!=6){
                    if($('#dispatchMode_'+dispatchId).val()!=2){//Not Compulsory if dispatch mode is Speed post
                        if($('#weight_'+dispatchId).val()==0){
                            alert("Enter Weight.");
                            $("#weight_"+dispatchId).focus();
                            toExit=1;
                            return false;
                        }
                    }
                    if($('#amount_'+dispatchId).val()==0){
                        alert("Enter Amount.");
                        $("#amount_"+dispatchId).focus();
                        toExit=1;
                        return false;
                    }
                    if($('#barcode_'+dispatchId).val()==0){
                        alert("Enter Barcode.");
                        $("#barcode_"+dispatchId).focus();
                        toExit=1;
                        return false;
                    }
                }
                dispatchModes.push($('#dispatchMode_'+dispatchId).val());
                amounts.push($('#amount_'+dispatchId).val());
                weights.push($('#weight_'+dispatchId).val());
                barcodes.push($('#barcode_'+dispatchId).val());
            }
        });
        if(toExit==1){
            return false;
        }
        if (selectedCases.length <= 0) {
            alert("Please Select at least one dak for dispatch..");
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.post("<?=base_url()?>/RI/DispatchController/doReDispatchFromRI", {
            'selectedCases': selectedCases,
            'dispatchModes': dispatchModes,
            'amounts': amounts,
            'weights': weights,
            CSRF_TOKEN: CSRF_TOKEN_VALUE,
            'barcodes': barcodes}, function (result) {
               // $("#dispatchDakFromRI").hide(); 
                $("#dataForDispatch").hide(); 
            alert("Selected Letters re-dispatched from R&I Successfully!");
            $('#dispatchDakFromRI').submit();
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
    });

    // $('.number').keypress(function(event) {
    //     if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46)
    //         return true;
    //     else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
    //         event.preventDefault();
    // });

</script>