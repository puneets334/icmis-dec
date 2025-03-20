<?= view('header') ?>

    <style>

    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Judicial >> Sentence Status >> Report</h3>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <!-- <div class="card">
                                    <div class="card-body"> -->
                                        <center><b style="text-align: center;"> <h3> In Jail / On Bail Report</h3></b> </center><br/>
                                        <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>


                                        <?php
                                        $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                        //echo form_open(base_url('#'), $attribute);
                                        ?>
                                       <form action="<?= base_url('#'); ?>" class="form-horizontal" name="component_search" 
                                        id="component_search" autocomplete="off" method="post" >
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="case_status" class="col-sm-5 col-form-label">Case States</label>
                                                    <div class="col-sm-7">
                                                        <select name="case_status" id="case_status" class="form-control">
                                                            <option value="A" selected="">All</option>
                                                            <option value="P">Pending</option>
                                                            <option value="D">Disposed</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="jail_bail" class="col-sm-5 col-form-label">Type</label>
                                                    <div class="col-sm-7">
                                                        <select name="jail_bail" id="jail_bail" class="form-control">
                                                            <option value="A" selected="">All</option>
                                                            <option value="C">In Jail</option>
                                                            <option value="B">On Bail</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                            </div>
                                        </div>

                                        <!--<center> <button type="submit" class="btn btn-primary" id="submit">Submit</button></center>-->
                                        <?php form_close();?>
                                        <br/>
                                        <center><span id="loader"></span> </center>
                                        <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                        <div id="record" class="record"></div>

                                    <!-- </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- 
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script> -->
    <script>
        $(document).ready(function() {
            $('#component_search').on('submit', function (e) {
                e.preventDefault();
                
                var case_status =$('#case_status :selected').val();
                var jail_bail =$('#jail_bail :selected').val();
                if (case_status.length == 0) {
                    alert("Please select case status");
                    validationError = false;
                    return false;
                }else if (jail_bail.length == 0) {
                    alert("Please select Jail type");
                    validationError = false;
                    return false;
                }
                
                //if ($('#component_search').valid()) { }
                    //alert(1);
                    var validateFlag = true;
                    var form_data = $(this).serialize();
                    if(validateFlag){
                        //var CSRF_TOKEN = 'CSRF_TOKEN';
                        //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide(); $(".form-response").html("");
                        $("#loader").html('');
                        $.ajax({
                            type: "GET",
                            url: "<?php echo base_url('Judicial/Sentence/Report/get_content'); ?>",
                            data: form_data,
                            beforeSend: function () {
                                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                            success: function (data) {
                                $("#loader").html('');
                                //updateCSRFToken();
                                var resArr = data.split('@@@');
                                if (resArr[0] == 1) {
                                    $('#record').html(resArr[1]);
                                } else if (resArr[0] == 3) {
                                    $('.alert-error').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                }
                            },
                            error: function() {
                                //updateCSRFToken();
                                alert('Something went wrong! please contact computer cell');
                            }
                        });
                        return false;
                    }
                 
                
            });
        });
       

    </script>