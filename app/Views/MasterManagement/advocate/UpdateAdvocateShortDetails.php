<?= view('header') ?>
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>
 
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
                                <h3 class="card-title">Master Management >> Advocate</h3>
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
                                                <h1 class="my-4">Update Advocate Short Details</h1>

                                                <form>
                                                 <?= csrf_field() ?>

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
                                                        <div class="col-md-12 d-flex justify-content-center mt-3">
                                                            <button type="button" class="btn btn-primary my-2" id="get-record">Get Details</button>
                                                        </div>
                                                    </div>
                                                    <!-- <div id="message"></div> -->
                                                    <div id="result" class="mt-3"></div>
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
        // url: "get_update_aor.php",
        url: baseURL + "/MasterManagement/Advocate/templateUpdateShortdetails", 
        data:{state:$("#state").val(),enroll:$("#enrol").val(),year:$("#year").val(),aor:$("#aor_code").val()},
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
       success :function(response){
        updateCSRFToken();
        $("#result").html(response);
        },
     error: function(xhr, status, error) {
        console.error(error);
        updateCSRFToken();
        alert(response.message);
        }
    
    });
}

$(document).on("click","#btnout",function(){
    //alert('ye mera india');
    save_data();
});

$(document).on("change","#adv_aor",function(){
    if(this.value == '' || this.value == 'N'){
        $("#row-aor-code").css('display','none');
        //$("#adv_aor_code").val("0");
    }
    else if(this.value == 'Y')
        $("#row-aor-code").css('display','table-row');
});


function save_data() {
    var out = $("#adv_aor").val();
    var title = $("#title").val();
    var name = $("#name").val();
    var mob = $("#adv_mob").val();
    var eml = $("#adv_email").val();
    var regNum = new RegExp('^[0-9]+$');
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    if ($("#title").val() == "") {
        alert('select title');
        document.getElementById('title').focus();
        return false;

    }
    if ($("#name").val() == "") {
        alert('enter name');
        document.getElementById('name').focus();
        return false;
    }
   
        if ($("#adv_aor").val() == 'Y') {
            if (document.getElementById('adv_aor_code').value == '' || document.getElementById('adv_aor_code').value == 0) {
                alert('Please Fill AOR Code');
                document.getElementById('adv_aor_code').focus();
                return false;
            }

            if (isNaN(document.getElementById('adv_aor_code').value)) {
                alert('Please Fill AOR Code in Numeric');
                document.getElementById('adv_aor_code').focus();
                return false;
            }
        }
       
        $("#btnout").attr("disabled", "true");
        //document.getElementById('result').innerHTML = "<div style='text-align:center'><img src='loading1.gif'/></div>";
        var aor_code = 0;
        if ($("#adv_aor").val() == 'Y')
            aor_code = document.getElementById('adv_aor_code').value;
        $.ajax({
            type: 'POST',
            url: baseURL + "/MasterManagement/Advocate/UpdateShortdetailsStore", 
            data: {
                adv_aor: out,
                enroll: $("#hd_enr").val(),
                year: $("#hd_en_yr").val(),
                state: $("#hd_state").val(),
                aor: $("#hd_aor").val(),
                title: title,
                name: name,
                mobile: mob,
                email: eml,
                aor_code: aor_code,
                adv_sen: $("#adv_sen").val(),
                address: $("#cadd").val(),
                city: $("#ccity").val(),
                aor_code_db: $("#aor_code_from_db").val()
            },
            headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
           })
            .done(function (msg) {
                updateCSRFToken();
                $("#btnout").attr("disabled", "false");
                $("#result").html(msg);
            })
            .fail(function () {
                updateCSRFToken();
                alert('Error Occured, Please Contact Server Room');
            });
    }

function remove_apos(value,id){
    var string = value.replace("'","");
    string = string.replace("&","and");
    $("#"+id).val(string);
}



 

</script>
