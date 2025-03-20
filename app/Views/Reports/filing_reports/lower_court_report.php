<?= view('header'); ?>

<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

    .btn-sm {
        padding: 0px 8px;
        font-size: 14px;
    }

    .card-header {
        padding: 5px;
    }

    h4 {
        line-height: 0px;
    }

    .c_vertical_align th {
        vertical-align: middle;
    }
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>/filing/diary_search.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Scrutiny >> Report >> Lower Court Report</h3>
                            </div>

                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                    <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-5">

                            <form method="post" id="push-form" action="<?= site_url(uri_string()) ?>">
                                <?= csrf_field() ?>

                                <div class="row box-body">

                                    <div class="col-md-4">
                                        <div class="col-sm-12">
                                            <label for="on_date" class="col-sm-6">On Date:</label>
                                            <input type="text" id="on_date" value="<?php echo isset($_POST['on_date']) ? $_POST['on_date'] : ''; ?>" name="on_date" class="form-control datepick" autocomplete="off" placeholder="On Date" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">                                        
                                        <button type="submit" id="view" name="view" class="btn btn-block btn-primary mt-3">View</button>
                                        
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">

                            <!-- Main content -->
                            <section class="content">

                                <?php

                                if (isset($_POST) && !empty($_POST)) {
                                ?>
                                    <div class="box-footer">
                                        <form>
                                            <button type="submit" class="btn btn-warning" id="print" name="print" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                        </form>
                                    </div>
                                    <div id="printable" class="box box-danger">
                                        <div class="table-responsive">
                                            <table width="100%" id="reportTable" class="table table-striped custom-table">
                                                <thead>
                                                    <h3 style="text-align: center;">User wise Lower Court Report on <?php echo $_POST['on_date'] ?></h3>
                                                    <tr>
                                                        <th rowspan='2'>SNo.</th>
                                                        <th rowspan='2'>Lower Court User</th>
                                                        <th rowspan='2'>section</th>
                                                        <th rowspan='2'>No. of files</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($case_result) && count($case_result) > 0) {
                                                        $i = 0;
                                                        $total_diary = 0;
                                                      
                                                        foreach ($case_result as $result) {
                                                            $i++;


                                                    ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $result['name']; ?></td>
                                                                <td><?php echo $result['section']; ?></td>
                                                                <td><a target="_blank" href="<?php echo base_url() ?>/Reports/Filing/Filing_Reports/lowerct_user_wise_detail_report/<?= $result['ent_dt'] ?? ''; ?>/<?= $result['usercode'] ?? ''; ?>/<?= str_replace(array(' ', '.'), '_', $result['name'] ?? ''); ?>"> <?= $result['total']; ?></a></td>

                                                            </tr>
                                                        <?php
                                                            $total_diary += $result['total'];
                                                        }
                                                        ?>
                                                        <tr style="font-weight: bold;">
                                                            <td colspan="3">Total</td>
                                                            <td><?= $total_diary ?></td>
                                                        </tr>

                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="100%">No Record found...</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>

                                            </table>
                                        </div>


                                    <?php } ?>
                                    </div>
                            </section>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
</section>
<script>
    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>