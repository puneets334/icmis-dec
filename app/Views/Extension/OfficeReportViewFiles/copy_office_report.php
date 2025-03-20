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
                                    <h3 class="card-title">Office Reports </h3>
                                </div>
                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?=base_url('Extension/OfficeReport');?>"><button class="btn btn-info btn-sm" type="button"><i class="fas fa-pencil	" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?= view('Extension/OfficeReportViewFiles/office_report_menus'); ?>
                                        <?php
                                        if($_SESSION['filing_details']['c_status']=='D')
                                            echo '<br/><br/><span class="text-red">The searched case is disposed. Office report in disposed case is not allowed.</span>';
                                        else{
                                        $attribute = array('class' => 'form-horizontal copy_or','name' => 'copy_or', 'id' => 'copy_or', 'autocomplete' => 'off');
                                        echo form_open(base_url('#'), $attribute);
                                        ?>




                                        <br><br><br>

                                                                                <div >
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <div class="form-group row " >
                                                                                                <label for="from" class="col-sm-3 col-form-label">Old Listing Date: </label>
                                                                                                <div class="col-sm-7">
                                                                                                    <input type="date" name="ddl_old_ord_date" class="form-control" id="ddl_old_ord_date" size="9" maxlength="10" class="dtp"/>&nbsp;&nbsp;
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                  <!--</div>
                                        
                                                                                    <div class="row">-->
                                                                                        <div class="col-sm-6">
                                                                                            <div class="form-group row " >
                                                                                                <label for="from" class="col-sm-3 col-form-label">New Listing Date: </label>
                                                                                                <div class="col-sm-7">
                                                                                                    <input type="date" name="ddl_new_ord_date" class="form-control" id="ddl_new_ord_date" size="9" maxlength="10" class="dtp"/>&nbsp;&nbsp;
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <pre> <font color="red">
    Note:-
                  1) In Old Order Date box, enter the old order date for which office report was prepared in advance.

                  2) In New Order Date box,enter the new order date in which the contents of office report are to be copied.
              </font>
              </pre>
                                                                                    </div>
                                        
                                                                                    <div class="row">
                                                                                        <div class="col-sm-4">
                                                                                            <div class="form-group row">
                                                                                                <div class="col-sm-10">
                                                                                                    <button type="submit" class="btn btn-primary" id="submit" >Submit</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>




                                    </div>
                                    <?php form_close();
                                        }?>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
    <script>
        var validationError = false;
            $('#copy_or').on('submit', function () {
                var old_date = $("#ddl_old_ord_date").val();
                var new_date =$('#ddl_new_ord_date').val();

                if (old_date=='') {
                    alert("Please enter Old Listing Date");
                    $('#ddl_old_ord_date').focus();
                    validationError = true;
                    return false;
                }
                if (new_date=='') {
                    alert("Please enter New Listing Date");
                    $('#ddl_new_ord_date').focus();
                    validationError = true;
                    return false;
                }
                if(validationError==false){
                  //  var form_data = $(this).serialize();
                   // alert(form_data);
                    var d_no= '<?php echo $_SESSION['filing_details']['diary_no']; ?>';
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Extension/OfficeReport/CopyOR'); ?>",
                        data: {
                            CSRF_TOKEN: CSRF_TOKEN_VALUE,
                            old_date: old_date,
                            new_date: new_date,
                            d_no: d_no
                        },
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

                        },
                        success: function (data) {
                            alert(data);
                            updateCSRFToken();
                        },
                        error: function () {
                            updateCSRFToken();
                        }

                    });
                    return false;
                }
                else {
                    return false;
                }
            });
/*
 var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Diary/generate'); ?>",
                        data: form_data,
                        async: false,
                        beforeSend: function () {
                            $('.form_save_first').val('Please wait...');
                            //$('.form_save_first').prop('disabled', true);
                        },
                        success: function (data) {
                            alert(data);
                            ///$('#pet_save').val('SAVE');
                            //$('#pet_save').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                $('.alert-success').show();
                                //window.location.href = resArr[2];
                                //location.reload();

                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                            updateCSRFToken();
                        },
                        error: function () {
                            updateCSRFToken();
                        }

                    });
                    return false;
                }
 */
    </script>
 <?=view('sci_main_footer') ?>