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

        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
        a {color:darkslategrey}      /* Unvisited link  */

        a:hover {color:black}    /* Mouse over link */
        a:active {color:#0000FF;}  /* Selected link   */

        .box.box-success {
            border-top-color: #00a65a;
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

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
        }
        .box-header.with-border {
            border-bottom: 1px solid #f4f4f4;
        }
        .box.box-danger {
            border-top-color: #dd4b39;
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
                                    <h3 class="card-title">R & I >>  E-copy  >> Envelope Received Report </h3>
                                </div>
                            </div>
                            <br><br>
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
                           <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="form-response"> </span></span>

                        <?php //= view('RI/Ecopy/EcopyHeading'); ?>
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'ecopy', 'id' => 'ecopy', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                        <!-- <div class="card-header bg-info text-white font-weight-bolder">eCopying | Envelope Received Report </div> -->
                        <br><br><br>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group row">
                                    <label for="from" class="col-sm-4 col-form-label" id="from_date_addon">From Date: </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="fromDate" name="fromDate" class="form-control from_date datepick" required placeholder="From Date" value="<?=!empty($fromDate)?$fromDate:null;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group row">
                                    <label for="to_date" class="col-sm-4 col-form-label" id="to_date_addon" >To Date:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="toDate" name="toDate" class="form-control to_date datepick" required placeholder="From Date" value="<?=!empty($toDate)?$toDate:null;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button"  id="btn_search" name="btn_search" class="btn btn-block btn-primary">Search</button>
                            </div>
                        </div>
                        <?php form_close(); ?>
                        <br><br><br><br>
                        <!-- /.content -->
                        <!--</div>-->
                        <!-- /.container -->

                        <div class="row col-md-12 m-2 p-2" id="result"></div>


                    </div>
                    <br><br><br>
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

$(function() {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,

            autoclose: true
        });
    });
    $(document).on('click','#btn_search',function() {
        // alert("EEEEEeeeee");
        // return false;
        var from_date = $(".from_date").val();
        var to_date = $(".to_date").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (from_date == '') {
            $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>From Date Required</strong></div>');
            $(".from_date").focus();
            return false;
        }
        if (to_date == '') {
            $('#result').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>To Date Required</strong></div>');
            $(".to_date").focus();
            return false;
        }

        $.ajax({
            url: '<?=base_url('/RI/EcopyController/envelope_movement_report_get');?>',
            cache: false,
            async: true,
            context: this,
            beforeSend: function () {
                $('#result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                from_date: from_date,
                to_date: to_date
            },
            type: 'POST',
            success: function (data, status) {
                updateCSRFToken();
                // alert(data);
                // return false;
                $('#result').html(data);
            },
            error: function (xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>