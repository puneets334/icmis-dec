<?=view('header'); ?>
 
    <style>
        .custom-radio{float: left; display: inline-block; margin-left: 10px; }
        .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
        .basic_heading{text-align: center;color: #31B0D5}
        .btn-sm {
            padding: 0px 8px;
            font-size: 14px;
        }
        .card-header {
            padding: 5px;
        }
        h4 {
            line-height: 0px;
        }
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>

    <!--<section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Filing</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>-->

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

                                 <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?/*=view('Filing/filing_breadcrumb'); */?>
                        <!-- /.card-header -->
                        <!-- <div class="row">
                             <div class="col-sm-10"></div>
                             <div class="col-sm-2">
                                 <div class="custom_action_menu">
                                     <button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                     <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pen	" aria-hidden="true"></i></button>
                                     <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                 </div>
                             </div>
                         </div>-->
                        <span class="alert alert-error" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </span>
                        <!-- <span class="form-response"> </span>-->
                        <!--<div class="alert alert-danger text-white" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </div>-->
                        <div id="dv_content1"   >
                            <input type="hidden" id="fil_hd"/>
                            <div id="show_fil">
                                <?php if($role==""){?>
                                    <font style="color: red;font-size: 16px; text-align: center"> <b><?php echo "Diary Role is not assigned. Only MA/Review/Curative/Contempt Petition can be filed."; ?></b></font></br/></br/>
                                <?php }?>
                                <?php
                                $attribute = array('class' => 'form-horizontal diary_generation_form', 'name' => 'diary_generation_form', 'id' => 'diary_generation_form', 'autocomplete' => 'off');
                                echo form_open('#', $attribute);

                                ?>

                                <!--step form start-->
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active stepperPosition_1" href="#diary_generation_tab_panel" data-toggle="tab">Basic Details</a></li>
                                        <li class="nav-item"><a class="nav-link stepperPosition_2" href="#petitioner_tab_panel" data-toggle="tab">Petitioner</a></li>
                                        <li class="nav-item"><a class="nav-link stepperPosition_3" href="#respondent_tab_panel" data-toggle="tab">Respondent</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="diary_generation_tab_panel">
                                            <h4 class="basic_heading"> Basic Details </h4>


                                            <div class="form-group row ">
                                                <div class="col-sm-2"></div>
                                                <label class="col-sm-1 col-form-label"> <!--Select Court <span class="text-red">*</span> :--></label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $party_details = array();
                                                    $caseData = array();
                                                    $subordinate_court_details = array();
                                                    $noHcEntry = '';
                                                    $noHCButton = '';
                                                    $scchecked = '';
                                                    $hcchecked = 'checked="checked"';
                                                    $dcchecked = '';
                                                    $ochecked = '';
                                                    $sachecked = '';


                                                    /*ddl_court $court_type = !empty($caseData['court_type']) ? $caseData['court_type'] : NULL;
                                                     $state_id = !empty($caseData['state_id']) ? $caseData['state_id'] : NULL;
                                                     $district_id = !empty($caseData['district_id']) ? $caseData['district_id'] : NULL;
                                                     $estab_code = !empty($caseData['estab_code']) ? $caseData['estab_code'] : NULL;
                                                     $estab_id = !empty($caseData['estab_id']) ? $caseData['estab_id'] : NULL;*/

                                                    $ddl_court_checked = '';
                                                    $court_type = !empty($diary_details['ddl_court']) ? $diary_details['ddl_court'] : 1;
                                                    $hc_value = !empty($diary_details['dacode']) ? $diary_details['dacode'] : NULL;
                                                    foreach ($court_type_list as $row) { ?>
                                                        <!--<div class="custom-control custom-radio">
                                                    <input class="custom-control-input ddl_court" type="radio" id="radio_selected_court<?/*=$row['id']*/?>" name="ddl_court" onchange="get_court_as(this.value)" value="<?/*=$row['id']*/?>" maxlength="1" <?/*=!empty($diary_details['from_court'])  && $diary_details['from_court']==$row['id'] ? 'checked="checked"' : ''; */?>>
                                                    <label for="radio_selected_court<?/*=$row['id']*/?>" class="custom-control-label"><?/*=$row['court_name'].$ddl_court_checked;*/?></label>
                                                </div>-->
                                                    <?php }?>

                                                </div>
                                            </div>

                                            <!--<div class="row ">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-8">
                                                    <label>CNR No</label>
                                                    <input type="text" class="form-control" id="cnr" name="cnr" maxlength="16" pattern="^[A-Z]{4}[0-9]{12}$" placeholder="Enter CNR">
                                                    OR
                                                </div>
                                                <div class="col-md-2"></div>
                                            </div>-->

                                            <div class="row ">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court Type<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_court" id="ddl_court" class="form-control">
                                                                <option value="">Select State</option>
                                                                <?php
                                                                foreach ($court_type_list as $row) {
                                                                    echo'<option value="'.$row['id'].'">'.$row['court_name'].'</option>';

                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">State <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
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
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court Bench <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_bench" id="ddl_bench" class="form-control">
                                                                <option value="" title="Select">Select High Court Bench</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div id="dv_case_no" style="text-align: center"></div>
                                                <div id="dv_parties"></div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Case Type <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
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
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Section <span class="text-red">*</span></label>
                                                        <div class="col-sm-7">
                                                            <select name="section" id="section"  class="form-control">
                                                                <option value="" title="Select">Select section</option>
                                                                <?php
                                                                foreach ($usersection as $row) {
                                                                    echo'<option value="' . sanitize(($row['id'])) . '">' . sanitize($row['section_name']) . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Special Type</label>
                                                        <div class="col-sm-7">
                                                            <select id="type_special" name="type_special" onchange="is_type_special_nature(this.value)" class="form-control">
                                                                <option value="" title="Select">Select</option>
                                                                <option value="1">None</option>
                                                                <option value="6">Jail Petition</option>
                                                                <option value="7">PUD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 sp_doc_signed" id="sp_doc_signed" style="display: none;">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Date of document signed by jailer <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control" name="txt_doc_signed" id="txt_doc_signed" size="9" maxlength="10" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-red">Extream Priority Category</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_priority" id="ddl_priority"  class="form-control">
                                                                <option value="0">None</option>
                                                                <?php
                                                                foreach ($ref_special_category_filing as $row) {
                                                                    echo'<option value="'.sanitize(($row['id'])).'">'.sanitize($row['category_name']).'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <!----  start code for  lowercourt information to be saved when ma,rp,curative,contempt petition is filed   ----->
                                            <div class="row" id="lct_casetype" style="display: none;">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <div class="col-sm-5">
                                                            <input type="radio" name ="sel" id="c" onclick="check(this.id)" checked="checked"><b>Case Type</b>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_nature_sci" id="ddl_nature_sci"  class="form-control">
                                                                <option value="">Select</option>
                                                                <?php
                                                                foreach ($casetype_nature_sci as $row) {
                                                                    echo'<option value="'.sanitize(($row['casecode'])).'">'.sanitize($row['casename']).'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" name="no" id="no" size="10" placeholder="Enter Case no.">
                                                        </div>
                                                        <div class="col-sm-4">

                                                            <?php $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y');
                                                            print '<select id="t_h_cyt" class="form-control">';?>
                                                            <option value=0>Year</option>
                                                            <?php
                                                            foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                                                print '<option value="'.$i.'"';
                                                                if(isset($_SESSION['session_diary_yr'])){
                                                                    if($i == $_SESSION['session_diary_yr']){ }
                                                                }
                                                                else{    if($i == date('Y')){  print 'selected="selected"'; }
                                                                }
                                                                print '>'.$i.'</option>';
                                                            } print '</select>'; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <b>OR   <input type="radio" name ="sel" id="d" onclick="check(this.id)"> Diary No. </b>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="diary_no" size="5" id="diary_no" class="form-control">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <?php   $currently_selected = date('Y'); $earliest_year = 1950; $latest_year = date('Y');
                                                            print '<select id="dyr" class="form-control">'; ?>
                                                            <option value=0>Year</option>
                                                            <?php foreach ( range( $latest_year, $earliest_year ) as $i ) {
                                                                print '<option value="'.$i.'"';
                                                                if(isset($_SESSION['session_diary_yr'])){
                                                                    if($i == $_SESSION['session_diary_yr']){ }
                                                                }
                                                                else{
                                                                    if($i == date('Y')){
                                                                        print 'selected="selected"';
                                                                    }
                                                                }
                                                                print '>'.$i.'</option>';
                                                            }
                                                            print '</select>'; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <!--<input type="button" value="Submit" onclick="f1()" id="sbtn" />-->
                                                    <div class="btn btn-success" value="Search" onclick="f1()" id="sbtn" />Search</div>
                                            </div>

                                        </div>

                                        <!----         end of the Code for Saving Lower court information          -->
                                        <div id="dv_sc_parties"></div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Total No. of Pages in File</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" size="4" maxlength="4" id="case_doc" onkeypress="return onlynumbers(event)" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="total pages">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--If SCLSC-->
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">IF SCLSC</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                              <input type="checkbox" name="if_sclsc" id="if_sclsc" onchange="is_if_sclsc(this.value)" <?php if(!empty($diary_details) && $diary_details["if_sclsc"]==1){ echo "checked";}?>>
                                                            </span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txt_sclsc_no" id="txt_sclsc_no" size="4" onkeypress="return onlynumbers(event)" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?=!empty($sclsc) ? $sclsc['sclsc_diary_no'] : ''; ?>" placeholder="Enter No.">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 sp_no_yr" id="sp_no_yr" style="display: none">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">SCLSC Year <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_sclsc_yr" id="ddl_sclsc_yr" class="form-control select2">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $yr_sclsc=date('Y');
                                                            for ($index = $yr_sclsc; $index >=1930; $index--) {
                                                                ?>
                                                                <option value="<?php echo $index; ?>" <?php if(!empty($sclsc) && $sclsc['sclsc_diary_year']==$index) { ?> selected="selected" <?php } ?>><?php echo $index; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--If SCLSC end -->

                                        </div>
                                        <hr/>
                                        <!--start Advocate-->
                                        <h4 class="basic_heading"> Advocate Details </h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Main Pet. Adv. <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="padvt" name="padvt" onchange="changeAdvocate(this.id,this.value)" class="form-control wwselect2" >
                                                            <option value="A" selected="selected">AOR</option>
                                                            <option value="N">Non-AOR</option>
                                                            <option value="S">State</option>
                                                            <!--<option value="C">Central</option>-->
                                                            <option value="SS">Petitioner In Person</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padv_is_ac">
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="is_ac" name="is_ac">
                                                        <label for="is_ac"> Is Amicus Curiae</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4" id="padv_state">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Enrol State <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_pet_adv_state" id="ddl_pet_adv_state"  class="form-control" disabled="true">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($state as $row) {
                                                                if (isset($row['cmis_state_id'])){
                                                                    echo'<option value="' . ($row['cmis_state_id']) . '">' . strtoupper($row['state_name']) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvno_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">AOR code/Name:/Enrol No <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvno" size="25" onchange="getAdvocate_for_main(this.id,'P')"  placeholder="Enrol No.(Non-AOR)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvyr_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'P')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  disabled="true"/>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Name</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control"  placeholder="Enter name" id="padvname" size="30" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvmob_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mobile</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" placeholder="Enter mobile number" id="padvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvemail_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Email Id</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvemail" name="padvemail" size="30" disabled="true" placeholder="Enter email id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Advocate -->
                                        <hr/>
                                        <!--start Advocate respondent-->
                                        <h4 class="basic_heading"> Advocate Details </h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Main Res. Adv. <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="radvt" onchange="changeAdvocate(this.id,this.value)" class="form-control" >
                                                            <option value="A">AOR</option>
                                                            <option value="N">Non-AOR</option>
                                                            <option value="S">State</option>
                                                            <!--<option value="C">Central</option>-->
                                                            <option value="SS">Respondent In Person</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radv_is_ac">
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="ris_ac" name="ris_ac">
                                                        <label for="ris_ac"> Is Amicus Curiae</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4" id="radv_state">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Enrol State <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_res_adv_state" id="ddl_res_adv_state"  class="form-control" disabled="true">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($state as $row) {
                                                                if (isset($row['cmis_state_id'])){
                                                                    echo'<option value="' . ($row['cmis_state_id']) . '">' . strtoupper($row['state_name']) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvno_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">AOR code/Name:/Enrol No <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvno" size="25"  onchange="getAdvocate_for_main(this.id,'R')"  placeholder="Enrol No.(Non-AOR)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvyr_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'R')" onkeypress="return onlynumbers(event)" disabled="true"/>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Name</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control"  placeholder="Enter name" id="radvname" size="30" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvmob_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mobile</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" placeholder="Enter mobile number" id="radvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvemail_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Email Id</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvemail" name="radvemail" size="30" disabled="true" placeholder="Enter email id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Advocate respondent-->
                                        <center><div class="btn btn-primary" onclick="setStepper('2')">Next</div></center>
                                        </div>
                                        <!-- /.diary_generation_tab_panel -->


                                        <div class="tab-pane" id="petitioner_tab_panel">

                                            <h4 class="basic_heading"> Petitioner Information</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Party Type <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selpt" name="selpt" onchange="activate_main(this.id)" class="form-control">
                                                                <option value="I">Individual</option>
                                                                <option value="D1">State Department</option>
                                                                <option value="D2">Central Department</option>
                                                                <option value="D3">Other Organization</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <!--start party type individual for_I_p-->
                                            <div id="for_I_p">
                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="name" class="col-sm-5 col-form-label">Name <span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pet_name" name="pet_name" placeholder="Enter Name" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Relation" class="col-sm-5 col-form-label">Relation :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selprel" name="selprel" onchange="setSex(this.value,this.id)" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="S">Son of</option>
                                                                    <option value="D">Daughter of</option>
                                                                    <option value="W">Wife of</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="name" class="col-sm-5 col-form-label">Father/Husband :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="prel" name="prel" placeholder="Enter Father/Husband" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Relation" class="col-sm-5 col-form-label">Gender :</label>
                                                            <div class="col-sm-7">
                                                                <select id="psex" name="psex" class="form-control select2">
                                                                    <option value="">Select</option>
                                                                    <option value="N">N.A.</option>
                                                                    <option value="M">Male</option>
                                                                    <option value="F">Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="petitioner_age" class="col-sm-5 col-form-label">Age :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="page" name="page" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Occupation/Department :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pocc" name="pocc"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Occupation/Department">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--start individual petitioner address area-->


                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">Country <span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="p_conti" name="p_conti" onchange="setCountry_state_dis(this.id,this.value)" class="form-control">
                                                                    <option value="">Select Country</option>
                                                                    <?php
                                                                    foreach ($country as $row) {?>
                                                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id']=='96') echo "Selected"; ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="paddi" name="paddi" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text"  class="form-control" id="ppini" name="ppini" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pcityi" name="pcityi" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpsti" name="selpsti" onchange="getDistrict('P',this.id,this.value)" class="form-control select2 custom-select rounded-0" style="width: 100%;">
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    $stateArr = array();
                                                                    if (count($state_list)) {
                                                                        foreach ($state_list as $dataRes) { ?>
                                                                            <option <?php echo $sel; ?> value="<?= sanitize(trim($dataRes['cmis_state_id'])); ?>"><?=sanitize(strtoupper($dataRes['agency_state'])); ?> </option>
                                                                            <?php
                                                                            $tempArr = array();
                                                                            $tempArr['id'] = sanitize(trim($dataRes['cmis_state_id']));
                                                                            $tempArr['state_name'] = strtoupper(trim($dataRes['agency_state']));
                                                                            $stateArr[] = (object)$tempArr;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpdisi" name="selpdisi"  class="form-control select2 custom-select rounded-0" style="width: 100%;">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="petitioner_email" class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pmobi" name="pmobi" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="petitioner_email" class="col-sm-5 col-form-label">Email Id:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pemaili"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="p_noi" name="p_noi" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--end individual petitioner address area-->
                                            </div>
                                            <!--end party type individual for_I_p-->
















                                            <!--start party type department for_D_p-->




                                            <div id="for_D_p" style="display: none">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label" id='for_D_p_sn1'><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle1">State <span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" id="pet_causetitle1" checked>
                                                                </div></label>
                                                            <div class="col-sm-7" id='for_D_p_sn2'>
                                                                <input type="text" class="form-control" name="pet_statename" id="pet_statename" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State">
                                                                <input type="hidden" id="pet_statename_hd"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle2">Department <span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" name="pet_causetitle2" id="pet_causetitle2" checked="">
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pet_deptt" name="pet_deptt" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department">
                                                                <input type="hidden" id="pet_deptt_code"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle3">Post :</label>
                                                                    <input type="checkbox" name="pet_causetitle3" id="pet_causetitle3">
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" name="pet_post" id="pet_post" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Post">
                                                                <input type="hidden" id="pet_post_code"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!--start department petitioner address area-->

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">Country <span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="p_contd" name="p_contd" onchange="setCountry_state_dis(this.id,this.value)" class="form-control">
                                                                    <option value="">Select Country</option>
                                                                    <?php
                                                                    foreach ($country as $row) {?>
                                                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id']=='96') echo "Selected"; ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="paddd" name="paddd" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text"  class="form-control" id="ppind" name="ppind" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pcityd" name="pcityd" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpstd" name="selpstd" onchange="getDistrict('P',this.id,this.value)" class="form-control">
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    foreach ($state_list as $dataRes) { ?>
                                                                        <option <?php if($dataRes['cmis_state_id']==23) echo "selected";?> value="<?= sanitize(trim($dataRes['cmis_state_id'])); ?>"><?=sanitize(strtoupper($dataRes['agency_state'])); ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpdisd" name="selpdisd"  class="form-control">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="petitioner_email" class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pmobd" name="pmobd" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="petitioner_email" class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pemaild" name="pemaild" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="p_nod" size="3" maxlength="4"  onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--end department petitioner address area-->

                                            </div>
                                            <!--end party type department for_D_p-->
                                            <!--extra address Petitioner-->
                                            <div class="col-md-12 mt-5mb-3">
                                                <div style="text-align: center;font-weight: bold;cursor: pointer;float: right;" id="ad_address" class="cl_center cl_add_address btn btn-outline-success"><i class='fas fa-plus-circle'></i> Additional Address</div>
                                                <input type="hidden" name="hd_add_address" id="hd_add_address" value="0"/>
                                                <div id="dv_add_parties"></div>
                                            </div><br/><br/>
                                            <!--end extra Address Petitioner-->
                                            <center>
                                                <div class="btn btn-primary" onclick="setStepper('1')">Previous</div>
                                                <div class="btn btn-primary" onclick="setStepper('3')">Next</div>
                                            </center>
                                        </div>
                                        <!-- /.petitioner_tab_panel -->



                                        <div class="tab-pane" id="respondent_tab_panel">
                                            <h4 class="basic_heading"> Respondent Information </h4>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Party-Type" class="col-sm-5 col-form-label">Party Type<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selrt" name="selrt" onchange="activate_main(this.id)" class="form-control">
                                                                <option value="I">Individual</option>
                                                                <option value="D1">State Department</option>
                                                                <option value="D2">Central Department</option>
                                                                <option value="D3">Other Organization</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--start party type individual for_I_r-->
                                            <div  id="for_I_r">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Name<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_name" name="res_name" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Party-Type" class="col-sm-5 col-form-label">Relation :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrrel" name="selrrel" onchange="setSex(this.value,this.id)" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="S">Son of</option>
                                                                    <option value="D">Daughter of</option>
                                                                    <option value="W">Wife of</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Father/Husband :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rrel" name="rrel" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Father/Husband">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Party-Type" class="col-sm-5 col-form-label">Gender :</label>
                                                            <div class="col-sm-7">
                                                                <select id="rsex" name="rsex" class="form-control select2">
                                                                    <option value="">Select</option>
                                                                    <option value="N">N.A.</option>
                                                                    <option value="M">Male</option>
                                                                    <option value="F">Female</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Age :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rage" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Occupation/Department :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rocc" onblur="remove_apos(this.value,this.id)" placeholder="Enter Occupation/Department">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                                <!--start individual respondent address area-->
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">Country<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="r_conti" name="r_conti" class="form-control" onchange="setCountry_state_dis(this.id,this.value)">
                                                                    <option value="">Select Country</option>
                                                                    <?php
                                                                    foreach ($country as $row) {?>
                                                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id']=='96') echo "Selected"; ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="raddi" name="raddi" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rpini" name="rpini" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rcityi" name="rcityi" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrsti" name="selrsti" class="form-control" onchange="getDistrict('R',this.id,this.value)">
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    foreach ($state_list as $dataRes) { ?>
                                                                        <option <?php if($dataRes['cmis_state_id']==23) echo "selected";?> value="<?= sanitize(trim($dataRes['cmis_state_id'])); ?>"><?=sanitize(strtoupper($dataRes['agency_state'])); ?> </option>
                                                                    <?php }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrdisi" name="selrdisi" class="form-control">
                                                                    <option value="">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rmobi" name="rmobi" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Id:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="remaili" name="remaili"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="r_noi" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <!--end individual respondent address area-->


                                            </div>
                                            <!--end party type individual for_I_r-->






                                            <!--start department respondent for for_D_r-->
                                            <div id="for_D_r" style="display: none">
                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="State" class="col-sm-5 col-form-label" id='for_D_r_sn1'>
                                                                <div class="icheck-primary d-inline">
                                                                    <label for="res_causetitle1">State<span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" id="res_causetitle1" checked="">
                                                                </div></label>
                                                            <div class="col-sm-7" id='for_D_r_sn2'>
                                                                <input type="text" class="form-control" id="res_statename" name="res_statename" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State">
                                                                <input type="hidden" id="res_statename_hd"/>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="State" class="col-sm-5 col-form-label">
                                                                <div class="icheck-primary d-inline">
                                                                    <label for="res_causetitle2">Department<span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" id="res_causetitle2" name="res_causetitle2" checked="">
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_deptt" name="res_deptt" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department">
                                                                <input type="hidden" id="res_deptt_code"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="respondent_causetitle3" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                    <label for="res_causetitle3">Post</label>
                                                                    <input type="checkbox" name="res_causetitle3" id="res_causetitle3">
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_post" name="res_post" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Post">
                                                                <input type="hidden" id="res_post_code"/>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!--start department respondent address area-->

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">Country<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="r_contd" name="r_contd" class="form-control" onchange="setCountry_state_dis(this.id,this.value)">
                                                                    <option value="">Select Country</option>
                                                                    <?php
                                                                    foreach ($country as $row) {?>
                                                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id']=='96') echo "Selected"; ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="raddd" name="raddd" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rpind" name="rpind" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rcityd" name="rcityd" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrstd" style="width:204px;" onchange="getDistrict('R',this.id,this.value)" class="form-control">
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    foreach ($state_list as $dataRes) { ?>
                                                                        <option <?php if($dataRes['cmis_state_id']==23) echo "selected";?> value="<?= sanitize(trim($dataRes['cmis_state_id'])); ?>"><?=sanitize(strtoupper($dataRes['agency_state'])); ?> </option>
                                                                    <?php }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrdisd" name="selrdisd" class="form-control">
                                                                    <option value="">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rmobd" name="rmobd" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="remaild" name="remaild"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="r_nod" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <!--end department respondent address area-->

                                            </div>
                                            <!--end department respondent for for_D_r-->









                                            <!--start individual respondent address area-->

                                            <!--end individual respondent address area-->










                                            <!--extra address respondend-->
                                            <div class="col-md-12 mt-5 mb-3">
                                                <div style="text-align: center;font-weight: bold;cursor: pointer;display: none;" id="rsp_add_add" class="btn btn-outline-success">R.Add Additional Address</div>
                                            </div>

                                            <div class="col-md-12 mt-5mb-3">
                                                <div style="text-align: center;font-weight: bold;cursor: pointer;float: right;" id="ad_address_r" class="cl_center cl_add_address btn btn-outline-success"><i class='fas fa-plus-circle'></i> Additional Address</div>
                                                <input type="hidden" name="hd_add_address_r" id="hd_add_address_r" value="0"/>
                                                <div id="dv_add_parties_r"></div>
                                            </div><br/><br/>

                                            <!--end extra Address respondent-->
                                            <center>
                                                <div class="btn btn-primary" onclick="setStepper('2')">Previous</div>
                                                <input type="button" class="btn-successww btn btn-success" value="Save" onclick="call_save_main('0')" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()"/>
                                            </center>

                                        </div>
                                        <!-- /.respondent_tab_panel -->

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->


                            <?=form_close(); ?>
                            <center> </center>
                            <input type="hidden" name="hd_p_barid" id="hd_p_barid"/>
                            <input type="hidden" name="hd_r_barid" id="hd_r_barid"/>
                        </div> <!--end show_fil-->
                        <input type="hidden" name="hd_current_date" id="hd_current_date" value="<?php echo date('d-m-Y') ?>"/>
                    </div> <!--end dv_content1-->



                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <link href="<?php echo base_url('autocomplete/autocomplete.css');?>" rel="stylesheet">
    <!--<script src="<?php /*echo base_url('autocomplete/autocomplete.min.js'); */?>"></script>-->
    <script src="<?php echo base_url('autocomplete/autocomplete-ui.min.js'); ?>"></script>
    <script src="<?php echo base_url('filing/diary_add_filing.js'); ?>"></script>
    <script>

        function change_addi_state(p_r_type,id_count,value) {
            //alert('p_r_type='+p_r_type +' id_count='+id_count + 'value='+ value);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if (p_r_type=='P') {
                if (id_count != 0) {
                    $('.petitioner_addi_district_'+id_count).val('');
                } else {
                    $('#petitioner_addi_district').val('');
                }
            }else if (p_r_type=='R') {
                if (id_count != 0) {
                    $('.respondent_addi_district_'+id_count).val('');
                } else {
                    $('#respondent_addi_district').val('');
                }
            }else {return false;}

            var get_state_id = value;
            if (get_state_id !='') {
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                    url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
                    success: function (data) {
                        if (p_r_type=='P') {
                            if (id_count != 0) {
                                $('.petitioner_addi_district_' + id_count).html(data);
                            } else {
                                $('#petitioner_addi_district').html(data);
                            }
                        }else if (p_r_type=='R') {
                            if (id_count != 0) {
                                $('.respondent_addi_district_' + id_count).html(data);
                            } else {
                                $('#respondent_addi_district').html(data);
                            }
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        }
        $('.remove_in_row').click(function() {
            var v = $(this).val();
            $('.delete_out_row_'+v).click();
        });

        $('.multi-field-wrapper').each(function() {
            var $wrapper = $('.multi-fields', this);
            $(".add-field", $(this)).click(function(e) {
                var length_row=$('.multi-field', $wrapper).length;
                //alert('length_row='+length_row);
                var petitioner_addi_state='petitioner_addi_state_'+length_row;
                var petitioner_addi_district='petitioner_addi_district_'+length_row;

                var delete_out_row='delete_out_row_'+length_row;
                var delete_in_row='delete_in_row_'+length_row;
                $(".remove_out_row:first").val(length_row);
                $(".remove_in_row:first").val(length_row);


                $(".remove_out_row:first").addClass(delete_out_row);
                $(".remove_in_row:first").addClass(delete_in_row);
                $(".add-field:first").addClass("d-none");
                $(".remove-field:first").removeClass("d-none");

                $("#petitioner_addi_state:first").addClass(petitioner_addi_state);
                $("#petitioner_addi_district:first").addClass(petitioner_addi_district);
                var selectElement = document.getElementById('petitioner_addi_state');
                selectElement.setAttribute("onchange", "change_addi_state('P',"+length_row+", this.value)");
                $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('');
                $(".remove_out_row:first").removeClass(delete_out_row);
                $(".remove_in_row:first").removeClass(delete_in_row);

                $(".add-field:first").removeClass("d-none");
                $(".remove-field").removeClass("d-none");
                $(".remove-field:first").addClass("d-none");

                $("#petitioner_addi_state:first").removeClass(petitioner_addi_state);
                $("#petitioner_addi_district:first").removeClass(petitioner_addi_district);

                var selectElement = document.getElementById('petitioner_addi_state');
                selectElement.setAttribute("onchange", "change_addi_state('P',0, this.value)");
                //selectElement.removeAttribute("onchange", "change_addi_state("+length_row+", this.value)");
            });

            $('.multi-field .remove_out_row', $wrapper).click(function() {
                var length_row=$('.multi-field', $wrapper).length;
                // alert('length_row='+length_row);
                if ($('.multi-field', $wrapper).length > 1)
                    $(this).parent('.multi-field').remove();
            });
        });

    </script>
    <script>
        $('.remove_in_row_heading').click(function() {
            var v = $(this).val();
            $('.delete_out_row_heading'+v).click();
        });

        $('.multi-field-wrapper').each(function() {
            var $wrapper = $('.multi-fields', this);
            $(".add-field_heading", $(this)).click(function(e) {
                var length_row=$('.multi-field', $wrapper).length;
                //alert('length_row='+length_row);
                var respondent_addi_state='respondent_addi_state_'+length_row;
                var respondent_addi_district='respondent_addi_district_'+length_row;

                var delete_out_row='delete_out_row_heading'+length_row;
                var delete_in_row='delete_in_row_heading'+length_row;
                $(".remove_out_row_heading:first").val(length_row);
                $(".remove_in_row_heading:first").val(length_row);

                $(".remove_out_row_heading:first").addClass(delete_out_row);
                $(".remove_in_row_heading:first").addClass(delete_in_row);
                $(".add-field_heading:first").addClass("d-none");
                $(".remove-field_heading:first").removeClass("d-none");

                $("#respondent_addi_state:first").addClass(respondent_addi_state);
                $("#respondent_addi_district:first").addClass(respondent_addi_district);
                var selectElement = document.getElementById('respondent_addi_state');
                selectElement.setAttribute("onchange", "change_addi_state('R',"+length_row+", this.value)");

                $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('');
                $(".remove_out_row_heading:first").removeClass(delete_out_row);
                $(".remove_in_row_heading:first").removeClass(delete_in_row);

                $(".add-field_heading:first").removeClass("d-none");
                $(".remove-field_heading").removeClass("d-none");
                $(".remove-field_heading:first").addClass("d-none");

                $("#respondent_addi_state:first").removeClass(respondent_addi_state);
                $("#respondent_addi_district:first").removeClass(respondent_addi_district);

                var selectElement = document.getElementById('respondent_addi_state');
                selectElement.setAttribute("onchange", "change_addi_state('R',0, this.value)");
            });

            $('.multi-field .remove_out_row_heading', $wrapper).click(function() {
                var length_row=$('.multi-field', $wrapper).length;
                // alert('length_row='+length_row);
                if ($('.multi-field', $wrapper).length > 1)
                    $(this).parent('.multi-field').remove();
            });
        });

    </script>

    <script type="text/javascript">
        var court_type = "<?php echo $court_type;?>";

        court_type = parseInt(court_type);
        if(court_type){
            switch (court_type){
                case 1:
                    //high court
                    $("#radio_selected_court1").prop('checked',true).trigger('click');
                    //get_high_court_list(court_type,hc_value);
                    break;
                case 3:
                    //district court
                    //$("#radio_selected_court3").prop('checked',true).trigger('click');
                    get_state_list();
                    break;
                case 4:
                    //supreme court
                    //$("#radio_selected_court4").prop('checked',true).trigger('click');
                    get_sci_case_type();
                    break;
                case 5:
                    //state agency
                    //$("#radio_selected_court5").prop('checked',true).trigger('click');
                    get_agency_state_list();
                    break;
                default:
            }
        }

        function is_if_sclsc(if_sclsc) {
            if($('input[name="if_sclsc"]').is(':checked'))
            {
                $('.sp_no_yr').css('display','inline');
            }
            else
            {
                $('.sp_no_yr').css('display','none');
            }
            $('#txt_sclsc_no').val('');
            $('#ddl_sclsc_yr').val('');
        }
        function is_if_efil(if_efil) {
            if($('input[name="if_efil"]').is(':checked'))
            {
                $('.sp_efil_yr').css('display','inline');
            }
            else
            {
                $('.sp_efil_yr').css('display','none');
            }
            $('#txt_efil_no').val('');
            $('#ddl_sclsc_yr').val('');
        }
        function is_type_special_nature(type) {
            if(type==6)
            {
                $('.sp_doc_signed').css('display','inline');
            }
            else
            {
                $('.sp_doc_signed').css('display','none');
                $('#jailer_sign_dt').val('');
            }

        }
        function get_court_as(court_as) {

            $('#select2-case_type_id-container').text('Select Case Type');
            $('#case_type_id').val('');

            if (court_as == '4') {
                get_sci_case_type();
            } else if (court_as == '1') {
                //get_high_court_list(court_as);
            } else if (court_as == '3') {
                get_state_list();

            }else if (court_as == '5') {
                get_agency_state_list();
            }
        }
        function get_high_court_list(court_as,hc_value=null) {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cmis_state_id: court_as},
                url: "<?php echo base_url('Common/Ajaxcalls/get_high_court'); ?>",
                success: function (data)
                {
                    $('#ddl_st_agncy').html(data);
                    if(hc_value && court_as == 1){
                        $('#ddl_st_agncy').val(hc_value).select2().trigger("change");
                    }
                    updateCSRFToken();
                },
                error: function () {
                    updateCSRFToken();
                }
            });

        }
        $(document).ready(function () {
            //----------Get High Court Bench List----------------------//
            $('#ddl_st_agncyStop').change(function () {

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('#case_type_id').val('');

                var high_court_id = $(this).val();
                //var court_type = $("input[name=ddl_court]:checked").val();
                //var court_type = $("input[name=ddl_court]:selected").val();
                var court_type =$('#ddl_court :selected').val();
                //var court_type = $("input[name=ddl_court]").val();
                // alert('CSRF_TOKEN_VALUE=' +CSRF_TOKEN_VALUE);
                alert('high_court_id=' +high_court_id + 'court_type=='+court_type);
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: high_court_id, court_type: court_type},
                    url: "<?php echo base_url('Common/Ajaxcalls/get_hc_bench_list'); ?>",
                    success: function (data)
                    {
                        $('#ddl_bench').html(data);
                        /*if(hc_bench_value && court_type == 1){
                            $('#ddl_bench').val(hc_bench_value).select2().trigger("change");
                        }*/
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });

            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $(document).on('click', '#if_sclsc', function() {
                if ($(this).is(':checked')) {
                    $('#sclc_info').css('display', 'inline-flex');
                } else {
                    $('#sclc_info').css('display', 'none');
                }
                $('#sclc_no').val('');
                $('#sclc_year').val('');
            });
            $(document).on('click', '#if_filing', function() {
                if ($(this).is(':checked')) {
                    $('#filing_info').css('display', 'inline-flex');
                } else {
                    $('#filing_info').css('display', 'none');
                }
                $('#filing_no').val('');
                $('#filing_year').val('');
            });
            $(document).on('change', '#selpt,#respondent_department', function() {
                var type_name = $(this).attr('id');
                var type = type_name.split("_");
                var type_code = type[0];

                var department = $('#' + type_name).val();

                if (department == "I") {
                    $('#' + type_code + '_state_central').css('display', 'none');
                    $('#' + type_code + '_individual').css('display', 'flex');
                    $('#' + type_code + '_post').css('display', 'none');
                } else if (department == "D3") {
                    $('#' + type_code + '_state_central').css('display', 'none');
                    $('#' + type_code + '_post').css('display', 'contents');
                    $('#' + type_code + '_individual').css('display', 'none');
                } else {
                    $('#' + type_code + '_state_central').css('display', 'contents');
                    $('#' + type_code + '_post').css('display', 'contents');
                    $('#' + type_code + '_individual').css('display', 'none');
                }
            });


        });
    </script>
    <script>
        var state_Arr = '<?php echo json_encode($stateArr)?>';
        //----------Get District List----------------------//
        $('#selpsti').change(function () {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#selpdisi').val('');

            var get_state_id = $(this).val();
            if (get_state_id !='') {
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                    url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
                    success: function (data) {
                        $('#selpdisi').html(data);
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        $('#ppini').blur(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#ppini").val();
            if(pincode){
                var stateObj = JSON.parse(state_Arr);
                var options = '';
                options +='<option value="">Select State</option>';
                stateObj.forEach((response)=> options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                $('#selpsti').html(options).select2().trigger("change");
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                    url: "<?php echo base_url('Common/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function (response)
                    {
                        var taluk_name;
                        var district_name;
                        var state;
                        if(response){
                            var resData = JSON.parse(response);
                            if(resData){
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if(taluk_name){
                                $("#pcityi").val('');
                                $("#pcityi").val(taluk_name);
                            }
                            else{
                                $("#pcityi").val('');
                            }
                            if(state){
                                var stateObj = JSON.parse(state_Arr);
                                if(stateObj){
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                );
                                }
                                if(singleObj){
                                    $('#selpsti').val('');
                                    $('#selpsti').val(singleObj.id).select2().trigger("change");
                                }
                                else{
                                    $('#selpsti').val('');
                                }
                                if(district_name){
                                    var stateId = $('#selpsti').val();
                                    setSelectedDistrict(stateId,district_name);
                                }
                            }
                            else{
                                $('#selpsti').val('');
                            }
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        function setSelectedDistrict(stateId,district_name){
            if(stateId && district_name){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                    url: "<?php echo base_url('Common/Ajaxcalls/getSelectedDistricts'); ?>",
                    success: function (resData)
                    {
                        if(resData){
                            var districtObj = JSON.parse(resData);
                            var singleObj = districtObj.find(
                                item => item['district_name'] === district_name
                        );
                            if(singleObj){
                                $('#selpdisi').val('');
                                $('#selpdisi').val(singleObj.id).select2().trigger("change");
                            }
                            else{
                                $('#selpdisi').val('');
                            }
                        }
                        else{
                            $('#selpdisi').val('');
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        }
        //----------Get District List----------------------//
        $('#selrsti').change(function () {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#selrdisi').val('');

            var get_state_id = $(this).val();
            if (get_state_id !='') {
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
                    url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
                    success: function (data) {
                        $('#selrdisi').html(data);
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        $('#rpini').blur(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#rpini").val();
            if(pincode){
                var stateObj = JSON.parse(state_Arr);
                var options = '';
                options +='<option value="">Select State</option>';
                stateObj.forEach((response)=>options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                $('#selrsti').html(options).select2().trigger("change");
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                    url: "<?php echo base_url('Common/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function (response)
                    {
                        var taluk_name;
                        var district_name;
                        var state;
                        if(response){
                            var resData = JSON.parse(response);
                            if(resData){
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if(taluk_name){
                                $("#rcityi").val('');
                                $("#rcityi").val(taluk_name);
                            }
                            else{
                                $("#rcityi").val('');
                            }
                            if(state){
                                var stateObj = JSON.parse(state_Arr);
                                if(stateObj){
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                );
                                }
                                if(singleObj){
                                    $('#selrsti').val('');
                                    $('#selrsti').val(singleObj.id).select2().trigger("change");
                                }
                                else{
                                    $('#selrsti').val('');
                                }
                                if(district_name){
                                    var stateId = $('#selrsti').val();
                                    setSelectedDistrictR(stateId,district_name);
                                }
                            }
                            else{
                                $('#selrsti').val('');
                            }
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        function setSelectedDistrictR(stateId,district_name){
            if(stateId && district_name){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                    url: "<?php echo base_url('Common/Ajaxcalls/getSelectedDistricts'); ?>",
                    success: function (resData)
                    {
                        if(resData){
                            var districtObj = JSON.parse(resData);
                            var singleObj = districtObj.find(
                                item => item['district_name'] === district_name
                        );
                            if(singleObj){
                                $('#selrdisi').val('');
                                $('#selrdisi').val(singleObj.id).select2().trigger("change");
                            }
                            else{
                                $('#selrdisi').val('');
                            }
                        }
                        else{
                            $('#selrdisi').val('');
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        }







        $('#ppind').blur(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#ppind").val();
            if(pincode){
                var stateObj = JSON.parse(state_Arr);
                var options = '';
                options +='<option value="">Select State</option>';
                stateObj.forEach((response)=>options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                $('#selpstd').html(options).select2().trigger("change");
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                    url: "<?php echo base_url('Common/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function (response)
                    {
                        var taluk_name;
                        var district_name;
                        var state;
                        if(response){
                            var resData = JSON.parse(response);
                            if(resData){
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if(taluk_name){
                                $("#pcityd").val('');
                                $("#pcityd").val(taluk_name);
                            }
                            else{
                                $("#pcityd").val('');
                            }
                            if(state){
                                var stateObj = JSON.parse(state_Arr);
                                if(stateObj){
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                );
                                }
                                if(singleObj){
                                    $('#selpstd').val('');
                                    $('#selpstd').val(singleObj.id).select2().trigger("change");
                                }
                                else{
                                    $('#selpstd').val('');
                                }
                                if(district_name){
                                    var stateId = $('#selpstd').val();
                                    setSelectedDistrictPD(stateId,district_name);
                                }
                            }
                            else{
                                $('#selpstd').val('');
                            }
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        function setSelectedDistrictPD(stateId,district_name){
            if(stateId && district_name){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                    url: "<?php echo base_url('Common/Ajaxcalls/getSelectedDistricts'); ?>",
                    success: function (resData)
                    {
                        if(resData){
                            var districtObj = JSON.parse(resData);
                            var singleObj = districtObj.find(
                                item => item['district_name'] === district_name
                        );
                            if(singleObj){
                                $('#selpdisd').val('');
                                $('#selpdisd').val(singleObj.id).select2().trigger("change");
                            }
                            else{
                                $('#selpdisd').val('');
                            }
                        }
                        else{
                            $('#selpdisd').val('');
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        }


        $('#rpind').blur(function(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var pincode = $("#rpind").val();
            if(pincode){
                var stateObj = JSON.parse(state_Arr);
                var options = '';
                options +='<option value="">Select State</option>';
                stateObj.forEach((response)=>options +='<option value="'+response.id+'">'+response.state_name+'</option>');
                $('#selrstd').html(options).select2().trigger("change");
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, pincode : pincode},
                    url: "<?php echo base_url('Common/Ajaxcalls/getAddressByPincode'); ?>",
                    success: function (response)
                    {
                        var taluk_name;
                        var district_name;
                        var state;
                        if(response){
                            var resData = JSON.parse(response);
                            if(resData){
                                taluk_name = resData[0]['taluk_name'].trim().toUpperCase();
                                district_name = resData[0]['district_name'].trim().toUpperCase();
                                state = resData[0]['state'].trim().toUpperCase();
                            }
                            if(taluk_name){
                                $("#rcityd").val('');
                                $("#rcityd").val(taluk_name);
                            }
                            else{
                                $("#rcityd").val('');
                            }
                            if(state){
                                var stateObj = JSON.parse(state_Arr);
                                if(stateObj){
                                    var singleObj = stateObj.find(
                                        item => item['state_name'] === state
                                );
                                }
                                if(singleObj){
                                    $('#selrstd').val('');
                                    $('#selrstd').val(singleObj.id).select2().trigger("change");
                                }
                                else{
                                    $('#selrstd').val('');
                                }
                                if(district_name){
                                    var stateId = $('#selrstd').val();
                                    setSelectedDistrictRD(stateId,district_name);
                                }
                            }
                            else{
                                $('#selrstd').val('');
                            }
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        });
        function setSelectedDistrictRD(stateId,district_name){
            if(stateId && district_name){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "GET",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: stateId},
                    url: "<?php echo base_url('Common/Ajaxcalls/getSelectedDistricts'); ?>",
                    success: function (resData)
                    {
                        if(resData){
                            var districtObj = JSON.parse(resData);
                            var singleObj = districtObj.find(
                                item => item['district_name'] === district_name
                        );
                            if(singleObj){
                                $('#selpdisd').val('');
                                $('#selpdisd').val(singleObj.id).select2().trigger("change");
                            }
                            else{
                                $('#selpdisd').val('');
                            }
                        }
                        else{
                            $('#selpdisd').val('');
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        updateCSRFToken();
                    }
                });
            }
        }
    </script>
    <!--<script src="<?php /*echo base_url('plugins/jquery-validation/jquery.validate.js'); */?>"></script>
    <script src="<?php /*echo base_url('plugins/jquery-validation/additional-methods.min.js'); */?>"></script>-->
    <script>
        $('#diary_generation_form').on('submit', function () {
            if ($('#diary_generation_form').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                var court_name = $.trim($("#ddl_court option:selected").val());
                //alert($("input:ddl_court:checked").val());
                //var court_name =$("input[name='ddl_court']:checked").val();
                alert('court_name='+court_name);
                /*if(court_name){
                    court_name =  parseInt(court_name);
                    switch(court_name){
                        case 1:
                            var high_courtname = $("#high_courtname option:selected").val();
                            var high_court_bench_name = $("#high_court_bench_name option:selected").val();
                            if(high_courtname ==''){
                                alert("Please select  high court name.");
                                $("#high_courtname").focus();
                                validateFlag = false;
                                return false;
                            }
                            else if(high_court_bench_name == ''){
                                alert("Please select  high court bench name.");
                                $("#high_court_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 3:
                            var district_court_state_name = $("#district_court_state_name option:selected").val();
                            var district_court_district_name = $("#district_court_district_name option:selected").val();
                            if(district_court_state_name ==''){
                                alert("Please select  state name.");
                                $("#district_court_state_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            else if(district_court_district_name == ''){
                                alert("Please select  district name.");
                                $("#district_court_district_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 4:
                            var supreme_state_name = $("#supreme_state_name option:selected").val();
                            var supreme_bench_name = $("#supreme_bench_name option:selected").val();
                            if(supreme_state_name ==''){
                                alert("Please select  state name.");
                                $("#supreme_state_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            else if(supreme_bench_name == ''){
                                alert("Please select  bench name.");
                                $("#supreme_bench_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        case 5:
                            var state_agency = $("#state_agency option:selected").val();
                            var state_agency_name = $("#state_agency_name option:selected").val();
                            if(state_agency ==''){
                                alert("Please select  state name.");
                                $("#state_agency").focus();
                                validateFlag = false;
                                return false;
                            }
                            else if(state_agency_name == ''){
                                alert("Please select agency name.");
                                $("#state_agency_name").focus();
                                validateFlag = false;
                                return false;
                            }
                            break;
                        default:
                            validateFlag = false;

                    }
                }
                else{
                    alert("Please select  court Type.");
                    $("#court_name").focus();
                    validateFlag = false;
                    return false;
                }*/
                if(validateFlag){ //alert('readt post form');
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Diary/generate'); ?>",
                        data: form_data,
                        async: false,
                        beforeSend: function () {
                            $('.form_save_first').val('Please wait...');
                            //$('.form_save_first').prop('disabled', true);
                        },
                        success: function (data) {
                            alert(data);
                            ///$('#pet_save').val('SAVE');
                            //$('#pet_save').prop('disabled', false);
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            } else if (resArr[0] == 2) {
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                                $('.alert-success').show();
                                //window.location.href = resArr[2];
                                //location.reload();

                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                            updateCSRFToken();
                        },
                        error: function () {
                            updateCSRFToken();
                        }

                    });
                    return false;
                }
            } else {
                return false;
            }
        });
        /* $(function () {
             $.validator.setDefaults({
                 submitHandler: function () {
                     alert( "Form successful submitted!" );
                 }
             });
             $('#diary_generation_form').validate({
                 rules: {
                     hc_court: {
                         required: true,
                     },
                     terms: {
                         required: true
                     },
                 },
                 messages: {
                     hc_court: {
                         required: "Please select court state"
                     },
                     terms: "Please accept our terms"
                 },
                 errorElement: 'span',
                 errorPlacement: function (error, element) {
                     error.addClass('invalid-feedback');
                     element.closest('.form-group').append(error);
                 },
                 highlight: function (element, errorClass, validClass) {
                     $(element).addClass('is-invalid');
                 },
                 unhighlight: function (element, errorClass, validClass) {
                     $(element).removeClass('is-invalid');
                 }
             });
         });*/
        // BS-Stepper Init
        function setStepper(stepperPosition) {
            //alert(stepperPosition);
            //$(".stepperPosition_"+position).click();
            if (stepperPosition == '1') {
                $(".stepperPosition_1").click();
            } else if (stepperPosition == '2') {
                $(".stepperPosition_2").click();
            } else if (stepperPosition == '3') {
                $(".stepperPosition_3").click();
            }else  {

            }
        }
    </script>
 <?=view('sci_main_footer');?>