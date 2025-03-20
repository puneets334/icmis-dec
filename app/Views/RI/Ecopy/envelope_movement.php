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
                                    <h3 class="card-title">R & I >> E-copy >> Envelope Receive Module</h3>
                                </div>
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
 
 


                        <?php
                        $attribute = array('class' => 'form-horizontal', 'name' => 'ecopy', 'id' => 'ecopy', 'autocomplete' => 'off', 'method' => 'POST');
                        echo form_open(base_url('#'), $attribute);
                        ?>

                       

                            <!-- <div class="card-header bg-info text-white font-weight-bolder">Envelope Receive Module </div> -->
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                              
                                                <input id="btn_search" name="btn_search" type="button" class="btn btn-success" value="Envelopes to Receive">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br> <br>
                               
                            </div>
 

                        <?php form_close(); ?>


                        <br> 
                        <div class="col-md-12 " id="result"></div>
                        <br> 



                        <!-- /.content -->
                        <!--</div>-->
                        <!-- /.container -->
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

        $("#btn_search").click(function(){
            // alert("fdf");
            // return false;
            $('#show_error').html("");
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url:'<?=base_url('/RI/EcopyController/get_envelope_movement');?>',
                cache: false,
                async: true,
                data: { CSRF_TOKEN: CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('#result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                },
                success: function(data, status) {
                    updateCSRFToken();
                    $("#result").html(data);
                    

                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        });

        $(document).on('click', '.btn_consume', function () {
            var barcode = $(this).data('barcode');
            $(".validation").remove(); // remove it
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $('#show_error').html("");
            $.ajax({
                url:'<?=base_url('/RI/EcopyController/envelope_movement_save');?>',
                cache: false,
                async: true,
                context: this,
                data:{
                    barcode:barcode,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',

                success: function(data) {
                    updateCSRFToken();
                    // alert(data);
                    var response = JSON.parse(data);
                    console.log(response.status);
                    if(response.status == 'success'){
                        
                        $(this).parents('.cell_tr').replaceWith('<td><button type="button" class="btn btn-success">'+response.status+'</button></td>');
                    }
                    else{
                         
                        $(this).closest('tr').find(".btn_consume").after('<button type="button" class="btn btn-danger">'+response .status+'</button>');
                    }
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        });
    </script>
