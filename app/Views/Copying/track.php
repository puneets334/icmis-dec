<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Track Your Consignment </h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_breadcrumb'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                            <h4 class="basic_heading">Track Your Consignment</h4>
                    </div>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Consignment No.</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="cn" name="cn" placeholder="Enter Consignment No" value="<?php if (!empty($formdata['cn'])) {
                                                                                                                                                            echo $formdata['cn'];
                                                                                                                                                        } ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <span class="input-group-append">
                                                <input type="submit" name="cn_search" id="cn_search" class="cn_search btn btn-primary" value="Search">
                                            </span>
                                        </div>
                                        <div class="col-sm-7"></div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                    </div>
                    <?= form_close() ?>

                </div>
                <div id="result_data"></div>
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
<script type="text/javascript">
          $(document).on('click', '#radioBtn a', function () {
          // $('#radioBtn a').on('click', function(){

          var sel = $(this).data('title');
          var tog = $(this).data('toggle');
          $('#'+tog).prop('value', sel);

          $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');
          $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');

          if(sel == 'timeline_show'){
              $("#show_tracking").removeClass("d-none");
              $(".show_table_data").addClass("d-none");
          }
          if(sel == 'table_show'){
              $("#show_tracking").addClass("d-none");
              $(".show_table_data").removeClass("d-none");
          }
      });

    $(document).on('click', '#cn_search', function() {

        var cn = $("#cn").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var maxLength = 10;
        if (cn.toString().length==0) {
            $('#show_error').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please Enter Consignment No.</strong></div>');
            $('#cn').focus();
            return false;
        }
        else if (cn.toString().length < maxLength) {
            $('#show_error').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please Enter Valid Consignment No.</strong></div>');
            $('#cn').focus();
            return false;
        }
        $.ajax({
            url: '<?php echo base_url('Copying/Copying/getConsignmentDetails'); ?>',
            data: {
                cn: cn,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            type: 'POST',
            success: function(data) {
                $('#show_error').html('');
                $('#result_data').html(data);
                updateCSRFToken();
            },
            error: function () {
               updateCSRFToken();
            }

        });

});
</script>