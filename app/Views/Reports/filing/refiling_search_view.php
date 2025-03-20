
<div class="active tab-pane" id="Refiling">
    <?php
    $attribute = array('class' => 'form-horizontal refiling_search_form','name' => 'refiling_search_form', 'id' => 'refiling_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">From</label>
                                <div class="col-sm-7">
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date"  value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Dairy No." class="col-sm-5 col-form-label">Dairy No.</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" value="<?php if(!empty($formdata['diary_no'])){ echo $formdata['diary_no']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Year</label>
                                <div class="col-sm-7">
                                    <select class="form-control select2" name="diary_year" id="diary_year"style="width: 100%;">
                                        <?php echo !empty($formdata['diary_year']) ? '<option value='.$formdata['diary_year'].'>'.$formdata['diary_year'].'</option>': '' ?>
                                        <option value="0">Year</option>
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
                    </div>
<!--                    <div class="row ">-->
<!---->
<!--                        <div class="col-sm-3">-->
<!--                            <div class="form-check">-->
<!--                                <input class="form-check-input" type="radio" name="reportview" value="cv" --><?php //if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'cv'){echo 'checked';}}?><!-->
<!--                                <label class="form-check-label">Complete View</label>-->
<!--                            </div>-->
<!---->
<!---->
<!--                        </div>-->
<!--                        <div class="col-sm-3">-->
<!--                            <div class="form-check">-->
<!--                                <input class="form-check-input" type="radio" name="reportview" value="sv" --><?php //if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?><!-->
<!--                                <label class="form-check-label">Summary View</label>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                        <input type="submit" name="refiling_search" id="refiling_search"  class="refiling_search btn btn-primary" value="Search">
                        <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
       <?= form_close()?>

 </div>
         <div id="result_data"></div>
      </div>
   </div>
 </div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#refiling_search_form').on('submit', function () {

        var diary_no = $("#diary_no").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(diary_no.length == 0) {
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            if (date1 > date2 &&  date2 < date1  ) {
                alert("To Date must be greater than From date");
                $("#to_date").focus();
                validationError = false;
                return false;
            } else {
                if (from_date.length == 0) {
                    alert("Please select from date.");
                    $("#from_date").focus();
                    validationError = false;
                    return false;
                }
                else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = false;
                    return false;
                }
            }
        }else{

            var diary_year = $("#diary_year").val();
            if(diary_year=='') {
                alert("Please Select Year.");
                $("#ddl_court").css('border-color', 'red');
                return false;
            }

        }

        if ($('#refiling_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/refiling_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.refiling_search').val('Please wait...');
                        $('.refiling_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.refiling_search').prop('disabled', false);
                        $('.refiling_search').val('Search');
                        $("#result_data").html(data);

                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }

                });
                return false;
            }
        } else {
            return false;
        }
    });

</script>


