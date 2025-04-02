<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reasons for Rejection</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_master'); ?>
                  
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                            <h4 class="basic_heading">Reasons for Rejection</h4>
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
                       <div class="row form-row">
                  
                            <div class="col-md-1">
                            <div class="input-group-prepend">
                                        <span class="input-group-text" id="reject_reasons_addon">Reason<span style="color:red;">*</span></span>
                                    </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group mb-3">
                                    
                                    <input name='reject_reasons' type="text" class="form-control uk-input" id="reject_reasons"  value="" placeholder="Reasons" minlength="5" maxlength="70" onkeyup="this.value=this.value.replace(/[0-9]/g,'');" required />

                                </div>
                            </div>
                            <div class="col-md-1">
                                <input id="btn_save" name="btn_save" type="button" class="btn btn-success btn-block" value="Save">
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
    $("#btn_save").click(function(){
        var reject_reasons = $("#reject_reasons").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#show_error').html("");
        if (reject_reasons.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Reasons Required* </strong></div>');
            $("#reject_reasons").focus();
            return false;
        }
        else{
            $.ajax({
                url:'<?php echo base_url('Copying/Copying/reason_reject_insert'); ?>',
                cache: false,
                async: true,
                data: {reasons:reject_reasons,CSRF_TOKEN: CSRF_TOKEN_VALUE},
                type: 'POST',
                success: function(data) {
                    console.log('Response',data);
                    if(data==1){
                        Swal.fire({
                          title: "Reasons Successfully Insert!",
                          text: "You clicked the button!",
                          icon: "success"
                     }).then((result) => {
                     if (result.isConfirmed) {
                     location.reload();
                     }
                   });
                    }else if(data==0){
                        Swal.fire({
                          title: "Reasons Not Successfully Insert!",
                          text: "You clicked the button!",
                          icon: "error"
                     }).then((result) => {
                     if (result.isConfirmed) {
                     location.reload();
                     }
                   });
                        

                    }else if(data==2){
                        Swal.fire({
                          title: "Reasons Rejection Already Insert",
                          text: "You clicked the button!",
                          icon: "error"
                     }).then((result) => {
                     if (result.isConfirmed) {
                     location.reload();
                     }
                    })
                    }else if(data==3){
                        swal({title:"Reasons Mandatory* ", text:"You clicked the button!", type:"danger"},
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