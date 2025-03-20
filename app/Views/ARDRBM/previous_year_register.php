<?= view('header') ?>

<style>
    button#search {
        margin-top: 36px;
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
                                <h3 class="card-title">Scrunity Registration</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4 diary_section">
                                            <div class="form-group">
                                                <label for="diary_number" class="col-sm-12 col-form-label">Diary No</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Diary No">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 diary_section">
                                            <div class="form-group">
                                                <label for="diary_year" class="col-sm-12 col-form-label">Diary Year</label>
                                                <div class="col-sm-12">
                                                    <?php $year = 1950;
                                                    $current_year = date('Y');
                                                    ?>
                                                    <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                        <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                            <option><?php echo $x; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 diary_section">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary" id="search" onclick="get_report()">Submit</button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php form_close(); ?>

                                    <br /><br />
                                    <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>

                                    <div id="dv_res1"> </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url('ardrbm/previous_year_register.js'); ?>"></script>