<?= $this->extend('header') ?>
<?= $this->section('content') ?>
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
    /* label{
        font-size: 1rem;
    line-height: .7rem;
    font-weight: bold;
    margin-bottom: 15px;
    margin-top: 15px;
    } */
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card p-4">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Earlier Court</h3>
                            </div>
                             <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm">
                                <table class='table table-sm table-hover '>
                                    <tr>
                                        <th class='bg-light'><b>Diary Number</b></th>
                                        <th>sdfds</th>
                                    </tr>
                                    <tr>
                                        <th class='bg-light'><b>Cause Title</b></th>
                                        <th>dsfd</th>
                                    </tr>
                                    <tr>
                                        <th class='bg-light'><b>Case Status </b></th>
                                        <th>sdf</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php  //echo $_SESSION["captcha"];
                                    $attribute = array('name' => 'earlier_insert', 'id' => 'diary_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('Filing/Earliercourt/earliercourt/'), $attribute);
                                    ?>
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                    <div class="form-group row ">
                                        
                                       
                                        <div class="col-sm-12">
										<label for="inputEmail3" class="col-form-label">Select Court<span style="color: red">*</span>: </label>
                                            <?php
                                            $party_details = array();
                                            $caseData = array();
                                            $subordinate_court_details = array();
                                            $noHcEntry = '';
                                            $noHCButton = '';
                                            $scchecked = '';
                                            $hcchecked = '';
                                            $dcchecked = '';
                                            $ochecked = '';
                                            $sachecked = '';

                                            $court_type = !empty($caseData['court_type']) ? $caseData['court_type'] : NULL;
                                            $state_id = !empty($caseData['state_id']) ? $caseData['state_id'] : NULL;
                                            $district_id = !empty($caseData['district_id']) ? $caseData['district_id'] : NULL;
                                            $estab_code = !empty($caseData['estab_code']) ? $caseData['estab_code'] : NULL;
                                            $estab_id = !empty($caseData['estab_id']) ? $caseData['estab_id'] : NULL;

                                            //echo '<pre>'; print_r($caseData['court_type']); exit;
                                            ?>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="radio_selected_court1" name="radio_selected_court" onchange="display_form(this.value)" value="4" maxlength="2" <?php echo $scchecked; ?>>
                                                <label for="radio_selected_court1" class="custom-control-label">Supreme Court</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="radio_selected_court2" name="radio_selected_court" onchange="display_form(this.value)" value="1" maxlength="2" <?php echo $hcchecked; ?>>
                                                <label for="radio_selected_court2" class="custom-control-label">High Court</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="radio_selected_court3" name="radio_selected_court" onchange="display_form(this.value)" value="3" maxlength="2" <?php echo $dcchecked; ?>>
                                                <label for="radio_selected_court3" class="custom-control-label">District Court</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="radio_selected_court4" name="radio_selected_court" onchange="display_form(this.value)" value="2" maxlength="2" <?php echo $ochecked; ?>>
                                                <label for="radio_selected_court4" class="custom-control-label">Other Court</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="radio_selected_court5" name="radio_selected_court" onchange="display_form(this.value)" value="5" maxlength="2" <?php echo $sachecked; ?>>
                                                <label for="radio_selected_court5" class="custom-control-label">State Agency/Tribunal</label>
                                            </div>


                                        </div>
                                    </div>
                                    <?php form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="department_id_4" style="display:none;">
                        <div class="row">
                            <div class="col-md-8">
                                <label> Supreme Court :</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="state_agency" id="state_agency" class="custom-select rounded-0" disabled>
                                            <option value="490506">DELHI</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="district_id" id="district_id" class="custom-select rounded-0" disabled>
                                            <option value="10000">DELHI</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

                                <!-- <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control"> -->
                            </div>

                        </div>

                    </div>
                    <div id="department_id_1" style="display:none;">
                        <div class="row">
                            <div class="col-md-8">
                                <label> High Court :</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="state_agency" id="state_agency" class="custom-select rounded-0" disabled>
                                            <option value="490506">DELHI</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="district_id" id="district_id" class="custom-select rounded-0" disabled>
                                            <option value="10000">DELHI</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :</label>
                                <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div id="department_id_5" style="display:none;">
                        <div class="row">
                            <div class="col-md-8">
                                <label> State Agency :</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select name="state_agency" id="state_agency" class="custom-select rounded-0" disabled>
                                            <option value="490506">DELHI</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="district_id" id="district_id" class="custom-select rounded-0" disabled>
                                            <option value="10000">DELHI</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. :</label>
                                <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div id="department_id_shs" style="display:none;">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Case Type:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <select name="case_type" id="case_type" class="custom-select rounded-0">
                                    <option value="0">Select</option>
                                    <option value="3" title="CIVIL APPEAL">AC</option>
                                    <option value="4" title="CRIMINAL APPEAL">AR</option>
                                    <option value="15" title="WRIT TO PETITION (CIVIL)...">BC</option>
                                    <option value="16" title="WRIT TO PETITION (CRIMINAL)...">BR</option>
                                    <option value="19" title="CONTEMPT PETITION (CIVIL)">CC</option>
                                    <option value="20" title="CONTEMPT PETITION (CRIMINAL)">CR</option>
                                    <option value="18" title="DEATH REFERENCE CASE">DR</option>
                                    <option value="31" title="DIARYNO AND DIARYYR">DY</option>
                                    <option value="23" title="ELECTION PETITION (CIVIL)">EC</option>
                                    <option value="24" title="ARBITRATION PETITION">FC</option>
                                    <option value="40" title="SUO MOTO TRANSFER PETITION(CIVIL)">GC</option>
                                    <option value="33" title="SUO MOTO WRIT PETITION(CRIMINAL)">GC</option>
                                    <option value="32" title="SUO MOTO WRIT PETITION(CIVIL)">GC</option>
                                    <option value="41" title="SUO MOTO TRANSFER PETITION(CRIMINAL)">GR</option>
                                    <option value="34" title="SUO MOTO CONTEMPT PETITION(CIVIL)">GR</option>
                                    <option value="35" title="SUO MOTO CONTEMPT PETITION(CRIMINAL)">HC</option>
                                    <option value="36" title="REF. U/S 143">HR</option>
                                    <option value="22" title="SPECIAL REFERENCE CASE">LC</option>
                                    <option value="39" title="MISCELLANEOUS APPLICATION">MA</option>
                                    <option value="28" title="MOTION(CRL)">MR</option>
                                    <option value="11" title="TRANSFERRED CASE (CIVIL)">NC</option>
                                    <option value="12" title="TRANSFERRED CASE (CRIMINAL)">NR</option>
                                    <option value="17" title="ORIGINAL SUIT">OC</option>
                                    <option value="13" title="SPECIAL LEAVE TO PETITION (CIVIL)...">PC</option>
                                    <option value="14" title="SPECIAL LEAVE TO PETITION (CRIMINAL)...">PR</option>
                                    <option value="25" title="CURATIVE PETITION(CIVIL)">QC</option>
                                    <option value="26" title="CURATIVE PETITION(CRL)">QR</option>
                                    <option value="9" title="REVIEW PETITION (CIVIL)">RC</option>
                                    <option value="10" title="REVIEW PETITION (CRIMINAL)">RR</option>
                                    <option value="1" title="SPECIAL LEAVE PETITION (CIVIL)">SC</option>
                                    <option value="2" title="SPECIAL LEAVE PETITION (CRIMINAL)">SR</option>
                                    <option value="7" title="TRANSFER PETITION (CIVIL)">TC</option>
                                    <option value="8" title="TRANSFER PETITION (CRIMINAL)">TR</option>
                                    <option value="37" title="REF. U/S 14 RTI">UC</option>
                                    <option value="9999" title="Unknown">uu</option>
                                    <option value="38" title="REF. U/S 17 RTI">VC</option>
                                    <option value="5" title="WRIT PETITION (CIVIL)">WC</option>
                                    <option value="6" title="WRIT PETITION(CRIMINAL)">WR</option>
                                    <option value="21" title="TAX REFERENCE CASE">XC</option>
                                    <option value="27" title="REF. U/A 317(1)">YC</option>
                                    <option value="50" title="WRIT NOTIFICATION NO.">WNN</option>
                                    <option value="51" title="ARBITRATION REFERENCE NO.">ARN</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="lc_case_no" name="lc_case_no" class="form-control" placeholder="Enter Case Number">
                            </div>-
                            <div class="col-md-2">
                                <input type="text" id="lc_case_no" name="lc_case_no" class="form-control" placeholder="Enter Case Number">
                            </div>
                            <div class="col-md-3">
                                <?php $year = 1930;
                                $current_year = date('Y');
                                ?>
                                <select name="lc_case_year" id="lc_case_year" class="custom-select rounded-0">
                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                        <option><?php echo $x; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label> Description (Reference at point no. 1) :</label>
                                <input type="text" class="form-control" id="order_description" name="order_description" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label> Subject/Law (Reference at point no. 1) :</label>
                                <input type="text" class="form-control" id="lc_subject_law" name="lc_subject_law" class="form-control">
                            </div>
                            <div id="department_state" class="col-md-4">
                                <label> Judge/Registrar/Member :</label>
                                <input type="text" class="form-control" id="judge_name1" name="judge_name1" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="department_id_2" style="display:none;">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Impugned Order/ Award/ Notification/ Circular etc. passed by:(Authority)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <select name="order_passed_by" id="order_passed_by" onChange="get_authority(this.value);" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="D1">State Department</option>
                                    <option value="D2">Central Department</option>
                                    <option value="D3">Other Organisation</option>
                                    <option value="X">Xtra</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="x_authdesc" name="x_authdesc" maxlength="100" size="25" disabled="disabled" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <select name="auth_description" id="auth_description" class="custom-select rounded-0">
                                    <option value="0">Select</option>
                                </select>
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
                                <input type="text" size="5" name="auth_orgcode" id="auth_orgcode" maxlength="5" onKeyPress="set_m_orgname(this.value);" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="x_authdesc" name="x_authdesc" maxlength="100" size="25" disabled="disabled" class="form-control">
                            </div>
                            <div class="col-md-4">


                                <input type="text" size="40" name="impugned_order_no" maxlength="50" value="" id="impugned_order_no" class="form-control">


                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label> Date of Impugned Judgement/ Order/ Award/ Notification/ Circular etc. ::</label>
                                <input type="date" size="40" name="impugned_date" maxlength="50" value="" id="impugned_date" class="form-control">

                            </div>
                            <div class="col-md-4">
                                <label>Brief Desc.of IMPUGNED Order/Judgement/Award/Notification etc:</label>
                                <input type="text" class="form-control" id="order_description" name="order_description" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label> Subject/Law involved in the IMPUGNED Order/Judgment/Award/Notification:</label>
                                <input type="text" class="form-control" id="lc_subject_law" name="lc_subject_law" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row" id="judment_challenged" style="display:none;">
                        <div class="col-md-4">
                            <label> Judgement Challanged </label>
                            <div class="icheck-primary">
                                <input type="checkbox" name="chk_judge_challenged" id="chk_judge_challenged" checked>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label> Judgement Type </label>
                            <select name="lc_judgment_type" id="lc_judgment_type" class="custom-select rounded-0">
                                <option value="">Select</option>
                                <option value="F">Final</option>
                                <option value="I">Interim</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label> Judgement Covered in:</label>
                            <input type="text" class="form-control" id="lc_judgement_covered_in" name="lc_judgement_covered_in" class="form-control">
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
                                <select name="lc_vehicle_state" id="lc_vehicle_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="lc_vehicle_state" id="lc_vehicle_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="lc_vehicle_no" name="lc_vehicle_no" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div id="from_court" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <label> Reference No. from court </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <select name="lc_reference_court" id="lc_reference_court" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="lc_vehicle_no" name="lc_vehicle_no" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label> Relied Upon from court </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <select name="lc_reference_court" id="lc_reference_court" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="lc_vehicle_no" name="lc_vehicle_no" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <select name="lc_reference_state" id="lc_reference_state" class="custom-select rounded-0">
                                    <option value="">Select</option>
                                    <option value="571222">ANDAMAN &amp; NICOBAR ISLAND </option>
                                    <option value="541950">ANDHRA PRADESH </option>
                                    <option value="537722">ARUNACHAL PRADESH </option>
                                    <option value="599089">TELANGANA</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <!-- Data List -->
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    //     $(document).on("change", "#ddl_vch_state", function () {
    //     get_ddl_vch_state();
    //   });

    function display_form(department_id) {
        alert(department_id);
        $('#department_id_' + department_id).css('display', 'inline');
        $('#judment_challenged').css('display', 'inline-flex');
        $('#vehicle_number').css('display', 'inline');
        $('#from_court').css('display', 'inline');
        for (let i = 1; i <= 5; i++) {
            if (i != department_id) {
                if (department_id == 1 || department_id == 4 || department_id == 5) {
                    $('#department_id_shs').css('display', 'inline');

                }
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
        }
    }
    $(document).ready(function() {

    $('#reservationdate').datetimepicker({
        format: 'L'
    });
});
</script>
<?= $this->endSection() ?>