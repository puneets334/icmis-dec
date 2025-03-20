<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">User Role Assigned List</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_master'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">User Role Assigned List</h4>
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
                                    <th>Name</th>
                                    <th>Application Type</th>
                                    <th>Applicant Type</th>
                                    <th>Role Assign By</th>
                                    <th>From Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($arr_result)) {
                                    $sno = 0;
                                    foreach ($arr_result as $value) {


                                ?>
                                        <tr>
                                            <td><?= ++$sno; ?></td>
                                            <td><?= $value['name']; ?></td>
                                            <td><?= $value['application_type']; ?></td>
                                            <td><?= $value['applicant_type']; ?></td>
                                            <td><?= $value['role_assign_by']; ?></td>
                                            <td><?= $value['from_date']; ?></td>
                                            <td>
                                                <button class="btn btn-danger" onclick="mainDataLoad('<?php echo urlencode($value['id']); ?>')" id="user_deactive_btn"><i class="fa fa-trash"></i> </button>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
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
    function mainDataLoad(id) {

        let text = "Are you sure want to delete the user role?";
        if (confirm(text) == true) {
            var usercode_deactive = id;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                url: '<?php echo base_url('Copying/Copying/user_role_delete'); ?>',
                cache: false,
                async: true,
                data: {
                    usercode_deactive: usercode_deactive,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    if(data==1){
                        swal({title:"Role Assigned Deleted Successfully!", text:"You clicked the button!", type:"success"},
                            function(){
                                location.reload();
                            });

                    }else{
                        swal({title:"Role Assign Not Deleted Successfully!", text:"You clicked the button!", type:"error"},
                            function(){
                                location.reload();
                            }
                        );
                    }
                    updateCSRFToken();
                }
            });
            return;
        }
    }
</script>