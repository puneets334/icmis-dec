<?= view('header') ?>
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>
    .form-style-10 {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }

    .form-label {
        font-weight: bold;
    }

    .datepicker {
        width: 100%;
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
                                <h3 class="card-title">Master Management >> User Management</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <form method="post" action="">
                        <div class="container mt-4">
                            <div class="form-row mb-3">
                                <div class="form-group col-md-4">
                                    <label for="department" class="cl_wh">DEPARTMENT</label>
                                    <input type="hidden" value="<?php echo $usertype_row['usertype']; ?>" id="cur_user_type" />
                                    <input type="hidden" value="<?php echo $usertype_row['usercode']; ?>" id="usercode" />
                                    <select id="department" class="form-control">
                                        <?php if ($usertype_row['usertype'] == 1 || $usertype_row['usertype'] == 57 || $usertype_row['usertype'] == 6) { ?>
                                            <option value="ALL">ALL</option>
                                        <?php } else { ?>
                                            <option value="0">SELECT</option>
                                        <?php } ?>
                                        <?php foreach ($dept as $dept_row) { ?>
                                            <option value="<?php echo $dept_row['udept']; ?>"><?php echo $dept_row['dept_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="section" class="cl_wh">SECTION</label>
                                    <select id="section" class="form-control">
                                        <?php if ($usertype_row['usertype'] == 1 || $usertype_row['usertype'] == 57 || $usertype_row['usercode'] == 2506) { ?>
                                            <option value="ALL">ALL</option>
                                        <?php } else { ?>
                                            <option value="0">SELECT</option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="designation" class="cl_wh">DESIGNATION</label>
                                    <select id="designation" class="form-control">
                                        <option value="ALL">ALL</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="calc-da-code" id="calc-da-code" /> Calculate DA Cases
                                </label>
                            </div>

                            <button type="button" id="btnShow" class="btn btn-primary mt-2">Show User</button>

                            <div id="for_judge_select" class="mt-3" style="display: none;">
                                <label class="text-white">SELECT JUDGE</label>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <select id="judges_id" class="form-control">
                                            <option value="">ALL</option>
                                            <?php  foreach ($sel_all_jud as $row_jud_sel) { ?>
                                                <option value="<?php echo $row_jud_sel['jcode']; ?>"><?php echo $row_jud_sel['jname']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>
                                            <input type="checkbox" id="orderjud" /> ORDER BY JUDGE
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div id="result_main_um" class="row mt-3"></div>








                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/user_mgmt_view.js') ?>"></script>