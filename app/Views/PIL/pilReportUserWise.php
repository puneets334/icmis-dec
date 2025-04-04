<?= view('header') ?>

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
        .box.box-danger {
            border-top-color: #dd4b39;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">PIL(E) >> Pil Report</h3>
                                </div>
                            </div>
                            <?php if (session()->getFlashdata('infomsg')) { ?>
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('infomsg') ?></strong>
                                </div>

                            <?php } ?>
                            <?php if (session()->getFlashdata('success_msg')) : ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                </div>
                            <?php endif; ?>

                        </div>

                        <span class="alert alert-error" style="display: none;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <span class="form-response"> </span>
                                </span>

                        <?= view('PIL/pilReportHeading'); ?>


                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        </br></br>

                        <div class="row col-md-12 ">

                            <div class="col-md-3">
                                <label class="control-label"><h5>From Date</h5></label>
                                <input type="text" id="from_date" name="from_date" class="form-control dtp" value="<?php echo date('d-m-Y', strtotime('first day of this month'));?>" required placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <label class="control-label"><h5>To Date</h5></label>
                                <input type="text" class="form-control dtp" id="to_date" required value="<?php echo date('d-m-Y');?>" name="to_date" placeholder="To Date">
                            </div>
                            <div class="col-md-3">
                                <button type="button" style="margin-left: 7%;margin-top: 11%;" id="view" name="save" value="submit" onclick="checkDates()" class="btn btn-primary">View</button>
                            </div>
                            <?php form_close(); ?>

                        </div> <br><br> <br>

                        <div id="userwise_report_data" class="box box-danger"></div>
                    </div>

                </div>
            </div>

        </div> <!-- card div -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->
    <script>

$(document).ready(function() {
        $('.dtp').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'

        });
    });
    checkDates();
        function checkDates() {
            var fromDate = document.getElementById('from_date').value;
            var toDate = document.getElementById('to_date').value;

            if((fromDate == '') || (toDate == ''))
            {
                alert("Please select the from date and to date !!!!");
                document.getElementById('from_date').focus();
                // document.getElementById('to_date').focus();

            }else{
                date1 = new Date(fromDate.split('-')[2],fromDate.split('-')[1]-1,fromDate.split('-')[0]);
                date2 = new Date(toDate.split('-')[2],toDate.split('-')[1]-1,toDate.split('-')[0]);
                if (date1 > date2)
                {
                    alert("To Date must be greater than From date");
                    return false;
                }
                // document.getElementById('push-form').submit();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('PIL/PilController/getPilUserWise'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        from_date: fromDate,
                        to_date: toDate,

                    },
                    success: function (data) {
                        // alert(data);
                        // return false;
                        updateCSRFToken();
                        $("#userwise_report_data").html(data);

                    },
                    error: function (data) {
                        updateCSRFToken();
                        alert(data);

                    }

                });
            }

        }
        $(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["print", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });


    </script>