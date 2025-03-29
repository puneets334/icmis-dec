<?= view('header') ?>
 
<style>
    .item {
        border: 1px solid #eee;
        box-shadow: 0 0 10px -3px #ccc;
        border-radius: 5px;
        margin-bottom: 30px;
        padding: 25px;
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
                                <h3 class="card-title">Record Room >> Search >> Speciman Signature </h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_registration_breadcrum'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">Speciman Signature </h4>
                    </div>

                    <form class="form-horizontal" id="push-form" method="post" action="<?php echo base_url('Copying/Copying/specimen_signature'); ?>">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-primary">
                                    <div class="card-body">
                                        <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                        <?php if (session()->getFlashdata('error')) { ?>
                                            <div class="alert alert-danger">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('error') ?></strong>
                                            </div>

                                        <?php } ?>
                                        <?php if (session()->getFlashdata('success_msg')) : ?>
                                            <div class="alert alert-success alert-dismissible">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <span id="show_error" class="ml-4 mr-4"></span> <!-- This Segment Displays The Validation Rule -->
                                        <div class="row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-4">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-4 col-form-label"> AOR Code</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" class="form-control" id="aor_code" name="aor_code" value="<?=$aor_code?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2"><button type="submit" name="btn_app_submit" class="btn btn-success"><span class="glyphicon glyphicon-chevron-right"></span>  Submit</button></div>
                                        </div>
                                        <?php if(isset($aor_code)){  ?>
                                             <object data="<?=base_url();?>/home/supreme_court/signature/<?=$aor_code?>.pdf" type="application/pdf" width="100%" height="600">
                                        <?php } ?>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>

                        </div>
                    </form>
                </div>
                <div id="result_data"></div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>