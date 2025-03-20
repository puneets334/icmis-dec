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
                                <div class="form-div_">

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
                                                                                        
                                            <div class="container mt-4">
                                                    <form id="registrationcmis">
                                                   
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h2 class="card-title">Add New Advocate in CMIS</h2>
                                                            </div>
                                                            <div class="card-body">
                                                                <!-- Row 1: State and AOR/NAOR -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_state" class="col-sm-4 col-form-label required-field">State</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_state" class="form-control" name="adv_state" required>
                                                                                    <option value="">--Select--</option>
                                                                                    <?php foreach($state_name as $row) { ?>
                                                                                        <option value="<?php echo $row['id_no']; ?>"><?php echo $row['name']; ?></option>
                                                                                      <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_aor" class="col-sm-4 col-form-label required-field">AOR/NAOR</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_aor" name="adv_aor" class="form-control" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="Y">AOR</option>
                                                                                    <option value="N">NAOR</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 2: Enrollment No. and Enrollment Date -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_enroll_no" class="col-sm-4 col-form-label">Enrollment No.</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control onlyNumbers" name="adv_enroll_no" id="adv_enroll_no" maxlength="20">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_enroll_dt" class="col-sm-4 col-form-label">Enrollment Date</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control datepicker" name="adv_enroll_dt" id="adv_enroll_dt" maxlength="10" placeholder="DD-MM-YYYY">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 3: Title and Name -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_tite" class="col-sm-4 col-form-label required-field">Title</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_title" class="form-control" name="adv_tite" required>
                                                                                    <option value='0'>Select</option>
                                                                                    <option value='Mr.'>Mr.</option>
                                                                                    <option value='Mrs.'>Mrs.</option>
                                                                                    <option value='Ms.'>Ms.</option>
                                                                                    <option value='M/S'>M/S</option>
                                                                                    <option value='Dr.'>Dr.</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_name" class="col-sm-4 col-form-label required-field">Name</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase onlyalpha" name="adv_name" id="adv_name" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 4: Father's/Husband's Name and Relation -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_fhnm" class="col-sm-4 col-form-label">Father's/Husband's Name</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase onlyalpha" name="adv_fhnm" id="adv_fhnm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_relation" class="col-sm-4 col-form-label">Relation</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_relation"  name="adv_rel" class="form-control">
                                                                                    <option value='0'>Select</option>
                                                                                    <option value='F'>Father</option>
                                                                                    <option value='H'>Husband</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 5: Mother's Name and Gender -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_moth" class="col-sm-4 col-form-label">Mother's Name</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase onlyalpha" name="adv_moth" id="adv_moth">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_sex" class="col-sm-4 col-form-label">Gender</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_sex" class="form-control" name="adv_sex">
                                                                                    <option value='0'>Select</option>
                                                                                    <option value='M'>Male</option>
                                                                                    <option value='F'>Female</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 6: Cast and Passing Year -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_cast" class="col-sm-4 col-form-label">Cast</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_cast" class="form-control" name="adv_cast">
                                                                                    <option value='0'>Select</option>
                                                                                    <option value='GEN'>GEN</option>
                                                                                    <option value='OBC'>OBC</option>
                                                                                    <option value='ST'>ST</option>
                                                                                    <option value='SC'>SC</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_year" class="col-sm-4 col-form-label">Passing Year</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control onlyNumbers" name="adv_year" id="adv_year" maxlength="4" pattern="\d{4}" title="Please enter a 4-digit year" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 7: Date of Birth and Practice City -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_dob" class="col-sm-4 col-form-label">Date of Birth</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control datepicker"  id="date" name="adv_dob" maxlength="10" placeholder="DD-MM-YYYY">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_pp" class="col-sm-4 col-form-label">Practice City</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase" name="adv_pp"  id="adv_pp">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 8: Address and City -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_address" class="col-sm-4 col-form-label required-field">Address</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase" name="adv_address" id="adv_address" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_city" class="col-sm-4 col-form-label required-field">City</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control toUpperCase"  name="adv_city" id="adv_city" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 9: Mobile No. and Email ID -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_mob" class="col-sm-4 col-form-label">Mobile No.</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" class="form-control" name="adv_mob" id="adv_mob" maxlength="10">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_email" class="col-sm-4 col-form-label">Email ID</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="email" class="form-control" name="adv_email" id="adv_email">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Row 10: IF Senior -->
                                                                <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label for="adv_sen" class="col-sm-4 col-form-label">IF Senior</label>
                                                                            <div class="col-sm-8">
                                                                                <select id="adv_sen" class="form-control" name="adv_sen">
                                                                                    <option value="Y">YES</option>
                                                                                    <option value="N" selected>NO</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Buttons -->
                                                                <div class="form-group text-center">
                                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload()">Reset</button>
                                                                </div>
                                                                <div id="result" class="text-center mt-3"></div>
                                                            </div>
                                                        </div>
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


 $( function() {
    $( ".datepicker" ).datepicker();
  } );


  $('.toUpperCase').on('blur', function(){
    this.value = this.value.toUpperCase();
});



$(document).ready(function() {
    $('.onlyalpha').on('keypress', function(event) {
      onlyAlpha(event);
    });
    function onlyAlpha(event) {
      var charCode = event.which ? event.which : event.keyCode;
      if (charCode === 8 || charCode === 9 || charCode === 37 || charCode === 39) {
        return;
      }
      var char = String.fromCharCode(charCode);
      if (!/^[a-zA-Z]$/.test(char)) {
        event.preventDefault(); 
      }
    }
  });



  $(document).ready(function() {
    $('.onlyNumbers').on('keypress', function(event) {
      onlyNumbers(event);
    });
    function onlyNumbers(event) {
      var charCode = event.which ? event.which : event.keyCode;
      if (charCode === 8 || charCode === 9 || charCode === 37 || charCode === 39 || charCode === 46 || charCode === 190) {
        return;
      }
      var char = String.fromCharCode(charCode);
      if (!/^[0-9]$/.test(char)) {
        event.preventDefault(); 
      }
    }
  });




  $(document).ready(function(){
    $("input[name=saveadvocate]").click(function(){
        saveAdv();
    });
    $("#adv_aor").change(function(){
        if(this.value == '' || this.value == 'N'){
            $("#row-aor-code").css('display','none');
            $("#adv_aor_code").val('');
        }
        else if(this.value == 'Y')
        {
            $("#row-aor-code").css('display','table-row');
            $("#row-aor-code").css('display','table-row');

        }
    });
    
});




$(document).ready(function() {
    $('#registrationcmis').on('submit', function(event) {
        event.preventDefault(); 

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if ($('#adv_state').val() === '') {
            alert('Please Select State');
            $('#adv_state').focus();
            return false;
        }
        if ($('#adv_aor').val() === '') {
            alert('Please Select AOR/NAOR');
            $('#adv_aor').focus();
            return false;
        }
        if ($('#adv_title').val() === '0') {
            alert('Please Select Title');
            $('#adv_title').focus();
            return false;
        }
        if ($('#adv_name').val() === '' || /^[\s]+$/.test($('#adv_name').val())) {
            alert('Please fill Advocate Name');
            $('#adv_name').focus();
            return false;
        }
        
        if ($('#adv_relation').val() === '0' && $('#adv_f_h_name').val() !== '') {
            alert('Please Select Advocate Relation');
            $('#adv_relation').focus();
            return false;
        }
        if ($('#adv_address').val() === '' || /^[\s]+$/.test($('#adv_address').val())) {
            alert('Please fill Address');
            $('#adv_address').focus();
            return false;
        }
        if ($('#adv_city').val() === '' || /^[\s]+$/.test($('#adv_city').val()) || !/^[A-Za-z ]+$/.test($('#adv_city').val())) {
            alert('Please enter valid City');
            $('#adv_city').focus();
            return false;
        }
        var mob = $('#adv_mob').val();
        if (mob !== '') {
            if (mob.length !== 10) {
                alert('Mobile No Must be of 10 Digits');
                $('#adv_mob').focus();
                return false;
            } else if (!/^[0-9]+$/.test(mob)) {
                alert('Please Use Numbers Only');
                $('#adv_mob').focus();
                return false;
            }
        }
        var email = $('#adv_email').val();
        if (email !== '' && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            alert('Please enter valid email');
            $('#adv_email').focus();
            return false;
        }

        $.ajax({
            url: baseURL + "/MasterManagement/Advocate/registrationcmisStore", 
            type: "POST",
            data: $(this).serialize(),
            headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
             },
            success: function(response) {
                updateCSRFToken();
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        alert('Invalid response format.');
                        return;
                    }
                }
                if (response.status === 404 && response.errors) {
                    let errorMessages = [];
                    $.each(response.errors, function(field, message) {
                        errorMessages.push(message);
                    });
                    alert(errorMessages.join("\n"));
                } else if (response.status === 200) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message || 'An unexpected error occurred.');
                }
            },
            error: function() {
                updateCSRFToken();
                alert('An error occurred while processing your request.');
            }
        });
    });
});


function noinput(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if (charCode==9) {
    return true;
    }
    return false;
}

 

</script>
