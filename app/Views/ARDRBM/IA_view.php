<?= view('header') ?>
<?php $uri = current_url(true); 
//pr($uri);?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">AR-DR BRANCH MANGEMENT</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <div class="col-md-12">
                                        <div class="card-header p-2" style="background-color: #fff;">
                                            <ul class="nav nav-pills">
                                                <?php $menu_id = '';
                                               /* $is_url = $uri->getSegment(5);
                                                $url = $uri->getSegment(3) . '/' . $uri->getSegment(4);
                                                if (!empty($is_url)) {
                                                    $url = $url . '/' . $is_url;
                                                } */
                                                $url = $uri->getPath();
                                                $usercode = session()->get('login')['usercode'];
                                                $sqrs = get_sub_menus_ardar($usercode, 33); //echo '<pre>';print_r($sqrs);
                                                if (!empty($sqrs)) {
                                                    foreach ($sqrs as $menu) {
                                                        if ($menu['sml1_id'] == 3304 || $menu['sml1_id'] == 3307 || $menu['sml1_id'] == 3308) {
                                                            if ($menu['url'] == $url) {
                                                                $menu_id = $menu['sml1_id'];
                                                            }
                                                             ?>
                                                            <li class="nav-item"><a onclick="get_search_view(<?= $menu['sml1_id']; ?>)" class="nav-link <?php if ($menu['url'] == $url) {
                                                                                                                                                            echo 'active';
                                                                                                                                                        } ?>" href="#ARDRBM" data-toggle="tab"><?= $menu['menu_nm']; ?> </a></li>
                                                    <?php }
                                                    }
                                                } else { ?>

                                                <?php } ?>
                                        </div>


                                    </div>

                                    <div id="load_search_view"> </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</section>

<script>
    <?php if (!empty($menu_id)) { ?>
        get_search_view(<?= $menu_id; ?>);
    <?php } ?>

    function get_search_view(type) {
        $.ajax({
            type: "GET",
            data: {
                type: type
            },
            url: "<?php echo base_url('ARDRBM/IA/get_search_view'); ?>",
            success: function(data) {
                $('#load_search_view').html(data);
            }
        });
    }
</script>