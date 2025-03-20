<div class="active tab-pane" id="Fil_Trap">
    <?php
    $attribute = array('class' => 'form-horizontal fil_trap_search_form','name' => 'fil_trap_search_form', 'id' => 'fil_trap_search_form', 'autocomplete' => 'off');
    echo form_open('#', $attribute);
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary">
                <div class="card-body">
                    <div >
                        <div id="dateSelection" class="col-sm-12 row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="From" class="col-form-label"><b>Bitween</b> From Date: </label>
                                    <div class="col-sm-6">
                                        <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" value="<?php echo !empty($formdata['from_date']) ? $formdata['from_date'] : '' ?>" placeholder="From Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="To" class=" col-form-label ">To Date:</label>
                                    <div class="col-sm-6">
                                        <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="to_date" name="to_date"  value="<?php if(!empty($formdata['to_date'])) echo $formdata['to_date'] ?>" placeholder="TO Date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="diary_search" class="col-sm-12 row">
                            <div class="form-group col-sm-4">
                                    <label for="Dairy No." class=" col-form-label">Dairy No.</label>
                                    <div>
                                        <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No"  value="<?php echo !empty($formdata['diary_no']) ? $formdata['diary_no'] : '' ?>">
                                    </div>
                            </div>
                                <div class="form-group col-sm-4">
                                    <label for="Year" class=" col-form-label">Year</label>
                                    <div >
                                        <select class="form-control select2" name="diary_year" id="diary_year" >
                                            <?php echo !empty($formdata['diary_year']) ? '<option value='.$formdata['diary_year'].'>'.$formdata['diary_year'].'</option>': '' ?>
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
                    </div>
                    <div class="row ">
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" checked type="radio" name="incompleteandcompletematter" value="cv" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'cv') {echo 'checked';} }?>>
                                <label class="form-check-label mt-3">Complete View</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="incompleteandcompletematter" value="cm" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'cm') {echo 'checked';} }?>>
                                <label class="form-check-label mt-3">Completed Matter</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="incompleteandcompletematter" value="im" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'im') {echo 'checked';} }?>>
                                <label class="form-check-label mt-3">Incomplete Matter</label>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                           <input type="button" name="fil_trap_search" id="fil_trap_search"  class="fil_trap_search btn btn-primary" value="Search">
                           <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--/.col (right) -->
    <?= form_close();?>
</div>
<!-- /.Fil_Trap -->
 <div id="fil_trap_result_data"></div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $("#dateSelection").hide();
    $("#diary_search").hide();
    var radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
    radioChangeEvents(radio_selected);
    var validationError = false;
    $('#fil_trap_search').on('click', function () {
        radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
        var diary_no=$("#diary_no").val();
        var diary_year=$("#diary_year").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0 && diary_no.length==0) {
            var date1 = new Date(from_date.split('-')[0], from_date.split('-')[1] - 1, from_date.split('-')[2]);
            var date2 = new Date(to_date.split('-')[0], to_date.split('-')[1] - 1, to_date.split('-')[2]);
            if (date1 > date2 &&  date2 < date1  ) {
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
                }
                else if (to_date.length == 0) {
                    alert("Please select to date.");
                    $("#to_date").focus();
                    validationError = true;
                    return false;
                }
            }
        }
        else if(radio_selected=='cv')
            {
                if(diary_no.length == 0 && diary_year.length==0) {
                    alert("Please enter Diary No. and Diary date.");
                    $("#diary_no").focus();
                    validationError = true;
                    return false;
                }
                else
                {
                    validationError = false;
                }
            }
        else if(radio_selected=='im')
        {
            validationError = false;
        }
        else{
            alert("At least one input required for the selected Report type");
            $("#from_date").focus();
            validationError = true;
            return false;
        }
        if(!(validationError)) {
            var form_data = $("#fil_trap_search_form").serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/fil_trap_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('.fil_trap_search').val('Please wait...');
                        $('.fil_trap_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $('.fil_trap_search').prop('disabled', false);
                        $('.fil_trap_search').val('Search');
                        $("#fil_trap_result_data").html(data);
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }

                });
                return false;
        } else {
            return false;
        }
    });
    $(document).ready(function() {
        $('input:radio').change(function() {
            var radio_selected=$('input[name="incompleteandcompletematter"]:checked').val();
            radioChangeEvents(radio_selected);
        });
    });

    function radioChangeEvents(radio_selected=null)
    {
        if(radio_selected=='cv')
        {
            $("#dateSelection").hide();
            $("#diary_search").show();
        }
        else if(radio_selected=='cm')
        {
            $("#dateSelection").show();
            $("#diary_search").hide();
        }
        else
        {
            $("#diary_search,#dateSelection input,select").each(function() {
                this.value = "";
            })

            $("#dateSelection").hide();
            $("#diary_search").hide();
        }
    }


</script>

