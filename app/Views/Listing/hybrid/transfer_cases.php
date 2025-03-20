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
    <title>Consent for Hearing - Transfer Cases</title>
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
                    <div class="card-header bg-info text-white font-weight-bolder">Consent for Hearing - Transfer Cases</div>
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

                            <div class="col-sm-2">
                                <label>Action</label>
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
    $(document).on('click','#button_search',function(){
        var listing_dts  = $("select#listing_dts option:selected").val();

        if(listing_dts == '-1'){
            $('#show_error').html('');
            $('#result').html('');
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select listing date</strong></div>');
            $("#listing_dts").focus();
            return false;
        }
        else{
            $("#result").html('');
            var postData = {};
            postData.listing_dts= listing_dts;
            $.ajax({
                url: './transfer_cases_get.php',
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


    $(document).on('click', '.btn_transfer', function () {

            var next_dt = $(this).data('next_dt');
            var old_roster_id = $(this).data('old_roster_id');
            var new_roster_id =  $(this).data('new_roster_id');

            swal({
                title: "Are you sure?",
                text: "Do you want to transfer record(s) ",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    var postData = {};
                    postData.next_dt=next_dt;
                    postData.old_roster_id=old_roster_id;
                    postData.new_roster_id = new_roster_id;
                    $.ajax({
                        url: './transfer_cases_save.php',
                        cache: false,
                        async: true,
                        data: postData,
                        beforeSend: function () {
                            $("#btn_transfer_" + old_roster_id).prop('disabled', true);
                            $("#btn_transfer_" + old_roster_id).html("Loading <i class='fas fa-sync fa-spin'></i>");
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function (data) {
                            if(data.status=='success') {

                                $("#transfer_result_"+old_roster_id).html("<span class='text-success'>SUCCESS</span>");
                                swal({title: "Success!", text: "Record(s) Successfully Transferred", icon: "success", button: "success!"}).then(function(){
                                    }
                                );
                            }
                            else {
                                $("#btn_transfer_"+old_roster_id).prop('disabled', false);
                                $("#btn_transfer_" + old_roster_id).html("Transfer");
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



    });
</script>
