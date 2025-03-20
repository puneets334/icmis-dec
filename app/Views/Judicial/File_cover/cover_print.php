<?= view('header') ?>
<?php helper('form'); ?>
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
                                <h3 class="card-title">Cover Print</h3>
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
                                    echo form_open(base_url($formAction), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>
                                    <center> <button type="submit" class="btn btn-primary" id="submit">Get Details</button></center>
                                    <?php form_close(); ?>

                                    
                                </div>
                                <div class="card-footer">
                                    <div id="report_result"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
    <script>
        function updateCSRFToken() {
            $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }
        $(document).ready(function() {
            $('#component_search').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
     
                var regNum = new RegExp('^[0-9]+$');
                var validationError = true; // Flag to track validation state
                $(".invalid-feedback").remove(); // Remove any previous error messages
                $(".is-invalid").removeClass('is-invalid border-danger'); // Reset error styles

                var search_type = $("input[name='search_type']:checked").val();
                if (!search_type) {
                    $("input[name='search_type']").closest('div')
                        .append("<div class='invalid-feedback d-block'>Please select case type</div>");
                    validationError = false;
                }

                var diary_number = $("#diary_number").val();
                var diary_year = $('#diary_year').val();

                var case_type = $('#case_type').val();
                var case_number = $("#case_number").val();
                var case_year = $('#case_year').val();

                if (search_type === 'D') {
                    if (!diary_number) {
                        $("#diary_number").addClass('is-invalid')
                            .after("<div class='invalid-feedback'>Please enter diary number</div>");
                        validationError = false;
                    }else if(!regNum.test(diary_number)){
                          $("#diary_number").addClass('is-invalid')
                            .after("<div class='invalid-feedback'>Please Enter Diary No in Numeric</div>");
                        validationError = false;
                    }
                    if (!diary_year) {
                        $("#diary_year").addClass('is-invalid border-danger')
                            .after("<div class='invalid-feedback'>Please select diary year</div>");
                        validationError = false;
                    }
                } else if (search_type === 'C') {
                    if (!case_type) {
                        $("#case_type").addClass('is-invalid border-danger')
                            .after("<div class='invalid-feedback'>Please select case type</div>");
                        validationError = false;
                    }
                    if (!case_number) {
                        $("#case_number").addClass('is-invalid')
                            .after("<div class='invalid-feedback'>Please enter case number</div>");
                        validationError = false;
                    }else if(!regNum.test(case_number)){
                          $("#case_number").addClass('is-invalid')
                            .after("<div class='invalid-feedback'>Please Enter case No in Numeric</div>");
                        validationError = false;
                    }
                    if (!case_year) {
                        $("#case_year").addClass('is-invalid border-danger')
                            .after("<div class='invalid-feedback'>Please select case year</div>");
                        validationError = false;
                    }
                }

                if (!validationError) {
                    return false; // Stop submission if validation fails
                }

                // Dynamically remove error messages and styles when input changes
                $('#component_search input, #component_search select').on('input change', function() {
                    $(this).removeClass('is-invalid border-danger');
                    $(this).next('.invalid-feedback').remove();
                });

                if ($('#component_search').valid()) {
                    var validateFlag = true;
                    var form_data = $(this).serialize();

                    if (validateFlag) {
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $('.alert-error').hide();
                        $(".form-response").html("");
                        $("#loader").html('');
                        $('#report_result').html('');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('Judicial/File_cover/CoverPrint/handlePostRequest'); ?>",
                            data: form_data,
                            beforeSend: function() {
                                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                            },
                            success: function(data) {
                                updateCSRFToken();
                                
                                if(data.error != undefined && data.success == 0) {
                                    alert(data.error);
                                    return false;
                                }

                                var resArr1 = data;
                                if (resArr1) {
                                    $('.alert-error').hide();
                                    $(".form-response").html("");
                                    $('#report_result').html(resArr1);
                                } else {
                                    $('#div_result').html('');
                                    $('.alert-error').show();
                                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; ERROR, Please Contact Server Room.</p>");
                                }
                            },
                            error: function() {
                                updateCSRFToken();
                                alert('Something went wrong! Please contact the computer cell.');
                            }
                        });
                        return false;
                    }
                } else {
                    return false;
                }
            });


        });
    </script>