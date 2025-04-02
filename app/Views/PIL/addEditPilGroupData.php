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
                                    <h3 class="card-title">PIL(E) >> Pil Entry >> PIL Group </h3>
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

                        <?= view('PIL/pilEntryHeading'); ?>
                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

<!--                        <div class="alert alert-success alert-dismissible hidden" id="success-msg">-->
<!--                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
<!--                            <h4><i class="icon fa fa-check"></i> Alert!</h4>-->
<!---->
<!--                        </div>-->
<!---->
<!--                        <div class="alert alert-danger alert-dismissible hidden" id="error-msg">-->
<!--                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
<!--                            <h4><i class="icon fa fa-ban"></i> Alert!</h4>-->

                        

                        

                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'frmPilGroupAddEdit', 'id' => 'frmPilGroupAddEdit', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <br><br>

                        <div class="row col-md-12 ">
                            <?php
                            $groupFileNumber="";
                            if(!empty($pilGroupDetail)){
                                $pilGroupDetail=$pilGroupDetail[0];
                                $groupFileNumber=$pilGroupDetail['group_file_number'];
                                // echo "<pre>";
                                // print_r($pilGroupDetail);
                                // die;
                            }
                            ?>
                            <input class="form-control" id="pilGroupId" name="pilGroupId" type="hidden" value="<?php echo !empty($pil_group_id)?$pil_group_id:'';?>">
                            <div class="col-md-3" >
                                <h5 style="display: flex;margin-top: -8%;">Group File Number</h5>
                                <input type="text" class="form-control" id="groupFileNumber" name="groupFileNumber" placeholder="Group File Number"  value="<?php echo !empty($groupFileNumber)?$groupFileNumber:''; ?>">
                            </div> 
                            <div class="col-md-3" >
                                <button type="button" name="save" id="save" style="text-align:center;" onClick="submitPilGroupMethod()" class="btn btn-success mt-2" >Save </button>&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" name="cancel" id="cancel-btn" style="text-align:center" onClick="goBackToPilGroup()" class="btn btn-primary mt-2" >Cancel </button>
                            </div>
                           </div>
                        <br><br>
                        </div>

                        <?php
                        if(!empty($casesInPilGroup))
                        {
                        ?>
                       <div>
                         <hr>
                           <div class="box box-success">
                               <div class="box-header with-border">
                                   <h5 class="box-title" style="margin-left: 2%">PIL Action Taken</h5>
                               </div>
                               <br>

                               <div class="row">
                                   <div class="col-sm-6">
                                       <div class="form-group row">
                                           <label for="actionTaken" class="col-sm-3 col-form-label">Action Taken</label>
                                           <div class="col-sm-9">
                                               <?php
                                               $arrActionTaken = array(
                                                   "0" => "Select",
                                                   "L" => "No Action Required",
                                                   "W" => "Written Letter",
                                                   "R" => "Letter Returned to Sender",
                                                   "S"=> "Letter Sent To",
                                                   "T"=> "Letter Transferred To",
                                                   "I"=> "Letter Converted To Writ",
                                                   "O"=> "Other Remedy"
                                               );
                                               ?>
                                               <select  class="form-control" name="actionTaken" id="actionTaken" onchange="showHide1(this)">
                                                   <?php
                                                   foreach($arrActionTaken as $key => $value){
                                                       echo '<option value="' . $key . '">' . $value . '</option>';
                                                   } ?>

                                               </select>
                                           </div>
                                       </div>
                                   </div>


                                   <br>

                                   <div class="row" name="divNoAction" id="divNoAction">
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                             <label for="lodgementDate" class="col-sm-3 col-form-label">Lodgement Date</label>
                                           <div class="col-sm-9">
                                               <input type="date" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="lodgementDate" id="lodgementDate" placeholder="Lodgement Date">
                                           </div>
                                       </div>
                                     </div>
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select  class="form-control" name="lodgedActionReason" id="lodgedActionReason">
                                                       <option value="0">Select</option>
                                                       <?php
                                                       if(!empty($lodgeActionReason)) {
                                                           foreach ($lodgeActionReason as $lodgeReason) {
                                                               echo '<option value="' . $lodgeReason['id'] . '">' . $lodgeReason['pil_sub_action_code'] . ' - ' . $lodgeReason['sub_action_description'] . '</option>';
                                                           }
                                                       }
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                   </div>


                                   <br>

                                   <div class="row" name="divWrittenLetter" id="divWrittenLetter">
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="writtenOn" class="col-sm-3 col-form-label">Written On</label>
                                               <div class="col-sm-9">
                                                   <input type="date" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="writtenOn" id="writtenOn" placeholder="Written On">
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select  class="form-control" name="writtenActionReason" id="writtenActionReason">
                                                       <option value="0">Select</option>
                                                       <?php
                                                       if(!empty($writtenActionReason)) {
                                                           foreach ($writtenActionReason as $writtenReason) {
                                                               echo '<option value="' . $writtenReason['id'] . '">' . $writtenReason['pil_sub_action_code'] . ' - ' . $writtenReason['sub_action_description'] . '</option>';
                                                           }
                                                       }
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="writtenTo" class="col-sm-3 col-form-label">Written To</label>
                                               <div class="col-sm-9">
                                                   <input class="form-control" style="font-weight: bold!important;" id="writtenTo" name="writtenTo" placeholder="Written To" type="text" onblur="return convertToUpperCase(this);">
                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="writtenFor" class="col-sm-3 col-form-label">Written For</label>
                                               <div class="col-sm-9">
                                                   <input class="form-control" style="font-weight: bold!important;" id="writtenFor" name="writtenFor" placeholder="Written For" type="text" onblur="return convertToUpperCase(this);">
                                               </div>
                                           </div>
                                       </div>

                                   </div>

                                   <div class="row" name="divReturn" id="divReturn" >
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="returnDate" class="col-sm-3 col-form-label">Return Date</label>
                                               <div class="col-sm-9">
                                                   <input type="date" style="font-weight: bold!important;" class="form-control" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" name="returnDate" id="returnDate" placeholder="Return Date">
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select  class="form-control" name="returnActionReason" id="returnActionReason" >
                                                       <option value="0">Select</option>
                                                       <?php
                                                       if(!empty($returnActionReason)) {
                                                           foreach ($returnActionReason as $returnReason) {
                                                               echo '<option value="' . $returnReason['id'] . '">' . $returnReason['pil_sub_action_code'] . ' - ' . $returnReason['sub_action_description'] . '</option>';
                                                           }
                                                       }
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="returnRemark" class="col-sm-3 col-form-label">Return remarks</label>
                                               <div class="col-sm-9">
                                                   <textarea class="form-control" style="font-weight: bold!important;" name="returnRemark" id="returnRemark" rows="2" placeholder="Return Remark ..." onblur="return convertToUpperCase(this);"></textarea>
                                               </div>
                                           </div>
                                       </div>

                                   </div>

                                   <!------------ END Letter Returned to Sender ---------------->

                                   <!------------ For Sent To ---------------->

                                   <div class="row" name="divSentTo" id="divSentTo" >
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="sentTo" class="col-sm-3 col-form-label">Sent To</label>
                                               <div class="col-sm-9">
                                                   <input class="form-control" style="font-weight: bold!important;" id="sentTo" name="sentTo" placeholder="sentTo" type="text" onblur="return convertToUpperCase(this);">
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select  class="form-control" name="sentActionReason" id="sentActionReason" >
                                                       <option value="0">Select</option>
                                                       <?php
                                                       if(!empty($sentActionReason)) {
                                                           foreach ($sentActionReason as $sentReason) {
                                                               echo '<option value="' . $sentReason['id'] . '">' . $sentReason['pil_sub_action_code'] . ' - ' . $sentReason['sub_action_description'] . '</option>';
                                                           }
                                                       }
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="sentOn" class="col-sm-3 col-form-label">Sent On</label>
                                               <div class="col-sm-9">
                                                   <input type="date" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="sentOn" id="sentOn" placeholder="Sent On" ">
                                               </div>
                                           </div>
                                       </div>

                                   </div>



                                   <!------------ END Sent To ---------------->

                                   <!------------ For Transferred To ---------------->


                                   <div class="row" name="divTransferredTo" id="divTransferredTo" >
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="transferredTo" class="col-sm-3 col-form-label">Transferred To</label>
                                               <div class="col-sm-9">
                                                   <input class="form-control" style="font-weight: bold!important;" id="transferredTo" name="transferredTo" placeholder="Transferred To" type="text" onblur="return convertToUpperCase(this);">
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select  class="form-control" name="transferActionReason" id="transferActionReason" >
                                                       <option value="0">Select</option>
                                                       <?php
                                                       if(!empty($transferActionReason)) {
                                                           foreach ($transferActionReason as $transferReason) {
                                                               echo '<option value="' . $transferReason['id'] . '">' . $transferReason['pil_sub_action_code'] . ' - ' . $transferReason['sub_action_description'] . '</option>';
                                                           }
                                                       }
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="transferredOn" class="col-sm-3 col-form-label">Transferred On</label>
                                               <div class="col-sm-9">
                                                   <input type="date" style="font-weight: bold!important;" class="form-control" name="transferredOn" id="transferredOn" placeholder="Transferred On">
                                               </div>
                                           </div>
                                       </div>

                                   </div>

                                   <!------------ END Transferred To ---------------->
                                   <!------------ For Converted To ---------------->
                                   <div class="row" name="divConvertTo" id="divConvertTo" >

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="convertedDiaryNumber" class="col-sm-3 col-form-label">Converted To</label>
                                               <div class="col-sm-9">
                                                   <input class="form-control" id="convertedDiaryNumber" name="convertedDiaryNumber" placeholder="Converted Diary Number" type="number" maxlength="6">
                                               </div>
                                           </div>
                                       </div>

                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <div class="col-sm-9">
                                                   <select class="form-control" id="convertedDiaryYear" name="convertedDiaryYear" >
                                                       <?php
                                                       for($year=date('Y'); $year>=1950; $year--)
                                                           echo '<option value="'.$year.'">'.$year.'</option>';
                                                       ?>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>

                                   </div>

                                   <!------------ END Converted To ---------------->
                                   <!------------ For Other Remedy ---------------->
                                   <div class="col-sm-12" name="divOtherRemedy" id="divOtherRemedy" >
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="otherRemedyRemark" class="col-sm-3 col-form-label">Remarks</label>
                                               <div class="col-sm-9">
                                                   <textarea class="form-control" style="font-weight: bold!important;" name="otherRemedyRemark" id="otherRemedyRemark" rows="3" placeholder="Other Remedy Remark ..." onblur="return convertToUpperCase(this);"></textarea>
                                               </div>
                                           </div>
                                       </div>

                                   </div>

                                   <!------------ END Other Remedy ---------------->


                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="report" class="col-sm-3 col-form-label">Report Received</label>
                                               <div class="col-sm-9">
                                                   <label>
                                                       <input name="reportReceived" id="reportReceivedYes" value="1" type="radio">
                                                       Yes
                                                   </label>


                                                   <label>
                                                       <input name="reportReceived" id="reportReceivedNo" value="0" type="radio">
                                                       No
                                                   </label>

                                               </div>
                                           </div>
                                       </div>
                                       <div class="col-sm-6">
                                           <div class="form-group row">
                                               <label for="reportDate" class="col-sm-3 col-form-label">Report Received Date</label>
                                               <div class="col-sm-9">
                                                   <input type="date" style="font-weight:bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="reportDate" id="reportDate" placeholder="Report Date">
                                               </div>
                                           </div>
                                       </div>



                               </br>
                               </div>
                           </div>

                           <!------------- PIL Deletion ------------>
                           <div class="box box-danger">
                               <div class="box-header with-border">
                                   <h5 class="box-title" style="margin-left: 2%">PIL Deletion</h5>
                               </div>
                               <br>

                               <div class="row">
                                   <div class="col-sm-6">
                                       <div class="form-group row">
                                           <label for="select" class="col-sm-3 col-form-label">Select </label>
                                           <div class="col-sm-9">

                                               <label> <input type="radio" name="destroyOrKeepIn" id="destroy" value="Y"  onclick="todaysDate();">
                                                       Destroy</label>
                                               <?php
//                                               NO SUCH QUERY IS WRITTEN TO FETCH THE RECORD FOR DELETED
//                                               $checkedN="";$checkedEN='';
//                                               foreach($casesInPilGroup as $row)
//                                               {
////                                                   print_r($row['is_deleted']);
//                                                   if(($row['is_deleted']=='t') && ($row['destroy_on'] != null)){
//                                                       $checkedN="checked";
//                                                   }else{
//                                                       $checkedEN="checked";
//
//                                                   }
//                                               }
                                               ?>
                                                    &nbsp;&nbsp;

                                               <label><input type="radio" name="destroyOrKeepIn" id="keepin" value="N"  onclick="todaysDate();" >
                                                       Keep In Record</label>

                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-sm-6">
                                       <div class="form-group row">
                                           <label for="destroy_keepinrecord" class="col-sm-3 col-form-label">Destroy/Keep in Record Date</label>
                                           <div class="col-sm-9">
                                               <input type="date" style="font-weight: bold!important;" onKeyDown="javascript:return dFilter(event.which, this, '##-##-####');" class="form-control" name="destroyOrKeepInDate" id="destroyOrKeepInDate" placeholder="Date">
                                           </div>
                                       </div>
                                   </div>

                                   <div class="col-sm-6">
                                       <div class="form-group row">
                                           <label for="remarks" class="col-sm-3 col-form-label">Remarks</label>
                                           <div class="col-sm-9">
                                               <textarea class="form-control" style="font-weight: bold!important;" name="destroyOrKeepInRemark" id="destroyOrKeepInRemark" rows="3" placeholder="Destroy Or Keep In Record Remark ..." onblur="return convertToUpperCase(this);"></textarea>
                                           </div>
                                       </div>
                                   </div>

                               </div>
                         </div>
                               <div class="box-footer">
                                   <button type="button" class="btn btn-success col-sm-3" style="margin-left: 2%" onclick="javascript:return submitGroupUpdationMethod()">Update in Selected PILs in this PIL Group</button>
                               </div>
                           </div>

                            <br>


                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Cases in Group</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->

                                <div class="box-body">
                                    <div class="row">
                                        <table id="tblCasesForAction" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                            <thead>
                                            <tr role="row" style="background-color: gainsboro">
                                                <th><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
                                                <th>Inward No/Year</th>
                                                <th>Received From</th>
                                                <th>Received On</th>
                                                <th>Subject</th>
                                                <th>Petition Date</th>
                                                <th>Group Number</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 0;
                                            $rowserial = "odd";
                                            foreach ($casesInPilGroup as $result){
                                                $i++;
                                                if ($i % 2 == 0)
                                                    $rowserial = "even";
                                                else {
                                                    $rowserial = "odd";
                                                }
                                                ?>
                                                <tr role="row" class="<?= $rowserial ?>">

                                                    <td>
                                                        <input type="checkbox" id="pils" name="pils[]" value="<?=$result['id']?>">
                                                    </td>
                                                    <!--<td><span class="label label-primary" onclick="addEditPilDetail(<?/*=$result['id']*/?>, '<?/*=base_url()*/?>')"><?/*=$result['pil_diary_number']*/?></span></td>-->
                                                    <td><a href="<?=base_url();?>index.php/PilController/rptPilCompleteData/<?=$result['id']?>" target="_blank">
                                                            <?=$result['pil_diary_number']?>
                                                    </td>
                                                    <td><?=$result['received_from']?></td>
                                                    <td><?=$result['received_on']!=null?date("d-m-Y", strtotime($result['received_on'])):null?></td>
                                                    <td><?=$result['subject']?></td>
                                                    <td><?=$result['petition_date']!=null?date("d-m-Y", strtotime($result['petition_date'])):null?></td>
                                                    <td><?=$result['group_file_number']?></td>

                                                </tr>

                                            <?php }
                                            ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                            </div>

                         <?php
                          }
                        form_close();
                        ?>
                       </div>


                        </div>
                    </div><br><br>

                </div>

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
console.log(new Date());
    function todaysDate(){
        if($("#destroyOrKeepInDate").val()=="" || $("#destroyOrKeepInDate").val()==null){
            // $("#destroyOrKeepInDate").datepicker().datepicker("setDate", new Date());
            document.getElementById('destroyOrKeepInDate').valueAsDate = new Date();
        }
    }

    window.onload=showHide1();

    function goBackToPilGroup(){
        //alert("<?php //echo base_url('/PIL/PilController/showPilGroup/');?>//");return false;
        window.location.href = "<?php echo base_url('/PIL/PilController/showPilGroup/');?>" ;
    }

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function selectallMe() {
        var checkBoxList=$('[name="pils[]"]');

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

    function submitPilGroupMethod(){
        // alert("TTTTTTtttt");
        var groupFileNumber = document.getElementById("groupFileNumber").value;
        if(groupFileNumber.length==0 || groupFileNumber.trim()=='')
        {
            alert("Please enter Group File Number");
            document.getElementById("groupFileNumber").focus();
            return false;
        }else{
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var groupid = groupFileNumber;
            var ucode = <?php echo $_SESSION['login']['usercode'] ?>;
            var pid = $('#pilGroupId').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'gpid': groupid,
                    'pid' : pid,
                    'ucode':ucode,
                },
                dataType: 'JSON',
                url: "<?php echo base_url('PIL/PilController/savePilGroupData'); ?>",
                success: function(data) {
                    updateCSRFToken();
                    if (data == '1') {
                        alert("Success! PIL File Group information Saved Successfully.");
                       // $('#groupFileNumber').val('');

                        // if (data == '1') {
                        //     alert("Saved Successfully.");
                        //     $("#success-msg").removeClass('hidden');
                        //     $('#success-msg').html("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                        //         " <h4><i class=\"icon fa fa-check\"></i> Success!</h4>PIL File Group information Saved Successfully.");
                        // } else {
                        //     $("#error-msg").removeClass('hidden');
                        //     $('#error-msg').html(" <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\n" +
                        //         "<h4><i class=\"icon fa fa-ban\"></i> Alert!</h4>There is some problem while saving data,Please Contact Computer Cell.");
                        // }


                    } else {
                        alert("There is some problem while saving data,Please Contact Computer Cell.");
                    }
                    
                },
                error: function(data) {
                    updateCSRFToken();
                    alert(data);
                    
                }
            });

        }

    }


    function submitGroupUpdationMethod(basePath)    {
        var groupFileNumber = document.getElementById("groupFileNumber").value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if(groupFileNumber.length==0 || groupFileNumber.trim()=='')
        {
            alert("Please enter Group File Number");
            return false;
        }
        var selectedCases = [];
        $('#tblCasesForAction input:checked').each(function() {
            if($(this).attr('name')!='allCheck')
                selectedCases.push($(this).attr('value'));
        });
        if(selectedCases.length<=0){
            alert("Please Select at least one case for Action..");
            return false;
        }
        $.ajax({
            type: 'POST',
            url: " <?php echo base_url('/PIL/PilController/groupUpdate'); ?>",
            data: $("#frmPilGroupAddEdit").serialize(),

            success: function (result) {
                updateCSRFToken();
                // alert("QQQQ"+result);return false;
                if (result == '1') {
                   alert("All PILs in this Group Updated Successfully.");
                } else {
                   alert("Alert! There is some problem while saving data,Please Contact Computer Cell.");
                }
            },
        error: function(data) {
            updateCSRFToken();
            alert(data);
            
        }
        });

    }

    function showgroup() {
        var str=document.getElementById("pilCategory").value;
        if(str==18){
            document.getElementById("otherGroup").style.display = "";
            document.getElementById("otherTextlabel").style.display = "";
        }
        else{
            document.getElementById("otherGroup").style.display = "none";
            document.getElementById("otherTextlabel").style.display = "none";
        }
    }


    function showHide1(id)
    {
        // alert("DDD");
        var queryFields=document.getElementById("actionTaken").value;
        //alert('Selected Action: '+queryFields);
        if(queryFields=='0'){
            //alert("Inside 0");
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }

        if(queryFields=='L'){
            document.getElementById("divNoAction").style.display = "";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }
        else if(queryFields=='W'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }else if(queryFields=='R'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }else if(queryFields=='S'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }else if(queryFields=='T'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="none";
        }
        else if(queryFields=='I'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="";
            document.getElementById("divOtherRemedy").style.display="none";
        }
        else if(queryFields=='O'){
            document.getElementById("divNoAction").style.display = "none";
            document.getElementById("divWrittenLetter").style.display="none";
            document.getElementById("divReturn").style.display="none";
            document.getElementById("divSentTo").style.display="none";
            document.getElementById("divTransferredTo").style.display="none";
            document.getElementById("divConvertTo").style.display="none";
            document.getElementById("divOtherRemedy").style.display="";
        }
        showgroup();
    }
    $(function () {
        $("#receivedOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#orderDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#petitionDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#lodgementDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#writtenOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#returnDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#sentOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#transferredOn").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#registrationDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#reportDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
        $("#destroyOrKeepInDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose:true
        });
    });


</script>
