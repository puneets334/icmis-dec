
<div class="tab-pane active" id="Case_Search">
    <?php
    $casesearchattribute = array('class' => 'form-horizontal case_search_form','name' => 'case_search_form', 'id' => 'case_search_form', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $casesearchattribute);
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
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
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
                                <label for="Case title Search" class="col-sm-5 col-form-label">Case title Search</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control case_title_search" id="case_title_search" name="case_title_search" placeholder="Case title Search" value="<?php if(!empty($formdata['case_title_search'])){ echo $formdata['case_title_search']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Dairy No." class="col-sm-5 col-form-label">Party Search</label>
                                <div class="col-sm-7">

                                    <select name="ddl_party_type" id="ddl_party_type" class="form-control" style="width: 100%;">


                                        <option value="">All</option>
                                        <option value="P" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'P'){  echo 'selected'; }?>>Petitioner</option>
                                        <option value="R" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'R'){  echo 'selected'; }?>>Respondent</option>
                                        <option value="I" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'I'){  echo 'selected'; }?>>Impleading</option>
                                        <option value="N" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'N'){  echo 'selected'; }?>>Intervenor</option>

                                    </select>

                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row ">

                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input saod" type="radio" name="search_against_order_details" id="search_against_order_details" onclick="is_if_saod_c(this.value)" value="caveat" <?php if(!empty($formdata['search_against_order_details'])){ if($formdata['search_against_order_details'] == 'caveat'){echo 'checked';}}?>>
                                <label class="form-check-label" for="search_against_order_details">Caveat Search Against Order Details</label>
                            </div>


                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input saod" type="radio" name="search_against_order_details" id="search_against_order_details" onclick="is_if_saod_d(this.value)" value="diary"  <?php if(!empty($formdata['search_against_order_details'])){ if($formdata['search_against_order_details'] == 'diary'){echo 'checked';}}?>>
                                <label for="Dairy No." class="form-check-label">Diary Search Against Order Details</label>
                            </div>


                        </div>

                    </div>





                    <!-------------Div 1-Caveat or dairy  Search Against Order Details --------------->
                    <div id="caveat_or_diary"  style="display: none">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group ">
                                <label  class="col-sm-7 col-form-label"> Court </label>
                                <div class="col-sm-12">
                                    <select name="ddl_court" id="ddl_court" class="form-control">
                                        <option value="">Select </option>
                                        <?php
                                        foreach ($court_type_list as $row) {
                                            echo'<option value="'.$row['id'].'">'.$row['court_name'].'</option>';

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label  class="col-sm-5 col-form-label">State </label>
                                <div class="col-sm-12">
                                    <select name="ddl_st_agncy" id="ddl_st_agncy" class="form-control">
                                        <option value="">Select State</option>
                                        <?php
                                        foreach ($state as $row) {
                                            if (isset($row['cmis_state_id'])){
                                                echo'<option value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['state_name'])) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label  class="col-sm-10 col-form-label">Court Bench </label>
                                <div class="col-sm-12">
                                    <select name="ddl_bench" id="ddl_bench" class="form-control">
                                        <option value="" title="Select">Select Court Bench</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group ">
                                <label class="col-sm-8 col-form-label">Case Type </label>
                                <div class="col-sm-12">
                                    <select name="ddl_nature" id="ddl_nature" class="form-control">
                                        <option value="">Select case type</option>
                                        <?php
                                        foreach ($casetype as $row) {
                                            echo'<option value="' . sanitize($row['casecode']) . '">' . sanitize($row['casename']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group ">
                                <label for="Case title Search" class="col-sm-8 col-form-label">Case No</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="txt_ref_caseno" name="txt_ref_caseno" placeholder="Case No" value="<?php if(!empty($formdata['txt_ref_caseno'])){ echo $formdata['txt_ref_caseno']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="Year" class="col-sm-6 col-form-label">Year</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="case_year" id="case_year"style="width: 100%;">
                                        <option value="">Year</option>    <?php
                                        $yr=date('Y');    for ($year_val = $yr; $year_val >=1947; $year_val--) {    ?>
                                            <option value="<?php echo $year_val; ?>" <?php if(!empty($formdata["diary_year"]) && $formdata["diary_year"]==$year_val) { ?> selected="selected" <?php } ?>><?php echo $year_val; ?></option>        <?php  }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" name="case_search" id="case_search"  class="case_search btn btn-primary" value="Search">
                            <input type="reset" name="reset_search" id="reset_search"  class="reset_search btn btn-primary" value="Reset">

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>

    </div>
      <?= form_close();?>
  </div>
 <!-- /.Case_Search -->
<center><span id="loader"></span> </center>
   <div id="case_search_result_data"></div>
  </div>
 </div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#case_search_form').on('submit', function () {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date.length != 0) {
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
        }

        var ele = document.getElementsByName('search_against_order_details');
        for(var i = 0; i < ele.length; i++) {
            if(ele[i].checked)
                var radio_slct_valid=ele[i].value;
        }

        if($('#case_search').is(':empty')){
            if((radio_slct_valid=='caveat') || (radio_slct_valid=='diary')){
                var case_year= $('#case_year').val();
                var ddl_court= $('#ddl_court').val();
                var ddl_st_agncy= $('#ddl_st_agncy').val();


                if(ddl_court==''){
                    alert("Please Select Court.");
                    $("#ddl_court").css('border-color', 'red');
                    return false;

                }else if(ddl_st_agncy=='' ){
                    alert("Please Select State.");
                    $("#ddl_st_agncy").css('border-color', 'red');
                    return false;

                }
                else if(case_year=='' ){
                    alert("Please Select Year.");
                    $("#case_year").css('border-color', 'red');
                    return false;

                }
            }

        }


        if ($('#case_search_form').valid()) {
            var validateFlag = true;
            var form_data = $(this).serialize();
            if(validateFlag){ //alert('readt post form');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $("#loader").html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('Reports/Filing/Report/case_search'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        $('.case_search').val('Please wait...');
                        $('.case_search').prop('disabled', true);
                    },
                    success: function (data) {
                        $("#loader").html('');
                        $('.case_search').prop('disabled', false);
                        $('.case_search ').val('Search');
                        $("#case_search_result_data").html(data);

                        updateCSRFToken();
                    },
                    error: function () {
                       // alert(message);
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

<script src="<?php echo base_url('filing/diary_add_filing.js'); ?>"></script>

<script type="text/javascript">

    function is_if_saod_d(diary) {
        if($('input:radio[name="search_against_order_details"]').is(':checked'))

        {
            $('#caveat_or_diary').css('display','inline');
        }
        else
        {
            $('#caveat_or_diary').css('display','none');
        }
        $('#case_title_search').val('');

    }
    function is_if_saod_c(caveat) {
        if($('input:radio[name="search_against_order_details"]').is(':checked'))
        {
            $('#caveat_or_diary').css('display','inline');
        }
        else
        {
            $('#caveat_or_diary').css('display','none');
        }
        $('#case_title_search').val('');

    }
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#case_title_search', function() {
            $(".saod").prop('checked', false);

            if($('input:radio[name="search_against_order_details"]').is(':checked'))
            {
                $('#caveat_or_diary').css('display','none');
                $(this).attr('checked', false);
                $('#caveat_or_diary').val('');
                $('#sclc_year').val('');
            }
            else
            {
                $('#caveat_or_diary').css('display','none');
            }


        });


    });
</script>



