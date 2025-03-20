<?= view('header') ?>
<style>
    .swal-footer {
        text-align: center;
    }
    .border{
        padding-right: 10px;
    }
</style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.card-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder">Directions Freeze for Hearing Mode
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-row col-12">
                                                    <?php
                                                    $attributes = 'class="col-md-12"';
                                                    $action = base_url('Listing/hybrid/registry_consent_process');
                                                    echo form_open($action, $attributes);
                                                        echo csrf_field();
                                                        ?>
                                                        <div class="input-group col-3 mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="listtype_addon">List Type</span>
                                                            </div>
                                                            <div class="border">
                                                                <?php
                                                                if(isset($masterList) && !empty($masterList) && count($masterList) > 0) { ?>
                                                                    <select name="list_type_name" id="list_type_name" class="form-control list_type" aria-describedby="listtype_addon">
                                                                        <option value="<?= $masterList[0]['id'] ?>"><?= $masterList[0]['list_type_name'] ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 pl-2 mb-3">
                                                            <button id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block">Get</button>
                                                        </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $("#btn_search").click(async function(){
            await updateCSRFTokenSync();
            $("#result").html(""); $('#show_error').html("");
            var list_type = $(".list_type").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            if (list_type.length == 0) {
                $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select list type</strong></div>');
                $("#from_date").focus();
                return false;
            } else {
                $.ajax({
                    url:'<?php echo base_url('Listing/hybrid/freeze_process'); ?>',
                    cache: false,
                    async: true,
                    data: {CSRF_TOKEN:csrf,list_type:list_type},
                    beforeSend:function(){
                        $("#btn_search").html('Loading <i class="fas fa-sync fa-spin"></i>');
                    },
                    type: 'POST',
                    success: function(data, status) {
                        $("#btn_search").html('Get');
                        $("#result").html(data);
                        updateCSRFTokenSync();
                    },
                    error: function(xhr) {
                        updateCSRFTokenSync();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
                updateCSRFTokenSync();
            }
        });

        $(document).on('click', '.save_action', function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var max_to_dt = $(this).data('max_to_dt');
            var max_weekly_no = $(this).data('max_weekly_no');
            var max_weekly_year = $(this).data('max_weekly_year');
            var list_type_id = $(this).data('list_type_id');
            var ip = '<?=$clientIP?>';
            var courtno = $(this).data('court_number');
            var action_content = $("#d_"+courtno).children(".save_action").html();        
            swal({
                title: "Are you sure?",
                text: "You want to Freeze",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {        
                    $.ajax({
                        url:'<?php echo base_url('Listing/hybrid/freeze_save'); ?>',
                        cache: false,
                        async: true,
                        data: {CSRF_TOKEN:csrf,list_type_id:list_type_id,ip:ip,max_to_dt:max_to_dt,max_weekly_no:max_weekly_no,max_weekly_year:max_weekly_year,courtno:courtno},
                        beforeSend:function(){                
                            $("#d_"+courtno).children(".save_action").html('Loading <i class="fas fa-sync fa-spin"></i>');
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(data, status) {
                            if(data.status == 'success'){
                                swal({title: "Success!",text: "Successfully Freezed",icon: "success",button: "success!"});                            
                                $("#d_"+courtno).html("<br><span class='text-success font-weight-bolder'>Success</span>");
                            } else{
                                swal({title: "Error!",text: data.status,icon: "error",button: "error!"});
                                $("#d_"+courtno).children(".save_action").html(action_content);
                            }
                            updateCSRFTokenSync();                    
                        },
                        error: function(xhr) {
                            updateCSRFTokenSync();
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });        
                } else {
                    swal("Cancelled", "Please try again :)", "error");
                }
                updateCSRFTokenSync();
            })
        });
        
        $(document).on('click', '.delete_action', function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var freeze_id = $(this).data('freeze_id');
            var ip = '<?=$clientIP?>';
            var courtno = $(this).data('court_number');
            $.ajax({
                url:'<?php echo base_url('Listing/hybrid/freeze_delete'); ?>',
                cache: false,
                async: true,
                data: {CSRF_TOKEN:csrf,freeze_id:freeze_id,ip:ip,courtno:courtno},
                beforeSend:function(){
                    $(this).html('Already Freezed! Want To Unfreeze? <i class="fas fa-sync fa-spin"></i>');
                },
                type: 'POST',
                dataType: "json",
                success: function(data, status) {
                    if(data.status == 'success') {
                        $(".freeze_id_"+freeze_id).html("<span class='text-danger font-weight-bolder'>Unfreezed</span>");
                        swal({title: "Success!",text: "Unfreezed Successfully",icon: "success",button: "success!"});
                    } else {
                        swal({title: "Error!",text: data.status,icon: "error",button: "error!"});
                        $(this).html('Already Freezed! Want To Unfreeze?');
                    }
                    updateCSRFTokenSync();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
            updateCSRFTokenSync();
        });
    </script>
<?=view('sci_main_footer') ?>