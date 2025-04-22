<?= view('header') ?>
<style>
    .button-container {
        display: flex;
        align-items: center;
        /* Aligns items vertically centered */
    }

    .button-container button {
        margin-right: 10px;
        /* Adds space between buttons */
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> User Management >> USER DEPARTMENT [& SECTION MAPPING]</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <?= csrf_field(); ?>
                        <div id="dv_content1" class="container-fluid mt-4">

                            <input type="hidden" id="hd_id_for_userdept">
                            <div class="top1">
                                <?php if ($name[0] != 1) exit(); ?>

                                <div class="form-group">
                                    <label for="id_udept">UserDept ID</label>
                                    <input type="text" id="id_udept" class="form-control" value="<?php echo $get_Open_id; ?>" size="3" disabled />
                                </div>

                                <div class="form-group">
                                    <label for="name_udept">UserDept Name</label>
                                    <input type="text" id="name_udept" class="form-control" maxlength="20" />
                                </div>

                                <div class="form-group">
                                    <label for="uside_flag">Userside Flag</label>
                                    <input type="text" id="uside_flag" class="form-control" maxlength="10" size="10" />
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="btnMain">Add New</button>
                                    <button type="button" class="btn btn-warning" id="btnUp">Update</button>
                                    <button type="button" class="btn btn-secondary" id="btnCan">Cancel</button>
                                </div>

                                <div class="form-group">
                                    <label for="bounded_utype">Bounded Section</label>
                                    <div class="input-group">
                                        <select id="bounded_utype" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($result_utype as $row_utype) { ?>
                                                <option value="<?php echo $row_utype['id']; ?>"><?php echo $row_utype['section_name'] . '-' . $row_utype['id']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" onclick="press_add()">Add-></button>
                                        </div>
                                    </div>
                                </div>

                                <div id="u_type_area" class="mb-3" style="overflow: hidden; font-size: 14px;"></div>
                                <input type="hidden" id="user_mapping" />
                            </div>

                            <div class="add_result"></div>
                            
                                <?php if ($result != 0) { ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped custom-table" id="result_main">
                                            <thead>
                                                <tr>
                                                    <th>SNo.</th>
                                                    <th>ID</th>
                                                    <th>UserDept Name</th>
                                                    <th>Userside Flag</th>
                                                    <th>Bounded User Section</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $sno = 1;
                                                foreach ($result as $select_type_row) { ?>
                                                    <tr>
                                                        <td><?php echo $sno; ?></td>
                                                        <td><?php echo $select_type_row['id']; ?></td>
                                                        <td><?php echo $select_type_row['dept_name']; ?></td>
                                                        <td><?php echo $select_type_row['uside_flag']; ?></td>
                                                        <td><?php echo $select_type_row['type_name']; ?></td>
                                                        <td class="button-container">
                                                            <button type="button" class="quick-btn" id="btnEdit<?php echo $select_type_row['id']; ?>">Edit</button>
                                                            <input type="hidden" id="hd_utype_top_<?php echo $select_type_row['id']; ?>" value="<?php echo $select_type_row['utype_top']; ?>" />
                                                            <button type="button" class="btn btn-danger" id="btnDelete<?php echo $select_type_row['id']; ?>">Remove</button>
                                                        </td>
                                                    </tr>
                                                <?php $sno++;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-warning">SORRY, NO RECORD FOUND!!!</div>
                                <?php } ?>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/user_dept.js') ?>"></script>