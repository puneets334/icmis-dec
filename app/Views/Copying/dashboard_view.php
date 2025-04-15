<?= view('header') ?>
 
<style>
    .item {
        border: 1px solid #eee;
        box-shadow: 0 0 10px -3px #ccc;
        border-radius: 5px;
        margin-bottom: 30px;
        padding: 25px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">eCopying | Dashboard</h3>
                            </div>
                        </div>
                    </div>
                    <?= view('Copying/copying_breadcrumb'); ?>
                    <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                        <h4 class="basic_heading">eCopying | Dashboard</h4>
                    </div>

                    <form class="form-horizontal" id="push-form" method="post">
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
                                                    <label for="From" class="col-sm-4 col-form-label"> From Date</label>
                                                    <div class="col-sm-8">
                                                        <input type="date" class="form-control" id="from_date" name="from_date" placeholder="Order Date" value="<?php if (!empty($fromDate)) {
                                                                                                                                                                    echo $fromDate;
                                                                                                                                                                } ?>" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-4 col-form-label">To Date</label>
                                                    <div class="col-sm-8">
                                                        <input type="date" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php if (!empty($toDate)) {
                                                                                                                                                                echo $toDate;
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
                                                    <input id="btn_search" name="btn_search" type="button" class="btn btn-success ml-2" value="Search">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>

                        </div>
                    </form>
                </div>
                <div id="result"></div>
                <div>
        <div class="modal fade" id="myModal" >
            <div class="modal-dialog" style="max-width:1500px;margin-right:50px;">
                <div class="modal-content myModal_content">

                </div>
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
    function validateDates(fromDate,toDate) {
        //console.log('from date:'+fromDate+' todate'+toDate)
        //const date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        //const date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        //console.log('after from date:'+date1+' todate'+date2)
        return fromDate <= toDate;
    }

    function showAlert(message) {
        $('#result').html("");
        $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' + message + '</strong></div>');
    }
    $(document).on('click', '#btn_search',function() {
        const from_date = $("#from_date").val();
        const to_date = $("#to_date").val();
        const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (!from_date) {
            showAlert("From Date Required");
            $("#from_date").focus();
            return false;
        }
        if (!to_date) {
            showAlert("To Date Required");
            $("#to_date").focus();
            return false;
        }
        if (!validateDates(from_date, to_date)) {
            showAlert("To Date must be greater than or equal to From date");
            $("#to_date").focus();
            return false;
        }

        $.ajax({
            url: '<?php echo base_url('Copying/Copying/dashboard_count'); ?>',
            type: 'POST',
            data: {
                from_date: from_date,
                to_date: to_date,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#result').html('<table width="100%" align="center"><tr><td>Loading...</td></tr></table>');
            },
            success: function(data) {
                $('#result').html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                showAlert("An error occurred while processing your request.");
                updateCSRFToken();
            }
        });
    });

    $(document).on('click', '.dashboard_modal', function() {
        const from_date = $("#from_date").val();
        const to_date = $("#to_date").val();
        const flag = $(this).data('flag');
        const CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if(!from_date){
            showAlert("From Date Required");
            $("#from_date").focus();
            return false;
        }
        if (!to_date) {
            showAlert("To Date Required");
            $("#to_date").focus();
            return false;
        }
        if (!validateDates(from_date, to_date)) {
            showAlert("To Date must be greater than or equal to From date");
            $("#to_date").focus();
            return false;
        }

        $("#myModal").modal({ backdrop: false });
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Copying/Copying/dashboard_details'); ?>",
            data: {
                flag: flag,
                from_date: from_date,
                to_date: to_date,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            success: function(data) {
                $(".myModal_content").html(data);
                updateCSRFToken();
            },
            error: function(xhr) {
                showAlert("An error occurred while fetching the details.");
                updateCSRFToken();
            }
        });
    });
</script>