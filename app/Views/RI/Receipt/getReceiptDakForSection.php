<?= view('header') ?>
<?//= view('sci_main_header_css_js') ?>
 
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
    <style>
        #address,#case,#postal,#name,#disMode
        {
            display:none;
        }
        #printable
        {
            margin-top: 3%;
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
                            <h4 class="page-header" style="margin-left: 1%">Receive/Return Letters</h4>
                            <br><br>

                            <div id="divReceiptDak">
                            <?php
                            $attribute = array('class' => 'form-horizontal','name' =>"frmReceiveDakForSection", 'id' => "frmReceiveDakForSection", 'autocomplete' => 'off');
                            echo form_open(base_url('#'), $attribute);
                            ?>

                                <?php
                        if((isset($forReceiveInSection) && sizeof($forReceiveInSection)>0 ) || (isset($forInitiatedReceivedInSection) && sizeof($forInitiatedReceivedInSection)>0 ))
                        {
                                    ?>

                                    <div class="form-group col-sm-9">
                                        <div class="col-sm-3">
                                            <label for="from" class="text-right">Receive/Return/Forward</label>
                                            <select  class="form-control" name="actionType" id="actionType" onchange="showHideFieldsDiv(this.value)">
                                                <option value="0">Select Action</option>
                                                <option value="1">Receive</option>
                                                <option value="2">Return</option>
                                                <option value="3">Foreward</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4" id="divReturnReason">
                                            <label for="from" class="text-right">Return Reason</label>
                                            <input type="text" id="returnReason" name="returnReason" class="form-control" required="" placeholder="Return Reason" value="">
                                        </div>
                                        <div class="form-group col-sm-3" id="divSection">
                                            <label for="dealingSection">Select Section</label>
                                            <select class="form-control" name="dealingSection" id="dealingSection" onchange="setOfficersOfSection(this.value)">
                                                <option value="0">Select</option>
                                                <?php
                                                if(!empty($dealingSections)) {
                                                    foreach ($dealingSections as $dealingSection) {
                                                        if(!empty($dealingSectionid))
                                                        {
                                                            if ($dealingSectionid == $dealingSection['id'])
                                                                echo '<option value="' . $dealingSection['id'] . '" selected="selected">' . $dealingSection['section_name'] . '</option>';

                                                        }
                                                        else
                                                            echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-3" id="divOfficer">
                                            <label for="officer">Select Officer</label>
                                            <select class="form-control" name="officer" id="officer">
                                                <option value="0">Select</option>

                                            </select>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label for="from" class="text-right">&nbsp;</label>
                                            <button type="button" id="btnReceiveForSection" name="btnReceiveForSection" class="btn btn-success form-control" onclick="return doReceiveForSection();" ><i class="fa fa-fw fa-download"></i>&nbsp;Save/Send</button>
                                        </div>
                                    </div>
                                    <!--<table id="reportTable1" class="table table-striped table-hover">-->
                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                                    <table  id="tblDispatchDak" class="table table-striped custom-table datatable_report">
<!--                                    <table id="" style="width: 95%" class="table table-striped table-hover">-->
                                        <thead>
                                        <tr>
                                            <th width="4%">SNo.</th>
                                            <th width="15%">R&I Diary Number / Initiated Letter No. / Reference No./ Process Id</th>
                                            <th width="10%">Sent To</th>
                                            <th width="15%">Postal Type, Number & Date</th>
                                            <th width="20%">Sender Name & Address</th>
                                            <!--<th width="8%">Case Number</th>-->
                                            <th width="12%">Dispatched By/ Dispatched on</th>
                                            <th width="10%">Forwarded By/ Forwarded On</th>
                                            <th width="2%">Image</th>
                                            <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $s_no=1;
                                    if(!empty($forInitiatedReceivedInSection))
                                    {
                                        foreach ($forInitiatedReceivedInSection as $forwardedCase)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$s_no++;?></td>
                                                <td>
                                                    <?php

                                                    if(!empty($forwardedCase['letter_number'])){
                                                        echo "<b>Internally Initiated - </b><br>Letter No: ".$forwardedCase['letter_number'];
                                                        echo "<br><b style='color:blue'>(".$forwardedCase['letterPriority'].")</b>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?=$forwardedCase['address_to']?>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                    <?php if($forwardedCase['is_forwarded']=='f'){ echo $forwardedCase['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($forwardedCase['dispatched_on']));}?>
                                                </td>
                                                <td>
                                                    <?php if($forwardedCase['is_forwarded']=='t'){ echo $forwardedCase['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($forwardedCase['dispatched_on']));}?>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="getImages(<?=$forwardedCase['ec_postal_transactions_id']?>)" class="glyphicon glyphicon-book" data-target="#myModal"></button>
                                                </td>
                                                <td>
                                                    <?php if(!empty($forwardedCase['action_taken_on']) && $forwardedCase['action_taken_on']!='0000-00-00 00:00:00'){ ?>
                                                        <?=$forwardedCase['action_taken_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($forwardedCase['action_taken_on']))?>
                                                    <?php }
                                                    else{?>
                                                        <input type="checkbox" id="daks" name="daks[]" value="<?=$forwardedCase['id']?>#<?=$forwardedCase['ec_postal_transactions_id']?>#<?=$forwardedCase['is_ad_card']?>#<?=$forwardedCase['ec_postal_dispatch_id']?>">
                                                    <?php  }?>
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                    }
                                    if(!empty($forReceiveInSection))
                                    {
                                        foreach ($forReceiveInSection as $case)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$s_no?></td>
                                                <td>
                                                    <?php
                                                    if($case['is_ad_card']==1){
                                                        echo "AD Card/Un-Serve Letter<br/>";
                                                    }
                                                    if(!empty($case['diary'])){
                                                        echo "R&I Diary No: ".$case['diary'];
                                                    }
                                                    elseif ($case['is_with_process_id']==1){
                                                        echo 'PId. '.$case['process_id'].'/'.$case['process_id_year'];
                                                        echo '<br/>'.$case['serve_stage'].'-'.$case['serve_type'];
                                                        echo (trim($case['serve_remarks']) !='') ? '<br/>Remarks.: '.trim($case['serve_remarks']) : '';
                                                    }
                                                    else{
                                                        echo 'Ref: '.$case['reference_number'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?=$case['address_to']?>

                                                </td>
                                                <td><?php
                                                    echo $case['postal_type'].'&nbsp;'.$case['postal_number'].'&nbsp;'.date("d-m-Y", strtotime($case['postal_date']));
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $case['sender_name'].'&nbsp;'.$case['address'];
                                                    ?>
                                                </td>
                                                <?php
//                                                $diarynumber="";
//                                                if(!empty($case['diary_number'])){
//                                                    $diarynumber=$case['diary_number'];
//                                                    $diarynumber="Diary No. ".substr($diarynumber, 0, -4)."/".substr($diarynumber, -4)."<br/>".$case['reg_no_display'];;
//                                                }
                                                ?>

                                                <td>
                                                    <?php if($case['is_forwarded']=='f'){ echo $case['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($case['dispatched_on']));}?>
                                                </td>
                                                <td>
                                                    <?php if($case['is_forwarded']=='t'){ echo $case['dispatched_by']; echo "&nbsp;On&nbsp;"; echo date("d-m-Y h:i:s A", strtotime($case['dispatched_on']));}?>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="getImages(<?=$case['ec_postal_transactions_id']?>)"  data-target="#myModal"><i class="fa fa-image" aria-hidden="true"></i></button>
                                                </td>
                                                <td>
                                                    <?php if(!empty($case['action_taken_on']) && $case['action_taken_on']!='0000-00-00 00:00:00'){ ?>
                                                        <?=$case['action_taken_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($case['action_taken_on']))?>
                                                    <?php }
                                                    else{?>
                                                        <input type="checkbox" id="daks" name="daks[]" value="<?=$case['id']?>#<?=$case['ec_postal_transactions_id']?>#<?=$case['is_ad_card']?>#<?=$case['ec_postal_dispatch_id']?>">
                                                    <?php  }?>
                                                </td>
                                            </tr>
                                            <?php
                                            $s_no++;
                                        }
                                    }
                                        ?>
                                        </tbody>
                                    </table>
                        </div>
                                    <?php
                                }
                                else{
                                    ?>
                                    <div class="form-group col-sm-12">
                                        <label class="text-danger">&nbsp;No Record Found!!</label>
                                    </div>

                                 <?php
                                  }
                                 ?>

                            <?php form_close();?>
                            </div>

                            <br><br>
                        </div>
                        <br>
                        <br>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <script>

        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });

        window.onload = showHideFieldsDiv(0);

        function showHideFieldsDiv(id) {
            //alert("Inside 0");exit;
            if (id == '0') {
                //alert("Inside 0");
                document.getElementById("divReturnReason").style.display = "none";
                document.getElementById("divSection").style.display = "none";
                document.getElementById("divOfficer").style.display = "none";
            }

            if (id == '1') {
                document.getElementById("divReturnReason").style.display = "none";
                document.getElementById("divSection").style.display = "none";
                document.getElementById("divOfficer").style.display = "none";
            } else if (id == '2') {
                document.getElementById("divReturnReason").style.display = "";
                document.getElementById("divSection").style.display = "none";
                document.getElementById("divOfficer").style.display = "none";;
            } else if (id=='3') {
                document.getElementById("divReturnReason").style.display = "none";
                document.getElementById("divSection").style.display = "";
                document.getElementById("divOfficer").style.display = "";
            }

        }

        function setOfficersOfSection(dealingSection){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#officer').empty().append('<option value="0">Select</option>');
            if(dealingSection=="0"){
                alert("Please Select Section!");
                exit;
            }

            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/ReceiptController/getOfficersListBySection');?>',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dealingSection: dealingSection},
                success: function (result) {
                    updateCSRFToken();
                    var officersOfSectionArray = JSON.parse(result);
                    for(i=0;i<officersOfSectionArray.length;i++){
                        var usercode =  officersOfSectionArray[i]['usercode'];
                        if(usercode!=null && usercode!="") {
                            var officerDetailString = officersOfSectionArray[i]['name'] + '(' + officersOfSectionArray[i]['empid'] + '), ' + officersOfSectionArray[i]['empTypeName'];
                            $('#officer').append('<option value="'+usercode+'">'+officerDetailString+'</option>');
                        }
                    }
                }
            });


        }

        function doReceiveForSection(){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            returnReason=$('#returnReason').val().trim();
            actionType=$('#actionType').val();
            officer=$('#officer').val();
            dealingSection=$('#dealingSection').val();

            if(actionType=="0"){
                alert("Please select action type then proceed.");
                $('#actionType').focus();
                return false;
            }
            else if(actionType=="2"){
                if(returnReason==''){
                    alert("Please Enter proper return reason.");
                    $('#returnReason').focus();
                    return false;
                }
            }else if(actionType=="3"){
                if(dealingSection=="0"){
                    alert("Please Select Section");
                    document.getElementById("dealingSection").focus();
                    return false;
                }

                if(officer=="0"){
                    alert("Please Select Officer");
                    document.getElementById("officer").focus();
                    return false;
                }
                if(!confirm('Are You Sure to Auto-Receive Dak/Daks and then Forward!')){
                    return false;
                }
            }

            var selectedCases = [];
            $('#tblDispatchDak input:checked').each(function() {
                if($(this).attr('name')!='allCheck')
                    selectedCases.push($(this).attr('value'));
            });
            if(selectedCases.length<=0){
                alert("Please Select at least one dak for receive/return..");
                return false;
            }

            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/ReceiptController/doReceiveDakForSection');?>',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    selectedCases:selectedCases,
                    actionType:actionType,
                    returnReason:returnReason,
                    dealingSection:dealingSection,
                    officer:officer
                },
                success: function (result) {
                    updateCSRFToken();
                    $("#divReceiptDak").html(result);
                }
            });

        }

        function getImages(id){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if(id==''){
                return;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url:'<?=base_url('/RI/ReceiptController/getImagesForTransactionId');?>',
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        id: id
                         },
                    success: function (result) {
                        updateCSRFToken();
                        // console.log(result+">>>>>");
                        if(result=='0'){
                            alert('No Image Found');
                        }else {
                            $('#imagecontentdiv').empty().append(result);
                            $('#myModal').modal('show');
                        }
                    }
                });
            }
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