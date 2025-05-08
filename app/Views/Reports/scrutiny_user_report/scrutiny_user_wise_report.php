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
                                <h3 class="card-title">Filing >> Scrutiny >> Report >> User wise Defect Report</h3>
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

                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="on_date" class="col-sm-6">On Date:</label>
                                            <input type="text" id="on_date" value="<?php echo isset($_POST['on_date']) ? $_POST['on_date'] : ''; ?>" name="on_date" class="form-control datepick" autocomplete="off" placeholder="On Date" required="required">
                                        </div>

                                        <div class="col-sm-4">
                                        <button type="submit"  id="view" name="view" class="btn btn-block btn-primary mt-5">View</button>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>


                    <div class="container">

                        <!-- Main content -->
                     

                            <?php
                            if (isset($_POST) && !empty($_POST)) {
                            ?><h3 style="text-align: center;">User wise Defect Report on <?php echo $_POST['on_date'] ?></h3>
                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <table id="datatable" class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th rowspan='2'>SNo.</th>
                                                <th rowspan='2'>Scrutiny User</th>
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
                                                        <td><?php echo $result['name'] ?? ''; ?></td>
                                                        <td><a target="_blank" href="<?php echo base_url() ?>/Reports/Filing/Filing_Reports/scrutiny_user_wise_defect_detail_report/<?= $result['usercode']; ?>/<?= $result['save_date'] ?? ''; ?>/<?= str_replace(array(' ', '.'), '_', $result['name'] ?? ''); ?>"> <?= $result['total']; ?></a></td>

                                                    </tr>
                                                <?php
                                                    $total_diary += $result['total'];
                                                }
                                                ?>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="2">Total</td>
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

                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
    </div>
</section>
<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });


    $(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

</script>