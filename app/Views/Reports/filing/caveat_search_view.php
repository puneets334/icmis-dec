
<!-- caveat start -->
<div class="tab-pane" id="Caveat">
    <?php  $attribute = array('class' => 'form-horizontal caveat_search_form','name' => 'caveat_search_form', 'id' => 'caveat_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);             ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="From" class="col-form-label">From</label>
                                <input  type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">

                            <div class="form-group row">
                                <label for="To" class="col-form-label">To</label>
                                
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                      
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="Dairy No." class="col-form-label">Caveat No.</label>
                                
                                    <input type="number" class="form-control" id="caveat_no" name="caveat_no" placeholder="Enter Caveat No" value="<?php if(!empty($formdata['caveat_no'])){ echo $formdata['caveat_no']; } ?>">
                                </div>
                            </div>
                      
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="Year" class="col-form-label">Year</label>
                                
                                    <select class="form-control select2" name="caveat_year" id="caveat_year"style="width: 100%;">
                                        <?php echo !empty($formdata['caveat_year']) ? '<option value='.$formdata['caveat_year'].'>'.$formdata['caveat_year'].'</option>': '' ?>
                                        <option value="">Year</option>
                                        <?php
                                        $end_year = 47; $sel = '';
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                    </div>
                    <div class="row ">

                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">

                            <div class="form-group row">
                                <label for="Party" class="col-form-label">Party</label>
                                
                                    <select name="ddl_party_type" id="ddl_party_type" class="form-control " style="width: 100%;">

                                        <option value="All">All</option>
                                        <option value="P">Caveator</option>
                                        <option value="R">Caveatee</option>
                                    </select>
                                </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="Cause Title" class="col-form-label">Cause Title</label>
                                
                                    <input type="text" class="form-control" name="cause_title" id="cause_title" value="<?php if(!empty($formdata['cause_title'])){ echo $formdata['cause_title']; } ?>" placeholder="Cause Title">
                                </div>
                            </div>
                        
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="Case Type" class="col-form-label">Case Type</label>
                                
                                    <select name="case_type_casecode" id="case_type_casecode" class="custom-select" style="width: 100%;">
                                        <option value="">Select case type</option>
                                        <?php
                                        foreach ($casetype as $row) {?>
                                            <!-- echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>'; -->
                                            <option value=<?=sanitize(($row['casecode']))  ?> <?php if(!empty($formdata['case_type_casecode'])&& sanitize(($row['casecode']==$formdata['case_type_casecode']))){  echo 'selected'; }?>><?=sanitize(strtoupper($row['casename']))?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group row">
                                <label for="Status" class="col-form-label">Status</label>
                                
                                    <select name="ddl_status" id="ddl_status" class="form-control " style="width: 100%;">
                                        <option value="All">All</option>
                                        <option value="P">Pending</option>
                                        <option value="D">Expired(>90 days)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="row ">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                        </div>
                        <!--<div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="csps" value="CS" checked >
                                <label class="form-check-label">Cause Title Search</label>
                            </div>-->
                        </div>
                        <!--<div class="col-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="csps" value="PS" >
                                <label class="form-check-label">Party Search</label>
                            </div>

                        </div>-->


                    </div>
                    <!--<div class="row ">

                        <div class="col-sm-6">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" name="caveat_greater_then_ninty_days" id="caveat_greater_then_ninty_days" <?php /*if(!empty($formdata['caveat_greater_then_ninty_days_no'])){ echo 'checked'; } */?>>
                                <label for="caveat_greater_then_ninty_days">Caveats Filed And Expired(>90 days old)</label>
                            </div>

                        </div>

                    </div>-->
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">

                        <input type="submit" name="caveat_search" id="caveat_search"  class="caveat_search btn btn-primary" value="Search">
                        <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">

                        </div>
                    </div>
                    <span class="alert alert-error" style="display: none;text-align: center;color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>

    </div>
    <!--/.col (right) -->
    <?= form_close()?>

</div>
<!-- /.caveat -->
<center><span id="loader"></span> </center>
<div id="result_data"></div>


<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>
<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    var validationError = false;
    $('#caveat_search_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var caveat_no = $("#caveat_no").val();
        var cause_title = $("#cause_title").val();

        if(from_date.length== 0 && caveat_no.length==0 && cause_title.length==0 ) {
            alert("Please Fill any one field .");
            $("#from_date").focus();
            validationError = true;
            return false;
        }

        if(from_date.length != 0) {
            if (to_date.length == 0) {
                alert("Please select to date.");
                $("#to_date").focus();
                validationError = true;
                return false;
            }}

            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            var diffMonths = (date2.getFullYear() - date1.getFullYear()) * 12 + (date2.getMonth() - date1.getMonth());
            var diffMilliseconds = Math.abs(date2 - date1);
            var oneDayMilliseconds = 1000 * 60 * 60 * 24;
            var diffDays = Math.ceil(diffMilliseconds / oneDayMilliseconds);
            if (date1 > date2 || diffDays > 31) {
                alert("To Date must be greater than From date and the days interval must be one month");
            if (date1 > date2) {
                alert("To Date must be greater than From date");
                $("#to_date").focus();
                validationError = true;
                return false;
            } else {
                if (from_date.length == 0) {
                    alert("Please select from date.");
                    $("#from_date").focus();
                    validationError = true;
                    return false;
                } else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = true;
                    return false;
                }
            }

        }

        if(caveat_no.length != 0){
            var caveat_year= $('#caveat_year').val();
            if(caveat_year==''){
                alert("Please Select Year.");
                $("#caveat_year").css('border-color', 'red');
                $('#caveat_year').prop('disabled', false);
                return false;
            }

        }


    if(!(validationError))  {
            var validateFlag = true;
            var form_data = $(this).serialize();
          //  if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/caveat_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                      //  $('.caveat_search').val('Please wait...');
                    //    $('.caveat_search').prop('disabled', true);
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");

                    },
                    success: function (data) {
                        updateCSRFToken();
                        $('.caveat_search').prop('disabled', false);
                        $('.caveat_search').val('Search');
                        $("#loader").html('');
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('.alert-error').hide();
                            $(".form-response").html("");
                            $('#result_data').html(resArr[1]);
                        } else if (resArr[0] == 3) {
                            $('#result_data').html('');
                            $('.alert-error').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        }


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

</script>


