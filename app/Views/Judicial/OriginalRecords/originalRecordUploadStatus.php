<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        

        <!-- Main content -->
        <section class="content ">
            <?php
                $attribute = array('class' => 'form-horizontal appearance_search_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => 'post');
                echo form_open(base_url('Judicial/OriginalRecord/UploadScannedFile/uploadIndex'), $attribute);
                ?>
                <div class="row">
                    <div class="col-sm-8 offset-2">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="page-header mb-0">Upload Original Records Status</h2>
                            </div>
                            <div class="card-body">
                            <?php if (isset($status) && isset($message)) {
                            if ($status == 1) {
                        ?>
                                <div class="alert alert-dismissible col-sm-12">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fa fa-check"></i> Uploaded</h5>
                                    <?= $message ?>
                                </div>
                                <div class="text-center">
                                    <button type="submit" id="uploadRecord" name="uploadRecord" class="btn btn-block btn-success">Upload For Other Case</button>
                                </div>

                            <?php } else { ?>
                                <div class="alert alert-dismissible col-sm-12">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fa fa-ban"></i> Alert!</h5>
                                    <?= $message ?>
                                </div>
                                <div class="text-center">
                                    <button type="submit" id="uploadRecord" name="uploadRecord" class="btn btn-block btn-danger">Retry</button>
                                </div>
                        <?php }
                        } ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?= form_close() ?>


        </section>
        <!-- /.content -->
    </div>
</section>

