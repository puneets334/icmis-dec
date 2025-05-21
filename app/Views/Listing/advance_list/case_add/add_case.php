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
                                <h3 class="card-title">Add Case in Advance List</h3>
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

                                    ?>
                                    <?= csrf_field() ?>
                                    <?php echo component_html(); ?>


                                    <center> <button type="submit" class="btn btn-primary" id="submit">Get Details</button></center>
                                    <?php form_close(); ?>

                                    <div id="report_result"></div>
                                    <div id="report_result1"></div>



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
 
        $('#component_search').on('submit', function() {

            var search_type = $("input[name='search_type']:checked").val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


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
            } else if (search_type == 'C')
            {
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

            if ($('#component_search').valid())
            {
                var validateFlag = true;
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
               

                if (validateFlag)
                {

                    $('.alert-error').hide();
                    $(".form-response").html("");
                    $("#loader").html('');

                    getCaseDetails(form_data);

                
                    return false;
                }
            }
            else
            {
                return false;
            }
            function getCaseDetails(form_data)
            {
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Listing/AddCaseAdvance/add_case_info/'); ?>",

                        data: {
                            search_type: search_type,
                            diary_number: diary_number,
                            diary_year: diary_year,
                            case_type: case_type,
                            case_number: case_number,
                            case_year: case_year,
                            CSRF_TOKEN: CSRF_TOKEN_VALUE
                        },
                        beforeSend: function() {

                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            updateCSRFToken();
                            var resArr1 = data;
                            var resArr = data.split('@@@');
                            if (resArr1) {
                                $('.alert-error').hide();
                                $(".form-response").html("");
                                $('#report_result').html(resArr1);
                            } else {
                                $('#div_result').html('');
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function(xhr) {
                            updateCSRFToken();
                            alert("Error: " + xhr.status + " " + xhr.statusText);
                        }
                    });
            }
        });
 

    $(document).on("click", "input[name=savebutton]", function()
    {

        if (!$('#advance_list_date').length || $('#advance_list_date :selected').val() == '') {
            alert("Select Advance List Date!");
            return false;
        }

        var advance_list_date = $('#advance_list_date :selected').val();
        checkIfListIsPrinted(advance_list_date);





    });

    function checkIfListIsPrinted(advance_list_date)
    {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Listing/AddCaseAdvance/checkIfPublished/'); ?>",
                data: {
                    date: $('#advance_list_date :selected').val(),CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(response) {


                if (response.msg == 0)
                {
                   
                    var dno = document.getElementById('fil_hd').value;
                    var listing_date = $('#advance_list_date :selected').val();
                    saveDataAdvanceList(dno,listing_date);
                } else {
                   // updateCSRFToken();
                    alert("This Part is Printed");
                }
            })
            .fail(function(){
             alert("Error, Please Contact Server-Room");
            });
    }
    async function saveDataAdvanceList(dno,listing_date)
    {
         await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('Listing/AddCaseAdvance/save_case_to_advance_list'); ?>",
            data: {
                dno: dno,
                listing_date: listing_date,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function()
            {
                $('#report_result').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
            },
            success: function(response)
            {
                updateCSRFToken();
                document.getElementById('report_result').innerHTML = response;
            },
            error: function() {
                updateCSRFToken();
                alert("Error, Please Contact Server-Room");
            }
        });
    }
</script>