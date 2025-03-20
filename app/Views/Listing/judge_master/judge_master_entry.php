<?= view('header') ?>
    <style>
        #display {
            padding: 10px !important;
        }
        .dt-buttons {
            margin-top: 0px !important;
        }
        #reportTable1_filter {
            margin: -41px 55px 0px 0px !important;
        }
        #reportTable1_paginate {
            margin-top: -25px !important;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p>
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder"> Judge Roster </div>
                                        <div class="card-body">
                                            <?php
                                            echo form_open('#');
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="well">
                                                        <div class="row">
                                                            <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode'] ?>"/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <label for="judge1">Judge 1</label>
                                                                <select class="form-control cus-form-ctrl" id="judge1" name="judge1" placeholder="judge1" required="required">
                                                                    <option value="">Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j1){
                                                                        echo '<option value="'.$j1['jcode'].'"'.(isset($_POST['judge1']) && $_POST['judge1'] == $j1['jcode'] ? 'selected="selected"' : ''). '>'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <label for="judge2">Judge 2</label>
                                                                <select class="form-control cus-form-ctrl" id="judge2" name="judge2" placeholder="judge2" required="required">
                                                                    <option value="">Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j2){
                                                                        echo '<option value="'.$j2['jcode'].'"'.(isset($_POST['judge2']) && $_POST['judge2'] == $j2['jcode'] ? 'selected="selected"' : ''). '>'.$j2['jcode'].' - '.$j2['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <label for="judge3">Judge 3</label>
                                                                <select class="form-control cus-form-ctrl" id="judge3" name="judge3" placeholder="judge3">
                                                                    <option value="">Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j3){
                                                                        echo '<option value="'.$j3['jcode'].'"'.(isset($_POST['judge3']) && $_POST['judge3'] == $j3['jcode'] ? 'selected="selected"' : ''). '>'.$j3['jcode'].' - '.$j3['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label for="from_date" id="lbl_from_date" class="col-sm-10">New From Date:</label>
                                                                <input type="text" id="from_date" value="<?php if(isset($_POST['from_date'])) echo date("Y-m-d", strtotime($_POST['from_date']));?>" name="from_date" class="form-control cus-form-ctrl datepick"  placeholder="From Date" readonly required="required">
                                                            </div>
                                                            <div id="div_todate" class="col-sm-3" >
                                                                <label for="to_date" id="lbl_to_date" class="col-sm-6">Old To Date:</label>
                                                                <input type="text" id="to_date" value="<?php if(isset($_POST['to_date'])) echo date("d-m-Y", strtotime(strtr($_POST['to_date'],'/','-'))); ?>" name="to_date" class="form-control cus-form-ctrl datepick" value="0000-00-00" readonly placeholder="To Date">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-sm-6">
                                                                <label for="fresh_limit" id="lbl_fresh_limit" class="col-sm-4">Fresh Limit:</label>
                                                                <select class="form-control cus-form-ctrl" id="fresh_limit" name="fresh_limit" placeholder="Fresh Limit" required="required">
                                                                    <option value="0">Select Fresh Limit</option>
                                                                    <?php
                                                                    $i=1;
                                                                    while($i<=50) {
                                                                        /*if($i==30) {
                                                                            echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
                                                                        }
                                                                        else{*/
                                                                            echo '<option value="' . $i .'"'.(isset($_POST['fresh_limit']) && $_POST['fresh_limit'] == $i ? 'selected="selected"' : ''). '>' . $i . '</option>';
                                                                        // }
                                                                        $i++;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="old_limit" id="lbl_fresh_limit" class="col-sm-4">Old Limit:</label>
                                                                <select class="form-control cus-form-ctrl" id="old_limit" name="old_limit" placeholder="Old Limit">
                                                                    <option value="0">Select Old Limit</option>
                                                                    <?php
                                                                        $j=1;
                                                                        while($j<=50){
                                                                        /*if($j==30) {
                                                                            echo '<option value="' . $j . '" selected="selected">' . $j . '</option>';
                                                                        }
                                                                        else{*/
                                                                            echo '<option value="' . $j .'"'.(isset($_POST['old_limit']) && $_POST['old_limit'] == $j ? 'selected="selected"' : ''). '>' . $j . '</option>';
                                                                        // }
                                                                        $j++;
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-sm-6">
                                                                <button id="button_id" type="button" name="button_id" class="btn btn-block btn-primary pull-left" onclick="check_already_sitted();">Insert/Update</button>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <button id="button_id2" type="button" name="button_id2" class="btn btn-danger btn-secondary pull-right" onclick="CloseEntry();">Close Old Entry</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                        <div id="display" class="box box-danger">
                                            <table width="100%" id="reportTable1" class="table table-striped table-hover">
                                                <thead>
                                                    <h3 style="text-align: center;"> JUDGES MASTER</h3>
                                                    <tr>
                                                        <th>S No.</th>
                                                        <th>Judge-1</th>
                                                        <th>Judge-2</th>
                                                        <th>Judge-3</th>
                                                        <th>New From Date</th>
                                                        <th>Old To Date</th>
                                                        <th>Fresh Limit</th>
                                                        <th>Old Limit</th>
                                                        <th>Inserted By</th>
                                                        <th>To Entered on</th>
                                                        <th>To Enter By</th>
                                                        <!--  <th>Traced Ip</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="alert alert-info alert-dismissable fade in m-3" id="info-alert">
                                            <button type="button" class="close" data-dismiss="alert">x</button>
                                            <strong>Info! </strong>
                                            No Record Found.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            // debugger;
            $('#reportTable1').DataTable().destroy();
            $('#reportTable1 tbody').empty();
            getAllNotices();
            $("#display").hide();
            $(function () {
                $('#from_date, #to_date').datepicker({
                    format: 'dd-mm-yyyy',
                    //startDate: new Date(),
                    autoclose:true
                });
            });
            $('#info-alert').hide();
        });

        function insert_Record() {
            // debugger;
            $("#display").show();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var judge1 = $('select[id=judge1]').val();
            var judge2 = $('select[id=judge2]').val();
            var judge3 = $('select[id=judge3]').val();
            var fresh_limit = $('select[name=fresh_limit]').val();
            var old_limit = $('select[name=old_limit]').val();
            var from_date = $('input[name=from_date]').val();
            // var to_date = $('input[name=to_date]').val();
            var usercode=$('#usercode').val();
            if(from_date!='' && judge1!='' && judge2!='') {
                $.ajax({
                    url: '<?php echo base_url("Listing/JudgeMaster/insert_Judge_Master"); ?>',
                    data: {
                        CSRF_TOKEN:csrf,
                        judge1: judge1,
                        judge2: judge2,
                        judge3: judge3,
                        fresh_limit: fresh_limit,
                        old_limit: old_limit,
                        from_date: from_date,
                        usercode: usercode
                    },
                    cache: false,
                    dataType: 'json',
                    type: "POST",
                    success: function (data) {
                        updateCSRFTokenSync();
                        $('#reportTable1').DataTable().destroy();
                        $('#reportTable1 tbody').empty();
                        //alert(data);
                        getAllNotices();
                    },
                    error: function (ts) {
                        updateCSRFTokenSync();
                        $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });
                    }
                });
            } else {
                alert("Blank Data can't be inserted !");
            }
            updateCSRFTokenSync();
        }

        function update_case() {
            // debugger;
            $("#display").show();
            var judge1 = $('select[id=judge1]').val();
            var judge2 = $('select[id=judge2]').val();
            var judge3 = $('select[id=judge3]').val();
            var fresh_limit = $('select[name=fresh_limit]').val();
            var old_limit = $('select[name=old_limit]').val();
            var from_date = $('input[name=from_date]').val();
            var to_date = $('input[name=to_date]').val();
            var usercode=$('#usercode').val();
            if(from_date!='' && judge1!='' && judge2!='' && fresh_limit!='' && old_limit!='' && to_date!='') {
            // if(to_date!='' && usercode!='') {
                // debugger;
                $.ajax({
                    url: '<?php echo base_url("Listing/JudgeMaster/update_case"); ?>',
                    data: {
                        judge1: judge1,
                        judge2: judge2,
                        judge3: judge3,
                        fresh_limit: fresh_limit,
                        old_limit: old_limit,
                        from_date: from_date,
                        to_date: to_date,
                        usercode: usercode
                    },
                    cache: false,
                    dataType: 'json',
                    type: "POST",
                    success: function (data) {
                        updateCSRFTokenSync();
                        $('#reportTable1').DataTable().destroy();
                        $('#reportTable1 tbody').empty();
                        //alert(data);
                        getAllNotices();
                    }
                    /*error: function (ts) {
                        $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });
                        //alert(ts.responseText)
                    }*/
                });
            } else {
                alert("Blank Data can't be inserted !");
            }
        }

        function getAllNotices() {
            // debugger;
            $.ajax({
                url: '<?php echo base_url("Listing/JudgeMaster/display_Judge_Entry"); ?>',
                data: {},
                cache:  false,
                dataType: 'json',
                type: "GET",
                success: function(data){
                    updateCSRFTokenSync();
                    // console.log(data);
                    if(data.length > 0) {
                        $("#display").show();
                        $('#reportTable1 tbody').empty();
                        sno = 1;
                        $.each(data, function (index) {
                            $('#reportTable1 tbody').append("<tr><td>" + sno + "</td><td>" + data[index].judge1 + "</td><td>" + data[index].judge2 + "</td><td>" + data[index].judge3 + "</td><td>" + data[index].from_dt + "</td><td>" + data[index].to_dt + "</td><td>" + data[index].fresh_limit + "</td><td>" + data[index].old_limit + "</td><td style='font-weight: bold'>" + data[index].ins_by + "</td><td>" + data[index].to_dt_ent_dt + "</td><td style='font-weight: bold'>" + data[index].to_updated_by + "</td></tr>");
                            sno++;
                        });
                        $('#reportTable1').DataTable({
                            "bSort": true,
                            dom: 'Bfrtip',
                            "scrollX": true,
                            iDisplayLength: 15,
                            buttons: [
                                {
                                    extend: 'print',
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                }
                            ]
                        });
                    } else {
                        $("#display").hide();
                        $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });
                    }
                }
                /*error: function(xhr, status, error) {
                    console.log(xhr);
                    if (xhr == 'undefined' || xhr == undefined) {
                        alert('undefined');
                    } else {
                        alert('object is there');
                    }
                    alert(status);
                    alert(error);
                }*/
                //error: function(ts) { alert(ts.responseText) }
            });
        }

        function check_case() {
            // debugger;
            if (check_validation() == true) {
                // debugger;
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();
                var judge1 = $("#judge1 option:selected").val();
                var judge2 = $("#judge2 option:selected").val();
                var judge3 = $("#judge3 option:selected").val();
                var to_dt = $('input[name=to_date]').val();
                if(judge1 != '' && judge2 != '') {
                    // debugger;
                    $.get("<?php echo base_url('Listing/JudgeMaster/check_case'); ?>", {CSRF_TOKEN: csrf,judge1: judge1,judge2: judge2, judge3: judge3, to_dt: to_dt}, function (result) {
                        // console.log(result)
                        var obj = $.parseJSON(result);
                        updateCSRFTokenSync();
                        if (Object.keys(obj).length > 0 && obj.case_detail != false) {
                            var id = obj.case_detail[0]['id'];
                            // var ent_dt = obj.case_detail[0]['ent_dt'];
                            // var usercode = obj.case_detail[0]['usercode'];
                            var r = confirm("Record already inserted. Do you want to enter new Record!");
                            if(r == true) {
                                // debugger;
                                update_case();
                                alert("Record Updated Successfully!");
                                location.reload();
                                return true;
                            } else {
                                location.reload();
                            }
                        } else {
                            insert_Record();
                            alert("Record Inserted Successfully!");
                            return true;
                        }
                    });
                }
            }
            updateCSRFTokenSync();
        }

        function check_validation() {
            // debugger;
            var judge1= $("#judge1").prop('selectedIndex');
            var judge2= $("#judge2").prop('selectedIndex');
            var judge3= $("#judge3").prop('selectedIndex');
            var fresh_limit = $('select[name=fresh_limit]').val();
            var old_limit = $('select[name=old_limit]').val();
            var from_date = $('input[name=from_date]').val();
            var to_date = $('input[name=to_date]').val();

            if(judge1==0) {
                alert("Please Select Judge 1.");
                $('#judge1').focus();
                return false;
            } else if(judge2==0) {
                alert("Please Select Judge 2.");
                $('#judge2').focus();
                return false;
            } else if(from_date=='') {
                alert("Please Select From Date.");
                $('#from_date').focus();
                return false;
            } else if(to_date=='') {
                alert("Please Select To Date.");
                $('#to_date').focus();
                return false;
            } else if(fresh_limit==0) {
                alert("Please Select fresh limit.");
                $('#fresh_limit').focus();
                return false;
            } else if(old_limit==0) {
                alert("Please Select old limit.");
                $('#old_limit').focus();
                return false;
            } else {
                if (judge1 != 0 && judge2 != 0) {
                    if (judge1 == judge2) {
                        alert("Judge 1 and Judge 2 cannot be same. Please select another Judge!");
                        $('#judge2').focus();
                        return false;
                    } else {
                        if (judge1 == judge3) {
                            alert("Judge 1 and Judge 3 cannot be same. Please select another Judge!");
                            $('#judge3').focus();
                            return false;
                        } else {
                            if (judge2 == judge3) {
                                alert("Judge 2 and Judge 3 cannot be same. Please select another Judge!");
                                $('#judge3').focus();
                                return false;
                            }
                        }
                        return true;
                    }
                }
            }
        }

        async function check_already_sitted() {
            if (check_validation() == true) {
                // debugger;
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();
                var judge1 = $("#judge1 option:selected").val();
                var judge2 = $("#judge2 option:selected").val();
                var judge3 = $("#judge3 option:selected").val();
                // var to_dt = $('input[name=to_date]').val();
                if (judge1 != '' && judge2 != '') {
                    $.post("<?php echo base_url('Listing/JudgeMaster/check_already_sitted'); ?>", {
                        CSRF_TOKEN:csrf,
                        judge1: judge1,
                        judge2: judge2,
                        judge3: judge3
                    },function (result) {
                        updateCSRFTokenSync();
                        var obj = $.parseJSON(result);
                        if (Object.keys(obj).length > 0 && obj.case_detail != false) {
                            var id = obj.case_detail[0]['id'];
                            var p1 = obj.case_detail[0]['p1'];
                            var p2 = obj.case_detail[0]['p2'];
                            var p3 = obj.case_detail[0]['p3'];
                            alert("Judges Already Sitted with Another Coram!");
                            return false;
                        } else {
                            check_case();
                        }
                    });
                } else {
                    alert("There is problem please contact Computer Cell!");
                }
            }else{
                return false;
            }

            updateCSRFTokenSync();
        }
        
        function CloseEntry() {
            if (check_validation() == true) {
                var r = confirm("Do you really want to close the entry for the Judges!");
                if (r == true) {
                    // debugger;
                    $("#display").show();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var csrf = $("input[name='CSRF_TOKEN']").val();
                    var judge1 = $('select[id=judge1]').val();
                    var judge2 = $('select[id=judge2]').val();
                    var judge3 = $('select[id=judge3]').val();
                    var fresh_limit = $('select[name=fresh_limit]').val();
                    var old_limit = $('select[name=old_limit]').val();
                    var from_date = $('input[name=from_date]').val();
                    var to_date = $('input[name=to_date]').val();
                    var usercode = $('#usercode').val();
                    if (judge1 != '' && judge2 != '' && to_date != '') {
                        //if(to_date!='' && usercode!=''){
                        //debugger;
                        $.ajax({
                            url: '<?php echo base_url("Listing/JudgeMaster/close_entry"); ?>',
                            data: {
                                CSRF_TOKEN:csrf,
                                judge1: judge1,
                                judge2: judge2,
                                judge3: judge3,
                                fresh_limit: fresh_limit,
                                old_limit: old_limit,
                                from_date: from_date,
                                to_date: to_date,
                                usercode: usercode
                            },
                            cache: false,
                            type: "POST",
                            success: function (data) {
                                updateCSRFTokenSync();
                                $('#reportTable1').DataTable().destroy();
                                $('#reportTable1 tbody').empty();
                                if(!empty(data)){
                                    alert(data);
                                }                                
                                getAllNotices();
                            }
                        });
                    } else {
                        alert("Blank Data can't be inserted !");
                    }
                }
                updateCSRFTokenSync();
            }
        }
    </script>
<?=view('sci_main_footer') ?>