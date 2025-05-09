<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }

    .required-field::after {
        content: " *";
        color: red;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >>Advocate </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Add New Advocate in CMIS</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="post" action="<?= site_url(uri_string()) ?>">
                                                <?= csrf_field() ?>
                                                <div class="row">

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_state" class=" col-form-label required-field">State</label>
                                                        <select id="adv_state" class="form-control" name="adv_state" required>
                                                            <option value="">--Select--</option>
                                                            <?php foreach ($state_name as $row) { ?>
                                                                <option value="<?php echo $row['id_no']; ?>"><?php echo $row['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_aor" class=" col-form-label required-field">AOR/NAOR</label>
                                                        <select id="adv_aor" name="adv_aor" class="form-control" required>
                                                            <option value="">Select</option>
                                                            <option value="Y">AOR</option>
                                                            <option value="N">NAOR</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_enroll_no" class=" col-form-label required-field">Enrollment No.</label>
                                                        <input type="text" class="form-control" name="adv_enroll_no" id="adv_enroll_no" maxlength="20">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_enroll_dt" class=" col-form-label required-field">Enrollment Date</label>
                                                        <input type="text" class="form-control dtp" name="adv_enroll_dt" id="adv_enroll_dt" maxlength="10" placeholder="DD-MM-YYYY" required />
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_tite" class=" col-form-label required-field">Title</label>
                                                        <select id="adv_title" class="form-control" name="adv_tite" required>
                                                            <option value='0'>Select</option>
                                                            <option value='Mr.'>Mr.</option>
                                                            <option value='Mrs.'>Mrs.</option>
                                                            <option value='Ms.'>Ms.</option>
                                                            <option value='M/S'>M/S</option>
                                                            <option value='Dr.'>Dr.</option>
                                                        </select>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_name" class=" col-form-label required-field">Name</label>
                                                        <input type="text" class="form-control toUpperCase " name="adv_name" id="adv_name" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_fhnm" class=" col-form-label">Father's/Husband's Name</label>
                                                        <input type="text" class="form-control toUpperCase " name="adv_fhnm" maxlength="60" id="adv_fhnm">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_relation" class=" col-form-label">Relation</label>
                                                        <select id="adv_relation" name="adv_rel" class="form-control">
                                                            <option value='0'>Select</option>
                                                            <option value='F'>Father</option>
                                                            <option value='H'>Husband</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_moth" class=" col-form-label">Mother's Name</label>
                                                        <input type="text" class="form-control toUpperCase onlyalpha"  maxlength="60" name="adv_moth" id="adv_moth">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_sex" class=" col-form-label">Gender</label>
                                                        <select id="adv_sex" class="form-control" name="adv_sex">
                                                            <option value='0'>Select</option>
                                                            <option value='M'>Male</option>
                                                            <option value='F'>Female</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_cast" class=" col-form-label">Cast</label>
                                                        <select id="adv_cast" class="form-control" name="adv_cast">
                                                            <option value='0'>Select</option>
                                                            <option value='GEN'>GEN</option>
                                                            <option value='OBC'>OBC</option>
                                                            <option value='ST'>ST</option>
                                                            <option value='SC'>SC</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_year" class=" col-form-label required-field">Passing Year</label>
                                                        <input type="text" class="form-control onlyNumbers" name="adv_year" id="adv_year" maxlength="4" pattern="\d{4}" title="Please enter a 4-digit year" required>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_dob" class="col-sm-4 col-form-label">Date of Birth</label>
                                                        <input type="text" class="form-control dtp" id="date" name="adv_dob" maxlength="10" placeholder="DD-MM-YYYY">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_pp" class="col-sm-4 col-form-label required-field">Practice City</label>
                                                        <input type="text" class="form-control toUpperCase" name="adv_pp" id="adv_pp"  maxlength="60" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_address" class="col-sm-4 col-form-label required-field">Address</label>
                                                        <input type="text" class="form-control toUpperCase" name="adv_address" id="adv_address"  maxlength="80" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_city" class="col-sm-4 col-form-label required-field">City</label>
                                                        <input type="text" class="form-control toUpperCase" name="adv_city" id="adv_city"  maxlength="60" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_mob" class="col-sm-4 col-form-label required-field">Mobile No.</label>
                                                        <input type="text" class="form-control" name="adv_mob" id="adv_mob" maxlength="10" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_email" class="col-sm-4 col-form-label">Email ID</label>
                                                        <input type="email" class="form-control" name="adv_email" id="adv_email">
                                                    </div>

                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <label for="adv_sen" class="col-sm-4 col-form-label">IF Senior</label>
                                                        <select id="adv_sen" class="form-control" name="adv_sen">
                                                            <option value="Y">YES</option>
                                                            <option value="N" selected>NO</option>
                                                        </select>
                                                    </div>


                                                    <div class="col-sm-12 col-md-3 mb-3 ">
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3 form-group text-center">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Save</button>
                                                        <button type="reset" class="btn btn-secondary" onclick="window.location.reload()">Reset</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="result" class="text-center mt-3"></div>
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
    $(document).ready(function()
    {
        $('input[required], select[required], textarea[required]').each(function() {
            var id = $(this).attr('id');
            if (id) {
                $('label[for="' + id + '"]').addClass('required-field');
            }
        });
    });

    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });


    $('.toUpperCase').on('blur', function() {
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




    $(document).ready(function() {
        $("input[name=saveadvocate]").click(function() {
            saveAdv();
        });
        $("#adv_aor").change(function() {
            if (this.value == '' || this.value == 'N') {
                $("#row-aor-code").css('display', 'none');
                $("#adv_aor_code").val('');
            } else if (this.value == 'Y') {
                $("#row-aor-code").css('display', 'table-row');
                $("#row-aor-code").css('display', 'table-row');

            }
        });

    });




    $(document).on("click", "#btn1", function() {
        // event.preventDefault();
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
        }

        if ($('#adv_enroll_no').val() === '') {
            alert('Please Select Enrollment No.');
            $('#adv_aor').focus();
        }

        if ($('#adv_enroll_dt').val() == '') {
            alert('Please Enter  Enrollment Date');
            $('#adv_enroll_dt').focus();
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



        // if ($('#adv_relation').val() === '0' && $('#adv_f_h_name').val() !== '') {
        //     alert('Please Select Advocate Relation');
        //     $('#adv_relation').focus();
        //     return false;
        // }
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
            data: $("form").serialize(),
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


    function noinput(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if (charCode == 9) {
            return true;
        }
        return false;
    }
</script>