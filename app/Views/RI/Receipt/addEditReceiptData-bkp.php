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
                                    <h3 class="card-title">R & I >> Receipt >> Add/Update </h3>
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

<!--                        --><?//= view('RI/RIReceiptHeading'); ?>


                      <!--  <div class="container-fluid">
                            <h3 class="page-header" style="margin-left: 1%">Add/Update:</h3>
                            <br>-->


                            <?php
                            $attribute = array('class' => 'form-horizontal', 'name' => 'frmRIAddEdit', 'id' => 'frmRIAddEdit', 'autocomplete' => 'off', 'method' => 'POST');
                            echo form_open(base_url('#'), $attribute);
                            ?>

                            <br>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo $_SESSION['login']['usercode']; ?>"/>
                            <?php
//                            $this->load->library('Common');
                            $diaryNumber=null;$diaryYear=null;$postalNo="";$postalDate=null;$letterNo=null;$letterDate=null;
                            $senderName="";$address="";$stateCode=0;
                            $subject="";$caseDiaryNo=null;$caseDiaryYear=null;
                            $receiptModeId=null;$isOpenable=null;$isOriginalRecord=NULL;
                            $dealingSectionId=null;$postalAddress="";$officerId=null;
                            $pilDiaryNumber="";$judgeCode=null;$remarks="";$actionTaken=0;
                            $dispatchToUserType="";

                            if(!empty($ecReceiptCompleteData)){
//                                var_dump($ecReceiptCompleteData);
//                                die;
                                $receiptCompleteData=$ecReceiptCompleteData[0];
                                $postalNo=$receiptCompleteData['postal_no'];
                                $diaryNumber=$receiptCompleteData['diary_no'];
                                $diaryYear=$receiptCompleteData['diary_year'];
                                if(!empty($receiptCompleteData['postal_date']))
                                $postalDate=date("d-m-Y", strtotime($receiptCompleteData['postal_date']));
                                else  $postalDate='';
                                $letterNo=$receiptCompleteData['letter_no'];
                                if(!empty($receiptCompleteData['letter_date']))
                                $letterDate=date("d-m-Y", strtotime($receiptCompleteData['letter_date']));
                                else  $letterDate='';
                                $senderName=$receiptCompleteData['sender_name'];
                                $address=$receiptCompleteData['address'];
                                $stateCode=$receiptCompleteData['ref_state_id'];
                                $subject=$receiptCompleteData['subject'];
                                $ecCaseId=$receiptCompleteData['ec_case_id'];
                                if(!empty($ecCaseId)) {
                                    $caseDiaryNo = substr($ecCaseId, 0, -4);
                                    $caseDiaryYear = substr($ecCaseId, -4);
                                }else{
                                    $caseDiaryNo = '';
                                    $caseDiaryYear = '';
                                }
                                $receiptModeId=$receiptCompleteData['ref_postal_type_id'];
                                $isOpenable=$receiptCompleteData['is_openable'];
                                $isOriginalRecord=$receiptCompleteData['is_original_record'];
                                //$dealingSectionId=$receiptCompleteData['dealing_section'];
                                $postalAddressee=$receiptCompleteData['postal_addressee'];
                                //$officerId=$receiptCompleteData['officer_id'];
                                $pilDiaryNumber=$receiptCompleteData['pil_diary_number'];
                                //$judgeCode=$receiptCompleteData['org_judge_id'];
                                $remarks=$receiptCompleteData['remarks'];
                                $actionTaken=$receiptCompleteData['action_taken'];
                                $dispatchToUserType=$receiptCompleteData['dispatched_to_user_type'];

                                echo ">>>>>>>".$dispatchToUserType.">>>".$receiptCompleteData['dispatched_to'];
                                $dispatchTo=$receiptCompleteData['dispatched_to'];

                                if($dispatchToUserType=='s'){
                                    $dealingSectionId=$receiptCompleteData['dispatched_to'];
                                }
                                elseif ($dispatchToUserType=='o'){
                                    $officerId=$receiptCompleteData['dispatched_to'];
                                }
                                elseif ($dispatchToUserType=='j'){
                                    $judgeCode=$receiptCompleteData['dispatched_to'];
                                }
                            }
                            ?>

                            <input class="form-control" id="receiptid" name="receiptid" type="hidden" value="<?php if(!empty($receiptId))print_r($receiptId); ?>">
                            <div class="box-body1">
                                <?php if($receiptId!=0){?>
                                    <div class="form-group row">
                                        <input type="hidden" name="diarynumber" id="diarynumber" value="<?=$diaryNumber?>">
                                        <input type="hidden" name="diaryyear" id="diaryyear" value="<?=$diaryYear?>">
                                        <label for="diary" class="col-sm-3 control-label" ><h5>Diary Number : <?=$diaryNumber?>/<?=$diaryYear?></h5></label>
                                    </div>
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="postalNo" class="control-label" style="margin-left:1%"><h5>Postal No.</h5></label>

                                    <div class="col-sm-4">
                                        <input class="form-control" id="postalNo" name="postalNo" placeholder="Postal Number" style="margin-left: 6%;" type="text" value="<?=$postalNo?>">
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <label for="postalDate" class="control-label" style="margin-left:11%"><h5>Postal Date</h5></label>
                                    <div class="col-sm-4">
                                      <input type="date" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" style="margin-left: 6%;" class="form-control" name="postalDate" id="postalDate" placeholder="Postal Date" value="<?=($postalDate!='')?$postalDate:''?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="letterNo" class="control-label" style="margin-left:1%"><h5>Letter No.</h5></label>

                                    <div class="col-sm-4">
                                        <input class="form-control" id="letterNo" name="letterNo" placeholder="Letter No." style="margin-left: 6%;" type="text" value="<?=$letterNo?>">
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <label for="letterDate" class="control-label" style="margin-left:11%"><h5>Letter Date</h5></label>
                                    <div class="col-sm-4">
                                    <input type="date" style="margin-left:7%" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="letterDate" id="letterDate" placeholder="Letter Date" value="<?=($letterDate!='')?$letterDate:''?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="senderName" class="control-label" style="margin-left:1%"><h5>Sender Name</h5></label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="senderName" name="senderName" placeholder="Sender Name" type="text" value="<?=$senderName?>">
                                    </div>

                                    <label for="senderAddress" class="control-label" style="margin-left:9.5%"><h5>Sender Address</h5></label>
                                    <div class="col-sm-4">
                                        <textarea class="form-control"  name="senderAddress" id="senderAddress" rows="2" placeholder="Address ..."><?=$address?></textarea>
                                    </div>

                                </div>
                                <div class="form-group row">

                                    <label for="state" class="control-label" style="margin-left:1%"><h5>State</h5></label>

                                    <div class="col-sm-4">
                                        <select  class="form-control" name="state" id="state" style="margin-left: 14%;">
                                            <option value="0">Select</option>
                                            <?php
                                            //var_dump($state);
                                            foreach($state as $st){
                                                if($stateCode==$st['state_code'])
                                                    echo '<option value="' . $st['state_code'] . '" selected="selected">' . $st['state_name'] . '</option>';
                                                else
                                                    echo '<option value="' . $st['state_code'] . '">' . $st['state_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>  &nbsp;&nbsp;&nbsp;
                                    <label for="subject" class="control-label" style="margin-left:14%"><h5>Subject</h5></label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="subject" name="subject" placeholder="Subject" type="text" value="<?=$subject?>" style="margin-left:12%;">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="caseType" class="control-label" style="margin-left:1%"><h5>Case Number</h5></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="caseType" name="caseType" onchange="javascript:ifValidCase('<?=base_url()?>')">
                                            <option value="0">Select</option>
                                            <?php
                                            foreach($caseTypes as $caseType){
                                                echo '<option value="' . $caseType['casecode'] . '">' . $caseType['short_description'] . '</option>';
                                            }
                                            ?>

                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <input class="form-control" id="caseNumber" name="caseNumber" placeholder="caseNumber" onblur="javascript:ifValidCase('<?=base_url()?>')" type="text" value="">
                                    </div>
                                    <div class="col-sm-1">
                                        <select class="form-control" id="caseYear" name="caseYear" onchange="javascript:ifValidCase('<?=base_url()?>')">
                                            <option value="0">Select</option>
                                            <?php
                                            for($year=date('Y'); $year>=1950; $year--)
                                                echo '<option value="'.$year.'">'.$year.'</option>';
                                            ?>
                                        </select>
                                    </div>&nbsp;&nbsp;&nbsp;
                                    <label for="state" class="control-label" style="margin-left:10%"><h5>Diary No</h5></label>
                                    <div class="col-sm-3">
                                        <input class="form-control"  style="margin-left: 12%;" id="caseDiaryNo" name="caseDiaryNo" placeholder="Case Diary No" type="text" onblur="javascript:ifValidCase('<?=base_url()?>')" value="<?=$caseDiaryNo?>">
                                    </div> &nbsp;
                                     &nbsp;
                                    <div class="col-sm-1">
                                        <select class="form-control" id="caseDiaryYear" name="caseDiaryYear" onchange="javascript:ifValidCase('<?=base_url()?>')" style="margin-left:29%;">
                                            <option value="0">Select</option>
                                            <?php
                                            for($year=date('Y'); $year>=1950; $year--){
                                                if($year==$caseDiaryYear)
                                                    echo '<option value="'.$year.'" selected="selected">'.$year.'</option>';
                                                else
                                                    echo '<option value="'.$year.'">'.$year.'</option>';
                                            }
                                            ?>
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12" id="divCaseDetails">

                                </div>



                                <div class="form-group row">
                                    <label for="receiptMode" class="control-label" style="margin-left:1%"><h5>Parcel Receipt Mode
                                            <span style="color: red">*</span></h5></label>
                                    <div class="col-sm-4">
                                        <select  class="form-control" name="receiptMode" id="receiptMode">
                                            <option value="0">Select</option>
                                            <?php
                                            //var_dump($state);
                                            foreach($receiptModes as $receipt){
                                                if($receiptModeId==$receipt['id'])
                                                    echo '<option value="' . $receipt['id'] . '" selected="selected">' . $receipt['postal_type_description'] . '</option>';
                                                else
                                                    echo '<option value="' . $receipt['id'] . '">' . $receipt['postal_type_description'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <label for="isOpenable" class="col-sm-2 control-label" style="margin-left:6%"><h5>Is Doc/parcel Openable</h5></label>

                                    <div class="col-sm-3 row">
                                        <div class="radio col-sm-3">
                                            <label><h5>

                                                <?php
                                                //echo "reportReceived value ".$reportReceived;
                                                $checkedY="";
                                                if($isOpenable=='t'){
                                                    $checkedY="checked";
                                                }?>
                                                <input name="isOpenable" id="isOpenableYes" value="t" type="radio" <?=$checkedY?>>
                                                Yes </h5></label>
                                        </div>
                                        <div class="radio col-sm-3">
                                            <label><h5>
                                                <?php
                                                $checkedN="";
                                                if($isOpenable=='f'){
                                                    $checkedN="checked";
                                                }?>
                                                <input name="isOpenable" id="isOpenableNo" value="f" type="radio" <?=$checkedN?>>
                                                No </h5></label>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">

                                    <label for="isOriginal" class="control-label" style="margin-left:1%"><h5>Is Original Record</h5></label>
                                    <div class="col-sm-4 row">
                                        <div class="radio col-sm-3">
                                            <label>

                                                <?php
                                                //echo "reportReceived value ".$reportReceived;
                                                $checkedY="";
                                                if($isOriginalRecord=='t'){
                                                    $checkedY="checked";
                                                }?>
                                                <input name="isOriginal" id="isOriginalYes" value="t" type="radio" <?=$checkedY?>>
                                                <h5>Yes</h5> </label>
                                        </div>
                                        <div class="radio col-sm-3">
                                            <label>
                                                <?php
                                                $checkedN="";
                                                if($isOriginalRecord=='f'){
                                                    $checkedN="checked";
                                                }?>
                                                <input name="isOriginal" id="isOriginalNo" value="f" type="radio" <?=$checkedN?>>
                                                <h5>No</h5></label>
                                        </div>
                                    </div>
                                    <label for="pilDiaryNumber" class="control-label" style="margin-left:7%"><h5>PIL Diary No (if Related to PIL)</h5></label>
                                    <div class="col-sm-3">
                                        <input class="form-control" id="pilDiaryNumber" name="pilDiaryNumber" placeholder="Pil Diary Number" type="text" value="<?=$pilDiaryNumber?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sentToUserType" class="control-label" style="margin-left:1%"><h5>Letter For (By Name)<span style="color: red">*</span></h5></label>
                                    <div class="col-sm-4">
                                        <?php
                                        $options = array(""=>"Select", "s"=>"Section", "o"=>"Officer", "j"=>"Hon'ble Judge");
                                        ?>
                                        <select  class="form-control" name="sentToUserType" id="sentToUserType" onchange="showHideSentToUserType(this.value)" >
                                            <?php
                                            foreach($options as $index=>$option){
                                                if($dispatchToUserType==$index)
                                                    echo "<option value=\'".$index."\' selected='selected'>".$option."</option>";
                                                else
                                                    echo "<option value='".$index."'>".$option."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                  <div class="col-sm-4">
                                    <div id="divOther" style="display: none">
                                        <label for="postalAddressee" class="control-label"><h5>Postal Addressee</h5></label>
                                        <div class="col-sm-4">
                                            <input class="form-control" id="postalAddressee" name="postalAddressee" placeholder="Pil Diary Number" type="text" value="<?=!empty($postalAddressee)?$postalAddressee:null?>">
                                        </div>
                                    </div>

                                    <div id="divJudge" style="display: none">
                                        <label for="judge" class="control-label"><h5>By Name(Hon'ble Judge)
                                                <span style="color: red">*</span></h5></label>
                                        <div class="col-sm-4">
                                            <select  class="form-control" name="judge" id="judge">
                                                <option value="0">Select</option>
                                                <?php
                                                foreach($judges as $judge){
                                                    if($judgeCode==$judge['jcode'])
                                                        echo "<option value='".$judge['jcode']."'selected='selected'>".$judge['jname']."</option>";
                                                    else
                                                        echo "<option value='".$judge['jcode']."'>".$judge['jname']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="divOfficer" style="display: none">
                                        <label for="officer" class="control-label"><h5>By Name(Officer)
                                                <span style="color: red">*</span></h5></label>
                                        <div class="col-sm-4">
                                            <select  class="form-control" name="officer" id="officer">
                                                <option value="0">Select</option>
                                                <?php
                                                foreach($officers as $officer){
                                                    if($officerId==$officer['usercode'])
                                                        echo '<option value="' . $officer['usercode'] . '" selected="selected">' . $officer['name'] .' ('.$officer['empid'].'), '.$officer['type_name']. '</option>';
                                                    else
                                                        echo '<option value="' . $officer['usercode'] . '">' . $officer['name'] .' ('.$officer['empid'].'), '.$officer['type_name']. '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                      

                                      <div id="divSection" class="col-sm-4" style="display: none">
                                        <label for="dealingSection" class="control-label"><h5>Dealing Section
                                                <span style="color: red">*</span></h5></label>

                                            <select  class="form-control" name="dealingSection" id="dealingSection">
                                                <option value="0">Select</option>
                                                <?php
                                                foreach($dealingSections as $dealingSection){
                                                    if($dealingSectionId==$dealingSection['id'])
                                                        echo '<option value="' . $dealingSection['id'] . '" selected="selected">' . $dealingSection['section_name'] . '</option>';
                                                    else
                                                        echo '<option value="' . $dealingSection['id'] . '">' . $dealingSection['section_name'] . '</option>';
                                                }
                                                ?>
                                            </select>

                                    </div>
                                  </div>

                                    <!--here-->
                                </div>

                                <div class="form-group row">

                                    <label for="remarks" class="control-label" style="margin-left:1%"><h5>Remarks</h5></label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="remarks" name="remarks" placeholder="Remarks" type="text" value="<?=!empty($remarks)?$remarks:null ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="box-footer" style="margin-left: 33%">


                                <?php
                                //echo $dispatchToUserType,'||'.$dispatchTo.'||'.$actionTaken;
                                if(($dispatchToUserType=='s' && $dispatchTo==68 && $actionTaken==1) or (!($dispatchToUserType=='s' && $dispatchTo==68) && $actionTaken==NULL)){
                                    echo "<button type='submit' id='btnSave' class='btn btn-success col-sm-2'>SAVE</button>";?>

                                <?php }
                                else{
                                    //echo '<span style="color: red">You can\'t modify as dak is already received by By Concern officer/Section On '.date("d-m-Y h:i:s A", strtotime($receiptCompleteData['action_taken_on'])).'</span>';
                                    echo '<span style="color: red">You can\'t modify as dak is already received by By Concern officer/Section or Returned to R&I</span>';
                                }
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-danger pull-right col-sm-2" onclick="goBack('<?=base_url()?>');">Cancel</button>
                            </div>


                            <?php form_close(); ?>


                            <br><br>
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

        function saveRIData(){
            // alert("DDDDdddd");
            var userid = <?=session()->get('login')['usercode'];?>;
            var receiptMode=document.getElementById("receiptMode").value;
            var caseType=document.getElementById("caseType").value;
            var sentToUserType=$("#sentToUserType").val();
            var isOpenable = $("input[name='isOpenable']:checked").val();
            var isOriginal = $("input[name='isOriginal']:checked").val();
            if(receiptMode==0){
                alert("Please select Parcel Receipt mode.");
                return false;
            }
            if(caseType!="0"){
                if($("#caseNumber").val().trim()==""){
                    alert("Please enter case number");
                    $("#caseNumber").focus();
                    return false;
                }
            }
            if(!sentToUserType){
                alert("Please select Letter For..");
                $("#sentToUserType").focus();
                return false;
            }
            else if(sentToUserType=='s' && $("#dealingSection").val()==0){
                alert("Please select Section Name..");
                $("#dealingSection").focus();
                return false;
            }
            else if(sentToUserType=='o' && $("#officer").val()==0){
                alert("Please select Officer Name..");
                $("#officer").focus();
                return false;
            }
            else if(sentToUserType=='j' && $("#judge").val()==0){
                alert("Please select Hon'ble Judge Name..");
                $("#judge").focus();
                return false;
            }
            /*if(!isOpenable){
                alert("Please select openable or not.");
                return false;
            }
            if(!isOriginal){
                alert("Please select parcel is original recoed or not.");
                return false;
            }*/

            $('#btnSave').val('Please wait ...').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/ReceiptController/saveReceiptData');?>',
                data: $("#frmRIAddEdit").serialize(),
                success: function (result) {
                    if (result == 'Success') {
                        alert("Saved Successfully");
                        goBack(basePath,userid);
                    }
                    else if (result != 'Error') {
                        alert("Saved Successfully as Diary Number "+result);
                        addEditReceiptDetail(0, basePath);
                    } else {
                        alert("There is some problem.");
                    }
                }
            });
            return false;
        }


        showHideSentToUserType('<?=$dispatchToUserType;?>');
        function showHideSentToUserType(id)
        {
            // alert("Hello "+id);
            if(id==''){
                //alert("Inside 0");
                document.getElementById("divOther").style.display = "none";
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            else if(id=='ot'){
                document.getElementById("divOther").style.display = "block";
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            else if(id=='j'){
                document.getElementById("divOther").style.display = "none";
                document.getElementById("divJudge").style.display = "block";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="none";
            }
            else if(id=='o'){
                document.getElementById("divOther").style.display = "none";
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="block";
                document.getElementById("divSection").style.display="none";
            }else if(id=='s'){
                document.getElementById("divOther").style.display = "none";
                document.getElementById("divJudge").style.display = "none";
                document.getElementById("divOfficer").style.display="none";
                document.getElementById("divSection").style.display="block";
            }
        }


        function goBack(basePath,userid)
        {
            //window.location.href = "../" ;
            //window.history.back();
            window.location.href = basePath+"/RI/ReceiptController/index/" ;

        }






    </script>

   <script>




       function saveRIData(basePath,userid){
           var receiptMode=document.getElementById("receiptMode").value;
           var caseType=document.getElementById("caseType").value;
           var sentToUserType=$("#sentToUserType").val();
           var isOpenable = $("input[name='isOpenable']:checked").val();
           var isOriginal = $("input[name='isOriginal']:checked").val();
           if(receiptMode==0){
               alert("Please select Parcel Receipt mode.");
               return false;
           }
           if(caseType!="0"){
               if($("#caseNumber").val().trim()==""){
                   alert("Please enter case number");
                   $("#caseNumber").focus();
                   return false;
               }
           }
           if(!sentToUserType){
               alert("Please select Letter For..");
               $("#sentToUserType").focus();
               return false;
           }
           else if(sentToUserType=='s' && $("#dealingSection").val()==0){
               alert("Please select Section Name..");
               $("#dealingSection").focus();
               return false;
           }
           else if(sentToUserType=='o' && $("#officer").val()==0){
               alert("Please select Officer Name..");
               $("#officer").focus();
               return false;
           }
           else if(sentToUserType=='j' && $("#judge").val()==0){
               alert("Please select Hon'ble Judge Name..");
               $("#judge").focus();
               return false;
           }
           /*if(!isOpenable){
               alert("Please select openable or not.");
               return false;
           }
           if(!isOriginal){
               alert("Please select parcel is original recoed or not.");
               return false;
           }*/

           $('#btnSave').val('Please wait ...').attr('disabled','disabled');
           $.ajax({
               type: 'POST',
               url: basePath+'index.php/RIController/saveReceiptData',
               data: $("#frmRIAddEdit").serialize(),
               success: function (result) {
                   if (result == 'Success') {
                       alert("Saved Successfully");
                       goBack(basePath,userid);
                   }
                   else if (result != 'Error') {
                       alert("Saved Successfully as Diary Number "+result);
                       addEditReceiptDetail(0, basePath);
                   } else {
                       alert("There is some problem.");
                   }
               }
           });
           return false;
       }

       //var id = <?//=$receiptCompleteData['dispatched_to_user_type']; ?>//;
       //showHideSentToUserType(id);
       // $("select#sentToUserType").change();
       showHideSentToUserType('<?=$dispatchToUserType;?>');
       function showHideSentToUserType(id)
       {
            alert("Hello "+id);
           if(id==''){
               //alert("Inside 0");
               document.getElementById("divOther").style.display = "none";
               document.getElementById("divJudge").style.display = "none";
               document.getElementById("divOfficer").style.display="none";
               document.getElementById("divSection").style.display="none";
           }
           else if(id=='ot'){
               document.getElementById("divOther").style.display = "block";
               document.getElementById("divJudge").style.display = "none";
               document.getElementById("divOfficer").style.display="none";
               document.getElementById("divSection").style.display="none";
           }
           else if(id=='j'){
               document.getElementById("divOther").style.display = "none";
               document.getElementById("divJudge").style.display = "block";
               document.getElementById("divOfficer").style.display="none";
               document.getElementById("divSection").style.display="none";
           }
           else if(id=='o'){
               document.getElementById("divOther").style.display = "none";
               document.getElementById("divJudge").style.display = "none";
               document.getElementById("divOfficer").style.display="block";
               document.getElementById("divSection").style.display="none";
           }else if(id=='s'){
               document.getElementById("divOther").style.display = "none";
               document.getElementById("divJudge").style.display = "none";
               document.getElementById("divOfficer").style.display="none";
               document.getElementById("divSection").style.display="block";
           }
       }


       function goBack(basePath,userid)
       {
           //window.location.href = "../" ;
           //window.history.back();
           window.location.href = basePath+"/RI/ReceiptController/index/" ;

       }






   </script>