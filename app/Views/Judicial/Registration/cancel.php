<?= view('header') ?>
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Judicial >> Registration >> Cancel</h3>
                            </div>
                            <div class="col-sm-2">
                              
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
<?php
$attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
echo form_open(base_url($formAction), $attribute);
?>
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
                
                <?php if(!empty($message)) { ?>
                <div class="row">
                    <h1><?= $message; ?></h1>
                </div>
                <?php } ?>
                <?php if(!empty($allow_cancel)) { ?>
                <div class="row">
                    <div class="col-sm-5">
                    </div>
                    <div class="col-sm-6">
                        <span class="input-group-append">
                            <input type='hidden' name='dairy_no' id='dairy_no' value='<?= $dairy_no; ?>'/>
                            <button id="btn_search" name="btn_search" type="button" class="btn btn-success ml-2" onclick="deleteRecord()" />Cancel Registraion</button>
                            <a href="<?php echo base_url('Judicial/Registration/cancel'); ?>" class="btn btn-secondary ml-2">Go Back</a>
                        </span>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row">
                    <div class="col-sm-5">
                    </div>
                    <div class="col-sm-6">
                        <span class="input-group-append">
                            <a href="<?php echo base_url('Judicial/Registration/cancel'); ?>" class="btn btn-secondary ml-2">Go Back</a>
                        </span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

</div>
<?php form_close();?>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.content -->
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script type="text/javascript">
    function deleteRecord() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var dairy_no = $("input[name='dairy_no']").val();

        // Perform AJAX request to delete the record
        $.ajax({
            url: "<?php echo base_url('Judicial/Registration/update'); ?>",
            type: "post",
            data: {
                CSRF_TOKEN:CSRF_TOKEN_VALUE,
                dairy_no: dairy_no
            },
            success: function(response) {
                
                console.log(response);

                if (response.success == 1) {
                    swal({
                        title: "Deleted!",
                        text: response.message,
                        icon: "success"
                    }).then(() => {
                        window.location = "<?php echo base_url('Judicial/Registration/cancel'); ?>"; // Reload the page even on failure
                    });
                } else {
                    swal({
                        title: "Error!",
                        text: response.error,
                        icon: "error"
                    }).then(() => {
                        window.location = "<?php echo base_url('Judicial/Registration/cancel'); ?>"; // Reload the page even on failure
                    });
                }
                updateCSRFToken();
            },
            error: function() {
                Swal.fire({
                    title: "Error!",
                    text: "There was an error while deleting the record.",
                    icon: "error"
                }).then(() => {
                    window.location = "<?php echo base_url('Judicial/Registration/cancel'); ?>"; // Reload the page even if there's an error
                });
                updateCSRFToken();
            }
        });
    }

    // $(function() {
    //     $("#example1").DataTable({
    //         "responsive": true,
    //         "lengthChange": false,
    //         "autoWidth": false,
    //         "buttons": ["copy", "csv", "excel", "pdf", "print"]
    //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    // });
</script>