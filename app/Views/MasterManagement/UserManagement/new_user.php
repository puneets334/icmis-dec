<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">New User</h3>
                            </div>
                            <?//= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="text-center">
                            <?php
                            echo form_open();
                            csrf_token();
                            ?>
                            <div id="dv_content1">
                                <input type="hidden" id="hd_id_for_usernew">
                                <div class="top1">
                                    <div style="margin-bottom: 5px;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><input type="radio" name="user_func" value="N"
                                                        onclick="toggleUser_fun(this.value)" checked id="rdbtn_n" /> New
                                                    User </label>
                                                <label>
                                                    <input type="radio" name="user_func" value="U"
                                                        onclick="toggleUser_fun(this.value)" id="rdbtn_u" />Update
                                                    Existing</label>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="for_up_users"  class="form-control select2" disabled 
                                                    onchange="edit_user_set_fields(this.value)">
                                                    <option value="0">Select</option>
                                                    <?php
                                                    if ($new_user != 0) {
                                                        foreach ($new_user as $row_aluser) {
                                                    ?>
                                                            <option value="<?php echo $row_aluser['usercode']; ?>">
                                                                <?php echo $row_aluser['name'] . ' = ' . $row_aluser['type_name'] . ' = ' . $row_aluser['empid']; ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="inner_1" id="hmm">
                                            <table style="text-align: left;width: 100%">
                                                <tr>
                                                    <td>User Department</td>
                                                    <td>
                                                        <select id="_userdept_nu" class="form-control" onchange="setUSDF_ut(this.value,'A')">
                                                            <option value="0">Select</option>
                                                            <?php
                                                            foreach ($result_dept as $dept_row) {
                                                                echo '<option value="' . $dept_row['id'] . '">  ' . $dept_row['dept_name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>User Section</td>
                                                    <td>
                                                        <select id="_usersec_nu" class="form-control">
                                                            <option value="0">Select</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>User Type</td>
                                                    <td>
                                                        <select id="_usertype_nu" class="form-control">
                                                            <option value="0">Select</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:center">User Login and Password
                                                        Initially will be same as Employee id</td>
                                                </tr>
                                                <tr>
                                                    <td>Employee ID</td>
                                                    <td>
                                                        <div>
                                                            <label>
                                                                <input type="radio" value="J" name="emp_type" id="rdbtn_j" /> Judge</label>
                                                            <label><input type="radio" value="E" name="emp_type"
                                                                    id="rdbtn_e" checked /> Employee</label>
                                                        </div>
                                                        <span
                                                            style="font-size: 12px;display: block;padding: 0px;margin-top: -5px; color: red">Please
                                                            put 0 if you don't know EMP ID</span>
                                                        <input type="text" id="_userempid_nu" onkeypress="" class="form-control" maxlength="9" onblur="getEmpINFO()" style="width: 60%;display:inline;" /> 
                                                        <input type="button" value="Reset" onclick="emp_reset()" class="btn btn-primary" style="display: inline;" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Emp ID <span style="color: red">[fill temporarily]</span></td>
                                                    <td><input type="text" id="temp_empid" disabled="" maxlength="9" class="form-control" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Name</td>
                                                    <td><input type="text" id="_username_name_nu" class="form-control" onkeypress="return onlyalphab(event)" /> </td>
                                                </tr>
                                            </table>
                                            <input type="button" value="Add New" id="btnMain" onclick="add_user()" />
                                            <input type="button" value="Cancel" id="btnCan" onclick="cancel_op_newuser()" style="background-color:#ffc107 !important;" />
                                        </div>
                                    </div>
                                    <div class="add_result"></div>
                                    <div id="result_main"></div>
                                </div>
                                <?php
                                echo form_close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script>

    $('#for_up_users').select2();

    function onlyalphab(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 40 ||
            charCode == 41 ||
            charCode == 37 || charCode == 39 || charCode == 44) {
            return true;
        }
        return false;
    }

    function onlyalphab_with_dash(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
            charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 40 ||
            charCode == 41 ||
            charCode == 37 || charCode == 39 || charCode == 44 || charCode == 45) {
            return true;
        }
    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
            return true;
        }
        return false;
    }


    function setMGMTF(value, str, aray_args) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        if (value != 0) {
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
                    data: {
                        CSRF_TOKEN: csrf,
                        mat: 4,
                        val: value
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    var msg2 = msg.split('~');
                    if (msg2[3] != '' && msg2[4] != '') {
                        if (msg2[5] < msg2[3] || msg2[5] > msg2[4]) {
                            $("#oversh").css("display", "inline");
                            $("#btnMain").css("display", "none");
                        } else {
                            $("#oversh").css("display", "none");
                            $("#btnMain").css("display", "inline");
                        }
                    } else {
                        $("#oversh").css("display", "none");
                        $("#btnMain").css("display", "inline");
                    }
                    if (str == 'A') {
                        $("#_userempid_nu").removeAttr("disabled");
                        $("#_userempid_nu").val("");
                        $("#_username_name_nu").removeAttr("disabled");
                        $("#_username_name_nu").val("");
                    } else if (str == 'U') {
                        $("#oversh").css("display", "none");
                        $("#btnMain").css("display", "inline");
                        $("#_userempid_nu").val(aray_args[10]);
                        $("#_userempid_nu").attr("disabled", "true");
                        $("#_username_name_nu").val(aray_args[9]);
                        $("#_username_name_nu").attr("disabled", "true");
                    }
                    
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        } else {
            $("#oversh").css("display", "none");
            $("#btnMain").css("display", "inline");
            if (str == 'A') {
                $("#_userempid_nu").removeAttr("disabled");
                $("#_userempid_nu").val("");
                $("#_username_name_nu").removeAttr("disabled");
                $("#_username_name_nu").val("");
            }
        }
    }

    function setUSDF_ut(val, str) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        if (val != 0) {
            var newval = val.split('~');
        } else {
            $("#_usertype_nu").val("0");
            $("#_userempid_nu").removeAttr("disabled");
            $("#_userempid_nu").val("");
            $("#_username_name_nu").removeAttr("disabled");
            $("#_username_name_nu").val("");
        }
        $.ajax({
                type: 'GET',
                async: true,
                url: "<?php echo base_url('/MasterManagement/UserManagement/newuser_manage'); ?>",
                data: {
                    CSRF_TOKEN: csrf,
                    mat: 5,
                    utype: newval[0]
                }
            })
            .done(function(msg) {
                updateCSRFToken();
                if (str == 'A') {
                    $("#_usersec_nu").html(msg);
                } else {
                    $("#_usersec_nu").html(msg);
                    $("#_usersec_nu").val(str);
                }

                $.ajax({
                        type: 'GET',
                        async: true,
                        url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
                        data: {
                            CSRF_TOKEN: csrf,
                            mat: 7,
                            udept: newval[0]
                        }
                    })
                    .done(function(msg) {
                        $("#_usertype_nu").html(msg);
                    })
                    .fail(function() {
                        alert("ERROR, Please Contact Server Room");
                    });
            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }


    function getEmpINFO() {

        var radio = '';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        if ($("#rdbtn_e").is(":checked") == true)
            radio = $("#rdbtn_e").val();
        else
            radio = $("#rdbtn_j").val();
        $.ajax({
                type: 'GET',
                url: "<?php echo base_url('MasterManagement/UserManagement/user_mgmt_multiple'); ?>?key=8",
                data: {
                    CSRF_TOKEN: csrf,
                    empid: $("#_userempid_nu").val(),
                    service: radio
                }
            })
            .done(function(msg) {
                if (msg == '0') {
                    $("#_username_name_nu").val("No Record Found");
                    $("#_userempid_nu").val("0");
                    $("#_userempid_nu").attr("disabled", "true");
                    $("#_username_name_nu").focus();
                    $("#temp_empid").removeAttr("disabled");
                } else {
                    $("#_username_name_nu").val(msg);
                    $("#_userempid_nu").attr("disabled", "true");
                    $("#_username_name_nu").attr("disabled", "true");
                    $("#_username_name_nu").focus();
                    $("#temp_empid").attr("disabled", "true");
                }
            })
            .fail(function() {
                alert("ERROR, Please Contact Server Room");
            });
    }

    function emp_reset() {
        $("#_username_name_nu").val("");
        $("#_userempid_nu").val("");
        $("#_userempid_nu").removeAttr("disabled");
        $("#_username_name_nu").removeAttr("disabled");
        $("#temp_empid").attr("disabled", "true");
        $("#temp_empid").val("");
    }

    function add_user() {
        var radio = '';
        if ($("#rdbtn_e").is(":checked") == true)
            radio = $("#rdbtn_e").val();
        else
            radio = $("#rdbtn_j").val();

        if ($("#_userdept_nu").val() == '0') {
            alert("Please Select User Department");
            $("#_userdept_nu").focus();
            return false;
        }
        if ($("#_usersec_nu").val() == '0') {
            alert("Please Select User Section");
            $("#_usersec_nu").focus();
            return false;
        }
        if ($("#_usertype_nu").val() == '0') {
            alert("Please Select User Type");
            $("#_usertype_nu").focus();
            return false;
        }
        if ($("#_userempid_nu").val().trim() == '') {
            alert("Please Enter Employee ID");
            $("#_userempid_nu").focus();
            return false;
        }
        var reg123 = new RegExp('^[0-9]+$');

        if ($("#_userempid_nu").val() == 0) {
            if ($("#temp_empid").val().trim() == 0 || $("#temp_empid").val().trim() == '') {
                alert("Please Enter Temporary Employee ID");
                $("#temp_empid").focus();
                return false;
            }
            if (!reg123.test($("#temp_empid").val().trim())) {
                alert("Please Enter Temporary Employee ID Numerics Only");
                $("#temp_empid").focus();
                return false;
            }
        }

        if ($("#_username_name_nu").val().trim() == '' || $("#_username_name_nu").val().trim() == 'No Record Found') {
            alert("Please Enter User's Name");
            $("#_username_name_nu").focus();
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
                async: false,
                data: {
                    CSRF_TOKEN: csrf,
                    mat: 1,
                    func: 1,
                    udept: $("#_userdept_nu").val(),
                    usec: $("#_usersec_nu").val(),
                    utype: $("#_usertype_nu").val(),
                    empid: $("#_userempid_nu").val(),
                    temp_empid: $("#temp_empid").val(),
                    name: $("#_username_name_nu").val(),
                    service: radio
                }
            })
            .done(function(msg) {
                updateCSRFToken();
                var msg2 = msg.split('~');
                if (msg2[0] == 1) {
                    $(".add_result").css("display", "block");
                    $(".add_result").css("color", "green");
                    $(".add_result").html(msg2[1]);
                    $("#_userdept_nu").val("0");
                    $("#_usertype_nu").val("0");
                    $("#_usersec_nu").val("0");
                    emp_reset();
                    $(".add_result").slideUp(3000);
                } else {
                    $(".add_result").css("display", "block");
                    $(".add_result").css("color", "red");
                    $(".add_result").html(msg);
                }
            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }

    // function mark_spl_mgmt(value) {
    //     var val = value.split("~");
    //     if (val[0] == 0) {
    //         var t_val = $("#_usertype_nu").val();
    //         setMGMTF(t_val, '0');
    //         setUSDF_ut($("#_userdept_nu").val(), t_val);
    //         $("#_userempid_nu").removeAttr("disabled");
    //         $("#_userempid_nu").val("");
    //         $("#_username_name_nu").removeAttr("disabled");
    //         $("#_username_name_nu").val("");
    //     } else {
    //         $("#_userempid_nu").removeAttr("disabled");
    //         $("#_userempid_nu").val("");
    //         $("#_username_name_nu").removeAttr("disabled");
    //         $("#_username_name_nu").val("");
    //         var userside = $("#_userdept_nu").val().split('~');

    //         if (val[0] == 1) {
    //             $("#_userempid_nu").val("0");
    //             $("#_userempid_nu").attr("disabled", "true");
    //             $("#_username_name_nu").val("Sub-Section");
    //             $("#_username_name_nu").attr("disabled", "true");
    //         }
    //     }
    // }

    function toggleUser_fun(value) {
        if (value == 'N') {
            $("#for_up_users").attr("disabled", "true");
            $("#for_up_users").val(0);
        } else if (value == 'U')
            $("#for_up_users").removeAttr("disabled");

        $("#_userdept_nu").val(0);
        $("#_usertype_nu").html("<option value=0>Select</option>");
        $("#_usersec_nu").html("<option value=0>Select</option>");
        $("#_userempid_nu").val("");
        $("#_userempid_nu").removeAttr("disabled");
        $("#_username_name_nu").val("");
        $("#_username_name_nu").removeAttr("disabled");
        $("#temp_empid").attr("disabled", "true");
        $("#temp_empid").val("");
        $("#btnMain").val("Add New");
        $("#btnMain").attr("onclick", "add_user()");
        $("#btnCan").css("display", "none");
    }

    function edit_user_set_fields(val) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                type: 'POST',
                async: false,
                url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
                data: {
                    mat: 3,
                    id: val,
                    CSRF_TOKEN : CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                var msg2 = msg.split('@');
                $("#hd_id_for_usernew").val(msg2[0]);
                $("#_userdept_nu").val(msg2[4]);
                //var newval = msg2[5].split('~');
                $("#btnMain").val("Update");
                $("#btnMain").attr("onclick", "edit_user()");
                $("#btnCan").css("display", "inline");

                newuser_manage_mat_5(msg2);
                
            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }

    async function newuser_manage_mat_5(msg2)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
            data: {
                mat: 5,
                utype: msg2[4],
                CSRF_TOKEN : CSRF_TOKEN_VALUE
            }
        })
        .done(function(msg) {
            $("#_usersec_nu").html(msg);
            $("#_usersec_nu").val(msg2[5]);

            $("#_userempid_nu").val(msg2[6]);
            $("#_userempid_nu").attr("disabled", "true");
            $("#temp_empid").attr("disabled", "true");
            $("#temp_empid").val("");
            $("#_username_name_nu").attr("disabled", "true");
            $("#_username_name_nu").val(msg2[1]);

            if (msg2[7] == 'E')
                $("#rdbtn_e").attr("checked", "true");
            else if (msg2[7] == 'J')
                $("#rdbtn_j").attr("checked", "true");

            //setMGMTF(msg2[3],'U',msg2);
            newuser_manage_mat_7(msg2);
            
        })
        .fail(function() {
            alert("ERROR, Please Contact Server Room");
        });
    }

    async  function newuser_manage_mat_7(msg2)
    {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('MasterManagement/UserManagement/newuser_manage'); ?>",
            data: {
                mat: 7,
                udept: msg2[4],
                CSRF_TOKEN : CSRF_TOKEN_VALUE
            }
        })
        .done(function(msg) {
            updateCSRFToken();
            $("#_usertype_nu").html(msg);
            $("#_usertype_nu").val(msg2[3]);
        })
        .fail(function() {
            updateCSRFToken();
            alert("ERROR, Please Contact Server Room");
        });
    }

    function edit_user() {
        var radio = '';
        if ($("#rdbtn_e").is(":checked") == true)
            radio = $("#rdbtn_e").val();
        else
            radio = $("#rdbtn_j").val();

        if ($("#_userdept_nu").val() == '0') {
            alert("Please Select User Department");
            $("#_userdept_nu").focus();
            return false;
        }
        if ($("#_usersec_nu").val() == '0') {
            alert("Please Select User Section");
            $("#_usersec_nu").focus();
            return false;
        }
        if ($("#_usertype_nu").val() == '0') {
            alert("Please Select User Type");
            $("#_usertype_nu").focus();
            return false;
        }
        if ($("#_userempid_nu").val().trim() == '') {
            alert("Please Enter Employee ID");
            $("#_userempid_nu").focus();
            return false;
        }
        var reg123 = new RegExp('^[0-9]+$');

        if ($("#_userempid_nu").val() == 0) {
            if ($("#temp_empid").val().trim() == 0 || $("#temp_empid").val().trim() == '') {
                alert("Please Enter Temporary Employee ID");
                $("#temp_empid").focus();
                return false;
            }
            if (!reg123.test($("#temp_empid").val().trim())) {
                alert("Please Enter Temporary Employee ID Numerics Only");
                $("#temp_empid").focus();
                return false;
            }
        }

        if ($("#_username_name_nu").val().trim() == '' || $("#_username_name_nu").val().trim() == 'No Record Found') {
            alert("Please Enter User's Name");
            $("#_username_name_nu").focus();
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
                type: 'GET',
                url: "<?php echo base_url('/MasterManagement/UserManagement/newuser_manage'); ?>",
                async: false,
                data: {
                    CSRF_TOKEN: csrf,
                    mat: 1,
                    func: 3,
                    udept: $("#_userdept_nu").val(),
                    usec: $("#_usersec_nu").val(),
                    utype: $("#_usertype_nu").val(),
                    empid: $("#_userempid_nu").val(),
                    temp_empid: $("#temp_empid").val(),
                    name: $("#_username_name_nu").val(),
                    usercode: $("#hd_id_for_usernew").val(),
                    service: radio
                }
            })
            .done(function(msg) {
                updateCSRFToken();
                var msg2 = msg.split('~');
                if (msg2[0] == 1) {
                    $(".add_result").css("display", "block");
                    $(".add_result").css("color", "green");
                    $(".add_result").html(msg2[1]);
                    $("#hd_id_for_usernew").val("");
                    $("#rdbtn_u").prop("checked", false);
                    $("#rdbtn_n").prop("checked", true);
                    $("#for_up_users").val(0);
                    $("#for_up_users").prop("disabled", true);
                    $("#_userdept_nu").val(0);
                    $("#_usertype_nu").val("0");
                    $("#_usersec_nu").val("0");
                    emp_reset();
                    $("#btnMain").val("Add New");
                    $("#btnMain").attr("onclick", "add_user()");
                    $("#btnCan").css("display", "none");
                    $(".add_result").slideUp(3000);
                } else {
                    $(".add_result").css("display", "block");
                    $(".add_result").css("color", "red");
                    $(".add_result").html(msg);
                }
                alert("Updated Successfully.")                
                location.reload();

            })
            .fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }

    function cancel_op_newuser() {
        $("#hd_id_for_usernew").val("");
        $("#rdbtn_u").prop("checked", false);
        $("#rdbtn_n").prop("checked", true);
        $("#for_up_users").val(0);
        $("#for_up_users").prop("disabled", true);
        $("#_userdept_nu").val(0);
        $("#_usersec_nu").val(0);
        $("#_usertype_nu").val(0);
        $("#_usersec_nu").html("<option value=0>Select</option>");
        $("#_userempid_nu").val("");
        $("#_userempid_nu").removeAttr("disabled");
        $("#_username_name_nu").val("");
        $("#_username_name_nu").removeAttr("disabled");
        $("#btnMain").val("Add New");
        $("#btnMain").attr("onclick", "add_user()");
       // $("#btnCan").css("display", "none");
        $(".add_result").slideUp();
    }
</script>