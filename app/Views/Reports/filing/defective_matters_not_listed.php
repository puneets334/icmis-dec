<!-- TO DO the view part -->
<div class="active tab-pane" id="Fil_Trap">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body">
                    <!-- <div class="row">
                        <input class="form-check-input Defective_Matters" type="radio" name="reg_or_def" value="rd_dm" checked>
                        <label class="form-check-label">Defective Matters Not Listed</label>
                        <input class="form-check-input tag_matt" type="radio" name="reg_or_def" value="tag_matt" >
                        <label class="form-check-label">Tagged Matters</label>
                    </div> -->
                    <div class="row">



                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input Defective_Matters" checked type="radio" name="reg_or_def" value="rd_dm">
                                <label class="form-check-label">Defective Matters Not Listed</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-check">
                                <input class="form-check-input tag_matt" type="radio" name="reg_or_def" value="tag_matt">
                                <label class="form-check-label">Tagged Matters</label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <?php
                    $attribute = array('class' => 'form-horizontal defective_matter_not_list_search_form', 'name' => 'defective_matter_not_list_search_form', 'id' => 'defective_matter_not_list_search_form', 'autocomplete' => 'off');
                    echo form_open('#', $attribute);
                    ?>
                    <div class="row tag_matt_no_need">
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">Enter No. of days:</label>
                                <div class="col-sm-7">
                                    <input type="number" required="required" class="form-control" id="days" name="days" value="<?php echo !empty($formdata['days']) ? $formdata['days'] : '' ?>" placeholder="No. of days">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row">
                                <label for="section" for="section" class="col-sm-5 col-form-label">Select
                                    Section:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="section" name="section[]" multiple required="required">
                                        <option value="0" disabled>Select multiple</option>
                                        <?php
                                        if (!empty($Sections)) {
                                            foreach ($Sections as $Section)
                                                echo '<option value="' . $Section->section_name . '" ' . (isset($_POST['section']) && $param[1] == $Section['section_name'] ? 'selected="selected"' : '') . '>' . $Section->section_name . '</option>';
                                        } ?>
                                    </select>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" name="defective_matter_not_list_search" id="defective_matter_not_list_search" class="defective_matter_not_list_search btn btn-primary" value="Search">
                            <input type="reset" name="reset_search" id="reset_search" class="reset_search btn btn-primary" value="Reset">
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

</div>
<div id="defective_matters_not_list_search_data"></div>
</div>

<script>
    $('.Defective_Matters').on('click', function() {
        $("#defective_matters_not_list_search_data").html('');
        $('.tag_matt_no_need').show();
    });

    $('.defective_matter_not_list_search').on('click', function() {
        var form_data = $('#defective_matter_not_list_search_form').serialize();
        if (form_data) {
            $('.alert-error').hide();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Reports/Filing/Report/get_defective_matters_not_listed'); ?>",
                data: form_data,
                beforeSend: function() {
                    $('#defective_matter_not_list_search').val('Please wait...');
                    $('#defective_matter_not_list_search').prop('disabled', true);
                },
                success: function(data) {
                    updateCSRFToken();
                    $('#defective_matter_not_list_search').prop('disabled', false);
                    $('#defective_matter_not_list_search').val('Submit');
                    $("#defective_matters_not_list_search_data").html(data);
                },
                error: function() {
                    updateCSRFToken();
                    $('#defective_matter_not_list_search').prop('disabled', false);
                    $('#defective_matter_not_list_search').val('Submit');
                    alert('Please Contact to Comouter Cell');
                }

            });
            return false;
        }
    });
</script>
<script>
    $('.tag_matt').on('click', function() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $('.alert-error').hide();
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('Reports/Filing/Filing_Reports/tagged_matter_report'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function() {
                $('#defective_matter_not_list_search').val('Please wait...');
                $('#defective_matter_not_list_search').prop('disabled', true);
            },
            success: function(data) {
                $('.tag_matt_no_need').hide();
                $('#defective_matter_not_list_search').prop('disabled', false);
                $('#defective_matter_not_list_search').val('Submit');
                $("#defective_matters_not_list_search_data").html(data);
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
                $('#defective_matter_not_list_search').prop('disabled', false);
                $('#defective_matter_not_list_search').val('Submit');
                alert('Please Contact to Comouter Cell');
            }

        });

    });
</script>