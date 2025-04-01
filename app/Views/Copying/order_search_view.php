<?= view('header') ?>
 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Search/Save Orders</h3>
                            </div>
                        </div>
                    </div>
                    <?// view('Copying/copying_breadcrumb'); ?> 
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                            <h4 class="basic_heading">Search/Save Orders</h4>
                    </div>  
                    <?php
                    $attribute = array('class' => 'form-horizontal order_search_form', 'name' => 'order_search_form', 'id' => 'order_search_form', 'autocomplete' => 'off');
                    echo form_open(base_url('Copying/Copying/download_order'), $attribute);
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
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

                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="form-group row">
                                                <label for="Dairy No." class="col-sm-5 col-form-label">Dairy No.</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" value="<?php if (!empty($formdata['diary_no'])) {
                                                                                                                                                                    echo $formdata['diary_no'];
                                                                                                                                                                } ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group row">
                                                <label for="Year" class="col-sm-5 col-form-label">Diary Year</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control select2" name="diary_year" id="diary_year" style="width: 100%;" required>
                                                        <?php echo !empty($formdata['diary_year']) ? '<option value=' . $formdata['diary_year'] . '>' . $formdata['diary_year'] . '</option>' : '' ?>
                                                        <option value="">Year</option>
                                                        <?php
                                                        $end_year = 47;
                                                        $sel = '';
                                                        for ($i = 0; $i <= $end_year; $i++) {
                                                            $year = (int) date("Y") - $i;
                                                            echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group row">
                                                <label for="From" class="col-sm-5 col-form-label">Order Date</label>
                                                <div class="col-sm-7">
                                                    <input type="date" class="form-control" id="order_date" name="order_date" placeholder="Order Date" value="<?php if (!empty($formdata['order_date'])) {
                                                                                                                                                                    echo $formdata['order_date'];
                                                                                                                                                                } ?>" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="input-group-append">
                                                <input type="submit" name="order_search" id="order_search" class="order_search btn btn-primary" value="Search">
                                            </span>
                                        </div>
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