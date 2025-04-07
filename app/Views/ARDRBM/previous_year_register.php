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
<div id="model-show-proposal" data-bs-backdrop='static' data-bs-keyboard="false" class="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div class="modal-body" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()"></div>
        
        <!-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div> -->

    <!-- <div id="dv_sh_hd" style="display: none;position: fixed;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.6;left: 0;overflow: hidden;z-index: 103">
        &nbsp;
    </div>
    <div id="dv_fixedFor_P" style="position: fixed;top:0;display: none;
        left:0;
        width:100%;
        height:100%;z-index: 105;">
        <div id="sp_close" style="text-align: right;cursor: pointer;width: 40px;float: right" onclick="closeData()"><b><img src="<?php echo base_url(); ?>/images/close_btn.png" style="width:30px;height:30px" /></b></div>
        <div style="width: auto;background-color: white;overflow: scroll;height: 500px;margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;word-wrap: break-word;" id="ggg" onkeypress="return  nb(event)" onmouseup="checkStat()"></div>
    </div> -->
</div>
  </div>
</div>
<script src="<?php echo base_url('ardrbm/previous_year_register.js'); ?>"></script>