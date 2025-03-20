<?= view('header') ?>
<style>
    .custom-radio {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .custom_action_menu {
        float: left;
        display: inline-block;
        margin-left: 10px;
    }

    .table thead th,
    .table th {
        width: 50%;
    }

    .row {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <?= view('Filing/filing_breadcrumb'); ?>
                    <!-- /.card-header -->
                    <?php $year = 1930;
                    $current_year = date('Y');
                    $main_case_status = $diary_details['c_status'];

                    //if($main_case_status == 'P'){
                    if ($c_status == 'P' && (($active_fil_no == '' || $active_fil_no == null) || $IB_officer == '1' || $section_officer == '1' || $session_user == '1' || $session_user == '135'   || $session_user == '1124'   || $session_user == '592'   || $session_user == '724' || $session_user == '213' || $session_user == '775' || $casetype_idd == '9' || $casetype_idd == '10' || $casetype_idd == '25' || $casetype_idd == '26')) {



                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <h4 class="basic_heading"> Earlier Court Details </h4>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                        <?php if (session()->getFlashdata('error')) { ?>
                                            <div class="alert alert-danger" style="display: none;;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('error') ?></strong>
                                            </div>
                                        <?php } ?>
                                        <?php if (session()->getFlashdata('message_error')) { ?>
                                            <div class="alert alert-danger" style="display: none;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('message_error') ?></strong>
                                            </div>
                                        <?php } ?>
                                        <?php if (session()->getFlashdata('success_msg')) : ?>
                                            <div class="alert alert-success alert-dismissible" style="display: none;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                            </div>
                                        <?php endif; ?>

                                        <?php  //echo $_SESSION["captcha"];
                                        $attribute = array('name' => 'earlier_insert', 'id' => 'earlier_insert', 'autocomplete' => 'off');
                                        echo form_open(base_url('Filing/Earlier_court/insertEarlierCourt/'), $attribute);


                                        if (!empty($lc_judges)) {
                                            $selected_j_3 = "";
                                            foreach ($lc_judges as $judge_data) {
                                                $judgeArray[$judge_data['lowerct_id']][$judge_data['judge_id']] = $judge_data['judge_id'];
                                            }
                                        }
                                        $disabled_radio = "";
                                        if (!empty($lcc_id)) {
                                            $disabled_radio = "disabled";
                                        } else {
                                            $disabled_radio = "";
                                        }


                                        ?>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-group row ">


                                                            <div class="col-sm-12">
                                                                <label for="inputEmail3" class="col-form-label pr-2">Select Court <?php echo $ct_code; ?><span style="color: red">*</span> : </label>
                                                                <input type="radio" name="radio_selected_court" id="rd_sc" onClick="display_form(this.value)" <?php if ($ct_code == 4) { ?> checked="checked" <?php } ?> class="cl_hc_dc" value="4" <?php echo $disabled_radio ?> /> <span class="check-span-txt"> Supreme Court </span>
                                                                <input type="radio" name="radio_selected_court" id="rd_hc" onClick="display_form(this.value)" <?php if ($ct_code == 1) { ?> checked="checked" <?php } ?> class="cl_hc_dc" value="1" <?php echo $disabled_radio ?> /> <span class="check-span-txt">High Court</span>
                                                                <input type="radio" name="radio_selected_court" id="rd_dc" onClick="display_form(this.value)" <?php if ($ct_code == 3) { ?> checked="checked" <?php } ?> class="cl_hc_dc" value="3" <?php echo $disabled_radio ?> /> <span class="check-span-txt">District Court</span>
                                                                <input type="radio" name="radio_selected_court" id="rd_sa" onClick="display_form(this.value)" <?php if ($ct_code == 5) { ?> checked="checked" <?php } ?> class="cl_hc_dc" value="5" <?php echo $disabled_radio ?> /> <span class="check-span-txt">State Agency</span>

                                                                <!-- <a href="<?php //echo base_url('/Filing/Earlier_court/index/')
                                                                                ?>" class="btn btn-primary" style="float:right;">Add New</a>-->
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="diary_recieved_date" id="diary_recieved_date" value="<?php echo date('m/d/Y', strtotime($diary_details['diary_no_rec_date'])) ?>">
                                        <input type="hidden" name="ct_court_type" id="ct_court_type" value="<?php echo $ct_code; ?>">


                                        <div id="department_id_4" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="">
                                                        <label> Supreme Court :</label>
                                                        <div class="form-group row ">
                                                            <div class="col-md-6 pl-0">
                                                                <select name="state_agency" id="state_agency" class="custom-select rounded-0">
                                                                    <option value="490506">DELHI</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 pr-0">
                                                                <select name="district_id" id="district_id" class="custom-select rounded-0">
                                                                    <option value="10000">DELHI</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="lc_idd" id="lc_idd" value="<?php echo $lcc_id ?>">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :<span style="color: red">*</span></label>
                                                        <div class="input-group date_" id="impugned_date">
                                                            <input type="text" style="width: 90% !important;" class="form-control " id="impugned_date_s" name="impugned_date_s" value="<?php if ($ct_code == '4') {
                                                                                                                                                                                            echo $lct_dec_dt;
                                                                                                                                                                                        } ?>" />
                                                            <div class="input-group-append" data-target="#impugned_date" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control"> -->
                                                </div>

                                            </div>

                                        </div>
                                        <div id="department_id_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-8">

                                                    <div class="row">
                                                        <div class="col-md-6 pl-0">
                                                            <label> High Court :<span style="color: red">*</span></label>
                                                            <select name="state_agency_h" id="state_agency_h" class="custom-select rounded-0" style="width: 100%;">
                                                                <option value="">SELECT</option>
                                                                <?php foreach ($states as $state) :

                                                                    if ($ct_code == '1' && $l_state == $state['id_no']) {
                                                                        $selected_dist_h = "selected";
                                                                    } else {
                                                                        $selected_dist_h = "";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_dist_h; ?>><?php echo $state['name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 pr-0">
                                                            <label> Bench :<span style="color: red">*</span></label>
                                                            <select name="h_bench_id" id="h_bench_id" class="custom-select rounded-0">
                                                                <option value="">SELECT</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :<span style="color: red">*</span></label>
                                                        <div class="input-group date_" id="impugned_date1" data-target-input="nearest">
                                                            <input type="text" style="width: 90% !important;" class="form-control datetimepicker-input_" id="impugned_date_1" name="impugned_date_1" value="<?php if ($ct_code == '1') {echo $lct_dec_dt;} ?>" />
                                                            <!-- <div class="input-group-append" data-target="#impugned_date1_" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="department_id_5" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6 pl-0">
                                                            <label>State Agency :<span style="color: red">*</span></label>
                                                            <select name="state_agency_s" id="state_agency_s" class="custom-select rounded-0" style="width: 100%;">
                                                                <option value="">SELECT</option>
                                                                <?php foreach ($states as $state) :

                                                                    if ($ct_code == '5' && $l_state == $state['id_no']) {
                                                                        $selected_state_ag = "selected";
                                                                    } else {
                                                                        $selected_state_ag = "";
                                                                    }

                                                                ?>
                                                                    <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_state_ag; ?>><?php echo $state['name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 pr-0">
                                                            <label>Bench<span style="color: red">*</span></label>
                                                            <select name="district_ids" id="district_ids" class="custom-select rounded-0" style="width: 100%;">
                                                                <option value="">SELECT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :<span style="color: red">*</span></label>
                                                    <div class="input-group date_" id="impugned_date2_" data-target-input="nearest">
                                                        <input type="text" style="width: 90% !important;" class="form-control datetimepicker-input_" id="impugned_date_2" name="impugned_date_2" data-target="#impugned_date2" value="<?php if ($ct_code == '5') {
                                                                                                                                                                                                                                            echo $lct_dec_dt;
                                                                                                                                                                                                                                        } ?>" />
                                                        <!-- <div class="input-group-append" data-target="#impugned_date2_" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="department_id_shs" style="display:none;">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Case Type:<span style="color: red">*</span></label>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="case_type" id="case_type" class="custom-select rounded-0" style="width: 100%;">
                                                            <option value="">SELECT</option>
                                                            <?php if (!empty($case_all_types)) {
                                                                foreach ($case_all_types as $case_type) {

                                                                    if ($ct_code == 4) {
                                                                        if (!empty($lct_casetype)) {
                                                                            if ($lct_casetype == $case_type['casecode']) {
                                                                                $selected_case_type = "selected";
                                                                            } else {
                                                                                $selected_case_type = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type['casecode']) . '" ' . $selected_case_type . '>' . sanitize(strtoupper($case_type['skey'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($lct_casetype)) {
                                                                            if ($lct_casetype == $case_type['lccasecode']) {
                                                                                $selected_case_type = "selected";
                                                                            } else {
                                                                                $selected_case_type = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type['lccasecode']) . '" ' . $selected_case_type . '>' . sanitize(strtoupper($case_type['type_sname'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" id="lc_case_no_from" name="lc_case_no_from" class="form-control" placeholder="Enter Case Number" value="<?php if ($ct_code != '3') {
                                                                                                                                                                                        echo $lct_caseno;
                                                                                                                                                                                    } ?>" onkeypress="return onlynumbers(event)">
                                                    </div>
                                                </div>-
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?
                                                        $readonly_text = "";
                                                        if (!empty($lcc_id)) {
                                                            $readonly_text = "readonly";
                                                        } ?>
                                                        <input type="text" id="lc_case_no" name="lc_case_no" class="form-control" placeholder="Enter Case Number" onkeypress="return onlynumbers(event)" <?php echo @$readonly_text ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?php $year = 1930;
                                                        $current_year = date('Y');

                                                        ?>
                                                        <select name="lc_case_year" id="lc_case_year" class="custom-select rounded-0">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if ($ct_code != '3' && $lct_caseyear == $x) {
                                                                    $selected_case_y = "selected";
                                                                } else {
                                                                    $selected_case_y = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_case_y; ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Description (Reference at point no. 1) :</label>
                                                        <input type="text" class="form-control" id="order_description" name="order_description" class="form-control" value="<?php if ($ct_code != '3') {
                                                                                                                                                                                echo $bf_desc;
                                                                                                                                                                            } ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Subject/Law (Reference at point no. 1) :</label>
                                                        <input type="text" class="form-control" id="lc_subject_law" name="lc_subject_law" class="form-control" value="<?php if ($ct_code != '3') {
                                                                                                                                                                            echo $sub_law;
                                                                                                                                                                        } ?>">
                                                    </div>
                                                </div>
                                                <div id="department_state" class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Judge/Registrar/Member :</label>
                                                        <select class="select2bs4" multiple="multiple" data-placeholder="Select a Judge" name="judge_name_5[]" id="judge_name_5" style="width: 100%;">
                                                            <option>SELECT</option>

                                                            <? if (!empty($judges_list)) {
                                                                $selected_j_5 = "";
                                                                foreach ($judges_list as $judge) {
                                                                    if ($ct_code == 4) {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['jcode'], $judgeArray[$lcc_id])) {
                                                                                $selected_j_5 = "selected";
                                                                            } else {
                                                                                $selected_j_5 = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['jcode']) . '" ' . $selected_j_5 . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['id'], $judgeArray[$lcc_id])) {
                                                                                $selected_j_5 = "selected";
                                                                            } else {
                                                                                $selected_j_5 = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['id']) . '" ' . $selected_j_5 . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="sch_div" class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Judge/Registrar/Member </label>
                                                        <select class="select2bs4" multiple="multiple" data-placeholder="Select a Judge" name="judge_name[]" id="judge_name" style="width: 100%;">
                                                            <? if (!empty($judges_list)) {
                                                                $selected_j = "";
                                                                foreach ($judges_list as $judge) {
                                                                    if ($ct_code == 4) {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['jcode'], $judgeArray[$lcc_id])) {
                                                                                $selected_j = "selected";
                                                                            } else {
                                                                                $selected_j = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['jcode']) . '" ' . $selected_j . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['id'], $judgeArray[$lcc_id])) {
                                                                                $selected_j = "selected";
                                                                            } else {
                                                                                $selected_j = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['id']) . '" ' . $selected_j . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="department_id_2" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Impugned Order/ Award/ Notification/ Circular etc. passed by:(Authority)<span style="color: red">*</span></label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="order_passed_by" id="order_passed_by" onChange="get_authority(this.value);" class="custom-select rounded-0">
                                                            <option value="">Select</option>
                                                            <option value="D1">State Department</option>
                                                            <option value="D2">Central Department</option>
                                                            <option value="D3">Other Organisation</option>
                                                            <option value="X">Xtra</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" id="x_authdesc" name="x_authdesc" maxlength="100" size="25" disabled="disabled" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="auth_description" id="auth_description" class="custom-select rounded-0">
                                                            <option value="0">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label>Organisation / Organisation of the Auth. </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Impugned Order No: </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" size="5" name="auth_orgcode" id="auth_orgcode" maxlength="5" onKeyPress="set_m_orgname(this.value);" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" id="x_authdesc" name="x_authdesc" maxlength="100" size="25" disabled="disabled" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input type="text" size="40" name="impugned_order_no" maxlength="50" value="" id="impugned_order_no" class="form-control">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :<span style="color: red">*</span></label>
                                                    <div class="input-group date_" id="impugned_date3_" data-target-input="nearest">
                                                        <input type="text" style="width:90% !important;" class="form-control datetimepicker-input" id="impugned_date3" data-target="#impugned_date3_" />
                                                        <div class="input-group-append" data-target="#impugned_date3_" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <label>Brief Desc.of IMPUGNED Order/Judgement/Award/Notification etc:</label>
                                                    <input type="text" class="form-control" id="order_description_2" name="order_description_2" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label> Subject/Law involved in the IMPUGNED Order/Judgment/Award/Notification:</label>
                                                    <input type="text" class="form-control" id="lc_subject_law_2" name="lc_subject_law_2" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="department_id_3" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label> District :<span style="color: red">*</span></label>
                                                    <div class="row">
                                                        <div class="col-md-6 pl-0">
                                                            <div class="form-group">
                                                                <select name="state_agency_d" id="state_agency_d" class="custom-select rounded-0" style="width: 100%;">
                                                                    <option value="">SELECT</option>
                                                                    <?php foreach ($states as $state) :
                                                                        if ($ct_code == '3' && $l_state == $state['id_no']) {
                                                                            $selected_dis = "selected";
                                                                        } else {
                                                                            $selected_dis = "";
                                                                        }
                                                                    ?>
                                                                        <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_dis; ?>><?php echo $state['name'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 pr-0">
                                                            <div class="form-group">
                                                                <select name="district_idd" id="district_idd" class="custom-select rounded-0" style="width: 100%;">
                                                                    <option value="">SELECT</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> CNR NO:</label>
                                                        <input type="text" class="form-control" id="filing_no" name="filing_no" class="form-control" value="<?php echo $cnr_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Case Type:<span style="color: red">*</span></label>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <select name="case_type_d" id="case_type_d" class="custom-select rounded-0" style="width: 100%;">
                                                            <option value="">Select</option>

                                                            <?php if (!empty($case_all_types)) {

                                                                foreach ($case_all_types as $case_type) {

                                                                    if ($ct_code == 4) {
                                                                        if (!empty($lct_casetype)) {
                                                                            if ($lct_casetype == $case_type['casecode']) {
                                                                                $selected_case_type = "selected";
                                                                            } else {
                                                                                $selected_case_type = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type['casecode']) . '" ' . $selected_case_type . '>' . sanitize(strtoupper($case_type['casename'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($lct_casetype)) {
                                                                            if ($lct_casetype == $case_type['lccasecode']) {
                                                                                $selected_case_type = "selected";
                                                                            } else {
                                                                                $selected_case_type = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type['lccasecode']) . '" ' . $selected_case_type . '>' . sanitize(strtoupper($case_type['type_sname'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" id="lc_case_no_d" name="lc_case_no_d" class="form-control" placeholder="Enter Case Number" value="<?php if ($ct_code == '3') {
                                                                                                                                                                                    echo $lct_caseno;
                                                                                                                                                                                } ?>" onkeypress="return onlynumbers(event)">
                                                    </div>
                                                </div>-
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <?php $readonly_text = "";
                                                        if (!empty($lcc_id)) {
                                                            $readonly_text = "readonly";
                                                        } ?>
                                                        <input type="text" id="lc_case_no_to" name="lc_case_no_to" class="form-control" placeholder="Enter Case Number" onkeypress="return onlynumbers(event)" <?php echo $readonly_text; ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?php $year = 1930;
                                                        $current_year = date('Y');
                                                        ?>
                                                        <select name="lc_case_year_2" id="lc_case_year_2" class="custom-select rounded-0">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if ($ct_code == '3' && $lct_caseyear == $x) {
                                                                    $selected_case_y = "selected";
                                                                } else {
                                                                    $selected_case_y = "";
                                                                } ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_case_y; ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Judge Description:</label>
                                                        <select name="m_ljuddesc" id="m_ljuddesc" class="custom-select rounded-0" style="width: 100%;">
                                                            <option value="">Select</option>
                                                            <?php foreach ($m_ljuddesc as $m_ljuddescription) :
                                                                if ($ct_code == '3' && $lct_judge_desg == $m_ljuddescription['post_code']) {
                                                                    $selected_judge_des = "selected";
                                                                } else {
                                                                    $selected_judge_des = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $m_ljuddescription['post_code'] ?>" <?php echo  $selected_judge_des; ?>><?php echo $m_ljuddescription['post_name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><br></label>
                                                        <select name="judge_name_3[]" id="judge_name_3" class="select2bs4" multiple="multiple" style="width: 100%;">
                                                            <option value="0">SELECT</option>
                                                            <? if (!empty($judges_list)) {
                                                                $selected_j_3 = "";

                                                                foreach ($judges_list as $judge) {
                                                                    if ($ct_code == 4) {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['jcode'], $judgeArray[$lcc_id])) {
                                                                                $selected_j_3 = "selected";
                                                                            } else {
                                                                                $selected_j_3 = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['jcode']) . '" ' . $selected_j_3 . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($lc_judges)) {
                                                                            if (in_array($judge['id'], $judgeArray[$lcc_id])) {
                                                                                $selected_j_3 = "selected";
                                                                            } else {
                                                                                $selected_j_3 = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($judge['id']) . '"  ' . $selected_j_3 . '>' . sanitize(strtoupper($judge['first_name'] . " " . $judge['sur_name'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Police Station:</label>
                                                        <select name="m_policestn" id="m_policestn" class="custom-select rounded-0">
                                                            <option value="">Select</option>
                                                            <?php $selected_police = "";
                                                            if (!empty($police_station_list)) {

                                                                foreach ($police_station_list as $police_station) {
                                                                    if ($police_station['policestncd'] == $polstncode) {
                                                                        $selected_police = "selected";
                                                                    } else {
                                                                        $selected_police = "";
                                                                    }
                                                                    echo '<option value="' . sanitize($police_station['policestncd']) . '" ' . $selected_police . '>' . sanitize(strtoupper($police_station['policestndesc'])) . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> FIR No:</label>
                                                        <input type="text" class="form-control" id="crimeno" name="crimeno" class="form-control" value="<?php echo $crimeno; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> FIR Year:</label>
                                                        <?php $year = 1930;
                                                        $current_year = date('Y');
                                                        ?>
                                                        <select name="crimeyear" id="crimeyear" class="custom-select rounded-0">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if ($ct_code == '3' && $crimeyear == $x) {
                                                                    $selected_crime_year = "selected";
                                                                } else {
                                                                    $selected_crime_year = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_crime_year ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :<span style="color: red">*</span></label>
                                                        <div class="input-group date_" id="impugned_date5_" data-target-input="nearest">
                                                            <input type="text" style="width:90% !important" class="form-control datetimepicker-input_" data-target="#impugned_date5_" id="impugned_date_5" name="impugned_date_5" value="<?php if ($ct_code == '3') {
                                                                                                                                                                                                                                                echo $lct_dec_dt;
                                                                                                                                                                                                                                            } ?>" />
                                                            <div class="input-group-append" data-target="#impugned_date5_" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Brief Desc.of IMPUGNED Order/Judgement/Award/Notification etc:</label>
                                                        <input type="text" class="form-control" id="order_description_d" name="order_description_d" class="form-control" value="<?php if ($ct_code == '3') {
                                                                                                                                                                                    echo $bf_desc;
                                                                                                                                                                                } ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Subject/Law involved in the IMPUGNED Order/Judgment/Award/Notification:</label>
                                                        <input type="text" class="form-control" id="lc_subject_law_3" name="lc_subject_law_3" class="form-control" value="<?php if ($ct_code == '3') {
                                                                                                                                                                                echo $sub_law;
                                                                                                                                                                            } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="judment_challenged" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">

                                                    <label> <br> </label>
                                                    <div class="icheck-success">
                                                        <?php
                                                        // echo ">>".$is_order_challenged;
                                                        if ($is_order_challenged == 'Y') {
                                                            $checked_judge_challegned = "checked";
                                                        } else {
                                                            $checked_judge_challegned = "";
                                                        } ?>
                                                        <input type="checkbox" id="chk_judge_challenged" name="chk_judge_challenged" <?php echo $checked_judge_challegned; ?>>
                                                        <label for="chk_judge_challenged">Judgement Challanged</label>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Judgement Type </label>
                                                        <select name="lc_judgment_type" id="lc_judgment_type" class="custom-select rounded-0">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $full_interim_flag == 'F' ? $selected_F = 'selected' : $selected_F = "";
                                                            $full_interim_flag == 'I' ? $selected_I = 'selected' : $selected_I = "";
                                                            ?>
                                                            <option value="F" <?php echo $selected_F; ?>>Final</option>
                                                            <option value="I" <?php echo $selected_I; ?>>Interim</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Judgement Covered in:</label>
                                                        <input type="text" class="form-control" id="lc_judgement_covered_in" name="lc_judgement_covered_in" class="form-control" value="<?php if (isset($judgement_covered_in)) {
                                                                                                                                                                                            echo $judgement_covered_in;
                                                                                                                                                                                        } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="vehicle_number" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label> Vehicle Number(in Case of Motor Accident Claim matters) </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?php
                                                        $selected_v_state = "";
                                                        if (!empty($vehicle_details)) {
                                                            $vehicle_state = $vehicle_details[0]['state'];
                                                        }
                                                        ?>
                                                        <select name="ddl_vch_state" id="ddl_vch_state" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="0">SELECT</option>

                                                            <?php foreach ($states as $state) :
                                                                if (!empty($vehicle_details)) {
                                                                    if ($vehicle_state == $state['id_no']) {
                                                                        $selected_v_state = "selected";
                                                                    } else {
                                                                        $selected_v_state = "";
                                                                    }
                                                                }
                                                            ?>
                                                                <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_v_state; ?>><?php echo $state['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="rto_code" id="rto_code" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <? $selcted_v_code = "";
                                                            if (!empty($rto_codes)) {
                                                                foreach ($rto_codes as $rto_code) {
                                                                    if ($rto_code['id'] == $vehicle_code) {
                                                                        $selcted_v_code = "selected";
                                                                    } else {
                                                                        $selcted_v_code = "";
                                                                    }
                                                                    echo '<option value="' . sanitize($rto_code['id']) . '" ' . $selcted_v_code . '>' . sanitize(strtoupper($rto_code['code'])) . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="lc_vehicle_no" name="lc_vehicle_no" class="form-control" maxlength="7" value="<?php echo $vehicle_no; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="from_court" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label> Reference No.</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label> From Court :</label>
                                                        <select name="ddl_ref_court" id="ddl_ref_court" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php $selected_ref_court = "";
                                                            if (!empty($court_type_list_rel)) {
                                                                foreach ($court_type_list_rel as $c_type_list_type) {
                                                                    if ($c_type_list_type['id'] == $ref_court) {
                                                                        $selected_ref_court = "selected";
                                                                    } else {
                                                                        $selected_ref_court = "";
                                                                    }
                                                                    echo '<option value="' . sanitize($c_type_list_type['id']) . '" ' . $selected_ref_court . '>' . sanitize(strtoupper($c_type_list_type['court_name'])) . '</option>';
                                                                }
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label> State:</label>
                                                        <select name="ddl_ref_state" id="ddl_ref_state" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php $selected_ref_state = "";
                                                            foreach ($states as $state) :
                                                                if (!empty($ref_state) && ($ref_state == $state['id_no'])) {
                                                                    $selected_ref_state = "selected";
                                                                } else {
                                                                    $selected_ref_state = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_ref_state ?>><?php echo $state['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label> District:</label>
                                                        <select name="ddl_ref_district" id="ddl_ref_district" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php if (!empty($ref_district_list)) {
                                                                foreach ($ref_district_list as $ref_district_l) {

                                                                    if (!empty($ref_district)) {
                                                                        if ($ref_district == $ref_district_l['id']) {
                                                                            $selected_ref_district = "selected";
                                                                        } else {
                                                                            $selected_ref_district = "";
                                                                        }
                                                                    }
                                                                    echo '<option value="' . sanitize($ref_district_l['id']) . '" ' . $selected_ref_district . '>' . sanitize(strtoupper($ref_district_l['agency_name'])) . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label> Type:</label>
                                                        <select name="ddl_ref_case_type" id="ddl_ref_case_type" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>

                                                            <?php if (!empty($case_types_ref)) {
                                                                foreach ($case_types_ref as $case_type_ref) {

                                                                    if ($ref_court == 4) {

                                                                        if (!empty($ref_case_type)) {

                                                                            if ($ref_case_type == $case_type_ref['casecode']) {
                                                                                $selected_case_type_ref = "selected";
                                                                            } else {
                                                                                $selected_case_type_ref = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type_ref['casecode']) . '" ' . $selected_case_type_ref . '>' . sanitize(strtoupper($case_type_ref['casename'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($ref_case_type)) {
                                                                            if ($ref_case_type == $case_type_ref['lccasecode']) {
                                                                                $selected_case_type_ref = "selected";
                                                                            } else {
                                                                                $selected_case_type_ref = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type_ref['lccasecode']) . '" ' . $selected_case_type_ref . '>' . sanitize(strtoupper($case_type_ref['type_sname'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> No.:</label>
                                                        <input type="text" class="form-control" id="txt_ref_caseno" name="txt_ref_caseno" class="form-control" value="<?php echo $ref_case_no; ?>" onkeypress="return onlynumbers(event)">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> Year:</label>
                                                        <select name="ddl_ref_caseyr" id="ddl_ref_caseyr" name="ddl_ref_caseyr" class="custom-select rounded-0" style="width:100%;">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if (!empty($ref_case_year) && ($x == $ref_case_year)) {
                                                                    $selected_ref_case_year = "selected";
                                                                } else {
                                                                    $selected_ref_case_year = "";
                                                                } ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_ref_case_year; ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label> Relied Upon </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> From Court:</label>
                                                        <select name="ddl_relied_court" id="ddl_relied_court" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php $selected_rel_court = "";
                                                            if (!empty($court_type_list_rel)) {
                                                                foreach ($court_type_list_rel as $c_type_list_type) {
                                                                    if ($c_type_list_type['id'] == $relied_court) {
                                                                        $selected_rel_court = "selected";
                                                                    } else {
                                                                        $selected_rel_court = "";
                                                                    }
                                                                    echo '<option value="' . sanitize($c_type_list_type['id']) . '" ' . $selected_rel_court . '>' . sanitize(strtoupper($c_type_list_type['court_name'])) . '</option>';
                                                                }
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> State:</label>
                                                        <select name="ddl_relied_state" id="ddl_relied_state" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php $selected_ref_state = "";
                                                            foreach ($states as $state) :
                                                                if (!empty($relied_state) && ($relied_state == $state['id_no'])) {
                                                                    $selected_relied_state = "selected";
                                                                } else {
                                                                    $selected_relied_state = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_relied_state ?>><?php echo $state['name'] ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> District:</label>
                                                        <select name="ddl_relied_district" id="ddl_relied_district" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php $selected_relied_district = "";
                                                            if (!empty($relied_district_list)) {
                                                                foreach ($relied_district_list as $relied_district_l) {

                                                                    if (!empty($relied_district)) {
                                                                        if ($relied_district == $relied_district_l['id']) {
                                                                            $selected_relied_district = "selected";
                                                                        } else {
                                                                            $selected_relied_district = "";
                                                                        }
                                                                    }
                                                                    echo '<option value="' . sanitize($relied_district_l['id']) . '" ' . $selected_relied_district . '>' . sanitize(strtoupper($relied_district_l['agency_name'])) . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> Type:</label>
                                                        <select name="ddl_relied_case_type" id="ddl_relied_case_type" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>

                                                            <?php if (!empty($case_types_relied)) {
                                                                foreach ($case_types_relied as $case_type_relied) {

                                                                    if ($relied_court == 4) {

                                                                        if (!empty($relied_case_type)) {

                                                                            if ($relied_case_type == $case_type_relied['casecode']) {
                                                                                $selected_case_type_relied = "selected";
                                                                            } else {
                                                                                $selected_case_type_relied = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type_relied['casecode']) . '" ' . $selected_case_type_relied . '>' . sanitize(strtoupper($case_type_relied['casename'])) . '</option>';
                                                                    } else {
                                                                        if (!empty($relied_case_type)) {
                                                                            if ($relied_case_type == $case_type_relied['lccasecode']) {
                                                                                $selected_case_type_relied = "selected";
                                                                            } else {
                                                                                $selected_case_type_relied = "";
                                                                            }
                                                                        }
                                                                        echo '<option value="' . sanitize($case_type_relied['lccasecode']) . '" ' . $selected_case_type_relied . '>' . sanitize(strtoupper($case_type_relied['type_sname'])) . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> No.:</label>
                                                        <input type="text" class="form-control" id="txt_relied_caseno" name="txt_relied_caseno" class="form-control" value="<?php echo $relied_case_no; ?>" onkeypress="return onlynumbers(event)">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                    <label> Year:</label>
                                                        <select name="ddl_relied_caseyr" id="ddl_relied_caseyr" class="custom-select rounded-0" style="width:100%;">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if (!empty($relied_case_year) && ($x == $relied_case_year)) {
                                                                    $selected_relied_case_year = "selected";
                                                                } else {
                                                                    $selected_relied_case_year = "";
                                                                } ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_relied_case_year; ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div id="govt_notification" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label> Government Notification No. </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <select name="ddl_gov_not_state" id="ddl_gov_not_state" class="custom-select rounded-0" style="width:100%;">
                                                            <option value="">SELECT</option>
                                                            <?php foreach ($states as $state) :
                                                                if (!empty($gov_not_state_id) && ($gov_not_state_id == $state['id_no'])) {

                                                                    $selected_gov = "selected";
                                                                } else {
                                                                    $selected_gov = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_gov; ?>><?php echo $state['name'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="txt_gov_not_no" name="txt_gov_not_no" value="<?php echo $gov_not_case_type ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="txt_g_n_no" name="txt_g_n_no" placeholder="Enter Number" value="<?php echo $gov_not_case_no ?>" onkeypress="return onlynumbers(event)">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <select name="ddl_g_n_y" id="ddl_g_n_y" class="custom-select rounded-0">
                                                            <?php for ($x = $current_year; $x >= $year; $x--) {
                                                                if (!empty($gov_not_case_year) && ($x == $gov_not_case_year)) {
                                                                    $selected_gov_case_year = "selected";
                                                                } else {
                                                                    $selected_gov_case_year = "";
                                                                }
                                                            ?>
                                                                <option value="<?php echo $x; ?>" <?php echo $selected_gov_case_year; ?>><?php echo $x; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group date_" id="government_notification_year_" data-target-input="nearest">
                                                        <input style="width:80% !important;" type="text" class="form-control datetimepicker-input_" data-target="#government_notification_year_" id="government_notification_date" name="government_notification_date" value="<?php echo $gov_not_date; ?>" />
                                                        <div class="input-group-append" data-target="#government_notification_year_" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                    <!-- <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control"> -->
                                                </div>

                                            </div>
                                        </div>
                                        <input type="hidden" id="d_case_type" name="d_case_type" value="<?php echo $diary_details['casetype_id'] ?>">
                                        <?php if ($diary_details['casetype_id'] == '7' || $diary_details['casetype_id'] == '8') {
                                            if (!empty($lcc_id)) {
                                                $styletransfer = "display:flex";
                                            } else {
                                                $styletransfer = "display:none";
                                            }
                                        ?>
                                            <div class="row" style="<?php echo $styletransfer ?>" id="t_div">
                                                <div class="col-md-12">
                                                    <label>Transfer To </label>
                                                </div>
                                            </div>
                                            <div class="row" style="<?php echo $styletransfer ?>" id="t1_div">
                                                <div class="col-md-2">
                                                    <select name="ddl_transfer_to" id="ddl_transfer_to" class="custom-select rounded-0" style="width:100%;">
                                                        <option value="">SELECT</option>
                                                        <?php $selected_trasfer_court = "";
                                                        if (!empty($court_type_list_rel)) {
                                                            foreach ($court_type_list_rel as $c_type_list_type) {
                                                                if ($c_type_list_type['id'] == $transfer_court) {
                                                                    $selected_trasfer_court = "selected";
                                                                } else {
                                                                    $selected_trasfer_court = "";
                                                                }
                                                                echo '<option value="' . sanitize($c_type_list_type['id']) . '" ' . $selected_trasfer_court . '>' . sanitize(strtoupper($c_type_list_type['court_name'])) . '</option>';
                                                            }
                                                        }

                                                        ?>


                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="ddl_tra_to_state" id="ddl_tra_to_state" class="custom-select rounded-0" style="width:100%;">
                                                        <option value="">SELECT</option>
                                                        <?php $selected_transfer_state = "";
                                                        foreach ($states as $state) :
                                                            if (!empty($transfer_state) && ($transfer_state == $state['id_no'])) {
                                                                $selected_transfer_state = "selected";
                                                            } else {
                                                                $selected_transfer_state = "";
                                                            }
                                                        ?>
                                                            <option value="<?php echo $state['id_no'] ?>" <?php echo $selected_transfer_state; ?>><?php echo $state['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="ddl_tra_to_district" id="ddl_tra_to_district" class="custom-select rounded-0" style="width:100%;">
                                                        <option value="">SELECT</option>
                                                        <?php if (!empty($transfer_district_list)) {
                                                            foreach ($transfer_district_list as $transfer_district_l) {

                                                                if (!empty($transfer_district)) {
                                                                    if ($transfer_district == $transfer_district_l['id']) {
                                                                        $selected_t_district = "selected";
                                                                    } else {
                                                                        $selected_t_district = "";
                                                                    }
                                                                }
                                                                echo '<option value="' . sanitize($transfer_district_l['id']) . '" ' . $selected_t_district . '>' . sanitize(strtoupper($transfer_district_l['agency_name'])) . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="ddl_tra_to_case_type" id="ddl_tra_to_case_type" class="custom-select rounded-0" style="width:100%;">
                                                        <option value="">SELECT</option>
                                                        <?php if (!empty($case_types_transfer)) {
                                                            foreach ($case_types_transfer as $case_type_transfer) {

                                                                if ($transfer_court == 4) {

                                                                    if (!empty($transfer_case_type)) {

                                                                        if ($transfer_case_type == $case_type_transfer['casecode']) {
                                                                            $selected_case_type_relied = "selected";
                                                                        } else {
                                                                            $selected_case_type_relied = "";
                                                                        }
                                                                    }
                                                                    echo '<option value="' . sanitize($case_type_transfer['casecode']) . '" ' . $selected_case_type_relied . '>' . sanitize(strtoupper($case_type_transfer['casename'])) . '</option>';
                                                                } else {
                                                                    if (!empty($transfer_case_type)) {
                                                                        if ($transfer_case_type == $case_type_transfer['lccasecode']) {
                                                                            $selected_case_type_relied = "selected";
                                                                        } else {
                                                                            $selected_case_type_relied = "";
                                                                        }
                                                                    }
                                                                    echo '<option value="' . sanitize($case_type_transfer['lccasecode']) . '" ' . $selected_case_type_relied . '>' . sanitize(strtoupper($case_type_transfer['type_sname'])) . '</option>';
                                                                }
                                                            }
                                                        } ?>

                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" id="txt_tra_to_caseno" name="txt_tra_to_caseno" class="form-control" value="<?php echo $transfer_case_no ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="ddl_tra_to_caseyr" id="ddl_tra_to_caseyr" class="custom-select rounded-0" style="width:100%;">
                                                        <?php for ($x = $current_year; $x >= $year; $x--) {
                                                            if (!empty($transfer_case_year) && ($x == $transfer_case_year)) {
                                                                $selected_transfer_case_year = "selected";
                                                            } else {
                                                                $selected_transfer_case_year = "";
                                                            } ?>
                                                            <option value="<?php echo $x; ?>" <?php echo $selected_transfer_case_year; ?>><?php echo $x; ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>

                                            </div>
                                        <?php } ?>
                                        <br><br>
                                        <center>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <?php if (empty($lcc_id)) : ?>
                                                        <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="addButton" style="display:none;" onclick="checkvalidations();">Submit</button>
                                                    <?php else : ?>
                                                        <button type="submit" class="btn btn-block bg-gradient-success form_save_first" id="updateButton">Submit</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </center>
                                        <?php echo form_close(); ?>
                                        <div class="row">
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3">
                                                <a href="<?php echo base_url('Filing/Similarity/') ?>"><button class="btn btn-block bg-gradient-success form_save_first">View Similarities</button></a>
                                            </div>
                                        </div>
                                        <br>

                                    <?php } else if ($c_status == 'P' && ($fil_no != '' || $fil_no != null)) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header p-2" style="background-color: #fff;">
                                                        <h4 class="basic_heading" style="color:red;"> Case has been Registered. Lower Court Details can be updated by Concerned Section or I-B officer Only!!!!</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header p-2" style="background-color: #fff;">
                                                        <h4 class="basic_heading" style="color:red;"> Case has been disposed</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if ($c_status == 'P' && (($active_fil_no == '' || $active_fil_no == null) || $IB_officer == '1' || $section_officer == '1' || $session_user == '1' || $session_user == '135'   || $session_user == '1124'   || $session_user == '592'   || $session_user == '724' || $session_user == '213' || $session_user == '775' || $casetype_idd == '9' || $casetype_idd == '10' || $casetype_idd == '25' || $casetype_idd == '26')) {
                                        if (is_array($result)) : ?>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Lower Court List</h3>

                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="lowercourtdata" class="table table-striped custom-table table-hover dt-responsive" style="width: 100%;">
                                                            <thead>
                                                                <tr>

                                                                    <th>#</th>
                                                                    <th>Action</th>
                                                                    <th>Court</th>
                                                                    <th>Agency State </th>
                                                                    <th>Agency Code</th>
                                                                    <th>Case No</th>
                                                                    <th>Order Date</th>
                                                                    <th>CNR No. / Designation</th>
                                                                    <th>Judge1/Judge2/Judge3 </th>
                                                                    <th>Judgement Challanged </th>
                                                                    <th>Judgement Type</th>
                                                                    <th>Description</th>
                                                                    <th>Subject/Law </th>
                                                                    <th>Police Station </th>
                                                                    <th>Crime No./Year </th>
                                                                    <th>Authority / Organisation / Impugned Order No. </th>
                                                                    <th>Judgement Covered in </th>
                                                                    <th>Vehicle Number </th>
                                                                    <th>Reference court / State / District / No. </th>
                                                                    <th>Relied Upon court / State / District / No. </th>
                                                                    <th>Transfer To court / State / District / No. </th>
                                                                    <th>Government Notification State / No. / Date </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $sn = 1;
                                                                //pr($result); 
                                                                foreach ($result as $lower_court_details) {
                                                                    // pr($lower_court_details);



                                                                    $cnr_designation = "";
                                                                    $case_no  = $lower_court_details['type_sname'];
                                                                    if ($lower_court_details['lct_casetype'] == 50) {
                                                                        $case_no = $case_no . "WNN";
                                                                    }
                                                                    if ($lower_court_details['lct_casetype'] == 51) {
                                                                        $case_no = $case_no . "ARN";
                                                                    }
                                                                    $case_no = $case_no . "-" . $lower_court_details['lct_caseno'] . "-" . $lower_court_details['lct_caseyear'];

                                                                    if ($lower_court_details['cnr_no'] != "") {
                                                                        $cnr_designation = $lower_court_details['cnr_no'] . '/';
                                                                    }

                                                                    $cnr_designation = $cnr_designation . $lower_court_details['post_name'];

                                                                    if ($lower_court_details['full_interim_flag'] == 'I')
                                                                        $full_interim_flag = 'Interim';
                                                                    else  if ($lower_court_details['full_interim_flag'] == 'F')
                                                                        $full_interim_flag =  'Final';
                                                                    else
                                                                        $full_interim_flag = '-';

                                                                    if ($lower_court_details['ct_code'] == '4')
                                                                        $court_name = "Supreme Court";
                                                                    else  if ($lower_court_details['ct_code'] == '1')
                                                                        $court_name =  "High Court";
                                                                    else  if ($lower_court_details['ct_code'] == '3')
                                                                        $court_name =  "District Court";
                                                                    else  if ($lower_court_details['ct_code'] == '2')
                                                                        $court_name =  "Other";
                                                                    else  if ($lower_court_details['ct_code'] == '5')
                                                                        $court_name =  "State Agency";

                                                                    $cno = $lower_court_details['lct_caseno'];
                                                                    $cy =  $lower_court_details['lct_caseyear'];

                                                                    $editURL = base_url('Filing/Earlier_court/index/' . $lower_court_details['lower_court_id']);

                                                                    // echo "select group_concat(concat(substr(caveat_no,1,length(caveat_no)-4),'/',substr(caveat_no,-4))) as ans from caveat_lowerct where caveat_no in(select caveat_no from caveat_diary_matching  where diary_no = 247892023 and display='Y') and lct_caseyear=$cy and lct_caseno=$cno and lw_display='Y'";
                                                                ?>
                                                                    <tr>
                                                                        <input type="hidden" id="lc_id_<?php echo $sn; ?>" value="<?php echo $lower_court_details['lower_court_id']; ?>">
                                                                        <input type="hidden" id="lc_cno_<?php echo $sn; ?>" value="<?php echo $lower_court_details['lct_caseno']; ?>">
                                                                        <input type="hidden" id="lc_cy_<?php echo $sn; ?>" value="<?php echo $lower_court_details['lct_caseyear']; ?>">
                                                                        <input type="hidden" id="lc_oc_<?php echo $sn; ?>" value="<?php echo $lower_court_details['is_order_challenged']; ?>">
                                                                        <input type="hidden" id="lc_ct_<?php echo $sn; ?>" value="<?php echo $lower_court_details['lct_casetype']; ?>">
                                                                        <input type="hidden" id="lc_ct_code_<?php echo $sn; ?>" value="<?php echo $lower_court_details['ct_code']; ?>">

                                                                        <td><?php echo $sn; ?>
                                                                        </td>
                                                                        <td>
                                                                            <div class="btn btn-success btn-sm table-edit-btn" type="button" onclick="editLowerCourtDetails('<?php echo $sn; ?>')"><i class="fas fa-pencil" aria-hidden="true"></i></a></div>
                                                                            <div class="btn btn-danger btn-sm table-edit-btn" type="button" onclick="deleteLowerCourt('<?php echo $sn; ?>')"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                                                        </td>
                                                                        <td><?php echo $court_name ?? '' //$lower_court_details['lower_court_id'] 
                                                                            ?></td>
                                                                        <td><?php echo $lower_court_details['name'] ?></td>
                                                                        <td><?php echo $lower_court_details['agency_name'] ?></td>
                                                                        <td><?php echo $case_no ?></td>
                                                                        <td><?php echo (!empty($lower_court_details['lct_dec_dt'])) ? date("d-m-Y", strtotime($lower_court_details['lct_dec_dt'])) : '' ?></td>
                                                                        <td><?php echo $cnr_designation ?></td>
                                                                        <td><?php
                                                                            if (!empty($judges_details)) {
                                                                                if (!empty($judges_details[$lower_court_details['lower_court_id']])) {
                                                                                    $judgesArray = $judges_details[$lower_court_details['lower_court_id']];
                                                                                    foreach ($judgesArray as $jud) {
                                                                                        echo $judge_name = $jud['judge_name'] . '<br>';
                                                                                    }
                                                                                } else {
                                                                                    echo '-';
                                                                                }
                                                                            }

                                                                            /* $judgesArray =  getJudgeDetailsByLowerct($lower_court_details['lower_court_id'],$lower_court_details['ct_code']);                                                                   
                                                                    if (!empty($judgesArray)) {                                                                       
                                                                        foreach ($judgesArray as $jud) {
                                                                            if($lower_court_details['ct_code'] == 4)
                                                                            {
                                                                                echo $judge_name = $jud['jname'] . '<br>';
                                                                            }else{
                                                                                echo $judge_name = $jud['title'] .' '.$jud['first_name'].' '.$jud['sur_name'] .'<br>';
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo '-';
                                                                    }  */
                                                                            ?></td>
                                                                        <td><?php echo $lower_court_details['is_order_challenged'] == 'Y' ? 'Yes' : 'No'; ?></td>
                                                                        <td><?php echo $full_interim_flag ?></td>
                                                                        <td><?php echo $lower_court_details['desc1'] ?></td>
                                                                        <td><?php echo $lower_court_details['usec2'] ?></td>
                                                                        <td>
                                                                            <?php echo $lower_court_details['policestndesc'] ?>

                                                                            <?php

                                                                            /* if(!empty($lower_court_details['polstncode']))
                                                                {                                                                    
                                                                    $police_name =   array_values(get_police_station_name($lower_court_details['polstncode'],$lower_court_details['l_state'],$lower_court_details['l_dist']));
                                                                    
                                                                    echo $police_name[0]['policestndesc'];
                                                                } */

                                                                            ?></td>
                                                                        <td><?php echo $lower_court_details['crimeno'] . '/' . $lower_court_details['crimeyear'] ?></td>
                                                                        <td><?php echo '/' ?></td>
                                                                        <td><?php echo $lower_court_details['judgement_covered_in'] ?></td>
                                                                        <td>
                                                                            <?php echo $lower_court_details['code'] . ' ' . $lower_court_details['vehicle_no'] ?>
                                                                            <?php
                                                                            /*
                                                                 $data = get_databy_rto_id($lower_court_details['vehicle_code']);
                                                                 if(!empty($data))
                                                                 {
                                                                     echo $data[0]['code'] . ' ';
                                                                 }*/
                                                                            //echo $lower_court_details['vehicle_no'] 

                                                                            ?></td>
                                                                        <td><?php
                                                                            if (!empty($all_ref_details[$lower_court_details['lower_court_id']])) {
                                                                                $ref_data = $all_ref_details[$lower_court_details['lower_court_id']];
                                                                                // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                                                                                echo $ref_data['court_name'] . '/' . $ref_data['name'] . '/' . $ref_data['reference_name'] . '/' . $ref_data['case_name'];
                                                                            } else {
                                                                                echo ' / ' . ' / ' . ' / ' . ' / ';
                                                                            }
                                                                            ?></td>
                                                                        <td><?php
                                                                            if (!empty($all_relied_details[$lower_court_details['lower_court_id']])) {
                                                                                $relied_data = $all_relied_details[$lower_court_details['lower_court_id']];
                                                                                // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                                                                                echo $relied_data['court_name'] . '/' . $relied_data['name'] . '/' . $relied_data['reference_name'] . '/' . $relied_data['case_name'];
                                                                            } else {
                                                                                echo ' / ' . ' / ' . ' / ' . ' / ';
                                                                            }
                                                                            ?></td>
                                                                        <td><?php
                                                                            if (!empty($all_transfer_details[$lower_court_details['lower_court_id']])) {
                                                                                $transfer_data = $all_transfer_details[$lower_court_details['lower_court_id']];
                                                                                // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                                                                                echo $transfer_data['court_name'] . '/' . $transfer_data['name'] . '/' . $transfer_data['reference_name'] . '/' . $transfer_data['case_name'];
                                                                            } else {
                                                                                echo ' / ' . ' / ' . ' / ' . ' / ';
                                                                            }
                                                                            ?></td>
                                                                        <td><?php
                                                                            if (!empty($all_gov_not_details[$lower_court_details['lower_court_id']])) {
                                                                                $govt_data = $all_gov_not_details[$lower_court_details['lower_court_id']];
                                                                                // print_r($all_ref_details[$lower_court_details['lower_court_id']]);
                                                                                $g_n_date = date_create($govt_data['gov_not_date']);
                                                                                $govt_data['gov_not_date'] = date_format($g_n_date, "d-m-Y");

                                                                                echo $govt_data['name'] . '/' . $govt_data['case_name'] . '/' . $govt_data['gov_not_date'];
                                                                            } else {
                                                                                echo ' / ' . ' / ' . ' / ';
                                                                            }
                                                                            ?></td>
                                                                    </tr>
                                                                <?php $sn++;
                                                                } ?>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endif;
                                    } ?>
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
    <?php if (!empty($lcc_id)) {
    ?>

        display_form_edit(<?php echo $ct_code; ?>);
        getBenchList1(<?php echo $ct_code ?>, <?php echo $l_state ?>, <?php echo $l_dist ?>);
        $("input[name=radio_selected_court][value=<?php echo $ct_code; ?>]").prop("checked", true);
    <?php

    } else { ?>
        //alert(<?php echo $diary_details['from_court']; ?>);
        display_form(<?php echo $diary_details['from_court']; ?>);
        $("input[name=radio_selected_court][value=<?php echo $diary_details['from_court']; ?>]").prop("checked", true);
    <?php } ?>

    function getBenchList1(court_type, state_agency, l_dist) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (court_type == 1) {
            var field_updated = "h_bench_id";
        } else if (court_type == 3) {
            var field_updated = "district_idd";
        } else if (court_type == 5) {
            var field_updated = "district_ids";
        }

        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                high_court_id: state_agency,
                court_type: court_type,
                bench_id: l_dist,
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list'); ?>",
            success: function(data) {
                $('#' + field_updated).html(data);
                //updateCSRFToken();
                $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function display_form_edit(department_id) {

        $('#department_id_' + department_id).css('display', 'inline');
        $('#judment_challenged').css('display', 'inline');
        $('#vehicle_number').css('display', 'inline');
        $('#from_court').css('display', 'inline');
        $('#govt_notification').css('display', 'none');
        $('#department_id_shs').css('display', 'none');
        $('#sch_div').css('display', 'none');
        for (let i = 1; i <= 5; i++) {
            if (i != department_id) {
                if (department_id == 2) {
                    $('#department_id_shs').css('display', 'none');
                    $('#from_court').css('display', 'none');
                }
                $('#department_id_' + i).css('display', 'none');
            } else {
                if (department_id == 1 || department_id == 4 || department_id == 5) {
                    $('#department_id_shs').css('display', 'inline');
                    $('#department_state').css('display', 'none');

                }
            }
        }
        if (department_id == 5) {
            $('#department_state').css('display', 'inline');
            $('#from_court').css('display', 'inline');

        }
        if (department_id == 2 || department_id == 5) {
            $('#govt_notification').css('display', 'inline');
        }
        if (department_id == 1 || department_id == 4 || department_id == 5) {
            $('#department_id_shs').css('display', 'inline');
        }
        if (department_id == 4 || department_id == 1) {
            $('#sch_div').css('display', 'inline');

        }
    }

    function display_form(department_id) {
        // alert(department_id);
        //$('#earlier_insert')[0].reset();

        $('#department_id_' + department_id).css('display', 'inline');
        $('#judment_challenged').css('display', 'inline');
        $('#vehicle_number').css('display', 'inline');
        $('#from_court').css('display', 'inline');
        $('#govt_notification').css('display', 'none');
        $('#department_id_shs').css('display', 'none');
        $('#sch_div').css('display', 'none');
        var d_case_type = $('#d_case_type').val();

        if (d_case_type == 7 || d_case_type == 8) {
            $('#t1_div').css('display', 'flex');
            $('#t_div').css('display', 'flex');
        }
        $('#addButton').css('display', 'block');
        for (let i = 1; i <= 5; i++) {
            if (i != department_id) {
                if (department_id == 2) {
                    $('#department_id_shs').css('display', 'none');
                    $('#from_court').css('display', 'none');
                }
                $('#department_id_' + i).css('display', 'none');
            } else {
                if (department_id == 1 || department_id == 4 || department_id == 5) {
                    $('#department_id_shs').css('display', 'inline');
                    $('#department_state').css('display', 'none');

                }
            }
        }
        if (department_id == 5) {
            $('#department_state').css('display', 'inline');
            $('#from_court').css('display', 'inline');
        }
        if (department_id == 2 || department_id == 5) {
            $('#govt_notification').css('display', 'inline');
        }
        if (department_id == 1 || department_id == 4 || department_id == 5) {
            $('#department_id_shs').css('display', 'inline');
        }
        if (department_id == 4 || department_id == 1) {
            $('#sch_div').css('display', 'inline');

        }
        if (department_id == 4) {
            getAllCaseTypes('490506', 4);
            setTimeout(function() {
                getAllJudges('490506', 4)
            }, 300);
        }
        setTimeout(function() {
            get_m_court_list(department_id)
        }, 1000);


    }

    function get_m_court_list(department_id) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                court_type: department_id
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_m_from_court_list'); ?>",
            success: function(data) {
                $('#ddl_ref_court').html(data);
                $('#ddl_relied_court').html(data);
                $('#ddl_transfer_to').html(data);
                updateCSRFToken();
            }
        });
    }

    function getAllJudges(state_agency, court_type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                state_agency: state_agency,
                court_type: court_type
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_judges_list'); ?>",
            success: function(data) {
                if (court_type == 3) {
                    $('#judge_name_3').html(data);
                } else if (court_type == 5) {
                    $('#judge_name_5').html(data);
                } else {
                    $('#judge_name').html(data);
                }
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }

    function getAllCaseTypes(state_agency, court_type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                state_agency: state_agency,
                court_type: court_type
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_case_types'); ?>",
            success: function(data) {
                if (court_type == 3) {
                    $('#case_type_d').html(data);
                } else {
                    $('#case_type').html(data);
                }
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }

    function getAllSelectedCaseTypes(state_agency, court_type, case_type) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                state_agency: state_agency,
                court_type: court_type,
                case_type: case_type
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_case_types'); ?>",
            success: function(data) {
                if (court_type == 3) {
                    $('#case_type_d').html(data);
                } else {
                    $('#case_type').html(data);
                }
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }

    function getAllCaseTypeForReference(state_agency, court_type, column_name) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                state_agency: state_agency,
                court_type: court_type
            },
            url: "<?php echo base_url('Common/Ajaxcalls/get_case_types'); ?>",
            success: function(data) {
                $('#' + column_name).html(data);
                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }
        });
    }

    $(document).on("focus", "#impugned_date_s,#impugned_date_1,#impugned_date_2,#impugned_date3,#government_notification_date,#impugned_date_5", function() {
        $('#impugned_date_s,#impugned_date_1,#impugned_date_2,#impugned_date3,#government_notification_date,#impugned_date_5').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });


    $(document).ready(function() {

        /*  $('#impugned_date,#impugned_date1,#impugned_date2,#impugned_date3,#government_notification_year,#impugned_date5').datetimepicker({
              format:'DD-MM-YYYY',
          }); */



        $('#state_agency_h,#state_agency_d,#state_agency_s').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $(this).val();
            var court_type = $("input[name=radio_selected_court]:checked").val();
            var field_updated = "";

            if (court_type == 1) {
                var field_updated = "h_bench_id";
            } else if (court_type == 3) {
                var field_updated = "district_idd";
            } else if (court_type == 5) {
                var field_updated = "district_ids";
            }

            $('#judge_name').html('');
            $('#judge_name_3').html('');
            $('#judge_name_5').html('');
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    high_court_id: state_agency,
                    court_type: court_type
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list_all_case_type_all_judges'); ?>",
                success: function(data) {
                    var resArr = data.split('@@@');
                    console.log(resArr);
                    $('#' + field_updated).html(resArr[0]);
                    if (court_type == 3) {
                        $('#case_type_d').html(resArr[1]);
                        $('#judge_name_3').html(resArr[2]);
                    } else if (court_type == 5) {
                        $('#case_type').html(resArr[1]);
                        $('#judge_name_5').html(resArr[2]);
                    } else {
                        $('#case_type').html(resArr[1]);
                        $('#judge_name').html(resArr[2]);
                    }
                    updateCSRFToken();
                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });


        $('#ddl_ref_state').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $(this).val();
            var court_type = $("#ddl_ref_court").val();
            $('#ddl_ref_district').html("");
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    high_court_id: state_agency,
                    court_type: court_type
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list'); ?>",
                success: function(data) {
                    $('#ddl_ref_district').html(data);
                    updateCSRFToken();
                    setTimeout(() => {
                        getAllCaseTypeForReference(state_agency, court_type, "ddl_ref_case_type");
                    }, 700);

                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });

        $('#ddl_ref_district').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $("#ddl_ref_state").val();
            var court_type = $("#ddl_ref_court").val();
            getAllCaseTypeForReference(state_agency, court_type, "ddl_ref_case_type");
        });
        $('#ddl_relied_state').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $(this).val();
            var court_type = $("#ddl_relied_court").val();
            $('#ddl_relied_district').html("");
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    high_court_id: state_agency,
                    court_type: court_type
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list'); ?>",
                success: function(data) {
                    $('#ddl_relied_district').html(data);
                    updateCSRFToken();
                    setTimeout(() => {
                        getAllCaseTypeForReference(state_agency, court_type, "ddl_relied_case_type");
                    }, 700);

                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });

        $('#ddl_relied_district').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $("#ddl_relied_state").val();
            var court_type = $("#ddl_relied_court").val();
            getAllCaseTypeForReference(state_agency, court_type, "ddl_relied_case_type");
        });

        $('#ddl_tra_to_state').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $(this).val();
            var court_type = $("#ddl_transfer_to").val();
            $('#ddl_tra_to_district').html("");
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    high_court_id: state_agency,
                    court_type: court_type
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list'); ?>",
                success: function(data) {
                    $('#ddl_tra_to_district').html(data);
                    updateCSRFToken();
                    setTimeout(() => {
                        getAllCaseTypeForReference(state_agency, court_type, "ddl_tra_to_case_type");
                    }, 700);

                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });

        $('#ddl_tra_to_district').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var state_agency = $("#ddl_tra_to_state").val();
            var court_type = $("#ddl_transfer_to").val();
            getAllCaseTypeForReference(state_agency, court_type, "ddl_tra_to_case_type");
        });


        $('#ddl_transfer_to').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var court_type = $("#ddl_transfer_to").val();
            if (court_type == 4) {
                $("#ddl_tra_to_state").html('');
                $('#ddl_tra_to_state').append(new Option("SELECT", "0"));
                $('#ddl_tra_to_state').append(new Option("DELHI", "490506"));
            } else {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    },
                    url: "<?php echo base_url('Common/Ajaxcalls/get_states_list'); ?>",
                    success: function(data) {
                        $("#ddl_tra_to_case_type").html('');
                        $('#ddl_tra_to_state').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });
            }
        });

        $('#ddl_ref_court').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var court_type = $("#ddl_ref_court").val();
            if (court_type == 4) {
                $("#ddl_ref_state").html('');
                $('#ddl_ref_state').append(new Option("SELECT", "0"));
                $('#ddl_ref_state').append(new Option("DELHI", "490506"));
            } else {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    },
                    url: "<?php echo base_url('Common/Ajaxcalls/get_states_list'); ?>",
                    success: function(data) {
                        $("#ddl_ref_case_type").html('');
                        $('#ddl_ref_state').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });
            }

        });

        $('#ddl_relied_court').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var court_type = $("#ddl_relied_court").val();
            if (court_type == 4) {
                $("#ddl_relied_state").html('');
                $('#ddl_relied_state').append(new Option("SELECT", "0"));
                $('#ddl_relied_state').append(new Option("DELHI", "490506"));

            } else {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    },
                    url: "<?php echo base_url('Common/Ajaxcalls/get_states_list'); ?>",
                    success: function(data) {
                        $("#ddl_relied_case_type").html('');
                        $('#ddl_relied_state').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });
            }
        });


        $('#ddl_vch_state').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var ddl_vch_state = $('#ddl_vch_state').val();

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_agency: ddl_vch_state,
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_rto_code'); ?>",
                success: function(data) {
                    $('#rto_code').html(data);
                    updateCSRFToken();

                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });

        $('#district_idd').change(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            var state_agency = $('#state_agency_d').val();
            var district_id = $('#district_idd').val();

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    state_agency: state_agency,
                    district_id: district_id
                },
                url: "<?php echo base_url('Common/Ajaxcalls/get_police_station_list'); ?>",
                success: function(data) {
                    $('#m_policestn').html(data);
                    updateCSRFToken();

                },
                error: function() {
                    updateCSRFToken();
                }
            });
        });

        function convertToDate(ddmmyyyy) {
            const [day, month, year] = ddmmyyyy.split("-").map(Number);
            return new Date(year, month - 1, day); // Month is 0-indexed
        }

        $('#addButton,#updateButton').click(function() {
            var earlier_insert = jQuery("#earlier_insert");
            var court_type = $("input[name=radio_selected_court]:checked").val();
            var state_agency_h = jQuery("#state_agency_h");
            var h_bench_id = jQuery("#h_bench_id");
            var case_type = jQuery("#case_type");
            var lc_case_no_from = jQuery("#lc_case_no_from");
            var impugned_date_1 = jQuery("#impugned_date_1");
            var judge_name = jQuery("#judge_name");
            var state_agency = jQuery("#state_agency");
            var district_id = jQuery("#district_id");
            var impugned_date_s = jQuery("#impugned_date_s");
            var state_agency_d = jQuery("#state_agency_d");
            var district_idd = jQuery("#district_idd");
            var case_type_d = jQuery("#case_type_d");
            var lc_case_no_d = jQuery("#lc_case_no_d");
            var impugned_date_5 = jQuery("#impugned_date_5");
            var judge_name_3 = jQuery("#judge_name_3");
            var state_agency_s = jQuery("#state_agency_s");
            var district_ids = jQuery("#district_ids");
            var impugned_date_2 = jQuery("#impugned_date_2");
            var judge_name_5 = jQuery("#judge_name_5");
            var lc_case_no = jQuery("#lc_case_no");
            var lc_case_no_to = jQuery("#lc_case_no_to");

            var ddl_vch_state = jQuery("#ddl_vch_state").val();

            var rto_code = jQuery("#rto_code");
            var lc_vehicle_no = jQuery("#lc_vehicle_no");

            var ddl_ref_court = jQuery("#ddl_ref_court").val();
            var ddl_ref_state = jQuery("#ddl_ref_state");
            var ddl_ref_district = jQuery("#ddl_ref_district");
            var ddl_ref_case_type = jQuery("#ddl_ref_case_type");
            var txt_ref_caseno = jQuery("#txt_ref_caseno");

            var ddl_gov_not_state = jQuery("#ddl_gov_not_state").val();
            var txt_gov_not_no = jQuery("#txt_gov_not_no");
            var txt_g_n_no = jQuery("#txt_g_n_no");
            var ddl_g_n_y = jQuery("#ddl_g_n_y");
            var government_notification_date = jQuery("#government_notification_date");

            var ddl_relied_court = jQuery("#ddl_relied_court").val();
            var ddl_relied_state = jQuery("#ddl_relied_state");
            var ddl_relied_district = jQuery("#ddl_relied_district");
            var ddl_relied_case_type = jQuery("#ddl_relied_case_type");
            var txt_relied_caseno = jQuery("#txt_relied_caseno");

            var ddl_transfer_to = jQuery("#ddl_transfer_to").val();
            var ddl_tra_to_state = jQuery("#ddl_tra_to_state");
            var ddl_tra_to_district = jQuery("#ddl_tra_to_district");
            var ddl_tra_to_case_type = jQuery("#ddl_tra_to_case_type");
            var txt_tra_to_caseno = jQuery("#txt_tra_to_caseno");

            if (court_type == "4") {
                date_impug1 = impugned_date_s.val();
                //date_impug = new Date(impugned_date_s.val());
                lc_caseyear = jQuery("#lc_case_year").val();
            } else if (court_type == "1") {
                date_impug1 = impugned_date_1.val();
                //date_impug = new Date(impugned_date_1.val());
                lc_caseyear = jQuery("#lc_case_year").val();
            } else if (court_type == "3") {
                date_impug1 = impugned_date_5.val();
                //date_impug = new Date(impugned_date_5.val());
                lc_caseyear = jQuery("#lc_case_year_2").val();
            } else if (court_type == "5") {
                date_impug1 = impugned_date_2.val();
                //date_impug = new Date(impugned_date_2.val());
                lc_caseyear = jQuery("#lc_case_year").val();
            }

            var diary_recieved_date = jQuery("#diary_recieved_date").val();
            diary_date = new Date(diary_recieved_date);
            date_impug = convertToDate(date_impug1);
            let ig_year = date_impug.getFullYear();
 

            if (court_type == "4" && impugned_date_s.val().length == 0) {
                alert("Please enter Date of Impugned Judgement");
                impugned_date_s.focus();
                return false;
            } else if (court_type == "4" && case_type.val().length == 0) {
                alert("Please select case type.");
                case_type.focus();
                return false;
            } else if (court_type == "4" && lc_case_no_from.val().length == 0) {
                alert("Please enter case no.");
                lc_case_no_from.focus();
                return false;
            } else if (court_type == "4" && lc_case_no.val().length != 0 && (lc_case_no.val() < lc_case_no_from.val())) {
                alert("Please enter Case No. greater than from Case no.");
                lc_case_no.focus();
                return false;
            } else if (court_type == "1" && state_agency_h.val() == 0) {
                alert("Please select State");
                state_agency_h.focus();
                return false;
            } else if (court_type == "1" && h_bench_id.val() == 0) {
                alert("Please select Bench");
                h_bench_id.focus();
                return false;
            } else if (court_type == "1" && case_type.val().length == 0) {
                alert("Please select case type.");
                case_type.focus();
                return false;
            } else if (court_type == "1" && impugned_date_1.val().length == 0) {
                alert("Please enter Date of Impugned Judgement");
                impugned_date_1.focus();
                return false;
            } else if (court_type == "1" && case_type.val() == 0) {
                alert("Please select Case Type");
                case_type.focus();
                return false;
            } else if (court_type == "1" && lc_case_no_from.val().length == 0) {
                alert("Please enter Case Number");
                lc_case_no_from.focus();
                return false;
            } else if (court_type == "1" && lc_case_no.val().length != 0 && (lc_case_no.val() < lc_case_no_from.val())) {
                alert("Please enter Case No. greater than from Case no.");
                lc_case_no.focus();
                return false;
            } else if (court_type == "3" && state_agency_d.val() == 0) {
                alert("Please select State");
                state_agency_d.focus();
                return false;
            } else if (court_type == "3" && district_idd.val() == 0) {
                alert("Please select District");
                district_idd.focus();
                return false;
            } else if (court_type == "3" && case_type_d.val() == 0) {
                alert("Please select Case Type");
                case_type_d.focus();
                return false;
            } else if (court_type == "3" && lc_case_no_d.val().length == 0) {
                alert("Please enter Case Number");
                lc_case_no_d.focus();
                return false;
            } else if (court_type == "3" && impugned_date_5.val().length == 0) {
                alert("Please enter Date of Impugned Judgement");
                impugned_date_5.focus();
                return false;
            } else if (court_type == "3" && lc_case_no_to.val().length != 0 && (lc_case_no_to.val() < lc_case_no_d.val())) {
                alert("Please enter Case No. greater than from Case no.");
                lc_case_no_to.focus();
                return false;
            } else if (court_type == "5" && state_agency_s.val() == 0) {
                alert("Please select State");
                state_agency_s.focus();
                return false;
            } else if (court_type == "5" && district_ids.val() == 0) {
                alert("Please select Bench");
                district_ids.focus();
                return false;
            } else if (court_type == "5" && impugned_date_2.val().length == 0) {
                alert("Please enter Date of Impugned Judgement");
                impugned_date_2.focus();
                return false;
            } else if (court_type == "5" && case_type.val() == 0) {
                alert("Please select Case Type");
                case_type.focus();
                return false;
            } else if (court_type == "5" && lc_case_no_from.val().length == 0) {
                alert("Please enter Case Number");
                lc_case_no_from.focus();
                return false;
            } else if (court_type == "5" && lc_case_no.val().length != 0 && (lc_case_no.val() < lc_case_no_from.val())) {
                alert("Please enter Case No. greater than from Case no.");
                lc_case_no.focus();
                return false;
            } else if (date_impug > diary_date) {
                alert("Order Date should be less than filing Date");
                return false;
            } else if (lc_caseyear > ig_year) {
                alert("Case year cannot be greater than Date of Impugned Judgement");
                return false;
            } else if (ddl_vch_state != 0 && rto_code.val().length == 0) {
                alert("Please enter vehicle rto code");
                rto_code.focus();
                return false;
            } else if (ddl_vch_state != 0 && lc_vehicle_no.val().length == 0) {
                alert("Please enter vehicle number");
                lc_vehicle_no.focus();
                return false;
            } else if (ddl_ref_court != 0 && ddl_ref_state.val() == 0) {
                alert("Please select Reference State");
                ddl_ref_state.focus();
                return false;
            } else if (ddl_ref_court != 0 && ddl_ref_district.val().length == 0) {
                alert("Please select Reference District");
                ddl_ref_district.focus();
                return false;
            } else if (ddl_ref_court != 0 && ddl_ref_case_type.val() == 0) {
                alert("Please select Reference Case Type");
                ddl_ref_case_type.focus();
                return false;
            } else if (ddl_ref_court != 0 && txt_ref_caseno.val().length == 0) {
                alert("Please enter Reference Case No");
                txt_ref_caseno.focus();
                return false;
            } else if (ddl_relied_court != 0 && ddl_relied_state.val() == 0) {
                alert("Please select Relied Upon State");
                ddl_relied_state.focus();
                return false;
            } else if (ddl_relied_court != 0 && ddl_relied_district.val().length == 0) {
                alert("Please select Relied Upon District");
                ddl_relied_district.focus();
                return false;
            } else if (ddl_relied_court != 0 && ddl_relied_case_type.val().length == 0) {
                alert("Please select Relied Upon Case Type");
                ddl_relied_case_type.focus();
                return false;
            } else if (ddl_relied_court != 0 && txt_relied_caseno.val().length == 0) {
                alert("Please select Relied Upon Case No");
                txt_relied_caseno.focus();
                return false;
            } else if (ddl_gov_not_state != 0 && txt_gov_not_no.val().length == 0) {
                alert("Please enter Government Notification Type");
                txt_gov_not_no.focus();
                return false;
            } else if (ddl_gov_not_state != 0 && txt_g_n_no.val().length == 0) {
                alert("Please enter Government Notification No.");
                txt_g_n_no.focus();
                return false;
            } else if (ddl_gov_not_state != 0 && government_notification_date.val().length == 0) {
                alert("Please enter Government Notification Date");
                government_notification_date.focus();
                return false;
            } else if (ddl_transfer_to != 0 && ddl_tra_to_state.val() == 0) {
                alert("Please select Transfer To State");
                ddl_tra_to_state.focus();
                return false;
            } else if (ddl_transfer_to != 0 && ddl_tra_to_district.val().length == 0) {
                alert("Please select Transfer To District");
                ddl_tra_to_district.focus();
                return false;
            } else if (ddl_transfer_to != 0 && ddl_tra_to_case_type.val().length == 0) {
                alert("Please select Transfer To Case Type");
                ddl_tra_to_case_type.focus();
                return false;
            } else if (ddl_transfer_to != 0 && txt_tra_to_caseno.val().length == 0) {
                alert("Please enter Transfer To Case No");
                txt_tra_to_caseno.focus();
                return false;
            } else {
                var earlier_insert = jQuery("#earlier_insert");
                jQuery.post("<?php echo base_url('Filing/Earlier_court/insertEarlierCourt/'); ?>", {
                        post_data: earlier_insert.serialize(),
                    })
                    .done(function(data) {
                        alert(data);
                        updateCSRFToken();
                    });
                updateCSRFToken();
            }

        });

        // $('#updateButton').click(function() {
        //     var earlier_insert = jQuery("#earlier_insert");
        //     jQuery.post("<?php echo base_url('Filing/Earlier_court/insertEarlierCourt'); ?>", {
        //             post_data: earlier_insert.serialize(),
        //         })
        //         .done(function(data) {
        //             alert(data);
        //             updateCSRFToken();
        //         });
        //     updateCSRFToken();
        // });

        // $('#updateButton').click(function() {
        //     var CSRF_TOKEN = 'CSRF_TOKEN';
        //     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        //     var earlier_insert = jQuery("#earlier_insert");

        //     $.ajax({
        //         type: "POST",
        //         data: {
        //             CSRF_TOKEN: CSRF_TOKEN_VALUE,
        //             post_data: earlier_insert.serialize(),
        //         },
        //         url: "<?php echo base_url('Filing/Earlier_court/updaEarliercourt'); ?>",
        //         success: function(data) {
        //          alert(data);
        //             updateCSRFToken();

        //         },
        //         error: function() {
        //             updateCSRFToken();
        //         }
        //     });
        // });    

    });
</script>
<script>
    $(function() {
        $("#lowercourtdata").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "buttons": ["copy", "csv", "excel", "print"]
        }).buttons().container().appendTo('#lowercourtdata_wrapper .col-md-6:eq(0)');

    });
</script>

<script>
    function deleteLowerCourt(id) {
        var confirm_record = confirm("Are you sure you want to delete record");
        if (confirm_record == true) {
            var lower_court_id = $('#lc_id_' + id).val();
            var lc_cno = $('#lc_cno_' + id).val();
            var lc_cyear = $('#lc_cy_' + id).val();
            var lc_oc = $('#lc_oc_' + id).val();
            var lc_ct = $('#lc_ct_' + id).val();

            var d_no = '<?php echo $_SESSION['filing_details']['diary_no']; ?>';
            var d_ct_id = '<?php echo $_SESSION['filing_details']['casetype_id']; ?>';
            var court_type = $('#lc_ct_code_' + id).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    lc_cno: lc_cno,
                    lc_cyear: lc_cyear,
                    lc_oc: lc_oc,
                    d_no: d_no,
                    lower_court_id: lower_court_id,
                    lc_ct: lc_ct,
                    d_ct_id: d_ct_id,
                    court_type: court_type
                },
                url: "<?php echo base_url('Filing/Earlier_court/deleteEarliercourt'); ?>",
                success: function(data) {

                    updateCSRFToken();
                    if ((data == 'Y')) {
                        alert("Earlier Court Details Deleted Successfully.");
                    } else {
                        alert(data);
                    }

                    location.reload();
                },
                error: function() {
                    updateCSRFToken();
                }
            });


        }
    }

    function editLowerCourtDetails(id) {
        var lower_court_id = $('#lc_id_' + id).val();
        var lc_cno = $('#lc_cno_' + id).val();
        var lc_cyear = $('#lc_cy_' + id).val();
        var lc_oc = $('#lc_oc_' + id).val();

        var d_no = '<?php echo $_SESSION['filing_details']['diary_no']; ?>';

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                lc_cno: lc_cno,
                lc_cyear: lc_cyear,
                lc_oc: lc_oc,
                d_no: d_no,
                lower_court_id: lower_court_id
            },
            url: "<?php echo base_url('Filing/Earlier_court/checkforUpdateEarliercourt'); ?>",
            success: function(data) {

                updateCSRFToken();
                if ((data == 'Y')) {
                    //alert("Ready for updation");
                    url_redirect = '<?php echo base_url('Filing/Earlier_court/index/') ?>/' + lower_court_id;
                    // alert(url_redirect);
                    location.href = url_redirect;
                } else {
                    alert(data);
                }

                // location.reload();
            },
            error: function() {
                updateCSRFToken();
            }
        });



    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }

    <?php if (session()->getFlashdata('error')) { ?>
        alert('<?= session()->getFlashdata('error') ?>');
    <?php } ?>
    <?php if (session()->getFlashdata('message_error')) { ?>
        alert('<?= session()->getFlashdata('message_error') ?>');

    <?php } ?>
    <?php if (session()->getFlashdata('success_msg')) : ?>
        alert('<?= session()->getFlashdata('success_msg') ?>');
    <?php endif; ?>
</script>