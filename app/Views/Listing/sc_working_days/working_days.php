<?= view('header') ?>
    <!-- Main content -->
    <style>
        .center2 {
            margin: auto;
            width: 40%;            
            padding: 10px;
            text-align: center;
        }
        hn1 { 
            display: block;
            font-size: 1.2em;
            margin-top: 0.67em;
            margin-bottom: 0.67em;
            margin-left: 0;
            margin-right: 0;
            font-weight: bold;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <!-- /.card-header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p> <!-- This Segment Displays The Validation Rule -->
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder">SCI Calendar</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-row col-12 px-2">
                                                    <?php
                                                    $attributes = 'class="row g-3"';
                                                    $action = base_url('Listing/SCWorkingDays/get_working');
                                                    echo form_open($action, $attributes);
                                                        echo csrf_field();
                                                        ?>
                                                        <div class="d-inline px-2">
                                                            <div class="input-group mb-3">
                                                                <div class="input-label">
                                                                    <label>Listing/Verification Date: </label>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <input type="text" class="form-control cus-form-ctrl dtp" name='is_working' id='is_working' value="<?php echo date('d-m-Y');?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-inline px-2">
                                                            <div class="pl-2 mb-3">
                                                                <button id="submit" name="submit" type="button" class="btn btn-success btn-block">GET</button>
                                                            </div>
                                                        </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12 m-0 p-0" id="result"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>        
        $(document).on("focus", ".dtppp", function () {
            $(this).datepicker({
                format: "dd-mm-yyyy",
                changeMonth: true,
                changeYear: true,
                yearRange: "1950:2050"
            });
        });


        $("#submit").click(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var is_working = $("#is_working").val();
            $.ajax({
                url: '<?php echo base_url('Listing/SCWorkingDays/get_working'); ?>',
                cache: false,
                async: true,
                data: {CSRF_TOKEN:csrf,is_working:is_working},
                beforeSend:function(){
                    $('#result').html('<table width="100%" align="center"><tr><td><img src="<?php echo base_url('images/load.gif'); ?>"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $("#result").html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
            updateCSRFToken();
        });
    </script>