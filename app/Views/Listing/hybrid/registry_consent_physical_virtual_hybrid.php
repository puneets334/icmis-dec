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
    <title> Physical Hearing (With Hybrid Option)  </title>
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
    <div class="card-header bg-info text-white font-weight-bolder">Directions for Physical Hearing (With Hybrid Option) - Daily List
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <label class="form-check-inline" for="mainhead_1"> Mainhead<span style="color:red;">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="M" title="Miscellaneous" name="mainhead" id="mainhead_1" checked>
                    <label class="form-check-label" for="mainhead_1">Misc.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="F" title="Regular" name="mainhead" id="mainhead_2" >
                    <label class="form-check-label" for="mainhead_2">Regular</label>
                </div>
            </div>
            <div class="col-sm-4">
                <label> Listing Date<span style="color:red;">*</span></label>
                <div class="border">
                    <select name="listing_dts" id="listing_dts" class="form-control">
                        <?php
                        $sql = "SELECT c.next_dt FROM heardt c WHERE mainhead = 'M' AND c.next_dt >= CURDATE() AND (c.main_supp_flag = '1' OR c.main_supp_flag = '2') GROUP BY next_dt order by c.next_dt desc";
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
            <div class="col-sm-4">
                <label> Board Type<span style="color:red;">*</span></label>
                <div class="border">
                    <select class="form-control" name="board_type" id="board_type">
                        <option value="0">Select Board Type</option>
                        <option value="J">Court</option>
                        <option value="C">Chamber</option>
                        <option value="R">Registrar</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label> Benches<span style="color:red;">*</span></label>
                <div class="border">
                    <select class="form-control" name="jud_ros" id="jud_ros">
                        <option value="-1" selected>SELECT</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <label> Part No.<span style="color:red;">*</span></label>
                <div class="border">
                    <select name="part_no" id="part_no" class="form-control">
                        <option value="">Part No.</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <label> Main/Supplementary List<span style="color:red;">*</span></label>
                <div class="form-check" style="">
                    <input class="form-check-input" type="radio" value="1" title="Main List" name="main_supp_list" id="main_supp_list_1" checked>
                    <label class="form-check-label" for="main_supp_list_1">
                        Main List
                    </label>
                </div>
                <div class="form-check" style="">
                    <input class="form-check-input" type="radio" title="Supplementary List"  value="2" name="main_supp_list" id="main_supp_list_2" >
                    <label class="form-check-label" for="main_supp_list_2">
                        Supplementary List
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9"></div>
            <div class="col-sm-2">
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
    var mainhead = $("input[name='mainhead']:checked").val();
    var listing_dts  = $("select#listing_dts option:selected").val();
    var board_type = $("select#board_type option:selected").val();
    var jud_ros = $("select#jud_ros option:selected").val();
    var part_no = $("select#part_no option:selected").val();
    var main_supp_list = $("input[name='main_supp_list']:checked").val();
    if(listing_dts == '-1'){
       $('#show_error').html('');
       $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select listing date</strong></div>');
       $("#listing_dts").focus();
       return false;
    }
    else if(board_type == '0'){
        $('#show_error').html('');
        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select board type</strong></div>');
        $("#board_type").focus();
        return false;

    }
    else if(jud_ros == '0'){
        $('#show_error').html('');
        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select benches</strong></div>');
        $("#jud_ros").focus();
        return false;
    }
    else if(part_no == '0'){
        $('#show_error').html('');
        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select part no</strong></div>');
        $("#part_no").focus();
        return false;
    }
    else if(main_supp_list == ''){
        $('#show_error').html('');
        $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select main/supplementary list</strong></div>');
        $("#main_supp_list_1").focus();
        return false;
    }
    else{
        $("#result").html('');
        var postData = {};
        postData.mainhead = mainhead;
        postData.listing_dts= listing_dts;
        postData.board_type= board_type;
        postData.jud_ros= jud_ros;
        postData.part_no= part_no;
        postData.main_supp_list= main_supp_list;
        $.ajax({
            url: './getDataByBoardTypeBenchListingDate.php',
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
    $(document).on("change","input[name='mainhead']",function(){
        var mainhead = get_mainhead();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '../common/get_cl_print_mainhead.php',
            cache: false,
            async: true,
            data: {mainhead:mainhead, board_type: board_type},
            type: 'POST',
            success: function(data, status) {
                $('#listing_dts').html(data);
                $('#jud_ros').html("<option value='-1' selected>EMPTY</option>");
                $('#part_no').html("<option value='-1' selected>EMPTY</option>");
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    function get_mainhead(){
        var mainhead = "";
        $('input[type=radio]').each(function () {
            if($(this).attr("name")=="mainhead" && this.checked)
                mainhead = $(this).val();
        });
        return mainhead;
    }
    $(document).on("change","#board_type",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '../common/get_cl_print_benches_from_roster.php',
            cache: false,
            async: true,
            data: {list_dt: list_dt, mainhead:mainhead, board_type:board_type},
            type: 'POST',
            success: function(data, status) {
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on("change","#listing_dts",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '../common/get_cl_print_benches_from_roster.php',
            cache: false,
            async: true,
            data: {list_dt: list_dt, mainhead:mainhead, board_type:board_type},
            type: 'POST',
            success: function(data, status) {
                $('#jud_ros').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on("change","#jud_ros",function(){
        var mainhead = get_mainhead();
        var list_dt = $("#listing_dts").val();
        var jud_ros = $("#jud_ros").val();
        var board_type = $("#board_type").val();
        $.ajax({
            url: '../common/get_cl_print_partno.php',
            cache: false,
            async: true,
            data: {list_dt: list_dt, mainhead:mainhead,jud_ros:jud_ros,board_type:board_type},
            type: 'POST',
            success: function(data, status) {
                $('#part_no').html(data);
            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $(document).on('click', '.delete_action', function () {
        var updation_method = $(this).data('updation_method');
        var diary_no = $(this).data('diary_no');
        var conn_key = $(this).data('conn_key');
        var ip = '<?=$clientIP?>';
        var next_dt = $(this).data('next_dt');
        var roster_id = $(this).data('roster_id');
        var judges = $(this).data('judges');
        var main_supp_flag = $(this).data('main_supp_flag');
        var clno = $(this).data('clno');
        var mainhead = $(this).data('mainhead');
        var board_type = $(this).data('board_type');
        var entry_date = $(this).data('entry_date');
        var courtno = $(this).data('courtno');
        var list_type_id = $(this).data('list_type_id');
        var id = $(this).attr('id');
        var checkbox_no ='';
        if(id){
            var idArr = id.split('_');
            checkbox_no = parseInt(idArr[1]);
        }
        $.ajax({
            url:'physical_vc_hybrid_direction_delete.php',
            cache: false,
            async: true,
            data: {diary_no:diary_no,ip:ip,conn_key:conn_key,updation_method:updation_method,next_dt:next_dt,roster_id:roster_id,
                judges:judges,main_supp_flag:main_supp_flag,clno:clno,mainhead:mainhead,board_type:board_type,entry_date:entry_date,courtno:courtno,
                list_type_id:list_type_id},
            type: 'POST',
            dataType: "json",
            success: function(data) {
                if(data.status == 'success'){
                    $("#vc_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                    $("#hybrid_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                    $("#physical_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                    $("#" + id).closest('.physical_vc_hybrid_remove_direction').remove();
                    $("#checkbox_"+checkbox_no).prop('checked',false);
                    // $("#checkbox_"+ checkbox_no).css('outline-color', 'white');
                    // $("#checkbox_"+ checkbox_no).css('outline-style', 'none');
                    // $("#checkbox_"+ checkbox_no).css('outline-width', 'none');
                    swal({title: "Success!",text: "Directions Removed",icon: "success",button: "success!"});
                }
                else{
                    swal({title: "Error!",text: data.status,icon: "error",button: "error!"});
                    $("#d_"+diary_no).children(".delete_action").html('Delete');
                }
            },
            error: function(xhr) {
                console.log("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
     });
    $(document).on('click', '.hybrid_action', function () {
        var updation_method = $(this).data('updation_method');
        //single
        if(updation_method == 'single') {
            var diary_no = $(this).data('diary_no');
            var button_name = $(this).text();
            var id = $(this).attr('id');
            var checkbox_no ='';
            if(id){
                var idArr = id.split('_');
                checkbox_no = parseInt(idArr[1]);
            }
            // if($('#checkbox_'+ checkbox_no).is(':checked')) {
                var conn_key = $(this).data('conn_key');
                var next_dt = $(this).data('next_dt');
                var roster_id = $(this).data('roster_id');
                var judges = $(this).data('judges');
                var main_supp_flag = $(this).data('main_supp_flag');
                var clno = $(this).data('clno');
                var mainhead = $(this).data('mainhead');
                var board_type = $(this).data('board_type');
                var update_flag = $(this).data('update_flag');
                var entry_date = $(this).data('entry_date');
                var courtno = $(this).data('courtno');
                var list_type_id = $(this).data('list_type_id');
                var update_flag_text = "";
                if (update_flag == "P") {
                    update_flag_text = "Physical";
                }
                if (update_flag == "V") {
                    update_flag_text = "VC";
                }
                if (update_flag == "H") {
                    update_flag_text = "Hybrid";
                }
                $.ajax({
                    url: 'consent_physical_virtual_hybrid_save.php',
                    cache: false,
                    async: true,
                    data: {
                        diary_no: diary_no,
                        conn_key: conn_key,
                        roster_id: roster_id,
                        judges: judges,
                        main_supp_flag: main_supp_flag,
                        clno: clno,
                        mainhead: mainhead,
                        board_type: board_type,
                        next_dt: next_dt,
                        updation_method: updation_method,
                        update_flag: update_flag,
                        entry_date:entry_date,
                        courtno:courtno,
                        list_type_id:list_type_id
                    },
                    beforeSend: function () {
                        $("#" + id).html('Processing <i class="fas fa-sync fa-spin"></i>');
                    },
                    type: 'POST',
                    dataType: "json",
                    success: function (data) {
                       $("#" + id).html(button_name);
                        if (data.status == 'success') {
                            swal({
                                title: "Success!",
                                text: "Directons for " + update_flag_text + " Hearing Preserved",
                                icon: "success",
                                button: "success!"
                            });
                            $("#vc_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                            $("#hybrid_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                            $("#physical_" + checkbox_no).addClass('btn-secondary').removeClass('btn-success');
                            setTimeout(function(){
                                $("#" + id).addClass('btn-success').removeClass('btn-secondary');
                            },1000);
                            $("#"+'removeDirection_'+checkbox_no).closest('.physical_vc_hybrid_remove_direction').remove();
                            setTimeout(function(){
                                $("#checkbox_" + checkbox_no).attr('data-entry_date',data.entry_date);
                                $("#" + id).closest('.physical_vc_hybrid_button').after('<div class="form-group mt-1 physical_vc_hybrid_remove_direction">' +
                                    '<button id="removeDirection_'+checkbox_no+'" data-entry_date="'+data.entry_date+'" data-updation_method="single" data-diary_no="'+diary_no+'" ' +
                                    'data-conn_key="'+conn_key+'" data-next_dt="'+next_dt+'" data-roster_id="'+roster_id+'" data-judges="'+judges+'" data-main_supp_flag="'+main_supp_flag+'" ' +
                                    'data-mainhead="'+mainhead+'" data-courtno="'+courtno+'" data-list_type_id="'+list_type_id+'" data-board_type="'+board_type+'" data-clno="'+clno+'" ' +
                                    'class="btn btn-danger delete_action btn-block" type="button" name="delete_action" >Remove Directions</button>' +
                                    '</div>');
                            },1000);
                        }
                        else {
                            swal({title: "Error!", text: data.status, icon: "error", button: "error!"});
                        }

                    },
                    error: function (xhr) {
                        console.log("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            // }
            // else{
            //   // alert("Please select case");
            //     swal({title: "Error!",text: "Please select case",icon: "error",button: "error!"});
            //     $('#checkbox_'+ checkbox_no).css('outline-color', 'red');
            //     $('#checkbox_'+ checkbox_no).css('outline-style', 'solid');
            //     $('#checkbox_'+ checkbox_no).css('outline-width', 'medium');
            //     return false;
            // }
        }
        //bulk
       else if(updation_method == 'bulk') {
            var chk_count = 0; var count_success = 0; var count_error = 0;
            var update_flag = $(this).data('update_flag');
            var mainhead = $("input[name='mainhead']:checked").val();
            var listing_dts  = $("select#listing_dts option:selected").val();
            var board_type = $("select#board_type option:selected").val();
            var jud_ros = $("select#jud_ros option:selected").val();
            var part_no = $("select#part_no option:selected").val();
            var main_supp_flag = $("input[name='main_supp_list']:checked").val();
            var entry_date = "";
            var roster_id ='';
            var judges ='';
            if(jud_ros){
                jud_rosArr = jud_ros.split('|');
                if(jud_rosArr[0]){
                    judges = jud_rosArr[0];
                }
                if(jud_rosArr[1]){
                    roster_id = jud_rosArr[1];
                }
            }
            var update_flag_text = "";
            if (update_flag == "P") {
                update_flag_text = "Physical";
            }
            if (update_flag == "V") {
                update_flag_text = "VC";
            }
            if (update_flag == "H") {
                update_flag_text = "Hybrid";
            }
            var diaryConnKeyArr = [];
            var tmpObject = {};
            var diary_no ='';
            var conn_key ='';
            var courtno ='';
            var next_dt ='';
            var list_type_id = '';
            $('input[type=checkbox]').each(function (){
                if($(this).attr("name")=="chk" && $(this).is(':checked')){
                    chk_count++;
                    diary_no = $(this).data('diary_no');
                    conn_key = $(this).data('conn_key');
                    entry_date = $(this).data('entry_date');
                    next_dt = $(this).data('next_dt');
                    courtno = $(this).data('courtno');
                    list_type_id = $(this).data('list_type_id');
                    tmpObject = {};
                    tmpObject.diary_no = diary_no;
                    tmpObject.conn_key = conn_key;
                    tmpObject.entry_date = entry_date;
                    tmpObject.next_dt = next_dt;
                    tmpObject.courtno = courtno;
                    tmpObject.list_type_id = list_type_id
                    diaryConnKeyArr.push(tmpObject);
                }
            });
            if(chk_count == 0){
                swal({title: "Error!",text: "Atleast one case should be selected",icon: "error",button: "error!"});
                return false;
            }
            swal({
                title: "Are you sure?",
                text: "You want to update "+chk_count+" cases for "+update_flag_text+" hearing",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $(".result_action_loader").html('Processing <i class="fas fa-sync fa-spin"></i>');
                        $.ajax({
                                url: 'consent_physical_virtual_hybrid_save.php',
                                cache: false,
                                async: true,
                                type: 'POST',
                                dataType: "json",
                                data: {
                                    diaryConnKeyArr: diaryConnKeyArr,
                                    mainhead: mainhead,
                                    next_dt: listing_dts,
                                    board_type: board_type,
                                    roster_id: roster_id,
                                    judges: judges,
                                    clno:part_no,
                                    main_supp_flag:main_supp_flag,
                                    updation_method: updation_method,
                                    update_flag: update_flag
                                },
                                beforeSend: function () {
                                     $(this).html(update_flag_text+' <i class="fas fa-sync fa-spin"></i>');
                                },
                                success: function (data) {
                                    count_success = data.count_success;
                                    count_error = data.count_error;
                                    if (data.status == 'success') {
                                        $(".result_action").html("Success <span class='badge badge-secondary'>"+count_success+"</span> out of <span class='badge badge-secondary'>"+chk_count+"</span> cases");
                                        $(".result_success_count").val(count_success);
                                        var successData = data.successArr;
                                        var button_id ='';
                                        var conn_key = '';
                                        var diary_no ='';
                                        var success = '';
                                        var entry_date = '';
                                        var courtno = '';
                                        if(successData.length >0){
                                            for(var i=0;i<successData.length;i++){
                                                 diary_no = successData[i]['diary_no'];
                                                 success = successData[i]['success'];
                                                 entry_date = successData[i]['entry_date'];
                                                 courtno = successData[i]['courtno'];
                                                if(success && success == '1'){
                                                   button_id =  $("#d_"+diary_no).find('.physical_vc_hybrid_button').children('.btn-secondary').attr('id');
                                                   conn_key =  $("#d_"+diary_no).find('.physical_vc_hybrid_button').children('.btn-secondary').attr('data-conn_key');
                                                   var removeDirectionId ='';
                                                   if(button_id){
                                                       button_idArr = button_id.split('_');
                                                       if(button_idArr[1]){
                                                           removeDirectionId  = button_idArr[1];
                                                       }
                                                   }
                                                   if(removeDirectionId){
                                                       $("#"+'removeDirection_'+removeDirectionId).closest('.physical_vc_hybrid_remove_direction').remove();
                                                       $("#checkbox_" + checkbox_no).attr('data-entry_date',entry_date);
                                                       $("#d_"+diary_no).find('.physical_vc_hybrid_button').after('<div class="form-group mt-1 physical_vc_hybrid_remove_direction">' +
                                                           '<button id="removeDirection_'+removeDirectionId+'" data-updation_method="single" data-diary_no="'+diary_no+'" ' +
                                                           'data-conn_key="'+conn_key+'" data-entry_date="'+entry_date+'" data-next_dt="'+listing_dts+'" data-roster_id="'+roster_id+'" data-judges="'+judges+'" data-main_supp_flag="'+main_supp_flag+'" ' +
                                                           'data-mainhead="'+mainhead+'" data-courtno ="'+courtno+'" data-list_type_id="'+list_type_id+'" data-board_type="'+board_type+'" data-clno="'+part_no+'" class="btn btn-danger delete_action btn-block" type="button" name="delete_action" >Remove Directions</button>' +
                                                           '</div>');
                                                           $("#physical_" + removeDirectionId).addClass('btn-secondary').removeClass('btn-success');
                                                           $("#vc_" + removeDirectionId).addClass('btn-secondary').removeClass('btn-success');
                                                           $("#hybrid_" + removeDirectionId).addClass('btn-secondary').removeClass('btn-success');
                                                           if(update_flag == 'P'){
                                                               $("#physical_" + removeDirectionId).addClass('btn-success').removeClass('btn-secondary');
                                                           }
                                                           else if(update_flag == 'V'){
                                                               $("#vc_" + removeDirectionId).addClass('btn-success').removeClass('btn-secondary');
                                                           }
                                                           else if(update_flag == 'H'){
                                                               $("#hybrid_" + removeDirectionId).addClass('btn-success').removeClass('btn-secondary');
                                                           }
                                                   }
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        $(".result_action").html("Error <span class='badge badge-secondary'>"+count_error+"</span> out of <span class='badge badge-secondary'>"+chk_count+"</span> cases");
                                        $(".result_success_count").val(count_error);
                                    }
                                },
                                error: function (xhr) {
                                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                                }
                            });
                       setTimeout(function() {
                        var result_success_count = $('.result_success_count').val();
                        if(result_success_count > 0){
                            $(".result_action_loader").html('');
                            swal({title: "Success!",text: result_success_count+" Cases Updated Successfully",icon: "success",button: "success!"}).then(function(){
                                }
                            );
                        }
                        else{
                            swal({title: "Error!",text: "Not Updated",icon: "error",button: "error!"});
                        }
                    }, 2000);
                } else {
                    swal("Cancelled", "Please try again", "error");
                }
            });

        }

    });





</script>
