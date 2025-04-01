<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reasons Rejection List</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_master'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">Reasons Rejection List</h4>
                    </div>
                    <div class="ml-4 mr-4">
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
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Reasons</th>
                                    <th>Entry Date</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rejection_reasons)) {
                                    $sno = 0;
                                    foreach ($rejection_reasons as $row) {
                                        $from_date = $row['entry_time'];
                                        $date = strtotime($from_date);
                                        $fromdate = date('d-m-Y H:i:s', $date);

                                ?>
                                        <tr>
                                            <td><?= ++$sno; ?></td>
                                            <td><?= $row['reasons']; ?></td>
                                            <td><?= $fromdate; ?></td>
                                            <td><?= $row['user_name']; ?></td>
                                            <td>
                                                <button class="btn btn-danger" onclick="reasonsDataLoad('<?php echo urlencode($row['id']); ?>')" id="user_deactive_btn"><i class="fa fa-trash"></i> </button>
                                            </td>
                                        </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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

<script>
    function reasonsDataLoad(id) {
        let text = "Are you sure want to delete the reason of rejection?";
        if (confirm(text) == true) {

            var reasons_deactive_id = id;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                url: '<?php echo base_url('Copying/Copying/reason_reject_save'); ?>',
                cache: false,
                async: true,
                data: {
                    reasons_deactive_id: reasons_deactive_id,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {

                    if(data==1){
                          Swal.fire({
                          title: "Reasons Rejection Deleted Successfully!",
                          text: "You clicked the button!",
                          icon: "success"
                          }).then((result) => {
                           if (result.isConfirmed) {
                               location.reload();
                           }
                          });
                    }else{
                        Swal.fire({
                          title: "Reasons Rejection Not Deleted Successfully!",
                          text: "You clicked the button!",
                          icon: "error"
                          }).then((result) => {
                           if (result.isConfirmed) {
                               location.reload();
                           }
                          });
                    }
                    $(window).scrollTop(0);
                    updateCSRFToken();
                }
            });
            return;
        }
    }
</script>