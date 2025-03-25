<?= view('header') ?>

<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
    <style>
        .mandatory ::after {
            content: "*";
            color: red;
        }

        #row-aor-code {
            display: none;
        }
    </style>
</head>

<body>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Record Room >>&nbsp; Advocate on Record >> &nbsp; Registration of new AOR</h3>
                                </div>
                            </div>
                        </div>
                        <br><br>

                        <form method="post" action="">
                            <?= csrf_field() ?>

                            <div id="dv_content1">

                                <table style="border-collapse: collapse;border-width: 2px;" align="center" cellspacing='4' cellpadding='6'>
                                    <tr>
                                        <th colspan='4'>Add New Advocate in CMIS </th>
                                    </tr>
                                    <tr>
                                        <td>State:<span style="color:red">*</span></td>
                                        <td><select id="adv_state" style="width: 100%;">
                                                <option value="">Select</option>
                                                <?php foreach ($state_list as $state_row): ?>
                                                    <option value="<?php echo $state_row['id_no']; ?>"><?php echo $state_row['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>AOR/NAOR:<span style="color:red">*</span></td>
                                        <td><select style="width: 163px" id='adv_aor'>
                                                <option value="">Select</option>
                                                <option value="Y">AOR</option>
                                                <option value="N">NAOR</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td>Enrollment No.:</td>
                                        <td><input type='text' size='20' maxlength="20" id="adv_enroll_no" onkeypress="return onlynumbersadv(event)" onkeyup="upper(this.id)" /></td>
                                        <td>Enrollment Date:</td>
                                        <td><!--<input type='text' size='20' id="adv_enroll_dt" onkeypress="return noinput(event)" onclick="javascript:NewCssCal(this.id,'yyyyMMdd')"/>-->
                                            <input type='text' size='20' id="adv_enroll_dt" onkeypress="return onlynumbersadv(event)" onkeyup="checkDate(this.value,this.id)" maxlength="10" placeholder="DD-MM-YYYY" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Title:<span style="color:red">*</span></td>
                                        <td><select id="adv_title" style="width: 163px;">
                                                <option value='0'>Select</option>
                                                <option value='Mr.'>Mr.</option>
                                                <option value='Mrs.'>Mrs.</option>
                                                <option value='Ms.'>Ms.</option>
                                                <option value='M/S'>M/S</option>
                                                <option value='Dr.'>Dr.</option>
                                            </select>
                                        </td>
                                        <td>Name:<span style="color:red">*</span></td>
                                        <td><input type="text" id="adv_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" /></td>
                                    </tr>
                                    <tr>
                                        <td>Father's/Husband's Name:</td>
                                        <td><input type="text" id="adv_f_h_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" /></td>
                                        <td>Relation:</td>
                                        <td><select id="adv_relation" style="width: 163px;">
                                                <option value='0'>Select</option>
                                                <option value='F'>Father</option>
                                                <option value='H'>Husband</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mother's Name:</td>
                                        <td><input type="text" id="adv_m_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" /></td>
                                        <td>Gender:</td>
                                        <td><select id="adv_sex" style="width: 163px;">
                                                <option value='0'>Select</option>
                                                <option value='M'>Male</option>
                                                <option value='F'>Female</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Cast:</td>
                                        <td><select id="adv_cast" style="width: 163px;">
                                                <option value='0'>Select</option>
                                                <option value='GEN'>GEN</option>
                                                <option value='OBC'>OBC</option>
                                                <option value='ST'>ST</option>
                                                <option value='SC'>SC</option>
                                            </select>
                                        </td>
                                        <td>Passing Year</td>
                                        <td><input type='text' size='20' maxlength="4" id="adv_year" onkeypress="return onlynumbers(event)" /></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth:</td>
                                        <td><input type='text' id="adv_dob" name="adv_dob" onkeypress="return onlynumbersadv(event)" onkeyup="checkDate(this.value,this.id)" maxlength="10" placeholder="DD-MM-YYYY" /></td>
                                        <td>Practice City:</td>
                                        <td><input type='text' size='20' id="adv_p_p" onkeyup="upper(this.id)" /></td>
                                    </tr>
                                    <tr>
                                        <td>Address:<span style="color:red">*</span></td>
                                        <td><input type='text' size='20' id="adv_address" onkeyup="upper(this.id)" /></td>
                                        <td>City:<span style="color:red">*</span></td>
                                        <td><input type='text' size='20' id="adv_city" onkeyup="upper(this.id)" /></td>
                                    </tr>
                                    <tr>
                                        <td>Mobile No.:</td>
                                        <td><input type='text' size='20' id="adv_mob1" maxlength="10" /></td>
                                        <td>Email ID:</td>
                                        <td><input type='text' size='20' id="adv_email" /></td>
                                    </tr>
                                    <tr>
                                        <td>IF Senior:</td>
                                        <td><select id="adv_sen">
                                                <option value="Y">YES</option>
                                                <option value="N" selected="">NO</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="center">
                                            <input type="button" value="Save" name='saveadvocate' />
                                            <input type="reset" value="Reset" onclick="window.location.reload()" />
                                        </td>
                                    </tr>
                                </table>
                                <div id="result" style="text-align: center"></div>
                                <div class="center" id="record"></div>

                            </div>
                        </form>


                    </div>
                </div>
            </div>

        </div>
    </section>
</body>

<script>
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


    function saveAdv() {
        if (document.getElementById('adv_state').value == '') {
            alert('Please Select State');
            document.getElementById('adv_state').focus();
            return false;
        }
        if (document.getElementById('adv_aor').value == '') {
            alert('Please Select AOR/NAOR');
            document.getElementById('adv_aor').focus();
            return false;
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

        if (document.getElementById('adv_mob1').value != '') {
            var regNum = new RegExp('^[0-9]+$');
            var mob = $("#adv_mob1").val();

            var len = document.getElementById('adv_mob1').value.length;
            if (len != 10) {
                alert('Mobile No Must be of 10 Digits');
                document.getElementById('adv_mob1').focus();
                return false;
            } else if (document.getElementById('adv_mob1').value == 0) {
                alert('Mobile No can not be Zero');
                document.getElementById('adv_mob1').focus();
                return false;
            } else if (!regNum.test(mob)) {
                alert("Please Use Numbers Only");
                $("#adv_mob1").focus();
                return false;
            }
        }

        if (document.getElementById('adv_email').value != '') {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('adv_email').value.match(mailformat)) {
                //return true;
            } else {
                alert('Please enter valid email');
                document.getElementById('adv_email').focus();
                return false;
            }
        }

        var xmlhttp;
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = xmlhttp.responseText;
                if (response.indexOf('successfully') > -1) {
                    alert(response);
                    window.location.reload();
                } else if (response.indexOf('DoB IS NOT A VALID DATE') > -1) {
                    alert(response);
                    document.getElementById('adv_dob').focus();
                    return;
                } else if (response.indexOf('ENROLMENT DATE IS NOT A VALID DATE') > -1) {
                    alert(response);
                    document.getElementById('adv_enroll_dt').focus();
                    return;
                } else {
                    alert(response);
                    return;
                }

            }
        }

        save_new_aor_records();
        
    }



    async function save_new_aor_records() {
        await updateCSRFTokenSync();

        var adv_tite = $('#adv_title').val();
        var adv_state = $('#adv_state').val();
        var adv_aor = $('#adv_aor').val();
        var adv_name = $('#adv_name').val();
        var adv_fhnm = $('#adv_f_h_name').val();
        var adv_rel = $('#adv_relation').val();
        var adv_sex = $('#adv_sex').val();
        var adv_cast = $('#adv_cast').val();
        var adv_dob = $('#adv_dob').val();
        var adv_year = $('#adv_year').val();
        var adv_address = $('#adv_address').val();
        var adv_city = $('#adv_city').val();
        var adv_enroll_no = $('#adv_enroll_no').val();
        var adv_enroll_dt = $('#adv_enroll_dt').val();
        var adv_mob1 = $('#adv_mob1').val();
        var adv_email = $('#adv_email').val();
        var adv_moth = $('#adv_m_name').val();
        var adv_pp = $('#adv_p_p').val();
        var adv_sen = $('#adv_sen').val();
        
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var tvap = adv_state + ";" +  adv_aor +  ";" + adv_enroll_no + ";"  + adv_enroll_dt + ";" +  adv_tite + ";" +  adv_name + ";" + adv_fhnm + ";" 
        + adv_rel + ";" + adv_moth + ";"  + adv_sex + ";" + adv_cast + ";" + adv_year + ";" + adv_dob + ";" + adv_p_p + ";"+ adv_city + ";" 
        + adv_address + ";" + adv_mob1 + ";" + adv_email + ";" + adv_sen  ;

        $.ajax({
            type: "POST",
            url: '<?= base_url('Record_room/advt_on_record/registernew_aor') ?>',
            data: {
                tvap: tvap,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            beforeSend: function() {
                $("#image").show();
            },

            complete: function() {
                $('#image').hide();
            },

            success: function(data) {

              $("#adv_title, #adv_state, #adv_aor, #adv_name, #adv_f_h_name, #adv_relation, #adv_sex, #adv_cast, #adv_dob, #adv_year").val("");
              $("#adv_address, #adv_city, #adv_enroll_no, #adv_enroll_dt, #adv_mob1, #adv_m_name, #adv_pp, #adv_cast, #adv_sen, #adv_email").val("");
                $('.center').html(data);
                $('#record').show();

            },

            error: function() {
                alert('Error');
            }


        });
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

    function onlynumbersadv(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 47 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) ||
            charCode == 9 || charCode == 8 || charCode == 45 || charCode == 40 || charCode == 41) {
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



    function checkDate(value, id) {
        if (value.length == '2' || value.length == '5') {
            value += '-';
            $("#" + id).val(value);
        }

    }
</script>