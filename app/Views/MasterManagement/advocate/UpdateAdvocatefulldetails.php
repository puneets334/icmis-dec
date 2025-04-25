<?= view('header') ?>
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .lebelfrom {
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
                                                        <h1 class="my-4">Update Advocate Full Details</h1>

                                                        <form id="" action="<?php echo  current_url(); ?>">
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
                                                                    <input type="text" class="form-control" maxlength="4" id="year" name="year" placeholder="Year" max="4" disabled>
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
                                                                    <input type="number" class="form-control" name="aor_code" id="aor_code" maxlength="6" placeholder="AOR Code" disabled>
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
    $(document).ready(function() {
        $("#radio_state").click(function() {
            $("#state").prop('disabled', false);
            $("#enrol").prop('disabled', false);
            $("#year").prop('disabled', false);
            $("#aor_code").prop('disabled', true);
            $("#state").val("");
            $("#enrol").val("");
            $("#year").val("");
            $("#aor_code").val("");
        });

        $("#radio_aor").click(function() {
            $("#state").prop('disabled', true);
            $("#enrol").prop('disabled', true);
            $("#year").prop('disabled', true);
            $("#aor_code").prop('disabled', false);
            $("#state").val("");
            $("#enrol").val("");
            $("#year").val("");
            $("#aor_code").val("");
        });

        $("#get-record").click(function() {
            get_adv_d();
        });
    });

    function get_adv_d() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var dead = $("#adv_dead").val();
        var enroll = $("#hd_enr").val();
        var year = $("#hd_en_yr").val();
        var state = $("#hd_state").val();
        var aor = $("#hd_aor").val();


        if ($("#radio_state").is(':checked')) {
            if ($("#state").val() == "") {
                alert("Please Select State");
                $("#state").focus();
                return false;
            }
            if ($("#enrol").val() == "") {
                alert("Please Fill Enrollment No.");
                $("#enrol").focus();
                return false;
            }
            if ($("#year").val() == "") {
                alert("Please Fill Enrollment Year");
                $("#year").focus();
                return false;
            }
        } else if ($("#radio_aor").is(':checked')) {
            if ($("#aor_code").val() == "") {
                alert("Please Fill AOR Code");
                $("#aor_code").focus();
                return false;
            }
        } else {
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
                type: 'POST',
                // url: "get_update_full.php",
                url: baseURL + "/MasterManagement/Advocate/getUpdateFull",
                data: {
                    state: $("#state").val(),
                    state_name: $("#state option:selected").text(),
                    enroll: $("#enrol").val(),
                    year: $("#year").val(),
                    aor: $("#aor_code").val()
                },
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
            })
            .done(function(msg) {
                updateCSRFToken();
                $("#result").html(msg);
            })
            .fail(function() {
                updateCSRFToken();
                alert("Error Occured, Please Contact Server Room");
            });
    }

    $(document).on("click", "#updateadvocate", function() {
        saveAdv();
    });

    $(document).on("change", "#adv_aor", function() {
        if (this.value == '' || this.value == 'N') {
            $("#row-aor-code").css('display', 'none');
            $("#adv_aor_code").val("0");
        } else if (this.value == 'Y')
            $("#row-aor-code").css('display', 'table-row');
    });


    function saveAdv() {
        if (document.getElementById('adv_aor').value == '') {
            alert('Please Select AOR/NAOR');
            document.getElementById('adv_aor').focus();
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
        if (document.getElementById('adv_title').value == 0) {
            alert('Please Select Title');
            document.getElementById('adv_title').focus();
            return false;
        }
        if (document.getElementById('adv_name')) {
            var letters = /^[A-Za-z .]+$/;
            var space_only = /^[ ]+$/;
            if (document.getElementById('adv_name').value == '') {
                alert('Please fill Advocate Name');
                document.getElementById('adv_name').focus();
                return false;
            } else if (document.getElementById('adv_name').value.match(space_only)) {
                alert('Please fill Advocate Name');
                document.getElementById('adv_name').focus();
                return false;
            }
        }
        if (document.getElementById('adv_f_h_name')) {
            var letters = /^[A-Za-z .]+$/;
            var space_only = /^[ ]+$/;

            if (document.getElementById('adv_f_h_name').value != "") {
                if (document.getElementById('adv_f_h_name').value.match(space_only)) {
                    alert('Please fill Father/Husband Name');
                    document.getElementById('adv_f_h_name').focus();
                    return false;
                } else if (document.getElementById('adv_f_h_name').value.match(letters)) {
                    //do nothing
                } else {
                    alert('Please enter Alphabet Characters only');
                    document.getElementById('adv_f_h_name').focus();
                    return false;
                }
            }
        }
        if (document.getElementById('adv_relation').value == 0) {
            if (document.getElementById('adv_f_h_name').value != '') {
                alert('Please Select Advocate Relation');
                document.getElementById('adv_relation').focus();
                return false;
            }
        }

        if (document.getElementById('adv_address')) {
            var space_only = /^[ ]+$/;
            if (document.getElementById('adv_address').value == '') {
                alert('Please fill Address');
                document.getElementById('adv_address').focus();
                return false;
            } else if (document.getElementById('adv_address').value.match(space_only)) {
                alert('Please fill Address');
                document.getElementById('adv_address').focus();
                return false;
            }
        }
        if (document.getElementById('adv_city')) {
            var letters = /^[A-Za-z ]+$/;
            var space_only = /^[ ]+$/;
            if (document.getElementById('adv_city').value == '') {
                alert('Please fill City');
                document.getElementById('adv_city').focus();
                return false;
            } else if (document.getElementById('adv_city').value.match(space_only)) {
                alert('Please fill City');
                document.getElementById('adv_city').focus();
                return false;
            } else if (document.getElementById('adv_city').value.match(letters)) {
                //do nothing
            } else {
                alert('Please enter Alphabet Characters only');
                document.getElementById('adv_city').focus();
                return false;
            }
        }
        var regNum = new RegExp('^[0-9]+$');
        var mob = $("#adv_mob").val();
        if (document.getElementById('adv_mob').value != '') {
            var len = document.getElementById('adv_mob').value.length;
            if (len != 10) {
                alert('Mobile No Must be of 10 Digits');
                document.getElementById('adv_mob').focus();
                return false;
            } else if (document.getElementById('adv_mob').value == 0) {
                alert('Mobile No can not be Zero');
                document.getElementById('adv_mob').focus();
                return false;
            } else if (!regNum.test(mob)) {
                alert("Please Use Numbers Only");
                $("#adv_mob").focus();
                return false;
            }
        }
        if (document.getElementById('adv_email').value != '') {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('adv_email').value.match(mailformat)) {} else {
                alert('Please enter valid email');
                document.getElementById('adv_email').focus();
                return false;
            }
        }

        //return false;
        var xmlhttp;
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("result").innerHTML = xmlhttp.responseText;
            }
        }
        var url = baseURL + "/MasterManagement/Advocate/UpdateAdvBar/?adv_tite=" + document.getElementById('adv_title').value +
            "&adv_state=" + document.getElementById('hd_state').value +
            "&adv_aor=" + document.getElementById('adv_aor').value +
            "&adv_name=" + document.getElementById('adv_name').value +
            "&adv_fhnm=" + document.getElementById('adv_f_h_name').value +
            "&adv_rel=" + document.getElementById('adv_relation').value +
            "&adv_sex=" + document.getElementById('adv_sex').value +
            "&adv_cast=" + document.getElementById('adv_cast').value +
            "&adv_dob=" + document.getElementById('adv_dob').value +
            "&adv_year=" + document.getElementById('adv_year').value +
            "&adv_address=" + document.getElementById('adv_address').value +
            "&adv_city=" + document.getElementById('adv_city').value +
            "&adv_enroll_no=" + document.getElementById('hd_enr').value +
            "&adv_enroll_dt=" + document.getElementById('hd_en_yr').value +
            "&adv_mob=" + document.getElementById('adv_mob').value +
            "&adv_email=" + document.getElementById('adv_email').value +
            "&adv_moth=" + document.getElementById('adv_m_name').value +
            "&adv_pp=" + document.getElementById('adv_p_p').value +
            "&enroll_no=" + $("#enrollment_no").val() +
            "&enroll_date=" + $("#enrollment_date").val() +
            "&state=" + $("#adv_state").val() +
            "&aor=" + $("#hd_aor").val();
        if ($("#adv_aor").val() == 'Y')
            url += "&aor_code=" + document.getElementById('adv_aor_code').value;
        //alert(url);
        xmlhttp.open("GET", url, false);
        xmlhttp.send(null);
    }

    function noinput(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if (charCode == 9) {
            return true;
        }
        return false;
    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
            return true;
        }
        return false;
    }

    function upper(id) {
        document.getElementById(id).value = document.getElementById(id).value.toUpperCase();
    }

    function onlyalpha(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) ||
            charCode == 9 || charCode == 8 || charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 ||
            charCode == 40 || charCode == 41 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }

    function onlynumbersadv(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) ||
            charCode == 9 || charCode == 8 || charCode == 45) {
            return true;
        }
        return false;
    }

    function checkDate(value, id) {
        if (value.length == '2' || value.length == '5') {
            value += '-';
            $("#" + id).val(value);
        }

    }
</script>