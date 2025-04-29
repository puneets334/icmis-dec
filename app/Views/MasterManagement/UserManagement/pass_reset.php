<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> User Management >> USER PASSWORD RESET</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <?= csrf_field(); ?>
                        <div class="container mt-4">
                            <div id="dv_content1">
                                <div class="top1 mb-3">
                                    <?php
                                    if ($name[0] != 1) {
                                        exit();
                                    }
                                    ?>
                                    <div class="form-row align-items-center">
                                        <div class="form-group col-md-4">
                                            <label for="u_pass_um" class="sr-only">Employee ID</label>
                                            <input type="text" class="form-control" id="u_pass_um" oninput="limitInputLength(this)" placeholder="Employee ID" onkeypress="return onlynumbers(event)" />
                                        </div>
                                        <div class="form-group col-md-4">
                                            <button type="button" class="btn btn-primary" onclick="getUserInfo__()">Show</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="hmm_result"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/pass_reset.js') ?>"></script>