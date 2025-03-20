<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> User Management >> USER LEAVE</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <div id="dv_content1" class="container mt-4">
                            <input type="hidden" id="hd_id_for_userleave">

                            <?php if ($permit == 0) exit(); ?>

                            <div id="onlyfrm">
                                <div id="update_section" class="mb-3">UPDATE FOR ID-<span id="update_id"></span></div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md">
                                        <label for="dept">DEPARTMENT</label>
                                        <select id="dept" class="form-control" onchange="dChange(this.value,'','','','','')">
                                            <option value="0">SELECT</option>
                                            <?php foreach ($dept as $dept_row) { ?>
                                                <option value="<?php echo $dept_row['id']; ?>"><?php echo $dept_row['dept_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md">
                                        <label for="sec">SECTION</label>
                                        <select id="sec" class="form-control" onchange="sChange(this.value,'','','','')">
                                            <option value="0">SELECT</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md">
                                        <label for="desguser">DESG & USERS</label>
                                        <select id="desguser" class="form-control">
                                            <option value="0">ALL</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md">
                                        <label for="leave">LEAVE TYPE</label>
                                        <select id="leave" class="form-control" onchange="lChange(this.value,'','')">
                                            <option value="0">SELECT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md">
                                        <label for="for_auth">Forwarding Authority</label>
                                        <select id="for_auth" class="form-control" onchange="faChange(this.value,'')">
                                            <option value="0">SELECT</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md">
                                        <label for="app_auth">Approval Authority</label>
                                        <select id="app_auth" class="form-control">
                                            <option value="0">SELECT</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md d-flex align-items-end">
                                        <input type="button" value="Add Below" class="btn btn-secondary" onclick="press_add_leave()" />
                                    </div>
                                </div>
                                <div id="l_type_area" class="mb-3">
                                    <!-- Dynamic content goes here -->
                                </div>
                                <input type="hidden" id="leave_mapping" />
                                <div class="text-right">
                                    <input type="button" value="Save Data" id="btnMainLeave" class="btn btn-success" onclick="add_userLeave()" />
                                    <input type="button" value="Cancel" id="btnCanLeave" class="btn btn-danger" onclick="cancel_op_leave()" />
                                </div>
                            </div>

                            <div class="add_result"></div>
                            <div id="result_main" class="mt-4">
                                <?php if ($result != 0) { ?>
                                    <table class="table table-bordered table-responsive">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>SNo.</th>
                                                <th>ID</th>
                                                <th>Department</th>
                                                <th>Section</th>
                                                <th>Designation/User</th>
                                                <th>Leave Type</th>
                                                <th>Forwarding Authority</th>
                                                <th>Approval Authority</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sno = 1;
                                            foreach ($result as $select_type_row) {
                                                if ($name[0] == 2 && $name[1] == 'PROTOCOL') {
                                                    if ($select_type_row['dept'] != 'PROTOCOL') continue;
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $sno; ?></td>
                                                    <td><?php echo $select_type_row['id']; ?></td>
                                                    <td><?php echo $select_type_row['dept']; ?></td>
                                                    <td><?php echo $select_type_row['utype']; ?></td>
                                                    <td><?php echo $select_type_row['perticular_user'] == NULL ? "ALL" : $select_type_row['perticular_user'] . '-' . $select_type_row['part_user_name']; ?></td>
                                                    <td><?php echo $select_type_row['ltype']; ?></td>
                                                    <td><?php echo $select_type_row['faname']; ?></td>
                                                    <td><?php echo $select_type_row['aaname']; ?></td>
                                                    <td>
                                                        <input type="button" id="btnEdit" class="btn btn-warning btn-sm" onclick="edit_user_leave_click_fun(<?php echo $select_type_row['id']; ?>)" value="Edit" />
                                                        <input type="button" id="btnc" class="btn btn-danger btn-sm" onclick="del_user_leave_click_fun(<?php echo $select_type_row['id']; ?>)" value="Remove" />
                                                    </td>
                                                </tr>
                                            <?php
                                                $sno++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <div class="alert alert-warning">SORRY, NO RECORD FOUND!!!</div>
                                <?php } ?>
                            </div>
                        </div>
                    </form>






                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/user_leave.js') ?>"></script>