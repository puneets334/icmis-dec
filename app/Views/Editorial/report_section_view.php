<?php  $uri = current_url(true); ?>
<?= view('header') ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Editorial >> Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                <?php if(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= session()->getFlashdata('error')?>
                                    </div>
                                <?php } else if(session("message_error")){ ?>
                                    <div class="alert alert-danger text-danger" style="color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?=session("message_error")?>
                                    </div>
                                <?php }else{?>

                                <?php }?>
                            </div>
                            <div class="col-md-12">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a onclick="get_date_view(1)" class="nav-link active" href="#date_wise_div" data-toggle="tab">Date Wise</a></li>
                                        <li class="nav-item"><a onclick="get_date_view(2)" class="nav-link <?php if(($uri->getSegment(4))=="caveat_search"){ echo 'active';}?>" href="#user" id="user_wise_search" data-toggle="tab">User Wise</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div id="load_view"> </div>

                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script>
        get_date_view(1);

        function get_date_view(type) {
            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "GET",
                data: { type: type},
                url: "<?php echo base_url('Editorial/ESCR/get_view'); ?>",
                success: function (data)
                {
                    $('#load_view').html(data);

                    //  updateCSRFToken();
                }
                // error: function () {
                //     updateCSRFToken();
                // }
            });

        }


    </script>



 <?=view('sci_main_footer') ?>