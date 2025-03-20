<?=view('header'); ?>
 
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?=view('Filing/filing_breadcrumb'); ?>
 

                    <div class="row">

                        <div class="col-md-12"  id="ref_table">
                            <div class="card">

                                <?php  //echo $_SESSION["captcha"];
                                $attribute = array('class' => 'form-horizontal','name' => 'refiling', 'id' => 'refiling', 'autocomplete' => 'off');
                                echo form_open(base_url('#'), $attribute);
                                ?>
 
                                <div id="divprint">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <h4 class="basic_heading" style="text-align: center"> Refiling Limitation Report </h4>
                                        
                                        <?php

                                        $caseStatus = session()->get('filing_details')['c_status'];
                                            if($caseStatus == "P")
                                            {
                                            ?>
                                                <div class="col-sm-12">
                                                        <!--   <a href="--><?//=base_url('Filing/Refiling/obj_back_date'); ?><!--"><button type="button" class="btn btn-outline-primary" name="rfg_bk_dt" id="rfg_bk_dt" >Refiling on back date</button></a>&nbsp;&nbsp;-->
                
                                                        <!--  <a href="--><?//= base_url('Filing/Refiling/obj_back_date1'); ?><!--"><button type="button" class="btn btn-outline-primary" name="cancel_rfg" id="cancel_rfg" >Cancel Refiling</button></a>-->
                                                        <!--  <button type="button" class="btn btn-primary" name="rfg_bk_dt" id="rfg_bk_dt" onclick="refilingBackDt()" >Refiling on back date</button>&nbsp;&nbsp;

                                                        <button type="button" class="btn btn-primary" name="cancel_rfg" id="cancel_rfg" onclick="cancelRefiling()">Cancel Refiling</button> -->


                                                </div>
                                            <?php
                                            }
                                        ?>
                                    </div>



                                    <div class="card-body">
                                        <div class="tab-content">


                                            <?php

                                            $filing_details = session()->get('filing_details');
                                            $user_details = session()->get('login');

 
                                            if(!empty($refiling_report['refiling_report'])) 
                                            {
 
                                                    $check = $refiling_report['refiling_report']['flag'];
        
                                                    $dno = substr($refiling_report['refiling_report']['diaryNo'], 0,-4);
                                                    $dyr = substr($refiling_report['refiling_report']['diaryNo'],-4);
        
                                                    ?>


                                                    <div style="text-align: center">
                                                        <span class="text-blue">Report Generated On <?= $refiling_report['refiling_report']['currentDate']; ?></span>
                                                    </div>
                                                    <br>
                                                    <table id="refiling_table" class="table table-bordered view-delts-table">
                                                        <tbody>
                                                            <tr id="def_notify">
                                                                <th  style="width:30%">Diary No.</th><td style="width:50%"><?= $dno."/".$dyr ; ?></td>
                                                            </tr>
                                                            <tr id="def_notify">
                                                                <th  style="width:30%">Cause Title</th><td style="width:50%"><?= $refiling_report['refiling_report']['causeTitle'] ; ?></td>
                                                            </tr>
                                                            <tr id="def_notify">
                                                                <th style="width:30%">Filing Date</th><td style="width:50%"><?= $refiling_report['refiling_report']['filingNo'] ; ?></td>
                                                            </tr>

                                                            <tr id="def_notify">
                                                                <th  style="width:30%">Defects Notified On</th><td style="width:50%"><?= $refiling_report['refiling_report']['defectNotifyDate'] ; ?></td>
                                                            </tr>
                                                            <tr id="ref_date">
                                                                <th style="width:30%">Refiling Date</th> <td style="width:50%"><?= $refiling_report['refiling_report']['refilingDate'] ; ?></td>
                                                            </tr>
                                                            <tr id="last_refile">
                                                                <th  style="width:30%">Last day of Refiling</th><td style="width:50%"><?= $refiling_report['refiling_report']['lastDayRefiling']; ?></td>
                                                            </tr>

                                                            <?php
                                                            if(($check == 1) || ($check == 2)){

                                                            if($check == 1)
                                                            {

                                                            if(!empty($refiling_report['refiling_report']['preCovid']))
                                                            {
                                                            ?>
                                                            <tr id="pre_covid" >
                                                                <th  style="width:30%;">Delay till 06-03-2020 * pre-covid <b>(a)</b></th><td style="width:50%;color:red;"><?php if(!empty($refiling_report['refiling_report']['preCovid'])) echo $refiling_report['refiling_report']['preCovid']; ?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                                }
                                                            ?>
                                                            <tr id="dead_period" >
                                                                <th style="width:30%;">Dead(corona) Period <b>(b)</b></th><td style="width:50%;color:red;"><?php if(!empty($refiling_report['refiling_report']['deadCoronaPeriod'])) echo $refiling_report['refiling_report']['deadCoronaPeriod']; ?></td>
                                                            </tr>
                                                            <tr id="delay_days" >
                                                                <th style="width:30%;">Delay Days Calculated <b>(c)</b></th><td style="width:50%;"><?php if(!empty($refiling_report['refiling_report']['delayDaysCal'])) echo $refiling_report['refiling_report']['delayDaysCal']; ?></td>
                                                            </tr>
                                                            <tr id="total_delay" >
                                                                <th  style="width:30%;">total Delay <b> [(a) + (b) + (c)] </b></th><td style="width:50%;"><?php if(!empty($refiling_report['refiling_report']['totalDelay'])) echo $refiling_report['refiling_report']['totalDelay']; ?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <tr id="delay_refile">
                                                                <th  style="width:30%">Delay in Refiling</th><td style="width:50%;color:red;"><?= $refiling_report['refiling_report']['delayInRefiling']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php
                                            }

                                            ?>
                                        </div>  
                                    </div>
                                    <br><br>
                                    <table>
                                           <div align="left">( Dealing Assistant) </div><div align="left"><?= $refiling_report['refiling_report']['currentDate']; ?></div><br><Br> <div align="right">(Branch Officer)</div>
                                    </table>
                                </div>

                                   
                                    <div align="center">
                                        <button type="button" class="btn btn-primary" name="hd_print" id="hd_print"  onclick="print_data()">Print Refiling Limitation Report</button>
                                    </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
                            <br>
                            <?php
                            
                            if(!empty($table_message)) {
                                if ($table_message['table_message'] != '') {
                                    echo "<span style='color:red;display:block;text-align:center'>" . $table_message['table_message'] . "</span>";
 
                                }
                            }

                            if(!empty($table_data['table_data']))
                            {
 
                                ?>

                                <div>

                                    <table id="defect_list" class="table table-bordered table-striped" >
                                        <tbody>
                                        <br>
                                        <?php
 

                                        if($remove_defect_option['remove_defect']== '0')
                                        {
                                         ?>
                                        <input type="button" value="Remove All" onclick="removeAll()" style="margin-left:94%" name="RemoveAll" id="RemoveAll"/>
                                        <?php
                                            }

                                         ?>
                                     
                                        <th width="2%">SNo</th>
                                        <th width="70%">Default</th>
                                        <th width="20%">Default Notified By</th>
                                        <?php
 

                                             if($remove_defect_option['remove_defect']== '0')
                                             {

                                                 $check=1;
                                            ?>
                                            <th>Select <input type='checkbox'  name='all' id='all' onchange="CheckedAll()"/> </th>
                                            <th>Remove</th>
                                            <th>Action</th>
                                            <?php
                                            }

                                        ?>
                                        <?php
                                        $i=1;
                                        $s=1;
                                        foreach($table_data['table_data'] as $row) { ?>
                                            <tr id="record<?=$row['id']?>">
                                                <td><?= '<b>'.$i++.'</b>' ?></td>
                                                <td><?= '<b>'.$row['obj_name'].'('.$row['remark'].' '.$row['mul_ent'].')</b>';?></td>
                                                <td><?= '<b>'.$row['name'].'</b>'?></td>

                                                <?php
 
                                                   if($check == 1)
                                                   { ?>
                                                    <td><input type='checkbox'  name='defectname' id="defect<?=$s++?>" value='<?=$row['id']?>' /></td>
                                                    <td><input type='text'  name='remove' id='remove_done<?=$row['id']?>' value="" style="display: none" /></td>
                                                    <td><input type='button' class="Removebtn" name='remove' id='remove_done_btn<?=$row['id']?>' onclick="removeBtn()" value="Remove" style="display: block" /></td>


                                                <?php   } ?>

                                            </tr>

                                        <?php }
                                        }?>
                                        <tr>
                                            <?php
                                            if($check == 1)
                                            {
                                            ?>
                                            <td colspan="8">
                                                <div style="text-align: center">
                                                    <span style="color: red;">Please click SMS button if defect(s) still present after refiling.</span>
                                                    <input type="button" name="btn_sms" id="btn_sms" onclick="btnSmsClicked()" value="SMS"/>
                                                    <div id="show_sms_status" style="text-align: center"></div>
                                                </div>
                                            </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>

                                        </tbody>

                                    </table>


                                </div>
                                <!-- /.card -->
                                <?php
//                            }

                            ?>

                        </div>
                        <?php form_close();?>
                    </div>

                    <div class="col-sm-12" id="sp_sms_status" style="text-align: center"> </div>
                    <br>
                    <div class="col-sm-12" id="ref_bk_dt"> </div>
                    <br>
                    <div class="col-sm-12" id="cancel_ref_mess"> </div>
                    <div class="col-sm-12" id="cancel_ref"> </div>

                <br><br><br><br>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
 

   function print_data()
   {
       var prtContent = document.getElementById('divprint');
       var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
       WinPrint.document.write(prtContent.outerHTML);
       WinPrint.document.close();
       WinPrint.focus();
       WinPrint.print();
       WinPrint.close();
   }

    function CheckedAll(){
        if (document.getElementById('all').checked) {
            for(i=0; i<document.getElementsByTagName('input').length;i++){
                document.getElementsByTagName('input')[i].checked = true;
            }
        }
        else {
            for(i=0; i<document.getElementsByTagName('input').length;i++){
                document.getElementsByTagName('input')[i].checked = false;
            }
        }
    }

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function removeAll()
    {
        updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var checkboxes = document.querySelectorAll("input:checked");
        if(checkboxes.length == 0)
        {
            alert("Please Check atleast one Default");

        }else{
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            var checkedValuesId = [];
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked)
                {
                    if(checkbox.value != 'on')
                    {
                        checkedValuesId.push(checkbox.value);
                    }

                }
            });
            var data = checkedValuesId.join(', ');

            var dno ='<?= $filing_details['diary_no'] ?>';
            var ucode ='<?= $user_details['usercode'] ?>';


            $.ajax({
                type:"POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    // 'def_name': dName,
                    'dno':dno,
                    'defect_id':data,
                    'ucode':ucode

                },
                url: "<?php echo base_url('Filing/Refiling/update_function'); ?>",
                success: function(data) {
                    // alert(data);
                    var obj = JSON.parse(data);
                    if(obj)
                    {
                        alert('Default Removed Successfully!!!!!');
                        alert(obj.message);

                        var idToUpdate = obj.id_updated;
                        $.each(idToUpdate, function (key, val) {

                            var text = val.trim();
                            //    alert("HHHHH"+text);
                            $('#remove_done' + text).show();
                            $('#remove_done' + text).val("Removed");
                            $('#remove_done'+text).css("color", "green");
                            $('#record' + text).css("color", "green");
                            // $('#btnListing').prop('disabled', true);
                            $('#RemoveAll').prop('disabled', true);
                            $('.Removebtn').prop('disabled', true);
                        });

                    }
                    else{
                        alert("Default Not Removed");
                    }

                    updateCSRFToken();
                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });






        }
    }

    function removeBtn()
    {
        updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var checkboxes = document.querySelectorAll("input:checked");
        if(checkboxes.length == 0)
        {
            alert("Please Check atleast one Default");

        }else{
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            var checkedValuesId = [];
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked)
                {
                    if(checkbox.value != 'on')
                    {
                        checkedValuesId.push(checkbox.value);
                    }

                }
            });
            var data = checkedValuesId.join(', ');

            var dno ='<?= $filing_details['diary_no'] ?>';
            var ucode ='<?= $user_details['usercode'] ?>';


            $.ajax({
                type:"POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    // 'def_name': dName,
                    'dno':dno,
                    'defect_id':data,
                    'ucode':ucode

                },
                url: "<?php echo base_url('Filing/Refiling/update_function'); ?>",
                success: function(data) {
                    // alert(data);
                    var obj = JSON.parse(data);
                    if(obj)
                    {
                        alert('Default Removed Successfully!!!!!!!!');
                        alert(obj.message);

                        var idToUpdate = obj.id_updated;
                        $.each(idToUpdate, function (key, val) {

                            var text = val.trim();
                               // alert("HHHHH"+text);
                               console.log($('#remove_done'+text));
                               // return false;

                            $('#remove_done' + text).show();
                            $('#remove_done' + text).val("Removed");
                            $('#remove_done'+text).css("color", "green");
                            $('#record' + text).css("color", "green");
                            // $('#btnListing').prop('disabled', true);
                            $('#remove_done_btn'+text).attr("disabled", 'disabled');


                        });

                    }
                    else{
                        alert("Default Not Removed");
                    }

                    updateCSRFToken();
                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });
        }
    }


    function btnSmsClicked()
    {
        // alert("jkhifet");
        updateCSRFToken();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dno ='<?= $filing_details['diary_no'] ?>';

        $.ajax({
            type:"POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dno:dno
            },
            url: "<?php echo base_url('Filing/Refiling/sms_btn_clicked'); ?>",
            success: function(data) {

                $('#show_sms_status').html(data);

            },
            error: function(data) {
                alert(data);
                updateCSRFToken();
            }

        });
    }

    function refilingBackDt()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dno ='<?= $filing_details['diary_no'] ?>';
        var status ='<?= $filing_details['c_status'] ?>';
        // alert(dno+">>>"+status);
        // return false;

        $.ajax({
            type:"POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dno:dno,
                status_case:status
            },
            url: "<?php echo base_url('Filing/Refiling/obj_back_date'); ?>",
            success: function(data) {

                // alert(data);

                 $('#ref_bk_dt').html(data);

                  $('#ref_table').hide();
                updateCSRFToken();
                // alert(data);

            },
            error: function(data) {
                alert(data);
                updateCSRFToken();
            }

        });
    }

    $(document).ready(function() {
        $(document).on('click', '#btn_back', function () {
            window.location.href = "<?php echo base_url('Filing/Refiling');?>";
        });
    });

    $(document).ready(function(){
        $(document).on('click','#btn_backdate',function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var dno ='<?= $filing_details['diary_no'] ?>';
            var back_date=$('#back_dt').val();
            if(back_date=='')
            {
                alert('Please enter date');
                $('#back_dt').focus();
                return;
            }

            var currentDate = new Date();
            var dateToCompare = new Date(back_date);

            //alert(currentDate);
            //alert(dateToCompare);

            if (dateToCompare > currentDate) {
                alert("Date cannot be greater than Today's Date ");
                $('#back_dt').focus();
                return;
            }
            var r = confirm("Are you sure to refile on back date?");
            if (r == false) {
                exit();
            }
            //alert(back_date);
            $.ajax({
                url: '<?php echo base_url('Filing/Refiling/save_back_date'); ?>',
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    d_no: dno,
                    back_date:back_date
                },
                beforeSend: function() {
                    $('#sp_sms_status').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {

                    $('#sp_sms_status').html(data);
                    $('#btn_backdate').attr("disabled", true);
                    $('#divprint').hide();

                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }

            });
        });
    });

    function cancelRefiling()
    {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var dno ='<?= $filing_details['diary_no'] ?>';
        var status ='<?= $filing_details['c_status'] ?>';
        // alert("RRRR");
        // return false;
        $.ajax({
            type:"POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dno:dno,
                flag:'A',
                status_case:status
            },
            url: "<?php echo base_url('Filing/Refiling/get_and_save_data'); ?>",
            success: function(data) {

                // alert(data);

                $('#cancel_ref').html(data);
                $('#ref_table').hide();
                updateCSRFToken();
                // alert(data);

            },
            error: function(data) {
                alert(data);
                updateCSRFToken();
            }

        });
    }

    $(document).ready(function(){
        $(document).on('click','#btn_cancel_ref',function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var dno ='<?= $filing_details['diary_no'] ?>';
            var r = confirm("Are you sure to cancel refiling?");
            if (r == false) {
                exit();
            }

            // alert("GGGGG");
            // return false;
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Filing/Refiling/cancel_save_data'); ?>",
                cache: false,
                async: true,
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno:dno,

                },
               success: function(data, status) {

                    $('#cancel_ref_mess').html(data);
                    $('#btn_backdate').attr("disabled", true);
                    $('#ref_table').hide();
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }

            });
        });
    });


</script>



