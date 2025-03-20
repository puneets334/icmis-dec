<?php
session_start();
include("../../menu_assign/config.php");
include("../../includes/functions.php");
include("../../includes/db_inc.php");
include_once("../common/field_function.php");
$clientIP = getClientIP();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title> AOR Case Record  </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../offline_copying/js/jquery-1.9.1.js"></script>
    <link rel="stylesheet" href="../../offline_copying/css/bootstrap.min.css" >
    <script src="../../offline_copying/js/bootstrap.min.js"></script>
    <link href="../../plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <script src="../../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="../../plugins/sweetalert/sweetalert5.7.min.js"></script>
    <link rel="stylesheet" href="../../plugins/fontawesome-free-5.7.0-web/css/all.css" >
</head>
<body>
<div class="bg-light">
<div class="container-fluid m-0 p-0">
<div class="row clearfix mr-1 ml-1 p-0">
<div class="col-12 m-0 p-0">
<p id="show_error"></p>
<div class="card">
    <div class="card-header bg-info text-white font-weight-bolder">VC Consent - Entry Module</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <label>Listing Date<span style="color:red;">*</span></label>
                <div class="border">
                    <select name="listing_dts" id="listing_dts" class="form-control">
                        <?php
                        $sql = "SELECT c.next_dt FROM heardt c WHERE c.next_dt >= CURDATE() AND (c.main_supp_flag = '1' OR c.main_supp_flag = '2') GROUP BY next_dt order by c.next_dt ";
                        $res=mysql_query($sql) or die(mysql_error());
                        if(mysql_num_rows($res)>0){
                            ?>
                            <option value="-1" selected>SELECT</option>
                            <?php
                            while($row=mysql_fetch_array($res)){
                                ?>
                                <option value="<?php echo $row['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($row['next_dt'])); ?></option>
                                <?php
                            }
                        }
                        else{
                            ?>
                            <option value="-1" selected>EMPTY</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-5">
                <label>List Type</label>
                <div class="border">
                    <select class="form-control" name="list_type" id="list_type">
                        <option value="0">ALL</option>
                        <option value="4">Misc.</option>
                        <option value="3">Regular</option>
                        <option value="5">Chamber</option>
                        <option value="6">Registrar</option>
                    </select>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-sm-5">
                <label>Hon'ble Judges</label>
                <div class="border">
                    <select class="form-control" name="judge_code" id="judge_code">
                        <option value="0" selected>All</option>
                        <?php
                        $sql = "SELECT jcode, if(jtype = 'J', jname, concat(first_name, ' ', sur_name,', ',jname)) judge_name from judge where display = 'Y' and is_retired = 'N' order by if(jtype = 'J',1,2), judge_seniority ";
                        $res=mysql_query($sql) or die(mysql_error());
                        if(mysql_num_rows($res)>0){
                            while($row=mysql_fetch_array($res)){
                                ?>
                                <option value="<?= $row['jcode'] ?>" > <?= $row['judge_name'] ?></option>
                                <?php
                            }
                        }
                        else{
                            ?>
                            <option value="-1" selected>EMPTY</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-5">
                <label>OR Court No.</label>
                <div class="border">
                    <select class="form-control" name="court_no" id="court_no">
                        <option value="0" selected>All</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="21">21 (Registrar)</option>
                        <option value="22">22 (Registrar)</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-2">
                <label>Action<span style="color:red;">*</span></label>
                <button id="button_search" name="button_search" type="button" class="btn btn-success btn-block">Search</button>
            </div>
        </div>




    </div>
</div>
</div>
        <div class="row col-md-12 m-0 p-0" id="result"></div>
    </div>
</body>
</html>
<script>
    $(document).on("focus",".dtp",function(){
        $('.dtp').datepicker(
            {
                format: 'dd-mm-yyyy',
                changeMonth : true,
                changeYear  : true,
                yearRange : '1950:2050',
                autoclose: true
            });
    });

$(document).on('click','#button_search',function(){
    var listing_dts  = $("select#listing_dts option:selected").val();
    var list_type = $("select#list_type option:selected").val();
    var judge_code = $("select#judge_code option:selected").val();
    var court_no = $("select#court_no option:selected").val();

    if(listing_dts == '-1'){
       $('#show_error').html('');
       $('#result').html('');
       $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select listing date</strong></div>');
       $("#listing_dts").focus();
       return false;
    }
    else if(judge_code > 0 && court_no > 0){
        $('#show_error').html('');
        $('#result').html('');
        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select either Honble Judge Name or Court No.</strong></div>');
        $("#listing_dts").focus();
        return false;
    }
    else{
        $("#result").html('');
        var postData = {};
        postData.listing_dts= listing_dts;
        postData.list_type= list_type;
        postData.judge_code= judge_code;
        postData.court_no= court_no;
        $.ajax({
            url: './get_aor_case_record.php',
            cache: false,
            async: true,
            data: postData,
            dataType:'html',
            beforeSend:function(){
                $("#button_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
            },
            type: 'POST',
            success: function(res) {
                if(res){
                    $("#result").html('');
                    $("#result").html(res);
                }
                else {
                    $("#result").html('');
                }
                $("#button_search").html('Search');
            },
            error: function(xhr) {
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
});
    function chkall(e){
        var elm=e.name;
        if(document.getElementById(elm).checked)
        {
            $('input[type=checkbox]').each(function () {
                if($(this).attr("name")=="chk")
                    this.checked=true;
            });
        }
        else
        {
            $('input[type=checkbox]').each(function () {
                if($(this).attr("name")=="chk")
                    this.checked=false;
            });
        }
    }





    $(document).on('click', '.save_modify', function () {
        var updation_method = $(this).data('updation_method');
        var action = $(this).data('action');
        //single
        if(updation_method == 'single') {
            var diary_no = $(this).data('diary_no');
            var button_name = $(this).text();
            var conn_key = $(this).data('conn_key');
            var next_dt = $(this).data('next_dt');
            var roster_id = $(this).data('roster_id');
            var main_supp_flag =  $(this).data('main_supp_flag');
            var clno =  $(this).data('clno');
            var id = $(this).attr('id');
            var applicant_type ='';
            var applicant_id='';
            var userArr =[];
            var chk_count=0;
            var actionSuccess='';
            if(action && action =='save'){
                actionSuccess ="saved";
            }
            else{
                actionSuccess ="modified";
            }
            if($('input[name='+diary_no+']')){
                $('input[name='+diary_no+']').each(function (){
                    if($(this).attr("name")== diary_no && $(this).is(':checked')){
                        chk_count++;
                        applicant_type = $(this).attr('data-applicant_type');
                        applicant_id = $(this).attr('data-applicant_id');
                        tmpObject = {};
                        tmpObject.applicant_type = applicant_type;
                        tmpObject.applicant_id = applicant_id;
                        userArr.push(tmpObject);
                    }
                });
            }
            if(chk_count == 0){
                swal({title: "Error!",text: "Atleast one record should be selected",icon: "error",button: "error!"});
                return false;
            }
            swal({
                title: "Are you sure?",
                text: "Do you want to "+actionSuccess+ ' '+chk_count+" record(s) ",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    var postData = {};
                    postData.diary_no=diary_no;
                    postData.conn_key=conn_key;
                    postData.roster_id=roster_id;
                    postData.next_dt=next_dt;
                    postData.userArr=userArr;
                    postData.main_supp_flag = main_supp_flag;
                    postData.clno = clno;
                    postData.updation_method=updation_method;
                    postData.action = action;
                    $.ajax({
                        url: './save_aor_case_data.php',
                        cache: false,
                        async: true,
                        data: postData,
                        beforeSend: function () {
                            // $("#" + id).html('Processing <i class="fas fa-sync fa-spin"></i>');
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function (data) {
                            if(data.status=='success') {
                               // console.log(data);
                                //return false;
                                swal({title: "Success!",text: chk_count+" record(s) "+actionSuccess+"  Successfully",icon: "success",button: "success!"}).then(function(){
                                    }
                                );
                            }
                            else {
                                swal({title: "Error!", text: data.status, icon: "error", button: "error!"});
                            }

                        },
                        error: function (xhr) {
                            console.log("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });
                } else {
                    swal("Cancelled", "Please try again", "error");
                }
            });
        }
        //bulk
       else if(updation_method == 'bulk') {

        }

    });

    $(document).on("click",".aorCheckboxAll",function () {
        var diary_no = $(this).attr("data-diaryid");
        var id = $(this).attr("id");
        if(diary_no && id){
            if(document.getElementById(id).checked)
            {
                $('input[type=checkbox]').each(function () {
                    if($(this).attr("name")== diary_no)
                        this.checked=true;
                });
            }
            else
            {
                $('input[type=checkbox]').each(function () {
                    if($(this).attr("name")== diary_no)
                        this.checked=false;
                });
            }
        }

    })

</script>
