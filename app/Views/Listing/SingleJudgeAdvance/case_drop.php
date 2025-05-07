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
                                <h3 class="card-title">SINGLE JUDGE ADVANCE CASE DROP MODULE</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    csrf_field();
                                    ?>

                                    <?php echo component_html(); ?>


                                    <center> <button type="submit" class="btn btn-primary" id="submit">GET DETAILS</button></center>
                                    <?php form_close(); ?>

                                    <div id="report_result"></div>
                                    <div id="res"></div>
                                    <div id="di_rslt" style="text-align: center;"></div>

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
    $(document).ready(function() {
        $("#search_type_d").click(function() {



            $("#report_result").val("");
            $("#case_type").val("");
            $("#case_number").val("");
            $("#case_year").val("");
            // location.reload();

        });

        $("#search_type_c").click(function() {
            $("#report_result").val("");

            $("#diary_number").val("");
            $("#diary_year").val("");
            //location.reload();


        });
        $('#component_search').on('submit', function() {
            
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year = $('#diary_year :selected').val();
            var case_type = $('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_year = $('#case_year :selected').val();
            if (search_type == 'D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                } else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            } else if (search_type == 'C') {
                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                } else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                } else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#component_search').valid()) {
                $('#di_rslt').html("");  
                var validateFlag = true;
                var form_data = $(this).serialize();
                if (validateFlag) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $(".form-response").html("");
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Listing/SingleJudgeAdvance/field_case_drop/'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            updateCSRFToken();
                            var resArr1 = data;
                            var resArr = data.split('@@@');
                            if (resArr1) {
                                updateCSRFToken();
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#report_result').html(resArr1);
                            } else {
                                $('#div_result').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    });

    $(document).on("click", "#drop_btn_note", function() {


        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var next_dt = $("#next_dt1").val();
        var brd_slno = $("#brd_slno").val();
        var partno = $("#partno1").val();
        var dno = $("#drop_diary1").val();
        var roster_id = $("#roster_id").val();
        var drop_rmk = $("#drop_rmk").val();
        var mainhead = 'M';
        var ldates = $("#ldates").val();
        var from_dt = $("#from_dt1").val();
        var to_dt = $("#to_dt1").val();

        if (drop_rmk == "") {
            alert("Drop Note Required.");
            return false;
        }

        $.ajax({
            url: "<?php echo base_url('Listing/SingleJudgeAdvance/dropNoteNow/'); ?>",
            type: 'POST',
            dataType: 'json', 
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                next_dt: next_dt,
                brd_slno: brd_slno,
                dno: dno,
                roster_id: roster_id,
                drop_rmk: drop_rmk,
                mainhead: mainhead,
                ldates: ldates,
                partno: partno,
                from_dt: from_dt,
                to_dt: to_dt
            },
            beforeSend: function(xhr) {
                $('#di_rslt').html('<table widht="100%"style="border-collapse: collapse; text-align: center;"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(response) {
                updateCSRFToken();

                console.log("AJAX Response:", response); 

                $('#show_fil').html("");

                if (response.message) {
                    
                    $('#di_rslt').html('<div class="alert alert-success">' + response.message + '</div>');
                } else if (response.error) {
                   
                    $('#di_rslt').html('<div class="alert alert-danger">' + response.error + '</div>');
                } else {
                    $('#di_rslt').html('<div class="alert alert-warning">Unexpected response format.</div>');
                }
            },
            error: function(xhr, status, error) {
                updateCSRFToken();
                console.error("AJAX Error:", xhr.responseText);
              
            }
        });
    });


    $(document).on("click", "#drop_btn", function() {



        $('#di_rslt').html("");
        var dno = $("#drop_diary").val();
        var next_dt = $("#next_dt").val();
        var from_dt = $("#from_dt").val();
        var to_dt = $("#to_dt").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?php echo base_url('Listing/SingleJudgeAdvance/caseDropNow/'); ?>",
            cache: false,
            async: true,
            data: {
                dno: dno,
                next_dt: next_dt,
                from_dt: from_dt,
                to_dt: to_dt,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
               
                $('#di_rslt').html('<table widht="100%"style="border-collapse: collapse; text-align: center;"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(response) {
                updateCSRFToken();
                $('#show_fil').html("");
                if (response.message) {
                    
                    $('#di_rslt').html('<div class="alert alert-success">' + response.message + '</div>');
                } else if (response.error) {
                   
                    $('#di_rslt').html('<div class="alert alert-danger">' + response.error + '</div>');
                } else {
                    //$('#di_rslt').html('<div class="alert alert-warning">Unexpected response format.</div>');
                }
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>