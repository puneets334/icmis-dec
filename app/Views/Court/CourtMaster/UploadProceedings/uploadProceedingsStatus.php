<?= view('header') ?>
<?php  $uri = current_url(true); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Proceeding Status</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-sm-3">
                            <label>&nbsp;</label>
                            <a href="<?= base_url('Court/CourtMasterController/get_session_upload')?>" id="btnReturnToUpload" class="btn btn-success"><i class="fa fa-fw fa-upload"></i>&nbsp;Upload More ROP's
                            </a>
                        </div>
                        <table id="tblCasesUploadStatus" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th width="5%">S.No.</th>
                                <th width="20%">File Name</th>
                                <th width="75%">Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $s_no=1;
                            foreach ($fileListStatus as $case => $status)
                            {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $s_no; ?>
                                    </td>
                                    <td>
                                        <?php echo $case; ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $status;
                                        ?>
                                    </td>

                                </tr>
                                <?php
                                $s_no++;
                            }   //for each
                            ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
   
</script>