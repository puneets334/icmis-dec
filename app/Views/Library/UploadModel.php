 <style>
    .list-group-item {
        text-align: left;
    }
    .modal .modal-body
    {
        padding: 0 !important;
    }
    .modal .modal-body {
            overflow-y: auto;
        }
    .custom-file-input
    {
        opacity: 1;
    }
 </style>
<div class="modal-header" style="position: relative;padding: 0; margin: 0;">
    <h5 class="modal-title" style="width: 100%;">Upload Document for <?= ($cause_title); ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">

            <div class="row">
                <div class="col-md-12 mt-2">

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5 class="card-title">Case Details</h5>
                        </div>


                        <div class="card-body box-profile">

                            <ul class="list-group mb-3">
                                <li class="list-group-item">
                                    <b>List Date</b> <a class="float-right"><?= date('d-m-Y', strtotime($_POST['list_date'])) ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Court No.</b> <a class="float-right"><?=$_POST['court_no']?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Item No.</b> <a class="float-right"><?=$_POST['item_no']?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Case No.</b> <a class="float-right"><?=$_POST['case_no']?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Title</b> <a class="float-right"><?=$_POST['cause_title']?></a>
                                </li>
                            </ul>
                        </div>


                    </div>

                </div>
            </div>
<style>
    table>thead>tr> th{
        font-weight: 600;
        background-color: #6c757d;
        color:#fff;
    }
</style>
            <div class="row">
                <div class="col-md-12 mt-2">

                    <div class="card card-secondary">

                        <div class="card-header">
                            <h5 class="card-title">Upload</h5>
                        </div>
                        <form method='post'  action='' enctype="multipart/form-data">
                        <?= csrf_field() ?>
                            <div class="card-body">
                                <?php
                              
                                
                                if (!empty($rs22)) {
                                    ?>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Heading</th>
                                            <th>Details</th>
                                            <th>Uploaded by AOR</th>
                                            <th>Upload by Library User</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        <?php
                                        foreach ($rs22 as $row22) {
                                        ?>
                                        <tr>
                                            <td><?=$row22['name_of_header']?></td>
                                            <td><?=$row22['header_details']?></td>
                                            <td>
                                                <?php
                                                if($row22['file_name']){
                                                    ?>
                                                    <a title="Uploaded File View"  target="_blank"
                                                    href="<?= '../files/library_aor_uploads/'.$row22['file_name'] ?>" >Uploaded by AOR</a>
                                                    <?php
                                                }else{
                                                    ?>
                                                     
                                                    <?php
                                                }
                                                ?>
                                            </td>

                                            <td>


                                                <div class="form-group" style="width: 100%;">

                                                    <?php if($row22['icmis_file_name']){ ?>
                                                        <a title="Uploaded File View" target="_blank" href="<?= '../files/library_aor_uploads/'.$row22['icmis_file_name'] ?>" >Uploaded by Library user</a>
                                                    <?php } ?>
                                                    <div class="input-group mt-3" id="inputFormRow">
                                                        <input type="hidden" name="library_referance_material_child[]" id="library_referance_material_child" value="<?=$row22['id']?>" />
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="upload_document_lib[]" id="upload_document_lib" accept="application/pdf,application/octet-stream"  >
                                                            <!-- <label class="custom-file-label" for="upload_document_lib">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>


                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                }

                                ?>

                                


                                <div class="form-group">
                                    <label for="upload_retain_option">Do you want to delete uploaded documents automatically after 30 days of list date?</label>
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text bg-white " >
                                            <div class="form-check">
                                                <input class="form-check-input mt-1 " type="radio" id="document_retain_option_yes" name="document_retain_option" value="Yes" checked>
                                                <label class="form-check-label ml-1 ml-1">Yes</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input mt-1 ml-1 " type="radio" id="document_retain_option_no" name="document_retain_option" value="No" >
                                                <label class="form-check-label ml-4">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status_option">Whether Task completed or not?</label>
                                    <div class="input-group-prepend ">
                                        <div class="input-group-text bg-white " >
                                            <div class="form-check">
                                                <input class="form-check-input mt-1 " type="radio" id="status_option_yes" name="status_option" value="Completed" checked>
                                                <label class="form-check-label ml-1 ml-1">Yes</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input mt-1 ml-1 " type="radio" id="status_option_no" name="status_option" value="Pending" >
                                                <label class="form-check-label ml-4">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" name="library_reference_material" value="<?=$_POST['library_reference_material']?>" />
                            <input type="hidden" name="diary_no" value="<?=$_POST['diary_no']?>" />
                            <input type="hidden" name="list_date" value="<?=$_POST['list_date']?>" />

                            <div class="card-footer justify-content-between">
                                <?php
                                if($_POST['i_status'] == 'Pending'){
                                    ?>
                                    <button type="button" class="btn btn-success btn_upload_save" data-diary_no="<?=$_POST['diary_no']?>" data-list_date="<?=$_POST['list_date']?>">Submit</button>
                                <?php }
                                else {
                                    ?>
                                    <span class="text-danger">This task already completed</span>
                                    <?php
                                }?>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
 
    
    
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>

 
</script>