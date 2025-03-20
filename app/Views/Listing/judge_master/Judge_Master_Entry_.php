
<!DOCTYPE html>


<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Judge Master Entry</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/buttons.dataTables.min.css">

</head>
<body class="hold-transition skin-blue layout-top-nav">
<!-- Report Div Start -->

<div class="wrapper" >
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <?=$this->session->flashdata('msg'); ?>
                </div>

                <div class="box box-info">

                    <form class="form-horizontal" id="push-form"  method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
                        <div class="box-body">

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label for="judge1">Judge 1</label>
                                    <select class="form-control" id="judge1" name="judge1" placeholder="judge1" required="required">
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
                                    <select class="form-control" id="judge2" name="judge2" placeholder="judge2" required="required">
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
                                    <select class="form-control" id="judge3" name="judge3" placeholder="judge3">
                                        <option value="">Select Judge</option>
                                        <?php
                                        foreach($judge as $j3){
                                            echo '<option value="'.$j3['jcode'].'"'.(isset($_POST['judge3']) && $_POST['judge3'] == $j3['jcode'] ? 'selected="selected"' : ''). '>'.$j3['jcode'].' - '.$j3['jname'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-sm-6" id="fromToDatePicker">
                                    <div class="col-sm-6">
                                        <label for="from_date" id="lbl_from_date" class="col-sm-10">New From Date:</label>
                                        <input type="text" id="from_date" value="<?php if(isset($_POST['from_date'])) echo date("d-m-Y", strtotime(strtr($_POST['from_date'],'/','-')));?>" name="from_date" class="form-control datepick"  placeholder="From Date" required="required">
                                    </div>
                                    <div id="div_todate" class="col-sm-6" >
                                        <label for="to_date" id="lbl_to_date" class="col-sm-6">Old To Date:</label>
                                        <input type="text" id="to_date" value="<?php if(isset($_POST['to_date'])) echo date("d-m-Y", strtotime(strtr($_POST['to_date'],'/','-'))); ?>" name="to_date" class="form-control datepick" value="0000-00-00"  placeholder="To Date">
                                    </div>
                                </div>

                                </div>
                                <div class="form-group">

                                    <div class="col-sm-6">
                                        <label for="fresh_limit" id="lbl_fresh_limit" class="col-sm-4">Fresh Limit:</label>

                                        <select class="form-control" id="fresh_limit" name="fresh_limit" placeholder="Fresh Limit" required="required">
                                            <option value="0">Select Fresh Limit</option>
                                            <?php
                                            $i=1;
                                            while($i<=50){

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

                                    <select class="form-control" id="old_limit" name="old_limit" placeholder="Old Limit">
                                        <option value="0">Select Old Limit</option>
                                        <?php
                                            $j=1;
                                            while($j<=50){
                                            /*if($j==30) {
                                                echo '<option value="' . $j . '" selected="selected">' . $j . '</option>';
                                            }
                                            else{*/
                                                echo '<option value="' . $j .'"'.(isset($_POST['old_limit']) && $_POST['old_limit'] == $j ? 'selected="selected"' : ''). '>' . $j . '</option>';
                                            //}
                                            $j++;
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <input type="hidden" name="usercode" id="usercode" value="<?=$this->session->userdata('dcmis_user_idd');?>" >
                           <!-- <input type="hidden" name="usercode" id="usercode" value="1" >-->

                        </div>
                    </form>
                    <div class="box-footer">
                        <button  style="width:15%;float:right" id="button_id"  name="button_id" class="btn btn-block btn-primary"  onclick="check_already_sitted();">Insert/Update</button>
                    </div>
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
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info alert-dismissable fade in" id="info-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>Info! </strong>
                    No Record Found.
                </div>
            </section>
        </div>
    </div>
</div>


<!-- Report Div End -->



<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<script src="<?=base_url()?>assets/js/Reports.js"></script>
<script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
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




    } );



    function insert_Record()
    {
       // debugger;
            $("#display").show();
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
                    url: '<?=base_url();?>index.php/JudgeMaster/insert_Judge_Master',
                    data: {
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
                        $('#reportTable1').DataTable().destroy();
                        $('#reportTable1 tbody').empty();
                        getAllNotices();
                        //alert(data);
                    },
                    error: function (ts) {
                        $("#info-alert").show();
                        $("#info-alert").fadeTo(2000, 500).slideUp(500, function(){
                            $("#info-alert").slideUp(500);
                        });
                        //alert(ts.responseText)
                    }
                });
            }
            else
            {
                alert("Blank Data can't be inserted !");
            }
        }

    function update_case()
    {
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
       if(from_date!='' && judge1!='' && judge2!='' && fresh_limit!='',old_limit!='',to_date!='') {
        //if(to_date!='' && usercode!=''){
           //debugger;
            $.ajax({
                url: '<?=base_url();?>index.php/JudgeMaster/update_case',
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
        }
        else
        {
            alert("Blank Data can't be inserted !");
        }
    }

        function getAllNotices()
        {
          // debugger;
            $.ajax({
                url: '<?=base_url();?>index.php/JudgeMaster/display_Judge_Entry',
                data: {},
                cache:  false,
                dataType: 'json',
                type: "POST",
                success: function(data){

                    if(data.length > 0)
                    {
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
                            iDisplayLength: 8,

                            buttons: [
                                {
                                    extend: 'print',
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                }
                            ]
                        });
                    }
                    else
                    {
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
            var judge1 =$("#judge1 option:selected").val();

            var judge2 = $("#judge2 option:selected").val();
            var judge3 = $("#judge3 option:selected").val();
            var to_dt = $('input[name=to_date]').val();

            if(judge1!='' && judge2 !='') {
                           // debugger;
                $.post("<?=base_url();?>index.php/JudgeMaster/check_case", {judge1: judge1,judge2:judge2, judge3:judge3, to_dt:to_dt}, function (result) {

                    var obj = $.parseJSON(result);
                   // console.log(obj.case_detail);

                    if (Object.keys(obj).length > 0 && obj.case_detail != false) {
                        var id = obj.case_detail[0]['id'];
                        //var ent_dt = obj.case_detail[0]['ent_dt'];
                        //var usercode = obj.case_detail[0]['usercode'];

                        var r = confirm("Record already inserted. Do you want to enter new Record!");
                       // alert(r);
                        if(r == true) {
                           // debugger;
                            update_case();
                            alert("Record Updated Successfully!");
                            location.reload();
                            return true;


                        } else {
                            location.reload();
                        }

                    }
                    else {

                       insert_Record();
                        alert("Record Inserted Successfully!");
                        return true;
                    }
                });
            }
        }

    }


    function check_validation()
    {
      //  debugger;
        var judge1= $("#judge1").prop('selectedIndex');
        var judge2= $("#judge2").prop('selectedIndex');
        var judge3= $("#judge3").prop('selectedIndex');

        var fresh_limit = $('select[name=fresh_limit]').val();
        var old_limit = $('select[name=old_limit]').val();
        var from_date = $('input[name=from_date]').val();
        var to_date = $('input[name=to_date]').val();


        if(judge1==0)
        {
            alert("Please Select Judge 1.");
            return false;
        }
        else if(judge2==0)
        {
            alert("Please Select Judge 2.");
            return false;
        }
        else if(fresh_limit==0)
        {
            alert("Please Select fresh limit.");
            return false;
        }

        else if(old_limit==0)
        {
            alert("Please Select old limit.");
            return false;
        }

        else if(from_date=='')
        {
            alert("Please Select from date.");
            return false;
        }

        else if(to_date=='')
        {
            alert("Please Select to date.");
            return false;
        }
        else {

            if (judge1 != 0 && judge2 != 0) {
                if (judge1 == judge2) {
                    alert("Judge 1 and Judge 2 cannot be same. Please select another Judge!");
                    return false;
                }
                else {

                    if (judge1 == judge3) {
                        alert("Judge 1 and Judge 3 cannot be same. Please select another Judge!");
                        return false;
                    }
                    else {
                        if (judge2 == judge3) {
                            alert("Judge 2 and Judge 3 cannot be same. Please select another Judge!");
                            return false;
                        }
                    }
                    return true;
                }

            }
        }

    }

    function check_already_sitted() {

        if (check_validation() == true) {
             //debugger;
            var judge1 = $("#judge1 option:selected").val();

            var judge2 = $("#judge2 option:selected").val();
            var judge3 = $("#judge3 option:selected").val();
            // var to_dt = $('input[name=to_date]').val();

            if (judge1 != '' && judge2 != '') {
                // debugger;
                $.post("<?=base_url();?>index.php/JudgeMaster/check_already_sitted", {
                    judge1: judge1,
                    judge2: judge2,
                    judge3: judge3
                },function (result) {

                    var obj = $.parseJSON(result);
                    //console.log(obj.case_detail);
                   // alert (Object.keys(obj).length);
                    if (Object.keys(obj).length > 0 && obj.case_detail != false) {
                        var id = obj.case_detail[0]['id'];
                        var p1 = obj.case_detail[0]['p1'];
                        var p2 = obj.case_detail[0]['p2'];
                        var p3 = obj.case_detail[0]['p3'];

                        alert("Judges Already Sitted with Another Coram!");
                        return false;

                    }
                    else {

                        check_case();
                    }

                });

            }
            else {
                alert("There is problem please contact Computer Cell!");
            }
        }
    }



</script>


</body>
</html>