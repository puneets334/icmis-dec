<?php
$attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
echo form_open(base_url('#'), $attribute);
?>
<?php echo component_html(); ?>

<input type="hidden" class="form-control" id="redirect_url" name="redirect_url" value="<?= $current_page_url; ?>" placeholder="Enter redirect url <?= $current_page_url; ?>">
<center> <button type="submit" class="btn btn-primary" id="submit">Submit</button></center>
<?php form_close(); ?>
<br /><br />
<center><span id="loader"></span> </center>
<span class="alert alert-error" style="display: none; color: red;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <span class="form-response"> </span>
</span>
<div id="record" class="record"></div>



<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(document).ready(function() {
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
                        url: "<?php echo base_url('Filing/Diary/search'); ?>",
                        data: form_data,
                        beforeSend: function() {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function(data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                //window.location.reload();
                                // window.location.href =resArr[1];
                                get_ia_entry_date_correction_list(resArr[1]);
                            } else if (resArr[0] == 3) {
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

    function get_ia_entry_date_correction_list(url) {

        $('#record').html('');

        var radio = $("input[type='radio'][name='search_type']:checked").val();

        var ia_search = "<?= base_url('ARDRBM/IA/get_ia_entry_date_correction_list') ?>";
        $('#record').html('');
        $.ajax({
            type: "GET",
            url: ia_search,
            data: {
                radio: radio,
                option: 1
            },
            beforeSend: function() {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function(data) {
                $('#loader').html('');
                updateCSRFToken();
                $("#record").html(data);
            },
            error: function() {
                updateCSRFToken();
                //alert('Something went wrong! please contact computer cell');
            }
        });

    }

    function old_delete_ld(docd_id) {
        var r = confirm("Are you Sure, Record to be Delete.");
        if (r == true) {
            $('#loader').html('');
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('ARDRBM/IA/delete_ia_entry_date_correction'); ?>",
                data: {
                    type: 'D',
                    docd_id: docd_id
                },
                beforeSend: function() {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(data) {
                    var resArr = data.split('@@@');
                    get_ia_entry_date_correction_list(resArr[1]);
                    alert(resArr[1]);
                    if (resArr[0] == 1) {
                        ('#loader').html(resArr[1]);

                    } else if (resArr[0] == 3) {
                        ('#loader').html(resArr[1]);
                    }
                }
            });
        }
    }
</script>