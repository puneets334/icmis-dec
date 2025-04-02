<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Barcode Consume</h3>
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_breadcrumb'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                            <h4 class="basic_heading">Barcode Consume</h4>
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
                        <div class="form-row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Get Pending Applications">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 m-0 p-0" id="result"></div>
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
    $("#btn_search").click(function(){
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        updateCSRFToken();
        $.ajax({
            url: '<?php echo base_url('Copying/Copying/getbarcodeconsume'); ?>',
            cache: false,
            async: true,
            data:{
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend:function(){
                $('#result').html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            type: 'POST',
            success: function(data) {
                $("#result").html(data);
                updateCSRFToken();
            }

        });
    });
</script>
    <script>
        $(document).on('click', '.btn_consume',function(){
            var app_id = $(this).data('app_id');
            var envelope_weight = $(this).data('envelope_weight');
            var barcode = $(this).closest('tr').find(".barcode_id").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $(".validation").remove(); // remove it
            if (barcode.length < 12) {
                //alert("Proper Barcode Entry Required");
                $(this).closest('tr').find(".barcode_id").after('<br><strong class="validation alert alert-danger alert-dismissible p-1">Proper Barcode Entry Required*</strong>');
                $(this).closest('tr').find(".barcode_id").focus();
                return false;
            }

            $('#show_error').html("");
            $.ajax({
                url: '<?php echo base_url('Copying/Copying/barcodesave'); ?>',
                cache: false,
                async: true,
                beforeSend:function(){
                    $('#tr_row_'+app_id).html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: {
                    app_id: app_id,
                    barcode: barcode,
                    envelope_weight: envelope_weight,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data) {
                    data = data.trim();
                    if(data=='Y'){
                        $('#tr_row_'+app_id).html('');
                        $('#tr_row_'+app_id).css({"background":"#d4edda"});
                        $('#tr_row_'+app_id).html('<strong>Success</strong>');
                    }
                    else{
                        $('#tr_row_'+app_id).html('');
                        $('#tr_row_'+app_id).css({"background":"red"});
                        $('#tr_row_'+app_id).html('<strong>Error</strong>');
                    }
                    updateCSRFToken();
                }

            });

        });
    </script>