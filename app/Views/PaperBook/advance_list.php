<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> DRAFT LIST </h3>
                            </div>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">
                            <form method="post" action="">
                                <?= csrf_field(); ?>
                                <input type="hidden" id="hd_id_for_usersec">
                                <div class="row align-items-center">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-2">
                                        <label for="">Causelist Date</label>
                                        <select class="form-control ele" name="cdate" id="cdate">
                                            <?php if (count($cause_date_list) > 0) { ?>
                                                <option value="" selected>SELECT</option>
                                                <?php foreach ($cause_date_list as $row) { ?>
                                                    <option value="<?php echo $row['next_dt']; ?>">
                                                        <?php echo date("d-m-Y", strtotime($row['next_dt'])); ?>
                                                    </option>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="" selected>EMPTY</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Type of List</label>
                                        <select class="form-control" id="flist" name="flist">
                                            <option value="">SELECT</option>
                                            <option value="1">Fresh Civil </option>
                                            <option value="2">Fresh Criminal</option>
                                            <option value="3">Diary Civil Matters</option>
                                            <option value="4">Diary Criminal Matters</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-26">
                                        <input type="checkbox" id="ma" name="ma" value="ma" />
                                        <label for="ma" class="col-form-label">Include Review/Curative/Contempt</label>
                                    </div>
                                    <div class="col-md-2 mt-26">
                                        <input type="button" class="btn btn-primary quick-btn" value="Submit" id="btnSubmit" onclick="get_data()" />
                                    </div>
                                </div>
                                <hr>

                                <div id="app_data"></div>
                                <div id="dis_notice" style="display: none"></div>
                                <input type="hidden" name="hd_fil_no_x" id="hd_fil_no_x" />
                                <input type="hidden" name="hd_recdt" id="hd_recdt" />
                                <input type="hidden" name="hd_off_notice" id="hd_off_notice" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/paper_book/advance_list.js') ?>"></script>
