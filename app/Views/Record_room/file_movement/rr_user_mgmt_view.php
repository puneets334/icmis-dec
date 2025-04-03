<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">File movement >> Case allotment >> Record room User/Hall case</h3>
                            </div>
                        </div>
                    </div>
                    <form method="post" action="">
                        <?= csrf_field(); ?>
                        <div id="dv_content1" class="row mt-4 col-sm-12">


                            <div class="col-sm-12 form-group">
                                <label>Select Allotment Category : </label>
                                <div>
                                    <div class="col-sm-6 form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="allottment_type" id="hallwise" value="1" checked />
                                        <label class="form-check-label" for="hallwise">Hallwise</label>
                                    </div>
                                    <div class="col-sm-6 form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="allottment_type" id="userwise" value="2" />
                                        <label class="form-check-label" for="userwise">Userwise</label>
                                    </div>
                                </div>
                            </div>

                            <div id="userCases" class="row form-group col-sm-12">

                                <div class="col-sm-4 mb-3">
                                    <label for="department" class="cl_wh">DEPARTMENT</label>
                                    <input type="hidden" value="<?php echo $usertype_row['usertype']; ?>"
                                        id="cur_user_type" />
                                    <input type="hidden" value="<?php echo $usertype_row['usercode']; ?>"
                                        id="usercode" />
                                    <select id="department" class="form-control">
                                        <option value="0">SELECT</option>
                                        <?php foreach ($dept as $dept_row) { ?>
                                            <option value="<?php echo $dept_row['udept']; ?>">
                                                <?php echo $dept_row['dept_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label for="section" class="cl_wh">SECTION</label>
                                    <select id="section" class="form-control">
                                        <?php if ($usertype_row['usertype'] == 1 || $usertype_row['usertype'] == 57 || $usertype_row['usercode'] == 2506) { ?>
                                            <option value="ALL">ALL</option>
                                        <?php } else { ?>
                                            <option value="0">SELECT</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="designation" class="cl_wh">DESIGNATION</label>
                                    <select id="designation" class="form-control">
                                        <option value="ALL">ALL</option>
                                    </select>
                                </div>

                            </div>


                            <div class="col-sm-12 form-group">
                                <input type="button" id="btnShow" value="Show" class="btn btn-primary mt-2" />
                            </div>

                            <div id="result_main_um"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/record_room/rr_user_mgmt_view.js"></script>