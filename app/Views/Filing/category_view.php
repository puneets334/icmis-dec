    <?php if ($category != 'category') { ?>
        <?=view('header'); ?>
         
    <?php  } ?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <?php if ($category != 'category') { ?>

                            <div class="card-header heading">

                                <div class="row">
                                    <div class="col-sm-10">
                                        <h3 class="card-title">Filing</h3>
                                    </div>
                                     <?=view('Filing/filing_filter_buttons'); ?>
                                </div>
                            </div>
                            <?= view('Filing/filing_breadcrumb'); ?>
                        <?php  } ?>

                        <!-- /.card-header -->
                        <?php
                        $case_group = session()->get('filing_details')['case_grp'];
                        $case_status = session()->get('filing_details')['c_status'];
                        $submaster_id = "";
                        //echo"jkjkkj";print_r($_SESSION);

                        if (!empty($mul_category)) {
                            $submaster_id = $mul_category[0]['submaster_id'];
                        }

                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <h4 class="basic_heading"> Category Details </h4>
                                        <ul class="nav nav-pills inner-comn-tabs">
                                            <li class="nav-item"><a class="nav-link active" href="#manage_category" data-toggle="tab">Category</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#manage_acts" data-toggle="tab">Acts and Section</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#basic_details" data-toggle="tab">Other Details</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#keywords" data-toggle="tab">Keywords</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                        <?php if (session()->getFlashdata('error')) { ?>
                                            <div class="alert alert-danger">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('error') ?></strong>
                                            </div>

                                        <?php } ?>
                                        <?php if (session()->getFlashdata('success_msg')) : ?>
                                            <div class="alert alert-success alert-dismissible">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <!-- <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                            Success alert preview. This alert is dismissable.
                                            </div> -->
                                        <div class="tab-content">

                                            <div class="tab-content">

                                                <div class="active tab-pane" id="manage_category">
                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'name' => 'diary_generation_form', 'id' => 'diary_generation_form', 'autocomplete' => 'off');
                                                    echo form_open(base_url('Filing/Category/updateCategory/'), $attribute);
                                                    ?>
                                                    <h4 class="basic_heading">Manage Category</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row cus-mx-none">
                                                                <label class="col-sm-12 col-form-label pl-0">Main Category</label>
                                                                <div class="col-sm-12 pl-0">
                                                                    <?php
                                                                    $allowedSectionsArray = [19];
                                                                    $l_user_section = $_SESSION['login']['section'];
                                                                    $disabled_update_category = "";
                                                                    if (!in_array($l_user_section, $allowedSectionsArray)) {
                                                                        $disabled_update_category = "disabled";
                                                                    }

                                                                    ?>

                                                                    <select name="main_category" id="main_category" class="form-control select2" required>
                                                                        <option value="" title="Select">Select Main Category</option>
                                                                        <?php
                                                                        $selectedmain_for = "";
                                                                        foreach ($main_categories as $row) {
                                                                            if (isset($row['subcode1'])) {
                                                                                if (!empty($mul_category)) {
                                                                                    $selectedmain_for = $row['subcode1'] == $mul_category[0]['subcode1'] ? 'selected' : '';
                                                                                }
                                                                                echo '<option value="' . sanitize(($row['subcode1'])) . '" ' . $selectedmain_for . '>'.$row['category_sc_old'] .' - ' . sanitize(strtoupper($row['sub_name1'])) . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row cus-mx-none">
                                                                <label class="col-sm-12 col-form-label pl-0">Sub Category</label>
                                                                <div class="col-sm-12 pl-0">
                                                                    <select name="sub_category" id="sub_category" class="custom-select rounded-0 select2">
                                                                        <option value="">Select Sub Category</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($case_status == 'P') : ?>
                                                        <center>
                                                            <div class="col-md-2 mt-3">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="saveStep1_">Save</button>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    <?php endif; ?>
                                                    <br><br>
                                                    <?= form_close(); ?>
                                                </div>

                                                <!-- /.diary_generation_tab_panel -->

                                                <div class="tab-pane" id="manage_acts">
                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'name' => 'acts_section_form', 'id' => 'acts_section_form', 'autocomplete' => 'off');
                                                    echo form_open(base_url('Filing/Category/updateActs/'), $attribute);
                                                    ?>
                                                    <h4 class="basic_heading"> Manage Act and Section </h4>
                                                    <div class="row">
                                                        <div class="col-md-8"> <label>Act :</label>
                                                        </div>
                                                        <div class="col-md-4"> <label>Section :</label>
                                                        </div>
                                                    </div>


                                                    <div class="multi-field-wrapper">
                                                        <div class="multi-fields">

                                                            <div class="multi-field">
                                                                <button type="button" class="remove_out_row  d-none btn btn-danger btn-outline-danger" value="1">out-Removed</button>
                                                                <span class="add-field btn btn-success float-sm-right"><i class='fas fa-plus-circle'></i></span>
                                                                <button type="button" class="remove_in_row remove-field d-none btn btn-danger float-sm-right" value="1"><i class='fas fa-minus-circle'></i></button>
                                                                <div class="row" style="padding-left: 20px;">
                                                                        <div class="row">
                                                                            <div class="col-md-7">
                                                                                <select class="form-control" name="act[]" id="act[]">
                                                                                    <option value="" title="Select">Select Act</option>
                                                                                    <?php
                                                                                    foreach ($acts as $act) {
                                                                                        if (isset($act['id'])) {
                                                                                            echo '<option value="' . sanitize(($act['id'])) . '">' . $act['id'] . ' - ' . sanitize(strtoupper($act['act_name'])) . '</option>';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-sm-1">
                                                                                <input type="number" class="form-control" id="section_1[]" name="section_1[]" maxlength="3" value="" onkeypress="return onlynumbers(event);">
                                                                            </div>
                                                                            <div class="col-sm-1">
                                                                                <input type="text" class="form-control" id="section_2[]" name="section_2[]" maxlength="3" onkeypress="return slashnot(event);">
                                                                            </div>
                                                                            <div class="col-sm-1">
                                                                                <input type="text" class="form-control" id="section_3[]" name="section_3[]" maxlength="3" onkeypress="return slashnot(event);">
                                                                            </div>
                                                                            <div class="col-sm-1">
                                                                                <input type="text" class="form-control" id="section_4[]" name="section_4[]" maxlength="3" onkeypress="return slashnot(event);">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <br>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- start Category Update-->

                                                    <div class="row" style="padding-left: 20px;">
                                                        <?php $i = 1;
                                                        if (!empty($acts_section)) {
                                                            $total_acts = count($acts_section);
                                                            //echo '<pre>';print_r($acts_section);
                                                            foreach ($acts_section as $actSection) {

                                                                $section_data = trim($actSection['section']);
                                                                $section_name = explode('(', $section_data);

                                                        ?>

                                                                <div class="row" id="category_update_row_<?= $i; ?>">
                                                                    <div class="col-md-7">
                                                                        <select class="form-control" name="act[]" id="act[]">
                                                                            <option value="" title="Select">Select Act</option>
                                                                            <?php
                                                                            foreach ($acts as $act) {
                                                                                if (isset($act['id'])) {
                                                                                    $selected_act = $act['id'] == $actSection['act'] ? 'selected' : " ";
                                                                                    echo '<option value="' . sanitize(($act['id'])) . '" ' . $selected_act . '>' . $act['id'] . ' - ' . sanitize(strtoupper($act['act_name'])) . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-1">
                                                                        <input type="text" class="form-control" id="section_1[]" name="section_1[]" maxlength="3" onkeypress="return onlynumbers(event);" value="<?php echo $section_name[0]; ?>">
                                                                    </div>
                                                                    <div class="col-sm-1">
                                                                        <input type="text" class="form-control" id="section_2[]" name="section_2[]" maxlength="3" onkeypress="return slashnot(event);" value="<?php echo isset($section_name[1]) ? substr($section_name[1], 0, -1) : ''; ?>">
                                                                    </div>
                                                                    <div class="col-sm-1">
                                                                        <input type="text" class="form-control" id="section_3[]" name="section_3[]" maxlength="3" onkeypress="return slashnot(event);" value="<?php echo isset($section_name[2]) ? substr($section_name[2], 0, -1) : ""; ?>">
                                                                    </div>
                                                                    <div class="col-sm-1">
                                                                        <input type="text" class="form-control" id="section_4[]" name="section_4[]" maxlength="3" onkeypress="return slashnot(event);" value="<?php echo isset($section_name[3]) ? substr($section_name[3], 0, -1) : ""; ?>">
                                                                    </div>

                                                                    <div class="col-sm-1">
                                                                        <button type="button" class="btn btn-danger float-sm-right" value="1" onclick="deleteRow('<?php echo $i; ?>');"><i class='fas fa-minus-circle'></i></button>
                                                                    </div>

                                                                </div>
                                                                <br />
                                                        <?php $i++;
                                                            }
                                                        }   ?>

                                                    </div>
                                                    <!-- End -->

                                                    <?php if ($case_status == 'P') : ?>
                                                        <center>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="saveStep2">Save</button>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    <?php endif; ?>
                                                    <br><br>
                                                    <?= form_close(); ?>

                                                </div>

                                                <div class="tab-pane" id="basic_details">
                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'name' => 'basic_detail_form', 'id' => 'basic_detail_form', 'autocomplete' => 'off');
                                                    echo form_open(base_url('Filing/Category/updateBasicDetails/'), $attribute);
                                                    ?>

                                                    <h4 class="basic_heading">Other Details </h4>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">Brief Description of IMPUGNED Order/Judgement/Award/Notification etc:</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="brief_description" name="brief_description" value="<?php echo $diary_details['brief_description'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">Claim Amount:</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="claim_amt" name="claim_amt" value="<?php echo $diary_details['claim_amt'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">Description of Relief Claimed:</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="relief" name="relief" value="<?php echo $diary_details['relief'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">Fixed For:</label>
                                                                <div class="col-sm-7">
                                                                    <select name="fixed_for" id="fixed_for" class="form-control select2">
                                                                        <option value="" title="Select">Select Fixed For</option>
                                                                        <?php
                                                                        foreach ($fixed_for as $fixed_for_row) {
                                                                            if (isset($fixed_for_row['id'])) {
                                                                                $selectedfixed_for = $fixed_for_row['id'] == $diary_details['fixed'] ? 'selected' : '';
                                                                                echo '<option value="' . sanitize(($fixed_for_row['id'])) . '" ' . $selectedfixed_for . '>' . sanitize(strtoupper($fixed_for_row['fixed_for_desc'])) . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">Listable Before:</label>
                                                                <div class="col-sm-7">
                                                                    <select name="bench" id="bench" class="form-control select2">
                                                                        <option value="" title="Select">Select Listable Before</option>
                                                                        <?php
                                                                        foreach ($listed_before as $listed_before_row) {
                                                                            if (isset($listed_before_row['id'])) {
                                                                                $selectedbench_for = $listed_before_row['id'] == $diary_details['bench'] ? 'selected' : '';
                                                                                echo '<option value="' . sanitize(($listed_before_row['id'])) . '"' . $selectedbench_for . '>' . sanitize(strtoupper($listed_before_row['bench_name'])) . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label"> Provision of Law :</label>
                                                                <div class="col-sm-7">
                                                                    <select name="actcode" id="actcode" class="form-control select2">
                                                                        <option value="" title="Select">Select Provision of Law</option>
                                                                        <?php
                                                                        foreach ($provision_of_law as $provision_of_law_row) {
                                                                            if (isset($provision_of_law_row['id'])) {
                                                                                $selectedactcode_for = $provision_of_law_row['id'] == $diary_details['actcode'] ? 'selected' : '';
                                                                                echo '<option value="' . sanitize(($provision_of_law_row['id'])) . '"' . $selectedactcode_for . '>' . sanitize(strtoupper($provision_of_law_row['law'])) . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($diary_details['valuation'] > 0) : ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-5 col-form-label"> Valuation:</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text" class="form-control" id="valuation" name="valuation" value="<?php echo $diary_details['valuation'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($diary_details['total_court_fee'] > 0) : ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-5 col-form-label"> Total Court Fee:</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text" class="form-control" id="total_court_fee" name="total_court_fee" value="<?php echo $diary_details['total_court_fee'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($diary_details['court_fee'] > 0) : ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-5 col-form-label"> Court Fee Paid:</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text" class="form-control" id="court_fee" name="court_fee" value="<?php echo $diary_details['court_fee'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">IF Sensitive Case </label>
                                                                <?php
                                                                $reasonforsensitive = $checked =  "";
                                                                if (!empty($sensitive_case_reason)) {
                                                                    $reasonforsensitive = $sensitive_case_reason[0]['reason'];
                                                                    $checked = "checked";
                                                                }

                                                                ?>
                                                                <div class="col-sm-7">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <input type="checkbox" name="if_sensitive" id="if_sensitive" value="1" <?php echo $checked; ?>>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="sensitive_case_reason" name="sensitive_case_reason" <?php echo (!empty($checked)) ? '' : 'readonly'; ?> value="<?php echo $reasonforsensitive; ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($case_status == 'P') : ?>
                                                        <center>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="saveStep3_">Save</button>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    <?php endif; ?>
                                                    <br><br>
                                                    <?= form_close(); ?>
                                                </div>
                                                <!-- /.petitioner_tab_panel -->
                                                <div class="tab-pane" id="keywords">
                                                    <?php
                                                    $attribute = array('class' => 'form-horizontal', 'name' => 'keyword_form', 'id' => 'keyword_form', 'autocomplete' => 'off');
                                                    echo form_open(base_url('Filing/Category/updateKeywords/'), $attribute);
                                                    ?>

                                                    <h4 class="basic_heading"> Keywords </h4>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Select Keyword</label>
                                                                <select class="duallistbox" multiple="multiple" name="diary_keyword[]" size="20">
                                                                    <?php 
                                                                        $sel = '';
                                                                        foreach ($keywords as $keyword) { 
                                                                            if (!empty($selected_keywords)) {
                                                                                $sel = '';
                                                                                if (in_array($keyword['id'], $selected_keywords)) {
                                                                                    $sel =  "selected=selected";
                                                                                }
                                                                            }        
                                                                    ?>
                                                                      <option <?= $sel; ?> value="<?= $keyword['id']; ?>"><?= $keyword['keyword_description']; ?></option>
                                                                    <?php } ?>

                                                                </select>

                                                                <!--<select class="duallistbox" multiple="multiple" name="diary_keyword[]">

                                                                    <?php /*$sel = '';
                                                                    if (!empty($diary_keywords)) {
                                                                        foreach ($diary_keywords as $diary_keyword) {
                                                                            $sel =  "selected=selected";  ?>
                                                                            <option <?= $sel; ?> value="<?= $diary_keyword['keyword_id']; ?>"><?= $diary_keyword['keyword_description']; ?></option>
                                                                    <?php }
                                                                    } ?>
                                                                    <?php
                                                                    foreach ($keywords as $keyword) { ?>
                                                                        <option value="<?= $keyword['id']; ?>"><?= $keyword['keyword_description']; ?></option>
                                                                    <?php } */?>

                                                                </select>-->


                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <?php if ($case_status == 'P') : ?>
                                                        <center>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="saveStep4">Save</button>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    <?php endif; ?>
                                                    <br><br>
                                                    <?= form_close(); ?>
                                                </div>
                                                <!-- /.respondent_tab_panel -->
                                            </div>

                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script>
        $('.remove_in_row').click(function() {
            var v = $(this).val();
            $('.delete_out_row_' + v).click();
            $(this).parent('.multi-field').remove();
        });

        $('.multi-field-wrapper').each(function() {
            var $wrapper = $('.multi-fields', this);
            $(".add-field", $(this)).click(function(e) {
                var length_row = $('.multi-field', $wrapper).length;
                var delete_out_row = 'delete_out_row_' + length_row;
                var delete_in_row = 'delete_in_row_' + length_row;
                $(".remove_out_row:first").val(length_row);
                $(".remove_in_row:first").val(length_row);

                $(".remove_out_row:first").addClass(delete_out_row);
                $(".remove_in_row:first").addClass(delete_in_row);
                $(".add-field:first").addClass("d-none");
                $(".remove-field:first").removeClass("d-none");

                $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('');
                $(".remove_out_row:first").removeClass(delete_out_row);
                $(".remove_in_row:first").removeClass(delete_in_row);

                $(".add-field:first").removeClass("d-none");
                $(".remove-field").removeClass("d-none");
                $(".remove-field:first").addClass("d-none");
            });

            $('.multi-field .remove_out_row', $wrapper).click(function() {
                var length_row = $('.multi-field', $wrapper).length;
                // alert('length_row='+length_row);
                if ($('.multi-field', $wrapper).length > 1)
                    $(this).parent('.multi-field').remove();
            });


        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on("click", "#if_sensitive", function () {
                if ($(this).is(":checked")) 
                    $("#sensitive_case_reason").prop('readonly',false);
                else $("#sensitive_case_reason").prop('readonly',true);
                    $("#sensitive_case_reason").val("");
            });


            var main_category = $('#main_category').val();
            if (main_category > 0) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        main_category_id: main_category,
                        selected_subcat: '<?php echo $submaster_id; ?>',
                    },
                    url: "<?php echo base_url('Common/Ajaxcalls/get_subcategories'); ?>",
                    success: function(data) {
                        $('#sub_category').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });
            }

            //----------Get All Subcategory List----------------------//
            $('#main_category').change(function() {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                var main_category_id = $(this).val();

                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        main_category_id: main_category_id
                    },
                    url: "<?php echo base_url('Common/Ajaxcalls/get_subcategories'); ?>",
                    success: function(data) {
                        $('#sub_category').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });

            });

            $('#saveStep1').click(function() {

                var main_category = $('#main_category').val();
                var sub_category = $('#sub_category').val();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        main_category: main_category,
                        sub_category: sub_category
                    },
                    url: "<?php echo base_url('Filing/Category/updateCategory/'); ?>",
                    success: function(data) {
                        // alert(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });

            });

            $('#saveStep3').click(function() {
                var basic_detail_form = jQuery("#basic_detail_form");
                jQuery.post("<?php echo base_url('Filing/Category/updateBasicDetails'); ?>", {
                        post_data: basic_detail_form.serialize(),
                    })
                    .done(function(data) {
                        alert(data);
                        updateCSRFToken();
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        updateCSRFToken();
                        console.log("AJAX Error: " + textStatus, errorThrown);
                    });
               
            });

            $('#saveStep4').click(function() {
                var keyword_form = jQuery("#keyword_form");
                jQuery.post("<?php echo base_url('Filing/Category/updateKeywords'); ?>", {
                        post_data: keyword_form.serialize(),
                    })
                    .done(function(data) {
                        alert(data);
                        updateCSRFToken();
                    });
               
            });
            $('#saveStep2').click(function() {
                var acts_section_form = jQuery("#acts_section_form");
                jQuery.post("<?php echo base_url('Filing/Category/updateActs'); ?>", {
                        post_data: acts_section_form.serialize(),
                    })
                    .done(function(data) {
                        alert(data);
                        updateCSRFToken();
                    });
                
            });
        });

        function onlynumbers(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            //alert(charCode);
            if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
                return true;
            }
            return false;
        }

        function slashnot(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode == 47) return false;
            else return true;
        }

        function deleteRow(i) {
            $('#category_update_row_' + i).html('');
        }
    </script>
