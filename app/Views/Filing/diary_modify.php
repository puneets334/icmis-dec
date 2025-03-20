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
                        <?=view('Filing/filing_breadcrumb');?>

                        <span class="alert alert-error" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </span>
                        <!-- <span class="form-response"> </span>-->
                        <?php
                        $utype=$rw_utype['usertype'];
                        $sec=$rw_utype['section'];

                        $allow_user=0;
                        if(!empty($check_if_fil_user)){
                            $allow_user=1;
                        }
                        $sel ='';
                        $stateArr = array();
                        if (count($state_list)) {
                            foreach ($state_list as $dataRes) {
                                $tempArr = array();
                                $tempArr['id'] = sanitize(trim($dataRes['cmis_state_id']));
                                $tempArr['state_name'] = strtoupper(trim($dataRes['agency_state']));
                                $stateArr[] = (object)$tempArr;
                            }
                        }

                        $court_type = !empty($diary_details['ddl_court']) ? $diary_details['ddl_court'] : 1;
                               $hc_value = !empty($diary_details['dacode']) ? $diary_details['dacode'] : NULL;

                        if(empty($fetch_rw)){ ?>
                        <div class="alert alert-danger text-white" style="display: block;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> Record Not Found!!!</span>
                        </div>
                        <?php  }else{



                            ?>


                        <div id="dv_content1"   >
                            <input type="hidden" id="fil_hd"/>
                            <div id="show_fil">
                                <?php if($role==""){ ?>
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
                                            <?php
                                            $filing_details= session()->get('filing_details');
                                            ?>
                                            <input type="hidden" id="t_h_cno" name="t_h_cno"  size="5" value="<?php echo substr($filing_details['diary_no'], 0, -4); ?>"/>
                                            <input type="hidden" id="t_h_cyt" name="t_h_cyt" maxlength="4" size="4" value="<?php if($filing_details) { echo substr($filing_details['diary_no'],-4); } else { echo date('Y'); }  ?>" />
                                            <div class="row ">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court Type<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_court" id="ddl_court" class="form-control" <?php if(($fetch_rw['casetype_id']==9 || $fetch_rw['casetype_id']==10|| $fetch_rw['casetype_id']==25|| $fetch_rw['casetype_id']==26|| $fetch_rw['casetype_id']==19|| $fetch_rw['casetype_id']==20 || $fetch_rw['casetype_id']==39)  && $sec == 19 && $utype <> 4 && $allow_user!=1){?> disabled="true" <?php } ?>>
                                                                <option value="">Select Court</option>
                                                                <?php
                                                                $sel = '';
                                                                foreach ($court_type_list as $row) {
                                                                    if ($row['id']==$fetch_rw['from_court']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo'<option '.$sel.' value="'.$row['id'].'">'.$row['court_name'].'</option>';
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
                                                            <select name="ddl_st_agncy" id="ddl_st_agncy" class="form-control" <?php  if(($fetch_rw['casetype_id']==9 || $fetch_rw['casetype_id']==10|| $fetch_rw['casetype_id']==25|| $fetch_rw['casetype_id']==26|| $fetch_rw['casetype_id']==19|| $fetch_rw['casetype_id']==20 || $fetch_rw['casetype_id']==39)  && $sec == 19 && $utype <> 4 && $allow_user!=1) {?> disabled="true" <?php } ?>>
                                                                <option value="">Select State</option>
                                                                <?php $sel='';
                                                                foreach ($state as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($fetch_rw['ref_agency_state_id'] == $row['cmis_state_id']) { $sel = 'selected=selected'; }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['state_name'])) . '</option>';
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
                                                                <?php
                                                                foreach ($hc_benches as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($fetch_rw['ref_agency_code_id'] == $row['id']) {  $sel = 'selected=selected';}else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['id'])) . '">' . sanitize(strtoupper($row['agency_name'])) . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($check_lowerct)){ ?>
                                                <div class="row ">
                                                    <div id="dv_case_no" style="text-align: center"><?/*=view ('Filing/get_case_strc');*/?></div>
                                                    <div id="dv_parties"></div>
                                                </div>
                                             <?php } ?>
                                            <div class="row ">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Case Type <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_nature" id="ddl_nature" class="form-control" <?php   if(  ($fetch_rw['casetype_id']==9 || $fetch_rw['casetype_id']==10|| $fetch_rw['casetype_id']==25|| $fetch_rw['casetype_id']==26|| $fetch_rw['casetype_id']==19|| $fetch_rw['casetype_id']==20 || $fetch_rw['casetype_id']==39)  && $sec == 19 && $utype <> 4 && $allow_user!=1) { echo " in loop";?> disabled="true" <?php } ?>>
                                                                <option value="">Select case type</option>
                                                                <?php $sel='';
                                                                foreach ($casetype as $row) {
                                                                    if ($fetch_rw['casetype_id'] == $row['casecode']) {  $sel = 'selected=selected';}else{$sel='';}
                                                                    echo'<option '.$sel.' value="' . sanitize($row['casecode']) . '">' . sanitize($row['casename']) . '</option>';
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
                                                                <?php $sel='';
                                                                foreach ($usersection as $row) {
                                                                    if ($fetch_rw['section_id'] == $row['id']) {  $sel = 'selected=selected';}else{$sel='';}
                                                                    echo'<option '.$sel.' value="' . sanitize(($row['id'])) . '">' . sanitize($row['section_name']) . '</option>';
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
                                                                <option value="1" <?php if($fetch_rw['nature']==1) echo "selected";?>>None</option>
                                                                <option value="6" <?php if($fetch_rw['nature']==6) echo "selected";?>>Jail Petition</option>
                                                                <!--<option value="7" <?php /*if($fetch_rw['nature']==7) echo "selected";*/?>>PUD</option>-->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 sp_doc_signed" id="sp_doc_signed" <?php if(empty($jail_petition_details) && empty($jail_petition_details['jailer_sign_dt'])) { ?> style="display: none"<?php } ?>>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Date of document signed by jailer <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <!--<div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                                </div>-->
                                                                <input type="date" class="form-control" name="txt_doc_signed" id="txt_doc_signed" value="<?=(!empty($jail_petition_details)) ? $jail_petition_details['jailer_sign_dt'] : '';?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label text-red">Extreme Priority Category</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_priority" id="ddl_priority"  class="form-control">
                                                                <option value="0">None</option>
                                                                <?php
                                                                foreach ($ref_special_category_filing as $row) {?>
                                                                    <option value="<?php echo $row['id'] ?>" <?php if($row['id']==$row['ref_special_category_filing_id'] and $row['display']='Y') { ?> selected="selected" <?php } ?>><?php echo $row['category_name'] ?></option>
                                                                <?php }?>
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
                                                        <input type="text" class="form-control" size="4" maxlength="4" id="case_doc" value="<?php echo $fetch_rw['case_pages']?>" onkeypress="return onlynumbers(event)" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="total pages">
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
                                                              <input type="checkbox" name="if_sclsc" id="if_sclsc" onchange="is_if_sclsc(this.value)" <?php if(!empty($fetch_rw) && $fetch_rw["if_sclsc"]==1){ echo "checked";}?>>
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
                                            <!--If Filing-->
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">IF Efiling <?php echo $fetch_rw["ack_id"];?></label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                              <input type="checkbox" name="if_efil" id="if_efil" onchange="is_if_efil(this.value)" <?php if($fetch_rw["ack_id"] !=1 && $fetch_rw["ack_id"] !='' && $fetch_rw["ack_id"] !=0){ echo "checked";}?>>
                                                            </span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txt_efil_no" id="txt_efil_no" size="4" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?=!empty($fetch_rw) ? $fetch_rw['ack_id'] : ''; ?>" placeholder="Enter No.">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 sp_efil_yr" <?php if($fetch_rw["ack_id"]==0 || $fetch_rw["ack_id"]=='') { ?> style="display: none" <?php } ?>>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">SCLSC Year</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_efil_yr" id="ddl_efil_yr" class="form-control select2">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $yr_sclsc=date('Y');
                                                            for ($index = $yr_sclsc; $index >=1930; $index--) {
                                                                ?>
                                                                <option value="<?php echo $index; ?>" <?php if(!empty($fetch_rw) && $fetch_rw['ack_rec_dt']==$index) { ?> selected="selected" <?php } ?>><?php echo $index; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--If Filing end -->
                                        </div>



                                        <hr/>
                                        <!--start Advocate-->
                                        <h4 class="basic_heading"> Advocate Details </h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Main Pet. Adv. <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="padvt" name="padvt" onchange="changeAdvocate(this.id,this.value)" class="form-control" >
                                                            <option value="A" <?php if($petadv_info_rw['aor_state']=='A') echo "selected";?>>AOR</option>
                                                            <option value="N" <?php if($petadv_info_rw['aor_state']=='N') echo "selected";?>>Non-AOR</option>
                                                            <option value="S" <?php if($petadv_info_rw['aor_state']=='S') echo "selected";?>>State</option>
                                                            <option value="SS" <?php if($petadv_info_rw['aor_state']=='SS') echo "selected";?>>Petitioner In Person</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padv_is_ac">
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="is_ac" name="is_ac" <?php if($petadv_info_rw['is_ac']=='Y') echo "checked"; ?>>
                                                        <label for="is_ac"> Is Amicus Curiae</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4" id="padv_state">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Enrol State <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_pet_adv_state" id="ddl_pet_adv_state"  class="form-control" <?php if($fetch_rw['padvt']!='S' && $fetch_rw['padvt']!='N') echo "disabled"; ?>>
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($state_list as $row) {
                                                                if (isset($row['cmis_state_id'])) {
                                                                    if ($row['cmis_state_id']==$petadv_info_rw['state_id']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                }
                                                                ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4" id="padvno_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">AOR code/Name:/Enrol No <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvno" size="25" maxlength="6" onkeypress="return onlynumbersadv(event)" onblur="getAdvocate_for_main(this.id,'P')" value="<?=($petadv_info_rw['aor_state']=='A' || $petadv_info_rw['aor_state']=='') ? $petadv_info_rw['aor_code']: $petadv_info_rw['enroll_no'];?>"  <?=($petadv_info_rw['aor_state']=='A' || $petadv_info_rw['aor_state']=='') && ($fetch_rw['padvt']=='C') ? 'disabled="true"': '';?>  placeholder="Enrol No.(Non-AOR)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvyr_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'P')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo $petadv_info_rw['enroll_date']?>" <?=($petadv_info_rw['aor_state']=='A' || $petadv_info_rw['aor_state']=='') && ($fetch_rw['padvt']=='C') ? 'disabled="true"': '';?> />

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Name</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control"  placeholder="Enter name" id="padvname" size="30" value="<?=($petadv_info_rw['aor_state']=='A' || $petadv_info_rw['aor_state']=='') ? $petadv_info_rw['name']:$pet_rw['partyname'].' (SELF)' ;?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvmob_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mobile</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" placeholder="Enter mobile number" id="padvmob" size="10" value="<?php echo $petadv_info_rw['mobile']?>" maxlength="10" onkeypress="return onlynumbers(event)" <?=($petadv_info_rw['aor_state']=='SS') ? : 'readonly=""';?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="padvemail_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Email Id</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="padvemail" name="padvemail" size="30" value="<?php echo $petadv_info_rw['email']?>" placeholder="Enter email id" <?=($petadv_info_rw['aor_state']=='SS') ? '' : 'readonly=""';?>>
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
                                                            <option value="A" <?php if(!empty($resadv_info_rw) && $resadv_info_rw['aor_state']=='A') echo "selected";?>>AOR</option>
                                                            <option value="N" <?php if(!empty($resadv_info_rw) && $resadv_info_rw['aor_state']=='N') echo "selected";?>>Non-AOR</option>
                                                            <option value="S" <?php if(!empty($resadv_info_rw) && $resadv_info_rw['aor_state']=='S') echo "selected";?>>State</option>
                                                            <option value="SS" <?php if(!empty($resadv_info_rw) && $resadv_info_rw['aor_state']=='SS') echo "selected";?>>Petitioner In Person</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radv_is_ac">
                                                <div class="form-group clearfix">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="ris_ac" name="ris_ac" <?php if(!empty($resadv_info_rw) && $resadv_info_rw['is_ac']=='Y') echo 'checked';?>>
                                                        <label for="ris_ac"> Is Amicus Curiae</label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4" id="radv_state">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Enrol State <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_res_adv_state" id="ddl_res_adv_state"  class="form-control" <?php if(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']!='S' && $resadv_info_rw['aor_state']!='N')) echo "disabled"; ?>>
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($state_list as $row) {
                                                                if (isset($row['cmis_state_id'])) {
                                                                    if (!empty($resadv_info_rw) && $row['cmis_state_id']==$resadv_info_rw['state_id']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                }
                                                                ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvno_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">AOR code/Name:/Enrol No <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvno" size="25"  onchange="getAdvocate_for_main(this.id,'R')"  value="<?php if(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']=='A' || $resadv_info_rw['aor_state']=='')) { echo $resadv_info_rw['aor_code']; }else{ echo (!empty($resadv_info_rw)) ? $resadv_info_rw['enroll_no'] :'';}?>" <?php if(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']=='SS') && ($fetch_rw['radvt']=='C')){ echo 'disabled="true"';}?>   placeholder="Enrol No.(Non-AOR)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvyr_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvyr" size="4" maxlength="4" onblur="getAdvocate_for_main(this.id,'R')" onkeypress="return onlynumbers(event)" value="<?=(!empty($resadv_info_rw)) ? $resadv_info_rw['enroll_date'] : '';?>" <?=(!empty($resadv_info_rw)) && ($resadv_info_rw['aor_state']=='SS') && ($fetch_rw['radvt']=='C') ? 'disabled="true"': '';?>>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Name</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control"  placeholder="Enter name" id="radvname" size="30" value="<?php if(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']=='SS')) { echo $res_rw['partyname'].' (SELF)'; }else{ if(!empty($resadv_info_rw)){ echo $resadv_info_rw['name']; }};?>" <?=(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']=='A' || $resadv_info_rw['aor_state']=='') && ($fetch_rw['radvt']=='C')) ? 'disabled': '';?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvmob_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Mobile</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" placeholder="Enter mobile number" id="radvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)"  value="<?=(!empty($resadv_info_rw)) ? $resadv_info_rw['mobile'] : '';?>" <?=(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state']=='SS')) ? : 'readonly=""';?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4" id="radvemail_">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Email Id</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="radvemail" name="radvemail" size="30" value="<?=(!empty($resadv_info_rw)) ? $resadv_info_rw['email'] : '';?>" placeholder="Enter email id" <?=(!empty($resadv_info_rw) && ($resadv_info_rw['aor_state'] =='SS')) ? '': 'readonly=""';?>>
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
                                                        <select id="selpt" name="selpt" onchange="activate_main(this.id)" class="form-control" disabled>
                                                            <option value="I" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='I') ? 'selected="selected"' :'';?>>Individual</option>
                                                            <option value="D1" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D1') ? 'selected="selected"' :'';?>>State Department</option>
                                                            <option value="D2" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D2') ? 'selected="selected"' :'';?>>Central Department</option>
                                                            <option value="D3" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D3') ? 'selected="selected"' :'';?>>Other Organization</option>
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
                                                            <input type="text" class="form-control" id="pet_name" name="pet_name" placeholder="Enter Name" value="<?php echo $pet_rw['partyname']?>" disabled onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Relation" class="col-sm-5 col-form-label">Relation :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selprel" name="selprel" onchange="setSex(this.value,this.id)" class="form-control" disabled>
                                                                <option value="">Select</option>
                                                                <option value="S" <?=!empty($pet_rw['sonof']) && ($pet_rw['sonof']=='S') ? 'selected="selected"' :'';?>>Son of</option>
                                                                <option value="D" <?=!empty($pet_rw['sonof']) && ($pet_rw['sonof']=='D') ? 'selected="selected"' :'';?>>Daughter of</option>
                                                                <option value="W" <?=!empty($pet_rw['sonof']) && ($pet_rw['sonof']=='W') ? 'selected="selected"' :'';?>>Wife of</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-5 col-form-label">Father/Husband :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="prel" name="prel" value="<?php echo $pet_rw['prfhname']?>" disabled placeholder="Enter Father/Husband" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Relation" class="col-sm-5 col-form-label">Gender :</label>
                                                        <div class="col-sm-7">
                                                            <select id="psex" name="psex" class="form-control select2" disabled>
                                                                <option value="">Select</option>
                                                                <option value="N" <?=!empty($pet_rw) && ($pet_rw['sex']=='N') ? 'selected="selected"' :'';?>>N.A.</option>
                                                                <option value="M" <?=!empty($pet_rw) && ($pet_rw['sex']=='M') ? 'selected="selected"' :'';?>>Male</option>
                                                                <option value="F" <?=!empty($pet_rw) && ($pet_rw['sex']=='F') ? 'selected="selected"' :'';?>>Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="petitioner_age" class="col-sm-5 col-form-label">Age :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="page" name="page" value="<?php echo $pet_rw['age']?>" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Occupation/Department :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pocc" name="pocc" value="<?php echo $pet_rw['addr1']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Occupation/Department" disabled>
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
                                                            <select  id="p_conti" name="p_conti" onchange="setCountry_state_dis(this.id,this.value)" class="form-control" disabled>
                                                                <option value="">Select Country</option>
                                                                <?php
                                                                foreach ($country as $row) {?>
                                                                    <option value="<?php echo $row['id']; ?>" <?php if (!empty($pet_rw['country']) && $pet_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                <?php   }   ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="paddi" name="paddi" value="<?php echo $pet_rw['addr2']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text"  class="form-control" id="ppini" name="ppini" maxlength="6" value="<?php echo $pet_rw['pin']?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pcityi" name="pcityi" value="<?php echo $pet_rw['dstname']?>" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select  id="selpsti" name="selpsti" onchange="getDistrict('P',this.id,this.value)" class="form-control" <?php if($pet_rw['country']!='96') echo "disabled";?> disabled>
                                                                <option value="">Select State</option>
                                                                <?php
                                                                $sel ='';
                                                                    foreach ($state_list as $row) {
                                                                        if (isset($row['cmis_state_id'])) {
                                                                            if ($row['cmis_state_id']==$pet_rw['state']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                        }
                                                                        ?>
                                                                        <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select  id="selpdisi" name="selpdisi"  class="form-control" <?php if($pet_rw['country'] !='96') echo "disabled";?>disabled>
                                                                <option value="">Select</option>
                                                                <?php
                                                                $sel ='';
                                                                foreach ($pet_dist_list as $row) {
                                                                if($pet_rw['city']==0){echo '<option  value="' . sanitize(($pet_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                if ($row['id_no']==$pet_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                   ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="petitioner_email" class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pmobi" name="pmobi" value="<?php echo $pet_rw['contact']?>" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no." disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="petitioner_email" class="col-sm-5 col-form-label">Email Id:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pemaili" value="<?php echo $pet_rw['email']?>"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="p_noi" name="p_noi" value="<?php echo $fetch_rw['pno'];?>"  size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--end individual petitioner address area-->
                                        </div>
                                        <!--end party type individual for_I_p-->
















                                        <!--start party type department for_D_p-->




                                        <div id="for_D_p" style="display: none">
                                            <?php if($pet_rw['ind_dep'] =='I') {?>
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
                                            <?php } else {?>
                                                <?php
                                                if($pet_rw['ind_dep']=='D3'){ $disply1st='none';} else{ $disply1st='block';}
                                                $data_of_match_pet_causetitle1 =$data_of_match_pet_causetitle2=$data_of_match_pet_causetitle3='';
                                                if(!empty($pet_rw) && isset($pet_rw['deptname']) && !empty($pet_rw['deptname'])){
                                                    $data_of_match_pet_causetitle1 = strpos('sarthak'.$fetch_rw['pet_name'], $pet_rw['deptname']);
                                                }
                                                if(!empty($pet_rw) && !empty($pet_rw['deptname']) && !empty($pet_rw['partysuff'])){
                                                    $data_of_match_pet_causetitle2 = strpos('sarthak'.$fetch_rw['pet_name'], trim(str_replace($pet_rw['deptname'],'',$pet_rw['partysuff'])));
                                                }
                                                if(!empty($pet_rw) && !empty($pet_rw['addr1'])){
                                                    $data_of_match_pet_causetitle3 = strpos('sarthak'.$fetch_rw['pet_name'], $pet_rw['addr1']);
                                                }
                                                $pet_statename=$pet_deptt='';
                                                if (!empty($pet_rw) && !empty($pet_rw['deptname']) && $pet_rw['partysuff']){
                                                    $pet_deptt=trim(str_replace($pet_rw['deptname'],'',$pet_rw['partysuff']));
                                                }else{$pet_deptt=$pet_rw['partysuff'];}
                                                ?>


                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label" id='for_D_p_sn1'><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle1">State <span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" id="pet_causetitle1" <?php if($data_of_match_pet_causetitle1 >0) echo "checked"; ?> disabled  >
                                                                </div></label>
                                                            <div class="col-sm-7" id='for_D_p_sn2'>
                                                                <input type="text" class="form-control" name="pet_statename" id="pet_statename" value="<?php echo $pet_rw['partyname']?>" disabled onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State" disabled>
                                                                <input type="hidden" id="pet_statename_hd" name="pet_statename_hd" value="<?php echo $pet_rw['state_in_name']?>" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle2">Department <span class="text-red">*</span> :</label>
                                                                    <input type="checkbox" name="pet_causetitle2" id="pet_causetitle2" <?php if($data_of_match_pet_causetitle2 >0) echo "checked"; ?> disabled >
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pet_deptt" name="pet_deptt" value="<?php echo $pet_deptt;?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department" disabled>
                                                                <input type="hidden" id="pet_deptt_code" name="pet_deptt_code" value="<?php echo $pet_rw['deptcode']; ?>" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label for="State" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                    <label for="pet_causetitle3">Post :</label>
                                                                    <input type="checkbox" name="pet_causetitle3" id="pet_causetitle3" <?php if($data_of_match_pet_causetitle3 >0) echo "checked"; ?> disabled >
                                                                </div></label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" name="pet_post" id="pet_post" value="<?php echo $pet_rw['addr1'];?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Post" disabled>
                                                                <input type="hidden" id="pet_post_code" value="<?php echo $pet_rw['authcode']?>" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php } ?>
                                            <!--start department petitioner address area-->

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">Country <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select  id="p_contd" name="p_contd" onchange="setCountry_state_dis(this.id,this.value)" class="form-control" disabled>
                                                                <option value="">Select Country</option>
                                                                <?php
                                                                foreach ($country as $row) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" <?php if (!empty($pet_rw['country']) && $pet_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                <?php   }   ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="paddd" name="paddd" value="<?php echo $pet_rw['addr2']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text"  class="form-control" id="ppind" name="ppind" maxlength="6" value="<?php echo $pet_rw['pin']?>" disabled onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pcityd" name="pcityd" value="<?php echo $pet_rw['dstname']?>" disabled onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select  id="selpstd" name="selpstd" onchange="getDistrict('P',this.id,this.value)" class="form-control" <?php if($pet_rw['country']!='96') echo "disabled";?> disabled>
                                                                <option value="">Select State</option>
                                                                <?php
                                                                $sel ='';
                                                                foreach ($state_list as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($row['cmis_state_id']==$pet_rw['state']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                    } ?>
                                                                <?php } ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select  id="selpdisd" name="selpdisd"  class="form-control" <?php if($pet_rw['country']!='96') echo "disabled";?> disabled>
                                                                <option value="">Select</option>
                                                                <?php
                                                                $sel ='';
                                                                foreach ($pet_dist_list as $row) {
                                                                    if($pet_rw['city']==0){echo '<option  value="' . sanitize(($pet_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                    if ($row['id_no']==$pet_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                    ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="petitioner_email" class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pmobd" name="pmobd" maxlength="10" value="<?php echo $pet_rw['contact']?>" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no." disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="petitioner_email" class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="pemaild" name="pemaild" value="<?php echo $pet_rw['email']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="p_nod"  value="<?php  echo $fetch_rw['pno']?>" size="3" maxlength="4"  onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--end department petitioner address area-->

                                        </div>

                                        <!--end party type department for_D_p-->

                                        <!--extra address Petitioner-->
                                        <div class="col-md-12 mt-5 mb-3">
                                            <div id="dv_add_parties"></div>
                                            <?php $p_sno=0; if (!empty($additional_address_p)){ foreach ($additional_address_p as $row) { $p_sno++; } }?>
                                            <input type="hidden" name="hd_add_address" id="hd_add_address"  value="<?php echo $p_sno; ?>" />
                                        </div>

                                        <!--end extra Address Petitioner-->
                                        <center>
                                            <div class="btn btn-primary" onclick="setStepper('1')">Previous</div>
                                            <div class="btn btn-primary" onclick="setStepper('3')">Next</div>
                                        </center>
                                    </div>
                                    <!-- /.petitioner_tab_panel -->



                                    <div class="tab-pane" id="respondent_tab_panel">
                                        <h4 class="basic_heading"> Respondent Information </h4>
                                        <?php  if($res_rw['pflag']!='P'){ ?>
                                            <h5 class="text-red" style="color: red"> PARTY NO 1 IS EITHER DELETED OR DISPOSED/DISMISSED, MODIFICATION CAN CHNAGE CAUSE-TITLE </h5>
                                            <?php  }  ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label for="Party-Type" class="col-sm-5 col-form-label">Party Type<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="selrt" name="selrt" onchange="activate_main(this.id)" class="form-control" disabled>
                                                            <option value="I" <?=!empty($res_rw) && ($res_rw['ind_dep']=='I') ? 'selected="selected"' :'';?>>Individual</option>
                                                            <option value="D1" <?=!empty($res_rw) && ($res_rw['ind_dep']=='D1') ? 'selected="selected"' :'';?>>State Department</option>
                                                            <option value="D2" <?=!empty($res_rw) && ($res_rw['ind_dep']=='D2') ? 'selected="selected"' :'';?>>Central Department</option>
                                                            <option value="D3" <?=!empty($res_rw) && ($res_rw['ind_dep']=='D3') ? 'selected="selected"' :'';?>>Other Organization</option>
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
                                                            <input type="text" class="form-control" id="res_name" name="res_name" value="<?php echo $res_rw['partyname']?>" disabled onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Name" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Party-Type" class="col-sm-5 col-form-label">Relation :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selrrel" name="selrrel" onchange="setSex(this.value,this.id)" class="form-control" disabled>
                                                                <option value="">Select</option>
                                                                <option value="S" <?=!empty($res_rw['sonof']) && ($res_rw['sonof']=='S') ? 'selected="selected"' :'';?>>Son of</option>
                                                                <option value="D" <?=!empty($res_rw['sonof']) && ($res_rw['sonof']=='D') ? 'selected="selected"' :'';?>>Daughter of</option>
                                                                <option value="W" <?=!empty($res_rw['sonof']) && ($res_rw['sonof']=='W') ? 'selected="selected"' :'';?>>Wife of</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Father/Husband :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rrel" name="rrel" value="<?php echo $res_rw['prfhname']?>" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Father/Husband" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Party-Type" class="col-sm-5 col-form-label">Gender :</label>
                                                        <div class="col-sm-7">
                                                            <select id="rsex" name="rsex" class="form-control select2">
                                                                <option value="">Select</option>
                                                                <option value="N" <?=!empty($res_rw) && ($res_rw['sex']=='N') ? 'selected="selected"' :'';?>>N.A.</option>
                                                                <option value="M" <?=!empty($res_rw) && ($res_rw['sex']=='M') ? 'selected="selected"' :'';?>>Male</option>
                                                                <option value="F" <?=!empty($res_rw) && ($res_rw['sex']=='F') ? 'selected="selected"' :'';?>>Female</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Age :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rage" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age" value="<?php echo $res_rw['age']?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Occupation/Department :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rocc" onblur="remove_apos(this.value,this.id)" placeholder="Enter Occupation/Department" value="<?php echo $res_rw['addr1']?>" disabled>
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
                                                                    <option value="<?php echo $row['id']; ?>" <?php if (!empty($res_rw['country']) && $res_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                <?php   }   ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="raddi" name="raddi" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address" value="<?php echo $res_rw['addr2']?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rpini" name="rpini" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin" value="<?php echo $res_rw['pin']?>"  disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rcityi" name="rcityi" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City" value="<?php echo $res_rw['dstname']?>" disabled>
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
                                                                $sel ='';
                                                                foreach ($state_list as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($row['cmis_state_id']==$res_rw['state']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                    }
                                                                    ?>
                                                                <?php } ?>
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
                                                                <?php
                                                                $sel ='';
                                                                foreach ($res_dist_list as $row) {
                                                                    if($res_rw['city']==0){echo '<option  value="' . sanitize(($res_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                    if ($row['id_no']==$res_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                    ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rmobi" name="rmobi" maxlength="10" value="<?php echo $res_rw['contact']?>" disabled onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Email Id:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="remaili" name="remaili" value="<?php echo $res_rw['email']?>" disabled onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="r_noi" value="<?php echo $fetch_rw['rno']?>"  size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <!--end individual respondent address area-->


                                        </div>
                                        <!--end party type individual for_I_r-->






                                        <!--start department respondent for for_D_r-->

                                        <div id="for_D_r" style="display: none">
                                            <?php if($res_rw['ind_dep']=='I') {  ?>
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
                                            <?php }else{?>
                                            <?php //error_reporting(0);
                                            if($res_rw['ind_dep']=='D3'){ $disply1st_res='none';} else{ $disply1st_res='block';}
                                            $data_of_match_res_causetitle1 =$data_of_match_res_causetitle2=$data_of_match_res_causetitle3='';
                                            if(!empty($res_rw) && isset($res_rw['deptname']) && !empty($res_rw['deptname'])){
                                                $data_of_match_res_causetitle1 = strpos('sarthak'.$fetch_rw['res_name'], $res_rw['deptname']);
                                            }
                                            if(!empty($res_rw) && !empty($res_rw['deptname']) && !empty($res_rw['partysuff'])){
                                                $data_of_match_res_causetitle2 = strpos('sarthak'.$fetch_rw['res_name'], trim(str_replace($res_rw['deptname'],'',$res_rw['partysuff'])));
                                            }
                                            if(!empty($res_rw) && !empty($res_rw['addr1'])){
                                                $data_of_match_res_causetitle3 = strpos('sarthak'.$fetch_rw['res_name'], $res_rw['addr1']);
                                            }
                                            $res_statename=$res_deptt='';
                                            if (!empty($res_rw) && !empty($res_rw['deptname']) && $res_rw['partysuff']){
                                                $res_deptt=trim(str_replace($res_rw['deptname'],'',$res_rw['partysuff']));
                                            }else{$res_deptt=$res_rw['partysuff'];}
                                            ?>

                                            <div class="row">
                                                <div class="col-md-4" style="display: <?php echo $disply1st_res; ?>">
                                                    <div class="form-group row">
                                                        <label for="State" class="col-sm-5 col-form-label" id='for_D_r_sn1'>
                                                            <div class="icheck-primary d-inline">
                                                                <label for="res_causetitle1">State<span class="text-red">*</span> :</label>
                                                                <input type="checkbox" id="res_causetitle1" <?php if($data_of_match_res_causetitle1 >0) echo "checked"; ?> disabled>
                                                            </div></label>
                                                        <div class="col-sm-7" id='for_D_r_sn2'>
                                                            <input type="text" class="form-control" id="res_statename" name="res_statename" value="<?php echo $res_rw['deptname'];?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State" disabled>
                                                            <input type="hidden" id="res_statename_hd" name="res_statename_hd" value="<?php echo $res_rw['state_in_name']?>">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="State" class="col-sm-5 col-form-label">
                                                            <div class="icheck-primary d-inline">
                                                                <label for="res_causetitle2">Department<span class="text-red">*</span> :</label>
                                                                <input type="checkbox" id="res_causetitle2" name="res_causetitle2" <?php if($data_of_match_res_causetitle2 >0) echo "checked"; ?>  disabled>
                                                            </div></label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="res_deptt" name="res_deptt" value="<?php echo $res_deptt; ?>" disabled  onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department">
                                                            <input type="hidden" id="res_deptt_code" name="res_deptt_code" value="<?php echo $res_rw['deptcode']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="respondent_causetitle3" class="col-sm-5 col-form-label"><div class="icheck-primary d-inline">
                                                                <label for="res_causetitle3">Post</label>
                                                                <input type="checkbox" name="res_causetitle3" id="res_causetitle3" <?php if($data_of_match_res_causetitle3 >0) echo "checked"; ?> disabled>
                                                            </div></label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="res_post" name="res_post" value="<?php echo $res_rw['addr1'];?>" disabled onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Post">
                                                            <input type="hidden" id="res_post_code" name="res_post_code" value="<?php echo $res_rw['authcode']?>">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <?php }?>
                                            <!--start department respondent address area-->

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">Country<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="r_contd" name="r_contd" class="form-control" onchange="setCountry_state_dis(this.id,this.value)" disabled>
                                                                <option value="">Select Country</option>
                                                                <?php
                                                                foreach ($country as $row) {?>
                                                                    <option value="<?php echo $row['id']; ?>" <?php if (!empty($res_rw['country']) && $res_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                <?php   }   ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="raddd" name="raddd" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address" value="<?php echo $res_rw['addr2']?>" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Pin Code :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rpind" name="rpind" value="<?php echo $res_rw['pin']?>" disabled maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Occupation/Department" class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rcityd" name="rcityd" value="<?php echo $res_rw['dstname']?>" disabled onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selrstd" style="width:204px;" onchange="getDistrict('R',this.id,this.value)" class="form-control" disabled>
                                                                <option value="">Select State</option>
                                                                <?php
                                                                $sel ='';
                                                                foreach ($state_list as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($row['cmis_state_id']==$res_rw['state']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                    } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="Country" class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="selrdisd" name="selrdisd" class="form-control" disabled>
                                                                <option value="">Select District</option>
                                                                <?php
                                                                $sel ='';
                                                                foreach ($res_dist_list as $row) {
                                                                    if($res_rw['city']==0){echo '<option  value="' . sanitize(($res_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                    if ($row['id_no']==$res_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                    echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                    ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="rmobd" name="rmobd" value="<?php echo $res_rw['contact']?>" disabled maxlength="10"  onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="remaild" name="remaild" value="<?php echo $res_rw['email']?>" disabled onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="r_nod" value="<?php echo $fetch_rw['rno']?>"  size="3" maxlength="4"   onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
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
                                            <div id="dv_add_parties_r"></div>
                                            <?php $r_sno=0; if (!empty($additional_address_r)){ foreach ($additional_address_r as $row) { $r_sno++; } }?>
                                            <input type="hidden" name="hd_add_address_r" id="hd_add_address_r"  value="<?php echo $p_sno; ?>" />
                                        </div>

                                        <!--end extra Address respondent-->
                                        <?php
                                        $disabled='';
                                        if($fetch_rw['c_status']=='D'){
                                            /*if($_REQUEST['hd_ud']==61) {
                                                $disabled == '';
                                            }else {
                                                $disabled = " disabled ";
                                            }*/
                                            $disabled = " disabled ";
                                        }

                                        ?>
                                        <center>
                                            <div class="btn btn-primary" onclick="setStepper('2')">Previous</div>
                                            <input type="button" class="btn btn-success" value="Save" <?php if($disabled == '') {?> onclick="call_update_main()" <?php } ?> id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()" <?php echo $disabled; ?>/> &nbsp;
                                            <input type="button" class="btn btn-danger" value="Cancel" onclick="window.location.reload()"/>
                                        </center>

                                    </div>
                                    <!-- /.respondent_tab_panel -->

                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->

                            <input type="hidden" name="hd_p_barid" id="hd_p_barid" value="<?php echo $fetch_rw['pet_adv_id'];  ?>"/>
                            <input type="hidden" name="hd_r_barid" id="hd_r_barid" value="<?php echo $fetch_rw['res_adv_id'];  ?>"/>
                            <?=form_close(); ?>

                        </div> <!--end show_fil-->
                        <input type="hidden" name="hd_current_date" id="hd_current_date" value="<?php echo date('d-m-Y') ?>"/>

                        <?php  } ?>
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
    <script src="<?php echo base_url('filing/diary_update_new_filing_mod.js'); ?>"></script>

    <script>
        activate_main('selpt');
        activate_main('selrt');
    </script>
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




    </script>

    <script type="text/javascript">
        var court_type = "<?php echo $court_type;?>";

        court_type = parseInt(court_type);
        if(court_type){
            switch (court_type){ //return false;
                case 1:
                    //high court
                   // $("#radio_selected_court1").prop('checked',true).trigger('click');
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

            <?php if (!empty($additional_address_p)){ ?>
            get_additional_address_modify('P','<?=count($additional_address_p);?>');
            <?php }?>
        <?php if (!empty($additional_address_r)){ ?>
        get_additional_address_modify('R','<?=count($additional_address_r);?>');
        <?php }?>

        <?php if (!empty($petadv_info_rw)){ ?>
        is_changeAdvocate('padvt','<?=$petadv_info_rw['aor_state'];?>');
        <?php }?>
        <?php if (!empty($resadv_info_rw)){ ?>
        is_changeAdvocate('radvt','<?=$resadv_info_rw['aor_state'];?>');
        <?php }?>
        function get_additional_address_modify(p_r,total_count){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var p_r=p_r;
            var hd_add_address=total_count;
            //alert('p_r='+p_r +' hd_add_address='+hd_add_address ); //return false;
            $.ajax({
                url: '/Filing/Diary_modify/additional_address_modify',
                cache: false,
                async: true,
                data: {hd_add_address: hd_add_address,p_r:p_r,CSRF_TOKEN: CSRF_TOKEN_VALUE},

                type: 'GET',
                success: function(data, status) {

                    if(p_r=='P')
                    {
                        $('#dv_add_parties').append(data);
                    }
                    else if(p_r=='R')
                    {
                        $('#dv_add_parties_r').append(data);
                    }


                }

            });
        }
    </script>
 <?=view('sci_main_footer');?>