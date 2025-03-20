<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Online Applications - Verification Module </h3>
                                <input type="hidden" name="<?=csrf_token() ?>" value="<?=csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_master'); ?>

                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">Online Applications - Verification Module </h4>
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
                    <span id="show_error" class="ml-4 mr-4"></span> <!-- This Segment Displays The Validation Rule -->
                    <div class="card-body">
                   
                        <div class="form-row">
                            <div class="col-4 pl-4">


                                        <label>Application Type<span style="color:red;">*</span></label>

                                    <select class="form-control" multiple="multiple" name="application_type[]" style="width: 100%;" id="application_type" data-placeholder="Select Application Type">
                                        <!--<option value="">Select Application Type</option>-->
                                        <?php
                                        foreach ($copy_category as $row) {
                                        ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['code'] . '-' . $row['description'] ?></option>
                                        <?php } ?>
                                    </select>


                            </div>
                            <div class="col-4 pl-4">


                                        <label>Applicant Type<span style="color:red;">*</span></label>

                                    <select class="form-control" multiple="multiple" name="applicant_type[]" style="width: 100%;" id="applicant_type" data-placeholder="Select Applicant Type">
                                        <option value="1">Advocate on Record</option>
                                        <option value="2">Party/Party-in-person</option>
                                        <option value="3">Appearing Counsel</option>
                                        <option value="6">Authenticated By AOR</option>
                                    </select>


                            </div>
                            <div class="col-4 pl-4">


                                        <label>Role Assign To<span style="color:red;">*</span></label>

                                    <?php
                                    if (!empty($users_data)) { ?>
                                        <select class="form-control" style="width: 100%;" id="role_assign_id">
                                             <option value="">Select Role Assign To</option>
                                            <?php

                                            foreach ($users_data as $row) {
                                            ?>
                                                <option value="<?= $row['usercode']; ?>"><?= $row['name']; ?></option>
                                            <?php }

                                            ?>
                                        </select>
                                    <?php } else {
                                        echo "Error:Not Authorized";
                                    } ?>

                            </div>

                            <div class="row col-12 m-3">
                                <div class="col-sm-5">
                                </div>
                                <div class="col-sm-2">
                                     <span class="input-group-append">
                                                 <input id="btn_save" name="btn_save" type="button" class="btn btn-success btn-block" value="Save">
                                                </span>
                                </div>
                                <div class="col-sm-5">


                                </div>


                            </div>

                            <!--<div class="row col-12 m-0 p-0" id="result"></div>-->
                        </div>
                               
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
    $("#btn_save").click(function() {
       
        var application_type = $("#application_type").val();
        var applicant_type = $("#applicant_type").val();
        var role_assign_to = $("#role_assign_id").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#show_error').html("");
        if ($("#application_type").val().length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Application Type Required* </strong></div>');
            $("#application_type").focus();
            return false;
        }
        if ($("#applicant_type").val().length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Applicant Type Required* </strong></div>');
            $("#applicant_type").focus();
            return false;
        }
        else if (role_assign_to.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Role Assign Required* </strong></div>');
            $("#role_assign_id").focus();
            return false;
        }else {
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/role_assign_add'); ?>',
                cache: false,
                async: true,
                data: {
                    application_type:application_type,
                    applicant_type:applicant_type,
                    role_assign:role_assign_to,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    if(data==1){
                        swal({title:"Role Assign Successfully!", text:"You clicked the button!", type:"success"},
                            function(){
                                location.reload();
                            });
                    }else if(data==0){
                        swal({title:"Role Assign Not Successfully!", text:"You clicked the button!", type:"error"},
                            function(){
                                location.reload();
                            });

                    }else if(data==2){
                        swal({title:"User Role Already Assigned !", text:"You clicked the button!", type:"error"},
                            function(){
                                location.reload();
                            });
                    }else if(data==3){
                        swal({title:"Full Fill All Input ApplicantType and RoleAssign Mandatory *", text:"You clicked the button!", type:"danger"},
                            function(){
                                location.reload();
                            });
                    }
                    else{
                        swal({title:"User Not Found !", text:"You clicked the button!", type:"error"},
                            function(){
                                location.reload();
                            });
                    }
                     updateCSRFToken();
                }

            });
        }
    });
</script>