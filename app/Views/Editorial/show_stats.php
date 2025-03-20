<?php //$uri = current_url(true); ?>
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
                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                            <?php if (session()->getFlashdata('error')) { ?>
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php } else if (session("message_error")) { ?>
                                <div class="alert alert-danger text-danger" style="color: red;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= session("message_error") ?>
                                </div>
                            <?php } else { ?>

                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="date_wise">
                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'method'=>'POST', 'name' => 'both_form', 'id' => 'both_form', 'autocomplete' => 'off');
                                            echo form_open(base_url('#'), $attribute);


                                            ?>

                                            <div class="mt-2">

                                                <ul class="nav nav-pills ">

                                                    <li class="nav-item" ><label class="radio-inline"><input type="radio" name="optradio"   value="1" checked>Date Wise</label></li> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <li class="nav-item" ><label class="radio-inline"><input type="radio" name="optradio"  value="2" >User Wise</label></li>
                                                </ul>
                                            </div>

                                            <br><br>


                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group row">
                                                        <label for="from" class="col-sm-6">From Date</label>
                                                        <div class="col-sm-6">
                                                            <input type="date" id="fromDate" name="fromDate" class="form-control datepick" required placeholder="From Date">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group row">
                                                        <label for="from" class="col-sm-6">To Date</label>
                                                        <div class="col-sm-6">
                                                            <input type="date" id="toDate" name="toDate" class="form-control datepick" required placeholder="To Date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">

                                                    <div class="form-group row">

                                                        <div class="col-sm-7">
                                                            <button type="submit" id="view" name="view" value="date_wise" class="btn btn-block btn-primary" >View</button>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <?php form_close(); ?>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <br><br>


                                        <div id="data_report"> </div>

                                        <br><br>

                                    </div>
                                    <!-- /.tab-content -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.row -->


            </div>
            <!-- /.container-fluid -->
</section>
<!-- /.content -->


<script>

    $('input[name="optradio"]').on('change', function(e){
        $("#fromDate").val('');
       $('#toDate').val('');
        });

    var validationError = false;
    $('#both_form').on('submit', function () {
        var from_date = $("#fromDate").val();
        var to_date =$('#toDate').val();
        var optradio = $('input[name="optradio"]:checked').val();

        if (from_date=='') {
            alert("Please Enter From Date");
            $('#fromDate').focus();
            validationError = true;
            return false;
        }
        if (to_date=='') {
            alert("Please Enter To Date");
            $('#toDate').focus();
            validationError = true;
            return false;
        }
        if(validationError==false){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Editorial/ESCR/show_count'); ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    fromDate: from_date,
                    toDate: to_date,
                    optradio: optradio,
                    },
                success: function (data) {
                    updateCSRFToken();
                    // console.log(data);return false;
                    $("#data_report").html(data);

                },
                error: function (data) {
                    updateCSRFToken();
                    alert(data);

                }

            });
            return false;
        }
        else {
            return false;
        }
    });


</script>













