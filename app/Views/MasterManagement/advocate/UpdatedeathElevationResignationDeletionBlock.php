<?= view('header') ?>
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>
    .card-title {
    float: none;
}
.required-field::after {
            content: "*";
            color: red;
        }

        .error{
            color: red;
        }

.lebelfrom{
    padding-right: 13px;
    margin-top: 4px;
}
</style>
<div class="container mt-4">

    <?php if (session()->getFlashdata('message_success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message_success'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message_error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('message_error'); ?>
        </div>
    <?php endif; ?>

</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Update Death/Elevation/Resignation/Deletion/Block</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">

                                    <div class="d-block text-center">
                                        <!--<span class="btn btn-danger">Add Menus/ Child</span>-->
                                        <div class="alert alert-success hide" role="alert" id="msgDiv">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong></strong>
                                        </div>
                                            <div id="loginbox" style="margin-top:20px;" class="mainbox">
                                                <div class="panel panel-info" id="addMenusDiv">
                                                    <div style="margin-top: 10px" class="panel-body">
                                                                                            
                                                    <div class="container">
                                                <h1 class="my-4">Update Death/Elevation/Resignation/Deletion/Block</h1>
                                                <form id="updateForm" method="post" action="<?= site_url('updatecontroller/getdetails') ?>">
                                                    <div class="row">
                                                        <!-- Radio State -->
                                                        <div class="col-md-2 d-flex align-items-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="radio_state_aor" id="radio_state" value="S">
                                                                <label class="form-check-label lebelfrom" for="radio_state" style="display: inline-block !important;">State</label>
                                                            </div>
                                                        </div>

                                                        <!-- State Select -->
                                                        <div class="col-md-2">
                                                            <select id="state" class="form-control" name="state" disabled>
                                                                <option value="">Select</option>
                                                                <?php foreach ($state_name as $state): ?>
                                                                    <option value="<?= $state['id_no'] ?>"><?= $state['name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <!-- Enrollment No -->
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" maxlength="6" id="enrol" name="enrol" placeholder="Enrollment No." disabled>
                                                        </div>

                                                        <!-- Year -->
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" maxlength="4" id="year" name="year" placeholder="Year" disabled>
                                                        </div>

                                                        <!-- Radio AOR Code -->
                                                        <div class="col-md-2 d-flex align-items-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="radio_state_aor" id="radio_aor" value="A">
                                                                <label class="form-check-label lebelfrom" for="radio_aor" style="display: inline-block !important;">AOR Code</label>
                                                            </div>
                                                        </div>

                                                        <!-- AOR Code -->
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" name="aor_code" id="aor_code" maxlength="6" placeholder="AOR Code" disabled>
                                                        </div>

                                                        <!-- Get Details Button -->
                                                        <div class="col-md-12 d-flex justify-content-center">
                                                            <button type="button" class="btn btn-primary my-2" id="get-record">Get Details</button>
                                                        </div>
                                                    </div>
                                                    <!-- <div id="message"></div> -->
                                                    <div id="result"></div>
                                                </form>
                                                 </div>
                                                </div>
                                            </div>
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
<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>

<script>

$(document).ready(function(){
    $("#radio_state").click(function(){
        $("#state").prop('disabled',false);
        $("#enrol").prop('disabled',false);
        $("#year").prop('disabled',false);
        $("#aor_code").prop('disabled',true);
        $("#state").val("");
        $("#enrol").val("");
        $("#year").val("");
        $("#aor_code").val("");
        
    });
    
    $("#radio_aor").click(function(){
        $("#state").prop('disabled',true);
        $("#enrol").prop('disabled',true);
        $("#year").prop('disabled',true);
        $("#aor_code").prop('disabled',false);
        $("#state").val("");
        $("#enrol").val("");
        $("#year").val("");
        $("#aor_code").val("");
    });
    
    $("#get-record").click(function(){
        get_adv_d();
    });
});

function get_adv_d(){

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if($("#radio_state").is(':checked')){
        if($("#state").val()==""){
            alert("Please Select State");
            $("#state").focus();
            return false;
        }
        if($("#enrol").val()==""){
            alert("Please Fill Enrollment No.");
            $("#enrol").focus();
            return false;
        }
        if($("#year").val()==""){
            alert("Please Fill Enrollment Year");
            $("#year").focus();
            return false;
        }
    }
    else if($("#radio_aor").is(':checked')){
        if($("#aor_code").val()==""){
            alert("Please Fill AOR Code");
            $("#aor_code").focus();
            return false;
        }
    }
    else{
        alert('Please Select Any Option');
        return false;
    }
    
    $.ajax({
        type: 'POST',
        // url: "get_update_de.php", old_url
        url: baseURL + "/MasterManagement/Advocate/getdetailsUDERDB", 
        data:{state:$("#state").val(),enroll:$("#enrol").val(),year:$("#year").val(),aor:$("#aor_code").val()},
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
     })
    .done(function(msg){
        updateCSRFToken();
        $("#result").html(msg);
        
    })
    .fail(function(){
        updateCSRFToken();
        alert("Error Occured, Please Contact Server Room");
    });
}



$(document).on("click","#btnout",function(){
    save_data();
    updateCSRFToken();
});

function save_data(){
    
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var dead = $("#adv_dead").val();
    var enroll = $("#hd_enr").val();
    var year = $("#hd_en_yr").val();
    var state = $("#hd_state").val();
    var aor = $("#hd_aor").val();
    console.log(dead,enroll,year,state,aor);



    $("#btnout").attr("disabled","true");
    // document.getElementById('result').innerHTML = "<div style='text-align:center'><img src='loading1.gif'/></div>";
    $.ajax({
        type: 'POST',
        url: baseURL + "/MasterManagement/Advocate/updateRecord", 
        // beforeSend: function (xhr) {
        //     document.getElementById('result').innerHTML = "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>";
        // },
        data: {
            dead: dead,
            enroll: enroll,
            year: year,
            state: state,
            aor: aor
        },
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
    })
    .done(function(response){
        updateCSRFToken();
        $("#btnout").attr("disabled","false");
        var resultColor;
        if (response.status === 200) {
            resultColor = "green"; 
        } else {
            resultColor = "red"; 
        }
        $("#result").html("<div style='color: " + resultColor + ";'>" + response.message + "</div>");

    })
    .fail(function(response){
        updateCSRFToken();
        alert(response.message);
    });
}



 

</script>
