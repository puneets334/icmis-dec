<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">USER RANGE</h3>
                            </div>
                            <?//= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">

                        <?php
                        echo form_open();
                        csrf_token();
                        ?>
                        <?php //include('../mn_sub_menu.php'); 
                        ?>
                        <div id="dv_content1">

                            <input type="hidden" id="usertype_id_for_range">
                            <div class="top1">
                                <div style="font-size: 18px;font-weight: bold;margin-bottom: 10px">USER RANGE</div>
                                <div class="inner_1" id="hmm">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for=""> UserType</label>
                                            <select id="sel_utype" class="form-control">
                                                <option value="0">Select</option>
                                                <?php
                                                foreach ($select_type as $select_type_row) {
                                                ?>
                                                    <option value="<?php echo $select_type_row['id']; ?>">
                                                        <?php echo $select_type_row['type_name'] . '-' . $select_type_row['id']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Lower Range</label>
                                            <input type="text" id="low_range" class="form-control" maxlength="5" size="5" />
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Upper Range</label>
                                            <input type="text" id="up_range" class="form-control" maxlength="5" size="5" />
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <input type="button" value="Add New" id="btnMain" />
                                            <input type="button" value="Update" id="btnUp" />
                                            <input type="button" value="Cancel" id="btnCan" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="add_result"></div>
                            <div id="result_main" class="mt-5">
                                <div class="row">
                                    <?php
                                    if (count($getAllUserRange_full) > 0) {
                                        $sno = 1;
                                    ?>
                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>
                                                    <th>SNo.</th>
                                                    <th>UserType Name</th>
                                                    <th>Lower Range</th>
                                                    <th>Upper Range</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($getAllUserRange_full as $select_type_row) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $sno; ?></td>
                                                        <td><?php echo $select_type_row['type_name']; ?></td>
                                                        <td><?php echo $select_type_row['low']; ?></td>
                                                        <td><?php echo $select_type_row['up']; ?></td>
                                                        <td><input type="button" id="btnEdit<?php echo $select_type_row['id']; ?>" value="Edit" />
                                                        <input type="button" id="btnDelete<?php echo $select_type_row['id']; ?>" value="Remove" />
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $sno++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                </div>
                                <!-- <div class="add_result"></div> -->
                               
                            <?php
                                    } else {
                            ?>
                                <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
                            <?php
                                    }
                            ?>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $("#btnMain").click(async function() {
            if ($("#sel_utype").val() == '0') {
                alert("Please Select UserType");
                $("#sel_utype").focus();
                return false;
            }
            if ($("#low_range").val().trim() == '' || $("#low_range").val().trim() == '0') {
                alert("Lower Range could not Blank or Zero");
                $("#low_range").focus();
                return false;
            }
            if ($("#up_range").val().trim() == '' || $("#up_range").val().trim() == '0') {
                alert("Upper Range could not Blank or Zero");
                $("#up_range").focus();
                return false;
            }
            if (parseInt($("#low_range").val()) > parseInt($("#up_range").val())) {
                alert("Lower Range could not be Greater than Upper Range");
                $("#low_range").focus();
                return false;
            }
            await updateCSRFTokenSync();
            CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var csrf = $("input[name='CSRF_TOKEN']").val();
            var low = $("#low_range").val().trim();
            var up = $("#up_range").val().trim();
            var reg123 = new RegExp('^[0-9]+$');
            if (!reg123.test(low)) {
                alert("Please Enter Numeric Value Only");
                $("#low_range").focus();
                return false;
            }
            if (!reg123.test(up)) {
                alert("Please Enter Numeric Value Only");
                $("#up_range").focus();
                return false;
            }
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                    async: false,
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        mat: 1,
                        func: 1,
                        utype: $("#sel_utype").val(),
                        low: low,
                        up: up
                    }
                })
                .done(function(msg) {
                    
                    //alert("Added Successfully");
                    //location.reload();
                    if(msg == "USERRANGE ADDED SUCCESSFULLY"){
                        alert("Added Sucessfully");
                        location.reload();
                        return false;
                    }
                    else{
                        alert(msg);
                        return false;
                    }
                    return false;
                    var msg2 = msg.split('~');
                    if (msg2[0] == 1) {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "green");
                        $(".add_result").html(msg2[1]);
                        $("#sel_utype").val("0");
                        $("#low_range").val("");
                        $("#up_range").val("");
                        $(".add_result").slideUp(3000);
                        $.ajax({
                                type: 'POST',
                                url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                                data: {
                                    CSRF_TOKEN: csrf,
                                    mat: 2
                                }
                            })
                            .done(function(msg_new) {
                                $("#result_main").html(msg_new);
                            })
                            .fail(function() {
                                alert("ERROR, Please Contact Server Room");
                            });
                    } else {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "red");
                        $(".add_result").html(msg);
                    }
                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        });
        $("#btnUp").css("display", "none");
        $("#btnMain").css("display", "inline");
        $("#btnCan").css("display", "none");
        $("#btnCan").click(async function() {
            await updateCSRFTokenSync();
             CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $("#usertype_id_for_range").val("");
            $("#sel_utype").val("0");
            $("#low_range").val("");
            $("#up_range").val("");
            //$("#btnMain").val("Add New");
            //$("#btnMain").attr("onclick","add_range()");
            $("#btnUp").css("display", "none");
            $("#btnMain").css("display", "inline");
            $("#btnCan").css("display", "none");
            $(".add_result").slideUp();
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        mat: 2
                    }
                })
                .done(function(msg_new) {
                    $("#result_main").html(msg_new);
                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        });

        $("#btnUp").click(async function() {
            if ($("#sel_utype").val() == '0') {
                alert("Please Select UserType");
                $("#sel_utype").focus();
                return false;
            }
            if ($("#low_range").val().trim() == '' || $("#low_range").val().trim() == '0') {
                alert("Lower Range could not Blank or Zero");
                $("#low_range").focus();
                return false;
            }
            if ($("#up_range").val().trim() == '' || $("#up_range").val().trim() == '0') {
                alert("Upper Range could not Blank or Zero");
                $("#up_range").focus();
                return false;
            }
            if (parseInt($("#low_range").val()) > parseInt($("#up_range").val())) {
                alert("Lower Range could not be Greater than Upper Range");
                $("#low_range").focus();
                return false;
            }
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            await updateCSRFTokenSync();
            CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var low = $("#low_range").val().trim();
            var up = $("#up_range").val().trim();
            var reg123 = new RegExp('^[0-9]+$');
            if (!reg123.test(low)) {
                alert("Please Enter Numeric Value Only");
                $("#low_range").focus();
                return false;
            }
            if (!reg123.test(up)) {
                alert("Please Enter Numeric Value Only");
                $("#up_range").focus();
                return false;
            }
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                    async: false,
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        mat: 1,
                        func: 3,
                        utype: $("#sel_utype").val(),
                        low: low,
                        up: up,
                        id: $("#usertype_id_for_range").val()
                    }
                })
                .done(function(msg) {                   
                    // var CSRF_TOKEN = 'CSRF_TOKEN';
                    // var csrf = $("input[name='CSRF_TOKEN']").val();
                    if(msg == "USERRANGE UPDATED SUCCESSFULLY"){
                        alert("Updated Sucessfully");
                        location.reload();
                        return false;
                    }
                    else{
                        alert(msg);
                        return false;
                    }
                    return false;
                    var msg2 = msg.split('~');
                    if (msg2[0] == 1) {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "green");
                        $(".add_result").html(msg2[1]);
                        $("#sel_utype").val("0");
                        $("#low_range").val("");
                        $("#up_range").val("");
                        $(".add_result").slideUp(3000);
                        $("#btnCan").css("display", "none");
                        $("#btnUp").css("display", "none");
                        $("#btnMain").css("display", "inline");
                        $.ajax({
                                type: 'POST',
                                url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                                data: {
                                    CSRF_TOKEN: csrf,
                                    mat: 2
                                }
                            })
                            .done(function(msg_new) {
                                $("#result_main").html(msg_new);
                            })
                            .fail(function() {
                                alert("ERROR, Please Contact Server Room");
                            });
                    } else {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "red");
                        $(".add_result").html(msg);
                    }
                    updateCSRFToken();
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
                updateCSRFToken();
        });

        




    });



    // edikt
    $(document).on("click", "[id^='btnEdit']", async function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        await updateCSRFTokenSync();
        CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var num = this.id.split('btnEdit');
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    mat: 3,
                    id: num[1]
                }
            })
            .done(function(msg) {
                //updateCSRFToken();
                var msg2 = msg.split('~');
                $("#usertype_id_for_range").val(msg2[0]);
                $("#sel_utype").val(msg2[1]);
                $("#low_range").val(msg2[2]);
                $("#up_range").val(msg2[3]);
                //$("#btnMain").val("Update");
                //$("#btnMain").attr("onclick","edit_user_range()");
                $("#btnCan").css("display", "inline");
                $("#btnMain").css("display", "none");
                $("#btnUp").css("display", "inline");
            })
            .fail(function() {
                //updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
        //updateCSRFToken();
    });

    $(document).on("click", "[id^='btnDelete']", async function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        await updateCSRFTokenSync();
        CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var num = this.id.split('btnDelete');
        if (confirm("ARE YOU SURE TO REMOVE THIS USERRANGE") == true) {
            $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        mat: 1,
                        func: 2,
                        id: num[1]
                    }
                })
                .done(function(msg) {
                    alert("Removed Successfully");
                    location.reload();
                    var msg2 = msg.split('~');
                    if (msg2[0] == 1) {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "#90C695");
                        $(".add_result").html(msg2[1]);
                        $("#sel_utype").val("0");
                        $("#low_range").val("");
                        $("#up_range").val("");
                        $(".add_result").slideUp(3000);
                        $.ajax({
                                type: 'POST',
                                url: "<?php echo base_url('MasterManagement/UserManagement/userrange_manage') ?>",
                                data: {
                                    CSRF_TOKEN: csrf,
                                    mat: 2
                                }
                            })
                            .done(function(msg_new) {
                                $("#result_main").html(msg_new);
                            })
                            .fail(function() {
                                alert("ERROR, Please Contact Server Room");
                            });
                    } else {
                        $(".add_result").css("display", "block");
                        $(".add_result").css("color", "red");
                        $(".add_result").html(msg);
                    }
                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        }
    });
</script>