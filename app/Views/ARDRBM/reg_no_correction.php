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
                                <h3 class="card-title">Reg No display Update</h3>
                            </div>
                            <div class="col-sm-2">

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
                                    <?php echo component_html(); ?>

                                    <center> <button type="button" class="btn btn-primary" id="search" onclick="search_case()">Submit</button></center>
                                    <?php form_close(); ?>
                                    <br /><br />
                                    <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                    <!-- <div id="record" class="record"></div> -->
                                    <div id="record" class="record" style="display:none; width: 100%; margin-left:15px;"></div>
                                    <div id="message" class="message" style="display:none; width: 100%; margin-left:15px;"></div>


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
    function enable_disable() {
        var temp_dyr = "<?= date('Y') ?>";
        var temp_cyr = "<?= date('Y') ?>";
        var radio = $("input[type='radio'][name='rad']:checked").val();
        if (radio == 2) {
            $('#record').hide();
            $('#case_type').val('');
            $('#case_number').val('');
            $('#case_year').val(temp_cyr);
            $('#diary_number').val('');
            $('#diary_year').val(temp_dyr);
            $(".e1").prop("disabled", false);
            $("#case_number").prop("disabled", false);
            $("#case_year").prop("disabled", false);
            $("#diary_number").prop("disabled", true);
            $("#diary_year").prop("disabled", true);
            $('#diary_year').css("background-color", "lightgrey");
            $('#case_type').css("background-color", "white");
            $('#case_year').css("background-color", "white");

        } else {
            $('#record').hide();
            $('#diary_number').val('');
            $('#diary_year').val(temp_dyr);
            $('#case_type').val('');
            $('#case_number').val('');
            $('#case_year').val(temp_cyr);
            $(".e1").prop("disabled", true);
            $("#case_number").prop("disabled", true);
            $("#case_year").prop("disabled", true);
            $("#diary_number").prop("disabled", false);
            $("#diary_year").prop("disabled", false);
            $('#diary_year').css("background-color", "white");
            $('#case_type').css("background-color", "lightgrey");
            $('#case_year').css("background-color", "lightgrey");

        }

    }

    async function search_case() {
        
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('.message').hide();
        $('#record').hide();

        var radio = $("input[type='radio'][name='search_type']:checked").val();
        var dno = $('#diary_number').val();
        var dyr = $('#diary_year').val();
        var ctype = $('#case_type').val();
        var cno = $('#case_number').val();
        var cyr = $('#case_year').val();
        
       
        if (radio == 'D') {
            if (!dno) {
                alert('Entery diary no to proceed!!');
                $('#dno').focus();
                return false;
            }
        } else if (radio == 'C') {
            if (!ctype) {
                alert('Select Case Type to proceed!!');
                $('#case_type').focus();
                return false;
            } else if (!cno) {
                alert('Enter Case No !!!');
                $('#case_number').focus();
                return false;
            }
        }

        if ((radio == 'D' && dno) || (radio == 'C' && ctype && cno)) {

            var ia_search = "<?php echo base_url('ARDRBM/IA/reg_no_correction_process'); ?>";
            
            $.ajax({
                type: "POST",
                url: ia_search,
                data: {
                    dno: dno,
                    radio: radio,
                    dyr: dyr,
                    ctype: ctype,
                    cno: cno,
                    cyr: cyr,
                    option: 1,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },

                beforeSend: function() {
                    //updateCSRFToken();
                    $('#image').show();
                    $('#record').hide();
                },

                complete: function() {
                    //updateCSRFToken();
                    $('#image').hide();
                },

                success: function(data) {
                    //updateCSRFToken();
                    $('.record').html(data);
                    $('#record').show();
                },

                error: function() {
                    alert("Error");
                }
            });

        }
    }
</script>