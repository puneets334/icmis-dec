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
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Menu Privilege</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="addroleDiv">
                                            <div class="form-title-area">
                                                <div class="form-title-inner">
                                                    <span class="form-center-title">
                                                        <?php $action = 'Create';
                                                        echo $action; ?> <span class="text-danger">[ Group of Role ]</span>
                                                    </span>
                                                    <span class="menusList">
                                                        <a href="#" id="roleList" class="quick-btn"><i class="fa fa-list">&nbsp;</i>Alloted Role list</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <form method="post" action="<?= site_url(uri_string()) ?>" class="form-box">
                                                <?= csrf_field() ?>
                                                <input type="hidden" value="<?php echo $data['roleMid'] ?>" name="roleMid" id="roleMid" />


                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 mb-3">
                                                        <label for="rcaption">Role's Caption</label>
                                                        <input id="rcaption" name="rcaption" type="text" class="form-control" value="<?php echo $rHeading ?>" placeholder="Enter Role's Caption" required>
                                                    </div>

                                                    <div class="col-sm-12 col-md-12 mb-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped custom-table">
                                                                <thead>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="text-left btn-block font-size-30 text-danger"><i class="fa fa-list"></i>&nbsp;Role List</div>
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                            <?php
                                                            $data = $model->fetchSubMenus($data['roleMid']);
                                                            ?>
                                                            <div class="col-sm-12 col-md-3 mb-3">
                                                                <button type="button" name="btn1" id="btn1" class="quick-btn mt-26"><?php echo $action; ?></button>
                                                                <button type="button" name="btn1" id="btn1" class="quick-btn mt-26">Clear/ Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="panel panel-danger hide" id="roleListDiv">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    Create <span class="text-success">[ Role ]</span>
                                                    <span class="menusList">
                                                        <a href="#" id="addrole"><i class="fa fa-plus">&nbsp;</i>Add Role</a>
                                                    </span>
                                                </div>
                                            </div>

                                            <div style="margin-top: 10px" class="panel-body">
                                                <div class="table-responsive" style="box-shadow: 5px 5px 8px 0px #737373;padding: 0px 6px;margin-bottom: 20px">
                                                    <div class="text-left btn-block font-size-30 text-danger"><i class="fa fa-list"></i>&nbsp;Role List</div>
                                                    <div class="table-responsive">
                                                        <table id="roleTable" class="table table-striped custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Role Name</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                $count = 1;


                                                                foreach ($rolelist as $rows) {
                                                                    echo '<tr>
                                                          <td>' . $count . '</td>								
                                                          <td>' . $rows['role_desc'] . '</td>								
                                                          <td>
                                                              <a class="fa fa-trash text-danger" data-id="' . esc($rows['id']) . '" id="roleDelete" href="javascript: void(0);" title="Click to delete this role"></a>&nbsp;&nbsp;
                                                              <form action="<?= site_url(uri_string()) ?>" method="post" id="FormRoleEdit" style="display: inline;">
                                                                  <input type="hidden" value="' . esc($rows['id']) . '" name="menu_id">
                                                                  <input type="hidden" value="' . esc($rows['role_desc']) . '" name="rHeading">
                                                                  <div class="fa fa-edit" style="position: relative;"></div>
                                                                  <input type="submit" value="Edit" style="color:transparent;border: 0px;background: transparent;margin-left: -22px;position: relative;z-index: 9; outline:0;">
                                                              </form>
                                                          </td>
                                                      </tr>';
                                                                    $count++;
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
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
    </div>
</section>

<script>
    $("#roleTable").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });

    $('#roleList, #addrole').click(function(e) {
        e.preventDefault();
        var activeid;
        activeid = $(this).attr('id');

        if (activeid == 'roleList') {
            $('#addroleDiv').addClass('hide');
            $('#roleListDiv').removeClass('hide');
        } else if (activeid == 'addrole') {
            $('#roleListDiv').addClass('hide');
            $('#addroleDiv').removeClass('hide');
        }
    });

    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function(e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus').removeClass('fa-minus');
            $(this).attr('title', 'Collapse this branch').prev("#menuIds").removeAttr('disabled');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus').removeClass('fa-plus');
            $(this).attr('title', 'Collapse this branch').prev("#menuIds").attr('disabled', true).prop('checked', false);
        }
        e.stopPropagation();
    });

    function check_uncheck_checkbox(isChecked) {
        if (isChecked) {
            $('input[name="menus"]').each(function() {
                this.checked = false;
                $(this).attr('disabled', true);
            });
            $('.all-menus-box').removeAttr('disabled').prop('checked', true);
        } else {
            $('input[name="menus"]').each(function() {
                $(this).removeAttr('disabled');
            });
        }
    }

    function moveMenu(chk) {
        if (chk == true) {
            $('#smlv1').removeClass('hide').removeAttr('disabled');
            $('input[type="submit"]').val('Move to another level').removeClass('btn-success').addClass('btn-danger');
        } else {
            $('#smlv1').val("").addClass('hide').attr('disabled', true);
            $('.smlv2, .smlv3, .smlv4, .smlv5, .smlv6').addClass('hide', true).find('select').removeAttr('required');
            $('input[type="submit"]').val('Update').removeClass('btn-danger').addClass('btn-success');
        }
    }

    $(document).on("click", "#btn1", function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var selected_smenus = "",
            data_form, action = 'createRole',
            submitBtnType = '',
            roleMid = '';
        submitBtnType = $("input[type='submit']").val(), roleMid = $('#roleMid').val();
        data_form = new FormData(this);
        if (submitBtnType == 'Update') {
            action = 'updateRole';
            data_form.append('roleMid', roleMid);
        }
        data_form.append('action', action);
        $.each($("input[name='menuIds']:checked"), function() {
            selected_smenus += $(this).val() + ',';
        });
        data_form.append('menuIds', selected_smenus);
        if (selected_smenus) {
            $.ajax({
                url: "<?php echo base_url('MasterManagement/RolesController/roleparameter'); ?>",
                method: 'POST',
                beforeSend: function() {
                    $('#dv_res1').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: {
                    data_form,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    updateCSRFToken();
                    if (resp.data == 'success') {
                        msg = 'Role created successfully';
                        if (action == 'updateRole') {
                            msg = 'Role updated successfully';
                        }
                        alert(msg);
                        window.location.reload(true);
                    } else if (resp.data == '0') {
                        alert(resp.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    updateCSRFToken();
                    alert("Error: " + jqXHR.status + " " + errorThrown);
                }
            });
        } else {
            alert('Please choose atleast one value.');
        }
    });

    function save_verification(dno) {

        var r = confirm("Are you Verfied this case");
        if (r == true) {
            if ($("#rremark_" + dno).val() == 'R' && $("#reject_remark_" + dno).val() == "") {
                alert("Please Entry Valid Rejection Reason");
                return false;
            }
            var rremark = $("#rremark_" + dno).val();
            var rejection_remark = $("#reject_remark_" + dno).val();
            var cl_date = $("#" + dno).data('cl_date');
            var dataString = "dno=" + dno + "&rremark=" + rremark + "&rejection_remark=" + rejection_remark + "&cl_date=" + cl_date;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('ManagementReports/DA/DA/response_case_remarks_verification'); ?>",
                data: dataString + '&' + CSRF_TOKEN + '=' + CSRF_TOKEN_VALUE,
                cache: false,
                success: function(data) {
                    // alert(data);
                    updateCSRFToken();
                    if (data == 1) {
                        var r = "#" + dno;
                        var row = "<tr><td colspan='7' style='text-align:center;color:red;'>DN : " + dno + " Verified Successfully</td></tr>";
                        $(r).replaceWith(row);
                    } else {
                        alert("Not Verified.");
                    }
                }
            }).fail(function() {
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
        } else {

            txt = "You pressed Cancel!";
        }

    }
</script>